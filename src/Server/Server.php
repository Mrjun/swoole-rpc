<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-07 12:22:53
 * @LastEditTime: 2020-06-09 10:55:34
 * @FilePath: /swoole-rpc/src/Server/Server.php
 */

namespace Swoole\Rpc\Server;
use Swoole\Rpc\Service;

class Server
{

    private static $instance;

    private $services = [];

    private $server;

    private $register;


    private function __construct(ServiceRegisterInterface $register=null)
    {
        $this->register = $register;
    }

    public static function getInstance(ServiceRegisterInterface $register=null)
    {
        if (null === static::$instance) {
            self::$instance = new self($register);
        }
        return self::$instance;
    }

    public function addService(Service $service)
    {
        $this->services[] = $service;
    }

    public function run(\Swoole\Server $server = null)
    {
        $this->server = $server;
        if ($this->server == null) {
            $this->server = new \Swoole\Server('0.0.0.0', 0);
        }


        foreach ($this->services as $service) {

            if($service->isRpc)
            {
                $port = $this->server->listen($service->host, $service->port, SWOOLE_SOCK_TCP);
                $options = $service->options;
                $options['open_length_check'] = 1;
                $options['package_length_type'] = 'V';
                $options['package_length_offset'] = 0;
                $options['package_body_offset'] = 4;
                $port->set($options);
    
                $port->on('receive', function ($serv, $fd, $from_id, $data) use ($service) {
                    $message = new Message($service->hook);
                    $msg = $message->unpack($data);
                    $return = $service->handle->deal($msg->name, $msg->arguments);
                    $rdata = $message->pack($return);
                    $serv->send($fd, $rdata);
                });
            }

            if($this->register!=null)
            {
                $this->register->register($service);
            }
            
        }

        $this->server->on('receive',function($serv, $fd, $from_id, $data)
        {
          
        });

        $this->server->start();
    }


    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
