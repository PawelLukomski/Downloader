<?php

namespace Controller;


use Manager\CurlManager;

class Controller extends CurlManager
{
    public function isCharOnEnd($string, $char)
    {
        preg_match("/.$/m", $string, $filter);
        if($filter == $char)
            return true;
        else
            return false;
    }
}