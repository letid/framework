<?php
namespace Letid\Id;
abstract class Request extends Core
{
	use traitInitial, traitModule, traitLang, traitVerso;
	public function Request()
    {
		Application::appProperty();
		Application::session()->start();
		Application::$hostname = $_SERVER['HTTP_HOST'];
		Application::$http = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos($_SERVER["SERVER_PROTOCOL"],'/'))).Application::SlH.Application::$hostname;
		if ($uri=trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), Application::SlA)) {
			Application::$uri = explode(Application::SlA, $uri);
		}
	}
	public function Initiate()
    {
		if ($this->InitialRoot($this->InitialRequest())) {
			// load: application (Autoload)
			$this->ModuleRequest();
			// call: application
			if ($app=$this->ModuleApplication()) {
				// update: configuration
				if (isset($app->configuration) && is_array($app->configuration)) {
					Application::$config = array_merge(Application::$config,$app->configuration);
				}
				// load: authorization
				$auth=$this->ModuleAuthorization();
				// connect: database
				if ($env = $this->ModuleEnvironment()) {
				    if (isset($env['database'])) {
						Application::$database = new \Letid\Database\Request;
						Application::$database->connection($env['database']);
				        if (Application::$database->errorConnection()) {
				            return $this->InitialDatabaseError();
				        } else {
				            // remove sensitive information
				            unset($env['database']);
				        }
				    }
				    if ($env) {
				        // update: configuration from environment
				        Application::$config = array_merge(Application::$config,$env);
				    }
				}
				$auth->InitiateRequest();
				// update: directory
				if (isset($app->directory) && is_array($app->directory)) {
					$this->directory = array_merge($this->directory,$app->directory);
				}
				$this->InitialDirectory();
				// watch: language changed
				$this->LangRequest();
				// config: verso
				$this->VersoRequest($app->page);
				// update: current verso
				$this->VersoResponse();
				// call: verso
				$this->InitialResponse();
			}
		}
    }
	public function Response()
    {
		if (self::$Content) {
			echo Application::template(self::$Content);
		} else {
			header("Content-Type: text/plain");
			// print_r(error_get_last());
			// print_r(get_class_methods($this));
			// print_r(self::$Content);
			// print_r(Application::$config);
			// print_r(Application::$dir);
			// print_r(Application::$langlist);
			print_r(Application::$content);
			// print_r(Application::$langname);
			// print_r(Application::$Verso);
			// print_r(self::$let);
			// print_r($this);
			// print_r(self::$Content);
		}
	}
}
