<?php
/**
* 体育派奖脚本
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

//ini_set("display_errors", "on");
define("CONFIG_DIR", dirname(dirname(__FILE__)));
define("COMMON_DIR", dirname(dirname(dirname(__FILE__))));
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require_once(COMMON_DIR."/common/sportCenterData.php");
require(CONFIG_DIR."/app/agents/include/define_function.php");

$redisObj = new Ciredis();
$nums_bill_ids= 0;
$per_num_each_thread= 0;
$bill_ids=array();
$mrow = array();

$redisObj = new Ciredis();

//$test=array(3235250,3241714,2524342,3226740);
//$redisObj->execute(array('flushall'));
//foreach($test as $key=>$val){
//	$res =$redisObj->pushMessage('MatchScorefinishList',(int)$val);	
//}

//去redis里面取出对应的赛程出来
for($i=0;$i<100;$i++) {
	$gid = $redisObj->popMessage("MatchScorefinishList");
	$gid = $gid[0];
	if(isset($gid) && $gid != "") {
		$mmysql = "select `Type`,MB_Team,TG_Team,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$gid;
		
		log_note('$mmysql='.$mmysql."\r\n\r\n");

		$result = mysqli_query($dbMasterLink,$mmysql);
		$mrow=mysqli_fetch_array($result);

        $MB_Team = $mrow['MB_Team'];
        $TG_Team = $mrow['TG_Team'];

		$Type=$mrow['Type'];
		$mb_in_score=$mrow['MB_Inball'];
		
		log_note('$mb_in_score='.$mb_in_score."\r\n\r\n");	
		
		$tg_in_score=$mrow['TG_Inball'];
		log_note('$tg_in_score='.$tg_in_score."\r\n\r\n");

        if ($mrow['Type']=='BK' and $mb_in_score=='' and $tg_in_score==''){
            log_note("BK结算跳过此盘口：$gid ，赛事比分为空！！！\r\n\r\n");
            continue;
        }

		$mb_in_score_v=$mrow['MB_Inball_HR'];
		$tg_in_score_v=$mrow['TG_Inball_HR'];

        if ($mrow['Type']=='FT' and $mb_in_score_v=='' and $tg_in_score_v==''){
            log_note("FT结算跳过此盘口：$gid ，赛事比分为空！！！\r\n\r\n");
            continue;
        }elseif($mrow['Type']=='FT'){
            log_note('$mb_in_score_v='.$mb_in_score_v."\r\n\r\n");
            log_note('$tg_in_score_v='.$tg_in_score_v."\r\n\r\n");
        }
		//$mb_time=$mrow['MB_Inball_Time'];
		//$tg_time=$mrow['TG_Inball_Time'];
		
		$mb_time='';
		$tg_time='';
		$M_Start=$mrow['M_Start'];
		
		mysqli_query($dbMasterLink,"update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set Score=1 where MID=".$gid);

		//搜索出来这个赛程的所有的注单，有多少？
		//$mysql="select ID,Active,userid,M_Name,LineType,OpenType,BetTime,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where M_Result='' and checked=0 and FIND_IN_SET($gid,MID)>0 and (Active=1 or Active=11 or Active=2 or Active=22) and LineType!=8 and Cancel!=1 order by LineType";
        $mysql="select ID,Active,userid,M_Name,LineType,OpenType,BetTime,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where MID='$gid' and M_Result='' and checked=0 and Cancel=0 order by LineType";
		//这里如果还需要得到这个比赛的值，那么也可以拿去出来，设置为global变量，然后再sentAward函数里面应用
		$result = mysqli_query($dbMasterLink, $mysql);
		//把这些注单，平均到100个进程里面去派彩
		while($row=mysqli_fetch_array($result)){
            if($row['LineType']!=8 && $row['LineType']!=16){ $bill_ids[] = $row; }
		}
		
		$nums_bill_ids = count($bill_ids);
		if($nums_bill_ids==0){
			echo "赛事没有注单！！！";
			continue;
		}
		
		$per_num_each_thread = ceil($nums_bill_ids / 100);

		$worker_num =100;
		for($i=0;$i<$worker_num ; $i++){
			$process = new swoole_process("sentAward", true);
			$pid = $process->start();
			$process->write($i);
			$workers[] = $process;
		}
	}else {
		echo "没有需要派奖的赛事！！";
	}
}

//派彩的方法里面，需要加事务进行处理
function sentAward(swoole_process $worker) {
	global $per_num_each_thread,$bill_ids,$database,$gid,$mb_in_score,$mb_in_score_v,$Type;
	//里面是所有的派奖业务逻辑，这个时候，需要传递参数进去而已，根据参数进行派奖，需要添加锁
	$i = $worker->read();
	
	log_note("------------------------------------------------------------------------------------\r\n");
	
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
		echo "没有需要派奖的注单！";
		return true;
		exit();
	}

	/* 派奖的业务逻辑处理 */
	$result = checkPrize($BillArray);
	if($result==0 || empty($result)) {
		return true;
	}

	//从库的连接业务逻辑
	/*
	$slave_num = count($database['gameSlave']);
	$rand_slave_num = rand(1, $slave_num);
	$conn1 = mysqli_connect($database['gameSlave'][$rand_slave_num]['hostname'], $database['gameSlave'][$rand_slave_num]['username'], $database['gameSlave'][$rand_slave_num]['password'], $database['gameSlave'][$rand_slave_num]['database'], $database['gameSlave'][$rand_slave_num]['port']);
	if (mysqli_connect_errno()) {
		echo "数据库连接错误！";
	}
	*/
	
	//这里一定要重新连接，每个swoole里面的链接都需要重新的连下
	$conn = mysqli_connect($database['gameDefault']['host'], $database['gameDefault']['user'], $database['gameDefault']['password'], $database['gameDefault']['dbname'], $database['gameDefault']['port']);
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	mysqli_query($conn, "SET NAMES 'utf8'");
	
	
	foreach($result as $id=>$row){
		if( mysqli_query($conn, "START TRANSACTION") !== TRUE ) {
			echo "体育派奖事务开启" ;
			continue;
		}	
		$sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID='" . $id ."' for update ";
		$query=mysqli_query($conn, $sql_for_update);
		$bill_count_flag=mysqli_fetch_array($query);
		
		//订单已结算
		if( $bill_count_flag['checked'] == 1 ) {
			echo "订单已结算，事务回滚!";
			mysqli_query($conn, "ROLLBACK");
			continue;
		}
		
		$user=$row['user'];
		$userid=$row['userid'];
		$vgold=$row['vgold'];
		$members=$row['members'];
		$graded=$row['graded'];
		$agents=$row['agents'];
		$world=$row['world'];
		$corprator=$row['corprator'];
		$super=$row['super'];
		$agent=$row['agent'];
		
		$userMoneyLock = mysqli_query($conn,"select Money,test_flag from ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
		if(!$userMoneyLock){
			echo "用户资金锁添加失败!";
			mysqli_query($conn, "ROLLBACK");
			continue;
		}
		$sendAwardTime=date('Y-m-d H:i:s',time());
		if($mb_in_score<0 && $mb_in_score_v<0){//赛事取消之类处理  则把投注资金还给用户
	       $cash=$row['BetScore'];
		   $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
		   if(mysqli_query($conn,$mysql)){
		   		if($Type=="FT"){
		  			$sql="update ".DBPREFIX."web_report_data set VGOLD='0',M_Result='0',D_Result='0',C_Result='0',B_Result='0',A_Result='0',T_Result='0',Cancel=1,sendAwardTime='$sendAwardTime',sendAwardIsAuto=1,Checked=1,Confirmed='$mb_in_score',updateTime='".date('Y-m-d H:i:s',time())."' where MID='$gid' and (active=1 or active=11) and LineType!=8";
		   		}elseif($Type=="BK"){
		   			$sql="update ".DBPREFIX."web_report_data set VGOLD='0',M_Result='0',D_Result='0',C_Result='0',B_Result='0',A_Result='0',T_Result='0',Cancel=1,sendAwardTime='$sendAwardTime',sendAwardIsAuto=1,Checked=1,Confirmed='$mb_in_score',updateTime='".date('Y-m-d H:i:s',time())."' where MID='$gid' and (active=2 or active=22) and LineType!=8";
		   		}
		   }else{
		   		echo "更新用户资金失败！<br/>";
		   		mysqli_query($conn, "ROLLBACK");
		   }
		}else{
      	   $cash=$row['BetScore']+$members;
		   $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
		   if(mysqli_query($conn,$mysql)){
		   		$sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
		   }else{
		   		echo "更新用户资金失败！<br/>";
		   		mysqli_query($conn, "ROLLBACK");
		   }
		}
		
		if(!mysqli_query($conn,$sql)){
			echo "派奖失败！<br/>";
			mysqli_query($conn, "ROLLBACK");
			continue;
		}
		
		//生成资金账变记录
		if($mb_in_score<0 && $mb_in_score_v<0){
			$moneyLogDesc="取消注单,退还本金{$row['BetScore']}";
		}else{
			switch ($graded){
				case 1:
					$moneyLogDesc="赢:退还本金{$row['BetScore']},派奖$members";
					break;
				case 0.5:
					$moneyLogDesc="赢一半:退还本金{$row['BetScore']},派奖$members";
					break;
				case -1:
					$moneyLogDesc="输";
					break;
				case -0.5:
					$moneyLogDesc="输一半:退还一半本金$cash";
					break;
				case 0:
					$moneyLogDesc="和局:退还本金$cash";
					break;
			}
	    }
	    $moneyLogDesc.=",{$Type}自动结算";
		//添加用户资金账变记录
		$userMoneyRow=mysqli_fetch_assoc($userMoneyLock);
		$sqlMchange=$moneyAfter=$moneyLogTime='';
		$moneyAfter=$userMoneyRow['Money']+$cash;
		$moneyLogTime=time();
		$sqlMchange1 = "INSERT INTO ".DBPREFIX."web_account_change_records(`userid`,`userName`,`istest`,`currencyBefore`,`money`,`currencyAfter`,`type`, `source`,`addTime`,`orderid`,`description`)VALUES";
		$sqlMchange2 = "({$userid},'{$user}',{$userMoneyRow['test_flag']},{$userMoneyRow['Money']},{$cash},{$moneyAfter},3,8,'$moneyLogTime',$id,'{$moneyLogDesc}')";
		$sqlMchange = $sqlMchange1.$sqlMchange2;
		$moneyLogRes=mysqli_query($conn,$sqlMchange);
		
		if($moneyLogRes){
	    	mysqli_query($conn, "COMMIT");
	    }else{
	    	echo "用户自己账变日志写入失败!";
			mysqli_query($conn, "ROLLBACK");
			continue;
	    }
	}
	
	unset($BillArray);
	unset($result);
	unset($row);
	unset($user);
	unset($userid);
	unset($graded);
	unset($vgold);
	unset($members);
	unset($agents);
	unset($world);
	unset($corprator);
	unset($super);
	unset($agent);
	unset($cash);
	echo "count tiyu bill ok!";
	return true;
}

function checkPrize($rows){
	global $Type,$mb_in_score,$tg_in_score,$tg_in_score_v,$mb_in_score_v,$mb_time, $tg_time, $M_Start,$MB_Team,$TG_Team;
	$result=array();

	foreach($rows as $key=>$row){
		$flag=true;
		$mtype=$row['Mtype'];
		$id=$row['ID'];
		$user=$row['M_Name'];
		if($Type=="FT"){
			if(in_array($row['LineType'],array(11,12,13,14,204,15,46,205,206,19,20,31,50,244))){
				if( trim($tg_in_score_v)==='' || trim($mb_in_score_v)==='' ) {
					continue;
				}
			}else{
				if( trim($tg_in_score)==='' || trim($mb_in_score)==='' ) {
					continue;	
				}
			}
			switch ($row['LineType']){
					case 1://独赢
						$graded=win_chk($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 2://让球
						$graded=odds_letb($mb_in_score,$tg_in_score,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;
					case 3://大小
						$graded=odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
						break;
					case 4://波胆
						$graded=odds_pd($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 5://单双
						$graded=odds_eo($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 6://总入球
						$graded=odds_t($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 7://半场全场
						$graded=odds_half($mb_in_score_v,$tg_in_score_v,$mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 9://滚球让球
						$score=explode('<FONT color=red><b>',$row['Middle']);
						$msg=explode("</b></FONT><br>",$score[1]);
						$bcd=explode(":",$msg[0]);
						$m_in=$bcd[0];
						$t_in=$bcd[1];
						if ($row['ShowType']=='H'){
							$mbinscore1=$mb_in_score-$m_in;
							$tginscore1=$tg_in_score-$t_in;
						}else{
							$mbinscore1=$mb_in_score-$t_in;
							$tginscore1=$tg_in_score-$m_in;
						}
						$graded=odds_letb_rb($mbinscore1,$tginscore1,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;
					case 10://滚球大小
						$graded=odds_dime_rb($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);	
						break;
					case 11://半场独赢
						$graded=win_chk_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 12://半场让球
						$graded=odds_letb_v($mb_in_score_v,$tg_in_score_v,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;
					case 13://半场大小
						$graded=odds_dime_v($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$row['Mtype']);
						break;
					case 14://半场波胆
						$graded=odds_pd_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 15://半场单双
						$wMtype= substr($row['Mtype'],1);
						$graded=odds_eo($mb_in_score_v,$tg_in_score_v,$wMtype);
						break;
					case 46://半场总入球
						$graded=odds_t_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 18:	//净胜球数
						$graded=team_net_profit($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 19://半场滚球让球
						$score=explode('<FONT color=red><b>',$row['Middle']);
						$msg=explode("</b></FONT><br>",$score[1]);
						$bcd=explode(":",$msg[0]);
						$m_in=$bcd[0];
						$t_in=$bcd[1];
						if ($row['ShowType']=='H'){
							$mbinscore1=$mb_in_score_v-$m_in;
							$tginscore1=$tg_in_score_v-$t_in;
						}else{
							$mbinscore1=$mb_in_score_v-$t_in;
							$tginscore1=$tg_in_score_v-$m_in;
						}
						$graded=odds_letb_vrb($mbinscore1,$tginscore1,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;	
					case 20://半场滚球大小
						$graded=odds_dime_vrb($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$row['Mtype']);	
						break;
					case 21://滚球独赢
						$graded=win_chk_rb($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 22:	//独赢 & 进球 大 /小
						$graded=win_and_ou($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
						break;			
					case 23:	//独赢 & 双方球队进球
						$graded=win_and_doublein($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 24:	//进球 大 /小 & 双方球队进球
						$graded=ou_and_doublein($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
						break;
					case 25:	//独赢 & 最先进球
						$graded=win_and_firstin($mb_in_score,$tg_in_score,$mb_time,$tg_time,$row['Mtype']);
						break;
					case 28:	//最多进球的半场
						$graded=most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 29:	//最多进球的半场 - 独赢
						$graded=win_most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 30:	//双半场进球
						$wType = substr($row['Mtype'],2,1);
						$graded=double_half_in($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$wType);
						break;
					case 31://半场滚球独赢
						$graded=win_chk_vrb($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 32:	//首个进球时间-3项
						$wType = substr($row['Mtype'],3,1);
						$graded=time3_first_in($mb_time,$tg_time,$M_Start,$wType);
						break;
					case 33:	//首个进球时间
						$wType = substr($row['Mtype'],3,1);
						$graded=time_first_in($mb_time,$tg_time,$M_Start,$wType);
						break;
					case 34:	//双重机会 & 进球 大 / 小
						$graded=changeDouble_and_ou($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
						break;
					case 35:	//双重机会 & 双方球队进球
						$graded=change_and_in_double($mb_in_score,$tg_in_score,$row['Mtype']);
						break;	
					case 37:	//进球大小 && 进球单双 
						$graded=ou_and_oe_in($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
						break;	
					case 39:	//足球三项让球
						$MiddleStr = explode('<br>',$row['Middle']);
                        $rangArr = explode('@',$MiddleStr[3]);
                        $rangStr = trim($rangArr[0]);
                        if($row['Mtype']=='W3H'){ $rangStr=str_replace($MB_Team,'',$rangStr); }
                        if($row['Mtype']=='W3C'){ $rangStr=str_replace($TG_Team,'',$rangStr);  }
                        if($row['Mtype']=='W3N'){ $rangStr=str_replace('让球和局','',$rangStr); }
                        $rangStr=str_replace('&nbsp;','',$rangStr);
                        $rangStr = trim(strip_tags($rangStr));
                        if( strpos($rangStr,'-')<0 && strpos($rangStr,'+')<0 ){ continue; }
                        $graded = rb_three_bet($mb_in_score,$tg_in_score,$row['Mtype'],$rangStr);
						break;
					case 41:	//赢得任一半场
						$graded=win_any_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;		
					case 42:	//赢得所有半场
						$graded=win_all_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
						break;
					case 44://球队进球数大小	
						$graded=teamballin_odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
						break;	
					case 61:	//零失球获胜
						$graded=win_lost_inzero($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 62:	//零失球
						$graded=lost_inzero($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 65:	//双方球队进球
						$mypeNew = str_replace('TS','',$row['Mtype']);
						$graded=doublein($mb_in_score,$tg_in_score,$mypeNew);
						break;
					case 69:	//双重机会
						$wType = substr($row['Mtype'],2,2);
						$graded=change_double($mb_in_score,$tg_in_score,$wType);
						break;	
					case 104://滚球波胆
						$mtypeSub = $row['Mtype'];
						$mtypeSub = substr($mtypeSub,1);
						$mtypeSub = str_replace('H','MB',$mtypeSub);
						$mtypeSub = str_replace('C','TG',$mtypeSub);
						$graded=odds_pd($mb_in_score,$tg_in_score,$mtypeSub);
						break;				
					case 105://滚球单双
						$mtypeSub = substr($row['Mtype'],1);
						$graded=odds_eo($mb_in_score,$tg_in_score,$mtypeSub);
						break;
					case 106://滚球总入球
						$mtypeSub = substr($row['Mtype'],1);
						$graded=odds_t($mb_in_score,$tg_in_score,$mtypeSub);
						break;
					case 107://滚球半/全场
						$mtypeSub = substr($row['Mtype'],1);
						$graded=odds_half($mb_in_score_v,$tg_in_score_v,$mb_in_score,$tg_in_score,$mtypeSub);
						break;
					case 115:	//滚球双方球队进球
						$mypeNew = str_replace('RTS','',$row['Mtype']);
						$graded=doublein($mb_in_score,$tg_in_score,$mypeNew);
						break;
					case 118:	//净胜球数
						$wType = str_replace('R','',$row['Mtype']);
						$graded=team_net_profit($mb_in_score,$tg_in_score,$wType);
						break;
					case 119:	//双重机会
						$wType = substr($row['Mtype'],3,2);
						$graded=change_double($mb_in_score,$tg_in_score,$wType);
						break;	
					case 120:	//零失球
						$graded=lost_inzero($mb_in_score,$tg_in_score,str_replace("R", "",$row['Mtype']));
						break;
					case 122:	//独赢 & 进球 大 /小
						$graded=win_and_ou($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['M_Place']);
						break;
					case 123:	//独赢 & 双方球队进球
						$graded=win_and_doublein($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']));
						break;
					case 124:	//进球 大 /小 & 双方球队进球
						$graded=ou_and_doublein($mb_in_score,$tg_in_score,str_replace("R","O",$row['Mtype']),$row['M_Place']);
						break;
				    case 128:	//最多进球的半场
						$graded=most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
						break;
					case 129:	//最多进球的半场 - 独赢
						$graded=win_most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
						break;
					case 130:	//双半场进球
						$wType = substr($row['Mtype'],3,1);
						$graded=double_half_in($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$wType);
						break;
					case 134://双重机会 & 进球 大 / 小	
						$graded=changeDouble_and_ou($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['M_Place']);
						break;
					case 135:	//双重机会 & 双方球队进球
						$graded=change_and_in_double($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']));
						break;
					case 137:	//进球大小 && 进球单双 
						$graded=ou_and_oe_in($mb_in_score,$tg_in_score,str_replace("R","O",$row['Mtype']),$row['M_Place']);
						break;
					case 139:	//滚球足球三项让球
						$graded=rb_three_bet($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['ShowType']);
						break;
					case 141:	//赢得任一半场
						$graded=win_any_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
						break;
					case 142:	//赢得所有半场
						$graded=win_all_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
						break;
					case 144://半场球队进球数大小	
						$wType = substr($row['Mtype'],1);
						$graded=teamballin_odds_dime($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$wType);
						break;
					case 154://滚球球队进球数大小
						$mypeNew = $row['Mtype'];
						$mypeNew = substr($mypeNew,1);
						$graded=teamballin_odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$mypeNew);
						break;	
					case 161:
						$graded=win_lost_inzero($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']));
						break;
					case 165:	//半场双方球队进球
						$mypeNew = str_replace('HTS','',$row['Mtype']);
						$graded=doublein($mb_in_score_v,$tg_in_score_v,$mypeNew);
						break;	
					case 204://半场滚球波胆
						$wType = str_replace('HR','',$row['Mtype']);
						$wType = str_replace('H','MB',$wType);
						$wType = str_replace('C','TG',$wType);
						$graded=odds_pd_v($mb_in_score_v,$tg_in_score_v,$wType);
						break;
					case 205://半场单双
						$wMtype= substr($row['Mtype'],2);
						$graded=odds_eo($mb_in_score_v,$tg_in_score_v,$wMtype);
						break;
					case 206://滚球半场总入球
						$mtypeSub = str_replace("R","",$row['Mtype']);
						$graded=odds_t_v($mb_in_score_v,$tg_in_score_v,$mtypeSub);
						break;
					case 244://滚球半场球队进球数大小	
						$wType = str_replace('R','',$row['Mtype']);
						$wType = substr($wType,1);
						$graded=teamballin_odds_dime($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$wType);
						break;	
					default: 
						$flag=false;
				}
		}elseif($Type=="BK"){
			switch ($row['LineType']){
					case 1://独赢
						$graded=win_chk($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 2://让球
						$graded=odds_letb($mb_in_score,$tg_in_score,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;
					case 3://大小
						$graded=odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
						break;
					case 5://单双
						$graded=odds_eo($mb_in_score,$tg_in_score,$row['Mtype']);
						break;	
					case 9://滚球让球
						$graded=odds_letb_rb($mb_in_score,$tg_in_score,$row['ShowType'],$row['M_Place'],$row['Mtype']);
						break;
					case 10://滚球大小
						$graded=odds_dime_rb($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);	
						break;
					case 13://球队得分大小
                        $ouStr='';
                        $ouStr = substr($row['M_Place'],0,1);
                        if($ouStr=='U' || $ouStr=='O'){
                            $graded=team_score_ou($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype'].$ouStr);
                        }else{
                            $graded=team_score_ou($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
                        }
                        break;
					case 21://滚球独赢
						$graded=win_chk_rb($mb_in_score,$tg_in_score,$row['Mtype']);
						break;
					case 23://滚球球队得分大小
						$wMtype=substr($row['Mtype'],1);
                        $ouStr='';
                        $ouStr = substr($row['M_Place'],0,1);
						$graded=team_score_ou($mb_in_score,$tg_in_score,$row['M_Place'],$wMtype.$ouStr);
						break;
					case 31://球队得分最后一位数
						$graded=store_last_num($mb_in_score,$tg_in_score,$row['Mtype']);	
						break;	
					case 105://滚球单双
						$graded=odds_eo($mb_in_score,$tg_in_score,substr($row['Mtype'],1));
						break;
					case 131://球队得分最后一位数 滚球
						$graded=store_last_num($mb_in_score,$tg_in_score,$row['Mtype']);	
						break;	
					default: 
						$flag=false;
				}
		}
		
		if($flag==false) {
			continue;	
		}
		
		if($row['M_Rate']<0){
			$num=str_replace("-","",$row['M_Rate']);
		}elseif($row['M_Rate']>0){
			$num=1;
		}
		switch ($graded){
			case 1:
				$g_res=$row['Gwin'];
				break;
			case 0.5:
				$g_res=$row['Gwin']*0.5;
				break;
			case -0.5:
				$g_res=-$row['BetScore']*0.5*$num;
				break;
			case -1:
				$g_res=-$row['BetScore']*$num;
				break;
			case 0:
				$g_res=0;
				break;
		}
		
		if(in_array($row['LineType'],array(2,3,9,10,12,13,19,20,44,144,154,244)) || ($row['LineType']==23 && $Type=="BK")){//让球、大小、球队进球数大小，不包含本金
			if($row['M_Rate']<=0.5){
				$vgold=0;
			}else{
				$vgold=abs($graded)*$row['BetScore'];	
			}	
		}else{//包含本金
			if($row['M_Rate']<=1.5){
				$vgold=0;
			}else{
				$vgold=abs($graded)*$row['BetScore'];	
			}
		}	
		
		$betscore=$row['BetScore'];
		$d_point=$row['D_Point']/100;
		$c_point=$row['C_Point']/100;
		$b_point=$row['B_Point']/100;
		$a_point=$row['A_Point']/100;
		
		$members=$g_res;//和会员结帐的金额
		$agents=$g_res*(1-$d_point);//上缴总代理结帐的金额
		$world=$g_res*(1-$c_point-$d_point);//上缴股东结帐
		if (1-$b_point-$c_point-$d_point!=0){
			$corprator=$g_res*(1-$b_point-$c_point-$d_point);//上缴公司结帐
		}else{
			$corprator=$g_res*($b_point+$a_point);//和公司结帐
		}
		$super=$g_res*$a_point;//和公司结帐
		$agent=$g_res;//公司退水帐目
		
		$result[$id]['user']=$user;
		$result[$id]['userid']=$row['userid'];
		$result[$id]['graded']=$graded;
		$result[$id]['vgold']=$vgold;
		$result[$id]['members']=$members;
		$result[$id]['agents']=$agents;
		$result[$id]['world']=$world;
		$result[$id]['corprator']=$corprator;
		$result[$id]['super']=$super;
		$result[$id]['agent']=$agent;
		$result[$id]['BetScore']=$row['BetScore'];
		
		$logContent='';
		$logContent=$Type."	".$row['ID']."\n\r";
		$logContent.="mb_in_score:".$mb_in_score."	tg_in_score:".$tg_in_score."\n\r";
		$logContent.="ShowType:".$row['ShowType']."	M_Place:".$row['M_Place']."		Mtype:".$row['Mtype']."\n\r";
		$logContent.="graded:".$graded."		g_res:".$g_res."		turn:".$turn."		members:".$members."\n\r\n\r";
		log_note($logContent."\r\n\r\n");
				
	}
	return $result;
}

function log_note($info) {	
	//ob_start(); //打开缓冲区  
	//var_dump($database);
	//$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
	//ob_clean(); //关闭缓冲区
	//log_note($info."\r\n");
	$dir = dirname(__FILE__);
	$file = $dir."/paijiang_note".date("ymd").".txt";
	$handle = fopen($file, 'a+');
	fwrite($handle, $info);
	fclose($handle);
}

?>
