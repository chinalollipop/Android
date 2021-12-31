<template>
  <div >

    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="user_bg">
        <div class="user_top header">
          <div class="header-right"><i class="fa fa-database"></i><p class="hg_money after_login">{{userMoney}}</p></div>
        </div>
        <div class="user_top_name ">
          <img src="/static/images/6668/user_ico.png">
          <div class="user_tip">
            <p class="accountcode">
              {{userName}}
              <span id="refresh" @click="getUserMoney()">刷新</span>
            </p>

            <p class="user_join"> 您已加入{{company_name}} <span class="user_join_day"> {{joinDays}}天</span></p>
          </div>
        </div>
      </div>
      <div class="user_center_bg">
        <div class="user_ye">
          <p>中心钱包：</p>
          <p class="hg_money">{{userMoney}}</p>
        </div>
        <div class="financial">
          <router-link to="deposit">
            <i class="qbzx_fa fa-deposit-card"></i>
            <span>存款</span>
          </router-link>
          <router-link to="withdraw">
            <i class="qbzx_fa fa-withdrow"></i>
            <span>取款</span>
          </router-link>
          <router-link to="tran">
            <i class="qbzx_fa fa-zz"></i>
            <span>转账</span>
          </router-link>
          <router-link to="promo">
            <i class="qbzx_fa fa-promos"></i>
            <span>优惠活动</span>
          </router-link>
        </div>
      </div>
      <div class="user_bottom">
        <div class="user_gg">
          <router-link to="promo"></router-link>
          <router-link to="agent"></router-link>
        </div>
        <p class="my_gm"> 我的功能 </p>
        <ul class="account-list">
          <li v-for="(lists,item) in menusList" :key="item">
            <router-link :to="lists.path" class="to-page" >
                  <span :class="lists.className">
                    <i v-if="lists.iClassName" :class="lists.iClassName"></i>
                  </span>
              <p>{{lists.name}}</p>
            </router-link>
          </li>
      </ul>
        <ul class="account-list" style="margin-top: .5rem;">
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
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
  name: 'myaccount',
    mixins:[Mixin],
    components: {
        FooterNav,
        Dialog
    },
  data () {
    return {
      menusList:[
          {name:'充值金额', className:'fa-database',iClassName:'fa', path:'deposit'},
          {name:'额度转换', className:'fa-tran',iClassName:'fa', path:'tran'},
          {name:'银行卡', className:'fa-card',iClassName:'fa', path:'bankcard'},
          {name:'提款', className:'fa-tk',iClassName:'fa', path:'withdraw'},
          {name:'平台余额', className:'fa-plat',iClassName:'fa', path:'platform'},
          {name:'站内信', className:'fa-znx',iClassName:'fa', path:'moremessage?msg_type=message'},
          {name:'账户中心', className:'fa-address-book',iClassName:'fa', path:'mydetail'},
          {name:'投注记录', className:'fa-bet',iClassName:'fa', path:'betrecord'},
          {name:'流水记录', className:'fa-fire',iClassName:'fa', path:'depositrecord'},
          {name:'新手教程', className:'fa-xinshou',iClassName:'fa', path:'help'},
          {name:'联系我们', className:'fa-lxwm',iClassName:'fa', path:'contactus'},
          {name:'代理加盟', className:'fa-agent',iClassName:'fa', path:'agent'},
          {name:'皇冠公告', className:'fa-gonggao',iClassName:'fa', path:'moremessage'}
      ]
    }
  },
    created:function(){

    },
    mounted: function () {
        let _self = this ;
        _self.getUserMoney();

    },
    methods:{


    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .header{background: none;height: 3rem;line-height: 3rem;}
  /* 会员中心 */
  .header-right .after_login{line-height: 3rem;}
  .account-list{ background: #fff;overflow: hidden;border-radius: 5px; }
  .account-list li{height: 5.6rem;line-height: 5.2rem;font-size:1.1rem;width: 25%;float: left;}
  .account-list label{color:#666;width:25%;display:inline-block;margin-right:.8rem}
  .account-list .to-page{ display: block;width: 100%;height: 100%;}
  .login-out a{display:block;height:3.928rem;line-height:3.928rem;margin:2.85rem 0 .9rem;background:#e64545;font-size:1.3rem;color:#fff}
  .accountcode span{background:#cbc8c9;padding:1px 15px;border-radius:5px}
  .user_top_name{margin-left:3%;text-align:left}
  .user_tip{display:inline-block;margin-left:2%}
  .user_bg{background:url(/static/images/6668/user_top_bg.png) no-repeat center top;height:12rem;width:100%;background-size:cover}
  .user_bg img{float:left;margin:0 0 4px 0;width:80px}
  .user_bg p{color:#fff;font-size:1em;line-height:1.5rem}
  .user_bg p span{color:#fff}
  .user_bg .user_join{color:rgb(161,199,251);font-size:1.1rem}
  .user_bg .user_join_jd{display:inline-block;width:70%;height:10px;background:#3f64d8;border-radius:10px}
  .user_center_bg{position:absolute;width:94%;height:10rem;background:url(/static/images/6668/user_center_bg.png) no-repeat center;background-size:100%;margin-top:-2rem;left:50%;margin-left:-47%}
  .user_center_bg .user_ye{height:5.5rem;color:#000;width:92%;margin:0 auto;text-align:left;padding:1.5rem 0 .8rem;font-size:1.2rem;border-bottom:1px dashed #e8e8e8}
  .user_center_bg .financial{margin-top:1rem;padding:0 3%}
  .financial i.fa-promos{background-position:-262px -3px;}
  .user_bottom{width:94%;margin:8rem auto 0}
  .user_bottom .user_gg a{display:inline-block;width:48%;height:6rem;border-radius:20px}
  .user_bottom .user_gg a:first-child{background: url(/static/images/6668/user_gg_img.jpg) no-repeat center ;background-size: 100%;margin-right: 2%;}
  .user_bottom .user_gg a:last-child{background: url(/static/images/6668/user_dl_img.jpg) no-repeat center ;background-size: 100%;}
  .user_bottom .my_gm{background:#fff;color:#000;font-weight:bold;font-size:1.2rem;height:2.7rem;line-height:2.7rem;text-align:left;padding-left:1.5rem;border-radius:5px;margin-bottom:.5rem}
  .user_bottom .my_gm:before{position:absolute;display:inline-block;content:'';width:5px;height:1.8rem;background:#008bfb;border-radius:8px;margin:0.4rem -.8rem}
  .account-list li a:nth-child(3n){border-right: none;}
  .account-list li a p{color: #666;height: 1.07rem;margin-top: -3rem;}
  .account-list li a span{display: inline-block;vertical-align: top;width: 48px;height: 50px;margin: 9px 0 9px 0;border-radius:50%;line-height: 32px;background: url(/static/images/6668/user_icon.png) no-repeat;transform: scale(.7);}
  .account-list li span i{font-size: 1.5rem;margin-top: .6rem;}
  .account-list li span.fa-database{background-position: 0 0;}
  .account-list li span.fa-tran{background-position: -52px 0;}
  .account-list li span.fa-card{background-position: -110px 0;}
  .account-list li span.fa-tk{background-position: -166px 0;}
  .account-list li span.fa-plat{background-position: -656px 0;}
  .account-list li span.fa-znx{background-position: -218px 0;}
  .account-list li span.fa-address-book{background-position: -276px 0;}
  .account-list li span.fa-bet{background-position: -332px 0;}
  .account-list li span.fa-fire{background-position: -384px 0;}
  .account-list li span.fa-xinshou{background-position: -438px 0;}
  .account-list li span.fa-lxwm{background-position: -494px 0;}
  .account-list li span.fa-agent{background-position: -548px 0;}
  .account-list li span.fa-gonggao{background-position: -600px 0;}
</style>
