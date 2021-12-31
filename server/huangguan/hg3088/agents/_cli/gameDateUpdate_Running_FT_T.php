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
$rtype = "FT_T";
$flag = $redisObj->getSimpleOne($rtype."_FLAG");

if($flag != 1) {
	$redisObj->setOne($rtype."_FLAG","1");
	
	mysqli_query($dbMasterLink, "SET NAMES 'utf8'");
	$begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
	$lockResult = mysqli_query($dbMasterLink,"select status from ".DBPREFIX."match_sports_running_lock where `Type` = '".$rtype."' for update");
	$lockRow=mysqli_fetch_assoc($lockResult);
	if($begin&&$lockResult){
		if($lockRow['status']==0){
			$matches=$rtype();
			$dataRes =refreshData($rtype,$matches);
			if($dataRes ){
				$setResult=$redisObj->setOne($rtype,json_encode($matches));
				if($setResult){
					mysqli_query($dbMasterLink,"COMMIT");

					$opens = array("A","B","C","D"); 	
					$worker_num = count($opens);
					if(CREAT_STATIC_PAGES){
                        for($i=0;$i<$worker_num; $i++){
                            $process = new swoole_process("createHtml", true);
                            $pid = $process->start();
                            $process->write($i);
                        }
                    }

				} 
			}
		}
	}
	@mysqli_query($dbMasterLink,"ROLLBACK");
	$redisObj->setOne($rtype."_FLAG", "0");
	echo "主进程执行完毕！";
}else {
	exit("有进程在执行，退出！");
}

//获取滚球总入球数据
function FT_T(){
	global $langx,$accoutArr;
	//echo "<br/>4&&&&&&&&&&&&&&&&&&&&&&&&&&&&zhuazhuazhuazhuazhuazhuazhuazhua&&&&&&&&&&&&&&<br/>";
	$result='';
	$curl = new Curl_HTTP_Client();
	//$curl->store_cookies("/tmp/cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
        if( $value['cookie'] =='' ){
            $dateCur = date('Y-m-d',time());
            $curl->set_cookie("gamePoint_21059363={$dateCur}%2A0%2A0; gamePoint_21059364={$dateCur}%2A0%2A0; gamePoint_21059365={$dateCur}%2A0%2A0; gamePoint_21059366={$dateCur}%2A2%2A0; gamePoint_21059367={$dateCur}%2A2%2A0; gamePoint_21059368={$dateCur}%2A2%2A0; gamePoint_21059369={$dateCur}%2A2%2A0;");
        }else{
            $curl->set_cookie($value['cookie']);
        }
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
			if ($openrow['Open']==1){
				$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',S_Single_Rate_RB='$datainfo[41]',S_Double_Rate_RB='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',M_Duration='$datainfo[48]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='FT'";
				if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
			}
		}	
	}elseif($key=="BK_M_ROU_EO"){
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
	            $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Win_Rate='$datainfo[29]', TG_Win_Rate='$datainfo[30]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[35]',MB_Dime_RB_S_H='$datainfo[36]',TG_Dime_RB_H='$datainfo[39]',TG_Dime_RB_S_H='$datainfo[40]',MB_Dime_Rate_RB_H='$datainfo[37]',MB_Dime_Rate_RB_S_H='$datainfo[38]',TG_Dime_Rate_RB_H='$datainfo[41]',TG_Dime_Rate_RB_S_H='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',M_Duration='$M_duration',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
	            if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
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
			$openresult = mysqli_query($dbMasterLink,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID=$midNew";
				$resultrb = mysqli_query($dbMasterLink,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,HRMB1TG0,HRMB2TG0,HRMB0TG1,HRMB0TG2,HRMB2TG1,HRMB1TG2,HRMB3TG0,HRMB0TG3,HRMB3TG1,HRMB1TG3,HRMB3TG2,HRMB2TG3,HRMB4TG0,HRMB0TG4,HRMB4TG1,HRMB1TG4,HRMB4TG2,HRMB2TG4,HRMB4TG3,HRMB3TG4,HRMB0TG0,HRMB1TG1,HRMB2TG2,HRMB3TG3,HRMB4TG4,HRUP5)VALUES('$midNew','$datainfo[8]','$datainfo[9]','$datainfo[24]','$datainfo[25]','$datainfo[10]','$datainfo[26]','$datainfo[11]','$datainfo[27]','$datainfo[12]','$datainfo[28]','$datainfo[13]','$datainfo[29]','$datainfo[14]','$datainfo[30]','$datainfo[15]','$datainfo[31]','$datainfo[16]','$datainfo[32]','$datainfo[17]','$datainfo[33]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]')";
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}	
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set HRMB1TG0='$datainfo[8]',HRMB2TG0='$datainfo[9]',HRMB0TG1='$datainfo[24]',HRMB0TG2='$datainfo[25]',HRMB2TG1='$datainfo[10]',HRMB1TG2='$datainfo[26]',HRMB3TG0='$datainfo[11]',HRMB0TG3='$datainfo[27]',HRMB3TG1='$datainfo[12]',HRMB1TG3='$datainfo[28]',HRMB3TG2='$datainfo[13]',HRMB2TG3='$datainfo[29]',HRMB4TG0='$datainfo[14]',HRMB0TG4='$datainfo[30]',HRMB4TG1='$datainfo[15]',HRMB1TG4='$datainfo[31]',HRMB4TG2='$datainfo[16]',HRMB2TG4='$datainfo[32]',HRMB4TG3='$datainfo[17]',HRMB3TG4='$datainfo[33]',HRMB0TG0='$datainfo[18]',HRMB1TG1='$datainfo[19]',HRMB2TG2='$datainfo[20]',HRMB3TG3='$datainfo[21]',HRMB4TG4='$datainfo[22]',HRUP5='$datainfo[23]' where MID=$midNew";	
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}	
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
			$openresult = mysqli_query($dbMasterLink,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($dbMasterLink,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
		    	if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RMB1TG0,RMB2TG0,RMB0TG1,RMB0TG2,RMB2TG1,RMB1TG2,RMB3TG0,RMB0TG3,RMB3TG1,RMB1TG3,RMB3TG2,RMB2TG3,RMB4TG0,RMB0TG4,RMB4TG1,RMB1TG4,RMB4TG2,RMB2TG4,RMB4TG3,RMB3TG4,RMB0TG0,RMB1TG1,RMB2TG2,RMB3TG3,RMB4TG4,RUP5)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[24]','$datainfo[25]','$datainfo[10]','$datainfo[26]','$datainfo[11]','$datainfo[27]','$datainfo[12]','$datainfo[28]','$datainfo[13]','$datainfo[29]','$datainfo[14]','$datainfo[30]','$datainfo[15]','$datainfo[31]','$datainfo[16]','$datainfo[32]','$datainfo[17]','$datainfo[33]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]')";
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RMB1TG0='$datainfo[8]',RMB2TG0='$datainfo[9]',RMB0TG1='$datainfo[24]',RMB0TG2='$datainfo[25]',RMB2TG1='$datainfo[10]',RMB1TG2='$datainfo[26]',RMB3TG0='$datainfo[11]',RMB0TG3='$datainfo[27]',RMB3TG1='$datainfo[12]',RMB1TG3='$datainfo[28]',RMB3TG2='$datainfo[13]',RMB2TG3='$datainfo[29]',RMB4TG0='$datainfo[14]',RMB0TG4='$datainfo[30]',RMB4TG1='$datainfo[15]',RMB1TG4='$datainfo[31]',RMB4TG2='$datainfo[16]',RMB2TG4='$datainfo[32]',RMB4TG3='$datainfo[17]',RMB3TG4='$datainfo[33]',RMB0TG0='$datainfo[18]',RMB1TG1='$datainfo[19]',RMB2TG2='$datainfo[20]',RMB3TG3='$datainfo[21]',RMB4TG4='$datainfo[22]',RUP5='$datainfo[23]' where MID=$datainfo[0]";	
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
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
			$openresult = mysqli_query($dbMasterLink,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($dbMasterLink,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RS_0_1,RS_2_3,RS_4_6,RS_7UP)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]')";
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RS_0_1='$datainfo[8]',RS_2_3='$datainfo[9]',RS_4_6='$datainfo[10]',RS_7UP='$datainfo[11]' where MID=$datainfo[0]";	
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
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
			$openresult = mysqli_query($dbMasterLink,$opensql);
		    $openrow=mysqli_fetch_assoc($openresult);
		    if ($openrow['Open']==1){
				$sqlrb = "select count(1) as num from `".DBPREFIX."match_sports_rb_expand` where  MID='$datainfo[0]'";
				$resultrb = mysqli_query($dbMasterLink,$sqlrb);
		    	$rowrb=mysqli_fetch_assoc($resultrb);
				if($rowrb['num']==0){
					$sql = "INSERT INTO ".DBPREFIX."match_sports_rb_expand(MID,RMBMB,RMBFT,RMBTG,RFTMB,RFTFT,RFTTG,RTGMB,RTGFT,RTGTG)VALUES('$datainfo[0]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]')";
				if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
				}else{
					$sql = "update ".DBPREFIX."match_sports_rb_expand set RMBMB='$datainfo[8]',RMBFT='$datainfo[9]',RMBTG='$datainfo[10]',RFTMB='$datainfo[11]',RFTFT='$datainfo[12]',RFTTG='$datainfo[13]',RTGMB='$datainfo[14]',RTGFT='$datainfo[15]',RTGTG='$datainfo[16]' where MID=$datainfo[0]";	
					if(!mysqli_query($dbMasterLink,$sql)){ $res=false;break;}
			    }
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
						$newDataArray[$datainfo[0]]['gid']=$datainfo[0];   
						$newDataArray[$datainfo[0]]['timer'] =$datainfo[1];
						$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
						$newDataArray[$datainfo[0]]['league']=$datainfo[2];
						$newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
						$newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
						$newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
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

						$tmpset=explode("^", $datainfo[48]);
						$tmpset[1]=str_replace("<font style=background-color=red>","",$tmpset[1]);
						$tmpset[1]=str_replace("</font>","",$tmpset[1]);
						$showretime="";
						if($tmpset[0]=="Start"){
								$showretime="-";
						}else if($tmpset[0]=="MTIME"){
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
				echo "parent.gamount=$gamecount;\n"; // 总数量
				$reBallCountCur = $cou;
				$listTitle="足球：滾球";
				$leagueNameCur='';
				break;
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

				for($i=0;$i<$cou;$i++){
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
					$datainfo=eval("return $messages;");
					$gamecount ++;
					$M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】
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
					$team_active ;
					$mbTeamArr= explode('-',$datainfo[5]);
					preg_match('/\d+/',$mbTeamArr[1],$mbTeamArrList);
					if($mbTeamArrList[0]==2){
						$team_active ='第二节';
					}elseif($mbTeamArrList[0]==3){
						$team_active ='第三节';
					}elseif($mbTeamArrList[0]==4){
						$team_active ='第四节';
					}else{
						switch ($datainfo[52]) {
							case 'Q1':
								$team_active ='第一节';
								break;
							case 'Q2':
								$team_active ='第二节';
								break;
							case 'Q3':
								$team_active ='第三节';
								break;
							case 'Q4':
								$team_active ='第四节';
								break;
							case 'H1':
								$team_active ='上半场';
								break;
							case 'H2':
								$team_active ='下半场';
								break;
							case 'OT':
								$team_active ='加时';
								break;
							case 'HT':
								$team_active ='半场';
								break;
						}
					}
					
					$team_time ='';
					if($datainfo[56] && $datainfo[56] > 0){ // 转化时间
						$team_hour = floor($datainfo[56]/3600); // 小时不要
						$team_minute = floor(($datainfo[56]-3600 * $team_hour)/60);
						$team_second = floor((($datainfo[56]-3600 * $team_hour) - 60 * $team_minute) % 60);
						$team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
					}
					
					$datainfo_team = $team_active."<span class=\"rb_time_color\">".$team_time."</span>" ;// 球队名称处理
					$datainfo_score = " $datainfo[53]-<span style=\"color:#FF0000\">$datainfo[54]</span>";// 比分处理
					
					$MID=$datainfo[0];
					if($datainfo[52]=='Q4'){ //篮球第四节不允许投注
						$datainfo[5]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$datainfo[5]);
						$datainfo[5]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$datainfo[5]);
						$newDataArray[$MID]['gid']=$datainfo[0];
						$newDataArray[$MID]['timer']=$datainfo[1];
						$newDataArray[$MID]['league']=$datainfo[2];
						$newDataArray[$MID]['gnum_h']=$datainfo[3];
						$newDataArray[$MID]['gnum_c']=$datainfo[4];
						$newDataArray[$MID]['team_h']=$datainfo[5];
						$newDataArray[$MID]['team_c']=$datainfo[6];
						$newDataArray[$MID]['strong']=$datainfo[7];
						$newDataArray[$MID]['ratio']='';
						$newDataArray[$MID]['ior_RH']='';
						$newDataArray[$MID]['ior_RC']='';
						$newDataArray[$MID]['ratio_o']='';
						$newDataArray[$MID]['ratio_u']='';
						$newDataArray[$MID]['ior_OUH']='';
						$newDataArray[$MID]['ior_OUC']='';
						$newDataArray[$MID]['ior_EOO']='';
						$newDataArray[$MID]['ior_EOE']='';
						$newDataArray[$MID]['ratio_ouho']='';
						$newDataArray[$MID]['ratio_ouhu']='';
						$newDataArray[$MID]['ior_OUHO']='';
						$newDataArray[$MID]['ior_OUHU']='';
						$newDataArray[$MID]['ratio_ouco']='';
						$newDataArray[$MID]['ratio_oucu']='';
						$newDataArray[$MID]['ior_OUCO']='';
						$newDataArray[$MID]['ior_OUCU']='';
						$newDataArray[$MID]['more']='';
						$newDataArray[$MID]['eventid']='';
						$newDataArray[$MID]['hot']='';
						$newDataArray[$MID]['ior_MH']='';
						$newDataArray[$MID]['ior_MC']='';
						$newDataArray[$MID]['team_info']=$datainfo_team;
						$newDataArray[$MID]['score_info']=$datainfo_score;
						$newDataArray[$MID]['center_tv']='';
						$newDataArray[$MID]['play']='';
						$newDataArray[$MID]['datetime']='';
						$newDataArray[$MID]['all']=''; 
					}else{
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
						$newDataArray[$MID]['gnum_h']=$datainfo[3];
						$newDataArray[$MID]['gnum_c']=$datainfo[4];
						$newDataArray[$MID]['team_h']=$datainfo[5];
						$newDataArray[$MID]['team_c']=$datainfo[6];
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
						$newDataArray[$MID]['ratio_ouho']=$datainfo[35];
						$newDataArray[$MID]['ratio_ouhu']=$datainfo[36];
						$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$datainfo[35]);
						$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$datainfo[36]);
						$newDataArray[$MID]['ior_OUHO']=$datainfo[37];
						$newDataArray[$MID]['ior_OUHU']=$datainfo[38];
						$newDataArray[$MID]['ratio_ouco']=$datainfo[39];
						$newDataArray[$MID]['ratio_oucu']=$datainfo[40];
						$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$datainfo[39]);
						$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$datainfo[40]);
						$newDataArray[$MID]['ior_OUCO']=$datainfo[41];
						$newDataArray[$MID]['ior_OUCU']=$datainfo[42];
						$newDataArray[$MID]['more']=$datainfo[25];
						$newDataArray[$MID]['eventid']=$datainfo[26];
						$newDataArray[$MID]['hot']=$datainfo[27];
						$newDataArray[$MID]['ior_MH']=$datainfo[29];
						$newDataArray[$MID]['ior_MC']=$datainfo[30];
						$newDataArray[$MID]['team_info']=$datainfo_team;
						$newDataArray[$MID]['score_info']=$datainfo_score;
						$newDataArray[$MID]['center_tv']=$datainfo[31];
						$newDataArray[$MID]['play']=$datainfo[32];
						$newDataArray[$MID]['datetime']=$datainfo[33];
						$newDataArray[$MID]['all']=$datainfo[49];        
						$newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&gnum={$datainfo[3]}&langx={$langx}&odd_f_type=H&strong=".$datainfo[7];            
					}
					$K=$K+1;					
				}
				
				$page_count=ceil($K/$page_size);
				echo "parent.t_page=$page_count;\n";
				echo "parent.gamount=$gamecount;\n"; // 总数量
				$listTitle="蓝球和美式足球 :滚球";
				$leagueNameCur='';
				break;	
			case "FT_PD"://全场滚球波胆	
				$reBallCountCur = 0;
				$page_size=60;
				echo "parent.retime=20;\n"; // 滚球倒计时刷新时间
				$today_bet_floatright ='today_bet_floatright_pd' ;
				$box_pd ='box_pd';
				$cou=sizeof($matches);
				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}
				$page_count=ceil($cou/$page_size);
				echo "parent.gamount=$cou;\n";
				echo "parent.t_page=$page_count;\n";
				for($i=0;$i<$cou;$i++){
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
					$datainfo=eval("return $messages;");
					$newDataArray[$datainfo[0]]['gid']=$datainfo[0];
					$newDataArray[$datainfo[0]]['datetime']=$datainfo[45];
					$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
					$newDataArray[$datainfo[0]]['league']=$datainfo[2];
					$newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
					$newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
					$newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
					$newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
					$newDataArray[$datainfo[0]]['strong']=$datainfo[7];
					$newDataArray[$datainfo[0]]['ior_H1C0']=change_rate($open,$datainfo[8]);
					$newDataArray[$datainfo[0]]['ior_H2C0']=change_rate($open,$datainfo[9]);
					$newDataArray[$datainfo[0]]['ior_H2C1']=change_rate($open,$datainfo[10]);
					$newDataArray[$datainfo[0]]['ior_H3C0']=change_rate($open,$datainfo[11]);
					$newDataArray[$datainfo[0]]['ior_H3C1']=change_rate($open,$datainfo[12]);
					$newDataArray[$datainfo[0]]['ior_H3C2']=change_rate($open,$datainfo[13]);
					$newDataArray[$datainfo[0]]['ior_H4C0']=change_rate($open,$datainfo[14]);
					$newDataArray[$datainfo[0]]['ior_H4C1']=change_rate($open,$datainfo[15]);
					$newDataArray[$datainfo[0]]['ior_H4C2']=change_rate($open,$datainfo[16]);
					$newDataArray[$datainfo[0]]['ior_H4C3']=change_rate($open,$datainfo[17]);
					$newDataArray[$datainfo[0]]['ior_H0C0']=change_rate($open,$datainfo[18]);
					$newDataArray[$datainfo[0]]['ior_H1C1']=change_rate($open,$datainfo[19]);
					$newDataArray[$datainfo[0]]['ior_H2C2']=change_rate($open,$datainfo[20]);
					$newDataArray[$datainfo[0]]['ior_H3C3']=change_rate($open,$datainfo[21]);
					$newDataArray[$datainfo[0]]['ior_H4C4']=change_rate($open,$datainfo[22]);
					$newDataArray[$datainfo[0]]['ior_OVH']=change_rate($open,$datainfo[23]);
					$newDataArray[$datainfo[0]]['ior_H0C1']=change_rate($open,$datainfo[24]);
					$newDataArray[$datainfo[0]]['ior_H0C2']=change_rate($open,$datainfo[25]);
					$newDataArray[$datainfo[0]]['ior_H1C2']=change_rate($open,$datainfo[26]);
					$newDataArray[$datainfo[0]]['ior_H0C3']=change_rate($open,$datainfo[27]);
					$newDataArray[$datainfo[0]]['ior_H1C3']=change_rate($open,$datainfo[28]);
					$newDataArray[$datainfo[0]]['ior_H2C3']=change_rate($open,$datainfo[29]);
					$newDataArray[$datainfo[0]]['ior_H0C4']=change_rate($open,$datainfo[30]);
					$newDataArray[$datainfo[0]]['ior_H1C4']=change_rate($open,$datainfo[31]);
					$newDataArray[$datainfo[0]]['ior_H2C4']=change_rate($open,$datainfo[32]);
					$newDataArray[$datainfo[0]]['ior_H3C4']=change_rate($open,$datainfo[33]);
					$newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
					$K=$K+1;
				}
				$reBallCountCur = $cou;
				$listTitle="滚球足球：波胆";
				$leagueNameCur='';
				break;
			case "FT_HPD"://半场滚球波胆	
				$reBallCountCur = 0;
				$page_size=60;
				$today_bet_floatright ='today_bet_floatright_pd' ;
				$box_pd ='box_pd' ;
				$cou=sizeof($matches);
				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}
				$page_count=ceil($cou/$page_size);
				echo "parent.gamount=$cou;\n";
				echo "parent.t_page=$page_count;\n";
				for($i=0;$i<$cou;$i++){
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
					$datainfo=eval("return $messages;");
					$midNew = $datainfo[0]-1;
					$newDataArray[$midNew]['gid']=$midNew;
					$newDataArray[$midNew]['datetime']=$datainfo[43];
					$newDataArray[$midNew]['datetimelove']=$datainfo[43];
					$newDataArray[$midNew]['dategh']=$date.$datainfo[3];
					$newDataArray[$midNew]['league']=$datainfo[2];
					$newDataArray[$midNew]['gnum_h']=$datainfo[3];
					$newDataArray[$midNew]['gnum_c']=$datainfo[4];
					$newDataArray[$midNew]['team_h']=$datainfo[5];
					$newDataArray[$midNew]['team_c']=$datainfo[6];
					$newDataArray[$midNew]['strong']=$datainfo[7];
					$newDataArray[$midNew]['ior_H1C0']=change_rate($open,$datainfo[8]);
					$newDataArray[$midNew]['ior_H2C0']=change_rate($open,$datainfo[9]);
					$newDataArray[$midNew]['ior_H2C1']=change_rate($open,$datainfo[10]);
					$newDataArray[$midNew]['ior_H3C0']=change_rate($open,$datainfo[11]);
					$newDataArray[$midNew]['ior_H3C1']=change_rate($open,$datainfo[12]);
					$newDataArray[$midNew]['ior_H3C2']=change_rate($open,$datainfo[13]);
					$newDataArray[$midNew]['ior_H4C0']=change_rate($open,$datainfo[14]);
					$newDataArray[$midNew]['ior_H4C1']=change_rate($open,$datainfo[15]);
					$newDataArray[$midNew]['ior_H4C2']=change_rate($open,$datainfo[16]);
					$newDataArray[$midNew]['ior_H4C3']=change_rate($open,$datainfo[17]);
					$newDataArray[$midNew]['ior_H0C0']=change_rate($open,$datainfo[18]);
					$newDataArray[$midNew]['ior_H1C1']=change_rate($open,$datainfo[19]);
					$newDataArray[$midNew]['ior_H2C2']=change_rate($open,$datainfo[20]);
					$newDataArray[$midNew]['ior_H3C3']=change_rate($open,$datainfo[21]);
					$newDataArray[$midNew]['ior_H4C4']=change_rate($open,$datainfo[22]);
					$newDataArray[$midNew]['ior_OVH']=change_rate($open,$datainfo[23]);
					$newDataArray[$midNew]['ior_H0C1']=change_rate($open,$datainfo[24]);
					$newDataArray[$midNew]['ior_H0C2']=change_rate($open,$datainfo[25]);
					$newDataArray[$midNew]['ior_H1C2']=change_rate($open,$datainfo[26]);
					$newDataArray[$midNew]['ior_H0C3']=change_rate($open,$datainfo[12]);
					$newDataArray[$midNew]['ior_H1C3']=change_rate($open,$datainfo[28]);
					$newDataArray[$midNew]['ior_H2C3']=change_rate($open,$datainfo[29]);
					$newDataArray[$midNew]['ior_H0C4']=change_rate($open,$datainfo[30]);
					$newDataArray[$midNew]['ior_H1C4']=change_rate($open,$datainfo[31]);
					$newDataArray[$midNew]['ior_H2C4']=change_rate($open,$datainfo[32]);
					$newDataArray[$midNew]['ior_H3C4']=change_rate($open,$datainfo[33]);
					$newDataArray[$midNew]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&wtype=HRPD&odd_f_type=H&langx={$langx}&rtype=";
					$K=$K+1;
				}
				$reBallCountCur = $cou;
				$listTitle="滚球足球：波胆";
				$leagueNameCur='';
				break;
			case "FT_T"://滚球总入球 
				$reBallCountCur = 0;
				$page_size=60;
				echo "parent.retime=20;\n"; // 倒计时刷新时间
				$cou=sizeof($matches);
				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}
                $matches=array();
                $cou=0;
				$page_count=ceil($cou/$page_size);
				echo "parent.gamount=$cou;\n";
				echo "parent.t_page=$page_count;\n";
				for($i=0;$i<$cou;$i++){
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
					$datainfo=eval("return $messages;");
					$datainfoA[8]=change_rate($open,$datainfo[8]);
					$datainfoA[9]=change_rate($open,$datainfo[9]);
					$datainfoA[10]=change_rate($open,$datainfo[10]);
					$datainfoA[11]=change_rate($open,$datainfo[11]);
					
					$newDataArray[$datainfo[0]]['gid']=$datainfo[0];
					$newDataArray[$datainfo[0]]['datetime']=$datainfo[22];
					$newDataArray[$datainfo[0]]['datetimelove']=$datainfo[22];
					$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
					$newDataArray[$datainfo[0]]['league']=$datainfo[2];
					$newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
					$newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
					$newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
					$newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
					$newDataArray[$datainfo[0]]['strong']=$datainfo[16];
					$newDataArray[$datainfo[0]]['ior_T01']=$datainfoA[8];
					$newDataArray[$datainfo[0]]['ior_T23']=$datainfoA[9];
					$newDataArray[$datainfo[0]]['ior_T46']=$datainfoA[10];
					$newDataArray[$datainfo[0]]['ior_OVER']=$datainfoA[11];
					$newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
					$K=$K+1;
				}
				
				$listTitle="滚球足球：全场总入球数";
				$reBallCountCur = $cou;
				$leagueNameCur='';
				break;	
			case "FT_F":
				$reBallCountCur = 0;
				$page_size=60;
				echo "parent.retime=20;\n"; // 倒计时刷新时间
				$cou=sizeof($matches);
				if(is_array($matches)){
					$cou=sizeof($matches);
				}else{
					$cou=0;
				}
				
				$page_count=ceil($cou/$page_size);
				echo "parent.gamount=$cou;\n";
				echo "parent.t_page=$page_count;\n";
				for($i=0;$i<$cou;$i++){
					$messages=$matches[$i];
					$messages=str_replace(");",")",$messages);
					$messages=str_replace("cha(9)","",$messages);
					$datainfo=eval("return $messages;");
					$datainfoA[8]=change_rate($open,$datainfo[8]);
					$datainfoA[9]=change_rate($open,$datainfo[9]);
					$datainfoA[10]=change_rate($open,$datainfo[10]);
					$datainfoA[11]=change_rate($open,$datainfo[11]);
					$datainfoA[12]=change_rate($open,$datainfo[12]);
					$datainfoA[13]=change_rate($open,$datainfo[13]);
					$datainfoA[14]=change_rate($open,$datainfo[14]);
					$datainfoA[15]=change_rate($open,$datainfo[15]);
					$datainfoA[16]=change_rate($open,$datainfo[16]);
					
					$newDataArray[$datainfo[0]]['gid']=$datainfo[0];
					$newDataArray[$datainfo[0]]['datetime']=$datainfo[27];
					$newDataArray[$datainfo[0]]['datetimelove']=$datainfo[27];
					$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
					$newDataArray[$datainfo[0]]['league']=$datainfo[2];
					$newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
					$newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
					$newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
					$newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
					$newDataArray[$datainfo[0]]['strong']=$datainfo[21];
					$newDataArray[$datainfo[0]]['ior_FHH']=$datainfoA[8];
					$newDataArray[$datainfo[0]]['ior_FHN']=$datainfoA[9];
					$newDataArray[$datainfo[0]]['ior_FHC']=$datainfoA[10];
					$newDataArray[$datainfo[0]]['ior_FNH']=$datainfoA[11];
					$newDataArray[$datainfo[0]]['ior_FNN']=$datainfoA[12];
					$newDataArray[$datainfo[0]]['ior_FNC']=$datainfoA[13];
					$newDataArray[$datainfo[0]]['ior_FCH']=$datainfoA[14];
					$newDataArray[$datainfo[0]]['ior_FCN']=$datainfoA[15];
					$newDataArray[$datainfo[0]]['ior_FCC']=$datainfoA[16];
					$newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type={$datainfo[21]}&wtype=RF&langx={$langx}&rtype=";
					$K=$K+1;
				}
				$reBallCountCur = $cou;
				$listTitle="足球滚球:半场 /全场";
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

		<!-- 2018 新增 右侧游戏-->
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
