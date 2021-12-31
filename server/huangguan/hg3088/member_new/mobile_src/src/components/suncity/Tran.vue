<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center">

      <div id="creditsChangeBox" class="user">
        <div class="top_user_ye">
          <div><p>中心钱包余额</p><span class="hg_money">{{userMoney}}</span></div>
          <div><p>彩票余额</p><span class="gmcp_money">{{gmcp_Money}}</span></div>
          <div><p>AG余额</p><span class="ag_money">{{ag_Money}}</span></div>
          <div><p>开元棋牌余额</p><span class="ky_money">{{ky_Money}}</span></div>
          <!--<div><p>皇冠棋牌余额</p><span class="ff_money">0.00</span></div>-->
          <div><p>VG棋牌余额</p><span class="vg_money">{{vg_Money}}</span></div>
          <div><p>快乐棋牌余额</p><span class="kl_money">{{kl_Money}}</span></div>
          <div><p>乐游棋牌余额</p><span class="ly_money">{{ly_Money}}</span></div>
          <div><p>MG电子余额</p><span class="mg_money">{{mg_Money}}</span></div>
          <div><p>OG视讯余额</p><span class="og_money">{{og_Money}}</span></div>
          <div><p>BBIN视讯余额</p><span class="bbin_money">0.00</span></div>
          <div><p>CQ9电子余额</p><span class="cq_money">{{cq_Money}}</span></div>
          <div><p>MW电子余额</p><span class="mw_money">{{mw_Money}}</span></div>
          <div><p>FG电子余额</p><span class="fg_money">{{fg_Money}}</span></div>
          <div><p>泛亚电竞余额</p><span class="avia_money">{{avia_Money}}</span></div>
          <div><p>雷火电竞余额</p><span class="fire_money">{{fire_Money}}</span></div>
        </div>

        <ul>
          <li>
            <h3>转出</h3>
            <select  name="f_blance" id="f_blance" v-model="f_blance">
              <option value="">请选择钱包</option>
              <option value="hg">中心钱包</option>
              <!--<option value="sc">皇冠体育余额</option>-->
              <option value="cp" v-if="tplnameList.indexOf(tpl_name)>=0">彩票余额</option>
              <option value="gmcp" v-else>国民彩票余额</option>
              <template v-if="memberData.test_flag =='0'"> <!-- 正式账号 -->
                <option value="ag">AG余额</option>
                <option value="og">OG视讯余额</option>
                <option value="bbin">BBIN视讯余额</option>
                <option value="ky">开元棋牌余额</option>
                <!-- <option value="ff">皇冠棋牌余额</option>-->
                <option value="vg">VG棋牌余额</option>
                <option value="kl">快乐棋牌余额</option>
                <option value="ly">乐游棋牌余额</option>
                <option value="mg">MG电子余额</option>
                <option value="cq">CQ9电子余额</option>
                <option value="mw">MW电子余额</option>
                <option value="fg">FG电子余额</option>
                <option value="avia">泛亚电竞余额</option>
                <option value="fire">雷火电竞余额</option>
              </template>
            </select>
          </li>
          <li>
            <h3>转入</h3>
            <select name="t_blance" id="t_blance" v-model="t_blance">
              <option value="">请选择钱包</option>
              <option value="hg">中心钱包</option>
              <!--<option value="sc">皇冠体育余额</option>-->
              <option value="cp" v-if="tplnameList.indexOf(tpl_name)>=0">彩票余额</option>
              <option value="gmcp" v-else>国民彩票余额</option>
              <template v-if="memberData.test_flag =='0'"> <!-- 正式账号 -->
                <option value="ag" >AG余额</option>
                <option value="og">OG视讯余额</option>
                <option value="bbin">BBIN视讯余额</option>
                <option value="ky">开元棋牌余额</option>
                <!-- <option value="ff">皇冠棋牌余额</option>-->
                <option value="vg">VG棋牌余额</option>
                <option value="kl">快乐棋牌余额</option>
                <option value="ly">乐游棋牌余额</option>
                <option value="mg">MG电子余额</option>
                <option value="cq">CQ9电子余额</option>
                <option value="mw">MW电子余额</option>
                <option value="fg">FG电子余额</option>
                <option value="avia">泛亚电竞余额</option>
                <option value="fire">雷火电竞余额</option>
              </template>
            </select>
          </li>

          <li>
            <h3>金额：</h3><input class="enter money-textbox" placeholder="金额" name="blance" v-model="v_amount">
          </li>
          <li>
            <div v-for="(list,index) in chMoneyData" :key="index" class="sbtn moneychoose">
              <button @click="chooseMoney(list.val_1)" :class="v_amount==list.val_1 && 'active'">{{list.val_1}}</button>
              <button @click="chooseMoney(list.val_2)" :class="v_amount==list.val_2 && 'active'">{{list.val_2}}</button>
              <button @click="chooseMoney(list.val_3)" :class="v_amount==list.val_3 && 'active'">{{list.val_3}}</button>
              <button @click="chooseMoney(list.val_4)" :class="v_amount==list.val_4 && 'active'">{{list.val_4}}</button>
              <button @click="chooseMoney(list.val_5)" :class="v_amount==list.val_5 && 'active'">{{list.val_5}}</button>
            </div>
          </li>
          <button class="close_ft_nav" name="trans_blance" @click="tranUserMoney">确认转帐</button>
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

    <FooterNav />
  </div>
</template>

<script>

//import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
  name: 'tran',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
      chMoneyData:[
          {val_1:100,val_2:500,val_3:1000,val_4:2000,val_5:5000}
      ]
    }
  },
    mounted: function () {
        let _self = this ;
        _self.getUserMoney(); // 上线打开

        // 获取各平台余额
        let pars = {action:'b'};
        _self.tranMoneyAction('og','',pars);
        _self.tranMoneyAction('avia','',pars);
        _self.tranMoneyAction('fire','',pars);
        _self.tranMoneyAction('gmcp','',pars);
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
    },
    methods:{
      /* 额度转换 */
        tranUserMoney:function (data) {
            let _self =this;
            if(_self.submitflag){
                _self.$refs.autoDialog.setPublicPop('请勿重复提交');
                return false;
            }

            _self.v_amount = Number(_self.v_amount);

            if(!(_self.f_blance && _self.t_blance) ){
                _self.$refs.autoDialog.setPublicPop('请选择转出和转入方');
                return false;
            }
            if(_self.v_amount<1 || isNaN(_self.v_amount)){
                _self.$refs.autoDialog.setPublicPop('请填写正确转账金额');
                return false;
            }
            if( _self.f_blance == _self.t_blance ){
                _self.$refs.autoDialog.setPublicPop("转出方与转入方相同");
                return false;
            }
            if((_self.f_blance!=='hg' && _self.t_blance !=='hg')){
                _self.$refs.autoDialog.setPublicPop('真人,电竞,彩票,棋牌,电子不能相互转账');
                return false ;
            }
            setTimeout(()=>{
                _self.submitflag = false;
            },2000);
            _self.submitflag = true ;
            let dat={
                id:_self.memberData.userid,
                uid: _self.memberData.Oid,
                userName: _self.memberData.UserName,
                action:'fundLimitTrans',
                f: _self.f_blance,
                t: _self.t_blance,
                b: _self.v_amount
            };
            _self.tranMoneyAction(_self.f_blance,_self.t_blance,dat);

        },
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .top_user_ye{background:#fff;width:96%;margin:1rem auto 0;overflow-y:hidden;border-radius:5px}
  .top_user_ye>div{float:left;width:33.33%;color:#2d3134;text-align:center;height: 4rem;padding:1rem 0}
  .top_user_ye>div:nth-child(-n+12){border-bottom:1px solid #ccc}
  .top_user_ye>div span {color: #cc8f28;}
</style>
