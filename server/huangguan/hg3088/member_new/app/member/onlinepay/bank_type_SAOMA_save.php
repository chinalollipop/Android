<?php
/**
 * 支付宝，微信二维码。手工扫码付款
 */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
checkDepositOrder('old');

$uid=$_SESSION['uid'];
$langx=$_SESSION['langx'];
$payid = $_REQUEST['payid'];
$sSql = "select * from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 and `id` = $payid ";
$aRes = mysqli_query($dbMasterLink,$sSql);
$aRow=mysqli_fetch_array($aRes);
$iCou=mysqli_num_rows($aRes);
if($iCou == 0){
    echo "支付方式有误，请重新选择"; exit;
}

$aBankpay = $aRow;  //公司支付类型
$memo = $_REQUEST['memo'];
$bank_name = $_REQUEST['bank_user'];
$userid=$_SESSION['userid'];
$sql = "SELECT Money FROM `".DBPREFIX.MEMBERTABLE."` WHERE ID='$userid' ";
$result = mysqli_query($dbMasterLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){
    echo "<script>alert('登录错误！请检查用户名或密码');history.back();</script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

$cash = $_REQUEST['v_amount'];
$moneyf = sprintf("%01.2f",$row['Money']); // 用户充值前余额
$currency_after = $moneyf+$cash; // 用户充值后的余额
$myname = $_SESSION['Alias'];
$username = $_SESSION['UserName'];
$getday = $_REQUEST['cn_date'];
if ($aBankpay['bankcode']==='USDT'){
    $getday=date('Y-m-d H:i:s');
}
if (strlen($bank_name)>255){
    echo "<script>alert('银行名称异常请联系客服！');history.back();</script>";
}
if (strlen($memo)>255){
    echo "<script>alert('订单号异常请重新输入！');history.back();</script>";
}
if (!is_numeric($cash))
    echo "<script>alert('汇款金额只能输入数字！');history.back();</script>";
if ($cash>$aBankpay['maxmoney']){
    echo "<script>alert('汇款金额不能超过{$aBankpay['maxmoney']}！');history.back();</script>";
}
if ( $getday == "")
    echo "<script>alert('您的名字和汇款日期必须填写完整！');history.back();</script>";
$agents=$_SESSION['Agents'];
$world=$_SESSION['World'];
$corprator=$_SESSION['Corprator'];
$super=$_SESSION['Super'];
$admin=$_SESSION['Admin'];
$phone=$_SESSION['Phone'];
$contact="";
// $notes=$bank_name.'-'.$memo;
$notes= $memo;  // 交易订单号后四位
$bank = $aBankpay['bank_name'];
$bank_account=$aBankpay['bank_account'];
$bank_address=$aBankpay['bank_addres'];
$order_code = date("YmdHis",time()).rand(100000,999999);
$paytype = $aBankpay['id']; // 线下银行公司入款 支付宝微信扫码 id
$payname = $aBankpay['bankcode'];
$test_flag=$_SESSION['test_flag'];
if ($aBankpay['bankcode'] =='USDT'){
    $rate = returnUsdtRate();
    $rate['usdt_amount'] = round($cash/ $rate['usdt_rate'],2);
    $IntoBank = $bank_name.'-'.$rate['usdt_rate'].'-'. $rate['usdt_amount'];
}else{
    $IntoBank = $bank_name.'-'.$notes ;
}
$sql = "insert into `".DBPREFIX."web_sys800_data` set DepositAccount='$IntoBank',userid='$userid',Checked=0,Payway='N',Gold='$cash',moneyf='$moneyf',currency_after='$currency_after',AddDate='".date("Y-m-d",time())."',Type='S',UserName='$username',Agents='$agents',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',CurType='RMB',Date='$getday',Name='$myname',Waterno='',Bank='$bank',Cancel='0',contact='$contact',Bank_Account='$bank_account',Bank_Address='$bank_address',Phone='$phone',Order_Code='$order_code',PayType='$paytype',PayName='$payname',test_flag='$test_flag'";
//echo $sql;echo '<br>';
mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());

?>
<html >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款</title>
    <style type="text/css">
        h3,p{margin: 0;}
        a{text-decoration: none;}
        .tableSubmit{border: 1px solid #ccc;padding: 10px;}
        .saoma_notes p {padding: 5px 0;font-size: 24px;font-weight: bold;}
        .saoma_notes span {font-size: 14px;}
        /*  .saoma_source img {max-width: 200px;}
        .usdt_price_font { color:red; font-size: 16px; font-weight: bolder;}
        .copy_icon{display: inline-block;vertical-align:middle;width: 30px;height: 30px;background: url(/images/2018/deposit/fuzhi.png) center no-repeat;background-size: cover;}
        .cz_title{line-height: 30px;float: left;font-size: 16px;}
        .left_50{margin-left: 50px;}
        .cz_url{width: 300px;text-align: center;}
        .tip_title{width: 100px;line-height: 50px;text-align: center;border: 1px solid #ccc;border-radius: 5px;font-size: 20px;}
        .tip_top{background: linear-gradient(to bottom, #fef9ed, #f1dbb0);padding: 0 10px;}
        .icon_chat{display: inline-block;width: 20px;height: 25px;background: url(/images/2018/deposit/chat_icon.png) center no-repeat;background-size:100%;vertical-align: middle;margin-left: 5px;}
        */
    </style>
</head>
<body >
<?php
/*if($aBankpay['bankcode']=='USDT'){
*/?><!--
    <div class="payWay bank_deposit_2">
        <table  class="tableSubmit"  cellspacing="0" cellpadding="0">
            <tr>
                <td >
                    <div class="tip_title left_50">TRC20</div>
                    <p class="left_50">
                        <span class="cz_title">请转入 <span class="usdt_price_font pay_to_usdt"> <?php /*echo $rate['usdt_amount'];*/?></span> USDT </span>
                        <a class="copy_icon copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt" title="点击复制"></a>
                    </p>
                    <div class="content_right">
                        <dl class="saoma_source" >
                            <dd>
                                <img src="<?php /*echo $aRow['photo_name'];*/?>" /><br>
                            </dd>
                        </dl>
                        <div class="cz_url">
                            <p>充值地址</p>
                            <span class="cz_title" style="float: none;"><span class="pay_to_usdt_url"> <?php /*echo $aRow['deposit_address'];*/?>  </span> </span></br>
                            <a class="copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt_url" title="点击复制"><span class="copy_icon"></span> 复制地址 </a>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td style="text-align: left;line-height: 30px;">

                    <p class="tip_top">支付完成请等待<span class="usdt_price_font">5-10</span>分钟到账，支付失败请<a style="margin-left: 5px;" target="_blank" class="to_livechat"><span class="usdt_price_font">咨询客服</span><span class="icon_chat"></span></a> </p>
                    <h3>*注意：</h3>
                    <p>
                        1.请勿向上述地址支付任何非TRC20 USDT 资产，否则资产将无法找回。<br>
                        2.当前<?php /*echo getSysConfig('usdt_jiaoyisuo') ? getSysConfig('usdt_jiaoyisuo') : 'Okex/火币/币安';*/?>交易所 USDT 最新场外卖出单价 <span class="usdt_price_font new_usdt_rate"> <?/*echo $rate['usdt_rate'];*/?></span> 元。<br>
                        3.请确保收款地址收到 <span class="usdt_price_font pay_to_usdt"> <?php /*echo $rate['usdt_amount'];*/?></span> USDT  <span class="usdt_price_font" style="font-size: 14px;">【不含转账手续费】</span> ，否则无法到账。<br>
                        4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。
                    </p>

                </td>
            </tr>
        </table>
    </div>

    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/common.js"></script>
    <script type="text/javascript" src="../../../js/layer/layer.js"></script>
    <script type="text/javascript" src="../../../js/clipboard.min.js"></script>
    <script type="text/javascript">
        var alertTime = 2000;
        $('.to_livechat').attr({"href":top.configbase.service_meiqia}); // 在线客服
        // countUsdtMount();
        copyBnakAction();
        // 复制
        function copyBnakAction() {
            $('.copy_btn').each(function (num) {
                // console.log(num);
                var clipboard = new ClipboardJS(this, {
                    text: function () {
                        return $(this).prev().text();
                    }
                });
                clipboard.on('success', function (e) {
                    //console.log(e);
                    layer.msg('复制成功!', {time: alertTime});
                    e.clearSelection();
                });
                clipboard.on('error', function (e) {
                    //console.log(e);
                    layer.msg('请选择“拷贝”进行复制!', {time: alertTime});
                });
            });
        }
        // usdt 金额输入与计算
        //function countUsdtMount(){
        //    var $pay_to_usdt = $('.pay_to_usdt');
        //    var rateUrl = '/app/member/api/usdtRateApi.php';
        //    $('.bank_deposit_1').hide();
        //    $('.bank_deposit_2').show();
        //    var cz_val = '<?php /*//echo $cash;*/?>//' ; // 充值金额
        //    $.ajax({
        //        type: 'POST',
        //        url: rateUrl,
        //        data: {},
        //        dataType: 'json',
        //        success: function (res) {
        //            if(res){
        //                $('.new_usdt_rate').text(res.data.usdt_rate); // 更新汇率
        //                var zf_val = cz_val/(res.data.usdt_rate); // 需要转入的usdt
        //                zf_val = advFormatNumber(zf_val,2); // 保留两位小数
        //                $pay_to_usdt.text(zf_val);
        //            }
        //        },
        //        error: function () {
        //            layer.msg('网络错误，请稍后重试!',{time:alertTime});
        //        }
        //    });
        //}
    </script>

--><?php
/*}else{
*/?>
    <table width="100%" border="0" cellspacing="10" cellpadding="0">
        <tr>
            <td align="center"><font color="#999999">您好：您的汇款信息已提交成功,请等待工作人员的审核，并请于10分钟之内查询您的帐户余额。</font><a href="pay_type.php?uid=<?php echo ($uid)?>&langx=<?php echo ($langx)?>">返回继续操作</a></td>
            <td align="center"><font color="#999999"></font><a href="../onlinepay/record.php?uid=<?php echo ($uid)?>&username=<?php echo ($username)?>&langx=<?php echo ($langx)?>&thistype=S&date_start=<?php echo $m_date?>&date_end=<?php echo $m_date?>">查看记录</a></td>

        </tr>
    </table>
<?php
/*}
*/?>



</body>