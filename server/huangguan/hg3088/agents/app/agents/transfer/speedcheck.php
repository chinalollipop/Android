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

$type=$_REQUEST['type'];  // add   edit  delete
$che=$_REQUEST['chk'];   // 要修改的内容

$id=$_REQUEST['id'];
$pay_id=$_REQUEST['pay_id'];
$url=$_REQUEST['url'];
$status=$_REQUEST['sts'];

switch ($type){
    case 'del':
        $sql = "delete from `".DBPREFIX."web_domain` WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        $res ? false : true;
        break;
    case 'edit':
        $Url=$_REQUEST['Url'];
        $status=$_REQUEST['status'];
        $Mac=$_REQUEST['mac'];
        $sql = "UPDATE `".DBPREFIX."web_domain` SET `Url`='{$Url}', `type`={$status}, `ios`={$Mac} WHERE `ID` = {$id}";
        echo $sql;
        $res = mysqli_query($dbMasterLink,$sql);
        !$res ? false : true;
        break;
    case 'add':
        $url=$_REQUEST['title'];
        $Mac=$_REQUEST['Mac'];
        $sqlCheck = "select ID from `".DBPREFIX."web_domain` where Url='{$url}'";
        $resCheck=mysqli_query($dbLink,$sqlCheck);
        $cou=mysqli_num_rows($resCheck);
        if($cou==1){
            echo "<script> alert('该域名已经存在！'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=speedcheck.php?uid=$uid'>";
        }elseif($cou==0){
            $type=$_REQUEST['status'];
            $sql = "insert `".DBPREFIX."web_domain`(`Url`,`type`,`ios`) values ('{$url}','{$type}','{$Mac}')";
            $res=mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());
            echo "<script> alert('添加成功！'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=speedcheck.php?uid=$uid'>";
        }
        break;
    default: break;
}

$sql = "select ID,Url,type,ios from ".DBPREFIX."web_domain order by ID desc";
$res = mysqli_query($dbLink,$sql);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>域名配置</title>
<style>
    .list-tab td{line-height: 20px;}
    .list-tab input{ float: left;}
</style>
</head>
<body>
<dl class="main-nav"><dt>中转站域名配置</dt><dd><b><span onclick="javascript:$('#adds').show();" >新增域名</span></b></dd></dl>

<div class="main-ui">
    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="uid" value="<?php echo $uid?>" />
                <input type="hidden" name="langx" value="<?php echo $langx?>" />
                <input type="hidden" name="type" value="add" />
                <table class="m_tab">
                    <tbody><tr><th></th><th>地址</th><th>状态</th><th>Mac版本</th></tr>
                    <tr>
                        <td></td>
                        <td><input class="inp1" type="text" name="title" value="" size="50"></td>
                        <td>
                            <input class="inp1" type="radio" name="status" checked value=1>开启
                            <input class="inp1" type="radio" name="status"  value=0>关闭
                        </td>
                        <td>
                            <input class="inp1" type="radio" name="Mac" checked value=1>开启
                            <input class="inp1" type="radio" name="Mac"  value=0>关闭
                        </td>
                        <td></td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="11">
                            <input type="button" value="确定" class="za_button btn2" onclick="javascript:$('#newsadd').submit();">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="100">编号</td>
            <td>地址</td>
            <td>状态</td>
            <td>Mac版</td>
            <td>操作</td>
        </tr>
    <?php
    while ($row = mysqli_fetch_assoc($res)){
    ?>
            <tr class=m_cen" style="text-align: center;">
                <td width="100"><?php echo $row['ID'];?></td>
                <td><input type="text" value="<?php echo $row['Url'];?>" name="Url<?php echo $row['ID']; ?>" size="50"></td>
                <td width="100">
                    <input class="inp1" type="radio" name="status<?php echo $row['ID']; ?>" <?php if($row['type']==1){ echo "checked"; }?> value=1>开启
                    <input class="inp1" type="radio" name="status<?php echo $row['ID']; ?>" <?php if($row['type']==0){ echo "checked"; }?> value=0>关闭
                </td>
                <td width="100">
                    <input class="inp1" type="radio" name="Mac<?php echo $row['ID']; ?>" <?php if($row['ios']==1){ echo "checked"; }?> value=1>开启
                    <input class="inp1" type="radio" name="Mac<?php echo $row['ID']; ?>" <?php if($row['ios']==0){ echo "checked"; }?> value=0>关闭
                </td>
                <td>
                    <input type="button" class="za_button btn_edit_<?php echo $row['ID']?>" onclick="btn_edit(<?php echo $row['ID']?>,'<?php echo $uid?>','<?php echo $langx?>','<?php echo $loginname?>')" value="修改" />
                    <input type="button" class="za_button" onclick="btn_del(<?php echo $row['ID']?>,'<?php echo $uid?>')" value="删除" />
                </td>
        </tr>
    <?php
    }
    ?>
    </table>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_del(id,uid) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"speedcheck.php",
            data:{id:id,uid: uid,type:type},
            success:function (data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='speedcheck.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        });
    }

    function btn_edit(id,uid,langx,loginname) {
        var type = 'edit';
        var Url = $("input[name=Url"+id+"]").val();
        var status = $("input[name=status"+id+"]:checked").val();
        var mac = $("input[name=Mac"+id+"]:checked").val();
        if(Url.length>5 && (status==1 || status==0)){
            $.ajax({
                type:"POST",
                url:"speedcheck.php",
                data:{
                    id: id,
                    uid: uid,
                    langx: langx,
                    loginname: loginname,
                    type: type,
                    Url: Url,
                    status: status,
                    mac: mac
                },
                success:function(data) {
                    if (data){
                        alert('更新成功！');
                        window.location.href='speedcheck.php?uid='+uid;
                    }else{
                        alert('更新失败！！');
                    }
                }
            })
        }else{
            alert('数据格式错误！');
        }
    }

</script>
</body>
</html>
