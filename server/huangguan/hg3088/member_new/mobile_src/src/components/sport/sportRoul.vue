<template>
    <div >

        <!-- 顶部导航栏 -->
        <div class="header sport_header" :class="apptip=='app' && 'hide-cont'">
            <div class="header_left">
                <a @click="$router.back()" class="back-active sport_back_icon" ></a>
            </div>
            <div class="header-center">
                <router-link to="sport?showtype=rb" class="header_live"><i class="rb_running_logo"></i>滚球</router-link>
                <router-link to="sport?showtype=today" class="header_today">今日</router-link>
                <router-link to="sport?showtype=future" class="header_early">早盘</router-link>
            </div>

        </div>
        <!-- 中间部分 -->
        <div class="content-center sport-content-center">

            <!-- 导航切换 -->
            <div class="sportNav">

                <div class="sportNav_title">
                    <div id="title_gtype" name="title_gtype" class="game_title">体育规则</div>
                </div>


            </div>

            <div class="selection">
                <select name="sportsdropdown" class="sportsdropdown" id="sportsdropdown" @change="chg_roul(sportsdropdown)" v-model="sportsdropdown">
                    <option value="/static/roul/sport_rule.html" selected="selected">一般体育说明</option>
                    <option value="/static/roul/outright.html">冠军</option>
                    <option value="/static/roul/parlays-multiples.html">综合过关</option>
                    <option value="/static/roul/football.html">足球</option>
                    <option value="/static/roul/basketball.html">篮球</option>
                    <option value="/static/roul/tennis.html">网球</option>
                    <option value="/static/roul/volleyball.html">排球</option>
                    <option value="/static/roul/badminton.html">羽毛球</option>
                    <option value="/static/roul/table-tennis.html">乒乓球</option>
                    <option value="/static/roul/baseball.html">棒球</option>
                    <option value="/static/roul/snooker_ruo.html">斯诺克 / 台球</option>
                    <option value="/static/roul/roul_nf.html">美式足球</option>
                    <option value="/static/roul/archery.html">射箭和射击</option>
                    <option value="/static/roul/athletic.html">田径</option>
                    <option value="/static/roul/aussie.html">澳式足球</option>
                    <option value="/static/roul/beach-soccer.html">沙滩足球</option>
                    <option value="/static/roul/beach-volleyball.html">沙滩排球</option>
                    <option value="/static/roul/boxing.html">拳击 / 搏斗</option>
                    <option value="/static/roul/cricket.html">板球</option>
                    <option value="/static/roul/cycling.html">自行车</option>
                    <option value="/static/roul/darts.html">飞镖</option>
                    <option value="/static/roul/e-sports.html">电子竞技</option>
                    <option value="/static/roul/field-hockey.html">曲棍球</option>
                    <!--<option value="/static/roul/financial-bets.html">金融投注</option>-->
                    <option value="/static/roul/futsal.html">室内足球</option>
                    <option value="/static/roul/golf.html">高尔夫</option>
                    <option value="/static/roul/gymnastics.html">体操</option>
                    <option value="/static/roul/handball.html">手球</option>
                    <option value="/static/roul/ice-hockey.html">冰球</option>
                    <option value="/static/roul/judo.html">柔道、摔交、跆拳道</option>
                    <option value="/static/roul/long-field-hockey.html">长曲棍球</option>
                    <option value="/static/roul/all-medal-betting.html">体育 / 奖章投注</option>
                    <!--<option value="/static/roul/major-league-lacrosse.html">袋棍球</option>-->
                    <!--<option value="/static/roul/medal-betting.html">奖牌投注</option>-->
                    <option value="/static/roul/motor-sports.html">赛车</option>
                    <option value="/static/roul/olympics.html">奥林匹克或相关事件投注</option>
                    <option value="/static/roul/rowing.html">赛艇和皮划艇</option>
                    <option value="/static/roul/rugby-league.html">橄榄球联盟</option>
                    <!--<option value="/static/roul/snooker.html">桌球/9号球巡回赛</option>-->
                    <option value="/static/roul/softball.html">垒球</option>
                    <!-- <option value="/static/roul/swimming.html">游泳</option>-->
                    <!--<option value="/static/roul/taekwondo.html">跆拳道</option>-->
                    <option value="/static/roul/tamp.html">三项全能和现代五项运</option>
                    <!--<option value="/static/roul/ultimate-fighting.html">终极格斗锦标赛（综合格斗大赛）</option>-->
                    <option value="/static/roul/water-polo.html">水球</option>
                    <option value="/static/roul/weightlifting.html">举重</option>
                    <!--<option value="/static/roul/wrestling.html">角力</option>-->
                    <option value="/static/roul/wintersports.html">冬季运动&冬季奥运会 / 比赛</option>
                </select>
            </div>

            <!-- 内容区域 -->
            <div id="rule_box" class="rule_box" name="rule_box">
                <!-- 一般体育说明 开始 -->

                <!-- 一般体育说明 结束 -->

            </div>

        </div>


        <FooterNav class="sport_footer" :class="apptip=='app' && 'hide-cont'"/>
    </div>
</template>

<script>

    import Mixin from '@/Mixin'
    import FooterNav from '@/components/Footer'

    export default {
        name: 'sportroul',
        mixins: [Mixin],
        components: {
            FooterNav
        },
        data () {
            return {
                apptip:'',
                sportsdropdown:'/static/roul/sport_rule.html'
            }
        },
        watch: {

        },
        destroyed(){

        },
        mounted: function () {
            let _self = this;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
            _self.chg_roul('/static/roul/sport_rule.html') ; // 默认体育规则页面
        },
        methods: {
            chg_roul:function (url){
                $('#rule_box').load(url,function () {

                }) ;
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

    .rule_box{color:#323232;text-align:left;margin:24px 0 16px 0;padding:0 10px;clear:both}
    .rule_box>>> h1{margin-bottom:8px;font-size:16px;font-weight:500;color:#be9553}
    .rule_box>>> li {	list-style-type: decimal;}
    .rule_box>>> ul {list-style: decimal outside!important;}
    .rule_box>>> ul ul {list-style-type: lower-alpha!important;}
    .rule_box>>>ul,.rule_box>>>ol{margin:1em 0;padding:0 0 0 40px}
    .rule_box>>>h2{display:block;margin:18px 0 20px 0;font-size:14px;font-weight:bold;color:#323232}

    .rule_box>>> .rules_update{display:block;font-size:12px;color:rgba(75,67,57,0.54);margin-bottom:10px}
    .rule_box>>> .rules_update em{font-style: normal;}
    .rule_box>>> .rule_title{font-size:16px;font-weight:bold;line-height:1.21;margin-bottom:17px}
    .rule_box>>> .rule_info{font-size:16px;line-height:1.21;margin-bottom:12px}
    .rule_box>>> .tutorial_page .rule_pic{padding:0;margin-bottom:21px}
    .rule_box>>> .tutorial_page .rule_info ol{margin:0;padding:0 0 0 18px}
    .rule_box>>> .tutorial_page .rule_info ol li{list-style-type:disc}
    .rule_box>>> .rule_pic{text-align:center;padding:9px 0 12px}
    .rule_box>>> .RULE strong{padding:0 5px}
    .rule_box>>> .help_info{padding:0 0 17px;font-size:17px;font-family:simsun;text-align:justify;color:#463D2F}
    .rule_box>>> .help_pic{text-align:center;padding:0 0 18px}
    .rule_box>>> .LayoutDiv1_contact{clear:both;float:left;margin-left:0;width:100%;display:block;background-color:#503F32;color:white;line-height:48px;margin-bottom:8px}
    .rule_box>>> .tutorial_page .LayoutDiv1_contact{margin-bottom:17px}
    .rule_box>>> .LayoutDiv1_contact + .rule_box{padding-top:10px}
    .rule_box>>> .help_rule{padding:10px 10px 10px;clear:both}
    .rule_box>>> .backtop{width:100%;height:48px;line-height:48px;text-align:center;font-size:15px;border-radius:3px;color:#5a5249;background-color:#e5e0dc;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
    .rule_box>>> .backtop:active{background-color:#cfc6be!important}

    /* New Rules */
    .rule_box>>> .RESUL h1, .rule_box>>> .RESUL h2, .rule_box>>> .HELP h1, .rule_box>>> .HELP h2 {font-size:14px; text-transform:uppercase;}
    .rule_box>>> .RESUL p, .rule_box>>> .RESUL ul, .rule_box>>> .HELP p, .rule_box>>> .HELP ul  { font-size:14px; font-weight:normal; line-height:1.6em;}

    /*FT menu*/
    .rule_box>>> .css_tr {display: table-row;}
    .rule_box>>> .rules_group { display:table; width:100%;}
    .rule_box>>> .rules_btn { display:table-cell; height:40px;line-height:40px; width:33.33% !important; vertical-align:middle; /*width:calc((100% - 30px)/3); */ text-align: center; border-right:15px solid #FFF; border-bottom:15px solid #FFF;background-image: url(/static/images/home_layer_4.jpg);	background-size: auto 100%;}
    .rule_box>>> .rules_btn:last-child {border-right:0px none;}
    .rule_box>>> .rules_btn a { display:block; /*line-height:40px; */color:#FFF; }
    .rule_box>>> .rules_btn:active {opacity:0.8;}
    .rule_box>>> .Soccer_Title { line-height:34px; padding-left:10px; color:#FFF; font-weight:normal;
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#492300+0,492300+44,331a00+100 */
        background: #492300; /* Old browsers */
        background: -moz-linear-gradient(top,  #492300 0%, #492300 44%, #331a00 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top,  #492300 0%,#492300 44%,#331a00 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom,  #492300 0%,#492300 44%,#331a00 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#492300', endColorstr='#331a00',GradientType=0 ); /* IE6-9 */
    }
    .rule_box>>> .acc_rulesTB{ border-collapse:collapse; border:1px solid #333; background-color:#fff; text-align:center; margin-bottom:18px;}
    .rule_box>>> .acc_rulesTB th, .acc_rulesTB td{border-bottom:1px solid #333; border-right:1px solid #333; height:30px; font-size:14px; vertical-align:middle;}
    .rule_box>>> .acc_rulesTB th{ background-color:#ff9700;}
    .rule_box>>> .RESUL { padding-bottom: 50px !important;}
    .rule_box>>> .RESUL .popBox { background-image:none; border:none !important; box-shadow:none;}

    .rule_box>>> .RESUL.DARTS .rule_box>>> ul ul, .RESUL.BS .rule_box>>> ul ul {list-style-type:decimal !important;}
    .rule_box>>> .acc_backUL ul {list-style-type:decimal !important;}

    /* for KO Hide*/
    .rule_box>>> .hide{display:none;visibility:hidden}
    .RESUL h1,.help_pwdforgot .rule_box>>> h1{margin-bottom:8px;font-size:16px;font-weight:500;color:#be9553}
    .rule_box>>> .RESUL h2,.help_pwdforgot h2{display:block;margin:18px 0 20px 0;font-size:14px;font-weight:bold;color:#323232}
    .rule_box>>> .RESUL p,.help_pwdforgot p{color:#323232;margin:0}
    .rule_box>>> .RESUL .terms_box ul{padding:0 16px 0 14px}
    .rule_box>>> .terms_box h1{margin-bottom:20px;color:#323232}
    .rule_box>>> .terms_box ul ~ h1{margin-top:25px}
    .rule_box>>> .terms_box>ul>li{margin-bottom:20px;margin-left:14px;line-height:1.21;font-size:14px}
    .rule_box>>> .help_pwdforgot .recovery_box{padding:0 10px}
    .rule_box>>> .help_newtv .recovery_box{padding:0 13px 40px}
    .rule_box>>> .help_newtv .new_img{margin-bottom:18px}
    .rule_box>>> .qa_help_page .LayoutDiv1_contact{margin-bottom:17px}
    .rule_box>>> .qa_help_page .help_rule{padding:0 9px}
    .rule_box>>> .qa_help_page .help_info{margin-bottom:12px;padding:0}

    .rule_box>>> .text_bold{font-weight: bold!important;}
    .rule_box>>> .no_list_number>li{list-style-type: none;}
    .rule_box>>> ul.acc_lower_alpha  > li {list-style-type: lower-alpha}/*abc*/
    .rule_box>>> ul.acc_romaUL li {list-style-type: lower-roman;}/*i*/
    .rule_box>>> .indent_outside {text-indent: -1.2em;}

</style>
