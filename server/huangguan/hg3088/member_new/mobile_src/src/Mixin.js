
//mixin.js

let MyMixin = {
    data:function(){
        return {
            baseUrl:'', // 线上打包发布
            CancelToken:null,
            axiosSource:null,
            submitflag:false, // 防止重复提交
            checkStatus:true, // 选中状态
            showmore_dis:false, // 3366
            FuWuTimeData:[],
            baseSettingData:[], // 基本设置
            tpl_name:'',
            company_name:'',
            gametypeList:['cp','gmcp','ky','ly','vg','kl','ag','agby','og','bbin','avia','fire'], // 属于打开游戏
            curtypeList:['mobile','today','rb','future','ky','ly','vg','kl'], // 不需要新窗口打开
            tplnameList:['0086/','0086dj/','6668/','bet365/','nbet365/'], // 体育彩票模板
            tplnameSecList:['0086/','086dj/'], // 使用图标库模板
            tplnameThd:'0086dj/', // 电竞打开iframe 方式
            tplnameFth:'8msport/',
            sportRoute:['/sport','/sportlist', '/newcate','/morecate','/sportroul'], // 体育样式路由
            thirdDeposit:['/account/deposit_two_third_zfb.php','/account/deposit_two_third_wx.php', '/account/deposit_two_third_qq.php','/account/deposit_two_third_bank.php','/account/deposit_two_third_kscz.php','/account/deposit_two_third_bank_youhui.php'],
            ag_game_list:[], // AG 电子列表
            mg_game_list:[], // MG 电子列表
            mw_game_list:[], // MW 电子列表
            fg_game_list:[], // FG 电子列表
            cq_game_list:[], // CQ9 电子列表
            userName:'',// 判断是否登录
            userMoney:0,// 用户余额
            cp_Money:0,// 彩票余额
            joinDays:0,// 注册天数
            gmcp_Money:0,// 彩票余额
            ag_Money:0,// AG余额
            bbin_Money:0,// BBIN余额
            og_Money:0,// OG余额
            ky_Money:0,// 开元余额
            ly_Money:0,// 乐游余额
            vg_Money:0,// vg余额
            kl_Money:0,// 快乐余额
            mw_Money:0,// MW电子余额
            fg_Money:0,// FG电子余额
            mg_Money:0,// MG电子余额
            cq_Money:0,// CQ9电子余额
            avia_Money:0,// 泛亚电竞余额
            fire_Money:0,// 雷火电竞余额
            memberData:[],// 会员登录数据
            v_amount:100, // 充值，提现，转账 默认金额
            usdt_rate:7, // usdt 汇率
            usdt_mon:0, // usdt 金额
            f_blance:'', // 转出平台
            t_blance:'', // 转入平台
            alerttitle: { // 配置提示信息
                int:'汇款金额必须为数字(最多两位小数)！' ,
                mon:'汇款金额不能小于100元！' ,
                bank:'请输入转入银行！' ,
                banktype:'请选择汇款方式！' ,
                time:'请选择汇款时间！' ,
                realname:'请填写真实姓名！',
                remark:'请输入存款备注！',
                chg_bank:'请选择银行！',
                bank_Account:'请输入正确的银行账号！',
                bank_Address:'请输入银行地址！',
                paypassword1:'请输入正确的提款密码！',
                paypassword2:'请输入确认密码！',
                phone : '请填写手机号码！',
                wechat :'请填写微信号码！',
                birthday :'请填写您的生日！',
                str_input_oldpwd:'请输入原密码！',
                str_pwd_NoChg:'现密码与原密码不能相同！',
                str_input_pwd:'请输入新密码！',
                str_input_repwd:'请确认新密码！',
                str_pwd_limit:'请输入6-15位数字或者字母密码！',
                str_pay_pwd_limit:'请输入4-6位数字密码！',
                str_err_pwd:'密码与确认密码需要一致！'
            },
            allBankList:[]
        }
    },
    watch:{

    },
    mounted: function () {
        let _self = this;

        let base_SettingData = JSON.parse(localStorage.getItem('baseSetData'));
        _self.baseSettingData = base_SettingData?base_SettingData:'';
        let member_Data = _self.localStorageGet('userData');
        _self.memberData = member_Data?member_Data:'';
        _self.company_name = _self.baseSettingData.company_name;
        _self.userName =  _self.memberData?_self.memberData.UserName:'';
        let money_item = _self.localStorageGet('member_money');
        _self.userMoney = money_item?money_item:0;

        _self.tpl_name = base_SettingData?base_SettingData.tpl_name.replace('views/',''):'';
        if(_self.tpl_name=='3366/'){
            _self.showmore_dis = true;
        }

    },
    computed:{
        // 接口地址
        ajaxUrl() {
            return {
                captcha: this.baseUrl + '/include/validatecode/captcha.php', // 数字验证码
                whapi: this.baseUrl + '/api/pageMaintenanceApi.php', // 页面维护 type
                msgapi: this.baseUrl + '/api/messageApi.php', // 系统短信
                time: this.baseUrl + '/api/redaysApi.php', // 获取日期
                banner: this.baseUrl + '/api/indexBannerApi.php', // 轮播
                logout: this.baseUrl + '/logout.php', // 退出
                login: this.baseUrl + '/login_api.php', // 登录
                gustlogin: this.baseUrl + '/guest_login_save_phone_api.php', // 试玩手机号
                reg: this.baseUrl + '/mem_reg_add.php', // 注册
                agentreg: this.baseUrl + '/reg_agent.php', // 代理注册
                forgetpwd: this.baseUrl + '/forget_pwd.php', // 忘记密码
                umoney: this.baseUrl + '/account_api.php', // 获取余额 action: cp_b
                lottery: this.baseUrl + '/index_api.php', // 获取体育彩票接口地址
                third_cp: this.baseUrl + '/api/thirdLotteryApi.php', // 获取三方彩票接口地址
                mrelease: this.baseUrl + '/mrelease.php', // 获取基本配置信息
                notice: this.baseUrl + '/api/userEmailsApi.php', // action: notice 获取会员公告 notice，短信 message
                promo: this.baseUrl + '/api/promosListApi.php', // 优惠活动
                artice: this.baseUrl + '/api/articleApi.php', // 获取新闻
                newyear_1: this.baseUrl + '/api/newyear2020_888w.php', //  新年活动
                newyear_2: this.baseUrl + '/api/newyear2021HbApi.php', //  2021新年活动
                lucky: this.baseUrl + '/api/best_lucky.php', //  幸运大转盘活动
                uplive: this.baseUrl + '/api/zhenren_salary.php', //  真人升级活动
                upsport: this.baseUrl + '/api/sport_dj_salary.php', //  体育升级活动
                uplive_lq: this.baseUrl + '/api/zhenren_week_jinji.php', //  领取 真人升级活动
                upsport_lq: this.baseUrl + '/api/sport_dj_week_jinji.php', //  领取 体育升级活动
                deposit: this.baseUrl + '/account/deposit_one_api.php', // 存款列表
                banklist: this.baseUrl + '/account/deposit_two_bank_company.php', // 公司入款银行列表，弃用 api/bankListApi.php
                bankcompany: this.baseUrl + '/account/deposit_two_bank_company_save.php', // 公司入款存款提交
                alisaoma: this.baseUrl + '/account/bank_type_ALISAOMA_api.php', // 获取支付宝扫码方式
                wxsaoma: this.baseUrl + '/account/bank_type_WESAOMA_api.php', // 获取微信扫码方式
                saoma_save: this.baseUrl + '/account/bank_type_SAOMA_save.php', // 支付宝扫，微信码存款提交
                third_zfb: this.baseUrl + '/account/deposit_two_third_zfb.php', // 支付宝 三方支付
                third_wx: this.baseUrl + '/account/deposit_two_third_wx.php', // 微信 三方支付
                third_qq: this.baseUrl + '/account/deposit_two_third_qq.php', // qq 三方支付
                third_bank: this.baseUrl + '/account/deposit_two_third_bank.php', // 银行 三方支付
                third_bank_yh: this.baseUrl + '/account/deposit_two_third_bank_youhui.php', // 银行卡 公司入款
                third_kscz: this.baseUrl + '/account/deposit_two_third_kscz.php', // 快速充值 三方支付
                usdt_ewm: this.baseUrl + '/account/bank_type_USDT_api.php', // USDT, ?type=detail&bankid=85
                usdt_rate: this.baseUrl + '/api/usdtRateApi.php', // USDT汇率
                record: this.baseUrl + '/account/record_api.php', // 转账，存款记录
                banks: this.baseUrl + '/account/bankcard.php', // 获取所有银行名称
                userbank: this.baseUrl + '/account/updatebank.php', // 获取银行卡信息,打码量， 绑定银行卡(action:add,bankFlag:1),获取用户银行卡信息
                //userbet:this.baseUrl+'/account/user_bet.php', // 用户打码量
                take: this.baseUrl + '/account/take.php', // 提款
                realname: this.baseUrl + '/account/update_realname.php', // 绑定真实姓名
                chgpwd: this.baseUrl + '/account/changepwd_save.php', // 更换登录与支付密码
                sportbet: this.baseUrl + '/wagers_api.php', // 体育注单记录
                lotterybet: this.baseUrl + '/api/historyLotteryApi.php', // 彩票注单记录
                agbet: this.baseUrl + '/api/historyAgGameApi.php', // AG注单记录
                otherbet: this.baseUrl + '/api/betHistoryApi.php', // 棋牌，电子等记录
                agapi: this.baseUrl + '/ag_api.php',
                aggame: this.baseUrl + '/zrsx_login.php', // AG 获取进入游戏链接
                ogapi: this.baseUrl + '/og/og_api.php', // 进入游戏 action=getLaunchGameUrl&type=mobile
                bbinapi: this.baseUrl + '/bbin/bbin_api.php',
                cpapi: this.baseUrl + '/ajaxTran.php',
                kyqpapi: this.baseUrl + '/ky/ky_api.php',
                hgqpapi: this.baseUrl + '/hgqp/hg_api.php',
                vgqpapi: this.baseUrl + '/vgqp/vg_api.php',
                klqpapi: this.baseUrl + '/klqp/kl_api.php',
                lyqpapi: this.baseUrl + '/lyqp/ly_api.php',
                mgapi: this.baseUrl + '/mg/mg_api.php',
                aviaapi: this.baseUrl + '/avia/avia_api.php',
                fireapi: this.baseUrl + '/thunfire/fire_api.php',
                sportapi: this.baseUrl + '/sportcenter/sport_api.php',
                gmcpapi: this.baseUrl + '/gmcp/cp_api.php',
                mwapi: this.baseUrl + '/mw/mw_api.php',
                cqapi: this.baseUrl + '/cq9/cq9_api.php',
                fgapi: this.baseUrl + '/fg/fg_api.php',
                sportresult: this.baseUrl + '/result.php', // 赛果
                gameNumapi: this.baseUrl + '/api/indexGameNumApi.php', // 获取游戏数量
                otherlsapi: this.baseUrl + '/var_lid_api.php', // 其他联赛
                gjlsapi: this.baseUrl + '/loadgame_R_api.php', // 冠军联赛
                p3api: this.baseUrl + '/var_lid_p3_api.php', // 综合过关联赛
                otherapi: this.baseUrl + '/var_by_league_api.php', // 其他赔率请求
                orderapi: this.baseUrl + '/order/order_prepare_api.php', // 体育普通玩法下注
                p3orderapi: this.baseUrl + '/order/order_prepare_p3_api.php', // 体育综合过关玩法下注
                allgameapi: this.baseUrl + '/get_game_allbets.php', // 体育更多玩法/所有玩法

            }
        }
    },

    methods:{
        /****
         * name:localStorage的key
         * data:localStorage的Value
         * expire:localStorage的过期时间,默认是 8 小时(expire:8)后过期 expire 纯数字
         ****/
        localStorageSet: function(name, data, expire){
            expire= new Date().getTime() + (expire?expire:8) * 60 * 60 * 1000;
            const obj = {
                data,
                expire
            };
            localStorage.setItem(name, JSON.stringify(obj));
        },
        localStorageGet: function (name) {
            const storage = localStorage.getItem(name);
            const time = new Date().getTime();
            let result = '';
            if (storage) {
                const obj = JSON.parse(storage);
                if (time < obj.expire) {
                    result = obj.data;
                } else {
                    localStorage.removeItem(name);
                }
            }
            return result;
        },
      // 设置cookie
      setCookieAction :function(theName,theValue,theDay) {
        if(theName != "" && theName){
          let expDay = "Web,01 Jan 2030 18:56:35 GMT";
          if(theDay != null){
            theDay = eval(theDay);
            let setDay = new Date();
            setDay.setTime(setDay.getTime()+(theDay*1000*60*60*24));
            expDay = setDay.toGMTString();
          }
          //document.cookie = theName+"="+escape(theValue)+";expires="+expDay;
          document.cookie = theName+"="+escape(theValue)+";path=/;expires="+expDay+";";
          return true;
        }
        return false;
      },
      // 获取cookie
      getCookieAction :function(name) {
        var re = '(?:; )?' + encodeURIComponent(name) + '=([^;]*);?';
        re = new RegExp(re);
        if (re.test(document.cookie)) {
          return decodeURIComponent(RegExp.$1);
        }
        return '';
      },
      //清除所有cookie函数
      delCookieAction:function(theName) {
        let exp = new Date();
        exp.setTime(exp.getTime() - 1);
        let cval=this.getCookieAction(theName);
        if(cval!=null){
          document.cookie= theName + "='';path=/;expires="+exp.toGMTString();
        }
      },
        /* 获取基本配置信息 */
        getBaseSetting:function (callback) {
            let _self = this;
            _self.axios({
                method: 'post',
                params: {},
                url: _self.ajaxUrl.mrelease
            }).then(res=>{
                if(res){
                    let rest = res.data;
                    _self.baseSettingData = rest.data;
                    localStorage.setItem('baseStatus','1');
                    localStorage.setItem('baseSetData', JSON.stringify(_self.baseSettingData));
                    callback;
                }
            }).catch(res=>{
                console.log('基本配置请求失败');
            });
        },
        // 加载loading
        loadingContent:function (par) {
            var $mask = $('.mask') ;
            if(par ==true){
                $mask.show() ;
                $mask.html('正在加载中...') ;
            }else{
                $mask.hide();
                $mask.html('') ;
            }
        },
        /* 选中与取消选中 */
        checkAction: function () {
            if(this.checkStatus){
                this.checkStatus = false;
            }else{
                this.checkStatus = true;
            }
        },
        // 获取系统短信，0 系统短信，1 存款短信，2 取款短信
        getUserMessage:function (type) {
            let _self = this;
            _self.axios({
                method: 'post',
                params: {type:type},
                url: _self.ajaxUrl.msgapi
            }).then(res=>{
                if(res){
                    let rest = res.data;
                    if(rest.status==200){
                        if(rest.data.mem_message){ // 有信息
                            _self.$refs.autoDialog.setPublicPop(rest.data.mem_message,'','',10000);
                        }
                    }

                }
            }).catch(res=>{
                console.log('系统短信请求失败');
            });
        },
        /* 获取服务器日期 */
        getFuWuTime :function(){
            let _self=this;
                _self.axios({
                    method: 'post',
                    params: {},
                    url: _self.ajaxUrl.time
                }).then(res => {
                    if (res) {
                        let rest = res.data;
                        _self.FuWuTimeData = rest.data;
                    }
                }).catch(res => {
                    console.log('服务器时间请求失败');
                });
        },
        /*  获取体育彩票地址 */
        loginLotteryAction :function () {
            let _self =this;
            let cpUrlArr = '';
            let senddata={actype:'login',appRefer:'mobile'};
            let cp_url = _self.ajaxUrl.third_cp; // 三方彩票
            if(_self.tplnameList.indexOf( _self.tpl_name)>=0){
                cp_url = _self.ajaxUrl.lottery;
            }
            _self.axios({
                method: 'post',
                params: senddata,
                url: cp_url
            }).then(res=>{
                if(res){
                    let rest = res.data;
                    if(_self.tplnameList.indexOf( _self.tpl_name)>=0){ // 体育彩票
                        cpUrlArr = {
                            cp_login:rest.data[0].cpUrl,
                            cp_url:rest.data[0].urlLogin,
                            cp_url_num:1
                        }
                    }else{
                        cpUrlArr = {
                            cp_login:rest.data.third_cpUrl+'?params='+rest.data.params+'&thirdLotteryId='+rest.data.thirdLotteryId, // 拼接登录链接, toXinyong:是否跳转到信用盘,type: 不传即是默认官方，要到信用就传 1
                            cp_url:rest.data.third_cpUrl,
                            cp_url_num:1
                        }
                    }

                    localStorage.setItem('cpUrlArr',JSON.stringify(cpUrlArr)) ; // 彩票登录地址
                    setTimeout(()=>_self.$router.push('/home'),1000); // 跳转到首页
                }
            }).catch(res=>{
                console.log('彩票链接获取失败');
            });
        },
        /* 获取用户余额，AG 余额*/
        getUserMoney:function (type) {
            let _self = this ;
            let pars = {action:'cp_b'};
            let url = _self.ajaxUrl.umoney;
            if(type=='ag'){
                url = _self.ajaxUrl.agapi;
                pars.action = 'b';
            }

            _self.openNewGame('','mobile','no'); // _self.$route.path 网站维护

                _self.axios({
                    method: 'post',
                    params: pars,
                    url: url
                }).then(res=>{ // balance_cp 彩票余额
                    if(res){
                        let rest = res.data;
                        if(rest.status !=200){
                            if(rest.describe.indexOf('登录信息已过期')>=0){ // 判断登录过期
                                _self.clearUserData();
                                _self.$router.push('login');
                            }else{
                                _self.$refs.autoDialog.setPublicPop(rest.describe);
                            }
                        }else{
                            _self.userMoney = rest.data.balance_hg;
                            _self.cp_Money = rest.data.balance_cp;
                            _self.joinDays = rest.data.joinDays;
                            if(type=='ag'){ // ag 接口多一个字段
                                _self.ag_Money = rest.data.balance_ag;
                            }
                            _self.localStorageSet('member_money', rest.data.balance_hg); // 用户金额
                        }
                       // resolve();
                    }
                }).catch(res=>{
                    console.log('余额请求失败');
                });

        },
        /* 金额快速选择 */
        chooseMoney: function (val) {
          this.v_amount = Number(val);
          this.countUsdtMount();
        },
        // usdt 金额输入与计算
        countUsdtMount:function (){
          let _self = this;
          if(!_self.usdt_rate){
            return;
          }
          let zf_val = _self.v_amount/(_self.usdt_rate);
          zf_val = _self.changeTwoDecimal(zf_val,'up'); // 保留两位小数
          _self.usdt_mon = zf_val;
        },
        /*
         * 转账
         *  t_blance 转出方
         *  t_blance 转入方
         *  dat 参数
         *  获取余额 dat:{action:'b'}
         * */
        tranMoneyAction:function(f_blance,t_blance,dat) {
            let _self = this;
            if(dat.b<1){ // 余额不足
                _self.$refs.autoDialog.setPublicPop('额度不足!');
                return false;
            }
            let plat,tranUrl; // plat 转账平台
            if(f_blance=='hg'){ // 中心钱包
                plat = t_blance;
            }else{
                plat = f_blance;
            }
            if(plat=='aggame' || plat=='agby'){
                plat ='ag';
            }

            switch (plat){
                case 'cp':// 彩票
                    tranUrl = _self.ajaxUrl.cpapi;
                    break;
                case 'gmcp':// 彩票
                    tranUrl = _self.ajaxUrl.gmcpapi;
                    break;
                case 'ag':
                    tranUrl = _self.ajaxUrl.agapi;
                    break;
                case 'og':
                    tranUrl = _self.ajaxUrl.ogapi;
                    break;
                case 'bbin':
                    tranUrl = _self.ajaxUrl.bbinapi;
                    break;
                case 'ky':
                    tranUrl = _self.ajaxUrl.kyqpapi;
                    break;
                case 'vg':
                    tranUrl = _self.ajaxUrl.vgqpapi;
                    break;
                case 'ly':
                    tranUrl = _self.ajaxUrl.lyqpapi;
                    break;
                case 'kl':
                    tranUrl = _self.ajaxUrl.klqpapi;
                    break;
                case 'mg':
                    tranUrl = _self.ajaxUrl.mgapi;
                    break;
                case 'mw':
                    tranUrl = _self.ajaxUrl.mwapi;
                    break;
                case 'cq':
                    tranUrl = _self.ajaxUrl.cqapi;
                    break;
                case 'fg':
                    tranUrl = _self.ajaxUrl.fgapi;
                    break;
                case 'avia':
                    tranUrl = _self.ajaxUrl.aviaapi;
                    break;
                case 'fire':
                    tranUrl = _self.ajaxUrl.fireapi;
                    break;
            }

                _self.axios({
                    method: 'post',
                    params: dat,
                    url: tranUrl
                }).then(res=>{ // balance_cp 彩票余额
                    if(res){
                        let rest = res.data;
                        if(rest.status=='200'){ // 转账成功
                            if(dat.action=='fundLimitTrans'){ // 转账操作
                                _self.v_amount =0; // 复原转账金额
                                _self.$refs.autoDialog.setPublicPop('转账成功，请查看余额！');
                            }
                            if(rest.data){ // 部分初始化时 data 返回 null
                                if(rest.data[0]){
                                    if(rest.data[0].hg_balance){
                                        _self.userMoney = rest.data[0].hg_balance;
                                    }
                                }else{
                                    if(rest.data.hg_balance){
                                        _self.userMoney = rest.data.hg_balance;
                                    }else{ // ag 接口
                                        _self.userMoney = rest.data.balance_hg;
                                    }
                                }
                                if(_self.userMoney>=0){
                                  _self.localStorageSet('member_money',  _self.userMoney); // 用户金额
                                }

                                switch (plat){
                                    case 'cp':// 彩票
                                        _self.getUserMoney();
                                        break;
                                    case 'ag':
                                        if(dat.action=='gamelist_dianzi') { // 获取游戏列表操作
                                            _self.ag_game_list = rest.data;
                                        }
                                        if(dat.action=='fundLimitTrans') { // 转账操作
                                            _self.getUserMoney('ag');
                                        }
                                        if(dat.action=='b'){ // 获取余额
                                            _self.ag_Money = rest.data.balance_ag;
                                            _self.cp_Money = rest.data.balance_cp;
                                        }
                                        break;
                                    case 'gmcp':// 彩票

                                        _self.gmcp_Money = rest.data.gmcp_balance;
                                        break;
                                    case 'og':
                                        _self.og_Money = rest.data[0].og_balance;
                                        break;
                                    case 'bbin':
                                        _self.bbin_Money = rest.data.bbin_balance;
                                        break;
                                    case 'ky':
                                        _self.ky_Money = rest.data.ky_balance;
                                        break;
                                    case 'vg':
                                        _self.vg_Money = rest.data.vg_balance;
                                        break;
                                    case 'ly':
                                        _self.ly_Money = rest.data.ly_balance;
                                        break;
                                    case 'kl':
                                        _self.kl_Money = rest.data.kl_balance;
                                        break;
                                    case 'mg':
                                        if(dat.action=='gamelist_dianzi') { // 获取游戏列表操作
                                            _self.mg_game_list = rest.data;
                                        }else{
                                            _self.mg_Money = rest.data.mg_balance;
                                        }
                                        break;
                                    case 'mw':
                                        if(dat.action=='gamelist_dianzi') { // 获取游戏列表操作
                                            _self.mw_game_list = rest.data;
                                        }else{
                                            _self.mw_Money = rest.data[0].mw_balance;
                                        }
                                        break;
                                    case 'cq':
                                        if(dat.action=='gamelist_dianzi') { // 获取游戏列表操作
                                            _self.cq_game_list = rest.data;
                                        }else{
                                            _self.userMoney = rest.data.hg_balance;
                                            _self.cq_Money = rest.data.cq_balance;
                                        }
                                        break;
                                    case 'fg':
                                        if(dat.action=='gamelist_dianzi') { // 获取游戏列表操作
                                            _self.fg_game_list = rest.data;
                                        }else{
                                            _self.userMoney = rest.data.hg_balance;
                                            _self.fg_Money = rest.data.fg_balance;
                                        }
                                        break;
                                    case 'avia':
                                        _self.avia_Money = rest.data[0].avia_balance;
                                        break;
                                    case 'fire':
                                        _self.userMoney = rest.data.hg_balance;
                                        _self.fire_Money = rest.data.fire_balance;
                                        break;
                                }

                            }

                        }else{
                            _self.$refs.autoDialog.setPublicPop(rest.describe);
                        }

                    }
                }).catch(res=>{
                    console.log('转账失败');
                });


        },
        // 清除用户登录信息
        clearUserData: function () {
            let _self = this;
            localStorage.removeItem('p3BetArray');
            localStorage.removeItem('userData');
            localStorage.removeItem('cpUrlArr'); // 删除彩票登录链接
            localStorage.removeItem('mymsg'); // 是否弹窗信息
            localStorage.removeItem('member_money'); // 清除用户金额
            _self.getBaseSetting();
        },
        // 退出函数
        loginOut:function () {
            let _self = this ;
            return new Promise((resolve, reject)=>{
                _self.axios({
                    method: 'post',
                    params: {},
                    url: _self.ajaxUrl.logout
                }).then(res=>{
                    if(res){
                        _self.clearUserData();
                        if(_self.tpl_name.indexOf('bet365')>=0){
                         window.location.href='/home';
                        }else{
                            _self.$router.push('/home'); // 回到首页
                        }
                        resolve();
                    }
                }).catch(res=>{
                    console.log('退出失败');
                    reject(res);
                });

            })
        },
        /* 判断是否测试账号，部分页面测试账号不能进入  */
        judgeTestFlag:function () {
            let _self = this;
            if(_self.memberData.test_flag =='1'){ // 测试账号
                _self.$refs.autoDialog.setPublicPop('请注册真实账号');
                setTimeout(()=>_self.$router.back(),2000);
            }
        },
        // 返回上一步
        goBack:function(){
            this.$router.back();
        },
        /* 获取银行列表 */
        getAllBankList:function () {
            let _self = this ;
                _self.axios({
                    method: 'post',
                    params: {action_type:'banks'},
                    url: _self.ajaxUrl.banks
                }).then(res=>{
                    if(res){
                        let rest = res.data;
                        _self.allBankList=rest.data;
                    }
                }).catch(res=>{
                    console.log('银行列表请求失败');
                });

        },
        // 银行卡加密处理
        returnBankAccount: function(count){
            let resstr;
            if(count){
                let font_6 = count.substring(0 , 6); // 前六位
                let strleng= count.length;
                let back_3 = count.substring(strleng-3 , strleng); // 后三位
                resstr = font_6+'******'+back_3 ;
            }
            return resstr;
        },
        /*
        ** 打开游戏判断是否登录, log 是否需要登录，默认需要
        *  type 打开游戏类型，如，ag,mg
        * */
        openNewGame:function (url,type,log,e) {
          let _self = this ;
          let gameNewWin;
          if(e){ // 电竞版本
              let cur = $(e.currentTarget);
              if(cur.attr('data-type')==type){
                  cur.addClass('active').siblings().removeClass('active');
              }
          }

          if(!log){ // 打开游戏，需要登录
              if(!_self.userName){
                  _self.$refs.autoDialog.setPublicPop('请先登录');
                  setTimeout(()=> _self.$router.push('login'),2000);
                  return false;
              }
          }
            if(type) { // 进入游戏判断
                if (_self.curtypeList.indexOf(type) < 0 ) { // 新窗口打开
                    if((_self.tpl_name == _self.tplnameThd && (type=='avia'||type=='fire'))){ // 电竞

                    }else{
                        gameNewWin =  window.open('about:blank',"_blank");
                    }
                }
                    _self.axios({
                        method: 'post',
                        params: {type:_self.returnWhPar(type)},
                        url: _self.ajaxUrl.whapi
                    }).then(res=>{ // state:1 维护
                        if(res){
                            let rest = res.data;
                            if(rest.data.state=='1'){ // 维护中
                                if (_self.curtypeList.indexOf(type) < 0 ) { // 新窗口打开
                                    if((_self.tpl_name == _self.tplnameThd && (type=='avia'||type=='fire'))){ // 电竞

                                    }else{
                                        gameNewWin.close();
                                    }
                                }
                                _self.$router.push('maintenance?content='+rest.data.content+'&title='+rest.data.title);
                            }else{
                                if (_self.curtypeList.indexOf(type) >= 0) { // 棋牌不需要新窗口打开
                                    if(url){
                                        _self.$router.push(url);
                                    }
                                } else if(_self.tpl_name == _self.tplnameThd && (type=='avia'||type=='fire')){ // 0086dj
                                    if(url){
                                        body_dzjj.location.replace(url);
                                    }
                                }else{
                                    if(url){
                                      //let win_url = window.location;
                                      //url = win_url.protocol+'//'+win_url.host+'/#/'+url; // hash 模式下处理
                                      gameNewWin.location.href = url;
                                    }
                                }

                            }
                        }
                    }).catch(res=>{
                        console.log('维护请求失败');
                    });

            }else{
                window.open(url,'_blank');
            }

        },
        // 返回维护参数： rb,today,future,video(ag 真人),game(ag 电子),lottery(彩票),mobile,ky,ly,vgqp,hgqp,avia,og,mw,cq,fg,bbin,mg,klqp,thunfire
        returnWhPar:function (type) {
          let re_type=type;
          switch (type){
              case 'ag':
                  re_type = 'video';
                  break;
              case 'aggame':
              case 'agby':
                  re_type = 'game';
                  break;
              case 'cp':
              case 'gmcp':
                  re_type = 'lottery';
                  break;
              case 'vg':
              case 'kl':
                  re_type = type+'qp';
                  break;
              case 'fire':
                  re_type = 'thunfire';
                  break;
          }
          return re_type;
        },
          //  下拉回弹效果
        setScroll: function() {
            this.navScroll = new iScroll("nav-wrapper",{ // 侧边栏
                hScrollbar:false,
                vScrollbar:false,
                click: true ,
            });
            this.conScroll = new iScroll("content-wrapper",{  // 投注区域
                onScrollEnd: function(){
                    this.refresh() ;
                },
               /* onBeforeScrollMove:function(e){
                    e.preventDefault();
                },*/
                vScroll:true,
                mouseWheel: true ,
                hScrollbar:false ,
                vScrollbar:false ,
                click: true ,
               // momentum: false ,
                useTransform: false ,
                useTransition: false ,
                // snapThreshold:0.5
            });

        },

        //禁止遮罩层以下屏幕滑动
        touchmove :function(){
            $(document).on("touchmove", function (e) {
                var e = e || event,
                    target = e.target || e.srcElement;
                if (e.target.className.indexOf("so-shade") >= 0) { //className為弹窗的蒙层的类名
                    e.preventDefault();
                }
            });
        },

        //格式化赔率
        payoffFormat:function(val){
            return (Number(val)/10000).toFixed(3);
        },
        // 美东时间设置
        setAmerTime :function (el,dayoff) {
            let _self = this;
            var today = new Date();
            today.setHours(today.getHours() - 12);
            var y = today.getFullYear();
            var m = today.getMonth() + 1;
            var d = today.getDate();
            var h = today.getHours();
            var mm = today.getMinutes();
            var s = today.getSeconds();
            m =  _self.checkTime(m);
            d = _self.checkTime(d);
            h = _self.checkTime(h);
            mm = _self.checkTime(mm);
            s = _self.checkTime(s);
            var returnRes ='';
            if(el =='.time_textbox'){
                // $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm); // 只到分
                returnRes=y+"-"+m+"-"+d+" "+h+":"+mm;
            }else if(dayoff=='dayoff'){ // 到天
                // $(el).val(y+"-"+m+"-"+d); // 只到分
                returnRes=y+"-"+m+"-"+d;
            }else if(dayoff=='daystart'){ // 开始时间 00:00
               // $(el).val(y+"-"+m+"-"+d+" 00:00");
                returnRes=y+"-"+m+"-"+d+" 00:00";
            }else if(dayoff=='dayend') { // 开始时间 23:59
                // $(el).val(y+"-"+m+"-"+d+" 23:59");
                returnRes=y+"-"+m+"-"+d+" 23:59";
            }else{
                $(el).val(y+"-"+m+"-"+d+" "+h+":"+mm+":"+s);
            }
            return returnRes;
       },
        /**
         * 1位数补0为2位数
         * @param i
         * @returns {*}
         */
         checkTime:function(i) {
            if(isNaN(i)){
              i=0;
            }
            if (i<10){i="0" + i}
            return i
        } ,
        // cal 容器 new_year_time
        setTimerAc:function (cal,year,month,day,hour,minute,second){
          let _self = this;
          var leftTime = (new Date(year,month-1,day,hour,minute,second)) - (new Date()); //计算剩余的毫秒数
          var days = parseInt(leftTime / 1000 / 60 / 60 / 24 , 10); //计算剩余的天数
          var hours = parseInt(leftTime / 1000 / 60 / 60 % 24 , 10); //计算剩余的小时
          var minutes = parseInt(leftTime / 1000 / 60 % 60, 10);//计算剩余的分钟
          var seconds = parseInt(leftTime / 1000 % 60, 10);//计算剩余的秒数
          days = _self.checkTime(days);
          hours = _self.checkTime(hours);
          minutes = _self.checkTime(minutes);
          seconds = _self.checkTime(seconds);
          var timer_in,timer_in_1;
          var str_1 = '红包活动开启中';
          if(seconds>=0){
            timer_in = setTimeout(()=>_self.setTimerAc('.new_year_time','2021','02','11','00','00','00'),1000); // 领取开始时间 如 2021,02,11,00,00,00, 这里是北京时间
            str_1 = days+"天" + hours+"时" + minutes+"分"+seconds+"秒"; // 侧边区域
            if(cal=='.new_year_time_de'){ // 活动内页
              timer_in_1 = setTimeout(()=>_self.setTimerAc('.new_year_time_de','2021','02','12','23','59','59'),1000); // 领取结束时间
              $('.timer_d').html('你目前还剩下 '+days+' 天时间');
              str_1 = '剩余时间<span class="timer_d">'+days+'</span>天' +
                '<span class="timer_h">'+hours+'</span>时' +
                '<span class="timer_m">'+minutes+'</span>分' +
                '<span class="timer_s">'+seconds+'</span>秒';
            }
          }else {
            clearTimeout(timer_in);
            clearTimeout(timer_in_1);
            if(cal=='.new_year_time_de'){ // 活动内页
              $('.newyear_hby_btn').hide();// 召唤红包雨按钮
              $('.timer_d').html('你目前还剩下 '+days+' 天时间');
              str_1 = '剩余时间<span class="timer_d">00</span>天' +
                '<span class="timer_h">00</span>时' +
                '<span class="timer_m">00</span>分' +
                '<span class="timer_s">00</span>秒';
            }

          }
          $(cal).html(str_1);
        },
        // 时间戳转换
        formatTimeUnlix:function (v,type) {
            if (v == null) {
                return '';
            }
            var date = new Date(v);
            var year = date.getFullYear();
            var month = (date.getMonth() + 1 < 10) ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1);
            var day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
            var hours = (date.getHours() < 10) ? '0' + date.getHours() : date.getHours();
            var minutes = (date.getMinutes() < 10) ? '0' + date.getMinutes() : date.getMinutes();
            var seconds = (date.getSeconds() < 10) ? '0' + date.getSeconds() : date.getSeconds();
            if(type =='0'){
                return year + '/' + month + '/' + day + ' ' + hours + ':' + minutes ;
            }else{
                return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
            }

        },
        // 倒计时处理
        formatTime:function(second, type) {
            var bk;
            if (type == 0) {
                var h = parseInt(second / 3600);
               // var h = Math.floor(second / 3600);
                var f = parseInt(second % 3600 / 60);
               // var f = Math.floor((second - (h * 60 * 60)) / 60);
                var s = parseInt(second % 60);
              //  var s = (second - (h * 60 * 60) - (f * 60));
              // second --;
              bk = (h < 10 ? "0" + h : h)+ ":" + (f < 10 ? "0" + f : f) + ":" + (s < 10 ? "0" + s : s)
              // bk = h + ":" + (f < 10 ? "0" + f : f) + ":" + (s < 10 ? "0" + s : s)
            } else {
                bk = second.split(":");
                bk = parseInt(bk[0] * 3600) + parseInt(bk[1] * 60) + parseInt(bk[2])
            }
            return bk
        },
        fftimeWithHour:function (n) {
            var sec_num = parseInt(n, 10); // don't forget the second param
            var hours   = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours   < 10) {hours   = "0"+hours;}
            if (minutes < 10) {minutes = "0"+minutes;}
            if (seconds < 10) {seconds = "0"+seconds;}
            return parseInt(hours)>0 ? hours+':'+minutes+':'+seconds : minutes+':'+seconds;
        },
        fftime:function (n) {
            return Number(n) < 10 ? '' + 0 + Number(n) : Number(n);
        },

        format:function(dateStr) {  //格式化时间
           return new Date(dateStr.replace(/[\-\u4e00-\u9fa5]/g, '/'));
        },
        diff:function (t) {  //根据时间差返回相隔时间
            return t > 0 ? {
                day: Math.floor(t / 86400),
                hour: Math.floor(t % 86400 / 3600),
                minute: Math.floor(t % 3600 / 60),
                second: Math.floor(t % 60)
            } : {day: 0, hour: 0, minute: 0, second: 0};
        },

        /*
         * 数字转千分位
         * */
        formatNumber:function (num) {
            return (num + '').replace(/\d{1,3}(?=(\d{3})+(\.\d*)?$)/g, '$&,');
        },
        /*
         * 还原金额，去除逗号
         * */
        returnMoney:function(s) {
            return parseFloat(s.replace(/[^\d\.-]/g, ""));
        },
        /*
         * 保留两位小数，roundUp 参数四舍五入
         * */
        changeTwoDecimal:function (x,roundUp) {
            var f_x = parseFloat(x);
            // console.log(typeof (f_x));
            if (isNaN(f_x) || f_x==0) {
                return '';
            }
            if(roundUp){ // 四舍五入
                var f_x = Math.round(x * 100) / 100;
            }else{ // 不四舍五入，直接保留两位小数
                var f_x = x * 100/100 ;
            }

            var s_x = f_x.toString();
            var pos_decimal = s_x.indexOf('.');
            var arr = s_x.split('.');

            if(pos_decimal>0){
                if(arr[1].length>1){
                    s_x = arr[0]+'.'+arr[1].substr(0,2);
                }else{
                    while (s_x.length <= pos_decimal + 2) {
                        s_x += '0';
                    }
                }
            }else{
                if (pos_decimal < 0) {
                    pos_decimal = s_x.length;
                    s_x += '.';
                }
                while (s_x.length <= pos_decimal + 2) {
                    s_x += '0';
                }
            }
            return s_x;
        },
        // 金额转换,分转成元
        roundAmt:function(v) {
            return isNaN(v) ? '0.00' : (v / 100).toFixed(2);
        },
        // 金额转换，支持实数, 元转成分
        monAmt :function(v) {
            return /^[-+]?\d+(\.\d*)?$/.test(v) ? v * 100 : '';
        },

        /*
         * 数字转换，显示千位符，s 要转换的数字，n 保留n位小数
         * */
        fortMoney:function (s, n) {
            n = n > 0 && n <= 20 ? n : 2;
            s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
            var l = s.split(".")[0].split("").reverse(),
                r = s.split(".")[1];
            let t = "";
            for(let i = 0; i < l.length; i ++ ){
                t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
            }
            return t.split("").reverse().join("") + "." + r;
        },
        isNumber:function(num,can) { // 是否为数字判断
            var re = /^[1-9]{1}[0-9]*$/ ; // 不能以0开头
            if(can){
                re = /^[0-9]+$/g; // 可以0 开头,提款密码
            }
            return re.test(num);
        },
        positiveNum :function(num) { // 验证数字，正整数判断，包含零
            var re = /^[0-9]*$/;
            return re.test(num);
        },
        positiveEngNum:function (val) { // 验证英文与数字或者下划线，帐号验证和密码验证
            var re = /^[A-Za-z0-9|_|]+$/;
            return re.test(val);
        },
        trueName :function(val) { // 验证真实姓名，中文字符
            var re = /^[\u4e00-\u9fa5]+$/;;
            return re.test(val);
        },
        phoneNum :function(num) { // 验证手机号码
            var re = /^1[3|4|5|7|8|][0-9]{9}$/;
            return re.test(num);
        },
        checkEmail:function (val) { // 验证邮箱
            var re = /^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;
            return re.test(val);
        },
        checkWechat :function(val) { // 验证微信
            var re = /^[a-zA-Z\d_]{5,}$/;
            return re.test(val);
        },
        checkqq :function(val) { // 验证qq
            var re = /^[1-9][0-9]{4,}$/;
            return re.test(val);
        },
         //验证银行卡号
        isBankAccount: function (val,el) {
            var reg = /^[0-9]{10,20}$/;
            return reg.test(val) ;
        }

    }
};
export default MyMixin;
