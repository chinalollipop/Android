<?php
/**
 * 新加坡的USDT汇率接口
 * 会员冲币-0.01  提币+0.01
 * 冲币是会员存款，存款汇率要低，对会员才有好处
 * */

include('../include/config.inc.php');

$rate = returnUsdtRate();
$status=200;
$describe='USDT存款汇率，USDT取款汇率';
exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>$rate],JSON_UNESCAPED_UNICODE));