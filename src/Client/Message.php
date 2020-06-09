<?

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 17:11:09
 * @LastEditTime: 2020-06-09 08:47:53
 * @FilePath: /swoole-rpc/src/Client/Message.php
 */

namespace Swoole\Rpc\Client;

use Swoole\Rpc\MessageHook;

class Message
{

    private $id;
    private $name;
    private $arguments;

    private $hook;


    public function __construct($name, $arguments, MessageHook $hook=null)
    {
        $this->id = MessageIdGenerator::getInstance()->getId();
        $this->name = $name;
        $this->arguments = $arguments;
        $this->hook = $hook;
    }

    private function encode()
    {
        $obj = [];
        foreach ($this as $key => $value) {
            if (!empty($value)) {
                $obj[$key] = $value;
            }
        }

        unset($obj['hook']);

        return $obj;
    }

    public function pack()
    {
        $data = json_encode($this->encode());
        if ($this->hook != null) {
            $data = $this->hook->beforePack($data);
        }

        $lenght = strlen($data);
        $bdata = pack('V1', $lenght).$data;
        return $bdata;
    }

    public function unpack($data)
    {
        $message = substr($data, 4);

        if ($this->hook != null) {
            $message = $this->hook->beforeUnPack($message);
        }

        $message = json_decode($message);

        if ($message->id == $this->id) {
            return $message->data;
        } else {
            return false;
        }
    }
}
