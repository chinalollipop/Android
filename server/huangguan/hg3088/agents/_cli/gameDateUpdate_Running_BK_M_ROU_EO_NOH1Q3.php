<?php
/**
 * 数据刷新滚球
 *
 * 同时去掉篮球滚球上半场和篮球滚球第三节
 * Date: 2020/01/10
 */
if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");
}

define("CONFIG_DIR", dirname(dirname(__FILE__)));
define("COMMON_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/curl_http.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require_once(COMMON_DIR."/common/sportCenterData.php");
require CONFIG_DIR."/app/agents/include/define_function_list.inc.php";

$langx="zh-cn";
$uid='';
$showtype='';
$Mtype='';
$page_no=0;
$nums_bill_ids= 0;
$per_num_each_thread= 0;
$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();
$matches = "";
$rtype = "BK_M_ROU_EO";
$flag = $redisObj->getSimpleOne($rtype."_FLAG");
$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷新渠道

if($flag != 1) {
	$redisObj->setOne($rtype."_FLAG","1");
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
//                    $matches = $redisObj->getSimpleOne($rtype); 刷不到水
                    $matches = BK_M_ROU_EO();
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
    global $flushWay;
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
    }else{
        global $langx,$accoutArr,$dbMasterLink,$redisObj;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("/tmp/cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
            $curl->set_referrer("".$value['Datasite']."/app/member/BK_browse/index.php?rtype=re_all&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
            $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/BK_browse/body_var.php?rtype=re_all&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
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
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a,$b,$html_data);
            preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
            if(is_array($matches[0]) && $matches[0]!=''){
                $cou=sizeof($matches[0]);
            }else{
                $cou=0;
            }
            if($cou>0){
                $result = $matches[0];
                break;
            }
        }
        for($i=0;$i<$cou;$i++){
            $messages=$result[$i];
            $messages=str_replace(");",")",$messages);
            $messages=str_replace("cha(9)","",$messages);
            $datainfo=eval("return $messages;");
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID='$datainfo[0]' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']!=1){ unset($result[$i]); }
        }
        //$redisObj->setOne('BK_M_ROU_EO',json_encode($result)); 不能覆盖正常值
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
	$cou = is_array($matches)&&count($matches) > 0 ? count($matches) : 0;
	$res=true;
	if($key=="BK_M_ROU_EO"){
		 for($i=0;$i<$cou;$i++){
	        $messages=$matches[$i];
	        $messages=str_replace(");",")",$messages);
	        $messages=str_replace("cha(9)","",$messages);
	        $datainfo=eval("return $messages;");
	        $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID='$datainfo[0]' and `Type`='BK' and `Cancel`=0";
	        $openresult = mysqli_query($dbMasterLink,$opensql);
	        $openrow=mysqli_fetch_assoc($openresult);
	        if($openrow['Open']==1){
	        	$M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】
	            $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Win_Rate='$datainfo[29]', TG_Win_Rate='$datainfo[30]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[35]',MB_Dime_RB_S_H='$datainfo[36]',TG_Dime_RB_H='$datainfo[39]',TG_Dime_RB_S_H='$datainfo[40]',MB_Dime_Rate_RB_H='$datainfo[37]',MB_Dime_Rate_RB_S_H='$datainfo[38]',TG_Dime_Rate_RB_H='$datainfo[41]',TG_Dime_Rate_RB_S_H='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',MB_Ball='$datainfo[53]',TG_Ball='$datainfo[54]',M_Duration='$M_duration',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
	            if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
	        }
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

				for($i=0;$i<$cou;$i++) {
                    $messages = $matches[$i];
                    $messages = str_replace(");", ")", $messages);
                    $messages = str_replace("cha(9)", "", $messages);
                    $datainfo = eval("return $messages;");
                    $MID=$datainfo[0];
                    $datainfos[$MID]=$datainfo;
                    $gamecount++;
                }

                // 同时关闭篮球上半场（指所有带括号的半场、第三节、第四节等）、篮球第三节（篮球从第三节结束，也就是第四节开始时不允许下注，下半场倒计时10分钟）-20200111
                foreach ($datainfos as $MID => $datainfo){
                    if(strpos($datainfo[5], '-') !== false || strpos($datainfo[6], '-') !== false ||
                        $datainfo[52]=='Q3' ||
                        $datainfo[52]=='H2' ||
                        ($datainfo[52]=='HT' and $datainfo[56]<=1190) ||
                        $datainfo[52]=='Q4' || $datainfo[52]=='OT'){

                        $datainfos[$MID]=array();
                        $datainfos[$MID][0] = $datainfo[0];
                        $datainfos[$MID][1] = $datainfo[1];
                        $datainfos[$MID][2] = $datainfo[2];
                        $datainfos[$MID][3] = $datainfo[3];
                        $datainfos[$MID][4] = $datainfo[4];
                        $datainfos[$MID][5] = $datainfo[5];
                        $datainfos[$MID][6] = $datainfo[6];
                        $datainfos[$MID][7] = $datainfo[7];
                        $datainfos[$MID][47] = $datainfo[47];
                        $datainfos[$MID][48] = $datainfo[48];
                        $datainfos[$MID][52] = $datainfo[52]; // Q1 第一节 Q2 第二节 Q3 第三节 Q4 第四节 H1 上半场 H2 下半场 OT 加时 HT 半场
                        $datainfos[$MID][56] = $datainfo[56];
                        $datainfos[$MID][53] = $datainfo[53];
                        $datainfos[$MID][54] = $datainfo[54];

                        // 其他盘口，赔率等投注信息不显示（无视美式足球）
                        if (strpos($datainfo[2],'美式足球')===false){

                            if (isset($datainfos[$MID+7])){
                                if ($datainfo[2]==$datainfos[$MID+7][2] and $datainfo[5]==$datainfos[$MID+7][5] and $datainfo[6]==$datainfos[$MID+7][6]){
                                    $datainfos[$MID+7]=array();
                                    $datainfos[$MID+7][0] = $datainfo[0];
                                    $datainfos[$MID+7][1] = $datainfo[1];
                                    $datainfos[$MID+7][2] = $datainfo[2];
                                    $datainfos[$MID+7][3] = $datainfo[3];
                                    $datainfos[$MID+7][4] = $datainfo[4];
                                    $datainfos[$MID+7][5] = $datainfo[5];
                                    $datainfos[$MID+7][6] = $datainfo[6];
                                    $datainfos[$MID+7][7] = $datainfo[7];
                                    $datainfos[$MID+7][47] = $datainfo[47];
                                    $datainfos[$MID+7][48] = $datainfo[48];
                                    $datainfos[$MID+7][52] = $datainfo[52];
                                    $datainfos[$MID+7][56] = $datainfo[56];
                                    $datainfos[$MID+7][53] = $datainfo[53];
                                    $datainfos[$MID+7][54] = $datainfo[54];
                                }
                            }
                            if (isset($datainfos[$MID+14])) {
                                if ($datainfo[2] == $datainfos[$MID + 14][2] and $datainfo[5] == $datainfos[$MID + 14][5] and $datainfo[6] == $datainfos[$MID + 14][6]) {
                                    $datainfos[$MID+14]=array();
                                    $datainfos[$MID+14][0] = $datainfo[0];
                                    $datainfos[$MID+14][1] = $datainfo[1];
                                    $datainfos[$MID+14][2] = $datainfo[2];
                                    $datainfos[$MID+14][3] = $datainfo[3];
                                    $datainfos[$MID+14][4] = $datainfo[4];
                                    $datainfos[$MID+14][5] = $datainfo[5];
                                    $datainfos[$MID+14][6] = $datainfo[6];
                                    $datainfos[$MID+14][7] = $datainfo[7];
                                    $datainfos[$MID+14][47] = $datainfo[47];
                                    $datainfos[$MID+14][48] = $datainfo[48];
                                    $datainfos[$MID+14][52] = $datainfo[52];
                                    $datainfos[$MID+14][56] = $datainfo[56];
                                    $datainfos[$MID+14][53] = $datainfo[53];
                                    $datainfos[$MID+14][54] = $datainfo[54];
                                }
                            }
                        }

                    }
                }

                // 篮球滚球盘口默认按照时间排序
                foreach ($datainfos as $key => $match){
                    // 转换时间 02-28<br>01:35a  -》  2019-02-28 01:35:00
                    // 转换时间 02-28<br>01:35p  -》  2019-02-28 13:35:00
                    $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a
                    $sAorP = substr($match[100],11);
                    $match[100] = date('Y-m-d H:i:s',strtotime('2019-'.substr($match[100],0, -1)));
                    if ($sAorP == 'p'){
                        $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
                    }
                    $datainfos[$key][100] = $match[100];
                }
                $datainfos = array_sort($datainfos,0,$type='desc');
                $datainfos = array_values(array_sort($datainfos,100,$type='asc'));

                foreach ($datainfos as $MID => $datainfo){
//					$M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】
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
                        $mbTeamArr = explode('-', $datainfo[5]);
                        preg_match('/\d+/', $mbTeamArr[1], $mbTeamArrList);
                        if ($mbTeamArrList[0] == 2) {
                            $team_active = '第二节';
                            $newDataArray[$MID]['headShow'] = 0;
                        } elseif ($mbTeamArrList[0] == 3) {
                            $team_active = '第三节';
                            $newDataArray[$MID]['headShow'] = 0;
                        } elseif ($mbTeamArrList[0] == 4) {
                            $team_active = '第四节';
                            $newDataArray[$MID]['headShow'] = 0;
                        } else {
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
                        }
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
						$newDataArray[$MID]['ratio']=$datainfo[8];
						$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
						$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
						$newDataArray[$MID]['ior_RH']=$datainfo[9];
						$newDataArray[$MID]['ior_RC']=$datainfo[10];
						$newDataArray[$MID]['ratio_o']=$datainfo[11];
						$newDataArray[$MID]['ratio_u']=$datainfo[12];
						$newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$datainfo[11]);
						$newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$datainfo[12]);
						$newDataArray[$MID]['ior_OUH']=$datainfo[14];
						$newDataArray[$MID]['ior_OUC']=$datainfo[13];
						$newDataArray[$MID]['ior_EOO']=$datainfo[15];
						$newDataArray[$MID]['ior_EOE']=$datainfo[16];
//						$newDataArray[$MID]['ratio_ouho']=$datainfo[35];
//						$newDataArray[$MID]['ratio_ouhu']=$datainfo[36];
//						$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$datainfo[35]);
//						$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$datainfo[36]);
//						$newDataArray[$MID]['ior_OUHO']=$datainfo[37];
//						$newDataArray[$MID]['ior_OUHU']=$datainfo[38];
                        unset($newDataArray[$MID]['ratio_ouho']);
                        unset($newDataArray[$MID]['ratio_ouhu']);
                        unset($newDataArray[$MID]['ratio_ouho_str']);
                        unset($newDataArray[$MID]['ratio_ouhu_str']);
                        unset($newDataArray[$MID]['ior_OUHO']);
                        unset($newDataArray[$MID]['ior_OUHU']);
//						$newDataArray[$MID]['ratio_ouco']=$datainfo[39];
//						$newDataArray[$MID]['ratio_oucu']=$datainfo[40];
//						$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$datainfo[39]);
//						$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$datainfo[40]);
//						$newDataArray[$MID]['ior_OUCO']=$datainfo[41];
//						$newDataArray[$MID]['ior_OUCU']=$datainfo[42];
                        unset($newDataArray[$MID]['ratio_ouco']);
                        unset($newDataArray[$MID]['ratio_oucu']);
                        unset($newDataArray[$MID]['ratio_ouco_str']);
                        unset($newDataArray[$MID]['ratio_oucu_str']);
                        unset($newDataArray[$MID]['ior_OUCO']);
                        unset($newDataArray[$MID]['ior_OUCU']);
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
						$newDataArray[$MID]['all']=$datainfo[49];
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
		$filesName=strtolower("Running".$open.$rtype.'_NOH1Q3').time().".html";
		$info=ob_get_contents();
		$file = $dir.$filesName;
		$handle = fopen($file, 'w+');
		fwrite($handle, $info);
		fclose($handle);
		ob_end_clean();
		unset($future_r_data);
		unset($newDataArray);
		$redis_error = $redisObj->setOne($rtype.'_NOH1Q3'.'_'.$open.'_URL',$filesName);
}
?>
