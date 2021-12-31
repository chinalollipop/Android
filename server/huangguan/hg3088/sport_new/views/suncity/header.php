
<div class="header">

    <div class="middle">
        <div class="warp clearfix">
            <div class="left fl">
                <a href="javascript:;" class="to_index bl1 fl">
                    <div class="logo"></div>
                </a>
                <div class="logo_banner">

                    <div class="logo_banner_lb" id="colee">
                        <div id="colee1">
                            <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_1.png"></p>
                            <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_2.png"></p>
                            <p><img src="<?php echo TPL_NAME;?>images/logobanner/banner_3.png"></p>
                        </div>
                        <div id="colee2"></div>
                    </div>

                </div>
            </div>

            <div class="top_user_nav right fr">

                <?php
                if(!$uid){ // 未登录
                    echo '<div class="left fl"> 
                                    <span class="icon username_icon"></span>
                                    <input type="text" class="top_username" placeholder="账号" minlength="5" maxlength="15">
                                    <div class="topforget">
                                    <span class="icon psw_icon"></span>
                                        <input type="password" class="top_password" placeholder="密码" minlength="6" maxlength="15">
                                        <a href="javascript:;" class="to_livechat forgetName">忘记？</a>
                                    </div>
                
                                    <div class="top-button">
                                        <span class="logo_right"></span>
                                        <a href="javascript:;" class="login-submit-btn">登入</a>
                                        <a class="to_memberreg" href="javascript:;">免费开户</a>
                                        
                                    </div>
                                </div>
                                <div class="top_right right fr">
                                    <div class="lang"></div>
                                    <a class="'.(GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin').'" href="javascript:;"></a>
                                </div> ';

                }else{
                    echo '<div class="userWrap clearfix">
                                            <a href="javascript:;" class="cm_icon fl reload" onclick="indexCommonObj.getUserMoneyAction(uid)"></a>
                                            <div class="userName fl">
                                                <p class="p_name" title="'.$username.'">您好,'.$username.'</p>
                                                <p class="num"><span> 余额: </span><span class="user_member_amount member_amount" title="0">加载中...</span></p>
                                            </div>
                                            <a href="javascript:;" class="cm_icon fl topnav_add" ></a>
                                            <a href="javascript:;" class="cm_icon fl topnav_money" ></a>
                                            <a href="javascript:;" class="to_usercenter_content cm_icon fl email" data-to="email"> <span class="for_email_mount css_email_mount" style="display: none;"> </span> </a>                                      
                                            <a href="javascript:;" class="cm_icon fl topnav_user" ></a>
                                              <div class="show_balance balance-info-all font_ch" >

                                                <!--<span class=" top-sub-list-item"> 彩票余额: <b class="user_member_lottery_amount"> 0 </b> </span>-->
                                                <span class=" top-sub-list-item"> 彩票余额: <b class="user_member_third_lottery_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> AG余额: <b class="user_member_ag_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> OG余额: <b class="user_member_og_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> BBIN余额: <b class="user_member_bbin_amount"> 0 </b> </span>                                         
                                                <span class=" top-sub-list-item"> 开元余额: <b class="user_member_ky_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> 乐游余额: <b class="user_member_ly_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> VG余额: <b class="user_member_vg_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 快乐余额: <b class="user_member_kl_amount"> 0 </b> </span>                                        
                                                <!--<span class=" top-sub-list-item"> 皇冠余额: <b class="user_member_hg_amount"> 0 </b> </span>-->                                        
                                                <span class=" top-sub-list-item"> MG余额: <b class="user_member_mg_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> CQ9余额: <b class="user_member_cq_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> MW电子余额: <b class="user_member_mw_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> FG电子余额: <b class="user_member_fg_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 泛亚电竞余额: <b class="user_member_avia_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 雷火电竞余额: <b class="user_member_fire_amount"> 0 </b> </span>
                                             </div>
                                            <div class="show_operate operate font_ch" >
                                                <a href="javascript:;" class="to_usercenter_content top-sub-list-item" data-to="deposit">存款</a>
                                                <a href="javascript:;" class="to_usercenter_content top-sub-list-item" data-to="withdraw">提款</a>
                                                <a href="javascript:;" class="to_usercenter_content top-sub-list-item" data-to="tranfer">额度转换</a>
                                             </div>
                                             <div class="show_personal personal font_ch">
                                                <a href="javascript:;" class="to_usercenter_content top-sub-list-item" data-to="userbetaccount">账户记录</a>
                                                <a href="javascript:;" class="to_usercenter_content top-sub-list-item" data-to="usercenter">我的账户</a>
                                                <a href="/app/member/logout.php" class=" top-sub-list-item">退出</a>
                                              </div>
                                         </div>' ;
                }
                ?>

            </div>
        </div>
    </div>
    <div class="bottom clearfix" id="mainMenu">
        <div class="border">
            <div class="warp">
                <ul class="nav fl">
                    <li class="active"><a href="javascript:;" class="a_first to_index bl0">首页 <!--<i>HOME</i> --></a></li>
                    <li class="nav-drop-ac hot"><a href="javascript:;" class="a_first to_lives" >真人视讯 <!--<i>CASINO</i>--></a>
                        <div class="nav-drop" >
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')" > <img src="images/gameicon/ag.png"> AG国际厅</a>
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')" > <img src="images/gameicon/live/og.png"> OG国际厅</a>
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')" > <img src="images/gameicon/live/bbin.png"> BBIN国际厅</a>
                        </div>
                    </li>
                    <li class="nav-drop-ac"><a href="javascript:;" class="a_first to_dianjing">电子竞技 <!--<i>E-SPORT</i>--> </a>
                        <div class="nav-drop" >
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')"> <img src="images/gameicon/lhdj.png"> 雷火电竞</a>
                            <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')"> <img src="images/gameicon/fydj.png"> 泛亚电竞</a>
                        </div>
                    </li>
                    <li><a href="javascript:;" class="a_first to_sports" data-rtype="r" data-showtype="today">体育竞技 <!--<i>SPORT</i>--></a>
                    <li class="nav-drop-ac hot"><a href="javascript:;" class="a_first to_chess">棋牌游戏 <!--<i>CHESS</i>--></a>
                        <div class="nav-drop" >
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')" > <img src="images/gameicon/qp/kyqp.png"> KY棋牌</a>
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')" > <img src="images/gameicon/qp/lyqp.png">  LEG棋牌</a>
                            <!--<a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')" > <img src="images/gameicon/qp/hgqp.png">  皇冠棋牌</a>-->
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" > <img src="images/gameicon/qp/vgqp.png">  VG棋牌</a>
                            <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')" > <img src="images/gameicon/qp/hlqp.png">  快乐棋牌</a>
                        </div>
                    </li>
                    </li>
                    <li class="nav-drop-ac"><a href="javascript:;" class="a_first to_games">电子游戏<!--<i>SLOTS GAMES</i>--></a>
                        <div class="nav-drop" >
                            <a href="javascript:;" class="to_games" data-type="mg"> <img src="images/gameicon/mg.png"> MG电子</a>
                            <a href="javascript:;" class="to_games" data-type="ag"> <img src="images/gameicon/ag.png"> AG电子</a>
                            <a href="javascript:;" class="to_games" data-type="cq"> <img src="images/gameicon/cq9.png"> CQ9电子</a>
                            <a href="javascript:;" class="to_games" data-type="mw"> <img src="images/gameicon/mw.png"> MW电子</a>
                            <a href="javascript:;" class="to_games" data-type="fg"> <img src="images/gameicon/fg.png">FG电子</a>
                        </div>
                    </li>

                    <li>
                        <!-- <span class="lottery-dialogs">
                             <span class="dialog-slides">
                                 <span class="icon icon_normal">
                                     <span class="arrow_bottom"></span>香港特码1赔48
                                 </span>
                             </span>
                         </span>-->
                        <a href="javascript:;" class="a_first to_lotterys">彩票游戏 <!--<i>LOTTERY</i>--></a>
                    </li>
                    <li><a href="javascript:;" class="a_first to_fish">捕鱼游戏 <!--<i>FISH GAMES</i>--></a></li>
                    <li class="hot"><a href="javascript:;" class="a_first to_promos">最新优惠 <!--<i>PROMOTION</i>--></a></li>
                    <li><a href="javascript:;" class="a_first to_agentreg">代理加盟 </a></li>
                    <li><a href="javascript:;" class="a_first to_presence">太阳城风采 <!--<i>SUN CITY STYLE</i>--></a></li>
                    <li><a href="javascript:;" class="a_first to_downloadapp">手机APP </a></li>

                </ul>
            </div>
        </div>
    </div>
</div>
<div style="height:135px;background: #2e2b33;"></div>



