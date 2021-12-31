<?php

/**
* 数据刷新滚球
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
define("CONFIG_DIR", dirname(dirname(__FILE__)));
define("COMMON_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/curl_http.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require_once(COMMON_DIR."/common/sportCenterData.php");
require_once(COMMON_DIR."/common/function.php");
require CONFIG_DIR."/app/agents/include/define_function_list.inc.php";
//赔率修复公用函数
require CONFIG_DIR."/app/agents/include/odds_convert_common_function.php";

$langx="zh-cn";
$uid='';
$showtype='';
$Mtype='';
$page_no=0;
$nums_bill_ids= 0;
$per_num_each_thread= 0;
//$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();
$matches = "";
$rtype = "FT_M_ROU_EO";
$flag = $redisObj->getSimpleOne($rtype."_FLAG");
//$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷水渠道
$flushWay = SPORT_FLUSH_WAY; // 刷水渠道
$flushDoamin = SPORT_FLUSH_DOMAIN; // 刷水网址

if($flag != 1) {
	$redisObj->setOne($rtype."_FLAG","0");
//    @error_log('FT update Start----'.date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
	mysqli_query($dbMasterLink, "SET NAMES 'utf8'");
	//$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
    //$lockResult = mysqli_query($dbMasterLink,"select status from ".DBPREFIX."match_sports_running_lock where `Type` = '".$rtype."' for update");
    //$lockRow=mysqli_fetch_assoc($lockResult);
    //if($begin&&$lockResult){
		//if($lockRow['status']==0){
            //$dataRes =refreshData($rtype,$matches);
			//if($dataRes ){
				//$setResult=$redisObj->setOne($rtype,json_encode($matches));
				//if($setResult){
                    //mysqli_query($dbMasterLink,"COMMIT");
                    $matches=FT_M_ROU_EO();
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
//    @error_log('FT update End----'.date("Y-m-d H:i:s").PHP_EOL.PHP_EOL, 3, '/tmp/group/logout_warn.log');
	$redisObj->setOne($rtype."_FLAG", "0");
	echo "主进程执行完毕！";
}else {
	exit("有进程在执行，退出！");
}

//获取滚球独赢大小单双数据
function FT_M_ROU_EO(){
    global $flushWay,$flushDoamin;
    $result = $dataPage = $dataCount = $dataTotal = [];
    if($flushWay == 'ujl'){ // 优久乐刷水
        // 抓取數據
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");
        for($page = 1; $page <= 10; $page ++) { // 默认抓取10页数据（注意是抓取一页数据，还是抓取所有数据？)
            $curl->set_referrer("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Roll_l.aspx");
            $htmlData = $curl->fetch_url("" . FLUSH_WEBSITE_196 . "/touzhu/FT_Browser/FT_Roll.aspx?p=" . $page);
            $htmlData = mb_convert_encoding($htmlData, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
            // 總頁數
            preg_match('/parent.page=(\s+\d+)/', $htmlData, $matchesPage);
            $pageTotal = trim($matchesPage[1]);
            if ($page > $pageTotal)
                break;
            // 單頁數據
            preg_match_all("/Array\((.+?)\);/is", $htmlData, $matches);
            $dataPage[$page] = $matches[0];
        }

        // 整合数据
        foreach ($dataPage as $preData){
            foreach ($preData as $matchData){
                $matchData = str_replace(");",")",$matchData);
                $matchData = eval("return $matchData;");
                $dataCount[] = $matchData;
            }
        }
        $dataTotal = compileData($dataCount); // 优久乐抓取的数据与正网调整一致，方便后续刷新数据和调用
        foreach ($dataTotal as $key => &$value){
            $result[] = "Array('" . implode("','", $value) . "');"; // 调整为正网数据后，还原回正则匹配的数据，方便后续匹配获取，免做修改。
        }
    }
    elseif($flushWay == 'ra686'){
        global $langx,$accoutArr,$dbMasterLink,$redisObj,$FT_RB_API,$FT_MORESPORT_API;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
        $jsonData = $curl->fetch_url($FT_RB_API); // 请求主盘口
        $aData = json_decode($jsonData,true);
        $cou= count($aData['data']['seasons']);

        if($cou>0 and $aData['success']){

            foreach ($aData['data']['seasons'] as $k => $aLeagues) {
                $league = $aLeagues['name']; // 联赛名称
                $isEsport = $aLeagues['esport'];
                foreach ($aLeagues['matches'] as $k2 => $aMatchs) {
                    $gid = $aMatchs['matchId'];

                    if ($aMatchs['liveStatus']=='HT'){
                        $aMatchs['clock']=$aMatchs['liveStatusText'];
                    }
                    // 加时赛gid 单独处理
//                    if (in_array('penalty',$aMatchs['obtSelections'])){
                    if ($aMatchs['extraTime']){
                        $gid = $aMatchs['markets'][0]['eventId'];
                    }
                    $aGames[$gid]['GID'] = $gid;
                    $aGames[$gid]['LEAGUE'] = $league;
                    $aGames[$gid]['startTime'] = $aGames[$gid]['DATETIME'] = str_replace('T', ' ', $aMatchs['startTime']);
                    $aGames[$gid]['RETIMESET'] = $aMatchs['liveStatus'].'^'.$aMatchs['clock']; //2H^80:09  HT~半场
                    $aGames[$gid]['TIMER'] = $aMatchs['clock'];
                    $aGames[$gid]['MORE'] = $aMatchs['totalMarkets'];
                    // 球队信息【队名、比分、红牌】
                    $competitors = $aMatchs['competitors'];
                    // 加时赛球队名称 单独处理
//                    if (in_array('penalty',$aMatchs['obtSelections'])){
                    if ($aMatchs['extraTime']){
                        $aGames[$gid]['GIDMASTER'] = $aMatchs['matchId']; // 加时赛的时候，主盘口的GID需要单独返回，方便获取所有玩法的数据
                        $aGames[$gid]['TEAM_H'] = $competitors['home']['name'].' -'.$aMatchs['markets'][0]['sectionName'];
                        $aGames[$gid]['TEAM_C'] = $competitors['away']['name'].' -'.$aMatchs['markets'][0]['sectionName'];
                        $hScore = 0;
                        $aScore = 0;
                        getJiaoQiuScore($hScore, $aScore, $aMatchs['ci'], $gid);
                        $aGames[$gid]['SCORE_H'] = $hScore; // 主队加时赛比分
                        $aGames[$gid]['SCORE_C'] = $aScore; // 客队加时赛比分
                    }
                    else{
                        $aGames[$gid]['TEAM_H'] = $competitors['home']['name'];
                        $aGames[$gid]['TEAM_C'] = $competitors['away']['name'];
                        $aGames[$gid]['SCORE_H'] = $competitors['home']['score']; // 主队比分
                        $aGames[$gid]['SCORE_C'] = $competitors['away']['score']; // 客队比分
                    }
                    $aGames[$gid]['REDCARD_H'] = $competitors['home']['redCard']; // 主队红牌数
                    $aGames[$gid]['REDCARD_C'] = $competitors['away']['redCard']; // 客队红牌数
                    $aGames[$gid]['Neutral'] = $aMatchs['neutral']; // 中立场
                    $aGames[$gid]['isEsport'] = $isEsport; // 是否电竞盘口
                    $obtSelections = $aMatchs['obtSelections']; // 标签菜单开关

                    // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                    $aGames[$gid]['eps']=$aGames[$gid]['handicaps']=$aGames[$gid]['goalsou']=$aGames[$gid]['corners']=$aGames[$gid]['bookings']=$aGames[$gid]['toqualify']=$aGames[$gid]['penalty']=$aGames[$gid]['extratime']='N';
                    if (in_array('eps',$obtSelections)){
                        $aGames[$gid]['eps']='Y';
                    }
                    if (in_array('handicaps',$obtSelections)){
                        $aGames[$gid]['handicaps']='Y';
                    }
                    if (in_array('goalsou',$obtSelections)){
                        $aGames[$gid]['goalsou']='Y';
                    }
                    if (in_array('corners',$obtSelections)){
                        $aGames[$gid]['corners']='Y';
                    }
                    if (in_array('bookings',$obtSelections)){
                        $aGames[$gid]['bookings']='N'; // 6686缺少主要的玩法，罚牌强制关闭
                    }
                    if (in_array('toqualify',$obtSelections)){
                        $aGames[$gid]['toqualify']='Y';
                    }
                    if (in_array('penalty',$obtSelections)){ // 6686缺少主要的玩法，关闭点球
                        $aGames[$gid]['penalty']='N';
                    }
                    if (in_array('extratime',$obtSelections)){
                        $aGames[$gid]['extratime']='Y';
                    }

                    // 主盘口玩法转换
                    $aGamesTmp=masterMethodsTrans($aMatchs['markets'], 're');

                    foreach ($aGamesTmp as $gidTmp => $gameTmp){
                        foreach ($gameTmp as $k => $v){
                            $aGames[$gid][$k] = $v;
                        }
                    }

                }

            }

        }
        else{
            echo '没有滚球盘口，请稍后！';
        }
        $result=[];
        foreach ($aGames as $gid => $datainfo){
            if($gid < 10000){ continue; }
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']===0){
            }
            else{
                $result[] = $datainfo;
            }
        }
        // 重新统计盘口的数量
        $cou = count($result);
        $redisObj->setOne("FT_Running_Num",(int)$cou);
        $redisObj->setOne("FT_M_ROU_EO",json_encode($result,JSON_UNESCAPED_UNICODE));

    }
    else{
        global $langx,$accoutArr,$dbMasterLink,$redisObj;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
        foreach($accoutArr as $key=>$value) {//在扩展表中获取账号重新刷水
            /*if( $value['cookie'] =='' ){
                $dateCur = date('Y-m-d',time());
                $curl->set_cookie("gamePoint_21059363={$dateCur}%2A0%2A0; gamePoint_21059364={$dateCur}%2A0%2A0; gamePoint_21059365={$dateCur}%2A0%2A0; gamePoint_21059366={$dateCur}%2A2%2A0; gamePoint_21059367={$dateCur}%2A2%2A0; gamePoint_21059368={$dateCur}%2A2%2A0; gamePoint_21059369={$dateCur}%2A2%2A0;");
            }else{
                $curl->set_cookie($value['cookie']);
            }*/

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
                $cou= $aData['totalDataCount'];
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
                    $GID_MASTER=$gid=$datainfo['GID'];
                    $RETIMESET=$datainfo['RETIMESET']; // 开赛时间
                    $SCORE_H=$datainfo['SCORE_H']; // 比分
                    $SCORE_C=$datainfo['SCORE_C'];
                    $redcard_h=$datainfo['REDCARD_H']; // 主队罚球数
                    $redcard_c=$datainfo['REDCARD_C']; // 客队罚球数
                    $datainfo['DATETIME'] = translateDatetime($datainfo['DATETIME']); // 主盘口时间格式转换
                    $Neutral=0;
                    $datainfos[$k]['Neutral']=$datainfo['Neutral']=$Neutral;
                    if (strpos($datainfo['TEAM_H'],'[中]')!==false){
                        $teamhMaster=$datainfos[$k]['TEAM_H']=trim(str_replace('[中]','',$datainfo['TEAM_H']));
                        $Neutral=1; // 是否中立场 1 是 0 不是
                        $datainfos[$k]['Neutral']=$datainfo['Neutral']=$Neutral;
                    }
                    $datainfos[$GID_MASTER]=$datainfo;


                    // 根据主盘口的ecid获取让球的更多玩法获取附属盘口，然后合并到数据集合中
                    /*if ($datainfo['MORE']>=6){ // 更多玩法
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
                                //$datainfos[$aData['game']['gid']]['TEAM_H']=$teamhMaster;
                                $datainfos[$aData['game']['gid']]['Neutral']=$Neutral;
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
                                        //$datainfos[$v2['gid']]['TEAM_H'] = $teamhMaster;
                                    }
                                    $datainfos[$v2['gid']]['Neutral']=$Neutral;
                                }
                            }
                        }
                        else{
                            echo '更多玩法请求失败，EID'.$datainfo['ECID']; //die;
                        }
                    }*/
                }
            }

            // 重新统计盘口的数量
            $cou = count($datainfos);
        }

        // 已经关闭取消的盘口，移除掉。Open 等于0的 移除掉
        foreach ($datainfos as $k => $datainfo){
            $gid=$datainfo['GID'];
            if($gid < 10000){ continue; }
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$gid' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']=='0'){ }
            else{
                $result[] = $datainfo;
            }
        }

        $redisObj->setOne("FT_Running_Num",(int)$cou);
        $redisObj->setOne("FT_M_ROU_EO",json_encode($result,JSON_UNESCAPED_UNICODE));
    }
	return $result;
}


// 整合数据-调整数据格式与正网一致-2018-11-03
function compileData($dataCount)
{
	$dataTotal = [];
	foreach ($dataCount as $key => &$value){
		$scores = strlen($value[32]) > 1 ? explode(':', $value[32]) : (strlen($value[31]) > 1 ? explode(':', $value[31]) : explode(':', $value[30])); // 比分
		$time = date('h:ia', strtotime($value[38]) - 12 * 3600); // 美东时间：11:00pm
		$datetime = $value[2] . '<br>' . substr($time, 0, 6) ; // 时间：11-02<br>11:00p
		$dataTotal[$value[0]] = [
			0 => $value[0],  // MID
			1 => $value[3],
			2 => $value[4],  // 联赛名称
			3 => '',         // 主队ID
			4 => '',         // 客队ID
			5 => $value[5],
			6 => $value[6],
			7 => stripos($value[12], '*') !== false ? 'C' : 'H',
			8 => str_replace('*', '', $value[12]),
			9 => $value[10]-0.01,
			10 => $value[11],
			11 => 'O' . $value[16],
			12 => 'U' . $value[16],
			13 => $value[18],
			14 => $value[17]-0.01,
			15 => 0,           // no1-不知是何意
			16 => 0,           // no2
			17 => 0,           // no3
			18 => $scores[0],  // 比分：主队
			19 => $scores[1],  // 比分：客队
			20 => $value[1],
			21 => stripos($value[28], '*') !== false ? 'C' : 'H',
			22 => str_replace('*', '', $value[28]), // 上半让球个数
			23 => $value[21],
			24 => $value[22],
			25 => 'O' . $value[29],
			26 => 'U' . $value[29],
			27 => $value[24],
			28 => $value[23],
			29 => 0,           // 主队红牌
			30 => 0,           // 客队红牌
			31 => '',          // 主队最后进球-196为空
			32 => '',          // 客队最后进球
			33 => $value[13],  // 主队独赢
			34 => $value[14],  // 客队独赢
			35 => $value[15],  // 和局
			36 => $value[25],  // 半场主队独赢
			37 => $value[26],  // 半场客队独赢
			38 => $value[27],  // 半场和局
			39 => '单',
			40 => '双',
			41 => $value[19],  // 单
			42 => $value[20],  // 双
			43 => '',          // 正网：视频ID；196：--
			44 => '',          // 正网：视频；196：--
			45 => '',          // 正网：perform；196：--
			46 => '',          // 正网：视频；196：--
			47 => $datetime,   // 日期：11-02<br>11:00p
			48 => '196^' . $value[3],   // 正网：1H^35:11；196：58
			49 => 0            // 正网：全部玩法；196：--
		];
	}
	return $dataTotal;
}

function refreshData($key,$datainfos){
	global $dbMasterLink,$flushWay;

	$res=true;
	if($key=="FT_M_ROU_EO"){

	    // 单独请求6686的角球、罚球盘口
        if($flushWay == 'ra686'){
            global $FT_RB_CORNERS_API;
            $curl = new Curl_HTTP_Client();
            $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
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
                foreach ($aGames as $row){
                    $datainfos[]=$row;
                }

            }
        }

//        @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
//        @error_log(json_encode($datainfos, JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');

        $start = 0;
        $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,ShowTypeRB,RB_Show,ECID,LID,ISRB,Neutral)VALUES";
        foreach ($datainfos as $k => $datainfo){
            if($datainfo['GID'] < 10000) {continue;}
            $LEAGUE=$datainfo['LEAGUE'];
            $TEAM_H=trim(str_replace('[中]','',$datainfo['TEAM_H'])); // 插入数据库的时候 去掉中，方便自动结算
            $TEAM_C=$datainfo['TEAM_C'];
            $GNUM_H=$datainfo['GNUM_H'];
            $GNUM_C=$datainfo['GNUM_C'];
            $STRONG=$datainfo['STRONG'];
            $RATIO_RE=$datainfo['RATIO_RE'];
            $IOR_REH=$datainfo['IOR_REH']>0?round_num($datainfo['IOR_REH']):'';
            $IOR_REC=$datainfo['IOR_REC']>0?round_num($datainfo['IOR_REC']):'';
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
            $IOR_ROUH=$datainfo['IOR_ROUH']>0?round_num($datainfo['IOR_ROUH']):'';    //客队全场赔率  13
            $IOR_ROUC=$datainfo['IOR_ROUC']>0?round_num($datainfo['IOR_ROUC']):'';    //主队全场赔率  14
            $HSTRONG=$datainfo['HSTRONG'];
            $RATIO_HRE=$datainfo['RATIO_HRE'];
            $IOR_HREH=$datainfo['IOR_HREH']>0?round_num($datainfo['IOR_HREH']):'';
            $IOR_HREC=$datainfo['IOR_HREC']>0?round_num($datainfo['IOR_HREC']):'';
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
            $IOR_HROUH=$datainfo['IOR_HROUH']>0?round_num($datainfo['IOR_HROUH']):'';  //半场得分小 客
            $IOR_HROUC=$datainfo['IOR_HROUC']>0?round_num($datainfo['IOR_HROUC']):'';  //半场得分大 主
            $SCORE_H=$datainfo['SCORE_H'];
            $SCORE_C=$datainfo['SCORE_C'];
            // MB_Card
            // TG_Card
            $REDCARD_H=$datainfo['REDCARD_H'];// MB_Red
            $REDCARD_C=$datainfo['REDCARD_C'];// TG_Red
            $IOR_RMH=$datainfo['IOR_RMH']>0?round_num($datainfo['IOR_RMH']):'';
            $IOR_RMC=$datainfo['IOR_RMC']>0?round_num($datainfo['IOR_RMC']):'';
            $IOR_RMN=$datainfo['IOR_RMN']>0?round_num($datainfo['IOR_RMN']):'';
            $IOR_HRMH=$datainfo['IOR_HRMH']>0?round_num($datainfo['IOR_HRMH']):'';
            $IOR_HRMC=$datainfo['IOR_HRMC']>0?round_num($datainfo['IOR_HRMC']):'';
            $IOR_HRMN=$datainfo['IOR_HRMN']>0?round_num($datainfo['IOR_HRMN']):'';
            $IOR_REOO=$datainfo['IOR_REOO']>0?round_num($datainfo['IOR_REOO']):'';
            $IOR_REOE=$datainfo['IOR_REOE']>0?round_num($datainfo['IOR_REOE']):'';
            $EVENTID=$datainfo['EVENTID'];
            $HOT=$datainfo['HOT'];
            $PLAY=$datainfo['PLAY'];
            $timestamp=$DATETIME=$datainfo['DATETIME'];
            $RETIMESET=$datainfo['RETIMESET'];
            $GID=$datainfo['GID'];
            $MT_GTYPE=$datainfo['MT_GTYPE'];
            $ECID=$datainfo['ECID'];
            $LID=$datainfo['LID'];
            $ISRB='Y'; // 返回的数据中没有这个字段，滚球赋值为 Y
            $Neutral=$datainfo['Neutral'];
            $m_date=explode(' ', $DATETIME)[0];
            $m_time=getMtime($DATETIME);

            // 将从正网拉取的测试数据过滤掉
            // stripos 查找字符串首次出现的位置（不区分大小写）
            $pos_m = stripos($LEAGUE, 'test'); // 查找联赛名称是否含有 test
            $pos_m_tw = stripos($LEAGUE, '测试'); // 查找联赛名称是否含有 测试
            $pos_mb = stripos($TEAM_H, 'test'); // 检查主队名称是否含有 test
            $pos_mb_tw = stripos($TEAM_H, '测试'); // 检查主队名称是否含有 测试
            $pos_tg = stripos($TEAM_C, 'test'); // 检查客队名称是否含有 test
            $pos_tg_tw = stripos($TEAM_C, '测试'); // 检查客队名称是否含有 测试
            if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                $pos_m_tw !== false || $pos_mb_tw !== false || $pos_tg_tw !== false){
                continue;
            }

            $checksql = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID` ='$GID'";
            $checkresult = mysqli_query($dbMasterLink,$checksql);
            $check=mysqli_num_rows($checkresult);

            if($check==0){

                if($start == 0) {
                    $insert_sql .= "('$GID','FT','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$STRONG','1','$ECID','$LID','$ISRB','$Neutral')" ;

                }else{
                    $insert_sql .= ",('$GID','FT','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','$STRONG','1','$ECID','$LID','$ISRB','$Neutral')" ;
                }
                $start++;
            }
            else{

//                $dataArray[$GID]=(array('FT',$timestamp,$m_date,$m_time,$LEAGUE,$TEAM_H,$TEAM_C,$STRONG,$RATIO_RE,$IOR_REH,$IOR_REC,$RATIO_ROUO,$RATIO_ROUU,$IOR_ROUH,
//                    $IOR_ROUC,$HSTRONG,$RATIO_HRE,$IOR_HREH,$IOR_HREC,$RATIO_HROUO,$RATIO_HROUU,$IOR_HROUH,$IOR_HROUC,'1',$ECID,$LID,$ISRB)); // 把数据放在二维数组里面

                // 更新，将所有需要的字段全部放到数据集
                $dataArray[$GID]=[
                    "FT","$timestamp","$m_date","$m_time","$LEAGUE","$TEAM_H","$TEAM_C","$STRONG", // 0-7
                    "$RATIO_RE","$IOR_REH","$IOR_REC", // 8-10
                    "$RATIO_ROUO","$RATIO_ROUU","$IOR_ROUH","$IOR_ROUC", // 11-14
                    "$HSTRONG","$RATIO_HRE","$IOR_HREH","$IOR_HREC", // 15-18
                    "$RATIO_HROUO","$RATIO_HROUU","$IOR_HROUH","$IOR_HROUC", // 19-22
                    "$SCORE_H","$SCORE_C","$REDCARD_H","$REDCARD_C", // 23-26
                    "$IOR_RMH","$IOR_RMC","$IOR_RMN","$IOR_HRMH","$IOR_HRMC","$IOR_HRMN", // 27-32
                    "$IOR_REOO","$IOR_REOE", // 33-34
                    "$HOT","$PLAY","$RETIMESET", // 35-37
                    "1","$ECID","$LID","$ISRB","$Neutral"// 38-42
                ];

/*


                $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeRB='$STRONG',M_LetB_RB='$RATIO_RE',MB_LetB_Rate_RB='$IOR_REH',TG_LetB_Rate_RB='$IOR_REC',MB_Dime_RB='$RATIO_ROUO',TG_Dime_RB='$RATIO_ROUU',
    MB_Dime_Rate_RB='$IOR_ROUC',TG_Dime_Rate_RB='$IOR_ROUH',ShowTypeHRB='$HSTRONG',M_LetB_RB_H='$RATIO_HRE',MB_LetB_Rate_RB_H='$IOR_HREH',TG_LetB_Rate_RB_H='$IOR_HREC',MB_Dime_RB_H='$RATIO_HROUO',TG_Dime_RB_H='$RATIO_HROUU',
    MB_Dime_Rate_RB_H='$IOR_HROUC',TG_Dime_Rate_RB_H='$IOR_HROUH',MB_Ball='$SCORE_H',TG_Ball='$SCORE_C',MB_Card='',TG_Card='',MB_Red='$REDCARD_H',TG_Red='$REDCARD_C',
    MB_Win_Rate_RB='$IOR_RMH',TG_Win_Rate_RB='$IOR_RMC',M_Flat_Rate_RB='$IOR_RMN',MB_Win_Rate_RB_H='$IOR_HRMH',TG_Win_Rate_RB_H='$IOR_HRMC',M_Flat_Rate_RB_H='$IOR_HRMN',S_Single_Rate_RB='$IOR_REOO',
    S_Double_Rate_RB='$IOR_REOE',Eventid='$EVENTID',Hot='$HOT',Play='$PLAY',M_Duration='$RETIMESET',RB_Show=1,S_Show=0,ECID='$ECID',LID='$LID',ISRB='$ISRB' where MID=$GID ";
                if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
            */
            }
        }
//        @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/logout_warn.log');
//        @error_log(json_encode($dataArray,JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/logout_warn.log');


        if($start>0){ // 有新增数据
//            echo $insert_sql;
            mysqli_query($dbMasterLink,$insert_sql) or die ("操作失敗!");
        }

//        die;

        $updateaccount =0 ; //用于判断是否有更新数据
        if(count($dataArray)>0){
            $e_sql='';
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
            $sdr_sql .="ShowTypeHRB = CASE MID ";
            $sthr_sql .="M_LetB_RB_H = CASE MID ";
            $mlh_sql .="MB_LetB_Rate_RB_H = CASE MID ";
            $mlrh_sql .="TG_LetB_Rate_RB_H = CASE MID ";
            $tlrh_sql .="MB_Dime_RB_H = CASE MID ";
            $mdh_sql .="TG_Dime_RB_H = CASE MID ";
            $tdh_sql .="TG_Dime_Rate_RB_H = CASE MID ";
            $tdrh_sql .="MB_Dime_Rate_RB_H = CASE MID ";
            $scoreh_sql .="MB_Ball = CASE MID "; // 23
            $scorec_sql .="TG_Ball = CASE MID ";
            $redcardh_sql .="MB_Red = CASE MID ";
            $redcardc_sql .="TG_Red = CASE MID ";
            $iorrmh_sql .="MB_Win_Rate_RB = CASE MID ";
            $iorrmc_sql .="TG_Win_Rate_RB = CASE MID ";
            $iorrmn_sql .="M_Flat_Rate_RB = CASE MID ";
            $iorhrmh_sql .="MB_Win_Rate_RB_H = CASE MID ";
            $iorhrmc_sql .="TG_Win_Rate_RB_H = CASE MID ";
            $iorhrmn_sql .="M_Flat_Rate_RB_H = CASE MID ";
            $iorreoo_sql .="S_Single_Rate_RB = CASE MID ";
            $iorreoe_sql .="S_Double_Rate_RB = CASE MID ";
            $hot_sql .="HOT = CASE MID ";
            $play_sql .="Play = CASE MID ";
            $mduration_sql .="M_Duration = CASE MID "; //37
            $rbshow_sql .="RB_Show = CASE MID ";
            $ecid_sql .="ECID = CASE MID ";
            $lid_sql .="LID = CASE MID ";
            $isrb_sql .="ISRB = CASE MID ";
            $Neutral_sql .="Neutral = CASE MID ";

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
                $scoreh_sql .= "WHEN $id THEN '$ordinal[23]' ";
                $scorec_sql .= "WHEN $id THEN '$ordinal[24]' ";
                $redcardh_sql .= "WHEN $id THEN '$ordinal[25]' ";
                $redcardc_sql .= "WHEN $id THEN '$ordinal[26]' ";
                $iorrmh_sql .= "WHEN $id THEN '$ordinal[27]' ";
                $iorrmc_sql .= "WHEN $id THEN '$ordinal[28]' ";
                $iorrmn_sql .= "WHEN $id THEN '$ordinal[29]' ";
                $iorhrmh_sql .= "WHEN $id THEN '$ordinal[30]' ";
                $iorhrmc_sql .= "WHEN $id THEN '$ordinal[31]' ";
                $iorhrmn_sql .= "WHEN $id THEN '$ordinal[32]' ";
                $iorreoo_sql .= "WHEN $id THEN '$ordinal[33]' ";
                $iorreoe_sql .= "WHEN $id THEN '$ordinal[34]' ";
                $hot_sql .= "WHEN $id THEN '$ordinal[35]' ";
                $play_sql .= "WHEN $id THEN '$ordinal[36]' ";
                $mduration_sql .= "WHEN $id THEN '$ordinal[37]' ";
                $rbshow_sql .= "WHEN $id THEN '$ordinal[38]' " ; // 拼接SQL语句
                $ecid_sql .= "WHEN $id THEN '$ordinal[39]' " ; // 拼接SQL语句
                $lid_sql .= "WHEN $id THEN '$ordinal[40]' " ; // 拼接SQL语句
                $isrb_sql .= "WHEN $id THEN '$ordinal[41]' " ; // 拼接SQL语句
                $Neutral_sql .= "WHEN $id THEN '$ordinal[42]' " ; // 拼接SQL语句

                $updateaccount++;

            }

            if($updateaccount>0){
                $sql .= $ty_sql.$e_sql.$m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$lm_sql.$e_sql.$mtw_sql.$e_sql.$ttw_sql.$e_sql.$stp_sql.$e_sql.$mr_sql.$e_sql.$mrr_sql.$e_sql.$trr_sql.
                    $e_sql.$mbd_sql.$e_sql.$tgd_sql.$e_sql.$tgr_sql.$e_sql.$mbr_sql.$e_sql.$sdr_sql.$e_sql.$sthr_sql.$e_sql.$mlh_sql.$e_sql.$mlrh_sql.$e_sql.$tlrh_sql.$e_sql.$mdh_sql.$e_sql.
                    $tdh_sql.$e_sql.$tdrh_sql.
                    $e_sql.$scoreh_sql.$e_sql.$scorec_sql.$e_sql.$redcardh_sql.$e_sql.$redcardc_sql.$e_sql.$iorrmh_sql.$e_sql.$iorrmc_sql.$e_sql.$iorrmn_sql.$e_sql.$iorhrmh_sql.$e_sql.$iorhrmc_sql.$e_sql.$iorhrmn_sql.$e_sql.$iorreoo_sql.$e_sql.$iorreoe_sql.
                    $e_sql.$hot_sql.$e_sql.$play_sql.$e_sql.$mduration_sql.
                    $e_sql.$rbshow_sql.$e_sql.$ecid_sql.$e_sql.$lid_sql.$e_sql.$isrb_sql.$e_sql.$Neutral_sql ;
                $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作

//                echo $sql ;
                mysqli_query($dbMasterLink,$sql) or die ("操作失敗!!");
            }

        }

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
		<TITLE>足球变数值</TITLE>
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
		switch ($rtype){
			case "FT_M_ROU_EO": 
				$reBallCountCur = 0;
				$page_size=60;
				echo "parent.retime=20;\n"; // 滚球倒计时刷新时间
				echo "parent.game_more=1;\n";
				echo "parent.str_more='多种玩法';\n";
				echo "parent.str_renew = '秒自动更新';\n";
				
				$gameVideoNow = $redisObj->getSimpleOne('gameVideoNow');
				$gameVideoNowArr = json_decode($gameVideoNow,true);
				$gameVideoFuture = $redisObj->getSimpleOne('gameVideoFuture');
				$gameVideoFutureArr = json_decode($gameVideoFuture,true);

				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}
				$gamecount =0 ;
				$page_count=ceil($cou/$page_size);
				echo "parent.t_page=$page_count;\n";
				for($i=0;$i<$cou;$i++){
//					$messages=$matches[$i];
//					$messages=str_replace(");",")",$messages);
//					$messages=str_replace("cha(9)","",$messages);
//                    $datainfo=eval("return $messages;");
                    $datainfo=$matches[$i];
					//if ($openrow['Open']==1){

                    $datainfo[8] = isset($datainfo[8])?$datainfo[8]:$datainfo['RATIO_RE'];     //让球数
                    $datainfo[9] = isset($datainfo[9])?$datainfo[9]:$datainfo['IOR_REH'];      //滚球主队让球的赔率
                    $datainfo[10] = isset($datainfo[10])?$datainfo[10]:$datainfo['IOR_REC'];   //滚球客队让球的赔率
                    $datainfo[13] = isset($datainfo[13])?$datainfo[13]:$datainfo['IOR_ROUH'];  //滚球客队全场赔率
                    $datainfo[14] = isset($datainfo[14])?$datainfo[14]:$datainfo['IOR_ROUC'];  //滚球主队全场赔率
                    $datainfo[22] = isset($datainfo[22])?$datainfo[22]:$datainfo['RATIO_HRE']; //半场滚球让球数
                    $datainfo[23] = isset($datainfo[23])?$datainfo[23]:$datainfo['IOR_HREH'];  //半场滚球主队让球的赔率
                    $datainfo[24] = isset($datainfo[24])?$datainfo[24]:$datainfo['IOR_HREC'];  //半场滚球客队让球的赔率
                    $datainfo[27] = isset($datainfo[27])?$datainfo[27]:$datainfo['IOR_HROUH']; //滚球客队半场小的赔率
                    $datainfo[28] = isset($datainfo[28])?$datainfo[28]:$datainfo['IOR_HROUC']; //滚球主队半场大的赔率
                    $datainfo[33] = isset($datainfo[33])?$datainfo[33]:$datainfo['IOR_RMH'];   //滚球主队独赢赔率
                    $datainfo[34] = isset($datainfo[34])?$datainfo[34]:$datainfo['IOR_RMC'];   //滚球客队独赢赔率
                    $datainfo[35] = isset($datainfo[35])?$datainfo[35]:$datainfo['IOR_RMN'];   //滚球和的赔率
                    $datainfo[36] = isset($datainfo[36])?$datainfo[36]:$datainfo['IOR_HRMH'];  //半场滚球主队独赢赔率
                    $datainfo[37] = isset($datainfo[37])?$datainfo[37]:$datainfo['IOR_HRMC'];  //半场滚球客队独赢赔率
                    $datainfo[38] = isset($datainfo[38])?$datainfo[38]:$datainfo['IOR_HRMN'];  //半场滚球和的赔率

                    // 电竞最后最后2分钟是否提前关闭
                    // 12分钟的赛事，上半场、下半场第5分钟开始 关闭赔率
                    // 10分钟的赛事，上半场、下半场第4分钟开始 关闭赔率
                    // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
                    // $datainfo[48];  2H^06:56
                    // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
                    $pos = strpos($datainfo['LEAGUE'],'电竞足球');
                    if ($pos === false){}
                    else{

                            $pos8minute = strpos($datainfo['LEAGUE'],'8分钟比赛');
                            if ($pos8minute===false){}
                            else{
                                $matchTotalMinites = 8;
                                $currentMinuteIn8 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                                $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                            }

                            $pos10minute = strpos($datainfo['LEAGUE'],'10分钟比赛');
                            if ($pos10minute===false){}
                            else{
                                $matchTotalMinites = 10;
                                $currentMinuteIn10 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                                $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                            }

                            $pos12minute = strpos($datainfo['LEAGUE'],'12分钟比赛');
                            if ($pos12minute===false){}
                            else{
                                $matchTotalMinites = 12;
                                $currentMinuteIn12 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                                $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                            }

                            $posYQminute = strpos($datainfo['LEAGUE'],'电竞邀请赛');
                            if ($posYQminute===false){}
                            else{
                                $matchTotalMinites = 12;
                                $currentMinuteIn12 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                                $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                            }

                            // 上半场
                            if(
                                ($matchTotalMinites==8 and $currentMinuteIn8>=3 and $retimeset0=='1H') or
                                ($matchTotalMinites==10 and $currentMinuteIn10>=4 and $retimeset0=='1H') or
                                ($matchTotalMinites==12 and $currentMinuteIn12>=5 and $retimeset0=='1H')
                            ){
                                // 半场大小
                                $datainfo[22]='';
                                // 半场让球
                                $datainfo[23]='';
                                $datainfo[24]='';
                                $datainfo[27]='';
                                $datainfo[28]='';
                                // 半场独赢
                                $datainfo[36]='';
                                $datainfo[37]='';
                                $datainfo[38]='';
                                // 所有玩法
                                $datainfo[49]='';
                            }

                            // 全场
                            if (
                                ($matchTotalMinites==8 and $currentMinuteIn8>=6 and $retimeset0=='2H') or
                                ($matchTotalMinites==10 and $currentMinuteIn10>=8 and $retimeset0=='2H') or
                                ($matchTotalMinites==12 and $currentMinuteIn12>=10 and $retimeset0=='2H')

                            ){
                                $datainfo[8]='';
                                $datainfo[22]='';
                                $datainfo[9]='';
                                $datainfo[10]='';
                                $datainfo[13]='';
                                $datainfo[14]='';
                                $datainfo[23]='';
                                $datainfo[24]='';
                                $datainfo[27]='';
                                $datainfo[28]='';
                                $datainfo[33]='';
                                $datainfo[34]='';
                                $datainfo[35]='';
                                $datainfo[36]='';
                                $datainfo[37]='';
                                $datainfo[38]='';
                                $datainfo[41]='';
                                $datainfo[42]='';
                                $datainfo[49]='';
                            }
                    }

						if ($datainfo[9]!=''){
									// 全场让球单独处理
									$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[9],$datainfo[10],100); // 默认都是香港盘
									$datainfo[9]=$ra_rate[0]; // 主队
									$datainfo[10]=$ra_rate[1]; // 客队
									$datainfo[9]=change_rate($open,$datainfo[9]);
									$datainfo[10]=change_rate($open,$datainfo[10]);
						}
						if ($datainfo[13]!=''){
									$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[13],$datainfo[14],100); // 默认都是香港盘
									$datainfo[13]=$ra_rate[0]; // 全场大小 大
									$datainfo[14]=$ra_rate[1]; // 全场大小 小
							$datainfo[13]=change_rate($open,$datainfo[13]);
							$datainfo[14]=change_rate($open,$datainfo[14]);
						}			
						if ($datainfo[23]!=''){
									// 半场让球单独处理
									$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[23],$datainfo[24],100); // 默认都是香港盘
									$datainfo[23]=$ra_rate[0]; // 主队
									$datainfo[24]=$ra_rate[1]; // 客队
									$datainfo[23]=change_rate($open,$datainfo[23]);
									$datainfo[24]=change_rate($open,$datainfo[24]);
						}
						if ($datainfo[28]!=''){
									$ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[28],$datainfo[27],100); // 默认都是香港盘
									$datainfo[28]=$ra_rate[0]; // 半场大小 大
									$datainfo[27]=$ra_rate[1]; // 半场大小 小
							$datainfo[28]=change_rate($open,$datainfo[28]);
							$datainfo[27]=change_rate($open,$datainfo[27]);
						}
						
						if ($datainfo[33]!=''){
							$datainfo[33]=change_rate($open,$datainfo[33]);
						}
						if ($datainfo[34]!=''){
							$datainfo[34]=change_rate($open,$datainfo[34]);
						}
						if ($datainfo[35]!=''){
							$datainfo[35]=change_rate($open,$datainfo[35]);
						}
						if ($datainfo[36]!=''){
							$datainfo[36]=change_rate($open,$datainfo[36]);
						}
						if ($datainfo[37]!=''){
							$datainfo[37]=change_rate($open,$datainfo[37]);
						}
						if ($datainfo[38]!=''){
							$datainfo[38]=change_rate($open,$datainfo[38]);
						}

						$datainfo[41]=change_rate($open,$datainfo[41]);
						$datainfo[42]=change_rate($open,$datainfo[42]);
						$show=0;
						$allMethods=$datainfo['MORE']<5 ? 0:$datainfo['MORE'];
						
						if($datainfo['STRONG']=="H"){
							$ratio_mb_str=$datainfo['RATIO_RE'];
							$ratio_tg_str='';
						}elseif($datainfo['STRONG']=="C"){
							$ratio_mb_str='';
							$ratio_tg_str=$datainfo['RATIO_RE'];
						}
						if($datainfo['HSTRONG']=="H"){
							$hratio_mb_str=$datainfo['RATIO_HRE'];
							$hratio_tg_str='';
						}elseif($datainfo['HSTRONG']=="C"){
							$hratio_mb_str='';
							$hratio_tg_str=$datainfo['RATIO_HRE'];
						}

						$datainfo['TEAM_H']=str_replace("[Mid]","<font color='#005aff'>[N]</font>",$datainfo['TEAM_H']);
						$datainfo['TEAM_H']=str_replace("[中]","<font color='#005aff'>[中]</font>",$datainfo['TEAM_H']);
                    $newDataArray[$datainfo['GID']]['gid']=$datainfo['GID'];
                    $newDataArray[$datainfo['GID']]['timer'] =$datainfo['TIMER'];
                    $newDataArray[$datainfo['GID']]['dategh']=$date.$datainfo['GNUM_H'];
                    $newDataArray[$datainfo['GID']]['datetimelove']=$datainfo['DATETIME'];
                    $newDataArray[$datainfo['GID']]['league']=$datainfo['LEAGUE'];
                    $newDataArray[$datainfo['GID']]['gnum_h']=$datainfo['GNUM_H'];
                    $newDataArray[$datainfo['GID']]['gnum_c']=$datainfo['GNUM_C'];
                    if ($datainfo['Neutral']==1){
                        $newDataArray[$datainfo['GID']]['team_h']=$datainfo['TEAM_H']." <font color='#005aff'>[中]</font>";
                    }else{
                        $newDataArray[$datainfo['GID']]['team_h']=$datainfo['TEAM_H'];
                    }
                    $newDataArray[$datainfo['GID']]['team_h_for_sort']=explode(' -',$datainfo['TEAM_H'])[0];
                    $newDataArray[$datainfo['GID']]['team_c']=$datainfo['TEAM_C'];
                    $newDataArray[$datainfo['GID']]['strong']=$datainfo['STRONG'];
                    $newDataArray[$datainfo['GID']]['ratio']=$datainfo['RATIO_RE']=$datainfo[8];
                    $newDataArray[$datainfo['GID']]['ratio_mb_str']=$ratio_mb_str;
                    $newDataArray[$datainfo['GID']]['ratio_tg_str']=$ratio_tg_str;
                    $newDataArray[$datainfo['GID']]['ior_RH']=$datainfo['IOR_REH']=$datainfo[9];
                    $newDataArray[$datainfo['GID']]['ior_RC']=$datainfo['IOR_REC']=$datainfo[10];
                    $newDataArray[$datainfo['GID']]['bet_RH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_H']}&strong={$datainfo['STRONG']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['bet_RC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_C']}&strong={$datainfo['STRONG']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['ratio_o']=$datainfo['RATIO_ROUO'];
                    $newDataArray[$datainfo['GID']]['ratio_u']=$datainfo['RATIO_ROUU'];
                    $newDataArray[$datainfo['GID']]['ratio_o_str']="大".str_replace('O','',$datainfo['RATIO_ROUO']);
                    $newDataArray[$datainfo['GID']]['ratio_u_str']="小".str_replace('U','',$datainfo['RATIO_ROUU']);
                    $newDataArray[$datainfo['GID']]['ior_OUH']=$datainfo['IOR_ROUH']=$datainfo[13]; // 全场大小 客队
                    $newDataArray[$datainfo['GID']]['ior_OUC']=$datainfo['IOR_ROUC']=$datainfo[14]; // 全场大小 主队
                    $newDataArray[$datainfo['GID']]['bet_OUH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_H']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['bet_OUC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_C']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['no1']=$datainfo['NO1'];
                    $newDataArray[$datainfo['GID']]['no2']=$datainfo['NO2'];
                    $newDataArray[$datainfo['GID']]['no3']=$datainfo['NO3'];
                    $newDataArray[$datainfo['GID']]['score_h']=$datainfo['SCORE_H'];    //  主 比分
                    $newDataArray[$datainfo['GID']]['score_c']=$datainfo['SCORE_C'];    //  客 比分
                    $newDataArray[$datainfo['GID']]['hgid']  =$datainfo['HGID'];
                    $newDataArray[$datainfo['GID']]['hstrong']=$datainfo['HSTRONG'];
                    $newDataArray[$datainfo['GID']]['hratio'] =$datainfo['RATIO_HRE']=$datainfo[22];
                    $newDataArray[$datainfo['GID']]['hratio_mb_str']=$hratio_mb_str;
                    $newDataArray[$datainfo['GID']]['hratio_tg_str']=$hratio_tg_str;
                    $newDataArray[$datainfo['GID']]['ior_HRH']=$datainfo['IOR_HREH']=$datainfo[23];
                    $newDataArray[$datainfo['GID']]['ior_HRC']=$datainfo['IOR_HREC']=$datainfo[24];
                    $newDataArray[$datainfo['GID']]['hratio_o']=$datainfo['RATIO_ROUHO'];
                    $newDataArray[$datainfo['GID']]['hratio_u']=$datainfo['RATIO_ROUHU'];
                    $newDataArray[$datainfo['GID']]['hratio_o_str']="大".str_replace('O','',$datainfo['RATIO_HROUO']);
                    $newDataArray[$datainfo['GID']]['hratio_u_str']="小".str_replace('U','',$datainfo['RATIO_HROUU']);
                    $newDataArray[$datainfo['GID']]['ior_HOUH']=$datainfo['IOR_HROUH']=$datainfo[27]; // 半场小 客队
                    $newDataArray[$datainfo['GID']]['ior_HOUC']=$datainfo['IOR_HROUC']=$datainfo[28]; // 半场大 主队
                    $newDataArray[$datainfo['GID']]['redcard_h']=$datainfo['REDCARD_H'];
                    $newDataArray[$datainfo['GID']]['redcard_c']=$datainfo['REDCARD_C'];
                    $newDataArray[$datainfo['GID']]['lastestscore_h'] =$datainfo['LASTESTSCORE_H'];
                    $newDataArray[$datainfo['GID']]['lastestscore_c'] =$datainfo['LASTESTSCORE_C'];
                    $newDataArray[$datainfo['GID']]['ior_MH']=$datainfo['IOR_RMH']=$datainfo[33];
                    $newDataArray[$datainfo['GID']]['ior_MC']=$datainfo['IOR_RMC']=$datainfo[34];
                    $newDataArray[$datainfo['GID']]['ior_MN']=$datainfo['IOR_RMN']=$datainfo[35];
                    $newDataArray[$datainfo['GID']]['ior_HMH']=$datainfo['IOR_HRMH']=$datainfo[36];
                    $newDataArray[$datainfo['GID']]['ior_HMC']=$datainfo['IOR_HRMC']=$datainfo[37];
                    $newDataArray[$datainfo['GID']]['ior_HMN']=$datainfo['IOR_HRMN']=$datainfo[38];
                    $newDataArray[$datainfo['GID']]['bet_MH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_H']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['bet_MC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_C']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['bet_MN']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=N&gnum={$datainfo['GNUM_C']}&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['str_odd']=$o;
                    $newDataArray[$datainfo['GID']]['str_even']=$e;
                    $newDataArray[$datainfo['GID']]['ior_EOO']=$datainfo['IOR_REOO']>0?$datainfo['IOR_REOO']:'';
                    $newDataArray[$datainfo['GID']]['ior_EOE']=$datainfo['IOR_REOE']>0?$datainfo['IOR_REOE']:'';
                    $newDataArray[$datainfo['GID']]['bet_EOO']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&rtype=RODD&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['bet_EOE']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&rtype=REVEN&langx={$langx}";
                    $newDataArray[$datainfo['GID']]['eventid']=$datainfo['EVENTID'];
                    $newDataArray[$datainfo['GID']]['hot']=$datainfo['HOT']=$datainfo[44];
                    $newDataArray[$datainfo['GID']]['play']=$datainfo['PLAY']=$datainfo[46];
                    $newDataArray[$datainfo['GID']]['datetime']=$datainfo['DATETIME']=$datainfo[47];
                    $newDataArray[$datainfo['GID']]['retimeset']=$datainfo['RETIMESET'];
                    $newDataArray[$datainfo['GID']]['more']=$show;
                    $newDataArray[$datainfo['GID']]['all']=$allMethods;
                    if(in_array($datainfo[43],$gameVideoNowArr)){
                        $newDataArray[$datainfo['GID']]['event']='on';
                    }elseif(in_array($datainfo[43],$gameVideoFutureArr)){
                        $newDataArray[$datainfo['GID']]['event']='out';
                    }else{
                        $newDataArray[$datainfo['GID']]['event']='no';
                    }

                    $tmpset=explode("^", $datainfo['RETIMESET']); // 足球滚球的倒计时

						$tmpset[1]=str_replace("<font style=background-color=red>","",$tmpset[1]);
						$tmpset[1]=str_replace("</font>","",$tmpset[1]);
						$showretime="";
						if($tmpset[0]=="Start"){
								$showretime="-";
						}else if($tmpset[0]=="MTIME" || $tmpset[0]=="196"){
							$showretime=$tmpset[1];
						}else{
							if($tmpset[0]=="1H"){$showretime="上  ".$tmpset[1]."'";}
							if($tmpset[0]=="2H"){$showretime="下  ".$tmpset[1]."'";}
                            if($tmpset[0]=="HT"){$showretime=$tmpset[1];}
						}
						$newDataArray[$datainfo['GID']]['showretime']=$showretime;
						$K=$K+1;
						if ($gmid==''){
							$gmid=$datainfo['GID'];
						}else{
							$gmid=$gmid.','.$datainfo['GID'];
						}
					//}
				}

                // 足球滚球盘口按照时间排序
                foreach ($newDataArray as $key => $match){
                    // 转换时间 01:35a  -》  01:35:00
                    // 转换时间 01:35p  -》  13:35:00
                    $match['datetime_sort'] = str_replace('<br>', ' ', $match['datetime']); //02-28 01:35a
                    $sAorP = substr($match['datetime_sort'],11);
                    $match['datetime_sort'] = date('Y-m-d H:i:s',strtotime(date('Y').'-'.substr($match['datetime_sort'],0, -1)));
                    if ($sAorP=='p'){
                        $match['datetime_sort'] = date('Y-m-d H:i:s',strtotime($match['datetime_sort'])+43200);
                    }
                    $newDataArray[$key]['datetime_sort'] = $match['datetime_sort'];
                }
//                $newDataArray = array_sort($newDataArray,'gid',$type='asc');
                $newDataArray = array_sort($newDataArray,'datetime_sort',$type='asc');

                // 按照队伍，gid分组
                $newDataArray = array_values(group_same_key($newDataArray,'team_h_for_sort'));
                foreach ($newDataArray as $k => $v){
                    $val_sort = array_sort($v,'gid',$type='asc');
                    foreach ($val_sort as $k2=>$v2){
                        $newDataArray2[] = $v2;
                    }
                }
                $newDataArray = $newDataArray2;
				echo "parent.gamount=$gamecount;\n"; // 总数量
				$reBallCountCur = $cou;
				$listTitle="足球：滾球";
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

            function showOpenLive() {
                var url = "../../member/live/live_max.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
                top.tvwin = window.open(url,"win","width=805,height=526,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
            }
            
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
