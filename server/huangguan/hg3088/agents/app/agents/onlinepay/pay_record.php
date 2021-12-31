<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include("../include/address.mem.php");
require_once("../include/config.inc.php");
require ("../include/define_function_list.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

// 调通后编写功能  新增、修改、删除
$type=$_REQUEST['type'];  // add   edit  delete
$che=$_REQUEST['chk'];   // 要修改的内容


$nowtime=time();
@$paytype=(int) $_REQUEST['paytype'];
@$startdate=$_REQUEST['sdate']==""?date("Y-m-d",$nowtime):$_REQUEST['sdate'];
@$enddate=$_REQUEST['edate']==""?date("Y-m-d",$nowtime):$_REQUEST['edate'];



switch($paytype){
    case 0:		//会员网银存款
        if($usertype == 8) {
            $sqlstr.="and a.type_code=0 and a.pay_way=1 ";
        }else {
            $sqlstr.="and a.type_code=0 and a.pay_way=1 and a.status = 1 ";
        }
        break;
    case 1:		//后台管理员存款
        $sqlstr.="and a.type_code=0 and a.pay_way=0 ";
        break;
    case 2:		//会员银行汇款
        $sqlstr.="and a.type_code=0 and a.pay_way=2 ";
        break;
    case 3:		//会员提款
        $sqlstr.="and a.type_code=1 and a.pay_way=3 ";
        break;
    case 4:		//后台管理员扣款
        $sqlstr.="and a.type_code=1 and a.pay_way=0 ";
        break;
}

$sql = "select *  from ".DBPREFIX."gxfcy_pay_record";
$res = mysqli_query($dbLink,$sql);
$lists = array();
while ($row = mysqli_fetch_array($res)){
    $lists[] = $row;
}



?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>存取款明细</title>
</head>
<body >
<dl class="main-nav">
    <dt>存取款明细</dt>
    <dd></dd>
</dl>
<div class="main-ui">
    <form name="myform"  action="pay_record.php" method="get">
        <input type=HIDDEN name="uid" value="<?php echo $uid?>">
        <input type=HIDDEN name="langx" value="<?php echo $langx?>">
        <input type=HIDDEN name="lv" value="<?php echo $lv?>">
        <input type=HIDDEN name="first" value="Y">
        <div class="headers">
            <div class="headersleft">
                <div class="headersconnect fl">类型:
                    <select name="paytype">
                        <option value="0" <?php echo $paytype==0?"selected":"";?>>会员网银存款</option>
                        <option value="1" <?php echo $paytype==1?"selected":"";?>>后台管理员存款</option>
                        <option value="2" <?php echo $paytype==2?"selected":"";?>>会员银行汇款</option>
                        <option value="3" <?php echo $paytype==3?"selected":"";?>>会员提款</option>
                        <option value="4" <?php echo $paytype==4?"selected":"";?>>后台管理员扣款</option>
                    </select> 日期:
                    <input type="text" class="za_text_auto" name="sdate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                    <input type="text" class="za_text_auto" name="edate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>
                    账号:<input type="text" name="username" value="<?php echo $username?>">
                    未处理: <input type="checkbox" name="status" value="0" <?php if($status === '0') echo 'checked="checked"';?>>
                    <?php if($paytype==3){?>
                        自动出款: <input type="checkbox" name="is_auto" value="1" <?php if($is_auto === '1') echo 'checked="checked"';?>>
                    <?php }?>
                    层级:
                    <select name="class">
                        <option value="" >全部</option>
                        <?php foreach($list as $val){ ?>
                            <option value="<?php echo $val['ename'];?>" <?php echo $val['ename']==$class?"selected":"";?>><?php echo $val['name'];?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" class="za_button" value="查询">
                    <?php if($paytype==3){?>
                        <font color="red"><b>总计：<?php echo $all_money;?>，自动：<?php echo $autowithdraw_money;?>，手动：<?php echo $manualwithdraw_money;?></b></font>
                    <?php }else {?>
                        <font color="red"><b>总计：<?php echo $all_money;?></b></font>
                    <?php }?>
                </div>
            </div>
        </div>
    </form>

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" >
            <td>订单日期</td>
            <td>会员</td>
            <?php if($paytype==3){?>
                <td>提款后余额</td>
            <?php }?>
            <td>所属上级</td>
            <td>会员支付层级</td>
            <td>订单号</td>
            <td>存取类型</td>
            <td>付款方式</td>
            <td>商户</td>
            <td>交易金额</td>
            <td>额外赠送金额</td>
            <?php if($paytype==0 || $paytype==1 || $paytype==4){?>
                <td>充值前余额</td>
                <td>充值后余额</td>
            <?php }?>
            <?php if($paytype==3){?>
                <td>打码量</td>
            <?php }?>
            <td>银行信息</td>
            <?php if($paytype!=3){?>
                <td>用户或前台自动生成备注</td>
            <?php }?>
            <td>管理员备注</td>
            <td>订单状态</td>
            <?php if($paytype==3){?>
                <td>自动出款</td>
                <td>备注</td>
            <?php }?>
            <td>审核人</td>
            <td>审核时间</td>
        </tr>

            <?php


    //        $array_type=$this->config->item("pay_type");
    //        $array_payway=$this->config->item("pay_way");
            $array_status=array(0=>"未处理",1=>"已处理");
            $array_clear=array(0=>"撤销",1=>"成功");

            foreach($lists as $value){
                $backgroundcolor = "";
                if($value['status']!=0){
                    $backgroundcolor = "#84C1FF";
                }
                $overcolor = $paytype == 3
                    ? ""
                    : "onmouseover=\"this.style.backgroundColor='#FFFFA2'\" onmouseout=\"this.style.backgroundColor='$backgroundcolor'\"";
                echo "<tr $overcolor style=\"background-color:$backgroundcolor\">";
                echo "<td>".date("m-d H:i:s",$value['addtime'])."</td>";
                echo "<td>".$value['username']."<br>".$value['realname']."<br>".$value['moneyf']."</td>";
                if($paytype==3){
                    echo "<td>".$value['currency_after']."</td>";
                }
                if($value['testflag'] == 1) {
                    echo "<td style='color:red;font-weight:bold;'>".$value['agentusername']."</td>";
                }else {
                    echo "<td>".$value['agentusername']."</td>";
                }
                echo "<td>".$value['name']."</td>";
                echo "<td>".$value['order_code']."</td>";
                echo "<td>".$array_type[$value['type_code']]."</td>";
                echo "<td>".$array_payway[$value['pay_way']]."</td>";
                echo "<td>".$value['account_company']."</td>";
                echo "<td>".$value['money']."</td>";
                echo "<td>".$value['extra_prize_money']."</td>";

                if($paytype==0 || $paytype==1 || $paytype==4){
                    echo "<td>".$value['moneyf']."</td>";
                    echo "<td>".$value['currency_after']."</td>";
                }

                if($paytype==3){
                    echo "<td><div id='bb".$value['id']."'>------</div>
                                <div><a href='javascript:;' onclick='getglod(".$value['userid'].",".$value['id'].")'>查询码量</a></div>
                                </td>";
                }
                echo "<td>";
                echo "姓名:".$value['bank_name']."<br>";
                echo "银行:".$value['bank_code']."<br>";
                echo "地址:".$value['bank_address']."<br>";
                echo "帐号:".$value['bank_account']."</td>";

                if($paytype!=3){
                    echo "<td>".$value['memo']."</td>";
                }

                echo "<td>".$value['context']."</td>";
                echo "<td width='120px;' style='text-align:center'><div title=\"".$value['context']."\">";
                if($value['status']==0 && $paytype == 0 && $usertype == 8){
                    echo "<a href=\"#\" onclick='javascritp:bd(".$value['id'].",1)'>补单</a> // ";
                    echo "<a href=\"#\" onclick='javascritp:bd(".$value['id'].",0)'>撤销</a>";
                }else if($value['status']==0){
                    if($value['is_auto'] == 1 && $value['is_auto_flag'] == 2) {
                        $auto_process_flag = 1;
                    }else {
                        $auto_process_flag = 0;
                    }
                    if($value['testflag'] == 1) {
                        echo "<font color='red' style='font-weight:bold'>测试用户<br /><br /></font>";
                    }
                    echo "<a href=\"#\" onclick='javascritp:op(".$value['id'].",1, $auto_process_flag)'>通过</a> // ";
                    echo "<a href=\"#\" onclick='javascritp:op(".$value['id'].",0, 0)'>撤销</a>";
                }else{
                    if($value['is_clear']==1){
                        echo "<font color='green'><strong>成功</strong></font>";
                    }else{
                        echo "<font color='red'><strong>已撤销</strong></font>";
                    }
                }
                echo "</div></td>";

                if($paytype==3){
                    echo "<td style='text-align:center'><div title=\"".$value['context']."\">";
                    if($value['status']==0) {
                        if($value['money'] > $max_auto_withdraw_money ) {
                            echo "<font color='red'><strong>超出限额</strong></font>";
                        } else if($value['bank_code'] == "农村信用合作社" ) {
                            echo "<font color='red'><strong>农社不能自动出款</strong></font>";
                        } else if($value['is_auto'] == 0 ) {
                            echo "<a href=\"#\" onclick='javascritp:autock(".$value['id'].")'>自动出款</a> ";
                        } else if($value['is_auto'] == 1 && $value['is_auto_flag'] == 2) {
                            echo "<font color='#C4C400'><strong>自动出款中</strong></font><br /><br /><a href=\"#\" onclick='javascritp:autock(".$value['id'].")'>再次发送</a>";
                        } else if($value['is_auto'] == 1 && $value['is_auto_flag'] == 0) {
                            echo "<font color='red'><strong>自动出款失败</strong></font>";
                        }
                    }else {
                        if($value['is_clear']==1 && $value['is_auto']==1 && $value['is_auto_flag']==1) {
                            echo "<font color='green'><strong>自动出成功</strong></font>";
                        }else if($value['is_clear']==1 && $value['is_auto']==0 ) {
                            echo "<font color='red'><strong>非自动出款</strong></font>";
                        }else if( $value['is_auto']==1 && $value['is_auto_flag'] == 0  ) {
                            echo "<font color='red'><strong>自动出款失败</strong></font>";
                        }else if( $value['is_auto']==1 && $value['is_auto_flag'] == 2  ) {
                            echo "<font color='yellow'><strong>自动出款中</strong></font><br /><br /><a href=\"#\" onclick='javascritp:autock(".$value['id'].")'>再次发送</a>";
                        }else {
                            echo "<font color='red'><strong>已撤销</strong></font>";
                        }
                    }

                    echo "</div></td>";
                    echo "<td>".$value['auto_memo']."</td>";
                }
                echo "<td>".$value['opusername']."</td>";
                echo "<td>".substr($value['optime'], 5)."</td>";
                echo "</tr>";
            }
            ?>

        </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>

</body>
</html>


