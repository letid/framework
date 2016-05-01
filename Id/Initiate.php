<?php
namespace Letid\Id;
trait Initiate
{
	private function InitiateRequest($app)
    {
		foreach (Config::$Initiate as $name) {
			if (property_exists($app, $name)) {
				$this->{$name} = $app->{$name};
			}
			if (property_exists(Config::class, $name) && property_exists($this, $name)) {
				Config::${$name} = $this->{$name};
			}
		}
	}
	private function InitiateResponse()
    {
		if ($app=$this->ModuleVerso()) {
			// foreach (self::$CoreVar as $name => $value) {
			// 	$app->{$name} = $value;
			// }
			self::$Content = call_user_func(array($app, $this->VersoMethod));
			//  if(is_callable(array($app, $this->VersoMethod))) {
			// 	 self::$Content = $app->{$this->VersoMethod}();
			//  }
		} else {
			// TODO: disable InitiateError on live application
			$this->InitiateError(Config::$Notification['error'],Config::$NoApplicationResponse);
		}
	}
	private function InitiateExists($NS,$Instantiating=null)
    {
	}
	private function InitiateError($file,$msg)
    {
		$this->InitiateErrorFile($file);
		$invalid[$file] = $msg;
		self::$Content = $invalid;
	}
	private function InitiateErrorFile($fileName)
    {
		if (file_exists($this->ADE.$fileName.Config::$Extension['template'])) {
			// Config::$dir['template'] = $this->ADE;
			Config::$dir->template = $this->ADE;
		} else {
			// Config::$dir['template'] = Config::$Root.Config::$Notification['dir'];
			Config::$dir->template = Config::$Root.Config::$Notification['dir'];
		}
	}
	private function InitiateHost()
    {
		if ($Host=$this->InitiateHostExists(Config::$hostname)) return $this->ADA.$Host;
	}
	private function InitiateHostExists($key)
    {
		// session_unset($_SESSION[$ID]);
		if ($_SESSION[$key]) {
			return $_SESSION[$key];
		} else if($this->host && $Name=$this->InitiateHostName()) {
			return $_SESSION[$key]=$Name;
		} else if($this->ADT) {
			return $_SESSION[$key]=$this->ADT;
		}
	}
	private function InitiateHostName()
    {
		foreach ($this->host as $Name => $Regex)
		{
			if ($Regex && $this->InitiateHostEngine(is_array($Regex)?$Regex:array($Regex))) {
				return $Name;
			}
		}
	}
	private function InitiateHostEngine($Regex)
    {
		foreach ($Regex as $Name)
		{
			if (preg_match("/$Name/", Config::$hostname)) {
				return true;
			}
		}
	}
	private function InitiateRoot($dir)
    {
		// TODO: remove
		Config::$dir = (object)array();
		if ($dir && file_exists($dir.Config::SlB)) {
			return Config::$dir->root = $dir.Config::SlA;
		} else {
			$this->InitiateError(Config::$Notification['error'],Config::$NoApplicationExists);
		}
	}
	private function InitiateDirectory($dir)
    {
		if (Config::$dir->root && is_array($dir)) {
			foreach ($dir as $id => $name)
			{
				Config::$dir->{$id} = Config::$dir->root.$name.Config::SlA;
			}
		}
	}
}
