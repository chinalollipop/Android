<?php
session_start();
include_once('../../../include/config.inc.php');


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='../login.php';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$sql = "select Bank_Name,Bank_Address,Bank_Account,owe_bet,owe_bet_time,Alias,Oid,Money,layer from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
//echo $sql;
//exit;
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);

// 检查当前会员是否设置不准操作额度分层
// 检查分层是否开启 status 1 开启 0 关闭
// layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金
$layerId=3;
$sUserlayer = $row['layer'];
if ($sUserlayer==$layerId){
    $layer = getUserLayerById($layerId);
    if ($layer['status']==1) {
        echo "<script language=javascript>alert('账号分层异常，请联系我们在线客服'); window.location.href='/';</script>";
        exit;
    }
}

$user_Money= number_format($row['Money'],2); // 用户余额

if($row['Alias'] == ''){ // 没有设置真实姓名
    echo "<script language=javascript>alert('请先设置您的真实姓名！'); window.location.href = './mset_realname.php';</script>";
}
if( $row['Bank_Name']=='' || $row['Bank_Address']=='' || $row['Bank_Account']==''){ // 没有绑定银行账号
    echo "<script language=javascript>alert('请先设置您的银行账号信息！'); window.location.href = './mset_bank.php?action=add';</script>";

}
$membermessage = getMemberMessage($username,'2'); // 取款短信

?>


<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
<style type="text/css">
    .textbox input{ width: 100% ;}
    .form-item .label{ width: 23%;}
    .withdraw-form .textbox{ width: 75%;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center ">
     <!--   <div class="top-deposit">
            <div class="login-logo"></div>
            <h2 class="web-title"></h2>
        </div> -->
        <!-- 主体内容 -->
        <div class="withdraw-form">
            <form method="post" name="withdraw_form" id="withdraw_form" >
                <div class="form-item">
                        <span class="label">
                            <span class="text">提款打码量</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                         <input type="text" name="owe_bet" value="<?php echo $row['owe_bet'];?>" autocomplete="off" readonly />
                    </span>

                </div>
                <div class="form-item">
                    <span class="label">
                        <span class="text">已打码量</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                        <input id="total_bet" style="width: 70%;" type="text" name="total_bet" value="加载中..." autocomplete="off" readonly />
                        <input id="user_bet_list" style="float:right; width: 30%;" type="button" class="zx_submit" value="查看详情"/>
                    </span>
                </div>
                <input type="text"  name="abcd_Address" style="display: none" autocomplete="on" readonly /> <!-- 防止 填充-->
                <div class="form-item form-select">
                        <span class="label">
                            <span class="text">开户行</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                         <input type="text" class="no-bandbank" name="Bank_Address" value="<?php echo $row['Bank_Address'];?>" autocomplete="off" readonly />
                    </span>

                </div>

                <div class="form-item form-select">
                        <span class="label">
                            <span class="text">银行账户</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="text" class="no-bandbank"  value="<?php echo returnBankAccount($row['Bank_Account']);?>" autocomplete="off" readonly />
                            <input type="hidden" class="no-bandbank" name="Bank_Account" value="<?php echo $row['Bank_Account'];?>" autocomplete="off" readonly />
                    </span>
                </div>

                <div class="form-item form-select">
                        <span class="label">
                            <span class="text">所在银行</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="text" class="no-bandbank" name="Bank_Name" id="Bank_Name" value="<?php echo $row['Bank_Name'];?>" autocomplete="off" readonly />
                    </span>
                </div>

                <div class="form-item form-select">
                        <span class="label">
                            <span class="text">提款金额</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="number" class="money-textbox" id="money-textbox1" name="Money" autocomplete="off" placeholder="请输入提款金额">
                            <a class="textbox-close" href="javascript:;">╳</a>
                        </span>
                </div>

                <table class="money moneychoose">

                    <tbody>
                    <tr>
                        <td><span>100</span></td>
                        <td><span>300</span></td>
                        <td><span>500</span></td>
                        <td><span>800</span></td>
                    </tr>
                    <tr>
                        <td><span>1000</span></td>
                        <td><span>2000</span></td>
                        <td><span>3000</span></td>
                        <td><span>5000</span></td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-item form-select">
                        <span class="label" id="testaaa">
                            <span class="text">提款密码</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="password" style="display: none" name="abc_Passwd" autocomplete="on" minlength="4" maxlength="6" disabled /> <!-- 用于防止自动填充密码 -->
                            <input type="password" id="Withdrawal_Passwd" name="Withdrawal_Passwd" autocomplete="off" minlength="4" maxlength="6" placeholder="请输入提款密码" />
                        </span>
                </div>

                <div class="btn-wrap">
                    <a href="javascript:withcCheckInput();" class="zx_submit">确认提款</a>
                    <a href="/<?php echo TPL_NAME;?>account.php" class="zx_submit btn-reg">取消</a>
                </div>

                <input id="Alias" name="Alias" type="hidden" value="<?php echo $row['Alias'] ?>">  <!-- 真实姓名 -->

            </form>
        </div>

        <!-- 公用 联系我们-->
        <div class="contact-us">

        </div>

    </div>

    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>
<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<!--<script type="text/javascript" src="../../../js/layer/mobile/layer.js"></script>-->
<script type="text/javascript">
    var checkedMoney ='' ;
    var uid = '<?php echo $uid?>' ; // 用户id
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('在线提款','','',usermon,uid) ;
    setPublicContact() ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用
    addServerUrl() ;
    chooseAction(checkedMoney,'1') ;
    deleteMoney(checkedMoney) ;
    getUserBetDetail();
   // getUserBank(uid) ;
    var submitflage = false ; // 防止重复提交
    function withcCheckInput(){
        if(submitflage){
            return false ;
        }
        var user_mon = '<?php echo $user_Money?>' ;
        var withdraw_mon = $('#money-textbox1').val() ; // 提款金额
        var pswval = $('#Withdrawal_Passwd').val() ;
        var bank_address = $('input[name="Bank_Address"]').val() ; // 开户行地址
        var bank_account = $('input[name="Bank_Account"]').val() ; // 开户行账号
        var bank_name = $('input[name="Bank_Name"]').val() ; // 开户行名称
        var msg ='第一次提款请设置银行信息' ;
        if(bank_address == '' || bank_address == msg ){
            alertComing(msg);
            return false ;
        }
        if(bank_account=='' || bank_account == msg){
            alertComing(msg);
            return false ;
        }
          if(bank_name=='' || bank_name == msg){
              alertComing(msg);
              return false ;
          }

         if(!checkInputInt(withdraw_mon)){
             alertComing('请输入整数的提款金额，不能小于100元！');
             return false;
         }
        if(withdraw_mon < 100){
            alertComing('请输入正确的提款金额，不能小于100元！');
            return false;
        }
        if(withdraw_mon > returnMoney(user_mon)){
            alertComing('请输入正确的提款金额，不能大于用户余额！');
            return false;
        }
        if(pswval == '' || (pswval.length > 6 && pswval.length < 4 || !checkInputInt(pswval,'can'))){
            alertComing('请输入正确的提款密码！');
            return false;
        }
       // window.withdraw_form.submit();
        submitflage = true ;
        $.ajax({
            url: '/account/take.php' ,
            type: 'POST',
            dataType: 'json',
            data: $("#withdraw_form").serialize() ,
            success: function (res) {
                if(res.status !='200'){ // 请求成功
                    submitflage = false ;
                    alertComing(res.describe);
                }else{ // 提款成功
                    submitflage = false ;
                    alertComing(res.describe);
                    window.location.href='../account.php' ;
                }

            },
            error: function (res) {
                submitflage = false ;
                alertComing(config.errormsg);
            }
        });

    }

    var withdrawNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var withdrawMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(withdrawNum>0){ // 有弹窗短信
        alert(withdrawMsg);
       /* layer.open({
            content: withdrawMsg
            ,btn: '确定'
        });*/

    }

</script>

</body>
</html>