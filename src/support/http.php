<?php
namespace letId\support
{
    abstract class http extends \letId\http\request
    {
        /**
        * NOTE: Everything down here can be modified in:
        * - app\routeController
        * To avoid errors/warnings we just sat default value.
    	* Route Configuration!
    	*/
    	protected $application = array();
        /**
        * CHANGED: removed ($ADT) -> default application, if no hostname matched!
        * application Configuration!
    	*/
        /**
        * application's directory rewrite!
    	*/
        protected $rewrite = array();
        /**
        * application's root
    	*/
        // protected $root = '../app/';
        /**
        * application's directory
    	*/
        protected $directory = array(
            'template'=>'template', 'language'=>'language'
        );
        /**
        * application's configuration
    	*/
        protected $configuration = array(
            /**
            * NOTE: (ANS, ADR) application's Namespace and appliations's Root Directory can't be modified!
            */
            // 'ANS'=>__NAMESPACE__,
            // 'ADR'=>__DIR__
        );
    }
}