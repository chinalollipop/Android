<?php
/**
 * 会员分层管理
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

$type = $_REQUEST['type'];

switch ($type){
    case 'add':
        $title = $_REQUEST['title'];
        $remark = $_REQUEST['remark'];
        $status = $_REQUEST['status'];

        if(!isset($title)){
            exit(json_encode(['err'=>-1,'msg'=>'标题错误，请重新输入']));
        }
        if(!isset($remark)){
            exit(json_encode(['err'=>-2,'msg'=>'备注错误，请重新输入']));
        }
        if (!in_array($status, [0, 1])){ //
            exit(json_encode(['err'=>-3,'msg'=>'启用状态错误，请重新选择']));
        }

        $now = date('Y-m-d H:i:s');
        $res=mysqli_query($dbMasterLink,"insert `".DBPREFIX."web_member_data_layer` values ('','{$title}','{$remark}','{$status}', '0','{$now}','{$now}')");
        if ($res){
            exit(json_encode(['err'=>0,'msg'=>'']));
        }else{
            exit(json_encode(['err'=>-5,'msg'=>'添加错误，请检查数据！']));
        }

        break;
    case 'edit':
        $id = $_REQUEST['id'];
        $edt_status = $_REQUEST['edt_status'];
        $sql = "UPDATE `".DBPREFIX."web_member_data_layer` SET `status`='{$edt_status}' WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        if ($res){
            exit(json_encode(['err'=>0,'msg'=>'']));
        }else{
            exit(json_encode(['err'=>-5,'msg'=>'更新错误，请检查数据！']));
        }

        break;
    case 'del':

        $sql = "DELETE FROM `" . DBPREFIX . "web_member_data_layer` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    default:

        break;
}
$searchWhere = ' WHERE 1 ';
if($title){
    $searchWhere .= ' AND `title` like "%' . $title . '%"';
}
$sql = "SELECT * FROM " . DBPREFIX . "web_member_data_layer" . $searchWhere . ' ORDER BY `id` DESC';
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
    <title>会员分层活动</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav"><dt>会员分层活动</dt>
        <dd>
            <table >
                <tr class="m_tline">
                </tr>
            </table>
        </dd>
    </dl>
</FORM>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="15%">分层标题</td>
            <td width="20%">备注 </td>
            <td width="15%">修改时间</td>
            <td width="5%">启用</td>
            <td width="10%">旗下会员</td>
<!--            <td width="5%">默认</td>-->
            <td width="10%">操作</td>
        </tr>
        <tr  class="m_title" >
            <td colspan="4"></td>
            <td>
                <?php
                echo "<a class='a_link' href=user_browse.php?uid=$uid&langx=$langx&lv=MEM&userlv=$level&layer=0 >新会员</a>";
                ?>
            </td>
            <td></td>
        </tr>
        <?php
        if(!empty($lists)){
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['title']?></td>
                <td><?php echo $value['remark']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td>
                    <!--<select id="edt_status">
                        <option value="1" <?php /*echo $value['status']==1?'selected':''; */?>>是</option>
                        <option value="0" <?php /*echo $value['status']==0?'selected':''; */?>>否</option>
                    </select>-->
                    <input type="checkbox" name="edt_status" ID="edt_status_<?php echo $value['id']?>" <?php echo $value['status']==1?"checked":"";?> >
                </td>
                <td>
                    <?php
                    echo "<a class='a_link' href=user_browse.php?uid=$uid&langx=$langx&lv=MEM&userlv=$level&layer=".$value['id']." >旗下会员</a>";
                    ?>
                </td>
<!--                <td>--><?php //echo 1 == $value['default'] ? '是' : '否';?><!--</td>-->
                <td>
                    <input type="button" class="za_button" onclick="btn_edt(<?php echo $value['id']?>)" value="修改" />
                </td>
            </tr>
        <?php }}else{
        ?>
        <tr class=m_cen >
            <td colspan="6">
                暂无会员分层
            </td>
        </tr>
        <?php }?>


        <tr>
            <td colspan="6">
                <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)">
                <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增">
            </td>
        </tr>
    </table>
    <br><br>
    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="type" value="add" />
                <table class="m_tab">
                    <tr style="font-weight: bold;"><td>分层标题</td><td>备注</td><td>启用</td></tr>
                    <tr>
                        <td><input class="inp1" type="text" id="title" name="title" value=""></td>
                        <td><input class="inp1" size="70px" type="text" id="remark" name="remark" value=""></td>
                        <td>
                            <select name="status" id="status">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="3">
                            <input type="button" value="新增" class="za_button btn2" onclick="user_layer_add()">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>

                </table>

            </form>
        </div>
    </div>

</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_del(id) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"article.php",
            data:{id : id,type : type},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='article.php';
                }
            }
        });
    }
    
    function btn_edt(id) {
        var type = 'edit';
        var obj = document.getElementById("edt_status_"+id);
        var edt_status = 0;
        if(obj.checked){
            edt_status = 1;
        }
        var pams = {};
        pams.id = id;
        pams.type = type;
        pams.edt_status = edt_status;
        $.ajax({
            type:"POST",
            url:"user_layer.php",
            data: pams,
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0) {
                    alert("更新成功");
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

    function user_layer_add() {
        var type = 'add';
        var pams = {};
        pams.type = type;
        pams.title = $("#title").val();
        pams.remark = $("#remark").val();
        pams.status = $("#status").val();
        $.ajax({
            type:"POST",
            url:"user_layer.php",
            data: pams,
            success:function(ret) {
                console.log(ret);
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