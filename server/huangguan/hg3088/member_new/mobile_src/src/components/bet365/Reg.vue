<template>
  <div class="bg_all">
    <HeaderNav pa_showback="true" pa_title="" />
    <childReg ref="child_Reg" @child_data="getChildValue" :P_Data="parentData"></childReg>

    <div class="content-center">
      <div class="member_reg" >

        <div class="login_center">
            <div class="login_form">
              <ul>
                <li>
                  <span class="logaccount-icon">推荐ID</span>
                  <input type="text" name="introducer" v-model="parentData.introducer" value="" minlength="4" maxlength="15" class="za_text" placeholder="没有推荐人可以不填">
                </li>
                <li>
                  <span class="logaccount-icon">用户名</span>
                  <input type="text" name="username" v-model="parentData.username" minlength="5" maxlength="15" class="za_text" placeholder="5-15个英文和数字组成">
                </li>
                <li class="psw_li">
                  <span class="logpwd-icon">密码</span>
                  <span><input :type="parentData.eye1Status?'password':'text'" name="password" v-model="parentData.password"  minlength="6" maxlength="15" class="za_text" placeholder="6-15个任意字符组成"></span>
                </li>
                <li class="psw_li">
                  <span class="logpwd-icon">确认密码</span>
                  <span><input :type="parentData.eye2Status?'password':'text'" name="password2" v-model="parentData.password2" minlength="6" maxlength="15" class="za_text" placeholder="6-15个任意字符组成"></span>
                </li>
                <li v-if="baseSettingData.telOn">
                  <span class="phone-icon">手机号码</span>
                  <input type="text" name="phone" v-model="parentData.phone" minlength="11" maxlength="11" class="za_text" placeholder="11位手机号码">
                </li>
                <p v-if="baseSettingData.telOn" class="red_color">*请认真填写，以便有优惠活动可以及时通知您参与！</p>

                <li v-if="baseSettingData.chatOn">
                  <span class="wechat-icon">微信号码</span>
                  <input type="text" name="wechat" v-model="parentData.wechat" class="za_text" placeholder="微信号">
                </li>

                <li v-if="baseSettingData.qqOn">
                  <span class="qq-icon">QQ号码</span>
                  <input type="text" name="qq" v-model="parentData.qq" class="za_text" placeholder="QQ号">
                </li>
                  <li v-if="baseSettingData.aliasOn">
                      <span class="name-icon">真实姓名</span>
                      <input type="text" name="alias" v-model="parentData.alias" class="za_text" placeholder="真实姓名">
                  </li>

                <li class="site-origin">
                  <div>
                      <span class="text">如何得知本站</span>
                      <select name="know_site" v-model="parentData.know_site">
                          <option value="3" selected>网络广告</option>
                          <option value="2">比分网</option>
                          <option value="1">朋友推荐</option>
                          <option value="4">论坛</option>
                      </select>
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
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import childReg from '@/components/common/Reg'

    export default {
        name: 'reg',
        mixins:[Mixin],
        components: {
          HeaderNav,
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

</style>
