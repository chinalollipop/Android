<?php 
session_start();
ini_set('display_errors','OFF');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$gtype=$_REQUEST['gtype'];
$evenId=$_REQUEST['gidmstr'];
$open=$_SESSION['OpenType'];
$liveCode=$_REQUEST['code'];

require ("../include/traditional.$langx.inc.php");

/*
$redisObj = new Ciredis();
$html_data=$redisObj->getSimpleOne('gameFtVideoIoratio');

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

$ioratioTeamDate=get_content_deal(json_decode($html_data,true));

$gameDate=$gameDateKey=$curGameDate=$curGameDateExpan=array();
foreach($ioratioTeamDate as $key=>$val){
	$val=str_replace('Array(','',$val);
	$val=str_replace(');','',$val);
	$val=str_replace('\'','',$val);
	$valCur=explode(',',$val);
	if(strpos($val,$liveCode)>10){
		$curGameDate=array_combine($gameDateKey,$valCur);
	}else{
		if(isset($curGameDate)&&$curGameDate['league']==$valCur[2]&&$curGameDate['team_h']==$valCur[5]&&$curGameDate['team_c']==$valCur[6]){
			$curGameDateExpan['all'][]=array_combine($gameDateKey,$valCur);
		}
	}
	if($valCur[0]=='gid'){
		$gameDateKey=$valCur;
	}
}
*/
/*echo '<pre>';
var_dump($curGameDate);
echo '<br/>';*/
$gameDate=$gameDateKey=$curGameDate=$curGameDateExpan=array();
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<!--<link href="http://205.201.1.89/style/member/reset.css" rel="stylesheet" type="text/css">-->
<!--<link href="http://205.201.1.89/style/member/live.css" rel="stylesheet" type="text/css">-->
<!--<link href="http://205.201.1.89/style/member/bet_maincortol.css" rel="stylesheet" type="text/css">-->
<script type="text/javascript" src="../../../js/jquery.js"></script>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<script>
var liveTV='Y';
var uid ='<?php echo $uid?>';
var odd_f_type ='H';
var odd_f_str = 'H,M,I,E';
var Format=new Array();
Format[0]=new Array( 'H','香港盘','Y');
Format[1]=new Array( 'M','马来盘','Y');
Format[2]=new Array( 'I','印尼盘','Y');
Format[3]=new Array( 'E','欧洲盘','Y');
var game_gidm='';
var gidmstr='';
var iorpoints =2;
var show_ior=100;
var title_strbig ='大';
var title_strsmall ='小';
var flash_ior_set ='Y';
var str_even = '和局';
var str_submit = '确定交易';
var str_reset = '重设';
var langx='zh-cn';
var GameData = new Array();
var GameFT = new Array();
//console.log(document.getElementById('live').contentWindow.document.getElementById('FT_sc_h'));
//document.getElementById('live').contentWindow.document.getElementById('FT_sc_h').innerHTML = "<?php echo $curGameDate[score_h];?>";
//console.log($("#live #FT_sc_h"));
//$("#live")[0].contentWindow.$("#FT_sc_h").val(<?php echo $curGameDate[score_h];?>); 
//'redcard_h',            '1',
//'redcard_c',            '0',
</script>
</head>
<!--<body  onLoad="onloads();">-->
<body>
<!--head-->
<div id="main_bet_head" class="noFloat" style="display:none" >
	<span  class="liveTV_headerBTN_on" title="">立即投注</span>
	<span tabindex=1 id="bet_list_main"  class="liveTV_headerBTN" title="">直播表</span>
</div>
<!--更新钮-->
<!--<span id="live_refresh" class="live_refreshBTN" style="display:none"></span>-->
<?php if(count($curGameDate)>0){?>
<div id="right_div" style="">
	<div>
		<div class="live_oddsDIV">
	  		<table cellspacing="0" cellpadding="0" class="live_oddsTB">
	        <tbody>
	        <tr>
	        <th colspan="6">全场</th>
	        </tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $curGameDate['team_h']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01"><?php echo $curGameDate['team_h']?></td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"><span id="RMH<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','RM','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>;odd_f_type=H&amp;type=H&amp;gnum=<?php echo $curGameDate['gnum_h'];?>&amp;strong=<?php echo $curGameDate['strong'];?>&amp;langx=zh-cn');"><?php echo change_rate($open,$curGameDate['ior_MH']);?></span></span></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span><?php if($curGameDate['ior_MH']=='H'){ echo $curGameDate['ratio']; }?></span></span><span class="live_oddWordG"><span id="REH<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','R','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>;odd_f_type=H&amp;type=H&amp;gnum=<?php echo $curGameDate['gnum_h'];?>&amp;strong=<?php echo $curGameDate['strong'];?>&amp;langx=zh-cn');" title="<?php echo $curGameDate['team_h']?>"><?php echo change_rate($open,$curGameDate['ior_RH']);?></span></span></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt class="light_BrownWord03">大</tt><?php echo str_replace('O','',$curGameDate['ratio_o']);?></span></span><span class="live_oddWordG"><span id="ROUC<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','OU','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>&amp;odd_f_type=H&amp;type=H&amp;gnum=<?php echo $curGameDate['gnum_h'];?>&amp;langx=zh-cn');" title="大"><?php echo change_rate($open,$curGameDate['ior_OUH']);?></span></span></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $curGameDate['team_c']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01"><?php echo $curGameDate['team_c']?></td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"><span id="RMC<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','RM','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>;odd_f_type=H&amp;type=C&amp;gnum=<?php echo $curGameDate['gnum_c'];?>&amp;strong=<?php echo $curGameDate['strong'];?>&amp;langx=zh-cn');"><?php echo change_rate($open,$curGameDate['ior_MC']);?></span></span></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span><?php if($curGameDate['ior_MH']=='C'){ echo $curGameDate['ratio']; }?></span></span><span class="live_oddWordG"><span id="REC<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','R','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>;odd_f_type=H&amp;type=H&amp;gnum=<?php echo $curGameDate['gnum_h'];?>&amp;strong=<?php echo $curGameDate['strong'];?>&amp;langx=zh-cn');" title="<?php echo $curGameDate['team_c']?>"><?php echo change_rate($open,$curGameDate['ior_RC']);?></span></span></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt class="light_BrownWord03">小 </tt><?php echo str_replace('U','',$curGameDate['ratio_u']);?></span></span><span class="live_oddWordG"><span id="ROUH<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','OU','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>&amp;odd_f_type=H&amp;type=C&amp;gnum=<?php echo $curGameDate['gnum_c'];?>&amp;langx=zh-cn');" title="小"><?php echo change_rate($open,$curGameDate['ior_OUC']);?></span></span></span></td>
		</tr>
		<tr class="live_oddTR">
		  <td name="team_320" class="live_oddsTD01">和</td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">和</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"><span id="RMN<?php echo $curGameDate['gid']?>" class="bet_bg_color"><span onclick="parent.parent.mem_order.betOrder('FT','RM','gid=<?php echo $curGameDate['gid'];?>&amp;uid=<?php echo $uid;?>;odd_f_type=H&amp;type=N&amp;gnum=<?php echo $curGameDate['gnum_c'];?>&amp;langx=zh-cn');"><?php echo change_rate($open,$curGameDate['ior_MC']);?></span></span> title="和局"><?php echo change_rate($open,$curGameDate['ior_MN']);?></span></span></span></td>
		  <td class="live_oddsTD03"></td>
		  <td class="live_oddsTD04"></td>
		</tr>
		<?php foreach($curGameDateExpan['all'] as $key=>$val){ ?>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $val['team_h']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01"><?php echo $val['team_h']?></td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"><span id="RMH<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=H&amp;gnum=72822&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;RM&quot;,&quot;RMH3352306&quot;);" title="<?php echo $val['team_h']?>"><?php echo change_rate($open,$val['ior_MH']);?></span></span></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span><?php if($val['ior_MH']=='H'){ echo $val['ratio']; }?></span></span><span class="live_oddWordG"><span id="REH<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=H&amp;gnum=72822&amp;strong=H&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;RE&quot;,&quot;REH3352306&quot;);" title="<?php echo $val['team_h']?>"><?php echo change_rate($open,$val['ior_RH']);?></span></span></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt class="light_BrownWord03">大</tt><?php echo str_replace('O','',$val['ratio_o']);?></span></span><span class="live_oddWordG"><span id="ROUC<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=C&amp;gnum=72821&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;ROU&quot;,&quot;ROUC3352306&quot;);" title="大"><?php echo change_rate($open,$val['ior_OUH']);?></span></span></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $val['team_c']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01"><?php echo $val['team_c']?></td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"><span id="RMC<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=C&amp;gnum=72821&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;RM&quot;,&quot;RMC3352306&quot;);" title="<?php echo $val['team_c']?>"><?php echo change_rate($open,$val['ior_MC']);?></span></span></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span><?php if($val['ior_MH']=='C'){ echo $val['ratio']; }?></span></span><span class="live_oddWordG"><span id="REC<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=C&amp;gnum=72821&amp;strong=H&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;RE&quot;,&quot;REC3352306&quot;);" title="<?php echo $val['team_c']?>"><?php echo change_rate($open,$val['ior_RC']);?></span></span></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt class="light_BrownWord03">小 </tt><?php echo str_replace('U','',$val['ratio_u']);?></span></span><span class="live_oddWordG"><span id="ROUH<?php echo $val['gid']?>" class="bet_bg_color"><span onclick="gethref(&quot;../FT_order/FT_order_all.php?gid=3352306&amp;uid=hoopa89am17879701l836270&amp;langx=zh-cn&amp;type=H&amp;gnum=72822&amp;odd_f_type=undefined&quot;,&quot;FT&quot;,&quot;ROU&quot;,&quot;ROUH3352306&quot;);" title="小"><?php echo change_rate($open,$val['ior_OUC']);?></span></span></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01">和</td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">和</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"></td>
		  <td class="live_oddsTD04"></td>
		</tr>
		<?php } ?>
		<?php if(count($curGameDateExpan['half'])>0){ ?>
        <tr>
        <th colspan="6">半场</th>
        </tr>
        <?php } ?>
        <?php foreach($curGameDateExpan['half'] as $key=>$val){ ?>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $newDataArray['team_h']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">SKA哈巴罗夫斯克</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span></span></span><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt style="display:none" class="light_BrownWord03">大 </tt></span></span><span class="live_oddWordG"></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $newDataArray['team_c']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">卢恩吉亚</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span></span></span><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt style="display:none" class="light_BrownWord03">小 </tt></span></span><span class="live_oddWordG"></span></td>
		</tr>
		<tr class="live_oddTR">
		  <td name="team_320" class="live_oddsTD01">和</td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">和</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"></td>
		  <td class="live_oddsTD04"></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $newDataArray['team_h']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">SKA哈巴罗夫斯克</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span></span></span><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt style="display:none" class="light_BrownWord03">大 </tt></span></span><span class="live_oddWordG"></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01"><?php echo $newDataArray['team_c']?></td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">卢恩吉亚</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"><span class="live_odd_rightWord"><span></span></span><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD04"><span class="live_odd_rightWord"><span><tt style="display:none" class="light_BrownWord03">小 </tt></span></span><span class="live_oddWordG"></span></td>
		</tr>
		<tr>
		  <td name="team_320" class="live_oddsTD01">和</td>
		  <td name="team_480" style="display:none;" class="live_oddsTD01">和</td>
		  <td class="live_oddsTD02"><span class="live_oddWordG"></span></td>
		  <td class="live_oddsTD03"></td>
		  <td class="live_oddsTD04"></td>
		</tr>
		<?php } ?>
		<?php if($curGameDate['more']>0){ ?>
      	<tr>
        		<td colspan="6" class="padd0"><span onclick="moreEvent('FT','3352306','N','');" class="live_allbetBTN">所有玩法 <span><?php echo $curGameDate['more'];?></span></span></td>
        </tr>
        <?php } ?>
			</tbody>
		</table>
		    	</div>
    	</div>
</div>
<?php } ?>
<iframe id="reloadPHP" name="reloadPHP" src="/ok.html" style="display:none" width="0" height="0"></iframe>
<iframe id="reloadgame" name="reloadgame" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
<iframe id="registLive" name="registLive" src="/ok.html" style="display:none" width="0" height="0" frameborder="NO" border="0"></iframe>
</body>
</html>