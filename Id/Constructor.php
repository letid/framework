<?php
// namespace Letid\Id;
trait Constructor
{
	public function __construct()
    {
        // constructor!
    }
	public function __set($name, $value)
	{
		$this->{$name} = $value;
	}
	public function __get($name)
	{
		if(property_exists($this, $name)) {
			return $this->{$name};
		}
	}
    public function __call($name, $arguments)
	{
		// if(method_exists($this, $name) == false) return $this;
    }
	// public function __toString()
	// {
	// }
	public function config($k,$v='')
	{
		if (is_bool($k)) {
			return Config::$list;
		} else if ($k) {
			if ($v) {
				return Config::$list[$k] = $v;
			} else {
				return Config::$list[$k];
			}
		}
	}
	public function lang($y,$v=array())
	{
		if ($y) {
			if ($v) {
				return preg_replace_callback(Config::$ATR,
					function ($k) use ($v){
						if (is_array($v[$k[1]])) {
							return implode(', ',$v[$k[1]]);
						} elseif ($v[$k[1]]) {
							 return $v[$k[1]];
						} elseif (is_string($this->{$k[1]})) {
							 return $this->{$k[1]};
						} elseif (isset(Config::$language[$k[1]])) {
							// NOTE: if lang has
							 return Config::$language[$k[1]];
						} elseif (ctype_upper($k[1]{0})) {
							// NOTE: when upper case
							return $k[1];
						} else {
						}
					}, $this->lang($y)
				);
			} else {
				if (isset(Config::$language[$y])) {
					return Config::$language[$y];
				} else {
					return $y;
				}
			}
		}
	}
}
