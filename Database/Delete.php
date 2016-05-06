<?php
namespace Letid\Database;
trait Delete
{
	static function delete()
	{
		return new self(array('DELETE' => func_get_args()));
	}
	static function drop()
	{
		return new self(
			array('DROP' => array_map(function($v){ return mb_strtoupper($v); }, func_get_args()))
		);
	}
	static function truncate()
	{
		return new self(array('TRUNCATE' => func_get_args()));
	}
	//--------------
	static function delete_from()
	{
		// The DELETE TABLE Statement is used to delete rows
		// DELETE FROM tableName
		return new self(array('DELETE FROM' => func_get_args()));
	}
	static function truncate_table()
	{
		// The TRUNCATE TABLE Statement is used to delete the data inside the table, and not the table itself
		// TRUNCATE TABLE tableName
		return new self(array('TRUNCATE TABLE' => func_get_args()));
	}
	static function drop_database()
	{
		// The DROP DATABASE statement is used to delete a database.
		// DROP DATABASE databaseName
		return new self(array('DROP DATABASE' => func_get_args()));
	}
	static function drop_table()
	{
		// The DROP TABLE statement is used to delete a table.
		// DROP TABLE tableName
		return new self(array('DROP TABLE' => func_get_args()));
	}
	static function drop_index()
	{
		// DROP INDEX Syntax for MySQL:
		// ALTER TABLE tableName DROP INDEX indexName
		return new self(array('DROP INDEX' => func_get_args()));
	}
}
/*
Database::drop('database')
	->from('databaseName');
Database::drop('table')
	->from('tableName');
Database::drop('index')
	->from('indexName')
	->alter('tableName');
Database::truncate('TABLE')
	->from('tableName');
Database::delete()
	->from('tableName');
*/