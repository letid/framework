<?php
namespace Letid\Config;
use Letid\Init;
use Letid\Database;
class Http extends Lethil
{
	public function Request()
    {
		if (self::Host()) {
			if (self::Root(static::Root().$this->application)) {
				self::Registration();
				if ($init=self::hasInitiateClass(static::ANS.self::INT)) {
					if ($db=call_user_func(array($init, Database))) {
						print_r($db);
						$this->database=Database\Connection::Connectivity(array_merge($this->database,$db));
						if($this->database->connect_errno){
							return self::Response(File::$Config['invalid'],$this->database->connect_error);
						}
					}
					// if (method_exists($init, Application))
					// if (is_callable(array($init, Application)))
					call_user_func(array($init, Before));
					// ob_start();
					self::$Content = call_user_func(array($init, Application));
					// ob_get_contents();
					// ob_get_clean();
					// ob_end_clean();
					call_user_func(array($init, After));
				}
			} else {
				self::Response(File::$Config['invalid'],'No Application directory!');
			}
		} else {
			self::Response(File::$Config['invalid'],'No Application exists!');
		}
    }
	private function hasInitiateClass($AnInit)
    {
		if (class_exists($AnInit)) {
			return new $AnInit();
		} else {
			self::Response(File::$Config['invalid'],'No Application initiate!');
		}
	}
	private function Response($file,$msg)
    {
		self::Root(static::Message());
		ob_start();
		include self::Included($file);
		self::$Content = ob_get_clean();
	}
	private function Included($fileName)
    {
		if (file_exists($o=$this->directory['root'].$fileName.File::$Extension)) {
			return $o;
		} else {
			return File::$Response.$fileName.File::$Extension;
		}
	}
	private function Registration()
    {
		$loader = new \Composer\Autoload\ClassLoader();
		$loader->addPsr4(static::ANS.self::SLB,array($this->directory['root']),true);
		$loader->register(true);
		// $loader->setUseIncludePath(true);
	}
	private function Host()
    {
		// session_unset($_SESSION[self::$host]);
		if ($_SESSION[self::$host]) {
			$app=$_SESSION[self::$host];
		} else if($this->application && $Name=self::Reg()) {
			$app=$_SESSION[self::$host]=$Name;
		} else if($this->default) {
			$app=$_SESSION[self::$host]=$this->default;
		} else {
			$app=array();
		}
		return $this->application=$app;
	}
	private function Reg()
    {
		foreach ($this->application as $Name => $Regex) {
			if ($Regex && self::Match(is_array($Regex)?$Regex:array($Regex))) {
				return $Name;
			}
		}
	}
	private function Match($Regex)
    {
		foreach ($Regex as $Name) {
			if (preg_match("/$Name/", self::$host)) {
				return true;
			}
		}
	}
	private function Root($dir)
    {
		if (file_exists($dir.self::SLA)) {
			if (is_array($this->directory)) {
				foreach ($this->directory as $id => $name) {
					$this->directory[$id] = $dir.self::SLA.$name.self::SLA;
				}
			}
			return $this->directory['root'] = $dir.self::SLA;
		}
	}
}
