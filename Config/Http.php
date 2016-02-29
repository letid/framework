<?php
namespace Letid\Config;
trait Http
{
	public function Request()
    {
		session_start();
		if ($uri=trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), static::SlA)) {
			self::$int['uri'] = explode(static::SlA, $uri);
		} else {
			self::$int['uri'] = [];
		}
		self::$int['hostname'] = $_SERVER['HTTP_HOST'];
	}
	public function Initiate()
    {
		if ($this->validateRoot($this->validateHost())) {
			$this->initiateAutoload();
			if ($AppInit=$this->classExists(self::ANC()) and $app=$this->classInit($AppInit) or $app=$this->classInit(self::AIN())) {
				$this->validateDirectory($app->directory);
				if ($app->database) {
					$app->database=Database\Connection::Connectivity($app->database);
					if ($app->database->connect_errno) {
						// TODO: invalidResponse
						return $this->invalidResponse(File::$Notification['database'],array_merge(Message::$DatabaseConnection,array('Message'=>$app->database->connect_error,'Code'=>$app->database->connect_errno)));
					}
				}
				$this->Interjection($app->page);
				$this->Integration();
				$this->Termination();
			}
		} else {
            $this->invalidResponse(File::$Notification['error'],Message::$NoApplicationExists);
        }
    }
	public function Response()
    {
		$this->template(self::$Content);
	}
}
