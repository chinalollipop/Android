<template>
    <div >
        <!-- APP 下载 只在首页展示-->
        <div v-show="appTip=='true' && $route.path=='/home'" class="app_tip">
            <span class="app_tip_logo" :class="tpl_name=='0086/'?'icon':''"></span>
            <div class="title">
                <p> {{company_name}} APP</p>
                <p> 轻便下载，安全使用</p>
            </div>
            <a class="linear-color-1 download_btn" target="_blank">
                <span class="icon "></span>
                <span>立即下载</span>
            </a>
            <a href="javascript:;" class="app_close" @click="appClose"></a>
        </div>

        <div class="header">
            <router-link v-if="(tpl_name=='suncity/' || tpl_name=='wnsr/' || tpl_name=='jinsha/') && $route.path=='/home'" to="appdownload" class="app_download">
                <i class="index_fa fa-app"></i> <p> APP </p>
            </router-link>
            <a v-if="showBack=='true'" class="icon-back back-active" @click="goBack">&nbsp;&nbsp;返回</a>

            <span class="header_logo">{{headerTitle}}</span>
            <div v-if="$route.path != '/reg'" class="header-right">
                <!--登录后-->
                <template v-if="userName">
                    <!-- 只有bet365 -->
                    <p v-if="tpl_name.indexOf('bet365')>=0" class="p_name">
                        <i class="fa fa-user"></i>
                        <span>{{userName}}</span>
                    </p>
                    <span >
                        <i class="fa fa-database"></i>
                        <p class="hg_money after_login">{{($parent.userMoney>0)?$parent.userMoney:self_userMoney}}</p>
                    </span>
                </template>

                <!-- 登录前-->
                <template v-else>
                    <template v-if="tpl_name.indexOf('bet365')>=0"> <!-- bet365 -->
                        <router-link to="reg" class="a_btn">
                            注册
                        </router-link>
                        <router-link to="login" class="a_btn">
                            登录
                        </router-link>
                    </template>
                    <router-link to="login" v-else> <!-- 其他 -->
                        <i class="fa fa-user-o index_fa"></i>
                        <p>登入/注册</p>
                    </router-link>
                </template>

            </div>
            <router-link v-if="$route.path == '/reg'" class="reg-login-btn" to="login">立即登录</router-link>
        </div>
    </div>
</template>

<script>
    import Mixin from '@/Mixin'
    export default {
        name: 'HeaderNav',
        mixins:[Mixin],
        props:['pa_showback', 'pa_title','pa_money'], // 父组件传值给子组件
        data :function() {
            return {
                showBack:'true', // 是否显示返回按钮
                headerTitle:'',
                appTip:'true',
                self_userMoney: 0
            }
        },
        mounted:function(){
            let _self = this;

            //console.log(typeof(_self.pa_showback))
            _self.showBack = (_self.pa_showback?_self.pa_showback:_self.showBack);
            _self.headerTitle = _self.pa_title;

            _self.self_userMoney = _self.localStorageGet('member_money');

            let sess_app = sessionStorage.getItem('appDownLoadTip');
            _self.appTip = (sess_app=='false'?sess_app:_self.appTip);

            _self.$nextTick(()=>{
                _self.judgeUserAgent();
            })

        },
        methods: {
            appClose :function () {
                this.appTip = 'false';
                sessionStorage.setItem('appDownLoadTip','false');
            },
            // app 下载处理，判断客户端类型
            judgeUserAgent: function () {
                let _self = this;
                var u = navigator.userAgent;
                var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
                var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
                var $download_btn = $('.download_btn');
                var $app_tip = $('.app_tip');
                var andurl = _self.baseSettingData.download_android_exe;
                var iosurl = _self.baseSettingData.download_ios_exe;
                if(_self.tpl_name=='wnsr/'){
                    andurl = _self.baseSettingData.vns_download_android_exe;
                    iosurl = _self.baseSettingData.vns_download_ios_exe;
                }
                //console.log(isAndroid+'=='+isiOS);
                if(isAndroid){
                    $app_tip.find('.icon').addClass('and');
                    $download_btn.attr('href',andurl);
                }
                if(isiOS){
                    $app_tip.find('.icon').addClass('ios');
                    $download_btn.attr('href',iosurl);
                }


            }
        }
    }
</script>
