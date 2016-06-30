<?php
namespace Letid\Database;
class Request extends Connection
{
	use Query, Build, Insert, Select, Update, Delete;
	public function __construct()
	{
		if (func_get_args()) {
			if (!$this->terminal(func_get_args())) $this->is_error();
		}
	}
	public function alter()
	{
		return $this->queries(function($queue, $args){
			return array_merge(array('ALTER TABLE' => $args), $queue);
		},func_get_args());
	}
	
	public function duplicateUpdate()
	{
		return $this->queries('ON DUPLICATE KEY UPDATE',func_get_args());
	}
	public function duplicateIgnore()
	{
		return $this->queries('ON DUPLICATE KEY IGNORE',func_get_args());
	}
}
