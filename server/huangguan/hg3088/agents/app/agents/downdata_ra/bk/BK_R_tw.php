<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../../include/config.inc.php");
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
$settime=$refurbishTimeData[0]['udp_bk_tw'];

$m_date=date('Y-m-d');
$allcount=0;
$t_page=10;

$allcount=0;
$langx="zh-cn";
// 更多玩法参数
$gtype = 'BK';
$showtype = 'FT';
$redisObj = new Ciredis();
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$dataArray = array() ; // 把需要的数据重新放在数组里面
$countdataArray = array() ;
if ($flushWay=='ra686'){

    // 首先获取蓝球今日赛事的联赛ID
    $jsonData = $curl->fetch_url($BK_TODAY_API);
    $aData = json_decode($jsonData,true);
    if ($aData['success']){
        $datainfos=[];
        if (count($aData['data']['competitionList'])>0 || !empty($aData['data']['competitionList'])){
            $leagueTmp = getLids686($aData);
            $lid = $leagueTmp['lid'];
            $aLeagueRegion = $leagueTmp['leagueRegion'];
            // 今日赛事的联赛信息存入redis
            $redisObj->setOne('TODAY_BK_LEAGUE_REGION',json_encode($aLeagueRegion));
        }
        else{
            echo ('success 蓝球今日的联赛LID没有数据-');
        }
    }
    else{
        echo ('error 蓝球今日的联赛LID拉取报错-');
    }

    // 然后根据联赛ID获取今日盘口
    $jsonData=$curl->fetch_url($BK_TODAY_SEC_API.$lid);
    $aData = json_decode($jsonData,true);
    $cou = count($aData['data']['seasons']); // 当前联赛数量， 每个联赛下多个matchs(主盘口)，每个主盘口有多个markets(玩法)

    if($cou>0 and $aData['success']){

        $datainfos=[];
        foreach ($aData['data']['seasons'] as $k => $aLeagues){ //联赛
            $isEsport = $aLeagues['esport'];// 是否电竞盘口
            $league = $aLeagues['name']; // 联赛名称
            $seasonId = $aLeagues['seasonId']; // 联赛LID

            foreach ($aLeagues['matches'] as $k2 => $aMatchs){ // 主盘口
                $gid = transGid($aMatchs['matchId']); // 赛事ID, 主盘口gid
                $inplay = $aMatchs['inplay']; // 是否滚球
                $showStat = $aMatchs['showStat'];
                $isNeutral = $aMatchs['neutral']; // 中立场
                $totalMarkets = $aMatchs['totalMarkets'];   // 更多玩法数量
                /*foreach ($aMatchs['markets'] as $k4 => $market) { // 主盘口玩法
                    if ($aMatchs['liveStatus'] == 'HT') {   //半场
                        $aMatchs['clock'] = $aMatchs['liveStatusText'];
                    }

                    $aGames[$gid]['gid'] = $gid;
                    $aGames[$gid]['inplay'] = $inplay;
                    $aGames[$gid]['league'] = $league;
                    $aGames[$gid]['seasonId'] = $seasonId;
                    $aGames[$gid]['startTime'] = $aGames[$gid]['datetime'] = str_replace('T', ' ', $aMatchs['startTime']);  //2021-07-31T06:00:00
                    $aGames[$gid]['RETIMESET'] = $aMatchs['liveStatus'] . '^' . $aMatchs['clock']; //2H^80:09
                    $aGames[$gid]['MORE'] = $aMatchs['totalMarkets'];   // 更多玩法数量

                    $aGames[$gid]['team_h'] = $aMatchs['competitors']['home']['name'];  //TEAM_H
                    $aGames[$gid]['team_c'] = $aMatchs['competitors']['away']['name'];  //TEAM_C


                    // 主盘口玩法转换
                    $aGamesTmp=masterMethodsTrans($aMatchs['markets']);

                    foreach ($aGamesTmp as $gidTmp => $gameTmp){ // 将处理好的玩法合到数据集中
                        foreach ($gameTmp as $k => $v){
                            if($k == 'GID') {continue;}
                            // 让球
                            if($k == 'STRONG') { $aGames[$gid]['strong'] = $v;}
                            if($k == 'RATIO_R') { $aGames[$gid]['ratio'] = $v;}
                            if($k == 'IOR_RH') { $aGames[$gid]['ior_RH'] = $v;}
                            if($k == 'IOR_RC') { $aGames[$gid]['ior_RC'] = $v;}
                            // 得分大小
                            if($k == 'RATIO_OUO') { $aGames[$gid]['ratio_o'] = $v;}
                            if($k == 'RATIO_OUU') { $aGames[$gid]['ratio_u'] = $v;}
                            if($k == 'IOR_OUH') { $aGames[$gid]['ior_OUH'] = $v;}
                            if($k == 'IOR_OUC') { $aGames[$gid]['ior_OUC'] = $v;}
                            // 单双
                            if($k == 'IOR_EOO') { $aGames[$gid]['ior_EOO'] = $v;}
                            if($k == 'IOR_EOE') { $aGames[$gid]['ior_EOE'] = $v;}

                            $aGames[$gid][$k] = $v;
                        }
                    }
                }*/

                // 根据更多玩法获取所有盘口(含主盘口和附属盘口)
                $dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,'',$seasonId,'');
                if(!empty($dataNew['tmp_Obj'])) {
                    foreach($dataNew['tmp_Obj'] as $key => $datainfo) {
                        if($datainfo['gid'] == '') {continue;}
                        $datainfo = getRatioData($datainfo);

                        if($datainfo['gid'] == $gid) {
                            $datainfo['isMaster'] = 'Y'; // 是否主盘口
                            $datainfo['inplay'] = $inplay; // 是否滚球
                            $datainfo['showStat'] = $showStat;
                            $datainfo['MORE'] = $totalMarkets;
                            $datainfo['seasonId'] = $seasonId;
                            $datainfo['neutral'] = $isNeutral==true? 1:0;  // 中立场
                            $datainfo['MIDFIELD'] = $datainfo['midfield'] = $isNeutral==true?" [中]":"";
                            $aGames[$key] = $datainfo;

                            $attachArray['M_Time'] =  $datainfo['M_Time']; // 拼接字段
                            $attachArray['seasonId'] =  $datainfo['seasonId'];
                        }

                        if($datainfo['gid'] != $gid) {    //附属盘口
                            $readyArr = array_merge($datainfo , $attachArray);
                            $aGames[$key] = $readyArr;
                        }
                    }
                }//==============更多玩法END

            }//==============主盘口END


        }//==============联赛END
        $allcount = count($aGames);

        // 数据整理好准备sql入库 或者 更新
        $start = 0;
        $insert_sql = "INSERT INTO `" . DATAHGPREFIX . SPORT_FLUSH_MATCH_TABLE . "` (`MID`,`Type`,`M_Start`,`M_Date`,`M_Time`,`M_League_tw`,`M_League`,`MB_MID`,`TG_MID`,`MB_Team_tw`,`MB_Team`,`TG_Team_tw`,`TG_Team`,`ShowTypeR`,`M_LetB`,`MB_LetB_Rate`,`TG_LetB_Rate`,`MB_Dime`,`TG_Dime`,`TG_Dime_Rate`,`MB_Dime_Rate`,`S_Single_Rate`,`S_Double_Rate`,`M_Type`,`S_Show`,`more`,`ECID`,`LID`,`ISRB`,`Neutral`)VALUES";

        foreach ($aGames as $k => $datainfo){
            if($datainfo['gid'] < 10000) { continue;}
            $GID=$datainfo['gid'];
            $m_date=explode(' ',$datainfo['startTime'])[0];
            $m_time=getMtime($datainfo['startTime']); // 时分秒 转换为 12小时制 时分
            if ($datainfo['showStat']) {
                $m_Type = 1;
            } else {
                $m_Type = 0;
            }
            $checksql = "select MID from ".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE." where `MID` ='$GID'";
            $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
            $check=mysqli_num_rows($checkresult);
            if($check==0){
                if($start == 0) {
                    $insert_sql .= "('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$m_time','$datainfo[league]','$datainfo[league]','','','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[ratio]','$datainfo[ior_RH]','$datainfo[ior_RC]','$datainfo[ratio_o]','$datainfo[ratio_u]','$datainfo[ior_OUH]','$datainfo[ior_OUC]','$datainfo[ior_EOO]','$datainfo[ior_EOE]','$m_Type','1','$datainfo[MORE]','$ECID','$datainfo[seasonId]','$ISRB','$datainfo[neutral]')";

                }else{
                    $insert_sql .= ",('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$m_time','$datainfo[league]','$datainfo[league]','','','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[ratio]','$datainfo[ior_RH]','$datainfo[ior_RC]','$datainfo[ratio_o]','$datainfo[ratio_u]','$datainfo[ior_OUH]','$datainfo[ior_OUC]','$datainfo[ior_EOO]','$datainfo[ior_EOE]','$m_Type','1','$datainfo[MORE]','$ECID','$datainfo[seasonId]','$ISRB','$datainfo[neutral]')";
                }
                $start++;
            }else{
                $dataArray[$datainfo['gid']] = (array("BK", "$datainfo[datetime]", "$m_date", "$m_time", "$datainfo[league]", "$datainfo[gnum_h]", "$datainfo[gnum_c]", "$datainfo[team_h]", "$datainfo[team_c]",   //1-8
                    "$datainfo[strong]", "$datainfo[ratio]", "$datainfo[ior_RH]", "$datainfo[ior_RC]", "$datainfo[ratio_o]", "$datainfo[ratio_u]", "$datainfo[ior_OUH]", "$datainfo[ior_OUC]", //9-16  //IOR_OUH 小 IOR_OUC 大
                    "$datainfo[IOR_MH]", "$datainfo[IOR_MC]", "$datainfo[IOR_MN]",  //17-19
                    "$datainfo[ior_EOO]", "$datainfo[ior_EOE]", // 20-21
                    "$datainfo[ratio_ouho]", "$datainfo[ratio_ouhu]", "$datainfo[ior_OUHO]", "$datainfo[ior_OUHU]", "$datainfo[ratio_ouco]", "$datainfo[ratio_oucu]", "$datainfo[ior_OUCO]", "$datainfo[ior_OUCU]", // 22-29
                    "$datainfo[MORE]", "$datainfo[31]", "$datainfo[32]", "$datainfo[EVENTID]", "$datainfo[34]", "$datainfo[HOT]", "$datainfo[PLAY]", "$datainfo[37]", "$m_Type")); // 把数据放在二维数组里面   //30-38
            }

        }

        if($start>0){ // 有新增数据
//            echo $insert_sql;
            mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
        }

    }
    else{
        echo($flushWay. '没有今日篮球盘口，请稍后');
    }

}
else {
    foreach ($accoutArr as $key => $value) {//在扩展表中获取账号重新刷水
        // 获取篮球今日赛事的联赛ID
        $postdata = array(
            'p' => 'get_league_list_All',
            'ver' => date('Y-m-d-H') . $value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'BK',
            'showtype' => 'ft',
            'FS' => 'N',
            'date' => '0',
            'nocp' => 'N',
        );
        $xml_data = $curl->send_post_data($value['Datasite'] . "/transform.php?ver=" . date('Y-m-d-H') . $value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if ($aData['status'] == 'success') {
            if (count($aData['classifier']['region']) > 0) {
                $lid = getLids($aData)['lid'];
                $aLeagueRegion = getLids($aData)['aLeagueRegion'];
                // 今日赛事的联赛信息存入redis
                $redisObj->setOne('TODAY_BK_LEAGUE_REGION',json_encode($aLeagueRegion, JSON_UNESCAPED_UNICODE));
            } else {
                echo('success 篮球今日赛事没有数据' . $langx);
            }
        } else {
            echo('error 篮球今日赛事没有数据' . $langx);
        }

        //  今日赛事 循环将每一个联赛下面的主盘口都捞出来
        unset($postdata);
        $postdata = array(
            'p' => 'get_game_list',
            'ver' => date('Y-m-d-H') . $value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'bk',
            'showtype' => 'today',
            'rtype' => 'r',
            'ltype' => '4',
            'lid' => $lid,
            'date' => '0',
            'action' => 'clickCoupon',
            'sorttype' => 'T',
        );
        $xml_data = $curl->send_post_data($value['Datasite'] . "/transform.php?ver=" . date('Y-m-d-H') . $value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);

        if (isset($aData['totalDataCount'])) {
            $cou = $aData['totalDataCount'];
        } else {
            $cou = 0;
        }

        if ($cou > 0) {
            $datainfos = [];
            if ($aData['ec']['game']['GID']) {
                $datainfo = $aData['ec']['game'];
                $datainfos[$datainfo['GID']] = $datainfo;
            } else {
                foreach ($aData['ec'] as $k => $v) {
                    $datainfo = $v['game'];
                    $datainfos[$datainfo['GID']] = $datainfo;
                }
            }
            foreach ($datainfos as $k => $datainfo) {

                $GID_MASTER = $datainfo['GID']; // 主盘口GID

                $RETIMESET = $datainfo['RETIMESET']; // 开赛时间
                $teamhMaster = $datainfo['TEAM_H'];
                $datainfo['DATETIME'] = translateDatetime($datainfo['DATETIME']); // 主盘口时间格式转换 //07-19 06:30a
                $datainfo['isMaster'] = 'Y'; // 是否主盘口

                $datainfos[$GID_MASTER] = $datainfo;

                // 根据主盘口的more获取让球的扩展盘口，然后合并到数据集合中
//                if ($datainfo['MORE'] >= 4) {
//                    unset($postdata);
//                    $postdata = array(
//                        'p' => 'get_game_more',
//                        'ver' => date('Y-m-d-H') . $value['Ver'],
//                        'langx' => $langx,
//                        'uid' => $value['Uid'],
//                        'gtype' => 'bk',
//                        'showtype' => 'today',
//                        'ltype' => '4',
//                        'isRB' => 'N',
//                        'lid' => $datainfo['LID'],
//                        'gid' => $GID_MASTER,
//                    );
//                    $xml_data = $curl->send_post_data($value['Datasite'] . "/transform.php?ver=" . date('Y-m-d-H') . $value['Ver'], $postdata);
//                    $aData = xmlToArray($xml_data);
//
//                    if ($aData['code'] == 615) {   //返回成功  code=615
//                        if ($aData['game']['gid']) {  //一条数据
//                            if ($aData['game']['gopen'] == 'N') continue;
//                            unset($aData['game']['@attributes']);
//                            foreach ($aData['game'] as $k2 => $v2) {
//                                //$datainfos[$aData['game']['gid']][strtoupper($k2)]=$v2;
//                                $datainfos[$aData['game']['gid']][$k2] = $v2;
//                            }
//
//                        } else {
//                            foreach ($aData['game'] as $k2 => $v2) {
//
//                                if ($v2['gopen'] == 'N') continue;    //(上半场 下半场 第三节 第四节 N)
//                                unset($v2['@attributes']);
//
//                                // 主数据集合兼容扩展盘口的字段名称
//                                foreach ($v2 as $k3 => $v3) {
//                                    $datainfos[$v2['gid']][$k3] = $v3;
//                                }
//                                /*$datainfos[$v2['gid']]=$v2;
//                                $datainfos[$v2['gid']]['M_Time']=$MTimeM;
//                                $datainfos[$v2['gid']]['RUNNING']=$RuningM;*/
//                            }
//                        }
//                    } else {
//                        echo '更多玩法请求失败，GID_MASTER:' . $GID_MASTER;
//                    }
//                }

            }

        }

        // 重新统计盘口的数量
        $cou = count($datainfos);

        if ($cou > 0) { //可以抓到数据
            $start = 0;
            $insert_sql = "INSERT INTO `" . DATAHGPREFIX . SPORT_FLUSH_MATCH_TABLE . "` (`MID`,`Type`,`M_Start`,`M_Date`,`M_Time`,`M_League_tw`,`M_League`,`MB_MID`,`TG_MID`,`MB_Team_tw`,`MB_Team`,`TG_Team_tw`,`TG_Team`,`ShowTypeR`,`M_LetB`,`MB_LetB_Rate`,`TG_LetB_Rate`,`MB_Dime`,`TG_Dime`,`TG_Dime_Rate`,`MB_Dime_Rate`,`S_Single_Rate`,`S_Double_Rate`,`M_Type`,`S_Show`,`more`)VALUES";

            foreach ($datainfos as $k => $datainfo) {

                if ($datainfo['isMaster'] == 'Y') {
                    $datainfo['datetime'] = $datainfo['DATETIME'];    //主盘口已转化
                    $datainfo['gid'] = $datainfo['GID'];
                    $datainfo['league'] = $datainfo['LEAGUE'];
                    $datainfo['team_h'] = $datainfo['TEAM_H'];
                    $datainfo['team_c'] = $datainfo['TEAM_C'];
                    $datainfo['gnum_h'] = $datainfo['GNUM_H'];
                    $datainfo['gnum_c'] = $datainfo['GNUM_C'];
                    $datainfo['midfield'] = $datainfo['MIDFIELD'];
                    $datainfo['ptype'] = $datainfo['PTYPE'];
                    $datainfo['strong'] = $datainfo['STRONG'];
                    $datainfo['ratio'] = $datainfo['RATIO_R'];
                    $datainfo['ior_RH'] = $datainfo['IOR_RH'];
                    $datainfo['ior_RC'] = $datainfo['IOR_RC'];
                    $datainfo['ratio_o'] = $datainfo['RATIO_OUO'];
                    $datainfo['ratio_u'] = $datainfo['RATIO_OUU'];
                    $datainfo['ior_OUH'] = $datainfo['IOR_OUH'];
                    $datainfo['ior_OUC'] = $datainfo['IOR_OUC'];
                    $datainfo['ior_MH'] = $datainfo['IOR_MH'];
                    $datainfo['ior_MC'] = $datainfo['IOR_MC'];
                    $datainfo['ior_EOO'] = $datainfo['ior_EOO'];
                    $datainfo['ior_EOE'] = $datainfo['ior_EOE'];
                    $datainfo['ratio_ouho'] = $datainfo['RATIO_OUHO'];
                    $datainfo['ratio_ouhu'] = $datainfo['RATIO_OUHU'];
                    $datainfo['ior_OUHO'] = $datainfo['IOR_OUHO'];
                    $datainfo['ior_OUHU'] = $datainfo['IOR_OUHU'];
                    $datainfo['ratio_ouco'] = $datainfo['RATIO_OUCO'];
                    $datainfo['ratio_oucu'] = $datainfo['RATIO_OUCU'];
                    $datainfo['ior_OUCO'] = $datainfo['IOR_OUCO'];
                    $datainfo['ior_OUCU'] = $datainfo['IOR_OUCU'];
                    $datainfo['Eventid'] = $datainfo['EVENTID'];
                    $datainfo['se_now'] = $datainfo['NOWSESSION'];

                } else {

                    if (!empty($datainfo['ratio_o'])) { // ratio_o 同主盘口的全场大球数 RATIO_OUO
                        $datainfo['ratio_o'] = 'O' . $datainfo['ratio_o'];
                    }
                    if (!empty($datainfo['ratio_u'])) { // ratio_u 同主盘口的全场小球数 RATIO_OUU
                        $datainfo['ratio_u'] = 'U' . $datainfo['ratio_u'];
                    }
                    if (!empty($datainfo['ratio_ouho'])) {
                        $datainfo['ratio_ouho'] = 'O' . $datainfo['ratio_ouho'];
                    }
                    if (!empty($datainfo['ratio_ouhu'])) {
                        $datainfo['ratio_ouhu'] = 'U' . $datainfo['ratio_ouhu'];
                    }
                    if (!empty($datainfo['ratio_ouco'])) {
                        $datainfo['ratio_ouco'] = 'O' . $datainfo['ratio_ouco'];
                    }
                    if (!empty($datainfo['ratio_oucu'])) {
                        $datainfo['ratio_oucu'] = 'U' . $datainfo['ratio_oucu'];
                    }

                }

                $timestamp = $DATETIME = $datainfo['datetime'];
                $m_date = explode(' ', $DATETIME)[0];
                $m_time = getMtime($DATETIME);    //08:00a

                //$datainfo[47]=$datainfo['datetime'];
                $LEAGUE = $datainfo['league'];
                $TEAM_H = $datainfo['team_h'];
                $TEAM_C = $datainfo['team_c'];
                //$datainfo[0]=$datainfo['gid'];
                // 将从正网拉取的测试数据过滤掉
                // stripos 查找字符串首次出现的位置（不区分大小写）
                $pos_m = stripos($LEAGUE, 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($LEAGUE, '測試'); // 查找联赛名称是否含有 測試
                $pos_mb = stripos($TEAM_H, 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($TEAM_H, '測試'); // 检查主队名称是否含有 測試
                $pos_tg = stripos($TEAM_C, 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($TEAM_C, '測試'); // 检查客队名称是否含有 測試
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                    $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false) {
                    continue;
                }

                if ($datainfo['RUNNING'] == 'Y') {
                    $m_Type = 1;
                } else {
                    $m_Type = 0;
                }

                $checksql = "select MID from `" . DATAHGPREFIX . SPORT_FLUSH_MATCH_TABLE . "` where `MID` ='$datainfo[gid]'";
                $checkresult = mysqli_query($dbCenterSlaveDbLink, $checksql);
                $check = mysqli_num_rows($checkresult);
                $countdataArray[] = ($check);
                if (DATA_CENTER_SWITCH) {
                    $dataCenterChildren = explode(',', DATA_CENTER_CHILDREN);
                    for ($m = 0; $m < count($dataCenterChildren); $m++) {
                        $redisObj->pushMessage($dataCenterChildren[$m] . '_BK_R_List', $datainfo[gid]);
                    }
                }
                if ($check == 0) {
                    if ($start == 0) {
                        //$insert_sql .= ",('$GID','BK','$timestamp','$m_date','$m_time','$datainfo[2]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[5]','$datainfo[6]','$datainfo[6]','$datainfo[7]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[17]','$datainfo[18]','$m_Type','1','$datainfo[30]')";
                        //MID,Type,开场时间,比赛日期,比赛时间,M_League_tw联赛繁体名称,M_League联赛简体名称,MB_MID,TG_MID,MB_Team_tw主队繁体名称,MB_Team主队简体名称,TG_Team_tw客队繁体名称,TG_Team客队简体名称,ShowTypeR全场H主队让球,M_LetB让球数,MB_LetB_Rate主队让球赔率,TG_LetB_Rate客队让球赔率,MB_Dime主队全场大小 O大U小,TG_Dime客队全场大小 O大U小,TG_Dime_Rate客队全场赔率,MB_Dime_Rate主队全场赔率,S_Single_Rate全场比分之和的单号赔率,S_Double_Rate全场比分之和的双号赔率,M_Type 0 无滚球 1 有滚球',S_Show,more更多比赛的数目,
                        $insert_sql .= "('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$m_time','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[ratio]','$datainfo[ior_RH]','$datainfo[ior_RC]','$datainfo[ratio_o]','$datainfo[ratio_u]','$datainfo[ior_OUH]','$datainfo[ior_OUC]','$datainfo[ior_EOO]','$datainfo[ior_EOE]','$m_Type','1','$datainfo[MORE]')";
                    } else {
                        $insert_sql .= ",('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$m_time','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[ratio]','$datainfo[ior_RH]','$datainfo[ior_RC]','$datainfo[ratio_o]','$datainfo[ratio_u]','$datainfo[ior_OUH]','$datainfo[ior_OUC]','$datainfo[ior_EOO]','$datainfo[ior_EOE]','$m_Type','1','$datainfo[MORE]')";
                    }

                    $start++;
                } else {
                    /*$dataArray[$datainfo[0]]=(array($check,$timestamp,$m_date,$m_time,$datainfo[2],$datainfo[3],$datainfo[4],$datainfo[5],$datainfo[6],$datainfo[7],
                        $datainfo[8],$datainfo[9],$datainfo[10],$datainfo[11],$datainfo[12],$datainfo[13],$datainfo[14],$datainfo[15],$datainfo[16],$datainfo[17],
                        $datainfo[18],$datainfo[20],$datainfo[21],$datainfo[22], $datainfo[23],$datainfo[24],$datainfo[25],$datainfo[26],$datainfo[27],$datainfo[28],
                        $datainfo[29],$datainfo[30], $datainfo[31],$datainfo[32],$datainfo[33],$datainfo[36],$datainfo[37],$m_Type)); // 把数据放在二维数组里面*/
                    $dataArray[$datainfo['gid']] = (array("$check", "$datainfo[datetime]", "$m_date", "$m_time", "$datainfo[league]", "$datainfo[gnum_h]", "$datainfo[gnum_c]", "$datainfo[team_h]", "$datainfo[team_c]",
                        "$datainfo[strong]", "$datainfo[ratio]", "$datainfo[ior_RH]", "$datainfo[ior_RC]", "$datainfo[ratio_o]", "$datainfo[ratio_u]", "$datainfo[ior_OUH]", "$datainfo[ior_OUC]",
                        "$IOR_MH", "$IOR_MC", "$datainfo[17]",
                        "$datainfo[ior_EOO]", "$datainfo[ior_EOE]",
                        "$datainfo[ratio_ouho]", "$datainfo[ratio_ouhu]", "$datainfo[ior_OUHO]", "$datainfo[ior_OUHU]", "$datainfo[ratio_ouco]", "$datainfo[ratio_oucu]", "$datainfo[ior_OUCO]", "$datainfo[ior_OUCU]",
                        "$datainfo[MORE]", "$datainfo[30]", "$datainfo[31]", "$datainfo[EVENTID]", "$datainfo[33]", "$datainfo[HOT]", "$datainfo[PLAY]", "$datainfo[37]", "$m_Type")); // 把数据放在二维数组里面
                }

                $allcount++;
            }

            if ($start > 0) { // 有新增数据
                //echo $insert_sql;
                mysqli_query($dbCenterMasterDbLink, $insert_sql) or die ("操作失敗!");
            }

        } else {
            break;
        }

        if ($allcount > 0) break;
    }
}
$redisObj->setOne("BK_Today_Num",(int)$allcount);
// $insertaccount =0 ; //用于判断是否有新数据插入
// $before_updateaccount = array_count_values($countdataArray)[0] ; // 统计需要插入数据的数量
$updateaccount =0 ; //用于判断是否有更新数据

if($allcount>0 and count($dataArray)>0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "; // update
    $ty_sql .="Type = CASE MID " ;
    $m_sql .="M_Start = CASE MID " ;
    $t_sql .="M_Date = CASE MID ";
    $l_sql .="M_Time = CASE MID ";
    $lm_sql .="M_League = CASE MID ";
    $mid_sql .="MB_MID = CASE MID ";
    $tid_sql .="TG_MID = CASE MID ";
    $mtw_sql .="MB_Team = CASE MID ";
    $ttw_sql .="TG_Team = CASE MID ";
    $stp_sql .="ShowTypeR = CASE MID ";
    $mr_sql .="M_LetB = CASE MID ";
    $mrr_sql .="MB_LetB_Rate = CASE MID ";
    $trr_sql .="TG_LetB_Rate = CASE MID ";
    $mbd_sql .="MB_Dime = CASE MID ";
    $tgd_sql .="TG_Dime = CASE MID ";
    $tgr_sql .="TG_Dime_Rate = CASE MID ";
    $mbr_sql .="MB_Dime_Rate = CASE MID ";
    $sdr_sql .="MB_Win_Rate = CASE MID ";
    $sthr_sql .="TG_Win_Rate = CASE MID ";
    $mlh_sql .="M_Flat_Rate = CASE MID ";
    $mlrh_sql .="S_Single_Rate = CASE MID ";
    $tlrh_sql .="S_Double_Rate = CASE MID ";
    $mdh_sql .="MB_Dime_H = CASE MID ";
    $tdh_sql .="MB_Dime_S_H = CASE MID ";
    $tdrh_sql .="MB_Dime_Rate_H = CASE MID ";
    $rbshow_sql .="MB_Dime_Rate_S_H = CASE MID ";
    $tdha_sql .="TG_Dime_H = CASE MID ";
    $tdsh_sql .="TG_Dime_S_H = CASE MID ";
    $tdrsh_sql .="TG_Dime_Rate_H = CASE MID ";
    $tgdrsh_sql .="TG_Dime_Rate_S_H = CASE MID ";
    $more_sql .="more = CASE MID ";
    $mwrh_sql .="MB_Win_Rate_H = CASE MID ";
    $twrh_sql .="TG_Win_Rate_H = CASE MID ";
    $eid_sql .="Eventid = CASE MID ";
    $emlrh_sql .="M_Flat_Rate_H = CASE MID ";
    $hot_sql .="Hot = CASE MID ";
    $play_sql .="Play = CASE MID ";
    $sshow_sql .="S_Show = CASE MID ";
    $mtyp_sql .="M_Type = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
            $ty_sql .= "WHEN $id THEN 'BK' " ; // 拼接SQL语句
            $m_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
            $t_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
            $l_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
            $lm_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
            $mid_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
            $tid_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
            $mtw_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
            $ttw_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
            $stp_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
            $mr_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
            $mrr_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
            $trr_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
            $mbd_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
            $tgd_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
            $tgr_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
            $mbr_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
            $sdr_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
            $sthr_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
            $mlh_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
            $mlrh_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
            $tlrh_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
            $mdh_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
            $tdh_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
            $tdrh_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
            $rbshow_sql .= "WHEN $id THEN '$ordinal[25]' " ; // 拼接SQL语句
            $tdha_sql .= "WHEN $id THEN '$ordinal[26]' " ; // 拼接SQL语句
            $tdsh_sql .= "WHEN $id THEN '$ordinal[27]' " ; // 拼接SQL语句
            $tdrsh_sql .= "WHEN $id THEN '$ordinal[28]' " ; // 拼接SQL语句
            $tgdrsh_sql .= "WHEN $id THEN '$ordinal[29]' " ; // 拼接SQL语句
            $more_sql .= "WHEN $id THEN '$ordinal[30]' " ; // 拼接SQL语句
            $mwrh_sql .= "WHEN $id THEN '$ordinal[31]' " ; // 拼接SQL语句
            $twrh_sql .= "WHEN $id THEN '$ordinal[32]' " ; // 拼接SQL语句
            $eid_sql .= "WHEN $id THEN '$ordinal[33]' " ; // 拼接SQL语句
            $emlrh_sql .= "WHEN $id THEN '$ordinal[34]' " ; // 拼接SQL语句
            $hot_sql .= "WHEN $id THEN '$ordinal[35]' " ; // 拼接SQL语句
            $play_sql .= "WHEN $id THEN '$ordinal[36]' " ; // 拼接SQL语句
            $sshow_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
            $mtyp_sql .= "WHEN $id THEN '$ordinal[38]' " ; // 拼接SQL语句

            $updateaccount++;


    }

    if($updateaccount>0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mid_sql.$e_sql.$tid_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.$tdh_sql.$e_sql.$tdrh_sql.$e_sql.$rbshow_sql.$e_sql.$tdha_sql.$e_sql.$tdsh_sql.$e_sql.$tdrsh_sql.$e_sql.$tgdrsh_sql.$e_sql.$mwrh_sql.$e_sql.$more_sql.$e_sql.$twrh_sql.$e_sql.$eid_sql.$e_sql.$emlrh_sql.$e_sql.$hot_sql.$e_sql.$play_sql.$e_sql.$sshow_sql.$e_sql.$mtyp_sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
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
var limit="30" ;
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
      單式數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $allcount; ?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
