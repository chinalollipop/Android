<template>
  <div >
    <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>

    <div class="content-center deposit">
      <div v-if="showType">
        <img style="width: 100%" :src="'/static/images/appinstall/'+tplName+showType+'_install_guide.png'" alt="">
      </div>

        <div v-else class="app_content">
            <div class="left app_bg">
                <img class="img_ios" :src="baseSettingData.download_ios_url" alt="APP下载" >
                <img class="img_android" :src="baseSettingData.download_android_url" alt="APP下载/" style="display: none;">
            </div>
            <div class="right app_title">
                <p class="title_sm"> 扫一扫二维码下载 </p>
                <p class="title_big"> 手机APP </p>
                <a href="javascript:;" class="ios active" data-to="ios">
                    <span class="icon ">
                        <i class="ios_icon"> </i>
                    </span>
                    <span class="text"> ios下载 </span>
                </a>
                <a href="javascript:;" class="android " data-to="android">
                    <span class="icon">
                         <i class="android_icon"> </i>
                    </span>
                    <span class="text"> 安卓下载 </span>
                </a>
            </div>
        </div>
    </div>

      <FooterNav :class="apptip=='app' && 'hide-cont'"/>
  </div>
</template>

<script>

    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'

    export default {
        name: 'appdownload',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav
        },
        data () {
            return {
              showType:'',
              tplName:'',  // 不存在就取根目录
              apptip:''
            }
        },
        mounted: function () {
            let _self = this;

          _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
          _self.changeEwm();
          _self.showType = this.$route.query.motype; // 获取参数
          if(_self.tpl_name=='nbet365/'){
            _self.tplName = 'bet365/';
          }else {
            _self.tplName = _self.tpl_name;
          }

        },
        methods:{
            // 切换二维码
            changeEwm:function () {
                $('.app_title a').on('click',function () {
                    var type = $(this).attr('data-to');
                    $(this).addClass('active').siblings().removeClass('active');
                    $('.app_bg img').hide();
                    $('.img_'+type).show();
                })
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .app_content {padding: 20px 10px;max-width: 640px;margin: 0 auto;overflow: hidden;}
    .app_bg {width: 12rem;height: 12rem;background: url(/static/images/appdownload_bg.png) no-repeat;background-size: 100%;}
    .app_bg img {width: 95%;}
    .app_title {width: calc(100% - 14rem);margin-top: 1rem;}
    .app_title p {color: #2c8dbc;}
    .title_sm {font-size: 1.2rem;}
    .title_big {font-size: 1.8rem;}
    .app_title>a {display:block;background: #2a8ebf;margin: 10px auto;padding: 4px 0;border-radius: 50px;width: 80%;display: flex;font-size: .9rem;}
    .app_title>a.active {background: #06a1ec;}
    .app_title>a .icon {display: inline-block;flex: 1.5;}
    .app_title>a .icon i {display: inline-block;width: 18px;height: 18px;}
    .app_title>a .text {flex: 3;text-align: left;}
    .ios_icon {background: url(/static/images/ios.png) no-repeat;background-size: 100%;}
    .android_icon {background: url(/static/images/az.png) no-repeat;background-size: 100%;}
</style>
