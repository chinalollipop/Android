<?php

$describe = "请联系客服！";
echo( json_encode( array( 'err'=>-1,'msg'=>$describe) ) );
exit;

session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

$redisObj = new Ciredis();

$username=$_REQUEST['username'] ; // 用户名
$flag_action=$_REQUEST["action"]; // 用户判断是哪一级页面
$realname=$_REQUEST['realname'] ; //  真实姓名
$pay_pasd= $_REQUEST["paypassword"]; // 用户输入的支付密码
// $birthday= substr($_REQUEST["birthday"],0,10); // 用户输入的生日
$newpassword = $_REQUEST['newpassword'] ; // 新密码
$md5newpassword = passwordEncryption($newpassword,$username);
$date=date("Y-m-d");
$todaydate=strtotime(date("Y-m-d"));
$editdate=strtotime($editdate);
$time=($todaydate-$editdate)/86400;

$mysql="Select ID,Address,EditDate,Alias,password,Status,loginTimesOfFail,resetTimesOfFail,isAutoFreeze,AutoFreezeDate from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";

$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$userID= $row['ID']; //用户id
$password= $row['password']; // 登录密码
$row_alias= $row['Alias']; // 真实姓名
$pay_password=$row['Address']; // 支付密码
// $row_birthday=substr($row['birthday'],0,10); // 生日
$editdate=$row['EditDate'];
$cou=mysqli_num_rows($result);

// 24小时前自动冻结的自动解冻
if ($row['Status']==1){
    if ($row['isAutoFreeze']==0){
        $describe = "您的账号被冻结，请与在线客服联系。";
        echo( json_encode( array( 'err'=>-1,'msg'=>$describe) ) );
        exit;
    }
    else{
        // 冻结24小时解冻
        if ((time()-strtotime($row['AutoFreezeDate']))>24*60*60){
            $sql="update ".DBPREFIX.MEMBERTABLE." set Status=0,loginTimesOfFail=0,resetTimesOfFail=0,isAutoFreeze=0,AutoFreezeDate='0000-00-00 00:00:00' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }
        else{ // 冻结24小时内
            $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
            echo( json_encode( array( 'err'=>-1,'msg'=>$describe) ) );
            exit;
        }
    }
}

if($flag_action =='t1s'){ // 一级页面
    if($cou==0){ // 帐号不存在
        echo( json_encode( array( 'err'=>-1,'msg'=>"当前帐号不存在，请重新输入!") ) );
        exit;
    }else{ // 帐号存在
        echo( json_encode( array( 'err'=>1,'msg'=>"帐号输入正确!") ) );
        exit;
    }
}else if($flag_action =='t2s'){ // 二级页面
	if($realname !=$row_alias  ){ // 资料不一致
        echo( json_encode( array( 'err'=>-2,'msg'=>"您的真实姓名有误!") ) );
        exit;
    }
    if($pay_pasd != $pay_password  ){ // 资料不一致
        $nowdate = date('Y-m-d H:i:s');
        $iResetTimesOfFail = $row['resetTimesOfFail']+1; // 失败次数
        // 登录失败>=5次 或者 修改密码 >= 3次，自动冻结
        if ($iResetTimesOfFail>=3){
            $sql="update ".DBPREFIX.MEMBERTABLE." set Status=1,resetTimesOfFail=".$iResetTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }else{
            // 小于5次，更新失败次数和时间
            $sql="update ".DBPREFIX.MEMBERTABLE." set resetTimesOfFail=".$iResetTimesOfFail.",isAutoFreeze=1,AutoFreezeDate='".$nowdate."' where UserName='$username'";
            $res = mysqli_query($dbMasterLink,$sql);
        }
        if ($iResetTimesOfFail>=3){
            $status='401.6';
            $describe = "您的账号已被冻结使用24小时，请与在线客服联系。";
            echo(json_encode(array('err' => -2, 'msg' => $describe)));
            exit;
        }
        else {
        	$describe = "取款密码错误,您还有".(3-$iResetTimesOfFail)."次机会";
            echo(json_encode(array('err' => -2, 'msg' => $describe)));
            exit;
        }
    }

	$redisObj->insert($userID.'_changePwd','ON',120);
	echo( json_encode( array( 'err'=>2,'msg'=>"信息提交正确!") ) );
	exit;

}else if($flag_action =='t3s'){ // 三级页面
	$changeStatus=$redisObj->getSimpleOne($userID.'_changePwd');
	if($changeStatus=='ON'){
		$mysql="update ".DBPREFIX.MEMBERTABLE." set PassWord='$md5newpassword',EditDate='$date' , Online=1 , OnlineTime=now() where UserName='$username'";
	    $memberResult = mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
	    // 更改彩票会员密码
	    $cpsql = "UPDATE gxfcy_user SET userpsw='".$md5newpassword."' where hguid=".$row['ID'];
	    // 连接彩票主库
		$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());
	    $updateUserPass = mysqli_query($cpMasterDbLink,$cpsql);//更新彩票用户密码
	    if($memberResult && $updateUserPass) {
	    	$delStatus=$redisObj->delete($userID.'_changePwd');
	    	echo( json_encode( array( 'err'=>3,'msg'=>"您的密码更改成功!") ) );
	    } else {
	        echo( json_encode( array( 'err'=>3,'msg'=>"您的密码更改失败，请重新修改!") ) );
	    }	
	}else{
			echo( json_encode( array( 'err'=>3,'msg'=>"修改密码时间已过期,请重新填写个人信息！") ) );
	}
}
?>
