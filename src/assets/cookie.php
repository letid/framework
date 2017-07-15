<?php
namespace letId\assets
{
  class cookie extends \letId\assist\cookie
  {
    public function user()
    {
      return $this->setId(avail::$config['signCookieId']);
    }
  }
}