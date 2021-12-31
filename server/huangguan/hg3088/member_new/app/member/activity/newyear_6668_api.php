<?php
/*
 * 6668  活动规则
 * 1.玩家在2019年2月4日至2月6日期间，存款金额大于等于1000即可获得红包。
 * 2.本次红包金额将随机赠送，符合要求的玩家只需点击(领取红包)即可获取红包。
 * 2.本活动所获得彩金完成一倍流水要求即可提款。

    签到规则：
        1.  2.4号-2.06号 任意一天签到，之后提示已签到 。
        2. 用户签到列表后台只有admin 能看。
    领取红包(直接到账):
 * */

session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");

$action = $_REQUEST['action'];
$userid = $_SESSION['userid'];
$UserName = $_SESSION['UserName'];
$realName = $_SESSION['Alias'];
$date = date('Y-m-d',time());

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = array('status'=>'401.0', 'info'=>'请重新登录!');
    echo json_encode($status);exit;
}
$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准领取彩金分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=4;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        $status = array('status'=>'401.66', 'info'=>'账号分层异常，请联系我们在线客服');
        echo json_encode($status);exit;
    }
}

$newYearBeginTime=  mktime(0,0,0,2,4, date('Y'));    //2月4日 北京时间2月4号 12:00
//$newYearBeginTime=  mktime(0,0,0,1,23, date('Y'));    //测试 1月23日 北京时间1月23号 12:00
$newYearEndTime = mktime(23,59,59,2,6, date('Y'));  //2月6日 北京时间2月7号 12:00
if(time() < $newYearBeginTime || time() > $newYearEndTime){
    $status = array('status'=>'401.2', 'info'=>'请于美东时间2月4号-6号期间领取红包哦!');
    echo json_encode($status);exit;
}

switch ($action){
    case 'get_remain_num': // 更新数据（剩余红包次数统计）

        $data = get_deposit_money_times();   //根据存款金额返回红包次数
	    $last_times = $data['last_times'];
        $status = array('status'=>'1', 'info'=>'获取剩余次数成功!', 'last_times'=> $last_times);
        echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;

        break;
    case 'receive_red_envelope': // 领取红包
        $data = get_deposit_money_times();   //返回今日存款总金额、有效投注(后台显示)、剩余红包次数

        $deposit_money = $data['deposit_money'];
        $valid_money = $data['valid_money'];
        $last_times = $data['last_times'];

        // 校验存款
        if ($deposit_money < 1000){
            $info = '存款金额'.($deposit_money>0?$deposit_money:0).'元，存款金额不足不能领取';
            $status = array('status'=>'401.3', 'info'=>$info, 'last_times'=> $last_times);
            echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
        }

        // 校验可领次数
        if ($last_times == 0){
            $status = array('status'=>'401.4', 'info'=>'可领次数不足不能领取', 'last_times'=> $last_times);
            echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
        }

        // ------------------------------------------------------------------------------------------抽取红包开始，初始化一些红包池数据Start
        // 总几率100%，根据金额还有几率，生成100个金额
        // 随机在100个值中抽取1个金额，给会员进行派发
        $sql = "select *  from ".DBPREFIX."newyear_red_envelope_config";
        $res = mysqli_query($dbLink,$sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($res)){
            $rows[]=$row;
        }

        $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
        foreach ($rows as $k => $v){
            $red_envelope_nums = $v['probability']*100;
            $keys = array_keys($red_envelope_pool); //98
            $last_key = max($keys); // 红包池最后一位key
            $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['money']);    // Array([99] => 88)
            $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);    //array( [0]-[99])
        }

        $rand = rand(0,99); // 0到99随机生成1个数字
        $gold = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if($beginFrom){

            $depositWaterDate  = $date;    // 存款流水日期
            $resultMem = mysqli_query($dbMasterLink,"select ID,Money from  ".DBPREFIX.MEMBERTABLE." where ID='$userid' for update");
            if($resultMem){
                $rowMem = mysqli_fetch_assoc($resultMem);
                $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='$userid'";
                if(mysqli_query($dbMasterLink,$mysql)){

                    $currency_after = $rowMem['Money']+$gold; // 用户充值后的余额
                    $agents=$_SESSION['Agents'];
                    $world=$_SESSION['World'];
                    $corprator=$_SESSION['Corprator'];
                    $super=$_SESSION['Super'];
                    $admin=$_SESSION['Admin'];
                    $getday= date("Y-m-d H:i:s");
                    $realName = $_SESSION['Alias'];
                    $notes='6668新春红包活动'; // 备注
                    $bank = $_SESSION['Bank_Name'];
                    $bank_account=$_SESSION['Bank_Account'];
                    $bank_address=$_SESSION['Bank_Address'];
                    $order_code = date("YmdHis",time()).rand(100000,999999);
                    $AuditDate = date("Y-m-d H:i:s");
                    $test_flag=$_SESSION['test_flag'];
                    $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$userid}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$UserName}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                    //@error_log($sql.PHP_EOL,  3,  '/tmp/aaa.log');
                    $res = mysqli_query($dbMasterLink,$sql);
                    if ($res) {
                        $BillAddDate = date("Y-m-d H:i:s");
                        $appRefer = 1;
                        $sqlBill="insert into ".DBPREFIX."newyear_red_envelope_bill set userid='{$userid}',UserName='{$UserName}',Alias='{$realName}',NewYearRedEnvelopeGold='{$gold}',BillAddDate='$BillAddDate',
                                    depositMoney='$deposit_money',validMoney='$valid_money',depositWaterDate='$depositWaterDate'";
                        $resBill = mysqli_query($dbMasterLink,$sqlBill);
                        if ($resBill){
                            $moneyLogRes = addAccountRecords(array($userid, $UserName, $test_flag, $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, $appRefer, $userid, "[$notes],成功入账"));
                            if ($moneyLogRes) {
                                mysqli_query($dbMasterLink, "COMMIT");
                                $status = array('status'=>'1', 'info'=>'恭喜，抽取新春红包'.$gold.'元');
                                echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;

                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                $status = array('status'=>'401.10', 'info'=>'插入用户资金账变记录失败');
                                echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
                            }
                        }else{
                            $status = array('status'=>'401.9', 'info'=>'插入会员彩金记录失败');
                            echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        $status = array('status'=>'401.8', 'info'=>'插入800账变记录失败');
                        echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $status = array('status'=>'401.7', 'info'=>'更新会员资金失败');
                    echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status = array('status'=>'401.6', 'info'=>'锁定会员资金失败');
                echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status = array('status'=>'401.5', 'info'=>'事务开启失败');
            echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;
        }

        // ------------------------------------------------------------------------------------------抽取红包开始，初始化一些红包池数据End

        break;
    default:
        $status = array('status'=>'401.2', 'info'=>'参数有误!');
        echo json_encode($status,JSON_UNESCAPED_UNICODE);exit;

        break;
}
exit;

// 获取体育当日 存款、 昨日有效投注 (现存现领)
function get_deposit_money_times(){
    global $dbLink, $userid,$grab_newyear_red_envelope_times_level,$date;

    // ---------------------------------------------------------------------------今日存款金额1000以上（包含1000）【只捞取快速充值、公司入款、第三方】Start

    $ckTime = " and `AddDate`= '$date'" ; //存款日期
    $sql_deposit_money = "select userid,Checked,Payway,Gold,AddDate,Type,UserName from ".DBPREFIX."web_sys800_data where Checked=1 and Type='S' and userid='$userid' $ckTime and (discounType =9 or Payway='N' or `PayType`>0)";
    $res = mysqli_query($dbLink,$sql_deposit_money);

    $deposit_money = $total_times = 0; // 默认0
    while ($row = mysqli_fetch_assoc($res)) {
        $onceDepositGold = $row['Gold'];
        $receive = getOnceDepositNum($onceDepositGold);
        $deposit_money += $onceDepositGold;   // 当日存款总额累计
        $total_times += $receive;   // 当日可领取总次数累计
    }

    // --------------------------------------------------------------------------今日存款金额-体育，End

    // ---------------------------------------------------------------------------今日有效投注-Start
    //$sql_valid_money = "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE Userid = '$userid' and M_Date = '".date('Y-m-d',strtotime('-1 day'))."' and M_Result!=0 and Checked = 1";
    $sql_valid_money = "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE Userid = '$userid' and M_Date = '$date' and M_Result!=0 and Checked = 1";
    $res_hg_valid_money =  mysqli_query($dbLink, $sql_valid_money);
    $row_valid_money = mysqli_fetch_assoc($res_hg_valid_money);
    $hg_valid_money = $row_valid_money['valid_money'];
    // 体育有效投注(流水)
    $valid_money = $hg_valid_money;
    // --------------------------------------------------------------------------今日有效金额-体育，End

    // -------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽Start
    /*foreach ($grab_newyear_red_envelope_times_level as $k => $v){
        if ($k<7){
            // 档位从第一档到第六档
            if ($deposit_money >= $v['deposit_amount'] and $deposit_money < $grab_newyear_red_envelope_times_level[$k+1]['deposit_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
                break;
            }
        }else{
            // 有效次数档位第七档
            if ($deposit_money >= $v['deposit_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
            }
        }
    }*/
    // ------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽End

    // 捞取今天已领取的几次
    $startTime = date('Y-m-d 00:00:00');
    $endTime = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $sql = "select count(1) as cou from ".DBPREFIX."newyear_red_envelope_bill where userid = $userid and BillAddDate between '$startTime' and '$endTime' ";
    $res =  mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_assoc($res);

    $data['deposit_money'] = sprintf("%.2f",$deposit_money)>0 ? $deposit_money : 0;
    $data['valid_money'] = sprintf("%.2f",$valid_money)>0 ? $valid_money : 0;
    $last_times = $total_times - $row['cou']; // 会员剩余可领次数

    //@error_log('今日累计存款:'.$deposit_money.'-流水:'.$valid_money.'-可领次数:'.$total_times . '-今日已领:'.$row['cou'] . '-剩余次:'.$last_times.PHP_EOL,  3,  '/tmp/aaa.log');
    $data['last_times'] = $last_times>0?$last_times:0;

    return $data;
}

// 获取单笔存款领取红包次数
function getOnceDepositNum($depositGold){
    $thousand = 1000;
    if($depositGold >= 1*$thousand && $depositGold < 5*$thousand) {
        $receive = 1;
    } elseif($depositGold >= 5*$thousand && $depositGold < 10*$thousand) {
        $receive = 3;
    } elseif($depositGold >= 10*$thousand && $depositGold < 50*$thousand) {
        $receive = 5;
    } elseif($depositGold >= 50*$thousand && $depositGold < 100*$thousand) {
        $receive = 8;
    } elseif($depositGold >= 100*$thousand && $depositGold < 500*$thousand) {
        $receive = 12;
    } elseif($depositGold >= 500*$thousand && $depositGold < 1000*$thousand) {
        $receive = 18;
    } elseif($depositGold >= 1000*$thousand && $depositGold < 5000*$thousand) {
        $receive = 28;
    } elseif($depositGold >= 5000*$thousand) {
        $receive = 58;
    } else{ // 不满足条件 0
        $receive = 0;
    }
    return $receive;
}

?>