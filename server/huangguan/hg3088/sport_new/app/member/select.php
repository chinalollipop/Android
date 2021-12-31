<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

$uid= $_SESSION['Oid'];
$langx=$_SESSION['langx'];
$live=$_REQUEST['live'];
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'';
$showtype=isset($_REQUEST['showtype'])?$_REQUEST['showtype']:'';
$gtype=isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'FT'; // 默认 FT

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
}else{
    require ("./include/traditional.$langx.inc.php");

	$Status=$_SESSION['Status'];
	$memname=$_SESSION['UserName'];
	$password=$_SESSION['password'];
	$logindate=date("Y-m-d");
	$datetime=date('Y-m-d h:i:s');

}



if(strpos($_SESSION['gameSwitch'],'|')>0){
    $gameArr=explode('|',$_SESSION['gameSwitch']);
}else{
    if(strlen($_SESSION['gameSwitch'])>0){
        $gameArr[]=$_SESSION['gameSwitch'];
    }else{
        $gameArr=array();
    }
}

$icon_path = (TPL_FILE_NAME=='8msport')?'8m/':(TPL_FILE_NAME=='bet365' || TPL_FILE_NAME=='nbet365')?'bet365/':'';



?>

<link rel="stylesheet" type="text/css" href="/<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" >
<link rel="stylesheet" type="text/css" href="/style/member/sports_common.css?v=<?php echo AUTOVER; ?>" >
<style type="text/css" >
    body{height: 100%;overflow-y: auto;overflow-x:hidden;background: #333;}
    .bodyset_8msport{background: #fff;}
</style>

<body class="bodyset bodyset_<?php echo TPL_FILE_NAME;?>" onLoad="bodyLoad('<?php echo $showtype;?>');" >
<div class="SportsMenu">
    <div class="special-leagues-menu">
       <!-- <div class="special-leagues-menu-title clickable">
            <img role="presentation" src="/images/sports/icon_uefa-league-color.png">
            欧洲冠军杯
        </div>-->
        <div class="bet-history-button-box">
            <div class="bet-history-button-box__header">
                <img class="bet-history-button-box__header-icon" src="/images/sports/<?php echo $icon_path;?>icon-down.png" alt="投注记录的图片">
                <span>投注记录</span>
                <img class="bet-history-button-box__header-icon bet-history-button-box__refresh-icon" src="/images/sports/<?php echo $icon_path;?>icon_reload.png" alt="刷新的图片">
            </div>
            <div class="sport_to_betrecord bet-history-button-box__wrapper">
                <a href="javascript:;" class="bet-history-button-box__unsettled">
                    <img class="bet-history-button-box__img" src="/images/sports/<?php echo $icon_path;?>icon_wj.png" alt="未结算的图片">
                    <span class="bet-history-button-box__text">未结算</span>
                </a>
                <a href="javascript:;" class="bet-history-button-box__settled">
                    <img class="bet-history-button-box__img" src="/images/sports/<?php echo $icon_path;?>icon_yj.png" alt="已结算的图片">
                    <span class="bet-history-button-box__text">已结算</span>
                </a>
            </div>
        </div>
        <div>
          <!--  <div class="leagues-menu-items">
                <div class="leagues-menu-item">
                    <div class="menu-item-name">滚球</div>
                    <div class="menu-item-badge">
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner"> </div>
                        </div>
                    </div>
                </div>
                <div class="leagues-menu-item">
                    <div class="menu-item-name">今日</div>
                    <div class="menu-item-badge">
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner">3</div>
                        </div>
                    </div>
                </div>
                <div class="leagues-menu-item">
                    <div class="menu-item-name">早盘</div>
                    <div class="menu-item-badge">
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner">0</div>
                        </div>
                    </div>
                </div>
                <div class="leagues-menu-item">
                    <div class="menu-item-name">串场</div>
                    <div class="menu-item-badge">
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner">2</div>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
    <div class="NonInplayMenu">
        <div class="NonInplayMenuTitle clickable"><img class="left-menu-icon" src="/images/sports/<?php echo $icon_path;?>menu.png" alt="">
            <!-- react-text: 107 -->体育菜单<!-- /react-text --></div>
        <div class="opened non-inplay-menu-content">
            <div class="NonInplayMenuTypes">
                <div class="type f_left <?php echo ($rtype=='re'?'selected':'')?>" data-rtype="re" data-showtype="rb">
                    <div class="menu-type-label">滚球</div>
                    <div>
                        <div class="badge">
                            <div class="badge-spinner on"></div>
                            <div class="badge-inner rb_count_number">0</div>
                        </div>
                    </div>
                </div>
                <div class="type f_left <?php echo ($rtype=='r'?'selected':'')?>" data-rtype="r" data-showtype="today">
                    <div class="menu-type-label">今日</div>
                    <div>
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner on today_count_number">0</div>
                        </div>
                    </div>
                </div>
                <div class="type f_left " data-rtype="r" data-showtype="future">
                    <div class="menu-type-label">早盘</div>
                    <div>
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <div class="badge-inner future_count_number">0</div>
                        </div>
                    </div>
                </div>
                <div class="type f_left p3_tag_nav" data-rtype="p3" data-showtype="today"> <!-- showtype : 今日 today  早盘 future -->
                    <div class="menu-type-label">串场</div>
                    <div>
                        <div class="badge">
                            <div class="badge-spinner"></div>
                            <!--<div class="badge-inner com_count_number">0</div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="non-inplay-menu-sports">
                <div class="sub-list-item bg-list-item sub-list-type-item sport-type">
                    <div class="change_game_type sub-list-sport-title <?php echo ($gtype=='FT'?'sub-list-sport-title-true':'');?>" data-gtype="FT" >
                        <div class="sport-img-wrapper">
                            <span class="subSportIcon ft"> </span>
                        </div>
                        <div class="count ft_count_number">0</div>
                        <div class="sport-name">足球</div>
                        <div class="icon-live-list"></div>
                    </div>
                    <div class="SubNonInplayMenu <?php echo ($gtype=='FT'?'opened':'closed');?>">
                        <div class="sub-menu-item side-p-10 selected" data-gtype="FT" data-rtype="r" data-statusType="r"><span class="sub-menu-item-txt">让球 &amp; 大小盘</span><span class="count"> </span></div>
                        <!--<div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">独赢盘 半场&amp;全场</span><span class="count">104</span></div>
                        <div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">入球数-单/双</span><span class="count">105</span></div>-->
                        <!--<div class="sub-menu-item side-p-10 " data-gtype="FT" data-rtype="t" data-statusType="t"><span class="sub-menu-item-txt">总入球</span><span class="count"> </span></div>
                        <div class="sub-menu-item side-p-10 " data-gtype="FT" data-rtype="f" data-statusType="f"><span class="sub-menu-item-txt">半场/全场</span><span class="count"> </span></div>-->
                        <div class="sub-menu-item side-p-10 sub-menu-item-pd" data-gtype="FT" data-rtype="<?php echo ($rtype=='re'?'rpd':'pd')?>" data-statusType="pd"><span class="sub-menu-item-txt">波胆</span><span class="count"> </span></div>
                        <!--<div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">最先 / 最后进球</span><span class="count">7</span></div>-->
                        <div class="sub-menu-item side-p-10 sub-menu-item-champion" data-gtype="FT" data-rtype="champion"><span class="sub-menu-item-txt">冠军</span><span class="count"> </span></div>
                    </div>
                </div>
                <div class="sub-list-item bg-list-item sub-list-type-item sport-type">
                    <div class="change_game_type sub-list-sport-title <?php echo ($gtype=='BK'?'sub-list-sport-title-true':'');?>" data-gtype="BK" >
                        <div class="sport-img-wrapper">
                            <span class="subSportIcon bk"> </span>
                        </div>
                        <div class="count bk_count_number">0</div>
                        <div class="sport-name">篮球</div>
                    </div>
                    <div class="SubNonInplayMenu <?php echo ($gtype=='BK'?'opened':'closed');?>">
                        <div class="sub-menu-item side-p-10 selected" data-gtype="BK" data-rtype="r" data-statusType="r"><span class="sub-menu-item-txt">让球 &amp; 大小盘</span><span class="count"> </span></div>
                        <div class="sub-menu-item side-p-10 sub-menu-item-champion" data-gtype="BK" data-rtype="champion"><span class="sub-menu-item-txt">冠军</span><span class="count"> </span></div>
                    </div>
                </div>
               <!-- <div class="sub-list-item bg-list-item sport-type">
                    <div class="change_game_type sub-list-sport-title" data-gtype="TN">
                        <div class="sport-img-wrapper">
                            <span class="subSportIcon tn"> </span>
                        </div>
                        <div class="count"> 0 </div>
                        <div class="sport-name">网球</div>
                        <div class="icon-live-list"></div>
                    </div>
                    <div class="SubNonInplayMenu closed">
                        <div class="sub-menu-item side-p-10 selected"><span class="sub-menu-item-txt">让球 &amp; 大小盘</span><span class="count"> </span></div>
                        <div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">盘数波胆</span><span class="count">0</span></div>
                        <div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">冠军</span><span class="count">8</span></div>
                    </div>
                </div>
                <div class="sub-list-item bg-list-item sport-type">
                    <div class="change_game_type sub-list-sport-title" data-gtype="VB">
                        <div class="sport-img-wrapper">
                            <span class="subSportIcon vb"> </span>
                        </div>
                        <div class="count"> 0 </div>
                        <div class="sport-name">棒球</div>
                        <div class="icon-live-list"></div>
                    </div>
                    <div class="SubNonInplayMenu closed">
                        <div class="sub-menu-item side-p-10 selected"><span class="sub-menu-item-txt">让球 &amp; 大小盘</span><span class="count"> </span></div>
                        <div class="sub-menu-item side-p-10 "><span class="sub-menu-item-txt">冠军</span><span class="count"> </span></div>
                    </div>
                </div>-->

            </div>
        </div>
    </div>
</div>
<div class="InfoCenter">
    <div class="sub-list-item bg-list-item bt-sp-0" style="cursor: pointer; height: auto; font-size: 12px; font-weight: normal; margin-bottom: 1px;">
        <div class="to_game_roul side-p-10 w-px-180 h-px-30" style="line-height: 40px; color: white; font-size: 14px;">
            <img role="presentation" class="left-menu-icon__game" src="/images/sports/<?php echo $icon_path;?>game-rule_off.png">
           游戏规则</div>
    </div>
<!--    <div class="sub-list-item bg-list-item bt-sp-0" style="cursor: pointer; height: auto; font-size: 12px; font-weight: normal; margin-bottom: 1px;">
        <div class="side-p-10 w-px-180 h-px-30" style="line-height: 40px; color: white; font-size: 14px;"><img role="presentation" class="left-menu-icon__game" src="/images/sports/game-flow_off.png">
           游戏玩法</div>
    </div>-->
</div>

<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" class="language_choose" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/select.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid = '<?php echo $uid;?>';
</script>
</body>
