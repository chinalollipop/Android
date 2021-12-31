<?php
session_start();
require ("../include/config.inc.php");
// 会员注册控制必填字段
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);
?>
<!-- 主体 -开始 -->


<div class="contact_all_top">

</div>

<div class="subcont">

    </div>
    <div class="inner">
        <div class="cl h13"></div>
        <div class="zhucebm">
            <!-- 左侧通用 -->
            <div class="leftnav">

            </div>
            <div class="menu-banner"><div class="welcome"></div></div>
            <div class="menu-main">
                <div class="menu-main-r">
                    <div class="menu-main-show-r">

                        <div class="reg-frm">
                            <form method="post" name="regreg" action="add_reg_mem.php?keys=add" onsubmit="return VerifyData('reg');" target="_blank">
                                <input type="hidden" name="website" class="setwebsite" value="">
                                <input type="hidden" name="loginwebsite" class="loginwebsite" value="">
                                    <div class="rightcont">
                                        <div class="cl h33"></div>
                                        <div class="zhucebox">
                                            <div class="zhucelx">
                                                <legend>注册账号</legend>
                                            </div>
                                            <div class="cl h12"></div>
                                            <ul class="zhuceuls">
                                               <!-- <li>
                                                    <div class="zhuzuomz"><span class="hong"></span>介绍人ID：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">-->
                                                            <input type="hidden" name="introducer" id="introducer" class="zhuceinpt" value="" minlength="4" maxlength="15">
                                          <!--              </div>
                                                        <span class="cw f12">若无介绍人则不填写！ </span>
                                                    </div>
                                                    <div class="cl "></div>
                                                </li>-->
                                                <li>

                                                    <div class="zhuzuomz"><span class="hong"></span>*账号：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">
                                                            <input type="text" class="zhuceinpt" name="username" id="username" minlength="5" maxlength="15" >
                                                            <div id="nickNameId"></div>
                                                        </div>

                                                    </div>
                                                    <div class="cl "></div>
                                                </li>
                                                <li>

                                                    <div class="zhuzuomz"><span class="hong"></span>*会员密码：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">
                                                            <input class="zhuceinpt" type="password" name="password" id="password" minlength="6" maxlength="15" >
                                                            <div id="pwdId"></div>
                                                        </div>
                                                        <span class="cw f12">密码规则：须为6~16个英文或数字且符合0~9或a~z字母！ </span>
                                                    </div>
                                                    <div class="cl "></div>
                                                </li>
                                                <li>

                                                    <div class="zhuzuomz"><span class="hong"></span>*确认密码：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">
                                                            <input class="zhuceinpt" type="password" name="password2" id="password2" minlength="6" maxlength="15">
                                                            <div id="repwdId"></div>
                                                        </div>
                                                        <span class="cw f12">请再次输入您的登录密码！ </span>
                                                    </div>
                                                    <div class="cl "></div>
                                                </li>

                                                <!--<li>
                                                    <div class="zhuzuomz"><span class="hong"></span>性别：</div>
                                                    <div class="zhucbybx">
                                                        <div class="cw l33">
                                                            <input type="radio" name="sex" class="" value="0" checked="checked">男
                                                            <input type="radio" name="sex" class="" value="1">女
                                                        </div>
                                                    </div>
                                                    <div class="cl "></div>
                                                </li>-->
                                                <?php if(empty($registerSet) || $registerSet['aliasOn'] == 1) { ?>
                                                <li>
                                                    <div class="zhuzuomz"><span class="hong"></span>*真实姓名：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">
                                                            <input class="zhuceinpt" size="30" maxlength="20" name="alias" id="alias" type="text">
                                                            <div id="realnameId"></div>
                                                        </div>
                                                        <span class="cw f12">必须与您的银行账户名称相同，否则不能出款！</span>
                                                    </div>
                                                    <div class="cl "></div>
                                                </li>
                                                <?php }if($registerSet['telOn'] == 1) { ?>
                                                    <li>
                                                        <div class="zhuzuomz"><span class="hong"></span>*手机号码：</div>
                                                        <div class="zhucbybx">
                                                            <div class="">
                                                                <input class="zhuceinpt" type="text" name="phone" id="phone" minlength="11" maxlength="11">
                                                            </div>
                                                            <span class="cw f12" style="color:red;">*&nbsp;请认真填写，以便有优惠活动可以及时通知您参与！ </span>
                                                        </div>
                                                        <div class="cl "></div>
                                                    </li>
                                                <?php } if($registerSet['chatOn'] == 1) { ?>
                                                    <li>
                                                        <div class="zhuzuomz"><span class="hong"></span>*微信号码：</div>
                                                        <div class="zhucbybx">
                                                            <div class="">
                                                                <input class="zhuceinpt" type="text" name="wechat" id="wechat" minlength="4" >
                                                            </div>
                                                        </div>
                                                        <div class="cl "></div>
                                                    </li>
                                                <?php } if($registerSet['qqOn'] == 1) { ?>
                                                    <li>
                                                        <div class="zhuzuomz"><span class="hong"></span>*QQ号码：</div>
                                                        <div class="zhucbybx">
                                                            <div class="">
                                                                <input class="zhuceinpt" type="text" name="qq" id="qq" minlength="4" >
                                                            </div>
                                                        </div>
                                                        <div class="cl "></div>
                                                    </li>

                                                <?php } ?>
                                                <li>

                                                    <div class="zhuzuomz"><span class="hong"></span>*验证码：</div>
                                                    <div class="zhucbybx">
                                                        <div class="">
                                                            <input class="zhuceinpt" id="verifycode" name="verifycode" type="text" tabindex="2" style="width:100px; height:30px" minlength="4" maxlength="4" >
                                                            <img title="点击刷新" border='1' src="../include/validatecode/captcha.php" align="absbottom" onclick="this.src='../include/validatecode/captcha.php?'+Math.random();"/>
                                                            <div id="verifycodeId"></div>
                                                        </div>

                                                    </div>
                                                    <div class="cl "></div>
                                                </li>
                                            </ul>

                                            <div class="cl h22"></div>
                                        </div>
                                        <div class="cl h22"></div>
<!--                                        <div class="zhucebox">-->
<!--                                            <div class="zhucelx">-->
<!--                                                会员资料-->
<!--                                            </div>-->
<!--                                            <div class="cl h12"></div>-->
<!--                                            <ul class="zhuceuls">-->
<!--                                                <li>-->
<!---->
<!--                                                    <div class="zhuzuomz"><span class="hong"></span>*取款密码：</div>-->
<!--                                                    <div class="zhucbybx">-->
<!--                                                        <div class="">-->
<!--                                                            <input class="zhuceinpt" type="password" name="paypassword" id="paypassword" minlength="4" maxlength="6">-->
<!---->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                    <div class="cl "></div>-->
<!--                                                </li>-->
<!---->
<!--                                                <li>-->
<!---->
<!--                                                    <div class="zhuzuomz"><span class="hong"></span>*手机号码：</div>-->
<!--                                                    <div class="zhucbybx">-->
<!--                                                        <div class="">-->
<!--                                                            <input class="zhuceinpt" type="text" name="phone" id="phone" minlength="11" maxlength="11">-->
<!--                                                        </div>-->
                                                       <!-- <div class="">
                                                            <select id="question" name="question" class="zhuceinpt" style="width: 219px">
                                                                <option value="">请选择</option>
                                                                <option value="您的车牌号码">您的车牌号码</option>
                                                                <option value="您所在的城市">您所在的城市</option>
                                                                <option value="您的生日">您的生日</option>
                                                                <option value="您的名字">您的名字</option>
                                                                <option value="您父亲的名字">您父亲的名字</option>
                                                                <option value="您母亲的名字">您母亲的名字</option>
                                                                <option value="您儿女的名字">您儿女的名字</option>
                                                                <option value="您妻子的名字">您妻子的名字</option>
                                                                <option value="您喜欢的数字">您喜欢的数字</option>
                                                                <option value="您喜欢的品牌">您喜欢的品牌</option>
                                                                <option value="您喜欢的运动">您喜欢的运动</option>
                                                                <option value="您喜欢的颜色">您喜欢的颜色</option>
                                                                <option value="您喜欢的球队">您喜欢的球队</option>
                                                                <option value="您喜欢的球星">您喜欢的球星</option>
                                                            </select>
                                                            <span class="cw f12"> 密码提示问题答案找回时需要，请谨记！</span>
                                                            <div id="questionId"></div>
                                                        </div>-->

<!--                                                    </div>-->
<!--                                                    <div class="cl "></div>-->
<!--                                                </li>-->
<!--                                                <li>-->
<!---->
<!--                                                    <div class="zhuzuomz"><span class="hong"></span>*微信：</div>-->
<!--                                                    <div class="zhucbybx">-->
<!--                                                        <div class="">-->
<!--                                                            <input class="zhuceinpt" type="text" name="wechat" id="wechat" minlength="5" >-->
<!--                                                        </div>-->
                                                        <!--<div class="">
                                                            <input type="text" class="zhuceinpt" name="answer" id="answer" value="">
                                                            <span class="cw f12">用于找回取款密码的答案，请谨记！</span>
                                                            <div id="answerId"></div>
                                                        </div>-->
<!--                                                    </div>-->
<!--                                                    <div class="cl "></div>-->
<!--                                                </li>-->
<!--                                                <li>-->
<!--                                                    <div class="zhuzuomz"><span class="hong"></span>*生日：</div>-->
<!--                                                    <div class="zhucbybx">-->
<!--                                                        <div class="">-->
<!--                                                            <input type="text" class="zhuceinpt" name="birthday" id="birthday" value="" onclick="WdatePicker({readOnly:true,isShowClear:false})">-->
<!--                                                            <span class="cw f12">用于取回密码的答案和生日礼金，需谨记！(填写格式为xxxx-xx-xx)</span>-->
<!--                                                            <div id="birthdayId"></div>-->
<!--                                                        </div>-->
<!--                                                    </div>-->
<!--                                                    <div class="cl "></div>-->
<!--                                                </li>-->
<!--                                            </ul>-->
<!--                                            <div class="cl h12"></div>-->
<!--                                        </div>-->
                                        <div class="cl h5"></div>
                                        <div class="f12 cw">
                                            <input type="checkbox" id="checkbox" name="checkbox" class="vm" checked="checked">我已经年满18岁，本人并无抵触所在国家所管辖的法律范围，且同意【条款及规则】
                                        </div>
                                        <div class="cl h15"></div>
                                        <div class="tac">
                                            <input type="submit" class="subbtns" value="" >
                                            <input type="reset" class="subbtns subbtns-rst" value="">
                                        </div>

                                        <div class="cl h15"></div>
                                        <div class="cl h15"></div>
                                        <div class="cl h15"></div>

                                    </div>

                                    <div class="cl"></div>

                            </form>
                        </div>


                        <div class="cl h25"></div>
        </div>
    </div>
</div>
<!-- 主体 -结束 -->
<script type="text/javascript" src="../js/validate.js"></script>
<script type="text/javascript">

    setWebsite(HTTPS_HEAD,FETCH_NUM) ;
    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    setUserMsg(usermessage,'nottip') ;
    loadLeftNav();
    function setWebsite(ad_login_http,num) {
        // 注册成功后跳转到登录网址
        var urlStr = '<?php echo $ulrarr?>';
        var urlArray = urlStr.split(',') ;
        var urllen = urlArray.length ;
        $('.loginwebsite').val(ad_login_http+'://'+urlArray[num]) ;

        // 注册来源网址
        var url = window.location.hostname ;
        $('.setwebsite').val(url) ;

    }

</script>

