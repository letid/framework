<?php
namespace Lethil\Config;
class Project extends Http
{
	protected $application = array();
    protected $default = false;
	public function Response()
    {
		// echo __DIR__.'/../';
		// self::meset($this->getRoot());
		// self::meset(__DIR__);
		// print self::meget('<hr>');
		print self::$Content;
		// if(self::$message){
		// 	echo include(self::$setting['dir']."default.error.project.configuration.html");
		// 	// echo file_get_contents(self::$setting['dir']."default.error.project.configuration.html");
		// 	// echo self::$setting['dir'];
		// }
		// print '<hr>';
		// $data = new Template('abc')->language();
		// print_r($this->loader);
		// print_r(Template::language());
		// echo self::PrivateMethodTest();
		// print self::$RequestedContent;
		// echo $this->PublicMethodTest();
		// print_r(parent::host);
		// print_r(self::$uri);
		// print_r($this->publicMethod());
		// print_r($GLOBALS);
		// print_r($GLOBALS[App]->host);
		// print_r(get_defined_vars());
		/*
		Content, Header, ContentType
		*/
    }
}
