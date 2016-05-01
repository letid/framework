<?php
namespace Letid\Id;
trait Lang
{
	private function LangRequest()
	{
		/*
		HACK: language Requests created:
			Config::$language
			Config::$langname
		NOTE: In order to get the language work "language_default" and "language" dir needs to set!
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		*/
		// NOTE: is language enabled!
		if ($this->langDefault=$this->config('language_default')) {
			//NOTE: Session keys
			$this->LSLG= $this->SessionID('lang.uage');
			$this->LSLN= $this->SessionID('lang.name');
			$lang = array(
				$this->langDefault => array()
			);
			if (isset($_GET['language']) && $sil=$_GET['language']) {
				if (!$_SESSION['sil'] || $sil != $_SESSION['sil']) {
					// NOTE: user requested language, so we reset the previous one
					unset($_SESSION[$this->LSLG]);
					$_SESSION['sil'] = $sil;
					$lang[$sil]=array();
				}
			} else {
				if (!isset($_SESSION['sil'])) {
					$_SESSION['sil'] = $this->langDefault;
				}
			}
			$this->LangEngine($lang);
		}
	}
	private function LangEngine($lang)
	{
		// $isCurrent = $this->langDefault;
		if (isset($_SESSION[$this->LSLG]) && isset($_SESSION[$this->LSLN])) {
			Config::$langname = $_SESSION[$this->LSLN];
			Config::$language = $_SESSION[$this->LSLG];
		} else {
			$dir = Config::$dir->language;
			foreach ($this->LangDirectory($dir) as $langName) {
				if ($langName==$_SESSION['sil']) {
					$isCurrent = true;
					Config::$langname[$langName]=true;
				} else {
					Config::$langname[$langName]=false;
				}
				// Config::$langname[$langName]=($langName==$_SESSION['sil'])?true:false;
				if (array_key_exists($langName,$lang)) {
					foreach ($this->LangDirectory($dir.$langName) as $fileName) {
						$filePath = $dir.$langName.Config::SlA.$fileName;
						$file = pathinfo($filePath);
						if($file['extension'] == Config::$Extension['language']) {
							if ($tmp=parse_ini_file($filePath)) {
								$lang[$langName] = array_merge_recursive($lang[$langName],$tmp);
							}
						}
					}
				}
			}
			if (!isset($isCurrent)) {
				Config::$langname[$this->langDefault]=true;
			}
			$_SESSION[$this->LSLN] = Config::$langname;
			foreach ($lang as $language) {
				if (is_array(Config::$language)) {
					Config::$language = array_merge(Config::$language,$language);
				} else {
					Config::$language = $language;
				}
			}
			$_SESSION[$this->LSLG] = Config::$language;
		}
	}
	private function LangDirectory($dir)
	{
		return array_diff(scandir($dir), array('..', '.'));
	}
}
