<?php
namespace letId\http
{
	/*
	$verso = new verso($route->page);
	$verso->set();
	$verso->execute();
	*/
	class verso
	{
		private $userRoute;
		public function __construct()
		{
			$userRoute = new avail::$classRequest['ASR'];
			// TODO: if $userRoute->page is not available
			// $session = avail::session('verso')->version();
      // if ($session->not()) {
      //   avail::$Verso = $session->set($this->setEngine($userRoute->page));
      // } else {
      //   avail::$Verso = $session->get();
      // }
			// print_r($userRoute->page);
			avail::$Verso = $this->setEngine($userRoute->page);
			// $this->setEngine($userRoute->page);
			// print_r(avail::$Verso);
			$this->execute();
		}
		/*
		$child = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_numeric')));
		$parent = array_filter($v, function ($k) { return !is_numeric($k); }, ARRAY_FILTER_USE_KEY);
		$APF = avail::$config['APF'];
		if (!isset($v[$APE])) {
			if (isset($v[$APF])) {
				$v[$APE] = ucfirst($v[$APF]);
			} else {
				$v[$APE] = $k;
			}
		}
		*/
		private function setEngine($routes,$link=array())
		{
			// TODO: Auth operator - avail::SlA
			$routeMenu = array();
			$APE = avail::$config['APE'];
			$APF = avail::$config['APF'];
			$APA = avail::$config['APA'];
			foreach ($routes as $k => $v) {
				if ($this->setAuthentication($v,$APA) && isset($v[$APF])) {
					// NOTE: create href
					$href = array($v[$APF]);
					if ($link) $href = array_merge($link,$href);
					$v['href'] = $href;
					if (!isset($v[$APE])) {
						if ($v[$APF]) {
							$v[$APE] = ucfirst($v[$APF]);
						} else {
							$v[$APE] = false;
						}
					}

					// NOTE: get Children
					$routeChildren = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_numeric')));
					// NOTE: get Parent
					$routeParent = array_filter($v, function($k) { return !is_numeric($k); }, ARRAY_FILTER_USE_KEY);
					$v=array_merge($routeParent,$this->setFilter($routeChildren,$href));
					$routeMenu[]=array_merge(avail::$VersoMenuDefault,$v);
				}
			}
			return $routeMenu;
			/*
			$APE = avail::$config['APE'];
			$APF = avail::$config['APF'];
			$APA = avail::$config['APA'];
			foreach ($routes as $k => $v) {
				if ($this->setAuthentication($v,$APA) && isset($v[$APF])) {
					// NOTE: create href
					$href = array($v[$APF]);
					if ($link) $href = array_merge($link,$href);
					$v['href'] = $href;
					if (!isset($v[$APE])) {
						if ($v[$APF]) {
							$v[$APE] = ucfirst($v[$APF]);
						} else {
							$v[$APE] = false;
						}
					}

					// NOTE: get Children
					$routeChildren = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_numeric')));
					// NOTE: get Parent
					$routeParent = array_filter($v, function($k) { return !is_numeric($k); }, ARRAY_FILTER_USE_KEY);
					// $routeParent = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_scalar')));
					$v=array_merge($routeParent,$this->setFilter($routeChildren,$href));
					$routes[$k] = array_merge(avail::$VersoMenuDefault,$v);
				} else {
					unset($routes[$k]);
				}
			}
			return $routes;
			*/
			/*
			$routeMenu = array();
			foreach ($routePage as $k => $v) {
				if ($this->setAuthentication($v,avail::$config['APA'])) {
					$APE = avail::$config['APE'];
					$APF = avail::$config['APF'];
					// NOTE: create href
					$href = array($v[$APF]);
					if ($link) $href = array_merge($link,$href);
					$v['href'] = $href;
					// NOTE: get Children
					$children = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_numeric')));
					// NOTE: get Parent
					$v=array_merge(array_filter($v, function($k) { return !is_numeric($k); }, ARRAY_FILTER_USE_KEY),$this->setFilter($children,$href));
					// NOTE: merge with default
					$routeMenu[] = array_merge(avail::$VersoMenuDefault,$v);
				}
			}
			return $routeMenu;
			*/
			/*
			array_walk($routePage,function(&$v,$k) use ($href) {
				// NOTE: is Page, and Authorized
				if ($this->setAuthentication($v,avail::$config['APA'])) {
					$APE = avail::$config['APE'];
					$APF = avail::$config['APF'];
					// NOTE: create href
					if (isset($v[$APF])) array_push($href,$v[$APF]);
					$v['href']=$href;
					// $v['current']='active';
					// NOTE: has child
					$child = array_intersect_key($v, array_flip(array_filter(array_keys($v), 'is_numeric')));
					if ($child) {
						$v=array_merge(array_filter($v, function($k) { return !is_numeric($k); }, ARRAY_FILTER_USE_KEY),$this->setFilter($child,$href));
					}
					// if (array_search(avail::$uri[0],array_column($v, $APF)) > -1) $v['current']='active';
					$v = array_merge(avail::$VersoMenuDefault,$v);
				} else {
					$v=null;
				}
			});
			return array_filter($routePage);
			*/
		}
		private function setFilter($v,$href)
		{
			// return $this->setEngine(array_filter($v, function($k) {if ($this->setExists($k)) return $k;}),$href);
			return $this->setEngine(array_filter($v, function($k) {if ($this->setExists($k)) return $k;}),$href);
		}
		private function requestArrange()
		{
			// $VersoURI required for Menu
			avail::$VersoURI = avail::$uri;
			return array_merge(avail::$VersoArrangeDefault,$this->requestArrangeCurrent(avail::$Verso,avail::$uri));
			// return $this->setArrange(avail::$Verso,avail::$uri);
		}
		private function execute()
		{
			$Id = $this->requestArrange();
			$versoClass = $Id[avail::$config['APC']];
			$versoMethod = $Id[avail::$config['APM']];
			// print_r(avail::$VersoURI);
			// print_r($Id);
			// print_r(avail::$Verso);
			// echo 'Ok';
			// echo avail::$http,avail::SlA,implode('/',$versoHref);
			avail::$contents['uriCurrent'] = implode('/',$Id['href']);
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
					if (property_exists($versoMap, 'responseHeader')) {
						avail::$responseHeader = array_merge((array)$versoMap->responseHeader,avail::$responseHeader);
					}
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
		private function requestArrangeCurrent($uriRoute, $uri,$uriChild=null)
		{
			$keyword = array_shift($uri);
			$APF = avail::$config['APF'];
			$i=array_search($keyword,array_column($uriRoute, $APF));
			// $i = array_search($keyword,array_combine(array_keys($uriRoute), array_column($uriRoute, $APF)));
			if ($i > -1 ) {
				$page = $uriRoute[$i];
				if ($uri and $y = $this->requestArrangeCurrent($page,$uri,1)) {
					return array_merge($page,$y);
				}
				return $page;
			} elseif ($uriChild) {
				$page = array();
			} elseif ($uriRoute) {
				avail::$VersoURI = array(avail::$config['APH']);
				return $uriRoute[0];
			}
		}
		private function setExists($i)
		{
			if (is_array($i)) {
				return isset($i[avail::$config['APE']]) || isset($i[avail::$config['APC']]) || isset($i[avail::$config['APM']]);
			}
		}
		private function setAuthentication($i,$Auth)
		{
			// avail::$classExtension['authentication']
			if (isset($i[$Auth])) {
				$y=$i[$Auth];
				if (is_array($y)) {
					$isOk = 0;
					foreach($y as $k => $v) {
						if (is_numeric($k)) {
							if (call_user_func_array(array(avail::$authentication, $v),array())) $isOk ++;
						} else {
							if (is_scalar($v)) {
								$v = array($v);
							}
							if (call_user_func_array(array(avail::$authentication, $k), array($v))) $isOk ++;
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