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
		// if (!$_SESSION["verso.page"]) {
		// 	$_SESSION["verso.page"] = $this->VersoExtends(func_get_args()[0]);
		// }
		// Config::$Verso['page'] = $_SESSION["verso.page"];
		// NOTE: testing
		Config::$Verso['page'] = $this->VersoExtends(func_get_args()[0]);
		// print_r(Config::$Verso['page']);
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
	/*
	private function VersoAuthorized_____($page)
	{
		$_is_auth = 0;
		foreach($page as $auth => $type)
		{
			$self_auth = @self::$user[$auth];
			if (is_array($type)) {
				if (isset($type['operator'])) {
					$au_mch = ($self_auth)?$self_auth:0;
					if (eval("return ($au_mch {$type['operator']} {$type[0]});")) {
						$_is_auth ++;
					}
				} else {
					if (in_array($self_auth,$type)) {
						$_is_auth ++;
					}
				}
			} elseif (is_numeric($type) and $self_auth >= $type) {
				$_is_auth ++;
			}
		}
		return $_is_auth;
	}
	*/
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
	private function VersoMenu($menuContent,$menuClass)
	{
		// $i = array();
		// foreach ($menuContent as $k => $v) {
		// 	if (is_string($type=$v[Config::$APT]) && $this->VersoMenuOption->type == $type && $this->VersoExists($v)) {
		// 		$class = array($k);
		// 		if (is_string($this->VersoMenuOption->activeClass)) {
		// 			if ($this->VersoCurrent(count($v['href'])-1) == $k) {
		// 				array_push($class,$this->VersoMenuOption->activeClass);
		// 			}
		// 		}
		// 		$link = array(
		// 			'a'=>array(
		// 				'text'=>$v[Config::$APE], 'attr'=>array('href'=>implode($v['href'],Config::SlA))
		// 			)
		// 		);
		// 		if (is_string($this->VersoMenuOption->hasChild) && $context = $this->VersoMenuEngine($v)) {
		// 			array_push($class,$this->VersoMenuOption->hasChild);
		// 			array_push($link, $this->VersoMenu($context,$k.$this->VersoMenuOption->suffixChild));
		// 		}
		// 		$i[] = array(
		// 			$this->VersoMenuOption->list=>array(
		// 				'text'=>$link, 'attr'=>array('class'=>$class)
		// 			)
		// 		);
		// 	}
		// }
		// return array(
		// 	$this->VersoMenuOption->menu=>array(
		// 		'text'=>$i, 'attr'=>array('class'=>$menuClass)
		// 	)
		// );
		return array(
			$this->VersoMenuOption->menu=> array(
				'text'=>array_map(
					function ($k, $v) {
						if (is_string($type=$v[Config::$APT]) && $this->VersoMenuOption->type == $type && $this->VersoExists($v)) {
							$class = array($k);
							if (is_string($this->VersoMenuOption->activeClass)) {
								if ($this->VersoCurrent(count($v['href'])-1) == $k) {
									array_push($class,$this->VersoMenuOption->activeClass);
								}
							}
							$link = array(
								'a'=>array(
									'text'=>$v[Config::$APE], 'attr'=>array('href'=>implode($v['href'],Config::SlA))
								)
							);
							if (is_string($this->VersoMenuOption->hasChild) && $context = $this->VersoMenuEngine($v)) {
								array_push($class,$this->VersoMenuOption->hasChild);
								array_push($link, $this->VersoMenu($context,$k.$this->VersoMenuOption->suffixChild));
							}
							return array(
								$this->VersoMenuOption->list=>array(
									'text'=>$link, 'attr'=>array('class'=>$class)
								)
							);
						}
					}, array_keys($menuContent), $menuContent
				),
				'attr'=>['class'=>$menuClass]
			)
		);
	}
	public function menu($Option=array())
	{
		// $timeStart = microtime(true);
		// NOTE: check Option and merge with default
		$this->VersoMenuOption = (object) array_merge(Config::$VersoMenuOption,$Option);
		//TODO: class:isCurrent, hasChild href:getPath
		//TODO: custom tag, class, href:reference to other domain(blank), home, Auth
		$menu = $this->VersoMenu(Config::$Verso['page'],$this->VersoMenuOption->menuClass);
		// print_r(Config::$Verso['page']);
		// $timeEnd = microtime(true);
		// echo round(($timeEnd - $timeStart), 3);
		// print_r($menu);
		return $this->html($menu);

	}
}
