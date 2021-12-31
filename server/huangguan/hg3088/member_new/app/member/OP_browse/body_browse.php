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
require ("../include/traditional.$langx.inc.php");


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$date=date("Y-m-d");
if ($rtype!='p3'){
    $tab_id="id=game_table";
    $tab="id=box";
}else{
    $tab_id="id=p3";
    $tab="id=P3box";
}
		  	 
switch ($rtype){
case "r":
	$caption=$Straight;
	$show="OU";
	$table='<tr>
              <th class="time" nowrap>'.$Times.'</th>
              <th class="team" nowrap>'.$U_01.'</th>
              <th class="h_1x2" nowrap>'.$WIN.'</th>
              <th class="h_r" nowrap>'.$HDP.'</th>
              <th class="h_ou" nowrap>'.$OU.'</th>
              <th class="h_oe" nowrap>'.$O_E.'</th>
            </tr>';
    break;
case "re":
	$caption=$Running_Ball;
	$show="RE";
	$table='<tr>
              <th class="time_re" nowrap>'.$Times.'</th>
              <th class="team" nowrap>'.$U_01.'</th>
              <th class="h_r" nowrap>'.$U_26.'</th>
              <th class="h_ou" nowrap>'.$U_27.'</th>
              <th class="h_r" nowrap>'.$U_28.'</th>
              <th class="h_ou" nowrap>'.$U_29.'</th>
            </tr>';
	break;
case "p3":
	$caption=$Mix_Parlay;
	$show="P3";
	$upd_msg=$Mix_Parlay_maximum;
	$table='<tr>
              <th class="time" nowrap>'.$Times.'</th>
              <th class="team" nowrap>'.$U_01.'</th>
              <th class="h_1x2" nowrap>'.$WIN.'</th>
              <th class="h_r" nowrap>'.$HDP.'</th>
              <th class="h_ou" nowrap>'.$OU.'</th>
              <th class="h_oe" nowrap>'.$O_E.'</th>
            </tr>';
	break;	
}
?>
<script>
var minlimit='0';
var maxlimit='0';
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php if($rtype=='pr'){ ?>
<link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php } ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
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

</script>

</head>
<body id="MOP" class="bodyset OPP3 body_browse_set" onLoad="onLoad();">
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
<!--   表格資料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="6" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table border="0" cellpadding="0" cellspacing="0" class="rb_box"><tr><td class="rb_time">*SE*</td></tr><tr><td class="rb_score">*SCORE*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
      *MYLOVE*<!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_1stR"><span class="con">*CON_HRH*</span> <span class="ratio">*RATIO_HRH*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUH*</span> <span class="ratio">*RATIO_HOUH*</span></td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_1stR"><span class="con">*CON_HRC*</span> <span class="ratio">*RATIO_HRC*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUC*</span> <span class="ratio">*RATIO_HOUC*</span></td>
  </tr>

</xmp>
</div>
<!--右方刷新鈕--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tr>
        <td class="top"><h1 class="top_h1"><em>其他体育 : 滚球</em>
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
                            <div id="fav_num" title="清空" onClick="chkDelAllShowLoveI();"><!--我的最愛場數--><span id="live_num"></span></div>
                            <div id="showNull" title="無資料" class="fav_null" style="display:none;"></div>
                            <div id="showAll" title="所有賽事" onClick="showAllGame('FT');" style="display:none;" class="fav_on"></div>
                            <div id="showMy" title="我的最愛" onClick="showMyLove('FT');" class="fav_out"></div>
                        </td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒數更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     資料顯示的layer     -->
                <div id=showtable></div>

    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>

    <center>
    <!--下方刷新鈕--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<!-- 所有玩法弹窗 -->
<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
    <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
</div>
<?php
	break;
	case "r":		
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="6" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="3" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name none">*TEAM_H*<br>
      *TEAM_C*</td>
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
  <tr id="TR2_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="drawn_td">*MYLOVE*<!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen">*RATIO_MN*</td>
    <td colspan="3" valign="top" class="b_cen"><span class="more_txt">*MORE*</span></td>
    </tr>

</xmp>
</div>
<!--右方刷新鈕--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
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
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=3dc25b21m6686359l27782827&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">歷史訊息</a></p>
        </td>
        </tr-->
      <tr>
        <td class="top"><h1 class="top_h1"><em>今日其他体育</em>
            <!--span id="hr_info">秒自動更新</span-->
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
                            <div id="fav_num" title="清空" onClick="chkDelAllShowLoveI();"><!--我的最愛場數--><span id="live_num"></span></div>
                            <div id="showNull" title="無資料" class="fav_null" style="display:none;"></div>
                            <div id="showAll" title="所有賽事" onClick="showAllGame('FT');" style="display:none;" class="fav_on"></div>
                            <div id="showMy" title="我的最愛" onClick="showMyLove('FT');" class="fav_out"></div>
                        </td>
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒數更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>

    <center>
    <!--下方刷新鈕--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>

<?php
	break;
	case "p3":		
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="6" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
        *LegMark*
       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="3" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name none">*TEAM_H*<br>
      *TEAM_C*</td>
    <td class="b_cen" id="*GID_MH*">*RATIO_MH*</td>
    <td class="b_rig"  id="*GID_RH*"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"  id="*GID_OUC*"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_rig"  id="*GID_EOO*">*RATIO_EOO*</td>
    </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen"  id="*GID_MC*">*RATIO_MC*</td>
    <td class="b_rig"  id="*GID_RC*"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"  id="*GID_OUH*"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_rig"  id="*GID_EOE*">*RATIO_EOE*</td>
    </tr>
  <tr id="TR2_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="drawn_td">*MYLOVE*<!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen"  id="*GID_MN*">*RATIO_MN*</td>
    <td colspan="3" valign="top" class="b_cen"><span class="more_txt">*MORE*</span></td>
    </tr>
</xmp>
</div>
<!--右方刷新鈕--><div id="refresh_right" style="position:absolute;top=-500;" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
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
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=3dc25b21m6686359l27782827&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">歷史訊息</a></p>
        </td>
        </tr-->
      <tr>
        <td class="top"><h1 class="top_h1"><em>其他体育 : 综合过关</em><span class="maxbet">单注最高派彩额： RMB 1,000,000.00</span>
            <!--span id="hr_info">秒自動更新</span-->
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
                        <td class="refresh_btn" id="refresh_btn" onClick="this.className='refresh_on';"><!--秒數更新--><div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div></td>
                        <td class="leg_btn"><div onClick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num"></span>)</div></td>
                        <td class="OrderType" id="Ordertype"></td>
                     </tr>
                  </table>
              
                </td>
              </tr>
            </table>
          </h2>
                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
      <tr>
        <td id="foot"><b>&nbsp;</b></td>
      </tr>
    </table>
    
    <center>
    <!--下方刷新鈕--><div id="refresh_down" class="refresh_M_btn" onClick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
    </center>

</td></tr></table>
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>

    <div id="show_play" style="position: absolute; display:none;">
    <xmp>
        <div class="more">
       <table id="table_team" border="0" cellspacing="1" cellpadding="0" width="100%" class="game" *table_team_sty*>
        <tr>
            <td class="game_team"><tt>*TEAM*</tt><input type="button" value="" *CLS*  class="close"></td>
        </tr>
        </table>    
        <table id="table_hpd" border="0" cellspacing="0" cellpadding="0" width="100%" class="game" *table_hpd_sty*>
            <tr>
                <td class="game_title" colspan="16">上半場波膽</td>
            </tr>
            <tr>
                <th>1:0</th>
                <th>2:0</th>
                <th>2:1</th>
                <th>3:0</th>
                <th>3:1</th>
                <th>3:2</th>
                <th>4:0</th>
                <th>4:1</th>
                <th>4:2</th>
                <th>4:3</th>
                <th>0:0</th>
                <th>1:1</th>
                <th>2:2</th>
                <th>3:3</th>
                <th>4:4</th>
                <th>其他</th>
            </tr>
            <tr class="b_cen">
                <td id="*GID_HH1C0*">*HH1C0*</td>
              <td id="*GID_HH2C0*">*HH2C0*</td>
              <td id="*GID_HH2C1*">*HH2C1*</td>
              <td id="*GID_HH3C0*">*HH3C0*</td>
              <td id="*GID_HH3C1*">*HH3C1*</td>
              <td id="*GID_HH3C2*">*HH3C2*</td>
              <td id="*GID_HH4C0*">*HH4C0*</td>
              <td id="*GID_HH4C1*">*HH4C1*</td>
              <td id="*GID_HH4C2*">*HH4C2*</td>
              <td id="*GID_HH4C3*">*HH4C3*</td>
              <td rowspan="2" id="*GID_HH0C0*">*HH0C0*</td>
                <td rowspan="2" id="*GID_HH1C1*">*HH1C1*</td>
                <td rowspan="2" id="*GID_HH2C2*">*HH2C2*</td>
                <td rowspan="2" id="*GID_HH3C3*">*HH3C3*</td>
                <td rowspan="2" id="*GID_HH4C4*">*HH4C4*</td>
                <td rowspan="2" id="*GID_HOVH*">*HOVH*</td>
            </tr>
            <tr class="b_cen">
                <td id="*GID_HH0C1*">*HH0C1*</td>
                <td id="*GID_HH0C2*">*HH0C2*</td>
                <td id="*GID_HH1C2*">*HH1C2*</td>
                <td id="*GID_HH0C3*">*HH0C3*</td>
                <td id="*GID_HH1C3*">*HH1C3*</td>
                <td id="*GID_HH2C3*">*HH2C3*</td>
                <td id="*GID_HH0C4*">*HH0C4*</td>
                <td id="*GID_HH1C4*">*HH1C4*</td>
                <td id="*GID_HH2C4*">*HH2C4*</td>
                <td id="*GID_HH3C4*">*HH3C4*</td>
            </tr>
        </table>        

        <table id="table_pd" border="0" cellspacing="0" cellpadding="0" width="100%" class="game" *table_pd_sty*>
            <tr>
                <td class="game_title" colspan="16">波膽</td>
          </tr>
            <tr>
                <th>1:0</th>
                <th>2:0</th>
                <th>2:1</th>
                <th>3:0</th>
                <th>3:1</th>
                <th>3:2</th>
                <th>4:0</th>
                <th>4:1</th>
                <th>4:2</th>
                <th>4:3</th>
                <th>0:0</th>
                <th>1:1</th>
                <th>2:2</th>
                <th>3:3</th>
                <th>4:4</th>
                <th>其他</th>
            </tr>
            <tr class="b_cen">
<td id="*GID_H1C0*">*H1C0*</td>
                <td id="*GID_H2C0*">*H2C0*</td>
                <td id="*GID_H2C1*">*H2C1*</td>
                <td id="*GID_H3C0*">*H3C0*</td>
                <td id="*GID_H3C1*">*H3C1*</td>
                <td id="*GID_H3C2*">*H3C2*</td>
                <td id="*GID_H4C0*">*H4C0*</td>
                <td id="*GID_H4C1*">*H4C1*</td>
                <td id="*GID_H4C2*">*H4C2*</td>
                <td id="*GID_H4C3*">*H4C3*</td>
                <td rowspan="2" id="*GID_H0C0*">*H0C0*</td>
                <td rowspan="2" id="*GID_H1C1*">*H1C1*</td>
                <td rowspan="2" id="*GID_H2C2*">*H2C2*</td>
                <td rowspan="2" id="*GID_H3C3*">*H3C3*</td>
                <td rowspan="2" id="*GID_H4C4*">*H4C4*</td>
                <td rowspan="2" id="*GID_OVH*">*OVH*</td>
            </tr>
            <tr class="b_cen">
                <td id="*GID_H0C1*">*H0C1*</td>
                <td id="*GID_H0C2*">*H0C2*</td>
                <td id="*GID_H1C2*">*H1C2*</td>
                <td id="*GID_H0C3*">*H0C3*</td>
                <td id="*GID_H1C3*">*H1C3*</td>
                <td id="*GID_H2C3*">*H2C3*</td>
                <td id="*GID_H0C4*">*H0C4*</td>
                <td id="*GID_H1C4*">*H1C4*</td>
                <td id="*GID_H2C4*">*H2C4*</td>
                <td id="*GID_H3C4*">*H3C4*</td>
</tr>
      </table>
<table id="table_t" border="0" cellspacing="0" cellpadding="0" width="100%" class="game" *table_t_sty*>
            <tr>
                <td class="game_title" colspan="16">總入球</td>
            </tr>
            <tr>    
            
                <th>0 - 1</th>
                <th>2 - 3</th>
                <th>4 - 6</th>
                <th>7或以上</th>
            </tr>
            <tr class="b_cen">
            
                <td id="GID_T01*">*T01*</td>
                <td id="GID_T23*">*T23*</td>
                <td id="GID_T46*">*T46*</td>
                <td id="GID_OVER*">*OVER*</td>
</tr>
      </table>
    
        <table id="table_f" border="0" cellspacing="0" cellpadding="0" width="100%" class="game" *table_f_sty*>
          <tr>
                <td class="game_title" colspan="16">半场 / 全场</td>
          </tr>
          <tr>             
                <th>主 / 主</th>
                <th>主 / 和</th>
                <th>主 / 客</th>
                <th>和 / 主</th>
                <th>和 / 和</th>
                <th>和 / 客</th>
                <th>客 / 主</th>
                <th>客 / 和</th>
                <th>客 / 客</th>
          </tr>
            <tr class="b_cen">
                <td id="*GID_FHH*">*FHH*</td>
                <td id="*GID_FHN*">*FHN*</td>
                <td id="*GID_FHC*">*FHC*</td>
                <td id="*GID_FNH*">*FNH*</td>
                <td id="*GID_FNN*">*FNN*</td>
                <td id="*GID_FNC*">*FNC*</td>
                <td id="*GID_FCH*">*FCH*</td>
                <td id="*GID_FCN*">*FCN*</td>
                <td id="*GID_FCC*">*FCC*</td>
                
            </tr>
        </table>    
        </div>  
   </xmp>
</div>
<div id=showtable_more style="position:absolute; display:none; "></div>
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
<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--></td></tr></table></div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    setFontAction();
</script>
</body>
</html>
<!--<div id="copyright">
    版權所有 皇冠 建議您以 IE 5.0 800 X 600 以上高彩模式瀏覽本站&nbsp;&nbsp;<a id=download title="下載" href="http://www.microsoft.com/taiwan/products/ie/" target="_blank">立刻下載IE</a>
</div>-->
<!--div id="copyright">
    版權所有 建議您以 IE 5.0 800 X 600 以上高彩模式瀏覽本站&nbsp;&nbsp;<a id=download title="下載" href="http://www.microsoft.com/taiwan/products/ie/" target="_blank">立刻下載IE</a>
</div-->
<!-- ------------------------------ 盤口選擇 ------------------------------ -->

<div  id=odd_f_window style="display: none;position:absolute">
<table id="odd_group" width="100" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td class="b_hline" >盤口</td>
        </tr>
        <tr >
            <td class="b_cen" width="100">
                <span id="show_odd_f" ></span></td>
        </tr>
    </table>
</div>

<!-- ------------------------------ 盤口選擇 ------------------------------ -->
