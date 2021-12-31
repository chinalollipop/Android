<?php

foreach ( $_REQUEST as $rkey => $rval ) {
    $_REQUEST [$rkey] = trim ( addslashes( stripslashes ( strip_tags ( $rval ) ) ) );
}

$iPayid = $_REQUEST['payid'];
$userid  = $_REQUEST['uid'];
$iHgUserid = $_REQUEST['userid'];
$fOrderAmount = $_REQUEST['order_amount'];
include "../class/config.inc.php";
include "../class/address.mem.php";
include "../class/paytype.php";

$sql = "select ID,UserName as uname,Alias,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Phone,Notes from ".DBPREFIX.MEMBERTABLE." where ID='$iHgUserid' ";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
if($cou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

// 第三方支付
$sSql = 'SELECT * FROM `'.DBPREFIX.'gxfcy_pay` WHERE `id` = '. $iPayid .' AND `status` = 1 limit 1';
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);
if($iCou==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$aRow = mysqli_fetch_assoc($oRes);

$aUser = $row;
$aThirdPay = $aRow;
//print_r($aThirdPay);die;

/**
 * @param  $aThirdPay     当前第三方支付数据
 * @param  $reqData       当前请求参数
 * 返回支付方式 pay_type，支付方式代码 PayCode，第三方简写 sPrifix
 */
$payDatas = CompanyPayType($aThirdPay , $_REQUEST);

$bankCode = $iPayCode = $payDatas['iPayCode'];
$sPrifix = $payDatas['sPrifix'];
$token = $aThirdPay['business_pwd'];

$data['merch_no'] = $aThirdPay['business_code'];  //商户号
$data['paytype'] = 920;  //支付方式如:银行卡（920），支付宝（921）
$orderAmount = $data['price'] = number_format($fOrderAmount,2,'.','');;  //单位：元。精确小数点后2位
$data['notify_url'] = $aThirdPay['url'].'/clzldz_notify_url.php';
$data['order_no'] = $sPrifix.date("YmdHis").rand(100000,999999);;  //订单号
$data['time'] = date('Y-m-d H:i:s');
$data['note'] = $aUser['UserName'].'|'.$iPayid.'|'.$aUser['ID'].'|'.$iPayCode; //会员名称|渠道id|用户Oid|支付方式代码

//将参数按顺序连Token一起用 + 号拼接，做md5-32位加密，取字符串小写。网址类型的参数值不要urlencode（例：merch_no .'+'. price .'+'. paytype .'+'. notify_url .'+'. order_no .'+'. time .'+'. token）
$sign_str =$data['merch_no'].'+'.$data['price'].'+'.$data['paytype'].'+'.$data['notify_url'].'+'.$data['order_no'].'+'.$data['time'].'+'.$token;
$data['sign']=md5($sign_str);
$data['pay_kind']='web_test';
$url="https://clzldz.nwks.site/merchant/pay/newapi";

// 插入一条订单到数据库，方便查询会员三方的订单
//判断终端类型
if ($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) { // 14 原生android，13 原生ios
    $playSource=$_REQUEST['appRefer'];
}else{
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=22;
    }
}
$userip = get_ip();   //支付用户 IP 地址必传，风控需要
$thirdData = [
    'userid' => $userid,
    'UserName' => $aUser['uname'],
    'Alias' => $aUser['Alias'],
    'merchantName' => $aThirdPay['title'],
    'PayType' => strval($aRow['id']), //
    'PayName' => $bankCode, //第三方充值渠道
    'thirdpay_code' => $aRow['thirdpay_code'],
    'Order_Code' => $data['order_no'],
    'thirdSysOrder' => '',
    'Gold' => $orderAmount,
    'UserTime' => date("Y-m-d H:i:s"),
    'SysTime' => '',
    'CallbackTime' => '',
    'AuditTime' => '',
    'Status' => '',
    'playSource' => $playSource,  //判断终端类型
    'ip' => $userip,
    'Remarks' => '',
    'Reviewer' => '',
];
$sInsData = '';
foreach ($thirdData as $key => $value){
    $sInsData.= "`$key` = '{$value}',";
}
$sInsData = rtrim($sInsData, ',');
$sql1 = "insert into `".DBPREFIX."web_thirdpay_data` set $sInsData";
if(mysqli_query($dbMasterLink,$sql1)) {
    $res= http_post_data($url,json_encode($data));
    print_r($res);
}else{
    exit('充值订单入库失败，请重新下单充值');
}


function http_post_data($url, $data_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=utf-8",
            "Content-Length: " . strlen($data_string))
    );
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $return_content;
}











