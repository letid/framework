<?php
namespace letId\assets
{
  class arrays
  {
    protected $Id;
    public $result;
    public function __construct($Id=null)
    {
      $this->Id = $Id;
    }
    /**
    * avail::arrays(array('a','b','c','d'))->to_sentence();
    */
    public function to_sentence($y=', ',$z=' & ')
    {
      return implode($z, array_filter(array_merge(array(join($y, array_slice($this->Id, 0, -1))), array_slice($this->Id, -1)), 'strlen'));
    }
    /**
    * avail::arrays(array('a','b','c','d'))->key_join_value();
    */
    public function key_join_value($y=', ',$z="%s='%s'")
    {
      return implode($y, array_map(
        function ($v, $k) use($z) {
          return sprintf($z, $k, $v);
        }, $this->Id, array_keys($this->Id)
      ));
    }
    /**
    * @var avail::arrays(1/2)->key_exists(needle,haystack);
    * NOTE: not in use
    */
    // public function key_exists($needle, $haystack) {
  	// 	foreach($haystack as $key => $value) {
    //     if ($needle == $key) {
    //       return ($this->Id==1)?NULL:$value;
    //     } elseif (is_array($value)) {
    //       if ($this->key_exists($needle,$value ) == true)
    //        return ($this->Id==2)?$value[$needle]:NULL;
    //       else continue;
    //     }
    //   }
  	// 	return false;
  	// }
    /**
    * avail::arrays('menu')->page_search(needle,haystack);
    * NOTE: not in use
    */
  	// public function page_search($needle, $haystack) {
    //   // $this->Id='menu';
  	// 	foreach($haystack as $key => $value) {
    //     if ($needle == $key and $haystack[$this->Id]) {
    //       return $needle;
    //     } elseif (is_array($value) and $this->page_search($needle,$value)) {
    //       if ($this->page_search($needle,$value))
    //         return $value[$needle];
    //       else continue;
    //     }
    //   }
  	// 	return false;
  	// }
    /**
    * avail::arrays(1/2)->value_exists(needle,haystack);
    * NOTE: not in use
    */
  	// public function value_exists($needle, $haystack) {
  	// 	foreach($haystack as $key => $value) {
    //     if ($needle == $value) {
    //       return ($this->Id==1)?NULL:$key;
    //     } else if (is_array($value)) {
    //       if ($this->value_exists($needle,$value) == true)
    //        return ($this->Id==2)?array_search($needle,$value):NULL;
    //       else continue;
    //     }
    //   }
  	// 	return false;
  	// }
    /**
    * avail::arrays($needle)->search_value($haystack);
    * avail::arrays($needle)->search_value($haystack)->result;
    */
  	public function search_value($haystack,$case=null)
    {
      // var_dump(array_filter($arr, function($v, $k) {
      //     return $k == 'b' || $v == 4;
      // }, ARRAY_FILTER_USE_BOTH));
  		foreach($haystack as $k => $v)
        if (is_array($v)) {
          if ($this->search_value($v,$case)->result) break; else continue;
        } elseif ($this->same($v,$case)) {
          $this->result=array($k=>$v);break;
        } else {
          continue;
        }
  		return $this;
  	}
  	// public function search_value_test($haystack,$case)
    // {
    //   return array_filter($haystack, function($v)use($case){
    //     if (is_array($v)) {
    //       return $this->search_value_test($v,$case);
    //     } elseif ($this->same($v,$case)) {
    //       return true;
    //     }
    //   });
  	// }
    /**
    * avail::arrays($needle)->search_key($haystack);
    * avail::arrays($needle)->search_key($haystack)->result;
    */
  	public function search_key($haystack,$case=null)
    {
      // $this->result=array_filter($haystack, function($k) use($case) {
      //     return $this->same($k,$case);
      // }, ARRAY_FILTER_USE_KEY);
      // print_r($this->result);
  		foreach($haystack as $k => $v)
        if (is_array($v)) {
          if ($this->search_key($v)->result) break; else continue;
        } elseif ($this->same($k,$case)) {
          $this->result=array($k=>$v);break;
        } else {
          continue;
        }
  		return $this;
  	}
    /**
    * avail::arrays($key)->same($value,$case);
    * @param $case true/false;
    * NOTE: use in search_value, search_key
    */
    //
    public function same($value,$case=null)
    {
      return ($case)? $this->Id == $value:strtolower($this->Id) == strtolower($value);
    }
    /**
    * avail::arrays($needle)->search_key($haystack)->get_value();
    * avail::arrays($needle)->search_value($haystack)->get_value();
    */
  	public function get_value($i) {
  		if ($this->result) return $this->resultEngine(array_values($this->result),$i);
  	}
    /**
    * avail::arrays($needle)->search_key($haystack)->get_key();
    * avail::arrays($needle)->search_value($haystack)->get_key();
    */
  	public function get_key($i) {
  		if ($this->result) return $this->resultEngine(array_keys($this->result),$i);
  	}
    /**
    * NOTE: use in get_value, get_key
    */
    private function resultEngine($value,$key)
    {
      return is_numeric($key) && count($value) >= $key?$value[$key]: $value;
    }
    public function __toString()
    {
      return $this->result;
    }
  }
}
