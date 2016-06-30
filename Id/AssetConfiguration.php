<?php
namespace Letid\Id;
/*
$Configuration = new Configuration(Id);
$Configuration->get();
$Configuration->set(Value);
$Configuration->all();

Configuration::request(Id)
Configuration::request(Id)->get()
Configuration::request(Id)->set(Value)
Configuration::request()->all()

Application::configuration(Id);
Application::configuration(Id)->get();
Application::configuration(Id)->set(Value);
Application::configuration()->all();
*/
class AssetConfiguration extends AssetId
{
    public function all()
    {
        return Application::$config;
    }
    public function has()
    {
        if ($this->Id) {
            return isset(Application::$config[$this->Id]);
        }
    }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return Application::$config[$this->Id]=func_get_args()[0];
        	}
        }
    }
    private function get()
    {
        if ($this->has()) {
        	return Application::$config[$this->Id];
        }
    }
    public function __toString()
    {
        return $this->get();
    }
}