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

    .member-title{background:#3b3a3a;height:154px;margin-top:10px}
    .memberWrap .wrap{padding-top:20px}
    .member-title-top{box-sizing:border-box;display:flex;align-items:center;width:100%;padding:10px 40px}
    .head-user-icon img{width:76px}
    .member-title-top .member-center{font-size:24px;color:#ffffff;padding:0 35px}
    .member-title-top .head-money p{font-size:16px;padding:5px 0px;color:#ffffff}
    .member-title-link{padding-left:40px}
    .member-title-link a{border:none;width:120px;height:35px;line-height:35px;text-align:center;-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;font-size:14px;background:#811f1d;color:#ffffff;display:inline-block}
    .member-title-link a.active{background:#f00b07}
    .member-center-link ul li{float:left;width:14.28%}
    .member-center-link{margin:4px 0px;border-top:1px solid #c70300;background:#313131}
    .member-center-link ul li.active a{color:#a0a0a0;background:#242424}
    .member-center-link ul li a{display:block;height:70px;font-size:18px;color:#ffffff;text-align:center;line-height:70px;position:relative}
    .member-center-link ul li a:after{content:'';width:3px;background:#404040;height:100%;position:absolute;top:0;right:0}
    .head-btn{margin-left:40px}
    .head-btn .btn{background: #d9534f;padding: 8px 12px;border: 1px solid transparent}

        /* 左侧 */
    .memberWrap_bottom{overflow:hidden;margin:10px 0;padding:10px}
    .memberWrap_left{background:#fff;width:200px;padding:0 0 264px;float:left}
    .memberWrap_left .top{position:relative;border-radius:10px 10px 0 0 !important;padding:15px 0 55px;margin-bottom:110px}
    .memberWrap_left .top .logo{margin:auto;display:block;width:74px;height:74px;background:url(<?php echo TPL_NAME;?>images/ucenter/user.png) center no-repeat}
    .memberWrap_left .top .top_user{position:absolute;top:55px;width:100%;line-height:25px;z-index:1;text-align:center;color:#767676}
    .memberWrap_left .title{color:#fff;width:75px;height:23px;border:1px solid #fff;border-radius:10px;text-align:center;margin:0 auto}
    .memberWrap_left li{position:relative;background: #fff;height: 50px;line-height: 50px;text-align:right;margin-bottom:10px;border-radius:5px;color: #333;cursor:pointer;padding-right:40px;font-size: 16px;}
    .memberWrap_left li.active,.memberWrap_left li:hover{background: #edf4fd;}
    .memberWrap_left li.active:after,.memberWrap_left li:hover:after{position:absolute;content:'';display:inline-block;width:5px;height:100%;right:0;z-index:1;background: #5da1ea;}
    .memberWrap_left li span{position:absolute;display:inline-block;width:35px;height:30px;background:url("<?php echo TPL_NAME;?>images/ucenter/user_left_icon.png");float:left;margin: 7px -50px;}
    .memberWrap_left li span.icon_l_ck{background-position: -35px 0;}
    .memberWrap_left li span.icon_l_qk{background-position: -35px -40px;}
    .memberWrap_left li span.icon_l_zz{background-position: -35px -80px;}
    .memberWrap_left li span.icon_l_gg{background-position: -35px -120px;}
    .memberWrap_left li span.icon_l_tz{background-position: -35px -160px;}
    .memberWrap_left li span.icon_l_zl{background-position: -35px -200px;}
    .memberWrap_left li span.icon_l_tc{background-position: -35px -240px;}
    .memberWrap_left li i{visibility: hidden;}
    .memberWrap_right{float: right;}

</style>
<div class="bg_center">
    <div class="w_1200">
    <!-- 中间部分 -->
    <div class="memberWrap_bottom">
        <!-- 左侧 -->
        <div class="memberWrap_left border_shadow">
            <div class="top btn_game">
                <p class="title">了解详情</p>
                <div class="top_user">
                    <span class="logo"></span>
                    <p class="color_7387e8"> <?php echo $username;?> </p>
                    <p> <?php echo $onlinetime;?> </p>
                </div>
            </div>

            <ul class="memberUserLeft">
                <li class="to_deposit <?php echo $type=='deposit'?'active':'';?>">
                    <span class="icon_l_ck"></span> 存款<i>占位</i>
                </li>
                <li class="to_withdraw <?php echo $type=='withdraw'?'active':'';?>">
                    <span class="icon_l_qk"></span> 提款<i>占位</i>
                </li>
                <li class="to_platform_tranfer <?php echo $type=='tranfer'?'active':'';?>">
                    <span class="icon_l_zz"></span> 额度转换
                </li>
                <li class="to_user_email <?php echo $type=='email'?'active':'';?>">
                    <span class="icon_l_gg"></span> 消息中心
                </li>
                <li class="to_userbetaccount <?php echo $type=='userbetaccount'?'active':'';?>">
                    <span class="icon_l_tz"></span> 账户记录
                </li>
                <li class="to_usercenter <?php echo $type=='usercenter'?'active':'';?>">
                    <span class="icon_l_zl"></span> 我的账户
                </li>
                <li class="" onclick="window.location.href='/app/member/logout.php'">
                    <span class="icon_l_tc"></span> 退出后台
                </li>
            </ul>

        </div>

        <!-- 右侧 -->
        <div class="middle_usercenter_content memberWrap_right">

        </div>

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