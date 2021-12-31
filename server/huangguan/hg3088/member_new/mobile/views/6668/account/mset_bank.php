<?php
// 手机端设置银行账号
session_start();
include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$Alias = $_SESSION['Alias'];
$Bank_Name = $_SESSION['Bank_Name'] ;
$Bank_Account = $_SESSION['Bank_Account'] ;
$Bank_Address = $_SESSION['Bank_Address'] ;

if($Alias==''){ // 未设置真实姓名
    echo "<script language=javascript>alert('请先设置您的真实姓名！'); window.location.href = './mset_realname.php';</script>";
}

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
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">

    <title class="web-title"></title>
    <style>

    </style>
</head>
<body class="bg_f9f9f9">
<div id="container" >
    <!-- 头部 -->
    <div class="header "></div>

    <!-- 中间内容 -->
    <div class="content-center deposit-two">
        <!--<div class="top-deposit">
            <div class="login-logo"></div>
            <h2 class="web-title"></h2>
        </div>-->
        <!-- 设置银行开始 -->
        <div class="" data-area="bank_pay">
            <form method="post" name="setbank" id="setbank" action="">
            <div class="form-item form-select">
                    <span class="label">
                        <span class="text">帐户姓名:</span>
                        <span class="line"></span>
                    </span>
                <span class="textbox">
                    <input type="hidden" name="bankFlag"  id="bankFlag" value="1" />
                    <input type="text" class="bank-account" value="<?php echo returnRealName($Alias)?>" readonly />
                    <input type="hidden" name="bank_name" id="bank_name" class="bank-account" value="<?php echo $Alias?>" readonly />
             </span>
            </div>
            <div class="form-item form-select">
                        <span class="label">
                            <span class="text"><!--<span class="red_color">*</span>--> 开户银行:</span>
                            <span class="line"></span>
                        </span>
                <span class="dropdown">
                <select id="chg_bank" name="chg_bank">

                </select>
                </span>
            </div>


            <div class="form-item form-select">
                        <span class="label">
                            <span class="text">银行账号:</span>
                            <span class="line"></span>
                        </span>
                <span class="textbox">
                    <input type="text" class="show-bank-account" value="<?php echo returnBankAccount($Bank_Account)?>" placeholder="银行账号" />
                    <input type="hidden" name="bank_Account" id="bank_Account" class="bank-account" value="<?php echo $Bank_Account?>" />
                 </span>
            </div>


            <div class="form-item form-select">
                <span class="label">
                    <span class="text">银行地址:</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input type="text" name="bank_Address" id="bank_Address" class="bank-address" value="<?php echo $Bank_Address?>" placeholder="银行地址" />

                 </span>
            </div>
      <?php if(!$_SESSION['Address']){

      ?>
            <div class="form-item form-select">
                <span class="label">
                    <span class="text">提款密码:</span>
                    <span class="line"></span>
                </span>
                    <span class="textbox">
                    <input type="password" name="paypassword1" id="paypassword1" class="paypassword1" minlength="6" maxlength="6" placeholder="请输入6位纯数字" />
                 </span>
            </div>
            <div class="form-item form-select">
                <span class="label">
                    <span class="text">确认密码:</span>
                    <span class="line"></span>
                </span>
                        <span class="textbox">
                    <input type="password" name="paypassword2" id="paypassword2" class="paypassword2" minlength="6" maxlength="6" placeholder="确认密码" />
                 </span>
            </div>
          <?php  } ?>

            <div class="btn-wrap">
                <a href="javascript:;" class="zx_submit bind_bank_btn" >提交设置</a>
                <a href="/<?php echo TPL_NAME;?>account.php" class="zx_submit btn-reg">取消设置</a>
            </div>
             </form>
        </div>

    </div>

    <!-- 底部 -->
    <div id="footer">

    </div>
</div>

<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>


<script type="text/javascript">
   $(function () {

       var uid = '<?php echo $uid?>' ;
       var usermon = getCookieAction('member_money') ; // 获取信息cookie
       var Bank_Name = '<?php echo $Bank_Name;?>' ; // 已绑定银行名称
       getBnakNameList(Bank_Name);

       setLoginHeaderAction('设置银行账号','','',usermon,uid) ; // 充值方式标题
       setFooterAction(uid) ; // 在 addServerUrl 前调用
       editBankAccount();
       bindBankAction();


   })

</script>
</body>
</html>