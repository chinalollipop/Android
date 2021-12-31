<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="nav_counter ">
        <a :class="showtab=='1' && 'current'" @click="tabChange(1)">个人资料</a>
        <a :class="showtab=='2' && 'current'" @click="tabChange(2)">修改密码</a>
      </div>

      <!--  个人资料 -->
      <div v-show="showtab=='1'" class="tab_person_item user" >
        <div v-if="showmore_dis" class="user_logo"></div>
        <ul>
          <li>
            <h3> <img v-if="showmore_dis" src="/static/images/3366/user/name.png" alt=""/> 用户名：</h3><h4 id="accountcode">{{memberData.UserName}}</h4>
          </li>
          <li>
            <h3> <img v-if="showmore_dis" src="/static/images/3366/user/write.png" alt=""/> 姓名：</h3><h4 style="color:red">{{memberData.Alias_hide}}</h4> <!-- 请至绑订银行卡做相关设定 -->
          </li>
          <!-- <li>
               <h3>性别：</h3>
               <h4>
                   <select id="change_gender" class="show_fix_button">
                       <option value="">请选择</option>
                       <option value="m">男</option>
                       <option value="f">女</option>
                   </select> <a href="javascript:void(0)" class="change_fix" style="display:none" data-inputid="change_gender">修改</a>
               </h4>
           </li>-->
          <li>
            <h3> <img v-if="showmore_dis" src="/static/images/3366/user/phone.png" alt=""/> 手机号：</h3><h4><input type="text" pattern="\d*" class="show_fix_button" :value="memberData.Phone" readonly="true" id="check_phone">
          </h4>
          </li>
          <!-- <li>
               <h3>验证码：</h3><h4><input type="text" pattern="\d*" class="show_fix_button" id="check_number"> <a href="javascript:void(0)" class="check_number" style="display:none">确定</a> </h4>
           </li>
           <li>
               <h3>邮箱：</h3><h4></h4>
           </li>-->
          <li>
            <h3> <img v-if="showmore_dis" src="/static/images/3366/user/wechat.png" alt=""/> 微信号：</h3>
            <h4>
              <input type="text" class="show_fix_button" id="change_wechat" :value="memberData.E_Mail" readonly />
              <a href="javascript:void(0)" class="change_fix" style="display:none" data-inputid="change_wechat">修改</a>
            </h4>
          </li>
          <li>
            <h3> <img v-if="showmore_dis" src="/static/images/3366/user/day.png" alt=""/> 生日：</h3>
            <h4>
              <input type="text" class="show_fix_button" id="change_birthday" :value="memberData.birthday" readonly />
            </h4>
          </li>
        </ul>
      </div>

      <div v-show="showtab=='2'" class="change_pswd user" >
        <!-- 修改登录密码 -->
        <div class="change-loginpsw" >
          <!--   <div class="password-tip">
                 <p>修改登录密码</p>
                 <span>为了您的帐户安全,我们强烈建议您每30天修改一次密码。</span>
             </div>-->

          <form  name="chg_log_password" id="chg_log_password" >
            <input type="hidden" name="flag_action" value="1"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
            <ul class="textbox-list">
              <li>
                <h3>旧登录密码：</h3>
                <input type="password" name="oldpassword" v-model="oldpassword" minlength="6" maxlength="15" class="enter" placeholder="原密码" autocomplete="off" >
              </li>
              <li>
                <h3>新登录密码：</h3>
                <input type="password" name="password" v-model="password" minlength="6" maxlength="15" class="enter" placeholder="新密码（6-15个字符）" autocomplete="off" >
              </li>
              <li>
                <h3>密码确认：</h3>
                <input type="password" name="REpassword" v-model="REpassword" minlength="6" maxlength="15" class="enter" placeholder="确认密码（6-15个字符）" autocomplete="off" >
              </li>


            </ul>

            <div class="changepsw-bottom">
              <input v-if="memberData.test_flag==0" type="button" class="zx_submit " value="确认修改" @click="changePwdSub(1)" readonly />
            </div>
          </form>
        </div>

        <!-- 修改支付密码 -->
        <div class="change-loginpsw">
          <div class="password-tip">
            <p>修改提款密码</p>
            <span>为了您的资金安全,我们强烈建议您不定时修改密码。</span>
          </div>
          <form method="post" name="chg_pay_password" id="chg_pay_password" >
            <input type="hidden" name="flag_action" value="2"> <!-- 1 为修改登录密码，2 为修改支付密码 -->
            <ul class="textbox-list">
              <li>
                <h3>旧提款密码：</h3>
                <input type="password" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="pay_oldpassword" v-model="pay_oldpassword" minlength="4" maxlength="6" class="inp-txt" placeholder="原密码" autocomplete="off" >
              </li>
              <li>
                <h3>新提款密码：</h3>
                <input type="password" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="pay_password" v-model="pay_password" minlength="6" maxlength="6" class="inp-txt" placeholder="新密码（6位纯数字）" autocomplete="off" >
              </li>
              <li>
                <h3>密码确认：</h3>
                <input type="password" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" name="pay_REpassword" v-model="pay_REpassword" minlength="6" maxlength="6" class="inp-txt" placeholder="确认密码（6位纯数字）" autocomplete="off" >
              </li>

            </ul>
            <div class="changepsw-bottom">
              <input v-if="memberData.test_flag==0" type="button" class="sure_changepsw zx_submit " @click="changePwdSub(2)" value="确认修改" readonly />
            </div>
          </form>
        </div>

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
  name: 'myaccount',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
      showtab:1,
      oldpassword: '',
      password: '',
      REpassword: '',
      pay_oldpassword: '',
      pay_password: '',
      pay_REpassword: '',
    }
  },
    mounted: function () {
        let _self = this ;
        _self.judgeTestFlag();
    },
    methods:{
      /* 标签切换 */
      tabChange: function (type) {
          let _self = this ;
          _self.showtab = type;
      },
      /* 提交修改密码 ,1 为修改登录密码，2 为修改支付密码 */
        changePwdSub: function (type) {
            let _self = this;
            if(_self.submitflag){
                return false;
            }
            let pars ={
                action: 1,
                flag_action: type,
            }
            if(type==1){ // 登录密码
                if(_self.oldpassword==''){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_input_oldpwd);
                    return false;
                }
                if(_self.password=='' || _self.password.length<6 || _self.password.length>15){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_input_pwd);
                    return false;
                }
                if(_self.password!=_self.REpassword){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_err_pwd);
                    return false;
                }
                pars.oldpassword = _self.oldpassword;
                pars.password = _self.password;
            }else{ // 支付密码
                if(_self.pay_oldpassword==''){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_input_oldpwd);
                    return false;
                }
                if(_self.pay_password=='' || _self.pay_password.length!=6){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_input_pwd);
                    return false;
                }
                if(_self.pay_password!=_self.pay_REpassword){
                    _self.$refs.autoDialog.setPublicPop(_self.alerttitle.str_err_pwd);
                    return false;
                }
                pars.pay_oldpassword = _self.pay_oldpassword;
                pars.pay_password = _self.pay_password;
            }
            _self.submitflag = true ;
            _self.axios({
                method: 'post',
                params: pars,
                url: _self.ajaxUrl.chgpwd
            }).then(res=>{
                if(res){
                    _self.submitflag = false ;
                    let rest = res.data;
                    _self.$refs.autoDialog.setPublicPop(rest.describe);
                    if(rest.status =='200'){ // 成功
                        if(type==1){
                            setTimeout(()=>_self.loginOut(),2000);
                        }else{
                            setTimeout(()=>_self.$router.back(),2000);
                        }
                    }
                }
            }).catch(res=>{
                _self.submitflag = false ;
                console.log('修改密码失败');
            });

        }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
