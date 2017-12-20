<?php
namespace letId\request
{
  class authentication
  {
    protected $table = array();

    protected function client()
    {
      /**
      * $this->client();
      * use in app\authenticationController
      */
    }
    /**
    * avail::$authentication->describe();
    * use in request\http
    */
    public function requestInitiate()
    {
      $this->client();
      if (isset($_GET['signout'])) {
      	$this->usersCookieRemove();
      } elseif ($this->usersCookie()->has()) {
         $this->requestUser();
      }
    }
    private function requestUser()
    {
      if ($this->table && $this->table['user']) {
        $user = avail::$database->select()->from($this->table['user'])->where($this->usersCookie()->get(true))->execute()->fetchObject()->rowsCount();
        if ($user->rowsCount) {
          avail::$user = $user->rows;
          foreach ($user->rows as $id => $name) avail::content('user.'.$id)->set($name);
        } else {
          $this->usersCookieRemove();
        }
      }
    }
    protected function usersCookieRemove()
    {
      // TODO: Not all session should be removed
      // avail::session()->delete();
      $this->usersCookie()->remove();
    }
    // private function _removeUser()
    // {
    //   $this->usersCookie()->remove();
    //   avail::$authentication->usersCookie()->remove();
    //
    // }
    // private function _addUser()
    // {
    //   $this->usersCookie()->set();
    //   avail::$authentication->usersCookie()->set();
    // }
    public function usersCookie()
    {
      return avail::cookie(avail::$config['signCookieId']);
    }
    // NOTE: use in route
    public function user()
  	{
      return avail::$user;
  	}
    public function guest()
    {
      return !avail::$user;
    }
    // public function confirm_user()
  	// {
    //   return avail::$user;
  	// }
    // public function confirm_guest()
  	// {
    //   return !avail::$user;
  	// }
    // public function confirm_age()
  	// {
    //   if ($v >= 10) return true;
  	// }
    // public function confirm_email()
  	// {
    //   if ($v >= 10) return true;
  	// }
    // public function user_email()
  	// {
    //   if ($this->user()) return avail::$user->email;
  	// }
    // public function age($v)
    // {
    //   if ($v >= 10) return true;
    // }
  }
}