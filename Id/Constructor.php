<?php
namespace Letid\Id;
trait Constructor
{
	use Core;
	public function __construct()
    {
        // constructor!
    }
	public function setConfig($name, $value)
	{
		Config::$list[$name] = $value;
	}
	public function getConfig($name)
	{
		return Config::$list[$name];
	}
	public function rowConfig()
	{
		return Config::$list;
	}
	public function config($k,$v='')
	{
		if ($k) {
			if ($v) {
				return $this->setConfig($k,$v);
			} else {
				return $this->getConfig($k);
			}
		}
	}
	public function lang($k)
	{
		if ($k && isset(Config::$language[$k])) {
			return Config::$language[$k];
		} else {
			return $k;
		}
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
	// public function __toString()
	// {
	// }
}
