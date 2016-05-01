<?php
namespace Letid\Id;
trait Utilities
{
	/*
	toStrOrAnd, toStrSentence, toStrKeyValue
	createSentence
	arrayToSentence,
	arrayJoinWithKey
	createQuery
	*/
	function array_sentence($Data,$Or=', ',$And=' & ')
    {
		return join($And, array_filter(array_merge(array(join($Or, array_slice($Data, 0, -1))), array_slice($Data, -1)), 'strlen'));
	}
	function array_key_join_value($Data,$sep=', ',$format="%s='%s'")
    {
		return join($sep, array_map(
			function ($v, $k) use($format) {
				return sprintf($format, $k, $v);
			}, $Data, array_keys($Data)
		));
	}
}
