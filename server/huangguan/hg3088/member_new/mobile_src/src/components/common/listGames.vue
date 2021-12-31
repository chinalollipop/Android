<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>

    <div class="content-center">
        <ul class="game_list">
            <li v-for="(list,index) in gameList" :key="index">
                <router-link :to="list.path+'?gametype='+list.type+'&gamename='+list.name">
                    <span class="gamelogo" :class="list.type+'_icon'"></span>
                    <span>{{list.name}}</span>
                </router-link>
            </li>
        </ul>
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
    name: 'listgames',
    mixins: [Mixin],
    components: {
        HeaderNav,
        FooterNav
    },
    data () {
        return {
            gameList:[
                { name:'FG电子', type:'fg', path:'/games'},
                { name:'AG电子', type:'aggame', path:'/games'},
                { name:'CQ9电子', type:'cq', path:'/games'},
                { name:'MG电子', type:'mg', path:'/games'},
                { name:'MW电子', type:'mw', path:'/games'}
            ],
        }
    },
    mounted: function () {
        let _self = this;
        _self.judgeTestFlag();

    },
    methods: {


    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .game_list{width:94%;margin:5% auto;overflow:hidden}
    .game_list li{width:47%;height:5rem;float:left;background:rgba(250,250,250,1);border-radius:5px;box-shadow:0px 0px 4px #bbb;margin:2%;position:relative}
    .game_list li:nth-child(1):after,.game_list li:nth-child(2):after{position:absolute;content:"";display:inline-block;width:2.2rem;height:2.2rem;top:0;right:0;background:url(/static/images/gameicon/remen.png) no-repeat;background-size:contain}
    .game_list li:nth-child(2n-1){margin-left:1%}
    .game_list li:nth-child(2n){margin-right:1%}
    .game_list li a{display:inline-block;width:100%;height:100%;color:rgba(124,124,124,1);font-size:1.2rem}
    .game_list li a span{position:relative;display:inline-block;line-height:5rem;width:54%;text-align:left}
    .game_list li a .gamelogo{float:left;width:40%;height:3rem;margin:1rem 0 0 3%}
    .game_list li a .fg_icon{background:url("/static/images/gameicon/icon_fg.png") center no-repeat;background-size:contain}
    .game_list li a .aggame_icon{background:url("/static/images/gameicon/icon_ag.png") center no-repeat;background-size:contain}
    .game_list li a .cq_icon{background:url("/static/images/gameicon/icon_cq9.png") center no-repeat;background-size:contain}
    .game_list li a .mg_icon{background:url("/static/images/gameicon/icon_mg.png") center no-repeat;background-size:contain}
    .game_list li a .mw_icon{background:url("/static/images/gameicon/icon_mw.png") center no-repeat;background-size:contain}
    .game_list li a span:last-child:after{content:"";display:inline-block;vertical-align:middle;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-box-sizing:border-box;box-sizing:border-box;position:absolute;height:.8rem;width:.8rem;border-right:2px solid #8a8a8a;border-top:2px solid #8a8a8a;margin:2rem 0 0 0;right:7%}
</style>
