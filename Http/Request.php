<?php
namespace Letid\Http;
use Letid\Id;
abstract class Request extends Id\Application
{
    use Id\Session, Id\Http, Id\Database, Id\Module, Id\Initiate, Id\Verso, Id\Template, Id\Html;
    /*
		NOTE: Application Configuration!
	*/
	protected $page = array(), $dir = array(), $database = array(), $config = array();
	/*
		Everything down here can be modified in:
			- App\Core
		To avoid errors/warnings we just sat default value.
	*/
    /*
		NOTE: Route Configuration!
	*/
	protected $host = array();
    /*
        $ADA: the applications directory
    */
    protected $ADA = '../app/';
    /*
    	$ADE: Errors and Notification responsive directory
    */
    protected $ADE = '../app/errors/';
    /*
		$ADT: default application, if no Hostname matched!
	*/
	protected $ADT = 0;
    /*
		$ATR: Template format -> Regex
	*/
	protected $ATR = '/[{](.*?)[}]/';
    /*
        $AAI: Initiation's Classname
    */
    protected $AAI = 'Application';
    /*
		Everything down here can be modified in:
			- App\Application
			- App\Environment\Configuration
		To avoid errors/warnings we just sat default value.
	*/
	/*
        $AIV: Environment's Folder and Namespace
    */
    protected $AIV = 'Environment';
	/*
        $AIA: Authorization's Class (not in use)
    */
    protected $AIA = 'Authorization';
    /*
        $AVC: Environment's Classname
    */
    protected $AVC = 'Configuration';
	/*
		APE: Page Namespace and Foldername -> Pages, Pagemap
	*/
	protected $APN = 'Pages';
	/*
		$APH: Page's home in $Page->Array
	*/
	protected $APH = 'home';
    /*
		$APM: Page's Authorization (Not in use)
	*/
	protected $APA = 'Auth';
    /*
		$APM: Page's Type (Not in use)
	*/
	protected $APT = 'Type';
	/*
		$APF: Page's Suffixes (Not in use)
	*/
	protected $APF = 'Page';
	/*
		$APE: Page's Menu
	*/
	protected $APE = 'Menu';
	/*
		$APC: Page's Class
	*/
	protected $APC = 'Class';
	/*
		$APM: Class's Method
	*/
	protected $APM = 'Method';
    /*
		Everything down here can't be modified;
            - Application's Namespace
		          const ANS = __NAMESPACE__;
            - Appliations's Root Directory
		          const ADR = __DIR__;
    */
}
