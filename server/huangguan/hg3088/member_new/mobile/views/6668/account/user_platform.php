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
        <meta name="apple-mobile-web-app-title" content="<?php echo COMPANY_NAME;?>">
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
        <!--<link href="../../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/add-logo.png"> <!-- 添加到桌面 -->
        <title class="web-title"></title>

</head>
<body style="background:#f9f9f9" >
<!--额度转换-->
<div id="container" class="dialog-content">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center">
            <div id="creditsChangeBox" class="user">
                <ul class="platform">
                  <!--  <li>
                        <p>皇冠体育</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="sc" data-from="hg" data-to="cp" >转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="sc" data-from="sc" data-to="hg" >转出</a>
                        </div>
                        <h6 class="sc_money">0.00</h6>
                    </li>-->
                    <li>
                        <p>彩票平台</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="cp" data-from="hg" data-to="cp" >转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="cp" data-from="cp" data-to="hg" >转出</a>
                        </div>
                       <!-- <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="bbin"  data-status="working"></i>
                        </div>-->
                        <h6 class="cp_money">0.00</h6>
                    </li>
                <!--    <li>
                        <p>国民彩票平台</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="gmcp" data-from="hg" data-to="gmcp" >转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="gmcp" data-from="gmcp" data-to="hg" >转出</a>
                        </div>
                        <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="bbin"  data-status="working"></i>
                        </div>
                        <h6 class="gmcp_money">0.00</h6>
                    </li>-->
                    <li>
                        <p>AG平台</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="ag" data-from="hg" data-to="ag">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="ag" data-from="ag" data-to="hg">转出</a>
                        </div>
                        <!--<div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="ag_ag_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="ag_money">0.00</h6>
                    </li>
                    <li>
                        <p>OG视讯</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="og" data-from="hg" data-to="og">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="og" data-from="og" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="og_og_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="og_money">0.00</h6>
                    </li>
                    <li>
                        <p>BBIN视讯</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="bbin" data-from="hg" data-to="bbin">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="bbin" data-from="bbin" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="og_og_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="bbin_money">0.00</h6>
                    </li>
                    <li>
                        <p>开元棋牌</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="ky" data-from="hg" data-to="ky">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="ky" data-from="ky" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="ky_ky_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="ky_money">0.00</h6>
                    </li>
                   <!-- <li>
                        <p>皇冠棋牌</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="hgqp" data-from="hg" data-to="ff">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="hgqp" data-from="ff" data-to="hg">转出</a>
                        </div>
                        <h6 class="ff_money">0.00</h6>
                    </li>-->
                    <li>
                        <p>VG棋牌</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="vgqp" data-from="hg" data-to="vg">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="vgqp" data-from="vg" data-to="hg">转出</a>
                        </div>
                       <!-- <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="vg_vg_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="vg_money">0.00</h6>
                    </li>
                    <li>
                        <p>快乐棋牌</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="klqp" data-from="hg" data-to="kl">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="klqp" data-from="kl" data-to="hg">转出</a>
                        </div>
                        <h6 class="kl_money">0.00</h6>
                    </li>
                    <li>
                        <p>乐游棋牌</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="lyqp" data-from="hg" data-to="ly">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="lyqp" data-from="ly" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="ly_ly_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="ly_money">0.00</h6>
                    </li>
                    <li>
                        <p>MG电子</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="mg" data-from="hg" data-to="mg">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="mg" data-from="mg" data-to="hg">转出</a>
                        </div>
                       <!-- <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="mg_mg_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="mg_money">0.00</h6>
                    </li>
                    <li>
                        <p>CQ9电子</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="cq" data-from="hg" data-to="cq">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="cq" data-from="cq" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="mg_cq_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="cq_money">0.00</h6>
                    </li>
                    <li>
                        <p>MW电子</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="mw" data-from="hg" data-to="mw">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="mw" data-from="mw" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="mw_mw_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="mw_money">0.00</h6>
                    </li>
                    <li>
                        <p>FG电子</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="fg" data-from="hg" data-to="fg">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="fg" data-from="fg" data-to="hg">转出</a>
                        </div>
                      <!--  <div class="plateform_re">
                            <i class="fa fa-refresh fa-spin" com="ag_ag" id="fg_fg_ccl" data-status="working"></i>
                        </div>-->
                        <h6 class="fg_money">0.00</h6>
                    </li>
                    <li>
                        <p>泛亚电竞</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="avia" data-from="hg" data-to="avia">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="avia" data-from="avia" data-to="hg">转出</a>
                        </div>
                        <!--  <div class="plateform_re">
                              <i class="fa fa-refresh fa-spin" com="ag_ag" id="avia_avia_ccl" data-status="working"></i>
                          </div>-->
                        <h6 class="avia_money">0.00</h6>
                    </li>
                    <li>
                        <p>雷火电竞</p>
                        <div class="turn_inout" >
                            <a href="javascript:;" class="transfer_all" data-platform="fire" data-from="hg" data-to="fire">转入</a> |
                            <a href="javascript:;" class="transfer_all" data-platform="fire" data-from="fire" data-to="hg">转出</a>
                        </div>
                        <!--  <div class="plateform_re">
                              <i class="fa fa-refresh fa-spin" com="ag_ag" id="avia_avia_ccl" data-status="working"></i>
                          </div>-->
                        <h6 class="fire_money">0.00</h6>
                    </li>
                </ul>

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

    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>

<!--转账弹窗-->

<div class="change_pop_all"><div class="change_pop_bg" ></div>
    <div id="all_pop"></div>
    <div class="change_Pop-up pop_close" >
        <div class="btn_close pop_cls close_event"><i class="fa fa-times"></i></div>
        <input class="alert_input money-textbox" placeholder="请输入转账金额" name="alert_blance" id="alert_blance">
        <button class="change_login_btn">确定</button>
    </div>
</div>

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

    //get_gmcp_balance('.gmcp_money'); // 三方彩票余额
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
    get_fg_balance('.fg_money'); // 获取fg电子余额
    //get_sc_balance('.sc_money'); // 获取皇冠体育余额

    get_blance('balance'); // 获取AG余额

    clickChangeMoney();
</script>  
</body>
</html>