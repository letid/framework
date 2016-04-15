<?php
namespace Letid\Db;
abstract class Request extends Connection
{
	public function __construct()
	{
		if (!$this->inquiry(func_get_args())) {
			$this->is_error();
		}
	}
	public function __call($name, $arguments)
    {
		return $this;
    }
}
