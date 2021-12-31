<?php
session_start();

$showtype=isset($_REQUEST['showtype'])?$_REQUEST['showtype']:''; // today ，rb
$gtype=isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'';
$fstype=isset($_REQUEST['fstype'])?$_REQUEST['fstype']:'';
$M_League=isset($_REQUEST['M_League'])?$_REQUEST['M_League']:'';
$tiptype=isset($_REQUEST['tiptype'])?$_REQUEST['tiptype']:'';
$isMaster=isset($_REQUEST['isMaster'])?$_REQUEST['isMaster']:'';
$gid=isset($_REQUEST['gid'])?$_REQUEST['gid']:'';
$allgid=isset($_REQUEST['allgid'])?$_REQUEST['allgid']:''; // 用于返回按钮

?>

<div id="body_loading" class="loading" ><i class="icon_load"></i></div>
<div id="body_show" class="box_l">
    <!--import sport html-->
    <div id="sport_content">
        <div class="head_sport TW">
            <div id="head_league" class="head_league ft"><!--class幫帶入各球類代碼-->

                <div class="title_le">
                    <div id="back_btn" class="btn_back" onclick="goToFirstBet('<?php echo $gtype;?>','<?php echo $showtype;?>','<?php echo ($showtype=='RB'?'r':'s');?>','<?php echo $allgid;?>','<?php echo $fstype;?>','<?php echo $M_League;?>')" >
                        <i class="icon_back"><svg><use xlink:href="#icon_back"></use></svg></i>
                    </div>

                    <div id="league_title" class="title_le_sport">
                        <span id="lea_title_gtype">我的参赛表</span>
                    </div>
                </div>
            </div>

            <?php
                if($gtype=='BK' && $showtype=='RB'){
            ?>
            <!-- 篮球滚球 -->
            <div class="box_scoboard <?php echo mb_strtolower($gtype);?>"> <!-- 半場樣式 新增class="half"-->
                <div id="box_scostate" class="box_scostate">
                    <tt id="game_live" class="text_time_go">第二节</tt>
                    <tt id="game_time" class="text_time_go">00:01</tt>
                    <i id="midfield" class="icon_n" style="display: none;"></i>
                </div>
                <div id="box_sco_bk" class="box_sco_bk">
                    <span id="sc_Q1" class="sco_bk bk_q">第一节</span>
                    <span id="sc_Q2" class="sco_bk bk_q">第二节</span>
                    <span id="sc_Q3" class="sco_bk bk_q">第三节</span>
                    <span id="sc_Q4" class="sco_bk bk_q">第四节</span>
                    <span id="sc_OT" class="sco_bk bk_ot">加时</span>
                    <span id="sc_H1" class="sco_bk bk_h1">上半场</span>
                    <span id="sc_H2" class="sco_bk bk_h2">下半场</span>
                </div>
                <div id="box_sco_l" class="box_sco_l">
                    <div class="box_scoteam team_h">
                        <span id="game_score_h" class="text_point">0</span>
                        <span id="game_team_h" class="text_team">主场</span>
                        <!-- 主队小节比分 -->
                        <div class="box_sco_bk box_sco_bk_h">
                            <!--<span id="sc_Q1_H" class="sco_bk bk_q">14</span>
                            <span id="sc_Q2_H" class="sco_bk bk_q on">14</span>
                            <span id="sc_Q3_H" class="sco_bk bk_q"></span>
                            <span id="sc_Q4_H" class="sco_bk bk_q"></span>
                            <span id="sc_OT_H" class="sco_bk bk_ot"></span>
                            <span id="sc_H1_H" class="sco_bk bk_h1 on">28</span>
                            <span id="sc_H2_H" class="sco_bk bk_h2"></span>-->
                        </div>
                    </div>

                    <div class="box_scoteam team_c">
                        <span id="game_score_c" class="text_point">0</span>
                        <span id="game_team_c" class="text_team">客场</span>
                        <!-- 客队小节比分 -->
                        <div class="box_sco_bk box_sco_bk_c">
                           <!-- <span id="sc_Q1_A" class="sco_bk bk_q">12</span>
                            <span id="sc_Q2_A" class="sco_bk bk_q on">11</span>
                            <span id="sc_Q3_A" class="sco_bk bk_q"></span>
                            <span id="sc_Q4_A" class="sco_bk bk_q"></span>
                            <span id="sc_OT_A" class="sco_bk bk_ot"></span>
                            <span id="sc_H1_A" class="sco_bk bk_h1 on">23</span>
                            <span id="sc_H2_A" class="sco_bk bk_h2"></span>-->
                        </div>
                    </div>

                </div>
                <div id="box_sco_point" class="box_sco_point">
                    <div id="320_sc_Q1" class="point_bk_s bk_q">1节 <tt id="320_sc_Q1_H">14</tt>-<tt id="320_sc_Q1_A">12</tt></div>
                    <div id="320_sc_Q2" class="point_bk_s bk_q on">2节 <tt id="320_sc_Q2_H">14</tt>-<tt id="320_sc_Q2_A">11</tt></div>
                    <div id="320_sc_Q3" class="point_bk_s bk_q" style="display: none;">3节 <tt id="320_sc_Q3_H"></tt>-<tt id="320_sc_Q3_A"></tt></div>
                    <div id="320_sc_Q4" class="point_bk_s bk_q" style="display: none;">4节 <tt id="320_sc_Q4_H"></tt>-<tt id="320_sc_Q4_A"></tt></div>
                    <div id="320_sc_H1" class="point_bk_s" style="display: none;">上半场 <tt id="320_sc_H1_H"></tt>-<tt id="320_sc_H1_A"></tt></div>
                    <div id="320_sc_H2" class="point_bk_s" style="display: none;">下半场 <tt id="320_sc_H2_H"></tt>-<tt id="320_sc_H2_A"></tt></div>
                    <div id="320_sc_OT" class="point_bk_s" style="display: none;">加时 <tt id="320_sc_OT_H"></tt>-<tt id="320_sc_OT_A"></tt></div>
                </div>
            </div>

            <?php
            }else{
            ?>
                <!-- 足球 -->
                <div class="box_scoboard_r <?php echo mb_strtolower($gtype);?>" >
                        <?php
                        if($showtype=='RB'){ // 滚球
                            echo '<div id="box_scostate" class="box_scostate">
                        <tt class="text_time_go"><span id="game_live"></span> <span id="game_time">00:00</span></tt>
                        <i id="midfield" class="icon_n" style=""></i>
                    </div>';
                        }else{
                            echo ' <div class="box_scostate">
                        <tt id="game_time" class="text_time">00:00</tt>
                        <i id="midfield" class="icon_n" style="display:none"></i>
                    </div>';
                        }
                        ?>

                        <div id="box_sco_l" class="box_sco_l">
                            <div class="box_scoteam team_h">
                                <span id="pk_h" class="icon_serve"></span><!-- 發球方 Add class="on" -->
                                <span id="redcard_h" class="icon_redcard"></span><!-- show red card Add class="on" -->
                                <span id="game_score_h" class="text_point last_goal"> </span><!-- 最後入球 Add class="last_goal" -->
                                <span id="game_team_h" class="text_team"> 主场 </span><!-- 強隊 Add class="strong_team" -->
                            </div>
                            <span class="box_sco_center">-</span>
                            <!--<div class="box_sco_vs">VS</div>-->
                            <div class="box_scoteam team_c">
                                <span id="pk_c" class="icon_serve"></span>
                                <span id="redcard_c" class="icon_redcard"></span>
                                <span id="game_score_c" class="text_point"> </span>
                                <span id="game_team_c" class="text_team"> 客场 </span>
                            </div>
                        </div>
                    </div>

            <?php } ?>

        </div>
    </div>

    <!--盤面用loading-->
    <div id="game_loading" class="loading" style="display: none;"><i class="icon_load"></i></div>

    <!--盤面-->
    <div id="main_content" class="content_sport">
        <div class="box_league box_today ft CN">

            <!-- no data -->
            <div id="div_nodata" class="NoEvent_game no_event" style="display: none;"><i></i><span>目前没有任何赛事。</span></div>

            <!-- 赛事列表 -->
            <div id="sport_div_show" class="box_inn ft_inn">

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

        </div>

    </div>
</div>

<script>
    var FStype='<?php echo $gtype;?>';
    var gid='<?php echo $gid;?>';
    var showtype='<?php echo $showtype;?>';
    var M_League='<?php echo $M_League;?>';
    var tiptype='<?php echo $tiptype;?>';
    var isMaster='<?php echo $isMaster;?>';
    var autotime=180; // 早盘
    var wh_type='future';

    if(showtype=='BK'){
       showtype = 'FT' ;
    }
    if(showtype=='BU'){
       showtype = 'FU' ;
    }
    if(showtype=='RB'){ // 倒计时 滚球
      autotime = 20 ; // 刷新时间
      wh_type = 'rb';
    }else if(showtype=='FT' || showtype=='BK'){ // 今日
      autotime = 60 ; // 刷新时间
      wh_type = 'today';
    }
    localStorage.setItem('footBallMaster',''); // 还原
    clearTimeout(sportTimerAc); // 清理体育定时器
    getMoreGames(gid,gtype,showtype,tiptype,M_League,isMaster) ;

    // type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
    betSureAction(FStype,showtype,tiptype);

    CountWinGold() ; // 可赢金额
    showBetWindow() ; // 下注
    spreadAction(); // 展开与收缩列表
    autoRefreshListAction(autotime);

    // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
    function autoRefreshListAction(time) {
        //let $btn = $('#refresh-btn') ;
        let wait = time ;
        let refreshTime = function() {
            if (wait == 0) {
                wait = time ;
                getMoreGames(gid,gtype,showtype,tiptype,M_League,isMaster) ;
                autoRefreshListAction(time);
            }else{
                wait--;
                sportTimerAc = setTimeout(refreshTime,1000) ;
            }
        }
        refreshTime();
    }

</script>



