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
class content
{
    public $Id = '!';
    public function __construct($Id='')
    {
        $this->Id = $Id;
    }
    public function all()
    {
        return avail::$content;
    }
    public function has()
    {
        if ($this->Id) {
        	return isset(avail::$content[$this->Id]);
        }
    }
    public function set($Id)
    {
        return avail::$content[$this->Id]=$Id;
    }
    public function get()
    {
        if ($this->has()) return avail::$content[$this->Id];
    }
    public function resolve()
    {
        if ($this->has()) {
        	return avail::$content[$this->Id];
        } elseif (isset(avail::$config[$this->Id])){
        	return avail::$config[$this->Id];
        }
        // if ($this->has()) {
        // 	return avail::$content[$this->Id];
        // } else if (avail::hasObj($this->Id)) {
        //     if ($name=avail::getObj($this->Id) and is_scalar($name)) {
        //         return $name;
        //     }
        // }
    }
    public function statics()
    {
        if (isset(avail::$config[$this->Id])){
            return avail::$config[$this->Id];
        }
        // $needle = 'app.';
        // $haystack = $this->Id;
        // $position = strpos($haystack, $needle);
        // if ($position === 0) {
        //     $name = substr_replace($haystack, '', $position, strlen($needle));
        //     if (is_scalar($varName=avail::getObj($name))) {
        //         return $varName;
        //     }
        // }
    }
    public function __toString()
    {
        return $this->get();
    }
}