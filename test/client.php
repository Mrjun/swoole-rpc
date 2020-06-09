<?php


require __DIR__ . '/bootstrap.php';


/*
 * @Author: 吴云祥
 * @Date: 2020-06-07 17:39:48
 * @LastEditTime: 2020-06-09 12:35:24
 * @FilePath: /swoole-rpc/test/client.php
 */ 

use Swoole\Rpc\Client\ClientFactory;
use Swoole\Rpc\Client\ConsulServiceDiscover;

$config =[
    [
       'uri' => 'http://127.0.0.1:8500/',
       'token' => '02f51f27-88c5-15d5-1669-219857377e28',
       'timeout' => '3'
    ]
];

$consulServiceDiscover= new ConsulServiceDiscover($config);

$options=[
    'service_name'=>'test-server',
    'health'=>true
];

$services=$consulServiceDiscover->services($options);

if(count($services)>0)
{
    $clientFactory=ClientFactory::getInstance(30);

    $client1=$clientFactory->getClient($services[0]->host,$services[0]->port);
    var_dump($client1->add(1,2));
    $client1->unLock();
    $client2=$clientFactory->getClient($services[0]->host,$services[0]->port);
    var_dump($client2->notAllow());
}

