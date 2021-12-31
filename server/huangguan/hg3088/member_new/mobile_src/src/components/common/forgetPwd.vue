<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" class=""/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="member_reg forget_all ">
        <div class="textbox-list" :class="tpl_name=='8msport/' && 'login_form login_form_forget'">
          <ul>
            <li>
              <div>
                <label>
                  <span class="account-icon"><em class="red_color">*</em> 帐号</span>
                </label>
                <span class="textbox">
                    <input type="text" name="username" v-model="username" minlength="5" maxlength="15" class="inp-txt" placeholder="会员帐号（5-15位数字或字母）">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon"><em class="red_color">*</em> 真实姓名</span>
                </label>
                <span class="textbox">
                    <input type="text" name="alias" v-model="alias" maxlength="15" class="inp-txt" placeholder="请输入您的真实姓名">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon2"><em class="red_color">*</em> 提款密码</span>
                </label>
                <span class="textbox">
                      <input type="number" name="paypassword" v-model="paypassword" minlength="4" maxlength="6" class="inp-txt" placeholder="请输入4-6位纯数字">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon"><em class="red_color">*</em> 新密码</span>
                </label>
                <span class="textbox">
                    <input type="password" name="password" v-model="password"  minlength="6" maxlength="15" class="inp-txt" placeholder="密码（6-15个字符）">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon2"><em class="red_color">*</em> 确认新密码</span>
                </label>
                <span class="textbox">
                      <input type="password" name="password2" v-model="password2" minlength="6" maxlength="15" class="inp-txt" placeholder="确认密码（6-15个字符）">
                </span>
              </div>
            </li>
          </ul>
        </div>

        <div class="btn-wrap">
          <a class="zx_submit before_yz" @click="reqSubmit">提交</a>

        </div>
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
        name: 'reg',
        mixins:[Mixin],
        components: {
            HeaderNav,
            Dialog
        },
        data () {
            return {
                username :'',
                alias :'',
                paypassword :'',
                password :'',
                password2 :''
            }
        },
        mounted: function () {
            let _self = this ;

        },
        methods:{
          /* 注册行为 */
            reqSubmit: function () {
                let _self =this;
                if(_self.submitflag){
                    return false ;
                }
                if(_self.username ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入帐号') ;
                    return false ;
                }
                if(_self.alias ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入真实姓名') ;
                    return false ;
                }
                if(_self.paypassword ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入提款密码') ;
                    return false ;
                }
                if(_self.password ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入新密码') ;
                    return false ;
                }
                if(_self.password !=_self.password2){
                    _self.$refs.autoDialog.setPublicPop('密码与确认密码不一致') ;
                    return false ;
                }

                let senddata = {
                    appRefer:'', //  是  int  终端ID
                    action_type:'reset', // String  1.check；2：recheck；3：reset(避免交互过多，可填好相关信息，直接传 reset
                    username: _self.username,
                    realname:_self.alias, // 真实姓名 action_type是recheck和reset时必填  String  用户真实账号
                    withdraw_password:_self.paypassword, // action_type是recheck和reset时必填  String  用户提款密码
                    // birthday:birthday, //  action_type是recheck和reset时必填  Date  用户生日
                    new_password:_self.password, // action_type是reset时必填  String  新密码
                    password_confirmation:_self.password2, // action_type是reset时必填  String  确认密码
                };

                _self.submitflag = true;
                _self.axios({
                    method: 'post',
                    params: senddata,
                    url: _self.ajaxUrl.forgetpwd
                }).then(res=>{
                    if(res){
                        _self.submitflag = false;
                        let rest = res.data;
                        if (rest.describe) {
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }
                        if (rest.status == '200') { // 登录成功
                            setTimeout(()=>_self.$router.push('/login'),2000);
                        }
                    }
                }).catch(res=>{
                    _self.submitflag = false;
                    console.log('会员更改密码失败');
                });

            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .textbox-list li {margin-bottom: .8rem;}
  label span {display: inline-block;width: 30%;height: 3rem;line-height: 3rem;float: left;color: #aeaeae;}
  .textbox-list .textbox input, .textbox-list select {display: inline-block;height: 3rem;width: 68%;}
</style>
