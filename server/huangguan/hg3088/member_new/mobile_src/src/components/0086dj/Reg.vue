<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" class=""/>
    <childReg ref="child_Reg" @child_data="getChildValue" :P_Data="parentData"></childReg>

    <div class="content-center">
      <div class="member_reg" >
        <div class="textbox-list">
          <ul>
            <li style="display: none">
              <div>
                <label>
                  <span class="account-icon">介绍人</span>
                </label>
                <span class="textbox">
                    <input type="text" name="introducer" v-model="parentData.introducer" minlength="4" maxlength="15" class="inp-txt" placeholder="没有可不填写">

                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="account-icon"><em class="red_color">*</em> 帐号</span>
                </label>
                <span class="textbox">
                    <input type="text" name="username" v-model="parentData.username" minlength="5" maxlength="15" class="inp-txt" placeholder="会员帐号（5-15位数字或字母）">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon"><em class="red_color">*</em> 密码</span>
                </label>
                <span class="textbox">
                    <input type="password" name="password" v-model="parentData.password"  minlength="6" maxlength="15" class="inp-txt" placeholder="密码（6-15个字符）">
                </span>
              </div>
            </li>
            <li>
              <div>
                <label>
                  <span class="pwd-icon2"><em class="red_color">*</em> 确认密码</span>
                </label>
                <span class="textbox">
                      <input type="password" name="password2" v-model="parentData.password2" minlength="6" maxlength="15" class="inp-txt" placeholder="确认密码（6-15个字符）">
                </span>
              </div>
            </li>

            <li v-if="baseSettingData.telOn">
              <div>
                <label>
                  <span class="text"><em class="red_color">*</em> 手机号码</span>
                </label>
                <span class="textbox">
                    <input type="text" name="phone" v-model="parentData.phone" minlength="11" maxlength="11" class="inp-txt" placeholder="请输入11位手机号码">
                </span>
              </div>
            </li>

            <li v-if="baseSettingData.chatOn">
              <div>
                <label>
                  <span class="text"><em class="red_color">*</em> 微信号码</span>
                </label>
                <span class="textbox">
                    <input type="text" name="wechat" v-model="parentData.wechat" class="inp-txt" placeholder="请输入微信号码">
                </span>
              </div>
            </li>

            <li v-if="baseSettingData.qqOn">
              <div>
                <label>
                  <span class="text"><em class="red_color">*</em> QQ号码</span>
                </label>
                <span class="textbox">
                    <input type="text" name="qq" v-model="parentData.qq" class="inp-txt" placeholder="请输入QQ号码">
                </span>
              </div>
            </li>
            <li v-if="baseSettingData.aliasOn">
              <div>
                <label>
                  <span class="text"><em class="red_color">*</em> 真实姓名</span>
                </label>
                <span class="textbox">
                    <input type="text" name="alias" v-model="parentData.alias" class="inp-txt" placeholder="请输入真实姓名">
                </span>
              </div>
            </li>
            <!--   <li>
                   <div>
                       <label>
                           <span class="pwd-icon2"><em class="red_color">*</em> 验证码</span>
                       </label>
                       <span class="textbox">
                           <input id="verifycode" class="inp-txt" name="verifycode" type="text" tabindex="2"  minlength="4" maxlength="4" placeholder="请输入验证码" >
                           <img title="点击刷新" class="yzm_code" style="position: absolute;right: 4.3%;"  src="/include/validatecode/captcha.php" align="absbottom" onclick="this.src='/include/validatecode/captcha.php?'+Math.random();"/>
                       </span>
                   </div>
               </li>-->
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
            <span class="checkbox-item " :class="checkStatus?'checked':''" @click="checkAction">
                <span class="icon"></span>
                <span class="text">同意本站《协议条款》</span>
            </span>
        </div>
        <div class="btn-wrap">
          <a class="zx_submit before_yz" @click="reqSubmit">立即注册</a>

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
