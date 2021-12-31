<?php

/* 存取款活动类 */
class ActivityBill{

    /**
     * 查询会员一周存取款
     *
     * @param $username
     * @return mixed
     *
     */
    public function lastWeekBill($user_id,$username,$time){
        global $dbLink;
        $date_s = date('Y-m-d',$time['beginLastweek']);
        $date_e =  date('Y-m-d',$time['endLastweek']);
        $userid = "userid='$user_id' ";

        $time =" AND addDate BETWEEN '".$date_s."' and '".$date_e."'" ; // 上周时间
        $payType = "AND Payway NOT IN ('O', 'R')";    // 申请礼金、返水不统计
        $Type = " AND Type IN ('S', 'T') AND discounType NOT IN (1,2,3,4,5,6,7,8) AND Checked=1 ";  // discounType in (1,2,3,4,5,6,7,8) 人工存款不算

        // 统计会员一周存款, 线下银行存款优惠, 返水, 申请礼金不统计在内
        $deposit_sql =  "select ID,userid,Checked,Payway,discounType,Gold,moneyf,currency_after,Type,UserName,Date,Name,Cancel,
        AuditDate,Preferential from ".DBPREFIX."web_sys800_data where $userid $time  $payType $Type order by ID desc";
        $res_deposit = mysqli_query($dbLink, $deposit_sql);

        $depositGold = $withDrawGold = 0;
        while ($row = mysqli_fetch_assoc($res_deposit)){
            if($row['Type'] == 'S') {
                if($row['Preferential'] == 1) {
                    $row['Gold'] = $row['currency_after']-$row['moneyf']; //存款实际金额, 不算优惠
                }
                $depositGold += $row['Gold']; // 上周总存款
            }

            if($row['Type'] == 'T') {
                $withDrawGold += $row['Gold'];// 上周总提款
            }
        }
        $depositGold = !empty($depositGold) ? sprintf("%01.2f",$depositGold):0;
        $withDrawGold = !empty($withDrawGold) ? sprintf("%01.2f",$withDrawGold):0;
        //@error_log('depositGold:'.$depositGold.'--withDrawGold:'.$withDrawGold.PHP_EOL, 3, '/tmp/aaa.log');
        $NegativeProfit = $depositGold  - $withDrawGold;

        return $NegativeProfit;  // 上周负盈利
    }




    /**
     * 根据上周负盈利返回转运金
     *
     * @param $numBets
     * @return mixed
     */
    public function transferGoldLevel($numBets){
        $thousand = 1000;
        if($numBets >= $thousand && $numBets < 2*$thousand) {
            $result['transferGold'] = '18';
            $result['status'] = 2;
        } elseif($numBets >= 2*$thousand && $numBets < 5*$thousand) {
            $result['transferGold'] = '28';
            $result['status'] = 2;
        } elseif($numBets >= 5*$thousand && $numBets < 10*$thousand) {
            $result['transferGold'] = '58';
            $result['status'] = 2;
        } elseif($numBets >= 10*$thousand && $numBets < 20*$thousand) {
            $result['transferGold'] = '88';
            $result['status'] = 2;
        } elseif($numBets >= 20*$thousand && $numBets < 50*$thousand) {
            $result['transferGold'] = '288';
            $result['status'] = 2;
        } elseif($numBets >= 50*$thousand && $numBets < 100*$thousand) {
            $result['transferGold'] = '888';
            $result['status'] = 2;
        } elseif($numBets >= 100*$thousand && $numBets < 200*$thousand) {
            $result['transferGold'] = '1688';
            $result['status'] = 2;
        } elseif($numBets >= 200*$thousand && $numBets < 500*$thousand) {
            $result['transferGold'] = '3888';
            $result['status'] = 2;
        } elseif($numBets >= 500*$thousand && $numBets < 1000*$thousand) {
            $result['transferGold'] = '8888';
            $result['status'] = 2;
        } elseif($numBets > 1000*$thousand) {
            $result['transferGold'] = '18888';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['transferGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

}

?>