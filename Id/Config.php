<?php
namespace Letid\Id;
class Config
{
	static $Root = __DIR__;
	// $Config = array(
	// 	'invalid'=>'configuration.invalid',
	// 	'valid'=>'configuration.valid'
	// ),
	// $Invalid = array(
	// 	'configuration'=>'configuration.invalid',
	// 	'database'=>'configuration.invalid.database',
	// ),
	// $Default = array(
	// 	'configuration'=>'configuration.invalid',
	// 	'database'=>'configuration.invalid.database',
	// ),
	// $Directory = '/../Notification/',
	// $Error = array(
	// 	'root'=>'/../Notification/',
	// 	'error'=>'error',
	// 	'maintaining'=>'maintaining'
	// 	'extension'=>'.html'
	// ),
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
	private $letid = array(
		'Build' => '29.03.16.22.59',
		'Version' => '1.0.1',
		'Name' => 'Letid',
		'Description' => 'Letid PHP Framework'
	);
}
