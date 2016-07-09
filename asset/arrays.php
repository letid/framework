<?php
namespace letId\asset;
class arrays extends avail
{
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
    // public function __toString()
	// {
	//     return $this->str;
	// }
}