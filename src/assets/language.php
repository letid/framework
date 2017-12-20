<?php
namespace letId\assets
{
  /*
  $language = new language(Id);
  $language->get();
  $language->all();
  $language->set(Value);

  language::request()->get()
  language::request()->set()
  language::request()->all()

  avail::language()->all();
  avail::language(Id)->get();
  avail::language(Id)->set(Value);
  */
  class language
  {
    public function __construct($Id=NULL)
    {
      $this->Id = $Id;
    }
    // public function all()
    // {
    //   return avail::$localeList;
    // }
    public function has()
    {
      if (is_scalar($this->Id)) return isset(avail::$localeList[$this->Id]);
    }
    public function get($option=array())
    {
      return $this->requestEngine($option);
    }
    public function set($Id)
    {
      if (is_scalar($this->Id)) return avail::$localeList[$this->Id]=$Id;
    }
    private function requestId($Id)
    {
      return is_scalar($Id) && isset(avail::$localeList[$Id])?avail::$localeList[$Id]:$Id;
    }
    private function requestEngine($v=array())
    {
      return preg_replace_callback(avail::$config['ATR'],
        function ($k) use ($v) {
          if (isset($v[$k[1]])) {
            // NOTE: Arguments provided
            return $v[$k[1]];
          } elseif (isset(avail::$localeList[$k[1]])) {
            // NOTE: Language has
            return avail::$localeList[$k[1]];
          } elseif (ctype_upper($k[1]{0})) {
            // NOTE: Uppercase
            return $k[1];
          } elseif (avail::content($k[1])->has()) {
            // NOTE: Content has
            return avail::content($k[1])->get();
          } elseif (avail::configuration($k[1])->has()) {
            return avail::configuration($k[1])->get();
          }
        }, $this->requestId($this->Id)
      );
    }
    public function __toString()
    {
      return $this->get();
    }
  }
}
