<?php

/*
 * @Author: 吴云祥
 * @Date: 2020-06-08 21:04:30
 * @LastEditTime: 2020-06-09 12:27:36
 * @FilePath: /swoole-rpc/src/Server/Hanlde.php
 */

namespace Swoole\Rpc\Server;


abstract class Hanlde
{

    private $filter=[];

    public function deal($name, $arguments)
    {
        $name=trim($name);
        $return = [];
        try {
            
            if (!method_exists($this, $name)||$name=='deal'||in_array($name,$this->filter)) {
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
