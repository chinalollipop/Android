var head_gtype = 'FT';// 默认
var ioradio = '';
function bodyLoad(showtype){
    selectClickAll();
    getMatchNum(showtype);
    setInterval(function () {
        getMatchNum(parent.showtype?parent.showtype:showtype);
    },120000)
    changeTypeMatch();
    changeSecGameType() ;
    changeThirdType();
    openGameRoul();
}
// 标签收缩点击事件
function selectClickAll() {
    $('.NonInplayMenuTitle').on('click',function () {
        var $cl = $('.non-inplay-menu-content');
        var hs = $cl.hasClass('opened');
        if(hs){
            $cl.removeClass('opened').addClass('closed');
        }else{
            $cl.removeClass('closed').addClass('opened');
        }
    })

    // 切换到投注记录页面
    $('.sport_to_betrecord>a').on('click',function () {
        if(!uid.replace(/\s+/g,"")){ // 去除空字符串，未登录
            parent.body.body_var.bodyVarAlert('请先登录');
            return;
        }
        var centerUrl = 'tpl/lobby/middle_user_all_center.php?type=userbetaccount';
        var tpl_name = parent.parent.tplName;
        if(tpl_name=='views/0086/' || tpl_name=='views/6668/'){
            centerUrl = 'tpl/lobby/middle_user_account.php';
        }
        parent.$('.middle_content').load(tpl_name+centerUrl,function () {

        })
    })

    
}

function reloadGameIndex(showtype,rtype) {
    var url ; // 跳转路径
    url = '/app/member/'+head_gtype+'_'+ (showtype=='future'?'future':'browse') +'/index.php?mtype=4&rtype='+rtype+'&showtype='+showtype ;
    parent.body.location = url ;
}
/*
*  一级标签切换
* 切换今日，早盘，滚球
*  head_gtype : FT , BK ,默认 FT
*
*/

function changeTypeMatch() {
     $('.NonInplayMenuTypes').on('click','.type',function () {
         head_gtype = 'FT' ; // 每次切换初始化为足球
         var rtype = $(this).attr('data-rtype') ;
         var showtype = $(this).attr('data-showtype') ;
         var rb_tip = '' ; // 切换 rtype

         parent.showtype = showtype;
        // console.log(rtype+'--'+showtype) ;
         $(this).addClass('selected').siblings().removeClass('selected');

         $('.change_game_type').removeClass('sub-list-sport-title-true').next().addClass('closed').removeClass('opened') ; // 二级标签处理
         $('.change_game_type:eq(0)').addClass('sub-list-sport-title-true').next().addClass('opened').removeClass('closed') ; // 二级标签处理 第一项
         $('.change_game_type').next().find('.sub-menu-item').removeClass('selected').eq(0).addClass('selected'); // 三级标签处理

         if(rtype == 'p3'){ // 综合过关
             $('.change_game_type').next().addClass('closed');

         }else{ // 非综合过关
             if(showtype == 'future'){ // 更新综合过关标签
                 $('.p3_tag_nav').attr('data-showtype','future') ;
             }else{
                 $('.p3_tag_nav').attr('data-showtype','today') ;
             }

             if(showtype == 'rb'){ // 滚球没有冠军
                 rb_tip = 'r';
                 $('.sub-menu-item-champion').hide() ;

             }else{
                 rb_tip = '';
                 $('.sub-menu-item-champion').show() ;
             }
             $('.SubNonInplayMenu .sub-menu-item ').each(function () {
                 var statusType = $(this).attr('data-statusType') ;
                 var gameType = $(this).attr('data-gtype') ;
                 if(statusType){
                     if(showtype == 'rb' && statusType == 'r'){ // 足球 篮球 滚球 让球/大小盘
                         $(this).attr('data-rtype','re') ;
                     }else{
                         $(this).attr('data-rtype',rb_tip+statusType) ;
                     }
                 }

             })


             getMatchNum(showtype) ; // 更新赛事数量
         }

         reloadGameIndex(showtype,rtype) ;



     });
}

/*
*  二级标签 切换 足球，篮球
* */
function changeSecGameType() {
    $('.change_game_type').on('click',function () {
        var gtype = $(this).attr('data-gtype');
        if(gtype == 'TN' || gtype == 'VB'){
            layer.msg('暂没有赛事',{time:2000});
            return ;
        }
        head_gtype = gtype ;
        var rtype = $('.NonInplayMenuTypes').find('.selected').attr('data-rtype') ;
        var showtype = $('.NonInplayMenuTypes').find('.selected').attr('data-showtype') ;

       // console.log(rtype+'=='+showtype)

        $(this).addClass('sub-list-sport-title-true').parents('.sub-list-type-item').siblings().find('.change_game_type').removeClass('sub-list-sport-title-true') ;

        $(this).next().find('.sub-menu-item').removeClass('selected').eq(0).addClass('selected');

        if(rtype == 'p3') { // 综合过关
            $('.sub-list-type-item').siblings().find('.SubNonInplayMenu').addClass('closed') ;
        }else{
            $(this).next().addClass('opened').removeClass('closed').parents('.sub-list-type-item').siblings().find('.SubNonInplayMenu').removeClass('opened').addClass('closed') ;
        }

        reloadGameIndex(showtype,rtype) ;
    })
}

/*
*  三级标签切换
* */
function changeThirdType() {
    $('.sub-menu-item').on('click',function () {

        var gtype = $(this).attr('data-gtype');
        head_gtype = gtype ;
        var rtype = $(this).attr('data-rtype') ;
        var showtype = $('.NonInplayMenuTypes').find('.selected').attr('data-showtype') ;
        $(this).addClass('selected').siblings().removeClass('selected') ;
        if(rtype == 'champion'){ // 冠军
            var cn_url = '/app/member/browse_FS/loadgame_R.php?FStype='+head_gtype+'&showtype='+showtype ;
            parent.body.location = cn_url ;
        }else{
            reloadGameIndex(showtype,rtype) ;
        }

    });
}



/* 
*  获取比赛赛事数量
*  type : today 默认今天 , future, rb
* */
function getMatchNum(showtype) {
    if(!showtype){
        showtype = 'today';
    }

    var actionurl = '/app/member/api/getBallNumber.php';
    $.ajax({
        type : 'POST',
        dataType : 'json',
        url : actionurl ,
        data : {showtype:showtype},
        success:function(res) {
            if(res){
                $('.ft_count_number').html(res.data.FT_NUM);
                $('.bk_count_number').html(res.data.BK_NUM);
                $('.today_count_number').html(res.data.TOTAL_TODAY_NUM);
                $('.future_count_number').html(res.data.TOTAL_FUTURE_NUM);
                $('.rb_count_number').html(res.data.TOTAL_RB_NUM);

            }

        }

    });
}



// 下注函数 isRuning 是否为
function betOrder(gtype,wtype,param,isRuning){
    if(!uid.replace(/\s+/g,"")){ // 去除空字符串，未登录
        if(wtype=='NFS'){ // 冠军
            parent.body.bodyVarAlert('请先登录');
        }else{
            parent.body.body_var.bodyVarAlert('请先登录');
        }
        return;
    }
    if (wtype=="P3"||wtype=="PR"){
        //keepGold_PR="";
    }else{
        keepGold="";
        keepGold_PR="";
    }

    ioradio="";
    parent.$('.add-bet-container').hide();
    var url=parseUrl(gtype,wtype,param,isRuning);
    parent.bet_order_frame.location.replace(url);

}

function parseUrl(gtype,wtype,param,isRuning){

    var urlArray=new Array();
    var rouhc = new Array("ROUH","ROUC","HRUH","HRUC"); // 篮球得分大小
    var ouhc = new Array("OUH","OUC","HOUH","HOUC"); // 篮球得分大小

    urlArray['R']=new Array("../"+gtype+"_order/"+gtype+"_order_r.php");
    urlArray['HR']=new Array("../"+gtype+"_order/"+gtype+"_order_hr.php");
    urlArray['OU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou.php");
    urlArray['HOU']=new Array("../"+gtype+"_order/"+gtype+"_order_hou.php");
    urlArray['M']=new Array("../"+gtype+"_order/"+gtype+"_order_m.php");
    urlArray['HM']=new Array("../"+gtype+"_order/"+gtype+"_order_hm.php");
    urlArray['EO']=new Array("../"+gtype+"_order/"+gtype+"_order_t.php");
    urlArray['REO']=new Array("../"+gtype+"_order/"+gtype+"_order_rt.php"); // 2018新增
    urlArray['HEO']=new Array("../"+gtype+"_order/"+gtype+"_order_t.php"); // 2018新增
    urlArray['HREO']=new Array("../"+gtype+"_order/"+gtype+"_order_rt.php"); // 2018新增
    urlArray['PD']=new Array("../"+gtype+"_order/"+gtype+"_order_pd.php");
    urlArray['HPD']=new Array("../"+gtype+"_order/"+gtype+"_order_hpd.php");
    urlArray['HRPD']=new Array("../"+gtype+"_order/"+gtype+"_order_hrpd.php"); // 2018新增
    urlArray['RPD']=new Array("../"+gtype+"_order/"+gtype+"_order_rpd.php"); // 2018新增
    urlArray['F']=new Array("../"+gtype+"_order/"+gtype+"_order_f.php");
    urlArray['RF']=new Array("../"+gtype+"_order/"+gtype+"_order_rf.php"); // 2018新增
    urlArray['T']=new Array("../"+gtype+"_order/"+gtype+"_order_t.php");
    urlArray['HT']=new Array("../"+gtype+"_order/"+gtype+"_order_t.php"); // 2018新增
    urlArray['RT']=new Array("../"+gtype+"_order/"+gtype+"_order_rt.php"); // 2018新增
    urlArray['HRT']=new Array("../"+gtype+"_order/"+gtype+"_order_rt.php"); // 2018新增
    urlArray['SP']=new Array("../"+gtype+"_order/"+gtype+"_order_sp.php");
    urlArray['P']=new Array("../"+gtype+"_order/"+gtype+"_order_p.php");
    urlArray['P3']=new Array("../"+gtype+"_order/"+gtype+"_order_p3.php");
    urlArray['PR']=new Array("../"+gtype+"_order/"+gtype+"_order_pr.php");
    urlArray['RE']=new Array("../"+gtype+"_order/"+gtype+"_order_re.php");
    urlArray['HRE']=new Array("../"+gtype+"_order/"+gtype+"_order_hre.php");
    urlArray['ROU']=new Array("../"+gtype+"_order/"+gtype+"_order_rou.php");
    urlArray['HROU']=new Array("../"+gtype+"_order/"+gtype+"_order_hrou.php");
    urlArray['RM']=new Array("../"+gtype+"_order/"+gtype+"_order_rm.php");
    urlArray['HRM']=new Array("../"+gtype+"_order/"+gtype+"_order_hrm.php");
    urlArray['NFS']=new Array("../"+gtype+"_order/"+gtype+"_order_nfs.php");

    //2018+vv
    urlArray['AR']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['AOU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['AM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+
    urlArray['BR']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['BOU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['BM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+
    urlArray['CR']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['COU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['CM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+
    urlArray['DR']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['DOU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['DM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+
    urlArray['ER']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['EOU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['EM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+
    urlArray['FR']=new Array("../"+gtype+"_order/"+gtype+"_order_r15.php");//	15分钟盘口	2018+
    urlArray['FOU']=new Array("../"+gtype+"_order/"+gtype+"_order_ou15.php");//	15分钟盘口	2018+
    urlArray['FM']=new Array("../"+gtype+"_order/"+gtype+"_order_m15.php");//	15分钟盘口	2018+

    urlArray['TS']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	双方进球数	2018+
    urlArray['HTS']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	双方进球数	2018+
    urlArray['WM']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	净胜球数	2018+
    urlArray['DC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	双重机会	2018+
    urlArray['CS']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	零失球	2018+
    urlArray['WN']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//	零失球获胜	2018+

    urlArray['MOUA']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//独赢 & 进球 大 / 小
    urlArray['MOUB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['MOUC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['MOUD']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");

    urlArray['MTS']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//独赢 & 双方球队进球

    urlArray['OUTA']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//进球 大 / 小 & 双方球队进球
    urlArray['OUTB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUTC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUTD']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");

    urlArray['MPG']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//独赢 & 双方球队进球

    urlArray['F2G']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//先进2球的一方
    urlArray['F3G']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//先进3球的一方
    urlArray['HG']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//最多进球的半场
    urlArray['MG']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//最多进球的半场 - 独赢
    urlArray['SB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//双半场进球
    urlArray['FG']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//首个进球方式
    urlArray['T3G']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//首个进球时间-3项
    urlArray['T1G']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//首个进球时间

    urlArray['DUA']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//双重机会 & 进球 大 / 小
    urlArray['DUB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['DUC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['DUD']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");

    urlArray['DS']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//双重机会 & 双方球队进球
    urlArray['DG']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//双重机会 & 最先进球

    urlArray['OUEA']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//进球 大 / 小 & 进球 单 / 双
    urlArray['OUEB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUEC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUED']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");

    urlArray['OUPA']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//进球 大 / 小 & 最先进球
    urlArray['OUPB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUPC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");
    urlArray['OUPD']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");

    urlArray['W3']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//三项让球投注
    urlArray['BH']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//落后反超获胜
    urlArray['WE']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//赢得任一半场
    urlArray['WB']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//赢得所有半场
    urlArray['TK']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//开球球队

    urlArray['OUH']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//球队进球数: 主队 - 大
    urlArray['OUC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//球队进球数: 客队 - 大
    urlArray['HOUH']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//球队进球数: 主队 - 大 - 上半场
    urlArray['HOUC']=new Array("../"+gtype+"_order/"+gtype+"_order_single.php");//球队进球数: 客队 - 小 - 上半场

    //console.log(urlArray[wtype]);
    //console.log(isRuning);

    if(urlArray[wtype] == undefined && isRuning){// 足球滚球玩法

        var runBallR15Ft = new Array("ARR","BRR","CRR","DRR","ERR","FRR");
        var runBallOU15Ft = new Array("AROU","BROU","CROU","DROU","EROU","FROU");
        var runBallM15Ft = new Array("ARM","BRM","CRM","DRM","ERM","FRM");
        var runBallROUFt = new Array("ROUH","ROUC");
        var runBallMethodFt = new Array("HRUH","HRUC","RTS","RTS2","RHTS","RWM","RDC","RCS","RWN",
            "RMUA","RMUB","RMUC","RMUD","RMTS","RUTA","RUTB","RUTC","RUTD","RMPG","RF2G","RF3G",
            "RHG","RMG","RSB","RFG","RT3G","RT1G","RDUA","RDUB","RDUC","RDUD","RDS","RDG","RUEA",
            "RUEB","RUEC","RUED","RUPA","RUPB","RUPC","RUPD","RW3","RBH","RWE","RWB","RTK",
            "ARG");
        ///*
        if(in_array(wtype,runBallROUFt)){
            urlArray[wtype]=new Array("../"+gtype+"_order/"+gtype+"_order_rouhc.php");
        }
        if(in_array(wtype,runBallMethodFt)){
            urlArray[wtype]=new Array("../"+gtype+"_order/"+gtype+"_order_rsingle.php");
        }
        if(in_array(wtype,runBallR15Ft)){
            urlArray[wtype]=new Array("../"+gtype+"_order/"+gtype+"_order_rr15.php");
        }
        if(in_array(wtype,runBallOU15Ft)){
            urlArray[wtype]=new Array("../"+gtype+"_order/"+gtype+"_order_rou15.php");
        }
        if(in_array(wtype,runBallM15Ft)){
            urlArray[wtype]=new Array("../"+gtype+"_order/"+gtype+"_order_rm15.php");
        }
        //*/
    }

    if(gtype=="BK"){
        /* 篮球得分大小 开始 */
        for(var ii in ouhc){
            if(wtype==ouhc[ii]){
                wtype = "OUHC";
            }
        }
        for(var ii in rouhc){
            if(wtype==rouhc[ii]){
                wtype = "ROUHC";
            }
        }
        urlArray['ROUHC']=new Array("../"+gtype+"_order/"+gtype+"_order_rouhc.php");
        urlArray['OUHC']=new Array("../"+gtype+"_order/"+gtype+"_order_ouhc.php");
        /* 篮球得分大小 结束 */
    }

    if(wtype=="SP" && gtype=="FT"){
        var url=urlArray[wtype]+"?"+param+'&wtype='+wtype;
    }else{
        if(param.indexOf('&wtype=')<0){
            var url=urlArray[wtype]+"?"+param+'&wtype='+wtype;
        }else{
            var url=urlArray[wtype]+"?"+param;
        }
    }

    if(isRuning && gtype=="FT"){ url=url+'&flag=all&id='+isRuning }
    if(isRuning && gtype=="BK"){ url=url+'&id='+isRuning }
    return url;
}

function in_array(val,ary){
    for(var k=0;k<=ary.length;k++){
        if(val==ary[k])return true;
    }
    return false;
}


function reload_var(){
    parent.refresh_var='Y';
    self.location.reload();
}


var xmlHttp;
function createXHR(){
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    }else if (window.ActiveXObject) {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    if (!xmlHttp) {
        alert('您使用的瀏覽器不支援 XMLHTTP 物件');
        return false;
    }
}
function sendRequest(url){

    createXHR();
    xmlHttp.open('GET',url,true);
    xmlHttp.onreadystatechange=catchResult;
    xmlHttp.send(null);
}

function catchResult(){
    if (xmlHttp.readyState==4){
        s=xmlHttp.responseText;
        if (xmlHttp.status == 200) {
            //alert("已成功加入~~"+s+":");
            // location.reload();
            document.getElementById('showURL').innerHTML=s;

            var obj = document.getElementById('newdomain');
            obj.submit();
            // document.getElementById(s).innerHTML='<img src="058/btn_cart.gif" width="129" height="32" align="absmiddle" />';
        }else{
            alert('執行錯誤,代碼:'+xmlHttp.status+'\('+xmlHttp.statusText+'\)');
        }
    }
}
