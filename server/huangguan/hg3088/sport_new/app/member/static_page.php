<?php
require ("include/config.inc.php");

// 判断今日赛事是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$gamename = $_REQUEST['gamename'] ;
$showtype = $_REQUEST['showtype'] ;
switch ($gamename){
    case 'TN':
        $gametitle='网球';
        break;
    case 'VB':
        $gametitle='排球';
        break;
    case 'BS':
        $gametitle='棒球';
        break;
    case 'OP':
        $gametitle='其他';
        break;
}
switch ($showtype){
    case 'today':
    case '':
        $gametit='今日';
        break;
    case 'future':
        $gametit='早盘';
        break;
    case 'rb':
        $gametit='滚球';
        break;

}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <link rel="stylesheet" href="../../style/member/mem_body_ft.css" type="text/css" media="screen">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">

<body class="bodyset FTR body_browse_set bodyset_browse_<?php echo TPL_FILE_NAME;?>">
<!-- 右方刷新按钮 -->
<div id="refresh_right" style="position: absolute; left: 755px;" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:reload_var()">
    <span>刷新</span>
</div>
<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tbody><tr><td>
            <table border="0" cellpadding="0" cellspacing="0" id="box">
                <tbody><tr>
                    <td class="top"><h1 class="top_h1"><em><?php echo $gametit.$gametitle; ?></em>
                        </h1><div id="skin" class="zoomChange">字体显示：<a id="skin_0" data-val="1" class="zoom zoomSmaller" href="javascript:;" title="点击切换原始字体">小</a><a id="skin_1" data-val="1.2" class="zoom zoomMed " href="javascript:;" title="点击切换中号字体">中</a><a id="skin_2" data-val="1.35" class="zoom zoomBigger" href="javascript:;" title="点击切换大号字体">大</a></div></td>
                </tr>
                <tr>
                    <td class="mem"><h2>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
                                <tbody><tr>
                                    <td id="page_no"><span id="pg_txt">1 / 1 页&nbsp;&nbsp; <select onchange="chg_pg(this.options[this.selectedIndex].value)" disabled=""><option value="0" selected="">1</option></select></span><div class="search_box">
                                            <input type="text" id="seachtext" placeholder="输入关键字查询" value="<?php echo $leaname;?>" class="select_btn">
                                            <input type="button" id="btnSearch" value="搜索" class="seach_submit" onclick="seaGameList()">
                                        </div></td>
                                    <td id="tool_td">

                                        <table border="0" cellspacing="0" cellpadding="0" class="tool_box">
                                            <tbody><tr>
                                                <td id="fav_btn">
                                                    <div id="fav_num" title="清空" onclick="chkDelAllShowLoveI();" style="display: none;"><!--我的最爱场数--><span id="live_num"></span></div>
                                                    <div id="showNull" title="无资料" class="fav_null" style="display: block;"></div>
                                                    <div id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display:none;" class="fav_on"></div>
                                                    <div id="showMy" title="我的最爱" onclick="showMyLove('FT');" class="fav_out" style="display: none;"></div>
                                                </td>
                                                <td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';">
                                                    <!--秒数更新-->
                                                    <div onclick="javascript:reload_var()"><font id="refreshTime">180</font></div>
                                                </td>
                                                <td class="leg_btn"><div id="sel_league">选择联赛 (<span id="str_num">全部</span>)</div></td>
                                                <td class="OrderType" id="Ordertype"><select id="myoddType" onchange="chg_odd_type()"><option value="H" selected="">香港盘</option></select></td>
                                            </tr>
                                            </tbody></table>

                                    </td>
                                </tr>
                                </tbody></table>
                        </h2>
                        <!--     资料显示的layer     -->
                        <div id="showtable">

                            <table id="game_table" cellspacing="0" cellpadding="0" class="game">
                                <tbody><tr>
                                    <th class="time">时间</th>
                                    <th class="team">赛事</th>
                                    <th class="h_1x2">独赢</th>
                                    <th class="h_r">让盘</th>
                                    <th class="h_ou">大小</th>
                                    <th class="h_oe">单双</th>
                                </tr>

                                <tr><td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>


                                </tr></tbody></table>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="foot"><b>&nbsp;</b></td>
                </tr>
                </tbody></table>



        </td></tr></tbody></table>

<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" class="language_choose" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    parent.static_retime=180 ;
    static_count_down() ;
    // 刷新页面
    function reload_var() {
        parent.body_browse.location.reload() ;
    }
    //倒數自動更新時間
    function static_count_down(){
        var rt=document.getElementById('refreshTime');
        setTimeout('static_count_down()',1000);
            if(parent.static_retime <= 0){
                reload_var();
                return;
            }
            parent.static_retime--;
            rt.innerHTML=parent.static_retime;
    }
</script>

</body>
</html>