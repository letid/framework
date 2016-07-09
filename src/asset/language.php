<?php
namespace letId\asset;
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
    public function __construct($Id=null)
	{
        $this->Id = $Id;
	}
    public function all()
    {
        return avail::$langlist;
    }
    public function has()
    {
        return isset(avail::$langlist[$this->Id]);
    }
    public function get($option=array())
    {
        return $this->requestEngine($option);
    }
    public function set($Id)
    {
        return avail::$langlist[$this->Id]=$Id;
    }
    private function requestId($Id)
    {
        return isset(avail::$langlist[$Id])?avail::$langlist[$Id]:$Id;
    }
    private function requestEngine($v=array())
    {
        return preg_replace_callback(avail::$config['ATR'],
            function ($k) use ($v) {
                if (isset($v[$k[1]])) {
                    return $v[$k[1]];
                } elseif (isset(avail::$langlist[$k[1]])) {
                    // NOTE: if language has
                    return avail::$langlist[$k[1]];
                } elseif (ctype_upper($k[1]{0})) {
                    // NOTE: when uppercase
                    return $k[1];
                }
            }, $this->requestId($this->Id)
        );
    }
    public function __toString()
	{
	    return $this->get();
	}
}