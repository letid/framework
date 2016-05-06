<?php
namespace Letid\Meta;
abstract class Validate
{
	static function email($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
	}
	static function url($url)
	{
		if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) return true;
	}
	static function date($date,$check_year=NULL,$format='YYYY-MM-DD')
	{
	}
}
