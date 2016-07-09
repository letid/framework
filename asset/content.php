<?php
namespace letId\asset;
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
class content extends avail
{
    public function all()
    {
        return self::$content;
    }
    public function has()
    {
        if ($this->Id) {
        	return isset(self::$content[$this->Id]);
        }
    }
    // public function setId($Id=null)
    // {
    //     if ($Id) {
    //         $this->Id = $Id;
    //     }
    //     return $this;
    // }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return self::$content[$this->Id]=func_get_args()[0];
        	}
        }
    }
    public function get()
    {
        if ($this->has()) {
        	return self::$content[$this->Id];
        }
    }
    public function resolve()
    {
        if ($this->has()) {
        	return self::$content[$this->Id];
        } else if (self::hasObj($this->Id)) {
            if ($name=self::getObj($this->Id) and is_scalar($name)) {
                return $name;
            }
        }
        // if ($this->has()) {
        // 	return self::$content[$this->Id];
        // } else if (self::hasObj($this->Id)) {
        //     if ($name=self::getObj($this->Id) and is_scalar($name)) {
        //         return $name;
        //     }
        // }
    }
    public function statics()
    {
        if (self::hasObj($this->Id)) {
            if ($name=self::getObj($this->Id) and is_scalar($name)) {
                return $name;
            }
        }
    }
    public function statics___()
    {
        $needle = 'app.';
        $haystack = $this->Id;
        $position = strpos($haystack, $needle);
        if ($position === 0) {
            $name = substr_replace($haystack, '', $position, strlen($needle));
            if (is_scalar($varName=self::getObj($name))) {
                return $varName;
            }
        }
    }
    public function __toString()
    {
        return $this->get();
    }
}