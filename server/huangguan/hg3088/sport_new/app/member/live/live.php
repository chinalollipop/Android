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

/*$sql="select $mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='$gtype' and `M_Date` ='$m_date' and Eventid='$eventid' and Play='Y' order by MID";
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
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <style>
        .live_evenH_1{background: #261d18}
        body,html{overflow-x:hidden;}
    </style>
<script>
performUrl = 'pfm.aspenbet.com';
var isTest = 'N';
var Gplaying='<?php echo $onVideo;?>';
var Datasite='<?php echo $videoAccout['Datasite']?>';
var uid = '<?php echo $videoAccout['Uid']?>';
var langx = '<?php echo $_SESSION['langx']?>';
var mtvid = '<?php echo $videoAccout['liveID']?>';
var eventlive = '';
var mcurrency = '人民币';
var videoData = '';
var GameDate = new Array('<?php echo $date1;?>','<?php echo $date2;?>','<?php echo $date3;?>','<?php echo $date4;?>','<?php echo $date5;?>','<?php echo $date6;?>','<?php echo $date7;?>');
var o_path = '<?php echo BROWSER_IP?>/app/member/Live_select.php?uid=<?php echo $uid?>&langx=zh-cn&live=Live';
var betGtype = '';
var _Gtype = '';
autoOddCheck = (''+autoOddCheck!='undefined')?autoOddCheck:true;
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

var bet_page = true;
var eventid = "";
var tv_now = "";
var select_type = "betlist";
var nolive_sw="";
var ctl_tv_status = true;
var is_init = true;
var tmp_sw = "Y";
var resize_w='';
</script>
</head>
<body scrolling="no">
	<div id="main_tv" class="live_main320">
        <div id="div_tv" class="live_DIV320">
		        <div id="div_show" class="live_noMV">
		        <!-- title -->
		        <div id="div_title" class="live_header">
		        		<h1 id="ctl_tv" class="On">观看现场</h1>
		        		<span id="btn_game" onClick="showGameList();" class="live_tvListBTN" title="直播日程表">直播表</span>
		        		<span id="btn_open" onClick="showOpenLive();" class="live_tvBigBTN" title="放大显示"></span>
				</div>
		        <!-- title End-->
				<!-- nogame title -->
		        <div id="div_title_nogame" style="display:none;" class="live_header">
		        		<h1 id="ctl_tv02" class="On">观看现场</h1>
                		<!-- 球类拉bar -->
				        <div id="sel_gtype_nogame" class="live_allSportsBTN"><tt id="select_gtype_nogame">所有体育</tt>
				            <div id="show_gtype_nogame" style="display:none" class="live_MINImenu"><span class="live_MINImenu_arr"></span>
				                <h1>选择体育</h1>
				                <ul id="option_gtype_nogame" class="live_MINIul">
				                </ul>
				        	</div>
				        </div>
		                <!-- 球类拉bar End-->
        		        <!--没有赛事-->
                		<div id="even_none_nogame" class="live_noList">现在还没有现场和未来赛事的直播流。</div>
                		<!--没有赛事 End-->
				</div>
		        <!-- nogame title End-->
				<div id="ctl_tv_div">
		        <div id="time_list" class="live_timeList" >
							<div id="div_gtype" class="live_listG">
                            <div id="gtype_bar">
				            <h1>直播日程表</h1>
                            	<!-- 球类拉bar -->
						        <div id="sel_gtype" class="live_allSportsBTN"><tt id="select_gtype">所有体育</tt>
						            <div id="show_gtype" style="display:none" class="live_MINImenu"><span class="live_MINImenu_arr"></span>
						                <h1>选择体育</h1>
						                <ul id="option_gtype" class="live_MINIul">
						                	<li id="option_game_All" data-value="All">所有球类</li>
											<li id="option_game_FT" data-value="FT">足球</li>
											<li id="option_game_BK" data-value="BK">篮球 / 美式足球</li>
						                </ul>
						        		</div>
						        </div>
                                <!-- 球类拉bar End-->
						        <!--<span id="showX" onClick="showGameList();" class="live_sportsCloseBTN"></span>-->
				        	</div>
                        <!--没有赛事-->
                        <div id="even_none" style="display:none;" class="live_noList">现在还没有现场和未来赛事的直播流。</div>
                        <!--没有赛事 End-->
                        <!-- 赛事列表 -->
				        <div id="even_list" class="live_evenH_1">
				            <div id="showlayers">
				            <?php foreach($liveTeam as $key=>$value){?>
				            <div><h2><?php echo $key; ?></h2>
				            <table cellspacing="0" cellpadding="0" class="live_listTB">
								<tbody>
								<?php foreach($value as $vk=>$va){
									if($va[13]=='perform'){
								?>
								<tr id="live_txt_FT_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" class="FT_LIST All_LIST <?php if($va[6]=='Y'){ echo 'live_txt_nomal'; }else{ echo 'live_txt_off'; }?>"  <?php if($_SESSION['Agents']=='demoguest'){ echo "onclick=alert('请注册真实会员！')"; }else{ echo "onclick=showOpenLive()"; } ?> > <!-- OpenTV_chgType('FT_<?php //echo $va[10]; ?>') -->
									<td id="live_tv_FT_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" width="50" class="<?php if($va[6]=='Y'){ echo 'live_tv_nomal'; }else{ echo 'live_tv_off'; }?>"><?php if($va[6]=='N'){echo substr($va[2],11,5);} ?></td>
									<td id="live_gtype_FT_<?php echo $va[10]; ?>_<?php echo $va[1]; ?>" width="30" class="<?php if($va[6]=='Y'){ echo 'live_sc_nomal'; }else{ echo 'live_sc_off'; }?>">&nbsp;</td>
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
				        <!-- 赛事列表 End-->
                        </div>
		      	</div>
		        <!-- body -->
		        <div id="div_body" class="live_oddsG">
		        		<!--计分板-->
		        		<div id="div_info" class="live_scoreDIV">
				            <!--足球-->
				            <div id="info_FT" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_SC">
						              <tr>
						                <td id="FT_clothes_h" width="20">&nbsp;</td>
						                <td id="FT_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td class="live_score_team"><span id="FT_team_h"></span><span id="FT_red_h" style="display:none" class="live_score_redCard">0</span></td>
						              </tr>
						              <tr>
						                <td id="FT_clothes_c">&nbsp;</td>
						                <td id="FT_sc_c" class="TXTnowrap tuhuiWord">0</td>
						                <td class="live_score_team"><span id="FT_team_c"></span><span id="FT_red_c" style="display:none" class="live_score_redCard">0</span></td>
						              </tr>
						            </table>
				          	</div>
							<!--篮球&棒球&其他-->
							<div id="info_BK" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BK">
						              <tr>
						                <td id="BK_clothes_h" width="20">&nbsp;</td>
						                <td id="BK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BK_team_h" class="live_score_team"></td>
						              </tr>
						              <tr>
						                <td id="BK_clothes_c">&nbsp;</td>
						                <td id="BK_sc_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BK_team_c" class="live_score_team"></td>
						              </tr>
						            </table>
				          	</div>
		            		<div id="info_BS" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BS">
						              <tr>
						                <td id="BS_clothes_h" width="20">&nbsp;</td>
						                <td id="BS_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BS_team_h" class="live_score_team"></td>
						              </tr>
						              <tr>
						                <td id="BS_clothes_c">&nbsp;</td>
						                <td id="BS_sc_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BS_team_c" class="live_score_team"></td>
						              </tr>
						              <tr>
						              	<td colspan="6" class="padd0">
						                <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
						                  <tr>
						                    <td width="35%">
                                            	<div class="live_LeiBaoG">
                                                	<span id="BS_base_1B" class="live_LeiBao01"></span><span id="BS_base_2B" class="live_LeiBao02"></span><span id="BS_base_3B" class="live_LeiBao03"></span>
                                                </div>
                                            </td>
						                    <td width="65%" class="Word_Paddright">出局:<tt id="BS_out_count" class="dark_pink"></tt></td>
						                  </tr>
						                </table>
						                </td>
						              </tr>
						            </table>
                                    <span id="BS_game_count" class="live_FTarr"></span><!--场次-->
				          	</div>
				          	<div id="info_OP" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BK">
						              <tr>
						                <td id="OP_clothes_h" width="20">&nbsp;</td>
						                <td id="OP_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td class="live_score_team"><span id="OP_team_h"></span><span id="OP_red_h" style="display:none" class="live_score_redCard">0</span></td>
						              </tr>
						              <tr>
						                <td id="OP_clothes_c">&nbsp;</td>
						                <td id="OP_sc_c" class="TXTnowrap tuhuiWord">0</td>
						                <td class="live_score_team"<span id="OP_team_c"></span>><span id="OP_red_c" style="display:none" class="live_score_redCard">0</span></td>
						              </tr>
						            </table>
				          	</div>
				            <!--网球-->
				            <div id="info_TN" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
						              <tr>
						              	<td id="TN_game_h"  width="20" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="TN_set_h"   width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TN_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TN_serve_h" width="21">&nbsp;</td>
						                <td id="TN_team_h"  class="live_score_team"></td>
						                <td rowspan="2"  id="TN_best" class="live_score_best"></td>
						              </tr>
						              <tr>
						              	<td id="TN_game_c" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="TN_set_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TN_point_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TN_serve_c">&nbsp;</td>
						                <td id="TN_team_c" class="live_score_team"></td>
						              </tr>
						              <tr>
						              	<td colspan="6" class="padd0">
						                <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
						                  <tr>
						                    <td width="50%"><span id="TN_before"></span><span id="TN_weather" class="RedWord" style="display:none">天气延赛</span></td>
						                    <td width="50%" class="Word_Paddright topTD">总局数 <tt id="TN_total" class="dark_pink"></tt></td>
						                  </tr>
						                </table>
						                </td>
						              </tr>
						            </table>
				          	</div>
				            <!--羽毛 乒乓 排球-->
				            <div id="info_VB" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
						              <tr>
						              	<td id="VB_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="VB_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="VB_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
						                <td id="VB_team_h"class="live_score_team"></td>
						                <td rowspan="2" id="VB_best" class="live_score_best"></span></td>
						              </tr>
						              <tr>
						              	<td id="VB_set_c" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="VB_point_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="VB_serve_c" class="live_scoreIcon_b">&nbsp;</td>
						                <td id="VB_team_c" class="live_score_team"></td>
						              </tr>
						              <tr>
						              	<td colspan="5" class="padd0">
						                <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
						                  <tr>
						                    <td id="VB_before" width="50%"></td>
						                    <td width="50%" class="Word_Paddright topTD">总分数 <tt id="VB_total" class="dark_pink"></tt></td>
						                  </tr>
						                </table>
						                </td>
						              </tr>
						            </table>
				          	</div>
				            <div id="info_BM" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
						              <tr>
						              	<td id="BM_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="BM_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BM_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
						                <td id="BM_team_h"class="live_score_team"></td>
						                <td rowspan="2" id="BM_best" class="live_score_best"></td>
						              </tr>
						              <tr>
						              	<td id="BM_set_c" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="BM_point_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="BM_serve_c" class="live_scoreIcon_b">&nbsp;</td>
						                <td id="BM_team_c" class="live_score_team"></td>
						              </tr>
						              <tr>
						              	<td colspan="5" class="padd0">
						                <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
						                  <tr>
						                    <td id="BM_before" width="50%"></td>
						                    <td width="50%" class="Word_Paddright topTD">总分数 <tt id="BM_total" class="dark_pink"></tt></td>
						                  </tr>
						                </table>
						                </td>
						              </tr>
						            </table>
				          	</div>
				          	<div id="info_TT" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_TN">
						              <tr>
						              	<td id="TT_set_h" width="20" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="TT_point_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TT_serve_h" width="21" class="live_scoreIcon_a">&nbsp;</td>
						                <td id="TT_team_h"class="live_score_team"></td>
						                <td rowspan="2" id="TT_best" class="live_score_best"></td>
						              </tr>
						              <tr>
						              	<td id="TT_set_c" class="Word_Paddleft tuhuiWord">0</td>
						                <td id="TT_point_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="TT_serve_c" class="live_scoreIcon_b">&nbsp;</td>
						                <td id="TT_team_c" class="live_score_team"></td>
						              </tr>
						              <tr>
						              	<td colspan="5" class="padd0">
						                <table cellspacing="0" cellpadding="0" class="live_scoreTB_inside">
						                  <tr>
						                    <td id="TT_before" width="50%"></td>
						                    <td width="50%" class="Word_Paddright topTD">总分数 <tt id="TT_total" class="dark_pink"></tt></td>
						                  </tr>
						                </table>
						                </td>
						              </tr>
						            </table>
				          	</div>
										<div id="info_SK" style="display:none">
						            <table cellspacing="0" cellpadding="0" class="live_scoreTB live_BS">
						              <tr>
						                <td id="SK_sc_h" width="10" class="TXTnowrap tuhuiWord">0</td>
						                <td id="SK_team_h" class="live_score_team"></td>
						                <td rowspan="2" id="SK_best" class="live_SK_left"></td>
						              </tr>
						              <tr>
						                <td id="SK_sc_c" class="TXTnowrap tuhuiWord">0</td>
						                <td id="SK_team_c" class="live_score_team"></td>
						              </tr>
						            </table>
				          	</div>
				        </div>
		           		<!--计分板 End-->
		            	<!-- 没有TV播放 -->
								<div valign="top" id="DemoImgLayer" class="live_demo_mini" style="display:none"></div>
								<!-- 没有TV播放 End-->
				      		<!--视讯影片区-->
					      <div id="FlahLayer" style="display:none;" class="live_movieDIV">

					      	<!-- 320pxperfrome -->
							<div id="live_320tv" class="live_320pxG" style="display:none;">
								<span></span>
								如果视频没有播放, 请点击电视屏幕.
							</div>

					      	<div id="videoFrame" style="display:none;" class="dome_L"></div>
				            <div id="dome_L" class="dome_L">
				            		<iframe id="DefLive" name="DefLive" width="100%" height="289" src="" scrolling="no" frameborder="0" framespacing="0" cellspacing="0" cellpadding="0" style="display:none;"></iframe>
				            </div>
				            <!--TV未播放假图 -->
										<div id="div_fake" class="live_TVdemoBG01" style="display:none">点击播放。</div>
										<!-- img : live_TVdemoBG01 / perform : live_TVdemoBG02 / unas : live_TVdemoBG03 -->
										<!--TV未播放假图 End-->

					      </div>
				      	<!--视讯影片区 End-->
                        <!--玩法没开-->
		        		<div id="wtype_close" class="liveTV_closeDIV"  style="display:none" >无提供交易。</div>
                        <!--无法投注-->
			        	<div id="bet_none" class="liveTV_closeDIV" style="display:none">您所选的赛事暂时无法投注。</div>
			        	<!--无法投注 End-->
		        </div>
		       	<!-- body End -->
		        <!-- 盘面 Start -->
		       	<div id="main_bet" style="display:">
				      	<div id="bet_mem" class="bet_mem">
				            <div id="bet_div" style="display:none">
				                <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" width="100%" height="483"></iframe>
				            </div>
				            <div id="LiveTV_mem" class="LiveTV_mem" style="display:block">
				                <iframe id="Live_mem"  name="Live_mem" scrolling="NO" frameborder="NO" border="0" width="100%" src='./game_ioratio_view.php'></iframe>
				            </div>
				        </div>
		      	</div>
		        </div>
		      	</div>
		</div>
    </div>

    <iframe id="reloadPHP" name="reloadPHP" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
    <iframe id="reloadgame" name="reloadgame" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
    <iframe id="registLive" name="registLive" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>

    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/header.js"></script>
    <script type="text/javascript" src="../../../js/src/live_radio.js"></script>
    <script>
    var isTestSite = false;
    var gtypeAry = new Array("FT","BK","TN","VB","BS","OP","TT","BM","SK");
    var notfind = new Object();
    var JQ;
    var load_jq_complet = false;
    var fade_out_sec = 5000;  //賠率變色畫動秒數
    var slide_sec = 100; //slide動畫秒數

    checkTvWinClose();

</script>
</body>
</html>
