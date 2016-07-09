<?php
namespace letId\response;
/**
* verse::nav(option)->request()
*/
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
		$this->requestContent = $this->requestInitiate(avail::$langname);
	}
	static function nav($Id=null)
    {
        return new self($Id);
    }
	public function request()
	{
		avail::content($this->requestOption['varName'].'language')->set(avail::html(
			array(
				$this->requestOption['menu']=>array(
					'text'=>$this->requestContent,
					'attr'=>array(
						'class'=>array(
							$this->requestOption['menuClass'],'language'
						)
					)
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
				'a'=> array(
					'text' => $k,
					'attr' => array('href'=>'?'.avail::$langPara.'='.$k)
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