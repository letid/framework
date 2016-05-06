<?php
namespace Letid\Database;
abstract class Connection
{
	public static $db	= 'Unavailable';
	const SP			= ' ';
	const ED			= ';';
	const rowsName		= 'rows';
	const rowsId		= 'rowsId';
	const rowsAffected	= 'rowsAffected';
	const rowsCount		= 'rowsCount';
	const rowsTotal		= 'rowsTotal';
	private static $fn 	= array(
		'select'=>array('on_select','selecting'),
		'update'=>array('on_update','updating'),
		'insert'=>array('on_insert','inserting')
	);
	protected function close()
	{
		self::$db->close(); // mysqli_close()
	}
	public function execute()
	{
		$this->build();
		if (!self::$db->query($this->query)) {
			$this->is_error();
		}
		return $this;
	}
	protected function terminal()
	{
		$this->queries = func_get_args()[0][0];
		if (is_object(self::$db)) {
			return $this->queries;
		} else {
			$this->error = true;
			$this->msg = self::$db;
		}
	}
	protected function queries($name,$args)
	{
		if (isset($args)) {
			if (is_callable($args)) {
				$this->queries[$name]=call_user_func($args, $this->queries, $name);
			} elseif (is_callable($name)) {
				$this->queries=call_user_func($name, $this->queries, $args);
			} else {
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
			$this->{$Id}=self::$db->query('SELECT FOUND_ROWS();')->fetch_row()[0];
		}
		return $this;
	}
	private function rows_num()
	{
		$i = strtolower(strtok($this->query, self::SP));
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
		if (self::$db->errno){
			$this->msg = self::$db->error;
			return $this->error = self::$db->errno;
		}
	}
	private function on_insert($Id=self::rowsId)
	{
		return $this->{$Id} = self::$db->insert_id; // mysqli_insert_id(self::$db)
	}
	private function on_update($Id=self::rowsAffected)
	{
		return $this->{$Id} = self::$db->affected_rows; // mysqli_affected_rows(self::$db)
	}
	private function on_select($Id=self::rowsCount)
	{
		if ($this->is_result()) {
			return $this->{$Id} = $this->result->num_rows; // mysqli_num_rows($this->data)
		}
	}
}
