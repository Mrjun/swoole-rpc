<?php

namespace Test;
/*
 * @Author: 吴云祥
 * @Date: 2020-06-09 08:04:41
 * @LastEditTime: 2020-06-09 08:06:28
 * @FilePath: /swoole-rpc/test/ServerHandle.php
 */

use Swoole\Rpc\Server\Hanlde;

class ServerHandle extends Hanlde
 {

    public function add($a,$b)
    {
        return $a+$b;
    }


 }