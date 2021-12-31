<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['gtype'];
require ("../include/traditional.$langx.inc.php");

$m_date=date('Y-m-d');
$date1=date('Y-m-d',time());
$date2=date('Y-m-d',time()+1*24*60*60);
$date3=date('Y-m-d',time()+2*24*60*60);
$date4=date('Y-m-d',time()+3*24*60*60);
$date5=date('Y-m-d',time()+4*24*60*60);
$date6=date('Y-m-d',time()+5*24*60*60);
$date7=date('Y-m-d',time()+6*24*60*60);

/*$sql="select $mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,Eventid,Hot,Play from ".DBPREFIX."match_sports where Type='$gtype' and `M_Date` ='$m_date' and Eventid='$eventid' and Play='Y' order by MID";
$result=mysqli_query($dbMasterLink,$sql);
$row=mysqli_fetch_assoc($result);*/

$videoAccoutArr = getOfficialVideoAccount();
$videoAccout= $videoAccoutArr[rand(0,count($videoAccoutArr)-1)];

$redisObj = new Ciredis();
$html_data=$redisObj->getSimpleOne('gameVideoLists');

function get_content_deal($html_data){
	$a = array(
		"if(self == top)",
		"<script>",
		"</script>",
		"new Array()",
		"parent.GameFT=new Array();",
		"\n\n",
		"_.",
		"g([",
		"])"
		);
	$b = array(
		"",
		"",
		"",
		"",
		"",
		"",
		"parent.",
		"Array(",
		")"
	);
	
	$msg = str_replace($a,$b,$html_data);
	preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
	return $matches[0];
}

$onVideo=0;
$liveTeamDate=get_content_deal(json_decode($html_data,true));
foreach($liveTeamDate as $key=>$val){
	$val=str_replace('Array(','',$val);
	$val=str_replace(');','',$val);
	$val=str_replace('\'','',$val);
	$valCur=explode(',',$val);
	if(in_array($valCur[0],array('FT','BK'))){
		if($valCur[0]=='Y'){ $onVideo=$onVideo+1; }
		$liveTeamArray[$valCur[0].'_'.$valCur[10]]=$valCur;
		$GameDataArray[]=$valCur;
		$keyCur=substr($valCur[2],0,10);
		$liveTeam[$keyCur][]=$valCur;		
	}
}

/*echo '<pre>';
print_r($liveTeamArray);
echo '<br/>';*/

$liveTeamJson=json_encode($liveTeamArray);
$GameDataJson=json_encode($GameDataArray);

/*echo '<pre>';
print_r($liveTeamJson);
echo '<br/>';*/

?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="欢迎访问 hg0088.com, 优越服务专属于注册会员。">
<title>观看现场</title>
		<link href="../../../style/member/live.css" rel="stylesheet" type="text/css">
		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->
		<script type="text/javascript" src="../../../js/jquery.js"></SCRIPT>
		<script type="text/javascript" src="../../../js/header.js"></SCRIPT>
		<!--<SCRIPT language="javascript" src="conf/script_live.php?langx=zh-cn&amp;opentype=liveTV"></SCRIPT>-->
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <style>
        .live_evenH_1{background: #261d18}
        body,html{overflow-x:hidden;}
    </style>
<script>
top.performUrl = 'pfm.aspenbet.com';
var isTest = 'N';
var Gplaying='<?php echo $onVideo;?>';
var Datasite='<?php echo $videoAccout['Datasite']?>';
var uid = '<?php echo $videoAccout['Uid']?>';
var langx = 'zh-cn';
var mtvid = '<?php echo $videoAccout['liveID']?>';
top.eventid = '';
var eventlive = '';
var mcurrency = '人民币';
var videoData = '';
var GameDate = new Array('<?php echo $date1;?>','<?php echo $date2;?>','<?php echo $date3;?>','<?php echo $date4;?>','<?php echo $date5;?>','<?php echo $date6;?>','<?php echo $date7;?>');
var o_path = '<?php echo BROWSER_IP?>/app/member/Live_select.php?uid=<?php echo $uid?>&langx=zh-cn&live=Live';
top.betGtype = '';
top._Gtype = '';
top.autoOddCheck = (''+top.autoOddCheck!='undefined')?top.autoOddCheck:true;
var performData=<?php echo $liveTeamJson;?>;
var GameData=<?php echo $GameDataJson;?>;

var Livegtype ="All";
var clickgtype = "All";
var Livegidm ="";
// 2017-03-06 3042.新會員端-tv 當已在右邊tv觀看時點放大和上方的tv圖示到另開要是繼續剛觀看的場次
var closeTV = false;
var gtype ="";//檢查tv是否結束用
var gidm ="";//檢查tv是否結束用
var isOpen = false;
var tv_show = true;
var isFixed = true;

top.bet_page = true;
top.eventid = "";
top.langx = parent.top.langx;
top.tv_now = "";
top.select_type = "betlist";
var nolive_sw="";
var ctl_tv_status = true;
var is_init = true;
var tmp_sw = "Y";
var resize_w='';
</script>
</head>
<body scrolling="no">
<div class="liveTV_main" style="background-color: #19130f;overflow: hidden;">
    <!-- title -->
    <div class="live_header">
        <h1>观看现场</h1>
    </div>
    <!-- title End -->
    <!-- body -->
    <div class="liveTVG noFloat">
        <!--左边区域-->
        <div class="liveTV_leftDIV">
            <!--计分板-->
            <div id="div_info" class="live_scoreDIV">
                <!--足球-->
                <div id="info_FT" style="">
                    <table cellspacing="0" cellpadding="0" class="live_scoreTB live_SC">
                        <tbody>
	                        <tr>
	                            <td id="FT_clothes_h" width="20" class="live_SC ">&nbsp;</td>
	                            <td id="FT_sc_h" width="10" class="TXTnowrap tuhuiWord"> </td> <!-- 进球数 -->
	                            <td class="live_score_team"><span id="FT_team_h"></span>
                                    <span id="FT_red_h" style="display:none" class="live_score_redCard">0</span> <!-- 红牌数 -->
                                </td>
	                        </tr>
	                        <tr>
	                            <td id="FT_clothes_c" class="live_SC ">&nbsp;</td>
	                            <td id="FT_sc_c" class="TXTnowrap tuhuiWord"> </td> <!-- 进球数 -->
	                            <td class="live_score_team"><span id="FT_team_c"></span>
                                    <span id="FT_red_c" style="display:none" class="live_score_redCard">0</span> <!-- 红牌数 -->
                                </td>
	                        </tr>
                        </tbody>
                     </table>
                </div>
                <!--篮球&棒球&其他-->
                <div id="info_BK" style="display:none">
                    <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BK">
                        <tbody><tr>
                            <td id="BK_clothes_h" width="20">&nbsp;</td>
                            <td id="BK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                            <td id="BK_team_h" class="live_score_team"></td>
                        </tr>
                        <tr>
                            <td id="BK_clothes_c">&nbsp;</td>
                            <td id="BK_sc_c" class="TXTnowrap tuhuiWord">0</td>
                            <td id="BK_team_c" class="live_score_team"></td>
                        </tr>
                        </tbody></table>
                </div>
                <div id="info_SK" style="display:none">
                    <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BS">
                        <tbody><tr>
                            <td id="SK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
                            <td id="SK_team_h" class="live_score_team"></td>
                            <td rowspan="2" id="SK_best" class="live_SK_left"></td>
                        </tr>
                        <tr>
                            <td id="SK_sc_c" class="TXTnowrap tuhuiWord">0</td>
                            <td id="SK_team_c" class="live_score_team"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--计分板 End-->
            <!--没有TV播放 -->
            <div valign="top" id="DemoImgLayer" class="liveTV_demo" ></div>
            <!--没有TV播放 End-->
            <!--视讯影片区-->
            <div id="max_FlahLayer" class="liveTV_movieBIGDIV"> <!--  FlahLayer  -->
                <div id="videoFrame" style="display:none;" class="dome_L"></div>
                <div class="dome_L">
                    <iframe id="DefLive" name="DefLive" style="width:100% !important;height:438px !important;"  scrolling="no" frameborder="0" framespacing="0" cellspacing="0" cellpadding="0" style=""></iframe>
                </div>
                <!--TV未播放假图 -->
                <div id="div_fake" class="live_TVdemoBG_BIG02" style="display:none">点击播放。</div>
                <!-- img : live_TVdemoBG_BIG01 / perform : live_TVdemoBG_BIG02 / unas : live_TVdemoBG_BIG03 -->
                <!--TV未播放假图 End-->
            </div>
            <!--视讯影片区 End-->
        </div>
        <!--左边区域 End-->
        <!--右边区域-->
        <div id="TV_right" class="liveTV_rightDIV">
            <!--弹出TV新增钮-->
            <div id="no_bet_head" style="display:none" class="noFloat">
                <!--<span id="bet_list" class="liveTV_headerBTN_on" title="">立即投注</span>
                <span tabindex="1" id="btn_bet" class="liveTV_headerBTN" title="">直播表</span>-->
            </div>
            <!--赛事直播日程表-->
            <div id="main" class="live_listG_nomal">
                <!--head-->
                <!--<div id="main_head" class="noFloat" style="position: fixed; z-index: 1; width: 301px;">
                    <span tabindex="1" id="btn_bet_main" class="liveTV_headerBTN" title="">立即投注</span>
                    <span tabindex="2" class="liveTV_headerBTN_on" title="">直播表</span>
                </div>-->
                <!--<div id="main_head_bak" style="height: 40px; width: 316px;"><span tabindex="1" id="btn_bet_main" class="liveTV_headerBTN" title="">立即投注</span></div>-->
                <h1>直播日程表</h1>
                <div id="time_list">
	                <div id="sel_gtype" class="live_allSportsBTN"><tt id="select_gtype">所有体育</tt>
	                    <div id="show_gtype" style="display:none" class="live_MINImenu" tabindex="100"><span class="live_MINImenu_arr"></span>
	                        <h1>选择体育</h1>
	                        <!--球类拉霸-->
	                        <ul id="option_gtype" class="live_MINIul">
	                            <!-- 程式趴 -->
	                            <!--li id="gtype_all">所有体育</li>
	                            <li id="gtype_FT">Soccer</li>
	                            <li id="gtype_BK" class="live_allSportsLow">Basketball & <br>American Football</li>
	                            <li id="gtype_TN">Tennis</li>
	                            <li id="gtype_VB">Volleyball</li>
	                            <li id="gtype_BM">Badminton</li>
	                            <li id="gtype_TT">Table Tennis</li>
	                            <li id="gtype_BS">Baseball</li>
	                            <li id="gtype_OP">Other Sports</li-->
	                            <li id="option_All" value="All">所有球类</li>
	                            <li id="option_FT" value="FT">足球</li>
	                            <li id="option_BK" value="BK">篮球 / 美式足球</li>
	                        </ul>
	                        <!--球类拉霸 End-->
	                    </div>
	                </div>
	                <!--没有赛事-->
	                <div id="even_none" class="live_noList" style="display:none">现在还没有现场和未来赛事的直播流。</div>
	                <!--没有赛事 End-->
	
	                <!-- 赛事列表 -->
	                <div id="even_list" class="live_scrollBar" style="">
	                    <div id="showlayers">
	                        <?php foreach($liveTeam as $key=>$value){?>
					            <h2><?php echo $key; ?></h2>
					            <table cellspacing="0" cellpadding="0" class="live_listTB">
									<tbody>
									<?php foreach($value as $vk=>$va){
									if($va[13]=='perform'){
									?>
									<tr id="live_txt_<?php echo $va[0];?>_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" class="<?php if($va[6]=='Y'){ echo 'live_txt_nomal'; }else{ echo 'live_txt_off'; }?>" <?php if($_SESSION['Agents']=='demoguest'){ echo "onclick=alert('请注册真实会员！')"; }else{ echo "onclick=OpenTV_new('". $va[0] . "_".$va[10]."')"; } ?> >
										<td id="live_tv_<?php echo $va[0];?>_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" width="50" class="<?php if($va[6]=='Y'){ echo 'live_tv_nomal'; }else{ echo 'live_tv_off'; }?>"><?php if($va[6]=='N'){echo substr($va[2],11,5);} ?></td>
										<td id="live_gtype_<?php echo $va[0];?>_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" width="30" class="<?php if($va[6]=='Y'){ echo 'live_sc_nomal'; }else{ echo 'live_sc_off'; }?>">&nbsp;</td>
										<td><?php echo $va[3]; ?> vs <?php echo $va[4]; ?></td>
									</tr>
									<?php }
									}
									?>
									</tbody>
								</table>
					            <?php }?>
	                    </div>
	                </div>
                </div>
                <!-- 赛事列表 End-->
                <!-- 赛事模组 -->
                <div id="tb_layer" style="display:none">
                    <xmp>
                        <h2>*GAME_DATE*</h2>
                        <table cellspacing="0" cellpadding="0" class="live_listTB">
                            *GAME_LIST*
                        </table>
                    </xmp>
                </div>
                <div id="tr_layer" style="display:none">
                    <xmp>
                        <tr id="live_txt_*ID*" *LIVE_TXT_CLASS* onClick="OpenTV_new('*ID*');"><!-- live_txt_on / live_txt_nomal / live_txt_off -->
                            <td id="live_tv_*ID*" width="50" *LIVE_TV_CLASS*>*TIME*</td><!-- live_tv_on / live_tv_nomal / live_tv_off -->
                            <td id="live_gtype_*ID*" width="30" *LIVE_GTYPE_CLASS*>&nbsp;</td>
                            <td>*TEAMH* vs *TEAMC*</td>
                        </tr>
                    </xmp>
                </div>
                <!-- 赛事模组 End -->
            </div>
            <!--赛事直播日程表 End-->
            <!--无法投注-->
            <div id="bet_none" class="live_closeDIV" style="display:none"><span>您所选的赛事暂时无法投注。</span></div>
            <!--无法投注 End-->
            <!--玩法没开-->
            <div id="wtype_close" style="display:none" class="live_closeDIV"><span>无提供交易。</span></div>
            <div id="none_div" class="live_noList_high" style="display:none">现在还没有现场和未来赛事的直播流。</div>
            <!-- 盘面 Start -->

<!--            <div id="main_bet" style="display:none">-->
<!--                <div id="bet_mem" class="bet_mem">-->
<!--                    <div id="mem_div" class="Live_mem">-->
<!--                        <iframe id="Live_mem" name="Live_mem" scrolling="YES" frameborder="NO" border="0" width="316" height="478" allowtransparency="true"></iframe>-->
<!--                    </div>-->
<!--                    <div id="bet_div" class="liveTV_DIV_Mask" style="display:none">-->
<!--                        <iframe id="bet_order_frame" class="liveTV_MaskG" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" width="100%" height="483" allowtransparency="true"></iframe>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            
            <!-- 盘面 End -->
        </div>
        <!--右边区域 End-->
    </div>
    <!-- body End -->
    <!-- load data -->
    <iframe id="reloadPHP" name="reloadPHP" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
    <iframe id="reloadgame" name="reloadgame" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
    <iframe id="registLive" name="registLive" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
    <!-- load data End-->
</div>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/header.js"></script>
<script type="text/javascript" src="../../../js/src/live_radio.js"></script>
</body>
</html>
