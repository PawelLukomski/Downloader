<?php
/**
 * Created by PhpStorm.
 * User: toor
 * Date: 17.08.18
 * Time: 16:31
 */

namespace Manager;


class Proxy
{
    protected $file = 'proxy.txt';

    public function getProxy()
    {
        $file = fopen(__DIR__."/../../resources/Proxies/".$this->file, "r");

        $proxies = [];

        while($line = fgets($file)) {
            $proxies[] = explode(":",$line);
        }
        $rand = $proxies[rand(0,count($proxies) -1)];

        return $rand;
    }
}