<?php
/**
 * 三方抽水设置列表
 * Date: 2019/12/28
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

$sql = 'SELECT `id`, `rebate` FROM ' . DBPREFIX . 'third_rebate_set ORDER BY `id`';
$result = mysqli_query($dbLink, $sql);

$thirdRebateSet = [];
while ($row = mysqli_fetch_assoc($result)){
    $thirdRebateSet[$row['id']] = json_decode($row['rebate'], true);
}
?>

<html>
<head>
    <title>三方抽水设置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td { padding: 3.5px 0 0  8px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>三方抽水设置</dt>
    <dd>
    </dd>
</dl>
<div class="main-ui" style="width: 100%">
    <table class="m_tab">
        <thead>
        <tr class="m_title">
            <td>ID</td>
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
        foreach ($thirdRebateSet as $key => $value) {
            $i ++;
            $class = $i % 2 == 0 ? 'odd' : 'even';
            ?>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td rowspan="6"><?php echo $key;?></td>
                <td>皇冠体育</td>
                <td><?php echo $value['hg'];?>%</td>
                <td>体育彩票</td>
                <td><?php echo $value['cp'];?>%</td>
                <td>AG视讯</td>
                <td><?php echo $value['ag'];?>%</td>
                <td>泛亚电竞</td>
                <td><?php echo $value['avia'];?>%</td>
                <td>AG电子</td>
                <td><?php echo $value['ag_dianzi'];?>%</td>
                <td>开元棋牌</td>
                <td><?php echo $value['ky'];?>%</td>
                <td rowspan="6">
                    <input type="button" value="编辑" class="za_button" onclick="location.href='third_water_set.php?action=edit&id=<?php echo $key;?>'">
                </td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票官方盘</td>
                <td><?php echo $value['project'];?>%</td>
                <td>OG视讯</td>
                <td><?php echo $value['og'];?>%</td>
                <td>雷火电竞</td>
                <td><?php echo $value['fire'];?>%</td>
                <td>MG电子</td>
                <td><?php echo $value['mg'];?>%</td>
                <td>VG棋牌</td>
                <td><?php echo $value['vgqp'];?>%</td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票信用盘</td>
                <td><?php echo $value['ssc'];?>%</td>
                <td>BBIN视讯</td>
                <td><?php echo $value['bbin'];?>%</td>
                <td></td>
                <td></td>
                <td>CQ9电子</td>
                <td><?php echo $value['cq'];?>%</td>
                <td>快乐棋牌</td>
                <td><?php echo $value['klqp'];?>%</td>
            </tr>
            <tr class="<?php echo $class;?>" data-row-index="<?php echo $i?>">
                <td></td>
                <td></td>
                <td>国民彩票追号</td>
                <td><?php echo $value['trace'];?>%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>MW电子</td>
                <td><?php echo $value['mw'];?>%</td>
                <td>乐游棋牌</td>
                <td><?php echo $value['lyqp'];?>%</td>
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
                <td><?php echo $value['fg'];?>%</td>
                <td><!--皇冠棋牌--></td>
                <td> <?php /*echo $value['hgqp'];*/?><!--% --></td>
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
                <td><?php echo $value['ag_dayu'];?>%</td>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
<!--        <tr>-->
<!--            <td colspan="14"><input type="button" value="添加" class="za_button" onclick="location.href='third_water_set.php?action=add'"></td>-->
<!--        </tr>-->
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
</script>