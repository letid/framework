<?php
namespace Letid\Id;
class Config
{
	/*
		NOTE: Slashes
	*/
	const SlA = '/', SlB = '\\';
	/*
		NOTE: Root Directory
	*/
	protected static $Root = __DIR__;
	static $list = array();
	static $Notification = array(
		'root'=>'/../Notification/',
		'error'=>'error',
		'database'=>'database',
		'maintaining'=>'maintaining'
	);
	// static $Error = 'error';
	// static $Maintaining = 'maintaining';
	// static $Warning = 'warning';
	// static $Invalid = 'invalid';
	// static $Notification = '/../Notification/';
	static $Extension = array(
		'template'=>'.html'
	);
	static $NoApplicationExists = array(
		'Title'=>'Invalid configuration',
		'Description'=>'Oops, Invalid configuration, no configuration file found!',
		'Message'=>'No Application exists!'
	);
	static $NoApplicationInitiation = array(
		'Title'=>'No Initiate Class found',
		'Description'=>'Oops, Invalid configuration, no Initiate class found!',
		'Message'=>'Invalid Initiate Class!'
	);
	static $DatabaseConnection = array(
		'Title'=>'Invalid Database configuration',
		'Description'=>'Oops, Invalid configuration!',
		'Message'=> 0,
		'Code'=> 0
	);
	static $letid = array(
		'Build' => '29.03.16.22.59',
		'Version' => '1.0.1',
		'Name' => 'Letid',
		'Description' => 'Letid PHP Framework'
	);
}
