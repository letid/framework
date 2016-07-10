<?php
namespace letId\asset;
/*
$configuration = new configuration(Id);
$configuration->get();
$configuration->set(Value);
$configuration->all();

avail::configuration(Id);
avail::configuration(Id)->get();
avail::configuration(Id)->set(Value);
avail::configuration()->all();
*/
abstract class configuration
{
    public $Id;
    public function __construct($Id=null)
    {
        $this->Id = $Id;
    }
    public function initiate()
    {
        avail::$config = array_merge(avail::$config,$this->setting);
    }
    public function request($Id)
    {
        if ($this->Id) {
            return array_merge($this->Id,$this->{$Id});
        } else {
            return $this->{$Id};
        }
    }
    public function all()
    {
        return avail::$config;
    }
    public function has()
    {
        return isset(avail::$config[$this->Id]);
    }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return avail::$config[$this->Id]=func_get_args()[0];
        	} else {
                avail::$config[$this->Id] = null;
            }
        }
    }
    public function get($Id=null)
    {
        return $this->has()?avail::$config[$this->Id]:$Id;
    }
    public function merge($Id=array())
    {
        if (is_array($this->Id)) {
            avail::$config = array_merge(avail::$config,$this->Id,$Id);
        }
    }
    public function __toString()
    {
        return $this->get();
    }
}