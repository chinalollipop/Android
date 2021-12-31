<?php

/**
* 数据刷新滚球
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

//ini_set("display_errors", "On");
define("CONFIG_DIR", dirname(dirname(__FILE__)));
define("COMMON_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/curl_http.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require_once(COMMON_DIR."/common/sportCenterData.php");
require_once(COMMON_DIR."/common/function.php");
require CONFIG_DIR."/app/agents/include/define_function_list.inc.php";

$langx="zh-cn";
$uid='';
$gtype = 'BK';
$showtype='RB';
$Mtype='';
$page_no=0;
$nums_bill_ids= 0;
$per_num_each_thread= 0;
$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();
$matches = "";
$rtype = "BK_M_ROU_EO";
$flag = $redisObj->getSimpleOne($rtype."_FLAG");
//$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷新渠道
$flushWay = SPORT_FLUSH_WAY; // 刷水渠道
$flushDoamin = SPORT_FLUSH_DOMAIN; // 刷水网址

if($flag != 1) {
	$redisObj->setOne($rtype."_FLAG","0");
	mysqli_query($dbMasterLink, "SET NAMES 'utf8'");
	//$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
	//$lockResult = mysqli_query($dbMasterLink,"select status from ".DBPREFIX."match_sports_running_lock where `Type` = '".$rtype."' for update");
	//$lockRow=mysqli_fetch_assoc($lockResult);
	//if($begin&&$lockResult){
		//if($lockRow['status']==0){
			//$matches=$rtype();
            //$dataRes =refreshData($rtype,$matches);
            //if($dataRes ){
				//$setResult=$redisObj->setOne($rtype,json_encode($matches));
				//if($setResult){
                    //mysqli_query($dbMasterLink,"COMMIT");
                    $matches=BK_M_ROU_EO();
                    $dataRes =refreshData($rtype,$matches);
                    $opens = array("A","B","C","D");
					$worker_num = count($opens);
					if(CREAT_STATIC_PAGES){
                        for($i=0;$i<$worker_num; $i++){
                            $process = new swoole_process("createHtml", true);
                            $pid = $process->start();
                            $process->write($i);
                        }
                    }

                //}
			//}
		//}
	//}
	//@mysqli_query($dbMasterLink,"ROLLBACK");
	$redisObj->setOne($rtype."_FLAG", "0");
	echo "主进程执行完毕！";
}else {
	exit("有进程在执行，退出！");
}

function BK_M_ROU_EO(){
    global $flushWay,$flushDoamin;
    $result = $dataCount = [];
    if($flushWay == 'ujl'){
        // 抓取數據
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");
        $curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/BK_Browser/BK_Roll_l.aspx");
        $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/BK_Browser/BK_Roll.aspx");
        $htmlData = mb_convert_encoding($htmlData, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        // 單頁數據
        preg_match_all("/Array\((.+?)\);/is", $htmlData, $matches);
        // 整合数据
        foreach ($matches[0] as $matchData){
            $matchData = str_replace(");", ")", $matchData);
            $matchData = str_replace("&gt; ", "", $matchData);
            $dataCount[] = eval("return $matchData;");
        }
        $dataTotal = compileData($dataCount); // 优久乐抓取的数据与正网调整一致，方便后续刷新数据和调用
        foreach ($dataTotal as $key => $value){
            $result[] = "Array('" . implode("','", $value) . "');"; // 调整为正网数据后，还原回正则匹配的数据，方便后续匹配获取，免做修改。
        }
    }
    elseif($flushWay == 'ra686'){
        global $dbMasterLink,$redisObj,$BK_RB_API,$langx,$gtype,$showtype;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
        $jsonData = $curl->fetch_url($BK_RB_API);
        $aData = json_decode($jsonData,true);
        $cou = count($aData['data']['seasons']); // 当前联赛数量， 每个联赛下多个matchs(主盘口)，每个主盘口有多个markets(玩法)

        if($cou>0 and $aData['success']){

            foreach ($aData['data']['seasons'] as $k => $aLeagues){

                $isEsport = $aLeagues['esport']; // 是否电竞盘口
                $seasonId = $aLeagues['seasonId']; // 联赛LID

                //==============主盘口START
                foreach ($aLeagues['matches'] as $k2 => $aMatchs){

                    /*$aGames[$gid]['gid'] = $gid;
                    $aGames[$gid]['league'] = $aLeagues['name'];
                    $aGames[$gid]['startTime'] = $aGames[$gid]['datetime'] = str_replace('T', ' ', $aMatchs['startTime']);
                    $aGames[$gid]['M_Time'] =getMtime($aGames[$gid]['startTime']); // 时分秒 转换为 12小时制 时分
                    $aGames[$gid]['RETIMESET'] = $liveStatus.'^'.$aMatchs['clock']; //2H^80:09
                    $aGames[$gid]['TIMER'] = $aMatchs['clock']; // 06:14
                    $LASTTIME = (explode(':',$aMatchs['clock'])[0]) * 60 + (explode(':',$aMatchs['clock'])[1]);
                    $aGames[$gid]['LASTTIME'] = $LASTTIME;
                    $aGames[$gid]['MORE'] = $aMatchs['totalMarkets']; // 更多玩法数量
                    $competitors = $aMatchs['competitors'];
                    $aGames[$gid]['team_h'] = $competitors['home']['name'];
                    $aGames[$gid]['team_c'] = $competitors['away']['name'];

                    // 比分
                    $aGames[$gid]['SCORE_H'] = $competitors['home']['score']; // 主队比分
                    $aGames[$gid]['SCORE_C'] = $competitors['away']['score']; // 客队比分
                    $aGames[$gid]['isNeutral'] = $isNeutral; // 中立场
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口
                    $aGames[$gid]['liveStatus'] = $aGames[$gid]['se_now'] = $liveStatus; // 当前第几节  Q1*/

                    // 主盘口玩法转换
                    /*$aGamesTmp=masterMethodsTrans($aMatchs['markets'], 're');
                    // 主盘口玩法转换
                    foreach ($aGamesTmp as $gidTmp => $gameTmp){
                        foreach ($gameTmp as $k => $v){

                            // 让球
                            if ($k=='RATIO_RE'){ $aGames[$gid]['ratio_re'] = $v; }
                            elseif ($k=='STRONG'){ $aGames[$gid]['strong'] = $v; }
                            elseif ($k=='IOR_REH'){ $aGames[$gid]['ior_REH'] = $v; }
                            elseif ($k=='IOR_REC'){ $aGames[$gid]['ior_REC'] = $v; }
                            // 半场让球
                            elseif ($k=='RATIO_HRE'){ $aGames[$gid]['HALF_RATIO_RE'] = $v; }
                            elseif ($k=='HSTRONG'){ $aGames[$gid]['hstrong'] = $v; }
                            elseif ($k=='IOR_HREH'){ $aGames[$gid]['HALF_IOR_REH'] = $v; }
                            elseif ($k=='IOR_HREC'){ $aGames[$gid]['HALF_IOR_REC'] = $v; }
                            // 得分大小
                            elseif ($k=='RATIO_ROUO'){ $aGames[$gid]['ratio_rouo'] = $v; }
                            elseif ($k=='RATIO_ROUU'){ $aGames[$gid]['ratio_rouu'] = $v; }
                            elseif ($k=='IOR_ROUH'){ $aGames[$gid]['ior_ROUH'] = $v; }  //ior_ROUH 小
                            elseif ($k=='IOR_ROUC'){ $aGames[$gid]['ior_ROUC'] = $v; }  //ior_ROUC 大

                            // 单双
                            elseif ($k=='IOR_REOO'){ $aGames[$gid]['ior_REOO'] = $v; }
                            elseif ($k=='IOR_REOE'){ $aGames[$gid]['ior_REOE'] = $v; }

                            else{ $aGames[$gid][$k] = $v; }

                        }
                    }*/
                    $gid = $aMatchs['matchId'];  // 篮球的盘口  拿主盘口的ID，球队大小放到一起展示
                    $inplay = $aMatchs['inplay']; // 是否滚球
                    $showStat = $aMatchs['showStat'];
                    $isNeutral = $aMatchs['neutral']; // 中立场
                    $liveStatus = getliveStatus($aMatchs['liveStatus']); // 当前第几节q1-q4转为大写 nowSession se_now
                    $TIMER = $aMatchs['clock']; // 01:16
                    $RETIMESET = $liveStatus.'^'.$aMatchs['clock']; //Q3^01:16
                    $LASTTIME = (explode(':',$aMatchs['clock'])[0]) * 60 + (explode(':',$aMatchs['clock'])[1]);//剩余s
                    $totalMarkets = $aMatchs['totalMarkets'];   // 更多玩法数量

                    // 根据更多玩法获取所有盘口(含主盘口和附属盘口)
                    $dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,'',$seasonId,'Y');
                    if(!empty($dataNew['tmp_Obj'])) {
                        $masGid = transGid($gid);  //主盘口gid
                        foreach($dataNew['tmp_Obj'] as $key => $datainfo) {
                            if($datainfo['gid'] == '') {continue;}
                            $datainfo = getRatioData($datainfo , $isGunQiu = 'Y');

                            if($datainfo['gid'] == $masGid) {
                                $datainfo['isMaster'] = 'Y'; // 是否主盘口
                                $datainfo['inplay'] = $inplay; // 是否滚球
                                $datainfo['showStat'] = $showStat;
                                $datainfo['MORE'] = $totalMarkets;
                                $datainfo['seasonId'] = $seasonId;
                                $datainfo['SCORE_H'] = $datainfo['score_h'];  // 主队比分
                                $datainfo['SCORE_C'] = $datainfo['score_c'];  // 客队比分
                                $datainfo['neutral'] = $isNeutral==true? 1:0;  // 中立场
                                $datainfo['MIDFIELD'] = $datainfo['midfield'] = $isNeutral==true?" [中]":"";
                                $datainfo['isEsport'] = $isEsport;  // 是否电竞盘口
                                $datainfo['M_Time'] =getMtime($datainfo['startTime']); // 时分秒转换为12小时制 时分 02:00a
                                $datainfo['NOWSESSION'] = $datainfo['nowSession'] = check_null($datainfo['se_now']);    //$liveStatus Q3 HALF_SE
                                $datainfo['RETIMESET'] = $RETIMESET; // Q3^01:16
                                $datainfo['LASTTIME'] = $datainfo['t_count'] = $LASTTIME>0? $LASTTIME:'';  // 76
                                $aGames[$key] = $datainfo;  //主盘口新增

                                $attachArray['M_Time'] =  $datainfo['M_Time']; // 拼接字段
                                $attachArray['LASTTIME'] = $attachArray['t_count'] = $LASTTIME>0? $LASTTIME:'';  // 76
                                $attachArray['seasonId'] =  $datainfo['seasonId'];
                                $attachArray['SCORE_H'] =  $datainfo['SCORE_H'];
                                $attachArray['SCORE_C'] =  $datainfo['SCORE_C'];
                            }

                            if($datainfo['gid'] != $masGid) {    //附属盘口
                                $readyArr = array_merge($datainfo , $attachArray);
                                $aGames[$key] = $readyArr;
                            }
                        }
                    }//==============更多玩法END

                } //==============主盘口END
            }//==============联赛END

        }
        else{
            echo ('没有滚球盘口，请稍后！');
        }
        $result=[];
        foreach ($aGames as $k => $datainfo){
            if($datainfo['gid'] < 10000) { continue;}
            $gid=$datainfo['gid'];
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']===0){ }
            else{
                $result[] = $datainfo;
            }
        }
        $cou = count($result);
        $redisObj->setOne("BK_Running_Num",(int)$cou);
        $redisObj->setOne("BK_M_ROU_EO",json_encode($result,JSON_UNESCAPED_UNICODE));

    }
    else{
        global $langx,$accoutArr,$dbMasterLink,$redisObj;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("/tmp/cookies.txt");
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
        foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水

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
                    unset($datainfos[$k]['@attributes']);
                    unset($datainfo['@attributes']);
                    $GIDM=$datainfo['GID']; // 主盘口GID
                    $datainfos[$GIDM]=$datainfo;
                    $datainfos[$GIDM]['isMaster']='Y'; // 是否主盘口
                    $MTimeM=explode(' ',$datainfo['DATETIME'])[1];
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
                        if($aData['game']['gid']){
                            unset($aData['game']['@attributes']);
                            foreach ($aData['game'] as $k2 => $v2){
                                $datainfos[$aData['game']['gid']][$k2]=$v2;
                            }
                            $datainfos[$aData['game']['gid']]['M_Time']=$MTimeM;
                        }else{

                            foreach ($aData['game'] as $k2 => $v2){
                                unset($v2['@attributes']);
                                if ($k2==0){
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
            }

        }

//        for($i=0;$i<$cou;$i++){
//            $messages=$result[$i];
//            $messages=str_replace(");",")",$messages);
//            $messages=str_replace("cha(9)","",$messages);
//            $datainfo=eval("return $messages;");
//            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID='$datainfo[0]' and `Cancel`=0";
//            $openresult = mysqli_query($dbMasterLink,$opensql);
//            $openrow=mysqli_fetch_assoc($openresult);
//            if($openrow['Open']!=1){ unset($result[$i]); }
//        }
        // 将后台关闭掉的盘口剔除，然后以数组形式返回数据
        /*if (!isset($aData['ec']['game'])){
            foreach ($aData['ec'] as $k => $v){
                $datainfo=$v['game'];
                $gid=$datainfo['GID'];
                $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
                $openresult = mysqli_query($dbMasterLink,$opensql);
                $openrow=mysqli_fetch_assoc($openresult);
                if($openrow['Open']!=1){ }
                else{
                    $result[] = $datainfo;
                }
            }
        }else{
            $datainfo=$aData['ec']['game'];
            $gid=$aData['ec']['game']['GID'];
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']!=1){ }
            else{
                $result[] = $datainfo;
            }
        }*/

        $datainfos = array_sort($datainfos,'gid',$type='asc');

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
                $datainfo['ior_ROUH']=$datainfo['IOR_ROUH']>0?round_num($datainfo['IOR_ROUH']):'';  //正网：客队小
                $datainfo['ior_ROUC']=$datainfo['IOR_ROUC']>0?round_num($datainfo['IOR_ROUC']):'';  //正网：主队大
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

                if (!empty($datainfo['ratio_rouo'])){   //滚球主队全场大小
                    $datainfo['ratio_rouo']='O'.$datainfo['ratio_rouo'];
                }
                if (!empty($datainfo['ratio_rouu'])){   //滚球客队全场大小
                    $datainfo['ratio_rouu']='U'.$datainfo['ratio_rouu'];
                }
                if (!empty($datainfo['ratio_rouho'])){   //半场滚球主队半场大小 O 大
                    $datainfo['ratio_rouho']='O'.$datainfo['ratio_rouho'];
                }
                if (!empty($datainfo['ratio_rouhu'])){   //半场滚球主队半场大小 U 小
                    $datainfo['ratio_rouhu']='U'.$datainfo['ratio_rouhu'];
                }
                if (!empty($datainfo['ratio_rouco'])){  //半场滚球客队半场大小 O 大
                    $datainfo['ratio_rouco']='O'.$datainfo['ratio_rouco'];
                }
                if (!empty($datainfo['ratio_roucu'])){  //半场滚球客队半场大小 U 小
                    $datainfo['ratio_roucu']='U'.$datainfo['ratio_roucu'];
                }
            }

            $gid=$datainfo['gid']; //主盘口GID gid 附属盘口gid
            if($gid < 10000){ continue; }
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']===0){ }
            else{
                $result[] = $datainfo;
            }
        }

        $cou = count($result);

        $redisObj->setOne("BK_Running_Num",(int)$cou);
        $redisObj->setOne('BK_M_ROU_EO',json_encode($result,JSON_UNESCAPED_UNICODE));
    }
    return $result;
}

// 整合数据-调整数据格式与正网一致-2018-11-04
function compileData($dataCount)
{
    $dataTotal = [];
    foreach ($dataCount as $key => $value){
        $dataTotal[$value[0]] = [
            0 => $value[0],
            1 => $value[2],  // 时间
            2 => $value[3],  // 联赛名称
            3 => '',         // 主队ID
            4 => '',         // 客队ID
            5 => $value[4],
            6 => $value[5],
            7 => stripos($value[10], '*') !== false ? 'C' : 'H',
            8 => str_replace('*', '', $value[10]),
            9 => $value[8]-0.01,
            10 => $value[9],
            11 => 'O' . $value[11],
            12 => 'U' . $value[11],
            13 => $value[13],
            14 => $value[12]-0.01,
            15 => '',           // '' - 不知是何意
            16 => '',           // ''
            17 => '',           // ''
            18 => '0',
            19 => '0',
            20 => '',
            21 => '',
            22 => '',
            23 => '',
            24 => '',
            25 => '',
            26 => '',
            27 => '',
            28 => '',
            29 => '',
            30 => '',
            31 => '单',
            32 => '双',
            33 => $value[26],  // 单
            34 => $value[27],  // 双
            35 => 'O' . $value[20],  // 主队积分大小-大
            36 => 'U' . $value[20],  // 主队积分大小-小
            37 => $value[21],        // 主队积分大赔率
            38 => $value[22],        // 主队积分小赔率
            39 => 'O' . $value[23],  // 客队积分大小-大
            40 => 'U' . $value[23],  // 客队积分大小-大
            41 => $value[24],        // 客队积分大赔率
            42 => $value[25],        // 客队积分小赔率
            43 => '',          // 正网：视频ID；196：--
            44 => '',          // 正网：视频；196：--
            45 => '',          // 正网：perform；196：--
            46 => '',          // 正网：视频；196：--
            47 => $value[1] . '<br>' . $value[2],         // 日期：11-02<br>11:00p
            48 => '196^' . $value[1] . '^' . $value[2],   // 正网：1H^35:11；196：58
            49 => '0',         // 正网：全部玩法；196：--
            50 => '',
            51 => '',
            52 => '',          // 正网：第几节；196：--
            53 => '',          // 正网：比分；196：--
            54 => '',          // 正网：比分；196：--
            55 => '',
            56 => ''
        ];
    }
    return $dataTotal;
}

function refreshData($key,$matches){
	global $dbMasterLink;

	$cou=count($matches);
	$res=true;
	if($key=="BK_M_ROU_EO"){
        if ($cou>0){

            $start = 0;
            $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,M_League_tw,M_League,MB_MID,TG_MID,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,ShowTypeRB,nowSession)VALUES";
            foreach ($matches as $k => $datainfo){
    //	        $messages=$matches[$i];
    //	        $messages=str_replace(");",")",$messages);
    //	        $messages=str_replace("cha(9)","",$messages);
    //	        $datainfo=eval("return $messages;");
                $m_date=explode(' ', $datainfo['datetime'])[0];
                $m_time=getMtime($datainfo['datetime']);


                // 将从正网拉取的测试数据过滤掉
                // stripos 查找字符串首次出现的位置（不区分大小写）
                $pos_m = stripos($datainfo['league'], 'test'); // 查找联赛名称是否含有 test
                $pos_m_tw = stripos($datainfo['league'], '测试'); // 查找联赛名称是否含有 测试
                $pos_mb = stripos($datainfo['team_h'], 'test'); // 检查主队名称是否含有 test
                $pos_mb_tw = stripos($datainfo['team_h'], '测试'); // 检查主队名称是否含有 测试
                $pos_tg = stripos($datainfo['team_c'], 'test'); // 检查客队名称是否含有 test
                $pos_tg_tw = stripos($datainfo['team_c'], '测试'); // 检查客队名称是否含有 测试
                if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                    $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                    continue;
                }

                $checksql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$datainfo[gid]'";
                $checkresult = mysqli_query($dbMasterLink,$checksql);
                $check=mysqli_num_rows($checkresult);

                if($check==0){
                    if($start == 0) {
                        $insert_sql .="('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$datainfo[M_Time]','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[se_now]')" ;
                    }else{
                        $insert_sql .=",('$datainfo[gid]','BK','$datainfo[datetime]','$m_date','$datainfo[M_Time]','$datainfo[league]','$datainfo[league]','$datainfo[gnum_h]','$datainfo[gnum_c]','$datainfo[team_h]','$datainfo[team_h]','$datainfo[team_c]','$datainfo[team_c]','$datainfo[strong]','$datainfo[se_now]')" ;
                    }
                    $start++;
                }else{
                    $M_duration = $datainfo['se_now'].'-'.$datainfo['t_count']; // 比赛进度 【 Q2-80】 【 第二节-第80秒】 附属盘口【t_count剩多少s】
                    $dataArray[$datainfo['gid']]=(array($check,$datainfo['datetime'],$m_date,$datainfo['M_Time'],$datainfo['league'],$datainfo['team_h'],$datainfo['team_c'], // 0-6
                        $datainfo['strong'],$datainfo['ratio_re'],$datainfo['ior_REH'],$datainfo['ior_REC'], // 7-10
                        $datainfo['ratio_rouo'],$datainfo['ratio_rouu'],$datainfo['ior_ROUH'],$datainfo['ior_ROUC'], //11-14
                        $datainfo['strong'],$datainfo['HALF_RATIO_RE'],$datainfo['HALF_IOR_REH'],$datainfo['HALF_IOR_REC'], // 15-18
                        $datainfo['ratio_rouho'],$datainfo['ratio_rouhu'],$datainfo['ratio_rouco'],$datainfo['ratio_roucu'], // 19-22
                        $datainfo['ior_ROUHO'],$datainfo['ior_ROUHU'],$datainfo['ior_ROUCO'],$datainfo['ior_ROUCU'], // 23-26
                        $datainfo['ior_REOO'],$datainfo['ior_REOE'], // 27-28
                        $datainfo['eventid'],$datainfo['HOT'],$datainfo['PLAY'],$datainfo['SCORE_H'],$datainfo['SCORE_C'],$M_duration,$datainfo['se_now'], // 29-35
                        $datainfo['ior_RMH'],$datainfo['ior_RMC'],$datainfo['ior_RMN'], // 36-38
                    )); // 把数据放在二维数组里面
                }


                /*$opensql = "select Open from `".DBPREFIX."match_sports` where MID='$datainfo[gid]' and `Type`='BK' and `Cancel`=0";
                $openresult = mysqli_query($dbMasterLink,$opensql);
                $openrow=mysqli_fetch_assoc($openresult);
                if($openrow['Open']==0){}
                else{
                    $M_duration = $datainfo['se_now'].'-'.$datainfo['t_count']; // 比赛进度 【 Q2-80】 【 第二节-第80秒】

                    $sql = "update `".DBPREFIX."match_sports` set ShowTypeRB='$datainfo[strong]',M_LetB_RB='$datainfo[ratio_re]',MB_LetB_Rate_RB='$datainfo[ior_REH]',TG_LetB_Rate_RB='$datainfo[ior_REC]',
                    MB_Dime_RB='$datainfo[ratio_rouo]',TG_Dime_RB='$datainfo[ratio_rouu]',MB_Dime_Rate_RB='$datainfo[ior_ROUC]',TG_Dime_Rate_RB='$datainfo[ior_ROUH]',
                    ShowTypeHRB='$datainfo[strong]',M_LetB_RB_H='$datainfo[HALF_RATIO_RE]',MB_LetB_Rate_RB_H='$datainfo[HALF_IOR_REH]',TG_LetB_Rate_RB_H='$datainfo[HALF_IOR_REC]',
                    MB_Dime_RB_H='$datainfo[ratio_rouho]',MB_Dime_RB_S_H='$datainfo[ratio_rouhu]',TG_Dime_RB_H='$datainfo[ratio_rouco]',TG_Dime_RB_S_H='$datainfo[ratio_roucu]',
                    MB_Dime_Rate_RB_H='$datainfo[ior_ROUHO]',MB_Dime_Rate_RB_S_H='$datainfo[ior_ROUHU]',TG_Dime_Rate_RB_H='$datainfo[ior_ROUCO]',TG_Dime_Rate_RB_S_H='$datainfo[ior_ROUCU]',
                    S_Single_Rate='$datainfo[ior_REOO]',S_Double_Rate='$datainfo[ior_REOE]',
                    Eventid='$datainfo[eventid]',Hot='$datainfo[40]',Play='$datainfo[41]',MB_Ball='$datainfo[SCORE_H]',TG_Ball='$datainfo[SCORE_C]',M_Duration='$M_duration',RB_Show=1,S_Show=0
                    where MID=$datainfo[gid] and `Type`='BK'";
    //                @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
    //                @error_log(json_encode($datainfo,JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');
    //                @error_log($sql.PHP_EOL, 3, '/tmp/group/logout_warn.log');
                    if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
                }*/
            }

            if($start>0){ // 有新增数据
//                print_r($insert_sql);
                mysqli_query($dbMasterLink,$insert_sql) or die ("操作失敗!");
            }

            $updateaccount =0 ; //用于判断是否有更新数据

            if($cou>0 and count($dataArray)>0){
                $ids = implode(',', array_keys($dataArray));
                $e_sql .= "END,";
                $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "; // update
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
                $trr_sql .="TG_LetB_Rate_RB = CASE MID "; //10
                $mbd_sql .="MB_Dime_RB = CASE MID ";
                $tgd_sql .="TG_Dime_RB = CASE MID ";
                $tgr_sql .="TG_Dime_Rate_RB = CASE MID ";
                $mbr_sql .="MB_Dime_Rate_RB = CASE MID "; // 14
                $sthrp_sql .="ShowTypeHRB = CASE MID ";
                $mrh_sql .="M_LetB_RB_H = CASE MID ";
                $mrrh_sql .="MB_LetB_Rate_RB_H = CASE MID ";
                $trrh_sql .="TG_LetB_Rate_RB_H = CASE MID ";
                $mbdh_sql .="MB_Dime_RB_H = CASE MID ";
                $mbdsh_sql .="MB_Dime_RB_S_H = CASE MID ";
                $tgdh_sql .="TG_Dime_RB_H = CASE MID ";
                $tgdsh_sql .="TG_Dime_RB_S_H = CASE MID ";
                $mbdrh_sql .="MB_Dime_Rate_RB_H = CASE MID ";
                $mbdrsh_sql .="MB_Dime_Rate_RB_S_H = CASE MID ";
                $tgdrh_sql .="TG_Dime_Rate_RB_H = CASE MID ";
                $tgdrsh_sql .="TG_Dime_Rate_RB_S_H = CASE MID ";
                $singlerate_sql .="S_Single_Rate = CASE MID ";
                $doublerate_sql .="S_Double_Rate = CASE MID "; // 28
                $sdr_sql .="Eventid = CASE MID ";
                $sthr_sql .="Hot = CASE MID ";
                $mlh_sql .="Play = CASE MID ";
                $mbball_sql .="MB_ball = CASE MID ";
                $tgball_sql .="TG_ball = CASE MID ";
                $mduration_sql .="M_Duration = CASE MID ";
                $nowsession_sql .="nowSession = CASE MID ";
                $iorrmh_sql .="MB_Win_Rate_RB = CASE MID ";
                $iorrmc_sql .="TG_Win_Rate_RB = CASE MID ";
                $iorrmn_sql .="M_Flat_Rate_RB = CASE MID ";
//                $mbinballhr_sql .="MB_Inball_HR = CASE MID ";
//                $tginballhr_sql .="TG_Inball_HR = CASE MID ";
                $score_sql .="Score = CASE MID ";
                $checked_sql .="Checked = CASE MID ";
                $scoresource_sql .="Score_Source = CASE MID ";
                $mlrh_sql .="RB_Show = CASE MID ";
                $tlrh_sql .="S_Show = CASE MID ";
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
                    $sthrp_sql .= "WHEN $id THEN '$ordinal[15]' ";
                    $mrh_sql .= "WHEN $id THEN '$ordinal[16]' ";
                    $mrrh_sql .= "WHEN $id THEN '$ordinal[17]' ";
                    $trrh_sql .= "WHEN $id THEN '$ordinal[18]' ";
                    $mbdh_sql .= "WHEN $id THEN '$ordinal[19]' ";
                    $mbdsh_sql .= "WHEN $id THEN '$ordinal[20]' ";
                    $tgdh_sql .= "WHEN $id THEN '$ordinal[21]' ";
                    $tgdsh_sql .= "WHEN $id THEN '$ordinal[22]' ";
                    $mbdrh_sql .= "WHEN $id THEN '$ordinal[23]' ";
                    $mbdrsh_sql .= "WHEN $id THEN '$ordinal[24]' ";
                    $tgdrh_sql .= "WHEN $id THEN '$ordinal[25]' ";
                    $tgdrsh_sql .= "WHEN $id THEN '$ordinal[26]' ";
                    $singlerate_sql .= "WHEN $id THEN '$ordinal[27]' ";
                    $doublerate_sql .= "WHEN $id THEN '$ordinal[28]' ";
                    $sdr_sql .= "WHEN $id THEN '$ordinal[29]' " ; // 拼接SQL语句
                    $sthr_sql .= "WHEN $id THEN '$ordinal[30]' " ; // 拼接SQL语句
                    $mlh_sql .= "WHEN $id THEN '$ordinal[31]' " ; // 拼接SQL语句
                    $mbball_sql .= "WHEN $id THEN '$ordinal[32]' " ; // 拼接SQL语句
                    $tgball_sql .= "WHEN $id THEN '$ordinal[33]' " ; // 拼接SQL语句
                    $mduration_sql .= "WHEN $id THEN '$ordinal[34]' " ; // 拼接SQL语句
                    $nowsession_sql .= "WHEN $id THEN '$ordinal[35]' " ; // 拼接SQL语句
                    $iorrmh_sql .= "WHEN $id THEN '$ordinal[36]' ";
                    $iorrmc_sql .= "WHEN $id THEN '$ordinal[37]' ";
                    $iorrmn_sql .= "WHEN $id THEN '$ordinal[38]' ";
//                    $mbinballhr_sql .= "WHEN $id THEN '$ordinal[34]' " ; // 拼接SQL语句
//                    $tginballhr_sql .= "WHEN $id THEN '$ordinal[35]' " ; // 拼接SQL语句
                    $score_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
                    $checked_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
                    $scoresource_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句
                    $mlrh_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
                    $tlrh_sql .= "WHEN $id THEN '0' " ; // 拼接SQL语句

                    $updateaccount++;

                }

                if($updateaccount>0){
                    $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.$e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sthrp_sql.$e_sql.$mrh_sql.$e_sql.$mrrh_sql.$e_sql.$trrh_sql.$e_sql.$mbdh_sql.$e_sql.$mbdsh_sql.$e_sql.$tgdh_sql.$e_sql.$tgdsh_sql.$e_sql.$mbdrh_sql.$e_sql.$mbdrsh_sql.$e_sql.$tgdrh_sql.$e_sql.$tgdrsh_sql.$e_sql.$singlerate_sql.$e_sql.$doublerate_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mbball_sql.$e_sql.$tgball_sql.$e_sql.$mduration_sql.$e_sql.$nowsession_sql.$e_sql.
                        $iorrmh_sql.$e_sql.$iorrmc_sql.$e_sql.$iorrmn_sql.$e_sql.
                        $score_sql.$e_sql.$checked_sql.$e_sql.$scoresource_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql;
                    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
//                    echo $sql .'<br>';
                    mysqli_query($dbMasterLink,$sql) or die ("操作失敗!!");
                }

            }
        }
        else{
            $res=false;
        }
	}else{
        $res=false;
    }
	return $res;
}

?>
<?php
function createHtml(swoole_process $worker) {
//createHtml($rtype,$matches,"A");
	global $uid,$langx,$showtype,$Mtype,$page_no,$rtype,$matches,$opens;
	$swoole_num = $worker->read();
	$open = $opens[$swoole_num];
	
	$redisObj = new Ciredis();
	
	$K=0;
	$num=60;
	$m_date=date('Y-m-d');
	$date=date('m-d');
	
		ob_start(); //打开缓冲区  
		$newDataArray = array();
		switch ($rtype){
			case "FT_M_ROU_EO":	
					$oldRtype='re';
					break;
			case "BK_M_ROU_EO":	
					$oldRtype='re';
					break;
			case "FT_PD":		
					$oldRtype='rpd';
					break;
			case "FT_HPD":		
					$oldRtype='hrpd';
					break;
			case "FT_T":		
					$oldRtype='rt';
					break;
			case "FT_F":		
					$oldRtype='rf';
					break;
		}
	
		?>
		
		<HEAD>
		<TITLE>篮球变数值</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=utf-8">
		<SCRIPT language=JavaScript>
		parent.flash_ior_set='Y';
		parent.minlimit_VAR='0';
		parent.maxlimit_VAR='0';
		parent.code='人民幣(RMB)';
		parent.ltype='3';
		parent.str_even = '和局';
		parent.str_submit = '确认';
		parent.str_reset = '重设';
		parent.langx='zh-cn';
		parent.rtype='<?php echo $oldRtype?>';
		parent.sel_lid='';
		top.today_gmt = '<?php echo $m_date ?>';
		top.now_gmt = '<?php echo date("H:i:s") ?>';
		parent.retime = 60 ; // 今日赛事刷新倒计时
		parent.gamount=0;
		parent.t_page=0;

		<?php
        $datainfos = [];
		switch ($rtype){
			case "BK_M_ROU_EO": // 从今日赛事切换到滚球 滚球
				echo "parent.retime=20;\n"; // 倒计时刷新时间
				echo "parent.str_renew = '秒自动更新';\n";
				$page_size=40;
				$page_count=0;
				$gamecount=0;

				echo "parent.t_page=0\n";
				echo "parent.gamount=0;\n";
				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}

				/*for($i=0;$i<$cou;$i++) {
                    $messages = $matches[$i];
                    $messages = str_replace(");", ")", $messages);
                    $messages = str_replace("cha(9)", "", $messages);
                    $datainfo = eval("return $messages;");
                    $MID=$datainfo[0];
                    $datainfos[$MID]=$datainfo;
                    $gamecount++;
                }*/
//                $datainfos = $matches;

                foreach ($matches as $k => $datainfo){
                    $datainfos[$datainfo['gid']] =$datainfo;
                }

                //篮球从第三节结束，也就是第四节开始时不允许下注，下半场倒计时10分钟
                // Q1 第一节 Q2 第二节 Q3 第三节 Q4 第四节 H1 上半场 H2 下半场 OT 加时 HT 半场
                foreach ($datainfos as $MID => $datainfo){

                    $datainfo[5]=$datainfo['team_h'];
                    $datainfo[6]=$datainfo['team_c'];
                    $datainfo[52]=$datainfo['se_now'];
                    $datainfo[56]=$datainfo['LASTTIME'];
                    if( $datainfo[52]=='Q4' || $datainfo[52]=='OT' || ($datainfo[52]=='H2' and $datainfo[56]<=600)){

//                    $datainfos[$MID]=array();
                        $datainfos[$MID]['strong']='';
                        $datainfos[$MID]['ior_REH']='';
                        $datainfos[$MID]['ior_REC']='';
                        $datainfos[$MID]['ratio_re']='';
                        $datainfos[$MID]['ratio_rouo']='';
                        $datainfos[$MID]['ratio_rouu']='';
                        $datainfos[$MID]['ior_ROUC']='';
                        $datainfos[$MID]['ior_ROUH']='';
                        $datainfos[$MID]['ratio_rouho']='';
                        $datainfos[$MID]['ratio_rouhu']='';
                        $datainfos[$MID]['ior_ROUHO']='';
                        $datainfos[$MID]['ior_ROUHU']='';
                        $datainfos[$MID]['ratio_rouco']='';
                        $datainfos[$MID]['ratio_roucu']='';
                        $datainfos[$MID]['ior_ROUCO']='';
                        $datainfos[$MID]['ior_ROUCU']='';
                        $datainfos[$MID]['MORE']='';

                        // 其他盘口，赔率等投注信息不显示（无视美式足球）
                        if (strpos($datainfo[2],'美式足球')===false){

                            if (isset($datainfos[$MID+7])){
                                if ($datainfo[2]==$datainfos[$MID+7][2] and $datainfo[5]==$datainfos[$MID+7][5] and $datainfo[6]==$datainfos[$MID+7][6]){
//                                $datainfos[$MID+7]=array();
                                    $datainfos[$MID+7]['strong']='';
                                    $datainfos[$MID+7]['ior_REH']='';
                                    $datainfos[$MID+7]['ior_REC']='';
                                    $datainfos[$MID+7]['ratio_re']='';
                                    $datainfos[$MID+7]['ratio_rouo']='';
                                    $datainfos[$MID+7]['ratio_rouu']='';
                                    $datainfos[$MID+7]['ior_ROUC']='';
                                    $datainfos[$MID+7]['ior_ROUH']='';
                                    $datainfos[$MID+7]['ratio_rouho']='';
                                    $datainfos[$MID+7]['ratio_rouhu']='';
                                    $datainfos[$MID+7]['ior_ROUHO']='';
                                    $datainfos[$MID+7]['ior_ROUHU']='';
                                    $datainfos[$MID+7]['ratio_rouco']='';
                                    $datainfos[$MID+7]['ratio_roucu']='';
                                    $datainfos[$MID+7]['ior_ROUCO']='';
                                    $datainfos[$MID+7]['ior_ROUCU']='';
                                    $datainfos[$MID+7]['MORE']='';
                                }
                            }
                            if (isset($datainfos[$MID+14])) {
                                if ($datainfo[2] == $datainfos[$MID + 14][2] and $datainfo[5] == $datainfos[$MID + 14][5] and $datainfo[6] == $datainfos[$MID + 14][6]) {
//                                $datainfos[$MID+14]=array();
                                    $datainfos[$MID+14]['strong']='';
                                    $datainfos[$MID+14]['ior_REH']='';
                                    $datainfos[$MID+14]['ior_REC']='';
                                    $datainfos[$MID+14]['ratio_re']='';
                                    $datainfos[$MID+14]['ratio_rouo']='';
                                    $datainfos[$MID+14]['ratio_rouu']='';
                                    $datainfos[$MID+14]['ior_ROUC']='';
                                    $datainfos[$MID+14]['ior_ROUH']='';
                                    $datainfos[$MID+14]['ratio_rouho']='';
                                    $datainfos[$MID+14]['ratio_rouhu']='';
                                    $datainfos[$MID+14]['ior_ROUHO']='';
                                    $datainfos[$MID+14]['ior_ROUHU']='';
                                    $datainfos[$MID+14]['ratio_rouco']='';
                                    $datainfos[$MID+14]['ratio_roucu']='';
                                    $datainfos[$MID+14]['ior_ROUCO']='';
                                    $datainfos[$MID+14]['ior_ROUCU']='';
                                    $datainfos[$MID+14]['MORE']='';
                                }
                            }
                        }

                    }
                }

                // 篮球滚球盘口默认按照时间排序
                foreach ($datainfos as $key => $match){
                    // 转换时间 02-28<br>01:35a  -》  2019-02-28 01:35:00
                    // 转换时间 02-28<br>01:35p  -》  2019-02-28 13:35:00
//                $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a
                    $match[100] = $match['datetime']; //02-28 01:35a
                    $sAorP = substr($match[100],11);
                    $match[100] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').'-'.substr($match[100],0, -1)));
                    if ($sAorP == 'p'){
                        $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
                    }
                    $datainfos[$key][100] = $match[100];
                }
                $datainfos = array_sort($datainfos,0,$type='desc');
                $datainfos = array_values(array_sort($datainfos,100,$type='asc'));

                foreach ($datainfos as $MID => $datainfo){
//					$M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】

                    $datainfo[0]=$datainfo['gid'];
                    $datainfo[2]=$datainfo['league'];
                    $datainfo[3]=$datainfo['gnum_h'];
                    $datainfo[4]=$datainfo['gnum_c'];
                    $datainfo[5]=$datainfo['team_h'];
                    $datainfo[6]=$datainfo['team_c'];
                    $datainfo[52]=$datainfo['se_now'];
                    $datainfo[53]=$datainfo['SCORE_H'];
                    $datainfo[54]=$datainfo['SCORE_C'];
                    $datainfo[56]=$datainfo['LASTTIME'];

					if ($datainfo[9]<>''){ // 全场让球
						// 全场让球单独处理
						$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[9],$datainfo[10],100); // 默认都是香港盘
						$datainfo[9]=$ra_rate[0]; // 主队
						$datainfo[10]=$ra_rate[1]; // 客队
						$datainfo[9]=change_rate($open,$datainfo[9]);
						$datainfo[10]=change_rate($open,$datainfo[10]);
					}
					if ($datainfo[13]<>''){
						$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[13],$datainfo[14],100); // 默认都是香港盘
						$datainfo[13]=$ra_rate[0]; // 全场大小 大
						$datainfo[14]=$ra_rate[1]; // 全场大小 小
						$datainfo[13]=change_rate($open,$datainfo[13]);
						$datainfo[14]=change_rate($open,$datainfo[14]);
					}
					if ($datainfo[37]<>''){
						$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[37],$datainfo[38],100); // 默认都是香港盘
						$datainfo[37]=$ra_rate[0];
						$datainfo[38]=$ra_rate[1];
						$datainfo[37]=change_rate($open,$datainfo[37]);
						$datainfo[38]=change_rate($open,$datainfo[38]);
					}
					if ($datainfo[41]<>''){
						$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[41],$datainfo[42],100); // 默认都是香港盘
						$datainfo[41]=$ra_rate[0];
						$datainfo[42]=$ra_rate[1];
						$datainfo[41]=change_rate($open,$datainfo[41]);
						$datainfo[42]=change_rate($open,$datainfo[42]);
					}
					if ($datainfo[33]<>''){
						$datainfo[33]=change_rate($open,$datainfo[33]);
					}
					if ($datainfo[34]<>''){
						$datainfo[34]=change_rate($open,$datainfo[34]);
					}
					if ($datainfo[28]<>''){
						$datainfo[28]=change_rate($open,$datainfo[28]);
					}
					if ($datainfo[29]<>''){
						$datainfo[29]=change_rate($open,$datainfo[29]);
					}
					if ($datainfo[30]<>''){
						$datainfo[30]=change_rate($open,$datainfo[30]);
					}
                    // $datainfo[52] 球队名称 Q1-Q4 第一节-第四节，H1 上半场，H2 下半场 ，OT 加时，HT 半场
                    $team_active = $team_time = '';
                    // 优久乐数据判断处理（篮球滚球没有比分和时间，另行处理）
                    $match_time=explode("^", $datainfo[48]);
                    if($match_time[0] == 196){
                        $team_active = '';
                        $team_time = $match_time[1];
                        $datainfo_score = $match_time[2];
                    }else {
//                        $mbTeamArr = explode('-', $datainfo[5]);
//                        preg_match('/\d+/', $mbTeamArr[1], $mbTeamArrList);
//                        if ($mbTeamArrList[0] == 2) {
//                            $team_active = '第二节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } elseif ($mbTeamArrList[0] == 3) {
//                            $team_active = '第三节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } elseif ($mbTeamArrList[0] == 4) {
//                            $team_active = '第四节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } else {
                            switch ($datainfo[52]) {
                                case 'Q1':
                                    $team_active = '第一节';
                                    break;
                                case 'Q2':
                                    $team_active = '第二节';
                                    break;
                                case 'Q3':
                                    $team_active = '第三节';
                                    break;
                                case 'Q4':
                                    $team_active = '第四节';
                                    break;
                                case 'H1':
                                    $team_active = '上半场';
                                    break;
                                case 'H2':
                                    $team_active = '下半场';
                                    break;
                                case 'OT':
                                    $team_active = '加时';
                                    break;
                                case 'HT':
                                    $team_active = '半场';
                                    break;
                            }
//                        }
                        if ($datainfo[56] && $datainfo[56] > 0) { // 转化时间
                            $team_hour = floor($datainfo[56] / 3600); // 小时不要
                            $team_minute = floor(($datainfo[56] - 3600 * $team_hour) / 60);
                            $team_second = floor((($datainfo[56] - 3600 * $team_hour) - 60 * $team_minute) % 60);
                            $team_time = ($team_minute > 9 ? $team_minute : "0" . $team_minute) . ':' . ($team_second > 9 ? $team_second : "0" . $team_second);
                        }
                        $datainfo_score = " $datainfo[53]-<span style=\"color:#FF0000\">$datainfo[54]</span>";// 比分处理
                    }
                    $datainfo_team = $team_active."<span class=\"rb_time_color\">".$team_time."</span>" ;// 球队名称处理


						// 全场滚球独赢主队 $datainfo[29]   全场滚球独赢客队 $datainfo[30]
                    $datainfo[7]=$datainfo['strong'];
                    $datainfo[8]=$datainfo['ratio_re'];
                    $datainfo[35]=$datainfo['ratio_rouho'];
                    $datainfo[36]=$datainfo['ratio_rouhu'];
                    $datainfo[37]=$datainfo['ior_ROUHO'];
                    $datainfo[38]=$datainfo['ior_ROUHU'];
                    $datainfo[39]=$datainfo['ratio_rouco'];
                    $datainfo[40]=$datainfo['ratio_roucu'];
                    $datainfo[41]=$datainfo['ior_ROUCO'];
                    $datainfo[42]=$datainfo['ior_ROUCU'];
                    $datainfo[25]=$datainfo['MORE'];

                    if($datainfo[7]=="H"){
							$ratio_mb_str=$datainfo[8];
							$ratio_tg_str='';
						}elseif($datainfo[7]=="C"){
							$ratio_mb_str='';
							$ratio_tg_str=$datainfo[8];
						}
						$datainfo[5]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$datainfo[5]);
						$datainfo[5]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$datainfo[5]);
						$newDataArray[$MID]['gid']=$datainfo[0];
						$newDataArray[$MID]['timer']=$datainfo[1];
						$newDataArray[$MID]['league']=$datainfo[2];
						$newDataArray[$MID]['dategh']=date('m-d').$datainfo[3];
						$newDataArray[$MID]['datetimelove']=$datainfo[47];
						$newDataArray[$MID]['gnum_h']=$datainfo[3];
						$newDataArray[$MID]['gnum_c']=$datainfo[4];
						$newDataArray[$MID]['team_h']=$datainfo[5];
                        $newDataArray[$MID]['team_h_score']=$datainfo[53];
                        $newDataArray[$MID]['team_h_for_sort']=explode('<font color=gray>',$datainfo[5])[0];
                        $newDataArray[$MID]['team_c']=$datainfo[6];
                        $newDataArray[$MID]['team_c_score']=$datainfo[54];
						$newDataArray[$MID]['strong']=$datainfo[7];
                        $newDataArray[$MID]['ratio']=$datainfo['ratio_re'];
						$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
						$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
                        $newDataArray[$MID]['ior_RH']=$datainfo['ior_REH'];
                        $newDataArray[$MID]['ior_RC']=$datainfo['ior_REC'];
						$newDataArray[$MID]['ratio_o']=$datainfo[11];
						$newDataArray[$MID]['ratio_u']=$datainfo[12];
                        $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$datainfo['ratio_rouo']);
                        $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$datainfo['ratio_rouu']);
                        $newDataArray[$MID]['ior_OUH']=$datainfo['ior_ROUC'];
                        $newDataArray[$MID]['ior_OUC']=$datainfo['ior_ROUH'];
						$newDataArray[$MID]['ior_EOO']=$datainfo[15];
						$newDataArray[$MID]['ior_EOE']=$datainfo[16];
                        $newDataArray[$MID]['ratio_ouho']=$datainfo[35];
                        $newDataArray[$MID]['ratio_ouhu']=$datainfo[36];
                        $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$datainfo[35]);
                        $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$datainfo[36]);
                        $newDataArray[$MID]['ior_OUHO']=$datainfo[37];
                        $newDataArray[$MID]['ior_OUHU']=$datainfo[38];
//                        unset($newDataArray[$MID]['ratio_ouho']);
//                        unset($newDataArray[$MID]['ratio_ouhu']);
//                        unset($newDataArray[$MID]['ratio_ouho_str']);
//                        unset($newDataArray[$MID]['ratio_ouhu_str']);
//                        unset($newDataArray[$MID]['ior_OUHO']);
//                        unset($newDataArray[$MID]['ior_OUHU']);
                        $newDataArray[$MID]['ratio_ouco']=$datainfo[39];
                        $newDataArray[$MID]['ratio_oucu']=$datainfo[40];
                        $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$datainfo[39]);
                        $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$datainfo[40]);
                        $newDataArray[$MID]['ior_OUCO']=$datainfo[41];
                        $newDataArray[$MID]['ior_OUCU']=$datainfo[42];
//                        unset($newDataArray[$MID]['ratio_ouco']);
//                        unset($newDataArray[$MID]['ratio_oucu']);
//                        unset($newDataArray[$MID]['ratio_ouco_str']);
//                        unset($newDataArray[$MID]['ratio_oucu_str']);
//                        unset($newDataArray[$MID]['ior_OUCO']);
//                        unset($newDataArray[$MID]['ior_OUCU']);
						$newDataArray[$MID]['more']=$datainfo[25];
						$newDataArray[$MID]['eventid']=$datainfo[26];
						$newDataArray[$MID]['hot']=$datainfo[27];
						$newDataArray[$MID]['ior_MH']=$datainfo[29];
						$newDataArray[$MID]['ior_MC']=$datainfo[30];
						$newDataArray[$MID]['team_info']=$datainfo_team;
						$newDataArray[$MID]['score_info']=$datainfo_score;
						$newDataArray[$MID]['center_tv']=$datainfo[31];
						$newDataArray[$MID]['play']=$datainfo[32];
						$newDataArray[$MID]['datetime']=$datainfo[47];
						$newDataArray[$MID]['all']=$datainfo[25];
						$newDataArray[$MID]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&gnum={$datainfo[3]}&langx={$langx}&odd_f_type=H&strong=".$datainfo[7];
//	                    if( $mbteamLast==$datainfo[5] && $tgteamLast==$datainfo[6] && $leagueLast==$datainfo[2] ){
                        if( $datainfo[53]=='' && $datainfo[54]==''){
							$newDataArray[$MID]['headShow']=0;	
						}else{
							$newDataArray[$MID]['headShow']=1;
						}
						$mbteamLast=$datainfo[5];
	            		$tgteamLast=$datainfo[6];
						$leagueLast=$datainfo[2];            

					$K=$K+1;					
				}

                // 按照时间排序后，按照联盟归类盘口
                $newDataArray = group_same_key($newDataArray,'team_h_for_sort');
                foreach ($newDataArray as $k => $v){
                    $val_sort = array_sort($v,'team_h_score',$type='desc');
                    foreach ($val_sort as $k2=>$v2){
                        $newDataArray2[] = $v2;
                    }
                }
                $newDataArray = $newDataArray2;
				$page_count=ceil($K/$page_size);
				echo "parent.t_page=$page_count;\n";
				echo "parent.gamount=$gamecount;\n"; // 总数量
				$listTitle="蓝球和美式足球 :滚球";
				$leagueNameCur='';
				break;
		}
		?>

		//重置滚球数量
		window.defaultStatus="Wellcome.................";
		</script>
		<link rel="stylesheet" href="/style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css" media="screen">
		</head>
		<body i1d="MFT" class="bodyset FTR body_browse_set" onload="onLoad();">
		<!-- 加载层 -->
		<!-- <div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--><!-- </td></tr></table></div>-->
        <div class="ss_table" style="display: inline-block">
		    <table border="0" cellpadding="0" cellspacing="0" id="myTable">
			<tbody>
				<tr>
					<td>
					 <table border="0" cellpadding="0" cellspacing="0" id="box" class="<?php echo $box_pd?>">
						<tbody>
							<tr>
								<td class="top">
									<h1 class="top_h1">
										<em><?php echo $listTitle; ?></em>
										<?php
										  if($rtype=='FT_PD' || $rtype=='FT_HPD'){ // 波胆才有
											if($rtype=='FT_HPD'){
												$select = 'selected' ;
											}else{
												$select = '' ;
											}
											  echo ' <select id="selwtype" onChange="chg_wtype(selwtype.value);">
														<option value="rpd" >全场</option>
														<option value="hrpd" '.$select.' >上半场</option>
													 </select>' ;
										  }
										  if($rtype=='FT_PD' || $rtype=='FT_HPD'){
											  echo '<span class="maxbet">单注最高派彩额 ： RMB 1,000,000.00</span>' ;
										  }
										?>

									</h1>
									<div id="skin" class="zoomChange">字体显示：<a id="skin_0" data-val="1" class="zoom zoomSmaller" href="javascript:;" title="点击切换原始字体">小</a><a id="skin_1" data-val="1.2" class="zoom zoomMed" href="javascript:;" title="点击切换中号字体">中</a><a id="skin_2" data-val="1.35" class="zoom zoomBigger" href="javascript:;" title="点击切换大号字体">大</a></div>
								</td>
							</tr>
							<tr>
								<td class="mem">
								<h2>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
									<tbody>
										<tr>
											<td id="page_no">
												<span id="pg_txt">
													
												</span>
												<div class="search_box">
													<input type="text" id="seachtext" placeholder="输入关键字查询" value="" class="select_btn">
													<input type="button" id="btnSearch" value="搜索" class="seach_submit" onclick="seaGameList()">
												</div>
											</td>
											<td id="tool_td"><!-- 滚球 -->
												<table border="0" cellspacing="0" cellpadding="0"
													class="tool_box">
													<tbody>
														<tr>
															<td id="fav_btn">
																<div id="fav_num" title="清空" onclick="chkDelAllShowLoveI();" style="display: none;"><!--我的最爱场数--><span id="live_num"></span></div>
																<div id="showNull" title="无资料" class="fav_null" style="display: block;"></div>
																<div id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display: none;" class="fav_on"></div>
																<div id="showMy" title="我的最爱" onclick="showMyLove('FT');" class="fav_out" style="display: none;"></div>
															</td>
															<td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';"><!--秒数更新-->
																<div onclick="javascript:reload_var()"><font id="refreshTime">刷新</font></div>
															</td>
															<td class="leg_btn">
																<div onclick="javascript:chg_league();" id="sel_league">选择联赛(<span id="str_num">全部</span>)</div>
															</td>
															<td class="OrderType" id="Ordertype">
																<select id="myoddType" onchange="chg_odd_type()">
																	<option value="H" selected="">香港盘</option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
								</h2>
								<!-- 资料显示的layer -->
								<div id="showtable">
									<table id="game_table" cellspacing="0" cellpadding="0" class="game">
										<tbody>
											<?php
											if(count($newDataArray)==0){
												echo "<tr><td colspan=20 class='no_game'>您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td></tr>";
											}else{
												switch ($rtype){
													case "FT_M_ROU_EO":	include "Running/body_rb_m_r_ou_eo.php";break;
													case "BK_M_ROU_EO":	include "Running/body_bk_re_m_r_ou.php";break;
													case "FT_PD":		include "Running/body_rpd.php";break;
													case "FT_HPD":		include "Running/body_hrpd.php";break;
													case "FT_T":		include "Running/body_rt.php";break;
													case "FT_F":		include "Running/body_rf.php";break;
												}	
											}
											?>	
										</tbody>
									</table>
								</div>
								</td>
							</tr>
							<tr>
								<td id="foot"><b>&nbsp;</b></td>
							</tr>
						</tbody>
					</table>
						<center><!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div></center>
					</td>
				</tr>
			</tbody>
		</table>
        </div>
		<!-- 原来的显示更多玩法 -->
		<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
			<iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
		</div>

		<!-- 所有玩法弹窗 -->
		<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
			<iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
		</div>

		<!--选择联赛-->
		<div id="legView" style="display:none;" class="legView" >
			<div class="leg_head" onMousedown="initializedragie('legView')"></div>
			<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>
			<div class="leg_foot"></div>
		</div>

		<!-- 2018 新增 右侧游戏 -->
		<div class="today_bet_floatright <?php echo $today_bet_floatright?>" >
		    <!-- <iframe id="live" name="live" src="../live/live.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>"></iframe> -->
		    <a href="javascript:;" class="today_bet_refresh" onClick="javascript:reload_var()"></a>
		    <a title="足球滚球" class="today_bet_football_move" href="javascript:parent.parent.header.chg_second_tip(this,'rb','<?php echo $uid?>','FT');parent.parent.header.chg_button_bg('FT','rb');parent.parent.header.chg_index(this,' ','../FT_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2','rb');" ></a>
		    <a title="足球赛事" style="display: none" class="today_bet_football" href="javascript:parent.parent.header.chg_button_bg('FT','<?php echo $Mtype ?>');parent.parent.header.chg_index(this,' ','../FT_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');"></a>
		    <a title="篮球赛事" class="today_bet_basketball" href="javascript:parent.parent.header.chg_button_bg('BK','today','BK','<?php echo $uid?>');parent.parent.header.chg_index(this,' ','../BK_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=all&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.BK_lid_type,'SI2');"></a>
		    <a title="蓝球滚球" class="today_bet_basketball_move" href="javascript:parent.parent.header.chg_second_tip(this,'rb','<?php echo $uid?>','BK');parent.parent.header.chg_button_bg('BK','rb');parent.parent.header.chg_index(this,' ','../BK_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.BK_lid_type,'SI2','rb');" ></a>
		    <a title="真人娱乐" href="../zrsx/index_<?php echo TPL_FILE_NAME;?>.php?uid=<?php echo $uid;?>" target="body" class="today_bet_live"></a>
		    <a title="电子游艺" href="../games.php?uid=<?php echo $uid;?>" target="_blank" class="today_bet_game"></a>
		    <a title="彩票游戏" href="../../../tpl/lottery.php?uid=<?php echo $uid;?>" target="body" class="today_bet_lottery"></a>
		</div>

		<script type="text/javascript" src="../../../js/jquery.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
		<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
		<script type="text/javascript">
			// 侧边栏游戏选项处理，在当前游戏中不显示当前游戏
			var g_type = sessionStorage.getItem('g_type') ;
			var m_type = sessionStorage.getItem('m_type') ;
			if(m_type == 'rb'){
				document.getElementsByClassName('today_bet_football_move')[0].style.display='none' ;
				document.getElementsByClassName('today_bet_football')[0].style.display='' ;

			}

            setBodyScroll();
        </script>
		</body>
		</html>
<?php 
		$file='';
		if($rtype=="BK_M_ROU_EO"){
			$dir = "/www/huangguan/hg3088/member_new/app/member/BK_browse/";
		}else{
			$dir = "/www/huangguan/hg3088/member_new/app/member/FT_browse/";
		}
		$filesName=strtolower("Running".$open.$rtype).time().".html";
		$info=ob_get_contents();  
		$file = $dir.$filesName;
		$handle = fopen($file, 'w+');
		fwrite($handle, $info);
		fclose($handle);
		ob_end_clean();
		unset($future_r_data);
		unset($newDataArray);
		$redis_error = $redisObj->setOne($rtype.'_'.$open.'_URL',$filesName);
}
?>
