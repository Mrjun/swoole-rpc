<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 20:20:55
 * @LastEditTime: 2020-06-09 09:40:27
 * @FilePath: /swoole-rpc/src/Service.php
 */

namespace Swoole\Rpc;

use Swoole\Rpc\MessageHook;

class Service
{
    public $id;
    public $name;
    public $host;
    public $port;
    public $isRpc=true;
    public $options;
    public $extends;
    public $handle;
    public $hook;
}
