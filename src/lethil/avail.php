<?php
namespace letId\lethil
{
	abstract class avail
	{
		/**
		* @var (string) Slashes, abstract
		* letId\httpRequest, letId\httpInitiate, letId\httpResponse, letIl leID, Hyper, leyper
		*/
		const SlP='.', SlA='/', SlB='\\', SlH='://', SlS = ' ', SlE = ';';
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
		* NOTE changed $contents to $context
		*/
		static $contents = array();
		static $responseContext, $responseMethod, $responseHeader=array(), $responseLog=array(), $responseType='responseTemplate'; // responseMeta
		/**
		* @var (object) database connection status
		*/
		static $databaseConnection = 'Unavailable';
		/**
		* @var (array) setting, configuration, environment
		*/
		static $config = array(
			/**
			* all application's root directory
			* @var default 'app'
			*/
			'ARO' => '../',
			/**
      * application's namespace, ANS can not be modified!
      */
      'ANS'=>'',
      /**
      * application's directory, ADR can not be modified! 
			* NOTE: Not in use (at the moment)! as routeController->application already defined dynamically!
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
			// NOTE: use in response->resourceCache
			'resourceCache'=>0,
			// NOTE: down from here can be modified in: app\route and environment.ini
			'visitsPrevious'=>0,
	    'visitsLimit'=>999,
			'visitsReset'=>'visitsReset',
			'signCookieId'=>'letIdUser',
			// NOTE: default language->locale
			'locale'=>'en',
			// default storage.root
			'storage.root'=>0,
			'build'=>'1',
			'version'=>'1.0',
			// application's map namespace.
			'ASP' => 'map',
			// application's environment ini file.
			'ASE' => 'environment',
			// Page's home in $Page->Array
			'APH' => 'home',
			'AHF' => 'class',
			'AHS' => 'Concluded',
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
		* className contains the list of the extenable classes!
		* @var (array) className
		*/
		static $classRequest = array(
			// application's configuration class
			'configuration' => \letId\request\configuration::class
		);
		/**
		* classExtension is optional and it's contains the list of the extenable classes that are NOT use in the framework
		* @var (array) classExtension
		* TODO not in use
		*/
		// extendedDatabase extendedArrays
		static $classExtension = array(
			'arrays' => \letId\assets\arrays::class,
			'assist' => \letId\assets\assist::class,
			'content' => \letId\assets\content::class,
			'cookie' => \letId\assets\cookie::class,
			'directory' => \letId\assets\directory::class,
			'filter' => \letId\assets\filter::class,
			'html' => \letId\assets\html::class,
			'language' => \letId\assets\language::class,
			'session' => \letId\assets\session::class,
			'template' => \letId\assets\template::class,
			'timer' => \letId\assets\timer::class,
			
			'database' => \letId\request\database::class,
			'form' => \letId\request\form::class,
			'log' => \letId\request\log::class,
			
			'authorization' => \letId\request\authorization::class,
			'mail' => \letId\request\mail::class,
			'response' => \letId\request\response::class,
			// TODO (Not in use).
			'validation' => \letId\request\validation::class
			// NOTE: no needed
			
			// 'map' => \letId\request\map::class,
			// 'verse' => \letId\request\verse::class,
			// 'verso' => \letId\request\verso::class
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
		* @var (array) Language list, locale
		*/
		static $localeList;
		/**
		* @var (string) Language parameter
		*/
		static $localePara = 'language';
		/**
		* @var (array) Language name
		*/
		static $localeName = array();
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
		static $Verso, $VersoURI, $VersoMenu=array();
		/**
		* @var (array) Menu -> option
		* prefixLink
		*/
		static $VersoMenuOption = array(
			'varName'=>'menu.', 'menu'=>'ol', 'class'=>'menu', 'attr'=>array(), 'list'=>'li', 'activeClass'=>'active', 'hasChild'=>'hasChild', 'hasChildSuffix'=>'Child', 'type'=>'page', 'fullURL'=>0
		);
		/**
		* @var (array) Menu -> default
		* TODO: not in use
		*/
		static $VersoMenuDefault = array(
			'Page'=>'home', 'Type'=>'page'
			// 'Page'=>'home','Class'=>'home', 'Method'=>'home', 'Type'=>'page'
		);
		/**
		* @var (array) minimum page requirement
		*/
		static $VersoArrangeDefault = array(
			'Class'=>'home', 'Method'=>'home'
		);
		/**
		* @var (array) framework notification Templates
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
		* @var (array) default Template's Extension
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
		* NOTE: class availables
		*/
		static $database = array('host'=>0,'port'=>0,'username'=>0,'password'=>0,'database'=>0);
		static $requestMail, $requestValidation, $requestAuthorization, $requestConfiguration;
		/**
		* parent constructor
		* @var (string) Id
		*/
		public $Id;
		use extensions;
		use assets;
		use requests;
		public function __construct($Id='')
		{
			$this->Id = $Id;
		}
		static function request($Id=null)
		{
			return new self($Id);
		}
	}
}