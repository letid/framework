<?php
namespace letId\asset;
/*
$configuration = new configuration(Id);
$configuration->get();
$configuration->set(Value);
$configuration->all();

configuration::request(Id)
configuration::request(Id)->get()
configuration::request(Id)->set(Value)
configuration::request()->all()

avail::configuration(Id);
avail::configuration(Id)->get();
avail::configuration(Id)->set(Value);
avail::configuration()->all();
*/
class configuration extends avail
{
    public function all()
    {
        return self::$config;
    }
    public function has()
    {
        return isset(self::$config[$this->Id]);
    }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return self::$config[$this->Id]=func_get_args()[0];
        	} else {
                self::$config[$this->Id] = null;
            }
        }
    }
    public function get($Id=null)
    {
        return $this->has()?self::$config[$this->Id]:$Id;
    }
    public function setClass($Id=null)
    {
        if ($this->has()) {
            if (class_exists(self::$config[$this->Id])) {
    			return new self::$config[$this->Id]($Id);
    		}
        }
    }
    public function merge($Id=array())
    {
        if (is_array($this->Id)) {
            self::$config = array_merge(self::$config,$this->Id,$Id);
        }
    }
    public function __toString()
    {
        return $this->get();
    }
}