<?php
namespace Letid\Id;
trait Template
{
	public function template($cluster)
    {
		if (is_array($cluster)) {
			return $this->TemplateRequest($cluster);
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
	private function TemplateResponse($cluster)
    {
		if (is_array($cluster)) {
			// array_walk($cluster,
			// 	function ($v, $k) {
			// 		print_r($this->TemplateEngine($k, $v));
			// 	}
			// );
			foreach ($cluster as $k => $v) print_r($this->TemplateEngine($k,$v));
		} else {
			print_r($cluster);
		}
	}
	private function TemplateEngine($cluster, $v)
    {
		if ($template=$this->TemplateExists($cluster)) {
			return preg_replace_callback(Config::$ATR,
				function ($k) use ($v){
					if (is_array($v[$k[1]])) {
						if(count(array_filter(array_keys($v[$k[1]]), 'is_string')) > 0) {
							return $this->TemplateEngine($k[1], $v[$k[1]]);
						} else {
							return implode(
								array_map(
									function ($child) use ($k) {
										return $this->TemplateEngine($k[1], $child);
									}, $v[$k[1]]
								)
							);
						}
					} elseif ($v[$k[1]]) {
						 return $v[$k[1]];
					} elseif (ctype_upper($k[1]{0})) {
						return $k[1];
					} else {
						return $k[0];
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
