<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    require(CONFIG_DIR."/include/config.inc.php");
    require(CONFIG_DIR."/include/define_function.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require ("../include/define_function.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$settime=$row['udp_bk_score'];
$time=$row['udp_bk_results'];
$date=date('Y-m-d',time()-$time*60*60);
$mysql="select ID,MID,userid,Active,M_Name,LineType,OpenType,BetTime,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked,Confirmed from ".DBPREFIX."web_report_data where M_Date='$date' and LineType=8 and Checked=0 and (Active=2 or Active=22) and cancel=0";
$result = mysqli_query($dbLink, $mysql);
while ($row = mysqli_fetch_assoc($result)){
	$k++;	
	$confirmArr=array();
	$sendAwardTime='';
	$notgraded=0;
	$id=$row['ID'];
	$userid=$row['userid'];
	$user=$row['M_Name'];
	$winrate=1;
	
	echo 'Time:'.$row['BetTime'].'<br>';
	echo '<br>'.$row['Middle'].'<br>';
	echo 'Mtype:'.$row['Mtype'].'<br>';
	echo 'Show:'.$row['ShowType'].'<br>';
	echo 'Place:'.$row['M_Place'].'<br>';
	$mid=explode(',',$row['MID']);
	$mtype=explode(',',$row['Mtype']);
	$rate=explode(',',$row['M_Rate']);
	$letb=explode(',',$row['M_Place']);
	$show=explode(',',$row['ShowType']);
	$confirmArr=explode(',',$row['Confirmed']);
	$cou=sizeof($mid);
	$count=0;
	for($i=0;$i<$cou;$i++){
		if(in_array($mid[$i],$confirmArr)){
				$graded=88;
		}else{
			$sql="select MB_Inball,TG_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".$mid[$i];
			$result1 = mysqli_query($dbLink, $sql);
			$rowr = mysqli_fetch_assoc($result1);
			$mb_in=$rowr['MB_Inball'];
			$tg_in=$rowr['TG_Inball'];
			if ($show[$i]=='H'){
			    echo $mb_in.'-'.$tg_in.'<br>';
			}else{
			    echo $tg_in.'-'.$mb_in.'<br>';
			}
			$mtypeFirst='';
			if ($mb_in=='' or $tg_in==''){
				$graded="99";
				$notgraded=1;
				echo '<font color=white>还有未完场赛事</font><br>';
				echo '<font color=white>'.$row['BetTime'].'-'.$row['M_Name'].'</font><br><br>';
                exit();
			}else if ($mb_in<0){
				$graded=88;
			}else{

                if ($mtype[$i]=='MH' or $mtype[$i]=='MC'){ // 篮球综合过关只有主队独赢与客队独赢
                    $graded=win_chk($mb_in,$tg_in,$mtype[$i]);
                }
                elseif ($mtype[$i]=='RH' or $mtype[$i]=='RC'){ // 让球
                    $graded=odds_letb($mb_in,$tg_in,$show[$i],$letb[$i],$mtype[$i]);
                }
                else if($mtype[$i]=='ODD' or $mtype[$i]=='EVEN'){
                    $graded=odds_eo($mb_in,$tg_in,$mtype[$i]);
                }
                else{ // 大小
                    $mtypeFirst=strtolower(substr($mtype[$i],0,1));
                    if($mtypeFirst=='t'){
                        $ouStr='';
                        $ouStr = substr($letb[$i],0,1);
                        if($ouStr=='U' || $ouStr=='O'){
                            $graded=team_score_ou($mb_in,$tg_in,$letb[$i],str_replace('T','',$mtype[$i]).$ouStr);
                        }else{
                            $graded=team_score_ou($mb_in,$tg_in,$letb[$i],str_replace('T','',$mtype[$i]));
                        }
                    }else{
                        $graded=odds_dime($mb_in,$tg_in,$letb[$i],$mtype[$i]);
                    }
                }

            }
		}
		switch ($graded){
			case "1":
				$winrate=$winrate*($rate[$i]);
				break;
			case "-1":
				$winrate=0;
				break;
			case "0":
				$winrate=$winrate;
				break;
			case "0.5":					
				$winrate=$winrate*(($rate[$i]-1)*0.5+1);
				break;
			case "-0.5":
				$winrate=$winrate*0.5;
				break;
			case "99":
				$winrate=$winrate;
				break;
			case "88":
				$winrate=$winrate;
				break;
			}
			if ($graded==-1){
				$winrate=0;
				$notgraded=0;
				break;
			}
	}

	$sendAwardTime=date('Y-m-d H:i:s',time());
	if ($notgraded==0){	
	    $g_res=$row['BetScore']*(abs($winrate)-1);	
		$vgold=$row['BetScore'];
		$d_point=$row['D_Point']/100;
		$c_point=$row['C_Point']/100;
		$b_point=$row['B_Point']/100;
		$a_point=$row['A_Point']/100;
		$members=$g_res;//和会员结帐的金额

		$agents=$g_res*(1-$d_point);//上缴总代理结帐的金额
		$world=$g_res*(1-$c_point-$d_point);//上缴股东结帐
		if(1-$b_point-$c_point-$d_point!=0){
			$corprator=$g_res*(1-$b_point-$c_point-$d_point);//上缴公司结帐
		}else{
			$corprator=$g_res*($b_point+$a_point);//和公司结帐
		}
		$super=$g_res*$a_point;//和公司结帐
		$agent=$g_res;//代理商退水总帐目
		
		$sendAwardTime=date('Y-m-d H:i:s',time());
		if( !mysqli_query($dbMasterLink, "START TRANSACTION")) {
			echo "篮球手动派奖事务开启失败！" ;
			continue;
		}
		$sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID=$id for update ";	
		$query=mysqli_query($dbMasterLink,$sql_for_update);
	    $bill_count_flag=mysqli_fetch_array($query);
		//订单已结算
		if( $bill_count_flag['checked'] == 1 ) {
			echo "订单已结算，事务回滚!";
			mysqli_query($dbMasterLink, "ROLLBACK");
			continue;
		}
		
		$userMoneyLock = mysqli_query($dbMasterLink,"select Money,test_flag from ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
		if(!$userMoneyLock){
			echo "用户资金锁添加失败!";
			mysqli_query($dbMasterLink, "ROLLBACK");
			continue;
		}
		
	    
		$cash=$row['BetScore']+$members;
		$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
		if(!mysqli_query($dbMasterLink,$mysql)){
	   		echo "派奖更新用户金额失败!";
			mysqli_query($dbMasterLink, "ROLLBACK");
			continue;
	    }
	    
	  	//生成资金账变记录
		if($mb_in<0){
			$moneyLogDesc="取消注单,退还本金{$row['BetScore']}";
		}else{
			if($members>0){
				$moneyLogDesc="赢:退还本金{$row['BetScore']},派奖$members";;
			}elseif($members<0){
				$moneyLogDesc="输";
			}elseif($members==0){
				$moneyLogDesc="和局:退还本金$cash";
			}else{
				$moneyLogDesc="";
			}
		}
	    $moneyLogDesc.=",BK综合过关自动结算";
		//添加用户资金账变记录
		$userMoneyRow=mysqli_fetch_array($userMoneyLock);
		$moneyLogRes=addAccountRecords(array($userid,$user,$userMoneyRow['test_flag'],$userMoneyRow['Money'],$cash,$userMoneyRow['Money']+$cash,3,9,$id,$moneyLogDesc));
		if(!$moneyLogRes){
	    	echo "用户自己账变日志写入失败!";
			mysqli_query($dbMasterLink, "ROLLBACK");
			continue;
	    }
		$sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		if(mysqli_query($dbMasterLink,$sql)){
			mysqli_query($dbMasterLink, "COMMIT");
		}else{
			echo "派奖更新用户注单表失败!";
			mysqli_query($dbMasterLink, "ROLLBACK");
			continue;	
		}
		echo '<font color=white>'.$row['BetTime'].'--'.$row['M_Name'].'--</font><font color=red>('.$members.')</font><br><br>';	
	}else{
		$sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',D_Result='',C_Result='',B_Result='',A_Result='',T_Result='',updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		mysqli_query($dbMasterLink,$sql) or die ("error!!");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>篮球过关结算</title>
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
		curtime=curmin+"秒后自动获取最新数据！" 
	else 
		curtime=cursec+"秒后自动获取最新数据！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<body>
<table width="220" height="190" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="220" height="190" align="center"><?php echo $date?><br><br><span id="timeinfo"></span><br>
	<input type=button name=button value="篮球过关刷新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
