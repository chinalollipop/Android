<?php
/**
 * 彩金列表
 * 1.独立赠送彩金
 * 2.批量赠送彩金
 * 3.按条件赠送彩金
 */
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d'); // 本天
if($_REQUEST['action'] == 'add'){
    $username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
    $gift = isset($_REQUEST['gift']) && $_REQUEST['gift'] ? sprintf('%.2f', $_REQUEST['gift']) : 0;
    $money = isset($_REQUEST['money']) && $_REQUEST['money'] ? sprintf('%.4f', $_REQUEST['money']) : 0; // 默认本金为0
    $countBet = isset($_REQUEST['count_bet']) && $_REQUEST['count_bet'] ? intval($_REQUEST['count_bet']) : 0; // 默认不计算打码量
    $remark = isset($_REQUEST['remark']) && $_REQUEST['remark'] ? trim($_REQUEST['remark']) : '';

    if($username == '' || $gift == 0 || $remark == ''){
        exit(json_encode(['status' => 4001, 'message' => '参数错误，会员名称或彩金金额或备注信息不能为空！']));
    }
    if($gift < 1){
        exit(json_encode(['status' => 4002, 'message' => '参数错误，请填写>=1的整数！']));
    }

    // 赠送彩金，直接赠送，无需审核
    // 开启事务
    $dbMasterLink->autocommit(false);
    $result = mysqli_query($dbMasterLink, 'SELECT `ID`,`UserName`,`test_flag`,`Alias`,`Money`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Phone`,`Bank_Name`,`Bank_Account`,`Bank_Address` FROM ' . DBPREFIX . MEMBERTABLE . ' WHERE `UserName` = "' . $username . '" FOR UPDATE');
    $count = mysqli_num_rows($result);
    if($count == 0){
        exit(json_encode(['code' => 4003, 'message' => '赠送彩金失败，不存在此会员！']));
    }
    $aUser = mysqli_fetch_assoc($result);
    $beforeBalance = $aUser['Money']; // 转换之前余额
    $afterBalance = bcadd($beforeBalance, $gift, 4); // 转换之后余额

    $betCount = 0;
    $updateMemberOweBet = '';
    if($countBet != 0){ // 计算打码量
        // 判断是否更新打码量，打码量=(本金+彩金)*打码量倍数
        $betCount = round( bcadd($gift, $money, 4) * $countBet); // 打码量四舍五入
        $updateMemberOweBet = ",owe_bet=owe_bet+$betCount"; // 累计会员提款打码量
        // 判断是否更新打码量统计时间
        $countBetTime = countBetTime($aUser['ID']);
        $updateMemberOweBet .= ($countBetTime == '' ? ",owe_bet_time='$now'" : ",owe_bet_time='$countBetTime'"); // 更新会员打码量开始统计时间
    }

    // 更新会员余额&打码量
    if(!$updated = mysqli_query($dbMasterLink, 'UPDATE ' . DBPREFIX . MEMBERTABLE . ' SET `Money` = ' . $afterBalance . $updateMemberOweBet . ' WHERE `ID` = ' . $aUser['ID'])) {
        $dbMasterLink->rollback();
        exit(json_encode(['code' => 4004, 'message' => '赠送彩金失败，请您稍后重试！']));
    }
    // orderId
    $orderId = date("YmdHis") . rand(100000, 999999);
    // 入库账变表
    $data = [
        'userid' => $aUser['ID'],
        'Checked' => 1,
        'Payway' => 'G', // 赠送彩金的类型
        'Gold' => $gift,
        'moneyf' => $beforeBalance,
        'currency_after' => $afterBalance,
        'AddDate' => $today,
        'Type' => 'S',
        'UserName' => $aUser['UserName'],
        'Agents' => $aUser['Agents'],
        'World' => $aUser['World'],
        'Corprator' => $aUser['Corprator'],
        'Super' => $aUser['Super'],
        'Admin' => $aUser['Admin'],
        'CurType' => 'RMB',
        'Date' => $now,
        'Name' => $aUser['Alias'],
        'Waterno' => '',
        'Phone' => $aUser['Phone'],
        'Notes' => $remark,
        'Bank_Account' => $aUser['Bank_Account'],
        'Bank_Address' => $aUser['Bank_Address'],
        'Bank' => $aUser['Bank_Name'],
        'Order_Code' => $orderId,
        'reason' => '独立赠送彩金',
        'User' => $_SESSION['UserName'],
        'AuditDate' => $now,
        'test_flag' => $aUser['test_flag'],
        'owe_bet' => $betCount,
        'count_bet' => $countBet
    ];

    $tmp = [];
    foreach($data as $key => $val){
        $tmp[] = $key . '=\'' . $val . '\'';
    }
    $sql = "INSERT INTO " . DBPREFIX . "web_sys800_data SET " . implode(',', $tmp);
    if(!$inserted = mysqli_query($dbMasterLink, $sql)){
        $dbMasterLink->rollback();
        exit(json_encode(['code' => 4005, 'message' => '赠送彩金失败，入库账变表失败！']));
    }

    // 入库会员账单表
    $data = [
        $aUser['ID'],
        $aUser['UserName'],
        $aUser['test_flag'],
        $beforeBalance,
        $gift,
        $afterBalance,
        13, // type：13赠送彩金
        6, // 来源：管理后台,
        $inserted,
        $remark
    ];
    if(!addAccountRecords($data)){
        $dbMasterLink->rollback();
        exit(json_encode(['code' => 2006, 'message' => '赠送彩金失败，入库会员账单表失败！']));
    }
    $dbMasterLink->commit();
    exit(json_encode(['status' => 200, 'message' => '赠送彩金成功！']));
}

$date_start = $_REQUEST['date_start'];
$date_end = $_REQUEST['date_end'];
$username = $_REQUEST['username'];
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

if($date_start > $date_end){
    echo "<script>alert('您选择时间错误，开始时间不能大于结束时间!');history.go(-1);</script>";
    exit;
}

if ($date_start == ''){
    $date_start = $today;
    $date_end = $today;
}

$sWhere = '`AddDate` BETWEEN "' . $date_start . '" AND "' . $date_end . '"';
if($username){
    $sWhere .= ' AND `UserName`="' . $username . '"';
}

$mysql = "SELECT * FROM " . DBPREFIX . "web_sys800_data WHERE " . $sWhere . " AND `Type`='S' AND `Payway`='G' AND `Checked`=1 AND `Cancel`='0' GROUP BY ID DESC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);
$totalCount = 0;
if($count){
    while ($row = mysqli_fetch_assoc($result)){
        $totalCount += $row['Gold'];
    }
}

// 分页
$page_size = 20;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);

// 单页分配用户
$list = [];
while($row = mysqli_fetch_assoc($result)){
    $list[] = $row;
}
?>
<html>
<head>
    <title>赠送彩金列表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .mark{color:red;float: right;margin-right: 20px}
        .m_cen{text-align: left;}
        .input_text{width: 400px;}
    </style>
</head>

<body>
<dl class="main-nav"><dt>赠送彩金列表</dt>
<dd>
<table>
    <tbody><tr class="m_tline">
        <td>&nbsp;&nbsp;&nbsp;
            <input type="button" class="za_button" id="addGift" value="独立赠送彩金">
        </td>
    </tr>
    </tbody>
</table>
</dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <FORM id="myFORM" ACTION="" METHOD=POST name="myFORM">
            <tr class="m_title">
                <td colspan="10">
                    快捷查询：
                    <!-- l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月 -->
                    <input type="button" class="za_button" onClick="chg_date('t')" value="今日">
                    <input type="button" class="za_button" onClick="chg_date('l')" value="昨日">
                    <input type="button" class="za_button" onClick="chg_date('w')" value="本周">
                    <input type="button" class="za_button" onClick="chg_date('lw')" value="上周">
                    <input type="button" class="za_button" onClick="chg_date('m')" value="本月">
                    <input type="button" class="za_button" onClick="chg_date('lm')" value="上个月">
                </td>
            </tr>
            <tr class="m_title">
                <td colspan="9">
                    <span id="ShowTime"></span>
                    开始日期：
                    <input type="text" name="date_start" id="date_start" value="<?php echo $date_start?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
                    结算日期：
                    <input type="text" name="date_end" id="date_end" value="<?php echo $date_end?>"  onclick="laydate({istime: false,istoday: false, format: 'YYYY-MM-DD'})" size=15 maxlength=11 class="za_text" readonly>
                    &nbsp;会员名:
                    <input type=text name="username" size=10 value="<?php echo $username?>" maxlength=50 class="za_text">
                    <input type="submit" name="SUBMIT" value="确认" class="za_button">
                    共<?php echo $count?>条
                    <select name='page' onChange="self.myFORM.submit()">
                        <?php
                        if ($page_count == 0){
                            $page_count = 1;
                        }
                        for($i = 0; $i < $page_count; $i++){
                            if ($i == $page){
                                echo "<option selected value = '$i'>" . ($i + 1) . "</option>";
                            }else{
                                echo "<option value = '$i'>" . ($i + 1) . "</option>";
                            }
                        }
                        ?>
                    </select> 共<?php echo $page_count?> 页
                </td>
            </tr>
        </FORM>
    </table>
    <table class="m_tab">
        <tr class="m_title">
            <td>订单号</td>
            <td>会员</td>
            <td>金额</td>
            <td>类型</td>
            <td>备注</td>
            <td>时间</td>
            <td>操作者</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="7">暂无记录</td></tr>';
        }
        $pageCount = '0.00';
        foreach ($list as $key => $value){
            $pageCount += $value['Gold'];
            ?>
            <tr class="m_rig">
                <td width="15%"><?php echo $value['Order_Code']?></td>
                <td width="10%"><?php echo $value['UserName']?></td>
                <td width="15%"><?php echo sprintf('%.2f', $value['Gold'])?></td>
                <td width="10%">彩金</td>
                <td width="25%"><?php echo $value['Notes']?></td>
                <td width="10%"><?php echo $value['AuditDate'];?></td>
                <td width="10%"><?php echo $value['User'];?></td>
            </tr>
        <?php }?>
        <tr class="m_rig2">
            <td colspan="2" ><?php  echo $date_start.'到'.$date_end;?></td>
            <td colspan="2" class="red">当前页总计 : <?php echo sprintf("%.2f", $pageCount)?> </td>
            <td colspan="3" class="red">全部页总计 : <?php echo sprintf("%.2f", $totalCount)?> </td>
        </tr>
    </table>
    <div id="add_window"  class="line_type_width hide_window" style="width: 800px;" >
        <form name="addForm" action="" target="_self" >
            <table class="list-tab">
                <tr>
                    <td id="r_title" colspan="2">
                        独立赠送彩金
                        <a class="close_window"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
                    </td>
                </tr>
                <tr class="m_cen">
                    <td>会员名称：<input type="text" name="member" value="" class="input_text"></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">彩金金额：<input type="text" name="gift" value="" class="input_text"><span class="mark">>=1,最多保留两位小数</span></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">本金金额：<input type="text" name="money" value="0" class="input_text"><span class="mark">只用于计算打码量,没有其它作用</span></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">打码量倍数：<select name="count_bet">
                            <option value="0" selected>不计算</option>
                            <option value="1">1 倍</option>
                            <option value="2">2 倍</option>
                            <option value="3">3 倍</option>
                            <option value="4">4 倍</option>
                            <option value="5">5 倍</option>
                            <option value="6">6 倍</option>
                            <option value="7">7 倍</option>
                            <option value="8">8 倍</option>
                            <option value="9">9 倍</option>
                            <option value="10">10 倍</option>
                            <option value="11">11 倍</option>
                            <option value="12">12 倍</option>
                            <option value="13">13 倍</option>
                            <option value="14">14 倍</option>
                            <option value="15">15 倍</option>
                            <option value="16">16 倍</option>
                            <option value="17">17 倍</option>
                            <option value="18">18 倍</option>
                            <option value="19">19 倍</option>
                            <option value="20">20 倍</option>
                            <option value="21">21 倍</option>
                            <option value="22">22 倍</option>
                            <option value="23">23 倍</option>
                            <option value="24">24 倍</option>
                            <option value="25">25 倍</option>
                            <option value="26">26 倍</option>
                            <option value="27">27 倍</option>
                            <option value="28">28 倍</option>
                            <option value="29">29 倍</option>
                            <option value="30">30 倍</option>
                            <option value="31">31 倍</option>
                            <option value="32">32 倍</option>
                            <option value="33">33 倍</option>
                            <option value="34">34 倍</option>
                            <option value="35">35 倍</option>
                            <option value="36">36 倍</option>
                            <option value="37">37 倍</option>
                            <option value="38">38 倍</option>
                            <option value="39">39 倍</option>
                            <option value="40">40 倍</option>
                            <option value="41">41 倍</option>
                            <option value="42">42 倍</option>
                            <option value="43">43 倍</option>
                            <option value="44">44 倍</option>
                            <option value="45">45 倍</option>
                            <option value="46">46 倍</option>
                            <option value="47">47 倍</option>
                            <option value="48">48 倍</option>
                            <option value="49">49 倍</option>
                            <option value="50">50 倍</option>
                        </select><span class="mark">打码量=(本金+彩金)*打码量倍数</span></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">备份说明：<input type="text" name="remark" value="" class="input_text"><span class="mark">请认真填写,让会员知道原因</span></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" id="add" name="add" value="添加" class="za_button">
                        <input type="button" id="cancel" name="cancel" value="取消" class="za_button">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="javascript">
    $('#addGift').on('click', function () {
        $('#add_window').show();
    });

    $('.close_window').on('click', function () {
        $('#add_window').hide();
    });

    $('#cancel').on('click', function () {
        $('#add_window').hide();
    });

    var addFlag = false; // 禁止重复提交
    $('#add').on('click', function () {
        if (addFlag) {
            alert('请勿重复提交!');
            return false;
        }
        var username = $('input[name="member"]').val();
        var gift = $('input[name="gift"]').val();
        var money = $('input[name="money"]').val();
        var count_bet = $('select[name="count_bet"]').val();
        var remark = $('input[name="remark"]').val();
        if(username == '' || gift == '' || remark == ''){
            alert('会员名称或彩金金额或备注信息不能为空！');
            return false;
        }
        var status = confirm("确认赠送会员【" + username + "】彩金：" + gift + "？" );
        if(!status){
            $('#add_window').hide();
            return false;
        }else{
            addFlag = true;
            $.ajax({
                type : 'POST',
                url : '/app/agents/800/gift_list_800.php?action=add&_=' + Math.random(),
                data : {username:username,gift:gift,money:money,count_bet:count_bet,remark:remark},
                dataType:'json',
                success:function(item) {
                    if(item.status == 200){
                        $('#add_window').hide();
                        window.location.reload();
                    }
                    addFlag = false;
                    alert(item.message);
                },
                error:function(){
                    addFlag = false;
                    alert('抱歉，网络异常，请稍后重试！');
                }
            });
        }
    });

    function chg_date(range) {
        //  l 昨日  t 今日  n 明日  w 本星期  lw 上星期 m 本月 lm 上个月
        var date_start;
        var date_end;
        switch (range) {
            case 'l': // 昨日
                date_start = '<?php echo date('Y-m-d', strtotime($today) - 86400);?>';
                date_end = '<?php echo date('Y-m-d', strtotime($today) - 86400);?>';
                break;
            case 't': // 今日
                date_start = '<?php echo $today;?>';
                date_end = '<?php echo $today;?>';
                break;
            case 'n': // 明日
                date_start = '<?php echo date('Y-m-d', strtotime($today) + 86400);?>';
                date_end = '<?php echo date('Y-m-d', strtotime($today) + 86400);?>';
                break;
            case 'w': // 本周
                date_start = '<?php echo date('Y-m-d', strtotime("this week"));?>';
                date_end = '<?php echo date('Y-m-d', strtotime("last day next week"));?>';
                break;
            case 'lw': // 上周
                date_start = '<?php echo date('Y-m-d', strtotime("last week Monday"));?>';
                date_end = '<?php echo date('Y-m-d', strtotime("last week Sunday"));?>';
                break;
            case 'm': // 本月
                date_start = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-01'));?>';
                date_end = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-' . date('t', time()) . ''));?>';
                break;
            case 'lm': // 上个月
                date_start = '<?php echo date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m', time()) . '-01')));?>';
                date_end = '<?php echo date('Y-m-d', strtotime(date('Y-m', time()) . '-01') - 86400);?>';
                break;
        }
        myFORM.date_start.value = date_start;
        myFORM.date_end.value = date_end;
    }
</script>
</body>
</html>