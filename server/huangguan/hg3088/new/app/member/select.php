<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

$uid=$uidOriginal=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$live=$_REQUEST['live'];
$mtype=$_REQUEST['mtype'];
$showtype=$_REQUEST['showtype'];
$comshowtype=$_REQUEST['comshowtype']; // 综合过关才有

require ("./include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{

	$Status=$_SESSION['Status'];
	$memname=$_SESSION['UserName'];
	$loginname=$_SESSION['LoginName'];
	$langx=$_SESSION['Language'];
	$logindate=date("Y-m-d");
	$datetime=date('Y-m-d h:i:s');
}

$show=$_REQUEST['show'];
if ($show==''){
    $show='N';
}

if($show=='Y'){
    $chk_fun='Show';
    $open='visible';
}else if($show=='N'){
    $chk_fun='None';
    $open='hidden';
}

$sel_line="$Sel_Cash_Line";


switch ($showtype){
    case "future": // 早盘
        $style='HFU';
        $Mtype="early"; // early 早盘，today 今日赛事，rb 滚球
        $rtype='r' ; // 足球
        $file_url = 'future' ; // 访问文件路径 browse 滚球和今日赛事，future 早盘
        break;
    case "rb": // 滚球
        $style='HFT';
        $Mtype="rb"; // early 早盘，today 今日赛事，rb 滚球
        $rtype='re' ; // 足球滚球
        $file_url = 'browse' ; // 访问文件路径 browse 滚球和今日赛事，future 早盘
        break;
    default: // 默认今日赛事
        $style='HFT';
        $Mtype="today"; // early 早盘，today 今日赛事，rb 滚球
        $rtype='r' ;  // 足球
        $file_url = 'browse' ; // 访问文件路径 browse 滚球和今日赛事，future 早盘
        break;
}
if($comshowtype=='comprehensive'){ // 综合过关才有
    $comMtype ='comprehensive';
    $Mtype='comprehensive' ;
    $rtype='p3' ;  // 综合过关
}


// 获取正网地址、与uid
$m_date=date('Y-m-d');
$dcRedisObj = new Ciredis('datacenter');
	// ----------------------------------------------------------统计足球盘口数目 Start
    $cou_num_ft_gq = $dcRedisObj->getSimpleOne("FT_Running_Num");// 滚球
    $cou_num_ft_future = $dcRedisObj->getSimpleOne("FT_Future_Num");//R 早盘 盘口数
	$cou_num_ft = $dcRedisObj->getSimpleOne("FT_Today_Num");//R  今日赛事 盘口数
	// ----------------------------------------------------------统计足球盘口数目 End
    // ----------------------------------------------------------统计篮球盘口数目 Start
    $cou_num_bk_gq = $dcRedisObj->getSimpleOne("BK_Running_Num");// 滚球
    $cou_num_bk_future = $dcRedisObj->getSimpleOne("BK_Future_Num");//R 早盘 盘口数
    $cou_num_bk = $dcRedisObj->getSimpleOne("BK_Today_Num");//R  今日赛事 盘口数
	// ----------------------------------------------------------统计篮球盘口数目 End

    if(strpos($_SESSION['gameSwitch'],'|')>0){
			$gameArr=explode('|',$_SESSION['gameSwitch']);	
      }else{
      		if(strlen($_SESSION['gameSwitch'])>0){
      			$gameArr[]=$_SESSION['gameSwitch'];	
      		}else{
      			$gameArr=array();	
      		}
      }
    
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <link rel="stylesheet" href="../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link href="../../style/member/mem_order_sel.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">

    <script>
        var comshowtype='<?php echo$comshowtype?>' ; // 综合过关才有
        var username = '<?php echo $memname?>'; // 用户名
        var chk_fun = '<?php echo $chk_fun?>';
        var mtype='3';
        /* 2018 新版新增 开始*/
        top.select_showtype = "today";
        top.select_gtype = "FT";
        top.tv_allbet = false;
        var gameData;
        var gtypeAry = gtypeAry || new Array("FT","BK","TN","VB","BM","TT","BS","OP","SK");
        var isFirst = true;
        var disHash = new Object();
        var maxHash="";
        var _set = new Object();

        /* 2018 新版新增 结束 */
    </script>
</head>

<body id="OSEL" class="bodyset" onLoad="bodyLoad();" >

<div id="main">
    <div id="menu">
        <div class="menu menu_on" id="menu_button" onclick="showSportMenu()">目录</div>
        <div class="ord_btn" id="order_button" onClick="showOrder();">交易单</div>
        <div class="record_btn" id="record_button" onClick="showRec();">我的注单</div>
        <?php
        echo "<script> top.uid = '$uid';</script>";
        ?>
    </div>

    <div id="order_div" name="order_div" >
        <div id="pls_bet" name="pls_bet" style="background-color:#E3CFAA;left:0;top:0; display:none;">
            <h1 id="SIN_BET" style="background-color:#503f32; line-height:40px; font-size:15px; padding-left:10px; color:#fff1d6; font-weight:bold;">单一投注</h1>
            <div id="bet_nodata" class="ord_noOrder" style="line-height:16px; padding:7px 10px 10px; background-color:#fff; font-size:14px; color:#45403b;">请把选项加入在您的注单。</div>
        </div>

        <!-- 目内容录开始 -->
        <div id="div_menu" name="div_menu" class="ord_DIV">
            <!--过关下注数-->
            <div id="show_parlay" class="ord_parlyG noFloat" onclick="showMenu('betslip')" style="display:none">
                <ul><li>过关串数</li></ul>
                <span id="count_parlay" class="ord_parlyNUM">0</span>
            </div>

            <!--滚球区-->
            <div id="euro_open"  class="ord_sportMenu_InPlayG">
                <h1>滚球中</h1>
                <div id="div_rb" class="ord_sportMenu_InPlay">
                    <div id="ft_rb_class" class="type_out" onclick="chg_button_bg('FT','rb');chg_type('<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=rb',parent.FT_lid_type,'SI2','rb');chg_type_class('ft_rb_class');" ><span class="ord_sportName">足球滚球</span><span id="RB_FT_games" class="ord_sportDigit"><?php echo $cou_num_ft_gq;?></span></div>
                    <?php if(!in_array('BK',$gameArr)){?>
                    <div id="bk_rb_class" class="type_out" onclick="chg_button_bg('BK','rb');chg_type('<?php echo BROWSER_IP?>/app/member/BK_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=rb',parent.BK_lid_type,'SI2','rb');chg_type_class('bk_rb_class');"><span class="ord_sportName">篮球 &amp; 美式足球</span><span id="RB_BK_games" class="ord_sportDigit"><?php echo $cou_num_bk_gq;?></span></div>
                    <?php }?>
                    <div id="tn_rb_class" style="display: none;" class="type_out" ><span class="ord_sportName">网球</span><span id="RB_TN_games" class="ord_sportDigit">0</span></div>
                    <div id="vb_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">排球</span><span id="RB_VB_games" class="ord_sportDigit">0</span></div>
                    <div id="bm_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">羽毛球</span><span id="RB_BM_games" class="ord_sportDigit">0</span></div>
                    <div id="tt_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">乒乓球</span><span id="RB_TT_games" class="ord_sportDigit">0</span></div>
                    <div id="bs_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">棒球</span><span id="RB_BS_games" class="ord_sportDigit">0</span></div>
                    <div id="sk_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">斯诺克/台球</span><span id="RB_SK_games" class="ord_sportDigit">0</span></div>
                    <div id="op_rb_class" style="display:none;" class="type_out" ><span class="ord_sportName">其他</span><span id="RB_OP_games" class="ord_sportDigit">0</span></div>

                </div>
                <div id="RB_nodata" style="display:none;" class="ord_noInPlay">现在没有进行的赛事</div><!--没赛-->
            </div>



            <!-- 特殊赛事 -->
            <div id="sp_game" style="display:none;" class="ord_sportMenu_WorldCupG">
                <h1 id="sp_name">世界杯赛事</h1>
                <div id="sp_show" class="ord_sportMenu_WorldCup"></div>
                <div id="sp_model" style="display:none;">
                    <div id="sp_game_RB" class="noFloat"><span class="ord_sportName">滚球赛事</span><span class="ord_sportDigitWC"> </span></div>
                    <div id="sp_game_FT" class="noFloat"><span class="ord_sportName">今日赛事</span><span class="ord_sportDigitWC"> </span></div>
                    <div id="sp_game_FU" class="noFloat"><span class="ord_sportName">早盘赛事</span><span class="ord_sportDigitWC"> </span></div>
                    <div id="sp_game_P3" class="noFloat"><span class="ord_sportName">综合过关</span><span class="ord_sportDigitWC"> </span></div>
                    <div id="sp_game_FS" class="noFloat"><span class="ord_sportName">冠军盘口</span><span class="ord_sportDigitWC"> </span></div>
                </div>
            </div>
            <!-- 特殊赛事 End -->

            <!-- 精选赛事 -->
            <div id="hot_game" style="" class="ord_sportMenu_highG">
                <h1 id="hot_name">精选赛事</h1>

                <div id="hot_show" class="ord_sportMenu_high">
                    <div class="ord_sportFT_high noFloat" > <!-- onclick="showHotDiv('FT');" -->
                        <span class="ord_sportName"><span class="ordH3">足球</span><span class="ordH4">顶级赛事</span></span><span id="arrow_FT" class="ord_sportArr"></span>
                    </div>
                    <ul id="hot_div_FT" style="display:none">
                        <li id="hot_game_RB_FT" ><h5>滚球赛事</h5><h6>16</h6></li>
                        <li id="hot_game_FT_FT" ><h5>今日赛事</h5><h6>4</h6></li>
                        <li id="hot_game_FU_FT" ><h5>早盘赛事</h5><h6>0</h6></li>
                        <li id="hot_game_P3_FT"  ><h5>综合过关</h5><h6>2</h6></li>
                        <li id="hot_game_FS_FT" ><h5>冠军盘口</h5><h6>0</h6></li>
                    </ul>
                </div>

                <div id="hot_model" style="display:none;">
                    <div > <!-- class="ord_sportFT_high noFloat" -->
                        <span class="ord_sportName"><span class="ordH3"> </span><span class="ordH4"> </span></span>
                    </div>
                    <ul >
                        <li ><h5>滚球赛事</h5><h6> </h6></li>
                        <li ><h5>今日赛事</h5><h6> </h6></li>
                        <li ><h5>早盘赛事</h5><h6> </h6></li>
                        <li ><h5>综合过关</h5><h6> </h6></li>
                        <li ><h5>冠军盘口</h5><h6> </h6></li>
                    </ul>
                </div>

            </div>
            <!-- 精选赛事 End -->
            <!--球类有下拉区-->
            <div class="ord_sportMenu_TodayG">
                <h1>体育</h1>
                <div class="ord_memu2">
              <span id="title_today" class="ord_memuBTN" >
                  <a id="todayshow" class="<?php echo ($Mtype=='today' && !$comMtype)?"ord_memuBTN_on":"" ?>" href="javascript:chg_button_bg(top.head_gtype,'today');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid ?>&showtype=today&langx=<?php echo $langx?>&mtype=4','<?php echo BROWSER_IP?>/app/member/'+top.head_gtype+'_browse/index.php?rtype=r&uid=<?php echo $uid ?>&langx=<?php echo $langx?>&mtype=4&showtype=today',parent.FT_lid_type,'SI2');" >今日</a>
              </span>
                    <span id="title_early" class="ord_memuBTN">
                  <a id="earlyshow"  class="<?php echo ($Mtype=='early' && !$comMtype)?"ord_memuBTN_on":"" ?>" href="javascript:chg_button_bg(top.head_gtype,'early');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=future&langx=<?php echo $langx?>&mtype=4','<?php echo BROWSER_IP?>/app/member/'+top.head_gtype+'_future/index.php?rtype=r&uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=4&showtype=future',parent.FU_lid_type,'SI2','future');" >早盘</a>
              </span>
                    <span id="title_parlay" class="ord_memuBTN no_margin">
                  <a class="<?php echo ($comMtype=='comprehensive')?"ord_memuBTN_on":"" ?>" href="javascript:chg_button_bg(top.head_gtype,'comprehensive');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo $showtype?>&langx=<?php echo $langx?>&mtype=4&comshowtype=comprehensive','<?php echo BROWSER_IP?>/app/member/'+top.head_gtype+'_<?php echo $file_url?>/index.php?rtype=p3&uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=4','','SI2');">综合过关</a>
              </span>
                </div>
                <div id="sportMenu_Today" class="ord_sportMenu_Today">
                    <!-- 足球 -->
                    <div id="title_FT" class="ord_sportFT_off noFloat" onclick="chg_button_bg('FT','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/FT_<?php echo $file_url?>/index.php?rtype=<?php echo $rtype ?>&uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);" >
                        <span class="ord_sportName">足球</span>
                        <span id="FT_games" class="ord_sportDigit"><?php echo $showtype == 'future' ? $cou_num_ft_future : $cou_num_ft ;?></span>
                    </div>
                    <ul id="wager_FT" select="wtype_FT_r" class="hide_game_list " >
                        <li id="re_class" class="type_on" onclick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_<?php echo $file_url?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('re_class');">独赢 &amp; 让球 &amp; 大小</li>
                        <li id="pd_class" onclick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_<?php echo $file_url?>/index.php?rtype=pd&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('pd_class');">波胆</li>
                        <li id="to_class" onclick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_<?php echo $file_url?>/index.php?rtype=t&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('to_class');">总入球</li>
                        <li id="hf_class" onclick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_<?php echo $file_url?>/index.php?rtype=f&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('hf_class');">半场 / 全场</li>
                        <li id="fs_class" onclick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/browse_FS/loadgame_R.php?uid=<?php echo $uid?>&langx=zh-cn&FStype=FT&mtype=4',parent.FT_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('fs_class');" >冠军</li>
                    </ul>
                    <?php if(!in_array('BK',$gameArr)){?>
                    <!-- 篮球 -->
                    <div id="title_BK" class="ord_sportBK_off noFloat" onclick="chg_button_bg('BK','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/BK_<?php echo $file_url?>/index.php?rtype=<?php echo $rtype?>&uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=4',parent.BK_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);" >
                        <span class="ord_sportName">篮球 &amp; 美式足球</span>
                        <span id="BK_games" class="ord_sportDigit"><?php echo $showtype == 'future' ? $cou_num_bk_future : $cou_num_bk;?></span>
                    </div>
                    <ul id="wager_BK" select="wtype_BK_r" class="hide_game_list ">
                        <li id="bk_re_class" class="type_on" onclick="chg_button_bg('BK','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/BK_<?php echo $file_url?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.BK_lid_type,'SI2');chg_type_class('bk_re_class');">让分 ＆ 大小 ＆ 单 / 双</li>
                        <!--<li id="hpa_class"  onclick="chg_button_bg('BK','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/BK_<?php /*echo $file_url*/?>/index.php?rtype=p3&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.BK_lid_type,'SI2');chg_type_class('hpa_class');">综合过关</li>-->
                        <li id="bk_fs_class" onclick="chg_button_bg('BK','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/browse_FS/loadgame_R.php?uid=<?php echo $uid?>&langx=zh-cn&FStype=BK&mtype=4',parent.FT_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('bk_fs_class');" >冠军</li>
                    </ul>
                    <?php } ?>
                    <!-- 网球 -->
                 <!--   <div id="title_TN"  class="ord_sportTN_off noFloat" onclick="chg_button_bg('TN','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/select.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=<?php /*echo $langx*/?>&mtype=4&comshowtype=<?php /*echo $comshowtype */?>','<?php /*echo BROWSER_IP*/?>/app/member/TN_<?php /*echo $file_url*/?>/index.php?rtype=<?php /*echo $rtype*/?>&uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&mtype=4',parent.TN_lid_type,'SI2',<?php /*echo  '\''.$Mtype.'\''*/?>);">
                        <span class="ord_sportName">网球</span>
                        <span id="TN_games" class="ord_sportDigit">0</span>
                    </div>-->
                    <div id="title_TN"  class="ord_sportTN_off noFloat" onclick="chg_button_bg('TN','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=<?php echo $langx?>&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/static_page.php?gamename=TN&showtype=<?php echo $showtype ?>',parent.TN_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);" >
                        <span class="ord_sportName">网球</span>
                        <span id="TN_games" class="ord_sportDigit">0</span>
                    </div>
                   <!-- <ul id="wager_TN" select="wtype_TN_r" class="hide_game_list">
                        <li id="tn_re_class" class="type_on" onclick="chg_button_bg('TN','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/TN_<?php /*echo $file_url*/?>/index.php?rtype=r&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.TN_lid_type,'SI2');chg_type_class('tn_re_class');">独赢 ＆ 让盘 ＆ 大小 ＆ 单 / 双</li>
                        <li id="tn_pd_class" onclick="chg_button_bg('TN','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/TN_<?php /*echo $file_url*/?>/index.php?rtype=pd&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.TN_lid_type,'SI2');chg_type_class('tn_pd_class');">赛盘投注</li>
                        <li id="tn_hpa_class" onclick="chg_button_bg('TN','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/TN_<?php /*echo $file_url*/?>/index.php?rtype=p3&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.TN_lid_type,'SI2');chg_type_class('tn_hpa_class');">综合过关</li>
                        <li id="tn_fs_class" onclick="chg_button_bg('TN','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/browse_FS/loadgame_R.php?uid=<?php /*echo $uid*/?>&langx=zh-cn&FStype=TN&mtype=4',parent.TN_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('tn_fs_class');">冠军</li>

                    </ul>-->

                    <!-- 排球 -->
                    <div id="title_VB" class="ord_sportVB_off noFloat"onclick="chg_button_bg('VB','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=<?php echo $langx?>&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/static_page.php?gamename=VB&showtype=<?php echo $showtype ?>',parent.VB_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);">
                        <span class="ord_sportName">排球</span><span id="VB_games" class="ord_sportDigit">0</span>
                    </div>
                   <!-- <div id="title_VB" class="ord_sportVB_off noFloat"onclick="chg_button_bg('VB','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/select.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=<?php /*echo $langx*/?>&mtype=4&comshowtype=<?php /*echo $comshowtype */?>','<?php /*echo BROWSER_IP*/?>/app/member/VB_<?php /*echo $file_url*/?>/index.php?rtype=<?php /*echo $rtype*/?>&uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&mtype=4',parent.VB_lid_type,'SI2',<?php /*echo  '\''.$Mtype.'\''*/?>);">
                        <span class="ord_sportName">排球</span><span id="VB_games" class="ord_sportDigit">0</span>
                    </div>-->
                   <!-- <ul id="wager_VB" select="wtype_VB_r" class="hide_game_list">
                        <li id="vb_re_class" class="type_on" onclick="chg_button_bg('VB','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/VB_<?php /*echo $file_url*/?>/index.php?rtype=r&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.VB_lid_type,'SI2');chg_type_class('vb_re_class');">独赢 ＆ 让盘 ＆ 大小 ＆ 单 / 双</li>
                        <li id="vb_pd_class" onclick="chg_button_bg('VB','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/VB_<?php /*echo $file_url*/?>/index.php?rtype=pd&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.VB_lid_type,'SI2');chg_type_class('vb_pd_class');">赛盘投注</li>
                        <li id="vb_hpa_class" onclick="chg_button_bg('VB','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/VB_<?php /*echo $file_url*/?>/index.php?rtype=p3&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.VB_lid_type,'SI2');chg_type_class('vb_hpa_class');">综合过关</li>
                        <li id="vb_fs_class" onclick="chg_button_bg('VB','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/browse_FS/loadgame_R.php?uid=<?php /*echo $uid*/?>&langx=zh-cn&FStype=VB&mtype=4',parent.VB_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('vb_fs_class');">冠军</li>

                    </ul>-->
                    <!-- 羽毛球-->
                    <div id="title_BM" style="display:none;" onclick="chgTitle('BM');" class="ord_sportBM_off noFloat"><span class="ord_sportName">羽毛球</span><span id="BM_games" class="ord_sportDigit">0</span></div>
                    <ul id="wager_BM" select="wtype_BM_r" class="hide_game_list">
                        <li id="wtype_BM_r" class="type_on" onclick="chgWtype(this.id);chg_type(this.id,'');">赛事</li>
                        <li id="wtype_BM_pd35" onclick="chgWtype(this.id);chg_type(this.id,parent.BM_lid_type);">波胆</li>
                        <li id="wtype_BM_fs" onclick="chgWtype(this.id);chg_type(this.id,'');parent.sel_league='';parent.sel_area='';top.hot_game='';" class="no_margin">冠军</li>
                    </ul>
                    <!-- 乒乓球 -->
                    <div id="title_TT" style="display:none;" onclick="chgTitle('TT');" class="ord_sportTT_off noFloat"><span class="ord_sportName">乒乓球</span><span id="TT_games" class="ord_sportDigit">0</span></div>
                    <ul id="wager_TT" select="wtype_TT_r" class="hide_game_list">
                        <li id="wtype_TT_r" class="type_on" onclick="chgWtype(this.id);chg_type(this.id,'');">赛事</li>
                        <li id="wtype_TT_pd57" onclick="chgWtype(this.id);chg_type(this.id,parent.TT_lid_type);">波胆</li>
                        <li id="wtype_TT_fs" onclick="chgWtype(this.id);chg_type(this.id,'');parent.sel_league='';parent.sel_area='';top.hot_game='';" class="no_margin">冠军</li>
                    </ul>
                    <!-- 棒球 -->
                    <div id="title_BS" class="ord_sportBS_off noFloat" onclick="chg_button_bg('BS','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=<?php echo $langx?>&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/static_page.php?gamename=BS&showtype=<?php echo $showtype ?>',parent.BS_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);">
                        <span class="ord_sportName">棒球</span><span id="BS_games" class="ord_sportDigit">0</span>
                    </div>
                    <!--<div id="title_BS" class="ord_sportBS_off noFloat" onclick="chg_button_bg('BS','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/select.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=<?php /*echo $langx*/?>&mtype=4&comshowtype=<?php /*echo $comshowtype */?>','<?php /*echo BROWSER_IP*/?>/app/member/BS_<?php /*echo $file_url*/?>/index.php?rtype=<?php /*echo $rtype*/?>&uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&mtype=4',parent.BS_lid_type,'SI2',<?php /*echo  '\''.$Mtype.'\''*/?>);">
                        <span class="ord_sportName">棒球</span><span id="BS_games" class="ord_sportDigit">0</span>
                    </div>
                    <ul id="wager_BS" select="wtype_BS_r" class="hide_game_list">
                        <li id="bs_re_class" class="type_on" onclick="chg_button_bg('BS','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/BS_<?php /*echo $file_url*/?>/index.php?rtype=r&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.BS_lid_type,'SI2');chg_type_class('bs_re_class');">独赢 ＆ 让盘 ＆ 大小 ＆ 单 / 双</li>
                        <li id="bs_hpa_class" onclick="chg_button_bg('BS','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/BS_<?php /*echo $file_url*/?>/index.php?rtype=p3&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.BS_lid_type,'SI2');chg_type_class('bs_hpa_class');">综合过关</li>
                        <li id="bs_fs_class" onclick="chg_button_bg('BS','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/browse_FS/loadgame_R.php?uid=<?php /*echo $uid*/?>&langx=zh-cn&FStype=BS&mtype=4',parent.BS_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('bs_fs_class');">冠军</li>

                    </ul>-->

                    <div id="title_SK" style="display:none;" onclick="chgTitle('SK');" class="ord_sportSK_off noFloat"><span class="ord_sportName">斯诺克/台球</span><span id="SK_games" class="ord_sportDigit">5</span></div>
                    <ul id="wager_SK" select="wtype_SK_r" class="hide_game_list">
                        <li id="wtype_SK_r" class="On" onclick="chgWtype(this.id);chg_type(this.id,parent.SK_lid_type);">赛事</li>
                        <li id="wtype_SK_fs" onclick="chgWtype(this.id);chg_type(this.id,'');parent.sel_league='';parent.sel_area='';top.hot_game='';" class="no_margin">冠军</li>
                    </ul>
                    <!-- 其他 -->
                    <div id="title_OP" class="ord_sportOP_off noFloat" onclick="chg_button_bg('OP','<?php echo $Mtype ?>');chg_index('<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&showtype=<?php echo  $showtype ?>&langx=<?php echo $langx?>&mtype=4&comshowtype=<?php echo $comshowtype ?>','<?php echo BROWSER_IP?>/app/member/static_page.php?gamename=OP&showtype=<?php echo $showtype ?>',parent.OP_lid_type,'SI2',<?php echo  '\''.$Mtype.'\''?>);">
                        <span class="ord_sportName">其他</span><span id="OP_games" class="ord_sportDigit">0</span>
                    </div>
                   <!-- <div id="title_OP" class="ord_sportOP_off noFloat" onclick="chg_button_bg('OP','<?php /*echo $Mtype */?>');chg_index('<?php /*echo BROWSER_IP*/?>/app/member/select.php?uid=<?php /*echo $uid*/?>&showtype=<?php /*echo  $showtype */?>&langx=<?php /*echo $langx*/?>&mtype=4&comshowtype=<?php /*echo $comshowtype */?>','<?php /*echo BROWSER_IP*/?>/app/member/OP_<?php /*echo $file_url*/?>/index.php?rtype=<?php /*echo $rtype*/?>&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4',parent.OP_lid_type,'SI2',<?php /*echo  '\''.$Mtype.'\''*/?>);">
                        <span class="ord_sportName">其他</span><span id="OP_games" class="ord_sportDigit">0</span>
                    </div>
                    <ul id="wager_OP" select="wtype_OP_r" class="hide_game_list">
                        <li id="op_re_class" class="type_on" onclick="chg_button_bg('OP','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/OP_<?php /*echo $file_url*/?>/index.php?rtype=r&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.OP_lid_type,'SI2');chg_type_class('op_re_class');">独赢 ＆ 让盘 ＆ 大小 ＆ 单 / 双</li>
                        <li id="op_fs_class" onclick="chg_button_bg('OP','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/browse_FS/loadgame_R.php?uid=<?php /*echo $uid*/?>&langx=zh-cn&FStype=OP&mtype=4',parent.BS_lid_type,'SI2');parent.sel_league='';parent.sel_area='';chg_type_class('op_fs_class');">冠军</li>

                    </ul>-->

                </div>
                <div id="FT_today_nodata" style="display:none;" class="ord_noInSports">今天没有赛事</div><!--没赛-->
                <div id="FT_early_nodata" style="display:none;" class="ord_noInSports">没有可供早盘的赛事</div><!--没赛-->
                <div id="FT_parlay_nodata" style="display:none;" class="ord_noInSports">没有综合过关</div><!--没赛-->

                <!--小广告-->
                <div id="hideAD" class="ord_adG">
                    <!-- <span><img src="../../images/order_ad03_cn.jpg"></span>
                     <span><a href=" " target="_blank"><img src="../../images/order_ad01_cn.jpg"></a></span>-->

                </div>


            </div>
        </div>

        <!-- 交易单内容 开始 -->
        <div id="bet_div" name="bet_div" style="display: none;">
            <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" height="0"></iframe>

        </div>

        <!-- 我的注单 开始-->
        <div id="rec5_div" name="rec5_div">
            <iframe id="rec_frame" name="rec_frame" scrolling='NO' frameborder="NO" border="0" height="0"></iframe>
        </div>

    </div>



</div>
<div id='showURL'></div>

<script type="text/javascript" src="../../js/jquery.js"></script><script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../js/src/header.js?v=<?php echo AUTOVER; ?>"></script>
<script class="language_choose" type="text/javascript" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/src/select.js?v=<?php echo AUTOVER; ?>"></script>
<script>


    setHeaderInit(username) ;
    addGameCls() ;

</script>



</body>
</html>