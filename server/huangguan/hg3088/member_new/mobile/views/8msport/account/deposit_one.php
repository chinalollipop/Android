<?php
session_start();
include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='../login.php';</script>";
    exit;
}

if( $_SESSION['Agents'] == 'demoguest') {
    echo "<script>alert('非常抱歉，请您注册真实会员！');window.location.href='../index.php';</script>";
    exit;
}

$user_id = $_SESSION['userid'];
if(!empty($user_id)) {
    $member_sql = "select ID,UserName,layer from ".DBPREFIX.MEMBERTABLE." where ID='$user_id'";
    $member_query = mysqli_query($dbLink,$member_sql);
    $memberinfo = mysqli_fetch_assoc($member_query);
}
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

$uid=$_SESSION['Oid'];

if($_SESSION['Alias'] == ''){ // 没有设置真实姓名
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
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
    <style>
        .content-center{text-align: left;}
        .deposit-nav .bank_img_8{background:url(images/deposit/ysf.png) no-repeat;background-size:contain}
        .pay_title{color:#333;text-align:left;padding:2%;font-size:1.2rem}
        .deposit-nav .item:after{display:none}
    </style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center deposit">
        <div class="bg_yy">
            <div class="tip_title"><span class="linear-color-1">1</span>请选择支付方式及通道</div>
            <div class="tab">
                <div class="deposit-nav">


                </div>

            </div>
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

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('在线存款','','',usermon,uid) ;
    setPublicContact() ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用
    getBankList() ;

    // 支付方式列表
    function getBankList() {
        var beturl = '/account/deposit_one_api.php' ;
        $.ajax({
            url: beturl ,
            type: 'POST',
            dataType: 'json',
            data: '',
            success: function (res) {

                if(res.status !='200'){ // 有错误信息
                    setPublicPop(res.describe);
                }else{ // 成功
                    var  str = '' ;
                    var tag = 'target="_blank"' ;
                    if( res.data.length>0){
                          for(var i=0;i< res.data.length;i++){
                              if(res.data[i].id==0){ // 快速充值
                                  str +='<a href="'+ res.data[i].api +'?bankid='+ res.data[i].bankid +'"  class="item" '+ tag +'>';
                              }else{
                                  str +='<a href="'+ returnBnakUrl(res.data[i].id) +'?bankid='+ res.data[i].bankid +'"  class="item" >';
                              }
                              str += '<i class="bank_img bank_img_'+  res.data[i].id  +'"></i>'+
                                    '<span>'+ res.data[i].title +'</span>'+
                                    '</a>' ;
                           }

                    }
                    $('.deposit-nav').html(str) ;

                }

            },
            error:function (msg) {
                setPublicPop(config.errormsg);
            }

        }) ;
    }

    function returnBnakUrl(id) {
        var url ='' ;
        switch (id){
            case 1: // 银行卡线上
                url = 'deposit_two_third_bank.php' ;
                break ;
            case 2: // 公司入款
                url = 'deposit_two_bank_company.php' ;
                break ;
            case 3: // 微信第三方
                url = 'deposit_two_third_bank.php' ;
                break ;
            case 4: // 支付宝第三方
                url = 'deposit_two_third_zfb.php' ;
                break ;
            case 5: // QQ第三方
                url = 'deposit_two_third_qq.php' ;
                break ;
            case 6: // 支付宝扫码
                url = 'bank_type_ALISAOMA.php' ;
                break ;
            case 7: // 微信扫码
                url = 'bank_type_WESAOMA.php' ;
                break ;
            case 8: // 银联扫码|云闪付
                url = 'bank_type_YLSMYSF.php' ;
                break ;
        }
        return url ;
    }

</script>

</body>
</html>