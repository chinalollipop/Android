<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

// 判断滚球是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$fstype=$_REQUEST['FStype'];    //FT BK
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:''; //FS
// $league_id=trim($_REQUEST['league_id']);
$league_id=$_REQUEST['myleaArr'];
$league_id=='ALL'?$league_id='':$league_id='';
require ("../include/traditional.$langx.inc.php");
if ($rtype==""){
	$rtype="FS";
}
//myleaArr: 世界杯2018(在俄罗斯),亚足联冠军联赛,巴西甲组联赛
//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}
$username=$_SESSION['UserName'];

//from loadgame_R.php
if ($rtype=='fs'){
	$type="and Gtype!='FI'";
}else if ($rtype=='fi'){
	$type="and Gtype='FI'";
}

if($fstype=="FT"){
	$matchType="足球冠军";
}elseif($fstype=="BK"){
	$matchType="篮球冠军";
}


$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

$m_date=date('Y-m-d');
$time=date('H:i:s');
$K=0;

$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
switch($langx){
	case "zh-cn":
		$suid=$row['uid_tw'];
		break;
	case "zh-cn":
		$suid=$row['uid'];
		break;
	case "en-us":
		$suid=$row['uid_en'];
		break;
}

function getShampionMatches(){
	global $dbMasterLink,$fstype,$league_id;
	$FT_M_ROU_EO_Time=TODAY_REDIS_REFLUSH_TIME;
	if($fstype=="FT"){
		$key="TODAY_FT_Champion";
	}elseif($fstype=="BK"){
		$key="TODAY_BK_Champion";
	}
	$redisObj = new Ciredis();
	$valReflushTime = $redisObj->getSimpleOne($key."_reflush_time");
	if($valReflushTime){//------------------------------------------存在数据
		if(time()-$valReflushTime>$FT_M_ROU_EO_Time){//数据过期
			$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
			$lockResult = mysqli_query($dbMasterLink,"select Type from ".DBPREFIX."match_sports_running_lock where `Type` = '".$key."' for update");
			if($begin && $lockResult->num_rows==1){
				$checkReflushTime1 = $redisObj->getSimpleOne($key."_reflush_time");
				//echo '<br/>';var_dump(time()-$checkReflushTime1);echo '<br/>';
				if(time()-$checkReflushTime1>$FT_M_ROU_EO_Time){//数据过期
					//echo "==================== out ====================<br/>";
					$matches=catchShampionByCurl();
					$updateRes = $redisObj->getSET($key."_reflush_time",time());
					if( $updateRes ){
						//echo "<br/>update redis<br/>";
						$setResult=$redisObj->setOne($key,json_encode($matches));
						if($setResult) mysqli_query($dbMasterLink,"COMMIT");
					}	
				}else{//直接读取redis
					//echo "==================== in1 ====================<br/>";
					$matchesJson = $redisObj->getSimpleOne($key);
					$matches = json_decode($matchesJson,true);
				}
			}
			mysqli_query($dbMasterLink,"ROLLBACK");
		}else{
			//echo "==================== in2 ====================<br/>";
			$matchesJson = $redisObj->getSimpleOne($key);
			$matches = json_decode($matchesJson,true);
		}
	}else{//------------------------------------------不存在,获取数据
			$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
			$lockResult = mysqli_query($dbMasterLink,"select Type from ".DBPREFIX."match_sports_running_lock where `Type` = '".$key."' for update");
			if($begin && $lockResult->num_rows==1){
				$checkReflushTime2 = $redisObj->getSimpleOne($key."_reflush_time");
				if($checkReflushTime2){
					//echo "==================== in3 ====================<br/>";
					$matchesJson = $redisObj->getSimpleOne($key);
					$matches = json_decode($matchesJson,true);
				}else{
					//echo "==================== new ====================<br/>";
					$matches=catchShampionByCurl();
					$updateRes = $redisObj->getSET($key."_reflush_time",time());
					if( $updateRes ){
						$setResult=$redisObj->setOne($key,json_encode($matches));
						if($setResult)  mysqli_query($dbMasterLink,"COMMIT");	
					}
				}
				mysqli_query($dbMasterLink,"ROLLBACK");			
			}
	}
	return $matches;
}

//获取冠军数据
function catchShampionByCurl(){
	global $langx,$fstype;
	$result='';
	//获取刷水账号
	$accoutArr = getFlushWaterAccount();
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	    /*$curl->set_referrer("" . $value['Datasite'] . "/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=$langx&mtype=3");
	    $html_data = $curl->fetch_url("" . $value['Datasite'] . "/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=$langx&rtype=fs&league_id=&FStype=$fstype");//redis缓存全部联赛,去掉联赛id参数,php进行筛选
	    $a = array(
	        "if(self == top)",
	        "<script>",
	        "</script>",
	        "new Array()",
	        "new Array();",
	        "\n\n"
	    );
	    $b = array(
	        "",
	        "",
	        "",
	        "",
	        "",
	        ""
	    );
	    $msg = str_replace($a,$b,$html_data);
	    preg_match_all("/new Array\((.+?)\);/is",$msg,$matches);
	    $cou_num=sizeof($matches[0]);
		if($cou_num>0){
			preg_match_all("/parent.areasarray=(.+?);/is",$html_data,$areasarray);
		    preg_match_all("/parent.itemsarray=(.+?);/is",$html_data,$itemsarray);
		    preg_match_all("/parent.leaguearray=(.+?);/is",$html_data,$leaguearray);
	        $result['data'] = $matches[0];
	        $result['areas'] = $areasarray;
	        $result['items'] = $itemsarray;
	        $result['league'] = $leaguearray;
			break;
	    }*/

        // 首先获取全部联赛的lid
        $postdata = array(
            'p' => 'get_league_list_FS',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => $fstype,    // FT  BK
            'FS' => 'Y',
            'showtype' => 'fu',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if ($aData['status']=='success'){

            $couFT=0;
            $aLid=[];
            if (count($aData['classifier']['region'])>0){
                $lids = getLids($aData);
                $aLid = explode(',' , $lids);
                $couLeague = count($aLid);
                $couFT+=$couLeague;
            }else{
                return $aLid;
            }

            // 循环将每一个联赛下面的全部冠军玩法都捞出来
            unset($postdata);
            unset($aData);
            $postdata = array(
                'p' => 'get_game_list_FS',
                'ver' => date('Y-m-d-H').$value['Ver'],
                'langx' => $langx,
                'uid' => $value['Uid'],
                'gtype' => $fstype,    // FT  BK
                'showtype' => 'FU',
                'rtype' => 'fs',
            );
            $aLidFs=[];
            foreach ($aLid as $k => $lid){

                $postdata['league_id'] = $lid;
                $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                $aData = xmlToArray($xml_data);
                $aLidFs[$lid] = $aData['game'];

            }
            $result=$aLidFs;
        }

	}
	return $result;
}

$allcount = 0;
$reBallCountCur = 0;
$result = getShampionMatches();

/*$matchesTem = isset($result['data'])?$result['data']:'';
$areasarray = isset($result['areas'])?$result['areas']:'';
$itemsarray = isset($result['items'])?$result['items']:'';
$leaguearray = isset($result['league'])?$result['league']:'';

$leagueSearchName=$matcheNew=array();
if(isset($league_id) && strlen($league_id)>2 && count($matchesTem)>0){
	$leagueSearchName = explode(',',$league_id);
	if(count($leagueSearchName>0)){
		foreach($matchesTem as $mk=>$mv){
			$mvStr=str_replace('\'','',$mv);
			$mvStrArr=explode(',',$mvStr);
			if(in_array($mvStrArr[2],$leagueSearchName)){
				$matches[]=$mv;
			}		
		}
	}else{
		$matches=$matchesTem;
	}
}else{
	$matches=$matchesTem;
}
$cou_num=count($matches);*/
if(strlen($league_id)==0 || $league_id=='ALL'){
	$leagueIdNum='ALL';	
}else{
	$leagueIdNum=count(explode(',',$league_id));
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv='Page-Exit' content='revealTrans(Duration=0,Transition=5)'>
<link rel="stylesheet" type="text/css" href="/<?php echo TPL_NAME;?>style/common.css?v=<?php echo AUTOVER; ?>" >
<link rel="stylesheet" type="text/css" href="/style/member/sports_common.css?v=<?php echo AUTOVER; ?>" >
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
<link rel="stylesheet" href="../../../style/member/mem_body_fs.css?v=<?php echo AUTOVER; ?>" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script>
LeaguesName='';
parent.legnum='<?php echo $leagueIdNum; ?>';
parent.nowtime='<?php echo $time?>';
parent.ch_leaguearray='<?php if($leagueIdNum!='All'){echo $league_id;}else{ echo 'ALL';}?>';
//parent.areasarray=<?php //echo ($areasarray[0] == null)?'0':$areasarray[1][0] ?>//;
//parent.itemsarray=<?php //echo ($itemsarray[0] == null)?'0':$itemsarray[1][0] ?>//;
//parent.leaguearray=<?php //echo ($leaguearray[0] == null)?'0':$leaguearray[1][0] ?>//;


parent.FStype='<?php echo $fstype ?>';
parent.uid='<?php echo $uid ?> ';
parent.rtype='<?php echo $rtype ?>';
parent.langx='<?php echo $langx ?>';
parent.base_url='uid=<?php echo $uid ?>&langx=<?php echo $langx ?>';
parent.sel_gtype='<?php echo $rtype ?><?php echo $fstype;?>';
parent.retime=180;
parent.retime_flag = 'N';	//自動更新旗標
parent.defaultOpen = true;         // 預設盤面顯示全縮 或是 全打開
parent.NoshowLeg=new Array();
parent.myLeg=new Array();
parent.LeagueAry=new Array();


/*
鍵盤
*/
document.onkeypress=checkfunc;
function checkfunc(e) {
    switch(event.keyCode){
    }
}

function CheckKey(){
    if(event.keyCode == 13) return true;
    if (event.keyCode!=46){
        if((event.keyCode < 48 || event.keyCode > 57))
        {
            alert(str_only_keyin_num);  /*僅能接受數字!!*/
            return false;
        }
    }
}
function countdown(){
    if (keepsec!=""){
        if (Showtypes=="P1"||Showtypes=="P2"||Showtypes=="P3"){
            reload_time.innerHTML=keepsec+"&nbsp"+str_sec+str_auto_upgrade+"&nbsp"+"--"+par_min+"~"+par_max;
        }else{
            reload_time.innerHTML=keepsec+"&nbsp"+str_sec+str_auto_upgrade+"&nbsp";
        }
        keepsec--;
    }
}

var keepsec="";
cc=setInterval("countdown()",1000);


</script>
</head>
<body id="MNFS" class="bodyset_browse_<?php echo TPL_FILE_NAME;?>" onLoad="onLoad()"> <!-- onLoad="set_reloadtime();" -->
<table border="0" cellpadding="0" cellspacing="0" id="box">

  <tr>
    <td class="mem">
    <h2>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
          <tr>
            <td id="page_no"><span id="pg_txt"></span></td>
                <td id="tool_td">
              
                  <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                    <tr>

                        <td class="var_nav_bg leg_btn">
                            <div onClick="javascript:chg_league();" id="sel_league">
                                <?php echo $U_19 ?> (<tt id="str_num" ></tt>)
                            </div>
                        </td>
                        <td class="refresh_btn" id="rsu_refresh" onClick="this.className='refresh_btn_on';">
                            <!--秒數更新-->
                            <div onClick="javascript:reload_var()"><font id="refreshTime" ></font></div>
                        </td>

                     </tr>
                  </table>
              
                </td>
              </tr>
        </table>
      </h2>
      <!--     資料顯示的layer     -->
      <div id="showgames" class="game-div game">
      <?php

        foreach ($result as $lid => $league){
            if (!isset($league['gamecount'])){
                foreach ($league as $k => $match){
                    $leagues[]=$match;
                }
            }else{
                $leagues[]=$league;
            }
        }

		if(isset($leagues)&&is_array($leagues)&&count($leagues)>0){
			foreach($leagues as $key=>$match){

			    $match[2]=$match['league'];
			    $match[0]=$match['gid'];
			    $match[1]=$match['datetime'];
			    $match[3]=$match['teamsname'];
			    $match[4]=$match['gamecount'];
	      		/*$match=str_replace('new Array(', '', $match);
	      		$match=str_replace(');', '', $match);
	      		$match=str_replace('\'', '', $match);
	      		$match=explode(',',$match);
	      		$matchNum=count($match);*/
	      		?>
					<table border="0" cellpadding="0" cellspacing="0" class="fs_leg jack_2018">
						<tbody>
							<tr class="b_hline tr_league">
								<td class="legicon" onclick="showLeg('<?php echo $match[2];?>');">
									<span id="<?php echo $match[0].'_'.$match[2];?>" class="showleg">
									<span id="LegOpen"></span>
									  <!--展開聯盟-符號-->
									  <!--span id="LegOpen"></span-->
									  <!--收合聯盟-符號-->
									  <!--div id="LegClose"></div-->
									</span>
								</td>
									<td onclick="showLeg('<?php echo $match[2];?>');" class="leg_bar"><?php echo $match[2];?></td>
									<td nowrap="" align="right"><?php echo $match[1];?></td>
							</tr>
						</tbody>
					</table>
	    			<table cellpadding=0 cellspacing=0 class='table-title'>
						<tbody>
							<tr id='TR_<?php echo $match[0];?>'>
							  <td nowrap="" align='left'><?php echo $match[3];?></td>
							</tr>
						</tbody>
					</table>
					<?php
//						for($i=6;$i<count($match);$i=$i+4){
						foreach ($match['rtypes'] as $k => $rtype){
							if($k%2==0){
					?>
					<?php if($rtype['ioratio']>0){?>
					<table class="b_tab TR_<?php echo $match[0];?>" cellpadding="0" cellspacing="0" border="0">
						<tbody>
							<tr>
								<td bgcolor="white" width="90%" class="team_name"><?php echo $rtype['teams']/*$match[$i+2]*/;?></td>
									<td width="60" class="r_bold" bgcolor="white"><font class="b_cen" title="<?php echo $rtype['teams']/*$match[$i+2]*/;?>"
										style="cursor:pointer"
										onclick="parent.mem_order.betOrder('FT','NFS','gametype=<?php echo $match['FStype'];?>&gid=<?php echo $match[0];?>&uid=<?php echo $uid;?>&rtype=<?php echo $rtype['rtype'];?>&wtype=FS&langx=<?php echo $langx;?>');"><?php echo $rtype['ioratio']/*change_rate($open,$match[$i+3])*/;?></font>
									</td>
							</tr>
						</tbody>
					</table>
					<?php }?>
					<?php }else{
						if(strlen($rtype['teams'])>0){
						?>
                    <?php if($rtype['ioratio']>0){?>
					<table class="b_tab TR_<?php echo $match[0];?>" cellpadding="0" cellspacing="0" border="0">
						<tbody>
							<tr>
								<td bgcolor="white" width="90%" class="team_name"><?php echo $rtype['teams']/*$match[$i+2]*/;?></td>

									<td width="60" class="r_bold" bgcolor="white"><font class="b_cen" title="<?php echo $rtype['teams']/*$match[$i+2]*/;?>"
										style="cursor:pointer"
										onclick="parent.mem_order.betOrder('FT','NFS','gametype=<?php echo $match['FStype'];?>&gid=<?php echo $match[0];?>&uid=<?php echo $uid;?>&rtype=<?php echo $rtype['rtype'];?>&wtype=FS&langx=<?php echo $langx;?>');"><?php echo $rtype['ioratio']/*change_rate($open,$match[$i+3])*/;?></font>
									</td>
							</tr>
						</tbody>
					</table>
                            <?php }?>
					<?php 	}
						}
//					}
                }?>

                <?php

                $leaguetitle[$match[2]][] = $match[0] ; // 联赛
                ?>

		<?php } ?>	
	<?php }else{
		echo "<span class='no_game'>您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</span>";
	}
	 
       $LeagueAry[] = $match[2] ; // 联赛
       $LeagueAry = array_unique($LeagueAry);
       //$leaguetitle[$match['league']][] = $match['dategh'] ; // 联赛
    
		?>
      </div>
      </td>
  </tr>
  <tr>
    <td id="foot"><b>&nbsp;</b></td>
  </tr>
</table>

<script language="JavaScript">
    <?php
    foreach ($leaguetitle as $key=>$leatitle){
        if($leatitle){
            // echo $key.'--' ;var_dump($leatitle);
            // var_dump($leatitle);
            $leastr = implode(',',$leatitle) ;
            echo "parent.myLeg['$key']= new Array('$leastr') ;\n"; // 联赛
            $k ++ ;
        }

    }

    ?>
</script>

<!--选择联赛-->
<div id="legView" style="display:none;margin-left:10px" class="legView"  >
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>
    <div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>
    <div class="leg_foot"></div>
</div>
<div id=NoDataTR style="display:none;"></div>


<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid = '<?php echo $uid;?>';
    var langx = '<?php echo $langx;?>';
</script>
</body>
</html>
