<template>
  <div class="agent_bg">
    <HeaderNav pa_showback="" pa_title="" class="" :class="apptip=='app' && 'hide-cont'"/>

    <div class="content-center">
        <div class="agent_con">
            <p>
                我们充满活力拥有品牌最诚信，并且有前瞻性以及高度的粘附力，期待您的加入！
            </p>
            <div class="btn">
                <router-link class="linear-color-1" to="agent?type=1"> 联盟方案 </router-link>
                <router-link class="linear-color-1" to="agent?type=2"> 联盟协议 </router-link>
                <router-link class="linear-color-1" to="agent?type=4"> 代理注册 </router-link>
            </div>
        </div>
    </div>

      <FooterNav :class="apptip=='app' && 'hide-cont'" />
  </div>
</template>

<script>

    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'

    export default {
        name: 'agentsIndex',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav
        },
        data () {
            return {
                apptip:''
            }
        },
        mounted: function () {
            let _self = this ;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
        },
        methods:{

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .agent_bg{min-height: 100vh;background: url(/static/images/8msport/dl_bg.jpg) center no-repeat;background-position-y: 3rem;background-size: 100%;}
    .agent_con{margin-top:71%;color:#000;line-height:2rem;padding:0 10%}
    .agent_con .btn a{display:block;width:70%;margin:1.5rem auto;line-height:2.8rem;border-radius:20px}
</style>
