<template>
  <div>
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <!-- 轮播 -->
    <div class="carousel swiper-container" >
      <div class="swiper-wrapper">
        <div class="swiper-slide" v-for="(banner,item) in bannerData" :key="item">
          <router-link v-if="banner.name.indexOf('promo')>=0" :to="'promo?prokey='+(banner.name.split('?')[1]?(banner.name.split('?')[1]+'#promos_id_'+banner.name.split('?')[1]):'')"> <!-- 优惠 -->
            <img :src="banner.img_path" class="swiper-lazy" alt="">
          </router-link>
          <router-link v-else-if="banner.name.indexOf('lives_upgraded')>=0" :to="'upgraded?game_Type='+(banner.name.split('?')[1]?(banner.name.split('?')[1]):'')"> <!-- 升级 -->
            <img :src="banner.img_path" class="swiper-lazy" alt="">
          </router-link>
          <router-link v-else-if="banner.name.indexOf('index')>=0" to="/"> <!-- 首页 -->
            <img :src="banner.img_path" class="swiper-lazy" alt="">
          </router-link>
          <router-link v-else :to="banner.name">
            <img :src="banner.img_path" class="swiper-lazy" alt="">
          </router-link>
        </div>
      </div>
      <!-- 如果需要分页器 -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</template>

<script>
import '../../../static/css/swiper-3.4.2.min.css'
import '../../../static/js/swiper-3.4.2.jquery.min.js'

//import axios from 'axios'
import Mixin from '@/Mixin'
import Dialog from '@/components/Dialog'

export default {
  name: 'index',
    mixins:[Mixin],
    components: {
        Dialog
    },
  data () {
    return {
      bannerData:[],
      indexGameflag: true, // 是否滚动
      baseNum:12, // 基数
      gameNum:[0,1,3,5,6,10], // 每种分类对应的游戏数量
    }
  },
    beforeDestroy(){

    },
    mounted: function () {
        let _self = this ;

        _self.openNewGame(_self.$route.path,'mobile','no'); // 网站维护

        _self.alertMemberMsg();

        _self.getIndexData('banner');
        if(_self.userName){
            if(_self.tplnameList.indexOf(_self.tpl_name)>=0){ // 体育彩票
                _self.doLotteryLogin();
            }

        }else{ // 未登录
            _self.getBaseSetting();
        }
        // 首页游戏滚动
        if(_self.tpl_name =='6668/' || _self.tpl_name =='8msport/'){ // 设置首页高度
            if(_self.tpl_name =='8msport/'){
                _self.gameNum = [0,2,4,6,8,12]; // 每种分类对应的游戏数量
                _self.baseNum = 4;
            }
            $('.home_container').css({'height':window.innerHeight});
            _self.setGameSwiper();
            _self.addIndexScrollTop();
        }

    },
    methods:{
      /* 轮播初始化
      * */
      initBnanner:function () {
          // 滚动轮播配置
          let bannerSwiper = new Swiper ('.swiper-container', {
              direction: 'horizontal',
              observer: true, // 没有此参数有问题
              autoHeight: true,
              loop: true,
              autoplay : 5000,
              autoplayDisableOnInteraction:false, // 手动切换后继续自动切换
              // 如果需要分页器
              pagination: '.swiper-pagination'
          });
      },
      /* 获取轮播,获取公告 action:'index' */
        getIndexData:function (type) {
            let _self = this ;
            let url = _self.ajaxUrl.notice;
            let pars = {action:'index'};
            if(type=='banner'){
                url = _self.ajaxUrl.banner;
                pars ='';
            }
            if(_self.tpl_name=='0086dj/' && type=='banner'){
              _self.bannerData = [
                  {"id":"1","title":"轮播1","name":"login","img_path":"/static/images/0086dj/index/banner_1.jpg"},
                  {"id":"2","title":"轮播2","name":"login","img_path":"/static/images/0086dj/index/banner_2.jpg"},
                  {"id":"3","title":"轮播3","name":"login","img_path":"/static/images/0086dj/index/banner_3.jpg"},
              ];
                _self.$nextTick(()=>{
                    _self.initBnanner();
                })
              return ;
            }

                _self.axios({
                    method: 'post',
                    params: pars,
                    url: url
                }).then(res=>{
                    if(res){
                        let rest = res.data;
                        if(type=='banner'){
                            _self.bannerData = rest.data;
                            _self.$nextTick(()=>{
                                _self.initBnanner();
                            })

                        }else{
                          if(rest.data.notice){
                            _self.$parent.noticeMsg = rest.data.notice;
                          }
                        }
                    }
                }).catch(res=>{
                    console.log('轮播,公告请求失败');
                });


        },

      /* 会员弹窗信息 */
        alertMemberMsg: function () {
            let _self = this;
            let getmsg = _self.localStorageGet('mymsg') ;
            let memmsg= (_self.memberData?_self.memberData.membermessage.mem_message:''); // 会员弹窗短信
            if(_self.userName && memmsg && getmsg <1){ // 有会员短信,只弹出一次
                _self.localStorageSet('mymsg',3) ;
                _self.$refs.autoDialog.setPublicPop(memmsg,'','',10000);
            }
        },

        // 体育彩票登录处理，非三方彩票
        doLotteryLogin:function () {
          let cpUrlArr = JSON.parse(localStorage.getItem('cpUrlArr')) || new Array() ;
          let newcpUrlArr = new Array() ;
          let str = '';
          if(cpUrlArr.cp_url_num==1){ // 首次加载
              newcpUrlArr = {
                  cp_login:cpUrlArr.cp_login,
                  cp_url:cpUrlArr.cp_url,
                  cp_url_num:2
              }
              localStorage.setItem('cpUrlArr',JSON.stringify(newcpUrlArr)) ; // 彩票登录地址
              str +='<iframe name="cp_login_url" id="cp_login_url" scrolling="NO" noresize src="'+ cpUrlArr.cp_url +'" style="display: none;"></iframe>';
              $('body').append(str);
          }
      },
        // 首页游戏切换
        changeGameTag:function () {
          $('.am-tabs-default-bar-content .am-tabs-default-bar-tab').click(function () {
              var i = $(this).index();
              //console.log(i)
              $(this).addClass('active').siblings().removeClass('active');
              $('.am-tabs-content-wrap .am-tabs-pane-wrap').eq(i).newshow(300).siblings().newhide(300);

          })
      },

      // 首页游戏滚动轮播配置
        setGameSwiper:function () {
          let _self = this;
          var $game_nav_on = $('.game_nav_on');
          //给每个页码绑定跳转的事件
          $('.swiper-pagination-game').on('click','li',function(){
              _self.indexGameflag = false;
              var index = $(this).index();
              var gameDivH = $('.Menual').height(); // 每个游戏标签高度
              // console.log(gameDivH)
              var scrTop = 0;
              if(index==0 || index==1){ // 体育和真人
                  scrTop = _self.baseNum*_self.gameNum[index] + _self.gameNum[index]*gameDivH;
              }else{
                  scrTop = _self.baseNum*_self.gameNum[index] + (_self.gameNum[index]+1)*gameDivH;
              }

              $(this).addClass('active').siblings().removeClass('active');
              var on_left = parseInt($('.swiper-pagination-game').find('.active').position().left)+4;
              if(index>0){
                  $game_nav_on.css({'transform':'translate3d('+on_left+'px, 0px, 0px)'});
              }else{
                  $game_nav_on.css({'transform':'translate3d(0px, 0px, 0px)'});
              }
              // console.log(scrTop)
              // $('.gameListAll').css({'transform':'translate3d(0px, -'+scrTop+'px, 0px)'});
              $('.middle_content').scrollTop(scrTop);

          });
      },

      // 监听滚动
        addIndexScrollTop:function () {
          let _self = this;
          var gameDivH = $('.Menual').height(); // 每个游戏标签高度
          var $gameLi = $('.swiper-pagination-game li');
          var $game_nav_on = $('.game_nav_on');
          var $middle_content = document.querySelector('.middle_content');
          $middle_content.addEventListener('touchstart', handler, { passive: false });
          $middle_content.addEventListener('scroll', handler, { passive: false }); // 滚动监听

          function handler(e) {
              switch (e.type) {
                  case 'touchstart':
                      _self.indexGameflag = true;
                      break;
                  case 'scroll':
                      if (!_self.indexGameflag) {
                          return;
                      }
                      $('.gameListAll').removeAttr('style');
                      var scrollH = this.scrollTop ; // 滚动高度
                      //console.log(scrollH)
                      for(var i=0;i<_self.gameNum.length;i++){
                          if(i==0 || i==1){ // 真人
                              if(scrollH > _self.baseNum*_self.gameNum[i] + _self.gameNum[i]*gameDivH ){
                                  $gameLi.eq(i).addClass('active').siblings().removeClass('active');
                              }
                          }else{
                              if(scrollH > _self.baseNum*_self.gameNum[i] + (_self.gameNum[i]+1)*gameDivH ){
                                  $gameLi.eq(i).addClass('active').siblings().removeClass('active');
                              }
                          }

                      }
                      var on_left = parseInt($('.swiper-pagination-game').find('.active').position().left)+4;
                      if($('.swiper-pagination-game').find('.active').index() ==0){
                          $game_nav_on.css({'transform':'translate3d(0px, 0px, 0px)'});
                      }else{
                          $game_nav_on.css({'transform':'translate3d('+on_left+'px, 0px, 0px)'});
                      }
                      break;
              }

          }

      }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
