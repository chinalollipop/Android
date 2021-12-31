<?php
/**
* 数据刷新今日赛事
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

//ini_set("display_errors", "On");
define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/configUserCli.php";
require(CONFIG_DIR."/app/agents/include/define_function_list.inc.php");


$langx="zh-cn";
$uid='';
$showtype='';
$Mtype='';
$page_no=0;
$nums_bill_ids= 0;
$per_num_each_thread= 0;
$bill_ids=array();
$redisObj = new Ciredis();

	$bill_ids = array('TODAY_FT_M_ROU_EO','TODAY_FT_PD','TODAY_FT_HPD','TODAY_FT_T','TODAY_FT_F','TODAY_FT_P3');
	$nums_bill_ids = count($bill_ids);
	if($nums_bill_ids==0){
		echo "赛事没有注单！！！";
		exit();	
	}
	
	$per_num_each_thread = 1;//每一个进程里面有几个
	$worker_num = $nums_bill_ids;//一共有几个进程
	for($i=0;$i<$worker_num ; $i++){
		$process = new swoole_process("getTodayDataByMethod", true);
		$pid = $process->start();
		$process->write($i);
	}


function getTodayDataByMethod(swoole_process $worker) {
	global $per_num_each_thread,$bill_ids,$database;
	$redisObj = new Ciredis();
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

    //数据中心
    $connDataCenterMysql = mysqli_connect($database['dataDefault']['host'], $database['dataDefault']['user'], $database['dataDefault']['password'], $database['dataDefault']['dbname'], $database['dataDefault']['port']);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    mysqli_query($connDataCenterMysql, "SET NAMES 'utf8'");

	//这里一定要重新连接，每个swoole里面的链接都需要重新的连下
	$connMysql = mysqli_connect($database['gameDefault']['host'], $database['gameDefault']['user'], $database['gameDefault']['password'], $database['gameDefault']['dbname'], $database['gameDefault']['port']);
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	mysqli_query($connMysql, "SET NAMES 'utf8'");
	
	$date=date('m-d');
	$m_date=date('Y-m-d');
	$matches=array();
	switch ($BillArray[0]){
		case 'TODAY_FT_M_ROU_EO':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and MB_Team!='' and `Open`=1 order by M_Start,M_League,MB_Team,MB_MID";
			  	$mysql="select MID,M_Time,M_Type,MB_MID,TG_MID,more,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FT' and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and `S_Show`=1 and MB_Team!='' and `Open`=1 order by M_Start,M_League,MB_Team,MB_MID";
			break;
		case 'TODAY_FT_PD':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and MB_Team!='' and `Open`=1 order by M_Start,MID";
				$mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FT' and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and PD_Show=1 and `MB2TG1`!=0 and MB_Team!='' and `Open`=1 order by M_Start,MID";
			break;
		case 'TODAY_FT_HPD':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date`='$m_date' and MB_Team!='' and `Open`=1 order by M_Start,MID";
				$mysql = "select MID,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB4TG0H,MB4TG1H,MB4TG2H,MB4TG3H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,MB4TG4H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,MB0TG4H,MB1TG4H,MB2TG4H,MB3TG4H,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FT' and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date`='$m_date' and `HPD_Show`=1 and `MB2TG1H`!=0 and MB_Team!='' and `Open`=1 order by M_Start,MID";
			break;
		case 'TODAY_FT_T':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and `Open`=1 ";
				$mysql = "select MID,M_Time,MB_Team,S_Double_Rate,S_Single_Rate,TG_Team,M_League,MB_Team,TG_Team,M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_0_1,S_2_3,S_4_6,S_7UP,MB_MID,TG_MID,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FT' and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and `T_Show`=1 and `Open`=1 and (S_0_1<>0 or S_2_3<>0 or S_4_6<>0 or S_7UP <>0) order by M_Start,MB_MID";
			break;
		case 'TODAY_FT_F':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and `Open`=1 order by M_Start,MB_MID";
				$mysql = "select MID,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG,MB_MID,TG_MID,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FT' and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` ='$m_date' and `F_Show`=1 and `Open`=1 order by M_Start,MB_MID";
			break;
		case 'TODAY_FT_P3':
                $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type` IN('FU','FT') and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' and `Open`=1 order by M_Start,MB_Team,MB_MID";
			    $mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB_MID,TG_MID,ShowTypeP,MB_P_LetB_Rate,MB_P_LetB_Rate_H, TG_P_LetB_Rate_H, MB_P_Dime_Rate_H, TG_P_Dime_Rate_H,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,ShowTypeHP,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,P3_Show from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type` IN('FU','FT') and `M_Start` > '".date('Y-m-d H:i:s')."' and `P3_Show`=1 and `Open`=1 order by M_Start,MB_Team,MB_MID";
			break;
	}

    $resultL = mysqli_query($connMysql, $mysqlL);
    while($rowL=mysqli_fetch_assoc($resultL)){
        $midArr[]=$rowL['MID'];
    }

	$result = mysqli_query($connDataCenterMysql, $mysql);
	while($row=mysqli_fetch_assoc($result)){
        if(in_array($row['MID'],$midArr)) $matches[]=$row;
	}
	$setResult=$redisObj->setOne($BillArray[0],json_encode($matches));
	if(CREAT_STATIC_PAGES){
        createHtml($BillArray[0],$matches);
    }
	
	ob_start(); //打开缓冲区
	echo '<pre>';
    echo $BillArray[0]."\n\r";
	echo count($matches)."\n\r";
	echo $mysqlL."\n\r";
    echo $mysql."\n\r";
	echo date('Y-m-d H:i:s',time());
	echo "\n\r";
	$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
	ob_clean(); //关闭缓冲区
	log_note($info."\r\n");

	mysqli_close($connMysql);
	unset($BillArray);
	unset($mysql);
	exit();	
}

function log_note($info) {	
	//ob_start(); //打开缓冲区  
	//var_dump($database);
	//$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
	//ob_clean(); //关闭缓冲区
	//log_note($info."\r\n");
	$dir = dirname(__FILE__);
	$file = $dir."/today".date("ymd").".txt";
	$handle = fopen($file, 'a+');
	fwrite($handle, $info);
	fclose($handle);
}

?>
<?php
function createHtml($rtype,$resulTotal){
	global $uid,$langx,$showtype,$Mtype,$page_no,$g_date;
	
	$redisObj = new Ciredis();
$K=0;
$num=60;
$m_date=date('Y-m-d');
$date=date('m-d');
$openArray = array('A','B','C','D');
$o='单';
$e='双';

foreach($openArray as $key=>$open){
ob_start(); //打开缓冲区  
$newDataArray = array();
switch ($rtype){
	case "TODAY_FT_M_ROU_EO":$oldRtype='r';break;
	case "TODAY_FT_PD":		 $oldRtype='pd';break;
	case "TODAY_FT_HPD":	 $oldRtype='hpd';break;
	case "TODAY_FT_T":		 $oldRtype='t';break;
	case "TODAY_FT_F":		 $oldRtype='f';break;
	case "TODAY_FT_P3":		 $oldRtype='p3';break;
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
parent.rtype='<?php echo $oldRtype?>';
parent.retime = 60 ; // 今日赛事刷新倒计时
parent.gamount=0;
parent.t_page=0;

<?php
switch ($rtype){
case "TODAY_FT_M_ROU_EO":  // 全部
	$page_size=60;
	$offset=$page_no*60;
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);
	
	$gameVideoNow = $redisObj->getSimpleOne('gameVideoNow');
	$gameVideoNowArr = json_decode($gameVideoNow,true);
	$gameVideoFuture = $redisObj->getSimpleOne('gameVideoFuture');
	$gameVideoFutureArr = json_decode($gameVideoFuture,true);
	
	echo "parent.str_renew = '秒自动更新';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='多种玩法';\n";
	echo "parent.t_page=$page_count;\n";	
	echo "parent.gamount=$cou;\n";
	foreach($resultArr as $key=>$row){
		$MB_Win_Rate=change_rate($open,$row["MB_Win_Rate"]);
		$TG_Win_Rate=change_rate($open,$row["TG_Win_Rate"]);
		$M_Flat_Rate=change_rate($open,$row["M_Flat_Rate"]);

        // 全场让球单独处理
        $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
        $MB_LetB_Rate=$ra_rate[0]; // 主队
        $TG_LetB_Rate=$ra_rate[1]; // 客队
        $MB_LetB_Rate=change_rate($open,$MB_LetB_Rate);
        $TG_LetB_Rate=change_rate($open,$TG_LetB_Rate);

        // 全场大小处理
        $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate"],$row["MB_Dime_Rate"],100); // 默认都是香港盘
        $TG_Dime_Rate=$ra_rate[0];
        $MB_Dime_Rate=$ra_rate[1];
        $TG_Dime_Rate=change_rate($open,$TG_Dime_Rate);
        $MB_Dime_Rate=change_rate($open,$MB_Dime_Rate);

		$S_Single_Rate=change_rate($open,$row['S_Single_Rate']);
		$S_Double_Rate=change_rate($open,$row['S_Double_Rate']);
		
		$MB_Win_Rate_H=change_rate($open,$row["MB_Win_Rate_H"]); // 全部独赢主队
		$TG_Win_Rate_H=change_rate($open,$row["TG_Win_Rate_H"]); // 全部独赢客队
		$M_Flat_Rate_H=change_rate($open,$row["M_Flat_Rate_H"]); // 全部独赢和局

        // 半场让球单独处理
        $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100); // 默认都是香港盘
        $MB_LetB_Rate_H=$h_ra_rate[0]; // 主队
        $TG_LetB_Rate_H=$h_ra_rate[1]; // 客队
        $MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);  // 半场让球主队
        $TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H); // 半场让球客队

        // 半场大小处理
        $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["MB_Dime_Rate_H"],100); // 默认都是香港盘
        $TG_Dime_Rate_H=$h_ra_rate[0];
        $MB_Dime_Rate_H=$h_ra_rate[1];
        $TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);  // 半场大小客队
        $MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H); // 半场大小主队
		
		if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=4;
		}else if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=3;
		}else{
		    $show=0;
		}
		if ($row['M_Type']==1){
			$Running="<br><font color=red>滾球</font>";
		}else{	
			$Running="";
		}
		$allMethods=$row[more]<5 ? 0:$row[more];
		if($row[ShowTypeR]=="H"){
			$ratio_mb_str=$row[M_LetB];
			$ratio_tg_str='';
			$hratio_mb_str=$row[M_LetB_H];
			$hratio_tg_str='';
		}elseif($row[ShowTypeR]=="C"){
			$ratio_mb_str='';
			$ratio_tg_str=$row[M_LetB];
			$hratio_mb_str='';
			$hratio_tg_str=$row[M_LetB_H];
		}
		$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
		$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            continue;
        }
        if ($pos_zh_tw === false){}
        else{
            continue;
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time].$Running;
		$newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
		$newDataArray[$row[MID]]['team_h']=$row[MB_Team];
		$newDataArray[$row[MID]]['team_c']=$row[TG_Team];
		$newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
		$newDataArray[$row[MID]]['ratio' ]=$row[M_LetB];
		$newDataArray[$row[MID]]['ratio_mb_str']=$ratio_mb_str;
		$newDataArray[$row[MID]]['ratio_tg_str']=$ratio_tg_str;
		$newDataArray[$row[MID]]['ior_RH']=$MB_LetB_Rate;
		$newDataArray[$row[MID]]['ior_RC']=$TG_LetB_Rate;
		$newDataArray[$row[MID]]['bet_RH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[MB_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
		$newDataArray[$row[MID]]['bet_RC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
		$newDataArray[$row[MID]]['ratio_o']=$row[MB_Dime];
		$newDataArray[$row[MID]]['ratio_u']=$row[TG_Dime];
		$newDataArray[$row[MID]]['ratio_o_str']="大".str_replace('O','',$row[MB_Dime]);
		$newDataArray[$row[MID]]['ratio_u_str']="小".str_replace('U','',$row[TG_Dime]);
		$newDataArray[$row[MID]]['ior_OUH']=$TG_Dime_Rate;
		$newDataArray[$row[MID]]['ior_OUC']=$MB_Dime_Rate;
		$newDataArray[$row[MID]]['bet_OUH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[MB_MID]}&langx={$langx}";
		$newDataArray[$row[MID]]['bet_OUC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[TG_MID]}&langx={$langx}";
		$newDataArray[$row[MID]]['ior_MH']=$MB_Win_Rate;
		$newDataArray[$row[MID]]['ior_MC']=$TG_Win_Rate;
		$newDataArray[$row[MID]]['ior_MN']=$M_Flat_Rate;
		$newDataArray[$row[MID]]['bet_MH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[MB_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
		$newDataArray[$row[MID]]['bet_MC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
		$newDataArray[$row[MID]]['bet_MN']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=N&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
		$newDataArray[$row[MID]]['str_odd']=$o;
		$newDataArray[$row[MID]]['str_even']=$e;
		$newDataArray[$row[MID]]['ior_EOO']=$S_Single_Rate;
		$newDataArray[$row[MID]]['ior_EOE']=$S_Double_Rate;
		$newDataArray[$row[MID]]['bet_EOO']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&rtype=ODD&langx={$langx}";
		$newDataArray[$row[MID]]['bet_EOE']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&rtype=EVEN&langx={$langx}";
		$newDataArray[$row[MID]]['hgid']='$row[MID]';
		$newDataArray[$row[MID]]['hstrong']=$row[ShowTypeHR];
		$newDataArray[$row[MID]]['hratio']=$row[M_LetB_H];
		$newDataArray[$row[MID]]['hratio_mb_str']=$hratio_mb_str;
		$newDataArray[$row[MID]]['hratio_tg_str']=$hratio_tg_str;
		$newDataArray[$row[MID]]['ior_HRH']=$MB_LetB_Rate_H;
		$newDataArray[$row[MID]]['ior_HRC']=$TG_LetB_Rate_H;
		$newDataArray[$row[MID]]['hratio_o']=$row[MB_Dime_H];
		$newDataArray[$row[MID]]['hratio_u']=$row[TG_Dime_H];
		$newDataArray[$row[MID]]['hratio_o_str']="大".str_replace('O','',$row[MB_Dime_H]);
		$newDataArray[$row[MID]]['hratio_u_str']="小".str_replace('U','',$row[TG_Dime_H]);
		$newDataArray[$row[MID]]['ior_HOUH']=$TG_Dime_Rate_H;
		$newDataArray[$row[MID]]['ior_HOUC']=$MB_Dime_Rate_H;
		$newDataArray[$row[MID]]['ior_HMH']=$MB_Win_Rate_H;
		$newDataArray[$row[MID]]['ior_HMC']=$TG_Win_Rate_H;
		$newDataArray[$row[MID]]['ior_HMN']=$M_Flat_Rate_H;
		$newDataArray[$row[MID]]['more']=$show;
		$newDataArray[$row[MID]]['all']=$allMethods;
		$newDataArray[$row[MID]]['eventid'] =$row[Eventid];
		$newDataArray[$row[MID]]['hot']=$row[Hot];
		$newDataArray[$row[MID]]['play']=$row[Play];
		if(in_array($row['Eventid'],$gameVideoNowArr)){
			$newDataArray[$row[MID]]['event']='on';	
		}elseif(in_array($row['Eventid'],$gameVideoFutureArr)){
			$newDataArray[$row[MID]]['event']='out';	
		}else{
			$newDataArray[$row[MID]]['event']='no';	
		}
		//var_dump($newDataArray);
		$K=$K+1;	
	}
	$listTitle="今日足球";
	$leagueNameCur='';
	break;
case "TODAY_FT_PD":  //波胆
	$page_size=60;
	$offset=$page_no*60;
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	foreach($resultArr as $key=>$row){
		$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
		$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            continue;
        }
        if ($pos_zh_tw === false){}
        else{
            continue;
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
		$newDataArray[$row[MID]]['team_h']=$row[MB_Team];
		$newDataArray[$row[MID]]['team_c']=$row[TG_Team];
		$newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
		$newDataArray[$row[MID]]['ior_H1C0']=change_rate($open,$row['MB1TG0']);
		$newDataArray[$row[MID]]['ior_H2C0']=change_rate($open,$row['MB2TG0']);
		$newDataArray[$row[MID]]['ior_H2C1']=change_rate($open,$row['MB2TG1']);
		$newDataArray[$row[MID]]['ior_H3C0']=change_rate($open,$row['MB3TG0']);
		$newDataArray[$row[MID]]['ior_H3C1']=change_rate($open,$row['MB3TG1']);
		$newDataArray[$row[MID]]['ior_H3C2']=change_rate($open,$row['MB3TG2']);
		$newDataArray[$row[MID]]['ior_H4C0']=change_rate($open,$row['MB4TG0']);
		$newDataArray[$row[MID]]['ior_H4C1']=change_rate($open,$row['MB4TG1']);
		$newDataArray[$row[MID]]['ior_H4C2']=change_rate($open,$row['MB4TG2']);
		$newDataArray[$row[MID]]['ior_H4C3']=change_rate($open,$row['MB4TG3']);
		$newDataArray[$row[MID]]['ior_H0C0']=change_rate($open,$row['MB0TG0']);
		$newDataArray[$row[MID]]['ior_H1C1']=change_rate($open,$row['MB1TG1']);
		$newDataArray[$row[MID]]['ior_H2C2']=change_rate($open,$row['MB2TG2']);
		$newDataArray[$row[MID]]['ior_H3C3']=change_rate($open,$row['MB3TG3']);
		$newDataArray[$row[MID]]['ior_H4C4']=change_rate($open,$row['MB4TG4']);
		$newDataArray[$row[MID]]['ior_OVH']= change_rate($open,$row['UP5']);
		$newDataArray[$row[MID]]['ior_H0C1']=change_rate($open,$row['MB0TG1']);
		$newDataArray[$row[MID]]['ior_H0C2']=change_rate($open,$row['MB0TG2']);
		$newDataArray[$row[MID]]['ior_H1C2']=change_rate($open,$row['MB1TG2']);
		$newDataArray[$row[MID]]['ior_H0C3']=change_rate($open,$row['MB0TG3']);
		$newDataArray[$row[MID]]['ior_H1C3']=change_rate($open,$row['MB1TG3']);
		$newDataArray[$row[MID]]['ior_H2C3']=change_rate($open,$row['MB2TG3']);
		$newDataArray[$row[MID]]['ior_H0C4']=change_rate($open,$row['MB0TG4']);
		$newDataArray[$row[MID]]['ior_H1C4']=change_rate($open,$row['MB1TG4']);
		$newDataArray[$row[MID]]['ior_H2C4']=change_rate($open,$row['MB2TG4']);
		$newDataArray[$row[MID]]['ior_H3C4']=change_rate($open,$row['MB3TG4']);
		$newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		
		$K=$K+1;	
	}
	$listTitle="今日足球：波胆";
	$leagueNameCur='';
	break;
case "TODAY_FT_HPD":
	$page_size=60;
	$offset=$page_no*60;
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	foreach($resultArr as $key=>$row){
		//echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[TG_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[ShowTypeR]','$row[MB1TG0H]','$row[MB2TG0H]','$row[MB2TG1H]','$row[MB3TG0H]','$row[MB3TG1H]','$row[MB3TG2H]','$row[MB4TG0H]','$row[MB4TG1H]','$row[MB4TG2H]','$row[MB4TG3H]','$row[MB0TG0H]','$row[MB1TG1H]','$row[MB2TG2H]','$row[MB3TG3H]','$row[MB4TG4H]','$row[UP5H]','$row[MB0TG1H]','$row[MB0TG2H]','$row[MB1TG2H]','$row[MB0TG3H]','$row[MB1TG3H]','$row[MB2TG3H]','$row[MB0TG4H]','$row[MB1TG4H]','$row[MB2TG4H]','$row[MB3TG4H]');\n";
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            continue;
        }
        if ($pos_zh_tw === false){}
        else{
            continue;
        }
        $newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
		$newDataArray[$row[MID]]['team_h']=$row[MB_Team]."<font color=gray> - [$Order_1st_Half]</font>";
		$newDataArray[$row[MID]]['team_c']=$row[TG_Team]."<font color=gray> - [$Order_1st_Half]</font>";
		$newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
		$newDataArray[$row[MID]]['ior_H1C0']=change_rate($open,$row[MB1TG0H]);
		$newDataArray[$row[MID]]['ior_H2C0']=change_rate($open,$row[MB2TG0H]);
		$newDataArray[$row[MID]]['ior_H2C1']=change_rate($open,$row[MB2TG1H]);
		$newDataArray[$row[MID]]['ior_H3C0']=change_rate($open,$row[MB3TG0H]);
		$newDataArray[$row[MID]]['ior_H3C1']=change_rate($open,$row[MB3TG1H]);
		$newDataArray[$row[MID]]['ior_H3C2']=change_rate($open,$row[MB3TG2H]);
		$newDataArray[$row[MID]]['ior_OVH' ]=change_rate($open,$row[UP5H]);
		$newDataArray[$row[MID]]['ior_H0C1']=change_rate($open,$row[MB0TG1H]);
		$newDataArray[$row[MID]]['ior_H0C2']=change_rate($open,$row[MB0TG2H]);
		$newDataArray[$row[MID]]['ior_H1C2']=change_rate($open,$row[MB1TG2H]);
		$newDataArray[$row[MID]]['ior_H0C3']=change_rate($open,$row[MB0TG3H]);
		$newDataArray[$row[MID]]['ior_H1C3']=change_rate($open,$row[MB1TG3H]);
		$newDataArray[$row[MID]]['ior_H2C3']=change_rate($open,$row[MB2TG3H]);
		$newDataArray[$row[MID]]['ior_H0C0']=change_rate($open,$row[MB0TG0H]);
		$newDataArray[$row[MID]]['ior_H1C1']=change_rate($open,$row[MB1TG1H]);
		$newDataArray[$row[MID]]['ior_H2C2']=change_rate($open,$row[MB2TG2H]);
		$newDataArray[$row[MID]]['ior_H3C3']=change_rate($open,$row[MB3TG3H]);
		$newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		$K=$K+1;	
	}
	$reBallCountCur = $cou;
    $listTitle="今日足球：波胆";
    $leagueNameCur='';
	break;
case "TODAY_FT_T"://总入球
	$page_size=60;
	$offset=$page_no*60;
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER','ior_MH','ior_MC','ior_MN');";
	foreach($resultArr as $key=>$row){
		$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
		$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            continue;
        }
        if ($pos_zh_tw === false){}
        else{
            continue;
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
		$newDataArray[$row[MID]]['team_h']=$row[MB_Team];
		$newDataArray[$row[MID]]['team_c']=$row[TG_Team];
		$newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
		$newDataArray[$row[MID]]['ior_T01']=change_rate($open,$row['S_0_1']);
		$newDataArray[$row[MID]]['ior_T23']=change_rate($open,$row['S_2_3']);
		$newDataArray[$row[MID]]['ior_T46']=change_rate($open,$row['S_4_6']);
		$newDataArray[$row[MID]]['ior_OVER']=change_rate($open,$row['S_7UP']);
		$newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		$K=$K+1;	
	}
	$listTitle="今日足球:全场-总入球数";
	$leagueNameCur='';
	break;
case "TODAY_FT_F":  //半场/全场
	$page_size=60;
	$offset=$page_no*60;
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');";
	foreach($resultArr as $key=>$row){
		$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
		$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            continue;
        }
        if ($pos_zh_tw === false){}
        else{
            continue;
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
		$newDataArray[$row[MID]]['team_h']=$row[MB_Team];
		$newDataArray[$row[MID]]['team_c']=$row[TG_Team];
		$newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
		$newDataArray[$row[MID]]['ior_FHH']=change_rate($open,$row[MBMB]);
		$newDataArray[$row[MID]]['ior_FHN']=change_rate($open,$row[MBFT]);
		$newDataArray[$row[MID]]['ior_FHC']=change_rate($open,$row[MBTG]);
		$newDataArray[$row[MID]]['ior_FNH']=change_rate($open,$row[FTMB]);
		$newDataArray[$row[MID]]['ior_FNN']=change_rate($open,$row[FTFT]);
		$newDataArray[$row[MID]]['ior_FNC']=change_rate($open,$row[FTTG]);
		$newDataArray[$row[MID]]['ior_FCH']=change_rate($open,$row[TGMB]);
		$newDataArray[$row[MID]]['ior_FCN']=change_rate($open,$row[TGFT]);
		$newDataArray[$row[MID]]['ior_FCC']=change_rate($open,$row[TGTG]);
		$newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		$K=$K+1;	
	}
	$listTitle="今日足球:半场 /全场";
	$leagueNameCur='';
	break;
	case "TODAY_FT_P3": // 综合过关
                $cou=count($resulTotal);
                echo "parent.retime=0;\n";
                echo "parent.game_more=1;\n";
                echo "parent.str_more='多种玩法';\n";
                echo "parent.gamount=$cou;\n";
                if ($cou<=0){
                    $page_size=0;
                }else{
                    $page_count=intval($cou/$page_size);
                }
                echo "parent.t_page=$page_count;";
                // 综合过关  RATIO_HMH ior_HPMH 独赢主队 ， RATIO_HMC ior_HPMC 独赢客队 ，RATIO_HMN ior_HPMN 独赢和局
                
                foreach($resulTotal as $key=>$row){
                    $mb_team=trim($row['MB_Team']);
                    $m_date=strtotime($row['M_Date']);
                    $dates=date("m-d",$m_date);
                    if (strlen($row['M_Time'])==5){
                        $pdate=$dates.'<br>0'.$row[M_Time];
                    }else{
                        $pdate=$dates.'<br>'.$row[M_Time];
                    }
                    if ($row['F_PD_Show']==1 and $row['F_T_Show']==1 and $row['F_F_Show']==1){
                        $show=3;
                    }else if ($row['F_HPD_Show']==1 and $row['F_PD_Show']==1 and $row['F_T_Show']==1 and $row['F_F_Show']==1){
                        $show=4;
                    }else{
                        $show=0;
                    }
	                if($row[ShowTypeP]=="H"){
						$ratio_mb_str=$row[M_P_LetB];
						$ratio_tg_str='';
						$hratio_mb_str=$row[M_LetB_H];
						$hratio_tg_str='';
					}elseif($row[ShowTypeP]=="C"){
						$ratio_mb_str='';
						$ratio_tg_str=$row[M_P_LetB];
						$hratio_mb_str='';
						$hratio_tg_str=$row[M_LetB_H];
					}
                    $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
					$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
                    $pos = strpos($row['M_League'],'电竞足球');
                    $pos_zh_tw = strpos($row['M_League'],'電競足球');
                    if ($pos === false){}
                    else{
                        continue;
                    }
                    if ($pos_zh_tw === false){}
                    else{
                        continue;
                    }
                    $newDataArray[$row[MID]]['gid']=$row[MID];
					$newDataArray[$row[MID]]['dategh']=date('m-d').$row[MB_MID];
					$newDataArray[$row[MID]]['datetime']=$pdate;
					$newDataArray[$row[MID]]['league']=$row[M_League];
					$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
					$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
					$newDataArray[$row[MID]]['team_h']=$row[MB_Team];
					$newDataArray[$row[MID]]['team_c']=$row[TG_Team];
					$newDataArray[$row[MID]]['strong']=$row[ShowTypeP];
					$newDataArray[$row[MID]]['ratio']=$row[M_P_LetB];
					$newDataArray[$row[MID]]['ratio_mb_str']=$ratio_mb_str;
					$newDataArray[$row[MID]]['ratio_tg_str']=$ratio_tg_str;
					$newDataArray[$row[MID]]['ior_PRH']=change_rate($open,$row['MB_P_LetB_Rate']);
					$newDataArray[$row[MID]]['ior_PRC']=change_rate($open,$row['TG_P_LetB_Rate']);
					$newDataArray[$row[MID]]['ratio_o']=$row[MB_P_Dime];
					$newDataArray[$row[MID]]['ratio_u']=$row[TG_P_Dime];
					$newDataArray[$row[MID]]['ratio_o_str']="大".str_replace('O','',$row[MB_P_Dime]);
					$newDataArray[$row[MID]]['ratio_u_str']="小".str_replace('U','',$row[TG_P_Dime]);
					$newDataArray[$row[MID]]['ior_POUC']=change_rate($open,$row['MB_P_Dime_Rate']);
					$newDataArray[$row[MID]]['ior_POUH']=change_rate($open,$row['TG_P_Dime_Rate']);
					$newDataArray[$row[MID]]['ior_PO']=change_rate($open,$row['S_P_Single_Rate']);
					$newDataArray[$row[MID]]['ior_PE']=change_rate($open,$row['S_P_Double_Rate']);
					$newDataArray[$row[MID]]['ior_MH']=change_rate($open,$row["MB_P_Win_Rate"]);
					$newDataArray[$row[MID]]['ior_MC']=change_rate($open,$row["TG_P_Win_Rate"]);
					$newDataArray[$row[MID]]['ior_MN']=change_rate($open,$row["M_P_Flat_Rate"]);
					$newDataArray[$row[MID]]['hstrong']=$row[ShowTypeP];
					$newDataArray[$row[MID]]['hratio']=$row[M_LetB_H];
					$newDataArray[$row[MID]]['hratio_mb_str']=$hratio_mb_str;
					$newDataArray[$row[MID]]['hratio_tg_str']=$hratio_tg_str;
					$newDataArray[$row[MID]]['ior_HPRH']=change_rate($open,$row["MB_P_LetB_Rate_H"]);  // 半场让球主队;
					$newDataArray[$row[MID]]['ior_HPRC']=change_rate($open,$row["TG_P_LetB_Rate_H"]); // 半场让球客队;
					$newDataArray[$row[MID]]['hratio_o']=$row[MB_Dime_H];
					$newDataArray[$row[MID]]['hratio_u']=$row[TG_Dime_H];
					$newDataArray[$row[MID]]['hratio_o_str']="大".str_replace('O','',$row[MB_Dime_H]);
					$newDataArray[$row[MID]]['hratio_u_str']="小".str_replace('U','',$row[TG_Dime_H]);
					$newDataArray[$row[MID]]['ior_HPOUH']=change_rate($open,$row['TG_P_Dime_Rate_H']); // 半场客队小;
					$newDataArray[$row[MID]]['ior_HPOUC']=change_rate($open,$row['MB_P_Dime_Rate_H']); // 半场主队大
					$newDataArray[$row[MID]]['ior_HPMH']=change_rate($open,$row["MB_Win_Rate_H"]); // RATIO_HMH ior_HPMH 独赢主队
					$newDataArray[$row[MID]]['ior_HPMC']=change_rate($open,$row["TG_Win_Rate_H"]); // RATIO_HMC ior_HPMC 独赢客队
					$newDataArray[$row[MID]]['ior_HPMN']=change_rate($open,$row["M_Flat_Rate_H"]);  // RATIO_HMN ior_HPMN 独赢和局
					$newDataArray[$row[MID]]['more']=$show;
					$newDataArray[$row[MID]]['gidm']=$row[MID];
					$newDataArray[$row[MID]]['par_minlimit']=3;
					$newDataArray[$row[MID]]['par_maxlimit']=10;	
                    $K=$K+1;
                }
                $listTitle="今日足球 : 综合过关";
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
<?php 
if($rtype=='TODAY_FT_P3'){
		$date1=date('Y-m-d',time()+24*60*60);
		$date2=date('Y-m-d',time()+2*24*60*60);
		$date3=date('Y-m-d',time()+3*24*60*60);
		$date4=date('Y-m-d',time()+4*24*60*60);
		$date5=date('Y-m-d',time()+5*24*60*60);
		$date6=date('Y-m-d',time()+6*24*60*60);
		$date7=date('Y-m-d',time()+7*24*60*60);
		$date8=date('Y-m-d',time()+8*24*60*60);
		$date9=date('Y-m-d',time()+9*24*60*60);	
	
	echo "<div class='div_date_title'><span id='show_date_opt'>";
	$todayDate=date('Y-m-d',time());
	echo "<span class='choose_select' value='$todayDate' onclick='chg_gdate(this)' class='".($g_date==$todayDate?'choose_select':'')."'>今日</span>";
	for($datei=1;$datei<10;$datei++){
		$dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
		$dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
		echo "<span value='$dateNowValue' onclick='chg_gdate(this)' class='".($g_date==$dateNowValue?'choose_select':'')."'>$dateNowStr</span>";
	}
	echo "<span value='ALL'        onclick='chg_gdate(this)' class='".($g_date=='ALL'?'choose_select':'')."'>全部</span>";
	echo "</span></div>";
}
?>
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
                                  if($rtype=='TODAY_FT_PD' || $rtype=='TODAY_FT_HPD'){ // 波胆才有
	                                  	if($rtype=='TODAY_FT_HPD'){
	                                  		$select = 'selected' ;
	                                  	}else{
	                                  		$select = '' ;
	                                  	}
										echo ' <select id="selwtype" onChange="chg_wtype(selwtype.value);">
                                                <option value="pd" >全场</option>
                                                <option value="hpd" '.$select.' >上半场</option>
                                             </select>' ;
                                  }

                                  if($rtype=='TODAY_FT_PD' || $rtype=='TODAY_FT_HPD' || $rtype=='TODAY_FT_T'|| $rtype=='TODAY_FT_F'){
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
										<span id="pg_txt"></span>
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
											case "TODAY_FT_M_ROU_EO":include "Running/body_m_r_ou_eo.php";break;
											case "TODAY_BK_M_ROU_EO":include "Running/body_bk_m_r_ou.php";break;
											case "TODAY_FT_PD":		 include "Running/body_pd.php";break;
											case "TODAY_FT_HPD":	 include "Running/body_hpd.php";break;
											case "TODAY_FT_T":		 include "Running/body_t.php";break;
											case "TODAY_FT_F":		 include "Running/body_f.php";break;
											case "TODAY_FT_P3":		 include "Running/body_p3.php";break;
											case "TODAY_BK_P3":		 include "Running/body_bk_p3.php";break;
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

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    // 侧边栏游戏选项处理，在当前游戏中不显示当前游戏
    var g_type = sessionStorage.getItem('g_type') ;
    var m_type = sessionStorage.getItem('m_type') ;
    if(m_type == 'rb'){
        document.getElementsByClassName('today_bet_football_move')[0].style.display='none' ;
        document.getElementsByClassName('today_bet_football')[0].style.display='' ;

    }
    // 会员提示信息
    if(top.memberNum=='1'){
        if (top.game_alert.indexOf('Message')==-1){
            layer.alert(top.memberMsg, {
                title: '会员信息',
                icon: false , // 0,1
                skin: 'layer-ext-moon'
            }) ;

            top.game_alert +='Message,' ;
        }
    }

    setBodyScroll();
    function showOpenLive() {
        var url = "../../member/live/live_max.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
        top.tvwin = window.open(url,"win","width=805,height=526,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
    }

</script>
</body>
</html>
<?php 
		$file='';
        $dir = "/www/huangguan/hg3088/member_new/app/member/FT_browse/";
		$filesName=strtolower("Today".$open.$rtype."_DJFT").time().".html";
		$info=ob_get_contents(); 
		ob_end_clean();
		$file = $dir.$filesName;
		$handle = fopen($file, 'w+');
		fwrite($handle, $info);
		fclose($handle);
		unset($newDataArray);
		$redisObj->setOne($rtype.'_DJFT'.'_'.$open.'_URL',$filesName);
	}
}
?>
