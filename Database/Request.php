<?php
namespace Letid\Database;
class Request extends Connection
{
	use Query, Build, Insert, Select, Update, Delete;
	public function __construct()
	{
		if (!$this->terminal(func_get_args())) {
			$this->is_error();
		}
	}
	// private static function is_arguments($args,$add='')
	// {
	// 	if (isset($args)) {
	// 		if (is_array($args)) {
	// 			return join(', ',$args);
	// 		} elseif (!is_string($args) && $add) {
	// 			return $add;
	// 		}
	// 		return $args;
	// 	}
	// 	return $add;
	// }
	public function __call($name, $arguments)
    {
		return $this;
    }
}
