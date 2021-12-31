<?php
require ("../include/config.inc.php");
?>
<!-- 主体 -开始 -->
<div style="clear:both;"></div>
<div class="gywm_m">
    <div class="gywm_mn">
        <!-- 主体左侧热门游戏列表 -开始 -->
        <div class="gywm_mnbl">

        </div>
        <!-- 主体左侧热门游戏列表 -结束 -->


        <!-- 主体右侧列表 -开始 -->
        <form method="post" name="main" action="reg_agent.php?keys=add" onsubmit="return VerifyData()" target="_blank">
           <!-- <input type="HIDDEN" name="website" value="http://ag.hg0088.me">-->
            <input type="HIDDEN" name="loginwebsite" class="loginwebsite" value="">

            <div class="gywm_mnbr">
            <div class="gywm_mnbr_hyzc">
                <div class="gywm_mnbr_rperson">皇冠现金网：A级品牌公司给予的不止是心动，更是一份信誉的保障，500万名会员共同的选择。</div>
            </div>

            <div class="gywm_mnbr_field">
                <fieldset>
                    <legend>注册代理帐号:</legend>
                    <table>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 帐号：</td><td width="511"><input type="text" name="username" id="username" minlength="5" maxlength="15" />
                            请输入<font class="yellow">5-15个字元</font>, 仅可输入英文字母以及数字的组合!!</td></tr>
                        <tr><td class="tdr">&nbsp;&nbsp;<font class="red">*</font> 会员密码：</td><td><input type="password" name="password" id="password" minlength="6" maxlength="15"/>
                            须为<font class="yellow">6-15位英文或数字</font>且符合0~9或a~z字元</td></tr>
                        <tr><td class="tdr">&nbsp;&nbsp;<font class="red">*</font> 确认密码：</td><td><input type="password" name="password2" id="password2" minlength="6" maxlength="15"/>
                            &nbsp; </td></tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>代理基本数据:</legend>
                    <table>
                        <tr><td class="tdr"><font class="red">*</font> 真实姓名：</td><td width="511">
                            <input type="text" name="alias" id="alias" />
                            必须与提款时的收款人一致！</td></tr>
                        <tr><td class="tdr"><font class="red">*</font> 邮箱地址：</td><td width="511">
                            <input type="text" name="e_mail" id="e_mail" />
                            以便网络通知等信息！</td></tr>
                        <tr>
                            <td class="tdr"><font class="red">*</font>
                             联系方式:
                               <!-- <label for="select"></label>
                            <select name="select" id="select">
                                <option value="0" selected="selected">联系方式</option>
                                <option value="tel" >手机号</option>
                                <option value="qq" >QQ号</option>
                                <option value="weixin" >微信号</option>
                            </select>-->
                            </td>
                            <td><input type="text" name="phone" id="phone" minlength="11" maxlength="11"/>
                            以便客服与您取得联系！</td>
                        </tr>
                        <tr>
                            <td class="tdr"><font class="red">*</font>
                                微信:
                            </td>
                            <td>
                                <input id="wechat" name="wechat" minlength="5" type="text" style="margin-top: 5px">

                            </td>
                        </tr>

                    </table>
                </fieldset>

                <fieldset>
                    <legend>代理银行资料:</legend>
                    <table>
                        <tr><td class="tdr">&nbsp;&nbsp;<font class="red">*</font> 出款银行：</td>
                            <td width="511">
                                <select name="bank_name" id="bank_name">
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
                            </td>
                        </tr>
                        <tr><td class="tdr"><font class="red">*</font> 银行账号：</td>
                            <td width="511">
                                <input type="text" name="bank_account" minlength="10" maxlength="20" id="bank_account" />
                            以便转账！
                            </td>
                        </tr>
                        <tr><td class="tdr"><font class="red">*</font> 开户地址：</td>
                            <td width="511"><input type="text" name="bank_address" id="bank_address" />
                            以便跨行转账！
                            </td>
                        </tr>
                        <tr><td class="tdr"><font class="red">*</font> 安全密码：
                        </td>
                            <td>
                                <input type="password" name="paypassword" id="paypassword" minlength="4" maxlength="6"/>
                            提款认证必须，请务必记住！
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <div class="gywm_mnbr_fieldbt">
                    <div class="gywm_mnbr_fieldbtbt">
                        <input type="submit" class="btn" value="确认提交" />
                        <input type="reset" class="btn" value="重新设置" />
                    </div>
                    <div class="gywm_mnbr_fieldbtbz">备注：<br/>1.标记有 * 者为必填项目。<br/>2.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。<br/>3.若公司有其它活动会E-MAIL通知，请客户填写清楚。
                    </div>
                </div>

            </div>

        </div>

        </form>
        <!-- 主体右侧列表 -结束 -->

    </div>
</div>
<!-- 主体 -结束 -->
<script type="text/javascript" src="./js/validate.js"></script>
<script type="text/javascript">
    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    loadLeftNav() ;
    setWebsite() ;
    setUserMsg(usermessage,'nottip') ;
    function setWebsite() {
        // 注册成功后跳转到登录网址
        var urlStr = '<?php echo $ulrarr?>';
        var urlArray = urlStr.split(',') ;
        var urllen = urlArray.length ;
        var num = Math.floor(Math.random() * urllen) ; // 随机生成整数
        $('.loginwebsite').val('<?php echo HTTPS_HEAD?>://ag.'+urlArray[num]) ;
    }

</script>