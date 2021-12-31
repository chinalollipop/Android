<template>
  <div class="home_container" ref="home_container">
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="false" pa_title="" />

    <div class="lb_gg">
      <!-- 轮播 -->
      <indexBanner ref="indexBanner"/>

      <!-- 滚动公告 -->
      <div class="notice index_notice" >
        <div class="notice-cont">
          <i class="notice-icon index_fa">

          </i>
          <div class="text">
            <marquee> {{noticeMsg}} </marquee>
          </div>
        </div>
      </div>

    </div>

    <!-- 钱包中心 -->
    <div class="login_box">
      <div class="login_box_sec">
        <div class="management">
          <div class="wallet_center">
            <p class="linear-color">{{userName?userName:'未登录'}}</p>
            <span class="font_Library">钱包中心:<span class="hg_money">{{userMoney}}</span> </span>
          </div>
          <div class="financial">
            <router-link to="deposit">
              <i class="index_fa fa-ck"></i>
              <div><p>存款</p></div>
            </router-link>
            <router-link to="withdraw">
              <i class="index_fa fa-withdrow"></i>
              <div><p>取款</p></div>
            </router-link>
            <router-link to="tran">
              <i class="index_fa fa-zz"></i>
              <div><p>转账</p></div>
            </router-link>

          </div>
        </div>
      </div>
    </div>

  <div class="game-page-all">
    <div class="game_nav_on"></div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination-game swiper-pagination-custom">
      <li class="swiper-pagination-custom active"><a >体育</a></li>
      <li class="swiper-pagination-custom"><a >真人</a></li>
      <li class="swiper-pagination-custom"><a >电竞</a></li>
      <li class="swiper-pagination-custom"><a >彩票</a></li>
      <li class="swiper-pagination-custom"><a >棋牌</a></li>
      <li class="swiper-pagination-custom"><a >电游</a></li>
    </div>
  </div>

    <div class="middle_content">

      <div class="carousel swiper-container-game">
        <div class="gameListAll">
          <template v-for="(lists,item) in gamesList">

            <div class="Menual" :key="item" v-if="gametypeList.indexOf(lists.type)>=0">
              <a @click="openNewGame(lists.path,lists.type)" ><span :class="lists.className"></span><p class="num" :class="lists.classNum"> </p></a>
            </div>
            <div class="Menual" :key="item" v-else>
              <router-link :to="lists.path" ><span :class="lists.className"></span><p class="num" :class="lists.classNum"> </p></router-link>
            </div>
          </template>

          <!--<div class="clear"></div>-->
        </div>
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
          { name:'体育投注', className:'game-sport-logo',classNum:'hgSportNum', type:'hg',path:'sport'},
          { name:'AG视讯', className:'game-live-ag-logo',classNum:'agLiveNum', type:'ag',path:'gameswin?gametype=ag'},
          { name:'OG视讯', className:'game-live-og-logo',classNum:'ogLiveNum', type:'og',path:'gameswin?action=getLaunchGameUrl&gametype=og'},
          { name:'BBIN视讯', className:'game-live-bbin-logo',classNum:'bbinLiveNum', type:'bbin',path:'gameswin?action=getLaunchGameUrl&gametype=bbin'},
          { name:'泛亚电竞', className:'game-dianjing-logo',classNum:'fydjNum', type:'avia',path:'gameswin?action=getLaunchGameUrl&gametype=avia'},
          { name:'雷火电竞', className:'game-lh-logo',classNum:'lhdjNum',type:'fire',path:'gameswin?action=getLaunchGameUrl&gametype=fire'},
          { name:'彩票游戏', className:'game-lottery-logo',classNum:'lotteryChessNum', type:'gmcp',path:'gameswin?gametype=gmcp'},
          { name:'开元棋牌', className:'game-chess-logo-ky',classNum:'kyChessNum', type:'ky',path:'gameswin?action=cm&gametype=ky'},
          { name:'乐游棋牌', className:'game-chess-logo-ly',classNum:'lyChessNum', type:'ly',path:'gameswin?action=cm&gametype=ly'},
          { name:'VG棋牌', className:'game-chess-logo-vg',classNum:'vgChessNum', type:'vg',path:'gameswin?action=cm&gametype=vg'},
          { name:'快乐棋牌', className:'game-chess-logo-kl',classNum:'klChessNum', type:'kl',path:'gameswin?action=cm&gametype=kl'},
          { name:'捕鱼王', className:'game-by-logo',classNum:'', type:'agby',path:'gameswin?game_id=6&gametype=agby'},
          { name:'AG电子', className:'game-ag-logo',classNum:'', type:'aggame',path:'/games?gametype=aggame&gamename=AG电子'},
          { name:'MG电子', className:'game-mg-logo',classNum:'', type:'mg',path:'/games?gametype=mg&gamename=MG电子'},
          { name:'MW电子', className:'game-mw-logo',classNum:'', type:'mw',path:'/games?gametype=mw&gamename=MW电子'},
          { name:'CQ9电子', className:'game-cq-logo',classNum:'', type:'cq',path:'/games?gametype=cq&gamename=CQ9电子'},
          { name:'FG电子', className:'game-fg-logo',classNum:'', type:'fg',path:'/games?gametype=fg&gamename=FG电子'}
    ]

    }
  },
    mounted: function () {
        let _self = this ;

        _self.$refs.home_container.style.height = window.innerHeight+'px'; // 初始化首页高度

        _self.$refs.indexBanner.getIndexData(); // 获取公告


    },
    methods:{

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .home_container{width:100%;margin:0 auto;overflow:hidden;top:0;left:0;
    display: -webkit-box;
    display: -webkit-flex;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    flex-direction: column;
  }
  .home_container .header{height:3rem;box-shadow: 0 0 3.2rem rgba(0,0,0,.1) inset;margin-bottom: 0;}
  .home_container>div{-webkit-flex:none;flex:none;height:auto}
  /* app 下载提示 */
  .home_container>>> .app_tip{z-index:4;background:#fff;text-align:left;padding:5px 0 3px 6%}
  .home_container>>> .app_tip .app_tip_logo{display:inline-block;width:3.5rem;height:3.5rem;background:url(/static/images/8msport/add-logo.png) center no-repeat;background-size:100%;margin-right:.8rem}
  .home_container>>> .app_tip div{display:inline-block;vertical-align:top}
  .home_container>>> .app_tip .title p{font-size:1.1rem;color: #7d7d7d;}
  .home_container>>> .app_tip .title p:first-child{margin-top:.5rem;}
  .home_container>>> .app_tip .download_btn{display:inline-block;border-radius:5px;padding:3px 8px 4px;float:right;margin:1rem 7% 0 0;}
  .home_container>>> .app_tip .download_btn span{display:inline-block;vertical-align:middle}
  .home_container>>> .app_tip .icon{width: 1.5rem;height: 1.8rem;}
  .home_container>>> .app_tip .icon.and{background:url(/static/images/8msport/apptip/and.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .icon.ios{background:url(/static/images/8msport/apptip/ios.png) center no-repeat;background-size: 90%;}
  .home_container>>> .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(/static/images/8msport/apptip/close.png) center no-repeat;background-size:100%;position:absolute;right:1%;top:8px;}

  .home_container>>> .swiper-container{width:98%;min-height:6rem;margin:auto}
  .home_container>>> .swiper-wrapper{height: auto;}
  .home_container>>> .swiper-slide{width:90%}
  .carousel img{width:100%}
  .notice{width:98%;margin:auto;background:#6b91e9;background:linear-gradient(to right,#6b91e9 0%,#5da2ea 100%);border-radius:5px 5px 0 0;font-size:1rem;height:2rem !important;line-height:2rem}
  .notice-cont {width: 100%;height: 100%}
  .notice div,.notice span{ display: inline-block;}
  .notice .notice-icon{width:3rem;height: 2.5rem;position: static;float: left;margin-top: -0.2rem;background-image:url(/static/images/8msport/index/gonggao.png);background-size: 60%;background-position: 10px 4px;}
  .notice .text {width: 85%;height:100%;float: left;}
  .notice .more-notice a{color:#FE6B5A; }

  /* 首页游戏列表 */
  .Menual{width: 98%;display:inline-block;height: 7rem;}
  .listType:first-child .Menual:first-child {margin-top: .5rem;}
  .Menual a {display:block;color: #2A8FBD;font-size: 1.2rem;position: relative;}
  .Menual span{display: block;height: 7rem;margin: 0 auto .5rem;background-size: 100%;border-radius: 6px;-webkit-border-radius: 6px;-ms-border-radius: 6px;}
  .Menual .num{display:none;position:absolute;height:auto;top:28%;left:51%;color:#fff;font-size:1.6rem;}
  .Menual .num.hgSportNum{top:35%;left: 42%;width: 4rem;text-align: right;}
  .Menual .game-sport-logo{background: url(/static/images/8msport/index/idx_sport.png) center no-repeat;background-size: 100%;}
  .Menual .game-sport-logo-bk{background: url(/static/images/8msport/index/idx_sport_bk.png) center no-repeat;background-size: 100%;}
  .Menual .game-bbin-logo{background: url(/static/images/8msport/index/idx_bb.png) center no-repeat;background-size: 100%;}
  .Menual .game-oblive-logo{background: url(/static/images/8msport/index/idx_allbet.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-ag-logo{background: url(/static/images/8msport/index/idx_ag.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-og-logo{background: url(/static/images/8msport/index/idx_og.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-bbin-logo{background: url(/static/images/8msport/index/idx_bbin.png) center no-repeat;background-size: 100%;}
  .Menual .game-kyqp-logo{background: url(/static/images/8msport/index/idx_kg.png) center no-repeat;background-size: 100%;}
  .Menual .game-lottery-logo{background: url(/static/images/8msport/index/idx_vr.png) center no-repeat;background-size: 100%;}
  .Menual .game-lottery-logo-xy{background: url(/static/images/8msport/index/idx_vr_xy.png) center no-repeat;background-size: 100%;}
  .Menual .game-byw-logo{background: url(/static/images/8msport/index/idx_fish.png) center no-repeat;background-size: 100%;}
  .Menual .game-pkw-logo{background: url(/static/images/8msport/index/idx_honey.png) center no-repeat;background-size: 100%;}
  .Menual .game-qhb-logo{background: url(/static/images/8msport/index/idx_hongbao.jpg) center no-repeat;background-size: 100%100%;}
  .Menual .game-xchb-logo{background: url(/static/images/8msport/index/idx_yearhb.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-xchb68-logo{background: url(/static/images/8msport/index/idx_yearhb68.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-promo-logo{background: url(/static/images/8msport/index/idx_pro.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-ydhg-logo{background: url(/static/images/8msport/index/idx_yd.png) center no-repeat;background-size: 100%;}
  .Menual .game-dljm-logo{background: url(/static/images/8msport/index/idx_agent.png) center no-repeat;background-size: 100%;}
  .Menual .game-pc-logo{background: url(/static/images/8msport/index/idx_pc.png) center no-repeat;background-size: 100%;}
  .Menual .game-by-logo{background: url(/static/images/8msport/index/game-by-logo.png) center no-repeat;background-size: 100%;}
  .Menual .game-dianjing-logo{background: url(/static/images/8msport/index/idx_dianjing.png) center no-repeat;background-size: 100%;}
  .Menual .game-lh-logo{background: url(/static/images/8msport/index/idx_dianjing_lh.png) center no-repeat;background-size: 100%;}
  .Menual .game-app-logo{background: url(/static/images/8msport/index/appload.png) center no-repeat;background-size: 100%;}
  .Menual .game-xldh-logo{background: url(/static/images/8msport/index/idx_wifi.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-lxwm-logo{background: url(/static/images/8msport/index/idx_contact.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-hggg-logo{background: url(/static/images/8msport/index/idx_remind.png) center no-repeat;background-size: 100%;}
  .Menual .game-help-logo{background: url(/static/images/8msport/index/idx_newbie.png) center no-repeat;background-size: 100%;}
  .Menual .game-qq-logo{ background: url(/static/images/8msport/home_qq.png) center no-repeat;background-size: 100%;}
  .Menual .game-wechat-logo{ background: url(/static/images/8msport/home_wechat.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo{ background: url(/static/images/8msport/index/idx_chess.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-ky{ background: url(/static/images/8msport/index/idx_chess_ky.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-vg{ background: url(/static/images/8msport/index/idx_chess_vg.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-hg{ background: url(/static/images/8msport/index/idx_chess_hg.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-ly{ background: url(/static/images/8msport/index/idx_chess_ly.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-kl{ background: url(/static/images/8msport/index/idx_chess_kl.png) center no-repeat;background-size: 100%;}
  .Menual .game-fg-logo{background: url(/static/images/8msport/index/idx_egame_fg.png) center no-repeat;background-size: 100%;}
  .Menual .game-ag-logo{background: url(/static/images/8msport/index/idx_egame_ag.png) center no-repeat;background-size: 100%;}
  .Menual .game-mg-logo{background: url(/static/images/8msport/index/idx_egame_mg.png) center no-repeat;background-size: 100%;}
  .Menual .game-cq-logo{background: url(/static/images/8msport/index/idx_egame_cq9.png) center no-repeat;background-size: 100%;}
  .Menual .game-mw-logo{background: url(/static/images/8msport/index/idx_egame_mw.png) center no-repeat;background-size: 100%;}

  .login_box{width:98%;margin:0 auto .4rem;color:#7c7c7c;font-size:1rem;background:#fff;border-radius:0 0 5px 5px}
  .login_box p{color: #7c7c7c;}
  .login_box .login_title{background:linear-gradient(to right,#ca9024 1%,#eeaf46 100%);color:#fff;text-align:left;padding:0 1.3rem;height:2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden}
  .login_box .login_title span{display:inline-block;max-width:40%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .login_box .login_txt{display:inline-block;width:6rem;height:2.3rem;background:url(/static/images/8msport/logo_icon.png) no-repeat;background-size:100%}
  .login_box  .login_grzl{border:1px solid #fff;border-radius:7px;margin-left:1.5rem;padding:.2rem .8rem}
  .login_box .management{padding:.5rem 0 .3rem;overflow:hidden}
  .login_box .wallet_center{display:inline-block;width:44%;float:left}
  .login_box .wallet_center p{-webkit-background-clip:text;color:transparent;-webkit-text-fill-color:transparent}
  .login_box .font_Library span{color:#000;font-weight: 700;}
  .login_box .font_Library i{font-style:normal;color:#d89a37}
  .login_box .financial a{float:left;width:14%;margin-left:1%;background:#fff;border-radius:8px;padding:5px 5px 0}
  .login_box .financial a i{margin:-.5rem -1.6rem;position:static}
  @media only screen and (max-width:320px){
    .login_box .financial a {margin-left: 0;transform: scale(.95);}
  }

  /* 游戏列表轮播 */
  .swiper-pagination-game.swiper-pagination-custom { display: -webkit-box;display: -webkit-flex;display: flex;width: 100%;overflow: hidden;padding: 5px;}
  .swiper-pagination-game .swiper-pagination-custom{-webkit-flex:auto;flex: auto;position:relative;bottom:0;width:auto;color:#060606;height:2.3rem;line-height:2.3rem;}
  .swiper-pagination-game .swiper-pagination-custom:last-child{padding: 0;}
  .swiper-pagination-game .swiper-pagination-custom.active{width:20%;/*border-radius: 20px;background: #7387e8;background: linear-gradient(to right,#7387e8 0%,#5f9fea 100%);*/color: #fff;}
  .swiper-pagination-game .swiper-pagination-custom.active a{color: #fff;}
  .swiper-pagination-game .swiper-pagination-custom.active:first-child a{margin-right:20%}
  .game-page-all{position:relative;width:98%;z-index:1;background:#fff;border-radius:5px;margin:auto}
  .game_nav_on{position:absolute;width:25%;height:2.3rem;border-radius:20px;background:#7387e8;background:linear-gradient(to right,#7387e8 0%,#5f9fea 100%);margin:5px 0 0 0;transition-duration:200ms}
  .swiper-pagination-game a{color: #000}
  .home_container .middle_content{overflow-y: scroll;-webkit-overflow-scrolling: touch;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;}
</style>
