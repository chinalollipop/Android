<?php
/**
 * 显示支付通道的分类
 *
 * 银行卡线上
银行卡线下
微信第三方
微信扫码
微信转账
支付宝第三方
支付宝扫码
支付宝转账

qq扫码

 */
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../../../../../app/member/include/address.mem.php";
require ("../../../../../app/member/include/config.inc.php");
require ("../../../../../app/member/include/define_function_list.inc.php");


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$langx=$_SESSION['langx'];

require ("../../../../../app/member/include/traditional.$langx.inc.php");
$username=$_SESSION['UserName'];
$uid=$_SESSION['Oid'];
$mtype=$_REQUEST['mtype'];
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数

$realname = $_SESSION['Alias'];
$payPassword = $_SESSION['payPassword'];

$member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where Oid='$uid'";
$member_query = mysqli_query($dbLink,$member_sql);
$memberinfo = mysqli_fetch_assoc($member_query);
$sUserlayer = $memberinfo['layer'];
// 检查当前会员是否设置不准操作额度分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=3;
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        echo "<script language=javascript>alert('账号分层异常，请联系我们在线客服'); window.location.href='/';</script>";
        exit;
    }
}

// 显示支付通道的分类
$sWhere ="AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$sWhere .= " AND `status` = 1 "; // 开启状态且小于会员存款次数
// 第三方 会员的存款次数必须大于等于我们设置的条件,显示这个通道
$sSql = 'SELECT id,account_company,depositNum,has_company_youhui FROM `'.DBPREFIX.'gxfcy_pay` WHERE 1 '.$sWhere." AND `depositNum` <= '$depositTimes'";
$oRes = mysqli_query($dbLink,$sSql);

//$iCou = mysqli_num_rows($oRes);
$aThird_bank_pay = $aThird_weixin_pay = $aThird_ali_pay = $aThird_qq_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){
    if ($aRow['has_company_youhui']==1){
        $aThird_bank_pay_youhui[] = $aRow;
    }
    else{
        switch ($aRow['account_company']){
            //得到第三方网银支付配置
            case 1: $aThird_bank_pay[] = $aRow; break;
            //得到第三方支付银行卡配置
            case 2: $aThird_bank_pay[] = $aRow; break;
            //得到第三方支付微信配置
            case 4: $aThird_weixin_pay[] = $aRow; break;
            //得到第三方支付支付宝配置
            case 5: $aThird_ali_pay[] = $aRow; break;
            //得到第三方支付QQ扫码配置
            case 6: $aThird_qq_pay[] = $aRow; break;
            default: break;
        }
    }
}

$sWhere .=" AND {$depositTimes} >= `mindeposit` AND  {$depositTimes} <= `maxdeposit` " ;

// 线下银行通道显示
$sSql = "SELECT id,bankcode,bank_name,issaoma FROM `".DBPREFIX."gxfcy_bank_data` WHERE 1 ".$sWhere;
$oRes = mysqli_query($dbLink,$sSql);

$aBank_pay = $aAli_pay = $aAli_saoma_pay = $aWx_saoma_pay = $aYlsm_ysf_saoma_pay = array();
while ($aRow = mysqli_fetch_assoc($oRes)){

    //得到USDT扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bank_name'] == 'USDT虚拟货币'){
        $aUsdt_pay[] = $aRow;
    }
    //得到银行卡支付
    if ($aRow['issaoma'] == 0 && $aRow['bankcode'] != 'WXSAOMA'  && $aRow['bankcode'] != 'ALISAOMA'){
        $aBank_pay[] = $aRow;
    }
    //得到支付宝支付
    /*if ($aRow['issaoma'] == 0 && $aRow['bank_name'] == '支付宝'){
        $aAli_pay[] = $aRow;
    }*/
    //得到微信扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'WXSAOMA'){
        $aWx_saoma_pay[] = $aRow;
    }
    //得到支付宝扫码支付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'ALISAOMA'){
        $aAli_saoma_pay[] = $aRow;
    }
    //银联扫码|云闪付
    if ($aRow['issaoma'] == 1 && $aRow['bankcode'] == 'YLSMYSF'){
        $aYlsm_ysf_saoma_pay[] = $aRow;
    }
}
/* 快速充值 开始*/
// 显示支付通道的分类
$kssWhere ="AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$kssWhere .= " AND `bankcode` = 'KSCZ' AND `status` = 1";
$kssWhere .= " AND {$depositTimes} >= `mindeposit` AND  {$depositTimes} <= `maxdeposit` ";

// 非第三方通道分类显示 查询快速充值链接 后台线下银行配置
$kssSql = "SELECT photo_name FROM `".DBPREFIX."gxfcy_bank_data` WHERE 1 ".$kssWhere;
$ksoRes = mysqli_query($dbLink,$kssSql);
$ksaRow = mysqli_fetch_assoc($ksoRes);
/* 快速充值 结束*/

$membermessage = getMemberMessage($username,'1'); // 存款短信

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >


<!-- 充值方式列表 开始-->

        <div class="memberWrap">

            <div class="memberTit clearfix">
                <span class="account_icon fl titImg deposit_nav" ></span>
            </div>
            <div class="payWay">
                <div class="tip_title"><span class="btn_game">1</span>选择支付方式</div>
                <div class="payList">
                    <?php
                    // 7557显示快速充值 98985不显示
                    if(DEPOSIT_WITHDRAW_SWITCH && !empty($ksaRow)) { //
                        ?>
                        <div class="payItemWrap" data-type="kscz">
                            <div class="payItem">
                                <img src="<?php echo TPL_NAME;?>images/kscz.png" alt="">
                                <div class="payText">
                                    <h1>快速充值</h1>
                                    <p>免费</p>
                                    <p>15分钟</p>
                                    <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                </div>
                            </div>
                            <div data-way="<?php echo $ksaRow['photo_name'];?>" class="checkbox "></div>
                        </div>

                    <?php } ?>

                    <?php if(!empty($aBank_pay)) {  // 线下公司入款
                        ?>
                        <div class="payItemWrap">
                            <div class="payItem">
                                <img src="<?php echo TPL_NAME;?>images/gsrk.png" alt="">
                                <div class="payText">
                                    <h1>公司入款</h1>
                                    <p>免费</p>
                                    <p>5分钟</p>
                                    <p title="￥100.00-无上限">￥100.00-无上限</p>
                                </div>
                            </div>
                            <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/remittance.php" class="checkbox "></div>
                        </div>

                        <?php
                    }
                    if(!empty($aThird_bank_pay_youhui)) {  // 三方网银autopay，
                        foreach ($aThird_bank_pay_youhui as $k => $v){
                            ?>
                            <div class="payItemWrap">
                                <div class="payItem">
                                    <img src="/images/gsrk.png" alt="">
                                    <div class="payText">
                                        <h1>公司入款</h1>
                                        <p>免费</p>
                                        <p>5分钟</p>
                                        <p>￥100.00-无上限</p>
                                    </div>
                                </div>
                                <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/pay_type_third_bank_youhui.php?pid=<?php echo $v['id']?>" class="checkbox "></div>
                            </div>
                            <?php
                        }
                    } ?>


                    <?php if(!empty($aThird_ali_pay)) { // 第三方支付宝
                        ?>
                        <div class="payItemWrap">
                            <div class="payItem">
                                <img src="/images/zfb.png" alt="">
                                <div class="payText">
                                    <h1> 支付宝入款 </h1>
                                    <p>免费</p>
                                    <p>1分钟到3分钟</p>
                                    <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                </div>

                            </div>
                            <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/pay_type_third_zfb.php" class="checkbox "></div>
                        </div>

                        <?php } ?>

                    <?php if(!empty($aAli_saoma_pay)) { // 线下支付宝扫码
                        foreach ($aAli_saoma_pay as $key => $val) {
                            ?>
                            <div class="payItemWrap">
                                <div class="payItem">
                                    <img src="/images/zfb.png" alt="">
                                    <div class="payText">
                                        <h1> 线下支付宝 </h1>
                                        <p>免费</p>
                                        <p>5分钟</p>
                                        <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                    </div>

                                </div>
                                <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/bank_type_ALISAOMA.php?bankid=<?php echo $val['id']?>" class="checkbox "></div>
                            </div>


                            <?php
                        }
                    }?>

                    <?php if(!empty($aThird_weixin_pay)) { // 第三方微信
                        ?>
                        <div class="payItemWrap">
                            <div class="payItem">
                                <img src="<?php echo TPL_NAME;?>images/wxzf.png" alt="">
                                <div class="payText">
                                    <h1> <?php echo $value['title']; ?> </h1>
                                    <p>免费</p>
                                    <p>15分钟</p>
                                    <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                </div>
                            </div>
                            <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/pay_type_third_wx.php" class="checkbox "></div>
                        </div>


                    <?php }  ?>

                    <?php if(!empty($aWx_saoma_pay)) { // 线下微信扫码
                        foreach ($aWx_saoma_pay as $key => $val) {
                            ?>
                            <div class="payItemWrap">
                                <div class="payItem">
                                    <img src="<?php echo TPL_NAME;?>images/wxzf.png" alt="">
                                    <div class="payText">
                                        <h1> 线下微信 </h1>
                                        <p>免费</p>
                                        <p>15分钟</p>
                                        <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                    </div>
                                </div>
                                <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/bank_type_WESAOMA.php?bankid=<?php echo $val['id']?>" class="checkbox "></div>
                            </div>


                            <?php
                        }
                    }?>

                            <?php if(!empty($aThird_bank_pay)) { // 第三方网银
                                    ?>
                                <div class="payItemWrap">
                                    <div class="payItem">
                                        <img src="/images/zxzf.png" alt="">
                                        <div class="payText">
                                            <h1> 在线支付 </h1>
                                            <p>免费</p>
                                            <p>即时入账</p>
                                            <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                        </div>
                                    </div>
                                    <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/pay_type_third_bank.php" class="checkbox "></div>
                                </div>

                               <?php } ?>


                            <?php if(!empty($aThird_qq_pay)) { // 第三方QQ扫码
                                ?>
                                <div class="payItemWrap">
                                    <div class="payItem">
                                        <img src="/images/qqsm.png" alt="">
                                        <div class="payText">
                                            <h1> 第三方支付 </h1>
                                            <p>免费</p>
                                            <p>即时入账</p>
                                            <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                        </div>
                                    </div>
                                    <div  data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/pay_type_third_qq.php" class="checkbox "></div>
                                </div>


                                <?php  } ?>


                    <?php if(!empty($aYlsm_ysf_saoma_pay)) { // 银联扫码|云闪付
                        foreach ($aYlsm_ysf_saoma_pay as $key => $val) {
                        ?>
                        <div class="payItemWrap">
                            <div class="payItem">
                                <img src="/images/ysf.png" alt="">
                                <div class="payText">
                                    <h1> 银联扫码|云闪付扫码 </h1>
                                    <p>免费</p>
                                    <p>即时入账</p>
                                    <p title="￥100.00-￥10000.00">￥100.00-￥10000.00</p>
                                </div>
                            </div>
                            <div data-way="<?php echo TPL_NAME;?>tpl/lobby/onlinepay/bank_type_YLSMYSF.php?bankid=<?php echo $val['id']?>" class="checkbox "></div>
                        </div>


                    <?php
                        }
                     } ?>



                </div>

                <button class="deposit_next_btn nextBtn">
                    下一步
                </button>
            </div>

        </div>

        <input type="hidden" id="realName" value="<?php echo $realname;?>" readonly />
        <input type="hidden" id="withdraw-pw" value="<?php echo $payPassword;?>" readonly />



<script>
    $(function () {
        var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
        var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
        var realname = '<?php echo $realname;?>' ;
        var payPassword = '<?php echo $payPassword;?>' ;
        // 弹窗信息
        if(depositNum>0){ // 有弹窗短信
            layer.alert(depositMsg, {
                title: '会员信息',
                icon: false , // 0,1
                skin: 'layer-ext-moon'
            }) ;
        }

        if( !realname || !payPassword){ // 未绑定资料
            indexCommonObj.ifBindRealName(realname);
        }


        // 存款
        function depositNextAction() {
            $('.payItemWrap').on('click',function () { // 选择支付方式
                var af_realname = $('#realName').val();
                var af_payPassword = $('#withdraw-pw').val();
                var type = $(this).data('type');
                var $chobj = $(this).find('.checkbox ');
                var ch_url = $chobj.data('way');
                if( !af_realname || !af_payPassword) { // 未绑定资料
                    return false;
                }
                $chobj.addClass('active');
                $(this).addClass('active').siblings().removeClass('active').find('.checkbox').removeClass('active') ;
                if(type == 'kscz'){ // 快速充值
                    window.open(ch_url);
                    return ;
                }
                $('.deposit_next_btn').attr('data-url',ch_url);

            });

            $('.deposit_next_btn').on('click',function () { // 存款下一步
                var url = $(this).data('url');
                if(!url){
                    layer.msg('请选择支付方式',{time:alertTime}) ;
                    return ;
                }
                // console.log(url)
                indexCommonObj.middle_usercenter_content.load(url,function () {

                });


            });
        }

        depositNextAction() ;


    })

</script>

