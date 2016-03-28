<?php
namespace Letid\Id;
trait Html
{
	/*
	$this->html('tag:string','text:string', 'attr:array')
	$this->HtmlEngine('tag:string','text:string', 'attr:array')
	$this->HtmlEngine('div','Space problem');
	$this->HtmlEngine('span','Ok',array('class'=>'testing'));
	$this->HtmlEngine('hr');

	array('<tag attr/>','<tag attr>text</tag>','text') ---> '/\w+/'
	*/
	public function html($html)
	{
		return $this->HtmlRequest($html);
	}
	private function HtmlRequest($cluster)
	{
		if (is_array($cluster)) {
			return implode(
				array_map(
					function ($v, $k) {
						if (is_numeric($k) or $k == 'text') {
							return $this->HtmlRequest($v);
						} elseif (!is_array($v)) {
							return $this->HtmlEngine($k,$v);
						} else {
							return $this->HtmlEngine($k,$this->HtmlRequest($v['text']),$v['attr']);
						}
					}, $cluster, array_keys($cluster)
				)
			);
		} else {
			return $cluster;
		}
	}
	private function HtmlResponse($tag, $text, $attr=array())
	{
		return $this->HtmlEngine($tag,$text,$attr);
	}
	private function HtmlEngine($tag, $text, $attr=array())
	{
		$Obj = (object) array();
		array_walk($attr, create_function('&$v,$k','$v=is_bool($v)?$k:$v; $v=is_array($v)?implode($v," "):$v; $v=" $k=\"$v\"";'));
		$Obj->attr = implode($attr);
		$Obj->tag = $tag;
		$Obj->text = $text;
		return preg_replace_callback(Config::$AHR,function($i) use ($Obj) {
				return $Obj->{$i[1]};
			}, Config::$AHE[$tag?in_array($tag, Config::$AHS)?0:1:2]
		);
	}
}
/*
array(
	'ol'=>array(
		'text'=>array(
			array(
				'li'=>array(
					'text'=>array(
						'strong'=>'isStrong', 'em'=>'isEm'
					),
					'attr'=>array(
						'class'=>'firstClass'
					)
				)
			),
			array(
				'li'=>array(
					'text'=>'Second',
					'attr'=>array(
						'class'=>'secondClass'
					)
				)
			)
		),
		'attr'=>array(
			'class'=>'currentClass'
		)
	)
);
array(
	'div'=>array(
		'text'=>array(
			'a'=>array(
				'text'=>array(
					'span'=>'isSpan', 'em'=>'isEm'
				),
				'attr'=>array(
					'href'=>'#'
				)
			)
		),
		'attr'=>array(
			'class'=>'current'
		)
	)
);
*/
