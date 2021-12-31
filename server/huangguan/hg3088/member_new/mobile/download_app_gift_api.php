<?php
/**
 * 老会员下载APP免费领取彩金
 * download_app_gift_api.php
 *
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
include_once('include/config.inc.php');
include('include/address.mem.php');

$userid = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$UserName = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];
$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'';
$typearr = array('13','14');
$gold = DOWNLOAD_APP_GIFT_GOLD;
$ip = get_ip();

$status = '502';
$describe = '该活动已暂停，谢谢阁下的支持！';
original_phone_request_response($status,$describe,$data);

//判断终端类型
if(!in_array($appRefer,$typearr)){
    $status = '502.1';
    $describe = '终端参数不正确!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

if( !isset($userid) || $userid == "" ) {
    $status = '401.1';
    $describe = '请先登录!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

$mem_sql = "select Alias,AddDate,layer,Agents from  ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
$mem_res = mysqli_query($dbLink,$mem_sql);
$mem_row = mysqli_fetch_assoc($mem_res);
$AddDate = $mem_row['AddDate']; // 会员注册时间
$realName = $mem_row['Alias']; // 会员真实姓名
$Agents = $mem_row['Agents']; // 会员真实姓名

$sUserlayer = $mem_row['layer'];
// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=4;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = '502.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe,$data);
    }
}

// 校验会员注册日期
/*
if ($AddDate > DOWNLOAD_APP_GIFT_DATE ){
    $status = '401.3';
    $describe = '抱歉，注册日期大于'.DOWNLOAD_APP_GIFT_DATE.'，不能领取此彩金';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}
*/

// 真实姓名是否为空，请先前往我的-提现页面设置提款信息
if (strlen($realName)>0){
    // 校验会员真实姓名是否重复领取
    $sql = "select 1 from ".DBPREFIX."download_app_gift_bill where RealName='$realName'";
    $result = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result);
    if ($cou > 0){
        $status = '401.14';
        $describe = '已领取过彩金，不可重复领取！';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }
}else{
    $status = '401.15';
    $describe = '您好，'.$UserName.'，请先前往我的-提现页面设置提款信息';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}


$sql = "select 1 from ".DBPREFIX."download_app_gift_bill where UserName='$UserName'";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
if ($cou > 0){
    $status = '401.16';
    $describe = '您好，'.$UserName.'，您已领取本彩金，不可重复领取';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

// 校验会员IP（注册IP、登录IP）是否重复领取
//$sql = "select 1 from ".DBPREFIX."download_app_gift_bill where IP='$ip'";
//$result = mysqli_query($dbLink,$sql);
//$cou=mysqli_num_rows($result);
//if ($cou > 0){
//
//    $status = '401.4';
//    $describe = '您好，您的IP:'.$ip.'已领取本彩金，不可重复领取';
//    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
//}

// 存款金额100以上（包含100）【只捞取快速充值、公司入款、第三方】
$sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where Checked=1 and Type='S' and userid='$userid' and (discounType =9 or Payway='N' or `PayType`>0)";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
if ($row['Gold'] >= DOWNLOAD_APP_GIFT_DEPOSIT){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
    if($beginFrom){

                    $DepositTotal = $row['Gold'];
                    $BillAddDate = date("Y-m-d H:i:s");
                    $sqlBill="insert into ".DBPREFIX."download_app_gift_bill set userid='{$userid}',UserName='{$UserName}',RealName='$realName',Agents='$Agents',GiftGold='{$gold}',IP='$ip',MemAddDate='$AddDate',DepositTotal='$DepositTotal',BillAddDate='$BillAddDate',status=2";
                    $resBill = mysqli_query($dbMasterLink,$sqlBill);
                    if ($resBill){
                            mysqli_query($dbMasterLink, "COMMIT");
                            $status = '200';
                            $describe = '彩金将在24小时内自动派发到账!';
                            $data[0]['data_gold'] = $gold;
                            //$data2[0]['balance_hg'] = formatMoney($currency_after);
                            original_phone_request_response($status,$describe,$data);

                    }else{
                        $status = '401.9';
                        $describe = '插入会员彩金记录失败';
                        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                    }

    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        $status = '401.5';
        $describe = '事务开启失败';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
    }

}else{
    $status = '401.4';
    $describe = '未达到存款要求，无法领取!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

