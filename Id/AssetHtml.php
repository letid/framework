<?php
namespace Letid\Id;
/*
new Html(
    array(
        'div'=>array(
            'text'=>'Message',
            'attr'=>array(
                'class'=>'active'
            )
        )
    )
);
Html::request(
    array(
        'div'=>array(
            'text'=>'Message',
            'attr'=>array(
                'class'=>'active'
            )
        )
    )
);
Html::request('div')->response('Ok',array('class'=>'active'));
Html::request('div')->text('Ok')->attr(array('class'=>'active'))->response();
Html::request('div')->text('Ok')->response(array('class'=>'active'));
Html::request('div')->response('Ok');

$Html = new Html(Id);
$Html->response(Text,Attr);

$Html = new Html(Id);
$Html->text(Text);
$Html->attr(Attr);
$Html->response(Text,Attr);

Html::request(Id);
Html::request(Id)->response(Text,Attr);
Html::request(Id)->text(Text)->attr(Attr)->response(Text,Attr);

Application::html(Id);
Application::html(Id)->response(Text,Attr);
Application::html(Id)->text(Text)->attr(Attr)->response(Text,Attr);
*/
class AssetHtml extends AssetId
{
	protected $text, $attr = array();
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
	public function response()
	{
		// $this->text(func_get_args()[0]);
		// $this->attr(func_get_args()[1]);
		return $this->Engine($this->Id, $this->text, $this->attr);
	}
	private function Initiate($cluster)
	{
		if (is_array($cluster)) {
			return implode(
				array_map(
					function ($v, $k) {
						if (is_numeric($k) or $k == 'text') {
							return $this->Initiate($v);
						} elseif (!is_array($v)) {
							return $this->Engine($k,$v);
						} else {
							return $this->Engine($k,$this->Initiate($v['text']),$v['attr']);
						}
					}, $cluster, array_keys($cluster)
				)
			);
		} else {
			return $cluster;
		}
	}
	private function Engine($tag, $text, $attr=array())
	{
		$Obj = (object) array();
		array_walk($attr, create_function('&$v,$k','$v=is_bool($v)?$k:$v; $v=is_array($v)?implode($v," "):$v; $v=" $k=\"$v\"";'));
		$Obj->attr = implode($attr);
		$Obj->tag = $tag;
		$Obj->text = $text;
		return preg_replace_callback(Application::AHR,function($i) use ($Obj) {
				return $Obj->{$i[1]};
			}, Application::$AHE[$tag?in_array($tag, Application::$AHS)?0:1:2]
		);
	}
	public function __toString()
	{
	    return $this->Initiate($this->Id);
	}
}