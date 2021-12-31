<?php
/*
 *  代理新增会员
 * */

include ("../app/agents/include/address.mem.php");
require ("../app/agents/include/config.inc.php");

//checkAdminLogin(); // 同一账号不能同时登陆
$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}

$redisObj = new Ciredis();
$alias_allows_duplicate = getSysConfig('alias_allows_duplicate'); // 验证会员昵称是否重复


$parents=$_SESSION['UserName'];
$uid=$_SESSION['Oid'];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级

$keys=$_REQUEST['keys'];
$username=str_replace(' ','',$_REQUEST["user_count"]);// 帐号
$password=trim($_REQUEST['password']);//密码
$curtype = ($_SESSION['CurType']=='RMB'?$_SESSION['CurType']:$_REQUEST['currency']);//币别
$pay_type=trim($_REQUEST['pay_type']);//现金
$type=trim($_REQUEST['type']);//盘口
$alias=trim($_REQUEST['alias']);// 真实姓名
$pay_password=trim($_REQUEST['pay_password']) ; // 取款密码
$phone= str_replace(' ','',$_REQUEST["phone"]) ; // 手机号码
$wechat= str_replace(' ','',$_REQUEST["wechat"]) ; // 微信号码
$birthday=$_REQUEST['birthday'] ; // 生日
// $country=$_REQUEST['country'] ; // 国家
$AddDate=date('Y-m-d H:i:s');// 新增日期

if ($keys=='add'){
    $loginfo='新增会员';
    if ($parents!=''){
        $loginfo='选择会员上线代理商:'.$parents.'';
        $sql = "select Agents,World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='$parents'";
        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);

        $agents=$row['Agents'];
        $world=$row['World'];
        $corprator=$row['Corprator'];
        $super=$row['Super'];
        $admin=$row['Admin'];
        $sports=$row['Sports'];
        $lottery=$row['Lottery'];

    }else{
        $status = '400.02';
        $describe = '代理商帐号名称不能为空!';
        original_phone_request_response($status,$describe,$resdata);
    }

//    $amysql="select Credit from ".DBPREFIX."web_agents_data where UserName='$parents'";
//    $aresult = mysqli_query($dbLink,$amysql);
//    $arow = mysqli_fetch_assoc($aresult);
//    $acredit=$arow['Credit'];
//
//    $bmysql="select sum(Credit) as Credit from ".DBPREFIX.MEMBERTABLE." where Agents='$parents'";
//    $bresult = mysqli_query($dbLink,$bmysql);
//    $brow = mysqli_fetch_assoc($bresult);
//    $bcredit=$brow['Credit'];
//    $money=$bcredit-$acredit;
//    if ($bcredit>$acredit){
//        $msg = "此新增会员 $username <br>目前代理商 $parents 最大信用额度为 ".number_format($acredit,0)."<br>所属代理商累计信用额度为 ".number_format($bcredit,0)."<br>已超过代理商信用额度 ".number_format($money,0)."<br>请回上一面重新输入";
//
//        exit($msg);
//
//        $loginfo='新增会员失败';
//        $ip_addr = get_ip();
//        $mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$parents',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
//        mysqli_query($dbMasterLink,$mysql);
//        exit();
//    }
    if ($alias_allows_duplicate && !empty($alias)){
        $msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where Alias='$alias'";
        $mresult = mysqli_query($dbLink,$msql);
        $mcou = mysqli_num_rows($mresult);
        if ($mcou>0){
            $status = '400.5';
            $describe = "真实姓名【{$alias}】已存在，请联系在线客服进行处理";
            original_phone_request_response($status,$describe,$aData);
        }
    }
    $mysql="select ID from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
    $result = mysqli_query($dbLink,$mysql);
    $count=mysqli_num_rows($result);
    if ($count>0){
        $status = '400.03';
        $describe = '您输入的帐号 '.$username.' 已经有人使用了!请重新输入会员账号';
        original_phone_request_response($status,$describe,$resdata);
    }else{
        $sql="insert into ".DBPREFIX.MEMBERTABLE." set ";
        $sql.="UserName='".$username."',";
        $sql.="LoginName='".$username."',";
        $sql.="PassWord='".passwordEncryption($password,$username)."',";
        $sql.="Alias='".$alias."',";
        $sql.="birthday='".$birthday."',";
        $sql.="Address='".$pay_password."',";
        $sql.="phone='".$phone."',";
        $sql.="E_Mail='".$wechat."',"; // 这个字段用于微信
        $sql.="Sports='".$sports."',";
        $sql.="Lottery='".$lottery."',";
        $sql.="AddDate='".$AddDate."',";
        $sql.="Status='0',";
        $sql.="CurType='".$curtype."',";
        $sql.="Pay_Type='".$pay_type."',";
        $sql.="Opentype='".$type."',";
        $sql.="regSource='2',";
        $sql.="Agents='".$parents."',";
        $sql.="World='".$world."',";
        $sql.="Corprator='".$corprator."',";
        $sql.="Super='".$super."',";
        $sql.="Admin='".$admin."' ";

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if(mysqli_query($dbMasterLink,$sql)) {
            $userid = mysqli_insert_id($dbMasterLink);

            $loginfo='新增会员:'.$username.' 密码:'.$password.' 名称:'.$alias.' 盘口:'.$type.' 币值:'.$curtype.' 上线代理商:'.$parents.'';
            $mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$parents'";
            if(mysqli_query($dbMasterLink,$mysql)) {
                mysqli_query($dbMasterLink, "COMMIT");

                // 插入管理员日志
//                $ip_addr = get_ip();
//                $logmysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$parents',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
//                mysqli_query($dbMasterLink,$logmysql);

                $status = '200';
                $describe = '恭喜注册已成功！帐号：'.$username.' 密码：'.$password.' 名称：'.$alias;
                original_phone_request_response($status,$describe,$resdata);

            }else {
                mysqli_query($dbMasterLink,"ROLLBACK");

                $status = '500';
                $describe = '操作失败!!';
                original_phone_request_response($status,$describe,$resdata);
            }

        }else {
            mysqli_query($dbMasterLink,"ROLLBACK");

            $status = '500.01';
            $describe = '操作失败!!!';
            original_phone_request_response($status,$describe,$resdata);
        }


    }

}
//else{
//    $ssql="select sum(credit) as credit from ".DBPREFIX.MEMBERTABLE." where Agents='$parents' and Status=0";
//    $sresult = mysqli_query($dbLink,$ssql);
//    $srow = mysqli_fetch_assoc($sresult);
//    $esql="select sum(credit) as credit from ".DBPREFIX.MEMBERTABLE." where Agents='$parents' and Status>0";
//    $eresult = mysqli_query($dbLink,$esql);
//    $erow = mysqli_fetch_assoc($eresult);
//}