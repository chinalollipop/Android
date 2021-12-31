<template>
  <div class="home_container">
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="false" pa_title="" />

    <div class="content-center">
      <!-- 轮播 -->
      <indexBanner ref="indexBanner"/>

      <!-- 滚动公告 -->
      <div class="notice index_notice" >
        <div class="notice-cont">
           <span class="notice-icon">
               <i class="fa fa-volume-down"></i>
           </span>
           <div class="text">
             <marquee> {{noticeMsg}} </marquee>
           </div>

        </div>
      </div>

      <div id="content">
        <template v-for="(lists,item) in gamesList">
          <div class="Menual" :key="item" v-if="lists.name=='电脑版'">
            <a :href="baseSettingData.pc_url" ><span :class="lists.className"></span>{{lists.name}}</a>
          </div>
          <div class="Menual" :key="item" v-else-if="gametypeList.indexOf(lists.type)>=0">
            <a @click="openNewGame(lists.path,lists.type)" ><span :class="lists.className"></span>{{lists.name}}</a>
          </div>
          <div class="Menual" :key="item" v-else>
            <router-link :to="lists.path" ><span :class="lists.className"></span>{{lists.name}}</router-link>
          </div>
        </template>

        <div class="clear"></div>
      </div>
      <!-- 新年红包 -->
      <div v-if="baseSettingData.redPocketOpen" class="new_year_con" style="cursor:pointer;position:fixed;bottom:0;right:0;z-index:20;width: 140px;height: 247px;background: url(/static/images/hongbao/newy_btn.png) no-repeat;background-size: 100%;">
        <a class="close_new_year" onclick="$(this).parent().hide()" style="display: block; position: absolute; width: 40px; height: 40px; right: 0; top: -2px;"></a>
        <router-link class="to_promos" to="/promo?prokey=newyear_hb" style="display: block;height: 80%;width: 100%;margin-top: 30px;"></router-link>
        <div class="new_year_time" style="transform: scale(.8);font-size:12px;text-align:center;width: 100%;height: 40px;line-height:40px;position: absolute;left: -3px;bottom: 64px;color: #c30202;">红包活动开启中</div>
      </div>

    </div>

    <FooterNav />
  </div>
</template>

<script>

import Mixin from '@/Mixin'
import Dialog from '@/components/Dialog'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import indexBanner from '@/components/common/indexBanner'

export default {
  name: 'index',
    mixins:[Mixin],
    components: {
        Dialog,
        HeaderNav,
        FooterNav,
        indexBanner
    },
  data () {
    return {
      noticeMsg:'欢迎光临',
      gamesList:[
          { name:'体育投注', className:'game-sport-logo', type:'hg',path:'sport'},
          { name:'泛亚电竞', className:'game-fydj-logo', type:'avia',path:'gameswin?action=getLaunchGameUrl&gametype=avia'},
          { name:'雷火电竞', className:'game-lhdj-logo',type:'fire', path:'gameswin?action=getLaunchGameUrl&gametype=fire'},
          { name:'OG视讯', className:'game-live-og-logo', type:'og',path:'gameswin?action=getLaunchGameUrl&gametype=og'},
          { name:'AG视讯', className:'game-live-ag-logo', type:'ag',path:'gameswin?gametype=ag'},
          { name:'BBIN视讯', className:'game-live-bbin-logo', type:'bbin',path:'gameswin?action=getLaunchGameUrl&gametype=bbin'},
          { name:'彩票游戏', className:'game-lottery-logo', type:'cp',path:'gameswin?gametype=cp'},
          { name:'VG棋牌', className:'game-chess-logo-vg', type:'vg',path:'gameswin?action=cm&gametype=vg'},
          { name:'乐游棋牌', className:'game-chess-logo-ly', type:'ly',path:'gameswin?action=cm&gametype=ly'},
          { name:'开元棋牌', className:'game-chess-logo-ky', type:'ky',path:'gameswin?action=cm&gametype=ky'},
          { name:'快乐棋牌', className:'game-chess-logo-kl', type:'kl',path:'gameswin?action=cm&gametype=kl'},
          { name:'电子游戏', className:'game-game-logo', type:'game',path:'/listgames'},
          { name:'捕鱼王', className:'game-by-logo', type:'agby',path:'gameswin?game_id=6&gametype=agby'},
          { name:'优惠活动', className:'game-promo-logo', type:'yh',path:'promo'},
          { name:'代理加盟', className:'game-dljm-logo', type:'dljm',path:'agent'},
          { name:'联系我们', className:'game-lxwm-logo', type:'lxwm',path:'contactus'},
          { name:'新手教学', className:'game-help-logo', type:'xsjx',path:'help'},
          { name:'皇冠公告', className:'game-hggg-logo', type:'gg',path:'moremessage?msg_type=notice'},
          { name:'电脑版', className:'game-pc-logo', type:'pc',path:'/home'},
          { name:'APP下载', className:'game-app-logo', type:'app',path:'appdownload'}
  ]
    }
  },
    beforeDestroy(){

    },
    mounted: function () {
        let _self = this ;

        if(_self.baseSettingData.redPocketOpen){ // 需要判断是否开启活动
          _self.setTimerAc('.new_year_time');
        }
        _self.$refs.indexBanner.getIndexData(); // 获取公告

    },
    methods:{

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
