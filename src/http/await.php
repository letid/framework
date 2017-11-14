<?php
namespace letId\http
{
	class await
	{
		public function request()
	  {
			avail::session()->start();
			// avail::session()->delete();
			// avail::cookie()->user()->remove();
			// avail::cookie()->user()->delete();
			if (!property_exists($this, 'uriPath')) {
				$this->requestAfter();
			}
			avail::$http = strtolower(substr($_SERVER['SERVER_PROTOCOL'],0,strpos($_SERVER['SERVER_PROTOCOL'],'/'))).avail::SlH.avail::$hostname;
			avail::$config['http'] = avail::$http;
			avail::$uriPath = trim(parse_url($this->uriPath, PHP_URL_PATH), avail::SlA);
			avail::$contents['letIdSRC'] = avail::$letid['build'];
			if (avail::$uriPath) {
				avail::$uri = explode(avail::SlA, avail::$uriPath);
			}
		}
		public function requestBefore()
	  {
			$hostname = dirname($_SERVER['SCRIPT_NAME']);
			avail::$hostname = $_SERVER['HTTP_HOST'].$hostname;
			$this->uriPath = str_replace($hostname,'',$_SERVER['REQUEST_URI']);
			avail::$config['ARO'] ='';
			// avail::$hostname = defined('app_hostname')?constant('app_hostname'):$_SERVER['HTTP_HOST'];
			// avail::$uriPath = trim(parse_url(defined('app_uriPath')?constant('app_uriPath'):$_SERVER['REQUEST_URI'], PHP_URL_PATH), avail::SlA);
		}
		private function requestAfter()
	  {
			avail::$hostname = $_SERVER['HTTP_HOST'];
			$this->uriPath = $_SERVER['REQUEST_URI'];
		}
		public function initiate()
	  {
			// NOTE: merge routeController->configurations
			avail::$config = array_merge(avail::$config,$this->configuration);
			$assign = new assign($this->application);
			// TODO: remove avail::$rewriteDirectories
			assign::$rewriteDirectories[avail::$contents['letIdSRC']]=avail::$Alert['resource'];
			// avail::configuration()->rewrite = $this->rewite;
			if ($assign->host()->directory($this->configuration)) {
				// NOTE: load environment.ini
				if (module::environment()) {
					module::load()->composer();
					// NOTE: load application configuration
					if (module::load('configuration')->configuration()) {
						// NOTE: $classExtension
						module::load()->extension();
						// TODO: ??? avail::configuration($configuration->setting)->merge();
						$configuration = avail::configuration();
						assign::$rewriteDirectories = array_merge($configuration->rewrite,assign::$rewriteDirectories);
						if ($assign->rewrite()) {
							// NOTE: get Pages
							if (module::load('ASR')->route()) {
								// NOTE: check visits authorization
								avail::authorization()->subscribe();
								// NOTE: set generated directories to configuration for application
								avail::directory($configuration->directory)->set();
								// NOTE: initiate language (locale)
								new verse();
								// NOTE: initiate page
								new verso();
							}
						}
					}
				}
			}
	  }
		public function response()
	  {
			print call_user_func_array(array(new avail::$classExtension['response'](avail::$responseType),avail::$responseMethod),array(avail::$responseContext));
			print avail::assist()->error_get_last();
		}
	}
}
