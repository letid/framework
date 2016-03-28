<?php
namespace Letid\Request;
use Letid\Db;
/*
use Letid\Request\DbQuery;
DbQuery::load("SELECT id FROM tables WHERE name LIKE 'Jo%'")->toArray()->hasCount();
DbQuery::load("SELECT SQL_CALC_FOUND_ROWS id,name FROM tables WHERE name LIKE 'Jo%' LIMIT 3, 1;")->rowsTotal()->rowsCount()->toArray();
DbQuery::load("UPDATE tables SET name='Kent2s' WHERE id ='0000000003';")->rowsCount();
class DbQuery extends Query
*/
class DbQuery extends Db\Request
{
	static function load()
	{
		return new self(func_get_args()[0]);
	}
	public function toArray($rowsName=self::rowsName)
	{
		if ($data=self::is_result()) {
			$this->{$rowsName} = $data->fetch_all(MYSQLI_ASSOC);
		}
		return $this;
	}
	public function rowsCount()
	{
		return call_user_func_array(array($this, rows_count),func_get_args());
	}
	public function rowsTotal()
	{
		return call_user_func_array(array($this, rows_total),func_get_args());
	}
}
