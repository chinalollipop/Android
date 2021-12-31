<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

// 判断今日赛事是否维护-单页面维护功能
checkMaintain('game');

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'ag'; // ag 默认 mg
$pam_username = $_REQUEST['username'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$Status=$_SESSION['Status'];
if ($Status==1){
    exit;
}

if ($cou > 0){

    // 更新登录次数、最后登录时间
    $ag_sql = "select userid from `".DBPREFIX."ag_users` where `userid` = '{$_SESSION['userid']}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);

    $ag_cou = mysqli_num_rows($ag_result);
    if ($ag_cou > 0){
        $last_launch_time = date('Y-m-d H:i:s');
        $res = mysqli_query($dbMasterLink, "update ".DBPREFIX."ag_users set launch_number = launch_number + 1, last_launch_time = '{$last_launch_time}'  WHERE userid = '{$_SESSION['userid']}'");
        /*if ($res){
            echo "<script>alert('更新登录次数成功')</script>";
        }else{
            echo "<script>alert('更新登录次数失败')</script>";
        }*/
    }
}

?>

<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>电子游艺</title>
    <link type="text/css" rel="stylesheet" href="../../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../../style/member/mg_games.css?v=<?php echo AUTOVER; ?>">

    <style>
        /* jquerybox */
        .game td, .more td {color: #c67777;}
        div.jbox .jbox-title-icon {background: url(/images/jquerybox/game.png) 90px 0 no-repeat;}
        div.jbox .jbox-button{background: url(/images/jquerybox/game_btn.png) no-repeat;background-size: 100%;}
        .game .tran_logo{background: url(/images/jquerybox/game_change.png) 5px 8px no-repeat;}
        div.jbox .jbox-title-panel {background: #b30000;}
        div.jbox .jbox-close, div.jbox .jbox-close-hover {background: url(/images/jquerybox/game_close.png) 0 -1px no-repeat;background-size: 100%;}
        .toppt{width: 100%;}
        .topm .nav{width: 300px;}
        .topm>div{display: inline-block;margin-top: 25px;}
        .topm .searchgame {margin-top: 40px;float: right;}
        .topm .searchgame span{font-size: 20px;}
        .searchgame div{display:inline-block;background:#d70000;color:#fff;padding:3px 18px;cursor:pointer;border-radius:5px;transition: .3s;}
        .searchgame div:hover {opacity: .8;}
        .searchgame input{padding:4px 5px;font-size: 14px;}
        .kf_88 ul li a {color: #000;}
        .kf_88 ul li a.active {color: #d90000;}
    </style>
</head>

<body class="page-slot page-slotb">
<div id="refresh_right" style="position:absolute;" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:refreshReload()"><span></span></div>
<dl class="ui-header">
    <dt></dt>
    <dd>
        <div class="toppt">
            <div class="topm">
                <div class="nav">
                    <ul class="tab-choose-game">
                        <li><a href="javascript:;" class="<?php echo $type=='ag'?'hover':'' ;?>" data-val="ag">AG首页</a></li>
                        <li><a href="javascript:;" class="<?php echo $type=='mg'?'hover':'' ;?>" data-val="mg">MG首页</a></li>
                        <!--<li><a href="javascript:;" data-val="pt">PT首页</a></li>-->
                        <!-- <li><a href="/pt/pt_yh.php?uid=--><!--">优惠专区</a></li>-->
                    </ul>
                </div>
                <div class="searchgame">
                    <span>游戏搜索</span>
                    <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="搜索游戏">
                    <div class="submit-btn">确认</div>
                </div>

            </div>
        </div>
    </dd>
</dl>
<!-- pt 独有 开始-->
<div class="noticeyy pt-self"></div>
<div class="jchi pt-self">
    <div class="pt-update" id="allptt">updating...</div>
</div>

<!-- pt 独有 结束-->

<div class="main" style="width:1210px">
    <div class="mainc"></div>
    <div class="mainm">
        <div class="cs_left">
            <div class="kf_88 left_ed" >
                <div class="ed_left ag_show" style="display:<?php echo $type=='ag'?'block':'none' ;?>">
                    <a href="javascript:void(0)" onclick="jb('ag');" style="float:right;">额度转换</a>
                    <span id="agmoney"></span>
                </div>
                <div class="ed_left mg_show" style="display:<?php echo $type=='mg'?'block':'none' ;?>" >
                    <a href="javascript:void(0)" onclick="jb('mg');" style="float:right;">额度转换</a>
                    <span id="mgmoney" ></span>
                </div>

                <ul style="margin-top:-62px;"><li></li> </ul>
            </div>
            <div class="kf_88 yxfl">
                <!-- 左侧选单 -->
                <ul class="ui-tabs-nav">

                </ul>
            </div>
            <div class="kf_88">
                <ul>
                    <li><img src="../../images/game/kf_ico.png" width="28" height="30" align="absmiddle">
                        <a href="https://static.meiqia.com/dist/standalone.html?_=t&eid=106062" target="_blank">客服中心</a>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <div class="cs_right" style="width:910px;">
            <!-- pt 独有-->
            <div class="pt-self ">
                <div class="slot_box_big">
                    <div class="slot_b1_big" onclick="open_game('./GameHall.php?name=fm&uid=<?php echo $uid ?>')"><img src="../../images/game/pt/funky_monkey.jpg" width="270" height="150" border="0"></div>
                    <div class="slot_b2_big">
                        <!-- <div class="slot_jc" id="b_fm" style="visibility:hidden">UPDATING</div>-->
                        <div class="slot_kk"> <span class="slot_tt">
              古怪猴子              </span> <span class="slot_bb"> <a class="ks_slots2" onclick="open_game('./GameHall.php?name=fm&uid=<?php echo $uid ?>')">真钱模式</a> <a class="ks_slots2" style="background:none;color: #000" onclick="open_game('./GameHall.php?name=fm&free=1&uid=<?php echo $uid ?>')">试玩模式</a> </span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="slot_box_big">
                    <div class="slot_b1_big" onclick="open_game('./GameHall.php?name=hk&uid=<?php echo $uid ?>')"><img src="../../images/game/pt/high_way.jpg" width="270" height="150" border="0"></div>
                    <div class="slot_b2_big">
                        <!--<div class="slot_jc" id="b_hk" style="visibility:hidden">UPDATING</div>-->
                        <div class="slot_kk"> <span class="slot_tt">
              高速公路之王              </span> <span class="slot_bb"> <a class="ks_slots2" onclick="open_game('./GameHall.php?name=hk&uid=<?php echo $uid ?>')">真钱模式</a> <a class="ks_slots2" style="background:none;color: #000" onclick="open_game('./GameHall.php?name=hk&free=1&uid=<?php echo $uid ?>')">试玩模式</a> </span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="slot_box_big">
                    <div class="slot_b1_big" onclick="open_game('./GameHall.php?name=ct&uid=<?php echo $uid ?>')"><img src="../../images/game/pt/captains-treasure.jpg" width="270" height="150" border="0"></div>
                    <div class="slot_b2_big">
                        <!--<div class="slot_jc" id="b_ct" style="visibility:hidden">UPDATING</div>-->
                        <div class="slot_kk"> <span class="slot_tt">
              船长的宝藏              </span> <span class="slot_bb"> <a class="ks_slots2" onclick="open_game('./GameHall.php?name=ct&uid=<?php echo $uid ?>')">真钱模式</a>
                                <a class="ks_slots2" style="background:none;color: #000" onclick="open_game('./GameHall.php?name=ct&free=1&uid=<?php echo $uid ?>')">试玩模式</a> </span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="slot_hen"></div>
            </div>
            <!-- pt 独有-->
            <div>
                <div id="main">
                    <!--  游戏内容列表 -->
                    <div id="holder" class="mg-main" style="min-height: 600px;">

                    </div>

                    <!-- 分页 -->
                    <div class="swControls">

                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>


    <div class="mainb"></div>
</div>


<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../../js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript">

    get_balance();
    mg_blance();
    var uid = <?php echo '\''.$uid.'\'' ?> ;
    var g_type = '<?php echo $type ;?>' ;

    function get_balance(){
        $('#agmoney').html('加载中');
        var dat={};
        dat.uid='<?php echo $uid;?>';
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'zrsx/ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){

                    $('#agmoney').html(ret.balance_ag);
                }
                else{
                    $('#agmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    // ag 游戏列表
    var aglist =[
        {
            name:'水果拉霸' ,
            gameurl:'../../images/member/2018/game/ag/FRU_ZH.png',
            gameid:'101',
            type:'all',

        },
        {
            name:'水果拉霸2' ,
            gameurl:'../../images/member/2018/game/ag/FRU2_ZH.png',
            gameid:'146',
            type:'slot',
        },
        {
            name:'杰克高手' ,
            gameurl:'../../images/member/2018/game/ag/PKBJ_ZH.png',
            gameid:'102',
            type:'video',
        },
        /*{
            name:'美女沙排' ,
            gameurl:'../../images/member/2018/game/ag/SB10_ZH.png',
            gameid:'103',
        },
        {
            name:'运财羊' ,
            gameurl:'../../images/member/2018/game/ag/SB10_ZH.png',
            gameid:'104',
        },
        {
            name:'武圣传' ,
            gameurl:'../../images/member/2018/game/ag/SB10_ZH.png',
            gameid:'105',
        },*/
        {
            name:'极速幸运轮' ,
            gameurl:'../../images/member/2018/game/ag/TGLW_ZH.png',
            gameid:'107',
            type:'table',
        },
        {
            name:'太空漫游' ,
            gameurl:'../../images/member/2018/game/ag/SB01_ZH.png',
            gameid:'108',
            type:'slot',
        },
        {
            name:'复古花园' ,
            gameurl:'../../images/member/2018/game/ag/SB02_ZH.png',
            gameid:'109',
            type:'slot',
        },
        {
            name:'关东煮' ,
            gameurl:'../../images/member/2018/game/ag/SB03_ZH.png',
            gameid:'110',
            type:'slot',
        },
        {
            name:'牧场咖啡' ,
            gameurl:'../../images/member/2018/game/ag/SB04_ZH.png',
            gameid:'111',
            type:'slot',
        },
        {
            name:'甜一甜屋' ,
            gameurl:'../../images/member/2018/game/ag/SB05_ZH.png',
            gameid:'112',
            type:'slot',
        },
        {
            name:'日本武士' ,
            gameurl:'../../images/member/2018/game/ag/SB06_ZH.png',
            gameid:'113',
            type:'slot',
        },
        {
            name:'象棋老虎机' ,
            gameurl:'../../images/member/2018/game/ag/SB07_ZH.png',
            gameid:'114',
            type:'slot',
        },
        {
            name:'麻将老虎机' ,
            gameurl:'../../images/member/2018/game/ag/SB08_ZH.png',
            gameid:'115',
            type:'slot',
        },
        {
            name:'西洋棋老虎机' ,
            gameurl:'../../images/member/2018/game/ag/SB09_ZH.png',
            gameid:'116',
            type:'slot',
        },
        {
            name:'开心农场' ,
            gameurl:'../../images/member/2018/game/ag/SB10_ZH.png',
            gameid:'117',
            type:'slot',
        },
        {
            name:'夏日营地' ,
            gameurl:'../../images/member/2018/game/ag/SB11_ZH.png',
            gameid:'118',
            type:'slot',
        },
        {
            name:'海底漫游' ,
            gameurl:'../../images/member/2018/game/ag/SB12_ZH.png',
            gameid:'119',
            type:'slot',
        },
        {
            name:'鬼马小丑' ,
            gameurl:'../../images/member/2018/game/ag/SB13_ZH.png',
            gameid:'120',
            type:'slot',
        },
        {
            name:'机动乐园' ,
            gameurl:'../../images/member/2018/game/ag/SB14_ZH.png',
            gameid:'121',
            type:'slot',
        },
        {
            name:'惊吓鬼屋' ,
            gameurl:'../../images/member/2018/game/ag/SB15_ZH.png',
            gameid:'122',
            type:'slot',
        },
        {
            name:'疯狂马戏团' ,
            gameurl:'../../images/member/2018/game/ag/SB16_ZH.png',
            gameid:'123',
            type:'slot',
        },
        {
            name:'海洋剧场' ,
            gameurl:'../../images/member/2018/game/ag/SB17_ZH.png',
            gameid:'124',
            type:'slot',
        },
        {
            name:'水上乐园' ,
            gameurl:'../../images/member/2018/game/ag/SB18_ZH.png',
            gameid:'125',
            type:'slot',
        },
        {
            name:'空中战争' ,
            gameurl:'../../images/member/2018/game/ag/SB19_ZH.png',
            gameid:'126',
            type:'all',
        },
        {
            name:'摇滚狂迷' ,
            gameurl:'../../images/member/2018/game/ag/SB20_ZH.png',
            gameid:'127',
            type:'slot',
        },
        {
            name:'越野机车' ,
            gameurl:'../../images/member/2018/game/ag/SB21_ZH.png',
            gameid:'128',
            type:'slot',
        },
        {
            name:'埃及奥秘' ,
            gameurl:'../../images/member/2018/game/ag/SB22_ZH.png',
            gameid:'129',
            type:'slot',
        },
        {
            name:'欢乐时光' ,
            gameurl:'../../images/member/2018/game/ag/SB23_ZH.png',
            gameid:'130',
            type:'slot',
        },
        {
            name:'侏罗纪' ,
            gameurl:'../../images/member/2018/game/ag/SB24_ZH.png',
            gameid:'131',
            type:'slot',
        },
        {
            name:'土地神' ,
            gameurl:'../../images/member/2018/game/ag/SB25_ZH.png',
            gameid:'132',
            type:'slot',
        },
        {
            name:'布袋和尚' ,
            gameurl:'../../images/member/2018/game/ag/SB26_ZH.png',
            gameid:'133',
            type:'slot',
        },
        {
            name:'正财神' ,
            gameurl:'../../images/member/2018/game/ag/SB27_ZH.png',
            gameid:'134',
            type:'slot',
        },
        {
            name:'武财神' ,
            gameurl:'../../images/member/2018/game/ag/SB28_ZH.png',
            gameid:'135',
            type:'slot',
        },
        {
            name:'偏财神' ,
            gameurl:'../../images/member/2018/game/ag/SB29_ZH.png',
            gameid:'136',
            type:'slot',
        },
        {
            name:'性感女仆' ,
            gameurl:'../../images/member/2018/game/ag/AV01_ZH.png',
            gameid:'137',
            type:'slot',
        },
        {
            name:'灵猴献瑞' ,
            gameurl:'../../images/member/2018/game/ag/SB30_ZH.png',
            gameid:'139',
            type:'slot',
        },
        {
            name:'百搭二王' ,
            gameurl:'../../images/member/2018/game/ag/PKBD_ZH.png',
            gameid:'140',
            type:'video',
        },
        {
            name:'红利百搭' ,
            gameurl:'../../images/member/2018/game/ag/PKBB_ZH.png',
            gameid:'141',
            type:'video',
        },
        {
            name:'天空守护者' ,
            gameurl:'../../images/member/2018/game/ag/SB31_ZH.png',
            gameid:'142',
            type:'slot',
        },
        {
            name:'齐天大圣' ,
            gameurl:'../../images/member/2018/game/ag/SB32_ZH.png',
            gameid:'143',
            type:'slot',
        },
        {
            name:'糖果碰碰乐' ,
            gameurl:'../../images/member/2018/game/ag/SB33_ZH.png',
            gameid:'144',
            type:'slot',
        },
        {
            name:'冰河世界' ,
            gameurl:'../../images/member/2018/game/ag/SB34_ZH.png',
            gameid:'145',
            type:'slot',
        },
        {
            name:'欧洲列强争霸' ,
            gameurl:'../../images/member/2018/game/ag/SB35_ZH.png',
            gameid:'147',
            type:'slot',
        },
        {
            name:'捕鱼王者' ,
            gameurl:'../../images/member/2018/game/ag/SB36_ZH.png',
            gameid:'148',
            type:'slot',
        },
        {
            name:'上海百乐门' ,
            gameurl:'../../images/member/2018/game/ag/SB37_ZH.png',
            gameid:'149',
            type:'slot',
        },
        {
            name:'竞技狂热' ,
            gameurl:'../../images/member/2018/game/ag/SB38_ZH.png',
            gameid:'150',
            type:'slot',
        },
        /*{
            name:'龙珠' ,
            gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
            gameid:'200',
            type:'all',
        },
        {
            name:'幸运8' ,
            gameurl:'../../images/member/2018/game/ag/SB38_ZH.png',
            gameid:'201',
            type:'all',
        },*/
        {
            name:'猛龙传奇' ,
            gameurl:'../../images/member/2018/game/ag/SB45_ZH.png',
            gameid:'156',
            type:'slot',
        },
        {
            name:'金龙珠' ,
            gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
            gameid:'160',
            type:'slot',
        },
        {
            name:'XIN哥来了' ,
            gameurl:'../../images/member/2018/game/ag/SB50_ZH.png',
            gameid:'161',
            type:'slot',
        },
        /* {
             name:'龙舟竞渡' ,
             gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
             gameid:'209',
             type:'all',
         },
         {
             name:'中秋佳节' ,
             gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
             gameid:'210',
             type:'all',
         },
         {
             name:'韩风劲舞' ,
             gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
             gameid:'211',
             type:'all',
         },
         {
             name:'美女大格斗' ,
             gameurl:'../../images/member/2018/game/ag/SB49_ZH.png',
             gameid:'212',
             type:'all',
         },*/
        {
            name:'龙凤呈祥' ,
            gameurl:'../../images/member/2018/game/ag/DTAQ_ZH.png',
            gameid:'213',
            type:'slot',
        },
        {
            name:'黄金对垒' ,
            gameurl:'../../images/member/2018/game/ag/DTAQ_ZH.png',
            gameid:'215',
            type:'slot',
        },
        {
            name:'街头烈战' ,
            gameurl:'../../images/member/2018/game/ag/SX02_ZH.png',
            gameid:'221',
            type:'slot',
        },
        {
            name:'金拉霸' ,
            gameurl:'../../images/member/2018/game/ag/SC03_ZH.png',
            gameid:'802',
            type:'slot',
        },
        // {
        //     name:'五行世界' ,
        //     gameurl:'../../images/member/2018/game/ag/DTA8_ZH.png',
        //     gameid:'DTGDTA8',
        // },
        // {
        //     name:'梦幻森林' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAB_ZH.png',
        //     gameid:'DTGDTAB',
        // },
        // {
        //     name:'财神到' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAF_ZH.png',
        //     gameid:'DTGDTAF',
        // },
        // {
        //     name:'新年到' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAG_ZH.png',
        //     gameid:'DTGDTAG',
        // },
        // {
        //     name:'龙凤呈祥' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAQ_ZH.png',
        //     gameid:'DTGDTAQ',
        // },
        // {
        //     name:'福禄寿' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAT_ZH.png',
        //     gameid:'DTGDTAT',
        // },
        // {
        //     name:'英雄荣耀' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAR_ZH.png',
        //     gameid:'DTGDTAR',
        // },
        // {
        //     name:'快乐农庄' ,
        //     gameurl:'../../images/member/2018/game/ag/DTB1_ZH.png',
        //     gameid:'DTGDTB1',
        // },
        // {
        //     name:'封神榜' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAM_ZH.png',
        //     gameid:'DTGDTAM',
        // },
        // {
        //     name:'摇滚之夜' ,
        //     gameurl:'../../images/member/2018/game/ag/DTAZ_ZH.png',
        //     gameid:'DTGDTAZ',
        // },
        // {
        //     name:'赛亚烈战' ,
        //     gameurl:'../../images/member/2018/game/ag/DTA0_ZH.png',
        //     gameid:'DTGDTA0',
        // },
        // {
        //     name:'欧洲轮盘' ,
        //     gameurl:'../../images/member/2018/game/ag/TA1L_ZH.png',
        //     gameid:'TAITA1O',
        // },
        // {
        //     name:'欧洲轮盘-低额投注' ,
        //     gameurl:'../../images/member/2018/game/ag/TA1L_ZH.png',
        //     gameid:'TAITA1P',
        // },
    ] ;
    // mg 游戏列表
    var game_ot = new Array();
    game_ot.push({gameurl:'28114',name:'5卷的驱动器 ',gameid:'1035',type:'video'});
    game_ot.push({gameurl:'28138',name:'特务珍金',gameid:'1155',type:'feature'});
    game_ot.push({gameurl:'28174',name:'爱丽娜',gameid:'1021',type:'video'});
    game_ot.push({gameurl:'28194',name:'阿瓦隆',gameid:'1013',type:'video'});
    game_ot.push({gameurl:'44751',name:'篮球巨星',gameid:'1159',type:'video'});
    game_ot.push({gameurl:'28268',name:'厨神',gameid:'1140',type:'video'});
    game_ot.push({gameurl:'46497',name:'比基尼派对',gameid:'1290',type:'video'});
    game_ot.push({gameurl:'28292',name:'冰球突破',gameid:'1229',type:'feature'});
    game_ot.push({gameurl:'28350',name:'狂欢节',gameid:'1117',type:'video'});
    game_ot.push({gameurl:'28404',name:'酷狼',gameid:'1084',type:'feature'});
    game_ot.push({gameurl:'46494',name:'舞龙',gameid:'1037',type:'video'});
    game_ot.push({gameurl:'28592',name:'足球明星',gameid:'1186',type:'video'});
    game_ot.push({gameurl:'28630',name:'黄金时代',gameid:'1041',type:'video'});
    game_ot.push({gameurl:'28634',name:'黄金公主',gameid:'1190',type:'video'});
    game_ot.push({gameurl:'28490',name:'新年快乐',gameid:'1145',type:'classic'});
    game_ot.push({gameurl:'28540',name:'神奇墨水',gameid:'1376',type:'feature'});
    game_ot.push({gameurl:'28644',name:'酷犬酒店',gameid:'1063',type:'video'});
    game_ot.push({gameurl:'28548',name:'不朽情缘',gameid:'1103',type:'video'});
    game_ot.push({gameurl:'28772',name:'凯蒂卡巴拉',gameid:'1286',type:'video'});
    game_ot.push({gameurl:'28800',name:'招财鞭炮',gameid:'1126',type:'video'});
    game_ot.push({gameurl:'28794',name:'幸运的锦鲤',gameid:'1060',type:'video'});
    game_ot.push({gameurl:'28788',name:'幸运的小妖精',gameid:'2878',type:'video'});
    game_ot.push({gameurl:'42981',name:'幸运双星',gameid:'1283',type:'video'});
    game_ot.push({gameurl:'28758',name:'终极破坏',gameid:'1115',type:'video'});
    game_ot.push({gameurl:'28716',name:'海底世界 ',gameid:'1308',type:'video'});
    game_ot.push({gameurl:'42979',name:'躲猫猫',gameid:'1147',type:'video'});
    game_ot.push({gameurl:'28546',name:'持枪王者',gameid:'1160',type:'video'});
    game_ot.push({gameurl:'28858',name:'花花公子',gameid:'1188',type:'bonus'});
    game_ot.push({gameurl:'28512',name:'纯铂',gameid:'1312',type:'video'});
    game_ot.push({gameurl:'28912',name:'帽子里的兔子',gameid:'2891',type:'video'});
    game_ot.push({gameurl:'28474',name:'红唇诱惑',gameid:'1247',type:'video'});
    game_ot.push({gameurl:'28468',name:'宝石迷阵',gameid:'1207',type:'feature'});
    game_ot.push({gameurl:'28456',name:'复古卷轴 - 极热',gameid:'1189',type:'feature'});
    game_ot.push({gameurl:'28452',name:'复古卷轴钻石耀眼',gameid:'1100',type:'feature'});
    game_ot.push({gameurl:'28440',name:'洛伯杰克',gameid:'1132',type:'bonus'});
    game_ot.push({gameurl:'45399',name:'宁静',gameid:'1300',type:'video'});
    game_ot.push({gameurl:'28386',name:'糖果多多',gameid:'1374',type:'video'});
    game_ot.push({gameurl:'45401',name:'星尘',gameid:'1404',type:'video'});
    game_ot.push({gameurl:'50193',name:'太阳征程',gameid:'1150',type:'video'});
    game_ot.push({gameurl:'28232',name:'反转马戏团',gameid:'1386',type:'video'});
    game_ot.push({gameurl:'28228',name:'雷神',gameid:'1028',type:'video'});
    game_ot.push({gameurl:'28226',name:'雷神2',gameid:'1330',type:'video'});
    game_ot.push({gameurl:'28112',name:'太阳神之许珀里翁',gameid:'1208',type:'video'});
    game_ot.push({gameurl:'28220',name:'太阳神之忒伊亚',gameid:'1385',type:'video'});
    game_ot.push({gameurl:'28218',name:'古墓丽影',gameid:'1122',type:'bonus'});
    game_ot.push({gameurl:'28216',name:'古墓丽影秘密之剑',gameid:'1383',type:'bonus'});
    game_ot.push({gameurl:'50194',name:'东方珍兽',gameid:'1164',type:'bonus'});

    // pt 游戏分类列表
    var jackpot_games={
        'tgcarousel':{
            'avng':{'name':'复仇者联盟','image':'slotgame001s.jpg','jp':'mrj-4'},
            'cam':{'name':'美国队长','image':'slotgame002s.jpg','jp':'mrj-4'},
            'trm':{'name':'雷神 - 超级复仇者','image':'slotgame003.jpg','jp':'mrj-4'},
            'irm2':{'name':'钢铁侠','image':'slotgame004.jpg','jp':'mrj-4'},
            'irm3':{'name':'钢铁侠2','image':'slotgame005.jpg','jp':'mrj-4'},
            'irmn3':{'name':'钢铁侠3','image':'slotgame006s.jpg','jp':'mrj-4'},
            'hlk50':{'name':'绿巨人','image':'slotgame007.jpg','jp':'mrj-4'},
            'xmn':{'name':'X战警','image':'slotgame008s.jpg','jp':'mrj-4'},
            'fnf':{'name':'神奇四侠','image':'slotgame009.jpg','jp':'mrj-4'},
            'fnf50':{'name':'神奇四侠50线','image':'slotgame010.jpg','jp':'mrj-4'},
            'bld':{'name':'刀锋战士','image':'slotgame011.jpg','jp':'mrj-4'},
            'ghr':{'name':'恶灵骑士','image':'slotgame012.jpg','jp':'mrj-4'},
            'drd':{'name':'夜魔侠','image':'slotgame013.jpg','jp':'mrj-4'},
            'elr':{'name':'艾丽卡','image':'slotgame014.jpg','jp':'mrj-4'},
            'irm50':{'name':'50线钢铁侠2','image':'slotgame015.jpg','jp':'mrj-4'},
            'kkg':{'name':'无敌金刚','image':'slotgame016.jpg'},
            'rky':{'name':'洛基传奇','image':'slotgame017.jpg'},
            'mmy':{'name':'木乃伊迷城','image':'slotgame018.jpg'},
            'hb':{'name':'一夜奇遇','image':'slotgame019.jpg'},
            'bl':{'name':'沙滩假日','image':'slotgame020.jpg','jp':'bl'},
            'cifr':{'name':'全景电影','image':'slotgame021s.jpg','jp':'cifr'},
            'mcb':{'name':'CASH BACK先生','image':'slotgame022.jpg'},
            'ct':{'name':'船长宝藏','image':'slotgame023.jpg'},
            'dt':{'name':'沙漠宝藏','image':'slotgame024.jpg'},
            't2':{'name':'沙漠宝藏2','image':'slotgame025.jpg'},
            'dlm':{'name':'Dr情圣博士','image':'slotgame026.jpg'},
            'evj':{'name':'全民大富翁','image':'slotgame027.jpg','jp':'evjj-1'},
            'fm':{'name':'古怪猴子','image':'slotgame028s.jpg'},
            'fbr':{'name':'绿茵法则','image':'slotgame029.jpg'},
            'fdt':{'name':'疯狂底特律七','image':'slotgame030.jpg'},
            'bib':{'name':'湛蓝深海','image':'slotgame031.jpg'},
            'lm':{'name':'疯狂乐透','image':'slotgame032.jpg'},
            'gts51':{'name':'幸运熊猫','image':'slotgame033.jpg'},
            'pnp':{'name':'粉红豹','image':'slotgame034.jpg','jp':'ls'},'tst':{'name':'网球明星','image':'slotgame035.jpg'},'ttcsc':{'name':'顶级王牌-明星','image':'slotgame036.jpg'},'tfs':{'name':'顶级王牌-足球明星','image':'slotgame037.jpg'},'wsffr':{'name':'玩转华尔街','image':'slotgame038.jpg'},'wis':{'name':'我心狂野','image':'slotgame039.jpg'},'spidc':{'name':'蜘蛛侠大战绿魔人','image':'slotgame051.jpg','jp':'mrj-4'},'wvm':{'name':'金钢狼','image':'slotgame052.jpg','jp':'mrj-4'},'xmn50':{'name':'X战警50线','image':'slotgame053.jpg','jp':'mrj-4'},'bld50':{'name':'刀锋战士50线','image':'slotgame054.jpg','jp':'mrj-4'},'gtsaod':{'name':'天使与恶魔','image':'slotgame055.jpg'},'ashfmf':{'name':'圆月财富','image':'slotgame056.jpg'},'irm3sc':{'name':'钢铁侠2刮刮乐','image':'scratch001.jpg'},'kkgsc':{'name':'金刚刮刮乐','image':'scratch002.jpg'},'essc':{'name':'东方神奇刮刮乐','image':'scratch003.jpg'},'fbm':{'name':'疯狂足球刮刮乐','image':'scratch004.jpg'},'lom':{'name':'完美爱情刮刮乐','image':'scratch005.jpg'},'sbj':{'name':'21点刮刮乐','image':'scratch006.jpg'},'ssa':{'name':'圣诞刮刮乐','image':'scratch007.jpg'},'ttc':{'name':'顶级王牌明星刮刮乐','image':'scratch008.jpg'},'pks':{'name':'法老王国刮刮乐','image':'scratch009.jpg'},'bbn':{'name':'甲壳虫宾果刮刮乐','image':'scratch010.jpg'},'sro':{'name':'轮盘刮刮乐','image':'scratch011.jpg'},'tclsc':{'name':'小丑刮刮乐','image':'scratch012.jpg'},'irmn3sc':{'name':'钢铁侠3刮刮乐','image':'scratch013.jpg'},'pbj':{'name':'乐透21点','image':'card001.jpg'},'bj21d_mh':{'name':'决斗21点','image':'card002.jpg'},'cheaa':{'name':'赌场德州扑克','image':'card003.jpg'},'pg':{'name':'牌九游戏','image':'card004.jpg'},'wv':{'name':'疯狂维京人','image':'card005.jpg'},'tqp':{'name':'龙舌兰扑克','image':'card006.jpg'},'rd':{'name':'红狗扑克','image':'card007.jpg'},'car':{'name':'加勒比扑克','image':'card008.jpg'},'bjs':{'name':'换牌21点','image':'card009.jpg'},'psdbj':{'name':'21点专业版','image':'card010.jpg'},'rom':{'name':'漫威轮盘','image':'card011.jpg'},'rodz':{'name':'美式轮盘','image':'card012.jpg'},'frr':{'name':'法式轮盘','image':'card013.jpg'},'ro':{'name':'欧式轮盘','image':'card014.jpg'},'rop':{'name':'轮盘专业版','image':'card015.jpg'},'cr':{'name':'双骰游戏','image':'card016.jpg'},'sb':{'name':'骰宝','image':'card017.jpg'},'ro_g':{'name':'豪华欧式轮盘','image':'card018.jpg'},'frr_g':{'name':'豪华法式轮盘'},'rop_g':{'name':'豪华轮盘专业版'},'romw':{'name':'多轮式轮盘'},'gtsro3d':{'name':'3D 进阶轮盘'},'fm':{'name':'古怪猴子',},'xmn':{'name':'X战警','jp':'mrj-4'},'cifr':{'name':'全景电影','jp':'cifr'},'cam':{'name':'美国队长','jp':'mrj-4'}
        }
    }

    // pt游戏列表
    var pt_game_ot=new Array();
    pt_game_ot.push({gameurl:'ash3brg',name:'三卡吹牛'});
    pt_game_ot.push({gameurl:'tclsc',name:'3个小丑刮刮乐'});
    pt_game_ot.push({gameurl:'7bal',name:'真人7席百家乐'});
    pt_game_ot.push({gameurl:'hb',name:'狂欢夜'});
    pt_game_ot.push({gameurl:'ashadv',name:'梦游仙境豪华版'});
    pt_game_ot.push({gameurl:'aogs',name:'众神时代'});
    pt_game_ot.push({gameurl:'ftsis',name:'众神时代：命运姐妹'});
    pt_game_ot.push({gameurl:'furf',name:'众神时代：狂暴4'});
    pt_game_ot.push({gameurl:'athn',name:'众神时代：智慧女神'});
    pt_game_ot.push({gameurl:'zeus',name:'众神时代：奥林匹斯之'});
    pt_game_ot.push({gameurl:'hrcls',name:'众神时代：奥林匹斯王'});
    pt_game_ot.push({gameurl:'aogro',name:'众神时代轮盘'});
    pt_game_ot.push({gameurl:'rodz',name:'美式轮盘'});
    pt_game_ot.push({gameurl:'ashamw',name:'野生亚马逊'});
    pt_game_ot.push({gameurl:'bja',name:'美式21点'});
    pt_game_ot.push({gameurl:'arc',name:'弓箭手'});
    pt_game_ot.push({gameurl:'art',name:'北极宝藏'});
    pt_game_ot.push({gameurl:'gtsatq',name:'亚特兰蒂斯女王'});
    pt_game_ot.push({gameurl:'ba',name:'百家乐'});
    pt_game_ot.push({gameurl:'bal',name:'真人百家乐'});
    pt_game_ot.push({gameurl:'bs',name:'白狮'});
    pt_game_ot.push({gameurl:'bl',name:'海滨嘉年华'});
    pt_game_ot.push({gameurl:'bt',name:'百慕大三角'});
    pt_game_ot.push({gameurl:'bj_mh5',name:'21点'});
    pt_game_ot.push({gameurl:'bjl',name:'真人21点'});
    pt_game_ot.push({gameurl:'psdbj',name:'职业21点'});
    pt_game_ot.push({gameurl:'bjsd_mh5',name:'多手投降21点'});
    pt_game_ot.push({gameurl:'bob',name:'熊之舞'});
    pt_game_ot.push({gameurl:'ashbob',name:'魔豆赏金'});
    pt_game_ot.push({gameurl:'bfb',name:'犎牛闪电突击'});
    pt_game_ot.push({gameurl:'ct',name:'船长的宝藏'});
    pt_game_ot.push({gameurl:'ctp2',name:'船长的宝藏 加强版'});
    pt_game_ot.push({gameurl:'cashfi',name:'深海大赢家'});
    pt_game_ot.push({gameurl:'ctiv',name:'猫王战赌城'});
    pt_game_ot.push({gameurl:'catqk',name:'猫后'});
    pt_game_ot.push({gameurl:'cheaa',name:'娱乐场同花顺'});
    pt_game_ot.push({gameurl:'chel',name:'真人娱乐场同花顺'});
    pt_game_ot.push({gameurl:'gtscb',name:'现金魔块'});
    pt_game_ot.push({gameurl:'chao',name:'超级 888'});
    pt_game_ot.push({gameurl:'chl',name:'狂野樱桃'});
    pt_game_ot.push({gameurl:'ashcpl',name:'宝箱满满'});
    pt_game_ot.push({gameurl:'cm',name:'中式厨房'});
    pt_game_ot.push({gameurl:'scs',name:'经典老虎机刮刮乐'});
    pt_game_ot.push({gameurl:'gtscnb',name:'警察和土匪'});
    pt_game_ot.push({gameurl:'gtscbl',name:'牛仔和外星人'});
    pt_game_ot.push({gameurl:'c7',name:'疯狂七'});
    pt_game_ot.push({gameurl:'gtsdrdv',name:'无畏的戴夫'});
    pt_game_ot.push({gameurl:'dt',name:'沙漠财宝'});
    pt_game_ot.push({gameurl:'dt2',name:'沙漠财宝二'});
    pt_game_ot.push({gameurl:'dv2',name:'钻石山谷 加强版'});
    pt_game_ot.push({gameurl:'dnr',name:'海豚之梦'});pt_game_ot.push({gameurl:'gtsdgk',name:'龙之国度'});pt_game_ot.push({gameurl:'dlm',name:'情圣博士'});pt_game_ot.push({gameurl:'dual_rol',name:'真人双桌轮盘'});pt_game_ot.push({gameurl:'eas',name:'复活节惊喜'});pt_game_ot.push({gameurl:'ro',name:'欧式轮盘'});pt_game_ot.push({gameurl:'rodl',name:'真人 VIP 轮盘'});pt_game_ot.push({gameurl:'esmk',name:'埃斯梅拉达'});pt_game_ot.push({gameurl:'evj',name:'欢乐积寶彩池'});pt_game_ot.push({gameurl:'ashfta',name:'魔镜与公主'});pt_game_ot.push({gameurl:'fcgz',name:'翡翠公主'});pt_game_ot.push({gameurl:'gtsflzt',name:'飞龙在天'});pt_game_ot.push({gameurl:'fkmj',name:'疯狂麻将'});pt_game_ot.push({gameurl:'gtsfpc',name:'鱼虾蟹'});pt_game_ot.push({gameurl:'ftg',name:'五虎将'});pt_game_ot.push({gameurl:'gtsfc',name:'足球嘉年华'});pt_game_ot.push({gameurl:'fbr',name:'终极足球'});pt_game_ot.push({gameurl:'fow',name:'惊异之林'});pt_game_ot.push({gameurl:'fday',name:'幸运日 '});pt_game_ot.push({gameurl:'frtln',name:'幸运狮子'});pt_game_ot.push({gameurl:'foy',name:'青春之泉'});pt_game_ot.push({gameurl:'fxf',name:'狐媚宝藏'});pt_game_ot.push({gameurl:'frtf',name:'五福海盗'});pt_game_ot.push({gameurl:'fdt',name:'德托里传奇'});pt_game_ot.push({gameurl:'fdtjg',name:'德托里传奇积宝游戏'});pt_game_ot.push({gameurl:'fmn',name:'水果狂热'});pt_game_ot.push({gameurl:'ashfmf',name:'圆月财富'});pt_game_ot.push({gameurl:'fnfrj',name:'酷炫水果'});pt_game_ot.push({gameurl:'fff',name:'酷炫水果农场'});pt_game_ot.push({gameurl:'fm',name:'古怪猴子'});pt_game_ot.push({gameurl:'ges',name:'艺伎故事 '});pt_game_ot.push({gameurl:'gesjp',name:'艺伎故事积宝游戏&nbsp;'});pt_game_ot.push({gameurl:'gemq',name:'宝石女王'});pt_game_ot.push({gameurl:'glr',name:'角斗士'});pt_game_ot.push({gameurl:'glrj',name:'角斗士积宝'});pt_game_ot.push({gameurl:'grel',name:'金色召集'});pt_game_ot.push({gameurl:'glg',name:'黄金体育竞技场'});pt_game_ot.push({gameurl:'gos',name:'黄金之旅'});pt_game_ot.push({gameurl:'bib',name:'海底探宝'});pt_game_ot.push({gameurl:'gro',name:'最强奥德赛'});pt_game_ot.push({gameurl:'hlf',name:'万圣节宝藏'});pt_game_ot.push({gameurl:'hlf2',name:'万圣节宝藏 2'});pt_game_ot.push({gameurl:'hh',name:'鬼宅'});pt_game_ot.push({gameurl:'ashhotj',name:'丛林之心'});pt_game_ot.push({gameurl:'hk',name:'高速公路之王'});pt_game_ot.push({gameurl:'gtshwkp',name:'高速公路之王加强版'});pt_game_ot.push({gameurl:'gts50',name:'炙热宝石'});pt_game_ot.push({gameurl:'hotktv',name:'火热KTV'});pt_game_ot.push({gameurl:'gtsir',name:'浮冰流'});pt_game_ot.push({gameurl:'aztec',name:'印加帝国头奖'});pt_game_ot.push({gameurl:'gtsirl',name:'幸运爱尔兰'});pt_game_ot.push({gameurl:'jpgt',name:'奖金巨人'});pt_game_ot.push({gameurl:'gtsje',name:'玉皇大帝'});pt_game_ot.push({gameurl:'gtsjxb',name:'吉祥 8'});pt_game_ot.push({gameurl:'jqw',name:'金钱蛙'});pt_game_ot.push({gameurl:'gtsjhw',name:'约翰韦恩'});pt_game_ot.push({gameurl:'kkg',name:'无敌金刚'});pt_game_ot.push({gameurl:'lndg',name:'遍地黄金'});pt_game_ot.push({gameurl:'ght_a',name:'烈焰钻石'});pt_game_ot.push({gameurl:'kfp',name:'六福兽'});pt_game_ot.push({gameurl:'rofl',name:'真人法式轮盘'});pt_game_ot.push({gameurl:'longlong',name:'龙龙龙'});pt_game_ot.push({gameurl:'lm',name:'疯狂乐透'});pt_game_ot.push({gameurl:'gts51',name:'幸运熊猫'});pt_game_ot.push({gameurl:'mgstk',name:'魔力老虎机'});pt_game_ot.push({gameurl:'ms',name:'神奇老虎机'});pt_game_ot.push({gameurl:'gtsmrln',name:'玛丽莲梦露'});pt_game_ot.push({gameurl:'mfrt',name:'幸运女士'});pt_game_ot.push({gameurl:'ashlob',name:'蒙提派森之万世魔星'});pt_game_ot.push({gameurl:'mcb',name:'返利先生'});pt_game_ot.push({gameurl:'nk',name:'海王星王国'});pt_game_ot.push({gameurl:'nian_k',name:'年年有余'});pt_game_ot.push({gameurl:'nc_bal',name:'真人无佣金百家乐'});pt_game_ot.push({gameurl:'nc_7bal',name:'真人无佣金7席百家乐'});pt_game_ot.push({gameurl:'pmn',name:'月亮下的黑豹'});pt_game_ot.push({gameurl:'pl',name:'派对风景线'});pt_game_ot.push({gameurl:'pfbj_mh5',name:'完美21点'});pt_game_ot.push({gameurl:'pgv',name:'企鹅度假'});pt_game_ot.push({gameurl:'pst',name:'法老王的秘密'});pt_game_ot.push({gameurl:'paw',name:'三只小猪与大灰狼'});pt_game_ot.push({gameurl:'pnp',name:'粉红豹'});pt_game_ot.push({gameurl:'gtspor',name:'充裕财富'});pt_game_ot.push({gameurl:'photk',name:'紫色狂热'});pt_game_ot.push({gameurl:'rodz_g',name:'奖金美式轮盘'});pt_game_ot.push({gameurl:'ro_g',name:'奖金欧式轮盘'});pt_game_ot.push({gameurl:'mcrol',name:'真人威信轮盘'});pt_game_ot.push({gameurl:'plba',name:'真人累积百家乐'});pt_game_ot.push({gameurl:'qop',name:'金字塔女王'});pt_game_ot.push({gameurl:'qnw',name:'权杖女王'});pt_game_ot.push({gameurl:'ririjc',name:'日日进财'});pt_game_ot.push({gameurl:'ririshc',name:'日日生财'});pt_game_ot.push({gameurl:'rky',name:'洛奇'});pt_game_ot.push({gameurl:'gtsrng',name:'罗马荣光'});pt_game_ot.push({gameurl:'rol',name:'真人轮盘'});pt_game_ot.push({gameurl:'gtsru',name:'魔方财富'});pt_game_ot.push({gameurl:'sfh',name:'野生狩獵'});pt_game_ot.push({gameurl:'gtssmbr',name:'桑巴之舞'});pt_game_ot.push({gameurl:'ssp',name:'圣诞老人奇袭'});pt_game_ot.push({gameurl:'savcas',name:'大草原现金'});pt_game_ot.push({gameurl:'samz',name:'亚马逊之谜'});pt_game_ot.push({gameurl:'shmst',name:'神秘夏洛克'});pt_game_ot.push({gameurl:'sx',name:'四象'});pt_game_ot.push({gameurl:'sbl',name:'真人骰宝'});pt_game_ot.push({gameurl:'skp',name:'俄式童话加强版'});pt_game_ot.push({gameurl:'sis',name:'忍者风云'});pt_game_ot.push({gameurl:'sisjp',name:'忍者风云积宝游戏'});pt_game_ot.push({gameurl:'sib',name:'银弹'});pt_game_ot.push({gameurl:'ashsbd',name:'辛巴达金航记'});pt_game_ot.push({gameurl:'spr',name:'斯巴达'});pt_game_ot.push({gameurl:'spud',name:'欧莱里之黄金大田'});pt_game_ot.push({gameurl:'sfr',name:'标准五卷轴'});pt_game_ot.push({gameurl:'sol',name:'幸运直击'});pt_game_ot.push({gameurl:'gtsswk',name:'孙悟空'});pt_game_ot.push({gameurl:'cnpr',name:'甜蜜派对'});pt_game_ot.push({gameurl:'tpd2',name:'泰国梦天堂'});pt_game_ot.push({gameurl:'thtk',name:'泰国佛寺'});pt_game_ot.push({gameurl:'dcv',name:'海上寻宝'});pt_game_ot.push({gameurl:'gtsjzc',name:'爵士俱乐部'});pt_game_ot.push({gameurl:'gtsgme',name:'大明帝国'});pt_game_ot.push({gameurl:'lvb',name:'恋爱之船'});pt_game_ot.push({gameurl:'mmy',name:'木乃伊迷城'});pt_game_ot.push({gameurl:'donq',name:'唐吉诃德'});pt_game_ot.push({gameurl:'tmqd',name:'三个火枪手'});pt_game_ot.push({gameurl:'ashtmd',name:'三门问题'});pt_game_ot.push({gameurl:'topg',name:'捍卫战士'});pt_game_ot.push({gameurl:'ttc',name:'顶级王牌名人游戏'});pt_game_ot.push({gameurl:'ta',name:'三友行'});pt_game_ot.push({gameurl:'trpmnk',name:'三倍猴子'});pt_game_ot.push({gameurl:'trl',name:'真爱游戏'});pt_game_ot.push({gameurl:'ub',name:'部落生活'});pt_game_ot.push({gameurl:'ubjl',name:'真人无限21点'});pt_game_ot.push({gameurl:'er',name:'开心假期'});pt_game_ot.push({gameurl:'vcstd',name:'开心假期豪華版'});pt_game_ot.push({gameurl:'gts52',name:'维京狂热'});pt_game_ot.push({gameurl:'vbal',name:'真人 VIP 百家乐'});pt_game_ot.push({gameurl:'whk',name:'白狮王'});pt_game_ot.push({gameurl:'gtswg',name:'野生动物大世界'});pt_game_ot.push({gameurl:'ashwgaa',name:'野生世界：北极大冒险'});pt_game_ot.push({gameurl:'wis',name:'狂野精灵'});pt_game_ot.push({gameurl:'gtswng',name:'纯金之翼'});pt_game_ot.push({gameurl:'wlg',name:'舞龙'});pt_game_ot.push({gameurl:'wlgjp',name:'舞龙积宝游戏'});pt_game_ot.push({gameurl:'wlcsh',name:'五路财神'});pt_game_ot.push({gameurl:'zcjb',name:'招财进宝'});pt_game_ot.push({gameurl:'zcjbjp',name:'招财进宝积宝财池'});pt_game_ot.push({gameurl:'zctz',name:'招财童子'});

    window.onscroll = scroll;function scroll(){
        var refresh_right= document.getElementById('refresh_right');refresh_right.style.top=document.body.scrollTop+39;
    }

    var userAgents='<?php echo $_SESSION['Agents'];?>';

    function jb(ctr) {
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            jQuery.jBox('get:tran_dianzi.php?uid='+uid+'&ctr=' + ctr, {
                title: "电子游戏额度转换",
                buttons: {'关闭': true}
            });
        }
    }

    function open_game(url) {
        window.open("mgIframe.php?mgUrl=" + url, 'pt_game');
    }
    // function loadkf(uri) {
    //     jQuery.jBox('get:' + uri, {
    //         title: "客服中心", width: 1000, height: 500,
    //         buttons: {'关闭': true}
    //     });
    // }

    var count = 20; // 每页展示数量
    var page_tt = 0; // 初始页码
    var game_type_c ='ag' ; // 默认游戏类型
    var game_list ={} ;
    var test_username = '<?php echo $test_username;?>';

    if(g_type =='mg'){
        game_type_c ='mg' ;
    }

    // mg 游戏列表渲染
    function int_page(cp,gamelist) {
        var str ='' ;
        if(game_type_c=='ag'){ //ag
            game_list = aglist ;
        }else{ // mg
            game_list = game_ot ;
        }
        if(!gamelist){
            gamelist = game_list;
        }
        page_tt = Math.ceil(gamelist.length / count); // 总页数

        if(game_type_c =='mg'){ // mg

            for (var i= (cp - 1) * count; i < (cp * count > gamelist.length ? gamelist.length : cp * count); i++) {
                str +='<div class="slot_box slot_box_'+ gamelist[i].type +'" style="width: 160px;">' +
                    '<div class="slot_b1" style="background-image: url(../../images/member/2018/game/mg/'+gamelist[i].gameurl+'.png);width: 140px;height: 135px; margin-left:5px;"></div>' +
                    '<div class="slot_b2">' +
                    '<div class="slot_kk">' +
                    '<span class="slot_tt">' + gamelist[i].name + '</span>' +
                    '<span class="slot_bb">' +
                    // '<a class="ks_slots2 " onclick="open_game(\'action.php?uid='+uid+'&action=f&gameid=' + gamelist[i].gameid + '\')">真钱模式</a>' +
                    '<a class="ks_slots2 " target="_blank" href="mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[i].gameid+'">真钱模式</a>' +
                    // '<a class="ks_slots2 try_play" target="_blank" href="mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[i].gameid+'">试玩模式</a>' +
                    '</span>' +
                    '<div class="clear"></div>' +
                    '</div></div>' +
                    '<div class="clear"></div>' +
                    '</div>';
            }
        }
        else if(game_type_c == 'ag'){

            for (var j = (cp - 1) * count; j < (cp * count > gamelist.length ? gamelist.length : cp * count); j++) {
                var realurl = 'zrsx/login.php?uid='+uid+'&gameid='+gamelist[j].gameid;
                var tryurl = 'zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+gamelist[j].gameid;
                str +='<div class="slot_box slot_box_'+ gamelist[j].type +'" style="width: 160px;">' +
                    '<div class="slot_b1" >' +
                    '<img style="width: 160px;" border="0" class="finsh_img" src="'+gamelist[j].gameurl+'">' +
                    '</div>' +
                    '<div class="slot_b2" style="padding-bottom: 15px;">' +
                    // '<div class="slot_jc" id="g_'+gamelist[i].gameurl+'" style="visibility:hidden;">UPDATING</div>' +
                    '<div class="slot_kk"><span class="slot_tt">'+gamelist[j].name+'</span>' +
                    '<span class="slot_bb"><a class="ks_slots2" href="'+realurl+'" target="_blank">真钱模式</a>' +
                    '<a class="ks_slots2 try_play" href="'+tryurl+'" target="_blank" >试玩模式</a></span>' +
                    '<div class="clear"></div>' +
                    '</div></div>' +
                    '<div class="clear"></div>' +
                    '</div>' ;
            }
        }

        jQuery('#holder').html(str) ;

        jQuery('.slot_b1').each(function () {
            jQuery(this).hover(function(){jQuery(this).css('background-position', '-145px 0px')},function(){jQuery(this).css('background-position', '0px 0px')})
        })
    };
    int_page(1);
    setPageCount() ;
    // 页码设置
    function setPageCount() {
        if (page_tt > 0) {
            var pstr = '' ;
            for (var j = 1; j <= page_tt; j++) {
                if (1 == j) {
                    pstr +='<a href="javascript:void(0)" class="swShowPage active" topage="1"></a>' ;
                } else {
                    pstr +='<a href="javascript:void(0)" class="swShowPage" topage="'+j+'"></a>' ;
                }
            }
            $('.swControls').html(pstr) ;

            $('.swControls').on('click','a',function () { // 绑定切换页码事件
                $(this).addClass('active').siblings().removeClass('active') ;
                int_page($(this).attr('toPage')) ;
            }) ;
        }
    }

    // pt 游戏列表渲染
    function getJackpot(game_type) {
        games = jackpot_games[game_type];
        for (var game in games) {
            if (document.getElementById(game_type + "-" + game) && games[game].jp) {
                var ticker = new Ticker({
                    info: 1,
                    casino: 'playtech',
                    game: games[game].jp,
                    currency: 'cny'
                });
                var textBox = game_type + "-" + game;
                ticker.attachToTextBox(textBox);
                ticker.SetCurrencySign('￥');
                ticker.SetCurrencyPos(0);
                ticker.tick();
            }
            if (document.getElementById("b_" + game) && games[game].jp) {
                var ticker = new Ticker({
                    info: 1,
                    casino: 'playtech',
                    game: games[game].jp,
                    currency: 'cny'
                });
                var textBox = "b_" + game;
                document.getElementById(textBox).style.visibility = 'visible';
                ticker.attachToTextBox(textBox);
                ticker.SetCurrencySign('￥');
                ticker.SetCurrencyPos(0);
                ticker.tick();
            }
            if (document.getElementById("g_" + game) && games[game].jp) {
                var ticker = new Ticker({
                    info: 1,
                    casino: 'playtech',
                    game: games[game].jp,
                    currency: 'cny'
                });
                var textBox = "g_" + game;
                document.getElementById(textBox).style.visibility = 'visible';
                ticker.attachToTextBox(textBox);
                ticker.SetCurrencySign('￥');
                ticker.SetCurrencyPos(0);
                ticker.tick();
            }
        }
    }
    // pt 游戏
    /*  var ticker = new Ticker({
          info: 2,
          casino: "playtech",
          currency: 'cny',
          root_url: "http://tickers.playtech.com/"
      });
      ticker.attachToTextBox("allptt");
      ticker.SetCurrencyPos(0);
      ticker.SetCurrencySign('¥');
      ticker.tick();*/


    // 左侧选单
    function setLeftList(par) {
        var $uitabsnav = $('.ui-tabs-nav');
        var list ='' ;
        if(par == 'mg'){ // mg
            list ='<li><a class="active" href="javascript:;" data-to="all">全部游戏</a></li>\n' +
                '<li><a href="javascript:;" data-to="video">视频老虎机</a></li>\n' +
                '<li><a href="javascript:;" data-to="classic">经典老虎机</a></li>\n' +
                '<li><a href="javascript:;" data-to="bonus">奖金老虎机</a></li>\n' +
                '<li><a href="javascript:;" data-to="feature">特色老虎机</a></li>\n' +
                '<div class="clear"></div>';
        }else{ // ag
            list =' <li class="ui-tabs-selected"><a class="active" href="javascript:;" data-to="all">全部游戏</a></li>\n' +
                '<li><a href="javascript:;" data-to="slot">经典老虎机</a></li>\n' +
                '<li><a href="javascript:;" data-to="video">视频扑克</a></li>\n' +
                '<li><a href="javascript:;" data-to="table">桌上游戏</a></li>\n' +
                '<div class="clear"></div>' ;
        }
        $uitabsnav.html(list) ;
        // 筛选游戏
        $uitabsnav.on('click','a',function () {
            var g_type = $(this).attr('data-to');
            var $holder = $('#holder') ;
            var chooseGameList = new Array();
            $(this).addClass('active').parent('li').siblings().find('a').removeClass('active');
            if(g_type=='all'){ // 全部
                chooseGameList = game_list;
            }else{
                $.each(game_list,function (i,v) {
                    if(v.type == g_type){ // 匹配搜索
                        // console.log(v.name)
                        chooseGameList.push(
                            {
                                name: v.name ,
                                gameurl: v.gameurl,
                                gameid: v.gameid,
                                type: v.type,
                            }
                        )
                    }
                })

            }
            // console.log(chooseGameList)
            int_page(1,chooseGameList);
            setPageCount();
        })


    }
    // 搜索游戏
    function seachGameName(){
        $('.submit-btn').on('click',function () {
            var txt = $('.seachgame_input').val();
            var seach_game_list = new Array();
            $.each(game_list,function (i,v) {
                if(v.name.indexOf(txt)>-1){ // 匹配搜索
                    // console.log(v.name)
                    seach_game_list.push(
                        {
                            name: v.name ,
                            gameurl: v.gameurl,
                            gameid: v.gameid,
                        }
                    )
                }
            })
            int_page(1,seach_game_list);
            setPageCount();

        })
    }


    // 顶部游戏标签切换
    function tabChangeGame() {
        $('.tab-choose-game').on('click','a',function () {
            $(this).addClass('hover').parent('li').siblings().find('a').removeClass('hover') ;
            var val = $(this).data('val') ;
            game_type_c = val ;
            // console.log(val) ;
            setLeftList(val) ;
            if(val =='mg'){ // mg
                $('.pt-self,.ag_show').hide() ;
                int_page(1) ;
                setPageCount() ;
                $('.mg_show').show();
            }else if(val =='ag'){
                $('.pt-self,.mg_show').hide() ;
                int_page(1) ;
                setPageCount() ;
                $('.ag_show').show();
            } else{ // pt
                $('.pt-self').show() ;
                getJackpot("tgcarousel");
                int_page(1) ;
                setPageCount() ;
            }
        })

    }

    function mg_blance() {
        $('#mgmoney').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'mg/mg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#mgmoney').html(ret.balance_mg);
                }
                else{
                    $('#mgmoney').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }
    // 回车键提交
    function enterSubmitAction() {
        $('input').bind('keyup', function(event) { // enter 登录
            if (event.keyCode == "13") {
                //回车执行查询
                $('.submit-btn').click();
            }
        });
    }

    setLeftList('ag') ;
    tabChangeGame() ;
    seachGameName();
    enterSubmitAction();

</script>

</body>
</html>