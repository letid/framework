<?php
namespace letId\http;
class verse
{
	public function __construct()
	{
		/*
		HACK: language Requests created:
			avail::$langlist
			avail::$langname
		NOTE: In order to get the language work "language" in config and "language" in dir needs to set!
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		*/
		// NOTE: is language enabled by user set 'language' in config
		if ($langDefault=avail::$config['language']) {
			$this->sessionList = avail::session('lang.list')->version();
			$this->sessionName = avail::session('lang.name')->version();
			$this->sessionSIL = avail::session('sil')->version();
			$lang = array(
				$langDefault => array()
			);
			$langPara = avail::$langPara;
			if (isset($_GET[$langPara]) && $sil=$_GET[$langPara]) {
				if ($this->sessionSIL->not() || !$this->sessionSIL->same($sil)) {
					// NOTE: user requested language, so we reset the previous one
					$this->sessionList->remove();
					$this->sessionSIL->set($sil);
					$lang[$sil]=array();
				}
			} else {
				if ($this->sessionSIL->not()) {
					$this->sessionSIL->set($langDefault);
				}
			}
			$this->setEngine($lang);
		}
	}
	private function setEngine($lang)
	{
		if ($this->sessionList->has() && $this->sessionName->has()) {
			avail::$langname = $this->sessionName->get();
			avail::$langlist = $this->sessionList->get();
		} else {
			$dir = avail::$dir->language;
			foreach ($this->setDirectory($dir) as $nameId) {
				if ($this->sessionSIL->same($nameId)) {
					$isCurrent = true;
					avail::$langname[$nameId]=true;
				} else {
					avail::$langname[$nameId]=false;
				}
				if (array_key_exists($nameId,$lang)) {
					foreach ($this->setDirectory($dir.$nameId) as $fileName) {
						$filePath = $dir.$nameId.avail::SlA.$fileName;
						$file = pathinfo($filePath);
						if($file['extension'] == avail::$Extension['language']) {
							if ($tmp=parse_ini_file($filePath)) {
								$lang[$nameId] = array_merge_recursive($lang[$nameId],$tmp);
							}
						}
					}
				}
			}
			if (!isset($isCurrent)) {
				avail::$langname[avail::$config['language']]=true;
			}
			$this->sessionName->set(avail::$langname);
			foreach ($lang as $language) {
				if (is_array(avail::$langlist)) {
					avail::$langlist = array_merge(avail::$langlist,$language);
				} else {
					avail::$langlist = $language;
				}
			}
			$this->sessionList->set(avail::$langlist);
		}
	}
	private function setDirectory($dir)
	{
		return array_diff(scandir($dir), array('..', '.'));
	}
}
