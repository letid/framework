<?php
namespace letId\database;
trait query
{
	static function query()
	{
		return new self(func_get_args());
	}
	public function fetchAll($rowsAssoc=MYSQLI_ASSOC,$rowsName=self::rowsName)
	{
		// HACK: MYSQLI_ASSOC, MYSQLI_BOTH, MYSQLI_NUM, $Assoc=MYSQLI_ASSOC
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_all($rowsAssoc);
		}
		return $this;
	}
	public function fetchObject($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_object();
		}
		return $this;
	}
	public function fetchAssoc($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_assoc();
		}
		return $this;
	}
	public function fetchGroup($group=null,$rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			while($r=$db->fetch_assoc()) {
				if (isset($r[$group])) $this->{$rowsName}[$r[$group]][]=$r;
					else $this->{$rowsName}[] = $r;
			}
		}
		return $this;
	}
	public function fetchJson($rowsAssoc=MYSQLI_NUM,$rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_all($rowsAssoc);
			// $db->free();
			$db->free_result();
			self::close();
		}
		return $this;
	}
	public function fetchRow($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_row();
		}
		return $this;
	}
	public function fetchfield($rowsName=self::rowsName)
	{
		if ($db=self::is_result()) {
			$this->{$rowsName} = $db->fetch_fields();
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