<?php

/* 中秋节日活动类 */
class ActivityHoliday{

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
    public function getDeposits($user_id,$time) {
        global $dbLink;
        $begin_time = $time['begin_time'];
        $end_time =  $time['end_time'];
        //@error_log('begin_time:'.$begin_time.'--end_time:'.$end_time.PHP_EOL, 3, '/tmp/aaa.log');
        $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围
        $kscz_where = " AND Payway='W' AND Type='S' AND Checked =1 AND discounType in (0,9)";  // 快速充值
        $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款

        // 第三方,快速充值
        $third_sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $kscz_where";
        //@error_log("third_sql:"."$third_sql".PHP_EOL, 3, '/tmp/aaa.log');
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
     * 根据存款金额返回中秋礼金
     *
     * @param $numBets  存款金额
     * @return mixed
     */
    public function getGiftAmount($numBets){
        $thousand = 1000;
        if($numBets >= 1*$thousand && $numBets < 6*$thousand) {
            $result['giftGold'] = '58';
            $result['status'] = 2;
        } elseif($numBets >= 6*$thousand && $numBets < 10*$thousand) {
            $result['giftGold'] = '88';
            $result['status'] = 2;
        } elseif($numBets >= 10*$thousand && $numBets < 30*$thousand) {
            $result['giftGold'] = '288';
            $result['status'] = 2;
        } elseif($numBets >= 30*$thousand && $numBets < 60*$thousand) {
            $result['giftGold'] = '388';
            $result['status'] = 2;
        } elseif($numBets >= 60*$thousand && $numBets < 200*$thousand) {
            $result['giftGold'] = '688';
            $result['status'] = 2;
        } elseif($numBets >= 200*$thousand && $numBets < 500*$thousand) {
            $result['giftGold'] = '1688';
            $result['status'] = 2;
        } elseif($numBets >= 500*$thousand && $numBets < 800*$thousand) {
            $result['giftGold'] = '2888';
            $result['status'] = 2;
        }  elseif($numBets >= 800*$thousand && $numBets < 1500*$thousand) {
            $result['giftGold'] = '5888';
            $result['status'] = 2;
        } elseif($numBets >= 1500*$thousand && $numBets < 3000*$thousand) {
            $result['giftGold'] = '8888';
            $result['status'] = 2;
        } elseif($numBets >= 3000*$thousand && $numBets < 5000*$thousand) {
            $result['giftGold'] = '16888';
            $result['status'] = 2;
        } elseif($numBets >= 5000*$thousand) {
            $result['giftGold'] = '38888';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['giftGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

}

?>