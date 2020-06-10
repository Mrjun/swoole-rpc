<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 22:16:37
 * @LastEditTime: 2020-06-10 08:34:18
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
            $serviceHelper->tags = $service->extends['tags'];
        }

        if (isset($service->extends['meta'])) {
            $serviceHelper->meta = $service->extends['meta'];
        }

        if (isset($service->extends['taggedAddresses'])) {
            $serviceHelper->taggedAddresses = $service->extends['taggedAddresses'];
        }

        if (isset($service->extends['enableTagOverride'])) {
            $serviceHelper->enableTagOverride = $service->extends['enableTagOverride'];
        }

        if (isset($service->extends['check'])) {
            $serviceHelper->check = $service->extends['check'];
        }

        if (isset($service->extends['checks'])) {
            $serviceHelper->checks = $service->extends['checks'];
        }

        if (isset($service->extends['kind'])) {
            $serviceHelper->kind = $service->extends['kind'];
        }

        if (isset($service->extends['proxyDestination'])) {
            $serviceHelper->proxyDestination = $service->extends['proxyDestination'];
        }

        if (isset($service->extends['proxy'])) {
            $serviceHelper->proxy = $service->extends['proxy'];
        }

        if (isset($service->extends['expose'])) {
            $serviceHelper->expose = $service->extends['expose'];
        }

        if (isset($service->extends['connect'])) {
            $serviceHelper->connect = $service->extends['connect'];
        }

        if (isset($service->extends['weights'])) {
            $serviceHelper->weights = $service->extends['weights'];
        }

        if (isset($service->extends['token'])) {
            $serviceHelper->token = $service->extends['token'];
        }

        if (isset($service->extends['namespace'])) {
            $serviceHelper->namespace = $service->extends['namespace'];
        }

        return $serviceHelper->register();
    }
}
