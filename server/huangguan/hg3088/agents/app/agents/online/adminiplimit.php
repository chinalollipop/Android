<?php
/**
 * 限制后台IP
 * Date: 2019/11/4
 */
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
$page = isset($_REQUEST["page"]) && $_REQUEST["page"] ? intval($_REQUEST["page"]) : 0;
$langx = $_SESSION["langx"];
$loginId = $_SESSION['ID'];
$loginname = $_SESSION['UserName'];

$type = $_REQUEST['type'];
$ip = $_REQUEST['ip'];
$reason = $_REQUEST['reason'];
$id = $_REQUEST['id'];
$now = date('Y-m-d H:i:s');
switch ($type){
    case 'del':
        $sql = "DELETE FROM `" . DBPREFIX . "admin_ip_data` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result && refreshCache()){
            insertLog("删除【{$ip}】成功");
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            insertLog("删除【{$ip}】失败");
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    case 'edit':
        $sql = "UPDATE `" . DBPREFIX . "admin_ip_data` SET `ip`='{$ip}', `admin_user_id`='{$loginId}', `admin_username`='{$loginname}',`admin_reason`='{$reason}', `created_at`='{$now}', `updated_at`='{$now}' WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result && refreshCache()){
            insertLog("更新【{$ip}】成功");
            exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        }else{
            insertLog("更新【{$ip}】失败");
            exit(json_encode(['code' => -1, 'msg' => '更新失败！']));
        }
        break;
    case 'add':
        $sql = "INSERT INTO `" . DBPREFIX . "admin_ip_data`(`ip`,`admin_user_id`,`admin_username`,`admin_reason`,`created_at`,`updated_at`) VALUES ('{$ip}','{$loginId}','{$loginname}','{$reason}','{$now}','{$now}')";
        $insertId = mysqli_query($dbMasterLink, $sql);
        if($insertId && refreshCache()){
            insertLog("添加【{$ip}】成功");
            echo "<script> alert('添加成功！'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=adminiplimit.php?uid=$uid'>";
        }else{
            insertLog("添加【{$ip}】失败");
            echo "<script> alert('添加失败！'); </script>";
        }
        break;
    default: break;
}

$searchWhere = ' WHERE 1=1 ';
if($ip && !$type){
    $searchWhere .= ' AND `ip` = "' . $ip . '"';
}

$sql = "SELECT `id`, `ip`, `admin_user_id`, `admin_username`,`admin_reason`, `created_at`, `updated_at` FROM " . DBPREFIX . "admin_ip_data" . $searchWhere . ' ORDER BY `id` ASC' ;
$result = mysqli_query($dbLink, $sql);
$count = mysqli_num_rows($result);

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$sql = $sql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $sql);

// 单页数据
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

// 更新缓存
function refreshCache(){
    global $dbMasterLink;
    $sql = "SELECT `id`, `ip` FROM " . DBPREFIX . "admin_ip_data";
    $result = mysqli_query($dbMasterLink, $sql);
    $lists = array();
    while ($row = mysqli_fetch_assoc($result)){
        $lists[] = $row['ip'];
    }
    $adminIpList = json_encode($lists);

    $redisObj = new Ciredis();
    $redisObj->setOne('admin_ip_list',$adminIpList) ; // 更新redis

    // 存储白名单文件
    $filePath = CACHE_DIR . '/agents/tmp/ipwhitelist.txt';
    if(!file_put_contents($filePath, $adminIpList)){
        return false;
    }
    return true;
}

function insertLog($info){
    global $dbMasterLink, $loginname;
    $ipAddress = get_ip();
    $info = "后台IP白名单" . $info;
    $mysql = "insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url,Level) values('$loginname',now(),'$info','$ipAddress','".BROWSER_IP."','管理员')";
    mysqli_query($dbMasterLink, $mysql);
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>后台IP白名单管理</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>

<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav"><dt>后台IP白名单</dt>
        <dd>
            <table >
                <tr class="m_tline">
                    <td>&nbsp;&nbsp;&nbsp;
                        IP地址：
                        <input type="text" id="dlg_text" name="ip" value="<?php if(!$type) echo $ip;?>" class="za_text" size="15" placeholder="请输入关键字">
                        &nbsp;&nbsp;&nbsp;
                        共<?php echo $cou?>条
                        <select name='page'>
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
                        <input type="submit" id="dlg_ok" value="查询" class="za_button">
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
</FORM>

<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td >ID</td>
            <td >IP</td>
            <td >管理员</td>
            <td >添加原因</td>
            <td >创建时间</td>
            <td >更新时间</td>
            <td >操作</td>
        </tr>
        <?php
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><input class="ip" type="text" name="ip" id="ip_<?php echo $value['id']?>" value="<?php echo $value['ip']?>" /></td>
                <td><?php echo $value['admin_username']?></td>
                <!--<td><?php /*echo $value['admin_reason']*/?></td>-->
                <td><input class="reason" type="text" name="admin_reason" id="reason_<?php echo $value['id']?>" value="<?php echo $value['admin_reason']?>" /></td>
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
                    <tbody><tr><td>IP</td><td>添加原因</td></tr>
                    <tr>
                        <td><input class="inp1 ip_add" type="text" name="ip" value=""></td>
                        <td><input class="inp1 ip_reason" type="text" name="reason" value=""></td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="11">
                            <input type="button" value="新增" class="za_button btn2" onclick="javascript:addNewIp();">
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

    // 新增IP
    function addNewIp() {
        var ip = $('.ip_add').val();
        var reason = $('.ip_reason').val();
        if(ip=='' || reason==''){
            alert('请填写需要添加的IP和添加原因');
            return;
        }
        $('#newsadd').submit();
    }

    function btn_del(id,uid) {
        var type = 'del';
        var ip = $("#ip_" + id).val();
        $.ajax({
            type: "POST",
            url:"adminiplimit.php",
            data:{id : id,uid: uid,type : type,ip :ip},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='adminiplimit.php?uid='+uid;
                }
            }
        });
    }

    function btn_edit(id, uid, langx, loginname) {
        var type = 'edit';
        var ip = $("#ip_" + id).val();
        var reason = $("#reason_" + id).val();

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"adminiplimit.php",
            data:{
                id : id,
                uid : uid,
                langx : langx,
                loginname : loginname,
                type : type,
                ip : ip,
                reason : reason
            },
            success:function(response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='adminiplimit.php?uid='+uid;
                }
            }
        })
    }
</script>
</body>
</html>
