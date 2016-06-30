<?php
namespace Letid\Id;
trait traitModule
{
	private function ModuleRequest()
	{
		$loader = new \Composer\Autoload\ClassLoader();
		$loader->addPsr4($this->ModuleName(),array(Application::$dir->root),true);
		// $loader->loadClass(\App\Private\Configuration);
		$loader->register(true);
		// $loader->setUseIncludePath(true);
		// return $loader;
		// print_r($loader);
	}
	private function ModuleApplication()
    {
		if ($Classname = $this->ModuleExists(Application::$config['AAI'])) {
			return new $Classname;
		} else {
			$this->InitiateError(Application::$Notification['error'],Application::$InvalidApplication);
		}
	}
	private function ModuleAuthorization()
    {
		if ($Classname = $this->ModuleExists(Application::$config['AIA'])) {
			return $this->authorization = new $Classname;
		}
	}
	private function ModuleVerso()
    {
		if ($Classname = $this->ModuleExists(Application::$config['APN'].Application::SlB.$this->versoClass)) {
			return new $Classname;
		}
	}
	private function ModuleExists($Name)
    {
		if (class_exists($Classname = $this->ModuleName($Name))) {
			return $Classname;
		}
	}
	private function ModuleName($Name='')
	{
		return static::ANS.Application::SlB.$Name;
	}
	private function ModuleEnvironment()
	{
		return parse_ini_file(Application::$dir->root.Application::$config['AIV'].Application::SlP.Application::$Extension['environment'],true);
	}
}
