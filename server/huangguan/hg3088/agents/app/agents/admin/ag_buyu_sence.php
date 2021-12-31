<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
$page=$_REQUEST['page'];

$datatime=date('Y-m-d H:i:s');

// 根据条件查询报表（会员名称、投注时间、游戏名称）
$sPrefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_';
$agUsername = $_REQUEST['username'] ? $sPrefix.$_REQUEST['username'] : '';

$sWhere = 1;
$agUsername != '' ? $sWhere .= " AND `UserName` = '$agUsername' " : '';
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 23:59:59', time());
    $sWhere .=" and EndTime between '{$date_s}' and '{$date_e}'";
}else{
    $sWhere .=" and EndTime between '{$date_s}' and '{$date_e}'";
}
$SceneId = $_REQUEST['SceneId'];
$SceneId !='' ? $sWhere .= " And `SceneId` = '$SceneId'" : '';
$agUsername = explode($sPrefix,$agUsername)[1];  // 赋值给搜索框去掉用户名前缀

$mysql="select * from `".DBPREFIX."ag_buyu_scene` where $sWhere order by `EndTime` desc";
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
$mem_total_money=array();
while($row=@mysqli_fetch_assoc($result)){
    $mem_total_money['Cost'] += $row['Cost'];
    $mem_total_money['Earn'] += $row['Earn'];
    $profit = $row['Earn'] - $row['Cost'];
    $mem_total_money['profit'] += $profit;
}

$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size";
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
    <title>捕鱼王</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red; margin: 0 20px 0 0;}
        input.za_text {width: 142px;}
        .detail_link a{color: blue;}
        .detail_link a:hover{ text-decoration-line: underline;  }
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>捕鱼王</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        场景：
                        <input type=TEXT name="SceneId" size=10 value="<?php echo $SceneId;?>" maxlength=20 class="za_text">
                        注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $agUsername;?>" maxlength=20 class="za_text">
                        <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
                        共<?php echo $cou?>条
                        <select name='page' onChange="self.myFORM.submit()">
                            <?php
                            if ($page_count==0){
                                $page_count=1;
                            }
                            for($i=0;$i<$page_count;$i++){
                                if ($i==$page){
                                    echo "<option selected value='$i'>".($i+1)."</option>";
                                }else{
                                    echo "<option value='$i'>".($i+1)."</option>";
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

<div class="main-ui">
    <table class="m_tab">

        <tr class="mem_total_money">
            <td colspan="13">
                子弹价值（支出）：<span><?php echo $mem_total_money['Cost'];?></span>
                鱼的价值（收入）：<span><?php echo $mem_total_money['Earn'];?></span>
                <?php
                if($agUsername && $agUsername !=''){ // 查询账号时才显示
                    echo '会员结果总额：<span>'.$mem_total_money['profit'].'</span>';
                }
                ?>


            </td>
        </tr>

        <tr class="m_title">
            <td>会员账号</td>
            <td>场景</td>
            <td>房间号</td>
            <td>子弹数量</td>
            <td>子弹价值(支出)</td>
            <td>鱼价值(收入)</td>
            <td>盈亏</td>
            <td>开始时间(美东)</td>
            <td>结束时间(美东)</td>
            <td>查看详情</td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="10">没有记录</td></tr>';
        }
        while($row=@mysqli_fetch_assoc($result)){
            $aUsername=explode('_',$row['UserName'],2);
            ?>
            <tr class="m_rig">
                <td><?php echo $aUsername[1]?></td>
                <td><?php echo $row['SceneId']?></td>
                <td><?php echo $row['RoomId']?></td>
                <td><?php echo $row['BulletOutNum']?></td>
                <td><?php echo $row['Cost']?></td>
                <td><?php echo $row['Earn']?></td>
                <td>
                    <?php
                    $yingli = $row['Earn'] - $row['Cost'];
                    if($yingli>0){
                        echo $yingli;
                    }else{
                        echo '<span style="color: blue;">'.$yingli.'</span>';
                    }
                    ?>
                </td>
                <td><?php echo $row['StartTime']?></td>
                <td><?php echo $row['EndTime']?></td>
                <td class="detail_link">
                    <a onclick="Buyu_Sence_Detail('<?php echo $row['SceneId']?>')" href="javascript:;" >查看详情</a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
    function Buyu_Sence_Detail( SceneId){
        layer.open({
            title:'下注记录详情',
            type: 2,
            shade: false,
            area: ['1200px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/admin/ag_buyu_bet.php?SceneId='+SceneId ,
        });
    }
</script>
</html>