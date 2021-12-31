<template>
  <div>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

  </div>
</template>

<script>

    import '../../../static/css/tncode/style.css'
    import tncode from '../../../static/css/tncode/tn_code.js'

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'reg',
        mixins:[Mixin],
        components: {
            Dialog
        },
        props: ['P_Data'], // 父组件给子组件传递值
        data () {
            return {
              childData:[],
              checkRegStatus:true, // 选中状态
              eye1Status:true,
              eye2Status:true,
              introducer:'',
              username :'',
              password :'',
              password2 :'',
              alias :'',
              phone :'',
              wechat :'',
              qq :'',
              know_site :3, // 默认选中
              typePwd: false
            }
        },
        watch: {
          // 父组件数据改变后更新到子组件
          P_Data(newVal, oldVal){
            this.childData = newVal;
          }
        },
        mounted: function () {
          let _self = this ;
          // 代理推广码
          _self.introducer = localStorage.getItem('agent_account');

          _self.childData={
            checkRegStatus:_self.checkRegStatus,
            eye1Status:_self.eye1Status,
            eye2Status :_self.eye2Status,
            introducer :_self.introducer,
            know_site :_self.know_site,
            username :_self.username,
            password :_self.password,
            password2 :_self.password2,
            phone :_self.phone,
            wechat :_self.wechat,
            qq :_self.qq,
            alias :_self.alias
          }

          _self.$emit('child_data',_self.childData);    // 直接向父组件传值 $emit

          _self.clearUserData();

          _self.$nextTick(()=>{
              // 初始化验证码
              if(_self.baseSettingData.code_open_switch){ // 已开启验证码
                  tncode.tncode.init();
              }
          });

        },
        methods:{
            /* 同意注册条款 */
          checRegkAction: function () {
              let _self = this;
              if(_self.checkRegStatus){
                _self.checkRegStatus = false;
              }else{
                _self.checkRegStatus = true;
              }
              _self.childData.checkRegStatus = _self.checkRegStatus;
              _self.$emit('child_data',_self.childData);    // 直接向父组件传值 $emit
            },
          /* 密码可见 */
            checkSeeAction:function (type) {
                let _self = this;
                if(type=='two'){
                    if(_self.eye2Status){
                        _self.eye2Status = false;
                    }else{
                        _self.eye2Status = true;
                    }
                }else{
                    if(_self.eye1Status){
                        _self.eye1Status = false;
                    }else{
                        _self.eye1Status = true;
                    }
                }
              _self.childData.eye1Status = _self.eye1Status;
              _self.childData.eye2Status = _self.eye2Status;
              _self.$emit('child_data',_self.childData);    // 直接向父组件传值 $emit

            },
          /* 注册行为 */
            reqSubmit: function () {
                let _self =this;
                let $TNCODE = tncode.tncode;

                _self.eye1Status = _self.childData.eye1Status;
                _self.eye2Status = _self.childData.eye2Status;
                _self.introducer = _self.childData.introducer;
                _self.know_site = _self.childData.know_site;
                _self.username = _self.childData.username;
                _self.password = _self.childData.password;
                _self.password2 = _self.childData.password2;
                _self.phone = _self.childData.phone;
                _self.wechat = _self.childData.wechat;
                _self.qq = _self.childData.qq;
                _self.alias = _self.childData.alias;

                if(!_self.checkRegStatus){
                    _self.$refs.autoDialog.setPublicPop('请勾选同意本站协议') ;
                    return false ;
                }
                if(_self.username ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入帐号') ;
                    return false ;
                }
                if(_self.password ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入密码') ;
                    return false ;
                }
                if(_self.password !=_self.password2){
                    _self.$refs.autoDialog.setPublicPop('密码与确认密码不一致') ;
                    return false ;
                }
                if(_self.baseSettingData.telOn && _self.phone ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入手机号码') ;
                    return false ;
                }
                if(_self.baseSettingData.chatOn && _self.wechat ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入微信号码') ;
                    return false ;
                }
                if(_self.baseSettingData.qqOn && _self.qq ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入QQ号码') ;
                    return false ;
                }
                if(_self.baseSettingData.aliasOn && _self.alias ==''){
                    _self.$refs.autoDialog.setPublicPop('请输入真实姓名') ;
                    return false ;
                }

                let senddata = {
                    introducer: _self.introducer,
                    keys: 'add',
                    username: _self.username,
                    password: _self.password,
                    password2: _self.password2,
                    alias:_self.alias,
                    // paypassword:_self.paypassword,
                    phone: _self.phone,
                    wechat: _self.wechat,
                    qq: _self.qq,
                    // birthday:_self.birthday,
                    // country:_self.country,
                    know_site: _self.know_site,
                    verifycode: Math.random()
                };

                if(_self.baseSettingData.code_open_switch) { // 已开启验证码
                    $TNCODE.show();
                    $TNCODE.onsuccess(function () {
                        _self.regSendAction(senddata);
                    })
                }else{
                    _self.regSendAction(senddata);
                }

            },
          /* 注册请求 */
            regSendAction:function (pars) {
                let _self = this;
                if(_self.submitflag){
                    return false ;
                }

                _self.submitflag = true;
                _self.axios({
                    method: 'post',
                    params: pars,
                    url: _self.ajaxUrl.reg
                }).then(res=>{
                    if(res){
                        _self.submitflag = false;
                        let rest = res.data;
                        if(_self.baseSettingData.code_open_switch) { // 已开启验证码
                            tncode.tncode.init();
                        }
                        if (rest.describe) {
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }
                        if (rest.status == '200') { // 登录成功
                            _self.localStorageSet('member_money', rest.data.Money); // 用户金额

                            _self.localStorageSet('userData',rest.data) ; // 保存数据,1 天有效期
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
                    console.log('会员注册失败');
                });
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
