<?php


require __DIR__ . '/bootstrap.php';


/*
 * @Author: 吴云祥
 * @Date: 2020-06-07 17:39:48
 * @LastEditTime: 2020-06-09 09:42:44
 * @FilePath: /swoole-rpc/test/server.php
 */

use Swoole\Rpc\Server\ConsulServiceRegister;
use Test\ServerHandle;
use Swoole\Rpc\Server\Server;
use Swoole\Rpc\Service;

$config =[
    [
       'uri' => 'http://32.65.89.36:8500/',   //consul地址
       'token' => '02f51f27-88c5-15d5-1669-219857377e28',  //若consul设置需要token，则需配置
       'timeout' => '3' //请求超时时间
    ],
    [
       'uri' => 'http://127.0.0.1:8500/',
       'token' => '02f51f27-88c5-15d5-1669-219857377e28',
       'timeout' => '3'
    ]
];

$register=new ConsulServiceRegister($config);



$server=Server::getInstance($register);

$service1=new Service();

$service1->id="test-server-1";
$service1->name="test-server";
$service1->host="127.0.0.1";
$service1->port=8000;


$service1->extends['enableTagOverride']=false;

$service1->extends['check']=
[//健康检查
    'interval' => '3s', //健康检查间隔时间，每隔3s，调用一次上面的URL
    'timeout'  => '1s',
    'tcp' =>"127.0.0.1:8000" 
];

$service1->handle=new ServerHandle();



$server->addService($service1);

$server->run();

