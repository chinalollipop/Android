/*
2018新增开始 ,投注页面公用js 迁移到这里
* */

var mcurrency = 'RMB';
var keepGold="";
var keepGold_PR="";
var resetCheck="";
var autoOddCheck="";
var ioradio="";

// 判断金额是否为整数
function checkInputInt(val) {
    var ret = /^[1-9][0-9]*$/ ;
    return ret.test(val) ;
}

//千分位符号
function addComma(vals){
    var integer = "";
    var decimal = "";
    var tmpval = "";
    var pn = (vals<0)?"-":"";
    vals = ""+Math.abs(vals);
    if(vals.indexOf(".")>=0){
        var valarr = vals.split(".");
        integer = valarr[0];
        decimal = valarr[1];
        tmpval = valarr[0];
    }else{
        integer = vals;
        tmpval = vals;
    }
    for (ii=integer.length;ii>3;ii-=3){
        var comma_index = ii-3;
        var strA = tmpval.substring(0,comma_index);
        var strB = tmpval.substring(comma_index);
        tmpval = strA+","+strB;
    }
    if(vals.indexOf(".")>=0){
        tmpval += "."+decimal;
    }
    tmpval = pn+tmpval;
    return tmpval;
}
// 转成浮点型
function Mathfloor(z){
    var tmp_z;
    tmp_z=(Math.floor(z*100+0.01))/100;
    return tmp_z;
}
//小数点位数
function printf(vals, points) {
    vals = "" + vals;
    var cmd = new Array();
    cmd = vals.split(".");
    if (cmd.length > 1){
        for (ii=0; ii<(points-cmd[1].length); ii++) vals = vals + "0";
    }else{
        vals = vals + ".";
        for (ii=0; ii<points; ii++) vals = vals + "0";
    }
    return vals;
}

// 统一添加设置字体大小
function setFontAction() {
   //  var str ='<div id="skin" class="zoomChange">字体显示：<a id="skin_0" data-val="1" class="zoom zoomSmaller" href="javascript:;" title="点击切换原始字体">小</a>' +
   //      '<a id="skin_1" data-val="1.2" class="zoom zoomMed " href="javascript:;" title="点击切换中号字体">中</a>' +
   //      '<a id="skin_2" data-val="1.35" class="zoom zoomBigger" href="javascript:;" title="点击切换大号字体">大</a>' +
   //      '</div>';
   //  $('.top').append(str) ;
   // // 切换字体大小
   //  $('.top').on('click','.zoom',function () {
   //      var val = $(this).data('val') ;
   //      sessionStorage.setItem('font_size',val) ;
   //      parent.body_browse.location.reload(true);
   //  });
   //
   //  // 判断之前设定的字体大小
   //  var fontSzie =  sessionStorage.getItem('font_size');
   //  if(fontSzie ==''){
   //      fontSzie = '1' ; // 默认1
   //  }
   //  $('.top').find('.zoom').each(function () {
   //      var val = $(this).data('val').toString() ;
   //      if(val == fontSzie){
   //          $(this).addClass('selected') ;
   //      }
   //  }) ;
   //  $('.body_browse_set').css({'zoom':fontSzie,'-moz-transform':'scale('+fontSzie+')','-moz-transform-origin':' top left'});

    // 统一添加搜索赛事
    var seastr ='<div class="search_box">\n' +
        '<input type="text" id="seachtext" placeholder="输入关键字查询" class="select_btn">\n' +
        '<input type="button" id="btnSearch" value="搜索" class="seach_submit" onclick="seaGameList()">\n' +
        '</div>' ;
    $('#page_no').append(seastr) ;

}


// 原来右侧刷新按钮处理
/*window.onscroll = scroll;
function scroll() {
    var refresh_right= document.getElementById('refresh_right');
    refresh_right.style.top=document.body.scrollTop+39;
}*/

// 右方刷新按钮设定
function setRefreshPos(){
    var refresh_right= body_browse.document.getElementById('refresh_right');
    if(refresh_right){
        refresh_right.style.left= body_browse.document.getElementById('myTable').clientWidth*1+20;
    }

}

//  右侧投注金额快速选择公用
function setBetFastAction() {
    var str = ' <ul><li value="100">+100</li><li value="200">+200</li><li value="500">+500</li><li value="1000">+1,000</li><li value="5000">+5,000</li><li value="10000">+10,000</li></ul>' ;
    if(typeof $ != 'undefined'){ // 防止有些页面未引入jquery
        $('.betAmount').html(str) ;
    }

}

// 右侧投注区域点击数字加到input框
function fastBetAction() {
    var sum = 0;
    if(typeof $ != 'undefined'){ // 防止有些页面未引入jquery
        $(".betAmount").on("click",'li',function(){
            sum += $(this).val();
            $("#gold").val(sum);
            $("#gold").keyup();
        });

        /*清空input框*/
        $("#betClear").click(function(){
            sum = 0;
            $("input[name='gold']").val("").focus();
            $("#gold").keyup();
        });
    }


}

// 下注金额输赢计算
function CountWinGold(){
    var m_rate = document.all.ioradio_r_h.value ; // 当前赔率
    var b_mon = document.all.gold.value ; // 当前投注金额
    if(b_mon==''){
        document.all.gold.focus();
        document.all.pc.innerHTML="0";
        keepGold="";
    }else{
        keepGold=document.getElementById("gold").value;
        // var tmp_var=document.all.gold.value * document.all.ioradio_pd.value-document.all.gold.value; // 原来足球半球
        var tmp_var = b_mon * m_rate ;
        // console.log(Math.round(m_rate*100))
        if(Math.round(m_rate*100)< 100){ // 如果赔率小于等于0
            tmp_var = tmp_var;
        }/*else{ // 如果赔率大于0
            tmp_var=tmp_var-b_mon;
        }*/

        tmp_var=Math.round(tmp_var*100);
        tmp_var=tmp_var/100;
        tmp_var=addComma(tmp_var);
        tmp_var=printf(tmp_var,2);
        document.all.pc.innerHTML=tmp_var;
        count_win=true;
    }
}
// 下注金额输赢计算（独赢、单双、半场独赢），3个类的赔率单独处理
function CountWinGold_dy_ds_dyh(){
    var m_rate = document.all.ioradio_r_h.value ; // 当前赔率
    var b_mon = document.all.gold.value ; // 当前投注金额
    if(b_mon==''){
        document.all.gold.focus();
        document.all.pc.innerHTML="0";
        keepGold="";
    }else{
        keepGold=document.getElementById("gold").value;
        // var tmp_var=document.all.gold.value * document.all.ioradio_pd.value-document.all.gold.value; // 原来足球半球
        var tmp_var = b_mon * m_rate ;
        // console.log(Math.round(m_rate*100))
         // 如果赔率大于0
        tmp_var=tmp_var-b_mon;

        tmp_var=Math.round(tmp_var*100);
        tmp_var=tmp_var/100;
        tmp_var=addComma(tmp_var);
        tmp_var=printf(tmp_var,2);
        document.all.pc.innerHTML=tmp_var;
        count_win=true;
    }
}

function SubChk(){
    var Error = false;
    if(document.all.gold.value==''){
        document.all.gold.focus();
        alert(message001);
        Error =  true;

    }else if(!checkInputInt(document.all.gold.value)){
        document.all.gold.focus();
        alert(message002);
        Error =  true;
    }
    else if(isNaN(document.all.gold.value) == true){
        document.all.gold.focus();
        alert(message002);
        Error =  true;

    }
    else if(eval(document.all.gold.value*1) < (document.all.gmin_single.value.replace(",",'')*1)){
        document.all.gold.focus();
        alert(message003+" "+mcurrency+" "+document.all.gmin_single.value);
        Error =  true;

    }
    else if(eval(document.all.gold.value*1) > eval(document.all.gmax_single.value*1)){
        document.all.gold.focus();
        alert(message004+" "+mcurrency+" "+document.all.gmax_single.value);
        Error =  true;

    } else if (document.all.pay_type.value!='1'){ //不檢查現金顧客
        if(eval(document.all.gold.value*1) > eval(document.all.singleorder.value)){
            document.all.gold.focus();
            alert(message006+" "+mcurrency+" "+document.all.singleorder.value);
            Error =  true;

        }
        if((eval(document.all.restsinglecredit.value)+eval(document.all.gold.value*1)) > eval(document.all.singlecredit.value)){
            document.all.gold.focus();
            if (eval(document.all.restsinglecredit.value)==0){
                alert(message007);
            }else{
                alert(message008+document.all.restsinglecredit.value+message009);
            }
            Error =  true;

        }
    } else if(eval(document.all.gold.value*1) > eval(document.all.restcredit.value)){
        document.all.gold.focus();
        alert(message010);
        Error =  true;
    }
    if(Error){
        try{
            parent.live_order_height(document.body.scrollHeight);
        } catch (E) {}
        return false;
    }
    Open_div();
    return false;
}
function Open_div(){
    if (confirm(message011+document.all.pc.innerHTML+message016)){
        document.all.gold.blur();
        document.all.btnCancel.disabled = true;
        document.all.Submit.disabled = true;
        document.all.gold.readOnly=true;
        Sure_wager();
    }else{
        Close_div();
    }

}
function Close_div(){
    document.all['gWager'].style.display = "none";
    document.all.btnCancel.disabled = false;
    document.all.Submit.disabled = false;
    document.all.gold.readOnly=false;
    try{
        parent.live_order_height(document.body.scrollHeight);
    } catch (E) {}
    return false;
}
function Sure_wager(){
    document.all['gWager'].style.display = "none";
    document.forms[0].submit();
    parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
}

// 投主页右方滚动
function setRightScroll() {
    //右侧快捷菜单随滚动条滚动
    var $floatBox = $('.today_bet_floatright');
    var n = 1;
    var zoomChange =function(n) {
        $(window).on('scroll',function(){
            var scrollTop = $(window).scrollTop()/n;
            $floatBox.stop().animate({top:scrollTop+11});
        });
    }
    zoomChange(1);
}


// 前端js转换赔率
function js_change_rate(c_type,c_rate){
    var t_rate=0;
    switch(c_type){
        case 'A':
            t_rate=0.03;
            break;
        case 'B':
            t_rate=0.01;
            break;
        case 'C':
            t_rate=0;
            break;
        case 'D': // 本平台默认用户盘口为D，玩法显示与投注 方便赔率转换
            t_rate=-0.01;
            break;
    }
    var cr=0;
    if (c_rate!='' && c_rate!='0'){
       // console.log(c_rate) ;
        c_rate = (c_rate).toString().replace("<font color='#cc0000'>",'').replace("</font>",'') ; // 防止出现NAN
        cr = (parseFloat(c_rate) - t_rate).toFixed(2);
        if (cr<=0 && cr>=-0.03){
            cr='';
        }
    }else{
        cr='';
    }
    return cr;
}

/* 公用函数整理开始*/

/**
 * 選擇多盤口時 轉換成該選擇賠率
 * @param odd_type  選擇盤口
 * @param iorH      主賠率
 * @param iorC      客賠率
 * @param show      顯示位數
 * @return      回傳陣列 0-->H  ,1-->C
 */
function  get_other_ioratio(odd_type, iorH, iorC , showior){
    var out=new Array();
    if(iorH!="" ||iorC!=""){
        out =chg_ior(odd_type,iorH,iorC,showior);
    }else{
        out[0]=iorH;
        out[1]=iorC;
    }
    return out;
}


/**
 * 轉換賠率
 * @param odd_f
 * @param H_ratio
 * @param C_ratio
 * @param showior
 * @return
 */
function chg_ior(odd_f,iorH,iorC,showior){
    iorH = Math.floor((iorH*1000)+0.001) / 1000;
    iorC = Math.floor((iorC*1000)+0.001) / 1000;
    var ior=new Array();
   // if(iorH < 4) iorH *=1000; // 原来的
   // if(iorC < 4) iorC *=1000; // 原来的
    if(iorH < 11) iorH *=1000;
    if(iorC < 11) iorC *=1000;
    iorH=parseFloat(iorH);
    iorC=parseFloat(iorC);
    switch(odd_f){
        case "H":   //香港變盤(輸水盤)
            ior = get_HK_ior(iorH,iorC);
            break;
        case "M":   //馬來盤
            ior = get_MA_ior(iorH,iorC);
            break;
        case "I" :  //印尼盤
            ior = get_IND_ior(iorH,iorC);
            break;
        case "E":   //歐洲盤
            ior = get_EU_ior(iorH,iorC);
            break;
        default:    //香港盤
            ior[0]=iorH ;
            ior[1]=iorC ;
    }
    ior[0]/=1000;
    ior[1]/=1000;

    ior[0]=printf(Decimal_point(ior[0],showior),iorpoints);
    ior[1]=printf(Decimal_point(ior[1],showior),iorpoints);
    //alert("odd_f="+odd_f+",iorH="+iorH+",iorC="+iorC+",ouH="+ior[0]+",ouC="+ior[1]);
    return ior;
}

/**
 * 換算成輸水盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_HK_ior( H_ratio, C_ratio){
    var out_ior=new Array();
    var line,lowRatio,nowRatio,highRatio;
    var nowType="";
    if (H_ratio <= 1000 && C_ratio <= 1000){
        out_ior[0]=H_ratio;
        out_ior[1]=C_ratio;
        return out_ior;
    }
    line=2000 - ( H_ratio + C_ratio );
    if (H_ratio > C_ratio){
        lowRatio=C_ratio;
        nowType = "C";
    }else{
        lowRatio = H_ratio;
        nowType = "H";
    }
    if (((2000 - line) - lowRatio) > 1000){
        //對盤馬來盤
        nowRatio = (lowRatio + line) * (-1);
    }else{
        //對盤香港盤
        nowRatio=(2000 - line) - lowRatio;
    }
    if (nowRatio < 0){
        highRatio = Math.floor(Math.abs(1000 / nowRatio) * 1000) ;
    }else{
        highRatio = (2000 - line - nowRatio) ;
    }
    if (nowType == "H"){
        out_ior[0]=lowRatio;
        out_ior[1]=highRatio;
    }else{
        out_ior[0]=highRatio;
        out_ior[1]=lowRatio;
    }
    return out_ior;
}
/**
 * 換算成馬來盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_MA_ior( H_ratio, C_ratio){
    var out_ior=new Array();
    var line,lowRatio,highRatio;
    var nowType="";
    if ((H_ratio <= 1000 && C_ratio <= 1000)){
        out_ior[0]=H_ratio;
        out_ior[1]=C_ratio;
        return out_ior;
    }
    line=2000 - ( H_ratio + C_ratio );
    if (H_ratio > C_ratio){
        lowRatio = C_ratio;
        nowType = "C";
    }else{
        lowRatio = H_ratio;
        nowType = "H";
    }
    highRatio = (lowRatio + line) * (-1);
    if (nowType == "H"){
        out_ior[0]=lowRatio;
        out_ior[1]=highRatio;
    }else{
        out_ior[0]=highRatio;
        out_ior[1]=lowRatio;
    }
    return out_ior;
}
/**
 * 換算成印尼盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_IND_ior( H_ratio, C_ratio){
    var out_ior=new Array();
    out_ior = get_HK_ior(H_ratio,C_ratio);
    H_ratio=out_ior[0];
    C_ratio=out_ior[1];
    H_ratio /= 1000;
    C_ratio /= 1000;
    if(H_ratio < 1){
        H_ratio=(-1) / H_ratio;
    }
    if(C_ratio < 1){
        C_ratio=(-1) / C_ratio;
    }
    out_ior[0]=H_ratio*1000;
    out_ior[1]=C_ratio*1000;
    return out_ior;
}
/**
 * 換算成歐洲盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_EU_ior(H_ratio, C_ratio){
    var out_ior=new Array();
    out_ior = get_HK_ior(H_ratio,C_ratio);
    H_ratio=out_ior[0];
    C_ratio=out_ior[1];
    out_ior[0]=H_ratio+1000;
    out_ior[1]=C_ratio+1000;
    return out_ior;
}

/*
去正負號做小數第幾位捨去
進來的值是小數值
*/
function Decimal_point(tmpior,show){
    var sign="";
    sign =((tmpior < 0)?"Y":"N");
    tmpior = (Math.floor(Math.abs(tmpior) * show + 1 / show )) / show;
    return (tmpior * ((sign =="Y")? -1:1)) ;
}

function formatNumber(num, b, add){
    //console.trace();
    var point = b;
    var t=1;
    for(;b>0;t*=10,b--);
    var n = (b==0)?0:(1/t); //極小數 處理溢位問題
    if(num*1 >= 0){
        if(add) return addZero(Math.round((num*t)+n)/t,point);
        else 	return Math.round((num*t)+n)/t;
    }else{
        if(add) return addZero(Math.round((num*t)-n)/t,point);
        else 	return Math.round((num*t)+n)/t;
    }
}
/*
CRM-230 單盤（without spread）玩法賠率的四捨五入邏輯 (會員端)
*/

//轉換賠率格式
function addZero(code,b){
    code+="";
    var str = "";
    var index = code.indexOf(".");

    if(index==-1){
        code+=".";
        index=code.length-1;
    }
    var r = b*1 - (code.length-index-1);
    for(var i=0; i<r; i++){
        str += "0";
    }
    str = code + str;
    return str;
}

function in_array(val,ary){
    for(var k=0;k<=ary.length;k++){
        if(val==ary[k])return true;
    }
    return false;
}

/* 公用函数整理结束*/
