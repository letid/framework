<?php
namespace letId\asset
{
    class session extends \letId\assist\session
    {
        public function version($Id='version')
        {
            return $this->setId($this->Id.avail::$config[$Id]);
        }
        public function not()
        {
            return !$this->has();
        }
        public function same($Id=null)
        {
            return $Id == $this->has();
        }
    }
}