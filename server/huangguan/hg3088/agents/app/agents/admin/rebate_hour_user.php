<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$type = $_REQUEST['type'];
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;
$username = $_REQUEST['username'];

if($type == 'add'){

    $username = $_REQUEST['username'];

    $result = mysqli_query($dbLink,"select id from  ".DBPREFIX.MEMBERTABLE." where UserName='{$username}'");
    $cou=mysqli_num_rows($result); // 总数
    if($cou==0){
        exit(json_encode(['err'=>-2,'msg'=>'用户名不存在，请重新输入']));
    }

    $result = mysqli_query($dbLink,"select id from `".DBPREFIX."rebate_hour_users` where UserName='{$username}'");
    $cou=mysqli_num_rows($result); // 总数
    if($cou>0){
        exit(json_encode(['err'=>-3,'msg'=>'用户名已加入，无需重复添加']));
    }

    $res=mysqli_query($dbMasterLink,"insert `".DBPREFIX."rebate_hour_users` values ('','{$username}')");
    if ($res){
        exit(json_encode(['err'=>0,'msg'=>'']));
    }else{
        exit(json_encode(['err'=>-5,'msg'=>'添加错误，请检查数据！']));
    }
}

if($type == 'delete'){

    $id = $_REQUEST['id'];
    if($id == '' || $id == false || !isset($id) ){
        exit(json_encode(['err'=>-6,'msg'=>'删除错误，请检查数据！']));
    }
    $res=mysqli_query($dbMasterLink,"delete from `".DBPREFIX."rebate_hour_users` where id = $id");
    if ($res){
        exit(json_encode(['err'=>0,'msg'=>'']));
    }else{
        exit(json_encode(['err'=>-7,'msg'=>'删除错误，请检查数据！']));
    }
}

$sWhere = 1;
$username != '' ? $sWhere .= " AND `username` = '$username' " : '';


$mysql = "select * from ".DBPREFIX."rebate_hour_users where $sWhere order by id desc";
$result = mysqli_query($dbLink, $mysql);
$count = mysqli_num_rows($result);

// 分页
$page_size = 50;
$page_count = ceil($count / $page_size);
$offset = $page * $page_size;
$mysql = $mysql . "  limit $offset, $page_size";
$result = mysqli_query($dbLink, $mysql);


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>时时返水</title>
    <style type="text/css">
        .rebate_user_search{width: 40px; height: 25px; margin: auto; text-align: center; line-height: 25px; background-color: #ffffbe; border: 1px solid #000000;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>时时返水</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td style="line-height: 33px;">
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
                        <input type="TEXT" name="username" size="10" value="<?php echo $username;?>" maxlength="20" class="za_text" placeholder="用户名">
                        <input type="SUBMIT" name="SUBMIT" value="确认" class="za_button">
                    </td>
                </tr>

            </table>
        </form>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <tr class="m_title">
            <td>ID</td>
            <td>时时返水用户名</td>
            <td>操作</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){

            ?>
            <tr class="m_rig">
                <td ><?php echo $row['id']?></td>
                <td ><?php echo $row['username']?></td>
                <td>
                    <input type="button" value="删除" class="za_button" onclick="rebate_hour_user_del('<?php echo $row['id'];?>')">
                </td>
            </tr>
        <?php }?>

        <tr>
            <td colspan="6">
                <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)">
                <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增">
            </td>
        </tr>
    </table><br><br>
    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="type" value="add" />
                <table class="m_tab">
                    <tr style="font-weight: bold;"><td>新增时时返水用户名</td></tr>
                    <tr>
                        <td><input class="inp1" type="text" id="username" name="username" value=""></td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="5">
                            <input type="button" value="新增" class="za_button btn2" onclick="rebate_hour_user_add()">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>

                </table>

            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">

    function rebate_hour_user_del(id) {
        var type = 'delete';
        var pams = {};
        pams.type = type;
        pams.id = id;
        $.ajax({
            type:"POST",
            url:"rebate_hour_user.php",
            data:pams,
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0){
                    alert('删除成功！');
                }else{
                    alert(ret.msg);
                }
                location.reload();
            }
        })
    }

    function rebate_hour_user_add() {
        var type = 'add';
        var pams = {};
        pams.type = type;
        pams.username = $("#username").val();
        $.ajax({
            type:"POST",
            url:"rebate_hour_user.php",
            data: pams,
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0) {
                    alert("添加成功");
                }else{
                    alert(ret.msg);
                }
                location.reload();
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        })

    }

</script>
</body>
</html>


