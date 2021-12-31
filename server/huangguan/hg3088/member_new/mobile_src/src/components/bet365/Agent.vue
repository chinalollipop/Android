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
            <li class="ProTab_5" @click="openNewGame(baseSettingData.agentLoginUrl,'','no')">代理登录</li>
        </ul>

        <div class="member_reg" >
            <!-- 联盟方案开始 -->
            <div v-show="showTab==1" class="agents_content material-card-content ProTab_con_1">
                <div class="gywm_mnbr_wenzi">
                    <h2>{{company_name}}集团代理合作方案</h2>


                    <p class="MsoNormal text_indent">{{company_name}}集团总部位于菲律宾，是获得英属维京群岛政府认证的合法互联网体育博彩公司，汇集了全球的博彩业界精英，皇冠体育致力打造符合体育博彩市场需求，并值得体育彩迷信赖的网上投注平台，务求能成为为每位体育彩迷量身订造，成为世界性博彩投注网站</p>
                    <p class="MsoNormal text_indent">{{company_name}}集团提供安全稳定的投注系统是本公司对每位体育彩迷的承诺，专业技术团队拥有高度的系统保障和支持能力，高效健全的支付平台更确保在线投注的公平、彩金支付机制的安全快速更是业界之冠，所有会员的彩金都能在</font>5分钟内到账，对会员承诺的履行永远都被我们视为成功的关键。无论您拥有的是网络资源，或是人脉资源，都欢迎您来加入皇冠国际合作伙伴的行列，无须负担任何费用，就可以开始无上限的收入。{{company_name}}集团，绝对是您最明智的选择!
                    </p>
                    <p class="MsoNormal"></p>
                    <h2>代理合作独立平台：</h2>

                    <p class="MsoNormal"> <span><strong>只要您介绍会员到{{company_name}}集团游戏，您就可以获得丰厚的盈利回报，集团有着强大的工作团队与您携手共创双赢，分红明细如下</strong></span></p>
                    <table id="table2" class="table-bordered" >
                        <tbody >
                        <tr><td> 当月净盈利   </td><td>当月最低有效会员  </td><td>当月可获佣金比例</td></tr>
                        <tr><td> 1~50000</td><td>   5或以上    </td><td> 25%</td></tr>
                        <tr><td> 50001~300000   </td><td>10或以上  </td><td>30%</td></tr>
                        <tr><td> 300001~800000  </td><td>50或以上  </td><td>35%</td></tr>
                        <tr><td> 800001~1000000</td><td>    80或以上</td><td>  40%</td></tr>
                        <tr><td> 2000001以上  </td><td>100或以上 </td><td>50%</td></tr>
                        </tbody>
                    </table>
                    <p class="MsoNormal"> 注：以上数据是显示您在集团推荐的玩家每月的净负盈利，也涉及到您的佣金比例（您的佣金比基于玩家的净负盈利）独立的代理专属链接，让您更好的推广</p>
                    <p class="MsoNormal"> <span><strong>代理须知</strong></span></p>
                    <p class="MsoNormal">1.佣金比例50%，您的会员净负盈利的50%将成为您的代理佣金收入</p>
                    <p class="MsoNormal">2.当月最少有5个活跃会员（累计存款500+ 有效投注1000+），您将开始获得佣金，增加有效会员输赢额和注册会员将使您获得更丰厚的返佣；</p>
                    <p class="MsoNormal">3.我们会时刻关注合作商的表现，会即时地调整佣金比例并通知合作商；</p>
                    <p class="MsoNormal">4.负盈利将被带入下一个月；</p>
                    <p class="MsoNormal">5.合作伙伴获得的支付费用，优惠和市场费用，这些费用将会累计并会在每月的代理佣金中扣除。</p>
                    <p class="MsoNormal"> <span><strong>这些费用包含：</strong></span></p>
                    <p class="MsoNormal">A.转账费用：包括所有代理会员的存款、取款费用</p>
                    <p class="MsoNormal">B.促销费用：任何费用支出为支持或协助代理与促销或营销目的费用</p>
                    <p class="MsoNormal">C.优惠红利：给予代理线下会员的现金红利或折扣</p>
                    <p class="MsoNormal">我们团队将在这里为您提供专业的咨询并真诚协助您使用合作计划工具，以便您最大限度的在行业领先的全部产品中获得更丰厚的佣金返利</p>
                    <p class="MsoNormal"> <span><strong>支付方式</strong></span></p>
                    <p class="MsoNormal">1.您的佣金将在次月初的15个工作日内计算完成，届时请联系QQ代理专员：{{baseSettingData.agents_service_qq}}在每个月的15-16号申请办理上个月的代理返利，我们免费提供支出交易，如果收款处要求加入额外的经费，我们将不负责支付；</p>
                    <p class="MsoNormal">2.最低支付金额高于RMB 500人民币将被直接支付，所有低于RMB 500人民币的支付将被累计到下一个月；</p>
                    <p class="MsoNormal">3.本公司有权在有需要时无通知的前提下改变或修正以及增加任何条款如上所述；</p>
                    <p class="MsoNormal">4.如果    客户在规定时间内不符合我们预期的代理表现，本公司有权随时取消合作伙伴账户，而不需要任何理由或提前通知；</p>
                    <p class="MsoNormal">5.相关代理规则与条款将在审核前发布予申请人。
                    <p class="MsoNormal"> <span><strong>集团优势</strong></span></p>
                    <p class="MsoNormal">1.我们提供顶级产品：体育投注、真人赌场、电子游艺、电竞下注、棋牌游戏、彩票游戏等多种游戏种类；</p>
                    <p class="MsoNormal">2.我们市场策略确保大量客户和高回报；</p>
                    <p class="MsoNormal">3.您可以获得更多的代理佣金，最高退佣比例高达50%；</p>
                    <p class="MsoNormal">4.我们提供的优质软件可以统计您的表现；</p>
                    <p class="MsoNormal">5.我们有受过良好训练的合作伙伴团队满足您任何需求；</p>
                    <p class="MsoNormal">{{company_name}}集团覆盖市场大，优势多，游戏多样化，我们将提供一条独立域名方便您推广。</p>

                </div>

            </div>
            <!-- 联盟方案结束 -->

            <!-- 联盟协议开始 -->
            <div v-show="showTab==2" class="agents_content material-card-content ProTab_con_2">

                <div class="gywm_mnbr_wenzi">
                    <h2>{{company_name}}集团联盟协议</h2>
                    <p class="MsoNormal"><strong>一、{{company_name}}集团对代理联盟的权利与义务</strong></p>
                    <p class="MsoNormal text_indent">{{company_name}}集团的客服部会登记联盟的会员并会观察他们的投注状况。联盟及会员必须同意并遵守{{company_name}}集团的会员条例，政策及操作程序。{{company_name}}集团保留拒绝或冻结联盟/会员账户权利
                        代理联盟可随时登入接口观察旗下会员的下注状况及会员在网站的活动概况。 {{company_name}}集团的客服部会根据代理联盟旗下的会员计算所得的佣金。</p>
                    <p class="MsoNormal text_indent">{{company_name}}集团保留可以修改合约书上的任何条例，包括: 现有的佣金范围、佣金计划、付款程序、及参考计划条例的权力，{{company_name}}集团会以电邮、网站公告等方法通知代理联盟。 代理联盟对于所做的修改有异议，代理联盟可选择终止合约，或洽客服人员反映意见。 如修改后代理联盟无任何异议，便视作默认合约修改，代理联盟必须遵守更改后的相关规定。</p>
                    <h2>二、代理联盟对{{company_name}}集团的权力及义务</h2>
                    <p class="MsoNormal text_indent">代理联盟应尽其所能，广泛地宣传、销售及推广{{company_name}}集团，使代理本身及{{company_name}}集团的利润最大化。代理联盟可在不违反法律下，以正面形象宣传、销售及推广{{company_name}}集团，并有责任义务告知旗下会员所有{{company_name}}集团的相关优惠条件及产品。
                        代理联盟选择的{{company_name}}集团推广手法若需付费，则代理应承担该费用。</p>
                    <p class="MsoNormal text_indent">任何{{company_name}}集团相关信息包括: 标志、报表、游戏画面、图样、文案等，代理联盟不得私自复制、公开、分发有关材料，{{company_name}}集团保留法律追诉权。 如代理在做业务推广有相关需要，请随时洽{{company_name}}集团。</p>
                    <h2>三、规则条款</h2>
                    <p class="MsoNormal text_indent text_indent">各阶层代理联盟不可在未经{{company_name}}集团许可情况下开设双/多个的代理账号，也不可从{{company_name}}集团账户或相关人士赚取佣金。请谨记任何阶层代理不能用代理帐户下注，{{company_name}}集团有权终止并封存账号及所有在游戏中赚取的佣金。
                        为确保所有{{company_name}}集团会员账号隐私与权益，{{company_name}}集团不会提供任何会员密码，或会员个人资料。各阶层代理联盟亦不得以任何方式取得会员数据，或任意登入下层会员账号，如发现代理联盟有侵害{{company_name}}集团会员隐私行为，{{company_name}}集团有权取消代理联盟红利，并取消代理联盟账号。</p>
                    <p class="MsoNormal text_indent">代理联盟旗下的会员不得开设多于一个的账户。{{company_name}}集团有权要求会员提供有效的身份证明以验证会员的身份，并保留以IP判定是否重复会员的权利。如违反上述事项，{{company_name}}集团有权终止玩家进行游戏并封存账号及所有于游戏中赚取的佣金
                        如代理联盟旗下的会员因为违反条例而被禁止享用{{company_name}}集团的游戏，或{{company_name}}集团退回存款给会员，{{company_name}}集团将不会分配相应的佣金给代理联盟。如代理联盟旗下会员存款用的信用卡、银行资料须经审核，{{company_name}}集团保留相关佣金直至审核完成。</p>
                    <p class="MsoNormal text_indent">合约内的条件会以{{company_name}}集团通知接受代理联盟加入后开始执行。{{company_name}}集团及代理联盟可随时终止此合约，在任何情况下，代理联盟如果想终止合约，都必须以书面/电邮方式提早于七日内通知{{company_name}}集团。 代理联盟的表现会3个月审核一次，如代理联盟已不是现有的合作成员则本合约书可以在任何时间终止。如合作伙伴违反合约条例，{{company_name}}集团有权立即终止合约。</p>
                    <p class="MsoNormal text_indent">在没有{{company_name}}集团许可下，代理联盟不能透露及授权{{company_name}}集团相关密数据，包括代理联盟所获得的回馈、佣金报表、计算等;代理联盟有义务在合约终止后仍执行机密文件及数据的保密。
                        在合约终止后，代理联盟及{{company_name}}集团将不须要履行双方的权利及义务。终止合约并不会解除代理联盟于终止合约前应履行的义务。</p>
                </div>

            </div>
            <!-- 联盟协议结束 -->

            <!-- 代理注册开始 -->
            <div v-show="showTab==4" class="agents_content material-card-content ProTab_con_4">
                <div class="login_center">
                    <div class="login_form">
                        <ul>
                            <li>
                                <label><span class="red_color">*</span>账号</label>
                                <input type="text" name="username" v-model="username" minlength="5" maxlength="15" class="za_text" placeholder="账号">
                            </li>
                            <li>
                                <label><span class="red_color">*</span>密码</label>
                                <input type="password" name="password" v-model="password"  minlength="6" maxlength="15" class="za_text" placeholder="密码">
                            </li>
                            <li>
                                <label><span class="red_color">*</span>确认密码</label>
                                <input type="password" name="password2" v-model="password2" minlength="6" maxlength="15" class="za_text" placeholder="确认密码">
                            </li>

                            <li>
                                <label><span class="red_color">*</span>真实姓名</label>
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
                                <label><span class="red_color">*</span>手机号码</label>
                                <input type="number" name="phone" v-model="phone"  minlength="11" maxlength="11" class="za_text" placeholder="手机号">
                            </li>
                            <li>
                                <label><span class="red_color">*</span>微信号码</label>
                                <input v-model="wechat"  type="text" name="wechat" class="za_text" placeholder="微信号码">
                            </li>
                            <li>
                                <span class="textbox" style="font-size: 1.15rem;">
                                <span class="red_color">*</span>出款银行
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
                                <label><span class="red_color">*</span>银行账号</label>
                                <input v-model="bank_account"  type="number" name="bank_account" class="za_text" placeholder="银行账号">

                            </li>
                            <li>
                                <label><span class="red_color">*</span>开户行地址</label>
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
    .agree-div{width: 90%;}
    .login_center{padding-bottom: 2rem;}
    .checkbox-item{color: #7a7a7a;}
    .login_form {background: #fff;border-radius: 5px;}
    .za_text,.member_reg select{color: #636363;width: 68%;padding-left: 5px;box-shadow: none;}
    .member_reg .zx_submit {border-radius: 5px;}
    .login_form li span{font-weight: normal;}
</style>
