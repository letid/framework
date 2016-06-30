<?php
namespace Letid\Id;
abstract class Response extends Core
{
    public function __set($name, $value)
    {
        $this->{$name} = $value;
        if (is_scalar($value)) {
            Application::$content[$name]  = $value;
            // Application::content($name)->set($value);
        }
    }
    public function __get($name)
    {
        if (isset(Application::$content[$name])) {
             return Application::$content[$name];
        }
        // return Application::content($name)->get();
    }
}