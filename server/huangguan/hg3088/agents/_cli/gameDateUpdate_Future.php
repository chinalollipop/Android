<?php
/**
* 数据刷新早盘
*/

if (php_sapi_name() != "cli") {
	exit("只能在_cli模式下面运行！");	
}

if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
define("CONFIG_DIR", dirname(dirname(__FILE__)));
define("COMMON_DIR", dirname(dirname(dirname(__FILE__))));
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
$bill_ids=array();
$redisObj = new Ciredis();

	$bill_ids = array('FUTURE_R','FUTURE_PD','FUTURE_HPD','FUTURE_T','FUTURE_F','FUTURE_BK_ALL','FUTURE_BK_R','FUTURE_FT_P3','FUTURE_BK_P3'); 	
	$nums_bill_ids = count($bill_ids);
	if($nums_bill_ids==0) {
		echo "赛事没有注单！！！";
		exit();	
	}
	
	$per_num_each_thread = 1;
	$worker_num = $nums_bill_ids;
	for($i=0;$i<$worker_num ; $i++){
		$process = new swoole_process("getTodayDataByMethod", true);
		$pid = $process->start();
		$process->write($i);
	}


function getTodayDataByMethod(swoole_process $worker){
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
	
	if(empty($BillArray)) {
		echo "没有需要更新的数据！";
		return true;
		exit();
	}
	
	/*
	ob_start(); //打开缓冲区  
	var_dump($BillArray[0]);
	$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
	ob_clean(); //关闭缓冲区
	log_note($info."\r\n");
	*/

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
		$matches=$midArr=array();
		switch ($BillArray[0]){
	            case 'FUTURE_R': // 全部

                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `Cancel`=0 and `M_Date` >'$m_date' and `MB_Team`!='' and `Open`=1 order by M_Start,MB_Team,MB_MID";
	              	$mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,more,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play,Neutral from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FU' and `M_Date` >'$m_date' and `S_Show`=1 and `MB_Team`!='' and `Open`=1 order by M_Start,MB_Team,MB_MID";

	            	break;
                case 'FUTURE_PD': // 波胆全场
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `Cancel`=0 and  `M_Date` >'$m_date' and `Open`=1 order by m_start,MB_MID";
                    $mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4,ShowTypeR,MB_MID,TG_MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FU' and  `M_Date` >'$m_date' and `PD_Show`=1 and `MB2TG1`!=0 and `Open`=1 order by m_start,MB_MID";
                    break;
                case 'FUTURE_HPD': // 波胆半场
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `Cancel`=0 and `M_Date` >'$m_date' and `Open`=1 order by M_Start,MB_MID";
                    $mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,ShowTypeR,MB_MID,TG_MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FU' and `M_Date` >'$m_date' and `HPD_Show`=1 and `MB2TG1H`!=0 and `Open`=1 order by M_Start,MB_MID";
                    break;
                case 'FUTURE_T': // 总入球
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `Cancel`=0 and `M_Date` >'$m_date' and `Open`=1 order by m_start,MB_MID";
                    $mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,S_0_1,S_2_3,S_4_6,S_7UP,ShowTypeR,MB_MID,TG_MID from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FU' and `M_Date` >'$m_date' and `T_Show`=1 and `Open`=1 and (S_0_1<>0 or S_2_3<>0 or S_4_6<>0 or S_7UP <>0) order by m_start,MB_MID";
                    break;
                case 'FUTURE_F': // 半场/ 全场
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FU' and `Cancel`=0 and `M_Date` >'$m_date' and `Open`=1 order by m_start,MB_MID";
                    $mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG,MB_MID,TG_MID,ShowTypeR from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type`='FU' and `M_Date` >'$m_date' and `F_Show`=1 and `MBMB`>0 and `Open`=1 order by m_start,MB_MID";
                    break;
                case 'FUTURE_BK_ALL': // 全部
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type in ('BK','BU') and `Cancel`=0 and `M_Date` >'$m_date' and MB_Team!='' order by M_Start,MB_Team,MB_MID";
                    $mysql = "select MID,M_Time,M_Date,M_Type,MB_MID,TG_MID,more,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,more,Eventid,Hot,Play from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type in ('BK','BU') and `M_Date` >'$m_date' and S_Show=1 and MB_Team!='' order by M_Start,MB_Team,MB_MID";
                    break;
                case 'FUTURE_BK_R':
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BU' and `Cancel`=0 and `M_Date` >'$m_date' and MB_Team!='' order by M_Start,MB_Team,MB_MID";
                    $mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,Eventid,Hot,Play from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='BU' and `M_Date` >'$m_date' and S_Show=1 and MB_Team!='' order by M_Start,MB_Team,MB_MID";
                    break;
                case 'FUTURE_FT_P3':
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type` IN('FU','FT') and `Cancel`=0 and `M_Date` >'$m_date' and `M_Start` > '".date('Y-m-d H:i:s')."' and `Open`=1 order by M_Start,MB_Team,MB_MID";
                	$mysql = "select MID,M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB_MID,TG_MID,ShowTypeP,MB_P_LetB_Rate,MB_P_LetB_Rate_H, TG_P_LetB_Rate_H, MB_P_Dime_Rate_H, TG_P_Dime_Rate_H,TG_P_LetB_Rate,M_P_LetB,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,S_P_Single_Rate,S_P_Double_Rate,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,ShowTypeHP,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,P3_Show,more from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `Type` IN('FU','FT') and `M_Date` >'$m_date' and `M_Start` > '".date('Y-m-d H:i:s')."' and `P3_Show`=1 and `Open`=1 order by M_Start,MB_Team,MB_MID";
					break;
				case 'FUTURE_BK_P3': // 综合过关
                    $mysqlL = "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type IN ('BU','BK') and `Cancel`=0 and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` >'$m_date' and MB_Team<>'' order by m_start,mid";
                    $mysql = "select MID,M_Date,M_Time,M_Flat_Rate_H,MB_Team,TG_Team,M_League,MB_Team,TG_Team,M_League,MB_Win_Rate,TG_Win_Rate,MB_MID,TG_MID,ShowTypeP,M_P_LetB,MB_P_LetB_Rate,TG_P_LetB_Rate,MB_P_Dime,TG_P_Dime,MB_P_Dime_Rate,TG_P_Dime_Rate,MB_P_Win_Rate,TG_P_Win_Rate,M_P_Flat_Rate,S_P_Single_Rate,S_Single_Rate,S_P_Double_Rate,MB_P_LetB_Rate_H, MB_P_Dime_Rate_H, TG_P_LetB_Rate_H, TG_P_Dime_Rate_H,S_Single_Rate, S_Double_Rate, MB_Dime_H, MB_Dime_S_H, MB_P_Dime_Rate_H, MB_P_Dime_Rate_S_H, TG_Dime_H, TG_Dime_S_H, TG_P_Dime_Rate_H, TG_P_Dime_Rate_S_H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type IN ('BU','BK') and `M_Start` > '".date('Y-m-d H:i:s')."' AND `M_Date` >'$m_date' and P3_Show=1 and MB_Team<>'' order by m_start,mid";
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
		$setResult=$redisObj->setOne($BillArray[0],json_encode($matches,JSON_UNESCAPED_UNICODE));

		if(CREAT_STATIC_PAGES){
            createHtml($BillArray[0],$matches);
        }
		/*
		ob_start(); //打开缓冲区  
		echo '<pre>';
		echo 'SUCCESS:_';
		echo count($matches)."_";
		echo $BillArray[0]."_";
		echo date('Y-m-d H:i:s',time());
		echo '<br/>';
		$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info 
		ob_clean(); //关闭缓冲区
		log_note($info."\r\n");
		*/
        mysqli_close($connDataCenterMysql);
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
	$file = $dir."/future".date("ymd").".txt";
	$handle = fopen($file, 'a+');
	fwrite($handle, $info);
	fclose($handle);
}
?>
<?php
function createHtml($rtype,$future_r_data){
	global $uid,$langx,$showtype,$Mtype,$page_no;

	$redisObj = new Ciredis();	
	$num=60;
	$m_date=date('Y-m-d');
	$K=0;
	$page_size=60; // 每页展示条数
	$page_gamecount=0; // 用于统计当前页共有多少数据

	$date=date('m-d');
	$openArray = array('A','B','C','D');
    $o='单';
    $e='双';

	foreach($openArray as $key=>$open){
		ob_start(); //打开缓冲区  
		$newDataArray = array();
		switch ($rtype){
			case "FUTURE_R":		$oldRtype='r';break;
			case "FUTURE_PD":		$oldRtype='pd';break;
			case "FUTURE_HPD":		$oldRtype='hpd';break;
			case "FUTURE_T":		$oldRtype='t';break;
			case "FUTURE_F":		$oldRtype='f';break;
			case "FUTURE_FT_P3":	$oldRtype='p3';break;
			case "FUTURE_BK_R":		$oldRtype='all';break;
			case "FUTURE_BK_ALL":	$oldRtype='r';break;
			case "FUTURE_BK_P3":	$oldRtype='p3';break;
		}
		?>
		<HEAD>
		<TITLE>足球變數值</TITLE>
			<META http-equiv=Content-Type content="text/html; charset=utf-8">
			<SCRIPT language=JavaScript>
				parent.flash_ior_set='Y';
				parent.minlimit_VAR='3';
				parent.maxlimit_VAR='10';
				parent.code='人民幣(RMB)';
				parent.ltype='3';
				parent.str_even = '和局';
				parent.str_submit = '确认';
				parent.str_reset = '重设';
				parent.langx='zh-cn';
				parent.rtype='<?php echo $oldRtype?>';
				parent.sel_lid='';
				parent.g_date = 'ALL'; // 默认时间
				parent.retime = 180; // 刷新时间

		<?php
		switch ($rtype){
					case "FUTURE_R":  // 全部
						$length = count($future_r_data) ; // 长度
						$page_count=ceil($length/$page_size); // 总共多少页
						$offset=$page_no*60;
						echo "parent.str_renew = '手动更新';\n";
						echo "parent.game_more=1;\n";
						echo "parent.str_more='多种玩法';\n";
						echo "parent.t_page=$page_count;\n";
						
						$gameVideoNow = $redisObj->getSimpleOne('gameVideoNow');
						$gameVideoNowArr = json_decode($gameVideoNow,true);
						$gameVideoFuture = $redisObj->getSimpleOne('gameVideoFuture');
						$gameVideoFutureArr = json_decode($gameVideoFuture,true);
						
						for($i=$offset;$i<($page_no+1)*$page_size;$i++){
							if($future_r_data[$i]['MB_Team']){ // 防止空数据
								$MB_Win_Rate=change_rate($open,$future_r_data[$i]["MB_Win_Rate"]);
								$TG_Win_Rate=change_rate($open,$future_r_data[$i]["TG_Win_Rate"]);
								$M_Flat_Rate=change_rate($open,$future_r_data[$i]["M_Flat_Rate"]);

								// 全场让球单独处理
								$ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate"],$future_r_data[$i]["TG_LetB_Rate"],100); // 默认都是香港盘
								$MB_LetB_Rate=$ra_rate[0]; // 主队
								$TG_LetB_Rate=$ra_rate[1]; // 客队
								$MB_LetB_Rate=change_rate($open,$MB_LetB_Rate);
								$TG_LetB_Rate=change_rate($open,$TG_LetB_Rate);
								// 全场大小单独处理
								$dx_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate"],$future_r_data[$i]["TG_Dime_Rate"],100); // 默认都是香港盘
								$MB_Dime_Rate=$dx_rate[0]; // 主队
								$TG_Dime_Rate=$dx_rate[1]; // 客队
								$MB_Dime_Rate=change_rate($open,$MB_Dime_Rate);
								$TG_Dime_Rate=change_rate($open,$TG_Dime_Rate);

								$S_Single_Rate=change_rate($open,$future_r_data[$i]['S_Single_Rate']);
								$S_Double_Rate=change_rate($open,$future_r_data[$i]['S_Double_Rate']);

								$MB_Win_Rate_H=change_rate($open,$future_r_data[$i]["MB_Win_Rate_H"]); // 独赢主队
								$TG_Win_Rate_H=change_rate($open,$future_r_data[$i]["TG_Win_Rate_H"]); // 独赢客队
								$M_Flat_Rate_H=change_rate($open,$future_r_data[$i]["M_Flat_Rate_H"]); // 独赢和局

								// 半场让球单独处理
								$h_ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate_H"],$future_r_data[$i]["TG_LetB_Rate_H"],100); // 默认都是香港盘
								$MB_LetB_Rate_H=$h_ra_rate[0]; //半场-让球 主队赢
								$TG_LetB_Rate_H=$h_ra_rate[1]; //半场-让球 客队赢
								$MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);
								$TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H);
								// 半场大小单独处理
								$h_dx_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate_H"],$future_r_data[$i]["TG_Dime_Rate_H"],100); // 默认都是香港盘
								$MB_Dime_Rate_H=$h_dx_rate[0]; // 主队
								$TG_Dime_Rate_H=$h_dx_rate[1]; // 客队
								$MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H);
								$TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);

								if ($future_r_data[$i]['HPD_Show']==1 and $future_r_data[$i]['PD_Show']==1 and $future_r_data[$i]['T_Show']==1 and $future_r_data[$i]['F_Show']==1){
									$show=4;
								}else if ($future_r_data[$i]['PD_Show']==1 and $future_r_data[$i]['T_Show']==1 and $future_r_data[$i]['F_Show']==1){
									$show=3;
								}else{
									$show=0;
								}
								$m_date=strtotime($future_r_data[$i]['M_Date']);
								$dates=date("m-d",$m_date);
								if ($future_r_data[$i]['M_Type']==1){
									$Running="<br><font color=red>滾球</font>";
								}else{
									$Running="";
								}
								$allMethods=$future_r_data[$i][more]<5 ? 0:$future_r_data[$i][more];
								$MID = $future_r_data[$i]['MID'];
								$MB_Team = $future_r_data[$i]['MB_Team'];
								$ShowTypeR = $future_r_data[$i]['ShowTypeR'];
								$MB_Dime = $future_r_data[$i]['MB_Dime'];
								$TG_Dime = $future_r_data[$i]['TG_Dime'];
								$M_LetB = $future_r_data[$i]['M_LetB'];
								$M_LetB_H = $future_r_data[$i]['M_LetB_H'];
								$MB_Dime_H = $future_r_data[$i]['MB_Dime_H'];
								$TG_Dime_H = $future_r_data[$i]['TG_Dime_H'];
								
								if($ShowTypeR=="H"){
									$ratio_mb_str=$M_LetB;
									$ratio_tg_str='';
									$hratio_mb_str=$M_LetB_H;
									$hratio_tg_str='';
								}elseif($ShowTypeR=="C"){
									$ratio_mb_str='';
									$ratio_tg_str=$M_LetB;
									$hratio_mb_str='';
									$hratio_tg_str=$M_LetB_H;
								}
								$MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
								$MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
								$newDataArray[$MID]['gid']=$MID;    
								$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'].$Running;    
								$newDataArray[$MID]['datetimelove']=date('m-d')."<br>".$future_r_data[$i][M_Time];  
								$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
								$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];      			  
								$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                                if ($future_r_data[$i]['Neutral']==1){
                                    $newDataArray[$MID]['team_h']=$MB_Team." <font color='#005aff'>[中]</font>";
                                }else{
                                    $newDataArray[$MID]['team_h']=$MB_Team;
                                }
								$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
								$newDataArray[$MID]['strong']=$ShowTypeR;      
								$newDataArray[$MID]['ratio'] =$future_r_data[$i]['M_LetB'];
								$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
								$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;      
								$newDataArray[$MID]['ior_RH']= $MB_LetB_Rate;      
								$newDataArray[$MID]['ior_RC']=$TG_LetB_Rate;    
								$newDataArray[$MID]['bet_RH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$MB_MID}&strong={$ShowTypeR}&langx={$langx}";
								$newDataArray[$MID]['bet_RC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";  
								$newDataArray[$MID]['ratio_o']=$MB_Dime;      
								$newDataArray[$MID]['ratio_u']=$TG_Dime;   
								$newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$MB_Dime);
								$newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$TG_Dime);   
								$newDataArray[$MID]['ior_OUH']=$TG_Dime_Rate;      
								$newDataArray[$MID]['ior_OUC']=$MB_Dime_Rate;  
								$newDataArray[$MID]['bet_OUH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$MB_MID}&langx={$langx}";
								$newDataArray[$MID]['bet_OUC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$TG_MID}&langx={$langx}";    
								$newDataArray[$MID]['ior_MH']=$MB_Win_Rate;      
								$newDataArray[$MID]['ior_MC']=$TG_Win_Rate;      
								$newDataArray[$MID]['ior_MN']=$M_Flat_Rate;
								$newDataArray[$MID]['bet_MH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$MB_MID}&strong={$ShowTypeR}&langx={$langx}";
								$newDataArray[$MID]['bet_MC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";
								$newDataArray[$MID]['bet_MN']="gid={$MID}&uid={$uid}&odd_f_type=H&type=N&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";      
								$newDataArray[$MID]['str_odd']=$o;      
								$newDataArray[$MID]['str_even']=$e;      
								$newDataArray[$MID]['ior_EOO']=$S_Single_Rate;      
								$newDataArray[$MID]['ior_EOE']=$S_Double_Rate;   
								$newDataArray[$MID]['bet_EOO']="gid={$MID}&uid={$uid}&odd_f_type=H&rtype=ODD&langx={$langx}";
								$newDataArray[$MID]['bet_EOE']="gid={$MID}&uid={$uid}&odd_f_type=H&rtype=EVEN&langx={$langx}";   
								$newDataArray[$MID]['hgid']= $MID;      
								$newDataArray[$MID]['hstrong']=$future_r_data[$i]['ShowTypeHR'];      
								$newDataArray[$MID]['hratio']=$M_LetB_H;  
								$newDataArray[$MID]['hratio_mb_str']=$hratio_mb_str;
								$newDataArray[$MID]['hratio_tg_str']=$hratio_tg_str;    
								$newDataArray[$MID]['ior_HRH']=$MB_LetB_Rate_H;      
								$newDataArray[$MID]['ior_HRC']=$TG_LetB_Rate_H;      
								$newDataArray[$MID]['hratio_o']=$MB_Dime_H;      
								$newDataArray[$MID]['hratio_u']=$TG_Dime_H;    
								$newDataArray[$MID]['hratio_o_str']="大".str_replace('O','',$MB_Dime_H);
								$newDataArray[$MID]['hratio_u_str']="小".str_replace('U','',$TG_Dime_H);  
								$newDataArray[$MID]['ior_HOUH']=$TG_Dime_Rate_H;      
								$newDataArray[$MID]['ior_HOUC']=$MB_Dime_Rate_H;      
								$newDataArray[$MID]['ior_HMH']=$MB_Win_Rate_H;      
								$newDataArray[$MID]['ior_HMC']=$TG_Win_Rate_H;      
								$newDataArray[$MID]['ior_HMN']=$M_Flat_Rate_H;      
								$newDataArray[$MID]['more']=$show;      
								$newDataArray[$MID]['all']=$allMethods;      
								$newDataArray[$MID]['eventid']=$future_r_data[$i]['Eventid'];      
								$newDataArray[$MID]['hot'] = $future_r_data[$i]['Hot'];      
								$newDataArray[$MID]['play'] = $future_r_data[$i]['Play'];
								if(in_array($future_r_data[$i]['Eventid'],$gameVideoNowArr)){
									$newDataArray[$MID]['event']='on';	
								}elseif(in_array($future_r_data[$i]['Eventid'],$gameVideoFutureArr)){
									$newDataArray[$MID]['event']='out';	
								}else{
									$newDataArray[$MID]['event']='no';	
								}
								$K=$K+1;
								$page_gamecount ++ ;
							}
						}
						echo "parent.gamount=$page_gamecount;\n";
						$listTitle="早盘足球";
						$leagueNameCur='';
						break;
					case "FUTURE_PD":  // 波胆全场
						$length = count($future_r_data) ; // 长度
						$page_count=ceil($length/$page_size); // 总共多少页
						$offset=$page_no*60;

						echo "parent.retime=0;\n";
						echo "parent.t_page=$page_count;\n";
						echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";
						for($i=$offset;$i<($page_no+1)*$page_size;$i++){
//							if($future_r_data[$i]['MB_MID']){ // 防止空数据
								$m_date=strtotime($future_r_data[$i]['M_Date']);
								$dates=date("m-d",$m_date);
								$MB_Team=$future_r_data[$i]['MB_Team'];
								$MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
								$MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
								$MID=$future_r_data[$i]['MID'];
								$newDataArray[$MID]['gid']=$MID;
								$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
								$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
								$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
								$newDataArray[$MID]['team_h']=$MB_Team;
								$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
								$newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
								$newDataArray[$MID]['ior_H1C0']=change_rate($open,$future_r_data[$i]['MB1TG0']);
								$newDataArray[$MID]['ior_H2C0']=change_rate($open,$future_r_data[$i]['MB2TG0']);
								$newDataArray[$MID]['ior_H2C1']=change_rate($open,$future_r_data[$i]['MB2TG1']);
								$newDataArray[$MID]['ior_H3C0']=change_rate($open,$future_r_data[$i]['MB3TG0']);
								$newDataArray[$MID]['ior_H3C1']=change_rate($open,$future_r_data[$i]['MB3TG1']);
								$newDataArray[$MID]['ior_H3C2']=change_rate($open,$future_r_data[$i]['MB3TG2']);
								$newDataArray[$MID]['ior_H4C0']=change_rate($open,$future_r_data[$i]['MB4TG0']);
								$newDataArray[$MID]['ior_H4C1']=change_rate($open,$future_r_data[$i]['MB4TG1']);
								$newDataArray[$MID]['ior_H4C2']=change_rate($open,$future_r_data[$i]['MB4TG2']);
								$newDataArray[$MID]['ior_H4C3']=change_rate($open,$future_r_data[$i]['MB4TG3']);
								$newDataArray[$MID]['ior_H0C0']=change_rate($open,$future_r_data[$i]['MB0TG0']);
								$newDataArray[$MID]['ior_H1C1']=change_rate($open,$future_r_data[$i]['MB1TG1']);
								$newDataArray[$MID]['ior_H2C2']=change_rate($open,$future_r_data[$i]['MB2TG2']);
								$newDataArray[$MID]['ior_H3C3']=change_rate($open,$future_r_data[$i]['MB3TG3']);
								$newDataArray[$MID]['ior_H4C4']=change_rate($open,$future_r_data[$i]['MB4TG4']);
								$newDataArray[$MID]['ior_OVH' ]=change_rate($open,$future_r_data[$i]['UP5']);
								$newDataArray[$MID]['ior_H0C1']=change_rate($open,$future_r_data[$i]['MB0TG1']);
								$newDataArray[$MID]['ior_H0C2']=change_rate($open,$future_r_data[$i]['MB0TG2']);
								$newDataArray[$MID]['ior_H1C2']=change_rate($open,$future_r_data[$i]['MB1TG2']);
								$newDataArray[$MID]['ior_H0C3']=change_rate($open,$future_r_data[$i]['MB0TG3']);
								$newDataArray[$MID]['ior_H1C3']=change_rate($open,$future_r_data[$i]['MB1TG3']);
								$newDataArray[$MID]['ior_H2C3']=change_rate($open,$future_r_data[$i]['MB2TG3']);
								$newDataArray[$MID]['ior_H0C4']=change_rate($open,$future_r_data[$i]['MB0TG4']);
								$newDataArray[$MID]['ior_H1C4']=change_rate($open,$future_r_data[$i]['MB1TG4']);
								$newDataArray[$MID]['ior_H2C4']=change_rate($open,$future_r_data[$i]['MB2TG4']);
								$newDataArray[$MID]['ior_H3C4']=change_rate($open,$future_r_data[$i]['MB3TG4']);
								$newDataArray[$MID]['bet_Url']="gid={$future_r_data[$i]['MID']}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
								$K=$K+1;
								$page_gamecount ++ ;
//							}
						}
						echo "parent.gamount=$page_gamecount;\n";
						$listTitle="早盘足球 : 波胆";
						$leagueNameCur='';
						break;
					case "FUTURE_HPD":  // 波胆半场
						$length = count($future_r_data) ; // 长度
						$page_count=ceil($length/$page_size); // 总共多少页
						$offset=$page_no*60;
						echo "parent.t_page=$page_count;\n";
						for($i=$offset;$i<($page_no+1)*$page_size;$i++){
							if($future_r_data[$i]['MB_MID']){ // 防止空数据
								$m_date=strtotime($future_r_data[$i]['M_Date']);
								$dates=date("m-d",$m_date);
								$MID = $future_r_data[$i]['MID'] ;
								$newDataArray[$MID]['gid']=$MID;
								$newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
								$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
								$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
								$newDataArray[$MID]['team_h']=$future_r_data[$i]['MB_Team'].'<font color=gray> - ['.$Order_1st_Half.']</font>';
								$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'].'<font color=gray> - ['.$Order_1st_Half.']</font>';
								$newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
								$newDataArray[$MID]['ior_H1C0'] =change_rate($open,$future_r_data[$i]['MB1TG0H']);
								$newDataArray[$MID]['ior_H2C0'] =change_rate($open,$future_r_data[$i]['MB2TG0H']);
								$newDataArray[$MID]['ior_H2C1'] =change_rate($open,$future_r_data[$i]['MB2TG1H']);
								$newDataArray[$MID]['ior_H3C0'] =change_rate($open,$future_r_data[$i]['MB3TG0H']);
								$newDataArray[$MID]['ior_H3C1'] =change_rate($open,$future_r_data[$i]['MB3TG1H']);
								$newDataArray[$MID]['ior_H3C2'] =change_rate($open,$future_r_data[$i]['MB3TG2H']);
								$newDataArray[$MID]['ior_H0C0'] =change_rate($open,$future_r_data[$i]['MB0TG0H']);
								$newDataArray[$MID]['ior_H1C1'] =change_rate($open,$future_r_data[$i]['MB1TG1H']);
								$newDataArray[$MID]['ior_H2C2'] =change_rate($open,$future_r_data[$i]['MB2TG2H']);
								$newDataArray[$MID]['ior_H3C3'] =change_rate($open,$future_r_data[$i]['MB3TG3H']);
								$newDataArray[$MID]['ior_OVH']  = change_rate($open,$future_r_data[$i]['UP5H']);
								$newDataArray[$MID]['ior_H0C1'] =change_rate($open,$future_r_data[$i]['MB0TG1H']);
								$newDataArray[$MID]['ior_H0C2'] =change_rate($open,$future_r_data[$i]['MB0TG2H']);
								$newDataArray[$MID]['ior_H1C2'] =change_rate($open,$future_r_data[$i]['MB1TG2H']);
								$newDataArray[$MID]['ior_H0C3'] =change_rate($open,$future_r_data[$i]['MB0TG3H']);
								$newDataArray[$MID]['ior_H1C3'] =change_rate($open,$future_r_data[$i]['MB1TG3H']);
								$newDataArray[$MID]['ior_H2C3'] =change_rate($open,$future_r_data[$i]['MB2TG3H']);
								$newDataArray[$MID]['bet_Url']="gid={$future_r_data[$i]['MID']}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
								$K=$K+1;
								$page_gamecount ++ ;
							}
						}
						echo "parent.gamount=$page_gamecount;\n";
						$listTitle="早盘足球 : 波胆";
						$leagueNameCur='';
						break;
					case "FUTURE_T": // 总入球
						$length = count($future_r_data) ; // 长度
						$page_count=ceil($length/$page_size); // 总共多少页
						$offset=$page_no*60;
						echo "parent.retime=0;\n";
						echo "parent.t_page=$page_count;\n";
						echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER','ior_MH','ior_MC','ior_MN');";
						for($i=$offset;$i<($page_no+1)*$page_size;$i++){
							if($future_r_data[$i]['MB_MID']){ // 防止空数据
								$MB_Team=$future_r_data[$i]['MB_Team'];
								$MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
								$MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
								$MID = $future_r_data[$i]['MID'] ;
								$m_date=strtotime($future_r_data[$i]['M_Date']);
								$dates=date("m-d",$m_date);
								$newDataArray[$MID]['gid']=$MID;
								$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
								$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
								$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
								$newDataArray[$MID]['team_h']=$MB_Team;
								$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
								$newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
								$newDataArray[$MID]['ior_T01']=change_rate($open,$future_r_data[$i]['S_0_1']);
								$newDataArray[$MID]['ior_T23']=change_rate($open,$future_r_data[$i]['S_2_3']);
								$newDataArray[$MID]['ior_T46']=change_rate($open,$future_r_data[$i]['S_4_6']);
								$newDataArray[$MID]['ior_OVER']=change_rate($open,$future_r_data[$i]['S_7UP']);
								$newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";		
								$K=$K+1;
								$page_gamecount ++ ;
							}
						}
						echo "parent.gamount=$page_gamecount;\n";
						$listTitle="早盘足球 : 总入球";
						$leagueNameCur='';
						break;
					case "FUTURE_F": // 半场/全场
						$length = count($future_r_data) ; // 长度
						$page_count=ceil($length/$page_size); // 总共多少页
						$offset=$page_no*60;
						echo "parent.retime=0;\n";
						echo "parent.t_page=$page_count;\n";
						echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');";
						for($i=$offset;$i<($page_no+1)*$page_size;$i++){
							if($future_r_data[$i]['MB_MID']){ // 防止空数据
								$MID = $future_r_data[$i]['MID'] ;
								$m_date=strtotime($future_r_data[$i]['M_Date']);
								$dates=date("m-d",$m_date);
								$MB_Team=$future_r_data[$i]['MB_Team'];
								$MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
								$MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
								$newDataArray[$MID]['gid']=$MID;
								$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
								$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
								$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
								$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
								$newDataArray[$MID]['team_h']=$MB_Team;
								$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
								$newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
								$newDataArray[$MID]['ior_FHH']=change_rate($open,$future_r_data[$i]['MBMB']);
								$newDataArray[$MID]['ior_FHN']=change_rate($open,$future_r_data[$i]['MBFT']);
								$newDataArray[$MID]['ior_FHC']=change_rate($open,$future_r_data[$i]['MBTG']);
								$newDataArray[$MID]['ior_FNH']=change_rate($open,$future_r_data[$i]['FTMB']);
								$newDataArray[$MID]['ior_FNN']=change_rate($open,$future_r_data[$i]['FTFT']);
								$newDataArray[$MID]['ior_FNC']=change_rate($open,$future_r_data[$i]['FTTG']);
								$newDataArray[$MID]['ior_FCH']=change_rate($open,$future_r_data[$i]['TGMB']);
								$newDataArray[$MID]['ior_FCN']=change_rate($open,$future_r_data[$i]['TGFT']);
								$newDataArray[$MID]['ior_FCC']=change_rate($open,$future_r_data[$i]['TGTG']);
								$newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
								$K=$K+1;
								$page_gamecount ++ ;
							}

						}
						echo "parent.gamount=$page_gamecount;\n";
						$listTitle="早盘足球 : 半场 /全场";
						$leagueNameCur='';
						break;
					case "FUTURE_FT_P3": // 综合过关
						$resulTotal=$future_r_data;
						$cou=count($resulTotal);
						echo "parent.retime=0;\n";
						echo "parent.game_more=1;\n";
						echo "parent.str_more='$more';\n";
						echo "parent.gamount=$cou;\n";
						$page_count=intval($cou/$page_size);
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
                            $newDataArray[$row[MID]]['more']=$row["more"];
							$newDataArray[$row[MID]]['gidm']=$row[MID];
							$newDataArray[$row[MID]]['par_minlimit']=3;
							$newDataArray[$row[MID]]['par_maxlimit']=10;	
							$K=$K+1;
						}
						$listTitle="早盘足球 : 综合过关";
						$leagueNameCur='';
						break;
				case "FUTURE_BK_ALL":
                        $page_size = 60;
                        $length = count($future_r_data) ; // 长度
                        $page_count=ceil($length/$page_size); // 总共多少页
                        $offset=$page_no*$page_size;
                        $resultArr=array_slice($future_r_data,$offset,$page_size);
                        $cou = count($resultArr);
                        echo "parent.retime=180;\n";
//                        echo "parent.str_renew = '$second_auto_update';\n";
                        echo "parent.t_page=$page_count;\n";
                        echo "parent.gamount=$cou;\n";
                        $newDataArray=[];
                    foreach($resultArr as $key=>$row){
//            		for($i=$offset;$i<($page_no+1)*$page_size;$i++){
//							if($future_r_data[$i]['MB_MID']){ // 防止空数据
								$M_Flat_Rate=change_rate($open,$row["M_Flat_Rate"]); //全场和的赔率
								// MB_Dime_Rate主队全场赔率      TG_Dime_Rate客队全场赔率
								$ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100); // 默认都是香港盘
								$MB_Dime_Rate=$ra_rate[0]; // 主队
								$TG_Dime_Rate=$ra_rate[1]; // 客队
				
								// 全场让球单独处理
								$ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
								$MB_LetB_Rate=$ra_rate[0]; // 主队    主队让球赔率
								$TG_LetB_Rate=$ra_rate[1]; // 客队    客队让球赔率
								$ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
								$MB_Dime_Rate_H=$ra_rate[0]; // 主队半场大的赔率      主队半场赔率
								$MB_Dime_Rate_S_H=$ra_rate[1]; // 主队半场小的赔率    半场主队独赢小的赔率
								$ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
								$TG_Dime_Rate_H=$ra_rate[0]; // 客队半场大的赔率      客队半场赔率
								$TG_Dime_Rate_S_H=$ra_rate[1]; //客队半场小的赔率     半场客队独赢小的赔率
							
								if($S_Single_Rate==''){
									$Single='';
								}else{
									$Single=$o;
								}
								if($S_Double_Rate==''){
									$Double='';
								}else{
									$Double=$e;
								}
								
								if($row['ShowTypeR']=="H"){
									$ratio_mb_str=$row['M_LetB'];
									$ratio_tg_str='';
								}elseif($row['ShowTypeR']=="C"){
									$ratio_mb_str='';
									$ratio_tg_str=$row['M_LetB'];
								}
								
								$m_date=strtotime($row['M_Date']);
								$dates=date("m-d",$m_date);
								$MID = $row['MID'] ;
								 $newDataArray[$MID]['gid']=$MID;
								 $newDataArray[$MID]['datetime']=$row['M_Type']==1?$dates.'<br>'.$row['M_Time'].'<br><font color=red>滚球</font>':$dates.'<br>'.$row['M_Time'];
								 $newDataArray[$MID]['dategh']=date('m-d').$row['MB_MID'];
								 $newDataArray[$MID]['datetimelove']=$date."<br>".$row['M_Time'];
								 $newDataArray[$MID]['league']=$row['M_League'];
								 $newDataArray[$MID]['gnum_h']=$row['MB_MID'];
								 $newDataArray[$MID]['gnum_c']=$row['TG_MID'];
								 $newDataArray[$MID]['team_h']=$row['MB_Team'];
								 $newDataArray[$MID]['team_c']=$row['TG_Team'];
								 $newDataArray[$MID]['strong']=$row['ShowTypeR'];
								 $newDataArray[$MID]['ratio']=$row['M_LetB'];
								 $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
								 $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
								 $newDataArray[$MID]['ior_RH']=change_rate($open,$MB_LetB_Rate);
								 $newDataArray[$MID]['ior_RC']=change_rate($open,$TG_LetB_Rate);
								 $newDataArray[$MID]['ratio_o']=$row['MB_Dime'];
								 $newDataArray[$MID]['ratio_u']=$row['TG_Dime'];
                                 $newDataArray[$MID]['ratio_o_str']=str_replace('O','大',$row['MB_Dime']);
                                 $newDataArray[$MID]['ratio_u_str']=str_replace('U','小',$row['TG_Dime']);
								 $newDataArray[$MID]['ior_OUH']=change_rate($open,$TG_Dime_Rate);
								 $newDataArray[$MID]['ior_OUC']=change_rate($open,$MB_Dime_Rate);
								 $newDataArray[$MID]['ior_MH']=change_rate($open,$row["MB_Win_Rate"]); //主队独赢赔率
								 $newDataArray[$MID]['ior_MC']=change_rate($open,$row["TG_Win_Rate"]); //客队独赢赔率
								 $newDataArray[$MID]['str_odd']=$Single;
								 $newDataArray[$MID]['str_even']=$Double;
								 $newDataArray[$MID]['ior_EOO']=change_rate($open,$row['S_Single_Rate']); // 主队单双赔率
								 $newDataArray[$MID]['ior_EOE']=change_rate($open,$row['S_Double_Rate']); // 客队单双赔率
								 $newDataArray[$MID]['ratio_ouho']=$row['MB_Dime_H'];
								 $newDataArray[$MID]['ratio_ouhu']=$row['MB_Dime_S_H'];
								 $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$row['MB_Dime_H']);
								 $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$row['MB_Dime_S_H']);
								 $newDataArray[$MID]['ior_OUHO']=change_rate($open,$MB_Dime_Rate_H);
								 $newDataArray[$MID]['ior_OUHU']=change_rate($open,$MB_Dime_Rate_S_H);
								 $newDataArray[$MID]['ratio_ouco']=$row['TG_Dime_H'];
								 $newDataArray[$MID]['ratio_oucu']=$row['TG_Dime_S_H'];
								 $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$row['TG_Dime_H']);
								 $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$row['TG_Dime_S_H']);
								 $newDataArray[$MID]['ior_OUCO']=change_rate($open,$TG_Dime_Rate_H);
								 $newDataArray[$MID]['ior_OUCU']=change_rate($open,$TG_Dime_Rate_S_H);
								 $newDataArray[$MID]['eventid']=$row['Eventid'];
								 $newDataArray[$MID]['hot']=$row['Hot'];
								 $newDataArray[$MID]['play']=$row['Play'];
								 $newDataArray[$MID]['all']=$row['more'];
								 $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&gnum={$row['MB_MID']}&langx={$langx}";
								 
								$K=$K+1;
								$page_gamecount ++ ;
//							}
				
						}
						$listTitle="早盘篮球和美式足球 ";
						$leagueNameCur='';
						break;
					case "FUTURE_BK_R":
							$length = count($future_r_data) ; // 长度
							$page_count=ceil($length/$page_size); // 总共多少页
							$offset=$page_no*60;
							echo "parent.retime=90;\n";
							echo "parent.str_renew = '$second_auto_update';\n";
							echo "parent.t_page=$page_count;\n";
							echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','str_odd','str_even','ior_EOO','ior_EOE','more','eventid','hot','play');";
					
							for($i=$offset;$i<($page_no+1)*$page_size;$i++){
								if($future_r_data[$i]['MB_MID']){ // 防止空数据
									$MB_Dime_Rate=change_rate($open,$future_r_data[$i]["MB_Dime_Rate"]);
									$TG_Dime_Rate=change_rate($open,$future_r_data[$i]["TG_Dime_Rate"]);
									// 全场让球单独处理
									$ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate"],$future_r_data[$i]["TG_LetB_Rate"],100); // 默认都是香港盘
									$MB_LetB_Rate=$ra_rate[0]; // 主队
									$TG_LetB_Rate=$ra_rate[1]; // 客队
									$MB_LetB_Rate=change_rate($open,$MB_LetB_Rate);
									$TG_LetB_Rate=change_rate($open,$TG_LetB_Rate);
									$S_Single_Rate=change_rate($open,$future_r_data[$i]['S_Single_Rate']);
									$S_Double_Rate=change_rate($open,$future_r_data[$i]['S_Double_Rate']);
									if ($S_Single_Rate==''){
										$Single='';
									}else{
										$Single=$o;
									}
									if ($S_Double_Rate==''){
										$Double='';
									}else{
										$Double=$e;
									}
									$m_date=strtotime($future_r_data[$i]['M_Date']);
									$dates=date("m-d",$m_date);
					
									$MID = $future_r_data[$i]['MID'] ;
									$M_League = $future_r_data[$i]['M_League'] ;
									$MB_MID = $future_r_data[$i]['MB_MID'] ;
									$TG_MID = $future_r_data[$i]['TG_MID'] ;
									$MB_Team = $future_r_data[$i]['MB_Team'] ;
									$TG_Team = $future_r_data[$i]['TG_Team'] ;
									$ShowTypeR = $future_r_data[$i]['ShowTypeR'] ;
									$M_LetB = $future_r_data[$i]['M_LetB'] ;
									$MB_Dime = $future_r_data[$i]['MB_Dime'] ;
									$TG_Dime = $future_r_data[$i]['TG_Dime'] ;
									$MB_Dime_H = $future_r_data[$i]['MB_Dime_H'] ;
									$MB_Dime_S_H = $future_r_data[$i]['MB_Dime_S_H'] ;
									$TG_Dime_H = $future_r_data[$i]['TG_Dime_H'] ;
									$TG_Dime_S_H = $future_r_data[$i]['TG_Dime_S_H'] ;
									$Eventid = $future_r_data[$i]['Eventid'] ;
									$Hot = $future_r_data[$i]['Hot'] ;
									$Play = $future_r_data[$i]['Play'] ;
									$more = $future_r_data[$i]['more'] ;
					
									$dateadds = $dates.'&nbsp;'.$date.'<br>'.$future_r_data[$i]['M_Time'].$Running.','.$future_r_data[$i]['M_League'] ;
									if ($future_r_data[$i]['M_Type']==1){
										$dateadd = $dates.'<br>'.$future_r_data[$i]['M_Time'].'<br><font color=red>Running Ball</font>' ;
										echo "parent.GameFT[$K]= Array('$MID','$dateadd','$M_League','$MB_MID','$TG_MID','$MB_Team','$TG_Team','$ShowTypeR','$M_LetB','$MB_LetB_Rate','$TG_LetB_Rate','$MB_Dime','$TG_Dime','$TG_Dime_Rate','$MB_Dime_Rate','$Single','$Double','$S_Single_Rate','$S_Double_Rate','0','$Eventid','$Hot','$Play');\n";
									}else{
										$Running='';
									}
									echo "parent.GameFT[$K]=new Array('$MID','$dateadds','$M_League','$MB_MID','$TG_MID','$MB_Team','$TG_Team','$ShowTypeR','$M_LetB','$MB_LetB_Rate','$TG_LetB_Rate','$MB_Dime','$TG_Dime','$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate',
										  '$o','$e','$S_Single_Rate','$S_Double_Rate',
										  '$MB_Dime_H','$MB_Dime_S_H','$MB_Dime_Rate_H','$MB_Dime_Rate_S_H','$TG_Dime_H','$TG_Dime_S_H','$TG_Dime_Rate_H','$TG_Dime_Rate_S_H','$more','$Eventid','$Hot','$Play');\n";
					
									$K=$K+1;
									$page_gamecount ++ ;
								}
							}
							echo "parent.gamount=$page_gamecount;\n";
							break;
						case "FUTURE_BK_P3":  //综合过关
							$length = count($future_r_data) ; // 长度
							$page_count=ceil($length/$page_size); // 总共多少页
							$offset=$page_no*60;
							echo "parent.retime=0;\n";
							echo "parent.t_page=$page_count;\n";
									for($i=$offset;$i<($page_no+1)*$page_size;$i++){
										if($future_r_data[$i]['MB_MID']){ // 防止空数据
											$S_Single_Rate=change_rate($open,$future_r_data[$i]['S_Single_Rate']); // 主队单双
											$S_Double_Rate=change_rate($open,$future_r_data[$i]['S_Double_Rate']); // 客队单双
											if ($S_Single_Rate==''){
												$Single='';
											}else{
												$Single=$o;
											}
											if ($S_Double_Rate==''){
												$Double='';
											}else{
												$Double=$e;
											}
											$m_date=strtotime($future_r_data[$i]['M_Date']);
											$date=date("m-d",$m_date);
											if (strlen($future_r_data[$i]['M_Time'])==5){
												$pdate=$date.'<br>0'.$future_r_data[$i]['M_Time'];
											}else{
												$pdate=$date.'<br>'.$future_r_data[$i]['M_Time'];
											}
											
											if($future_r_data[$i]['ShowTypeP']=="H"){
													$ratio_mb_str=$future_r_data[$i]['M_P_LetB'];
													$ratio_tg_str='';
											}elseif($future_r_data[$i]['ShowTypeP']=="C"){
													$ratio_mb_str='';
													$ratio_tg_str=$future_r_data[$i]['M_P_LetB'];
											}
							
											$MID = $future_r_data[$i]['MID'];
											$newDataArray[$MID]['gid']=$MID;
											$newDataArray[$MID]['datetime']=$pdate;
											$newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
											$newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
											$newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
											$newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
											$newDataArray[$MID]['team_h']=$future_r_data[$i]['MB_Team'];
											$newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
											$newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeP'];
											$newDataArray[$MID]['ratio']=$future_r_data[$i]['M_P_LetB'];
											$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
											$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
											$newDataArray[$MID]['ior_PRH']=change_rate($open,$future_r_data[$i]['MB_P_LetB_Rate']);
											$newDataArray[$MID]['ior_PRC']=change_rate($open,$future_r_data[$i]['TG_P_LetB_Rate']);
											$newDataArray[$MID]['ratio_o']=$future_r_data[$i]['MB_P_Dime'];
											$newDataArray[$MID]['ratio_u']=$future_r_data[$i]['TG_P_Dime'];
											$newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$future_r_data[$i][MB_P_Dime]);
											$newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$future_r_data[$i][TG_P_Dime]);
											$newDataArray[$MID]['ior_POUC']=change_rate($open,$future_r_data[$i]['MB_P_Dime_Rate']);
											$newDataArray[$MID]['ior_POUH']=change_rate($open,$future_r_data[$i]['TG_P_Dime_Rate']);
											$newDataArray[$MID]['str_odd']=$Single;
											$newDataArray[$MID]['str_even']=$Double;
											$newDataArray[$MID]['ior_PO']=change_rate($open,$future_r_data[$i]['S_P_Single_Rate']);
											$newDataArray[$MID]['ior_PE']=change_rate($open,$future_r_data[$i]['S_P_Double_Rate']);
											$newDataArray[$MID]['ior_PMH']=change_rate($open,$future_r_data[$i]["MB_P_Win_Rate"]);
											$newDataArray[$MID]['ior_PMC']=change_rate($open,$future_r_data[$i]["TG_P_Win_Rate"]);
											$newDataArray[$MID]['hratio']=$future_r_data[$i]['M_LetB_H'];
											$newDataArray[$MID]['gidm']=$MID;
											$newDataArray[$MID]['par_minlimit']=3;
											$newDataArray[$MID]['par_maxlimit']=10;
											$newDataArray[$MID]['ratio_pouho']=$future_r_data[$i]['MB_Dime_H'];
											$newDataArray[$MID]['ratio_pouhu']=$future_r_data[$i]['MB_Dime_S_H'];
											$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$future_r_data[$i]['MB_Dime_H']);
											$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$future_r_data[$i]['MB_Dime_S_H']);
											$newDataArray[$MID]['ior_POUHO']=change_rate($open,$future_r_data[$i]["MB_P_Dime_Rate_H"]); 
											$newDataArray[$MID]['ior_POUHU']=change_rate($open,$future_r_data[$i]["MB_P_Dime_Rate_S_H"]); 
											$newDataArray[$MID]['ratio_pouco']=$future_r_data[$i]['TG_Dime_H'];
											$newDataArray[$MID]['ratio_poucu']=$future_r_data[$i]['TG_Dime_S_H'];
											$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$future_r_data[$i]['TG_Dime_H']);
											$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$future_r_data[$i]['TG_Dime_S_H']);
											$newDataArray[$MID]['ior_POUCO']=change_rate($open,$future_r_data[$i]["TG_P_Dime_Rate_H"]); 
											$newDataArray[$MID]['ior_POUCU']=change_rate($open,$future_r_data[$i]["TG_P_Dime_Rate_S_H"]); 
											
											$K=$K+1;
											$page_gamecount ++ ;
										}
									}
								echo "parent.gamount=$page_gamecount;\n";
								$listTitle="早盘篮球和美式足球 : 综合过关";
								$leagueNameCur='';
								break;
							}	
		?>
		</script>
		<link rel="stylesheet" href="/style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css" media="screen">
		</head>
		<body i1d="MFT" class="bodyset FTR body_browse_set" onload="onLoad();">
		<?php 
				if(in_array($rtype,array('FUTURE_R','FUTURE_PD','FUTURE_HPD','FUTURE_T','FUTURE_F','FUTURE_FT_P3'))){
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
					for($datei=1;$datei<10;$datei++){
						$dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
						$dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
						echo "<span value='$dateNowValue' onclick='chg_gdate(this)' class='".($g_date==$dateNowValue?'choose_select':'')."'>$dateNowStr</span>";
					}
					echo "<span value='ALL' onclick='chg_gdate(this)' class='".($g_date=='ALL'?'choose_select':'')."'>全部</span>";
					echo "</span></div>";
				}
		?>
        <div class="ss_table" style="display: inline-block">
		    <table border="0" cellpadding="0" cellspacing="0" id="myTable">
			<tbody>
				<tr>
					<td>
					 <table border="0" cellpadding="0" cellspacing="0" id="box" class="">
						<tbody>
							<tr>
								<td class="top">
									<h1 class="top_h1">
										<em><?php echo $listTitle; ?></em>
										<?php
										if($rtype=='FUTURE_PD' || $rtype=='FUTURE_HPD'){ // 波胆才有
											if($rtype=='FUTURE_HPD'){
												$select = 'selected' ;
											}else{
												$select = '' ;
											}
											echo '<select id="selwtype" onChange="chg_wtype(selwtype.value);">
														<option value="pd" >全场</option>
														<option value="hpd" '.$select.' >上半场</option>
											  </select>' ;
										}

										if($rtype=='FUTURE_PD' || $rtype=='FUTURE_HPD' || $rtype=='FUTURE_F'|| $rtype=='FUTURE_FT_P3'){
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
												<span id="pg_txt">&nbsp;
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
													case "FUTURE_R":		include "Running/body_m_r_ou_eo.php";break;
													case "FUTURE_PD":		include "Running/body_pd.php";break;
													case "FUTURE_HPD":		include "Running/body_hpd.php";break;
													case "FUTURE_T":		include "Running/body_t.php";break;
													case "FUTURE_F":		include "Running/body_f.php";break;
													case "FUTURE_FT_P3":	include "Running/body_p3.php";break;
													case "FUTURE_BK_R":		include "Running/body_bk_m_r_ou.php";break;
													case "FUTURE_BK_ALL":	include "Running/body_bk_m_r_ou.php";break;
													case "FUTURE_BK_P3":	include "Running/body_bk_p3.php";break;
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
		
		<!-- 2018 新增 右侧视频 
		<div class="today_bet_floatright <?php echo $today_bet_floatright?>" >
		    <iframe id="live" name="live" src="../live/live.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>"></iframe>
		</div>
		-->

		<script type="text/javascript" src="../../../js/jquery.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
		<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
        <script>
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
			if($rtype=="FUTURE_BK_R" || $rtype=="FUTURE_BK_ALL" || $rtype=="FUTURE_BK_P3"){
				$dir = "/www/huangguan/hg3088/member_new/app/member/BK_future/";
			}else{
				$dir = "/www/huangguan/hg3088/member_new/app/member/FT_future/";
			}
			$filesName=strtolower("Future".$open.$rtype).time().".html";
			$info=ob_get_contents(); 
			ob_end_clean();
			$file = $dir.$filesName;
			$handle = fopen($file, 'w+');
			fwrite($handle, $info);
			fclose($handle);
			unset($newDataArray);
			$redisObj->setOne($rtype.'_'.$open.'_URL',$filesName);
	}
	unset($future_r_data);
}
?>
