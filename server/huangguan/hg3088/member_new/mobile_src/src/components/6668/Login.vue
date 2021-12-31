<template>
  <div class="bg_all">

    <childLogin ref="child_Login" @child_data="getChildValue" :P_Data="parentData"></childLogin>

    <div class="content-center" >
      <div class="login_div">
        <div class="login-logo"></div>
        <div class="login_change">
          <a class="active">登录</a>
          <router-link class="to_reg" to="reg">注册</router-link>
        </div>
        <input type=hidden name="demoplay" id="demoplay" value="">
        <div class="login_center">
          <div class="big_div">
            <ul class="login_form">
              <li>
                  <span class="logaccount-icon">
                     <!-- <i class="fa fa-user-circle"></i>-->
                  </span><input autocomplete="off" name="username" type="text" class="za_text" v-model="parentData.username" placeholder="请输入会员帐号" /></li>
              <li class="psw_li">
                  <span class="logpwd-icon">
                      <!--<i class="fa fa-unlock-alt"></i>-->
                  </span><input autocomplete="off" name="passwd" :type="checkStatus?'password':'text'" class="za_text" v-model="parentData.password" maxlength="15" placeholder="密码（6-15个字符）"/>
                <a class="see_psw" :class="checkStatus?'see_psw_close':'see_psw_open'" @click="checkAction"></a>
              </li>
              <li v-if="baseSettingData.login_verify_realname==1">
                <span class="name-icon"></span>
                <input autocomplete="off" v-model="parentData.alias" placeholder="阁下名字"/>
                <p class="red_color" style="font-size: .9rem;margin-top: 1rem;">注：请输入阁下账户名字，以确保是本人操作！</p>
              </li>

            </ul>

            <div class="login_forget">

              <div class="remember_psw">
                <label class="switch checkbox-item" @click="checkLogAction">
                  <input type="checkbox" class="control" :checked="parentData.remPwdStatus?'checked':''">
                  <div class="checkbox"></div>
                </label>
                <span class="text">记住密码</span>
              </div>

              <!--<router-link to="forgetpwd" class="forgot_psw" ><p>忘记密码?</p></router-link>-->
              <a :href="baseSettingData.service_meiqia" class="forgot_psw" target="_blank"><p>忘记密码?</p></a>
            </div>
            <a @click="doLoginAction"  class="zx_submit before_yz" > 登录 </a>

            <div class="login_bottom">
              <!--试玩必须输入手机号的开关：TRUE 跳转到输入手机号的页面，FALSE 直接登入试玩-->
              <router-link v-if="baseSettingData.guest_login_must_input_phone" to="gustlogin"  class="zx_submit try_paly" > 先去逛逛 </router-link>
              <a v-else @click="doLoginAction('try')"  class="zx_submit try_paly" > 先去逛逛 </a>
              <a class="zx_submit to_pc" :href="baseSettingData.pc_url"> 电脑版 </a>
            </div>

            <router-link to="appinstallation" class="zx_submit app_in" style="width: 86%;"> 安卓APP转账报毒解决方案 </router-link>

          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import childLogin from '@/components/common/Login'

export default {
  name: 'login',
    mixins:[Mixin],
    components: {
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

  .login_div .try_paly,.login_div .to_pc,.login_div .app_in{height:40px;line-height:40px;margin-top: 2rem;background: transparent;width: 35%;box-shadow: none;border: 1px solid #f2f2f2;}
  .login_div .to_pc {margin-left: 5%;}
</style>
