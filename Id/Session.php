<?php
namespace Letid\Id;
trait Session
{
	private function SessionRequest()
    {
		if ($this->SessionExists() === FALSE ) {
			session_start();
		}
	}
	private function SessionExists()
    {
		if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? true : false;
	        } else {
	            return session_id() === '' ? false : true;
	        }
	    }
	    return false;
	}
	private function SessionID($id)
    {
		// return $id.'.'.uniqid();
		return $id.'.'.$this->config('version');
	}
}
