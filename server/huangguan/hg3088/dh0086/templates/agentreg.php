<?php
require ("../include/config.inc.php");
?>
<style>
    .agent_hide{ display: none;}
</style>
<!-- 主体 -开始 -->

<div class="contact_all_top">

</div>

<div class="subcont">

    <div class="inner">
        <div class="cl h13"></div>
        <div class="zhucebm">
            <div class="leftnav">

            </div>

        <!-- 主体右侧列表 -开始 -->
            <div class="rightcont">
                <div class="cl h33"></div>
                <div class="gycont">
                    <form method="post" name="main" action="reg_agent.php?keys=add" onsubmit="return VerifyData()" target="_blank">
                        <input type="HIDDEN" name="loginwebsite" class="loginwebsite" value="">
                        <div class="gyqimcont" style="display: block;">
                            <div class="cl h33"></div>
                            <div class="zhucebox">
                                <div class="zhucelx">
                                    注册代理账号
                                </div>
                                <div class="cl h12"></div>
                                    <input type="hidden" name="keys" value="add">
                                    <input type="hidden" name="website" value=" ">
                                    <ul class="zhuceuls">
                                        <li>
                                            <div class="zhuzuomz"><span class="hong">*</span>账号：</div>
                                            <div class="zhucbybx">
                                                <div class="">
                                                    <input name="username" type="text" size="30" id="username" minlength="5" maxlength="15" class="zhuceinpt" >
                                                    <div id="nickNameId"></div>

                                                </div>
                                                <span class="cw f12"> 须为5-15个字母, 仅可输入英文字母以及数字的组合！</span>

                                            </div>
                                            <div class="cl "></div>
                                        </li>
                                        <li>

                                            <div class="zhuzuomz"><span class="hong">*</span>密码：</div>
                                            <div class="zhucbybx">
                                                <div class="">
                                                    <input size="30" type="password" name="password" id="password" class="zhuceinpt" minlength="6" maxlength="15">
                                                    <div id="pwdId"></div>

                                                </div>
                                                <span class="cw f12">须为6~16个英文或数字且符合0~9或a~z字母！ </span>
                                            </div>
                                            <div class="cl "></div>
                                        </li>
                                        <li>

                                            <div class="zhuzuomz"><span class="hong">*</span>确认密码：</div>
                                            <div class="zhucbybx">
                                                <div class="">
                                                    <input size="30" type="password" name="password2" id="password2" class="zhuceinpt" minlength="6" maxlength="15">
                                                    <div id="repwdId"></div>
                                                </div>
                                                <span class="cw f12">请再次输入您的登录密码！ </span>
                                            </div>
                                            <div class="cl "></div>
                                        </li>


                                    </ul>
                                    <div class="cl h22"></div>

                            </div>

                            <div class="cl h33"></div>
                            <div class="zhucebox">
                                <div class="zhucelx">
                                    代理基本数据
                                </div>
                                <div class="cl h12"></div>
                                <ul class="zhuceuls">

                                    <li>
                                        <div class="zhuzuomz"><span class="hong">*</span>真实姓名：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <input id="alias" size="30" maxlength="10" name="alias" type="text" class="zhuceinpt" >
                                                <div id="realnameId"></div>
                                            </div>
                                            <span class="cw f12">必须与提款时的收款人一致！</span>
                                        </div>
                                        <div class="cl "></div>
                                    </li>
                                    <li>
                                        <div class="zhuzuomz"><span class="hong">*</span>手机号码：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <input id="phone" name="phone" minlength="11" maxlength="11"  type="text" class="zhuceinpt" >
                                                <div id="realnameId"></div>
                                            </div>
                                        </div>
                                        <div class="cl "></div>
                                    </li>
                                    <li>
                                        <div class="zhuzuomz"><span class="hong">*</span>微信：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <input id="wechat" name="wechat"  minlength="5" type="text" class="zhuceinpt" >
                                                <div id="realnameId"></div>
                                            </div>
                                        </div>
                                        <div class="cl "></div>
                                    </li>
                                </ul>
                                <div class="cl h22"></div>
                            </div>
                            <div class="cl h33"></div>
                            <div class="zhucebox">
                                <div class="zhucelx">
                                    代理银行资料
                                </div>
                                <div class="cl h12"></div>
                                <ul class="zhuceuls">
                                    <li>

                                        <div class="zhuzuomz"><span class="hong">*</span>开户地址：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <select class="zhuceinpt" name="bank_name" id="bank_name" style="width: 219px">
                                                    <option value="工商银行">工商银行</option>
                                                    <option value="交通银行">交通银行</option>
                                                    <option value="农业银行">农业银行</option>
                                                    <option value="建设银行">建设银行</option>
                                                    <option value="招商银行">招商银行</option>
                                                    <option value="民生银行总行">民生银行总行</option>
                                                    <option value="中信银行">中信银行</option>
                                                    <option value="光大银行">光大银行</option>
                                                    <option value="华夏银行">华夏银行</option>
                                                    <option value="广东发展银行">广东发展银行</option>
                                                    <option value="深圳平安银行">深圳平安银行</option>
                                                    <option value="中国邮政">中国邮政</option>
                                                    <option value="中国银行">中国银行</option>
                                                    <option value="农村信用合作社">农村信用合作社</option>
                                                    <option value="兴业银行">兴业银行</option>
                                                </select>
                                            </div>
                                            <span class="cw f12">以便跨行转账！</span>
                                        </div>
                                        <div class="cl "></div>
                                    </li>

                                    <li>

                                        <div class="zhuzuomz"><span class="hong">*</span>银行账号：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <input id="bank_account" maxlength="23" name="bank_account" type="text" class="zhuceinpt" >
                                                <div id="bankCardId"></div>

                                            </div>
                                            <span class="cw f12">以便转账！</span>
                                        </div>
                                        <div class="cl "></div>
                                    </li>

                                    <li>

                                        <div class="zhuzuomz"><span class="hong">*</span>开户地址：</div>
                                        <div class="zhucbybx">
                                            <div class="">
                                                <input id="bank_address" size="30" maxlength="50" name="bank_address" type="text" class="zhuceinpt" ">
                                                <div id="bankAddressId"></div>
                                            </div>
                                            <span class="cw f12">以便跨行转账！</span>
                                        </div>
                                        <div class="cl "></div>
                                    </li>
                                </ul>

                                <div class="cl h22"></div>

                            </div>
                            <div class="cl h33"></div>
                            <div class="tac">
                                <input type="submit" class="subbtns" value="" >
                                <input type="reset" class="subbtns subbtns-rst" value="">
                            </div>

                            <div class="cl h22"></div>
                            <div class="fw f12">
                                备注：
                                <br>1.标记 * 号为必填项目。
                                <br>2.手机与取款密码为取款金额的凭证，请会员务必填写详细资料。
                                <br>3.若公司有其他活动会E-MAIL通知，请客户填写清楚。
                            </div>
                        </div>
                    </form>
                </div>
                <div class="cl"></div>
            </div>
        </div>
        <div class="cl h25"></div>
    </div>
</div>


<!-- 主体 -结束 -->
<script type="text/javascript" src="./js/validate.js"></script>
<script type="text/javascript">
    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    setWebsite() ;
    setUserMsg(usermessage,'nottip') ;
    loadLeftNav() ;
    function setWebsite() {
        // 注册成功后跳转到登录网址
        var urlStr = '<?php echo $ulrarr?>';
        var urlArray = urlStr.split(',') ;
        var urllen = urlArray.length ;
        var num = Math.floor(Math.random() * urllen) ; // 随机生成整数
        $('.loginwebsite').val('<?php echo HTTPS_HEAD?>://ag.'+urlArray[num]) ;
    }
    // 切换按钮
    function changeTip(obj) {
        var tx = $(obj).text() ;
        var cls = $(obj).data('val') ;
        $('.agent_title').text(tx);
        $('.agent_reg,.agent_hide').hide();
        $('.'+cls).css('display','table-row');

    }

</script>