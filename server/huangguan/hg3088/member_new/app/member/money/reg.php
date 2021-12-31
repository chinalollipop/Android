<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
$file = str_replace("\\","/",dirname(dirname(__FILE__)));
require ($file."/include/config.inc.php");
$agents=$_REQUEST['agents'];
if ($agents==''){
	$agent='huifa';
}else{
	$agent=$agents;
}

$sql = "select World,Corprator,Super,Admin,Sports,Lottery from ".DBPREFIX."web_agents_data where UserName='$agent'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$world=$row['World'];
$corprator=$row['Corprator'];
$super=$row['Super'];
$admin=$row['Admin'];
$sports=$row['Sports'];
$lottery=$row['Lottery'];

$keys=$_REQUEST['keys'];
if ($keys=='add'){
	$AddDate=date('Y-m-d H:i:s');//新增日期
	$alias=$_REQUEST['alias'];//名称
	$phone=$_REQUEST['phone']; //手机
	$username=$_REQUEST['username'];//帐号
	$password=$_REQUEST['password'];//密码
    $password2=$_REQUEST['password2'];// 确认密码
    $notes= isset($_REQUEST['know_site'])?$_REQUEST['know_site']:'';// 备注 notes 替换成 know_site ：3 网络广告，2 比分网，1 朋友推荐， 4 论坛
	$address=$_REQUEST['address'];//QQ/MSN/Email
	$type='C';
	$ip_addr=getenv("REMOTE_ADDR");

$msql = "select UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
$mresult = mysqli_query($dbLink,$msql);
$mcou = mysqli_num_rows($mresult);
if ($mcou>0){
		echo "<script languag='JavaScript'>alert('帐户已经有人使用，请重新注册！');self.location='index.php';</script>";
		exit();
}else{
	
$sql="insert into ".DBPREFIX.MEMBERTABLE." set ";
$sql.="UserName='".$username."',";
$sql.="PassWord='".$password."',";
$sql.="Credit='0',";
$sql.="Money='0',";
$sql.="Alias='".$alias."',";
$sql.="Sports='".$sports."',";
$sql.="Lottery='".$lottery."',";
$sql.="AddDate='".$AddDate."',";
$sql.="Status='0',";
$sql.="CurType='RMB',";
$sql.="Pay_Type='1',";
$sql.="Opentype='".REG_OPEN_TYPE."',";
$sql.="Agents='".$agent."',";
$sql.="World='".$world."',";
$sql.="Corprator='".$corprator."',";
$sql.="Super='".$super."',";
$sql.="Admin='".$admin."',";
$sql.="Phone='".$phone."',";
$sql.="Notes='".$notes."',";
$sql.="Address='".$address."',";
$sql.="RegisterIP='".$ip_addr."',";
$sql.="regSource='0',";
$sql.="Reg='1' ";

$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
if(mysqli_query($dbMasterLink,$sql)) {
	$mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$agent'";
	if(mysqli_query($dbMasterLink,$mysql)) {
		mysqli_query($dbMasterLink, "COMMIT");
	}else {
		mysqli_query($dbMasterLink,"ROLLBACK");
		die ("操作失败!!!");
	}	
}else {
	mysqli_query($dbMasterLink,"ROLLBACK");
	die ("操作失败!!!");
}


}
}
?>
<?php
if ($keys=='add'){
?>
<script languag='JavaScript'>alert('恭喜注册已成功！\n帐号：<?php echo $username?>\n密码：<?php echo $password?>\n名称：<?php echo $alias?>\n手机号码：<?php echo $phone?>\n备注：<?php echo $notes?>');self.location='<?php echo HTTPS_HEAD?>://raw1188.com';</script>
<?php
}
?>