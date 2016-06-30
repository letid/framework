<?php
namespace Letid\Id;
abstract class Application
{
	/**
	* @var (string) Slashes
	* letId\httpRequest, letId\httpInitiate, letId\httpResponse, letIl leID, Hyper, leyper
	*/
	const SlP='.', SlA='/', SlB='\\', SlH='://', SlS = ' ', SlE = ';';
	/**
	* @var (object)
	* self property -> working
	*/
	static $app;
	/**
	* @var (string) Root directory
	*/
	static $root = __DIR__;
	/**
	* @var (string) Application's name
	*/
	static $name;
	/**
	* @var (array) html content used in Template, Menu and Language
	*/
	static $content = array('test'=>'this is test');
	/**
	* @var (object) database connection status
	*/
	static $db = 'Unavailable';
	/**
	* @var (array) Setting, Configuration, Environment
	*/
	static $config = array(
		// Application Initiation's Classname
		'AAI' => 'Application',
		/*
		NOTE: Down from here can be modified in:
			- App\Application
		*/
		'signCookieId'=>'letIdUser',
		'language'=>'en',
		'version'=>'1.0',
		// Authorization's Class
		'AIA' => 'Authorization',
		// Page's home in $Page->Array
		'APN' => 'Pages',
		// Environment's ini file
		'AIV' => 'Environment',
		// Environment's Classname  (Not in use)
		'AVC' => 'Configuration',
		// Page's home in $Page->Array
		'APH' => 'home',
		// Page's Authorization
		'APA' => 'Auth',
		// Page's Link
		'APL' => 'Link',
		// Page's Type
		'APT' => 'Type',
		// Page's Suffixes (Not in use)
		'APF' => 'Page',
		// Page's Menu
		'APE' => 'Menu',
		// Page's Class
		'APC' => 'Class',
		// Class's Method
		'APM' => 'Method',
		// Template format -> Regex
		'ATR' => '/[{](.*?)[}]/'
	);
	/**
	* @var (array) User info.
	*/
	static $user = array();
	/**
	* @var (string) Hostname
	*/
	static $hostname;
	/**
	* @var (object) directory
	*/
	static $dir = array(
		'template'=>'template', 'language'=>'language'
	);
	/**
	* @var (array) Language list
	*/
	static $langlist;
	/**
	* @var (string) Language parameter
	*/
	static $langpara = 'language';
	/**
	* @var (array) Language name
	*/
	static $langname = array();
	/**
	* @var (array) Language name
	* http://domain.ext, used in Http
	*/
	static $url, $domain, $http;
	/**
	* @var (array) used in Http
	*/
	static $uri = array();
	/**
	* @var (array) Page
	*/
	static $Verso, $VersoURI;
	/**
	* @var (array) Menu -> option
	* prefixLink
	*/
	static $VersoMenuOption = array(
		'varName'=>'menu_', 'menu'=>'ol', 'menuClass'=>'menu', 'list'=>'li', 'activeClass'=>'active', 'hasChild'=>'hasChild', 'hasChildSuffix'=>'Child', 'type'=>'page', 'fullURL'=>0
	);
	/**
	* @var (array) Menu -> default
	*/
	static $VersoMenuDefault = array(
		'Type'=>'page', 'Auth'=>array()
	);
	/**
	* @var (array) minimum page requirement
	*/
	static $VersoArrangeDefault = array(
		'Class'=>'home', 'Method'=>'home'
	);
	/**
	* @var (array) Framework Templates
	*/
	static $Notification = array(
		'dir'=>'/../Notification/',
		'error'=>'error',
		'database'=>'database',
		'maintaining'=>'maintaining'
	);
	/**
	* @var (array) Default Template's Extension
	*/
	static $Extension = array(
		'template'=>'html',
		'language'=>'ini',
		'environment'=>'ini',
		'script'=>'php'
	);
	/**
	* NOTE: Message On Core Configuration is Missing/Invalid
	* TODO: NoApplicationExists -> Message (Oops, Project configuration file LETHIL is missing! Pleace check INDEX in project/lethil/, until then lethil is under-construction!)
	*/
	static $InvalidConfiguration = array(
		'Title'=>'Invalid configuration',
		'Description'=>'Oops, no configuration file found!',
		'Message'=>'No application exists!'
	);
	/**
	* NOTE: Message On Application Initiation is Missing/Invalid
	*/
	static $InvalidApplication = array(
		'Title'=>'No Initiate Class found',
		'Description'=>'Oops, no Initiate class found!',
		'Message'=>'Invalid configuration!'
	);
	/**
	* NOTE: Message On Application Class & Method are missing
	*/
	static $InvalidClass = array(
		'Title'=>'Class not found',
		'Description'=>'Oops, no valid Class found!',
		'Message'=>'Unable to reach Class!'
	);
	/**
	* NOTE: Message On Application Class & Method are missing
	*/
	static $InvalidMethod = array(
		'Title'=>'{Method}',
		'Description'=>'Oops, no valid Method found!',
		'Message'=>'Unable to call Method: {Method}!'
	);
	/**
	* NOTE: Message On Application Database is Error
	*/
	static $InvalidDatabase = array(
		'Title'=>'Invalid Database configuration',
		'Description'=>'Oops, invalid configuration!',
		'Message'=> 'error',
		'Code'=> 1
	);
	/**
	* NOTE: HTML tag generator, array('<tag attr/>','<tag attr>text</tag>','text') ---> '/\w+/'
	* HtmlSelfCloser(AHS), HtmlElement(AHE), HtmlPreg(AHR)
	*/
	static $AHS = array('img','input','hr','br','meta','link');
	static $AHE = array('<{tag}{attr}/>','<{tag}{attr}>{text}</{tag}>','{text}');
	const AHR 	= '/{(.*?)}/';
	/**
	* NOTE: Framework Info
	*/
	static $letid = array(
		'Build' => '30.06.16.11.17',
		'Version' => '1.0.6',
		'Name' => 'Letid',
		'Description' => 'Letid PHP Framework'
	);
	/**
	* NOTE: Class availables
	*/
	static $database;
	static function language($Id=null)
    {
		return new AssetLanguage($Id);
    }
	static function template($Id=array())
    {
		return new AssetTemplate($Id);
    }
	static function html($Id=array())
    {
		return new AssetHtml($Id);
    }
	static function content($Id=null)
    {
		return new AssetContent($Id);
    }
	static function configuration($Id=null)
    {
		return new AssetConfiguration($Id);
    }
	static function cookie($Id=null)
    {
		return new AssetCookie($Id);
    }
	static function session($Id=null)
    {
		return new AssetSession($Id);
    }
	static function timer($Id=null)
    {
		return new AssetTimer($Id);
    }
	static function filter($Id=null)
	{
		// TODO: add more module
		return new AssetFilter($Id);
	}
	static function menu($Id=array())
    {
		// TODO: add more module
		return new MenuId($Id);
    }
	static function assist($Id=null)
    {
		// TODO: add more module
		return new AssistId($Id);
    }
	static function mail($Id=array())
    {
		// TODO: working
		return new MailId($Id);
    }
	static function arrays($Id=null)
    {
		// TODO: add more module
		return new ArraysId($Id);
    }
	static function appProperty()
	{
		self::$app = new \ReflectionClass(self::class);
	}
	static function hasProperty($Id)
	{
        return self::$app->hasProperty($Id);
	}
	static function getProperty($Id)
	{
        return self::$app->getStaticPropertyValue($Id);
	}
	static function setProperty($Id,$Value)
	{
        return self::$app->setStaticPropertyValue($Id,$Value);
	}
}