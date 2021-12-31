<?php
/**
 * 皇冠体育配置
 * Date: 2019/10/18
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once('../include/address.mem.php');
require_once("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid = $_REQUEST["uid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
$key = $_REQUEST['key'];
$value = $_REQUEST['value'];
$status = $_REQUEST['status'];
$remark = $_REQUEST['remark'];
$now = date('Y-m-d H:i:s');

switch ($type){
    case 'del':
        $sql = "DELETE FROM `" . DBPREFIX . "sport_center_config` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            refreshCache();
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    case 'edit':
        $sql = "UPDATE `" . DBPREFIX . "sport_center_config` SET `key`='{$key}', `value`='{$value}', `status`='{$status}', `remark`='{$remark}', `editor`='{$loginname}', `created_at`='{$now}', `updated_at`='{$now}' WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            refreshCache();
            exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '更新失败！']));
        }
        break;
    case 'add':
        $sql = "INSERT INTO `" . DBPREFIX . "sport_center_config`(`key`,`value`,`status`,`remark`,`creator`,`created_at`,`updated_at`) VALUES ('{$key}','{$value}','{$status}','{$remark}','{$loginname}','{$now}','{$now}')";
        $insertId = mysqli_query($dbMasterLink, $sql);
        if($insertId){
            refreshCache();
            echo "<script> alert('添加成功！'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=sportCenterSet.php?uid=$uid'>";
        }else{
            echo "<script> alert('添加失败！'); </script>";
        }
        break;
    default: break;
}

$sql = "SELECT `id`, `key`, `value`, `status`, `remark`, `creator`, `editor`, `created_at`, `updated_at` FROM " . DBPREFIX . "sport_center_config";
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

// 更新缓存
function refreshCache(){
    global $dbLink;
    $sql = "SELECT `id`, `key`, `value` FROM " . DBPREFIX . "sport_center_config WHERE status = 1";
    $result = mysqli_query($dbLink, $sql);
    $lists = array();
    while ($row = mysqli_fetch_assoc($result)){
        $lists[$row['key']] = $row['value'];
    }
    $sportCenterSet = json_encode($lists);
    $redisObj = new Ciredis();
    $redisObj->setOne('sport_center_set', $sportCenterSet);
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>皇冠体育配置</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
        input.value{width: 300px;}
    </style>
</head>
<body>
<dl class="main-nav"><dt>中心参数</dt><dd></dd></dl>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="5%">ID</td>
            <td width="5%">参数</td>
            <td width="20%">value</td>
            <td width="5%">状态</td>
            <td width="10%">描述</td>
            <td width="7%">创建人</td>
            <td width="8%">修改人</td>
            <td width="15%">创建时间</td>
            <td width="15%">更新时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><input type="text" name="key" id="name_<?php echo $value['id']?>" value="<?php echo $value['key']?>" /></td>
                <td><input class="value" type="text" name="value" id="value_<?php echo $value['id']?>" value="<?php echo $value['value']?>"/></td>
                <td><input type="checkbox" name="status" id="status_<?php echo $value['id']?>" value="<?php echo $value['status']?>" <?php echo $value['status'] == 1 ? "checked" : "";?>></td>
                <td><input type="text" name="remark" id="remark_<?php echo $value['id']?>" value="<?php echo $value['remark']?>" /></td>
                <td><?php echo $value['creator']?></td>
                <td><?php echo $value['editor']?></td>
                <td><?php echo $value['created_at']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td>
                    <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>,'<?php echo $uid?>','<?php echo $langx?>','<?php echo $loginname?>')" value="修改" />
                    <input type="button" onclick="btn_del(<?php echo $value['id']?>,'<?php echo $uid?>')" value="删除" />
                </td>
            </tr>
            <?php
        }

        ?>
        <tr class=m_cen >
            <td colspan="13">
                <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)" />
                <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增" />
            </td>
        </tr>
    </table>

    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="uid" value="<?php echo $uid?>" />
                <input type="hidden" name="langx" value="<?php echo $langx?>" />
                <input type="hidden" name="type" value="add" />
                <table class="m_tab">
                    <tbody><tr><th>参数</th><th>value</th><th>启用状态</th><th>描述说明</th></tr>
                    <tr>
                        <td><input class="inp1" type="text" name="key" value=""></td>
                        <td><input class="inp1" type="text" name="value" value=""></td>
                        <td><input type="checkbox" name="status" value="1" checked></td>
                        <td><input class="inp1" type="text" name="remark" value=""></td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="11">
                            <input type="button" value="新增" class="za_button btn2" onclick="javascript:$('#newsadd').submit();">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_del(id,uid) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"sportCenterSet.php",
            data:{id : id,uid: uid,type : type},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='sportCenterSet.php?uid='+uid;
                }
            }
        });
    }

    function btn_edit(id, uid, langx, loginname) {
        var type = 'edit';
        var key = $("#name_" + id).val();
        var value = $("#value_" + id).val();
        var obj = document.getElementById("status_"+id);
        var remark = $("#remark_" + id).val();
        var status = 0;
        if(obj.checked){
            status = 1;
        }

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"sportCenterSet.php",
            data:{
                id : id,
                uid : uid,
                langx : langx,
                loginname : loginname,
                type : type,
                key : key,
                value : value,
                status : status,
                remark : remark
            },
            success:function(response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='sportCenterSet.php?uid='+uid;
                }
            }
        })
    }
</script>
</body>
</html>