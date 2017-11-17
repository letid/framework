<?php
namespace letId\response
{
	class verso
	{
		protected $requestContent, $requestOption, $requestMenu = array();
		public function __construct($requestOption=array())
		{
			$this->requestOption = array_merge(avail::$VersoMenuOption,$requestOption);
			if (!avail::$VersoMenu) $this->menuEngine();
		}
		private function menuEngine()
    {
			foreach (avail::$Verso as $key => $value) {
				if (is_array($value) && isset($value['Type'])){
					if (!isset(avail::$VersoMenu[$value['Type']])) avail::$VersoMenu[$value['Type']]=array();
					avail::$VersoMenu[$value['Type']][]=$value;
				}
			}
    }
		/*
		verso::requestTotal(Id)
		*/
		static function requestTotal($Id='menu.total')
		{
			avail::content($Id)->set(count(avail::$Verso));
		}
		/*
		verso::menu(option)->??;
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
			// if (isset(avail::$VersoMenu[$Id])) $this->requestEngine(avail::$VersoMenu[$Id]);

			// print_r(avail::$VersoMenu[$Id]);
		}
		private function requestEngine($menu)
		{
			$this->requestInitiate($menu,$this->requestOption['class'],array());
			foreach ($this->requestMenu as $k => $v) {
				// $attr=array('class'=>array($this->requestOption['class'],$k));
				avail::content($this->requestOption['varName'].$k)->set(avail::html(
					array(
						$this->requestOption['menu']=>array(
							'text'=>$v,
							// 'attr'=>array_merge($this->requestOption['attr'],$attr)
							'attr'=>array_merge(array('class'=>array($this->requestOption['class'],$k)),$this->requestOption['attr'])
							// 'attr'=>array(
							// 	'class'=>array(
							// 		$this->requestOption['class'],$k
							// 	)
							// )
						)
					)
				));
			}
		}
		private function requestInitiate($menuRoute,$menuClass,$Parent)
		{
			$i = array();
			$APT = avail::$config['APT'];
			$APF = avail::$config['APF'];
			$APE = avail::$config['APE'];

			foreach ($menuRoute as $k => $v) {
				if (isset($v[$APT]) && is_string($type=$v[$APT])) {
					$name=$v[$APF];
					// $class = array($k);
					$class = array();
					if ($name)array_push($class,$name);
					$ParenthasChild=0;
					if (is_string($this->requestOption['activeClass'])) {
						if ($this->requestActive(count($v['href'])-1) == $name || (!$name && avail::$VersoURI[0] == avail::$config['APH'] ) ) {
							if (is_array($Parent) || $Parent) {
								array_push($class,$this->requestOption['activeClass']);
								$ParenthasChild=1;
							}
						}
					}
					$link = array(
						'a'=> array(
							'text' => $this->requestLinkFilter($v[$APE]),
							'attr' => $this->requestAttr($v)
						)
					);
					if (is_string($this->requestOption['hasChild'])) {
						if ($Children = $this->requestFilter($v)) {
							array_push($class,$this->requestOption['hasChild']);
							$hasChild = array(
								$this->requestOption['menu']=>array(
									'text'=>$this->requestInitiate($Children,$name.$this->requestOption['hasChildSuffix'],$ParenthasChild), 'attr'=>array('class'=>$menuClass)
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
			return array_map(function($v) {
				return array(
					'span' => array(
						'text' => $this->requestLink($v)
					)
				);
			}, is_array($i)?$i:array($i));
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
			if (avail::content($k)->has()) {
				// NOTE: content has it
				return avail::content($k)->get();
			} elseif (avail::language($k)->has()) {
				// NOTE: language has it
				return avail::language($k);
			// } elseif (is_array($k)) {
			// 	// NOTE: language has it???
			// 	return avail::$user->displayname;
			} else {
				// NOTE: when uppercase
				return $k;
			}
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
				// $link = avail::SlA.implode(avail::SlA,$k['href']);
				$link = implode(avail::SlA,$k['href']);
				if ($this->requestOption['fullURL']) {
					$link = avail::$http.avail::SlA.$link;
				} else {
					$link = avail::$hostSlA.$link;

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
