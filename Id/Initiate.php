<?php
namespace Letid\Id;
trait Initiate
{
	private function initiateEngine()
    {
		if ($app=$this->initiateExists(self::APC(),true)) {
			foreach (self::$scoreConfiguration as $name => $value) {
				$app->{$name}=$value;
			}
			self::$Content = call_user_func(array($app, self::$APD));
		}
	}
	private function ANC()
	{
		return static::ANS.self::SlB.static::ANV.self::SlB.static::ANC;
	}
	private function APC()
	{
		return static::ANS.self::SlB.static::APE.self::SlB.self::$APC;
	}
	private function AIN()
	{
		return static::ANS.self::SlB.static::AIN;
	}
	// if ($AppInit=$this->initiateExists(self::ANC()) and $app=$this->initiateClass($AppInit) or $app=$this->initiateClass(self::AIN())) {
	private function initiateExists($NS,$Instantiating)
    {
		if (class_exists($NS)) {
			if ($Instantiating){
				return new $NS();
			} else {
				return $NS;
			}
		}
	}
	private function initiateClass($app)
    {
		if (class_exists($app)) {
			return new $app();
		} else {
			// TODO: invalidResponse
			$this->initiateResponsive(File::$Notification['error'],self::$NoApplicationInitiation);
		}
	}
	private function initiateResponsive($file,$msg) //invalidResponse
    {
		$this->initiateResponsivefile($file);
		$invalid[$file] = $msg;
		self::$Content = $invalid;
	}
	private function initiateResponsivefile($fileName)
    {
		if (file_exists(self::$scoreVar['directory']['root'].$fileName.File::$Extension['template'])) {
			self::$scoreVar['directory']['template'] = self::$scoreVar['directory']['root'];
		} else {
			self::$scoreVar['directory']['template'] = File::$Root.File::$Notification['root'];
		}
	}
	private function initiateHost()
    {
		if ($Host=$this->initiateHostset(self::$scoreVar['hostname'])) return static::ARO.$Host;
	}
	private function initiateHostset($key)
    {
		// session_unset($_SESSION[$ID]);
		if ($_SESSION[$key]) {
			return $_SESSION[$key];
		} else if($this->map && $Name=$this->initiateHostadd()) {
			return $_SESSION[$key]=$Name;
		} else if(static::AMP) {
			return $_SESSION[$key]=static::AMP;
		}
	}
	private function initiateHostadd()
    {
		foreach ($this->map as $Name => $Regex)
		{
			if ($Regex && $this->initiateHostmatch(is_array($Regex)?$Regex:array($Regex))) {
				return $Name;
			}
		}
	}
	private function initiateHostmatch($Regex)
    {
		foreach ($Regex as $Name)
		{
			if (preg_match("/$Name/", self::$scoreVar['hostname'])) {
				return true;
			}
		}
	}
	private function initiateRoot($dir)
    {
		// self::$int['directory'] = [];
		if ($dir && file_exists($dir.static::SlB)) {
			return self::$scoreVar['directory']['root'] = $dir.static::SlA;
		}
		// self::$int['directory'] = [];
		// if ($dir && file_exists($dir.static::SlB)) {
		// 	return self::$int['directory']['root'] = $dir.static::SlA;
		// }
	}
	private function initiateAutoload()
	{
		$loader = new \Composer\Autoload\ClassLoader();
		$loader->addPsr4(static::ANS.static::SlB,array(self::$scoreVar['directory']['root']),true);
		// $loader->loadClass(\App\Private\Configuration);
		$loader->register(true);
		// $loader->setUseIncludePath(true);
		// print_r($loader);
	}
	private function initiateDirectory($dir)
    {
		if (self::$scoreVar['directory']['root'] && is_array($dir)) {
			foreach ($dir as $id => $name)
			{
				self::$scoreVar['directory'][$id] = self::$scoreVar['directory']['root'].$name.static::SlA;
			}
		}
	}
}
