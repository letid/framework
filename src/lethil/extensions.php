<?php
namespace letId\lethil
{
	trait extensions
	{
		/**
		* NOTE: avail::configuration() is extendable user application
		*/
		static function configuration($Id=null)
		{
			return new self::$classRequest['configuration']($Id);
		}
		/**
		* NOTE: avail::validation() is extendable user application
		*/
		static function validation($Id=array())
		{
			return new self::$classExtension['validation']($Id);
		}
		/**
		* NOTE: avail::authorization() is extendable user application
		*/
		static function authorization($Id=array())
		{
			return new self::$classExtension['authorization']($Id);
		}
		/**
		* NOTE: avail::mail() is extendable user application
		*/
		static function mail($Id=array())
		{
			return new self::$classExtension['mail']($Id);
		}
	}
}