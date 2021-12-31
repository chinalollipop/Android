<?php
session_start();
include_once('../../include/config.inc.php');
$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'lmfa' ; // 用于app 跳转到这个页面 ?tip=app

$display = 'style="display: block;"';
$companyName = COMPANY_NAME;

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
    <title class="web-title"></title>
<style type="text/css">
    .content-center-agents .ProTab_nav{display: -webkit-flex;display: flex;width: 100%;}
    .content-center-agents .ProTab_nav li{width: auto;-webkit-flex: 1;flex: 1;}
    .content-center-agents .ProTab_nav li.on{color: #fff;}
    .content-center-agents .ProTab_nav li.on:before{display: none;}
    .content-center-agents .login_form li {display:-webkit-flex;display: flex;color:#000;width: 100%;padding: 0;background: #fff;border-radius: 5px;height: 3.5rem;line-height: 3.5rem;box-shadow: 0 3px 4px rgba(0,0,0,0.2);}
    .content-center-agents .login_form li:first-child{margin-top: 0;}
    .content-center-agents .login_form label{-webkit-flex: 2;flex: 2;padding-left: 4%;}
    .content-center-agents .za_text,.content-center-agents .login_form li .textbox{color:#acacac;-webkit-flex: 5;flex: 5;text-align: right;padding-right: 4%;}
    .content-center-agents .member_reg select{width:100%;height: 100%;padding:0;border: 0;color:#acacac;direction: rtl;}
    .agree-div{width: 90%;}
    .login_center{padding-bottom: 2rem;}
    .member_reg .zx_submit {border-radius: 5px;}
    .member_reg>div{padding: 2%;}
    .member_reg>div:last-child{padding: 1%;}
    input::placeholder{color:#acacac;}
    input::-webkit-input-placeholder{color:#acacac;}
    input:-moz-input-placeholder{color:#acacac;}
    input:-ms-input-placeholder{color:#acacac;}
    .material-card-content h3{color: #5ea0ea;margin: .4rem 0;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">

    </div>

    <!-- 中间注册表单内容 -->
    <div class="content-center content-center-agents">
        <!--  标签 -->
        <ul class="ProTab_nav ">
            <li class="ProTab_1 <?php echo ($type=='lmfa'?'on':'');?>" onclick="ProTab_Js(1)">联盟方案</li>
            <li class="ProTab_2 <?php echo ($type=='lmxy'?'on':'');?>" onclick="ProTab_Js(2)">联盟协议</li>
            <!-- <li class="ProTab_3" onclick="ProTab_Js(3)">推广地址</li>-->
            <li class="ProTab_4 <?php echo ($type=='dlzc'?'on':'');?>" onclick="ProTab_Js(4)">代理注册</li>
            <li class="ProTab_5" onclick="window.open('<?php echo returnAgentUrl();?>')">代理登录</li>
        </ul>

        <div class="member_reg" >

         <!-- 联盟方案开始 -->
            <div class="bg_yy agents_content material-card-content ProTab_con_1" <?php echo ($type=='lmfa'?$display:'');?> >
                <!-- 8M 开始-->
                <div class="gywm_mnbr_wenzi">
                    <h2><?php echo $companyName;?>代理合作方案</h2>

                    <p class="MsoNormal text_indent">
                        <?php echo $companyName;?>拥有欧洲马耳他博彩管理局（MGA）、英国GC监督委员会（Gambling Commission）和菲律宾政府博彩委员会（PAGCOR）颁发的合法执照。注册于英属维尔京群岛，是受国际博彩协会认可的合法博彩公司。
                    </p>
                    <p class="MsoNormal text_indent">
                        一直以来我们秉承用户至上的原则，务实经营的理念，聚集优秀的高素质人才资源与业界高质量精英团队合作，致力于打造体育精品，竭诚为每一位用户奉献全方位的极致体验和高端的游戏享受。<?php echo $companyName;?>与业界知名的博彩产品供应商如AsiaGaming, BBin,Microgaming等拥有良好的深度合作关系，提供最优质的体育赛事，真人娱乐场以及几百款小游戏，并不断的开发高质量的娱乐新品。
                    </p>
                    <p class="MsoNormal text_indent">
                        全年365天，<?php echo $companyName;?>每一天与您同在，为您提供7x24小时全天候咨询服务。同时我们拥有经验丰富，水平一流的科技团队，为网站的安全稳定保驾护航，为产品与服务的创新提供源源不断的动力。
                    </p>

                    <h3>代理合作独立平台：</h3>

                    <p>a.具有便利的计算机上网设备。</p>
                    <p>b.有一定的人脉资源、网络资源或自己的广告网站。</p>
                    <h3>代理独立平台：</h3>
                    <p class="MsoNormal text_indent">
                        我们为您提供单独的代理后台，您可以在后台不受限制的开出下线，并且实时了解下线会员输赢，投注，存款，取款情况。代理后台有一个您的专属链接，您可以直接将您的专属链接链接在网站、论坛、博客等等可链接的网络页面，也可在群里面发送您的专属链接，只要通过您的专属链接注册的会员都算是您的下线。推广方式简单方便，推广渠道多种多样</p>
                    <p class="red">【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】</p>
                    <p class="red">QQ：<span class="agents_qq_number"> </span> （代理请务必添加）</p>
                    <p>【代理输赢不累计，当月清零，更有流水佣金，让您月收入上百万】</p>
                    <p>如果您成为"<?php echo $companyName;?>博彩"的代理，您就可拥有以下收入：</p>
                    <p>结算方法：</p>
                    <p>A收入：比如您本月的代理账号内【有赢利】的情况下，就可拥有以下收入: </p>
                    <table class="table-bordered" border="1" cellspacing="0" cellpadding="0">
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
                    <p class="MsoNormal text_indent">
                        您的代理账号的赢利都由公司承担，您不用承担任何客户赢输钱的风险，佣金每月5日结算一次。结算完毕后，即可申请提款，您的所有提款将在3个工作日内到达您指定的收款银行帐号.以上计算方式仅供参考,具体参照详细规定。</p>
                    <h3>贴心服务：</h3>
                    <p class="MsoNormal text_indent">24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟<?php echo $companyName;?>合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！ </p>
                    <h3>代理申请联系方式： </h3>
                    <p>QQ：<span class="agents_qq_number"> </span> （代理请务必添加）</p>

                </div>

            </div>
         <!-- 联盟方案结束 -->

            <!-- 联盟协议开始 -->
            <div class="bg_yy agents_content material-card-content ProTab_con_2" <?php echo ($type=='lmxy'?$display:'');?> >
                <!-- 8M 开始 -->
                <div class="gywm_mnbr_wenzi">
                    <h2><?php echo $companyName;?>联盟协议</h2>

                    <h3>一、<?php echo $companyName;?>对代理联盟的权利与义务</h3>
                    <p class="MsoNormal text_indent">1.<?php echo $companyName;?>的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守<?php echo $companyName;?>的会员条例，政策及操作程序。<?php echo $companyName;?>保留拒绝或冻结联盟/会员账户权利。</p>
                    <p class="MsoNormal text_indent">2.代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 <?php echo $companyName;?>的客服部会根据代理联盟旗下的会员计算所得的佣金。</p>
                    <p class="MsoNormal text_indent">3.<?php echo $companyName;?>保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，<?php echo $companyName;?>会以电邮、网站公告等方法通知代理联盟。 代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。</p>
                    <h3>二、代理联盟对<?php echo $companyName;?>的权力及义务</h3>
                    <p class="MsoNormal text_indent">1.代理联盟应尽其所能，广泛地宣传、销售及推广<?php echo $companyName;?>，使代理本身及<?php echo $companyName;?>的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广<?php echo $companyName;?>，并有责任义务告知旗下会员所有<?php echo $companyName;?>的相关优惠条件及产品。</p>
                    <p class="MsoNormal text_indent">2.代理联盟选择的<?php echo $companyName;?>推广手法若需付费，则代理应承担该费用。</p>
                    <p class="MsoNormal text_indent"><?php echo $companyName;?>相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，<?php echo $companyName;?>保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽<?php echo $companyName;?>。3.任何
                    <h3>三、规则条款</h3>
                    <p class="MsoNormal text_indent">1.各阶层代理联盟不可在未经<?php echo $companyName;?>许可情况下开设双/多个的代理账号，也不可从<?php echo $companyName;?>账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，<?php echo $companyName;?>有权终止并封存账号及所有在游戏中赚取的佣金。</p>
                    <p class="MsoNormal text_indent">2.为确保所有<?php echo $companyName;?>会员账号隐私与权益，<?php echo $companyName;?>不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害<?php echo $companyName;?>会员隐私行为，<?php echo $companyName;?>有权取消代理联盟红利，并取消代理联盟账号。</p>
                    <p class="MsoNormal text_indent">3.代理联盟旗下的会员不得开设多于一个的账户。<?php echo $companyName;?>有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，<?php echo $companyName;?>有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金 。</p>
                    <p class="MsoNormal text_indent">4.如代理联盟旗下的会员因为违反条例而被禁止享用<?php echo $companyName;?>的游戏，或<?php echo $companyName;?>退回存款给会员，<?php echo $companyName;?>将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，<?php echo $companyName;?>保留相关佣金直至审核完成。</p>
                    <p class="MsoNormal text_indent">5.合约内的条件会以<?php echo $companyName;?>通知接受代理联盟加入后开始执行。<?php echo $companyName;?>及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知<?php echo $companyName;?>。 代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，<?php echo $companyName;?>有权立即终止合约。</p>
                    <p class="MsoNormal text_indent">6.在没有<?php echo $companyName;?>许可下，代理联盟不能透露及授权<?php echo $companyName;?>相关密数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。</p>
                    <p class="MsoNormal text_indent">7.在合约终止后，代理联盟及<?php echo $companyName;?>将不须要履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。</p>

                </div>


            </div>
            <!-- 联盟协议结束 -->

        <!-- 代理注册开始 -->
        <div class="agents_content material-card-content ProTab_con_4" <?php echo ($type=='dlzc'?$display:'');?> >
            <div class="login_center">
                <div class="login_form">
                    <ul>
                        <li>
                            <!--<span class="logaccount-icon"></span>-->
                            <label><span class="red_color">*</span>账号</label>
                            <input type="text" name="username" id="username" minlength="5" maxlength="15" class="za_text" placeholder="账号">
                        </li>
                        <li>
                            <!--<span class="logpwd-icon"></span>-->
                            <label><span class="red_color">*</span>密码</label>
                            <input type="password" name="password" id="password"  minlength="6" maxlength="15" class="za_text" placeholder="密码">
                        </li>
                        <li>
                            <!--<span class="logpwd-icon"></span>-->
                            <label><span class="red_color">*</span>确认密码</label>
                            <input type="password" name="password2" id="password2" minlength="6" maxlength="15" class="za_text" placeholder="确认密码">
                        </li>

                        <li>
                            <!--<span class="name-icon"></span>-->
                            <label><span class="red_color">*</span>真实姓名</label>
                            <input type="text" name="alias" id="alias"  maxlength="10" class="za_text" placeholder="真实姓名">

                        </li>
                        <!--     <li>
                                 <div>
                                     <label>
                                         <span class="text"><em class="red_color">*</em> 提款密码</span>
                                     </label>
                                     <span class="textbox">
                                         <input type="password" name="paypassword" id="paypassword"  minlength="6" maxlength="6" class="za_text" placeholder="请输入6位纯数字">
                                     </span>
                                 </div>
                             </li>-->
                        <li>
                            <!--<span class="phone-icon"></span>-->
                            <label><span class="red_color">*</span>手机号码</label>
                            <input type="text" name="phone" id="phone"  minlength="11" maxlength="11" class="za_text" placeholder="手机号">
                        </li>
                        <li>
                            <!--<span class="wechat-icon"></span>-->
                            <label><span class="red_color">*</span>微信号码</label>
                            <input id="wechat"  type="text" name="wechat" class="za_text" placeholder="微信号码">
                        </li>
                        <li>
                            <!--<span class="bank-ck-icon"></span>-->
                            <label><span class="red_color">*</span>出款银行</label>
                                <span class="textbox" >
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

                        </li>
                        <li>
                            <!--<span class="bank-account-icon"></span>-->
                            <label><span class="red_color">*</span>银行账号</label>
                            <input id="bank_account"  type="number" name="bank_account" class="za_text" placeholder="银行账号">

                        </li>
                        <li>
                            <!--<span class="bank-address-icon"></span>-->
                            <label><span class="red_color">*</span>开户行地址</label>
                            <input id="bank_address"  type="text" name="bank_address" class="za_text" placeholder="开户行地址">
                        </li>
                    </ul>
                </div>

                <div class="agree-div">
                    <span class="checkbox-item checked">
                        <span class="icon"></span>
                        <span class="text">同意本站<span class="agreeText">《协议条款》</span></span>
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
        </div>
       <!-- 代理注册结束 -->
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