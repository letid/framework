<?php
namespace Letid\Http;
abstract class Response
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
	// protected static $error,$warning,$message=array(),$Header,$Content,$ContentType;
	/*
		NOTE: HTML
	*/
	protected static $Header, $Content, $ContentType;
	// public static $ContentHeader, $Content, $ContentType;
	// public static $PageHeader, $PageContent, $PageType;
	/*
		NOTE: Properties available through Application!
	*/
	protected static $int = array();
	/*
		NOTE: Sessions Identification!
	*/
	protected static $SpeedId = '123';
}
