<?php
/*
 * 0086  活动规则
 * 1.老玩家在活动开启后，在4号当天,点击(签到)即可领抽取新年红包。
 * 2.玩家在2019年2月4日签到后至2月10日期间，存款金额大于等于1000即可获得红包。
 * 3.可领次数时间次日15:00自动完成统计，次数生成即可点击获取红包！
 * 4.本次红包金额将随机赠送，符合要求的玩家只需点击(领取红包)即可获取红包。
 * 5.本活动所获得彩金完成一倍流水要求即可提款。

    签到规则：
        1.  4号当天签到，手机号必填，注册时间1.31前注册，历史存款大于100，免费获取一次抽红包的次数。
        2.  2.4号-2.10号 任意一天签到，之后提示已签到 。
        3. 用户签到列表后台只有admin 能看。
    新年红包剩余次数：
        1.  次日北京时间15点更新前一天的存款,流水额，对比分别按条件获得昨日抢红包次数。 (美东时间3:00自动显示)
        2.  (比如存款了1000，有了一次，体育流水3000，又有了一次，那这个客户当天就可以有2次红包)
        3.  剩余红包次数 = 存款总次数sum(depositNum) + 流水总次数sum(validNum) + 优惠- 已领取总次数
    领取红包(直接到账):
        1. 次数为0 不满足条件，提示不符合领取
        2. 昨日次数没领，自动累加。10号活动结束，次数清零。
        3. 领取红包成功一次，新春领取红包表记录一次。
 * */
include_once('include/config.inc.php');
$mobile = $_REQUEST['mobile'];
$action = $_REQUEST['action'];
$userid = $_SESSION['userid'];
$AddDate = $_SESSION['AddDate']; // 注册时间
$UserName = $_SESSION['UserName'];
$realName = $_SESSION['Alias'];
$date = date('Y-m-d',time());

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.0';
    $describe = '请重新登录!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
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
        $status = '502.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe,$data);
    }
}

$newYearBeginTime=  mktime(0,0,0,2,4, date('Y'));    //2月4日 北京时间2月4号 12:00
//$newYearBeginTime=  mktime(0,0,0,1,23, date('Y'));    //测试2月23日 北京时间1月23号 12:00
$newYearEndTime = mktime(23,59,59,2,10, date('Y'));  //2月10日 北京时间2月11号 12:00
if(time() < $newYearBeginTime || time() > $newYearEndTime){
    $status = '401.2';
    $describe = '请于美东时间2月4号-10号期间签到领取红包哦!';
    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
}

switch ($action){
    case 'mobilesign':// 签到
        if(!isPhone($mobile)) {  // 验证手机
            $status = '402.1';
            $describe = '手机号码不符合规范!';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        $signInStatus = isSignIn($userid);  // 验证是否签到
        if($signInStatus){
            $status = '402.2';
            $describe = '已经签到，请点击领取红包!';
            exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
        }

        $signData  = ['userid'=>$userid, 'UserName'=>trim($UserName), 'Alias'=>trim($realName), 'mobile'=>intval($mobile),]; // 签到数据

        $result = applyMobileSign($signData); // 添加签到
        if(!$result){
            original_phone_request_response('402.3','异常情况，请重新签到!');
        }

        original_phone_request_response('200','签到成功，请按次数领取红包!');
        break;
    case 'get_remain_num':
        /*
         * 1.  第一次进来请求的接口，是否记录当日红包次数
         *          没有则根据存款金额和体育有效投注返回总红包次数
         * 2.  有则返回剩余次数
         */

        // 当日新春红包次数表是否记录，有则返回
        $record_num_sql = "select ID,userid,UserName,receiveTime from ".DBPREFIX."newyear_red_envelope_num where userid='$userid' and receiveTime = '".date('Y-m-d',time())."'";
        $result = mysqli_query($dbLink,$record_num_sql);
        $recorData = mysqli_fetch_assoc($result);

        $h = date('G');
        if(empty($recorData) && $h >= 3) { //记录当日领取红包次数   3点以后捞前一日存款和流水
            if($date == NEWYEAR_RECEIVE_GIFT_DATA) {   //2019-02-04 只记录免费一次
                $freeNum = isFreeRedEnvelope();
                $remainNum['deposit_money'] = $remainNum['valid_money'] = $remainNum['depositNum'] = $remainNum['validNum'] = 0;
            } else{
                $depositValid = get_deposit_valid_money();   //返回昨日存款金额和体育有效投注
                $remainNum = get_deposit_valid_num($depositValid);   //根据存款金额和体育有效投注返回对应红包总次数
            }
            $remainNum['freeNum'] = $freeNum >0 ? $freeNum : 0;
            $insertResult = insertRedEnvelope($remainNum);// 新春红包次数记录表
        }


        $last_times = get_remain_num();    // 获取剩余总次数
        $data[0]['last_times'] = $last_times;
        $status = '200';
        $describe = '获取剩余次数成功!';
        original_phone_request_response($status,$describe,$data);

        break;
    case 'receive_red_envelope': // 领取红包
        $signInStatus = isSignIn($userid);  // 验证是否签到
        if(!$signInStatus){
	        original_phone_request_response('405.00','请先输入有效手机号进行签到!');
        }

        // 当前红包次数表派发日期
        $sql_num = "select * from ".DBPREFIX."newyear_red_envelope_num where userid='$userid' and `status`!=1 and `currentCountNum` !=0  order by ID ";
        $result = mysqli_query($dbLink,$sql_num);
        $user_red_date_num = mysqli_fetch_assoc($result);
	    $last_date_times = $user_red_date_num['currentCountNum'] - $user_red_date_num['takeNum'];    // 当前派发日期剩余次数
        if($last_date_times <= 0) {
            original_phone_request_response('405.01','不满足领取次数!');
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

            // 红包次数表 先加锁再更新次数与状态
            $depositWaterDate = $user_red_date_num['depositWaterDate'];
            $sql = "select takeNum, `status` from  ".DBPREFIX."newyear_red_envelope_num where userid = $userid and depositWaterDate='$depositWaterDate' for update";
            if(mysqli_query($dbMasterLink,$sql)){

                // 红包次数表根据日期次数 更新状态
                if($last_date_times==1){
                    $re_num_status = 1; // 最后一次 status改为1
                }elseif ($last_date_times>1){
                    $re_num_status = 2; //  领取中
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
            	    original_phone_request_response('405.01','不满足领取次数!');
                }

                $sql = "update ".DBPREFIX."newyear_red_envelope_num set takeNum=takeNum+1, `status`=$re_num_status where userid = $userid and depositWaterDate='$depositWaterDate'";
                if(mysqli_query($dbMasterLink,$sql)){

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
                            $notes='新春红包活动'; // 备注
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
                                $depositMoney = $user_red_date_num['depositMoney'];
                                $validMoney = $user_red_date_num['validMoney'];
                                $sqlBill="insert into ".DBPREFIX."newyear_red_envelope_bill set userid='{$userid}',UserName='{$UserName}',Alias='{$realName}',NewYearRedEnvelopeGold='{$gold}',BillAddDate='$BillAddDate',
                                            depositMoney='$depositMoney',validMoney='$validMoney',depositWaterDate='$depositWaterDate'";
                                $resBill = mysqli_query($dbMasterLink,$sqlBill);
                                if ($resBill){
                                    $moneyLogRes = addAccountRecords(array($userid, $UserName, $test_flag, $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, $_REQUEST['appRefer'], $userid, "[$notes],成功入账"));
                                    if ($moneyLogRes) {
                                        mysqli_query($dbMasterLink, "COMMIT");
					                    $last_times = get_remain_num();    // 获取剩余总次数
                                        $status = '200';
                                        $describe = '恭喜，抽取新春红包'.$gold.'元';
                                        $data2[0]['data_gold'] = $gold;
                                        $data2[0]['last_times'] = $last_times;
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
                    $status = '401.10';
                    $describe = '锁定会员资金失败';
                    exit(json_encode(["status"=>$status,"describe"=>$describe,"data"=>[]],JSON_UNESCAPED_UNICODE));
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
		        $status = '401.11';
            	$describe = '锁定存留日期、次数、状态失败';
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
exit;
//验证是否签到
function isSignIn($userid) {
    global $dbLink;
    $sql = "select * from ".DBPREFIX."newyear_red_envelope_signin where userid='$userid'";
    $result = mysqli_query($dbLink,$sql);
    $returnResult = mysqli_fetch_assoc($result);
    $sign = isset($returnResult) ? true : false;
   return $sign;
}

/**
 * 申请签到
 * @param   $signData
 * @return  boolean
 */
function applyMobileSign($signData) {
    global $dbMasterLink;
    $data = $signData;
    $data['signTime'] = date("Y-m-d H:i:s"); // 签到时间
    $data['created_at'] = date("Y-m-d H:i:s"); // 添加时间
    $data['updated_at'] = date("Y-m-d H:i:s"); // 修改时间
    $data['status'] = 1; //签到状态

    foreach($data as $key=>$val){
        $tmp[]=$key.'=\''.$val.'\'';
    }
    $sql="insert into ".DBPREFIX."newyear_red_envelope_signin set ".implode(',',$tmp);
    $insterid = mysqli_query($dbMasterLink,$sql);
    return $insterid;
}

// 获取体育 昨日存款总额和有效投注
function get_deposit_valid_money(){
    global $dbLink, $userid;

    // ---------------------------------------------------------------------------昨日存款金额1000以上（包含1000）【只捞取快速充值、公司入款、第三方】Start
    $lastDayBegin = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $lastDayEnd = date('Y-m-d 23:59:59', strtotime('-1 day'));
    $ckTime = " and `AddDate`>= '$lastDayBegin' and `AddDate`<= '$lastDayEnd'" ; //查询存款时间
    $sql_deposit_money = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where Checked=1 and Type='S' and userid='$userid' $ckTime and (discounType =9 or Payway='N' or `PayType`>0)";

    $result = mysqli_query($dbLink,$sql_deposit_money);
    $row = mysqli_fetch_assoc($result);
    $deposit_money = $row['Gold'];
    // --------------------------------------------------------------------------昨日存款金额-体育，End


    // ---------------------------------------------------------------------------昨日有效金额-体育，Start
    $sql_valid_money = "select sum(VGOLD) as valid_money from ".DBPREFIX."web_report_data WHERE Userid = '$userid' and M_Date = '".date('Y-m-d',strtotime('-1 day'))."'";

    $res_hg_valid_money =  mysqli_query($dbLink, $sql_valid_money);
    $row_valid_money = mysqli_fetch_assoc($res_hg_valid_money);
    $hg_valid_money = $row_valid_money['valid_money'];
    // 体育有效投注(流水)
    $valid_money = $hg_valid_money;
    // --------------------------------------------------------------------------昨日有效金额-体育，End

    $data['deposit_money'] = sprintf("%.2f",$deposit_money)>0 ? $deposit_money : 0;
    $data['valid_money'] = sprintf("%.2f",$valid_money)>0 ? $valid_money : 0;

    return $data;
}

// 根据存款金额和体育有效投注 分别返回对应红包次数
function get_deposit_valid_num($data){
    global $grab_newyear_red_envelope_times_level;
    $valid_money = $data['valid_money'];
    $deposit_money = $data['deposit_money'];
    //---------------------------------------------------------------------- 存款金额和体育有效投注对应领取红包次数Start
    foreach ($grab_newyear_red_envelope_times_level as $k => $v){
        if ($k<7){
            // 档位从第一档到第六档
            if ($deposit_money >= $v['deposit_amount'] and $deposit_money <$grab_newyear_red_envelope_times_level[$k+1]['deposit_amount']){
                $grab_deposit_red_envelope_times = $v['grab_red_envelope_times']; // 昨日存款可领次数
            }

            if ($valid_money >= $v['valid_amount'] and $valid_money <$grab_newyear_red_envelope_times_level[$k+1]['valid_amount']){
                $grab_valid_red_envelope_times = $v['grab_red_envelope_times']; // 体育流水有效投注可领次数
            }
        }elseif ($k=7){
            // 有效次数档位第七档
            if ($deposit_money >= $v['deposit_amount']){
                $grab_deposit_red_envelope_times = $v['grab_red_envelope_times']; // 昨日存款可领总次数
            }

            if ($valid_money >= $v['valid_amount']){
                $grab_valid_red_envelope_times = $v['grab_red_envelope_times']; // 有效投注可领总次数
            }
        }
    }

    $returndata['deposit_money'] = $deposit_money;
    $returndata['valid_money'] = $valid_money;
    $returndata['grab_deposit_red_envelope_times'] = $grab_deposit_red_envelope_times >0 ? $grab_deposit_red_envelope_times : 0;
    $returndata['grab_valid_red_envelope_times'] = $grab_valid_red_envelope_times >0? $grab_valid_red_envelope_times : 0;

    return $returndata;
    // --------------------------------------------------------------------- 存款金额和体育有效投注对应领取红包次数End
}

// 判断是否享受优惠一次
function isFreeRedEnvelope() {
    global $dbLink, $userid,$AddDate;

    $sql_his_deposit = "select sum(Gold) as Gold from ".DBPREFIX."web_sys800_data where Checked=1 and Type='S' and userid='$userid'  and (discounType =9 or Payway='N' or `PayType`>0)";
    $result = mysqli_query($dbLink,$sql_his_deposit);
    $row = mysqli_fetch_assoc($result);
    $his_deposit = $row['Gold'];
    //注册时间小于等于2019-01-31 23:59:59、历史存款大于100
    $isFree = ($AddDate <= REGISTER_GIFT_TIME && $his_deposit > HISTORY_DEPOSIT) ? 1 :0;
    return $isFree;
}

// 记录新春红包次数表，不存在则插入
function insertRedEnvelope($remainNum) {
    global $dbMasterLink,$userid, $UserName;

    $data['userid'] = $userid;
    $data['UserName'] = $UserName;
    $data['depositMoney'] = $remainNum['deposit_money']; //昨日存款
    $data['validMoney'] = $remainNum['valid_money'];     //昨日有效投注(流水)
    $data['depositNum'] = intval($remainNum['grab_deposit_red_envelope_times']); //昨日存款红包次数
    $data['validNum'] = intval($remainNum['grab_valid_red_envelope_times']); //昨日流水(有效投注)红包次数
    $data['freeNum'] = $remainNum['freeNum'];             // 2月4号是否可以免费领取的次数 0 或者1
    $data['currentCountNum'] = intval($remainNum['grab_deposit_red_envelope_times']) + intval($remainNum['grab_valid_red_envelope_times']) + $remainNum['freeNum']; //昨日共计领取次数
    $data['depositWaterDate'] = date('Y-m-d', strtotime('-1 day'));       // 存款流水日期
    $data['receiveTime'] = date("Y-m-d");       // 会员统计红包次数日期
    $data['created_at'] = date("Y-m-d H:i:s"); // 添加时间
    $data['updated_at'] = date("Y-m-d H:i:s"); // 更新时间

    foreach($data as $key=>$val){
        $tmp[]=$key.'=\''.$val.'\'';
    }
    $sql="INSERT IGNORE INTO ".DBPREFIX."newyear_red_envelope_num set ".implode(',',$tmp);
    $insterid = mysqli_query($dbMasterLink,$sql);
    return $insterid;
}

// 统计会员剩余领取红包次数
function get_remain_num() {
    global $dbMasterLink, $userid;
    // 查询新春活动领取总次数
    $sql_num = "select sum(currentCountNum) as CountNums from ".DBPREFIX."newyear_red_envelope_num where userid='$userid' ";
    $result = mysqli_query($dbMasterLink,$sql_num);
    $sql_num_assoc = mysqli_fetch_assoc($result);

    // 会员已领取次数
    $sql = "select count(1) as cou from ".DBPREFIX."newyear_red_envelope_bill where userid = $userid ";
    $res =  mysqli_query($dbMasterLink, $sql);
    $row = mysqli_fetch_assoc($res);
    $last_times = $sql_num_assoc['CountNums'] - $row['cou'];
    $last_times = $last_times > 0 ? $last_times : 0;
    return $last_times;
}
?>