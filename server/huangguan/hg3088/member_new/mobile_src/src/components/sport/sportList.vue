<template>
  <div >

      <!-- 顶部导航栏 -->
      <div class="header sport_header">
          <div class="header_left">
              <router-link to="sport" class="back-active sport_back_icon" ></router-link>
          </div>
          <div class="header-center">
              <router-link to="sport?showtype=rb" class="header_live" :class="showtype=='RB' && 'active'" data-type="RBMATCH" ><i class="rb_running_logo"></i>滚球</router-link>
              <router-link to="sport?showtype=today" class="header_today" :class="(showtype=='FT' || showtype=='BK') && 'active'" data-type="TODAYMATCH" >今日</router-link>
              <router-link to="sport?showtype=future" class="header_early" :class="(showtype=='FU' || showtype=='BU') && 'active'" data-type="FUTUREMATCH" >早盘</router-link>
          </div>
          <div class="header-right" >
              <span class="menu_icon" data-num="1" @click="checkAction"> </span>
          </div>
      </div>

      <!-- 中间部分 -->
      <div class="content-center sport-content-center">

          <!-- 下拉菜单 -->
          <div v-show="!checkStatus" class="subaccountform_menu">

              <div class="menu_user">
                  <div class="float_left user_2">{{userName}}</div>
                  <div class="dropdown_sub_right float_right">
                      RMB
                      <span id="acc_credit" name="acc_credit" class="curr_amount_2">{{userMoney}} </span>
                      <span id="curr_reload" class="float_right curr_reload "></span>
                  </div>
              </div>

          </div>

          <!-- 导航切换 -->
          <div class="sportNav">
              <div class="sportNav_tip">
                  <div class="ul">
                      <a @click="getLeagueMatches('FT',showtype,sorttype,mdate,tiptype)"><span class="football-icon"></span><p>足球</p></a>
                      <a @click="getLeagueMatches('BK',showtype,sorttype,mdate,tiptype)"><span class="basketball-icon"></span><p>篮球</p></a>
                      <router-link to="gameresult"><span class="gameresult-icon"></span><p>赛果</p></router-link>
                      <router-link to="/"><span class="live-icon"></span><p>真人荷官</p></router-link>

                    <template v-if="tpl_name !='0086dj/'">
                      <router-link to="/"><span class="games-icon"></span><p>老虎机</p></router-link>
                      <a v-if="tplnameList.indexOf(tpl_name)>=0" @click="openNewGame('gameswin?gametype=cp','cp')"><span class="lottery-icon"></span><p>彩票</p></a>
                      <a v-else @click="openNewGame('gameswin?gametype=gmcp','gmcp')"><span class="lottery-icon"></span><p>彩票</p></a>
                      <a @click="openNewGame('gameswin?action=cm&gametype=ky','ky')"><span class="chess-icon-ky"></span><p>开元棋牌</p></a>
                    </template>
                  </div>
              </div>
              <div class="sportNav_title">
                  <div id="title_gtype" name="title_gtype" class="game_title">{{gtype=='FT'?'足球':'篮球'}}</div>
                  <div id="refresh" class="refresh" @click="getLeagueMatches(gtype,showtype,sorttype,mdate,tiptype)">
                      <span id="refresh-btn"> </span>
                  </div>
              </div>


          </div>
          <!-- 内容区域 -->
          <div class="dport-content">

              <!-- 有赛事 容器-->
              <div class="has_sport_matches" >
                  <!-- 标题栏-->
                  <div v-if="showtype !='RB'" class="hdp_header"> <!--滚球不展示-->
                      <table border="0" cellspacing="0" cellpadding="0" class="tool_table">
                          <tbody>
                          <tr>
                              <td id="change_r" class="h_r" :class="tiptype==''?'hdp_up':''" data-type="ALLMATCH" @click="changeSportMatches($event,gtype,'',showtype)">让球 &amp; 大小</td>
                              <td id="change_p" class="h_p " :class="tiptype=='p3'?'hdp_up':''" data-type="P3MATCH" @click="changeSportMatches($event,gtype,'',showtype)" >综合过关</td>
                              <td id="change_fs"  class="h_fs " :class="tiptype=='champion'?'hdp_up':''" data-type="CHAMPION" @click="changeSportMatches($event,gtype,'',showtype)">冠军</td>
                          </tr>
                          </tbody>
                      </table>
                  </div>


                  <!-- 联赛选择 -->
                  <div class="selection" :class="(showtype=='FU' || showtype=='BU' || tiptype=='p3')?'selection_future':''">
                      <!-- 日期赛事选择 -->
                      <!-- 早盘才有日期 -->
                      <select v-if="showtype=='FU' || showtype=='BU' || tiptype=='p3'" class="sportsdropdown" id="time_sel_sort" name="mdate" @change="changeSportAll" v-model="mdate">
                          <option value="" >全部日期</option>
                          <option v-for="(datelist,index) in FuWuTimeData.half" :key="index" :value="datelist.value"> {{datelist.str}} </option>
                      </select>


                      <select class="sportsdropdown" id="sel_sort" name="sorttype" @change="changeSportAll" v-model="sorttype">
                          <option value="league">联盟排序</option>
                          <option value="time">时间排序</option>
                      </select>
                  </div>
                  <!-- 无赛事 -->
                  <div v-show="dataList.length==0" id="div_nodata" name="div_nodata" class="NoEvent_game" >无赛程</div>

                  <!-- 联赛列表 -->
                  <div class="league_list">
                    <!-- 滚球才有 -->
                    <div v-if="(showtype =='RB' || showtype =='FT' || (tiptype=='p3' && showtype !='FU')) && dataList.length>0" class="inneraccordion" @click="openNewGame('newcate?FStype='+gtype+'&mtype=4&showtype='+showtype+'&fstiptype='+showtype+'&tiptype='+tiptype+'&M_League=&gid=',wh_type)">
                      <div class="game_name"><span style="color: #a74e25">所有赛事</span></div>
                  <!--    <div class="more_right">
                        <div class="list_num"></div>
                        <div class="list_arr"></div>
                      </div>-->
                    </div>

                      <div v-for="(list,index) in dataList" :data-leaid="list.gid" class="inneraccordion" :key="index"
                           @click="openNewGame('newcate?FStype='+gtype+'&mtype=4&showtype='+showtype+'&fstiptype='+showtype+'&tiptype='+tiptype+'&M_League='+list.M_League+'&gid='+(list.gid?list.gid:list.lid),wh_type)">  <!--冠军是 lid-->
                          <div class="game_name"><span>{{list.M_League}}</span></div>
                          <div class="more_right">
                              <div class="list_num">{{list.num}}</div>
                              <div class="list_arr"> </div>
                              </div>
                       </div>

                  </div>

                  <div id="bottom_all" class="allsports" ><router-link to="sport">所有球类</router-link></div>
                  <div  class="LayoutDiv5" ><router-link to="/">回到首页</router-link></div>

              </div>

          </div>


      </div>
      <!-- 遮罩层 -->
      <div class="mask"  ></div>

      <FooterNav class="sport_footer" />
  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import FooterNav from '@/components/Footer'
import commonSport from '@/league_list.js'

export default {
    name: 'sportlist',
    mixins: [Mixin,commonSport],
    components: {
        FooterNav
    },
    data () {
        return {
            dataList:[],
            showtype:'',
            gtype:'',
            sorttype:'',
            mdate:'',
            FStype:'',
            tiptype:'',
            wh_type:'future'
        }
    },
    watch: {
        '$route' (to, from) { // 当前路由再次切换刷新
            this.$router.go(0);
        }
    },
    destroyed(){
        clearTimeout(this.sportTimer);　　// 清除定时器
        this.sportTimer = null;
    },
    mounted: function () {
        let _self = this;
        _self.userMoney = _self.localStorageGet('member_money');

        _self.showtype = _self.$route.query.showtype?_self.$route.query.showtype:''; // 获取参数,滚球 RB , 今日 FT，早盘 FU
        _self.gtype = _self.$route.query.gtype?_self.$route.query.gtype:''; // 获取参数
        _self.sorttype = _self.$route.query.sorttype?_self.$route.query.sorttype:''; // 获取参数
        _self.mdate = _self.$route.query.mdate?_self.$route.query.mdate:''; // 获取参数
        _self.FStype = _self.$route.query.FStype?_self.$route.query.FStype:''; // 获取参数
        _self.tiptype = _self.$route.query.tiptype?_self.$route.query.tiptype:''; // 获取参数

        if(_self.showtype=='BK'){
            _self.showtype = 'FT' ;
        }
        if(_self.showtype=='BU'){
            _self.showtype = 'FU' ;
        }
        if(_self.showtype=='RB'){ // 倒计时 滚球
            _self.autotime = 20 ; // 刷新时间
            _self.wh_type = 'rb';
        }else if(_self.showtype=='FT' || _self.showtype=='BK'){ // 今日
            _self.autotime = 60 ; // 刷新时间
            _self.wh_type = 'today';
        }

        _self.getFuWuTime();
        _self.getLeagueMatches(_self.gtype,_self.showtype,_self.sorttype,_self.mdate,_self.tiptype);
        _self.autoRefreshLeagueAction(_self.autotime);
    },
    methods: {
        /**
         * /var_lid_api.php  体育联赛数据接口
         *
         * @param  gtype   FT 足球，BK 篮球
         * @param  showtype   RB 滚球 FT 今日赛事 FU 早盘
         * @param  sorttype   league 联盟排序  time 时间排序
         * @param  mdate  早盘日期
         */
        getLeagueMatches:function (gtype,showtype,sorttype,mdate,fstype) {
            let _self =this;
            if(_self.clickflage){
                return false ;
            }
            _self.clickflage = true ;

            _self.gtype = gtype; // 重新更新 gtype

            let more ;
            let fs_showtype = '' ; // 冠军 showtype , 早盘需要传  future
            let ajaxurl = _self.ajaxUrl.otherlsapi;

            if(showtype == 'RB'){ // 滚球
                more = 'r' ;
            }else{
                more = 's' ;
            }
            let params = {
                gtype: gtype ,
                showtype: showtype ,
                sorttype: sorttype ,
                mdate: mdate ,
            } ;
            //$('#title_gtype').html(gtype=='FT'?'足球':'篮球');
            if(gtype =='BK'){ // 篮球单独处理
                if(showtype =='FT'){ // 今日 和 滚球
                    showtype ='BK' ;
                }else if( showtype =='RB'){ // 滚球
                    showtype ='RB' ;
                }else{ // 早盘
                    showtype ='BU' ;

                }
            }

            if(fstype=='champion'){ // 冠军
                ajaxurl = _self.ajaxUrl.gjlsapi;
                params.FStype = gtype ;
                params.mtype = '4' ;
                if(showtype=='FU' || showtype=='BU'){
                    fs_showtype = 'future' ;
                }

            }else if(fstype=='p3'){ // 综合过关
                ajaxurl = _self.ajaxUrl.p3api;
                params.FStype = gtype ;
                params.mtype = '4' ;
                if(showtype=='FU' || showtype=='BU'){
                    fs_showtype = 'future' ;
                }
            }
            _self.loadingContent(true) ;
            _self.axios({
                method: 'post',
                params: params,
                url: ajaxurl
            }).then(res=>{
                let rest = res.data;
                if(res.status=='200'){ // 请求数据成功
                    _self.loadingContent(false) ;
                    _self.dataList = rest.data;
                    setTimeout(function () {
                        _self.clickflage = false ;
                    },_self.clicktime);

                }
            }).catch(res=>{
                _self.loadingContent(false) ;
                console.log('体育数据获取失败');
                setTimeout(function () {
                    _self.clickflage = false ;
                },_self.clicktime);
            });

        },

        // 联盟排序 和 时间排序选择
        changeSportAll:function () {
            let _self = this;
//            let league = _self.sorttype ; // 排序类型
//            let mdate = _self.mdate ;
//            let more_par = '' ; // 冠军参数
//            if(_self.tiptype=='champion'){ // 冠军
//                more_par = '&FStype='+_self.gtype+'&mtype=4&tiptype=champion' ;
//            }else if(_self.tiptype=='p3'){ // 综合过关
//                more_par = '&FStype='+_self.gtype+'&mtype=4&tiptype=p3' ;
//            }
           // _self.$router.push('sportlist?gtype='+gtype+'&showtype='+showtype+'&sorttype='+league+'&mdate='+mdate+more_par);
            _self.getLeagueMatches(_self.gtype,_self.showtype,_self.sorttype,_self.mdate,_self.tiptype);
        },
        // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
        autoRefreshLeagueAction:function (time) {
            let _self = this;
            let $btn = $('#refresh-btn') ;
            let wait = time ;
            let refreshTime = function() {
                if (wait == 0) {
                    wait = time ;
                    $btn.text(wait) ;
                    _self.getLeagueMatches(_self.gtype,_self.showtype,_self.sorttype,_self.mdate,_self.tiptype) ;
                    _self.autoRefreshLeagueAction(time);
                }else{
                    $btn.text(wait) ;
                    wait--;
                    //console.log(wait+'++');
                    _self.sportTimer = setTimeout(refreshTime,1000) ;
                }
            }
            refreshTime();
        }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .header-right {width: 14%;}
</style>
