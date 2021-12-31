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

if($_REQUEST['action'] == 'add'){
    $username = isset($_REQUEST['username']) && $_REQUEST['username'] ? trim($_REQUEST['username']) : '';
    $countBet = isset($_REQUEST['count_bet']) && $_REQUEST['count_bet'] ? intval($_REQUEST['count_bet']) : 0;
    $remark = isset($_REQUEST['remark']) && $_REQUEST['remark'] ? trim($_REQUEST['remark']) : '';
    $is_free = isset($_REQUEST['is_free']) && $_REQUEST['is_free'] ? trim($_REQUEST['is_free']) : 1;

    if($username == '' || $countBet == 0 || $remark == '' || $is_free == ''){
        exit(json_encode(['status' => 4001, 'message' => '参数错误，会员名称或赠送码量金额或备注信息不能为空！']));
    }
    if($countBet < 1){
        exit(json_encode(['status' => 4002, 'message' => '参数错误，请填写>=1的整数！']));
    }

    // 赠送码量，直接赠送，无需审核
    // 开启事务
    $result=mysqli_query($dbMasterLink, "START TRANSACTION");
    if (!$result) {
        exit(json_encode(['code' => 4003, 'message' => '事务开启失败！ ' . mysqli_error($dbMasterLink)]));
    }
    $result = mysqli_query($dbMasterLink, 'SELECT `ID`,`UserName` FROM ' . DBPREFIX . MEMBERTABLE . ' WHERE `UserName` = "' . $username . '" FOR UPDATE');
    $count = mysqli_num_rows($result);
    if($count == 0){
        exit(json_encode(['code' => 4003, 'message' => '赠送码量失败，不存在此会员！']));
    }
    $aUser = mysqli_fetch_assoc($result);
    // 更新会员打码量
    $date = date('Y-m-d');
    $datetime = date('Y-m-d H:i:s');
    $sql = "REPLACE INTO ".DBPREFIX."zhenren_week_report (userid, username, total, total_ag, total_og, total_bbin, total_manual, count_date_start, count_date_end, created_at, remark, is_free) VALUES 
            ('".$aUser['ID']."','".$username."','".$countBet."','0','0','0',$countBet,'".$date."','".$date."','".$datetime."','".$remark."','".$is_free."')";
    $result=mysqli_query($dbMasterLink, $sql);
    if (!$result){
        $result=mysqli_query($dbMasterLink, "ROLLBACK");
        exit(json_encode(['code' => 4004, 'message' => '赠送码量失败，请您稍后重试！']));
    }
    $dbMasterLink->commit();
    exit(json_encode(['status' => 200, 'message' => '赠送码量成功！']));
}
else if($_REQUEST['action'] == 'del'){
    $userid = $_REQUEST['userid'];
    $count_date_start = $_REQUEST['count_date_start'];
    $count_date_end = $_REQUEST['count_date_end'];
    $sql = "DELETE FROM `" . DBPREFIX . "zhenren_week_report` WHERE `userid` = {$userid} and `count_date_start`='{$count_date_start}' and `count_date_end`='{$count_date_end}'";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        exit(json_encode(['status' => 200, 'message' => '删除成功！']));
    }else{
        exit(json_encode(['status' => 4005, 'message' => '删除失败！']));
    }
}


$sWhere = 1;
$search_name=$_REQUEST['username']; // 查找会员账号

if ($search_name==''){ // 会员账号
    $mem="";
}else{
    $mem="and username='$search_name'";
}

$sql="select * from `".DBPREFIX."zhenren_week_report` where $sWhere $mem order by `created_at` desc";
$result = mysqli_query($dbLink,$sql);
$totalCount=0;
while ($row = mysqli_fetch_array($result)) {
//    if($row['status']==1) {
        $totalCount+= $row['total']; //全部页总计
//    }
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
    <title>真人每周码量报表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
        .main-nav dt{ width: 128px;}
        .mark{color:red;float: right;margin-right: 20px}
        .m_cen{text-align: left;}
        .input_text{width: 400px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>真人每周码量报表</dt>
    <dd>
        <table>
            <tbody><tr class="m_tline">
                <td>&nbsp;&nbsp;&nbsp;
                    <input type="button" class="za_button" id="addGift" value="独立赠送码量">
                </td>
            </tr>
            </tbody>
        </table>
    </dd>
</dl>

<div class="main-ui width_1300">
    <table class="m_tab">
        <tr class="m_title"><td colspan="16"><b>真人每周码量报表</b></td></tr>
        <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
            <tr>
                <td colspan="16">
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
            <td>ID</td>
            <td>用户ID</td>
            <td>用户名</td>
            <td>总码量</td>
            <td>ag码量</td>
            <td>og码量</td>
            <td>bbin码量</td>
            <td>手动添加码量</td>
            <td>是否赠送码量</td>
            <td>开始日期</td>
            <td>结束日期</td>
            <td>数据生成时间</td>
            <td>备注</td>
            <td>操作</td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="16">没有记录</td></tr>';
        }
        $i=1;
        //        while($row=@mysqli_fetch_assoc($result)){
        foreach ($aData as $k => $row){
//        if($row['status'] == 1)
            $pageCount += $row['total'];
        ?>
        <tr class="m_title">
            <td align="center" width=""><?php echo $i; ?></td>
            <td align="center" width=""><?php echo $row['userid']; ?></td>
            <td align="center" width=""><?php echo $row['username']; ?></td>
            <td align="center" width=""><?php echo $row['total']; ?></td>
            <td align="center" width=""><?php echo $row['total_ag']; ?></td>
            <td align="center" width=""><?php echo $row['total_og']; ?></td>
            <td align="center" width=""><?php echo $row['total_bbin']; ?></td>
            <td align="center" width=""><?php echo $row['total_manual']; ?></td>
            <td align="center" width=""><?php echo $row['is_free']?'是':'否'; ?></td>
            <td align="center" width=""><?php echo $row['count_date_start']; ?></td>
            <td align="center" width=""><?php echo $row['count_date_end']; ?></td>
            <td align="center" width=""><?php echo $row['created_at']; ?></td>
            <td align="center" width=""><?php echo $row['remark']; ?></td>
<!--            <td align="center" width=""><a href="zhenren_week_report.php?action=del&userid=--><?php //echo $row['userid']?><!--&count_date_start=--><?php //echo $row['count_date_start']?><!--&count_date_end=--><?php //echo $row['count_date_end']?><!--">删除</a></td>-->
            <td>
                <?php
                if ($row['is_free']==1){
                ?>
                <input type="button" onclick="btn_del(<?php echo $row['userid']?>,'<?php echo $row['count_date_start'];?>','<?php echo $row['count_date_end'];?>')" value="删除" />
                <?php
                }
                ?>
            </td>
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

    <div id="add_window"  class="line_type_width hide_window" style="width: 800px;" >
        <form name="addForm" action="" target="_self" >
            <table class="list-tab">
                <tr>
                    <td id="r_title" colspan="2">
                        独立赠送码量
                        <a class="close_window"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
                    </td>
                </tr>
                <tr class="m_cen">
                    <td>会员名称：<input type="text" name="member" value="" class="input_text"></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">赠送码量：<input type="text" name="count_bet" value="" class="input_text"><span class="mark">>=1,最多保留两位小数</span></td>
                </tr>
                <tr class="m_cen">
                    <td width="10px">赠送码量说明：<input type="text" name="remark" value="" class="input_text"><span class="mark">请认真填写,让会员知道原因</span></td>
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
</body>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
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
        var count_bet = $('input[name="count_bet"]').val();
        var remark = $('input[name="remark"]').val();
        console.log(username+'-'+count_bet+'-'+remark)
        if(username == '' || count_bet == '' || remark == ''){
            alert('会员名称或赠送码量金额或备注信息不能为空！');
            return false;
        }
        var status = confirm("确认赠送会员【" + username + "】码量：" + count_bet + "？" );
        if(!status){
            $('#add_window').hide();
            return false;
        }else{
            addFlag = true;
            $.ajax({
                type : 'POST',
                url : '/app/agents/activity/zhenren_week_report.php?action=add&_=' + Math.random(),
                data : {username:username,count_bet:count_bet,remark:remark},
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
    function btn_del(userid,count_date_start,count_date_end) {
        $.ajax({
            type: "POST",
            url:"zhenren_week_report.php?action=del",
            data:{
                userid:userid,
                count_date_start:count_date_start,
                count_date_end:count_date_end
            },
            dataType:'json',
            success:function(item) {
                alert(item.message);
                if(item.status == 200){
                    window.location.reload();
                }
            },
        });
    }
</script>
</html>

