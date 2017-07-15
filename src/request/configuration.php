<?php
namespace letId\request
{
  class configuration extends \letId\assist\configuration
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