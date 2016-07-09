<?php
namespace letId\support
{
    abstract class authorization extends avail
    {
        public $table = array(), $userCookie;
        public function subscribe()
    	{
            $this->userCookie = self::cookie()->user();
    		if (isset($_GET['signout'])) {
    			$this->subscribeReset();
    		} else {
    			if ($this->userCookie->has()) $this->subscribeUser();
    		}
    	}
    	private function subscribeUser()
        {
            $user = self::$database->select()->from($this->table['user'])->where($this->userCookie->get(true))->execute()->toObject()->rowsCount();
    		if ($user->rowsCount) {
    			self::$user = $user->rows;
                foreach ($user->rows as $id => $name) self::content('user.'.$id)->set($name);
    		} else {
    			$this->subscribeReset();
    		}
        }
        private function subscribeReset()
        {
    		// TODO: Not all session should be removed
    		self::session()->delete();
    		$this->userCookie->remove();
    		// self::cookie()->user()->remove();
        }
        public function test()
        {
            echo 'support\authorization::test';
        }
    }
}