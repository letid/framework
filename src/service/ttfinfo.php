<?php
 /*
	$obj = new ttfinfo;
	$obj->setFile($filename);
	return $obj->readFile();

 	$obj = new ttfinfo;
	$obj->setDir($directory);
	$obj->readDir();
	return $obj->data;
 */
 namespace letId\service
 {
	 abstract class ttfinfo {
	 	/**
	 	* Restrict the resource pointer to this directory and above.
	 	* Change to 1 for to allow the class to look outside of it current directory
	 	*/
	 	protected $dirRestriction = 1;
	 	/**
	 	* Restrict the resource pointer to this directory and above.
	 	* Change to 1 for nested directories
	 	*/
	 	protected $dirRecursive = 0;
	 	protected $directory;
	 	protected $filename;
	 	/**
	 	* set the filename
	 	* @param string $data the new value
	 	* @return object reference to this
	 	*/
	 	public function setFile($file)
	 	{
	 		if ($this->dirRestriction && preg_match('[\.\/|\.\.\/]', $file))
	 		{
	 			$this->exitClass('Error: Directory restriction is enforced!');
	 		}

	 		$this->filename = $file;
	 		return $this;
	 	}

	 	/**
	 	* set the Font Directory
	 	* @param string $data the new value
	 	* @return object referrence to this
	 	*/
	 	public function setDir($dir)
	 	{
	 		if ($this->dirRestriction && preg_match('[\.\/|\.\.\/]', $dir))
	 		{
	 			$this->exitClass('Error: Directory restriction is enforced!');
	 		}

	 		$this->directory = $dir;
	 		return $this;
	 	}

	 	/**
	 	* @return information contained in the TTF 'name' table of all fonts in a directory.
	 	*/
	 	public function readDir()
	 	{
	 		if (empty($this->directory)) { $this->exitClass('Error: Fonts Directory has not been set with setDir().'); }
	 		if (empty($this->dirBackup)){ $this->dirBackup = $this->directory; }

	 		$this->data = array();
	 		$d = dir($this->directory);

	 		while (false !== ($e = $d->read()))
	 		{
	 			if($e != '.' && $e != '..')
	 			{
	 				$e = $this->directory . $e;
	 				if($this->dirRecursive && is_dir($e))
	 				{
	 					$this->setDir($e);
	 					$this->data = array_merge($this->data, readDir());
	 				}
	 				else if ($this->is_ttf($e) === true)
	 				{
	 					$this->setFile($e);
	 					$this->data[$e] = $this->readFile();
	 				}
	 			}
	 		}
	 		if (!empty($this->dirBackup)){ $this->directory = $this->dirBackup; }
	 		$d->close();
	 		return $this;
	 	}

	 	/*
	 	* function setProtectedVar()
	 	* @public
	 	* @param string $var the new variable
	 	* @param string $data the new value
	 	* @return object reference to this

	 	* DISABLED, NO REAL USE YET

	 	public function setProtectedVar($var, $data)
	 	{
	 		if ($var == 'filename')
	 		{
	 			$this->setFile($data);
	 		} else {
	 			//if (isset($var) && !empty($data))
	 			$this->$var = $data;
	 		}
	 		return $this;
	 	}
	 	*/
	 	/**
	 	* @return information contained in the TTF 'name' table.
	 	*/
	 	public function readFile()
	 	{
	 		$info = NULL;
      // $number_name_records_dec = 0;
      // $offset_storage_dec = 0;
	 		$fd = fopen ($this->filename, "r");
	 		$this->text = fread ($fd, filesize($this->filename));
	 		fclose ($fd);
	 		$number_of_tables = hexdec($this->dec2ord($this->text[4]).$this->dec2ord($this->text[5]));
	 		for ($i=0;$i<$number_of_tables;$i++)
	 		{
	 			$tag = $this->text[12+$i*16].$this->text[12+$i*16+1].$this->text[12+$i*16+2].$this->text[12+$i*16+3];

	 			if ($tag == 'name')
	 			{
	 				$this->ntOffset = hexdec(
	 					$this->dec2ord($this->text[12+$i*16+8]).$this->dec2ord($this->text[12+$i*16+8+1]).
	 					$this->dec2ord($this->text[12+$i*16+8+2]).$this->dec2ord($this->text[12+$i*16+8+3]));

	 				$offset_storage_dec = hexdec($this->dec2ord($this->text[$this->ntOffset+4]).$this->dec2ord($this->text[$this->ntOffset+5]));
	 				$number_name_records_dec = hexdec($this->dec2ord($this->text[$this->ntOffset+2]).$this->dec2ord($this->text[$this->ntOffset+3]));
	 			}
	 		}

	 		$storage_dec = $offset_storage_dec + $this->ntOffset;
	 		$storage_hex = strtoupper(dechex($storage_dec));

	 		for ($j=0;$j<$number_name_records_dec;$j++)
	 		{
	 			$platform_id_dec	= hexdec($this->dec2ord($this->text[$this->ntOffset+6+$j*12+0]).$this->dec2ord($this->text[$this->ntOffset+6+$j*12+1]));
	 			$name_id_dec		= hexdec($this->dec2ord($this->text[$this->ntOffset+6+$j*12+6]).$this->dec2ord($this->text[$this->ntOffset+6+$j*12+7]));
	 			$string_length_dec	= hexdec($this->dec2ord($this->text[$this->ntOffset+6+$j*12+8]).$this->dec2ord($this->text[$this->ntOffset+6+$j*12+9]));
	 			$string_offset_dec	= hexdec($this->dec2ord($this->text[$this->ntOffset+6+$j*12+10]).$this->dec2ord($this->text[$this->ntOffset+6+$j*12+11]));

	 			if (!empty($name_id_dec) and empty($info[$name_id_dec]))
	 			{
	 				for($l=0;$l<$string_length_dec;$l++)
	 				{
	 					if (ord($this->text[$storage_dec+$string_offset_dec+$l]) == '0') {
							continue;
						} else {
	 						$info[$name_id_dec][] = $this->text[($storage_dec+$string_offset_dec+$l)];
	 					}
	 				}
	 			}
	 		}
	 		return $info;
	 	}

	 	/**
	 	* @return 'Copyright notice' contained in the TTF 'name' table at index 0
	 	*/
	 	public function copyright()
	 	{
	 		$i = $this->readFile();
	 		return $i[0];
	 	}
	 	/**
	 	* @return 'Font Family name' contained in the TTF 'name' table at index 1
	 	*/
	 	public function family()
	 	{
	 		$i = $this->readFile();
	 		return $i[1];
	 	}
	 	/**
	 	* @return 'Font Subfamily name' contained in the TTF 'name' table at index 2
	 	*/
	 	public function subfamily()
	 	{
	 		$i = $this->readFile();
	 		return $i[2];
	 	}
	 	/**
	 	* @return 'Unique font identifier' contained in the TTF 'name' table at index 3
	 	*/
	 	public function identifier()
	 	{
	 		$i = $this->readFile();
	 		return $i[3];
	 	}
	 	/**
	 	* @return 'Full font name' contained in the TTF 'name' table at index 4
	 	*/
	 	public function fullname()
	 	{
	 		$i = $this->readFile();
	 		return $i[4];
	 	}
	 	/**
	 	* Used to lessen redundant calls to multiple functions.
	 	*/
	 	protected function dec2ord($dec)
	 	{
	 		return $this->dec2hex(ord($dec));
	 	}
	 	/**
	 	* private function to perform Hexadecimal to decimal with proper padding.
	 	*/
	 	protected function dec2hex($dec)
	 	{
	 		return str_repeat('0', 2-strlen(($hex=strtoupper(dechex($dec))))) . $hex;
	 	}
	 	/**
	 	* private function to perform Hexadecimal to decimal with proper padding.
	 	*/
	 	protected function exitClass($msg)
	 	{
	 		return $msg;
      // it;
	 	}
	 	/**
	 	* private helper function to test in the file in question is a ttf.
	 	*/
	 	protected function is_ttf($file)
	 	{
	 		$ext = explode('.', $file);
	 		$ext = $ext[count($ext)-1];
	 		return preg_match("/ttf$/i",$ext) ? true : false;
	 	}
	 }
}
?>
