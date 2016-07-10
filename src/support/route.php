<?php
namespace letId\support
{
    abstract class route
    {
        public function __set($name, $value)
        {
            $this->{$name} = $value;
            if (is_scalar($value)) {
                avail::content($name)->set($value);
            }
        }
        public function __get($name)
        {
            if (isset(avail::$content[$name])) {
                 return avail::content($name)->get();
            }
        }
        // public function __call($name, $arguments)
    	// {
    	// 	// return $this;
    	// }
    }
}