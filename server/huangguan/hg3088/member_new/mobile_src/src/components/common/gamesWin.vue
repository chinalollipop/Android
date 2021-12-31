<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney" v-show="curtypeList.indexOf(gametype)>=0"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />
    <div>
      <!-- 棋牌游戏走这里 -->
      <iframe id="gameiframe" class="gameiframe" ref="myiframe" scrolling="no" frameborder="0"  allowfullscreen="true"></iframe>
    </div>
  </div>
</template>

<script>

import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
    name: 'gameswin',
    mixins: [Mixin],
    components: {
        HeaderNav,
        Dialog
    },
    data () {
        return {
            action:'',
            game_id:'',
            gametype:'',
            cp_type:''
        }
    },
    mounted: function () {
        let _self = this;
        _self.judgeTestFlag();
        _self.getUserMoney(); // 获取余额

        _self.$refs.myiframe.height = document.documentElement.clientHeight; // 初始化窗口高度

        _self.action = _self.$route.query.action?_self.$route.query.action:''; // 获取参数
        _self.game_id = _self.$route.query.game_id?_self.$route.query.game_id:''; // 获取参数
        _self.gametype = _self.$route.query.gametype; // 获取参数
        _self.cp_type = _self.$route.query.cp_type?this.$route.query.cp_type:''; // 获取参数, 信用盘就传 1，官方盘不传

        _self.getGameUrl();
    },
    methods: {
      /* 进入游戏 */
      getGameUrl:function () {
          let _self =this;
          let url= _self.ajaxUrl.aggame; // 默认 AG, AG 视讯，AG 捕鱼，AG 电子
          let curwin = '';// 是否保持在当前路由，棋牌需要
          if(_self.curtypeList.indexOf(_self.gametype) >= 0){
              curwin = 'yes';
          }
          let pars= {
              type: 'mobile',
              action: _self.action,
              game_id: _self.game_id,
              gametype: _self.gametype
          };
          switch (_self.gametype){
              case 'cp': // 彩票不需要再去请求接口
              case 'gmcp': // toXinyong:是否跳转到信用盘,type: 不传即是默认官方，要到信用就传 1
                  let cpUrlArr = JSON.parse(localStorage.getItem('cpUrlArr')) || new Array() ;
                  let tz_url = cpUrlArr.cp_login;
                  if(_self.gametype=='gmcp'){
                      tz_url += '&toXinyong='+_self.cp_type;
                  }
                  window.location.href = tz_url; // 跳转游戏链接
                  return false;
                  break;
              case 'mg':
                  url = _self.ajaxUrl.mgapi;
                  break;
              case 'fg':
                  url = _self.ajaxUrl.fgapi;
                  break;
              case 'mw':
                  url = _self.ajaxUrl.mwapi;
                  break;
              case 'cq':
                  url = _self.ajaxUrl.cqapi;
                  break;
              case 'ky':
                  url = _self.ajaxUrl.kyqpapi;
                  break;
              case 'ly':
                  url = _self.ajaxUrl.lyqpapi;
                  break;
              case 'vg':
                  url = _self.ajaxUrl.vgqpapi;
                  break;
              case 'kl':
                  url = _self.ajaxUrl.klqpapi;
                  break;
              case 'og':
                  url = _self.ajaxUrl.ogapi;
                  break;
              case 'bbin':
                  url = _self.ajaxUrl.bbinapi;
                  break;
              case 'avia':
                  url = _self.ajaxUrl.aviaapi;
                  break;
              case 'fire':
                  url = _self.ajaxUrl.fireapi;
                  break;
          }
         // return new Promise((resolve, reject)=>{
              _self.axios({
                  method: 'post',
                  params: pars,
                  url: url
              }).then(res=>{
                  if(res){
                      let rest = res.data;
                      let tourl = ''; // 游戏链接
                      if(rest.status ==200){
                          if(rest.data[0]){
                              tourl = rest.data[0].toUrl;
                          }else{
                              tourl = rest.data.url;
                          }
                          if(tourl){
                              if(curwin){ // 棋牌游戏
                                  _self.$refs.myiframe.src = tourl;
                              }else{
                                  window.location.href = tourl; // 跳转游戏链接
                              }

                          }else{
                              _self.$refs.autoDialog.setPublicPop('链接为空');
                          }

                      }else{
                          _self.$refs.autoDialog.setPublicPop(rest.describe);
                      }
                     // resolve();
                  }
              }).catch(res=>{
                  console.log('游戏链接请求失败');
                 // reject(res);
              });

         // })
      }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
