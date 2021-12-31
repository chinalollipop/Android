<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
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
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$date=date("Y-m-d");

if ($rtype=='p3'){
    $tab_id="id=pr";
}else{
    $tab_id="id=game_table";
}
		  	 
switch ($rtype){
case "r":
	$caption=$Straight;
	$show="OU";
	$table='<tr>
              <th class="time">'.$Times.'</th>
              <th class="team">'.$U_01.'</th>
              <th class="h_1x2">'.$WIN.'</th>
              <th  class="h_r">'.$Handicap.'</th>
              <th  class="h_ou">'.$Over_Under.'</th>
              <th  class="h_oe">'.$O_E.'</th>
              <th  class="h_r">'.$U_24.'</th>
              <th  class="h_ou">'.$U_25.'</th>
            </tr>';
    break;
case "re":
	$caption=$Running_Ball;
	$show="RE";
	$table='<tr> 
			<th  class="time_re">'.$Times.'</th>
			<th class="team">'.$U_01.'</th>
			<th class="h_r">'.$Handicap.'</th>
			<th class="h_ou">'.$OU.'</th>
		    </tr>';
        break;
    case 'p3': // 综合过关
        $table_dif='bd_all' ;// 波胆table 类
        $caption=$Handicap_Parlay;
        $show="PR";
        $width=55;
        $tab_id="";
        $upd_msg=$Mix_Parlay_maximum;
        $table='<tr>
              <th class="bk_h_1x1">'.$gametitle.'</th>
       
              <th class="h_1x2">'.$WIN.'</th>
              <th  class="h_r">'.$Handicap.'</th>
              <th  class="h_ou">'.$Over_Under.'</th>
              <th  class="h_oe">'.$O_E.'</th>
            </tr><tr class="bet_correct_title">
            <td colspan="20">'.$U_10.'<span class="maxbet">'.$U_11.'： RMB 1,000,000.00</span>
          </td>
          </tr>';
        break;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php if($rtype=='pr'){?>
<link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php } ?>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<script> 
var minlimit='3';
var maxlimit='10';
</script>
<script> 
var rtype = '<?php echo $rtype?>';
var odd_f_str = 'H,M,I,E';

var Format=new Array();
Format[0]=new Array( 'H','<?php echo $HK_Odds?>','Y');
//Format[1]=new Array( 'M','<?php//=$Malay_Odds?>//','Y');
//Format[2]=new Array( 'I','<?php//=$Indo_Odds?>//','Y');
//Format[3]=new Array( 'E','<?php//=$Euro_Odds?>//','Y');
</script>
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
if (document.all){
    document.onmouseup=new Function("dragapproved=false;");
}
function initializedragie(drop_layers){
    return;
    keep_drop_layers=drop_layers;
    iex=event.clientX
    iey=event.clientY
    eval("tempx="+drop_layers+".style.pixelLeft")
    eval("tempy="+drop_layers+".style.pixelTop")
    dragapproved=true;
    document.onmousemove=drag_dropie;
}
function drag_dropie(){
    if (dragapproved==true){
        eval("document.all."+keep_drop_layers+".style.pixelLeft=tempx+event.clientX-iex");
        eval("document.all."+keep_drop_layers+".style.pixelTop=tempy+event.clientY-iey");
        return false
    }
}</script>
</head> 

<body id="MBS" class="bodyset BSRE body_browse_set" onLoad="onLoad();">
<div id="LoadLayer">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>

          <table id="game_table"  cellspacing="0" cellpadding="0" class="game">
            <?php echo $table?>
            *showDataTR*
          </table>
  </xmp>
</div>
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
        <td class="top"><h1 class="top_h1"><em>棒球 : 滚球</em>
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
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<?php
	break;
	case "r":
?>
<!--   表格资料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="8" class="b_hline">
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
      *MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen">*RATIO_MH*</td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_cen">*RATIO_ODD*</td>
    <td class="b_1stR"><span class="con">*CON_HRH*</span> <span class="ratio">*RATIO_HRH*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUH*</span> <span class="ratio">*RATIO_HOUH*</span></td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen">*RATIO_MC*</td>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_cen">*RATIO_EVEN*</td>
    <td class="b_1stR"><span class="con">*CON_HRC*</span> <span class="ratio">*RATIO_HRC*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUC*</span> <span class="ratio">*RATIO_HOUC*</span></td>
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
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=3dc25b21m6686359l27782827&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">历史讯息</a></p>
        </td>
        </tr-->
      <tr>
        <td class="top"><h1 class="top_h1"><em>今日棒球</em>
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
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<?php
	break;
	case "pr":
?>
<!--   表格资料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="10" class="b_hline">
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
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=3dc25b21m6686359l27782827&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">历史讯息</a></p>
        </td>
        </tr-->
      <tr>
        <td class="top"><h1 class="top_h1"><em>今日棒球 : 综合过关</em><span class="maxbet">单注最高派彩额： RMB 1,000,000.00</span>
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
<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>
<div id="controlscroll" ><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--></td></tr></table></div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
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
