<?php
namespace letId\request
{
  abstract class http extends \letId\http\await
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
    * application's directories
    */
    protected $directory = array();
    /**
    * application's configuration
    */
    protected $configuration = array();
    /**
    * application's setting
    */
    protected $setting = array();
  }
}