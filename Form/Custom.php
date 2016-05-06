<?php
namespace Letid\Form;
trait Custom
{
    private function custom_duplicate_check($value,$name)
    {
        if ($this->formTable) {
            return !Database::select('id')->from($this->formTable)->where($name,$value)->execute()->rowsCount()->rowsCount;
        }
    }
    private function custom_password_encrypt($value)
    {
        return sha1($value);
    }
}