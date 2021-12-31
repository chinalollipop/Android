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
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];
$action=$_REQUEST['action'];
$gid=$_REQUEST['gid'];
$gtype=$_REQUEST['gtype'];
$rtype=$_REQUEST['rtype'];
$lv=$_REQUEST['lv'];
$page=$_REQUEST['page'];

$sql = "select M_Date,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,M_Time,MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='".$gtype."' and MID='".$gid."'";
$result = mysqli_query($dbLink, $sql);
$row=mysqli_fetch_assoc($result)
?>
<html>
<head>
<title>reports_member</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body >
<dl class="main-nav">
    <dt>注单结算</dt>
    <dd> 线上操盘－<font color="#CC0000">填写比分&nbsp;</font>&nbsp;&nbsp;&nbsp;日期:<?php echo $row['M_Date'] ?> ~ <?php echo $row['M_Date'] ?> -- 下注管道:网路下注 -- <a href="javascript:history.go( -1 );">回上一页</a> </dd>
</dl>
<div class="main-ui">
    <FORM NAME="LAYOUTFORM" onSubmit="return SubChk();" action="../clearing/clearing<?php echo $gtype?>.php?uid=<?php echo $uid?>&gid=<?php echo $gid?>&gtype=<?php echo $gtype?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&page=<?php echo $page?>" method="POST">
<table class="m_tab">
  <tr class="m_title" > 
      <td width="80">时间</td>
      <td width="275">主客队伍</td>
      <td width="70">上半场进球数</td>
      <td width="70">全场进球数</td>
    </tr>
  <tr class="m_rig"> 
    <td align="center" colspan="4"><?php echo $row['M_League']?></td>
    </tr>
  <tr class="m_cen"> 
      <td rowspan="2" width="80" align="center"><?php echo $row['M_Date']?><br>
      <?php echo $row['M_Time']?></td>
      <td width="275" rowspan="2" align="left"><?php echo $row['MB_Team']?><br>
      <?php echo $row['TG_Team']?></td>
      <td width="70" align="center"><input name="mb_inball_v" type="text" class="za_text" onKeyPress="return CheckKey()" value="<?php echo $row['MB_Inball_HR']?>" size="5"></td>
      <td width="70" align="center"><input name="mb_inball" type="text" class="za_text" value="<?php echo $row['MB_Inball']?>" size="5"></td>
    </tr>
  <tr class="m_cen"> 
      <td width="70" align="center"><input name="tg_inball_v" type="text" class="za_text" onKeyPress="return CheckKey()" value="<?php echo $row['TG_Inball_HR']?>"  size="5"></td>
      <td width="70" align="center"><input name="tg_inball" type="text" class="za_text" value="<?php echo $row['TG_Inball']?>"  size="5"></td>
    </tr>
    <tr class="m_cen">
        <td colspan="4">
            <input type="submit" value=" 提 交 " name="subject" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" value=" 清 除 " name="cancel" class="za_button">
        </td>
    </tr>
  </table>

</form>
</div>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script language="JavaScript">
    function SubChk() {

        var $mbhalf = $('input[name="mb_inball_v"]') ;
        var $tghalf = $('input[name="tg_inball_v"]')  ;
        var $mball = $('input[name="mb_inball"]') ;
        var $tgall = $('input[name="tg_inball"]')  ;
        // 可以单独结算 半场 或者 全场
        if(($mbhalf.val()=='' || $tghalf.val()=='') && ($mball.val()=='' || $tgall.val()=='')){
            alert("请输入上半场或者全场的进球数!");
            return false;
        }

        if($mbhalf.length==0 || $tghalf.length==0 || $mball.length==0 || $tgall.length==0){
            if(($mbhalf.val()=='' || $tghalf.val()=='') || ($mball.val()=='' || $tgall.val()=='')){
                alert("请输入上半场或者全场的进球数!!");
                return false;
            }
        }
        if( $mbhalf.val() || $tghalf.val() ){ // 如果半场有值
            if($mbhalf.val()<0){
                $mbhalf.focus();
                alert("请输入主队上半场进球数!!");
                return false;
            }
            if($tghalf.val()<0){
                $tghalf.focus();
                alert("请输入客队上半场进球数!!");
                return false;
            }

        }
        // if( $mball.val() || $tgall.val() ){ // 如果全场有值
        //     if($mball.val()<0){
        //         $mball.focus();
        //         alert("请输入主队全场进球数!!");
        //         return false;
        //     }
        //     if($mball.val()<0){
        //         $tgall.focus();
        //         alert("请输入客队全场进球数!!");
        //         return false;
        //     }
        //
        // }

        var mb_score_all =  $mball.val()?$mball.val():'' ;
        var tg_score_all =  $tgall.val()?$tgall.val():'' ;
        var mb_score_half = $mbhalf.val()?$mbhalf.val():'' ;
        var tg_score_half = $tghalf.val()?$tghalf.val():'' ;

        if(!confirm("主队半场进球数："+mb_score_half+"  主队全场进球数："+mb_score_all+"\n\n客队半场进球数："+tg_score_half+"  客队全场进球数："+tg_score_all+"\n\n请确定输入是否正确?")){return false;}
    }

    function CheckKey(){
        if(event.keyCode == 13) return false;
        if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("进球数只能输入数字!!"); return false;}
        //if (isNaN(event.keyCode) == true)){alert("下注金额仅能输入数字!!"); return false;}
    }
</script>
</body>
</html>
