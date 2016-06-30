<?php
namespace Letid\Id;
trait traitLang
{
	private function LangRequest()
	{
		/*
		HACK: language Requests created:
			Application::$langlist
			Application::$langname
		NOTE: In order to get the language work "language" in config and "language" in dir needs to set!
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		*/
		// NOTE: is language enabled by user set 'language' in config
		if ($this->langDefault=Application::$config['language']) {
			$this->langList = Application::session('lang.uage')->id();
			$this->langName = Application::session('lang.name')->id();
			// $this->langList = Session::id('lang.uage');
			// $this->langName = Session::id('lang.name');
			$lang = array(
				$this->langDefault => array()
			);
			$langpara = Application::$langpara;
			if (isset($_GET[$langpara]) && $sil=$_GET[$langpara]) {
				if (!$_SESSION['sil'] || $sil != $_SESSION['sil']) {
					// NOTE: user requested language, so we reset the previous one
					unset($_SESSION[$this->langList]);
					$_SESSION['sil'] = $sil;
					$lang[$sil]=array();
				}
			} else {
				if (!isset($_SESSION['sil'])) {
					$_SESSION['sil'] = $this->langDefault;
				}
			}
			$this->LangTerminal($lang);
		}
	}
	private function LangTerminal($lang)
	{
		if (isset($_SESSION[$this->langList]) && isset($_SESSION[$this->langName])) {
			Application::$langname = $_SESSION[$this->langName];
			Application::$langlist = $_SESSION[$this->langList];
		} else {
			$dir = Application::$dir->language;
			foreach ($this->LangDirectory($dir) as $langName) {
				if ($langName==$_SESSION['sil']) {
					$isCurrent = true;
					Application::$langname[$langName]=true;
				} else {
					Application::$langname[$langName]=false;
				}
				if (array_key_exists($langName,$lang)) {
					foreach ($this->LangDirectory($dir.$langName) as $fileName) {
						$filePath = $dir.$langName.Application::SlA.$fileName;
						$file = pathinfo($filePath);
						if($file['extension'] == Application::$Extension['language']) {
							if ($tmp=parse_ini_file($filePath)) {
								$lang[$langName] = array_merge_recursive($lang[$langName],$tmp);
							}
						}
					}
				}
			}
			if (!isset($isCurrent)) {
				Application::$langname[$this->langDefault]=true;
			}
			$_SESSION[$this->langName] = Application::$langname;
			foreach ($lang as $language) {
				if (is_array(Application::$langlist)) {
					Application::$langlist = array_merge(Application::$langlist,$language);
				} else {
					Application::$langlist = $language;
				}
			}
			$_SESSION[$this->langList] = Application::$langlist;
		}
	}
	private function LangDirectory($dir)
	{
		return array_diff(scandir($dir), array('..', '.'));
	}
}
