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
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">

<body class="bodyset FTR body_browse_set">
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
                    <span id="showNull" title="无资料" class="bet_star_btn_out fav_null" style="display: inline-block;">
            <tt class="bet_star_text">
               0
            </tt>
        </span>

        <span id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display:none;" class="bet_star_btn_all fav_out">
            <tt class="bet_star_All">全部</tt>
            <tt id="live_num_all" class="bet_star_text" style="display: none;"> </tt>
        </span>
        <span id="showMy" title="我的最爱" onclick="showMyLove('FT');" style="display:none;" class="bet_star_btn_on">
            <!--我的最爱场数-->
            <tt id="live_num" class="bet_star_text"></tt>
        </span>

        <!-- 选择联赛 -->
        <span id="sel_league"  class="bet_league_btn">
            <tt class="bet_normal_text">
               选择联赛 (<tt id="str_num" class="bet_yellow">全部</tt>)
            </tt>
        </span>

        <span id="sel_Market" class="bet_view_btn" ><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
        <span id="sel_filters" class="bet_Special_btn" ><tt id="SpanFilter" class="bet_normal_text">隐藏赛盘投注</tt></span>
        <span id="show_pg_chk" style="display:none;" class="bet_paging"><label><input id="pg_chk"  type="checkbox" class="bet_selsect_box" value="C"><span></span><span class="bet_more_chk">分页</span></label></span>

    </div>

    <!--右侧按钮-->
    <div class="bet_right">
        <!--<span id="pg_txt" class="bet_page_btn" style="display:none;">
       </span>-->
        <span id="sel_sort" class="bet_sort_time_btn"><tt class="bet_sort_text">排序</tt>
             <div id="show_sort"  class="bet_sort_bg" style="display:none;" tabindex="100">
                <span class="bet_arrow"></span>
                <span class="bet_arrow_text">赛事排序</span>
                <ul id="SortSel" selvalue="T">
                <li id="sort_time" onclick="chgSortValue('T');" class="bet_sort_time_choose">按时间排序</li>
                <li id="sort_leg" onclick="chgSortValue('C');" class="bet_sort_comp">按联盟排序</li>
                </ul>
             </div>
         </span>

        <!--盘口选择 -->
        <span id="sel_odd" class="bet_odds_btn"><tt id="chose_odd" class="bet_normal_text">香港盘</tt>
 <div id="show_odd" onmouseleave="hideDiv(this.id);" class="bet_odds_bg" style="display: none;" tabindex="100">
 <span class="bet_arrow"></span>
<span class="bet_arrow_text">盘口类型</span>
<ul id="myoddType" selvalue="H" seltext="香港盘">
<li id="odd_H" value="H" class="bet_odds_contant" selected="">香港盘</li><li id="odd_M" value="M" class="bet_odds_contant">马来盘</li><li id="odd_I" value="I" class="bet_odds_contant">印尼盘</li><li id="odd_E" value="E" class="bet_odds_contant">欧洲盘</li></ul>
</div></span>
        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text">刷新</tt>
        </span>

    </div>

</div>

<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table">
    <tbody><tr><td>
            <table border="0" cellpadding="0" cellspacing="0" id="box">

                <tbody><tr>
                    <td class="mem">

                        <!--     资料显示的layer     -->
                        <div id="showtable">

                            <table class="bet_game_top">
                                <tbody><tr>
                                    <th class="bk_h_1x1"><?php echo $gametit.$gametitle?></th>

                                    <th class="h_1x2">独赢</th>
                                    <th class="h_r">让盘</th>
                                    <th class="h_ou">大小</th>
                                    <th class="h_oe">单双</th>
                                </tr>        </tbody></table>
                            <table id="game_table" cellspacing="0" cellpadding="0" class="game ">


                                <tbody><tr><td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>


                                </tr></tbody></table>

                        </div>
                    </td>
                </tr>

                </tbody></table>

        </td></tr></tbody></table>

<!-- 选择联赛 -->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onmousedown="initializedragie('legView')"></div>

    <div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>

<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
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