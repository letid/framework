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
    * avail::content(id)->set(string);
    */
    public function set($value=null)
    {
      if (is_scalar($this->Id)) {
        if ($value) return avail::$contents[$this->Id]=$value;
      }
    }
    public function get($value=null)
    {
      return $this->has()?avail::$contents[$this->Id]:$value;
    }
    public function resolve()
    {
      if (isset(avail::$config[$this->Id])) {
        if (is_scalar(avail::$config[$this->Id])) return avail::$config[$this->Id];
      }
    }
    public function __toString()
    {
      return $this->get();
    }
  }
}
