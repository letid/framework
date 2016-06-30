<?php
namespace Letid\Id;
trait traitInitial
{
	private function InitialRequest()
    {
		return Application::$name=$this->InitialHostExists(Application::$hostname);
	}
	private function InitialHostExists($hostname)
    {
		// session_unset($_SESSION[$hostname]);
		if (isset($_SESSION[$hostname]) && $_SESSION[$hostname]) {
			return $_SESSION[$hostname];
		} else if($Name=$this->InitialHostName($this->application)) {
			return $_SESSION[$hostname]=$Name;
		}
	}
	private function InitialHostName($application)
    {
		if (is_array($application)) {
			foreach ($application as $Name => $Regex)
			{
				if ($Regex && $this->InitialHostEngine(is_array($Regex)?$Regex:array($Regex))) {
					return $Name; break;
				}
			}
		}
	}
	private function InitialHostEngine($Regex)
    {
		foreach ($Regex as $Name)
		{
			if (preg_match("/$Name/", Application::$hostname)) {
				return true;
			}
		}
	}
	private function InitialRoot($appName)
    {
		Application::$config = is_array($this->configuration)?array_merge(Application::$config,$this->configuration):Application::$config;
		$this->directory = is_array($this->directory)?array_merge(Application::$dir,$this->directory):Application::$dir;
		Application::$dir = (object)array();
		if ($appName && file_exists($this->root.$appName.Application::SlB)) {
			return Application::$dir->root = $this->root.$appName.Application::SlA;
		} else {
			// NOTE: first Error
			$this->InitialError(Application::$Notification['error'],Application::$InvalidConfiguration);
		}
	}
	private function InitialDatabaseError()
    {
		$this->InitialError(
			Application::$Notification['database'],
			array_merge(
				Application::$InvalidDatabase,
				array(
					'Message'=>Application::$db->connect_error,
					'Code'=>Application::$db->connect_errno
					// 'src/style.css'=>array()
				)
			)
		);
	}
	private function InitialResponse()
    {
		if ($app=$this->ModuleVerso()) {
			// is_callable($methodVariable, true, $callable_name)
			if (is_callable(array($app,$this->versoMethod), true)) {
				self::$Content = call_user_func(array($app, $this->versoMethod));
				// call_user_func(array($app, '_delete_or_try'));
			} else {
				Application::content('Method')->set($this->versoMethod);
				$this->InitialError(Application::$Notification['error'],Application::$InvalidMethod);
			}
		} else {
			// TODO: disable InitialError on live application
			$this->InitialError(Application::$Notification['error'],Application::$InvalidClass);
		}
	}
	private function InitialExists($NS,$Instantiating=null)
    {
	}
	private function InitialError($file,$msg)
    {
		$this->InitialErrorFile($file);
		$invalid[$file] = $msg;
		self::$Content = $invalid;
	}
	private function InitialErrorFile($fileName)
    {
		Application::$dir->template = $this->root.'errors/';
		if (!file_exists(Application::$dir->template.$fileName.Application::$Extension['template'])) {
			Application::$dir->template = Application::$root.Application::$Notification['dir'];
		}
	}
	private function InitialDirectory()
    {
		if (Application::$dir->root && is_array($this->directory)) {
			foreach ($this->directory as $name => $dir)
			{
				Application::$dir->{$name} = Application::$dir->root.$dir.Application::SlA;
			}
		}
	}
}
