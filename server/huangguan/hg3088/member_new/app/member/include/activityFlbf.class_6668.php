<?php

/* 存取款活动类 */
class ActivityFlbf{

    /**
     * 查询会员在 6,16,26 号线下公司入款的金额， 派送金额
     *
     * @param $username
     * @return mixed
     */
    public function getCompanyDeposit($user_id,$username,$time){
        global $dbLink;
        $begin_time = $time['begin_time'];
        $end_time =  $time['end_time'];
        $timeWhere = " AND `AddDate`>= '$begin_time' AND `AddDate`<= '$end_time'" ; //存款时间范围
        $gs_where = " AND Payway='N' AND Type='S' AND Checked=1";  // 公司卡存款

        //公司卡存款
        $company_sql = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where userid='$user_id' $timeWhere $gs_where";
        //@error_log("company_sql:"."$company_sql".PHP_EOL, 3, '/tmp/aaa.log');
        $company_query = mysqli_query($dbLink, $company_sql);
        $company_cou_res = mysqli_num_rows($company_query);
        if($company_cou_res > 0) {
            $result_company = mysqli_fetch_assoc($company_query); //公司存款额
            $company_money = !empty($result_company['Gold']) ? sprintf("%01.2f",$result_company['Gold']):0;
        }

        return $company_money;  // 当前日期会员公司存款额
    }




    /**
     * 根据上周负盈利返回转运金
     *
     * @param $numBets
     * @return mixed
     */
    public function bfGoldLevel($numBets){
        if($numBets >= 166 && date('d') == '6') {
            $result['sixGold'] = '6';
            $result['status'] = 2;
        } elseif($numBets >= 666  && date('d') == '16') {
            $result['sixGold'] = '36';
            $result['status'] = 2;
        } elseif($numBets >= 2666  && date('d') == '26') {
            $result['sixGold'] = '66';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['sixGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

}

?>