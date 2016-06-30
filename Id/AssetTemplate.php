<?php
namespace Letid\Id;
/*
$Template = new Template(Id);
$Template->response()

Template::request(Id)
Template::request(Id)->response()

Application::template(Id)
Application::template(Id)->response()
*/
class AssetTemplate extends AssetId
{
    public function response()
    {
		if (is_array($this->Id)) {
            return implode(
    			array_map(
    				function ($v, $k) {
    					return $this->Engine($k, $v);
    				}, $this->Id, array_keys($this->Id)
    			)
    		);
		} else {
			return $this->Id;
		}
	}
	private function Engine($cluster, $v)
    {
		if ($template=$this->Exists($cluster)) {
			return preg_replace_callback(Application::$config['ATR'],
				function ($k) use ($v){
					if (is_array($v[$k[1]])) {
						if(count(array_filter(array_keys($v[$k[1]]), 'is_string')) > 0) {
							return $this->Engine($k[1], $v[$k[1]]);
						} else if ($v[$k[1]]) {
							return implode(
								array_map(
									function ($child) use ($k) {
										return $this->Engine($k[1], $child);
									}, $v[$k[1]]
								)
							);
						} else if ($file=$this->Children($k[1])) {
							return file_get_contents($file);
                        } elseif ($varName=Application::content($k[1])->statics()) {
                            return $varName;
						} else {
							// NOTE: check
						}
					} elseif ($v[$k[1]]) {
                        return Application::language($v[$k[1]])->get();
					} elseif ($resolveContent = Application::content($k[1])->resolve()) {
                        return $resolveContent;
					} elseif (Application::language($k[1])->has()) {
                        return Application::language($k[1])->get();
					} elseif (ctype_upper($k[1]{0})) {
						return $k[1];
					} else if ($file=$this->Children($k[1])) {
						// TODO: check filetype(text,image) and process advanced features
						return file_get_contents($file);
					} elseif (preg_match('/\s|\r|;|:|#/', $k[0])) {
						return $k[0];
					} else {
						// NOTE: when not match
					}
				}, $template
			);
		}

	}
	private function Exists($name)
    {
		if (file_exists($file = $this->File($name))) {
			return file_get_contents($file);
		} else {
			// TODO: when no template found
		}
	}
	private function Children($name)
    {
		if (file_exists($file = Application::$dir->template.$name)) return $file;
	}
	private function File($fileName)
    {
		return Application::$dir->template.$fileName.Application::SlP.Application::$Extension['template'];
	}
    public function __toString()
	{
	    return $this->response();
	}
}