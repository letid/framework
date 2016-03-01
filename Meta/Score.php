<?php
namespace Letid\Meta;
trait Score
{
	/*
		NOTE: HTML
	*/
	protected static $Content, $ContentType, $ContentMeta;

	protected static $scoreConfiguration = array();

	protected static $scoreVar = array();

	private static $ETIO = 'Everything in order!';
	private static $COIV = 'Configuration Invalid!';
	private static $COVL = 'Configuration Valid!';
	protected static $NoApplicationExists = array(
		'Title'=>'Invalid configuration',
		'Description'=>'Oops, Invalid configuration, no configuration file found!',
		'Message'=>'No Application exists!'
	);
	protected static $NoApplicationInitiation = array(
		'Title'=>'No Initiate Class found',
		'Description'=>'Oops, Invalid configuration, no Initiate class found!',
		'Message'=>'Invalid Initiate Class!'
	);
	protected static $DatabaseConnection = array(
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

	// public $PublicInfo = array();
	// protected $ProtectedInfo = array();
	// private $PrivateInfo = array();
	//
	// public static $PublicStaticInfo = array();
	// protected static $ProtectedStaticInfo = array();
	// private static $PrivateStaticInfo = array();
	// private static $initAccount = array();
	// private static $infoAccount = array();
	// private static $infoKnowledge = array();
	// private static $infoArranage = array();
	// private static $infoNote = array();
	// private static $intNote = array();
	// private static $infoDirectory = array();
	// private static $infoStatistics = array();
	// private static $infoScoop = array();
	// private static $infoScore = array();
	// private static $infoStatement = array();
	/*
	NOTE: Rrefixs for...
		 ...traits
		Requestive
		Initiative
		Responsive
		Engine
		Exists
		Validate
		...abstracts class
		???
		Termination
		Interjection
		Integration

		arrangeRequestive
		clusterRequestive
		--
		pageRequestive

		$this->initiateRequestive($app->page);
		$this->pageIntegration();
		$this->pageTermination();

		$this->Interjection($app->page);
		$this->Integration();
		$this->Termination();
	*/
	/*
	No Application directory!
	No Application exists!
	No Application Initiation!
	No Application Configuration!
	*/
	/*
	namespace Letid\Http;
	abstract class Response

	namespace Letid\Http;
	use Letid\Config\Info
	abstract class Response
	trait Score {

	}
	class Application {
	    use Score
	}
	abstract class Response extends Application {

	}
	*/
}
