<?php
namespace letId\form;
trait response
{
	public function response($Id=array())
    {
		if ($this->responseTerminal($Id)) {
			$this->responseTask(true,$Id,false,array('Done!','Unchanged!'));
		}
		return $this;
    }

	private function responseTerminal($Id=null)
	{
		if ($this->submit) {
			if ($this->error) {
				// $this->responseTaskerror($Id,$this,$this->message);
				$this->responseError($this->message);
			} else {
				return true;
			}
		} else {
			$this->responseDefault();
		}
	}
	private function responseTask($status,$callback,$db,$msg)
	{
		if ($status) {
			$this->responseTasksuccess($callback,$db,$msg[0]);
		} else {
			$this->responseTaskerror($callback,$db,$msg[1]);
		}
	}
	private function responseTasksuccess($callback,$db,$msg)
	{
		if (is_callable($callback)) {
			// $msg = ($callback, true, $this);
			$msg = call_user_func_array(array($callback), array(true, $this));
		}
		$this->responseSuccess($msg);
	}
	private function responseTaskerror($callback,$db,$msg)
	{
		$this->formPost = false;
		if (is_callable($callback)) {
			// $msg = ($callback, false, $db);
			$msg = call_user_func_array(array($callback), array(false, $db));
		} elseif ($db and isset($db->msg)) {
			$msg = $db->msg;
		}
		$this->error = $msg;
		$this->responseError($msg);
	}
	private function responseError($Id)
	{
		$this->responseMessage($this->formName.$this->messageName, $Id, array('message error'));
	}
	private function responseSuccess($Id)
	{
		$this->responseMessage($this->formName.$this->messageName, $Id, array('message success'));
	}
	private function responseDefault()
	{
		if ($this->message) {
			$this->responseMessage($this->formName.$this->messageName, $this->message, array('message'));
		}
	}
	private function responseMessage($name, $msg, $attr)
	{
		avail::content($name)->set($this->responseMessageContainer(
			$msg, $attr
		));
	}
	private function responseMessageContainer($msg,$attr='message')
	{
		return avail::html('p')->text($msg)->attr(
			array('class'=>$attr)
		)->response();
	}
	// NOTE: DONE -> REDIRECTING
	public function redirectOnsuccess($Id='/')
	{
		if ($this->submit) {
			if (!$this->error) {
				return header("Location: $Id");
			}
		}
		return $this;
	}
	// NOTE: DONE
	public function done($Id)
	{
		return $Id;
	}
}