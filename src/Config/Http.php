<?php
namespace Lethil\Config;
class Http
{
	const SLA = '/';
	const SLB = '\\';
	protected static $host,$uri,$error,$warning,
		$message=array(),
		$Header,$Content,$ContentType;
	protected static $setting=array();
	protected $tostring;
	public function __construct()
	{
		session_start();
		$uri 						= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		self::$uri 					= explode(self::SLA, $uri);
		self::$host					= $_SERVER['HTTP_HOST'];
	}
	public function Request()
    {
		// session_unset($_SESSION[self::$host]);
		if ($Name=self::getApplicationName()) {
			self::$setting['name'] = $Name;
		} else if($this->Application && $Name=self::isApplication()) {
			self::$setting['name'] = self::setApplicationName($Name);
		} else if($this->Default) {
			self::$setting['name'] = self::setApplicationName($this->Default);
		}
		if(self::$setting['name']) {
			if(is_dir(static::Root())) {
				if(self::getRoot(static::Root().self::$setting['name'].self::SLA)){
					self::setLoader();
					// self::$Content = \App\Abc::createApplication();
				} else if(self::getRoot(static::Error())) {
					self::$Content = require self::$setting['dir']."invalid.configuration.html";
				} else {
					self::$Content = 'Invalid directory configuration!';
				}
			} else if(self::getRoot(static::Error())) {
				self::$Content = include self::$setting['dir']."invalid.configuration.html";
			} else {
				self::$Content = 'Invalid directory configuration!';
			}
		} else {
			if(self::getRoot(static::Error())) {
				self::$Content = require_once self::$setting['dir']."invalid.configuration.html";
			} else {
				self::$Content = 'Invalid directory configuration!';
			}
		}
    }
	private function setLoader()
    {
		$loader = new \Composer\Autoload\ClassLoader();
		// $loader->add($this->Name(),self::$setting['dir'],true);
		$loader->addPsr4($this->Name().self::SLB,array(self::$setting['dir']),true);
		// activate the autoloader
		$loader->register(true);
		// to enable searching the include path (eg. for PEAR packages)
		// $loader->setUseIncludePath(true);
		// print_r($loader);
	}
	private function isApplication()
    {
		foreach ($this->Application as $Name => $Regex) {
			if($Regex && self::isRegex(is_array($Regex)?$Regex:array($Regex))) {
				return $Name;
			}
		}
	}
	private function isRegex($Regex)
    {
		foreach ($Regex as $Name) {
			if(preg_match("/$Name/", self::$host)){
				return true;
			}
		}
	}
	private function setApplicationName($Name)
    {
		return $_SESSION[self::$host]=$Name;
	}
	private function getApplicationName()
    {
		if (isset($_SESSION[self::$host])) {
			return $_SESSION[self::$host];
		}
	}
	private function getRoot($d)
    {
		if(file_exists($d)) {
			return self::$setting['dir'] = $d;
		}
		// return is_dir($q);
		// return file_exists($file);
	}
	public function meset($q)
    {
		if(is_array($q)) {
			foreach($q as $msg) {
				array_push(self::$message,$msg);
			}
		} else {
			array_push(self::$message,$q);
		}
    }
	public function meget($q=' ')
    {
		return implode($q,self::$message);
    }
	public function __set($name, $value)
	{
		$this->{$name} = $value;
	}
	public function __get($name)
	{
		if(property_exists($this, $name)) return $this->{$name};
	}
    public function __call($name, $arguments)
	{
		if(method_exists($this, $name) == false) return $this;
    }
	public function __toString()
	{
		return isset($this->tostring)?$this->tostring:null;
	}
}
