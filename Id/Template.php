<?php
namespace Letid\Id;
trait Template
{
	public function template($cluster)
    {
		// TODO: header
		// if (is_array($cluster)) {
		// 	return $this->TemplateRequest($cluster);
		// }
		if (is_array($cluster)) {
			return $this->TemplateRequest($cluster);
		} else {
			return $cluster;
		}
	}
	private function TemplateRequest($cluster)
    {
		return implode(
			array_map(
				function ($v, $k) {
					return $this->TemplateEngine($k, $v);
				}, $cluster, array_keys($cluster)
			)
		);
	}
	/*
	private function TemplateResponse($cluster)
    {
		if (is_array($cluster)) {
			// array_walk($cluster,
			// 	function ($v, $k) {
			// 		print_r($this->TemplateEngine($k, $v));
			// 	}
			// );
			// foreach ($cluster as $k => $v) print_r($this->TemplateEngine($k,$v));
			print_r($this->TemplateRequest($cluster));
		} else {
			print_r($cluster);
		}
	}
	*/
	private function TemplateEngine($cluster, $v)
    {
		if ($template=$this->TemplateExists($cluster)) {
			return preg_replace_callback(Config::$ATR,
				function ($k) use ($v){
					if (is_array($v[$k[1]])) {

						if(count(array_filter(array_keys($v[$k[1]]), 'is_string')) > 0) {
							return $this->TemplateEngine($k[1], $v[$k[1]]);
						} else if ($v[$k[1]]) {
							return implode(
								array_map(
									function ($child) use ($k) {
										return $this->TemplateEngine($k[1], $child);
									}, $v[$k[1]]
								)
							);
						} else {
							// NOTE: check
							// return $this->TemplateEngine($k[1]);
						}
					} elseif ($v[$k[1]]) {
 						 return $v[$k[1]];
					} elseif (is_scalar($this->{$k[1]})) {
						 return $this->{$k[1]};
					} elseif (isset(Config::$data[$k[1]])) {
						// NOTE: if data has
						 return Config::$data[$k[1]];
					} elseif (isset(Config::$language[$k[1]])) {
						// NOTE: if lang has
						 return Config::$language[$k[1]];
					} elseif (ctype_upper($k[1]{0})) {
						// NOTE: when upper case
						return $k[1];
					} else {
						// NOTE: when not match
						// return $k[0];
					}
				}, $template
			);
		}
	}
	private function TemplateExists($name)
    {
		if (file_exists($file = $this->TemplateFile($name))) {
			return file_get_contents($file);
		} else {
			// TODO: when no template found
		}
	}
	private function TemplateFile($fileName)
    {
		return Config::$dir->template.$fileName.Config::$Extension['template'];
	}
}
