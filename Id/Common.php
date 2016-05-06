<?php
namespace Letid\Id;
trait Common
{
	use Utilities, Template, Html;
	public function config($y,$v='')
	{
		if ($y) {
			if (is_scalar($y)) {
				if ($v) {
					return Config::$list[$y] = $v;
				} elseif (isset(Config::$list[$y])) {
					return Config::$list[$y];
				}
			} else {
				return Config::$list;
			}
		}
	}
	public function data($y,$v='')
	{
		if ($y) {
			if ($v) {
				return Config::$data[$y]=$v;
			} elseif (isset(Config::$data[$y])) {
				return Config::$data[$y];
			}
		}
	}
	public function lang($y,$v=array())
	{
		if ($y) {
			if ($v) {
				return preg_replace_callback(Config::$ATR,
					function ($k) use ($v) {
						if (isset($v[$k[1]])) {
							if (is_array($v[$k[1]])) {
								return implode(', ',$v[$k[1]]);
							} else {
								 return $v[$k[1]];
							}
						} elseif (is_scalar($this->{$k[1]})) {
							 return $this->{$k[1]};
						} elseif (isset(Config::$language[$k[1]])) {
							// NOTE: if lang has
							 return Config::$language[$k[1]];
						} elseif (ctype_upper($k[1]{0})) {
							// NOTE: when upper case
							return $k[1];
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
	public function error_get_last()
	{
		if ($message = error_get_last()) {
			$this->error_get_last = $this->template(
				array('error_get_last'=>$message)
			);
		}
	}
}