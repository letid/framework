<?php
namespace Letid\Id;
trait Verso
{
	private function VersoRequest()
	{
		Config::$Verso['host'] = func_get_args()[0];
	}
	private function VersoInitiate()
	{
		// NOTE: get verso session id
		$id = $this->SessionID('verso');
		if (!isset($_SESSION[$id])) {
			$_SESSION[$id] = $this->VersoEngine(func_get_args()[0]);
		}
		Config::$Verso['page'] = $_SESSION[$id];
	}
	private function VersoEngine($menu,$href=array())
	{
		// TODO: Auth operator - Config::SlA
		array_walk($menu,function(&$v,$k) use ($href) {
			// NOTE: is Page, and Authorized
			if ($this->VersoAuth($v,$this->APA)) {
				// NOTE: create href
				array_push($href,$k);
				// NOTE: has child
				$v=$this->VersoFilter($v,$href);
				// print_r($v);
				// NOTE: has Menu
				if (!isset($v[Config::$APE])) {
					$v[Config::$APE] = ucfirst($k);
				}
				// NOTE: merge with default, required in Menu
				$v = array_merge(Config::$VersoMenuDefault,$v,array('href'=>$href));
			}
		});
		return $menu;
	}
	private function VersoFilter($v,$href)
	{
		// return $this->VersoEngine(array_filter($v, function($k) {if ($this->VersoExists($k)) return $k;}),$href);
		return array_merge($v,$this->VersoEngine(array_filter($v, function($k) {if ($this->VersoExists($k)) return $k;}),$href));
	}
	private function VersoResponse()
	{
		// $VersoURI required for Menu
		Config::$VersoURI = Config::$uri;
		$Task = array_merge(Config::$VersoArrangeDefault,$this->VersoArrange(Config::$Verso['page'],Config::$uri));
		$this->VersoClass = $Task[Config::$APC];
		$this->VersoMethod = $Task[Config::$APM];
	}
	private function VersoArrange($v, $k)
	{
		if ($k && is_array($y = $v[$k[0]])) {
			if (array_shift($k) && isset($k[0]) && $i=$this->VersoArrangeItem($y, $k[0])) {
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
		if (isset($v[$k]) && $this->VersoExists($v[$k])) {
			return array_filter(array_map(
				function ($y) {
					if ($this->VersoExists($y) !== true) return $y;
				}, $v
			));
		}
	}
	private function VersoExists($i)
	{
		if (is_array($i)) {
			if (isset($i[Config::$APE]) || isset($i[Config::$APC]) || isset($i[Config::$APM])) {
				return true;
			}
		}
	}
	private function VersoAuth($i,$Auth)
	{
		if ($this->ModuleAuth) {
			if (isset($i[$Auth])) {
				$y=$i[$Auth];
				if (is_array($y)) {
					$isOk = 0;
					foreach($y as $k => $v) {
						if ($this->ModuleAuth->{$k}($v)) $isOk ++;
					}
					return (count($y) == $isOk)?true:false;
				}
			} else {
				return true;
			}
		}
	}
}