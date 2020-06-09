<?php

namespace Test;
/*
 * @Author: 吴云祥
 * @Date: 2020-06-09 08:04:41
 * @LastEditTime: 2020-06-09 12:36:00
 * @FilePath: /swoole-rpc/test/ServerHandle.php
 */

use Swoole\Rpc\Server\Hanlde;

class ServerHandle extends Hanlde
 {

    public function __construct()
    {
        $this->filter=[
            // 'notAllow'
        ];
    }


    public function add($a,$b)
    {
        return $a+$b;
    }

    public function notAllow()
    {
        return "a";
    }


 }