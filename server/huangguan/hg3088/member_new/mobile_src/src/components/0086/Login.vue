<template>
  <div >
    <HeaderNav pa_showback="" pa_title="用户登录" class="header_nav"/>
    <childLogin ref="child_Login" @child_data="getChildValue" :P_Data="parentData"></childLogin>

    <div class="content-center">
      <div class="login_div">
        <!-- <h4>请先登入您的会员帐号</h4>-->
        <div class="login-logo"></div>
        <input type=hidden name="demoplay" id="demoplay" value="">
        <ul class="login_form">
          <li>
            <span class="logaccount-icon"> </span>
            <input autocomplete="off" v-model="parentData.username" type="text" class="za_text" size="20" placeholder="请输入会员帐号" /></li>
          <li class="psw_li">
            <span class="logpwd-icon"></span>
            <input autocomplete="off" v-model="parentData.password" :type="checkStatus?'password':'text'" class="za_text"  maxlength="15" size="20" placeholder="密码（6-15个字符）"/>
            <a class="see_psw" :class="checkStatus?'see_psw_close':'see_psw_open'" @click="checkAction"></a>
          </li>
          <li v-if="baseSettingData.login_verify_realname==1">
            <span class="logaccount-icon"></span>
            <input autocomplete="off" v-model="parentData.alias" placeholder="阁下名字"/>
            <p class="red_color" style="font-size: .9rem;margin-top: .5rem;">注：请输入阁下账户名字，以确保是本人操作！</p>
          </li>
        </ul>
        <div class="login_forget">
          <div class="remember_psw checkbox-item " :class="parentData.remPwdStatus?'checked':''" @click="checkLogAction">
            <span class="icon"></span>
            <span class="text">记住密码</span>
          </div>

          <!--<router-link to="forgetpwd" class="forgot_psw" ><p>忘记密码?</p></router-link>-->
          <a :href="baseSettingData.service_meiqia" class="forgot_psw" target="_blank"><p>忘记密码?</p></a>
        </div>
        <a @click="doLoginAction"  class="zx_submit before_yz" > 确认登录 </a>
        <router-link to="reg" class="zx_submit btn-reg" >立即注册</router-link>

        <!--试玩必须输入手机号的开关：TRUE 跳转到输入手机号的页面，FALSE 直接登入试玩-->
        <router-link v-if="baseSettingData.guest_login_must_input_phone" to="gustlogin"  class="zx_submit reg_btn" > 试玩参观 </router-link>
        <a v-else @click="doLoginAction('try')"  class="zx_submit reg_btn" > 试玩参观 </a>

        <router-link to="appinstallation" class="zx_submit" style="color: #ff0000;margin-top: 10px;"> 安卓APP转账报毒解决方案 </router-link>

      </div>
    </div>

  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import childLogin from '@/components/common/Login'

export default {
  name: 'login',
    mixins:[Mixin],
    components: {
        HeaderNav,
        childLogin
    },
  data () {
    return {
        parentData:[]
    }
  },
    mounted: function () {

    },
    methods:{
        // 从子组件拿值
        getChildValue(data){
          this.parentData = data
        },
        /* 选中与取消选中 */
        checkLogAction: function () {
            this.$refs.child_Login.checkLogAction();
        },
        /* 登录行为 */
        doLoginAction: function (type) {
            this.$refs.child_Login.doLoginAction(type);
        }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .header_nav /deep/ .header_logo {background: none;font-size: 1.6rem;line-height: 2.43rem;font-family: 宋体;}
  .header_nav /deep/ .header-right {display: none;}
  .reg_btn{margin-top:10px;background:#2facea;}
</style>
