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
		if ($this->initiateRoot($this->initiateHost())) {
			$this->initiateAutoload();
			if ($AppInit=$this->initiateExists(self::ANC()) and $app=$this->initiateClass($AppInit) or $app=$this->initiateClass(self::AIN())) {
				$this->initiateDirectory($app->directory);
				if ($app->database) {
					$app->database=Database\Connection::Connectivity($app->database);
					if ($app->database->connect_errno) {
						// TODO: invalidResponse
						return $this->initiateResponsive(File::$Notification['database'],array_merge(Info::$DatabaseConnection,array('Message'=>$app->database->connect_error,'Code'=>$app->database->connect_errno)));
					}
				}
				/*
				static $Delete = array();
				public $PublicDelete = array();
				protected $ProtectedDelete = array();
				private $PrivateDelete = array();
				*/
				$this->PublicInfo['public'] ='Okey';
				$this->ProtectedInfo['protected'] ='Okey';
				$this->PrivateInfo['private'] ='Okey';

				self::$PublicStaticInfo['static'] ='Okey';
				self::$ProtectedStaticInfo['static'] ='Okey';
				// self::$PrivateStaticInfo['static'] ='Okey';

				Info::$PublicStaticInfo['public-static'] ='Okey';
				// Info::$ProtectedStaticInfo['protected-static'] ='Okey';
				// Info::$PrivateStaticInfo['private-static'] ='Okey';

				// self::$PublicStaticInfo['abc'] ='abc';
				// self::$uri['test'] ='info test changed';
				// // Test::$asdfasdf ='info test ok';
				// // Test::$Love = 'love';
				// $this->TestPublic['xyx'] = 'love';
				//
				// $this->TestPublic['abc'] = 'changed';
				// // print_r($this->TestPublic);
				// // Test::$TestPublic['abc'] = 'love';
				// // $this->testSet('love','Ok');
				// $this->Love = 'love';
				// print_r($this);
				$this->clusterRequestive($app->page);
				$this->clusterInitiative();
				$this->initiateEngine();
			}
		} else {
            $this->initiateResponsive(File::$Notification['error'],Info::$NoApplicationExists);
        }
    }
	public function Response()
    {
		$this->template(self::$Content);
	}
}
