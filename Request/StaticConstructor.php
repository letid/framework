<?php
namespace Letid\Request;
abstract class StaticConstructor
{
	use Application;
	static $iClass;
	public function __construct($name,$arguments)
	{
        $this->{$name} = $arguments;
		// foreach (func_get_args() as $argument) {
		// 	if (is_object($argument)) {
		// 		$customClass = $argument;
		// 	} else if (is_array($argument)) {
		// 		$setting = $argument;
		// 	}
		// }
	}
	protected function settingConfiguration($argument)
    {
		return self::$iClass->{$argument};
    }
	public function __call($name, $arguments)
    {
		return $this;
    }
}