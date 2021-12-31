<?php
/**
 * config.php
 * Easypay聚合支付系统
 * =========================================================
 * Copy right 2015-2025 Easypay, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.0533hf.com
 *
 * 请尊重开发人员劳动成果，严禁使用本系统转卖、销售或二次开发后转卖、销售等商业行为。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @author : 366131726@qq.com
 * @date : 2019-05-14
 */

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");

// $gateway = 'http://api.0533hf.com';      //网关地址
$gateway = 'http://api.feiligu.cn';      //网关地址

//$merId = '2019051'; //商户号
$merId = '20190956'; //商户号

// $md5Key = 'oqEusKNCynLAQIiYmrXJTHFfaODzkMpP';
$md5Key = 'oWmEUVdgGbaPMIrHztsNQFAZifepnYxj';

//平台公钥
//$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAu/d6Tqx7e17Cgzc7HDhwKB5IJmqTtRbnQX8kScvWRml1LIe2+TBhU9NRAyjh08iF/w3gIznXv3oogMfABsGGGr0sIwXPHS/uAt8zr5k6YKE5GzDFYfwrVI8865pJBAwmkjVPlvxYDueBv6pPj/yPUhJWkYepFMinIL0IBipTbIfNynR/ynHlezWvSkXkhPvW2rwhbKW+wUewl1KXsEC21/PaDuWYjOTIZ0XifRtKS/KU4MWlf8Oj/YezPvkmz5u5i7wfzOLK1QO7LKbGJf/VweREE+FeUpxCwvO42CpbJOzIMAvna467GuxrM2OyPvKApBHBWjcTOZ+RZ+5R7bHe2QIDAQAB';
$publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAy/ek+ePCxPJsRpfFbx98jro9i2L9ks9pk3sHfZYz+QCg+im60ojosaGpJR9SnMEFW8W5WPSQ9L2b1WPyQNWMyOhF7V1MesX1fHNE6yQTo4vrSzbLrMxUJmSkUaqkwmTBIUHEgmF8R4epHTk65VQA2InA9bz/IV/hT5yawLYNm58NbmpvGd5x1fnx8XqXUy4joOeDQnL1Ia0VP2fdS7TfTGM2Ug8jD6MIwKtQAaeM78Bze3GPhe5caOgUQzYB39MQRSGsbN28aqGOVAs10uAVXbJx4OGiHOoO9VZ3u8zLxdHBW4KIAXABdj2QPKXjDtefrSfo1G3OvH1cxP4wOzUYSwIDAQAB';

//商户私钥
$privateKey = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCDE9YVUSCArIeKbfv2cW2TC7GnAMFrSi0C2kUPYMu2VuQLtFIaYGmhoe+Vcp3KmRl/i7cbzWcm8Zce9NJgEoJYhoGmulCgCsiXjXsATWdVsCbnI2/V22j3eE/J1HQBn4RaYzi16PyuFj6EXn7nYILwcsdNyJ+TSmIfNE4aRb7IZWpA090gOUPCNEGSCFVR03N4ImAWU+JN/TGH9V4U9JRfe6+F9sYGQnYCNSK2bGf4ak9jEmJtf5cz5EYB4lYCwne3HlGhdoxQLYmCGapdHyYy3OagZDdZqI8inDnNVZ1DZtBDuD50ePufobbq3Haqohy3JFL+R+axC4MfvjnTHUjhAgMBAAECggEAF91YsPDgVg0TcGSt5ySnqooKgjlk/b+3ijlrMW2SKVSQmIDwIdVD5iWxlSgVCnntZodtgyZWjYSW2w//7XXxPWTf3hc17q2H7+/WYSu2kKdNre+JwQn9hnDlNf30UQdV9Y7zGEufFaL/JuJ9gGrV7Ck5mDPaudplGwFO5wGjxqBNfuk8ScdEJ2jw6StQP6JgLouzExf09j7L+pdSaFeikGLAaDQMRc/fG0TY8bKkFt1Hp4GjVlmizYhfabH/PhRPcurHBKFUmdGgnwnrhCtkYAGHzQPff/i5stH+uH/uZhik0Ee6XbenPc/pdLXgIE5hZt4JTTVJERXZUN8blrP5QQKBgQC/k0SzyPF/RtCa5qeVXWcdvbY9HRiZ9PfwtZ+jrH8gsfUKHYVWT2i19klYKdGNNS8e7/rEBuzfpDkkvXrOM55KgrDVzYQkb4W83dLk8MagVWsS31Qbl7hkcj5s442ds6uKvBMqOOFDOjMMxuVpWi1adoNjZUjhRy2BJfzcOlF0/QKBgQCvKE78TolK0XFgFJaHQCVHuEeK7VLLxrQFJa5FyU4rgMkjq81A0Ru5zNg4QglaFjB+W2DZBAgB/BONWwfSTIiNgtGt9ZSqEBDgR7VWf9mrd4ksm9L+aRwoCcwLrf5ue5WewzEqbdby5M6Kda4AZP+//o9A4pLs53HSzWCjEml6tQKBgDXJQyC26hax6x+SYrqs3qaa/O9rm9ydyn7qf0eUxpyHWiTc5PK2mM99tlnqY8shg3lMJzuiNh8UHHcUO8Zo96gzyTI94TvZk9LS9MMSVrHtSYKi8RpOHpcU/DHWMYx1RVROOZNBJP2a+Xo83WwWxWUAoSLm7O9S7JO19qheN0CBAoGAUjVB9QtLRrFMXALtr2b82E92TI8caAXHog7QX3Ke3K4iOTq+J+i88ZRh+u2LhBucAQIUT2aj94J/Dr4lyp8fDAPVM6dqcfL+aLVfN8zjtaVx3Vz5R2y/yU7n1KeCHgqk65HDNp28391hzvxygT7mBg+M3rA4szXhZ0X7UC4pEW0CgYEAqUzj1HVv2w4BZVgRhybge5oofs2WD/yWu39hNWjQCIE7uASmif6fYcZLyTDZLNRBvvHkSms/nWQ0zlW60I704H5IUJg+xcTq2dWFNpURTJURQicILaLFq8n4n1ZaqKg0IDq5ew1sFaILbSizYpFylfgZ66HFV348yNEjSkQVeUk=';

require 'Rsa.php';
require 'Http.php';
require 'Random.php';

/**
 * 签名算法
 * @param $data         请求数据
 * @param $md5Key       md5秘钥
 * @param $privateKey   商户私钥
 */
function sign($data,$md5Key,$privateKey){
    ksort($data);
    reset($data);
    $arg = '';
    foreach ($data as $key => $val) {
        //空值不参与签名
        if ($val == '' || $key == 'sign') {
            continue;
        }
        $arg .= ($key . '=' . $val . '&');
    }
    $arg = $arg . 'key=' . $md5Key;

    //签名数据转换为大写
    $sig_data = strtoupper(md5($arg));
    //使用RSA签名
    $rsa = new Rsa('', $privateKey);
    //私钥签名
    return $rsa->sign($sig_data);
}

/**
 * 验签
 * @param $data         返回数据
 * @param $md5Key       md5秘钥
 * @param $pubKey       平台公钥
 */
function verify($data,$md5Key,$pubKey){
    //验签
    ksort($data);
    reset($data);
    $arg = '';
    foreach ($data as $key => $val) {
        //空值不参与签名
        if ($val == '' || $key == 'sign') {
            continue;
        }
        $arg .= ($key . '=' . $val . '&');
    }
    $arg = $arg . 'key=' . $md5Key;
    $signData = strtoupper(md5($arg));
    $rsa = new Rsa($pubKey, '');
    if ($rsa->verify($signData, $data['sign']) == 1) {
        return true;
    }
    return false;
}


