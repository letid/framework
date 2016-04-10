<?php
namespace Letid\Id;
class Config
{
	/*
		SlA: Slashes
	*/
	const SlP='.', SlA='/', SlB='\\', SlH='://';
	/*
		$Root: Root Directory
	*/
	static $Root = __DIR__;
	/*
		$tmp: Temporary
	*/
	static $tmp;
	/*
		$list: Configuration
	*/
	static $list = array();
	/*
		$uri: Configuration, used in Verso
	*/
	static $hostname;
	/*
		$dir: (object)directory
	*/
	static $dir;
	/*
		$language: (array) Language list
	*/
	static $language;
	/*
		$lang: Language default and current merged
	*/
	static $langname = array();
	/*
		$url: http://domain.ext, used in Http
	*/
	static $url;
	/*
		$uri: used in Http
	*/
	static $uri = array();
	/*
		NOTE: $Verso Page
	*/
	static $Verso, $VersoURI;
	/*
		$VersoMenuOption: Menu -> option
	*/
	static $VersoMenuOption = array(
		'varName'=>'menu', 'menu'=>'ol', 'menuClass'=>'menu', 'list'=>'li', 'suffixChild'=>'Child', 'activeClass'=>'active', 'hasChild'=>'hasChild', 'type'=>'page'
	);
	/*
		$VersoMenuDefault: Menu -> default
	*/
	static $VersoMenuDefault = array(
		'Type'=>'page', 'Auth'=>array()
	);
	static $VersoArrangeDefault = array(
		'Class'=>'home',
		'Method'=>'home'
	);
	/*
		NOTE: Framework Templates
	*/
	static $Notification = array(
		'dir'=>'/../Notification/',
		'error'=>'error',
		'database'=>'database',
		'maintaining'=>'maintaining'
	);
	/*
		NOTE: Default Template's Extension
	*/
	static $Extension = array(
		'template'=>'.html',
		'language'=>'ini',
		'script'=>'php'
	);
	/*
		NOTE: Message On Core Configuration is Missing/Invalid
	*/
	static $NoApplicationExists = array(
		'Title'=>'Invalid configuration',
		'Description'=>'Oops, no configuration file found!',
		'Message'=>'No application exists!'
	);
	/*
		NOTE: Message On Application Initiation is Missing/Invalid
	*/
	static $NoApplicationInitiation = array(
		'Title'=>'No Initiate Class found',
		'Description'=>'Oops, no Initiate class found!',
		'Message'=>'Invalid configuration!'
	);
	/*
		NOTE: Message On Application Class & Method are missing
	*/
	static $NoApplicationResponse = array(
		'Title'=>'No Class/Method found',
		'Description'=>'Oops, no valid Method found!',
		'Message'=>'Invalid Class/Method!'
	);
	/*
		NOTE: Message On Application Database is Error
	*/
	static $DatabaseConnection = array(
		'Title'=>'Invalid Database configuration',
		'Description'=>'Oops, invalid configuration!',
		'Message'=> 0,
		'Code'=> 0
	);
	/*
		$letid: Framework Info
	*/
	static $letid = array(
		'Build' => '29.04.03.09.45',
		'Version' => '1.0.3',
		'Name' => 'Letid',
		'Description' => 'Letid PHP Framework'
	);
	/*
		NOTE: HTML tag generator, array('<tag attr/>','<tag attr>text</tag>','text') ---> '/\w+/'
	*/
	/*
		$AHS: HtmlSelfCloser
	*/
	static $AHS = array('img','input','hr','br','meta','link');
	/*
		$AHE: HtmlElement
	*/
	static $AHE = array('<{tag}{attr}/>','<{tag}{attr}>{text}</{tag}>','{text}');
	/*
		$AHR: HtmlPreg
	*/
	static $AHR = '/{(.*?)}/';
	/*
		$Init: Merge these properties from Config->Route->Application (see how it work: Id\Initiate)
	*/
	static $Initiate = array('AIV','AVC','APN', 'APT', 'ATR','APH','APE','APC','APM');
	/*
		NOTE: Everything down here can be modified in:
			- App\Core
		To avoid errors/warnings we just sat default value. Please see Http/Request.
	*/
	static $ATR, $APH, $APT, $APE, $APC, $APM;

}
