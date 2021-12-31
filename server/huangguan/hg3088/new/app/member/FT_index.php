<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "include/address.mem.php";
include "include/config.inc.php";
/*echo "<script>if(self == top) parent.location='".BROWSER_IP."'\n;</script>";*/

$mtype=$_REQUEST['mtype'];
$langx=$_SESSION['langx'];
$showtype=$_REQUEST['showtype'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$uid = $_SESSION['Oid'];
$todaydate=date('Y-m-d');
$username = $_SESSION['UserName'] ;

if ($showtype=="future" or $showtype=="hgfu"){
	$browse="FT_future";
	$index="index";
}else if($showtype=="nfs"){
	$browse="FS_browse";
	$index="loadgame_R";
}else if($showtype=="sk"){
	$browse="browse";
	$index="index";
}else{
	$browse="FT_browse";
	$index="index";
}

//include "./include/redis.php";
//CP登录
	    $redisObj = new Ciredis();
	    
//CP登录
/*
$useridISHGUNIONCP = $redisObj->getSimpleOne($_SESSION['userid'].'_IS_HGUNIONCP');
if(isset($useridISHGUNIONCP) && $useridISHGUNIONCP=='ON'){
	$urlLogin='';
}else{
	    $redisRes = $redisObj->insert($_SESSION['userid'].'_IS_HGUNIONCP','ON',7200);
*/
	    $uniqueUnionCode = getUnionCode();
		$redisRes = $redisObj->setOne($_SESSION['userid'].'_HG_UNION_CP',serialize($uniqueUnionCode));
		$AgentsName = $_SESSION['Agents'] ;
		$resultA = mysqli_query($dbLink,"select ID from ".DBPREFIX."web_agents_data where UserName='$AgentsName'");
		$rowA = mysqli_fetch_assoc($resultA);
		$hg_union_agentid = CP_UNION_VALID;
		$id = CP_UNION_VALID - $_SESSION['userid'];
		$ida = $hg_union_agentid - $rowA['ID'];
		$name = $username;
		$pwd = $_SESSION['password'];
		$key = md5($pwd.$uniqueUnionCode.md5($name));
        $test_flag = $_SESSION['test_flag'] ;
		$urlLogin=HTTPS_HEAD.'://'.CP_URL.'.'.getMainHost().'/login/login_ok_api.winer?agent='.CP_AGENT.'&id='.$id.'&ida='.$ida.'&name='.$name.'&pwd='.$pwd.'&key='.$key.'&langx='.$langx.'&flag='.$test_flag ;
//		echo "<script src=".$urlLogin." ></script>";
//}
		

//创建登录旧版连接地址
	 $oldUnionCode = getUnionCode();
	 $redisRes = $redisObj->setOne($_SESSION['userid'].'_NEW_CHANGE_OLD',serialize($oldUnionCode));
	 $keyOld = md5($pwd.$oldUnionCode.md5($name));
	 $idOld=$_SESSION['userid']+9876889;

    $afterurl = returnNewOldVersion('old'); // 随机取一个配置的域名
	 //$oldLogin=HTTPS_HEAD.'://'.getMainHost().'/login.php?id='.$idOld.'&sign=newchangeold&username='.$name.'&password='.$pwd.'&key='.$keyOld.'&langx='.$langx;
	// $oldLogin=HTTPS_HEAD.'://'.getMainHost().'/login.php';
	 $oldLogin= $afterurl.'/login.php';


// 会员弹窗信息
$membermessage = getMemberMessage($username,'0'); // 系统短信

//if ($mcou==1){
//    echo '<script>if (top.game_alert.indexOf(\'Message\')==-1){alert("'.$mem_message.'"); top.game_alert+=\'Message,\'}</script>';
//}

?>
<html>
<head>
<title>欢迎光临投注</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../style/member/mem_header_ft_cn.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .euro_btn{height:45px;cursor:pointer;background:0 0}
        .left_banner{margin-top:5px}
        .left_banner .kyqp a{background:#fff url(../../images/ico-kyqp.png) no-repeat 17px top}
        .left_banner .live a{background:#fff url(../../images/ico-zhenren.png) no-repeat 17px top}
        .left_banner .lottery a{background:#fff url(../../images/ico-caipiao.png) no-repeat 17px top}
        .left_banner .pt a{background:#fff url(../../images/ico-dianzi.png) no-repeat 17px top}
        .left_banner .fish a{background:#fff url(/images/kill_fish.png) no-repeat 17px 6px}
        .left_banner .phone a{background:#fff url(/images/phone_ico.png) no-repeat 17px 6px}
        .left_banner .euro_btn a:hover{text-decoration:none;background-color:#ac6708;background-position:17px bottom;color:#fff}
        .left_banner .euro_btn a{text-decoration:none;float:left;padding-left:68px;width:265px;height:44px;line-height:44px;border-right:1px solid #856c4e;border-bottom:1px solid #856c4e;font-size:18px;color:#000;font-family:"Microsoft Yahei";background-color:#fff}

    </style>
</head>


<body class="index_body">

<!-- 2018 新版开始 -->
<div class="indexW_mid">
    <div id="header" class="header_top">
        <div class="head_main noFloat">
            <!--帐户区-->
            <div class="head_accDIV">

                        <span class="head_acc">
                        <span id="sel_div_acc" class="head_accBTN"></span><!--将按钮图案取出-->
                        <div id="div_acc" class="head_MINImenu" style="display:none" onmouseleave="hideDiv('div_acc');" >
                            <span class="head_MINImenu_arr"></span>
                        <h1>我的帐户</h1>
                        <ul class="head_MINIul">
                        <li id="hide_balance" onclick="hideMoney('hide')" >隐藏余额</li>
                        <li id="show_balance" onclick="hideMoney()" style="display:none;">显示余额</li>
                       <!-- <li onclick="" >详细设定</li>
                        <li onclick=" ">密码恢复 <span id="mail_status" class="head_annouTD_new"></span></li>-->
                        <li onclick="openPublicAction('changepsw')" >更改密码</li>
                        <li onclick="window.location.href='/app/member/logout.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>'" >退出</li>
                        </ul>
                        </div>
                        </span>

                <div class="head_cre">
                    <?php if($_SESSION['Agents']=='demoguest'){?><h1><strong>试玩玩家<strong></h1><?php } ?>
                    <h1 id="head_cre" <?php if($_SESSION['Agents']=='demoguest'){ echo "style='display:none'"; }?>></h1>
                    <h2 id="credit"> </h2><span class="head_refresh" onclick="reloadCrditFunction();"></span>
                </div>
            </div>

            <div class="head_Right noFloat">
                <!--功能按钮区-->
                <ul id="head_MINI" class="head_OUTmenu noFloat CN">
                    <li class="head_lan no_margin" title="语言转换">
                        <span id="sel_div_langx" class="head_lanBTN"></span><!--将按钮图案取出-->
                        <span class="head_lanTxt">简</span>
                        <div id="div_langx" class="head_MINImenu" style="display:none" onmouseleave="hideDiv('div_langx');" tabindex="100">
                            <span class="head_MINImenu_arr"></span>
                            <h1>语言</h1>
                            <ul class="head_MINIul">
                                <li class="head_en" onclick="changeLangx('en-us')" >English</li>
                                <li class="head_tw" onclick="changeLangx('zh-tw')" >繁体</li>
                                <li class="head_cn" onclick="changeLangx('zh-cn')" >简体</li>
                                <li style="display:none" class="head_kr" onclick="changeLangx('ko-kr')">한국어</li>
                            </ul>
                        </div>
                    </li>

                    <li class="head_help" title="帮助">
                        <span id="sel_div_help" class="head_helpBTN"></span><!--将按钮图案取出-->
                        <div id="div_help" class="head_MINImenu" style="display:none" onmouseleave="hideDiv('div_help');" tabindex="100">
                            <span class="head_MINImenu_arr"></span>
                            <h1>帮助</h1>
                            <ul class="head_MINIul">
                                <li onclick="openPublicAction('sportsrule')" >体育规则</li>
                                <li onclick="openPublicAction('sportsroul')" >规则与条款</li>
                                <!--<li onclick="openPublicAction('')" >新功能</li>-->
                                <li onclick="openPublicAction('sportsway')" >赔率计算列表</li>
                            </ul>
                        </div>
                    </li>

                    <li class="head_con" onclick="openPublicAction('sportsconn')" title="联系我们"></li>
                    <li id="head_live" class="head_live" onclick="" title="在线直播"></li> <!-- OpenLive() -->

                    <li id="head_ann" class="head_ann" onclick="openPublicAction('morescroll')" title="公告">
                        <span id="head_annBTN" class="head_annGIF" onclick="">
                        <span id="count_ann" class="head_annMINI" style="display: none"> 5 </span></span><!--将按钮图案取出-->
                        <span id="head_ann_arr" class="head_MINImenu_arr" style="display:none"></span>
                        <iframe id="annou" name="annou" class="head_annouDIV" style="display:none;" onmouseleave="hideDiv('annou');"></iframe>
                    </li>
                </ul>

                <ul class="head_Left">
                    <li class="head_time" id="head_date" style="">
                        <span id="head_year" class="head_space"> </span>
                    </li>
                    <li id="chg_site" style="">
                        <form action="<?php echo $oldLogin?>" method="post" name="new_to_old" id="new_to_old" target="_top">
                            <input type="hidden" name="id" value="<?php echo $idOld?>">
                            <input type="hidden" name="sign" value="newchangeold">
                            <input type="hidden" name="username" value="<?php echo $username?>">
                            <input type="hidden" name="password" value="<?php echo $_SESSION['password']?>">
                            <input type="hidden" name="key" value="<?php echo $keyOld?>">
                            <input type="hidden" name="langx" value="<?php echo $langx?>">
                            <a class="" id="chg_site_a"  href="javascript:;" onclick="document.getElementById('new_to_old').submit()">切换至旧版</a>
                        </form>

                    </li><!-- goto_old_version -->
                    <?php if($_SESSION['Agents']!='demoguest'){?>
                    <li><a href="<?php echo BROWSER_IP?>/app/member/onlinepay/deposit_withdraw.php?uid=<?php echo $uid ?>&langx=<?php echo $langx ?>&username=<?php echo $username?>" target="body" >存取款中心</a></li>
                    <?php } ?>
                    <li id="btn_openbets" onclick="openPublicAction('openbets')">交易状况</li>
                    <li id="btn_history" onclick="openPublicAction('account')">帐户历史</li>
                    <li id="btn_result" onclick="openPublicAction('gameresult')">赛果</li>

                </ul>
            </div>
        </div>
        <div>
            <div id="showURL"></div>
            <iframe id="memOnline" name="memOnline" height="0" width="0" style="display:none;"></iframe>
            <iframe id="reloadPHP" name="reloadPHP"  width="0" height="0" style="display:none;"></iframe>
            <iframe id="reloadPHP1" name="reloadPHP1"  width="0" height="0" style="display:none;"></iframe>
        </div>
    </div>
<!-- 2018 新版结束 -->

    <!--左侧区域 -->
    <div class="index_leftTD">
        <div id="loadingL" class="index_loadDIV" style="display:none;"></div>
        <iframe id="mem_order" name="mem_order" noresize="" scrolling="NO" src="<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype?>" width="100%" height="100%" frameborder="0">

        </iframe>
    </div>

    <!-- 中间部分 -->
    <div class="index_midTD">
        <div id="status_s_zh-tw" class="status_errDIV" style="display:none;">您的帳戶狀態已被改為 “只能看帳”。<br>您只能繼續查看<tt onclick="showMyAccount('OpenBets');">交易狀況</tt>和<tt onclick="showMyAccount('Statement');">帳戶歷史</tt>。</div>
        <div id="status_s_zh-cn" class="status_errDIV" style="display:none">您的帐户状态已被改为 “只能看帐”。<br>您只能继续查看<tt onclick="showMyAccount('OpenBets');">交易状况</tt>和<tt onclick="showMyAccount('Statement');">帐户历史</tt>。</div>
        <div id="status_s_en-us" class="status_errDIV" style="display:none">Your account has been changed to ‘View Only’ access.<br>You may only access <tt onclick="showMyAccount('OpenBets');">Open Bets</tt> and <tt onclick="showMyAccount('Statement');">Statements</tt>.</div>
        <div id="status_s_ko-kr" class="status_errDIV" style="display:none">고객님의 계정이 "조회 전용" 상태로 전환 되었습니다.<br>고객님은 <tt onclick="showMyAccount('OpenBets');">미정산 베팅</tt> 및 <tt onclick="showMyAccount('Statement');">계정내역에</tt> 한하여 이용하실 수 있습니다.</div>
        <div id="loading" class="index_loadDIV" style="display: none;"><div class="index_loadDIV_edge"></div></div>
        <div id="body_view" name="body_view" style="width:100%;height:100%;">
            <iframe id="body" name="body" src="<?php echo BROWSER_IP?>/app/member/<?php echo $browse?>/<?php echo $index?>.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype?>&league_id=<?php echo $league_id?>&showtype=<?php echo $showtype?>" width="100%" height="100%" frameborder="0">

            </iframe>
        </div>
    </div>
    <!-- 右侧 -->
    <div id="top_tv" class="index_rightTD coffee " style="background: #19130f;">
<!--        <div id="noTV" class="index_noDIV" style="display:none">-->
<!--        </div>-->
<!--        <div id="loadingR" class="index_loadDIV" style="display:none"></div>-->
<!--        <iframe id="show_tv" name="show_tv" src="--><?php //echo BROWSER_IP?><!--/app/member/right_live.php?uid=--><?php //echo $uid?><!--&langx=--><?php //echo $langx?><!--&opentype=self"  noresize="" scrolling="NO" width="100%" height="100%" frameborder="0"></iframe>-->
<!--    </div>-->

        <!--公告-->
        <iframe id="live" name="live" height="430" src="<?php echo BROWSER_IP?>/app/member/live/live.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>"></iframe>
        <div id="banner" class="left_banner">
           <!-- <a href="../../app/member/zrsx/index.php?uid=<?php /*echo $uid;*/?>" target="body"><img src="../../images/banner/1.png?v=<?php /*echo AUTOVER;*/?>" style="border:none"></a>
            <a href="games.php?uid=<?php /*echo $uid;*/?>" target="_blank" ><img src="../../images/banner/2.png?v=<?php /*echo AUTOVER;*/?>" style="border:none"></a>
            <a href="../../tpl/lottery.php?uid=<?php /*echo $uid;*/?>" target="body"><img src="../../images/banner/3.png?v=<?php /*echo AUTOVER;*/?>" style="border:none"></a>
            <a href="../../app/member/ky/ky_games.php?uid=<?php /*echo $uid;*/?>" target="_blank"><img src="../../images/banner/kyqp.jpg?v=<?php /*echo AUTOVER;*/?>" style="border:none"></a>
            <a href="../../tpl/downloadapp.html" target="body" ><img src="../../images/banner/4.png?v=<?php /*echo AUTOVER;*/?>" style="border:none"></a>-->
            <div class="euro_btn kyqp" >
                <a href="../../app/member/chess_game.php?uid=<?php echo $uid;?>" target="body">棋牌游戏</a>
            </div>
            <div class="euro_btn live">
                <a href="../../app/member/zrsx/index.php?uid=<?php echo $uid;?>" target="body">真人娱乐</a>
            </div>
            <div class="euro_btn lottery">
                <a href="../../tpl/lottery.php?uid=<?php echo $uid;?>" target="body">彩票游戏</a>
            </div>
            <div class="euro_btn pt" >
                <a href="middle_games.php?uid=<?php echo $uid;?>" target="body">电子游艺</a>
            </div>
            <div class="euro_btn fish" >
                <a href="zrsx/fishing.php?uid=<?php echo $uid;?>" target="body">捕鱼游戏</a>
            </div>
            <div class="euro_btn phone" style=" margin-bottom: 5px;">
                <a href="../../tpl/downloadapp.html" target="body">手机投注</a>
            </div>
         
        </div>
        <div id="info_div" name="info_div" class="right_gonggao" >
            <div class="msg_box">
                <h1>公告<span class="more"><a href="#" onClick="openPublicAction('morescroll');">更多&nbsp;&nbsp;</a></span> </h1>
                <div class="msg_main">
                    <marquee height="90" scrollAmount="1" direction="up" onMouseOver="this.stop();" onMouseOut="this.start();">
                        <span id="real_msg"><?php echo getScrollMsg();?></span>
                    </marquee>
                </div>
            </div>
        </div>
</div>
<iframe name="bottom" scrolling="NO" noresize src="<?php echo $urlLogin;?>" ></iframe> <!-- 彩票联合登陆 -->

<script type="text/javascript" src="../../js/jquery.js"></script><script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../js/header.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    top.g_date = '<?php echo $todaydate?>';
    top.memberNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    top.memberMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息

try{
	FT_lid_ary=top.FT_lid['FT_lid_ary'];
	FT_lid_type=top.FT_lid['FT_lid_type'];
	FT_lname_ary=top.FT_lid['FT_lname_ary'];
	FT_lid_ary_RE=top.FT_lid['FT_lid_ary_RE'];
	FT_lname_ary_RE=top.FT_lid['FT_lname_ary_RE'];
	FU_lid_ary=top.FU_lid['FU_lid_ary'];
	FU_lid_type=top.FU_lid['FU_lid_type'];
	FU_lname_ary=top.FU_lid['FU_lname_ary'];
	FSFT_lid_ary=top.FSFT_lid['FSFT_lid_ary'];
	FSFT_lname_ary=top.FSFT_lid['FSFT_lname_ary'];
}catch(E){
	initlid_FT();
}
try{	
	BK_lid_ary=top.BK_lid['BK_lid_ary'];
	BK_lid_type=top.BK_lid['BK_lid_type'];
	BK_lname_ary=top.BK_lid['BK_lname_ary'];
	BK_lid_ary_RE=top.BK_lid['BK_lid_ary_RE'];
	BK_lname_ary_RE=top.BK_lid['BK_lname_ary_RE'];
	BU_lid_ary=top.BU_lid['BU_lid_ary'];
	BU_lid_type=top.BU_lid['BU_lid_type'];
	BU_lname_ary=top.BU_lid['BU_lname_ary'];
	FSBK_lid_ary=top.FSBK_lid['FSBK_lid_ary'];
	FSBK_lname_ary=top.FSBK_lid['FSBK_lname_ary'];	
}catch(E){
	initlid_BK();
}  	
try{
	BS_lid_ary=top.BS_lid['BS_lid_ary'];
	BS_lid_type=top.BS_lid['BS_lid_type'];
	BS_lname_ary=top.BS_lid['BS_lname_ary'];
	BS_lid_ary_RE=top.BS_lid['BS_lid_ary_RE'];
	BS_lname_ary_RE=top.BS_lid['BS_lname_ary_RE'];
	BSFU_lid_ary=top.BSFU_lid['BSFU_lid_ary'];
	BSFU_lid_type=top.BSFU_lid['BSFU_lid_type'];
	BSFU_lname_ary=top.BSFU_lid['BSFU_lname_ary'];
	FSBS_lid_ary=top.FSBS_lid['FSBS_lid_ary'];	
	FSBS_lname_ary=top.FSBS_lid['FSBS_lname_ary'];	
}catch(E){
	initlid_BS();
}
try{
	TN_lid_ary=top.TN_lid['TN_lid_ary'];
	TN_lid_type=top.TN_lid['TN_lid_type'];
	TN_lname_ary=top.TN_lid['TN_lname_ary'];
	TN_lid_ary_RE=top.TN_lid['TN_lid_ary_RE'];
	TN_lname_ary_RE=top.TN_lid['TN_lname_ary_RE'];
	TU_lid_ary=top.TU_lid['TU_lid_ary'];
	TU_lid_type=top.TU_lid['TU_lid_type'];
	TU_lname_ary=top.TU_lid['TU_lname_ary'];
	FSTN_lid_ary=top.FSTN_lid['FSTN_lid_ary'];	
	FSTN_lname_ary=top.FSTN_lid['FSTN_lname_ary'];	
}catch(E){
	initlid_TN();
}  
try{
	VB_lid_ary=top.VB_lid['VB_lid_ary'];
	VB_lid_type=top.VB_lid['VB_lid_type'];
	VB_lname_ary=top.VB_lid['VB_lname_ary'];
	VB_lid_ary_RE=top.VB_lid['VB_lid_ary_RE'];
	VB_lname_ary_RE=top.VB_lid['VB_lname_ary_RE'];
	VU_lid_ary=top.VU_lid['VU_lid_ary'];
	VU_lid_type=top.VU_lid['VU_lid_type'];
	VU_lname_ary=top.VU_lid['VU_lname_ary'];
	FSVB_lid_ary=top.FSVB_lid['FSVB_lid_ary'];
	FSVB_lname_ary=top.FSVB_lid['FSVB_lname_ary'];	
}catch(E){
	initlid_VB();
}  
try{
	OP_lid_ary=top.OP_lid['OP_lid_ary'];
	OP_lid_type=top.OP_lid['OP_lid_type'];
	OP_lname_ary=top.OP_lid['OP_lname_ary'];
	OP_lid_ary_RE=top.OP_lid['OP_lid_ary_RE'];
	OP_lname_ary_RE=top.OP_lid['OP_lname_ary_RE'];
	OM_lid_ary=top.OM_lid['OM_lid_ary'];
	OM_lid_type=top.OM_lid['OM_lid_type'];
	OM_lname_ary=top.OM_lid['OM_lname_ary'];
	FSOP_lid_ary=top.FSOP_lid['FSOP_lid_ary'];
	FSOP_lname_ary=top.FSOP_lid['FSOP_lname_ary'];	
}catch(E){
	initlid_OP();
}    	

    function initlid_FT(){
        top.FT_lid = new Array();
        top.FU_lid = new Array();
        top.FSFT_lid = new Array();
        top.FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
        top.FT_lid['FT_lid_type']= FT_lid_type='';
        top.FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
        top.FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
        top.FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
        top.FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
        top.FU_lid['FU_lid_type']= FU_lid_type='';
        top.FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
        top.FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
        top.FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';
    }
    function initlid_BK(){
        top.BK_lid = new Array();
        top.BU_lid = new Array();
        top.FSBK_lid = new Array();
        top.BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
        top.BK_lid['BK_lid_type']= BK_lid_type='';
        top.BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
        top.BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
        top.BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
        top.BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
        top.BU_lid['BU_lid_type']= BU_lid_type='';
        top.BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
        top.FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
        top.FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';
    }
    function initlid_BS(){
        top.BS_lid = new Array();
        top.BSFU_lid = new Array();
        top.FSBS_lid = new Array();
        top.BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
        top.BS_lid['BS_lid_type']= BS_lid_type='';
        top.BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
        top.BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
        top.BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
        top.BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
        top.BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
        top.BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
        top.FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
        top.FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';
    }
    function initlid_TN(){
        top.TN_lid = new Array();
        top.TU_lid = new Array();
        top.FSTN_lid = new Array();
        top.TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
        top.TN_lid['TN_lid_type']= TN_lid_type='';
        top.TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
        top.TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
        top.TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
        top.TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
        top.TU_lid['TU_lid_type']= TU_lid_type='';
        top.TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
        top.FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';
        top.FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';
    }
    function initlid_VB(){
        top.VB_lid = new Array();
        top.VU_lid = new Array();
        top.FSVB_lid = new Array();
        top.VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
        top.VB_lid['VB_lid_type']= VB_lid_type='';
        top.VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
        top.VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
        top.VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
        top.VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
        top.VU_lid['VU_lid_type']= VU_lid_type='';
        top.VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
        top.FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
        top.FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL'
    }
    function initlid_OP(){
        top.OP_lid = new Array();
        top.OM_lid = new Array();
        top.FSOP_lid = new Array();
        top.OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
        top.OP_lid['OP_lid_type']= OP_lid_type='';
        top.OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
        top.OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
        top.OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
        top.OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
        top.OM_lid['OM_lid_type']= OM_lid_type='';
        top.OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
        top.FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
        top.FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
    }

 // goToOldVersion() ;

    function changeColor(){
        var color="#f00|#0f0|#00f|#880|#808|#088|yellow|green|blue|gray";
        color=color.split("|");
        document.getElementById("chg_site_a").style.color=color[parseInt(Math.random() * color.length)];
    }
    setInterval("changeColor()",200);

    function showMoreMsg(){
        window.open('./scroll_history.php?uid='+top.uid+'&langx='+top.langx,"History","width=617,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
    }
    reloadCrditFunction() ;
    setInterval("headerShowTimer('#head_date')",1000);
    setInterval("reloadCrditFunction()",3000);

</script>

</body>

</html>