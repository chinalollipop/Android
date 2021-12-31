<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    require(CONFIG_DIR."/include/config.inc.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require(CONFIG_DIR."/include/traditional.zh-cn.inc.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require ('../include/curl_http.php');
    require ("../include/traditional.zh-cn.inc.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}
@error_log('date -'.date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/group/score_bk_from_yjl.php.log');
die;

	$redisObj = new Ciredis();
	$mysql = "select udp_ft_score,udp_ft_results from ".DBPREFIX."web_system_data";
	$result = mysqli_query($dbLink,$mysql);
	$row = mysqli_fetch_assoc($result);
	$settime=$row['udp_ft_score'];
	$time=$row['udp_ft_results'];
	$list_date=date('Y-m-d',time()-$time*60*60);
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("https://www.ujiule.net");
	$html_data=$curl->fetch_url("https://www.ujiule.net/touzhu/BK_Browser/BK_Score.aspx?date=".$list_date."&name=&t=");
	$html_data = mb_convert_encoding($html_data, 'utf-8', 'GBK,UTF-8,ASCII');
	$data = get_content_deal($html_data);
	preg_match_all("/array\((.+?)\);/is",$data,$matches);
	$m=0;
	$dataInfo = $matches[0];
	$cou = count($dataInfo);
	if($cou>0){//可以抓到数据
			for($i=0;$i<count($dataInfo);$i++){
				$matchNo='';
				$data = eval("return $dataInfo[$i];");
					
					/*echo '<br/>';
					echo '<pre>';
					print_r($data);
					echo '<br/>';*/
					/*
				 	[0] => 2482398
				    [1] => 04-16
				    [2] => 07:48a
				    [3] => 俄羅斯女子籃球超級聯賽2
				    [4] => 喀山諾奇卡2[女]
				    [5] => 沙赫蒂[女]
				    [6] => 1
				    [7] => 2
				    [8] => 1
				    [9] => 2018/4/16 20:18:38
				    [10] => 2018/4/16 18:00:00
				    [11] => :
				    [12] => :
				    [13] => :
				    [14] => :
				    [15] => 35:34
				    [16] => 51:44
				    [17] => 86:78
				    [18] => 04-16 06:00a
				*/
				
				$mid = $data[0];			//获取MID
				$matchDate = $data[1];		//比赛日期
				$matchTime = $data[2];		//比赛开始时间
				$mbTeam = $data[4];	//主队
				$tgTeam = $data[5];	//客队
				
				$mbTeamArr = explode(' - ',$mbTeam);//通过主客队名称判断比赛阶段 
				$tgTeamArr = explode(' - ',$tgTeam);
				if(count($mbTeamArr)==1 && count($tgTeamArr)==1){$matchNo='all';}
				if(count($mbTeamArr)==2 && count($tgTeamArr)==2){
					$mbTeamArrNo=str_replace("[第", "", $mbTeamArr[1]);
					$mbTeamArrNo=str_replace("節]", "", $mbTeamArrNo);
					$mbTeamArrNo=str_replace("[", "", $mbTeamArrNo);
					$mbTeamArrNo=str_replace("]", "", $mbTeamArrNo);
					$tgTeamArrNo=str_replace("[第", "", $tgTeamArr[1]);
					$tgTeamArrNo=str_replace("節]", "", $tgTeamArrNo);
					$tgTeamArrNo=str_replace("[", "", $tgTeamArrNo);
					$tgTeamArrNo=str_replace("]", "", $tgTeamArrNo);
					if($mbTeamArrNo==$tgTeamArrNo){
						if($tgTeamArrNo=="上半"){
							$matchNo="hr";
						}elseif($tgTeamArrNo=="下半"){
							$matchNo="xb";
						}else{
							$matchNo=$mbTeamArrNo;	
						}
					}
				}
				
				/*if($mid==2547377){
					echo '<br/>';
					var_dump($matchNo);
					echo '<br/>';				
				}*/
				
				if(!$matchNo){continue;}//不存在比赛场次，退出分数更新
				$score1 = $data[11];	//第一节比分
				$score2 = $data[12];	//第二节比分
				$score3 = $data[13];	//第三节比分
				$score4 = $data[14];	//第四节比分
				$scoreHr = $data[15];	//第上半场比分
				$scoreXb = $data[16];	//第下半场比分
				$scoreAll = $data[17];	//全场比分
					
					$sql="select MID,MB_Team,TG_Team,MB_Team_en,TG_Team_en,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='$mid' and  M_Date='".$list_date."'";
					//echo $sql; 
					$result = mysqli_query($dbLink,$sql);
					$mcou = mysqli_num_rows($result);
					$row = mysqli_fetch_assoc($result);
					
					/*if($mid==2547377){
						echo '<pre>';
						var_dump($row);
						echo '<br/>';
					}*/

                    // 比分小于0，赛事异常（取消或者其他），跳过不更新
                    if($mcou >0 ){
                        if($row['MB_Inball']<0 && $row['TG_Inball']<0 && $row['MB_Inball_HR']<0 && $row['TG_Inball_HR']<0) continue;
						if( $row['Score_Source']==2 || $row['Score_Source']==3){ continue; }//如果皇冠或管理员已经处理过，则跳出当前赛事，继续下一个赛事
						$mbRowArr = explode(' - ',$row['MB_Team']);//判断是否为同一个比赛阶段 
						$tgRowArr = explode(' - ',$row['TG_Team']);	
						
						/*if($mid==2547377){
								echo '<pre>';
								var_dump($mbRowArr);
								echo '<br/>';
								echo '<pre>';
								var_dump($tgRowArr);
								echo '<br/>';
						}*/
						
						if(count($mbRowArr)==1 && count($tgRowArr)==1 && $matchNo=='all'){//全场
							$scoreHrArr = explode(':',$scoreHr);	
							$scoreAllArr = explode(':',$scoreAll);
							if($scoreAllArr[0]==0 && $scoreAllArr[1]==0) continue;	
							$a=	$row['MB_Inball'].$row['TG_Inball'].$row['MB_Inball_HR'].$row['TG_Inball_HR'];
							$b=	trim($scoreAllArr[0]).trim($scoreAllArr[1]).trim($scoreHrArr[0]).trim($scoreHrArr[1]);
							if($a!=$b && $scoreAllArr[0] != "" && $scoreAllArr[1] != ""){
									$check=0;
									if($row['MB_Inball']!='' && trim($scoreAllArr[0])!=$row['MB_Inball'])	$check=1;
									if($row['TG_Inball']!='' && trim($scoreAllArr[1])!=$row['TG_Inball'])	$check=1;
									if($row['MB_Inball_HR']!='' && trim($scoreHrArr[0])!=$row['MB_Inball_HR'])	$check=1;
									if($row['TG_Inball_HR']!='' && trim($scoreHrArr[1])!=$row['TG_Inball_HR'])	$check=1;
								$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "."Checked=".$check.",MB_Inball='$scoreAllArr[0]',TG_Inball='$scoreAllArr[1]',MB_Inball_HR='$scoreHrArr[0]',TG_Inball_HR='$scoreHrArr[1]',Score_Source=1  where Type='BK' and ($scoreAllArr[0]!=0 or $scoreAllArr[1]!=0) and M_Date='$list_date' and MID=".(int)$mid;
								mysqli_query($dbMasterLink, $mysql) or die('1abc');	
								$redisObj->pushMessage('MatchScorefinishList',(int)$mid);
							}
						}elseif(count($mbRowArr)==2 && count($tgRowArr)==2){
							if((strpos($mbRowArr[1],"上半")>0 || strpos($mbRowArr[1],"下半")>0) && (strpos($tgRowArr[1],"上半")>0 || strpos($tgRowArr[1],"下半")>0)){//上下半场
//								$patterns = "/\d+/";
//								preg_match_all($patterns,$mbRowArr[1],$mbRowArrNo);
//								preg_match_all($patterns,$tgRowArr[1],$tgRowArrNo);
								if($matchNo=='hr'){ $scoreArr = explode(':',$scoreHr); }//上
								//if($mbRowArrNo[0][0]=='1' && $tgRowArrNo[0][0]=='1' && $matchNo='hr'){ $scoreArr = explode(':',$scoreHr); }//上
								//if($mbRowArrNo[0][0]=='2' && $tgRowArrNo[0][0]=='2' && $matchNo='xb'){ $scoreArr = explode(':',$scoreXb); }//下
								if( $matchNo=='xb'){ $scoreArr = explode(':',$scoreXb); }//下
								if($scoreArr[0]==0 && $scoreArr[1]==0) continue;	
								if($scoreArr[0]=='' && $scoreArr[1]=='') continue;	
								$a=	$row['MB_Inball'].$row['TG_Inball'];
								$b=	trim($scoreArr[0]).trim($scoreArr[1]);
								if($a!=$b){
									$check=0;
									if($row['MB_Inball']!='' && trim($scoreArr[0])!=$row['MB_Inball'])	$check=1;
									if($row['TG_Inball']!='' && trim($scoreArr[1])!=$row['TG_Inball'])	$check=1;
									$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "."Checked=".$check.",MB_Inball='$scoreArr[0]',TG_Inball='$scoreArr[1]',MB_Inball_HR=0,TG_Inball_HR=0,Score_Source=1  where Type='BK' and ($scoreArr[0]!=0 or $scoreArr[1]!=0) and M_Date='$list_date' and MID=".(int)$mid;
									mysqli_query($dbMasterLink, $mysql) or die('2abc');
									$redisObj->pushMessage('MatchScorefinishList',(int)$mid);
								}
							}elseif((strpos($mbRowArr[1],"第")>0 && strpos($mbRowArr[1],"节")>0) && (strpos($tgRowArr[1],"第")>0 && strpos($tgRowArr[1],"节")>0)){
								$patterns = "/\d+/";
								preg_match_all($patterns,$mbRowArr[1],$mbRowArrNo);
								preg_match_all($patterns,$tgRowArr[1],$tgRowArrNo);
                                if ($score1==':' && $score2==':' && $score3==':' && $score4==':') continue; // 比分为空，跳过不更新
								if($mbRowArrNo[0][0]=='1' && $tgRowArrNo[0][0]=='1' && $matchNo='1'){$scoreArr = explode(':',$score1);}//第一节
								if($mbRowArrNo[0][0]=='2' && $tgRowArrNo[0][0]=='2' && $matchNo='2'){$scoreArr = explode(':',$score2);}//第二节
								if($mbRowArrNo[0][0]=='3' && $tgRowArrNo[0][0]=='3' && $matchNo='3'){$scoreArr = explode(':',$score3);}//第三节
								if($mbRowArrNo[0][0]=='4' && $tgRowArrNo[0][0]=='4' && $matchNo='4'){$scoreArr = explode(':',$score4);}//第四节
								if($scoreArr[0]==0 && $scoreArr[1]==0) continue;	
								if($scoreArr[0]=='' && $scoreArr[1]=='') continue;	
								$a=	$row['MB_Inball'].$row['TG_Inball'];
								$b=	trim($scoreArr[0]).trim($scoreArr[1]);
								if($a!=$b){
									$check=0;
									if($row['MB_Inball']!='' && trim($scoreArr[0])!=$row['MB_Inball'])	$check=1;
									if($row['TG_Inball']!='' && trim($scoreArr[1])!=$row['TG_Inball'])	$check=1;
									$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "."Checked=".$check.",MB_Inball='$scoreArr[0]',TG_Inball='$scoreArr[1]',MB_Inball_HR=0,TG_Inball_HR=0,Score_Source=1  where Type='BK' and ($scoreArr[0]!=0 or $scoreArr[1]!=0) and M_Date='$list_date' and MID=".(int)$mid;
									mysqli_query($dbMasterLink, $mysql) or die('3abc');		
									$redisObj->pushMessage('MatchScorefinishList',(int)$mid);
								}
							}elseif($matchNo=='all'){
								$scoreHrArr = explode(':',$scoreHr);	
								$scoreAllArr = explode(':',$scoreAll);	
								if($scoreAllArr[0]==0 && $scoreAllArr[1]==0) continue;	
								$a=	$row['MB_Inball'].$row['TG_Inball'].$row['MB_Inball_HR'].$row['TG_Inball_HR'];
								$b=	trim($scoreAllArr[0]).trim($scoreAllArr[1]).trim($scoreHrArr[0]).trim($scoreHrArr[1]);
								if($a!=$b){
									$check=0;
									if($row['MB_Inball']!='' && trim($scoreAllArr[0])!=$row['MB_Inball'])	$check=1;
									if($row['TG_Inball']!='' && trim($scoreAllArr[1])!=$row['TG_Inball'])	$check=1;
									if($row['MB_Inball_HR']!='' && trim($scoreHrArr[0])!=$row['MB_Inball_HR'])	$check=1;
									if($row['TG_Inball_HR']!='' && trim($scoreHrArr[1])!=$row['TG_Inball_HR'])	$check=1;
									$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set "."Checked=".$check.",MB_Inball='$scoreAllArr[0]',TG_Inball='$scoreAllArr[1]',MB_Inball_HR='$scoreHrArr[0]',TG_Inball_HR='$scoreHrArr[1]',Score_Source=1  where Type='BK' and ($scoreAllArr[0]!=0 or $scoreAllArr[1]!=0) and M_Date='$list_date' and MID=".(int)$mid;
									mysqli_query($dbMasterLink, $mysql) or die('4abc');	
									$redisObj->pushMessage('MatchScorefinishList',(int)$mid);
								}
							}
						}
						$m=$m+1;
					}
				}
		}


function get_content_deal($html_data){
	$html_data = strtolower($html_data);
	$a = array(
		"<script language='javascript' type='text/javascript'>",
		"</script>",
		"<html>",
		"</html>",
		"<head>",
		"</head>",
		"<title>",
		"</title>"
		);
	$b = array(
		"",
		"",
		"",
		"",
		"",
		"",
	    "",
		""
	);
	$msg = str_replace($a,$b,$html_data);
	return $msg;
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>悠久乐篮球接比分</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
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
			curtime=curmin+"秒后自动本页获取最新数据！" 
		else 
			curtime=cursec+"秒后自动本页获取最新数据！" 
			timeinfo.innerText=curtime 
			setTimeout("beginrefresh()",1000) 
		} 
} 

window.onload=beginrefresh 

</script>
<body>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center"><br><?php echo $list_date?><br><br><span id="timeinfo"></span><br>
      <input type=button name=button value="足球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
