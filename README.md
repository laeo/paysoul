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

```

## 支持的接口

|  渠道  |  接口  |  别名  |
| :-----: | :-----: | :-----: |
| 支付宝 | 扫码付 | alipay.scan |
| 微信 | 扫码付 | wxpay.scan |

## 支持的接口操作

- 预下单 `deal`
- 退款 `refund`
- 退款查询 `refundQuery`
- 关闭订单 `close`
- 订单查询 `query`
- 回调校验 `verify`
- 响应回调请求 `respond`
- 处理异步通知 `notify`
