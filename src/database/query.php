<?php
namespace letId\database;
trait query
{
	static function query()
	{
		return new self(func_get_args());
	}
	public function toArray($rowsName=self::rowsName)
	{
		// HACK: MYSQLI_ASSOC, MYSQLI_BOTH, MYSQLI_NUM, $Assoc=MYSQLI_ASSOC
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_all(MYSQLI_ASSOC);
		}
		return $this;
	}
	public function toObject($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_object();
		}
		return $this;
	}
	public function toJson($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_all(MYSQLI_NUM);
			// $db->free();
			$db->free_result();
			self::close();
		}
		return $this;
	}
	public function rowArray($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_row();
			// $this->{$rowsName} = $db->fetch_fields();
		}
		return $this;
	}
	public function rowsCount()
	{
		return call_user_func_array(array($this, 'rows_count'),func_get_args());
	}
	public function rowsTotal()
	{
		return call_user_func_array(array($this, 'rows_total'),func_get_args());
	}
	public function rowsAffected()
	{
		return call_user_func_array(array($this, 'rows_count'),func_get_args());
	}
	public function rowsId()
	{
		return call_user_func_array(array($this, 'rows_count'),func_get_args());
	}
}
