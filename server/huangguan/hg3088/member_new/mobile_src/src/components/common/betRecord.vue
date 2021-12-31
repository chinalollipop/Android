<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="deposit-two" >
        <div class="form">
          <div class="form-item">
            <span class="label">
                  <span class="text">赛事类型</span>
                  <span class="line"></span>
            </span>

            <span class="dropdown" :class="tpl_name=='8msport/' && 'textbox'">
                 <select id="select_type" v-model="select_type" >
                    <option value="FT">足球</option>
                    <option value="BK">篮球</option>
                    <option value="FS">冠军</option>
                    <option value="lottery" v-if="tplnameList.indexOf(tpl_name)>=0"> 彩票 </option>
                    <option value="aglive"> AG视讯 </option>
                    <option value="aggame"> AG电子 </option>
                    <option value="agby"> AG捕鱼 </option>
                    <option value="kyqp"> 开元棋牌 </option>
                    <option value="lyqp"> 乐游棋牌 </option>
                    <option value="vgqp"> VG棋牌 </option>
                    <option value="klqp"> 快乐棋牌 </option>
                    <!-- <option value="hgqp"> 皇冠棋牌 </option>-->
                    <option value="mgdz"> MG电子 </option>
                    <option value="avia"> 泛亚电竞 </option>
                    <option value="oglive"> OG视讯 </option>
                    <option value="bbinlive"> BBIN视讯 </option>
                    <option value="cq9dz"> CQ9电子 </option>
                    <option value="mwdz"> MW电子 </option>
                    <option value="fgdz"> FG电子 </option>
                </select>
            </span>
          </div>
          <div class="form-item">
            <span class="label">
                  <span class="text">是否结算</span>
                  <span class="line"></span>
            </span>
            <span class="dropdown" :class="tpl_name=='8msport/' && 'textbox'">
                 <select id="select_tip" v-model="select_tip" >
                       <option value="">全部</option> <!-- 全部 -->
                       <option value="N">未结注单</option> <!-- 未结注单 -->
                       <option value="Y">已结注单</option> <!-- 已结注单 -->

                </select>
            </span>
          </div>
          <div class="form-item">
            <span class="label">
                  <span class="text">状态</span>
                  <span class="line"></span>
            </span>

            <span class="dropdown" :class="tpl_name=='8msport/' && 'textbox'">
                 <select id="select_status"  v-model="select_status">
                      <option value="N">有效注单</option> <!-- 有效注单 -->
                      <option value="Y">无效注单</option> <!-- 无效注单 -->
                </select>
            </span>
          </div>
          <div class="form-item">
            <span class="label">
                <span class="text">开始时间</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input class="deposit-input"  placeholder="选择日期" type="text" id="begin_time" v-model="begin_time" readonly />
            </span>
          </div>
          <div class="form-item">
            <span class="label">
                <span class="text">结束时间</span>
                <span class="line"></span>
            </span>
            <span class="textbox">
                <input class="deposit-input"  placeholder="选择日期" type="text" id="end_time" v-model="end_time" readonly />
            </span>
          </div>
          <table class="money moneychoose" >
            <tbody>
            <tr>
              <td :class="(FuWuTimeData.today+' 00:00')==begin_time && 'active'"><span @click="chooseDateAction($event,FuWuTimeData.today,FuWuTimeData.today)">今日</span></td>
              <td :class="(FuWuTimeData.yestoday+' 00:00')==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.yestoday,FuWuTimeData.yestoday)">昨日</span></td>
              <td :class="(FuWuTimeData.lastweek+' 00:00')==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.lastweek,FuWuTimeData.today)">近一周</span></td>
              <td :class="(FuWuTimeData.monfirst+' 00:00')==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.monfirst,FuWuTimeData.today)">本月</span></td>
            </tr>
            </tbody>
          </table>

          <div class="btn-wrap">
            <a href="javascript:;" class="zx_submit" @click="seachDataActtion($event)">查询</a>
          </div>

        </div>

      </div>
      <table width="100%" border="0" id="table_record" class="table_record">
        <thead>
        <tr>
          <th style="width: 22%">日期</th>
          <th style="width: 50%">赛事</th>
          <th style="width: 14%">金额</th>
          <th style="width: 14%">输/赢</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
      </table>

      <div class="get_more_data" style="display: none;"><a class="get_more_action" data-page="1" @click="getMoreData($event)">加载更多数据</a></div>


    </div>

    <FooterNav />
  </div>
</template>

<script>
    import '../../../static/css/icalendar.css'
    import '../../../static/js/icalendar.min.js'

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'myaccount',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav,
            Dialog
        },
        data () {
            return {
                select_type: 'FT',
                select_tip: '', // 是否结算
                select_status: 'N', // 是否取消
                begin_time: '',
                end_time: '',
                dataList:[]
            }
        },
        mounted: function () {
            let _self = this ;
            _self.getFuWuTime();
            _self.getUserMoney(); // 上线打开

            // 时间初始化
            let begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间
            let endcalendar = new lCalendar();   // 时间插件初始化 ，结束时间
            _self.begin_time= _self.setAmerTime('#begin_time','daystart');
            _self.end_time= _self.setAmerTime('#begin_time','dayend');
            begincalendar.init({
                'trigger': '#begin_time',
                'type': 'datetime',
                defaultValue:_self.begin_time,
            });
            endcalendar.init({
                'trigger': '#end_time',
                'type': 'datetime',
                defaultValue:_self.end_time,
            });

        },
        methods:{
            // 日期快速选择
            chooseDateAction: function(e,datebegin,dateend) {
                this.begin_time= datebegin+' 00:00';
                this.end_time= dateend+' 23:59';
            },
            // 查询按钮
            seachDataActtion: function(e) {
                let _self =this;
                $('.get_more_action').attr('data-page',1) ;
                $('#table_record tbody').html('') ; // 每次查询需要清空

                let type = _self.select_type;
                let tip = _self.select_tip;
                let status = _self.select_status;
                let begintime = _self.begin_time;
                let endtime = _self.end_time;
                _self.getTodayWagers(0,type,tip,status,begintime,endtime)
            },
          /* 加载更多数据 */
            getMoreData: function (e) {
                let _self = this;
                let $src = $(e.currentTarget);
                let $get_more_action = $('.get_more_action');
                $get_more_action.html('加载中...') ;

                let curpage = Number($src.attr('data-page')) ;
                let allcount = Number($src.attr('data-count')) ; // 总页数
                let type = _self.select_type;
                let tip = _self.select_tip;
                let status = _self.select_status;
                let begintime = _self.begin_time;
                let endtime = _self.end_time;
                if(curpage<1 || curpage >= allcount){ // 没有数据
                    $get_more_action.html('没有更多数据了') ;
                    return false ;
                }
                _self.getTodayWagers(curpage,type,tip,status,begintime,endtime,'more')

            },
          /* 查询数据 */
            getTodayWagers: function(page,type,tip,status,begintime,endtime,more) {
                var _self = this;
                var $get_more_action = $('.get_more_action');
                if(_self.submitflag){
                    return false ;
                }
                if(more){ // 加载更多数据
                    var curpage = Number($get_more_action.attr('data-page')) ; // 当前页面
                }

                var $recordslist = $('#table_record tbody') ;
                _self.submitflag = true ;
                let pars ={
                    gtype :type , // 篮球 BK ，足球 FT
                    Checked :tip , // 是否结算 ，N 未结注单 Y 已结注单
                    Cancel :status , // 是否取消 , Y  无效注单 N 有效注单
                    date_start :begintime , // 是否取消 , Y  无效注单 N 有效注单
                    date_end :endtime , // 是否取消 , Y  无效注单 N 有效注单
                    page : page  // 页面
                }
                let ajaxurl = _self.ajaxUrl.sportbet;
                switch (type){
                    case 'lottery': // 彩票
                        ajaxurl = _self.ajaxUrl.lotterybet;
                        break;
                    case 'aglive': // AG 视讯
                    case 'aggame': // AG 电子
                    case 'agby': // AG 捕鱼
                        ajaxurl = _self.ajaxUrl.agbet;
                        break;
                    case 'kyqp': // 开元棋牌
                    case 'lyqp': // 乐游棋牌
                    case 'vgqp': // vg棋牌
                    case 'klqp': // 快乐棋牌
                    case 'hgqp': // hg棋牌
                    case 'mgdz': // mg电子
                    case 'avia': // 泛亚电竞
                    case 'oglive': // OG视讯
                    case 'bbinlive': // BBIN视讯
                    case 'cq9dz': // CQ9电子
                    case 'mwdz': // MW电子
                    case 'fgdz': // FG电子
                        if(tip==''){ // 默认
                            pars.Checked = 'Y';
                        }
                        ajaxurl = _self.ajaxUrl.otherbet;
                        break;

                }
                _self.axios({
                    method: 'post',
                    params: pars,
                    url: ajaxurl
                }).then(res => {
                    if (res) {
                        _self.submitflag = false ;
                        let rest = res.data;
                        if(rest.status==200){ // 有数据返回
                            $get_more_action.attr('data-count',rest.data.page_count) ; // 总页数

                            if(rest.data.total==0){
                                $('.get_more_data').hide() ;
                                var nodata = '<tr class="no-data"><td colspan="5">暂时没有注单记录</td></tr>' ;
                                $recordslist.html(nodata) ;
                            }else{ // 有数据
                                var str = '' ;
                                var text_color;
                                var text_tip;
                                var p3_result = '' ;
                                for(var i=0;i<rest.data.rows.length;i++){

                                    if(Number(rest.data.rows[i].M_Result) > 0){ // 赢
                                        text_tip = '赢' ;
                                        text_color = 'text-red' ;
                                    }else if(Number(rest.data.rows[i].M_Result) < 0){ // 输
                                        text_tip = '输' ;
                                        text_color = 'text-lose' ;
                                    }else if(Number(rest.data.rows[i].M_Result) == 0){ // 和局
                                        text_color = 'text-blue' ;
                                        if(type =='lottery'){  // 彩票 ( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
                                            if(rest.data.rows[i].cancel=='1'){
                                                text_tip = '已取消' ;
                                            }else if(rest.data.rows[i].count=='0'){
                                                text_tip = '未结算' ;
                                            }else {
                                                text_tip = '和局' ;
                                            }

                                        }else{
                                            text_tip = '和局' ;
                                        }
                                    }
                                    var BetScore = parseFloat( rest.data.rows[i].BetScore);
                                    BetScore = BetScore.toFixed(2);
                                    var M_Result = parseFloat( rest.data.rows[i].M_Result);
                                    M_Result = M_Result.toFixed(2);
                                    str += '<tr class="wagers">' +
                                        '<td >' +
                                        '<p>'+rest.data.rows[i].BetTime+'</p>' +
                                        '<p> '+rest.data.rows[i].orderNo+'</p>' +
                                        '</td>' ;
                                    str += '<td class="bet_content">';
                                    if(type=='FT'||type=='BK'||type=='FS'){ // 体育

                                        if(rest.data.rows[i].Middle.length > 0){ // 综合过关
                                            for(var j=0;j<rest.data.rows[i].Middle.length;j++){
                                                str += '<div class="bet_content_detail"><p>'+rest.data.rows[i].Middle[j].M_League+'</p>'+
                                                    '<p>'+ rest.data.rows[i].Middle[j].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ rest.data.rows[i].Middle[j].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ rest.data.rows[i].Middle[j].vs_team_name2 +'</p>'+
                                                    '<p><span class="text-blue">'+ rest.data.rows[i].Middle[j].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ rest.data.rows[i].Middle[j].bet_content +'</span> @ <span class="red_color">'+ rest.data.rows[i].Middle[j].bet_rate +'</span></p></div>';
                                                p3_result +='<p style="color:#ff0000"> '+ rest.data.rows[i].Middle[j].font_a +'</p>' ;
                                            }
                                        }else{ // 非综合过关 ,rest.data.rows[i].font_a 全场比分，rest.data.rows[i].corner_num 投注时比分

                                            str += '<div class="bet_content_detail"><p>'+rest.data.rows[i].M_League+'</p>'+
                                                '<p>'+ rest.data.rows[i].Title +'</p>'+
                                                '<p>'+ rest.data.rows[i].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ rest.data.rows[i].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ rest.data.rows[i].vs_team_name2 +'&nbsp;&nbsp;<span class="red_color">'+rest.data.rows[i].corner_num+'</span></p>'+
                                                '<p><span class="text-blue">'+ rest.data.rows[i].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ rest.data.rows[i].bet_content +'</span> @ <span class="red_color">'+ rest.data.rows[i].bet_rate +'</span></p>' +
                                                '<p><strong style="color:green">'+ rest.data.rows[i].isDanger +'</strong></p>'
                                            '</div>';

                                        }

                                    }else{ // 其他
                                        str += '<p class="bet_title">'+rest.data.rows[i].Title+'</p>';
                                    }

                                    str += '</td>';
                                    str +=  '<td class="bet_gold"><p>'+BetScore+'</p> </td>' ;
                                    if(M_Result && M_Result !='NaN'){ // 帐户历史
                                        if(rest.data.rows[i].zt){ // 异常注单
                                            str += '<td class="'+text_color+'"><p><font color=#cc0000><b>'+ rest.data.rows[i].zt +'</b></font></p> </td>' ;
                                        }else{
                                            str += '<td class="'+text_color+'">';
                                            if(rest.data.rows[i].Middle.length > 0) { // 综合过关
                                                str += p3_result ;
                                            }else{ // 非综合过关
                                                str += '<p style="color:#ff0000">'+ rest.data.rows[i].font_a  +'</p>' ;

                                            }
                                            str += '<p>'+ text_tip +'</p>'+ M_Result +'</td>' ;

                                        }

                                    }else{
                                        str += '<td ></td>';
                                    }
                                    str += '</tr>';
                                }

                                $recordslist.append(str) ;

                                if(rest.data.page_count>1){
                                    $get_more_action.html('加载更多数据') ;
                                    $('.get_more_data').show() ;
                                }else{
                                    $('.get_more_data').hide() ;
                                }
                                if(more) { // 加载更多数据
                                    curpage++ ;
                                    $get_more_action.attr('data-page',curpage) ;
                                    if(curpage == rest.data.page_count){
                                        $get_more_action.html('没有更多数据了') ;
                                    }
                                }

                            }
                        }
                    }
                }).catch(res => {
                    _self.submitflag = false ;
                    console.log('投注记录请求失败');
                });
            }


        }
    }

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
