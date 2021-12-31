<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>

    <div class="content-center deposit">
        <div class="app_content">
          <div class="app-download-guide-box">
            <div class="btn-list-box">
              <p class="first-tip"><span >请仔细阅读安装说明再操作</span></p>
              <router-link :to="'appdownload?motype=huawei'+(apptip?'&tip=app':'')" class="chos-btn"><span >华为手机安装APP和杀毒设置</span></router-link>
              <router-link :to="'appdownload?motype=VIVO'+(apptip?'&tip=app':'')" class="chos-btn"><span >VIVO手机安装APP和杀毒设置</span></router-link>
              <router-link :to="'appdownload?motype=OPPO'+(apptip?'&tip=app':'')" class="chos-btn"><span >OPPO手机安装APP和杀毒设置</span></router-link>
              <router-link :to="'appdownload?motype=XiaoMi'+(apptip?'&tip=app':'')" class="chos-btn"><span >小米手机安装APP和杀毒设置</span></router-link>
              <router-link :to="'appdownload?motype=MeiZu'+(apptip?'&tip=app':'')" class="chos-btn"><span >魅族手机安装APP和杀毒设置</span></router-link>
              <router-link :to="'appdownload?motype=other'+(apptip?'&tip=app':'')" class="chos-btn"><span >其他手机安装APP和杀毒设置</span></router-link>
              <img src="/static/images/appinstall/Android_Guide_Detail.png" alt="">
            </div>

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
        name: 'appinstallation',
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
          let _self = this;
          _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
        },
        methods:{

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  img{width: 100%;margin-top: 1em;}
  .app-download-guide-box .btn-list-box .first-tip{font-weight:300;font-size:1.2rem;margin-top:1em;color: #000}
  .app-download-guide-box .btn-list-box .chos-btn{display:block;font-size: 1.2rem;font-weight: 300;color: white;margin-top: 1.2em;}
  .app-download-guide-box .btn-list-box .chos-btn span{display:inline-block;background-color:#dc3b40;width:16em;height:2.2em;line-height:2.2em;border-radius:.18605rem}

</style>
