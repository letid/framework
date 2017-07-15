<?php
namespace letId\response
{
	/**
	* verso::nav(option)->request();
	*/
	class versoPrevious
	{
		protected $requestContent, $requestOption, $requestMenu = array();
		public function __construct($requestOption=array())
		{
			// if ($requestOption) {
			// 	$this->requestOption = array_merge(avail::$VersoMenuOption,$requestOption);
			// } else {
			// 	$this->requestOption = avail::$VersoMenuOption;
			// }
			$this->requestOption = array_merge(avail::$VersoMenuOption,$requestOption);
			$this->menuEngine();
			// print_r(avail::$VersoMenuOption);
			// print_r(avail::$Verso);
		}
		private function menuEngine()
    {
			if (!avail::$VersoMenu){
				foreach (avail::$Verso as $key => $value) {
					if (is_array($value) && isset($value['Type'])){
						if (!isset(avail::$VersoMenu[$value['Type']])) avail::$VersoMenu[$value['Type']]=array();
						avail::$VersoMenu[$value['Type']][$key]=$value;
					}
				}
	    }
    }
		/**
		* verso::menu(option)->??;
		*/
		static function menu($Id=array())
    {
			return new self($Id);
    }
		/**
		* verso::menu(option)->request();
		*/
		public function request()
		{
			$this->requestEngine(avail::$Verso);
		}
		/**
		* verso::menu(option)->requestOne(name from verso type);
		*/
		public function requestOne($Id)
		{
			if (isset(avail::$VersoMenu[$Id]) && !avail::content($this->requestOption['varName'].$Id)->has()) $this->requestEngine(avail::$VersoMenu[$Id]);
		}
		private function requestEngine($menu)
		{
			$this->requestInitiate($menu,$this->requestOption['menuClass'],array());
			foreach ($this->requestMenu as $k => $v) {
				// $attr=array('class'=>array($this->requestOption['menuClass'],$k));
				avail::content($this->requestOption['varName'].$k)->set(avail::html(
					array(
						$this->requestOption['menu']=>array(
							'text'=>$v,
							// 'attr'=>array_merge($this->requestOption['menuAttr'],$attr)
							'attr'=>array_merge(array('class'=>array($this->requestOption['menuClass'],$k)),$this->requestOption['menuAttr'])
							// 'attr'=>array(
							// 	'class'=>array(
							// 		$this->requestOption['menuClass'],$k
							// 	)
							// )
						)
					)
				));
			}
		}
		private function requestInitiate($Id,$menuClass,$Parent)
		{
			$i = array();
			foreach ($Id as $k => $v) {
				if (isset($v[avail::$config['APT']]) && is_string($type=$v[avail::$config['APT']])) {
					$class = array($k);
					$ParenthasChild=0;
					if (is_string($this->requestOption['activeClass'])) {
						if ($this->requestActive(count($v['href'])-1) == $k) {
							if (is_array($Parent) || $Parent) {
								array_push($class,$this->requestOption['activeClass']);
								$ParenthasChild=1;
							}
						}
					}
					$link = array(
						// 'a'=> array(
						// 	'text' => $this->requestLink($v[avail::$config['APE']]), 'attr' => $this->requestAttr($v)
						// )
						'a'=> array(
							'text' => $this->requestLinkFilter($v[avail::$config['APE']]), 
							'attr' => $this->requestAttr($v)
						)
					);
					if (is_string($this->requestOption['hasChild'])) {
						if ($Children = $this->requestFilter($v)) {
							array_push($class,$this->requestOption['hasChild']);
							$hasChild = array(
								$this->requestOption['menu']=>array(
									'text'=>$this->requestInitiate($Children,$k.$this->requestOption['hasChildSuffix'],$ParenthasChild), 'attr'=>array('class'=>$menuClass)
								)
							);
							array_push($link, $hasChild);
						}
					}
					$list = array(
						$this->requestOption['list']=>array(
							'text'=>$link, 'attr'=>array('class'=>$class)
						)
					);
					if(is_array($Parent)) {
						$this->requestMenu[$type][] = $list;
					} else {
						$i[] = $list;
					}
				}
			}
			return $i;
		}
		private function requestFilter($v=array())
		{
			return array_filter($v, function($i){
				if (is_array($i)) {
					return isset($i[avail::$config['APE']]) || isset($i[avail::$config['APC']]) || isset($i[avail::$config['APM']]);
				}
			});
		}
		private function requestLinkFilter($i)
		{
			if (is_array($i)) {
				$r=array();
				foreach ($i as $k => $v) {
					$r[]=array(
						'span' => array(
							'text' => $this->requestLink($v)
						)
					);
				}
				return $r;
			} else {
				return array(
					'span' => array(
						'text' => $this->requestLink($i)
					)
				);
			}
		}
		private function requestLink($y)
		{
			return preg_replace_callback(avail::$config['ATR'],
				function ($k) {
					return $this->requestText($k[1]);
				}, $this->requestText($y)
			);
		}
		private function requestText($k)
		{
			// print_r($k);
			if (avail::content($k)->resolve()) {
				// NOTE: content has it
				return avail::content($k);
			} elseif (avail::language($k)->has()) {
				// NOTE: language has it
				return avail::language($k);
			// } elseif (is_array($k)) {
			// 	// NOTE: language has it
			// 	return avail::$user->displayname;
			} else {
				// NOTE: when uppercase
				return $k;
			}

			// if (is_scalar($k)) return $k;
		}
		private function requestAttr($k)
		{
			$attr = array();
			if (isset($k[avail::$config['APL']])) {
				$attr['href'] = $k[avail::$config['APL']];
				if (avail::filter($k[avail::$config['APL']])->response(FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
					$attr['target'] = '_blank';
				}
			} else {
				$link = avail::SlA.implode($k['href'],avail::SlA); //avail::$http
				if ($this->requestOption['fullURL']) {
					$link = avail::$http.$link;
				}
				$attr['href'] = $link;
			}
			return $attr;
		}
		private function requestActive($key)
		{
			if (isset(avail::$VersoURI[$key])) return avail::$VersoURI[$key];
		}
		public function __toString()
		{
			return $this->request();
		}
	}
}