<?php
session_start();

$showtype=isset($_REQUEST['showtype'])?$_REQUEST['showtype']:''; // today ，rb
$gtype=isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'';
$fstype=isset($_REQUEST['fstype'])?$_REQUEST['fstype']:'';
//$M_League=isset($_REQUEST['M_League'])?$_REQUEST['M_League']:''; // 综合过关需要
$tiptype=isset($_REQUEST['tiptype'])?$_REQUEST['tiptype']:'';
$gid=isset($_REQUEST['gid'])?$_REQUEST['gid']:'';
$more=isset($_REQUEST['more'])?$_REQUEST['more']:'';
$pdtype=isset($_REQUEST['pdtype'])?$_REQUEST['pdtype']:''; // 波胆
$lid=$gid; // 冠军用到
if(!$tiptype){
    $tiptype=$fstype;
}
$tplname = $_SESSION['TPL_FILE_NAME_SESSION'];

?>

<div id="body_loading" class="loading" ><i class="icon_load"></i></div>
<div id="body_show" class="box_l">
    <!--import sport html-->
    <div id="sport_content">
        <div class="head_sport TW">
            <div class="head_league ft">
                <!-- 滚球独有 -->
                <div class="box_scroll sport_scroll hide"> <?php /*echo ($showtype=='RB'?'':'hide');*/?>
                    <div class="btn_go_l"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div class="btn_go_r"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div class="menu_sport dragscroll_gtype">
                        <div class="to_sec_sports btn_le_sport <?php echo ($gtype=='FT')?'on':''; ?>" data-gtype="ft" data-rtype="<?php echo $rtype;?>" data-showtype="<?php echo $showtype;?>">
                            <i><svg><use xlink:href="#icon_ft"></use></svg></i><span>足球</span>
                        </div>
                        <div class="to_sec_sports btn_le_sport <?php echo ($gtype=='BK')?'on':''; ?>" data-gtype="bk" data-rtype="<?php echo $rtype;?>" data-showtype="<?php echo $showtype;?>">
                            <i><svg><use xlink:href="#icon_bk"></use></svg></i><span>篮球 &amp; 美式足球</span>
                        </div>
                    </div>
                </div>

                <div class="title_le">
                    <div id="back_btn" class="btn_back to_sec_sports" data-gtype="<?php echo $gtype;?>" data-rtype="<?php echo $tiptype;?>" data-showtype="<?php echo $showtype;?>"> <!--style="<?php /*echo ($showtype=='RB'?'visibility:hidden':'');*/?>"-->
                        <i class="icon_back"><svg><use xlink:href="#icon_back"></use></svg></i>
                    </div>
                    <div id="total_league_title" class="title_le_sport" style="display: none;">
                        <tt id="showtype_now">今日</tt>
                        <span id="gtype_now">足球</span>
                    </div>

                    <div id="league_title" class="title_le_sport" style="">
                        <tt id="league_gtype"><?php echo ($gtype=='FT')?'足球':'篮球 & 美式足球'; ?> </tt>
                        <span id="league_name">我的参赛表</span>
                    </div>
                    <!--<div id="sel_sort" class="btn_le_sort"><i id="icon_sort" class="icon_sort_cup"></i></div>-->
                    <div id="sel_date" style="display: none;" class="btn_le_cla"><i id="date_icon" class="icon_calendar"></i></div>
                    <div id="showPLimit" style="display: none;" class="text_p_in"><p id="game_parlay"></p><p>串</p><p>1</p></div>
                </div>

                <div id="league_tab" class="box_scroll market_scroll" style="display: none;">
                    <div id="tab_left" class="btn_go_l"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div id="tab_right" class="btn_go_r"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div id="tab_scroll" class="box_slide dragscroll_tab">
                        <div id="league_tab_rb" class="btn_market" style="display: none;">滚球</div>
                        <div id="league_tab_game" class="btn_market on">赛事</div>
                        <div id="league_tab_fs" class="btn_market">冠军</div>
                        <div id="league_tab_fantasy" class="btn_market" style="display: none;">梦幻赛</div>
                    </div>
                </div>
            </div>
            <div id="total_tab" class="box_market" style="">
                <div id="tab_main" class="btn_betmain <?php echo ($pdtype!='pd'?'on':'')?>" onclick="goToFirstBet(gtype,showtype,more,gid,fstype,M_League)">主要玩法</div>
                <div id="tab_pd_play" class="btn_betcorr <?php echo ($pdtype=='pd'?'on':'')?>" onclick="goToFirstBet(gtype,showtype,more,gid,fstype,M_League,'pd')" style="<?php echo (($gtype=='BK' || $fstype=='p3' || $fstype=='champion')?'display:none':'')?>">波胆</div>
            </div>
        </div>
    </div>

    <!--盤面用loading-->
    <div id="game_loading" class="loading" style="display: none;"><i class="icon_load"></i></div>

    <!--盤面-->
    <div id="main_content" class="content_sport">
        <div class="box_league box_today ft CN">

            <!-- no data -->
            <div id="div_nodata" class="NoEvent_game no_event" style="display: none;"><i></i><span>目前没有任何赛事。</span></div>

            <!-- league content -->
            <div id="sport_div_show" class="box_outer ft_outer">


            </div>
        </div>

        </div>
    </div>
</div>

<script>
    var gtype='<?php echo $gtype;?>';
    var fstype='<?php echo $fstype;?>';
    var gid='<?php echo $gid;?>';
    var showtype='<?php echo $showtype;?>';
    var M_League=localStorage.getItem('match_league') || ''; // 综合过关需要
    var tiptype='<?php echo $tiptype;?>';
    var more='<?php echo $more;?>';
    var p_dtype='<?php echo $pdtype;?>';
    var autotime=180; // 早盘
    var wh_type='future';
    localStorage.setItem('ALL_GAME_GID',gid); // 用于返回上一页

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
    getNewGameDetails(gtype,gid,showtype,M_League,tiptype);
    // type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
    betSureAction(gtype,showtype,tiptype);

    CountWinGold() ; // 可赢金额
    showBetWindow() ; // 下注
    spreadAction(); // 展开与收缩列表
    betP3ReadyAction(); // 综合过关独有
    autoRefreshListAction(autotime);
    closeBetAction(); // 此页面关闭下注

    // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
    function autoRefreshListAction(time) {
        //let $btn = $('#refresh-btn') ;
        let wait = time ;
        let refreshTime = function() {
            if (wait == 0) {
                wait = time ;
                //$btn.text(wait) ;
                getNewGameDetails(gtype,gid,showtype,M_League,tiptype) ;
                autoRefreshListAction(time);
            }else{
                //$btn.text(wait) ;
                wait--;
                sportTimerAc = setTimeout(refreshTime,1000) ;
            }
        }
        refreshTime();
    }

</script>



