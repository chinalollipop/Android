<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname = $_SESSION['UserName'];
$lv =  $_SESSION['admin_level'] ;
$id=$_REQUEST["id"];
$type=$_REQUEST['type'];
$page=$_REQUEST["page"];
$leaguename = isset($_REQUEST["leaguename"])?$_REQUEST["leaguename"]:'' ;

$action=$_REQUEST['action'];
if ($action=='ok'){ // 变更联盟限制
	$mysql="update ".DBPREFIX."match_league set R='".$_REQUEST['r']."',OU='".$_REQUEST['ou']."',VR='".$_REQUEST['vr']."',VOU='".$_REQUEST['vou']."',M='".$_REQUEST['m']."',VM='".$_REQUEST['vm']."',RB='".$_REQUEST['rb']."',ROU='".$_REQUEST['rou']."',VRB='".$_REQUEST['vrb']."',VROU='".$_REQUEST['vrou']."',RM='".$_REQUEST['rm']."',VRM='".$_REQUEST['vrm']."',EO='".$_REQUEST['eo']."',PD='".$_REQUEST['pd']."',T='".$_REQUEST['t']."',F='".$_REQUEST['f']."',CS='".$_REQUEST['cs']."' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql) or die('操作失败!');
    /* 插入系统日志 */
    $loginfo = $loginname.' 在联盟变更中<font class="red"> 变更了 </font> 联赛名称为 <font class="blue">'.$leaguename.'</font>, id 为 <font class="red">'.$id.'</font> ,type 为 <font class="green">'.$type.'</font> 的赛事联盟' ;
    innsertSystemLog($loginname,$lv,$loginfo);

	echo "<SCRIPT language='javascript'>self.location='./league.php?uid=$uid&langx=$langx&type=$type&page=$page';</script>";
}
$mysql = "select ID,M_League,R,RB,M,EO,OU,ROU,VM,PD,VR,VRB,RM,T,CS,VOU,VROU,VRM,F from ".DBPREFIX."match_league where ID='$id'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    input.za_text{ width: auto;}
</style>
</head>

<body>
<dl class="main-nav"><dt>联盟变更</dt>
    <dd>
        联盟单注限制&nbsp;--&nbsp;<font color="#CC0000">单注修改</font>&nbsp;--&nbsp;<a href="javascript:history.go( -1 );">回上一页</a>
    </dd>
</dl>
<FORM NAME="myFORM" onSubmit="return SubChk();" ACTION="" METHOD=POST>
    <input type="hidden" name="leaguename" value="<?php echo $row["M_League"]?>" />
<div class="main-ui">
<table id="glist_table" class="m_tab" >
  <tr class="m_title" >
    <td>ID</td>
    <td colspan="2">赛事联盟</td>
    <td>类型</td>
    <td>单注限制</td>
    <td>类型</td>
    <td>单注限制</td>
    <td>类型</td>
    <td>单注限制</td>
    <td>类型</td>
    <td>单注限制</td>
  </tr>
  <tr class="m_left" > 
      <td width="45" rowspan="4" align="center"><?php echo $row["ID"]?></td>
      <td colspan="2" rowspan="3"><?php echo $row["M_League"]?></td>
      <td width="80">全场让球</td>
      <td width="80"><input name="r" size="5" maxlength="7" class="za_text" value="<?php echo $row['R'];?>"></td>
      <td width="80">全场滚球让球</td>
      <td width="80"><input name="rb" size="5" maxlength="7" class="za_text" value="<?php echo $row['RB'];?>"></td>
      <td width="80">全场独赢</td>
      <td width="80"><input name="m" size="5" maxlength="7" class="za_text" value="<?php echo $row['M'];?>"></td>
      <td width="80">单双</td>
      <td width="80"><input name="eo" size="5" maxlength="7" class="za_text" value="<?php echo $row['EO'];?>"></td>
    </tr>
  
  <tr class="m_left">
    <td>全场大小球</td>
    <td><input name="ou" size="5" maxlength="7" class="za_text" value="<?php echo $row['OU']?>"></td>
    <td>全场滚球大小</td>
    <td><input name="rou" size="5" maxlength="7" class="za_text" value="<?php echo $row["ROU"]?>"></td>
    <td>上半独赢</td>
    <td><input name="vm" size="5" maxlength="7" class="za_text" value="<?php echo $row['VM'];?>"></td>
    <td>波胆</td>
    <td><input name="pd" size="5" maxlength="7" class="za_text" value="<?php echo $row['PD'];?>"></td>
  </tr>
  <tr class="m_left">
    <td>上半让球</td>
    <td><input name="vr" size="5" maxlength="7" class="za_text" value="<?php echo $row['VR'];?>"></td>
    <td>上半滚球让球</td>
    <td><input name="vrb" size="5" maxlength="7" class="za_text" value="<?php echo $row['VRB'];?>"></td>
    <td>全场滚球独赢</td>
    <td><input name="rm" size="5" maxlength="7" class="za_text" value="<?php echo $row['RM'];?>"></td>
    <td>总入球</td>
    <td><input name="t" size="5" maxlength="7" class="za_text" value="<?php echo $row['T'];?>"></td>
  </tr>
  <tr class="m_left">
    <td width="198" align="right">特殊类</td>
    <td width="80"><input name="cs" size="5" maxlength="7" class="za_text" value="<?php echo $row['CS'];?>"></td>
    <td>上半大小球</td>
    <td><input name="vou" size="5" maxlength="7" class="za_text" value="<?php echo $row['VOU'];?>"></td>
    <td>上半滚球大小</td>
    <td><input name="vrou" size="5" maxlength="7" class="za_text" value="<?php echo $row['VROU'];?>"></td>
    <td>上半滚球独赢</td>
    <td><input name="vrm" size="5" maxlength="7" class="za_text" value="<?php echo $row['VRM'];?>"></td>
    <td>半全场</td>
    <td><input name="f" size="5" maxlength="7" class="za_text" value="<?php echo $row['F'];?>"></td>
  </tr>
  
  <tr class="m_cen">
    <td colspan="11">
        <input type="submit" value=" 提 交 " name="B1" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="reset" value=" 清 除 " name="B2" class="za_button">
  　  </td>
      <input type=hidden value="ok" name=action>
    </tr>
  <tr class="m_cen">
    <td colspan="11">说明</td>
    </tr>
</table>

</div>

</form>

<script language="JavaScript">
    function SubChk(){
        /*if (document.all.m_date.value==''){
            document.all.m_date.focus();
            alert("请输入比赛日期!!");
            return false;
        }
        if (document.all.m_time.value==''){
            document.all.m_time.focus();
            alert("请输入比赛时间!!");
            return false;
        }
        if(!confirm("日期更改为："+document.all.m_date.value+"\n时间更改为："+document.all.m_time.value+"\n\n请确定输入是否正确?")){return false;}*/
    }
</script>

</body>
</html>