<?php
namespace Letid\Id;
/*
	dedicated for Http\Response
*/
trait Menu
{
	public function menu($Option=array())
	{
		$timer = new Timer();
		// NOTE: check Option and merge with default
		$Option = array_merge(Config::$VersoMenuOption,$Option);
		$Menu = array();
		$this->MenuRequest($Menu,Config::$Verso['page'],$Option,$Option['menuClass'],array());
		foreach ($Menu as $k => $i) {
			$varName=$Option['varName'].'_'.$k;
			$this->{$varName} = $this->html(
				array(
					$Option['menu']=>array(
						'text'=>$i, 'attr'=>array('class'=>array($Option['menuClass'],$k))
					)
				)
			);
		}
		$this->timer_finish = $timer->finish();
	}
	private function MenuRequest(&$Menu,$Content,$Option,$menuClass,$Parent)
	{
		$i = array();
		foreach ($Content as $k => $v) {
			if (isset($v[Config::$APT]) && is_string($type=$v[Config::$APT])) {
				$class = array($k);
				$ParenthasChild=0;
				if (is_string($Option['activeClass'])) {
					if ($this->MenuCurrent(count($v['href'])-1) == $k) {
						if (is_array($Parent) || $Parent) {
							array_push($class,$Option['activeClass']);
							$ParenthasChild=1;
						}
					}
				}
				$link=array();
				$link['a']=array('text'=>$this->lang($v[Config::$APE]));
				$link['a']['attr']=array();
				if (isset($v[Config::$APL])) {
					$link['a']['attr']['href']=$v[Config::$APL];
					// TODO: if valid link add target=_blank
				} else {
					// array_unshift($v['href'], Config::$url);
					$link['a']['attr']['href']=Config::SlA.implode($v['href'],Config::SlA);
				}
				if (is_string($Option['hasChild'])) {
					if ($context = $this->MenuEngine($v)) {
						array_push($class,$Option['hasChild']);
						$hasChild = array(
							$Option['menu']=>array(
								'text'=>$this->MenuRequest($Menu,$context,$Option,$k.$Option['suffixChild'],$ParenthasChild), 'attr'=>array('class'=>$menuClass)
							)
						);
						array_push($link, $hasChild);
					}
				}
				$list = array(
					$Option['list']=>array(
						'text'=>$link, 'attr'=>array('class'=>$class)
					)
				);
				if(is_array($Parent)) {
					$Menu[$type][] = $list;
				} else {
					$i[] = $list;
				}
				// if($Parent) {
				// 	$Menu[$type][] = $list;
				// } else {
				// 	$i[] = $list;
				// }
			}
		}
		return $i;
	}
	private function MenuEngine($v=array())
	{
		return array_filter($v, function($i){
			if (is_array($i)) {
				if (isset($i[Config::$APE]) || isset($i[Config::$APC]) || isset($i[Config::$APM])) return true;
			}
		});
	}
	private function MenuCurrent($key)
	{
		if (isset(Config::$VersoURI[$key])) return Config::$VersoURI[$key];
	}
}
