<?php
namespace Letid\Meta;
abstract class Application
{
	use Score;
	public function __construct()
    {
        // constructor!
    }
	public function setScore($name, $value)
	{
		self::$scoreVar[$name] = $value;
	}
	public function getScore($name)
	{
		return self::$scoreVar[$name];
	}
	public function __set($name, $value)
	{
		$this->{$name} = $value;
	}
	public function __get($name)
	{
		if(property_exists($this, $name)) {
			return $this->{$name};
		} else {
			return $this->getScore($name);
		}
	}
    public function __call($name, $arguments)
	{
		if(method_exists($this, $name) == false) return $this;
    }
	public function __toString()
	{
		// return isset($this->tostring)?$this->tostring:null;
	}
}
