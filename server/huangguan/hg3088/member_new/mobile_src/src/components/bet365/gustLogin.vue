<template>
  <div class="bg_all">
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="true" pa_title="" />

    <div class="content-center">
      <div class="member_reg" >

              <div class="login_center">
                  <ul class="login_form">
                      <li>
                          <span class="phone-icon">手机号码</span>
                          <input type="text" name="phone" v-model="phone" minlength="11" maxlength="11" class="za_text" placeholder="以便有优惠活动可以及时通知您参与！">
                      </li>
                      <li style="position: relative;">
                          <span class="logpwd-icon">验证码</span>

                          <input v-model="verifycode" class="za_text" name="verifycode" type="text" tabindex="2"  minlength="4" maxlength="4" placeholder="请输入验证码" >
                          <img title="点击刷新" class="yzm_code" :src="ajaxUrl.captcha" align="absbottom" @click="updateCaptcha($event)"/>
                      </li>
                  </ul>
              </div>

              <div class="btn-wrap">
                  <a class="zx_submit" @click="reqSubmit">提交</a>

              </div>
      </div>
    </div>

  </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import Dialog from '@/components/Dialog'
    import HeaderNav from '@/components/Header'

    export default {
        name: 'gustlogin',
        mixins:[Mixin],
        components: {
            Dialog,
            HeaderNav
        },
        data () {
            return {
                verifycode:'',
                phone :''
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

                if(_self.phone ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入手机号码') ;
                    return false ;
                }

                let senddata = {
                    phone: _self.phone,
                    verifycode:_self.verifycode
                };

                _self.submitflag = true;
                _self.axios({
                    method: 'post',
                    params: senddata,
                    url: _self.ajaxUrl.gustlogin
                }).then(res=>{
                    if(res){
                        _self.submitflag = false;
                        let rest = res.data;
                        if (rest.status == '200') { // 登录成功
                            _self.$router.push('login?type=gustlogin');
                        }else { // 登录失败
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }
                    }
                }).catch(res=>{
                    _self.submitflag = false;
                    console.log('试玩登录失败');
                });

            },
            // 更新验证码
            updateCaptcha:function (e) {
                let _self = this;
                e.currentTarget.src = _self.ajaxUrl.captcha;
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .yzm_code{position: absolute;right: 4.3%;top: .4rem;}
    .login_form li p {font-size: 0.7rem;margin: 8px 0;}
</style>
