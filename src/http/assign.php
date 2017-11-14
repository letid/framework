<?php
namespace letId\http
{
  class assign
  {
    static public $rewriteDirectories=array();
    public function __construct($Id)
  	{
  		$this->Id = $Id;
  	}
    public function host()
    {
      avail::$name=$this->hostExists();
      avail::$config['name']=avail::$name;
      return $this;
    }
    private function hostExists()
    {
      $name = avail::session(avail::$hostname);
      if ($name->has()) {
        return $name->get();
      } else if($Id=$this->hostName()) {
        return $name->set($Id);
      }
    }
    private function hostName()
    {
      if (is_array($this->Id)) {
        foreach ($this->Id as $Id => $Name) {
          if ($Name) {
            if (is_array($Name) and $this->hostEngine($Name)) {
              return $Id; break;
            } elseif ($Name == '*') {
              return $Id; break;
            } elseif ($this->hostMatch($Name)) {
              return $Id; break;
            }
          } else {
            return $Id; break;
          }
        }
      } else {
        return $this->Id;
      }
    }
    private function hostEngine($Name)
    {
      foreach ($Name as $Id) {
        if ($this->hostMatch($Id)) {
          return true; break;
        }
      }
    }
    private function hostMatch($Id)
    {
      return preg_match("/$Id/", avail::$hostname);
    }
    /**
    * NOTE: merging configurations based on app\routeController
    * NOTE: create directory sturcture for both applications and errors
    */
    public function directory($configuration)
    {
      // avail::$config = array_merge(avail::$config,$configuration);
      avail::$dir = (object) array();
      avail::$config['ARO'] = avail::$config['ARO'].avail::$config['ANS'].avail::SlA;
      avail::$config['ARD'] = avail::$config['ARO'].avail::$config['ARD'];
      avail::$config['ARO'] = avail::$config['ARO'].avail::$name.avail::SlA;
      avail::$dir->root = avail::$config['ARD'];
      avail::$dir->template = avail::$dir->root.avail::$Alert['template'];
    	if (avail::$name) {
        if (file_exists(avail::$config['ARO'])) {
            return avail::$dir->root = avail::$config['ARO'];
        } else {
            self::template('application')->error(array('class'=>avail::$config['ASR'],'root'=>avail::$config['ARO'],'controller'=>avail::$config['ASR']));
        }
    	} else {
        self::template('application')->error(array('class'=>'route','root'=>'config','controller'=>'routeController','name'=>'application'));
    	}
    }
    public function rewrite()
    {
      if (avail::$uri) {
        if ($Id=avail::$uri[0] and isset(self::$rewriteDirectories[$Id])) {
          avail::$responseMethod = self::$rewriteDirectories[$Id];
          avail::$responseType = pathinfo(avail::$uriPath, PATHINFO_EXTENSION);
          return !avail::$responseContext = preg_replace("/$Id/", $this->rewriteEngine($Id).self::$rewriteDirectories[$Id], avail::$uriPath,1);
        }
      }
      return true;
    }
    private function rewriteEngine($Id)
    {
      return (avail::$contents['letIdSRC'] == $Id)?avail::$rootId:avail::$dir->root;
    }
    static function template($Id='error')
    {
      return new self(avail::$Alert[$Id]);
    }
    public function error($Id)
    {
      avail::directory($this->Id)->alertExists();
      if ($this->rewrite()) {
        avail::$responseMethod = avail::$responseType;
        avail::$responseContext[$this->Id]=$Id;
        return true;
      }
    }
    /*
    avail::$database = new \letId\database\request;
    */
    static function database($Id)
    {
      avail::$database = new avail::$classExtension['database'];
      avail::$database->connection($Id);
      if (avail::$database->errorConnection()) {
        return self::template('database')->error(array(
          'env'=>avail::$config['ASE'],
          'root'=>avail::$config['ARO'],
          'msg'=>avail::$databaseConnection->connect_error,
          'code'=>avail::$databaseConnection->connect_errno
          // 'src/style.css'=>array()
        ));
      }
    }
  }
}
