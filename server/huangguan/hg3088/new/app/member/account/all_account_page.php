<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$chk_cw=$_REQUEST['chk_cw'];
$wintype =$_REQUEST['wintype'] ;// 当前是哪个页面
require ("../include/traditional.$langx.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

?>

<html class="zh-cn"><head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="../../../style/my_account.css?v=<?php echo AUTOVER; ?>">
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>">

</head>
<body>

<script src="../../../js/jquery.js"></script>

<body  class="index_accBody"> <!-- onload="init();" -->
<table id="ACC_W" class="index_accMainTB" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <!-- 左侧公用 -->
        <td class="index_accTD_left">
            <table cellspacing="0" cellpadding="0" class="acc_TB_main">
                <tbody><tr>
                    <!--左边选单区-->
                    <td><div class="acc_right_menuDIV">
                            <h1>我的帐户</h1>
                            <ul>
                                <li id="account" onclick="goToPage('account',this);" class="">帐户历史</li>
                                <li id="openbets" onclick="goToPage('openbets',this);" class="">交易状况</li>
                                <!--<li id="mem_conf" onclick=" ;" class="">详细设定</li>-->
                                <!--<li id="set_email" onclick=" ;" class="">密码恢复</li>-->
                                <?php if($_SESSION['Agents']!='demoguest'){?>
                                <li id="changepsw" onclick="goToPage('changepsw',this);" class="">更改密码</li>
                                <?php } ?>
                                <li id="morescroll" onclick="goToPage('morescroll',this);" class="">公告栏</li>
                            </ul>
                            <h1>帮助</h1>
                            <ul>
                                <li id="sportsrule" onclick="goToPage('sportsrule',this);" class="">体育规则</li>
                                <li id="sportsroul" onclick="goToPage('sportsroul',this);" class="">规则与条款</li>
                                <li id="gameresult" onclick="goToPage('gameresult',this);" >赛果</li>
                                <li id="sportsmap" onclick="goToPage('sportsmap',this);" class="">指南</li>
                                <!--<li id="new_features" onclick="goToPage('sportsrule',this);" class="">新功能</li>-->
                                <li id="sportsway" onclick="goToPage('sportsway',this);" class="">赔率计算列表</li>
                                <li id="sportsconn" onclick="goToPage('sportsconn',this);" class="">联系我们</li>
                                <li id="troubleshooting" onclick=" ;" class="">故障疑难排解</li>
                            </ul>
                        </div></td>

                </tr>
                </tbody></table>
        </td>
        <!-- 右侧 -->
        <td class="index_accTD_right">
            <div id="body_view" name="body_view" style="display:;width:100%;height:100%;">
                <iframe id="body_right" name="body_right" width="100%" height="100%" frameborder="0" src=""></iframe>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<script type="text/javascript">
    var uid ='<?php echo $uid?>' ;
    var langx ='<?php echo $langx?>' ;
    var wintype='<?php echo $wintype?>' ;
    $('#'+wintype).addClass('on') ; // 初始化
    goToPage(wintype) ;

    // 页面跳转
    function goToPage(par,obj) {
        var url='';
        switch (par){
            case 'openbets': // 交易状况
                url = "../today/today_wagers.php?langx="+langx+"&uid="+uid ;
                break;
            case 'account': // 帐户历史
                url = "../history/history_data.php?langx="+langx+"&uid="+uid ;
                break;
            case 'gameresult': // 赛果
                url='result/result.php?game_type=FT&uid='+uid+'&langx='+langx ;
                break;
            case 'changepsw': // 更改密码
                url='../account/chg_passwd.php?game_type=FT&uid='+uid+'&langx='+langx ;
                break;
            case 'morescroll': // 更多公告
                url='../scroll_history.php?select_date=all&uid='+uid+'&langx='+langx ;
                break;
            case 'sportsrule': // 体育规则
                url='/tpl/QA_sport.html' ;
                break;
            case 'sportsroul': // 规则与条款
                url='/tpl/QA_roul.html' ;
                break;
            case 'sportsmap': // 指南
                url='/tpl/roul_mp.html' ;
                break;
            case 'sportsway': // 赔率计算列表
                url='/tpl/QA_way.html' ;
                break;
            case 'sportsconn': // 联系我们
                url='/tpl/QA_conn.html' ;
                break;
        }
        $(obj).addClass('on').siblings().removeClass('on');
        $(obj).parent('ul').siblings().find('li').removeClass('on');

       frames.body_right.location.href = url ;
    }

    // 右侧公用选择类型
    function showOption(_type){
        var _otherType = (_type == "gtype")?"type":"gtype";
        var _status = body_right.document.getElementById("chose_"+_type).style.display;
        var _newStatus = (_status=="")?"none":"";
        body_right.document.getElementById("chose_"+_type).style.display = _newStatus;
        top.showResultObj=new Object();
        if(_newStatus == ""){
            try {
                body_right.document.getElementById("chose_"+_otherType).style.display = "none";
            }catch (e){}

        }
    }

    // 右侧公用选择游戏类型
    function changeGameType(obj) {
        body_right.$('[name="game_type"]').val($(obj).data('value'));
        body_right.$('#sel_gtype').text($(obj).text());
        body_right.$('#chose_gtype').hide() ;
        body_right.$('.seach_btn').click() ;
    }
    // 公用刷新
    function reload_var() {
        location.reload() ;
    }

</script>
</body>
</html>