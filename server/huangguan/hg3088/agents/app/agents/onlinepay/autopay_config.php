<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";

include("../include/address.mem.php");
require_once("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];


//@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/pay_config.log');
// 调通后编写功能  新增、修改、删除
$type=$_REQUEST['type'];  // add   edit  delete
$che=$_REQUEST['chk'];   // 要修改的内容

$id=$_REQUEST['id'];
$title=$_REQUEST['title'];
$method=$_REQUEST['method'];
$business_code=$_REQUEST['code'];
$business_pwd=$_REQUEST['pwd'];
$pay_id=$_REQUEST['pay_id'];
$url=$_REQUEST['url'];
$status=$_REQUEST['sts'];

switch ($type){
    case 'del':
        $sql = "delete from `".DBPREFIX."gxfcy_autopay` WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        $res ? false : true;
        break;
    case 'edit':
        $sql = "UPDATE `".DBPREFIX."gxfcy_autopay` SET `business_code`='{$business_code}', `business_pwd`='{$business_pwd}', `url`='{$url}', `pay_id`='{$pay_id}', `status`='{$status}' WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        !$res ? false : true;
        break;
    case 'add':
        $sql = "insert `".DBPREFIX."gxfcy_autopay` values ('','{$title}','{$method}','{$business_code}','{$business_pwd}','{$pay_id}','{$url}','{$status}')";
        $res=mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());
        echo "<script> alert('添加成功！'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=autopay_config.php?uid=$uid'>";
        break;
    default: break;
}

$sql = "select *  from ".DBPREFIX."gxfcy_autopay order by ID ";
$res = mysqli_query($dbLink,$sql);
$lists = array();
while ($row = mysqli_fetch_assoc($res)){
    $row['class']=explode(',',$row['class']);
   $lists[$row['id']] = $row;
}
$listsJson = json_encode($lists);
$sqlUlevel = "select * from ".DBPREFIX."gxfcy_userlevel order by sort asc";
$resUlevel = mysqli_query($dbLink,$sqlUlevel);
while($row = mysqli_fetch_assoc($resUlevel)){
    $userLevelArr[$row['ename']] = $row;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>自动出款配置</title>
<style>
    .list-tab td{line-height: 20px;}
    .list-tab input{ float: left;}
</style>
</head>
<body>
<dl class="main-nav"><dt>自动出款配置</dt><dd></dd></dl>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="100">名称</td>
            <td>方法名</td>
            <td>商户号</td>
            <td>商户密匙</td>
            <td>终端号</td>
            <td>返回网址</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
    <?php

    foreach ($lists as $k=>$value){
    ?>
        <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
            <td width="100"><?php echo $value['title'];?></td>
            <td><?php echo $value['method'];?></td>
            <td><input type="text" class="business_code_<?php echo $value['id']?>" value="<?php echo $value['business_code']?>" /></td>
            <td><input type="text" class="business_pwd_<?php echo $value['id']?>" value="<?php echo $value['business_pwd']?>" /></td>
            <td><input type="text" class="pay_id_<?php echo $value['id']?>" value="<?php echo $value['pay_id']?>" /></td>
            <td><input type="text" class="url_<?php echo $value['id']?>" value="<?php echo $value['url']?>" /></td>
            <td>
                <input type="checkbox" name="sts" ID="sts_<?php echo $value['id']?>" <?php echo $value['status']==1?"checked":"";?> >
            </td>
            <td>

                <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>,'<?php echo $uid?>','<?php echo $langx?>','<?php echo $loginname?>')" value="修改" />
                <input type="button" onclick="btn_del(<?php echo $value['id']?>,'<?php echo $uid?>')" value="删除" />
            </td>
        </tr>
    <?php
    }

    ?>
        <tr class=m_cen >
            <td colspan="11">
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
                    <tbody><tr><th>名称</th><th>方法名</th><th>商户号</th><th>商户密匙</th><th>终端号</th><th>返回网址</th><th>启用状态</th></tr>
                    <tr>
                        <td><input class="inp1" type="text" name="title" value=""></td>
                        <td><input class="inp1" type="text" name="method" value=""></td>
                        <td><input class="inp1" type="text" name="code" value=""></td>
                        <td><input class="inp1" type="text" name="pwd" value=""></td>
                        <td><input class="inp1" type="text" name="pay_id" value=""></td>
                        <td><input class="inp1" type="text" name="url" value=""></td>
                        <td><input type="checkbox" name="sts" value="1"></td>
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
            url:"autopay_config.php",
            data:{id:id,uid: uid,type:type},
            success:function (data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='autopay_config.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        });
    }

    function btn_edit(id,uid,langx,loginname) {
        var type = 'edit';
        var business_code = $(".business_code_"+id).val();
        var business_pwd = $(".business_pwd_"+id).val();
        var pay_id = $(".pay_id_"+id).val();
        var url = $(".url_"+id).val();
        var minCurrency = $(".minCurrency_"+id).val();
        var maxCurrency = $(".maxCurrency_"+id).val();
        var obj = document.getElementById("sts_"+id);
        var sts = 0;

        if(obj.checked){
            sts = 1;
        }

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"autopay_config.php",
            data:{
                id: id,
                uid: uid,
                langx: langx,
                loginname: loginname,
                type: type,
                code: business_code,
                pwd: business_pwd,
                pay_id: pay_id,
                url: url,
                sts: sts
            },
            success:function(data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='autopay_config.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        })
    }



</script>
</body>
</html>


