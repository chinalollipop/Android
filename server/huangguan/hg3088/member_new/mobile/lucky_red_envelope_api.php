<?php
/**
 * lucky_red_envelope_api.php
 *
 * @param action
 *         get_valid 更新数据（昨日有效金额、可领次数）
 *         extract_lucky_red_envelope，人人抽取幸运红包
 *
 */

/**
 * 0086抽取幸运红包 extract_lucky_red_envelope
活动说明：
1、每日计算区间：北京时间中午12：00：00至次日12：00：00期间的体育、彩票的打码量（输赢一半计算一半打码量，和局不计算打码量）。
2、此优惠需要会员在此活动页面点击【抽取幸运红包】进行领取，领取到的红包金额将自动添加到会员账户！
3、每位会员每个可参与的红包次数，次日12点之后自动更新，更新可领次数即可开始点击抽取幸运红包进行领取，直至可领次数用完为止；如果在24小时内未点击抽取幸运红包本公司将视为自动放弃本次活动。
4、领取红包之后将重新计算新一轮的日累计打码量！

活动条款：
1、此活动仅适用于手机APP用户！
2、任何个人或团体以不诚实方式套取红利，HG0086保留取消其活动资格，收回优惠红利和活动产生盈利以及关闭会员账号的权利。
3、HG0086保留在任何时候都可以更改，停止该活动的权利，并不做提前通知。
4、本活动最终解释权归属HG0086所有。
 */

/**
 * 6668抽取幸运红包
活动规则：
1.本次活动仅限体育与彩票，其它游戏流水一律不计算有效流水
2.活动按照北京时间中午12：00：00至次日12：00：00期间的体育与彩票的有效投注，次日中午12:00:00后点击申请24小时内未点击申请本公司将视为自动放弃本次活动。
3.此优惠需要会员在此活动页面点击【点击获取次数】进行申请，成功获取次数后，红包自动掉落，即可领取红包，领取到的红包金额将自动添加到会员账户！
4.幸运红包一倍流水即可提款。

活动条款：
1.每位玩家，每一地址，每一电子邮箱，每一电话号码，每一微信，相同支付方式（相同借记卡/银行账户）及IP地址只能享有一次申请机会：若有会员重复申请账户行为，公司保留取消或收回会员的优惠彩金权利。
2.本公司的所有优惠特为玩家而设，如发现任何团体或个人，以不诚实方式套取红利或任何威胁，滥用公司优惠等行为，公司保留冻结，取消该团体或个人账户及个人账户结余盈利。
3.若会员对活动有争议时，为了确保双方权益，杜绝身份盗用行为，本公司有权利要求会员向我们提供充足的文件，用以确认是否享有该优惠资质。
4.本公司保留对活动最终解释权；以及在无通知的情况下修改,终止活动的权利（使用于所有的优惠）
 */

//error_reporting(E_ALL);
//ini_set('display_errors','On');
include_once('include/config.inc.php');
$action = $_REQUEST['action']; // appRefer=14&action=extract_lucky_red_envelope , appRefer=14&action=get_valid
$userid = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$UserName = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];

$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'';
$typearr = array('13','14');

$aCp_default = $database['cpDefault'];

//判断终端类型
if(!in_array($appRefer,$typearr)){
    $status = '502.1';
    $describe = '终端参数不正确!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

if( !isset($userid) || $userid == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

if (LUCKY_RED_ENVELOPE_SWITCH===FALSE){
    $status = '401.333';
    $describe = LUCKY_RED_ENVELOPE_CLOSE_MESSAGE;
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

function getUserDetails(){
    global $dbLink, $userid;
    $sql = "select Alias,test_flag,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Account,Bank_Address from  ".DBPREFIX.MEMBERTABLE." where ID='$userid'";
    $res = mysqli_query($dbLink,$sql);
    while ($row = mysqli_fetch_assoc($res)){
        $rows[]=$row;
    }
    return $rows;
}

switch ($action){

    case 'get_valid': // 更新数据（昨日有效金额、可领次数）

        if (LUCKY_RED_ENVELOPE_SWITCH===FALSE){
            $status = '401.33';
            $describe = LUCKY_RED_ENVELOPE_CLOSE_MESSAGE;
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        //$data = get_valid_money_times();
        $data = get_valid_money_times_only_tiyu1314();
        $status = '200';
        $describe = '获取昨日有效金额、可领次数成功';
        original_phone_request_response($status,$describe,$data);
        break;
    case 'extract_lucky_red_envelope': // 领取红包
    case 'receive_red_envelope': // 领取红包

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
                $status = '502.66';
                $describe = '账号分层异常，请联系我们在线客服';
                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
            }
        }

        $curGetTime = date('H:i:s',time());
        if($curGetTime<"03:00:00"){
            $status = '402.00';
            $describe = '请于北京时间每日15:00至次日12:00申请彩金';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        $memberData = getUserDetails();
        $realName =$memberData['Alias'];
        $test_flag = $memberData['test_flag'];
        $agents = $memberData['Agents'];
        $world = $memberData['World'];
        $corprator = $memberData['Corprator'];
        $super = $memberData['Super'];
        $admin = $memberData['Admin'];
        $bank = $memberData['Bank_Name'];
        $bank_account = $memberData['Bank_Account'];
        $bank_address = $memberData['Bank_Address'];

        //$data = get_valid_money_times();
        $data = get_valid_money_times_only_tiyu1314();
        $valid_money = $data[0]['valid_money'];
        $last_times = $data[0]['last_times'];

        // 校验有效投注
        if ($valid_money<1000){
            $status = '401.3';
            $describe = '昨日有效金额'.($valid_money>0?$valid_money:0).'元，有效金额不足不能领取';
//            original_phone_request_response($status,$describe,$data);
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        // 校验可领次数
        if ($last_times == 0){
            $status = '401.4';
            $describe = '可领次数不足不能领取';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        // ------------------------------------------------------------------------------------------抽取红包开始，初始化一些红包池数据Start
        // 总几率100%，根据金额还有几率，生成100个金额
        // 随机在100个值中抽取1个金额，给会员进行派发
        $sql = "select *  from ".DBPREFIX."lucky_red_envelope_config";
        $res = mysqli_query($dbLink,$sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($res)){
            $rows[]=$row;
        }

        $red_envelope_pool = array(); // 红包池，将下面生成的红包金额放入，总共100个
        foreach ($rows as $k => $v){
            $red_envelope_nums = $v['probability']*100;
            $keys = array_keys($red_envelope_pool);
            $last_key = max($keys); // 红包池最后一位key
            $tmp_arr = array_fill($last_key+1, $red_envelope_nums, $v['money']);
            $red_envelope_pool = array_merge($red_envelope_pool , $tmp_arr);
        }

        $rand = rand(0,99); // 0到99随机生成1个数字
        $gold = $red_envelope_pool[$rand]; //要派发给会员的幸运红包金额


        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        if($beginFrom){
            $resultMem = mysqli_query($dbMasterLink,"select ID,Money from  ".DBPREFIX.MEMBERTABLE." where ID='$userid' for update");
            if($resultMem){
                $rowMem = mysqli_fetch_assoc($resultMem);
                $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='$userid'";
                if(mysqli_query($dbMasterLink,$mysql)){

                    $currency_after = $rowMem['Money']+$gold; // 用户充值后的余额
                    $notes='APP幸运红包活动'; // 备注
                    $order_code = date("YmdHis",time()).rand(100000,999999);
                    $AuditDate = date("Y-m-d H:i:s");

                    $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$userid}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$UserName}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$AuditDate',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                    //@error_log($sql.PHP_EOL,  3,  '/tmp/aaa.log');
                    $res = mysqli_query($dbMasterLink,$sql);
                    if ($res) {
                        $sqlBill="insert into ".DBPREFIX."lucky_red_envelope_bill set userid='{$userid}',UserName='{$UserName}',LuckyRedEnvelopeGold='{$gold}',valid_money='$valid_money',BillAddDate='$AuditDate'";
                        $resBill = mysqli_query($dbMasterLink,$sqlBill);
                        if ($resBill){
                            $moneyLogRes = addAccountRecords(array($userid, $UserName, $test_flag, $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, $appRefer, $userid, "[$notes],成功入账"));
                            if ($moneyLogRes) {
                                mysqli_query($dbMasterLink, "COMMIT");
                                $status = '200';
                                $describe = '恭喜，抽取APP幸运红包'.$gold.'元';
                                $data2[0]['data_gold'] = $gold;
                                $data2[0]['balance_hg'] = formatMoney($currency_after);
                                original_phone_request_response($status,$describe,$data2);

                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                $status = '401.10';
                                $describe = '插入用户资金账变记录失败';
                                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                            }
                        }else{
                            $status = '401.9';
                            $describe = '插入会员彩金记录失败';
                            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        $status = '401.8';
                        $describe = '插入800账变记录失败';
                        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $status = '401.7';
                    $describe = '更新会员资金失败';
                    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status = '401.6';
                $describe = '锁定会员资金失败';
                exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $status = '401.5';
            $describe = '事务开启失败';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        // ------------------------------------------------------------------------------------------抽取红包开始，初始化一些红包池数据End

        break;
    default:
        $status = '401.2';
        $describe = '参数有误!';
        exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        break;
}

// 获取有效投注（ 体育——原生1314）、可领取红包次数
function get_valid_money_times_only_tiyu1314(){
    global $aCp_default, $dbLink, $userid, $UserName, $grab_red_envelope_times_level;

    // 昨日有效金额（体育+彩票）
    // --------------------------------------------------------------------------------------------------------昨日有效金额-体育，3点之前捞注单表、3点以后捞历史报表Start
    $h = date('G');

    /*
        if ($h >= 3){
            $res_hg = mysqli_query($dbLink, "select sum(valid_money) as valid_money from ".DBPREFIX."web_report_history_report_data where userid = $userid AND M_Date = '".date('Y-m-d',strtotime('-1 day'))."' AND playSource IN (13,14)");
             $row_hg = mysqli_fetch_assoc($res_hg);
            $hg_valid_money = $row_hg['valid_money'];
        }else{
    */
    $sql_valid_money = "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE M_Name = '$UserName' and M_Date = '".date('Y-m-d',strtotime('-1 day'))."' and M_Result!=0 and Checked = 1 and playSource IN (13,14)";
    $res_hg_valid_money =  mysqli_query($dbLink, $sql_valid_money);
    $row_valid_money = mysqli_fetch_assoc($res_hg_valid_money);
    $hg_valid_money = $row_valid_money['valid_money'];
    //  }
    // --------------------------------------------------------------------------------------------------------昨日有效金额-体育，3点之前捞注单表、3点以后捞历史报表End


    // -------------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表Start
    /*
    $yestoday = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $start_day_cp = strtotime($yestoday);
    $end_day_cp = $start_day_cp + 60*60*24;
    $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
    if ($h >= 2){
        $sql_cp = "select sum(valid_money) as valid_money from gxfcy_history_bill_report_less_12hours where username = '$UserName' AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' ";
        $res_cp = mysqli_query($cpDbLink, $sql_cp);
        $row_cp = mysqli_fetch_assoc($res_cp);
        $cp_valid_money = $row_cp['valid_money'];
    }else{
        $sql_cp = "select sum(valid_money) as valid_money from gxfcy_bill where `count`=1 and username = '$UserName' and bet_time BETWEEN '" . $start_day_cp . "' and '" . $end_day_cp . "' ";
        $res_cp = mysqli_query($cpDbLink, $sql_cp);
        $row_cp = mysqli_fetch_assoc($res_cp);
        $cp_valid_money = $row_cp['valid_money'];
    }
    */
    // ----------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表End

    // 有效投注（体育+彩票）
    //$valid_money = $hg_valid_money+$cp_valid_money;
    $valid_money = $hg_valid_money;

    // -------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽Start
    foreach ($grab_red_envelope_times_level as $k => $v){
        if ($k<9){
            // 有效次数档位从第一档到第九档
            if ($valid_money >= $v['valid_amount'] and $valid_money < $grab_red_envelope_times_level[$k+1]['valid_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
                break;
            }
        }else{
            // 有效次数档位第十档
            if ($valid_money >= $v['valid_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
            }
        }
    }
    // 捞取今天已领取的几次
    $startTime = date('Y-m-d 00:00:00');
    $endTime = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $sql = "select count(1) as cou from ".DBPREFIX."lucky_red_envelope_bill where userid = $userid and BillAddDate between '$startTime' and '$endTime' ";
    $res =  mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_assoc($res);
    $last_times = $grab_red_envelope_times - $row['cou']; // 会员剩余可领次数
    // ------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽End

    $data[0]['valid_money'] = intval($valid_money);
    $data[0]['last_times'] = $last_times>0?$last_times:0;
    return $data;
}

// 获取有效投注（体育+彩票）、可领取红包次数
function get_valid_money_times(){
    global $aCp_default, $dbLink, $userid, $UserName, $grab_red_envelope_times_level;

    // 昨日有效金额（体育+彩票）
    // --------------------------------------------------------------------------------------------------------昨日有效金额-体育，3点之前捞注单表、3点以后捞历史报表Start
    $h = date('G');
    if ($h >= 3){
        $res_hg = mysqli_query($dbLink, "select sum(valid_money) as valid_money from ".DBPREFIX."web_report_history_report_data where userid = $userid AND M_Date = '".date('Y-m-d',strtotime('-1 day'))."' ");
        $row_hg = mysqli_fetch_assoc($res_hg);
        $hg_valid_money = $row_hg['valid_money'];
    }else{
        $sql_valid_money = "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE M_Name = '$UserName' and M_Date = '".date('Y-m-d',strtotime('-1 day'))."' and M_Result!=0 and Checked = 1";
        $res_hg_valid_money =  mysqli_query($dbLink, $sql_valid_money);
        $row_valid_money = mysqli_fetch_assoc($res_hg_valid_money);
        $hg_valid_money = $row_valid_money['valid_money'];
    }
    // --------------------------------------------------------------------------------------------------------昨日有效金额-体育，3点之前捞注单表、3点以后捞历史报表End


    // -------------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表Start
    $yestoday = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $start_day_cp = strtotime($yestoday);
    $end_day_cp = $start_day_cp + 60*60*24;
    $cpDbLink = @mysqli_connect($aCp_default['host'],$aCp_default['user'],$aCp_default['password'],$aCp_default['dbname'],$aCp_default['port']) or die("mysqli connect error".mysqli_connect_error());
    if ($h >= 2){
        $sql_cp = "select sum(valid_money) as valid_money from gxfcy_history_bill_report_less_12hours where username = '$UserName' AND bet_time BETWEEN '".$start_day_cp."' and '".$end_day_cp."' ";
        $res_cp = mysqli_query($cpDbLink, $sql_cp);
        $row_cp = mysqli_fetch_assoc($res_cp);
        $cp_valid_money = $row_cp['valid_money'];
    }else{
        $sql_cp = "select sum(valid_money) as valid_money from gxfcy_bill where `count`=1 and username = '$UserName' and bet_time BETWEEN '" . $start_day_cp . "' and '" . $end_day_cp . "' ";
        $res_cp = mysqli_query($cpDbLink, $sql_cp);
        $row_cp = mysqli_fetch_assoc($res_cp);
        $cp_valid_money = $row_cp['valid_money'];
    }
    // ----------------------------------------------------------------------------------------------------昨日有效金额-彩票，2点之前捞注单表、2点以后捞历史报表End

    // 有效投注（体育+彩票）
    $valid_money = $hg_valid_money+$cp_valid_money;

    // -------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽Start
    foreach ($grab_red_envelope_times_level as $k => $v){
        if ($k<9){
            // 有效次数档位从第一档到第九档
            if ($valid_money >= $v['valid_amount'] and $valid_money < $grab_red_envelope_times_level[$k+1]['valid_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
                break;
            }
        }else{
            // 有效次数档位第十档
            if ($valid_money >= $v['valid_amount']){
                $grab_red_envelope_times = $v['grab_red_envelope_times']; // 可领总次数
            }
        }
    }
    // 捞取今天已领取的几次
    $startTime = date('Y-m-d 00:00:00');
    $endTime = date('Y-m-d 00:00:00',strtotime('+1 day'));
    $sql = "select count(1) as cou from ".DBPREFIX."lucky_red_envelope_bill where userid = $userid and BillAddDate between '$startTime' and '$endTime' ";
    $res =  mysqli_query($dbLink, $sql);
    $row = mysqli_fetch_assoc($res);
    $last_times = $grab_red_envelope_times - $row['cou']; // 会员剩余可领次数
    // ------------------------------------------------------------------------------------------------------- 检查会员领取红包次数是否用尽End

    $data[0]['valid_money'] = $valid_money>0?$valid_money:0;
    $data[0]['last_times'] = $last_times>0?$last_times:0;
    return $data;
}


