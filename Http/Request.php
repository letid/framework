<?php
namespace Letid\Http;
abstract class Request extends Response
{
	/*
		Everything down here can be modified in:
			- App\Routine
			- App\Initiate
			- App\Environment\Configuration
		To avoid errors/warnings we just sat default value.
	*/
	/*
		AIN: Initiation Classname
	*/
	const AIN = 'Initiate';
	/*
		APE: Page Classname and Foldername
	*/
	const APE = 'Pages';
	/*
		AHM: Homepage in $Page->Array
	*/
	const AHM = 'home';
	/*
		ANV: private Environment Path
	*/
	const ANV = 'Environment';
	/*
		ANC: private Environment->Configuration Classname and Foldername
	*/
	const ANC = 'Configuration';
	/*
		AMP: if no Hostname matched
	*/
	const AMP = null;
	/*
    	ARO: the application directory
    */
    const ARO = '../app/';
    /*
    	ARN: Errors and Notification responsive directory
    */
    const ARN = '../app/errors/';
	/*
        ANS: the Application Namespace, this can not be modified!
		const ANS = __NAMESPACE__;
    */
    /*
        ADR: Application Directory, this can not be modified! Not in used (at the moment)!
		const ADR = __DIR__;
    */
	/*
		APEN: Page Menu
	*/
	const APM = 'menu';
	/*
		APCA: Page Class
	*/
	const APC = 'Class';
	/*
		APME: Page Method
	*/
	const APD = 'Method';
	/*
		ATR: Template structure -> Regex
	*/
	const ATR = '/[{](.*?)[}]/';
	/*
		NOTE: variables available through Application Namespace!
		$map, $directory...
	*/
	protected $map = array();
	protected $page = array();
	protected $directory = array();
}
