<template>
    <div >
        <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>
        <Dialog ref="autoDialog" pa_dialogtitle="" />
        <div class="content-center">
            <!-- 2021 新年活动只展示背景图 -->
          <div v-if="show_bg=='bg'" class="newyear_bg">
            <img src="/static/images/hongbao/hb_bg_6668.jpg?v=1" width="100%">
            <span id="promos_id_newyear_hb"></span>

            <!-- 2021 新年活动 背影 -->
            <div class="hb_mask_1">
              <div id="hongbao_animation"> </div>
            </div>
            <!-- 领取红包成功 -->
            <div class="receiveAfter_1 receiveAfter_act">
              <div class="tip">
                <p>恭喜您获得</p>
                <p> <span class="hb_mount">{{hb_n_mount}}</span><span>元红包</span> </p>
              </div>
              <!-- 关闭红包 -->
              <span class="hb_close_1">领取</span>
            </div>
            <div class="ProTab_con">
              <!-- 申请按钮 -->
              <a class="pro_btn btn_newyear_hb" href="javascript:;" data-api="/api/newyear2021HbApi.php" data-type="newyear_hb"></a>
            </div>
          </div>

          <!-- 活动列表不变 -->
          <div v-else>
            <!--  标签 -->
            <div class="promo_nav">
                <ul class="ProTab_nav css_flex">
                    <li class="on"  data-type="all">全部</li>
                    <li v-for="(lists,item) in resData.categoryList" :data-type="lists.id">{{lists.name}}</li>
                </ul>
            </div>
            <!-- 背影 -->
            <div class="hb_mask_1">
                <div id="hongbao_animation"> </div>
            </div>
            <main class="main promo">

                <ul class="ProTab_con">
                    <li class="ProTab_con_1" style="display:block">
                        <div  v-for="(plists,item) in resData.promoList" class="material-card " :class="'promos_'+plists.type" >
                            <div class="promotions_title_box" :class="'promos_id_'+plists.id" :id="'promos_id_'+plists.id" @click="showDetails($event)">
                                <img :src="plists.imgurl">
                                <div class="promotions_title">{{plists.title}}</div>
                            </div>
                            <div class="material-card-content">
                                <div class="line"></div>
                                <div v-if="plists.flag=='appLuck'" class="appLuckRed" >
                                    <a class="pro_btn btn_appDownload" href="javascript:;" data-api="/download_app_gift_api.php" data-type="appDownload"></a>
                                    <div class="valid_num">
                                        <span class="valid_money">0</span>
                                        <span class="last_times">0</span>
                                    </div>
                                </div>

                                <a href="javascript:;" class="promos_btn pro_btn" :class="'btn_'+plists.flag" :data-api="plists.ajaxurl" :data-type="plists.flag"></a>
                                <img :src="plists.contenturl">
                            </div>
                        </div>

                    </li>
                </ul>
            </main>

          </div>

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
    import hb_animate from '../../../static/js/newyear_hb.js'

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
                appRefer:'',
                postData:{},
                apptip:'',
                prokey:'',
                Oid:'',
                userid:'',
                User_Name:'',
                Agents:'',
                newYearBeginTime:'',
                newYearEndTime:'',
                curtime:'',
                hb_num:0, // 红包数量
                hb_n_mount:0, // 红包金额
                show_bg:'' // 新年活动只展示背景图
            }
        },
        mounted: function () {
            let _self = this ;

            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
            _self.appRefer = _self.$route.query.appRefer?_self.$route.query.appRefer:''; // 获取参数
            _self.prokey = _self.$route.query.prokey?_self.$route.query.prokey:''; // 获取参数
            _self.show_bg = _self.$route.query.showbg?_self.$route.query.showbg:''; // 获取参数
            _self.Oid = _self.$route.query.Oid?_self.$route.query.Oid:''; // 获取参数
            _self.userid = _self.$route.query.userid?_self.$route.query.userid:''; // 获取参数
            _self.User_Name = _self.$route.query.UserName?_self.$route.query.UserName:''; // 获取参数
            _self.Agents = _self.$route.query.Agents?_self.$route.query.Agents:''; // 获取参数

            if(!_self.memberData){ // 兼容APP
                _self.memberData = {};
                _self.memberData.Oid = _self.Oid;
                _self.memberData.userid = _self.userid;
                _self.memberData.UserName = _self.User_Name;
                _self.memberData.test_flag = (_self.Agents=='demoguest'?1:0); // 0 正式，1 测试
            }

            _self.postData = {
                user_id: _self.memberData.userid ,
                username: _self.memberData.UserName ,
            }

            if(_self.show_bg=='bg'){ // 新年活动时不需要获取，时间到直接显示红包雨，其他台子也可以参考

              _self.newYearBeginTime= _self.baseSettingData.newYearBeginTime?_self.baseSettingData.newYearBeginTime:'2021-02-11 00:00:00' ;// 活动开始时间
              _self.newYearEndTime  = _self.baseSettingData.newYearEndTime?_self.baseSettingData.newYearEndTime:'2021-02-13 23:59:59' ;// 活动结束时间
              _self.curtime = _self.formatTimeUnlix((new Date()).getTime(),1);

              _self.$nextTick(()=>{
                // 红包雨 , 北京时间 ：2021/02/11 00:00:00 到 2021/02/13 23:59:59
                if(_self.curtime > _self.newYearBeginTime && _self.curtime < _self.newYearEndTime){
                  _self.getRedBag();
                }

              });
            }else{
              _self.getPromos();
            }
            _self.ProTab_Promo();

            if(_self.appRefer && _self.memberData){
                _self.checkReceive();
            }

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
            // 跳转到对应的优惠活动详情
            goToPromosDetail:function (){
                let key = this.prokey;
                if(key){
                    $('.promos_id_'+key).click();
                    document.getElementById('promos_id_'+key).scrollIntoView(); // 跳转到对应的活动
                }
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
                            _self.$nextTick(()=>{
                                _self.goToPromosDetail();
                                _self.getRedBag();
                                _self.autoGetPromos(); // 防止节点监听不了点击事件
                            })
                            resolve(res)
                        }
                    }).catch(res=>{
                        _self.submitflag = false;
                        console.log('获取优惠失败');
                        reject(res);
                    });
                });
            },
            // 自动领取
            autoGetPromos: function () {
                // 自动领取
                let _self = this;
                let $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
                let $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
                let $hb_close_1 = $('.hb_close_1');// 关闭按钮

                $('.ProTab_con').off().on('click','.pro_btn',function () {
                    let type = $(this).attr('data-type');

                    _self.postData.type_flag = type;
                    _self.postData.action = 'receive_red_envelope';

                    if(!_self.memberData.userid){
                        _self.$refs.autoDialog.setPublicPop("请先登录！");
                        return false ;
                    }
                    if(_self.memberData.test_flag =='1'){ // 0 正式，1 测试
                        _self.$refs.autoDialog.setPublicPop("请注册真实用户！");
                        return false ;
                    }

                    if(!_self.appRefer){
                        if(type =='appLuck' || type=='appDownload') { // app 幸运红包
                            _self.$refs.autoDialog.setPublicPop('请在APP优惠活动领取');
                            return false;
                        }
                    }

                    if(_self.submitflag){
                        return ;
                    }
                    let url = _self.baseUrl+$(this).data('api') ;
                    _self.submitflag = true ;

                    _self.axios({
                        method: 'post',
                        params: _self.postData,
                        url: url
                    }).then(res=>{
                        if(res){
                            _self.submitflag = false ;
                            let rest = res.data;
                            if(type =='appLuck' || type=='appDownload'){ // app 幸运红包
                                _self.$refs.autoDialog.setPublicPop(rest.describe);
                            }else if(type == '2020_6668' || type=='newyear_hb'){ // 新年活动
                                if(rest.status=='200'){ // 领取成功 不需要弹出提示
                                    $receiveAfter_act.show().addClass('reback');
                                    $hb_close_1.show();
                                  _self.hb_n_mount = rest.data.giftGold;
                                }else{
                                    $hb_mask_1.hide();
                                    $receiveAfter_act.hide();
                                    $hb_close_1.hide();
                                    _self.$refs.autoDialog.setPublicPop(rest.describe);
                                }
                            }else{
                                if(rest.info){
                                    _self.$refs.autoDialog.setPublicPop(rest.info);
                                }else{
                                    _self.$refs.autoDialog.setPublicPop(rest.describe);
                                }
                            }

                        }
                    }).catch(res=>{
                        _self.submitflag = false ;
                        console.log('活动申请请求失败');
                    });

                });
            },
            // 查询可领取次数
            checkReceive:function () {
                let _self = this;
                var apiUrl = _self.baseUrl+'/lucky_red_envelope_api.php';
                _self.postData.appRefer = _self.appRefer;
                _self.postData.AddDate = _self.memberData.AddDate;
                _self.postData.Alias = _self.memberData.Alias;
                _self.postData.action = 'get_valid';
                _self.axios({
                    method: 'post',
                    params: _self.postData,
                    url: apiUrl
                }).then(res=>{
                    if(res){
                        let rest = res.data;
                        $('.valid_money').text(rest.data[0].valid_money);
                        $('.last_times').text(rest.data[0].last_times);
                    }
                }).catch(res=>{
                    console.log('查询领取请求失败');
                });
            },
            // 点击红包
            getRedBag:function (){
                let _self = this;
                var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
                var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
                var $hb_close_1 = $('.hb_close_1');// 关闭按钮

                if(_self.show_bg=='bg'){   /* 2021 新年活动 独有 */
                  _self.autoGetPromos(); // 防止节点监听不了点击事件
                  $hb_mask_1.show();
                  // 红包雨 , 北京时间 ：2021/02/11 00:00:00 到 2021/02/13 23:59:59
                  hb_animate.hb_animate.hbInit(); // 红包雨
                }

                $hb_close_1.on('click',function () { // 关闭新年红包
                    $(this).hide();
                    $hb_mask_1.hide();
                    $receiveAfter_act.hide().removeClass('reback');
                });

                $('.ny_hb_btn').off().on('click',function () { // 召唤红包雨
                    if(!_self.memberData.userid){
                        _self.$refs.autoDialog.setPublicPop('请先登录');
                        return ;
                    }
                    if(_self.curtime < _self.newYearBeginTime || _self.curtime > _self.newYearEndTime){
                        _self.$refs.autoDialog.setPublicPop('请于北京时间1月24号-1月28号期间领取红包!');
                        return false;
                    }
                    $hb_mask_1.show();
                    // 红包雨 , 北京时间 ：2021/02/11 00:00:00 到 2021/02/13 23:59:59
                    hb_animate.hb_animate.hbInit(); // 红包雨
                });
                $('#hongbao_animation').on('click','.hongbao_li img',function () {  // 点击红包，抢红包
                    var type = $(this).attr('data-type');
                    if(type=='hb'){
                      //$('.btn_2020_6668').click();
                      $('.btn_newyear_hb').click();
                    }
                })
            }

        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .deposit .tab .item, .deposit .tab .expand{ margin-top: 0;border-top:none;}
    /*优惠活动*/
    .promo_nav {overflow-x: auto;background: #fff;}
    .promo{padding: 3% 3% 0 3%}
    .promotions_title_box {height:auto; }
    .promotions_title, .pro_btn {text-align: center;font-size: 15px; margin: 6px 0 8px 0; width: 100%; line-height: 26px; color: #757575; font-weight: 600;}
    .material-card-content {position:relative;max-height:0px;}
    .word_pro_sl b{color:#00b3e0;}
    .promotions_title_box img{width: 100%; height: auto; }
    .material-card-content img {width: 100%;}
    .material-card-content a:hover{background-color:transparent;}
    .material-card-content .pro_btn,.content .ny_hb_btn{position:absolute;display:block;width:32%;height:4rem;margin:85% 0 0 80%;background:transparent}
    .material-card-content .btn_attendance,.material-card-content .btn_chess{ margin: 73% 0 0 70%;}
    .material-card-content .btn_week{ margin: 89% 0 0 69%;}
    .material-card-content .btn_king{ margin: 126% 0 0 35%;}
    .material-card-content .btn_dragon{ margin: 150% 0 0 35%;}
    .material-card-content .btn_appLuck{ margin: 117% 0 0 34%;}
    .material-card-content .btn_sj_holiday{ margin: 120% 0 0 34%;}
    .material-card{padding: 0;}
    .ProTab_nav{width: 170%;height: 35px;}
    .ProTab_nav li{width: 12%;margin: 0;color: #000;border-radius: 0;border: 0;}
    .ProTab_nav li.on{background: none;color: #000;border-bottom: 2px solid #02a0e8;}

    .material-card-content .appLuckRed{position:absolute;width:100%;height:6rem;margin:31% 0 0}
    .material-card-content .btn_appDownload{margin:0 0 0 21%}
    .material-card-content .valid_num{position:absolute;width:100%;height:2rem;bottom:0}
    .material-card-content .valid_num span{box-sizing:content-box;display:inline-block;width:30%;line-height:2rem;padding-left:29%;color:#fff;font-size:1rem;overflow:hidden;text-overflow:ellipsis}
    .material-card-content .valid_num span:last-child{width:10%;padding-left:18.5%}

    /*优惠活动*/

  /* 新年活动 */

</style>
