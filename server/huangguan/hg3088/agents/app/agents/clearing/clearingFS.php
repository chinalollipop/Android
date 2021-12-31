<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function.php");

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];

require ("../include/traditional.zh-cn.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$loginname=$_SESSION['UserName'];
$lv = $_REQUEST['lv'];
$page=$_REQUEST['page'];
$date=date('Y-m-d');
$gtype=$_REQUEST['gtype'];
$gid=$_REQUEST['gid'];
$M_Start=$_REQUEST['start'];
$M_League=$_REQUEST['League'];
$MB_Team=$_REQUEST['mbTeam'];
$guanjun=$_REQUEST['guanjun'];

if( count($guanjun)<1) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$field_count=0;
$sendAwardTime='';
$mysql="select ID,Active,userid,M_Name,LineType,OpenType,BetTime,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,$middle as Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where FIND_IN_SET($gid,MID)>0 and Active=7 and LineType=16 and Mtype!='' and Cancel!=1 and Checked=0 order by linetype,mtype";
$result = mysqli_query($dbLink,$mysql);
$countBet=mysqli_num_rows($result);
if($countBet>0){
	while ($row = mysqli_fetch_assoc($result)){
			$sourceLog='';
			$moneyLogDesc='';
			$flag=true;
			$mtype=$row['Mtype'];
			$id=$row['ID'];
			$userid=$row['userid'];
			$user=$row['M_Name'];
			if($flag==false) {
				continue;	
			}
			if($row['M_Rate']<0){
				$num=str_replace("-","",$row['M_Rate']);
			}else if ($row['M_Rate']>0){
				$num=1;
			}
			if(in_array($row['Mtype'],$guanjun)){//赢
				$graded=1;
				$g_res=$row['Gwin'];
			}else{//输
				$graded=-1;
				$g_res=-$row['BetScore']*$num;
			}
			
			if($row['M_Rate']<=1.5){
				$vgold=0;
			}else{
				$vgold=abs($graded)*$row['BetScore'];
			}
			
			$betscore=number_format($row['BetScore'],2);
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
			$agent=$g_res*1;//公司退水帐目
			
			if( !mysqli_query($dbMasterLink, "START TRANSACTION")) {
				echo "冠军手动派奖事务开启失败！" ;
				continue;
			}
			$sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID='" . $row['ID'] ."' for update ";	
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
			$sendAwardTime=date('Y-m-d H:i:s',time());
			if($mb_in_score<0 and $mb_in_score_v<0){
				$cash=$row['BetScore'];
			}else{
				$cash=$row['BetScore']+$members;
		    }
			
			$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
	        if(!mysqli_query($dbMasterLink,$mysql)){
		   		echo "冠军派奖更新用户金额失败!";
				mysqli_query($dbMasterLink, "ROLLBACK");
				continue;
		    }
		    
		    //生成资金账变记录
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
		    
		    $moneyLogDesc.=",冠军人工结算,操作人:".$_SESSION['UserName'];
			//添加用户资金账变记录
			$userMoneyRow=mysqli_fetch_array($userMoneyLock);
			$moneyLogRes=addAccountRecords(array($userid,$user,$userMoneyRow['test_flag'],$userMoneyRow['Money'],$cash,$userMoneyRow['Money']+$cash,3,6,$id,$moneyLogDesc));
		    if(!$moneyLogRes){
		    	echo "用户自己账变日志写入失败!";
				mysqli_query($dbMasterLink, "ROLLBACK");
				continue;
		    }
			
		    $sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='".$_SESSION['UserName']."',Checked=1 where ID='$id'";
			
			if(mysqli_query($dbMasterLink,$sql)){
					mysqli_query($dbMasterLink, "COMMIT");
			}else{
				echo "派奖更新用户注单表失败!";
				mysqli_query($dbMasterLink, "ROLLBACK");
				continue;	
			}
			
			switch ($row['OddsType']){
				case 'H':
				    $Odds='<BR><font color =green>'.$Rep_HK.'</font>';
					break;
				case 'M':
				    $Odds='<BR><font color =green>'.$Rep_Malay.'</font>';
					break;
				case 'I':
				    $Odds='<BR><font color =green>'.$Rep_Indo.'</font>';
					break;
				case 'E':
				    $Odds='<BR><font color =green>'.$Rep_Euro.'</font>';
					break;
				case '':
				    $Odds='';
					break;
			}
			$time=strtotime($row['BetTime']);
			$times=date("Y-m-d",$time).'<br>'.date("H:i:s",$time);
			$field_count=$field_count+1;
	}
}

$mysql1="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set Score=1,Cancel=0 where MID='$gid'";
mysqli_query($dbMasterLink,$mysql1);	
if(count($guanjun)==1){
	$mysql2="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set win=1 where MID='$gid' and Gid='$guanjun[0]'";
	mysqli_query($dbMasterLink,$mysql2);			
}else{
	foreach($guanjun as $key){
		$guanjunNew[]="'".$key."'";
	}
	$guanjunStr=implode(',',$guanjunNew);
	$mysql2="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set win=1 where MID='$gid' and Gid in ($guanjunStr)";
	mysqli_query($dbMasterLink,$mysql2);		
}

echo "<SCRIPT language='javascript'>self.location='/app/agents/score/match.php?uid=$uid&langx=zh-cn&gtype=FS&page=$page';</script>";

?>
