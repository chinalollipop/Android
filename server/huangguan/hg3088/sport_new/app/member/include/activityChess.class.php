<?php

class ActivityChess{
    /* 棋牌活动类 */

    /**
     * 查询棋牌有效投注
     *
     * @param $username
     * @return mixed
     */
    public function lastDayChessValidBet($user_id,$username,$time){
        global $dbLink;
        $begin_time = $time['beginYesterday'];
        $end_time =  $time['endYesterday'];
        $userid_where = "userid='$user_id'";
        
        $time =" AND game_endtime BETWEEN '".$begin_time."' and '".$end_time."'" ;

        // 开元棋牌
        $sql_kyqp =  "select SUM(cellscore) as cellscore from ".DBPREFIX."ky_projects where $userid_where $time";
        $res_kyqp = mysqli_query($dbLink, $sql_kyqp);
        $result_kyqp = mysqli_fetch_assoc($res_kyqp); //开元棋牌当前时间 订单表有效投注总和
        $kyqp_valid_cost = !empty($result_kyqp['cellscore']) ? sprintf("%01.2f",$result_kyqp['cellscore']):0;

        // 皇冠棋牌
        $sql_hgqp =  "select SUM(valid_bet) as cellscore from ".DBPREFIX."ff_projects where $userid_where $time";
        $res_hgqp = mysqli_query($dbLink, $sql_hgqp);
        $result_hgqp = mysqli_fetch_assoc($res_hgqp); //开元棋牌当前时间 订单表有效投注总和
        $hgqp_valid_cost = !empty($result_hgqp['cellscore']) ? sprintf("%01.2f",$result_hgqp['cellscore']):0;

        // VG棋牌
        $sql_vgqp =  "select SUM(validbetamount) as cellscore from ".DBPREFIX."vg_projects where $userid_where $time";
        $res_vgqp = mysqli_query($dbLink, $sql_vgqp);
        $result_vgqp = mysqli_fetch_assoc($res_vgqp); //开元棋牌当前时间 订单表有效投注总和
        $vgqp_valid_cost = !empty($result_vgqp['cellscore']) ? sprintf("%01.2f",$result_vgqp['cellscore']):0;

        // 乐游棋牌
        $sql_lyqp =  "select SUM(cellscore) as cellscore from ".DBPREFIX."ly_projects where $userid_where $time";
        //@error_log($sql_lyqp.PHP_EOL,  3,  '/tmp/aaa.log');
        $res_lyqp = mysqli_query($dbLink, $sql_lyqp);
        $result_lyqp = mysqli_fetch_assoc($res_lyqp); //开元棋牌当前时间 订单表有效投注总和
        $lyqp_valid_cost = !empty($result_lyqp['cellscore']) ? sprintf("%01.2f",$result_lyqp['cellscore']):0;


        //@error_log('ky:'.$kyqp_valid_cost.'--hg:'.$hgqp_valid_cost.'--vg:'.$vgqp_valid_cost.'--ly:'.$lyqp_valid_cost.PHP_EOL,  3,  '/tmp/aaa.log');
        $betSum = $kyqp_valid_cost + $hgqp_valid_cost + $vgqp_valid_cost + $lyqp_valid_cost;
        return $betSum;  // 昨日棋牌有效投注总额
    }


    /**
     * 根据昨日下注总额返回彩金
     *
     * @param $numBets
     * @return mixed
     */
    public function chessGameLevel($numBets){
        $thousand = 1000;
        if($numBets >= $thousand && $numBets < 2*$thousand) {
            $result['chessGold'] = '8';
            $result['status'] = 2;
        } elseif($numBets >= 2*$thousand && $numBets < 5*$thousand) {
            $result['chessGold'] = '18';
            $result['status'] = 2;
        } elseif($numBets >= 5*$thousand && $numBets < 10*$thousand) {
            $result['chessGold'] = '28';
            $result['status'] = 2;
        } elseif($numBets >= 10*$thousand && $numBets < 50*$thousand) {
            $result['chessGold'] = '58';
            $result['status'] = 2;
        } elseif($numBets >= 50*$thousand && $numBets < 100*$thousand) {
            $result['chessGold'] = '88';
            $result['status'] = 2;
        } elseif($numBets >= 100*$thousand && $numBets < 500*$thousand) {
            $result['chessGold'] = '188';
            $result['status'] = 2;
        } elseif($numBets >= 500*$thousand && $numBets < 1000*$thousand) {
            $result['chessGold'] = '588';
            $result['status'] = 2;
        } elseif($numBets >= 1000*$thousand && $numBets < 5000*$thousand) {
            $result['chessGold'] = '1288';
            $result['status'] = 2;
        } elseif($numBets >= 5000*$thousand && $numBets < 10000*$thousand) {
            $result['chessGold'] = '5888';
            $result['status'] = 2;
        } elseif($numBets > 10000*$thousand) {
            $result['chessGold'] = '8888';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['chessGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

    /**
     * 6668天天得意金根据昨日下注总额返回彩金
     *
     * @param $numBets
     * @return mixed
     */
    public function qpdyjGameLevel($numBets){
        $thousand = 10000;
        if($numBets >= $thousand && $numBets < 3*$thousand) {
            $result['chessGold'] = '18';
            $result['status'] = 2;
        } elseif($numBets >= 3*$thousand && $numBets < 5*$thousand) {
            $result['chessGold'] = '38';
            $result['status'] = 2;
        } elseif($numBets >= 5*$thousand && $numBets < 10*$thousand) {
            $result['chessGold'] = '58';
            $result['status'] = 2;
        } elseif($numBets >= 10*$thousand && $numBets < 50*$thousand) {
            $result['chessGold'] = '128';
            $result['status'] = 2;
        } elseif($numBets >= 50*$thousand && $numBets < 100*$thousand) {
            $result['chessGold'] = '388';
            $result['status'] = 2;
        } elseif($numBets >= 100*$thousand && $numBets < 500*$thousand) {
            $result['chessGold'] = '588';
            $result['status'] = 2;
        } elseif($numBets >= 500*$thousand && $numBets < 1000*$thousand) {
            $result['chessGold'] = '888';
            $result['status'] = 2;
        } elseif($numBets >= 1000*$thousand && $numBets < 10000*$thousand) {
            $result['chessGold'] = '1888';
            $result['status'] = 2;
        } elseif($numBets > 10000*$thousand) {
            $result['chessGold'] = '18888';
            $result['status'] = 2;
        } else{ // 不满足条件 回馈金额0  状态不符合3
            $result['chessGold'] = '0';
            $result['status'] = 3;
        }
        return $result;
    }

}

?>