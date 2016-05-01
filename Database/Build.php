<?php
namespace Letid\Database;
trait Build
{
    public function build()
    {
        $data = $this->queries;
        $this->query = join(self::SP, array_map(
            function ($v, $k) {
                if (is_numeric($k)) {
                    return is_array($v)?join(',',$v):$v;
                } else {
                    $fNQ = strtolower('build_'.str_replace(self::SP, '_', $k));
                    if (method_exists($this, $fNQ)) {
                        $y = call_user_func_array(array($this, $fNQ),array($k,$v));
                        if (is_array($y)) {
                            return join(' ',$y);
                        } elseif ($y) {
                            return sprintf("%s %s", $k, $y);
                        }
                    } elseif ($v) {
                        if (is_array($v)) {
                            return sprintf("%s %s", $k, join(',',$v));
                        } else {
                            return sprintf("%s %s", $k, $v);
                        }
                    }
                    return $k;
                }
            }, $data, array_keys($data)
        )).self::ED;
        return $this;
    }
    private function build_select($Name,$args)
    {
        return join(self::SP, array_map(
            function ($v, $k) {
                if (!$rows = join(',',$v)) {
                    $rows = '*';
                }
                if (is_numeric($k)) {
                    return $rows;
                } else {
                    return $k.self::SP.$rows;
                }
            }, $args, array_keys($args)
        ));
    }
    private function build_set($Name,$args)
    {
        return join(', ', array_map(
            function ($v, $k) {
                return sprintf("%s='%s'", $k, addslashes($v));
            }, $args, array_keys($args)
        ));
    }
    private function build_values($Name,$args)
    {
        $columnValue = array();
        foreach ($args as $key => $value) {
            $abcd = array_map(
                function ($v) {
                    return sprintf("'%s'", addslashes($v));
                }, $value
            );
            $valueFilter = join(',', $abcd);
            $columnValue[] = sprintf("(%s)", $valueFilter);
        }
        return join(', ',$columnValue);
    }
    private function build_columns($Name,$args)
    {
        return join(', ',array_map(
            function ($v,$k) {
                if (is_scalar($v)) {
                    return sprintf("%s", $v);
                } else {
                    return sprintf("%s (%s)", $k,join(', ',$v));
                }
            }, $args, array_keys($args)
        ));
    }
    // private function build_from($Name,$args)
    // {
    //     if (is_array($args)) return join(', ',$args);
    // }
    private function build_where($Name,$args)
    {
        if (count($args) == 2) {
            if (substr($args[1], 0, 1) == '%' or substr($args[1], -1) == '%') {
                $operator = array('LIKE');
            } else {
                $operator = array('=');
            }
            array_splice($args, 1, 0, $operator);
        }
        return vsprintf("%s %s '%s'", $args);
    }
    private function build_insert_into($Name,$args)
    {
        return $this->build_columns($Name,$args);
    }
    private function build_update($Name,$args)
    {
        return $this->build_columns($Name,$args);
    }
    // private function build_group_by_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_order_by_build_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_limit_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_offset_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_insert_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_update_($Name,$args)
    // {
    //     return join(', ',$args);
    // }
    // private function build_delete_($Name,$args)
    // {
    //     return join(',',$args);
    // }
}