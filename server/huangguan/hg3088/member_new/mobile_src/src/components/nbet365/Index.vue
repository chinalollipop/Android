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
          <i class="notice-icon fa fa-volume-up">

          </i>
          <div class="text">
            <marquee>
              {{noticeMsg}}
            </marquee>
          </div>
        </div>
      </div>

      <!-- 游戏列表轮播 -->

      <!-- 如果需要分页器 -->
      <div class="game-page-all">
        <div class="swiper-pagination-game css_flex"></div>
      </div>

      <div class="carousel swiper-container-game" >
        <div class="swiper-wrapper">
          <!-- 热门 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <a class="game-list" :class="lists.className" :key="item" v-if="lists.hot=='yes' && gametypeList.indexOf(lists.type)>=0" @click="openNewGame(lists.path,lists.type)">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>
              <router-link class="game-list" :to="lists.path" :key="item" v-else-if="lists.hot=='yes'" >
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </router-link>
            </template>

          </div>
          <!-- 体育 电竞 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <router-link class="game-list" :to="lists.path" :key="item" v-if="item<=2 && lists.type=='hg'">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </router-link>
              <a class="game-list" :key="item" v-else-if="item<=2" @click="openNewGame(lists.path,lists.type)">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>

            </template>
          </div>
          <!-- 彩票 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <a class="game-list" :class="lists.className" :key="item" v-if="item>2 && item<=4" @click="openNewGame(lists.path,lists.type)">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>
            </template>
          </div>
          <!-- 视讯 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <a class="game-list" :key="item" v-if="item>4 && item<=7" @click="openNewGame(lists.path,lists.type)">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>
            </template>
          </div>
          <!-- 电子 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <a class="game-list" :key="item" v-if="item>7 && item<=13 && lists.type=='agby'" @click="openNewGame(lists.path,lists.type)">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>
              <router-link class="game-list" :to="lists.path" :key="item" v-else-if="item>7 && item<=13">
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </router-link>
            </template>
          </div>
          <!-- 棋牌 -->
          <div class="swiper-slide">
            <template v-for="(lists,item) in gamesList">
              <a class="game-list" :key="item" v-if="item>13 && item<=17" @click="openNewGame(lists.path,lists.type)" >
                <span class="game-icon" :class="lists.className" :style="{backgroundImage: 'url(/static/images/bet365/index/'+lists.className+'.png)'}"></span>
                <div class="right list-right">
                  <span class="hot"></span>
                  <span class="text">{{lists.name}}</span>
                </div>
              </a>
            </template>
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
          { name:'官方体育', className:'hg_s',hot:'yes', type:'hg',path:'sport'},
          { name:'雷火电竞', className:'lh',hot:'not',type:'fire', path:'gameswin?action=getLaunchGameUrl&gametype=fire'},
          { name:'泛亚电竞', className:'fy',hot:'yes', type:'avia',path:'gameswin?action=getLaunchGameUrl&gametype=avia'},
          { name:'天天彩票', className:'tt',hot:'yes', type:'cp',path:'gameswin?gametype=cp'},
          { name:'官方玩法', className:'gfcp',hot:'not', type:'cp',path:'gameswin?gametype=cp'},
          { name:'AG视讯', className:'ag_ls',hot:'yes', type:'ag',path:'gameswin?gametype=ag'},
          { name:'OG视讯', className:'og',hot:'yes', type:'og',path:'gameswin?action=getLaunchGameUrl&gametype=og'},
          { name:'BBIN视讯', className:'bb',hot:'not', type:'bbin',path:'gameswin?action=getLaunchGameUrl&gametype=bbin'},
          { name:'AG电子', className:'ag_ls',hot:'not', type:'aggame',path:'/games?gametype=aggame&gamename=AG电子'},
          { name:'MG电子', className:'mg',hot:'yes', type:'mg',path:'/games?gametype=mg&gamename=MG电子'},
          { name:'FG电子', className:'fg',hot:'not', type:'fg',path:'/games?gametype=fg&gamename=FG电子'},
          { name:'MW电子', className:'mw',hot:'yes', type:'mw',path:'/games?gametype=mw&gamename=MW电子'},
          { name:'CQ9电子', className:'cq',hot:'not', type:'cq',path:'/games?gametype=cq&gamename=CQ9电子'},
          { name:'捕鱼王', className:'by',hot:'yes', type:'agby',path:'gameswin?game_id=6&gametype=agby'},
          { name:'开元棋牌', className:'ky',hot:'yes', type:'ky',path:'gameswin?action=cm&gametype=ky'},
          { name:'VG棋牌', className:'vg',hot:'yes', type:'vg',path:'gameswin?action=cm&gametype=vg'},
          { name:'乐游棋牌', className:'le',hot:'not', type:'ly',path:'gameswin?action=cm&gametype=ly'},
          { name:'快乐棋牌', className:'kl',hot:'not', type:'kl',path:'gameswin?action=cm&gametype=kl'},
          { name:'优惠活动', className:'yh',hot:'yes', type:'yh',path:'promo'},
          { name:'APP下载', className:'dl',hot:'yes', type:'app',path:'appdownload'}
  ]
    }
  },
    mounted: function () {
        let _self = this ;

        _self.$refs.indexBanner.getIndexData(); // 获取公告

        _self.$nextTick(()=>{
            _self.initIndexBnanner();
        });
    },
    methods:{

      /* 首页游戏初始化
       * */
        initIndexBnanner:function () {
            // 滚动轮播配置
            let bannerSwiper = new Swiper ('.swiper-container-game', {
                direction: 'horizontal',
                observer: true, // 没有此参数有问题
                autoHeight: true,
                loop: true,
                autoplay : false,
                autoplayDisableOnInteraction:false, // 手动切换后继续自动切换
                // 如果需要分页器
                pagination: '.swiper-pagination-game',
                paginationClickable: true,
                paginationBulletRender: function (swiper, index, className) {
                    var text = '';
                    switch (index){
                        case 0:
                            text = '热门';
                            break;
                        case 1:
                            text = '体育';
                            break;
                        case 2:
                            text = '彩票';
                            break;
                        case 3:
                            text = '视讯';
                            break;
                        case 4:
                            text = '电子';
                            break;
                        case 5:
                            text = '棋牌';
                            break;
                    }
                    return '<a class="swiper-pagination-custom ' + className + '">' + (text) + '</a>';
                }

            });
        },
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .home_container{background:rgb(242,246,244);min-height: 100vh; }
  .home_container>>> .header .header_logo{display: inline-block;}
  /* app 下载提示 */
  .home_container>>> .app_tip{background:url(/static/images/bet365/apptip/bg.png) center no-repeat;background-size:cover;text-align:left;padding:8px 0 5px 6%}
  .home_container>>> .app_tip .app_tip_logo{display:inline-block;width:3.5rem;height:3.5rem;background:url(/static/images/bet365/add-logo.png) center no-repeat;background-size:100%;margin-right:.8rem}
  .home_container>>> .app_tip div{display:inline-block;vertical-align:top}
  .home_container>>> .app_tip .title p{font-size:1rem;color: #fff;}
  .home_container>>> .app_tip .title p:first-child{margin-top:.5rem;background: url(/static/images/bet365/apptip/title.png) left center no-repeat;background-size: 66%;text-indent: -99999px;}
  .home_container>>> .app_tip .download_btn{display:inline-block;background: #ffff00;border-radius:20px;padding:3px 8px 4px;float:right;margin:1rem 7% 0 0;color: #06a165}
  .home_container>>> .app_tip .download_btn span{display:inline-block;vertical-align:middle}
  .home_container>>> .app_tip .icon{width: 1.5rem;height: 1.8rem;}
  .home_container>>> .app_tip .icon.and{background:url(/static/images/bet365/apptip/and.png) center no-repeat;background-size:100%}
  .home_container>>> .app_tip .icon.ios{background:url(/static/images/bet365/apptip/ios.png) center no-repeat;background-size: 90%;}
  .home_container>>> .app_tip .app_close{display:inline-block;width:1.4rem;height:1.4rem;background:url(/static/images/bet365/apptip/close.png) center no-repeat;background-size:100%;position:absolute;right:1%;top:8px;}

  /* 分类 */
  .home_container>>> .swiper-pagination-game a{color:#333;flex: auto;-webkit-flex: auto;height: 4rem;line-height: 2.2rem;border-color: transparent;border-bottom: 1px solid #ddd;margin: 0 !important;border-radius: 0;}
  .home_container>>> .swiper-pagination-game a.swiper-pagination-bullet-active{background-color: rgba(255,255,255,0.5);border-top-left-radius: 5px;border-top-right-radius: 5px;border: 1px solid #ddd;border-bottom-color: transparent;}
  .home_container>>> .swiper-pagination-game a:before {
    display: block;text-shadow: 1px 1px 3px #bbb;color: #F16346;
    font: normal normal normal 14px/1 FontAwesome; content: "";background:url(/static/images/bet365/index/icon/icon_1.png);background-size: 50%;background-position: center;background-repeat: no-repeat;width: 3.6rem;height: 1.8rem;margin: 3px auto 0;
  }
  .home_container>>> .swiper-pagination-game a:nth-child(2):before{background-image: url(/static/images/bet365/index/icon/icon_2.png);}
  .home_container>>> .swiper-pagination-game a:nth-child(3):before{background-image: url(/static/images/bet365/index/icon/icon_3.png);}
  .home_container>>> .swiper-pagination-game a:nth-child(4):before{background-image: url(/static/images/bet365/index/icon/icon_4.png);}
  .home_container>>> .swiper-pagination-game a:nth-child(5):before{background-image: url(/static/images/bet365/index/icon/icon_5.png);}
  .home_container>>> .swiper-pagination-game a:nth-child(6):before{background-image: url(/static/images/bet365/index/icon/icon_6.png);}
  .game-page-all{margin-top: 2.9rem;}
  .swiper-container-game{margin: 5px;text-align: left;overflow: hidden;}
  .swiper-container-game a{color:#333;display: inline-block;width: 49%;height:5rem;line-height:5rem;background-color: rgba(0,0,0,0.03);padding: 5px 0;border-radius: 5px;border: 1px solid #ccc;margin-top: 7px;text-align: left;}
  .swiper-container-game a:nth-child(2n){margin-left: 6px;}
  .swiper-container-game .game-icon{display: inline-block;width: 6rem;max-width:150px;height: 100%;background-size: 100%;background-position:center; }
  .swiper-container-game .list-right{height: 100%;padding-right: 10px;}
  .swiper-container-game a.gfcp{display: none}

  /* 轮播 */
  .home_container>>> .swiper-pagination-bullet{border-radius:8px;width:10px;height:10px;background:none;border:1px solid rgb(178,202,194);margin: 0 2px !important;}
  .home_container>>> .swiper-pagination-bullet-active{background:rgb(178,202,194)}
</style>
