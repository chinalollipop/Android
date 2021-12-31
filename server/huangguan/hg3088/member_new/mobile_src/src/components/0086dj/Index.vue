<template>
  <div class="home_container">
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="dj_top css_flex">
      <span class="logo"></span>
      <div class="top_right css_flex">
        <template v-for="(lists,item) in gamesList">
          <a :key="item" :class="item==0 && 'active'" v-if="gametypeList.indexOf(lists.type)>=0" @click="openNewGame(lists.path,lists.type,'',$event)" :data-type="lists.type">
            {{lists.name}}
          </a>
          <router-link v-else :key="item" :to="lists.path" >{{lists.name}}</router-link>
        </template>
        <a :href="baseSettingData.service_meiqia" target="_blank">在线客服</a>
      </div>

    </div>

    <div class="content-center">
      <!-- 轮播 -->
      <indexBanner v-show="!userName" ref="indexBanner"/> <!-- 未登录 -->

      <iframe v-if="userName" id="body_dzjj" name="body_dzjj" width="100%" height="100%" frameborder="0"> </iframe> <!-- 登录后 -->

    </div>

  </div>
</template>

<script>

import Mixin from '@/Mixin'
import Dialog from '@/components/Dialog'
import indexBanner from '@/components/common/indexBanner'

export default {
  name: 'index',
    mixins:[Mixin],
    components: {
        Dialog,
        indexBanner
    },
  data () {
    return {
      noticeMsg:'欢迎光临',
      gamesList:[
          { name:'雷火电竞', className:'game-lhdj-logo',type:'fire', path:'gameswin?action=getLaunchGameUrl&gametype=fire'},
          { name:'泛亚电竞', className:'game-fydj-logo', type:'avia',path:'gameswin?action=getLaunchGameUrl&gametype=avia'},
          { name:'会员中心', className:'game-hyzx-logo',type:'account', path:'/myaccount'}
  ]
    }
  },
    mounted: function () {
        let _self = this ;

        if(_self.userName){ // 登录后
            _self.openNewGame('gameswin?action=getLaunchGameUrl&gametype=fire','fire'); // 默认打开雷火电竞
        }

    },
    methods:{

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .home_container>>> .swiper-pagination{display: none;}
    .content-center,iframe{margin-bottom: 0;min-height: calc(100vh - 3.5rem);}
    .dj_top{background: #191929;height: 3.5rem;line-height: 3.5rem;}
    .dj_top .logo{display:inline-block;width:9rem;height:100%;background: url(/static/images/0086dj/logo.png) center no-repeat;background-size: contain;}
    .dj_top .top_right,.dj_top .top_right a{-webkit-flex: auto;flex: auto;position: relative;}
    .dj_top .active{color: #ffb400;}
    .dj_top .active:after {position: absolute;content: '';width: 100%;height: 3px;background: #ffb400;left: 0;bottom: 0;}
</style>
