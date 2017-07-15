<?php
namespace letId\lethil
{
	trait requests
	{
		static function form($Id=null)
		{
			return new self::$classExtension['form']($Id);
		}
		static function log($Id=null)
		{
			return new self::$classExtension['log']($Id);
		}
	}
}