

    <div class="header">
        <div class="header_top">
            <div class="header_t_l">
                <p>7x24小时客服热线：<span class="ess_service_phone"> </span>  </p>
                <p> 美东时间： <span class="getAmericaTime"> </span> </p>
            </div>
            <div class="hader_t_r">
                <?php
                    if(!$uid) {
                        echo ' <a href="javascript:;" class="red mfzc to_memberreg">免费注册</a>';
                    }
                ?>

                <a href="<?php echo getSysConfig('line_sense_url');?>" target="_blank">线路检测</a>
                <?php
                if(!$uid) {
                    echo ' <a href="javascript:;" class="to_testplaylogin">免费试玩</a>';
                }
                ?>

                <a href="<?php echo getSysConfig('promo_url');?>" class=" mvkt" target="_blank">活动大厅</a>
                <a href="javascript:;" class="to_agentreg">代理加盟</a>
                <a href="<?php echo getSysConfig('kscz_url');?>" class="red" target="_blank">快速充值</a>
                <a href="<?php echo getSysConfig('download_app_page');?>" class="red" target="_blank">APP下载</a>

            </div>
        </div>
        <div class="header_m">
            <a href="javascript:;" class="header_logo">
                <img src="<?php echo TPL_NAME;?>images/header/LOGO.png?v=3" alt="">
            </a>
            <div class="shizi"></div>
            <?php
            if(!$uid) { // 未登录 ,没有登录之前的头部
                echo '  <div class="header_r">
                        <a href="javascript:;" class="to_memberreg dckh">
                        </a>
                        <div class="login_wrap">
                            
                                <div class="login_u_y">
                                    <p>
                                        <input type="text" title="请填写 5-15 位大小写英数字" maxlength="15" class="top_username h_user" placeholder="账号">
                                        <input type="password"  title="请填写 6-15 位大小写英数字" minlength="6" maxlength="15" class="top_password h_upwd" placeholder="密码">
                                    </p>
                                    
                                  <!--  <p>
                                        <input type="text" title="请填写验证码" maxlength="6" class="top_verifycode h_yzm" placeholder="验证码">
                                        <img class="register_captchaImg" src="/app/member/include/validatecode/captcha.php" alt="点选此处产生新验证码" onclick="this.src=\'/app/member/include/validatecode/captcha.php?v=\'+Math.random();">
                                    </p>-->
                                </div>
                                <div class="login_u_u">
                                    <p>
                                        <a href="javascript:;" class="dl login-submit-btn" ></a>    
                                         <a href="javascript:;" class="forget_upwd to_forgetpassword"></a>  
                                    </p>
                                     
                                </div>
                           
                        </div>
                    </div>' ;
            }else{  // 登录成功之后，会显示账号，账户余额，会员中心，线上存款，线上取款等，退出
                echo '<div class="header_dlcg top_user_nav">
                        <div class="dlcg_l">
                            <div class="accinfo">
                                <span class="acczht">账号：</span>
                                <span class="acczhtinfo" title="'.$username.'">'.$username.'</span>
                            </div>
                            <div class="accye">
                                <span class="accyet">余额：</span>
                                <span class="accyeinfo user_member_amount">0.00</span>
                                <b class="jh topnav_add" title="请点击，查看更多余额" onclick="$(\'.yeinfo\').slideToggle();">+</b>
                            </div>
                            <div class="accdym">
                                <a href="javascript:;" class="hyzx to_usercenter">会员中心</a>
                                <a href="javascript:;" class="to_deposit">线上存款</a>
                                <a href="javascript:;" class="xsqk to_withdraw">线上取款</a>
                                <a href="javascript:;" class="yjgh to_platform_tranfer">额度转换</a>
                                <a href="javascript:;" class="wdxx to_user_email">未读讯息<span class="dis_for_email_mount" >(0)</span></a>
                                <a href="javascript:;" class="to_userbetaccount">账号记录</a>
                            </div>
                        </div>
                        <a class="quit" href="/app/member/logout.php"></a>
                        <div class="yeinfo">
                            
                            <ul>
                                <li>
                                    <span>彩票余额:</span>
                                    <span class="je user_member_third_lottery_amount">--</span>
                                </li>
                                <li>
                                    <span>AG余额:</span>
                                    <span class="je user_member_ag_amount">--</span>
                                </li>
                                <li>
                                    <span>开元余额:</span>
                                    <span class="je user_member_ky_amount">--</span>
                                </li>
                                <li>
                                    <span>乐游余额:</span>
                                    <span class="je user_member_ly_amount">--</span>
                                </li>
                                <li>
                                    <span>VG余额:</span>
                                    <span class="je user_member_vg_amount">--</span>
                                </li>
                                <li>
                                    <span>快乐余额:</span>
                                    <span class="je user_member_kl_amount">--</span>
                                </li>
                                 <li>
                                    <span>OG余额:</span>
                                    <span class="je user_member_og_amount">--</span>
                                </li> 
                                <li>
                                    <span>BBIN余额:</span>
                                    <span class="je user_member_bbin_amount">--</span>
                                </li>             
                               <!-- <li>
                                    <span>皇冠余额:</span>
                                    <span class="je user_member_hg_amount">&#45;&#45;</span>
                                </li>-->
                                <li>
                                    <span>MG余额:</span>
                                    <span class="je user_member_mg_amount">--</span>
                                </li>                              
                                <li>
                                    <span>CQ9余额:</span>
                                    <span class="je user_member_cq_amount">--</span>
                                </li>
                                  <li>
                                    <span>MW余额:</span>
                                    <span class="je user_member_mw_amount">--</span>
                                </li>
                                 <li>
                                    <span>FG余额:</span>
                                    <span class="je user_member_fg_amount">--</span>
                                </li>
                                <li>
                                    <span>泛亚电竞余额:</span>
                                    <span class="je user_member_avia_amount">--</span>
                                </li>
                                <li>
                                    <span>雷火电竞余额:</span>
                                    <span class="je user_member_fire_amount">--</span>
                                </li> 
                            </ul>
                        </div>
                    </div>';
            }
            ?>


        </div>
        <div class="header_bottom">
            <div class="ltht"></div>
            <div class="header_nav">
                <div class="nav_main">
                    <a href="javascript:;" class="to_index sy">
                        首页
                    </a>
                    <!-- 主导航 -->
                    <div class="zdh">
                        <ul class="ul_header">
                            <li class="cp">
                                <a href="javascript:;" class="to_lotterys">彩票游戏</a>
                               <!-- <ul class="cpyx">
                                    <li><a href="javascript:;">北京赛车</a></li>
                                    <li><a href="javascript:;">欢乐生肖</a></li>
                                    <li><a href="javascript:;">PC蛋蛋</a></li>
                                    <li><a href="javascript:;">香港六合彩</a></li>
                                    <li><a href="javascript:;">重庆幸运农场</a></li>
                                    <li><a href="javascript:;">广东十分快三</a></li>

                                </ul>-->
                            </li>
                            <li class="xlnav">
                                <a href="javascript:;" class="to_lives">视讯直播</a>
                                <ul class="xlgame sxzb">
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid;?>')">AG视讯</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">OG视讯</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">BBIN视讯</a></li>
                                </ul>
                            </li>
                            <li class="xlnav">
                                <a href="javascript:;" class="to_dianjing">电子竞技</a>  <img src="<?php echo TPL_NAME;?>images/header/hot.gif" alt="" class="hot">
                                <ul class="xlgame sxzb">
                                    <li><a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')">雷火电竞</a></li>
                                    <li><a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')">泛亚电竞</a></li>
                                </ul>
                            </li>
                            <li class="xlnav">
                                <a href="javascript:;" class="to_games">电子游艺</a>
                                <img src="<?php echo TPL_NAME;?>images/header/hot.gif" alt="" class="hot">
                                <ul class="xlgame dzyy">
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid;?>')" >AG电子</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/mg/mg_api.php?action=getLaunchGameUrl')" >MG电子</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mw/mw_api.php?action=gameLobby')" >MW电子</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/index.php')" >CQ9电子</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/fg/index.php')" >FG电子</a></li>
                                </ul>

                            </li>
                            <li class="xlnav">
                                <a href="javascript:;" class="to_fish">捕鱼游戏</a>
                                <ul class="xlgame byyx">
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid; ?>&gameid=6')">AG捕鱼</a></li>
                                </ul>
                            </li>
                            <li class="xlnav">
                                <a href="javascript:;" class="qpyxa to_chess">棋牌游戏</a>
                                <ul class="xlgame qpyx">
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/ky/index.php?uid=<?php echo $uid;?>')">开元棋牌</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/lyqp/index.php?uid=<?php echo $uid;?>')">乐游棋牌</a></li>
                                   <!-- <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','/app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">皇冠棋牌</a></li>-->
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/vgqp/index.php?uid=<?php echo $uid;?>')">VG棋牌</a></li>
                                    <li><a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/klqp/index.php?uid=<?php echo $uid;?>')">快乐棋牌</a></li>
                                </ul>
                                <img src="<?php echo TPL_NAME;?>images/header/hot.gif" alt="" class="hot">
                            </li>
                            <li><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育赛事</a></li>
                            <li><a href="javascript:;" class="to_sports" data-rtype="re" data-showtype="rb">滚球盘</a></li>

                           
                            <li>
                                <a href="javascript:;" class="yhhda to_promos">优惠活动</a>
                                <img src="<?php echo TPL_NAME;?>images/header/hot.gif" alt="" class="hot">
                            </li>
                        </ul>

                    </div>
                    <!-- 品牌形象 -->
                    <a href="javascript:;" class="to_presence ppxx">
                        品牌形象
                    </a>
                </div>
            </div>

        </div>

    </div>
