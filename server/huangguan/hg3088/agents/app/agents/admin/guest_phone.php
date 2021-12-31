<?php
/**
 * 试玩参观手机号查看
 * Date: 2018/11/7
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

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : 0;

// 查询条件（日期、游戏、房间、局号、用户名）
$startTime = $_REQUEST['date_start'] ? trim($_REQUEST['date_start']) : date('Y-m-d') . ' 00:00:00';
$endTime = $_REQUEST['date_end'] ? trim($_REQUEST['date_end']) : date('Y-m-d') . ' 23:59:59';

$sWhere = 1;
$sWhere .= " AND `login_time` BETWEEN '{$startTime}' AND '{$endTime}'";

$mysql = "SELECT * 
          FROM `" . DBPREFIX . "web_guest_phone_data`
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
    <title>试玩参观手机号</title>
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
    <dt>试玩参观手机号</dt>
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
            <td>ID</td>
            <td>手机号</td>
            <td>试玩时间</td>
        </tr>
        <?php
        if($count == 0){
            echo '<tr><td colspan="13">暂无记录</td></tr>';
        }

        while($row = mysqli_fetch_assoc($result)){

            ?>
            <tr class="m_rig">
                <td ><?php echo $row['id']?></td>
                <td ><?php echo $row['phone']?></td>
                <td ><?php echo $row['login_time']?></td>
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




