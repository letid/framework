<?php
namespace Letid\Assist;
abstract class Validate
{
	protected $Id = '!';
    public function __construct()
    {
        if (func_get_args()) {
            $this->Id = func_get_args()[0];
        }
    }
	static function request()
    {
        return new self(func_get_args()[0]);
    }
	function email()
	{
		if(filter_var($this->Id, FILTER_VALIDATE_EMAIL)) return true;
	}
	function url()
	{
		if(filter_var($this->Id, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) return true;
	}
	function date($date,$check_year=NULL,$format='YYYY-MM-DD')
	{
	}
}
