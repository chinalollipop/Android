<?php
session_start();
/**
 * 显示支付通道的分类
 * 银行卡线上、银行卡线下、微信第三方、微信扫码、微信转账、支付宝第三方、支付宝扫码、支付宝转账、qq扫码、USDT虚拟币
 */

include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);

}
$user_id = $_SESSION['userid'];
if(!empty($user_id)) {
    $member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
    $member_query = mysqli_query($dbLink,$member_sql);
    $memberinfo = mysqli_fetch_assoc($member_query);
}
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准操作额度分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=3;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = '401.2';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
    }
}

$uid=$_SESSION['Oid'];
$DepositTimes = $_SESSION['DepositTimes']; // 用于限制用户可见存款方式 会员存款次数
// 显示支付通道的分类
$sWhere ="AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$sWhere .= ' AND `status` = 1'; // 开启状态且小于会员存款次数
$sSql = 'SELECT id,account_company,depositNum,has_company_youhui FROM `'.DBPREFIX.'gxfcy_pay` WHERE 1 '.$sWhere." AND `depositNum` <= '$DepositTimes'" ;
$oRes = mysqli_query($dbLink,$sSql);
$iCou1 = mysqli_num_rows($oRes);
$aThird_bank_pay = $aThird_weixin_pay = $aThird_ali_pay = $aThird_qq_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){
    if ($aRow['has_company_youhui']==1){
        $aThird_bank_pay_youhui[] = $aRow;
    }
    else{
        switch ($aRow['account_company']){
            //得到第三方支付银行卡配置
            case 2: $aRow['code'] = 'third_bank_pay'; $aThird_bank_pay[] = $aRow; break;
            //得到第三方支付微信配置
            case 4: $aRow['code'] = 'third_weixin_pay'; $aThird_weixin_pay[] = $aRow; break;
            //得到第三方支付支付宝配置
            case 5: $aRow['code'] = 'third_ali_pay'; $aThird_ali_pay[] = $aRow; break;
            //得到第三方支付QQ扫码配置
            case 6: $aRow['code'] = 'third_qq_pay'; $aThird_qq_pay[] = $aRow; break;
            default: break;
        }
    }
}
$sWhere .= " AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` " ;
// 非第三方通道分类显示
$sSql = "SELECT id,bankcode,bank_name,issaoma,photo_name FROM `".DBPREFIX."gxfcy_bank_data` WHERE 1 ".$sWhere.' order by id ';
// echo $sSql;

$oRes = mysqli_query($dbLink,$sSql);
$iCou2 = mysqli_num_rows($oRes);
$aKscz_pay = $aBank_pay = $aAli_pay = $aAli_saoma_pay = $aWx_saoma_pay = $aYlsm_ysf_saoma_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){

    //得到USDT扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'USDT'){
        $aUsdt_pay[] = $aRow;
    }
    // 快速充值
    if ($aRow['issaoma'] == 0 && $aRow['bankcode'] == 'KSCZ'){
        $aKscz_pay = $aRow;
    }
    //得到银行卡支付
    if ($aRow['issaoma'] == 0 && $aRow['bankcode'] != 'WXSAOMA' && $aRow['bankcode'] != 'ALISAOMA' && $aRow['bankcode'] != 'KSCZ'){
        $aRow['code'] = 'bank_pay';
        $aBank_pay[] = $aRow;
    }
    //得到微信扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'WXSAOMA'){
        $aRow['code'] = 'wx_saoma_pay';
        $aWx_saoma_pay[] = $aRow;
    }
    //得到支付宝扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'ALISAOMA'){
        $aRow['code'] = 'ali_saoma_pay';
        $aAli_saoma_pay[] = $aRow;
    }
    //银联扫码|云闪付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'YLSMYSF'){
        $aYlsm_ysf_saoma_pay[] = $aRow;
    }
}

/**
    id对应的充值方式
    case 0://快速充值
    case 1://银行卡线上
    case 2://公司入款
    case 10://显示公司入款，实际三方网银
    case 3://微信第三方
    case 4://支付宝第三方
    case 5://QQ第三方
    case 6://支付宝扫码
    case 7://微信扫码
    case 8://云闪付
    case 9://USDT虚拟币
 */
$aData=array();
if(!empty($aUsdt_pay)) { // USDT虚拟币
    foreach ($aUsdt_pay as $k =>$v) {
        $aData['usdt_pay'.$k]['id'] = 9;
        $aData['usdt_pay'.$k]['bankid'] = $v['id'];
        $aData['usdt_pay'.$k]['title'] = 'USDT虚拟币';
        $aData['usdt_pay'.$k]['api'] = '/account/deposit_two_usdt.php';
    }
}
if(!empty($aBank_pay)) {
    $aData['bank_pay']['id']=2;
    $aData['bank_pay']['bankid']='';
    $aData['bank_pay']['title']='公司入款';
    $aData['bank_pay']['api']='/account/deposit_two_bank_company_save.php';
}
if(!empty($aThird_bank_pay_youhui)) {
    foreach ($aThird_bank_pay_youhui as $k => $v) {
        $aData['bank_pay_'.$k]['id'] = 10;
        $aData['bank_pay_'.$k]['bankid'] = $v['id'];
        $aData['bank_pay_'.$k]['title'] = '公司入款';
        $aData['bank_pay_'.$k]['api'] = '/account/deposit_two_third_bank_youhui.php';
    }
}

if(DEPOSIT_WITHDRAW_SWITCH && !empty($aKscz_pay)) {// 快速充值链接
    $aData['kscz']['id']=0;
    $aData['kscz']['bankid']='';
    $aData['kscz']['title']='快速充值';
    $aData['kscz']['api']=$aKscz_pay['photo_name'];
}

if(!empty($aThird_bank_pay)) { // 第三方网银
    $aData['third_bank_pay']['id']=1;
    $aData['third_bank_pay']['bankid']='';
    $aData['third_bank_pay']['title']='银行卡线上';
    $aData['third_bank_pay']['api']='/account/deposit_two_third_bank.php';
}

if(!empty($aThird_weixin_pay)){
    $aData['third_weixin_pay']['id']=3;
    $aData['third_weixin_pay']['bankid']='';
    $aData['third_weixin_pay']['title']='微信第三方';
    $aData['third_weixin_pay']['api']='/account/deposit_two_third_wx.php';
}

if(!empty($aThird_ali_pay)){
    $aData['third_ali_pay']['id']=4;
    $aData['third_ali_pay']['bankid']='';
    $aData['third_ali_pay']['title']='支付宝第三方';
    $aData['third_ali_pay']['api']='/account/deposit_two_third_zfb.php';
}

if(!empty($aThird_qq_pay)){
    $aData['third_qq_pay']['id']=5;
    $aData['third_qq_pay']['bankid']='';
    $aData['third_qq_pay']['title']='QQ第三方';
    $aData['third_qq_pay']['api']='/account/deposit_two_third_qq.php';
}

if(!empty($aAli_saoma_pay)){

    foreach ($aAli_saoma_pay as $k =>$v){
        $aData['ali_saoma_pay'.$k]['id']=6;
        $aData['ali_saoma_pay'.$k]['bankid']=$v['id'];
        $aData['ali_saoma_pay'.$k]['title']='支付宝扫码';
        $aData['ali_saoma_pay'.$k]['api']='/account/bank_type_ALISAOMA_api.php';
    }

}

if(!empty($aWx_saoma_pay)){

    foreach ($aWx_saoma_pay as $k => $v){
        $aData[$k]['id']=7;
        $aData[$k]['bankid']=$v['id'];
        $aData[$k]['title']='微信扫码';
        $aData[$k]['api']='/account/bank_type_WESAOMA_api.php';
    }
}

if(!empty($aYlsm_ysf_saoma_pay)){

    foreach ($aYlsm_ysf_saoma_pay as $k =>$v){
        $aData['ylsm_ysf_saoma_pay'.$k]['id']=8;
        $aData['ylsm_ysf_saoma_pay'.$k]['bankid']=$v['id'];
        $aData['ylsm_ysf_saoma_pay'.$k]['title']='银联扫码|云闪付';
        $aData['ylsm_ysf_saoma_pay'.$k]['api']='/account/bank_type_YLSMYSF_api.php';
    }

}

$aData = array_values($aData);

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$aData);
