<template>
    <div >
        <template v-if="tpl_name.indexOf('bet365')>=0">
            <div class="mask_footer" v-show="showCZTX || showWD" @click="closeAllBg"></div>
            <!-- 在线存取款 -->
            <div class="zxcqk_div css_flex" v-show="showCZTX">
                <router-link to="deposit">
                    <i class="fa fa-archive"></i>
                    <p>在线存款</p>
                </router-link>
                <router-link to="withdraw">
                    <i class="fa fa-usd"></i>
                    <p>在线取款</p>
                </router-link>
            </div>
            <!-- 我的 -->
            <div class="wd_div" v-show="showWD">
                <template  v-for="(lists,item) in menusList">
                    <a :key="item" v-if="lists.iClassName=='fa-sign-out'" @click="loginOut" class="footer_lgout" :class="userName && 'footer_lgin'"> <!-- 退出 -->
                        <i class="fa" :class="lists.iClassName"></i>
                        <p>{{lists.name}}</p>
                    </a>
                    <router-link :key="item" :to="lists.path" v-else>
                        <i class="fa" :class="lists.iClassName"></i>
                        <p>{{lists.name}}</p>
                    </router-link>
                </template>

            </div>
        </template>

        <div id="footer" :class="sportRoute.indexOf($route.path)>=0?'sport_footer':''">
            <template v-for="(list,index) in menus" >
                <a :href="$parent.baseSettingData.service_meiqia" :key="index" :class="`${list.path == $route.path && 'active'} ${list.classSec}`"  v-if="list.classSec=='online-server'" target="_blank">
                    <i :class="list.className"></i>
                    <p>{{list.name}}</p>
                </a>
                <!-- 兼容 bet365 -->
                <a href="javascript:;" :key="index" :class="`${list.path == $route.path && 'active'} ${list.classSec}`" :data-to="list.classSec" v-else-if="list.path=='none'" >
                    <i :class="list.className"></i>
                    <p>{{list.name}}</p>
                </a>
                <router-link :key="index" :class="`${list.path == $route.path && 'active'} ${list.classSec}`" :to="list.path" :data-to="list.classSec" v-else>
                    <i :class="list.className"></i>
                    <p>{{list.name}}</p>
                </router-link>
            </template>
        </div>
    </div>
</template>

<script>
    import Mixin from '@/Mixin'

    export default {
        name: 'FooterNav',
        mixins:[Mixin],
        data :function() {
            return {
                menus:[],
                menus_0086:[
                    { name:'首页', classSec:'to-home', className:'index_fa fa fa-home', path:'/home'},
                    { name:'存款', classSec:'to-deposit', className:'index_fa fa fa-credit-card', path:'/deposit'},
                    { name:'优惠活动', classSec:'to-promos', className:'index_fa fa fa-promo fa-gift', path:'/promo'},
                    { name:'客服', classSec:'online-server', className:'index_fa fa fa-commenting', path:'/service'},
                    { name:'我的', classSec:'to-myaccount', className:'index_fa fa fa-user-circle', path:'/myaccount'}
                ],
                menus_6668:[
                    { name:'优惠', classSec:'to-promos', className:'index_fa fa-promo fa-gift', path:'/promo'},
                    { name:'存款', classSec:'to-deposit', className:'index_fa fa fa-credit-card', path:'/deposit'},
                    { name:'首页', classSec:'to-home', className:'index_fa fa fa-home', path:'/home'},
                    { name:'客服', classSec:'online-server', className:'index_fa fa fa-commenting', path:'/service'},
                    { name:'我的', classSec:'to-myaccount', className:'index_fa fa fa-user-circle', path:'/myaccount'}
                ],
                menus_bet365:[
                    { name:'首页', classSec:'to-home', className:'index_fa fa fa-home', path:'/home'},
                    { name:'优惠活动', classSec:'to-promos', className:'fa fa-promo fa-gift', path:'/promo'},
                    { name:'充值提现', classSec:'to-deposit', className:'index_fa fa fa-credit-card', path:'none'},
                    { name:'在线客服', classSec:'online-server', className:'index_fa fa fa-commenting', path:'/service'},
                    { name:'我的', classSec:'to-myaccount', className:'index_fa fa fa-user-circle', path:'none'}
                ],
                showCZTX:false,// 充值提现
                showWD:false,// 我的
                menusList:[
                    { name:'额度转换', className:'',iClassName:'fa-retweet', path:'tran'},
                    { name:'资金流水', className:'',iClassName:'fa-fire', path:'depositrecord'},
                    { name:'注单查询', className:'',iClassName:'fa-life-ring', path:'betrecord'},
                    { name:'消息公告', className:'',iClassName:'fa-volume-up', path:'moremessage'},
                    { name:'账户中心', className:'',iClassName:'fa-address-book', path:'mydetail'},
                    { name:'银行卡', className:'',iClassName:'fa-credit-card', path:'bankcard'},
                    { name:'代理加盟', className:'',iClassName:'fa-handshake-o', path:'agent'},
                    { name:'联系我们', className:'',iClassName:'fa-comments-o', path:'contactus'},
                    { name:'安全退出', className:'',iClassName:'fa-sign-out', path:''}

                ]

            }
        },
        mounted:function(){
            let _self = this;
            if(_self.tpl_name=='6668/' || _self.tpl_name=='8msport/'){
                _self.menus = _self.menus_6668;
            }else if(_self.tpl_name.indexOf('bet365')>=0){
                _self.menus = _self.menus_bet365;
            }else{
                _self.menus = _self.menus_0086;
            }
            _self.$nextTick(()=>{
                if(_self.tpl_name.indexOf('bet365')>=0){
                    _self.showMoreTag();
                }

            })
        },
        methods: {
           /* bet365 充值提现，我的 */
           showMoreTag:function(){
               let _self = this;
               $('#footer a').on('click',function () {
                   let type = $(this).attr('data-to');
                   //$(this).addClass('active').siblings().removeClass('active');
                   if(type=='to-myaccount'){
                       if(_self.showCZTX){
                           _self.showCZTX = false;
                       }
                       if(_self.showWD){
                           _self.showWD = false;
                       }else{
                           _self.showWD = true;
                       }

                   }else if(type=='to-deposit'){
                       if(_self.showWD){
                           _self.showWD = false;
                       }
                       if(_self.showCZTX){
                           _self.showCZTX = false;
                       }else{
                           _self.showCZTX = true;
                       }
                   }
               })

           },
            closeAllBg:function () {
                let _self = this;
                _self.showCZTX = false;
                _self.showWD = false;
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .mask_footer{display: block;z-index: 19;}

</style>
