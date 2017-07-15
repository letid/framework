<?php
namespace letId\request
{
  class authorization
  {
    protected $table = array();// $userCookie;
    /**
    * avail::authorization()->subscribe();
    * use in request\http
    */
    public function subscribe()
    {
      $this->userCookie = avail::cookie()->user();
      if (isset($_GET['signout'])) {
      	$this->subscribeReset();
      } elseif ($this->userCookie->has()) {
         $this->subscribeUser();
      }
    }
    private function subscribeUser()
    {
      if ($this->table && $this->table['user']) {
        $user = avail::$database->select()->from($this->table['user'])->where($this->userCookie->get(true))->execute()->toObject()->rowsCount();
        if ($user->rowsCount) {
          avail::$user = $user->rows;
          foreach ($user->rows as $id => $name) avail::content('user.'.$id)->set($name);
        } else {
          $this->subscribeReset();
        }
      }
    }
    private function subscribeReset()
    {
      // TODO: Not all session should be removed
      avail::session()->delete();
      $this->userCookie->remove();
      // self::cookie()->user()->remove();
    }
    public function user()
  	{
      return avail::$user;
  	}
    public function guest()
    {
      return !avail::$user;
    }
    public function confirm_user()
  	{
      return avail::$user;
  	}
    public function confirm_guest()
  	{
      return !avail::$user;
  	}
    public function confirm_age()
  	{
      if ($v >= 10) return true;
  	}
    public function confirm_email()
  	{
      if ($v >= 10) return true;
  	}
    public function user_email()
  	{
      // if ($this->user()) return avail::$user->email;
  	}
    public function age($v)
    {
      // if ($v >= 10) return true;
    }
  }
}