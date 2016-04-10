<?php
namespace Letid\Id;
trait Language
{
	private function LanguageRequest($sil)
	{
		/*
		HACK: language Requests created:
			Config::$language
			Config::$langname
		NOTE: In order to get the language work "language_default" and "language" dir needs to set!
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		*/
		//NOTE: is language enabled!
		if ($this->langDefault=$this->getConfig('language_default')) {
			//NOTE: Session keys
			$this->LSLG= 'lang.uage';
			$this->LSLN= 'lang.name';
			$lang = array(
				$this->langDefault => array()
			);
			if ($sil) {
				if (!$_SESSION['sil'] || $sil != $_SESSION['sil']) {
					//NOTE: user request other, so we reset the previous one
					unset($_SESSION[$this->LSLG]);
					$_SESSION['sil'] = $sil;
					$lang[$sil]=array();
				}
			} else {
				$_SESSION['sil'] = $this->langDefault;
			}
			$this->LanguageEngine($lang);
			// NOTE: testing
			// print_r(Config::$langname);
			// print_r(Config::$language);
		}
	}
	private function LanguageEngine($lang)
	{
		// $isCurrent = $this->langDefault;
		if ($_SESSION[$this->LSLG] && $_SESSION[$this->LSLN]) {
			Config::$langname = $_SESSION[$this->LSLN];
			Config::$language = $_SESSION[$this->LSLG];
		} else {
			$dir = Config::$dir->language;
			foreach ($this->LanguageDirectory($dir) as $langName) {
				if ($langName==$_SESSION['sil']) {
					$isCurrent = true;
					Config::$langname[$langName]=true;
				} else {
					Config::$langname[$langName]=false;
				}
				// Config::$langname[$langName]=($langName==$_SESSION['sil'])?true:false;
				if (array_key_exists($langName,$lang)) {
					foreach ($this->LanguageDirectory($dir.$langName) as $fileName) {
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
	private function LanguageDirectory($dir)
	{
		return array_diff(scandir($dir), array('..', '.'));
	}
}
