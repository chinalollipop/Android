<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
        <div class="container" >
            <div class="banner_top" :class="'banner_top_'+actionType">
                <!-- 搜索框 -->
                <div >
                    <div class="up_seachBox">
                        <input type="text" class="up_seach_input" placeholder="请输入会员账号" v-model="up_userName" minlength="4" maxlength="20" readonly>
                        <a href="javascript:;" class="up_seach_btn" @click="checkMemberLevel"></a>
                    </div>
                </div>
                <!-- 公告 -->
                <!--   <div class="newSection">
                       <div class="newsBox ">
                           <dl>
                               <dt>最新公告</dt>
                               <dd class="bd">
                                   <marquee onmouseout="this.start();" onmouseover="this.stop();" direction="left" scrolldelay="150" scrollamount="5">
                                       <?php /*echo getScrollMsg(); */?>
                                   </marquee>
                               </dd>
                           </dl>
                       </div>
                   </div>-->
            </div>

            <!-- 表格内容 -->
            <div class="up_content" >
                <div class="top-icon icon"> </div>
                <!-- 顶部文字 -->
                <p v-html="tipStrData.title_1" class="up_tip" style="text-indent: 2rem;">

                </p>
                <div class="table_bg">
                    <table class="up_table up_level_table" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr class="tr_bg">
                            <th width="20%" class="back-yell">晋升标准等级</th>
                            <th width="20%" class="back-yell">有效投注</th>
                            <th width="20%" class="back-yell">晋级礼金</th>
                            <th width="20%" class="back-yell">月俸禄</th>
                            <!--<th width="20%" class="back-yell">时时返水</th>-->
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(list,item) in LevelStrData" :key="item">
                                <td>{{list.level}}</td>
                                <td>{{list.valid_bet}}</td>
                                <td>{{list.jinji_salary}}元</td>
                                <td>{{list.month_salary}}元</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-html="tipStrData.title_2" class="center_tip">

                </p>
                <div class="bottom-icon icon"> </div>
                <p v-html="tipStrData.title_3" class="bottom_tip">

                </p>
            </div>


        </div>
        <!-- 查询数据 -->
        <div class="dialog animated bounce result">
            <div class="dialog-container animated bounceInDown" >
                <span class="closebtn" onclick="$('.dialog').removeClass('animate-enter');"></span>
                <h2 class="title">
                  <div class="seach_top">
                    <p >会员账号：<span class="seach_name"> </span></p>
                    <a href="javascript:;" class="btn_lq" :data-type="btn_flag" @click="getCaiJin">领取晋级彩金</a>
                  </div>
                </h2>
                <div class="content">
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <th width="10%" class="back-yell">当前等级</th>
                            <th width="20%" class="back-yell">累计有效投注</th>
                            <th width="20%" class="back-yell">累计赠送码量</th>
                            <th width="15%" class="back-yell">累计晋级礼金</th>
                            <th width="10%" class="back-yell">月俸禄</th>
                            <th width="20%" class="back-yell">距离下一等级<br>所需有效投注</th>
                        </tr>
                        <tr>
                            <td><span class="level"></span>级</td>
                            <td class="user_total_bet"></td>
                            <td class="free_total_bet"></td>
                            <td class="total_jinji_salary"></td>
                            <td class="total_month_salary"></td>
                            <td class="next_level_need_valid_money"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="level_each" style=" margin-top:1.5rem;">
                        <thead>
                        <tr>
                            <th width="10%" class="back-yell">当周等级</th>
                            <th width="25%" class="back-yell">当周投注</th>
                            <th width="15%" class="back-yell">晋级彩金</th>
                            <th width="25%" class="back-yell">投注周期</th>
                            <th width="10%" class="back-yell">状态</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- 页码 -->
                    <div v-if="LevelData.page_count>0" class="page_upload pagination" >
                        <span>共 {{LevelData.page_count}} 页</span>
                        <select class="select_page" v-model="cur_Page" @change="upLevelChangePage($event)">
                            <option v-for="(list,item) in LevelData.page_count" class="swShowPage" :value="item" > 第{{item+1}}页 </option>
                        </select>

                    </div>

                </div>

            </div>
            <div class="mask-bg"></div>
        </div>

        <FooterNav :class="apptip=='app' && 'hide-cont'"/>
    </div>

  </div>
</template>

<script>
    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'Index',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav,
            Dialog
        },
        data () {
            return {
                cur_Page:0, // 默认第一页
                up_userName:'',
                actionType:'live', // live sport
                LevelStrData:[], // 等级数据
                LevelData:[], // 查询等级数据
                tipStrData: {},
                liveData :{
                    'title_1':'即日起，在<span class="df_1">HG0086</span>，投注真人视讯，每一笔有效投注将永久累计，只要达到一定的晋级标准，即可领取晋级礼金、月俸禄。达到一定等级后，即使您当月没有投注，也能躺着月月领俸禄，高达80,000元。等级30级，累计晋级礼金高达<span class="df_1">748,300</span>元，月俸禄高达<span class="df_1">80,000</span>元, 会员账号享有至高无上的价值体验，终身有效！ <span class="df_2">【派送时间通知】</span>晋级礼金每周一20:00至周二20:00点击【查询】按钮进行自助领取；月俸禄以最高等级对应的金额派送，每月<span class="df_1">10号00:00</span>系统准时自动派送！玩游戏，还能获得高收益，聪明的您还在等什么呢？抓紧时间注册吧！',
                    'title_2':'<span class="df_1"> 注：</span>每周一20:00更新数据后，即可查看晋级礼金。若跨越多个等级，则晋级礼金进行累计派送，月俸禄以最高等级进行派送。<br> <span class="df_1">例：</span>会员累计真人视讯有效投注20万，【等级1，晋级礼金30元，9元周俸禄】；当会员累计投注达到200万，等级4，跨越了3个等级，晋级礼金为： 70+100+200=370元，月俸禄70元。',
                    'title_3':'1.活动所得奖金，无需打码，即可提款；<br>2.每周一20:00更新，届时可查看【最新等级，晋级礼金，月俸禄】；<br> 3.晋级礼金每周一20:00至周二20:00期间点击【查询】自助申请派发；月俸禄以最高等级对应的金额派送，每月<span class="df_1">10号00:00</span>系统自动派送!<br> 4.<span class="df_1">HG0086</span>保留对活动的最终解释权,以及在无通知的情况下修改、终止活动的权利。<br> 5.请勿任何方式重复申请彩金行为，否则将停用阁下账户！'
                },
                sportData :{
                    'title_1':'即日起，在<span class="df_1">HG0086</span>，投注<span class="df_2">体育竞技（包含电竞）</span>，每一笔有效投注将永久累计，只要达到一定的晋级标准，即可领取晋级礼金、月俸禄。达到一定等级后，即使您当月没有投注，也能躺着月月领俸禄，高达119,999元。等级30级，累计晋级礼金高达306,666元，月俸禄高达119,999元, 会员账号享有至高无上的价值体验，终身有效！ <br> <span class="df_2">【派送时间通知】</span>晋级礼金每周一20:00至周二20:00点击【查询】按钮进行自助领取；月俸禄以最高  等级对应的金额派送，每月10号00:00系统准时自动派送！ <br>玩游戏，还能获得高收益，聪明的您还在等什么呢？抓紧时间注册吧！',
                    'title_2':'<span class="df_1">注：</span>每周一20:00更新数据后，即可查看晋级礼金。若跨越多个等级，则晋级礼金进行累计派送，月俸禄以最高等级进行派送。<br> <span class="df_1">例：</span>会员累计体育竞技有效投注20万，【等级1，晋级礼金188元，18元周俸禄】；当会员累计投注达到200万，等级4，跨越了3个等级，晋级礼金为： 388+888+1888=3164元，月俸禄252元。',
                    'title_3':'1.活动所得奖金，无需打码，即可提款；<br>2.每周一20:00更新，届时可查看【最新等级，晋级礼金，月俸禄】；<br>3.晋级礼金每周一20:00至周二20:00期间点击【查询】自助申请派发；月俸禄以最高等级对应的金额派送，<span class="df_1">每月10号00:00</span>系统自动派送!<br>4.<span class="df_1">HG0086</span>保留对活动的最终解释权,以及在无通知的情况下修改、终止活动的权利。<br> 5.请勿任何方式重复申请彩金行为，否则将停用阁下账户！'
                },
                apptip:'',
                btn_flag:'false'
            }
        },
        mounted: function () {
            let _self = this ;
            _self.up_userName = _self.userName?_self.userName:_self.$route.query.username ;
            _self.actionType = _self.$route.query.game_Type; // 获取参数
            _self.tipStrData = (_self.actionType=='live')?_self.liveData:_self.sportData;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
            _self.checkUpgraded();

        },
        methods:{
            /*
             * 获取等级数据，查询等级
             *  type :
             *  getZhenrenLevelSalaryInfo 查询等级数据
             *  getSalaryRecords
             *
             * */
            checkUpgraded: function(type,curPage) {
                let _self =this;
                let ajaxurl = '' ; // 真人
                switch(_self.actionType){
                    case 'live':
                        ajaxurl = _self.ajaxUrl.uplive ; // 真人
                        break;
                    case 'sport':
                        ajaxurl = _self.ajaxUrl.upsport;
                        break;
                }

                if(!type){
                    type = 'getZhenrenLevelSalaryInfo';
                }
                if(!curPage){curPage=0}
                let pars ={
                    action:type,
                    username:_self.up_userName,
                    page:curPage
                };
                return new Promise((resolve, reject)=>{
                    _self.axios({
                        method: 'post',
                        params: pars,
                        url: ajaxurl
                    }).then(res=>{
                        if(res){
                            let rest = res.data;
                            if(rest.status ==200){
                                switch (type){
                                    case 'getZhenrenLevelSalaryInfo':
                                        _self.LevelStrData = rest.data;
                                        break;
                                    case 'getSalaryRecords':
                                        _self.LevelData = rest.data;
                                        _self.showLevelData(rest.data,curPage);
                                        break;
                                }

                            }else{
                                _self.$refs.autoDialog.setPublicPop(res.describe);
                            }
                            resolve(res);
                        }
                    }).catch(res=>{
                        console.log('获取列表数据失败');
                        reject(res);
                    });
                });
            },
            /*
             *  查询会员等级
             * */
            checkMemberLevel: function () {
                let _self = this;
                if(!_self.up_userName){
                    _self.$refs.autoDialog.setPublicPop('请输入账号');
                    return false;
                }
                _self.checkUpgraded('getSalaryRecords');
            },
            // 显示查询数据
            showLevelData: function(resData,curPage) {
                var $dialog = $('.dialog');
                var $level_each = $('.level_each tbody');
                var $page_upload = $('.page_upload');
                if(curPage==0){ // 首页
                    $dialog.addClass('animate-enter');
                    $('.seach_name').html(resData.current_level.username); // 用户名
                    $('.level').html(resData.current_level.level); // 等级
                    $('.user_total_bet').html(resData.current_level.user_total_bet); // 累计有效投注
                    $('.free_total_bet').html(resData.current_level.free_total_bet); // 累计赠送投注
                    $('.total_jinji_salary').html(resData.current_level.total_jinji_salary); // 累计彩金
                    $('.total_month_salary').html(resData.current_level.total_month_salary);
                    $('.next_level_need_valid_money').html(resData.current_level.next_level_need_valid_money);
                }

                var l_str = '';
                if(resData.each_salary.length>0){
                    for(var i=0;i<resData.each_salary.length;i++){
                        l_str +=' <tr class="rank-tr" >'+
                            '<td>'+resData.each_salary[i].level+'级</td>'+
                            '<td>'+resData.each_salary[i].total+'</td>'+
                            '<td>'+resData.each_salary[i].gift_gold+'</td>'+
                            '<td>'+resData.each_salary[i].count_date_start+'--'+resData.each_salary[i].count_date_end+'</td>'+
                            '<td>'+this.returnUpStaus(resData.each_salary[i].status)+'</td>'+
                            '</tr>';
                    }
                }else{ // 没有数据
                    l_str = '<tr class="rank-tr" ><td colspan="6">暂无数据</td></tr>';
                }

                $level_each.html(l_str);
                // 分页
               // $page_upload.html(this.returnPage(curPage,resData.page_count)) ;

            },
            returnPage: function (page,pagecount) { // 返回页码
                var pagestr = '' ;
                if(pagecount>0){
                    pagestr +='<span>共 '+pagecount+' 页</span> <select class="select_page" @change="upLevelChangePage">';
                }
                for(var j=0;j<pagecount;j++){ // 分页
                    pagestr += ' <option class="swShowPage" topage="'+ (j+1) +'" '+(page==j?'selected':'')+'> 第'+ (j+1) +'页 </option>';
                }
                if(pagecount>0){
                    pagestr +='</select>';
                }
                return pagestr ;
            },
            // 页码切换
            upLevelChangePage: function (e) {
                let _self = this;
                let thispage = _self.cur_Page;
                _self.checkUpgraded('getSalaryRecords',thispage);
            },

            /*
             * 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
             **/
            returnUpStaus: function(type) {
                var str;
                switch (type){
                    case '1':
                        str = '已派发';
                        break;
                    case '2':
                        str = '未审核';
                        break;
                    case '3':
                        str = '不符合';
                        break;
                    case '4':
                        str = '已拒绝';
                        break;
                }
                return str;
            },
          /* 领取周晋级彩金 */
          getCaiJin:function () {
            let _self = this;
            let ajaxurl = '' ; // 真人
            let pars ={
                username:_self.up_userName,
              };
              if(_self.btn_flag=='true'){
                return false;
              }
              switch(_self.actionType){
                case 'live':
                  ajaxurl = _self.ajaxUrl.uplive_lq; // 真人
                  pars.action = 'zhenrenWeekJinjiApply';
                  break;
                case 'sport':
                  ajaxurl = _self.ajaxUrl.upsport_lq;
                  pars.action = 'djWeekJinjiApply';
                  break;
              }

            _self.btn_flag = 'true';
            _self.axios({
              method: 'post',
              params: pars,
              url: ajaxurl
            }).then(res=>{
              if(res){
                let rest = res.data;
                if(rest){
                  _self.$refs.autoDialog.setPublicPop(rest.describe);
                  _self.btn_flag = 'false';
                }

              }
            }).catch(res=>{
              console.log('领取失败');
            });


        }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .seach_top{display: flex;display: -webkit-flex;justify-content: center;align-items: center;}
  .seach_top .btn_lq{color: #67180b;font-size: 14px;margin-left: 5px;display: inline-block;width: 110px;height: 30px;line-height:30px;background:url(/static/images/upgraded/btn_lq.png) center no-repeat;background-size: 100%;}

  select{color: #fff;height: 2rem;border-radius: 5px;}
    select option{background: #2c3232;}
    .pagination {padding: 8px 0;position: absolute;width: 100%;bottom: 0;}
    p >>> .df_1{color: #d09300;}
    p >>> .df_2{color: #ff0000;}

    .banner_top{width: 100%;height: 10rem;background: url(/static/images/upgraded/banner_live.jpg) top center no-repeat;position: relative;background-size: cover;}
    .banner_top_sport{background-image: url(/static/images/upgraded/banner_sport.jpg);}
    .newSection {position: absolute;width: 100%;height: 2rem;bottom: 0;background: rgba(16, 12, 12, 0.82);}
    .newSection dl{margin: 0;}
    .newSection dt{padding-left: 2rem;color: #f0cf66;line-height: 2rem;font-size: 1rem;font-weight: bold;float: left;background: url(/static/images/upgraded/icon_gg.png) center left no-repeat;}
    .newSection dd{height: 100%;padding: 0 2%;}
    .newSection dd a:hover{text-decoration: underline;}
    .newSection dd li{height: 2rem;line-height: 2rem; width:auto!important; font-size: 12px;color: #cfcbcb; }
    .newSection dd li:hover{ cursor:pointer; }
    .newSection marquee{color: #fff;line-height: 2rem;width: 86%;}
    .up_seachBox{width: 14rem;max-width: 300px;height: 3rem;position: absolute;background: url(/static/images/upgraded/search_bg.png) center no-repeat;bottom: 0;right: 3%;background-size: contain;}
    .up_seach_btn{transition:.3s;display:inline-block;width:4rem;height:3rem;background: url(/static/images/upgraded/btn_check.png) center no-repeat;float: right;margin: 0 2px 0 0;background-size: 100%;}
    .up_seach_btn:hover{transform: scale(1.05);}
    .up_seachBox input {padding: 0 .6rem;width: 8.5rem;height: 2rem;line-height: 2rem;border: none;background-color: transparent;color: #EFDC60;font-size: 1rem;margin: .5rem 0 0 0;}
    .up_content{color:#fff;background:#0f0f0f;border-top:1px solid #d09300;border-bottom:1px solid #d09300;padding:1rem 2% 5rem}
    .up_content table td{color:#fff;font-size: .9rem;}
    .up_content .icon{width:90%;height:3rem;margin:0 auto}
    .up_content .top-icon{background:url(/static/images/upgraded/title_top.png) center no-repeat;background-size: 100%;}
    .up_content .bottom-icon{background:url(/static/images/upgraded/title_bottom.png) center no-repeat;background-size: 100%;}
    .table_bg{width:100%;margin:1rem auto;background:#1b1b1b;}
    .up_content p{line-height: 1.5rem;padding: 0 2%;text-align: left;font-size: .9rem;}
    .up_table{width: 100%;line-height: 2.5rem;text-align: center;}
    .up_table .tr_bg{background: url(/static/images/upgraded/table_bg.png) center no-repeat;background-size: cover;}
    .up_table tr th{color: #b00101;line-height: normal;}
    .up_table tr th,.up_table tr td{border: 1px solid #fff;}
    .up_footer{background: #070707;}
    .up_footer .footer_icon{width: 100%;height: 200px;background: url(/static/images/upgraded/up_icon.png) center no-repeat;}
    .copyright {line-height: 60px;color: #4c4c4c;text-align: center;border-top: 1px solid #4c4c4c;}

    /*消息框*/
    .dialog {position:fixed;_position:absolute;top:7%;left:50%;margin-left:-49%;padding:0;border-radius:2px;animation-fill-mode:both;animation-duration:.3s;z-index:300;background:none;display:none;}
    .dialog-container {width:98%;height:100%;display:block;position:relative;background:#2c3232;border:3px solid #f0a844;border-radius:8px;overflow:hidden;}
    .dialog .closebtn {opacity:1;position:absolute;background:url(/static/images/upgraded/closebtn.png) no-repeat;width:64px;height:64px;right:-4px;top:-4px;display:block;cursor:pointer;}
    .dialog .dialog-container > .title {margin:1.5rem 0;text-align:center;line-height:2rem;}
    .dialog .dialog-container > .title p{color:#fff;font-size:1.3rem;}
    .dialog .content {position:relative;height:31rem;min-height:450px;width:98%;border-top:1px solid #333;margin:0 auto;}
    .dialog .content .warp {padding:10px;}
    .animate-enter .bounceInDown {-webkit-animation-name:bounceInDown;animation-name:bounceInDown;-webkit-animation-duration: 1s;animation-duration: 1s;z-index: 300;}
    .animate-enter {display:block;}

    .dialog-container > .title{ color: #ffcf0d; font-weight:normal; }
    .dialog-container > .title span{ color:#fff; font-weight:bold;}
    .result table{width: 100%; margin: 0 auto;}
    .result table tr td,.result table tr th,.result table >>> td{  height: 3rem; /*line-height: 3rem; */color: #f2f2f2; font-size: 1rem; padding-left:0px;border: 1px solid #f0a844; text-align:center; }
    .result table tr td{font-size: .9rem;}
    .result table tr th{border: 1px solid #f0a844; color: #f0a844;}
    .dianjing-container table tr td{position: relative;}
    .mask-bg {background-color: #000;opacity: 0.6;filter: alpha(opacity=60);top: 0;left: 0;width: 100%;height: 100%;z-index: 1;position: fixed;}

    @-webkit-keyframes bounceInDown {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-2000px);
            transform: translateY(-2000px);
        }
        60% {
            opacity: 1;
            -webkit-transform: translateY(30px);
            transform: translateY(30px);
        }
        80% {
            -webkit-transform: translateY(-10px);
            transform: translateY(-10px);
        }
        100% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
    }
    @keyframes bounceInDown {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-2000px);
            -ms-transform: translateY(-2000px);
            transform: translateY(-2000px);
        }
        60% {
            opacity: 1;
            -webkit-transform: translateY(30px);
            -ms-transform: translateY(30px);
            transform: translateY(30px);
        }
        80% {
            -webkit-transform: translateY(-10px);
            -ms-transform: translateY(-10px);
            transform: translateY(-10px);
        }
        100% {
            -webkit-transform: translateY(0);
            -ms-transform: translateY(0);
            transform: translateY(0);
        }
    }

</style>
