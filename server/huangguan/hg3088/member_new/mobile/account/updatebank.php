<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

include_once('../include/config.inc.php');

$uid=$_SESSION['Oid'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$userid = $_SESSION['userid'];
$chg_bank = trim($_REQUEST["chg_bank"]) ;
$bank_Account = trim($_REQUEST["bank_Account"])  ;
$bank_Address = trim($_REQUEST["bank_Address"]) ;
$usdt_address = trim($_REQUEST["usdt_address"]); // USDT地址
$action = trim($_REQUEST['action']);
$resultData = []; // 初始化返回数据

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
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
        original_phone_request_response($status,$describe,$aData);
    }
}

// s手机版第一次提款绑定银行账号
if(!empty($_REQUEST['bankFlag']) && !empty($chg_bank) && !empty($bank_Account) && !empty($bank_Address)){
    if(!$_REQUEST['bank_Address']){ // 银行地址验证
        $status = '401.2';
        $describe = '开户行地址不符合规范';
        original_phone_request_response($status,$describe);
    }
    if(!isBankNumber($_REQUEST['bank_Account'])){ // 银行卡号验证
        $status = '401.3';
        $describe = '银行卡号不符合规范';
        original_phone_request_response($status,$describe);
    }

    $payPass = '';
    $payPassword1 = trim($_REQUEST['paypassword1']);
    $payPassword2 = trim($_REQUEST['paypassword2']);
    if(!empty($action) && $action == 'add' && $payPassword1){ // 第一次绑定银行卡时需设置提款密码
        // 提款密码验证
        if(!isPayNumber($payPassword1)){
            $status='401.4';
            $describe = "提款密码不符合规范!";
            original_phone_request_response($status,$describe);
        }
        if($payPassword1 != $payPassword2){
            $status = '401.5';
            $describe = '两次输入的提款密码不一致';
            original_phone_request_response($status,$describe);
        }
        $payPass .= ", Address='$payPassword1'";
    }

//    if(!empty($usdt_address)){
//        $usdt_add = '';
//        if(!isUsdtAddress($usdt_address)){
//            $status = '401.31';
//            $describe = '抱歉，您输入的USDT地址不符合规范！';
//            original_phone_request_response($status,$describe);
//        }
//        $usdt_add .= ", Usdt_Address='$usdt_address'";
//    }

    // 数据不为空
    $mysql="update ".DBPREFIX.MEMBERTABLE." set Bank_Name='".$chg_bank."', Bank_Address='".$bank_Address."',Bank_Account='".$bank_Account."'". $payPass . ", Online=1 , OnlineTime=now() where ID='".$_SESSION["userid"]."'";
    $result = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
    if($result){//成功跳转至withdraw.php 输入提款金额
        // 更新session
        $_SESSION['Bank_Name']= $chg_bank ;
        $_SESSION['Bank_Account']= $bank_Account ;
        $_SESSION['Bank_Address']= $bank_Address ;
        if($payPassword1){
            $_SESSION['Address'] = $payPassword1;
        }
        $status = '200';
        $describe = '银行账号信息设置成功！';
        original_phone_request_response($status,$describe);

    }else{// 失败重新绑定
        $status = '500.1';
        $describe = '银行账号设置失败,请重新绑定！';
        original_phone_request_response($status,$describe);
    }
}

$mysql="select ID,UserName,Bank_Name,Bank_Account,Bank_Address,Usdt_Address,owe_bet,owe_bet_time from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<2 LIMIT 1";
$result = mysqli_query($dbMasterLink,$mysql);
$row = mysqli_fetch_assoc($result);

// 会员银行卡信息
$resultData = [
    'UserName' => $row['UserName'],
    'Bank_Name' => $row['Bank_Name'],
    'Bank_Account' => returnBankAccount($row['Bank_Account']),
    'Bank_Account_hide' => returnBankAccount($row['Bank_Account']),
    'Bank_Address' => $row['Bank_Address'],
    'Usdt_Address' => !empty($row['Usdt_Address']) ? returnBankAccount($row['Usdt_Address']) : '',
    'Usdt_Address_hide' => !empty($row['Usdt_Address']) ? returnBankAccount($row['Usdt_Address']) : '',
    'bank_pwd' => $_SESSION['Address']?1:0, // 是否已经设置提款密码
];

// 若会员提款，获取会员打码量信息
if(empty($_REQUEST['bankFlag']) || empty($chg_bank) || empty($bank_Account) || empty($bank_Address)){
    require_once ROOT_DIR.'/common/count/function.php';
    $countTime = (empty($row['owe_bet_time']) || $row['owe_bet_time'] == '0000-00-00 00:00:00' ? '1969-12-31 20:00:00' : $row['owe_bet_time']); // 开始统计时间
    $countData = countBet($countTime, $row['ID']);
    // 会员打码量信息
    $resultData['owe_bet'] = $row['owe_bet'];
    $resultData['total_bet'] = $countData['total'];
    $resultData['bet_list'] = [];

    foreach ($countData as $key => $value){
        if($key != 'total'){
            $resultData['bet_list'][] = [
                'key' => $key,
                'value' => $value,
                'msg' => typeMsg($key),
            ];
        }
    }
}

$status = '200';
$describe = 'success';
original_phone_request_response($status,$describe,$resultData);
