<?php
namespace Letid\Id;
trait Http
{
	public function Request()
    {
		$this->SessionRequest();
		Config::$hostname = $_SERVER['HTTP_HOST'];
		// $this->HttpProtocol = Validate::protocal();
		Config::$url = Validate::protocal().Config::SlH.Config::$hostname;
		if ($uri=trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), Config::SlA)) {
			Config::$uri = explode(Config::SlA, $uri);
		}
	}
	public function Initiate()
    {
		// check: application directory
		if ($this->InitiateRoot($this->InitiateHost())) {
			// composer: Autoload
			$this->ModuleAutoload();
			// load: application
			if ($app=$this->ModuleApplication()) {
				// load: authorization
				$auth=$this->ModuleAuthorization();
				// add: config
				Config::$list = array_replace_recursive($this->config,$app->config);
				if ($app->database) {
					// mysql: connection
					// $this->DatabaseRequest(array_merge($this->database,$app->database));
					$this->DatabaseRequest($app->database);
					if ($this->DatabaseError()) {
						return $this->DatabaseInitiate();
					} else {
						$auth->database=$this->database;
					}
				}
				// get: necessary vars
				$this->InitiateRequest($app);
				$this->dir = array_merge($this->dir,$app->dir);
				// get: necessary directory
				$this->InitiateDirectory($this->dir);
				// config: vars and verso
				$this->VersoRequest($this->host);
				// check: verso authorization
				$this->page = array_replace_recursive($this->page,$app->page);
				$this->VersoInitiate($this->page);
				// get: necessary verso
				$this->VersoResponse();
				// load: verso
				$this->InitiateResponse();
			}
		}
    }
	public function Response()
    {
		$this->TemplateResponse(self::$Content);
	}
}
