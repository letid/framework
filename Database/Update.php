<?php
namespace letId\database;
trait update
{
	static function update()
	{
		return new self(array('UPDATE' => func_get_args()));
	}
}
/*
Database::update(
		array('username'=>1, 'password'=>2)
	)
	->to(
		'tableName'
	)
	->build();
HACK: UPDATE tableName SET username = '1', modified = '2';

Database::update(
		array('username', 'password')
		// SAME AS ABOVE: 'username', 'password'
	)
	->value(
		array(1,2),
		array(3,4)
	)
	->to(
		'tableName'
	)
	->execute()
	->rowsAffected();
HACK: UPDATE tableName (username,password) VALUES ('1','2'), ('3','4');
*/