<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit-two">
        <!-- 公司入款 -->
        <template v-if="show_type=='company'">
          <div v-show="set_1" class="payWay bank_deposit_1" style="padding-bottom: 20px;">
            <div class="payWayTit">选择银行</div>
            <div class="bank_list">
              <a v-for="(list,index) in dataList" :key="index" href="javascript:;" class="bank_li" :data-bank="list.bank_name+'-'+list.bank_user" :data-id="list.id" @click="chooseBnakType($event)">
                <div class="top_li">
                <span class="icon" :style="{backgroundImage: 'url(/static/images/bank/icon_'+list.bankcode+'.png)'}" > </span>
                <span> {{list.bank_name}}<br><span class="red_color">{{list.bank_context}}</span><template v-if="list.bank_context !=''"> <br> </template> {{list.bank_user}} </span>
                </div>
                <p class="red_color tip_note">{{list.notice}}</p>
              </a>
            </div>

          </div>

          <div v-show="set_2" class="payWay bank_deposit_2">
            <div class="payWayTit">
              汇款详细账户资料
            </div>
            <div class="bank_deposit_bottom">

              <div class="banks_details" id="show_bank_list">
                  <div v-for="(list,index) in dataList" class="bank_list_li" :class="'bank_list_li_'+list.id">
                      <div>
                          <span> 银行 </span>
                          <span class="bank_name">{{list.bank_name}} <span class="red_color">{{list.bank_context}}</span></span>
                      </div>
                      <div> <span> 开户名 </span> <span :class="'bank_username_'+list.id">{{list.bank_user}} </span>
                          <a href="javascript:;" :data-clipboard-target="'.bank_username_'+list.id"><span class="icon"></span> 复制</a>
                      </div>
                      <div> <span> 银行账号 </span> <span :class="'bank_account_'+list.id">{{list.bank_account}}</span>
                          <a href="javascript:;" :data-clipboard-target="'.bank_account_'+list.id"><span class="icon"></span> 复制</a>
                      </div>
                      <div> <span> 银行分行 </span> <span class="bank_address red_color">{{list.bank_addres}}</span> </div>
                  </div>
              </div>
              <div class="warn" style="padding:15px 5px">
                <h2>温馨提示： </h2>
                <p>一、请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。 <br>
                  二、每次存款赠送最高2%红利。<!--本公司最低存款金额为100元，-->
                </p>
              </div>
              <a href="javascript:;" class="deposit_bank_next zx_submit" @click="chooseBnakSec($event)"> 填写汇款信息表格 </a>
              <div class="tip">此存款信息只是您汇款详情的提交，并非代表存款，您需要自己通过ATM或网银转帐到本公司提供的账户后，填写提交此信息，待工作人员审核充值！</div>
            </div>
          </div>

          <!-- 公司入款开始 -->
          <div v-show="set_3" class="payWay bank_deposit_3" data-area="bank_pay">
            <div class="payWayTit">
              汇款信息提交
            </div>
            <div class="form-item">
                            <span class="label clearfix">
                                <span class="text">汇款金额</span>
                                <span class="line"></span>
                            </span>
              <span class="textbox">
                                <input type="number" step="0.01" v-model="v_amount" class="deposit-input money-textbox" placeholder="请输入汇款金额" />
                                <!--<a class="textbox-close" href="javascript:;">╳</a>-->
                            </span>
            </div>

            <table class="money moneychoose" >

              <tbody>
              <tr v-for="(list,index) in chMoneyData" :key="index" >
                <td @click="chooseMoney(list.val_1)" :class="v_amount==list.val_1 && 'active'"><span>{{list.val_1}}</span></td>
                <td @click="chooseMoney(list.val_2)" :class="v_amount==list.val_2 && 'active'"><span>{{list.val_2}}</span></td>
                <td @click="chooseMoney(list.val_3)" :class="v_amount==list.val_3 && 'active'"><span>{{list.val_3}}</span></td>
                <td @click="chooseMoney(list.val_4)" :class="v_amount==list.val_4 && 'active'"><span>{{list.val_4}}</span></td>
              </tr>
              </tbody>
            </table>
            <div class="form-item form-select">
                            <span class="label">
                                <span class="text">存款人姓名</span>
                                <span class="line"></span>
                            </span>
              <span class="textbox">
                                <input type="text" v-model="v_Name" class="deposit-input" placeholder="请输入存款人姓名" />
                            <!--<a class="textbox-close" href="javascript:;">╳</a>-->
                        </span>
            </div>

            <div class="form-item form-select">
                        <span class="label">
                            <span class="text">汇款方式</span>
                            <span class="line"></span>
                        </span>
              <span class="dropdown">
                                <select name="InType" id="InType" v-model="in_type">
                                    <option value="">请选择汇款方式</option>
                                    <option value="银行柜台">银行柜台</option>
                                    <option value="ATM现金">ATM现金</option>
                                    <option value="ATM卡转">ATM卡转</option>
                                    <option value="网银转账">网银转账</option>
                                    <option value="其他">其他</option>
                                </select>
                            </span>
            </div>

            <div class="form-item">
                <span class="label">
                    <span class="text">汇款时间</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="time_textbox deposit-input" v-model="save_time" placeholder="选择日期" type="text" readonly />
                </span>
            </div>

            <div id="other" >
              <div class="form-item">
                                <span class="label">
                                    <span class="text">备注</span>
                                    <span class="line"></span>
                                </span>
                <span class="textbox">
                                    <input type="text" placeholder="可填入银行转账单号等信息" v-model="remark" maxlength="50">
                                </span>
              </div>

            </div>
            <div class="btn-wrap">
              <a href="javascript:;" class="zx_submit" @click="depositeBankAction">确认存款</a>
            </div>

          </div>
        </template>

        <!-- 支付宝扫码 -->
        <template v-else-if="show_type=='smzf'">
            <div class="content_right">
                <dl class="saoma_source" >
                    <dt>手机扫一扫，轻松支付</dt>
                    <dd>
                        <img :src="dataList.photo_name" alt="加载中..."/><br>
                    </dd>
                </dl>
                <div class="saoma_notes">请不要使用整数进行存款否则无法成功，<br>请使用例如：101或者123等！</div>
            </div>
            <div class="form-item">
                <span class="label clearfix" >
                    <span class="text">支付宝姓名</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input " type="text" v-model="v_Name" readonly >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text"></span>
            </div>
            <div class="form-item">
                <span class="label clearfix" >
                    <span class="text">存入金额</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input money-textbox" type="number" step="0.01" v-model="v_amount" placeholder="请输入汇款金额" >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="form-item">
                <span class="label clearfix" >
                    <span class="text">{{dataList.notice}}</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="deposit-input "v-model="remark" type="text" :placeholder="'请输入'+dataList.notice" >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="form-item">
                <span class="label clearfix" >
                    <span class="text">汇款日期</span>
                    <span class="line"></span>
                </span>
                <span class="textbox">
                    <input class="time_textbox deposit-input" v-model="save_time" type="text" placeholder="选择日期" readonly >
                </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="btn-wrap">
                <a href="javascript:;" class="zx_submit" @click="depositeBankAction">确认存款</a>
            </div>
            <p class="sm_tip">支付宝转帐时，请使用您本人支付宝帐号；转帐金额与您申请时填写的金额保持一致，会加快到帐速度。
                支付遇到困难？请联系我们的线上客服获得帮助。
            </p>

        </template>

      <!-- USDT 支付 -->
      <template v-else-if="show_type=='usdt'">
        <div class="payWay">
          <div class="payWayTit">USDT支付</div>
          <div class="form-item">
            <span class="label clearfix" >
                <span class="text">充值金额</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input class="deposit-input money-textbox" type="number" @keyup="countUsdtMount" step="0.01" v-model="v_amount" placeholder="请输入汇款金额" >
            </span>
          </div>
          <div class="form-item">
            <span class="label clearfix" >
                <span class="text">支付通道</span>
                <span class="line"></span>
            </span>
            <span class="textbox" style="line-height: 3rem">
                <img style="width: 2rem; height: 2rem; display: inline;vertical-align: middle" src="/static/images/deposit/usdt.png" alt=""> &nbsp;USDT-极速
            </span>
          </div>

          <p class="sm_tip">
            请注意：请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。<br>
            本公司最低存款金额为<span class="red_color">{{dataList.min_deposit}}</span>元，每次存款赠送最高<span class="red_color">{{dataList.yuhui_rate}}</span>红利。
          </p>

        </div>
        <div class="payWay">
          <div class="tip_title ">TRC20</div>
          <div class="content_right">
            <dl class="saoma_source" >
              <dt>
                请转入 <span class="red_color pay_to_usdt">{{usdt_mon}}</span> USDT
                <a class="copy_icon copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt" title="点击复制"></a>
              </dt>
              <dd>
                <img :src="dataList.photo_name" alt="加载中..."/><br>
              </dd>
            </dl>
            <div class="cz_url">
              <p>充值地址</p>
              <span class="cz_title" style="float: none;"><span class="pay_to_usdt_url">{{dataList.deposit_address}}</span> </span><br>
              <a class="copy_btn" href="javascript:;" data-clipboard-target=".pay_to_usdt_url" title="点击复制"><span class="copy_icon"></span> 复制地址 </a>
            </div>
            <div class="sm_tip">
              <p class="tip_top">支付完成请等待<span class="red_color">5-10</span>分钟到账，支付失败请<a :href="baseSettingData.service_meiqia" style="margin-left: 5px;" target="_blank" class="to_livechat"><span class="red_color">咨询客服</span><span class="icon_chat"></span></a> </p>
              <h3>*注意</h3>
              1.请勿向上述地址支付任何非TRC20 USDT 资产，否则资产将无法找回。<br>
              2.当前火币/币安交易所 USDT 最新场外卖出单价 <span class="red_color"> {{usdt_rate}} </span> 元。<br>
              3.请确保收款地址收到 <span class="red_color"> {{usdt_mon}} </span> USDT  <span class="red_color" >【不含转账手续费】</span> ，否则无法到账。<br>
              4.您支付至上述地址后，需要整个网络节点的确认，请耐心等待。
            </div>
          </div>
        </div>
        <div class="btn-wrap">
          <a href="javascript:;" class="zx_submit" @click="depositeBankAction">确认存款</a>
          <a href="/static/usdtjc/usdtjc.html" class="zx_submit" target="_blank" style="margin-top: 10px;background: #ccc;">USDT充值教程</a>
        </div>
      </template>

        <!-- 三方支付 -->
        <template v-else>
            <div class="form-item">
                    <span class="label clearfix" >
                        <span class="text">充值金额</span>
                        <span class="line"></span>
                    </span>
                <span class="textbox">
                        <input class="deposit-input money-textbox" name="order_amount"  v-model="v_amount" type="number" step="0.01" placeholder="请输入汇款金额" />
                        <!--<a class="textbox-close" href="javascript:;">╳</a>-->
                    </span>
            </div>
            <div class="tip error hide">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <table class="money moneychoose" >

                <tbody>
                <tr v-for="(list,index) in chMoneyData" :key="index" >
                    <td @click="chooseMoney(list.val_1)" :class="v_amount==list.val_1 && 'active'"><span>{{list.val_1}}</span></td>
                    <td @click="chooseMoney(list.val_2)" :class="v_amount==list.val_2 && 'active'"><span>{{list.val_2}}</span></td>
                    <td @click="chooseMoney(list.val_3)" :class="v_amount==list.val_3 && 'active'"><span>{{list.val_3}}</span></td>
                    <td @click="chooseMoney(list.val_4)" :class="v_amount==list.val_4 && 'active'"><span>{{list.val_4}}</span></td>
                </tr>
                </tbody>
            </table>
            <div class="form-item form-select">
                    <span class="label">
                        <span class="text">支付渠道</span>
                        <span class="line"></span>
                    </span>

                <span class="dropdown">
                        <select name="pid" v-model="payid" @change="getBanklist($event)">
                            <option value="">请选择支付渠道</option>

                            <option v-for="(list,index) in dataList" :key="index" :value="list.id" :data-minCurrency="list.minCurrency" :data-maxCurrency="list.maxCurrency" :data-url="list.url">{{list.title}}</option>
                        </select>
                    </span>
            </div>

            <!-- 银行卡线上才有 ，银行 公司入款-->
            <div class="form-item form-select" v-show="showThirdBank">
                    <span class="label">
                        <span class="text">转入银行</span>
                        <span class="line"></span>
                    </span>

                <span class="dropdown">
                        <select name="third_banklist" v-model="third_banklist">
                            <option value="">请选择转入银行</option>
                            <option v-for="(list,index) in third_banklist_arr" :key="index" :value="list.bankcode" >{{list.bankname}}</option>
                        </select>
                    </span>
            </div>

            <div class="tip error">
                <span class="icon"></span>
                <span class="text">必填</span>
            </div>
            <div class="btn-wrap">
                <a href="javascript:;" class="zx_submit" @click="checkThird_pay">申请存款</a>
            </div>

        </template>

    </div>

    <FooterNav />
  </div>
</template>

<script>
import '../../../static/css/icalendar.css'
import '../../../static/js/icalendar.min.js'
import ClipboardJS from 'clipboard';

//import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
    name: 'depositsec',
    mixins: [Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
    data () {
        return {
            set_1:true,
            set_2:false,
            set_3:false,
            bankid: '',
            type_name:'', // 充值方式
            type_api:'', // 充值api
            show_type:'company', // company 公司入款, smzf  微信扫码和支付宝扫码,third 三方支付
            min_money:10, // 三方充值最小金额
            max_money:10000, // 三方充值最大金额
            showThirdBank:false, // 显示三方银行卡线上银行
            third_banklist:'', // 三方银行卡线上银行
            third_banklist_arr:[], // 三方银行卡线上银行
            ajax_url:'', // 请求支付方式地址
            save_ajax_url:'', // 提交存款
            dataList: [],
            payid:'',
            v_Name:'',
            in_type:'',
            into_bank:'',
            save_time:'',
            remark:'',
            chMoneyData:[
                {val_1:100,val_2:300,val_3:500,val_4:800},
                {val_1:1000,val_2:2000,val_3:3000,val_4:5000}
            ]

        }
    },
    mounted: function () {
        let _self = this;
        _self.bankid = this.$route.query.bankid; // 获取参数
        _self.type_name = this.$route.query.typename; // 获取参数
        _self.type_api = this.$route.query.czapi; // 获取参数
        _self.judgeType();

        _self.getUserMessage(1);
        _self.getBnakList('detail',_self.bankid);

    },
    methods: {
        /* 判断是哪种充值方式，公司入款，支付宝扫码，微信扫码 ，
        * company 公司入款, third 微信扫码和支付宝扫码
        * */
        judgeType: function () {
            let _self = this;
            if(_self.thirdDeposit.indexOf( _self.type_api)>=0){ // 三方充值
                _self.show_type = 'third';
//                _self.save_ajax_url = _self.ajaxUrl.saoma_save;
                if(_self.type_api.indexOf('third_zfb') >=0){
                    _self.ajax_url = _self.ajaxUrl.third_zfb;
                }else if(_self.type_api.indexOf('third_wx') >=0){
                    _self.ajax_url = _self.ajaxUrl.third_wx;
                }else if(_self.type_api.indexOf('third_qq') >=0){
                    _self.ajax_url = _self.ajaxUrl.third_qq;
                }else if(_self.type_api.indexOf('third_bank') >=0){ //银行卡线上
                    _self.ajax_url = _self.ajaxUrl.third_bank;
                    if(_self.type_api.indexOf('two_third_bank_youhui')>=0){ // 银行 公司入款
                      _self.ajax_url = _self.ajaxUrl.third_bank_yh;
                    }
                }else {
                    _self.ajax_url = _self.ajaxUrl.third_kscz;
                }
            }else if(_self.type_name.indexOf('支付宝') >=0 || _self.type_name.indexOf('微信') >=0){ //扫码支付
                _self.show_type = 'smzf';
                _self.save_ajax_url = _self.ajaxUrl.saoma_save;
                if(_self.type_name.indexOf('支付宝') >=0){
                    _self.ajax_url = _self.ajaxUrl.alisaoma;
                }else{
                    _self.ajax_url = _self.ajaxUrl.wxsaoma;
                }
            } else if('/account/deposit_two_usdt.php'.indexOf( _self.type_api)>=0){ // usdt 支付
                _self.show_type = 'usdt';
                _self.save_ajax_url = _self.ajaxUrl.saoma_save;
                _self.ajax_url = _self.ajaxUrl.usdt_ewm;
                _self.getUsdtRate(); // 获取usdt 汇率
            }else{ // 公司入款
                _self.show_type = 'company';
                _self.save_ajax_url = _self.ajaxUrl.bankcompany;
                _self.ajax_url = _self.ajaxUrl.banklist;
            }

        },
        /* 时间选择 */
        chooseTime: function () {
            let _self= this;
            // 时间插件初始化 ，公司入款
            _self.save_time = _self.setAmerTime('.time_textbox');
            let calendar = new lCalendar();
            calendar.init({
                'trigger': '.time_textbox',
                'type': 'datetime',
                defaultValue:_self.save_time,
            });
        },

      /* 获取存款列表 */
        getBnakList: function (type, id) {
            let _self = this;
            if (_self.show_type=='company') { // 公司入款不需要
                id = '';
            }
            let pars = {
                type: type,
                bankid: id
            };

                _self.axios({
                    method: 'post',
                    params: pars,
                    url: _self.ajax_url
                }).then(res => {
                    if (res) {
                        let rest = res.data;
                        if (_self.show_type=='smzf' || _self.show_type=='usdt') { // 扫码支付,usdt
                            _self.dataList = rest.data[0];
                            _self.v_Name =  _self.dataList.bank_user;
                            _self.payid =  _self.dataList.id;
                        }else{
                            _self.dataList = rest.data;
                            if(_self.type_api.indexOf('third_bank') >=0){ // 银行卡线上 默认选中第一个处理
                              _self.payid = _self.dataList[0].id;
                              _self.getBanklist();
                              _self.third_banklist = _self.dataList[0].bankList[0].bankcode; // 银行
                            }
                        }

                        if(!(_self.show_type =='third' || _self.show_type =='usdt')){ // 三方支付不需要,usdt
                            _self.$nextTick(()=>{
                                _self.chooseTime();
                                _self.copyBnakAction($('#show_bank_list').find('.bank_list_li a'));
                            });
                        }

                        //console.log(_self.dataList)
                    }
                }).catch(res => {
                    console.log('银行列表请求失败');

                });


        },
        // 选择银行第一步
        chooseBnakType: function (e){
            //let $IntoBank = $('#IntoBank');
            let _self = this;
            let bankId = $(e.currentTarget).attr('data-id');
            let bank = $(e.currentTarget).attr('data-bank');
            _self.set_1 = false;
            _self.set_2 = true;

            $('.bank_list_li_'+bankId).show();
            //$IntoBank.val(bank).attr({'data-id':bankId});
            //$("#payid").val(bankId);
            _self.into_bank=bank;
            _self.payid=bankId;

        },
        // 选择银行第二步
        chooseBnakSec: function (e) {
            let _self = this;
            $('.list-tab thead td h1').text('汇款信息提交');
            _self.set_2 = false;
            _self.set_3 = true;
        },
        // 存款提交
        depositeBankAction :function () {
            let _self =this;
            if(_self.submitflag){
                return false;
            }
            _self.v_amount = Number(_self.v_amount);
            if(_self.v_amount<100){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.mon);
                return false;
            }
            if(!_self.v_Name){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.realname);
                return false;
            }
            if (_self.show_type=='company') { // 公司入款
                if(!_self.in_type){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.banktype);
                    return false;
                }
            }
            if(!(_self.show_type =='usdt')){ //usdt 不需要
              if(!_self.save_time){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.time);
                return false;
              }
              if(!_self.remark){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.remark);
                return false;
              }
            }

            let pars ={
                payid: _self.payid , // 银行卡 id
                v_Name: _self.v_Name , // 真实姓名，公司入款
                bank_user: _self.v_Name , // 真实姓名,扫码支付
                InType: _self.in_type ,
                IntoBank: _self.into_bank ,
                v_amount: _self.v_amount ,
                cn_date: _self.save_time ,
                memo: _self.remark
            }

            _self.submitflag = true ;
            _self.axios({
                method: 'post',
                params: pars,
                url: _self.save_ajax_url
            }).then(res => {
                if (res) {
                    _self.submitflag = false ;

                    let rest = res.data;
                    _self.$refs.autoDialog.setPublicPop(rest.describe);
                    if(rest.status=='200'){
                      // if(_self.show_type =='usdt') { //usdt
                      //   _self.countUsdtMount();
                      // }else {
                        setTimeout(()=>_self.$router.push('/depositrecord'),2000); // 跳转到存款记录
                      //}

                    }
                }
            }).catch(res => {
                _self.submitflag = false ;
                console.log('提交存款请求失败');
            });
        },
        // 复制
        copyBnakAction: function (type) {
            let _self = this;
            // $('#show_bank_list').find('.bank_list_li a')
          type.each(function (num) {
                 //console.log(num+'==');
                let clipboard = new ClipboardJS(this, {
                    text: function () {
                        return $(this).prev().text();
                    }
                });
                clipboard.on('success', function (e) {
                    //console.log(e);
                    _self.$refs.autoDialog.setPublicPop('复制成功');
                    e.clearSelection();
                });
                clipboard.on('error', function (e) {
                    //console.log(e);
                    _self.$refs.autoDialog.setPublicPop('请选择“拷贝”进行复制');
                });
            });
        },
        // 选择三方支付方式
        getBanklist: function (e) {
            let _self = this;
            let hh = _self.dataList.filter(function (c, i, a) {//第一个参数为当前项,第二个参数为索引,第三个为原值
                if (_self.payid.indexOf(c.id)>=0) {
                    return c
                }
            })
            if(_self.type_api.indexOf('third_bank') >=0){ // 银行卡线上
                _self.third_banklist = ''; // 需要初始化
                _self.showThirdBank = true;
                _self.third_banklist_arr = hh[0].bankList?hh[0].bankList:[];
            }

            _self.min_money = hh[0].minCurrency; //获取当前option
            _self.max_money = hh[0].maxCurrency;
            _self.save_ajax_url = hh[0].url; // 三方存款提交地址

        },
        // 三方支付提交
        checkThird_pay: function () {
            let _self = this;
            let uid = _self.memberData.Oid;
            let userid = _self.memberData.userid;
            _self.v_amount = Number(_self.v_amount);
            _self.min_money = Number(_self.min_money);
            _self.max_money = Number(_self.max_money);
            if(_self.payid==='' ){
                _self.$refs.autoDialog.setPublicPop('请选择支付渠道');
                return false;
            }
            if(_self.v_amount<_self.min_money){
                _self.$refs.autoDialog.setPublicPop('最小充值金额'+_self.min_money);
                return false;
            }

            if(_self.v_amount>_self.max_money){
                _self.$refs.autoDialog.setPublicPop('最大充值金额'+_self.max_money);
                return false;
            }

            let sfUrl = _self.save_ajax_url+'?payid='+_self.payid+'&uid='+uid+'&userid='+userid+'&order_amount='+_self.v_amount;

            if(_self.type_api.indexOf('third_bank') >=0){ // 银行卡线上
                if(_self.third_banklist===''){
                    _self.$refs.autoDialog.setPublicPop('转入银行为空');
                    return false;
                }
                sfUrl +='&banklist='+_self.third_banklist;
            }

            _self.openNewGame(sfUrl,'','no')
        },
      // usdt 金额输入与计算
      countUsdtMount:function (){
        let _self = this;
        let zf_val = _self.v_amount/(_self.usdt_rate); // 需要转入的usdt
        zf_val = _self.changeTwoDecimal(zf_val,'up'); // 保留两位小数
        _self.usdt_mon = zf_val;

        },
      // 获取汇率
      getUsdtRate:function () {
        let _self = this;
        _self.axios({
          method: 'post',
          params: {},
          url: _self.ajaxUrl.usdt_rate
        }).then(res => {
          if (res) {
            let rest = res.data;
            if(rest.status=='200'){
              _self.usdt_rate = rest.data.usdt_rate; // 更新汇率
              _self.countUsdtMount();
              _self.$nextTick(()=>{
                _self.copyBnakAction($('.copy_btn'));
              });

            }
          }
        }).catch(res => {
          console.log('USDT汇率请求失败');
        });
      }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .copy_icon{display: inline-block;vertical-align:middle;width: 25px;height: 25px;background: url(/static/images/deposit/fuzhi.png) center no-repeat;background-size: cover;}
  .tip_title{width: 100px;line-height: 50px;text-align: center;border: 1px solid #ccc;border-radius: 5px;font-size: 1.5rem; margin: 0 auto;}
  .cz_url a{color:#000;}
  .tip_top{background: linear-gradient(to bottom, #fef9ed, #f1dbb0);padding: 5px 10px 0;}
  .icon_chat{display: inline-block;width: 20px;height: 25px;background: url(/static/images/deposit/chat_icon.png) center no-repeat;background-size:100%;vertical-align: middle;margin-left: 5px;}
  /* 公司入款开始 */
  .content-center{color:#000;font-size:1.2rem}
  .payWayTit{text-align:left;padding:.5rem 5%}
  /*.payWay{display:none}*/
  .bank_list{overflow:hidden;border:1px solid #ddd;border-left:0;border-right:0}
  .bank_li{background:#fff;border-bottom:1px solid #ddd;width:50%;float:left;color:#000;height:8rem;font-size:1rem;padding:1rem 1%;border-right:1px solid #ddd}
  .bank_li .top_li{height:4.7rem;display:flex;display:-webkit-flex;align-items: center;}
  .bank_li .tip_note{font-size: .8rem;}
  .bank_li:nth-child(2n+1){border-left:0}
  .bank_li:nth-child(2n){border-right:0}
  .bank_li span{font-size:1rem;text-align: left;}
  .bank_li .icon{display:inline-block;width:2.5rem;max-width:50px;height:100%;background-position:center; background-repeat: no-repeat;background-size: 90%;flex: none;-webkit-flex: none;}
  .bank_deposit_bottom{margin:0 auto;width:100%}
  .banks_details{border:1px solid #ddd;color:#656565;font-size:1.1rem}
  .banks_details .bank_list_li {display: none;height: auto;padding: 0;}
  .banks_details>div,.banks_details .bank_list_li>div{display:flex;display:-webkit-flex;padding:5px 2%;border-bottom:1px solid #ddd;height:3rem;align-items:center;text-align:left}
  .banks_details>div:last-child,.banks_details .bank_list_li>div:last-child{border-bottom:0}
  .banks_details>div span:first-child{width:96px}
  .banks_details>div span:nth-child(2){width:220px}
  .banks_details>div a{font-size: 1rem;width:53px;height:33px;line-height:33px;background:#2A8FBD;border-radius:5px;text-align:center;padding:0 7px}
  .deposit_bank_next{display:block;width:94%;text-align:center;margin:15px auto}
  .warn{text-align:left;color:#656565}
  .warn h2{font-size:1.2rem}
  .warn p{font-size:1rem}
  .tip{padding:0 1%;font-size:1rem}
  .sm_tip{text-align: left;font-size: .9rem;padding: 1rem 2%;}
  /* 公司入款结束*/

</style>
