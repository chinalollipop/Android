<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$host = $_SESSION['HOST_SESSION'];
$key = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;

$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>
<style>
    .agencyWrap .nav li span{width:30px;height:30px;background:url("<?php echo $tplNmaeSession;?>images/agent_icon.png");}
    .agencyWrap .nav li span.icon_lmfa{background-position: -52px -7px;}
    .agencyWrap .nav li span.icon_lmxy{background-position: -52px -60px;}
    .agencyWrap .nav li span.icon_tgdz{background-position: -52px -108px;}
    .agencyWrap .nav li span.icon_dlzc{background-position: -52px -162px;}
    .agencyWrap .nav li span.icon_dldl{background-position: -52px -210px;}
    .agencyWrap .nav li span.icon_right{background-position: -52px -254px;}
    .agencyWrap .nav li .active span.icon_lmfa{background-position: -7px -7px;}
    .agencyWrap .nav li .active span.icon_lmxy{background-position: -7px -60px;}
    .agencyWrap .nav li .active span.icon_tgdz{background-position: -7px -108px;}
    .agencyWrap .nav li .active span.icon_dlzc{background-position: -7px -162px;}
    .agencyWrap .nav li .active span.icon_dldl{background-position: -7px -210px;}
    .agencyWrap .nav li .active span.icon_right{background-position: -7px -254px;}
    .agencyWrap .nav li:hover span{background-position-x:-7px;color: #fff; }
    .articleWrap .textWrap .table tr td{text-align: center}
</style>


<div class="agencyWrap">
    <div class="nav">
        <ul class="about-nav">
            <li><a class="active" href="javascript:;"> <span class="icon_lmfa"></span> 联盟方案 <span class="icon_right"> </a></li>
            <li><a href="javascript:;" > <span class="icon_lmxy"></span> 联盟协议 <span class="icon_right"> </a></li>
            <li><a href="javascript:;"> <span class="icon_tgdz"></span> 推广地址 <span class="icon_right"> </a></li>
            <li><a href="javascript:;"> <span class="icon_dlzc"></span> 代理注册 <span class="icon_right"> </a></li>
            <li><a href="<?php echo $_SESSION['AGENT_LOGIN_URL'];?>" target="_blank" > <span class="icon_dldl"></span> 代理登录 <span class="icon_right"> </a></li>

        </ul>
        <span class="bottomLogo"></span>
    </div>
    <div class="articleWrap">

        <h1 class="bzzx_title">联盟方案</h1>
        <div class="textWrap">
            <div class="textBox textItem ">
                <p><?php echo $companyName;?>在线持牌经营博彩投注多年。是目前世界最大的网络博彩集团之一！ 持有菲律宾政府卡格扬经济特区 First Cagayan leisure and Resort Corporation颁发的体育博彩与线上赌场执照。 拥有多元化的产品，使用最公平、公正、公开的系统，在市场上的众多博彩网站中，我们自豪的提供会员最优惠的回馈， 给予代理合作最优势的营利回报! 无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入<?php echo $companyName;?>代理合作的行列， 无须负担任何费用，就可以开始无上限的收入。加入<?php echo $companyName;?>，绝对是您最聪明的选择!</p>

               <h2> 注册申请</h2>

               <p> 请点击【代理注册】在线提出申请，并确实填写各项资料。 <?php echo $companyName;?>会评估审核联盟申请讯息，3日内由专员与您联系开通，并提供您的注册帐号、密码及推广链接。</p>
                   <table class="table" border="1" cellspacing="0" cellpadding="0">

                       <tr><td rowspan="2">当月营利</td><td rowspan="2">当月最低有效会员	</td><td colspan="3">当月退佣比例</td></tr>
                        <tr><td>真人/电子/棋牌</td><td>	彩票	</td><td>体育/电竞</td></tr>
                        <tr><td>1~50000	</td><td>5或以上</td><td>	30%</td><td>	15%</td><td>	10%</td></tr>
                        <tr><td>50001~300000	</td><td>10或以上	</td><td>35%</td><td>	20%	</td><td>15%</td></tr>
                        <tr><td>300001~800000</td><td>	30或以上	</td><td>40%</td><td>	30%	</td><td>20%</td></tr>
                        <tr><td>800001~1200000	</td><td>50或以上	</td><td>45%</td><td>	35%	</td><td>25%</td></tr>
                        <tr><td>1200001以上	</td><td>100或以上</td><td>	50%	</td><td>40%	</td><td>30%</td></tr>

                </table>

                <h2>注：<?php echo $companyName;?> 保留上述条例之最终更改权！</h2>

                <p>请谨记任何使用不诚实方法以骗取佣金将会永久冻结账户，佣金一律不予发还！ 新合作运行商正式确立合作关系之后，须用心推广，前三个月需有每月3个或以上的有效会员增长，否则公司有权终止合作关系。</p>

                <h2>回馈/佣金计算</h2>
               <p> 1.* 请注意：</p>
               <p> 真人娱乐、体育博彩等项目，以报表中【派彩】字段，扣除相应费用后，依照上表门坎 	X 佣金百分比。</p>
               <p> 2.当月联盟体系以：真人、体育、彩票等项目的【派彩/投注量/总额公点金额】扣除相应费用后产生退佣总计，乘以相应退佣百分比後。</p>
               <p> 3.相应费用包括：会员各项优惠、存/取款相应手续费(请留意：<?php echo $companyName;?> 会员重复出款￥手续费/未达100%投注出款的手续费由会员吸收，不纳入计算)。</p>
               <p> 4.【当月最低有效会员】定义为，在月结区间内进行过最少存款充值500RMB以上且有1000RMB有效下注的会员，如联盟体系当月未达【当月最低有效会员】最低门坎5人，则该月无法领取佣金回馈。联盟体系当月营利达到标准，而【当月最低有效会员】人数未达相应最低门坎，则该月佣金比例依照【当月最低有效会员】人数所达门坎相应的百分比进行退佣。</p>
              <p>  5.例：</p>
              <p>  体育当月营利为￥100001，而当月有效会员人数为5人，联盟虽达到营利为￥100001，却未达到有效会员10人以上，故依照联盟有效会员人数5人的门坎的退佣比例核算。</p>

                <h2>佣金结算：</h2>
               <p> 您的代理下玩家所有盈利均由公司承担，您不用承担任何客户赢钱的风险，佣金每个月5号结算。结算完毕后，即可申请提款，您的所有提款将在24小时内到达您指定的收款银行帐号</p>

                <h2>贴心服务：</h2>
               <p> 24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟<?php echo $companyName;?>现金网合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！</p>

                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <h2>合作协议</h2>
                <p><?php echo $companyName;?>与BBIN进行技术合作，为哥斯特黎加合法注册之博彩公司。我们采用最为多元、 先进、公正的系统，在众多博彩网站中，我们自豪能为会员提供最优惠的回馈、为代理商创造强劲的营利优势! <?php echo $companyName;?>秉持商业联营、资源整合、利益共享的理念，与合作伙伴携手打造利多的荣景。 无论您拥有的是网络资源，或是丰富的人脉，都欢迎您来加入我们的行列，不须负担任何费用， 就可以开拓无上限的营收。<?php echo $companyName;?>娱乐绝对是您最聪明的选择!</p>

               <h2> 一、代理商注册规约</h2>

                <p>为防堵不肖业者滥用<?php echo $companyName;?>所提供的代理优惠制度，我们将严格审核每位代理商申请注册时所提供的个人资料(包括 姓名、IP、住址、电邮信箱、电话、支付方式等等)。若经审核发现代理商有任何不良营利企图，或与其他代理 商、会员进行合谋套利等行为，<?php echo $companyName;?>娱乐公司将关闭该合作代理商之账户、扣除账户中的本金，并收回该代理商 的所有佣金与优惠。 同一IP/同一姓名/同一收款账号的会员只能是一个合作代理商的下线，代理商本身不能成为其他代理商的下线会员。</p>

                <h2>二、权责条款</h2>

                <p>（1）<?php echo $companyName;?>对联盟伙伴的权利与义务</p>

                <p><?php echo $companyName;?>的客服部门会登记合作代理商的下线会员并观察其投注状况。 代理商及会员皆须同意并遵守<?php echo $companyName;?>的会员条例、政策及操作程序。 合作代理商可随时登入管理端接口观察其下线会员的下注状况与活动概况。 <?php echo $companyName;?>保留所有对合作代理商或会员之账户加以拒绝或冻结的权利。 <?php echo $companyName;?>有权修改合约书上之任何条例(包括:现有的佣金范围、佣金计划、付款程序、及参考计划条例等 等)，<?php echo $companyName;?>公司会以电邮、网站公告等方法通知合作代理商。若代理商对于任何修改持有异议，可选择终止合 约、或洽谈客服人员提出意见。如代理商未提出异议，便视作默认合约修改，必须遵守更改后的相关规定。</p>

                <p>（2）联盟伙伴对<?php echo $companyName;?>的权力及义务</p>

                <p>合作代理商应尽其所能，广泛地宣传、销售及推广<?php echo $companyName;?>使代理商本身及 <?php echo $companyName;?>的利润最大化。合作 代理商可在不违反法律的情况下，以正面形象宣传、销售及推广<?php echo $companyName;?>， 并有责任义务告知旗下会员所有关于<?php echo $companyName;?>的相关优惠条件及产品。 合作代理商选择推广<?php echo $companyName;?>的手法若需付费，则代理商应自行承担该费用。 任何<?php echo $companyName;?>的相关信息(包括：标志、报表、游戏画面、图样、文案等)，合作代理商不得私自复制、公开、 分发有关材料，<?php echo $companyName;?>保留法律追诉权。 如代理商在业务推广方面需要相关的技术支持， 欢迎随时洽询<?php echo $companyName;?>客服人员。</p>

                <h2>三、各项细则</h2>

                <p>各阶层合作代理商不可在未经<?php echo $companyName;?>娱乐允许下开设双/多个代理账号， 也不可从<?php echo $companyName;?>之游戏账户或其他相关人士赚取佣金。 请谨记任何代理商皆不能用代理帐户下注，<?php echo $companyName;?> 有权终止并封存账号及其所有在游戏中赚取的佣金。</p>

                <p>为确保所有<?php echo $companyName;?>会员的账号隐私与权益， <?php echo $companyName;?>不会提供任何会员密码，或会员个人资料。 各阶层合作代理商亦不得以任何方式取得会员数据，或任意登入下层会员账号， 如发现代理商有侵害<?php echo $companyName;?>会员隐私的行为， <?php echo $companyName;?>有权取消代理商之红利，并取消该名代理商之账号。</p>

                <p>合作代理商旗下的会员不得开设多于一个的账户。<?php echo $companyName;?>有权要求会员提供有效的身份证明以验证会员的身份， 并保留以IP判定会员是否重复注册的权利。如违反上述事项， <?php echo $companyName;?>有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金。</p>

                <p>如合作代理商旗下的会员因违反条例而被禁止使用<?php echo $companyName;?>的游戏， 或<?php echo $companyName;?>退回存款给会员， <?php echo $companyName;?>将不会分配相应的佣金给代理商。 如合作代理商旗下会员存款用的信用卡、银行资料须经审核，<?php echo $companyName;?>将保留相关佣金直至审核完毕。</p>

                <p>合约条件将于<?php echo $companyName;?>正式接受合作代理商加入后开始生效。 <?php echo $companyName;?>娱乐公司及代理商可随时终止此合约。 在任何情况下，代理商若欲终止合约，都必须以书面/电邮方式提早于七日内通知<?php echo $companyName;?>。 代理商的表现将会每3个月审核一次，如代理商已不是现有的合作成员，则本合约书可以在任何时间终止。 如代理商违反合约条例，<?php echo $companyName;?>有权立即终止合约。</p>

                <p>在没有<?php echo $companyName;?>的许可下， 代理商不能透露及授权<?php echo $companyName;?>的相关机密资料， 包括代理商所获得的回馈、佣金报表、计算方式等；代理商有义务在合约终止后仍执行机密文件及数据的保密。 合约终止之后，代理商及<?php echo $companyName;?>将不须履行双方的权利及义务。 终止合约并不会解除代理商于终止合约前所应履行的义务。</p>

                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <p style="text-align: center">作为一名尊贵的<?php echo $companyName;?>合作伙伴，您可以利用您的资源简单赚取高额佣金，轻松实现成功与财富的梦想！</p>
                <div class="iptWrap">
                    <input type="text" class="agents_url" value="<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=您的代理编号">
                    <img class="copyButton" data-clipboard-target=".agents_url" src="../<?php echo $tplNmaeSession;?>images/copy.jpg" alt="">
                </div>
                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <form class="agents_reg_form" onsubmit="return false"> <!-- return false 防止表单提交后自动跳转 -->
                    <div class="regAgent">
                        <div class="regTit">
                            <span>注册代理账号</span>
                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>代理账号 </p>
                                <div class="regIpt agent_icon">
                                    <input type="text" name="username" id="username" minlength="5" maxlength="15" placeholder="请输入代理账号">
                                </div>
                                <p class="reg-error">须为5-15个字母, 仅可输入英文字母以及数字的组合！</p>
                            </div>

                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>密码 </p>
                                <div class="regIpt pwd agent_icon">
                                    <input type="password" name="password" id="password"  minlength="6" maxlength="16" placeholder="请输入密码">
                                </div>
                                <p class="reg-error">须为6~16个英文或数字且符合0~9或a~z字母！</p>
                            </div>


                            <div class="refItem fl ml84">
                                <p><span class="red">*</span>确认密码 </p>
                                <div class="regIpt pwd agent_icon">
                                    <input type="password" name="password2" id="password2" minlength="6" maxlength="16" placeholder="请再次输入密码">
                                </div>
                                <p class="reg-error">须为6~16个英文或数字且符合0~9或a~z字母！</p>
                            </div>
                        </div>
                        <div class="regTit">
                            <span>代理基本数据</span>
                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>真实姓名 </p>
                                <div class="regIpt agent_icon">
                                    <input type="text" name="alias" id="alias" placeholder="请输入真实姓名">
                                </div>
                                <p class="reg-error">必须与提款绑定银行卡持有人一致！</p>
                            </div>

                        </div>
                        <div class="regAcount clearfix">

                            <div class="refItem fl  ">
                                <p><span class="red">*</span>手机号 </p>
                                <div class="regIpt phone agent_icon">
                                    <input type="text" name="phone" id="phone" minlength="11" maxlength="11" placeholder="请输入手机号码">
                                </div>
                                <p class="reg-error">请输入11位手机号码</p>
                            </div>

                            <div class="refItem fl ml84">
                                <p><span class="red">*</span>微信 </p>
                                <div class="regIpt wechat agent_icon">
                                    <input type="text" name="wechat" id="wechat" placeholder="请输入微信">
                                </div>
                                <p class="reg-error">以便更好的联系</p>
                            </div>


                        </div>

                        <div class="regTit">
                            <span>代理银行资料</span>
                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>开户银行 </p>
                                <div class="regIpt agent_icon">
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
                                    <!--<input type="text" placeholder="请输入开户银行">-->
                                </div>
                                <p class="reg-error">以便跨行转账！</p>
                            </div>

                        </div>
                        <div class="regAcount clearfix">

                            <div class="refItem fl  ">
                                <p><span class="red">*</span>开户地址 </p>
                                <div class="regIpt  wz agent_icon">
                                    <input type="text" name="bank_address" id="bank_address" placeholder="请输入开户地址">
                                </div>
                                <p class="reg-error">以便跨行转账！</p>
                            </div>

                            <div class="refItem fl ml84">
                                <p><span class="red">*</span>银行账号 </p>
                                <div class="regIpt card agent_icon">
                                    <input type="text" name="bank_account" id="bank_account" placeholder="请输入银行卡号">
                                </div>
                                <p class="reg-error">以便转账！</p>
                            </div>


                        </div>
                    </div>
                    <div class="submitWrap">
                        <button class="firstBtn agents_submit">确认提交</button>
                        <button type="reset" onclick="$('.agents_reg_form')[0].reset()">重置域名</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--注册登录-->
<!--<div class="modal fade " tabindex="-1" role="dialog" id="ageRegister" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog register" role="document">
        <div style="background: #f2f2f2;height: 460px" class="modal-content register_form">
            <div class="modal-header title">
                <div class="orange_border"></div>
                代理登录
                <div onclick="closeModal(this)" class="close_btn">&#215;</div>
            </div>
            <div class="modal-body">
                <form>
                    <div class="dlWrap">
                        <p class="tip">代理账号</p>
                        <div class="dlIpt clearfix">
                            <input type="text">
                        </div>

                        <p class="tip">密码</p>
                        <div class="dlIpt pwd clearfix">
                            <input type="password">
                        </div>

                        <button class="loginBtn">登录</button>
                        <p class="loginTip">没有账号？立即注册</p>
                        <button class="regBtn">立即注册</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>-->


<script type="text/javascript">
    $(function () {
        var index = '<?php echo $key;?>';
       // $('.textWrap .textBox').eq(index).show().siblings().hide();
        // 标签切换
        // $('.navLink').click(function () {
        //     var i = $(this).index();
        //    // console.log(i)
        //     $('.textWrap .textBox').eq(i).show().siblings().hide();
        //     $(this).addClass('active').siblings().removeClass('active');
        //
        // });
        // 标签切换
        $('.about-nav li a').on('click',function () {
            var ii = $(this).parents('li').index();
            var tx = $(this).text();
            // console.log(ii);
            $('.bzzx_title').text(tx);
            $(this).addClass('active').parents('li').siblings().find('a').removeClass('active');
            $('.textWrap .textBox:eq('+ii+')').removeClass('hide').siblings().addClass('hide');

        });

        $('.addBtn').on('click',function () { // 立即加入
            $('.about-nav li a').eq(3).click();
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
