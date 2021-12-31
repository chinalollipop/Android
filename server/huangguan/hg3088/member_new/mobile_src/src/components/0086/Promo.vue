<template>
    <div >
        <HeaderNav pa_showback="" pa_title="" :class="apptip=='app' && 'hide-cont'"/>
        <Dialog ref="autoDialog" pa_dialogtitle="" />
        <div class="content-center">
          <!-- 2021 新年活动 背影 -->
          <div class="hb_mask_1">
            <div id="hongbao_animation"> </div>
          </div>
            <main class="main promo">
                <!--  标签 -->
                <div class="promo_nav">
                    <ul class="ProTab_nav css_flex">
                        <li class="on"  data-type="all">全部</li>
                        <li v-for="(lists,item) in resData.categoryList" :data-type="lists.id">{{lists.name}}</li>
                    </ul>
                </div>

                <!-- 幸运转盘活动 -->
                <div class="mask"> </div>
                <div class="flex lucky_input">
                    <div>
                        <span>手机号</span><input type="text" class="phoneNumber" minlength="11" maxlength="11" placeholder="请输入手机号码获取验证码">
                    </div>
                    <div>
                        <span>验证码</span><input type="text" class="yzmNumber" minlength="2" maxlength="8" placeholder="请输入收到的验证码">
                    </div>
                    <div class="luck-bottom">
                        <a href="javascript:;" class="btn-yzm" data-type="sure"> 获取验证码 </a>
                        <a href="javascript:;" data-type="cancel"> 取消 </a>
                    </div>
                </div>

                <!-- 新年活动 领取红包 开始-->
                <div class="hb_mask">
                    <div class="blin"></div>
                    <div class="caidai"></div>
                    <div class="winning">
                        <div class="red-head"></div>
                        <div class="red-body">
                            <p class="hb_mount">{{hb_n_mount}}</p>
                            <p class="hb_title"> {{company_name}} </p>
                        </div>
                        <!--<div class="hb_card">
                            <a href="" target="_self" class="win"></a>
                        </div>
                        <a target="_self" class="btn"></a>-->
                    </div>
                    <span class="hb_close"></span>
                </div>
                <!-- 领取红包 结束-->

                <ul class="ProTab_con">
                    <li class="ProTab_con_1" style="display:block">
                        <div  v-for="(plists,item) in resData.promoList" class="material-card " :class="'promos_'+plists.type" >
                            <div class="promotions_title_box" :class="'promos_id_'+(plists.flag=='newyear_hb'?plists.flag:plists.id)" :id="'promos_id_'+(plists.flag=='newyear_hb'?plists.flag:plists.id)" @click="showDetails($event)">
                                <img :src="plists.imgurl">
                                <div class="promotions_title">{{plists.title}}</div>
                            </div>
                            <div class="material-card-content">
                                <div class="line"></div>
                                <!-- 2021 新年活动 -->
                              <div v-if="plists.flag=='newyear_hb'" >
                                <div class="newyear_2021">
                                  <div class="new_y_top">
                                    <div class="css_flex top_timer new_year_time_de">
<!--                                      <span class="timer_d">11</span>-->
<!--                                      <span class="timer_h">11</span>-->
<!--                                      <span class="timer_m">00</span>-->
<!--                                      <span class="timer_s">40</span>-->
                                    </div>
                                    <div class="new_y_bottom"> <span class="timer_d">你目前还剩下 00 天时间</span> </div>
                                  </div>
                                  <div class="newyear_hby_btn ny_hb_btn"> </div> <!-- 召唤红包雨按钮 -->
                                  <span class="n_hb_num newyear_num">{{hb_num}}</span> <!-- 红包数量 -->
                                  <!-- 领取红包成功 -->
                                  <div class="receiveAfter_1 receiveAfter_act">
                                    <div class="tip">
                                      <p>恭喜您获得</p>
                                      <p> <span class="hb_mount">{{hb_n_mount}}</span><span>元红包</span> </p>
                                    </div>
                                    <!-- 关闭红包 -->
                                    <span class="hb_close_1">领取</span>
                                  </div>
                                </div>
                                <a href="javascript:;" class="promos_btn pro_btn" :class="'btn_'+plists.flag" :data-api="plists.ajaxurl" :data-type="plists.flag"></a>
                              </div>

                                <div v-else-if="plists.flag=='2020_288w'" class="newYearFirst">
                                    <div class="newyear_btn">
                                        <a class="promos_btn pro_btn promos_newyear_1" :class="'btn_'+plists.flag" href="javascript:;" :data-type="plists.flag">
                                            <p class="hb_mount">{{hb_n_mount}}</p>
                                            <p class="hb_title"> {{company_name}}</p>
                                        </a>
                                        <a class="promos_btn pro_btn promos_newyear_2" :class="'btn_'+plists.flag" href="javascript:;" :data-type="plists.flag">
                                            <p class="hb_mount">{{hb_n_mount}}</p>
                                            <p class="hb_title"> {{company_name}}</p>
                                        </a>
                                        <a class="promos_btn pro_btn promos_newyear_3" :class="'btn_'+plists.flag" href="javascript:;" :data-type="plists.flag">
                                            <p class="hb_mount">{{hb_n_mount}}</p>
                                            <p class="hb_title"> {{company_name}}</p>
                                        </a>
                                    </div>
                                </div>
                                <div  v-else-if="plists.flag=='2020_888w' || plists.flag=='2020_yx'" class="machine" :class="'machine_'+plists.flag">
                                    <div class="receiveAfter receiveAfter_act">
                                        <div class="tip"> <span>￥</span><span class="hb_mount">{{hb_n_mount}}</span><span>元</span> </div>
                                    </div>
                                    <div class="new_zfy new_zfy_0"> </div>
                                    <div class="new_zfy new_zfy_1"> </div>
                                    <dl class="rotate_box">
                                        <dd ></dd>
                                        <dd ></dd>
                                        <dd ></dd>
                                    </dl>
                                    <!--<a class="poiner">
                                        <img src="/static/mages/hongbao/poiner.png" alt="">
                                    </a>-->
                                    <span class="zw_hh"><img src="/static/images/hongbao/rocker.png" alt="" style="visibility: hidden"></span> <!-- 占位 -->
                                    <a class="rotate_btn rotate_btn_css" >
                                        <img src="/static/images/hongbao/rocker.png" alt="" style="visibility: hidden">
                                    </a>
                                    <div class="newyear_num newyear_num_st">{{hb_num}}</div>
                                    <div class="btn_box">
                                        <a href="javascript:;" class="promos_btn pro_btn" :class="'btn_'+plists.flag" :data-api="plists.ajaxurl" :data-type="plists.flag"></a>
                                    </div>
                                </div>
                                <div v-else-if="plists.flag=='best_lucky'">
                                    <div class="box-lucky">
                                        <div class="lucky-wrap" >

                                        </div>
                                        <a class="lucky-btn" data-luckyTip="0"><i></i>立即抽奖</a>
                                    </div>
                                    <div class="luckyM ">恭喜抽中赠送彩金<span class="lucky_hb_mount">{{hb_n_mount}}</span>元</div>
                                </div>

                                <a v-else href="javascript:;" class="promos_btn pro_btn" :class="'btn_'+plists.flag" :data-api="plists.ajaxurl" :data-type="plists.flag"></a>
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
                hb_n_mount:0 // 红包金额
            }
        },
        mounted: function () {
            let _self = this ;
            _self.apptip = _self.$route.query.tip?_self.$route.query.tip:''; // 获取参数
            _self.prokey = _self.$route.query.prokey?_self.$route.query.prokey:''; // 获取参数
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

            if(_self.baseSettingData.redPocketOpen){ // 需要判断是否开启活动
              _self.newYearBeginTime= _self.baseSettingData.newYearBeginTime?_self.baseSettingData.newYearBeginTime:'2021-02-11 00:00:00' ;// 活动开始时间
              _self.newYearEndTime  = _self.baseSettingData.newYearEndTime?_self.baseSettingData.newYearEndTime:'2021-02-12 23:59:59' ;// 活动结束时间
              _self.curtime = _self.formatTimeUnlix((new Date()).getTime(),1);

              _self.setTimerAc('.new_year_time_de'); // 需要判断是否开启活动
              _self.getNewYearTime();
              }

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
                let $hb_mask = $('.hb_mask'); // 新年活动一
                let $winning = $('.winning'); // 新年活动一
                let $rotate_btn = $('.rotate_btn');// 摇杆按钮
                let $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
                let $hb_mask_1 = $('.hb_mask_1'); // 新年活动
                let $hb_close_1 = $('.hb_close_1');// 关闭按钮

                $('.ProTab_con').off().on('click','.hb_close',function () { // 关闭新年红包
                    $hb_mask.hide();
                    $winning.removeClass('reback');
                });

                $('.ProTab_con').off().on('click','.pro_btn',function () {
                    let type = $(this).attr('data-type');
                    let indexNum = Number($(this).index())+1;

                    let postData = {
                        type_flag: type ,
                        user_id: _self.memberData.userid ,
                        username: _self.memberData.UserName ,
                       // platfrom: platfrom ,
                        action:'receive_red_envelope'
                    }

                    if(!_self.memberData.userid){
                        _self.$refs.autoDialog.setPublicPop("请先登录！");
                        return false ;
                    }
                    if(_self.memberData.test_flag =='1'){ // 0 正式，1 测试
                        _self.$refs.autoDialog.setPublicPop("请注册真实用户！");
                        return false ;
                    }

                    if(type =='2020_888w' || type =='2020_yx'){ // 新年活动二摇奖
                        let g_num = _self.hb_num;
                        if(_self.curtime < _self.newYearBeginTime || _self.curtime > _self.newYearEndTime){
                            if(type =='2020_888w'){
                                _self.$refs.autoDialog.setPublicPop('请于北京时间1月25号中午12:00-1月30日11:59期间领取红包!');
                            }else{
                                _self.$refs.autoDialog.setPublicPop('请于美东时间02月11日期间领取红包!');
                            }

                            return false;
                        }
                        if(g_num < 1){
                            _self.$refs.autoDialog.setPublicPop('可领次数不足');
                            return false;
                        }
                        if($rotate_btn.hasClass('act')){ // 正在摇奖中
                            return false;
                        }
                        $('.new_zfy').text('');// 清空祝福语
                        $receiveAfter_act.hide().removeClass('reback'); // 初始化金额弹窗

                        setTimeout(function () {
                            _self.getNewYearTime('receive');
                        },5000)
                        $rotate_btn.click();
                        return false ; // 不需要执行下面了
                    }

                    if(_self.submitflag){
                        return ;
                    }
                    let url = _self.baseUrl+$(this).data('api') ;
                    _self.submitflag = true ;


                    _self.axios({
                        method: 'post',
                        params: postData,
                        url: url
                    }).then(res=>{
                        if(res){
                            _self.submitflag = false ;
                            let rest = res.data;
                            if(type == '2020_288w'){
                                if(rest.status=='200'){ // 领取成功
                                    $hb_mask.show();
                                    $winning.addClass('reback');
                                    $('.promos_newyear_'+indexNum).addClass('active');
                                    $('.promos_newyear_'+indexNum).find('.hb_title').show();
                                    $('.promos_newyear_'+indexNum).find('.hb_mount').show();
                                    _self.hb_n_mount = rest.data.giftGold;
                                }else{
                                    _self.$refs.autoDialog.setPublicPop(rest.describe);
                                }
                            }else if(type == 'newyear_hb'){ // 2021 新年活动
                              if(rest.status=='200'){ // 领取成功 不需要弹出提示
                                $receiveAfter_act.show().addClass('reback');
                                $hb_close_1.show();
                                _self.hb_n_mount = rest.data.giftGold;
                                _self.hb_num = 0 ; // 红包只能领取一次
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
            // 获取新年活动二红包次数
            getNewYearTime: function(type){
                let _self = this;
                var $rotate_btn = $('.rotate_btn');// 摇杆按钮
                var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
                if(!_self.memberData.userid){
                  return false;
                }
                var postData = {
                    user_id: _self.memberData.userid ,
                    username: _self.memberData.UserName ,
                    action:'getGrabTimes', // 查询可领取次数
                    //platfrom:''
                }
                if(type){ // 领取
                    postData.action = 'receive_red_envelope'; // 领取
                }

                _self.axios({
                    method: 'post',
                    params: postData,
                    url: _self.ajaxUrl.newyear_2
                }).then(res=>{
                    if(res){
                        let rest = res.data;
                        if(rest.status =='200'){ // 成功
                            _self.hb_num = rest.data.lastTimes;
                            if(type =='receive'){ // 领取成功
                              _self.hb_n_mount = rest.data.giftGold;
                                $receiveAfter_act.show().addClass('reback');
                            }
                            if(rest.data.lastTimes < 1){ // 次数不足,不能摇奖
                                $rotate_btn.addClass('act');
                            }
                        }else{
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }

                    }
                }).catch(res=>{
                    console.log('活动查询请求失败');
                });


            },
            // 获取幸运大转盘活动配置
            getBestLucky: function (params){
                var luckUrl = ajaxurl;
                if(!params.mem_yzm && params.action=='draw'){ // 获取验证码
                    luckUrl ='/api/message_xsend.php';
                }
                params.user_id = _self.memberData.userid;
                $.ajax({
                    type : 'POST',
                    url : luckUrl,
                    data : params,
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            var str ='';
                            let rest = res.data;
                            if(params.action=='check'){ // 检验资格
                                if(rest.status=='200'){ // 符合抽奖条件
                                    $luckyBtn.attr('data-luckyTip','1');
                                }else{
                                    $luckyBtn.attr('data-luckyTip','0');
                                }
                                for(var i=0;i<rest.data.length;i++){
                                    str +=' <span class="lucky-span'+(i+1)+'" data-id="'+ rest.data[i].id +'">' +
                                        '<i>'+ rest.data[i].best_lucky_content +'</i>' +
                                        '<img src="/images/hongbao/lucky/prize_'+ (i+1) +'.png" alt="'+  rest.data[i].best_lucky_content +'">' +
                                        '</span>';
                                }
                                $luckyBox.html(str);
                            }
                            if(params.action=='draw') { // 验证码获取，抽奖
                                if(params.mem_yzm){ // 最后抽奖步骤
                                    if(rest.status=='200') { // 符合抽奖条件,返回抽奖数据
                                        _self.hb_n_mount = rest.data.gift_gold;
                                        $luckyBox.find('span').each(function (i,v) {
                                            var txt = $(this).find('i').text();
                                            //console.log(txt)
                                            if(txt==rest.data.best_lucky_content){
                                                //console.log(i+'==')
                                                luckyAnime(i); // 抽奖动画
                                            }
                                        });

                                    }else {
                                      _self.$refs.autoDialog.setPublicPop(rest.describe);
                                    }
                                }else{
                                    if(rest.status=='200'){ // 发送验证码成功
                                        $luckbottom.find('.btn-yzm').text('确定');
                                    }
                                  _self.$refs.autoDialog.setPublicPop(rest.describe);
                                }

                            }

                        }

                    },
                    error:function(){

                    }
                });
            },
            // 幸运大转盘红包领取
            getLuckyGift: function (){
                let _self =this;
                $luckyBtn.on('click', function(){
                    var lucktip = $(this).attr('data-luckyTip');
                    if(!_self.memberData.userid){
                      _self.$refs.autoDialog.setPublicPop('请先登录');
                        return false;
                    }
                    if(lucktip==0){
                      _self.$refs.autoDialog.setPublicPop('当前不符合抽奖条件');
                        return false;
                    }else{
                        $mask.show();
                        $luckyinput.show();

                        $luckyinput.off().on('click','a',function () {
                            var type = $(this).attr('data-type');
                            var phone = $('.phoneNumber').val();
                            var yzm = $('.yzmNumber').val();
                            var btnTxt = $luckbottom.find('.btn-yzm').text();
                            var luckData ={
                                action:'draw',
                                mem_phone:phone,
                                mem_yzm:yzm
                            };
                            // console.log(type)
                            if(type=='sure'){ // 确定
                                if(!phone){
                                  _self.$refs.autoDialog.setPublicPop('请输入手机号')
                                    return false;
                                }
                                if(btnTxt=='确定' && !yzm){
                                  _self.$refs.autoDialog.setPublicPop('请输入验证码');
                                    return false;
                                }
                                if(yzm){ // 输入验证码后请求数据
                                    $mask.hide();
                                    $luckyinput.hide();
                                }
                                getBestLucky(luckData);
                            }else{ // 取消
                                $mask.hide();
                                $luckyinput.hide();
                            }

                        })


                    }

                });
            },
            // 幸运抽奖动画
            luckyAnime: function (num){
                // var num = Math.floor(Math.random() * 7); // 七个随机
                let _self =this;
                Lucky.start(num, function(index){
                    var luckyText = $luckyBox.find('span').eq(index).find('i').text();
                    _self.$refs.autoDialog.setPublicPop(luckyText);
                    $luckyM.show();
                    // console.log('index', index, 'lucky-span', 'lucky-span'+(index+1));
                });
            },
          // 2021 新年活动 点击红包
          getRedBag:function (){
              let _self =this;
              var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
              var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
              var $hb_close_1 = $('.hb_close_1');// 关闭按钮

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
                  _self.$refs.autoDialog.setPublicPop('请于北京时间02月11号-02月12号期间领取红包!');
                  return false;
                }
                if(_self.hb_num==0){
                  _self.$refs.autoDialog.setPublicPop('没有可领取次数');
                  return false;
                }
                $hb_mask_1.show();
                hb_animate.hb_animate.hbInit(); // 红包雨
              });
              $('#hongbao_animation').on('click','.hongbao_li img',function () {  // 点击红包，抢红包
                var type = $(this).attr('data-type');
                if(type=='hb'){
                  $('.btn_newyear_hb').click();
                }
              })
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
    .ProTab_nav li {width: 12%;margin: 5px 0 0;}
    /*优惠活动*/
    .ProTab_con{padding: 1% 2% 0 2%}
    .promotions_title_box {height:auto; }
    .promotions_title, .pro_btn {text-align: center;font-size: 15px; margin: 6px 0 8px 0; width: 100%; line-height: 26px; color: #757575; font-weight: 600;}
    .material-card-content {position:relative;max-height:0px;}
    .word_pro_sl b{color:#00b3e0;}
    .promotions_title_box img{width: 100%; height: auto; }
    .material-card-content img {width: 100%;}
    .material-card-content a:hover{background-color:transparent;}
    .material-card-content .pro_btn{position:absolute;display:block;width:34%;height:4rem;margin:80% 0 0 67%;background:transparent}
    .material-card-content .btn_attendance{ margin: 113% 0 0 35%;}
    .material-card-content .btn_chess{ margin: 75% 0 0 70%;}
    .material-card-content .btn_king{ margin: 125% 0 0 33%;}
    .material-card-content .btn_shuangdan{ margin: 62% 0 0 57%;}
    .material-card-content .btn_promotion{ margin: 76% 0 0 34%;}
    .material-card-content .btn_sport_dm{ margin: 115% 0 0 33%;}
    .material-card-content .btn_sj_holiday{ margin: 256% 0 0 68%;}
    .material-card-content .btn_euro{ margin: 92% 0 0 45%;}

    /* 2021 新年活动 */
    .material-card-content .new_y_top{ position: absolute;width: 14rem;left: 50%;height: 4rem;margin: 9% 0 0 -28%;}
    .material-card-content .new_y_top .top_timer {height: 1.5rem;line-height: 1.5rem;margin: 6% 0 0 3%;font-size: .8rem;color: #ba000b;}
    .material-card-content .new_y_top .top_timer>>> span{margin:0 0;background: #ba000b;color: #e8c3c3;border-radius: 5px;display: inline-block;width: 1.5rem;max-width:50px;text-align: center;}
    .material-card-content .new_y_top .new_y_bottom{font-size: 1rem;color: #ba000b;height: 2rem;line-height: 1.5rem;text-align: center;}

    .material-card-content .newyear_2021 .newyear_hby_btn{/*display:none;*/cursor:pointer;width: 8rem;height: 3rem;background: url(/static/images/hongbao/hb_btn.png) center no-repeat;background-size: 100%;position: absolute;margin: 96% 0 0 38%;transform: scale(.7);transition: .3s;}
    .material-card-content .newyear_2021 .newyear_hby_btn:hover{transform: scale(.75);}
    .material-card-content .newyear_2021 .n_hb_num{position: absolute;margin: 109.5% 0 0 29.5%;font-size: .9rem;color: #fff;}

    /* 新年 活动一 中奖提示  开始 */
    .material-card-content .newyear_btn{position:absolute;width:100%;height:10rem;margin:49% 0 0 0}
    .material-card-content .btn_2020_288w{width:31%;height:100%;background:url(/static/images/hongbao/hb_off.png) center no-repeat;background-size:100%;margin:0;left:35%}
    .material-card-content .btn_2020_288w.active{background:url(/static/images/hongbao/hb_on.png) center no-repeat;background-size:100%}
    .material-card-content .btn_2020_288w.promos_newyear_1{left:3%}
    .material-card-content .btn_2020_288w:last-child{left:67%}
    .hb_mask{display:none;position:fixed;left:0;top:0;z-index:10;width:100%;height:100%;background-color:rgba(0,0,0,0.85)}
    .hb_mask .blin{width:100%;max-width:747px;height:100%;max-height:752px;margin:0 auto 0;background-image:url(/static/images/hongbao/gold.png);background-size:100%;background-repeat:no-repeat;background-position:center;-o-animation:circle 10s linear infinite;-ms-animation:circle 10s linear infinite;-moz-animation:circle 10s linear infinite;-webkit-animation:circle 10s linear infinite;animation:circle 10s linear infinite}
    .hb_mask .caidai{position:absolute;left:0;top:0;z-index:1;width:100%;height:100%;background-image:url(/static/images/hongbao/dianzhui.png);-o-transform:scale(1.2);-ms-transform:scale(1.2);-moz-transform:scale(1.2);-webkit-transform:scale(1.2);transform:scale(1.2);background-size:100%}
    .hb_mask .winning{position:absolute;left:50%;top:50%;z-index:1;width:198px;height:265px;margin:-35% -24%;-webkit-transform:scale(0.1);transform:scale(0.1)}
    .reback{-o-animation:reback .5s linear forwards;-ms-animation:reback .5s linear forwards;-moz-animation:reback .5s linear forwards;-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}
    /* .winning .red-head{position:relative;top:-0.33333333rem;width:100%;height:4.46666667rem;background-image:url("/images/hongbao/top.png")} */
    .winning .red-body{position:relative;z-index:2;width:100%;height:100%;background-image:url(/static/images/hongbao/hb_on.png);background-repeat:no-repeat}
    .hb_mount{color:#d6261e !important;font-size:40px !important;font-weight:bold;padding:42px 12px !important;width:84%;text-align:center !important;}
    .promos_btn .hb_mount{display: none;padding: 2rem 0 !important;font-size: 2.5rem !important;}
    .hb_title{font-size:20px !important;text-align:center !important;color:#fcd639 !important;margin-top:20px}
    .promos_btn .hb_title{display:none;margin-top:10px;font-size: 1rem !important;}
    .winning .pull{-o-animation:card .5s linear forwards;-webkit-animation:card .5s linear forwards;animation:card .5s linear forwards}
    .hb_close{cursor:pointer;opacity:1;position:absolute;right:6%;top:20%;z-index:10;width:40px;height:40px;background-image:url(/static/images/hongbao/close.png);background-size:100%}
    /* .winning .hb_card{position:absolute;left:50%;top:50%;z-index:1;margin-left:-3.2rem;margin-top:-1.06666667rem;width:80%;height:4.26666667rem;background-image:url("/images/hongbao/middle.png");-o-transition:top .5s;-ms-transition:top .5s;-moz-transition:top .5s;-webkit-transition:top .5s;transition:top .5s}
     .hb_card .win{display:block;margin:0.13333333rem auto;width:92%;height:3.86666667rem;background-image:url("/images/hongbao/prize2.png")}
     .winning .btn{position:absolute;left:50%;bottom:10%;z-index:2;width:4.85333333rem;height:0.94666667rem;margin-left:-2.42666667rem;background-image:url("/images/hongbao/button.png");-o-animation:shake .5s 2 linear alternate;-ms-animation:shake .5s 2 linear alternate;-moz-animation:shake .5s 2 linear alternate;-webkit-animation:shake .5s 2 linear alternate;animation:shake .5s 2 linear alternate}
     */

    @keyframes reback {
        100% {
            -o-transform: scale(1);
            -ms-transform: scale(1);
            -moz-transform: scale(1);
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }
    @keyframes circle {
        0% {
            -o-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -o-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes card {
        100% {
            margin-top: -3.2rem;
        }
    }
    @keyframes shake {
        50% {
            -o-transform: rotate(-5deg);
            -ms-transform: rotate(-5deg);
            -moz-transform: rotate(-5deg);
            -webkit-transform: rotate(-5deg);
            transform: rotate(-5deg);
        }
        100% {
            -o-transform: rotate(5deg);
            -ms-transform: rotate(5deg);
            -moz-transform: rotate(5deg);
            -webkit-transform: rotate(5deg);
            transform: rotate(5deg);
        }
    }

    @keyframes fadein {
        100% {
            opacity: 1;
            -o-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    /* 新年 活动一 中奖提示  结束 */

    /* 新年 活动二 */
    .machine{width:100%;height:24rem;position:absolute;top:50%;left:50%;-webkit-transform:translate(-50%,-72%);transform:translate(-50%,-72%)}
    .rotate_box{width:60%;margin:0 auto;padding-top:4rem}
    .rotate_box dd{position:relative;width:24.8%;height:7rem;margin-right:9%;float:left;background:url(/static/images/hongbao/prize.png);background-size:cover}
    .rotate_box dd:last-child{margin-right:0}
    .rotate_btn_css,.zw_hh{width:3rem;overflow:hidden;position:absolute;right:.2%;bottom:17rem;transform-origin:center bottom}
    .zw_hh{z-index:1}
    .poiner{width:.88rem;position:absolute;right:.4rem;top:-1.2rem}
    .btn_box{width:10rem;height:4rem;position:absolute;left:50%;margin-left:-5rem;bottom:1rem}
    .material-card-content .btn_box a{transition:.3s;width:100%;height:100%;margin:0;background:url(/static/images/hongbao/yj_btn.png) center no-repeat;background-size:100%}
    .material-card-content .btn_box a:hover,.material-card-content .btn_box a:active{background:url(/static/images/hongbao/yj_btn_hover.png) center no-repeat;background-size:100%}
    .newyear_num_st{position:absolute;width:4rem;bottom:29%;color:#f6bd2f;font-size:3.5rem;font-weight:bold;text-align:center;right:22%}
    .machine .receiveAfter{z-index:2;display:none;transform:scale(0.1);position:absolute;width:10rem;height:14rem;background:url(/static/images/hongbao/hb1_on.png) center no-repeat;background-size:100%;top:2%;left:27.5%;color:#d6261e;font-size:1.5rem}
    .machine .receiveAfter .tip{text-align:center;padding-top:2.8rem}
    .machine .receiveAfter .tip .hb_mount{padding:0 !important;font-size:36px !important}
    .machine .new_zfy{width:25%;position:absolute;font-size:1rem;color:#fdc731;left:15%;top:5.6rem;text-align:center;z-index:1;transform:scale(.8)}
    .machine .new_zfy.new_zfy_1{left:35%}

    /* 元宵 */
    .machine_2020_yx{-webkit-transform:translate(-50%,-62%);transform:translate(-50%,-62%);height:22rem}
    .machine_2020_yx .rotate_box dd{margin-right:8%;background:url(/static/images/hongbao/prize_1.png)}
    .machine_2020_yx .newyear_num_st{bottom:27%;right:30%;font-size:2.8rem}
    .machine_2020_yx .new_zfy{top: 8.1rem;}
    /* 幸运转盘 */
    .lucky_input{display:none;padding:1rem 0;position:absolute;z-index:295;background:#fff;border-radius:5px;width:80%;left:50%;margin:115% -40% 0}
    .lucky_input>div{color:#000;line-height:40px}
    .lucky_input>div input{border:1px solid #ccc;line-height:28px;padding:0 5px}
    .luck-bottom a{display:inline-block;padding: 0 10px;height:30px;line-height:30px;color:#333;border:1px solid #dedede;margin:1rem 1rem 0}
    .luck-bottom a:first-child{border-color:#1E9FFF;background-color:#1E9FFF;color:#fff}
    .box-lucky{position:absolute;left:50%;margin:47% -9.5rem 0;width:19rem;height:19rem;background:url(/static/images/hongbao/lucky/prize_bg.png) center no-repeat;background-size:100%}
    .lucky-wrap{position:relative;width:100%;height:100%;background:url(/static/images/hongbao/lucky/prize_bg_sec.png) center no-repeat;background-size:90%;transform:rotate(-25deg);-webkit-transform:rotate(-25deg)}
    .lucky-wrap img{display:inline-block;width:2.5rem;height:2.5rem}
    .lucky-wrap span{display:inline-block;position:absolute;top:0;left:30%;width:8rem;height:50%;color:#fff;-webkit-transform-origin:50% 100%;transform-origin:50% 100%;text-align:center}
    .lucky-wrap span.lucky-span1{-webkit-transform:rotate(22.5deg);transform:rotate(22.5deg)}
    .lucky-wrap span.lucky-span2{-webkit-transform:rotate(75.5deg);transform:rotate(75.5deg)}
    .lucky-wrap span.lucky-span3{-webkit-transform:rotate(128.5deg);transform:rotate(128.5deg)}
    .lucky-wrap span.lucky-span4{-webkit-transform:rotate(180.5deg);transform:rotate(180.5deg)}
    .lucky-wrap span.lucky-span5{-webkit-transform:rotate(232.5deg);transform:rotate(232.5deg)}
    .lucky-wrap span.lucky-span6{-webkit-transform:rotate(283.5deg);transform:rotate(283.5deg)}
    .lucky-wrap span.lucky-span7{-webkit-transform:rotate(331.5deg);transform:rotate(331.5deg)}
    .lucky-wrap i{display:block;width:100%;height:2rem;font-style:normal;font-size:.8rem;line-height:2rem;margin:1.2rem 0 0;transform:scale(.9);-webkit-transform:scale(.9)}
    .lucky-wrap img{max-width:100%}
    .lucky-btn{position:absolute;left:50%;top:50%;text-indent:-999em;z-index:11;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:7rem;height:7rem;background:url(/static/images/hongbao/lucky/prize_btn.png) center no-repeat;background-size:80%}
    .luckyM{display:none;position:absolute;width:70%;height:2.8rem;line-height:2.8rem;left:50%;background:#f79d07;margin:135% -35% 0;border-radius:50px;text-align:center;color:#fff;font-size:1.3rem;}

    /*优惠活动*/
</style>
