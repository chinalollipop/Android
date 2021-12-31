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
$gametitle ='早盘排球' ;
switch ($rtype){
case "r":
	$caption=$Straight;
	$table='<tr>
	         <th class="bk_h_1x1">'.$gametitle.'</th>
           
            <th class="h_1x2">'.$WIN.'</th>
            <th class="h_r">'.$Handicap.'</th>
            <th class="h_ou">'.$Over_Under.'</th>
            <th class="h_oe">'.$O_E.'</th>
			</tr>';
    break;
case "re": // 滚球
    $caption=$Running_Ball;
    $table='<tr>
        <th class="bk_h_1x1">排球:滚球</th>
   
        <th class="h_r">'.$U_23.'</th>
        <th class="h_ou">'.$Over_Under.'</th>
        <tr>';
    break;
case "pd": // 赛盘投注
	$caption=$Correct_Score;
	$upd_msg=$Correct_Score_maximum_TN;
	$table='<tr>
	         <th class="bk_h_1x1">'.$gametitle.'</th>
         
            <th  class="h_pd">3:0</th>
            <th  class="h_pd">3:1</th>
            <th  class="h_pd">3:2</th>
			<tr><tr class="bet_correct_title">
            <td colspan="20">赛盘投注<span class="maxbet">'.$U_11.'： RMB 1,000,000.00</span>
          </td>
          </tr>';
	break;
case "p":
	$caption=$Parlay;
	$upd_msg=$Mix_Parlay_maximum;
	$table='<tr>
	         <th class="bk_h_1x1">'.$gametitle.'</th>
            <th width=156>'.$Home.'</th>
            <th width=55>&nbsp;</th>
            <th width=55>&nbsp;</th>
            <th width=160>'.$Away.' </th>
			<tr>';
	break;
case "p3": // 综合过关
	$caption=$Handicap_Parlay;
	$upd_msg=$Mix_Parlay_maximum;
	$table='<tr>
	         <th class="bk_h_1x1">'.$gametitle.'</th>
            
            <th class="h_1x2">'.$WIN.'</th>
            <th class="h_r">'.$Handicap.'</th>
            <th class="h_ou">'.$Over_Under.'</th>
            <th class="h_oe">'.$O_E.'</th>
			</tr><tr class="bet_correct_title">
            <td colspan="20">综合过关<span class="maxbet">'.$U_11.'： RMB 1,000,000.00</span>       
           </h1></td>
           </tr>';
	break;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php if($rtype=='p3'){ ?>
<link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
<?php } ?>

</head>
 
<body id="MVU" class="bodyset VBR body_browse_set <?php echo $table_dif?>" onLoad="onLoad();">
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
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r'){ // 全部才有
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
        if($rtype=='pd'){ // 只有波胆才有
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'hpd\');"><tt class="bet_normal_text" >半场</tt></span>' ;
        }else if($rtype=='hpd'){
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'pd\');"><tt class="bet_normal_text" >全场</tt></span>' ;
        }
        ?>

        <?php
        if($rtype=='r'){ // 全部才有
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket();"><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
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
        if($rtype=='r'){ // 只有全部才有
            echo '<span id="sel_odd" class="bet_odds_btn"> </span>' ;
        }
        ?>

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>

</div>
<!-- 球赛展示区顶部 结束-->

<!-- 日期 -->
<div id="show_date_opt" class="bet_title_date">

</div>

<?php
switch($rtype){
case 're': // 滚球
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="4" class="b_hline">
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
    <td rowspan="2" class="b_cen"><table class="rb_box"><tr><td class="rb_time">*SE*</td></tr><tr><td class="rb_score">*SCORE*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
      *MYLOVE*<!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
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

<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table bk_top"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">

      <tr>
        <td class="mem">
                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
    </table>

</td></tr></table>
<div class="more" id="more_window" name="more_window" >
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
    <!-- 所有玩法弹窗 -->
    <div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
        <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
    </div>
<?php
break;
case 'r':
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;" >
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
    <td rowspan="2" class="b_cen"><table><tr><td class="b_cen">*DATETIME*</td></tr></table></td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
      *MYLOVE*<!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
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

<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table bk_top"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">

      <tr>

        <td class="mem">

                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>
    </table>

</td></tr></table>
<div class="more" id="more_window" name="more_window" >
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<?php
break;
case 'pd':
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="7" class="b_hline">
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
    <td rowspan="2" class="b_cen">*DATETIME*</td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*</td>
    <!--td class="b_cen">*RATIO_H2C0*</td>
    <td class="b_cen">*RATIO_H2C1*</td-->
    <td class="b_cen">*RATIO_H3C0*</td>
    <td class="b_cen">*RATIO_H3C1*</td>
    <td class="b_cen">*RATIO_H3C2*</td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <!--td class="b_cen">*RATIO_H0C2*</td>
    <td class="b_cen">*RATIO_H1C2*</td-->
    <td class="b_cen">*RATIO_H0C3*</td>
    <td class="b_cen">*RATIO_H1C3*</td>
    <td class="b_cen">*RATIO_H2C3*</td>
  </tr>
  
</xmp>
</div>

<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table bk_top"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">

      <tr>

        <td class="mem">

                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>

    </table>

</td></tr></table>
<div class="more" id="more_window" name="more_window" >
  <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>
<?php
break;
case 'p3':
?>
<!--   表格資料     -->
<div id=DataTR style="display:none;" >
    <xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="11" class="b_hline">
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

<div id=NoDataTR style="display:none;">
    <xmp>
       <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
     </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table bk_top"><tr><td>
 <table border="0" cellpadding="0" cellspacing="0" id="box">

      <tr>

        <td class="mem">

                <!--     資料顯示的layer     -->
                <div id=showtable></div>
    </td>
      </tr>

    </table>

</td></tr></table>

<?php
break;
}
?>
<!--選擇聯盟-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>
<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>
<!-- 分页 -->
<div id="show_page_txt" class="bet_page_bot_rt">

</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/future.js?v=<?php echo AUTOVER; ?>"></script>

<script>
    var minlimit='3';
    var maxlimit='10';

    var DateAry = new Array('<?php echo $date1?>','<?php echo $date2?>','<?php echo $date3?>','<?php echo $date4?>','<?php echo $date5?>','<?php echo $date6?>','<?php echo $date7?>','<?php echo $date8?>','<?php echo $date9?>');
    var rtype = '<?php echo $rtype?>';
    var odd_f_str = 'H,M,I,E';
    top.today_gmt = '<?php echo $date?>';

    //在body_browse載入
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
        if(sel_gtype=="VU"){
                if(top.showtype!='hgft'){
                    selgdate(rtype);
                }
        }

    }

    function setleghi(leghight){
        var legview =document.getElementById('legFrame');

        if((leghight*1) > 95){
            legview.height = leghight;
        }else{

            legview.height = 95;
        }

    }

    function reload_var(Level){
        parent.loading_var = 'Y';
        if(Level=="up"){
            var tmp = "./"+parent.sel_gtype+"_browse/body_var.php";
            if (parent.sel_gtype=="VU"){
                tmp = "./VB_future/body_var.php";
            }
        }else{
            var tmp = "./body_var.php";
        }

        var l_id =eval("parent.parent."+sel_gtype+"_lid_type");
        if(top.showtype=='hgft'&&parent.sel_gtype=="VU"){
            l_id=3;
        }

        var homepage = tmp+"?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&league_id="+l_id;
        //alert("parent.g_date==>"+parent.g_date)
        if (parent.sel_gtype=="VU"){

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
            //alert(parent.retime);
            //obj_cd = document.getElementById('cd');
            //obj_cd.innerHTML = parent.retime;
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
        if(top.showtype=='hgft'&&parent.sel_gtype=="VU"){
            l_id=3;
        }
        parent.location.href="index.php?uid="+top.uid+"&langx="+top.langx+"&mtype="+parent.ltype+"&rtype="+wtype+"&showtype=&league_id="+l_id;

        //<frame name="body_var" scrolling="NO" noresize src="body_var.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype;?>&delay=<?php echo $delay;?>&league_id=<?php echo $league_id?>">
        //<frame name="body_browse" src="body_browse.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype;?>&delay=<?php echo $delay;?>&showtype=<?php echo $showtype?>">


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
        eval("parent.parent."+parent.sel_gtype+"_lid_type='3'");
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