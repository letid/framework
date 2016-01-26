<?php
namespace Lethil\Init;
class Database extends \mysqli
{
	static $db;
	// var $rows, $total, $overall;
	public function __construct($query,$method=NULL)
	{
		self::$db->set_charset("utf8");
		$this->result 	= self::$db->query($query);
		$fn 			= strtolower(strtok($query, " "));

		if(self::$db->error):
			$this->msg = self::$db->error;
		else:
			//$this->msg = $this->{$fn}();
			if(method_exists($this, $fn) && is_callable(array($this, $fn)))$this->msg = call_user_func(array($this, $fn));
			if($this->msg and $method)call_user_func(array($this, $method));
		endif;
	}
	function Connection($d)
	{
		// $d['host'],$d['username'],$d['password'],$d['database']
		if(strpos($h,'/cloudsql/') !== false) {
			self::$db = new parent(NULL, $d['username'], $d['password'], $d['database'], NULL, $d['host']);
		} else {
			self::$db = new parent($d['host'], $d['username'], $d['password'], $d['database']);
		}
		//self::$db->connect_errno; self::$db->connect_error;
	}
	public function test()
	{
		return self::$db;
	}
	public function abc()
	{
		return 'sql->connection';
	}
	private function select()
	{
		$this->total = $this->result->num_rows;// mysqli_num_rows($this->result)
		return $this->total;
	}
	private function insert()
	{
		$this->insert_id = self::$db->insert_id;//mysqli_insert_id()
		return $this->insert_id;
	}
	private function update()
	{
		$this->affected_rows = self::$db->affected_rows;
		return $this->affected_rows;
	}
	public function found_rows()
	{
		$row = self::$db->query("SELECT FOUND_ROWS()"); //SELECT FOUND_ROWS() AS row;
		$this->found_rows = $row->fetch_row();
		return $this->found_rows[0];
	}
	public function fetch_row()
	{
		$this->rows = $this->result->fetch_row();//mysqli_fetch_row($this->result)
	}
	public function fetch_array($v=MYSQLI_ASSOC)//MYSQLI_ASSOC,MYSQLI_BOTH,MYSQLI_NUM
	{
		while($r=$this->result->fetch_array($v))
			$this->rows[]=$r;
	}
	public function fetch_assoc($group=NULL,$overall=NULL)
	{
		while($r=$this->result->fetch_assoc()):
			if(isset($r[$group]))$this->rows[$r[$group]][]=$r;
				else $this->rows[] = $r;
			if(isset($r[$overall]))$this->overall +=$r[$overall];
		endwhile;
	}
	public function fetch_assoc_json($group=NULL)
	{
		while($r = $this->result->fetch_array(MYSQLI_NUM)) $this->rows[] = $r[0];
		//mysqli_free_result($this->result);
		$this->result->free();
		self::$db->close();
	}
	public function fetch_this()
	{
		foreach($this->result->fetch_object() as $user_key => $user_value)
			$this->rows[$user_key] = $user_value;
	}
	public function fetch_object()
	{
		foreach($this->result->fetch_object() as $user_key => $user_value):
			$this->{$user_key} = $user_value;
		endforeach;
	}
	public function get_row()
	{
		$this->rows = $this->result->fetch_row();
	}
	public function get_array($v=MYSQLI_ASSOC)
	{
		$this->rows = $this->result->fetch_array($v);
	}
	public function get_assoc()
	{
		$this->rows = $this->result->fetch_assoc();
	}
	public function get_object()
	{
		$this->rows = $this->result->fetch_object();
	}
    public function __call($name, $arguments)
    {
		if(method_exists($this, $name) == false):
			//return $this->_call_underfind(__CLASS__,$name);
		endif;
    }
	public function __get($name)
	{
	   if (property_exists($this, $name)):
	   		return $this->{$name};
	   endif;
	}
	public function __set($name, $value)
	{
		$this->{$name} = $value;
	}
	public function __toString()
	{
		if($this->msg):
			return $this->msg;
		endif;
	}
}
