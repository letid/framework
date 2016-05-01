<?php
namespace Letid\Id;
use Letid\Database\Connection;
trait Database
{
	// NOTE: method available to user
	private function DatabaseRequest()
    {
		$this->database = $this->DatabaseConnectMySQLi(func_get_args()[0]);
	}
	private function DatabaseConnectMySQLi($d)
	{
		if (is_array($d)) {
			if(strpos($d['host'],'/cloudsql/') !== false) {
				Connection::$db = new \mysqli(NULL, $d['username'], $d['password'], $d['database'], NULL, $d['host']);
			} else {
				Connection::$db = new \mysqli($d['host'], $d['username'], $d['password'], $d['database']);
			}
			// Connection::$db->set_charset("utf8");
			return Connection::$db;
		}
	}
	private function DatabaseError()
    {
		if ($this->database->connect_errno) return true;
	}
	private function DatabaseInitiate()
    {
		return $this->InitiateError(Config::$Notification['database'],array_merge(Config::$DatabaseConnection,array('Message'=>$this->database->connect_error,'Code'=>$this->database->connect_errno)));
	}
}