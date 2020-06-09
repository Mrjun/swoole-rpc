<?php
/*
 * @Author: 吴云祥
 * @Date: 2020-06-06 07:12:22
 * @LastEditTime: 2020-06-08 22:33:48
 * @FilePath: /swoole-rpc/src/LogInterface.php
 */

namespace Swoole\Rpc;


interface LogInterface
{
    public function log($type, $message);
}
