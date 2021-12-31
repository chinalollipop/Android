<template>
  <div >

    <HeaderNav pa_showback="false" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="user_bg">
        <div class="user_bg_top">
          <div class="left">
            <img src="/static/images/3366/user_ico.png">
            <p id="accountcode">{{userName}}</p>
          </div>
          <span class="right" id="refresh" @click="getUserMoney">刷新</span>
        </div>
        <div class="user_bg_bottom">
          <div class="left user_bg_bottom_left">
            <ul class="account-list left">
              <li v-for="(lists,item) in menusList" :key="item" v-if="item<=3">
                <router-link :to="lists.path" class="to-page">
                  <span :class="lists.iClassName"> </span>
                  <p>{{lists.name}}</p>
                </router-link>
              </li>
            </ul>
          </div>
          <div class="right user_bg_bottom_right">
            <h4 class="hg_money">{{userMoney}}</h4>
            <p> 钱包中心 </p>
          </div>
        </div>

      </div>

      <ul class="account-list">
        <li v-for="(lists,item) in menusList" :key="item" v-if="item>3">
          <a v-if="lists.path=='aglogin'" @click="openNewGame(baseSettingData.agentLoginUrl,'','no')" class="to-page" >
            <span :class="lists.iClassName"> </span>
            <p>{{lists.name}}</p>
          </a>
          <router-link v-else :to="lists.path" class="to-page" >
                <span :class="lists.iClassName"> </span>
            <p>{{lists.name}}</p>
          </router-link>
        </li>
        <div class="clear"></div>
      </ul>

      <ul class="account-list">
        <li style="border: 0">
          <a class="to-page" @click="loginOut">
            <span class="fa-sign-out"> </span>
            <p>登出</p>
          </a>
        </li>
      </ul>

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
      menusList:[
          { name:'充值', className:'',iClassName:'fa-chongzhi', path:'deposit'},
          { name:'额度转换', className:'',iClassName:'fa-retweet', path:'tran'},
          { name:'银行卡', className:'',iClassName:'fa-credit-card', path:'bankcard'},
          { name:'提现', className:'',iClassName:'fa-usd', path:'withdraw'},
          { name:'平台余额', className:'',iClassName:'fa-list', path:'platform'},
          { name:'消息公告', className:'',iClassName:'fa-gonggao', path:'moremessage'},
          { name:'站内信', className:'',iClassName:'fa-envelope', path:'moremessage?msg_type=message'},
          { name:'账户中心', className:'',iClassName:'fa-address-book', path:'mydetail'},
          { name:'投注记录', className:'',iClassName:'fa-life-ring', path:'betrecord'},
          { name:'流水记录', className:'',iClassName:'fa-fire', path:'depositrecord'},
          { name:'新手教学', className:'',iClassName:'fa-xinshou', path:'help'},
          { name:'代理加盟', className:'',iClassName:'fa-agent', path:'agent'},
          { name:'代理登录', className:'',iClassName:'fa-agent-log', path:'aglogin'},
          { name:'联系我们', className:'',iClassName:'fa-lxwm', path:'contactus'},
          { name:'下载APP', className:'',iClassName:'fa-app', path:'appDownload'}

      ]
    }
  },
    created:function(){

    },
    mounted: function () {
        let _self = this ;
        _self.getUserMoney(); // 上线打开

    },
    methods:{


    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .account-list{background:#fff;width:98%;margin:0 auto 10px;border-radius:5px}
  .account-list li{margin:0 5%;height:4rem;line-height:4rem;border-bottom:1px solid #e6e6e6;font-size:1.1rem;text-align:left}
  .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
  .account-item:after{margin-top:1.5rem;border-right: 2px solid #e6e6e6;border-top:2px solid #e6e6e6}
  .account-list .to-page{display:block;width:100%;height:100%}
  .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
  .user_bg{text-align:center;background:#fff;height:12.5rem;width:98%;margin:10px auto;border-radius:5px;box-shadow:0 0px 2px #ffc408;padding:0 5%}
  .user_bg img{margin:15px 0 4px 0;width:70px;float:left}
  .user_bg .user_bg_top p{color:#000;font-size:1.3rem;margin:40px 0 8px 20px;display:inline-block;font-weight:700}
  .user_bg h4{font-size:1.4rem;display:inline-block;vertical-align:top;color:#000}
  .user_bg span{color:#070707;border-radius:20px;padding:3px 15px;margin:35px 0 0 .5rem;display:inline-block;background:linear-gradient(to right,#fce3c1 1%,#f5ca71 100%)}
  .account-list li a:nth-child(3n){border-right:none}
  .account-list li a p{color:#333;margin-top:-3.5rem;font-size:1.2rem;display:inline-block}
  .account-list li a span{display:inline-block;vertical-align:top;width:50px;height:100%;max-height:55px;background:url(/static/images/3366/user_icon.png) no-repeat;background-position:-5px -8px;transform:scale(.7)}
  .account-list li a span:before{content: ''}
  .account-list li span i{font-size:1.5rem;margin-top:.6rem}
  .account-list li .fa-retweet{background-position-y:-72px}
  .account-list li .fa-credit-card{background-position-y:-136px}
  .account-list li .fa-usd{background-position-y:-202px}
  .account-list li .fa-list{background-position-y:-260px}
  .account-list li .fa-gonggao{background-position-y:-326px}
  .account-list li .fa-envelope{background-position-y:-390px}
  .account-list li .fa-address-book{background-position-y:-458px}
  .account-list li .fa-life-ring{background-position-y:-522px}
  .account-list li .fa-fire{background-position-y:-586px}
  .account-list li .fa-xinshou{background-position-y:-652px}
  .account-list li .fa-gytyc{background-position-y:-716px}
  .account-list li .fa-agent{background-position-y:-782px}
  .account-list li .fa-lxwm{background-position-y:-846px}
  .account-list li .fa-sign-out{background-position-y:-911px}
  .account-list li .fa-app {background-position-y: -976px;}
  .user_bg_bottom .user_bg_bottom_left{width:74%}
  .user_bg_bottom .account-list{width:100%}
  .user_bg_bottom .account-list li{position:relative;width:25%;border:0;line-height:5rem;float:left;margin:0}
  .user_bg_bottom .account-list li:nth-child(n+2):after{position:absolute;content:'';display:inline-block;width:1px;height:27px;top:9px;left:0;background:#e6e6e6}
  .user_bg_bottom .account-list li a p,.user_bg_bottom_right p{font-size:1rem;color:#333}
  .user_bg_bottom .account-list li a p{display:block;text-align:center}
  .user_bg_bottom .account-list li a span{margin:0;width: 100%;border-radius: 0;background-position-x:0; }
  .user_bg_bottom_right{max-width:26%;overflow:hidden;text-overflow:ellipsis}
  .account-list li a .fa-agent-log{background:url(/static/images/3366/agent_icon.png) center no-repeat;}
</style>
