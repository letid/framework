<?php
namespace Letid\Id;
trait Utilities
{
	public function array_sentence($x,$y=', ',$z=' & ')
    {
		return join($z, array_filter(array_merge(array(join($y, array_slice($x, 0, -1))), array_slice($x, -1)), 'strlen'));
	}
	public function array_key_join_value($x,$y=', ',$z="%s='%s'")
    {
		return join($y, array_map(
			function ($v, $k) use($z) {
				return sprintf($z, $k, $v);
			}, $x, array_keys($x)
		));
	}
}