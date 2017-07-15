<?php
namespace letId\assets
{
  /*
  $content = new content(Id);
  $content->get();
  $content->set(Value);
  $content->all();
  
  content::request(Id)
  content::request(Id)->get()
  content::request(Id)->set(Value)
  content::request()->all()
  
  avail::content(Id);
  avail::content(Id)->get();
  avail::content(Id)->set(Value);
  avail::content()->all();
  */
  class content
  {
    public function __construct($Id=null)
    {
      $this->Id = $Id;
    }
    // public function all()
    // {
    //   return avail::$contents;
    // }
    public function has()
    {
      if (is_scalar($this->Id)) return isset(avail::$contents[$this->Id]);
    }
    /**
    * Add
    * avail::content(id)->set(string);
    */
    public function set($value=null)
    {
      if (is_scalar($this->Id)) {
        if ($value) return avail::$contents[$this->Id]=$value;
      }
    }
    public function get()
    {
      if ($this->has()) return avail::$contents[$this->Id];
    }
    public function resolve()
    {
      if ($this->has()) {
        return avail::$contents[$this->Id];
      } elseif (is_scalar($this->Id) && isset(avail::$config[$this->Id])){
        return avail::$config[$this->Id];
      }
    }
    public function statics()
    {
      if (is_scalar($this->Id) && isset(avail::$config[$this->Id])) return avail::$config[$this->Id];
      // $needle = 'app.';
      // $haystack = $this->Id;
      // $position = strpos($haystack, $needle);
      // if ($position === 0) {
      //   $name = substr_replace($haystack, '', $position, strlen($needle));
      //   if (is_scalar($varName=avail::getObj($name))) {
      //     return $varName;
      //   }
      // }
    }
    // public function __get($name)
    // {
    //   if (isset(avail::$contents[$name])) {
    //     return avail::$contents[$name];
    //   }
    // }
    // public function __set($name, $value)
    // {
    //   if (is_scalar($value)) {
    //     avail::$contents[$name]=$value;
    //   }
    // }
    public function __toString()
    {
      return $this->get();
    }
  }
}