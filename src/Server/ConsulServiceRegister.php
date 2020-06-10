<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 22:16:37
 * @LastEditTime: 2020-06-10 08:23:49
 * @FilePath: /pf-connection-server/vendor/clouds-flight/swoole-rpc/src/Server/ConsulServiceRegister.php
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

        if (isset($service->extends['tags'])) {
            $serviceHelper->enableTagOverride = $service->extends['tags'];
        }

        if (isset($service->extends['meta'])) {
            $serviceHelper->enableTagOverride = $service->extends['meta'];
        }

        if (isset($service->extends['taggedAddresses'])) {
            $serviceHelper->enableTagOverride = $service->extends['taggedAddresses'];
        }

        if (isset($service->extends['enableTagOverride'])) {
            $serviceHelper->enableTagOverride = $service->extends['enableTagOverride'];
        }

        if (isset($service->extends['check'])) {
            $serviceHelper->check = $service->extends['check'];
        }

        if (isset($service->extends['checks'])) {
            $serviceHelper->check = $service->extends['checks'];
        }

        if (isset($service->extends['kind'])) {
            $serviceHelper->check = $service->extends['kind'];
        }

        if (isset($service->extends['proxyDestination'])) {
            $serviceHelper->check = $service->extends['proxyDestination'];
        }

        if (isset($service->extends['proxy'])) {
            $serviceHelper->check = $service->extends['proxy'];
        }

        if (isset($service->extends['expose'])) {
            $serviceHelper->check = $service->extends['expose'];
        }

        if (isset($service->extends['connect'])) {
            $serviceHelper->check = $service->extends['connect'];
        }

        if (isset($service->extends['weights'])) {
            $serviceHelper->check = $service->extends['weights'];
        }

        if (isset($service->extends['token'])) {
            $serviceHelper->check = $service->extends['token'];
        }

        if (isset($service->extends['namespace'])) {
            $serviceHelper->check = $service->extends['namespace'];
        }

        return $serviceHelper->register();
    }
}
