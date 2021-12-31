<?php

/*早盘足球的注单*/
if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
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

$redisObj = new Ciredis();

$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_ft_r'];

$allcount=0;
$langx="zh-cn";
$redisObj = new Ciredis();
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt"); 
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$allcount=0;
$dataArray= array() ; // 把需要的数据重新放在数组里面
$countdataArray = array() ;
if (SPORT_FLUSH_WAY=='ra686'){

    // 首先获取足球早盘的联赛ID
    $jsonData = $curl->fetch_url($FT_FUTURE_API);
    //echo $FT_FUTURE_API;
    //print_r($jsonData);
    $aData = json_decode($jsonData,true);
    if ($aData['success']){
        $datainfos=[];
        if (count($aData['data']['competitionList'])>0 || !empty($aData['data']['competitionList']) ){
            $leagueTmp = getLids686($aData);
            $lid = $leagueTmp['lid'];
            $aLeagueRegion = $leagueTmp['leagueRegion'];
            // 今日赛事的联赛信息存入redis
            $redisObj->setOne('FUTURE_FT_LEAGUE_REGION',json_encode($aLeagueRegion));
            $redisObj->setOne('FUTURE_FT_LID',$lid);
        }
        else{
            echo ('success 足球早盘的联赛LID没有数据-');
        }
    }
    else{
        echo ('error 足球早盘联赛LID拉取报错-');
    }

    // 然后根据联赛ID获取足球早盘
    $alid=explode(',',$lid);
    $alid2=array_chunk($alid,6); //每次最多请求6个联赛
    $aSeasons=[];
    $cou=0;
    foreach ($alid2 as $i => $v){
        $l = implode(',',$v);
        $jsonData=$curl->fetch_url($FT_FUTURE_SEC_API.$l); //请求6个联赛的盘口数据
        $aData = json_decode($jsonData,true);
        foreach ($aData['data']['seasons'] as $k => $aLeagues){
            $aSeasons[]=$aLeagues;
            $cou+=count($aLeagues['matches']); //所有联赛的盘口数量
        }
    }
    //echo $cou.'==';
    if($cou>0){
        foreach ($aSeasons as $k => $aLeagues){

            $isEsport = $aLeagues['esport']; // 是否电竞盘口
            $league = $aLeagues['name']; // 联赛名称
            foreach ($aLeagues['matches'] as $k2 => $aMatchs){
                $gid = $aMatchs['matchId']; // 赛事 ID

                // 标签数据
                $aObtSelections[$gid] = $aMatchs['obtSelections'];

                // 请求更多附属盘口
                /*$moreJsonData = $curl->fetch_url($FT_MORESPORT_TODAY_API.$gid);
                $moreaData = json_decode($moreJsonData,true);

                $aMatchs = $moreaData['data']['match'];
                $isEsport = $aMatchs['esport'];// 是否电竞盘口
                $league = $aMatchs['seasonName']; // 联赛名称*/

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
                $aGames[$gid]['Neutral'] = $aMatchs['neutral']; // 中立场

                // 主盘口玩法转换
                $aGamesTmp=masterMethodsTrans($aMatchs['markets']);

                foreach ($aGamesTmp as $gidTmp => $gameTmp){ // 将处理好的附属盘口合到数据集中
                    foreach ($gameTmp as $k => $v){
                        $aGames[$gid][$k] = $v;
                    }
                }

            }

        }

        // 将足球早盘的标签数据存入到redis，方便前端显示
        $redisObj->setOne('FUTURE_FT_OBTSELECTIONS',json_encode($aObtSelections));

        // 数据整理好准备sql入库 或者 更新
        $start = 0;
        $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,M_Type,S_Show,more,ECID,LID,ISRB,Neutral)VALUES";

        foreach ($aGames as $k => $datainfo){
            if($datainfo['GID'] < 10000) { continue;}
            $GID=$datainfo['GID'];
            $m_date=explode(' ',$datainfo['startTime'])[0];
            $m_time=getMtime($datainfo['startTime']); // 时分秒 转换为 12小时制 时分
            $checksql = "select MID from ".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE." where `MID` ='$GID'";
            $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
            $check=mysqli_num_rows($checkresult);

            // 将从正网拉取的测试数据过滤掉
            // stripos 查找字符串首次出现的位置（不区分大小写）
            $pos_m = stripos($datainfo[LEAGUE], 'test'); // 查找联赛名称是否含有 test
            $pos_m_tw = stripos($datainfo[LEAGUE], '測試'); // 查找联赛名称是否含有 測試
            $pos_mb = stripos($datainfo[TEAM_H], 'test'); // 检查主队名称是否含有 test
            $pos_mb_tw = stripos($datainfo[TEAM_H], '測試'); // 检查主队名称是否含有 測試
            $pos_tg = stripos($datainfo[TEAM_C], 'test'); // 检查客队名称是否含有 test
            $pos_tg_tw = stripos($datainfo[TEAM_C], '測試'); // 检查客队名称是否含有 測試
            if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                continue;
            }

            if ($datainfo['RUNNING']=='Y'){
                $m_Type=1;
            }else{
                $m_Type=0;
            }
            // echo $m_date.'--'.$datainfo[0].'<br>';

            $checksql = "select MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$GID'";
            $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
            $check=mysqli_num_rows($checkresult);
            $countdataArray[] =($check) ;
            if(DATA_CENTER_SWITCH){
                $dataCenterChildren = explode(',',DATA_CENTER_CHILDREN);
                for($m=0;$m<count($dataCenterChildren);$m++){
                    $redisObj->pushMessage($dataCenterChildren[$m].'_FT_R_List',$GID);
                }
            }
            if($check==0){
                if($start == 0) {
                    $insert_sql .= "('$GID','FU','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','$GNUM_H','$GNUM_C','$m_Type','1','$datainfo[MORE]','$ECID','$LID','$ISRB','$datainfo[Neutral]')";
                }else {
                    $insert_sql .= ",('$GID','FU','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','$GNUM_H','$GNUM_C','$m_Type','1','$datainfo[MORE]','$ECID','$LID','$ISRB','$datainfo[Neutral]')";
                }
                $start++;
                //mysqli_query($dbMasterLink,$sql) or die ("操作失敗!!!");
            }else{
                $dataArray[$GID]=(array($check,$datainfo[startTime],$m_date,$m_time,$datainfo[LEAGUE],$GNUM_H,$GNUM_C,$datainfo[TEAM_H],$datainfo[TEAM_C],$datainfo[STRONG],$datainfo[RATIO_R],$datainfo[IOR_RH],$datainfo[IOR_RC],
                    $datainfo[RATIO_OUO],$datainfo[RATIO_OUU],$datainfo[IOR_OUH],$datainfo[IOR_OUC],$datainfo[IOR_MH],$datainfo[IOR_MC],$datainfo[IOR_MN],$datainfo[IOR_EOO],$datainfo[IOR_EOE],
                    $datainfo[HSTRONG],$datainfo[RATIO_HR],$datainfo[IOR_HRH],$datainfo[IOR_HRC],$datainfo[RATIO_HOUO],$datainfo[RATIO_HOUU],$datainfo[IOR_HOUH],$datainfo[IOR_HOUC],$datainfo[IOR_HMH],$datainfo[IOR_HMC],$datainfo[IOR_HMN],
                    $datainfo[MORE],$datainfo[35],$PLAY,$datainfo[37],$m_Type,$ECID,$LID,$ISRB,$datainfo[Neutral])); // 把数据放在二维数组里面
            }
            $allcount++;

        }

        if($start>0){ // 有新增数据
//            echo $insert_sql;
            mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
        }

    }
    else{
        echo ('没有早盘足球盘口，请稍后');
    }
}
else{
    foreach($accoutArr as $key=>$value){
//	for($page_no=0;$page_no<15;$page_no++){
//			$curl->set_referrer("".$value['Datasite']."/app/member/FT_future/index.php?rtype=r&uid=".$value['Uid']."&langx=".$langx."&mtype=3");
//			$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_future/body_var.php?rtype=r&uid=".$value['Uid']."&langx=".$langx."&g_date=ALL&mtype=3&page_no=".$page_no);
			//echo "".$value['Datasite']."/app/member/FT_future/body_var.php?rtype=r&uid=".$value['Uid']."&langx=".$langx."&g_date=ALL&mtype=3&page_no=".$page_no."<br />";
//			$matches = get_content_deal($html_data);


        // 获取足球早盘的联赛ID
        $postdata = array(
            'p' => 'get_league_list_All',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'FT',
            'showtype' => 'fu',
            'FS' => 'N',
            'date' => 'all',
            'nocp' => 'N',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if ($aData['status']=='success'){
            if (count($aData['classifier']['region'])>0){
                $lid = getLids($aData)['lid'];
                $aLeagueRegion = getLids($aData)['aLeagueRegion'];
                // 今日赛事的联赛信息存入redis
                $redisObj->setOne('FUTURE_FT_LEAGUE_REGION',json_encode($aLeagueRegion, JSON_UNESCAPED_UNICODE));
            }
            else{
                echo ('success 没有足球早盘数据'.$langx);
            }
        }
        else{
            echo ('error 没有足球早盘数据'.$langx);
        }

        // 获取早盘的赛事
        unset($postdata);
        $postdata = array(
            'p' => 'get_game_list',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'ft',
            'showtype' => 'early',
            'rtype' => 'r',
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

        if ($cou>0){

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
            foreach ($datainfos as $k => $datainfo){

                // 标签数据
                $aObtSelections[$k]=[];
                $aObtSelectionsK=[];
                if ($datainfo['R_COUNT']>0){ array_push($aObtSelections[$k],'handicaps'); }
                if ($datainfo['OU_COUNT']>0){ array_push($aObtSelections[$k],'goalsou'); }
                if ($datainfo['CN_COUNT']>0){  array_push($aObtSelections[$k],'corners'); }
                if ($datainfo['RN_COUNT']>0){ array_push($aObtSelections[$k],'bookings'); }
                if ($datainfo['WI_COUNT']>0){ array_push($aObtSelections[$k],'toqualify'); }
                if ($datainfo['ET_COUNT']>0){  array_push($aObtSelections[$k],'ET_COUNT'); }
                if ($datainfo['PK_COUNT']>0){  array_push($aObtSelections[$k],'PK_COUNT'); }

                //$datainfo=$v['game'];
                $GID_MSTER=$datainfo['GID'];
                if ($datainfo['ECID']==''){
                    $datainfo['ECID']=str_replace('ec','',$v['@attributes']['id']);
                }

                $RETIMESET=$datainfo['RETIMESET']; // 开赛时间
                $SCORE_H=$datainfo['SCORE_H']; // 比分
                $SCORE_C=$datainfo['SCORE_C'];
                $redcard_h=$datainfo['REDCARD_H']; // 主队罚球数
                $redcard_c=$datainfo['REDCARD_C']; // 客队罚球数
                $datainfo['DATETIME'] = translateDatetime($datainfo['DATETIME']); // 主盘口时间格式转换 //07-19 06:30a
                $teamhMaster=$datainfo['TEAM_H'];
                $IOR_HOUH=$datainfo['IOR_HOUH']; // 半场大小 客队半场赔率 TG_Dime_Rate_H
                $IOR_HOUC=$datainfo['IOR_HOUC']; // 半场大小 主队半场赔率 MB_Dime_Rate_H
                $Neutral=0;
                $datainfos[$k]['Neutral']=$datainfo['Neutral']=$Neutral;
                if (strpos($datainfo['TEAM_H'],'[中]')!==false){
                    $teamhMaster=$datainfos[$k]['TEAM_H']=trim(str_replace('[中]','',$datainfo['TEAM_H']));
                    $Neutral=1; // 是否中立场 1 是 0 不是
                    $datainfos[$k]['Neutral']=$datainfo['Neutral']=$Neutral;
                }
                $datainfos[$GID_MSTER]=$datainfo;

                // 根据主盘口的ecid获取让球的更多玩法获取附属盘口，然后合并到数据集合中
//                if ($datainfo['MORE']>=6){ // 更多玩法
//                    unset($postdata);
//                    $postdata = array(
//                        'p' => 'get_game_more',
//                        'ver' => date('Y-m-d-H').$value['Ver'],
//                        'langx' => $langx,
//                        'uid' => $value['Uid'],
//                        'gtype' => 'ft',
//                        'showtype' => 'early',
//                        'ltype' => '4',
//                        'isRB' => 'N',
////                    'lid' => 'Y',
//                        'ecid' => $datainfo['ECID'],
//                    );
//                    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
//                    $aData = xmlToArray($xml_data);
//                    /*      id="gid4907945" master="Y" mode="CN"  ptype="-角球数"
//                            id="gid4907947" master="N" mode="CN"  ptype="-角球数"
//                            id="gid4907949" master="Y" mode="RN" ptype="-罚牌数"
//                            id="gid4907951" master="N" mode="RN" ptype="-罚牌数"
//                            id="gid4907953" master="Y" mode="WI" ptype="-冠军"
//                            id="gid4908067" master="Y" mode="ET" ptype="-加时赛"
//                            id="gid4908069" master="N" mode="ET" ptype="-加时赛"
//                            id="gid4908071" master="Y mode="PKR" ptype="-点球(让球盘)"
//                            id="gid4908073" master="Y mode="PKOU" ptype="-点球(大小盘)"
//                            id="gid4908075" master="N mode="PKOU" ptype="-点球(大小盘)"
//                    */
//                    if ($aData['code']==617){
//                        if ($aData['game']['gid']){
//                            if ($aData['game']['gopen']=='N') continue;
//                            unset($aData['game']['@attributes']);
//                            foreach ($aData['game'] as $k2=> $v2){
//                                $datainfos[$aData['game']['gid']][strtoupper($k2)]=$v2;
//                            }
//                            $datainfos[$aData['game']['gid']]['RETIMESET']=$RETIMESET;
//                            $datainfos[$aData['game']['gid']]['SCORE_H']=$SCORE_H;
//                            $datainfos[$aData['game']['gid']]['SCORE_C']=$SCORE_C;
//                            $datainfos[$aData['game']['gid']]['TEAM_H']=$teamhMaster;
//                            $datainfos[$aData['game']['gid']]['ECID']=$datainfo['ECID'];    //同一盘口一个ECID
//                            $datainfos[$aData['game']['gid']]['LID']=$datainfo['LID'];
//
//                            if($aData['game']['gid'] == $GID_MSTER) { //主盘口半场大小赔率
//                                $datainfos[$aData['game']['gid']]['IOR_HOUH']=$IOR_HOUH;
//                                $datainfos[$aData['game']['gid']]['IOR_HOUC']=$IOR_HOUC;
//                            }
//                            $datainfos[$aData['game']['gid']]['Neutral']=$Neutral;
//                        }
//                        else{
//                            foreach ($aData['game'] as $k2 => $v2){
//                                if ($v2['gopen']=='N') continue;
//                                unset($v2['@attributes']);
//
//                                foreach ($v2 as $k3 => $v3){
//                                    $datainfos[$v2['gid']][strtoupper($k3)] = $v3;
//                                }
//                                $datainfos[$v2['gid']]['RETIMESET'] = $RETIMESET;
//                                $datainfos[$v2['gid']]['ECID']=$datainfo['ECID'];    //同一盘口一个ECID
//                                $datainfos[$v2['gid']]['LID']=$datainfo['LID'];
//
//                                if($aData['game']['gid'] == $GID_MSTER) { //主盘口半场大小赔率
//                                    $datainfos[$v2['gid']]['IOR_HOUH']=$IOR_HOUH;
//                                    $datainfos[$v2['gid']]['IOR_HOUC']=$IOR_HOUC;
//                                }
//                                // -角球数，-罚牌数，-点球(让球盘)，-点球(大小盘)，-C组冠军，-C组排尾队伍等等
//                                // ptype有值的情况下，比分和主队名称 不需要从主盘口拿值
//                                if (strlen(trim($v2['ptype']))==0){
//                                    $datainfos[$v2['gid']]['SCORE_H'] = $SCORE_H;
//                                    $datainfos[$v2['gid']]['SCORE_C'] = $SCORE_C;
//                                    $datainfos[$v2['gid']]['TEAM_H'] = $teamhMaster;
//                                }
//                                $datainfos[$v2['gid']]['Neutral']=$Neutral;
//                            }
//                        }
//                    }else{
//                        echo '更多玩法请求失败，EID'.$datainfo['ECID'];
//                    }
//
//                }

                /*// 根据主盘口的ecid获取让球的扩展盘口，然后合并到数据集合中
                if ($datainfo['R_COUNT']>=2){ // 2个让球盘的时候再去请求
                    unset($postdata);
                    $postdata = array(
                        'p' => 'get_game_OBT',
                        'ver' => date('Y-m-d-H').$value['Ver'],
                        'langx' => $langx,
                        'uid' => $value['Uid'],
                        'gtype' => 'ft',
                        'showtype' => 'early',
                        'isEarly' => 'N',
                        'model' => 'R',
                        'ecid' => $datainfo['ECID'],
                        'ltype' => '4',
                    );
                    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                    $aXmldata = explode('<?xml', $xml_data);
                    $xml_data='';
                    $xml_data='<?xml'.$aXmldata[1];
                    $aData = xmlToArray($xml_data);
                    foreach ($aData['ec']['game'] as $k2 => $v2){
                        unset($v2['@attributes']);
                        foreach ($v2 as $k3 => $v3){
                            $datainfos[$v2['GID']][$k3] = $v3;
                        }
                    }
                }

                // 根据主盘口的ecid获取得分大小的扩展盘口，然后合并到数据集合中
                if ($datainfo['OU_COUNT']>=2){ // 2个大小盘的时候再去请求
                    unset($postdata);
                    $postdata = array(
                        'p' => 'get_game_OBT',
                        'ver' => date('Y-m-d-H').$value['Ver'],
                        'langx' => $langx,
                        'uid' => $value['Uid'],
                        'gtype' => 'ft',
                        'showtype' => 'early',
                        'isEarly' => 'N',
                        'model' => 'OU',
                        'ecid' => $datainfo['ECID'],
                        'ltype' => '4',
                    );
                    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                    $aXmldata = explode('<?xml', $xml_data);
                    $xml_data='';
                    $xml_data='<?xml'.$aXmldata[1];
                    $aData = xmlToArray($xml_data);
                    foreach ($aData['ec']['game'] as $k2 => $v2){
                        unset($v2['@attributes']);
                        foreach ($v2 as $k3 => $v3){
                            $datainfos[$v2['GID']][$k3] = $v3;
                        }
                    }
                }
                // 根据主盘口的ecid获取角球的扩展盘口，然后合并到数据集合中
                if ($datainfo['CN_COUNT']>=1){ // 角球
                    unset($postdata);
                    $postdata = array(
                        'p' => 'get_game_OBT',
                        'ver' => date('Y-m-d-H').$value['Ver'],
                        'langx' => $langx,
                        'uid' => $value['Uid'],
                        'gtype' => 'ft',
                        'showtype' => 'early',
                        'isEarly' => 'N',
                        'model' => 'CN',
                        'ecid' => $datainfo['ECID'],
                        'ltype' => '4',
                    );
                    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                    $aXmldata = explode('<?xml', $xml_data);
                    $xml_data='';
                    $xml_data='<?xml'.$aXmldata[1];
                    $aData = xmlToArray($xml_data);
                    $datainfos[$aData['ec']['game']['GID']]=$aData['ec']['game'];
                }*/
            }

            // 将足球早盘的标签数据存入到redis，方便前端显示
            $redisObj->setOne('FUTURE_FT_OBTSELECTIONS',json_encode($aObtSelections));
        }

			if($cou>0){//可以抓到数据
                $start = 0;
                $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,M_Type,S_Show,more,ECID,LID,ISRB,Neutral)VALUES";
                foreach ($datainfos as $k => $datainfo){

					if (!empty($datainfo)){

                        $LEAGUE=$datainfo['LEAGUE'];
                        $GNUM_H=$datainfo['GNUM_H'];
                        $GNUM_C=$datainfo['GNUM_C'];
                        $TEAM_H=trim(str_replace('[中]','',$datainfo['TEAM_H'])); // 插入数据库的时候 去掉中，方便自动结算
                        $TEAM_C=$datainfo['TEAM_C'];
                        $STRONG=$datainfo['STRONG'];
                        $RATIO_R=((!empty($datainfo['RATIO'])) || $datainfo['RATIO']==='0')?$datainfo['RATIO']:$datainfo['RATIO_R'];    // 让球数  全场 主 让球数  M_LetB   主盘口特有字段 RATIO_R  附属盘口字段 RATIO
                        $IOR_RH=$datainfo['IOR_RH'];    // 全场主队让球赔率  MB_LetB_Rate
                        $IOR_RC=$datainfo['IOR_RC'];    // 全场客队让球赔率  TG_LetB_Rate
                        $RATIO_OUO=!empty($datainfo['RATIO_O'])?$datainfo['RATIO_O']:$datainfo['RATIO_OUO'];  //主队全场大小  O 大  U 小  MB_Dime    主盘口特有字段 RATIO_OUO  通用字段 RATIO_O
                        $RATIO_OUU=!empty($datainfo['RATIO_U'])?$datainfo['RATIO_U']:$datainfo['RATIO_OUU'];  //客队全场大小  O 大  U 小  TG_Dime    主盘口特有字段 RATIO_OUU  通用字段 RATIO_U
                        if (strpos($RATIO_OUO,'O')!==false){}
                        else{
                            if (!empty($RATIO_OUO)){
                                $RATIO_OUO='O'.$RATIO_OUO;
                            }
                        }
                        if (strpos($RATIO_OUU,'U')!==false){}
                        else{
                            if (!empty($RATIO_OUU)){
                                $RATIO_OUU='U'.$RATIO_OUU;
                            }
                        }
                        $IOR_OUH=$datainfo['IOR_OUH'];  //客队全场赔率 TG_Dime_Rate
                        $IOR_OUC=$datainfo['IOR_OUC'];  //主队全场赔率 MB_Dime_Rate
                        $HSTRONG=$datainfo['HSTRONG'];  //滚球半场 H 主队让球 其他的是客队让球  ShowTypeHR
                        $RATIO_HR=isset($datainfo['HRATIO'])?$datainfo['HRATIO']:$datainfo['RATIO_HR'];  //半场让球数 M_LetB_H   主盘字段  RATIO_HR  主盘附属盘字段 HRATIO
                        $IOR_HRH=$datainfo['IOR_HRH'];  //半场主队让球赔率  MB_LetB_Rate_H
                        $IOR_HRC=$datainfo['IOR_HRC'];  //半场客队让球赔率 TG_LetB_Rate_H
                        $RATIO_HOUO=!empty($datainfo['RATIO_HO'])?$datainfo['RATIO_HO']:$datainfo['RATIO_HOUO'];    //主队半场得分  O 大 MB_Dime_H     主特盘字段 RATIO_HOUO  附属盘字段 RATIO_HO
                        $RATIO_HOUU=!empty($datainfo['RATIO_HU'])?$datainfo['RATIO_HU']:$datainfo['RATIO_HOUU'];    //客队半场得分  O 大 TG_Dime_H     主特盘字段 RATIO_HOUU  附属盘字段 RATIO_HU
                        if (strpos($RATIO_HOUO,'O')!==false){}
                        else {
                            if (!empty($RATIO_HOUO)) {
                                $RATIO_HOUO='O'.$RATIO_HOUO;
                            }
                        }
                        if (strpos($RATIO_HOUU,'U')!==false){}
                        else{
                            if (!empty($RATIO_HOUU)){
                                $RATIO_HOUU='U'.$RATIO_HOUU;
                            }
                        }
                        $IOR_HOUH=$datainfo['IOR_HOUH']; // 半场大小  客队半场赔率 TG_Dime_Rate_H
                        $IOR_HOUC=$datainfo['IOR_HOUC']; // 半场大小  主队半场赔率 MB_Dime_Rate_H
                        $SCORE_H=$datainfo['SCORE_H'];
                        $SCORE_C=$datainfo['SCORE_C'];
                        // MB_Card
                        // TG_Card
                        $REDCARD_H=$datainfo['REDCARD_H'];// MB_Red
                        $REDCARD_C=$datainfo['REDCARD_C'];// TG_Red
                        $IOR_MH=$datainfo['IOR_MH'];    //MB_Win_Rate  主队独赢赔率
                        $IOR_MC=$datainfo['IOR_MC'];    //TG_Win_Rate  客队独赢赔率
                        $IOR_MN=$datainfo['IOR_MN'];    //M_Flat_Rate  全场和的赔率
                        $IOR_HMH=$datainfo['IOR_HMH'];  //MB_Win_Rate_H  半场主队独赢赔率
                        $IOR_HMC=$datainfo['IOR_HMC'];  //TG_Win_Rate_H  半场客队独赢赔率
                        $IOR_HMN=$datainfo['IOR_HMN'];  //M_Flat_Rate_H  半场和的赔率
                        $IOR_EOO=$datainfo['IOR_EOO']>0?$datainfo['IOR_EOO']:'';    //S_Single_Rate  全场比分之和的单号赔率
                        $IOR_EOE=$datainfo['IOR_EOE']>0?$datainfo['IOR_EOE']:'';    //S_Double_Rate  全场比分之和的双号赔率
                        $EVENTID=$datainfo['EVENTID'];
                        $HOT=$datainfo['HOT'];
                        $PLAY=$datainfo['PLAY'];
                        $timestamp=$DATETIME=$datainfo['DATETIME'];
                        $GID=$datainfo['GID'];
                        $MT_GTYPE=$datainfo['MT_GTYPE'];

                        $MORE_COUNT=$datainfo['MORE_COUNT'];
                        $ECID=$datainfo['ECID'];    //同一盘口一个ECID
                        $LID=$datainfo['LID'];
                        $ISRB='N'; // 返回的数据中没有这个字段，滚球赋值为 Y
                        $Neutral=$datainfo['Neutral'];

                        // 将从正网拉取的测试数据过滤掉
                        // stripos 查找字符串首次出现的位置（不区分大小写）
                        $pos_m = stripos($LEAGUE, 'test'); // 查找联赛名称是否含有 test
                        $pos_m_tw = stripos($LEAGUE, '測試'); // 查找联赛名称是否含有 測試
                        $pos_mb = stripos($TEAM_H, 'test'); // 检查主队名称是否含有 test
                        $pos_mb_tw = stripos($TEAM_H, '測試'); // 检查主队名称是否含有 測試
                        $pos_tg = stripos($TEAM_C, 'test'); // 检查客队名称是否含有 test
                        $pos_tg_tw = stripos($TEAM_C, '測試'); // 检查客队名称是否含有 測試
                        if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                            $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                            continue;
                        }

                        $m_date=explode(' ', $DATETIME)[0];
                        $m_time=getMtime($DATETIME);    //08:00a
					
                        //修正刷水数据，部署数据刷水得到以后被降赔率了
                        $datainfo[9] = $datainfo[9]==0?$datainfo[9]:$datainfo[9]+0.01;
                        $datainfo[10] = $datainfo[10]==0?$datainfo[10]:$datainfo[10]+0.01;
                        $datainfo[13] = $datainfo[13]==0?$datainfo[13]:$datainfo[13]+0.01;
                        $datainfo[14] = $datainfo[14]==0?$datainfo[14]:$datainfo[14]+0.01;
                        $datainfo[20] = $datainfo[20]==0?$datainfo[20]:$datainfo[20]+0.01;
                        $datainfo[21] = $datainfo[21]==0?$datainfo[21]:$datainfo[21]+0.01;
                        $datainfo[25] = $datainfo[25]==0?$datainfo[25]:$datainfo[25]+0.01;
                        $datainfo[26] = $datainfo[26]==0?$datainfo[26]:$datainfo[26]+0.01;
                        $datainfo[29] = $datainfo[29]==0?$datainfo[29]:$datainfo[29]+0.01;
                        $datainfo[30] = $datainfo[30]==0?$datainfo[30]:$datainfo[30]+0.01;

                        /* $mDate=explode(' ',strtoupper($DATETIME));
                        $Y=date('Y');
                        $m_date=$Y."-".$mDate[0];
                        $m_time=strtolower($mDate[1]);
                        $hhmmstr=explode(":",$m_time);
                        $hh=$hhmmstr[0];
                        //echo $datainfo[0];exit;
                        $ap=substr($m_time,strlen($m_time)-1,1);
                        if ($ap=='p' and $hh<>12){
                            $hh+=12;
                        }
                        $timestamp = $m_date." ".$hh.":".substr($hhmmstr[1],0,strlen($hhmmstr[1])-1).":00";*/
                        if ($datainfo['RUNNING']=='Y'){
                            $m_Type=1;
                        }else{
                            $m_Type=0;
                        }
                        $checksql = "select MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` =$GID";
                        $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
                        $check=mysqli_num_rows($checkresult);
                        $countdataArray[] =($check) ;
                        if(DATA_CENTER_SWITCH){
                            $dataCenterChildren = explode(',',DATA_CENTER_CHILDREN);
                            for($m=0;$m<count($dataCenterChildren);$m++){
                                $redisObj->pushMessage($dataCenterChildren[$m].'_FU_R_List',$GID);
                            }
                        }
                        if($check==0){
                            if($start == 0) {
                                $insert_sql .= "('$GID','FU','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$m_Type','1','$MORE_COUNT','$ECID','$LID','$ISRB','$Neutral')" ;
                            }else{
                                $insert_sql .= ",('$GID','FU','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$m_Type','1','$MORE_COUNT','$ECID','$LID','$ISRB','$Neutral')" ;
                            }
                            $start++;
                        }else{
                            $dataArray[$GID]=(array($check,$timestamp,$m_date,$m_time,$LEAGUE,$GNUM_H,$GNUM_C,$TEAM_H,$TEAM_C,
                                        $STRONG,$RATIO_R,$IOR_RH,$IOR_RC,$RATIO_OUO,$RATIO_OUU,$IOR_OUH,$IOR_OUC,$IOR_MH,$IOR_MC,$IOR_MN,
                                        $IOR_EOO,$IOR_EOE,
                                        $HSTRONG,$RATIO_HR,$IOR_HRH,$IOR_HRC,$RATIO_HOUO,$RATIO_HOUU,$IOR_HOUH,$IOR_HOUC,$IOR_HMH,$IOR_HMC,$IOR_HMN,
                                        $MORE_COUNT,$datainfo[35],$PLAY,$datainfo[37],$m_Type,$ECID,$LID,$ISRB,$Neutral)); // 把数据放在二维数组里面
                        }
                        $allcount++;
					}else{
						continue;
					}
				}
                if($start>0){ // 有新增数据
                    mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
                }
			}else{
				break;
			} 
//		}
	if($allcount>0)	break;
}
}
$redisObj->setOne("FT_Future_Num",(int)$allcount);
//var_dump($dataArray);
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
    $mbw_sql .="MB_Win_Rate = CASE MID ";
    $tgw_sql .="TG_Win_Rate = CASE MID ";
    $mfr_sql .="M_Flat_Rate = CASE MID ";
    $ssr_sql .="S_Single_Rate = CASE MID ";
    $sdr_sql .="S_Double_Rate = CASE MID ";
    $sthr_sql .="ShowTypeHR = CASE MID ";
    $mlh_sql .="M_LetB_H = CASE MID ";
    $mlrh_sql .="MB_LetB_Rate_H = CASE MID ";
    $tlrh_sql .="TG_LetB_Rate_H = CASE MID ";
    $mdh_sql .="MB_Dime_H = CASE MID ";
    $tdh_sql .="TG_Dime_H = CASE MID ";
    $tdrh_sql .="TG_Dime_Rate_H = CASE MID ";
    $mdrh_sql .="MB_Dime_Rate_H = CASE MID ";
    $mwrh_sql .="MB_Win_Rate_H = CASE MID ";
    $twrh_sql .="TG_Win_Rate_H = CASE MID ";
    $mfrh_sql .="M_Flat_Rate_H = CASE MID ";
    $more_sql .="more = CASE MID ";
    $eid_sql .="Eventid = CASE MID ";
    $hot_sql .="Hot = CASE MID ";
    $play_sql .="Play = CASE MID ";
    $sshow_sql .="S_Show = CASE MID ";
    $mtp_sql .="M_Type = CASE MID ";
    $ecid_sql .="ECID = CASE MID ";
    $lid_sql .="LID = CASE MID ";
    $isrb_sql .="ISRB = CASE MID ";
    $Neutral_sql .="Neutral = CASE MID ";

    foreach ($dataArray as $id => $ordinal) {

            $ty_sql .= "WHEN $id THEN 'FU' " ; // 拼接SQL语句
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
            $mbw_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
            $tgw_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
            $mfr_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
            $ssr_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
            $sdr_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
            $sthr_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
            $mlh_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
            $mlrh_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
            $tlrh_sql .= "WHEN $id THEN '$ordinal[25]' " ; // 拼接SQL语句
            $mdh_sql .= "WHEN $id THEN '$ordinal[26]' " ; // 拼接SQL语句
            $tdh_sql .= "WHEN $id THEN '$ordinal[27]' " ; // 拼接SQL语句
            $tdrh_sql .= "WHEN $id THEN '$ordinal[28]' " ; // 拼接SQL语句
            $mdrh_sql .= "WHEN $id THEN '$ordinal[29]' " ; // 拼接SQL语句
            $mwrh_sql .= "WHEN $id THEN '$ordinal[30]' " ; // 拼接SQL语句
            $twrh_sql .= "WHEN $id THEN '$ordinal[31]' " ; // 拼接SQL语句
            $mfrh_sql .= "WHEN $id THEN '$ordinal[32]' " ; // 拼接SQL语句
            $more_sql .= "WHEN $id THEN '$ordinal[33]' " ; // 拼接SQL语句
            $eid_sql .= "WHEN $id THEN '$ordinal[34]' " ; // 拼接SQL语句
            $hot_sql .= "WHEN $id THEN '$ordinal[35]' " ; // 拼接SQL语句
            $play_sql .= "WHEN $id THEN '$ordinal[36]' " ; // 拼接SQL语句
            $sshow_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
            $mtp_sql .= "WHEN $id THEN '$ordinal[37]' " ; // 拼接SQL语句
            $ecid_sql .= "WHEN $id THEN '$ordinal[38]' " ; // 拼接SQL语句
            $lid_sql .= "WHEN $id THEN '$ordinal[39]' " ; // 拼接SQL语句
            $isrb_sql .= "WHEN $id THEN '$ordinal[40]' " ; // 拼接SQL语句
            $Neutral_sql .= "WHEN $id THEN '$ordinal[41]' " ; // 拼接SQL语句
            $updateaccount++;

    }

    if($updateaccount>0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mid_sql.$e_sql.$tid_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$mbw_sql.$e_sql.$tgw_sql.$e_sql.$mfr_sql.$e_sql.$ssr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.$tdh_sql.$e_sql.$tdrh_sql.$e_sql.$mdrh_sql.$e_sql.$mwrh_sql.$e_sql.$twrh_sql.$e_sql.$mfrh_sql.$e_sql.$more_sql.$e_sql.$eid_sql.$e_sql.$hot_sql.$e_sql.$play_sql.$e_sql.$sshow_sql.$e_sql.$mtp_sql.$e_sql.$ecid_sql.$e_sql.$lid_sql.$e_sql.$isrb_sql.$e_sql.$Neutral_sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
        // echo $sql ;
        mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");
    }

}


function get_content_deal($html_data){
	$a = array(
	"if(self == top)",
	"<script>",
	"</script>",
	"]=new Array()",
	"parent.GameFU=new Array();",
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
      單式數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $allcount?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
