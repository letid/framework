<?php
namespace Letid\Id;
abstract class Application
{
	use Common;
	static $scoop;
	protected function scoop_set()
	{
		if (func_get_args()) {
			return self::$scoop=func_get_args()[0];
		}
	}
	protected function scoop_get()
	{
		if (func_get_args()) {
			if (self::$scoop->{func_get_args()[0]}) {
				return self::$scoop->{func_get_args()[0]};
			}
		} else {
			return self::$scoop;
		}
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
		return $this;
	}
}