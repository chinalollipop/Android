<template>
  <div class="home_container">
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <HeaderNav pa_showback="false" pa_title="" />

    <div class="content-center">
      <!-- 轮播 -->
      <indexBanner ref="indexBanner"/>

      <!-- 滚动公告 -->
      <div class="notice index_notice">
        <div class="notice-cont">
          <i class="notice-icon index_fa">

          </i>
          <div class="text">
            <marquee>
              {{noticeMsg}}
            </marquee>
          </div>
        </div>
      </div>
      <div class="login_box">
        <div class="login_box_sec">
          <div class="management">
            <div class="wallet_center">
              <p>{{userName?userName:'未登录'}}</p>
              <span class="font_Library">钱包 <i>¥</i><span class="hg_money">{{userMoney?userMoney:0}}</span> </span>
            </div>

            <div class="financial">
              <router-link to="deposit">
                <div class="left">
                  <p>充值存款</p>
                  <p class="tip">快捷到账</p>
                </div>
                <i class="index_fa fa-credit-card"></i>
              </router-link>
              <router-link to="tran">
                <div class="left">
                  <p>额度转账</p>
                  <p class="tip">简易秒换</p>
                </div>
                <i class="index_fa fa-zz"></i>
              </router-link>
              <router-link to="withdraw">
                <div class="left">
                  <p>取款便利</p>
                  <p class="tip">秒到账户</p>
                </div>
                <i class="index_fa fa-withdrow"></i>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="game_classified">
        <div class="render-list-wrap">
          <div class="am-tabs am-tabs-vertical am-tabs-left">
            <div class="am-tabs-tab-bar-wrap">
              <div class="am-tabs-default-bar am-tabs-default-bar-animated am-tabs-default-bar-left">
                <div class="am-tabs-default-bar-content">
                  <div class="am-tabs-default-bar-tab active">
                    <div class="game_species">
                      <i class="index_fa fa-sport"></i>
                      <span>体育竞技</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-live"></i>
                      <span>真人视讯</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-lottery"></i>
                      <span>彩票游戏</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-game"></i>
                      <span>电子游戏</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-chess"></i>
                      <span>棋牌游戏</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-dzjj"></i>
                      <span>电子竞技</span>
                    </div>
                  </div>
                  <div class="am-tabs-default-bar-tab">
                    <div class="game_species">
                      <i class="index_fa fa-fish"></i>
                      <span>捕鱼游戏</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="am-tabs-content-wrap am-tabs-content-wrap-animated">
              <!--体育-->
              <div class="am-tabs-pane-wrap wrap-active">
                <div class="game_img" v-for="(lists,item) in gamesList" v-if="item==5" :key="item" >
                  <router-link :to="lists.path">
                    <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                  </router-link>
                </div>
              </div>
              <!--真人-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img">
                  <div class="game_xz distance game_xz_50 game_xz_75">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item<=2" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                    </div>
                    <div class="game_img " @click="$refs.autoDialog.setPublicPop('敬请期待')">
                      <img src="/static/images/3366/index/index_ag_4.png" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--彩票-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_50">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item>14 && item<=16" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--老虎机-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_75">
                    <div class="game_img" :class="['cq','mw'].indexOf(lists.type)>=0 &&'game_all game_six_img'" v-for="(lists,item) in gamesList" v-if="item>9 && item<=14" :key="item" >
                      <router-link :to="lists.path">
                        <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
              <!--棋牌-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img">
                  <div class="game_xz distance">
                    <div class="game_img" :class="['ly','kl'].indexOf(lists.type)>=0 &&'game_all game_six_img'" v-for="(lists,item) in gamesList" v-if="item>5 && item<=9" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--电子竞技-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_50">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item>2 && item<=4" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>

              <!--捕鱼-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" v-for="(lists,item) in gamesList" v-if="item==17" :key="item" @click="openNewGame(lists.path,lists.type)" >
                  <div>
                    <img :src="'/static/images/3366/index/'+lists.className+'.png'" alt="">
                  </div>
                </div>
              </div>

            </div>
          </div>
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
          { name:'AG视讯', className:'index_ag_1', type:'ag',path:'gameswin?gametype=ag'},
          { name:'OG视讯', className:'index_ag_2', type:'og',path:'gameswin?action=getLaunchGameUrl&gametype=og'},
          { name:'BBIN视讯', className:'index_ag_3', type:'bbin',path:'gameswin?action=getLaunchGameUrl&gametype=bbin'},
          { name:'雷火电竞', className:'index_dzjj_lh',type:'fire', path:'gameswin?action=getLaunchGameUrl&gametype=fire'},
          { name:'泛亚电竞', className:'index_dzjj', type:'avia',path:'gameswin?action=getLaunchGameUrl&gametype=avia'},
          { name:'体育投注', className:'index_sport', type:'hg',path:'sport'},
          { name:'开元棋牌', className:'index_kyqp', type:'ky',path:'gameswin?action=cm&gametype=ky'},
          { name:'VG棋牌', className:'index_vgqp', type:'vg',path:'gameswin?action=cm&gametype=vg'},
          { name:'乐游棋牌', className:'index_lyqp', type:'ly',path:'gameswin?action=cm&gametype=ly'},
          { name:'快乐棋牌', className:'index_klqp', type:'kl',path:'gameswin?action=cm&gametype=kl'},
          { name:'AG电子', className:'index_ag_game',type:'aggame',path:'/games?gametype=aggame&gamename=AG电子'},
          { name:'MG电子', className:'index_mg_game',type:'mg',path:'/games?gametype=mg&gamename=MG电子'},
          { name:'FG电子', className:'index_fg_game',type:'fg',path:'/games?gametype=fg&gamename=FG电子'},
          { name:'MW电子', className:'index_mw_game',type:'mw',path:'/games?gametype=mw&gamename=MW电子'},
          { name:'CQ9电子', className:'index_cq_game',type:'cq',path:'/games?gametype=cq&gamename=CQ9电子'},
          { name:'信用玩法', className:'index_x_lottery', type:'gmcp',path:'gameswin?gametype=gmcp&cp_type=1'},
          { name:'官方玩法', className:'index_g_lottery', type:'gmcp',path:'gameswin?gametype=gmcp'},
          { name:'捕鱼王', className:'index_fish', type:'agby',path:'gameswin?game_id=6&gametype=agby'}
  ]
    }
  },
    mounted: function () {
        let _self = this ;

        _self.$refs.indexBanner.getIndexData(); // 获取公告

        _self.$nextTick(()=>{
            _self.changeGameTag();
        });
    },
    methods:{
        // 首页游戏切换
        changeGameTag:function () {
          $('.am-tabs-default-bar-content .am-tabs-default-bar-tab').click(function () {
              var i = $(this).index();
              //console.log(i)
              $(this).addClass('active').siblings().removeClass('active');
              $('.am-tabs-content-wrap .am-tabs-pane-wrap').eq(i).newshow(300).siblings().newhide(300);

          })
      }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  /* app 下载提示 */
  .home_container>>> .app_tip{background:url(/static/images/3366/apptip/bg.png) center no-repeat;background-size:cover;text-align:left;padding:8px 0 5px 6%}
  .home_container>>> .app_tip .app_tip_logo{display:inline-block;width:3.5rem;height:3.5rem;background:url(/static/images/3366/add-logo.png) center no-repeat;background-size:100%;margin-right:.8rem}
  .home_container>>> .app_tip div{display:inline-block;vertical-align:top}
  .home_container>>> .app_tip .title p{font-size:1.1rem;color: #525254;}
  .home_container>>> .app_tip .title p:first-child{margin-top:.5rem;background: url(/static/images/3366/apptip/title.png) center no-repeat;background-size: 100%;text-indent: -99999px;}
  .home_container>>> .app_tip .download_btn{display:inline-block;background: #fba704;border-radius:20px;padding:3px 8px 4px;float:right;margin:1rem 7% 0 0;}
  .home_container>>> .app_tip .download_btn span{display:inline-block;vertical-align:middle}
  .home_container>>> .app_tip .icon{width: 1.5rem;height: 1.8rem;}
  .home_container>>> .app_tip .icon.and{background:url(/static/images/3366/apptip/and.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .icon.ios{background:url(/static/images/3366/ios.png) center no-repeat;background-size: 90%;}
  .home_container>>> .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(/static/images/3366/apptip/close.png) center no-repeat;background-size:100%;position:absolute;right:1%;top:8px;}

  .game_classified{width:100%;height: 27rem;padding: 0 8px 0;-webkit-box-sizing:border-box;box-sizing:border-box;position:relative;overflow:visible;z-index: 0;}
  .render-list-wrap{height: 100%;}
  .am-tabs-default-bar,.am-tabs-left,.am-tabs-right{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;-ms-flex-direction:row;flex-direction:row}
  .am-tabs,.am-tabs *{-webkit-box-sizing:border-box;box-sizing:border-box}
  .am-tabs{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;position:relative;overflow:hidden;height:100%;width:100%}
  .am-tabs-vertical .am-tabs-pane-wrap,.am-tabs-vertical .am-tabs-tab-bar-wrap{height:100%}
  .am-tabs-pane-wrap,.am-tabs-tab-bar-wrap{-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0}
  .am-tabs-default-bar-left,.am-tabs-default-bar-left .am-tabs-default-bar-content,.am-tabs-default-bar-right,.am-tabs-default-bar-right .am-tabs-default-bar-content{-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}
  .am-tabs-default-bar,.am-tabs-default-bar-tab{position:relative;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-shrink:0;-ms-flex-negative:0;flex-shrink:0}
  .am-tabs-default-bar{width:100%;height:101.4%;overflow:visible;z-index:1}
  .render-list-wrap .am-tabs-default-bar-content{padding:0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}
  .am-tabs-default-bar-left .am-tabs-default-bar-content,.am-tabs-default-bar-right .am-tabs-default-bar-content{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;height:100%}
  .render-list-wrap .am-tabs-default-bar-content .am-tabs-default-bar-tab{border-bottom:none;margin-bottom: 4px;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;background: #fff;color: #0a0a0a;}
  .render-list-wrap .am-tabs-default-bar-tab{padding:0;margin-right: 4px;border-radius: 5px;}
  .am-tabs-default-bar-tab{-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;font-size:15px;height:43.5px;line-height:43.5px}
  .render-list-wrap .am-tabs-default-bar-content .active{background: linear-gradient(to right, #fa9602, #ffc408);color:#fff}
  .game_species{height:100%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;border-radius:0.1rem;overflow:hidden}
  .game_species img{width:0.64rem;height:0.64rem;vertical-align:middle;margin-left:0.2rem;vertical-align:0}
  .game_species span{font-size: 0.8rem;padding: 0 .8rem 0 .2rem;margin-left: 2.5rem;}
  .img_active2{display:none}
  .active .game_species .img_active2{display:block !important}
  .active .game_species .img_active1{display:none}
  .am-tabs-content-wrap{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;width:100%;height:100%}
  .game_img{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;height:100%;border-radius: 5px;}
  .am-tabs-pane-wrap{display:none;width:100%;overflow-y:auto}
  .game_img div,.game_img a{width:100%;height:100%;overflow:hidden;word-wrap:break-word}
  .game_img div.game_xz_75,.game_img a.game_xz_75{overflow-y: auto;}
  .game_img div img,.game_img a img{width:100%;height:100%;vertical-align:0}
  .game_xz{width:100%;height:100%;font-size:0}
  .game_xz .game_all{width: 49.2%;}
  .game_xz .game_six_img{margin-bottom: 4px;}
  .distance > div:nth-child(odd){margin-right: 1.5%;}
  .game_xz >div{float:left;width:100%;height: 8.8rem;overflow:hidden;word-wrap:break-word;margin-bottom: 3px;}
  .game_xz.game_xz_50 >div {height: 50%;}
  .game_xz.game_xz_75 >div {height: 8.3rem;}
  .game_xz .game_six_img:nth-child(5),.game_xz .game_six_img:nth-child(6){margin-bottom:0}
  .wrap-active{display:block}
  .game_one_img > div{float:left;width:100%;height:1.84rem;overflow:hidden;word-wrap:break-word}
  .game_one_img .game_all{width:49%}
  .qp_img_one{margin-right:2%}
  .am-tabs-default-bar-tab i{left: -.2rem;}
  .active.am-tabs-default-bar-tab i{background-position-x: -52px}
  i.fa-live {background-position: 0px -257px;}
  i.fa-lottery {background-position: 0px -307px;}
  i.fa-sport {background-position: 0px -358px;}
  i.fa-game {background-position: 0px -409px;}
  i.fa-chess {background-position: 0px -459px;}
  i.fa-dzjj {background-position: 0px -510px;}
  i.fa-fish {background-position: 0px -561px;}

  .login_box{margin:0 auto .8rem;color: #333;font-size: .9rem;}
  .login_box .login_box_sec{background:#eeeeee;box-shadow: 0 -1px 2px #fde9a8;border-bottom: 3px solid #fff;}
  .login_box .login_title{background:linear-gradient(to right,#ca9024 1%,#eeaf46 100%);color:#fff;text-align:left;padding:0 1.3rem;height: 2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden}
  .login_box .login_title span {display: inline-block;max-width: 40%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
  .login_box .login_txt{display:inline-block;width:6rem;height:2.3rem;background:url(/static/images/3366/logo_icon.png) no-repeat;background-size:100%}
  .login_box  .login_grzl{border:1px solid #fff;border-radius:7px;margin-left:1.5rem;padding:.2rem .8rem}
  .login_box .management{padding:.8rem 0;overflow:hidden}
  .login_box .wallet_center{display:inline-block;width:28%;padding-top:.5rem;float:left;border-right:1px dashed #bdbcbc}
  .login_box .font_Library span{color:#d89a37}
  .login_box .font_Library i{font-style:normal;color:#d89a37}
  .login_box .financial a{float:left;width:23%;margin-left:1%;background:#fff;border-radius:8px;padding:5px 5px 0}
  .login_box .financial a i{margin:-1.8rem -1.6rem;position:static}
  @media only screen and (max-width:320px){
    .login_box .financial a {margin-left: 0;transform: scale(.95);}
  }
  .login_box p{color: #333;}
  .login_box .financial .tip{color: #a7a5a5;font-size: .7em;}
  .login_box .financial i.fa-credit-card{background-position:2px -658px;}
  i.fa-zz {background-position: -52px -613px;}
  i.fa-withdrow {background-position: -52px -663px;}
  /* 轮播 */
  .home_container>>> .swiper-container-horizontal>.swiper-pagination-bullets,.home_container>>> .swiper-pagination-custom,.home_container>>> .swiper-pagination-fraction{bottom:-6px}
  .home_container>>> .swiper-pagination-bullet{border-radius:8px;width:2.5rem;height:10px;background:#dcdcdc;border:0;margin: 0 2px !important;}
  .home_container>>> .swiper-pagination-bullet-active{background:#ffa207}
</style>
