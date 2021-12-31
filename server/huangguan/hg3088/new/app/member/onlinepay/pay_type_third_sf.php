<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
include "../include/httprequest.php";

$uid=httprequest::param('uid');
$mtype=httprequest::param('mtype');
$langx=httprequest::param('langx');
$iPayid = httprequest::param('payid');
$fOrderAmount = httprequest::param('order_amount');


require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 第三方支付
$sWhere = " 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$sWhere .= ' AND `id` = '. $iPayid .' AND `status` = 1 ';
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE '.$sWhere .' limit 1';
$oRes = mysql_query($sSql);
$iCou=mysql_num_rows($oRes);
$aRow = mysql_fetch_array($oRes);

if ($iCou > 0){

    //--------------------------保存redis Start
    $oRedis = new memcachedb();

    $oRedis->setDatabase('1');
    $oRedisKey = 'sf' . $aRow['business_code'];
    $aRes = $oRedis->getHashAllByKey($oRedisKey);
    // 准备redis缓存数据，商户号、商户秘钥、中间站秘钥、上分地址、终端号
    $aReRedisData = array(
        'business_code' => $aRow['business_code'],
        'business_pwd' => $aRow['business_pwd'],
        'middle_key' => $aRow['middle_key'],
        'back_url' => $aRow['back_url'],
        'pay_id' => $aRow['pay_id']
    );

    // 检查redis数据，覆盖更新
    if(empty($aRes)){
        $oRedis->setHash($oRedisKey,$aReRedisData );
    }else{
        if ( $aRes['business_code'] != $aRow['business_code'] ||
            $aRes['business_pwd'] != $aRow['business_pwd'] ||
            $aRes['middle_key'] != $aRow['middle_key'] ||
            $aRes['back_url'] != $aRow['back_url'] ||
            $aRes['pay_id'] != $aRow['pay_id']
        ) {
            $oRedis->setHash($oRedisKey,$aReRedisData );
        }
    }
    //-------------------------------保存redis End

    // 订单转中间站 Start

    // 订单转中间站 End
}else{
    exit('支付类型错误，请重新选择');
}
