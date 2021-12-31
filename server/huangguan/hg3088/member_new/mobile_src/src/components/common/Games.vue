<template>
  <div >

    <HeaderNav pa_showback="" pa_title="" :pa_money="userMoney"/>
    <Dialog ref="autoDialog" pa_dialogtitle="" />

    <div class="content-center ele-game dialog-content">
        <div class="liveNav">
            <span class="live-money">电子额度：￥
                <span id="video_blance" class="live_money">
                    {{returnGameData(gametype).game_Money}}
                </span>
            </span>
            <a class="live-change limit-toggle" @click="showTranWin"><span class="change-icon"></span>额度转换</a>
        </div>
        <!-- 标签切换 -->
        <div class="nav-over">
            <div class="tab-nav">
                <div v-for="(list,index) in gameList" :key="index" class="item" :class="gametype==list.type && 'active'">
                    <a href="javascript:;" >{{list.name}}</a>
                </div>
            </div>
        </div>
        <!-- 内容区域 -->
        <div class="type-wrap game-list ">
            <li v-for="(list,index) in returnGameData(gametype).game_List" :key="index" :class="gametype+'-list'">
                <a href="javascript:;" @click="openNewGame('gameswin?action=getLaunchGameUrl&game_id='+list.gameid+'&gametype='+gametype,gametype)">
                    <img :src="'/static'+list.gameurl" :alt="list.name">
                <p>{{list.name}}</p>
                </a>
            </li>
        </div>

        <!--额度转换 窗口-->
        <div class="quota-manage show" ref="edzh_dialog">
            <div class="quota-info clearfix">
                <div>
                    <span class="label">账户余额：</span>
                    <span class="quota hg_money">{{userMoney}}元</span>
                </div>
                <div>
                    <span class="label">{{gamename}}余额：</span>
                    <span class="quota third_game_money">{{returnGameData(gametype).game_Money}}元</span>
                </div>
            </div>
            <div class="textbox-wrap">
                <input type="number" class="limit_gold" placeholder="输入金额" v-model="tran_amount">
            </div>
            <div class="btn-wrap clearfix">
                <a href="javascript:;" class="zx_submit" @click="changeLimitAction('out')">{{gamename}}转出</a>
                <a href="javascript:;" class="zx_submit" @click="changeLimitAction('in')">{{gamename}}转入</a>
            </div>
        </div>

    </div>

    <FooterNav ref="footernav"/>
  </div>
</template>

<script>

import axios from 'axios'
import Mixin from '@/Mixin'
import HeaderNav from '@/components/Header'
import FooterNav from '@/components/Footer'
import Dialog from '@/components/Dialog'

export default {
    name: 'games',
    mixins: [Mixin],
    components: {
        HeaderNav,
        FooterNav,
        Dialog
    },
    data () {
        return {
            tran_amount:'', // 转账金额
            gametype:'aggame',
            gamename:'AG电子',
            gameList:[
                { name:'FG电子', type:'fg'},
                { name:'AG电子', type:'aggame'},
                { name:'CQ9电子', type:'cq'},
                { name:'MG电子', type:'mg'},
                { name:'MW电子', type:'mw'}
            ]

        }
    },
    mounted: function () {
        let _self = this;
        _self.gametype = this.$route.query.gametype; // 获取参数
        _self.gamename = this.$route.query.gamename; // 获取参数

        _self.setTranWin();

        // 获取各平台余额
        let pars = {action:'b'}; // 获取余额参数
        let pars_g = {action:'gamelist_dianzi'}; // 获取游戏列表参数
        _self.tranMoneyAction( _self.gametype,'',pars);
        _self.tranMoneyAction( _self.gametype,'',pars_g);  // 获取游戏列表

    },
    methods: {
        /* 转账窗口设置 ,收起转账窗口 */
        setTranWin:function () {
            let _self = this;
            let win_height= _self.$refs.edzh_dialog.offsetHeight;
            _self.$refs.edzh_dialog.style.bottom = '-'+win_height+'px';
        },
        /* 显示转账窗口 */
        showTranWin:function () {
            let _self = this;
            let f_height= _self.$refs.footernav.$el.offsetHeight;
            let win_height= _self.$refs.edzh_dialog.offsetHeight;
            let win_bottom = Number(_self.$refs.edzh_dialog.style.bottom.replace('px',''));
            let body_size = (getComputedStyle(window.document.documentElement)['font-size']).slice(0,2); // 获取根节点设置
            if(f_height==0){
                f_height= 4.2*body_size;
            }
            // console.log(win_bottom);
            // console.log(_self.$refs.footernav)
           // console.log(f_height)
            if(win_bottom>0){ // 收起
                _self.setTranWin();
            }else{ // 显示
                _self.tran_amount = ''; // 重置
                _self.$refs.edzh_dialog.style.bottom = f_height+'px';
            }

        },
        /* 返回数据 */
        returnGameData:function (type) {
            let _self = this;
            let game_cur_data =[];
            switch (type){
                case 'aggame':
                    game_cur_data ={
                        game_Money:_self.ag_Money,
                        game_List:_self.ag_game_list
                    };
                    break;
                case 'fg':
                    game_cur_data ={
                        game_Money:_self.fg_Money,
                        game_List:_self.fg_game_list
                    };
                    break;
                case 'cq':
                    game_cur_data ={
                        game_Money:_self.cq_Money,
                        game_List:_self.cq_game_list
                    };
                    break;
                case 'mg':
                    game_cur_data ={
                        game_Money:_self.mg_Money,
                        game_List:_self.mg_game_list
                    };
                    break;
                case 'mw':
                    game_cur_data ={
                        game_Money:_self.mw_Money,
                        game_List:_self.mw_game_list
                    };
                    break;
            }

            return game_cur_data;
        },
        /* 转账操作 */
        changeLimitAction:function (type) {
            let _self =this;
            let game_type = (_self.gametype=='aggame')?'ag':_self.gametype;
            _self.f_blance = 'hg'; // 初始转出方
            _self.t_blance = game_type; // 初始转入方
            if(type=='out'){
                _self.f_blance = game_type;
                _self.t_blance = 'hg';
            }
            if(_self.submitflag){
                _self.$refs.autoDialog.setPublicPop('请勿重复提交');
                return false;
            }

            _self.tran_amount = Number(_self.tran_amount);
            if(_self.tran_amount<1 || isNaN(_self.tran_amount)){
                _self.$refs.autoDialog.setPublicPop('请填写正确转账金额');
                return false;
            }
            setTimeout(()=>{
                _self.submitflag = false;
            },2000);
            _self.submitflag = true ;
            let dat={
                id:_self.memberData.userid,
                uid: _self.memberData.Oid,
                userName: _self.memberData.UserName,
                action:'fundLimitTrans',
                f: _self.f_blance,
                t: _self.t_blance,
                b: _self.tran_amount
            };
            _self.setTranWin();
            _self.tranMoneyAction(_self.f_blance,_self.t_blance,dat);

        }

    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .liveNav{height:2.85rem;line-height:2.85rem;border-bottom:1px solid #e6e6e6;font-size:1.1rem;padding:0 .8rem;color: #888;}
    .liveNav .live-money{float:left}
    .liveNav .live-change{float:right;color:#e64545}
    .liveNav .change-icon{display:inline-block;width:1.8rem;height:1.5rem;background:url(/static/images/quota.png) no-repeat;background-size:contain;vertical-align:middle}

    .tab-nav .item{display: none;}
    .tab-nav .item.active{display: inline-block;}
    .tab-nav .item a, .tab-nav .item span{padding: .4rem 1rem;}
    .nav-over {overflow-x: auto;}
    /* 额度转换弹出窗 */
    .dialog-content .quota-manage {transition: .5s;z-index: 22;position: fixed;left: 0;bottom: -20rem;width: 100%;border-top: 1px solid #e6e6e6;background: #fff;}
    .dialog-content .quota-manage .quota-info div {float: left;width: 50%;padding: 15px 0;}
    .dialog-content .quota-manage .quota-info div .label {padding-left: 10px;color: #8d8d8d;}
    .dialog-content .quota-manage .quota-info div .quota {color: #dc2424;}
    .dialog-content .quota-manage .textbox-wrap {padding: 0 20px;margin-bottom: 15px;}
    .dialog-content .quota-manage .textbox-wrap input {width: 90%;display: inline-block;padding: 0 10px;height: 40px;border: 1px solid #e6e6e6;background: #f9f9f9;border-radius: 10px;}
    .dialog-content .quota-manage .btn-wrap {padding: 0 20px;width: auto;margin-bottom: 4.5rem;}
    .dialog-content .quota-manage .btn-wrap .btn {width: 45%;float: left;border-radius: 10px;}
    .dialog-content .quota-manage .btn-wrap .btn-active {float: right;background: #dc2424;}
    .quota-manage .btn-wrap a{ width: 45%;border-radius: 10px; }
    .quota-manage .btn-wrap a:first-child{ float: left; }
    .quota-manage .btn-wrap a:last-child{ float: right; }
</style>
