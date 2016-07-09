<?php
namespace letId\support
{
    class form extends \letId\form\request
    {
        static function name($Id=null)
    	{
    		return new self($Id);
    	}
    }
}