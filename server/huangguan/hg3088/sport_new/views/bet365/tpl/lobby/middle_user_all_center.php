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
    .bg_center{min-height: 885px;background:#272727;padding: 30px 0;}
    .member-title{background:#3b3a3a;height:154px;margin-top:10px}
    .memberWrap .wrap{padding-top:20px}
    .member-title-top{box-sizing:border-box;display:flex;align-items:center;width:100%;height:96px;padding:10px 40px}
    .head-user-icon img{width:76px}
    .member-title-top .member-center{font-size:24px;color:#ffffff;padding:0 35px}
    .member-title-top .head-money p{font-size:16px;padding:5px 0px;color:#ffffff}
    .member-title-link{padding-left:40px}
    .member-title-link a{border:none;width:120px;height:35px;line-height:35px;text-align:center;-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;font-size:14px;background:#811f1d;color:#ffffff;display:inline-block}
    .member-title-link a.active{background:#f00b07}
    .member-center-link{height:70px;margin:4px 0px;border-top:1px solid #c70300;background:#313131}
    .member-center-link ul li{float:left;width:14.28%}
    .member-center-link ul li.active a{color:#a0a0a0;background:#242424}
    .member-center-link ul li a{display:block;height:70px;font-size:18px;color:#ffffff;text-align:center;line-height:70px;position:relative}
    .member-center-link ul li a:after{content:'';width:3px;background:#404040;height:100%;position:absolute;top:0;right:0}
    .head-btn{margin-left:40px}
    .head-btn .btn{background: #d9534f;padding: 8px 12px;border: 1px solid transparent}

        /* 左侧 */
    .memberWrap_bottom {background: #ECE8E9;overflow: hidden;margin: 8px 0 0;}
    .memberWrap_left{border-radius:5px;width:184px;padding:20px 8px;float:left}
    .memberWrap_left p{font-size:20px;font-weight:700;color:#fff;padding:0 10px 10px}
    .memberWrap_left li{position:relative;background: #fff;height: 50px;line-height: 50px;text-align:right;margin-bottom:10px;border-radius:5px;color: #333;cursor:pointer;padding-right:40px;font-size: 16px;}
    .memberWrap_left li.active,.memberWrap_left li:hover{color:#fff;background: #7a7a7a;background: linear-gradient(to right, #7a7a7a, #898787);box-shadow: 4px 6px 8px #888;}
    .memberWrap_left li.active:after,.memberWrap_left li:hover:after{content:'';width:0;height:0;border-top:8px solid transparent;border-left:8px solid rgb(137,135,135);border-bottom:8px solid transparent;position:absolute;top:50%;transform:translateY(-50%);right:-8px;z-index:1}
    .memberWrap_left li span{position:absolute;display:inline-block;width:25px;height:25px;background:url("<?php echo TPL_NAME;?>images/user_left_icon.png");float:left;margin: 12px -50px;}
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
<div class="bg_center">
    <div class="w_1200">
    <!-- 顶部 -->
    <div class="memberWrap_top">
        <div class="member-title">
            <div class="member-title-top">
                <div class="head-user-icon">
                    <img src="<?php echo TPL_NAME;?>images/user_icon.png" alt="">
                </div>
                <div class="member-center">会员中心</div>
                <div class="head-money">
                    <p>欢迎回来：<?php echo $username;?></p>
                    <p>余额: ￥ <span class="user_member_amount"> 加载中... </span></p>
                </div>
                <div class="head-btn">
                    <a href="/app/member/logout.php" class="btn btn-danger">退出</a>
                </div>

            </div>
            <div class="member-title-link">
                <a href="javascript:;" class="to_deposit <?php echo $type=='deposit'?'active':'';?>">在线存款</a>
                <a href="javascript:;" class="to_withdraw <?php echo $type=='withdraw'?'active':'';?>">在线取款</a>
                <a href="javascript:;" class="to_platform_tranfer <?php echo $type=='tranfer'?'active':'';?>">额度转换</a>
                <a href="javascript:;" class="to_userbetaccount <?php echo $type=='userbetaccount'?'active':'';?>">资金流水</a>
               <!-- <a href="javascript:;">我的推广</a>-->
            </div>
        </div>

        <div class="member-center-link">
            <ul class="clearfix">
                <li class="<?php echo $type=='usercenter'?'active':'';?>"><a href="javascript:;" class="to_usercenter ">会员资料</a></li>
                <li class="<?php echo $type=='deposit'?'active':'';?>"><a href="javascript:;" class="to_deposit">在线存款</a></li>
                <li class="<?php echo $type=='withdraw'?'active':'';?>"><a href="javascript:;" class="to_withdraw">在线取款</a></li>
                <li class="<?php echo $type=='tranfer'?'active':'';?>"><a href="javascript:;" class="to_platform_tranfer">额度转换</a></li>
                <li class="<?php echo $type=='userbetaccount'?'active':'';?>"><a href="javascript:;" class="to_userbetaccount ">注单查询</a></li>
                <li class="<?php echo $type=='userbetaccount'?'active':'';?>"><a href="javascript:;" class="to_userbetaccount">资金流水</a></li>
                <li class="<?php echo $type=='email'?'active':'';?>"><a href="javascript:;" class="to_user_email ">消息中心</a></li>
            </ul>
        </div>

    </div>

    <!-- 中间部分 -->
    <div class="memberWrap_bottom">
        <!-- 左侧 -->
        <div class="memberWrap_left ">
            <!--<p class="title">财务中心</p>-->
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

           <!-- <p class="title">个人中心</p>-->
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