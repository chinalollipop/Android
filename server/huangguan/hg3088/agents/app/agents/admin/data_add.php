<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$type=$_REQUEST['type'];
$gtype=$_REQUEST['gtype'];

if ($gtype=='FT'){
	$match='足球';
}else if ($gtype=='BK'){
	$match='篮球';
}else if ($gtype=='TN'){
	$match='网球';
}else if ($gtype=='VB'){
	$match='排球';
}else if ($gtype=='BS'){
	$match='棒球';
}else if ($gtype=='OP'){
	$match='其它';
}
$datetime=date("Y-m-d h:i:s");
$date=date('Y-m-d');

if($type=='data_add'){
	$sql="insert into `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MID='".$_REQUEST['mid']."',Type='".$gtype."',M_Date='".$_REQUEST['date']."',M_Time='".$_REQUEST['time']."',M_Start='".$_REQUEST['start']."',MB_Team='".$_REQUEST['mb_team']."',TG_Team='".$_REQUEST['tg_team']."',MB_Team_tw='".$_REQUEST['mb_team_tw']."',TG_Team_tw='".$_REQUEST['tg_team_tw']."',MB_Team_en='".$_REQUEST['mb_team_en']."',TG_Team_en='".$_REQUEST['tg_team_en']."',M_League='".$_REQUEST['m_league']."',M_League_tw='".$_REQUEST['m_league_tw']."',M_League_en='".$_REQUEST['m_league_en']."'";
	mysqli_query($dbCenterMasterDbLink,$sql);
	echo "<SCRIPT language='javascript'>alert('新增".$match."赛事成功');self.location='play_game.php?uid=$uid&gtype=$gtype&langx=$langx';</script>";
}
?>
<html>
<head>
<title></title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body >
<dl class="main-nav"><dt>赛程添加</dt><dd> 说明：MID是针对赛事的标志，请把相对应的MID填写正确 &nbsp;&nbsp;&nbsp;<a href="javascript:history.go( -1 );">回上一页</a></dd></dl>
<div class="main-ui">
    <table  class="m_tab">
   <form name="MYFORM" onSubmit="return SubChk();" method="post" action="">
        <tr class="m_title">
          <td colspan="2"><font color=red><b><?php echo $match?></b></font>-赛事新增</td>
        </tr>
        <tr class="m_rig_re">
		  <td width="80">MID：</td>
          <td width="327" align="left"><input name="mid" type="text" size="10" maxlength="10"></td>
        </tr>
        <tr class="m_rig_re">
          <td>日期：</td>
          <td align="left"><input name="date" type="text" value="<?php echo $date?>" size="10" maxlength="10"></td>
        </tr>
        <tr class="m_rig_re">
          <td>时间：</td>
          <td align="left"><input name="time" type="text" size="10" maxlength="10"></td>
        </tr>
        <tr class="m_rig_re">
          <td>开赛时间：</td>
          <td align="left"><input name="start" type="text" value="<?php echo $datetime?>" size="15" maxlength="20"></td>
        </tr>
        <tr class="m_rig_re">
          <td rowspan="3">简体队名：</td>
          <td align="left"><input name="m_league" type="text" size="40" maxlength="50">(联盟)</td>
        </tr>
		<tr class="m_rig_re">
          <td align="left"><input name="mb_team" type="text" size="40" maxlength="50">(主队)</td>
        </tr>
        <tr class="m_rig_re">
          <td align="left"><input name="tg_team" type="text" size="40" maxlength="50">(客队)</td>
        </tr>
        <tr class="m_rig_re">
          <td rowspan="3">繁体队名：</td>
		  <td align="left"><input name="m_league_tw" type="text" size="40" maxlength="50">(联盟)</td>
        </tr>
		<tr class="m_rig_re">
          <td align="left"><input name="mb_team_tw" type="text" size="40" maxlength="50">(主队)</td>
        </tr>
        <tr class="m_rig_re">
          <td align="left"><input name="tg_team_tw" type="text" size="40" maxlength="50">(客队)</td>
        </tr>
        <tr class="m_rig_re">
          <td rowspan="3">英文队名：</td>
		  <td align="left"><input name="m_league_en" type="text" size="40" maxlength="50">(联盟)</td>
        </tr>
		<tr class="m_rig_re">
          <td align="left"><input name="mb_team_en" type="text" size="40" maxlength="50">(主队)</td>
        </tr>
        <tr class="m_rig_re">
          <td align="left"><input name="tg_team_en" type="text" size="40" maxlength="50">(客队)</td>
        </tr>
        <tr class="m_rig_re">
          <td colspan="2" align="center"><input class=za_button type="submit" name="Submit" value="提交"></td>
		<input type=hidden value="data_add" name=type>
        </tr>
	</form>	
</table>

</div>
<script language=javascript>
    function SubChk(){
        if (document.all.mid.value==''){
            document.all.mid.focus();
            alert("请输入相对应赛事的MID!!");
            return false;
        }
        if (document.all.date.value==''){
            document.all.date.focus();
            alert("请输入相对应赛事的日期!!");
            return false;
        }
        if (document.all.time.value==''){
            document.all.time.focus();
            alert("请输入相对应赛事的时间!!");
            return false;
        }
        if (document.all.start.value==''){
            document.all.start.focus();
            alert("请输入相对应赛事的开赛时间!!");
            return false;
        }
        if (document.all.m_league.value==''){
            document.all.m_league.focus();
            alert("请输入简体 联盟!!");
            return false;
        }
        if (document.all.mb_Team.value==''){
            document.all.mb_Team.focus();
            alert("请输入简体 主队名!!");
            return false;
        }
        if (document.all.tg_Team.value==''){
            document.all.tg_Team.focus();
            alert("请输入简体 客队名!!");
            return false;
        }
        if(!confirm("赛事 MID："+document.all.mid.value+"\n\n赛事 日期："+document.all.date.value+"\n\n赛事 时间："+document.all.time.value+"\n\n赛事 开赛时间："+document.all.start.value+"\n\n简体 联盟："+document.all.m_league.value+"\n\n简体 主队名："+document.all.mb_Team.value+"\n\n简体 主队名："+document.all.tg_Team.value+"\n\n请确定输入是否正确?")){return false;}
    }
</script>
</body>
</html>
