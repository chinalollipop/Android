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
	$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 当前游戏类型
    $more = isset($_REQUEST['more'])?$_REQUEST['more']:'' ; // 当前游戏是今日赛事还是滚球
    $gid = isset($_REQUEST['gid'])?$_REQUEST['gid']:''; // 当前游戏id
    $fstiptype = isset($_REQUEST['fstiptype'])?$_REQUEST['fstiptype']:''; // 冠军
    $showtype = $type ;
    $tip_showtype = $showtype ; // 用于标签判断
    $gtype = isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'' ;
    $mdate = isset($_REQUEST['mdate'])?$_REQUEST['mdate']:'' ; // 时间
    $FStype = isset($_REQUEST['FStype'])?$_REQUEST['FStype']:''; // 冠军标志 FT 足球 ,BK 篮球
    $M_League = isset($_REQUEST['M_League'])?$_REQUEST['M_League']:''; // 冠军标志联赛名称
    $fsshowtype = isset($_REQUEST['showtype'])?$_REQUEST['showtype']:''; // 冠军 和综合过关 showtype ，早盘需要传 future
    $tiptype = isset($_REQUEST['tiptype'])?$_REQUEST['tiptype']:''; // 冠军 champion ，综合过关 p3

    if($type=='RB' || $type=='FT'){
        $list_type = $gtype;
    }else{
        $list_type = $type;
    }

    if($FStype){ // 冠军
        $gtype = $FStype ;
        $tip_showtype = $fstiptype ;
        $showtype = $fstiptype ;
        $list_type = $FStype ;
    }
    if($showtype=='RB'){ // 倒计时 滚球
        $autotime = 20 ; // 刷新时间
    }else if($showtype=='FT' || $showtype=='BK'){ // 今日
        $autotime = 60 ; // 刷新时间
    }else{ // 早盘
        $autotime = 90 ; // 刷新时间
    }
$cpUrl = $_SESSION['cpUrl'] ; // 彩票链接

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
    <div id="container">

        <!-- 顶部导航栏 -->
        <div class="header sport_header">
            <div class="header_left">
                <a href="sport.php?gtype=<?php echo $gtype;?>&showtype=<?php echo $showtype;?>&sorttype=league&mdate=<?php echo $mdate;?>&tiptype=<?php echo $tiptype;?>" class="back-active sport_back_icon" ></a>

            </div>
            <div class="header-center">
                <a  href="sport_main.php?showtype=rb" class="header_live <?php if($tip_showtype=='RB'){echo 'active';} ?>" data-type="RBMATCH" ><i class="rb_running_logo"></i>滚球</a>
                <a  href="sport_main.php?showtype=today" class="header_today <?php if($tip_showtype=='FT' || $tip_showtype=='BK'){echo 'active';} ?>" data-type="TODAYMATCH" >今日</a>
                <a  href="sport_main.php?showtype=future" class="header_early <?php if($tip_showtype=='FU' || $tip_showtype=='BU'){echo 'active';} ?>" data-type="FUTUREMATCH" >早盘</a>
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
                <div class="sportNav_tip">
                    <ul>
                        <li onclick="window.location.href='sport_main.php';"><span class="football-icon"></span><p>足球</p></li>
                        <li onclick="window.location.href='sport_main.php';"><span class="basketball-icon"></span><p>篮球</p></li>
                        <li onclick="ifHasLogin('gameresult.php','','<?php echo $uid?>')"><span class="gameresult-icon"></span><p>赛果</p></li>
                       <!-- <li onclick="setPublicPop('敬请期待!')"><span class="tnball-icon"></span><p>网球</p></li>
                        <li onclick="setPublicPop('敬请期待!')"><span class="vbball-icon"></span><p>棒球</p></li>-->
                        <li onclick="ifHasLogin('/','','<?php echo $uid?>')"><span class="live-icon"></span><p>真人荷官</p></li>
                        <?php
                        if(TPL_FILE_NAME != '0086dj'){
                        ?>
                        <li onclick="ifHasLogin('/<?php echo TPL_NAME;?>games.php','','<?php echo $uid?>')"><span class="games-icon"></span><p>老虎机</p></li>
                        <li onclick="ifHasLogin('<?php echo $cpUrl;?>','win','<?php echo $uid?>')"><span class="lottery-icon"></span><p>彩票</p></li>
                        <li onclick="ifHasLogin('/ky/','','<?php echo $uid?>')"><span class="chess-icon-ky"></span><p>开元棋牌</p></li>
                        <!--<li onclick="ifHasLogin('/hgqp/','','<?php /*echo $uid*/?>')"><span class="chess-icon-hg"></span><p>皇冠棋牌</p></li>-->
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="sportNav_title">
                    <div id="lea_title_gtype"  class="game_title"> </div>
                    <div id="refresh" class="refresh" onclick="getNewGameDetails('<?php echo $list_type?>','<?php echo $more?>','<?php echo $gid?>','<?php echo $M_League?>','<?php echo $fsshowtype?>','<?php echo $tiptype?>');">
                        <span id="refresh-btn"> </span>
                    </div>
                </div>
            </div>

            <!-- 投注列表区域 开始-->
            <div class="bet-content">

                <!-- 有赛事 容器-->
                <div class="has_sport_matches" >
                    <!-- 标题栏-->
                    <?php if($showtype !='RB'){ ?>  <!--滚球不展示-->
                        <div class="hdp_header">
                            <table border="0" cellspacing="0" cellpadding="0" class="tool_table">
                                <tbody><tr>
                                    <td id="change_r" class="h_r <?php echo $tiptype==''?'hdp_up':'';?>" data-type="ALLMATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')">让球 &amp; 大小</td>
                                    <td id="change_p" class="h_p <?php if($tiptype=='p3'){ echo 'hdp_up';}?>" data-type="P3MATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')" >综合过关</td>
                                    <td id="change_fs" class="h_fs <?php if($tiptype=='champion'){ echo 'hdp_up';}?>" data-type="CHAMPION" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')">冠军</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    <?php } ?>

                </div>

                <!-- 无赛事 -->
                <div id="div_nodata" name="div_nodata" class="NoEvent_game" style="display:none;">无赛程</div>

                <!-- 赛事列表 -->
                <div id="sport_div_show" >


<!--                    <table border="0" cellspacing="0" cellpadding="0" class="game_tab">-->
<!--                        <tbody><tr class="oddstitle">-->
<!--                            <td class="odds_bg">-->
<!--                                <div class="odds_score"><span class="odds_live">滚球</span><span style="display:none" =""="" class="mini_tv"></span></div>-->
<!--                                <div class="odds_re_time">-->
<!--                                    <span class="odds_min">14:15</span>-->
<!--                                    <div id="icon_N" class="icon_N" style="display:none"><div class="odds_mid"> </div></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td class="ratio_headA"><div class="headA_line">让球</div></td>-->
<!--                            <td class="ratio_headB">大 / 小</td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr>-->
<!--                            <td colspan="3" class="gameplay" style="display:;"> 角球数 </td>-->
<!--                        </tr>-->
<!---->
<!---->
<!--                        <tr class="oddsdetails td_first">-->
<!--                            <td class="team_box">-->
<!--                                <div name="dynamicFont" class="team_name"><span>赫塔费</span></div>-->
<!---->
<!--                            </td>-->
<!---->
<!---->
<!--                            <td id="h_rh_3314138" name="h_rh_3314138" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con">0 / 0.5</div>-->
<!--                                    <div class="*STYLE_RH*"><font class="ratio">0.98</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td id="c_ouc_3314138" name="c_ouc_3314138" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"><span class="con_ou">大</span> 10</div>-->
<!--                                    <div class="*STYLE_OUC*"><font class="ratio">1.06</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr class="oddsdetails">-->
<!--                            <td class="team_box">-->
<!--                                <div name="dynamicFont" class="team_name"><span>埃瓦尔</span></div>-->
<!---->
<!--                            </td>-->
<!---->
<!--                            <td id="c_rc_3314138" name="c_rc_3314138" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"></div>-->
<!--                                    <div class="*STYLE_RC*"><font class="ratio">0.86</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td id="h_ouh_3314138" name="h_ouh_3314138" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"><span class="con_ou">小</span> 10</div>-->
<!--                                    <div class="*STYLE_OUH*"><font class="ratio">0.76</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                        <!-- bet -->-->
<!--                        <tr>-->
<!--                            <td colspan="3">-->
<!---->
<!--                                <div id="more_3314138" name="more_3314138" class="bet">-->
<!--                                    <span>9</span> 玩法-->
<!--                                    <div class="list_arr_cen"> </div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->
<!---->
<!--                    <table border="0" cellspacing="0" cellpadding="0" class="game_tab">-->
<!--                        <tbody><tr class="oddstitle">-->
<!--                            <td class="odds_bg">-->
<!--                                <div class="odds_score"><span class="odds_live">滚球</span><span style="" class="mini_tv"></span></div>-->
<!--                                <div class="odds_re_time">-->
<!--                                    <span class="odds_min">16:15</span>-->
<!--                                    <div id="icon_N" class="icon_N" style="display:none"><div class="odds_mid"> </div></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td class="ratio_headA"><div class="headA_line">让球</div></td>-->
<!--                            <td class="ratio_headB">大 / 小</td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr>-->
<!--                            <td colspan="3" class="gameplay" style="display:none;"> *GAMEPLAY* </td>-->
<!--                        </tr>-->
<!---->
<!---->
<!--                        <!-- odds details -->-->
<!--                        <tr class="oddsdetails td_first">-->
<!--                            <td class="team_box">-->
<!--                                <div name="dynamicFont" class="team_name"><span>雷加利斯 </span></div>-->
<!---->
<!--                            </td>-->
<!---->
<!---->
<!--                            <td id="h_rh_3314140" name="h_rh_3314140" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"></div>-->
<!--                                    <div class="*STYLE_RH*"><font class="ratio">1.03</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td id="c_ouc_3314140" name="c_ouc_3314140" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"><span class="con_ou">大</span> 2 / 2.5</div>-->
<!--                                    <div class="*STYLE_OUC*"><font class="ratio">1.11</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                        <tr class="oddsdetails">-->
<!--                            <td class="team_box">-->
<!--                                <div name="dynamicFont" class="team_name"><span>皇家苏斯达 </span></div>-->
<!---->
<!--                            </td>-->
<!---->
<!--                            <td id="c_rc_3314140" name="c_rc_3314140" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con">0 / 0.5</div>-->
<!--                                    <div class="*STYLE_RC*"><font class="ratio">0.90</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!---->
<!--                            <td id="h_ouh_3314140" name="h_ouh_3314140" class="odds_box">-->
<!--                                <div class="ratio_box ">-->
<!--                                    <div class="con"><span class="con_ou">小</span> 2 / 2.5</div>-->
<!--                                    <div class="*STYLE_OUH*"><font class="ratio">0.82</font></div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->

<!--                        <tr>-->
<!--                            <td colspan="3">-->

<!--                                <div id="more_3314140" name="more_3314140" class="bet">-->
<!--                                    <span>79</span> 玩法-->
<!--                                    <div class="list_arr_cen"> </div>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                        </tbody>-->
<!--                    </table>-->

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

                       <!-- <ul>
                            <span class="closeBtn" style="display: none;"></span>
                            <li>
                                <span id="bet_menutype" class="ord_gametype">足球  </span>
                                <span id="bet_score" class="orderScore" style=""> </span>
                                <span id="bet_league" class="ord_leag">超级联赛</span>
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
                                <span id="bet_chose_team" class="team_chose"> </span>
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
                  <!--  <li class="finish_bet_league"> </li>
                    <li class="finish_bet_team"> </li>
                    <li class="finish_bet_content"> </li>-->
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
        var uid = '<?php echo $uid?>' ;
        var list_type = '<?php echo $list_type?>';
        var showtype = '<?php echo $showtype?>';
        var sorttype = '<?php echo $sorttype?>';
        var mdate = '<?php echo $mdate?>';
        var autotime = '<?php echo $autotime?>' ; // 倒计时刷新时间
        var more = '<?php echo $more?>' ; // 当前游戏是今日赛事还是滚球
        var gid = '<?php echo $gid ?>' ; // 当前游戏id
        var FStype = '<?php echo $FStype ?>' ; // 冠军标志
        var tiptype =  '<?php echo $tiptype?>' ;  // 当前标志 p3 综合过关，champion 冠军
        var M_League = '<?php echo $M_League ?>' ; // 冠军标志
        var fsshowtype = '<?php echo $fsshowtype ?>' ; // 冠军标志

        get_cp_blance('.user_money');
        //setFooterAction(uid,'card');  // 在 addServerUrl 前调用
        setFooterAction(uid);  // 在 addServerUrl 前调用
        autoRefreshLeagueAction(autotime) ;

        // type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
        if(tiptype=='champion'){ // 冠军
            getNewGameDetails(FStype,'','',M_League,fsshowtype,'champion') ;
            betSureAction(list_type,showtype,'champion') ;
        }else if(tiptype=='p3'){ // 综合过关
            getNewGameDetails(FStype,'','',M_League,fsshowtype,'p3') ;
            betSureAction(list_type,showtype,'p3') ;
            betP3ReadyAction() ;
        } else{
            getNewGameDetails(list_type,more,gid) ;
            betSureAction(list_type,more,'');
        }
        spreadAction() ;
        CountWinGold();
        showBetWindow();

        clearInputMon();
        closeBetFinish();

        // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
        function autoRefreshLeagueAction(time) {
            var $btn = $('#refresh-btn') ;
            var wait = time ;
            var refreshTime = function() {
                if (wait == 0) {
                    wait = time ;
                    $btn.text(wait) ;

                    if(tiptype=='champion') { // 冠军
                        getNewGameDetails(FStype,'','',M_League,fsshowtype,'champion') ;
                    }else if(tiptype=='p3'){ // 综合过关
                        getNewGameDetails(FStype,'','',M_League,fsshowtype,'p3') ;
                    } else{
                        getNewGameDetails(list_type,more,gid) ;
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
