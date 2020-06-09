<?

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 17:11:09
 * @LastEditTime: 2020-06-09 08:55:17
 * @FilePath: /swoole-rpc/src/Server/Message.php
 */

namespace Swoole\Rpc\Server;

use Swoole\Rpc\MessageHook;

class Message
{

    private $id;
    private $data;

    private $hook;


    public function __construct(MessageHook $hook=null)
    {
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

    public function pack($data)
    {
        $this->data=$data;
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

        $this->id = $message->id;

        return $message;
    }
}
