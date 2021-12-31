<?php
session_start();
include_once('../../include/config.inc.php');
$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
    <title class="web-title"></title>
<style type="text/css">
    .content-center{bottom: 0;}
    .ProTab_nav{height: 48px;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">

    </div>

    <!-- 中间注册表单内容 -->
    <div class="content-center">

        <div class="member_reg" >

            <!--  标签 -->
            <ul class="ProTab_nav">
                <li class="ProTab_1 on" onclick="ProTab_Js(1)">联盟方案</li>
                <li class="ProTab_2" onclick="ProTab_Js(2)">联盟协议</li>
               <!-- <li class="ProTab_3" onclick="ProTab_Js(3)">推广地址</li>-->
                <li class="ProTab_4" onclick="ProTab_Js(4)">代理注册</li>
                <li class="ProTab_5" onclick="window.open('<?php echo returnAgentUrl();?>')">代理登录</li>
            </ul>

         <!-- 联盟方案开始 -->
            <div class="agents_content material-card-content ProTab_con_1" style="display: block;">

                <!-- 0086 开始-->
                <div class="gyqimcont" >
                    <div class="linabtxs">
                        <h2>0086联盟方案</h2>
                    </div>

                    <p class="MsoNormal text_indent">"皇冠体育博彩"总部位于菲律宾，是获得英属维京群岛政府认证的合法互联网体育博彩公司，汇集了全球的博彩业界精英，"新宝皇冠体育博彩"致力打造符合体育博彩市场需求，并值得体育彩迷信赖的网上投注平台，由业界精英组成的金牌专业团队，凭借对体育博彩市场的丰富经验，不断对体育博彩市场进行深入透彻的研究，并进一步将研究结果实践在产品完善化上.网罗世界各地各种体育比赛项目并提供多样化玩法选择，务求能成为为每位体育彩迷量身订造，以满足彩迷们的体育观赏及投注喜好为目标的世界性体彩投注网站。</p>
                    <p class="MsoNormal text_indent">"皇冠体育博彩"以雄厚实力建构了强大的网络在线体育博彩投注平台，提供安全稳定的投注系统是本公司对每位体育彩迷的承诺，专业技术团队拥有高度的系统保障和支持能力，高效健全的支付平台更确保在线投注的公平、公正和安全.彩金支付机制的安全快速更是业界之冠，所有会员的彩金都能在12小时内到账，对会员承诺的履行永远都被我们视为成功的关键，亦因此"皇冠体育博彩"能在全球网络在线体育投注平台赢得可靠可信的美誉.无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入皇冠国际合作伙伴的行列，无须负担任何费用，就可以开始无上限的收入。皇冠国际，绝对是您最聪明的选择!</p>
                    <p class="MsoNormal"><strong>代理条件：</strong></p>
                    <br>a.具有便利的计算机上网设备。
                    <br>b.有一定的人脉资源、网络资源或自己的广告网站。
                    <p class="MsoNormal"><strong>代理独立平台：</strong></p>
                    <br>我们为您提供单独的代理后台，您可以在后台不受限制的开出下线，并且实时了解下线会员输赢，投注，存款，取款情况。代理后台有一个您的专属链接，您可以直接将您的专属链接链接在网站、论坛、博客等等可链接的网络页面，也可在群里面发送您的专属链接，只要通过您的专属链接注册的会员都算是您的下线。推广方式简单方便，推广渠道多种多样。
                    <br><span class="hong">【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】 </span>

                    <br><br><font color="#ff0000">QQ：<span class="agents_qq_number"> </span> （代理请务必添加） </font>
                    <br>代理收入：新创A,B,C两种收入，其中收入叠加方式：A+C/B+C
                    <br>【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】
                    <br>如果您成为"皇冠体育博彩"的代理，您就可拥有以下收入：
                    <p class="MsoNormal"><strong>结算方法：</strong></p>
                    <br>A收入：比如您本月的代理账号内【有赢利】的情况下，就可拥有以下收入:
                    <br>
                    <br>
                    <table border="1" class="table-bordered">
                        <tbody>
                        <tr>
                            <td >当月营利</td>
                            <td >当月最低有效会员</td>
                            <td >体育</td>
                            <td >真人AG/BBIN</td>
                            <td >彩票/六合彩</td>
                        </tr>
                        <tr align="middle">
                            <td height="20">100-50000</td>
                            <td>5或以上</td>
                            <td>30%</td>
                            <td>20%</td>
                            <td>20%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20">50001-500000</td>
                            <td>10或以上</td>
                            <td>35%</td>
                            <td>25%</td>
                            <td>25%</td>
                        </tr>
                        <tr align="middle">
                            <td height="20">500001-800000</td>
                            <td>15或以上</td>
                            <td>40%</td>
                            <td>30%</td>
                            <td>30%</td>
                        </tr>


                        <tr align="middle">
                            <td height="20">800001-2000000</td>
                            <td>20或以上</td>
                            <td>45%</td>
                            <td>35%</td>
                            <td>35%</td>
                        </tr>


                        <tr align="middle">
                            <td height="20">2000001以上</td>
                            <td>50或以上</td>
                            <td>50%</td>
                            <td>40%</td>
                            <td>40%</td>
                        </tr>
                        </tbody>
                    </table>
                    <br>B收入：比如您本月的代理账号内【没有赢利】的情况下，就可拥有以下收入:
                    <br>1.一个月内您的代理账号流水达到1-2000000元，可得流水佣金0.3%！
                    <br>2.一个月内您的代理账号流水达到2000001元-8000000元，可得流水佣金0.4%！
                    <br>3.一个月内您的代理账号流水达到8000001元-20000000元，可得流水佣金0.5%！
                    <br>4.一个月内您的代理账号流水达到20000001元-50000000元，可得流水佣金0.6%！
                    <br>5.一个月内您的代理账号流水达到50000001元以上，可得流水佣金1.0%！

                    <br><br>注：代理商每个月必须保持增长6位有效会员才可享有推广金.(有效投注3000以上算一位有效会员)。
                    <br>如：您本月代理无盈利，但是打码量为 180万，那么您的收入是：
                    <br>1800000*0.003=5400元佣金
                    <br>您的代理账号的赢利都由公司承担，您不用承担任何客户赢输钱的风险，佣金每月5日结算一次。结算完毕后，即可申请提款，您的所有提款将在3个工作日内到达您指定的收款银行帐号.以上计算方式仅供参考,具体参照详细规定。
                    <br>贴心服务：
                    <br>24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟皇冠国际合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！
                    <br>代理申请联系方式：
                    <font color="#ff0000"> QQ：<span class="agents_qq_number"> </span> （代理请务必添加）</font>
                </div>

            </div>
         <!-- 联盟方案结束 -->

            <!-- 联盟协议开始 -->
            <div class="agents_content material-card-content ProTab_con_2">

                <!-- 0086 开始 -->
                <div class="gyqimcont" style="display: block;">
                    <div class="linabtxs">
                        <h2> 0086联盟协议 </h2>
                    </div>

                    <h2> 一、皇冠国际对代理联盟的权利与义务</h2>
                    <p class="MsoNormal text_indent">1.皇冠国际的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守皇冠国际的会员条例，政策及操作程序。皇冠国际保留拒绝或冻结联盟/会员账户权利。</p>
                    <p class="MsoNormal text_indent">2.代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 皇冠国际的客服部会根据代理联盟旗下的会员计算所得的佣金。
                    <p class="MsoNormal text_indent">3.皇冠国际保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，皇冠国际会以电邮、网站公告等方法通知代理联盟。 代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。</p>
                    <h2> 二、代理联盟对皇冠国际的权力及义务</h2>
                    <p class="MsoNormal text_indent">1.代理联盟应尽其所能，广泛地宣传、销售及推广皇冠国际，使代理本身及皇冠国际的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广皇冠国际，并有责任义务告知旗下会员所有皇冠国际的相关优惠条件及产品。</p>
                    <p class="MsoNormal text_indent">2.代理联盟选择的皇冠国际推广手法若需付费，则代理应承担该费用。</p>
                    <p class="MsoNormal text_indent">3.任何皇冠国际相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，皇冠国际保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽皇冠国际。</p>
                    <h2> 三、规则条款</h2>
                    <p class="MsoNormal text_indent">1.各阶层代理联盟不可在未经皇冠国际许可情况下开设双/多个的代理账号，也不可从皇冠国际账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，皇冠国际有权终止并封存账号及所有在游戏中赚取的佣金。</p>
                    <p class="MsoNormal text_indent">2.为确保所有皇冠国际会员账号隐私与权益，皇冠国际不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害皇冠国际会员隐私行为，皇冠国际有权取消代理联盟红利，并取消代理联盟账号。</p>
                    <p class="MsoNormal text_indent">3.代理联盟旗下的会员不得开设多于一个的账户。皇冠国际有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，皇冠国际有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金</p>
                    <p class="MsoNormal text_indent">4.如代理联盟旗下的会员因为违反条例而被禁止享用皇冠国际的游戏，或皇冠国际退回存款给会员，皇冠国际将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，皇冠国际保留相关佣金直至审核完成。</p>
                    <p class="MsoNormal text_indent">5.合约内的条件会以皇冠国际通知接受代理联盟加入后开始执行。皇冠国际及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知皇冠国际。 代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，皇冠国际有权立即终止合约。</p>
                    <p class="MsoNormal text_indent">6.在没有皇冠国际许可下，代理联盟不能透露及授权皇冠国际相关密数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。</p>
                    <p class="MsoNormal text_indent">7.在合约终止后，代理联盟及皇冠国际将不须要履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。</p>
                </div>

            </div>
            <!-- 联盟协议结束 -->

        <!-- 代理注册开始 -->
        <div class="agents_content material-card-content ProTab_con_4">
            <div class="textbox-list">
                <ul>
                    <li>
                        <div>
                            <label>
                                <span class="account-icon"><em class="red_color">*</em> 帐号</span>
                            </label>
                            <span class="textbox">
                                <input type="text" name="username" id="username" minlength="5" maxlength="15" class="inp-txt" placeholder="会员帐号（5-15位数字或字母）">
                            </span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="pwd-icon"><em class="red_color">*</em> 密码</span>
                            </label>
                            <span class="textbox">
                                <input type="password" name="password" id="password"  minlength="6" maxlength="15" class="inp-txt" placeholder="密码（6-15个字符）">
                            </span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="pwd-icon2"><em class="red_color">*</em> 确认密码</span>
                            </label>
                            <span class="textbox">
                                  <input type="password" name="password2" id="password2" minlength="6" maxlength="15" class="inp-txt" placeholder="确认密码（6-15个字符）">
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="textbox-list user-info">
                <ul>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 真实姓名</span>
                            </label>
                            <span class="textbox">
                                <input type="text" name="alias" id="alias"  maxlength="10" class="inp-txt" placeholder="提款行卡的姓名，用于提款">
                            </span>
                        </div>
                    </li>
               <!--     <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 提款密码</span>
                            </label>
                            <span class="textbox">
                                <input type="password" name="paypassword" id="paypassword"  minlength="6" maxlength="6" class="inp-txt" placeholder="请输入6位纯数字">
                            </span>
                        </div>
                    </li>-->
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 手机号码</span>
                            </label>
                            <span class="textbox">
                                  <input type="text" name="phone" id="phone"  minlength="11" maxlength="11" class="inp-txt" placeholder="请输入11位手机号码">
                            </span>

                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 微信号码</span>
                            </label>
                            <span class="textbox">
                                <input id="wechat"  type="text" name="wechat" placeholder="微信号码">
                            </span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 出款银行</span>
                            </label>
                            <span class="textbox">
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
                            </span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 银行账号</span>
                            </label>
                            <span class="textbox">
                                <input id="bank_account"  type="text" name="bank_account" placeholder="银行账号">
                            </span>
                        </div>
                    </li>
                    <li>
                        <div>
                            <label>
                                <span class="text"><em class="red_color">*</em> 开户行地址</span>
                            </label>
                            <span class="textbox">
                                <input id="bank_address"  type="text" name="bank_address" placeholder="开户行地址">
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="agree-div">
                <span class="checkbox-item checked">
                    <span class="icon"></span>
                    <span class="text">同意本站《协议条款》</span>
                </span>
            </div>
            <!-- 错误提示 -->
            <div class="error-msg">
                <span class="text" id="error_msg"></span>
            </div>
            <div class="btn-wrap">
                <a href="javascript:reqSubmit();" class="zx_submit">立即注册</a>
            </div>
        </div>
       <!-- 代理注册开始 -->
    </div>
    </div>


</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

    var mem_flage = false ; // 防止重复提交
    // 此处设置为了兼容APP
    var tplfilename='<?php echo TPL_FILE_NAME;?>';
    var webConfig = '<?php echo str_replace("\\/", "/", json_encode(getSysConfig(), JSON_UNESCAPED_UNICODE));?>';　// 基础设置
    var configbase = $.parseJSON(webConfig);
    if(tplfilename=='wnsr'){
        configbase.agents_service_qq = configbase.vns_agents_service_qq;
        configbase.service_qq = configbase.vns_service_qq;
        configbase.service_meiqia = configbase.vns_service_meiqia;
        configbase.service_email = configbase.vns_service_email;
        configbase.service_phone_24 = configbase.vns_service_phone_24;
    }
    localStorage.setItem('webconfigbase',JSON.stringify(configbase));

    function reqSubmit() {
        var bankname = $("input[name='bank_name']").val();
        var bankaccount = removeAllSpace($("input[name='bank_account']").val());
        var bankaddress = removeAllSpace($("input[name='bank_address']").val());

        if(!VerifyData('agents')){ // 没有通过前端验证
            return false ;
        }
        if(bankaccount=='' || !isBankAccount(bankaccount)){
            setPublicPop('请输入正确的银行卡账号!');
            return false ;
        }
        if(bankaddress==''){
            setPublicPop('请输入开户行地址!');
            return false ;
        }
        if(mem_flage){
            return false ;
        }
        mem_flage = true ;

        var username = removeAllSpace($("input[name='username']").val());
        var password = removeAllSpace($("input[name='password']").val());
        var password2 = removeAllSpace($("input[name='password2']").val());
        var alias = removeAllSpace($("input[name='alias']").val());
       // var paypassword = removeAllSpace($("input[name='paypassword']").val());
       // var question  = main.question.value ;
       // var answer = $("input[name='answer']").val();
        var phone = removeAllSpace($("input[name='phone']").val());
        var wechat = removeAllSpace($("input[name='wechat']").val());


        var senddata = {
            username:username,
            password:password,
            password2:password2,
            alias:alias,
            bank_name:bankname,
            bank_address:bankaddress,
            bank_account:bankaccount,
           // paypassword:paypassword,
            phone:phone,
            wechat:wechat,

        }

        /**  ret.err
         *  -1 您输入的推荐代理 $agent 不存在
         *  -2 帐户已经有人使用，请重新注册！
         *  -3 插入新账户信息 数据库操作失败!!!
         *  -4 更新代理信息操作失败
         *  0  注册成功
         */
        $.ajax({
            url: '/reg_agent.php' ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success: function (ret) {
                    // setPublicPop(ret.describe);
                    // {"status":"200","describe":"用户登录成功","timestamp":"20180910000837",
                    // "data":{"UserName":"jack005","Agents":"dzfjazajzyj","LoginTime":"2018-09-10 00:08:37","birthday":"1989-09-10","Money":"0.0000","Phone":"13899989879","test_flag":"0","Oid":"e4e623ec55b59c82d723ra2","Alias":"发发发","BindCard_Flag":"0","BetMinMoney":"20","BetMaxMoney":"5000000"},"sign":""}
                if(ret.status=='200'){ // 注册成功
                    mem_flage = false ;
                    setPublicPop(ret.describe);
                    setTimeout(function () {
                        window.location.href = ret.data.agentUrl;
                    },2000);
                }else {
                    mem_flage = false ;
                    setPublicPop(ret.describe);
            }
            },
            error: function (msg) {
                mem_flage = false ;
                setPublicPop('注册账号异常');
            }
        });
    }

    setLoginHeaderAction('注册') ;
    addServerUrl() ;
    agreeMentAction() ;

    // 优惠tab
    function ProTab_Js(n){
        $(".ProTab_"+n).addClass('on').siblings().removeClass('on');
        $(".ProTab_con_"+n).fadeIn(300).siblings('.agents_content').hide();
    }

</script>

</body>
</html>