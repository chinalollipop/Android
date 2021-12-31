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
if(in_array('BKQ3',$gameArr)){
    $mem_bkq3_off = 'off';
}
$isClosedH1 = in_array('BKH1', $gameArr); // 是否关闭篮球滚球上半场20200111

$showtypeArr = array('FTRB','FTFT','FTFU','BKRB','BKFT','BKFU');
if(!in_array($gtype.$showtype,$showtypeArr)){
	echo json_encode(array('status'=>-2,'msg'=>"参数不合法！"));
}

//判断赛事是否存在
if(strlen($gid)>6 && intval($gid)>100000){
	$result = mysqli_query($dbLink,"SELECT MID FROM `".DBPREFIX."match_sports` WHERE MID=".$gid);
	$row = mysqli_fetch_assoc($result);
	$cou=mysqli_num_rows($result);
	if($cou!=1){
		echo json_encode(array('status'=>-4,'msg'=>"赛事不存在！"));
	}	
}else{
	echo json_encode(array('status'=>-3,'msg'=>"参数不合法！"));
}

$midLockSet='';

$midLockCheck = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid");
$cou=mysqli_num_rows($midLockCheck);
if($cou==0)	$midLockSet = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more_midlock(`MID`)VALUES({$gid})");
if($midLockSet || $cou==1){
	//先读取redis 如果redis不存在则重新抓取数据存入数据库redis
	$redisObj = new Ciredis();
	
	/*echo $gid."_reflush_time";
	echo '<br/>';*/
	$valReflushTime = $redisObj->getSimpleOne($gid."_reflush_time");
	/*echo '<pre>';
	var_dump($valReflushTime);
	echo '<pre>';
	var_dump(date('Y-m-d H:i:s',$valReflushTime));
	echo '<br/>';*/
	
	if($valReflushTime){//存在数据,更新数据库，redis
		 	//echo 'exit data read<br/>';
			if($showtype=="RB"){
				$reflushTime=10;
			}elseif($showtype=="FU"){
				$reflushTime=30;
			}else{
				$reflushTime=60;
			} 
			
			/*echo '<pre>';
			var_dump($reflushTime);
			echo '<br/>';*/
			//print_r(time()-$valReflushTime);
			//echo '<br/><br/>';
			if( time()-$valReflushTime > $reflushTime){ //数据过期,重新抓取更新数据库,redis
				//echo "out date re get<br/>";
				$dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid);
				if($dataNew['tmp_Obj']&&count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary']&&count($dataNew['gid_ary'])>0 ){
					$tmp_Obj=$dataNew['tmp_Obj'];
					$gid_ary=$dataNew['gid_ary'];
					$updateSt = $redisObj->getSET($gid."_reflush_time",time());
					//var_dump($updateSt);
					if($updateSt){
						//echo '1515151515<br/>';
						$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
						if($begin){
							$lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
							$details = json_encode($tmp_Obj);
							$details=str_replace('\'','',$details);	
							$setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
							//echo '<br/>';
							//var_dump($lockMid);
							//var_dump($setGames);
							if($lockMid->num_rows==1 && $setGames){
								$comStatus = mysqli_query($dbMasterLink,"COMMIT");
								//var_dump($comStatus);
								if($comStatus){
									$redisObj->getSET($gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis
									echo json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary));
									exit();		
								}	
							}else{
								mysqli_query($dbMasterLink,"ROLLBACK");
							}
						}
					}
				}else{
					//echo "in date get redis<br/>";
					$redisObj->delete($gid."_reflush_time");
					echo json_encode(array('status'=>-1,'msg'=>"数据为空！"));
					exit();
				}
			}else{//不更新数据
				$games = $redisObj->getSimpleOne($gid);//在redis取出数据
				echo $games;
				exit();	
			}					
	}else{//不存在数据：接口抓取数据，存入数据库，redis
		//echo 'no data get<br/>';
		$dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid);
		//var_dump($dataNew);
		if( $dataNew['tmp_Obj'] && count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary'] && count($dataNew['gid_ary'])>0 ){
			//echo '7878888888888888888888<br/>';
			$tmp_Obj=$dataNew['tmp_Obj'];
			$gid_ary=$dataNew['gid_ary'];
			$rtStatus=$redisObj->setOne($gid."_reflush_time",time());//写入刷新时间
			//var_dump($rtStatus);
			if($rtStatus){
				$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
				if($begin){
						$lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
						//echo '<br/>566666<br/>';
						//var_dump($lockMid);
						$details = json_encode($tmp_Obj);
						$details=str_replace('\'','',$details);	
						$exitResult = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more where MID=".$gid);
						$exitsNum = mysqli_fetch_assoc($exitResult);
						if(isset($exitsNum['MID'])==$exitsNum['MID']){//更新
							$setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
						}else{//存入
							$setGames = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");	
						}
						//echo '<br/>55555555<br/>';
						//var_dump($setGames);
						if($lockMid->num_rows==1 && $setGames){
							//echo 'success<br/>';
							$comStatus = mysqli_query($dbMasterLink,"COMMIT");
							if($comStatus){
								//echo 'commit<br/>';
								$redisObj->setOne($gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis
								echo json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary));
								exit();		
							}	
						}else{
							mysqli_query($dbMasterLink,"ROLLBACK");
						}
				}
			}	
		}else{
			$redisObj->delete($gid."_reflush_time");
			echo json_encode(array('status'=>-1,'msg'=>"数据为空！"));
			exit();
		}	
	}	
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



?>