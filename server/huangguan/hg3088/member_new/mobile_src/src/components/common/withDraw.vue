<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="withdraw-form">
        <form method="post" name="withdraw_form" id="withdraw_form" >
          <div class="form-item">
            <span class="label">
                <span class="text">提款打码量</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                 <input type="text" v-model="owe_bet" autocomplete="off" readonly />
            </span>
          </div>
          <div class="form-item">
            <span class="label">
                <span class="text">已打码量</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
              <input id="total_bet" style="width: 60%;" type="text" v-model="total_bet" autocomplete="off" readonly />
              <a id="user_bet_list" style="float:right; width: 34%;" class="zx_submit" @click="showUserBet">查看详情</a>
            </span>
          </div>
          <input type="text"  name="abcd_Address" style="display: none" autocomplete="on" readonly /> <!-- 防止 填充-->

          <div class="form-item form-select">
                        <span class="label">
                            <span class="text">所在银行</span>
                            <span class="line"></span>
                        </span>
            <span class="textbox">
                            <input type="text" class="no-bandbank" v-model="chg_bank" autocomplete="off" readonly />
                    </span>
          </div>
          <div class="form-item form-select">
            <input type="radio" name="choose_type" class="checkbox choose_w_type" checked @click="chooseWithdraw('bank')" />
            <span class="label">
                <span class="text">银行账户</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input type="text" class="no-bandbank" v-model="hide_bank_Account" autocomplete="off" readonly />

            </span>
          </div>

          <!-- usdt -->
          <div class="form-item form-select" v-if="show_usdt_ch">
            <input type="radio" name="choose_type" class="checkbox choose_w_type" @click="chooseWithdraw('usdt')" />
            <span class="label">
                <span class="text">USDT(TRC20)提款地址</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input type="text" class="no-bandbank" v-model="hide_usdt_Account" autocomplete="off" readonly />

            </span>
          </div>

          <div class="form-item form-select">
            <span class="label">
                <span class="text">开户行</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                 <input type="text" class="no-bandbank" name="Bank_Address" v-model="bank_Address" autocomplete="off" readonly />
            </span>

          </div>

          <div class="form-item form-select">
            <span class="label">
                <span class="text">提款金额</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input type="number" class="money-textbox" name="Money" v-model="v_amount" autocomplete="off" placeholder="请输入提款金额" @keyup="countUsdtMount">
            </span>

          </div>
          <div class="form-item form-select" v-if="show_usdt_je" style="text-align: left;padding-left: 34%;line-height: 1.5rem;">
                <!-- 有充值过才显示usdt 金额 -->
            <div class="show_usdt" >
              <span>USDT提币数量：<span class="red_color pay_to_usdt">{{usdt_mon}}</span></span>
              <p>实时汇率：<span class="red_color new_usdt_rate">{{usdt_rate}}</span></p>
            </div>
          </div>
          <table class="money moneychoose">

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
                        <span class="label" id="testaaa">
                            <span class="text">提款密码</span>
                            <span class="line"></span>
                        </span>
            <span class="textbox">
                            <input type="password" style="display: none" name="abc_Passwd" autocomplete="on" minlength="4" maxlength="6" disabled /> <!-- 用于防止自动填充密码 -->
                            <input type="password" v-model="pay_pwd" autocomplete="off" minlength="4" maxlength="6" placeholder="请输入提款密码" />
                        </span>
          </div>
          <div class="btn-wrap">
            <a class="zx_submit" @click="withcCheckInput">确认提款</a>
            <a @click="goBack" class="zx_submit btn-reg">取消</a>
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
  name: 'withdraw',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
        owe_bet: 0,
        total_bet: 0,
        dataList: [],
        chg_bank: '' , // 开户银行
        hide_bank_Account: '' , // 银行账户
        bank_Account: '' , // 银行账户
        bank_Address: '' , // 银行地址
        show_usdt_ch:false,
        show_usdt_je:false,
        hide_usdt_Account: '' , // usdt账户
        usdt_Account: '' , // usdt账户
        w_type:'bank', // 默认银行取款
        pay_pwd:'', // 提款密码
        chMoneyData:[
            {val_1:100,val_2:300,val_3:500,val_4:800},
            {val_1:1000,val_2:2000,val_3:3000,val_4:5000}
        ]
    }
  },
    mounted: function () {
        let _self = this ;
        _self.judgeTestFlag();
        _self.getUserMessage(2);
        if(!_self.memberData.Alias){ // 未设置真实姓名，跳转到设置真实姓名
          _self.$refs.autoDialog.setPublicPop('请先设置真实姓名');
          setTimeout(()=>_self.$router.push('/setrealname'),1500);
        }else{
            _self.getUserAllBet();
        }
        _self.getUserMoney();


        _self.getUserUsdtRate();
        _self.countUsdtMount();


    },
    methods:{
      /* 获取用户打码量 */
      getUserAllBet: function () {
          let _self =this;
          let senddata={appRefer:4};
          _self.axios({
              method: 'post',
              params: senddata,
              url: _self.ajaxUrl.userbank
          }).then(res=>{
              if(res){
                  let rest = res.data;
                  _self.dataList=rest.data;
                  _self.owe_bet = rest.data.owe_bet;
                  _self.total_bet = rest.data.total_bet;
                  _self.chg_bank= _self.dataList.Bank_Name;
                  _self.hide_bank_Account=_self.returnBankAccount(_self.dataList.Bank_Account);
                  _self.bank_Account=_self.dataList.Bank_Account;
                  _self.bank_Address=_self.dataList.Bank_Address;

                  _self.hide_usdt_Account=_self.returnBankAccount(_self.dataList.Usdt_Address);
                  _self.usdt_Account=_self.dataList.Usdt_Address;
                  if(_self.dataList.Usdt_Address){ // 如果绑定 usdt 账号，展示
                    _self.show_usdt_ch = true;
                  }

                  if(_self.chg_bank=='' || _self.bank_Account=='' || _self.bank_Address==''){// 未设置银行卡信息
                    _self.$refs.autoDialog.setPublicPop('请先设置银行账号和提款密码');
                    setTimeout(()=>_self.$router.push('/bankcard'),1500);
                  }

              }
          }).catch(res=>{
              console.log('打码量获取失败');
          });
      },
      /* 查看打码量 */
        showUserBet: function () {
          let _self =this;
          let str ='<table width="100%" border="0" class="table_ml table_record">' +
              '<thead>' +
              '<tr><th style="width: 50%">类别</th><th style="width: 50%">打码量</th></tr>' +
              '</thead>' +
              '<tbody>';
          for(let ii=0; ii<_self.dataList.bet_list.length;ii++){
              str +='<tr><td>'+_self.dataList.bet_list[ii].msg+'</td><td>'+_self.dataList.bet_list[ii].value+'</td></tr>';
          }
            str +='<tr><td>总计：</td><td>'+_self.dataList.total_bet+'</td></tr>';
            str +='</tbody>' +
              '</table>';
            _self.$refs.autoDialog.setPublicPop(str,'打码量列表','pop_all_more',60000);
        },
      /* 提款提交 */
      withcCheckInput:function () {
          let _self =this;
          if(_self.submitflag){
              return false;
          }
          _self.v_amount = Number(_self.v_amount);
          if(!_self.isNumber(_self.v_amount) || _self.v_amount < 100){
              _self.$refs.autoDialog.setPublicPop('请输入正确的提款金额，不能小于100元！');
              return false;
          }
          if(_self.pay_pwd == '' || (_self.pay_pwd.length > 6 && _self.pay_pwd.length < 4 || !_self.isNumber(_self.pay_pwd,'can'))){
              _self.$refs.autoDialog.setPublicPop('请输入正确的提款密码！');
              return false;
          }
          let senddata={
              Bank_Address: _self.bank_Address,
              //Bank_Account: _self.bank_Account,
              Bank_Name: _self.chg_bank,
              Money: _self.v_amount,
              Withdrawal_Passwd: _self.pay_pwd,
              //Alias: _self.memberData.Alias
          };
          if(_self.w_type=='usdt'){
            senddata.usdt_rate = _self.usdt_rate; // 传多一个参数
          }

          _self.submitflag = true;
          _self.axios({
              method: 'post',
              params: senddata,
              url: _self.ajaxUrl.take
          }).then(res=>{
              if(res){
                  _self.submitflag = false;
                  let rest = res.data;
                  _self.$refs.autoDialog.setPublicPop(rest.describe);
                  if(rest.status =='200'){ // 成功
                      setTimeout(()=>_self.$router.back(),2000);
                  }
              }
          }).catch(res=>{
              _self.submitflag = false;
              console.log('提款请求失败');
          });
      },
      // 获取usdt账号信息
      getUserUsdtRate:function (){
        let _self = this;
        _self.axios({
          method: 'post',
          params: {action:'getUsdtAddress'},
          url: _self.ajaxUrl.usdt_rate
        }).then(res => {
          if (res) {
            let rest = res.data;
            if(rest.status=='200'){
              _self.usdt_rate = rest.data.withdrawals_usdt_rate; // 更新汇率
            }
          }
        }).catch(res => {
          console.log('USDT汇率请求失败');
        });

      },
      // 选择提款方式
      chooseWithdraw:function (type) {
        let _self = this;
        _self.w_type = type;
        if(_self.w_type=='usdt'){
          _self.show_usdt_je = true;
        }else{
          _self.show_usdt_je = false;
        }
      }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .pop_all_more >>> .table_ml thead tr, .pop_all_more >>> .table_ml thead th,.pop_all_more >>> .table_ml td{background: #fff}
  .pop_all_more >>> .table_ml td{padding: 10px 3px;}
  .pop_all_more >>> .Pop-up{height: 60%;top: 10%;}
  .pop_all_more >>> .Pop-up .pop_text{height: 82%;overflow-y: auto;}
  .pop_all_more >>> .table_ml{margin: 0;}
  .checkbox{float: left;margin-left: -3px;}
  .form-item .label{color: #989898;display: flex;float: left;align-items: center;line-height: normal;}
</style>
