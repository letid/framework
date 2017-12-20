<?php
namespace letId\http
{
	/*
	$verso = new versoPrevious($route->page);
	$verso->set();
	$verso->execute();
	*/
	class versoPrevious
	{
		private $userRoute;
		public function __construct()
		{
			$userRoute = new avail::$classRequest['ASR'];
			// $session = avail::session('verso')->version();
      // if ($session->not()) {
      //   avail::$Verso = $session->set($this->setEngine($userRoute->page));
      // } else {
      //   avail::$Verso = $session->get();
      // }
			// print_r($userRoute->page);
			avail::$Verso = $this->setEngine($userRoute->page);
			// print_r(avail::$Verso);
			$this->execute();
		}
		private function setEngine($menu,$href=array())
		{
			// TODO: Auth operator - avail::SlA
			array_walk($menu,function(&$v,$k) use ($href) {
				// NOTE: is Page, and Authorized
				if ($this->setAuthorization($v,avail::$config['APA'])) {
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
		private function execute()
		{
			$Id = $this->executeArrange();
			$versoClass = $Id[avail::$config['APC']];
			$versoMethod = $Id[avail::$config['APM']];
			if ($versoMap=module::load($versoClass)->map()) {

				if (method_exists($versoMap,$versoMethod) && is_callable(array($versoMap,$versoMethod))) {
					// NOTE: Current's concluded!
					avail::$responseContext = call_user_func_array(array($versoMap,$versoMethod),array());
					// if (is_callable(array($versoMap,$versoMethod.avail::$config['AHS']),false,$methodConcluded)) {
					// 	call_user_func_array(array($versoMap,$methodConcluded),array());
					// }
					// NOTE: Class's concluded!
					if (is_callable(array($versoMap,avail::$config['AHF'].avail::$config['AHS']),false,$classConcluded)) {
						call_user_func_array(array($versoMap,$classConcluded),array());
					}
					avail::$responseMethod = avail::$responseType;
					if (property_exists($versoMap, 'responseType')) {
						if ($versoMap->responseType) {
							avail::$responseType = $versoMap->responseType;
							if (is_array($versoMap->responseType)) {
								avail::$responseMethod = 'none';
							} elseif (is_callable(array(avail::$classExtension['response'], $versoMap->responseType))) {
								avail::$responseMethod = $versoMap->responseType;
							}
						}
					}
					// if (property_exists($versoMap, 'responseType')) {
					// 	if ($versoMap->responseType) avail::$responseType = $versoMap->responseType;
					// }
					if (property_exists($versoMap, 'responseHeader')) {
						avail::$responseHeader = array_merge((array)$versoMap->responseHeader,avail::$responseHeader);
					}
					// avail::$responseMethod = avail::$responseType;
				} else {
					// avail::assist(14)->log('is_not_callable');
					assign::template('method')->error(array(
						'class'=>avail::$config['ARO'].avail::$config['ASP'].avail::SlA.$versoClass, 'method'=>$versoMethod
					));
				}

			} else {
				// TODO: disable initial()->error() on live application
				// avail::assist(14)->log('is_not_callable');
				assign::template('class')->error(array(
					'class'=>$versoClass, 'root'=>avail::$config['ARO'].avail::$config['ASP'].avail::SlA,
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
		private function setAuthorization($i,$Auth)
		{
			// avail::$classExtension['authentication']
			// avail::$authentication
			if (isset($i[$Auth])) {
				$y=$i[$Auth];
				if (is_array($y)) {
					$isOk = 0;
					foreach($y as $k => $v) {
						if (is_numeric($k)) {
							if (call_user_func_array(array(avail::$authentication, $v),array())) {
								$isOk ++;
							}
						} else {
							if (is_scalar($v)) {
								$v = array($v);
							}
							if (call_user_func_array(array(avail::$authentication, $k), array($v))) {
								$isOk ++;
							}
						}
					}
					return count($y) == $isOk;
				} else {
					return call_user_func_array(array(avail::$authentication, $y),array());
				}
			} else {
				return true;
			}
		}
	}
}