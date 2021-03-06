<?php
namespace letId\assist
{
  /*
  $template = new template(Id);
  $template->response()

  avail::template(Id)
  avail::template(Id)->response()
  */
  class template
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
    private function requestMapping($name,$v)
    {
      return implode(array_map(
        function ($child) use($name) {
          return $this->requestEngine($name, $child);
        }, $v
      ));
    }
    private function responseEngine($Id)
    {
      return implode(
        array_map(
          function ($v, $k) {
            if(count(array_filter(array_keys($v), 'is_numeric')) > 0) {
              return $this->requestMapping($k,$v);
            } else {
              return $this->requestEngine($k, $v);
            }
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
              $kId = $k[1];
              if (isset($v[$kId])) {
                $vId=$v[$kId];
                if (is_array($vId)) {
                  if(count(array_filter(array_keys($vId), 'is_string')) > 0) {
                    if (file_exists($this->requestTemplate($kId))) {
                      return $this->requestEngine($kId, $vId);
                    } else {
                      return $this->responseEngine($vId);
                    }
                  } elseif ($vId) {
                    return $this->requestMapping($k[1],$vId);
                  } elseif ($file=$this->requestChild($kId)) {
                    return file_get_contents($file);
                  } else {
                    return avail::content($kId)->resolve();
                  }
                } else {
                  return avail::language($vId)->get();
                }
              } elseif (avail::content($kId)->get()) {
                return avail::content($kId)->get();
              } elseif (avail::language($kId)->has()) {
                return avail::language($kId)->get();
              } elseif (avail::configuration($kId)->has()) {
                return avail::configuration($kId)->get();
              } elseif (ctype_upper($kId{0})) {
                return $kId;
              } elseif ($file=$this->requestChild($kId)) {
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
      $file = avail::$dir->template.$name;
      if (file_exists($file)) {
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
