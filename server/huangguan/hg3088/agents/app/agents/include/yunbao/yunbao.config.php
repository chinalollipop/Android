<?php
/* *
 * 配置文件
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究新生支付接口使用，只是提供一个参考。
 */
ini_set('date.timezone','Asia/Shanghai'); 
//商户号
$merchantid = "100000181";

//商户RSA私钥 (长度2048)
$yunbao_config['private_key_path']= "../include/yunbao/certs/private_key.pem";

//云宝平台RSA公钥
$yunbao_config['yunbao_public_key_path']= "../include/yunbao/certs/platform_public_key.pem";

//版本代码 
$version = "v1";

//编码
$charset = "UTF-8";

//签名类型  目前只支持 RSA
$signtype = "RSA"; 

//异步通知地址
$notifyUrl = "http://127.0.0.1:8081/notifyUrl";

//提现接口地址
$api_pay = "http://www.vipskpe.cn/gateway/service/pay";

//支付结果查询地址
$api_pay_query = "http://www.vipskpe.cn/gateway/service/query";
?>