<?php
session_start();
// 公司卡号入款
// 输入金额，添加记录入库
include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'detail'; // 默认，兼容APP
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
//echo $sSql;die;
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);

if( $iCou==0 ){
    $status = '401.2';
    $describe = '支付方式有误，请重新选择';
    original_phone_request_response($status,$describe);
}
$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    if ($aRow['bankcode'] != 'KSCZ'){
        $aData[]=$aRow;
    }
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$aData);