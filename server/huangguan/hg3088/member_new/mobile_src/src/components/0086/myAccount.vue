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
          <router-link :to="lists.path" class="to-page" >
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

</style>
