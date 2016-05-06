<?php
namespace Letid\Form;
class Request extends \Letid\Id\Application
{
	use Name, Setting, Custom, Response;
	public function __construct($name,$arguments)
	{
		$this->{$name} = $arguments;
	}
	private function formMethod()
	{
		$this->formData = $this->formSwitch();
	}
	private function formSwitch()
	{
		switch (strtolower($this->scoop_get($this->formName.'_method'))) {
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
		if (method_exists($this->scoop_get(),$customMethod)) {
			return array($this->scoop_get(), $customMethod);
		} elseif (method_exists($this,'custom_'.$customMethod)) {
			return array($this, 'custom_'.$customMethod);
		}
	}
	private function hasValue($valueName,$has)
	{
		if (is_scalar($has)) {
			return $this->data($valueName,$has);
		} else if (isset($has['value'])) {
			return $this->data($valueName,$has['value']);
		}
	}
	private function hasStatus($fillName,$has)
	{
		if (isset($has['status'])) {
			return $this->lang($has['status']);
		} elseif ($fillName) {
			return $fillName;
		}
	}
	private function hasMask($maskName,$has)
	{
		if (is_scalar($has)) {
			$this->data($maskName,$this->lang($has));
		} else if (isset($has['mask'])) {
			$this->data($maskName,$this->lang($has['mask']));
		}
	}
	private function hasMessage($messageName, $msg, $value)
	{
		$this->data($messageName,
			$this->hasMessageHtml(
				$msg, $value
			)
		);
	}
	private function hasMessageHtml($msg,$attr='message')
	{
		return $this->html(
		    array(
		    	'p'=>array(
		    		'text'=>$msg,
		    		'attr'=>array(
		    			'class'=>$attr
		    		)
		    	)
		    )
		);
	}
	private function responseError($Message)
    {
		$this->hasMessage($this->messageName, $Message, array('message error'));
    }
	private function responseSuccess($Message)
    {
		$this->hasMessage($this->messageName, $Message, array('message success'));
    }
	private function responseDefault()
    {
		$this->hasMessage($this->messageName, $this->formMessage,array('message'));
		// $this->hasMessage($this->messageName, $Message);
    }
}