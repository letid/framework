<?php
namespace letId\request
{
  abstract class map
  {
    public function __get($name)
    {
      if (avail::content($name)->has()) {
        return avail::content($name)->get();
      }
    }
    public function __set($name, $value)
    {
      $this->{$name} = $value;
      avail::content($name)->set($value);
    }
    // public function __call($name, $arguments)
    // {
    // 	return $this;
    // }
  }
}