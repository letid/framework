<?php
namespace Letid\Id;
trait Verso
{
	/*
	navigator -> EMPTY OR NOT DEFINED, it's NULL
	Auth=>array('user'=>'member','age'=>18,'country'=>'NO','ethnic'=>'zomi','level'=>'1','id'=>'1','code'=>'454345MJ')
	Auth->user=>all,visitor,member,manager,administrator

			'Auth'=>array(
				'title'=>array('1'=>array()),
				'age'=>18,
				'age'=>array('30','operator'=>'>='),
				'country'=>array('NO'),
				'ethnic'=>array('zomi'),
				'level'=>array('1'),
				'id'=>array('1'),
				'code'=>array('KDHIE3454')
			)
	*/
	private function VersoRequest()
	{
		Config::$Verso['host'] = func_get_args()[0];
	}
	private function VersoInitiate()
	{
		// TODO: session or cookie
		// NOTE: get verso session id
		$id = $this->SessionID('verso');
		if (!$_SESSION[$id]) {
			$_SESSION[$id] = $this->VersoExtends(func_get_args()[0]);
		}
		Config::$Verso['page'] = $_SESSION[$id];
	}
	private function VersoExtends($menu,$href=array())
	{
		// TODO: Auth operator
		array_walk($menu,function(&$v,$k) use ($href) {
			// NOTE: is Page, and Authorized
			if ($this->VersoExists($v,$this->APA)) {
				// NOTE: create href
				array_push($href,$k);
				// NOTE: has child
				$v = $this->VersoExtendsEngine($v,$href);
				if (!$v[Config::$APE]) {
					// NOTE: has Menu
					$v[Config::$APE] = ucfirst($k);
				}
				// NOTE: merge with default, required in Menu
				$v = array_merge(Config::$VersoMenuDefault,$v,array('href'=>$href));
				/*
				// HACK: current
				$x = count($href)-1;
				if ($this->VersoCurrent($x) == $k) {
					$v['current'] = true;
				}
				*/
			} else {
				$v = '';
			}
		});
		return $menu;
	}
	private function VersoExtendsEngine($v,$href)
	{
		if ($ol = array_filter($v, function($y, $x) {if ($this->VersoExists($y)) return $y;})) {
			return array_merge($v,$this->VersoExtends($ol,$href));
		}
		return $v;
	}
	private function VersoResponse()
	{
		// $VersoURI required for VersoMenu
		Config::$VersoURI = Config::$uri;
		$Task = array_merge(Config::$VersoArrangeDefault,$this->VersoArrange(Config::$Verso['page'],Config::$uri));
		$this->VersoClass = $Task[Config::$APC];
		$this->VersoMethod = $Task[Config::$APM];
	}
	private function VersoCurrent($key)
	{
		if (isset(Config::$VersoURI[$key])) return Config::$VersoURI[$key];
	}
	private function VersoArrange($v, $k)
	{
		if ($k && is_array($y = $v[$k[0]])) {
			if (array_shift($k) && $i=$this->VersoArrangeItem($y, $k[0])) {
				return array_replace($i,$this->VersoArrange($y,$k));
			} else {
				return $y;
			}
		} else {
			// NOTE: Verso not Exists, update URI for VersoMenu, required for home!
			Config::$VersoURI = array(Config::$APH);
			return array();
		}
	}
	private function VersoArrangeItem($v, $k)
	{
		if ($this->VersoExists($v[$k])) {
			return array_filter(array_map(
				function ($y) {
					if ($this->VersoExists($y) !== true) return $y;
				}, $v
			));
		}
	}
	private function VersoExists($i,$o='')
	{
		// NOTE: used in ArrangeItem, MenuItem??
		if (is_array($i) and isset($i[Config::$APE]) || isset($i[Config::$APC]) || isset($i[Config::$APM])) {
			 if ($o) {
				// NOTE: Verso Authorization
				if ($this->ModuleAuth && is_array($y=$i[$o])) {
					$isOk = 0;
					foreach($y as $k => $v) {
						if ($this->ModuleAuth->{$k}($v)) $isOk ++;
					}
					return (count($y) == $isOk)?true:false;
				}
			 }
			 return true;
		}
	}
	private function VersoMenuEngine($v)
	{
		return array_filter($v, function($y, $x){if ($this->VersoExists($y)) return $y;});
	}
	private function VersoMenu(&$Menu,$menuContent,$Option,$menuClass,$is)
	{
		$i = array();
		foreach ($menuContent as $k => $v) {
			if (is_string($type=$v[Config::$APT]) && $this->VersoExists($v)) {//&& $Option->type == $type
				$class = array($k);
				if (is_string($Option->activeClass)) {
					if ($this->VersoCurrent(count($v['href'])-1) == $k) {
						array_push($class,$Option->activeClass);
					}
				}
				$link = array(
					'a'=>array(
						'text'=>$this->lang($v[Config::$APE]), 'attr'=>array('href'=>implode($v['href'],Config::SlA))
					)
				);
				if (is_string($Option->hasChild) && $context = $this->VersoMenuEngine($v)) {
					array_push($class,$Option->hasChild);
					array_push($link, $this->VersoMenu($Menu,$context,$Option,$k.$Option->suffixChild));
				}
				$list = array(
					$Option->list=>array(
						'text'=>$link, 'attr'=>array('class'=>$class)
					)
				);
				if($is) {
					$Menu[$type][] = $list;
				} else {
					$i[] = $list;
				}
			}
		}
		if ($i) {
			return array(
				$Option->menu=>array(
					'text'=>$i, 'attr'=>array('class'=>$menuClass)
				)
			);
		}
	}
	public function menu($Option=array())
	{
		// $timeStart = microtime(true);
		// NOTE: check Option and merge with default
		$Option = (object) array_merge(Config::$VersoMenuOption,$Option);
		$Menu = array();
		$this->VersoMenu($Menu,Config::$Verso['page'],$Option,$Option->menuClass,true);
		foreach ($Menu as $k => $i) {
			$varName=$Option->varName.'_'.$k;
			$this->{$varName} = $this->html(
				array(
					$Option->menu=>array(
						'text'=>$i, 'attr'=>array('class'=>array($Option->menuClass,$k))
					)
				)
			);
		}
	}
}
