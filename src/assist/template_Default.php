<?php
namespace letId\assist
{
  /*
  $template = new template(Id);
  $template->response()

  avail::template(Id)
  avail::template(Id)->response()
  */
  class template_Default
  {
    private $requestContent = array();
    // private $Id=array();
    public function __construct($Id = array())
    {
      $this->Id = $Id;
    }
    public function response()
    {
      if (is_array($this->Id)) {
        return $this->responseEngine($this->Id);
      } else {
        return (string)$this->Id;
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
      if ($this->requestContents($cluster,$v)) {
      	return preg_replace_callback(avail::$config['ATR'],
      		function ($k) use ($v){
            if (isset($k[1])) {
              $kName = $k[1];
              if (isset($v[$kName])) {
                $vk=$v[$kName];
                if (is_array($vk)) {
                  if(count(array_filter(array_keys($vk), 'is_string')) > 0) {
                    if (file_exists($this->requestTemplate($kName))) {
                      return $this->requestEngine($kName, $vk);
                    } else {
                      return $this->responseEngine($vk);
                    }
                  } elseif ($vk) {
                    return implode(array_map(
                      function ($child) use ($k) {
                        // echo $k[1];
                        return $this->requestEngine($k[1], $child);
                      }, $vk
                    ));
                  } elseif ($file=$this->requestChild($kName)) {
                    return file_get_contents($file);
                  } else {
                    return avail::content($kName)->resolve();
                  }
                } else {
                  return avail::language($vk)->get();
                }
        			} elseif (avail::content($kName)->get()) {
                return avail::content($kName)->get();
        			} elseif (avail::language($kName)->has()) {
                return avail::language($kName)->get();
        			} elseif (avail::configuration($kName)->has()) {
                return avail::configuration($kName)->get();
        			} elseif (ctype_upper($kName{0})) {
        				return $kName;
        			} elseif ($file=$this->requestChild($kName)) {
        				// TODO: check filetype(text,image) and process advanced features
        				return file_get_contents($file);
        			} elseif (preg_match('/\s|\r|;|:|#/', $k[0])) {
        				return $k[0];
        			} else {
        				// NOTE: when not match
        			}
            }
      		}, $this->template
      	);
      }
    }
    // TODO: save loaded file temp. for loop!
    private function requestContents($name)
    {
      if($name != strip_tags($name)) {
        return $this->template = $name;
      } elseif(preg_match(avail::$config['ATR'], $name)) {
        return $this->template = $name;
      } elseif (isset($this->requestContent[$name])) {
        return $this->template = $this->requestContent[$name];
      } elseif (file_exists($file = $this->requestTemplate($name))) {
        $this->requestContent[$name] = file_get_contents($file);
        // $this->requestContent[$name] = trim(file_get_contents($file));
        // $this->requestContent[$name] = preg_replace('/[\r\n]+/', '', preg_replace('/[ \t]+/', ' ', file_get_contents($file)));
        // $this->requestContent[$name] = preg_replace('/ \s+/', ' ', preg_replace('/[\r]+/', '', file_get_contents($file)));
        if (avail::$config['resourceMinify']){
          $this->requestContent[$name] = preg_replace('/[\r\n]+/', '', preg_replace('/ \s+/', ' ',$this->requestContent[$name]));
        }
        return $this->template = $this->requestContent[$name];
      }
    }
    private function requestChild($name)
    {
      if (file_exists($file = avail::$dir->template.$name)) {
        return $file;
      }
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
}
