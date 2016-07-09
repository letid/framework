<?php
namespace letId\assist
{
	/*
	cookie::request('id')
		->has()
		->set(value)
		->get(unserialize)
		->remove()
		->delete();
	*/
	abstract class cookie extends avail
	{
		public function has()
        {
            if (isset($_COOKIE[$this->Id])) return $_COOKIE[$this->Id];
        }
		public function get($Unserialize=false)
		{
			if ($this->has()) {
				if ($Unserialize) {
					return unserialize($_COOKIE[$this->Id]);
				} else {
					return $_COOKIE[$this->Id];
				}
			}
		}
		public function set($Id=null)
		{
			if ($Id) {
				if (is_array($Id)) {
					setcookie($this->Id,serialize($Id),$this->time());
				} else {
					setcookie($this->Id,$Id,$this->time());
				}
			}
		}
		public function remove()
		{
			if (isset($_COOKIE[$this->Id])) {
				unset($_COOKIE[$this->Id]);
				setcookie($this->Id, '', -1, '/');
				// setcookie($this->Id, '', 1);
				// setcookie($this->Id, '', 1, '/');
			}
		}
		public function delete()
		{
			// setcookie($this->Id,false);
		}
		private function time()
		{
			return time()+1209600;
		}
	}
}