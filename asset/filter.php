<?php
namespace letId\asset;
class filter
{
    protected $Id = '!';
	public function __construct($Id=null)
	{
		if ($Id) {
			$this->Id = $Id;
		}
	}
    public function response($filter,$flags=FILTER_NULL_ON_FAILURE)
	{
        /*
        avail::filter(EMAIL)->response();
        */
        if (is_callable($filter)) {
            return call_user_func($filter, $this->Id, $flags);
        } else {
            return filter_var($this->Id, $filter, $flags);
        }
        // return filter_var($this->Id, $filter,$flags);
        // return array_filter(filter_var_array($this->Id, $args), 'strlen');
	}
    public function email($filter=FILTER_VALIDATE_EMAIL)
	{
        /*
        avail::filter(EMAIL)->email();
        */
        return filter_var($this->Id, $filter);
		// filter_var($this->Id, FILTER_VALIDATE_EMAIL)
	}
	public function url($filter=FILTER_VALIDATE_URL,$flags=FILTER_FLAG_HOST_REQUIRED)
	{
        /*
        avail::filter(EMAIL)->email();
        */
        return filter_var($this->Id, $filter, $flags);
		// filter_var($this->Id, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)
	}
    public function valid_date($date,$check_year=NULL,$format='YYYY-MM-DD'){
		if(strlen($date) >= 8 && strlen($date) <= 10){
			$separator_only = str_replace(array('M','D','Y'),'', $format);
			$separator = $separator_only[0];
			if($separator){
				$regexp = str_replace($separator, "\\" . $separator, $format);
				$regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('YYYY', '\d{4}', $regexp);
				$regexp = str_replace('YY', '\d{2}', $regexp);
				if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
					foreach(array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value){
						if($key == 'YY') $year = '20'.$value;
						if($key == 'YYYY') $year = $value;
						if($key[0] == 'M') $month = $value;
						if($key[0] == 'D') $day = $value;
					}
					if(isset($check_year)){
						if($check_year >= $year){
							if(checkdate($month,$day,$year)) return true;
						}else{
							return false;
						}
					}else{
						if(checkdate($month,$day,$year)) return true;
					}
				}
			}
		}
		return false;
	}
    // public function __call($name, $arguments)
	// {
	// 	return $this;
	// }
}
