<?php
/**
 * 客户端-发布版本管理
 * Date: 2018/9/13
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");
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
$terminalId = $_REQUEST['terminal_id'];
$version = trim($_REQUEST['version']);
$fileSize = trim($_REQUEST['file_size']);
$filePath = trim($_REQUEST['file_path']);
$description = trim($_REQUEST['description']);
$isForce = $_REQUEST['is_force'];
$status = $_REQUEST['status'];
$now = date('Y-m-d H:i:s');

switch ($type){
    case 'del':
        $sql = "DELETE FROM `" . DBPREFIX . "web_releases` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    case 'edit':
        $sql = "UPDATE `" . DBPREFIX . "web_releases` SET `terminal_id`={$terminalId}, `version`='{$version}', `file_size`='{$fileSize}', `file_path`='{$filePath}', `description`='{$description}', `is_force`={$isForce}, `status`='{$status}', `created_at`='{$now}', `updated_at`='{$now}' WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '更新失败！']));
        }
        break;
    case 'add':
        $sql = "INSERT INTO `" . DBPREFIX . "web_releases` VALUES ('','{$terminalId}','{$version}','{$fileSize}','{$filePath}','{$description}','{$isForce}','{$status}','{$now}','{$now}')";
        $insertId = mysqli_query($dbMasterLink, $sql);
        if($insertId){
            echo "<script> alert('添加成功！'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=release.php?uid=$uid'>";
        }else{
            echo "<script> alert('添加失败！'); </script>";
        }
        break;
    default: break;
}
$result = mysqli_query($dbLink, "SELECT `id`, `name` FROM " . DBPREFIX . "web_terminals WHERE `status` = 1");
$terminalIds = [];
while ($row = mysqli_fetch_assoc($result)){
    $terminalIds[$row['id']] = $row['name'];
}

$sql = "SELECT `id`, `terminal_id`, `version`, `file_size`, `file_path`, `description`, `is_force`,`status`, `created_at`, `updated_at` FROM " . DBPREFIX . "web_releases";
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>发布版本管理</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
        input.version{width: 50px;}
        input.file_size{width: 60px;}
        input.file_path{width: 300px;}
    </style>
</head>
<body>
<dl class="main-nav"><dt>发布管理</dt><dd></dd></dl>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="5%">ID</td>
            <td width="10%">客户端</td>
            <td width="5%">版本号</td>
            <td width="5%">文件大小</td>
            <td width="15%">文件路径</td>
            <td width="15%">发布描述</td>
            <td width="5%">是否强制</td>
            <td width="5%">发布状态</td>
            <td width="10%">创建时间</td>
            <td width="10%">更新时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><select name="terminal_id" id="terminal_id_<?php echo $value['id']?>">
                        <?php foreach ($terminalIds as $key => $terminal){?>
                        <option <?php echo ($key == $value['terminal_id'] ? 'selected' : '') ?> value="<?php echo $key;?>"><?php echo $terminal?></option>
                        <?php }?>
                    </select>
                </td>
                <td><input class="version" type="text" name="version" id="version_<?php echo $value['id']?>" value="<?php echo $value['version']?>" /></td>
                <td><input class="file_size" type="text" name="file_size" id="file_size_<?php echo $value['id']?>" value="<?php echo $value['file_size']?>"/></td>
                <td><input class="file_path" type="text" name="file_path" id="file_path_<?php echo $value['id']?>" value="<?php echo $value['file_path']?>"/></td>
                <td><textarea type="text" name="description" id="description_<?php echo $value['id']?>" rows="3" cols="50"><?php echo $value['description']?></textarea></td>
                <td><input type="checkbox" name="is_force" id="is_force_<?php echo $value['id']?>" value="<?php echo $value['is_force']?>" <?php echo $value['is_force'] == 1 ? "checked" : "";?>></td>
                <td><input type="checkbox" name="status" id="status_<?php echo $value['id']?>" value="<?php echo $value['status']?>" <?php echo $value['status'] == 1 ? "checked" : "";?>></td>
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
                    <tbody><tr><th>客户端</th><th>版本号</th><th>文件大小</th><th>文件路径</th><th>描述</th><th>是否强制</th><th>发布状态</th></tr>
                    <tr>
                        <td><select name="terminal_id" id="terminal_id_<?php echo $value['id']?>">
                                <?php foreach ($terminalIds as $key => $terminal){?>
                                    <option <?php echo ($key == $value['terminal_id'] ? 'selected' : '') ?> value="<?php echo $key;?>"><?php echo $terminal?></option>
                                <?php }?>
                            </select>
                        </td>
                        <td><input class="inp1" type="text" name="version" value=""></td>
                        <td><input class="inp1" type="text" name="file_size" value=""></td>
                        <td><input class="inp1" type="text" name="file_path" value=""></td>
                        <td><input class="inp1" type="text" name="description" value=""></td>
                        <td><input type="checkbox" name="is_force" value="1"></td>
                        <td><input type="checkbox" name="status" value="1"></td>
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
            url:"release.php",
            data:{id : id,uid: uid,type : type},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='release.php?uid='+uid;
                }
            }
        });
    }

    function btn_edit(id, uid, langx, loginname) {
        var type = 'edit';
        var terminalId = $("#terminal_id_" + id).val();
        var version = $("#version_" + id).val();
        var fileSize = $("#file_size_" + id).val();
        var filePath = $("#file_path_" + id).val();
        var description = $("#description_" + id).val();
        var objIsForce = document.getElementById("is_force_"+id);
        var isForce = 0;
        if(objIsForce.checked){
            isForce = 1;
        }
        var objStatus = document.getElementById("status_"+id);
        var status = 0;
        if(objStatus.checked){
            status = 1;
        }

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"release.php",
            data:{
                id : id,
                uid : uid,
                langx : langx,
                loginname : loginname,
                type : type,
                terminal_id : terminalId,
                version : version,
                file_size : fileSize,
                file_path : filePath,
                description : description,
                is_force : isForce,
                status : status
            },
            success:function(response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='release.php?uid='+uid;
                }
            }
        })
    }
</script>
</body>
</html>