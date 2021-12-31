<?php
// 25秒进球取消注单

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require(CONFIG_DIR."/include/define_function.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
    require ('../include/curl_http.php');
    require ("../include/define_function.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$mysql = "select udp_ft_score,udp_ft_results from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime=$row['udp_ft_score'];
$time=$row['udp_ft_results'];
$list_date=date('Y-m-d',time()-$time*60*60);
$date=date('Y-m-d',time()-$time*60*60);
$mDate=date('Y-m-d',time()-$time*60*60);

//获取刷水账号
$accoutArr = array();
//$langx="zh-tw";
$accoutArr=getFlushWaterAccount();//数组随机排序

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");

$methodStr="LineType=9 or LineType=19 or LineType=10 or LineType=20 or LineType=21 or LineType=31 or LineType=50 or ";
$methodStr .="LineType=104 or LineType=105 or LineType=106 or LineType=107 or LineType=115 or LineType=118 or ";//以下为新增玩法，请参见FT_order_hre/re_finsih.php
$methodStr .="LineType=119 or LineType=120 or LineType=161 or LineType=122 or LineType=123 or LineType=124 or LineType=128 or "; 
$methodStr .="LineType=129 or LineType=130 or LineType=134 or LineType=135 or LineType=137 or LineType=141 or ";
$methodStr .="LineType=144 or LineType=142 or LineType=204 or LineType=206";


$cou1=0;
$datainfos=[];
if($flushWay == 'ra686'){
    $jsonData = $curl->fetch_url($FT_RB_API); // 请求主盘口
    $aData = json_decode($jsonData,true);
    $cou1= count($aData['data']['seasons']);
    if($cou1>0 and $aData['success']){
        foreach ($aData['data']['seasons'] as $k => $aLeagues) {
            $league = $aLeagues['name']; // 联赛名称
            $isEsport = $aLeagues['esport'];
            foreach ($aLeagues['matches'] as $k2 => $aMatchs) {
                $gid = $aMatchs['matchId'];
                if ($aMatchs['liveStatus']=='HT'){
                    $aMatchs['clock']=$aMatchs['liveStatusText'];
                }
                $aGames[$gid]['GID'] = $gid;
                $aGames[$gid]['LEAGUE'] = $league;
                $aGames[$gid]['startTime'] = $aGames[$gid]['DATETIME'] = str_replace('T', ' ', $aMatchs['startTime']);
                $aGames[$gid]['RETIMESET'] = $aMatchs['liveStatus'].'^'.$aMatchs['clock']; //2H^80:09  HT~半场
                $aGames[$gid]['TIMER'] = $aMatchs['clock'];
                $aGames[$gid]['MORE'] = $aMatchs['totalMarkets'];
                // 球队信息【队名、比分、红牌】
                $competitors = $aMatchs['competitors'];
                $aGames[$gid]['TEAM_H'] = $competitors['home']['name'];
                $aGames[$gid]['TEAM_C'] = $competitors['away']['name'];
                $aGames[$gid]['SCORE_H'] = $competitors['home']['score']; // 主队比分
                $aGames[$gid]['SCORE_C'] = $competitors['away']['score']; // 客队比分
                $aGames[$gid]['REDCARD_H'] = $competitors['home']['redCard']; // 主队红牌数
                $aGames[$gid]['REDCARD_C'] = $competitors['away']['redCard']; // 客队红牌数
                $aGames[$gid]['Neutral'] = $aMatchs['neutral']; // 中立场
                $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口

            }

        }
    }
    $jsonData = $curl->fetch_url($FT_RB_CORNERS_API); // 请求主盘口
    $aData = json_decode($jsonData,true);
    $cou= count($aData['data']['seasons']);
    if($cou>0 and $aData['success']){
        foreach ($aData['data']['seasons'] as $k => $aLeagues) {
            $league = $aLeagues['name']; // 联赛名称
            $isEsport = $aLeagues['esport'];
            foreach ($aLeagues['matches'] as $k2 => $aMatchs) {

                // 将玩法的信息捞出
                $aGamesTmp=masterMethodsTrans($aMatchs['markets'], 're');
                foreach ($aGamesTmp as $gid => $gameTmp){
                    // 将每个字段的值合到数据集合中
                    foreach ($gameTmp as $k => $v){
                        $aGames[$gid][$k] = $v;
                    }
                    // 当前盘口角球盘口的球队信息
                    $aGames[$gid]['LEAGUE']=$league;
                    if ($aMatchs['liveStatus']=='HT'){ $aGames[$gid]['clock']=$aMatchs['liveStatusText']; }
                    $aGames[$gid]['startTime']=$aGames[$gid]['DATETIME']=str_replace('T', ' ', $aMatchs['startTime']);
                    $aGames[$gid]['RETIMESET'] = $aMatchs['liveStatus'].'^'.$aMatchs['clock']; //2H^80:09  HT~半场
                    $aGames[$gid]['TIMER'] = $aMatchs['clock'];
                    // 角球球队信息【队名、比分】
                    $competitors = $aMatchs['competitors'];
                    $aGames[$gid]['TEAM_H'] = $competitors['home']['name']." -角球数";
                    $aGames[$gid]['TEAM_C'] = $competitors['away']['name']." -角球数";
                    $aGames[$gid]['SCORE_H'] = $competitors['home']['cornerKick']; // 主队比分
                    $aGames[$gid]['SCORE_C'] = $competitors['away']['cornerKick']; // 客队比分
                    $aGames[$gid]['Neutral'] = $aMatchs['neutral']; // 中立场
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口

                }
            }
        }

    }

    $datainfos = $aGames;

}
else{
foreach($accoutArr as $key=>$value){
//		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
//		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=re&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
//		$matches = get_content_deal($html_data);
//		$cou1=sizeof($matches);
    $postdata = array(
        'p' => 'get_game_list',
        'ver' => date('Y-m-d-H').$value['Ver'],
        'langx' => 'zh-cn',
        'uid' => $value['Uid'],
        'gtype' => 'ft',
        'showtype' => 'live',
        'rtype' => 'rb',
        'ltype' => '4',
        'sorttype' => 'T',
    );
    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
    $aData = xmlToArray($xml_data);
    if(isset($aData['totalDataCount'])){
        $cou1= $aData['totalDataCount'];
    }else{
        if ($aData['code']=='error' and $aData['msg']=='doubleLogin') {
            if ($value['status'] != 1) {
                // 刷不到水，返水数据异常时，重置刷水账号的状态  0  正常  1 异常
                $datetime = date('Y-m-d H:i:s');
                $id = $value['ID'];
                $sql1 = "update " . DATAHGPREFIX . "web_getdata_account_expand set `datetime`='" . $datetime . "',status='1' where ID=" . $id;
                $res1 = mysqli_query($dbMasterLink, $sql1);

            }
        }
    }

		if($cou1>0){//可以抓到数据
//			for($i=0;$i<$cou1;$i++){
//				$messages=$matches[$i];
//				$messages=str_replace("new Array(","",$messages);
//				$messages = str_replace($a,$b,$messages);
//			    $messages=str_replace(");","",$messages);
//			    $messages=str_replace("'","",$messages);
//			    $datainfo=explode(",",$messages);
            if ($aData['ec']['game']['GID']){
                $datainfo=$aData['ec']['game'];
                $datainfos[$datainfo['GID']]=$datainfo;
            }
            else{
                foreach ($aData['ec'] as $k => $v) {
                    $datainfo=$v['game'];
                    $datainfos[$datainfo['GID']]=$datainfo;
                }
            }
			break;
		} 
}
}


foreach ($datainfos as $k => $datainfo) {
    $datainfo[0]=$datainfo['GID'];
    $datainfo[18]=$datainfo['SCORE_H']>0?$datainfo['SCORE_H']:0;
    $datainfo[19]=$datainfo['SCORE_C']>0?$datainfo['SCORE_C']:0;
    $mb_inball_hr=trim(strip_tags($datainfo[18]));
    $tg_inball_hr=trim(strip_tags($datainfo[19]));
    $thistime=date("Y-m-d H:i:s",(time()-25));
    $sql="select ID,MID,Userid,M_Name,BetScore,M_Result,QQ83068506 from ".DBPREFIX."web_report_data where QQ83068506<>'' and BetTime>'$thistime' and MID='".(int)$datainfo[0]."' and M_Date='".$list_date."' and (".$methodStr.") and M_Result='' and Cancel!=1 and Confirmed=0 and Checked=0 and Active=1";
    $result = mysqli_query($dbLink, $sql);
    while($rows=mysqli_fetch_assoc($result)){
        $id=$rows['ID'];
        $mid=$rows['MID'];
        $userid=$rows['Userid'];
        $username=$rows['M_Name'];
        $betscore=$rows['BetScore'];
        $inball=$mb_inball_hr.":".$tg_inball_hr;
        if($rows['QQ83068506']!=$inball){
            $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
            if($beginFrom){
                $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                if($resultMem){
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    $sql1="update ".DBPREFIX."web_report_data set VGOLD='0',M_Result='0',D_Result='0',C_Result='0',B_Result='0',A_Result='0',T_Result='0',Cancel=1,Checked=1,Confirmed='-18',Danger=0,MB_Ball='$mb_inball_hr',TG_Ball='$tg_inball_hr',updateTime='".date('Y-m-d H:i:s',time())."' where ID=$id and M_Result='' and Cancel!=1 ";
                    if(mysqli_query($dbMasterLink, $sql1)){
                        $tsql = "update ".DBPREFIX.MEMBERTABLE." SET Money=Money+$betscore where ID=$userid";
                        if(mysqli_query($dbMasterLink,$tsql)){
                            $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$betscore,$rowMem['Money']+$betscore,2,9,$id,"[Score18]FT取消订单,25s内进球"));
                            if($moneyLogRes){
                                mysqli_query($dbMasterLink,"COMMIT");
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                echo "用户资金日志写入失败！<br/>";
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "用户资金更新失败！<br/>";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "取消订单数据更新失败！<br/>";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "用户资金锁定失败！<br/>";
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "事务开启失败！<br/>";
            }
        }
    }
}

function get_content_deal($html_data){
	$a = array(
		"<font style=background-color=red>",
		"</font>"
	);
	$b = array(
		"",
		""
	);
	preg_match_all("/new Array\((.+?)\);/is",$html_data,$matches);
	return $matches[0];	
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>进球取消</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 

var limit="8" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒后自动获取最新数据！" 
	else 
		curtime=cursec+"秒后自动获取最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<body>
<table width="220" height="190" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="220" height="190" align="center"><?php echo $date?><br><br><span id="timeinfo"></span><br>
	<input type=button name=button value="足球滚球进球取消25s刷新" onClick="window.location.reload()"></td>  
  </tr>
</table>
</body>
</html>