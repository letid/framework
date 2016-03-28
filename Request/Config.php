<?php
namespace Letid\Request;
use Letid\Id;
/*
use Letid\Request\Config;
- Config:set(), ...
*/
class Config extends Id\Config
{
    public static function set($name, $value)
    {
        self::$list[$name] = $value;
    }
    public static function get($name)
    {
        return self::$list[$name];
    }
    public static function toArray()
    {
        return self::$list;
    }
    public static function toObject()
    {
        return (object) self::$list;
    }
}
