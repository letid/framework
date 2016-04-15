<?php
namespace Letid\Request;
use Letid\Db;
class Database extends Db\Query
{
}
/*
Database::load(
Database::select()->from()->where()
Database::insert()->to()
Database::update()->to()->where()
Database::query()
INSERT INTO {$this->users_table} SET $rowsData, created = NOW(), modified = NOW()
SELECT id FROM {$this->users_table} WHERE $row = '$value'

table, fields = '*', where = NULL, $order = NULL, limit = NULL, offset = NULL
data,list,info,result, listNum,listRow

*/
/*
class Database extends Db\Request
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
*/