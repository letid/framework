<?php
namespace Letid\Id;
/*
MenuPage::request(option)->response(content);

Application::navigation()->response();
*/
class MenuPage
{
	private $content, $option, $menu = array();
	public function __construct($option=array())
	{
		if ($option) {
			$this->option = array_merge(Application::$VersoMenuOption,$option);
		} else {
			$this->option = Application::$VersoMenuOption;
		}
	}
	static function request($Id=null)
    {
        return new self($Id);
    }
	public function response()
	{
		// $this->content = Application::$Verso['page'];
		// if (func_get_args()) {
		// 	if (func_get_args()[0]) {
		// 		$this->content = func_get_args()[0];
		// 	}
		// }
		$this->Initiate(Application::$Verso['page'],$this->option['menuClass'],array());
		foreach ($this->menu as $k => $v) {
			Application::content($this->option['varName'].$k)->set(Application::html(
				array(
					$this->option['menu']=>array(
						'text'=>$v,
						'attr'=>array(
							'class'=>array(
								$this->option['menuClass'],$k
							)
						)
					)
				)
			));
		}
	}
	private function Initiate($Content,$menuClass,$Parent)
	{
		$i = array();
		foreach ($Content as $k => $v) {
			if (isset($v[Application::$config['APT']]) && is_string($type=$v[Application::$config['APT']])) {
				$class = array($k);
				$ParenthasChild=0;
				if (is_string($this->option['activeClass'])) {
					if ($this->Active(count($v['href'])-1) == $k) {
						if (is_array($Parent) || $Parent) {
							array_push($class,$this->option['activeClass']);
							$ParenthasChild=1;
						}
					}
				}
				$link = array(
					'a'=> array(
						'text' => $this->Content($v[Application::$config['APE']]), 'attr' => $this->Attr($v)
					)
				);
				if (is_string($this->option['hasChild'])) {
					if ($Children = $this->Engine($v)) {
						array_push($class,$this->option['hasChild']);
						$hasChild = array(
							$this->option['menu']=>array(
								'text'=>$this->Initiate($Children,$k.$this->option['hasChildSuffix'],$ParenthasChild), 'attr'=>array('class'=>$menuClass)
							)
						);
						array_push($link, $hasChild);
					}
				}
				$list = array(
					$this->option['list']=>array(
						'text'=>$link, 'attr'=>array('class'=>$class)
					)
				);
				if(is_array($Parent)) {
					$this->menu[$type][] = $list;
				} else {
					$i[] = $list;
				}
			}
		}
		return $i;
	}
	private function Engine($v=array())
	{
		return array_filter($v, function($i){
			if (is_array($i)) {
				return isset($i[Application::$config['APE']]) || isset($i[Application::$config['APC']]) || isset($i[Application::$config['APM']]);
			}
		});
	}
	private function Content($y)
	{
		return preg_replace_callback(Application::$config['ATR'],
			function ($k) {
				return $this->Text($k[1]);
			}, $this->Text($y)
		);
	}
	private function Text($k)
	{
		if (Application::content($k)->has()) {
			// NOTE: content has it
			return Application::content($k);
		} elseif (Application::language($k)->has()) {
			// NOTE: language has it
			return Application::language($k);
		// } elseif (is_array($k)) {
		// 	// NOTE: language has it
		// 	return Application::$user->displayname;
		} else {
			// NOTE: when uppercase
			return $k;
		}
	}
	private function Attr($k)
	{
		$attr = array();
		if (isset($k[Application::$config['APL']])) {
			$attr['href'] = $k[Application::$config['APL']];
			if (Application::filter($k[Application::$config['APL']])->response(FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
				$attr['target'] = '_blank';
			}
		} else {
			$link = Application::SlA.implode($k['href'],Application::SlA); //Application::$http
			if ($this->option['fullURL']) {
				$link = Application::$http.$link;
			}
			$attr['href'] = $link;
		}
		return $attr;
	}
	private function Active($key)
	{
		if (isset(Application::$VersoURI[$key])) return Application::$VersoURI[$key];
	}
	public function __toString()
	{
		$this->response();
	}
}
