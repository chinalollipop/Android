
<?php
session_start();
require ("../../member/include/config.inc.php");
require ("gb_big5.php");

// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$message=$_POST['message'];
$message_tw=gb2big5($message);
$message_en=$_POST['message_en'];
$mdate=date('Y-m-d');
$level=4;
$mshow=1;
$uid=$_REQUEST["uid"];
$ntime=date("Y-m-d Hhi" ,time()); 
$sql="insert into ".DBPREFIX."web_marquee_data(ndate,message,message_tw,message_en,level,ntime,mshow) values('$mdate','$message','$message_tw','$message_en','$level','$ntime','$mshow')";
	$result=mysqli_query($dbMasterLink,$sql);
if(!$result)
{
echo "找不到该查询";
}
else
{
	//开始
	$sql2 = "select admin from  ".DBPREFIX."web_system_data  where sysuid='$uid'";
$result2 = mysqli_query($dbLink,$sql2);
$row2 = mysqli_fetch_assoc($result2);
if ($row2['admin']){
	
	$agname=$row2['admin'];
	$loginfo='添加历史信息';
}	$ip_addr = get_ip();
$mysql="insert into ".DBPREFIX."web_mem_log_data(username,logtime,context,logip,level) values('$agname',now(),'$loginfo','$ip_addr','2')";
mysqli_query($dbMasterLink,$mysql);
//结束
echo "<script language='javascript'>{alert('添加成功');location.href='system.php';}</script>";
}
?>