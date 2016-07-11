<?php
namespace letId\asset
{
	abstract class avail
	{
		/**
		* @var (string) Slashes, abstract
		* letId\httpRequest, letId\httpInitiate, letId\httpResponse, letIl leID, Hyper, leyper
		*/
		const SlP='.', SlA='/', SlB='\\', SlH='://', SlS = ' ', SlE = ';';
		// static $ANS,$ADR;
		/**
		* @var (object)
		* self or property -> working
		*/
		static $application;
		/**
		* @var (string) letId root directory
		*/
		static $rootId = __DIR__.'/../';
		/**
		* @var (string) application's name
		*/
		static $name;
		/**
		* @var (array) html content used in Template, Menu and Language
		*/
		static $content = array();
		static $contextId=array(), $contextLog=array(), $contextResponse, $contextType='responseTemplate';// $contextMeta,$contextHeader,$contextExt;
		/**
		* @var (object) database connection status
		*/
		static $databaseConnection = 'Unavailable';
		/**
		* @var (array) setting, configuration, environment
		*/
		static $config = array(
			/**
			* all applications root directory
			*/
			'ARO' => '../app/',
			/**
	        * application's namespace, ANS can not be modified!
	        */
	        'ANS'=>'',
	        /**
	        * application's directory, ADR can not be modified! Not in used (at the moment)!
	        */
	        'ADR'=>'',
			/**
			* error's folder which should be under applications root directory
			*/
			'ARD' => 'error/',
			/**
			* application route's classname
			*/
			'ASR' => 'route',
			/**
			* down from here can be modified in: app\route and environment.ini
			*/
			'visitsPrevious'=>0,
	        'visitsReset'=>999,
			'signCookieId'=>'letIdUser',
			/**
			* NOTE: default language
			*/
			'language'=>'en',
			'version'=>'1.0',
			// application's authorization class
			'ASA' => 'authorization',
			// application's map namespace.
			'ASP' => 'map',
			// application's mail class
			'ASM' => 'mail',
			// application's environment ini file.
			'ASE' => 'environment',
			// application's validation class (Not in use).
			'ASV' => 'validation',
			// application's configuration class
			'ASC' => 'configuration',
			// application's response
			'ASO' => 'response',
			// database,
			// Page's home in $Page->Array
			'APH' => 'home',
			'AHS' => 'Concluded',
			'AHF' => 'class',
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
		* @var (array) User
		*/
		static $user;
		/**
		* @var (string) Hostname
		*/
		static $hostname;
		/**
		* @var (object) directory
		*/
		static $dir;
		/**
		* @var (array) Language list
		*/
		static $langlist;
		/**
		* @var (string) Language parameter
		*/
		static $langPara = 'language';
		/**
		* @var (array) Language name
		*/
		static $langname = array();
		/**
		* @var (array) Language name
		* http://domain.ext, used in Http
		*/
		static $http;
		/**
		* @var (array) used in Http
		*/
		static $uri = array(), $uriPath;
		/**
		* @var (array) Page
		*/
		static $Verso, $VersoURI;
		/**
		* @var (array) Menu -> option
		* prefixLink
		*/
		static $VersoMenuOption = array(
			'varName'=>'menu.', 'menu'=>'ol', 'menuClass'=>'menu', 'list'=>'li', 'activeClass'=>'active', 'hasChild'=>'hasChild', 'hasChildSuffix'=>'Child', 'type'=>'page', 'fullURL'=>0
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
		static $Alert = array(
			'template'=>'template/',
			'resource'=>'resource',
			'error'=>'error',
			'maintaining'=>'maintaining',
			'application'=>'application',
			'database'=>'database',
			'configuration'=>'configuration',
			'route'=>'route',
			'class'=>'class',
			'method'=>'method',
			'success'=>'success'
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
		* NOTE: HTML tag generator
		* array('<tag attr/>','<tag attr>text</tag>','text') ---> '/\w+/'
		* @var HtmlSelfCloser(AHS), HtmlElement(AHE), HtmlPreg(AHR)
		*/
		static $AHS = array('img','input','hr','br','meta','link');
		static $AHE = array('<{tag}{attr}/>','<{tag}{attr}>{text}</{tag}>','{text}');
		const AHR 	= '/{(.*?)}/';
		/**
		* NOTE: framework info.
		*/
		static $letid = array(
			'build' => '07.10.16.17.20',
			'version' => '1.0.8',
			'name' => 'letId',
			'description' => 'letId PHP Framework'
		);
		/**
		* NOTE: Class availables
		*/
		static $database = array('host'=>0,'port'=>0,'username'=>0,'password'=>0,'database'=>0);
		static $requestMail, $requestValidation, $requestAuthorization, $requestConfiguration;
		/**
		* parent constructor
		* @var (string) Id
		*/
		public $Id = '!';
		public function __construct($Id='')
		{
			$this->Id = $Id;
		}
		static function request($Id=null)
		{
			return new self($Id);
		}
		static function language($Id=null)
	    {
			return new language($Id);
	    }
		static function template($Id=array())
	    {
			return new template($Id);
	    }
		static function html($Id=array())
	    {
			return new html($Id);
	    }
		static function content($Id=null)
	    {
			return new content($Id);
	    }
		static function directory($Id=null)
	    {
			return new directory($Id);
	    }
		static function cookie($Id=null)
	    {
			return new cookie($Id);
	    }
		static function session($Id=null)
	    {
			return new session($Id);
	    }
		static function timer($Id=null)
	    {
			return new timer($Id);
	    }
		static function filter($Id=null)
		{
			// TODO: add more module
			return new filter($Id);
		}
		static function assist($Id=null)
	    {
			// TODO: add more module
			return new assist($Id);
	    }
		static function arrays($Id=null)
	    {
			return new arrays($Id);
	    }
		static function configuration($Id=null)
	    {
			return new self::$config['ASC']($Id);
	    }
		static function validation($Id=array())
	    {
			return new self::$config['ASV']($Id);
	    }
		static function authorization($Id=array())
	    {
			return new self::$config['ASA']($Id);
	    }
		static function mail($Id=array())
	    {
			return new self::$config['ASM']($Id);
	    }
	}
}