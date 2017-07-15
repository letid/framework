<?php
namespace letId\assist
{
  /*
  session::request('id')
      ->has()
  	->set(value)
  	->get()
  	->remove()
  	->delete();
  */
  abstract class session extends essential
  {
    public function start()
    {
      if ($this->status() === false) session_start();
    }
    private function status()
    {
      if (php_sapi_name() !== 'cli') {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
          return session_status() === PHP_SESSION_ACTIVE ? true : false;
        } else {
          return session_id() === '' ? false : true;
        }
      }
      return false;
    }
    public function has()
    {
      if (isset($_SESSION[$this->Id])) return $_SESSION[$this->Id];
    }
    public function get()
    {
      return $_SESSION[$this->Id];
    }
    public function set($Id='')
    {
      return $_SESSION[$this->Id]=$Id;
    }
    public function remove()
    {
      unset($_SESSION[$this->Id]);
    }
    public function delete()
    {
      session_unset();
    }
  }
}