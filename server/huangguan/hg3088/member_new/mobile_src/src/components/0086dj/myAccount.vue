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
      <div class="list_all">
        <!-- 真人列表 -->
        <div v-show="!checkStatus" class="live_list">
          <span class="icon_close" @click="checkAction"></span>
          <a class="ag_live" @click="openNewGame('gameswin?gametype=ag','ag')"></a>
          <a class="bbin_live" @click="openNewGame('gameswin?action=getLaunchGameUrl&gametype=bbin','bbin')"></a>
          <a class="og_live" @click="openNewGame('gameswin?action=getLaunchGameUrl&gametype=og','og')"></a>
        </div>
        <ul class="account-list">
          <li v-for="(lists,item) in menusList" :key="item">
            <a class="to-page" v-if="lists.path=='myaccount'" @click="checkAction">
                  <span :class="lists.className">
                    <i v-if="lists.iClassName" :class="lists.iClassName"></i>
                  </span>
              <p>{{lists.name}}</p>
            </a>
            <router-link :to="lists.path" class="to-page" v-else>
                  <span :class="lists.className">
                    <i v-if="lists.iClassName" :class="lists.iClassName"></i>
                  </span>
              <p>{{lists.name}}</p>
            </router-link>
          </li>
          <li >
            <a class="to-page" @click="loginOut">
              <span>
                <i  class="fa fa-sign-out"></i>
              </span>
              <p>登出</p>
            </a>
          </li>
          <div class="clear"></div>
        </ul>
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
          { name:'体育竞技', className:'',iClassName:'fa fa-sport', path:'sport'},
          { name:'真人视讯', className:'',iClassName:'fa fa-live', path:'myaccount'},
          { name:'真人升级', className:'sj-live',iClassName:'', path:'upgraded?game_Type=live'},
          { name:'体育升级', className:'sj-sport',iClassName:'', path:'upgraded?game_Type=sport'},
          { name:'充值', className:'',iClassName:'fa fa-database', path:'deposit'},
          { name:'额度转换', className:'',iClassName:'fa fa-retweet', path:'tran'},
          { name:'银行卡', className:'',iClassName:'fa fa-credit-card', path:'bankcard'},
          { name:'提现', className:'',iClassName:'fa fa-usd', path:'withdraw'},
          { name:'平台余额', className:'',iClassName:'fa fa-list', path:'platform'},
          { name:'站内信', className:'',iClassName:'fa fa-envelope', path:'moremessage?msg_type=message'},
          { name:'账户中心', className:'',iClassName:'fa fa-address-book', path:'mydetail'},
          { name:'投注记录', className:'',iClassName:'fa fa-life-ring', path:'betrecord'},
          { name:'流水记录', className:'',iClassName:'fa fa-fire', path:'depositrecord'}

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
  .list_all{position: relative;}
  .live_list{position: absolute;width: 90%;max-width:520px;height: 5rem;background: rgba(0,0,0,.5);border-radius: 5px;top: 5rem;left: 50%;margin-left: -45%;-webkit-animation:popAnimation .3s both;animation:popAnimation .3s both;}
  .live_list a {display: inline-block;width: 33.3%;max-width:170px;height: 100%;float: left;background: url(/static/images/0086dj/icon/ag_live.png) center no-repeat;background-size: 100%;}
  .live_list a.bbin_live {background: url(/static/images/0086dj/icon/bbin_live.png) center no-repeat;background-size: 100%;}
  .live_list a.og_live {background: url(/static/images/0086dj/icon/og_live.png) center no-repeat;background-size: 100%;}
  .live_list .icon_close {position: absolute;display: inline-block;width: 1.3rem;height: 1.3rem;background: #fff url(/static/images/apptip/close.png) center no-repeat;background-size: 50%;right: .2rem;top: .2rem;border-radius: 50%;}

</style>
