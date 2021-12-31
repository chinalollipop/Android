<template>
    <div >
        <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>
        <Dialog ref="autoDialog" pa_dialogtitle="" />
        <div class="content-center">
            <main class="main promo">
                <!--  标签 -->
                <div class="promo_nav">
                    <ul class="ProTab_nav css_flex">
                        <li class="on"  data-type="all">全部</li>
                        <li v-for="(lists,item) in resData.categoryList" :data-type="lists.id">{{lists.name}}</li>
                    </ul>
                </div>

                <ul class="ProTab_con">
                    <li class="ProTab_con_1" style="display:block">
                        <div  v-for="(plists,item) in resData.promoList" class="material-card " :class="'promos_'+plists.type" >
                            <div class="promotions_title_box" :class="'promos_id_'+plists.id" :id="'promos_id_'+plists.id" @click="showDetails($event)">
                                <img :src="plists.imgurl">
                                <div class="promotions_title">{{plists.title}}</div>
                            </div>
                            <div class="material-card-content">
                                <div class="line"></div>
                                <a v-if="plists.type==7" href="javascript:;" class="promos_btn pro_btn" :class="'btn_'+plists.flag" :data-api="plists.ajaxurl" :data-type="plists.flag"></a>
                            <img :src="plists.contenturl">
                        </div>
                        </div>

                    </li>
                </ul>
            </main>
        </div>

        <FooterNav :class="apptip=='app' && 'hide-cont'" />
    </div>
</template>

<script>

    //import axios from 'axios'
    import Mixin from '@/Mixin'
    import HeaderNav from '@/components/Header'
    import FooterNav from '@/components/Footer'
    import Dialog from '@/components/Dialog'

    export default {
        name: 'Index',
        mixins:[Mixin],
        components: {
            HeaderNav,
            FooterNav,
            Dialog
        },
        data () {
            return {
                isChoose:'', // 展示列表
                resData:[],
                apptip:''
            }
        },
        mounted: function () {
            let _self = this ;

            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数

            _self.getPromos();
            _self.ProTab_Promo();

        },
        methods:{
            /* 标签切换 */
             ProTab_Promo: function(){
                $('.ProTab_nav').on('click','li',function () {
                    let type = $(this).attr('data-type');
                    $(this).addClass('on').siblings().removeClass('on');
                    if(type == 'all'){ // 全部
                        $('.material-card').show() ;
                    }else{
                        $('.material-card').hide() ;
                        $(".promos_"+type).show();
                    }
                })
            },
            /* 展开详情 */
            showDetails: function (e) {
                let cla = 'triggered';
                let cur = $(e.currentTarget);
                cur.parent().toggleClass(cla);
            },
            /* 获取优惠 */
            getPromos: function () {
                let _self =this;
                if(_self.submitflag){
                    return false ;
                }
                let senddata = {};
                _self.submitflag = true;
                return new Promise((resolve, reject)=>{
                    _self.axios({
                        method: 'post',
                        params: senddata,
                        url: _self.ajaxUrl.promo
                    }).then(res=>{
                        if(res){
                            _self.submitflag = false;
                            _self.resData = res.data.data;
                            resolve(res)
                        }
                    }).catch(res=>{
                        _self.submitflag = false;
                        console.log('获取优惠失败');
                        reject(res);
                    });
                });
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    p,dl,dd{margin: 0;padding: 0;}
    .deposit .tab .item, .deposit .tab .expand{ margin-top: 0;border-top:none;}
    .promo_nav {overflow-x: auto; }
    .ProTab_nav {width: 170%;}
    .ProTab_nav li {width: 12%;}
    /*优惠活动*/
    .ProTab_con{padding: 1% 2% 0 2%}
    .promotions_title_box {height:auto; }
    .promotions_title, .pro_btn {text-align: center;font-size: 15px; margin: 6px 0 8px 0; width: 100%; line-height: 26px; color: #757575; font-weight: 600;}
    .material-card-content {position:relative;max-height:0px;}
    .word_pro_sl b{color:#00b3e0;}
    .promotions_title_box img{width: 100%; height: auto; }
    .material-card-content img {width: 100%;}
    .material-card-content a:hover{background-color:transparent;}
    .material-card-content .pro_btn{position:absolute;display:block;width:32%;height:4rem;margin:80% 0 0 70%;background:transparent}

</style>
