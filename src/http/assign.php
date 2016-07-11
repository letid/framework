<?php
namespace letId\http;
class assign
{
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
        if (avail::session(avail::$hostname)->has()) {
            return avail::session(avail::$hostname)->get();
        } else if($Id=$this->hostName()) {
            return avail::session(avail::$hostname)->set($Id);
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
    public function dir($configuration)
    {
        avail::$config = array_merge(avail::$config,$configuration);
        avail::$dir = (object) array();
        avail::$config['ARD'] = avail::$config['ARO'].avail::$config['ARD'];
        avail::$config['ARO'] = avail::$config['ARO'].avail::$name.avail::SlA;
        avail::$dir->root = avail::$config['ARD'];
        avail::$dir->template = avail::$dir->root.avail::$Alert['template'];
		if (avail::$name) {
            if (file_exists(avail::$config['ARO'])) {
                return avail::$dir->root = avail::$config['ARO'];
            } else {
                assign::template('application')->error(array('class'=>avail::$config['ASR'],'root'=>avail::$config['ARO'],'controller'=>avail::$config['ASR']));
            }
		} else {
            assign::template('application')->error(array('class'=>'route','root'=>'config','controller'=>'routeController','name'=>'application'));
		}
	}

    public function rewrite($rules)
    {
        if ($uri=avail::$uri) {
            if ($Id=$uri[0]) {
                if (isset($rules[$Id])) {
                    avail::$contextResponse = $rules[$Id];
                    avail::$contextType = pathinfo(avail::$uriPath, PATHINFO_EXTENSION);
                    return !avail::$contextId = preg_replace("/$Id/", avail::$dir->root.$rules[$Id], avail::$uriPath,1);
                }
            }
        }
        return true;
	}
    static function template($Id='error')
    {
        return new self(avail::$Alert[$Id]);
    }
    public function error($Id)
    {
        $src = preg_replace("/[^A-Za-z0-9 ]/", '', avail::$letid['version']);
        avail::directory($this->Id)->alertExists();
        if ($this->rewrite(array($src=>avail::$Alert['resource']))) {
            avail::$content['letIdSRC'] = $src;
            avail::$contextResponse = avail::$contextType;
            avail::$contextId[$this->Id]=$Id;
        }
	}
    public function database($Id)
    {
        avail::$database = new \letId\database\request;
        avail::$database->connection($Id);
        if (avail::$database->errorConnection()) {
            assign::template('database')->error(array(
                'env'=>avail::$config['ASE'],
                'root'=>avail::$config['ARO'],
                'msg'=>avail::$databaseConnection->connect_error,
                'code'=>avail::$databaseConnection->connect_errno
                // 'src/style.css'=>array()
            ));
            return true;
        }
    }
}
