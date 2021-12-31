<?php
/**
 * 意见投诉
 * Date: 2019/10/3
 */
session_start();
require_once '../include/config.inc.php';
include_once ("../include/address.mem.php");
require_once '../include/redis.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$aCategory = array(
    'tiyu'=>'体育赛事',
    'live'=>'视讯直播',
    'dianzi'=>'电子游艺',
    'caipiao'=>'彩票游戏',
    'qipai'=>'棋牌游戏',
    'buyu'=>'捕鱼',
    'dianjing'=>'电子竞技',
    'youhui'=>'优惠活动',
    'appdown'=>'APP下载',
    'jianyi'=>'建议/投诉',
);

// 接收参数
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、游戏、房间、局号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';

$sWhere = 1;
$sWhere .= " AND `createtime` BETWEEN '{$startTime}' AND '{$endTime}'";

$mysql = "SELECT * 
          FROM `" . DBPREFIX . "web_opinion_complaint`
          WHERE $sWhere 
          ORDER BY `id` DESC";
//echo $mysql; die;
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
    <title>意见投诉</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .main-ui{width: 100%}
        .mem_total_money td span{ color:red;}
        input.za_text {width: 142px;}
    </style>
    <script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
</head>
<body >
<dl class="main-nav">
    <dt>意见投诉</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <?php
                        $match_day_before_yesterday_start = date('Y-m-d 00:00:00',time()-86400*2);
                        $match_day_before_yesterday_end = date('Y-m-d 23:59:59',time()-86400*2);
                        $match_date_yesterday_start = date('Y-m-d 00:00:00',time()-86400);
                        $match_date_yesterday_end = date('Y-m-d 23:59:59',time()-86400);
                        $match_date_today_start = date('Y-m-d 00:00:00');
                        $match_date_today_end = date('Y-m-d 23:59:59');
                        ?>
                        试玩日期：
                        <input type="text" name="date_start" id="date_start" value="<?php echo $startTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至
                        <input type="text" name="date_end" id="date_end" value="<?php echo $endTime?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        <input type="button" class="match_date_tomorrow" value="前日" onclick="match_date('<?php echo $match_day_before_yesterday_start;?>','<?php echo $match_day_before_yesterday_end;?>')" />
                        <input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('<?php echo $match_date_yesterday_start;?>','<?php echo $match_date_yesterday_end;?>')" />
                        <input type="button" class="match_date_today" value="今日" onclick="match_date('<?php echo $match_date_today_start;?>','<?php echo $match_date_today_end;?>')" />
                        <input type="submit" name="SUBMIT" value="确认" class="za_button">

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
                    </td>
                </tr>

            </table>
        </form>
    </dd>
</dl>

<div class="main-ui" style="width: 1300px;">
    <table class="m_tab">
        <tr class="m_title">
            <td width="25">ID</td>
            <td width="100">用户名</td>
            <td width="100">联系方式</td>
            <td width="70">分类</td>
            <td>意见投诉</td>
            <td width="150">投诉时间</td>
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
                <td ><?php echo $row['phone_email']?></td>
                <td ><?php echo $aCategory[$row['category']];?></td>
                <td ><?php echo $row['content']?></td>
                <td ><?php echo $row['createtime']?></td>
            </tr>
        <?php }?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script language="javascript">

    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( dateStart, dateEnd ) {

        var form = $('#myFORM');
        $('#date_start').val(dateStart);
        $('#date_end').val(dateEnd);

        form.submit();

    }
</script>
</html>




