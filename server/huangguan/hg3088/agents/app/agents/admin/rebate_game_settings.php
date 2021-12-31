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
$uid=$_REQUEST["uid"];
$type = $_REQUEST['type'];

if($type == 'add'){

    $rebate_name = $_REQUEST['rebate_name'];
    $rebate = $_REQUEST['rebate'];
    $game_type = $_REQUEST['game_type'];
    $left_interval = $_REQUEST['left_interval'];
    $right_interval = $_REQUEST['right_interval'];

    if(!isset($rebate_name)){
        exit(json_encode(['err'=>-1,'msg'=>'名称错误，请重新输入']));
    }

    if(!isset($rebate) or $rebate >= 1){
        exit(json_encode(['err'=>-2,'msg'=>'返水错误，请重新输入']));
    }

    if (!in_array($game_type, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18])){ // 9 乐游棋牌 10 MG电子 11 泛亚电竞 12 OG视讯 13 CQ9电子 14 MW电子 15 FG电子 16 BBIN视讯 17快乐棋牌 18雷火电竞
        exit(json_encode(['err'=>-3,'msg'=>'类型错误，请重新选择']));
    }

    if ($left_interval < 1 or $right_interval <1 or $right_interval<= $left_interval ){
        exit(json_encode(['err'=>-4,'msg'=>'区间有误，请重新输入']));
    }

    $res=mysqli_query($dbMasterLink,"insert `".DBPREFIX."rebate_game_settings` values ('','{$rebate_name}','{$rebate}','{$game_type}','{$left_interval}','{$right_interval}')");
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
    $res=mysqli_query($dbMasterLink,"delete from `".DBPREFIX."rebate_game_settings` where id = $id");
    if ($res){
        exit(json_encode(['err'=>0,'msg'=>'']));
    }else{
        exit(json_encode(['err'=>-7,'msg'=>'删除错误，请检查数据！']));
    }
}


$result_data = mysqli_query($dbLink,"select * from ".DBPREFIX."rebate_game_settings ");
$cou=mysqli_num_rows($result_data);
if ($cou>0){
    $data=[];
    while ($row = mysqli_fetch_assoc($result_data)){
        $data[]=$row;
    }
}



?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>游戏返点设置</title>
    <style type="text/css">
        .rebate_user_search{width: 40px; height: 25px; margin: auto; text-align: center; line-height: 25px; background-color: #ffffbe; border: 1px solid #000000;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>返点查询</dt>
    <dd></dd>
</dl>
<div class="main-ui">

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" style="font-weight: bold;" >
            <td>ID</td>
            <td>名称</td>
            <td>返水%</td>
            <td>类型</td>
            <td>区间</td>
            <td>操作</td>
        </tr>
            <?php
            if (count($data) == 0){
                ?>
                <tr><td colspan="8"><br>没有数据<br></td></tr>
            <?php } ?>
            <?php
            foreach ($data as $k => $v){
            ?>
                <tr>
                    <td><?php echo $v['id'];?></td>
                    <td><?php echo $v['rebate_name'];?></td>
                    <td><?php echo $v['rebate'] * 100 . ' %';?></td>
                    <td>
                        <?php
                        $game_type_data = [1 => '体育', 2 => 'AG视讯',3 => '彩票', 4 => '开元棋牌',5 => 'AG电子',6 => 'AG捕鱼王', 7 => '皇冠棋牌', 8 => 'VG棋牌', 9 => '乐游棋牌', 10 => 'MG电子', 11 => '泛亚电竞', 12 => 'OG视讯', 13 => 'CQ9电子', 14 => 'MW电子', 15 => 'FG电子', 16 => 'BBIN视讯', 17 => '快乐棋牌', 18 => '雷火电竞'];
                        echo $game_type_data[$v['game_type']];
                        ?>
                    </td>
                    <td><?php echo $v['left_interval'].' - '.$v['right_interval'];?></td>
                    <td>
                        <input type="button" value="删除" class="za_button" onclick="rebate_del('<?php echo $v['id'];?>')">
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="6">
                    <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)">
                    <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增">
                </td>
            </tr>
        </form>
    </table>
    <br><br>
    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="type" value="add" />
                <table class="m_tab">
                    <tr style="font-weight: bold;"><td>名称</td><td>返水（例：0.8% 请输入 0.008）</td><td>类型</td><td>区间</td></tr>
                    <tr>
                        <td><input class="inp1" type="text" id="rebate_name" name="rebate_name" value=""></td>
                        <td><input class="inp1" type="text" id="rebate" name="rebate" value=""></td>
                        <td>
                            <select name="game_type" id="game_type">
                                <option value="1">体育</option>
                                <option value="2">AG视讯</option>
                                <option value="5">AG电子</option>
                                <option value="6">AG捕鱼王</option>
                                <option value="3">彩票</option>
                                <option value="4">开元棋牌</option>
                               <!-- <option value="7">皇冠棋牌</option>-->
                                <option value="8">VG棋牌</option>
                                <option value="9">乐游棋牌</option>
                                <option value="10">MG电子</option>
                                <option value="11">泛亚电竞</option>
                                <option value="12">OG视讯</option>
                                <option value="13">CQ9电子</option>
                                <option value="14">MW电子</option>
                                <option value="15">FG电子</option>
                                <option value="16">BBIN视讯</option>
                                <option value="17">快乐棋牌</option>
                                <option value="18">雷火电竞</option>
                            </select>
                        </td>
                        <td>
                            <input class="inp1" type="text" id="left_interval" name="left_interval" value="">
                            <input class="inp1" type="text" id="right_interval" name="right_interval" value="">
                        </td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="5">
                            <input type="button" value="新增" class="za_button btn2" onclick="rebate_add()">
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

    var uid = '<?php echo $uid;?>';
    function rebate_del(id) {
        var type = 'delete';
        $.ajax({
            type:"POST",
            url:"rebate_game_settings.php",
            data:{
                id: id,
                uid: uid,
                type: type,
            },
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0){
                    alert('更新成功！');
                }else{
                    alert(ret.msg);
                }
                location.reload();
            }
        })
    }

    function rebate_add() {
        var type = 'add';
        var pams = {};
        pams.uid = uid;
        pams.type = type;
        pams.rebate_name = $("#rebate_name").val();
        pams.rebate = $("#rebate").val();
        pams.game_type = $("#game_type").val();
        pams.left_interval = $("#left_interval").val();
        pams.right_interval = $("#right_interval").val();
        $.ajax({
            type:"POST",
            url:"rebate_game_settings.php",
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


