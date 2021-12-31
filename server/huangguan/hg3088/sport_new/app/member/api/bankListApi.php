<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
//header("Content-type: text/html; charset=utf-8");

require ("../include/config.inc.php");

/*
 *  返回公司入款银行信息
 * */
$aData = [];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$bankid = isset($_REQUEST['bankid'])?$_REQUEST['bankid']:'';
$where ='';
switch ($type){
    case 'list': // 获取配置银行列表
        $where .=" id,bank_name ";
        break;
    case 'detail': // 某个银行信息
        $where .=" bank_name,bank_context,bank_account,bank_addres,bank_user,bankcode,photo_name,notice,id ";
        break;
}
$sSql = "select $where from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 AND `issaoma`='0' AND `bankcode`!='KSCZ' AND `bank_name`!='支付宝' AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ".($bankid?" AND id=$bankid":'')." order by sort ASC";
// echo $sSql;
$oRes = mysqli_query($dbLink,$sSql);

$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    $status = '400.01';
    $describe = '支付方式有误，请重新选择~！';
    original_phone_request_response($status,$describe,$aData);
}

while($aRow = mysqli_fetch_assoc($oRes)){
    $aData[]=$aRow;
}
$status = '200';
$describe = '请求数据成功！';
original_phone_request_response($status,$describe,$aData);


?>