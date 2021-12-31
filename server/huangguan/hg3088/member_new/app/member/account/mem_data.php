<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select UserName,Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and status<2";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$memname=$row['UserName'];
$credit=$row['Money'];	
?>
<html>
<head>
<title>mem_data</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<script language=javascript>
//function Go_Chg_pass(){
//	Real_Win=window.open("chg_passwd.php?uid=<?php//=$uid?>//","Chg_pass","width=360,height=166,status=no");
//}
function getgrpdomain(){
	Real_Win=window.open("grpdomain.php?uid=<?php echo $uid?>","grpdomain","width=450,height=600,status=no");
}
</script>
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td id="ad">

<span class="real_msg"><marquee scrolldelay="120"><?php echo $mem_msg?></marquee></span>
<p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')"><?php echo $News_History?></a></p>

</td>
  </tr>
  <tr>
    <td class="top">
  	  <h1><em><?php echo $Data?></em>
  	  	<input type="button" name="Submit323" value="<?php echo $Edit_Password?>" onClick="Go_Chg_pass();">
  	  	<input type="button" name="grpdomain" value="<?php echo $Real_news?>" onClick="getgrpdomain();">
  	  	</h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
    <table border="0" cellspacing="1" cellpadding="0" class="game">
      <tr class="b_lef"> 
        <td width="20%"><?php echo $Login_Name?></td>
        <td colspan="6"><?php echo $memname?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Credit_Line?></td>
        <td  colspan="6"><?php echo $credit?></td>
      </tr>
      <form method=post onSubmit="return SubChk()"></form>
      <tr> 
        <td colspan="7" class="b_hline"><?php echo $Single_Game_Limit?></td>
      </tr>
      <tr class="b_lef"> 
        <th>&nbsp;</th>
        <th width="14%"><?php echo $Mem_Soccer?></th>
        <th width="14%"><?php echo $Mem_Baseketball?></th>
        <th width="14%"><?php echo $Mem_Tennis?></th>
        <th width="14%"><?php echo $Mem_VolleyBall?></th>
        <th width="14%"><?php echo $Mem_BaseBall?></th>
        <th width="14%"><?php echo $Mem_Other?></th>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Handicap?></td>
        <td><?php echo FT_R_Scene ?></td>
        <td><?php echo BK_R_Scene ?></td>
        <td><?php echo TN_R_Scene ?></td>
        <td><?php echo VB_R_Scene ?></td>
        <td><?php echo BS_R_Scene ?></td>
        <td><?php echo OP_R_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Over_Under?></td>
        <td><?php echo FT_OU_Scene ?></td>
        <td><?php echo BK_OU_Scene ?></td>
        <td><?php echo TN_OU_Scene ?></td>
        <td><?php echo VB_OU_Scene ?></td>
        <td><?php echo BS_OU_Scene ?></td>
        <td><?php echo OP_OU_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_1_x_2?></td>
        <td><?php echo FT_M_Scene ?></td>
        <td>*</td>
        <td><?php echo TN_M_Scene ?></td>
        <td><?php echo VB_M_Scene ?></td>
        <td><?php echo BS_M_Scene ?></td>
        <td><?php echo OP_M_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Running_Ball?></td>
        <td><?php echo FT_RE_Scene ?></td>
        <td><?php echo BK_RE_Scene ?></td>
        <td><?php echo TN_RE_Scene ?></td>
        <td><?php echo VB_RE_Scene ?></td>
        <td><?php echo BS_RE_Scene ?></td>
        <td><?php echo OP_RE_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_R_B_Over_Under?></td>
        <td><?php echo FT_ROU_Scene ?></td>
        <td><?php echo BK_ROU_Scene ?></td>
        <td><?php echo TN_ROU_Scene ?></td>
        <td><?php echo VB_ROU_Scene ?></td>
        <td><?php echo BS_ROU_Scene ?></td>
        <td><?php echo OP_ROU_Scene ?></td>
      </tr> 
	  <tr class="b_lef">
        <td><?php echo $Mem_R_B_1_x_2?></td>
        <td><?php echo FT_RM_Scene ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>       
      <tr class="b_lef"> 
        <td><?php echo $Mem_1_x_2_Parlay?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo TN_P_Scene ?></td>
        <td><?php echo VB_P_Scene ?></td>
        <td><?php echo BS_P_Scene ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr  class="b_lef">
        <td><?php echo $Mem_Handicap_Parlay?></td>
        <td></td>
        <td><?php echo BK_PR_Scene ?></td>
        <td><?php echo TN_PR_Scene ?></td>
        <td><?php echo VB_PR_Scene ?></td>
        <td><?php echo BS_PR_Scene ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr  class="b_lef">
        <td><?php echo $Mem_Mix_Parlay?></td>
        <td><?php echo FT_PR_Scene ?></td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td><?php echo FT_OP_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Correct_Score?></td>
        <td><?php echo FT_PD_Scene ?></td>
        <td>&nbsp;</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Total_Goals?></td>
        <td><?php echo FT_T_Scene ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo BS_T_Scene ?></td>
        <td><?php echo OP_T_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Odd_Even?></td>
        <td><?php echo FT_EO_Scene ?></td>
        <td><?php echo BK_EO_Scene ?></td>
        <td><?php echo TN_EO_Scene ?></td>
        <td><?php echo VB_EO_Scene ?></td>
        <td><?php echo BS_EO_Scene ?></td>
        <td><?php echo OP_EO_Scene ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Half_Full_Time?></td>
        <td><?php echo FT_F_Scene ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo OP_F_Scene ?></td>
      </tr>
      <tr > 
        <td colspan="7" class="b_hline"><?php echo $Single_Bet_Limit?></td>
      </tr>
      <tr class="b_lef"> 
        <th>&nbsp;</th>
        <th width="14%"><?php echo $Mem_Soccer?></th>
        <th width="14%"><?php echo $Mem_Baseketball?></th>
        <th width="14%"><?php echo $Mem_Tennis?></th>
        <th width="14%"><?php echo $Mem_VolleyBall?></th>
        <th width="14%"><?php echo $Mem_BaseBall?></th>
        <th width="14%"><?php echo $Mem_Other?></th>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Handicap?></td>
        <td><?php echo FT_R_Bet ?></td>
        <td><?php echo BK_R_Bet ?></td>
        <td><?php echo TN_R_Bet ?></td>
        <td><?php echo VB_R_Bet ?></td>
        <td><?php echo BS_R_Bet ?></td>
        <td><?php echo OP_R_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Over_Under?></td>
        <td><?php echo FT_OU_Bet ?></td>
        <td><?php echo BK_OU_Bet ?></td>
        <td><?php echo TN_OU_Bet ?></td>
        <td><?php echo VB_OU_Bet ?></td>
        <td><?php echo BS_OU_Bet ?></td>
        <td><?php echo OP_OU_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_1_x_2?></td>
        <td><?php echo FT_M_Bet ?></td>
        <td>*</td>
        <td><?php echo TN_M_Bet ?></td>
        <td><?php echo VB_M_Bet ?></td>
        <td><?php echo BS_M_Bet ?></td>
        <td><?php echo OP_M_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Running_Ball?></td>
        <td><?php echo FT_RE_Bet ?></td>
        <td><?php echo BK_RE_Bet ?></td>
        <td><?php echo TN_RE_Bet ?></td>
        <td><?php echo VB_RE_Bet ?></td>
        <td><?php echo BS_RE_Bet ?></td>
        <td><?php echo OP_RE_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_R_B_Over_Under?></td>
        <td><?php echo FT_ROU_Bet ?></td>
        <td><?php echo BK_ROU_Bet ?></td>
        <td><?php echo TN_ROU_Bet ?></td>
        <td><?php echo VB_ROU_Bet ?></td>
        <td><?php echo BS_ROU_Bet ?></td>
        <td><?php echo OP_ROU_Bet ?></td>
      </tr> 
	  <tr class="b_lef">
        <td><?php echo $Mem_R_B_1_x_2?></td>
        <td><?php echo FT_RM_Bet ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>       
      <tr class="b_lef"> 
        <td><?php echo $Mem_1_x_2_Parlay?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo TN_P_Bet ?></td>
        <td><?php echo VB_P_Bet ?></td>
        <td><?php echo BS_P_Bet ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr  class="b_lef">
        <td><?php echo $Mem_Handicap_Parlay?></td>
        <td></td>
        <td><?php echo BK_PR_Bet ?></td>
        <td><?php echo TN_PR_Bet ?></td>
        <td><?php echo VB_PR_Bet ?></td>
        <td><?php echo BS_PR_Bet ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr  class="b_lef">
        <td><?php echo $Mem_Mix_Parlay?></td>
        <td><?php echo FT_PR_Bet ?></td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td><?php echo FT_OP_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Correct_Score?></td>
        <td><?php echo FT_PD_Bet ?></td>
        <td>&nbsp;</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
        <td>10000</td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Total_Goals?></td>
        <td><?php echo FT_T_Bet ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo BS_T_Bet ?></td>
        <td><?php echo OP_T_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Odd_Even?></td>
        <td><?php echo FT_EO_Bet ?></td>
        <td><?php echo BK_EO_Bet ?></td>
        <td><?php echo TN_EO_Bet ?></td>
        <td><?php echo VB_EO_Bet ?></td>
        <td><?php echo BS_EO_Bet ?></td>
        <td><?php echo OP_EO_Bet ?></td>
      </tr>
      <tr class="b_lef"> 
        <td><?php echo $Mem_Half_Full_Time?></td>
        <td><?php echo FT_F_Bet ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo OP_F_Bet ?></td>
      </tr>
    </table> 
	</td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>

<div id="copyright"><?php echo $Copyright?></div>
</body>
</html>