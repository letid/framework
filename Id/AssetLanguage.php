<?php
namespace Letid\Id;
/*
$Language = new Language(Id);
$Language->get();
$Language->all();
$Language->set(Value);

Language::request()->get()
Language::request()->set()
Language::request()->all()

Application::language()->all();
Application::language(Id)->get();
Application::language(Id)->set(Value);
*/
class AssetLanguage extends AssetId
{
    public function all()
    {
        return Application::$langlist;
    }
    public function has()
    {
        if ($this->Id) {
        	return isset(Application::$langlist[$this->Id]);
        }
    }
    public function get($option=null)
    {
        if ($this->Id) {
        	return $this->Engine($option);
        }
    }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return Application::$langlist[$this->Id]=func_get_args()[0];
        	}
        }
    }
    private function Id($Id)
    {
        if (isset(Application::$langlist[$Id])) {
            return Application::$langlist[$Id];
        } else {
            return $Id;
        }
    }
    private function Engine($v=array())
    {
        return preg_replace_callback(Application::$config['ATR'],
            function ($k) use ($v) {
                if ($v[$k[1]]) {
                    return $v[$k[1]];
                } elseif (isset(Application::$langlist[$k[1]])) {
                    // NOTE: if language has
                    return Application::$langlist[$k[1]];
                } elseif (ctype_upper($k[1]{0})) {
                    // NOTE: when uppercase
                    return $k[1];
                }
            }, $this->Id($this->Id)
        );
    }
    public function __toString()
	{
	    return $this->get();
	}
}