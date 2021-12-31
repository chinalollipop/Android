<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require(CONFIG_DIR."/include/traditional.zh-cn.inc.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
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
$time=$row['udp_ft_results'];
$list_date=date('Y-m-d',time()-$time*60*60);
/*
$list_date="2018-08-07";
var_dump($list_date);
*/
$m=0;
$langx="zh-cn";
$accoutArr = array();
$accoutArr=getFlushWaterAccount();//数组随机排序

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

    $curl->set_referrer("{$value[Datasite]}/app/member/account/index.php?uid={$value[Uid]}&langx={$langx}");
    $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/account/result/result.php?game_type=FT&list_date=".$list_date."&uid=".$value['Uid']."&langx=".$langx);

    $data = get_content_deal($html_data);

	$cou=sizeof($data);
    //可以抓到数据
	if($cou>0 && $data[0]!='' ){
            for ($i=1;$i<sizeof($data);$i++){

                    $mb_inball=$mb_inball_hr=$tg_inball=$tg_inball_hr=$mid_m='';
                    $abcde = array();
			        $abcde=explode("tdrowspan=2class=acc_result_bg",$data[$i]);
                    //获取MID
                    $mid_m=explode(">",$abcde[0]);
                    $mid_m=explode("_",$mid_m[0]);
                    $mid_m=$mid_m[2];
                    //echo $mid_m;

                    if(strpos($abcde[0], "tdrowspan=2class=acc_result_time") !== false){
                        //主队比分
                        $hr = explode("<spanclass=acc_cont_bold>",$abcde[0]);
                        $mb_inball_hr=explode("</td>",$hr[1]);
                        $mb_inball=$mb_inball_hr[0];
                        $mb_inball_hr=explode("</td>",$hr[2]);
                        $mb_inball_hr=$mb_inball_hr[0];

                        //客队比分
                        $full = explode("<spanclass=acc_cont_bold>",$abcde[1]);
                        $tg_inball=explode("</td>",$full[1]);
                        $tg_inball=$tg_inball[0];
                        $tg_inball_hr=explode("</td>",$full[2]);
                        $tg_inball_hr=$tg_inball_hr[0];
                    }elseif(strpos($abcde[0], "tdrowspan=3class=acc_result_time") !== false){
                        //主队比分
                        $hr = explode("</tr>",$abcde[1]);
                        $mb=explode("<spanclass=acc_cont_bold>",$hr[1]);
                        $mb_inball= explode("</td>",$mb[1])[0];
                        $mb_inball_hr = explode("</td>",$mb[2])[0];

                        //客队比分
                        $tg=explode("<spanclass=acc_cont_bold>",$hr[2]);
                        $tg_inball= explode("</td>",$tg[1])[0];
                        $tg_inball_hr = explode("</td>",$tg[2])[0];

                    }

                $mb_inball_special=$mb_inball_hr_special=$tg_inball_special=$tg_inball_hr_special=array();
                if(strpos($mb_inball, "(比分)") !== false){
                    $mb_inball_special = explode("(比分)",$mb_inball);
                    $mb_inball = $mb_inball_special[0];
                }
                if(strpos($mb_inball_hr, "(比分)") !== false){
                    $mb_inball_hr_special = explode("(比分)",$mb_inball_hr);
                    $mb_inball_hr = $mb_inball_hr_special[0];
                }
                if(strpos($tg_inball, "(比分)") !== false){
                    $tg_inball_special = explode("(比分)",$tg_inball);
                    $tg_inball = $tg_inball_special[0];
                }
                if(strpos($tg_inball_hr, "(比分)") !== false){
                    $tg_inball_hr_special = explode("(比分)",$tg_inball_hr);
                    $tg_inball_hr = $tg_inball_hr_special[0];
                }

				    if($tg_inball==$Score1){
							$mb_inball='-1';
							$tg_inball='-1';
					}
					if ($tg_inball_hr==$Score1){
						$mb_inball_hr='-1';
						$tg_inball_hr='-1';		
					}
					if ($tg_inball==$Score2){
						$mb_inball='-2';
						$tg_inball='-2';
					}
					if ($tg_inball_hr==$Score2){
						$mb_inball_hr='-2';
						$tg_inball_hr='-2';	
					}
					if ($tg_inball==$Score3){
						$mb_inball='-3';
						$tg_inball='-3';
					}
					if ($tg_inball_hr==$Score3){
						$mb_inball_hr='-3';
						$tg_inball_hr='-3';
					}
					if ($tg_inball==$Score4){
						$mb_inball='-4';
						$tg_inball='-4';					
					}
					if ($tg_inball_hr==$Score4){
						$mb_inball_hr='-4';
						$tg_inball_hr='-4';
					}
					if ($tg_inball==$Score5){
						$mb_inball='-5';
						$tg_inball='-5';
					}
					if ($tg_inball_hr==$Score5){
						$mb_inball_hr='-5';
						$tg_inball_hr='-5';							
					}
					if ($tg_inball==$Score6){
						$mb_inball='-6';
						$tg_inball='-6';
					}
					if ($tg_inball_hr==$Score6){
						$mb_inball_hr='-6';
						$tg_inball_hr='-6';				
					}
					if ($tg_inball=='赛事无pk/加时' || $tg_inball=='賽事無pk/加時'){
						$mb_inball='-7';
						$tg_inball='-7';				
					}
					if ($tg_inball_hr=='赛事无pk/加时' || $tg_inball=='賽事無pk/加時'){
						$mb_inball_hr='-7';
						$tg_inball_hr='-7';
					}
					if ($tg_inball==$Score8){
						$mb_inball='-8';
						$tg_inball='-8';
					}
					if ($tg_inball_hr==$Score8){
						$mb_inball_hr='-8';
						$tg_inball_hr='-8';
					}
					/*if ($tg_inball==$Score9){
						$mb_inball='-9';
						$tg_inball='-9';	
					}
					if ($tg_inball_hr==$Score9){
						$mb_inball_hr='-9';
						$tg_inball_hr='-9';			
					}*/
					if ($tg_inball==$Score10){
						$mb_inball='-10';
						$tg_inball='-10';
					}
					if ($tg_inball_hr==$Score10){
						$mb_inball_hr='-10';
						$tg_inball_hr='-10';							
					}
					if ($tg_inball==$Score11){
						$mb_inball='-11';
						$tg_inball='-11';
					}
					if ($tg_inball_hr==$Score11){
						$mb_inball_hr='-11';
						$tg_inball_hr='-11';				
					}
					if ($tg_inball==$Score12){
						$mb_inball='-12';
						$tg_inball='-12';				
					}
					if ($tg_inball_hr==$Score12){
						$mb_inball_hr='-12';
						$tg_inball_hr='-12';
					}
					if ($tg_inball==$Score13){
						$mb_inball='-13';
						$tg_inball='-13';
					}
					if ($tg_inball_hr==$Score13){
						$mb_inball_hr='-13';
						$tg_inball_hr='-13';
					}
					if ($tg_inball==$Score14){
						$mb_inball='-14';
						$tg_inball='-14';					
					}
					if ($tg_inball_hr==$Score14){
						$mb_inball_hr='-14';
						$tg_inball_hr='-14';
					}
					if ($tg_inball==$Score15){
						$mb_inball='-15';
						$tg_inball='-15';
					}
					if ($tg_inball_hr==$Score15){
						$mb_inball_hr='-15';
						$tg_inball_hr='-15';								
					}
					if ($tg_inball==$Score16){
						$mb_inball='-16';
						$tg_inball='-16';
					}
					if ($tg_inball_hr==$Score16){
						$mb_inball_hr='-16';
						$tg_inball_hr='-16';				
					}
					if ($tg_inball==$Score17){
						$mb_inball='-17';
						$tg_inball='-17';				
					}
					if ($tg_inball_hr==$Score17){
						$mb_inball_hr='-17';
						$tg_inball_hr='-17';
					}
					if ($tg_inball==$Score18){
						$mb_inball='-18';
						$tg_inball='-18';
					}
					if ($tg_inball_hr==$Score18){
						$mb_inball_hr='-18';
						$tg_inball_hr='-18';
					}
					if ($tg_inball==$Score19){
						$mb_inball='-19';
						$tg_inball='-19';	
					}
					if ($tg_inball_hr==$Score19){
						$mb_inball_hr='-19';
						$tg_inball_hr='-19';	
					}
					if ($tg_inball==$Score51){
						$mb_inball='-51';
						$tg_inball='-51';	
					}
					if ($tg_inball_hr==$Score51){
						$mb_inball_hr='-51';
						$tg_inball_hr='-51';	
					}
					if ($tg_inball==$Score52){
						$mb_inball='-52';
						$tg_inball='-52';	
					}
					if ($tg_inball_hr==$Score52){
						$mb_inball_hr='-52';
						$tg_inball_hr='-52';	
					}
                    if ($tg_inball==$Score53){
                        $mb_inball='-53';
                        $tg_inball='-53';
                    }
					if ($tg_inball_hr==$Score53){
						$mb_inball_hr='-53';
						$tg_inball_hr='-53';
					}
						
					$sql="select MB_Team,TG_Team,M_League,MB_Inball,Score_Source,M_Start from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID='$mid_m' and  M_Date='".$list_date."'";
					$result = mysqli_query($dbLink,$sql);
					$cou=mysqli_num_rows($result);
					$row = mysqli_fetch_assoc($result);
					if ($cou>0){
                        if( $row['Score_Source']==3 ){ continue; }//如果管理员已经处理过，则跳出当前赛事，继续下一个赛事
                        if( $row['M_Start'] > date('Y-m-d H:i:s',time()) ){ continue; }//如果当前时间早于开赛时间，则跳出当前赛事，继续下一个赛事
						$MB_Team=$row['MB_Team'];
						$TG_Team=$row['TG_Team'];
						$M_League=$row["M_League"];
						$M_Start=$row["M_Start"];
						if($mb_team<>"" or $tg_team<>""){
							$sqlq="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where M_Start='$M_Start' and Type='FT' and M_League='$M_League' and  TG_Team='$TG_Team' and MB_Team='$MB_Team' and M_Date='".$list_date."'";
							$resultq = mysqli_query($dbLink,$sqlq);
							while($rowq = mysqli_fetch_assoc($resultq)){
								$mid = $rowq["MID"];
								if( ($mb_inball<0 && $mb_inball!=$rowq["MB_Inball"]) || ($mb_inball_hr<0 && $mb_inball_hr!=$rowq['MB_Inball_HR']) ){
                                    $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Score_Source=2,Cancel=1,Score=1 where `Type`='FT' and M_Date='".$list_date."' and `MID`='".(int)$mid."'";
                                    mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
								}else{
									if($rowq['MB_Inball']==""){
									        //检查比赛是否开始，开始后在更新比分
                                            if( strlen($rowq['M_Start'])>0 && date('Y-m-d H:i:s',time()-600 ) > $rowq['M_Start'] ){
                                                $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Score_Source=2 where Type='FT' and M_Date='".$list_date."' and MID=".(int)$mid;
                                                mysqli_query($dbMasterLink, $mysql) or die('abc');
                                                $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                            }
									}else{
										$m_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID='".(int)$mid."' and M_Date='".$list_date."'";
										$m_result = mysqli_query($dbLink,$m_sql);
										$m_row = mysqli_fetch_assoc($m_result);
										$a=	$m_row['MB_Inball'].$m_row['TG_Inball'].$m_row['MB_Inball_HR'].$m_row['TG_Inball_HR'];
										$b=	trim($mb_inball).trim($tg_inball).trim($mb_inball_hr).trim($tg_inball_hr);
										if (strcmp($a,$b)!=0){
											$check=1;
											$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='".(int)$mb_inball."',TG_Inball='".(int)$tg_inball."',MB_Inball_HR='".(int)$mb_inball_hr."',TG_Inball_HR='".(int)$tg_inball_hr."',Checked='".$check."',Score_Source=2 where Type='FT' and M_Date='".$list_date."' and MID=".(int)$mid;
											mysqli_query($dbMasterLink, $mysql) or die('abc');
											$redisObj->pushMessage('MatchScorefinishList',(int)$mid);
										}
									}
									$m=$m+1;		
								}
							}
						}	
					}
				}
	    }
    if($m>0){	break;	}
}

function get_content_deal($html_data){
	$html_data = strtolower($html_data);
	$a = array(
		"<script>",
		"</script>",
		'"',
		"\n\n",
		"<br>",
		" ",
		'</b></font>',
		"<td>",
		"<tdalign=left>",
		"<fontcolor=#cc0000>",
		"<fontcolor=red>",
		"<b>",
		"</b>",
		"</a>",
		"</font>",
		"<spanstyle=overflow:hidden;>",
		"</span>",
		"&nbsp;&nbsp;",
		"acc_result_tr_topBL"
	);
	$b = array(
		"",
		"",
		"",
		"",
		"-",
		"",
		'',
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"acc_result_tr_top"
	);
	$msg = str_replace($a,$b,$html_data);
    $data1 = explode( "<tdclass=acc_results_otherw></td>" , $msg);
    $data1[1] = str_replace('acc_result_tr_topblid','acc_result_tr_topid',$data1[1]);
    $data1[1] = str_replace('<spanclass=blackword>','<spanclass=acc_cont_bold>',$data1[1]);
    $data1[1] = str_replace('<spanclass=acc_result_post>','<spanclass=acc_cont_bold>',$data1[1]);
    $data1[1] = str_replace('tdrowspan=3class=acc_result_bg','tdrowspan=2class=acc_result_bg',$data1[1]);
    $data=explode("<trclass=acc_result_tr_topid=",$data1[1]);
    return $data;
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>正网足球接比分</title>
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
