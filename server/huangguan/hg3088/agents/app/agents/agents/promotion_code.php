<?php
/**
 * 推广管理
 * Date: 2019/10/11
 */
ini_set('display_errors','Off');
session_start();
require_once '../include/config.inc.php';
include_once ("../include/address.mem.php");
require_once '../include/redis.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$now = date('Y-m-d H:i:s');

// 批量添加推荐号码
if($_REQUEST['action'] == 'add'){
    $codeStart = isset($_REQUEST['code_start']) && $_REQUEST['code_start'] ? intval($_REQUEST['code_start']) : '';
    $codeEnd = isset($_REQUEST['code_end']) && $_REQUEST['code_end'] ? intval($_REQUEST['code_end']) : '';
    if(!$codeStart || !$codeEnd || $codeStart <= 100000 || $codeEnd <= 100000){
        exit(json_encode(['status' => 4001, 'message' => '参数错误，请填写6位整数！']));
    }
    if($codeStart > $codeEnd){
        exit(json_encode(['status' => 4002, 'message' => '参数错误，开始号码>结束号码！']));
    }
    $sql = 'insert into ' . DBPREFIX . 'promotion_code(`id`,`code`,`created_at`,`updated_at`) VALUES';
    for ($i=$codeStart; $i<=$codeEnd; $i++){
        $sql .= '(' . ($i-100000) . ', "'. $i. '", "'.$now.'", "'.$now.'"),';
    }
    if(!mysqli_query($dbMasterLink, rtrim($sql, ','))){
        exit(json_encode(['status' => 4003, 'message' => '抱歉，批量添加失败！']));
    }
    exit(json_encode(['status' => 200, 'message' => '批量添加成功！']));
}else if($_REQUEST['action'] == 'assign'){
    $id = $_REQUEST['id'];
    $agent_name = $_REQUEST['agent_name'];
    // 判断分配用户是否为代理
    $resAgent = mysqli_query($dbLink, "SELECT `ID`, `UserName`, `Status` FROM ".DBPREFIX."web_agents_data where `UserName` = '{$agent_name}' LIMIT 1");
    $count = mysqli_num_rows($resAgent);
    if($count <= 0) {
        exit(json_encode( ['status' => '4006', 'message' => '不存在此代理账号！'] ) );
    }
    $aUser = mysqli_fetch_assoc($resAgent);

    $sql = "UPDATE `" . DBPREFIX . "promotion_code` SET `is_assign`=1,`agent_id`={$aUser['ID']},`agent_status`={$aUser['Status']},`agent_name`='{$agent_name}',`assigned_at`='{$now}',`updated_at`='{$now}' WHERE `id` = {$id}";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        exit(json_encode(['status' => 200, 'message' => '更新成功！']));
    }else{
        exit(json_encode(['status' => 4004, 'message' => '更新失败！']));
    }
}else if($_REQUEST['action'] == 'del'){
    $id = $_REQUEST['id'];
    $sql = "DELETE FROM `" . DBPREFIX . "promotion_code` WHERE `id` = {$id}";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        exit(json_encode(['status' => 200, 'message' => '删除成功！']));
    }else{
        exit(json_encode(['status' => 4005, 'message' => '删除失败！']));
    }
}

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : '';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : '';
$agent_name = $_REQUEST['agent_name'] ? trim($_REQUEST['agent_name']) : '';
$isAssign = $_REQUEST['is_assign'] ? intval($_REQUEST['is_assign']) : '0'; // 默认显示未分配的code

$sWhere = 1;
if($startTime)
    $sWhere .= " AND `updated_at` >= '{$startTime}'";
if($endTime)
    $sWhere .= " AND `updated_at` <= '{$endTime}'";
if($isAssign != -1)
    $sWhere .= " AND `is_assign`={$isAssign}";
if($agent_name)
    $sWhere .= " AND `agent_name` like '%{$agent_name}%'";

$mysql = "SELECT `id`, `code`, `is_assign`, `agent_id`, `agent_name`, `agent_status`, `assigned_at`, `created_at`, `updated_at` 
          FROM `" . DBPREFIX . "promotion_code`
          WHERE $sWhere 
          ORDER BY `id` ASC";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 查询全部统计已分配和未分配数量
$applied = $unApplied = [];
$totalApplied = $totalUnApplied = 0;
while($row = mysqli_fetch_assoc($result)){
    if($row['is_assign'] == 1){
        $totalApplied ++;
        $applied[] = $row['code'];
    }else{
        $totalUnApplied ++;
        $unApplied[] = $row['code'];
    }
}

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);

// 单页分配用户
$promotionCode = [];
while($row = mysqli_fetch_assoc($result)){
    $promotionCode[] = $row;
}

?>
<html>
<head>
    <title>推广号码</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
        input.za_text {width: 142px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>推广管理</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        分配日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        代理帐号：
                        <input type="text" name="agent_name" size=10 value="<?php echo $agent_name;?>" maxlength=20 class="za_text">

                        <select name="is_assign" id="is_assign">
                            <option value="-1">全部</option>
                            <option value="0" <?php if($isAssign == 0) echo "selected";?> >未分配</option>
                            <option value="1" <?php if($isAssign == 1) echo "selected";?> >已分配</option>
                        </select>
                        <input type="submit" name="SUBMIT" value="确认" class="za_button">
                        <input type="button" class="za_button" id="addCode" value="新增推广码">
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

            </table>
        </form>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <tr class="mem_total_money">
            <td colspan="13">
        <?php if($isAssign == -1){?>
                已分配：<span><?php echo $totalApplied;?>&nbsp;&nbsp;(<?php echo $applied ? current($applied) .'-'. end($applied) : '未分配'?>)</span>&nbsp;&nbsp;
                未分配：<span><?php echo $totalUnApplied;?>&nbsp;&nbsp;(<?php echo $unApplied ? current($unApplied) . '-' . end($unApplied) : '已全部分配，请添加推广号'?>)</span>&nbsp;&nbsp;
        <?php }elseif($isAssign == 1){?>
            已分配：<span><?php echo $totalApplied;?>&nbsp;&nbsp;(<?php echo $applied ? current($applied) .'-'. end($applied) : '未分配'?>)</span>&nbsp;&nbsp;
        <?php }elseif($isAssign == 0){?>
            未分配：<span><?php echo $totalUnApplied;?>&nbsp;&nbsp;(<?php echo $unApplied ? current($unApplied) . '-' . end($unApplied) : '已全部分配，请添加推广号'?>)</span>&nbsp;
        <?php }?>
            </td>
        </tr>
        <tr class="m_title">
            <td>推广号码</td>
            <td>是否分配</td>
            <td>生成时间</td>
            <td>分配代理</td>
            <td>代理状态</td>
            <td>分配时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="7">暂无记录</td></tr>';
        }
        foreach ($promotionCode as $key => $value){
            ?>
            <tr class="m_rig">
                <td width="10%"><?php echo $value['code']?></td>
                <td width="10%"><?php echo $value['is_assign'] == 0 ? '未分配' : '已分配'?></td>
                <td width="15%"><?php echo $value['created_at']?></td>
                <td width="10%"><input type="text" name="agent_name" id="agent_name_<?php echo $value['id'];?>" value="<?php echo $value['agent_name']?>" /></td>
                <td width="10%"><?php echo $value['is_assign'] == 1 ? ($value['agent_status'] == 0 ? '启用' : ($value['agent_status'] == 1 ? '冻结' : '停用')) : ''?></td>
                <td width="15%"><?php echo $value['assigned_at'];?></td>
                <td>
                    <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>)" value="分配" />
                    <input type="button" onclick="btn_del(<?php echo $value['id']?>)" value="删除" />
                </td>
            </tr>
        <?php }?>
    </table>
    <div id="add_window"  class="line_type_width hide_window" >
        <form name="addForm" action="" target="_self" >
            <table class="list-tab">
                <tr>
                    <td id="r_title" colspan="2">
                        批量添加推广号码
                        <a class="close_window"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
                    </td>
                </tr>
                <tr class=m_cen" style="text-align: center;">
                    <td width="10px">开始号码（6位）：<input type="text" name="code_start" value=""></td>
                </tr>
                <tr class=m_cen" style="text-align: center;">
                    <td width="10px">结束号码（6位）：<input type="text" name="code_end" value=""></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" id="add" name="add" value="添加" class="za_button"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script>
    $('#addCode').on('click', function () {
        $('#add_window').show();
    });

    $('.close_window').on('click', function () {
        $('#add_window').hide();
    });

    $('#add').on('click', function () {
        var code_start = $('input[name="code_start"]').val();
        var code_end = $('input[name="code_end"]').val();
        if(code_start == '' || code_end == ''){
            alert('参数错误，请填写6位整数！');
            return false;
        }
        var status = confirm("确认要添加" + code_start + "-" + code_end + "的推荐码？" );
        if(!status){
            $('#add_window').hide();
            return false;
        }else{
            $.ajax({
                type : 'POST',
                url : '/app/agents/agents/promotion_code.php?action=add&_=' + Math.random(),
                data : {code_start:code_start,code_end:code_end},
                dataType:'json',
                success:function(item) {
                    if(item.status == 200){
                        $('#add_window').hide();
                        window.location.reload();
                    }
                    alert(item.message);
                },
                error:function(){
                    alert('抱歉，网络异常，请稍后重试！');
                }
            });
        }
    });

    function btn_del(id) {
        $.ajax({
            type: "POST",
            url:"promotion_code.php?action=del",
            data:{id:id},
            dataType:'json',
            success:function(item) {
                alert(item.message);
                if(item.status == 200){
                    window.location.reload();
                }
            },
        });
    }

    function btn_edit(id) {
        var agent_name = $("#agent_name_" + id).val();
        alert(agent_name);
        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"promotion_code.php?action=assign&_=" + Math.random(),
            data:{id:id,agent_name:agent_name},
            dataType:'json',
            success:function(item) {
                alert(item.message);
                if(item.status == 200){
                    window.location.reload();
                }
            },
            error:function(){
                alert('抱歉，网络异常，请稍后重试！');
            }
        })
    }
</script>
</html>


