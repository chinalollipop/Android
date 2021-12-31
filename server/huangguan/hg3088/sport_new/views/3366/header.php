

    <div class="header">

        <div class="top clearfix ">  <?php /*echo ($uid?'login_top':'');*/?>

            <div class="warp w_1200">
                <div class="left fl">
                    <a target="_top" href="javascript:;" onclick="addUrlFavorite()"> 加入收藏 </a>
                    <a href="javascript:;" onclick="setHome(this)"> 设为首页 </a>
                    <span class="getAmericaTime"> </span>&nbsp;&nbsp;
                    <span> <a href="javascript:;" class="to_line_sense" > 线路检测 </a> </span>
                    <?php
                        if($uid){
                            echo ' | <span><a href="javascript:;" class="to_usercenter_content" data-to="usercenter" > 账户中心 </a> | </span><span><a href="/app/member/logout.php"> 退出登录 </a></span>';
                        }
                    ?>

                </div>
                <div class="right fr">
                    <?php
                    if(!$uid){ // 未登录
                        $testPlayFlag = GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin';
                        echo ' <input type="text" class="top_username" placeholder="会员账号" minlength="5" maxlength="15">
                                <div class="topforget">
                                    <input type="password" class="top_password" placeholder="密码" minlength="6" maxlength="15">
                                    <a href="javascript:;" class="to_livechat forgetName" >忘记？</a> <!-- to_forgetpassword -->
                                </div>
                                
                                <div class="top-button">
                                    <a href="javascript:;" class="login-submit-btn">登录</a>
                                    <a class="to_memberreg" href="javascript:;">注册</a>
                                    <a class="'.$testPlayFlag.'" href="javascript:;" >立即试玩</a>
                                </div>';

                    }else{
                        echo '<div class="userWrap clearfix">
                                             <span > 欢迎您： </span> <span class="themeColor" title="'.$username.'"> '.$username.' </span><img class="zs_icon" src="'.TPL_NAME.'images/zs_icon.png"> |&nbsp;
                                             <span > 余额： </span><span class="user_member_amount member_amount themeColor"> 加载中...</span> |&nbsp;
                                            <a href="javascript:;" class="to_usercenter_content " data-to="deposit" >
                                                <p class="cm_icon topnav_money"></p>
                                                <span>存款</span>
                                            </a>
                                             <a href="javascript:;" class="to_usercenter_content" data-to="withdraw" >
                                                <p class="cm_icon topnav_withdraw"></p>
                                                <span>提款</span>
                                            </a> 
                                             <a href="javascript:;" class="to_usercenter_content" data-to="tranfer" >
                                                <p class="cm_icon topnav_tran"></p>
                                                <span>转账</span>                                              
                                            </a> | 
                                             <a href="javascript:;" class="to_usercenter_content" data-to="email"> 
                                                <p class="cm_icon email"></p>
                                                 <span>站内信</span> <span class="dis_for_email_mount css_email_mount" > (0)</span>
                                              </a>                                      

                                         </div>' ;
                    }
                    ?>

                </div>

            </div>

        </div>

        <div class="middle">
            <div class="warp clearfix w_1200">
                <div class="left fl">
                    <a href="" class="bl1 fl">
                        <div class="logo"></div>
                    </a>
                 <!--   <div class="logo_banner">
                        <span class="logo_right"></span>
                        <div class="logo_banner_lb" id="colee">
                            <div id="colee1">
                                <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_1.png"></p>
                                <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_2.png"></p>
                                <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_3.png"></p>
                            </div>
                            <div id="colee2"></div>
                        </div>

                    </div>-->
                </div>
                <div class="top_user_nav right fr">
                    <ul class="nav fl">
                        <li class="active">
                            <a href="javascript:;" class="to_index bl0">首页<p class="en_name">HOME</p></a>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育赛事<p class="en_name">SPORT</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today"> <img src="<?php echo TPL_NAME;?>images/navxl/sport.png"> </a>
                                </div>
                            </div>
                            <!--<div class="sports-drop"><a <?php /*echo($uid?'href="'.$oldLogin.'"':'onclick=" layer.msg(\'请先登录\',{time:alertTime})"');*/?> >皇冠体育</a></div>-->
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_lives" >视讯直播 <p class="en_name">CASINO</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/ag_live.png"> </a>
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/og_live.png"> </a>
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/bbin_live.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_dianjing">电子竞技<p class="en_name">E-SPORT</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../app/member/thunfire/fire_api.php?action=getLaunchGameUrl')"> <img src="<?php echo TPL_NAME;?>images/navxl/dj_lh.png"> </a>
                                    <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../app/member/avia/avia_api.php?action=getLaunchGameUrl')"> <img src="<?php echo TPL_NAME;?>images/navxl/dj.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_games">电子游戏<p class="en_name">SLOTS</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_games" > <img src="<?php echo TPL_NAME;?>images/navxl/ag_game.png"> </a>
                                    <a href="javascript:;" class="to_games" data-type="mg"> <img src="<?php echo TPL_NAME;?>images/navxl/mg_game.png"> </a>
                                    <a href="javascript:;" class="to_games" data-type="cq"> <img src="<?php echo TPL_NAME;?>images/navxl/cq_game.png"> </a>
                                    <a href="javascript:;" class="to_games" data-type="mw"> <img src="<?php echo TPL_NAME;?>images/navxl/mw_game.png"> </a>
                                    <a href="javascript:;" class="to_games" data-type="fg"> <img src="<?php echo TPL_NAME;?>images/navxl/fg_game.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_lotterys">彩票游戏 <p class="en_name">LOTTERY</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_lotterys"> <img src="<?php echo TPL_NAME;?>images/navxl/lottery_xy.png"> </a>
                                    <a href="javascript:;" class="to_lotterys"> <img src="<?php echo TPL_NAME;?>images/navxl/lottery_gf.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_chess">棋牌游戏 <p class="en_name">CHESS</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_vg.png"> </a>
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_ky.png"> </a>
                                    <!--<a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')" > <img src="<?php /*echo TPL_NAME;*/?>images/navxl/chess_hg.png"> </a>-->
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_ly.png"> </a>
                                    <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_kl.png"> </a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_fish">捕鱼<p class="en_name">FISHING</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_fish"> <img src="<?php echo TPL_NAME;?>images/navxl/by.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:;" class="to_promos">优惠活动<p class="en_name">PROMOS</p></a>

                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_downloadapp">APP下载<p class="en_name">MOBILE</p></a>

                            <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_downloadapp"> <img src="<?php echo TPL_NAME;?>images/navxl/app.png"> </a>
                                </div>
                            </div>
                        </li>
                        <li><a href="javascript:;" class="to_agentreg">代理加盟<p class="en_name">PROXY</p></a></li>
                       <!-- <li><a href="javascript:;" class="to_suggestion">意见/投诉</a></li>-->

                    </ul>

                </div>
            </div>
        </div>

    </div>
    <div style="height:130px"></div>




