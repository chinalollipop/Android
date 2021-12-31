<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">
      <div id="creditsChangeBox" class="user">
        <ul class="platform">
          <li>
            <p>泛亚电竞</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="avia" data-from="hg" data-to="avia">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="avia" data-from="avia" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="avia_money">{{avia_Money}}</h6>
          </li>
          <li>
            <p>雷火电竞</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="fire" data-from="hg" data-to="fire">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="fire" data-from="fire" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="fire_money">{{fire_Money}}</h6>
          </li>
          <li v-if="tplnameList.indexOf(tpl_name)>=0">
            <p>彩票平台</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="cp" data-from="hg" data-to="cp" >转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="cp" data-from="cp" data-to="hg" >转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="bbin"  data-status="working"></i>
            </div>
            <h6 class="cp_money">{{cp_Money}}</h6>
          </li>
          <li v-else>
              <p>国民彩票平台</p>
              <div class="turn_inout" >
                  <a href="javascript:;" class="transfer_all" data-platform="gmcp" data-from="hg" data-to="gmcp" >转入</a> |
                  <a href="javascript:;" class="transfer_all" data-platform="gmcp" data-from="gmcp" data-to="hg" >转出</a>
              </div>
              <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
                  <i class="fa fa-refresh fa-spin" com="bbin"  data-status="working"></i>
              </div>
              <h6 class="gmcp_money">{{gmcp_Money}}</h6>
          </li>
          <li>
            <p>AG平台</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="ag" data-from="hg" data-to="ag">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="ag" data-from="ag" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="ag_money">{{ag_Money}}</h6>
          </li>
          <li>
            <p>OG视讯</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="og" data-from="hg" data-to="og">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="og" data-from="og" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="og_money">{{og_Money}}</h6>
          </li>
          <li>
            <p>BBIN视讯</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="bbin" data-from="hg" data-to="bbin">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="bbin" data-from="bbin" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="bbin_money">{{bbin_Money}}</h6>
          </li>
          <li>
            <p>开元棋牌</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="ky" data-from="hg" data-to="ky">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="ky" data-from="ky" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="ky_money">{{ky_Money}}</h6>
          </li>
          <li>
            <p>VG棋牌</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="vgqp" data-from="hg" data-to="vg">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="vgqp" data-from="vg" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag"  data-status="working"></i>
            </div>
            <h6 class="vg_money">{{vg_Money}}</h6>
          </li>
          <li>
            <p>快乐棋牌</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="klqp" data-from="hg" data-to="kl">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="klqp" data-from="kl" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="kl_money">{{kl_Money}}</h6>
          </li>
          <li>
            <p>乐游棋牌</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="lyqp" data-from="hg" data-to="ly">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="lyqp" data-from="ly" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="ly_money">{{ly_Money}}</h6>
          </li>
          <li>
            <p>MG电子</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="mg" data-from="hg" data-to="mg">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="mg" data-from="mg" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="mg_money">{{mg_Money}}</h6>
          </li>
          <li>
            <p>CQ9电子</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="cq" data-from="hg" data-to="cq">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="cq" data-from="cq" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="cq_money">{{cq_Money}}</h6>
          </li>
          <li>
            <p>MW电子</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="mw" data-from="hg" data-to="mw">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="mw" data-from="mw" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="mw_money">{{mw_Money}}</h6>
          </li>
          <li>
            <p>FG电子</p>
            <div class="turn_inout" >
              <a href="javascript:;" class="transfer_all" data-platform="fg" data-from="hg" data-to="fg">转入</a> |
              <a href="javascript:;" class="transfer_all" data-platform="fg" data-from="fg" data-to="hg">转出</a>
            </div>
            <div v-if="tplnameSecList.indexOf(tpl_name)>=0"  class="plateform_re">
              <i class="fa fa-refresh fa-spin" com="ag_ag" data-status="working"></i>
            </div>
            <h6 class="fg_money">{{fg_Money}}</h6>
          </li>
         
        </ul>
        <template v-if="showmore_dis">
          <select class="plateform_sel" v-model="ch_platform">
            <option value=""> 请选择平台 </option>
            <option value="avia"> 泛亚电竞平台 </option>
            <option value="fire"> 雷火电竞平台 </option>
            <option value="gmcp"> 彩票平台 </option>
            <option value="ag"> AG平台 </option>
            <option value="og"> OG平台 </option>
            <option value="bbin"> BBIN视讯 </option>
            <option value="ky"> 开元棋牌平台 </option>
            <!--<option value="ff"> 皇冠棋牌平台 </option>-->
            <option value="vg"> VG棋牌平台 </option>
            <option value="kl"> 快乐棋牌平台 </option>
            <option value="ly"> 乐游棋牌平台 </option>
            <option value="mg"> MG平台 </option>
            <option value="mw"> MW电子平台 </option>
            <option value="fg"> FG电子平台 </option>
          </select>
          <div class="platform_input">
            <p>金额（元）</p>
            <label>￥</label><input class="alert_input money-textbox" placeholder="请输入转账金额" name="alert_blance" v-model="v_amount">
          </div>
          <div class="change_btn">
            <a class="btn_1" href="javascript:;" @click="changeMoney($event,'in')">余额转入</a>
            <a class="btn_2" href="javascript:;" @click="changeMoney($event,'out')">余额转出</a>
          </div>
        </template>

        <ul>
          <li>
            <h3>温馨提示：</h3>
            <ol>
              <li>1.转账前请退出游戏或游戏投注界面。</li>
              <li>2.不参与活动时, 户内转账金额不能少于 1元，户内转账不收取任何手续费。</li>
              <li>3.如遇网速较慢时，请耐心等候片刻，不要多次重复提交。</li>
            </ol>
          </li>
        </ul>
      </div>
    </div>

    <!--转账弹窗-->
    <div v-show="changeFlag" class="change_pop_all">
      <div class="change_pop_bg" ></div>
      <div class="Pop-up change_Pop-up pop_close" >
        <div class="btn_close pop_cls close_event" @click="closeChangeBox"><i class="fa fa-times"></i></div>
        <div class="pop_title"> </div>
        <input type="number" class="alert_input money-textbox" placeholder="请输入转账金额" name="alert_blance" v-model="v_amount">
        <button class="change_login_btn" @click="changeMoney($event)">确定</button>
      </div>
    </div>

    <FooterNav />
  </div>
</template>

<script>

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
      changeFlag: false,
      ch_platform:''
    }
  },
    mounted: function () {
        let _self = this ;

        _self.judgeTestFlag();
        // 获取各平台余额
        let pars = {action:'b'};
        if(_self.tplnameList.indexOf(_self.tpl_name)<0){ // 国民彩票
            _self.tranMoneyAction('gmcp','',pars);
        }
        _self.tranMoneyAction('og','',pars);
        _self.tranMoneyAction('avia','',pars);
        _self.tranMoneyAction('fire','',pars);
        _self.tranMoneyAction('ag','',pars);
        _self.tranMoneyAction('bbin','',pars);
        _self.tranMoneyAction('ky','',pars);
        _self.tranMoneyAction('vg','',pars);
        _self.tranMoneyAction('kl','',pars);
        _self.tranMoneyAction('ly','',pars);
        _self.tranMoneyAction('mg','',pars);
        _self.tranMoneyAction('cq','',pars);
        _self.tranMoneyAction('mw','',pars);
        _self.tranMoneyAction('fg','',pars);

        _self.$nextTick(()=>{ // 要不判断的彩票节点取不到
            _self.showChangeBox();
        })

    },
    methods:{
      //关闭弹窗
      closeChangeBox:function(){
          this.changeFlag = false;
      },
      /* 显示转账 */
      showChangeBox:function () {
          let _self = this;
          $('.turn_inout').on('click','a',function () {
              _self.changeFlag = true;
              let $cur = $(this);
              let f_blance = $cur.attr('data-from') ; // 转出方
              let t_blance = $cur.attr('data-to') ; // 转入方
              let t_type = $cur.attr('data-type') ; // 特殊情况
              let fromplat = $cur.attr('data-platform');// 当前转账平台

              _self.v_amount = 100; // 重置初始值
              _self.f_blance = f_blance;
              _self.t_blance = t_blance;

          })
      },
      changeMoney:function (e,type) { // type: in 转入到第三方，out 转入到钱包中心
          let _self = this;
          let blance = Number(_self.v_amount) ;
          if(blance<1 || isNaN(blance)){
              _self.changeFlag = false;
              _self.$refs.autoDialog.setPublicPop('请填写正确转账金额');
              return false;
          }
          if(_self.showmore_dis){
              if(!_self.ch_platform){
                  _self.$refs.autoDialog.setPublicPop('请选择平台！');
                  return false;
              }
            if(type=='in'){
                _self.f_blance = 'hg';
                _self.t_blance = _self.ch_platform;
            }else if(type=='out'){
                _self.f_blance = _self.ch_platform;
                _self.t_blance = 'hg';
            }
          }

          let dat={
              id:_self.memberData.userid,
              uid: _self.memberData.Oid,
              userName: _self.memberData.UserName,
              action:'fundLimitTrans',
              f: _self.f_blance,
              t: _self.t_blance,
              b: blance
          };
          _self.tranMoneyAction(_self.f_blance,_self.t_blance,dat);
          _self.changeFlag = false;
              
      }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
