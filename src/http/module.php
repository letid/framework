<?php
namespace letId\http
{
  class module
  {
    private $Id;
    public function __construct($Id=NULL)
  	{
  		$this->Id = $Id;
  	}
    static function load($Id=NULL)
  	{
  		return new self($Id);
  	}
    public function composer()
    {
      $loader = new \Composer\Autoload\ClassLoader();
      $loader->addPsr4($this->classPath(),array(avail::$dir->root),true);
      // $loader->loadClass(\App\Private\Configuration);
      $loader->register(true);
      // $loader->setUseIncludePath(true);
      // return $loader;
      // print_r($loader);
    }
    private function classPath($Id='')
    {
      return $this->IdName=avail::$config['ANS'].avail::SlB.$Id;
    }
    /**
    * support extension for configuration
    */
  	public function configuration()
    {
      $className = $this->Id.'Controller';
      if (class_exists($this->classPath($className))){
        if (is_subclass_of($this->IdName,avail::$classRequest[$this->Id])) {
          avail::$classRequest[$this->Id]=$this->IdName;
        } else {
          return !assign::template('configuration')->error(array('filename'=>$className,'require'=>'Require to be extended!','root'=>avail::$config['ARO']));
        }
      }
      return true;
  	}
    /**
    * TODO not in use, see more info $classExtension
    * module::load()->extension();
    */
  	public function extension()
    {
      foreach (avail::$classExtension as $key => $value) {
        if (class_exists($this->classPath($key.'Controller'))) {
          if (is_subclass_of($this->IdName,$value)) {
            avail::$classExtension[$key]=$this->IdName;
          }
        }
      }
  	}
    /**
    * NOTE: environment
    * module::load()->environment();
    */
    static function environment()
  	{
  		$ase = parse_ini_file(avail::$dir->root.avail::$config['ASE'].avail::SlP.avail::$Extension['environment'],true);
      if ($ase) {
        avail::configuration($ase)->merge();
        if (isset($ase['database']) && is_array($ase['database'])) {
          if (assign::database(array_merge(avail::$database,$ase['database']))) {
            return false;
          } else {
            // avail::configuration($ase)->merge();
            // NOTE: remove database from $config
            avail::configuration('database')->set();
          }
        }
      }
      return true;
  	}
    /**
    * module::load()->route();
    */
    public function route()
    {
      if (class_exists($this->classPath(avail::$config['ASR']))) {
        return avail::$classRequest['ASR'] = $this->IdName;
      } else {
        assign::template('route')->error(array('class'=>avail::$config['ASR'],'root'=>avail::$dir->root));
      }
  	}
    /**
    * module::load()->map();
    */
    public function map()
    {
    	// if ($Classname = $this->classExists(avail::$config['ASP'].avail::SlB.$this->Id)) return new $Classname;
      if (class_exists($this->classPath(avail::$config['ASP'].avail::SlB.$this->Id))) return new $this->IdName;
    }
  }
}
