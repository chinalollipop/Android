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
    .special_tip{margin:20px 0;color:#E82626}
    table{width:100%;border:1px solid #D6D5D5;border-collapse:collapse;text-align:center;vertical-align:middle;margin-bottom:20px}
    td{width:20%;height:40px;color:#D6D5D5}
    tr:first-child>td{background:#fcef8f;color:#333}
    .join_us{display:block;width:600px;height:50px;line-height:50px;font-size:24px;color:#fff;background:#E82626;border:0;outline:0;cursor:pointer;border-radius:10px;box-shadow:3px 3px 2px #000;margin:40px auto;text-align:center}
    select[name='bank_name'] option {color:#D6D5D5;}
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
                    <p class="agent_tip">
                        "<?php echo $companyName;?>"总部位于菲律宾，是获得英属维京群岛政府认证的合法互联网体育博彩公司，汇集了全球的博彩业界精英，"<?php echo $companyName;?>"致力打造符合体育博彩市场需求，并值得体育彩迷信赖的网上投注平台，由业界精英组成的金牌专业团队，凭借对体育博彩市场的丰富经验，不断对体育博彩市场进行深入透彻的研究，并进一步将研究结果实践在产品完善化上.网罗世界各地各种体育比赛项目并提供多样化玩法选择，务求能成为为每位体育彩迷量身订造，以满足彩迷们的体育观赏及投注喜好为目标的世界性体彩投注网站。
                    </p>
                    <p class="agent_tip">
                        "<?php echo $companyName;?>"以雄厚实力建构了强大的网络在线体育博彩投注平台，提供安全稳定的投注系统是本公司对每位体育彩迷的承诺，专业技术团队拥有高度的系统保障和支持能力，高效健全的支付平台更确保在线投注的公平、公正和安全.彩金支付机制的安全快速更是业界之冠，所有会员的彩金都能在12小时内到账，对会员承诺的履行永远都被我们视为成功的关键，亦因此"<?php echo $companyName;?>"能在全球网络在线体育投注平台赢得可靠可信的美誉.无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入澳门线上娱乐合作伙伴的行列，无须负担任何费用，就可以开始无上限的收入。<?php echo $companyName;?>，绝对是您最聪明的选择!
                    </p>
                    <h3>代理条件</h3>
                    <p class="agent_tip">
                        a.具有便利的计算机上网设备。
                    </p>
                    <p class="agent_tip">
                        b.有一定的人脉资源、网络资源或自己的广告网站。
                    </p>
                    <h3>代理独立平台:</h3>
                    <p class="agent_tip">
                        我们为您提供单独的代理后台，您可以在后台不受限制的开出下线，并且实时了解下线会员输赢，投注，存款，取款情况。代理后台有一个您的专属链接，您可以直接将您的专属链接链接在网站、论坛、博客等等可链接的网络页面，也可在群里面发送您的专属链接，只要通过您的专属链接注册的会员都算是您的下线。推广方式简单方便，推广渠道多种多样
                    </p>
                    <p class="special_tip">
                        【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】
                    </p>
                    <p class="special_tip">
                        QQ：<span class="agent_service_number"> </span> （代理请务必添加）
                    </p>
                    <p class="agent_tip">
                        【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】
                    </p>
                    <p class="agent_tip">
                        如果您成为"<?php echo $companyName;?>"的代理，您就可拥有以下收入：
                    </p>
                    <p class="agent_tip">
                        结算方法：
                    </p>
                    <p class="agent_tip">
                        A收入：比如您本月的代理账号内【有赢利】的情况下，就可拥有以下收入:
                    </p>
                    <table border="1">
                        <tbody>
                        <tr>
                            <td>当月营利</td>
                            <td>当月最低有效会员</td>
                            <td>体育</td>
                            <td>真人/电子/棋牌</td>
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
                        </tbody>
                    </table>
                    <p class="agent_tip">
                        B收入：比如您本月的代理账号内【没有赢利】的情况下，就可拥有以下收入:
                    </p>
                    <p class="agent_tip">
                        1.一个月内您的代理账号流水达到1-2000000元，可得流水佣金0.3%！
                    </p>
                    <p class="agent_tip">
                        2.一个月内您的代理账号流水达到2000001元-8000000元，可得流水佣金0.4%！
                    </p>
                    <p class="agent_tip">
                        3.一个月内您的代理账号流水达到8000001元-20000000元，可得流水佣金0.5%！
                    </p>
                    <p class="agent_tip">
                        4.一个月内您的代理账号流水达到8000001元-20000000元，可得流水佣金0.5%！
                    </p>
                    <p class="agent_tip">
                        5.一个月内您的代理账号流水达到50000001元以上，可得流水佣金1.0%！
                    </p>
                    <p class="agent_tip">
                        注：代理商每个月必须保持增长6位有效会员才可享有推广金.(有效投注3000以上算一位有效会员)。
                    </p>
                    <p class="agent_tip">
                        如：您本月代理无盈利，但是打码量为 180万，那么您的收入是：
                    </p>
                    <p class="agent_tip">
                        1800000*0.003=5400元佣金
                    </p>
                    <p class="agent_tip">
                        您的代理账号的赢利都由公司承担，您不用承担任何客户赢输钱的风险，佣金每月5日结算一次。结算完毕后，即可申请提款，您的所有提款将在3个工作日内到达您指定的收款银行帐号.以上计算方式仅供参考,具体参照详细规定。
                    </p>
                    <h3>贴心服务：</h3>
                    <p class="agent_tip">
                        24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟澳门线上娱乐合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！
                    </p>
                    <h3>代理申请联系方式：</h3>
                    <p class="agent_tip">QQ：<span class="agent_service_number"> </span>（代理请务必添加）</p>
                    <a class="join_us addBtn">立即加入</a>

            </div>
            <div class="textBox textItem hide">

                    <h2>联盟协议</h2>
                    <h3>一、澳门线上娱乐对代理联盟的权利与义务</h3>
                    <p class="agent_tip">
                        1.澳门线上娱乐国际的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守澳门线上娱乐的会员条例，政策及操作程序。澳门线上娱乐保留拒绝或冻结联盟/会员账户权利。
                    </p>
                    <p class="agent_tip">
                        2.代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 澳门线上娱乐的客服部会根据代理联盟旗下的会员计算所得的佣金。
                    </p>
                    <p class="agent_tip">
                        3.澳门线上娱乐保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，澳门线上娱乐会以电邮、网站公告等方法通知代理联盟。
                        代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。
                    </p>
                    <h3>二、代理联盟对澳门线上娱乐的权力及义务</h3>
                    <p class="agent_tip">
                        1.代理联盟应尽其所能，广泛地宣传、销售及推广澳门线上娱乐，使代理本身及澳门线上娱乐的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广澳门线上娱乐，并有责任义务告知旗下会员所有澳门线上娱乐的相关优惠条件及产品。
                    </p>
                    <p class="agent_tip">
                        2.代理联盟选择的澳门线上娱乐推广手法若需付费，则代理应承担该费用。
                    </p>
                    <p class="agent_tip">
                        3.任何澳门线上娱乐相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，澳门线上娱乐保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽澳门线上娱乐。
                    </p>
                    <h3>三、规则条款</h3>
                    <p class="agent_tip">
                        1.各阶层代理联盟不可在未经澳门线上娱乐许可情况下开设双/多个的代理账号，也不可从澳门线上娱乐账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，澳门线上娱乐有权终止并封存账号及所有在游戏中赚取的佣金。
                    </p>
                    <p class="agent_tip">
                        2.为确保所有澳门线上娱乐会员账号隐私与权益，澳门线上娱乐不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害澳门线上娱乐会员隐私行为，澳门线上娱乐有权取消代理联盟红利，并取消代理联盟账号。
                    </p>
                    <p class="agent_tip">
                        3.代理联盟旗下的会员不得开设多于一个的账户。澳门线上娱乐有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，澳门线上娱乐有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金
                        。
                    </p>
                    <p class="agent_tip">
                        4.如代理联盟旗下的会员因为违反条例而被禁止享用澳门线上娱乐的游戏，或澳门线上娱乐退回存款给会员，澳门线上娱乐将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，澳门线上娱乐保留相关佣金直至审核完成。
                    </p>
                    <p class="agent_tip">
                        5.合约内的条件会以澳门线上娱乐通知接受代理联盟加入后开始执行。澳门线上娱乐及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知澳门线上娱乐。
                        代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，澳门线上娱乐有权立即终止合约。

                    </p>
                    <p class="agent_tip">
                        6.在没有澳门线上娱乐许可下，代理联盟不能透露及授权澳门线上娱乐相关密数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。

                    </p>
                    <p class="agent_tip">
                        7.在合约终止后，代理联盟及澳门线上娱乐将不须要履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。
                    </p>
                    <a class="join_us addBtn">
                        立即加入
                    </a>

            </div>
            <div class="textBox textItem hide">
                <p style="text-align: center">作为一名尊贵的<?php echo $companyName;?>合作伙伴，您可以利用您的资源简单赚取高额佣金，轻松实现成功与财富的梦想！</p>
                <div class="iptWrap">
                    <input type="text" class="agents_url" value="<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=您的代理编号">
                    <button class="repeat copyButton" data-clipboard-target=".agents_url" >复制文字</button>
                </div>
                <a class="join_us addBtn">立即加入</a>
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