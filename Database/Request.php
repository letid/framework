<?php
namespace Letid\Database;
class Request extends Connection
{
	use Query, Build, Insert, Select, Update, Delete;
	public function __construct()
	{
		if (!$this->terminal(func_get_args())) $this->is_error();
	}
	public function alter()
	{
		return $this->queries(function($queue, $args){
			return array_merge(array('ALTER TABLE' => $args), $queue);
		},func_get_args());
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
