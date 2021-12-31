<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");
include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$id=$_REQUEST["id"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require("../../agents/include/traditional.$langx.inc.php");

$agenturl = explode(';',$registeredAgent);
$fetch_num = array_rand($agenturl,1);
$afterurl = $agenturl[$fetch_num]; // 随机取一个配置的域名

?>
<html>
<head>
<title>代理推广</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body >

    <dl class="main-nav">
        <dt>推广网址</dt>
        <dd>
        </dd>
    </dl>
<div class="main-ui">
    <div class="spread-ui">
        <p>
            <input type="text" value="<?php echo $afterurl.'?intr='.$loginname ;?>" id="url" size="60" class="spread_url inp-txt2">&nbsp;
            <input type="button" onclick="copyUrl2()" value="复制" class="za_button button_110"></p>
        <h1>请从下列网址选择提交，我们会尽快为您开通</h1>
        <p></p>
        <table class="list-tab">
            <thead>
            <tr><td>专用域名</td><td>操作</td><td>专用域名</td><td>操作</td><td>专用域名</td><td>操作</td></tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <br>&nbsp;
        <p></p>
        <div class="tips" style="display:none;">推广域名已提交，请耐心等待客服人员处理 ！</div>
    </div>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/user_search.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var username = '<?php echo $loginname?>' ;

    // 代理推广链接
    var urlStr = '<?php echo $ulrarr?>';
    var urlArray = urlStr.split(',') ;
    var urllen = urlArray.length ;
    var num = Math.floor(Math.random() * urllen) ; // 随机生成整数

</script>

</body>
</html>
