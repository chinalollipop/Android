<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

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
$settime=$refurbishTimeData[0]['udp_ft_re'];

$redisObj = new Ciredis();

$allcount=0;
$accoutArr=getFlushWaterAccount();

$curl = new Curl_HTTP_Client();
$langx="zh-cn";
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$dataArray=$pushMidArr=array() ; // 把需要的数据重新放在数组里面
$countdataArray = array() ;
if (SPORT_FLUSH_WAY=='ra686'){

    $jsonData = $curl->fetch_url("" . $flushDoamin . "/api/fn/matches/matchStatus/Live/closeSoccer/0011100100/lang/zh/marketGroup/am/oddsType/HONG_KONG/orderBy/league/page/1/pageSize/30/sId/1/source/a/timezone/-4");
    $aData = json_decode($jsonData,true);
    $cou= $aData['pageData']['totalRecords'];

    if($cou>0){

        $datainfos=[];
        foreach ($aData['data']['iot'] as $k => $aLeagues){

            foreach ($aLeagues['match'] as $k2 => $aMatchs){
                $isNeutral = $aMatchs['info']['isNeutral']; // 中立场
                $isEsport = $aMatchs['info']['isEsport']; // 是否电竞盘口
                if ($aMatchs['info']['liveStatus']=='HT'){
                    $aMatchs['info']['clock']=$aMatchs['info']['liveStatusText'];
                }
                foreach ($aMatchs['events'] as $k3 => $v3){

                    $gid = $v3['fixtureId'];
                    $aGames[$gid]['GID'] = $gid;
                    $aGames[$gid]['LEAGUE'] = $aLeagues['info']['name'];
                    $aGames[$gid]['startTime'] = $aGames[$gid]['DATETIME'] = str_replace('T', ' ', $aMatchs['info']['startTime']);
                    $aGames[$gid]['RETIMESET'] = $aMatchs['info']['liveStatus'].'^'.$aMatchs['info']['clock']; //2H^80:09  HT~半场
                    $aGames[$gid]['TIMER'] = $aMatchs['info']['clock'];
                    $aGames[$gid]['MORE'] = $aMatchs['info']['totalMarkets'];
                    if ($v3['description']=='角球'){
                        $aGames[$gid]['TEAM_H'] = $v3['competitors']['home']['name'].' '.$v3['description'].'数';
                    }else{
                        $aGames[$gid]['TEAM_H'] = $v3['competitors']['home']['name'];
                    }
                    $aGames[$gid]['TEAM_C'] = $v3['competitors']['away']['name'];
                    // 比分
                    $aGames[$gid]['SCORE_H'] = $v3['score']['homeScore']; // 主队比分
                    $aGames[$gid]['SCORE_C'] = $v3['score']['awayScore']; // 客队比分
                    $aGames[$gid]['REDCARD_H'] = $v3['score']['hRedCard']; // 主队红牌数
                    $aGames[$gid]['REDCARD_C'] = $v3['score']['aRedCard']; // 客队红牌数
                    $aGames[$gid]['isNeutral'] = $isNeutral; // 中立场
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口

                    foreach ($aMatchs['events'][$k3]['markets'] as $k4 => $market){

                        // 全场独赢
                        if ($k4=='1x2_1x2_FT_1X2'){
                            $aGames[$gid]['IOR_RMH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['IOR_RMC'] = $market['outcomes']['a']['odds'];
                            $aGames[$gid]['IOR_RMN'] = $market['outcomes']['d']['odds'];
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
                                    $aGames[$gid]['STRONG']='H';
                                }else{
                                    $aGames[$gid]['STRONG']='C';
                                }
                            }
                            else{
                                $aGames[$gid]['RATIO_RE']=$RATIO_RE;
                                $aGames[$gid]['STRONG']='H';
                            }
                            $aGames[$gid]['IOR_REH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['IOR_REC'] = $market['outcomes']['a']['odds'];
                        }
                        // 让球上半场。让球数小于0  主队让 H， 让球数大于0 客队让 C
                        if (strpos($k4,'ah1st_ah1st_')!==false){
                            $RATIO_HRE = $market['ename'];
                            if (strlen($RATIO_HRE)>1){
                                $jiajian=substr($RATIO_HRE , 0 , 1);
                                $aGames[$gid]['RATIO_HRE']=substr($RATIO_HRE,1);
                                if ($jiajian=='-'){
                                    $aGames[$gid]['HSTRONG']='H';
                                }else{
                                    $aGames[$gid]['HSTRONG']='C';
                                }
                            }
                            else{
                                $aGames[$gid]['RATIO_HRE']=$RATIO_HRE;
                                $aGames[$gid]['HSTRONG']='H';
                            }
                            $aGames[$gid]['IOR_HREH'] = $market['outcomes']['h']['odds'];
                            $aGames[$gid]['IOR_HREC'] = $market['outcomes']['a']['odds'];
                        }
                        // 全场大小
                        if (strpos($k4,'ou_ou_')!==false){
                            $aGames[$gid]['RATIO_ROUO']='O'.$market['ename'];
                            $aGames[$gid]['RATIO_ROUU']='U'.$market['ename'];
                            $aGames[$gid]['IOR_ROUH'] = $market['outcomes']['un']['odds'];
                            $aGames[$gid]['IOR_ROUC'] = $market['outcomes']['ov']['odds'];
                        }
                        // 进球大小上半场
                        if (strpos($k4,'ou1st_ou1st_')!==false){
                            $aGames[$gid]['RATIO_HROUO']='O'.$market['ename'];
                            $aGames[$gid]['RATIO_HROUU']='U'.$market['ename'];
                            $aGames[$gid]['IOR_HROUH'] = $market['outcomes']['un']['odds'];
                            $aGames[$gid]['IOR_HROUC'] = $market['outcomes']['ov']['odds'];
                        }
                        // 单双
                        if ($k4=='oe_oe_FT_OE'){
                            $aGames[$gid]['IOR_REOO'] = $market['outcomes']['od']['euOdds'];
                            $aGames[$gid]['IOR_REOE'] = $market['outcomes']['ev']['euOdds'];
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
            $GID=$datainfo['GID'];
            $m_date=explode(' ',$datainfo['startTime'])[0];
            $m_time=getMtime($datainfo['startTime']); // 时分秒 转换为 12小时制 时分
            $checksql = "select MID from ".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE." where `MID` ='$GID'";
            $checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
            $check=mysqli_num_rows($checkresult);
            if($check==0){
                if($start == 0) {
                    $insert_sql .= "('$GID','FT','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','','','$datainfo[STRONG]','1','$ECID','$LID','$ISRB')" ;

                }else{
                    $insert_sql .= ",('$GID','FT','$datainfo[startTime]','$m_date','$m_time','$datainfo[TEAM_H]','$datainfo[TEAM_H]','$datainfo[TEAM_C]','$datainfo[TEAM_C]','$datainfo[LEAGUE]','$datainfo[LEAGUE]','','','$datainfo[STRONG]','1','$ECID','$LID','$ISRB')" ;
                }
                $start++;
            }else{
                $dataArray[$GID]=(array('FT',$datainfo[startTime],$m_date,$m_time,
                    $datainfo[LEAGUE],$datainfo[TEAM_H],$datainfo[TEAM_C],
                    $datainfo[STRONG],$datainfo[RATIO_RE],$datainfo[IOR_REH],$datainfo[IOR_REC],$datainfo[RATIO_ROUO],$datainfo[RATIO_ROUU],$datainfo[IOR_ROUH],$datainfo[IOR_ROUC],
                    $datainfo[HSTRONG],$datainfo[RATIO_HRE],$datainfo[IOR_HREH],$datainfo[IOR_HREC],$datainfo[RATIO_HROUO],$datainfo[RATIO_HROUU],$datainfo[IOR_HROUH],$datainfo[IOR_HROUC],
                    '1',"$ECID","$LID","$ISRB",$datainfo[M_Duration]));

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

    foreach($accoutArr as $key=>$value){
    //	$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=".$langx."&mtype=4");
    //	$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=re&uid=".$value['Uid']."&langx=".$langx."&mtype=4");
    //	$matches = get_content_deal($html_data);

        $postdata = array(
            'p' => 'get_game_list',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => 'ft',
            'showtype' => 'live',
            'rtype' => 'rb',
            'ltype' => '4',
            'sorttype' => 'T',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
    //    print_r($xml_data);
        $aData = xmlToArray($xml_data);
    //    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
    //    @error_log(json_encode($aData,JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');

        if(isset($aData['totalDataCount'])){
            $cou= $aData['totalDataCount'];
        }else{
            $cou=0;
        }

        // 根据主盘口的ecid 获取附属盘口
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
                $GID_MSTER=$datainfo['GID'];
                if ($datainfo['ECID']==''){
                    $datainfo['ECID']=str_replace('ec','',$v['@attributes']['id']);
                }
                $RETIMESET=$datainfo['RETIMESET']; // 开赛时间
                $SCORE_H=$datainfo['SCORE_H']; // 比分
                $SCORE_C=$datainfo['SCORE_C'];
                $redcard_h=$datainfo['REDCARD_H']; // 主队罚球数
                $redcard_c=$datainfo['REDCARD_C']; // 客队罚球数
                $datainfo['DATETIME'] = translateDatetime($datainfo['DATETIME']); // 主盘口时间格式转换
                $teamhMaster=$datainfo['TEAM_H'];
                $datainfos[$GID_MSTER]=$datainfo;

                // 根据主盘口的ecid获取让球的更多玩法获取附属盘口，然后合并到数据集合中
                if ($datainfo['MORE']>=6){ // 更多玩法
                    unset($postdata);
                    $postdata = array(
                        'p' => 'get_game_more',
                        'ver' => date('Y-m-d-H').$value['Ver'],
                        'langx' => $langx,
                        'uid' => $value['Uid'],
                        'gtype' => 'ft',
                        'showtype' => 'live',
                        'ltype' => '4',
                        'isRB' => 'Y',
    //                    'lid' => 'Y',
                        'ecid' => $datainfo['ECID'],
                    );
                    $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                    $aData = xmlToArray($xml_data);

                    if ($aData['code']==617){

                        if ($aData['game']['gid']){
                            if ($aData['game']['gopen']=='N') continue;
                            unset($aData['game']['@attributes']);
                            foreach ($aData['game'] as $k2=> $v2){
                                $datainfos[$aData['game']['gid']][strtoupper($k2)]=$v2;
                            }
                            $datainfos[$aData['game']['gid']]['RETIMESET']=$RETIMESET;
                            $datainfos[$aData['game']['gid']]['SCORE_H']=$SCORE_H;
                            $datainfos[$aData['game']['gid']]['SCORE_C']=$SCORE_C;
                           // $datainfos[$aData['game']['gid']]['TEAM_H']=$teamhMaster;
                        }
                        else{
                            foreach ($aData['game'] as $k2 => $v2){
                                if ($v2['gopen']=='N') continue;
                                unset($v2['@attributes']);
                                foreach ($v2 as $k3 => $v3){
                                    $datainfos[$v2['gid']][strtoupper($k3)] = $v3;
                                }
                                $datainfos[$v2['gid']]['RETIMESET'] = $RETIMESET;
                                // -角球数，-罚牌数，-点球(让球盘)，-点球(大小盘)，-C组冠军，-C组排尾队伍等等
                                // ptype有值的情况下，比分和主队名称 不需要从主盘口拿值
                                if (strlen(trim($v2['ptype']))==0){
                                    $datainfos[$v2['gid']]['SCORE_H'] = $SCORE_H;
                                    $datainfos[$v2['gid']]['SCORE_C'] = $SCORE_C;
                                   // $datainfos[$v2['gid']]['TEAM_H'] = $teamhMaster;
                                }
                            }
                        }
                    }
                    else{
                        echo '更多玩法请求失败，EID'.$datainfo['ECID'];
                    }

                }

            }
    //        @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
    //        @error_log(json_encode($datainfos,JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');
    //        exit();
        }

        // 重新统计盘口的数量
        $cou = count($datainfos);

        if($cou>0){//可以抓到数据
            $start = 0;
            $insert_sql = "INSERT INTO ".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."(MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,ShowTypeRB,RB_Show,ECID,LID,ISRB)VALUES";

        /*if ($aData['totalDataCount']==1){
            $tmp['ec']['data']['game']=$aData['ec']['game'];
            $aData=array();
            $aData=$tmp;
        }*/
        foreach ($datainfos as $k => $datainfo){
//            $datainfo=$v['game'];

            $LEAGUE=$datainfo['LEAGUE'];
            $TEAM_H=$datainfo['TEAM_H'];
            $TEAM_C=$datainfo['TEAM_C'];
            $GNUM_H=$datainfo['GNUM_H'];
            $GNUM_C=$datainfo['GNUM_C'];
            $STRONG=$datainfo['STRONG'];
            $RATIO_RE=$datainfo['RATIO_RE'];
            $IOR_REH=$datainfo['IOR_REH'];
            $IOR_REC=$datainfo['IOR_REC'];
            $RATIO_ROUO=$datainfo['RATIO_ROUO'];
            $RATIO_ROUU=$datainfo['RATIO_ROUU'];
            if (strpos($RATIO_ROUO,'O')!==false){}
            else{
                if (!empty($RATIO_ROUO)){
                    $RATIO_ROUO='O'.$RATIO_ROUO;
                }
            }
            if (strpos($RATIO_ROUU,'U')!==false){}
            else{
                if (!empty($RATIO_ROUU)){
                    $RATIO_ROUU='U'.$RATIO_ROUU;
                }
            }
            $IOR_ROUH=$datainfo['IOR_ROUH'];
            $IOR_ROUC=$datainfo['IOR_ROUC'];
            $HSTRONG=$datainfo['HSTRONG'];
            $RATIO_HRE=$datainfo['RATIO_HRE'];
            $IOR_HREH=$datainfo['IOR_HREH'];
            $IOR_HREC=$datainfo['IOR_HREC'];
            $RATIO_HROUO=$datainfo['RATIO_HROUO'];
            $RATIO_HROUU=$datainfo['RATIO_HROUU'];
            if (strpos($RATIO_HROUO,'O')!==false){}
            else {
                if (!empty($RATIO_HROUO)) {
                    $RATIO_HROUO='O'.$RATIO_HROUO;
                }
            }
            if (strpos($RATIO_HROUU,'U')!==false){}
            else{
                if (!empty($RATIO_HROUU)){
                    $RATIO_HROUU='U'.$RATIO_HROUU;
                }
            }
            $IOR_HROUH=$datainfo['IOR_HROUH'];
            $IOR_HROUC=$datainfo['IOR_HROUC'];
            $SCORE_H=$datainfo['SCORE_H'];
            $SCORE_C=$datainfo['SCORE_C'];
            // MB_Card
            // TG_Card
            $REDCARD_H=$datainfo['REDCARD_H'];// MB_Red
            $REDCARD_C=$datainfo['REDCARD_C'];// TG_Red
            $IOR_RMH=$datainfo['IOR_RMH'];
            $IOR_RMC=$datainfo['IOR_RMC'];
            $IOR_RMN=$datainfo['IOR_RMN'];
            $IOR_HRMH=$datainfo['IOR_HRMH'];
            $IOR_HRMC=$datainfo['IOR_HRMC'];
            $IOR_HRMN=$datainfo['IOR_HRMN'];
            $IOR_REOO=$datainfo['IOR_REOO']>0?$datainfo['IOR_REOO']:'';
            $IOR_REOE=$datainfo['IOR_REOE']>0?$datainfo['IOR_REOE']:'';
            $EVENTID=$datainfo['EVENTID'];
            $HOT=$datainfo['HOT'];
            $PLAY=$datainfo['PLAY'];
            $timestamp=$DATETIME=$datainfo['DATETIME'];
            $GID=$datainfo['GID'];
            $MT_GTYPE=$datainfo['MT_GTYPE'];
            $ECID=$datainfo['ECID'];
            $LID=$datainfo['LID'];
            $ISRB='Y'; // 返回的数据中没有这个字段，滚球赋值为 Y

//		for($i=0;$i<$cou;$i++){
			/*$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$date=explode('<br>',$datainfo[47]);*/

            $m_date=explode(' ', $DATETIME)[0];
            $m_time=getMtime($DATETIME);

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

            if( (stripos($datainfo[55], '點球') > 0) && $datainfo[56]=='Y' && ($datainfo[18]>0 || $datainfo[19]>0) ){ continue; }


			$checksql = "select MID from ".DATAHGPREFIX."match_sports where `MID` ='$GID'";
			$checkresult = mysqli_query($dbCenterSlaveDbLink,$checksql);
			$check=mysqli_num_rows($checkresult);
            $countdataArray[] =($check) ;
            if(DATA_CENTER_SWITCH){
                $dataCenterChildren = explode(',',DATA_CENTER_CHILDREN);
                for($m=0;$m<count($dataCenterChildren);$m++){
                    $redisObj->pushMessage($dataCenterChildren[$m].'_FT_RE_List',$GID);
                }
            }

			if($check==0){
                if($start == 0) {
                    $insert_sql .= "('$GID','FT','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$STRONG','1','$ECID','$LID','$ISRB')" ;

                }else{
                    $insert_sql .= ",('$GID','FT','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$STRONG','1','$ECID','$LID','$ISRB')" ;
                }
                $start++;
            }else{
                /*$dataArray[$datainfo[0]]=(array($check,$timestamp,$m_date,$m_time,$datainfo[2],$datainfo[3],$datainfo[4],$datainfo[5],$datainfo[6],$datainfo[7],
                    $datainfo[8],$datainfo[9],$datainfo[10],$datainfo[11],$datainfo[12],$datainfo[13],$datainfo[14],$datainfo[15],$datainfo[16],$datainfo[17],
                    $datainfo[20],$datainfo[21],$datainfo[22],$datainfo[23],$datainfo[24],$datainfo[25],$datainfo[26],$datainfo[27],$datainfo[28],$datainfo[29],
                    $datainfo[30])); // 把数据放在二维数组里面*/
                $dataArray[$GID]=(array('FT',$timestamp,$m_date,$m_time,$LEAGUE,$TEAM_H,$TEAM_C,$STRONG,$RATIO_RE,$IOR_REH,$IOR_REC,$RATIO_ROUO,$RATIO_ROUU,$IOR_ROUH,
                    $IOR_ROUC,$HSTRONG,$RATIO_HRE,$IOR_HREH,$IOR_HREC,$RATIO_HROUO,$RATIO_HROUU,$IOR_HROUH,$IOR_HROUC,'1',$ECID,$LID,$ISRB)); // 把数据放在二维数组里面

			}
		}

        if($start>0){ // 有新增数据
//            echo $insert_sql;
            mysqli_query($dbCenterMasterDbLink,$insert_sql) or die ("操作失敗!");
        }
        break;
	}
    }

}

$redisObj->setOne("FT_Running_Num",(int)$cou);
//var_dump($dataArray);
//var_dump($countdataArray);
// $insertaccount =0 ; //用于判断是否有新数据插入
// $before_updateaccount = array_count_values($countdataArray)[0] ; // 统计需要插入数据的数量
$updateaccount =0 ; //用于判断是否有更新数据

if($cou>0 and count($dataArray)>0){
    $ids = implode(',', array_keys($dataArray));
    $e_sql .= "END,";
    $sql = "update ".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE." set "; // update
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
    $sdr_sql .="ShowTypeHRB = CASE MID ";
    $sthr_sql .="M_LetB_RB_H = CASE MID ";
    $mlh_sql .="MB_LetB_Rate_RB_H = CASE MID ";
    $mlrh_sql .="TG_LetB_Rate_RB_H = CASE MID ";
    $tlrh_sql .="MB_Dime_RB_H = CASE MID ";
    $mdh_sql .="TG_Dime_RB_H = CASE MID ";
    $tdh_sql .="TG_Dime_Rate_RB_H = CASE MID ";
    $tdrh_sql .="MB_Dime_Rate_RB_H = CASE MID ";
    $rbshow_sql .="RB_Show = CASE MID ";
    $ecid_sql .="ECID = CASE MID ";
    $lid_sql .="LID = CASE MID ";
    $isrb_sql .="ISRB = CASE MID ";
    $mdrt_sql .="M_Duration = CASE MID " ;

    foreach ($dataArray as $id => $ordinal) {
        //echo $ordinal[2].'--' ;
            $ty_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
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
            $sthr_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
            $mlh_sql .= "WHEN $id THEN '$ordinal[17]' " ; // 拼接SQL语句
            $mlrh_sql .= "WHEN $id THEN '$ordinal[18]' " ; // 拼接SQL语句
            $tlrh_sql .= "WHEN $id THEN '$ordinal[19]' " ; // 拼接SQL语句
            $mdh_sql .= "WHEN $id THEN '$ordinal[20]' " ; // 拼接SQL语句
            $tdh_sql .= "WHEN $id THEN '$ordinal[21]' " ; // 拼接SQL语句
            $tdrh_sql .= "WHEN $id THEN '$ordinal[22]' " ; // 拼接SQL语句
            $rbshow_sql .= "WHEN $id THEN '$ordinal[23]' " ; // 拼接SQL语句
            $ecid_sql .= "WHEN $id THEN '$ordinal[24]' " ; // 拼接SQL语句
            $lid_sql .= "WHEN $id THEN '$ordinal[25]' " ; // 拼接SQL语句
            $isrb_sql .= "WHEN $id THEN '$ordinal[26]' " ; // 拼接SQL语句
            $mdrt_sql .= "WHEN $id THEN '$ordinal[27]' " ; // 拼接SQL语句

            $updateaccount++;

    }

    if($updateaccount>0){
        $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.
            $e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.
            $tdh_sql.$e_sql.$tdrh_sql.$e_sql.$rbshow_sql.$e_sql.$ecid_sql.$e_sql.$lid_sql.$e_sql.$isrb_sql.$e_sql.$mdrt_sql ;
        $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
//        echo $sql ;
        mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失敗!!");
    }

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
      走地數據接收<br>
      <span id="timeinfo"></span><br>
      <input type=button name=button value="繁體 <?php echo $cou?>" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
