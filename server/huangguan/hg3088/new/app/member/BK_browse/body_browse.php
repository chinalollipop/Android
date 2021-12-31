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
$table_title='蓝球和美式足球' ;
$bk_dx_title ='球队得分：大/小' ;
switch ($rtype){
    case "r": // 全部
    case "r_main": // 主要盘口
        $caption=$Straight;
        $show="OU";
        $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title .'</th>
            <th nowrap class="h_1x2"> </th>
            <th nowrap class="h_1x2">'.$WIN.'</th>

		    <th class="bk_h_rq" >'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		     <th>'.$OE.'</th>  <!--单双-->
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>
			</tr>'; /* <th class="bk_h_ds">'.$O_E.'</th> */
        break;
    case "rq4":
        $caption=$Straight;
        $show="OU";
        $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th  class="h_r">'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		    		
		    <th class="h_ouhc" colspan="2">球队得分：大/小</th>
			</tr>'; /* <th class="bk_h_ds">'.$O_E.'</th>	 */
        break;
//case "r": // 从滚球切换的时候
        $caption=$Straight;
        $show="OU";
        $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th  class="h_r">'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		   		
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>

			</tr>'; /*  <th class="bk_h_ds">'.$O_E.'</th>	 */
        break;
    case "re": // 滚球
    case "re_main": // 滚球
        $caption=$Running_Ball;
        $show="RE";
        $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title.':'.$Running_Ball.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th  class="bk_h_rq">'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>
			</tr>';
        break;
    case "p3": // 综合过关
        $table_dif='bd_all' ;// 波胆table 类
        $caption=$Handicap_Parlay;
        $show="P3";
        $width=55;
        $tab_id="";
        $upd_msg=$Mix_Parlay_maximum;
        $table='<tr>
		    <th class="bk_future_h" colspan="2">'.$table_title.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th class="bk_h_r">'.$Handicap.'</th>
		    <th class="bk_h_r">'.$Over_Under.'</th>
		    <th class="h_oe">'.$O_E.'</th>
		    <th class="h_oe" colspan="2">'.$bk_dx_title.'</th>
			</tr> <tr class="bet_correct_title">
                <td colspan="20">'.$U_10. ' <span class="maxbet">'.$U_11.'  ： RMB 3,000,000.00</span></td>   
			</tr>';
        break;
}
?>
<?php if($rtype=='p3'){?>

<?php } ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>

<link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php if($rtype=='p3'){?>
<link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php } ?>
</head>

<body id="MFT" class="bodyset BKR body_browse_set <?php echo $table_dif?>" onLoad="onLoad();">
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r' or $rtype=='re' or $rtype =='r_main' or $rtype =='re_main' ){ // 主要盘口、全部盘口才有
            ?>
            <span id="showNull" title="<?php echo $U_16 ?>" class="bet_star_btn_out fav_null" >
            <tt class="bet_star_text">
               0
            </tt>
        </span>

            <span id="showAll" title="<?php echo $U_17 ?>" onClick="showAllGame('FT');" style="display:none;" class="bet_star_btn_all fav_out">
            <tt class="bet_star_All">全部</tt>
            <tt id="live_num_all" class="bet_star_text" style="display: none;"> </tt>
        </span>
            <span id="showMy" title="<?php echo $U_18 ?>" onClick="showMyLove('FT');" style="display:none;" class="bet_star_btn_on">
            <!--我的最爱场数-->
            <tt id="live_num" class="bet_star_text" ></tt>
        </span>

            <?php
        }
        ?>
        <!-- 选择联赛 -->
        <span id="sel_league" onclick="chg_league();" class="bet_league_btn">
            <tt class="bet_normal_text">
               <?php echo $U_19 ?> (<tt id="str_num" class="bet_yellow"></tt>)
            </tt>
        </span>

        <?php

        if($rtype=='r' or $rtype=='re' or $rtype=='r_main' or $rtype=='re_main'){ // 全部才有、主要盘口
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket('main');"><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
            <span id="all_sel_Market" class="bet_view_btn" onclick="chgMarket('all');" style="display: none;"><tt id="all_SpanMarket" class="bet_normal_text">全部盘口</tt></span>
            <span id="sel_filters" class="bet_Special_btn" onclick="show_filters();"><tt id="SpanFilter" class="bet_normal_text">隐藏特殊</tt></span>
            <span id="show_pg_chk" style="display:none;" class="bet_paging"><label><input id="pg_chk" onclick="clickChkbox();" type="checkbox" class="bet_selsect_box" value="C"><span></span><span class="bet_more_chk">分页</span></label></span>
            <div id="show_pg_chk_msg" style="display:none;" class="bet_game_head_i"><div class="bet_head_i_bg"><span class="bet_head_iarrow_text">如您觉得网页运行缓慢,请选分页，<br>这会限制每页显示的比赛场数。</span></div></div>
            <?php
        }
        ?>

    </div>

    <!--右侧按钮-->
    <div class="bet_right">
        <!--<span id="pg_txt" class="bet_page_btn" style="display:none;">
       </span>-->
        <span id="sel_sort" class="bet_sort_time_btn"><tt class="bet_sort_text">排序</tt>
             <div id="show_sort"  class="bet_sort_bg" style="display:none;" tabindex="100">
                <span class="bet_arrow"></span>
                <span class="bet_arrow_text">赛事排序</span>
                <ul id="SortSel" selvalue="T">
                <li id="sort_time" onclick="chgSortValue('T');" class="bet_sort_time_choose">按时间排序</li>
                <li id="sort_leg" onclick="chgSortValue('C');" class="bet_sort_comp">按联盟排序</li>
                </ul>
             </div>
         </span>

        <!--盘口选择 -->
        <?php
        if($rtype=='r' or $rtype=='re' or $rtype =='r_main' or $rtype =='re_main'){ // 只有全部才有
            echo '<span id="sel_odd" class="bet_odds_btn"> </span>' ;
        }
        ?>

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>

</div>
<!-- 球赛展示区顶部 结束-->
<div id="LoadLayer">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>
      <table class="bet_game_top">
          <?php echo $table ?>
      </table>
          <table id="game_table"  cellspacing="0" cellpadding="0" class="game ">

            *showDataTR*
          </table>
  </xmp>
</div> 
<?php switch($rtype){
	case "re": // 滚球
    case "re_main": //滚球主要盘口
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="8" class="b_hline">
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
  <tr class="bet_game_tr_top" id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen">
        <table border="0" cellpadding="0" cellspacing="0" class="rb_box rb_bk_box">
            <tr><td class="rb_time"> *SE* </td></tr> <!--  标题 -->
            <tr><td class="rb_score"> *SCORE* </td></tr> <!-- 比分 -->
        </table>
    </td>
    <td rowspan="2" class="team_name">*TEAM_H* *MYLOVE* <br> <!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
      <span class="bk_team_c">*TEAM_C*</span><span class="more_txt bk_more">*MORE*</span></td>
   <td class="b_cen bc_h self_win_re">*RATIO_MH*</td> <!-- 滚球独赢列 -->
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_1stR b_dy"><span class="con">*CON_OUHO*</span><span class="ratio">*RATIO_OUHO*</span></td> <!-- 滚球得分大小主队大-->
    <td class="b_1stR"><span class="con">*CON_OUHU*</span><span class="ratio">*RATIO_OUHU*</span></td> <!-- 滚球得分大小主队小-->
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen bc_h self_win_re">*RATIO_MC*</td> <!-- 滚球独赢列 -->
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_1stR b_dy"><span class="con">*CON_OUCO*</span><span class="ratio">*RATIO_OUCO*</span></td> <!-- 滚球得分大小客队大-->
    <td class="b_1stR"><span class="con">*CON_OUCU*</span><span class="ratio">*RATIO_OUCU*</span></td> <!-- 滚球得分大小客队小-->
  </tr>
</xmp>
</div>

    <div id=NoDataTR style="display:none;">
       <xmp>
          <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
      </xmp>
    </div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table">
        <tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
  <!--    <tr>
        <td class="top"><h1 class="top_h1"><em>蓝球和美式足球 : 滚球</em>&nbsp;
                  <select id="sel_rtype" onChange="change_RE_session();">
                        <option value="all" selected>所有时节</option>
                        <option value="90">全场</option>
                        <option value="8">上半场</option>
                        <option value="9">下半场</option>
                    </select>
          </h1></td>
      </tr>-->
      <tr>
        <td class="mem">
            <h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
         <!--     <tr>
                <td id="page_no"><span id="pg_txt"></span></td>

              </tr>-->
            </table>
          </h2>
                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>

    </table>

</td></tr>
</table>
<div class="more" id="more_window" name="more_window" >
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>

<!-- 所有玩法弹窗 -->
<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
    <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
</div>

<?php break;
   case "p3":

?>
<!--   表格資料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="10" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" class="showleg">
        *LegMark*
       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr class="bet_game_tr_top" id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>*TEAM_C*</td>
   <td class="b_cen self_win">*RATIO_MH*</td> <!-- 综合过关独赢列 -->
    <td class="b_rig"  id="*GID_RH*"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"  id="*GID_OUC*"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
      <td class="b_rig"><span class="con">*RATIO_EOO*</span></td><!--综合过关新增主队单双-->
      <td class="b_1stR h_dx_big_1"><span class="con">*CON_OUHO*</span><span class="ratio">*RATIO_OUHO*</span></td>  <!-- 综合过关 半场得分大小-->
      <td class="b_1stR h_dx_small_1"><span class="con">*CON_OUHU*</span><span class="ratio">*RATIO_OUHU*</span></td>  <!-- 综合过关 半场得分大小-->
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen self_win">*RATIO_MC*</td> <!-- 综合过关独赢列 -->
    <td class="b_rig"  id="*GID_RC*"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"  id="*GID_OUH*"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
      <td class="b_rig"><span class="con">*RATIO_EOE*</span></td><!--综合过关新增客队单双-->
      <td class="b_1stR h_dx_big_2"><span class="con">*CON_OUCO*</span><span class="ratio">*RATIO_OUCO*</span></td>  <!-- 综合过关 半场得分大小-->
      <td class="b_1stR h_dx_small_2"><span class="con">*CON_OUCU*</span><span class="ratio">*RATIO_OUCU*</span></td>  <!-- 综合过关 半场得分大小-->
  </tr>
</xmp>
</div>

       <div id=NoDataTR style="display:none;">
    <xmp><tr>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td></tr>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table">
    <tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
      <!--tr>
        <td id="ad">
            <span id="real_msg"></span>
        <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=526d836bm6460522l29326314&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">歷史訊息</a></p>
        </td>
        </tr-->
  <!--    <tr>
        <td class="top">
            <h1 class="top_h1"><em>今日蓝球和美式足球 : 综合过关</em><span class="maxbet">单注最高派彩额： RMB 1,000,000.00</span></h1>
        </td>
      </tr>-->
      <tr>
        <td class="mem"><h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
             <!-- <tr>
                <td id="page_no"><span id="pg_txt"></span></td>

              </tr>-->
            </table>
          </h2>
            <!--     資料顯示的layer     -->
            <div id=showtable></div>
    </td>
      </tr>

    </table>

</td></tr></table>
<?php 
   break;
   default: // 默认
?> 
<!--   表格資料     -->
<div id=DataTR style="display:none;">
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="8" class="b_hline">
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
  <tr class="bet_game_tr_top" id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H* *MYLOVE* <br> <!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
      <span class="bk_team_c">*TEAM_C*</span><span class="more_txt bk_more">*MORE*</span></td>
    <td class="b_cen self_win">*RATIO_MH*</td> <!-- 全部 独赢列 -->
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td> <!-- 全部 让球列 -->
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
      <td class="b_rig"><span class="con">*RATIO_EOO*</span></td><!--新增主队单双-->
    <td class="b_1stR df_big_1"><span class="con">*CON_OUHO*</span><span class="ratio">*RATIO_OUHO*</span></td>  <!-- 球队得分大小-->
    <td class="b_1stR df_small_1"><span class="con">*CON_OUHU*</span><span class="ratio">*RATIO_OUHU*</span></td>  <!-- 球队得分大小-->
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen self_win">*RATIO_MC*</td> <!-- 独赢列 -->
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td> <!--让球列 -->
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
      <td class="b_rig"><span class="con">*RATIO_EOE*</span></td><!--新增客队单双-->
    <td class="b_1stR df_big_2"><span class="con">*CON_OUCO*</span><span class="ratio">*RATIO_OUCO*</span></td>  <!-- 球队得分大小-->
    <td class="b_1stR df_small_2"><span class="con">*CON_OUCU*</span><span class="ratio">*RATIO_OUCU*</span></td>  <!-- 球队得分大小-->
  </tr>
</xmp>
</div>

<!-- 篮球赛事 右侧-->

       <div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table">
    <tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">
 <!--     <tr>
        <td class="top"><h1 class="top_h1"><em>今日蓝球和美式足球 : </em>&nbsp;
                  <select id="sel_rtype" onChange="change_rtype();">
                        <option value="all" selected>所有时节</option>
                        <option value="r">单式</option>
                        <option value="rq4">单节</option>
                    </select>
          </h1></td>
      </tr>-->
      <tr>
        <td class="mem">
     <!--       <h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td id="page_no"><span id="pg_txt"></span></td>

              </tr>
            </table>
          </h2>-->
                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>

    </table>

</td></tr>
</table>
<div class="more" id="more_window" name="more_window" >
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>

<!-- 所有玩法弹窗 -->
<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
    <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
</div> 
 
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

<!-- 分页 -->
<div id="show_page_txt" class="bet_page_bot_rt">

</div>

<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var rtype = '<?php echo $rtype?>';
    var odd_f_str = 'H,M,I,E';
    top.today_gmt = '<?php echo $date?>';
    //在body_browse载入
    var ReloadTimeID;
    var sel_gtype=parent.sel_gtype;

    //網頁載入
    function onLoad(){
        top.swShowLoveI=false;
        if((""+eval("parent."+sel_gtype+"_lname_ary"))=="undefined") eval("parent."+sel_gtype+"_lname_ary='ALL'");
        if((""+eval("parent."+sel_gtype+"_lid_ary"))=="undefined") eval("parent."+sel_gtype+"_lid_ary='ALL'");
        if(parent.ShowType==""||rtype=="r") parent.ShowType = 'OU';
        if(rtype=="hr") parent.ShowType = 'OU';
        if(rtype=="re") parent.ShowType = 'RE';
        if(rtype=="pd") parent.ShowType = 'PD';
        if(rtype=="hpd") parent.ShowType = 'HPD';
        if(rtype=="t") parent.ShowType = 'EO';
        if(rtype=="f") parent.ShowType = 'F';
        if(parent.parent.leg_flag=="Y"){
            parent.parent.leg_flag="N";
            parent.pg=0;
            reload_var();
        }
        parent.loading = 'N';
        if(parent.loading_var == 'N'){
            parent.ShowGameList();
            obj_layer = document.getElementById('LoadLayer');
            obj_layer.style.display = 'none';
        }
        if (parent.retime_flag == 'Y'){
            //ReloadTimeID = setInterval("reload_var()",parent.retime*1000);
            count_down();
        }else{
            var rt=document.getElementById('refreshTime');
            rt.innerHTML=top.refreshTime;
        }
        document.getElementById("odd_f_window").style.display = "none";

        if (""+top.BK_RE_session=="undefined"){
            top.BK_RE_session="all";
        }
       /* if (rtype=="r"||rtype=="all"||rtype=="rq4"||rtype=="re"){
            var selRtype=document.getElementById("sel_rtype");
            for (i = 0; i < selRtype.length; i++) {
                if (rtype=="re"){
                    if(selRtype.options[i].value==top.BK_RE_session){
                        selRtype.selectedIndex=i;
                    }
                }else{
                    if(selRtype.options[i].value==rtype){
                        selRtype.selectedIndex=i;
                    }
                }
            }
        }*/

    }


    function setleghi(leghight){
        var legview =document.getElementById('legFrame');
        if((leghight*1) > 95){
            legview.height = leghight;
        }else{

            legview.height = 95;
        }

    }

    function reload_var(Level,leaname){
        parent.loading_var = 'Y';
        if(Level=="up"){
            var tmp = "./"+parent.sel_gtype+"_browse/body_var.php";
            if (parent.sel_gtype=="BU"){
                tmp = "./BK_future/body_var.php";
            }
        }else{
            var tmp = "./body_var.php";
        }

        var l_id =eval("parent.parent."+sel_gtype+"_lid_type");
        if(top.showtype=='hgft'&&parent.sel_gtype=="BU"){
            l_id=3;
        }

            var homepage = tmp + "?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype + "&page_no=" + parent.pg + "&league_id=" + l_id+"&leaname="+leaname;

        //alert("parent.g_date==>"+parent.g_date)
        if (parent.sel_gtype=="BU"){

            homepage+="&g_date="+parent.g_date;
        }
        parent.body_var.location = homepage;
        if(rtype=="r") document.getElementById('more_window').style.display='none';
    }





    //倒數自動更新時間
    function count_down(){
        var rt=document.getElementById('refreshTime');
        setTimeout('count_down()',1000);
        if (parent.retime_flag == 'Y'){
            if(parent.retime <= 0){
                if(parent.loading_var == 'N')
                    reload_var();
                return;
            }
            parent.retime--;
            rt.innerHTML=parent.retime;
        }
    }


    // 赛事切换分页
    function chg_pg(sel){
        if (sel==parent.pg) {return;}

        if(sel ==="add"){
            parent.pg++;
        }else if(sel ==="del"){
            parent.pg--;
        }else if(sel==0){
            parent.pg=0;
        }
        // parent.pg=pg;
        reload_var();
    }
    function chg_wtype(wtype){
        var l_id =eval("parent.parent."+sel_gtype+"_lid_type");
        if(top.showtype=='hgft'&&parent.sel_gtype=="BU"){
            l_id=3;
        }
        parent.location.href="index.php?uid="+top.uid+"&langx="+top.langx+"&mtype="+parent.ltype+"&rtype="+wtype+"&showtype=&league_id="+l_id;

    }

    function setleghi(leghight){
        var legview =document.getElementById('legFrame');
        if((leghight*1)>95){
            legview.height=leghight;
        }else{
            legview.height=95;
        }
    }
    function LegBack(){
        var legview =document.getElementById('legView');
        legview.style.display='none';
        reload_var();

    }


    function unload(){
        clearInterval(ReloadTimeID);
    }
    window.onunload=unload;


    //----------------------------我的最愛  start----------------------------------
    function showPicLove(){
        var gtypeNum= StatisticsGty(top.today_gmt,top.now_gmt,getGtypeShowLoveI());
        try{

            document.getElementById("showNull").style.display = "none";
            document.getElementById("showAll").style.display = "none";
            document.getElementById("showMy").style.display = "none";
            if(gtypeNum!=0){
                document.getElementById("live_num").innerHTML =gtypeNum;

                if(top.swShowLoveI){
                    document.getElementById("showAll").style.display = "inline-block";
                }else{
                    document.getElementById("showMy").style.display = "inline-block";
                }
            }else{
                document.getElementById("showNull").style.display = "inline-block";
                top.swShowLoveI=false;
            }
        }catch(E){}
    }

    //我的最愛中的顯示全部
    function showAllGame(gtype){
        top.swShowLoveI=false;
        eval("parent.parent."+parent.sel_gtype+"_lid_type=top."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
        reload_var();
    }

    //单式盤面點下我的最愛
    function showMyLove(gtype){
        top.swShowLoveI =true;

        parent.pg =0;
        //if (gtype=="FU"){
        eval("parent.parent."+parent.sel_gtype+"_lid_type='3'");
        //}
        reload_var();
    }


    function StatisticsGty(today,now_gmt,gtype){
        var out=0;
        var array =new Array(0,0,0);
        var tmp =today.split("-");
        var newtoday =tmp[1]+"-"+tmp[2];
        var Months =tmp[1]*1;
        tmp =now_gmt.split(":");
        var newgmt=tmp[0]+":"+tmp[1];
        var tmpgday = new Array(0,0);
        var bf = false;
        for (var i=0 ; i < top.ShowLoveIarray[gtype].length ; i++){
            tmpday = top.ShowLoveIarray[gtype][i][1].split("<br>")[0];
            tmpgday = tmpday.split("-");
            tmpgmt =top.ShowLoveIarray[gtype][i][1].split("<br>")[1];
            tmpgmt=time_12_24(tmpgmt);
            if(++tmpgday[0] < Months){
                bf = true;
            }else{
                bf = false;
            }
            if(bf){
                array[2]++;
            }else{
                if(newtoday >= tmpday ){
                    if((newtoday+" "+newgmt) >= (tmpday+" "+tmpgmt)){
                        array[0]++; //走地
                    }else{
                        array[1]++; //单式
                    }
                }else if(newtoday < tmpday){
                    array[2]++; //早餐
                }
            }
        }
        if(parent.sel_gtype=="FT"||parent.sel_gtype=="OP"||parent.sel_gtype=="BK"||parent.sel_gtype=="BS"||parent.sel_gtype=="VB"||parent.sel_gtype=="TN"){
            if(parent.rtype=="re"){
                out=array[0];
            }else{
                out=array[1];
            }
        }else if(parent.sel_gtype=="FU"||parent.sel_gtype=="OM"||parent.sel_gtype=="BU"||parent.sel_gtype=="BSFU"||parent.sel_gtype=="VU"||parent.sel_gtype=="TU"){
            out=array[2];
        }

        return out;
    }

    function time_12_24(stTime){
        var out="";
        var shour =stTime.split(":")[0]*1;
        var smin=stTime.split(":")[1];
        var aop =smin.substr(smin.length-1,1);
        if(aop =="p"){
            if((shour*1)>0)
                shour += 12;
        }
        out=((shour < 10)?"0":"")+shour+":"+smin;
        return out;
    }

    // new array{球類 , new array {gid ,data time ,聯盟,H,C,sw}}
    function addShowLoveI(gid,getDateTime,getLid,team_h,team_c){
        var getGtype =getGtypeShowLoveI();
        var getnum =top.ShowLoveIarray[getGtype].length;
        var sw =true;
        for (var i=0 ; i < top.ShowLoveIarray[getGtype].length ; i++){
            if(top.ShowLoveIarray[getGtype][i][0]==gid)
                sw = false;
        }
        if(sw){
            top.ShowLoveIarray[getGtype]= arraySort(top.ShowLoveIarray[getGtype] ,new Array(gid,getDateTime,getLid,team_h,team_c));
            chkOKshowLoveI();
        }
        document.getElementById("sp_"+MM_imgId(getDateTime,gid)).innerHTML = "<div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+top.str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"');\"></div>";
    }
    function arraySort(array ,data){
        var outarray =new Array();
        var newarray =new Array();
        for(var i=0;i < array.length ;i++){
            if(array[i][1]<= data[1]){
                outarray.push(array[i]);
            }else{
                newarray.push(array[i]);
            }
        }
        outarray.push(data);
        for(var i=0;i < newarray.length ;i++){
            outarray.push(newarray[i]);
        }
        return  outarray;
    }


    function getGtypeShowLoveI(){
        var Gtype;
        var getGtype =parent.sel_gtype;
        var getRtype =parent.rtype;
        Gtype =getGtype;
        if(getRtype=="re"){
            Gtype +="RE";
        }
        /*
        if(getGtype =="FU"||getGtype=="FT"){
            Gtype ="FT";
        }else if(getGtype =="OM"||getGtype=="OP"){
            Gtype ="OP";
        }else if(getGtype =="BU"||getGtype=="BK"){
            Gtype ="BK";
        }else if(getGtype =="BSFU"||getGtype=="BS"){
            Gtype ="BS";
        }else if(getGtype =="VU"||getGtype=="VB"){
            Gtype ="VB";
        }else if(getGtype =="TU"||getGtype=="TN"){
            Gtype ="TN";
        }else {
            Gtype ="FT";
        }
        */

        //alert("in==>"+parent.sel_gtype+",out==>"+Gtype);
        return Gtype;
    }
    function chkOKshowLoveI(){
        var getGtype = getGtypeShowLoveI();
        var getnum =top.ShowLoveIOKarray[getGtype].length ;
        var ibj="" ;
        top.ShowLoveIOKarray[getGtype]="";
        for (var i=0 ; i < top.ShowLoveIarray[getGtype].length ; i++){
            tmp = top.ShowLoveIarray[getGtype][i][1].split("<br>")[0];
            top.ShowLoveIOKarray[getGtype]+=tmp+top.ShowLoveIarray[getGtype][i][0]+",";
        }
        showPicLove();
    }


    function chkDelshowLoveI(data2,data){
        var getGtype = getGtypeShowLoveI();
        var tmpdata = data2.split("<br>")[0]+data;
        var tmpdata1 ="";
        var ary = new Array();
        var tmp = new Array();
        tmp = top.ShowLoveIarray[getGtype];
        top.ShowLoveIarray[getGtype]= new Array();
        for (var i=0 ; i < tmp.length ; i++){
            tmpdata1 =tmp[i][1].split("<br>")[0]+tmp[i][0];
            if(tmpdata1 == tmpdata){
                ary = tmp[i];
                continue;
            }
            top.ShowLoveIarray[getGtype].push(tmp[i]);
        }
        chkOKshowLoveI();
        var gtypeNum= StatisticsGty(top.today_gmt,top.now_gmt,getGtypeShowLoveI());
        if(top.swShowLoveI){

            var sw=false;
            //alert(top.swShowLoveI+"-"+gtypeNum);
            if(gtypeNum==0){
                top.swShowLoveI=false;
                eval("parent.parent."+parent.sel_gtype+"_lid_type=top."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
                reload_var();
            }else{
                parent.ShowGameList();
            }
        }else{
            if(gtypeNum==0){
                reload_var();
            }else{
                document.getElementById("sp_"+MM_imgId(ary[1],ary[0])).innerHTML ="<div id='"+MM_imgId(ary[1],ary[0])+"' class=\"fov_icon_out\" style=\"cursor:hand;display:none;\" title=\""+top.str_ShowMyFavorite+"\" onClick=\"addShowLoveI('"+ary[0]+"','"+ary[1]+"','"+ary[2]+"','"+ary[3]+"','"+ary[4]+"'); \"></div>";
            }
        }
    }

    function chkDelAllShowLoveI(){
        var getGtype=getGtypeShowLoveI();
        top.ShowLoveIarray[getGtype]= new Array();
        top.ShowLoveIOKarray[getGtype]="";
        if(top.swShowLoveI){
            top.swShowLoveI=false;
            eval("parent.parent."+parent.sel_gtype+"_lid_type=top."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
            parent.pg =0;
            reload_var();
        }else{
            parent.ShowGameList();
        }

    }

    //檢查所選的最愛賽事是否已經進入滾球或是結束
    function checkLoveCount(GameArray){

        var getGtype = getGtypeShowLoveI();
        var tmpdata = "";
        var tmpdata1 ="";
        var ary = new Array();
        var tmp = new Array();
        tmp = top.ShowLoveIarray[getGtype];
        top.ShowLoveIarray[getGtype] = new Array();
        for (s=0;s < GameArray.length;s++){
            tmpdata=GameArray[s].datetime.split("<br>")[0]+GameArray[s].gnum_h;
            for (var i=0;i < tmp.length; i++){
                tmpdata1 =tmp[i][1].split("<br>")[0]+tmp[i][0];
                if(tmpdata1 == tmpdata){
                    top.ShowLoveIarray[getGtype].push(tmp[i]);
                }
            }
        }
        chkOKshowLoveI();
    }

    function mouseEnter_pointer(tmp){
        try{
            document.getElementById(tmp.split("_")[1]).style.display ="block";
        }catch(E){}
    }

    function mouseOut_pointer(tmp){
        try{
            document.getElementById(tmp.split("_")[1]).style.display ="none";
        }catch(E){}
    }

    function chkLookShowLoveI(){
        top.swShowLoveI =true;
        eval("parent.parent."+parent.sel_gtype+"_lid_type='3'");
        parent.pg =0;
        reload_var();
    }

    function MM_imgId(time,gid){
        var tmp = time.split("<br>")[0];
        //alert(tmp+gid);
        return tmp+gid;
    }


    //----------------------------我的最愛  end----------------------------------




    //--------------------------odd_f   start--------------------
    //盤口onclick事件

    function ChkOddfDiv(){
        var odd_show ='<tt id="chose_odd" class="bet_normal_text">香港盘</tt>\n' +
            ' <div id="show_odd" onmouseleave="hideDiv(this.id);" class="bet_odds_bg" style="display: none;" tabindex="100">\n' +
            ' <span class="bet_arrow"></span>\n' +
            '<span class="bet_arrow_text">盘口类型</span>\n' +
            '<ul id="myoddType" selvalue="'+Format[0][0]+'" seltext="'+Format[0][1]+'">\n';
        // var odd_show="<select id=myoddType onchange=chg_odd_type()>";
        for (i = 0; i < Format.length; i++) {
            //沒盤口選擇時，預設為H(香港變盤)
            if((odd_f_str.indexOf(Format[i][0])!=(-1))&&Format[i][2]=="Y"){

                if(top.odd_f_type==Format[i][0]){
                    odd_show+="<li id=odd_"+Format[i][0]+" value="+Format[i][0]+" class=bet_odds_contant selected>"+Format[i][1]+"</li>";
                }else{
                    odd_show+="<li id=odd_"+Format[i][0]+" value="+Format[i][0]+" class=bet_odds_contant >"+Format[i][1]+"</li>";
                }
            }
        }

        odd_show +='</ul>\n' +
            '</div>'; ;
        //document.getElementById("Ordertype").innerHTML=odd_show;
        var $sel_odd = document.getElementById("sel_odd") ;
        if($sel_odd){
            $sel_odd.innerHTML=odd_show;
        }

    }

    //切換盤口
    function chg_odd_type(){

        var myOddtype=document.getElementById("myoddType");
        top.odd_f_type=myOddtype.options[myOddtype.selectedIndex].value;
        reload_var();
    }

    function show_oddf(){
        for (i = 0; i < Format.length; i++) {
            if(Format[i][0]==top.odd_f_type){
                document.getElementById("oddftext").innerHTML=Format[i][1];
            }
        }

    }
    //--------------------------odd_f   end--------------------

    function change_rtype(){
        var myOddtype=document.getElementById("sel_rtype");
        rtype=myOddtype.options[myOddtype.selectedIndex].value;
        parent.location.href="index.php?uid="+top.uid+"&langx="+top.langx+"&mtype="+parent.ltype+"&rtype="+rtype;



    }
    function change_RE_session(){
        var myOddtype=document.getElementById("sel_rtype");
        rtype=myOddtype.options[myOddtype.selectedIndex].value;

        top.BK_RE_session=rtype;
        reload_var();
        // parent.location.href="index.php?uid="+top.uid+"&langx="+top.langx+"&mtype="+parent.ltype+"&rtype="+rtype;
    }

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
    }


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