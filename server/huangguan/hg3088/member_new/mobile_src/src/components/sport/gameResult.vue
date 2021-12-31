<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

      <!-- 中间部分 -->
      <div class="content-center deposit">

          <div class="deposit-two" >
              <div class="form">
                  <div class="form-item">
                      <span class="label">
                            <span class="text">赛事类型</span>
                            <span class="line"></span>
                      </span>

                     <span class="dropdown" :class="tpl_name=='8msport/'?'textbox':''">
                           <select id="select_type" v-model="select_type" >
                                 <option value="FT">足球</option>
                                 <option value="BK">篮球</option>

                          </select>
                      </span>
                  </div>

                  <div class="form-item">
                    <span class="label">
                        <span class="text">日期</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                        <input class="deposit-input"  placeholder="选择日期" type="text" id="begin_time" v-model="begin_time" readonly />
                    </span>
                  </div>

                  <table class="money moneychoose" >
                      <tbody>
                      <tr>
                          <td :class="(FuWuTimeData.today)==begin_time && 'active'"><span @click="chooseDateAction($event,FuWuTimeData.today,FuWuTimeData.today)">今日</span></td>
                          <td :class="(FuWuTimeData.yestoday)==begin_time && 'active'"><span @click="chooseDateAction($event,FuWuTimeData.yestoday,FuWuTimeData.yestoday)">昨日</span></td>
                          <td> </td>
                          <td> </td>
                      </tr>
                      </tbody>
                  </table>

                  <div class="btn-wrap">
                      <a href="javascript:;" class="zx_submit" @click="seachDataActtion">查询</a>
                  </div>

              </div>

          </div>

          <table width="100%" border="0" id="table_record" class="table_record" >
              <thead>
              <tr>
                  <th style="width: 22%">日期</th>
                  <th style="width: 50%">赛事</th>
                  <th style="width: 14%">半场</th>
                  <th style="width: 14%">全场</th>
              </tr>
              </thead>
              <tbody>
              <tr v-show="dataList==''" class="no-data"><td colspan="5">暂时没有赛果记录</td></tr>
                <!-- 联赛标题 -->
                <template v-for="(list,index) in dataList">
                    <tr :key="index" class="league_name" :data-league="list.name"><td colspan="6" class="b_hline">{{list.name}}</td></tr>
                    <tr class="wagers" v-for="(listchild,item) in list.result" :key="listchild.MID">
                        <td> {{listchild.M_Date}}<br> {{listchild.M_Time}} </td>
                        <td class="bet_content"> {{listchild.MB_Team}}<br>{{listchild.TG_Team}} </td>
                        <td> <b class="red_color"><span>{{listchild.MB_Inball_HR}}</span><br><span>{{listchild.TG_Inball_HR}}</span></b>  </td>
                        <td>  <b class="red_color"><span>{{listchild.MB_Inball}}</span><br><span>{{listchild.TG_Inball}}</span></b>  </td>
                    </tr>
                </template>

              </tbody>
          </table>


          <div class="clear"></div>

      </div>

  </div>
</template>

<script>
import '../../../static/css/icalendar.css'
import '../../../static/js/icalendar.min.js'

//import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import Dialog from '@/components/Dialog'

export default {
    name: 'listgames',
    mixins: [Mixin],
    components: {
        HeaderNav,
        Dialog
    },
    data () {
        return {
            select_type:'FT',
            begin_time:'',
            dataList:[]
        }
    },
    mounted: function () {
        let _self = this;
        _self.getFuWuTime();

        // 时间初始化
        let begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间
        _self.begin_time= _self.setAmerTime('#begin_time','dayoff');
        begincalendar.init({
            'trigger': '#begin_time',
            'type': 'date',
            defaultValue:_self.begin_time,
        });

    },
    methods: {
        // 日期快速选择
        chooseDateAction: function(e,datebegin,dateend) {
            this.begin_time= datebegin;
            this.end_time= dateend;
        },
        // 查询赛果
        seachDataActtion:function () {
            let _self = this;
            if(_self.submitflag){
                return false;
            }
            let pars = {
                game_type :_self.select_type , // 篮球 BK ，足球 FT
                list_date :_self.begin_time
            };
            _self.submitflag = true;
            return new Promise((resolve, reject)=>{
                _self.axios({
                    method: 'post',
                    params: pars,
                    url: _self.ajaxUrl.sportresult
                }).then(res=>{
                    if(res){
                        _self.submitflag = false;
                        let rest = res.data;
                        if(rest.status =='200'){
                            _self.dataList = rest.data.length?rest.data:[];
                        }else{
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }

                        resolve();
                    }
                }).catch(res=>{
                    _self.submitflag = false;
                    console.log('赛果请求失败');
                    reject(res);
                });

            })
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .moneychoose td { width: 25%;}
</style>
