<?php
namespace Letid\Config;
class Lethil
{
	const SLA = '/', SLB = '\\';
	const INT = '\Initiate';
	const PAE = '\Pages';
	protected static $host,$uri,$error,$warning, $message=array(),$Header,$Content, $ContentType;
	protected $tostring;
	public function __construct()
	{
		session_start();
		$uri 						= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		self::$uri 					= explode(self::SLA, $uri);
		self::$host					= $_SERVER['HTTP_HOST'];
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
	public function meget($q='')
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
		// if(method_exists($this, $name) == false) return $this;
    }
	public function __toString()
	{
		return isset($this->tostring)?$this->tostring:null;
	}
}
