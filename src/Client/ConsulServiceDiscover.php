<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-09 07:17:17
 * @LastEditTime: 2020-06-09 10:41:33
 * @FilePath: /swoole-rpc/src/Client/ConsulServiceDiscover.php
 */

 namespace Swoole\Rpc\Client;

use Easy\Consul\Api\Health;
use Easy\Consul\ConfigObserver;
use Easy\Consul\LogInterface;
use Easy\Consul\ApiFactory;
use Easy\Consul\Helper\ServiceHelper;
use Swoole\Rpc\Service;

class ConsulServiceDiscover
{

    public function __construct($cfg, ConfigObserver $observer = null, LogInterface $log = null, $options = [])
    {
        ApiFactory::init($cfg, $observer, $log, $options);
    }

    public function services(array $options = []) :array
    {
        $services = [];

        $service = new ServiceHelper();

        if (isset($options['service_id'])) {
            $service->id = $options['service_id'];
        }

        if (isset($options['service_name'])) {
            $service->name = $options['service_name'];
        }

        if (isset($options['health']) && $options['health'] === true) {

            $res=$service->healthServiceByName();

            if($res!=false)
            {
                foreach($res as $item)
                {
                    $s=new Service();
                    $s->id=$item->Service->ID;
                    $s->name=$item->Service->Service;
                    $s->host=$item->Service->Address;
                    $s->port=$item->Service->Port;
                    $services[]=$s;
                }
            }

        } else {
            
            $res=$service->services();

            if($res!=false)
            {
                foreach($res as $item)
                {
                    $s=new Service();
                    $s->id=$item->ID;
                    $s->name=$item->Service;
                    $s->host=$item->Address;
                    $s->port=$item->Port;
                    $services[]=$s;
                }
            }

            
        }

        return $services;
    }
}
