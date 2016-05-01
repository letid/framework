<?php
namespace Letid\Id;
abstract class Application
{
	use Common;
	public function __construct()
	{
	}
	public function __set($name, $value)
	{
		$this->{$name} = $value;
	}
	public function __get($name)
	{
		if(property_exists($this, $name)) {
			return $this->{$name};
		}
	}
	public function __call($name, $arguments)
	{
		// if(method_exists($this, $name) == false) return $this;
	}
	/*
	public function __toString()
	{
	}
	*/
}