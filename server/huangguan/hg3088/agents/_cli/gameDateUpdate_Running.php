<?php
/**
* 数据刷新滚球
*/

//ini_set("display_errors", "On");
define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/curl_http.php";
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require(CONFIG_DIR."/app/agents/include/define_function.php");

$langx="zh-cn";
$nums_bill_ids= 0;
$per_num_each_thread= 0;
$bill_ids=array();
$mrow = array();
$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();

	$bill_ids = array("FT_M_ROU_EO","BK_M_ROU_EO","FT_T","FT_F","FT_PD","FT_HPD"); 	
	$nums_bill_ids = count($bill_ids);
	if($nums_bill_ids==0){
		echo "赛事没有注单！！！";
		exit();	
	}
	
	$per_num_each_thread = 1;
	$worker_num = $nums_bill_ids;
	for($i=0;$i<$worker_num ; $i++){
		$process = new swoole_process("getRunningDataByMethod", true);
		$pid = $process->start();
		$process->write($i);
		$workers[] = $process;
	}


function getRunningDataByMethod(swoole_process $worker) {
	global $per_num_each_thread,$bill_ids,$database;
	$i = $worker->read();
	//log_note("------------------------------------------------------------------------------------\r\n");
	
    $start_point = $i * $per_num_each_thread;
    $end_point = ($i+1) * $per_num_each_thread;

	$BillArray = array();
	if( isset($bill_ids[$start_point]) && !empty($bill_ids[$start_point]) ) {
		for($finger=$start_point;$finger<$end_point;$finger++) {
			if( isset($bill_ids[$finger]) && !empty($bill_ids[$finger]) ) {
				$BillArray[] = $bill_ids[$finger];
			}
		}
	}
	
	if(empty($BillArray)) {
		echo "没有需要更新的数据！";
		return true;
		exit();
	}
	
	//这里一定要重新连接，每个swoole里面的链接都需要重新的连下
	$connMysql = mysqli_connect($database['gameDefault']['host'], $database['gameDefault']['user'], $database['gameDefault']['password'], $database['gameDefault']['dbname'], $database['gameDefault']['port']);
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	mysqli_query($connMysql, "SET NAMES 'utf8'");
	$begin = mysqli_query($connMysql,"start transaction");//开启事务$from
	$lockResult = mysqli_query($connMysql,"select status from ".DBPREFIX."match_sports_running_lock where `Type` = '".$BillArray[0]."' for update");
	$lockRow=mysqli_fetch_assoc($lockResult);
	if($begin&&$lockResult){
		if($lockRow['status']==0){
				//$lockUpdteResult = mysqli_query($connMysql,"update ".DBPREFIX."match_sports_running_lock set status=1  where `Type` = '".$key."' for update");//放在什么位置
				$matches=$BillArray[0]();
				$dataRes =refreshData($BillArray[0],$matches,$connMysql);
			if($dataRes ){
				$redisObj = new Ciredis();
				$setResult=$redisObj->setOne($BillArray[0],json_encode($matches));
				if($setResult){
					ob_start(); //打开缓冲区  
					/*echo '<pre>';
					echo 'SUCCESS:_';
					echo count($matches)."_";
					echo $BillArray[0]."_";
					echo date('Y-m-d H:i:s',time());
					echo '<br/>';
					$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
					ob_clean(); //关闭缓冲区
					*/
					log_note($info."\r\n");
					mysqli_query($connMysql,"COMMIT");	exit;
				}  	
			}
		}		
	}		
	
	/*ob_start(); //打开缓冲区  
	echo '<pre>';
	echo 'FAIL:';
	echo date('Y-m-d H:i:s',time());
	echo '<br/>';
	$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info */
	
	mysqli_query($connMysql,"ROLLBACK");
	exit();
}

//获取滚球独赢大小单双数据
function FT_M_ROU_EO(){
	global $langx,$accoutArr;
	//echo "<br/>1&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
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
	return $result;
}

function BK_M_ROU_EO(){
	global $langx,$accoutArr;
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
    return $result;
}

//获取滚球波胆数据
function FT_PD(){
	global $langx,$accoutArr;
	//echo "<br/>2&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=$langx&mtype=4");
		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=rpd&uid=".$value['Uid']."&langx=$langx&mtype=4");
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
	return $result;
}

//获取滚球半场波胆数据
function FT_HPD(){
	global $langx,$accoutArr;
	//echo "<br/>3&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=$langx&mtype=4");
		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=hrpd&uid=".$value['Uid']."&langx=$langx&mtype=4");
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
	return $result;
}

//获取滚球总入球数据
function FT_T(){
	global $langx,$accoutArr;
	//echo "<br/>4&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=re&uid=".$value['Uid']."&langx=$langx&mtype=4");
		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=rt&uid=".$value['Uid']."&langx=$langx&mtype=4");
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
	return $result;
}

//获取半场/全场数据
function FT_F(){
	global $langx,$accoutArr;
	//echo "<br/>5&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt"); 
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
		$curl->set_referrer("".$value['Datasite']."/app/member/FT_browse/index.php?rtype=rb&uid=".$value['Uid']."&langx=$langx&mtype=4");
		$html_data=$curl->fetch_url("".$value['Datasite']."/app/member/FT_browse/body_var.php?rtype=rf&uid=".$value['Uid']."&langx=$langx&mtype=4");
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
	return $result;
}

function refreshData($key,$matches,$connMysql){
	$cou = is_array($matches)&&count($matches) > 0 ? count($matches) : 0;
	$res=true;
	if($key=="FT_M_ROU_EO"){
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
			$openresult = mysqli_query($connMysql,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
			if ($openrow['Open']==1){
				$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',S_Single_Rate_RB='$datainfo[41]',S_Double_Rate_RB='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',M_Duration='$datainfo[48]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='FT'";
				if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
			}
		}	
	}elseif($key=="BK_M_ROU_EO"){
		 for($i=0;$i<$cou;$i++){
	        $messages=$matches[$i];
	        $messages=str_replace(");",")",$messages);
	        $messages=str_replace("cha(9)","",$messages);
	        $datainfo=eval("return $messages;");
	        $opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID='$datainfo[0]' and `Type`='BK' and `Cancel`=0";
	        $openresult = mysqli_query($connMysql,$opensql);
	        $openrow=mysqli_fetch_assoc($openresult);
	        if($openrow['Open']==1){
	            $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Win_Rate='$datainfo[29]', TG_Win_Rate='$datainfo[30]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[35]',MB_Dime_RB_S_H='$datainfo[36]',TG_Dime_RB_H='$datainfo[39]',TG_Dime_RB_S_H='$datainfo[40]',MB_Dime_Rate_RB_H='$datainfo[37]',MB_Dime_Rate_RB_S_H='$datainfo[38]',TG_Dime_Rate_RB_H='$datainfo[41]',TG_Dime_Rate_RB_S_H='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',M_Duration='$M_duration',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
	            if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
	        }
	    }
	}elseif($key=="FT_HPD"){
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$midNew = $datainfo[0]-1;
			$opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID=$midNew and `Type`='FT' and `Cancel`=0";
			$openresult = mysqli_query($connMysql,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID=$midNew";
				$resultrb = mysqli_query($connMysql,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,HRMB1TG0,HRMB2TG0,HRMB0TG1,HRMB0TG2,HRMB2TG1,HRMB1TG2,HRMB3TG0,HRMB0TG3,HRMB3TG1,HRMB1TG3,HRMB3TG2,HRMB2TG3,HRMB4TG0,HRMB0TG4,HRMB4TG1,HRMB1TG4,HRMB4TG2,HRMB2TG4,HRMB4TG3,HRMB3TG4,HRMB0TG0,HRMB1TG1,HRMB2TG2,HRMB3TG3,HRMB4TG4,HRUP5)VALUES('$midNew','$datainfo[8]','$datainfo[9]','$datainfo[24]','$datainfo[25]','$datainfo[10]','$datainfo[26]','$datainfo[11]','$datainfo[27]','$datainfo[12]','$datainfo[28]','$datainfo[13]','$datainfo[29]','$datainfo[14]','$datainfo[30]','$datainfo[15]','$datainfo[31]','$datainfo[16]','$datainfo[32]','$datainfo[17]','$datainfo[33]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]')";
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}	
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set HRMB1TG0='$datainfo[8]',HRMB2TG0='$datainfo[9]',HRMB0TG1='$datainfo[24]',HRMB0TG2='$datainfo[25]',HRMB2TG1='$datainfo[10]',HRMB1TG2='$datainfo[26]',HRMB3TG0='$datainfo[11]',HRMB0TG3='$datainfo[27]',HRMB3TG1='$datainfo[12]',HRMB1TG3='$datainfo[28]',HRMB3TG2='$datainfo[13]',HRMB2TG3='$datainfo[29]',HRMB4TG0='$datainfo[14]',HRMB0TG4='$datainfo[30]',HRMB4TG1='$datainfo[15]',HRMB1TG4='$datainfo[31]',HRMB4TG2='$datainfo[16]',HRMB2TG4='$datainfo[32]',HRMB4TG3='$datainfo[17]',HRMB3TG4='$datainfo[33]',HRMB0TG0='$datainfo[18]',HRMB1TG1='$datainfo[19]',HRMB2TG2='$datainfo[20]',HRMB3TG3='$datainfo[21]',HRMB4TG4='$datainfo[22]',HRUP5='$datainfo[23]' where MID=$midNew";	
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}	
			    }
			}
		}
	}elseif($key=="FT_PD"){
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$opensql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
			$openresult = mysqli_query($connMysql,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($connMysql,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
		    	if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RMB1TG0,RMB2TG0,RMB0TG1,RMB0TG2,RMB2TG1,RMB1TG2,RMB3TG0,RMB0TG3,RMB3TG1,RMB1TG3,RMB3TG2,RMB2TG3,RMB4TG0,RMB0TG4,RMB4TG1,RMB1TG4,RMB4TG2,RMB2TG4,RMB4TG3,RMB3TG4,RMB0TG0,RMB1TG1,RMB2TG2,RMB3TG3,RMB4TG4,RUP5)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[24]','$datainfo[25]','$datainfo[10]','$datainfo[26]','$datainfo[11]','$datainfo[27]','$datainfo[12]','$datainfo[28]','$datainfo[13]','$datainfo[29]','$datainfo[14]','$datainfo[30]','$datainfo[15]','$datainfo[31]','$datainfo[16]','$datainfo[32]','$datainfo[17]','$datainfo[33]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]')";
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RMB1TG0='$datainfo[8]',RMB2TG0='$datainfo[9]',RMB0TG1='$datainfo[24]',RMB0TG2='$datainfo[25]',RMB2TG1='$datainfo[10]',RMB1TG2='$datainfo[26]',RMB3TG0='$datainfo[11]',RMB0TG3='$datainfo[27]',RMB3TG1='$datainfo[12]',RMB1TG3='$datainfo[28]',RMB3TG2='$datainfo[13]',RMB2TG3='$datainfo[29]',RMB4TG0='$datainfo[14]',RMB0TG4='$datainfo[30]',RMB4TG1='$datainfo[15]',RMB1TG4='$datainfo[31]',RMB4TG2='$datainfo[16]',RMB2TG4='$datainfo[32]',RMB4TG3='$datainfo[17]',RMB3TG4='$datainfo[33]',RMB0TG0='$datainfo[18]',RMB1TG1='$datainfo[19]',RMB2TG2='$datainfo[20]',RMB3TG3='$datainfo[21]',RMB4TG4='$datainfo[22]',RUP5='$datainfo[23]' where MID=$datainfo[0]";	
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
			    }
			}
		}
	}elseif($key=="FT_T"){
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$opensql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
			$openresult = mysqli_query($connMysql,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($connMysql,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RS_0_1,RS_2_3,RS_4_6,RS_7UP)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]')";
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RS_0_1='$datainfo[8]',RS_2_3='$datainfo[9]',RS_4_6='$datainfo[10]',RS_7UP='$datainfo[11]' where MID=$datainfo[0]";	
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
			    }
			}
		}
	}elseif($key=="FT_F"){
		for($i=0;$i<$cou;$i++){
			$messages=$matches[$i];
			$messages=str_replace(");",")",$messages);
			$messages=str_replace("cha(9)","",$messages);
			$datainfo=eval("return $messages;");
			$opensql = "select Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
			$openresult = mysqli_query($connMysql,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($connMysql,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RMBMB,RMBFT,RMBTG,RFTMB,RFTFT,RFTTG,RTGMB,RTGFT,RTGTG)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]')";
				if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RMBMB='$datainfo[8]',RMBFT='$datainfo[9]',RMBTG='$datainfo[10]',RFTMB='$datainfo[11]',RFTFT='$datainfo[12]',RFTTG='$datainfo[13]',RTGMB='$datainfo[14]',RTGFT='$datainfo[15]',RTGTG='$datainfo[16]' where MID=$datainfo[0]";	
					if(!mysqli_query($connMysql,$sql)){ $res=false;break;}
			    }
			}
		}
	}
	return $res;
}

function log_note($info) {	
	//ob_start(); //打开缓冲区  
	//var_dump($database);
	//$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
	//ob_clean(); //关闭缓冲区
	//log_note($info."\r\n");
	$dir = dirname(__FILE__);	
	$file = $dir."/running".date("ymd").".txt";
	$handle = fopen($file, 'a+');
	fwrite($handle, $info);
	fclose($handle);
}

?>
