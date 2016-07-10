<?php
namespace letId\support
{
    abstract class http extends \letId\http\request
    {
        protected $application = array();
        /**
        * NOTE: Everything down here can be modified in:
        * - app\routeController
        * To avoid errors/warnings we just sat default value.
    	*/
        /**
        * application's directory rewrite!
    	*/
        protected $rewrite = array();
        /**
        * application's directory
    	*/
        protected $directory = array();
        /**
        * application's configuration
    	*/
        protected $configuration = array();
    }
}