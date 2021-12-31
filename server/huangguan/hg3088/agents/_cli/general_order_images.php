<?php
/**
 * 监听订单号，并且自动生成订单号截图
 *
 */

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

//ini_set("display_errors", "on");
///www/huangguan/hg3088/agents
define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/address.mem.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require(CONFIG_DIR."/app/agents/include/define_function.php");

$redisObj = new Ciredis();

//去redis里面取出对应的订单号出来
$gid = $redisObj->popMessage("general_order_image");

//var_dump($gid);   /*array { [0]=> "MGO806157914948559951"}*/
$gid = $gid[0];
if(!$gid) {
    return false;
}

$data = $redisObj->getOne($gid);

$transferResult = transferOrderToImage($data);

// 如果生成图片，清掉redis当前订单号
if($transferResult) { // true
    $redisObj->delete($gid);
} else{
    @error_log("生成订单号".$gid."截图失败!!".PHP_EOL, 3, '/tmp/general_order_images.log');
}


exit();



?>