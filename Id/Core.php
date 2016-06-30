<?php
namespace Letid\Id;
abstract class Core
{
	static protected $let, $id, $user;
	static protected $Content, $ContentType, $ContentMeta;
	public function __call($name, $arguments)
	{
		return $this;
	}
}