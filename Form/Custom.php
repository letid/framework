<?php
namespace Letid\Form;
trait Custom
{
    private function customDuplicate($value,$name,$callback)
    {
        if ($this->formTable) {
            $query = self::$database->select($name)->from($this->formTable)->where($name,$value);
            if (isset($callback)) {
                if (is_callable($callback)) {
                    $query->whereAnd(call_user_func($callback, $this));
                } else {
                    $query->whereAnd($callback);
                }
            }
            return !$query->execute()->rowsCount()->rowsCount;
        }
    }
    private function customExists($value,$name,$callback)
    {
        if ($this->formTable) {
            $query = self::$database->select($name)->from($this->formTable)->where($name,$value);

            if (isset($callback)) {
                if (is_callable($callback)) {
                    $query->whereAnd(call_user_func($callback, $this));
                } else {
                    $query->whereAnd($callback);
                }
            }
            return $query->execute()->rowsCount()->rowsCount;
            // $query->execute()->rowsCount();
            // print_r($query);
            // return $query->rowsCount;
        }
    }
    private function customEncrypt($value)
    {
        return self::assist()->sha1($value);
    }
    private function customSignin()
    {
        // checkbox for remember me can be use (id=>true)
        $db = self::$database->select('*')->from($this->formTable)->where($this->formPost)->execute()->toObject()->rowsCount();
        if ($db->rowsCount) {
            if (isset($db->rows->status))
            {
                $rowsArray = (array) $db->rows;
                if ($db->rows->status > 0)
                {
                    if(isset($db->rows->logs))
                    {
                        self::$database->update(array_filter($rowsArray, function(&$v, $k) {
                            if ($k == 'logs') {
                                return $v=$v+1;
                            }
                            if ($k == 'modified') {
                                return $v=date('Y-m-d G:i:s');
                            }
                        }, ARRAY_FILTER_USE_BOTH))->to($this->formTable)->where($this->formPost)->execute()->rowsAffected();
                    }
                }
                self::session()->remove();
                /*
                $signCookieValue = array_intersect_key($rowsArray, array_flip(array('userid','password')));
                Cookie::id($this->configuration('signCookieId'))->set($signCookieValue);
                \Letid\Assist\Cookie::id($this->configuration('signCookieId'))->set(array_intersect_key($rowsArray, array_flip(array('userid','password'))));
                */
                // Cookie::id($this->configuration('signCookieId'))->set(array_intersect_key($rowsArray, array_flip(array('userid','password'))));
                self::cookie()->sign()->set(array_intersect_key($rowsArray, array_flip(array('userid','password'))));
            }
            // $_COOKIE['letid.user.name']
            // unserialize($_COOKIE['letid.user.name']);
            // NOTE: temporary avoid redirectIfsuccess
            // $this->formPost = false;
            // $this->responseSuccess("Signed in! What's next?");
        } else {
            $this->formPost = false;
            if ($db->msg) {
                $this->responseError($db->msg);
            } else {
                // TODO: message need to be improved
                // $this->responseError($db->query);
                $this->responseError('Incorrect username or password');
            }
        }
    }
}