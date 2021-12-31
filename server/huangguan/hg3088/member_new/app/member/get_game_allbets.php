<?php
session_start();
error_reporting(1);
ini_set('display_errors','Off');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "include/address.mem.php";
require ("include/config.inc.php");
require_once("../../../common/sportCenterData.php");
require ("include/curl_http.php");
require ("include/define_function_list.inc.php");

$tmp_Obj=$gid_ary=array();

$gid   = $_REQUEST['gid'];
$langx= $_SESSION['langx'];
$uid   = $_REQUEST['uid'];
$gtype = $_REQUEST['gtype'];
$showtype = $_REQUEST['showtype'];
require ("include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

// 会员篮球滚球投注开关
if(strpos($_SESSION['gameSwitch'],'|')>0){
    $gameArr=explode('|',$_SESSION['gameSwitch']);
}else{
    if(strlen($_SESSION['gameSwitch'])>0){
        $gameArr[]=$_SESSION['gameSwitch'];
    }else{
        $gameArr=array();
    }
}
if(in_array('BKQ3',$gameArr)){ // 是否停用篮球滚球第3节
    $mem_bkq3_off = 'off';
}
$isClosedH1 = in_array('BKH1', $gameArr); // 是否关闭篮球滚球上半场20200111

$showtypeArr = array('FTRB','FTFT','FTFU','BKRB','BKFT','BKFU');
if(!in_array($gtype.$showtype,$showtypeArr)){
	echo json_encode(array('status'=>-2,'msg'=>"参数不合法！"));
}

//判断赛事是否存在
if(strlen($gid)>6 && intval($gid)>100000){
	$result = mysqli_query($dbLink,"SELECT MID,ECID,LID,ISRB FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID=".$gid);
	$row = mysqli_fetch_assoc($result);
	$cou=mysqli_num_rows($result);
	if($cou!=1){
		echo json_encode(array('status'=>-4,'msg'=>"赛事不存在！"));
	}	
}else{
	echo json_encode(array('status'=>-3,'msg'=>"参数不合法！"));
}

$ecid=$row['ECID'];
$lid=$row['LID'];
$isrb=$row['ISRB']=='Y'?$row['ISRB']:'N';
$midLockSet='';
$midLockCheck = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid");
$cou=mysqli_num_rows($midLockCheck);
if($cou==0)	$midLockSet = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more_midlock(`MID`)VALUES({$gid})");
if($midLockSet || $cou==1){
	$redisObj = new Ciredis();
	$valReflushTime = $redisObj->getSimpleOne($gid."_reflush_time");
	if($valReflushTime){ //存在赛事,更新数据库，redis
		 	//echo 'exit data read<br/>';
//         $moregamedata = getDataFromInterface($langx,$gtype,$showtype,$gid) ;
//        var_dump($moregamedata['tmp_Obj']);
//        echo($moregamedata['tmp_Obj'][$gid]['ior_HOUHO']);
			if($showtype=="RB"){ $reflushTime=5;}elseif($showtype=="FU"){$reflushTime=10;}else{$reflushTime=20;}
			if( time()-$valReflushTime > $reflushTime ){ //数据过期,重新抓取更新数据库,redis
				//echo "out date re get<br/>";
				$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
				$lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
				$valReflushTime1 = $redisObj->getSimpleOne($gid."_reflush_time");
				if(time()-$valReflushTime1 > $reflushTime){
					if($begin&&$lockMid->num_rows==1){
						$dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,$ecid,$lid,$isrb);
						if($dataNew['tmp_Obj']&&count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary']&&count($dataNew['gid_ary'])>0 ){
							$tmp_Obj=$dataNew['tmp_Obj'];
							$gid_ary=$dataNew['gid_ary'];
							$updateSt = $redisObj->getSET($gid."_reflush_time",time());
							if($updateSt){
								$details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
								$details=str_replace('\'','',$details);	
								$setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
								if($setGames){
									$comStatus = mysqli_query($dbMasterLink,"COMMIT");
									if($comStatus){
										$redisObj->getSET("gameMore_".$gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis
                                        // 电竞足球
                                        $isCloseDJFT = closeDJFT($tmp_Obj[$gid]);
                                        if ($isCloseDJFT==1){}
                                        else{
                                        	if ($gtype=='FT'){
												$tmp_Obj_gid[$gid] = current($tmp_Obj);
												$tmp_Obj=$tmp_Obj_gid;
											}
                                            echo json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary));
                                            exit();
                                        }
									}	
								}
							}
						}
					}
					$redisObj->delete($gid."_reflush_time");
					mysqli_query($dbMasterLink,"ROLLBACK");
					echo json_encode(array('status'=>-1,'msg'=>"数据为空！"));
					exit();
				}
			}
			//echo "in date <br/>";
			$games = $redisObj->getSimpleOne("gameMore_".$gid);//在redis取出数据
            $arr = json_decode($games, true);
            // 电竞足球
            $isCloseDJFT = closeDJFT($arr['tmp_Obj'][$gid]);
            if ($isCloseDJFT==1){
                echo json_encode(array('status'=>-1,'msg'=>"数据为空！！！"));
                exit();
            }else{
                echo $games;
                exit();
            }
	}else{//不存在赛事：接口抓取数据，存入数据库，redis
		//echo 'new data<br/>';
		$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
		$lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
		$valReflushTime2 = $redisObj->getSimpleOne($gid."_reflush_time");
		if(!$valReflushTime2){
			if($begin && $lockMid->num_rows==1 ){
				$dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,$ecid,$lid,$isrb);
				if( $dataNew['tmp_Obj'] && count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary'] && count($dataNew['gid_ary'])>0 ){
					$tmp_Obj=$dataNew['tmp_Obj'];
					$gid_ary=$dataNew['gid_ary'];
					$rtStatus=$redisObj->setOne($gid."_reflush_time",time());//写入刷新时间
					if($rtStatus){
						$details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
						$details=str_replace('\'','',$details);
						$exitResult = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more where MID=".$gid);
						$exitsNum = mysqli_fetch_assoc($exitResult);
						if(isset($exitsNum['MID'])==$exitsNum['MID']){//更新
							$setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
						}else{//存入
							$setGames = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
						}
						if($setGames){
							$comStatus = mysqli_query($dbMasterLink,"COMMIT");
							if($comStatus){
								$redisObj->setOne("gameMore_".$gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis
                                // 电竞足球
                                $isCloseDJFT = closeDJFT($tmp_Obj[$gid]);
                                if ($isCloseDJFT==1){
                                    echo json_encode(array('status'=>-1,'msg'=>"数据为空！！"));
                                    exit();
								}
                                else{
									if ($gtype=='FT'){
										$tmp_Obj_gid[$gid] = current($tmp_Obj);
										$tmp_Obj=$tmp_Obj_gid;
									}
                                    echo json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary));
                                    exit();
                                }
							}	
						}
						$redisObj->delete($gid."_reflush_time");
					}	
				}
			}
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo json_encode(array('status'=>-1,'msg'=>"数据为空！！"));
			exit();
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");
			$games = $redisObj->getSimpleOne("gameMore_".$gid);//在redis取出数据
            $arr = json_decode($games, true);
            // 电竞足球
            $isCloseDJFT = closeDJFT($arr['tmp_Obj'][$gid]);
            if ($isCloseDJFT==1){
                echo json_encode(array('status'=>-1,'msg'=>"数据为空！！！"));
                exit();
            }else{
                echo $games;
                exit();
            }
		}
	}	
}else{
	echo json_encode(array('status'=>-1,'msg'=>"数据为空！！！"));
	exit();	
}

function getGameDate($Datasite,$param){
		$curl = new Curl_HTTP_Client();
		$curl->store_cookies("cookies.txt");
		$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
		$curl->set_referrer($Datasite);
		$gameDataXml = $curl->send_post_data("".$Datasite."/app/member/get_game_allbets.php?",$param,"",10);
		$xml= xmlToArray(trim($gameDataXml));
		return $xml; 
}

function closeDJFT($v2){

    $isRBpos = strpos($v2['re_time'],'^');
    if ($isRBpos===false){}
    else{
        // 电竞最后最后2分钟是否提前关闭
        // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
        // 10分钟的电竞盘口   上半场第4分钟开始关闭赔率，下半场第8分钟开始关闭赔率
        // 12分钟的电竞盘口   上半场第5分钟开始关闭赔率，下半场第10分钟开始关闭赔率
        // $datainfo[48];  2H^06:56
        // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
        $pos = strpos($v2['league'],'电竞足球');
        if ($pos === false){}
        else{
            $pos8minute = strpos($v2['league'],'8分钟比赛');
            if ($pos8minute===false){}
            else{
                $matchTotalMinites = 8;
                $currentMinuteIn8 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $pos10minute = strpos($v2['league'],'10分钟比赛');
            if ($pos10minute===false){}
            else{
                $matchTotalMinites = 10;
                $currentMinuteIn10 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $pos12minute = strpos($v2['league'],'12分钟比赛');
            if ($pos12minute===false){}
            else{
                $matchTotalMinites = 12;
                $currentMinuteIn12 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $posYQminute = strpos($v2['league'],'电竞邀请赛');
            if ($posYQminute===false){}
            else{
                $matchTotalMinites = 12;
                $currentMinuteIn12 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            if (
                ($matchTotalMinites==8 and $currentMinuteIn8>=3 and $retimeset0=='1H') or
                ($matchTotalMinites==8 and $currentMinuteIn8>=6 and $retimeset0=='2H') or
                ($matchTotalMinites==10 and $currentMinuteIn10>=4 and $retimeset0=='1H') or
                ($matchTotalMinites==10 and $currentMinuteIn10>=8 and $retimeset0=='2H') or
                ($matchTotalMinites==12 and $currentMinuteIn12>=5 and $retimeset0=='1H') or
                ($matchTotalMinites==12 and $currentMinuteIn12>=10 and $retimeset0=='2H')

            ){
                return 1;
            }
        }
    }
}

?>
