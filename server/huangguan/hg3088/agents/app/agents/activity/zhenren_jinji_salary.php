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

$loginname=$_SESSION['UserName'];
$page=$_REQUEST['page'];
$lv=$_REQUEST["lv"];
$action = $_REQUEST['action'];
$status = $_REQUEST['status'];
$date=date('Y-m-d H:i:s');
$competence=$_SESSION['Competence'];
$competence_num=explode(",",$competence);


// 派发奖金
if($action == 'send'){
    $att_id = $_REQUEST['id'];
    $mysql="select id,userid,username,gift_gold,status from ".DBPREFIX."zhenren_jinji_salary where ID=".$att_id;
    $rs=mysqli_query($dbLink,$mysql);
    $rows=@mysqli_fetch_assoc($rs);
    $gold = $rows['gift_gold'];

    if ($status == 1) {
        if ($rows['status'] == 2) { //状态：1已派发,2未审核,3不符合
            $beginFrom = mysqli_query($dbMasterLink, "start transaction");    //开启事务$from
            $mysql_status = mysqli_query($dbMasterLink, "select id,userid,status from " . DBPREFIX . "zhenren_jinji_salary where ID=" . $att_id . " for update");

            if ($beginFrom && $mysql_status) {
                $row_status = mysqli_fetch_assoc($mysql_status);
                if ($row_status['status'] == 2) {
                    $resultMem = mysqli_query($dbMasterLink, "select ID,UserName,Money,test_flag,Alias,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Address,Bank_Account from  " . DBPREFIX . MEMBERTABLE . " where ID='{$rows['userid']}' for update");
                    if ($resultMem) {
                        $rowMem = mysqli_fetch_assoc($resultMem);
                        $mysql = "update " . DBPREFIX . MEMBERTABLE . " set Money=Money+$gold where ID='" . $rows['userid'] . "'";
                        if (mysqli_query($dbMasterLink, $mysql)) {
                            $mysql = "update " . DBPREFIX . "zhenren_jinji_salary set status=1,audited_at='$date',auditor='$loginname' where id=" . $rows['id'];
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
                                $notes = '真人晋级礼金'; // 备注
                                $bank = $rowMem['Bank_Name'];
                                $bank_account = $rowMem['Bank_Account'];
                                $bank_address = $rowMem['Bank_Address'];
                                $order_code = date("YmdHis", time()) . rand(100000, 999999);
                                $AuditDate = date("Y-m-d H:i:s", time());
                                $test_flag = $rowMem['test_flag'];
                                $sql = "insert into `" . DBPREFIX . "web_sys800_data` set userid='{$rowMem['ID']}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='" . date("Y-m-d", time()) . "',Type='S',UserName='{$rowMem['UserName']}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                                $res = mysqli_query($dbMasterLink, $sql);

                                if ($res) {
                                    $moneyLogRes = addAccountRecords(array($rowMem['ID'], $rows['username'], $rowMem['test_flag'], $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, 6, $rows['id'], "[真人晋级礼金]存款审核,成功入账"));
                                    if ($moneyLogRes) {
                                        mysqli_query($dbMasterLink, "COMMIT");
                                        echo "<script>alert('派发成功!');</script>";
                                    } else {
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
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                    }
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                }
            } else {
                mysqli_query($dbMasterLink, "ROLLBACK");
            }
            $loginfo_status = '<font class="red">成功</font>';

        }
        elseif ($rows['status'] == 3) { // 不符合条件，失败
            $mysql = "update " . DBPREFIX . "zhenren_jinji_salary set status=4,audited_at='$date',auditor='$loginname' where id=" . $rows['id'];
            mysqli_query($dbMasterLink, $mysql);
            $loginfo_status = '<font class="red">失败</font>';
            echo "<script>alert('该用户不符合条件，无法派发回馈!');</script>";
        }
    }
    elseif ($status == 4) {
        $mysql = "update " . DBPREFIX . "zhenren_jinji_salary set status=4,audited_at='$date',auditor='$loginname' where id=" . $rows['id'];
        mysqli_query($dbMasterLink, $mysql);
        $loginfo_status = '<font class="red">失败</font>';
        echo "<script>alert('该用户不符合条件，拒绝成功!');</script>";
    }
    $loginfo = $loginname.' 对会员帐号 <font class="green">'.$rows['username'].'</font> 真人晋级礼金操作为'.$loginfo_status.',金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['id'].'</font>' ;
}

$sWhere = 1;
$search_name=$_REQUEST['username']; // 查找会员账号

$search_status = $_REQUEST['status'];
if($search_status) { // 用户状态：1审核通过,2未审核,3已拒绝
    $sWhere .= " and status = $search_status";
}else{ //查找全部
    $search_status='';
}

if ($search_name==''){ // 会员账号
    $mem="";
}else{
    $mem="and username='$search_name'";
}

$sql="select * from `".DBPREFIX."zhenren_jinji_salary` where $sWhere $sWhereTime $mem order by `id` desc";
$result = mysqli_query($dbLink,$sql);
$totalCount=0;
while ($row = mysqli_fetch_array($result)) {
    if($row['status']==1) {
        $totalCount+= $row['gift_gold']; //全部页总计
    }
}
$cou=mysqli_num_rows($result);
$page_size=100;
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
$aData = array();
while($row=mysqli_fetch_assoc($result)){
    $aData[]=$row;
}

?>
<html>
<head>
    <title>真人晋级礼金</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>真人晋级礼金</dt>
    <dd></dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title"><td colspan="16"><b>真人晋级礼金</b></td></tr>
        <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
            <tr>
                <td colspan="16">
                    会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $search_name;?>" maxlength=20 class="za_text">
                    审核状态：
                    <select name="status" id="type" class="za_select za_select_auto" onchange="">
                        <option value="0" <?php if($search_status ==0){ echo 'selected';}?>>全部</option>
                        <option value="1" <?php if($search_status ==1){ echo 'selected';}?>>已派发</option>
                        <option value="2" <?php if($search_status ==2){ echo 'selected';}?>>未审核</option>
                        <!--<option value="3" <?php /*if($search_status ==3){ echo 'selected';}*/?>>不符合</option>-->
                        <option value="4" <?php if($search_status ==4){ echo 'selected';}?>>已拒绝</option>
                    </select>
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
            <td>ID</td>
            <td>用户ID</td>
            <td>用户</td>
            <td>级别</td>
            <td>真实姓名</td>
            <td>代理线</td>
            <?php
            if($competence_num[38]==1) {  // 手机号码权限控制
                echo '<td>手机号</td>';
            }
            ?>
            <td>投注周期</td>
            <td>礼金内容</td>
            <td>申请时间</td>
            <td>彩金</td>
            <td>审核人</td>
            <td>审核时间</td>
            <td>状态</td>
            <td><font color="red">操作</font></td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="16">没有记录</td></tr>';
        }
        $i=1;
        //        while($row=@mysqli_fetch_assoc($result)){
        foreach ($aData as $k => $row){
        if($row['status'] == 1)
            $pageCount += $row['gift_gold'];
        ?>
        <tr class="m_title">
            <td align="center" width=""><?php echo $i; ?></td>
            <td align="center" width=""><?php echo $row['userid']; ?></td>
            <td align="center" width=""><?php echo $row['username']; ?></td>
            <td align="center" width=""><?php echo $row['level']; ?></td>
            <td align="center" width=""><?php echo $row['Alias']; ?></td>
            <td align="center" width=""><?php echo $row['Agents']; ?></td>
            <?php
            if($competence_num[38]==1) {  // 手机号码权限控制
                echo '<td align="center" width="">'.str_replace('8','*',$row['Phone']).'</td>';
            }
            ?>
            <td align="center" width=""><?php echo $row['count_date_start'].'——'.$row['count_date_end']; ?></td>
            <td align="center" width=""><?php echo $row['EventName']; ?></td>
            <td align="center" width=""><?php echo $row['created_at']; ?></td>
            <td align="center" width=""><?php echo sprintf("%.2f",$row['gift_gold']); ?></td>
            <td align="center" width=""><?php echo $row['auditor']; ?></td>
            <?php
            //0未审核,1已派发,2不符合'
            if ($row['status'] == 2) {
                echo '<td align="center"></td>';
                echo '<td align="center" >未审核</td>';
                echo '<td align="center" >';
                echo '<a href="zhenren_jinji_salary.php?id='.$row['id'].'&lv='.$lv.'&page='.$page.'&action=send&status=1"><font color="blue">派发</font>&nbsp;&nbsp;';
                echo '<a href="zhenren_jinji_salary.php?id='.$row['id'].'&lv='.$lv.'&page='.$page.'&action=send&status=4"><font color="blue">拒绝</font>';
                echo '</td>';
            }elseif($row['status'] == 1){
                echo '<td align="center">'.$row['audited_at'].'</td>';
                echo '<td align="center" >已派发</td>';
                echo '<td align="center" ></td>';
            }elseif($row['status'] == 3){
                echo '<td align="center"></td>';
                echo '<td align="center" >不符合</td>';
                echo '<td align="center" >';
                echo '<a href="zhenren_jinji_salary.php?id='.$row['id'].'&lv='.$lv.'&page='.$page.'&action=send&status=1"><font color="blue">派发</font>&nbsp;&nbsp;';
                echo '<a href="zhenren_jinji_salary.php?id='.$row['id'].'&lv='.$lv.'&page='.$page.'&action=send&status=4"><font color="blue">拒绝</font>';
                echo '</td>';
            }elseif($row['status'] == 4){
                echo '<td align="center">'.$row['audited_at'].'</td>';
                echo '<td align="center" >已拒绝</td>';
                echo '<td align="center"></td>';
            }
            ?>

            <?php
            $i=$i+1;
            }
            ?>
        </tr>

        <tr class="m_rig2">
            <td colspan="2" >统计</td>
            <td colspan="6" class="red">当前页总计 : <?php echo sprintf("%01.2f", $pageCount)?> </td>
            <td colspan="8" class="red">全部页总计 : <?php echo sprintf("%01.2f", $totalCount)?> </td>
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
