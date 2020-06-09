<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 22:12:00
 * @LastEditTime: 2020-06-09 07:40:48
 * @FilePath: /swoole-rpc/src/Server/ServiceRegisterInterface.php
 */

namespace Swoole\Rpc\Server;

use Swoole\Rpc\Service;


interface ServiceRegisterInterface
{
   public function register(Service $service);
}
