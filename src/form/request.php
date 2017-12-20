<?php
namespace letId\form;
abstract class request
{
	use initiate, custom, response, database;
	public $state, $submit, $error=array(), $table, $message, $messageName='.form.message';
	public $form, $formName, $formId=array(), $formPost=array();
	public function __construct($vars)
	{
		$this->formName = $vars;
	}
	private function requestState($Id=null)
	{
		if ($Id) {
			if (isset($this->state[$Id])) {
				return $this->state[$Id];
			}
		} else {
			return $this->state;
		}
	}
	private function requestMethod()
	{
		switch (strtolower($this->requestState('method'))) {
			case 'request': return $_REQUEST;
			case 'get': return $_GET;
			default: return $_POST;
		}
	}
	private function requestSupport()
	{
		$support = $this->requestState('val');
		if ($support) {
			if (!$this->submit) {
				$row = avail::$database->select()->from($this->table)->where($support)->execute()->fetchAll()->rows;
				if ($row) return $row[0];
			}
		}
	}
	private function requestVisibility($visibilityName,$visibilityValue,$fillName)
	{
		if (is_array($visibilityValue)) {
			foreach ($visibilityValue as $key => $value) {
				if (is_numeric($key) && isset($_GET[$fillName])) {
					avail::content($visibilityName)->set($value);
				} elseif (isset($_GET[$key])) {
					avail::content($visibilityName)->set($value);
				}
			}
		} elseif (is_scalar($visibilityValue)) {
			avail::content($visibilityName)->set($visibilityValue);
		}
	}
	/*
	private function requestRequire($requireName, $requireValue,$fillName)
	{
		if (is_array($requireValue)) {
			if ($this->submit) {
				if (!$fillValue) {
					// $this->error[] = $this->requestStatushas($fillName,$requireValue);
					// $this->requestMaskhas($maskName,$requireValue);
					// if ($classDefault) {
					// 	array_push($classValue,$classDefault);
					// }
					// if (isset($requireValue['class'])) {
					// 	array_push($classValue,$requireValue['class']);
					// }
				}
			} else {
			}
		}
	}
	*/
	private function requestPostset($fillName)
	{
		return isset($this->form[$fillName]);
	}
	private function requestPosthas($fillName)
	{
		if ($this->requestPostset($fillName)) return $this->form[$fillName];
	}
	private function requestPostvalue($name,$is)
	{
		if (is_scalar($is)) {
			return avail::content($name)->set($is);
		} else if (isset($is['value'])) {
			if (is_array($is['value'])) {
				avail::content($name)->set(json_encode($is['value']));
				return $is['value'];
			} else {
				return avail::content($name)->set($is['value']);
			}
		} else {
			avail::content($name)->set(json_encode($is));
			return $is;
		}
	}
	private function requestMaskhas($maskName,$is)
	{
		if (is_scalar($is)) {
			avail::content($maskName)->set($is);
		} else if (isset($is['mask'])) {
			avail::content($maskName)->set($is['mask']);
		}
	}
	private function requestStatushas($fillName,$is)
	{
		if (isset($is['status'])) {
			return avail::language($is['status'])->get();
		} elseif ($fillName) {
			return $fillName;
		}
	}
	private function requestClasshas($className,$is)
	{
		if (is_scalar($is)) {
			avail::content($className)->set($is);
		} elseif (is_array($is)) {
			if (isset($is['class'])) {
				if (is_scalar($is['class'])) {
					avail::content($className)->set($is['class']);
				} else {
					avail::content($className)->set(implode($is['class'],' '));
				}
			} else {
				avail::content($className)->set(implode($is,' '));
			}
		}
	}
	private function requestCustomhas($method)
	{
		if ($i = avail::assist(avail::validation())->is_callable($method)) {
			return $i;
		} else {
			return avail::assist($this)->is_callable('custom'.$method);
		}
	}
	private function requestInputCheckbox($is,$name,$value)
	{
		// <input type="checkbox" name="civil" value="1" checked="checked"> Married...
		// <input type="checkbox" name="civil[]" value="1">
		$checkbox = array();
		foreach ($is as $id => $text) {
			$attr = array('type'=>'checkbox','name'=>$name.'[]', 'value'=>$id, 'id'=>$id);
			if (is_array($value)) {
				if (in_array($id,$value)) {
					$attr[]='checked';
				}
			} elseif ($id == $value) {
				$attr[]='checked';
			}
			$checkbox[]=array(
				'input'=>array('attr'=>$attr),
				'label'=>array(
					'text'=>$text, 'attr'=>array('for'=>$id)
				)
			);
		}
		return $checkbox;
	}
	private function requestInputRadio($is,$name,$value)
	{
		// <input type="radio" name="gender" value="1" checked> Male...
		// <label for="gender">Male</label>
		$radio = array();
		foreach ($is as $id => $text) {
			$attr = array('type'=>'radio','name'=>$name, 'value'=>$id, 'id'=>$id);
			if ($id == $value) {
				$attr[]='checked';
			}
			$radio[]=array(
				'input'=>array('attr'=>$attr),
				'label'=>array(
					'text'=>$text, 'attr'=>array('for'=>$id)
				)
			);
		}
		return $radio;
	}
	private function requestSelectOption($is,$name,$value)
	{
		// <select name="country">
		// 	<option value="NO">Norway</option>
		// </select>
		$option = array();
		foreach ($is as $id => $text) {
			$attr = array('value'=>$id);
			if ($id == $value) {
				$attr[]='selected';
			}
			$option[]=array(
				'option'=>array(
					'text'=>$text, 'attr'=>$attr
				)
			);
		}
		return array(
			'select'=>array(
				'text'=>$option, 'attr'=>array('name'=>$name)
			)
		);
	}
}