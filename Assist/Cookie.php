<?php
namespace Letid\Assist;
/*
Cookie::id('id')
	->set(value);
	->get(value);
	->remove();
*/
abstract class Cookie
{
	protected $Id = '!';
	public function __construct()
	{
		if (func_get_args()) {
			$this->Id = func_get_args()[0];
		}
	}
	static function id()
	{
		return new self(func_get_args()[0]);
	}
	public function get($Unserialize=false)
	{
		if (isset($_COOKIE[$this->Id])) {
			if ($Unserialize) {
				return unserialize($_COOKIE[$this->Id]);
			} else {
				return $_COOKIE[$this->Id];
			}
		}
	}
	public function set($Info)
	{
		if (isset($Info)) {
			if (is_array($Info)) {
				setcookie($this->Id,serialize($Info),$this->time());
			} else {
				setcookie($this->Id,$Info,$this->time());
			}
		}
	}
	public function remove()
	{
		if (isset($_COOKIE[$this->Id])) {
			unset($_COOKIE[$this->Id]);
			setcookie($this->Id, '', -1, '/');
		}
	}
	private function time()
	{
		return time()+1209600;
	}
}
