<?php
namespace Letid\Db;
abstract class Request extends Connection
{
	// public $data, $list, $total;
	/*
	table, fields = '*', where = NULL, $order = NULL, limit = NULL, offset = NULL
	data,list,info,result, listNum,listRow
	*/
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
