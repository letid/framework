<?php
namespace Letid\Form;
trait Response
{
	// NOTE: DONE -> RESPONSING
	public function response($args)
    {
		if ($this->responseTerminal()) {
			if (isset($args)) {
				$this->responseTask($args,$this);
			}
		}
		return $this;
    }
	// NOTE: DONE -> SIGNING UP
	public function signup($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->insert($this->formPost)->to($this->formTable)->execute()->rowsId();
			if ($db->rowsId) {
				$this->responseSuccess("Inserted!");
			} else {
				$this->responseTask('Error!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> SIGNING IN
	public function signin($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->select('*')->from($this->formTable)->where($this->formPost)->execute()->toObject()->rowsCount();
			if ($db->rowsCount) {
				if (isset($db->rows->status))
				{
					$rowsArray = (array) $db->rows;
					if ($db->rows->status > 0)
					{
						if(isset($db->rows->logs))
						{
							self::$database->update(array_filter($rowsArray, function(&$v, $k) {
								if ($k == 'logs') {
									return $v=$v+1;
								}
								if ($k == 'modified') {
									return $v=date('Y-m-d G:i:s');
								}
							}, ARRAY_FILTER_USE_BOTH))->to($this->formTable)->where($this->formPost)->execute()->rowsAffected();
						}
					}
					else
					{
						// NOTE: user account has been deactivated, and needed to activate!
					}
					self::session()->remove();
					self::cookie()->sign()->set(array_intersect_key($rowsArray, array_flip(array('userid','password'))));
				}
			} else {
				$this->responseTask('Incorrect username or password!',$db);
			}
		}
		return $this;
    }
	// NOTE: UNDONE
	public function signout()
    {
		$this->formPost = true;
		return $this;
    }
	/**
	* check email is exists
	* check if sent
	* then task insert or update
	* redirect to reset page
	*/
	public function forgotpassword()
    {
		if ($this->responseTerminal()) {
			$db = self::$database->select('*')->from($this->formTable)->where($this->formPost)->execute()->rowsId()->toObject();
			if ($db->rowsCount) {
				$taskId = self::assist('password')->sha1($db->rows->userid);
				$taskCode = self::assist()->uniqid();
				$taskQuery = array(
					'taskid' => 'password-user-'.$db->rows->userid,
					'code'=> $taskCode,
					'status' => 1,
					'userid' => $db->rows->userid,
					'subject' => 'reset password'
				);
				self::$database->insert(
					$taskQuery
				)->to(
					'tasks'
				)->duplicateUpdate(
					$taskQuery
				)->execute();
				if(self::mail(array('email/reset-password'=>$taskQuery))->send()) {
					$this->responseSuccess("Verification code has been sent to your email.");
				} else {
					$this->responseTask('Mail count not sent, please try again later!');
				}
			} else {
				$this->responseTask('No email exists!',$db);
			}
		}
		return $this;
    }
	public function changepassword($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->update($this->formPost)->to($this->formTable)->where($this->formId)->execute()->rowsAffected();
			if ($db->rowsAffected) {
				self::cookie()->sign()->set(array_merge($this->formId,$this->formPost));
			}
			if (isset($args)) {
				$this->responseTask($args,$db);
			} else if ($db->rowsAffected) {
				$this->responseSuccess('Changed!');
			} else {
				$this->responseTask('Unchanged!',$db);
			}
		}
		return $this;
    }
	public function resetpassword($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->select()->from('tasks')->where('code',$this->formPost['code'])->execute()->rowsCount()->toObject();
			if ($db->rowsCount) {
				$update = self::$database->update(
					array('password'=>$this->formPost['password'], 'status'=>1)
				)->to($this->formTable)->where('userid',$db->rows->userid)->execute()->rowsAffected();
				if ($update->rowsAffected) {
					 self::$database->delete()->from('tasks')->where('taskid',$db->rows->taskid)->execute();
				} else {
					$this->responseTask('Unchanged!',$update);
				}
			} else {
				$this->responseTask('Invalid verification code!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> INSERTING
	public function insert($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->insert($this->formPost)->to($this->formTable)->execute()->rowsId();
			if (isset($args)) {
				$this->responseTask($args,$db);
			} else if ($db->rowsId) {
				$this->responseSuccess('Inserted!');
			} else {
				$this->responseTask('Unchanged!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> UPDATING
	public function update($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->update($this->formPost)->to($this->formTable)->where($this->formId)->execute()->rowsAffected();
			if (isset($args)) {
				$this->responseTask($args,$db);
			} else if ($db->rowsAffected) {
				$this->responseSuccess('Updated!');
			} else {
				$this->responseTask('Unchanged!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> INSERTING OR UPDATING
	public function insertOrupdate($args)
    {
		if ($this->responseTerminal()) {
			$db = self::$database->insert(
				array_merge($this->formId,$this->formPost)
			)->to(
				$this->formTable
			)->duplicateUpdate(
				$this->formPost
			)->execute()->rowsAffected();
			if (isset($args)) {
				$this->responseTask($args,$db);
			} else if ($db->result) {
				$this->responseSuccess('Updated!');
			} else {
				$this->responseTask('Unchanged!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> DELETING
	public function delete()
    {
		if ($this->responseTerminal()) {
			$db = self::$database->delete()->from($this->formTable)->where($this->formId)->execute()->rowsAffected();
			if (isset($args)) {
				$this->responseTask($args,$db);
			} else if ($db->rowsAffected) {
				// $db->rowsAffected > 0
				$this->responseSuccess('Deleted!');
			} else {
				$this->responseTask('Unchanged!',$db);
			}
		}
		return $this;
    }
	// NOTE: DONE -> REDIRECTING
	public function redirectIfsuccess($args)
    {
		if ($this->issetPost($this->formName)) {
			if ($this->formPost) {
				return header('Location: '.$args);
			}
		}
		return $this;
    }
	// NOTE: DONE
	public function done($args)
    {
		return $args;
    }
}