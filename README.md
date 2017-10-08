# Paysoul
简单好用的开源支付宝、微信支付SDK。

[![Build Status](https://travis-ci.org/doubear/paysoul.svg?branch=develop)](https://travis-ci.org/doubear/paysoul)

## 安装
正在开发中，可尝试使用下述命令安装。

```bash
composer require doubear/paysoul dev-develop
```

## 用法

```php
use Doubear\Paysoul\Paysoul;

//参照代码仓库中 `config` 目录下的配置文件构造配置信息
$config = [
    //...
];

//使用配置信息创建 Paysoul 实例
$paysoul = new Paysoul($config);

//发起支付宝交易预创建请求
$qrcode = $paysoul->channel('alipay.scan')->deal($orderId, $orderSubject, $orderAmount);

//将 $qrcode 生成二维码，展示给用户扫码即可付款


//处理网关异步通知
$body = file_get_contents('php://input');
$paysoul->channel('alipay.scan')->notify($body, function ($channel, $notify, $exception) {
    if ($exception !== null) {
        //校验回调请求时出现问题
        //记录日志
        info('支付宝扫码付回调异常：' . $exception->getMessage());
        //返回处理失败的响应
        return $channel->respond(false);
    }

    //正常处理业务逻辑，校验订单金额（单位为分）之类
    //最后若处理成功则返回正常响应
    return $channel->respond(true);
});
```

## 支持的接口

|  渠道  |  接口  |  别名  |
| :-----: | :-----: | :-----: |
| 支付宝 | 扫码付 | alipay.scan |
| 微信 | 扫码付 | wxpay.scan |
| 微信 | 手机网站 | wxpay.mweb|

## 支持的接口操作

- 预下单 `deal`
- 退款 `refund`
- 退款查询 `refundQuery`
- 关闭订单 `close`
- 订单查询 `query`
- 回调校验 `verify`
- 响应回调请求 `respond`
- 处理异步通知 `notify`

## 协议
本组建基于 MIT 协议开发，欢迎参与开发、发起合并请求。