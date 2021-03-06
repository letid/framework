<?php
namespace letId\assist
{
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
    protected $Id;
    public function __construct($Id=null)
    {
      $this->Id = $Id;
    }
    /**
    * avail::configuration(array())->vars();
    */
    public function vars($Id)
    {
      return $this->{$Id};
    }
    /**
    * avail::configuration(tra)->setting(1);
    */
    public function own($Id=null)
    {
      if (isset($this->{$this->Id})) {
        $setting = $this->{$this->Id};
      } elseif (isset(avail::$config[$this->Id])) {
        $setting = avail::$config[$this->Id];
      } else {
        $setting = $this->Id;
      }
      if (is_array($setting)){
        return isset($setting[$Id])?$setting[$Id]:$setting;
      } else {
        return $setting;
      }
    }
    public function all()
    {
      return avail::$config;
    }
    public function has()
    {
      if (is_scalar($this->Id)) return isset(avail::$config[$this->Id]);
    }
    /**
    * Add
    * avail::configuration(id)->set(Optional array());
    * Remove
    * avail::configuration(id)->set();
    */
    public function set()
    {
      if ($this->Id) {
        if (func_get_args()) {
          return avail::$config[$this->Id]=func_get_args()[0];
        } elseif (isset(avail::$config[$this->Id])) {
          unset(avail::$config[$this->Id]);
        }
      }
    }
    public function get($Id=null)
    {
      return $this->has()?avail::$config[$this->Id]:$Id;
    }
    /**
    * avail::configuration(array())->merge(Optional array());
    * avail::configuration(array('lang'=>'en','locale'=>'en'))->merge();
    */
    public function merge($Id=array())
    {
      if (is_array($this->Id)) return avail::$config = array_merge(avail::$config,$this->Id,$Id);
    }
    public function __get($name)
    {
      return $this->{$name};
    }
    // public function __set($name, $value)
    // {
    //   if (is_scalar($value)) {
    //     $this->{$name} = $value;
    //   }
    // }
  }
}
