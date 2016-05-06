<?php
namespace Letid\Database;
/*

*/
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
        /*
        'AND'=>array('id',1),
        'OR'=>array('id','1%'),
        */
        // $args = array(
        //     // array('id',1)
        //     // array(
        //     //     array('a1','1%'),
        //     //     array('a2','1%'),
        //     //     array('a3','>','1')
        //     // ),
        //     // // 'OR'=>array('id','>','1'),
        //     // array(
        //     //     array(
        //     //         array('b1','1'),
        //     //         array('b2','1')
        //     //     ),
        //     //     array(
        //     //         array('c1','1'),
        //     //         array('c3','1')
        //     //     )
        //     // )
        //     // array(
        //     //     array('a','>','1'),
        //     //     'or',
        //     //     array('b','<','2'),
        //     // ),
        //     // 'or',
        //     // array(
        //     //     array(
        //     //         array('e','>','1'),
        //     //         array('f','>','1')
        //     //     ),
        //     //     'or',
        //     //     array(
        //     //         array('g','>','1'),
        //     //         array('g','>','1')
        //     //     )
        //     // ),
        //     // 'or',
        //     // array('z','>','1'),
        //     // 'or',
        //     // array('trt','>','1')
        // );
        // $args = array('id',1);

        if (count($args) == count($args, COUNT_RECURSIVE)) {
            return $this->build_where_helper($args);
        } else {
            // print_r($args);
            $args =  $this->build_where_deep($args);
            print_r($args);
            $args_map =  $this->build_where_map($args,true);
            // print_r($args_map);
            return join(' ', $args_map);
        }
    }
    private function build_where_map($args,$test)
    {
        return array_map(
            function ($v,$k) use($test) {
                $Ope='';
                if ($k % 2 == 0) {
                    // if (is_array($v)) {
                    //     $Ope = 'AND';
                    // }
                    // if ($test) {
                    //
                    //     $Ope = 'AND...';
                    // }
                } else {
                    if (is_array($v)) {
                        $Ope = 'AND';
                    }
                }

                // if ($k > 0) {
                //     if (is_array($v)) {
                //         $Ope = 'AND';
                //     }
                // }
                if (is_array($v)) {
                    $map = $this->build_where_map($v);
                    if ($Ope) {
                        $format = '%s %s';
                        if (count($map) > 1) {
                            $format = '%s (%s)';
                        }
                        return sprintf($format, $Ope, join(' ',$map));
                    } elseif (count($map) > 1) {
                        return sprintf("(%s)", join(' ',$map));
                    } else {
                        return join(' ', $map);
                    }
                } elseif (strpos($v, ' ') > 0) {
                    return $v;
                } else {
                    return strtoupper($v);
                }
            }, $args, array_keys($args)
        );
    }
    private function build_where_deep($args)
    {
        array_walk($args, function(&$value) {
            if (is_array($value)) {
                if (count($value) == count($value, COUNT_RECURSIVE)) {
                    $value = array($this->build_where_helper($value));
                } else {
                    $value = $this->build_where_deep($value);
                }
            }
        });
        return $args;
    }
    private function build_where_helper($args)
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
        // return sprintf("%s %s '%s'", $args[0], $args[1], $args[2]);
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