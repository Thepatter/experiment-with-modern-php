## esay-ems 

#### 地址
https://github.com/overtrue/easy-sms
#### 环境需求
* PHP >= 5.6
#### 安装
`composer require "overtrue/easy-sms`
#### 使用
```php
use Overtrue\EasySms\EasySms;

$config = [
    // http 请求超时时间(秒)
    'timeout' => 5.0,
    // 默认发送配置
    'default' => [
        // 网关调用策略,默认: 顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy:class,
        
        // 默认可用的发送网关
        'gateways' => [
            'yunpian', 'aliyun', 'alidayu',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'yunpian' => [
            'api_key' => xxxxxxxxxxxxxxxxxxx,
        ],
        'aliyun' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'sign_name' => '',
        ],
    ],
];

$easySms = new EasySms($config);

$easySms->send($phone, [
    'content' => '您的验证码为: 1234',
    'template' => 'SMS_001',
    'data' => [
        'code' => 1234'
    ],
]);   
```
#### 短信内容
由于使用多网关发送,所以一条短信要支持多平台发送,每家发送方式不一昂,抽象的公共属性
* content 文字内容,使用在像云片类似的以文字内容发送的平台
* template 模版 ID, 使用在以模版 ID 来发送短信的平台
* data 模版变量,使用在以模版 ID 来发送短信的平台

#### 发送网关
默认使用 default 中的设置来发送,如果某一条短信你想要覆盖默认的设置,在 send 方法中使用第三个参数
```php
$easySms->send($phone, [
    'content' => '您的验证码为: 1234',
    'template' => 'SMS_001',
    'data' => [
        'code' => 1234'
    ],
], ['yunpian', 'juhe']);  // 这里的网关配置将会覆盖全局默认值
```
#### 返回值

返回值为一个数组
```php
[
    'yunpian' => [
        'status' => 'success',
        'result' => [...] // 平台返回值
    ],
    'juhe' => [
        'status' => 'failure',
        'exception' => \Overtrue\EasySms\Exceptions\GatewayErrorException 对象
    ],
    //...
]
```

#### 自定义网关
```php
$config = [
    ...
    'default' => [
        'gateways' => [
            'mygateway', // 配置你的网站到可用的网关列表
        ],
    ],
    'gateways' => [
        'mygateway' => [...], // 你网关所需要的参数，如果没有可以不配置
    ],
];

$easySms = new EasySms($config);

// 注册
$easySms->extend('mygateway', function($gatewayConfig){
    // $gatewayConfig 来自配置文件里的 `gateways.mygateway`
    return new MyGateway($gatewayConfig);
});

$easySms->send(13188888888, [
    'content'  => '您的验证码为: 6379',
    'template' => 'SMS_001',
    'data' => [
        'code' => 6379
    ],
]);
```

#### 定义短信场景

继承 Overtrue\EasySms\Message 来定义短信模型

```php
<?php

use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

class OrderPaidMessage extends Message
{
    protected $order;
    protected $strategy = OrderStrategy::class;           // 定义本短信的网关使用策略，覆盖全局配置中的 `default.strategy`
    protected $gateways = ['alidayu', 'yunpian', 'juhe']; // 定义本短信的适用平台，覆盖全局配置中的 `default.gateways`

    public function __construct($order)
    {
        $this->order = $order;
    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null)
    {
        return sprintf('您的订单:%s, 已经完成付款', $this->order->no);    
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {
        return 'SMS_003';
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        return [
            'order_no' => $this->order->no    
        ];    
    }
}
```

发送自定义短信:
```php
$order = '';
$message = new OrderPaidMessage($order);
$easySms->send($phone, $message);
```

## Laravel 实际应用(云片)

#### 安装
`composer require "overtrue/easy-sms"`

#### 配置
`config/easysms.php`
```php
<?php
return [
    // HTTP 请求的超时时间（秒）
    'timeout' => 5.0,

    // 默认发送配置
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'yunpian',
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'yunpian' => [
            'api_key' => env('YUNPIAN_API_KEY'),
        ],
    ],
];
```
创建 ServiceProvider
`php artisan make:provider EasySmsServiceProvider`
修改文件
app/providers/EasySmsServiceProvider.php
```php
<?php

namespace App\Providers;

use Overtrue\EasySms\EasySms;
use Illuminate\Support\ServiceProvider;

class EasySmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EasySms::class, function ($app) {
            return new EasySms(config('easysms'));
        });

        $this->app->alias(EasySms::class, 'easysms');
    }
}
```
在 config/app.php 中 providers 中增加 `App\Providers\EasySmsServiceProvider::class`
在 .env 中配置 `YUNPIAN_API_KEY`
#### 应用
```php
// 生成 4 位随机数,左侧补0
$code = str_pad(random_int(1,9999), 4, 0, STR_PAD_LEFT);

try {
    $result = $easySms->send($phone, [
        'content' => "【php社区】您的验证码是{$code},如非本人操作,请忽略本短信" // 云片模版内容
    ]);
} catch (\GuzzleHttp\Exception\ClientException $exception) {
    $response = $exception->getResponse();
    $result = json_decode($response->getBody()->getContents(), true);
    return $this->response->errorInternal($result['msg'] ?? '短信发送异常');
}
```
