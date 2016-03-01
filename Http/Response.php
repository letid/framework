<?php
namespace Letid\Http;
use Letid\Meta;
abstract class Response extends Meta\Application
{
    /*
		NOTE: Slashes
	*/
	const SlA = '/', SlB = '\\';
	/*
		NOTE: Application Class & Method
	*/
	protected static $APC, $APD;
	/*
		NOTE: variables available through Framework!
	*/
	protected static $error, $warning, $message=array();
	/*
		NOTE: Sessions Identification!
	*/
	protected static $SpeedId = '123';
}
