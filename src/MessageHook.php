<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 18:58:47
 * @LastEditTime: 2020-06-08 19:08:23
 * @FilePath: /swoole-rpc/src/MessageHook.php
 */

namespace Swoole\Rpc;

interface MessageHook
{
    public function beforePack(string $data): string;
    public function beforeUnpack(string $data): string;
}
