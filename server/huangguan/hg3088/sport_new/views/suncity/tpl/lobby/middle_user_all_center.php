<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

?>

<style>
    .memberWrap_top{background:#19181d;border-radius:5px;margin:5px 0 10px;padding:30px 20px}
    .memberWrap_top>div{display:inline-block;padding-right:40px}
    .memberWrap_top>div:nth-child(n+2){padding:0 40px}
    .memberWrap_top>div:nth-child(2):after{border-left:1px solid #333;position:absolute;display:inline-block;content:'';width:1px;height:80px;top:35px;margin-left:-42px}
    .memberWrap_top .row>div{line-height:26px}
    .memberWrap_top .memberWrap_top_right>div{display:inline-block;text-align:center;margin-right:30px;cursor:pointer}
    .memberWrap_top_right>div span{display:block;width:65px;height:63px;background:url(<?php echo TPL_NAME;?>images/user_top_icon.png) 2px 1px no-repeat}
    .memberWrap_top_right>div span.icon_qk{background-position:2px -70px}
    .memberWrap_top_right>div span.icon_zz{background-position:2px -141px}
    .memberWrap_top_right>div span.icon_jy{background-position:2px -212px}
    .memberWrap_top .qb_bottom{margin-top:10px}
    .memberWrap_top .qb_bottom span{color:#fee061;font-weight:700;font-size:22px}
    .memberWrap_top .qb_bottom .reload_money{display:inline-block;cursor:pointer;width:20px;height:20px;background:url(<?php echo TPL_NAME;?>images/reload_y.png) no-repeat;margin:0 10px}
    /* 左侧 */
    .memberWrap_left{background:#19181d;border-radius:5px;width:184px;padding:20px 8px;float:left}
    .memberWrap_left p{font-size:20px;font-weight:700;color:#fff;padding:0 10px 10px}
    .memberWrap_left li{background:#2f2e34;height:38px;line-height:38px;text-align:right;margin-bottom:10px;border-radius:5px;color:#8a8686;cursor:pointer;padding-right:40px}
    .memberWrap_left li.active,.memberWrap_left li:hover{background:url(<?php echo TPL_NAME;?>images/user_hover.png) no-repeat;color:#fff}
    .memberWrap_left li span{position:absolute;display:inline-block;width:25px;height:25px;background:url(<?php echo TPL_NAME;?>images/user_left_icon.png);float:left;margin:6px -50px}
    .memberWrap_left li span.icon_l_ck{background-position: -28px 0;}
    .memberWrap_left li span.icon_l_qk{background-position: -28px -34px;}
    .memberWrap_left li span.icon_l_zz{background-position: -28px -68px;}
    .memberWrap_left li span.icon_l_tz{background-position: -28px -100px;}
    .memberWrap_left li span.icon_l_jy{background-position: -28px -136px;}
    .memberWrap_left li span.icon_l_gg{background-position: -28px -168px;}
    .memberWrap_left li span.icon_l_zl{background-position: -28px -203px;}
    .memberWrap_left li span.icon_l_xg{background-position: -28px -238px;}
    .memberWrap_left li.active span.icon_l_ck{background-position: 0 0;}
    .memberWrap_left li.active span.icon_l_qk{background-position: 0px -34px;}
    .memberWrap_left li.active span.icon_l_zz{background-position: 0px -68px;}
    .memberWrap_left li.active span.icon_l_tz{background-position: 0px -100px;}
    .memberWrap_left li.active span.icon_l_jy{background-position: 0px -136px;}
    .memberWrap_left li.active span.icon_l_gg{background-position: 0px -168px;}
    .memberWrap_left li.active span.icon_l_zl{background-position: 0px -203px;}
    .memberWrap_left li.active span.icon_l_xg{background-position: 0px -238px;}
    .memberWrap_left li:hover span{background-position-x:0px;color: #fff; }
    .memberWrap_left li i{visibility: hidden;}
    .memberWrap_right{float: right;}
</style>

<div class="w_1200">
    <!-- 顶部 -->
    <div class="memberWrap_top">
        <div class="row">
            <div class="member-name">
                <strong>
                    <span id="label-username">尊敬的：<?php echo $username;?></span>
                </strong>
            </div>
            <div class="last-login">
                <span>上次登录:</span>
                <span id="label-lastlogintime"><?php echo $onlinetime;?></span>
            </div>
        </div>
        <div class="row">
            <p> 中心钱包 </p>
            <div class="qb_bottom">
                <span class="user_member_amount"> 加载中... </span>
                <span class="reload_money" onclick="indexCommonObj.getUserMoneyAction(uid)" ></span>
                <a href="javascript:;"> 一键回收 </a>
            </div>
        </div>
        <div class="row memberWrap_top_right">
            <div class="to_deposit"> <span ></span><p>存款</p></div>
            <div class="to_withdraw"> <span class="icon_qk"></span><p>取款</p></div>
            <div class="to_platform_tranfer"> <span class="icon_zz"></span><p>转账</p></div>
            <div class="to_userbetaccount"> <span class="icon_jy"></span><p>交易记录</p></div>
        </div>

    </div>

    <!-- 中间部分 -->
    <div class="memberWrap_bottom">
        <!-- 左侧 -->
        <div class="memberWrap_left ">
            <p class="title">财务中心</p>
            <ul class="memberUserLeft">
                <li class="to_deposit <?php echo $type=='deposit'?'active':'';?>">
                    <span class="icon_l_ck"></span> 存款<i>占位</i>
                </li>
                <li class="to_withdraw <?php echo $type=='withdraw'?'active':'';?>">
                    <span class="icon_l_qk"></span> 取款<i>占位</i>
                </li>
                <li class="to_platform_tranfer <?php echo $type=='tranfer'?'active':'';?>">
                    <span class="icon_l_zz"></span> 转账<i>占位</i>
                </li>
                <li class="to_userbetaccount <?php echo $type=='userbetaccount'?'active':'';?>">
                    <span class="icon_l_tz"></span> 投注记录
                </li>
                <li class="to_userbetaccount">
                    <span class="icon_l_jy"></span> 交易记录
                </li>

            </ul>

            <p class="title">个人中心</p>
            <ul class="memberUserLeft">
                <li class="to_user_email <?php echo $type=='email'?'active':'';?>">
                    <span class="icon_l_gg"></span> 消息公告
                </li>
                <li class="to_usercenter <?php echo $type=='usercenter'?'active':'';?>">
                    <span class="icon_l_zl"></span> 个人资料
                </li>
                <!--       <li>
                           <span class="icon_l_xg"></span> 修改密码
                       </li>-->

            </ul>

        </div>

        <!-- 右侧 -->
        <div class="middle_usercenter_content memberWrap_right">

        </div>

    </div>

</div>
<script type="text/javascript" src="js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">


    $(function () {
        var type = '<?php echo $type;?>';

        // 加载页面函数
        function loadUserCenterAll() {
            var callBackFunction ;
            switch (type){
                case 'deposit': // 存款
                    callBackFunction = indexCommonObj.loadDepositPage() ;
                    break;
                case 'withdraw': // 提款
                    callBackFunction = indexCommonObj.loadWithdrawPage() ;
                    break;
                case 'tranfer': // 转账
                    callBackFunction = indexCommonObj.loadUserPlatformPage() ;
                    break;
                case 'userbetaccount': // 帐户记录
                    callBackFunction = indexCommonObj.loadUserAccountPage() ;
                    break;
                case 'email': // 消息公告
                    callBackFunction = indexCommonObj.loadUserMails() ;
                    break;
                case 'usercenter': // 个人资料
                    callBackFunction = indexCommonObj.loadUserCenterPage() ;
                    break;

            }
        }
        // 标签切换
        function changeLeftNav(){
            $('.memberUserLeft').on('click','li',function () {
                $(this).parent().siblings().find('li').removeClass('active');
                $(this).addClass('active').siblings().removeClass('active');
            })
        }

        changeLeftNav();
        loadUserCenterAll();


    })
</script>