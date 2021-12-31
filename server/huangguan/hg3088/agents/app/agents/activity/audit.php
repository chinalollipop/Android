<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
$page=$_REQUEST['page'];
$lv=$_REQUEST["lv"];
$action = $_REQUEST['action'];
$date=date('Y-m-d H:i:s');

// 派发奖金
if(!empty($action) && $action == 'send'){
    $att_id = $_REQUEST['ID'];
    $mysql="select ID,userid,UserName,cashBack,status from ".DBPREFIX."web_attendance where ID=".$att_id;
    $rs=mysqli_query($dbLink,$mysql);
    $rows=@mysqli_fetch_assoc($rs);
    $gold = $rows['cashBack'];  //领取金额
    if($rows['status'] ==2) { //状态：1已派发,2未审核,3不符合
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $mysql_status=mysqli_query($dbMasterLink,"select ID,userid,status from ".DBPREFIX."web_attendance where ID=".$att_id." for update");
        if($beginFrom && $mysql_status) {
            $row_status = mysqli_fetch_assoc($mysql_status);
            if($row_status['status'] == 2) {
                $resultMem = mysqli_query($dbMasterLink, "select ID,UserName,Money,test_flag,Alias,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Address,Bank_Account from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
                if ($resultMem) {
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    $mysql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='" . $rows['userid'] . "'";
                    //echo $mysql;echo '<br>';
                    if (mysqli_query($dbMasterLink, $mysql)) {
                        $mysql = "update ".DBPREFIX."web_attendance set status=1,distribute_time='$date' where ID=" . $rows['ID'];
                        $auditResult = mysqli_query($dbMasterLink, $mysql);
                        if($auditResult){
                            $currency_after = $rowMem['Money']+$gold; // 用户充值后的余额
                            $agents=$rowMem['Agents'];
                            $world=$rowMem['World'];
                            $corprator=$rowMem['Corprator'];
                            $super=$rowMem['Super'];
                            $admin=$rowMem['Admin'];
                            $getday= date("Y-m-d H:i:s",time());
                            $realName = $rowMem['Alias'];
                            $notes='全勤回馈奖'; // 备注
                            $bank = $rowMem['Bank_Name'];
                            $bank_account=$rowMem['Bank_Account'];
                            $bank_address=$rowMem['Bank_Address'];
                            $order_code = date("YmdHis",time()).rand(100000,999999);
                            $AuditDate = date("Y-m-d H:i:s",time());
                            $test_flag=$rowMem['test_flag'];
                            $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$rowMem['ID']}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$rows['UserName']}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                            //@error_log($sql.PHP_EOL,  3,  '/tmp/aaa.log');
                            $res = mysqli_query($dbMasterLink,$sql);
                            if ($res) {
                                $moneyLogRes = addAccountRecords(array($rowMem['ID'], $rows['UserName'], $rowMem['test_flag'], $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, 6, $rows['ID'], "[全勤奖回馈]存款审核,成功入账"));
                                if ($moneyLogRes) {
                                    mysqli_query($dbMasterLink, "COMMIT");
                                    echo "<script>alert('派发成功!');</script>";
                                } else {
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                }
                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        }else{
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
        $loginfo_status = '<font class="red">成功</font>';
    } elseif($rows['status'] ==3){ // 不符合条件，失败
        $loginfo_status = '<font class="red">失败</font>';
        echo "<script>alert('该用户不符合条件，无法派发回馈!');top.location.href='javascript:history.go( -1 );';</script>";
        //echo "<script>alert('该用户不符合条件，无法派发回馈!');</script>";
    }
    $loginfo = $loginname.' 对会员帐号 <font class="green">'.$rows['UserName'].'</font> 入款全勤奖操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['ID'].'</font>' ;
}

$sWhere = 1;
$search_name=$_REQUEST['username']; // 查找会员账号
$year=$_REQUEST['year'];
$month=$_REQUEST['month'];

if (empty($year) && empty($month)){  // 默认当前年月
    $year = date('Y');
    $month= date('m');
}
$date = $year.'-'.$month.'-01';
$date_s = date('Y-m-01', strtotime($date));
$date_e = date('Y-m-d', strtotime("$date_s +1 month"));
$sWhereTime .=" and add_time between '{$date_s}' and '{$date_e}'";

$search_status = $_REQUEST['status'];
if(!empty($search_status)) { // 用户状态：1已派发,2未审核,3不符合
    $sWhere .= " and status = $search_status";
}else{ //查找全部
    $search_status='';
}

if ($search_name==''){ // 会员账号
    $mem="";
}else{
    $mem="and UserName='$search_name'";
}
//select * from `hgty78_web_attendance` where 1   order by `ID` asc
$sql="select * from `".DBPREFIX."web_attendance` where $sWhere $sWhereTime $mem order by `add_time` DESC";
// print_r($sql);
$result = mysqli_query($dbLink,$sql);
$totalCount=0;
while ($row = mysqli_fetch_array($result)) {
    if($row['status']==1) {
        $totalCount+= $row['cashBack']; //全部页总计
    }
}
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
if ($page==''){
    $page=0;
}
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink,$mysql);
if ($cou==0){
    $page_count=1;
}
?>
<html>
<head>
    <title>全勤签到审核</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>全勤签到</dt>
    <dd></dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title"><td colspan="10"><b>全勤签到回馈金审核及记录</b></td></tr>
        <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
        <tr>
            <td colspan="10">
                <!--注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD 00:00:00'})" size=12 maxlength=10 class="za_text" readonly>
                至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD 23:59:59'})" size=12 maxlength=10 class="za_text" readonly>-->
                查询年份：
                <select name="year" id="type" class="za_select za_select_auto" onchange="">
                    <?php
                    $minYear = date('Y')-3;
                    for($i=$minYear;$i<date('Y')+1;$i++){
                        if ($i==$year){
                            echo "<option selected value='$i'>".$i."</option>";
                        }else{
                            echo "<option value='$i'>".$i."</option>";
                        }
                    }
                    ?>
                </select>
                查询月份：
                <select name="month" id="type" class="za_select za_select_auto" onchange="">
                    <?php
                    for($i=1;$i<13;$i++){
                        if ($i==$month){
                            echo "<option selected value='$i'>".$i."</option>";
                        }else{
                            echo "<option value='$i'>".$i."</option>";
                        }
                    }
                    ?>
                </select>
                用户状态：
                <select name="status" id="type" class="za_select za_select_auto" onchange="">
                    <option value="0" <?php if($search_status ==0){ echo 'selected';}?>>全部</option>
                    <option value="1" <?php if($search_status ==1){ echo 'selected';}?>>已派发</option>
                    <option value="2" <?php if($search_status ==2){ echo 'selected';}?>>未审核</option>
                    <option value="3" <?php if($search_status ==3){ echo 'selected';}?>>不符合</option>
                </select>

                会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $search_name;?>" maxlength=20 class="za_text">
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
        </FORM>

        <tr class="m_title">
            <td>序号</td>
            <td>用户</td>
            <td>下注总额</td>
            <td>充值天数</td>
            <td>领取金额</td>
            <td>提交时间</td>
            <td>发放时间</td>
            <td>状态</td>
            <td><font color="red">一键通过(未审核)</font></td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="10">没有记录</td></tr>';
        }
        $i=1;
        while($row=@mysqli_fetch_assoc($result)){
        if($row['status'] == 1)
            $pageCount += $row['cashBack'];
        ?>
        <tr class="m_title">
            <td align="center" ><?php echo $i; ?></td>
            <td align="center"><?php echo $row['UserName']; ?></td>
            <td align="center" ><?php echo sprintf("%01.0f",$row['totalBet']); ?></td>
            <td align="center" ><?php echo $row['rechargeDay']; ?></td>
            <td align="center"><?php echo $row['cashBack']; ?></td>
            <td align="center" class="add_time"><?php echo $row['add_time']; ?></td>
            <?php
            //0未审核,1已派发,2不符合'
            if ($row['status'] == 2) {
                echo '<td align="center" ></td>';
                echo '<td align="center" >未审核</td>';
                echo '<td align="center" ><a href="audit.php?uid='.$uid.'&langx='.$langx.'&ID='.$row['ID'].'&lv='.$lv.'&action=send"><font color="blue">恢复并派发</font></td>';
            }elseif($row['status'] == 1){
                echo '<td align="center" >'.$row['distribute_time'].'</td>';
                echo '<td align="center" >已派发</td>';
                echo '<td align="center" ></td>';
            }elseif($row['status'] == 3){
                echo '<td align="center" ></td>';
                echo '<td align="center" >不符合</td>';
                echo '<td align="center"><a href="audit.php?uid='.$uid.'&langx='.$langx.'&ID='.$row['ID'].'&lv='.$lv.'&action=send"><font color="blue">恢复并派发</font></a></td>';
            }
            ?>

        <?php
        $i=$i+1;
        }
        ?>
        </tr>

        <tr class="m_rig2">
            <td colspan="2" >统计</td>
            <td colspan="4" class="red">当前页总计 : <?php echo sprintf("%01.2f", $pageCount)?> </td>
            <td colspan="5" class="red">全部页总计 : <?php echo sprintf("%01.2f", $totalCount)?> </td>
        </tr>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</html>
    <!-- 插入系统日志 -->
<?php
if ($action=='send'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>
