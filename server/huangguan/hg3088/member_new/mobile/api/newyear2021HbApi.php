<?php
/*
 * 活动：2021 新年新气象
1.剩余时间是活动结束时间2.12号24点为截至计算。期间的这个时间显示时分秒。
2.剩余天数也是按照2.12号活动结束日期天数。
  申请时间北京时间2021年 02.11日（除夕）00:00-02.12日24:00开始，活动时间持续48小时，只能领取其中一个红包。
3.会员注册存款次数必须10次以上才有领取资格。
4，红包金额18>18888，这个是给会员看的。实际的领取红包金额，待会发一份表格给你。


输赢额0--- -100     设置8元
输赢额-100--- -1000  设置28元
输赢额-1000--- -1万  设置58元
输赢额-1万--- -5万   设置88元
输赢额-5万--- -10万  设置108元
输赢额-10万--- -50万   设置188元
输赢额-50万--- -300万  设置688元
输赢额-300万以上   设置18888元

输赢额0---100     设置8元
输赢额100---1000  设置28元
输赢额1000---1万  设置58元
输赢额1万---5万   设置88元
输赢额5万---10万  设置108元
输赢额10万---50万   设置188元
输赢额50万---300万  设置688元
输赢额300万以上   设置18888元

5 仅限注册时间为：2021-1月31日之前注册的会员领取
 **/

session_start();
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

$user_id = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$username = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'];

if(!$user_id) {
    $status = '502.2';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
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
        $status = '400.66';
        $describe = '账号分层异常，请联系我们在线客服';
        original_phone_request_response($status,$describe);
    }
}

switch ($_REQUEST['action']){
    case 'getGrabTimes':

        $last_times = getGrabTimes();

        $data['lastTimes'] = $last_times;
        $status = '200';
        $describe = '';
        original_phone_request_response($status,$describe,$data);

        break;
    case 'receive_red_envelope':
        // 活动申请时间为  美东时间2021-02-10 12:00 - 2021-02-12 12:00  同：北京时间2021-02-11 00:00 - 2021-02-12 24:00
        $sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
        $aRedPocketset = json_decode($sRedPocketset,true) ;
        $newYearBeginTime = isset($aRedPocketset['newYearBeginTime'])?$aRedPocketset['newYearBeginTime']:'2021-02-11 00:00:00';
        $newYearEndTime = isset($aRedPocketset['newYearEndTime'])?$aRedPocketset['newYearEndTime']:'2021-02-12 24:00:00';

        $curtime = date("Y-m-d H:i:s",time()+12*60*60);

        if($curtime < $newYearBeginTime || $curtime > $newYearEndTime){
            $status = '401.2';
            $describe = '请于北京时间'.$newYearBeginTime.'至'.$newYearEndTime.'期间领取红包哦!';
            original_phone_request_response($status,$describe);
        }

        // 校验可领次数
        $last_times = getGrabTimes();
        if ($last_times == 0){
            $status = '401.4';
            $describe = '可领次数不足不能领取';
            original_phone_request_response($status,$describe);
        }

        // 查询会员输赢  WinLossCredit  负数会员赢， 正数会员输
        $sql = "SELECT test_flag,DepositTimes,WinLossCredit,AddDate FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$user_id' AND Status<2 ";
        $result = mysqli_query($dbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);
        if ($cou==0){
            $status = '401.5';
            $describe = '账号异常!';
            original_phone_request_response($status,$describe);
        }

        // 检查会员注册时间：所有注册在2021年1月31日前，包含1月31日
        if ($row['AddDate'] > '2021-01-31 23:59:59' ){
            $status = '401.3';
            $describe = '抱歉，您的账号注册时间超过1月31号，无法领取!';
            original_phone_request_response($status,$describe);
        }

        if ($row['DepositTimes'] < 10){ // 没有存款次数
            $status = '401.51';
            $describe = '存款次数必须10次以上!';
            original_phone_request_response($status,$describe);
        }

        $giftGold = goldLevel($row['WinLossCredit']);   // 根据输赢额返回红包额度


        // 入库存款彩金表，等待审核
        $now = date('Y-m-d H:i:s');
        $bj_now = date('Y-m-d H:i:s', time()+12*60*60);
        $insertData = [
            'userid' => $user_id,
            'username' => trim($username),
            'EventName' => '2021新年活动彩金',
            'registered_at' => $row['AddDate'],
            'DepositTimes' => $row['DepositTimes'],
            'WinLossCredit' => $row['WinLossCredit'],
            'gift_gold' => $giftGold,
            'status' => 2, // 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
            'created_at' => $now,
            'bj_created_at' => $bj_now,
            'updated_at' => $now,
        ];
        foreach($insertData as $key => $val){
            $tmp[] = $key.'=\''.$val.'\'';
        }
        $sql = "INSERT INTO `" . DBPREFIX . "newyear_2021_hb` SET " . implode(',', $tmp);
        //echo $sql; die;
        if (!$inserted = mysqli_query($dbMasterLink, $sql)) {
            $status = '500.6';
            $describe = '系统繁忙，请稍后再试吧!';
            original_phone_request_response($status,$describe);
        }else{
            $resdata = array('giftGold'=>$giftGold);
            $status = '200';
            $describe = '领取成功、系统自动派发。';
            original_phone_request_response($status,$describe,$resdata);
        }

        break;
        default: break;
}

/**
 * 获取可领取红包次数，活动期间领取一次，领取过不在领取
 * @return int
 */
function getGrabTimes(){
    global $dbLink, $user_id;
    $check_att_sql = "select * from ".DBPREFIX."newyear_2021_hb where userid='$user_id'";
    $checkresult = mysqli_query($dbLink,$check_att_sql);
    $row = mysqli_fetch_assoc($checkresult);
    $cou=mysqli_num_rows($checkresult);
    //$last_times = !empty($row) ? 0 : 1 ;
    if($cou) {
        $last_times = 0;
    }else{
        $last_times = 1;
    }

    return $last_times;
}

/**
 * 根据输赢额返回红包金额
 *
 * @param $numBets
 * @return mixed
 */
function goldLevel($numBets){
    global $dbLink;
    $result_newyear= mysqli_query($dbLink, "select `level`,`left_interval`,`right_interval`,`giftGold` from ".DBPREFIX."newyear_2021_hb_config order by level asc");
    $cou = mysqli_num_rows($result_newyear);
    if ($cou>0){
        $newConfigData = [];
        while ($row = mysqli_fetch_assoc($result_newyear)){
            $newConfigData[] = $row;
        }
    }

    foreach ($newConfigData as $k => $v){
        //if ( bccomp($v['total'], $v1['left_interval'], 4) > -1 && bccomp($v['total'], $v1['right_interval'], 4) == -1){
        if ( $numBets >= $v['left_interval']  && $numBets < $v['right_interval']){
            $gold = $v['giftGold'];
            break;
        }
    }

    return $gold;
}