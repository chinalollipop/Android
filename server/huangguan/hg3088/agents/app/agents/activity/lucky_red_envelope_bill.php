<?php
session_start();
include("../include/address.mem.php");
require("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$username = $_REQUEST['username'] ? $_REQUEST['username'] : '';

$sWhere = 1;
$username != '' ? $sWhere .= " AND `username` = '$username' " : '';
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 00:00:00', strtotime('+1 day'));
    $sWhere .=" and BillAddDate between '{$date_s}' and '{$date_e}'";
}else{
    $sWhere .=" and BillAddDate between '{$date_s}' and '{$date_e}'";
}
$sql = "select *  from ".DBPREFIX."lucky_red_envelope_bill where $sWhere order by ID DESC";
//echo $sql; die;
$res = mysqli_query($dbLink,$sql);
$gold_total=0;
$num=0;
$page_size=50;
$page=$_REQUEST['page'];
$data=[];

while ($row = mysqli_fetch_assoc($res)) {
    if( $page * $page_size <= $num && $num < ($page+1) * $page_size ) {
        $data[]=$row;
    }
    $num+=1;
    $gold_total += $row['LuckyRedEnvelopeGold'];
}
$cou=$num;
$page_count=ceil($cou/$page_size);


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>APP幸运红包记录</title>
    <style>
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>APP幸运红包记录 </dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $username;?>" maxlength=20 class="za_text">
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
            <td colspan="4">
                总投注总额：<span><?php echo ($mem_total_money['drop_money']>0)?$mem_total_money['drop_money']:'0';?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo ($mem_total_money['valid_money']>0)?$mem_total_money['valid_money']:'0';?></span>&nbsp;&nbsp;
                <?php
                if($agUsername && $agUsername !=''){
                    echo ' 会员结果总额：<span>'.round($mem_total_money['user_win'],1).'</span>';
                }
                ?>

            </td>
        </tr>

        <tr class="m_title">
            <td>领取时间</td>
            <td>会员账号</td>
            <td>领取金额（元）</td>
            <td>有效金额</td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="10">没有记录</td></tr>';
        }else{

            foreach ($data as $k => $v){?>
                <tr class="m_rig">
                    <td><?php echo $v['BillAddDate']?></td>
                    <td><?php echo $v['UserName']?></td>
                    <td><?php echo $v['LuckyRedEnvelopeGold']?></td>
                    <td><?php echo $v['valid_money']?></td>
                </tr>

                <?php
            }
        }

        ?>
        <tr class="m_rig2">
            <td colspan=""><?php echo date("Y-m-d",strtotime($date_s)).'&nbsp;到&nbsp;'.date("Y-m-d",strtotime($date_e)); ?></td>
            <td colspan="3">总计：<?php echo sprintf("%01.2f", $gold_total)?></td>

        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript">
    function sbar(st){
        st.style.backgroundColor='#BFDFFF';
    }
    function cbar(st){
        st.style.backgroundColor='';
    }
</script>
</body>
</html>
