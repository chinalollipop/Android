<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];

require ("../include/traditional.$langx.inc.php");

$tableName = "web_marquee_data";
$username=$loginname;
$id=$_REQUEST['id'];
$action=$_REQUEST['action'];
$level=$_REQUEST['level'];
$date_start=$_REQUEST['date_start'];

if ($action==1){
	$msg=$_REQUEST['msg_system'];
	$msg_tw=$_REQUEST['msg_system_tw'];
	$msg_en=$_REQUEST['msg_system_en'];
	$date=date('y-m-d');
	$time=$_REQUEST['ntime'];	
	$m=$_REQUEST['member'];
	$d=$_REQUEST['agents'];
	$c=$_REQUEST['world'];
	$b=$_REQUEST['corprator'];
	$a=$_REQUEST['super'];

	if ($m==1){
		$mysql="insert into ".DBPREFIX."$tableName (Level,Message,Message_tw,Message_en,Time,Date,Admin) values ('MEM','$msg','$msg_tw','$msg_en','$time','$date','$username')";
		mysqli_query($dbMasterLink,$mysql);
        getScrollMsg('upd');
	}
	if ($d==1){
		$mysql="insert into ".DBPREFIX."$tableName (Level,Message,Message_tw,Message_en,Time,Date,Admin) values ('D','$msg','$msg_tw','$msg_en','$time','$date','$username')";
		mysqli_query($dbMasterLink,$mysql);
	}
	if ($c==1){
		$mysql="insert into ".DBPREFIX."$tableName (Level,Message,Message_tw,Message_en,Time,Date,Admin) values ('C','$msg','$msg_tw','$msg_en','$time','$date','$username')";
		mysqli_query($dbMasterLink,$mysql);
	}
	if ($b==1){
		$mysql="insert into ".DBPREFIX."$tableName (Level,Message,Message_tw,Message_en,Time,Date,Admin) values ('B','$msg','$msg_tw','$msg_en','$time','$date','$username')";
		mysqli_query($dbMasterLink,$mysql);
	}
	if ($a==1){
		$mysql="insert into ".DBPREFIX."$tableName (Level,Message,Message_tw,Message_en,Time,Date,Admin) values ('A','$msg','$msg_tw','$msg_en','$time','$date','$username')";
		mysqli_query($dbMasterLink,$mysql);
	}
}

if ($level==''){
	$level='MEM';
}
if ($date_start=='') {
	$date_start=date('Y-m-d');
}
$date=$_REQUEST['date'];
$message=$_REQUEST['message'];
$message_tw=$_REQUEST['message_tw'];
$message_en=$_REQUEST['message_en'];

if ($_POST['update']){
	$mysql="update ".DBPREFIX."$tableName set Date='$date',Message='$message',Message_tw='$message_tw',Message_en='$message_en' where id='$id'";
	$result=mysqli_query($dbMasterLink,$mysql);
    getScrollMsg('upd');
}
if ($_POST['delete']){
	$mysql="delete from ".DBPREFIX."$tableName where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
    getScrollMsg('upd');
}
$syssql = "select Msg_System,Msg_System_tw,Msg_System_en from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$syssql);
$sysrow = mysqli_fetch_assoc($result);

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    input.za_text_auto,textarea{ width: 100%;}

</style>
</head>
<body  onLoad="onLoad()";>
<dl class="main-nav"><dt>系统公告</dt><dd></dd></dl>
<div class="main-ui">
<form name="MYFORM"  onSubmit="return SubChk();" action="add_notice.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&action=1" method=post>
<table width="975" border="0" cellpadding="0" cellspacing="1" class="m_tab">

    <TR class="m_title"> 
      <td width=50>语种</td>
      <td>公告内容</td>
    </TR>
    <TR class=m_cen> 
      <td align=center>简体</td>
      <td align=left><input class="za_text_auto"  maxLength=500 size=180 value="<?php echo $sysrow['Msg_System']?>" name=msg_system></td>
    </TR>
    <TR class=m_cen> 
      <td align=center>繁体</td>
      <td align=left><input class="za_text_auto"  maxLength=500 size=180 value="<?php echo $sysrow['Msg_System_tw']?>" name=msg_system_tw></td>
    </TR>
    <TR class=m_cen> 
      <td align=center>英文</td>
      <td align=left><input class="za_text_auto"  maxlength=500 size=180 value="<?php echo $sysrow['Msg_System_en']?>" name=msg_system_en></td>
    </TR>
    <TR class=m_cen>
      <td align=center>发布时间</td>
      <td align=left><input class="za_text_auto"  maxlength=16 size=18 value="<?php echo date('Y-m-d H:i:s')?>" name=ntime></td>
    </TR>
    <TR class=m_cen> 
      <td align=center>选项</td>
      <td align=right>
          <div align="left">
	      会员 
          <input name="member" type="checkbox" value="1" checked>
          代理 
          <input name="agents" type="checkbox" value="1">
          总代理 
          <input name="world" type="checkbox" value="1">
          股东 
          <input name="corprator" type="checkbox" value="1">
		  公司 
          <input name="super" type="checkbox" value="1">
        </div></td>
    </TR>
    <TR class=m_cen> 
      <td></td>
      <td >
          <input class=za_button type=submit value="提交" name=cmdsubmit>
          <input class=za_button type=reset value="取消" name=cmdcancel></td>
    </TR>
	
  </table>
</form>  
<br>
<table class="m_tab">
	<tr class="m_title">
<form name="FrmData" method="post" action="add_notice.php?uid=<?php echo $uid?>&level=<?php echo $level?>&date_start=<?php echo $date_start?>&langx=<?php echo $langx?>">	
	   <td colspan="5" align="center">线上数据－<font color="#CC0000">公告管理&nbsp;</font>
	   <select class=za_select onchange=document.FrmData.submit(); id="level" name="level">
          　<option value="MEM">会员</option>
			<option value="D">代理</option>
			<option value="C">总代理</option>
			<option value="B">股东</option>
			<option value="A">公司</option>
	    </select>
		<select class=za_select onchange=document.FrmData.submit(); id="date_start" name="date_start">
				<option value=""></option> 
<?php
$dd = 24*60*60;
$t = time();
$aa=0;
$bb=0;
for($i=0;$i<=10;$i++)
{
	$today=date('Y-m-d',$t);
	if ($date_start==date('Y-m-d',$t)){
		echo "<option value='$today' selected>".date('Y-m-d',$t)."</option>";	
	}else{
		echo "<option value='$today'>".date('Y-m-d',$t)."</option>";	
	}
$t -= $dd;
}
?>
		</select>
		</td>
		</form>
	</tr>
	<tr class="m_title">
	  <td>日期</td>
	  <td>简体訊息</td>
	  <td>简体訊息</td>
	  <td >简体訊息</td>
	  <td >功能</td>
	</tr>
<?php
$sql = "select ID,Date,Message,Message_tw,Message_en,Level,Time from ".DBPREFIX."$tableName where Level='$level' and Date='$date_start' order by ID desc";
$result = mysqli_query($dbLink,$sql);
while ($row = mysqli_fetch_array($result)){
?>

  <tr>
    <form name="FrmSubmit" method="post" action="add_notice.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&level=<?php echo $level?>&date_start=<?php echo $date_start?>&langx=<?php echo $langx?>">
        <td width="87" align="center"><input name="date" type="text" id="date" value="<?php echo $row['Date']?>" maxlength=10 size="6"></td>
        <td width="245" align="center"><textarea name="message" cols="30" rows="5" id="message"><?php echo trim($row['Message'])?></textarea></td>
        <td align="center"><textarea name="message_tw" cols="30" rows="5" id="message"><?php echo trim($row['Message_tw'])?></textarea></td>
        <td align="left"><textarea name="message_en" cols="30" rows="5" id="message"><?php echo trim($row['Message_en'])?></textarea></td>
        <td width="163" align="center"><input class=za_button name="update" type="Submit" id="update" value="更新"><br><br><input class=za_button name="delete" type="Submit" id="delete" value="删除"><input name="id" type="hidden" id="id" value="<?php echo $row['ID']?>"></td>
    </form>
  </tr>
<?php
}
?>  
</table>
</div>

<script language=javascript>
    function SubChk(){
        if (document.all.msg_system.value==''){
            document.all.msg_system.focus();
            alert("请输入简体公告!!");
            return false;
        }
        if (document.all.msg_system_tw.value==''){
            document.all.msg_system_tw.focus();
            alert("请输入繁体公告!!");
            return false;
        }
        if (document.all.msg_system_en.value==''){
            document.all.msg_system_en.focus();
            alert("请输入英文公告!!");
            return false;
        }
        if(!confirm("简体公告："+document.all.msg_system.value+"\n\n繁体公告："+document.all.msg_system_tw.value+"\n\n英文公告："+document.all.msg_system_en.value+"\n\n请确定输入是否正确?")){return false;}
    }
    function onLoad(){
        var gtype = document.getElementById('level');
        gtype.value = '<?php echo $level?>';
    }
</script>

</body>
</html>
