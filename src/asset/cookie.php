<?php
namespace letId\asset
{
    class cookie extends \letId\assist\cookie
    {
        public function user()
        {
            return $this->setId(avail::$config['signCookieId']);
        }
    }
}