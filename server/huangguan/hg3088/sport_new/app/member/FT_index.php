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
$uid=$_SESSION['Oid'];
$mtype=4 ;
$langx=$_SESSION['langx'];
$showtype=isset($_REQUEST['showtype'])?$_REQUEST['showtype']:'';
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'';
$gtype=isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'FT'; // 默认 FT
$gtype=strtoupper($gtype);
if($gtype==''){
    $gtype = 'FT';
}

//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}

$username=$_SESSION['UserName'];
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
	$browse="".$gtype."_browse"; // FT, BK
	$index="index";
}

$membermessage = getMemberMessage($username,'0'); // 系统短信

$icon_path = (TPL_FILE_NAME=='8msport')?'8m/':(TPL_FILE_NAME=='bet365' || TPL_FILE_NAME=='nbet365')?'bet365/':'';

?>

<link rel="stylesheet" type="text/css" href="/style/member/sports_common.css?v=<?php echo AUTOVER; ?>" >
<style>
    .sport_app_download{position: relative;cursor:pointer;width:100%;height: 360px;background: url(<?php echo TPL_NAME;?>images/app_download.jpg?v=6) no-repeat;background-size: 100%;}
    .sport_app_download span{position:absolute;display:block;width:82px;height:82px;background-size:100% !important;top:82px;left:35px}
    .sport_app_download span.app_6668,.sport_app_download span.app_0086{width: 134px;height: 134px;top: 133px;left: 16px;}
    .sport_app_download span.app_8msport{display: none;}
    .sport_app_download span.app_wnsr{width: 92px;height: 92px;top: 81px;left: 32px;}
    .sport_app_download span.app_3366,.sport_app_download span.app_newhg,.sport_app_download span.app_bet365,.sport_app_download span.app_nbet365{width: 68px;height: 68px;top: 115px;left: 203px;}
    .sport_app_download span.app_jinsha{width: 135px;height: 135px;top: 364px;left: 85px;}
    .sport_app_download span:last-child {top: 199px;}
    .sport_app_download span.app_wnsr:last-child{top: 205px;}
    .sport_app_download span.app_3366:last-child,.sport_app_download span.app_newhg:last-child,.sport_app_download span.app_bet365:last-child,.sport_app_download span.app_nbet365:last-child{top: 224px;}
    .sport_app_download span.app_jinsha:last-child {top: 80px;}
    .sport_app_download span.app_6668:last-child,.sport_app_download span.app_0086:last-child{top: 133px;left: 162px;}
</style>

<div class="sport_content_all sport_content_all_<?php echo TPL_FILE_NAME;?>">
    <!--左侧区域 -->
    <div class="LeftSection CustomScrollbar">

        <iframe id="mem_order" name="mem_order" noresize="" scrolling="NO" src="<?php echo BROWSER_IP?>/app/member/select.php?gtype=<?php echo $gtype;?>&uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&showtype=<?php echo $showtype?>&mtype=<?php echo $mtype?>" width="100%" height="100%" frameborder="0">

        </iframe>
    </div>

    <!-- 中间部分 -->
    <div class="CenterSection">
        <div id="body_view" name="body_view" style="width:100%;height:100%;">
            <iframe id="body" name="body" src="<?php echo BROWSER_IP?>/app/member/<?php echo $browse?>/<?php echo $index?>.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&mtype=<?php echo $mtype?>&league_id=<?php echo $league_id?>&showtype=<?php echo $showtype?>" width="100%" height="100%" frameborder="0">

            </iframe>
        </div>
    </div>

    <!-- 右侧 -->
    <div class="RightSection enlarge CustomScrollbar CustomScrollbar_<?php echo TPL_FILE_NAME;?>">
        <div class="RightSectionHeader">
            <div class="RightSectionTitle">
                <div class="f_left RightSectionSubTitle selected not-logged-in">
                    <img class="bet-form-icon" src="/images/sports/<?php echo $icon_path;?>icon_move.png" alt="投注单的图片"><span class="cur-p">投注单</span></div>
            </div>
        </div>
        <div class="liveList1 CustomScrollbar">
            <div>
                <div class="BetForm enlarge"> <!-- hide -->
                    <div class="p-h-t-10 side-p-10 top-sub-list-style no-bet add-bet-container">
                        <img class="folder-icon enlarge" src="/images/sports/<?php echo $icon_path;?>icon_add.png" alt="添加选项到投注单的图片">
                        添加选项到投注单
                    </div>
                    <!-- 下注区域 -->
                    <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" height="0"></iframe>
                    <!-- 下注区域 -->
                </div>


                <!--<div class="BetForm enlarge hide">
                    <div>
                        <div class="singleBet">
                            <span><div class="bet-sp-b">
                                    <div class="p-h-t-10 side-p-10 top-sub-list-style bet-detail-wrapper bet-sp-b">
                                        <div class="FormTopTitle">
                                            <div class="f_left infoIcon0 infoIcon tooltip no-margin">
                                                <div class="info">i</div>

                                            </div>
                                            <div class="SingleBetTopTitle no-margin">
                                                <div class="title-label">滚球 独赢</div>
                                                <div class="title-score"></div>
                                            </div>
                                            <div class="f_right cancel no-margin">
                                                <img src="/images/sports/icon_cancel.png" class="display-block" role="presentation">
                                            </div></div><hr class="bet-form--horizontal-rule enlarge">
                                        <div class="betFormDetails">
                                            <span class="betTeamTitle">添田豪(日本)</span>
                                            <span class="odd-color"></span>
                                            <b class="odd-color">1.28</b>
                                        </div>
                                        <div class="bet-form-details__teams">
                                        </div>
                                    </div>
                                </div>
                                <b><div class="p-h-t-10 side-p-10 top-sub-list-style bet-sp-b" style="padding-top: 0px; padding-bottom: 0px; height: 35px;"><div class="bet-amt-input-container enlarge">
                                            <img src="/images/sports/btn_close_dark.png" class="btn-clear-input" role="presentation"><input id="textfield1" value="" placeholder="投注额" class="side-p-10 bet-amt-input enlarge"></div><input type="text" disabled="" value="" placeholder="可赢金额" class="side-p-10 f_right single-bet-amt-inverse-input"><br></div><div style="line-height: 30px;">
                                        <div class="nopanel f_left w-p-100"><div style="padding: 0px 9px; margin-top: 9px;"><div class="f_left valueBtn enlarge">+100</div>
                                                <div class="f_left valueBtn enlarge">+500</div><div class="f_left valueBtn enlarge">+1000</div><div class="f_left valueBtn enlarge">+5000</div><div class="f_left valueBtn enlarge">+10000</div><div class="f_left valueBtn enlarge">+50000</div></div></div></div><div style="word-break: keep-all;"></div></b></span></div>
                        <div class="p-h-t-10 side-p-10 top-sub-list-style main bet-form-part1" style="padding-top: 0px; padding-bottom: 0px;">
                            <div class="allBetTitle">

                                <div class="allBet">

                                </div>
                            </div>
                            <div class="winAmountTitle">

                                <div class="winAmount1">

                                </div>
                            </div>
                            <div style="color: rgb(90, 90, 90); text-align: center; margin-bottom: 4px;">请登录
                            </div>
                            <div id="nowReg" class="w-p-100 cur-p bet-form-btn" style="background: rgb(255, 146, 0); color: rgb(255, 255, 255);">立即注册
                            </div>
                            <div class="w-p-100 cur-p bet-form-btn cancel no-margin">取消</div>
                        </div>
                    </div>
                </div>-->

            </div>
            <!--<div class="LiveSoccerShow">
                <div class="bt-sp-0 LiveSoccerShowTitleMenu" style="margin-bottom: 1px; line-height: 40px; font-weight: bold;">
                    <div>
                        <img class="live-center-icon" src="/images/sports/icon_live.png" alt="直播中心的图片">
                        <b class="side-p-10" style="font-size: 14px; color: white; font-weight: normal;">直播中心</b>
                    </div>
                    <div class="LiveSoccerShowTitleMenu-icon-wrapper"><span class="LiveSoccerShowTitleMenu-icon-wrapper__icon LiveSoccerShowTitleMenu-icon-wrapper__icon-info"></span><span class="LiveSoccerShowTitleMenu-icon-wrapper__icon LiveSoccerShowTitleMenu-icon-wrapper__icon-minimize"></span>
                    </div>
                </div>
                <div class="Livecontainer">
                    <div class="NoMatchDisplay">当前没有现场直播赛事</div>
                </div>
            </div>-->
            <?php
                if(TPL_FILE_NAME !='8msport'){
                    echo '<a class="Ads to_livechat" href="javascript:;">
                                <div class="contact-us-title">联系我们</div>
                                <img class="contact-us" src="'.TPL_NAME.'images/contact_us.jpg?v=2">
                            </a>';
                }
            ?>

            <div class="to_downloadapp sport_app_download <?php echo (TPL_FILE_NAME=='0086dj'?'hide':'');?>" <?php echo ((TPL_FILE_NAME=='jinsha' || TPL_FILE_NAME=='8msport')?'style="height:566px;"':'');?> >
                <span class="download_ios_app app_<?php echo TPL_FILE_NAME;?>"></span>
                <span class="download_android_app app_<?php echo TPL_FILE_NAME;?>"></span>
            </div>

        </div>
    </div>

</div>

<script type="text/javascript" src="js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/header.js?v=<?php echo AUTOVER; ?>"></script>
<script>

    try{
        FT_lid_ary= FT_lid['FT_lid_ary'];
        FT_lid_type= FT_lid['FT_lid_type'];
        FT_lname_ary= FT_lid['FT_lname_ary'];
        FT_lid_ary_RE= FT_lid['FT_lid_ary_RE'];
        FT_lname_ary_RE= FT_lid['FT_lname_ary_RE'];
        FU_lid_ary= FU_lid['FU_lid_ary'];
        FU_lid_type= FU_lid['FU_lid_type'];
        FU_lname_ary= FU_lid['FU_lname_ary'];
        FSFT_lid_ary= FSFT_lid['FSFT_lid_ary'];
        FSFT_lname_ary= FSFT_lid['FSFT_lname_ary'];
    }catch(E){
        initlid_FT();
    }
    try{
        BK_lid_ary= BK_lid['BK_lid_ary'];
        BK_lid_type= BK_lid['BK_lid_type'];
        BK_lname_ary= BK_lid['BK_lname_ary'];
        BK_lid_ary_RE= BK_lid['BK_lid_ary_RE'];
        BK_lname_ary_RE= BK_lid['BK_lname_ary_RE'];
        BU_lid_ary= BU_lid['BU_lid_ary'];
        BU_lid_type= BU_lid['BU_lid_type'];
        BU_lname_ary= BU_lid['BU_lname_ary'];
        FSBK_lid_ary= FSBK_lid['FSBK_lid_ary'];
        FSBK_lname_ary= FSBK_lid['FSBK_lname_ary'];
    }catch(E){
        initlid_BK();
    }
    try{
        BS_lid_ary= BS_lid['BS_lid_ary'];
        BS_lid_type= BS_lid['BS_lid_type'];
        BS_lname_ary= BS_lid['BS_lname_ary'];
        BS_lid_ary_RE= BS_lid['BS_lid_ary_RE'];
        BS_lname_ary_RE= BS_lid['BS_lname_ary_RE'];
        BSFU_lid_ary= BSFU_lid['BSFU_lid_ary'];
        BSFU_lid_type= BSFU_lid['BSFU_lid_type'];
        BSFU_lname_ary= BSFU_lid['BSFU_lname_ary'];
        FSBS_lid_ary= FSBS_lid['FSBS_lid_ary'];
        FSBS_lname_ary= FSBS_lid['FSBS_lname_ary'];
    }catch(E){
        initlid_BS();
    }
    try{
        TN_lid_ary= TN_lid['TN_lid_ary'];
        TN_lid_type= TN_lid['TN_lid_type'];
        TN_lname_ary= TN_lid['TN_lname_ary'];
        TN_lid_ary_RE= TN_lid['TN_lid_ary_RE'];
        TN_lname_ary_RE= TN_lid['TN_lname_ary_RE'];
        TU_lid_ary= TU_lid['TU_lid_ary'];
        TU_lid_type= TU_lid['TU_lid_type'];
        TU_lname_ary= TU_lid['TU_lname_ary'];
        FSTN_lid_ary= FSTN_lid['FSTN_lid_ary'];
        FSTN_lname_ary= FSTN_lid['FSTN_lname_ary'];
    }catch(E){
        initlid_TN();
    }
    try{
        VB_lid_ary= VB_lid['VB_lid_ary'];
        VB_lid_type= VB_lid['VB_lid_type'];
        VB_lname_ary= VB_lid['VB_lname_ary'];
        VB_lid_ary_RE= VB_lid['VB_lid_ary_RE'];
        VB_lname_ary_RE= VB_lid['VB_lname_ary_RE'];
        VU_lid_ary= VU_lid['VU_lid_ary'];
        VU_lid_type= VU_lid['VU_lid_type'];
        VU_lname_ary= VU_lid['VU_lname_ary'];
        FSVB_lid_ary= FSVB_lid['FSVB_lid_ary'];
        FSVB_lname_ary= FSVB_lid['FSVB_lname_ary'];
    }catch(E){
        initlid_VB();
    }
    try{
        OP_lid_ary= OP_lid['OP_lid_ary'];
        OP_lid_type= OP_lid['OP_lid_type'];
        OP_lname_ary= OP_lid['OP_lname_ary'];
        OP_lid_ary_RE= OP_lid['OP_lid_ary_RE'];
        OP_lname_ary_RE= OP_lid['OP_lname_ary_RE'];
        OM_lid_ary= OM_lid['OM_lid_ary'];
        OM_lid_type= OM_lid['OM_lid_type'];
        OM_lname_ary= OM_lid['OM_lname_ary'];
        FSOP_lid_ary= FSOP_lid['FSOP_lid_ary'];
        FSOP_lname_ary= FSOP_lid['FSOP_lname_ary'];
    }catch(E){
        initlid_OP();
    }


    function initlid_FT(){
        FT_lid = new Array();
        FU_lid = new Array();
        FSFT_lid = new Array();
        FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
        FT_lid['FT_lid_type']= FT_lid_type='';
        FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
        FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
        FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
        FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
        FU_lid['FU_lid_type']= FU_lid_type='';
        FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
        FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
        FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';
    }
    function initlid_BK(){
        BK_lid = new Array();
        BU_lid = new Array();
        FSBK_lid = new Array();
        BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
        BK_lid['BK_lid_type']= BK_lid_type='';
        BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
        BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
        BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
        BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
        BU_lid['BU_lid_type']= BU_lid_type='';
        BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
        FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
        FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';
    }
    function initlid_BS(){
        BS_lid = new Array();
        BSFU_lid = new Array();
        FSBS_lid = new Array();
        BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
        BS_lid['BS_lid_type']= BS_lid_type='';
        BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
        BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
        BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
        BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
        BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
        BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
        FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
        FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';
    }
    function initlid_TN(){
        TN_lid = new Array();
        TU_lid = new Array();
        FSTN_lid = new Array();
        TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
        TN_lid['TN_lid_type']= TN_lid_type='';
        TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
        TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
        TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
        TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
        TU_lid['TU_lid_type']= TU_lid_type='';
        TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
        FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';
        FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';
    }
    function initlid_VB(){
        VB_lid = new Array();
        VU_lid = new Array();
        FSVB_lid = new Array();
        VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
        VB_lid['VB_lid_type']= VB_lid_type='';
        VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
        VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
        VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
        VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
        VU_lid['VU_lid_type']= VU_lid_type='';
        VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
        FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
        FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL'
    }
    function initlid_OP(){
        OP_lid = new Array();
        OM_lid = new Array();
        FSOP_lid = new Array();
        OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
        OP_lid['OP_lid_type']= OP_lid_type='';
        OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
        OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
        OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
        OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
        OM_lid['OM_lid_type']= OM_lid_type='';
        OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
        FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
        FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
    }

    var uid = '<?php echo $uid;?>';
    /* 下注相关 开始*/
    $(function () {

        indexCommonObj.loadPageAction(uid) ;

        // close_bet();
        //
        // // 关闭下注
        // function close_bet(){
        //     document.getElementById('bet_order_frame').height =0;
        //     // bet_order_frame.document.writeln("<html><head><link href=\"/style/member/sports_common.css\" rel=\"stylesheet\" type=\"text/css\"></head><body  class='bet_info' style='background:#E3CFAA;margin:0'>");
        //     // bet_order_frame.document.writeln("</body></html>");
        //     document.getElementById('bet_order_frame').height = bet_order_frame.document.body.scrollHeight ;
        //
        //     try {
        //         document.getElementById('bet_promos_div').style.display='block';
        //     }catch (e) { }
        //
        //     var scripts=new Array();
        //
        //     try{
        //         parent.body.orderRemoveALL();
        //     }catch (E) {}
        // }
    })

    /* 下注相关 结束*/


</script>

