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
        <div class="content-center bet-container sport-content-center" :class="gtype">

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
                <div class="sportNav_title">
                    <div id="lea_title_gtype"  class="game_title"> </div>
                    <div id="refresh" class="refresh" @click="getMoreGames(gid,gtype,showtype,tiptype,M_League,isMaster)">
                        <span id="refresh-btn"> </span>
                    </div>
                </div>


            </div>

            <!-- 投注列表区域 开始-->
            <div class="bet-content">

                <!-- 球队栏 -->
                <table border="0" cellspacing="0" cellpadding="0" id="div_matches" class="matches">
                    <tbody>
                    <tr>
                        <td class="board_1">

                            <div id="board_title" class="board_title">
                                <div class="board_l">
                                    <div id="game_live" class="live_time_board">{{mtype==1?'滚球':''}}</div>
                                    <div id="game_time" class="game_time"> </div> <!-- 比赛时间 -->
                                    <div id="game_midfield" style="display:none" class="odds_mid"> <!--N--></div>
                                </div>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td class="match_team board_2">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%" class="scoreboard">
                                <tbody>
                                <tr>
                                    <td class="board_team_h">
                                        <span id="game_score_h" class="score_zero">0</span> <!-- 主队进球数 score_light -->
                                        <div id="game_team_h">主场</div>
                                    </td>
                                    <td class="board_score"><span class="score_v">|</span></td>
                                    <td class="board_team_c">
                                        <span id="game_score_c" class="score_zero">0</span> <!-- 客队进球数 -->
                                        <div id="game_team_c">客场</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr class="add_bk_score"></tr>
                    </tbody>
                </table>

                <!-- 无赛事 -->
                <div id="div_nodata" name="div_nodata" class="NoEvent_game" style="display:none;">此赛事暂时停止收注或已关闭</div>

                <!-- 有赛事 容器-->
                <div class="has_sport_matches" >

                </div>
                <!-- 赛事列表 -->
                <div id="sport_div_show" >

                    <!-- 单场 让球 开始-->
                    <div class="bet-dcrq">

                    </div>
                    <!-- 单场 让球 结束-->

                    <!-- 半场 让球 开始-->
                    <div class="bet-bcrq">

                    </div>
                    <!-- 半场 让球 结束-->

                    <!-- 单场 大/小 开始-->
                    <div class="bet-dcdx">

                    </div>
                    <!-- 单场 大/小 结束-->

                    <!-- 半场 大/小 开始-->
                    <div class="bet-bcdx">

                    </div>
                    <!-- 半场 大/小 结束-->

                    <!-- 单场 独赢 开始-->
                    <div class="bet-dcdy">

                    </div>
                    <!-- 单场 独赢 结束-->

                    <!-- 半场 独赢 开始-->
                    <div class="bet-bcdy">

                    </div>
                    <!-- 半场 独赢 结束-->

                    <!-- 波胆全场 开始-->
                    <div class="bet-bddc">

                    </div>
                    <!-- 波胆全场 结束-->

                    <!-- 波胆半场 开始-->
                    <div class="bet-bdbc">

                    </div>
                    <!-- 波胆半场 结束-->

                    <!-- 15分钟盘口 让球 开始-->
                    <div class="bet-swrz">

                    </div>
                    <!-- 15分钟盘口 让球 结束-->

                    <!-- 15分钟盘口 大小 开始-->
                    <div class="bet-swdx">

                    </div>
                    <!-- 15分钟盘口 大小 结束-->

                    <!-- 15分钟盘口 独赢 开始-->
                    <div class="bet-swdy">

                    </div>
                    <!-- 15分钟盘口 独赢 结束-->

                    <!-- 总进球数全场 开始-->
                    <div class="bet-zjqsdc">

                    </div>
                    <!-- 总进球数全场 结束-->

                    <!-- 总进球数半场 开始-->
                    <div class="bet-zjqsbc">

                    </div>
                    <!-- 总进球数半场 结束-->

                    <!-- 双方球队进球全场 开始-->
                    <div class="bet-sfqddc">

                    </div>
                    <!-- 双方球队进球全场 结束-->

                    <!-- 双方球队进球半场 开始-->
                    <div class="bet-sfqdbc">

                    </div>
                    <!-- 双方球队进球半场 结束-->

                    <!-- 球队进球数 单场-主队-大小 开始-->
                    <div class="bet-qdjqszdc">

                    </div>
                    <!-- 球队进球数 单场-主队-大小 结束-->


                    <!-- 球队进球数 半场-主队-大小 开始-->
                    <div class="bet-qdjqszbc">

                    </div>
                    <!-- 球队进球数 半场-主队-大小 结束-->

                    <!--  篮球才有 球队得分: - 最后一位数 主队 开始-->
                    <div class="bet-mbqddfzhyws">

                    </div>
                    <!-- 球队得分: - 最后一位数 主队 结束-->

                    <!--  篮球才有 球队得分: - 最后一位数 客队 开始-->
                    <div class="bet-tgqddfzhyws">

                    </div>
                    <!-- 球队得分: - 最后一位数 客队 结束-->

                    <!-- 单双单场 开始-->
                    <div class="bet-dansdc">

                    </div>
                    <!-- 单双单场 结束-->

                    <!-- 单双半场 开始-->
                    <div class="bet-dansbc">

                    </div>
                    <!-- 单双半场 结束-->

                    <!-- 最先/最后进球 开始-->
                    <div class="bet-zxzhjq">

                    </div>
                    <!-- 最先/最后进球 结束-->

                    <!-- 全场/半场 开始-->
                    <div class="bet-qcbc">

                    </div>
                    <!-- 全场/半场 结束-->

                    <!-- 净胜球数 开始-->
                    <div class="bet-jsqs">

                    </div>
                    <!-- 净胜球数 结束-->

                    <!-- 双重机会 开始-->
                    <div class="bet-scjh">

                    </div>
                    <!-- 双重机会 结束-->

                    <!-- 零失球 开始-->
                    <div class="bet-lingsq">

                    </div>
                    <!-- 零失球 结束-->

                    <!-- 零失球获胜 开始-->
                    <div class="bet-lingsqhs">

                    </div>
                    <!-- 零失球获胜 结束-->

                    <!-- 独赢 & 进球 大 / 小  开始-->
                    <div class="bet-dyjqdx">

                    </div>
                    <!-- 独赢 & 进球 大 / 小  结束-->

                    <!-- 独赢 & 双方球队进球  开始-->
                    <div class="bet-dysfqdjq">

                    </div>
                    <!-- 独赢 & 双方球队进球  结束-->

                    <!-- 进球 大 / 小 & 双方球队进球  开始-->
                    <div class="bet-jqdxsfqdjq">

                    </div>
                    <!-- 进球 大 / 小 & 双方球队进球  结束-->

                    <!-- 独赢 & 最先进球  开始-->
                    <div class="bet-dyzxjq">

                    </div>
                    <!-- 独赢 & 最先进球  结束-->

                    <!-- 最多进球的半场  开始-->
                    <div class="bet-zdjqdbc">

                    </div>
                    <!-- 最多进球的半场  结束-->

                    <!-- 最多进球的半场 - 独赢  开始-->
                    <div class="bet-zdjqdbcdy">

                    </div>
                    <!-- 最多进球的半场 - 独赢  结束-->

                    <!-- 双半场进球  开始-->
                    <div class="bet-sbcjq">

                    </div>
                    <!-- 双半场进球  结束-->

                    <!-- 首个进球时间-3项  开始-->
                    <div class="bet-sgjqsj3x">

                    </div>
                    <!-- 首个进球时间-3项  结束-->

                    <!-- 首个进球时间  开始-->
                    <div class="bet-sgjqsj">

                    </div>
                    <!-- 首个进球时间  结束-->

                    <!-- 双重机会 & 进球 大 / 小  开始-->
                    <div class="bet-scjhjqdx">

                    </div>
                    <!--  双重机会 & 进球 大 / 小  结束-->

                    <!--双重机会 & 双方球队进球  开始-->
                    <div class="bet-scjhsfqdjq">

                    </div>
                    <!--  双重机会 & 双方球队进球  结束-->

                    <!-- 双重机会 & 最先进球  开始-->
                    <div class="bet-scjhzxjq">

                    </div>
                    <!--   双重机会 & 最先进球  结束-->

                    <!--  进球 大 / 小 & 进球 单 / 双  开始-->
                    <div class="bet-jqdxds">

                    </div>
                    <!--   进球 大 / 小 & 进球 单 / 双  结束-->

                    <!--   进球 大 / 小 & 最先进球  开始-->
                    <div class="bet-jqdxzxjq">

                    </div>
                    <!--    进球 大 / 小 & 最先进球  结束-->

                    <!-- 三项让球投注  开始-->
                    <div class="bet-sxrqtz">

                    </div>
                    <!--  三项让球投注  结束-->

                    <!--   赢得任一半场  开始-->
                    <div class="bet-ydrybc">

                    </div>
                    <!--    赢得任一半场  结束-->

                    <!--   赢得所有半场  开始-->
                    <div class="bet-ydsybc">

                    </div>
                    <!--    赢得所有半场  结束-->


                </div>

                <div class="clear"></div> <!-- 清除浮动 -->

                <div  class="allsports"><a @click="$router.back()">{{gtype=='FT'?'足球':'篮球'}}</a></div>
                <div  class="allsports" ><router-link to="sport">所有球类</router-link></div>

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
                gtype:'',
                mtype:'',
                fstiptype:'',
                tiptype:'',
                M_League:'',
                gid:'',
                isMaster:'',  // 足球滚球增加1个参数  isMaster  Y  是主盘口， N附属盘口
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
            _self.gtype = _self.$route.query.gtype?_self.$route.query.gtype:''; // 获取参数
            _self.mtype = _self.$route.query.mtype?_self.$route.query.mtype:''; // 获取参数
            _self.tiptype = _self.$route.query.tiptype?_self.$route.query.tiptype:''; // 获取参数
            _self.M_League = _self.$route.query.M_League?_self.$route.query.M_League:''; // 获取参数
            _self.gid = _self.$route.query.gid?_self.$route.query.gid:''; // 获取参数
            _self.isMaster = _self.$route.query.isMaster?_self.$route.query.isMaster:'';
           localStorage.setItem('footBallMaster',_self.isMaster); // 投注需要带上
            //console.log(_self.isMaster);
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

            _self.getMoreGames(_self.gid,_self.gtype,_self.showtype,_self.tiptype,_self.M_League,_self.isMaster) ;

            _self.autoRefreshLeagueAction(_self.autotime);

            _self.betSureAction(_self.gtype,_self.showtype,_self.tiptype);
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
                        _self.getMoreGames(_self.gid,_self.gtype,_self.showtype,_self.tiptype,_self.M_League,_self.isMaster);
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
