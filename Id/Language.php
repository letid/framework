<?php
namespace Letid\Id;
trait Language
{
	private function LanguageRequest($sil)
	{
		/*
		language Request created
			Config::$language
			Config::$langname
		In order to get work language "language_default" and "language" dir needs to set
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		*/
		if ($this->langDefault=$this->getConfig('language_default')) {
			//NOTE: language is enabled
			$lang = array(
				$this->langDefault => array()
			);
			if ($sil) {
				if (!$_SESSION['sil'] || $sil != $_SESSION['sil']) {
					//NOTE: user request other, so we reset the previous one
					unset($_SESSION['lang.uage']);
					$_SESSION['sil'] = $sil;
					$lang[$sil]=array();
				}
			} else {
				$_SESSION['sil'] = $this->langDefault;
			}
			$this->LanguageEngine($lang);
			// NOTE: testing
			// print_r(Config::$language);
			// print_r(Config::$langname);
		}
	}
	private function LanguageEngine($lang)
	{
		if ($_SESSION['lang.uage'] && $_SESSION['lang.name']) {
			Config::$langname = $_SESSION['lang.name'];
			Config::$language = $_SESSION['lang.uage'];
		} else {
			$dir = Config::$dir->language;
			foreach ($this->LanguageDirectory($dir) as $langName) {
				Config::$langname[$langName]=($langName==$_SESSION['sil'])?true:false;
				if (array_key_exists($langName,$lang)) {
					foreach ($this->LanguageDirectory($dir.$langName) as $fileName) {
						$filePath = $dir.$langName.Config::SlA.$fileName;
						$file=pathinfo($filePath);
						if ($file['extension'] == 'php') {
							if ($Tmp=$this->LanguageRequire($filePath)) {
								$lang[$langName] = array_merge($lang[$langName],$Tmp);
							}
						}
					}
				}
			}
			$_SESSION['lang.name'] = Config::$langname;
			$_SESSION['lang.uage'] = Config::$language = array_reduce($lang, 'array_merge', array());
			// NOTE: incase
			// if (count($lang) > 1) {
			// 	return array_merge($lang[array_keys($lang)[0]],$lang[array_keys($lang)[1]]);
			// } else {
			// 	return $lang[array_keys($lang)[0]];
			// }
		}
	}
	private function LanguageDirectory($dir)
	{
		return array_diff(scandir($dir), array('..', '.'));
	}
	private function LanguageRequire($dir)
	{
		if (is_array($language=require_once($dir))) return $language;
	}
}
