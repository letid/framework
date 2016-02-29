<?php
namespace Letid\Config;
class File
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
}
