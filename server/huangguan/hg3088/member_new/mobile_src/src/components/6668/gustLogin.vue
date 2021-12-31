<template>
  <div class="bg_all">
    <HeaderNav pa_showback="" pa_title="" class=""/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="member_reg" >
          <div class="login-logo"></div>
          <div class="login_center">
              <div class="big_div ">
                  <div class="login_form">
                      <ul>
                          <li >
                              <span class="phone-icon"></span>
                              <input type="text" name="phone" v-model="phone" minlength="11" maxlength="11" class="za_text" placeholder="* 手机号">
                          </li>
                          <li>
                              <span class="logpwd-icon"></span>
                              <span >
                                <input v-model="verifycode" class="inp-txt" name="verifycode" type="text" minlength="4" maxlength="4" placeholder="* 请输入验证码" >
                                <img title="点击刷新" class="yzm_code" style="position: absolute;right: 4.3%;" :src="ajaxUrl.captcha" align="absbottom" @click="updateCaptcha($event)"/>
                            </span>
                          </li>

                      </ul>
                  </div>


                  <div class="btn-wrap">
                      <a class="zx_submit before_yz" @click="reqSubmit">提交</a>

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
    import HeaderNav from '@/components/Header'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'gustlogin',
        mixins:[Mixin],
        components: {
            HeaderNav,
            Dialog
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

</style>
