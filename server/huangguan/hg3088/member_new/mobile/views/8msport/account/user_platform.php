<?php
session_start();
include_once('../../../include/config.inc.php');
include('../../../include/address.mem.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='../login.php';</script>";
    exit;
}
$langx=$_SESSION['Language'];
$UserName = $_SESSION['UserName'];
$uid = $_SESSION['Oid'];
$hgId = $_SESSION['userid'];

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="apple-mobile-web-app-title" >
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/add-logo.png"> <!-- 添加到桌面 -->
    <title class="web-title"></title>
    <style>
        /*平台金额*/
        .top_user_ye{width:98%;margin:1rem auto 0;overflow-y:hidden;border-radius:5px}
        .top_user_ye>div{float:left;width:30%;margin:.5rem 3% .8rem 1%;color:#2d3134;text-align:center;height:2rem;line-height:1.3rem;padding:1rem 0;background:#fff;box-shadow:0px 0 10px 0px rgba(0,0,0,.2);border-radius:5px}
        .top_user_ye>div:nth-child(3n){margin-right:0}
        .top_user_ye>div span{color:#5ea0ea}
        .select_edzh{margin:1rem 2rem}
        .select_edzh select{width:100%;border:2px solid #cacaca}
        .dropdown:after{margin:0.6em 0 0 -7%;}
        .plateform_sel{margin:10px 0;}
        .platform_input {margin-top: 3%;}
        input{width:94%;margin:0 3% 1rem;text-align:left;border:2px solid #cacaca}
        /*
        .platform_input{color:#3c3941;padding:10px 5% 0;box-shadow:0px 2px 10px 0px rgba(0,0,0,.1);border-radius:5px;font-size:1.1rem}
        .alert_input{border:0;width:76%;margin-bottom:1rem;padding-left:2%;border-radius:5px;box-shadow:none}*/
        .change_btn{margin:20px 2%;}
        .change_btn a{font-size: 1.1rem;display:inline-block;width:46%;border-radius:20px;box-shadow:0px 2px 10px 0px rgba(0,0,0,.1);text-align:center;padding:10px 0}
        .change_btn a:last-child{margin-left: 6%;}
    </style>

</head>
<body >
<!--额度转换-->
<div id="container" class="dialog-content">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center">
        <div id="creditsChangeBox" class="user">
            <div class="bg_yy">
                <div class="tip_title"><span class="linear-color-1">1</span>平台余额</div>
                <div class="top_user_ye">
                    <div><p>中心钱包余额</p><span class="hg_money">0.00</span></div>
                    <!--<div><p>皇冠体育余额</p><span class="sc_money">0.00</span></div>-->
                    <!-- <div><p>彩票余额</p><span class="cp_money">0.00</span></div>-->
                    <div><p>彩票余额</p><span class="gmcp_money">0.00</span></div>
                    <div><p>AG余额</p><span class="ag_money">0.00</span></div>
                    <div><p>开元棋牌余额</p><span class="ky_money">0.00</span></div>
                    <!--<div><p>皇冠棋牌余额</p><span class="ff_money">0.00</span></div>-->
                    <div><p>VG棋牌余额</p><span class="vg_money">0.00</span></div>
                    <div><p>快乐棋牌余额</p><span class="kl_money">0.00</span></div>
                    <div><p>乐游棋牌余额</p><span class="ly_money">0.00</span></div>
                    <div><p>MG电子余额</p><span class="mg_money">0.00</span></div>
                    <div><p>OG视讯余额</p><span class="og_money">0.00</span></div>
                    <div><p>BBIN视讯余额</p><span class="bbin_money">0.00</span></div>
                    <div><p>CQ9电子余额</p><span class="cq_money">0.00</span></div>
                    <div><p>MW电子余额</p><span class="mw_money">0.00</span></div>
                    <div><p>FG电子余额</p><span class="fg_money">0.00</span></div>
                    <div><p>泛亚电竞余额</p><span class="avia_money">0.00</span></div>
                    <div><p>雷火电竞余额</p><span class="fire_money">0.00</span></div>
                </div>
            </div>
            <div class="bg_yy">
                <div class="tip_title"><span class="linear-color-1">2</span>请选择平台</div>
                <div class="select_edzh dropdown">
                    <select class="plateform_sel">
                        <option data-platform="" data-to=""> 请选择平台 </option>
                        <!--<option data-platform="sc" data-to="sc"> 皇冠体育 </option>-->
                        <option data-platform="gmcp" data-to="gmcp"> 彩票平台 </option>
                        <option data-platform="ag" data-to="ag"> AG平台 </option>
                        <option data-platform="og" data-to="og"> OG平台 </option>
                        <option data-platform="bbin" data-to="bbin"> BBIN视讯 </option>
                        <option data-platform="ky" data-to="ky"> 开元棋牌平台 </option>
                        <!--<option data-platform="hgqp" data-to="ff"> 皇冠棋牌平台 </option>-->
                        <option data-platform="vgqp" data-to="vg"> VG棋牌平台 </option>
                        <option data-platform="klqp" data-to="kl"> 快乐棋牌平台 </option>
                        <option data-platform="lyqp" data-to="ly"> 乐游棋牌平台 </option>
                        <option data-platform="mg" data-to="mg"> MG平台 </option>
                        <option data-platform="mw" data-to="mw"> MW电子平台 </option>
                        <option data-platform="fg" data-to="fg"> FG电子平台 </option>
                        <option data-platform="avia" data-to="avia"> 泛亚电竞平台 </option>
                        <option data-platform="fire" data-to="fire"> 雷火电竞平台 </option>
                    </select>
                </div>
            </div>
            <div class="bg_yy">
                <div class="tip_title"><span class="linear-color-1">3</span>输入转账金额</div>
                <div class="platform_input">
                    <!--<label>￥</label>--><input class="alert_input money-textbox" placeholder="￥请输入转账金额" name="alert_blance" id="alert_blance">
                </div>
            </div>
            <div class="change_btn">
                <a class="btn_1 linear-color-1" href="javascript:;" data-from="hg" data-type="in">余额转入</a>
                <a class="btn_2 linear-color-1" href="javascript:;" data-type="out">余额转出</a>
            </div>

            <div class="bg_yy">
                 <ul>
                       <li>
                           <h3>温馨提示：</h3>
                           <ol>
                               <li>1.转账前请退出游戏或游戏投注界面。</li>
                               <li>2.不参与活动时, 户内转账金额不能少于 1元，户内转账不收取任何手续费。</li>
                               <li>3.如遇网速较慢时，请耐心等候片刻，不要多次重复提交。</li>
                           </ol>
                       </li>
                   </ul>
            </div>
        </div>
    </div>

    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>

<!--转账弹窗-->

<!--<div class="change_pop_all"><div class="change_pop_bg" ></div>
    <div id="all_pop"></div>
    <div class="change_Pop-up pop_close" >
        <div class="btn_close pop_cls close_event"><i class="fa fa-times"></i></div>
        <input class="alert_input money-textbox" placeholder="请输入转账金额" name="alert_blance" id="alert_blance">
        <button class="change_login_btn">确定</button>
    </div>
</div>-->

<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid = '<?php echo $uid?>' ;
    var userName = '<?php echo $UserName?>' ;
    var id = '<?php echo $hgId?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie

    setLoginHeaderAction('平台余额','','',usermon,uid) ;
    setFooterAction(uid) ;

    //get_sc_balance('.sc_money'); // 获取皇冠体育余额
   get_blance('balance'); // 获取AG余额

    get_gmcp_balance('.gmcp_money'); // 三方彩票余额
    get_ky_balance('.ky_money'); // 获取开元棋牌余额
    //get_ff_balance('.ff_money'); // 获取皇冠棋牌余额
    get_vg_balance('.vg_money'); // 获取VG棋牌余额
    get_kl_balance('.kl_money'); // 获取快乐棋牌余额
    get_ly_balance('.ly_money'); // 获取乐游棋牌余额
    get_mg_balance('.mg_money'); // 获取MG电子余额
    get_avia_balance('.avia_money'); // 获取泛亚电竞余额
    get_fire_balance('.fire_money'); // 获取雷火电竞余额
    get_og_balance('.og_money'); // 获取OG视讯余额
    get_bbin_balance('.bbin_money'); // 获取BBIN视讯余额
    get_cq_balance('.cq_money'); // 获取CQ9电子余额
    get_mw_balance('.mw_money'); // 获取MW电子余额
    get_fg_balance('.fg_money'); // 获取FG电子余额

    clickChangeMoney();
</script>  
</body>
</html>