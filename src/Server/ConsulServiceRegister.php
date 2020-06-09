<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 22:16:37
 * @LastEditTime: 2020-06-09 10:20:48
 * @FilePath: /swoole-rpc/src/Server/ConsulServiceRegister.php
 */

namespace Swoole\Rpc\Server;


use Easy\Consul\ConfigObserver;
use Easy\Consul\LogInterface;
use Easy\Consul\ApiFactory;
use Easy\Consul\Helper\ServiceHelper;
use Swoole\Rpc\Service;
use Swoole\Rpc\Server\ServiceRegisterInterface;

class ConsulServiceRegister implements ServiceRegisterInterface
{

    public function __construct($cfg, ConfigObserver $observer = null, LogInterface $log = null, $options = [])
    {
        ApiFactory::init($cfg, $observer, $log, $options);
    }

    public function register(Service $service)
    {

        $serviceHelper = new ServiceHelper();

        $serviceHelper->id = $service->id;
        $serviceHelper->name = $service->name;
        $serviceHelper->address = $service->host;
        $serviceHelper->port = $service->port;

        if (isset($service->extends['enableTagOverride'])) {
            $serviceHelper->enableTagOverride = $service->extends['enableTagOverride'];
        }

        if (isset($service->extends['check'])) {
            $serviceHelper->check = $service->extends['check'];
        }

        return $serviceHelper->register();
    }
}
