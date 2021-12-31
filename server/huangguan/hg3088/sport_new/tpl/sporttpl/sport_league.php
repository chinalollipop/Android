<?php
session_start();
include "../../../common/function.php";

$wh_showtype = $showtype=isset($_REQUEST['showtype'])?$_REQUEST['showtype']:''; // today ，rb
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'';
$gtype=isset($_REQUEST['gtype'])?$_REQUEST['gtype']:'FT'; // 默认 FT
$mdate=isset($_REQUEST['mdate'])?$_REQUEST['mdate']:''; // 早盘日期
$showtype=strtoupper($showtype); // 转成大写
$gtype=strtoupper($gtype); // 转成大写
$sorttype=isset($_REQUEST['sorttype'])?$_REQUEST['sorttype']:'league'; // 排序，默认 league，time
if($gtype==''){
    $gtype = 'FT';
}
if($showtype=='TODAY'){
    $showtype='FT';
}
$resdata = array();
// 近一周
$weekarray=array("日","一","二","三","四","五","六");
for($datei=0;$datei<7;$datei++){ // 从明天开始，往后 7 天数据
    $resdata[]=array(
        'value'=>date('Y-m-d',time()+$datei*24*60*60),
        'str'=>date('m'.'月'.'d'.'日',time()+$datei*24*60*60),
        'week'=>"星期".$weekarray[date("w",strtotime(date('Y-m-d',time()+$datei*24*60*60)))]
    );
}
if($wh_showtype=='fu'){
    $wh_showtype='future';
}


?>

<!-- PC left content / MOBILE center content-->

    <div id="body_loading" class="loading"><i class="icon_load"></i></div>
    <div id="body_show" class="box_l">

        <!--import sport html-->
        <div id="sport_content" class="sport_content">
            <div class="head_sport TW">
                <div class="head_league ft"><!--class幫帶入各球類代碼-->
                    <div class="box_scroll sport_scroll">
                        <div class="btn_go_l"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                        <div class="btn_go_r"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                        <div class="menu_sport dragscroll_gtype">
                            <div class="to_sec_sports btn_le_sport <?php echo ($gtype=='FT')?'on':''; ?>" data-gtype="ft" data-rtype="<?php echo $rtype;?>" data-showtype="<?php echo $showtype;?>">
                                <i><svg><use xlink:href="#icon_ft"></use></svg></i><span>足球</span>
                            </div>
                            <div class="to_sec_sports btn_le_sport <?php echo ($gtype=='BK')?'on':''; ?>" data-gtype="bk" data-rtype="<?php echo $rtype;?>" data-showtype="<?php echo $showtype;?>">
                                <i><svg><use xlink:href="#icon_bk"></use></svg></i><span>篮球 &amp; 美式足球</span>
                            </div>
                            <div id="symbol_tn" class="btn_le_sport"><i><svg><use xlink:href="#icon_tn"></use></svg></i><span>网球</span></div>
                            <div id="symbol_vb" class="btn_le_sport"><i><svg><use xlink:href="#icon_vb"></use></svg></i><span>排球</span></div>
                            <div id="symbol_bm" class="btn_le_sport"><i><svg><use xlink:href="#icon_bm"></use></svg></i><span>羽毛球</span></div>
                            <div id="symbol_tt" style="display: none" class="btn_le_sport"><i><svg><use xlink:href="#icon_tt"></use></svg></i><span>乒乓球</span></div>
                            <div id="symbol_bs" class="btn_le_sport"><i><svg><use xlink:href="#icon_bs"></use></svg></i><span>棒球</span></div>
                            <div id="symbol_sk" style="display: none" class="btn_le_sport"><i><svg><use xlink:href="#icon_sk"></use></svg></i><span>斯诺克 / 台球</span></div>
                            <div id="symbol_op" class="btn_le_sport"><i><svg><use xlink:href="#icon_op"></use></svg></i><span>其他</span></div>
                        </div>
                    </div>
                    <div class="title_le">
                        <!--<div id="back_btn" name="back_btn" class="btn_back"><i class="icon_back"><svg><use xlink:href="#icon_back"></use></svg></i></div>-->
                        <div id="total_league_title" class="title_le_sport">
                            <tt id="showtype_now"><?php echo $rtype=='p3'?'综合过关':( ($showtype=='FT' || $showtype=='BK')?'今日':($showtype=='RB'?'滚球':'早盘') ); ?></tt>
                            <span id="gtype_now"><?php echo ($gtype=='FT')?'足球':'篮球 & 美式足球'; ?> </span>
                        </div>

                        <div id="league_title" class="title_le_sport" style="display: none;">
                            <tt id="league_gtype">足球</tt>
                            <span id="league_name">联盟名称</span>
                        </div>
                        <div id="sel_sort" style="display: none;" class="btn_le_sort" onclick="getLeagueMatches(gtype,showtype,'league',mdate,tiptype)" title="按联盟排序" ><i class="icon_sort_cup"></i></div> <!-- 排序 -->
                        <div id="sel_time" class="btn_le_sort" onclick="getLeagueMatches(gtype,showtype,'time',mdate,tiptype)" title="按时间排序" ><i class="icon_sort_time"></i></div> <!-- 时间 -->
                        <!-- 早盘和冠军 -->
                        <div id="sel_date" class="btn_le_cla" onclick="$('#div_date').toggle()" title="选择日期" style="<?php echo ($showtype=='FU' || $rtype=='p3')?'':'display:none;';?>"><i id="date_icon" class="icon_calendar"></i></div>

                        <div id="showPLimit" style="display: none;" class="text_p_in"><p id="game_parlay"></p><p>串</p><p>1</p></div>
                    </div>

                    <div id="league_tab" class="box_scroll market_scroll">
                        <div id="tab_left" class="btn_go_l"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                        <div id="tab_right" class="btn_go_r"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                        <div id="tab_scroll" class="box_slide dragscroll_tab">
                            <div id="league_tab_rb" class="btn_market" style="display: none;">滚球</div>
                            <div id="league_tab_game" class="to_sec_sports btn_market <?php echo ($rtype != 'champion'?'on':'');?>" data-gtype="<?php echo $gtype;?>" data-rtype="r" data-showtype="<?php echo $showtype;?>" >赛事</div>
                            <!-- 滚球没有冠军 -->
                            <div id="league_tab_fs" class="to_sec_sports btn_market <?php echo ($rtype == 'champion'?'on':'');?>" data-gtype="<?php echo $gtype;?>" data-rtype="champion" data-showtype="<?php echo $showtype;?>" style="<?php echo( ($showtype=='RB' || $rtype=='p3')?'display: none':'');?>">冠军</div>
                            <div id="league_tab_fantasy" class="btn_market" style="display: none;">梦幻赛</div>
                        </div>
                    </div>
                </div>
               <!-- <div id="total_tab" class="box_market" style="display: none;">
                    <div id="tab_main" class="btn_betmain on">主要玩法</div>
                    <div id="tab_pd" class="btn_betcorr">波胆</div>
                </div>-->
            </div>
        </div>

        <!--盤面-->
        <div class="content_sport">
            <!--import 盤面 html-->
            <div id="main_content" class="main_sport" >

                <!-- 早盘/综合过关 日期 开始 -->
                <div id="div_date" class="box_scroll date_scroll" style="">
                    <div id="date_left" class="btn_go_l"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div id="date_right" class="btn_go_r"><svg><use xlink:href="#icon_rightarr"></use></svg></div>
                    <div id="date_scroll" class="menu_date dragscroll_date">
                        <label id="date_total">
                            <div class="btn_date <?php echo ($mdate==''?'on':'');?>" >
                                <tt>所有日期</tt>
                            </div>
                            <?php
                                foreach ($resdata as $key=>$item){
                                    echo ' <div class="btn_date" data-value="'.$item['value'].'">
                                                <tt class="text_week">'.$item['week'].'</tt>
                                                <tt class="text_date">'.mb_strcut($item['str'],5,2).'</tt>
                                                <tt class="text_month">'.mb_strcut($item['str'],0,2).'月</tt>
                                            </div>';
                                }
                            ?>

                           <!-- <div id="btn_date_future" class="btn_date">
                                <tt>未来</tt>
                            </div>-->
                        </label>
                    </div>
                </div>
                <!-- 早盘/综合过关 日期 结束 -->

                <div class="box_filter today_filter ft">
                    <div id="div_nodata" class="NoEvent_game no_event" style="display: none;"><i></i><span>目前没有任何赛事。</span></div>

                    <div id="div_hasdata">
                        <?php
                        if($showtype=='RB' || $showtype=='FT' || $showtype=='today' || $rtype=='p3'){
                            echo ' <div id="div_coupon" class="popular_league">
                            <div class="show_hide_lea btn_title_le"><tt>最火</tt></div>
                            <div class="box_le_filter">
                                <div class="btn_coupon1 btn_event" onclick="goToFirstBet(\''.$gtype.'\',\''.$showtype.'\',\'r\',\'\',\''.$rtype.'\',\'\')">
                                    <div class="bg_event"></div>
                                    <span class="title_event">所有赛事</span>
                                </div>
                            </div>
                        </div>';
                        }
                        ?>

                        <!-- 联赛数据区域 -->
                        <div class="league_list classic_league">


                        </div>


                    </div>

                    <div id="div_gmt" class="notice_text">
                        <span><i></i><tt>今日赛事显示时区为GMT-4</tt></span>
                    </div>

                </div>
            </div>

        </div>

    </div>

<script>
    var showtype='<?php echo $showtype;?>';
    var wh_showtype='<?php echo $wh_showtype;?>';
    var tiptype='<?php echo $rtype;?>';
    var gtype='<?php echo $gtype;?>';
    var sorttype='<?php echo $sorttype;?>'; // league 联盟排序
    var mdate='<?php echo $mdate;?>';
    var autotime=180; // 早盘
    var wh_type='future';

    if(showtype=='BU'){
        showtype = 'FU' ;
    }
    if(showtype=='BK'){
        showtype = 'FT' ;
    }

    if(showtype=='RB'){ // 倒计时 滚球
        autotime = 20 ; // 刷新时间
        wh_type = 'rb';
    }else if(showtype=='FT' || showtype=='BK'){ // 今日
        autotime = 60 ; // 刷新时间
        wh_type = 'today';
    }
    clearTimeout(sportTimerAc); // 清理体育定时器

    getPageMaintenance(wh_type).then(res=>{
        if(res.data.state==1){ // 维护
            $('.middle_content .middle_sport_content').html(returnWhStr(res.data.title,res.data.content));
        }else {
            // if(showtype=='RB'){ // 滚球直接进入投注页
            //     $('.btn_coupon1').click();
            // }else {
            //
            // }
            getLeagueMatches(gtype,showtype,sorttype,mdate,tiptype);
            autoRefreshLeagueAction(autotime);
            changeSportDates();
        }
    });


    localStorage.removeItem('curBetArray'); // 删除已选择的赛事
    localStorage.removeItem('curOtbMenu'); // 删除
    closeBetAction(); // 此页面关闭下注

    // 日期选择，早盘赛事
    function changeSportDates() {
        $('#date_total').off().on('click','.btn_date',function () {
            var mdate = $(this).attr('data-value') ;
            $(this).addClass('on').siblings().removeClass('on');
            getLeagueMatches(gtype,showtype,sorttype,mdate,tiptype);
        })

    }

    // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
    function autoRefreshLeagueAction(time) {
        //let _self = this;
        //let $btn = $('#refresh-btn') ;
        let wait = time ;
        let refreshTime = function() {
            if (wait == 0) {
                wait = time ;
                //$btn.text(wait) ;
                getLeagueMatches(gtype,showtype,sorttype,mdate,tiptype) ;
                autoRefreshLeagueAction(time);
            }else{
                //$btn.text(wait) ;
                wait--;
                //console.log(wait+'++');
                sportTimerAc = setTimeout(refreshTime,1000) ;
            }
        }
        refreshTime();
    }


</script>



