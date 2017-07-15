<?php
namespace letId\http
{
	class versePrevious
	{
		/*
		HACK: language Requests created:
		avail::$localeList
		avail::$localeName
		NOTE: In order to get the language work "language" in config and "language" in dir needs to set!
		substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		NOTE: is language enabled by user set 'language' in config
		*/
		public function __construct()
		{
			if ($langDefault=avail::$config['locale']) {
				$this->sessionList = avail::session('locale.list')->version();
				$this->sessionName = avail::session('locale.name')->version();
				$this->sessionLocale = avail::session('locale')->version();
				$lang = array(
					$langDefault => array()
				);
				$localePara = avail::$localePara;
				if (isset($_GET[$localePara]) && $sil=$_GET[$localePara]) {
					if ($this->sessionLocale->not() || !$this->sessionLocale->same($sil)) {
						// NOTE: user requested language, so we reset the previous one
						$this->sessionList->remove();
						$this->sessionLocale->set($sil);
						$lang[$sil]=array();
					}
				} else {
					if ($this->sessionLocale->not()) {
						$this->sessionLocale->set($langDefault);
					}
				}
				$this->setEngine($lang);
			}
		}
		private function setEngine($lang)
		{
			if ($this->sessionList->has() && $this->sessionName->has()) {
				avail::$localeName = $this->sessionName->get();
				avail::$localeList = $this->sessionList->get();
			} else {
				$dir = avail::$dir->language;
				foreach ($this->setDirectory($dir) as $nameId) {
					if ($this->sessionLocale->same($nameId)) {
						$isCurrent = true;
						avail::$localeName[$nameId]=true;
					} else {
						avail::$localeName[$nameId]=false;
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
					avail::$localeName[avail::$config['locale']]=true;
				}
				$this->sessionName->set(avail::$localeName);
				foreach ($lang as $language) {
					if (is_array(avail::$localeList)) {
						avail::$localeList = array_merge(avail::$localeList,$language);
					} else {
						avail::$localeList = $language;
					}
				}
				$this->sessionList->set(avail::$localeList);
			}
		}
		private function setDirectory($dir)
		{
			return array_diff(scandir($dir), array('..', '.'));
		}
	}
}
