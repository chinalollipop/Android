<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$host = $_SESSION['HOST_SESSION'];

$key = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;

$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>


<style>

</style>

<div id="new-banner">
    <div id="new-banner-box">
        <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
        <div class="msg-connet">

            <div class="left" style="margin-lefT:8px;">
                <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                    <a href="javascript:;" class="to_lives ylc_right"></a> </div>
            </div>

        </div>
    </div>
</div>

<div id="sidebarwrap">
    <div id="sidebarbox">
        <div id="leftsidebar">
            <ul>
                <li class="bbin"><a href="javascript:;" class="to_lives cur">BBIN娱乐</a></li>
                <li class="mg"><a href="javascript:;" class="to_lives">AG娱乐</a></li>
                <li class="sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                <li class="lot"><a href="javascript:;" class="to_lotterys">彩票游戏</a></li>
                <li class="ele"><a href="javascript:;" class="to_games">电子游艺</a></li>
            </ul>
            <div id="ads1"><a href="javascript:;" class="to_promos"></a></div>
            <div id="ads2"><a href="javascript:;" class="to_promos"></a></div>
        </div>
        <div id="rightsidebar" class="textWrap agent">
            <div class=" mainnav">
                <h1><span>申请合作</span></h1>
                <div id="middle" class="description">
                    <div class="content"><p>
                            <a href="<?php echo $_SESSION['AGENT_LOGIN_URL'];?>" target="_blank">
                                <span style="color:#FFFFE0">代理登陆</span>
                            </a>
                            <span style="color:#FFFFE0"> &nbsp; &nbsp;</span>
                            <a href="javascript:;" class="navLink" data-to="agent_reg">
                                <span style="color:#FFFFE0">代理注册</span>
                            </a><br>
                            <span style="font-size:14px"><span style="color:#FFFF00"><strong>条款与规则</strong></span></span>&nbsp;<br>
                            提供高额合作回报率，加入本公司合作伙伴计划需要200RMB费用（独家一条国际域名，一年使用权和管理费维护费），不需承担任何风险。<br>
                            只要您介绍会员到本公司，您就可以获得我们净赢利的回报。<br>
                            本公司有着强大的工作团队与您携手共创双赢未来。<br>
                            1. 我们提供顶级产品：体育、真人赌场、电子游戏、快乐彩、彩票等多游戏种类。<br>
                            2. 我们的市场策略保证大量客户和高回报。<br>
                            3. 您可以获得更多佣金，佣金比率高达50%。<br>
                            4. 我们提供的优质软件可以统计您的表现。<br>
                            5. 我们有受过良好训练的合作伙伴队伍满足您任何需求。</p>

                        <table style="border-collapse:collapse; border-spacing:0px; box-sizing:border-box; color:rgb(0, 0, 0); font-family:simsun,arial,sans-serif; font-size:12px; line-height:17.1429px; max-width:100%; width:582px">
                            <thead>
                            <tr>
                                <td rowspan="2"><span style="color:#FFF0F5">当月盈利</span></td>
                                <td rowspan="2"><span style="color:#FFF0F5">当月最低有效会员</span></td>
                                <td colspan="3"><span style="color:#FFF0F5">当月退佣比例</span></td>
                            </tr>
                            <tr>
                                <td><span style="color:#FFF0F5">真人、电子</span></td>
                                <td><span style="color:#FFF0F5">彩票(有效投注)</span></td>
                                <td><span style="color:#FFF0F5">体育投注</span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><span style="color:#FFF0F5">1~50000</span></td>
                                <td><span style="color:#FFF0F5">5或以上</span></td>
                                <td><span style="color:#FFF0F5">25%</span></td>
                                <td><span style="color:#FFF0F5">0.1%</span></td>
                                <td><span style="color:#FFF0F5">25%</span></td>
                            </tr>
                            <tr>
                                <td><span style="color:#FFF0F5">50001~300000</span></td>
                                <td><span style="color:#FFF0F5">10或以上</span></td>
                                <td><span style="color:#FFF0F5">35%</span></td>
                                <td><span style="color:#FFF0F5">0.1%</span></td>
                                <td><span style="color:#FFF0F5">30%</span></td>
                            </tr>
                            <tr>
                                <td><span style="color:#FFF0F5">300001~800000</span></td>
                                <td><span style="color:#FFF0F5">50或以上</span></td>
                                <td><span style="color:#FFF0F5">40%</span></td>
                                <td><span style="color:#FFF0F5">0.1%</span></td>
                                <td><span style="color:#FFF0F5">35%</span></td>
                            </tr>
                            <tr>
                                <td><span style="color:#FFF0F5">800001~1000000</span></td>
                                <td><span style="color:#FFF0F5">80或以上</span></td>
                                <td><span style="color:#FFF0F5">45%</span></td>
                                <td><span style="color:#FFF0F5">0.1%</span></td>
                                <td><span style="color:#FFF0F5">40%</span></td>
                            </tr>
                            <tr>
                                <td><span style="color:#FFF0F5">2000001以上</span></td>
                                <td><span style="color:#FFF0F5">100或以上</span></td>
                                <td><span style="color:#FFF0F5">50%</span></td>
                                <td><span style="color:#FFF0F5">0.1%</span></td>
                                <td><span style="color:#FFF0F5">50%</span></td>
                            </tr>
                            <tr>
                                <td colspan="5"><span style="color:#FF0000">给予合作伙伴最高的盈利回报只要您成为我们的代理，只要您拥有人脉或用心推广，积极发展下线，无需任何费用，亏损统一由我们承担，让您实实在在坐等高收益，睡觉都在赚钱。</span></td>
                            </tr>
                            </tbody>
                        </table>

                        <p>以上数据是显示您在本公司推荐的玩家每月的净损失，也涉及到您的佣金比率(您的佣金比基于玩家的净损失)。<br>
                            1. 佣金比率为50%，您的会员净输额50%将会成为您的佣金。<br>
                            2. 当月存款500元以上，有效投注1000以上，登陆次数5次以上且正常投注，您将开始获得佣金，增加会员净输额和注册的会员会使您获得更多的回报。<br>
                            3. 我们会时刻关注合作商的表现，会即时地调整佣金比率并通知合作商。<br>
                            4. 负收益将被带入下一个月。<br>
                            5. 合作伙伴获得的支付费用，优惠和市场费用，这些费用将会累计并会在代理每月的佣金中扣除。<br>
                            这些费用包括：<br>
                            A. 转帐费用：包括所有代理会员的存款和提款费用。<br>
                            B. 促销费用：任何费用支出为支持或协助代理与促销或营销目的。<br>
                            C. 优惠红利：给予代理会员的现金红利或是折扣。</p>

                        <p><strong><span style="color:#FFFF00"><span style="font-size:14px">合作计划团队专用</span></span></strong><br>
                            我们的团队将在这里为您提供专业的咨询并真诚协助您使用合作计划工具，以便您最大限度的在行业领先的全部产品中获取丰厚的佣金。<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>一个值得您信任的名字</strong></span></span><br>
                            我们在此向您提出诚挚邀请，希望您能成为世界最大在线博彩集团中的一员。我们在200个不同的国家中，拥有超过1,400万的忠实客户。<br>
                            &nbsp;</p>

                        <p><strong><span style="color:#FFFF00"><span style="font-size:14px">获取所有产品的佣金</span></span></strong><br>
                            只需推广bet365体育投注，就能赚取诱人的50％佣金。您将在这里发现广受欢迎的缤纷体育赛事从而确保自己轻松获得优厚回报。<br>
                            &nbsp;</p>

                        <p><span style="font-size:14px"><span style="color:#FFFF00"><strong>最好的合作计划工具</strong></span></span><br>
                            我们提供丰富全面的工具及报告功能，您可更加有效的管理自己的个人商务，并获得极佳的创意支持与行业领先的客户收益。<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>支付方式：</strong></span></span><br>
                            1.您的佣金将自动在下个月初的15个工作日内自动存入您注册时指定的银行帐户中每个月只结算一次，仅管我们提供免费支出交易，<br>
                            如果收款处要求加入的额外经费我们将不负责支付。<br>
                            最低支付金额高于RMB 500人民币的将被直接支付，所有低于RMB 499人民币的支付将被累计下一个月。<br>
                            本公司有权改变或修正和增加任何条款如上所述，如有需要时。<br>
                            如果客户在规定的时间内不符合我们预期的代理表现，本公司有权随时取消合作伙伴帐户，而不需任何理由或者提前通知。<br>
                            相关代理规则与条款将在审核前发布予申请人。</p>

                        <p>代理部QQ：<span class="agent_service_number"> </span> </p>
                    </div>


                </div>
                <div class="clear"></div>
            </div>

            <!-- 代理注册 -->
            <div class="agent_reg" style="display: none">
                <div id="middle" class="pagent">
                    <div class="reg_bg"></div>
                    <div class="form">
                        <h2 id="privateag"><span>代理注册</span></h2>
                        <div id="agentregurl">代理商专属链接地址：<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=代理ID</div>
                        <form action="" method="post" name="agent" id="agent">
                            <div class="reg_bottom"></div>
                            <h2>代理资料</h2>


                            <div id="agboxbx1">
                                <div><label class="clearfloat"><span>代理账号:</span><input name="username" id="username" type="text" minlength="5" maxlength="15"> * 5-15个英文和数字组成 </label></div>
                                <div><label class="clearfloat"><span>密    码:</span><input name="password" id="password" type="password" minlength="6" maxlength="15"> * 6-15个任意字符组成 </label></div>
                                <div><label class="clearfloat"><span>确认密码:</span><input name="repassword" id="password2" type="password" minlength="6" maxlength="15"> * 6-15个任意字符组成 </label></div></div>
                            <div class="agboxbot"><div class="agboxbotw"></div></div>
                            <div class="reg_bottom"></div>
                            <h2>个人资料</h2>
                            <div id="agboxbx2"><div><label class="clearfloat"><span>真实姓名:</span><input type="text" name="realname" id="alias"> * 名字必须与您的银行户口所用名字相同 </label></div>
                                <div><label class="clearfloat"><span>联系电话:</span><input type="text" name="tel" id="phone" minlength="11" maxlength="11"> * 请填写您的手机 </label></div>
                                <div><label class="clearfloat"><span>微信:</span><input type="text" name="weixin" id="wechat"> </label></div></div>
                            <div class="agboxbot">
                                <div class="agboxbotw"></div>
                            </div>
                            <div class="reg_bottom"></div>
                            <h2>银行资料</h2>
                            <div id="agboxbx3">
                                <div>
                                    <label class="clearfloat"><span>出款银行:</span>
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
                                        </select> *</label>
                                </div>
                                <div><label class="clearfloat"><span>银行账号:</span><input name="bank_account" id="bank_account" type="text" value=""> * 请认真填写,否则不能出款</label></div>
                                <div><label class="clearfloat"><span>开户行地址:</span><input name="bank_address" id="bank_address" type="text" value=""> * 请认真填写,否则不能出款 </label></div>
                                <!--<div><label class="clearfloat"><span>您的推广网址:</span><input name="url" type="text" value="">&nbsp;&nbsp;&nbsp;&nbsp;请输入您的推广网址,如果没有可留空 </label></div>-->
                            </div>
                            <div class="agboxbot"><div class="agboxbotw"></div></div>
                            <div class="submitDiv verifyRandom">
                                <input name="submit" type="button" id="submitbutton" class="agents_submit" value=" 提 交 "></div>
                            <div class="reg_bottom"></div>
                        </form></div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">

    $(function () {
        var index = '<?php echo $key;?>';

        // 标签切换
        $('.navLink').click(function () {
            var i = $(this).index();
            var type = $(this).attr('data-to');
            //console.log(i)
            $('.textWrap').find('.'+type).show().siblings().hide();
            //$(this).addClass('active').siblings().removeClass('active');

        });

        var clipboard = new ClipboardJS('.copyButton');
        //优雅降级:safari 版本号>=10,提示复制成功;否则提示需在文字选中后，手动选择“拷贝”进行复制
        clipboard.on('success', function(e) {
            layer.msg('复制成功!',{time:alertTime})
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
            layer.msg('请选择“拷贝”进行复制!',{time:alertTime})
        });

        function agentsReg() { // 代理注册
            var actionurl = '/app/member/api/reg_agent.php' ;
            var agregflage = false ;
            $('.agents_submit').on('click',function () {
                if(agregflage){
                    return false ;
                }
                var username = $("#username").val();
                var passwd = $("#password").val();
                var passwd2 =$("#password2").val();
                var phone =$("#phone").val();
                var alias =$("#alias").val();
                var wechat =$("#wechat").val();
                var bank_name =$("#bank_name").val();
                var bank_address =$("#bank_address").val();
                var bank_account =$("#bank_account").val();
                var title = '' ;

                if (username == "" ) {
                    title = '账号不能为空!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (!isNum(username)){
                    title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (username.length < 5 || username.length > 15) {
                    title = '账号需在5-15位之间!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if ( passwd == "" ) {
                    title = '密码不能为空！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (passwd.length < 6 || passwd.length > 15) {
                    title = '密码需在6-15位之间！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if ( passwd2 != passwd ) {
                    title = '密码与确认密码不一致！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(!alias){
                    title = '请输入真实姓名！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(phone=='' || !isMobel(phone)){
                    title = '请输入正确的手机号码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(wechat=='' || !isWechat(wechat)){
                    title = '请输入正确的微信号码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(!bank_address){
                    title = '请输入银行地址！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(bank_account=='' || !isBankAccount(bank_account)){
                    title = '请输入正确银行账号！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }

                agregflage = true ;
                $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    url : actionurl ,
                    data : {
                        keys:'add',
                        username:username,
                        password:passwd,
                        password2:passwd2,
                        phone:phone,
                        alias:alias,
                        wechat:wechat,
                        bank_name:bank_name,
                        bank_address:bank_address,
                        bank_account:bank_account,
                    },
                    success:function(res) {
                        if(res){
                            agregflage = false ;
                            layer.msg(res.describe,{time:alertTime});
                            if(res.status ==200){
                                window.location.href = res.data.agentUrl ;
                            }
                        }

                    },
                    error:function(){
                        agregflage = false ;
                        layer.msg('稍后请重试',{time:alertTime});
                    }
                });


            })
        }

        agentsReg();

    })
</script>