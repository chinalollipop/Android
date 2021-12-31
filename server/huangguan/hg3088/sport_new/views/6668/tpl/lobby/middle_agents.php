<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$host = $_SESSION['HOST_SESSION'];

$key = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;

?>
<style>
body{background: #eeeeee;}
</style>

<div class="banner" style="background: url('<?php echo $tplNmaeSession;?>images/dl.jpg') no-repeat center center;width: 100%;height: 504px"></div>
<div class="agencyWrap">
    <div class="nav">
        <img class="nav-img" src="<?php echo $tplNmaeSession;?>images/nav.png" alt="">
        <ul>
            <li><a class="active" href="javascript:;">体育竞技</a></li>
            <li><a href="javascript:;" class="to_lives">真人视讯</a></li>
            <li><a href="javascript:;" class="to_games">电子游戏</a></li>
            <li><a href="javascript:;"class="to_lotterys">彩票游戏</a></li>
            <li><a href="javascript:;" class="to_promos">优惠活动</a></li>
            <li><a href="javascript:;" class="to_downloadapp">手机APP</a></li>
            <li><a href="javascript:;" class="to_livechat">在线客服</a></li>
        </ul>
        <span class="bottomLogo agent_icon"></span>
    </div>
    <div class="articleWrap">
        <div class="articleNav clearfix">
            <div class="articleItem navLink <?php echo ($key==0?'active':'');?>">联盟方案</div>
            <div class="articleItem navLink">联盟协议</div>
            <div class="articleItem navLink <?php echo ($key==2?'active':'');?>">推广地址</div>
            <div class="articleItem navLink">代理注册</div>
            <a class="articleItem" href="<?php echo $_SESSION['AGENT_LOGIN_URL'];?>" target="_blank"> 代理登录 </a>
        </div>
        <div class="textWrap">
            <div class="textBox textItem ">
                <h1>联盟方案</h1>

                <div class="gywm_mnbr_wenzi">

                    <p class="MsoNormal text_indent">皇冠现金网总部位于菲律宾，是获得英属维京群岛政府认证的合法互联网体育博彩公司，汇集了全球的博彩业界精英，皇冠体育致力打造符合体育博彩市场需求，并值得体育彩迷信赖的网上投注平台，务求能成为为每位体育彩迷量身订造，成为世界性博彩投注网站</p>
                    <p class="MsoNormal text_indent">皇冠现金网提供安全稳定的投注系统是本公司对每位体育彩迷的承诺，专业技术团队拥有高度的系统保障和支持能力，高效健全的支付平台更确保在线投注的公平、彩金支付机制的安全快速更是业界之冠，所有会员的彩金都能在5分钟内到账，对会员承诺的履行永远都被我们视为成功的关键。无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入皇冠国际合作伙伴的行列，无须负担任何费用，就可以开始无上限的收入。皇冠现金网，绝对是您最明智的选择!
                    </p>
                    <p class="MsoNormal"></p>
                    <h2>代理合作独立平台：</h2>

                    <p class="MsoNormal text_indent">我们为您提供单独的代理后台，您可以在后台不受限制的开出下线，并且实时了解下线会员输赢，投注，存款，取款情况。代理后台有一个您的专属链接，您可以直接将您的专属链接链接在网站、论坛、博客等等可链接的网络页面，也可在QQ群等社交软件里面发送您的专属链接，只要通过您的专属链接注册的会员都算是您的下线。有便利的计算机上网设备。有一定的人脉资源、网络资源或自己的广告网站。推广方式简单方便，推广渠道多种多样。
                    </p>

                    <h2>代理佣金收入：</h2>
                    <p class="MsoNormal">结算方法：</p>

                    <h2>A收入计划：</h2>
                    <table class="table" border="1" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td colspan="5" align="center" class="style1">
								<span class="style3">
								<span>注册代理后请联系代理专员QQ:<span class="agent_service_number"> </span> </span></span></td>
                        </tr>
                        <tr>
                            <td height="20" rowspan="2" align="middle">
                                当月营利
                            </td>
                            <td rowspan="2" align="middle">
                                当月最低有效会员
                            </td>
                            <td align="middle" colspan="3" class="style1">当月退佣比例当月退佣比例</td>
                        </tr>
                        <tr>
                            <td align="middle" class="style1">
                                体育博弈</td>
                            <td align="middle" class="style1">
                                真人视讯/棋牌/电子/电竞</td>
                            <td align="middle" class="style1">
                                彩票游戏</td>
                        </tr>
                        <tr align="middle">
                            <td height="20" class="style1">
                                100-50000</td>
                            <td class="style1">
                                5或以上</td>
                            <td class="style1">
                                30%</td>
                            <td class="style1">
                                20%</td>
                            <td class="style1">
                                20%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20" class="style1">
                                50001-500000</td>
                            <td class="style1">
                                10或以上</td>
                            <td class="style1">
                                35%</td>
                            <td style="padding: 0px; margin: 0px; width: 74px;" class="style1">
                                25%</td>
                            <td class="style1">
                                25%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20" class="style1">
                                500001-3000000</td>
                            <td class="style1">
                                25或以上</td>
                            <td class="style1">
                                40%</td>
                            <td style="padding: 0px; margin: 0px; width: 74px;" class="style1">
                                30%</td>
                            <td class="style1">
                                30%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20" class="style1">
                                3000001-5000000</td>
                            <td class="style1">
                                50或以上</td>
                            <td class="style1">
                                45%</td>
                            <td style="padding: 0px; margin: 0px; width: 74px;" class="style1">
                                35%</td>
                            <td class="style1">
                                35%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20" class="style1">
                                5000001以上</td>
                            <td class="style1">
                                100或以上</td>
                            <td class="style1">
                                50%</td>
                            <td style="padding: 0px; margin: 0px; width: 74px;" class="style1">
                                40%</td>
                            <td class="style1">
                                40%</td>
                        </tr>
                        </tbody></table>


                    <h2>B收入计划：</h2>

                    <table class="table" border="1" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td height="20" align="left">
                                &nbsp;如当月在您的代理账号无盈利的情况下，代理可以从体育投注总额的百分比作为推广奖金。</td>
                        </tr>
                        <tr align="left">
                            <td height="20">
                                &nbsp;1：一个月内公司在您的代理账号内投注总额达到1000元-5000000元，可享受0.3%的推广奖金。</td>
                        </tr>
                        <tr align="left">
                            <td height="20">
                                &nbsp;2：一个月内公司在您的代理账号内投注总额达到5000001元-10000000元，可享受0.4%的推广奖金。</td>
                        </tr>
                        <tr align="left">
                            <td height="20">
                                &nbsp;3：一个月内公司在您的代理账号内投注总额达到10000001元-20000000元，可享受0.5%的推广奖金。</td>
                        </tr>
                        <tr align="left">
                            <td height="20">
                                &nbsp;4：一个月内公司在您的代理账号内投注总额达到20000001元-50000000元，可享受0.7%的推广奖金。</td>
                        </tr>
                        <tr align="left">
                            <td height="20">
                                &nbsp;5：一个月内公司在您的代理账号内投注总额达到50000001元以上，可享受1%的推广奖金。</td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="MsoNormal text_indent">举例：
                        比如您本月的代理无盈利情况下，但体育有效投注额在1000000元的情况下那么您的收入如：1000000x0.003=3000（推广奖金），真人视讯、彩票游戏有效投注将不计算在内，不能参与B收入计划。</p>

                    <h2>计划相关说明：</h2>
                    <p class="MsoNormal text_indent">参与B收入计划需代理每个月发展新的会员加入,代理商每个月必须保持增长3位新有效会员才可享有推广金.（举例：如当月有效会员10个，当月领取了推广奖金，那么下个月有效会员如果没有达到13个有效会员的情况，下个月将没有推广金收入）。</p>

                    <h2>什么是有效会员？</h2>
                    <p class="MsoNormal text_indent">有效会员定义为当月有存款充值3000元以上且有效投注额在3000以上。
                    </p>

                    <h2>佣金结算：</h2>
                    <p class="MsoNormal text_indent">您的代理下玩家所有盈利均由公司承担，您不用承担任何客户赢钱的风险，佣金每个月5号结算。结算完毕后，即可申请提款，您的所有提款将在24小时内到达您指定的收款银行帐号</p>

                    <h2>贴心服务：</h2>
                    <p class="MsoNormal text_indent">24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟皇冠现金网合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！</p>

                    <p class="MsoNormal text_indent">代理申请成功后请联系<font class="red">代理专员QQ：<span class="agent_service_number"> </span> </font>
                        （代理请务必添加），我们将全程为您提供更好的代理便捷服务以及代理方面咨询。
                    </p>

                </div>

                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem">
                <h1>联盟协议</h1>
                <div class="gywm_mnbr_wenzi">
                    <h2>一、皇冠现金网对代理联盟的权利与义务</h2>
                    <p class="MsoNormal text_indent">皇冠现金网的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守皇冠现金网的会员条例，政策及操作程序。皇冠现金网保留拒绝或冻结联盟/会员账户权利
                        代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 皇冠现金网的客服部会根据代理联盟旗下的会员计算所得的佣金。</p>
                    <p class="MsoNormal text_indent">皇冠现金网保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，皇冠现金网会以电邮、网站公告等方法通知代理联盟。 代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。</p>
                    <h2>二、代理联盟对皇冠现金网的权力及义务</h2>
                    <p class="MsoNormal text_indent">代理联盟应尽其所能，广泛地宣传、销售及推广皇冠现金网，使代理本身及皇冠现金网的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广皇冠现金网，并有责任义务告知旗下会员所有皇冠现金网的相关优惠条件及产品。
                        代理联盟选择的皇冠现金网推广手法若需付费，则代理应承担该费用。</p>
                    <p class="MsoNormal text_indent">任何皇冠现金网相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，皇冠现金网保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽皇冠现金网。</p>
                    <h2>三、规则条款</h2>

                    <p class="MsoNormal text_indent text_indent">各阶层代理联盟不可在未经皇冠现金网许可情况下开设双/多个的代理账号，也不可从皇冠现金网账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，皇冠现金网有权终止并封存账号及所有在游戏中赚取的佣金。 为确保所有皇冠现金网会员账号隐私与权益，皇冠现金网不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害皇冠现金网会员隐私行为，皇冠现金网有权取消代理联盟红利，并取消代理联盟账号。</p>

                    <p class="MsoNormal text_indent">代理联盟旗下的会员不得开设多于一个的账户。皇冠现金网有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，皇冠现金网有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金 如代理联盟旗下的会员因为违反条例而被禁止享用皇冠现金网的游戏，或皇冠现金网退回存款给会员，皇冠现金网将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，皇冠现金网保留相关佣金直至审核完成。</p>

                    <p class="MsoNormal text_indent">合约内的条件会以皇冠现金网通知接受代理联盟加入后开始执行。皇冠现金网及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知皇冠现金网。 代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，皇冠现金网有权立即终止合约。</p>

                    <p class="MsoNormal text_indent">在没有皇冠现金网许可下，代理联盟不能透露及授权皇冠现金网相关资料或数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。 在合约终止后，代理联盟及皇冠现金网将不再履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。</p>

                </div>

                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem">
                <h1>推广地址</h1>
                <p style="text-align: center">作为一名尊贵的皇冠合作伙伴，您可以利用您的资源简单赚取高额佣金，轻松实现成功与财富的梦想！</p>
                <div class="iptWrap">
                    <input type="text" class="agents_url" value="<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=您的代理编号">
                    <img class="copyButton" data-clipboard-target=".agents_url" src="<?php echo $tplNmaeSession;?>images/copy.jpg" alt="">
                </div>
                <a class="addBtn">立即加入</a>
            </div>
            <div class="textBox textItem">
                <form class="agents_reg_form" onsubmit="return false"> <!-- return false 防止表单提交后自动跳转 -->
                    <h1>代理注册</h1>
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
        $('.textWrap .textBox').eq(index).show().siblings().hide();
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
