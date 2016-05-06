<?php
namespace Letid\Form;
trait Response
{
	public function response()
    {
		if ($this->formMessage) {
			if ($this->issetPost($this->formName)) {
				$this->responseError($this->formMessage);
			} else {
				$this->responseDefault($this->formMessage);
			}
		}
		return $this;
    }
	public function insert()
    {
		if ($this->issetPost($this->formName)) {
			if ($this->formPost) {
				$db = Database::insert($this->formPost)->to($this->formTable)->execute()->rowsId();
				if ($db->rowsId) {
					$this->responseSuccess("Done! What's next?"); // $db->query
				} else {
					$this->responseError($db->msg);
				}
			} else {
				$this->responseDefault($this->formMessage);
			}
		} elseif ($this->formMessage) {
			$this->responseDefault($this->formMessage);
		}
		return $this;
    }
	public function login()
    {
		if ($this->issetPost($this->formName)) {
			if ($this->formPost) {
				$this->responseError('Login is not ready');
			} else {
				$this->responseDefault($this->formMessage);
			}
		} elseif ($this->formMessage) {
			$this->responseDefault($this->formMessage);
		}
		return $this;
    }
	public function update()
    {
		if ($this->issetPost($this->formName)) {
			if ($this->formPost) {
				$this->responseError('Update is not ready');
			} else {
				$this->responseDefault($this->formMessage);
			}
		} elseif ($this->formMessage) {
			$this->responseDefault($this->formMessage);
		}
		return $this;
    }
	public function delete()
    {
		if ($this->issetPost($this->formName)) {
			if ($this->formPost) {
				$this->responseError('Delete is not ready');
			} else {
				$this->responseDefault($this->formMessage);
			}
		} elseif ($this->formMessage) {
			$this->responseDefault($this->formMessage);
		}
		return $this;
    }
}