<template>
  <div >
    <Dialog ref="autoDialog" pa_dialogtitle="" />

  </div>
</template>

<script>

import '../../../static/css/tncode/style.css'
import tncode from '../../../static/css/tncode/tn_code.js'

//import axios from 'axios'
import Mixin from '@/Mixin'
import Dialog from '@/components/Dialog'

// 引入base64
const Base64 = require('js-base64').Base64
export default {
  name: 'login',
    mixins:[Mixin],
    components: {
        Dialog
    },
  props: ['P_Data'], // 父组件给子组件传递值
  data () {
    return {
        childData:[],
        remPwdStatus:false,
        username :'',
        password :'',
        alias :'',
        type:'',
        url_username:'',
        url_password:'',
        agenttip:''
    }
  },
    watch: {
      // 父组件数据改变后更新到子组件
      P_Data(newVal, oldVal){
        this.childData = newVal;
      }
    },
    beforeDestroy(){
        global.Base64 = null; // 销毁变量，避免造成内存泄漏
        tncode.tncode.hide();
    },
    mounted: function () {
        let _self = this ;

        _self.clearUserData();
        _self.getRememberMe();

        _self.$nextTick(()=>{ // 要不判断的彩票节点取不到
            // 初始化验证码
            if(_self.baseSettingData.code_open_switch){ // 已开启验证码
              tncode.tncode.init();
            }
        });

        _self.type = _self.$route.query.type?_self.$route.query.type:''; // 获取参数,试玩提交手机号后
        _self.agenttip = _self.$route.query.agenttip?_self.$route.query.agenttip:''; // 获取参数,代理域登录
        _self.url_username = _self.$route.query.username?_self.$route.query.username:'';
        _self.url_password = _self.$route.query.passwd?_self.$route.query.passwd:'';

        _self.childData={
          remPwdStatus:_self.remPwdStatus,
          username :_self.username,
          password :_self.password,
          alias :_self.alias
        }

      _self.$emit('child_data',_self.childData);    // 直接向父组件传值 $emit

        if( _self.agenttip=='1'){
            _self.childData.username =_self.url_username;
            _self.childData.password = _self.url_password;
            _self.doLoginAction();
        }
        if(_self.type=='gustlogin'){
            _self.doLoginAction('try');
        }

    },
    methods:{
      /* 记住密码填充 */
        getRememberMe:function () {
            // 在页面加载时从cookie获取登录信息
            let account = localStorage.getItem("accountInfo");
            // 如果存在赋值给表单，并且将记住密码勾选
            if(account){
                let infoArr = account.split('&');
                this.username = infoArr[0];
                this.password = Base64.decode(infoArr[1]);
                this.remPwdStatus = true
            }
        },
      /* 记住密码 */
        rememberMe:function () {
            let account = this.username+'&'+Base64.encode(this.password);
            if(this.remPwdStatus){
                localStorage.setItem("accountInfo",account)
            }else{
                localStorage.removeItem("accountInfo");
            }
        },
      /* 选中与取消选中 */
        checkLogAction: function () {
          let _self = this;
            if(this.remPwdStatus){
              _self.remPwdStatus = false;
            }else{
              _self.remPwdStatus = true;
            }
            _self.childData.remPwdStatus = _self.remPwdStatus;
            _self.$emit('child_data',_self.childData);    // 更新父组件值
        },
      /* 登录行为 */
      doLoginAction: function (type) {
          let _self =this;
          let $TNCODE = tncode.tncode;
          let demoplay = '';

          if(type=='try'){
              demoplay = 'Yes';
              _self.username = "demoguest";
              _self.password = "nicainicainicaicaicaicai";
          }else{ // 正式会员登录
            _self.username = _self.childData.username;
            _self.password = _self.childData.password;
            _self.alias = _self.childData.alias;
          }
          if(_self.username ==''){
              _self.$refs.autoDialog.setPublicPop('请输入帐号') ;
              return false ;
          }
          if(_self.password ==''){
              _self.$refs.autoDialog.setPublicPop('请输入密码') ;
              return false ;
          }
          if(_self.baseSettingData.login_verify_realname==1){
            if(_self.alias==''){
              _self.$refs.autoDialog.setPublicPop('请输入真实姓名') ;
              return false ;
            }
          }

          let senddata = {
              demoplay: demoplay,
              username: _self.username ,
              passwd: _self.password,
              realname: _self.alias,
              agenttip: _self.agenttip,
              yzm_input: Math.random()
          };

          if(_self.baseSettingData.code_open_switch) { // 已开启验证码
              $TNCODE.show();
              $TNCODE.onsuccess(function () {
                  _self.loginLast(senddata);
              })
          }else{
              _self.loginLast(senddata);
          }
      },
      loginLast: function (pars) {
          let _self = this;
          if(_self.submitflag){
              return false ;
          }
          _self.submitflag = true;
            _self.axios({
                method: 'post',
                // withCredentials:true, // 表示请求可以携带cookie
                params: pars,
                url: _self.ajaxUrl.login
            }).then(res=>{
                if(res){
                    _self.submitflag = false;
                    let rest = res.data;
                    if (rest.describe) {
                        _self.$refs.autoDialog.setPublicPop(rest.describe);
                    }
                    if(_self.baseSettingData.code_open_switch) { // 已开启验证码
                        tncode.tncode.init();
                    }
                    if (rest.status == '200') { // 登录成功
                        _self.rememberMe();
                        _self.localStorageSet('member_money', rest.data.Money); // 用户金额

                        _self.localStorageSet('userData',rest.data) ; // 保存数据,0.5天有效期
                        _self.loginLotteryAction();

                    } else if (rest.status == '300.1') {
                        window.location.href = rest.data.agentchangeurl;
                    }
                }
            }).catch(res=>{
              _self.submitflag = false;
                if(_self.baseSettingData.code_open_switch) { // 已开启验证码
                    tncode.tncode.init();
                }
              console.log('登录失败');
        });
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
