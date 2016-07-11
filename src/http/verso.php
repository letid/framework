<?php
namespace letId\http;
class verso
{
	public function __construct($Id)
	{
		$this->Id = $Id;
	}
	public function set()
	{
		$session = avail::session('verso')->version();
		if ($session->not()) {
			$session->set($this->setEngine($this->Id));
		}
		avail::$Verso = $session->get();
	}
	private function setEngine($menu,$href=array())
	{
		// TODO: Auth operator - avail::SlA
		array_walk($menu,function(&$v,$k) use ($href) {
			// NOTE: is Page, and Authorized
			if ($this->setAuthorization(avail::authorization(),$v,avail::$config['APA'])) {
				// NOTE: create href
				$APE = avail::$config['APE'];
				array_push($href,$k);
				// NOTE: has child
				$v=$this->setFilter($v,$href);
				// print_r($v);
				// NOTE: has Menu
				if (!isset($v[$APE])) {
					$v[$APE] = ucfirst($k);
				}
				// NOTE: merge with default, required in Menu
				$v = array_merge(avail::$VersoMenuDefault,$v,array('href'=>$href));
			} else {
				$v='';
			}
		});
		return $menu;
	}
	private function setFilter($v,$href)
	{
		// return $this->setEngine(array_filter($v, function($k) {if ($this->setExists($k)) return $k;}),$href);
		return array_merge($v,$this->setEngine(array_filter($v, function($k) {if ($this->setExists($k)) return $k;}),$href));
	}
	private function executeArrange()
	{
		// $VersoURI required for Menu
		avail::$VersoURI = avail::$uri;
		return array_merge(avail::$VersoArrangeDefault,$this->setArrange(avail::$Verso,avail::$uri));
	}
	public function execute()
	{
		$Id = $this->executeArrange();
		$versoObject = $Id[avail::$config['APC']];
		$versoMethod = $Id[avail::$config['APM']];
		if ($Obj=module::request($versoObject)->map()) {
			if (method_exists($Obj,$versoMethod) && is_callable(array($Obj,$versoMethod))) {
				avail::$contextId = call_user_func(array($Obj,$versoMethod));
				avail::$contextResponse = avail::$contextType;
				$currentSupport = array($Obj,$versoMethod.avail::$config['AHS']);
				if (is_callable($currentSupport)) {
					call_user_func($currentSupport);
				}
				$finalSupport = array($Obj,avail::$config['AHF'].avail::$config['AHS']);
				if (is_callable($finalSupport)) {
					call_user_func($finalSupport);
				}
			} else {
				// avail::assist(14)->log('is_not_callable');
				assign::template('method')->error(array(
					'class'=>avail::$config['ARO'].avail::$config['ASP'].avail::SlA.$versoObject, 'method'=>$versoMethod,
				));
			}
		} else {
			// TODO: disable initial()->error() on live application
			// avail::assist(14)->log('is_not_callable');
			assign::template('class')->error(array(
				'class'=>$versoObject, 'root'=>avail::$config['ARO'].avail::$config['ASP'].avail::SlA,
			));
		}
	}
	private function setArrange($v, $k)
	{
		if ($k && isset($v[$k[0]]) && is_array($y = $v[$k[0]])) {
			if (array_shift($k) && isset($k[0]) && $i=$this->setArrangeItem($y, $k[0])) {
				return array_replace($i,$this->setArrange($y,$k));
			} else {
				return $y;
			}
		} else {
			// NOTE: Verso not Exists, update URI for VersoMenu, required for home!
			avail::$VersoURI = array(avail::$config['APH']);
			return array();
		}
	}
	private function setArrangeItem($v, $k)
	{
		if (isset($v[$k]) && $this->setExists($v[$k])) {
			return array_filter(array_map(
				function ($y) {
					if ($this->setExists($y) !== true) return $y;
				}, $v
			));
		}
	}
	private function setExists($i)
	{
		if (is_array($i)) {
			return isset($i[avail::$config['APE']]) || isset($i[avail::$config['APC']]) || isset($i[avail::$config['APM']]);
		}
	}
	private function setAuthorization($className,$i,$Auth)
	{
		if (isset($i[$Auth])) {
			$y=$i[$Auth];
			if (is_array($y)) {
				$isOk = 0;
				foreach($y as $k => $v) {
					if (is_numeric($k)) {
						if (call_user_func(array($className, $v))) {
							$isOk ++;
						}
					} else {
						if (is_scalar($v)) {
							$v = array($v);
						}
						if (call_user_func_array(array($className, $k), $v)) {
							$isOk ++;
						}
					}
				}
				return count($y) == $isOk;
			} else {
				return call_user_func(array($className, $y));
			}
		} else {
			return true;
		}
	}
}