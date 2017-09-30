<?php

return [
    'alipay' => [
        'sandbox'              => false,
        'app_id'               => '',
        'notify_url'           => '',
        'return_url'           => '',
        'merchant_private_key' => '',
        'alipay_public_key'    => '',
    ],

    'wxpay' => [

        /**
         * 用于标记是否启用沙盒模式
         */
        'sandbox' => false,

        /**
         * 绑定支付的APPID（必须配置，开户邮件中可查看）
         */
        'app_id' => '',

        /**
         * 商户号（必须配置，开户邮件中可查看）
         */
        'mch_id' => '',

        /**
         * 商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
         * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
         */
        'key' => '',

        /**
         * 公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
         * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
         */
        'app_secret' => '',

        /**
         * 证书路径,注意应该填写绝对路径
         *
         * （仅退款、撤销订单时需要，可登录商户平台下载，API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
         */
        'ssl_cert_path' => '',
        'ssl_key_path' => '',

        /**
         * 支付状态异步通知地址
         */
        'notify_url' => '',
    ],
];
