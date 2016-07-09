<?php
namespace letId\http;
use letId\support;
abstract class request
{
	public function request()
    {
		avail::intObj();
		avail::session()->start();
		avail::$hostname = $_SERVER['HTTP_HOST'];
		avail::$http = strtolower(substr($_SERVER['SERVER_PROTOCOL'],0,strpos($_SERVER['SERVER_PROTOCOL'],'/'))).avail::SlH.avail::$hostname;
		avail::$uriPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), avail::SlA);
		if (avail::$uriPath) {
			avail::$uri = explode(avail::SlA, avail::$uriPath);
		}
	}
	public function initiate()
    {
		$assign = new assign($this->application);
		if ($assign->host()->dir($this->configuration)) {
			$module = new module;
			$module->loader();
			if ($assign->rewrite(avail::$uri,$this->rewrite)) {
				if ($route=$module->route()) {
					if (isset($route->configuration)) {
						$assign->con($route->configuration);
					}
					$module->ASC();
					module::request(support\validation::class)->extension('ASV');
					module::request(support\authorization::class)->extension('ASA');
					module::request(support\mail::class)->extension('ASM');
					$ase = $module->environment();
					if ($ase) {
					    if (isset($ase['database'])) {
							if ($assign->database(array_merge(avail::$database,$ase['database']))) {
								return true;
							} else {
					            unset($ase['database']);
					        }
					    }
						$assign->con($ase);
					}
					avail::authorization()->subscribe();
					if (isset($route->directory) && is_array($route->directory)) {
						$this->directory = array_merge($this->directory,$route->directory);
					}
					avail::directory()->set($this->directory);
					new verse();
					$verso = new verso($route->page);
					$verso->set();
					$verso->execute();
				}
			}
		}
    }
	public function response()
    {
		echo call_user_func(array(module::request(support\response::class)->response(avail::$contextType),avail::$contextResponse),avail::$context);
	}
}
