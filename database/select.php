<?php
namespace letId\database;
trait select
{
	static function select()
	{
		return new self(array('SELECT' => array(func_get_args())));
	}
	public function rowsCalc()
	{
		return $this->queries('SELECT',function($q,$name){
			return array('SQL_CALC_FOUND_ROWS'=>$q[$name][0]);
		});
	}
	public function from()
	{
		return $this->queries(function($q, $args){
			if (isset($q['SELECT']) or isset($q['DELETE'])) {
				if(isset($q['DELETE'])) {
					$q['DELETE'] = array();
				}
				$q['FROM'] = $args;
			} else {
				$q[]=$args;
			}
			return $q;
		},func_get_args());
	}
	public function where()
	{
		// TODO: advance features such as AND, OR, ()
		return $this->queries('WHERE',func_get_args());
	}
	public function whereAnd()
	{
		return $this->queries(function($q,$args){
			$q['WHERE'] = $this->whereOrAnd($q['WHERE'], $args);
			return $q;
		},func_get_args());
	}
	public function whereOr()
	{
		return $this->queries(function($q,$args){
			$q['WHERE'] = $this->whereOrAnd($q['WHERE'], $args,'OR');
			return $q;
		},func_get_args());
	}
	public function whereAndNot()
	{
		return $this->queries(function($q,$args){
			$q['WHERE'] = $this->whereOrAnd($q['WHERE'], $args,'AND','!=');
			return $q;
		},func_get_args());
	}
	public function whereOrNot()
	{
		return $this->queries(function($q,$args){
			$q['WHERE'] = $this->whereOrAnd($q['WHERE'], $args,'OR','!=');
			return $q;
		},func_get_args());
	}
	private function whereOrAnd($queries,$args,$oper='AND',$int=null)
	{
		if (is_array($args[0])) {
			$args = $args[0];
		}
		if ($int) {
			array_splice($args, 1, 0, $int);
		}
		return array($queries,$oper,$args);
	}
	/*
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
	*/
	public function group_by()
	{
		return $this->queries('GROUP BY',func_get_args());
	}
	public function order_by()
	{
		return $this->queries('ORDER BY',func_get_args());
	}
	public function limit()
	{
		return $this->queries('LIMIT',func_get_args());
	}
	public function offset()
	{
		return $this->queries('OFFSET',func_get_args());
	}
}
/*
Database::select(
		'column'
	)
	->rowsCalc()
	->from(
		'tableName'
	)
	->where(
		'id','=',1,
		'id',1
		'name','khen%'
	)
	->group_by('username')
	->order_by('username DESC')
	->limit(12)
	->offset(18)

	->build()
	->execute()

	->toArray()
	->rowsCount()
	->rowsTotal();
*/
/*
SELECT *,id,array FROM tableName
SELECT SQL_CALC_FOUND_ROWS *,id,array FROM tableName
INSERT INTO tableName SET created = NOW(), modified = NOW()
UPDATE tableName SET name='Khen' WHERE id=1
DELETE FROM tableName WHERE id=1
WHERE
ORDER BY columnName ASC|DESC, columnName ASC|DESC;
ORDER BY columnName;
ORDERS LIMIT 10 OFFSET 15
ORDERS LIMIT 15, 10
OFFSET 4

GROUP BY UNIQUEID ORDER BY _matches DESC LIMIT 12 OFFSET 0
*/