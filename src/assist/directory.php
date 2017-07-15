<?php
namespace letId\assist
{
  /*
  avail::directory(Id)->get();
  avail::directory(Id)->set(Value);
  */
  abstract class directory {
    private $Id;
    public function __construct($Id=null)
    {
      $this->Id = $Id;
    }
    public function has()
    {
      if ($this->Id) return isset(avail::$dir[$this->Id]);
    }
    /**
    * NOTE: avail::directory(array)->set();
    * NOTE: avail::directory(key)->set(value);
    */
    public function set($Id=null)
    {
      if (avail::$dir->root) {
        if (is_array($this->Id)) {
          foreach ($this->Id as $name => $dir) {
            avail::$dir->{$name} = avail::$dir->root.$dir.avail::SlA;
          }
        } else {
          avail::$dir->{$this->Id} = avail::$dir->root.$Id.avail::SlA;
        }
      }
    }
    public function get()
    {
      if ($this->has()) return avail::$dir[$this->Id];
    }
    public function alertExists()
    {
      if (!file_exists(avail::$dir->template.$this->Id.avail::SlP.avail::$Extension['template'])) {
        avail::$dir->root = avail::$rootId;
        avail::$dir->template = avail::$rootId.avail::$Alert['template'];
      }
    }
  }
}