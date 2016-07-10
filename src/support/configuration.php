<?php
namespace letId\support
{
    class configuration extends \letId\asset\configuration
    {
        /**
        * application's directory rewrite!
    	*/
        protected $rewrite = array(
            'src'=>'resource'
        );
        /**
        * application's directory
    	*/
        protected $directory = array(
            'template'=>'template',
            'language'=>'language'
        );
        /**
        * application's setting
    	*/
        protected $setting = array();
    }
}