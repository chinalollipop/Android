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
             公告
          </span>
          <div class="text">
            <marquee> {{noticeMsg}} </marquee>
          </div>

        </div>
      </div>
      <div class="login_box">
        <div class="login_box_sec">
          <div class="login_title">
            <span>{{userName?'欢迎您，'+userName:'未登录'}}</span>
            <div class="right">
              <div class="login_txt left"></div>
              <router-link to="mydetail" class="login_grzl" >个人资料</router-link>
            </div>

          </div>
          <div class="management">
            <div class="wallet_center">
              <span class="font_Library"><i>¥</i><span class="hg_money">{{userMoney?userMoney:0}}</span> </span>
              <div class="wallet">中心钱包</div>
            </div>
            <div class="financial">
              <router-link to="deposit">
                <i class="index_fa fa-credit-card"></i>
                <p>存款</p>
              </router-link>
              <router-link to="tran">
                <i class="index_fa fa-zz"></i>
                <p>转账</p>
              </router-link>
              <router-link to="withdraw">
                <i class="index_fa fa-withdrow"></i>
                <p>取款</p>
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
                      <i class="index_fa fa-live"></i>
                      <span>真人视讯</span>
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
                      <i class="index_fa fa-sport"></i>
                      <span>体育竞技</span>
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
                      <i class="index_fa fa-game"></i>
                      <span>电子游戏</span>
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
                      <i class="index_fa fa-fish"></i>
                      <span>捕鱼游戏</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="am-tabs-content-wrap am-tabs-content-wrap-animated">

              <!--真人-->
              <div class="am-tabs-pane-wrap wrap-active">
                <div class="game_img">
                  <div class="game_xz distance game_xz_50 game_xz_75">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item<=2" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                    </div>
                    <div class="game_img " @click="$refs.autoDialog.setPublicPop('敬请期待')">
                      <img src="/static/images/newhg/index/index_ag_4.png" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--电子竞技-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_50">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item>2 && item<=4" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--体育-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" v-for="(lists,item) in gamesList" v-if="item==5" :key="item" >
                  <router-link :to="lists.path">
                    <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                  </router-link>
                </div>
              </div>

              <!--棋牌-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img">
                  <div class="game_xz distance">
                    <div class="game_img" :class="['vg','kl'].indexOf(lists.type)>=0 &&'game_all game_six_img'" v-for="(lists,item) in gamesList" v-if="item>5 && item<=9" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--老虎机-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_75">
                    <div class="game_img" :class="['aggame','mg','cq','mw'].indexOf(lists.type)>=0 &&'game_all game_six_img'" v-for="(lists,item) in gamesList" v-if="item>9 && item<=14" :key="item" >
                      <router-link :to="lists.path">
                        <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
              <!--彩票-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" >
                  <div class="game_xz distance game_xz_50">
                    <div class="game_img" v-for="(lists,item) in gamesList" v-if="item>14 && item<=16" :key="item" @click="openNewGame(lists.path,lists.type)" >
                      <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
                    </div>
                  </div>
                </div>
              </div>
              <!--捕鱼-->
              <div class="am-tabs-pane-wrap">
                <div class="game_img" v-for="(lists,item) in gamesList" v-if="item==17" :key="item" @click="openNewGame(lists.path,lists.type)" >
                  <div>
                    <img :src="'/static/images/newhg/index/'+lists.className+'.png'" alt="">
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
          { name:'乐游棋牌', className:'index_lyqp', type:'ly',path:'gameswin?action=cm&gametype=ly'},
          { name:'VG棋牌', className:'index_vgqp', type:'vg',path:'gameswin?action=cm&gametype=vg'},
          { name:'快乐棋牌', className:'index_klqp', type:'kl',path:'gameswin?action=cm&gametype=kl'},
          { name:'FG电子', className:'index_fg_game',type:'fg',path:'/games?gametype=fg&gamename=FG电子'},
          { name:'AG电子', className:'index_ag_game',type:'aggame',path:'/games?gametype=aggame&gamename=AG电子'},
          { name:'MG电子', className:'index_mg_game',type:'mg',path:'/games?gametype=mg&gamename=MG电子'},
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
  .home_container>>> .app_tip{background:url(/static/images/newhg/apptip/bg.jpg) center no-repeat;background-size:cover;text-align:left;padding:8px 0 5px 6%}
  .home_container>>> .app_tip .app_tip_logo{display:inline-block;width:3.5rem;height:3.5rem;background:url(/static/images/newhg/add-logo.png) center no-repeat;background-size:100%;margin-right:.8rem}
  .home_container>>> .app_tip div{display:inline-block;vertical-align:top}
  .home_container>>> .app_tip .title p{font-size:1.1rem}
  .home_container>>> .app_tip .title p:first-child{margin-top:.5rem}
  .home_container>>> .app_tip .download_btn{display:inline-block;background:#322f37;border-radius:20px;padding:3px 8px 4px;float:right;margin:1.3rem 7% 0 0}
  .home_container>>> .app_tip .download_btn span{display:inline-block;vertical-align:middle}
  .home_container>>> .app_tip .icon{width:1.5rem;height:1.5rem}
  .home_container>>> .app_tip .icon.and{background:url(/static/images/suncity/apptip/and.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .icon.ios{background:url(/static/images/suncity/apptip/ios.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(/static/images/newhg/suncity/close.png) center no-repeat;background-size:100%;position:absolute;right:1%;top:8px;}

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
  .render-list-wrap .am-tabs-default-bar-content .am-tabs-default-bar-tab{border-bottom:none;margin-bottom: 4px;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;background: #dbdbdb;color: #0a0a0a;}
  .render-list-wrap .am-tabs-default-bar-tab{padding:0;margin-right: 4px;border-radius: 5px;}
  .am-tabs-default-bar-tab{-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;font-size:15px;height:43.5px;line-height:43.5px}
  .render-list-wrap .am-tabs-default-bar-content .active{background: linear-gradient(to right, #ca9024 1%, #eeaf46 100%);color:#fff}
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
  .game_img div.game_xz_75{overflow-y: auto;}
  .game_img div img,.game_img a img{width:100%;height:100%;vertical-align:0}
  .game_xz{width:100%;height:100%;font-size:0}
  .game_xz .game_all{width: 49.2%;}
  .game_xz .game_six_img{margin-bottom: 4px;}
  .distance > div:nth-child(odd){margin-right: 1.5%;}
  .game_xz >div{float:left;width:100%;height: 8.8rem;overflow:hidden;word-wrap:break-word;margin-bottom: 4px;}
  .game_xz.game_xz_50 >div {height: 13.8rem;}
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

  .login_box{padding:0 8px;margin:0 auto .8rem}
  .login_box .login_box_sec{background:#dbdbdb;border-radius:5px}
  .login_box .login_title{background:linear-gradient(to right,#ca9024 1%,#eeaf46 100%);color:#fff;text-align:left;padding:0 1.3rem;height: 2.3rem;line-height:2.3rem;border-radius:5px 5px 0 0;overflow:hidden}
  .login_box .login_title span {display: inline-block;max-width: 40%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
  /*.login_box .login_txt{display:inline-block;width:6rem;height:2.3rem;background:url(/static/images/newhg/logo_icon.png) no-repeat;background-size:100%}*/
  .login_box  .login_grzl{border:1px solid #fff;border-radius:7px;margin-left:1.5rem;padding:.2rem .8rem}
  .login_box .management{padding:.8rem;overflow:hidden}
  .login_box .wallet_center{display:inline-block;width:35%;padding-top:.5rem;float:left;border-right:1px solid #464545}
  .login_box .font_Library{color:#d89a37}
  .login_box .wallet{color:#464545}
  .login_box .font_Library i{font-style:normal}
  .login_box .financial a{float:left;width:16%;margin-left:3%}
  .login_box .financial a i{margin:-.8rem -1.6rem;position:static}
  .login_box .financial p{color:#d89a37}
  .login_box .financial i.fa-credit-card{background-position:-52px -52px;transform:scale(.75)}
  i.fa-zz {background-position: -52px -672px;}
  i.fa-withdrow {background-position: 0 -672px;}
  .home_container>>> .app_download{display: inline-block;float: left;margin-left: 3%;font-size: 1rem;}
  .home_container>>> .app_download i.index_fa{display:block;background:url(/static/images/suncity/app_icon.png) top left no-repeat;background-size:84%;width:2.8rem;margin:-.2rem -.3rem}
  .home_container>>> .app_download p {margin-top: 1.1rem;}

</style>
