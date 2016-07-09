<?php
namespace letId\form;
trait database
{
	// NOTE: DONE -> SIGNING UP
	public function signup($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->insert($this->formPost)->to($this->table)->execute()->rowsId();
			$this->responseTask($db->rowsId,$Id,$db,array('Inserted!','Unchanged!'));
		}
		return $this;
    }
	// NOTE: DONE -> SIGNING IN
	public function signin($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->select()->from($this->table)->where($this->formPost)->execute()->toObject()->rowsCount();
			if ($db->rowsCount) {
				if (isset($db->rows->status))
				{
					$rowsArray = (array) $db->rows;
					if ($db->rows->status > 0)
					{
						if(isset($db->rows->logs))
						{
							avail::$database->update(array_filter($rowsArray, function(&$v, $k) {
								if ($k == 'logs') {
									return $v=$v+1;
								}
								if ($k == 'modified') {
									return $v=date('Y-m-d G:i:s');
								}
							}, ARRAY_FILTER_USE_BOTH))->to($this->table)->where($this->formPost)->execute()->rowsAffected();
						}
					}
					else
					{
						// NOTE: user account has been deactivated, and needed to activate!
					}
					avail::session()->delete();
					avail::cookie()->user()->set(array_intersect_key($rowsArray, array_flip(array('userid','password'))));
				}
			} else {
				$this->message = avail::language('invalid VALUE')->get(array(
					'value'=>avail::arrays(array_keys($this->formPost))->to_sentence(null, ' / ')
				));
				$this->responseTaskerror($Id,$db,$this->message);
			}
		}
		return $this;
    }
	// NOTE: UNDONE
	public function signout()
    {
		return $this;
    }
	public function forgotpassword()
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->select('*')->from($this->table)->where($this->formPost)->execute()->rowsId()->toObject();
			if ($db->rowsCount) {
				$taskId = avail::assist('password')->sha1($db->rows->userid);
				$taskCode = avail::assist()->uniqid();
				$taskQuery = array(
					'taskid' => 'password-user-'.$db->rows->userid,
					'code'=> $taskCode,
					'status' => 1,
					'userid' => $db->rows->userid,
					'subject' => 'reset password'
				);
				$status = avail::mail(array('email/reset.password'=>$taskQuery))->send();
				if($status) {
					avail::$database->insert(
						$taskQuery
					)->to(
						'tasks'
					)->duplicateUpdate(
						$taskQuery
					)->execute();
				}
				$msg = array(avail::language('Verification code has been sent')->get(),avail::language('Mail could not send')->get());
				$this->responseTask($status,$Id,$db,$msg);
			} else {
				$this->message = avail::language('no VALUE exists')->get(array(
					'value'=>avail::arrays($this->formPost)->to_sentence()
				));
				$this->responseTaskerror($Id,$db,$this->message);
			}
		}
		return $this;
    }
	public function changepassword($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->update($this->formPost)->to($this->table)->where($this->formId)->execute()->rowsAffected();
			if ($db->rowsAffected) {
				avail::cookie()->user()->set(array_merge($this->formId,$this->formPost));
			}
			$this->responseTask($db->rowsAffected,$Id,$db,array('Changed!','Unchanged!'));
		}
		return $this;
    }
	public function resetpassword($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->select()->from('tasks')->where('code',$this->formPost['code'])->execute()->rowsCount()->toObject();
			if ($db->rowsCount) {
				$update = avail::$database->update(
					array('password'=>$this->formPost['password'], 'status'=>1)
				)->to($this->table)->where('userid',$db->rows->userid)->execute()->rowsAffected();
				if ($update->rowsAffected) {
					 avail::$database->delete()->from('tasks')->where('taskid',$db->rows->taskid)->execute();
				}
				$this->responseTask($update->rowsAffected,$Id,$db,array('Reseted!','Unchanged!'));
			} else {
				$this->responseTaskerror($Id,$db,'Invalid verification code!');
			}
		}
		return $this;
    }
	// NOTE: DONE -> INSERTING
	public function insert($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->insert($this->formPost)->to($this->table)->execute()->rowsId();
			$this->responseTask($db->rowsId,$Id,$db,array('Inserted!','Unchanged!'));
		}
		return $this;
    }
	// NOTE: DONE -> UPDATING
	public function update($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->update($this->formPost)->to($this->table)->where($this->formId)->execute()->rowsAffected();
			$this->responseTask($db->rowsAffected,$Id,$db,array('Updated!','Unchanged!'));
		}
		return $this;
    }
	// NOTE: DONE -> INSERTING OR UPDATING
	public function insertOrupdate($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->insert(
				array_merge($this->formId,$this->formPost)
			)->to(
				$this->table
			)->duplicateUpdate(
				$this->formPost
			)->execute()->rowsAffected();
			$this->responseTask($db->result,$Id,$db,array('Updated!','Unchanged!'));
		}
		return $this;
    }
	// NOTE: DONE -> DELETING
	public function delete($Id=null)
    {
		if ($this->responseTerminal()) {
			$db = avail::$database->delete()->from($this->table)->where($this->formId)->execute()->rowsAffected();
			$this->responseTask($db->rowsAffected,$Id,$db,array('Deleted!','Unchanged!'));
		}
		return $this;
    }
}