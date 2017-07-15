<?php 
//ini_set( "display_errors", 0);
/*
$o = new numericToMyanmar('4333');
$o->request();

*/
namespace letId\service
{
	class numericToMyanmar
	{
		private $m = array(
			'M_sn'=> array(
				0 => 'သုည', 1 => 'တစ်', 2 => 'နှစ်', 3 => 'သုံး', 4 => 'လေး', 
				5 => 'ငါး', 6 => 'ခြောက်', 7 => 'ခုနစ်', 8 => 'ရှစ်', 9 =>'ကိုး'
			),
			'M_tn'=>array(
				1 => 'နှင့်', // and
				2 => 'ဆယ်', //ten
				3 => 'ရာ', //hundred
				4 => 'ထောင်', //Thousand
				5 => 'သောင်း', 
				6 => 'သိန်း', 
				7 => 'သန်း', 
				8 => 'ကု​ဋေ​'
			),
			/*
			100304444
			count chars
			divided by last zero
			*/
			
			'M_tns'=>array(
				1 => 'နှင့်', // and
				2 => 'ဆယ်', //ten
				3 => 'ရာ', //hundred
				4 => 'ထောင်', //Thousand
				5 => 'သောင်း', // 10 Thousand 
				6 => 'သိန်း', // 100 Thousand
				7 => 'သန်း', // Million
				8 => 'ကု​ဋေ​' // 10 Million
			),
			'M_w'=>array(
				0 => "ဝ", 1 => "၁", 2 => "၂", 3 => "၃", 4 => "၄", 5 => "၅", 6 => "၆", 7 => "၇", 8 => "၈", 9 =>"၉"
			)
		);
		function __construct($q) {
			$this->q = $q;
		}
		public function request()
		{
			$number = number_format($this->q, 0, NULL, '');
			$count = strlen($number);
			$result['ne'] = $this->ne($number);
			if ($count <= 8) {
				$result['d'] = "less than 8";
				$result['de'] = $this->converter($number);
				$result['th'] = $this->test_converter($number,$count,6);
				$result['ta'] = $this->th_converter($number,$count,7);
				$result['kd'] = $this->th_converter($number,$count,8);
			} elseif ($count <= 13) {
				$result['th'] = $this->test_converter($number,$count,6);
				$result['ta'] = $this->test_converter($number,$count,7);
				$result['kd'] = $this->test_converter($number,$count,8);
			} else {
				$result['th'] = $this->test_converter($number,$count,6);
				$result['ta'] = $this->test_converter($number,$count,7);
				$result['kd'] = $this->test_converter($number,$count,8);
			}
			return $result;
		}
		private function test_converter($q,$total,$limit)
		{
			$word = NULL;
			$number_array = str_split($q, $limit);
			/*
			$sum = ($total - $limit) + 1;
			$sum_first = ($sum > 8)?8:$sum;
			$sum_last = $total - $sum_first;
			*/
		
			$sum = ($total - $limit) + 1;
			$sum_first = ($sum > 7)?6:$sum;
		
			if ($sum_first > 1)  {
				$num_first = substr($q, 0,$sum_first);
				// $num_last = substr($q, -($sum_last));
				$num_last = substr($q, - isset($sum_last)?$sum_last:0);
				
				$word_first = $this->converter($num_first,false,false);
				
				$number_array = str_split($num_last, $limit);
				foreach ($number_array as $v) {
					$words_last[] = $this->converter($v,false,false);
				}
				$word_last = implode("",$words_last);
					if ($word_last) {
						$result = $word_first.$this->m['M_w'][2];
					} else {
						$result = $word_first;
					}
				$word = $this->m['M_tns'][$limit].$this->m['M_w'][1].' '.$result;
				//$result = array("first"=>$word_first,"last"=>$word_last);
			}
			return $word;
		}
		private function ne($q)
		{
			$number_array = str_split($q);
			foreach($number_array as $k => $v) {
				$r[] = $this->m['M_w'][$v];
			}
			return implode($r);
		
			//return str_replace($number_array,$this->m['M_n'],$q);
			}
		private function ta_converter($q,$total,$limit)
		{
			// 14 - 6 = 8
			// 6 - 2 = 4
			// 14 - 4 = 
			//$limits = $limit + 1;
			$first = $this->converter(substr($q, 0,$limit),false,false);
			return $this->m['M_tns'][$limit].$this->m['M_w'][1].' '.$first;
			/*
			if ($limit < $total) {
				$num = $limit + 1;
				//$num_last = $total - $num;
				$first = $this->converter(substr($q, 0,$limit),false,false);
				//$last = $this->converter(substr($q, -($num_last)));
				$last = NULL;
				if ($last) {
					return $first.$this->m['M_tns'][$limit].', '.$last;
				} else {
					return $this->m['M_tns'][$limit].$this->m['M_w'][1].' '.$first;//$this->m['M_w'][2];
				}
			}
			*/
		}
		private function th_converter($q,$total,$limit)
		{
			$limits = $limit - 1;
			if ($limit < $total) {
				$num = $total - $limits;
				$num_last = $total - $num;
				$first = $this->converter(substr($q, 0, $num),false,false);
				$last = $this->converter(substr($q, -($num_last)));
				if ($last) {
					return $first.$this->m['M_tns'][$limit].', '.$last;
				} else {
					return $this->m['M_tns'][$limit].$this->m['M_w'][1].' '.$first;//$this->m['M_w'][2];
				}
			}
		}
		private function converter($q,$anding=true,$ending=true)
		{
			$n = str_split($q);
			$counts = count($n);
		
			$last = $counts - 1 ;
			$result = array();
		
			foreach($n as $k => $v) {
				$vd = ($v==0)?1:NULL;
				$vk = $counts - $k;
				if ($k == "0") {
					//FIRST
					if ($anding == true) {
						$n_r = $this->m['M_sn'][$v].$this->m['M_tns'][$vk];	
					} else {
						$n_r = $this->m['M_sn'][$v].$this->m['M_tns'][$vk];	
					}
				} else if ($k == $last) {
					//END
					if ($ending == true) {
						$n_r = $this->m['M_tns'][$vk].' '.$this->m['M_sn'][$v].'....';	
					} else {
						$n_r = $this->m['M_sn'][$v];	
					}
				} else {
					$n_r = $this->m['M_sn'][$v].$this->m['M_tns'][$vk];
				}
				if ($v!=0) {
					$result[] = $n_r;
				}
			}
			return implode(" ", $result);
		}
  }
}
?>