<?php
/**
 * 代理手续费设置
 * Date: 2019/12/17
 */
include ("../../agents/include/address.mem.php");
require_once("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid = $_REQUEST["uid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$now = date('Y-m-d H:i:s');

switch ($type){
    case 'edit':
        $id = $_REQUEST['id'];
        $value = $_REQUEST['value'];
        $title = $_REQUEST['title'];
        $sql = "UPDATE `" . DBPREFIX . "agent_fee_set` SET `value`='{$value}', `title`='{$title}', `updated_at`='{$now}' WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            insertLog("更新【{$title}】为：【{$value}】成功");
            refreshCache();
            exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        }else{
            insertLog("更新【{$title}】为：【{$value}】失败");
            exit(json_encode(['code' => -1, 'msg' => '更新失败！']));
        }
        break;
    default: break;
}

$sql = "SELECT `id`, `key`, `value`, `title`, `remark`, `created_at`, `updated_at` FROM " . DBPREFIX . "agent_fee_set";
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

// 更新缓存
function refreshCache(){
    global $dbLink;
    $sql = "SELECT `id`, `key`, `value` FROM " . DBPREFIX . "agent_fee_set";
    $result = mysqli_query($dbLink, $sql);
    $lists = array();
    while ($row = mysqli_fetch_assoc($result)){
        $lists[$row['key']] = $row['value'];
    }
    $agentFeeSet = json_encode($lists);
    $redisObj = new Ciredis();
    $redisObj->setOne('agent_fee_set', $agentFeeSet);
}

function insertLog($info){
    global $dbMasterLink, $loginname;
    $ipAddress = get_ip();
    $info = "代理手续费设置" . $info;
    $mysql = "insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url,Level) values('$loginname',now(),'$info','$ipAddress','".BROWSER_IP."','管理员')";
    mysqli_query($dbMasterLink, $mysql);
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>代理手续费设置</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>
<dl class="main-nav"><dt>代理手续费设置</dt><dd></dd></dl>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="5%">ID</td>
            <td width="10%">标题</td>
            <td width="10%">数值</td>
            <td width="20%">描述</td>
            <td width="10%">创建时间</td>
            <td width="10%">更新时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><input type="text" name="title" id="title_<?php echo $value['id']?>" value="<?php echo $value['title']?>" /></td>
                <td><input class="value" type="text" name="value" id="value_<?php echo $value['id']?>" value="<?php echo $value['value']?>"/></td>
                <td style="text-align: left"><?php echo $value['remark']?></td>
                <td><?php echo $value['created_at']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td>
                    <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>,'<?php echo $uid?>')" value="修改" />
                </td>
            </tr>
            <?php
        }

        ?>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_edit(id, uid) {
        var type = 'edit';
        var value = $("#value_" + id).val();
        var title = $("#title_" + id).val();

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"agents_fee_set.php",
            data:{
                id : id,
                uid : uid,
                type : type,
                value : value,
                title : title
            },
            dataType:'JSON',
            success:function(response) {
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='agents_fee_set.php?uid='+uid;
                }
            }
        })
    }
</script>
</body>
</html>

