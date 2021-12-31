<?php

include_once('include/config.inc.php');

$EditDate=date('Y-m-d');//新增日期
$AddDate=date('Y-m-d H:i:s');//新增日期
$alias=$_REQUEST['alias'];// 真实姓名
$phone=$_REQUEST['phone']; //手机
$wechat=$_REQUEST['wechat']; //微信
$username=$_REQUEST['username'];//帐号
$password=$_REQUEST['password'];//密码
$password2=$_REQUEST['password2'];//密码
$address=$_REQUEST['address'];//QQ/Skype
$bank_name=$_REQUEST['bank_name'];//银行名称
$bank_account=$_REQUEST['bank_account'];//银行账号
$bank_address=$_REQUEST['bank_address'];//银行地址
$paypassword=$_REQUEST['paypassword'];// 提款密码
$e_mail='';//邮箱
$ip_addr=getenv("REMOTE_ADDR");//IP
$Competence= '0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,1,1,0,1,' ; // 用于显示该所拥有的权限，代理 D 层级 都是这个

//publicRegValidate($username,'',$password,$password2,$alias,'',$phone,$wechat,'','ad');

// $_REQUEST['appRefer'] 13 苹果，14 安卓
$platform = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ;
if($platform =='13'){ // 苹果
    $online_status = '13' ;
}else if($platform == '14'){ // 安卓
    $online_status = '14' ;
}else{ // web 手机
    $online_status = '1' ;
}


if(!is_username($username)){ // 用户名验证

    $status = '401.2';
    $describe = '用户名不符合规范!';
    original_phone_request_response($status,$describe);

}

if($password !=$password2){

    $status = '401.3';
    $describe = '密码与确认密码不一致!';
    original_phone_request_response($status,$describe);

}
if(strlen($password) >15 || strlen($password)<6){

    $status = '401.4';
    $describe = '密码不符合规范!';
    original_phone_request_response($status,$describe);

}
if(!isTrueName($alias)){ // 真实姓名验证

    $status = '401.5';
    $describe = '真实姓名不符合规范!';
    original_phone_request_response($status,$describe);

}

if(!isPhone($phone)){ // 手机号码验证

    $status = '401.6';
    $describe = '手机号码不符合规范!';
    original_phone_request_response($status,$describe);

}
if(!isWechat($wechat)){ // 微信号码验证

    $status = '401.7';
    $describe = '微信号码不符合规范!';
    original_phone_request_response($status,$describe);

}

if(!$bank_address){ // 银行卡号验证

    $status = '401.8';
    $describe = '开户行地址不符合规范!';
    original_phone_request_response($status,$describe);

}
if(!isBankNumber($bank_account)){ // 银行卡号验证

    $status = '401.9';
    $describe = '银行卡号不符合规范!';
    original_phone_request_response($status,$describe);

}
$sql = "select UserName,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='cdm323'"; // 默认总代理
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$world=$row['UserName'];
$corprator=$row['Corprator']; //  股东 B
$super=$row['Super'];  // 公司 A
$admin=$row['Admin']; // 管理员（？子账号）
$sports=$row['Sports'];
$lottery=$row['Lottery'];

$msql = "select ID from ".DBPREFIX."web_agents_data where UserName='$username'";
$mresult = mysqli_query($dbLink,$msql);
$mcou = mysqli_num_rows($mresult);
if ($mcou>0){

    $status = '401.10';
    $describe = '帐户已经有人使用，请重新注册！';
    original_phone_request_response($status,$describe);

}else{

    /*    // 生成唯一的代理前缀(5位字符串)，方便会员管理
        $sPrefix = make_char(5);
        $sql = "select * from ".DBPREFIX."web_agents_data where Prefix='$sPrefix'";
        $result = mysqli_query($dbLink,$msql);
        $cou = mysqli_num_rows($mresult);
        if( $cou !=0 ){
            $sPrefix = make_char(5);
        }*/

    $sql="insert into ".DBPREFIX."web_agents_data set ";
    $sql.="Level='D',"; // Agent 代理 D  World 总代 C  Corprator 股东 B  Super 公司 A  Admin 管理员（？子账号）
    $sql.="UserName='".$username."',";
    $sql.="LoginName='".$username."',";
    $sql.="PassWord='".passwordEncryption($password,$username)."',";
    $sql.="PassWord_Safe='".$paypassword."',";
    $sql.="Credit='0',";
    $sql.="Alias='".$alias."',";
    $sql.="AddDate='".$AddDate."',";
    $sql.="EditDate='".$EditDate."',";
    $sql.="Status='0',"; // 0 默认开启，原来是1
    $sql.="LineType='".$linetype."',";
    $sql.="wager='1',";
    $sql.="UseDate='0',";
    $sql.="A_Point='100',";
    $sql.="B_Point='0',";
    $sql.="C_Point='0',";
    $sql.="D_Point='0',";

    //$sql.="Prefix='".$sPrefix."',";
    $sql.="World='".$world."',";
    $sql.="Corprator='".$corprator."',";
    $sql.="Super='".$super."',";
    $sql.="Admin='".$admin."',";
    $sql.="Bank_Name='".$bank_name."',";
    $sql.="Bank_Address='".$bank_address."',";
    $sql.="Bank_Account='".$bank_account."',";
    $sql.="E_Mail='".$e_mail."',";
    $sql.="Phone='".$phone."',";
    $sql.="wechat='".$wechat."',";
    $sql.="Address='".$address."',";
    $sql.="RegisterIP='".$ip_addr."',";
    $sql.="regSource='".$online_status."',";
    $sql.="Competence='".$Competence."',";
    $sql.="Reg='1';";


    $agents_in = mysqli_query($dbMasterLink,$sql);
    if (!$agents_in){

        $status = '401.11';
        $describe = '操作失败!!!';
        original_phone_request_response($status,$describe);

    }

    $mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$world'";
    $agents_up = mysqli_query($dbMasterLink,$mysql);
    if (!$agents_up){

        $status = '401.12';
        $describe = '操作失败!!';
        original_phone_request_response($status,$describe);

    }
    $agentUrl = returnAgentUrl().'/m?actionType=login_ad&level=D&UserName='.$username.'&PassWord='.$password; // 手机代理登录链接
    $status = '200';
    $describe = '代理注册成功！';
    $data['agentUrl'] = $agentUrl;
    $data['username'] = $username;
    $data['password'] = $password;
    $data['alias'] = $alias;
    $data['bank_address'] = $bank_address;
    $data['bank_account'] = $bank_account;
    $data['phone'] = $phone;

    original_phone_request_response($status,$describe,$data);

}
