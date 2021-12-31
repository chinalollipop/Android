<?php
session_start();
include("../include/address.mem.php");
require("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

//print_r($_REQUEST); die;
$loginname = $_SESSION['UserName'];
$username = $_REQUEST['username'] ? $_REQUEST['username'] : '';
$action=$_REQUEST['action'];
$status = $_REQUEST['status'];
$lv=$_REQUEST["lv"];

// 派发彩金
if($action == 'send'){

    $date = date('Y-m-d H:i:s');
    $att_id = $_REQUEST['ID'];
    $mysql="select ID,userid,UserName,RealName,GiftGold,IP,MemAddDate,status from ".DBPREFIX."download_app_gift_bill where ID=".$att_id;
    $res=mysqli_query($dbLink,$mysql);
    $rows=@mysqli_fetch_assoc($res);
    $gold = $rows['GiftGold'];  //派发彩金

    if ($status==1) {
        switch ($rows['status']) {
            case 1:
                echo "<script>alert('不要重复派发');</script>";
                break;
            case 2:
                $beginFrom = mysqli_query($dbMasterLink, "start transaction");    //开启事务$from
                if ($beginFrom) {
                    $mysql_status = mysqli_query($dbMasterLink, "select ID,userid,status from " . DBPREFIX . "download_app_gift_bill where ID=" . $att_id . " for update");
                    if ($mysql_status) {
                        $row_status = mysqli_fetch_assoc($mysql_status);
                        if ($row_status['status'] == 2) {
                            $resultMem = mysqli_query($dbMasterLink, "select ID,UserName,Money,test_flag,Alias,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Address,Bank_Account from  " . DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
                            if ($resultMem) {
                                $rowMem = mysqli_fetch_assoc($resultMem);
                                $mysql = "update " . DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='" . $rows['userid'] . "'";
                                if (mysqli_query($dbMasterLink, $mysql)) {
                                    $mysql = "update " . DBPREFIX . "download_app_gift_bill set status=1,review_time='$date',review_name='$loginname' where ID=" . $rows['ID'];
                                    $promotionResult = mysqli_query($dbMasterLink, $mysql);
                                    if ($promotionResult) { // 派发成功，插入至帐变表，以便查看会员存款查询

                                        $currency_after = $rowMem['Money'] + $gold; // 用户充值后的余额
                                        $agents = $rowMem['Agents'];
                                        $world = $rowMem['World'];
                                        $corprator = $rowMem['Corprator'];
                                        $super = $rowMem['Super'];
                                        $admin = $rowMem['Admin'];
                                        $getday = date("Y-m-d H:i:s", time());
                                        $realName = $rowMem['Alias'];
                                        $notes = '下载APP免费领取彩金'; // 备注
                                        $bank = $rowMem['Bank_Name'];
                                        $bank_account = $rowMem['Bank_Account'];
                                        $bank_address = $rowMem['Bank_Address'];
                                        $order_code = date("YmdHis", time()) . rand(100000, 999999);
                                        $AuditDate = date("Y-m-d H:i:s", time());
                                        $test_flag = $rowMem['test_flag'];
                                        $sql = "insert into `" . DBPREFIX . "web_sys800_data` set userid='{$rowMem['ID']}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='" . date("Y-m-d", time()) . "',Type='S',UserName='{$rows['UserName']}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                                        //@error_log($sql.PHP_EOL,  3,  '/tmp/aaa.log');
                                        $res = mysqli_query($dbMasterLink, $sql);
                                        if ($res) {
                                            $moneyLogRes = addAccountRecords(array($rowMem['ID'], $rows['UserName'], $rowMem['test_flag'], $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, 6, $rows['ID'], "[$notes],成功入账"));
                                            if ($moneyLogRes) {
                                                mysqli_query($dbMasterLink, "COMMIT");
                                                echo "<script>alert('派发成功!');</script>";
                                            } else {
                                                mysqli_query($dbMasterLink, "ROLLBACK");
                                                echo "<script>alert('插入用户资金账变记录失败');</script>";
                                            }
                                        } else {
                                            mysqli_query($dbMasterLink, "ROLLBACK");
                                            echo "<script>alert('插入会员彩金记录失败');</script>";
                                        }
                                    } else {
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                        echo "<script>alert('更新彩金订单状态失败');</script>";
                                    }
                                } else {
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    echo "<script>alert('更新会员资金失败');</script>";
                                }
                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                echo "<script>alert('锁定会员资金失败');</script>";
                            }
                        } else {
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            echo "<script>alert('订单重复处理');</script>";
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        echo "<script>alert('订单状态锁定失败');</script>";
                    }
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    echo "<script>alert('事务开启失败');</script>";
                }
                break;
            case 4:
                echo "<script>alert('该用户不符合条件已拒绝，不要重复派发!');</script>";
                break;
        }
        $loginfo_status = '<font class="red">成功</font>' ;

    }elseif($status==4){
        $mysql = "update " . DBPREFIX . "download_app_gift_bill set status=4,review_time='$date',review_name='$loginname' where ID=" . $rows['ID'];
        mysqli_query($dbMasterLink, $mysql);
        $loginfo_status = '<font class="red">失败</font>' ;
        echo "<script>alert('该用户不符合条件，拒绝成功!');</script>";
    }
    $loginfo = $loginname.' 对会员帐号 <font class="green">'.$rows['UserName'].'</font> VIP晋升彩金操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['ID'].'</font>' ;
}


$sWhere = 1;
$username != '' ? $sWhere .= " AND `username` = '$username' " : '';
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 00:00:00', strtotime('+1 day'));
    $sWhere .=" and BillAddDate between '{$date_s}' and '{$date_e}'";
}else{
    $sWhere .=" and BillAddDate between '{$date_s}' and '{$date_e}'";
}
$sql = "select *  from ".DBPREFIX."download_app_gift_bill where $sWhere order by ID DESC";
//echo $sql; die;
$res = mysqli_query($dbLink,$sql);
$gold_total=0;
$num=0;
$page_size=50;
$page=$_REQUEST['page'];
$data=[];

while ($row = mysqli_fetch_assoc($res)) {
    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
        $data[]=$row;
    }
    $num+=1;
    $gold_total += $row['GiftGold'];
}
$cou=$num;
$page_count=ceil($cou/$page_size);


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>APP老会员领取彩金记录</title>
    <style>
        .main-nav dt{ width: 180px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>APP老会员领取彩金记录</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $username;?>" maxlength=20 class="za_text">
                        <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                        共<?php echo $cou?>条
                        <select name='page' onChange="self.myFORM.submit()">
                            <?php
                            if ($page_count==0){
                                $page_count=1;
                            }
                            for($i=0;$i<$page_count;$i++){
                                if ($i==$page){
                                    echo "<option selected value='$i'>".($i+1)."</option>";
                                }else{
                                    echo "<option value='$i'>".($i+1)."</option>";
                                }
                            }
                            ?>
                        </select> 共<?php echo $page_count?> 页
                    </td>
                </tr>

            </table>
        </form>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <tr class="m_title">
            <td>领取时间</td>
            <td>代理商</td>
            <td>会员账号</td>
            <td>真实姓名</td>
            <td>彩金额度（元）</td>
            <td>会员申请IP</td>
            <td>会员注册时间</td>
            <td>会员存款总额</td>
            <td>审核人</td>
            <td>审核时间</td>
            <td>状态</td>
            <td><font color="red">操作</font></td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="11">没有记录</td></tr>';
        }else{

            foreach ($data as $k => $v){?>
                <tr class="m_rig">
                    <td><?php echo $v['BillAddDate']?></td>
                    <td><?php echo $v['Agents']?></td>
                    <td><?php echo $v['UserName']?></td>
                    <td><?php echo $v['RealName']?></td>
                    <td><?php echo $v['GiftGold']?></td>
                    <td><?php echo $v['IP']?></td>
                    <td><?php echo $v['MemAddDate']?></td>
                    <td><?php echo $v['DepositTotal']?></td>
                    <td><?php echo $v['review_name']?></td>
                    <td><?php echo $v['review_time']?></td>
                    <?php

                    switch ($v['status']){
                        case 1:
                            echo '<td>已派发</td>';
                            echo '<td align="center" ></td>';
                            break;
                        case 2:
                            echo '<td>未审核</td>';
                            echo '<td align="center" >';
                            echo '<a href="download_app_gift_bill.php?&ID='.$v['ID'].'&lv='.$lv.'&action=send&status=1"><font color="blue">派发</font>&nbsp;&nbsp;';
                            echo '<a href="download_app_gift_bill.php?&ID='.$v['ID'].'&lv='.$lv.'&action=send&status=4"><font color="blue">拒绝</font>';
                            echo '</td>';
                            break;
                        case 4:
                            echo '<td>已拒绝</td>';
                            echo '<td align="center" ></td>';
                            break;
                        default:
                            echo '<td></td>';
                            echo '<td></td>';
                            break;
                    }

                    ?>


                </tr>

                <?php
            }
        }

        ?>

        <tr class="m_rig2">
            <td colspan="2"><?php echo date("Y-m-d",strtotime($date_s)).'&nbsp;到&nbsp;'.date("Y-m-d",strtotime($date_e)); ?></td>
            <td colspan="2">总计：<?php echo sprintf("%01.2f", $gold_total)?></td>
            <td colspan="7"></td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</body>
</html>
<!-- 插入系统日志 -->
<?php
if ($action=='send'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>
