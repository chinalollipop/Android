<template>
  <div class="home_container" ref="home_container">
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="false" pa_title="" />

    <div class="lb_gg">
      <!-- 轮播 -->
      <indexBanner ref="indexBanner"/>

      <!-- 新年红包 -->
      <div v-if="baseSettingData.redPocketOpen" class="new_year_con" style="position:absolute;display:inline-block;width: 8rem;height: 9rem;background: url(/static/images/hongbao/newy_btn_6668.gif) center no-repeat;background-size: 100%;left: 0;z-index: 20;bottom: 2rem;">
        <a class="close_new_year" onclick="$(this).parent().hide()" style="display: block; position: absolute; width: 30px; height: 30px; right: -4px; top: -4px;"></a>
        <router-link class="to_promos" to="/promo?prokey=newyear_hb&showbg=bg" style="display: block;height: 80%;width: 100%;margin-top: 20px;"></router-link>
      </div>

      <!-- 滚动公告 -->
      <div class="notice index_notice">
        <div class="notice-cont">
             <span class="notice-icon">
                 <i class="fa fa-volume-down"></i>
             </span>
            <div class="text">
              <marquee> {{noticeMsg}} </marquee>
            </div>

        </div>
      </div>
    </div>

    <!-- 钱包中心 -->
    <div class="login_box">
      <div class="login_box_sec">
        <div class="login_title">
          <span>{{userName?userName:'未登录'}}</span>
          <div class="wallet_center">
            <div v-if="userName" class="wallet">中心钱包： <span class="font_Library"><i>¥</i><span class="hg_money">{{userMoney}}</span> </span></div>
            <div v-else class="wallet" ><span>请先登录 </span><i class="right qbzx_fa login_icon"></i></div>
        </div>

      </div>
      <div class="management">

        <div class="financial">
          <router-link to="deposit">
            <i class="qbzx_fa fa-deposit-card"></i>
            <span>存款</span>
          </router-link>
          <router-link to="tran">
            <i class="qbzx_fa fa-zz"></i>
            <span>转账</span>
          </router-link>
          <router-link to="withdraw">
            <i class="qbzx_fa fa-withdrow"></i>
            <span>取款</span>
          </router-link>
          <router-link to="bankcard">
            <i class="qbzx_fa fa-yhk"></i>
            <span>银行卡</span>
          </router-link>
        </div>
      </div>
    </div>
  </div>
  <div class="game-page-all">
    <div class="game_nav_on"></div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination-game swiper-pagination-custom">
      <li class="swiper-pagination-custom active"><a >体育赛事</a></li>
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

//import axios from 'axios'
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
          { name:'彩票游戏', className:'game-lottery-logo',classNum:'lotteryChessNum', type:'cp',path:'gameswin?gametype=cp'},
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
    ],
      hgSportNum: 0,
      agLiveNum: 0,
      ogLiveNum: 0,
      bbinLiveNum: 0,
      fydjNum: 0,
      lhdjNum: 0,
      hgChessNum: 0,
      vgChessNum: 0,
      lyChessNum: 0,
      kyChessNum: 0,
      klChessNum: 0,
      lotteryChessNum: 0
    }
  },
    mounted: function () {
        let _self = this ;

        _self.$refs.home_container.style.height = window.innerHeight+'px'; // 初始化首页高度

        _self.$refs.indexBanner.getIndexData(); // 获取公告

        _self.$nextTick(()=>{
            _self.getBallNum();
        })

    },
    methods:{
      /* 获取数量 */
        getBallNum:function () {
            let _self = this;
            return new Promise((resolve, reject)=>{
                _self.axios({
                    method: 'post',
                    params: {},
                    url: _self.ajaxUrl.gameNumapi
                }).then(res=>{
                    if(res){
                        let rest = res.data;
                        $('.hgSportNum').text(rest.data.hgSportNum);
                        $('.agLiveNum').text(rest.data.agLiveNum);
                        $('.ogLiveNum').text(rest.data.ogLiveNum);
                        $('.bbinLiveNum').text(rest.data.bbinLiveNum);
                        $('.fydjNum').text(rest.data.fydjNum);
                        $('.lhdjNum').text(rest.data.lhdjNum);
                        $('.hgChessNum').text(rest.data.hgChessNum);
                        $('.vgChessNum').text(rest.data.vgChessNum);
                        $('.lyChessNum').text(rest.data.lyChessNum);
                        $('.kyChessNum').text(rest.data.kyChessNum);
                        $('.klChessNum').text(rest.data.klChessNum);
                        $('.lotteryChessNum').text(rest.data.lotteryChessNum);
                        resolve();
                    }
                }).catch(res=>{
                    console.log('球数量请求失败');
                    reject(res);
                });

            })
        },
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
  .home_container>div{-webkit-flex:none;flex: none;height: auto;}
  .home_container .header{height: 2.8rem;}
  /* app 下载提示 */
  .home_container>>> .app_tip{background:url(/static/images/6668/apptip/bg.png) center no-repeat;background-size:cover;text-align:left;padding: 5px 0 2px 6%;}
  .home_container>>> .app_tip .app_tip_logo{display:inline-block;width: 2.7rem;height: 2.7rem;background:url(/static/images/6668/add-logo.png) center no-repeat;background-size:100%;margin-right: 0.5rem;}
  .home_container>>> .app_tip div{display:inline-block;vertical-align:top}
  .home_container>>> .app_tip .title p{font-size: 1rem;}
  .home_container>>> .app_tip .title p:first-child{/* margin-top: .1rem; */}
  .home_container>>> .app_tip .download_btn{display:inline-block;background:#fff;border-radius:20px;color:#2a8fbd;padding:3px 8px 4px;float:right;margin: .5rem 10% 0 0;}
  .home_container>>> .app_tip .download_btn span{display:inline-block;vertical-align:middle}
  .home_container>>> .app_tip .icon{width:1.5rem;height:1.5rem}
  .home_container>>> .app_tip .icon.and{background:url(/static/images/6668/apptip/and.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .icon.ios{background:url(/static/images/6668/apptip/ios.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(/static/images/6668/apptip/close.png) center no-repeat;background-size:100%;position:absolute;right:1%;top:5px;}

  /* 轮播 */
  .lb_gg{position: relative}
  .lb_gg>>> .swiper-pagination{display: none;}
  .notice {padding: .4rem 0;margin:0 auto;}
  .index_notice{width:100%;background: rgba(0,0,0,.5);padding: .4rem 3% .1rem;position: absolute;border-radius: 15px 15px 0 0;bottom: 0;z-index: 2;}
  .notice-cont {width: 100%;}
  .notice div,.notice span{ display: inline-block;}
  .notice .notice-icon{width: 2.2rem;height: 1.4rem;color: #2A8FBD;background: url(/static/images/6668/notice.png) center no-repeat;background-size:contain;vertical-align: middle;}
  .notice .text {width: 87%;float: right;margin-left: 0.8rem;}
  .notice .more-notice a{color:#FE6B5A; }

  /* 首页 */
  .Menual{width: 94%;display:inline-block;margin-top: .5rem;height: 7rem;}
  .Menual:last-child{margin-bottom: 6rem;}
  .Menual a {display:block;color: #2A8FBD;font-size: 1.2rem;position: relative;}
  .Menual span{display: block;height: 7rem;margin: 0 auto .5rem;background-size: 100%;border-radius: 6px;-webkit-border-radius: 6px;-ms-border-radius: 6px;}
  .Menual .num{position:absolute;height:auto;top:28%;left:51%;color:#fff;font-size:1.6rem;}
  .Menual .num.hgSportNum{top:35%;left: 42%;width: 4rem;text-align: right;}
  .Menual .game-sport-logo{background: url(/static/images/6668/index/idx_sport.png) center no-repeat;background-size: 100%;}
  .Menual .game-bbin-logo{background: url(/static/images/6668/index/idx_bb.png) center no-repeat;background-size: 100%;}
  .Menual .game-oblive-logo{background: url(/static/images/6668/index/idx_allbet.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-ag-logo{background: url(/static/images/6668/index/idx_ag.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-og-logo{background: url(/static/images/6668/index/idx_og.png) center no-repeat;background-size: 100%;}
  .Menual .game-live-bbin-logo{background: url(/static/images/6668/index/idx_bbin.png) center no-repeat;background-size: 100%;}
  .Menual .game-kyqp-logo{background: url(/static/images/6668/index/idx_kg.png) center no-repeat;background-size: 100%;}
  .Menual .game-lottery-logo{background: url(/static/images/6668/index/idx_vr.png) center no-repeat;background-size: 100%;}
  .Menual .game-byw-logo{background: url(/static/images/6668/index/idx_fish.png) center no-repeat;background-size: 100%;}
  .Menual .game-pkw-logo{background: url(/static/images/6668/index/idx_honey.png) center no-repeat;background-size: 100%;}
  .Menual .game-qhb-logo{background: url(/static/images/6668/index/idx_hongbao.jpg) center no-repeat;background-size: 100%100%;}
  .Menual .game-xchb-logo{background: url(/static/images/6668/index/idx_yearhb.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-xchb68-logo{background: url(/static/images/6668/index/idx_yearhb68.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-promo-logo{background: url(/static/images/6668/index/idx_pro.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-ydhg-logo{background: url(/static/images/6668/index/idx_yd.png) center no-repeat;background-size: 100%;}
  .Menual .game-dljm-logo{background: url(/static/images/6668/index/idx_agent.png) center no-repeat;background-size: 100%;}
  .Menual .game-pc-logo{background: url(/static/images/6668/index/idx_pc.png) center no-repeat;background-size: 100%;}
  .Menual .game-by-logo{background: url(/static/images/6668/index/game-by-logo.png) center no-repeat;background-size: 100%;}
  .Menual .game-dianjing-logo{background: url(/static/images/6668/index/idx_dianjing.png) center no-repeat;background-size: 100%;}
  .Menual .game-lh-logo{background: url(/static/images/6668/index/game-lh-logo.png) center no-repeat;background-size: 100%;}
  .Menual .game-app-logo{background: url(/static/images/6668/index/appload.png) center no-repeat;background-size: 100%;}
  .Menual .game-xldh-logo{background: url(/static/images/6668/index/idx_wifi.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-lxwm-logo{background: url(/static/images/6668/index/idx_contact.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-hggg-logo{background: url(/static/images/6668/index/idx_remind.png) center no-repeat;background-size: 100%;}
  .Menual .game-help-logo{background: url(/static/images/6668/index/idx_newbie.png) center no-repeat;background-size: 100%;}
  .Menual .game-qq-logo{ background: url(/static/images/6668/home_qq.png) center no-repeat;background-size: 100%;}
  .Menual .game-wechat-logo{ background: url(/static/images/6668/home_wechat.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo{ background: url(/static/images/6668/index/idx_chess.jpg) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-ky{ background: url(/static/images/6668/index/idx_chess_ky.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-vg{ background: url(/static/images/6668/index/idx_chess_vg.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-kl{ background: url(/static/images/6668/index/idx_chess_kl.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-hg{ background: url(/static/images/6668/index/idx_chess_hg.png) center no-repeat;background-size: 100%;}
  .Menual .game-chess-logo-ly{ background: url(/static/images/6668/index/idx_chess_ly.png) center no-repeat;background-size: 100%;}
  .Menual .game-fg-logo{background: url(/static/images/6668/index/idx_egame_fg.png) center no-repeat;background-size: 100%;}
  .Menual .game-ag-logo{background: url(/static/images/6668/index/idx_egame_ag.png) center no-repeat;background-size: 100%;}
  .Menual .game-mg-logo{background: url(/static/images/6668/index/idx_egame_mg.png) center no-repeat;background-size: 100%;}
  .Menual .game-cq-logo{background: url(/static/images/6668/index/idx_egame_cq9.png) center no-repeat;background-size: 100%;}
  .Menual .game-mw-logo{background: url(/static/images/6668/index/idx_egame_mw.png) center no-repeat;background-size: 100%;}
  /* 钱包中心 */
  .login_box{width:100%;height:6rem;padding:0 8px;background: url(/static/images/6668/index/qb_bg.png) top center no-repeat;background-size: 100%;}
  .login_box .login_title{color: #707070;text-align:left;padding:0 1.3rem;height: 2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden;}
  .login_box .login_title span {max-width: 100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: top;display: inline-block;}
  .login_box .management{padding:.5rem .8rem 0;overflow:hidden;border-top: 1px dashed #e8e8e8;}
  .login_box .wallet_center{display:inline-block;width: 65%;float: right;text-align: right;}
  .login_box .font_Library i{font-style:normal}
  i.login_icon{background-position:-212px -3px;margin-top:-.4rem}

  /* 游戏列表轮播 */
  .swiper-pagination-game.swiper-pagination-custom { display: -webkit-box;display: -webkit-flex;display: flex;width: 100%;background: #fff;box-shadow: 0 2px 4px rgba(169, 169, 169, 0.2);overflow: hidden;border-radius: 0;}
  .swiper-pagination-game .swiper-pagination-custom{-webkit-flex:auto;flex: auto;position:relative;bottom:0;width:auto;color:#000;height:3rem;line-height:4rem;}
  .swiper-pagination-game .swiper-pagination-custom:last-child{padding: 0;}
  .swiper-pagination-game .swiper-pagination-custom.active{line-height: 3.2rem;width: 30%;color: #fff;transform:scale(1.1);}
  .swiper-pagination-game .swiper-pagination-custom.active a{color: #fff;}
  .game-page-all{position: relative;width: 100%;z-index: 1;}
  .game_nav_on{position:absolute;width:35%;height: 3rem;background: url(/static/images/6668/index/btn_active.png) center no-repeat;background-size: 100%;margin-top: .40rem;transition-duration:200ms;}
  .swiper-pagination-game .swiper-pagination-custom.active:before{position:absolute;width:100%;left:0;line-height:5.2rem;transform:scale(.65)}
  .swiper-pagination-game .swiper-pagination-custom:first-child.active:before{content:'SPORTS'}
  .swiper-pagination-game .swiper-pagination-custom:nth-child(2).active:before{content:'LIVE CASINO'}
  .swiper-pagination-game .swiper-pagination-custom:nth-child(3).active:before{content:'E-SPORTS'}
  .swiper-pagination-game .swiper-pagination-custom:nth-child(4).active:before{content:'LOTTERY'}
  .swiper-pagination-game .swiper-pagination-custom:nth-child(5).active:before{content:'BOARD GAMES'}
  .swiper-pagination-game .swiper-pagination-custom:nth-child(6).active:before{content:'SLOT GAME'}
  .swiper-pagination-game a{color: #000}
  .home_container .middle_content{overflow-y: scroll;-webkit-overflow-scrolling: touch;
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;}

</style>
