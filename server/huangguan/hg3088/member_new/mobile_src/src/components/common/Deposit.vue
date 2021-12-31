<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center deposit">
      <div class="bg_yy">
        <div v-if="tpl_name=='8msport/'" class="tip_title"><span class="linear-color-1">1</span>请选择支付方式及通道</div>
        <div class="tab">
          <div class="deposit-nav deposit_one">
            <template v-for="(list,index) in dataLsist" >
                <a :href="list.api+'?bankid='" :key="index" class="item" v-if="list.title=='快速充值'" target="_blank">
                  <i class="bank_img" :class="'bank_img_'+list.id"></i>
                  <span>{{list.title}}</span>
                </a>
                <router-link :to="'depositsec?bankid='+list.bankid+'&typename='+list.title+'&czapi='+list.api" :key="index" class="item" v-else>
                  <i class="bank_img" :class="'bank_img_'+list.id"></i>
                  <span>{{list.title}}</span>
                </router-link>
            </template>

          </div>

        </div>
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
  name: 'deposit',
    mixins:[Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
  data () {
    return {
        dataLsist:[]
    }
  },
    mounted: function () {
        let _self = this ;
        _self.judgeTestFlag();
        _self.getUserMoney(); // 获取余额
        _self.getDepositList();

    },
    methods:{
    /* 获取存款列表 */
    getDepositList: function () {
        let _self = this;
            _self.axios({
                method: 'post',
                params: {},
                url: _self.ajaxUrl.deposit
            }).then(res=>{
                if(res){
                    let rest = res.data;
                    _self.dataLsist = rest.data;
                }
            }).catch(res=>{
                console.log('银行列表请求失败');
            });

    }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
