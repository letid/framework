<?php
namespace letId\asset;
/*
$template = new template(Id);
$template->response()

template::request(Id)
template::request(Id)->response()

avail::template(Id)
avail::template(Id)->response()
*/
class template
{
    public $requestContent = array();
    public function __construct($Id=null)
	{
        $this->Id = $Id;
	}
    public function response()
    {
		if (is_array($this->Id)) {
            return $this->responseEngine($this->Id);
		} else {
			return $this->Id;
		}
	}
    private function responseEngine($Id)
    {
        return implode(
            array_map(
                function ($v, $k) {
                    return $this->requestEngine($k, $v);
                }, $Id, array_keys($Id)
            )
        );
    }
	private function requestEngine($cluster, $v)
    {
		if ($this->requestContents($cluster)) {
			return preg_replace_callback(avail::$config['ATR'],
				function ($k) use ($v){
					if (isset($v[$k[1]])) {
                        $vk=$v[$k[1]];
                        if (is_array($vk)) {
                            if(count(array_filter(array_keys($vk), 'is_string')) > 0) {
                                if (file_exists($this->requestTemplate($k[1]))) {
                                    return $this->requestEngine($k[1], $vk);
                                } else {
                                    return $this->responseEngine($vk);
                                }
    						} elseif ($vk) {
                                return implode(
    								array_map(
    									function ($child) use ($k) {
    										return $this->requestEngine($k[1], $child);
    									}, $vk
    								)
    							);
    						} elseif ($file=$this->requestChild($k[1])) {
    							return file_get_contents($file);
                            } else {
                                return avail::content($k[1])->statics();
    						}
                        } else {
                            return avail::language($vk)->get();
                        }
					} elseif ($resolveContent = avail::content($k[1])->resolve()) {
                        return $resolveContent;
					} elseif (avail::language($k[1])->has()) {
                        return avail::language($k[1])->get();
					} elseif (ctype_upper($k[1]{0})) {
						return $k[1];
					} elseif ($file=$this->requestChild($k[1])) {
						// TODO: check filetype(text,image) and process advanced features
						return file_get_contents($file);
					} elseif (preg_match('/\s|\r|;|:|#/', $k[0])) {
						return $k[0];
					} else {
						// NOTE: when not match
					}
				}, $this->template
			);
		}
	}
	public function requestContents($name)
    {
        // TODO: save loaded file temp. for loop!
		if (isset($this->requestContent[$name])) {
            return $this->template = $this->requestContent[$name];
        } elseif (file_exists($file = $this->requestTemplate($name))) {
			$this->requestContent[$name] = file_get_contents($file);
            return $this->template = $this->requestContent[$name];
		}
	}
	private function requestChild($name)
    {
		if (file_exists($file = avail::$dir->template.$name)) return $file;
	}
	private function requestTemplate($fileName)
    {
		return avail::$dir->template.$fileName.avail::SlP.avail::$Extension['template'];
	}
    public function __toString()
	{
	    return $this->response();
	}
}