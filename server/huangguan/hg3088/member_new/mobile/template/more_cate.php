<?php
session_start();
include_once('../include/config.inc.php');
include ('../include/define_function_list.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='/".TPL_NAME."login.php';</script>";
    exit;
}
$username = $_SESSION['UserName'];
$uid=$_SESSION["Oid"];
$type = $_REQUEST['type']; // 当前游戏类型
$more = $_REQUEST['more']; // 当前游戏是今日赛事还是滚球
$gid = $_REQUEST['gid']; // 当前游戏id
$showtype = $_REQUEST['showtype'] ;
$gtype = $_REQUEST['gtype'] ;
$mdate = isset($_REQUEST['mdate'])?$_REQUEST['mdate']:'' ; // 时间
$mtype= $_REQUEST['mtype'] ; // mtype 1 为滚球字眼，其他不是
$M_League = isset($_REQUEST['M_League'])?$_REQUEST['M_League']:''; // 综合过关

if($showtype=='RB'){ // 倒计时 滚球
    $autotime = 20 ; // 刷新时间
}else if($showtype=='FT' || $showtype=='BK'){ // 今日
    $autotime = 60 ; // 刷新时间
}else{ // 早盘
    $autotime = 90 ; // 刷新时间
}
$tiptype = isset($_REQUEST['tiptype'])?$_REQUEST['tiptype']:''; // 冠军 champion ，综合过关 p3

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="/<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php
    if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='0086dj'){
        echo '<link href="/style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>';
    }
    ?>
    <link href="/<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
    <style type="text/css">
        .header-right {width: 14%;}
        <?php
             if((TPL_FILE_NAME=='3366' || TPL_FILE_NAME=='8msport')){
                 echo '.header{background:#c1c1c1}';
             }
         ?>
    </style>
    <script type="text/javascript">
        // 解决 部分浏览器返回不刷新问题
        window.addEventListener('pageshow', function(event) {
            //event.persisted属性为true时，表示当前文档是从往返缓存中获取
            if (event.persisted) {
                location.reload();
            }
        });
    </script>
</head>
<body >
<div id="container" class="<?php echo $gtype;?>">

    <!-- 顶部导航栏 -->
    <div class="header sport_header">
        <div class="header_left">
            <a class="back-active sport_back_icon" href="javascript:goBackAction();" ></a>

        </div>
        <div class="header-center">
            <a class="header_live <?php if($showtype=='RB'){echo 'active';} ?>" data-type="RBMATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','')"><i class="rb_running_logo"></i>滚球</a>
            <a class="header_today <?php if($showtype=='FT' || $showtype=='BK'){echo 'active';} ?>" data-type="TODAYMATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','')">今日</a>
            <a class="header_early <?php if($showtype=='FU' || $showtype=='BU'){echo 'active';} ?>" data-type="FUTUREMATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','')">早盘</a>
        </div>
        <div class="header-right" >
            <span class="menu_icon" data-num="1" onclick="showDownMenu(this)"> </span>
        </div>
    </div>

    <!-- 中间内容 -->
    <div class="content-center bet-container sport-content-center">

        <!-- 下拉菜单 -->
        <div class="subaccountform_menu" style="display: none">

            <div class="menu_user">
                <div class="float_left user_2"><?php echo $username?></div>
                <div class="dropdown_sub_right float_right">
                    RMB
                    <span id="acc_credit"  class="curr_amount_2 user_money"> </span>
                    <span id="curr_reload" class="float_right curr_reload "></span>
                </div>
            </div>

        </div>

        <!-- 导航切换 -->
        <div class="sportNav">

            <div class="sportNav_title">
                <div id="lea_title_gtype"  class="game_title"> </div>
                <div id="refresh" class="refresh" onclick="getMoreGames('<?php echo $gid?>','<?php echo $gtype?>','<?php echo $showtype?>','<?php echo $tiptype?>','<?php echo $M_League?>');">
                    <span id="refresh-btn"> </span>
                </div>
            </div>
        </div>

        <!-- 投注列表区域 开始-->
        <div class="bet-content">

            <!-- 球队栏 -->
            <table border="0" cellspacing="0" cellpadding="0" id="div_matches" class="matches">
                <tbody>
                <tr>
                    <td class="board_1">

                        <div id="board_title" class="board_title">
                            <div class="board_l">
                                <div id="game_live" class="live_time_board"><?php echo $mtype==1?'滚球':'' ?></div>
                                <div id="game_time" class="game_time"> </div> <!-- 比赛时间 -->
                                <div id="game_midfield" style="display:none" class="odds_mid"> <!--N--></div>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td class="match_team board_2">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="scoreboard">
                            <tbody>
                            <tr>
                                <td class="board_team_h">
                                    <span id="game_score_h" class="score_zero">0</span> <!-- 主队进球数 score_light -->
                                    <div id="game_team_h">主场</div>
                                </td>
                                <td class="board_score"><span class="score_v">|</span></td>
                                <td class="board_team_c">
                                    <span id="game_score_c" class="score_zero">0</span> <!-- 客队进球数 -->
                                    <div id="game_team_c">客场</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="add_bk_score"></tr>
                </tbody>
            </table>

            <!-- 无赛事 -->
            <div id="div_nodata" name="div_nodata" class="NoEvent_game" style="display:none;">此赛事暂时停止收注或已关闭</div>

            <!-- 有赛事 容器-->
            <div class="has_sport_matches" >

            </div>
            <!-- 赛事列表 -->
            <div id="sport_div_show" >

                <!-- 单场 让球 开始-->
                <div class="bet-dcrq">

                </div>
                <!-- 单场 让球 结束-->

                <!-- 半场 让球 开始-->
                <div class="bet-bcrq">

                </div>
                <!-- 半场 让球 结束-->

                <!-- 单场 大/小 开始-->
                <div class="bet-dcdx">

                </div>
                <!-- 单场 大/小 结束-->

                <!-- 半场 大/小 开始-->
                <div class="bet-bcdx">

                </div>
                <!-- 半场 大/小 结束-->

                <!-- 单场 独赢 开始-->
                <div class="bet-dcdy">

                </div>
                <!-- 单场 独赢 结束-->

                <!-- 半场 独赢 开始-->
                <div class="bet-bcdy">

                </div>
                <!-- 半场 独赢 结束-->

                <!-- 波胆全场 开始-->
                <div class="bet-bddc">

                </div>
                <!-- 波胆全场 结束-->

                <!-- 波胆半场 开始-->
                <div class="bet-bdbc">

                </div>
                <!-- 波胆半场 结束-->

                <!-- 15分钟盘口 让球 开始-->
                <div class="bet-swrz">

                </div>
                <!-- 15分钟盘口 让球 结束-->

                <!-- 15分钟盘口 大小 开始-->
                <div class="bet-swdx">

                </div>
                <!-- 15分钟盘口 大小 结束-->

                <!-- 15分钟盘口 独赢 开始-->
                <div class="bet-swdy">

                </div>
                <!-- 15分钟盘口 独赢 结束-->

                <!-- 总进球数全场 开始-->
                <div class="bet-zjqsdc">

                </div>
                <!-- 总进球数全场 结束-->

                <!-- 总进球数半场 开始-->
                <div class="bet-zjqsbc">

                </div>
                <!-- 总进球数半场 结束-->

                <!-- 双方球队进球全场 开始-->
                <div class="bet-sfqddc">

                </div>
                <!-- 双方球队进球全场 结束-->

                <!-- 双方球队进球半场 开始-->
                <div class="bet-sfqdbc">

                </div>
                <!-- 双方球队进球半场 结束-->

                <!-- 球队进球数 单场-主队-大小 开始-->
                <div class="bet-qdjqszdc">

                </div>
                <!-- 球队进球数 单场-主队-大小 结束-->


                <!-- 球队进球数 半场-主队-大小 开始-->
                <div class="bet-qdjqszbc">

                </div>
                <!-- 球队进球数 半场-主队-大小 结束-->

                <!--  篮球才有 球队得分: - 最后一位数 主队 开始-->
                <div class="bet-mbqddfzhyws">

                </div>
                <!-- 球队得分: - 最后一位数 主队 结束-->

                <!--  篮球才有 球队得分: - 最后一位数 客队 开始-->
                <div class="bet-tgqddfzhyws">

                </div>
                <!-- 球队得分: - 最后一位数 客队 结束-->

                <!-- 单双单场 开始-->
                <div class="bet-dansdc">

                </div>
                <!-- 单双单场 结束-->

                <!-- 单双半场 开始-->
                <div class="bet-dansbc">

                </div>
                <!-- 单双半场 结束-->

                <!-- 最先/最后进球 开始-->
                <div class="bet-zxzhjq">

                </div>
                <!-- 最先/最后进球 结束-->

                <!-- 全场/半场 开始-->
                <div class="bet-qcbc">

                </div>
                <!-- 全场/半场 结束-->

                <!-- 净胜球数 开始-->
                <div class="bet-jsqs">

                </div>
                <!-- 净胜球数 结束-->

                <!-- 双重机会 开始-->
                <div class="bet-scjh">

                </div>
                <!-- 双重机会 结束-->

                <!-- 零失球 开始-->
                <div class="bet-lingsq">

                </div>
                <!-- 零失球 结束-->

                <!-- 零失球获胜 开始-->
                <div class="bet-lingsqhs">

                </div>
                <!-- 零失球获胜 结束-->

                <!-- 独赢 & 进球 大 / 小  开始-->
                <div class="bet-dyjqdx">

                </div>
                <!-- 独赢 & 进球 大 / 小  结束-->

                <!-- 独赢 & 双方球队进球  开始-->
                <div class="bet-dysfqdjq">

                </div>
                <!-- 独赢 & 双方球队进球  结束-->

                <!-- 进球 大 / 小 & 双方球队进球  开始-->
                <div class="bet-jqdxsfqdjq">

                </div>
                <!-- 进球 大 / 小 & 双方球队进球  结束-->

                <!-- 独赢 & 最先进球  开始-->
                <div class="bet-dyzxjq">

                </div>
                <!-- 独赢 & 最先进球  结束-->

                <!-- 最多进球的半场  开始-->
                <div class="bet-zdjqdbc">

                </div>
                <!-- 最多进球的半场  结束-->

                <!-- 最多进球的半场 - 独赢  开始-->
                <div class="bet-zdjqdbcdy">

                </div>
                <!-- 最多进球的半场 - 独赢  结束-->

                <!-- 双半场进球  开始-->
                <div class="bet-sbcjq">

                </div>
                <!-- 双半场进球  结束-->

                <!-- 首个进球时间-3项  开始-->
                <div class="bet-sgjqsj3x">

                </div>
                <!-- 首个进球时间-3项  结束-->

                <!-- 首个进球时间  开始-->
                <div class="bet-sgjqsj">

                </div>
                <!-- 首个进球时间  结束-->

                <!-- 双重机会 & 进球 大 / 小  开始-->
                <div class="bet-scjhjqdx">

                </div>
                <!--  双重机会 & 进球 大 / 小  结束-->

                <!--双重机会 & 双方球队进球  开始-->
                <div class="bet-scjhsfqdjq">

                </div>
                <!--  双重机会 & 双方球队进球  结束-->

                <!-- 双重机会 & 最先进球  开始-->
                <div class="bet-scjhzxjq">

                </div>
                <!--   双重机会 & 最先进球  结束-->

                <!--  进球 大 / 小 & 进球 单 / 双  开始-->
                <div class="bet-jqdxds">

                </div>
                <!--   进球 大 / 小 & 进球 单 / 双  结束-->

                <!--   进球 大 / 小 & 最先进球  开始-->
                <div class="bet-jqdxzxjq">

                </div>
                <!--    进球 大 / 小 & 最先进球  结束-->

                <!-- 三项让球投注  开始-->
                <div class="bet-sxrqtz">

                </div>
                <!--  三项让球投注  结束-->

                <!--   赢得任一半场  开始-->
                <div class="bet-ydrybc">

                </div>
                <!--    赢得任一半场  结束-->

                <!--   赢得所有半场  开始-->
                <div class="bet-ydsybc">

                </div>
                <!--    赢得所有半场  结束-->


            </div>

            <div class="clear"></div> <!-- 清除浮动 -->

            <div  class="allsports"  onclick="goBackAction()"><a href="javascript:;"><?php echo ($gtype=='FT'?'足球':'篮球')?></a></div>
            <div id="bottom_all" class="allsports" onclick="window.location.href='sport_main.php'">所有球类</div>

        </div>


        <div class="clear"></div> <!-- 清除浮动 -->

    </div>


    <!-- 投注表单弹窗 开始-->
    <div id="div_bet" class="betBox betRecript box_off" > <!-- box_on -->
        <div class="titleBar">
            <div id="div_bet_title" class="titleBarLeft">
                <i class="iconArrow"></i>
                <div class="titleBarText">
                    <span id="bet_orderTitle">交易单</span>
                    <tt id="bet_credit" class="user_money"> </tt>
                </div>
            </div>
            <div id="plus_btn" class="plusBtn" style="display: none;"></div>
            <div class="closeBtnW" style="display: none;"></div>
        </div>
        <div id="div_content" class="content">
            <div class="betInformation">
                <!---------------------------------- normal model ---------------------------------->
                <div id="normal_order_model">

                    <!--<ul>
                        <span class="closeBtn" style="display: none;"></span>
                        <li>
                            <span id="bet_menutype" class="ord_gametype">足球  </span>
                            <span id="bet_score" class="orderScore" style=""> </span>
                            <span id="bet_league" class="ord_leag"> 超级联赛</span>
                            <span id="bet_teamname" class="ord_teamname" style="display: none;"></span>
                            <div id="bet_teamDiv">
                                <span id="bet_team_h" class="team_h">主场</span>
                                <span id="bet_con"  class="ord_con"><font class="ratio_red"> </font></span>
                                <em>VS</em>
                                <span id="bet_team_c" class="team_c">客场</span>
                                <span id="bet_con_c" class="ord_con_c" style="display: none;"></span>
                            </div>
                        </li>
                        <li>
                            <span id="bet_chose_team" class="team_chose">  </span>
                            <span id="bet_chose_con" class="ord_chose_con" style="display: none;"></span>
                            <span class="team_at">@</span>
                            <span id="bet_ior" class="ord_ior"><font class="ratio_red"> </font></span>

                        </li>
                    </ul>-->

                </div>
                <!---------------------------------- normal model ---------------------------------->
                <!---------------------------------- parlay model ---------------------------------->
                <div id="parlay_order_model" style="display:none;">
                    <div id="game_*ID*">
                        <!-- 关盘 <class="close_game"> -->
                        <ul>
                            <span id="del_*ID*" class="closeBtn"></span>
                            <li>
                                <span class="ord_gametype">*MENUTYPE*</span>
                                <span class="ord_leag">*LEAGUE*</span>
                                <div id="bet_teamDiv">
                                    <span id="bet_team_h" name="bet_team_h" class="team_h">*TEAM_H*</span>
                                    <span id="bet_con" name="bet_con" class="ord_con" style="*DISPLAY_CON*">*CON*</span>
                                    <em>v</em>
                                    <span class="team_c">*TEAM_C*</span>
                                    <span class="ord_con_c" style="*DISPLAY_CON_C*">*CON_C*</span>
                                </div>
                            </li>
                            <li>
                                <span class="team_chose">*CHOICE_TEAM*</span>
                                <span class="ord_chose_con">*CHOICE_CON*</span>
                                <span class="team_at">@</span>
                                <span class="ord_ior">*IORATIO*</span>
                                <!--赔率有变动 <font class="txtOddsChange">1.96</font>-->
                            </li>
                        </ul>
                    </div>
                </div>
                <!---------------------------------- parlay model ---------------------------------->
                <div id="parlay_order_show" style="display: none;"></div>
                <div id="div_bet_info" class="amountDiv">
                    <div class="amountInput">
                        <!--<div id="bet_gold" class="txtBlack"></div>--> <!--输入中 txtBlack / 输入中 txtGray-->
                        <input id="betGold" name="" type="number" placeholder="投注额">
                        <!--<tt id="bet_gold_tt" class="txtGray" tabindex="-1" style=""></tt>-->
                        <span id="clear_btn" class="closeBtn"></span>
                    </div>
                    <div>
                        <p>单注最低:&nbsp;</p><tt id="minbet"> </tt><br>
                        <p>单注最高:&nbsp;</p><tt id="maxbet" class="bet_maxmoney"> </tt>
                    </div>
                </div>
            </div>
            <div id="div_bet_info2" class="winAmount">
                <ul>
                    <li>
                        <p>可赢金额:</p>
                        <tt id="bet_win_gold" class="txtGreen txtBold">0.00</tt>
                    </li>
                </ul>
            </div>
            <div id="div_err" class="errorBox" style="display: none;">
                <i class="iconError"></i>
                <div id="err_msg"></div>
            </div>
        </div>
        <!--  <div id="div_calc" class="betKeyboard" style="display: none;">
              <div class="numberDiv">
                  <span id="num_1">1</span>
                  <span id="num_2">2</span>
                  <span id="num_3">3</span>
                  <span id="num_4">4</span>
                  <span id="num_5">5</span>
                  <span id="num_6">6</span>
                  <span id="num_7">7</span>
                  <span id="num_8">8</span>
                  <span id="num_9">9</span>
                  <span id="num_no" class="nonBtn"></span>
                  <span id="num_0">0</span>
                  <span id="num_x" class="delBtn"><i></i></span>
              </div>
              <div class="quickDiv">
                  <span id="add_1">+100</span>
                  <span id="add_2">+200</span>
                  <span id="add_3">+500</span>
                  <span id="add_4">+1000</span>
              </div>
          </div>-->

        <div id="div_nobet" class="noBetBox" style="display: none;"><!--无单的画面-->
            <i class="iconFlag"></i>
            <p>请把选项加入在您的注单.</p>
        </div>
        <div class="betBtnDiv noBetMode" style="display: none;"><!--无单的按钮-->
        </div>
        <div id="div_betBtn" class="betBtnDiv bettingMode">
            <span id="clear_order" class="delAllBtn whiteBtn">全删除</span>
            <span id="set_btn" class="settingBtn grayBtn"></span>
            <span id="submitSrc" href="javascript:void(0);" class="betSubmitBtn greenBtn" >
						<tt id="bet_gold2_tt">0.00</tt>
						<p>投注</p>
					</span>
            <span id="loading_bet" class="loadingBtn greenBtn" style="display: none;">
						<i class="iconLoading"></i>
					</span>
            <!-- <span id="noBet_btn" class="okBtn greenBtn">确认</span>-->
        </div>
    </div>

    <!-- 投注表单弹窗 结束-->


    <!-- 综合过关订单 按钮-->
    <div class="p3_bet_action p3_bet_icon <?php echo( $tiptype=='p3'?'':'hide-cont') ?>">
        <span id="p3_bet_number" class="p3_bet_number">0</span>
    </div>

    <!-- 底部footer -->
    <div id="footer" class="sport_footer">

    </div>

    <!-- 投注成功后区域 开始-->
    <div class="bet-sure-content">
        <div class="order_mem_data">
            <div class="bet-title bet_caption"> </div>
            <div class="bet-title">交易成功</div>
            <div class="bet-title-bottom"> 当前余额：<span class="user_money red_color"> </span> 元</div>
        </div>

        <ul class="hisInfo">
            <li> 单号：<span class="bet_order_num"></span></li>

            <ul class="bet_order_allcontent">

            </ul>

            <li ><span class="finish_bet_mon"> </span> 元</li>
            <li >可赢：<span class="finish_bet_win"></span> <a href="betrecord.php" class="to_betrecord red_color">前往交易记录</a> </li>
        </ul>
        <div class="finish_bet_btn greenBtn">确定</div>

    </div>
    <!-- 投注表单确认区域 结束-->
    <!-- 遮罩层 -->
    <div class="mask"  ></div>
    <!-- 下注遮罩层 -->
    <div class="bet_mask"  ></div>

</div>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="/js/league_list.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid ?>' ;
    var gtype = '<?php echo $gtype ?>';
    var showtype = '<?php echo $showtype?>';
    var sorttype = '<?php echo $sorttype?>';
    var mdate = '<?php echo $mdate?>';
    var autotime = '<?php echo $autotime?>' ; // 倒计时刷新时间
    var more = '<?php echo $more?>' ; // 当前游戏是今日赛事还是滚球
    var gid = '<?php echo $gid ?>' ; // 当前游戏id
    var tiptype = '<?php echo $tiptype ?>' ; // 综合过关 p3
    var M_League = '<?php echo $M_League ?>' ; // 综合过关 p3

    get_cp_blance('.user_money');
   // setFooterAction(uid,'card');  // 在 addServerUrl 前调用
    setFooterAction(uid);  // 在 addServerUrl 前调用
    autoRefreshLeagueAction(autotime) ;
    if(tiptype=='p3'){ // 综合过关
        getMoreGames(gid,gtype,showtype,tiptype,M_League) ;
        betP3ReadyAction();
        betSureAction(gtype,showtype,'p3') ;
    }else{
        getMoreGames(gid,gtype,showtype) ;
        betSureAction(gtype,showtype,'') ;
    }

    CountWinGold() ; // 可赢金额
    showBetWindow() ; // 下注

    clearInputMon();
    closeBetFinish();
    spreadAction(); // 展开与收缩列表

    // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
    function autoRefreshLeagueAction(time) {
        var $btn = $('#refresh-btn') ;
        var wait = time ;
        var refreshTime = function() {
            if (wait == 0) {
                wait = time ;
                $btn.text(wait) ;
                if(tiptype=='p3') { // 综合过关
                    getMoreGames(gid,gtype,showtype,tiptype,M_League) ;
                }else{
                    getMoreGames(gid,gtype,showtype) ;
                }

                autoRefreshLeagueAction(time);
                // location.reload(true);
            }else{
                $btn.text(wait) ;
                wait--;
                //console.log(wait+'++');
                setTimeout(refreshTime,1000) ;
            }
        }
        refreshTime();
    }

</script>

</body>
</html>
