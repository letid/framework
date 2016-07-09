<?php
namespace letId\asset;
/*
$directory = new directory(Id);
$directory->get();
$directory->set(Value);
$directory->all();

directory::request(Id)
directory::request(Id)->get()
directory::request(Id)->set(Value)
directory::request()->all()

avail::directory(Id);
avail::directory(Id)->get();
avail::directory(Id)->set(Value);
avail::directory()->all();
*/
class directory extends avail
{
    public function all()
    {
        return self::$dir;
    }
    public function has()
    {
        if ($this->Id) {
            return isset(self::$dir[$this->Id]);
        }
    }
    /**
    * avail::directory()->set(array);
    * avail::directory(string)->set(string);
    */
    public function set($Id=null)
    {
        if (self::$dir->root) {
            if (is_array($Id)) {
                foreach ($Id as $name => $dir)
                {
                    self::$dir->{$name} = self::$dir->root.$dir.static::SlA;
                }
            } elseif ($this->Id) {
                self::$dir->{$this->Id} = self::$dir->root.$Id.static::SlA;
            }
        }

    }
    public function get()
    {
        if ($this->has()) {
        	return self::$dir[$this->Id];
        }
    }
    public function existsTemplate()
    {
        self::$dir->template = self::$config['ARO'].self::$config['ARD'];
		if (!file_exists(self::$dir->template.$this->Id.self::$Extension['template'])) {
			self::$dir->template = self::$root.self::$Alert['dir'];
		}
	}
    public function __toString()
    {
        return $this->get();
    }
}