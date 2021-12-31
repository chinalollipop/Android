<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit-two">
        <div data-area="bank_pay">
            <form method="post" name="setinfo" id="setinfo" action="">
                <div class="form-item form-select">
                    <span class="label">
                        <span class="text">真实姓名:</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                    <input type="text" name="realname" v-model="realname" placeholder="提款行卡的姓名，用于提款" />
                </span>
                </div>
                <!--<div class="form-item form-select">
                    <span class="label">
                        <span class="text">手机号码:</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                        <input type="text" name="phone" id="phone" class="bank-account" placeholder="请输入11位手机号码" />
                    </span>
                </div>-->
                <!--<div class="form-item form-select">
                    <span class="label">
                        <span class="text">微信</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                        <input type="text" name="wechat" id="wechat" class="bank-address" placeholder="微信号码" />
                     </span>
                </div>
                <div class="form-item form-select">
                    <span class="label">
                        <span class="text">生日</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                        <input id="birthday" maxlength="12"  type="text" name="birthday" placeholder="请填写出生年月日" readonly />
                     </span>
                </div>-->
                <div class="btn-wrap">
                    <a href="javascript:;" class="zx_submit" @click="setRealName">提交设置</a>
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
  name: 'setrealname',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
      realname:''
    }
  },
    mounted: function () {
        let _self = this ;

    },
    methods:{

      /* 获取用户银行信息 type:'add' 设置银行信息 */
        setRealName:function () {
            let _self = this ;
            if(_self.submitflag){
                return false;
            }
            if(!(_self.realname || _self.trueName(_self.realname)) ){
                _self.$refs.autoDialog.setPublicPop(_self.alerttitle.realname);
                return false;
            }

            let pars ={
                realname : _self.realname,
//              phone : phone,
//              wechat : wechat,
//              birthday : birthday
            };
            _self.submitflag = true ;
            _self.axios({
                  method: 'post',
                  params: pars,
                  url: _self.ajaxUrl.realname
              }).then(res=>{
                  if(res){
                      _self.submitflag = false ;
                      let rest = res.data;
                      _self.$refs.autoDialog.setPublicPop(rest.describe);
                      if(rest.status =='200'){ // 成功
                          let o_member_Data = JSON.parse(localStorage.getItem('userData'));
                          o_member_Data.data.Alias = _self.realname ; // 更新真实姓名
                          _self.localStorageSet('userData',o_member_Data.data) ;

                          setTimeout(()=>_self.$router.push('/bankcard'),2000);
                      }

                  }
              }).catch(res=>{
                  _self.submitflag = false ;
                  console.log('设置真实姓名失败');
              });
        },

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
