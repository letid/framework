<?php
namespace Letid\Id;
use Letid\Database;
trait Http
{
	public function Request()
    {
		session_start();
		self::$scoreVar['hostname'] = $_SERVER['HTTP_HOST'];
		if ($uri=trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), static::SlA)) {
			self::$scoreVar['uri'] = explode(static::SlA, $uri);
		} else {
			self::$scoreVar['uri'] = [];
		}
	}
	public function Initiate()
    {
		if ($this->initiateRoot($this->initiateHost())) {
			$this->initiateAutoload();
			if ($AppInit=$this->initiateExists(self::ANC()) and $app=$this->initiateClass($AppInit) or $app=$this->initiateClass(self::AIN())) {
				$this->initiateDirectory($app->dir);
				if ($app->database) {
					// TODO: Database Main need to move under Config Namespace...
					$app->database=Database\Connection::Connectivity($app->database);
					if ($app->database->connect_errno) {
						return $this->initiateResponsive(File::$Notification['database'],array_merge(self::$DatabaseConnection,array('Message'=>$app->database->connect_error,'Code'=>$app->database->connect_errno)));
					}
				}
				$this->clusterRequestive($app->page);
				$this->clusterInitiative();
				$this->initiateEngine();
			}
		} else {
            $this->initiateResponsive(File::$Notification['error'],self::$NoApplicationExists);
        }
    }
	public function Response()
    {
		$this->template(self::$Content);
	}
}
