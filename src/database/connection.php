<?php
namespace letId\database;
class connection
{
	const rowsName		= 'rows';
	const rowsId		= 'rowsId';
	const rowsAffected	= 'rowsAffected';
	const rowsCount		= 'rowsCount';
	const rowsTotal		= 'rowsTotal';
	private static $fn 	= array(
		'select'=>array('on_select','selecting'),
		'update'=>array('on_update','updating'),
		'insert'=>array('on_insert','inserting'),
		'delete'=>array('on_update','deleting')
	);
	public $queries=array();
	public function connection($d)
	{
		$this->mysqli($d);
	}
	private function mysqli($d)
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		try {
			avail::$databaseConnection = new \mysqli($d['host'], $d['username'], $d['password'], $d['database']);
			if (isset($d['charset'])) avail::$databaseConnection->set_charset($d['charset']);
		} catch (\Exception $e ) {
			avail::content('msg')->set($e->getMessage());
			avail::content('code')->set($e->getCode());
			// avail::$databaseConnection  = (object) array('connect_errno' => $e->getCode(), 'connect_error' => $e->getMessage());
			// avail::$databaseConnection  = (object) array('connect_errno' => $e->getCode(), 'connect_error' => $e->getMessage());
		}
	}
	public function errorConnection()
	{
		return !is_object(avail::$databaseConnection);
		// return avail::$databaseConnection->connect_errno ?? is_string(avail::$databaseConnection);
	}
	/*
	mysqli_close()
	mysqli_result::close()
	*/
	public function close()
	{
		avail::$databaseConnection->close();
	}
	/*
	mysqli_result::free()
	NOTE: not in use, because each fetch method can do it!
	*/
	public function free()
	{
		if (self::is_result()) {
			$this->result->free();
		}
	}
	/*
	mysqli_free_result(self::is_result());
	mysqli_result::free_result()
	NOTE: not in use, because each fetch method can do it!
	*/
	public function free_result()
	{
		if (self::is_result()) {
			$this->result->free_result();
		}
	}
	public function execute()
	{
		$this->result=avail::$databaseConnection->query($this->build()->query);
		$this->is_error();
		return $this;
	}
	public function prepare()
	{
		return $this;
	}
	protected function terminal()
	{
		$this->queries = func_get_args()[0][0];
		if (is_object(avail::$databaseConnection)) {
			return $this->queries;
		} else {
			$this->error = true;
			$this->msg = avail::$databaseConnection;
		}
	}
	protected function queries($name,$args)
	{
		if (isset($args)) {
			if (is_callable($args)) {
				// NOTE: might not work,
				// $this->queries[$name]=call_user_func($args, $this->queries, $name);
				$this->queries[$name]=call_user_func_array($args, array($this->queries, $name));
			} elseif (is_callable($name)) {
				// $this->queries=call_user_func($name, $this->queries, $args);
				$this->queries=call_user_func_array($name, array($this->queries, $args));
			} elseif($args[0]) {
				$this->queries[$name]=$args;
			}
		} else {
			$this->queries[]=$name;
		}
		return $this;
	}
	protected function rows_count()
	{
		if ($fNR=self::rows_num()) {
			if (is_callable(array($this, $fNR))) {
				call_user_func_array(array($this, $fNR),func_get_args());
			}
		}
		return $this;
	}
	protected function rows_total($Id=self::rowsTotal)
	{
		if (stripos($this->query,'SQL_CALC_FOUND_ROWS')) {
			$this->{$Id}=avail::$databaseConnection->query('SELECT FOUND_ROWS();')->fetch_row()[0];
		}
		return $this;
	}
	private function rows_num()
	{
		$i = strtolower(strtok($this->query, avail::SlS));
		if (isset(self::$fn[$i])) {
			return self::$fn[$i][0];
		} else {
			return $i;
		}
	}
	protected function is_result()
	{
		if (is_object($this->result)) {
			return $this->result;
		}
	}
	protected function is_error()
	{
		if (avail::$databaseConnection->errno){
			$this->msg = avail::$databaseConnection->error;
			return $this->error = avail::$databaseConnection->errno;
		}
	}
	private function on_insert($Id=self::rowsId)
	{
		return $this->{$Id} = avail::$databaseConnection->insert_id; // mysqli_insert_id(avail::$databaseConnection)
	}
	private function on_update($Id=self::rowsAffected)
	{
		return $this->{$Id} = avail::$databaseConnection->affected_rows; // mysqli_affected_rows(avail::$databaseConnection)
	}
	private function on_select($Id=self::rowsCount)
	{
		if ($this->is_result()) {
			return $this->{$Id} = $this->result->num_rows; // mysqli_num_rows($this->data)
		}
	}
}
