<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require (CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require (CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else {
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
$settime=$refurbishTimeData[0]['udp_ft_pd'];

$allcount=0;
$langx="zh-cn";
$accoutArr = array();
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$dataArray= array() ; // 把需要的数据重新放在数组里面
$redisObj = new Ciredis();
if($flushWay == 'ra686'){
    global $FT_TODAY_PD_API;
    $lid = $redisObj->getSimpleOne('TODAY_FT_LID');
    $jsonData = $curl->fetch_url($FT_TODAY_PD_API.$lid);  // 请求波胆主盘口
    $aData = json_decode($jsonData,true);
    $cou= count($aData['data']['seasons']);

    if($cou>0 and $aData['success']) {
        foreach ($aData['data']['seasons'] as $k => $aLeagues) {
            $league = $aLeagues['name']; // 联赛名称
            $isEsport = $aLeagues['esport'];
            foreach ($aLeagues['matches'] as $k2 => $aMatchs) {
                $gid = $aMatchs['matchId'];

                $aGames[$gid]['GID'] = $gid;
                $aGames[$gid]['LEAGUE'] = $league;
                $competitors = $aMatchs['competitors'];
                $aGames[$gid]['TEAM_H'] = $competitors['home']['name'];
                $aGames[$gid]['TEAM_C'] = $competitors['away']['name'];
                if ($aMatchs['showStat']){
                    $pd_show='1';
                }else{
                    $pd_show='0';
                }

                // 主盘口玩法转换
                $aGamesTmp=masterMethodsTrans($aMatchs['markets'], '');

                foreach ($aGamesTmp as $gidTmp => $gameTmp){
                    foreach ($gameTmp as $k => $v){
                        $aGames[$gid][$k] = $v;
                    }
                }
                $datainfo = $aGames[$gid];
                $dataArray[$gid]=(array($datainfo['IOR_H1C0'],$datainfo['IOR_H2C0'],$datainfo['IOR_H2C1'],$datainfo['IOR_H3C0'],$datainfo['IOR_H3C1'],$datainfo['IOR_H3C2'],$datainfo['IOR_H4C0'],$datainfo['IOR_H4C1'],$datainfo['IOR_H4C2'],$datainfo['IOR_H4C3'],$datainfo['IOR_H0C0'],$datainfo['IOR_H1C1'],$datainfo['IOR_H2C2'],$datainfo['IOR_H3C3'],$datainfo['IOR_H4C4'],$datainfo['IOR_OVH'],$datainfo['IOR_H0C1'],$datainfo['IOR_H0C2'],$datainfo['IOR_H1C2'],$datainfo['IOR_H0C3'],$datainfo['IOR_H1C3'],$datainfo['IOR_H2C3'],$datainfo['IOR_H0C4'],$datainfo['IOR_H1C4'],$datainfo['IOR_H2C4'],$datainfo['IOR_H3C4'],$pd_show)); // 把数据放在二维数组里面
            }
        }

    }

    $allcount = count($dataArray);

}
else{
foreach($accoutArr as $key=>$value){
//	for($page_no=0;$page_no<10;$page_no++){
//		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=pd&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
//		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=pd&uid=".$value['Uid']."&langx=".$langx."&mtype=3&page_no=".$page_no);
//		$matches = get_content_deal($html_data);
//		$cou=sizeof($matches);
        // 获取今日赛事的联赛ID，波胆也是
        $postdata = array(
            'p' => 'get_league_list_All',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'FT',
            'showtype' => 'ft',
            'FS' => 'N',
            'date' => '0',
            'nocp' => 'N',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        $lid = $aData['coupons']['coupon'][0]['lid'];

        $postdata = array(
            'uid' => $value['Uid'],
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'p' => 'get_game_list',
            'gtype' => 'ft',
            'showtype' => 'today',
            'rtype' => 'pd',
            'ltype' => '4',
            'lid' => $lid,
            'action' => 'clickCoupon',
            'sorttype' => 'T',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if(isset($aData['totalDataCount'])){
            $cou= $aData['totalDataCount'];
        }else{
            $cou=0;
        }

		if($cou>0){
            foreach ($aData['ec'] as $k => $v){
                $datainfo=$v['game'];
//				$messages=$matches[$i];
//				$messages=str_replace(");",")",$messages);
//				$messages=str_replace("cha(9)","",$messages);
//				$datainfo=eval("return $messages;");

                $datainfo[0]=$datainfo['GID'];
                $datainfo[2]=$datainfo['LEAGUE'];
                $datainfo[5]=$datainfo['TEAM_H'];
                $datainfo[6]=$datainfo['TEAM_C'];
                if ($datainfo['HIDE_N']=='N'){
                    $pd_show='1';
                }else{
                    $pd_show='0';
                }

				if (!empty($datainfo)){

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

                    $dataArray[$datainfo[0]]=(array($datainfo['IOR_H1C0'],$datainfo['IOR_H2C0'],$datainfo['IOR_H2C1'],$datainfo['IOR_H3C0'],$datainfo['IOR_H3C1'],$datainfo['IOR_H3C2'],$datainfo['IOR_H4C0'],$datainfo['IOR_H4C1'],$datainfo['IOR_H4C2'],$datainfo['IOR_H4C3'],$datainfo['IOR_H0C0'],$datainfo['IOR_H1C1'],$datainfo['IOR_H2C2'],$datainfo['IOR_H3C3'],$datainfo['IOR_H4C4'],$datainfo['IOR_OVH'],$datainfo['IOR_H0C1'],$datainfo['IOR_H0C2'],$datainfo['IOR_H1C2'],$datainfo['IOR_H0C3'],$datainfo['IOR_H1C3'],$datainfo['IOR_H2C3'],$datainfo['IOR_H0C4'],$datainfo['IOR_H1C4'],$datainfo['IOR_H2C4'],$datainfo['IOR_H3C4'],$pd_show)); // 把数据放在二维数组里面

				    $allcount++;
				}else{
					continue;
				}
			}		
		}else{
			break;
		} 
//	}
	if($allcount>0)	break;
}
}

if($allcount>0 and count($dataArray)>0) { //可以抓到数据
    //var_dump($dataArray);
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="MB1TG0 = CASE MID " ;
    $t_sql .="MB2TG0 = CASE MID ";
    $l_sql .="MB2TG1 = CASE MID ";
    $tp_sql .="MB3TG0 = CASE MID ";
    $mb_sql .="MB3TG1 = CASE MID ";
    $tb_sql .="MB3TG2 = CASE MID ";
    $mr_sql .="MB4TG0 = CASE MID ";
    $tr_sql .="MB4TG1 = CASE MID ";
    $tt_sql .="MB4TG2 = CASE MID ";
    $pr_sql .="MB4TG3 = CASE MID ";
    $mf_sql .="MB0TG0 = CASE MID ";
    $mh_sql .="MB1TG1 = CASE MID ";
    $mh2_sql .="MB2TG2 = CASE MID ";
    $mh3_sql .="MB3TG3 = CASE MID ";
    $mh4_sql .="MB4TG4 = CASE MID ";
    $u5_sql .="UP5 = CASE MID ";
    $m1_sql .="MB0TG1 = CASE MID ";
    $m2_sql .="MB0TG2 = CASE MID ";
    $mb1_sql .="MB1TG2 = CASE MID ";
    $mb2_sql .="MB0TG3 = CASE MID ";
    $mb3_sql .="MB1TG3 = CASE MID ";
    $mb4_sql .="MB2TG3 = CASE MID ";
    $mb5_sql .="MB0TG4 = CASE MID ";
    $mb6_sql .="MB1TG4 = CASE MID ";
    $mb7_sql .="MB2TG4 = CASE MID ";
    $mb8_sql .="MB3TG4 = CASE MID ";
    $hs_sql .="PD_Show = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
        $tp_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
        $mb_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
        $tb_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
        $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
        $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
        $tt_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
        $pr_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
        $mf_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
        $mh_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
        $mh2_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
        $mh3_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
        $mh4_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
        $u5_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
        $m1_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
        $m2_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
        $mb1_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
        $mb2_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
        $mb3_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
        $mb4_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
        $mb5_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
        $mb6_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
        $mb7_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
        $mb8_sql .= "WHEN $id THEN '$ordinal[25]' " ; // 拼接SQL语句
        $hs_sql .= "WHEN $id THEN '$ordinal[26]' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tp_sql.$e_sql.$mb_sql.$e_sql.$tb_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$tt_sql.$e_sql.$pr_sql.$e_sql.$mf_sql.$e_sql.$mh_sql.$e_sql.$mh2_sql.$e_sql.$mh3_sql.$e_sql.$mh4_sql.$e_sql.$u5_sql.$e_sql.$m1_sql.$e_sql.$m2_sql.$e_sql.$mb1_sql.$e_sql.$mb2_sql.$e_sql.$mb3_sql.$e_sql.$mb4_sql.$e_sql.$mb5_sql.$e_sql.$mb6_sql.$e_sql.$mb7_sql.$e_sql.$mb8_sql.$e_sql.$hs_sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    // echo $sql ;
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
      波膽數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $allcount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
