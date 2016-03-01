<?php
namespace Letid\Id;
trait Template
{
	private function delete_config()
    {
		// print_r($this);
		return 'delete_config';
	}
	public function template()
    {
		$this->templateResponsive(func_get_args()[0]);
	}
	private function templateRequestive()
    {
		// TODO: check file_exists if necessary!
	}
	private function templateResponsive($Content)
    {
		if (is_array($Content)) {
			foreach ($Content as $k => $v) print_r($this->templateEngine($k,$v));
		} else {
			print_r($Content);
		}
	}
	private function templateEngine($tpl, $v)
    {
		if ($template=$this->templateExists($tpl)) {
			return preg_replace_callback(static::ATR,
				function ($k) use ($v){
					if (is_array($v[$k[1]])) {
						if(count(array_filter(array_keys($v[$k[1]]), 'is_string')) > 0) {
							return $this->templateEngine($k[1], $v[$k[1]]);
						} else {
							return implode(
								array_map(
									function ($child) use ($k) {
										return $this->templateEngine($k[1], $child);
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
	private function templateExists($fileName)
    {
		if (file_exists(self::$scoreVar['directory']['template'].$fileName.File::$Extension['template'])) {
			return file_get_contents(self::$scoreVar['directory']['template'].$fileName.File::$Extension['template']);
		}
	}
}
