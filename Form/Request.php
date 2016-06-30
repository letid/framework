<?php
namespace Letid\Form;
class Request extends \Letid\Id\Application
{
	use Initiate, Custom, Response;
	static protected $scope;
	public function __construct($vars)
	{
		$this->formName = $vars;
	}
	private function scope()
	{
		if (func_get_args()) {
			if (isset(self::$scope->{func_get_args()[0]})) {
				return self::$scope->{func_get_args()[0]};
			}
		} else {
			return self::$scope;
		}
	}
	static function request()
	{
		return new self(func_get_args()[0]);
	}
	private function formSwitch()
	{
		switch (strtolower($this->scope('method'))) {
			case 'request': return $_REQUEST;
			case 'get': return $_GET;
			default: return $_POST;
		}
	}
	private function issetPost($fillName)
	{
		return isset($this->formData[$fillName]);
	}
	private function hasPost($fillName)
	{
		if ($this->issetPost($fillName)) return $this->formData[$fillName];
	}
	private function hasCustom($customMethod)
	{
		if (method_exists($this->scope(),$customMethod)) {
			return array($this->scope(), $customMethod);
		} elseif (method_exists($this,'custom'.$customMethod)) {
			return array($this, 'custom'.$customMethod);
		}
	}
	private function hasValue($valueName,$has)
	{
		if (is_scalar($has)) {
			return self::content($valueName)->set($has);
		} else if (isset($has['value'])) {
			// return self::content($valueName)->set($has['value']);
			// if (is_array($has['value'])) {
			// 	// $abc = '<option>Ok</option>';
			// 	// print_r($has['value']);
			// 	// return Application::content($valueName)->set($has['value'][0]);
			// } else {
			// 	return Application::content($valueName)->set($has['value']);
			// }
			return self::content($valueName)->set($has['value']);
		}
	}
	private function hasStatus($fillName,$has)
	{
		if (isset($has['status'])) {
			return self::language($has['status'])->get();
		} elseif ($fillName) {
			return $fillName;
		}
	}
	private function hasMask($maskName,$has)
	{
		if (is_scalar($has)) {
			self::content($maskName)->set(self::language($has)->get());
		} else if (isset($has['mask'])) {
			self::content($maskName)->set(self::language($has['mask'])->get());
		}
	}
	private function hasClass($className,$has)
	{
		if (is_scalar($has)) {
			self::content($className)->set($has);
		} elseif (is_array($has)) {
			self::content($className)->set(implode($has,' '));
		} else if (isset($has['class'])) {
			if (is_scalar($has['class'])) {
				self::content($className)->set($has['class']);
			} else {
				self::content($className)->set(implode($has['class'],' '));
			}
		}
	}
	private function hasMessage($formMessageName, $msg, $value)
	{
		self::content($formMessageName)->set($this->hasMessageHtml(
			$msg, $value
		));
	}
	private function hasMessageHtml($msg,$attr='message')
	{
		return self::html('p')->text($msg)->attr(
			array('class'=>$attr)
		)->response();
	}
	private function responseTerminal()
    {
		if ($this->formSubmit) {
			if ($this->formError) {
				$this->responseError($this->formMessage);
			} else {
				return true;
			}
		} elseif ($this->formMessage) {
			$this->responseDefault();
		}
		return false;
    }
	private function responseTask($args,$query)
    {
		if (is_callable($args)) {
			$this->responseSuccess(call_user_func($args, $query));
		} else {
			$this->formPost = false;
			if (isset($query) and $query->msg) {
				$this->responseError($query->msg);
			} else {
				$this->responseError($args);
			}
		}
    }
	private function responseError($msg)
    {
		$this->hasMessage($this->formMessageName, $msg, array('message error'));
    }
	private function responseSuccess($msg)
    {
		$this->hasMessage($this->formMessageName, $msg, array('message success'));
    }
	private function responseDefault()
    {
		$this->hasMessage($this->formMessageName, $this->formMessage, array('message'));
    }
}