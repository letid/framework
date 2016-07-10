<?php
namespace letId\http;
class assign
{
    public function __construct($Id)
	{
		$this->Id = $Id;
	}
    static function request($Id='error')
	{
		return new self(avail::$Alert[$Id]);
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
        avail::$config['ARO'] = avail::$config['ARO'].avail::$name.avail::SlA;
        avail::$config['ARD'] = avail::$config['ARO'].avail::$config['ARD'];
		if (avail::$name && file_exists(avail::$config['ARO'])) {
			return avail::$dir->root = avail::$config['ARO'];
		} else {
            assign::request('verso')->error(array('verso'=>avail::$config['ASR'],'root'=>avail::$config['ARO']));
		}
	}
    public function rewrite($rewrite)
    {
        if ($uri=avail::$uri) {
            if ($Id=$uri[0] and isset($rewrite[$Id])) {
                avail::$contextResponse = $rewrite[$Id];
                avail::$contextType = pathinfo(avail::$uriPath, PATHINFO_EXTENSION);
                return !avail::$context = preg_replace("/$Id/", avail::$dir->root.$rewrite[$Id], avail::$uriPath,1);
            }
        }
        return true;
	}
    public function error($Id)
    {
        avail::$contextResponse = avail::$contextType;
        avail::directory($this->Id)->existsTemplate();
		return avail::$context[$this->Id]=$Id;
	}
    public function database($Id)
    {
        avail::$database = new \letId\database\request;
        avail::$database->connection($Id);
        if (avail::$database->errorConnection()) {
            return assign::request('database')->error(array(
                'env'=>avail::$config['ASE'],
                'root'=>avail::$config['ARO'],
                'msg'=>avail::$databaseConnection->connect_error,
                'code'=>avail::$databaseConnection->connect_errno
                // 'src/style.css'=>array()
            ));
        }
    }
}
