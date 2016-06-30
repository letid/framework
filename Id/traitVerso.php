<?php
namespace Letid\Id;
trait traitVerso
{
	private function VersoRequest($page)
	{
		$id = Application::session('verso')->id();
		if (!isset($_SESSION[$id])) {
			$_SESSION[$id] = $this->VersoEngine($page);
		}
		Application::$Verso['page'] = $_SESSION[$id];
		// print_r(Application::$Verso['page']);
	}
	private function VersoEngine($menu,$href=array())
	{
		// TODO: Auth operator - Application::SlA
		array_walk($menu,function(&$v,$k) use ($href) {
			// NOTE: is Page, and Authorized
			if ($this->VersoAuth($v,Application::$config['APA'])) {
				// NOTE: create href
				$APE = Application::$config['APE'];
				array_push($href,$k);
				// NOTE: has child
				$v=$this->VersoFilter($v,$href);
				// print_r($v);
				// NOTE: has Menu
				if (!isset($v[$APE])) {
					$v[$APE] = ucfirst($k);
				}
				// if (!isset($v[$APE])) {
				// 	$v[$APE] = ucfirst($k);
				// } elseif (is_array($v[$APE])) {
				// 	// NOTE: add name
				// 	$v[$APE] = $this->authorization->user->displayname;
				// 	// $v[Application::$APE] = 'Khen Solomon Lethil';
				// }
				// NOTE: merge with default, required in Menu
				$v = array_merge(Application::$VersoMenuDefault,$v,array('href'=>$href));
			} else {
				$v='';
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
		Application::$VersoURI = Application::$uri;
		$Task = array_merge(Application::$VersoArrangeDefault,$this->VersoArrange(Application::$Verso['page'],Application::$uri));
		// print_r($Task);
		$this->versoClass = $Task[Application::$config['APC']];
		$this->versoMethod = $Task[Application::$config['APM']];
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
			Application::$VersoURI = array(Application::$config['APH']);
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
			if (isset($i[Application::$config['APE']]) || isset($i[Application::$config['APC']]) || isset($i[Application::$config['APM']])) {
				return true;
			}
		}
	}
	private function VersoAuth($i,$Auth)
	{
		if ($this->authorization) {
			if (isset($i[$Auth])) {
				$y=$i[$Auth];
				if (is_array($y)) {
					$isOk = 0;
					foreach($y as $k => $v) {
						if (is_numeric($k)) {
							if (call_user_func_array(array($this->authorization, $v),array($k))) $isOk ++;
						} elseif (call_user_func_array(array($this->authorization, $k), array())) {
							$isOk ++;
						}
					}
					return count($y) == $isOk;
				} else {
					return call_user_func_array(array($this->authorization, $y),array());
				}
			} else {
				return true;
			}
		}
	}
}