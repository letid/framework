<?php
namespace Letid\Id;
use Composer;
trait Module
{
	private function ModuleApplication()
    {
		if ($Classname = $this->ModuleExists($this->AIV.Config::SlB.$this->AVC) or $Classname = $this->ModuleExists($this->AAI)) {
			return $this->ModuleApp = new $Classname();
			// $this->ModuleApp
		} else {
			$this->InitiateError(Config::$Notification['error'],Config::$NoApplicationInitiation);
		}
	}
	private function ModuleVerso()
    {
		if ($Classname = $this->ModuleExists($this->APN.Config::SlB.$this->VersoClass)) {
			return new $Classname();
			// $this->ModuleVerso;
		}
	}
	private function ModuleAuthorization()
    {
		///?? not in used
		if ($Classname = $this->ModuleExists($this->AIA)) {
			return $this->ModuleAuth = new $Classname;
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
		return static::ANS.Config::SlB.$Name;
	}
	private function ModuleAutoload()
	{
		$loader = new Composer\Autoload\ClassLoader();
		$loader->addPsr4($this->ModuleName(),array(self::$CoreVar['directory']['root']),true);
		// $loader->loadClass(\App\Private\Configuration);
		$loader->register(true);
		// $loader->setUseIncludePath(true);
		// return $loader;
		// print_r($loader);
	}
}
