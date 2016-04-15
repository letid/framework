<?php
namespace Letid\Id;
trait Utilities
{
	function joinAndOr($Data,$Or=', ',$And=' & ')
    {
		return join($And, array_filter(array_merge(array(join($Or, array_slice($Data, 0, -1))), array_slice($Data, -1)), 'strlen'));
	}
	function joinWithKey($Data)
    {
		return join(', ', array_map(
			function ($v, $k) {
				return sprintf("%s='%s'", $k, $v);
			}, $Data, array_keys($Data)
		));
	}
}
