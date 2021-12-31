<?php
session_start();
	include_once('../include/config.inc.php');

if(!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "") { // 已登录
    echo "<script>alert('请重新登录!');window.location.href='/".TPL_NAME."login.php';</script>";
    exit;
}

$uid=$_SESSION["Oid"];
$username = $_SESSION['UserName']; // 拿到用户名
$gtype = $_REQUEST['gtype'];
$showtype = isset($_REQUEST['showtype'])?$_REQUEST['showtype']:''; // 当前类型，滚球，今日，早盘
$sorttype = isset($_REQUEST['sorttype'])?$_REQUEST['sorttype']:''; // 联盟排序
$mdate = isset($_REQUEST['mdate'])?$_REQUEST['mdate']:'' ; // 时间
$date=date('Y-m-d');
$FStype = isset($_REQUEST['FStype'])?$_REQUEST['FStype']:''; // 冠军标志 FT 足球 ,BK 篮球
$tiptype = isset($_REQUEST['tiptype'])?$_REQUEST['tiptype']:''; // 当前标志 p3 综合过关，champion 冠军

if($showtype=='RB'){ // 倒计时 滚球
    $autotime = 20 ; // 刷新时间
}else if($showtype=='FT' || $showtype=='BK'){ // 今日
    $autotime = 60 ; // 刷新时间
}else{ // 早盘
    $autotime = 90 ; // 刷新时间
}
$cpUrl = $_SESSION['cpUrl'] ; // 彩票链接
if($showtype=='BK'){
    $showtype = 'FT' ;
}
if($showtype=='BU'){
    $showtype = 'FU' ;
}

?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
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
    </head>
    <body >
    <div id="container">

        <!-- 顶部导航栏 -->
        <div class="header sport_header">
            <div class="header_left">
                <a href="sport_main.php" class="back-active sport_back_icon" ></a>
            </div>
            <div class="header-center">
                <a href="sport_main.php?showtype=rb" class="header_live <?php if($showtype=='RB'){echo 'active';} ?>" data-type="RBMATCH" ><i class="rb_running_logo"></i>滚球</a> <!--onclick="changeSportMatches(this,'<?php /*echo $gtype*/?>','','<?php /*echo $showtype*/?>')"-->
                <a href="sport_main.php?showtype=today" class="header_today <?php if($showtype=='FT' || $showtype=='BK'){echo 'active';} ?>" data-type="TODAYMATCH" >今日</a> <!--onclick="changeSportMatches(this,'<?php /*echo $gtype*/?>','','<?php /*echo $showtype*/?>')"-->
                <a href="sport_main.php?showtype=future" class="header_early <?php if($showtype=='FU' || $showtype=='BU'){echo 'active';} ?>" data-type="FUTUREMATCH" >早盘</a> <!--onclick="changeSportMatches(this,'<?php /*echo $gtype*/?>','','<?php /*echo $showtype*/?>')"-->
            </div>
            <div class="header-right" >
                <span class="menu_icon" data-num="1" onclick="showDownMenu(this)"> </span>
            </div>
        </div>

        <!-- 中间部分 -->
        <div class="content-center sport-content-center">

            <!-- 下拉菜单 -->
                <div class="subaccountform_menu" style="display: none">

                    <div class="menu_user">
                        <div class="float_left user_2"><?php echo $username?></div>
                        <div class="dropdown_sub_right float_right">
                            RMB
                            <span id="acc_credit" name="acc_credit" class="curr_amount_2"> </span>
                            <span id="curr_reload" class="float_right curr_reload "></span>
                        </div>
                    </div>


                </div>


            <!-- 导航切换 -->
            <div class="sportNav">
                <div class="sportNav_tip">
                    <ul>
                    <li onclick="getLeagueMatches('FT','<?php echo $showtype?>','<?php echo $sorttype?>','<?php echo $mdate?>','<?php echo $tiptype?>');"><span class="football-icon"></span><p>足球</p></li>
                        <?php
                        if(strpos($_SESSION['gameSwitch'],'|')>0){
                            $gameArr=explode('|',$_SESSION['gameSwitch']);
                        }else{
                            if(strlen($_SESSION['gameSwitch'])>0){
                                $gameArr[]=$_SESSION['gameSwitch'];
                            }else{
                                $gameArr=array();
                            }
                        }
                        if(!in_array('BK',$gameArr)) {
                        ?>
                    <li onclick="getLeagueMatches('BK','<?php echo $showtype?>','<?php echo $sorttype?>','<?php echo $mdate?>','<?php echo $tiptype?>');"><span class="basketball-icon"></span><p>篮球</p></li>
                        <?php } ?>
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
                    <div id="title_gtype" name="title_gtype" class="game_title">足球</div>
                    <div id="refresh" class="refresh" onclick="getLeagueMatches('<?php echo $gtype?>','<?php echo $showtype?>','<?php echo $sorttype?>','<?php echo $mdate?>','<?php echo $tiptype?>');">
                        <span id="refresh-btn"> </span>
                    </div>
                </div>


            </div>
            <!-- 内容区域 -->
            <div class="dport-content">

                <!-- 有赛事 容器-->
                <div class="has_sport_matches" >
                    <!-- 标题栏-->
                    <?php if($showtype !='RB'){ ?>  <!--滚球不展示-->
                        <div class="hdp_header">
                            <table border="0" cellspacing="0" cellpadding="0" class="tool_table">
                                <tbody>
                                <tr>
                                    <td id="change_r" class="h_r <?php echo $tiptype==''?'hdp_up':'';?>" data-type="ALLMATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')">让球 &amp; 大小</td>
                                    <td id="change_p" class="h_p <?php if($tiptype=='p3'){ echo 'hdp_up';}?>" data-type="P3MATCH" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')" >综合过关</td>
                                    <td id="change_fs"  class="h_fs <?php if($tiptype=='champion'){ echo 'hdp_up';}?>" data-type="CHAMPION" onclick="changeSportMatches(this,'<?php echo $gtype?>','','<?php echo $showtype?>')">冠军</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <!-- 联赛选择 -->
                    <div class="selection <?php if($showtype=='FU' || $showtype=='BU' || $tiptype=='p3'){echo 'selection_future';} ?>">
                        <!-- 日期赛事选择 -->
                        <?php if($showtype=='FU' || $showtype=='BU' || $tiptype=='p3'){ // 早盘才有日期
                            echo ' <select class="sportsdropdown" id="time_sel_sort" name="mdate" onchange="changeSportDates(this,\''.$gtype.'\',\''.$showtype.'\',\''.$sorttype.'\',\''.$tiptype.'\')">
                                    <option value="" '.($mdate==''?'selected':'').'>全部日期</option>';
                            if($tiptype=='p3' &&($showtype=='FT' || $showtype=='BK')){  // 今日
                                echo '<option value="'.date('Y-m-d').'" '.($mdate==date('Y-m-d')?'selected':'').'>'.date('m'.'月'.'d'.'日').'</option>';
                            }
                            for($datei=1;$datei<16;$datei++){ // 往后 15 天数据
                                $dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
                                $dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
                                echo '<option  value="'.$dateNowValue.'"  '.($mdate==$dateNowValue?'selected':'').'>'.$dateNowStr.'</option>';
                            }
                             echo '</select>';
                        }
                        ?>

                        <select class="sportsdropdown" id="sel_sort" name="sorttype" onchange="changeSportLeague(this,'<?php echo $gtype?>','<?php echo $showtype?>','<?php echo $mdate?>','<?php echo $tiptype?>')">
                            <option value="league" <?php echo $sorttype=='league'?'selected':'';?> >联盟排序</option>
                            <option value="time" <?php echo $sorttype=='time'?'selected':'';?> >时间排序</option>
                        </select>
                    </div>
                    <!-- 无赛事 -->
                    <div id="div_nodata" name="div_nodata" class="NoEvent_game" style="display:none;">无赛程</div>

                    <!-- 联赛列表 -->
                    <div class="league_list">

                       <!-- <div id="lid_101156" name="lid_101156" class="inneraccordion">
                            <div class="game_name"><span>亚运会2018男子足球U23(在印尼)</span></div>
                            <div class="more_right">
                                <div class="list_num">2</div>
                                <div class="list_arr"> </div>
                            </div>
                        </div>
                        <div id="lid_102444" name="lid_102444" class="inneraccordion">
                            <div class="game_name"><span>欧足联欧洲联赛外围赛</span></div>
                            <div class="more_right">
                                <div class="list_num">21</div>
                                <div class="list_arr"> </div>
                            </div>
                        </div>-->

                    </div>

                    <div id="bottom_all" class="allsports" onclick="window.location.href='sport_main.php'">所有球类</div>
                    <div  class="LayoutDiv5" ><a href="/">回到页首</a></div>

                </div>

                <!-- 足球 -->
                <div id="ft_other_list" data-area="1" class="list">
                 <!--   <a onclick="ifHasLogin('cate.php?gtype=ft')">
                        <div class="game-name">球会友谊赛</div>
                        <div class="row-wrap">
                            <div class="td1">07:45a</div>
                            <div class="td3 account-item">未开始</div>
                            <div class="td2">
                                <div><span>华拿伦加 [中]</span></div>
                                <div><span>广士云格</span></div>
                            </div>
                        </div>
                    </a>
                   -->

                </div>

                <!-- 足球滚球-->
                <div id="ft_roll_list" data-area="2" class="list hide-cont">
                   <!-- <a onclick="ifHasLogin('cate.php?gtype=ft_rb')">
                        <div class="game-name">印度果阿超级联赛</div>
                        <div class="row-wrap"><div class="td1" style="line-height: 18px">中场</div>
                            <div class="td3 account-item">0:2</div>
                            <div class="td2">
                                <div><span>萨尔高卡</span></div>
                                <div><span>甸普</span></div>
                            </div>
                        </div>
                    </a>
                   -->

                </div>

                <!-- 篮球 -->
                <div id="bk_other_list" data-area="3" class="list hide-cont">

                   <!-- <a onclick="ifHasLogin('cate.php?gtype=bk')">
                        <div class="game-name">美國大學籃球</div>
                        <div class="row-wrap">
                            <div class="td1">11:00p</div>
                            <div class="td3 account-item">未开始</div>
                            <div class="td2">
                                <div><span>空軍</span></div>
                                <div><span>科羅拉多州立</span></div>
                            </div>
                        </div>
                    </a>-->

                </div>

                <!-- 篮球滚球 -->
                <div id="bk_roll_list" data-area="4" class="list hide-cont">
                   <!-- <a onclick="ifHasLogin('cate.php?gtype=bk_rb')">
                        <div class="game-name">NBA美国职业篮球联赛</div>
                        <div class="row-wrap">
                            <div class="td1">第四节4'</div>
                            <div class="td3 account-item">108:92</div>
                            <div class="td2">
                                <div><span>奥兰多魔术</span></div>
                                <div><span>克里夫兰骑士</span></div>
                            </div>
                        </div>
                     </a>-->
                </div>

                <!-- 早盘足球 -->
                <div id="fu_other_list" data-area="5" class="list hide-cont">

                </div>
                <!-- 早盘篮球 -->
                <div id="bu_other_list" data-area="6" class="list hide-cont">

                </div>

            </div>


        </div>


	       <div class="clear"></div>

        <!-- 底部footer -->
        <div id="footer" class="sport_footer">

        </div>

        <!-- 遮罩层 -->
        <div class="mask"  ></div>

     </div>

    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/animate.js"></script>
    <script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
     <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript" src="/js/league_list.js?v=<?php echo AUTOVER; ?>"></script>

    <script type="text/javascript">
        var uid = '<?php echo $uid?>' ;
        var gtype = '<?php echo $gtype?>';
        var showtype = '<?php echo $showtype?>';
        var sorttype = '<?php echo $sorttype?>';
        var mdate = '<?php echo $mdate?>';
        var autotime = '<?php echo $autotime?>' ; // 倒计时刷新时间
        var FStype =  '<?php echo $FStype?>' ;  // 是否是冠军
        var tiptype =  '<?php echo $tiptype?>' ;  // 当前标志 p3 综合过关，champion 冠军
        var usermon = getCookieAction('member_money') ; // 获取信息cookie
        $('#acc_credit').html(usermon) ;
        autoRefreshLeagueAction(autotime) ;
        setFooterAction(uid);  // 在 addServerUrl 前调用
        sportWeiHu();
        // console.log(showtype)
        if(tiptype=='champion'){ // 如果是冠军
            getLeagueMatches(gtype,showtype,sorttype,mdate,'champion') ;
        }else if(tiptype=='p3'){ // 综合过关
            getLeagueMatches(gtype,showtype,sorttype,mdate,'p3') ;
        } else{
            getLeagueMatches(gtype,showtype,sorttype,mdate) ;
        }

        // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
        function autoRefreshLeagueAction(time) {
            var $btn = $('#refresh-btn') ;
            var wait = time ;
            var refreshTime = function() {
                if (wait == 0) {
                    wait = time ;
                    $btn.text(wait) ;

                    if(tiptype=='champion'){ // 如果是冠军
                        getLeagueMatches(gtype,showtype,sorttype,mdate,'champion') ;
                    }else if(tiptype=='p3'){ // 综合过关
                        getLeagueMatches(gtype,showtype,sorttype,mdate,'p3') ;
                    } else{
                        getLeagueMatches(gtype,showtype,sorttype,mdate) ;
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

        // 体育维护判断
        function sportWeiHu() {
            var Maintenance_type = 'rb'; // 默认滚球
            switch (showtype){
                case 'RB':
                    Maintenance_type = 'rb';
                    break;
                case 'FT':
                    Maintenance_type = 'today';
                    break;
                case 'FU':
                    Maintenance_type = 'future';
                    break;
            }
            getPageMaintenance(Maintenance_type); // 判断是否维护
        }

    </script>


    </body>
</html>