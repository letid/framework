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
      foreach (avail::$classExtension as $Id => $value) {
        if (class_exists($this->classPath($Id.'Controller'))) {
          if (is_subclass_of($this->IdName,$value)) {
            avail::$classExtension[$Id]=$this->IdName;
          }
        }
        $this->extensionConnect($Id);
      }
  	}
  	private function extensionConnect($Id)
    {
      // NOTE: database, authentication
      if (property_exists(avail::class, $Id)) avail::${$Id} = new avail::$classExtension[$Id];
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
        if (isset($ase['assetMaintaining'])){
          return !assign::template('maintaining')->error(array('msg'=>$ase['assetMaintaining']));
        }
        if (isset($ase['database']) && is_array($ase['database'])) {
          avail::configuration('database')->set(array_merge(avail::$database,$ase['database']));
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
    * module::load()->language_management_system();
    */
    public function language_management_system()
    {
      if (isset(avail::$config['uriAdmin.language'])){
        if (avail::$uri && avail::$uri[0] == avail::$config['uriAdmin.language']) {
          // print_r(avail::$dir);
          // print_r(avail::$config);
          // print_r(avail::$localeList);
          // print_r(avail::$localeName);
          // $files1 = scandir(avail::$dir->language);
          // print_r($files1);
          // $this->languageList = 'abc';
          // avail::content('languageList')->set('abc');
          $languageContent=array(
            'ol'=>array(
              'text'=>array_map(function($k,$v) {
                return array(
                  'li'=>array(
                    'text'=>array(
                      'span'=>$k, ' - ', $v
                    )
                  )
                );
              }, array_keys(avail::$localeList), array_values(avail::$localeList)),
              'attr'=>array(
                'class'=>'note'
              )
            )
          );
          return !assign::template('language')->error(
            array(
              'languageContent'=>avail::html($languageContent)
            )
          );
        }
      }
      return true;
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
