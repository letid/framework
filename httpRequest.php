<?php
namespace Letid\Http;
abstract class Request extends \Letid\Id\Request
{
    /**
    * NOTE: Everything down here can be modified in:
    * - App\Route
    * To avoid errors/warnings we just sat default value.
	* Route Configuration!
	*/
	protected $application = array();
    /**
    * CHANGED: removed ($ADT) -> default application, if no hostname matched!
    * Application Configuration!
	*/
    protected $root = '../app/';
    protected $directory = array();
    protected $configuration = array();
    /**
    * Application Initiation's Classname
    * protected $AAI = 'Application';
    * NOTE: Application's Namespace and Appliations's Root Directory can't be modified:
    * const ANS = __NAMESPACE__;
    * const ADR = __DIR__;
    */
}