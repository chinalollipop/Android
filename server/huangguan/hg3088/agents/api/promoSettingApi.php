<?php
/*
 *  代理新增会员
 * */

require ("../app/agents/include/config.inc.php");

include_once "../../common/promosCommon.php";

$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}
$redisObj = new Ciredis();

$table = DBPREFIX."web_promos_rule";
$timeNow = date('Y-m-d H:i:s');
$username=$_SESSION['UserName'];
$uid=$_SESSION['Oid'];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级

$action = $_REQUEST['action'];
$type = $_REQUEST['type'];
$title = $_REQUEST['title'];
$promokey = $_REQUEST['promokey'];
$promotjdate = $_REQUEST['promotjdate'];
$promotjdatesec = $_REQUEST['promotjdatesec'];
$promolqdate = $_REQUEST['promolqdate'];
$promolqdatesec = $_REQUEST['promolqdatesec'];
$promolqdatetime = $_REQUEST['promolqdatetime'];
$promolqdatetimetip = $_REQUEST['promolqdatetimetip'];
$payway = $_REQUEST['payway'];
$discountype = $_REQUEST['discountype'];
$promodeposit = $_REQUEST['promodeposit'];
$promodepositday = $_REQUEST['promodepositday'];
$promodepositdayfirst = $_REQUEST['promodepositdayfirst'];
$promovalid = $_REQUEST['promovalid'];
$promobonus = $_REQUEST['promobonus'];
$usdtbonus = $_REQUEST['usdtbonus'];
$promoprofitable = $_REQUEST['promoprofitable'];
$gametype = $_REQUEST['gametype'];
$gametypedetail = $_REQUEST['gametypedetail'];
$gametypechoose = $_REQUEST['gametypechoose'];
$mergeorsplit = $_REQUEST['mergeorsplit'];

if($userlv !='M'){ // 非管理员
    $status = '400.02';
    $describe = '您没有权限操作!';
    original_phone_request_response($status,$describe,$resdata);
}

$attTime = $redisObj->getSimpleOne('promos_set_flag');
if($attTime) {
    $allowtime = time()-$attTime;
    if($allowtime<5) { // 5 秒
        $status = '400.03';
        $describe = '短时间内请勿重复操作!';
        original_phone_request_response($status,$describe,$resdata);
    }
}
// 时间限制
$redisObj->setOne('promos_set_flag', time());

if($action=='get'){ // 获取数据
        $resdata = returnPromoSet($type);
        $status = '200';
        $describe = '查询数据成功!';
        original_phone_request_response($status,$describe,$resdata);
}else if($action=='set'){ // 设置
    $checksql = "SELECT `ID` FROM " . $table . " WHERE `name`='$type'" ; // 检查是否有对应的活动标识
    $result = mysqli_query($dbLink, $checksql);
    $cou = mysqli_num_rows($result);
    if($cou>0){ // 更新
        $nextsql = "update " .$table. " set operator='$username',leader='$promokey',statisticsDayType='$promotjdate',statisticsDay='$promotjdatesec',receiveDayType='$promolqdate',receiveDay='$promolqdatesec',receiveTime='$promolqdatetime',
                 promolqDatetimeTip='$promolqdatetimetip',Payway='$payway',discounType='$discountype',depositLimits='$promodeposit',depositDays='$promodepositday',depositDaysFirst='$promodepositdayfirst',validBet='$promovalid',bonus='$promobonus',usdtbonus='$usdtbonus',
                 profitable='$promoprofitable',gameType='$gametype',gameTypeDetails='$gametypedetail',gameTypeChoose='$gametypechoose',mergeOrSplit='$mergeorsplit',AddDate='$timeNow' where name='$type'";
    }else{ // 插入
        $nextsql = "insert into " .$table. " set operator='$username',name='$type',title='$title',leader='$promokey',statisticsDayType='$promotjdate',statisticsDay='$promotjdatesec',receiveDayType='$promolqdate',receiveDay='$promolqdatesec',
        receiveTime='$promolqdatetime',promolqDatetimeTip='$promolqdatetimetip',Payway='$payway',discounType='$discountype',depositLimits='$promodeposit',depositDays='$promodepositday',depositDaysFirst='$promodepositdayfirst',validBet='$promovalid',bonus='$promobonus',usdtbonus='$usdtbonus',
        profitable='$promoprofitable',gameType='$gametype',gameTypeDetails='$gametypedetail',gameTypeChoose='$gametypechoose',mergeOrSplit='$mergeorsplit',AddDate='$timeNow'";
    }
    $result = mysqli_query($dbMasterLink, $nextsql);
    if($result){
        $status = '200';
        $describe = '更新数据成功!';
        original_phone_request_response($status,$describe,$resdata);
    }

}
