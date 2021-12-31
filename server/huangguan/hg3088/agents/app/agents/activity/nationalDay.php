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
$goldType = $_REQUEST['gold_type'];

$date = date('Y-m-d H:i:s');

$goldStatus = [
    1 => '已审核',
    2 => '待审核',
    3 => '已拒绝',
];

if($goldType == '1'){
    $nationTable = 'web_national_register';
    $goldMoney = 'gold_free';
    $markMeg = '免费彩金';
}else if($goldType == '2'){
    $nationTable = 'web_national_deposit';
    $goldMoney = 'gold_deposit';
    $markMeg = '存款彩金';
}

// 派发奖金
if($action == 'send'){
    $att_id = $_REQUEST['id'];
    $mysql="select * from ".DBPREFIX.$nationTable." where id=".$att_id;
    $rs = mysqli_query($dbLink, $mysql);
    $rows = @mysqli_fetch_assoc($rs);

    $gold = $rows[$goldMoney];  //国庆有惊喜 优惠乐翻天
    if($rows['status'] == 2) { //状态：1已派发,2未审核,3不符合
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
        $mysql_status=mysqli_query($dbMasterLink,"select id,userid,status from ".DBPREFIX.$nationTable." where id=".$att_id." for update");
        if($beginFrom && $mysql_status) {
            $row_status = mysqli_fetch_assoc($mysql_status);
            if($row_status['status'] == 2) {
                $resultMem = mysqli_query($dbMasterLink, "select ID,UserName,Money,test_flag,Alias,Agents,World,Corprator,Super,Admin,Bank_Name,Bank_Address,Bank_Account from  ".DBPREFIX.MEMBERTABLE." where ID='{$rows['userid']}' for update");
                if ($resultMem) {
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    $mysql = "update " . DBPREFIX.MEMBERTABLE." set Money=Money+$gold where ID='" . $rows['userid'] . "'";
                    if (mysqli_query($dbMasterLink, $mysql)) {
                        $mysql = "update " . DBPREFIX . $nationTable . " set status=1,updated_at='$date',audited_at='$date',auditor='$loginname' where id=" . $rows['id'];
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
                            $notes='国庆有惊喜 优惠乐翻天'; // 备注
                            $bank = $rowMem['Bank_Name'];
                            $bank_account=$rowMem['Bank_Account'];
                            $bank_address=$rowMem['Bank_Address'];
                            $order_code = date("YmdHis",time()).rand(100000,999999);
                            $AuditDate = date("Y-m-d H:i:s",time());
                            $test_flag = $rowMem['test_flag'];
                            $sql = "insert into `".DBPREFIX."web_sys800_data` set userid='{$rowMem['ID']}',Checked=1,Payway='O',Gold='$gold',moneyf='{$rowMem['Money']}',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='{$rows['username']}',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$realName',notes='$notes',Bank_Account='$bank_account',Bank='$bank',Bank_Address='$bank_address',Order_Code='$order_code',AuditDate='$AuditDate',Cancel='0',test_flag='$test_flag'";
                            $res = mysqli_query($dbMasterLink,$sql);
                            echo mysqli_error($dbMasterLink);
                            if ($res) {
                                $moneyLogRes = addAccountRecords(array($rowMem['ID'], $rows['username'], $rowMem['test_flag'], $rowMem['Money'], $gold, $rowMem['Money'] + $gold, 11, 6, $rows['id'], "[国庆]{$markMeg}审核,成功入账"));
                                if ($moneyLogRes) {
                                    mysqli_query($dbMasterLink, "COMMIT");
                                    echo "<script>alert('派发成功!');</script>";
                                } else {
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                    echo "<script>alert('入库资金账变失败!');</script>";
                                }
                            } else {
                                mysqli_query($dbMasterLink, "ROLLBACK");
                                echo "<script>alert('入库账变失败!');</script>";
                            }
                        } else {
                            mysqli_query($dbMasterLink, "ROLLBACK");
                            echo "<script>alert('审核失败!');</script>";
                        }
                    } else {
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        echo "<script>alert('上分失败!');</script>";
                    }
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    echo "<script>alert('开启资金锁失败!');</script>";
                }
            } else {
                mysqli_query($dbMasterLink, "ROLLBACK");
                echo "<script>alert('此单已处理!');</script>";
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            echo "<script>alert('开启事务失败!');</script>";
        }
        $loginfo = $loginname.' 对会员帐号 <font class="green">'.$rows['username'].'</font> 国庆有惊喜操作为<font class="red">成功</font>,金额为 <font class="red">'.number_format($gold,2).'</font>,id为 '.$rows['id'].'</font>';
    }
}

// 搜索查询
$sWhere = 1;
$search_name = isset($_REQUEST['username']) && $_REQUEST['username'] ? $_REQUEST['username'] : ''; // 查找会员账号
$search_status = isset($_REQUEST['status']) && $_REQUEST['status'] ? $_REQUEST['status'] : '';
if($search_status) { // 用户状态：1审核通过,2未审核,3已拒绝
    $sWhere .= " and status = $search_status";
}
if ($search_name){ // 会员账号
    $sWhere .="and UserName='$search_name'";
}

$sql = "select * from `".DBPREFIX.$nationTable."` where $sWhere order by `created_at` desc";
$result = mysqli_query($dbLink,$sql);
$totalCount = 0;
while ($row = mysqli_fetch_array($result)) {
    if($row['status'] == 1) {
        $totalCount += $row[$goldMoney];
    }
}
$cou = mysqli_num_rows($result);
$page_size = 100;
$page_count = ceil($cou / $page_size);
if ($page == ''){
    $page = 0;
}
$offset = $page * $page_size;
$mysql = $sql."  limit $offset,$page_size;";
$result = mysqli_query($dbLink,$mysql);
if ($cou == 0){
    $page_count = 1;
}
?>
<html>
<head>
    <title>国庆有惊喜 优惠乐翻天</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>国庆活动</dt>
    &nbsp;&nbsp;&nbsp;&nbsp;
    审核彩金类型：
    <select id="gold_type" onchange="show(this)">
        <option value="1" <?php if($goldType == 1){echo "selected";}?>>免费礼金</option>
        <option value="2" <?php if($goldType == 2){echo "selected";}?>>存款礼金</option>
    </select>
    <dd></dd>
</dl>

<?php if($goldType == 1){?>
<div class="main-ui width_1300" id="national_register">
    <table class="m_tab">
        <tr class="m_title">
            <td colspan="12"><b>国庆活动【<span id="t_title" style="color:red;">免费礼金</span>】申请列表</b></td>
        </tr>
        <FORM id="registerForm" ACTION="" METHOD=POST  name="FrmData">
            <tr>
                <td colspan="12">
                    <input type="hidden" name="gold_type" value="1">
                    会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $search_name;?>" maxlength=20 class="za_text">
                    审核状态：
                    <select name="status" id="type" class="za_select za_select_auto" onchange="">
                        <option value="0" <?php if($search_status ==0){ echo 'selected';}?>>全部</option>
                        <option value="1" <?php if($search_status ==1){ echo 'selected';}?>>已派发</option>
                        <option value="2" <?php if($search_status ==2){ echo 'selected';}?>>未审核</option>
                        <option value="3" <?php if($search_status ==3){ echo 'selected';}?>>已拒绝</option>
                    </select>
                    <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                    共<?php echo $cou?>条
                    <select name='page' onChange="self.registerForm.submit()">
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
            <td>注册IP</td>
            <td>注册时间</td>
            <td>存款次数</td>
            <td>免费彩金</td>
            <td>申请时间</td>
            <td>状态</td>
            <td>审核人</td>
            <td>审核时间</td>
            <td><font color="red">操作</font></td>
        </tr>
        <?php
        if($cou == 0){ // 没有记录
            echo '<tr ><td colspan="12">暂时没有符合条件的记录</td></tr>';
        }
        $i = 1;
        while($row=@mysqli_fetch_assoc($result)){
        if ($row['status'] == 1)
            $pageCount += $row['gold_free'];
        ?>
        <tr class="m_title">
            <td align="center" width="10"><?php echo $i; ?></td>
            <td align="center" width="10"><?php echo $row['userid']; ?></td>
            <td align="center" width="10"><?php echo $row['username']; ?></td>
            <td align="center" width="10"><?php echo $row['ip']; ?></td>
            <td align="center" width="20"><?php echo $row['registered_at']; ?></td>
            <td align="center" width="10"><?php echo $row['deposit_times']; ?></td>
            <td align="center" width="20"><?php echo sprintf("%.2f", $row['gold_free']); ?></td>
            <td align="center" width="20"><?php echo $row['created_at']; ?></td>
            <td align="center" width="10"><?php echo $goldStatus[$row['status']]; ?></td>
            <td align="center" width="10"><?php echo $row['auditor']; ?></td>
            <td align="center" width="20"><?php echo $row['audited_at']; ?></td>
            <td align="center">
            <?php
                if ($row['status'] == 2){
                    $href = "./nationalDay.php?uid={$uid}&langx={$langx}&id={$row['id']}&lv={$lv}&page={$page}&action=send&gold_type=1";
                    echo '<a href="'. $href . '"><font color="blue">派发</font>';
                }
            ?>
            </td>
        <?php
            $i = $i + 1;
        }
        ?>
        </tr>
        <tr class="m_rig2">
            <td colspan="2" >统计</td>
            <td colspan="5" class="red">当前页总计 : <?php echo sprintf("%01.2f", $pageCount)?> </td>
            <td colspan="5" class="red">全部页总计 : <?php echo sprintf("%01.2f", $totalCount)?> </td>
        </tr>
    </table>
</div>
<?php } else if($goldType == 2) {?>
<div class="main-ui width_1300" id="national_deposit">
    <table class="m_tab">
        <tr class="m_title">
            <td colspan="12"><b>国庆活动【<span id="t_title" style="color:red;">存款礼金</span>】申请列表</b></td>
        </tr>
        <FORM id="depositForm" ACTION="" METHOD=POST  name="FrmData">
            <tr>
                <td colspan="12">
                    <input type="hidden" name="gold_type" value="2">
                    会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $search_name;?>" maxlength=20 class="za_text">
                    审核状态：
                    <select name="status" id="type" class="za_select za_select_auto" onchange="">
                        <option value="0" <?php if($search_status ==0){ echo 'selected';}?>>全部</option>
                        <option value="1" <?php if($search_status ==1){ echo 'selected';}?>>已派发</option>
                        <option value="2" <?php if($search_status ==2){ echo 'selected';}?>>未审核</option>
                        <option value="3" <?php if($search_status ==3){ echo 'selected';}?>>已拒绝</option>
                    </select>
                    <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                    共<?php echo $cou?>条
                    <select name='page' onChange="self.depositForm.submit()">
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
            <td>注册IP</td>
            <td>注册时间</td>
            <td>存款最大金额</td>
            <td>存款彩金</td>
            <td>申请时间</td>
            <td>状态</td>
            <td>审核人</td>
            <td>审核时间</td>
            <td><font color="red">操作</font></td>
        </tr>
        <?php
        if($cou == 0){ // 没有记录
            echo '<tr ><td colspan="12">暂时没有符合条件的记录</td></tr>';
        }
        $j = 1;
        while($row=mysqli_fetch_assoc($result)){
            if ($row['status'] == 1)
                $pageCount += $row['gold_deposit'];
        ?>
        <tr class="m_title">
            <td align="center" width="10"><?php echo $j; ?></td>
            <td align="center" width="10"><?php echo $row['userid']; ?></td>
            <td align="center" width="10"><?php echo $row['username']; ?></td>
            <td align="center" width="10"><?php echo $row['ip']; ?></td>
            <td align="center" width="20"><?php echo $row['registered_at']; ?></td>
            <td align="center" width="10"><?php echo $row['max_deposit_money']; ?></td>
            <td align="center" width="10"><?php echo sprintf("%.2f", $row['gold_deposit']); ?></td>
            <td align="center" width="20"><?php echo $row['created_at']; ?></td>
            <td align="center" width="10"><?php echo $goldStatus[$row['status']]; ?></td>
            <td align="center" width="10"><?php echo $row['auditor']; ?></td>
            <td align="center" width="20"><?php echo $row['audited_at']; ?></td>
            <td align="center">
            <?php
            if ($row['status'] == 2){
                $href = "./nationalDay.php?uid={$uid}&langx={$langx}&id={$row['id']}&lv={$lv}&page={$page}&action=send&gold_type=2";
                echo '<a href="'. $href . '"><font color="blue">派发</font>';
            }
            ?>
            </td>
        <?php
            $j = $j + 1;
        }
        ?>
        </tr>
        <tr class="m_rig2">
            <td colspan="2" >统计</td>
            <td colspan="5" class="red">当前页总计 : <?php echo sprintf("%01.2f", $pageCount)?> </td>
            <td colspan="5" class="red">全部页总计 : <?php echo sprintf("%01.2f", $totalCount)?> </td>
        </tr>
    </table>
</div>
<?php } ?>
</body>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
    var url = '<?php echo "nationalDay.php?uid=$uid&langx=$langx&lv=$level";?>';
    function show(obj){
        var goldType = obj.value;
        if(goldType == 1){
            window.location.href = url + "&gold_type=1";
        }
        if(goldType == 2){
            window.location.href = url + "&gold_type=2";
        }
    }
</script>
</html>
<!-- 插入系统日志 -->
<?php
if ($action=='send'){ // 有操作才需要插入
    innsertSystemLog($loginname,$lv,$loginfo);
}
?>
