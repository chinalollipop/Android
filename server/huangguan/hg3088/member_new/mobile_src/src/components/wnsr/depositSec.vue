<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit-two">
        <template v-if="show_type">

          <!-- 公司入款开始 -->
          <div class="payWay bank_deposit_3" data-area="bank_pay">

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
                        <span class="text">转入银行</span>
                        <span class="line"></span>
                    </span>
                  <span class="dropdown">
                            <select name="IntoBank" v-model="into_bank" @change="chooseBnakType($event)">
                                <option value="">请选择转入银行</option>
                                <option v-for="(list,index) in dataList" :key="index" :data-id="list.id" :value="list.bank_name+'-'+list.bank_user">{{list.bank_name}}</option>
                            </select>
                        </span>
              </div>
              <div id="seebank" class="seebank-div" @click="checkAction">查看银行账号</div>
              <div class="bank_list" id="show_bank_list" v-show="!checkStatus">
                  <div class="bank_list_li" v-for="(list,index) in dataList" :key="index">{{list.bank_name}}<br>
                      <span :class="'bank_username_'+list.id">{{list.bank_user}}</span>
                      <a :class="'cp_bank_username_'+list.id" :data-clipboard-target="'.bank_username_'+list.id">复制</a><br>
                      <span :class="'bank_useraccount_'+list.id">{{list.bank_account}}</span>
                      <a :class="'cp_bank_username_'+list.id" :data-clipboard-target="'.bank_useraccount_'+list.id">复制</a>
                  </div>
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
        <template v-if="!show_type">
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
            bankid: '',
            type_name:'', // 充值方式
            show_type:true, // true ,company 公司入款, false ,third 微信扫码和支付宝扫码
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
            if(_self.type_name.indexOf('支付宝') >=0 || _self.type_name.indexOf('微信') >=0){
                _self.show_type = false;
                _self.save_ajax_url = _self.ajaxUrl.saoma_save;
                if(_self.type_name.indexOf('支付宝') >=0){
                    _self.ajax_url = _self.ajaxUrl.alisaoma;
                }else{
                    _self.ajax_url = _self.ajaxUrl.wxsaoma;
                }
            }else{ // 公司入款
                _self.show_type = true;
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
            if (_self.show_type) { // 公司入款不需要
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
                        if (_self.show_type) { // 公司入款
                            _self.dataList = rest.data;
                        }else{ // 扫码支付
                            _self.dataList = rest.data[0];
                            _self.v_Name =  _self.dataList.bank_user;
                            _self.payid =  _self.dataList.id;
                        }
                        _self.$nextTick(()=>{
                            _self.chooseTime();
                            _self.copyBnakAction();
                        });
                        //console.log(_self.dataList)
                    }
                }).catch(res => {
                    console.log('银行列表请求失败');
                });


        },
        // 选择银行
        chooseBnakType: function (e){
            let _self = this;
            let hh = _self.dataList.filter(function (c, i, a) {//第一个参数为当前项,第二个参数为索引,第三个为原值
                if (_self.into_bank.indexOf(c.bank_name)>=0) {
                    return c
                }
            })
            let bankId = hh[0].id; //获取当前option的id的值

            _self.payid=bankId;

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
            if (_self.show_type) { // 公司入款
                if(!_self.in_type){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.banktype);
                    return false;
                }
                if(!_self.payid ){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.bank);
                    return false;
                }
            }
            if(!_self.save_time){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.time);
                return false;
            }
            if(!_self.remark){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.remark);
                return false;
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
                        setTimeout(()=>_self.$router.push('/depositrecord'),2000); // 跳转到存款记录
                    }
                }
            }).catch(res => {
                _self.submitflag = false ;
                console.log('提交存款请求失败');
            });
        },
        // 复制
        copyBnakAction: function () {
            let _self = this;
            $('#show_bank_list').find('.bank_list_li a').each(function (num) {
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
        }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .bank_list{text-align: left;padding: 0 3%;}
    .bank_list a {padding: 3px 6px;background: #413e45;border-radius: 5px;margin: 0 3px;}
    .bank_list .bank_list_li {padding-bottom: 10px;border-bottom: 1px solid #eaeaea;}
</style>
