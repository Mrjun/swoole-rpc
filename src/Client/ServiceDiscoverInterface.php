<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-09 07:18:03
 * @LastEditTime: 2020-06-09 07:46:05
 * @FilePath: /swoole-rpc/src/Client/ServiceDiscoverInterface.php
 */

use Swoole\Rpc\Service;

interface ServiceDiscoverInterface
 {

    public function services(array $options=[]):array;

 }