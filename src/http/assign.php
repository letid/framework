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
            foreach ($this->Id as $Name => $Regex)
            {
                if ($Regex && $this->hostEngine(is_array($Regex)?$Regex:array($Regex))) {
                    return $Name; break;
                }
            }
        }
    }
    private function hostEngine($Regex)
    {
        foreach ($Regex as $Name)
        {
            if (preg_match("/$Name/", avail::$hostname)) {
                return true;
            }
        }
    }
    public function dir($configuration)
    {
        $this->con($configuration);
        avail::$dir = (object) array();
        $dirRoot = avail::$config['ARO'].avail::$name.avail::SlA;
		if (avail::$name && file_exists(avail::$config['ARO'].avail::$name.avail::SlB)) {
			return avail::$dir->root = $dirRoot;
		} else {
            assign::request('configuration')->error(array('verso'=>avail::$config['ASR'],'root'=>$dirRoot));
		}
	}
    public function rewrite($uri,$rewrite)
    {
        if ($uri) {
            if (isset($rewrite[$uri[0]])) {
                avail::$contextResponse = $rewrite[$uri[0]];
                avail::$contextType = pathinfo(avail::$uriPath, PATHINFO_EXTENSION);
                return !avail::$context = preg_replace("/$uri[0]/", avail::$dir->root.$rewrite[$uri[0]], avail::$uriPath,1);
            }
        }
        return true;
	}
    public function con($Id)
    {
        avail::$config = array_merge(avail::$config,$Id);
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
                'root'=>avail::$config['ARO'].avail::$name.avail::SlA,
                'msg'=>avail::$databaseConnection->connect_error,
                'code'=>avail::$databaseConnection->connect_errno
                // 'src/style.css'=>array()
            ));
        }
    }
}
