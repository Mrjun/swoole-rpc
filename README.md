# swoole-rpc








服务端：

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

     //创建consul服务注册类，通过实现 ServiceRegisterInterface 可扩展其它服务注册方式
     //ConsulServiceRegister的参数就是easy-consul(https://github.com/clouds-flight/easy-consul) ApiFactory::init($config);的参数
     $register=new ConsulServiceRegister($config);
     
    
     //传入创建的实现了 ServiceRegisterInterface 接口的类实例即可
     $server=Server::getInstance($register);

     //创建一个服务
     
     $service1=new Service();

     $service1->id="test-server-1";
     $service1->name="test-server";
     $service1->host="127.0.0.1";
     $service1->port=8000;
     //Service全部成员
     //    public $id;  //服务id，同一服务名，需用id区分
     //    public $name; //服务名
     //    public $host;  //服务的地址
     //    public $port;  //服务的监听端口
     //    public $isRpc=true; //是否是rpc服务，若为false，则当存在服务注册类实例时，只会注册该服务，而不会启动监听
     //    public $options;  //swoole/server  set 函数 的options 参数 ，但包检查等参数不能设置，即使设置了也会被覆盖
     //    public $extends;  //服务注册类所需其它参数
     //    public $handle;   //继承 Hanlde 类的子类对象实例，即rpc服务器的功能，编写函数给客户端调用
     //    public $hook;  //实现了MessageHookInterface接口的类实例，主要钩子主要用于打包数据和解包数据之前，留有可以自定义加解密的功能

     //服务注册类扩展参数：某种服务注册类所需的其它参数存放在这里，下面是consul注册类的健康检查参数
     $service1->extends['enableTagOverride']=false;

     $service1->extends['check']=
     [//健康检查
         'interval' => '3s', //健康检查间隔时间，每隔3s，调用一次上面的URL
         'timeout'  => '1s',
         'tcp' =>"127.0.0.1:8000" 
     ];
    
     $service1->handle=new ServerHandle();
     //添加服务
     $server->addService($service1);

     //可选参数 \Swoole\Server 对象，若需启动监听其它服务，可自行创建\Swoole\Server 对象，完成自定义所需监听接口或操作后，再将 对象传入 run
     //若不传，则会在内部创建并启动
     $server->run();


客户端：

     $config =[
         [
            'uri' => 'http://127.0.0.1:8500/',
            'token' => '02f51f27-88c5-15d5-1669-219857377e28',
            'timeout' => '3'
         ]
     ];

     //创建consul服务发现类，通过实现 ServiceDiscoverInterface 可扩展其它服务发现方式
     //ConsulServiceDiscover的参数就是easy-consul(https://github.com/clouds-flight/easy-consul) ApiFactory::init($config);的参数
     $consulServiceDiscover= new ConsulServiceDiscover($config);

     //options参数
     //service_name  根据服务名查找
     //health  bool  是否查找健康服务，false 则根据服务名查找所有服务
     $options=[
         'service_name'=>'test-server',
         'health'=>true
     ];
      
     //执行查找，返回Service实例数组
     $services=$consulServiceDiscover->services($options);

     if(count($services)>0)
     {
         //ClientFactory::getInstance 参数为客户端最大活跃数量，即创建的连接在未超过该值时，或将其保存起来，下次获取客户端不用在创建和连接
         $clientFactory=ClientFactory::getInstance(30);

         //获取客户端连接
         $client1=$clientFactory->getClient($services[0]->host,$services[0]->port);
         
         //getClient($host, $port, $options = [], LogInterface $log = null, MessageHook $hook = null)
         //$options 为 swoole/client set 函数参数
         //$log   日志类实例，可自行实现 LogInterface，创建对象后传入,日志类型为 LogType::ERROR 时表示 连接rpc服务器端失败，可通过该消息
         //进行错误提醒等功能
         //$hook 同服务器端，用于注入加解密操作
         
         
         //执行服务器handle方法，返回false则表明调用失败，
         var_dump($client1->add(1,2));
         //解锁客户端，$clientFactory->getClient 在返回客户端实例时，已将实例状态锁定，使用完后需将其解锁，否则下次服务再获取到该客户端实例
         $client1->unLock();
         
         $client2=$clientFactory->getClient($services[0]->host,$services[0]->port);
         var_dump($client2->del(1,2));
     }
     
  
