<?php
session_start();

$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$host = $_SESSION['HOST_SESSION'];

$key = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;
$companyName = $_SESSION['COMPANY_NAME_SESSION'] ;

?>
<style>
    .agencyWrap .nav li span.icon_sport{background-position: 0px -4px;}
    .agencyWrap .nav li span.icon_live{background-position: 0px -58px;}
    .agencyWrap .nav li span.icon_game{background-position: 0px -108px;}
    .agencyWrap .nav li span.icon_lottery{background-position: 0px -164px;}
    .agencyWrap .nav li span.icon_dj{background-position: 0px -220px;}
    .agencyWrap .nav li span.icon_promo{background-position: 0px -276px;}
    .agencyWrap .nav li span.icon_app{background-position: 0px -328px;}
    .agencyWrap .nav li span.icon_kf{background-position: 0px -382px;}
    .articleWrap_agent h1{text-align: center;font-size: 24px;}
</style>

<div class="agencyWrap">
    <div class="nav nav_agents border_shadow">
        <div class="nav_top btn_game">
            <img class="nav-img" src="../<?php echo $tplNmaeSession;?>images/logo_about.png" alt="">
        </div>
        <ul>
            <li><a href="javascript:;" class="to_sports"> <span class="icon_sport"></span> 体育竞技</a></li>
            <li><a href="javascript:;" class="to_lives"> <span class="icon_live"></span> 真人视讯</a></li>
            <li><a href="javascript:;" class="to_games"> <span class="icon_game"></span> 电子游戏</a></li>
            <li><a href="javascript:;"class="to_lotterys"> <span class="icon_lottery"></span> 彩票游戏</a></li>
            <li><a href="javascript:;"class="to_dianjing"> <span class="icon_dj"></span> 电子竞技</a></li>
            <li><a href="javascript:;" class="to_promos"> <span class="icon_promo"></span> 优惠活动</a></li>
            <li><a href="javascript:;" class="to_downloadapp"> <span class="icon_app"></span> 手机APP</a></li>
            <li><a href="javascript:;" class="to_livechat"> <span class="icon_kf"></span> 在线客服</a></li>
        </ul>

    </div>
    <div class="articleWrap articleWrap_agent">
        <div class="articleNav clearfix border_shadow">
            <div class="articleItem navLink <?php echo ($key==0?'active':'');?>">联盟方案</div>
            <div class="articleItem navLink">联盟协议</div>
            <div class="articleItem navLink <?php echo ($key==2?'active':'');?>">推广地址</div>
            <div class="articleItem navLink">代理注册</div>
            <a class="articleItem" href="<?php echo $_SESSION['AGENT_LOGIN_URL'];?>" target="_blank"> 代理登录 </a>
        </div>
        <div class="textWrap border_shadow">
            <div class="textBox textItem ">
                <h1>联盟方案</h1>
                <p>
                    <?php echo $companyName;?>拥有欧洲马耳他博彩管理局（MGA）、英国GC监督委员会（Gambling Commission）和菲律宾政府博彩委员会（PAGCOR）颁发的合法执照。注册于英属维尔京群岛，是受国际博彩协会认可的合法博彩公司。
                </p>
                <p>
                    一直以来我们秉承用户至上的原则，务实经营的理念，聚集优秀的高素质人才资源与业界高质量精英团队合作，致力于打造体育精品，竭诚为每一位用户奉献全方位的极致体验和高端的游戏享受。<?php echo $companyName;?>与业界知名的博彩产品供应商如AsiaGaming, BBin,Microgaming等拥有良好的深度合作关系，提供最优质的体育赛事，真人娱乐场以及几百款小游戏，并不断的开发高质量的娱乐新品。
                </p>
                <p>
                    全年365天，<?php echo $companyName;?>每一天与您同在，为您提供7x24小时全天候咨询服务。同时我们拥有经验丰富，水平一流的科技团队，为网站的安全稳定保驾护航，为产品与服务的创新提供源源不断的动力。
                </p>
                <h2>代理条件：</h2>
                <p>a.具有便利的计算机上网设备。</p>
                <p>b.有一定的人脉资源、网络资源或自己的广告网站。</p>
                <h2>代理独立平台：</h2>
                <p>
                    我们为您提供单独的代理后台，您可以在后台不受限制的开出下线，并且实时了解下线会员输赢，投注，存款，取款情况。代理后台有一个您的专属链接，您可以直接将您的专属链接链接在网站、论坛、博客等等可链接的网络页面，也可在群里面发送您的专属链接，只要通过您的专属链接注册的会员都算是您的下线。推广方式简单方便，推广渠道多种多样</p>
                <p class="red">【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】</p>
                <p class="red">QQ：<span class="agent_service_number"> </span> （代理请务必添加）</p>
                <p>【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】</p>
                <p>如果您成为"<?php echo $companyName;?>博彩"的代理，您就可拥有以下收入：</p>
                <p>结算方法：</p>
                <p>A收入：比如您本月的代理账号内【有赢利】的情况下，就可拥有以下收入: </p>
                <table class="table" border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>当月营利</td>
                        <td>当月最低有效会员</td>
                        <td>体育</td>
                        <td>真人/电子/棋牌/电竞</td>
                        <td>彩票/六合彩</td>
                    </tr>
                    <tr>
                        <td>100-50000</td>
                        <td>5或以上</td>
                        <td>30%</td>
                        <td>20%</td>
                        <td>20%</td>
                    </tr>
                    <tr>
                        <td>50001-500000</td>
                        <td>10或以上</td>
                        <td>35%</td>
                        <td>25%</td>
                        <td>25%</td>
                    </tr>
                    <tr>
                        <td>500001-800000</td>
                        <td>15或以上</td>
                        <td>40%</td>
                        <td>30%</td>
                        <td>30%</td>
                    </tr>
                    <tr>
                        <td>800001-2000000</td>
                        <td>20或以上</td>
                        <td>45%</td>
                        <td>35%</td>
                        <td>35%</td>
                    </tr>
                    <tr>
                        <td>2000001以上</td>
                        <td>50或以上</td>
                        <td>50%</td>
                        <td>40%</td>
                        <td>40%</td>
                    </tr>
                </table>
                <p>B收入：比如您本月的代理账号内【没有赢利】的情况下，就可拥有以下收入: </p>
                <p>1.一个月内您的代理账号体育流水达到1-2000000元，可得流水佣金0.3%！</p>
                <p>2.一个月内您的代理账号体育流水达到2000001元-8000000元，可得流水佣金0.4%！ </p>
                <p>3.一个月内您的代理账号体育流水达到8000001元-20000000元，可得流水佣金0.5%！ </p>
                <p>3.一个月内您的代理账号体育流水达到8000001元-20000000元，可得流水佣金0.5%！ </p>
                <p>5.一个月内您的代理账号体育流水达到50000001元以上，可得流水佣金1.0%！ </p>
                <p>注：代理商每个月必须保持增长6位有效会员才可享有推广金.(有效投注3000以上算一位有效会员)。 </p>
                <p>如：您本月代理无盈利，但是打码量为 180万，那么您的收入是：</p>
                <p>1800000*0.003=5400元佣金 </p>
                <p>
                    您的代理账号的赢利都由公司承担，您不用承担任何客户赢输钱的风险，佣金每月5日结算一次。结算完毕后，即可申请提款，您的所有提款将在3个工作日内到达您指定的收款银行帐号.以上计算方式仅供参考,具体参照详细规定。</p>
                <h2>贴心服务：</h2>
                <p>24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟<?php echo $companyName;?>合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！ </p>
                <h2>代理申请联系方式： </h2>
                <p>QQ：<span class="agent_service_number"> </span> （代理请务必添加）</p>
                <a class="addBtn btn_game">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <h1>联盟协议</h1>
                <h2>一、<?php echo $companyName;?>对代理联盟的权利与义务</h2>
                <p>1.<?php echo $companyName;?>的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守<?php echo $companyName;?>的会员条例，政策及操作程序。<?php echo $companyName;?>保留拒绝或冻结联盟/会员账户权利。</p>
                <p>2.代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 <?php echo $companyName;?>的客服部会根据代理联盟旗下的会员计算所得的佣金。</p>
                <p>3.<?php echo $companyName;?>保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，<?php echo $companyName;?>会以电邮、网站公告等方法通知代理联盟。 代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。</p>
                <h2>二、代理联盟对<?php echo $companyName;?>的权力及义务</h2>
                <p>1.代理联盟应尽其所能，广泛地宣传、销售及推广<?php echo $companyName;?>，使代理本身及<?php echo $companyName;?>的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广<?php echo $companyName;?>，并有责任义务告知旗下会员所有<?php echo $companyName;?>的相关优惠条件及产品。</p>
                <p>2.代理联盟选择的<?php echo $companyName;?>推广手法若需付费，则代理应承担该费用。</p>
                <p><?php echo $companyName;?>相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，<?php echo $companyName;?>保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽<?php echo $companyName;?>。3.任何
                <h2>三、规则条款</h2>
                <p>1.各阶层代理联盟不可在未经<?php echo $companyName;?>许可情况下开设双/多个的代理账号，也不可从<?php echo $companyName;?>账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，<?php echo $companyName;?>有权终止并封存账号及所有在游戏中赚取的佣金。</p>
                <p>2.为确保所有<?php echo $companyName;?>会员账号隐私与权益，<?php echo $companyName;?>不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害<?php echo $companyName;?>会员隐私行为，<?php echo $companyName;?>有权取消代理联盟红利，并取消代理联盟账号。</p>
                <p>3.代理联盟旗下的会员不得开设多于一个的账户。<?php echo $companyName;?>有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，<?php echo $companyName;?>有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金 。</p>
                <p>4.如代理联盟旗下的会员因为违反条例而被禁止享用<?php echo $companyName;?>的游戏，或<?php echo $companyName;?>退回存款给会员，<?php echo $companyName;?>将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，<?php echo $companyName;?>保留相关佣金直至审核完成。</p>
                <p>5.合约内的条件会以<?php echo $companyName;?>通知接受代理联盟加入后开始执行。<?php echo $companyName;?>及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知<?php echo $companyName;?>。 代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，<?php echo $companyName;?>有权立即终止合约。</p>
                <p>6.在没有<?php echo $companyName;?>许可下，代理联盟不能透露及授权<?php echo $companyName;?>相关密数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。</p>
                <p>7.在合约终止后，代理联盟及<?php echo $companyName;?>将不须要履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。</p>

                <a class="addBtn btn_game">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <h1>推广地址</h1>
                <p style="text-align: center">作为一名尊贵的皇冠合作伙伴，您可以利用您的资源简单赚取高额佣金，轻松实现成功与财富的梦想！</p>
                <div class="iptWrap">
                    <input type="text" class="agents_url" value="<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=您的代理编号">
                    <span class="copyButton btn_game" data-clipboard-target=".agents_url" > 复制网址 </span>
                </div>
                <a class="addBtn btn_game">立即加入</a>
            </div>
            <div class="textBox textItem hide">
                <form class="agents_reg_form" onsubmit="return false"> <!-- return false 防止表单提交后自动跳转 -->
                    <h1>代理注册</h1>
                    <div class="regAgent">
                        <div class="regTit">
                            <span class="btn_game">1</span> <span>注册代理账号</span>
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
                            <span class="btn_game">2</span><span>代理基本数据</span>
                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>真实姓名 </p>
                                <div class="regIpt realname agent_icon">
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
                            <span class="btn_game">3</span><span>代理银行资料</span>
                        </div>
                        <div class="regAcount clearfix">
                            <div class="refItem fl">
                                <p><span class="red">*</span>开户银行 </p>
                                <div class="regIpt bank agent_icon">
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
                        <button class="firstBtn agents_submit btn_game">确认提交</button>
                        <button class="btn_game" type="reset" onclick="$('.agents_reg_form')[0].reset()">重置域名</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    // 代理登录验证
    function checkAgent(){
        var username = $('#login_username').val();
        var pwd = $('#login_password').val();
        if(!username || !isNum(username)){
            layer.msg('请输入正确的用户名!',{time:alertTime});
            return false;
        }
        if(!pwd){
            layer.msg('请输入登录密码!',{time:alertTime});
            return false;
        }
    }

    $(function () {
        var index = '<?php echo $key;?>';

        // 标签切换
        $('.navLink').click(function () {
            var i = $(this).index();
            // console.log(i)
            $('.textWrap .textBox').eq(i).show().siblings().hide();
            $(this).addClass('active').siblings().removeClass('active');

        });
        $('.addBtn').on('click',function () { // 立即加入
            $('.navLink').eq(3).click();
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
                            // if(res.status ==200){
                            //     window.location.href = '/' ;
                            // }
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
        changeAgentTab();

        // 登录注册切换
        function changeAgentTab(){
            $('.reg-title .reg-title-text').on('click',function () {
                var type = $(this).attr('data-to');
                var ot_type = $(this).siblings().attr('data-to');
                // console.log(type);
                // console.log(ot_type);
                $(this).addClass('active').siblings().removeClass('active');
                if(type=='reg'){
                    $('.login-box-wrap').css({'transform':'translateX(-430px)'});
                }else{
                    $('.login-box-wrap').css({'transform':'translateX(0)'});
                }
            })
        }

        // game hover
        $('#section3 .game-info').hover(function () {
            $(this).addClass('active').siblings().removeClass('active');
            clearInterval(timer);
        })

        var game_i = 0;
        function timerActive() {
            var html = $('#new .game-info')[game_i];
            $(html).addClass('active').siblings().removeClass('active')
            game_i = game_i > 3 ? 0 : game_i += 1
        }
        var timer= setInterval(function () {
            timerActive()
        }, 2500)


    })
</script>