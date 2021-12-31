<?php
/*
 *  代理新增会员
 * */

include ("../app/agents/include/address.mem.php");
require ("../app/agents/include/config.inc.php");

include_once "../../common/promosCommon.php";

$resdata = array();
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '400.01';
    $describe = '您的登录信息已过期,请重新登录!';
    original_phone_request_response($status,$describe,$resdata);
}
$redisObj = new Ciredis();

$table = DBPREFIX."web_promos_receiveList";
$timeNow = date('Y-m-d H:i:s');
$username=$_SESSION['UserName'];
$uid=$_SESSION['Oid'];
$userlv=$_SESSION['admin_level'] ; // 当前管理员层级

$action = $_REQUEST['action'];
$seachType = $_REQUEST['seachType'];
$type = $_REQUEST['type'];
$seachTxt = $_REQUEST['seachTxt'];
$startTime = $_REQUEST['startTime'];
$endTime = $_REQUEST['endTime'];
$curId = $_REQUEST['curId']; // 需要派发的ID
$page=$_REQUEST["page"];
$prostatus=$_REQUEST["status"];
$checkstatus =$_REQUEST["checkstatus"]; // 派发状态
$swDays = date('Y-m-d',strtotime('-14 day')); // 最多查询半个月数据
// echo $swDays;

if(!$startTime || !$endTime){
    $startTime = $endTime = date('Y-m-d');
}
if($startTime<$swDays){
    //$startTime = $swDays;
    $status = '400.06';
    $describe = '最多查询近半个月数据!';
    original_phone_request_response($status,$describe,$resdata);
}
if($endTime<$swDays){
   // $endTime = $swDays;
    $status = '400.07';
    $describe = '最多查询近半个月数据!';
    original_phone_request_response($status,$describe,$resdata);
}

if ($page==''){
    $page=0;
}

if($userlv !='M'){ // 非管理员
    $status = '400.02';
    $describe = '您没有权限操作!';
    original_phone_request_response($status,$describe,$resdata);
}

$sqlSeach ="`ID`,`UserName`,`Alias`,`Phone`,`bankAccount`,`applyIp`,`name`,`deposit`,`depositDay`,`totalBet`,`profitable`,`eventName`,`promoGold`,`gameType`,`gameTypeDetails`,`add_time`,`review_time`,`gameType` ,`review_name`,`status`";

if($action=='get'){ // 获取数据
    if($seachType=='first'){ // 第一次才需要
        // 查询自动领取活动分类
        $searchWhere = " where category_id='7' and `status`='1'";
        $sql = "SELECT `id`, `title`,`flag` FROM " . DBPREFIX . "web_promos" . $searchWhere . ' ORDER BY `id` DESC';
        $result = mysqli_query($dbLink, $sql);
        while ($row = mysqli_fetch_assoc($result)){
            $resdata['promoType'][] = $row;
        }
    }else{
        $resdata['promoType']=array();
    }

    $curTotal = $allTotal =0; // $curTotal 当前页总计，$curTotal 所有总计
    // 查询会员申请活动数据
    $page_size = 50; //每页展示数量
    $seaWhere = " `add_day` BETWEEN '{$startTime}' AND '{$endTime}' ";
    if($type){
        $seaWhere .= "AND `name`='{$type}' ";
    }
    if($prostatus){
        $seaWhere .= " AND `status`='{$prostatus}' ";
    }
    if($seachTxt){
        $seaWhere .= "AND (`UserName`='{$seachTxt}' or `Alias`='{$seachTxt}' or `Phone`='{$seachTxt}' or `applyIp`='{$seachTxt}'or `bankAccount`='{$seachTxt}') ";
    }
    $seaksql = "SELECT $sqlSeach FROM " . $table . " WHERE $seaWhere order by `add_time` DESC";
    $searesult = mysqli_query($dbLink, $seaksql);
    $seacou=mysqli_num_rows($searesult);
    while ($allsearow = mysqli_fetch_assoc($searesult)){ // 赠送金额 所有总计
        $allTotal += $allsearow['promoGold'];
    }
    $page_count=ceil($seacou/$page_size); // 总页数
    $offset=$page*$page_size;
    $mysql=$seaksql."  limit $offset,$page_size;"; //
    $afresult = mysqli_query($dbLink,$mysql);

    $resdata['total']=$seacou; // 总条目
    $resdata['page_count']=$page_count?$page_count:1; // 总页数

    while ($searow = mysqli_fetch_assoc($afresult)){
        $resdata['promoList'][] = $searow;
        $curTotal += $searow['promoGold'];
    }
    $resdata['curTotal'] = $curTotal;
    $resdata['allTotal'] = $allTotal;
    $status = '200';
    $describe = '查询数据成功!';
    original_phone_request_response($status,$describe,$resdata);

}else if($action=='check'){ // 审核

    $attTime = $redisObj->getSimpleOne('promos_check_flag');
    if($attTime) {
        $allowtime = time()-$attTime;
        if($allowtime<2) { // 5 秒
            $status = '400.03';
            $describe = '短时间内请勿重复操作!';
            original_phone_request_response($status,$describe,$resdata);
        }
    }
// 时间限制
    $redisObj->setOne('promos_check_flag', time());

    if(!$curId){
        $status = '400.10';
        $describe = '没有需要操作的数据!';
        original_phone_request_response($status,$describe,$resdata);
    }
    // 开始派发彩金

    $mysql="select $sqlSeach from ".$table." where ID in ($curId)";
   // echo $mysql;
    $res=mysqli_query($dbLink,$mysql);
    //$rows=@mysqli_fetch_assoc($res);
    while ($allsearow = mysqli_fetch_assoc($res)){
        $promoArr[] = $allsearow;
    }
    $num = 0; // 遍历次数
    foreach ($promoArr as $key => $rows) {
        //var_dump($rows['promoGold']);
        $num++;
        $proName = $rows['eventName'];
        $gold = $rows['promoGold'];  // 派发额度


        if($rows['status'] ==2 && $checkstatus==1) { // 状态：1已派发,2未审核,3不符合，4拒绝
            $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
            $mysql_status=mysqli_query($dbMasterLink,"select ID,userid,status from ".$table." where ID=".$rows['ID']." for update");

            if($beginFrom && $mysql_status) {
                $row_status = mysqli_fetch_assoc($mysql_status);
                if($row_status['status'] == 2) {
                    $resultMem = mysqli_query($dbMasterLink, "select ID,UserName,Money,test_flag,Alias,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Address,Bank_Account from  ".DBPREFIX.MEMBERTABLE." where ID='{$row_status['userid']}' for update");
                    if ($resultMem) {
                        $rowMem = mysqli_fetch_assoc($resultMem);
                        $mysql = "update " . DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='" . $row_status['userid'] . "'";
                        if (mysqli_query($dbMasterLink, $mysql)) {
                            $mysql = "update " . $table . " set status='1',review_time='$timeNow',review_name='$username' where ID=" . $rows['ID'];
                            $promotionResult = mysqli_query($dbMasterLink, $mysql);
                            if($promotionResult){ // 派发成功，插入至帐变表，以便查看会员存款查询
                                $currency_after = $rowMem['Money']+$gold; // 用户充值后的余额
                                $agents=$rowMem['Agents'];
                                $world=$rowMem['World'];
                                $corprator=$rowMem['Corprator'];
                                $super=$rowMem['Super'];
                                $admin=$rowMem['Admin'];
                                $getday= date("Y-m-d H:i:s",time());
                                $realName = $rowMem['Alias'];
                                $notes=$proName; // 备注
                                $bank = $rowMem['Bank_Name'];
                                $bank_account=$rowMem['Bank_Account'];
                                $bank_address=$rowMem['Bank_Address'];
                                $order_code = date("YmdHis",time()).rand(100000,999999);
                                $AuditDate = date("Y-m-d H:i:s",time());
                                $test_flag=$rowMem['test_flag'];
                                $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$rowMem['ID']}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$rows['UserName']}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                                $res = mysqli_query($dbMasterLink,$sql);

                                if ($res) {
                                    $moneyLogRes = addAccountRecords(array($rowMem['ID'], $rows['UserName'], $rowMem['test_flag'], $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, 6, $rows['ID'], "[$proName]活动礼金,成功入账"));
                                    if ($moneyLogRes) {
                                        mysqli_query($dbMasterLink, "COMMIT");

                                        $loginfo_status = '<font class="red">成功</font>' ;
                                        $loginfo = $username.' 对会员帐号 <font class="green">'.$rows['UserName'].'</font> '.$proName.'操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['ID'].'</font>' ;
                                        innsertSystemLog($username,$userlv,$loginfo);

                                    } else {
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                        $status = '400.04';
                                        $describe = '添加账变日志失败!';
                                        original_phone_request_response($status,$describe,$resdata);
                                    }
                                } else {
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    $status = '400.05';
                                    $describe = '账变记录插入失败!';
                                    original_phone_request_response($status,$describe,$resdata);
                                }
                            }else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        } else {
                            mysqli_query($dbMasterLink, "ROLLBACK");
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                    }
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
            }

        }else if($rows['status'] ==3 || $checkstatus==4){ // 不符合条件，失败
            $mysql = "update " . $table . " set status=4,review_time='$timeNow',review_name='$username' where ID =".$rows['ID'];

            mysqli_query($dbMasterLink, $mysql);
            $loginfo_status = '<font class="red">失败</font>' ;

            $loginfo = $username.' 对会员帐号 <font class="green">'.$rows['UserName'].'</font> '.$proName.'操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['ID'].'</font>' ;
            innsertSystemLog($username,$userlv,$loginfo);
            if($num==count($promoArr)){
                $status = '200';
                $describe = '已拒绝派发彩金!';
                original_phone_request_response($status,$describe,$resdata);
            }

        }

    }

    $status = '200';
    $describe = '派发成功!';
    original_phone_request_response($status,$describe,$resdata);

}
