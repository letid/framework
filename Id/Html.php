<?php
namespace Letid\Id;
// NOTE: readme(dosc/html.md)
trait Html
{
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
