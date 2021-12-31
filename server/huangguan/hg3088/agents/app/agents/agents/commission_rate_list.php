<?php
/**
 * 代理退佣设置列表
 * Date: 2019/12/14
 */
include ("../../agents/include/address.mem.php");
require ("../../agents/include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$loginname = $_SESSION['UserName'];
$action = isset($_REQUEST['action']) && $_REQUEST['action'] ? $_REQUEST['action'] : '';
$money = isset($_REQUEST['money']) && $_REQUEST['money'] ? $_REQUEST['money'] : '';

if($action == 'del'){
    $sql = "DELETE FROM `" . DBPREFIX . "agent_commission_set` WHERE `money` = {$money}";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        insertLog("删除【>={$money}】成功");
        exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
    }else{
        insertLog("删除【>={$money}】失败");
        exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
    }
}

$sql = 'SELECT `id`, `money`, `rebate` FROM ' . DBPREFIX . 'agent_commission_set ORDER BY `money`';
$result = mysqli_query($dbLink, $sql);

$agentRebateSet = [];
while ($row = mysqli_fetch_assoc($result)){
    $agentRebateSet[$row['money']] = [
        'id' => $row['id'],
        'rebate' => json_decode($row['rebate'], true)
    ];
}

function insertLog($info){
    global $dbMasterLink, $loginname;
    $ipAddress = get_ip();
    $info = "代理退佣设置" . $info;
    $mysql = "insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url,Level) values('$loginname',now(),'$info','$ipAddress','".BROWSER_IP."','管理员')";
    mysqli_query($dbMasterLink, $mysql);
}
?>

<html>
<head>
    <title>代理退佣设置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td { padding: 3.5px 0 0  8px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>代理退佣设置</dt>
    <dd>
    </dd>
</dl>
<div class="main-ui" style="width: 100%">
    <table class="m_tab">
        <thead>
        <tr class="m_title">
            <td>会员输赢总和</td>
            <td colspan="2">体育赛事</td>
            <td colspan="2">彩票游戏</td>
            <td colspan="2">真人视讯</td>
            <td colspan="2">电子竞技</td>
            <td colspan="2">电子游艺</td>
            <td colspan="2">对战游戏</td>
            <td colspan="2">操作</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $i = 0;
        foreach ($agentRebateSet as $key => $value) {
            $i ++;
            $class = $i % 2 == 0 ? 'odd' : 'even';
            ?>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td rowspan="6">大于等于 <?php echo $key;?></td>
                <td>皇冠体育</td>
                <td><?php echo $value['rebate']['hg'];?>%</td>
                <td>体育彩票</td>
                <td><?php echo $value['rebate']['cp'];?>%</td>
                <td>AG视讯</td>
                <td><?php echo $value['rebate']['ag'];?>%</td>
                <td>泛亚电竞</td>
                <td><?php echo $value['rebate']['avia'];?>%</td>
                <td>AG电子</td>
                <td><?php echo $value['rebate']['ag_dianzi'];?>%</td>
                <td>开元棋牌</td>
                <td><?php echo $value['rebate']['ky'];?>%</td>
                <td rowspan="6">
                    <input type="button" value="编辑" class="za_button" onclick="location.href='commission_rate_set.php?action=edit&id=<?php echo $value['id'];?>'">
                    <input type="button" value="删除" class="za_button" onclick="btn_del('<?php echo $key;?>')">
                </td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票官方盘</td>
                <td><?php echo $value['rebate']['project'];?>%</td>
                <td>OG视讯</td>
                <td><?php echo $value['rebate']['og'];?>%</td>
                <td>雷火电竞</td>
                <td><?php echo $value['rebate']['fire'];?>%</td>
                <td>MG电子</td>
                <td><?php echo $value['rebate']['mg'];?>%</td>
                <td>VG棋牌</td>
                <td><?php echo $value['rebate']['vgqp'];?>%</td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票信用盘</td>
                <td><?php echo $value['rebate']['ssc'];?>%</td>
                <td>BBIN视讯</td>
                <td><?php echo $value['rebate']['bbin'];?>%</td>
                <td></td>
                <td></td>
                <td>CQ9电子</td>
                <td><?php echo $value['rebate']['cq'];?>%</td>
                <td>快乐棋牌</td>
                <td><?php echo $value['rebate']['klqp'];?>%</td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票追号</td>
                <td><?php echo $value['rebate']['trace'];?>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>MW电子</td>
                <td><?php echo $value['rebate']['mw'];?>%</td>
                <td>乐游棋牌</td>
                <td><?php echo $value['rebate']['lyqp'];?>%</td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>FG电子</td>
                <td><?php echo $value['rebate']['fg'];?>%</td>
                <td><!--皇冠棋牌--></td>
                <td><!-- <?php /*echo $value['rebate']['hgqp'];*/?>% --></td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>AG捕鱼王</td>
                <td><?php echo $value['rebate']['ag_dayu'];?>%</td>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="14"><input type="button" value="添加" class="za_button" onclick="location.href='commission_rate_set.php?action=add'"></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script>
    $(document).ready(function() {
        $('.even,.odd').on('mouseover', function() {
            var index = $(this).attr('data-row-index');
            var bgColor = 'rgb(204, 255, 204)';
            $("tr[data-row-index=" + index + "]").css('background-color', bgColor);
        });
        $('.even,.odd').on('mouseout', function() {
            var index = $(this).attr('data-row-index');
            $("tr[data-row-index=" + index + "]").css('background-color', '');
        });
    });

    function btn_del(money) {
        if(confirm("确定删除【>=" + money + "】的退佣设置？")) {
            $.ajax({
                type: "POST",
                url: "commission_rate_list.php",
                data: {action: 'del', money: money},
                dataType: 'JSON',
                success: function (response) {
                    alert(response.msg);
                    if (response.code == 0) {
                        window.location.href = 'commission_rate_list.php';
                    }
                }
            });
        }
    }
</script>