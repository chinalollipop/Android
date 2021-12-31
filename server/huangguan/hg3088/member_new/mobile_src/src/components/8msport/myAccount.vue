<template>
  <div >

    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="false" pa_title="" />

    <div class="content-center">

      <!-- -->
      <div class="user_bg">
        <div class="user_bg_top">
          <img src="/static/images/8msport/ucenter/user_ico.png">
          <div class="user_tip">
            <p id="accountcode"> {{userName}} </p>
            <p class="user_join"> 您已加入{{company_name}} <span class="user_join_day">{{joinDays}}天 </span></p>
          </div>
        </div>
        <div class="user_bg_bottom">
          <div class="left user_bg_bottom_left">
            <ul class="account-list">
              <li>
                <router-link class="to-page" to="deposit">
                  <span class="fa-chongzhi">

                  </span>
                  <p>充值</p>
                </router-link>
              </li>
              <li>
                <router-link class="to-page" to="withdraw">
                  <span class="fa-usd">

                  </span>
                  <p>取款</p>
                </router-link>
              </li>
              <li>
                <router-link class="to-page" to="tran">
                  <span class="fa-retweet">

                  </span>
                  <p>转账</p>
                </router-link>
              </li>
              <li>
                <router-link class="to-page" to="promo">
                  <span class="fa-promos">

                  </span>
                  <p>优惠</p>
                </router-link>
              </li>

            </ul>
          </div>
        </div>

      </div>

      <div class="user_gg">
        <a >
          <p class="sx">中心钱包 <span class="icon_refresh" @click="getUserMoney()"></span></p>
          <p>￥<span class="hg_money">{{userMoney}}</span></p>
        </a>
        <router-link to="agentsindex">
          <span class="icon"></span>
        </router-link>
      </div>
      <!-- -->
      <div class="user_bottom">
        <ul class="account-list account-list-sec">
          <li v-for="(lists,item) in menusList" :key="item">
            <router-link :to="lists.path" class="to-page" >
                  <span :class="lists.className">
                    <i v-if="lists.iClassName" :class="lists.iClassName"></i>
                  </span>
              <p>{{lists.name}}</p>
              <i class="account-item"></i>
            </router-link>
          </li>
      </ul>
        <ul class="account-list ">
          <li style="width:100%;height: 4rem;line-height: 4rem;">
            <a class="to-page" style="font-size: 1.3rem;color: rgb(110,110,110)" @click="loginOut">
              退出登录
            </a>
          </li>
        </ul>
        <div class="clear"></div>
      </div>

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
          { name:'充值金额', className:'fa-czje',iClassName:'fa ', path:'deposit'},
          { name:'额度转换', className:'fa-retweet',iClassName:'fa', path:'tran'},
          { name:'银行卡', className:'fa-credit-card',iClassName:'fa', path:'bankcard'},
          { name:'提款', className:'fa-usd',iClassName:'fa', path:'withdraw'},
          { name:'站内信', className:'fa-envelope',iClassName:'fa', path:'moremessage?msg_type=message'},
          { name:'账户中心', className:'fa-address-book',iClassName:'fa', path:'mydetail'},
          { name:'投注记录', className:'fa-life-ring',iClassName:'fa', path:'betrecord'},
          { name:'流水记录', className:'fa-fire',iClassName:'fa', path:'depositrecord'},
          { name:'新手教程', className:'fa-xinshou',iClassName:'fa', path:'help'},
          { name:'联系我们', className:'fa-lxwm',iClassName:'fa', path:'contactus'},
          { name:'皇冠公告', className:'fa-gonggao',iClassName:'fa', path:'moremessage'}

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
  .account-list-sec{padding-bottom: 15px;}
  .account-list li{margin:0 5%;height:4rem;line-height:4rem;border-bottom:1px solid #f0f0f0;font-size:1.1rem;text-align:left}
  .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
  .account-item:after{margin-top:1.5rem;border-right: 2px solid #e6e6e6;border-top:2px solid #e6e6e6}
  .account-list .to-page{display:block;width:100%;height:100%}
  .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
  .user_bg{text-align:center;background:#fff;height:12rem;width:98%;margin:10px auto;border-radius:5px;box-shadow:0 0px 6px rgba(0,0,0,.2);padding:0 5%}
  .user_bg_top{display:-webkit-flex;display:flex}
  .user_bg img{margin:15px 0 4px 0;width:70px;height:70px;float:left}
  .user_bg .user_tip{display:inline-block;margin-left:5%;padding-top:30px;text-align:left}
  .user_bg .user_bg_top p{color:#5ea0ea;font-size:1.3rem;}
  .user_bg .user_bg_top .user_join{color:#acacac;font-size:1rem}
  .account-list li a:nth-child(3n){border-right:none}
  .account-list li a p{color:#333;height:1.07rem;font-size:1.2rem;display:inline-block}
  .account-list li a span{display:inline-block;vertical-align:top;width:3rem;max-width:66px;height:3rem;max-height:55px;background-size: 85%;background-repeat: no-repeat;background-position: center;}
  .account-list li a span:before{content: ''}
  .account-list-sec li a span{margin-top: .2rem}
  .account-list li span i{font-size:1.5rem;margin-top:.6rem}
  .account-list li .fa-retweet{background-image:url(/static/images/8msport/ucenter/icon_edzh.png)}
  .account-list li .fa-chongzhi{background-image:url(/static/images/8msport/ucenter/icon_ck.png)}
  .account-list li .fa-usd{background-image:url(/static/images/8msport/ucenter/icon_qk.png)}
  .account-list li .fa-usd-1{background-image:url(/static/images/8msport/ucenter/icon_tk.png)}
  .account-list li .fa-promos{background-image:url(/static/images/8msport/ucenter/icon_yh.png)}
  .account-list li .fa-czje{background-image:url(/static/images/8msport/ucenter/icon_czje.png)}
  .account-list li .fa-edzh{background-image:url(/static/images/8msport/ucenter/icon_edzh.png)}
  .account-list li .fa-credit-card{background-image:url(/static/images/8msport/ucenter/icon_yhk.png)}
  .account-list li .fa-list{background-image:url(/static/images/8msport/ucenter/icon_ptye.png)}
  .account-list li .fa-gonggao{background-image:url(/static/images/8msport/ucenter/icon_gg.png)}
  .account-list li .fa-envelope{background-image:url(/static/images/8msport/ucenter/icon_znx.png)}
  .account-list li .fa-address-book{background-image:url(/static/images/8msport/ucenter/icon_zhzx.png)}
  .account-list li .fa-life-ring{background-image:url(/static/images/8msport/ucenter/icon_tzjl.png)}
  .account-list li .fa-fire{background-image:url(/static/images/8msport/ucenter/icon_lsjl.png)}
  .account-list li .fa-xinshou{background-image:url(/static/images/8msport/ucenter/icon_sxjc.png)}
  .account-list li .fa-lxwm{background-image:url(/static/images/8msport/ucenter/icon_lxwm.png)}
  .account-list li .fa-app {background-image:url(/static/images/8msport/ucenter/icon_app.png)}
  .user_bg_bottom .user_bg_bottom_left{width:100%;margin-top: .5rem;}
  .user_bg_bottom .account-list{width:100%}
  .user_bg_bottom .account-list li{text-align:center;position:relative;width:25%;border:0;float:left;margin:0}
  .user_bg_bottom .account-list li a p{font-size:1rem;color:#333;display:block;text-align:center;margin-top:-2rem;}
  .user_gg{font-size:1.2rem;display:-webkit-flex;display:flex;background:#fff;border-radius:5px;width:98%;margin:0 auto 10px}
  .user_gg a{color:#000;position:relative;-webkit-flex:1;flex:1;margin:1rem 0 .8rem}
  .user_gg a:first-child{border-right:1px solid #e8e8e8}
  .user_gg a:last-child span{display:inline-block;width:3rem;height:2.5rem;background:url(/static/images/8msport/ucenter/icon_dlhz.png) center -2px no-repeat;background-size:100%}
  .user_gg a:last-child:before{content:'代理加盟';position:absolute;left:50%;top:50%;transform:translate(-50%,10%)}
  .user_gg a p{display:-webkit-flex;display:flex;justify-content:center;line-height:2.3rem;color:#5ea0ea}
  .user_gg a p.sx{color: #000;}
  .user_gg a .icon_refresh{display: inline-block;width: 2.3rem;height: 2.3rem;background:url(/static/images/8msport/ucenter/icon_reload.png) center no-repeat;background-size: 100%;margin-left: 1rem;}
</style>
