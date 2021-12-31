<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");     
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$rtype=ltrim(strtolower($_REQUEST['rtype']));
$league_id=$_REQUEST['league_id'];
require ("../include/traditional.$langx.inc.php");

if ($rtype==""){
	$rtype="r";
}
?>
<script>
var show_ior = '100';
</script>
<html>
<head>
<title>下注分割畫面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
    <script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
    <script>
var keepGameData=new Array();
var gidData=new Array();
parent.gamecount=0;
//判斷賠率是否變動
//包td

function checkRatio(rec,index){
 //alert(flash_ior_set);
    //return true;
    if (flash_ior_set =='Y'){

        if (""+keepGameData[rec]=="undefined"||keepGameData[rec]==""){
            keepGameData[rec]=new Array();
            keepGameData[rec][index]=GameFT[rec][index];
        }
        //判斷gid是否相同
        if (gidData[rec]!=GameFT[rec][0]||""+GameFT[rec][0]=="undefined"){
            keepGameData[rec]=new Array();
            gidData[rec]=new Array();
            keepGameData[rec][index]=GameFT[rec][index];
            gidData[rec][0]=GameFT[rec][0];
        }

        if (""+keepGameData[rec][index]=="undefined" ||keepGameData[rec][index]==""){
            keepGameData[rec][index]=GameFT[rec][index];
        }
        //alert("aaa==>"+keepGameData[rec][index]+"bbb==>"+GameFT[rec][index]);
        if (keepGameData[rec][index]!=GameFT[rec][index]&& keepGameData[rec][index] !=""&&GameFT[rec][index]!=""){
            //keepGameData[rec][index]=GameFT[rec][index];
            keepGameData[rec][index] = "";
            //keepGameData[rec]="";
            return " bgcolor=yellow ";
        }
        return true;
    }
}
//包font
function checkRatio_font(rec,index){
//alert(flash_ior_set);
    //return true;
    //alert(GameFT.length+"----"+keepGameData.length)

    if (flash_ior_set =='Y'){
        if (""+keepGameData[rec]=="undefined"||keepGameData[rec]==""){
            keepGameData[rec]=new Array();
            keepGameData[rec][index]=GameFT[rec][index];
        }
        //判斷gid是否相同
        if (gidData[rec]!=GameFT[rec][0]||""+GameFT[rec][0]=="undefined"){
            keepGameData[rec]=new Array();
            gidData[rec]=new Array();
            keepGameData[rec][index]=GameFT[rec][index];
            gidData[rec][0]=GameFT[rec][0];
        }
        if (""+keepGameData[rec][index]=="undefined"||keepGameData[rec][index] ==""){
            keepGameData[rec][index]=GameFT[rec][index];
        }

        //alert("ccc==>"+keepGameData[rec][index]+"ddd==>"+GameFT[rec][index]);
        if (keepGameData[rec][index]!=GameFT[rec][index] && keepGameData[rec][index] !=""&&GameFT[rec][index]!="") {
            //keepGameData[rec][index]=GameFT[rec][index];
            keepGameData[rec][index] = "";
            //keepGameData[rec]="";
            return '  style=\"background-color : #dc9602\" ';
        }
        return true;
    }
}
function gethighlight(){
    return " style=\"color:red\" style=\"font-weight:bolder\" ";
}
//滑鼠移動帶出索引
//function showMsg(msg, type) {
//  var showHelpMsg = body_browse.document.getElementById("showHelpMsg");
////    var showHelpMsg = parent.body_browse.document.getElementById('showHelpMsg');
//  var helpMsg = body_browse.document.getElementById('helpMsg').innerHTML;
//  var tmpHTML = "";
//  if(type == 1) {
//      tmpHTML = helpMsg;
//      tmpHTML = tmpHTML.replace("*SHOWMSG*", msg);
//      showHelpMsg.innerHTML = tmpHTML;
//      showHelpMsg.style.display = "block";
//      showHelpMsg.style.top = body_browse.document.body.scrollTop+body_browse.event.clientY-10;
//      showHelpMsg.style.left = body_browse.document.body.scrollLeft+body_browse.event.clientX+10;
//  } else showHelpMsg.style.display = "none";
//}

//====== 加入現場轉播功能 2009-04-09
// 開啟轉播
function OpenLive(eventid, gtype){
    if (top.liveid == undefined) {
        parent.self.location = "";
        return;
    }
    window.open("../live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid+"&eventid="+eventid+"&gtype="+gtype,"Live","width=780,height=585,top=0,left=0,status=no,toolbar=no,scrollbars=no,resizable=no,personalbar=no");
}

function VideoFun(eventid, hot, play, gtype) {
    var tmpStr = "";
    if (play == "Y") {
        tmpStr+= "<img lowsrc=\"/images/member/video_1.gif\" onClick=\"parent.OpenLive('"+eventid+"','"+gtype+"')\" style=\"cursor:hand\">";
    } else {
        tmpStr+= "<img lowsrc=\"/images/member/video_2.gif\">";
    }
    return tmpStr;
}

function MM_ShowLoveI(gid,getDateTime,getLid,team_h,team_c){
    var txtout="";
    if(!top.swShowLoveI){
        //alert(chkRepeat(gid));
        if(!chkRepeat(gid)){    
            //txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><img id='"+MM_imgId(getDateTime,gid)+"' lowsrc=\"/images/member/icon_X2.gif\" vspace=\"0\" style=\"cursor:hand;display:none;\" title=\""+top.str_ShowMyFavorite+"\" onClick=\"addShowLoveI('"+gid+"','"+getDateTime+"','"+getLid+"','"+team_h+"','"+team_c+"'); \"></span>";
            txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><div id='"+MM_imgId(getDateTime,gid)+"' class=\"fov_icon_out\" style=\"cursor:hand;display:none;\" title=\""+top.str_ShowMyFavorite+"\" onClick=\"addShowLoveI('"+gid+"','"+getDateTime+"','"+getLid+"','"+team_h+"','"+team_c+"'); \"></div></span>";
        }else{
            //txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><img lowsrc=\"/images/member/love_small.gif\" style=\"cursor:hand\" title=\""+top.str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \"></span>";
            txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+top.str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \"></div></span>";
        }
    }else{
        //txtout = "<img lowsrc=\"/images/member/love_small.gif\" style=\"cursor:hand\" title=\""+top.str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \">";
        txtout = "<div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+top.str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \"></div>";
    }
    return txtout;
}

function chkRepeat(gid){
    var getGtype =getGtypeShowLoveI();
    var sw =false;
    for (var i=0 ; i < top.ShowLoveIarray[getGtype].length ; i++){
        if(top.ShowLoveIarray[getGtype][i][0]==gid)
            sw =true;
    }
    return sw;
}

function MM_IdentificationDisplay(time,gid){
    var getGtype = getGtypeShowLoveI();
    var txt_array = top.ShowLoveIOKarray[getGtype];
    if(top.swShowLoveI){
        var tmp = time.split("<br>")[0];
        if(txt_array.length==0)return true;
        if(txt_array.indexOf(tmp+gid +",",0)== -1)
            return true;
    }
}
function getGtypeShowLoveI(){
    var Gtype;
    var getGtype =sel_gtype;
    var getRtype =rtype;
    Gtype =getGtype;
    if(getRtype=="re"){
        Gtype +="RE";
    }
    /*
    if(getGtype =="FU"||getGtype=="FT"){
        Gtype ="FT";
    }else if(getGtype =="OM"||getGtype=="OP"){
        Gtype ="OP";
    }else if(getGtype =="BU"||getGtype=="BK"){
        Gtype ="BK";
    }else if(getGtype =="BSFU"||getGtype=="BS"){
        Gtype ="BS";
    }else if(getGtype =="VU"||getGtype=="VB"){
        Gtype ="VB";
    }else if(getGtype =="TU"||getGtype=="TN"){
        Gtype ="TN";
    }else {
        Gtype ="FT";
    }
    */
    
    //alert("in==>"+parent.sel_gtype+",out==>"+Gtype);
    return Gtype;
}
function MM_imgId(time,gid){    
    var tmp = time.split("<br>")[0];
    //alert(tmp+gid);
    return tmp+gid;
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<?php
switch($rtype){
case 'r':
?>
<script>
var ObjDataFT=new Array();
var oldObjDataFT=new Array();
var keepleg="";
var legnum=0;
var NoshowLeg=new Array();
var myLeg=new Array();
var LeagueAry=new Array();

function ShowGameList(){
    if(loading == 'Y') return;
    if (parent.gamecount!=gamount){
        oldObjDataFT=new Array();
    }
    if(top.odd_f_type==""||""+top.odd_f_type=="undefined") top.odd_f_type="H";
    keepscroll=body_browse.document.body.scrollTop;

    var conscroll= body_browse.document.getElementById('controlscroll');
    conscroll.style.display="";
    conscroll.style.top=keepscroll+1;
    //conscroll.focus();

    dis_ShowLoveI();

    //秀盤面
    showtables(GameFT,GameHead,gamount,top.odd_f_type);

    body_browse.scroll(0,keepscroll);

    //設定右方重新整理位置
    setRefreshPos();

    //顯示盤口
    body_browse.ChkOddfDiv();

    parent.gamecount=gamount;

    if(top.showtype=='hgft'||top.showtype=='hgfu'){
        obj_sel = body_browse.document.getElementById('sel_league');
        obj_sel.style.display='none';

    }else{
        show_page();
    }

    conscroll.style.display="none";
    coun_Leagues();
    body_browse.showPicLove();
    loadingOK();
}


//表格函數
function showtables(GameData,Game_Head,data_amount,odd_f_type){
    ObjDataFT=new Array();
    myLeg=new Array();
    for (var j=0;j < data_amount;j++){
        if (GameData[j]!=null){
            ObjDataFT[j]=parseArray(Game_Head,GameData[j]);
        }
    }
    var trdata;//=body_browse.document.getElementById('DataTR').innerHTML;
    var showtableData;
    if(body_browse.document.all){
            showtableData=body_browse.document.getElementById('showtableData').innerText ;
            trdata=body_browse.document.getElementById('DataTR').innerText;
            notrdata=body_browse.document.getElementById('NoDataTR').innerText;
    } else{
            showtableData=body_browse.document.getElementById('showtableData').textContent ;
            trdata=body_browse.document.getElementById('DataTR').textContent;
            notrdata=body_browse.document.getElementById('NoDataTR').textContent;
    }
    var showtable=body_browse.document.getElementById('showtable');
    var showlayers="";
    keepleg="";
    legnum=0;
    LeagueAry =new Array();
    var chk_Love_I=new Array();
    if(ObjDataFT.length > 0){
        for ( i=0 ;i < ObjDataFT.length;i++){
            tmp_Str=getLayer(trdata,i,odd_f_type);
            showlayers+=tmp_Str;
            if (top.swShowLoveI&&tmp_Str!=""){
                chk_Love_I.push(ObjDataFT[i]);  
            }
        }
        if (top.swShowLoveI){
            body_browse.checkLoveCount(chk_Love_I); 
        }
        if(showlayers=="")showlayers=notrdata;
        showtableData=showtableData.replace("*showDataTR*",showlayers);
    }else{
        showtableData=showtableData.replace("*showDataTR*",notrdata);
    }
    
    showtable.innerHTML=showtableData;

}

//表格內容
function getLayer(onelayer,gamerec,odd_f_type){
    var open_hot = false;
    if(MM_IdentificationDisplay(ObjDataFT[gamerec].datetime,ObjDataFT[gamerec].gnum_h)) return "";
    if (!top.swShowLoveI){
        if(("|"+eval('parent.'+sel_gtype+'_lname_ary')).indexOf(("|"+ObjDataFT[gamerec].league+"|"),0)==-1&&eval('parent.'+sel_gtype+'_lname_ary')!='ALL') return "";
        if((""+LeagueAry).indexOf(ObjDataFT[gamerec].league)== -1)LeagueAry.push(ObjDataFT[gamerec].league);
    
    }
    var tmp_date = ObjDataFT[gamerec].datetime.split("<br>")[0];
    onelayer=onelayer.replace(/\*ID_STR\*/g,tmp_date+ObjDataFT[gamerec].gnum_h);
    onelayer=onelayer.replace(/\*TR_EVENT\*/g,"onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);'");

    if (""+myLeg[ObjDataFT[gamerec].league]=="undefined"){
        myLeg[ObjDataFT[gamerec].league]=ObjDataFT[gamerec].league;
        myLeg[ObjDataFT[gamerec].league]=new Array();
        myLeg[ObjDataFT[gamerec].league][0]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }else{
        myLeg[ObjDataFT[gamerec].league][myLeg[ObjDataFT[gamerec].league].length]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }

    //--------------判斷聯盟名稱列顯示或隱藏----------------
    if (ObjDataFT[gamerec].league==keepleg){
            onelayer=onelayer.replace("*ST*"," style='display: none;'");
    }else{
            onelayer=onelayer.replace("*ST*"," style='display: ;'");
    }
    //---------------------------------------------------------------------
    //--------------判斷聯盟底下的賽事顯示或隱藏----------------
    if (NoshowLeg[ObjDataFT[gamerec].league]==-1){
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: none;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegClose'></span>"); //聯盟的小圖
    }else{
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: ;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegOpen'></span>");  //聯盟的小圖
    }
    //---------------------------------------------------------------------
    //盤口賠率 start
    var R_ior =Array();
    var OU_ior =Array();

    
    //R_ior  = get_other_ioratio(odd_f_type, ObjDataFT[gamerec].ior_RH   , ObjDataFT[gamerec].ior_RC   , show_ior);
    //OU_ior = get_other_ioratio(odd_f_type, ObjDataFT[gamerec].ior_OUH  , ObjDataFT[gamerec].ior_OUC  , show_ior); // 全场大小

    
    // ObjDataFT[gamerec].ior_RH=R_ior[0];
    // ObjDataFT[gamerec].ior_RC=R_ior[1];
    //ObjDataFT[gamerec].ior_OUH=OU_ior[0];
    //ObjDataFT[gamerec].ior_OUC=OU_ior[1];
    //盤口賠率 end
    //滾球字眼
    ObjDataFT[gamerec].datetime=ObjDataFT[gamerec].datetime.replace("Running Ball",top.str_RB);
    keepleg=ObjDataFT[gamerec].league;
    onelayer=onelayer.replace(/\*LEG\*/gi,ObjDataFT[gamerec].league);

    var tmp_date=ObjDataFT[gamerec].datetime.split("<br>");
    if (sel_gtype=="VU"){
        tmp_date_str=tmp_date[0]+"<br>"+change_time(tmp_date[1]);
    }else{
        tmp_date_str=change_time(tmp_date[1]);
    }
    if (tmp_date.length==3){
        tmp_date_str+="<br>"+tmp_date[2];
    }   
    onelayer=onelayer.replace("*DATETIME*",ObjDataFT[gamerec].datetime);//tmp_date_str
    onelayer=onelayer.replace("*TEAM_H*",ObjDataFT[gamerec].team_h.replace("[Mid]","<font color=\"#005aff\">[N]</font>").replace("[中]","<font color=\"#005aff\">[中]</font>"));
    onelayer=onelayer.replace("*TEAM_C*",ObjDataFT[gamerec].team_c);
    //全場
    //獨贏
    onelayer=onelayer.replace("*RATIO_MH*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"M"));
    onelayer=onelayer.replace("*RATIO_MC*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"M"));
    onelayer=onelayer.replace("*RATIO_MN*",parseUrl(uid,odd_f_type,"N",ObjDataFT[gamerec],gamerec,"M"));
    //讓球
    if (ObjDataFT[gamerec].strong=="H"){
        onelayer=onelayer.replace("*CON_RH*",ObjDataFT[gamerec].ratio); /*讓球球頭*/
        onelayer=onelayer.replace("*CON_RC*","");
    }else{
        onelayer=onelayer.replace("*CON_RH*","");
        onelayer=onelayer.replace("*CON_RC*",ObjDataFT[gamerec].ratio);
    }

    onelayer=onelayer.replace("*RATIO_RH*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"R"));/*讓球賠率*/
    onelayer=onelayer.replace("*RATIO_RC*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"R"));
    //大小
    if (top.langx=="en-us"){
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_o.replace("O","<b>"+"o"+"</b>"));    /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_u.replace("U","<b>"+"u"+"</b>"));
    }else{
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_o.replace("O",top.strOver)); /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_u.replace("U",top.strUnder));
    }
    onelayer=onelayer.replace("*RATIO_OUH*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"OU"));/*大小賠率*/
    onelayer=onelayer.replace("*RATIO_OUC*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"OU"));
    //單雙
    if (top.langx=="en-us"){
    onelayer=onelayer.replace("*RATIO_EOO*","<span class=\"con_oe\">"+"<b>"+ObjDataFT[gamerec].str_odd+"</b>"+"&nbsp</span>"+parseUrl(uid,odd_f_type,"O",ObjDataFT[gamerec],gamerec,"EO"));
    onelayer=onelayer.replace("*RATIO_EOE*","<span class=\"con_oe\">"+"<b>"+ObjDataFT[gamerec].str_even+"</b>"+"&nbsp</span>"+parseUrl(uid,odd_f_type,"E",ObjDataFT[gamerec],gamerec,"EO"));
    }else{
    onelayer=onelayer.replace("*RATIO_EOO*","<span class=\"con_oe\">"+ObjDataFT[gamerec].str_odd+"&nbsp</span>"+parseUrl(uid,odd_f_type,"O",ObjDataFT[gamerec],gamerec,"EO"));
    onelayer=onelayer.replace("*RATIO_EOE*","<span class=\"con_oe\">"+ObjDataFT[gamerec].str_even+"&nbsp</span>"+parseUrl(uid,odd_f_type,"E",ObjDataFT[gamerec],gamerec,"EO")); 
        }
    //我的最愛
    onelayer=onelayer.replace("*MYLOVE*",parseMyLove(ObjDataFT[gamerec]));
    if (ObjDataFT[gamerec].eventid != "" && ObjDataFT[gamerec].eventid != "null" && ObjDataFT[gamerec].eventid != undefined) {  //判斷是否有轉播
        tmpStr= VideoFun(ObjDataFT[gamerec].eventid, ObjDataFT[gamerec].hot, ObjDataFT[gamerec].play, "BK");
        onelayer=onelayer.replace("*TV*",tmpStr);
    }
    onelayer=onelayer.replace("*TV*","");

    return onelayer;
}

//取得下注的url
function parseUrl(uid,odd_f_type,betTeam,GameData,gamerec,wtype){
    var urlArray=new Array();
    urlArray['R']=new Array("../VB_order/VB_order_r.php",eval("GameData.team_"+betTeam.toLowerCase()));
    urlArray['OU']=new Array("../VB_order/VB_order_ou.php",(betTeam=="C" ? top.strOver : top.strUnder));
    urlArray['M']=new Array("../VB_order/VB_order_m.php",(betTeam=="N" ? top.str_irish_kiss : eval("GameData.team_"+betTeam.toLowerCase())));
    urlArray['EO']=new Array("../VB_order/VB_order_t.php", (betTeam=="O"  ? top.str_o : top.str_e));

    var param=getParam(uid,odd_f_type,betTeam,wtype,GameData);
    var order=urlArray[wtype][0];
    var team=urlArray[wtype][1].replace("[Mid]","[N]");
    var tmp_rtype="ior_"+wtype+betTeam;
    var ioratio_str="GameData."+tmp_rtype;

    var ioratio=eval(ioratio_str);

    if(ioratio!=""){
    ioratio=Mathfloor(ioratio);
    ioratio=printf(ioratio,iorpoints);
   }
    var ret="<a href='javascript://' onclick=\"parent.parent.mem_order.betOrder('VB','"+wtype+"','"+param+"');\" title='"+team+"'><font "+check_ioratio(gamerec,tmp_rtype,GameData)+">"+ioratio+"</font></a>";

    return ret;
}

//--------------------------public function --------------------------------

//取得下注參數
function getParam(uid,odd_f_type,betTeam,wtype,GameData){
    var paramArray=new Array();
    paramArray['R']=new Array("gid","uid","odd_f_type","type","gnum","strong","langx");
    paramArray['OU']=new Array("gid","uid","odd_f_type","type","gnum","langx");
    paramArray['M']=new Array("gid","uid","odd_f_type","type","gnum","langx");
    paramArray['EO']=new Array("gid","uid","odd_f_type","rtype","langx");

    var param="";
    var gid=((wtype=="R"||wtype=="OU"||wtype=="M"||wtype=="EO") ? GameData.gid : GameData.hgid);
    var gnum=eval("GameData.gnum_"+(betTeam=="N"? "c":betTeam.toLowerCase()));
    var strong=(wtype=="R" ? GameData.strong : GameData.hstrong);
    var rtype=(betTeam=="O" ? "ODD" : "EVEN");
    var type=betTeam;

    for (var i=0;i<paramArray[wtype].length;i++){
        if (i>0)  param+="&";
        param+=paramArray[wtype][i]+"="+eval(paramArray[wtype][i]);
    }
    return param;
}

function parseMyLove(GameData){
    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='100%'   border='0' cellpadding='0' cellspacing='0'><tr>";//<td align='left'>"+str_even+"</td>";
    tmpStr+= "<td class='hot_td' >";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}
</script>
<?php
break;
case 're':
?>
<script>
var ObjDataFT=new Array();
var oldObjDataFT=new Array();
var keepleg="";
var legnum=0;
var NoshowLeg=new Array();
var myLeg=new Array();
var LeagueAry=new Array();

function ShowGameList(){
    if(loading == 'Y') return;
    if (parent.gamecount!=gamount){
        oldObjDataFT=new Array();
    }
    if(top.odd_f_type==""||""+top.odd_f_type=="undefined") top.odd_f_type="H";
    keepscroll=body_browse.document.body.scrollTop;

    var conscroll= body_browse.document.getElementById('controlscroll');
    conscroll.style.display="";
    conscroll.style.top=keepscroll+1;
    //conscroll.focus();

    dis_ShowLoveI();

    //秀盤面
    showtables(GameFT,GameHead,gamount,top.odd_f_type);

    body_browse.scroll(0,keepscroll);

    //設定右方重新整理位置
    setRefreshPos();

    //顯示盤口
    body_browse.ChkOddfDiv();

    parent.gamecount=gamount;

    if(top.showtype=='hgft'||top.showtype=='hgfu'){
        obj_sel = body_browse.document.getElementById('sel_league');
        obj_sel.style.display='none';

    }else{
        show_page();
    }

    conscroll.style.display="none";
    coun_Leagues();
    body_browse.showPicLove();
    loadingOK();
}


//表格函數
function showtables(GameData,Game_Head,data_amount,odd_f_type){
    ObjDataFT=new Array();
    myLeg=new Array();
    for (var j=0;j < data_amount;j++){
        if (GameData[j]!=null){
            ObjDataFT[j]=parseArray(Game_Head,GameData[j]);
        }
    }
    var trdata;//=body_browse.document.getElementById('DataTR').innerHTML;
    var showtableData;
    if(body_browse.document.all){
            showtableData=body_browse.document.getElementById('showtableData').innerText ;
            trdata=body_browse.document.getElementById('DataTR').innerText;
            notrdata=body_browse.document.getElementById('NoDataTR').innerText;
    } else{
            showtableData=body_browse.document.getElementById('showtableData').textContent ;
            trdata=body_browse.document.getElementById('DataTR').textContent;
            notrdata=body_browse.document.getElementById('NoDataTR').textContent;
    }
    var showtable=body_browse.document.getElementById('showtable');
    var showlayers="";
    keepleg="";
    legnum=0;
    LeagueAry =new Array();
    var chk_Love_I=new Array();
    if(ObjDataFT.length > 0){
        for ( i=0 ;i < ObjDataFT.length;i++){
            tmp_Str=getLayer(trdata,i,odd_f_type);
            showlayers+=tmp_Str;
            if (top.swShowLoveI&&tmp_Str!=""){
                chk_Love_I.push(ObjDataFT[i]);  
            }
        }
        if (top.swShowLoveI){
            body_browse.checkLoveCount(chk_Love_I); 
        }
        if(showlayers=="")showlayers=notrdata;
        showtableData=showtableData.replace("*showDataTR*",showlayers);
    }else{
        showtableData=showtableData.replace("*showDataTR*",notrdata);
    }
    
    showtable.innerHTML=showtableData;

}

//表格內容
function getLayer(onelayer,gamerec,odd_f_type){
    var open_hot = false;
    if(MM_IdentificationDisplay(ObjDataFT[gamerec].datetime,ObjDataFT[gamerec].gnum_h)) return "";
    if (!top.swShowLoveI){
        if(("|"+eval('parent.'+sel_gtype+'_lname_ary_RE')).indexOf(("|"+ObjDataFT[gamerec].league+"|"),0)==-1&&eval('parent.'+sel_gtype+'_lname_ary_RE')!='ALL') return "";
        if((""+LeagueAry).indexOf(ObjDataFT[gamerec].league)== -1)LeagueAry.push(ObjDataFT[gamerec].league);
    }
    var tmp_date = ObjDataFT[gamerec].datetime.split("<br>")[0];
    onelayer=onelayer.replace(/\*ID_STR\*/g,tmp_date+ObjDataFT[gamerec].gnum_h);
    onelayer=onelayer.replace(/\*TR_EVENT\*/g,"onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);'");

    if (""+myLeg[ObjDataFT[gamerec].league]=="undefined"){
        myLeg[ObjDataFT[gamerec].league]=ObjDataFT[gamerec].league;
        myLeg[ObjDataFT[gamerec].league]=new Array();
        myLeg[ObjDataFT[gamerec].league][0]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }else{
        myLeg[ObjDataFT[gamerec].league][myLeg[ObjDataFT[gamerec].league].length]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }

    //--------------判斷聯盟名稱列顯示或隱藏----------------
    if (ObjDataFT[gamerec].league==keepleg){
            onelayer=onelayer.replace("*ST*"," style='display: none;'");
    }else{
            onelayer=onelayer.replace("*ST*"," style='display: ;'");
    }
    //---------------------------------------------------------------------
    //--------------判斷聯盟底下的賽事顯示或隱藏----------------
    if (NoshowLeg[ObjDataFT[gamerec].league]==-1){
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: none;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegClose'></span>"); //聯盟的小圖
    }else{
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: ;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegOpen'></span>");  //聯盟的小圖
    }
    //---------------------------------------------------------------------
    //盤口賠率 start
    var R_ior =Array();
    var OU_ior =Array();

    
   // R_ior  = get_other_ioratio(odd_f_type, ObjDataFT[gamerec].ior_RH   , ObjDataFT[gamerec].ior_RC   , show_ior);
    //OU_ior = get_other_ioratio(odd_f_type, ObjDataFT[gamerec].ior_OUH  , ObjDataFT[gamerec].ior_OUC  , show_ior); // 全场大小

    
    // ObjDataFT[gamerec].ior_RH=R_ior[0];
    // ObjDataFT[gamerec].ior_RC=R_ior[1];
    //ObjDataFT[gamerec].ior_OUH=OU_ior[0];
    //ObjDataFT[gamerec].ior_OUC=OU_ior[1];
    //盤口賠率 end
    //滾球字眼
    ObjDataFT[gamerec].datetime=ObjDataFT[gamerec].datetime.replace("Running Ball","");
    ObjDataFT[gamerec].timer=ObjDataFT[gamerec].timer.replace("<font style=background-color=red>","").replace("</font>","");
    keepleg=ObjDataFT[gamerec].league;
    onelayer=onelayer.replace(/\*LEG\*/gi,ObjDataFT[gamerec].league);

    onelayer=onelayer.replace("*DATETIME*",change_time(ObjDataFT[gamerec].timer));
    //onelayer=onelayer.replace("*SCORE*",ObjDataFT[gamerec].score_h+"&nbsp;-&nbsp;"+ObjDataFT[gamerec].score_c);
    onelayer=onelayer.replace("*SCORE*","&nbsp");
    onelayer=onelayer.replace("*TEAM_H*",ObjDataFT[gamerec].team_h.replace("[Mid]","<font color=\"#005aff\">[N]</font>").replace("[中]","<font color=\"#005aff\">[中]</font>"));
    onelayer=onelayer.replace("*TEAM_C*",ObjDataFT[gamerec].team_c);
    onelayer=onelayer.replace("*SE*",top.str_RB);
    //全場
    //讓球
    if (ObjDataFT[gamerec].strong=="H"){
        onelayer=onelayer.replace("*CON_RH*",ObjDataFT[gamerec].ratio); /*讓球球頭*/
        onelayer=onelayer.replace("*CON_RC*","");
    }else{
        onelayer=onelayer.replace("*CON_RH*","");
        onelayer=onelayer.replace("*CON_RC*",ObjDataFT[gamerec].ratio);
    }

    onelayer=onelayer.replace("*RATIO_RH*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"R"));/*讓球賠率*/
    onelayer=onelayer.replace("*RATIO_RC*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"R"));
    //大小
    if (top.langx=="en-us"){
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_o.replace("O","<b>"+"o"+"</b>"));    /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_u.replace("U","<b>"+"u"+"</b>"));
    }else{
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_o.replace("O",top.strOver)); /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_u.replace("U",top.strUnder));
    }
    onelayer=onelayer.replace("*RATIO_OUH*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"OU"));/*大小賠率*/
    onelayer=onelayer.replace("*RATIO_OUC*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"OU"));
    //我的最愛
    onelayer=onelayer.replace("*MYLOVE*",parseMyLove(ObjDataFT[gamerec]));
    if (ObjDataFT[gamerec].eventid != "" && ObjDataFT[gamerec].eventid != "null" && ObjDataFT[gamerec].eventid != undefined) {  //判斷是否有轉播
        tmpStr= VideoFun(ObjDataFT[gamerec].eventid, ObjDataFT[gamerec].hot, ObjDataFT[gamerec].play, "BK");
        onelayer=onelayer.replace("*TV*",tmpStr);
    }
    onelayer=onelayer.replace("*TV*","");

    return onelayer;
}

//取得下注的url
function parseUrl(uid,odd_f_type,betTeam,GameData,gamerec,wtype){
    var urlArray=new Array();
    urlArray['R']=new Array("../VB_order/VB_order_re.php",eval("GameData.team_"+betTeam.toLowerCase()));
    urlArray['OU']=new Array("../VB_order/VB_order_rou.php",(betTeam=="C" ? top.strOver : top.strUnder));

    var rewtype = new Array();
    rewtype['R'] = "RE";
    rewtype['OU'] = "ROU";

    var param=getParam(uid,odd_f_type,betTeam,wtype,GameData);
    var order=urlArray[wtype][0];
    var team=urlArray[wtype][1].replace("[Mid]","[N]");
    var tmp_rtype="ior_"+wtype+betTeam;
    var ioratio_str="GameData."+tmp_rtype;

    var ioratio=eval(ioratio_str);
    if(ioratio!=""){
        ioratio=Mathfloor(ioratio);
        ioratio=printf(ioratio,iorpoints);
    }
    var ret="<a href='javascript://' onclick=\"parent.parent.mem_order.betOrder('VB','"+rewtype[wtype]+"','"+param+"');\" title='"+team+"'><font "+check_ioratio(gamerec,tmp_rtype,GameData)+">"+ioratio+"</font></a>";

    return ret;
}

//--------------------------public function --------------------------------

//取得下注參數
function getParam(uid,odd_f_type,betTeam,wtype,GameData){
    var paramArray=new Array();
    paramArray['R']=new Array("gid","uid","odd_f_type","type","gnum","strong","langx");
    paramArray['OU']=new Array("gid","uid","odd_f_type","type","gnum","langx");

    var param="";
    var gid=((wtype=="R"||wtype=="OU"||wtype=="M"||wtype=="EO") ? GameData.gid : GameData.hgid);
    var gnum=eval("GameData.gnum_"+(betTeam=="N"? "c":betTeam.toLowerCase()));
    var strong=(wtype=="R" ? GameData.strong : GameData.hstrong);
    var rtype=(betTeam=="O" ? "ODD" : "EVEN");
    var type=betTeam;

    for (var i=0;i<paramArray[wtype].length;i++){
        if (i>0)  param+="&";
        param+=paramArray[wtype][i]+"="+eval(paramArray[wtype][i]);
    }
    return param;
}

function parseMyLove(GameData){
    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='100%'   border='0' cellpadding='0' cellspacing='0'><tr>";//<td align='left'>"+str_even+"</td>";
    tmpStr+= "<td class='hot_td' >";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}
</script>
<?php
break;
case 'pd':
?>
<script>
var ObjDataFT=new Array();
var oldObjDataFT=new Array();
//var GameHead=new Array("gid","datetime","league","gnum_h","gnum_c","team_h","team_c","strong","ior_H1C0","ior_H2C0","ior_H2C1","ior_H3C0","ior_H3C1","ior_H3C2","ior_H4C0","ior_H4C1","ior_H4C2","ior_H4C3","ior_H0C0","ior_H1C1","ior_H2C2","ior_H3C3","ior_H4C4","ior_OVH","ior_H0C1","ior_H0C2","ior_H1C2","ior_H0C3","ior_H1C3","ior_H2C3","ior_H0C4","ior_H1C4","ior_H2C4","ior_H3C4","ior_OVC");
var keepleg="";
var legnum=0;
var NoshowLeg=new Array();
var myLeg=new Array();
var LeagueAry=new Array();

function ShowGameList(){
    if(loading == 'Y') return;
    if (parent.gamecount!=gamount){
        oldObjDataFT=new Array();
    }
    if(top.odd_f_type==""||""+top.odd_f_type=="undefined") top.odd_f_type="H";
    keepscroll=body_browse.document.body.scrollTop;
    var conscroll= body_browse.document.getElementById('controlscroll');
    dis_ShowLoveI();    

    //秀盤面
    showtables(GameFT,GameHead,gamount,top.odd_f_type);

    body_browse.scroll(0,keepscroll);

    //設定右方重新整理位置
    setRefreshPos();

    parent.gamecount=gamount;

    if(top.showtype=='hgft'||top.showtype=='hgfu'){
        obj_sel = body_browse.document.getElementById('sel_league');
        obj_sel.style.display='none';

    }else{
        show_page();
    }
    conscroll.style.display="none";
    coun_Leagues();
    body_browse.showPicLove();
    loadingOK();
}
var hotgdateArr =new Array();
function hot_gdate(gdate){
    if((""+hotgdateArr).indexOf(gdate)==-1){
        hotgdateArr.push(gdate);
    }
}


//表格函數
function showtables(GameData,Game_Head,data_amount,odd_f_type){
    ObjDataFT=new Array();
    myLeg=new Array();
    for (var j=0;j < data_amount;j++){
        if (GameData[j]!=null){
            ObjDataFT[j]=parseArray(Game_Head,GameData[j]);
        }
    }
    var trdata;
    var showtableData;
    if(body_browse.document.all){
            showtableData=body_browse.document.getElementById('showtableData').innerText ;
            trdata=body_browse.document.getElementById('DataTR').innerText;
            notrdata=body_browse.document.getElementById('NoDataTR').innerText;
    } else{
            showtableData=body_browse.document.getElementById('showtableData').textContent ;
            trdata=body_browse.document.getElementById('DataTR').textContent;
        notrdata=body_browse.document.getElementById('NoDataTR').textContent;
    }
    var showtable=body_browse.document.getElementById('showtable');
    var showlayers="";
    keepleg="";
    legnum=0;
    LeagueAry =new Array();
    if(ObjDataFT.length > 0){
        for ( i=0 ;i < ObjDataFT.length;i++){
                showlayers+=getLayer(trdata,i,odd_f_type);
                
        }
        if(showlayers=="")showlayers=notrdata;
        showtableData=showtableData.replace("*showDataTR*",showlayers);
    }else{
            showtableData=showtableData.replace("*showDataTR*",notrdata);
      
    }
    showtable.innerHTML=showtableData;
}


//表格內容
function getLayer(onelayer,gamerec,odd_f_type){
    var open_hot = false;
    if(MM_IdentificationDisplay(ObjDataFT[gamerec].datetime,ObjDataFT[gamerec].gnum_h)) return "";
    if(("|"+eval('parent.'+sel_gtype+'_lname_ary')).indexOf(("|"+ObjDataFT[gamerec].league+"|"),0)==-1&&eval('parent.'+sel_gtype+'_lname_ary')!='ALL') return "";
    if((""+LeagueAry).indexOf(ObjDataFT[gamerec].league)== -1)LeagueAry.push(ObjDataFT[gamerec].league);
    var tmp_date = ObjDataFT[gamerec].datetime.split("<br>")[0];
    onelayer=onelayer.replace(/\*ID_STR\*/g,tmp_date+ObjDataFT[gamerec].gnum_h);
    onelayer=onelayer.replace(/\*TR_EVENT\*/g,"onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);'");

    if (""+myLeg[ObjDataFT[gamerec].league]=="undefined"){
            myLeg[ObjDataFT[gamerec].league]=ObjDataFT[gamerec].league;
            myLeg[ObjDataFT[gamerec].league]=new Array();
            myLeg[ObjDataFT[gamerec].league][0]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }else{
            myLeg[ObjDataFT[gamerec].league][myLeg[ObjDataFT[gamerec].league].length]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }

    //--------------判斷聯盟名稱列顯示或隱藏----------------
    if (ObjDataFT[gamerec].league==keepleg){
            onelayer=onelayer.replace("*ST*"," style='display: none;'");
    }else{
            onelayer=onelayer.replace("*ST*"," style='display: ;'");
    }
    //---------------------------------------------------------------------
    //--------------判斷聯盟底下的賽事顯示或隱藏----------------
    if (NoshowLeg[ObjDataFT[gamerec].league]==-1){
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: none;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegClose'></span>"); //聯盟的小圖
    }else{
        onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: ;'");
        onelayer=onelayer.replace("*LegMark*","<span id='LegOpen'></span>");  //聯盟的小圖
    }
    //---------------------------------------------------------------------



    //滾球字眼
    ObjDataFT[gamerec].datetime=ObjDataFT[gamerec].datetime.replace("Running Ball","");
    keepleg=ObjDataFT[gamerec].league;
    onelayer=onelayer.replace(/\*LEG\*/gi,ObjDataFT[gamerec].league);


    var tmp_date=ObjDataFT[gamerec].datetime.split("<br>");
    if (sel_gtype=="VU"){
        tmp_date_str=tmp_date[0]+"<br>"+change_time(tmp_date[1]);
    }else{
        tmp_date_str=change_time(tmp_date[1]);
    }
    
    onelayer=onelayer.replace("*DATETIME*",ObjDataFT[gamerec].datetime);//tmp_date_str
    onelayer=onelayer.replace("*TEAM_H*",ObjDataFT[gamerec].team_h.replace("[Mid]","<font color=\"#005aff\">[N]</font>").replace("[中]","<font color=\"#005aff\">[中]</font>"));
    onelayer=onelayer.replace("*TEAM_C*",ObjDataFT[gamerec].team_c);
    //全場
    //波膽
    onelayer=onelayer.replace("*RATIO_H2C0*",parseUrl(uid,odd_f_type,"H2C0",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H2C1*",parseUrl(uid,odd_f_type,"H2C1",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H3C0*",parseUrl(uid,odd_f_type,"H3C0",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H3C1*",parseUrl(uid,odd_f_type,"H3C1",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H3C2*",parseUrl(uid,odd_f_type,"H3C2",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H0C2*",parseUrl(uid,odd_f_type,"H0C2",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H1C2*",parseUrl(uid,odd_f_type,"H1C2",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H0C3*",parseUrl(uid,odd_f_type,"H0C3",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H1C3*",parseUrl(uid,odd_f_type,"H1C3",ObjDataFT[gamerec],gamerec,"PD"));
    onelayer=onelayer.replace("*RATIO_H2C3*",parseUrl(uid,odd_f_type,"H2C3",ObjDataFT[gamerec],gamerec,"PD"));

    return onelayer;
}


//取得下注的url
function parseUrl(uid,odd_f_type,rtype,GameData,gamerec,wtype){
    var urlArray=new Array();
    urlArray['PD']=new Array("../VB_order/VB_order_pd.php");

    var param=getParam(uid,odd_f_type,rtype,wtype,GameData);
    var order=urlArray[wtype][0];
    var tmp_rtype="ior_"+rtype;
    var ioratio_str="GameData."+tmp_rtype;

    var ioratio=eval(ioratio_str);
    if(ioratio!=""){
        ioratio=Mathfloor(ioratio);
        ioratio=printf(ioratio,iorpoints);
    }
        var team="";
    if (rtype=="OVH"){
        team="Other Score";
    }else{
        team=rtype.replace("H","").replace("C",":");
    }
    var ret="<a href='javascript://' onclick=\"parent.parent.mem_order.betOrder('VB','"+wtype+"','"+param+"');\"  title='"+team+"'><font "+check_ioratio(gamerec,tmp_rtype,GameData)+">"+ioratio+"</font></a>";

    return ret;

}

//--------------------------public function --------------------------------

//取得下注參數
function getParam(uid,odd_f_type,rtype,wtype,GameData){
    var paramArray=new Array();
    paramArray['PD']=new Array("gid","uid","odd_f_type","rtype","langx");

    var param="";
    var gid=GameData.gid;

    for (var i=0;i<paramArray[wtype].length;i++){
        if (i>0)  param+="&";
        param+=paramArray[wtype][i]+"="+eval(paramArray[wtype][i]);
    }
    return param;
}
</script>
<?php
break;
case 'p3':
?>
<script>
var ObjDataFT=new Array();
var oldObjDataFT=new Array();
var keepleg="";
var legnum=0;
var NoshowLeg=new Array();
var myLeg=new Array();
var LeagueAry=new Array();
function ShowGameList(){
    
    if(loading == 'Y') return;
    if (parent.gamecount!=gamount){
        oldObjDataFT=new Array();
    }
    if(top.odd_f_type==""||""+top.odd_f_type=="undefined") top.odd_f_type="H";
    keepscroll=body_browse.document.body.scrollTop;
    
    var conscroll= body_browse.document.getElementById('controlscroll');
    conscroll.style.display="";
    conscroll.style.top=keepscroll+1;
    //conscroll.focus();
    
    dis_ShowLoveI();
    
    //秀盤面
    showtables(GameFT,GameHead,gamount,top.odd_f_type);

    //重新將選過的單子秀出來
    orderShowSelALL();
    body_browse.scroll(0,keepscroll);
    
    //設定右方重新整理位置
    setRefreshPos();

    //顯示盤口
    //body_browse.ChkOddfDiv();

    
    parent.gamecount=gamount;

    if(top.showtype=='hgft'||top.showtype=='hgfu'){
        obj_sel = body_browse.document.getElementById('sel_league');
        obj_sel.style.display='none';

    }else{
        show_page();
    }
    

    conscroll.style.display="none";
    coun_Leagues();
    body_browse.showPicLove();
loadingOK();
}
var hotgdateArr =new Array();
function hot_gdate(gdate){
    if((""+hotgdateArr).indexOf(gdate)==-1){
        hotgdateArr.push(gdate);
    }
}

 function orderShowSelALL(){
    for(var i=0;i < top.ordergid.length;i++){
        var obj=top.orderArray["G"+top.ordergid[i]];
        try{
            var classary=(body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className).split("_");
            body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className="pr_"+classary[1];
        }catch(E){} 
    }
}
//表格函數
function showtables(GameData,Game_Head,data_amount,odd_f_type){
    ObjDataFT=new Array();
    myLeg=new Array();
    for (var j=0;j < data_amount;j++){
        if (GameData[j]!=null){
            ObjDataFT[j]=parseArray(Game_Head,GameData[j]);
        }   
    }
    //alert("ObjDataFT===>"+ObjDataFT.length);
    var trdata;//=body_browse.document.getElementById('DataTR').innerHTML;
    var showtableData;
    if(body_browse.document.all){
            showtableData=body_browse.document.getElementById('showtableData').innerText ;
            trdata=body_browse.document.getElementById('DataTR').innerText;
            notrdata=body_browse.document.getElementById('NoDataTR').innerText;
    } else{
            showtableData=body_browse.document.getElementById('showtableData').textContent ;
            trdata=body_browse.document.getElementById('DataTR').textContent;
        notrdata=body_browse.document.getElementById('NoDataTR').textContent;
    }
    //alert(trdata);
    var showtable=body_browse.document.getElementById('showtable');
    var showlayers="";
    keepleg="";
    legnum=0;
    LeagueAry =new Array();
    if(ObjDataFT.length > 0){
        for ( i=0 ;i < ObjDataFT.length;i++){
                showlayers+=getLayer(trdata,i,odd_f_type);
                
        }
        if(showlayers=="")showlayers=notrdata;
        showtableData=showtableData.replace("*showDataTR*",showlayers);
    }else{
            showtableData=showtableData.replace("*showDataTR*",notrdata);
      
    }
    showtable.innerHTML=showtableData;
    //oldObjDataFT=ObjDataFT;
    
//  conscroll.style.display="none";
}


//表格內容
function getLayer(onelayer,gamerec,odd_f_type){
    var open_hot = false;
    if(MM_IdentificationDisplay(ObjDataFT[gamerec].datetime,ObjDataFT[gamerec].gnum_h)) return "";
    if(("|"+eval('parent.'+sel_gtype+'_lname_ary')).indexOf(("|"+ObjDataFT[gamerec].league+"|"),0)==-1&&eval('parent.'+sel_gtype+'_lname_ary')!='ALL') return "";
    if((""+LeagueAry).indexOf(ObjDataFT[gamerec].league)== -1)LeagueAry.push(ObjDataFT[gamerec].league);
    var tmp_date = ObjDataFT[gamerec].datetime.split("<br>")[0];
    onelayer=onelayer.replace(/\*ID_STR\*/g,tmp_date+ObjDataFT[gamerec].gnum_h);
    onelayer=onelayer.replace(/\*TR_EVENT\*/g,"onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);'");
    //alert(ObjDataFT[gamerec].league+"==="+keepleg+"["+(ObjDataFT[gamerec].league==keepleg)+"]")
    
    if (""+myLeg[ObjDataFT[gamerec].league]=="undefined"){
            myLeg[ObjDataFT[gamerec].league]=ObjDataFT[gamerec].league;
            myLeg[ObjDataFT[gamerec].league]=new Array();
            myLeg[ObjDataFT[gamerec].league][0]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }else{
            myLeg[ObjDataFT[gamerec].league][myLeg[ObjDataFT[gamerec].league].length]=tmp_date+ObjDataFT[gamerec].gnum_h;
    }
    
    if (ObjDataFT[gamerec].league==keepleg){
            //alert(ObjDataFT[gamerec].league+"==="+keepleg+"["+(ObjDataFT[gamerec].league==keepleg)+"]")
            onelayer=onelayer.replace("*ST*"," style='display: none;'");
            //--------------判斷聯盟顯示或隱藏----------------
            if (NoshowLeg[ObjDataFT[gamerec].league]==-1){
                //return "";
                onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: none;'"); 
                //聯盟的小圖
                onelayer=onelayer.replace("*LegMark*","<span id='LegClose'></span>");
            }else{
                onelayer=onelayer.replace("*LegMark*","<span id='LegOpen'></span>");
                }
            //---------------------------------------------------------------------
        }else{  
                onelayer=onelayer.replace("*ST*","style='display:'';'");
            
            //--------------判斷聯盟顯示或隱藏----------------
        if (NoshowLeg[ObjDataFT[gamerec].league]==-1){
                onelayer=onelayer.replace(/\*CLASS\*/g,"style='display: none;'"); 
                onelayer=onelayer.replace("*LegMark*","<span id='LegClose'></span>");
            }else{
                //聯盟的小圖
                onelayer=onelayer.replace("*LegMark*","<span id='LegOpen'></span>");
                }
            //---------------------------------------------------------------------
        
    }
/*

    var PR_ior =Array();
    var POU_ior =Array();
    
    PR_ior  = get_other_ioratio("", ObjDataFT[gamerec].ior_PRH   , ObjDataFT[gamerec].ior_PRC   , show_ior);
    POU_ior = get_other_ioratio("", ObjDataFT[gamerec].ior_POUH  , ObjDataFT[gamerec].ior_POUC  , show_ior);
    
    
    ObjDataFT[gamerec].ior_PRH=PR_ior[0];
    ObjDataFT[gamerec].ior_PRC=PR_ior[1];
    ObjDataFT[gamerec].ior_POUH=POU_ior[0];
    ObjDataFT[gamerec].ior_POUC=POU_ior[1];
*/

    //滾球字眼
    ObjDataFT[gamerec].datetime=ObjDataFT[gamerec].datetime.replace("Running Ball",top.str_RB);
    keepleg=ObjDataFT[gamerec].league;
    onelayer=onelayer.replace(/\*LEG\*/gi,ObjDataFT[gamerec].league);
    
    
    var tmp_date=ObjDataFT[gamerec].datetime.split("<br>");
    if (sel_gtype=="VU"){
        tmp_date_str=tmp_date[0]+"<br>"+change_time(tmp_date[1]);
    }else{
        tmp_date_str=change_time(tmp_date[1]);
    }
    
    onelayer=onelayer.replace("*DATETIME*",ObjDataFT[gamerec].datetime);//tmp_date_str
    onelayer=onelayer.replace("*TEAM_H*",ObjDataFT[gamerec].team_h.replace("[Mid]","<font color=\"#005aff\">[N]</font>").replace("[中]","<font color=\"#005aff\">[中]</font>"));
    onelayer=onelayer.replace("*TEAM_C*",ObjDataFT[gamerec].team_c);
    //全場
    
        onelayer=onelayer.replace("*GID_RH*",ObjDataFT[gamerec].gid+"_PRH");
        onelayer=onelayer.replace("*GID_RC*",ObjDataFT[gamerec].gid+"_PRC");

        onelayer=onelayer.replace("*GID_OUH*",ObjDataFT[gamerec].gid+"_POUH");
        onelayer=onelayer.replace("*GID_OUC*",ObjDataFT[gamerec].gid+"_POUC");
    
    //讓球
    if (ObjDataFT[gamerec].strong=="H"){
        onelayer=onelayer.replace("*CON_RH*",ObjDataFT[gamerec].ratio); /*讓球球頭*/
        onelayer=onelayer.replace("*CON_RC*","");
    }else{
        onelayer=onelayer.replace("*CON_RH*","");
        onelayer=onelayer.replace("*CON_RC*",ObjDataFT[gamerec].ratio);
    }
    
    
    onelayer=onelayer.replace("*RATIO_RH*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"PR"));/*讓球賠率*/
    onelayer=onelayer.replace("*RATIO_RC*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"PR"));
    //大小
    if (top.langx=="en-us"){
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_o.replace("O","<b>"+"o"+"</b>"));    /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_u.replace("U","<b>"+"u"+"</b>"));
    }else{
        onelayer=onelayer.replace("*CON_OUC*",ObjDataFT[gamerec].ratio_o.replace("O",top.strOver)); /*大小球頭*/
        onelayer=onelayer.replace("*CON_OUH*",ObjDataFT[gamerec].ratio_u.replace("U",top.strUnder));
    }
    onelayer=onelayer.replace("*RATIO_OUC*",parseUrl(uid,odd_f_type,"C",ObjDataFT[gamerec],gamerec,"POU"));/*大小賠率*/
    onelayer=onelayer.replace("*RATIO_OUH*",parseUrl(uid,odd_f_type,"H",ObjDataFT[gamerec],gamerec,"POU"));
    
    //我的最愛
    onelayer=onelayer.replace("*MYLOVE*",parseMyLove(ObjDataFT[gamerec]));
    onelayer=onelayer.replace("*TV*",'');
    //alert(onelayer);
    return onelayer;
}


//取得下注的url
function parseUrl(uid,odd_f_type,betTeam,GameData,gamerec,wtype){
    var urlArray=new Array();
    urlArray['PR']=new Array(eval("GameData.team_"+betTeam.toLowerCase()));
    urlArray['POU']=new Array((betTeam=="C" ? top.strOver : top.strUnder));
    var team="";
    var title_str="";
    if (urlArray[wtype]!=null){
        team=urlArray[wtype][0];
        title_str="title='"+team+"'";
    }
    var tmp_rtype="ior_"+wtype+betTeam;
    var ioratio_str="GameData."+tmp_rtype;
    var bet_rtype=wtype+betTeam;
    if (wtype.indexOf("T") > -1){
        bet_rtype=wtype.substr(1,1)+"~"+wtype.substr(2,1);
    }
    var ioratio=eval(ioratio_str);
    if(ioratio!=""){
        ioratio=Mathfloor(ioratio);
        ioratio=printf(ioratio,iorpoints);
    }
    var ret="<a href='javascript:void(0)'  onclick='parent.orderParlay(\""+GameData.gidm+"\",\""+GameData.gid+"\",\""+GameData.hgid+"\",\""+(bet_rtype)+"\",\""+GameData.par_minlimit+"\",\""+GameData.par_maxlimit+"\")' "+title_str+"><font "+check_ioratio(gamerec,tmp_rtype,GameData)+">"+ioratio+"</font></a>";
    return ret;
}

//------------------------新過關變色直接新增功能-------------------max 2010/10
top.orderArray=new Array();
top.ordergid=new Array();
function resort(ary){
    var tempary=new Array();
    for(var i=0;i<ary.length;i++){
        if (ary[i]!=0){
            tempary[tempary.length]=ary[i];
            }
        }
    return tempary;
    }


function orderRemoveALL(){

    for(var i=0;i<top.ordergid.length;i++){
                orderRemoveGidBgcolor(top.ordergid[i]);     
        }
        top.orderArray=new Array();
        top.ordergid=new Array();
    }

function orderRemoveGid(removeGid){
    for(var i=0;i<top.ordergid.length;i++){
        var obj=top.orderArray["G"+top.ordergid[i]];
        if (obj.gid==removeGid || obj.hgid==removeGid){
            orderRemoveGidBgcolor(top.ordergid[i]);
            top.orderArray["G"+top.ordergid[i]]="undefined";
            top.ordergid[i]=0;
        } 
    }
    top.ordergid=resort(top.ordergid);
}
function orderRemoveGidBgcolor(gidm){
    var tmpobj=top.orderArray["G"+gidm];
    try{
        var classary=( body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className).split("_");
        body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className="b_"+classary[1];
    }catch(E){}
}


function orderParlay(gidm,gid,hgid,wtype,par_minlimit,par_maxlimit){
    if (""+top.orderArray["G"+gidm]=="undefined"){
        top.ordergid[top.ordergid.length]=gidm;
    }else{
        //orderRemoveGidBgcolor(gidm);
        orderRemoveGidBgcolor(gidm);
        
        var tmp_obj=top.orderArray["G"+gidm];
        if (tmp_obj.wtype==wtype&&tmp_obj.gid==gid){
            orderRemoveGid(gid);
            if (top.ordergid.length > 0){
                orderParlayParam();
            }else{
                
                    try{
                        parent.mem_order.close_bet();   
                    }catch(E){}
            }
            return;
        }   
    }
            
    try{
        var classary=(body_browse.document.getElementById(gid+"_"+wtype).className).split("_");
        body_browse.document.getElementById(gid+"_"+wtype).className="pr_"+classary[1];
    }catch(E){
    }
    var orderobj=new Object();
    orderobj.wtype=wtype;
    orderobj.gid=gid;
    orderobj.hgid=hgid;
    orderobj.par_minlimit=par_minlimit;
    orderobj.par_maxlimit=par_maxlimit; 
    top.orderArray["G"+gidm]=orderobj;
    orderParlayParam();
    
}
//------------------------------------------------------------------------------------
function orderParlayParam(){
    var param="";
        for(var i=0;i<top.ordergid.length;i++){
            var obj=top.orderArray["G"+top.ordergid[i]];
            if (i!=0) param+="&";
             gameparam="game"+(i+1)+"="+obj.wtype+"&game_id"+(i+1)+"="+obj.gid+"&minlimit"+(i+1)+"="+obj.par_minlimit+"&maxlimit"+(i+1)+"="+obj.par_maxlimit;
            param+=gameparam;
            }
    parent.paramData=new Array();
    parent.mem_order.betOrder('VB','PR',"teamcount="+top.ordergid.length+"&uid="+top.uid+"&langx="+top.langx+"&"+param);
}
//--------------------------public function --------------------------------


function parseMyLove(GameData){

    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='100%'   border='0' cellpadding='0' cellspacing='0'><tr>";              
    tmpStr+= "<td class='hot_td' >";
//  tmpStr+= "<table><tr align='right'><td>";
    //tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
//  tmpStr+= "</td></tr></table>";
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}


function killgid(gids){
    //alert(gids);
    var gidary=gids.split("|");
    for (var i=0;i<gidary.length;i++){
        orderRemoveGid(gidary[i]);  
    }
    alert(top.str_otb_close);
}</script>
<?php
break;
}
?>
<script>

//--------------------------------public function ----------------------------

function parseArray(gameHead,gameData){
    var gameObj=new Object();
    for (var i=0;i<gameHead.length;i++){
        if (gameHead[i]!=""){   
            eval("gameObj."+gameHead[i]+"='"+gameData[i]+"'");
        }
    }
    return gameObj;
}


function check_ioratio(rec,rtype,GameData){
//alert(flash_ior_set);
    //return true;
    //alert(GameFT.length+"----"+keepGameData.length)

    if (flash_ior_set =='Y'){
        //alert(oldObjDataFT[rec]);
        if (""+oldObjDataFT[rec]=="undefined" || oldObjDataFT[rec].gid != GameData.gid){
            var gameObj=new Object();
            gameObj.gid=GameData.gid;
            oldObjDataFT[rec]=gameObj;
        }
        
        var new_ioratio=eval("GameData."+rtype);
        var old_ioratio=eval("oldObjDataFT[rec]."+rtype);
        
        
        if (""+old_ioratio=="undefined"){
            eval("oldObjDataFT[rec]."+rtype+"=GameData."+rtype);
            old_ioratio=eval("oldObjDataFT[rec]."+rtype);
        }
        
        //alert("old_ioratio==>"+old_ioratio+",new_ioratio==>"+new_ioratio);
        if (""+new_ioratio=="undefined" || new_ioratio==""){
            eval("oldObjDataFT[rec]."+rtype+"=GameData."+rtype);
            return;
        }
        
        /*
        if (parseFloat(old_ioratio)>parseFloat(new_ioratio) ){
            eval("oldObjDataFT[rec]."+rtype+"=GameData."+rtype);
            return "  style='border: 1px solid #FF0000;' ";
        }
        if (parseFloat(old_ioratio)<parseFloat(new_ioratio) ){
            eval("oldObjDataFT[rec]."+rtype+"=GameData."+rtype);
            return "  style='border: 1px solid #00FF00;' ";
        }
        */
        
        if (old_ioratio!=new_ioratio && old_ioratio !="" && new_ioratio!="") {
            eval("oldObjDataFT[rec]."+rtype+"=GameData."+rtype);
            return "  style='background-color : #dc9602' ";
        }
        
        return true;
    }

}
//--------------判斷聯盟顯示或隱藏----------------
function showLeg(leg){
    for (var i=0;i<myLeg[leg].length;i++){
    if ( body_browse.document.getElementById("TR_"+myLeg[leg][i]).style.display!="none"){
                showLegIcon(leg,"LegClose",myLeg[leg][i],"none");
                
        }else{
            showLegIcon(leg,"LegOpen",myLeg[leg][i],"");
        }
    }
    if ((""+NoshowLeg[leg])=="undefined"){
        NoshowLeg[leg]=-1;
    }else{
        NoshowLeg[leg]=NoshowLeg[leg]*-1;
    }

}
function showLegIcon(leg,state,gnumH,display){
    var  ary=body_browse.document.getElementsByName(leg);
            
    for (var j=0;j<ary.length;j++){
        ary[j].innerHTML="<span id='"+state+"'></span>";
    }
    try{
        body_browse.document.getElementById("TR3_"+gnumH).style.display=display;
    }catch(E){}
    try{
        body_browse.document.getElementById("TR2_"+gnumH).style.display=display;
    }catch(E){}
    try{
        body_browse.document.getElementById("TR1_"+gnumH).style.display=display;
    }catch(E){}
    try{
        body_browse.document.getElementById("TR_"+gnumH).style.display=display;
    }catch(E){}
}
//----------------------


//將時間 轉回 24小時//04:00p
function  change_time(get_time){
    
    if (get_time.indexOf("font") > 0 ) return get_time;
    if (get_time.indexOf("p")>0 || get_time.indexOf("a")>0){
        gtime=get_time.split(":");
        if (gtime[1].indexOf("p")>0){
            
            if (gtime[0]!="12"){
                gtime[0]=gtime[0]*1+12;
            }   
        }
        gtime[1]=gtime[1].replace("a","").replace("p","");
        
    }else{
        return get_time;
    }
    return gtime[0]+":"+gtime[1];
    
}

//隱藏我的最愛选择联赛
function dis_ShowLoveI(){

if(top.swShowLoveI){
  body_browse.document.getElementById("sel_league").style.display="none";
 }else{
  body_browse.document.getElementById("sel_league").style.display="";
 }
 
}


function changeTitleStr(s,at){
    if (s.charAt(at)=="H"){
        return "H";
    }else if(s.charAt(at)=="C"){
        return "A";
    }else if(s.charAt(at)=="N"){
        return "D";
    }
    return "";
}
function loadingOK(){
    //alert("loadingOK")
    try{
        body_browse.document.getElementById("refresh_btn").className="refresh_btn";
    }catch(E){}

}</script>
<SCRIPT LANGUAGE="JAVASCRIPT"> 
<!--

 
 var username='';
 var maxcredit='';
 var code='';
 var pg=0;
 var sel_league=''; //選擇顯示聯盟
 var uid=''; //user's session ID
 var loading = 'Y'; //是否正在讀取瀏覽頁面
 var loading_var = 'Y'; //是否正在讀取變數值頁面
 var ShowType = ''; //目前顯示頁面
 var ltype = 1; //目前顯示line
 var retime_flag = 'N'; //自動更新旗標

 
 var str_even = '和局';
 var str_renew = '秒自動更新';
 var str_submit = '確認';
 var str_reset = '重設';
 
 var num_page = 20; //設定20筆賽程一頁
 var now_page = 1; //目前顯示頁面
 var pages = 1; //總頁數
 var msg = ''; //即時資訊
 var gamount = 0; //目前顯示一般賽程數

 //for(var i=0; i<512; i++){
 //	GameFT[i] = new Array(34); //為各賽程宣告 34 個欄位
 //}
var sel_gtype='VB';

// -->
</SCRIPT>
</head>
<frameset rows="0,*" frameborder="NO" border="0" framespacing="0">
  <frame name="body_var" scrolling="NO" noresize src="body_var.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=3&delay=">
  <frame name="body_browse" src="body_browse.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=3&delay=">
</frameset>
<noframes><body>
 
</body></noframes>
</html>

