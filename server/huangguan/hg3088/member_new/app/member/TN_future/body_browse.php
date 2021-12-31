<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
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
$rtype=trim($_REQUEST['rtype']);
$g_date=$_REQUEST['g_date'];
require ("../include/traditional.$langx.inc.php");


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$date=date('Y-m-d');
$date1=date('Y-m-d',time()+24*60*60);
$date2=date('Y-m-d',time()+2*24*60*60);
$date3=date('Y-m-d',time()+3*24*60*60);
$date4=date('Y-m-d',time()+4*24*60*60);
$date5=date('Y-m-d',time()+5*24*60*60);
$date6=date('Y-m-d',time()+6*24*60*60);
$date7=date('Y-m-d',time()+7*24*60*60);
$date8=date('Y-m-d',time()+8*24*60*60);
$date9=date('Y-m-d',time()+9*24*60*60);
$date10=date('Y-m-d',time()+10*24*60*60);

if ($rtype=='r' or $rtype=='re' or $rtype=='pd'){
    $tab_id="id=game_table";
}else{
    $tab_id="";
}
switch ($rtype){
case "r":
	$caption=$Straight;
	$table='<tr>
	        <th class="time">'.$Times.'</th>
            <th class="team">'.$U_01.'</th>
			<th class="h_1x2">'.$WIN.'</th>
            <th class="h_r">'.$U_23.'</th>
            <th class="h_ou">'.$Over_Under.'</th>
            <th class="h_oe">'.$Odd_Even.'</th>
			</tr>';		
    break;
case "re":
	$caption=$Running_Ball;
	$table='<tr>
	        <th class="time_re">'.$Times.'</th>
            <th class="team">'.$U_01.'</th>
            <th class="h_r">'.$U_23.'</th>
            <th class="h_ou">'.$Over_Under.'</th>
			<tr>';
	break;
case "pd":
	$caption=$Correct_Score;
	$upd_msg=$Correct_Score_maximum_TN;
	$table='<tr>
	        <th class="time">'.$Times.'</th>
            <th class="team">'.$U_01.'</th>
            <th class="h_pd">2:0</th>
            <th class="h_pd">2:1</th>
            <th class="h_pd">3:0</th>
            <th class="h_pd">3:1</th>
            <th class="h_pd">3:2</th>
			<tr>';
	break;
case "p":
	$caption=$Parlay;
	$upd_msg=$Mix_Parlay_maximum;
	$table='<tr>
	        <th width=40>'.$Times.'</th>
            <th width=156>'.$Home.'</th>
            <th width=55>&nbsp;</th>
            <th width=55>&nbsp;</th>
            <th width=160>'.$Away.' </th>
			<tr>';
	break;
case "pr":
	$caption=$Handicap_Parlay;
	$upd_msg=$Mix_Parlay_maximum;
	$table='<tr>
	        <th class="time">'.$Times.'</th>
            <th class="team">'.$U_01.'</th>
			<th class="h_1x2">'.$WIN.'</th>
            <th class="h_r">'.$U_23.'</th>
            <th class="h_ou">'.$Over_Under.'</th>
            <th class="h_oe">'.$Odd_Even.'</th>
			</tr>';	
	  break;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php if($rtype=='pr'){ ?>
<link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php } ?>

<script> 
var minlimit='3';
var maxlimit='10';
</script>
<script> 
var DateAry = new Array('<?php echo $date1?>','<?php echo $date2?>','<?php echo $date3?>','<?php echo $date4?>','<?php echo $date5?>','<?php echo $date6?>','<?php echo $date7?>','<?php echo $date8?>','<?php echo $date9?>');
var rtype = '<?php echo $rtype?>';
var odd_f_str = 'H,M,I,E';

var Format=new Array();
Format[0]=new Array( 'H','<?php echo $HK_Odds?>','Y');
//Format[1]=new Array( 'M','<?php//=$Malay_Odds?>//','Y');
//Format[2]=new Array( 'I','<?php//=$Indo_Odds?>//','Y');
//Format[3]=new Array( 'E','<?php//=$Euro_Odds?>//','Y');
</script>

    <script class="language_choose" type="text/javascript" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script>
//在body_browse載入


var ReloadTimeID;
var sel_gtype=parent.sel_gtype;

var keep_drop_layers;
var dragapproved=false;
var iex;
var iey;
var tempx;
var tempy;

</script>

</head>

<body id="MTU" class="bodyset TNR body_browse_set" onLoad="onLoad();">
<div id="LoadLayer">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>
          <table id="game_table"  cellspacing="0" cellpadding="0" class="game">
             <?php echo $table?>
            *showDataTR*
          </table>
  </xmp>
</div>
<div class="div_date_title"> <span id="show_date_opt"></span> </div>
<?php
switch($rtype){
	case "re":
?>	
<!--   表格资料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="4" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table class="rb_box"><tr><td class="rb_time">*SE*</td></tr><tr><td class="rb_score">*SCORE*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
       *MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    
  </tr>
  
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tr>
        <td class="top"><h1 class="top_h1"><em>网球 : 滚球</em>
          </h1></td>
      </tr>
      <tr>
        <td class="mem"><h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="page_no"><span id="pg_txt"></span></td>
                <td id="tool_td">
              
                  <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                    <tr>
                        <td id="fav_btn">
                            <div id="fav_num" title="清空" onClick="chkDelAllShowLoveI();"><!--我的最爱场数--><span id="live_num"></span></div>
                            <div id="showNull" title="无资料" class="fav_null" style="display:none;"></div>
                            <div id="showAll" title="所有赛事" onClick="showAllGame('FT');" style="display:none;" class="fav_on"></div>
                            <div id="showMy" title="我的最爱" onClick="showMyLove('FT');" class="fav_out"></div>
                        </td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒数更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     资料显示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>

    <center>
    <!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table> 
<?php
	break;
	case "r":
?>
<!--   表格资料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="6" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
       *MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">*RATIO_MH*</td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_cen">*RATIO_EOO*</td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen">*RATIO_MC*</td>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_cen">*RATIO_EOE*</td>
  </tr>

  
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tr>
        <td class="top"><h1 class="top_h1"><em>早盘网球</em>
          </h1></td>
      </tr>
      <tr>
        <td class="mem"><h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="page_no"><span id="pg_txt"></span></td>
                <td id="tool_td">
              
                  <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                    <tr>
                        <td id="fav_btn">
                            <div id="fav_num" title="清空" onClick="chkDelAllShowLoveI();"><!--我的最爱场数--><span id="live_num"></span></div>
                            <div id="showNull" title="无资料" class="fav_null" style="display:none;"></div>
                            <div id="showAll" title="所有赛事" onClick="showAllGame('FT');" style="display:none;" class="fav_on"></div>
                            <div id="showMy" title="我的最爱" onClick="showMyLove('FT');" class="fav_out"></div>
                        </td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒数更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     资料显示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>

    <center>
    <!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<?php
	break;
	case "pd":
?>
<!--   表格资料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="7" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen">*DATETIME*</td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*</td>
    <td class="b_cen">*RATIO_H2C0*</td>
    <td class="b_cen">*RATIO_H2C1*</td>
    <td class="b_cen">*RATIO_H3C0*</td>
    <td class="b_cen">*RATIO_H3C1*</td>
    <td class="b_cen">*RATIO_H3C2*</td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen">*RATIO_H0C2*</td>
    <td class="b_cen">*RATIO_H1C2*</td>
    <td class="b_cen">*RATIO_H0C3*</td>
    <td class="b_cen">*RATIO_H1C3*</td>
    <td class="b_cen">*RATIO_H2C3*</td>
  </tr>
  
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tr>
        <td class="top"><h1 class="top_h1"><em>早盘网球 : 赛盘投注</em><span class="maxbet">单注最高派彩额： RMB 1,000,000.00</span>
          </h1></td>
      </tr>
      <tr>
        <td class="mem"><h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="page_no"><span id="pg_txt"></span></td>
                <td id="tool_td">
              
                  <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                    <tr>
                        <td id="fav_btn">&nbsp;</td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒数更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     资料显示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>

    <center>
    <!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<?php
	break;
	case "pr":
?>
<!--   表格资料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="11" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" class="showleg">
        *LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>*TEAM_C*</td>
    <td class="b_rig">&nbsp;</td>
    <td class="b_rig"  id="*GID_RH*"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"  id="*GID_OUC*"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_rig">&nbsp;</td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_rig">&nbsp;</td>
    <td class="b_rig"  id="*GID_RC*"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"  id="*GID_OUH*"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_rig">&nbsp;</td>
  </tr>
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <!--tr>
        <td id="ad">
            <span id="real_msg"></span>
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=7a751d0cm6686359l27775383&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">历史讯息</a></p>
        </td>
        </tr-->
      <tr>
        <td class="top"><h1 class="top_h1"><em>早盘网球 : 综合过关</em><span class="maxbet">单注最高派彩额： RMB 1,000,000.00</span>
            <!--span id="hr_info">秒自动更新</span-->
          </h1></td>
      </tr>
      <tr>
        <td class="mem"><h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="page_no"><span id="pg_txt"></span></td>
                <td id="tool_td">
              
                  <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                    <tr>
                        <td id="fav_btn">&nbsp;</td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒数更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     资料显示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>
    
    <center>
    <!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<?php
	break;
}
?>


<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<!-- 所有玩法弹窗 -->
<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
    <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
</div>
<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>
<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--></td></tr></table></div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/future_old.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    setFontAction();
</script>
</body>
</html>
<!--<div id="copyright">
    版权所有 皇冠 建议您以 IE 5.0 800 X 600 以上高彩模式浏览本站&nbsp;&nbsp;<a id=download title="下载" href="http://www.microsoft.com/taiwan/products/ie/" target="_blank">立刻下载IE</a>
</div>-->
<!--div id="copyright">
    版权所有 建议您以 IE 5.0 800 X 600 以上高彩模式浏览本站&nbsp;&nbsp;<a id=download title="下载" href="http://www.microsoft.com/taiwan/products/ie/" target="_blank">立刻下载IE</a>
</div-->
<!-- ------------------------------ 盘口选择 ------------------------------ -->

<div  id=odd_f_window style="display: none;position:absolute">
<table id="odd_group" width="100" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td class="b_hline" >盘口</td>
        </tr>
        <tr >
            <td class="b_cen" width="100">
                <span id="show_odd_f" ></span></td>
        </tr>
    </table>
</div>

<!-- ------------------------------ 盘口选择 ------------------------------ -->
