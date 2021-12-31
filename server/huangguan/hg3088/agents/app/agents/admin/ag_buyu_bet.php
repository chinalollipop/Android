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

$sWhere = 1;
$SceneId = $_REQUEST['SceneId'];
$SceneId !='' ? $sWhere .= " And `SceneId` = '$SceneId'" : '';
$BillId = $_REQUEST['BillId'];
$BillId !='' ? $sWhere .= " And `BillId` = '$BillId'" : '';


$mysql="select * from `".DBPREFIX."ag_buyu_projects` where $sWhere order by `Time` desc";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
$mem_total_money=array();

$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size";
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
    <title>捕鱼王-捕鱼-下注记录详情</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red; margin: 0 20px 0 0;}
        input.za_text {width: 150px;}
        input.za_text2 {width: 200px;}
        .detail_link a{color: blue;}
        .detail_link a:hover{ text-decoration-line: underline;  }
    </style>
</head>
<body >
<dl class="main-nav">
    <dt></dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        场景：
                        <input type=TEXT name="SceneId" size=10 value="<?php echo $SceneId;?>" maxlength=20 class="za_text">
                        订单号：<input type=TEXT name="BillId" size=15 value="<?php echo $BillId;?>" maxlength=30 class="za_text2">
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

<div>
    <table class="m_tab">

        <tr class="m_title">
            <td>场景</td>
            <td>订单号</td>
            <td>房间号</td>
            <td>房间倍率</td>
            <td>会员账号</td>
            <td>鱼种类</td>
            <td>鱼编号</td>
            <td>子弹价值(支出)</td>
            <td>盈亏</td>
            <td>时间(美东)</td>
            <td>是否击中</td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="11">没有记录</td></tr>';
        }
        while($row=@mysqli_fetch_assoc($result)){
            ?>
            <tr class="m_rig">
                <td><?php echo $row['SceneId']?></td>
                <td><?php echo $row['BillId']?></td>
                <td><?php echo $row['RoomId']?></td>
                <td><?php echo $row['RoomBet']*10?></td>
                <td><?php echo $row['UserName']?></td>
                <td><?php echo $row['FishType']?></td>
                <td><?php echo $row['FishId']?></td>
                <td><?php echo $row['CannonCost']*$row['RoomBet']*$row['CannonBoost']?></td>
                <td>
                    <?php
                    if ($row['UserCashDelta']>0){
                        echo $row['UserCashDelta'];
                    }else{
                        echo '<span style="color: blue;">'.$row['UserCashDelta'].'</span>';
                    }
                    ?>
                </td>
                <td><?php echo $row['Time']?></td>
                <td>
                    <?php
                    if ($row['Hunted']=='true'){
                        echo '<span style="color: green; font-size: 18px; font-weight: bold;">√</span>';
                    }elseif($row['Hunted']=='false'){
                        echo '<span style="color: red; font-size: 18px; font-weight: bold;">×</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</html>