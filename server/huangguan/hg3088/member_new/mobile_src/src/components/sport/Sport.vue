<template>
  <div >

      <!-- 顶部导航栏 -->
      <div class="header sport_header">
          <div class="header_left">
              <!--<router-link v-if="showmore_dis" to="/" class="back-active icon-back">&nbsp;&nbsp;返回</router-link>-->
              <router-link to="/" class="back-active sport_back_icon" ></router-link>
              <div class="wel">
                  您好<p id="acc_username" class="acc_username">{{memberData.test_flag==0?userName:'试玩玩家'}}</p>
              </div>
          </div>
          <div class="header-right">
              <span class="rmb_color">RMB</span> <p class="hg_money mon_color after_login"> {{userMoney}} </p>
          </div>
      </div>

      <!-- 中间部分 -->
      <div class="content-center sport-content-center">
          <div class="tab">
              <div class="sport-nav">
                  <a v-show="showtype=='rb'|| showtype==''" href="javascript:;" data-action="1" class="item " >
                      <span>滚球赛事</span>
                  </a>
                  <div v-show="showtype=='rb'" class="sport_expand sport_expand_1">
                      <ul>
                          <li class="football2">
                              <a @click="openNewGame('/sportlist?gtype=FT&showtype=RB&sorttype=league','rb')">
                                  <span class="football-r-icon"></span>
                                  <span class="text">足球({{FT_Running_Num}})</span>

                              </a>
                          </li>
                          <li class="basketball2_rb">
                              <a @click="openNewGame('/sportlist?gtype=BK&showtype=RB&sorttype=league','rb')">
                                  <span class="basketball-r-icon"></span>
                                  <span class="text">篮球/美式足球({{BK_Running_Num}})</span>
                              </a>
                          </li>
                      </ul>
                  </div>
                  <a v-show="showtype=='today'|| showtype==''" href="javascript:;" data-action="2" class="item today_item" >
                      <span>今日赛事</span>
                  </a>
                  <div v-show="showtype=='today'|| showtype==''" class="sport_expand sport_expand_2" >
                      <ul>
                          <li class="football1">
                              <a @click="openNewGame('/sportlist?gtype=FT&showtype=FT&sorttype=league','today')">
                                  <span class="football-icon"></span>
                                  <span class="text">足球({{FT_Today_Num}})</span>

                              </a>
                          </li>
                          <li class="basketball1">
                              <a @click="openNewGame('/sportlist?gtype=BK&showtype=FT&sorttype=league','today')">
                                  <span class="basketball-icon"></span>
                                  <span class="text">篮球/美式足球({{BK_Today_Num}})</span>

                              </a>
                          </li>
                      </ul>
                  </div>
                  <a v-show="showtype=='future'|| showtype==''" href="javascript:;" data-action="3" class="item ">
                      <span>早盘赛事</span>
                  </a>
                  <div v-show="showtype=='future'" class="sport_expand sport_expand_3" >
                      <ul>
                          <li class="football1">
                              <a @click="openNewGame('/sportlist?gtype=FT&showtype=FU&sorttype=league','future')">
                                  <span class="football-icon"></span>
                                  <span class="text">足球({{FT_Future_Num}})</span>
                              </a>
                          </li>
                          <li class="basketball1">
                              <a @click="openNewGame('/sportlist?gtype=BK&showtype=FU&sorttype=league','future')">
                                  <span class="basketball-icon"></span>
                                  <span class="text">篮球/美式足球({{BK_Future_Num}})</span>
                              </a>
                          </li>
                      </ul>
                  </div>
              </div>
              <div class="sport-bottom">
                  <div class="selection_HK_box">
                      <!--<span class="selection_HK">
                          <select id="header_odds" name="header_odds" class="HK_dropdown">
                              <option value="H">香港盘</option>
                              <option value="M">马来盘</option>
                              <option value="I">印尼盘</option>
                              <option value="E">欧洲盘</option>
                          </select>
                      </span>-->
                      <router-link to="gameresult" class="sport_result">赛果</router-link>
                      <router-link to="sportroul" class="sport_rule">体育规则</router-link>
                  </div>

              </div>
          </div>
      </div>

    <FooterNav class="sport_footer" />
  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import FooterNav from '@/components/Footer'

export default {
    name: 'sport',
    mixins: [Mixin],
    components: {
        FooterNav
    },
    data () {
        return {
            showtype:'',
            FT_Running_Num:0,
            FT_Today_Num:0,
            BK_Running_Num:0,
            BK_Today_Num:0,
            FT_Future_Num:0,
            BK_Future_Num:0,
        }
    },
    mounted: function () {
        let _self = this;
        _self.userMoney = _self.localStorageGet('member_money');

        _self.showtype = _self.$route.query.showtype?_self.$route.query.showtype:''; // 获取参数

        _self.changeSportTab();
        _self.getBallNum();

    },
    methods: {
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
                     _self.FT_Running_Num = rest.data.FT_Running_Num;
                     _self.FT_Today_Num = rest.data.FT_Today_Num;
                     _self.BK_Running_Num = rest.data.BK_Running_Num;
                     _self.BK_Today_Num = rest.data.BK_Today_Num;
                     _self.FT_Future_Num = rest.data.FT_Future_Num;
                     _self.BK_Future_Num = rest.data.BK_Future_Num;
                     resolve();
                 }
             }).catch(res=>{
                 console.log('球数量请求失败');
                 reject(res);
             });

         })
     },
        // 标签切换
    changeSportTab:function () {
        $('.sport-nav').on('click','.item',function () {
            var act = $(this).data('action') ;
            var has = document.getElementsByClassName('sport_expand_'+act)[0].style.display ;
            $('.sport_expand ').hide() ;
            // console.log(has)
            if(has=='none'){
                $('.sport_expand_'+act).show() ;
            }else{
                $('.sport_expand_'+act).hide() ;
            }

        }) ;
    }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .selection_HK{display:inline-block;width:48.5%;height:3.57rem;line-height:3.57rem;clear:both;position:relative}
    .selection_HK:before{content:"";position:absolute;top:1rem;left:53px;display:inline-block;width:24px;height:24px;opacity:0.8;background:url(/staic/images/arrow_godown.svg) no-repeat center center;-webkit-transform:rotate(-180deg);-moz-transform:rotate(-180deg);transform:rotate(-180deg)}
    .selection_HK_box a{display: inline-block;width: 48%;height: 3.57rem;line-height: 3.57rem;color:rgba(255,255,255,0.72);padding-left: 1%;}
    .sport_rule {float: right; border-left: 1px solid #726156;}
    .HK_dropdown option{color: #818181;}
</style>
