<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require (CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");

    require (CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require("../../include/config.inc.php");
    require_once("../../../../../common/sportCenterData.php");

    require("../../include/curl_http.php");
    require_once("../../include/address.mem.php");

    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }

}

$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_ft_pr'];

$langx="zh-cn";
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$dataArray= array() ; // 把需要的数据重新放在数组里面
if (SPORT_FLUSH_WAY=='ra686') {

    // 获取综合过关全部日期的lid
    $jsonData = $curl->fetch_url($FT_P3_LEAGUE_API);
    $aData = json_decode($jsonData,true);
    if ($aData['success']){
        $datainfos=[];
        if (count($aData['data']['competitionList'])>0 || !empty($aData['data']['competitionList'])){
            $leagueTmp = getLids686($aData);
            $lid = $leagueTmp['lid'];
            $aLeagueRegion = $leagueTmp['leagueRegion'];
            /*foreach ($aData['data']['competitionList'] as $k => $v){
                // 点击综合过关首先显示联赛地区列表
                // 联赛、赛事数量、国家地区
                foreach ($v['seasons'] as $k2 => $v2){
                    $aLeague[$v2['name']]['region'] = $v['name'];
                    $aLeague[$v2['name']]['M_League'] = $v2['name'];
                    $aLeague[$v2['name']]['num'] = $v2['count'];
                }
            }
            $redisObj->setOne('FT_P3_LEAGUE_REGION',json_encode($aLeague, JSON_UNESCAPED_UNICODE));*/
            // 今日赛事的联赛信息存入redis
            $redisObj->setOne('FT_P3_LEAGUE_REGION',json_encode($aLeagueRegion, JSON_UNESCAPED_UNICODE));
        }
        else{
            echo ('success 足球串关的联赛LID没有数据-');
        }
    }
    else{
        echo ('error 足球串关的联赛LID拉取报错-');
    }

    // 根据LID获取综合过关全部的盘口
    $jsonData=$curl->fetch_url($FT_P3_MATCH_API.$lid);
    $aData = json_decode($jsonData,true);
    $cou = count($aData['data']['seasons']); // 当前赛事场数
    if($cou>0){
        foreach ($aData['data']['seasons'] as $k => $aLeagues){
            $isEsport = $aLeagues['esport']; // 是否电竞盘口
            $league = $aLeagues['name']; // 联赛名称
            foreach ($aLeagues['matches'] as $k2 => $aMatchs){

                $gid = $aMatchs['matchId']; // 赛事 ID
                $aObtSelections[$gid] = $aMatchs['obtSelections'];// 标签数据
                foreach ($aMatchs['markets'] as $k4 => $market){

                    if ($aMatchs['liveStatus']=='HT'){
                        $aMatchs['clock']=$aMatchs['liveStatusText'];
                    }
                    $aGames[$gid]['GID'] = $gid;
                    $aGames[$gid]['LEAGUE'] = $league;
                    $aGames[$gid]['startTime'] = str_replace('T', ' ', $aMatchs['startTime']);
                    $aGames[$gid]['MORE'] = $aMatchs['totalMarkets']; // 更多玩法数量

                    $aGames[$gid]['TEAM_H'] = $aMatchs['competitors']['home']['name'];
                    $aGames[$gid]['TEAM_C'] = $aMatchs['competitors']['away']['name'];
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口

                    // 主盘口玩法转换
                    $aGamesTmp=masterP3MethodsTrans($aMatchs['markets'],'re');

                    foreach ($aGamesTmp as $gidTmp => $gameTmp){ // 将处理好的附属盘口合到数据集中
                        foreach ($gameTmp as $k => $v){
                            $aGames[$gid][$k] = $v;
                        }
                    }

                }
                $datainfo = $aGames[$gid];

                $dataArray[$gid]=(array($datainfo['STRONG'],$datainfo['RATIO_RE'],$datainfo['IOR_REH'],$datainfo['IOR_REC'],$datainfo['RATIO_ROUO'],$datainfo['RATIO_ROUU'],$datainfo['IOR_ROUC'],$datainfo['IOR_ROUH'],$datainfo['IOR_REOO'],$datainfo['IOR_REOE'],
                    $datainfo['IOR_RMH'],$datainfo['IOR_RMC'],$datainfo['IOR_RMN'],$datainfo['IOR_HREH'],$datainfo['IOR_HREC'],$datainfo['IOR_HROUH'],$datainfo['IOR_HROUC'],
                    $datainfo['HSTRONG'],$datainfo['RATIO_HRE'],$datainfo['RATIO_HROUU'],$datainfo['RATIO_HROUO'],$datainfo['IOR_HRMH'],$datainfo['IOR_HRMC'],$datainfo['IOR_HRMN'],$datainfo['MORE'])); // 把数据放在二维数组里面
            }
        }
    }


}
else{
foreach($accoutArr as $key=>$value){ //在扩展表中获取账号重新刷水
//	for($page_no=0;$page_no<10;$page_no++){
//        echo '--';
//	    print_r($page_no);
//	    echo ':';
//		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=p3&uid=".$value['Uid']."&langx=".$langx."&mtype=4");
//		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=p3&uid=".$value['Uid']."&langx=".$langx."&mtype=4&showgtype=FU&g_date=ALL&page_no=".$page_no);

//		print_r($html_data);die;
//		$matches = get_content_deal($html_data);

        // 获取综合过关全部日期的lid
        $postdata = array(
            'p' => 'get_league_list_All',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'FT',
            'FS' => 'N',
            'showtype' => 'p3',
            'date' => 'all',
            'nocp' => 'N',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if ($aData['status']=='success'){
            if (count($aData['classifier']['region'])>0){
                $lid = getLids($aData)['lid'];
            }
            else{
                exit('success 没有足球的综合过关LID数据'.$langx);
            }
        }
        else{
            exit('error 没有足球的综合过关LID数据'.$langx);
        }

        // 获取综合过关全部的盘口
        $postdata=[];
        $postdata = array(
            'uid' => $value['Uid'],
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'p' => 'get_game_list',
            'p3type' => 'P3',
            'date' => 'all',
            'gtype' => 'ft',
            'showtype' => 'parlay',
            'rtype' => 'rb',
            'ltype' => '4',
//            'lid' => '108902,101061,106191,100160,100712,100832,106421,103761,107642,100845,100253,101553,102000,100021,100235,100127,100128,100075,100303,102258,100032,100028,100117,100122,103711,101354,103313,102814,108304,100080,100217,100098,100868,100030,100146,100087,100857,101369,107656,102812',
            'lid' => $lid,
            'action' => 'clickCoupon',
            'sorttype' => 'L',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if(isset($aData['totalDataCount'])){
            $cou= $aData['totalDataCount'];
        }else{
            $cou=0;
        }

		if($cou>0){ //可以抓到数据
//			for($i=0;$i<$cou;$i++){
//				$messages=$matches[$i];
//				$messages=str_replace(");",")",$messages);
//				$messages=str_replace("cha(9)","",$messages);
//				$datainfo=eval("return $messages;");
            if ($aData['totalDataCount']==1){
                $tmp['ec']['data']['game']=$aData['ec']['game'];
                $aData=array();
                $aData=$tmp;
            }
            foreach ($aData['ec'] as $k => $v){

                $datainfo=$v['game'];
                $datainfo[0]=$datainfo['GID'];
                $datainfo[2]=$datainfo['LEAGUE'];
                $datainfo[5]=$datainfo['TEAM_H'];
                $datainfo[6]=$datainfo['TEAM_C'];

                // 将从正网拉取的测试数据过滤掉
                // stripos 查找字符串首次出现的位置（不区分大小写）
                $pos_m = stripos($datainfo[2], 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($datainfo[2], '測試'); // 查找联赛名称是否含有 測試
                $pos_mb = stripos($datainfo[5], 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($datainfo[5], '測試'); // 检查主队名称是否含有 測試
                $pos_tg = stripos($datainfo[6], 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($datainfo[6], '測試'); // 检查客队名称是否含有 測試
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                    $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                    continue;
                }

                /*
               * $row["MB_P_LetB_Rate_H"];  // 半场让球主队 $datainfo[23] 原来  $datainfo[63]
                 $row["TG_P_LetB_Rate_H"]; // 半场让球客队 $datainfo[24] 原来  $datainfo[64]
                 $row['MB_P_Dime_Rate_H']; // 半场主队大 $datainfo[28] 原来  $datainfo[68]
                 $row['TG_P_Dime_Rate_H']; // 半场客队小 $datainfo[27] 原来  $datainfo[67]
                 $row['MB_P_Win_Rate_H']; // 半场客队小 $datainfo[29] 原来  $datainfo[96]
                 $row['TG_P_Win_Rate_H']; // 半场客队小 $datainfo[30] 原来  $datainfo[97]
                 $row['M_P_Flat_Rate_H']; // 半场客队小 $datainfo[31] 原来  $datainfo[98]
               * */
                $dataArray[$datainfo[0]]=(array($datainfo['STRONG'],$datainfo['RATIO_RE'],$datainfo['IOR_REH'],$datainfo['IOR_REC'],$datainfo['RATIO_ROUO'],$datainfo['RATIO_ROUU'],$datainfo['IOR_ROUC'],$datainfo['IOR_ROUH'],$datainfo['IOR_REOO'],$datainfo['IOR_REOE'],
                    $datainfo['IOR_RMH'],$datainfo['IOR_RMC'],$datainfo['IOR_RMN'],$datainfo['IOR_HREH'],$datainfo['IOR_HREC'],$datainfo['IOR_HROUH'],$datainfo['IOR_HROUC'],
                    $datainfo['HSTRONG'],$datainfo['RATIO_HRE'],$datainfo['RATIO_HROUU'],$datainfo['RATIO_HROUO'],$datainfo['IOR_HRMH'],$datainfo['IOR_HRMC'],$datainfo['IOR_HRMN'],'')); // 把数据放在二维数组里面
			}
		} 
	//}
}
}

if($cou>0 and count($dataArray)>0){
    $ids = implode(',', array_keys($dataArray));
    // echo $ids;
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="ShowTypeP = CASE MID " ;
    $t_sql .="M_P_LetB = CASE MID ";
    $l_sql .="MB_P_LetB_Rate = CASE MID ";
    $tg_sql .="TG_P_LetB_Rate = CASE MID ";
    $mp_sql .="MB_P_Dime = CASE MID ";
    $tp_sql .="TG_P_Dime = CASE MID ";
    $mr_sql .="MB_P_Dime_Rate = CASE MID ";
    $tr_sql .="TG_P_Dime_Rate = CASE MID ";
    $sp_sql .="S_P_Single_Rate = CASE MID ";
    $spd_sql .="S_P_Double_Rate = CASE MID ";
    $mv_sql .="MB_P_Win_Rate = CASE MID ";
    $tv_sql .="TG_P_Win_Rate = CASE MID ";
    $mf_sql .="M_P_Flat_Rate = CASE MID ";
    $mplr_sql .="MB_P_LetB_Rate_H = CASE MID ";
    $tplr_sql .="TG_P_LetB_Rate_H = CASE MID ";
    $tpr_sql .="TG_P_Dime_Rate_H = CASE MID ";
    $mh_sql .="MB_P_Dime_Rate_H = CASE MID ";
    $shp_sql .="ShowTypeHP = CASE MID ";
    $mpl_sql .="M_P_LetB_H = CASE MID ";
    $mpd_sql .="MB_P_Dime_H = CASE MID ";
    $tpd_sql .="TG_P_Dime_H = CASE MID ";
    $tpsr_sql .="MB_P_Win_Rate_H = CASE MID ";
    $msh_sql .="TG_P_Win_Rate_H = CASE MID ";
    $mpr_sql .="M_P_Flat_Rate_H = CASE MID ";
    $p3_sql .="P3_Show = CASE MID ";
//    $more_sql .="more = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
        $tg_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
        $mp_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
        $tp_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
        $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
        $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
        $sp_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
        $spd_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
        $mv_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
        $tv_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
        $mf_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
        $mplr_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
        $tplr_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
        $tpr_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
        $mh_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
        $shp_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
        $mpl_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
        $mpd_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
        $tpd_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
        $tpsr_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
        $msh_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
        $mpr_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
        $p3_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
//        $more_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tg_sql.$e_sql.$mp_sql.$e_sql.$tp_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$sp_sql.$e_sql.$spd_sql.$e_sql.$mv_sql.$e_sql.$tv_sql.$e_sql.$mf_sql.$e_sql.$shp_sql.$e_sql.$mpl_sql.$e_sql.$mplr_sql.$e_sql.$tplr_sql.$e_sql.$mpd_sql.$e_sql.$tpd_sql.$e_sql.$tpr_sql.$e_sql.$mh_sql.$e_sql.$tpsr_sql.$e_sql.$msh_sql.$e_sql.$mpr_sql.$e_sql.$p3_sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
//     echo $sql ;
    mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");
}

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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
<script> 

var limit="<?php echo $settime?>" 
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
		curtime=curmin+"秒後自動獲取!" 
	else 
		curtime=cursec+"秒後自動獲取!" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      綜合關數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
