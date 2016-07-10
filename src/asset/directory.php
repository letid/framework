<?php
namespace letId\asset;
/*
$directory = new directory(Id);
$directory->get();
$directory->set(Value);
$directory->all();

avail::directory(Id);
avail::directory(Id)->get();
avail::directory(Id)->set(Value);
avail::directory()->all();
*/
class directory
{
    public $Id;
    public function __construct($Id=null)
    {
        $this->Id = $Id;
    }
    public function all()
    {
        return avail::$dir;
    }
    public function has()
    {
        if ($this->Id) {
            return isset(avail::$dir[$this->Id]);
        }
    }
    /**
    * avail::directory()->set(array);
    * avail::directory(string)->set(string);
    */
    public function set($Id=null)
    {
        if (avail::$dir->root) {
            if (is_array($Id)) {
                foreach ($Id as $name => $dir)
                {
                    avail::$dir->{$name} = avail::$dir->root.$dir.avail::SlA;
                }
            } elseif ($this->Id) {
                avail::$dir->{$this->Id} = avail::$dir->root.$Id.avail::SlA;
            }
        }

    }
    public function get()
    {
        if ($this->has()) {
        	return avail::$dir[$this->Id];
        }
    }
    public function existsTemplate()
    {
        avail::$dir->template = avail::$config['ARD'];
        // avail::$dir->template = avail::$config['ARO'].avail::$config['ARD'];
		if (!file_exists(avail::$dir->template.$this->Id.avail::$Extension['template'])) {
			avail::$dir->template = avail::$root.avail::$Alert['dir'];
		}
	}
    public function __toString()
    {
        return $this->get();
    }
}