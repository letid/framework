<?php
namespace Letid\Database;
trait Build
{
    public function build()
    {
        $this->query = implode(Application::SlS, array_map(
            function ($v, $k) {
                if (is_numeric($k)) {
                    return is_array($v)?implode(',',$v):$v;
                } else {
                    $fNQ = strtolower('build_'.str_replace(Application::SlS, '_', $k));
                    if (method_exists($this, $fNQ)) {
                        $y = call_user_func_array(array($this, $fNQ),array($k,$v));
                        if (is_array($y)) {
                            return implode(' ',$y);
                        } elseif ($y) {
                            return sprintf("%s %s", $k, $y);
                        }
                    } elseif ($v) {
                        if (is_array($v)) {
                            return sprintf("%s %s", $k, implode(',',$v));
                        } else {
                            return sprintf("%s %s", $k, $v);
                        }
                    }
                    return $k;
                }
            }, $this->queries, array_keys($this->queries)
        ));
        return $this;
    }
    private function build_select($Name,$args)
    {
        return implode(Application::SlS, array_map(
            function ($v, $k) {
                if (!$rows = implode(',',$v)) {
                    $rows = '*';
                }
                if (is_numeric($k)) {
                    return $rows;
                } else {
                    return $k.Application::SlS.$rows;
                }
            }, $args, array_keys($args)
        ));
    }
    private function build_set($Name,$args)
    {
        return implode(', ', array_map(
            function ($v, $k) {
                if (is_array($v)) {
                    return sprintf("%s=%s", $k, implode(' ', $v));
                } else {
                    return sprintf("%s='%s'", $k, addslashes($v));
                }
                // return sprintf("%s='%s'", $k, addslashes($v));
            }, $args, array_keys($args)
        ));
    }
    private function build_values($Name,$args)
    {
        $columnValue = array();
        foreach ($args as $key => $value) {
            $abcd = array_map(
                function ($v) {
                    // if (is_array($v)) {
                    //     print_r($v);
                    //     return implode(' ', $v);
                    // } else {
                    //     echo $v;
                    //     return sprintf("'%s'", addslashes($v));
                    // }
                    return sprintf("'%s'", addslashes($v));
                }, $value
            );
            $valueFilter = implode(',', $abcd);
            $columnValue[] = sprintf("(%s)", $valueFilter);
        }
        return implode(', ',$columnValue);
    }
    private function build_columns($Name,$args)
    {
        return implode(', ',array_map(
            function ($v,$k) {
                if (is_scalar($v)) {
                    return sprintf("%s", $v);
                } else {
                    return sprintf("%s (%s)", $k,implode(', ',$v));
                }
            }, $args, array_keys($args)
        ));
    }
    // private function build_from($Name,$args)
    // {
    //     if (is_array($args)) return implode(', ',$args);
    // }
    private function build_where($Name,$args)
    {
        if (sizeof($args) == sizeof($args, COUNT_RECURSIVE)) {
            return $this->build_where_operator($args);
        } else {
            return str_replace( array('AND AND AND','AND OR AND','AND AND','AND OR'), array('AND','OR'), $this->build_where_implode($args));
        }
    }
    private function build_where_implode($args)
    {
        return implode(' AND ',array_map(
            function ($value) {
                if (is_array($value)) {
                    if (count($value) == count($value, COUNT_RECURSIVE)) {
                        return $this->build_where_operator($value);
                    } else {
                        $row = $this->build_where_implode($value);
                        if (count($value) > 1) {
                            return sprintf('(%s)', $row);
                        } else {
                            return $row;
                        }
                    }
                } elseif (strstr($value, ' ')) {
                    return $value;
                } else {
                    return strtoupper($value);
                }
            }, $args
        ));
    }
    private function build_where_operator($args)
    {
        if (array_keys($args) !== range(0, count($args) - 1)) {
            return implode(' AND ', array_map(
    			function ($v, $k) {
                    if (is_string($k)) {
                        return $this->build_where_addslashes($k, '=', $v);
                    } elseif (strpos($v, ' ') > 0) {
                        return $v;
                    } else {
                        return strtoupper($v);
                    }
    			}, $args, array_keys($args)
    		));
        } else {
            if (count($args) == 2) {
                if (substr($args[1], 0, 1) == '%' or substr($args[1], -1) == '%') {
                    array_splice($args, 1, 0, 'LIKE');
                } else {
                    array_splice($args, 1, 0, '=');
                }
            }
            return call_user_func_array(array($this, 'build_where_addslashes'), $args);
        }
    }
    private function build_where_addslashes($k,$x,$v)
    {
        // return vsprintf("%s %s '%s'", $args);
        return sprintf("%s %s '%s'",$k,$x,addslashes($v));
    }
    private function build_insert_into($Name,$args)
    {
        return $this->build_columns($Name,$args);
    }
    private function build_update($Name,$args)
    {
        return $this->build_columns($Name,$args);
    }
    private function build_on_duplicate_key_update($Name,$args)
    {
        // print_r($Name);
        return $this->build_set($Name,$args[0]);
        // print_r($args);
        // return 'love=ks';
    }
    private function build_and__($Name,$args)
    {
        // echo $Name;
        // print_r($args);
        array_splice($args[0], 1, 0, '!=');
        // print_r($args);
        return $this->build_where('aknd',$args);
    }
    // private function build_group_by_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_order_by_build_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_limit_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_offset_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_insert_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_update_($Name,$args)
    // {
    //     return implode(', ',$args);
    // }
    // private function build_delete_($Name,$args)
    // {
    //     return implode(',',$args);
    // }
}