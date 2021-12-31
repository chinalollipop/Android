<?php
/**
 * 会员注册设置
 * Date: 2020/1/14
 */
require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$redisObj = new Ciredis();

$loginname = $_SESSION['UserName'];

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
$name = $_REQUEST['name'];
$isOn = $_REQUEST['is_on'];
$now = date('Y-m-d H:i:s');
$codeOpen = isset($_REQUEST["codeOpenSwitch"]) && $_REQUEST["codeOpenSwitch"] ? $_REQUEST["codeOpenSwitch"] : 'false'; // 是否开启验证码

$codeOpenSwitch = $redisObj->getSimpleOne('code_open_switch'); // 取redis 设置的值
if(!$codeOpenSwitch){
    $codeOpenSwitch ='';
}
// echo $codeOpenSwitch.'==';
switch ($type){
    case 'edit':
        $msg = $isOn == 1 ? '必填' : '不必填';
        $sql = "UPDATE `" . DBPREFIX . "member_register_set` SET `is_on`='{$isOn}', `created_at`='{$now}', `updated_at`='{$now}' WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            insertLog("设置【{$name}】为：【{$msg}】成功");
            refreshCache();
            exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        }else{
            insertLog("设置【{$name}】为：【{$msg}】失败");
            exit(json_encode(['code' => -1, 'msg' => '更新失败！']));
        }
        break;
    case 'switch': // 验证码开关
        $redisObj->setOne('code_open_switch',$codeOpen) ;
        exit(json_encode(['code' => 0, 'msg' => '更新成功！']));
        break;
    default: break;
}

$sql = "SELECT `id`, `name`, `item`, `is_on`, `created_at`, `updated_at` FROM " . DBPREFIX . "member_register_set";
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

// 更新缓存
function refreshCache(){
    global $dbLink,$redisObj;
    $sql = "SELECT `id`, `item`, `is_on` FROM " . DBPREFIX . "member_register_set";
    $result = mysqli_query($dbLink, $sql);
    $lists = array();
    while ($row = mysqli_fetch_assoc($result)){
        $lists[$row['item']] = $row['is_on'];
    }
    $agentFeeSet = json_encode($lists);

    $redisObj->setOne('member_register_set', $agentFeeSet);
}

function insertLog($info){
    global $dbMasterLink, $loginname;
    $ipAddress = get_ip();
    $info = "会员注册设置" . $info;
    $mysql = "insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url,Level) values('$loginname',now(),'$info','$ipAddress','".BROWSER_IP."','管理员')";
    mysqli_query($dbMasterLink, $mysql);
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>会员注册管理</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>
<dl class="main-nav"><dt>会员注册管理</dt><dd></dd></dl>
<div class="main-ui">
    <table class="m_tab" style="margin-bottom: 20px;">
        <tbody>
            <tr class="m_title" >
                <td >序号</td>
                <td >前台注册/登录是否开启验证码</td>
                <td >操作</td>
            </tr>
            <tr class="m_title" >
                <td> 1 </td>
                <td> <input type="checkbox" name="codeOpenSwitch" value="true" <?php echo  $codeOpenSwitch=='true'?'checked':'';?> ></td>
                <td> <input type="button" class="za_button"  value="提交" onclick="btn_edit('1','switch')"> </td>
            </tr>
        </tbody>

    </table>

    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="5%">ID</td>
            <td width="10%">要求字段</td>
            <td width="5%">是否必填</td>
            <td width="20%">创建时间</td>
            <td width="20%">更新时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <input type="hidden" name="name" id="name_<?php echo $value['id']?>" value="<?php echo $value['name']?>" />
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['name']?></td>
                <td>
                    <select name="is_on" id="is_on_<?php echo $value['id']?>">
                        <option value="0" <?php echo $value['is_on'] == 0 ? "selected" : "";?>>否</option>
                        <option value="1" <?php echo $value['is_on'] == 1 ? "selected" : "";?>>是</option>
                    </select>
                </td>
                <td><?php echo $value['created_at']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td>
                    <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>,'edit')" value="修改" />
                </td>
            </tr>
            <?php
        }

        ?>
    </table>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_edit(id,type) {
        var name = $("#name_" + id).val();
        var is_on = $("#is_on_" + id).val();
        var datapars = {
            id : id,
            type : type,
            name : name,
            is_on : is_on
        };
        if(type =='switch'){ // 验证码开关
            datapars.codeOpenSwitch = $('input[name="codeOpenSwitch"]:checked').val();
        }
        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"register_set.php",
            data:datapars,
            success:function(response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='register_set.php';
                }
            }
        })
    }
</script>
</body>
</html>