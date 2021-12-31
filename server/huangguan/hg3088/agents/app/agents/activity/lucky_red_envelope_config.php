<?php
session_start();
include("../include/address.mem.php");
require("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

//$ids = $_REQUEST['id'];
$type=$_REQUEST['type'];  // edit
//$moneys = $_REQUEST['money'];
//$probabilitys = $_REQUEST['probability'];
$uid = $_SESSION['Oid'];
$langx = $_SESSION['langx'];
$level = $_SESSION['admin_level'];


switch ($type){
    case 'edit':

        $sum_probability=0;
        foreach ($_REQUEST['probability'] as $id => $probability){
            $sum_probability+=$probability;
        }
        if ($sum_probability!=1){
            echo "<script>alert('几率有误，几率综合不能大于或小于1，请重新填写几率重新修改');</script>";
        }else{
            $ids = implode(',',array_values($_REQUEST['id']));
            $sql = "UPDATE ".DBPREFIX."lucky_red_envelope_config SET money = CASE id ";
            foreach ($_REQUEST['money'] as $id => $money){
                $sql .= sprintf("WHEN %d THEN %d ", $id, $money);
            }
            $sql .= "END,";
            $sql .= "probability = CASE id ";
            foreach ($_REQUEST['probability'] as $id => $probability){
                $sql .= sprintf("WHEN %d THEN %s ", $id, $probability);
            }
            $sql .= "END ";
            $sql .= "where id IN ($ids)";
            $res = mysqli_query($dbMasterLink,$sql);
            !$res ? false : true;
        }

        break;
    default: break;
}


$sql = "select *  from ".DBPREFIX."lucky_red_envelope_config";
$res = mysqli_query($dbLink,$sql);
$lists = array();
while ($row = mysqli_fetch_assoc($res)){
    $lists[] = $row;
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>APP幸运红包配置</title>
    <style>
        input.za_text_auto {width: 150px;}
        .main-ui{ width: 900px;}
        .red_records{ float: right; line-height: 23px;}
    </style>
</head>
<body >
<dl class="main-nav"><dt>APP幸运红包配置 <a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y"></a></dt><dd> </dd></dl>
<div class="main-ui">
    <table  class="m_tab">
<!--        <tr><td colspan="2"><a class="za_button red_records" href="download_app_gift_bill.php?uid=--><?php //echo $uid?><!--$uid&langx=--><?php //echo $langx?><!--&lv=--><?php //echo $level?><!--">红包记录</a</td></tr>-->
        <form id="myform" name="myform" action="" method="post">
            <input type="hidden" name="type" value="edit">
            <tr class="m_title">
                <td>每次领取红包的金额</td>
                <td>领取红包的几率<font color="red">（1=100%）</font></td>

            </tr>
            <?php
            foreach ($lists as $value){
                ?>
                <tr  class=m_cen>
                    <td>
                        <input type="hidden" name="id[<?php echo $value['id'];?>]" value="<?php echo $value['id'];?>">
                        <input type="text" name="money[<?php echo $value['id'];?>]" class="za_text_auto" value="<?php echo $value['money'];?>">
                    </td>
                    <td><input type="text" name="probability[<?php echo $value['id'];?>]" class="za_text_auto" value="<?php echo $value['probability'];?>"></td>

                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="2">
                    <input type="submit" class="za_button" value="修改">
                </td>
            </tr>
        </form>
    </table>
    <br>

</div>

</body>
</html>
