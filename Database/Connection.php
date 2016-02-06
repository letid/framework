<?php
namespace Letid\Database;
use \mysqli as letDb;
class Connection
{
	protected static $db='Unavailable';
	const SP=' ';
	const rowsName='rows';
	const rowsId='rowsId';
	const rowsAffected='rowsAffected';
	const rowsCount='rowsCount';
	const rowsTotal='rowsTotal';

	protected static $rowsTotalNum;

	private static $fn = array(
		'select'=>array('ed'),
		'update'=>array('d'),
		'insert'=>array('ed')
	);
	// public $msg, $error;
	/*
	inquiry,config,queries
	*/
	static function Connectivity($d)
	{
		if (is_array($d)) {
			if(strpos($d['host'],'/cloudsql/') !== false) {
				self::$db = new letDb(NULL, $d['username'], $d['password'], $d['database'], NULL, $d['host']);
			} else {
				self::$db = new letDb($d['host'], $d['username'], $d['password'], $d['database']);
			}
			// self::$db->set_charset("utf8");
			return self::$db;
		}
	}
	protected function close()
	{
		// mysqli_close()
		self::$db->close();
	}
	protected function queries()
	{
		return self::$db->query(func_get_args()[0]);
	}
	protected function inquiry()
	{
		$this->query=func_get_args()[0][0];
		if (is_object(self::$db)) {
			$result = self::queries($this->query);
			if (stripos($this->query,'SQL_CALC_FOUND_ROWS')) {
				self::rows_total_num();
			}
			return $this->result = $result;
		} else {
			$this->error = true;
			$this->msg = self::$db;
		}
	}
	protected function is_result()
	{
		if (is_object($this->result)) {
			return $this->result;
		}
	}
	protected function rows_count()
	{
		if ($this->result) {
			if ($fn = self::is_fn()) {
				if (is_callable(array($this, $fn))) {
					call_user_func_array(array($this, $fn),func_get_args());
				} else {
					$this->{$fn} = $result;
				}
			}
		}
		return $this;
	}
	protected function rows_total_num()
	{
		return self::$rowsTotalNum=self::queries('SELECT FOUND_ROWS();')->fetch_row()[0];
	}
	protected function rows_total($rowsTotal=self::rowsTotal)
	{
		if (self::$rowsTotalNum) {
			$this->{$rowsTotal} = self::$rowsTotalNum;
		}
		return $this;
	}
	protected function is_error()
	{
		if (self::$db->errno){
			$this->msg = self::$db->error;
			return $this->error = self::$db->errno;
		}
	}
	protected function is_fn()
	{
		$f = strtolower(strtok($this->query, self::SP));
		if ($n=self::$fn[$f]) {
			return $f.$n[0];
		} else {
			return $f;
		}
	}
	protected function inserted($Name=self::rowsId)
	{
		//mysqli_insert_id(self::$db)
		return $this->{$Name} = self::$db->insert_id;
	}
	protected function updated($Name=self::rowsAffected)
	{
		//mysqli_affected_rows(self::$db)
		return $this->{$Name} = self::$db->affected_rows;
	}
	protected function selected($Name=self::rowsCount)
	{
		// mysqli_num_rows($this->data)
		return $this->{$Name} = $this->result->num_rows;
	}
}
