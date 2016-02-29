<?php
namespace Letid\Config;
class Message
{
	static $ETIO = 'Everything in order!';
	static $COIV = 'Configuration Invalid!';
	static $COVL = 'Configuration Valid!';
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
		'Message'=>'',
		'Code'=> 0
	);
	/*
	No Application directory!
	No Application exists!
	No Application Initiation!
	No Application Configuration!
	*/
}
