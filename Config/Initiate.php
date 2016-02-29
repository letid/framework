<?php
namespace Letid\Config;
trait Initiate
{
	private function Termination()
    {
		if ($app=$this->classExists(self::APC(),true)) {
			foreach (self::$int as $name => $value) {
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
	public function classExists($NS,$Instantiating)
    {
		if (class_exists($NS)) {
			if ($Instantiating){
				return new $NS;
			} else {
				return $NS;
			}
		}
	}
	private function classInit($app)
    {
		if (class_exists($app)) {
			return new $app();
		} else {
			// TODO: invalidResponse
			$this->invalidResponse(File::$Notification['error'],Message::$NoApplicationInitiation);
		}
	}
	private function invalidResponse($file,$msg)
    {
		$this->invalidNotification($file);
		$invalid[$file] = $msg;
		self::$Content = $invalid;
	}
	private function invalidNotification($fileName)
    {
		if (file_exists(self::$int['directory']['root'].$fileName.File::$Extension['template'])) {
			self::$int['directory']['template'] = self::$int['directory']['root'];
		} else {
			self::$int['directory']['template'] = File::$Root.File::$Notification['root'];
		}
	}
	private function validateHost()
    {
		if ($Host=$this->validateHostset(self::$int['hostname'])) return static::ARO.$Host;
	}
	private function validateHostset($key)
    {
		// session_unset($_SESSION[$ID]);
		if ($_SESSION[$key]) {
			return $_SESSION[$key];
		} else if($this->map && $Name=self::validateHostadd()) {
			return $_SESSION[$key]=$Name;
		} else if(static::AMP) {
			return $_SESSION[$key]=static::AMP;
		}
	}
	private function validateHostadd()
    {
		foreach ($this->map as $Name => $Regex)
		{
			if ($Regex && self::validateHostmatch(is_array($Regex)?$Regex:array($Regex))) {
				return $Name;
			}
		}
	}
	private function validateHostmatch($Regex)
    {
		foreach ($Regex as $Name)
		{
			if (preg_match("/$Name/", self::$int['hostname'])) {
				return true;
			}
		}
	}
	private function validateRoot($dir)
    {
		self::$int['directory'] = [];
		if ($dir && file_exists($dir.static::SlB)) {
			return self::$int['directory']['root'] = $dir.static::SlA;
		}
	}
	private function initiateAutoload()
	{
		$loader = new \Composer\Autoload\ClassLoader();
		$loader->addPsr4(static::ANS.static::SlB,array(self::$int['directory']['root']),true);
		// $loader->loadClass(\App\Private\Configuration);
		$loader->register(true);
		// $loader->setUseIncludePath(true);
		// print_r($loader);
	}
	private function validateDirectory($dir)
    {
		if (self::$int['directory']['root'] && is_array($dir)) {
			foreach ($dir as $id => $name)
			{
				self::$int['directory'][$id] = self::$int['directory']['root'].$name.static::SlA;
			}
		}
	}
}
