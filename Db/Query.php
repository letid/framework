<?php
namespace Letid\Db;
class Query extends Request
{
	static function load()
	{
		return new self(func_get_args()[0]);
	}
	public function toArray($rowsName=self::rowsName)
	{
		// HACK: MYSQLI_ASSOC, MYSQLI_BOTH, MYSQLI_NUM, $Assoc=MYSQLI_ASSOC
		if ($data=self::is_result()) {
			$this->{$rowsName} = $data->fetch_all(MYSQLI_ASSOC);
		}
		return $this;
	}
	/*
	public function toObject($rowsName=self::rowsName)
	{
		if ($data=self::is_result()) {
			$this->{$rowsName} = $data->fetch_object();
		}
		return $this;
	}
	public function toJson($rowsName=self::rowsName)
	{
		if ($data=self::is_result()) {
			$this->{$rowsName} = $data->fetch_all(MYSQLI_NUM);
			// $data->free();
			// mysqli_free_result($data);
			// self::close();
		}
		return $this;
	}
	*/
	public function rowsCount()
	{
		return call_user_func_array(array($this, rows_count),func_get_args());
	}
	public function rowsTotal()
	{
		return call_user_func_array(array($this, rows_total),func_get_args());
	}
	/*
	public function aTest()
	{
		self::a1()->a4()->a3();
		// self::is_list()->f()->a3();
	}
	private function a1()
	{
		echo 'A1->';
		return $this;
	}
	private function a2()
	{
		echo 'A2->';
		// return $this;
	}
	private function a3()
	{
		echo 'A3->';
		return $this;
	}
	*/
}
