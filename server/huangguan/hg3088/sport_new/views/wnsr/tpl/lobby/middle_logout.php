<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];


?>
<link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="../../style/common.css?v=<?php echo AUTOVER; ?>" >
<style>
    /* 退出登录提示 */
    body {background: rgba(0,0,0,.5);}
    .layui-layer-logout{width:500px;height: 200px; margin: 0 auto;padding-top:50px;  }
    .layui-layer-logout .layui-layer-title {background: #575757;color: #fff;text-align: center;font-size: 22px;padding: 5px 20px;border-radius: 20px 20px 0 0;border-bottom: 0;}
    .layui-layer-logout .layui-layer-content {background: #fff;text-align: center;/*border-radius: 0 0 20px 20px;*/}
    .layui-layer-logout .logout_div_all {width: 90%;margin: 0 auto;}
    .layui-layer-logout .logout_div_all .logout_notice a{color: #366aa4;}
    .layui-layer-logout .logout_div_all p {padding: 15px;}
    .layui-layer-logout .logout_bottom {margin-top: 26px;padding-bottom: 26px;}
    .layui-layer-logout .logout_bottom a {background: #e48c09;color: #fff;padding: 2px 10px;margin-right: 30px;border-radius: 8px;}
    .layui-layer-logout .logout_bottom a:last-child{background:#ccc;color:#000 }
</style>
<div class="layui-layer-logout">
    <div class="layui-layer-title">温馨提示</div>
    <div class="layui-layer-content">
        <div class="logout_div_all">
            <p>由于您长时间未做任何操作，系统已自动退出，请您重新登录。</p>
            <!--<div class="logout_notice">注意：如果这不是您本人的操作，那么您的密码很可能已经泄露，请立刻修改密码。如有疑问，请联系<a class="to_livechat" href="javascript:;">在线客服</a>。</div>-->
            <div class="logout_bottom">
                <a href="/" >重新登录</a>
                <a href="javascript:;" class="to_livechat">修改密码</a>
                <a href="javascript:;" onclick="window.location.href='/'">关闭</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="../../js/config.js?v=<?php echo AUTOVER; ?>" ></script> <script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var web_config = JSON.parse(localStorage.getItem('webconfigbase'));
    var configbase={
        onlineserve:web_config.service_meiqia,
    };
    indexCommonObj.addLiveUrl();
</script>