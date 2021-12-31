
    <div class="header">
        <div class="top-nav">
            <div class="w_1000">
                <ul class="left-nav">
                    <li class="license">牌照展示</li>
                    <li class="listt" >
                        <img src="<?php echo TPL_NAME;?>images/cn.png"/>
                        <img src="<?php echo TPL_NAME;?>images/usa.png"/>
                        <img src="<?php echo TPL_NAME;?>images/hk.png" />
                        <div class="top_time fr">
                            美国东部时间: <span class="getAmericaTime"> </span>
                        </div>
                    </li>
                </ul>

                <ul class="right-nav fr" >
                    <?php
                    if(!$uid) { // 未登录
                        ?>
                        <li><a href="javascript:;" class="<?php echo GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin' ?>" style="color: red;">立即试玩</a></li>
                    <?php } ?>
                    <li><a href="<?php echo TPL_NAME;?>tpl/lobby/kidnap.php" target="_blank" class="" style="color: #00ff1e;">防劫持教程</a></li>
                    <li ><a href="javascript:;" class="to_usercenter_content" data-to="deposit">快速充值</a></li>
                    <!-- <li class="red"><a href="javascript:;" target="_blank">线路检测</a></li>-->
                    <li class="light-blue"><a href="javascript:;" class="to_downloadapp">手机APP</a></li>
                    <li class="light-blue"><a href="javascript:;" class="to_aboutus" data-index="0">帮助中心</a></li>
                    <li class="light-blue"><a href="javascript:;" class="to_agentreg">代理加盟</a></li>
                </ul>
            </div>
        </div>

        <div class="middle">
            <div class="nav_top">
                <div class="logo">
                    <a href="javascript:;">
                        <embed src="<?php echo TPL_NAME;?>images/logo1.swf" type="application/x-shockwave-flash" width="360" height="78" quality="high" wmode="transparent"></embed>
                    </a>
                </div>

                <nav class="index_nav">
                    <ul >
                        <li>
                            <a href="javascript:;" class="to_index">首页<span>HOME</span></a>
                        </li>
                        <li class="red prize">
                            <a href="javascript:;" class="to_lives">真人视讯<span>LIVE CASINO </span></a>
                        </li>
                        <li class="green hot">
                            <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育竞技<span>SPORT</span></a>
                        </li>
                        <li class="blue bao">
                            <a href="javascript:;" class="to_games">电子游艺<span>SLOTS</span></a>
                        </li>
                        <li class="fire">
                            <a href="javascript:;" class="to_chess">棋牌游戏<span>BOARD</span></a>
                        </li>
                        <li>
                            <a href="javascript:;" class="to_dianjing">电子竞技<span>ELECTRIC</span></a>

                        </li>
                        <li>
                            <a href="javascript:;" class="to_lotterys">彩票游戏<span>LOTTERY</span></a>
                        </li>
                        <li class="lottery">
                            <a href="javascript:;" class="to_fish">捕鱼游戏<span>FISHING</span></a>
                        </li>
                        <li class="red hot">
                            <a href="javascript:;" class="to_promos">最新优惠<span>PROMOTIO</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="to_livechat">在线客服<span>SERVICE</span></a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

        <div class="bottom clearfix" >
            <div class="w_1000">

                <?php
                if(!$uid){ // 未登录
                    echo '<div class="account-box">
                                 <div class="login_form">
                                    <input id="login_account" type="text" placeholder="账号" class="top_username" minlength="5" maxlength="15">
                                    <input id="login_password" type="password" placeholder="密码" class="top_password" minlength="6" maxlength="15">
            
                                  <!--  <div class="check-code-wrapper">
                                        <input type="text" placeholder="验证码" class="top_verifycode" minlength="4" maxlength="6">
                                        <img class="index_captchaImg" src="/app/member/include/validatecode/captcha.php" title="点选此处更新验证码" alt="验证码" onclick="this.src=\'/app/member/include/validatecode/captcha.php?v=\'+Math.random();" >
                                    </div>-->
                                    <a href="javascript:;">
                                    <button class="login-box login-submit-btn login-btn" >登入</button>
                                    </a>
                                    <button class="reg-box to_memberreg login-btn"type="button" >免费开户</button>
                                    <a href="javascript:;" class="to_forgetpassword forget-btn" >忘记密码</a> <!--to_forgetpassword-->
                                 </div>
                            </div>
                       ';

                }else{
                    echo '<div class="log-account-box ng-scope">
                                <ul class="account-info">
                                    <li>
                                        帐号 :
                                        <span class="account" title="'.$username.'">'.$username.'</span>
                                            <a href="javascript:;" title="站内信" class="to_usercenter_content mailbox ng-scope" data-to="email">
                                                <span class="for_email_mount for_email_number">0</span>
                                            </a>
                                    </li>
                                    <li>
                                        账户余额 :
                                        <span class="account ng-binding" >$<span class="user_member_amount">0.00</span></span>
                                        <a title="更新">
                                            <i class="fa fa-refresh"></i>
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </a>
                                        
                                        <div class="callBackAllWallet" ></div>
                                    </li>
                                </ul>
                    
                                <div class="account-nav">
                                    <ul>
                                        <li title="投注记录">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="userbetaccount">
                                                投注记录
                                            </a>
                                        </li>
                                        <li class="org" title="线上取款">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="withdraw">
                                                线上取款
                                            </a>
                                        </li>
                                        <li class="green" title="线上存款">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="deposit">
                                                线上存款
                                            </a>
                                        </li>
                                        <li title="交易记录">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="userbetaccount">
                                                交易记录
                                            </a>
                                        </li>
                                        <li class="org" title="修改取款密码">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="usercenter">
                                                修改取款密码
                                            </a>
                                        </li>
                                        <li class="red" title="会员中心">
                                            <a href="javascript:;" class="to_usercenter_content" data-to="usercenter">
                                                会员中心
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <div class="action-box">
                                <a class="login-btn ng-binding" href="/app/member/logout.php">登出</a>
                            </div>
                        </div>
                        ' ;
                }
                ?>
                <div class="domain">易记域名: <?php echo str_replace('https://','',getSysConfig('vns_backup_web_url'))?><span ></span></div>
            </div>
        </div>


    </div>
    <div style="height:180px;background: #2e2b33;"></div>


