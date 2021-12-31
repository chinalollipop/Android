<?php

/**
 * Class ag_game
 *
 * AG游戏对接控制器
 */

class ag_game{

    private static $_mysqli = null;

    //初始化必要的数据
    public function __construct($mysqli) {
        self::$_mysqli = $mysqli;

    }

    /**
     * AG转账失败，写入错误日志
     *
     * @param $username
     * @param $sJxc_trans_no
     * @param $fShiftMoney
     */
    public function third_deposit_or_withdraw_error_in($username, $sJxc_trans_no, $fShiftMoney){
        $data = array(
            'user_name' => $username,
            'third_id' => '5',
            'transfer_no' => $sJxc_trans_no,
            'amount' => $fShiftMoney,
            'wirte_time' => date('Y-m-d H:i:s')
        );
        $sInsData = '';
        foreach ($data as $key => $value){
            if ($key=='wirte_time') {
                $sInsData.= "`$key` = '{$value}'";
            }else{
                $sInsData.= "`$key` = '{$value}',";
            }
        }
        $sql = "insert into `third_withdraw_error` set $sInsData";
        $in = mysqli_query(self::$_mysqli,$sql);
        if (!$in){
            exit("报错：确认转账失败，请与AG方确认");
        }
    }


}
