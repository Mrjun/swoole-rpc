<?php
/*
 * @Author: 吴云祥
 * @Date: 2020-06-07 12:23:01
 * @LastEditTime: 2020-06-14 10:39:24
 * @FilePath: /pf-connection-server/vendor/clouds-flight/swoole-rpc/src/Client/Client.php
 */

namespace Swoole\Rpc\Client;

use Swoole\Rpc\LogInterface;
use Swoole\Rpc\LogType;
use Swoole\Rpc\MessageHook;

class Client
{
    private $client;

    private $host;

    private $port;

    private $options;

    private $status;

    private $hook;

    private $log;

    private $timeout;

    public function __construct($host, $port,$timeout, $options=[], LogInterface $log=null, MessageHook $hook = null)
    {
        $this->client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        $this->host = $host;
        $this->port = $port;
        $this->options = $options;
        $this->log = $log;
        $this->hook = $hook;

        $this->timeout=$timeout;

        $this->options['open_length_check'] = 1;
        $this->options['package_length_type'] = 'V';
        $this->options['package_length_offset'] = 0;
        $this->options['package_body_offset'] = 4;

        if (!isset($this->options['timeout'])) {
            $this->options['timeout'] = 1;
        }

        $this->client->set($this->options);

        $this->status = ClientStatus::FREE;
    }

    public function isLock()
    {
        if ($this->status == ClientStatus::BUSY) {
            return true;
        } else {
            return false;
        }
    }

    public function lock()
    {
        $this->status = ClientStatus::BUSY;
    }

    public function unLock()
    {
        $this->status = ClientStatus::FREE;
    }

    public function __call($name, $arguments)
    {

        if (!$this->client->isConnected()) {

            if (!$this->client->connect($this->host, $this->port,$this->timeout)) {
                if($this->log!=null)
                {
                    $this->log->log(LogType::ERROR, '连接' . $this->host . ':' . $this->port . '失败');
                }
                
                return false;
            }
        }

        $message = new Message($name, $arguments, $this->hook);

        $bdata = $message->pack();

        $len = $this->client->send($bdata);

        if (!$len || $len != strlen($bdata)) {
            $this->close();
            return false;
        }

        $data = $this->client->recv();


        $return = $message->unpack($data);

        if ($return === false) {
            $this->client->close();
        }

        return $return;
    }
}
