<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 21:04:30
 * @LastEditTime: 2020-06-10 09:53:27
 * @FilePath: /pf-connection-server/vendor/clouds-flight/swoole-rpc/src/Server/Hanlde.php
 */

namespace Swoole\Rpc\Server;


abstract class Hanlde
{

    private $filter=[];

    public $server;

    public function deal($name, $arguments)
    {
        $name=trim($name);
        $return = [];
        try {
            
            if (!method_exists($this, $name)||$name=='deal'||$name=='__construct'||in_array($name,$this->filter)) {
                $return['status'] = 0;
                $return['msg'] = '执行失败,'.$name.'方法不存在';
                $return['data'] =null;
            } else {
                
                $data = $this->{$name}(...$arguments);
                $return['status'] = 1;
                $return['msg'] = '执行成功';
                $return['data'] = $data;
            }
        } catch (\Exception $e) {
            $return['status'] = 0;
            $return['msg'] = '执行失败,'.$e->getMessage();
            $return['data'] = null;
        }

        return $return;
    }
}
