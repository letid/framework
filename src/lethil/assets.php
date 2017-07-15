<?php
namespace letId\lethil
{
	trait assets
	{
		static function arrays($Id=null)
		{
			return new self::$classExtension['arrays']($Id);
		}
		/**
		* TODO: add more module
		*/
		static function assist($Id=null)
		{
			return new self::$classExtension['assist']($Id);
		}
		static function content($Id=null)
		{
			return new self::$classExtension['content']($Id);
		}
		static function cookie($Id=null)
		{
			return new self::$classExtension['cookie']($Id);
		}
		static function directory($Id=null)
		{
			return new self::$classExtension['directory']($Id);
		}
		/**
		* TODO: add more filter
		*/
		static function filter($Id=null)
		{
			return new self::$classExtension['filter']($Id);
		}
		static function html($Id=array())
		{
			return new self::$classExtension['html']($Id);
		}
		static function language($Id=null)
		{
			return new self::$classExtension['language']($Id);
		}
		static function session($Id=null)
		{
			return new self::$classExtension['session']($Id);
		}
		static function template($Id=array())
		{
			return new self::$classExtension['template']($Id);
		}
		static function timer($Id=null)
		{
			return new self::$classExtension['timer']($Id);
		}
	}
}