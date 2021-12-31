
/* 2018 新版  开始*/
var showResultObj = new Object();
function setGameType(type) {
    var gametype=[
        {type:'FT',name:'足球'},
        {type:'BK',name:'篮球 / 美式足球'},
        {type:'TN',name:'网球'},
        {type:'VB',name:'排球'},
        {type:'BM',name:'羽毛球'},
        {type:'TT',name:'乒乓球'},
        {type:'BS',name:'棒球'},
        {type:'SK',name:'斯诺克/台球'},
        {type:'OP',name:'其他'},
    ] ;
    var str='' ;
    for(var i=0;i<gametype.length;i++){
        if(type==gametype[i].type){
            str +=' <li id="sel_gtype" onclick="showOption(\'gtype\');" class="acc_selectMS_first">'+gametype[i].name+'</li>\n';
        }
    }
    str += '\t<ul id="chose_gtype" class="acc_selectMS_options" style="display: none;">\n';
    for(var i=0;i<gametype.length;i++){
        if(type==gametype[i].type){
            str +='<li data-value="'+gametype[i].type+'" class="On">'+gametype[i].name+'</li>';
        }else{
            str +='<li data-value="'+gametype[i].type+'" class="acc_select" onclick="changeGameType(this)">'+gametype[i].name+'</li>';
        }
    }
    str += '</ul>' ;
    $('#type_acc_selectMS').html(str) ;
}


var gid ='';


function init(){

    var sel_type = document.getElementById("sel_type");
    var obj_type = document.getElementById(chg_type);
    obj_type.className = "On";
    sel_type.innerHTML = obj_type.innerHTML;
    // setClick("gtype");
    setClick("type");

    // document.body.onclick=function(evt){getTarget((evt) ? evt : window.event);}
    var langx = langx;
    var _set = {};
    if(langx == "zh-tw" ||langx == "zh-cn"){
        _set.monthName = ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"]; // 月份語系;
        _set.weekName = ["日","一","二","三","四","五","六"]; // 星期語系;
    }
    if(langx == "en-us"){
        _set.monthName = ["January","February","March","April","May","June","July","August","September","October","November","December"]; // 月份語系;
        _set.weekName = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]; // 星期語系;
    }
    _set.futureYear = 20; // 未來年份數;
    _set.cssShow = false;
    _set.maxDate=max_day;
    var tmpScreen = document.getElementById("div_state");


    for(var key in showResultObj){
        document.getElementById(key).style.display = showResultObj[key];
    }
    if(lasttr == "Y"){
        document.getElementById("results_tableLine").className = "acc_results_tableBL";
    }
}

function setClick(type){
    var typeObj = document.getElementById("chose_"+type);
    for(var i=0; i<typeObj.children.length; i++){
        var obj = typeObj.children[i];
        setClickEvent(obj,type);
    }
}

function setClickEvent(obj,type){
    obj.onclick=function(){
        chgChose(obj,type);
    };
}

function chgChose(obj, type){
    var _value = obj.getAttribute("value");
    chg_gtype(_value);

}


function showOption(_type){
    var _otherType = (_type == "gtype")?"type":"gtype";
    var _status = document.getElementById("chose_"+_type).style.display;
    var _newStatus = (_status=="")?"none":"";
    document.getElementById("chose_"+_type).style.display = _newStatus;
    if(_newStatus == ""){
        document.getElementById("chose_"+_otherType).style.display = "none";
    }
}

function chg_gtype(tmpValue){
    var strUrl ="";
    if(tmpValue=="FS" || (chg_type == "Outright" && tmpValue != "")){
        strUrl ="/app/member/result/result_fs.php";
    }else{
        strUrl ="/app/member/result/result.php";
    }
    if(tmpValue=="FS" || tmpValue=="")tmpValue = game_type;
    self.location.href=strUrl+"?game_type="+tmpValue+"&today_day="+game_date;
}

function refreshReload(level){
    reload_var(level);
}

function reload_var(Level){
    location.reload();
}

function showDiv(divname, isShow){
    var obj = document.getElementById("chose_"+divname);
    obj.style.display = (isShow)?"":"none";

}

// 右侧公用选择类型
function showOption(_type){
    var _otherType = (_type == "gtype")?"type":"gtype";
    var _status = document.getElementById("chose_"+_type).style.display;
    var _newStatus = (_status=="")?"none":"";
   document.getElementById("chose_"+_type).style.display = _newStatus;
    if(_newStatus == ""){
        try {
            document.getElementById("chose_"+_otherType).style.display = "none";
        }catch (e){}

    }
}

// 右侧公用选择游戏类型
function changeGameType(obj) {
    $('[name="game_type"]').val($(obj).data('value'));
    $('#sel_gtype').text($(obj).text());
    $('#chose_gtype').hide() ;
   $('.seach_btn').click() ;
}

// 选择日期
function showDate(obj){
    $('[name="today_day"]').val($(obj).html());
    $('.seach_btn').click() ;
}

/* 2018 新版  结束*/