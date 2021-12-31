<?php
/*	
 *
 * 	每天定时自动计算返水报表（体育、AG真人电子）
 * 	1，	只支持cli模式下的运行。
 * 	2，	示例URL:
 *	 			php rebate_daily_report_general.php 									//定时任务，生成昨日的数据
 *			或	
 *	 			php rebate_daily_report_general.php old								//重新批量生成数据
 *			或
 *	 			php rebate_daily_report_general.php 2016-10-06						//重新生成某一个天的数据
 *			或
 *	 			php rebate_daily_report_general.php 2016-10-06 2016-10-07 1	//重新生成从某一个天到某一天的数据
 * 	3，	开启时间，每天的美东时间3,4,5点执行计划任务
 *  4， 执行相关历史报表统计脚本完成后，再统计返水数据
 *  5， 剔除加入时时返水的会员，时时返水会员不能参加天天返水
 *  6， 剔除不返水分层的会员，加入不返水分层的会员不能参加返水
 *
 *	auth: lincoin
 *	2018-03-27
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";

$conn = $dbMasterLink;
$sAg_prefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_'; // AG用户名前缀 BT5A_，返水需要转为体育的用户名

//只在CLI命令下有效
if (php_sapi_name() == "cli") {

	//只设置参数a old，则从9.1号开始，将今天之前的所有数据全部重新生成
	if(isset($argv[1]) && $argv[1] == 'old') {

	    $start_time = mktime(0, 0, 0, 9, 1, 2017);		//从2016.9.1号可以计算数据
		//当天不计算到历史数据里面，因为未完成
		$stop_time = strtotime(date("Y-m-d"));
		countall($start_time,$stop_time, false, $conn, $database['cpDefault'], $sAg_prefix);
	}elseif(isset($argv[1])) {

		//重新生成某天-某天的报表数据，包含 开始天，不包含 结束天
		$start_time = strtotime($argv[1]);
		if($argv[1] > date("Y-m-d", strtotime("-1 day"))) {
			exit("起始时间不能大于昨天");
		}
		
		if(isset($argv[2]) && !empty($argv[2])) {
			//如果结束时间大于今天，那么将今天作为结束时间
			if($argv[2] > date("Y-m-d")) {
				$argv[2] = date("Y-m-d");
			}
			$stop_time = strtotime($argv[2]);
		}else {
			$stop_time = strtotime($argv[1]."+1 day");
		}

		//如果设置了参数 c 1，则置gxfcy_history_bill_report_flag标示位为2，代表此数据已经重新生成（主要是为了包含香港六合彩的数据）
		if( isset($argv[3]) && $argv[3] == 1 ) {
			twopreweekcountall($start_time, $stop_time, $conn, $database['cpDefault'], $sAg_prefix);
		}else {
			countall($start_time, $stop_time, false, $conn, $database['cpDefault'], $sAg_prefix);
		}
	}else {

		//每日生成昨日的报表数据
		$start_time = strtotime(date("Y-m-d",strtotime("-1 day")));
		$stop_time = strtotime(date("Y-m-d"));

//		echo date("Y-m-d",strtotime("-1 day")).'-'.date("Y-m-d"); die;

		dailycountall($start_time,$stop_time, $conn, $database['cpDefault'], $sAg_prefix);
	}
}


/**
 * 
 * 每日生成历史報表，多次cronjob运行的情况下，只需要检查疏漏，不需要重复执行
 * @param date $StartTime
 * @param date $stop_time
 */
function dailycountall($StartTime, $stop_time, $conn, $aCp_default, $sAg_prefix){
    global $dbLink;
	$result=array();

	//首先，从历史报表里面清楚掉数据，再重新计算
	$sql = " select rebate_date,flag from ".DBPREFIX."rebate_history_report_flag where rebate_date >= '".date("Y-m-d",$StartTime)."' and rebate_date < '".date("Y-m-d",$stop_time)."' ";
	$result=mysqli_query($dbLink, $sql);
	while($row = mysqli_fetch_array($result)) {
		//已经生成
		if(isset($row['flag']) && $row['flag'] == 1) {
			exit("数据已经生成");
		}
	}
	countall($StartTime, $stop_time, false, $conn, $aCp_default, $sAg_prefix);
	exit("数据生成成功");
}

/**
 * 
 * 重新生成某段时间点历史报表，多次cronjob运行的情况下，只需要检查疏漏，不需要重复执行
 * @param date $StartTime
 * @param date $stop_time
 */
function twopreweekcountall($StartTime, $Endtime, $conn, $aCp_default, $sAg_prefix){
    global $dbLink;
	$result=array();

	//首先判断这天的数据是否存在或者是否已经重新生成
	for($i=1; $i<=50; $i++) {
		$stop_time = $StartTime + 3600*24;
		if($stop_time <= $Endtime) {
			$sql = " select * from ".DBPREFIX."rebate_history_report_flag where rebate_date >= '".date("Y-m-d",$StartTime)."' and rebate_date < '".date("Y-m-d",$stop_time)."' ";
			$result=mysqli_query($dbLink, $sql);
			$row = mysqli_fetch_array($result);
			//已经生成
			if(isset($row['flag']) && $row['flag'] == 2) {
				echo date("Y-m-d", $StartTime)." 报表已经重新生成！\n";
				$StartTime = $stop_time;
				continue;
			}
			countall($StartTime, $stop_time, true, $conn, $aCp_default, $sAg_prefix);
		}else {
			exit("数据重新生成成功！\n");
		}
		$StartTime = $stop_time;
	}
}

/**
 *
 * 根据条件，统计数据（体育、真人、彩票、开元棋牌、皇冠棋牌、VG棋牌、乐游棋牌、MG电子、泛亚电竞、雷火电竞、OG视讯、MW电子、CQ9电子、FG电子、BBIN视讯、快乐棋牌）/，生成返水报表
 *
 * @param date $StartTime
 * @param date $stop_time
 */
function countall($StartTime, $stop_time, $reGeneral=false, $conn, $aCp_default, $sAg_prefix){
    global $dbLink, $og_prefix, $mw_prefix, $bbin_prefix;

    @error_log(date("Y-m-d H:i:s").'--------------- 生成返水报表开始'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

    //如果结束时间大于当天凌晨，则将当天凌晨当做结束时间
	if($stop_time > strtotime(date("Y-m-d"))) {
		$stop_time = strtotime(date("Y-m-d"));
	}

    // 要统计的日期列表
    $d = ($stop_time - $StartTime) / (3600*24); // 天数

	//首先，从返水报表里面清除掉数据，再重新计算
	$sql = " DELETE from ".DBPREFIX."rebate_history_report where R_date >= '".date("Y-m-d",$StartTime)."' and R_date < '".date("Y-m-d",$stop_time)."'";
	$result=mysqli_query($conn, $sql);
	$sql = " DELETE from ".DBPREFIX."rebate_history_report_flag where rebate_date >= '".date("Y-m-d",$StartTime)."' and rebate_date < '".date("Y-m-d",$stop_time)."' ";
	$result=mysqli_query($conn, $sql);


    // 体育返水点数
    $result_rebate= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 1");
    $cou = mysqli_num_rows($result_rebate);
    if ($cou>0){
        $data_rebate_hg = [];
        while ($row = mysqli_fetch_assoc($result_rebate)){
            $data_rebate_hg[] = $row;
        }
    }
/*
    // 彩票返水点数
    $result_rebate_cp= mysqli_query($conn, "select * from ".DBPREFIX."rebate_game_settings where game_type = 3");
    $cou = mysqli_num_rows($result_rebate_cp);
    if ($cou>0){
        $data_rebate_cp = [];
        while ($row = mysqli_fetch_assoc($result_rebate_cp)){
            $data_rebate_cp[] = $row;
        }
    }*/

    // AG返水点数公用（真人视讯、电子游艺、捕鱼王）
    $result_rebate_ag= mysqli_query($dbLink, "select id,rebate_name,rebate,game_type,left_interval,right_interval from ".DBPREFIX."rebate_game_settings where game_type in (2,5,6)");
    $cou = mysqli_num_rows($result_rebate_ag);
    if ($cou>0){
        $data_rebate_ag = [];
        while ($row = mysqli_fetch_assoc($result_rebate_ag)){
            if( $row['game_type']==2 ){ $data_rebate_ag['zrsx'][] = $row; }
            if( $row['game_type']==5 ){ $data_rebate_ag['dz'][] = $row; }
            if( $row['game_type']==6 ){ $data_rebate_ag['byw'][] = $row; }
        }
    }

    // 开元棋牌返水点数
    $result_rebate_ky= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 4");
    $cou = mysqli_num_rows($result_rebate_ky);
    if ($cou>0){
        $data_rebate_ky = [];
        while ($row = mysqli_fetch_assoc($result_rebate_ky)){
            $data_rebate_ky[] = $row;
        }
    }

    // 皇冠棋牌返水点数
    $result_rebate_hgqp= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 7");
    $cou = mysqli_num_rows($result_rebate_hgqp);
    if ($cou>0){
        $data_rebate_hgqp = [];
        while ($row = mysqli_fetch_assoc($result_rebate_hgqp)){
            $data_rebate_hgqp[] = $row;
        }
    }

    // VG棋牌返水点数
    $result_rebate_vgqp= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 8");
    $cou = mysqli_num_rows($result_rebate_vgqp);
    if ($cou>0){
        $data_rebate_vgqp = [];
        while ($row = mysqli_fetch_assoc($result_rebate_vgqp)){
            $data_rebate_vgqp[] = $row;
        }
    }

    // 乐游棋牌返水点数
    $result_rebate_lyqp= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 9");
    $cou = mysqli_num_rows($result_rebate_lyqp);
    if ($cou>0){
        $data_rebate_lyqp = [];
        while ($row = mysqli_fetch_assoc($result_rebate_lyqp)){
            $data_rebate_lyqp[] = $row;
        }
    }

    // MG电子返水点数
    $result_rebate_mg= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 10");
    $cou = mysqli_num_rows($result_rebate_mg);
    if ($cou>0){
        $data_rebate_mg = [];
        while ($row = mysqli_fetch_assoc($result_rebate_mg)){
            $data_rebate_mg[] = $row;
        }
    }

    // 泛亚电竞返水点数
    $result_rebate_avia= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 11");
    $cou = mysqli_num_rows($result_rebate_avia);
    if ($cou>0){
        $data_rebate_avia = [];
        while ($row = mysqli_fetch_assoc($result_rebate_avia)){
            $data_rebate_avia[] = $row;
        }
    }

    // OG视讯返水点数
    $result_rebate_og= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 12");
    $cou = mysqli_num_rows($result_rebate_og);
    if ($cou>0){
        $data_rebate_og = [];
        while ($row = mysqli_fetch_assoc($result_rebate_og)){
            $data_rebate_og[] = $row;
        }
    }

    // CQ9电子返水点数
    $result_rebate_cq= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 13");
    $cou = mysqli_num_rows($result_rebate_cq);
    if ($cou>0){
        $data_rebate_cq = [];
        while ($row = mysqli_fetch_assoc($result_rebate_cq)){
            $data_rebate_cq[] = $row;
        }
    }

    // MW电子返水点数
    $result_rebate_mw= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 14");
    $cou = mysqli_num_rows($result_rebate_mw);
    if ($cou>0){
        $data_rebate_mw = [];
        while ($row = mysqli_fetch_assoc($result_rebate_mw)){
            $data_rebate_mw[] = $row;
        }
    }

    // FG电子返水点数
    $result_rebate_fg= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 15");
    $cou = mysqli_num_rows($result_rebate_fg);
    if ($cou>0){
        $data_rebate_fg = [];
        while ($row = mysqli_fetch_assoc($result_rebate_fg)){
            $data_rebate_fg[] = $row;
        }
    }

    // BBIN视讯返水点数
    $result_rebate_bbin= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 16");
    $cou = mysqli_num_rows($result_rebate_bbin);
    if ($cou>0){
        $data_rebate_bbin = [];
        while ($row = mysqli_fetch_assoc($result_rebate_bbin)){
            $data_rebate_bbin[] = $row;
        }
    }

    // 快乐棋牌返水点数
    $result_rebate_klqp= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 17");
    $cou = mysqli_num_rows($result_rebate_klqp);
    if ($cou>0){
        $data_rebate_klqp = [];
        while ($row = mysqli_fetch_assoc($result_rebate_klqp)){
            $data_rebate_klqp[] = $row;
        }
    }

    // 雷火电竞返水点数
    $result_rebate_fire= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_settings where game_type = 18");
    $cou = mysqli_num_rows($result_rebate_fire);
    if ($cou>0){
        $data_rebate_fire = [];
        while ($row = mysqli_fetch_assoc($result_rebate_fire)){
            $data_rebate_fire[] = $row;
        }
    }

    // 循环统计 d 天的数据
	for($i=1; $i<=$d; $i++) {

        $end_time = $StartTime + 3600*24;

        $result=mysqli_query($conn, "START TRANSACTION");
		if (!$result) {
		    echo('事务开启失败！ ' . mysqli_error($conn));
		    continue;
		}

        @error_log('--------------- 生成报表第'.$d.'天开始'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

        if($end_time <= $stop_time) {

		    // 体育历史汇总数据
            $result_data = mysqli_query($dbLink,"select userid, username, sum(count_pay) as count_pay, sum(valid_money_rebate) as total, M_Date, bet_time from ".DBPREFIX."web_report_history_report_data where M_Date='".date("Y-m-d",$StartTime)."' group by username ");
            $cou=mysqli_num_rows($result_data);
            if ($cou>0){
                $data=[];
                while ($row = mysqli_fetch_assoc($result_data)){
                    $data[]=$row;
                }
                // 根据userid，捞出会员表的用户名使用，防止生成多条返水记录-----------20191229 lincoin
                $sHgUserid = implode(',',array_column($data, 'userid'));
                $result_hg_user =  mysqli_query($dbLink, "select ID,UserName from ".DBPREFIX."web_member_data WHERE ID in ({$sHgUserid})");
                while ($row = mysqli_fetch_assoc($result_hg_user)){
                    $aUserNameForHgRebate[] = $row;
                }
                foreach ($aUserNameForHgRebate as $k => $v){
                    $aUserNameForHgRebateId[$v['ID']] = $v;
                }
                foreach ($data as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_hg as $k1 => $v1){

                        if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                            $data[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            continue;
                        }
                    }
                    $data[$k]['username']=$aUserNameForHgRebateId[$v['userid']]['UserName'];
                    $data[$data[$k]['username']]=$data[$k];
                    unset($data[$k]);
                }
            }

            @error_log('--------------- 体育数据已捞出，共'.count($data).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 彩票数据
            /*$cpMasterDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
            $result_data_cp = mysqli_query($cpMasterDbLink,"SELECT userid, username, game_code, sum(count_pay) as count_pay, sum(valid_money) as total, bet_time FROM `gxfcy_history_bill_report` WHERE bet_time BETWEEN '$StartTime' and '".$end_time."' and testflag = 0 group by username");
            $cou_cp=mysqli_num_rows($result_data_cp);
            if($cou_cp>0){
                $data_cp=[];
                while ($row = mysqli_fetch_assoc($result_data_cp)){
                    $data_cp[]=$row;
                }
                foreach ($data_cp as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_cp as $k1 => $v1){
                        if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                            $data_cp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_cp[$v['username']]=$data_cp[$k];
                    unset($data_cp[$k]);
                }
            }

            @error_log('--------------- 彩票数据已捞出，共'.count($data_cp).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');*/

            // AG真人视讯数据
            $result_data_ag = mysqli_query($dbLink,"select userid, username, game_code, sum(count_pay) as count_pay, sum(valid_money) as total, bet_time from ".DBPREFIX."ag_projects_history_report where M_Date='".date("Y-m-d",$StartTime)."' and game_code='BR' group by username ");
            $cou_ag=mysqli_num_rows($result_data_ag);
            if ($cou_ag>0){
                $data_ag=[];
                while ($row = mysqli_fetch_assoc($result_data_ag)){
                    $data_ag[]=$row;
                }
                foreach ($data_ag as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_ag['zrsx'] as $k1 => $v1){
                        if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                            $data_ag[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $username = explode($sAg_prefix, $v['username']);
                    // AG用户名转为HG用户名，去掉前缀
                    $data_ag[$k]['username']= $username[1];
                    $data_ag[$username[1]]=$data_ag[$k];
                    unset($data_ag[$k]);
                }
            }

            @error_log('--------------- AG真人视讯数据已捞出，共'.count($data_ag).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // AG电子数据
            $result_data_ag_dianzi = mysqli_query($dbLink,"select userid, username, game_code, sum(count_pay) as count_pay, sum(valid_money) as total, bet_time from ".DBPREFIX."ag_projects_history_report where M_Date='".date("Y-m-d",$StartTime)."' and (game_code='' or game_code='SLOT') group by username ");
            $cou_ag_dianzi=mysqli_num_rows($result_data_ag_dianzi);
            if ($cou_ag_dianzi>0){

                while ($row = mysqli_fetch_assoc($result_data_ag_dianzi)){
                    $data_ag_dianzi[]=$row;
                }
                foreach ($data_ag_dianzi as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_ag['dz'] as $k1 => $v1){
                        if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                            $data_ag_dianzi[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $username = explode($sAg_prefix, $v['username']);
                    // AG用户名转为HG用户名，去掉前缀
                    $data_ag_dianzi[$k]['username']= $username[1];
                    $data_ag_dianzi[$username[1]]=$data_ag_dianzi[$k];
                    unset($data_ag_dianzi[$k]);
                }
            }
            @error_log('--------------- AG电子数据已捞出，共'.count($data_ag_dianzi).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // AG捕鱼王打鱼数据
            $result_data_ag_dayu = mysqli_query($dbLink,"select userid, UserName as username, sum(BulletOutNum) as count_pay, sum(Cost) as total, sum(Cost) as valid_money  from ".DBPREFIX."ag_buyu_scene where EndTime BETWEEN '".date("Y-m-d 00:00:00",$StartTime)."' and '".date("Y-m-d 00:00:00",$end_time)."' group by UserName ");
            $cou_ag_dayu=mysqli_num_rows($result_data_ag_dayu);
            if ($cou_ag_dayu>0){

                while ($row = mysqli_fetch_assoc($result_data_ag_dayu)){
                    $data_ag_dayu[]=$row;
                }
                foreach ($data_ag_dayu as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_ag['byw'] as $k1 => $v1){
                        if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                            $data_ag_dayu[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $username = explode($sAg_prefix, $v['username']);
                    // AG用户名转为HG用户名，去掉前缀
                    $data_ag_dayu[$k]['username']= $username[1];
                    $data_ag_dayu[$username[1]]=$data_ag_dayu[$k];
                    unset($data_ag_dayu[$k]);
                }
            }
            @error_log('--------------- AG捕鱼王打鱼数据已捞出，共'.count($data_ag_dayu).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 开元棋牌数据
            $sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "ky_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_ky = mysqli_query($dbLink, $sql);
            $cou_ky=mysqli_num_rows($result_data_ky);
            if ($cou_ky>0){
                $data_ky=[];
                while ($row = mysqli_fetch_assoc($result_data_ky)){
                    $data_ky[]=$row;
                }
                foreach ($data_ky as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_ky as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_ky[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_ky[$v['username']]=$data_ky[$k];
                    unset($data_ky[$k]);
                }
            }

            @error_log('--------------- 开元棋牌数据已捞出，共'.count($data_ky).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');


            // 皇冠棋牌数据
            $hgqp_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "ff_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_hgqp = mysqli_query($dbLink, $hgqp_sql);
            $cou_hgqp=mysqli_num_rows($result_data_hgqp);
            if ($cou_hgqp>0){
                $data_hgqp=[];
                while ($row = mysqli_fetch_assoc($result_data_hgqp)){
                    $data_hgqp[]=$row;
                }
                foreach ($data_hgqp as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_hgqp as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_hgqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_hgqp[$v['username']]=$data_hgqp[$k];
                    unset($data_hgqp[$k]);
                }
            }

            @error_log('--------------- 皇冠棋牌数据已捞出，共'.count($data_hgqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');


            // VG棋牌数据
            $vgqp_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "vg_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_vgqp = mysqli_query($dbLink, $vgqp_sql);
            $cou_vgqp=mysqli_num_rows($result_data_vgqp);
            if ($cou_vgqp>0){
                $data_vgqp=[];
                while ($row = mysqli_fetch_assoc($result_data_vgqp)){
                    $data_vgqp[]=$row;
                }
                foreach ($data_vgqp as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_vgqp as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_vgqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_vgqp[$v['username']]=$data_vgqp[$k];
                    unset($data_vgqp[$k]);
                }
            }

            @error_log('--------------- VG棋牌数据已捞出，共'.count($data_vgqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');


            // 乐游棋牌数据 total_bet 投注，total_cellscore 有效投注
            $lyqp_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "ly_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_lyqp = mysqli_query($dbLink, $lyqp_sql);
            $cou_lyqp=mysqli_num_rows($result_data_lyqp);
            if ($cou_lyqp>0){
                $data_lyqp=[];
                while ($row = mysqli_fetch_assoc($result_data_lyqp)){
                    $data_lyqp[]=$row;
                }
                foreach ($data_lyqp as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_lyqp as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_lyqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_lyqp[$v['username']]=$data_lyqp[$k];
                    unset($data_lyqp[$k]);
                }
            }

            @error_log('--------------- 乐游棋牌数据已捞出，共'.count($data_lyqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 快乐棋牌数据 total_bet 投注，total_cellscore 有效投注
            $klqp_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "kl_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_klqp = mysqli_query($dbLink, $klqp_sql);
            $cou_klqp=mysqli_num_rows($result_data_klqp);
            if ($cou_klqp>0){
                $data_klqp=[];
                while ($row = mysqli_fetch_assoc($result_data_klqp)){
                    $data_klqp[]=$row;
                }
                foreach ($data_klqp as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_klqp as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_klqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_klqp[$v['username']]=$data_klqp[$k];
                    unset($data_klqp[$k]);
                }
            }

            @error_log('--------------- 快乐棋牌数据已捞出，共'.count($data_klqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');


            // MG电子数据 total_bet 投注，total_cellscore 有效投注
            $mg_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "mg_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_mg = mysqli_query($dbLink, $mg_sql);
            $cou_mg=mysqli_num_rows($result_data_mg);
            if ($cou_mg>0){
                $data_mg=[];
                while ($row = mysqli_fetch_assoc($result_data_mg)){
                    $data_mg[]=$row;
                }
                foreach ($data_mg as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_mg as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_mg[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $username = explode('_', $v['username'],2);
                    // MG用户名转为HG用户名，去掉前缀
                    $data_mg[$k]['username']= $username[1];
                    $data_mg[$username[1]]=$data_mg[$k];
                    unset($data_mg[$k]);
                }
            }

            @error_log('--------------- MG电子数据已捞出，共'.count($data_mg).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 泛亚电竞数据 total_bet 投注，total_cellscore 有效投注
            $avia_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "avia_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_avia = mysqli_query($dbLink, $avia_sql);
            $cou_avia=mysqli_num_rows($result_data_avia);
            if ($cou_avia>0){
                $data_avia=[];
                while ($row = mysqli_fetch_assoc($result_data_avia)){
                    $data_avia[]=$row;
                }
                foreach ($data_avia as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_avia as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_avia[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_avia[$v['username']]=$data_avia[$k];
                    unset($data_avia[$k]);
                }
            }

            @error_log('--------------- 泛亚电竞数据已捞出，共'.count($data_avia).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 雷火电竞数据 total_bet 投注，total_cellscore 有效投注
            $fire_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "fire_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_fire = mysqli_query($dbLink, $fire_sql);
            $cou_fire=mysqli_num_rows($result_data_fire);
            if ($cou_fire>0){
                $data_fire=[];
                while ($row = mysqli_fetch_assoc($result_data_fire)){
                    $data_fire[]=$row;
                }
                foreach ($data_fire as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_fire as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_fire[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    $data_fire[$v['username']]=$data_fire[$k];
                    unset($data_fire[$k]);
                }
            }

            @error_log('--------------- 雷火电竞数据已捞出，共'.count($data_fire).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');



            // OG视讯数据 total_bet 投注，total_cellscore 有效投注
            $og_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "og_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_og = mysqli_query($dbLink, $og_sql);
            $cou_og=mysqli_num_rows($result_data_og);
            if ($cou_og>0){
                $data_og=[];
                while ($row = mysqli_fetch_assoc($result_data_og)){
                    $data_og[]=$row;
                }
                // 根据userid，捞出会员表的用户名使用，防止生成多条返水记录-----------20191229 lincoin
                $sOgUserid = implode(',',array_column($data_og, 'userid'));
                $result_og_user =  mysqli_query($dbLink, "select ID,UserName from ".DBPREFIX."web_member_data WHERE ID in ({$sOgUserid})");
                while ($row = mysqli_fetch_assoc($result_og_user)){
                    $aUserNameForOGRebate[] = $row;
                }
                foreach ($aUserNameForOGRebate as $k => $v){
                    $aUserNameForOGRebateId[$v['ID']] = $v;
                }
                foreach ($data_og as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_og as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_og[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
//                    // OG用户名转为HG用户名，去掉前缀
//                    $username = explode($og_prefix, $v['username']);
//                    $data_og[$k]['username']=$username[1];
//                    $data_og[$username[1]]=$data_og[$k];
                    $data_og[$k]['username']=$aUserNameForOGRebateId[$v['userid']]['UserName'];
                    $data_og[$data_og[$k]['username']]=$data_og[$k];
                    unset($data_og[$k]);
                }
            }

            @error_log('--------------- OG视讯数据已捞出，共'.count($data_og).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // CQ9电子数据 total_bet 投注，total_cellscore 有效投注
            $cq_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "cq9_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_cq = mysqli_query($dbLink, $cq_sql);
            $cou_cq=mysqli_num_rows($result_data_cq);
            if ($cou_cq>0){
                $data_cq=[];
                while ($row = mysqli_fetch_assoc($result_data_cq)){
                    $data_cq[]=$row;
                }
                foreach ($data_cq as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_cq as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_cq[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    // CQ9用户名转为HG用户名
                    $data_cq[$v['username']]=$data_cq[$k];
                    unset($data_cq[$k]);
                }
            }

            @error_log('--------------- CQ9电子数据已捞出，共'.count($data_cq).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // MW电子数据 total_bet 投注，total_cellscore 有效投注
            $mw_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "mw_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_mw = mysqli_query($dbLink, $mw_sql);
            $cou_mw=mysqli_num_rows($result_data_mw);
            if ($cou_mw>0){
                $data_mw=[];
                while ($row = mysqli_fetch_assoc($result_data_mw)){
                    $data_mw[]=$row;
                }
                foreach ($data_mw as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_mw as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_mw[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    // OG用户名转为HG用户名，去掉前缀
                    $username = explode('_', $v['username'],2);
                    $data_mw[$k]['username']=$username[1];
                    $data_mw[$username[1]]=$data_mw[$k];
                    unset($data_mw[$k]);
                }
            }

            @error_log('--------------- MW电子数据已捞出，共'.count($data_mw).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');


            // FG电子数据 total_bet 投注，total_cellscore 有效投注
            $fg_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "fg_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_fg = mysqli_query($dbLink, $fg_sql);
            $cou_fg=mysqli_num_rows($result_data_fg);
            if ($cou_fg>0){
                $data_fg=[];
                while ($row = mysqli_fetch_assoc($result_data_fg)){
                    $data_fg[]=$row;
                }
                foreach ($data_fg as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_fg as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_fg[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    // FG用户名转为HG用户名，去掉前缀
                    $username = explode('_', $v['username'],2);
                    $data_fg[$k]['username']=$username[1];
                    $data_fg[$username[1]]=$data_fg[$k];
                    unset($data_fg[$k]);
                }
            }

            @error_log('--------------- FG电子数据已捞出，共'.count($data_fg).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // BBIN视讯数据 total_bet 投注，total_cellscore 有效投注
            $bbin_sql = "SELECT `userid`, `username`, SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `total`, `count_date` 
                    FROM " . DBPREFIX . "jx_bbin_history_report 
                    WHERE `count_date` = '" . date("Y-m-d",$StartTime) . "' GROUP BY `username`";
            $result_data_bbin = mysqli_query($dbLink, $bbin_sql);
            $cou_bbin=mysqli_num_rows($result_data_bbin);
            if ($cou_bbin>0){
                $data_bbin=[];
                while ($row = mysqli_fetch_assoc($result_data_bbin)){
                    $data_bbin[]=$row;
                }
                // 根据userid，捞出会员表的用户名使用，防止生成多条返水记录-----------20191229 lincoin
                $sBbinUserid = implode(',',array_column($data_bbin, 'userid'));
                $result_bbin_user =  mysqli_query($dbLink, "select ID,UserName from ".DBPREFIX."web_member_data WHERE ID in ({$sBbinUserid})");
                while ($row = mysqli_fetch_assoc($result_bbin_user)){
                    $aUserNameForBbinRebate[] = $row;
                }
                foreach ($aUserNameForBbinRebate as $k => $v){
                    $aUserNameForBbinRebateId[$v['ID']] = $v;
                }
                foreach ($data_bbin as $k =>$v){
                    // 计算返水
                    foreach ($data_rebate_bbin as $k1 => $v1){
                        if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                            $data_bbin[$k]['R_total'] = $v['total'] * $v1['rebate'];
                            break;
                        }
                    }
                    // bbin用户名转为HG用户名，去掉前缀
//                    $username = strtolower(substr($v['username'], strlen(strtoupper($bbin_prefix))));
                    $username = $aUserNameForBbinRebateId[$v['userid']]['UserName'];
                    $data_bbin[$k]['username']=$username;
                    $data_bbin[$username]=$data_bbin[$k];
                    unset($data_bbin[$k]);
                }
            }

            @error_log('--------------- BBIN视讯数据已捞出，共'.count($data_bbin).'条'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');



            // 将当天的数据按照游戏类别统计，分别从各个游戏数组中捞取
            // 统计体育会员
            $day_data = [];
            if (count($data)>0){
                foreach ($data as $k => $v) {
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y') . '-' . date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_ag[$k]['count_pay'] + $data_ag_dianzi[$k]['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay'] + $data_klqp[$k]['count_pay']+ $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_ag[$k]['total'] + $data_ag_dianzi[$k]['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_hg'] = $v['total'];
                    $day_data[$k]['total_ag'] = $data_ag[$k]['total'];
                    $day_data[$k]['total_ag_dianzi'] = $data_ag_dianzi[$k]['total'];
                    $day_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
                    $day_data[$k]['total_ky'] = $data_ky[$k]['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_ag[$k]['R_total'] + $data_ag_dianzi[$k]['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total'] + $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_hg'] = $v['R_total'];
                    $day_data[$k]['R_total_ag'] = $data_ag[$k]['R_total'];
                    $day_data[$k]['R_total_ag_dianzi'] = $data_ag_dianzi[$k]['R_total'];
                    $day_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
                    $day_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data[$k]);
                    unset($data_ag[$k]);
                    unset($data_ag_dianzi[$k]);
                    unset($data_ag_dayu[$k]);
                    unset($data_ky[$k]);
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计AG视讯会员
            if (count($data_ag)>0){
                foreach ($data_ag as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d',$StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z',$StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_ag_dianzi[$k]['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay'] + $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_ag_dianzi[$k]['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_ag'] = $v['total'];
                    $day_data[$k]['total_ag_dianzi'] = $data_ag_dianzi[$k]['total'];
                    $day_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
                    $day_data[$k]['total_ky'] = $data_ky[$k]['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_ag_dianzi[$k]['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_ag'] = $v['R_total'];
                    $day_data[$k]['R_total_ag_dianzi'] = $data_ag_dianzi[$k]['R_total'];
                    $day_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
                    $day_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_ag[$k]);
                    unset($data_ag_dianzi[$k]);
                    unset($data_ag_dayu[$k]);
                    unset($data_ky[$k]);
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计AG电子会员
            if (count($data_ag_dianzi)>0){
                foreach ($data_ag_dianzi as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d',$StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z',$StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay'] + $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_ag_dianzi'] = $v['total'];
                    $day_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
                    $day_data[$k]['total_ky'] = $data_ky[$k]['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total'] + $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_ag_dianzi'] = $v['R_total'];
                    $day_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
                    $day_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_ag_dianzi[$k]);
                    unset($data_ag_dayu[$k]);
                    unset($data_ky[$k]);
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计AG捕鱼王打鱼会员
            if (count($data_ag_dayu)>0){
                foreach ($data_ag_dayu as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d',$StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z',$StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_ag_dayu'] = $v['total'];
                    $day_data[$k]['total_ky'] = $data_ky[$k]['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_ag_dayu'] = $v['R_total'];
                    $day_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_ag_dayu[$k]);
                    unset($data_ky[$k]);
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计开元棋牌会员
            if (count($data_ky)>0){
                foreach ($data_ky as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay'] + $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_ky'] = $v['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total'] + $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_ky'] = $v['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_ky[$k]);
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计皇冠棋牌会员
            if (count($data_hgqp)>0){
                foreach ($data_hgqp as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay'] + $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_hgqp[$k]);
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计VG棋牌会员
            if (count($data_vgqp)>0){
                foreach ($data_vgqp as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_vgqp'] = $v['total'];
                    $day_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_vgqp'] = $v['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_vgqp[$k]);
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }
            // 统计乐游棋牌会员
            if (count($data_lyqp)>0){
                foreach ($data_lyqp as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_klqp[$k]['count_pay']+ $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_klqp[$k]['total']+ $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_lyqp'] = $v['total'];
                    $day_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total'] + $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_lyqp'] = $v['R_total'];
                    $day_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_lyqp[$k]);
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计快乐棋牌会员
            if (count($data_klqp)>0){
                foreach ($data_klqp as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_klqp'] = $v['total'];
                    $day_data[$k]['total_mg'] = $data_mg[$k]['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total'] + $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_klqp'] = $v['R_total'];
                    $day_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_klqp[$k]);
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计MG电子会员
            if (count($data_mg)>0){
                foreach ($data_mg as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_mg'] = $v['total'];
                    $day_data[$k]['total_avia'] = $data_avia[$k]['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_mg'] = $v['R_total'];
                    $day_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_mg[$k]);
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计泛亚电竞会员
            if (count($data_avia)>0){
                foreach ($data_avia as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_avia'] = $v['total'];
                    $day_data[$k]['total_fire'] = $data_fire[$k]['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_avia'] = $v['R_total'];
                    $day_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_avia[$k]);
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计雷火电竞会员
            if (count($data_fire)>0){
                foreach ($data_fire as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_fire'] = $v['total'];
                    $day_data[$k]['total_og'] = $data_og[$k]['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_fire'] = $v['R_total'];
                    $day_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_fire[$k]);
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计OG视讯会员
            if (count($data_og)>0){
                foreach ($data_og as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay'] + $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total'] + $data_bbin[$k]['total'];
                    $day_data[$k]['total_og'] = $v['total'];
                    $day_data[$k]['total_mw'] = $data_mw[$k]['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_og'] = $v['R_total'];
                    $day_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_og[$k]);
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计MW电子会员
            if (count($data_mw)>0){
                foreach ($data_mw as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_mw'] = $v['total'];
                    $day_data[$k]['total_cq'] = $data_cq[$k]['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_mw'] = $v['R_total'];
                    $day_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_mw[$k]);
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }


            // 统计CQ9电子会员
            if (count($data_cq)>0){
                foreach ($data_cq as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_cq'] = $v['total'];
                    $day_data[$k]['total_fg'] = $data_fg[$k]['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_cq'] = $v['R_total'];
                    $day_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_cq[$k]);
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计FG电子会员
            if (count($data_fg)>0){
                foreach ($data_fg as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay']+ $data_bbin[$k]['count_pay'];
                    $day_data[$k]['total'] = $v['total']+ $data_bbin[$k]['total'];
                    $day_data[$k]['total_fg'] = $v['total'];
                    $day_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
                    $day_data[$k]['R_total'] = $v['R_total']+ $data_bbin[$k]['R_total'];
                    $day_data[$k]['R_total_fg'] = $v['R_total'];
                    $day_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_fg[$k]);
                    unset($data_bbin[$k]);
                }
            }

            // 统计BBIN视讯会员
            if (count($data_bbin)>0){
                foreach ($data_bbin as $k => $v){
                    $day_data[$k]['userid'] = $v['userid'];
                    $day_data[$k]['username'] = $v['username'];
                    $day_data[$k]['R_date'] = date('Y-m-d', $StartTime);
                    $day_data[$k]['R_period'] = date('Y').'-'.date('z', $StartTime);
                    $day_data[$k]['count_pay'] = $v['count_pay'];
                    $day_data[$k]['total'] = $v['total'];
                    $day_data[$k]['total_bbin'] = $v['total'];
                    $day_data[$k]['R_total'] = $v['R_total'];
                    $day_data[$k]['R_total_bbin'] = $v['R_total'];
                    $day_data[$k]['operation_time'] = date('Y-m-d H:i:s');
                    unset($data_bbin[$k]);
                }
            }

            @error_log('--------------- 数据统计完成，共'.count($day_data).'条-------'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

            // 5， 剔除加入时时返水的会员，时时返水会员不能参加天天返水
            // 天天返水时，生成返水报表将时时返水的会员剔除
            $mysql = "select username from ".DBPREFIX."rebate_hour_users";
            $result = mysqli_query($dbLink, $mysql);
            $count = mysqli_num_rows($result);
            if ($count>0){
                while ($row = mysqli_fetch_assoc($result)){
                    echo '剔除加入时时返水的会员--------------------------'.$row['username'].'已剔除';
                    unset($day_data[$row['username']]);
                }
            }


            // 检查不返水分层是否开启 status 1 开启 0 关闭
            // 6， 剔除不返水分层的会员，加入不返水分层的会员不能参加返水 layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
            $layerId=1;
            $layer = getUserLayerById($layerId);
            if ($layer['status']==1){
                $mysql = "select ID,UserName,layer from " . DBPREFIX.MEMBERTABLE." WHERE layer = 1";
                $result = mysqli_query($dbLink, $mysql);
                $count = mysqli_num_rows($result);
                if ($count>0){
                    while ($row = mysqli_fetch_assoc($result)){
                        echo '剔除不返水分层会员--------------------------'.$row['UserName'].'已剔除';
                        unset($day_data[$row['UserName']]);
                    }
                }
            }

            if (count($day_data)>0){
                @error_log('--------------- 返水报表准备入库'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');

                // 每一天多个会员的统计数据入库
                foreach ($day_data as $k => $v){
                    if($v['R_total'] > 0){
                        $strKeys = join(',', array_keys($v));
                        $strValues = join("','", array_values($v));
                        $sql = "REPLACE INTO ".DBPREFIX."rebate_history_report (" . $strKeys . ") VALUES ('" . $strValues . "') ";
                        //@error_log('每一天多个会员的统计数据入库:'.$sql.PHP_EOL, 3, '/tmp/aaa.log');
                        //print_r($sql);exit;
                        echo '--------------------------';
                        $result = mysqli_query($conn, $sql);
                        if (!isset($result) || $result <= 0) {
                            $result=mysqli_query($conn, "ROLLBACK");
                            die('计算报表数据失败！ ' . mysqli_error($conn));
                        }
                    }
                }
                @error_log('--------------- 返水报表入库成功'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');
            }

            if($reGeneral) {
				$sql = "insert into ".DBPREFIX."rebate_history_report_flag(rebate_date, flag) value('".date("Y-m-d",$StartTime)."', 2) ";
			}else {
				$sql = "insert into ".DBPREFIX."rebate_history_report_flag(rebate_date, flag) value('".date("Y-m-d",$StartTime)."', 1) ";
			}
            $result=mysqli_query($conn, $sql);
			if(!$result) {
				$result=mysqli_query($conn, "ROLLBACK");
			    echo('插入计算成功表示符失败！' . mysqli_error($conn));
			    continue;
			}else {
				$result=mysqli_query($conn, "COMMIT");
			}

		}else {
			echo date("Y-m-d", $StartTime-86400)."所有的计算完成！\n";
			break;
		}
        $StartTime = $end_time;
	}

    @error_log(date("Y-m-d H:i:s").'--------------- 生成返水报表结束'.PHP_EOL, 3, '/tmp/group/rebate_daily_report_general.log');
}
