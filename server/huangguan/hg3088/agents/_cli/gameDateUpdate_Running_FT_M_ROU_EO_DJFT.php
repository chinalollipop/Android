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
$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();
$matches = "";
$rtype = "FT_M_ROU_EO";
$flag = $redisObj->getSimpleOne($rtype."_FLAG");
$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷水渠道

if($flag != 1) {
	$redisObj->setOne($rtype."_FLAG","1");
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

//获取滚球独赢大小单双数据
function FT_M_ROU_EO(){
    global $flushWay;
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
    }else{
        global $langx,$accoutArr,$dbMasterLink,$redisObj;
        $result='';
        $curl = new Curl_HTTP_Client();
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
            if( $value['cookie'] =='' ){
                $dateCur = date('Y-m-d',time());
                $curl->set_cookie("gamePoint_21059363={$dateCur}%2A0%2A0; gamePoint_21059364={$dateCur}%2A0%2A0; gamePoint_21059365={$dateCur}%2A0%2A0; gamePoint_21059366={$dateCur}%2A2%2A0; gamePoint_21059367={$dateCur}%2A2%2A0; gamePoint_21059368={$dateCur}%2A2%2A0; gamePoint_21059369={$dateCur}%2A2%2A0;");
            }else{
                $curl->set_cookie($value['cookie']);
            }
            $curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=$langx&mtype=4");
            $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=re&uid=".$value['Uid']."&langx=$langx&mtype=4");
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
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a,$b,$html_data);
            preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
            /*echo '<pre>';
            print_r($matches[0]);
            echo '<br/>';
            echo '<br/>';*/
            if(is_array($matches[0]) && $matches[0][0]!=''){
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
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']!=1){ unset($result[$i]);}
        }
        $redisObj->setOne("FT_M_ROU_EO",json_encode($result));
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

function refreshData($key,$matches){
	global $dbMasterLink;
	$cou = is_array($matches)&&count($matches) > 0 ? count($matches) : 0;
	$res=true;
	if($key=="FT_M_ROU_EO"){
        for($i=0;$i<$cou;$i++){
            $messages=$matches[$i];
            $messages=str_replace(");",")",$messages);
            $messages=str_replace("cha(9)","",$messages);
            $datainfo=eval("return $messages;");
            $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
            $openresult = mysqli_query($dbMasterLink,$opensql);
            $openrow=mysqli_fetch_assoc($openresult);
            if($openrow['Open']==1){
                //赔率开关
                if(ODDS_REPAIR_SWITCH==1 && ($datainfo[41]>0 || $datainfo[42]>0)){
                    $EO_ior = get_other_ioratio( "H", $datainfo[41]*1-1 , $datainfo[42]*1-1 , 100);
                    $ior_EOO = $EO_ior[0]*1+1;
                    $ior_EOE = $EO_ior[1]*1+1;
                    $S_Single_Rate_RB = $ior_EOO;
                    $S_Double_Rate_RB = $ior_EOE;
                }else{
                    $S_Single_Rate_RB = $datainfo[41];
                    $S_Double_Rate_RB = $datainfo[42];
                }

                $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',S_Single_Rate_RB='$S_Single_Rate_RB',S_Double_Rate_RB='$S_Double_Rate_RB',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',M_Duration='$datainfo[48]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='FT'";
                if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
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
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
                    $datainfo=eval("return $messages;");
					//if ($openrow['Open']==1){

                    // 电竞最后最后2分钟是否提前关闭
                    // 12分钟的赛事，上半场、下半场第5分钟开始 关闭赔率
                    // 10分钟的赛事，上半场、下半场第4分钟开始 关闭赔率
                    // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
                    // $datainfo[48];  2H^06:56
                    // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
                    $pos = strpos($datainfo[2],'电竞足球');
                    if ($pos === false){}
                    else{

                            $pos8minute = strpos($datainfo[2],'8分钟比赛');
                            if ($pos8minute===false){}
                            else{
                                $matchTotalMinites = 8;
                                $currentMinuteIn8 = explode(':',explode('^',$datainfo[48])[1])[0];
                                $retimeset0 = explode('^',$datainfo[48])[0];
                            }

                            $pos10minute = strpos($datainfo[2],'10分钟比赛');
                            if ($pos10minute===false){}
                            else{
                                $matchTotalMinites = 10;
                                $currentMinuteIn10 = explode(':',explode('^',$datainfo[48])[1])[0];
                                $retimeset0 = explode('^',$datainfo[48])[0];
                            }

                            $pos12minute = strpos($datainfo[2],'12分钟比赛');
                            if ($pos12minute===false){}
                            else{
                                $matchTotalMinites = 12;
                                $currentMinuteIn12 = explode(':',explode('^',$datainfo[48])[1])[0];
                                $retimeset0 = explode('^',$datainfo[48])[0];
                            }

                            $posYQminute = strpos($datainfo[2],'电竞邀请赛');
                            if ($posYQminute===false){}
                            else{
                                $matchTotalMinites = 12;
                                $currentMinuteIn12 = explode(':',explode('^',$datainfo[48])[1])[0];
                                $retimeset0 = explode('^',$datainfo[48])[0];
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
						$allMethods=$datainfo[49]<5 ? 0:$datainfo[49];
						
						if($datainfo[7]=="H"){
							$ratio_mb_str=$datainfo[8];
							$ratio_tg_str='';
							$hratio_mb_str=$datainfo[22];
							$hratio_tg_str='';
						}elseif($datainfo[7]=="C"){
							$ratio_mb_str='';
							$ratio_tg_str=$datainfo[8];
							$hratio_mb_str='';
							$hratio_tg_str=$datainfo[22];
						}
						$datainfo[5]=str_replace("[Mid]","<font color='#005aff'>[N]</font>",$datainfo[5]);
						$datainfo[5]=str_replace("[中]","<font color='#005aff'>[中]</font>",$datainfo[5]);
                    $pos = strpos($datainfo[2],'电竞足球');
                    $pos_zh_tw = strpos($datainfo[2],'電競足球');
                    if ($pos === false){}
                    else{
                        continue;
                    }
                    if ($pos_zh_tw === false){}
                    else{
                        continue;
                    }
						$newDataArray[$datainfo[0]]['gid']=$datainfo[0];   
						$newDataArray[$datainfo[0]]['timer'] =$datainfo[1];
						$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
                        $newDataArray[$datainfo[0]]['datetimelove']=$datainfo[47];
						$newDataArray[$datainfo[0]]['league']=$datainfo[2];
						$newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
						$newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
						$newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
                        $newDataArray[$datainfo[0]]['team_h_for_sort']=explode(' -',$datainfo[5])[0];
						$newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
						$newDataArray[$datainfo[0]]['strong']=$datainfo[7];
						$newDataArray[$datainfo[0]]['ratio']=$datainfo[8];
						$newDataArray[$datainfo[0]]['ratio_mb_str']=$ratio_mb_str;
						$newDataArray[$datainfo[0]]['ratio_tg_str']=$ratio_tg_str;
						$newDataArray[$datainfo[0]]['ior_RH']=$datainfo[9];
						$newDataArray[$datainfo[0]]['ior_RC']=$datainfo[10];
						$newDataArray[$datainfo[0]]['bet_RH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[3]}&strong={$datainfo[7]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['bet_RC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[4]}&strong={$datainfo[7]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['ratio_o']=$datainfo[11];
						$newDataArray[$datainfo[0]]['ratio_u']=$datainfo[12];
						$newDataArray[$datainfo[0]]['ratio_o_str']="大".str_replace('O','',$datainfo[11]);
						$newDataArray[$datainfo[0]]['ratio_u_str']="小".str_replace('U','',$datainfo[12]);
						$newDataArray[$datainfo[0]]['ior_OUH']=$datainfo[13];
						$newDataArray[$datainfo[0]]['ior_OUC']=$datainfo[14];
						$newDataArray[$datainfo[0]]['bet_OUH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[3]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['bet_OUC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[4]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['no1']=$datainfo[15];
						$newDataArray[$datainfo[0]]['no2']=$datainfo[16];
						$newDataArray[$datainfo[0]]['no3']=$datainfo[17];
						$newDataArray[$datainfo[0]]['score_h']=$datainfo[18];
						$newDataArray[$datainfo[0]]['score_c']=$datainfo[19];
						$newDataArray[$datainfo[0]]['hgid']  =$datainfo[20];
						$newDataArray[$datainfo[0]]['hstrong']=$datainfo[21];
						$newDataArray[$datainfo[0]]['hratio'] =$datainfo[22];
						$newDataArray[$datainfo[0]]['hratio_mb_str']=$hratio_mb_str;
						$newDataArray[$datainfo[0]]['hratio_tg_str']=$hratio_tg_str;
						$newDataArray[$datainfo[0]]['ior_HRH']=$datainfo[23];
						$newDataArray[$datainfo[0]]['ior_HRC']=$datainfo[24];
						$newDataArray[$datainfo[0]]['hratio_o']=$datainfo[25];
						$newDataArray[$datainfo[0]]['hratio_u']=$datainfo[26];
						$newDataArray[$datainfo[0]]['hratio_o_str']="大".str_replace('O','',$datainfo[25]);
						$newDataArray[$datainfo[0]]['hratio_u_str']="小".str_replace('U','',$datainfo[26]);
						$newDataArray[$datainfo[0]]['ior_HOUH']=$datainfo[27];
						$newDataArray[$datainfo[0]]['ior_HOUC']=$datainfo[28];
						$newDataArray[$datainfo[0]]['redcard_h']=$datainfo[29];
						$newDataArray[$datainfo[0]]['redcard_c']=$datainfo[30];
						$newDataArray[$datainfo[0]]['lastestscore_h'] =$datainfo[31];
						$newDataArray[$datainfo[0]]['lastestscore_c'] =$datainfo[32];
						$newDataArray[$datainfo[0]]['ior_MH']=$datainfo[33];
						$newDataArray[$datainfo[0]]['ior_MC']=$datainfo[34];
						$newDataArray[$datainfo[0]]['ior_MN']=$datainfo[35];
						$newDataArray[$datainfo[0]]['ior_HMH']=$datainfo[36];
						$newDataArray[$datainfo[0]]['ior_HMC']=$datainfo[37];
						$newDataArray[$datainfo[0]]['ior_HMN']=$datainfo[38];
						$newDataArray[$datainfo[0]]['bet_MH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[3]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['bet_MC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[4]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['bet_MN']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=N&gnum={$datainfo[4]}&langx={$langx}";
						$newDataArray[$datainfo[0]]['str_odd']=$o;
						$newDataArray[$datainfo[0]]['str_even']=$e;
						$newDataArray[$datainfo[0]]['ior_EOO']=$datainfo[41];
						$newDataArray[$datainfo[0]]['ior_EOE']=$datainfo[42];
						$newDataArray[$datainfo[0]]['bet_EOO']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&rtype=RODD&langx={$langx}";
						$newDataArray[$datainfo[0]]['bet_EOE']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&rtype=REVEN&langx={$langx}";
						$newDataArray[$datainfo[0]]['eventid']=$datainfo[43];
						$newDataArray[$datainfo[0]]['hot']=$datainfo[44];
						$newDataArray[$datainfo[0]]['play']=$datainfo[46];
						$newDataArray[$datainfo[0]]['datetime']=$datainfo[47];
						$newDataArray[$datainfo[0]]['retimeset']=$datainfo[48];
						$newDataArray[$datainfo[0]]['more']=$show;
						$newDataArray[$datainfo[0]]['all']=$allMethods;		
						if(in_array($datainfo[43],$gameVideoNowArr)){
							$newDataArray[$datainfo[0]]['event']='on';	
						}elseif(in_array($datainfo[43],$gameVideoFutureArr)){
							$newDataArray[$datainfo[0]]['event']='out';	
						}else{
							$newDataArray[$datainfo[0]]['event']='no';	
						}
						
						$tmpset=explode("^", $datainfo[48]);
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
						}
						$newDataArray[$datainfo[0]]['showretime']=$showretime;
						$K=$K+1;
						if ($gmid==''){
							$gmid=$datainfo[0];
						}else{
							$gmid=$gmid.','.$datainfo[0];
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
		$filesName=strtolower("Running".$open.$rtype.'_DJFT').time().".html";
		$info=ob_get_contents();  
		$file = $dir.$filesName;
		$handle = fopen($file, 'w+');
		fwrite($handle, $info);
		fclose($handle);
		ob_end_clean();
		unset($future_r_data);
		unset($newDataArray);
		$redis_error = $redisObj->setOne($rtype.'_DJFT'.'_'.$open.'_URL',$filesName);
}
?>
