<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
        <!--  标签 -->
        <ul class="ProTab_nav">
            <li class="ProTab_1" :class="showTab==1?'on':''" @click="ProTab_Js(1)">联盟方案</li>
            <li class="ProTab_2" :class="showTab==2?'on':''" @click="ProTab_Js(2)">联盟协议</li>
            <!-- <li class="ProTab_3" @click="ProTab_Js(3)">推广地址</li>-->
            <li class="ProTab_4" :class="showTab==4?'on':''" @click="ProTab_Js(4)">代理注册</li>

        </ul>

        <div class="member_reg" >
            <!-- 联盟方案开始 -->
            <div v-show="showTab==1" class="agents_content material-card-content ProTab_con_1">
                <div class="gywm_mnbr_wenzi">

                    <p class="MsoNormal text_indent">{{company_name}}在线持牌经营博彩投注多年。是目前世界最大的网络博彩集团之一！ 持有菲律宾政府卡格扬经济特区 First Cagayan leisure and Resort Corporation颁发的体育博彩与线上赌场执照。 拥有多元化的产品，使用最公平、公正、公开的系统，在市场上的众多博彩网站中，我们自豪的提供会员最优惠的回馈， 给予代理合作最优势的营利回报! 无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入{{company_name}}代理合作的行列， 无须负担任何费用，就可以开始无上限的收入。加入{{company_name}}，绝对是您最聪明的选择!</p>

                    <h2> 注册申请</h2>

                    <p class="MsoNormal text_indent"> 请点击【代理注册】在线提出申请，并确实填写各项资料。 {{company_name}}会评估审核联盟申请讯息，3日内由专员与您联系开通，并提供您的注册帐号、密码及推广链接。</p>
                    <table class="table table-bordered" border="1" cellspacing="0" cellpadding="0">

                        <tr><td rowspan="2">当月营利</td><td rowspan="2">当月最低有效会员	</td><td colspan="3">当月退佣比例</td></tr>
                        <tr><td>真人/电子/棋牌</td><td>	彩票	</td><td>体育/电竞</td></tr>
                        <tr><td>1~50000	</td><td>5或以上</td><td>	30%</td><td>	15%</td><td>	10%</td></tr>
                        <tr><td>50001~300000	</td><td>10或以上	</td><td>35%</td><td>	20%	</td><td>15%</td></tr>
                        <tr><td>300001~800000</td><td>	30或以上	</td><td>40%</td><td>	30%	</td><td>20%</td></tr>
                        <tr><td>800001~1200000	</td><td>50或以上	</td><td>45%</td><td>	35%	</td><td>25%</td></tr>
                        <tr><td>1200001以上	</td><td>100或以上</td><td>	50%	</td><td>40%	</td><td>30%</td></tr>

                    </table>

                    <h2>注：{{company_name}} 保留上述条例之最终更改权！</h2>

                    <p class="MsoNormal text_indent">请谨记任何使用不诚实方法以骗取佣金将会永久冻结账户，佣金一律不予发还！ 新合作运行商正式确立合作关系之后，须用心推广，前三个月需有每月3个或以上的有效会员增长，否则公司有权终止合作关系。</p>

                    <h2>回馈/佣金计算</h2>
                    <p > 1.* 请注意：</p>
                    <p class="MsoNormal text_indent"> 真人娱乐、体育博彩等项目，以报表中【派彩】字段，扣除相应费用后，依照上表门坎 	X 佣金百分比。</p>
                    <p class="MsoNormal text_indent"> 2.当月联盟体系以：真人、体育、彩票等项目的【派彩/投注量/总额公点金额】扣除相应费用后产生退佣总计，乘以相应退佣百分比後。</p>
                    <p class="MsoNormal text_indent"> 3.相应费用包括：会员各项优惠、存/取款相应手续费(请留意：{{company_name}} 会员重复出款￥手续费/未达100%投注出款的手续费由会员吸收，不纳入计算)。</p>
                    <p class="MsoNormal text_indent"> 4.【当月最低有效会员】定义为，在月结区间内进行过最少存款充值500RMB以上且有1000RMB有效下注的会员，如联盟体系当月未达【当月最低有效会员】最低门坎5人，则该月无法领取佣金回馈。联盟体系当月营利达到标准，而【当月最低有效会员】人数未达相应最低门坎，则该月佣金比例依照【当月最低有效会员】人数所达门坎相应的百分比进行退佣。</p>
                    <p >  5.例：</p>
                    <p class="MsoNormal text_indent">  体育当月营利为￥100001，而当月有效会员人数为5人，联盟虽达到营利为￥100001，却未达到有效会员10人以上，故依照联盟有效会员人数5人的门坎的退佣比例核算。</p>

                    <h2>佣金结算：</h2>
                    <p class="MsoNormal text_indent"> 您的代理下玩家所有盈利均由公司承担，您不用承担任何客户赢钱的风险，佣金每个月5号结算。结算完毕后，即可申请提款，您的所有提款将在24小时内到达您指定的收款银行帐号</p>

                    <h2>贴心服务：</h2>
                    <p class="MsoNormal text_indent"> 24小时值班客服服务。您可以直接联系网站在线客服或者合作伙伴负责人开出代理帐号。立即加盟{{company_name}}合作伙伴队伍，快速开创一番属于自己的事业！我们将以最低的门槛和最丰厚的回报助您成功！</p>

                </div>

            </div>
            <!-- 联盟方案结束 -->

            <!-- 联盟协议开始 -->
            <div v-show="showTab==2" class="agents_content material-card-content ProTab_con_2">

                <div class="gywm_mnbr_wenzi">
                    <h2>合作协议</h2>
                    <p class="MsoNormal text_indent">{{company_name}}与BBIN进行技术合作，为哥斯特黎加合法注册之博彩公司。我们采用最为多元、 先进、公正的系统，在众多博彩网站中，我们自豪能为会员提供最优惠的回馈、为代理商创造强劲的营利优势! {{company_name}}秉持商业联营、资源整合、利益共享的理念，与合作伙伴携手打造利多的荣景。 无论您拥有的是网络资源，或是丰富的人脉，都欢迎您来加入我们的行列，不须负担任何费用， 就可以开拓无上限的营收。{{company_name}}娱乐绝对是您最聪明的选择!</p>

                    <h2> 一、代理商注册规约</h2>

                    <p class="MsoNormal text_indent">为防堵不肖业者滥用{{company_name}}所提供的代理优惠制度，我们将严格审核每位代理商申请注册时所提供的个人资料(包括 姓名、IP、住址、电邮信箱、电话、支付方式等等)。若经审核发现代理商有任何不良营利企图，或与其他代理 商、会员进行合谋套利等行为，{{company_name}}娱乐公司将关闭该合作代理商之账户、扣除账户中的本金，并收回该代理商 的所有佣金与优惠。 同一IP/同一姓名/同一收款账号的会员只能是一个合作代理商的下线，代理商本身不能成为其他代理商的下线会员。</p>

                    <h2>二、权责条款</h2>

                    <p>（1）{{company_name}}对联盟伙伴的权利与义务</p>

                    <p class="MsoNormal text_indent">{{company_name}}的客服部门会登记合作代理商的下线会员并观察其投注状况。 代理商及会员皆须同意并遵守{{company_name}}的会员条例、政策及操作程序。 合作代理商可随时登入管理端接口观察其下线会员的下注状况与活动概况。 {{company_name}}保留所有对合作代理商或会员之账户加以拒绝或冻结的权利。 {{company_name}}有权修改合约书上之任何条例(包括:现有的佣金范围、佣金计划、付款程序、及参考计划条例等 等)，{{company_name}}公司会以电邮、网站公告等方法通知合作代理商。若代理商对于任何修改持有异议，可选择终止合 约、或洽客服人员提出意见。如代理商未提出异议，便视作默认合约修改，必须遵守更改后的相关规定。</p>

                    <p>（2）联盟伙伴对{{company_name}}的权力及义务</p>

                    <p class="MsoNormal text_indent">合作代理商应尽其所能，广泛地宣传、销售及推广{{company_name}}使代理商本身及 {{company_name}}的利润最大化。合作 代理商可在不违反法律的情况下，以正面形象宣传、销售及推广{{company_name}}， 并有责任义务告知旗下会员所有关于{{company_name}}的相关优惠条件及产品。 合作代理商选择推广{{company_name}}的手法若需付费，则代理商应自行承担该费用。 任何{{company_name}}的相关信息(包括：标志、报表、游戏画面、图样、文案等)，合作代理商不得私自复制、公开、 分发有关材料，{{company_name}}保留法律追诉权。 如代理商在业务推广方面需要相关的技术支持， 欢迎随时洽询{{company_name}}客服人员。</p>

                    <h2>三、各项细则</h2>

                    <p class="MsoNormal text_indent">各阶层合作代理商不可在未经{{company_name}}娱乐允许下开设双/多个代理账号， 也不可从{{company_name}}之游戏账户或其他相关人士赚取佣金。 请谨记任何代理商皆不能用代理帐户下注，{{company_name}} 有权终止并封存账号及其所有在游戏中赚取的佣金。</p>

                    <p class="MsoNormal text_indent">为确保所有{{company_name}}会员的账号隐私与权益， {{company_name}}不会提供任何会员密码，或会员个人资料。 各阶层合作代理商亦不得以任何方式取得会员数据，或任意登入下层会员账号， 如发现代理商有侵害{{company_name}}会员隐私的行为， {{company_name}}有权取消代理商之红利，并取消该名代理商之账号。</p>

                    <p class="MsoNormal text_indent">合作代理商旗下的会员不得开设多于一个的账户。{{company_name}}有权要求会员提供有效的身份证明以验证会员的身份， 并保留以IP判定会员是否重复注册的权利。如违反上述事项， {{company_name}}有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金。</p>

                    <p class="MsoNormal text_indent">如合作代理商旗下的会员因违反条例而被禁止使用{{company_name}}的游戏， 或{{company_name}}退回存款给会员， {{company_name}}将不会分配相应的佣金给代理商。 如合作代理商旗下会员存款用的信用卡、银行资料须经审核，{{company_name}}将保留相关佣金直至审核完毕。</p>

                    <p class="MsoNormal text_indent">合约条件将于{{company_name}}正式接受合作代理商加入后开始生效。 {{company_name}}娱乐公司及代理商可随时终止此合约。 在任何情况下，代理商若欲终止合约，都必须以书面/电邮方式提早于七日内通知{{company_name}}。 代理商的表现将会每3个月审核一次，如代理商已不是现有的合作成员，则本合约书可以在任何时间终止。 如代理商违反合约条例，{{company_name}}有权立即终止合约。</p>

                    <p class="MsoNormal text_indent">在没有{{company_name}}的许可下， 代理商不能透露及授权{{company_name}}的相关机密资料， 包括代理商所获得的回馈、佣金报表、计算方式等；代理商有义务在合约终止后仍执行机密文件及数据的保密。 合约终止之后，代理商及{{company_name}}将不须履行双方的权利及义务。 终止合约并不会解除代理商于终止合约前所应履行的义务。</p>


                </div>

            </div>
            <!-- 联盟协议结束 -->

            <!-- 代理注册开始 -->
            <div v-show="showTab==4" class="agents_content material-card-content ProTab_con_4">
                <div class="login_center">
                    <div class="login_form">
                        <ul>
                            <li>
                                <span class="logaccount-icon"></span>
                                <input type="text" name="username" v-model="username" minlength="5" maxlength="15" class="za_text" placeholder="账号">
                            </li>
                            <li>
                                <span class="logpwd-icon"></span>
                                <input type="password" name="password" v-model="password"  minlength="6" maxlength="15" class="za_text" placeholder="密码">
                            </li>
                            <li>
                                <span class="logpwd-icon"></span>
                                <input type="password" name="password2" v-model="password2" minlength="6" maxlength="15" class="za_text" placeholder="确认密码">
                            </li>

                            <li>
                                <span class="name-icon"></span>
                                <input type="text" name="alias" v-model="alias"  maxlength="10" class="za_text" placeholder="真实姓名">

                            </li>
                            <!--     <li>
                                     <div>
                                         <label>
                                             <span class="text"><em class="red_color">*</em> 提款密码</span>
                                         </label>
                                         <span class="textbox">
                                             <input type="password" name="paypassword" v-model="paypassword"  minlength="6" maxlength="6" class="za_text" placeholder="请输入6位纯数字">
                                         </span>
                                     </div>
                                 </li>-->
                            <li>
                                <span class="phone-icon"></span>
                                <input type="number" name="phone" v-model="phone"  minlength="11" maxlength="11" class="za_text" placeholder="手机号">
                            </li>
                            <li>
                                <span class="wechat-icon"></span>
                                <input v-model="wechat"  type="text" name="wechat" class="za_text" placeholder="微信号码">
                            </li>
                            <li>
                                <span class="bank-ck-icon"></span>
                                <span class="textbox" style="font-size: 1.15rem;color: #f1f1f1;margin-left: 5px;">
                                    选择银行
                                    <select name="bank_name" v-model="bank_name">
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
                                <span class="bank-account-icon"></span>
                                <input v-model="bank_account"  type="number" name="bank_account" class="za_text" placeholder="银行账号">

                            </li>
                            <li>
                                <span class="bank-address-icon"></span>
                                <input v-model="bank_address"  type="text" name="bank_address" class="za_text" placeholder="开户行地址">
                            </li>
                        </ul>
                    </div>

                    <div class="agree-div">
                         <span class="checkbox-item" :class="checkStatus?'checked':''" @click="checkAction">
                            <span class="icon"></span>
                            <span class="text">同意本站<span class="agreeText">《协议条款》</span></span>
                         </span>
                    </div>
                    <div class="btn-wrap">
                        <a @click="reqSubmit" class="zx_submit">立即注册</a>
                    </div>
                </div>

            </div>
            <!-- 代理注册开始 -->
        </div>
    </div>

  </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'Index',
        mixins:[Mixin],
        components: {
            HeaderNav,
            Dialog
        },
        data () {
            return {
                showTab:1,
                introducer:'',
                username :'',
                password :'',
                password2 :'',
                phone :'',
                wechat :'',
                qq :'',
                alias :'',
                bank_name :'工商银行',
                bank_address :'',
                bank_account :'',
                apptip:''
            }
        },
        mounted: function () {
            let _self = this ;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
        },
        methods:{
            /* 标签 */
            ProTab_Js: function (tab) {
                this.showTab = tab;
            },
          /* 注册行为 */
            reqSubmit: function () {
                let _self =this;
                if(_self.submitflag){
                    return false ;
                }
                if(!_self.checkStatus){
                    _self.$refs.autoDialog.setPublicPop('请勾选同意本站协议') ;
                    return false ;
                }
                if(_self.username ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入帐号') ;
                    return false ;
                }
                if(_self.password ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入密码') ;
                    return false ;
                }
                if(_self.password !=_self.password2){
                    _self.$refs.autoDialog.setPublicPop('密码与确认密码不一致') ;
                    return false ;
                }
                if(_self.alias ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入真实姓名') ;
                    return false ;
                }
                if(_self.phone ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入手机号码') ;
                    return false ;
                }
                if(_self.wechat ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入微信号码') ;
                    return false ;
                }
                if(_self.bank_account ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入正确的银行卡账号') ;
                    return false ;
                }
                if(_self.bank_address ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入开户行地址') ;
                    return false ;
                }

                let senddata = {
                    introducer: _self.introducer,
                    keys: 'add',
                    username: _self.username,
                    password: _self.password,
                    password2: _self.password2,
                    alias:_self.alias,
                    bank_name:_self.bank_name,
                    bank_address:_self.bank_address,
                    bank_account:_self.bank_account,
                    // paypassword:_self.paypassword,
                    phone: _self.phone,
                    wechat: _self.wechat,
                    //qq: _self.qq,
                    // birthday:_self.birthday,
                    // country:_self.country,
                    verifycode: Math.random()
                };

                _self.submitflag = true;
                _self.axios({
                    method: 'post',
                    params: senddata,
                    url: _self.ajaxUrl.agentreg
                }).then(res=>{
                    if(res){
                        _self.submitflag = false;
                        let rest = res.data;
                        if (rest.describe) {
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                            setTimeout(()=> { // 跳转到代理注册页面
                                window.location.href = rest.data.agentUrl;
                            },2000);
                        }
                    }
                }).catch(res=>{
                    _self.submitflag = false;
                    console.log('代理注册失败');
                });

            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .login_center{padding-bottom: 2rem;}
    .table-bordered tr:first-child {background: none;color: #333;}
</style>
