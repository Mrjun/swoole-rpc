<?php
/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 08:50:03
 * @LastEditTime: 2020-06-14 10:38:25
 * @FilePath: /pf-connection-server/vendor/clouds-flight/swoole-rpc/src/Client/ClientFactory.php
 */

namespace Swoole\Rpc\Client;

use Swoole\Rpc\LogInterface;
use Swoole\Rpc\MessageHook;

class ClientFactory
{

    private static $instance;

    private $clients = [];

    private $locks = [];

    private $maxClientNum;

    private function __construct($maxClientNum)
    {
        $this->maxClientNum = $maxClientNum;
    }

    public static function getInstance($maxClientNum = 0)
    {
        if (null === static::$instance) {
            self::$instance = new self($maxClientNum);
        }
        return self::$instance;
    }

    public function getClient($host, $port,$timeout=1, $options = [], LogInterface $log = null, MessageHook $hook = null)
    {
        
        $server = $host . ':' . $port;

        if (isset($this->clients[$server])) {
            
            $select = null;

            foreach ($this->clients[$server] as $client) {

                if (!$client->isLock()) {
                    $this->locks[$server]->lock();
                    if (!$client->isLock()) {
                        $client->lock();
                        $select = $client;
                        $this->locks[$server]->unlock();
                        break;
                    }
                    $this->locks[$server]->unlock();
                }
            }

            if (!empty($select)) {
                return $select;
            }
        } else {
            $this->clients[$server]=[];
            $this->locks[$server] = new \Swoole\Lock(SWOOLE_MUTEX);
        }
    
        $client = new Client($host, $port,$timeout, $options, $log, $hook);
        $client->lock();

        $this->locks[$server]->lock();
        if (count($this->clients[$server]) < $this->maxClientNum) {
            $this->clients[$server][] = $client;
        }
        $this->locks[$server]->unlock();

        return $client;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
