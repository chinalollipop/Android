<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div class="deposit-two" >
        <div class="form">
          <div class="form-item">
            <span class="label">
                  <span class="text">类别</span>
                  <span class="line"></span>
            </span>

            <span class="dropdown" :class="tpl_name=='8msport/' && 'textbox'">
                 <select id="select_type" v-model="select_type" >
                       <option value="ALL">全部</option>
                       <option value="S">充值</option>
                       <option value="T">提现</option>
                       <option value="Q">额度转账</option>
                       <option value="R">返水</option>
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
                       <option value="ALL">全部</option>
                       <option value="1">成功</option>
                       <option value="-1">失败</option>
                       <option value="0,2">处理中</option>
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
              <td :class="FuWuTimeData.today==begin_time && 'active'"><span @click="chooseDateAction($event,FuWuTimeData.today,FuWuTimeData.today)">今日</span></td>
              <td :class="FuWuTimeData.yestoday==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.yestoday,FuWuTimeData.yestoday)">昨日</span></td>
              <td :class="FuWuTimeData.lastweek==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.lastweek,FuWuTimeData.today)">近一周</span></td>
              <td :class="FuWuTimeData.monfirst==begin_time && 'active'"><span @click="chooseDateAction(this,FuWuTimeData.monfirst,FuWuTimeData.today)">本月</span></td>
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
          <th style="width: 25%">订单号</th>
          <th style="width: 30%">日期/时间</th>
          <th style="width: 15%">项目</th>
          <th style="width: 15%">订单状态</th>
          <th style="width: 15%">金额</th>
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
      select_type: 'ALL',
      select_status: 'ALL',
      begin_time: '',
      end_time: '',
      dataList:[],
      gamename:{
        'S':'存款记录',
        'T':'提款记录',
        'ALL':'存提记录',
        'Q':'额度转换记录',
        'R':'返水记录'
      }
    }
  },
    mounted: function () {
        let _self = this ;
        _self.getFuWuTime();
        _self.getUserMoney(); // 上线打开

        // 时间初始化
        let begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间
        let endcalendar = new lCalendar();   // 时间插件初始化 ，结束时间
        _self.begin_time= _self.setAmerTime('#begin_time','dayoff');
        _self.end_time= _self.setAmerTime('#begin_time','dayoff');
        begincalendar.init({
            'trigger': '#begin_time',
            'type': 'date',
            defaultValue:_self.begin_time,
        });
        endcalendar.init({
            'trigger': '#end_time',
            'type': 'date',
            defaultValue:_self.end_time,
        });

    },
    methods:{
      // 日期快速选择
       chooseDateAction: function(e,datebegin,dateend) {
           this.begin_time= datebegin;
           this.end_time= dateend;
        },
        // 查询按钮
        seachDataActtion: function(e) {
          let _self =this;
          $('.get_more_action').attr('data-page',1) ;
          $('#table_record tbody').html('') ; // 每次查询需要清空

          let type = _self.select_type;
          let status = _self.select_status;
          let begintime = _self.begin_time;
          let endtime = _self.end_time;
          _self.getDepositRecord(0,type,status,begintime,endtime)
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
          let status = _self.select_status;
          let begintime = _self.begin_time;
          let endtime = _self.end_time;
          if(curpage<1 || curpage >= allcount){ // 没有数据
              $get_more_action.html('没有更多数据了') ;
              return false ;
          }
          _self.getDepositRecord(curpage,type,status,begintime,endtime,'more')

    },
      /* 查询数据 */
      getDepositRecord: function(page,type,status,begintime,endtime,more) {
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
              thistype: type ,  // type S 存款，T 提款
              page:page ,
              type_status:status , // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败
              date_start:begintime , // 开始时间
              date_end:endtime , // 结束时间
          }
          _self.axios({
              method: 'post',
              params: pars,
              url: _self.ajaxUrl.record
          }).then(res => {
              if (res) {
                  _self.submitflag = false ;
                  let rest = res.data;
                  if(rest.status==200){ // 有数据返回
                      $get_more_action.attr('data-count',rest.data.page_count) ; // 总页数

                      if(rest.data.total==0){
                          $('.get_more_data').hide() ;
                          var nodata = '<tr class="no-data"><td colspan="5">暂时没有'+_self.gamename[type]+'</td></tr>' ;
                          $recordslist.html(nodata) ;
                      }else{ // 有数据
                          var str = '' ;
                          for(var i=0;i<rest.data.rows.length;i++){
                              str += '<tr>' +
                                  '<td>'+ rest.data.rows[i].Order_code +'</td>' +
                                  ' <td>'+ rest.data.rows[i].AuditDate +'</td>' ;
                              if(rest.data.rows[i].Type=='T'){
                                  str += '<td>提款</td>' ;
                              }else if(rest.data.rows[i].Type=='S'){
                                  if(rest.data.rows[i].notes=='APP幸运红包活动'){
                                      str += '<td>红包</td>' ;
                                  }else if(rest.data.rows[i].notes=='APP签到红包'){
                                      str += '<td>'+rest.data.rows[i].notes+'</td>' ;
                                  }else{
                                      str += '<td>存款</td>' ;
                                  }
                              }else{ // 转账记录
                                  str += '<td>'+ _self.returnChangeType(rest.data.rows[i].From) +'</br><font color="red">转入</font></br>'+ _self.returnChangeType(rest.data.rows[i].To) +'</td>' ;
                              }

                              if(rest.data.rows[i].Checked == 0 || rest.data.rows[i].Checked == 2){ // 审核中 ,2 二次审核
                                  str += '<td class="font"><font color="green">审核中</font></td>';
                              }else if(rest.data.rows[i].Checked == -1){ // 失败
                                  str += '<td class="font"><font color="#cd5c5c">失败</font></td>';
                              }else if(rest.data.rows[i].Checked == 1){ // 成功
                                  str += '<td class="font"><font color="red">成功</font></td>';
                              }
                              str += '<td>'+ rest.data.rows[i].Gold +'</td>' +
                                  '</tr>' ;

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
              console.log('存提记录请求失败');
          });
      },
        // 额度转换类型处理 type
        returnChangeType: function(type) {
          var str = '' ;
          switch (type){
              case 'hg':
                  str = '中心钱包';
                  break;
              case 'sc': // 皇冠体育
                  str ='皇冠体育' ;
                  break;
              case 'cp': // 彩票
                  str ='彩票平台' ;
                  break;
              case 'gmcp': // 三方彩票
                  str ='彩票平台' ;
                  break;
              case 'ag': // ag真人
                  str ='AG平台' ;
                  break;
              case 'bbin': // bbin真人
                  str ='BBIN视讯' ;
                  break;
              case 'og':
                  str ='OG视讯' ;
                  break;
              case 'ky': // 彩票
                  str ='开元棋牌' ;
                  break;
              case 'ly': // 乐游棋牌
                  str ='乐游棋牌' ;
                  break;
              case 'ff':
                  str ='皇冠棋牌' ;
                  break;
              case 'vg':
                  str ='VG棋牌' ;
                  break;
              case 'kl':
                  str ='快乐棋牌' ;
                  break;
              case 'cq': // CQ9真人
                  str ='CQ9平台' ;
                  break;
              case 'fg': // FG真人
                  str ='FG平台' ;
                  break;
              case 'mw':
                  str ='MW平台' ;
                  break;
              case 'mg':
                  str ='MG平台' ;
                  break;
              case 'avia': // 泛亚电竞
                  str ='泛亚电竞' ;
                  break;
              case 'fire': // 雷火电竞
                  str ='雷火电竞' ;
                  break;
          }
          return str ;
      }

    }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
