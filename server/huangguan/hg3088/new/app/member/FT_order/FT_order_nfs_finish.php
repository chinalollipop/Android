<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$gid=$_REQUEST['gid'];
$tid=$_REQUEST['tid'];
$gametype=$_REQUEST['gametype'];
$rtype=$_REQUEST['rtype'];
$wtype=$_REQUEST['wtype'];
$gold=$_REQUEST['gold'];
$active=$_REQUEST['active'];
$ioradio_fs=$_REQUEST['ioradio_fs'];
$line=$_REQUEST['line_type'];
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$token=$_REQUEST['token'];
if($token == $_SESSION['bet_token']){ // 防止重复订单
    echo resubmitAction() ;
    exit();
}else{
    $_SESSION['bet_token'] = $token ;
}
$sql = "select Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0 order by ID";
$result = mysqli_query($dbLink,$sql);

$memrow = mysqli_fetch_assoc($result);
$open=$_SESSION['OpenType'];
$pay_type =$_SESSION['Pay_Type'];
$memname=$_SESSION['UserName'];
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$HMoney=$memrow['Money'];
$w_current=$_SESSION['CurType'];
if($HMoney < $gold || $gold<10 || $HMoney<=0){
	echo attention("$User_insufficient_balance",$uid,$langx);
	exit();
}
$memid= $_SESSION['userid'];
$test_flag=$_SESSION['test_flag'];

//判断此赛程是否已经关闭：取出此场次信息and inball=''
$mysql = "select * from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where `M_Start`>now() and MID='$gid' and  Gid='$rtype'";
$result = mysqli_query($dbMasterLink,$mysql);

$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
if ($cou==0){
	echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
	exit();
}else{

	$turn_url="/app/member/FT_order/FT_order_nfs.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&gametype=".$gametype."&langx=".$langx;
	//下注时间Date('Y').'-'.   $row["ShowType"]
	$m_date=date("Y-m-d",strtotime($row["M_Start"]));
	$showtype=$row["ShowTypeR"];
	$bettime=date('Y-m-d H:i:s');
	
	//联盟处理:生成写入数据库的联盟样式和显示的样式，二者有区别
	$w_gtype=$row['Gid'];
	$w_sleague=$row['M_League'];
	$w_sleague_tw=$row['M_League_tw'];
	$w_sleague_en=$row['M_League_en'];
	$s_sleague=$row[$m_league];
	
	$w_sitem=$row['M_Item'];
	$w_sitem_tw=$row['M_Item_tw'];
	$w_sitem_en=$row['M_Item_en'];
	$s_sitem=$gametype."冠军";
	$m_item=$row[$m_item];
	
	//根据下注的类型进行处理：构建成新的数据格式，准备写入数据库

	$bet_type='冠军';
	$bet_type_tw="冠軍";
	$bet_type_en="Outright";
	
	$ftype=$row['mshow'];
	$w_mb_team=$row['MB_Team'];
	$w_mb_team_tw=$row['MB_Team_tw'];
	$w_mb_team_en=$row['MB_Team_en'];
	$s_mb_team=$team[$i];
	$s_m_rate=change_rate($open,$row['M_Rate']);

	
	$gwin=($s_m_rate-1)*$gold;
	$wtype=$gametype;
	
	$lines=$row['M_League']."&nbsp;-&nbsp;".$w_mb_team."<br>".$row['M_Item']."&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>".$s_m_rate."</b></FONT>";	
	$lines_tw=$row['M_League_tw']."&nbsp;-&nbsp;".$w_mb_team_tw."<br>".$row['M_Item_tw']."&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>".$s_m_rate."</b></FONT>";
	$lines_en=$row['M_League_en']."&nbsp;-&nbsp;".$w_mb_team_en."<br>".$row['M_Item_en']."&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>".$s_m_rate."</b></FONT>";
	//echo $lines_tw;exit;
$ip_addr = get_ip();

$psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
$result = mysqli_query($dbLink,$psql);
$prow = mysqli_fetch_assoc($result);
$a_point=$prow['A_Point']+0;
$b_point=$prow['B_Point']+0;
$c_point=$prow['C_Point']+0;
$d_point=$prow['D_Point']+0;

$showVoucher=show_voucher($wtype);

$begin = mysqli_query($dbMasterLink,"start transaction");
$lockResult = mysqli_query($dbMasterLink,"select Money from ".DBPREFIX.MEMBERTABLE." where ID = ".$memid." for update");
if($begin && $lockResult){
	$checkRow = mysqli_fetch_assoc($lockResult);
		$HMoney=$Money=$checkRow['Money'];
		$havemoney=$HMoney-$gold;
		if($havemoney < 0 || $gold<=0 || $HMoney<=0){
			mysqli_query($dbMasterLink,"ROLLBACK");
			echo attention("$User_insufficient_balance",$uid,$langx);
			exit();
		}	
	$sql = "INSERT INTO ".DBPREFIX."web_report_data	(QQ83068506,Glost,playSource,danger,MID,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$inball1',$Money,2,'0','$gid',$memid,$test_flag,'$active','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$s_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$wtype','FS','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
	$insertBet=mysqli_query($dbMasterLink,$sql);
	if($insertBet){
        $lastId=mysqli_insert_id($dbMasterLink);
		$moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,2,$lastId,"FT投注$w_gtype"));
		if($moneyLogRes){
			$sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=".$havemoney." , Online=1 , OnlineTime=now() where ID=".$memid;
			$updateMoney=mysqli_query($dbMasterLink,$sql1);	
			if($updateMoney){
				mysqli_query($dbMasterLink,"COMMIT");
			}else{
				mysqli_query($dbMasterLink,"ROLLBACK");
				die("操作失败3");		
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");
			die("操作失败2");		
		}
	}else{
		mysqli_query($dbMasterLink,"ROLLBACK");
		die("操作失败1");		
	}
}else{
	mysqli_query($dbMasterLink,"ROLLBACK");
	die("操作失败0");
}

// echo attention("$Order_OK");exit;
?>
<html>
<head>
<meta http-equiv='Content-Type' content="text/html; charset=utf-8">

<html>
<head>
<title>ft_nfs_order_finish</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<link rel="stylesheet" href="/style/member/mem_order<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
<!--<script>window.setTimeout("self.location='../select.php?uid=<?php/*=$uid*/?>'", 45000);</script>-->
</head>
<body id="OFIN" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
  <div class="ord">
    <span><h1><?php echo $s_sitem?><?php echo $Order_betting_order?></h1></span>
    <div id="info">
      <div class="fin_title"><p class="fin_acc">成功提交注单！</p><p class="p-underline"><?php echo $Order_Bet_success?>&nbsp;<?php echo $showVoucher;?></p><p class="error">危险球 - 待确认</p></div>
      <p class="team"><?php echo $s_sleague?>&nbsp;-&nbsp;<?php echo $row["M_Start"]?><br><?php echo $row[$mb_team];?>-&nbsp;<?php echo $m_item;?>&nbsp;@&nbsp;<FONT COLOR="#CC0000"><B><?php echo $s_m_rate?></B></font></p>
      <p class="deal-money"><?php echo $Order_Bet_Amount?><?php echo $gold?></p>
    </div>
    <p class="foot">
      <input type="button" name="FINISH" value="<?php echo $Order_Quit?>" onClick="parent.close_bet();" class="no">
      <input type="button" name="PRINT" value="<?php echo $Order_Print?>" onClick="window.print()" class="yes">
    </p>
  </div>
</body>


<?php

// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
    // 需要参数

    $data = array(
        'caption' => $caption, //标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        's_sleague' => $s_sleague,  //联盟处理:联盟样式和显示的样式
        'btype' => $btype,
        'M_Date' =>  $row["M_Start"], //日期
        'Sign' => $Sign,
        's_mb_team' => $row[$mb_team].'-'.$m_item,   // 主队
        's_tg_team' => $s_tg_team,  // 客队
        's_m_place' => $s_m_place,  // 选择所属队
        'w_m_rate' => $s_m_rate,  // 赔率
        'Order_Bet_Amount' => $Order_Bet_Amount,  // 交易金额：
        'gold' => $gold, //20
        'Order_Quit' => $Order_Quit, //关闭
        'Order_Print' => $Order_Print, //列印
        'userid' => $memid,
        'playSource' => 2,  //'投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓',
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);
}
?>
</html>
<?php
}
?>