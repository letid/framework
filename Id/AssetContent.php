<?php
namespace Letid\Id;
/*
$Content = new Content(Id);
$Content->get();
$Content->set(Value);
$Content->all();

Content::request(Id)
Content::request(Id)->get()
Content::request(Id)->set(Value)
Content::request()->all()

Application::content(Id);
Application::content(Id)->get();
Application::content(Id)->set(Value);
Application::content()->all();
*/
class AssetContent extends AssetId
{
    public function all()
    {
        return Application::$content;
    }
    public function has()
    {
        if ($this->Id) {
        	return isset(Application::$content[$this->Id]);
        }
    }
    public function set()
    {
        if ($this->Id) {
        	if (func_get_args()) {
        		return Application::$content[$this->Id]=func_get_args()[0];
        	}
        }
    }
    public function get()
    {
        if ($this->has()) {
        	return Application::$content[$this->Id];
        }
    }
    public function resolve()
    {
        if ($this->has()) {
        	return Application::$content[$this->Id];
        } else if (Application::hasProperty($this->Id)) {
            if ($name=Application::getProperty($this->Id) and is_scalar($name)) {
                return $name;
            }
        }
        // if ($this->has()) {
        // 	return Application::$content[$this->Id];
        // } else if (Application::hasProperty($this->Id)) {
        //     if ($name=Application::getProperty($this->Id) and is_scalar($name)) {
        //         return $name;
        //     }
        // }
    }
    public function statics()
    {
        if (Application::hasProperty($this->Id)) {
            if ($name=Application::getProperty($this->Id) and is_scalar($name)) {
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
            if (is_scalar($varName=Application::getProperty($name))) {
                return $varName;
            }
        }
    }
    public function __toString()
    {
        return $this->get();
    }
}