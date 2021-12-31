<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>

    <div class="content-center">
        <div class="content">
            <p class="title">{{wh_title}}</p>
            <div class="tips">{{wh_content}}</div>
            <p class="ico"><img src="/static/images/ico.png" width="150" height="150" alt="维护"></p>
            <p class="bottom">您可以进行平台其他游戏！<br>感谢您的耐心等候。</p>
        </div>
    </div>

    <FooterNav />
  </div>
</template>

<script>

import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'

export default {
    name: 'maintenance',
    mixins: [Mixin],
    components: {
        HeaderNav,
        FooterNav
    },
    data () {
        return {
            wh_title:'',
            wh_content:''
        }
    },
    mounted: function () {
        let _self = this;
        _self.wh_title = this.$route.query.title; // 获取参数
        _self.wh_content = this.$route.query.content; // 获取参数
    },
    methods: {


    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .content{width:300px;border:1px solid #493721;margin:50px auto;font-size:14px}
    .content .title{text-align:center;background-color:#493721;margin:0;line-height:40px;color:#fff;font-size:16px}
    .tips{color:black;font-size:14px;padding:0;padding-top:20px;width:60%;overflow:hidden;margin:0 auto}
    .tips span{color:red}
    .content .ico{text-align:center;margin:0 auto}
    .content .bottom{text-align:center;padding-bottom:20px;font-size:14px;color: #000;}
</style>
