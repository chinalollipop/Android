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

	$redisObj = new Ciredis();
	$mysql = "select udp_ft_score,udp_ft_results from ".DBPREFIX."web_system_data";
	$result = mysqli_query($dbLink,$mysql);
	$row = mysqli_fetch_assoc($result);
	$settime=$row['udp_ft_score'];
	$list_date=date('Y-m-d',time());
	$m=0;
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("/tmp/cookies.txt");
	$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	$curl->set_referrer("https://www.ujiule.net");
  //$html_data=$curl->fetch_url("https://ujiule.com/touzhu/FT_Browser/FT_Score.aspx?date=".$list_date."&name=&t=0.10182072701315725");
	$html_data=$curl->fetch_url("https://www.ujiule.net/touzhu/FT_Browser/FT_Score.aspx?date=".$list_date."&name=&t=");
	$html_data = mb_convert_encoding($html_data, 'utf-8', 'GBK,UTF-8,ASCII');
	$data = get_content_deal($html_data);
	preg_match_all("/array\((.+?)\);/is",$data,$matches);
	$m=0;
	$dataInfo = $matches[0];
	$cou = count($dataInfo);

	/*
	$dataInfo = array(
	    array('3151158','2018527','03-27','88','南澳洲國家甲組聯賽','科夫','西部前鋒','1','','','0:2','0:2','1','2018/3/27 18:29:05','2018/3/27 16:30:00','','','03-27 04:30a','0','1'),
	    array('3151160','2018528','03-27','88','南澳洲國家甲組聯賽','科夫','西部前鋒','','','','0:2','0:2','1','2018/3/27 18:29:05','2018/3/27 16:30:00','','','03-27 04:30a','0','1'),
	    array('3151314','2018524','03-27','中場','捷克聯賽u21','普利布蘭u21','布拉格斯巴達u21','1','','','2:0',':','0','2018/3/27 17:48:15','2018/3/27 17:00:00','','','03-27 05:00a','0','1'),
	    array('3151316','2018523','03-27','中場','捷克聯賽u21','普利布蘭u21','布拉格斯巴達u21','','','','2:0',':','0','2018/3/27 17:48:15','2018/3/27 17:00:00','','','03-27 05:00a','0','1'),
	    array('3154220','2018522','03-27','中場','捷克聯賽u21','普利布蘭u21 -角球數','布拉格斯巴達u21 -角球數','','','','6:1',':','0','2018/3/27 17:48:15','2018/3/27 17:00:00','','','03-27 05:00a','0','1'),
	    array('3154264','2018526','03-27','中場','澳洲昆士蘭州女子國家超級聯賽','卡帕拉巴(女)','南部聯合(女)','1','','','0:1',':','0','2018/3/27 18:28:59','2018/3/27 17:30:00','','','03-27 05:30a','0','1'),
	    array('3154266','2018525','03-27','中場','澳洲昆士蘭州女子國家超級聯賽','卡帕拉巴(女)','南部聯合(女)','','','','0:1',':','0','2018/3/27 18:28:59','2018/3/27 17:30:00','','','03-27 05:30a','0','1')
	);
	*/
	if($cou>0){//可以抓到数据
			for($i=0;$i<count($dataInfo);$i++){
				$data = eval("return $dataInfo[$i];");
				//$data = $dataInfo[$i];
				if($data[12]==2) continue;//作废比赛 	等待正网结算
				if(in_array(substr($data[0],-1), array('a','b','c','d','e','f'))) continue; //15分钟盘口退出循环
				
				$dataHalf = explode(':',$data[10]);//半场
				$dataAll = explode(':',$data[11]);//全场
				$mid_m = $data[0];				//获取MID
				$mb_inball = $dataAll[0];		//主队半场比分
				$tg_inball = $dataAll[1];		//客队半场比分
				$mb_inball_hr = $dataHalf[0];	//主队全场比分
				$tg_inball_hr = $dataHalf[1];	//主队全场比分
				
					//赛事特殊情况处理
					if ($tg_inball==$Score1){$mb_inball='-1';$tg_inball='-1';}
					if ($tg_inball_hr==$Score1){$mb_inball_hr='-1';$tg_inball_hr='-1';}
					if ($tg_inball==$Score2){$mb_inball='-2';$tg_inball='-2';}
					if ($tg_inball_hr==$Score2){$mb_inball_hr='-2';$tg_inball_hr='-2';}
					if ($tg_inball==$Score3){$mb_inball='-3';$tg_inball='-3';}
					if ($tg_inball_hr==$Score3){$mb_inball_hr='-3';$tg_inball_hr='-3';}
					if ($tg_inball==$Score4){$mb_inball='-4';$tg_inball='-4';}
					if ($tg_inball_hr==$Score4){$mb_inball_hr='-4';$tg_inball_hr='-4';}
					if ($tg_inball==$Score5){$mb_inball='-5';$tg_inball='-5';}
					if ($tg_inball_hr==$Score5){$mb_inball_hr='-5';$tg_inball_hr='-5';}
					if ($tg_inball==$Score6){$mb_inball='-6';$tg_inball='-6';}
					if ($tg_inball_hr==$Score6){$mb_inball_hr='-6';$tg_inball_hr='-6';}
					if ($tg_inball=='赛事无pk/加时'){$mb_inball='-7';$tg_inball='-7';}
					if ($tg_inball_hr=='赛事无pk/加时'){$mb_inball_hr='-7';$tg_inball_hr='-7';}
					if ($tg_inball==$Score8){$mb_inball='-8';$tg_inball='-8';}
					if ($tg_inball_hr==$Score8){$mb_inball_hr='-8';$tg_inball_hr='-8';}
					//if ($tg_inball==$Score9){$mb_inball='-9';$tg_inball='-9';}
					//if ($tg_inball_hr==$Score9){$mb_inball_hr='-9';$tg_inball_hr='-9';}
					if ($tg_inball==$Score10){$mb_inball='-10';$tg_inball='-10';}
					if ($tg_inball_hr==$Score10){$mb_inball_hr='-10';$tg_inball_hr='-10';}
					if ($tg_inball==$Score11){$mb_inball='-11';$tg_inball='-11';}
					if ($tg_inball_hr==$Score11){$mb_inball_hr='-11';$tg_inball_hr='-11';}
					if ($tg_inball==$Score12){$mb_inball='-12';$tg_inball='-12';}
					if ($tg_inball_hr==$Score12){$mb_inball_hr='-12';$tg_inball_hr='-12';}
					if ($tg_inball==$Score13){$mb_inball='-13';$tg_inball='-13';}
					if ($tg_inball_hr==$Score13){$mb_inball_hr='-13';$tg_inball_hr='-13';}
					/* 暂时取消掉此类情况
					if ($tg_inball==$Score14){$mb_inball='-14';$tg_inball='-14';}
					if ($tg_inball_hr==$Score14){$mb_inball_hr='-14';$tg_inball_hr='-14';}
					if ($tg_inball==$Score15){$mb_inball='-15';$tg_inball='-15';}
					if ($tg_inball_hr==$Score15){$mb_inball_hr='-15';$tg_inball_hr='-15';}
					if ($tg_inball==$Score16){$mb_inball='-16';$tg_inball='-16';}
					if ($tg_inball_hr==$Score16){$mb_inball_hr='-16';$tg_inball_hr='-16';}
					if ($tg_inball==$Score17){$mb_inball='-17';$tg_inball='-17';}
					if ($tg_inball_hr==$Score17){$mb_inball_hr='-17';$tg_inball_hr='-17';}
					if ($tg_inball==$Score18){$mb_inball='-18';$tg_inball='-18';}
					if ($tg_inball_hr==$Score18){$mb_inball_hr='-18';$tg_inball_hr='-18';}
					if ($tg_inball==$Score19){$mb_inball='-19';$tg_inball='-19';}
					if ($tg_inball_hr==$Score19){$mb_inball_hr='-19';$tg_inball_hr='-19';}
					*/
					//MB_Inball_Time       text          utf8_general_ci  YES             (NULL)                       select,insert,update,references  主队进球时间                               
					//TG_Inball_Time       text          utf8_general_ci  YES             (NULL)                       select,insert,update,references  客队进球时间 

					$sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID='$mid_m' and  M_Date='".$list_date."'";
					$result = mysqli_query($dbLink,$sql);
					$mcou = mysqli_num_rows($result);
					$row = mysqli_fetch_assoc($result);

					if($mcou >0 ){
						if( $row['MB_Inball']<0 || $row['TG_Inball']<0 || $row['MB_Inball_HR']<0 || $row['TG_Inball_HR']<0 ){ continue; }//如果其中有一项小于0则认为管理员已做赛事处理，跳出当前赛事，继续下一个赛事
                        if( $row['Score_Source']==2 || $row['Score_Source']==3){ continue; }//如果皇冠或管理员已经处理过，则跳出当前赛事，继续下一个赛事

						$MB_Inball_O = $row['MB_Inball'];
						$TG_Inball_O = $row['TG_Inball'];
						$MB_Inball_HR_O = $row['MB_Inball_HR'];
						$TG_Inball_HR_O = $row['TG_Inball_HR'];
						
						$updateArr=array();
						if( $mb_inball != $MB_Inball_O && $mb_inball != ''){
						//if( $mb_inball != $MB_Inball_O && ($mb_inball > 0 || $mb_inball===0 || $mb_inball < 0) ){
							$updateArr['MB_Inball'] = $mb_inball;
						} 
						if( $tg_inball != $TG_Inball_O && $tg_inball != '' ){
						//if( $tg_inball != $TG_Inball_O && ($tg_inball > 0 || $tg_inball===0 || $tg_inball < 0) ){
							$updateArr['TG_Inball'] = $tg_inball;
						}

                        if( $mb_inball_hr != $MB_Inball_HR_O && $mb_inball_hr < 0 ){
						//if( $mb_inball_hr != $MB_Inball_HR_O && $mb_inball_hr != '' ){
							$updateArr['MB_Inball_HR'] = $mb_inball_hr;
						}

                        if( $tg_inball_hr != $TG_Inball_HR_O && $tg_inball_hr < 0 ){
						//if( $tg_inball_hr != $TG_Inball_HR_O && $tg_inball_hr != '' ){
							$updateArr['TG_Inball_HR'] = $tg_inball_hr;
						} 
						
						if( count($updateArr)==0 ){ continue; }
						$tmp=array();
						foreach($updateArr as $key=>$val){
							if( ( $key=='MB_Inball' || $key=='TG_Inball' ) && $val >= 0 ){
							//if( ( $key=='MB_Inball' || $key=='TG_Inball' ) && ($val >0 || $val===0)){
								if(strtotime($row['M_Start'])+90*60 < time()){
									$tmp[]=$key.'=\''.$val.'\'';
								}
							}else{
								$tmp[]=$key.'=\''.$val.'\'';
							}
						}
						if( count($tmp)==0 ){ continue; }
						if( $mb_inball<0 || $tg_inball<0 || $mb_inball_hr<0 || $tg_inball_hr<0 ){

//									$logContent='';
//									$logContent="FT196	".$mid_m."\n\r";
//									$logContent.=implode(',',$tmp)."\n\r";
//									$logContent.=date('Y-m-d H:i:s',time())."\n\r\n\r";
//									$file = "./resultSCore_".date('Y-m-d',time()).".txt";
//								    $handle = fopen($file, 'a+');
//								    ob_start(); //打开缓冲区
//								    echo $logContent;//打印信息
//								    $info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info
//									ob_clean(); //关闭缓冲区
//								    fwrite($handle, $info);
//								    fclose($handle);
							
							$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp).",Cancel=1,Score_Source=1 where Type='FT' and M_Date='".$list_date."' and MID=".(int)$mid_m;
							mysqli_query($dbMasterLink, $mysql) or die('abc');
					    }else{
                            $a=	$row['MB_Inball'].$row['TG_Inball'];
                            $b=	trim($mb_inball).trim($tg_inball);
							//$a=	$row['MB_Inball'].$row['TG_Inball'].$row['MB_Inball_HR'].$row['TG_Inball_HR'];
                            //$b=	trim($mb_inball).trim($tg_inball).trim($mb_inball_hr).trim($tg_inball_hr);
							if(strcmp($a,$b)!=0 && $row['Score_Source'] != 2 && $row['Score_Source'] != 3){
								$check=0;
								if($row['MB_Inball']!='' && trim($mb_inball)!=$row['MB_Inball'])	$check=1;
								if($row['TG_Inball']!='' && trim($tg_inball)!=$row['TG_Inball'])	$check=1;
								//if($row['MB_Inball_HR']!='' && trim($mb_inball_hr)!=$row['MB_Inball_HR'])	$check=1;
                                //if($row['TG_Inball_HR']!='' && trim($tg_inball_hr)!=$row['TG_Inball_HR'])	$check=1;
								
//									$logContent='';
//									$logContent="FT196	".$mid_m."\n\r";
//									$logContent.=implode(',',$tmp)."\n\r";
//									$logContent.=date('Y-m-d H:i:s',time())."\n\r\n\r";
//									$file = "./resultSCore_".date('Y-m-d',time()).".txt";
//								    $handle = fopen($file, 'a+');
//								    ob_start(); //打开缓冲区
//								    echo $logContent;//打印信息
//								    $info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info
//									ob_clean(); //关闭缓冲区
//								    fwrite($handle, $info);
//								    fclose($handle);
								
								$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp).",Checked='".$check."',Score_Source=1 where Type='FT' and M_Date='".$list_date."' and MID=".(int)$mid_m;
								mysqli_query($dbMasterLink, $mysql) or die('abc');
					    		$redisObj->pushMessage('MatchScorefinishList',(int)$mid_m);//加入派奖队列
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
<title>悠久乐足球接比分</title>
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
