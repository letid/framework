<?php
namespace Letid\Id;
abstract class AssetId
{
    protected $Id = '!';
    public function __construct()
    {
        if (func_get_args()) {
            $this->Id = func_get_args()[0];
        }
    }
    static function request($Id=null)
    {
        return new self($Id);
    }
    public function isCallable()
	{
        return is_callable(array($this,$this->Id));
	}
}