<?php
/*	
 *
 * 	时时返水【只返给已添加时时返水的用户名】
 *  0， 禁止重复执行
 * 	1，	只支持cli模式下的运行。
 * 	2，	示例URL:
 *	 			php rebate_hour_execute.php 									//定时任务，生成昨日的数据
 *			或	
 *	 			php rebate_hour_execute.php old								//重新批量生成数据
 *			或
 *	 			php rebate_hour_execute.php 2016-10-06						//重新生成某一个天的数据
 *			或
 *	 			php rebate_hour_execute.php 2016-10-06 2016-10-07 1	//重新生成从某一个天到某一天的数据
 * 	3，	开启时间，每个小时的某个点执行计划任务
 *  4， 执行注单的统计脚本完成后，再统计返水数据
 *  5， 剔除不返水分层的会员，加入不返水分层的会员不能参加返水 layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
 *  6， 执行返水操作
 *
 *	auth: lincoin
 *	2019-12-10
 * */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";

$sAg_prefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_'; // AG用户名前缀 BT5A_，返水需要转为体育的用户名

//只在CLI命令下有效
if (php_sapi_name() == "cli") {

    //每日生成昨日的报表数据
    $start_time = strtotime(date("Y-m-d H:00:00",strtotime("-1 hour")));
    $stop_time = strtotime(date("Y-m-d H:00:00"));

//    echo $start_time.'========'.$stop_time; die;

    countall($start_time,$stop_time);

}

/**
 *
 * 根据条件，统计数据（体育、真人、彩票、开元棋牌、皇冠棋牌、VG棋牌、乐游棋牌、MG电子、泛亚电竞、雷火电竞、OG视讯、MW电子、CQ9电子、FG电子、BBIN电子、快乐棋牌），进行时时返水
 *
 * @param date $StartTime
 * @param date $stop_time
 */
function countall($start_time, $stop_time){
    global $dbMasterLink, $dbLink, $og_prefix, $mw_prefix, $sAg_prefix, $bbin_prefix;

    $StartTime = date('Y-m-d H:i:s', $start_time);
    $BjStartTime = date('Y-m-d H:i:s', $start_time+12*60*60);
    $EndTime = date('Y-m-d H:i:s', $stop_time);
    $BjEndTime = date('Y-m-d H:i:s', $stop_time+12*60*60);


    $sql = " select * from ".DBPREFIX."rebate_hour_hour_report_flag where rebate_date_hour = '{$StartTime}' ";
    $result=mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_array($result);
    //已经生成
    if(isset($row['flag']) && $row['flag'] == 1) {
        echo($StartTime.'时时返水已派发。不能重复返水 ');
        exit;
    }

    // 声明变量
    $data_rebate_hg = $data_rebate_ag = $data_rebate_ky = $data_rebate_hgqp = $data_rebate_vgqp = $data_rebate_lyqp = $data_rebate_klqp = $data_rebate_mg = $data_rebate_avia = $data_rebate_og = $data_rebate_cq =
        $data_rebate_mw = $data_rebate_fg = $data_rebate_bbin = [];
    $data_hg = $data_ag = $data_ag_dianzi = $data_ag_dayu = $data_ky = $data_hgqp = $data_vgqp = $data_vgqp = $data_lyqp = $data_klqp = $data_mg = $data_avia = $data_fire = $data_og = $data_cq = $data_mw = $data_fg = $data_bbin = [];
    // 体育返水点数
    $result_rebate= mysqli_query($dbLink, "select * from ".DBPREFIX."rebate_game_hour_settings");
    $cou = mysqli_num_rows($result_rebate);
    if ($cou>0){
        while ($row = mysqli_fetch_assoc($result_rebate)){
            switch ($row['game_type']){
                case 1: $data_rebate_hg[] = $row; break;
                case 2: $data_rebate_ag['zrsx'][] = $row; break;// AG视讯返水点数
                case 5: $data_rebate_ag['dz'][] = $row; break;// AG电子返水点数
                case 6: $data_rebate_ag['byw'][] = $row; break;// AG捕鱼王返水点数
                case 4: $data_rebate_ky[] = $row; break;// 开元棋牌返水点数
                case 7: $data_rebate_hgqp[] = $row; break;// 皇冠棋牌返水点数
                case 8: $data_rebate_vgqp[] = $row; break;// VG棋牌返水点数
                case 9: $data_rebate_lyqp[] = $row; break;// 乐游棋牌返水点数
                case 10: $data_rebate_mg[] = $row; break;// MG电子返水点数
                case 11: $data_rebate_avia[] = $row; break;// 泛亚电竞返水点数
                case 12: $data_rebate_og[] = $row; break;// OG视讯返水点数
                case 13: $data_rebate_cq[] = $row; break;// CQ9电子返水点数
                case 14: $data_rebate_mw[] = $row; break;// MW电子返水点数
                case 15: $data_rebate_fg[] = $row; break;// FG电子返水点数
                case 16: $data_rebate_bbin[] = $row; break;// BBIN视讯返水点数
                case 17: $data_rebate_klqp[] = $row; break;// 快乐棋牌返水点数
                case 18: $data_rebate_fire[] = $row; break;// 雷火返水点数
                default: break;
            }
        }
    }

    @error_log(date("Y-m-d H:i:s").PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');
    @error_log('--------------- 时时返水'.$StartTime.'开始'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 全部捞出，然后根据（游戏类别、用户名、日期）将数据归类
    $sql = "select Userid as userid, M_Name as username from ".DBPREFIX."web_report_data 
            where sendAwardTime between '".$StartTime."' and '".$EndTime."' and testflag=0 and `Cancel`=0 
            group by Userid";
    $result=mysqli_query($dbLink, $sql);
    if(!$result) {
        die('计算报表数据失败11！ ' . mysqli_error($dbLink));
    }
    $cou = mysqli_num_rows($result);
    if ($cou>0){

        while ($row = mysqli_fetch_assoc($result)){
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
        foreach ($data as $k => $v){
            $username=$aUserNameForHgRebateId[$v['userid']]['UserName'];
            $data[$k]['username'] = $username;
            $data_hg[$username] = $data[$k];
        }
        // valid_money 有效下注总额（用户，分类）
        $sql = "select Userid as userid, M_Name as username, BetType_en, M_Rate, VGOLD, Active as game_code from ".DBPREFIX."web_report_data 
                where sendAwardTime between '".$StartTime."' and '".$EndTime."' and checked = 1 and testflag=0 and `Cancel`=0";
        $result=mysqli_query($dbLink, $sql);
        if(!$result) {
            die('计算报表数据失败33！ ' . mysqli_error($dbLink));
        }
        $cou = mysqli_num_rows($result);
        if ($cou>0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $username=$aUserNameForHgRebateId[$row['userid']]['UserName'];
                if($row['BetType_en'] == '1x2' or $row['BetType_en'] == 'Odd/Even' or $row['BetType_en']=='1st Half 1x2' or
                    $row['BetType_en'] =='Running 1x2' or $row['BetType_en']=='1st Half Running 1x2'){

                    if ($row['M_Rate']>=1.5){ // 单双、独赢、半场独赢，赔率小于1.5的不算入有效投注
                        $data_hg[$username]['total']+=$row['VGOLD'];
                        $data_hg[$username]['count_pay']+=1;
                    }
                }else{
                    if ($row['M_Rate']>=0.5){ // 0.5以下的不算入有效投注
                        $data_hg[$username]['total']+=$row['VGOLD'];
                        $data_hg[$username]['count_pay']+=1;
                    }
                }
            }
        }else{
            @error_log("计算返水有效投注金额:0 ".PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');
        }

        // 按照用户名、游戏类别 归类下注金额、有效返水投注金额
        foreach ($data_hg as $k => $v){
            // 计算返水
            foreach ($data_rebate_hg as $k1 => $v1){
                if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                    $data_hg[$k]['R_total'] = $data_hg[$k]['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_hg[$k]['R_total'] <= 0){
                unset($data_hg[$k]);
            }
        }
    }

    @error_log('--------------- 体育数据已捞出，共'.count($data_hg).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // AG真人视讯数据
    $sql = "select userid, username, `type` as game_code, sum(1) as count_pay, sum(valid_money) as total from ".DBPREFIX."ag_projects 
            where bettime>='".$StartTime."' and bettime<'".$EndTime."' and `type`='BR' 
            group by userid";
    $result_data_ag = mysqli_query($dbLink,$sql);
    $cou_ag=mysqli_num_rows($result_data_ag);
    if ($cou_ag>0){
        $data_ag=[];
        while ($row = mysqli_fetch_assoc($result_data_ag)){
            $row['username'] = explode($sAg_prefix, $row['username'])[1];
            $data_ag[$row['username']]=$row;
        }
        foreach ($data_ag as $k =>$v){
            // 计算返水
            foreach ($data_rebate_ag['zrsx'] as $k1 => $v1){
                if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                    $data_ag[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_ag[$k]['R_total']<=0){
                unset($data_ag[$k]);
            }
        }
    }

    @error_log('--------------- AG真人视讯数据已捞出，共'.count($data_ag).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // AG电子数据
    $sql = "select userid, username, `type` as game_code, sum(1) as count_pay, sum(valid_money) as total 
            from ".DBPREFIX."ag_projects 
            where bettime>='".$StartTime."' and bettime<'".$EndTime."' and (`type`='' or `type`='SLOT') 
            group by userid";
    $result_data_ag_dianzi = mysqli_query($dbLink,$sql);
    $cou_ag_dianzi=mysqli_num_rows($result_data_ag_dianzi);
    if ($cou_ag_dianzi>0){
        while ($row = mysqli_fetch_assoc($result_data_ag_dianzi)){
            $row['username'] = explode($sAg_prefix, $row['username'])[1];
            $data_ag_dianzi[$row['username']]=$row;
        }
        foreach ($data_ag_dianzi as $k =>$v){
            // 计算返水
            foreach ($data_rebate_ag['dz'] as $k1 => $v1){
                if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                    $data_ag_dianzi[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_ag_dianzi[$k]['R_total']<=0){
                unset($data_ag_dianzi[$k]);
            }
        }
    }

    @error_log('--------------- AG电子数据已捞出，共'.count($data_ag_dianzi).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // AG捕鱼王打鱼数据（以场景为单位）
    $result_data_ag_dayu = mysqli_query($dbLink,"select userid, UserName as username, sum(BulletOutNum) as count_pay, sum(Cost) as total  from ".DBPREFIX."ag_buyu_scene where EndTime BETWEEN '".$StartTime."' and '".$EndTime."' group by userid ");
    $cou_ag_dayu=mysqli_num_rows($result_data_ag_dayu);
    if ($cou_ag_dayu>0){

        while ($row = mysqli_fetch_assoc($result_data_ag_dayu)){
            $row['username'] = explode($sAg_prefix, $row['username'])[1];
            $data_ag_dayu[$row['username']]=$row;
        }
        foreach ($data_ag_dayu as $k =>$v){
            // 计算返水
            foreach ($data_rebate_ag['byw'] as $k1 => $v1){
                if ( ($v['total'] >= $v1['left_interval']) && ($v['total'] < $v1['right_interval']) ){
                    $data_ag_dayu[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_ag_dayu[$k]['R_total']<=0){
                unset($data_ag_dayu[$k]);
            }
        }
    }

    @error_log('--------------- AG捕鱼王打鱼数据已捞出，共'.count($data_ag_dayu).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 开元棋牌数据
    $ky_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`cellscore`) AS `total`
            FROM " . DBPREFIX . "ky_projects 
            WHERE `game_endtime` >= '{$StartTime}' AND `game_endtime` < '{$EndTime}' 
            GROUP BY `userid` ASC";
    $result_data_ky = mysqli_query($dbLink, $ky_sql);
    $cou_ky=mysqli_num_rows($result_data_ky);
    if ($cou_ky>0){
        $data_ky=[];
        while ($row = mysqli_fetch_assoc($result_data_ky)){
            $data_ky[$row['username']]=$row;
        }
        foreach ($data_ky as $k =>$v){
            // 计算返水
            foreach ($data_rebate_ky as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_ky[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_ky[$k]['R_total'] <= 0){
                unset($data_ky[$k]);
            }
        }
    }

    @error_log('--------------- 开元棋牌数据已捞出，共'.count($data_ky).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 皇冠棋牌数据
    $hgqp_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`valid_bet`) AS `total`
                FROM " . DBPREFIX . "ff_projects 
                WHERE `game_endtime` >= '{$StartTime}' AND `game_endtime` < '{$EndTime}' 
                GROUP BY `userid`";
    $result_data_hgqp = mysqli_query($dbLink, $hgqp_sql);
    $cou_hgqp=mysqli_num_rows($result_data_hgqp);
    if ($cou_hgqp>0){
        $data_hgqp=[];
        while ($row = mysqli_fetch_assoc($result_data_hgqp)){
            $data_hgqp[$row['username']]=$row;
        }
        foreach ($data_hgqp as $k =>$v){
            // 计算返水
            foreach ($data_rebate_hgqp as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_hgqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_hgqp[$k]['R_total'] <= 0){
                unset($data_hgqp[$k]);
            }
        }
    }

    @error_log('--------------- 皇冠棋牌数据已捞出，共'.count($data_hgqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // VG棋牌数据
    $vgqp_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`validbetamount`) AS `total`
            FROM " . DBPREFIX . "vg_projects 
            WHERE `game_endtime` >= '{$StartTime}' AND `game_endtime` < '{$EndTime}' 
            GROUP BY `userid`";
    $result_data_vgqp = mysqli_query($dbLink, $vgqp_sql);
    $cou_vgqp=mysqli_num_rows($result_data_vgqp);
    if ($cou_vgqp>0){
        $data_vgqp=[];
        while ($row = mysqli_fetch_assoc($result_data_vgqp)){
            $data_vgqp[$row['username']]=$row;
        }
        foreach ($data_vgqp as $k =>$v){
            // 计算返水
            foreach ($data_rebate_vgqp as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_vgqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if($data_vgqp[$k]['R_total'] <= 0){
                unset($data_vgqp[$k]);
            }
        }
    }

    @error_log('--------------- VG棋牌数据已捞出，共'.count($data_vgqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 乐游棋牌数据 total_bet 投注，total_cellscore 有效投注
    $lyqp_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`cellscore`) AS `total` 
            FROM " . DBPREFIX . "ly_projects 
            WHERE `game_endtime` >= '{$StartTime}' AND `game_endtime` < '{$EndTime}' 
            GROUP BY `userid`";
    $result_data_lyqp = mysqli_query($dbLink, $lyqp_sql);
    $cou_lyqp=mysqli_num_rows($result_data_lyqp);
    if ($cou_lyqp>0){
        $data_lyqp=[];
        while ($row = mysqli_fetch_assoc($result_data_lyqp)){
            $data_lyqp[$row['username']]=$row;
        }
        foreach ($data_lyqp as $k =>$v){
            // 计算返水
            foreach ($data_rebate_lyqp as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_lyqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_lyqp[$k]['R_total'] <= 0){
                unset($data_lyqp[$k]);
            }
        }
    }

    @error_log('--------------- 乐游棋牌数据已捞出，共'.count($data_lyqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 快乐棋牌数据 total_bet 投注，total_cellscore 有效投注
    $klqp_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`amount`) AS `total` 
            FROM " . DBPREFIX . "kl_projects 
            WHERE `gametime` >= '{$StartTime}' AND `gametime` < '{$EndTime}' 
            GROUP BY `userid`";
    $result_data_klqp = mysqli_query($dbLink, $klqp_sql);
    $cou_klqp=mysqli_num_rows($result_data_klqp);
    if ($cou_klqp>0){
        $data_klqp=[];
        while ($row = mysqli_fetch_assoc($result_data_klqp)){
            $data_klqp[$row['username']]=$row;
        }
        foreach ($data_klqp as $k =>$v){
            // 计算返水
            foreach ($data_rebate_klqp as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_klqp[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_klqp[$k]['R_total'] <= 0){
                unset($data_klqp[$k]);
            }
        }
    }

    @error_log('--------------- 快乐棋牌数据已捞出，共'.count($data_klqp).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // MG电子数据 total_bet 投注，total_cellscore 有效投注
    $mg_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`amount`) AS `total` 
            FROM " . DBPREFIX . "mg_projects 
            WHERE `transaction_time` >= '{$StartTime}' AND `transaction_time` < '{$EndTime}' AND category='WAGER'
            GROUP BY `userid`";
    $result_data_mg = mysqli_query($dbLink, $mg_sql);
    $cou_mg=mysqli_num_rows($result_data_mg);
    if ($cou_mg>0){
        $data_mg=[];
        while ($row = mysqli_fetch_assoc($result_data_mg)){
            $row['username'] = explode('_', $row['username'],2)[1];
            $data_mg[$row['username']]=$row;
        }
        foreach ($data_mg as $k =>$v){
            // 计算返水
            foreach ($data_rebate_mg as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_mg[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_mg[$k]['R_total'] <= 0){
                unset($data_mg[$k]);
            }
        }
    }

    @error_log('--------------- MG电子数据已捞出，共'.count($data_mg).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 泛亚电竞数据 total_bet 投注，total_cellscore 有效投注
    $avia_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`betMoney`) AS `total` 
            FROM " . DBPREFIX . "avia_projects 
            WHERE `rewardAt` >= '{$StartTime}' AND `rewardAt` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_avia = mysqli_query($dbLink, $avia_sql);
    $cou_avia=mysqli_num_rows($result_data_avia);
    if ($cou_avia>0){
        $data_avia=[];
        while ($row = mysqli_fetch_assoc($result_data_avia)){
            $data_avia[$row['username']]=$row;
        }
        foreach ($data_avia as $k =>$v){
            // 计算返水
            foreach ($data_rebate_avia as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_avia[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_avia[$k]['R_total'] <= 0){
                unset($data_avia[$k]);
            }
        }
    }

    @error_log('--------------- 泛亚电竞数据已捞出，共'.count($data_avia).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // 雷火电竞数据 total_bet 投注，total_cellscore 有效投注
    $fire_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`amount`) AS `total` 
            FROM " . DBPREFIX . "fire_projects 
            WHERE `settlement_datetime` >= '{$StartTime}' AND `settlement_datetime` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_fire = mysqli_query($dbLink, $fire_sql);
    $cou_fire=mysqli_num_rows($result_data_fire);
    if ($cou_fire>0){
        $data_fire=[];
        while ($row = mysqli_fetch_assoc($result_data_fire)){
            $data_fire[$row['username']]=$row;
        }
        foreach ($data_fire as $k =>$v){
            // 计算返水
            foreach ($data_rebate_fire as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_fire[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_fire[$k]['R_total'] <= 0){
                unset($data_fire[$k]);
            }
        }
    }

    @error_log('--------------- 雷火电竞数据已捞出，共'.count($data_fire).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');


    // OG视讯数据 total_bet 投注，total_cellscore 有效投注
    $og_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`validbet`) AS `total`
            FROM " . DBPREFIX . "og_projects 
            WHERE `md_bettingdate` >= '{$StartTime}' AND `md_bettingdate` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_og = mysqli_query($dbLink, $og_sql);
    $cou_og=mysqli_num_rows($result_data_og);
    if ($cou_og>0){

        while ($row = mysqli_fetch_assoc($result_data_og)){
            $data_og_tmp[]=$row;
        }
        // 根据userid，捞出会员表的用户名使用，防止生成多条返水记录-----------20191229 lincoin
        $sOgUserid = implode(',',array_column($data_og_tmp, 'userid'));
        $result_og_user =  mysqli_query($dbLink, "select ID,UserName from ".DBPREFIX."web_member_data WHERE ID in ({$sOgUserid})");
        while ($row = mysqli_fetch_assoc($result_og_user)){
            $aUserNameForOGRebate[] = $row;
        }
        foreach ($aUserNameForOGRebate as $k => $v){
            $aUserNameForOGRebateId[$v['ID']] = $v;
        }
        foreach ($data_og_tmp as $k => $v){
            $username = $aUserNameForOGRebateId[$v['userid']]['UserName'];
            $data_og_tmp[$k]['username']=$username;
            $data_og[$username] = $data_og_tmp[$k];
        }
        foreach ($data_og as $k =>$v){
            // 计算返水
            foreach ($data_rebate_og as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_og[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_og[$k]['R_total'] <= 0){
                unset($data_og[$k]);
            }
        }
    }

    @error_log('--------------- OG视讯数据已捞出，共'.count($data_og).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // CQ9电子数据 total_bet 投注，total_cellscore 有效投注
    $cq_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`bet`) AS `total`
            FROM " . DBPREFIX . "cq9_projects 
            WHERE `endroundtime` >= '{$StartTime}' AND `bettime` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_cq = mysqli_query($dbLink, $cq_sql);
    $cou_cq=mysqli_num_rows($result_data_cq);
    if ($cou_cq>0){
        $data_cq=[];
        while ($row = mysqli_fetch_assoc($result_data_cq)){
            $data_cq[$row['username']]=$row;
        }
        foreach ($data_cq as $k =>$v){
            // 计算返水
            foreach ($data_rebate_cq as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_cq[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_cq[$k]['R_total'] <= 0){
                unset($data_cq[$k]);
            }
        }
    }

    @error_log('--------------- CQ9电子数据已捞出，共'.count($data_cq).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // MW电子数据 total_bet 投注，total_cellscore 有效投注
    $mw_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`playMoney`) AS `total`
            FROM " . DBPREFIX . "mw_projects 
            WHERE `logDate` >= '{$BjStartTime}' AND `logDate` < '{$BjEndTime}' 
            GROUP BY `username`";
    $result_data_mw = mysqli_query($dbLink, $mw_sql);
    $cou_mw=mysqli_num_rows($result_data_mw);
    if ($cou_mw>0){
        $data_mw=[];
        while ($row = mysqli_fetch_assoc($result_data_mw)){
            $row['username'] = explode('_', $row['username'],2)[1];
            $data_mw[$row['username']]=$row;
        }
        foreach ($data_mw as $k =>$v){
            // 计算返水
            foreach ($data_rebate_mw as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_mw[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_mw[$k]['R_total'] <= 0){
                unset($data_mw[$k]);
            }
        }
    }

    @error_log('--------------- MW电子数据已捞出，共'.count($data_mw).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // FG电子数据 total_bet 投注，total_cellscore 有效投注
    $fg_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`all_bets`) AS `total`
            FROM " . DBPREFIX . "fg_projects 
            WHERE `endtime` >= '{$StartTime}' AND `endtime` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_fg = mysqli_query($dbLink, $fg_sql);
    $cou_fg=mysqli_num_rows($result_data_fg);
    if ($cou_fg>0){
        $data_fg=[];
        while ($row = mysqli_fetch_assoc($result_data_fg)){
            $row['username'] = explode('_', $row['username'],2)[1];
            $data_fg[$row['username']]=$row;
        }
        foreach ($data_fg as $k =>$v){
            // 计算返水
            foreach ($data_rebate_fg as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_fg[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_fg[$k]['R_total'] <= 0){
                unset($data_fg[$k]);
            }
        }
    }

    @error_log('--------------- FG电子数据已捞出，共'.count($data_fg).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    // BBIN视讯数据 total_bet 投注，total_cellscore 有效投注
    $bbin_sql = "SELECT `userid`, `username`, SUM(1) AS `count_pay`, SUM(`BetAmount`) AS `total`
            FROM " . DBPREFIX . "jx_bbin_projects 
            WHERE `WagersDate` >= '{$StartTime}' AND `WagersDate` < '{$EndTime}'
            GROUP BY `userid`";
    $result_data_bbin = mysqli_query($dbLink, $bbin_sql);
    $cou_bbin=mysqli_num_rows($result_data_bbin);

    if ($cou_bbin>0){

        while ($row = mysqli_fetch_assoc($result_data_bbin)){
            // bbin用户名转为HG用户名，去掉前缀
//            $row['username'] = strtolower(substr($row['username'], strlen(strtoupper($bbin_prefix))));
            $data_bbin_tmp[]=$row;
        }
        // 根据userid，捞出会员表的用户名使用，防止生成多条返水记录-----------20191229 lincoin
        $sBbinUserid = implode(',',array_column($data_bbin_tmp, 'userid'));
        $result_bbin_user =  mysqli_query($dbLink, "select ID,UserName from ".DBPREFIX."web_member_data WHERE ID in ({$sBbinUserid})");
        while ($row = mysqli_fetch_assoc($result_bbin_user)){
            $aUserNameForBbinRebate[] = $row;
        }
        foreach ($aUserNameForBbinRebate as $k => $v){
            $aUserNameForBbinRebateId[$v['ID']] = $v;
        }
        foreach ($data_bbin_tmp as $k => $v){
            $username = $aUserNameForBbinRebateId[$v['userid']]['UserName'];
            $data_bbin_tmp[$k]['username'] = $username;
            $data_bbin[$username] = $data_bbin_tmp[$k];
        }
        foreach ($data_bbin as $k =>$v){
            // 计算返水
            foreach ($data_rebate_bbin as $k1 => $v1){
                if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
                    $data_bbin[$k]['R_total'] = $v['total'] * $v1['rebate'];
                    break;
                }
            }
            if ($data_bbin[$k]['R_total'] <= 0){
                unset($data_bbin[$k]);
            }
        }
    }

    @error_log('--------------- BBIN视讯数据已捞出，共'.count($data_fg).'条'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');




    // 将当天的数据按照游戏类别统计，分别从各个游戏数组中捞取
    // 统计体育会员
    $hour_data = [];
    if (count($data_hg)>0){
        foreach ($data_hg as $k => $v) {
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_ag[$k]['count_pay'] + $data_ag_dianzi[$k]['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_ag[$k]['total'] + $data_ag_dianzi[$k]['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_hg'] = $v['total'];
            $hour_data[$k]['total_ag'] = $data_ag[$k]['total'];
            $hour_data[$k]['total_ag_dianzi'] = $data_ag_dianzi[$k]['total'];
            $hour_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
            $hour_data[$k]['total_ky'] = $data_ky[$k]['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_ag[$k]['R_total'] + $data_ag_dianzi[$k]['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_hg'] = $v['R_total'];
            $hour_data[$k]['R_total_ag'] = $data_ag[$k]['R_total'];
            $hour_data[$k]['R_total_ag_dianzi'] = $data_ag_dianzi[$k]['R_total'];
            $hour_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
            $hour_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_hg[$k]);
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_ag_dianzi[$k]['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_ag_dianzi[$k]['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_ag'] = $v['total'];
            $hour_data[$k]['total_ag_dianzi'] = $data_ag_dianzi[$k]['total'];
            $hour_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
            $hour_data[$k]['total_ky'] = $data_ky[$k]['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_ag_dianzi[$k]['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_ag'] = $v['R_total'];
            $hour_data[$k]['R_total_ag_dianzi'] = $data_ag_dianzi[$k]['R_total'];
            $hour_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
            $hour_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_ag_dayu[$k]['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_ag_dayu[$k]['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_ag_dianzi'] = $v['total'];
            $hour_data[$k]['total_ag_dayu'] = $data_ag_dayu[$k]['total'];
            $hour_data[$k]['total_ky'] = $data_ky[$k]['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_ag_dayu[$k]['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_ag_dianzi'] = $v['R_total'];
            $hour_data[$k]['R_total_ag_dayu'] = $data_ag_dayu[$k]['R_total'];
            $hour_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_ky[$k]['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_ky[$k]['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_ag_dayu'] = $v['total'];
            $hour_data[$k]['total_ky'] = $data_ky[$k]['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_ky[$k]['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_ag_dayu'] = $v['R_total'];
            $hour_data[$k]['R_total_ky'] = $data_ky[$k]['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_hgqp[$k]['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_hgqp[$k]['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_ky'] = $v['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_hgqp[$k]['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_ky'] = $v['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_vgqp[$k]['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_vgqp[$k]['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_hgqp'] = $data_hgqp[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $data_vgqp[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_vgqp[$k]['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_hgqp'] = $data_hgqp[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $data_vgqp[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_lyqp[$k]['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_lyqp[$k]['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_vgqp'] = $v['total'];
            $hour_data[$k]['total_lyqp'] = $data_lyqp[$k]['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_lyqp[$k]['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_vgqp'] = $v['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $data_lyqp[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay']+ $data_klqp[$k]['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total']+ $data_klqp[$k]['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_lyqp'] = $v['total'];
            $hour_data[$k]['total_klqp'] = $data_klqp[$k]['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total']+ $data_klqp[$k]['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_lyqp'] = $v['R_total'];
            $hour_data[$k]['R_total_klqp'] = $data_klqp[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_lyqp[$k]);
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_mg[$k]['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_mg[$k]['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_klqp'] = $v['total'];
            $hour_data[$k]['total_mg'] = $data_mg[$k]['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_mg[$k]['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total'] + $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_klqp'] = $v['R_total'];
            $hour_data[$k]['R_total_mg'] = $data_mg[$k]['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_avia[$k]['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_avia[$k]['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_mg'] = $v['total'];
            $hour_data[$k]['total_avia'] = $data_avia[$k]['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_avia[$k]['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total'] + $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_mg'] = $v['R_total'];
            $hour_data[$k]['R_total_avia'] = $data_avia[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_fire[$k]['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_fire[$k]['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_avia'] = $v['total'];
            $hour_data[$k]['total_fire'] = $data_fire[$k]['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'];
            $hour_data[$k]['R_total_avia'] = $v['R_total'] + $data_fire[$k]['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_fire'] = $data_fire[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_og[$k]['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_og[$k]['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_fire'] = $v['total'];
            $hour_data[$k]['total_og'] = $data_og[$k]['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'];
            $hour_data[$k]['R_total_fire'] = $v['R_total'] + $data_og[$k]['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $data_og[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_mw[$k]['count_pay'] + $data_cq[$k]['count_pay'] + $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total'] + $data_mw[$k]['total'] + $data_cq[$k]['total'] + $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_og'] = $v['total'];
            $hour_data[$k]['total_mw'] = $data_mw[$k]['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total'] + $data_mw[$k]['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_og'] = $v['R_total'];
            $hour_data[$k]['R_total_mw'] = $data_mw[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
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
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'] + $data_cq[$k]['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total']+ $data_cq[$k]['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_mw'] = $v['total'];
            $hour_data[$k]['total_cq'] = $data_cq[$k]['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total']+ $data_cq[$k]['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_mw'] = $v['R_total'];
            $hour_data[$k]['R_total_cq'] = $data_cq[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_mw[$k]);
            unset($data_cq[$k]);
            unset($data_fg[$k]);
            unset($data_bbin[$k]);
        }
    }

    // 统计CQ9电子会员
    if (count($data_cq)>0){
        foreach ($data_cq as $k => $v){
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay']+ $data_fg[$k]['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total']+ $data_fg[$k]['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_cq'] = $v['total'];
            $hour_data[$k]['total_fg'] = $data_fg[$k]['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total']+ $data_fg[$k]['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_cq'] = $v['R_total'];
            $hour_data[$k]['R_total_fg'] = $data_fg[$k]['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_cq[$k]);
            unset($data_fg[$k]);
            unset($data_bbin[$k]);
        }
    }

    // 统计FG电子会员
    if (count($data_fg)>0){
        foreach ($data_fg as $k => $v){
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay']+ $data_bbin[$k]['count_pay'];
            $hour_data[$k]['total'] = $v['total']+ $data_bbin[$k]['total'];
            $hour_data[$k]['total_fg'] = $v['total'];
            $hour_data[$k]['total_bbin'] = $data_bbin[$k]['total'];
            $hour_data[$k]['R_total'] = $v['R_total']+ $data_bbin[$k]['R_total'];
            $hour_data[$k]['R_total_fg'] = $v['R_total'];
            $hour_data[$k]['R_total_bbin'] = $data_bbin[$k]['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_fg[$k]);
            unset($data_bbin[$k]);
        }
    }

    // 统计BBIN视讯会员
    if (count($data_bbin)>0){
        foreach ($data_bbin as $k => $v){
            $hour_data[$k]['userid'] = $v['userid'];
            $hour_data[$k]['username'] = $v['username'];
            $hour_data[$k]['R_date_hour'] = $StartTime;
            $hour_data[$k]['count_pay'] = $v['count_pay'];
            $hour_data[$k]['total'] = $v['total'];
            $hour_data[$k]['total_bbin'] = $v['total'];
            $hour_data[$k]['R_total'] = $v['R_total'];
            $hour_data[$k]['R_total_bbin'] = $v['R_total'];
            $hour_data[$k]['operation_time'] = date('Y-m-d H:i:s');
            unset($data_bbin[$k]);
        }
    }

    @error_log('--------------- 数据统计完成，共'.count($hour_data).'条-------'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');


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
                unset($hour_data[$row['UserName']]);
            }
        }
    }

    @error_log('--------------- 检查用户名是否加入时时返水'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

    if (count($hour_data)>0){
        $aUserName = array_column($hour_data, 'username');
        $sUserName = "'".implode("','",$aUserName)."'";
        $sql = "SELECT `username` FROM " . DBPREFIX . "rebate_hour_users where username in ($sUserName)";
        $result = mysqli_query($dbLink, $sql);
        $cou=mysqli_num_rows($result);
        if ($cou>0){
            $aHourRebateUser=$hour_user_data=[];
            while ($row = mysqli_fetch_assoc($result)){
                $aHourRebateUser[]=$row;
                $hour_user_data[$row['username']] = $hour_data[$row['username']];
                $hour_user_data[$row['username']]['status'] = 1;
            }

        }else{
            $result=mysqli_query($dbMasterLink, "ROLLBACK");
            @error_log('--------------- 时时返水退出，用户名未加入时时返水'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');
            exit("时时返水退出，用户名未加入时时返水");
        }
    }
    else{
        exit("没有码量，时时返水退出");
    }

    if (count($hour_user_data)>0){
        @error_log('--------------- 时时返水报表准备入库'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');

        $result=mysqli_query($dbMasterLink, "START TRANSACTION");
        if (!$result) {
            echo('事务开启失败！ ' . mysqli_error($dbMasterLink));
            exit;
        }

        $data=[];
        // 每一天多个会员的统计数据入库
        foreach ($hour_user_data as $k => $v){
            if($v['R_total'] > 0){

                // 添加资金锁
                $lockMoney = mysqli_query($dbMasterLink, "select ID,test_flag, Agents, World, Corprator, Super, Admin,Alias,Phone,Money from " . DBPREFIX.MEMBERTABLE." WHERE ID = '{$v['userid']}' for update ");
                if ($lockMoney){
                    $aUser = mysqli_fetch_assoc($lockMoney);
                    $moneyf = $aUser['Money'];
                    $currency_after = $aUser['Money'] + $v['R_total'];
                    // 更新会员资金
                    $result = mysqli_query($dbMasterLink, "update " . DBPREFIX.MEMBERTABLE." set Money=Money + " . $v['R_total'] . " where ID = '" . $v['userid'] . "' ");
                    if ($result) {
                        $oDatetime = new DateTime('NOW');
                        $sTime8 = dechex($oDatetime->format('U')); // 8bit
                        $sUser6 = sprintf("%06s", substr(dechex($aUser['ID']), 0, 6)); // 6bit
                        $sTrans_no = 'HOURREBATE' . $sTime8 . $sUser6; //订单号生成规则

                        $data['userid'] = $v['userid'];
                        $data['Checked'] = 1;
                        $data['Payway'] = 'hourhourRebate'; // Rebate
                        $data['reason'] = '时时返水';
                        $data['AuditDate'] = date("Y-m-d H:i:s");
                        $data['Gold'] = $v['R_total'];
                        $data['moneyf'] = $moneyf;
                        $data['currency_after'] = $currency_after;
                        $data['AddDate'] = date("Y-m-d", time());
                        $data['Type'] = 'R';
                        $data['UserName'] = $v['username'];
                        $data['Agents'] = $aUser['Agents'];
                        $data['World'] = $aUser['World'];
                        $data['Corprator'] = $aUser['Corprator'];
                        $data['Super'] = $aUser['Super'];
                        $data['Admin'] = $aUser['Admin'];
                        $data['CurType'] = 'RMB';
                        $data['Date'] = date("Y-m-d H:i:s");
                        $data['Name'] = $aUser['Alias'];
                        $data['Waterno'] = '';
                        $data['Phone'] = $aUser['Phone'];
                        $data['Notes'] = '时时返水';
                        $data['test_flag'] = $aUser['test_flag'];
                        $data['Order_Code'] = $sTrans_no;

                        $sInsData = '';
                        foreach ($data as $key => $value) {
                            if ($key == 'Order_Code') {
                                $sInsData .= "`$key` = '{$value}'";
                            } else {
                                $sInsData .= "`$key` = '{$value}',";
                            }
                        }
                        // 插入返水记录
                        $in = mysqli_query($dbMasterLink, "insert into `" . DBPREFIX . "web_sys800_data` set $sInsData");
                        if ($in) {
                            $strKeys = join(',', array_keys($v));
                            $strValues = join("','", array_values($v));
                            $sql = "INSERT INTO ".DBPREFIX."rebate_hour_hour_report (" . $strKeys . ") VALUES ('" . $strValues . "') ";
                            $result = mysqli_query($dbMasterLink, $sql);
                            if ($result) {
                                // 插入返水账变        0用户id|1用户名|2测试/正式|3操作前金额|4操作金额|5操作后金额|6操作类型|7来源|8数据id或订单号|9描述可为空
                                // 添加返水类型备注（type -4 - 返水，source - 5-后台）
                                $moneyLogRes=addAccountRecords(array($v['userid'],$v['username'],$aUser['test_flag'],$moneyf,$v['R_total'],$currency_after,56,8,'',"[计划任务-时时返水]{$StartTime}时时返水入账"));
                                if($moneyLogRes) {
                                    mysqli_query($dbMasterLink, "COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    die(json_encode(["err" => -9, "msg" => "添加返水账变日志失败！"]));
                                }
                            }
                            else{
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                die(json_encode(["err" => -5, "msg" => "时时返水报表添加失败！"]));
                            }
                        }
                        else {
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            die(json_encode(["err" => -7, "msg" => "账变记录插入失败！"]));
                        }
                    }
                    else{
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        die(json_encode(["err" => -4, "msg" => "更新会员资金失败！"]));
                    }
                }
                else{
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    die(json_encode(["err" => -3, "msg" => "锁定会员资金失败！"]));
                }
            }
        }
    }

    $sql = "insert into ".DBPREFIX."rebate_hour_hour_report_flag(rebate_date_hour, flag) value('{$StartTime}', 1) ";
    $result=mysqli_query($dbMasterLink, $sql);
    if($result){
        @error_log('--------------- 时时返水报表入库成功'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');
        @error_log(date("Y-m-d H:i:s").'--------------- 时时返水结束'.PHP_EOL, 3, '/tmp/group/rebate_hour_execute.log');
        echo "--------------- 时时返水结束";
    }
    else {
        die(json_encode(["err" => -8, "msg" => "插入计算成功表示符失败！"]));
    }
}
