<?php
session_start();
include_once('../../include/config.inc.php');

include('../../include/address.mem.php');
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('你的登录信息已过期，请先登录!');window.location.href='login.php';</script>";
    exit;
}
$langx=$_SESSION['Language'];
$UserName = $_SESSION['UserName'];
$uid = $_SESSION['Oid'];
$hgId = $_SESSION['userid'];
$test_flag = $_SESSION['test_flag']; // 测试账号为1

?>
<html>
<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="apple-mobile-web-app-title" >
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
        <!--<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/add-logo.png"> <!-- 添加到桌面 -->
        <title class="web-title"></title>
    <style>
        .ed_top{display:-webkit-flex;display:flex;justify-content:space-between}
        .ed_top .btn{padding:4% 2% 0}
        .ed_top .btn a{padding:.5rem;border-radius:5px;margin-right:.5rem}
        .top_user_ye{width:98%;margin:1rem auto 0;overflow-y:hidden;border-radius:5px}
        .top_user_ye>div{float:left;width:30%;margin:.5rem 3% .8rem 1%;color:#2d3134;text-align:center;height:4rem;line-height:1.3rem;padding:1rem 0;background:#fff;box-shadow:0px 0 10px 0px rgba(0,0,0,.2);border-radius:5px}
        .top_user_ye>div:nth-child(3n){margin-right:0}
        .top_user_ye>div span{color:#5ea0ea;display: block;}
        .top_user_ye>div a{display:inline-block;margin-top:.3rem;padding:.2rem .5rem;border-radius:20px}
        .user ul li{border:0}
        .select_edzh{margin:1rem 2rem}
        .select_edzh select{width:100%;border:2px solid #cacaca}
        .dropdown:after{margin:0.6em 0 0 -7%;}
        .user ul input{width:94%;margin:0 3% 1rem;text-align:left;border:2px solid #cacaca}

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
            <div  class="user">
                <div class="bg_yy">
                    <div class="ed_top">
                        <div class="tip_title"><span class="linear-color-1">1</span>平台余额</div>
                        <div class="btn right">
                            <a class="linear-color-1 ye_refurbish" onclick="getUserAllMoney()">全部更新</a>
                            <a class="linear-color-1 btn_retrieve">一键回收</a>
                        </div>
                    </div>

                    <div class="top_user_ye">
                        <div><p>中心钱包</p><span class="hg_money">0.00</span></div>
                        <!--<div><p>皇冠体育</p><span class="sc_money">0.00</span></div>-->
                       <!-- <div><p>彩票</p><span class="cp_money">0.00</span></div>-->
                        <div class="thirdYe"><p>彩票</p><span class="gmcp_money">0.00</span> <a class="linear-color-1" data-type="gmcp">一键转入</a> </div>
                        <div class="thirdYe"><p>AG平台</p><span class="ag_money">0.00</span> <a class="linear-color-1" data-type="ag">一键转入</a> </div>
                        <div class="thirdYe"><p>开元棋牌</p><span class="ky_money">0.00</span> <a class="linear-color-1" data-type="ky">一键转入</a> </div>
                       <!-- <div class="thirdYe"><p>皇冠棋牌</p><span class="ff_money">0.00</span> <a class="linear-color-1" data-type="ff">一键转入</a>  </div>-->
                        <div class="thirdYe"><p>VG棋牌</p><span class="vg_money">0.00</span> <a class="linear-color-1" data-type="vg">一键转入</a> </div>
                        <div class="thirdYe"><p>快乐棋牌</p><span class="kl_money">0.00</span> <a class="linear-color-1" data-type="vg">一键转入</a> </div>
                        <div class="thirdYe"><p>乐游棋牌</p><span class="ly_money">0.00</span> <a class="linear-color-1" data-type="ly">一键转入</a> </div>
                        <div class="thirdYe"><p>MG电子</p><span class="mg_money">0.00</span> <a class="linear-color-1" data-type="mg">一键转入</a> </div>
                        <div class="thirdYe"><p>OG视讯</p><span class="og_money">0.00</span> <a class="linear-color-1" data-type="og">一键转入</a> </div>
                        <div class="thirdYe"><p>BBIN视讯</p><span class="bbin_money">0.00</span> <a class="linear-color-1" data-type="bbin">一键转入</a> </div>
                        <div class="thirdYe"><p>CQ9电子</p><span class="cq_money">0.00</span> <a class="linear-color-1" data-type="cq">一键转入</a> </div>
                        <div class="thirdYe"><p>MW电子</p><span class="mw_money">0.00</span> <a class="linear-color-1" data-type="mw">一键转入</a> </div>
                        <div class="thirdYe"><p>FG电子</p><span class="fg_money">0.00</span> <a class="linear-color-1" data-type="fg">一键转入</a> </div>
                        <div class="thirdYe"><p>泛亚电竞</p><span class="avia_money">0.00</span> <a class="linear-color-1" data-type="avia">一键转入</a> </div>
                        <div class="thirdYe"><p>雷火电竞</p><span class="fire_money">0.00</span> <a class="linear-color-1" data-type="fire">一键转入</a> </div>
                    </div>
                </div>
                <div class="bg_yy">
                    <div class="tip_title"><span class="linear-color-1">2</span>转出钱包</div>
                    <div class="select_edzh dropdown">
                        <select  name="f_blance" id="f_blance">
                            <option value="">请选择平台</option>
                            <option value="hg">中心钱包</option>
                            <!--<option value="sc">皇冠体育</option>-->
                            <?php
                            if(!$test_flag){ // 非测试帐号
                                echo '
                                     <option value="gmcp">彩票</option>
                                    <!--<option value="cp">彩票</option>-->
                                    <option value="ag">AG平台</option>
                                    <option value="og">OG视讯</option>
                                    <option value="bbin">BBIN视讯</option>
                                    <option value="ky">开元棋牌</option>
                                    <!--<option value="ff">皇冠棋牌</option>-->
                                    <option value="vg">VG棋牌</option>
                                    <option value="kl">快乐棋牌</option>
                                    <option value="ly">乐游棋牌</option>
                                    <option value="mg">MG电子</option>
                                    <option value="cq">CQ9电子</option>
                                    <option value="mw">MW电子</option>
                                    <option value="fg">FG电子</option>
                                    <option value="avia">泛亚电竞</option>
                                    <option value="fire">雷火电竞</option>
                                ';
                            }
                            ?>

                        </select>
                    </div>
                </div>

                <div class="bg_yy">
                    <div class="tip_title"><span class="linear-color-1">3</span>转入钱包</div>
                    <div class="select_edzh dropdown">
                        <select name="t_blance" id="t_blance" >
                            <option value="">请选择平台</option>
                            <option value="hg">中心钱包</option>
                            <!--<option value="sc">皇冠体育</option>-->
                            <?php
                                if(!$test_flag) { // 非测试帐号
                                    echo '
                                        <option value="gmcp">彩票</option>
                                        <!--<option value="cp" >彩票</option>-->
                                        <option value="ag" >AG平台</option>
                                        <option value="og">OG视讯</option>
                                        <option value="bbin">BBIN视讯</option>
                                        <option value="ky">开元棋牌</option>
                                        <!--<option value="ff">皇冠棋牌</option>-->
                                        <option value="vg">VG棋牌</option>
                                        <option value="kl">快乐棋牌</option>
                                        <option value="ly">乐游棋牌</option>
                                        <option value="mg">MG电子</option>
                                        <option value="cq">CQ9电子</option>
                                        <option value="mw">MW电子</option>
                                        <option value="fg">FG电子</option>
                                        <option value="avia">泛亚电竞</option>
                                        <option value="fire">雷火电竞</option>
                                    ';
                                }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="bg_yy">
                    <div class="tip_title"><span class="linear-color-1">4</span>转账金额</div>
                    <ul>
                        <li>
                            <div class="sbtn moneychoose">
                                <button value="100">100</button>
                                <button value="500">500</button>
                                <button value="1000">1000</button>
                                <button value="2000">2000</button>
                                <button value="5000">5000</button>
                            </div>
                        </li>
                        <li>
                            <input class="enter money-textbox" placeholder="￥请输入转账金额" name="blance" id="blance">
                        </li>

                </ul>
                </div>
                <div class="bottom">
                    <button class="close_ft_nav" name="trans_blance" id="trans_blance">确认转帐</button>
                    <div class="bg_yy">
                        <h3>温馨提示：</h3>
                        <ol>
                            <li>1.转账前请退出游戏或游戏投注界面。</li>
                            <li>2.不参与活动时, 户内转账金额不能少于 1元，户内转账不收取任何手续费。</li>
                            <li>3.如遇网速较慢时，请耐心等候片刻，不要多次重复提交。</li>
                        </ol>
                    </div>
                </div>
            </div>
    </div>

    <!-- 底部footer -->
    <div id="footer">

    </div>
</div>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/usercenter.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var uid = '<?php echo $uid?>' ;
    var id = "<?php echo $hgId;?>";
    var userName = "<?php echo $UserName;?>";
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var checkedMoney = '';
    var test_flag = '<?php echo $test_flag;?>';
    setLoginHeaderAction('额度转换','','',usermon,uid) ;
    setFooterAction(uid) ;
    chooseAction(checkedMoney);

    getUserAllMoney();
    // 获取余额
    function getUserAllMoney() {
        get_blance('balance'); // 获取AG余额

        //get_sc_balance('.sc_money'); // 皇冠体育余额

        if(test_flag ==0){
            get_gmcp_balance('.gmcp_money'); // 获取三方彩票余额
            get_ky_balance('.ky_money'); // 获取开元棋牌余额
            //get_ff_balance('.ff_money'); // 获取皇冠棋牌余额
            get_vg_balance('.vg_money'); // 获取VG棋牌余额
            get_kl_balance('.kl_money'); // 获取快乐棋牌余额
            get_ly_balance('.ly_money'); // 获取乐游棋牌余额
            get_mg_balance('.mg_money'); // 获取MG电子余额
            get_og_balance('.og_money'); // 获取OG视讯余额
            get_bbin_balance('.bbin_money'); // 获取BBIN视讯余额
            get_cq_balance('.cq_money'); // 获取cq9余额
            get_mw_balance('.mw_money'); // 获取MW电子余额
            get_fg_balance('.fg_money'); // 获取fg电子余额
            get_avia_balance('.avia_money'); // 获取泛亚电竞余额
            get_fire_balance('.fire_money'); // 获取雷火电竞余额
        }
    }

    tranUserMoney();
    oneTransfer();
    oneRecovery();

</script>  
</body>
</html>