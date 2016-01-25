<?php
namespace Lethil;

class Utility
{
	public $tostring;
	// public function __construct($e) {
	// {
	// 	$this->tostring=$e;
	// }
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
