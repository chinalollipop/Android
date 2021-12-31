<template>
  <div >

    <HeaderNav pa_showback="false" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="user_bg">
        <img src="/static/images/user_ico.png">
        <p id="accountcode">{{userName}}</p>
        <h4 class="hg_money">{{userMoney}}</h4>
        <span id="refresh" @click="getUserMoney()">刷新</span>
      </div>
      <ul class="account-list">
        <li v-for="(lists,item) in menusList" :key="item">
          <a v-if="lists.path=='aglogin'" @click="openNewGame(baseSettingData.agentLoginUrl,'','no')" class="to-page" >
            <span :class="lists.iClassName"> </span>
            <p>{{lists.name}}</p>
          </a>
          <router-link v-else :to="lists.path" class="to-page" >
                <span :class="lists.iClassName"> </span>
            <p>{{lists.name}}</p>
          </router-link>
        </li>
        <li >
          <a class="to-page" @click="loginOut">
            <span class="fa-sign-out"> </span>
            <p>登出</p>
          </a>
        </li>
        <div class="clear"></div>
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
          { name:'充值', className:'',iClassName:'fa-database', path:'deposit'},
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
          /*{ name:'太阳城风采', className:'',iClassName:'fa-gytyc', path:'aboutus'},*/
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
  .account-list{ background: #fff; }
  .account-list li{/* padding:0 4%; */height: 5.6rem;line-height: 5.6rem;border-bottom: 1px solid #e6e6e6;border-right: 1px solid #e6e6e6;font-size:1.1rem;width: 33.3%;float: left;}
  .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
  .account-item:after{ right: 6%; }
  .account-list .to-page{ display: block;width: 100%;height: 100%;}
  .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
  .user_bg{text-align: center;background: url(/static/images/wnsr/user_bg.png) no-repeat center top;height: 11.42rem;width: 100%;background-size: cover;}
  .user_bg img{margin: 20px 0 4px 0;width: 70px;}
  .user_bg p{color: #ffffff;font-size: 1em;margin: 0 0 8px 0;}
  .user_bg h4{font-size: 2rem;display: inline-block;vertical-align: top;}
  .user_bg span{color: #070707;font-size: .8rem;border-radius: 20px;padding: 2px 10px;margin: .4rem 0 0 .5rem;display: inline-block;background: linear-gradient(to right, #fce3c1 1%, #f5ca71 100%);}
  .account-list li a:nth-child(3n){border-right: none;}
  .account-list li a p{color: #666666;height: 3rem;line-height:2rem;margin-top: -2rem;font-size: 1.2rem;}
  .account-list li a span{display: inline-block;vertical-align: top;width: 50px;height: 44px;margin: 9px 0 0 0;line-height: 32px;background: url(/static/images/wnsr/user_icon.png) no-repeat;background-position: -5px -8px;transform: scale(.7);}
  .account-list li a span:before{content: ''}
  .account-list li span i{font-size: 1.5rem;margin-top: .6rem;}
  .account-list li .fa-retweet{background-position-y: -72px;}
  .account-list li .fa-credit-card{background-position-y: -142px;}
  .account-list li .fa-usd{background-position-y: -212px;}
  .account-list li .fa-list{background-position-y: -266px;}
  .account-list li .fa-gonggao{background-position-y: -332px;}
  .account-list li .fa-envelope{background-position-y: -399px;}
  .account-list li .fa-address-book{background-position-y: -464px;}
  .account-list li .fa-life-ring{background-position-y: -528px;}
  .account-list li .fa-fire{background-position-y: -592px;}
  .account-list li .fa-xinshou{background-position-y: -658px;}
  .account-list li .fa-gytyc{background-position-y: -722px;}
  .account-list li .fa-agent{background-position-y: -788px;}
  .account-list li .fa-lxwm{background-position-y: -852px;}
  .account-list li .fa-sign-out{background-position-y: -918px;}
  .account-list li .fa-app {background-position-y: -982px;}
  .account-list li a .fa-agent-log{background:url(/static/images/wnsr/agent_icon.png) center no-repeat;}
</style>
