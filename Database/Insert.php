<?php
namespace Letid\Database;
trait Insert
{
	static function insert()
	{
		return new self(array('INSERT INTO' => func_get_args()));
	}
	public function value()
	{
		return $this->queries('VALUES',func_get_args());
	}
	public function to()
	{
		return $this->queries(function($q, $args){
			$k = key($q);
			if (isset($q['VALUES'])) {
				$q[$k]=array($args[0] => (is_array($q[$k][0]))?$q[$k][0]:array_values($q[$k]));
			} else {
				$q['SET']=$q[$k][0];
				$q[$k] = $args;
			}
			return $q;
		},func_get_args());
	}
}
/*
uniqid(); NOW(); date('Y-m-d G:i:s');
Database::insert(
		array('username'=>1, 'password'=>2)
	)
	->to(
		'tableName'
	)
	->build();
HACK: INSERT INTO tableName SET username = '1', modified = '2';

Database::insert(
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
	->rowsId();
HACK: INSERT INTO tableName (username,password) VALUES ('1','2'), ('3','4');
*/