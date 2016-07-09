<?php
namespace letId\form;
trait custom
{
    private function customDuplicate($value,$name,$callback)
    {
        if ($this->table) {
            $db = avail::$database->select($name)->from($this->table)->where($name,$value);
            if (isset($callback)) {
                if (is_callable($callback)) {
                    $db->whereAnd(call_user_func($callback, $this));
                } else {
                    $db->whereAnd($callback);
                }
            }
            return !$db->execute()->rowsCount()->rowsCount;
        }
    }
    private function customExists($value,$name,$callback)
    {
        if ($this->table) {
            $db = avail::$database->select($name)->from($this->table)->where($name,$value);

            if (isset($callback)) {
                if (is_callable($callback)) {
                    $db->whereAnd(call_user_func($callback, $this));
                } else {
                    $db->whereAnd($callback);
                }
            }
            return $db->execute()->rowsCount()->rowsCount;
            // $db->execute()->rowsCount();
            // print_r($db);
            // return $db->rowsCount;
        }
    }
    private function customEncrypt($value)
    {
        return avail::assist(null)->sha1($value);
    }
}