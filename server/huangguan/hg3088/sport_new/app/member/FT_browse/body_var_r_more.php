<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");

$gid   = $_REQUEST['gid'];
$langx=$_SESSION['langx'];
$uid   = $_SESSION['Oid'];
$ltype = $_REQUEST['ltype'];
require ("../include/traditional.$langx.inc.php");

//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}

$mysql = "select MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,MB_MID,TG_MID,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4,S_0_1,S_2_3,S_4_6,S_7UP,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB4TG0H,MB4TG1H,MB4TG2H,MB4TG3H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,MB4TG4H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,MB0TG4H,MB1TG4H,MB2TG4H,MB3TG4H from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID='$gid'";
$result = mysqli_query($dbCenterMasterDbLink, $mysql);

$row=mysqli_fetch_assoc($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
var langx = '<?php echo $langx;?>';
var iorpoints=2; // 保留2位小数;
var ObjDataFT=new Array();
function onLoad(){
    show_detail(GameHead,GameHead_SP,GameOther,SPdata);
    parent.document.getElementById('more_window').style.display="block";
    var dd=document.getElementById('showALL_DATA');
    parent.document.getElementById('showdata').width=dd.clientWidth;
    parent.document.getElementById('showdata').height=dd.clientHeight;
}
function show_detail(Game_Head,Game_Head_SP,GameData,SPdata){
    ObjDataFT=new Object();
    var ObjDataSP=new Object();
    
    if (GameData!=null){
        ObjDataFT=parseArray(Game_Head,GameData);
    }
    if (SPdata!=null){
        ObjDataSP=parseArray(Game_Head_SP,SPdata);
        ObjDataSP.gid=ObjDataFT.gid;
    }
    var tableData;
    if(document.all){
        tableData=document.getElementById('showtableData').innerText;
    }else{
        tableData=document.getElementById('showtableData').textContent;
    }
    tableData=tableData.replace("*SHOW_TEAM_H*",ObjDataFT.team_h).replace("[Mid]","[N]");
    tableData=tableData.replace("*SHOW_TEAM_C*",ObjDataFT.team_c);
    tableData=tableData.replace("*SHOW_TEAM_FS_H*",ObjDataFT.team_h);
    tableData=tableData.replace("*SHOW_TEAM_FS_C*",ObjDataFT.team_c);
    
    var Head_PD  =new Array('ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');
    var Head_HPD =new Array('ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC');
    var Head_F   =new Array('ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');
    var Head_T   =new Array('ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER');
    var Head_SP_PG =new Array('ior_PGFH','ior_PGFC','ior_PGFN','ior_PGFN','ior_PGLH','ior_PGLC','ior_PGLN');


    var Head_SP1 =new Array('ior_OSFH','ior_OSFC','ior_OSFN','ior_OSLH','ior_OSLC','ior_OSLN','ior_OSLN','ior_STFH','ior_STFC','ior_STFN','ior_STFN','ior_STLH','ior_STLC','ior_STLN','ior_GAFH','ior_GAFC','ior_GAFN','ior_GALH','ior_GALC','ior_GALN');
    var Head_SP2 =new Array('ior_CNFH','ior_CNFC','ior_CNFN','ior_CNLH','ior_CNLC','ior_CNLN','ior_CDFH','ior_CDFC','ior_CDFN','ior_CDLH','ior_CDLC','ior_CDLN','ior_YCFH','ior_YCFC','ior_YCFN','ior_YCLH','ior_YCLC','ior_YCLN','ior_RCFH','ior_RCFC','ior_RCFN','ior_RCLH','ior_RCLC','ior_RCLN');

    tableData=parseM(tableData,ObjDataFT,Head_PD,"PD");
    tableData=parseM(tableData,ObjDataFT,Head_HPD,"HPD");
    tableData=parseM(tableData,ObjDataFT,Head_T,"T");
    tableData=parseM(tableData,ObjDataFT,Head_F,"F");
    tableData=parseM(tableData,ObjDataSP,Head_SP1,"SP0");
    tableData=parseM(tableData,ObjDataSP,Head_SP_PG,"SP_PG");
    tableData=parseM(tableData,ObjDataSP,Head_SP2,"SP1");
    tableData=parseFS(tableData,Stype,FS_teams,"FS");   
    var showtable=document.getElementById('showtable');
    showtable.innerHTML=tableData;  

}
function parseM(layout,gamedata,tag,wtype){
    var hasUrl=false;
    var tmp_wtype=wtype;
    if (wtype=="SP0"||wtype=="SP1"||wtype=="SP_PG"){
        tmp_wtype="SP";
    }
    for (i=0;i < tag.length;i++){
        var rtype=tag[i].split("_")[1];
        var ratio_url=parseUrl(uid,odd_f_type,gamedata,tmp_wtype,rtype);
        if (ratio_url!=""){
            hasUrl=true;
        }
        layout=layout.replace("*"+tag[i].toUpperCase()+"*",ratio_url);
    }
    if (hasUrl){
        layout=layout.replace("*DISPLAY_"+wtype+"*","");
    }else{
        layout=layout.replace("*DISPLAY_"+wtype+"*","style='display: none'");
    }
    return layout;
}

//取得下注的url
function parseUrl(uid,odd_f_type,GameData,wtype,rtype){
    var urlArray=new Array();
    urlArray['PD']=new Array("../FT_order/FT_order_pd.php");
    urlArray['HPD']=new Array("../FT_order/FT_order_hpd.php");
    urlArray['F']=new Array("../FT_order/FT_order_f.php");
    urlArray['T']=new Array("../FT_order/FT_order_t.php");
    urlArray['SP']=new Array("../FT_order/FT_order_sp.php");
    urlArray['NFS']=new Array("../FT_order/FT_order_nfs.php");
    
    var paramRtype = new Array();
    paramRtype['ODD'] = "ODD";
    paramRtype['EVEN'] = "EVEN";
    paramRtype['T01'] = "0~1";
    paramRtype['T23'] = "2~3";
    paramRtype['T46'] = "4~6";
    paramRtype['OVER'] = "OVER";
    
    var param=getParam(uid,odd_f_type,GameData,wtype,rtype);
    var order=urlArray[wtype][0];
    
    var ioratio=eval("GameData.ior_"+rtype);
    var ret="";
    
    var team="";
    if (wtype=="SP"){
        tmp_rtype=rtype.substring(3,4);
        //alert(tmp_rtype);
        if (tmp_rtype=="H"){
            team=ObjDataFT.team_h;
        }else if (tmp_rtype=="C"){
            team=ObjDataFT.team_c;
        }else{
            team=str_rtype_SP[rtype];
        }
    }else if(wtype=="PD"||wtype=="HPD"){
        if (rtype=="OVH"||rtype=="HOVH"){
            team="Other Score";
        }else{
            team=rtype.replace("H","").replace("H","").replace("C",":");
        }
    }else if (wtype=="F"){
        team=changeTitleStr(rtype,1)+"/"+changeTitleStr(rtype,2);   
    }else{
        if (rtype=="OVER"){
            team="7up";
        }else if (rtype=="ODD"){
            team=strOdd;
        }else if (rtype=="EVEN"){
            team=strEven;
        }else{  
            team=paramRtype[rtype];
        }
    }
    if(ioratio!=""){
        ioratio=Mathfloor(ioratio);
        ioratio=printf(ioratio,iorpoints);
    }
    if (ioratio*1 > 0){
        //ret="<a href='"+order+"?"+param+"' target='mem_order'>"+ioratio+"</a>";
        ret="<a href='javascript://' onclick=\"parent.parent.parent.mem_order.betOrder('FT','"+wtype+"','"+param+"');\" title='"+team+"'>"+ioratio+"</a>";
    }
    return ret;
    
}

//--------------------------public function --------------------------------

//取得下注參數
function getParam(uid,odd_f_type,GameData,wtype,rtype){
    var paramArray=new Array();
    //paramArray['R']=new Array("gid","uid","odd_f_type","type","gnum","strong");
    paramArray['PD']=new Array("gid","uid","odd_f_type","rtype");
    paramArray['HPD']=new Array("gid","uid","odd_f_type","rtype");
    paramArray['F']=new Array("gid","uid","odd_f_type","rtype");
    paramArray['T']=new Array("gid","uid","odd_f_type","rtype");
    paramArray['SP']=new Array("gid","uid","odd_f_type","rtype");
    var param="";
    var gid=((wtype=="HPD") ? GameData.hgid : GameData.gid);
    if (wtype=="HPD"){
        rtype=rtype.substring(1,5); 
    }
    if (wtype=="T"){
        if (rtype!="OVER"&&rtype!="ODD"&&rtype!="EVEN"){
            rtype=rtype.substring(1,2)+"~"+ rtype.substring(2,3);
        }
    }
    for (var i=0;i<paramArray[wtype].length;i++){
        if (i>0)  param+="&";
        param+=paramArray[wtype][i]+"="+eval(paramArray[wtype][i]);
    }
    param+="&langx="+langx;
    return param;
}
function parseFS(layout,stype,teams,wtype){
    var h,c,tcount,stype_h1,stype_h2,stype_c1,stype_c2;
    if(teams["H"] == null || teams["C"] == null){
        layout=layout.replace("*DISPLAY_"+wtype+"*","style='display: none'");
        return layout;
    }else{
        layout=layout.replace("*DISPLAY_"+wtype+"*","");
    }
    
    try{
        stype_h1 = stype['H1'][0];
        stype_h2 = stype['H2'][0];
        stype_c1 = stype['C1'][0];
        stype_c2 = stype['C2'][0];
        h = teams["H"];
        c = teams["C"];
    }catch(e){
        return layout;
    }
    tcount = h.length;
    if(c.length > h.length) tcount = c.length;
    var trdata=document.getElementById('DataTR_FS').innerHTML;
    var showlayers="";
    for(n=1;n < tcount;n++){
        showlayers+=parseFS_TR(trdata,h,c,stype_h1,stype_h2,stype_c1,stype_c2,n);
    }
    layout=layout.replace("*showDataTR_FS*",showlayers);
    return layout;
}
function parseFS_TR(onelayer,h,c,stype_h1,stype_h2,stype_c1,stype_c2,n){
    
    if (h[n]!=null){
        onelayer=onelayer.replace("*TEAM_H*",h[n]['tname']);//主隊球員名稱
        onelayer=onelayer.replace("*IOR_FH*",getFSHref(h[n]['gtype'],h[n]['gid'+stype_h1],h[n]['rtype'+stype_h1],"FS",h[n]['ioratio'+stype_h1]));//主隊最先進球賠率
        onelayer=onelayer.replace("*IOR_LH*",getFSHref(h[n]['gtype'],h[n]['gid'+stype_h2],h[n]['rtype'+stype_h2],"FS",h[n]['ioratio'+stype_h2]));//主隊最先後球賠率
    }else{
        onelayer=onelayer.replace("*TEAM_H*","");//客隊球員名稱
        onelayer=onelayer.replace("*IOR_FH*","");//客隊最先進球賠率
        onelayer=onelayer.replace("*IOR_LH*","");//客隊最先後球賠率
    }
    if (c[n]!=null){
        onelayer=onelayer.replace("*TEAM_C*",c[n]['tname']);//客隊球員名稱
        onelayer=onelayer.replace("*IOR_FC*",getFSHref(c[n]['gtype'],c[n]['gid'+stype_c1],c[n]['rtype'+stype_c1],"FS",c[n]['ioratio'+stype_c1]));//客隊最先進球賠率
        onelayer=onelayer.replace("*IOR_LC*",getFSHref(c[n]['gtype'],c[n]['gid'+stype_c2],c[n]['rtype'+stype_c2],"FS",c[n]['ioratio'+stype_c2]));//客隊最先後球賠率
    }else{
        onelayer=onelayer.replace("*TEAM_C*","");//客隊球員名稱
        onelayer=onelayer.replace("*IOR_FC*","");//客隊最先進球賠率
        onelayer=onelayer.replace("*IOR_LC*","");//客隊最先後球賠率
    }
    return onelayer;
}


function getFSHref(gametype,gid,rtype,wtype,ratio){
    var str = "";
    if(ratio != undefined){
        //str = '<a href=\"../FT_order/FT_order_nfs.php?gametype='+gametype+'&gid='+gid+'&uid='+uid+'&rtype='+rtype+'&wtype='+wtype+'\" target=\"mem_order\">'+ratio+'</A>';
        var param="gametype="+gametype+"&gid="+gid+"&uid="+uid+"&rtype="+rtype+"&wtype="+wtype+"&langx="+langx;
        str = "<a class=\"r_bold\" onclick=\"parent.parent.parent.mem_order.betOrder('FT','NFS','"+param+"');\" style=\"cursor:hand\">"+ratio+"</a>";
        
    }
    return str;
}



</script>

<script>

//--------------------------------public function ----------------------------

function setRefreshPos(){
        var refresh_right= body_browse.document.getElementById('refresh_right');
        refresh_right.style.left= body_browse.document.getElementById('myTable').clientWidth*1+20;
        //refresh_right.style.top= 39;
    }

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
            return "  style='background-color : yellow' ";
        }
        
        return true;
    }

}
//--------------判斷聯盟顯示或隱藏----------------
function showLeg(leg){
    for (var i=0;i<myLeg[leg].length;i++){
    if ( body_var.document.getElementById("TR_"+myLeg[leg][i]).style.display!="none"){
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

//分頁
function show_page(){
    //alert(rtype)
    pg_str='';
    obj_pg = body_browse.document.getElementById('pg_txt');
//  alert(t_page);
    if (t_page==0){
        t_page=1;
        //obj_pg.innerHTML = "";
        //return;
    }
    var tmp_lid="";
    if (rtype=="re"){
        tmp_lid=eval("parent."+sel_gtype+"_lid_ary_RE");
    }else{
        tmp_lid=eval("parent."+sel_gtype+"_lid_ary");
    }

    if(tmp_lid=='ALL'&&!swShowLoveI){
        var disabled="";
        if (t_page==1){
            disabled="disabled";
            }
        var pghtml=(pg*1+1)+" / " +t_page+" "+page+"&nbsp;&nbsp; <select  onchange='chg_pg(this.options[this.selectedIndex].value)' "+disabled+">";
        for(var i=0;i<t_page;i++){
            if (pg==i){
                pghtml+="<option value='"+i+"' selected>"+(i+1)+"</option>";
            }else{
                pghtml+="<option value='"+i+"' >"+(i+1)+"</option>";
            }
        }
        pghtml+="</select>";
        obj_pg.innerHTML = pghtml;
    }else{
        obj_pg.innerHTML = "";
    }
}

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

if(swShowLoveI){
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
    try{
    body_browse.document.getElementById("refresh_right").className="refresh_M_btn";
    }catch(E){}

}
</script>
<SCRIPT>
var uid='<?php echo $uid ?>';
GameOther = Array('<?php echo $row[MID]?>','<?php echo $m_date?>','<?php echo $row[M_League]?>','<?php echo $row[MB_MID]?>','<?php echo $row[TG_MID]?>','<?php echo $row[MB_Team]?>','<?php echo $row[TG_Team]?>','<?php echo $row[MB_Win_Rate]?>','<?php echo $row[TG_Win_Rate]?>','<?php echo $row[M_Flat_Rate]?>','<?php echo $row[MB_Win_Rate_H]?>','<?php echo $row[TG_Win_Rate_H]?>','<?php echo $row[M_Flat_Rate_H]?>','','','','','','<?php echo $row[MID]?>','<?php echo $row[MB1TG0]?>','<?php echo $row[MB2TG0]?>','<?php echo $row[MB2TG1]?>','<?php echo $row[MB3TG0]?>','<?php echo $row[MB3TG1]?>','<?php echo $row[MB3TG2]?>','<?php echo $row[MB4TG0]?>','<?php echo $row[MB4TG1]?>','<?php echo $row[MB4TG2]?>','<?php echo $row[MB4TG3]?>','<?php echo $row[MB0TG0]?>','<?php echo $row[MB1TG1]?>','<?php echo $row[MB2TG2]?>','<?php echo $row[MB3TG3]?>','<?php echo $row[MB4TG4]?>','<?php echo $row[UP5]?>','<?php echo $row[MB0TG1]?>','<?php echo $row[MB0TG2]?>','<?php echo $row[MB1TG2]?>','<?php echo $row[MB0TG3]?>','<?php echo $row[MB1TG3]?>','<?php echo $row[MB2TG3]?>','<?php echo $row[MB0TG4]?>','<?php echo $row[MB1TG4]?>','<?php echo $row[MB2TG4]?>','<?php echo $row[MB3TG4]?>','','<?php echo $row[S_0_1]?>','<?php echo $row[S_2_3]?>','<?php echo $row[S_4_6]?>','<?php echo $row[S_7UP]?>','<?php echo $row[MBMB]?>','<?php echo $row[MBFT]?>','<?php echo $row[MBTG]?>','<?php echo $row[FTMB]?>','<?php echo $row[FTFT]?>','<?php echo $row[FTTG]?>','<?php echo $row[TGMB]?>','<?php echo $row[TGFT]?>','<?php echo $row[TGTG]?>','<?php echo $row[MB1TG0H]?>','<?php echo $row[MB2TG0H]?>','<?php echo $row[MB2TG1H]?>','<?php echo $row[MB3TG0H]?>','<?php echo $row[MB3TG1H]?>','<?php echo $row[MB3TG2H]?>','<?php echo $row[MB4TG0H]?>','<?php echo $row[MB4TG1H]?>','<?php echo $row[MB4TG2H]?>','<?php echo $row[MB4TG3H]?>','<?php echo $row[MB0TG0H]?>','<?php echo $row[MB1TG1H]?>','<?php echo $row[MB2TG2H]?>','<?php echo $row[MB3TG3H]?>','<?php echo $row[MB4TG4H]?>','<?php echo $row[UP5H]?>','<?php echo $row[MB0TG1H]?>','<?php echo $row[MB0TG2H]?>','<?php echo $row[MB1TG2H]?>','<?php echo $row[MB0TG3H]?>','<?php echo $row[MB1TG3H]?>','<?php echo $row[MB2TG3H]?>','<?php echo $row[MB0TG4H]?>','<?php echo $row[MB1TG4H]?>','<?php echo $row[MB2TG4H]?>','<?php echo $row[MB3TG4H]?>');
//GameOther = Array('924221','07-04<BR>08:00a','球会','10456','10455','阿晓斯 [中]','奥丹斯 ','C','3.85','1.85','3.30','','','','','','','','924220','11','19','13','51','29','29','111','81','91','111','15','7','13','51','111','12','7.5','8','8','15','13','23','41','36','41','81','','1.99','1.91','4.2','1.8','2.4','11','6.5','15','23','7.5','5','4.5','29','15','3','','','','','','','','','','','','','','','','','','','','','','','','','','','');
//SPdata = new Array('2.25','1.65','15.00','2.25','1.65','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
SPdata= new Array();
</SCRIPT>
<script>
SPrtype = new Array('PGFH','PGFC','PGFN','PGLH','PGLC','PGLN','OSFH','OSFC','OSFN','OSLH','OSLC','OSLN','STFH','STFC','STFN','STLH','STLC','STLN','GAFH','GAFC','GAFN','GALH','GALC','GALN','CNFH','CNFC','CNFN','CNLH','CNLC','CNLN','CDFH','CDFC','CDFN','CDLH','CDLC','CDLN','YCFH','YCFC','YCFN','YCLH','YCLC','YCLN','RCFH','RCFC','RCFN','RCLH','RCLC','RCLN');
str_rtype_SP = new Array();
str_rtype_SP['PGF'] = '最先进球';
str_rtype_SP['OSF'] = '最先越位';
str_rtype_SP['STF'] = '最先替补球员';
str_rtype_SP['CNF'] = '第一颗角球';
str_rtype_SP['CDF'] = '第一张卡';
str_rtype_SP['RCF'] = '会进球';
str_rtype_SP['YCF'] = '第一张黄卡';
str_rtype_SP['GAF'] = '有失球';
str_rtype_SP['PGL'] = '最后进球';
str_rtype_SP['OSL'] = '最后越位';
str_rtype_SP['STL'] = '最后替补球员';
str_rtype_SP['CNL'] = '最后一颗角球';
str_rtype_SP['CDL'] = '最后一张卡';
str_rtype_SP['RCL'] = '不会进球';
str_rtype_SP['YCL'] = '最后一张黄卡';
str_rtype_SP['GAL'] = '没有失球';
str_rtype_SP['PGFN'] = '没进球';
str_rtype_SP['OSFN'] = '没越位';
str_rtype_SP['STFN'] = '没替补';
str_rtype_SP['CNFN'] = '无角球';
str_rtype_SP['CDFN'] = '没发卡';
str_rtype_SP['RCFN'] = '没发卡';
str_rtype_SP['YCFN'] = '没发卡';
str_rtype_SP['PGLN'] = '没进球';
str_rtype_SP['OSLN'] = '没越位';
str_rtype_SP['STLN'] = '没替补';
str_rtype_SP['CNLN'] = '无角球';
str_rtype_SP['CDLN'] = '没发卡';
str_rtype_SP['RCLN'] = '没发卡';
str_rtype_SP['YCLN'] = '没发卡';
str_rtype_SP['PG'] = '最先/最后进球球队';
str_rtype_SP['OS'] = '最先/最后越位球队';
str_rtype_SP['ST'] = '最先/最后替补球员球队';
str_rtype_SP['CN'] = '第一颗/最后一颗角球';
str_rtype_SP['CD'] = '第一张/最后一张卡';
str_rtype_SP['RC'] = '会进球/不会进球';
str_rtype_SP['YC'] = '第一张/最后一张黄卡';
str_rtype_SP['GA'] = '有失球/没有失球';
FS_teams = new Array();
Stype = new Array();
var GameHead =new Array('hgid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_MH','ior_MC','ior_MN','','','','','ior_HMH','ior_HMC','ior_HMN','gid','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC','ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC','ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC');
var GameHead_SP =new Array('ior_PGFH','ior_PGFC','ior_PGFN','ior_PGLH','ior_PGLC','ior_PGLN','ior_OSFH','ior_OSFC','ior_OSFN','ior_OSLH','ior_OSLC','ior_OSLN','ior_STFH','ior_STFC','ior_STFN','ior_STLH','ior_STLC','ior_STLN','ior_GAFH','ior_GAFC','ior_GAFN','ior_GALH','ior_GALC','ior_GALN','ior_CNFH','ior_CNFC','ior_CNFN','ior_CNLH','ior_CNLC','ior_CNLN','ior_CDFH','ior_CDFC','ior_CDFN','ior_CDLH','ior_CDLC','ior_CDLN','ior_YCFH','ior_YCFC','ior_YCFN','ior_YCLH','ior_YCLC','ior_YCLN','ior_RCFH','ior_RCFC','ior_RCFN','ior_RCLH','ior_RCLC','ior_RCLN');
</script>

</head>
<body id="MFT" class="BODYMORE" onLoad="onLoad();">
<div id=showtableData style="display:none;">
<xmp>
    <table border="0" cellpadding="0" cellspacing="0" id="showALL_DATA">
    <tr>
    <td>    
    <table id="table_team" width="100%" border="0" cellspacing="1" cellpadding="0" class="game">
        <tr>
            <td class="game_team">
                <tt>
                    *SHOW_TEAM_H*
                </tt>&nbsp;&nbsp;
                <span class="vs">vs.</span>&nbsp;&nbsp;
                <tt>
                    *SHOW_TEAM_C*
                </tt>
                <input type="button" class="close" value="" onClick="parent.document.getElementById('more_window').style.display='none';">
    
            </td>
        </tr>
    </table>        
    <table id="table_pd" *DISPLAY_PD* border="0" class="game">
        <tr>
            <td class="game_title" colspan="16">波胆</td>
        </tr>
        <tr>
            <th>1:0</th>
            <th>2:0</th>
            <th>2:1</th>
            <th>3:0</th>
            <th>3:1</th>
            <th>3:2</th>
            <th>4:0</th>
            <th>4:1</th>
            <th>4:2</th>
            <th>4:3</th>
            <th>0:0</th>
            <th>1:1</th>
            <th>2:2</th>
            <th>3:3</th>
            <th>4:4</th>
            <th>其它</th>
        </tr>
        <tr class="b_cen">
            <td>*IOR_H1C0*</td>
            <td>*IOR_H2C0*</td>
            <td>*IOR_H2C1*</td>
            <td>*IOR_H3C0*</td>
            <td>*IOR_H3C1*</td>
            <td>*IOR_H3C2*</td>
            <td>*IOR_H4C0*</td>
            <td>*IOR_H4C1*</td>
            <td>*IOR_H4C2*</td>
            <td>*IOR_H4C3*</td>
            
            <td rowspan="2">*IOR_H0C0*</td>
            <td rowspan="2">*IOR_H1C1*</td>
            <td rowspan="2">*IOR_H2C2*</td>
            <td rowspan="2">*IOR_H3C3*</td>
            <td rowspan="2">*IOR_H4C4*</td>
            <td rowspan="2">*IOR_OVH*</td>
        </tr>   
        <tr class="b_cen">
            <td>*IOR_H0C1*</td>
            <td>*IOR_H0C2*</td>
            <td>*IOR_H1C2*</td>
            <td>*IOR_H0C3*</td>
            <td>*IOR_H1C3*</td>
            <td>*IOR_H2C3*</td>
            <td>*IOR_H0C4*</td>
            <td>*IOR_H1C4*</td>
            <td>*IOR_H2C4*</td>
            <td>*IOR_H3C4*</td>
        </tr>
    </table>
    <table id="table_hpd" *DISPLAY_HPD* border="0" class="game">
        <tr>
            <td class="game_title" colspan="16">上半场波胆</td>
        </tr>
        <tr>
            <th>1:0</th>
            <th>2:0</th>
            <th>2:1</th>
            <th>3:0</th>
            <th>3:1</th>
            <th>3:2</th>
            <th>4:0</th>
            <th>4:1</th>
            <th>4:2</th>
            <th>4:3</th>
            <th>0:0</th>
            <th>1:1</th>
            <th>2:2</th>
            <th>3:3</th>
            <th>4:4</th>
            <th>其它</th>
        </tr>
        <tr class="b_cen">
            <td>*IOR_HH1C0*</td>
            <td>*IOR_HH2C0*</td>
            <td>*IOR_HH2C1*</td>
            <td>*IOR_HH3C0*</td>
            <td>*IOR_HH3C1*</td>
            <td>*IOR_HH3C2*</td>
            <td>*IOR_HH4C0*</td>
            <td>*IOR_HH4C1*</td>
            <td>*IOR_HH4C2*</td>
            <td>*IOR_HH4C3*</td>
            
            <td rowspan="2">*IOR_HH0C0*</td>
            <td rowspan="2">*IOR_HH1C1*</td>
            <td rowspan="2">*IOR_HH2C2*</td>
            <td rowspan="2">*IOR_HH3C3*</td>
            <td rowspan="2">*IOR_HH4C4*</td>
            <td rowspan="2">*IOR_HOVH*</td>
        </tr>   
        <tr class="b_cen">
            <td>*IOR_HH0C1*</td>
            <td>*IOR_HH0C2*</td>
            <td>*IOR_HH1C2*</td>
            <td>*IOR_HH0C3*</td>
            <td>*IOR_HH1C3*</td>
            <td>*IOR_HH2C3*</td>
            <td>*IOR_HH0C4*</td>
            <td>*IOR_HH1C4*</td>
            <td>*IOR_HH2C4*</td>
            <td>*IOR_HH3C4*</td>
        </tr>
    </table>
    <table id="table_t" *DISPLAY_T* border="0"  class="game">
        <tr>
            <td class="game_title" colspan="6">总入球</td>
        </tr>
        <tr>
            <!--th>单</th>
            <th>双</th--> 
            <th>0 - 1</th>
            <th>2 - 3</th>
            <th>4 - 6</th>
            <th>7或以上</th>
        </tr>
        <tr class="b_cen">
            <!--td>*IOR_ODD*</td>
            <td>*IOR_EVEN*</td-->
            <td>*IOR_T01*</td>
            <td>*IOR_T23*</td>
            <td>*IOR_T46*</td>
            <td>*IOR_OVER*</td>
        </tr>   
    </table>
    <table id="table_f" *DISPLAY_F* border="0"  class="game">
      <tr><td class="game_title" colspan="9">半场 / 全场</td></tr>
      <tr>
        <th>主 / 主</th>
        <th>主 / 和</th>
        <th>主 / 客</th>
        <th>和 / 主</th>
        <th>和 / 和</th>
        <th>和 / 客</th>
        <th>客 / 主</th>
        <th>客 / 和</th>
        <th>客 / 客</th>
      </tr>
      <tr>
        <td class="b_cen">*IOR_FHH*</td>
        <td class="b_cen">*IOR_FHN*</td>
        <td class="b_cen">*IOR_FHC*</td>
        <td class="b_cen">*IOR_FNH*</td>
        <td class="b_cen">*IOR_FNN*</td>
        <td class="b_cen">*IOR_FNC*</td>
        <td class="b_cen">*IOR_FCH*</td>
        <td class="b_cen">*IOR_FCN*</td>
        <td class="b_cen">*IOR_FCC*</td>

      </tr>
      
      
  </table>
  <table border="0" class="game" id="table_sp_PG" *DISPLAY_SP_PG*>
      <tr class="game_title">
        <td colspan="3">最先进球</td>
        <td colspan="3">最后进球</td>
        </tr>
      <tr>
        <th width="133">主队</th>
        <th width="133">客队</th>
        <th width="96">无</th>
        <th width="133">主队</th>
        <th width="133">客队</th>
        <th width="96">无</th>
      </tr>
      <tr>
        <td class="b_cen">*IOR_PGFH*</td>
        <td class="b_cen">*IOR_PGFC*</td>
        <td class="b_cen">*IOR_PGFN*</td>
        <td class="b_cen">*IOR_PGLH*</td>
        <td class="b_cen">*IOR_PGLC*</td>
        <td class="b_cen">*IOR_PGFN*</td>
      </tr>
    </table>
  <!--<table id="table_sp_PG" *DISPLAY_SP_PG* border="0" class="game">
    <tr class="game_title">
        <td nowrap>队伍</td>
        <td>最先进球</td>
        <td>最后进球</td>
      </tr>
      <tr class="b_cen">
        <td nowrap>主队</td>
        <td>*IOR_PGFH*</td>
        <td>*IOR_PGLH*</td>
    </tr>
    <tr class="b_cen">
        <td nowrap>客队</td>
        <td>*IOR_PGFC*</td>
        <td>*IOR_PGLC*</td>
    </tr>
    <tr class="b_cen">
        <td>无</td>
        <td>*IOR_PGFN*</td>
        <td>*IOR_PGFN*</td>
    </tr>
  </table> -->       
    <table border="0" class="game" id="table_sp0" *DISPLAY_SP0*>
      <tr class="game_title">
        <td nowrap width="74">队伍</td>
        <td width="106">最先<br>
          替补球员</td>
        <td width="106">最后<br>
          替补球员</td>
        <td width="106">最先越位</td>
        <td width="106">最后越位</td>
        <td width="106">有失球</td>
        <td width="106">无失球</td>
      </tr>
      <tr class="b_cen">
        <td nowrap>主队</td>
        <td>*IOR_STFH*</td>
        <td>*IOR_STLH*</td>
        
        <td>*IOR_OSFH*</td>
        <td>*IOR_OSLH*</td>
        <td>*IOR_GAFH*</td>
        <td>*IOR_GALH*</td>
      </tr>
      <tr class="b_cen">
        <td nowrap>客队</td>
        <td>*IOR_STFC*</td>
        <td>*IOR_STLC*</td>
        
        <td>*IOR_OSFC*</td>
        <td>*IOR_OSLC*</td>
        <td>*IOR_GAFC*</td>
        <td>*IOR_GALC*</td>
      </tr>
      <tr class="b_cen">
        <td>无</td>
        <td>*IOR_STFN*</td>
        <td>*IOR_STFN*</td>
        <td>*IOR_OSLN*</td>
        <td>*IOR_OSLN*</td>
        <td class="b_cen">&nbsp;</td>
        <td class="b_cen">&nbsp;</td>
      </tr>
    </table>
    <table id="table_sp1" *DISPLAY_SP1* border="0" class="game">
      <tr class="game_title">
       <td nowrap>队伍</td>
        <td width="12%">第一颗角球</td>
        <td width="12%">最后一颗<br>角球</td>
        <td width="12%">第一张卡</td>
        <td width="12%">最后一张卡</td>
        <td width="12%">第一张黄卡</td>
        <td width="12%">最后一张<br>黄卡</td>
        <td width="12%">会进球</td>
        <td width="12%">不会进球</td>
      </tr>
      <tr class="b_cen">
        <td>主</td>
        <td>*IOR_CNFH*</td>
        <td>*IOR_CNLH*</td>
        <td>*IOR_CDFH*</td>
        <td>*IOR_CDLH*</td>
        <td>*IOR_YCFH*</td>
        <td>*IOR_YCLH*</td>
        <td>*IOR_RCFH*</td>
        <td>*IOR_RCLH*</td>
      </tr>
      <tr class="b_cen">
        <td>客</td>
        <td>*IOR_CNFC*</td>
        <td>*IOR_CNLC*</td>
        <td>*IOR_CDFC*</td>
        <td>*IOR_CDLC*</td>
        <td>*IOR_YCFC*</td>
        <td>*IOR_YCLC*</td>
        <td>*IOR_RCFC*</td>
        <td>*IOR_RCLC*</td>
      </tr>
      <tr class="b_cen">
        <td align="center">无</td>
        <td colspan="2">*IOR_CNFN*</td>
        <td colspan="2">*IOR_CDLN*</td>
        <td colspan="2">*IOR_YCFN*</td>
        <td colspan="2"></td>
      </tr>
    </table>
    <table id="table_fs" *DISPLAY_FS* border="0" class="game">
    <tr>
        <td class="game_title" colspan="6"> 最先 / 最后进球球员</td>
    </tr>
    <tr>
        <th colspan=3><tt>*SHOW_TEAM_FS_H*</tt></th>
        <th colspan=3>*SHOW_TEAM_FS_C*</span></th>
    </tr>
    <tr>
        <th>球员</th>
        <th id="pname_h1">最先</th>
        <th id="pname_h2">最后</th>
        <th>球员</th>
        <th id="pname_c1">最先</th>
        <th id="pname_c2">最后</th>
    </tr>
        <span class="show_str_none"> *showDataTR_FS* </span>
    </table>
</td>
</tr>
</table>
</xmp>

</div>
<!--   表格资料     -->
<table id=DataTR_FS style="display:none;">
    <tr class="b_cen">
        <td>*TEAM_H*</td>
        <td>*IOR_FH*</td>
        <td>*IOR_LH*</td>
        <td>*TEAM_C*</td>
        <td>*IOR_FC*</td>
        <td>*IOR_LC*</td>
    </tr>
</table>
<div id=showtable></div>
</body>
</html>
