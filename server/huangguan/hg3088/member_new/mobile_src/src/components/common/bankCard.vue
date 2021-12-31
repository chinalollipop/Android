<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit-two">
      <!-- 设置银行开始 -->
      <div data-area="bank_pay">
        <form method="post" name="setbank" id="setbank" action="">
          <div class="form-item form-select">
                    <span class="label">
                        <span class="text">账户姓名:</span>
                        <span class="line"></span>
                    </span>
            <span class="textbox">
                    <input type="text" :value="memberData.Alias_hide" readonly />
             </span>
          </div>
          <div class="form-item form-select">
                        <span class="label">
                            <span class="text"> 开户银行:</span>
                            <span class="line"></span>
                        </span>
            <span class="dropdown">
                <select id="chg_bank" name="chg_bank" v-model="chg_bank">
                    <option value="">***选择银行***</option>
                    <option v-for="(list,item) in allBankList" :value="list" :key="item">{{list}}</option>
                </select>
                </span>
          </div>


          <div class="form-item form-select">
                        <span class="label">
                            <span class="text">银行账号:</span>
                            <span class="line"></span>
                        </span>
            <span class="textbox">
                    <!--<input :type="showBankTip?'hidden':'text'" class="show-bank-account" v-model="hide_bank_Account" @focus="showBankAccount" placeholder="银行账号" />
                    <input :type="showBankTip?'text':'hidden'"  v-model="bank_Account" class="bank-account" placeholder="银行账号" />-->
                  <input type="text" class="show-bank-account" v-model="hide_bank_Account" placeholder="银行账号" />
                  <input type="hidden" class="show-bank-account" v-model="old_bank_Account"/>
                 </span>
          </div>


          <div class="form-item form-select">
                <span class="label">
                    <span class="text">银行地址:</span>
                    <span class="line"></span>
                </span>
            <span class="textbox">
                    <input type="text" name="bank_Address" v-model="bank_Address" class="bank-address" placeholder="银行地址" />

                 </span>
          </div>
          <div class="form-item form-select" v-if="tpl_name !='jinsha/'">
                <span class="label">
                    <span class="text">TRC20的提币地址:</span>
                    <span class="line"></span>
                </span>
            <span class="textbox">
                    <input :type="showUsdtTip?'hidden':'text'" class="show-bank-account" v-model="hide_usdt_Address" placeholder="TRC20的提币地址" readonly />
                    <!--<input :type="showUsdtTip?'text':'hidden'"  v-model="usdt_Address" class="usdt-account" placeholder="TRC20的提币地址" />-->
            </span>
          </div>
          <div class="tip error" style="text-align: left;padding: 0 3%;">
            <span class="icon"></span>
            <span class="text">如需修改提币地址，请联系客服</span>
          </div>
          <!-- 资金密码 -->
          <template v-if="bank_pwd_flage==0">
            <div class="form-item form-select">
                  <span class="label">
                      <span class="text">设置提款密码:</span>
                      <span class="line"></span>
                  </span>
              <span class="textbox">
                      <input type="password" name="paypassword1" v-model="paypassword1" class="paypassword1" minlength="6" maxlength="6" placeholder="请输入6位纯数字" />
                   </span>
            </div>
            <div class="form-item form-select">
                  <span class="label">
                      <span class="text">确认提款密码:</span>
                      <span class="line"></span>
                  </span>
              <span class="textbox">
                      <input type="password" name="paypassword2" v-model="paypassword2" class="paypassword2" minlength="6" maxlength="6" placeholder="确认提款密码" />
                   </span>
            </div>
          </template>

          <div class="btn-wrap">
            <a href="javascript:;" class="zx_submit bind_bank_btn" @click="getUserBankList('add')">提交设置</a>
            <a @click="goBack" class="zx_submit btn-reg">取消设置</a>
          </div>
        </form>
      </div>
    </div>

    <FooterNav />
  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
  name: 'bankcard',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
      showBankTip:false,
      showUsdtTip:false,
      userBankData:[],
      bank_pwd_flage: 0 , // 是否设置提款密码
      chg_bank: '' , // 开户银行
      old_bank_Account: '' , // 原银行账户
      hide_bank_Account: '' , // 银行账户
      bank_Account: '' , // 银行账户
      bank_Address: '' , // 银行地址
      usdt_Address: '' , // USDT地址
      hide_usdt_Address: '' , // USDT地址
      bankFlag: 1 , // 绑定账号标识
      paypassword1: '',
      paypassword2: ''
    }
  },
    mounted: function () {
        let _self = this ;
        _self.judgeTestFlag();
        _self.getUserBankList();
        _self.getAllBankList();

    },
    methods:{
      /* 编辑银行卡 */
        showBankAccount: function (type) {
            if(type=='usdt'){
              this.showUsdtTip =true;
            }else{
              this.showBankTip =true;
            }
        },
      /* 获取用户银行信息 type:'add' 设置银行信息 */
        getUserBankList:function (type) {
            let _self = this ;
            let pars ={};
            if(_self.submitflag){
                return false;
            }
            if(type=='add'){ // 设置银行卡
                if(!_self.chg_bank){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.chg_bank);
                    return false;
                }
                if(_self.old_bank_Account == _self.hide_bank_Account){
                  _self.$refs.autoDialog.setPublicPop('未更换银行账号！');
                  return false;
                }
                if(!(_self.hide_bank_Account || _self.isBankAccount(_self.hide_bank_Account))){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.hide_bank_Account);
                    return false;
                }
                if(!_self.bank_Address){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.bank_Address);
                    return false;
                }
                if(_self.bank_pwd_flage==0){
                  if(!_self.isNumber(_self.paypassword1) || _self.paypassword1.length < 6 || _self.paypassword1.length > 6 ){
                      _self.$refs.autoDialog.setPublicPop(_self.alerttitle.paypassword1);
                      return false;
                  }
                  if( _self.paypassword2=='' ||  _self.paypassword1 !=  _self.paypassword2 ){
                      _self.$refs.autoDialog.setPublicPop('两次输入的提款密码不一致！');
                      return false;
                  }
                }

                pars ={
                    action: type,
                    chg_bank: _self.chg_bank , // 开户银行
                    //bank_Account: _self.bank_Account , // 银行账户
                    bank_Account: _self.hide_bank_Account , // 银行账户
                    bank_Address: _self.bank_Address , // 银行地址
                    //usdt_address: _self.usdt_Address , // USDT地址
                    bankFlag: _self.bankFlag , // 绑定账号标识
                    paypassword1: _self.paypassword1,
                    paypassword2: _self.paypassword2
                }
            }
            _self.submitflag = true ;
            _self.axios({
                  method: 'post',
                  params: pars,
                  url: _self.ajaxUrl.userbank
              }).then(res=>{
                  if(res){
                      _self.submitflag = false ;
                      let rest = res.data;
                      if(type=='add'){ // 设置银行卡
                          _self.$refs.autoDialog.setPublicPop(rest.describe?rest.describe:'error--');
                          if(rest.status=='200'){
                              setTimeout(()=>_self.$router.push('/withdraw'),2000); // 跳转到存款
                          }
                      }else{
                        _self.userBankData=rest.data;
                        _self.chg_bank= _self.userBankData.Bank_Name;
                        _self.old_bank_Account = _self.hide_bank_Account=_self.returnBankAccount(_self.userBankData.Bank_Account);
                        _self.bank_Account=_self.userBankData.Bank_Account;
                        _self.bank_Address=_self.userBankData.Bank_Address;
                        _self.hide_usdt_Address=_self.returnBankAccount(_self.userBankData.Usdt_Address);
                        _self.usdt_Address=_self.userBankData.Usdt_Address;
                        _self.bank_pwd_flage=_self.userBankData.bank_pwd;
                      }

                  }
              }).catch(res=>{
                  _self.submitflag = false ;
                  console.log('银行信息请求失败');
              });
        },

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .form-item .label{width: 40%;}
  .deposit-two select,.deposit-two .textbox, .withdraw-form .textbox{width: 58%;}
</style>
