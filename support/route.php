<?php
namespace letId\support
{
    abstract class route extends avail
    {
        public function __set($name, $value)
        {
            $this->{$name} = $value;
            if (is_scalar($value)) {
                self::content($name)->set($value);
            }
        }
        public function __get($name)
        {
            if (isset(self::$content[$name])) {
                 return self::content($name)->get();
            }
        }
        // public function __call($name, $arguments)
    	// {
    	// 	// return $this;
    	// }
    }
}