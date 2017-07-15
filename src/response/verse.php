<?php
namespace letId\response
{
	class verse
	{
		private $requestContent, $requestOption;
		public function __construct($requestOption)
		{
			if ($requestOption) {
				$this->requestOption = array_merge(avail::$VersoMenuOption,$requestOption);
			} else {
				$this->requestOption = avail::$VersoMenuOption;
			}
			$this->requestContent = $this->requestInitiate(avail::$localeName);
		}
		/*
		verse::requestTotal(Id)
		*/
		static function requestTotal($Id='locale.total')
		{
			avail::content($Id)->set(count(avail::$localeName));
		}
		/*
		verse::menu(option)->??;
		*/
		static function menu($Id=null)
	  {
	    return new self($Id);
	  }
		/*
		verse::menu(option)->request()
		*/
		public function request()
		{
			avail::content($this->requestOption['varName'].'locale')->set(avail::html(
				array(
					$this->requestOption['menu']=>array(
						'text'=>$this->requestContent,
						'attr'=>array_merge(array('class'=>array($this->requestOption['class'],'locale')),$this->requestOption['attr'])
						
						// 'attr'=>array(
						// 	'class'=>array(
						// 		$this->requestOption['class'],'locale'
						// 	)
						// )
					)
				)
			));
		}
		private function requestInitiate($Id)
		{
			$i = array();
			foreach ($Id as $k => $v) {
				$class = array($k);
				if (is_string($this->requestOption['activeClass'])) {
					if ($v) {
						array_push($class,$this->requestOption['activeClass']);
					}
				}
				$link = array(
					// 'span'=> array(
					// 	'text' => array(
					// 		'a'=> array(
					// 			'text' => $k,
					// 			'attr' => array('href'=>'?'.avail::$localePara.'='.$k)
					// 		)
					// 	)
					// )
					'a'=> array(
						'text' => $k,
						'attr' => array('href'=>'?'.avail::$localePara.'='.$k)
					)
				);
				$list = array(
					$this->requestOption['list']=>array(
						'text'=>$link, 'attr'=>array('class'=>$class)
					)
				);
				$i[] = $list;
			}
			return $i;
		}
		public function __toString()
		{
			$this->request();
		}
	}
}