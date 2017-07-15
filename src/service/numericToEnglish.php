<?php
/*
USAGE
=======
$o = new numericToEnglish('4333');
$o->request();
*/
namespace letId\service
{
	class numericToEnglish
	{
		private $_original = 0;
		private $_parsed_number_text = '';
		private $config = array(
			'E_sn'=>array(
				1 => 'One', 2 => 'Two', 3 => 'Three', 
				4 => 'Four', 5 => 'Five', 6 => 'Six', 
				7 => 'Seven', 8 => 'Eight', 9 =>'Nine'
			),
			'E_tn'=>array(
				0 => 'Ten', 1 => 'Eleven', 2 => 'Twelve', 
				3 => 'Thirteen', 4 => 'Fourteen', 5 => 'Fifteen', 
				6 => 'Sixteen', 7 => 'Seventeen', 8 => 'Eighteen', 9 => 'Nineteen'
			),
			'E_tns'=>array(
				2 => 'Twenty', 3 => 'Thirty', 
				4 => 'Forty', 5 => 'Fifty', 6 => 'Sixty', 7 => 'Seventy', 
				8 => 'Eighty', 9 => 'Ninety'
			),
			'E_cn'=> array(
				1 => 'Thousand', 2 => 'Million', 3 => 'Billion', 4 => 'Trillion', 
				5 => 'Quadrillion', 6 => 'Quintrillion', 7 => 'Sextillion', 
				8 => 'Septillion', 9 => 'Octillion', 
				9 => 'Nonillion', 9 => 'Decillion'
			)
		);
		function __construct($number)
		{
			$this->q = trim($number);
		}
		public function request($new_number = NULL)
		{
			if($new_number !== NULL) {
					$this->q = trim($new_number);
			}
			if($this->q == 0) return 'Zero';
		
			$num = str_split($this->q, 1);
			krsort($num);
			$chunks = array_chunk($num, 3);
			krsort($chunks);
		
			$final_num = array();
			foreach ($chunks as $k => $v){
				ksort($v);
				$temp = trim($this->_parse_num(implode('', $v)));
				if($temp != ''){
					$final_num[$k] = $temp;
					if (isset($this->config['E_cn'][$k]) && $this->config['E_cn'][$k] != '') {
						$final_num[$k] .= ' '.$this->config['E_cn'][$k];
					}
				}
			}
			$this->_parsed_number_text = implode(', ', $final_num);
			return $this->_parsed_number_text;
		}
		private function _parse_num($num)
		{
			$temp = array();
			if (isset($num[2])) {
				if (isset($this->config['E_sn'][$num[2]])) {
					$temp['h'] = $this->config['E_sn'][$num[2]].' Hundred';
				}
			}
		
			if (isset($num[1])) {
				if ($num[1] == 1) {
					$temp['t'] = $this->config['E_tn'][$num[0]];
				} else {
					if (isset($this->config['E_tns'][$num[1]])) {
						$temp['t'] = $this->config['E_tns'][$num[1]];
					}
				}
			}
		
			if (!isset($num[1]) || $num[1] != 1) {
				if (isset($this->config['E_sn'][$num[0]])) {
					if (isset($temp['t'])) {
						$temp['t'] .= ' '.$this->config['E_sn'][$num[0]];
					} else {
						$temp['u'] = $this->config['E_sn'][$num[0]];
					}
				}
			}
			return implode(' and ', $temp);
		}
		public function __toString()
		{
			return $this->_parsed_number_text;
		}
  }
}
?>