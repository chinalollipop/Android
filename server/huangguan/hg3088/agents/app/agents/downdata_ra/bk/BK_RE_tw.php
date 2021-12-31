<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
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
$settime=$refurbishTimeData[0]['udp_bk_re'];

$redisObj = new Ciredis();

//获取刷水账号
$cou=0;
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$langx='zh-cn';
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$dataArray= array() ; // 把需要的数据重新放在数组里面
$countdataArray = array() ;
if (SPORT_FLUSH_WAY=='ra686'){

    $jsonData = $curl->fetch_url("" . $flushDoamin . "/api/fn/matches/matchStatus/Live/closeSoccer/0011100100/lang/zh/marketGroup/am/oddsType/HONG_KONG/orderBy/league/page/1/pageSize/30/sId/2/source/a/timezone/-4");
    $aData = json_decode($jsonData,true);
    $cou= $aData['pageData']['totalRecords'];

    if($cou>0){

        $datainfos=[];
        foreach ($aData['data']['iot'] as $k => $aLeagues){

            foreach ($aLeagues['match'] as $k2 => $aMatchs){
                $gid = $aMatchs['info']['matchId'];  // 篮球的盘口  拿主盘口的ID，球队大小放到一起展示
                $isNeutral = $aMatchs['info']['isNeutral']; // 中立场
                $isEsport = $aMatchs['info']['isEsport']; // 是否电竞盘口
                $liveStatus = strtoupper($aMatchs['info']['liveStatus']); // 当前第几节  Q1
                foreach ($aMatchs['events'] as $k3 => $v3){

                    $aGames[$gid]['gid'] = $gid;
                    $aGames[$gid]['league'] = $aLeagues['info']['name'];
                    $aGames[$gid]['startTime'] = $aGames[$gid]['DATETIME'] = str_replace('T', ' ', $aMatchs['info']['startTime']);
                    $aGames[$gid]['RETIMESET'] = $aMatchs['info']['liveStatus'].'^'.$aMatchs['info']['clock']; //2H^80:09
                    $aGames[$gid]['TIMER'] = $aMatchs['info']['clock'];
                    $aGames[$gid]['more'] = $aMatchs['info']['totalMarkets'];
                    if ($v3['description']=='角球'){
                        $aGames[$gid]['team_h'] = $v3['competitors']['home']['name'].' '.$v3['description'].'数';
                    }else{
                        $aGames[$gid]['team_h'] = $v3['competitors']['home']['name'];
                    }
                    $aGames[$gid]['team_c'] = $v3['competitors']['away']['name'];
                    // 比分
                    $aGames[$gid]['SCORE_H'] = $v3['score']['homeScore']; // 主队比分
                    $aGames[$gid]['SCORE_C'] = $v3['score']['awayScore']; // 客队比分
                    $aGames[$gid]['redcard_h'] = $v3['score']['redcard_h']; // 主队罚球数
                    $aGames[$gid]['redcard_c'] = $v3['score']['redcard_c']; // 客队罚球数
                    $aGames[$gid]['isNeutral'] = $isNeutral; // 中立场
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口
                    $aGames[$gid]['liveStatus'] = $liveStatus; // 当前第几节  Q1

                    foreach ($aMatchs['events'][$k3]['markets'] as $k4 => $market){

                        // 全场独赢
                        if ($k4=='ml_ml_FT_ML'){
                            $aGames[$gid]['IOR_RMH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['IOR_RMC'] = $market['outcomes']['a']['odds'];
                        }
                        // 半场独赢
                        if ($k4=='1x21st_1x21st_HT_1X2'){

                            $aGames[$gid]['IOR_HRMH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['IOR_HRMC'] = $market['outcomes']['a']['odds'];
                            $aGames[$gid]['IOR_HRMN'] = $market['outcomes']['d']['odds'];
                        }
                        // 全场让球。让球数小于0  主队让 H， 让球数大于0 客队让 C
                        if (strpos($k4,'ah_ah_')!==false){
                            $RATIO_RE = $market['ename'];
                            if (strlen($RATIO_RE)>1){
                                $jiajian=substr($RATIO_RE , 0 , 1);
                                $aGames[$gid]['RATIO_RE']=substr($RATIO_RE,1);;
                                if ($jiajian=='-'){
                                    $aGames[$gid]['strong']='H';
                                }else{
                                    $aGames[$gid]['strong']='C';
                                }
                            }
                            else{
                                $aGames[$gid]['ratio_re']=$RATIO_RE;
                                $aGames[$gid]['strong']='H';
                            }
                            $aGames[$gid]['ior_REH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['ior_REC'] = $market['outcomes']['a']['odds'];
                        }
                        // 让球上半场。让球数小于0  主队让 H， 让球数大于0 客队让 C
                        if (strpos($k4,'ah1st_ah1st_')!==false){
                            $RATIO_HRE = $market['ename'];
                            if (strlen($RATIO_HRE)>1){
                                $jiajian=substr($RATIO_HRE , 0 , 1);
                                $aGames[$gid]['RATIO_HRE']=substr($RATIO_HRE,1);
                                if ($jiajian=='-'){
                                    $aGames[$gid]['hstrong']='H';
                                }else{
                                    $aGames[$gid]['hstrong']='C';
                                }
                            }
                            else{
                                $aGames[$gid]['HALF_RATIO_RE']=$RATIO_HRE;
                                $aGames[$gid]['hstrong']='H';
                            }
                            $aGames[$gid]['HALF_IOR_REH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['HALF_IOR_REC'] = $market['outcomes']['a']['odds'];
                        }
                        // 全场大小
                        if (strpos($k4,'ou_ou_')!==false){
                            $aGames[$gid]['ratio_rouo']='O'.$market['ename'];
                            $aGames[$gid]['ratio_rouu']='U'.$market['ename'];
                            $aGames[$gid]['ior_ROUH'] = $market['outcomes']['un']['odds'];
                            $aGames[$gid]['ior_ROUC'] = $market['outcomes']['ov']['odds'];
                        }
                    }
                }
            }

        }

        $cou = count($aGames);

        // 数据整理好准备sql入库 或者 更新
        $start = 0;
        $insert_sql = "INSERT INTO ".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."(MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,ShowTypeRB,RB_Show,ECID,LID,ISRB)VALUES";

        foreach ($aGames as $k => $datainfo){
            $GID=$datainfo['gid'];
            $m_date=explode(' ',$datainfo['startTime'])[0];
            $m_time=getMtime($datainfo['startTime']); // 时分秒 转换为 12小时制 时分
            $checksql = "select MID from ".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE." where `MID` ='$GID'";
            $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
            $check=mysqli_num_rows($checkresult);
            if($check==0){
                if($start == 0) {
                    $insert_sql .= "('$GID','BK','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','','','$datainfo[STRONG]','1','$ECID','$LID','$ISRB')" ;

                }else{
                    $insert_sql .= ",('$GID','BK','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','','','$datainfo[STRONG]','1','$ECID','$LID','$ISRB')" ;
                }
                $start++;
            }else{
                $dataArray[$GID]=(array($check,$datainfo[startTime],$m_date,$m_time,
                    $datainfo['LEAGUE'],$datainfo['TEAM_H'],$datainfo['TEAM_C'],
                    $datainfo['STRONG'],$datainfo['RATIO_RE'],$datainfo['IOR_REH'],$datainfo['IOR_REC'],$datainfo['IOR_ROUH'],$datainfo['IOR_ROUC'],$datainfo['RATIO_ROUO'],$datainfo['RATIO_ROUU'],$datainfo['Eventid'],$datainfo['liveStatus'])); // 把数据放在二维数组里面
            }

        }

        if($start>0){ // 有新增数据
//            echo $insert_sql;
            mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
        }

    }
    else{
        exit('没有滚球盘口，请稍后');
    }

}
else{

foreach($accoutArr as $key=>$value){ //在扩展表中获取账号重新刷水
//	$curl->set_referrer("".$value['Datasite']);
//	$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/BK_browse/body_var.php?rtype=re_all&uid=".$value['Uid']."&langx=zh-tw&mtype=4");
//	$matches = get_content_deal($html_data);
//	$cou=sizeof($matches);
    $postdata = array(
        'p' => 'get_game_list',
        'ver' => date('Y-m-d-H').$value['Ver'],
        'langx' => $langx,
        'uid' => $value['Uid'],
        'gtype' => 'bk',
        'showtype' => 'live',
        'rtype' => 'rb',
        'ltype' => '4',
        'sorttype' => 'T',
    );
    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
    $aData = xmlToArray($xml_data);
    if(isset($aData['totalDataCount'])){
        $cou= $aData['totalDataCount'];
    }else{
        $cou=0;
    }

    // 根据主盘口的lid  gid获取附属盘口
    if($cou>0){
        $datainfos=[];
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
        foreach ($datainfos as $k => $datainfo) {
            unset($datainfo['@attributes']);
            $GIDM=$datainfo['GID']; // 主盘口GID
            $datainfos[$GIDM]=$datainfo;
            $datainfos[$GIDM]['isMaster']='Y'; // 是否主盘口
            $MTimeM=explode(' ',$datainfo['DATETIME'])[1];  //08:00a
            $datainfos[$GIDM]['M_Time']=$MTimeM;

            // 根据主盘口的more获取让球的扩展盘口，然后合并到数据集合中
            if ($datainfo['MORE']>=4) {
                unset($postdata);
                $postdata = array(
                    'p' => 'get_game_more',
                    'ver' => date('Y-m-d-H').$value['Ver'],
                    'langx' => $langx,
                    'uid' => $value['Uid'],
                    'gtype' => 'bk',
                    'showtype' => 'live',
                    'ltype' => '4',
                    'isRB' => 'Y',
                    'lid' => $datainfo['LID'],
                    'gid' => $GIDM,
                );
                $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                $aData = xmlToArray($xml_data);

                if($aData['game']['gid']){  //一条数据
                    unset($aData['game']['@attributes']);
                    foreach ($aData['game'] as $k2 => $v2){
                        $datainfos[$aData['game']['gid']][$k2]=$v2;
                    }
                    $datainfos[$aData['game']['gid']]['M_Time']=$MTimeM;
                }else{
                    foreach ($aData['game'] as $k2 => $v2){
                        unset($v2['@attributes']);
                        if ($k2==0){//$k2=0,gid和主盘口GID相同
                            // 主数据集合兼容扩展盘口的字段名称
                            foreach ($v2 as $k3 => $v3){
                                $datainfos[$v2['gid']][$k3]=$v3;
                            }
                        }else{
                            if($v2['gopen'] == 'Y') {   //拉取 gopen = Y, (上半场 下半场 第三节 第四节 N)
                                $datainfos[$v2['gid']]=$v2;
                                $datainfos[$v2['gid']]['M_Time']=$MTimeM;
                            }
                        }
                    }
                }
            }

        }
//        @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
//        @error_log(json_encode($datainfos,JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');

    }

    // 重新统计盘口的数量
    $cou = count($datainfos);

	if($cou>0){//可以抓到数据
        $start = 0;
        $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,M_League_tw,M_League,MB_MID,TG_MID,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,ShowTypeRB,nowSession)VALUES";

        $i=0;
        $datainfo=[];
        foreach ($datainfos as $k => $datainfo){

            if ($datainfo['isMaster']=='Y'){

                $datainfo['datetime']=translateDatetime($datainfo['DATETIME']);
                $datainfo['gid']=$datainfo['GID'];
                $datainfo['league']=$datainfo['LEAGUE'];
                $datainfo['team_h']=$datainfo['TEAM_H'];
                $datainfo['team_c']=$datainfo['TEAM_C'];
                $datainfo['midfield']=$datainfo['MIDFIELD'];
                $datainfo['ptype']=$datainfo['PTYPE'];
                $datainfo['strong']=$datainfo['STRONG'];
                $datainfo['ratio_re']=$datainfo['RATIO_RE'];
                $datainfo['ior_REH']=$datainfo['IOR_REH']>0?round_num($datainfo['IOR_REH']):'';
                $datainfo['ior_REC']=$datainfo['IOR_REC']>0?round_num($datainfo['IOR_REC']):'';
                $datainfo['ratio_rouo']=$datainfo['RATIO_ROUO'];
                $datainfo['ratio_rouu']=$datainfo['RATIO_ROUU'];
                $datainfo['ior_ROUH']=$datainfo['IOR_ROUH']>0?round_num($datainfo['IOR_ROUH']):'';
                $datainfo['ior_ROUC']=$datainfo['IOR_ROUC']>0?round_num($datainfo['IOR_ROUC']):'';
                $datainfo['ratio_rouho']=$datainfo['RATIO_ROUHO'];
                $datainfo['ratio_rouhu']=$datainfo['RATIO_ROUHU'];
                $datainfo['ior_ROUHO']=$datainfo['IOR_ROUHO']>0?round_num($datainfo['IOR_ROUHO']):'';
                $datainfo['ior_ROUHU']=$datainfo['IOR_ROUHU']>0?round_num($datainfo['IOR_ROUHU']):'';
                $datainfo['ratio_rouco']=$datainfo['RATIO_ROUCO'];
                $datainfo['ratio_roucu']=$datainfo['RATIO_ROUCU'];
                $datainfo['ior_ROUCO']=$datainfo['IOR_ROUCO']>0?round_num($datainfo['IOR_ROUCO']):'';
                $datainfo['ior_ROUCU']=$datainfo['IOR_ROUCU']>0?round_num($datainfo['IOR_ROUCU']):'';
                $datainfo['Eventid']=$datainfo['EVENTID'];
                $datainfo['se_now']=$datainfo['NOWSESSION'];
            }else{
                $datainfo['ior_REH']=$datainfo['ior_REH']>0?round_num($datainfo['ior_REH']):'';
                $datainfo['ior_REC']=$datainfo['ior_REC']>0?round_num($datainfo['ior_REC']):'';
                $datainfo['ior_ROUH']=$datainfo['ior_ROUH']>0?round_num($datainfo['ior_ROUH']):'';
                $datainfo['ior_ROUC']=$datainfo['ior_ROUC']>0?round_num($datainfo['ior_ROUC']):'';
                $datainfo['ior_ROUHO']=$datainfo['ior_ROUHO']>0?round_num($datainfo['ior_ROUHO']):'';
                $datainfo['ior_ROUHU']=$datainfo['ior_ROUHU']>0?round_num($datainfo['ior_ROUHU']):'';
                $datainfo['ior_ROUCO']=$datainfo['ior_ROUCO']>0?round_num($datainfo['ior_ROUCO']):'';
                $datainfo['ior_ROUCU']=$datainfo['ior_ROUCU']>0?round_num($datainfo['ior_ROUCU']):'';

                if (!empty($datainfo['ratio_rouo'])){
                    $datainfo['ratio_rouo']='O'.$datainfo['ratio_rouo'];
                }
                if (!empty($datainfo['ratio_rouu'])){
                    $datainfo['ratio_rouu']='U'.$datainfo['ratio_rouu'];
                }
                if (!empty($datainfo['ratio_rouho'])){
                    $datainfo['ratio_rouho']='O'.$datainfo['ratio_rouho'];
                }
                if (!empty($datainfo['ratio_rouhu'])){
                    $datainfo['ratio_rouhu']='U'.$datainfo['ratio_rouhu'];
                }
                if (!empty($datainfo['ratio_rouco'])){
                    $datainfo['ratio_rouco']='O'.$datainfo['ratio_rouco'];
                }
                if (!empty($datainfo['ratio_roucu'])){
                    $datainfo['ratio_roucu']='U'.$datainfo['ratio_roucu'];
                }
            }
            $datainfo[47]=$datainfo['datetime'];
            $datainfo[2]=$datainfo['league'];
            $datainfo[5]=$datainfo['team_h'];
            $datainfo[6]=$datainfo['team_c'];
            $datainfo[0]=$datainfo['gid'];

            $date=explode(' ',$datainfo[47]);
            $m_date=$date[0];

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


			$checksql = "select MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$datainfo[0]'";
			$checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
			$check=mysqli_num_rows($checkresult);
            $countdataArray[] =($check) ;

            if(DATA_CENTER_SWITCH){
                $dataCenterChildren = explode(',',DATA_CENTER_CHILDREN);
                for($m=0;$m<count($dataCenterChildren);$m++){
                    $redisObj->pushMessage($dataCenterChildren[$m].'_BK_RE_List',$datainfo[0]);
                }
            }

			if($check==0){
                if($start == 0) {
//                    $insert_sql .="('$datainfo[0]','BK','$timestamp','$m_date','$datainfo[1]','$datainfo[2]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[5]','$datainfo[6]','$datainfo[6]','$datainfo[7]','$datainfo[52]')" ;
                    $insert_sql .="('$datainfo[0]','BK','$datainfo[datetime]','$m_date','$datainfo[M_Time]','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[se_now]')" ;
                }else{
                    $insert_sql .=",('$datainfo[0]','BK','$datainfo[datetime]','$m_date','$datainfo[M_Time]','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[se_now]')" ;
                }
                $start++;
			}else{
                $dataArray[$datainfo[0]]=(array($check,$datainfo[datetime],$m_date,$datainfo[M_Time],$datainfo['league'],$datainfo['team_h'],$datainfo['team_c'],$datainfo['strong'],$datainfo['ratio_re'],$datainfo['ior_REH'],$datainfo['ior_REC'],$datainfo['ior_ROUH'],$datainfo['ior_ROUC'],$datainfo['ratio_rouo'],$datainfo['ratio_rouu'],$datainfo['Eventid'],$datainfo['se_now'])); // 把数据放在二维数组里面
			}
		}
        if($start>0){ // 有新增数据
            mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
        }

		break;
	}
}
}
$redisObj->setOne("BK_Running_Num",(int)$cou);

//$insertaccount =0 ; //用于判断是否有新数据插入
// $before_updateaccount = array_count_values($countdataArray)[0] ; // 统计需要插入数据的数量
$updateaccount =0 ; //用于判断是否有更新数据

if($cou>0 and count($dataArray)>0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "; // update
    $ty_sql .="Type = CASE MID " ;
    $m_sql .="M_Start = CASE MID " ;
    $t_sql .="M_Date = CASE MID ";
    $l_sql .="M_Time = CASE MID ";
    $lm_sql .="M_League = CASE MID ";
    $mtw_sql .="MB_Team = CASE MID ";
    $ttw_sql .="TG_Team = CASE MID ";
    $stp_sql .="ShowTypeRB = CASE MID ";
    $mr_sql .="M_LetB_RB = CASE MID ";
    $mrr_sql .="MB_LetB_Rate_RB = CASE MID ";
    $trr_sql .="TG_LetB_Rate_RB = CASE MID ";
    $mbd_sql .="MB_Dime_RB = CASE MID ";
    $tgd_sql .="TG_Dime_RB = CASE MID ";
    $tgr_sql .="TG_Dime_Rate_RB = CASE MID ";
    $mbr_sql .="MB_Dime_Rate_RB = CASE MID ";
    $sdr_sql .="Eventid = CASE MID ";
    $sthr_sql .="Hot = CASE MID ";
    $mlh_sql .="Play = CASE MID ";
    $mlrh_sql .="RB_Show = CASE MID ";
    $tlrh_sql .="S_Show = CASE MID ";
    $mbball_sql .="MB_ball = CASE MID ";
    $tginball_sql .="TG_ball = CASE MID ";
//    $mbinballhr_sql .="MB_ball_HR = CASE MID ";
//    $tginballhr_sql .="TG_ball_HR = CASE MID ";
    $score_sql .="Score = CASE MID ";
    $checked_sql .="Checked = CASE MID ";
    $scoresource_sql .="Score_Source = CASE MID ";
    $nowsession_sql .="nowSession = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
            $ty_sql .= "WHEN $id THEN 'BK' " ; // 拼接SQL语句
            $m_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
            $t_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
            $l_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
            $lm_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
            $mtw_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
            $ttw_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
            $stp_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
            $mr_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
            $mrr_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
            $trr_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
            $mbd_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
            $tgd_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
            $tgr_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
            $mbr_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
            $sdr_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
            $sthr_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
            $mlh_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
            $mlrh_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
            $tlrh_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
            $mbball_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
            $tgball_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
//            $mbinballhr_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
//            $tginballhr_sql .= "WHEN $id THEN '' " ; // 拼接SQL语句
            $score_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
            $checked_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
            $scoresource_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
            $nowsession_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
            $updateaccount++;

    }

    if($updateaccount>0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$nowsession_sql ;
        $sql .= $e_sql.$mbball_sql.$e_sql.$tgball_sql.$e_sql.$score_sql.$e_sql.$checked_sql.$e_sql.$scoresource_sql;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
//        echo $sql .'<br>';
        mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");
    }

}


function get_content_deal($html_data){
	$a = array(
		"if(self == top)",
		"<script>",
		"</script>",
		"new Array()",
		"parent.GameBK=new Array();",
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
var limit="<?php echo $settime; ?>";

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
      走地數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou;?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
