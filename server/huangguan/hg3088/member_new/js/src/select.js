function bodyLoad(){
    close_bet();
    setMsg(msg);
}

function setMsg(msg){
    document.getElementById('real_msg').innerHTML=msg;
}
function showOrder(){

    try{
        bet_order_frame.resetTimer();

    }catch(e){}
    document.getElementById('rec_frame').height=0;
    rec_frame.document.close();
    document.getElementById('order_button').className="ord_on";
    document.getElementById('record_button').className="record_btn";
    var betDiv=document.getElementById('bet_div');
    var rec5Div=document.getElementById('rec5_div');
    betDiv.style.display="block";
    rec5Div.style.display="none";
    document.getElementById('pls_bet').style.display="none";
    document.getElementById('info_div').style.display='block'; // 显示公告
    document.getElementById('left-game-add').style.display='block'; // 显示右侧游戏
    try {
        document.getElementById('bet_promos_div').style.display='block'; // 显示右侧游戏
    }catch (e) { }


}
function showRec(){
    try{
        bet_order_frame.clearAllTimer();
    }catch(e){}
    try{
        close_bet();
    }catch(e){}
    //  bet_order_frame.document.close();
    document.getElementById('order_button').className="ord_btn";
    document.getElementById('record_button').className="record_on";

    var betDiv=document.getElementById('bet_div');
    var rec5Div=document.getElementById('rec5_div');

    betDiv.style.display="none";
    rec5Div.style.display="block";
    rec5_div.focus();
    rec_frame.location.replace("./today/show10rec.php?uid="+top.uid+"&langx="+top.langx);
    document.getElementById('pls_bet').style.display="none";
}
function onloadSet(w,h,frameName){
    document.getElementById(frameName).width  =216;
    document.getElementById(frameName).height =h;
    //document.getElementById(frameName).height =311;
    document.getElementById('pls_bet').style.display="none";
    if (frameName=="rec_frame"){
        if(tenrec_id!=""){
            document.getElementById('info_div').style.display='none';
            try {
                document.getElementById('bet_promos_div').style.display='none';
            }catch (e) { }
        }
    }
}

// 下注函数 isRuning 是否为
function betOrder(gtype,wtype,param,isRuning){
    if (wtype=="P3"||wtype=="PR"){
        //top.keepGold_PR="";
    }else{
        top.keepGold="";
        top.keepGold_PR="";
    }

    top.ioradio="";
    var url=parseUrl(gtype,wtype,param,isRuning);
    document.getElementById('order_button').className="ord_on";
    document.getElementById('record_button').className="record_btn";
    document.getElementById('pls_bet').style.display="none";
    document.getElementById('rec_frame').height=0;
    rec_frame.document.close();
    document.getElementById('rec5_div').style.display="none";
    document.getElementById('bet_div').style.display="block";

    bet_order_frame.location.replace(url);
    document.getElementById('info_div').style.display='none';
    document.getElementById('left-game-add').style.display='none';
    try {
        document.getElementById('bet_promos_div').style.display='none';
    }catch (e) { }
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

function close_bet(){
    document.getElementById('pls_bet').style.display="none";
    //document.getElementById('bet_div').style.display="block";
    document.getElementById('bet_order_frame').height =0;
    bet_order_frame.document.close();
    bet_order_frame.document.writeln("<html><head><link href=\"/style/member/mem_order_sel.css\" rel=\"stylesheet\" type=\"text/css\"></head><body  class='bet_info' style='background:#E3CFAA;margin:0'>");
    bet_order_frame.document.writeln(document.getElementById('pls_bet').innerHTML);
    bet_order_frame.document.writeln("</body></html>");
//  bet_order_frame.location.replace("");
    document.getElementById('bet_order_frame').height = bet_order_frame.document.body.scrollHeight ;
    document.getElementById('info_div').style.display='block'; // 显示公告
    document.getElementById('left-game-add').style.display='block'; // 显示右侧游戏
    try {
        document.getElementById('bet_promos_div').style.display='block';
    }catch (e) { }

    top.scripts=new Array();
    top.keepGold="";
    top.keepGold_PR="";
    try{
        parent.body.orderRemoveALL();
    }catch (E) {}
}

function show_record(){
    if (parent.show=='N'||(""+parent.show=="undefined")){
        parent.show='';
    }else{
        parent.show='N';
    }
    self.location = "./select.php?uid="+top.uid+"&langx="+top.langx+"&show="+parent.show;
}
function reload_var(){
    parent.refresh_var='Y';
    self.location.reload();
}

function showMoreMsg(){
    //parent.body.location='./scroll_history.php?uid='+top.uid+'&langx='+top.langx;
    window.open('./scroll_history.php?uid='+top.uid+'&langx='+top.langx,"History","width=617,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
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