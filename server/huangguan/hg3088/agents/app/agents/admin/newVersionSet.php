<?php

session_start();
require_once '../include/config.inc.php';
include_once ("../include/address.mem.php");
require_once '../include/redis.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$actype = isset($_REQUEST["actype"])? $_REQUEST["actype"] : '';
$level=$_REQUEST['level'];

$newurl = isset($_REQUEST["newurl"]) && $_REQUEST["newurl"] ? $_REQUEST["newurl"] : ''; // 域名地址
$oldurl = isset($_REQUEST["oldurl"]) && $_REQUEST["oldurl"] ? $_REQUEST["oldurl"] : ''; // 域名地址
$http_url = isset($_REQUEST["http_url"]) && $_REQUEST["http_url"] ? $_REQUEST["http_url"] : ''; // http 域名
$ts_http_url = isset($_REQUEST["ts_http_url"]) && $_REQUEST["ts_http_url"] ? $_REQUEST["ts_http_url"] : ''; // 推送 http 域名
$ts_https_url = isset($_REQUEST["ts_https_url"]) && $_REQUEST["ts_https_url"] ? $_REQUEST["ts_https_url"] : ''; // 推送 https 域名

$redisObj = new Ciredis();
if($actype=='version'){ // 新旧版配置
    $data = array(
        'newurl'=>$newurl,
        'oldurl'=>$oldurl,
    );

    if($newurl || $oldurl){
        $redisObj->setOne('new_version_set',json_encode($data)) ;
    }
}else if($actype=='urlset'){ // http 域名配置
    $data = array(
        'http_url'=>$http_url,
        'ts_http_url'=>$ts_http_url,
        'ts_https_url'=>$ts_https_url,
    );

    if($http_url || $ts_http_url || $ts_https_url){
        $redisObj->setOne('http_ts_url',json_encode($data)) ;
    }
}
$datajson = $redisObj->getSimpleOne('new_version_set'); // 取redis 设置的值
$datajson_url = $redisObj->getSimpleOne('http_ts_url'); // 取redis 设置的值
$datastr = json_decode($datajson,true) ;
$datastr_url = json_decode($datajson_url,true) ;


?>
<html>
<head>
    <title>新旧版域名切换</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        .m_title td input.za_text {width: 80%;}
        .m_title td.td_sec input.za_text {width: 40%;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>新旧版域名切换</dt>
    <dd>

    </dd>
</dl>

<div class="main-ui ">
    <div class="width_1300">
        <form action="newVersionSet.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $level?>&actype=urlset"  method="post" name="form1" >
            <table class="m_tab">
                <tr class="m_title">
                    <td>非https域名配置(多个域名之间以英文,符号分割)，如 hg123.com,hg234.com，有推送功能，后台域名也需要加上 </td>
                    <td>推送域名配置(测试 ra44448.com，暂时支持一个，http,https 各配置一个)</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td> <input style="width: 90%" class="za_text" type="text" name="http_url" value="<?php echo $datastr_url['http_url'];?>" > </td>
                    <td class="td_sec">
                        <input class="za_text" type="text" name="ts_http_url" value="<?php echo $datastr_url['ts_http_url'];?>" placeholder="http 推送域名,00863333.com ,6668111333.com">
                        <input class="za_text" type="text" name="ts_https_url" value="<?php echo $datastr_url['ts_https_url'];?>" placeholder="https 推送域名,00862222.com ,6668111222.com">
                    </td>
                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>
<br>
        <form action="newVersionSet.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $level?>&actype=version"  method="post" name="form1" >
            <table class="m_tab">
                <tr class="m_title">
                    <td>新版切换旧版域名配置(多个域名之间以英文;符号分割)，需要带上 http / https</td>
                    <td>旧版切换新版域名配置(多个域名之间以英文;符号分割)，需要带上 http / https</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td> <input class="za_text" type="text" name="newurl" value="<?php echo $datastr['newurl'];?>" > </td>
                    <td> <input class="za_text" type="text" name="oldurl" value="<?php echo $datastr['oldurl'];?>" > </td>
                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>
    </div>
</div>
</body>

</html>




