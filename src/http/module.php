<?php
namespace letId\http;
class module
{
    public function __construct($Id=null)
	{
		$this->Id = $Id;
	}
    static function request($Id)
	{
		return new self($Id);
	}
    public function loader()
    {
        $loader = new \Composer\Autoload\ClassLoader();
        $loader->addPsr4($this->name(),array(avail::$dir->root),true);
        // $loader->loadClass(\App\Private\Configuration);
        $loader->register(true);
        // $loader->setUseIncludePath(true);
        // return $loader;
        // print_r($loader);
    }
    public function name($Id='')
    {
        return avail::$config['ANS'].avail::SlB.$Id;
    }
    private function nameExists($Id)
    {
		if (class_exists($Classname = $this->name($Id))) {
			return $Classname;
		}
	}
    /**
    * support extension for configuration
    */
	public function configuration()
    {
        $filename = avail::$config['ASC'];
        avail::$config['ASC'] = $this->nameExists($filename);
        if (avail::$config['ASC']) {
            if (is_subclass_of(avail::configuration(), $this->Id)) {
                return 1;
            } else {
                assign::template('configuration')->error(array('filename'=>$filename,'require'=>'require to extends!','root'=>avail::$config['ARO']));
            }
        } else {
            return avail::$config['ASC'] = $this->Id;
        }
	}
    /**
    * support extension for validation, mail, authorization, form
    */
	public function extension($Id)
    {
        $Classname = $this->nameExists(avail::$config[$Id]);
        if (!$Classname) {
            $Classname = $this->Id;
        }
        avail::$config[$Id] = $Classname;
	}
    /**
    * support extension for response
    */
	public function response($Id)
    {
        $Classname = $this->nameExists(avail::$config['ASO']);
		if (!$Classname) {
            $Classname = $this->Id;
        }
        return new $Classname($Id);
	}
    /**
    * environment
    */
    public function environment()
	{
		return parse_ini_file(avail::$dir->root.avail::$config['ASE'].avail::SlP.avail::$Extension['environment'],true);
	}
    /**
    * route
    */
    public function route()
    {
		if ($Classname = $this->nameExists(avail::$config['ASR'])) {
			return new $Classname;
		} else {
            assign::template('route')->error(array('class'=>avail::$config['ASR'],'root'=>avail::$dir->root));
		}
	}
    /**
    * map
    */
    public function map()
    {
    	if ($Classname = $this->nameExists(avail::$config['ASP'].avail::SlB.$this->Id)) {
    		return new $Classname;
    	}
    }
}
