<template>
  <div class="bg_all">
    <childReg ref="child_Reg" @child_data="getChildValue" :P_Data="parentData"></childReg>

    <div class="content-center">
      <div class="member_reg" >
        <div class="login-logo"></div>
        <div class="login_center">
          <div class="small_div left">
            <router-link class="to_reg" to="login">
              <span class="reg_icon"></span>
              返回登录
            </router-link>
          </div>
          <div class="big_div right">
            <p class="title">注 册</p>
            <div class="login_form">
              <ul>
                <li style="display: none">
                  <span class="logaccount-icon"></span>
                  <input type="text" name="introducer" v-model="parentData.introducer" minlength="4" maxlength="15" class="za_text" placeholder="介绍人(没有可不填写)">
                </li>
                <li>
                  <span class="logaccount-icon"></span>
                  <input type="text" name="username" v-model="parentData.username" minlength="5" maxlength="15" class="za_text" placeholder="账号">
                </li>
                <li class="psw_li">
                  <span class="logpwd-icon"></span>
                  <span><input :type="parentData.eye1Status?'password':'text'" name="password" v-model="parentData.password"  minlength="6" maxlength="15" class="za_text" placeholder="密码"></span>
                  <a class="see_psw" :class="parentData.eye1Status?'see_psw_close':'see_psw_open'" @click="checkSeeAction"></a>
                </li>
                <li class="psw_li">
                  <span class="logpwd-icon"></span>
                  <span><input :type="parentData.eye2Status?'password':'text'" name="password2" v-model="parentData.password2" minlength="6" maxlength="15" class="za_text" placeholder="确认密码"></span>
                  <a class="see_psw" :class="parentData.eye2Status?'see_psw_close':'see_psw_open'" @click="checkSeeAction('two')"></a>
                </li>
                <li v-if="baseSettingData.telOn">
                  <span class="phone-icon"></span>
                  <input type="text" name="phone" v-model="parentData.phone" minlength="11" maxlength="11" class="za_text" placeholder="手机号">
                </li>
                <li v-if="baseSettingData.chatOn">
                  <span class="wechat-icon"></span>
                  <input type="text" name="wechat" v-model="parentData.wechat" class="za_text" placeholder="微信号">
                </li>

                <li v-if="baseSettingData.qqOn">
                  <span class="qq-icon"></span>
                  <input type="text" name="qq" v-model="parentData.qq" class="za_text" placeholder="QQ号">
                </li>
                <li v-if="baseSettingData.aliasOn">
                  <span class="name-icon"></span>
                  <input type="text" name="alias" v-model="parentData.alias" class="za_text" placeholder="真实姓名">
                </li>


                <li class="site-origin">
                  <div>
                    <label>
                      <span class="text">如何得知本站</span>
                    </label>
                    <span class="textbox">
                      <select name="know_site" v-model="parentData.know_site">
                          <option value="3" selected>网络广告</option>
                          <option value="2">比分网</option>
                          <option value="1">朋友推荐</option>
                          <option value="4">论坛</option>
                      </select>
                  </span>
                  </div>
                </li>
              </ul>
            </div>
            <div class="agree-div">
            <span class="checkbox-item " :class="parentData.checkRegStatus?'checked':''" @click="checRegkAction">
                <span class="icon"></span>
                <span class="text">同意本站<span class="agreeText">《协议条款》</span></span>
            </span>
            </div>
            <div class="btn-wrap">
              <a class="zx_submit before_yz" @click="reqSubmit">立即注册</a>

            </div>

          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import childReg from '@/components/common/Reg'

    export default {
        name: 'reg',
        mixins:[Mixin],
        components: {
          childReg
        },
        data () {
            return {
              parentData:[]
            }
        },
        mounted: function () {

        },
        methods:{
          /* 同意条款 */
          checRegkAction:function () {
            this.$refs.child_Reg.checRegkAction();
          },
          /* 密码可见 */
          checkSeeAction:function (type) {
            this.$refs.child_Reg.checkSeeAction(type);
          },
          // 从子组件拿值
          getChildValue(data){
            this.parentData = data
          },
          /* 注册行为 */
          reqSubmit: function () {
            this.$refs.child_Reg.reqSubmit();
          }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .site-origin label {float: left;margin-top: .6rem;}
</style>
