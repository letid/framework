<?php
namespace Letid\Id;
/*
MenuLanguage::request(option)->response()
*/
class MenuLanguage
{
	private $content, $option;
	public function __construct($option=array())
	{
		if ($option) {
			$this->option = array_merge(Application::$VersoMenuOption,$option);
		} else {
			$this->option = Application::$VersoMenuOption;
		}
		$this->content = $this->Initiate(Application::$langname);
		// Application::content('Menu_Language')->set('sdfe');
		// print_r(Application::$langlist);
		// print_r(Application::$langname);
		// print_r(Application::$language);
	}
	static function request($Id=null)
    {
        return new self($Id);
    }
	public function response()
	{
		Application::content($this->option['varName'].'language')->set(Application::html(
			array(
				$this->option['menu']=>array(
					'text'=>$this->content,
					'attr'=>array(
						'class'=>array(
							$this->option['menuClass'],'language'
						)
					)
				)
			)
		));
	}
	private function Initiate($Content)
	{
		$i = array();
		foreach ($Content as $k => $v) {
			$class = array($k);
			if (is_string($this->option['activeClass'])) {
				if ($v) {
					array_push($class,$this->option['activeClass']);
				}
			}
			$link = array(
				'a'=> array(
					'text' => $k,
					'attr' => array('href'=>'?'.Application::$langpara.'='.$k)
				)
			);
			$list = array(
				$this->option['list']=>array(
					'text'=>$link, 'attr'=>array('class'=>$class)
				)
			);
			$i[] = $list;
		}
		return $i;
	}
	public function __toString()
	{
		$this->response();
	}
}
