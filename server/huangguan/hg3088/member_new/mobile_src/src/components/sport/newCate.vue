<template>
    <div >
        <Dialog ref="autoDialog" pa_dialogtitle="" />

        <!-- 顶部导航栏 -->
        <div class="header sport_header">
            <div class="header_left">
                <a @click="$router.back()" class="back-active sport_back_icon" ></a>
            </div>
            <div class="header-center">
                <router-link to="sport?showtype=rb" class="header_live" :class="showtype=='RB' && 'active'" data-type="RBMATCH" ><i class="rb_running_logo"></i>滚球</router-link>
                <router-link to="sport?showtype=today" class="header_today" :class="(showtype=='FT') && 'active'" data-type="TODAYMATCH" >今日</router-link>
                <router-link to="sport?showtype=future" class="header_early" :class="(showtype=='FU') && 'active'" data-type="FUTUREMATCH" >早盘</router-link>
            </div>
            <div class="header-right" >
                <span class="menu_icon" data-num="1" @click="checkAction"> </span>
            </div>
        </div>

        <!-- 中间部分 -->
        <div class="content-center bet-container sport-content-center">

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
                    <div id="lea_title_gtype" class="game_title"> {{FStype=='FT'?'足球':'篮球'}} </div>
                    <div id="refresh" class="refresh" @click="getNewGameDetails(FStype,gid,showtype,M_League,tiptype)">
                        <span id="refresh-btn"> </span>
                    </div>
                </div>


            </div>
            <!-- 内容区域 -->
            <div class="bet-content">

                <!-- 标题栏-->
                <div v-if="showtype !='RB'" class="hdp_header"> <!--滚球不展示-->
                    <table border="0" cellspacing="0" cellpadding="0" class="tool_table">
                        <tbody>
                        <tr>
                            <td id="change_r" class="h_r" :class="tiptype==''?'hdp_up':''" data-type="ALLMATCH" @click="changeSportMatches($event,FStype,'',showtype)">让球 &amp; 大小</td>
                            <td id="change_p" class="h_p " :class="tiptype=='p3'?'hdp_up':''" data-type="P3MATCH" @click="changeSportMatches($event,FStype,'',showtype)" >综合过关</td>
                            <td id="change_fs"  class="h_fs " :class="tiptype=='champion'?'hdp_up':''" data-type="CHAMPION" @click="changeSportMatches($event,FStype,'',showtype)">冠军</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                <!-- 无赛事 -->
                <div id="div_nodata" name="div_nodata" class="NoEvent_game" style="display:none;">无赛程</div>

                <!-- 赛事列表 -->
                <div id="sport_div_show" >

                </div>

                <div class="clear"></div> <!-- 清除浮动 -->

                <div  class="allsports"><a @click="$router.back()">{{FStype=='FT'?'足球':'篮球'}}</a></div>
                <div  class="LayoutDiv5" ><router-link to="/">回到首页</router-link></div>


            </div>


        </div>

        <!-- 投注表单弹窗 开始-->
        <div id="div_bet" ref="div_bet_win" class="betBox betRecript box_off" > <!-- box_on -->
            <div class="titleBar">
                <div id="div_bet_title" class="titleBarLeft">
                    <i class="iconArrow"></i>
                    <div class="titleBarText">
                        <span id="bet_orderTitle">交易单</span>
                        <tt id="bet_credit" class="user_money">{{userMoney}}</tt>
                    </div>
                </div>
                <div id="plus_btn" class="plusBtn" style="display: none;"></div>
                <div class="closeBtnW" style="display: none;"></div>
            </div>
            <div id="div_content" class="content">
                <div class="betInformation">
                    <!---------------------------------- normal model ---------------------------------->
                    <div id="normal_order_model">

                    </div>

                    <div id="div_bet_info" class="amountDiv">
                        <div class="amountInput">
                            <!--<div id="bet_gold" class="txtBlack"></div>--> <!--输入中 txtBlack / 输入中 txtGray-->
                            <input id="betGold" name="" type="number" placeholder="投注额">
                            <!--<tt id="bet_gold_tt" class="txtGray" tabindex="-1" style=""></tt>-->
                            <span id="clear_btn" class="closeBtn"></span>
                        </div>
                        <div>
                            <p>单注最低:&nbsp;</p><tt id="minbet"> </tt><br>
                            <p>单注最高:&nbsp;</p><tt id="maxbet" class="bet_maxmoney"> </tt>
                        </div>
                    </div>
                    <!-- 金额快速选择 -->
                    <div class="betAmount btn_betAmount">
                      <ul>
                        <li value="100">100</li>
                        <li value="200">200</li>
                        <li value="500">500</li>
                        <li value="1000">1,000</li>
                        <li value="5000">5,000</li>
                        <li value="10000">10,000</li>
                      </ul>
                    </div>

                </div>
                <div id="div_bet_info2" class="winAmount">
                    <ul>
                        <li>
                            <p>可赢金额:</p>
                            <tt id="bet_win_gold" class="txtGreen txtBold">0.00</tt>
                        </li>
                    </ul>
                </div>
                <div id="div_err" class="errorBox" style="display: none;">
                    <i class="iconError"></i>
                    <div id="err_msg"></div>
                </div>
            </div>

            <div id="div_nobet" class="noBetBox" style="display: none;"><!--无单的画面-->
                <i class="iconFlag"></i>
                <p>请把选项加入在您的注单.</p>
            </div>
            <div class="betBtnDiv noBetMode" style="display: none;"><!--无单的按钮-->
            </div>
            <div id="div_betBtn" class="betBtnDiv bettingMode">
                <span id="clear_order" class="delAllBtn whiteBtn">全删除</span>
                <span id="set_btn" class="settingBtn grayBtn"></span>
                <span id="submitSrc" href="javascript:void(0);" class="betSubmitBtn greenBtn" >
						<tt id="bet_gold2_tt">0.00</tt>
						<p>投注</p>
					</span>
                <span id="loading_bet" class="loadingBtn greenBtn" style="display: none;">
						<i class="iconLoading"></i>
					</span>
                <!-- <span id="noBet_btn" class="okBtn greenBtn">确认</span>-->
            </div>
        </div>
        <!-- 投注表单弹窗 结束-->

        <!-- 综合过关订单 按钮-->
        <div v-show="tiptype=='p3'" class="p3_bet_action p3_bet_icon">
            <span id="p3_bet_number" class="p3_bet_number">0</span>
        </div>

        <!-- 投注成功后区域 开始-->
        <div class="bet-sure-content">
            <div class="order_mem_data">
                <div class="bet-title bet_caption"> </div>
                <div class="bet-title">交易成功</div>
                <div class="bet-title-bottom"> 当前余额：<span class="user_money red_color">{{userMoney}}</span> 元</div>
            </div>

            <ul class="hisInfo">
                <li> 单号：<span class="bet_order_num"></span></li>
                <ul class="bet_order_allcontent">
                    <!--  <li class="finish_bet_league"> </li>
                      <li class="finish_bet_team"> </li>
                      <li class="finish_bet_content"> </li>-->
                </ul>

                <li ><span class="finish_bet_mon"> </span> 元</li>
                <li >可赢：<span class="finish_bet_win"></span> <router-link to="betrecord" class="to_betrecord red_color">前往交易记录</router-link> </li>
            </ul>
            <div class="finish_bet_btn greenBtn">确定</div>

        </div>
        <!-- 投注表单确认区域 结束-->
        <!-- 遮罩层 -->
        <div class="mask"  ></div>
        <!-- 下注遮罩层 -->
        <div class="bet_mask"  ></div>

        <FooterNav class="sport_footer" />
    </div>
</template>

<script>

    import Mixin from '@/Mixin'
    import FooterNav from '@/components/Footer'
    import Dialog from '@/components/Dialog'
    import commonSport from '@/league_list.js'

    export default {
        name: 'sportlist',
        mixins: [Mixin,commonSport],
        components: {
            FooterNav,
            Dialog
        },
        data () {
            return {
                //dataList:[],
                showtype:'', // 滚球 RB， 今日 FT，早盘 FU
                FStype:'',
                mtype:'',
                fstiptype:'',
                tiptype:'',
                M_League:'',
                gid:'',
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

            _self.showtype = _self.$route.query.showtype?_self.$route.query.showtype:''; // 获取参数
            _self.FStype = _self.$route.query.FStype?_self.$route.query.FStype:''; // 获取参数
            _self.mtype = _self.$route.query.mtype?_self.$route.query.mtype:''; // 获取参数
            _self.tiptype = _self.$route.query.tiptype?_self.$route.query.tiptype:''; // 获取参数
            _self.M_League = _self.$route.query.M_League?_self.$route.query.M_League:''; // 获取参数
            _self.gid = _self.$route.query.gid?_self.$route.query.gid:''; // 获取参数
            localStorage.setItem('footBallMaster',''); // 还原

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

            _self.getNewGameDetails(_self.FStype,_self.gid,_self.showtype,_self.M_League,_self.tiptype);
            _self.autoRefreshLeagueAction(_self.autotime);

            _self.betSureAction(_self.FStype,_self.showtype,_self.tiptype);
            _self.spreadAction();
            _self.betP3ReadyAction(); // 综合过关独有
            _self.CountWinGold();
            _self.showBetWindow();
            _self.clearInputMon();
            _self.closeBetFinish();

        },
        methods: {

            // 自动刷新函数,time 自定义秒数刷新,callback 回调函数
            autoRefreshLeagueAction:function (time) {
                let _self = this;
                let $btn = $('#refresh-btn') ;
                let wait = time ;
                let refreshTime = function() {
                    if (wait == 0) {
                        wait = time ;
                        $btn.text(wait) ;
                        _self.getNewGameDetails(_self.FStype,_self.gid,_self.showtype,_self.M_League,_self.tiptype) ;
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
