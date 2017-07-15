<?php
namespace letId\assets
{
	/*
	new html(
	    array(
	        'div'=>array(
	            'text'=>'Message',
	            'attr'=>array(
	                'class'=>'active'
	            )
	        )
	    )
	);
	html::request(
	    array(
	        'div'=>array(
	            'text'=>'Message',
	            'attr'=>array(
	                'class'=>'active'
	            )
	        )
	    )
	);
	html::request('div')->response('Ok',array('class'=>'active'));
	html::request('div')->text('Ok')->attr(array('class'=>'active'))->response();
	html::request('div')->text('Ok')->response(array('class'=>'active'));
	html::request('div')->response('Ok');
	
	$html = new html(Id);
	$html->response(Text,Attr);
	
	$html = new html(Id);
	$html->text(Text);
	$html->attr(Attr);
	$html->response(Text,Attr);
	
	html::request(Id);
	html::request(Id)->response(Text,Attr);
	html::request(Id)->text(Text)->attr(Attr)->response(Text,Attr);
	
	avail::html(Id);
	avail::html(Id)->response(Text,Attr);
	avail::html(Id)->text(Text)->attr(Attr)->response(Text,Attr);
	*/
	class html
	{
		protected $text, $attr = array();
		protected $Id;
		public function __construct($Id=null)
		{
			$this->setId($Id);
		}
		public function setId($Id=null)
		{
			if ($Id) {
				$this->Id = $Id;
			}
			return $this;
		}
		static function request($Id=null)
		{
			return new self($Id);
		}
		public function text($value)
		{
			if ($value) {
				$this->text = $value;
			}
			return $this;
		}
		public function attr($value)
		{
			if ($value) {
				$this->attr = $value;
			}
			return $this;
		}
		// public function form($value)
		// {
		// 	return $this;
		// }
		// public function formSelect($value)
		// {
		// 	return $this;
		// }
	
		public function response()
		{
			// $this->text(func_get_args()[0]);
			// $this->attr(func_get_args()[1]);
			return $this->requestEngine($this->Id, $this->text, $this->attr);
		}
		private function requestMap($cluster)
		{
			if (is_array($cluster)) {
				return implode(
					array_map(
						function ($v, $k) {
							if (is_numeric($k) or $k == 'text') {
								return $this->requestMap($v);
							} elseif (!is_array($v)) {
								return $this->requestEngine($k,$v);
							} else {
								$attr=isset($v['attr'])?$v['attr']:array();
								if (isset($v['text'])) {
									return $this->requestEngine($k,$this->requestMap($v['text']),$attr);
								} else {
									return $this->requestEngine($k,null,$attr);
								}
								// return $this->requestEngine($k,$this->requestMap($v['text']),$attr);
							}
						}, $cluster, array_keys($cluster)
					)
				);
			} else {
				return $cluster;
			}
		}
		private function requestEngine($tag, $text, $attr=array())
		{
			$h = array();
			// '&$v,$k','$v=is_bool($v)?$k:$v; $v=is_array($v)?implode($v," "):$v; $v=" $k=\"$v\"";'
			// array_walk($attr, create_function('&$v,$k','$v=is_array($v)?implode($v," "):$v; $v=" $k=\"$v\""; '));
			if (is_array($attr)) {
				array_walk($attr, function(&$v, $k) {
					if (is_array($v)) $v = implode($v,($k=='href'?'/':' '));
					$v = is_numeric($k)?sprintf(' %s', $v):sprintf(' %s="%s"', $k, $v);
				});
			}
			$h['attr'] = is_array($attr)?implode($attr):$attr;
			$h['tag'] = $tag;
			$h['text'] = $text;
			return preg_replace_callback(avail::AHR,function($i) use ($h) {
					// return $h[$i[1]];
					if (is_scalar($h[$i[1]])){
						return $h[$i[1]];
					} else {
						print_r($h[$i[1]]);
					}
				}, avail::$AHE[$tag?in_array($tag, avail::$AHS)?0:1:2]
			);
		}
		public function __toString()
		{
			return $this->requestMap($this->Id);
		}
	}
}