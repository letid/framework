<?php
namespace Letid\Utility;
class Html
{
	public $element;
	public $html;
	public $attribute;
	public $attr = array();
	private static $tag = array('img','input','hr','br','meta','link');
	private static $tpl=array('<{element}{attribute}/>','<{element}{attribute}>{html}</{element}>','{html}');
	private static $preg = '/[{](.*?)[}]/';
	public function __construct($element,$html=NULL,$attr=NULL)
	{
		// Html("ol", "{language}", array("id" => "ddd","class" => "class"));
		if(is_array($element)){
			$this->html=self::h($element);
		}else{
			$this->element=self::h($element);
			if($html)$this->html=self::h($html);
			if($attr)$this->attr($attr);
		}
	}
	public function __toString()
	{
		return self::generate();
	}
	public function attr($q)
	{
		return $this->attr = array_merge($this->attr, (array) $q);
	}
	private function generate()
	{
		array_walk($this->attr, create_function('&$v,$k','$v=is_bool($v)?$k:$v; $v=" $k=\"$v\"";'));
		$this->attribute = implode($this->attr);
		return preg_replace_callback(self::$preg,function($M){
				return $this->{$M[1]};
			},self::$tpl[$this->element?in_array($this->element, self::$tag)?0:1:2]
		);
	}
	public static function h($d)
	{
		if(is_array($d)){
			foreach($d as $k => $v){
				if(is_numeric($k) or $k == 'text') $r[] = self::h($v);
					elseif(!is_array($v)) $r[] = new self($k,$v);
					else $r[] = new self($k,self::h($v['text']), @$v['attr']);
			}
			return implode(' ',$r);
		}else{
			return $d;
		}
	}
}
/*
$ol 		= new html("ol", "{language}", array("id" => "ddd","class" => "class"));
$ol_li 		= new html("li", "{language}", array("class" => "class"));
$ol_li_a 	= new html("a", "{language}", array("class" => "class"));

    $input = new html("input");
    echo $input->attributes(array("name" => "test", "value" => "testing", "disabled" => true))->output();
    // <input name="test" value="testing" disabled="disabled"/>

    echo new html("a", "Link Text", array("href" => "http://www.google.com"));
    // <a href="http://www.google.com">Link Text</a>

    $html = new html("a");
    $html->innerHTML("Link Text");
    $html->attributes(array("href" => "http://www.google.com"));
    echo $html->output();
    // <a href="http://www.google.com">Link Text</a>

    echo $html->innerHTML("Link Text")->attributes(array("href" => "http://www.google.com"))->output();
    // <a href="http://www.google.com">Link Text</a>

    $html->innerHTML = "Override Text";
    echo $html->output();
    // <a href="http://www.google.com">Override Text</a>

    $html->attributes["href"] = "http://www.yahoo.com";
    echo $html->output();
    // <a href="http://www.yahoo.com">Override Text</a>
*/
