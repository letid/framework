<?php
namespace Letid\Form;
trait Name
{
	static function name()
	{
		return new self('formName',func_get_args()[0]);
	}
}