<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 17:27:15
 * @LastEditTime: 2020-06-09 09:18:18
 * @FilePath: /swoole-rpc/src/Client/MessageIdGenerator.php
 */

namespace Swoole\Rpc\Client;

class MessageIdGenerator
{

    private static $instance;

    private $lock;

    private $currentId;

    private function __construct()
    {
        $this->lock = new \Swoole\Lock(SWOOLE_MUTEX);
        $this->currentId = 1;
    }


    public static function getInstance()
    {
        if (null === static::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getId()
    {
        $id = null;

        $this->lock->lock();
        if ($this->currentId == PHP_INT_MAX) {
            $this->currentId = 1;
        }
        $id = $this->currentId++;
        $this->lock->unlock();

        return $id;
    }


    private function __clone()
    {
    }


    private function __wakeup()
    {
    }
}
