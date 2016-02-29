<?php
namespace Letid\Config;
trait Page
{
	private function Interjection()
	{
		self::$int['page'] = $this->Initiation(func_get_args()[0]);
	}
	private function Integration()
	{
		self::$int['menu'] = array_replace_recursive(self::$int['page'][static::AHM],$this->Arrange(self::$int['page'],self::$int['uri']));
		self::$APC = self::$int['menu'][static::APC];
		self::$APD = self::$int['menu'][static::APD];
		return self::$int['menu'];
	}
	private function Arrange($page, $key)
	{
		if (is_array($v = $page[$key[0]])) {
			if (array_shift($key) && $Item=$this->ArrangeItem($v, $key[0])) {
				return array_replace_recursive($Item,$this->Arrange($v,$key));
			}
		} else {
			// NOTE: required for home
			return array();
		}
		return $v;
	}
	private function ArrangeItem($page, $key)
	{
		if (self::hasChild($page, $key)) {
			foreach ($page as $k => $v) {
				if ($this->hasChild($page, $k)) {
					// NOTE: Other Item
					unset($page[$k]);
				}
			}
			// NOTE: Current Item, unset($Page[$key]);
			return $page;
		}
	}
	private function hasChild($page, $key)
	{
		// NOTE: used in ArrangeItem, MenuItem??
		if (is_array($page[$key]) and isset($page[$key][static::APM]) || isset($page[$key][static::APC]) || isset($page[$key][static::APD])) {
			return true;
		}
	}
	private function Initiation($p,$sub=NULL)
	{
		if(is_array($p)):
			foreach($p as $k => $v):
				if(isset($v['authorization']) and is_array($v['authorization'])):
					if(count($v['authorization']) == $this->Authorization($v['authorization'])):
						$r[$k] = $this->Initiation($v);
					endif;
				else:
					$r[$k] = $this->Initiation($v);
				endif;
			endforeach;
		else:
			$r = $p;
		endif;
		return $r;
	}
	private function Authorization($p)
	{
		$_is_auth = 0;
		foreach($p as $auth => $type):
			$self_auth = @self::$user[$auth];
			if(is_array($type)):
				if(isset($type['operator'])):
					$au_mch = ($self_auth)?$self_auth:0;
					if(eval("return ($au_mch {$type['operator']} {$type[0]});")):
						$_is_auth ++;
					endif;
				else:
					if(in_array($self_auth,$type)) $_is_auth ++;
				endif;
			elseif(is_numeric($type) and $self_auth >= $type):
				$_is_auth ++;
			endif;
		endforeach;
		return $_is_auth;
	}
	public function Menu($pages,$type=NULL,$sub=NULL)
	{
		foreach($pages as $m => $page) {

		}
	}
}
