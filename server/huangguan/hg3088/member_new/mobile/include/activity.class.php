<?php

class Activity{
    /* 活动类 */


    /**
     * 查询用户下注总额
     *
     * @param $username
     * @param $sAg_prefix
     * @param $aCp_default
     * @return mixed
     * $platfrom  hg0086 ,hg6668 只计算体育的有效投注
     */
    public function lastHistoryBet($user_id,$username,$time,$sAg_prefix=0,$aCp_default=0,$type,$platfrom){
        global $dbLink;
        $begin_time = $time['begin_time'];
        $end_time =  $time['end_time'];
        $userid_where = "userid='$user_id'";
        $name_where = "username='$username'"; //用于彩票

        // 体育上月历史注单统计报表下注总额  start
        if($type == 'quanqin' || $type =='vip') {
            $time_Where =" and `M_Date`>= '$begin_time' and `M_Date`<= '$end_time'" ;
            $sql_history_bet = "select userid,username,game_code,count_pay,sum(total) as betTotals,sum(valid_money) as valid_money from ".DBPREFIX."web_report_history_report_data where $userid_where $time_Where ";

            $sql_res_history = mysqli_query($dbLink, $sql_history_bet);
            $cou_res_z = mysqli_num_rows($sql_res_history);
            if($cou_res_z > 0) {
                $report_Ty_Row = mysqli_fetch_assoc($sql_res_history); //体育下注总额  会员各代号游戏下注总额 之和
                $hg_valid_money = !empty($report_Ty_Row['valid_money']) ? sprintf("%01.2f",$report_Ty_Row['valid_money']):0;
            }
        }

        if($type == 'quanqin' && $platfrom=='hg0086') {  // 0086 改成统计所有
            // 彩票主数据上月投注额 start
            $start_day_cp = strtotime($begin_time)-12*3600;
            $end_day_cp = strtotime($end_time)-12*3600;
            $cp_time =" AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."'" ;
            $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
            $sql_cp = "select sum(total) as total, sum(valid_money) as valid_money from gxfcy_history_bill_report where $name_where $cp_time";

            $res_cp = mysqli_query($cpDbLink, $sql_cp);
            $cou_cp = mysqli_num_rows($res_cp);
            if ($cou_cp>0) {
                $row_cp = mysqli_fetch_assoc($res_cp);
                $cp_valid_money = !empty($row_cp['valid_money']) ? sprintf("%01.2f",$row_cp['valid_money']):0;
            }

            // AG主数据上月投注额  start
            $ag_time =" AND bet_time BETWEEN '".$begin_time."' and '".$end_time."'" ;
            // count_pay 总注数, total投注总额, valid_money有效投注
            $sql_ag_bet =  "select username, sum(total) as total, sum(valid_money) as valid_money from ".DBPREFIX."ag_projects_history_report where $userid_where $ag_time";

            $res_ag = mysqli_query($dbLink, $sql_ag_bet);
            $cou_res_ag = mysqli_num_rows($res_ag);
            if($cou_res_ag > 0) {
                $result_ag = mysqli_fetch_assoc($res_ag); //AG历史注单报表投注总额
                $ag_valid_money = !empty($result_ag['valid_money']) ? sprintf("%01.2f",$result_ag['valid_money']):0;
            }

            // 捕鱼王   UserName=bajie0920  userid='36535'
            $byw_time =" AND EndTime BETWEEN '".$begin_time."' and '".$end_time."'" ;
            $sql_byw =  "select SUM(Cost) as Cost from ".DBPREFIX."ag_buyu_scene where $userid_where $byw_time";

            $res_byw = mysqli_query($dbLink, $sql_byw);
            $result_byw = mysqli_fetch_assoc($res_byw); //捕鱼王 子弹价值(支出)
            $byw_valid_cost = !empty($result_byw['Cost']) ? sprintf("%01.2f",$result_byw['Cost']):0;

            //cron 13:10 开元棋牌转移到历史订单表
            //0086   12604用户 9月：  hgty78_ky_projects  187077.36      hgty78_ky_history_projects 3387.54
            $kyqp_time =" AND game_endtime BETWEEN '".$begin_time."' and '".$end_time."'" ;
            $sql_kyqp =  "select SUM(cellscore) as cellscore from (
                select SUM(cellscore) as cellscore from ".DBPREFIX."ky_projects where $userid_where $kyqp_time
                UNION
                select SUM(cellscore) as cellscore from ".DBPREFIX."ky_history_projects where $userid_where $kyqp_time
            ) as aa";
            //@error_log($sql_kyqp.PHP_EOL,  3,  '/tmp/aaa.log');
            $res_kyqp = mysqli_query($dbLink, $sql_kyqp);
            $result_kyqp = mysqli_fetch_assoc($res_kyqp); //开元棋牌上月订单表和历史订单表有效投注总和
            $kyqp_valid_cost = !empty($result_kyqp['cellscore']) ? sprintf("%01.2f",$result_kyqp['cellscore']):0;
        }

        //@error_log('ty:'.$hg_valid_money.'cp:'.$cp_valid_money.'ag:'.$ag_valid_money.'byw:'.$byw_valid_cost.'kyqp:'.$kyqp_valid_cost.PHP_EOL,  3,  '/tmp/aaa.log');
        $betSum = $hg_valid_money + $cp_valid_money + $ag_valid_money + $byw_valid_cost + $kyqp_valid_cost;
        return $betSum;  // 上月有效投注总额
    }



    /**
     * 如果1号三点前，还未生成注单统计表
     * 全勤奖 需要在注单表统计上月最后一天投注额
     * VIP晋升彩金 需要在注单表统计昨日投注额
     *
     * @param $username
     * @param $time
     * @param $sAg_prefix
     * @param $aCp_default
     * @param $type
     * @return mixed
     * $platfrom  hg0086 ,hg6668 只计算体育的有效投注
     */
    public function lastDayBet($user_id,$username,$time,$sAg_prefix=0,$aCp_default=0,$type,$platfrom){
        global $dbLink;

        // 接收时间
        $lastDate = date('Y-m-d', strtotime($time['begin_time']));
        $lastDayBegin = $time['begin_time'];
        $lastDayEnd =  $time['end_time'];

        // VIP 只统计体育有效投注
        if($type == 'quanqin' || $type =='vip') {
            // 体育注单表上月最后一天有效投注
            if(isset($user_id) && $user_id>0){
                $sWhere_hg = "Userid='$user_id'";
                if($type == 'quanqin') {
                    $tWhere =" and `M_Date`= '$lastDate'" ;
                } elseif($type =='vip') {
                    $tWhere =" and `M_Date`>= '$lastDayBegin' and `M_Date`<= '$lastDayEnd'" ;
                }
                $cWhere = " and testflag=0 and `Cancel`=0";
                $res_hg = mysqli_query($dbLink, "select sum(BetScore) as total,sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE $sWhere_hg $tWhere $cWhere");
            }else{
                if($type == 'quanqin') {
                    $tWhere =" `M_Date`= '$lastDate'" ;
                } elseif($type =='vip') {
                    $tWhere =" `M_Date`>= '$lastDayBegin' and `M_Date`<= '$lastDayEnd'" ;
                }
                $cWhere = " and testflag=0 and `Cancel`=0";
                $res_hg = mysqli_query($dbLink, "select sum(BetScore) as total,sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE $tWhere $cWhere");
            }

            $cou_hg = mysqli_num_rows($res_hg);
            if ($cou_hg>0){
                $row_hg = mysqli_fetch_assoc($res_hg);
                $data['last_day_hg'] = !empty($row_hg['valid_money']) ? sprintf("%01.2f",$row_hg['valid_money']):0;
            }
        }

        if($type == 'quanqin' && $platfrom=='hg0086') {  // 0086 改成统计所有
            $userid_where = "userid='$user_id'";

            //  彩票上月最后一天有效下注金额
            $sWhere_cp = "username='$username'";
            $start_day_cp = strtotime($lastDayBegin)-12*3600;
            $end_day_cp = strtotime($lastDayEnd)-12*3600;
            $cp_lastday_time =" AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."'" ;
            $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
            $res_cp = mysqli_query($cpDbLink, "select sum(drop_money) as total,sum(valid_money) as valid_money from gxfcy_bill where $sWhere_cp $cp_lastday_time");

            $cou_cp = mysqli_num_rows($res_cp);
            if ($cou_cp>0) {
                $row_cp = mysqli_fetch_assoc($res_cp);
                $data['last_day_cp'] = !empty($row_cp['valid_money']) ? sprintf("%01.2f",$row_cp['valid_money']):0;
            }

            // AG上月最后一天有效投注
            /*$ag_username = $sAg_prefix.$username;
            $sWhere_ag = "username='$ag_username'";  //ag用户名*/
            $res_ag = mysqli_query($dbLink, "select sum(amount) as total,sum(valid_money) as valid_money from ".DBPREFIX."ag_projects where $userid_where and bettime BETWEEN '".$lastDayBegin."' and '".$lastDayEnd."'");

            //@error_log("select sum(amount) as total,sum(valid_money) as valid_money from ".DBPREFIX."ag_projects where $userid_where and bettime BETWEEN '".$lastDayBegin."' and '".$lastDayEnd."'".PHP_EOL,  3,  '/tmp/aaa.log');
            $cou_ag = mysqli_num_rows($res_ag);
            if ($cou_ag>0) {
                $row_ag = mysqli_fetch_assoc($res_ag);
                $data['last_day_ag'] = !empty($row_ag['valid_money']) ? sprintf("%01.2f",$row_ag['valid_money']):0;
            }
        }

        //@error_log('上月hg:'.$data['last_day_hg'].'--上月最后一天ag'.$data['last_day_cp'].'--上月最后一天cp'.$data['last_day_ag'].PHP_EOL,  3,  '/tmp/aaa.log');
        $lastDaySum = $data['last_day_hg'] + $data['last_day_cp'] + $data['last_day_ag'];
        return $lastDaySum;  // 1号三点  统计前一天投注总额
    }


    /**
     *  上月充值天数
     *
     * @param $user_id
     * @param $time
     * @param $flag 条件标识
     * @return int
     */
    public function lastMonthDeposit($user_id, $time, $flag=null){
        global $dbLink;
//        $begin_time = date('Y-m-01 00:00:00',strtotime('-1 month'));  // 上月开始时间截止时间
//        $end_time = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
        $begin_time = $time['begin_time'];  // 上月开始时间截止时间
        $end_time = $time['end_time'];
        $ck_Where = " and `AddDate`>= '$begin_time' and `AddDate`<= '$end_time'" ; //上月存款时间
        //$third_where ="AND Payway='W' AND Type='S' AND Checked =1 AND PayType>0";  // 第三方存款 discounType='0' User=''
        $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值  审核人User!='' discounType=9
        $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款
        // 人工存款 Payway=W  'discounType' , array('1','2','3','4','5','7') Checked ==1  不算存款天数

        !empty($flag) ?  $ck_Where .= " AND Gold >= '{$flag}' ": ''; // 签到次数

        // 第三方快速充值
        $third_sql = mysqli_query($dbLink, "select DISTINCT AddDate from ".DBPREFIX."web_sys800_data where userid='$user_id' $ck_Where $kscz_where");
        $thridDate = array();
        while($memThridRow = mysqli_fetch_assoc($third_sql)){
            $thridDate[] = $memThridRow['AddDate'];
        }
        //公司卡存款
        $company_sql = mysqli_query($dbLink, "select DISTINCT AddDate from ".DBPREFIX."web_sys800_data where userid='$user_id' $ck_Where $gs_where");
        //@error_log("company:"."select DISTINCT AddDate from ".DBPREFIX."web_sys800_data where userid='$user_id' $ck_Where $gs_where".PHP_EOL,  3,  '/tmp/aaa.log');
        $CompanyDate = array();
        while($memCompanyRow = mysqli_fetch_assoc($company_sql)){
            $CompanyDate[] = $memCompanyRow['AddDate'];
        }
        $depositUniqueDate = count(array_unique(array_merge($thridDate,$CompanyDate))); // 存款天数去重
        return $depositUniqueDate;
    }


    /**
     *  根据时间统计会员总存款金额
     *  统计不包含人工  Payway='W',`Type` in ('S','T'), discounType in (1,2,3,4,5,6,7,8), `Checked`=1, 审核人User!='' 人工存提款不算存款天数和存款金额
     *  第三方存款  Payway='W', `Type`='S', `Checked`=1, `PayType`>0 , discounType =0  审核人User=''  (PayType为第三方网银id)
     *  快速充值    Payway='W', `Type`='S', `Checked`=1, `PayType`=null , discounType=9 ,  审核人User!=''
     *  公司卡(线下银行) Payway='N' Type='S' Checked`=1, `PayType`>0   discounType =0  审核人User!='' (PayType为线下银行id)
     *
     * @param $user_id ,$username , $time
     * @return int
     */
    public function depositAmount($user_id,$time) {
        global $dbLink;
        $begin_time = $time['begin_time'];
        $end_time =  $time['end_time'];
        $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围
        $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值
        $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款

        // 第三方,快速充值
        $third_sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $kscz_where";
        $third_query = mysqli_query($dbLink, $third_sql);
        $third_cou_res = mysqli_num_rows($third_query);
        if($third_cou_res > 0) {
            $result_third = mysqli_fetch_assoc($third_query); //第三方,快速充值存款额
            $third_money = !empty($result_third['Gold']) ? sprintf("%01.2f",$result_third['Gold']):0;
        }

        //公司卡存款
        $company_sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $gs_where";
        //@error_log("company_sql:"."$company_sql".PHP_EOL, 3, '/tmp/aaa.log');
        $company_query = mysqli_query($dbLink, $company_sql);
        $company_cou_res = mysqli_num_rows($company_query);
        if($company_cou_res > 0) {
            $result_company = mysqli_fetch_assoc($company_query); //公司存款额
            $company_money = !empty($result_company['Gold']) ? sprintf("%01.2f",$result_company['Gold']):0;
        }

        $depositGold = $third_money + $company_money;
        return $depositGold;
    }

    /**
     * 根据下注总额和充值天数返回回馈金额和状态
     * 领取金额 388,888,1888,2888,3888,5888,6888,8888    cashStatus(状态：0默认1,已派发,2未审核,3不符合)
     *
     * @param $numBets
     * @param $depositDays
     * @return mixed
     */
    public function feedBack($numBets , $depositDays) {
        $tenThousand = '10000';
        $lastMonthNum = date('d ',strtotime(-date('d').'day')); // 上月天数
        if($numBets >= 10*$tenThousand && $numBets < 100*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '388';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '888';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 100*$tenThousand && $numBets < 500*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '1888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '2888';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 500*$tenThousand && $numBets < 1000*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '3888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '5888';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 1000*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '6888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '8888';}
            $result['cashStatus'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['cashBack'] = 0;$result['cashStatus'] = 3;
        }
        return $result;
    }

    /**
     * 6668平台忠诚全勤奖 根据每月有效投注总额和充值天数返回回馈金额和状态
     * 领取金额 388,588,888,1888,2888,3888,5888,6888    cashStatus(状态：0默认1,已派发,2未审核,3不符合)
     *
     * @param $numBets
     * @param $depositDays
     * @return mixed
     */
    public function Loyalty($numBets , $depositDays) {
        $tenThousand = '10000';
        $lastMonthNum = date('d ',strtotime(-date('d').'day')); // 上月天数
        if($numBets >= 10*$tenThousand && $numBets < 100*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '388';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '588';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 100*$tenThousand && $numBets < 300*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '1888';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 300*$tenThousand && $numBets < 500*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '2888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '3888';}
            $result['cashStatus'] = 2;
        } elseif($numBets >= 500*$tenThousand) {
            if($depositDays >= 10 && $depositDays <=20) { $result['cashBack'] = '5888';}
            if($depositDays >= 21 && $depositDays <=$lastMonthNum) { $result['cashBack'] = '6888';}
            $result['cashStatus'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['cashBack'] = 0;$result['cashStatus'] = 3;
        }
        return $result;
    }


    /**
     * 根据上周下注总额返回 会员等级 和晋升彩金
     * 根据会员等级 领取金额 青铜88, 白银188, 黄金288, 铂金588, 钻石888, 星耀1888, 王者3888, 皇冠15888
     *
     * @param $numBets
     * @return mixed
     */
    public function levelLottery($numBets){
        $thousand = 1000;
        if($numBets >= 30*$thousand && $numBets < 100*$thousand) {
            $result['memLevel'] = '青铜';
            $result['vipGold'] = '88';
            $result['status'] = 2;
        } elseif($numBets >= 100*$thousand && $numBets <= 300*$thousand) {
            $result['memLevel'] = '白银';
            $result['vipGold'] = '188';
            $result['status'] = 2;
        } elseif($numBets >= 300*$thousand && $numBets <= 500*$thousand) {
            $result['memLevel'] = '黄金';
            $result['vipGold'] = '288';
            $result['status'] = 2;
        } elseif($numBets >= 500*$thousand && $numBets <= 1000*$thousand) {
            $result['memLevel'] = '铂金';
            $result['vipGold'] = '588';
            $result['status'] = 2;
        } elseif($numBets >= 1000*$thousand && $numBets <= 3000*$thousand) {
            $result['memLevel'] = '钻石';
            $result['vipGold'] = '888';
            $result['status'] = 2;
        } elseif($numBets >= 3000*$thousand && $numBets <= 5000*$thousand) {
            $result['memLevel'] = '星耀';
            $result['vipGold'] = '1888';
            $result['status'] = 2;
        } elseif($numBets >= 5000*$thousand && $numBets <= 10000*$thousand) {
            $result['memLevel'] = '王者';
            $result['vipGold'] = '3888';
            $result['status'] = 2;
        } elseif($numBets >= 10000*$thousand) {
            $result['memLevel'] = '皇冠';
            $result['vipGold'] = '15888';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['memLevel'] = '普通';
            $result['vipGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

    /**
     * 0086平台根据会员前一天存款总额返回彩金
     *  1000+ 18, 3000+ 38, 5000+ 58, 1万+ 88, 3万+ 188, 5万+ 388, 10万+ 888, 20万+ 1888， 50万+ 3888， 100万+ 8888， 200万+ 18888
     *
     * @param $numBets
     * @return mixed
     */
    public function getLastDepositGift($numBets){
        $tenThousand = '10000';
        if($numBets >= 1000 && $numBets < 3000) {
            $result['GiftGold'] = '18';  $result['status'] = 2;
        } elseif($numBets >= 3000 && $numBets < 5000) {
            $result['GiftGold'] = '38';  $result['status'] = 2;
        } elseif($numBets >= 5000 && $numBets < 1*$tenThousand) {
            $result['GiftGold'] = '58';  $result['status'] = 2;
        } elseif($numBets >= 1*$tenThousand && $numBets < 3*$tenThousand) {
            $result['GiftGold'] = '88';  $result['status'] = 2;
        } elseif($numBets >= 3*$tenThousand && $numBets < 5*$tenThousand) {
            $result['GiftGold'] = '188'; $result['status'] = 2;
        } elseif($numBets >= 5*$tenThousand && $numBets < 10*$tenThousand) {
            $result['GiftGold'] = '388'; $result['status'] = 2;
        } elseif($numBets >= 10*$tenThousand && $numBets < 20*$tenThousand) {
            $result['GiftGold'] = '888'; $result['status'] = 2;
        } elseif($numBets >= 20*$tenThousand && $numBets < 50*$tenThousand) {
            $result['GiftGold'] = '1888'; $result['status'] = 2;
        } elseif($numBets >= 50*$tenThousand && $numBets < 100*$tenThousand) {
            $result['GiftGold'] = '3888'; $result['status'] = 2;
        } elseif($numBets >= 100*$tenThousand && $numBets < 200*$tenThousand) {
            $result['GiftGold'] = '8888'; $result['status'] = 2;
        } elseif($numBets >= 200*$tenThousand) {
            $result['GiftGold'] = '18888'; $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['GiftGold'] = '0'; $result['status'] = 3;
        }
        return $result;
    }

    /**
     * 6668平台根据会员当天申请单笔存款额返回彩金
     *  100+ 8, 1000+ 18, 5000+ 38, 1万+ 88, 3万+ 188, 5万+ 288, 10万+ 388,
     *  20万+ 688，30万+ 888， 50万+ 3888， 100万+ 8888， 200万+ 38888， 500万+ 88888, 800万+ 288888
     *
     * @param $numBets
     * @return mixed
     */
    public function getSingleDepositGift($numBets){
        $tenThousand = '10000';
        if($numBets >= 100 && $numBets < 1000) {
            $result['GiftGold'] = '8';  $result['status'] = 2;
        } elseif($numBets >= 1000 && $numBets < 5000) {
            $result['GiftGold'] = '18';  $result['status'] = 2;
        } elseif($numBets >= 5000 && $numBets < 1*$tenThousand) {
            $result['GiftGold'] = '38';  $result['status'] = 2;
        } elseif($numBets >= 1*$tenThousand && $numBets < 3*$tenThousand) {
            $result['GiftGold'] = '88';  $result['status'] = 2;
        } elseif($numBets >= 3*$tenThousand && $numBets < 5*$tenThousand) {
            $result['GiftGold'] = '188'; $result['status'] = 2;
        } elseif($numBets >= 5*$tenThousand && $numBets < 10*$tenThousand) {
            $result['GiftGold'] = '288'; $result['status'] = 2;
        } elseif($numBets >= 10*$tenThousand && $numBets < 20*$tenThousand) {
            $result['GiftGold'] = '388'; $result['status'] = 2;
        } elseif($numBets >= 20*$tenThousand && $numBets < 30*$tenThousand) {
            $result['GiftGold'] = '688'; $result['status'] = 2;
        } elseif($numBets >= 30*$tenThousand && $numBets < 50*$tenThousand) {
            $result['GiftGold'] = '888'; $result['status'] = 2;
        } elseif($numBets >= 50*$tenThousand && $numBets < 100*$tenThousand) {
            $result['GiftGold'] = '3888'; $result['status'] = 2;
        } elseif($numBets >= 100*$tenThousand && $numBets < 200*$tenThousand) {
            $result['GiftGold'] = '8888'; $result['status'] = 2;
        } elseif($numBets >= 200*$tenThousand && $numBets < 500*$tenThousand) {
            $result['GiftGold'] = '38888'; $result['status'] = 2;
        } elseif($numBets >= 500*$tenThousand && $numBets < 800*$tenThousand) {
            $result['GiftGold'] = '88888'; $result['status'] = 2;
        } elseif($numBets >= 800*$tenThousand) {
            $result['GiftGold'] = '288888'; $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['GiftGold'] = '0'; $result['status'] = 3;
        }
        return $result;
    }

    /** 获取最大一笔存单
     * @param $user_id
     * @param $time
     * @return string
     */
    public function depositMaxAmount($user_id, $time){
        global $dbLink;
        $begin_time = $time['begin_time'];
        $end_time =  $time['end_time'];
        $third_money = $company_money = 0;
        $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围
        $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值
        $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款

        // 第三方,快速充值
        $third_sql = "select max(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $kscz_where";
        $third_query = mysqli_query($dbLink, $third_sql);
        $third_cou_res = mysqli_num_rows($third_query);
        if($third_cou_res > 0) {
            $result_third = mysqli_fetch_assoc($third_query); //第三方,快速充值存款额
            $third_money = !empty($result_third['Gold']) ? sprintf("%01.2f",$result_third['Gold']):0;
        }

        //公司卡存款
        $company_sql = "select max(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $gs_where";
        $company_query = mysqli_query($dbLink, $company_sql);
        $company_cou_res = mysqli_num_rows($company_query);
        if($company_cou_res > 0) {
            $result_company = mysqli_fetch_assoc($company_query); //公司存款额
            $company_money = !empty($result_company['Gold']) ? sprintf("%01.2f",$result_company['Gold']):0;
        }

        $depositGold = $third_money > $company_money ? $third_money : $company_money;
        return $depositGold;
    }

    /**
     * 活动日期	单笔存款金额	国庆礼金
     * 美东时间10月1日至10月7日
     * 100+	  8
     * 500+	  28
     * 1000+  58
     * 5000+  98
     * 1万+	  188
     * 3万+	  388
     * 5万+	  588
     * 10万+	  888
     * 20万+	  1888
     * 50万+	  3888
     * 100万+ 12888
     *
     * @param $numBets
     * @return string
     */
    public function getDepositGold($numBets){
        $tenThousand = '10000';
        if($numBets >= 100 && $numBets < 500) {
            $goldDeposit = '8';
        } elseif($numBets >= 500 && $numBets < 1000) {
            $goldDeposit = '28';
        } elseif($numBets >= 1000 && $numBets < 5000) {
            $goldDeposit = '58';
        } elseif($numBets >= 5000 && $numBets < 1*$tenThousand) {
            $goldDeposit = '98';
        } elseif($numBets >= 1*$tenThousand && $numBets < 3*$tenThousand) {
            $goldDeposit = '188';
        } elseif($numBets >= 3*$tenThousand && $numBets < 5*$tenThousand) {
            $goldDeposit = '388';
        } elseif($numBets >= 5*$tenThousand && $numBets < 10*$tenThousand) {
            $goldDeposit = '588';
        } elseif($numBets >= 10*$tenThousand && $numBets < 20*$tenThousand) {
            $goldDeposit = '888';
        } elseif($numBets >= 20*$tenThousand && $numBets < 50*$tenThousand) {
            $goldDeposit = '1888';
        } elseif($numBets >= 50*$tenThousand && $numBets < 100*$tenThousand) {
            $goldDeposit = '3888';
        } elseif($numBets >= 100*$tenThousand) {
            $goldDeposit = '12888';
        } else{ // 不满足条件 回馈金额0
            $goldDeposit = '0';
        }
        return $goldDeposit;
    }


    /**
     * 6668活动日期美东10月7日至10月9日	单笔存款金额	国庆礼金
     * 统计时间10月1日至10月7日
     *
     * @param $depositMax 单笔最大存款金额
     * @param $signNum 签到次数
     * @return string
     */
    public function getNationalDayGold($depositMax , $signNum){
        if($signNum >= 1 && $signNum < 3) {
            if($depositMax >= 500 && $depositMax < 1000) {
                $goldDeposit = '18';
            } else if($depositMax >= 1000 && $depositMax < 5000) {
                $goldDeposit = '28';
            } else if($depositMax >= 5000 && $depositMax < 10000) {
                $goldDeposit = '58';
            } else if($depositMax >= 10000 && $depositMax < 50000) {
                $goldDeposit = '88';
            } else if($depositMax >= 50000 && $depositMax < 100000) {
                $goldDeposit = '288';
            } else if($depositMax >= 100000 && $depositMax < 500000) {
                $goldDeposit = '588';
            } else if($depositMax >= 500000) {
                $goldDeposit = '888';
            }
        } elseif($signNum >= 3 && $signNum < 5) {
            if($depositMax >= 500 && $depositMax < 1000) {
                $goldDeposit = '38';
            } else if($depositMax >= 1000 && $depositMax < 5000) {
                $goldDeposit = '58';
            } else if($depositMax >= 5000 && $depositMax < 10000) {
                $goldDeposit = '88';
            } else if($depositMax >= 10000 && $depositMax < 50000) {
                $goldDeposit = '128';
            } else if($depositMax >= 50000 && $depositMax < 100000) {
                $goldDeposit = '388';
            } else if($depositMax >= 100000 && $depositMax < 500000) {
                $goldDeposit = '688';
            } else if($depositMax >= 500000) {
                $goldDeposit = '1888';
            }
        } elseif($signNum >= 5 && $signNum < 7) {
            if($depositMax >= 500 && $depositMax < 1000) {
                $goldDeposit = '58';
            } else if($depositMax >= 1000 && $depositMax < 5000) {
                $goldDeposit = '88';
            } else if($depositMax >= 5000 && $depositMax < 10000) {
                $goldDeposit = '128';
            } else if($depositMax >= 10000 && $depositMax < 50000) {
                $goldDeposit = '188';
            } else if($depositMax >= 50000 && $depositMax < 100000) {
                $goldDeposit = '688';
            } else if($depositMax >= 100000 && $depositMax < 500000) {
                $goldDeposit = '888';
            } else if($depositMax >= 500000) {
                $goldDeposit = '3888';
            }
        } elseif($signNum >= 7) {
            if($depositMax >= 500 && $depositMax < 1000) {
                $goldDeposit = '88';
            } else if($depositMax >= 1000 && $depositMax < 5000) {
                $goldDeposit = '188';
            } else if($depositMax >= 5000 && $depositMax < 10000) {
                $goldDeposit = '288';
            } else if($depositMax >= 10000 && $depositMax < 50000) {
                $goldDeposit = '588';
            } else if($depositMax >= 50000 && $depositMax < 100000) {
                $goldDeposit = '1888';
            } else if($depositMax >= 100000 && $depositMax < 500000) {
                $goldDeposit = '2888';
            } else if($depositMax >= 500000) {
                $goldDeposit = '6888';
            }
        } else{ // 不满足条件 回馈金额0
            $goldDeposit = '0';
        }
        return $goldDeposit;
    }

}

?>