<?php
session_start();
error_reporting(0);
ini_set('display_errors','Off');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/curl_http.php");

$gid   = $_REQUEST['gid'];
$langx=$_SESSION['langx'];
$uid   = $_SESSION['Oid'];
$ltype = $_REQUEST['ltype'];
$date = date("Y-m-d");
require ("../include/traditional.$langx.inc.php");

$open =$_SESSION['OpenType']; // 当前会员盘口类型
//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta name="Robots" contect="none">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
    <link rel="stylesheet" href="../../../style/member/bet_maincortol.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script charset="utf-8" src="../../../js/jquery.js" ></script>
<script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
var odd_f_type = '<?php echo GAME_POSITION;?>';
var _REQUEST = new Array();
 _REQUEST['gid']='<?php echo $gid;?>';
 _REQUEST['uid']='<?php echo $uid;?>';
 _REQUEST['ltype']='4';
 _REQUEST['langx']='zh-cn';
 _REQUEST['gtype']='FT';
 _REQUEST['showtype']='RB';
 _REQUEST['date']='<?php echo $date;?>';

var more_fave_wtype = new Array(); 
var opentype='<?php echo $open?>';
var retime=30;
var iorpoints=2; // 保留2位小数;
var show_ior = '100';	//parent
var langx = '<?php echo $langx;?>';
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
var ObjDataFT = new Array();
var gid_ary = new Array();
var more_window_display_none = false;   //
var gid_rtype_ior = new Array();
var gidXmlObj = new Object();
//var obj_ary = new Array("myMarkets","mainMarkets","goalMarkets","corners","otherMarkets");
//var open_movie = {"myMarkets":false,"mainMarkets":true,"goalMarkets":true,"corners":true,"otherMarkets":true};
var obj_ary = new Array();
var open_movie = new Object()
var favorites_ary = new Array();  //favorites
var retime_flag;
var retime_run;
var show_gid;
var RE_Regex = new RegExp('\^\[A-FH\]\?RE$');
var ROU_Regex = new RegExp('\(\^\[A-FH\]\?ROU[HC]\?$\|\^HRU\[HC\]$\)');
var ARE_Regex = new RegExp('\[ABDE\]RE');
var AROU_Regex = new RegExp('\[ABDE\]ROU');
var ARM_Regex = new RegExp('\[ABDE\]RM');
var PD_Regex = new RegExp('\^H\?R\?H\[0-9\]C\[0-9\]$');
var RMOU_Regex = new RegExp('\^RMU[A-D]$');
var RDU_Regex = new RegExp('\^RDU[A-D]$');
var ROUT_Regex = new RegExp('\^RUT[A-D]$');
var ROUP_Regex = new RegExp('\^RUP[A-D]$');
var ROUE_Regex = new RegExp('\^RUE[A-D]$');
var RNC_Regex = new RegExp('\^RNC[1-9A-U]$');
var RNB_Regex = new RegExp('\^RNB[A-O]$');
var RSH_Regex = new RegExp('\^RS[CH][A-O]$');

//Ricky PJB-176 CRM229 世界盃新玩法
//加時賽 - 5分鐘進球: 大小 (雙盤)
var TARU_Regex = new RegExp('\^T\[ABDE\]RU');

var more_bgYalloW ="";
var TV_eventid = "";
var FavRevMap;
var mod="ALL_Markets";
//var mid = "<?php //echo $uid;?>//".match(/m\d*l\d*$/);
//mid = mid[0];
//mid =	mid.substring(1,mid.length).split("l")
//mid = mid[0];
// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
var OuterOpen = false;
// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
var gameOpen = "";

function init(){
	document.getElementById("LoadLayer").style.display="";
	document.getElementById("LoadLayer").style.visibility = "visible";
	parent.document.getElementById('all_more_window').style.display = "";
	show_gid = _REQUEST['gid'];
	obj_ary = get_obj_ary();
	open_movie = get_open_movie(obj_ary);
	open_movieF();
	FavRevMap = make_FavRevMap();
	reloadGameData();
	retime_run = retime;
	//setAllMark();
	if(retime > 0){
		retime_flag='Y';
	}
	else{
		retime_flag='N';
	}
	if (retime_flag == 'Y'){
		//ReloadTimeID = setInterval("reload_var()",parent.retime*1000);
		count_down();
	}else{
		var rt=document.getElementById('refreshTime');
		rt.innerHTML=refreshTime;
	}
	btnClickEvent("BackToTop");
}

//reload game data
function reloadGameData(){
	retime_run = retime;
	var getHTML = new HttpRequestXML();
	getHTML.addEventListener("LoadComplete", reloadGameDataComplete);
  getHTML.loadURL("/app/member/get_game_allbets.php","POST", getUrlParam());
}

function getUrlParam(){
		var param = "";
		param="uid="+_REQUEST['uid'];
		param+="&langx="+_REQUEST['langx'];
		param+="&gtype="+_REQUEST['gtype'];
		param+="&showtype="+_REQUEST['showtype'];
		//param+="&testMode="+"1";
		param+="&gid="+_REQUEST['gid'];
		param+="&ltype="+_REQUEST['ltype'];
		param+="&date="+_REQUEST['date'];
		return param;
}

function getNodeVal(Node){
		return Node.childNodes[0].nodeValue;
}

function reloadGameDataComplete(objData){
				ObjDataFT=objData.tmp_Obj;
				gid_ary = objData.gid_ary;
                if(!ObjDataFT){ // underfind
                    show_close_info('N') ;
                    document.getElementById("LoadLayer").style.visibility = "hidden";
                    // parent.refreshReload();
                    return;
                }

				// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
				if(ObjDataFT == ""){
                    closeClickEvent();
                    return;
					if(old_ObjDataFT==""){
						closeClickEvent();
						// parent.refreshReload();
						return;
					}
					ObjDataFT = old_ObjDataFT;
				}else{
					old_ObjDataFT = ObjDataFT;
				}
				
				gameOpen = ObjDataFT[show_gid]["gopen"];
				show_close_info(ObjDataFT[show_gid]["gopen"]);
				show_gameInfo(gid_ary[0],ObjDataFT);
				TV_title();

				var tpl = new fastTemplate();
				var tmpScreen = "";
				var newWtypeOU = new Array("RMOU","RDU","ROUE","ROUT","ROUP");
				var newWtypeNEXT = new Array("RSH","RNC","RNB");
				var temporaryNo = new Array("REO","AR","AROU","AM","BR","BROU","BM",
											"CR","COU","CM","DR","DROU","DM",
											"ER","EROU","EM","FR","FROU","FM",
											"SP","MPG","F2G","F3G","FG","T3G",
											"T1G","DG","OUPA","OUPB","OUPC",
											"OUPD","BH","TK","BRG","RTS2","ARG",
											"RT1G","RT3G","RSB","RRCD","RPA","DRG","RHG","RMG",
											"RPG","RC","RCD","RCN","RCD","RYC","RGA","RST","ROS",
											"RMQ","ROG","ROT","BRG","CRG","DRG","ERG","FRG","GRG","HRG",
                                            "RT","RHT","RPD");
				var div_model = document.getElementById('div_model');
				for(var j=0; j<div_model.children.length; j++){
						var tab_model = div_model.children[j].cloneNode(true);
						//alert(tab_model.id+"=="+tab_model.nodeName);
						if(tab_model.nodeName =="TABLE"&&tab_model.id.indexOf("model")!=-1){
								var wtype = tab_model.id.split("_")[1];
								if(in_array(wtype,temporaryNo))	continue;
								document.getElementById('body_'+wtype).innerHTML ="";
								var tmpDiv = document.createElement("div");
								tmpDiv.appendChild(tab_model);

								tpl.init(tmpDiv);
								var tr_color = 0;
								var show_wtype = false;
								var nowBall;
								for(var k=0; k<gid_ary.length; k++){
										var gid = gid_ary[k];
										var hgid = ObjDataFT[gid]["hgid"];
										//var chkArray();
										var strong = ObjDataFT[gid]["strong"];
										var ior_arr;
										//為了OU&其他玩法，多回圈做新的樣式(Title底下有多個Wtype)
										var wtypeAndOU;
										if(in_array(wtype,newWtypeOU)){
											wtypeAndOU = new Array();
											var wtypeNewDelOu = wtype;
											var wtypeNewDelOuStr = wtypeNewDelOu.replace('O','');
											if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"A"]=='Y'){wtypeAndOU.push("A");}
											if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"B"]=='Y'){wtypeAndOU.push("B");}
											if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"C"]=='Y'){wtypeAndOU.push("C");}
											if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"D"]=='Y'){wtypeAndOU.push("D");}
										}else{
											wtypeAndOU = new Array("");
										}
										
										for(var s = 0;s < wtypeAndOU.length;s++){
											var tmpWtype = wtype+wtypeAndOU[s];
											tmpWtype = changeToRB(tmpWtype);
											//該wtype第一次且有值才做addBlock
											if(wtypeAndOU.length != 1){
												if(k == 0){
													tpl.addBlock(wtype);
													tr_color++;
												}
												ior_arr = getIor(ObjDataFT[gid],tmpWtype);
												//console.log(ior_arr);
												//return false;
												var sw =ObjDataFT[gid]["sw_"+tmpWtype];
												var DISPLAY = "DISPLAY_"+wtype;
												if(sw == "N" || ior_arr=="nodata" || (in_array(wtype,newWtypeNEXT) && show_wtype)){
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
													continue;
												}else{
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
													tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
													show_wtype = true;
													nowBall = tmpWtype;
												}
											}else{//wtypeAndOU.length = 1
												ior_arr = getIor(ObjDataFT[gid],tmpWtype);
												if(ior_arr=="nodata") continue;
												tpl.addBlock(wtype);
												tr_color++;
												//var tr_class="";
												//var sw = ObjDataFT[gid]["sw_"+wtype];
												show_wtype = true;
											}
											for(var t=0; t<rtypeMap[tmpWtype].length; t++){
												var rtype = rtypeMap[tmpWtype][t];
												//var ior = ObjDataFT[gid]["ior_"+rtype];
												var ior = ior_arr[rtype]["ior"];
												var IORATIO = "IORATIO_"+rtype;
												var RATIO = "RATIO_"+rtype;
												var td_class = "TD_CLASS_"+rtype;
												var RTYPE_GID = rtype+"_GID";
												var RTYPE_HGID = rtype+"_HGID";
												var RTYPE,WTYPE,tmpWtype1,STR_RTYPE,str_rtype;
												if(in_array(wtype,newWtypeOU)){
													tmpWtype1 = rtype.substr(0,rtype.length-2);
													var htmlRtype = rtype.substr(0,rtype.length-3)+rtype.substr(rtype.length-2);
													htmlRtype = "R"+changeToDB(htmlRtype);
													var htmlWtype = htmlRtype.substr(0,htmlRtype.length-2);
													IORATIO = "IORATIO_"+htmlRtype;
													RATIO = "RATIO_"+htmlRtype;
													RTYPE_GID = htmlRtype+"_GID";
													RTYPE_HGID = htmlRtype+"_HGID";
													RTYPE = htmlRtype+"_RTYPE";
													WTYPE = htmlWtype+"_WTYPE";
													STR_RTYPE = "STR_"+htmlRtype;
													td_class = "TD_CLASS_"+htmlRtype;
													var str_rtype = rtype.substr(rtype.length-1);
													if(rtype.substr(0,2) == "RU")	str_rtype = rtype.substr(rtype.length-2,1);
													str_rtype = "str_"+tmpWtype1.substr(tmpWtype1.length-1)+str_rtype;
                                                    str_rtype = str_ABCD_OU[str_rtype];
												}
												try{
													//tpl.replace(new RegExp('\\*'+IORATIO+'\\*','g'), ior_arr[rtype]["ior"]);
													//Ricky 2017-11-16 CRM-230 雙盤不走四捨五入邏輯
													//tpl.replace(new RegExp('\\*'+IORATIO+'\\*','g'), parse_ior(gid,rtype,ior_arr[rtype]["ior"],wtype));
                                                    tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), ior);
                                                    //Ricky 2017-11-16 CRM-230 雙盤不走四捨五入邏輯
                                                    tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), parse_ior(gid,rtype,ior,wtype));

													tpl.replace(/\*TEAM_H\*/g, ObjDataFT[gid]["team_h"]);
													tpl.replace(/\*TEAM_C\*/g, ObjDataFT[gid]["team_c"]);
													tpl.replace(/\*GID\*/g, gid);
													tpl.replace(/\*HGID\*/g, hgid);
													tpl.replace("*TR_CLASS*",((t+tr_color)%2!=0)?"more_white":"more_color");
													tpl.replace(new RegExp('\\*'+RTYPE_GID+'\\*'), rtype+"_"+gid);
													tpl.replace(new RegExp('\\*'+RTYPE_HGID+'\\*'), rtype+"_"+hgid);
													tpl.replace(new RegExp('\\*'+RTYPE+'\\*'),rtype);
													tpl.replace(new RegExp('\\*'+WTYPE+'\\*'),tmpWtype1);
													tpl.replace(new RegExp('\\*'+STR_RTYPE+'\\*'),str_rtype);
													if(more_bgYalloW == rtype+"_"+gid || more_bgYalloW == rtype+"_"+hgid){
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_yellow");
													}else{
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_white");
													}
													
													//console.log(1313123214);
													//console.log(new RegExp('\\*'+RATIO+'\\*','g'));
													//console.log(undefined2space(ior_arr[rtype]["ratio"]));

													tpl.replace(new RegExp('\\*'+RATIO+'\\*','g'), undefined2space(ior_arr[rtype]["ratio"]));
												}catch(e){

													//alert(wtype+"-"+rtype+" is undefined");
												}
										}
									}

								}
								tmpScreen = tpl.fastPrint();
								if( ARE_Regex.test(wtype) || AROU_Regex.test(wtype) || ARM_Regex.test(wtype) ){
									var type15 = wtype.substr(0,1);
									tmpScreen = tmpScreen.replace("*SCORE_H"+type15+"*",ObjDataFT[gid]["score_h_"+type15]);
									tmpScreen = tmpScreen.replace("*SCORE_C"+type15+"*",ObjDataFT[gid]["score_c_"+type15]);
								}
								//Ricky PJB-176 CRM229 世界盃新玩法
								else if( TARU_Regex.test(wtype) ) 
								{
									var type5 = wtype.substr(1,1);
									tmpScreen = tmpScreen.replace("*SCORE_H"+type5+"OT*",ObjDataFT[gid]["score_h_"+type5+"_ot"]);
									tmpScreen = tmpScreen.replace("*SCORE_C"+type5+"OT*",ObjDataFT[gid]["score_c_"+type5+"_ot"]);
								}
								
								tmpScreen = tmpScreen.replace(/\*TEAM_H\*/g, ObjDataFT[gid]["team_h"]);
								tmpScreen = tmpScreen.replace(/\*TEAM_C\*/g, ObjDataFT[gid]["team_c"]);
								tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_H\*/g, "title='"+ObjDataFT[gid]["team_h"]+"'");//IE tag裡是大寫
								tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_C\*/g, "title='"+ObjDataFT[gid]["team_c"]+"'");
								tmpScreen = tmpScreen.replace(/\*title_team_h\*/g, "title='"+ObjDataFT[gid]["team_h"]+"'");//chrome..tag小寫
								tmpScreen = tmpScreen.replace(/\*title_team_c\*/g, "title='"+ObjDataFT[gid]["team_c"]+"'");
								tmpScreen = tmpScreen.replace(new RegExp('\\*STR_'+wtype.toUpperCase()+'\\*'),"str_"+nowBall);

								tmpScreen = (show_wtype)?tmpScreen:"";
								document.getElementById('body_'+wtype).innerHTML += tmpScreen;
								document.getElementById('body_'+wtype).style.display = "";

						}
				}

				document.getElementById("LoadLayer").style.display="none";
				document.getElementById("LoadLayer").style.visibility = "hidden";

			//最愛
				var tmp_arr = new Array();
				tmp_arr = more_fave_wtype;
				more_fave_wtype = new Array();
                if(tmp_arr) {
                    for (var i = 0; i < tmp_arr.length; i++) {
                        if (FavMap[tmp_arr[i]] == undefined) {
                            if (!in_array(tmp_arr[i], more_fave_wtype)) {
                                more_fave_wtype.push(tmp_arr[i]);
                            }
                            continue;
                        }
                        addFavorites(tmp_arr[i], "fromTop");
                    }
                }


		/*}else{
			closeClickEvent();
		}*/
	fix_body_wtype();
	//fixMoreWindow();
}


function changeToRB(wtype){
	if(wtype.indexOf("RMOU")!=-1) wtype = "RMU"+wtype.substring(4,wtype.length);
	else if(wtype.indexOf("ROUE")!=-1 || wtype.indexOf("ROUP")!=-1 || wtype.indexOf("ROUT")!=-1)	wtype = "R"+wtype.substring(2,wtype.length);

	return wtype;
}
function changeToDB(wtype){
	if(wtype.indexOf("RMU")!=-1) wtype = "MOU"+wtype.substring(3,wtype.length);
	else if(wtype.indexOf("RUE")!=-1 || wtype.indexOf("RUP")!=-1 || wtype.indexOf("RUT")!=-1)	wtype = "O"+wtype.substring(1,wtype.length);
	else	wtype = wtype.substring(1,wtype.length);
	return wtype;
}


//liveTV
function liveTVClickEvent(){
	//alert("TV click");
	if (TV_eventid != "" && TV_eventid != "null" && TV_eventid != undefined) {	//判斷是否有轉播
			parent.OpenLive(TV_eventid,"FT");
	}
}


//refresh
function reFreshClickEvent(){
	//alert("refresh");
	//reloadGameData();
	// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
	reloadGameData(OuterOpen);

}
//倒數自動更新時間
function count_down(){
	var rt=document.getElementById('refreshTime');
	setTimeout('count_down()',1000);
	if (retime_flag == 'Y'){
		if(retime_run <= 0){
				btnClickEvent("Refresh")
				return;
		}
		retime_run--;
		rt.innerHTML=retime_run;
	}
}


//close
function closeClickEvent(){
		parent.show_more_gid='';
        try{
           // parent.document.getElementById('more_window').style.display='none';
            parent.document.getElementById('all_more_window').style.display='none';
            parent.body_browse.document.getElementById('MFT').className="bodyset FTRE";
            parent.body_browse.document.getElementById('box').style.display="";
            parent.body_browse.document.getElementById('right_div').style.display="";
            parent.body_browse.scrollTo(0,top.browse_ScrollY);
        }catch(e){}

		retime_flag ="N";
    parent.parent.retime_flag = "Y";
}

//buttons
function btnClickEvent(eventName){
	//alert(eventName);
	if(eventName == "BackToTop" ){
		//parent.body_browse.scrollTo(0,0)
		document.getElementById("more_div").scrollTop = "0";
	}
	if(eventName == "Close" ) closeClickEvent();
	if(eventName == "Refresh" ) reloadGameData();
}


//playCssEvent
function playCssEvent(eventName){
		//alert(eventName);

		var obj = document.getElementById('movie_'+eventName);
		obj.style.display=(obj.style.display=="")?"none":"";

		setMark(eventName);
}

//set mark
function setMark(_name){

		var obj = document.getElementById('movie_'+_name);
		var showObj = document.getElementById('mark_'+_name);
		//fixMoreWindow()
		if(obj.style.display==""){
				showObj.className = "more_up"; //open
		}else{
				showObj.className = "more_out"; //open
		}
}


//set all mark
function setAllMark(){
		for(var i=0; i<obj_ary.length; i++){
				setMark(obj_ary[i]);
		}
}

var titleDiv;


function show_gameInfo(gid,ObjDataFT){

	var league_name = ObjDataFT[gid]["league"];
	var team_h = ObjDataFT[gid]["team_h"];
	var team_c = ObjDataFT[gid]["team_c"];
	//var session = ObjDataFT[gid]["session"];
	//var score_h = ObjDataFT[gid]["score_h"]*1;
	//var score_c = ObjDataFT[gid]["score_c"]*1;
	// 2017-01-10 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
	//var score_h = ObjDataFT[gid]["score_h"]?ObjDataFT[gid]["score_h"]*1:"";
	//var score_c = ObjDataFT[gid]["score_c"]?ObjDataFT[gid]["score_c"]*1:"";
	// 2017-02-09 17>>(1)足球,其他,檯球-還在滾球中但還沒入比分,比分板應該要秀0-0
	var score_h = ObjDataFT[gid]["score_h"]?ObjDataFT[gid]["score_h"]*1:"0";
	var score_c = ObjDataFT[gid]["score_c"]?ObjDataFT[gid]["score_c"]*1:"0";
	var score_new = ObjDataFT[gid]["score_new"];
	var redcard_h = ObjDataFT[gid]["redcard_h"]*1;
	var redcard_c = ObjDataFT[gid]["redcard_c"]*1;
	var midfield = ObjDataFT[gid]["midfield"];
	var re_time = ObjDataFT[gid]["re_time"];
	var tmpset=re_time.split("^");
 	// 2017-09-21 64.足球滾球-上半場 計時器00:00暫停時   會員端記分板和時節部分請幫秀比分（原顯示上半場00”)
    var showretime= '' ;
    if(tmpset[1]){
        showretime=tmpset[1].replace("'","");
    }
  	var status = "";
  	switch (tmpset[0]){
    	case "HT":
            status = statu["HT"];
            break;
    	case "1H":
            status = statu["1H"];
            break;
    	case "2H":
            status = statu["2H"];
            break;
    	default:
      		status = tmpset[0];
	}
	// 2017-01-25 所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195)
	if( ObjDataFT[gid]['Live'] == 'Y' ){
		if(ObjDataFT[gid]['gopen']=='Y'){
			
			if(titleDiv == undefined){
				titleDiv = document.createElement("div");
				titleDiv.appendChild(gameInfo.cloneNode(true));
			}

			var tmpDiv = titleDiv.cloneNode(true);
			var tmp_repl =  tmpDiv.innerHTML;
			tmp_repl = tmp_repl.replace('*TEAM_H*',team_h);
			tmp_repl = tmp_repl.replace('*TEAM_C*',team_c);
			tmp_repl = tmp_repl.replace('*MID_DISPLAY*',(midfield=="Y")?"":'style="display:none"');
			tmp_repl = tmp_repl.replace('*mid_display*',(midfield=="Y")?"":'style="display:none"');
			// 2017-09-21 64.足球滾球-上半場 計時器00:00暫停時   會員端記分板和時節部分請幫秀比分（原顯示上半場00”)
			if(showretime=="00:00"){
				tmp_repl = tmp_repl.replace('*STATUS*',"LIVE");
			}else{
				tmp_repl = tmp_repl.replace('*STATUS*',status);
			}
			
			tmp_repl = tmp_repl.replace('*SCORE_H*',(score_new=="H")?createSpanClass(score_h,"more_bold"):score_h);
			tmp_repl = tmp_repl.replace('*SCORE_C*',(score_new=="C")?createSpanClass(score_c,"more_bold"):score_c);
			if(redcard_h==0){
				tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*','style="display:none"');
				tmp_repl = tmp_repl.replace('*red_h_display*','style="display:none"');
				tmp_repl = tmp_repl.replace('*RED_H*',"");
			}else{
				tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*',"");
				tmp_repl = tmp_repl.replace('*red_h_display*',"");
				tmp_repl = tmp_repl.replace('*RED_H*',redcard_h);
			}
			if(redcard_c==0){
				tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*','style="display:none"');
				tmp_repl = tmp_repl.replace('*red_c_display*','style="display:none"');
				tmp_repl = tmp_repl.replace('*RED_C*',"");
			}else{
				tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*',"");
				tmp_repl = tmp_repl.replace('*red_c_display*',"");
				tmp_repl = tmp_repl.replace('*RED_C*',redcard_c);
			}
			//tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*',(redcard_h!='0')?"":"none");
			//tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*',(redcard_c!='0')?"":"none");
			// 2017-09-21 64.足球滾球-上半場 計時器00:00暫停時   會員端記分板和時節部分請幫秀比分（原顯示上半場00”)
			if(showretime=="00:00"){
				tmpDiv.innerHTML = tmp_repl.replace('*RB_TIME*',0);
			}else{
				tmpDiv.innerHTML = tmp_repl.replace('*RB_TIME*',showretime);
			}

			document.getElementById("title_league").innerHTML = league_name;
			gameInfo.parentNode.replaceChild(tmpDiv.children[0],gameInfo);

		}
		else{
			if(titleDiv == undefined){
				titleDiv = document.createElement("div");
				titleDiv.appendChild(gameInfo.cloneNode(true));
			}

			var tmpDiv = titleDiv.cloneNode(true);
			var tmp_repl =  tmpDiv.innerHTML;
			tmp_repl = tmp_repl.replace('*TEAM_H*',team_h);
			tmp_repl = tmp_repl.replace('*TEAM_C*',team_c);
			tmp_repl = tmp_repl.replace('*MID_DISPLAY*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*mid_display*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*STATUS*',status);
			// 2017-01-10 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
			tmp_repl = tmp_repl.replace('*SCORE_H*',(score_new=="H")?createSpanClass(score_h,"more_bold"):score_h);
			tmp_repl = tmp_repl.replace('*SCORE_C*',(score_new=="C")?createSpanClass(score_c,"more_bold"):score_c);
			tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*red_h_display*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*RED_H*',"");
			tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*red_c_display*',"style='display:none'");
			tmp_repl = tmp_repl.replace('*RED_C*',"");
			//tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*',"none");
			//tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*',"none");
			tmpDiv.innerHTML = tmp_repl.replace('*RB_TIME*',showretime.replace("'",""));


			document.getElementById("title_league").innerHTML = league_name;
			gameInfo.parentNode.replaceChild(tmpDiv.children[0],gameInfo);
		}
		// 2017-02-09  2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
		OuterOpen = false;
	// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195	
	}else if( ObjDataFT[gid]['Live'] == 'N' && OuterOpen){
		if(titleDiv == undefined){
			titleDiv = document.createElement("div");
			titleDiv.appendChild(gameInfo.cloneNode(true));
		}
		var tmpDiv = titleDiv.cloneNode(true);
		var tmp_repl =  tmpDiv.innerHTML;
		tmp_repl = tmp_repl.replace('*TEAM_H*',team_h);
		tmp_repl = tmp_repl.replace('*TEAM_C*',team_c);
		tmp_repl = tmp_repl.replace('*STATUS*',"");	
		tmp_repl = tmp_repl.replace('*SCORE_H*',"");
		tmp_repl = tmp_repl.replace('*SCORE_C*',"");
		tmp_repl = tmp_repl.replace('*MID_DISPLAY*',"style='display:none'");
		tmp_repl = tmp_repl.replace('*mid_display*',"style='display:none'");
		tmp_repl = tmp_repl.replace('*RED_C_DISPLAY*',"style='display:none'");
		tmp_repl = tmp_repl.replace('*red_c_display*',"style='display:none'");
		tmp_repl = tmp_repl.replace('*RED_H_DISPLAY*',"style='display:none'");
		tmp_repl = tmp_repl.replace('*red_h_display*',"style='display:none'");
		// 2017-01-23 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
		tmpDiv.innerHTML = tmp_repl.replace('*RB_TIME*',"");
		document.getElementById("title_league").innerHTML = league_name;
		gameInfo.parentNode.replaceChild(tmpDiv.children[0],gameInfo);
	}
	

}

function setRefreshPos(){
		var refresh_right= body_browse.document.getElementById('refresh_right');
		refresh_right.style.left= body_browse.document.getElementById('myTable').clientWidth*1+20;
		//refresh_right.style.top= 39;
}

function addFavorites(fav_wtype,kind){
	kind = kind||"";
	if(kind=="")fav_wtype = FavRevMap[fav_wtype];
	for(var k=0;k<FavMap[fav_wtype].length;k++){
		try{
			wtype_str = FavMap[fav_wtype][k]
			var fave_cont;
			var favorites_ = document.getElementById("favorites_"+wtype_str);
			var body_ = document.getElementById("body_"+wtype_str);
			var cont_myMarket = document.getElementById("count_myMarkets");
			var movie_myMarkets = document.getElementById("movie_myMarkets");
			var tmp_repl = body_.innerHTML;
			tmp_repl = tmp_repl.replace("model_","f_table_");
			tmp_repl = tmp_repl.replace("addFavorites","delFavorites");
			tmp_repl = tmp_repl.replace("star_down","star_up");
			favorites_.innerHTML = tmp_repl;
			favorites_.style.display="";
			body_.innerHTML = "";
			body_.style.display="none";
		}catch(e){;}
	}
	if(!in_array(fav_wtype,more_fave_wtype))more_fave_wtype.push(fav_wtype);
	top.CM.set("more_fave_wtype@"+mid,more_fave_wtype);
	fave_cont = count_wtype("myMarkets","ALL_Markets");
	cont_myMarket.innerHTML = fave_cont;
	//if(fave_cont!=0){
		//document.getElementById("movie_myMarkets_nodata").style.display="none";
	//}
	// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
	var myMarkets = document.getElementById("movie_myMarkets_nodata");
	myMarkets.style.display = (fave_cont!=0 || gameOpen!='Y')? "none":"";

	if(movie_myMarkets.style.display =="none" ) playCssEvent('myMarkets');
	fix_body_wtype();
}
function delFavorites(fav_wtype){
	fav_wtype = FavRevMap[fav_wtype];
	for(var k=0;k<FavMap[fav_wtype].length;k++){
		try{
			wtype_str = FavMap[fav_wtype][k]
			var tmp_arr = new Array();
			var tmp_wtype ;
			var fave_cont;
			var favorites_ = document.getElementById("favorites_"+wtype_str);
			var body_ = document.getElementById("body_"+wtype_str);
			var cont_myMarket = document.getElementById("count_myMarkets");
			var tmp_repl = favorites_.innerHTML;
			tmp_repl = tmp_repl.replace("f_table_","model_");
			tmp_repl = tmp_repl.replace("delFavorites","addFavorites");
			tmp_repl = tmp_repl.replace("star_up","star_down");
			body_.innerHTML = tmp_repl ;
			favorites_.innerHTML = "";
			favorites_.style.display="none";
			if( mod== "ALL_Markets" || modeMap[wtype_str][mod]){
				body_.style.display ="";
			}
			else{
				body_.style.display ="none";
			}
		}catch(e){;}
	}
	for(var i=0, a=0;i < more_fave_wtype.length ; i++){
		tmp_wtype = more_fave_wtype[i]
		if(fav_wtype != tmp_wtype) tmp_arr[a++] = tmp_wtype ;
	}
	more_fave_wtype = tmp_arr;
	if(more_fave_wtype.length == 0 ) more_fave_wtype.push("fave_wtype");
	top.CM.set("more_fave_wtype@"+mid,more_fave_wtype);
	fave_cont = count_wtype("myMarkets","ALL_Markets");
	cont_myMarket.innerHTML = fave_cont;
	if(fave_cont == 0)document.getElementById("movie_myMarkets_nodata").style.display="";
	fix_body_wtype();
}


function betEvent(gid,rtype,ratio,wtype){
	var tar ;
	if(ratio==0)return;
    var old_gid = _REQUEST['gid'];
    // if( gid != _REQUEST['gid']){
    //     gid = _REQUEST['gid'];
    // }
    parent.parent.parent.mem_order.betOrder('FT',wtype,getParam(old_gid,wtype,rtype,ratio),old_gid);

	if(rtype == "R0~1" || rtype == "R2~3" || rtype == "R4~6" ){
		var indexs = rtype.substr(1,1) *1 /2 ;
		rtype = rtypeMap[wtype][indexs];
	}
	if(wtype.indexOf('EO') != -1){
		if(rtype.indexOf('ODD') != -1){
			rtype = wtype+"O";
		}else{
			rtype = wtype+"E";
		}
	}
	if(more_bgYalloW != ""){
		try{
		tar = document.getElementById(more_bgYalloW);

		setObjectClass(tar,"bg_white");
		}catch(e){}
	}
	more_bgYalloW = rtype+"_"+gid;
	tar = document.getElementById(more_bgYalloW);
	if(tar){
		setObjectClass(tar,"bg_yellow");	
	}

}

function canclebet(){
        if(more_bgYalloW != ""){
                try{
                tar = document.getElementById(more_bgYalloW);
                setObjectClass(tar,"bg_white");
                }catch(e){}
        }
        more_bgYalloW="";
}


var setObjectClass = function(targetObj,classStr){
    var browserVar = navigator.userAgent.toLowerCase();
    //alert("browser:"+navigator.userAgent.toLowerCase());

    if(browserVar.indexOf("msie") > -1){
            targetObj.className = classStr;
            //targetObj.setAttribute("className", classStr);
    }else{
            targetObj.setAttribute("class", classStr);
    }
    return;
}


function getParam(gid,wtype,rtype,ratio){
	// if(gid%2==1) gid = gid-1;;
	tmp_game = gidXmlObj[gid];
	var strong = ObjDataFT[gid]["strong"];
	var compoundWtype = new Array("RMUA","RMUB","RMUC","RMUD","RMPG","RMTS","RDUA","RDUB","RDUC","RDUD","RDS","RDG","RUTA","RUTB","RUTC","RUTD","RUPA","RUPB","RUPC","RUPD","RUEA","RUEB","RUEC","RUED");

	//Ricky 2017-12-20 PJB-176 CRM229 世界盃新玩法
	var wtypeSingle2017 = Array("RPS","RTW","RPF","RPXA","RPXB","RPXC","RPXD","RPXE","RPXF","RPXG","RPXH","RPXI","RPXJ","RPXK","RPXL","RPXM","RPXN","RPXO");
	var wtypeDouble2017 = Array("TARU","TBRU","TDRU","TERU");

	var type = rtype.substr(rtype.length-1,1).toUpperCase();
	if(wtype.indexOf('OU') != -1 || wtype == 'HRUH' || wtype == 'HRUC' ){
		if(type=='O')type='C';
		if(type=='U')type='H'
	}
	if( wtype=='M' || wtype.indexOf('RM') != -1 ){
		var new_type = (type=='H')?'H':'C';
	}
	if(wtype == "HPD" || wtype == "HRPD"){
		rtype = rtype.substr(1,rtype.length);
	}
	var param = 'gid='+gid+'&uid='+_REQUEST['uid']+'&odd_f_type='+odd_f_type+'&langx='+_REQUEST['langx']+'&rtype='+rtype;
	if(wtype=='RE' ||wtype=='HRE')param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&strong='+strong+'&type='+type;
	else if(wtype=='ROU' || wtype =='HROU')param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&type='+type;
	else if(wtype=='RM' || wtype =='HRM')param += '&gnum='+ObjDataFT[gid]['gnum_'+new_type.toLowerCase()]+'&type='+type;
	else if(wtype=='ROUH' || wtype =='HRUH' || wtype=='ROUC' || wtype =='HRUC')param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&type='+(type =='H'?'U':'O')+'&wtype='+wtype;
	else if(ARE_Regex.test(wtype)) param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&strong='+strong+'&type='+type+'&wtype='+wtype;
	else if(AROU_Regex.test(wtype)) param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&type='+(type =='H'?'U':'O')+'&wtype='+wtype;
	//Ricky 2017-12-20 PJB-176 CRM229 世界盃新玩法
	else if(in_array(wtype,wtypeDouble2017)) param += '&gnum='+ObjDataFT[gid]['gnum_'+type.toLowerCase()]+'&type='+type+'&wtype='+wtype;
	else if(ARM_Regex.test(wtype)) param += '&gnum='+ObjDataFT[gid]['gnum_'+new_type.toLowerCase()]+'&type='+type+'&wtype='+wtype;
	else if(in_array(wtype,compoundWtype)) param += '&wtype='+wtype;	//有OUPD這wtype，會與下面的判斷相符，故拉前做
	else if(wtype.indexOf("PD") != -1 || wtype == 'RT' || wtype == 'HRT' || wtype == 'RF' || wtype.indexOf("EO") != -1) param +='';
	else param += '&wtype='+wtype;


	/*
	var thisRegex = new RegExp('\[ABDE\]RE');
	if(thisRegex.test(wtype)){
	}
	*/
	//preg_match("/([A-F]RE)/",$rtype)


	return param;
}



function fixMoreWindow(){
	var tab_show = document.getElementById('tab_show');
	var MFT = parent.document.getElementById('MFT');
	parent.document.getElementById('showdata').width = MFT.offsetWidth;
	parent.document.getElementById('showdata').height = get_max(tab_show.offsetHeight,MFT.offsetHeight);

}
function get_max(a,b){
	if(a>b)return a;
	else return b;
}

function open_movieF(){
	var market;
	for(var i=0;i<obj_ary.length ;i++){
		market = obj_ary[i];
		if(open_movie[market] == false) playCssEvent(market);
	}

}

function getXML_TagValue(xmlnode,xmlnodeRoot,TagName){
	var ret_value="";
	if(xmlnode.Node(xmlnodeRoot,TagName).childNodes[0] != null && xmlnode.Node(xmlnodeRoot,TagName) != null) {
		ret_value = getNodeVal(xmlnode.Node(xmlnodeRoot,TagName));
	}
	return ret_value;
}

function parse_ior(gid,rtype,ior_value,wtype){
	var red_word = true;
	var bgcolor = false
	if(ior_value *1 < 0 )red_word = false;
	if(typeof(gid_rtype_ior[gid+rtype]) != "undefined" && gid_rtype_ior[gid+rtype] != ior_value ){
		bgcolor = true;
	}
	gid_rtype_ior[gid+rtype] = ior_value;
	if(ior_value!=""){
		//ior_value=parent.Mathfloor(ior_value);
		ior_value=Mathfloor(ior_value);
		//ior_value=parent.printf(ior_value,2);
		// 2017-07-28 CRM-230 單盤（without spread）玩法賠率的四捨五入邏輯 (會員端)
		//Ricky 2017-11-16 CRM-230 雙盤不走四捨五入邏輯
		//ior_value=parent.chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=js_change_rate(opentype,ior_value);
	}
	//if(ior_value*1 == 0 &&  (PD_Regex.test(rtype) || rtype == "HROVH" || rtype == "ROVH" ) )return "-";
	if(ior_value*1 == 0)return "-";
	if(red_word) {
	  ior_value = '<font color=\'#cc0000\'>'+ior_value+'</font>';
	}
	else{
		ior_value = '<font color=\'#1f497d\'>'+ior_value+'</font>';
  }
	if(bgcolor)ior_value = '<font style=\'background-color : yellow\'>'+ior_value+'</font>';
	return ior_value;

}


function XML2Array(xmlnode,xmlnodeRoot){
	var tmp_Obj = new Array();
	var gameXML = xmlnode.Node(xmlnodeRoot,"game",false);
	gid_ary = new Array();
	for(var k=0; k<gameXML.length; k++){
		var tmp_game = gameXML[k];
		var gid = getNodeVal(xmlnode.Node(tmp_game,"gid"));
		var hgid = getNodeVal(xmlnode.Node(tmp_game,"hgid"));
		var TagName = tmp_game.getElementsByTagName("*");
		gid_ary[gid_ary.length] = gid;
	  tmp_Obj[gid] = new Array();
	  tmp_Obj[hgid] = new Array();
		for( var i=0;i<TagName.length;i++){
			try{
				tmp_Obj[gid][TagName[i].nodeName] =  getXML_TagValue(xmlnode,tmp_game,TagName[i].nodeName);
				tmp_Obj[hgid][TagName[i].nodeName] =  getXML_TagValue(xmlnode,tmp_game,TagName[i].nodeName);
			}
			catch(e){
				//tmp_Obj[gid][TagName[i]] = "";
				//tmp_Obj[hgid][TagName[i]] = "";
			}
		}
	}
	return tmp_Obj;
}

function getIor(gdata,wtype){
	var map = rtypeMap[wtype];
	var ior = new Object();
	var rtype,ratio_str,type;
	var gopen = gdata["gopen"];
	var sw = gdata["sw_"+wtype];
	var ior_all_zero = true;
  var strong;
  (wtype=="HRE") ? strong = gdata['hstrong']: strong = gdata['strong'];
	if(gopen == "N") return "nodata" ;
	if(sw == "N") return "nodata" ;
	for(var i=0;i<map.length;i++){
		rtype =  map[i];
		//if( wtype_chk_ary.indexOf(wtype)  && gdata["ior_"+rtype]==0 ) return "nodata" ;
		if( !isNaN(gdata["ior_"+rtype]) && gdata["ior_"+rtype]*1 != 0) ior_all_zero= false;
		ior[rtype] = new Object();
		ior[rtype]["ior"] = gdata["ior_"+rtype];

		ratio_str = getRatioName(wtype,rtype);

		if(gdata[ratio_str]){
				ior[rtype]["ratio"] = gdata[ratio_str];

				type = rtype.substr(rtype.length-1,1);
				if(wtype.indexOf('RE') != -1){
						if(type != strong && type!="N"){
							ior[rtype]["ratio"] = "";
						}
		  	}
	  }
	}
	if(ior_all_zero)return "nodata";
	//2017-07-20 CRB-101 足球玩法原雙盤改單盤  (會員三端) Ricky
	//將 "ROT","RTS","RTS2" 從判斷式中拿掉
	//Ricky 2018-01-04 PJB-176 CRM-229世界盃新玩法 (8)所有會員端-all bets-雙盤-改盤口沒作用
	if( RE_Regex.test(wtype) || ROU_Regex.test(wtype) || wtype == 'REO' || wtype == 'HREO' ||
			RNC_Regex.test(wtype) || RNB_Regex.test(wtype) || RSH_Regex.test(wtype) || TARU_Regex.test(wtype)
			){
			var arry = new Array();
			if(wtype == 'REO' || wtype=='HREO') {
					arry[0] = (ior[map[0]]["ior"]*1000 - 1000) / 1000;
					arry[1] = (ior[map[1]]["ior"]*1000 - 1000) / 1000;

					  arry = get_other_ioratio("H",arry[0],arry[1],show_ior);
					arry[0] =(arry[0]*1000 + 1000) / 1000;
					arry[1] =(arry[1]*1000 + 1000) / 1000;
			}else if(RSH_Regex.test(wtype)){
				 arry[0] = ior[map[2]]["ior"]*1;
				 arry[1] = ior[map[3]]["ior"]*1;

				 arry = get_other_ioratio(odd_f_type,arry[0],arry[1],show_ior);
				 ior[map[2]]["ior"] = arry[0];
				 ior[map[3]]["ior"] = arry[1];

				 arry[0] = ior[map[0]]["ior"]*1;
				 arry[1] = ior[map[1]]["ior"]*1;

				 arry = get_other_ioratio(odd_f_type,arry[0],arry[1],show_ior);
			}else{
				 arry[0] = ior[map[0]]["ior"]*1;
				 arry[1] = ior[map[1]]["ior"]*1;

				// arry = get_other_ioratio(odd_f_type,arry[0],arry[1],show_ior); // show_ior 固定为100 ，这个函数会造成球队得分大小赔率转换，后端也需要这样处理(2018/09/23 改成后端统一处理)
			}

			ior[map[0]]["ior"] = arry[0];
			ior[map[1]]["ior"] = arry[1];
	}

	return ior;
}

function getRatioName(wtype,rtype){
	var ratio= "ratio";
		if(wtype.indexOf('RE') != -1) {
			ratio = "ratio_"+wtype;
		}else if(wtype=='HROU' || wtype=='ROU'){
			ratio = "ratio_"+wtype+(rtype.substr(rtype.length-1,1)=='C'?'o':'u');
		}else{
			ratio = "ratio_"+rtype;
		}
	return ratio.toLowerCase();
}

function undefined2space(val){
	if(val == 'undefined' || typeof(val) == 'undefined')return "";
	else return val;
}

function fix_body_wtype(){

	var cnt;
	for(var i=0;i<obj_ary.length;i++){
		var _name = obj_ary[i];
		cnt = count_wtype(_name);
		document.getElementById("count_"+_name).innerHTML = cnt;
		if(i>0){
			if(cnt == 0){
				document.getElementById("head_"+_name).style.display = "none";
			}else{
				document.getElementById("head_"+_name).style.display = "";
			}
		}
	}
}

function count_wtype(_name){
	var div_model = document.getElementById('movie_'+_name);
	var cnt = 0
	for(var j=0; j<div_model.children.length; j++){
		var child_model = div_model.children[j];
		var div_id = child_model.id;
		if(child_model.nodeName =="DIV"&&( div_id.indexOf("body")!=-1 || div_id.indexOf("favorites")!=-1)){

			var wtype = div_id.split("body_")[1] || div_id.split("favorites_")[1] ;

			if(child_model.innerHTML !="" ) {
				if(div_id.indexOf("body")!=-1){
					setStarTitle(wtype,addtoMyMarket);
				}
				else{
					setStarTitle(wtype,"");
				}
				if(wtype.match(/^[A-FH]?(ROU|RE|RM)$/)){
					child_table = child_model.childNodes[0];
					cnt = cnt + child_table.rows.length -1;
				}
				else cnt++;
			}
			else child_model.style.display="none";
		}
	}
	return cnt;
}



function TV_title(){
	var tv_bton = document.getElementById("live_tv");
	try{
		 TV_eventid = undefined2space( ObjDataFT[show_gid]["eventid"] ) ;
	}catch(e){
		tv_bton.style.display="none";
		return;
	}
	if (TV_eventid != "" && TV_eventid != "null" && TV_eventid != undefined) {	//判斷是否有轉播
		tv_bton.title = str_TV_RB;
		tv_bton.className = "more_tv_on";
	}
	else {
		tv_bton.style.display="none";
	}
}

function show_close_info(gopen){
	var dis_str = "";
	if(gopen=='N')dis_str = "none";
	document.getElementById("gameOver").style.display = (dis_str=="none")?"":"none" ;
	//document.getElementById("mod_table").style.display = dis_str;
	for(i=0;i<obj_ary.length;i++){
		var _name = obj_ary[i];
		var mark = document.getElementById("mark_"+_name);
		document.getElementById("head_"+_name).style.display = dis_str;
		if( gopen == 'Y') {
			document.getElementById("movie_"+_name).style.display = (mark.className == "more_up")?"":"none";
		}
		else{
			document.getElementById("movie_"+_name).style.display = "none";
		}
	}
}




function setStarTitle(wtype,TitleText){

	document.getElementById("star_"+wtype).title = TitleText;

}
function createFontColor(str,aColor){
	return "<font color='"+aColor+"'>"+str+"</font>";
}
function createSpanClass(str,aclass){
	return "<span class='"+aclass+"'>"+str+"</span>";
}


function get_obj_ary(){
	var retAry = new Array();
	var t = document.getElementById("tab_show");
	var td = t.children[0].children[0].children[0];
	for(keys=0 ;keys<td.children.length;keys++){
		htmlObj = td.children[keys];
		if(htmlObj.id.indexOf("head_")!= -1 && htmlObj.nodeName == "TABLE"){
			retAry.push(htmlObj.id.split("_")[1]);
		}
	}
	return retAry
}

function get_open_movie(objAry){
	var retObj = new Object();
	for(var i=0;i<objAry.length;i++){
		objName = objAry[i];
		var obj = document.getElementById('movie_'+objName);
		if(obj != null){
			//if(obj.style.display=="")	retObj[objName]=false;
			// 2017-03-13 300.新舊會員端-足球,棒球-all bet-我的盤口要是預設關的
			if(objName=="myMarkets")	retObj[objName]=false;
			else retObj[objName]=true;
		}
	}
	//return open_movie
	// 2017-03-13 300.新舊會員端-足球,棒球-all bet-我的盤口要是預設關的
	return retObj;
}
function make_FavRevMap(){
	var Map = new Object();
	for(var k in  FavMap ){
		for (var kk in FavMap[k]){
			t_value = FavMap[k][kk];
			t_key = k;
			Map[t_value] = t_key;
		}
	}
	return Map;
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
function HttpRequestXML(){
		var self=this;
		var req;
		var eventHandler=new Array();
		var parentClass;
		self.init=function(){
				self.addEventListener("LoadComplete",self.cmd_proc);
				//self.removeEventListener("LoadComplete");
				//alert("onload");
				}
		self.help=function(){
				var str="";
				str+="EventName:LoadComplete Method:function(html)\n";
				str+="Method:loadURL(url,post/get,pamam)\n";
				return str;
		}
		
		self.setParentclass=function(parentclass){
				parentClass=parentclass;
				//util=parentClass.util;
		}
		
		self.getThis=function(varible){
				return eval(varible);
		}
	
		
		
		self.loadURL=function(url,method,params) {
				req = false;
		    // branch for native XMLHttpRequest object
		   
		    if(window.XMLHttpRequest && !(window.ActiveXObject)) {
			    	try{
								req = new XMLHttpRequest();
			      }catch(e){
								req = false;
			      }
			    // branch for IE/Windows ActiveX version
		    }else if(window.ActiveXObject){
		       	try{
		        		req = new ActiveXObject("Msxml2.XMLHTTP");
		      	}catch(e){
			        	try{
			          		req = new ActiveXObject("Microsoft.XMLHTTP");
			        	}catch(e){
			          		req = false;
			        	}
						}
		    }
		    
		 
				if(req){
				
						req.onreadystatechange = self.processReqChange;
						if(method==undefined) method="POST";
						if(method.toUpperCase()=="POST"){
								req.open("POST", url, true);
								  //req.setRequestHeader("Content-Type","text/xml;charset=utf8");
								// xmlHttp.setRequestHeader("Content-Type","text/xml;charset="+charset);
								// params = "lorem=ipsum&name=binny";
					  		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								//php:·í½s½X¤£¬°utf-8®É­n¥[¦bphp  header('Content-Type:text/html;charset=big5');
								//	req.setRequestHeader("Content-length", params.length);
								//	if (params!="" && params!=undefined)
								req.send(params);
				
						}else{
								req.open("GET", url+"?"+params, true);
								req.send("");
						}
				}
		}
		
		self.processReqChange=function() {
				// only if req shows "loaded"
		 		//   alert(req.status);
		    if(req.readyState == 4){
		        // only if "OK"
		        //alert("req.status="+req.status);
		        if(req.status == 200){
		            // ...processing statements go here...
		            //self.cmd_proc(req.responseText);
		          //	self.eventhandler("LoadComplete",req.responseText);
                    if(req.response){ // ie 取不到 response
                        var gameDataObj=JSON.parse(req.response);
                    }else{
                        var gameDataObj=JSON.parse(req.responseText);
                    }
		           self.eventhandler("LoadComplete",gameDataObj);
		        }else{
		            //alert("There was a problem retrieving the XML data:\n" +req.statusText);
		        }
		    }
		}
		
		self.addEventListener=function(eventname,eventFunction){
				eventHandler[eventname]=eventFunction;
		}
		
		self.removeEventListener=function(eventname){
				EventHandler[eventname]=undefined;
		}
		
		self.eventhandler=function(eventname,param){
				if(eventHandler[eventname]!=undefined){
						eventHandler[eventname](param);
				}
		}
		
		self.cmd_proc=function(html){
				alert(html);
				//return html;
		}
		//self.addEventLisition("LoadComplete",self.cmd_proc);
		self.init();
}
//parent-------------------------------------start

function chkIsFS(wtype){
	var isFS = false;

	var ary = new Array();
	ary["FS"] = true;
	ary["SFS"] = true;

	if(ary[wtype] || wtype.indexOf("FS")!= -1){
		isFS = true;
	}

	return isFS;
}

function chkIsM(rtype){
	try{
		rtype = rtype.toUpperCase();
	}catch(e){}
	var isM = false;

	var M_wtype = new Array("A","B","C","D","E","F");
	var F_wtype = new Array("01","02");
	var RF_wtype = new Array("01","02","03","04","05","06","07","08","09","10",
											"11","12","13","14","15","16","17","18","19","20",
											"21","22","23","24","25","26","27","28","29","30",
											"31","32","33","34","35");

	var ary = new Array();
	ary["MH"] = true;
	ary["MC"] = true;
	ary["MN"] = true;
	ary["HMH"] = true;
	ary["HMC"] = true;
	ary["HMN"] = true;
	ary["RMH"] = true;
	ary["RMC"] = true;
	ary["RMN"] = true;
	ary["HRMH"] = true;
	ary["HRMC"] = true;
	ary["HRMN"] = true;

	for(var i = 0;i < M_wtype.length;i++){
		ary[M_wtype[i]+"MH"] = true;
		ary[M_wtype[i]+"MC"] = true;
		ary[M_wtype[i]+"MN"] = true;
	}
	for(var i = 0;i < F_wtype.length;i++){
		ary["F"+F_wtype[i]+"H"] = true;
		ary["F"+F_wtype[i]+"C"] = true;
	}
	for(var i = 0;i < RF_wtype.length;i++){
		ary["RF"+RF_wtype[i]+"H"] = true;
		ary["RF"+RF_wtype[i]+"C"] = true;
	}

	if(ary[rtype]){
		isM = true;
	}

	return isM;
}

function chkIsEven(wtype)
{
	var isEven = false;
	
	var OUHC = new Array("OUH","OUC","HOUH","HOUC");
	//Ricky 2018-01-26 PJB-176 CRM-229世界盃新玩法 (19)新、手機會員端-內層盤面/所有注單-5分鐘進球，不走四捨五入邏輯
	var DOUBLE = new Array(
				"TARU","TARUO","TARUU",
				"TBRU","TBRUO","TBRUU",
				"TDRU","TDRUO","TDRUU",
				"TERU","TERUO","TERUU",			
				"EOH","EOC","HEOH","HEOC"
				,"RSH1","RSH2","RSH3","RSH4","RSH5","RSH6","RSH7","RSH8","RSH9","RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK"
				,"RSHL","RSHM","RSHN","RSHO","RSHP","RSHQ","RSHR","RSHS","RSHT","RSHU"
				,"RSC1","RSC2","RSC3","RSC4","RSC5","RSC6","RSC7","RSC8","RSC9","RSCA","RSCB","RSCC","RSCD","RSCE","RSCF","RSCG","RSCH","RSCI","RSCJ","RSCK"
				,"RSCL","RSCM","RSCN","RSCO","RSCP","RSCQ","RSCR","RSCS","RSCT","RSCU"
				,"RNB1","RNB2","RNB3","RNB4","RNB5","RNB6","RNB7","RNB8","RNB9","RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK"
				,"RNBL","RNBM","RNBN","RNBO","RNBP","RNBQ","RNBR","RNBS","RNBT","RNBU"
				,"RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK"
				,"RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU"								
			);
	var OU15 = new Array("AOU","BOU","COU","DOU","EOU","FOU");
	var R15 = new Array("AR","BR","CR","DR","ER","FR");
	var ROU15 = new Array("AROU","BROU","CROU","DROU","EROU","FROU");
	var ROUHC = new Array("ROUH","ROUC","HRUH","HRUC");	
	
	
	var ary = new Array();
	ary["HR"] = true;
	ary["R"] = true;
	ary["HRE"] = true;
	ary["RE"] = true;
	ary["HOU"] = true;
	ary["OU"] = true;
	ary["HROU"] = true;
	ary["ROU"] = true;
	
	for(var i=0;i<OUHC.length;i++){
		ary[OUHC[i]] = true;
	}
	
	for(var i=0;i<DOUBLE.length;i++){
		ary[DOUBLE[i]] = true;
	}

	for(var i=0;i<OU15.length;i++){
		ary[OU15[i]] = true;
	}

	for(var i=0;i<R15.length;i++){
		ary[R15[i]] = true;
	}

	for(var i=0;i<ROU15.length;i++){
		ary[ROU15[i]] = true;
	}

	for(var i=0;i<ROUHC.length;i++){
		ary[ROUHC[i]] = true;
	}
	
	if(ary[wtype])
	{
		isEven = true;
	}

	return isEven;

}

//Ricky 2017-11-15 四捨五入邏輯
//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
function chkIsRorOU(wtype){
	var isRorOU = false;

	var OU = new Array("OU","ROU","HOU","HROU","OUH","OUC","ROUH","ROUC","HOUH","HOUC","HROUH","HROUC","POU","HPOU","POUH","POUC","HPOUH","HPOUC");
	var R = new Array("R","RE","HR","HRE","RH","RC","REH","REC","HRH","HRC","HREH","HREC","PR","HPR","PRH","PRC","HPRH","HPRC");
	//Ricky 2018-01-26 PJB-176 CRM-229世界盃新玩法 (19)新、手機會員端-內層盤面/所有注單-5分鐘進球，不走四捨五入邏輯
	var DOUBLE = new Array(
				"TARU","TARUO","TARUU",
				"TBRU","TBRUO","TBRUU",
				"TDRU","TDRUO","TDRUU",
				"TERU","TERUO","TERUU",	
				"EO","HEO","REO","HREO","EOH","EOC","HEOH","HEOC","EOO","EOE","HEOO","HEOE","REOO","REOE","HREOO","HREOE"
				,"AEO","BEO","CEO","DEO","EEO","FEO","GEO","AREO","BREO","CREO","DREO","EREO","FREO","GREO"
				,"RSH1","RSH2","RSH3","RSH4","RSH5","RSH6","RSH7","RSH8","RSH9","RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK"
				,"RSHL","RSHM","RSHN","RSHO","RSHP","RSHQ","RSHR","RSHS","RSHT","RSHU"
				,"RSC1","RSC2","RSC3","RSC4","RSC5","RSC6","RSC7","RSC8","RSC9","RSCA","RSCB","RSCC","RSCD","RSCE","RSCF","RSCG","RSCH","RSCI","RSCJ","RSCK"
				,"RSCL","RSCM","RSCN","RSCO","RSCP","RSCQ","RSCR","RSCS","RSCT","RSCU"
				,"RNB1","RNB2","RNB3","RNB4","RNB5","RNB6","RNB7","RNB8","RNB9","RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK"
				,"RNBL","RNBM","RNBN","RNBO","RNBP","RNBQ","RNBR","RNBS","RNBT","RNBU"
				,"RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK"
				,"RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU"
				,"PEO","HPEO","PREO","HPREO","PEOH","PEOC","HPEOH","HPEOC","PEOO","PEOE","HPEOO","HPEOE"
				,"APEO","BPEO","CPEO","DPEO","EPEO","FPEO","GPEO"								
			);
	var OU15 = new Array("AOU","BOU","COU","DOU","EOU","FOU","GOU"
						,"APOU","BPOU","CPOU","DPOU","EPOU","FPOU","GPOU"
						,"PAOU","PBOU","PCOU","PDOU","PEOU","PFOU","PGOU"
						,"AOUH","BOUH","COUH","DOUH","EOUH","FOUH","GOUH"
						,"APOUH","BPOUH","CPOUH","DPOUH","EPOUH","FPOUH","GPOUH"
						,"PAOUH","PBOUH","PCOUH","PDOUH","PEOUH","PFOUH","PGOUH"
						,"AOUC","BOUC","COUC","DOUC","EOUC","FOUC","GOUC"
						,"APOUC","BPOUC","CPOUC","DPOUC","EPOUC","FPOUC","GPOUC"
						,"PAOUC","PBOUC","PCOUC","PDOUC","PEOUC","PFOUC","PGOUC"
						,"AROUH","BROUH","CROUH","DROUH","EROUH","FROUH","GROUH"
						,"AROUC","BROUC","CROUC","DROUC","EROUC","FROUC","GROUC"
			);
	var R15 = new Array("AR","BR","CR","DR","ER","FR","GR"
						,"APR","BPR","CPR","DPR","EPR","FPR","GPR"
						,"PAR","PBR","PCR","PDR","PER","PFR","PGR"
						,"ARE","BRE","CRE","DRE","ERE","FRE","GRE"
			);
	var ROU15 = new Array("AROU","BROU","CROU","DROU","EROU","FROU");
	var ROUHC = new Array("ROUH","ROUC","HRUH","HRUC");	

	var ary = new Array();
	
	for(var i=0;i<R.length;i++){
		ary[R[i]] = true;
	}
	for(var i=0;i<OU.length;i++){
		ary[OU[i]] = true;
	}

	for(var i=0;i<DOUBLE.length;i++){
		ary[DOUBLE[i]] = true;
	}

	for(var i=0;i<OU15.length;i++){
		ary[OU15[i]] = true;
	}

	for(var i=0;i<R15.length;i++){
		ary[R15[i]] = true;
	}

	for(var i=0;i<ROU15.length;i++){
		ary[ROU15[i]] = true;
	}

	for(var i=0;i<ROUHC.length;i++){
		ary[ROUHC[i]] = true;
	}

	if(ary[wtype])
	{
		isRorOU = true;
	}

	return isRorOU;
}


function chgForm_Single_ratio(odds,rtype,wtype){
	rtype = rtype.toUpperCase();
	//return odds;
	odds = odds*1;
	//Ricky 2017-11-15 四捨五入邏輯
	//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
	var isRorOU = chkIsRorOU(wtype);	
	var isM = chkIsM(rtype);
	var isFS = chkIsFS(rtype);
	var isEven = chkIsEven(rtype);

	//alert("isM->"+", isFS->"+isFS+", wtype->"+wtype+" , odds->"+odds);
	//Ricky 2017-11-15 四捨五入邏輯
	//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
	if(isRorOU)
	{
		return formatNumber(odds,2,2);
	}
	else if(isEven)
	{
		return formatNumber(odds,2,2);
	}	
	else
	{
		if(!(isM || isFS) && odds == 0){
			return formatNumber(odds,0);
		}else if((isM || isFS) && 10 <= odds && odds < 98.5){		//獨贏or冠軍賠率10~98.5 秀小數點後一位
			return formatNumber(odds,1,1);
		}else if(!(isM || isFS) && 5 <= odds && odds < 20){	//非獨贏賠率5~20 秀小數點後一位
			return formatNumber(odds,1,1);
		}else if(!(isM || isFS) && 20 <= odds ){							//非獨贏賠率大於等於20秀整數
			return formatNumber(odds,0);
		}else if((isM || isFS) && 101 <= odds ){						//獨贏or冠軍賠率大於等於101秀整數
			return formatNumber(odds,0);
		}
	
		return formatNumber(odds,2,2);
	}	

}


function chgForm_Single_ratio(odds,rtype,wtype){
	rtype = rtype.toUpperCase();
	//return odds;
	odds = odds*1;
	//Ricky 2017-11-15 四捨五入邏輯
	//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
	var isRorOU = chkIsRorOU(wtype);	
	var isM = chkIsM(rtype);
	var isFS = chkIsFS(rtype);
	var isEven = chkIsEven(rtype);

	//alert("isM->"+", isFS->"+isFS+", wtype->"+wtype+" , odds->"+odds);
	//Ricky 2017-11-15 四捨五入邏輯
	//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
	if(isRorOU)
	{
		return formatNumber(odds,2,2);
	}
	else if(isEven)
	{
		return formatNumber(odds,2,2);
	}	
	else
	{
		if(!(isM || isFS) && odds == 0){
			return formatNumber(odds,0);
		}else if((isM || isFS) && 10 <= odds && odds < 98.5){		//獨贏or冠軍賠率10~98.5 秀小數點後一位
			return formatNumber(odds,1,1);
		}else if(!(isM || isFS) && 5 <= odds && odds < 20){	//非獨贏賠率5~20 秀小數點後一位
			return formatNumber(odds,1,1);
		}else if(!(isM || isFS) && 20 <= odds ){							//非獨贏賠率大於等於20秀整數
			return formatNumber(odds,0);
		}else if((isM || isFS) && 101 <= odds ){						//獨贏or冠軍賠率大於等於101秀整數
			return formatNumber(odds,0);
		}
	
		return formatNumber(odds,2,2);
	}	

}
//parent-------------------------------------end
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
 var _=new Object();
 var dataClass=new Object();
	var debug=true;
function maxlog(obj,message){

	if (!debug) return;
	if (document.getElementById(obj)==null){
                    document.body.innerHTML=document.body.innerHTML+"<div id="+obj+"></div>";
                }
  	if (message==""){
  			document.getElementById(obj).innerHTML="";
  		}else{
  			document.getElementById(obj).innerHTML=document.getElementById(obj).innerHTML+message;
			}
	}
//��������O����(_)
function removeMem(obj){
	 
	
  	 for(var variable in obj){
  	 	try{
  	 		
  	 		//alert(variable+"===>"+typeof obj[variable]);
  	 		if (typeof obj[variable]=="object"){
  	 			maxlog("removeMC_log","NEXT->"+variable);
  	 			//document.getElementById("rm").innerHTML=document.getElementById("rm").innerHTML+"NEXT->"+variable+"<br>";
  	 			obj[variable].removeMC();
  	 			removeMem(obj[variable]);
  	 				maxlog("removeMC_log","[Next Remove]"+variable);
  	 			obj[variable]=null;
  	 		}else{
  	 				//document.getElementById("rm").innerHTML=document.getElementById("rm").innerHTML+"[Remove]"+variable+"<br>";
  	 		 				maxlog("removeMC_log","[Remove]"+variable);
  	 		 		obj[variable]=null;
  	 			}
  	 		
  	 		}catch(e){
  	 				maxlog("removeMC_log","[Try Remove]"+variable);
  	 			obj[variable]=null;
  	 			}
  	 		}
  	 	}

 function showObj(Obj){
            	//alert(obj)
               maxlog("showobj_log","");
                
                for(var variable in Obj){
                    try{
                         maxlog("showobj_log","name:"+variable+"==== value:"+Obj[variable]);
                    }catch(e){
                    	//alert(e);
                    	}
                }
                
            }
//���Jhtml���(��js)
 function loadHtml(Url,autoClearBody,loadComplete){	
  	urls=Url.split("/");
  	filename=urls[urls.length-1].split(".")[0];
  	urlpath=Url.replace(urls[urls.length-1],"");
  
		url=Url;
		//jsurl=urlpath+"js/"+filename+".js";
		jsurl="/js/"+filename+".js";
		if (autoClearBody!=false) try{ removeMem(_);}catch(e){}
		loaderProc(url,jsurl,autoClearBody,loadComplete);
  		
  }

function loaderProc(url,jsurl,autoClearBody,loadComplete){
		var getHTML=new HttpRequest();
		getHTML.addEventListener("LoadComplete",function(html){
				//alert(html);
			if (autoClearBody!=false) document.body.innerHTML="";
					var tempHtml=new parseHTML(html);
					dbody=tempHtml.getTag("div")[0];
					//dbody=tempHtml.getTag("body")[0];
					//dhead=tempHtml.getTag("head")[0];
					//document.body.innerHTML="";
	
					//CSS
					alink=tempHtml.getTag("link");

					for(i=0;i<alink.length;i++) {
							document.body.appendChild(alink[i]);

						//document.getElementsByTagName("head")[0].appendChild(alink[i]);
					}
					
					 document.body.appendChild(dbody);
					//alert("header=>"+dhead.innterHTML);
					

					/*
					var s=tempHtml.getTag("script")[0];
					if (s!=null){
							//document.getElementsByTagName("head")[0].appendChild(s);
							alert(s.tagName+"==>"+s.innerHTML);
							(document.getElementsByTagName("head")[0] || document.body).appendChild(s.cloneNode(true));
						}
						*/
						
					//document.body.replaceChild(document.body,dbody);
					tempHtml.remove();
					//script
					//alert(jsurl);
					loadscript(jsurl,loadComplete);
				})
	
			getHTML.loadURL(url,"GET","");
	
}

	
//�ѪRxml
function parseXml(xml){
		var tempHtml=new parseHTML(xml);
  		var xml=tempHtml.getChildren();
  		alert("-==>"+xml[0].tagName);
  		var firstNode=xml[0].tagName;
  	
  		//game=xml["server"].node["group"].children["game"].children;
					root=tempHtml.getTag(firstNode);

					
					xmlnode=new xmlNode(root);
					tempHtml.remove();
					return xmlnode;
	
	}


//�ѪRxml Class	
function xmlNodeMax(root){
  				_self=this;
  				_self.Root=root;
  				parentNode=_self.Root[0];
  				_self.getParentNode=function(){
  					return parentNode;
  					}
  				_self.getNode=function(node,auto){
  					retNode=parentNode.getElementsByTagName(node);
  					parentNode=retNode[0];
  					if (auto==false) return retNode[0].childNode;
  					if (retNode.length==1) return retNode[0].childNodes[0];
  					else return retNode[0].childNodes;
  				//	return retNode;
  					}
	  		_self.Node=function(parentNode,node,auto){
	  				if (parentNode.length>1){
	  					alert("DataNode error!!");
	  					return;
	  					}
  					retNode=parentNode.getElementsByTagName(node);
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  					//return retNode;
  					}
  				_self.removeMC=function(){}
  			}	

//�ѪRxml Class	
function xmlNode(root){
  				_self=this;
  				_self.Root=root;
  				parentNode=_self.Root[0];
  				_self.getParentNode=function(){
  					return parentNode;
  					}
  				_self.getNode=function(node,auto){
  					retNode=parentNode.getElementsByTagName(node);
  					parentNode=retNode[0];
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  				//	return retNode;
  					}
	  		_self.Node=function(parentNode,node,auto){
	  				if (parentNode.length>1){
	  					alert("DataNode error!!");
	  					return;
	  					}
  					retNode=parentNode.getElementsByTagName(node);
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  					//return retNode;
  					}
  				_self.removeMC=function(){}
  			}	
//�ѪRhtml Class
function parseHTML(html){
	//alert("parseHTML==>"+html);
	var _self=this;
	var divObj=document.createElement("div");
	//var divObj=document.createDocumentFragment();
	//	var divObj=document.createDocumentFragment();
	//document.body.appendChild(divObj);

	//document.appendChild(divObj);
		
	alert("parseHTML==>"+html);
		
	divObj.innerHTML=html;
	//divObj.innerHTML="<xmp>"+html+"</xmp>"
	//divObj.innerHTML = divObj.innerHTML.replace("<xmp>","").replace("</xmp>","").replace("<XMP>","").replace("</XMP>","");
	
	//divObj.appendChild(divObj1);
	//divObj1=null;
	alert(divObj.innerHTML);

	document.getElementById("test_look").innerHTML ="<xmp>"+divObj.innerHTML+"</xmp>";	
	//alert(divObj.parentNode);
	_self.getTag=function(tagID,divobj){
		
		if (divobj==undefined) divobj=divObj;
		var retobj=new Array();
		for(var i=0;i<divobj.children.length;i++){
		//	alert(divobj.children[i].tagName+"==>"+ divobj.children[i].id);
			if (divobj.children[i].tagName.toUpperCase()==tagID.toUpperCase()){
				retobj.push(divobj.children[i]);
				}
			}
		return retobj;
	
		}
	_self.getChildren=function(){
		return divObj.children;
		}
	//document.body.appChild(divObj);
 	_self.getObj=function(tagID,divobj){
 		if (divobj==undefined) divobj=divObj;
				var obj=null;
				try{
					obj=divobj.children[tagID];
				}catch(e){
					obj=null;
				}
				
				return obj;
      }
    //return _self;
     _self.remove=function(){
	   	//�ۤv��@�����C�@��div�U��������
	   	//��@����@���^�ӵ���
	   	divObj=null;
	   	
   	}
    _self.removeMC=function(){}
 }
 
 //���Jscript
 function loadscript(url,loadComplete){
		
		scriptAry=document.getElementsByTagName("script");
		for(i=scriptAry.length-1;i>=0;i--){
			//if (scriptAry[i].src==url) return;
			scriptAry[i].parentNode.removeChild(scriptAry[i]);
		}
		
	var src=document.createElement("script");	
	(document.getElementsByTagName("head")[0] || document.body).appendChild(src);
	src.id=url;
	src.src=url;
	if(loadComplete!=null) src.onload=loadComplete;
	
}




function removeScript(url){
	//alert(url+"==>"+document.getElementById(url));
	var obj=document.getElementById(url);
	if (obj!=null)
		document.getElementById(url).parentNode.removeChild(document.getElementById(url));
	}
function runJS(js){
	return new Function("return "+js)();
	}
//CSS�ʹ�class	
function cssAni(divObj){
  			var _self=this;
  		 _self.play=function(ms,times){
			 			playtime=ms/1000;                                          
						divObj.style["-webkit-animation-duration"]=playtime+"s";       
						divObj.style["-webkit-animation-iteration-count"]=1; 
					 	_self.finishTimer=setTimeout(_self.finishAni,ms,divObj);  
				}   
			  _self.showXY=function(x,y){
  					divObj.style.top=y;
  					divObj.style.left=x;
  				}
  			_self.finishAni=function(divObj){
  				divObj.style["-webkit-animation-iteration-count"]=0;
  				csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
  	  		lastPos=csstag[csstag.length-1].style.cssText.replace(";","").split(":");
					divObj.style[lastPos[0]]=lastPos[1];
  				_self.finish(divObj);
  				}
  		  _self.finish=function(divObj){
  					alert("override finish(divObj)");
  				
  				}
  			//���ocss keyframe size
  			_self.KeyFrameSize=function(){
  				csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
  				return csstag.length;
  				}
  				
  			//�]�w��@��css keyframe
        _self.setKeyFrame=function(keyframe,value){
        	csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
        	if (keyframe>csstag.length-1) alert("error=>keyframe>size");
        	csstag[keyframe].style.cssText=csstag[0].style[0]+":"+value;
        	}      
        //���ocss�Ҧ�keyframe����                                             
				_self.getCssTag=function(findTagName){	
    			for (var i=0;i<document.styleSheets[0].rules.length;i++){
    			//alert(document.styleSheets[0].rules[i].name);
    				if (document.styleSheets[0].rules[i].name==findTagName){
    					return document.styleSheets[0].rules[i].cssRules;
    			//alert(i)
    					}
    				}
    				
    			}
}</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
var rtypeMap = new Object();

//走地
rtypeMap["RE"] = ["REH","REC"];
rtypeMap["HRE"] = ["HREH","HREC"];
rtypeMap["ROU"] = ["ROUH","ROUC"];
rtypeMap["HROU"] = ["HROUH","HROUC"];
rtypeMap["RM"] = ["RMH","RMC","RMN"];
rtypeMap["HRM"] = ["HRMH","HRMC","HRMN"];
rtypeMap["ARE"] = ["AREH","AREC"];
rtypeMap["AROU"] = ["AROUU","AROUO"];
rtypeMap["ARM"] = ["ARMH","ARMC","ARMN"];
rtypeMap["BRE"] = ["BREH","BREC"];
rtypeMap["BROU"] = ["BROUU","BROUO"];
rtypeMap["BRM"] = ["BRMH","BRMC","BRMN"];
rtypeMap["DRE"] = ["DREH","DREC"];
rtypeMap["DROU"] = ["DROUU","DROUO"];
rtypeMap["DRM"] = ["DRMH","DRMC","DRMN"];
rtypeMap["ERE"] = ["EREH","EREC"];
rtypeMap["EROU"] = ["EROUU","EROUO"];
rtypeMap["ERM"] = ["ERMH","ERMC","ERMN"];
rtypeMap["RPD"] = ["RH1C0","RH2C0","RH2C1","RH3C0","RH3C1","RH3C2","RH4C0","RH4C1","RH4C2","RH4C3","RH0C0","RH1C1","RH2C2","RH3C3","RH4C4","ROVH","RH0C1","RH0C2","RH1C2","RH0C3","RH1C3","RH2C3","RH0C4","RH1C4","RH2C4","RH3C4","ROVC"];
rtypeMap["HRPD"] = ["HRH1C0","HRH2C0","HRH2C1","HRH3C0","HRH3C1","HRH3C2","HRH4C0","HRH4C1","HRH4C2","HRH4C3","HRH0C0","HRH1C1","HRH2C2","HRH3C3","HRH4C4","HROVH","HRH0C1","HRH0C2","HRH1C2","HRH0C3","HRH1C3","HRH2C3","HRH0C4","HRH1C4","HRH2C4","HRH3C4","HROVC"];
rtypeMap["RT"] = ["RT01","RT23","RT46","ROVER"];
rtypeMap["HRT"] = ["HRT0","HRT1","HRT2","HRTOV"];
rtypeMap["RF"] = ["RFHH","RFHN","RFHC","RFNH","RFNN","RFNC","RFCH","RFCN","RFCC"];
rtypeMap["RWM"] = ["RWMH1","RWMH2","RWMH3","RWMHOV","RWMC1","RWMC2","RWMC3","RWMCOV","RWM0","RWMN"];
rtypeMap["RDC"] = ["RDCHN","RDCCN","RDCHC"];
rtypeMap["RWE"] = ["RWEH","RWEC"];
rtypeMap["RWB"] = ["RWBH","RWBC"];
rtypeMap["ARG"] = ["ARGH","ARGC","ARGN"];
rtypeMap["BRG"] = ["BRGH","BRGC","BRGN"];
rtypeMap["CRG"] = ["CRGH","CRGC","CRGN"];
rtypeMap["DRG"] = ["DRGH","DRGC","DRGN"];
rtypeMap["ERG"] = ["ERGH","ERGC","ERGN"];
rtypeMap["FRG"] = ["FRGH","FRGC","FRGN"];
rtypeMap["GRG"] = ["GRGH","GRGC","GRGN"];
rtypeMap["HRG"] = ["HRGH","HRGC","HRGN"];
rtypeMap["IRG"] = ["IRGH","IRGC","IRGN"];
rtypeMap["JRG"] = ["JRGH","JRGC","JRGN"];
rtypeMap["RTS"] = ["RTSY","RTSN"];
rtypeMap["ROUH"] = ["ROUHO","ROUHU"];
rtypeMap["ROUC"] = ["ROUCO","ROUCU"];
rtypeMap["HRUH"] = ["HRUHO","HRUHU"];
rtypeMap["HRUC"] = ["HRUCO","HRUCU"];
rtypeMap["REO"] = ["REOO","REOE"];
rtypeMap["HREO"] = ["HREOO","HREOE"];
rtypeMap["RCS"] = ["RCSH","RCSC"];
rtypeMap["RWN"] = ["RWNH","RWNC"];
rtypeMap["RHG"] = ["RHGH","RHGC"];
rtypeMap["RMG"] = ["RMGH","RMGC","RMGN"];
rtypeMap["RSB"] = ["RSBH","RSBC"];
rtypeMap["RT3G"] = ["RT3G1","RT3G2","RT3GN"];
rtypeMap["RT1G"] = ["RT1G1","RT1G2","RT1G3","RT1G4","RT1G5","RT1G6","RT1GN"];
rtypeMap["RTS2"] = ["RTS2Y","RTS2N"];
rtypeMap["RMUA"] = ["RMUAHO","RMUANO","RMUACO","RMUAHU","RMUANU","RMUACU"];
rtypeMap["RMUB"] = ["RMUBHO","RMUBNO","RMUBCO","RMUBHU","RMUBNU","RMUBCU"];
rtypeMap["RMUC"] = ["RMUCHO","RMUCNO","RMUCCO","RMUCHU","RMUCNU","RMUCCU"];
rtypeMap["RMUD"] = ["RMUDHO","RMUDNO","RMUDCO","RMUDHU","RMUDNU","RMUDCU"];
rtypeMap["RMPG"] = ["RMPGHH","RMPGNH","RMPGCH","RMPGHC","RMPGNC","RMPGCC"];
rtypeMap["RMTS"] = ["RMTSHY","RMTSNY","RMTSCY","RMTSHN","RMTSNN","RMTSCN"];
rtypeMap["RDUA"] = ["RDUAHO","RDUACO","RDUASO","RDUAHU","RDUACU","RDUASU"];
rtypeMap["RDUB"] = ["RDUBHO","RDUBCO","RDUBSO","RDUBHU","RDUBCU","RDUBSU"];
rtypeMap["RDUC"] = ["RDUCHO","RDUCCO","RDUCSO","RDUCHU","RDUCCU","RDUCSU"];
rtypeMap["RDUD"] = ["RDUDHO","RDUDCO","RDUDSO","RDUDHU","RDUDCU","RDUDSU"];
rtypeMap["RDS"] = ["RDSHY","RDSCY","RDSSY","RDSHN","RDSCN","RDSSN"];
rtypeMap["RDG"] = ["RDGHH","RDGCH","RDGSH","RDGHC","RDGCC","RDGSC"];
rtypeMap["RUEA"] = ["RUEAOO","RUEAOE","RUEAUO","RUEAUE"];
rtypeMap["RUEB"] = ["RUEBOO","RUEBOE","RUEBUO","RUEBUE"];
rtypeMap["RUEC"] = ["RUECOO","RUECOE","RUECUO","RUECUE"];
rtypeMap["RUED"] = ["RUEDOO","RUEDOE","RUEDUO","RUEDUE"];
rtypeMap["RUTA"] = ["RUTAOY","RUTAON","RUTAUY","RUTAUN"];
rtypeMap["RUTB"] = ["RUTBOY","RUTBON","RUTBUY","RUTBUN"];
rtypeMap["RUTC"] = ["RUTCOY","RUTCON","RUTCUY","RUTCUN"];
rtypeMap["RUTD"] = ["RUTDOY","RUTDON","RUTDUY","RUTDUN"];
rtypeMap["RUPA"] = ["RUPAOH","RUPAOC","RUPAUH","RUPAUC"];
rtypeMap["RUPB"] = ["RUPBOH","RUPBOC","RUPBUH","RUPBUC"];
rtypeMap["RUPC"] = ["RUPCOH","RUPCOC","RUPCUH","RUPCUC"];
rtypeMap["RUPD"] = ["RUPDOH","RUPDOC","RUPDUH","RUPDUC"];
rtypeMap["ROT"] = ["ROTY","ROTN"];
rtypeMap["RSHA"] = ["RSHAY","RSHAN","RSCAY","RSCAN"];
rtypeMap["RSHB"] = ["RSHBY","RSHBN","RSCBY","RSCBN"];
rtypeMap["RSHC"] = ["RSHCY","RSHCN","RSCCY","RSCCN"];
rtypeMap["RSHD"] = ["RSHDY","RSHDN","RSCDY","RSCDN"];
rtypeMap["RSHE"] = ["RSHEY","RSHEN","RSCEY","RSCEN"];
rtypeMap["RSHF"] = ["RSHFY","RSHFN","RSCFY","RSCFN"];
rtypeMap["RSHG"] = ["RSHGY","RSHGN","RSCGY","RSCGN"];
rtypeMap["RSHH"] = ["RSHHY","RSHHN","RSCHY","RSCHN"];
rtypeMap["RSHI"] = ["RSHIY","RSHIN","RSCIY","RSCIN"];
rtypeMap["RSHJ"] = ["RSHJY","RSHJN","RSCJY","RSCJN"];
rtypeMap["RSHK"] = ["RSHKY","RSHKN","RSCKY","RSCKN"];
rtypeMap["RSHL"] = ["RSHLY","RSHLN","RSCLY","RSCLN"];
rtypeMap["RSHM"] = ["RSHMY","RSHMN","RSCMY","RSCMN"];
rtypeMap["RSHN"] = ["RSHNY","RSHNN","RSCNY","RSCNN"];
rtypeMap["RSHO"] = ["RSHOY","RSHON","RSCOY","RSCON"];
rtypeMap["RNBA"] = ["RNBAH","RNBAC"];
rtypeMap["RNBB"] = ["RNBBH","RNBBC"];
rtypeMap["RNBC"] = ["RNBCH","RNBCC"];
rtypeMap["RNBD"] = ["RNBDH","RNBDC"];
rtypeMap["RNBE"] = ["RNBEH","RNBEC"];
rtypeMap["RNBF"] = ["RNBFH","RNBFC"];
rtypeMap["RNBG"] = ["RNBGH","RNBGC"];
rtypeMap["RNBH"] = ["RNBHH","RNBHC"];
rtypeMap["RNBI"] = ["RNBIH","RNBIC"];
rtypeMap["RNBJ"] = ["RNBJH","RNBJC"];
rtypeMap["RNBK"] = ["RNBKH","RNBKC"];
rtypeMap["RNBL"] = ["RNBLH","RNBLC"];
rtypeMap["RNBM"] = ["RNBMH","RNBMC"];
rtypeMap["RNBN"] = ["RNBNH","RNBNC"];
rtypeMap["RNBO"] = ["RNBOH","RNBOC"];
rtypeMap["RNC1"] = ["RNC1H","RNC1C"];
rtypeMap["RNC2"] = ["RNC2H","RNC2C"];
rtypeMap["RNC3"] = ["RNC3H","RNC3C"];
rtypeMap["RNC4"] = ["RNC4H","RNC4C"];
rtypeMap["RNC5"] = ["RNC5H","RNC5C"];
rtypeMap["RNC6"] = ["RNC6H","RNC6C"];
rtypeMap["RNC7"] = ["RNC7H","RNC7C"];
rtypeMap["RNC8"] = ["RNC8H","RNC8C"];
rtypeMap["RNC9"] = ["RNC9H","RNC9C"];
rtypeMap["RNCA"] = ["RNCAH","RNCAC"];
rtypeMap["RNCB"] = ["RNCBH","RNCBC"];
rtypeMap["RNCC"] = ["RNCCH","RNCCC"];
rtypeMap["RNCD"] = ["RNCDH","RNCDC"];
rtypeMap["RNCE"] = ["RNCEH","RNCEC"];
rtypeMap["RNCF"] = ["RNCFH","RNCFC"];
rtypeMap["RNCG"] = ["RNCGH","RNCGC"];
rtypeMap["RNCH"] = ["RNCHH","RNCHC"];
rtypeMap["RNCI"] = ["RNCIH","RNCIC"];
rtypeMap["RNCJ"] = ["RNCJH","RNCJC"];
rtypeMap["RNCK"] = ["RNCKH","RNCKC"];
rtypeMap["RNCL"] = ["RNCLH","RNCLC"];
rtypeMap["RNCM"] = ["RNCMH","RNCMC"];
rtypeMap["RNCN"] = ["RNCNH","RNCNC"];
rtypeMap["RNCO"] = ["RNCOH","RNCOC"];
rtypeMap["RNCP"] = ["RNCPH","RNCPC"];
rtypeMap["RNCQ"] = ["RNCQH","RNCQC"];
rtypeMap["RNCR"] = ["RNCRH","RNCRC"];
rtypeMap["RNCS"] = ["RNCSH","RNCSC"];
rtypeMap["RNCT"] = ["RNCTH","RNCTC"];
rtypeMap["RNCU"] = ["RNCUH","RNCUC"];


//單式

rtypeMap["R"] = ["RH","RC"];
rtypeMap["HR"] = ["HRH","HRC"];
rtypeMap["OU"] = ["OUH","OUC"];
rtypeMap["HOU"] = ["HOUH","HOUC"];
rtypeMap["M"] = ["MH","MC","MN"];
rtypeMap["HM"] = ["HMH","HMC","HMN"];
rtypeMap["AR"] = ["ARH","ARC"];
rtypeMap["AOU"] = ["AOUO","AOUU"];
rtypeMap["AM"] = ["AMH","AMC","AMN"];
rtypeMap["BR"] = ["BRH","BRC"];
rtypeMap["BOU"] = ["BOUO","BOUU"];
rtypeMap["BM"] = ["BMH","BMC","BMN"];
rtypeMap["CR"] = ["CRH","CRC"];
rtypeMap["COU"] = ["COUO","COUU"];
rtypeMap["CM"] = ["CMH","CMC","CMN"];
rtypeMap["DR"] = ["DRH","DRC"];
rtypeMap["DOU"] = ["DOUO","DOUU"];
rtypeMap["DM"] = ["DMH","DMC","DMN"];
rtypeMap["ER"] = ["ERH","ERC"];
rtypeMap["EOU"] = ["EOUO","EOUU"];
rtypeMap["EM"] = ["EMH","EMC","EMN"];
rtypeMap["FR"] = ["FRH","FRC"];
rtypeMap["FOU"] = ["FOUO","FOUU"];
rtypeMap["FM"] = ["FMH","FMC","FMN"];
rtypeMap["PD"] = ["H1C0","H2C0","H2C1","H3C0","H3C1","H3C2","H4C0","H4C1","H4C2","H4C3","H0C0","H1C1","H2C2","H3C3","H4C4","OVH","H0C1","H0C2","H1C2","H0C3","H1C3","H2C3","H0C4","H1C4","H2C4","H3C4","OVC"];
rtypeMap["HPD"] = ["HH1C0","HH2C0","HH2C1","HH3C0","HH3C1","HH3C2","HH4C0","HH4C1","HH4C2","HH4C3","HH0C0","HH1C1","HH2C2","HH3C3","HH4C4","HOVH","HH0C1","HH0C2","HH1C2","HH0C3","HH1C3","HH2C3","HH0C4","HH1C4","HH2C4","HH3C4","HOVC"];
rtypeMap["T"] = ["T01","T23","T46","OVER"];
rtypeMap["HT"] = ["HT0","HT1","HT2","HTOV"];
rtypeMap["F"] = ["FHH","FHN","FHC","FNH","FNN","FNC","FCH","FCN","FCC"];
rtypeMap["WM"] = ["WMH1","WMH2","WMH3","WMHOV","WMC1","WMC2","WMC3","WMCOV","WM0","WMN"];
rtypeMap["DC"] = ["DCHN","DCCN","DCHC"];
rtypeMap["W3"] = ["W3H","W3C","W3N"];
rtypeMap["BH"] = ["BHH","BHC"];
rtypeMap["WE"] = ["WEH","WEC"];
rtypeMap["WB"] = ["WBH","WBC"];
rtypeMap["PG"] = ["PGFH","PGLH","PGFN","PGFC","PGLC"];
rtypeMap["RC"] = ["RCFH","RCLH","RCFC","RCLC"];
rtypeMap["TS"] = ["TSY","TSN"];
rtypeMap["OUH"] = ["OUHO","OUHU"];
rtypeMap["OUC"] = ["OUCO","OUCU"];
rtypeMap["HOUH"] = ["HOUHO","HOUHU"];
rtypeMap["HOUC"] = ["HOUCO","HOUCU"];
rtypeMap["EO"] = ["EOO","EOE"];
rtypeMap["HEO"] = ["HEOO","HEOE"];
rtypeMap["SFS"] = ["H19","C19","H20","C20","H21","C21"];
rtypeMap["CS"] = ["CSH","CSC"];
rtypeMap["WN"] = ["WNH","WNC"];
rtypeMap["F2G"] = ["F2GH","F2GC","F2GN"];
rtypeMap["F3G"] = ["F3GH","F3GC","F3GN"];
rtypeMap["HG"] = ["HGH","HGC"];
rtypeMap["MG"] = ["MGH","MGC","MGN"];
rtypeMap["SB"] = ["SBH","SBC"];
rtypeMap["FG"] = ["FGS","FGH","FGN","FGP","FGF","FGO"];
rtypeMap["T3G"] = ["T3G1","T3G2","T3GN"];
rtypeMap["T1G"] = ["T1G1","T1G2","T1G3","T1G4","T1G5","T1G6","T1GN"];
rtypeMap["TK"] = ["TKH","TKC"];
rtypeMap["PA"] = ["PAH","PAC"];
rtypeMap["RCD"] = ["RCDH","RCDC"];
rtypeMap["CN"] = ["CNFH","CNLH","CNFN","CNFC","CNLC"];
rtypeMap["CD"] = ["CDFH","CDLH","CDFN","CDFC","CDLC"];
rtypeMap["YC"] = ["YCFH","YCLH","YCFN","YCFC","YCLC"];
rtypeMap["ST"] = ["STFH","STLH","STFN","STFC","STLC"];
rtypeMap["GA"] = ["GAFH","GALH","GAFN","GAFC","GALC"];
rtypeMap["OS"] = ["OSFH","OSLH","OSFN","OSFC","OSLC"];


rtypeMap["HTS"] = ["HTSY","HTSN"];
rtypeMap["EOH"] = ["EOHO","EOHE"];
rtypeMap["EOC"] = ["EOCO","EOCE"];
rtypeMap["HEOH"] = ["HEOHO","HEOHE"];
rtypeMap["HEOC"] = ["HEOCO","HEOCE"];
rtypeMap["MW"] = ["MWH","MWC","MWHOT","MWCOT","MWHPK","MWCPK"];
rtypeMap["MQ"] = ["MQH","MQC","MQHOT","MQCOT","MQHPK","MQCPK"];
rtypeMap["MOUA"] = ["MOUAHO","MOUANO","MOUACO","MOUAHU","MOUANU","MOUACU"];
rtypeMap["MOUB"] = ["MOUBHO","MOUBNO","MOUBCO","MOUBHU","MOUBNU","MOUBCU"];
rtypeMap["MOUC"] = ["MOUCHO","MOUCNO","MOUCCO","MOUCHU","MOUCNU","MOUCCU"];
rtypeMap["MOUD"] = ["MOUDHO","MOUDNO","MOUDCO","MOUDHU","MOUDNU","MOUDCU"];
rtypeMap["MPG"] = ["MPGHH","MPGNH","MPGCH","MPGHC","MPGNC","MPGCC"];
rtypeMap["MTS"] = ["MTSHY","MTSNY","MTSCY","MTSHN","MTSNN","MTSCN"];
rtypeMap["DUA"] = ["DUAHO","DUACO","DUASO","DUAHU","DUACU","DUASU"];
rtypeMap["DUB"] = ["DUBHO","DUBCO","DUBSO","DUBHU","DUBCU","DUBSU"];
rtypeMap["DUC"] = ["DUCHO","DUCCO","DUCSO","DUCHU","DUCCU","DUCSU"];
rtypeMap["DUD"] = ["DUDHO","DUDCO","DUDSO","DUDHU","DUDCU","DUDSU"];
rtypeMap["DS"] = ["DSHY","DSCY","DSSY","DSHN","DSCN","DSSN"];
rtypeMap["DG"] = ["DGHH","DGCH","DGSH","DGHC","DGCC","DGSC"];
rtypeMap["OUEA"] = ["OUEAOO","OUEAOE","OUEAUO","OUEAUE"];
rtypeMap["OUEB"] = ["OUEBOO","OUEBOE","OUEBUO","OUEBUE"];
rtypeMap["OUEC"] = ["OUECOO","OUECOE","OUECUO","OUECUE"];
rtypeMap["OUED"] = ["OUEDOO","OUEDOE","OUEDUO","OUEDUE"];
rtypeMap["OUTA"] = ["OUTAOY","OUTAON","OUTAUY","OUTAUN"];
rtypeMap["OUTB"] = ["OUTBOY","OUTBON","OUTBUY","OUTBUN"];
rtypeMap["OUTC"] = ["OUTCOY","OUTCON","OUTCUY","OUTCUN"];
rtypeMap["OUTD"] = ["OUTDOY","OUTDON","OUTDUY","OUTDUN"];
rtypeMap["OUPA"] = ["OUPAOH","OUPAOC","OUPAUH","OUPAUC"];
rtypeMap["OUPB"] = ["OUPBOH","OUPBOC","OUPBUH","OUPBUC"];
rtypeMap["OUPC"] = ["OUPCOH","OUPCOC","OUPCUH","OUPCUC"];
rtypeMap["OUPD"] = ["OUPDOH","OUPDOC","OUPDUH","OUPDUC"];
rtypeMap["OT"] = ["OTY","OTN"];
rtypeMap["OG"] = ["OGY","OGN"];


//過關
rtypeMap["PR"] = ["PRH","PRC"];
rtypeMap["HPR"] = ["HPRH","HPRC"];
rtypeMap["POU"] = ["POUH","POUC"];
rtypeMap["HPOU"] = ["HPOUH","HPOUC"];
rtypeMap["PAR"] = ["PARH","PARC"];
rtypeMap["PAOU"] = ["PAOUO","PAOUU"];
rtypeMap["PBR"] = ["PBRH","PBRC"];
rtypeMap["PBOU"] = ["PBOUO","PBOUU"];
rtypeMap["PCR"] = ["PCRH","PCRC"];
rtypeMap["PCOU"] = ["PCOUO","PCOUU"];
rtypeMap["PDR"] = ["PDRH","PDRC"];
rtypeMap["PDOU"] = ["PDOUO","PDOUU"];
rtypeMap["PER"] = ["PERH","PERC"];
rtypeMap["PEOU"] = ["PEOUO","PEOUU"];
rtypeMap["PFR"] = ["PFRH","PFRC"];
rtypeMap["PFOU"] = ["PFOUO","PFOUU"];
rtypeMap["POUH"] = ["POUHO","POUHU"];
rtypeMap["POUC"] = ["POUCO","POUCU"];
rtypeMap["HPOUH"] = ["HPOUHO","HPOUHU"];
rtypeMap["HPOUC"] = ["HPOUCO","HPOUCU"];
rtypeMap["PEO"] = ["PEOO","PEOE"];
rtypeMap["HPEO"] = ["HPEOO","HPEOE"];

//new Array("myMarkets","mainMarkets","goalMarkets","corners","otherMarkArray;
modeMap = new Array()
modeMap["R"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["OU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["M"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["AR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["AOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["AM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["BM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["CR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["COU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["CM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["DR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["DOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["ER"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["EOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["FR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["FOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["FM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};

modeMap["PD"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPD"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HT"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["F"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};

modeMap["WM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DC"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["W3"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["WE"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["WB"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PG"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["RC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["TS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["SFS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["CS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["WN"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};

modeMap["F2G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["F3G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};

modeMap["HG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["SB"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["FG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T3G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T1G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["TK"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["PA"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":true,"Others":false};
modeMap["RCD"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["CN"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":true,"Specials":true,"Others":false};
modeMap["CD"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["YC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["ST"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["GA"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["OS"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};



modeMap["HTS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EOH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EOC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEOH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEOC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MW"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["MQ"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["MOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MPG"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MTS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DS"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUE"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUT"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUP"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OT"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["OG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};




modeMap["PR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HPR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["POU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PAR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PAOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PBR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PBOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PCR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PCOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PDR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PDOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PER"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PEOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PFR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PFOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["POUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["POUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};

var Pwtype = new Array ("PR","HPR","POU","HPOU","PAR","PAOU","PBR","PBOU","PCR","PCOU","PDR","PDOU","PER","PEOU","PFR","PFOU","POUH","POUC","HPOUH","HPOUC","PEO","HPEO")


var FavMap = new Object();
FavMap["R"]  = ["R","RE","PR"] ;
FavMap["HR"] = ["HR","HRE","HPR"] ;
FavMap["OU"] = ["OU","ROU","POU"] ;
FavMap["HOU"] = ["HOU","HROU","HPOU"] ;
FavMap["M"] = ["M","RM"] ;
FavMap["HM"] = ["HM","HRM"] ;
FavMap["AR"] = ["AR","ARE","PAR"] ;
FavMap["AOU"] = ["AOU","AROU","PAOU"] ;
FavMap["AM"] = ["AM","ARM"] ;
FavMap["BR"] = ["BR","BRE","PBR"] ;
FavMap["BOU"] = ["BOU","BROU","PBOU"] ;
FavMap["BM"] = ["BM","BRM"] ;
FavMap["CR"] = ["CR","PCR"] ;
FavMap["COU"] = ["COU","PCOU"] ;
FavMap["CM"] = ["CM"] ;
FavMap["DR"] = ["DR","DRE","PDR"] ;
FavMap["DOU"] = ["DOU","DROU","POU"] ;
FavMap["DM"] = ["DM","DRM"] ;
FavMap["ER"] = ["ER","ERE","PER"] ;
FavMap["EOU"] = ["EOU","EROU","PEOU"] ;
FavMap["EM"] = ["EM","ERM"] ;
FavMap["FR"] = ["FR","PFR"] ;
FavMap["FOU"] = ["FOU","PFOU"] ;
FavMap["FM"] = ["FM","PFM"] ;
FavMap["PD"] = ["PD","RPD"] ;
FavMap["HPD"] = ["HPD","HRPD"] ;
FavMap["T"] = ["T","RT"] ;
FavMap["HT"] = ["HT","HRT"] ;
FavMap["F"] = ["F","RF"] ;
FavMap["WM"] = ["WM","RWM"] ;
FavMap["DC"] = ["DC","RDC"] ;
FavMap["W3"] = ["W3"] ;
FavMap["BH"] = ["BH","RBH"] ;
FavMap["WE"] = ["WE","RWE"] ;
FavMap["WB"] = ["WB","RWB"] ;
FavMap["PG"] = ["PG"] ;
FavMap["RC"] = ["RC"] ;
FavMap["TS"] = ["TS","RTS"] ;
FavMap["OUH"] = ["OUH","ROUH","POUH"] ;
FavMap["OUC"] = ["OUC","ROUC","POUC"] ;
FavMap["HOUH"] = ["HOUH","HRUH","HPOUH"] ;
FavMap["HOUC"] = ["HOUC","HRUC","HPOUC"] ;
FavMap["EO"] = ["EO","REO","PEO"] ;
FavMap["HEO"] = ["HEO","HREO","HPEO"] ;
FavMap["SFS"] = ["SFS"] ;
FavMap["CS"] = ["CS"] ;
FavMap["WN"] = ["WN","RWN"] ;
FavMap["F2G"] = ["F2G"] ;
FavMap["F3G"] = ["F3G"] ;
FavMap["HG"] = ["HG","RHG"] ;
FavMap["MG"] = ["MG","RMG"] ;
FavMap["SB"] = ["SB","RSB"] ;
FavMap["FG"] = ["FG"] ;
FavMap["T3G"] = ["T3G","RT3G"] ;
FavMap["T1G"] = ["T1G","RT1G"] ;
FavMap["TK"] = ["TK"] ;
FavMap["PA"] = ["PA"] ;
FavMap["RCD"] = ["RCD"] ;
FavMap["CN"] = ["CN"] ;
FavMap["CD"] = ["CD"] ;
FavMap["YC"] = ["YC"] ;
FavMap["ST"] = ["ST"] ;
FavMap["GA"] = ["GA"] ;
FavMap["OS"] = ["OS"] ;
FavMap["ARG"] = ["ARG"] ;
FavMap["BRG"] = ["BRG"] ;
FavMap["CRG"] = ["CRG"] ;
FavMap["DRG"] = ["DRG"] ;
FavMap["ERG"] = ["ERG"] ;
FavMap["FRG"] = ["FRG"] ;
FavMap["GRG"] = ["GRG"] ;
FavMap["HRG"] = ["HRG"] ;
FavMap["IRG"] = ["IRG"] ;
FavMap["JRG"] = ["JRG"] ;
FavMap["HTS"] = ["HTS"];
FavMap["RTS2"] = ["RTS2"];
FavMap["EOH"] = ["EOH"];
FavMap["EOC"] = ["EOC"];
FavMap["HEOH"] = ["HEOH"];
FavMap["HEOC"] = ["HEOC"];
FavMap["MW"] = ["MW"];
FavMap["MQ"] = ["MQ"];
FavMap["MOU"] = ["MOU","RMOU"];
FavMap["MPG"] = ["MPG","RMPG"];
FavMap["MTS"] = ["MTS","RMTS"];
FavMap["DU"] = ["DU","RDU"];
FavMap["DS"] = ["DS","RDS"];
FavMap["DG"] = ["DG","RDG"];
FavMap["OUE"] = ["OUE","ROUE"];
FavMap["OUT"] = ["OUT","ROUT"];
FavMap["OUP"] = ["OUP","ROUP"];
FavMap["OT"] = ["OT","ROT"];
FavMap["OG"] = ["OG"];
FavMap["RSHA"] = ["RSHA"];
FavMap["RSHB"] = ["RSHB"];
FavMap["RSHC"] = ["RSHC"];
FavMap["RSHD"] = ["RSHD"];
FavMap["RSHE"] = ["RSHE"];
FavMap["RSHF"] = ["RSHF"];
FavMap["RSHG"] = ["RSHG"];
FavMap["RSHH"] = ["RSHH"];
FavMap["RSHI"] = ["RSHI"];
FavMap["RSHJ"] = ["RSHJ"];
FavMap["RSHK"] = ["RSHK"];
FavMap["RSHL"] = ["RSHL"];
FavMap["RSHM"] = ["RSHM"];
FavMap["RSHN"] = ["RSHN"];
FavMap["RSHO"] = ["RSHO"];
FavMap["RNBA"] = ["RNBA"];
FavMap["RNBB"] = ["RNBB"];
FavMap["RNBC"] = ["RNBC"];
FavMap["RNBD"] = ["RNBD"];
FavMap["RNBE"] = ["RNBE"];
FavMap["RNBF"] = ["RNBF"];
FavMap["RNBG"] = ["RNBG"];
FavMap["RNBH"] = ["RNBH"];
FavMap["RNBI"] = ["RNBI"];
FavMap["RNBJ"] = ["RNBJ"];
FavMap["RNBK"] = ["RNBK"];
FavMap["RNBL"] = ["RNBL"];
FavMap["RNBM"] = ["RNBM"];
FavMap["RNBN"] = ["RNBN"];
FavMap["RNBO"] = ["RNBO"];
FavMap["RNC1"] = ["RNC1"];
FavMap["RNC2"] = ["RNC2"];
FavMap["RNC3"] = ["RNC3"];
FavMap["RNC4"] = ["RNC4"];
FavMap["RNC5"] = ["RNC5"];
FavMap["RNC6"] = ["RNC6"];
FavMap["RNC7"] = ["RNC7"];
FavMap["RNC8"] = ["RNC8"];
FavMap["RNC9"] = ["RNC9"];
FavMap["RNCA"] = ["RNCA"];
FavMap["RNCB"] = ["RNCB"];
FavMap["RNCC"] = ["RNCC"];
FavMap["RNCD"] = ["RNCD"];
FavMap["RNCE"] = ["RNCE"];
FavMap["RNCF"] = ["RNCF"];
FavMap["RNCG"] = ["RNCG"];
FavMap["RNCH"] = ["RNCH"];
FavMap["RNCI"] = ["RNCI"];
FavMap["RNCJ"] = ["RNCJ"];
FavMap["RNCK"] = ["RNCK"];
FavMap["RNCL"] = ["RNCL"];
FavMap["RNCM"] = ["RNCM"];
FavMap["RNCN"] = ["RNCN"];
FavMap["RNCO"] = ["RNCO"];
FavMap["RNCP"] = ["RNCP"];
FavMap["RNCQ"] = ["RNCQ"];
FavMap["RNCR"] = ["RNCR"];
FavMap["RNCS"] = ["RNCS"];
FavMap["RNCT"] = ["RNCT"];
FavMap["RNCU"] = ["RNCU"];
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<script>
function fastTemplate(){
		var _self=this;
		var parentClass;
		var Hashtabl=new Array();
		var dataHash=new Array();
		var keyHash=new Array();
		var SampleTable;
		var samplelayer;
		var tempTag;
		_self.init=function(obj){
			SampleTable=obj.innerHTML;
			samplelayer="";
		}
		
		_self.setParentclass=function(parentclass){
			parentClass=parentclass;
		}
		
		_self.getThis=function(varible){
			return eval(varible);
		}
		_self.setPrivate=function(varible,val){
  				eval(varible+"='"+val+"'");
  			}
		_self.addBlock=function(tag){
			var s_srt="<!-- START DYNAMIC BLOCK: "+tag+" -->";
			var e_srt="<!-- END DYNAMIC BLOCK: "+tag+" -->";
			var n_start =SampleTable.indexOf(s_srt,0);
			var n_end   =SampleTable.lastIndexOf(e_srt,SampleTable.length);
			var sampleTag =SampleTable.substring(n_start,n_end);
			
			sampleTag =sampleTag.replace(s_srt,"");
			samplelayer=SampleTable.replace(s_srt+sampleTag+e_srt,"*TAG_"+tag+"*");

			if (dataHash[tag]==undefined){
				dataHash[tag]=new Array();
				keyHash[keyHash.length]=tag;
			}
			tempTag=tag;
			dataHash[tag][dataHash[tag].length]=sampleTag;
		}
			
		_self.replace=function(oldTag,newTag){
				/*
				console.log('------------------------');
				console.log(oldTag);
				console.log(newTag);
				console.log(tempTag);
				console.log(dataHash[tempTag][dataHash[tempTag].length-1]);
				*/
				dataHash[tempTag][dataHash[tempTag].length-1]=dataHash[tempTag][dataHash[tempTag].length-1].replace(oldTag,newTag);
				//console.log(dataHash[tempTag][dataHash[tempTag].length-1]);
			}
		
		_self.fastPrint=function(){
			var output=samplelayer;
			for (var i=0;i<keyHash.length;i++){
				allLayer="";
				for (var j=0;j<dataHash[keyHash[i]].length;j++){
					allLayer+=dataHash[keyHash[i]][j];
					}
				output=output.replace("*TAG_"+keyHash[i]+"*",allLayer);
				}
				return output;
			}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
	
if (onloadQue==null){
	var browse="";
	//注意:此js必須放在最後一個js
	if (document.all){//IE getElementsByName只能用在form改為用document上
			browse="IE";
			document.getElementsByName=function(objName){
		 		var children=new Array();
		 	var obj=document.getElementsByTagName("*");
		 		for (var i=0;i<obj.length;i++){
		 			if (obj[i].name==objName){
		 				children[obj[i].id]=obj[i];
		 				 children.push(obj[i]);
		 				}
		 			}
		 		return children;
		 		}
	 	}

	if (!document.all){
		 document.all=function(){
		return document.getElementsByTagName("*");}();
	}
	function autoAddID(){ //自動把沒有加到id而有name的tag補上id同名為name
	  
		 var all=document.getElementsByTagName("*");
		 for (var i=0;i<all.length;i++){
				 	try{
					 	if (all[i].attributes["name"]!=null && all[i].id=="") all[i].id=all[i].attributes["name"].value;
					}catch(e){}
					//新增textContent
					//try{
						//alert(all[i].innerText);
					 	//if (all[i].innerText!=null && all[i].innerText!="") all[i].textContent=all[i].innerText;
					// if (all[i].textContent==null && all[i].innerText!="" && all[i].innerText!=null) all[i].textContent=all[i].innerText;
					//}catch(e){}
					//新增innertext
					try{
							if (all[i].innerText==null && all[i].textContent!="" && all[i].textContent!=null) all[i].innerText=all[i].textContent;
					}catch(e){}
					try{
						if(all[i].tagName.toUpperCase()=="IFRAME"){
							all[i].location=all[i].contentWindow.location;
							}
					}catch(e){}
			 	}
			 	/*
			 	 var xmp=document.getElementsByTagName("xmp");
			 	 var tmpDoc=document.createElement("div");
			 	 var len=xmp.length;
			 	 for (j=0;j<len;j++){
			 	 	tmpDoc.innerHTML=xmp[j].innerHTML;
			 	 	//alert(xmp[i].innerHTML);
			 	 	 var tall=tmpDoc.getElementsByTagName("*");
			 	 	 for (var i=0;i<tall.length;i++){
			 	 	 	//alert(tall[i].id);
						 	try{
							 	if (tall[i].attributes["name"]!=null && tall[i].id=="") tall[i].id=tall[i].attributes["name"].value;
							}catch(e){}
						
			 	 		}
			 	 		tmpDoc.innerHTML="<xmp>"+tmpDoc.innerHTML+"</xmp>";
			 	 	xmp[j].parentNode.replaceChild(tmpDoc.children[0],xmp[j]);
				
			 	 }
			 	 */
			 	 
		}	
		
	 var onloadQue=new Array();
	 function addonloadQue(func){
	 	onloadQue[onloadQue.length]=func;
	 	}
	 function autoStartLoad(){
	 	for(var i=0;i<onloadQue.length;i++){
						onloadQue[i]();
					}
					onloadQue=new Array();
	 	}
	
	 
	 
document.onreadystatechange = function (e) {
	//alert(document.readyState+"\n"+e.target.body.innerHTML+'ready')
	if (document.readyState=="complete"){
		//alert("-->"+document.body.attributes["ONLOAD"]);
		//alert(document.body.onload.toString());
		//showObj(document.body);
		try{
			if (document.body.onload!=null && document.body.onload!="") addonloadQue(document.body.onload);
		//document.body.removeAttribute("onload",0);
		document.body.onload=function(){};
	  if(window.onload!=null) addonloadQue(window.onload);
		window.onload=autoStartLoad;
	}catch(e){alert(e)}
		}
	//alert(document.readyState+" ready");
	}

	
	 addonloadQue(autoAddID);
	 //if(window.onload!=null) addonloadQue(window.onload);
	 //window.onload=autoStartLoad;
	 
	 function GetEvent(caller){ 
	 	var obj=new Object;
	 	caller=GetEvent.caller;
		if(browse=="IE"){ 
			//event.keycode=event.keyCode;
			var eventobj= window.event; //For IE.
			  for(var variable in eventobj){
			  	try{
			  		obj[variable]=eventobj[variable];
			  	}catch(e){}
			  	}
			  	obj.clientX=window.event.x;
			  	obj.clientY=window.event.y;
			  	obj.returnValue=function (){window.event.returnValue = false;} 
			  	obj.keyCode=window.event.keyCode;
			  	obj.keycode=window.event.keyCode;
			return obj;
			
		} 
		if(caller == null || typeof(caller) != "function") 
		return null; 
		while(caller.caller != null){ 
		caller = caller.caller; 
		} 
		var event=caller.arguments[0];
		
	//	if (event.keyCode==0){
	//		event.keycode=event.which;
	//		}
	
			  for(var variable in event){
			  	try{
			  		obj[variable]=event[variable];
			  	}catch(e){}
			  	}
			 if (event.keyCode==0){
			  	obj.keyCode=event.which;
			 }
			 obj.keycode=obj.keyCode;
			 obj.returnValue=function (){event.preventDefault();}
			 obj.x=event.clientX;
			 obj.y=event.clientY;
			 //alert(event.x+":"+event.y);
			return obj;	
			
		return obj; 
	} 
	 function GetEventTarget(event){ 
		if(browse=="IE") return event.srcElement; 
		return event.target; 
		} 
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
</script>



</head>
<body id="MFT" class="BODYMORE" onLoad="init();">

<div id="div_show" class="more_b_bg">
<div id="LoadLayer" class="more_load" style="display:none;"><img src="/images/sports/moregame/loading_pic.gif" width="38" height="38"></div>
<div class="more_three_btn">
	<!------------------------ right buttons ------------------------>
	<table class="more_right_btn">
			<tr><td><input type="button" class="more_btn_re_cn" value="刷新" onClick="btnClickEvent('Refresh');"></td></tr>
			<tr><td><input type="button" class="more_btn_close_cn" value="关闭" onClick="btnClickEvent('Close');"></td></tr>
			<tr><td><input type="button" class="more_btn_back_cn" value="返回顶部" onClick="btnClickEvent('BackToTop');"></td></tr>
	</table>
	<!------------------------ right buttons ------------------------>
</div>

<div id="more_div" class="more_over">
<table id="tab_show" border="0" cellspacing="0" cellpadding="0" class="more_table">
 	<tr class="mo_bg_h"><td>
	<table id="gameHead" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="more_title">
				<span id="title_league" name="title_league" class="more_long">*LEAGUE_NAME*</span>
				<input type="button" class="more_x" value="" onClick="closeClickEvent();">
				<span  class="more_re" onClick="reFreshClickEvent();"><span id="refreshTime" class="more_re_t">refresh</span></span>
                <input id="live_tv" type="button" class="more_tv_on" value="" onClick="liveTVClickEvent();" >
			</td>
		</tr>
	</table>
	<table id="gameInfo" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_bg_table_re">
		<tr class="more_bg1">
            <td class="more_left" colspan="5">
            <img src="/images/sports/moregame/more_n.jpg" class="more_n" *MID_DISPLAY*/>
			<span id="title_showtype" name="title_showtype" class="more_time">*RB_TIME*</span>&nbsp;&nbsp;&nbsp;<span class="more_yellow_ft">*STATUS*</span>
            </td>
		</tr>
		<tr class="more_bg2">
            <td class="more_red" *RED_H_DISPLAY*>*RED_H*</td>
			<td class="mo_rbg_1"><span id="title_team_h" name="title_team_h" class="more_team">*TEAM_H*</span></td>
            <td class="mo_rbg_2"><span id="score_h" class="more_score">*SCORE_H*</span>&nbsp;<span class="more_line">-</span>&nbsp;<span id="score_c" class="more_score2">*SCORE_C*</span></td>
            <td class="mo_rbg_3"><span id="title_team_c" name="title_team_c" class="more_team2">*TEAM_C*</span></td>
            <td class="more_red" *RED_C_DISPLAY*>*RED_C*</td>
		</tr>
	</table>
	
	<div id="gameOver" style="display:none;">
		<table border="0" cellpadding="0" cellspacing="0" class="">
			<tr class="more_no2"><td>
				此赛事暂时停止收注或已关闭
			</td></tr>
		</table>
	</div>
	<!------------------------ my markets ------------------------>
	<table id="head_myMarkets" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr onClick="playCssEvent('myMarkets',this);" class="more_hand">
			<td class="more_title2">
					<span id="mark_myMarkets" class="more_up"></span>
					<span style="float: left;">我的盘口</span>
					<span class="more_star"></span>
					<span class="more_black"><span id="count_myMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	<div id="movie_myMarkets">
			<div id="movie_myMarkets_nodata">
				<table border="0" cellpadding="0" cellspacing="0" class="">
					<tr class="more_no"><td>
						点击 ★ 图示添加赛事至我的盘口
					</td></tr>
				</table>
			</div>
			<div id="favorites_RE"></div>
			<div id="favorites_HRE"></div>
			<div id="favorites_ROU"></div>
			<div id="favorites_HROU"></div>
			<div id="favorites_RM"></div>
			<div id="favorites_HRM"></div>
            <div id="favorites_RPD"></div>
			<div id="favorites_HRPD"></div>
			<div id="favorites_ARE"></div>
			<div id="favorites_AROU"></div>
			<div id="favorites_ARM"></div>
			<div id="favorites_BRE"></div>
			<div id="favorites_BROU"></div>
			<div id="favorites_BRM"></div>
			<div id="favorites_DRE"></div>
			<div id="favorites_DROU"></div>
			<div id="favorites_DRM"></div>
			<div id="favorites_ERE"></div>
			<div id="favorites_EROU"></div>
			<div id="favorites_ERM"></div>
			<div id="favorites_RT"></div>
			<div id="favorites_HRT"></div>
            <div id="favorites_RTS"></div>
			<div id="favorites_RTS2"></div>
			<div id="favorites_ROUH"></div>
			<div id="favorites_ROUC"></div>
			<div id="favorites_HRUH"></div>
			<div id="favorites_HRUC"></div>
			<div id="favorites_REO"></div>
			<div id="favorites_HREO"></div>
            <div id="favorites_ARG"></div>
			<div id="favorites_BRG"></div>
			<div id="favorites_CRG"></div>
			<div id="favorites_DRG"></div>
			<div id="favorites_ERG"></div>
			<div id="favorites_FRG"></div>
			<div id="favorites_GRG"></div>
			<div id="favorites_HRG"></div>
			<div id="favorites_IRG"></div>
			<div id="favorites_JRG"></div>
			<div id="favorites_RF"></div>
			<div id="favorites_RWM"></div>
			<div id="favorites_RDC"></div>
            <div id="favorites_RCS"></div>
			<div id="favorites_RWN"></div>
			<div id="favorites_RMOU"></div>
			<div id="favorites_RMTS"></div>
			<div id="favorites_ROUT"></div>
			<div id="favorites_RMPG"></div>
			<div id="favorites_RHG"></div>
			<div id="favorites_RMG"></div>
			<div id="favorites_RSB"></div>
			<div id="favorites_RSHA"></div>
			<div id="favorites_RSHB"></div>
			<div id="favorites_RSHC"></div>
			<div id="favorites_RSHD"></div>
			<div id="favorites_RSHE"></div>
			<div id="favorites_RSHF"></div>
			<div id="favorites_RSHG"></div>
			<div id="favorites_RSHH"></div>
			<div id="favorites_RSHI"></div>
			<div id="favorites_RSHJ"></div>
			<div id="favorites_RSHK"></div>
			<div id="favorites_RSHL"></div>
			<div id="favorites_RSHM"></div>
			<div id="favorites_RSHN"></div>
			<div id="favorites_RSHO"></div>
			<div id="favorites_RNC1"></div>
			<div id="favorites_RNC2"></div>
			<div id="favorites_RNC3"></div>
			<div id="favorites_RNC4"></div>
			<div id="favorites_RNC5"></div>
			<div id="favorites_RNC6"></div>
			<div id="favorites_RNC7"></div>
			<div id="favorites_RNC8"></div>
			<div id="favorites_RNC9"></div>
			<div id="favorites_RNCA"></div>
			<div id="favorites_RNCB"></div>
			<div id="favorites_RNCC"></div>
			<div id="favorites_RNCD"></div>
			<div id="favorites_RNCE"></div>
			<div id="favorites_RNCF"></div>
			<div id="favorites_RNCG"></div>
			<div id="favorites_RNCH"></div>
			<div id="favorites_RNCI"></div>
			<div id="favorites_RNCJ"></div>
			<div id="favorites_RNCK"></div>
			<div id="favorites_RNCL"></div>
			<div id="favorites_RNCM"></div>
			<div id="favorites_RNCN"></div>
			<div id="favorites_RNCO"></div>
			<div id="favorites_RNCP"></div>
			<div id="favorites_RNCQ"></div>
			<div id="favorites_RNCR"></div>
			<div id="favorites_RNCS"></div>
			<div id="favorites_RNCT"></div>
			<div id="favorites_RNBA"></div>
			<div id="favorites_RNBB"></div>
			<div id="favorites_RNBC"></div>
			<div id="favorites_RNBD"></div>
			<div id="favorites_RNBE"></div>
			<div id="favorites_RNBF"></div>
			<div id="favorites_RNBG"></div>
			<div id="favorites_RNBH"></div>
			<div id="favorites_RNBI"></div>
			<div id="favorites_RNBJ"></div>
			<div id="favorites_RNBK"></div>
			<div id="favorites_RNBL"></div>
			<div id="favorites_RNBM"></div>
			<div id="favorites_RNBN"></div>
			<div id="favorites_RNBO"></div>
			<div id="favorites_RT3G"></div>
			<div id="favorites_RT1G"></div>
			<div id="favorites_RDU"></div>
			<div id="favorites_RDS"></div>
			<div id="favorites_RDG"></div>
			<div id="favorites_ROUE"></div>
			<div id="favorites_ROUP"></div>
			<div id="favorites_RWE"></div>
			<div id="favorites_RWB"></div>
			<div id="favorites_ROT"></div>
	</div>
	<!------------------------ my markets ------------------------>
	
	
	
	
	

	<!------------------------ main markets ------------------------>
	<table id="head_mainMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('mainMarkets',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_mainMarkets" class="more_up"></span>
					<span style="float: left;">主盘口</span>
					<span class="more_black"><span id="count_mainMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_mainMarkets">
			<div id="body_RE"></div>
			<div id="body_HRE"></div>
			<div id="body_ROU"></div>
			<div id="body_HROU"></div>
			<div id="body_RM"></div>
			<div id="body_HRM"></div>
            <div id="body_RPD"></div>
			<div id="body_HRPD"></div>
			<div id="body_ARE"></div>
			<div id="body_AROU"></div>
			<div id="body_ARM"></div>
			<div id="body_BRE"></div>
			<div id="body_BROU"></div>
			<div id="body_BRM"></div>
			<div id="body_DRE"></div>
			<div id="body_DROU"></div>
			<div id="body_DRM"></div>
			<div id="body_ERE"></div>
			<div id="body_EROU"></div>
			<div id="body_ERM"></div>
			<div id="body_RT"></div>
			<div id="body_HRT"></div>
            <div id="body_RTS"></div>
			<div id="body_RTS2"></div>
			<div id="body_ROUH"></div>
			<div id="body_ROUC"></div>
			<div id="body_HRUH"></div>
			<div id="body_HRUC"></div>
			<div id="body_REO"></div>
			<div id="body_HREO"></div>
            <div id="body_ARG"></div>
			<div id="body_BRG"></div>
			<div id="body_CRG"></div>
			<div id="body_DRG"></div>
			<div id="body_ERG"></div>
			<div id="body_FRG"></div>
			<div id="body_GRG"></div>
			<div id="body_HRG"></div>
			<div id="body_IRG"></div>
			<div id="body_JRG"></div>
			<div id="body_RF"></div>
			<div id="body_RWM"></div>
			<div id="body_RDC"></div>
            <div id="body_RCS"></div>
			<div id="body_RWN"></div>
			<div id="body_RMOU"></div>
			<div id="body_RMTS"></div>
			<div id="body_ROUT"></div>
			<div id="body_RMPG"></div>
			<div id="body_RSHA"></div>
			<div id="body_RSHB"></div>
			<div id="body_RSHC"></div>
			<div id="body_RSHD"></div>
			<div id="body_RSHE"></div>
			<div id="body_RSHF"></div>
			<div id="body_RSHG"></div>
			<div id="body_RSHH"></div>
			<div id="body_RSHI"></div>
			<div id="body_RSHJ"></div>
			<div id="body_RSHK"></div>
			<div id="body_RSHL"></div>
			<div id="body_RSHM"></div>
			<div id="body_RSHN"></div>
			<div id="body_RSHO"></div>
	</div>

	<!------------------------ main markets ------------------------>
    
    
    
    
    
	<!------------------------ corners ------------------------>
	<table id="head_corners" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('corners',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_corners" class="more_up"></span>
					<span style="float: left;">角球</span>
					<span class="more_black"><span id="count_corners" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_corners">
		<div id="body_RNC1"></div>
		<div id="body_RNC2"></div>
		<div id="body_RNC3"></div>
		<div id="body_RNC4"></div>
		<div id="body_RNC5"></div>
		<div id="body_RNC6"></div>
		<div id="body_RNC7"></div>
		<div id="body_RNC8"></div>
		<div id="body_RNC9"></div>
		<div id="body_RNCA"></div>
		<div id="body_RNCB"></div>
		<div id="body_RNCC"></div>
		<div id="body_RNCD"></div>
		<div id="body_RNCE"></div>
		<div id="body_RNCF"></div>
		<div id="body_RNCG"></div>
		<div id="body_RNCH"></div>
		<div id="body_RNCI"></div>
		<div id="body_RNCJ"></div>
		<div id="body_RNCK"></div>
		<div id="body_RNCL"></div>
		<div id="body_RNCM"></div>
		<div id="body_RNCN"></div>
		<div id="body_RNCO"></div>
		<div id="body_RNCP"></div>
		<div id="body_RNCQ"></div>
		<div id="body_RNCR"></div>
		<div id="body_RNCS"></div>
		<div id="body_RNCT"></div>
		<div id="body_RNCU"></div>
	</div>

	<!------------------------ corners ------------------------>
	
	
	
	
	
	<!------------------------ bookings ------------------------>
	<table id="head_bookings" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('bookings',this);" class="more_hand">
			<td class="more_title3">
				<span id="mark_bookings" class="more_up"></span>
					<span style="float: left;">罚牌</span>
					<span class="more_black"><span id="count_bookings" class="more_num">0</span></span>
			</td>
		</tr>
	</table>


	<div id="movie_bookings">
			<div id="body_RNBA"></div>
			<div id="body_RNBB"></div>
			<div id="body_RNBC"></div>
			<div id="body_RNBD"></div>
			<div id="body_RNBE"></div>
			<div id="body_RNBF"></div>
			<div id="body_RNBG"></div>
			<div id="body_RNBH"></div>
			<div id="body_RNBI"></div>
			<div id="body_RNBJ"></div>
			<div id="body_RNBK"></div>
			<div id="body_RNBL"></div>
			<div id="body_RNBM"></div>
			<div id="body_RNBN"></div>
			<div id="body_RNBO"></div>
	</div>
	<!------------------------ bookings ------------------------>

	
	
	
	
	
	<!------------------------ goal markets ------------------------>
	<table id="head_goalMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('goalMarkets',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_goalMarkets" class="more_up"></span>
					<span style="float: left;">进球盘口</span>
					<span class="more_black"><span id="count_goalMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	
	<div id="movie_goalMarkets">
			<div id="body_RHG"></div>
			<div id="body_RMG"></div>
			<div id="body_RSB"></div>
			<div id="body_RT3G"></div>
			<div id="body_RT1G"></div>
			<div id="body_RDU"></div>
			<div id="body_RDS"></div>
			<div id="body_RDG"></div>
			<div id="body_ROUE"></div>
			<div id="body_ROUP"></div>
	</div>
	<!------------------------ goal markets ------------------------>

	
	
	
	

	<!------------------------ other markets ------------------------>
	<table id="head_otherMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('otherMarkets',this);" class="more_hand">
			<td class="more_title3">					
					<span id="mark_otherMarkets" class="more_up"></span>
					<span style="float: left;">其他盘口</span>
					<span class="more_black"><span id="count_otherMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_otherMarkets">
    		 <div id="body_RWE"></div>
			 <div id="body_RWB"></div>
			 <div id="body_ROT"></div>
	</div>
	<!------------------------ other markets ------------------------>
	

	</td></tr>
</table>
</div>


<div id="div_model" style="display:none;">
	
			<!---------- RE ---------->
		 	<table id="model_RE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th colspan="2" class="more_title4">
							<span style="float: left;">让球</span>
							<span class="more_star_bg"><span id="star_RE" name="star_RE" onClick="addFavorites('RE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RE -->
				<tr class="mo_bor_bom *TR_CLASS*">
						<td id="*REH_GID*" onClick="betEvent('*GID*','REH','*IORATIO_REH*','RE');" style="cursor:pointer;"  class="*TD_CLASS_REH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_REH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_REH*</span></div></td>
						<td id="*REC_GID*" onClick="betEvent('*GID*','REC','*IORATIO_REC*','RE');" style="cursor:pointer"  class="*TD_CLASS_REC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_REC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_REC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RE -->
	
			</table>
			<!---------- RE ---------->
			
			
			
			<!---------- HRE ---------->
		 	<table id="model_HRE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">让球</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HRE" name="star_HRE" onClick="addFavorites('HRE');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRE -->
				<tr class="*TR_CLASS*">
						<td id="*HREH_HGID*" onClick="betEvent('*HGID*','HREH','*IORATIO_HREH*','HRE');" style="cursor:pointer"  class="*TD_CLASS_HREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_HREH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_HREH*</span></div></td>
						<td id="*HREC_HGID*" onClick="betEvent('*HGID*','HREC','*IORATIO_HREC*','HRE');" style="cursor:pointer"  class="*TD_CLASS_HREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_HREC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_HREC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRE -->
	
			</table>
			<!---------- HRE ---------->
			
			
			
			<!---------- ROU ---------->
		 	<table id="model_ROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">大 / 小</span>
							<span class="more_star_bg"><span id="star_ROU" name="star_ROU" onClick="addFavorites('ROU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROU -->
				<tr class="*TR_CLASS*">
						<td id="*ROUC_GID*" onClick="betEvent('*GID*','ROUC','*IORATIO_ROUC*','ROU');" style="cursor:pointer"  class="*TD_CLASS_ROUC*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUC*</span><span class="m_red_bet" title="大">*IORATIO_ROUC*</span></div></td>
						<td id="*ROUH_GID*" onClick="betEvent('*GID*','ROUH','*IORATIO_ROUH*','ROU');" style="cursor:pointer"  class="*TD_CLASS_ROUH*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUH*</span><span class="m_red_bet" title="小">*IORATIO_ROUH*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROU -->
	
			</table>
			<!---------- ROU ---------->
			
			
			
			
			<!---------- HROU ---------->
		 	<table id="model_HROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">大 / 小</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HROU" name="star_HROU" onClick="addFavorites('HROU');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HROU -->
				<tr class="*TR_CLASS*">
						<td id="*HROUC_HGID*" onClick="betEvent('*HGID*','HROUC','*IORATIO_HROUC*','HROU');" style="cursor:pointer"  class="*TD_CLASS_HROUC*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HROUC*</span><span class="m_red_bet" title="大">*IORATIO_HROUC*</span></div></td>
						<td id="*HROUH_HGID*" onClick="betEvent('*HGID*','HROUH','*IORATIO_HROUH*','HROU');" style="cursor:pointer"  class="*TD_CLASS_HROUH*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HROUH*</span><span class="m_red_bet" title="小">*IORATIO_HROUH*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HROU -->
	
			</table>
			<!---------- HROU ---------->
			
			
			
			
			<!---------- RM ---------->
		 	<table id="model_RM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">独赢</span>
							<span class="more_star_bg"><span id="star_RM" name="star_RM" onClick="addFavorites('RM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RM -->
				<tr class="*TR_CLASS*">
						<td id="*RMH_GID*" onClick="betEvent('*GID*','RMH','*IORATIO_RMH*','RM');" style="cursor:pointer"  class="*TD_CLASS_RMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RMH*</span></div></td>
						<td id="*RMN_GID*" onClick="betEvent('*GID*','RMN','*IORATIO_RMN*','RM');" style="cursor:pointer"  class="*TD_CLASS_RMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_RMN*</span></div></td>
						<td id="*RMC_GID*" onClick="betEvent('*GID*','RMC','*IORATIO_RMC*','RM');" style="cursor:pointer"  class="*TD_CLASS_RMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RM -->
	
			</table>
			<!---------- RM ---------->
			
			
			
			
			<!---------- HRM ---------->
		 	<table id="model_HRM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">独赢</span>
							<span class="more_og">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HRM" name="star_HRM" onClick="addFavorites('HRM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRM -->
				<tr class="*TR_CLASS*">
						<td id="*HRMH_HGID*" onClick="betEvent('*HGID*','HRMH','*IORATIO_HRMH*','HRM');" style="cursor:pointer"  class="*TD_CLASS_HRMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_HRMH*</span></div></td>
						<td id="*HRMN_HGID*" onClick="betEvent('*HGID*','HRMN','*IORATIO_HRMN*','HRM');" style="cursor:pointer"  class="*TD_CLASS_HRMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_HRMN*</span></div></td>
						<td id="*HRMC_HGID*" onClick="betEvent('*HGID*','HRMC','*IORATIO_HRMC*','HRM');" style="cursor:pointer"  class="*TD_CLASS_HRMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_HRMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRM -->
	
			</table>
			<!---------- HRM ---------->
			
			
			
			
			
			
			<!---------- ARE ---------->
		 	<table id="model_ARE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 让球</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HA* - *SCORE_CA*</span>

							<span class="more_star_bg"><span id="star_ARE" name="star_ARE" onClick="addFavorites('ARE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ARE -->
				<tr class="*TR_CLASS*">
						<td id="*AREH_GID*" onClick="betEvent('*GID*','AREH','*IORATIO_AREH*','ARE');" style="cursor:pointer"  class="*TD_CLASS_AREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_AREH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_AREH*</span></div></td>
						<td id="*AREC_GID*" onClick="betEvent('*GID*','AREC','*IORATIO_AREC*','ARE');" style="cursor:pointer"  class="*TD_CLASS_AREC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_AREC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_AREC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ARE -->
	
			</table>
			<!---------- ARE ---------->
			
			
			
			
			
			
			<!---------- AROU ---------->
		 	<table id="model_AROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 大 / 小</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HA* - *SCORE_CA*</span>

							<span class="more_star_bg"><span id="star_AROU" name="star_AROU" onClick="addFavorites('AROU');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: AROU -->
				<tr class="*TR_CLASS*">
						<td id="*AROUO_GID*" onClick="betEvent('*GID*','AROUO','*IORATIO_AROUO*','AROU');" style="cursor:pointer"  class="*TD_CLASS_AROUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_AROUO*</span><span class="m_red_bet" title="大">*IORATIO_AROUO*</span></div></td>
						<td id="*AROUU_GID*" onClick="betEvent('*GID*','AROUU','*IORATIO_AROUU*','AROU');" style="cursor:pointer"  class="*TD_CLASS_AROUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_AROUU*</span><span class="m_red_bet" title="小">*IORATIO_AROUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: AROU -->
	
			</table>
			<!---------- AROU ---------->
			
			
			
			
			
			
			<!---------- ARM ---------->
		 	<table id="model_ARM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 独赢</span>
							<span class="more_og"></span>
							<span class="more_text">15分钟 比分: *SCORE_HA* - *SCORE_CA*</span>
							<span class="more_star_bg"><span id="star_ARM" name="star_ARM" onClick="addFavorites('ARM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ARM -->
				<tr class="*TR_CLASS*">
						<td id="*ARMH_GID*" onClick="betEvent('*GID*','ARMH','*IORATIO_ARMH*','ARM');" style="cursor:pointer"  class="*TD_CLASS_ARMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ARMH*</span></div></td>
						<td id="*ARMN_GID*" onClick="betEvent('*GID*','ARMN','*IORATIO_ARMN*','ARM');" style="cursor:pointer"  class="*TD_CLASS_ARMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_ARMN*</span></div></td>
						<td id="*ARMC_GID*" onClick="betEvent('*GID*','ARMC','*IORATIO_ARMC*','ARM');" style="cursor:pointer"  class="*TD_CLASS_ARMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ARMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ARM -->
	
			</table>
			<!---------- ARM ---------->
			
			
			
			
			
			
			
			<!---------- BRE ---------->
		 	<table id="model_BRE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 让球</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HB* - *SCORE_CB*</span>

							<span class="more_star_bg"><span id="star_BRE" name="star_BRE" onClick="addFavorites('BRE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BRE -->
				<tr class="*TR_CLASS*">
						<td id="*BREH_GID*" onClick="betEvent('*GID*','BREH','*IORATIO_BREH*','BRE');" style="cursor:pointer"  class="*TD_CLASS_BREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_BREH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BREH*</span></div></td>
						<td id="*BREC_GID*" onClick="betEvent('*GID*','BREC','*IORATIO_BREC*','BRE');" style="cursor:pointer"  class="*TD_CLASS_BREC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_BREC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BREC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BRE -->
	
			</table>
			<!---------- BRE ---------->
			
			
			
			
			<!---------- BROU ---------->
		 	<table id="model_BROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_text">15分钟 比分: *SCORE_HB* - *SCORE_CB*</span>
							<span class="more_star_bg"><span id="star_BROU" name="star_BROU" onClick="addFavorites('BROU');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BROU -->
				<tr class="*TR_CLASS*">
						<td id="*BROUO_GID*" onClick="betEvent('*GID*','BROUO','*IORATIO_BROUO*','BROU');" style="cursor:pointer"  class="*TD_CLASS_BROUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_BROUO*</span><span class="m_red_bet" title="大">*IORATIO_BROUO*</span></div></td>
						<td id="*BROUU_GID*" onClick="betEvent('*GID*','BROUU','*IORATIO_BROUU*','BROU');" style="cursor:pointer"  class="*TD_CLASS_BROUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_BROUU*</span><span class="m_red_bet" title="小">*IORATIO_BROUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BROU -->
	
			</table>
			<!---------- BROU ---------->
			
			
			
			<!---------- BRM ---------->
		 	<table id="model_BRM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 独赢</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HB* - *SCORE_CB*</span>

							<span class="more_star_bg"><span id="star_BRM" name="star_BRM" onClick="addFavorites('BRM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BRM -->
				<tr class="*TR_CLASS*">
						<td id="*BRMH_GID*" onClick="betEvent('*GID*','BRMH','*IORATIO_BRMH*','BRM');" style="cursor:pointer"  class="*TD_CLASS_BRMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BRMH*</span></div></td>
						<td id="*BRMN_GID*" onClick="betEvent('*GID*','BRMN','*IORATIO_BRMN*','BRM');" style="cursor:pointer"  class="*TD_CLASS_BRMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_BRMN*</span></div></td>
						<td id="*BRMC_GID*" onClick="betEvent('*GID*','BRMC','*IORATIO_BRMC*','BRM');" style="cursor:pointer"  class="*TD_CLASS_BRMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BRMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BRM -->
	
			</table>
			<!---------- BRM ---------->
			
			
			<!---------- DRE ---------->
		 	<table id="model_DRE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 让球</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HD* - *SCORE_CD*</span>

							<span class="more_star_bg"><span id="star_DRE" name="star_DRE" onClick="addFavorites('DRE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DRE -->
				<tr class="*TR_CLASS*">
						<td id="*DREH_GID*" onClick="betEvent('*GID*','DREH','*IORATIO_DREH*','DRE');" style="cursor:pointer"  class="*TD_CLASS_DREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_DREH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_DREH*</span></div></td>
						<td id="*DREC_GID*" onClick="betEvent('*GID*','DREC','*IORATIO_DREC*','DRE');" style="cursor:pointer"  class="*TD_CLASS_DREC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_DREC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_DREC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DRE -->
	
			</table>
			<!---------- DRE ---------->
			
			
			
			
			
			<!---------- DROU ---------->
		 	<table id="model_DROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 大 / 小</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HD* - *SCORE_CD*</span>

							<span class="more_star_bg"><span id="star_DROU" name="star_DROU" onClick="addFavorites('DROU');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DROU -->
				<tr class="*TR_CLASS*">
						<td id="*DROUO_GID*" onClick="betEvent('*GID*','DROUO','*IORATIO_DROUO*','DROU');" style="cursor:pointer"  class="*TD_CLASS_DROUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_DROUO*</span><span class="m_red_bet" title="大">*IORATIO_DROUO*</span></div></td>
						<td id="*DROUU_GID*" onClick="betEvent('*GID*','DROUU','*IORATIO_DROUU*','DROU');" style="cursor:pointer"  class="*TD_CLASS_DROUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_DROUU*</span><span class="m_red_bet" title="小">*IORATIO_DROUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DROU -->
	
			</table>
			<!---------- DROU ---------->
			
			
			
			
			<!---------- DRM ---------->
		 	<table id="model_DRM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 独赢</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HD* - *SCORE_CD*</span>

							<span class="more_star_bg"><span id="star_DRM" name="star_DRM" onClick="addFavorites('DRM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DRM -->
				<tr class="*TR_CLASS*">
						<td id="*DRMH_GID*" onClick="betEvent('*GID*','DRMH','*IORATIO_DRMH*','DRM');" style="cursor:pointer"  class="*TD_CLASS_DRMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_DRMH*</span></div></td>
						<td id="*DRMN_GID*" onClick="betEvent('*GID*','DRMN','*IORATIO_DRMN*','DRM');" style="cursor:pointer"  class="*TD_CLASS_DRMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_DRMN*</span></div></td>
						<td id="*DRMC_GID*" onClick="betEvent('*GID*','DRMC','*IORATIO_DRMC*','DRM');" style="cursor:pointer"  class="*TD_CLASS_DRMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_DRMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DRM -->
	
			</table>
			<!---------- DRM ---------->
			
			
			<!---------- ERE ---------->
		 	<table id="model_ERE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 让球</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HE* - *SCORE_CE*</span>

							<span class="more_star_bg"><span id="star_ERE" name="star_ERE" onClick="addFavorites('ERE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ERE -->
				<tr class="*TR_CLASS*">
						<td id="*EREH_GID*" onClick="betEvent('*GID*','EREH','*IORATIO_EREH*','ERE');" style="cursor:pointer"  class="*TD_CLASS_EREH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_EREH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_EREH*</span></div></td>
						<td id="*EREC_GID*" onClick="betEvent('*GID*','EREC','*IORATIO_EREC*','ERE');" style="cursor:pointer"  class="*TD_CLASS_EREC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_EREC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_EREC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ERE -->
	
			</table>
			<!---------- ERE ---------->
			
			
			
			
			<!---------- EROU ---------->
		 	<table id="model_EROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 大 / 小</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HE* - *SCORE_CE*</span>

							<span class="more_star_bg"><span id="star_EROU" name="star_EROU" onClick="addFavorites('EROU');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: EROU -->
				<tr class="*TR_CLASS*">
						<td id="*EROUO_GID*" onClick="betEvent('*GID*','EROUO','*IORATIO_EROUO*','EROU');" style="cursor:pointer"  class="*TD_CLASS_EROUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_EROUO*</span><span class="m_red_bet" title="大">*IORATIO_EROUO*</span></div></td>
						<td id="*EROUU_GID*" onClick="betEvent('*GID*','EROUU','*IORATIO_EROUU*','EROU');" style="cursor:pointer"  class="*TD_CLASS_EROUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_EROUU*</span><span class="m_red_bet" title="小">*IORATIO_EROUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: EROU -->
	
			</table>
			<!---------- EROU ---------->
			
			
			
			
			<!---------- ERM ---------->
		 	<table id="model_ERM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 独赢</span>
							<span class="more_og"></span>

							<span class="more_text">15分钟 比分: *SCORE_HE* - *SCORE_CE*</span>

							<span class="more_star_bg"><span id="star_ERM" name="star_ERM" onClick="addFavorites('ERM');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ERM -->
				<tr class="*TR_CLASS*">
						<td id="*ERMH_GID*" onClick="betEvent('*GID*','ERMH','*IORATIO_ERMH*','ERM');" style="cursor:pointer"  class="*TD_CLASS_ERMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ERMH*</span></div></td>
						<td id="*ERMN_GID*" onClick="betEvent('*GID*','ERMN','*IORATIO_ERMN*','ERM');" style="cursor:pointer"  class="*TD_CLASS_ERMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_ERMN*</span></div></td>
						<td id="*ERMC_GID*" onClick="betEvent('*GID*','ERMC','*IORATIO_ERMC*','ERM');" style="cursor:pointer"  class="*TD_CLASS_ERMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ERMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ERM -->
	
			</table>
			<!---------- ERM ---------->



			
			
			<!---------- RPD ---------->
		 	<table id="model_RPD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">波胆</span>
							<span class="more_star_bg"><span id="star_RPD" name="star_RPD" onClick="addFavorites('RPD');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RPD -->
				<tr class="*TR_CLASS*">
						<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
						<td colspan="1" class="game_team"><span>和局</span></td>
						<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>
				</tr>
				
				
				<tr class="more_white">
						<td id="*RH1C0_GID*" onClick="betEvent('*GID*','RH1C0','*IORATIO_RH1C0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH1C0*" width="20%"><span style="float: left;">1 - 0</span><span class="m_red2" title="1 - 0">*IORATIO_RH1C0*</span></td>
						<td id="*RH2C0_GID*" onClick="betEvent('*GID*','RH2C0','*IORATIO_RH2C0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH2C0*" width="20%"><span style="float: left;">2 - 0</span><span class="m_red2" title="2 - 0">*IORATIO_RH2C0*</span></td>
						<td id="*RH0C0_GID*" onClick="betEvent('*GID*','RH0C0','*IORATIO_RH0C0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH0C0*" width="20%"><span style="float: left;">0 - 0</span><span class="m_red2" title="0 - 0">*IORATIO_RH0C0*</span></td>
						<td id="*RH0C1_GID*" onClick="betEvent('*GID*','RH0C1','*IORATIO_RH0C1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH0C1*" width="20%"><span style="float: left;">0 - 1</span><span class="m_red2" title="0 - 1">*IORATIO_RH0C1*</span></td>
						<td id="*RH0C2_GID*" onClick="betEvent('*GID*','RH0C2','*IORATIO_RH0C2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH0C2*" width="20%"><span style="float: left;">0 - 2</span><span class="m_red2" title="0 - 2">*IORATIO_RH0C2*</span></td>
				</tr>                                                                                                           
				                                                                                                                
				<tr class="more_color">                                                                                         
						<td id="*RH2C1_GID*" onClick="betEvent('*GID*','RH2C1','*IORATIO_RH2C1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH2C1*" ><span style="float: left;">2 - 1</span><span class="m_red2" title="2 - 1">*IORATIO_RH2C1*</span></td>
						<td id="*RH3C0_GID*" onClick="betEvent('*GID*','RH3C0','*IORATIO_RH3C0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH3C0*" ><span style="float: left;">3 - 0</span><span class="m_red2" title="3 - 0">*IORATIO_RH3C0*</span></td>
						<td id="*RH1C1_GID*" onClick="betEvent('*GID*','RH1C1','*IORATIO_RH1C1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH1C1*" ><span style="float: left;">1 - 1</span><span class="m_red2" title="1 - 1">*IORATIO_RH1C1*</span></td>
						<td id="*RH1C2_GID*" onClick="betEvent('*GID*','RH1C2','*IORATIO_RH1C2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH1C2*" ><span style="float: left;">1 - 2</span><span class="m_red2" title="1 - 2">*IORATIO_RH1C2*</span></td>
						<td id="*RH0C3_GID*" onClick="betEvent('*GID*','RH0C3','*IORATIO_RH0C3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH0C3*" ><span style="float: left;">0 - 3</span><span class="m_red2" title="0 - 3">*IORATIO_RH0C3*</span></td>
				</tr>                                                                                                           
				                                                                                                                
				<tr class="more_white">                                                                                         
						<td id="*RH3C1_GID*" onClick="betEvent('*GID*','RH3C1','*IORATIO_RH3C1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH3C1*" ><span style="float: left;">3 - 1</span><span class="m_red2" title="3 - 1">*IORATIO_RH3C1*</span></td>
						<td id="*RH3C2_GID*" onClick="betEvent('*GID*','RH3C2','*IORATIO_RH3C2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH3C2*" ><span style="float: left;">3 - 2</span><span class="m_red2" title="3 - 2">*IORATIO_RH3C2*</span></td>
						<td id="*RH2C2_GID*" onClick="betEvent('*GID*','RH2C2','*IORATIO_RH2C2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH2C2*" ><span style="float: left;">2 - 2</span><span class="m_red2" title="2 - 2">*IORATIO_RH2C2*</span></td>
						<td id="*RH1C3_GID*" onClick="betEvent('*GID*','RH1C3','*IORATIO_RH1C3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH1C3*" ><span style="float: left;">1 - 3</span><span class="m_red2" title="1 - 3">*IORATIO_RH1C3*</span></td>
						<td id="*RH2C3_GID*" onClick="betEvent('*GID*','RH2C3','*IORATIO_RH2C3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH2C3*" ><span style="float: left;">2 - 3</span><span class="m_red2" title="2 - 3">*IORATIO_RH2C3*</span></td>
				</tr>                                                                                                           
				                                                                                                                
				<tr class="more_color">                                                                                         
						<td id="*RH4C0_GID*" onClick="betEvent('*GID*','RH4C0','*IORATIO_RH4C0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH4C0*" ><span style="float: left;">4 - 0</span><span class="m_red2" title="4 - 0">*IORATIO_RH4C0*</span></td>
						<td id="*RH4C1_GID*" onClick="betEvent('*GID*','RH4C1','*IORATIO_RH4C1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH4C1*" ><span style="float: left;">4 - 1</span><span class="m_red2" title="4 - 1">*IORATIO_RH4C1*</span></td>
						<td id="*RH3C3_GID*" onClick="betEvent('*GID*','RH3C3','*IORATIO_RH3C3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_RH3C3*</span></td>
						<td id="*RH0C4_GID*" onClick="betEvent('*GID*','RH0C4','*IORATIO_RH0C4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH0C4*" ><span style="float: left;">0 - 4</span><span class="m_red2" title="0 - 4">*IORATIO_RH0C4*</span></td>
						<td id="*RH1C4_GID*" onClick="betEvent('*GID*','RH1C4','*IORATIO_RH1C4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH1C4*" ><span style="float: left;">1 - 4</span><span class="m_red2" title="1 - 4">*IORATIO_RH1C4*</span></td>
				</tr>
				
				<tr class="more_white">
						<td id="*RH4C2_GID*" onClick="betEvent('*GID*','RH4C2','*IORATIO_RH4C2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH4C2*" ><span style="float: left;">4 - 2</span><span class="m_red2" title="4 - 2">*IORATIO_RH4C2*</span></td>
						<td id="*RH4C3_GID*" onClick="betEvent('*GID*','RH4C3','*IORATIO_RH4C3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH4C3*" ><span style="float: left;">4 - 3</span><span class="m_red2" title="4 - 3">*IORATIO_RH4C3*</span></td>
						<td id="*RH4C4_GID*" onClick="betEvent('*GID*','RH4C4','*IORATIO_RH4C4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH4C4*" ><span style="float: left;">4 - 4</span><span class="m_red2" title="4 - 4">*IORATIO_RH4C4*</span></td>
						<td id="*RH2C4_GID*" onClick="betEvent('*GID*','RH2C4','*IORATIO_RH2C4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH2C4*" ><span style="float: left;">2 - 4</span><span class="m_red2" title="2 - 4">*IORATIO_RH2C4*</span></td>
						<td id="*RH3C4_GID*" onClick="betEvent('*GID*','RH3C4','*IORATIO_RH3C4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RH3C4*" ><span style="float: left;">3 - 4</span><span class="m_red2" title="3 - 4">*IORATIO_RH3C4*</span></td>
				</tr>
				
				<tr class="more_color">
						<td colspan="5">
                             <table border="0" cellpadding="0" cellspacing="0" class="mo_bor_bom">
                               <tr class="more_color">
                                 <td width="30%">&nbsp;</td>
                                 <td width="40%" id="*ROVH_GID*" onClick="betEvent('*GID*','ROVH','*IORATIO_ROVH*','RPD');" class="*TD_CLASS_ROVH*" style=" border-left:1px solid #C5B0A3; border-right:1px solid #C5B0A3; cursor:pointer;"><span class="m_left">其他比分</span><span class="m_red2" title="其他比分">*IORATIO_ROVH*</span></td>
                                 <td width="30%" class="more_other">&nbsp;</td>
                              </tr>
                            </table>
                        </td>
				</tr>
				<!-- END DYNAMIC BLOCK: RPD -->
	
			</table>
			<!---------- RPD ---------->
			
			
			
			
			<!---------- HRPD ---------->
		 	<table id="model_HRPD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">波胆</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HRPD" name="star_HRPD" onClick="addFavorites('HRPD');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRPD -->
				<tr class="*TR_CLASS*">
						<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
						<td colspan="1" class="game_team"><span>和局</span></td>
						<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>
				</tr>
				
				
				<tr class="more_white">
						<td id="*HRH1C0_HGID*" onClick="betEvent('*HGID*','HRH1C0','*IORATIO_HRH1C0*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH1C0*" width="20%"><span style="float: left;">1 - 0</span><span class="m_red2" title="1 - 0">*IORATIO_HRH1C0*</span></td>
						<td id="*HRH2C0_HGID*" onClick="betEvent('*HGID*','HRH2C0','*IORATIO_HRH2C0*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH2C0*" width="20%"><span style="float: left;">2 - 0</span><span class="m_red2" title="2 - 0">*IORATIO_HRH2C0*</span></td>
						<td id="*HRH0C0_HGID*" onClick="betEvent('*HGID*','HRH0C0','*IORATIO_HRH0C0*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH0C0*" width="20%"><span style="float: left;">0 - 0</span><span class="m_red2" title="0 - 0">*IORATIO_HRH0C0*</span></td>
						<td id="*HRH0C1_HGID*" onClick="betEvent('*HGID*','HRH0C1','*IORATIO_HRH0C1*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH0C1*" width="20%"><span style="float: left;">0 - 1</span><span class="m_red2" title="0 - 1">*IORATIO_HRH0C1*</span></td>
						<td id="*HRH0C2_HGID*" onClick="betEvent('*HGID*','HRH0C2','*IORATIO_HRH0C2*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH0C2*" width="20%"><span style="float: left;">0 - 2</span><span class="m_red2" title="0 - 2">*IORATIO_HRH0C2*</span></td>
				</tr>                                                                                                               
				                                                                                                                    
				<tr class="more_color">                                                                                             
						<td id="*HRH2C1_HGID*" onClick="betEvent('*HGID*','HRH2C1','*IORATIO_HRH2C1*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH2C1*" ><span style="float: left;">2 - 1</span><span class="m_red2" title="2 - 1">*IORATIO_HRH2C1*</span></td>
						<td id="*HRH3C0_HGID*" onClick="betEvent('*HGID*','HRH3C0','*IORATIO_HRH3C0*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C0*" ><span style="float: left;">3 - 0</span><span class="m_red2" title="3 - 0">*IORATIO_HRH3C0*</span></td>
						<td id="*HRH1C1_HGID*" onClick="betEvent('*HGID*','HRH1C1','*IORATIO_HRH1C1*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH1C1*" ><span style="float: left;">1 - 1</span><span class="m_red2" title="1 - 1">*IORATIO_HRH1C1*</span></td>
						<td id="*HRH1C2_HGID*" onClick="betEvent('*HGID*','HRH1C2','*IORATIO_HRH1C2*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH1C2*" ><span style="float: left;">1 - 2</span><span class="m_red2" title="1 - 2">*IORATIO_HRH1C2*</span></td>
						<td id="*HRH0C3_HGID*" onClick="betEvent('*HGID*','HRH0C3','*IORATIO_HRH0C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH0C3*" ><span style="float: left;">0 - 3</span><span class="m_red2" title="0 - 3">*IORATIO_HRH0C3*</span></td>
				</tr>                                                                                                               
				                                                                                                                    
				<tr class="more_white">                                                                                             
						<td id="*HRH3C1_HGID*" onClick="betEvent('*HGID*','HRH3C1','*IORATIO_HRH3C1*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C1*" ><span style="float: left;">3 - 1</span><span class="m_red2" title="3 - 1">*IORATIO_HRH3C1*</span></td>
						<td id="*HRH3C2_HGID*" onClick="betEvent('*HGID*','HRH3C2','*IORATIO_HRH3C2*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C2*" ><span style="float: left;">3 - 2</span><span class="m_red2" title="3 - 2">*IORATIO_HRH3C2*</span></td>
						<td id="*HRH2C2_HGID*" onClick="betEvent('*HGID*','HRH2C2','*IORATIO_HRH2C2*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH2C2*" ><span style="float: left;">2 - 2</span><span class="m_red2" title="2 - 2">*IORATIO_HRH2C2*</span></td>
						<td id="*HRH1C3_HGID*" onClick="betEvent('*HGID*','HRH1C3','*IORATIO_HRH1C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH1C3*" ><span style="float: left;">1 - 3</span><span class="m_red2" title="1 - 3">*IORATIO_HRH1C3*</span></td>
						<td id="*HRH2C3_HGID*" onClick="betEvent('*HGID*','HRH2C3','*IORATIO_HRH2C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH2C3*" ><span style="float: left;">2 - 3</span><span class="m_red2" title="2 - 3">*IORATIO_HRH2C3*</span></td>
				</tr>                                                                                                               
                
                <tr class="more_color">                                                                                             
						<td colspan="2">&nbsp;</td>
						<td id="*HRH3C3_HGID*" onClick="betEvent('*HGID*','HRH3C3','*IORATIO_HRH3C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_HRH3C3*</span></td>
						<td colspan="2">&nbsp;</td>
				</tr>
                
                <!--<tr class="more_color">                                                                                             
						<td id="*HRH4C0_HGID*" onClick="betEvent('*HGID*','HRH4C0','*IORATIO_HRH4C0*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH4C0*" ><span style="float: left;">4 - 0</span><span class="m_red2" title="4 - 0">*IORATIO_HRH4C0*</span></td>
						<td id="*HRH4C1_HGID*" onClick="betEvent('*HGID*','HRH4C1','*IORATIO_HRH4C1*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH4C1*" ><span style="float: left;">4 - 1</span><span class="m_red2" title="4 - 1">*IORATIO_HRH4C1*</span></td>
						<td id="*HRH3C3_HGID*" onClick="betEvent('*HGID*','HRH3C3','*IORATIO_HRH3C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_HRH3C3*</span></td>
						<td id="*HRH0C4_HGID*" onClick="betEvent('*HGID*','HRH0C4','*IORATIO_HRH0C4*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH0C4*" ><span style="float: left;">0 - 4</span><span class="m_red2" title="0 - 4">*IORATIO_HRH0C4*</span></td>
						<td id="*HRH1C4_HGID*" onClick="betEvent('*HGID*','HRH1C4','*IORATIO_HRH1C4*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH1C4*" ><span style="float: left;">1 - 4</span><span class="m_red2" title="1 - 4">*IORATIO_HRH1C4*</span></td>
				</tr>
				
				<tr class="more_white">
						<td id="*HRH4C2_HGID*" onClick="betEvent('*HGID*','HRH4C2','*IORATIO_HRH4C2*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH4C2*" ><span style="float: left;">4 - 2</span><span class="m_red2" title="4 - 2">*IORATIO_HRH4C2*</span></td>
						<td id="*HRH4C3_HGID*" onClick="betEvent('*HGID*','HRH4C3','*IORATIO_HRH4C3*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH4C3*" ><span style="float: left;">4 - 3</span><span class="m_red2" title="4 - 3">*IORATIO_HRH4C3*</span></td>
						<td id="*HRH4C4_HGID*" onClick="betEvent('*HGID*','HRH4C4','*IORATIO_HRH4C4*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH4C4*" ><span style="float: left;">4 - 4</span><span class="m_red2" title="4 - 4">*IORATIO_HRH4C4*</span></td>
						<td id="*HRH2C4_HGID*" onClick="betEvent('*HGID*','HRH2C4','*IORATIO_HRH2C4*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH2C4*" ><span style="float: left;">2 - 4</span><span class="m_red2" title="2 - 4">*IORATIO_HRH2C4*</span></td>
						<td id="*HRH3C4_HGID*" onClick="betEvent('*HGID*','HRH3C4','*IORATIO_HRH3C4*','HRPD');" style="cursor:pointer"  class="*TD_CLASS_HRH3C4*" ><span style="float: left;">3 - 4</span><span class="m_red2" title="3 - 4">*IORATIO_HRH3C4*</span></td>
				</tr-->
				
				<tr class="more_white">
						<td colspan="5">
                             <table border="0" cellpadding="0" cellspacing="0" class="mo_bor_bom">
                               <tr class="more_white">
                                 <td width="30%">&nbsp;</td>
                                 <td width="40%" id="*HROVH_HGID*" onClick="betEvent('*HGID*','HROVH','*IORATIO_HROVH*','HRPD');" class="*TD_CLASS_HROVH*" style=" border-left:1px solid #C5B0A3; border-right:1px solid #C5B0A3; cursor:pointer;"><span class="m_left">其他比分</span><span class="m_red2" title="其他比分">*IORATIO_HROVH*</span></td>
                                 <td width="30%" class="more_other">&nbsp;</td>
                              </tr>
                            </table>
                        </td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRPD -->
	
			</table>
			<!---------- HRPD ---------->
			
			
			
			
			
			<!---------- RT ---------->
		 	<table id="model_RT" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">总进球数</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RT" name="star_RT" onClick="addFavorites('RT');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RT -->
				<tr class="more_white">
						<td id="*RT01_GID*" onClick="betEvent('*GID*','R0~1','*IORATIO_RT01*','RT');" style="cursor:pointer"  class="*TD_CLASS_RT01*" width="25%"><span class="m_left">0 - 1</span><span class="m_red" title="0 - 1">*IORATIO_RT01*</span></td>
						<td id="*RT23_GID*" onClick="betEvent('*GID*','R2~3','*IORATIO_RT23*','RT');" style="cursor:pointer"  class="*TD_CLASS_RT23*" width="25%"><span class="m_left">2 - 3</span><span class="m_red" title="2 - 3">*IORATIO_RT23*</span></td>
						<td id="*RT46_GID*" onClick="betEvent('*GID*','R4~6','*IORATIO_RT46*','RT');" style="cursor:pointer"  class="*TD_CLASS_RT46*" width="25%"><span class="m_left">4 - 6</span><span class="m_red" title="4 - 6">*IORATIO_RT46*</span></td>
						<td id="*ROVER_GID*" onClick="betEvent('*GID*','ROVER','*IORATIO_ROVER*','RT');" style="cursor:pointer"  class="*TD_CLASS_ROVER*" width="25%"><span class="m_left">7或以上</span><span class="m_red" title="7或以上">*IORATIO_ROVER*</span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RT -->
	
			</table>
			<!---------- RT ---------->
			
			
			
			
			
			<!---------- HRT ---------->
		 	<table id="model_HRT" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">总进球数</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HRT" name="star_HRT" onClick="addFavorites('HRT');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRT -->
				<tr class="more_white">
						<td id="*HRT0_HGID*" onClick="betEvent('*HGID*','HRT0','*IORATIO_HRT0*','HRT');" style="cursor:pointer"  class="*TD_CLASS_HRT0*" width="25%"><span class="m_left">0</span><span class="m_red" title="0">*IORATIO_HRT0*</span></td>
						<td id="*HRT1_HGID*" onClick="betEvent('*HGID*','HRT1','*IORATIO_HRT1*','HRT');" style="cursor:pointer"  class="*TD_CLASS_HRT1*" width="25%"><span class="m_left">1</span><span class="m_red" title="1">*IORATIO_HRT1*</span></td>
						<td id="*HRT2_HGID*" onClick="betEvent('*HGID*','HRT2','*IORATIO_HRT2*','HRT');" style="cursor:pointer"  class="*TD_CLASS_HRT2*" width="25%"><span class="m_left">2</span><span class="m_red" title="2">*IORATIO_HRT2*</span></td>
						<td id="*HRTOV_HGID*" onClick="betEvent('*HGID*','HRTOV','*IORATIO_HRTOV*','HRT');" style="cursor:pointer"  class="*TD_CLASS_HRTOV*" width="25%"><span class="m_left">3或以上</span><span class="m_red" title="3或以上">*IORATIO_HRTOV*</span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRT -->
	
			</table>
			<!---------- HRT ---------->
			
			
			
			
			
			
			<!---------- RF ---------->
		 	<table id="model_RF" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">半场 / 全场</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RF" name="star_RF" onClick="addFavorites('RF');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RF -->
				<tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
				</tr>
				
				
				<tr class="more_white">
						<td id="*RFHH_GID*" onClick="betEvent('*GID*','RFHH','*IORATIO_RFHH*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFHH*" width="35%"><span class="m_left">主队 / 主队</span><span class="m_red" title="主队 / 主队">*IORATIO_RFHH*</span></td>
						<td id="*RFNH_GID*" onClick="betEvent('*GID*','RFNH','*IORATIO_RFNH*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFNH*" width="30%"><span class="m_left">和局 / 主队</span><span class="m_red" title="和局 / 主队">*IORATIO_RFNH*</span></td>
						<td id="*RFCH_GID*" onClick="betEvent('*GID*','RFCH','*IORATIO_RFCH*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFCH*" width="35%"><span class="m_left">客队 / 主队</span><span class="m_red" title="客队 / 主队">*IORATIO_RFCH*</span></td>
                                                                                                                    
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_color">                                                                                     
						<td id="*RFHN_GID*" onClick="betEvent('*GID*','RFHN','*IORATIO_RFHN*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFHN*" ><span class="m_left">主队 / 和局</span><span class="m_red" title="主队 / 和局">*IORATIO_RFHN*</span></td>
						<td id="*RFNN_GID*" onClick="betEvent('*GID*','RFNN','*IORATIO_RFNN*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFNN*" ><span class="m_left">和局 / 和局</span><span class="m_red" title="和局 / 和局">*IORATIO_RFNN*</span></td>
						<td id="*RFCN_GID*" onClick="betEvent('*GID*','RFCN','*IORATIO_RFCN*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFCN*" ><span class="m_left">客队 / 和局</span><span class="m_red" title="客队 / 和局">*IORATIO_RFCN*</span></td>
                                                                                                                    
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_white">                                                                                     
						<td id="*RFHC_GID*" onClick="betEvent('*GID*','RFHC','*IORATIO_RFHC*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFHC*" ><span class="m_left">主队 / 客队</span><span class="m_red" title="主队 / 客队">*IORATIO_RFHC*</span></td>
						<td id="*RFNC_GID*" onClick="betEvent('*GID*','RFNC','*IORATIO_RFNC*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFNC*" ><span class="m_left">和局 / 客队</span><span class="m_red" title="和局 / 客队">*IORATIO_RFNC*</span></td>
						<td id="*RFCC_GID*" onClick="betEvent('*GID*','RFCC','*IORATIO_RFCC*','RF');" style="cursor:pointer"  class="*TD_CLASS_RFCC*" ><span class="m_left">客队 / 客队</span><span class="m_red" title="客队 / 客队">*IORATIO_RFCC*</span></td>

				</tr>
				<!-- END DYNAMIC BLOCK: RF -->
	
			</table>
			<!---------- RF ---------->
			
			
			
			
			
			<!---------- RWM ---------->
		 	<table id="model_RWM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">净胜球数</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RWM" name="star_RWM" onClick="addFavorites('RWM');" class="star_down"></span></span>
						</th>
				</tr>

				<!-- START DYNAMIC BLOCK: RWM -->
				<tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>
				</tr>


				<tr class="more_white">
						<td id="*RWMH1_GID*" onClick="betEvent('*GID*','RWMH1','*IORATIO_RWMH1*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMH1*" width="35%"><span class="m_left">净胜1球</span><span class="m_red" title="净胜1球">*IORATIO_RWMH1*</span></td>
						<td id="*RWM0_GID*" onClick="betEvent('*GID*','RWM0','*IORATIO_RWM0*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWM0*" width="30%"><span class="m_left">0 - 0 和局</span><span class="m_red" title="0 - 0 和局">*IORATIO_RWM0*</span></td>
						<td id="*RWMC1_GID*" onClick="betEvent('*GID*','RWMC1','*IORATIO_RWMC1*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMC1*" width="35%"><span class="m_left">净胜1球</span><span class="m_red" title="净胜1球">*IORATIO_RWMC1*</span></td>

				</tr>

				<tr class="more_color">
						<td id="*RWMH2_GID*" onClick="betEvent('*GID*','RWMH2','*IORATIO_RWMH2*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMH2*" ><span class="m_left">净胜2球</span><span class="m_red" title="净胜2球">*IORATIO_RWMH2*</span></td>
					 	<td id="*RWMN_GID*" onClick="betEvent('*GID*','RWMN','*IORATIO_RWMN*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMN*" ><span class="m_left">任何进球和局</span><span class="m_red" title="任何进球和局">*IORATIO_RWMN*</span></td>
						<td id="*RWMC2_GID*" onClick="betEvent('*GID*','RWMC2','*IORATIO_RWMC2*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMC2*" ><span class="m_left">净胜2球</span><span class="m_red" title="净胜2球">*IORATIO_RWMC2*</span></td>

				</tr>

				<tr class="more_white">
						<td id="*RWMH3_GID*" onClick="betEvent('*GID*','RWMH3','*IORATIO_RWMH3*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMH3*" ><span class="m_left">净胜3球</span><span class="m_red" title="净胜3球">*IORATIO_RWMH3*</span></td>
						<td><span></span><span></span></td>
						<td id="*RWMC3_GID*" onClick="betEvent('*GID*','RWMC3','*IORATIO_RWMC3*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMC3*" ><span class="m_left">净胜3球</span><span class="m_red" title="净胜3球">*IORATIO_RWMC3*</span></td>

				</tr>

				<tr class="more_color">
						<td id="*RWMHOV_GID*" onClick="betEvent('*GID*','RWMHOV','*IORATIO_RWMHOV*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMHOV*" ><span class="m_left">净胜4球或更多</span><span class="m_red" title="净胜4球或更多">*IORATIO_RWMHOV*</span></td>
						<td><span></span><span></span></td>
						<td id="*RWMCOV_GID*" onClick="betEvent('*GID*','RWMCOV','*IORATIO_RWMCOV*','RWM');" style="cursor:pointer"  class="*TD_CLASS_RWMCOV*" ><span class="m_left">净胜4球或更多</span><span class="m_red" title="净胜4球或更多">*IORATIO_RWMCOV*</span></td>

				</tr>
				<!-- END DYNAMIC BLOCK: RWM -->

			</table>
			<!---------- RWM ---------->
			
			
			
			
			 <!---------- RMOU ---------->
	     <table id="model_RMOU" cellpadding="0" cellspacing="0" border="0" class="more_table2">
	        <tr>
	        	<th class="more_title4" colspan="3">
	        		<span style="float: left;">独赢 & 进球 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RMOU" name="star_RMOU" onClick="addFavorites('RMOU');" class="star_down"></span></span>
						</th>
					</tr>
	
	
	        <!-- START DYNAMIC BLOCK: RMOU -->
					<tr class="*DISPLAY_RMOU*">
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
					</tr>

	        <tr class="more_white *DISPLAY_RMOU*">
	           <td id="*RMOUHO_GID*" onClick="betEvent('*GID*','*RMOUHO_RTYPE*','*IORATIO_RMOUHO*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUHO*" width="35%"><span class="m_left">*STR_RMOUHO*</span><span class="m_red" title="*STR_RMOUHO*">*IORATIO_RMOUHO*</span></td>
	           <td id="*RMOUNO_GID*" onClick="betEvent('*GID*','*RMOUNO_RTYPE*','*IORATIO_RMOUNO*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUNO*" width="30%"><span class="m_left">*STR_RMOUNO*</span><span class="m_red" title="*STR_RMOUNO*">*IORATIO_RMOUNO*</span></td>
	           <td id="*RMOUCO_GID*" onClick="betEvent('*GID*','*RMOUCO_RTYPE*','*IORATIO_RMOUCO*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUCO*" width="35%"><span class="m_left">*STR_RMOUCO*</span><span class="m_red" title="*STR_RMOUCO*">*IORATIO_RMOUCO*</span></td>
	        </tr>
	        <tr class="more_color *DISPLAY_RMOU*">
	           <td id="*RMOUHU_GID*" onClick="betEvent('*GID*','*RMOUHU_RTYPE*','*IORATIO_RMOUHU*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUHU*" width="35%"><span class="m_left">*STR_RMOUHU*</span><span class="m_red" title="*STR_RMOUHU*">*IORATIO_RMOUHU*</span></td>
	           <td id="*RMOUNU_GID*" onClick="betEvent('*GID*','*RMOUNU_RTYPE*','*IORATIO_RMOUNU*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUNU*" width="30%"><span class="m_left">*STR_RMOUNU*</span><span class="m_red" title="*STR_RMOUNU*">*IORATIO_RMOUNU*</span></td>
	           <td id="*RMOUCU_GID*" onClick="betEvent('*GID*','*RMOUCU_RTYPE*','*IORATIO_RMOUCU*','*RMOU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RMOUCU*" width="35%"><span class="m_left">*STR_RMOUCU*</span><span class="m_red" title="*STR_RMOUCU*">*IORATIO_RMOUCU*</span></td>
	        </tr>
	      	<!-- END DYNAMIC BLOCK: RMOU -->
	     </table>
	     <!---------- RMOU ---------->
	
	
	
	
	     <!---------- RMTS ---------->
	     <table id="model_RMTS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
	        <tr>
	        	<th class="more_title4" colspan="3">
	          	<span style="float: left;">独赢 & 双方球队进球</span>
	          	<span class="more_og"></span>
	          	<span class="more_star_bg"><span id="star_RMTS" name="star_RMTS" onClick="addFavorites('RMTS');" class="star_down"></span></span>
	          </th>
	        </tr>
	
	        <!-- START DYNAMIC BLOCK: RMTS -->
	        <tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
					</tr>
	
	        <tr class="more_white">
	           <td id="*RMTSHY_GID*" onClick="betEvent('*GID*','RMTSHY','*IORATIO_RMTSHY*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSHY*" width="35%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_RMTSHY*</span></td>
	           <td id="*RMTSNY_GID*" onClick="betEvent('*GID*','RMTSNY','*IORATIO_RMTSNY*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSNY*" width="30%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_RMTSNY*</span></td>
	           <td id="*RMTSCY_GID*" onClick="betEvent('*GID*','RMTSCY','*IORATIO_RMTSCY*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSCY*" width="35%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_RMTSCY*</span></td>
	        </tr>
	        <tr class="more_color">
	           <td id="*RMTSHN_GID*" onClick="betEvent('*GID*','RMTSHN','*IORATIO_RMTSHN*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSHN*" width="35%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_RMTSHN*</span></td>
	           <td id="*RMTSNN_GID*" onClick="betEvent('*GID*','RMTSNN','*IORATIO_RMTSNN*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSNN*" width="30%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_RMTSNN*</span></td>
	           <td id="*RMTSCN_GID*" onClick="betEvent('*GID*','RMTSCN','*IORATIO_RMTSCN*','RMTS');" style="cursor:pointer"  class="*TD_CLASS_RMTSCN*" width="35%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_RMTSCN*</span></td>
	        </tr>
	
	      	<!-- END DYNAMIC BLOCK: RMTS -->
	     </table>
	     <!---------- RMTS ---------->
	
	
	
	
	     <!---------- ROUT ---------->
				<table id="model_ROUT" cellpadding="0" cellspacing="0" border="0" class="more_table2">
				  <tr>
				  	<th class="more_title4" colspan="2">
				    	<span style="float: left;">进球 大 / 小 & 双方球队进球</span>
				    	<span class="more_og"></span>
				    	<span class="more_star_bg"><span id="star_ROUT" name="star_ROUT" onClick="addFavorites('ROUT');" class="star_down"></span></span>
				    </th>
				  </tr>
					
	
				  <!-- START DYNAMIC BLOCK: ROUT -->
	        <tr class="*DISPLAY_ROUT*">
						<td class="game_team"><span>是</span></td>
						<td class="game_team"><span>不是</span></td>
					</tr>

				  <tr class="more_white *DISPLAY_ROUT*">
				     <td id="*ROUTOY_GID*" onClick="betEvent('*GID*','*ROUTOY_RTYPE*','*IORATIO_ROUTOY*','*ROUT_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUTOY*" width="50%"><span class="m_left">*STR_ROUTOY*</span><span class="m_red" title="*STR_ROUTOY*">*IORATIO_ROUTOY*</span></td>
				     <td id="*ROUTON_GID*" onClick="betEvent('*GID*','*ROUTON_RTYPE*','*IORATIO_ROUTON*','*ROUT_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUTON*" width="50%"><span class="m_left">*STR_ROUTON*</span><span class="m_red" title="*STR_ROUTON*">*IORATIO_ROUTON*</span></td>
				  </tr>
				  <tr class="more_color *DISPLAY_ROUT*">
				     <td id="*ROUTUY_GID*" onClick="betEvent('*GID*','*ROUTUY_RTYPE*','*IORATIO_ROUTUY*','*ROUT_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUTUY*" width="50%"><span class="m_left">*STR_ROUTUY*</span><span class="m_red" title="*STR_ROUTUY*">*IORATIO_ROUTUY*</span></td>
				     <td id="*ROUTUN_GID*" onClick="betEvent('*GID*','*ROUTUN_RTYPE*','*IORATIO_ROUTUN*','*ROUT_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUTUN*" width="50%"><span class="m_left">*STR_ROUTUN*</span><span class="m_red" title="*STR_ROUTUN*">*IORATIO_ROUTUN*</span></td>
				  </tr>
				  <!-- END DYNAMIC BLOCK: ROUT -->
	
				</table>
				<!---------- ROUT ---------->
	
	
	
	
				<!---------- RMPG ---------->
	     <table id="model_RMPG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
	        <tr>
	        	<th class="more_title4" colspan="3">
	          	<span style="float: left;">独赢 & 最先进球</span>
				    	<span class="more_og"></span>
				    	<span class="more_star_bg"><span id="star_RMPG" name="star_RMPG" onClick="addFavorites('RMPG');" class="star_down"></span></span>
				    </th>
	        </tr>
	
	        <!-- START DYNAMIC BLOCK: RMPG -->
	        <tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
					</tr>
	
	        <tr class="more_white">
	           <td id="*RMPGHH_GID*" onClick="betEvent('*GID*','RMPGHH','*IORATIO_RMPGHH*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGHH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RMPGHH*</span></div></td>
	           <td id="*RMPGNH_GID*" onClick="betEvent('*GID*','RMPGNH','*IORATIO_RMPGNH*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGNH*" width="30%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RMPGNH*</span></div></td>
	           <td id="*RMPGCH_GID*" onClick="betEvent('*GID*','RMPGCH','*IORATIO_RMPGCH*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGCH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RMPGCH*</span></div></td>
	        </tr>
	        <tr class="more_color">
	           <td id="*RMPGHC_GID*" onClick="betEvent('*GID*','RMPGHC','*IORATIO_RMPGHC*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGHC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RMPGHC*</span></div></td>
	           <td id="*RMPGNC_GID*" onClick="betEvent('*GID*','RMPGNC','*IORATIO_RMPGNC*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGNC*" width="30%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RMPGNC*</span></div></td>
	           <td id="*RMPGCC_GID*" onClick="betEvent('*GID*','RMPGCC','*IORATIO_RMPGCC*','RMPG');" style="cursor:pointer"  class="*TD_CLASS_RMPGCC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RMPGCC*</span></div></td>
	        </tr>
	
	      	<!-- END DYNAMIC BLOCK: RMPG -->
	     </table>
	     <!---------- RMPG ---------->
	
	
	
	
	    <!---------- RDC ---------->
		 	<table id="model_RDC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">双重机会</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RDC" name="star_RDC" onClick="addFavorites('RDC');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RDC -->
				<tr class="more_white">
						<td id="*RDCHN_GID*" onClick="betEvent('*GID*','RDCHN','*IORATIO_RDCHN*','RDC');" style="cursor:pointer"  class="*TD_CLASS_RDCHN*" ><div class="more_font"><span class="m_team">*TEAM_H* / 和局</span><span class="m_red_bet" title="*TEAM_H* / 和局">*IORATIO_RDCHN*</span></div></td>
				</tr>
				
				<tr class="more_color">
						<td id="*RDCCN_GID*" onClick="betEvent('*GID*','RDCCN','*IORATIO_RDCCN*','RDC');" style="cursor:pointer"  class="*TD_CLASS_RDCCN*" ><div class="more_font"><span class="m_team">*TEAM_C* / 和局</span><span class="m_red_bet" title="*TEAM_C* / 和局">*IORATIO_RDCCN*</span></div></td>
				</tr>
				
				<tr class="more_white">
						<td id="*RDCHC_GID*" onClick="betEvent('*GID*','RDCHC','*IORATIO_RDCHC*','RDC');" style="cursor:pointer"  class="*TD_CLASS_RDCHC*" ><div class="more_font"><span class="m_team">*TEAM_H* / *TEAM_C*</span><span class="m_red_bet" title="*TEAM_H* / *TEAM_C*">*IORATIO_RDCHC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RDC -->
	
			</table>
			<!---------- RDC ---------->
			
			
			
			
			<!---------- RWE ---------->
		 	<table id="model_RWE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">赢得任一半场</span>
							<span class="more_star_bg"><span id="star_RWE" name="star_RWE" onClick="addFavorites('RWE');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RWE -->
				<tr class="more_white">
						<td id="*RWEH_GID*" onClick="betEvent('*GID*','RWEH','*IORATIO_RWEH*','RWE');" style="cursor:pointer"  class="*TD_CLASS_RWEH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RWEH*</span></div></td>
						<td id="*RWEC_GID*" onClick="betEvent('*GID*','RWEC','*IORATIO_RWEC*','RWE');" style="cursor:pointer"  class="*TD_CLASS_RWEC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RWEC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RWE -->
	
			</table>
			<!---------- RWE ---------->
			
			
			
			
			
			<!---------- RWB ---------->
		 	<table id="model_RWB" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">赢得所有半场</span>
							<span class="more_star_bg"><span id="star_RWB" name="star_RWB" onClick="addFavorites('RWB');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RWB -->
				<tr class="more_white">
						<td id="*RWBH_GID*" onClick="betEvent('*GID*','RWBH','*IORATIO_RWBH*','RWB');" style="cursor:pointer"  class="*TD_CLASS_RWBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RWBH*</span></div></td>
						<td id="*RWBC_GID*" onClick="betEvent('*GID*','RWBC','*IORATIO_RWBC*','RWB');" style="cursor:pointer"  class="*TD_CLASS_RWBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RWBC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RWB -->
	
			</table>
			<!---------- RWB ---------->
			
			
			
			<!---------- ROT ---------->
			<table id="model_ROT" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
				    <span style="float: left;">加时赛</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_ROT" name="star_ROT" onClick="addFavorites('ROT');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: ROT -->

			  <tr class="more_white">
			     <td id="*ROTY_GID*" onClick="betEvent('*GID*','ROTY','*IORATIO_ROTY*','ROT');" style="cursor:pointer"  class="*TD_CLASS_ROTY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_ROTY*</span></div></td>
			     <td id="*ROTN_GID*" onClick="betEvent('*GID*','ROTN','*IORATIO_ROTN*','ROT');" style="cursor:pointer"  class="*TD_CLASS_ROTN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_ROTN*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: ROT -->

			</table>
			<!---------- ROT ---------->




			<!---------- ARG ---------->
		 	<table id="model_ARG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第一个进球</span>
							<span class="more_star_bg"><span id="star_ARG" name="star_ARG" onClick="addFavorites('ARG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ARG -->
				<tr class="more_white">
						<td id="*ARGH_GID*" onClick="betEvent('*GID*','ARGH','*IORATIO_ARGH*','ARG');" style="cursor:pointer"  class="*TD_CLASS_ARGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ARGH*</span></div></td>
						<td id="*ARGN_GID*" onClick="betEvent('*GID*','ARGN','*IORATIO_ARGN*','ARG');" style="cursor:pointer"  class="*TD_CLASS_ARGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_ARGN*</span></div></td>
						<td id="*ARGC_GID*" onClick="betEvent('*GID*','ARGC','*IORATIO_ARGC*','ARG');" style="cursor:pointer"  class="*TD_CLASS_ARGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ARGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: ARG -->
	
			</table>
			<!---------- ARG ---------->
			
			
			
			
			<!---------- BRG ---------->
		 	<table id="model_BRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第二个进球</span>
							<span class="more_star_bg"><span id="star_BRG" name="star_BRG" onClick="addFavorites('BRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BRG -->
				<tr class="more_white">
						<td id="*BRGH_GID*" onClick="betEvent('*GID*','BRGH','*IORATIO_BRGH*','BRG');" style="cursor:pointer"  class="*TD_CLASS_BRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BRGH*</span></div></td>
						<td id="*BRGN_GID*" onClick="betEvent('*GID*','BRGN','*IORATIO_BRGN*','BRG');" style="cursor:pointer"  class="*TD_CLASS_BRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_BRGN*</span></div></td>
						<td id="*BRGC_GID*" onClick="betEvent('*GID*','BRGC','*IORATIO_BRGC*','BRG');" style="cursor:pointer"  class="*TD_CLASS_BRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: BRG -->
	
			</table>
			<!---------- BRG ---------->
			
			
			
			
			
			<!---------- CRG ---------->
		 	<table id="model_CRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第三个进球</span>
							<span class="more_star_bg"><span id="star_CRG" name="star_CRG" onClick="addFavorites('CRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CRG -->
				<tr class="more_white">
						<td id="*CRGH_GID*" onClick="betEvent('*GID*','CRGH','*IORATIO_CRGH*','CRG');" style="cursor:pointer"  class="*TD_CLASS_CRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CRGH*</span></div></td>
						<td id="*CRGN_GID*" onClick="betEvent('*GID*','CRGN','*IORATIO_CRGN*','CRG');" style="cursor:pointer"  class="*TD_CLASS_CRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_CRGN*</span></div></td>
						<td id="*CRGC_GID*" onClick="betEvent('*GID*','CRGC','*IORATIO_CRGC*','CRG');" style="cursor:pointer"  class="*TD_CLASS_CRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: CRG -->
	
			</table>
			<!---------- CRG ---------->		
			
			
			<!---------- DRG ---------->
		 	<table id="model_DRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第四个进球</span>
							<span class="more_star_bg"><span id="star_DRG" name="star_DRG" onClick="addFavorites('DRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DRG -->
				<tr class="more_white">
						<td id="*DRGH_GID*" onClick="betEvent('*GID*','DRGH','*IORATIO_DRGH*','DRG');" style="cursor:pointer"  class="*TD_CLASS_DRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_DRGH*</span></div></td>
						<td id="*DRGN_GID*" onClick="betEvent('*GID*','DRGN','*IORATIO_DRGN*','DRG');" style="cursor:pointer"  class="*TD_CLASS_DRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_DRGN*</span></div></td>
						<td id="*DRGC_GID*" onClick="betEvent('*GID*','DRGC','*IORATIO_DRGC*','DRG');" style="cursor:pointer"  class="*TD_CLASS_DRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_DRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: DRG -->
	
			</table>
			<!---------- DRG ---------->
			
			
			
			<!---------- ERG ---------->    
		 	<table id="model_ERG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第五个进球</span>
							<span class="more_star_bg"><span id="star_ERG" name="star_ERG" onClick="addFavorites('ERG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ERG -->
				<tr class="more_white">
						<td id="*ERGH_GID*" onClick="betEvent('*GID*','ERGH','*IORATIO_ERGH*','ERG');" style="cursor:pointer"  class="*TD_CLASS_ERGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ERGH*</span></div></td>
						<td id="*ERGN_GID*" onClick="betEvent('*GID*','ERGN','*IORATIO_ERGN*','ERG');" style="cursor:pointer"  class="*TD_CLASS_ERGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_ERGN*</span></div></td>
						<td id="*ERGC_GID*" onClick="betEvent('*GID*','ERGC','*IORATIO_ERGC*','ERG');" style="cursor:pointer"  class="*TD_CLASS_ERGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ERGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: ERG -->
	
			</table>
			<!---------- ERG ---------->
			
			
			
			<!---------- FRG ---------->    
		 	<table id="model_FRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第六个进球</span>
							<span class="more_star_bg"><span id="star_FRG" name="star_FRG" onClick="addFavorites('FRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: FRG -->
				<tr class="more_white">
						<td id="*FRGH_GID*" onClick="betEvent('*GID*','FRGH','*IORATIO_FRGH*','FRG');" style="cursor:pointer"  class="*TD_CLASS_FRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_FRGH*</span></div></td>
						<td id="*FRGN_GID*" onClick="betEvent('*GID*','FRGN','*IORATIO_FRGN*','FRG');" style="cursor:pointer"  class="*TD_CLASS_FRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_FRGN*</span></div></td>
						<td id="*FRGC_GID*" onClick="betEvent('*GID*','FRGC','*IORATIO_FRGC*','FRG');" style="cursor:pointer"  class="*TD_CLASS_FRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_FRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: FRG -->
	
			</table>
			<!---------- FRG ---------->
			
			
			
			<!---------- GRG ---------->  
		 	<table id="model_GRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第七个进球</span>
							<span class="more_star_bg"><span id="star_GRG" name="star_GRG" onClick="addFavorites('GRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: GRG -->
				<tr class="more_white">
						<td id="*GRGH_GID*" onClick="betEvent('*GID*','GRGH','*IORATIO_GRGH*','GRG');" style="cursor:pointer"  class="*TD_CLASS_GRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_GRGH*</span></div></td>
						<td id="*GRGN_GID*" onClick="betEvent('*GID*','GRGN','*IORATIO_GRGN*','GRG');" style="cursor:pointer"  class="*TD_CLASS_GRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_GRGN*</span></div></td>
						<td id="*GRGC_GID*" onClick="betEvent('*GID*','GRGC','*IORATIO_GRGC*','GRG');" style="cursor:pointer"  class="*TD_CLASS_GRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_GRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: GRG -->
	
			</table>
			<!---------- GRG ---------->
			
			
			
			
			<!---------- HRG ---------->     
		 	<table id="model_HRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第八个进球</span>
							<span class="more_star_bg"><span id="star_HRG" name="star_HRG" onClick="addFavorites('HRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRG -->
				<tr class="more_white">
						<td id="*HRGH_GID*" onClick="betEvent('*GID*','HRGH','*IORATIO_HRGH*','HRG');" style="cursor:pointer"  class="*TD_CLASS_HRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_HRGH*</span></div></td>
						<td id="*HRGN_GID*" onClick="betEvent('*GID*','HRGN','*IORATIO_HRGN*','HRG');" style="cursor:pointer"  class="*TD_CLASS_HRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_HRGN*</span></div></td>
						<td id="*HRGC_GID*" onClick="betEvent('*GID*','HRGC','*IORATIO_HRGC*','HRG');" style="cursor:pointer"  class="*TD_CLASS_HRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_HRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: HRG -->
	
			</table>
			<!---------- HRG ---------->
			
			
			
			
			<!---------- IRG ---------->   
		 	<table id="model_IRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第九个进球</span>
							<span class="more_star_bg"><span id="star_IRG" name="star_IRG" onClick="addFavorites('IRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: IRG -->
				<tr class="more_white">
						<td id="*IRGH_GID*" onClick="betEvent('*GID*','IRGH','*IORATIO_IRGH*','IRG');" style="cursor:pointer"  class="*TD_CLASS_IRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_IRGH*</span></div></td>
						<td id="*IRGN_GID*" onClick="betEvent('*GID*','IRGN','*IORATIO_IRGN*','IRG');" style="cursor:pointer"  class="*TD_CLASS_IRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_IRGN*</span></div></td>
						<td id="*IRGC_GID*" onClick="betEvent('*GID*','IRGC','*IORATIO_IRGC*','IRG');" style="cursor:pointer"  class="*TD_CLASS_IRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_IRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: IRG -->
	
			</table>
			<!---------- IRG ---------->
			
			
			
			
			<!---------- JRG ---------->    
		 	<table id="model_JRG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第十个进球</span>
							<span class="more_star_bg"><span id="star_JRG" name="star_JRG" onClick="addFavorites('JRG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: JRG -->
				<tr class="more_white">
						<td id="*JRGH_GID*" onClick="betEvent('*GID*','JRGH','*IORATIO_JRGH*','JRG');" style="cursor:pointer"  class="*TD_CLASS_JRGH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_JRGH*</span></div></td>
						<td id="*JRGN_GID*" onClick="betEvent('*GID*','JRGN','*IORATIO_JRGN*','JRG');" style="cursor:pointer"  class="*TD_CLASS_JRGN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_JRGN*</span></div></td>
						<td id="*JRGC_GID*" onClick="betEvent('*GID*','JRGC','*IORATIO_JRGC*','JRG');" style="cursor:pointer"  class="*TD_CLASS_JRGC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_JRGC*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: JRG -->
	
			</table>
			<!---------- JRG ---------->
			
			
			
			<!---------- RTS ---------->   
		 	<table id="model_RTS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">双方球队进球</span>
							<span class="more_star_bg"><span id="star_RTS" name="star_RTS" onClick="addFavorites('RTS');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RTS -->
				<tr class="more_white">
						<td id="*RTSY_GID*" onClick="betEvent('*GID*','RTSY','*IORATIO_RTSY*','RTS');" style="cursor:pointer"  class="*TD_CLASS_RTSY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RTSY*</span></div></td>
						<td id="*RTSN_GID*" onClick="betEvent('*GID*','RTSN','*IORATIO_RTSN*','RTS');" style="cursor:pointer"  class="*TD_CLASS_RTSN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RTSN*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: RTS -->
	
			</table>
			<!---------- RTS ---------->
			
			
			
			
			<!---------- RTS2 ---------->
			<table id="model_RTS2" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
			    	<span style="float: left;">双方球队进球</span>
						<span class="more_og2">&nbsp;- 下半场</span>
						<span class="more_star_bg"><span id="star_RTS2" name="star_RTS2" onClick="addFavorites('RTS2');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RTS2 -->

			  <tr class="more_white">
			     <td id="*RTS2Y_GID*" onClick="betEvent('*GID*','RTS2Y','*IORATIO_RTS2Y*','RTS2');" style="cursor:pointer"  class="*TD_CLASS_RTS2Y*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RTS2Y*</span></div></td>
			     <td id="*RTS2N_GID*" onClick="betEvent('*GID*','RTS2N','*IORATIO_RTS2N*','RTS2');" style="cursor:pointer"  class="*TD_CLASS_RTS2N*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RTS2N*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: RTS2 -->

			</table>
			<!---------- RTS2 ---------->



			<!---------- ROUH ---------->      
		 	<table id="model_ROUH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
                            <span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> - 大 / 小</span></span>
							<span class="more_star_bg"><span id="star_ROUH" name="star_ROUH" onClick="addFavorites('ROUH');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROUH -->
				<tr class="more_white">
						<td id="*ROUHO_GID*" onClick="betEvent('*GID*','ROUHO','*IORATIO_ROUHO*','ROUH');" style="cursor:pointer"  class="*TD_CLASS_ROUHO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUHO*</span><span class="m_red_bet" title="大">*IORATIO_ROUHO*</span></div></td>
						<td id="*ROUHU_GID*" onClick="betEvent('*GID*','ROUHU','*IORATIO_ROUHU*','ROUH');" style="cursor:pointer"  class="*TD_CLASS_ROUHU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUHU*</span><span class="m_red_bet" title="小">*IORATIO_ROUHU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROUH -->
	
			</table>
			<!---------- ROUH ---------->
			
			
			
			<!---------- ROUC ---------->   
		 	<table id="model_ROUC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
                            <span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> - 大 / 小</span></span>
							<span class="more_star_bg"><span id="star_ROUC" name="star_ROUC" onClick="addFavorites('ROUC');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROUC -->
				<tr class="more_white">
						<td id="*ROUCO_GID*" onClick="betEvent('*GID*','ROUCO','*IORATIO_ROUCO*','ROUC');" style="cursor:pointer"  class="*TD_CLASS_ROUCO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUCO*</span><span class="m_red_bet" title="大">*IORATIO_ROUCO*</span></div></td>
						<td id="*ROUCU_GID*" onClick="betEvent('*GID*','ROUCU','*IORATIO_ROUCU*','ROUC');" style="cursor:pointer"  class="*TD_CLASS_ROUCU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUCU*</span><span class="m_red_bet" title="小">*IORATIO_ROUCU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROUC -->
	
			</table>
			<!---------- ROUC ---------->
			
			
			
			
			<!---------- HRUH ---------->   
		 	<table id="model_HRUH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
							<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> - 大 / 小</span> - 上半场</span>
							<span class="more_star_bg"><span id="star_HRUH" name="star_HRUH" onClick="addFavorites('HRUH');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRUH -->
				<tr class="more_white">
						<td id="*HRUHO_HGID*" onClick="betEvent('*HGID*','HRUHO','*IORATIO_HRUHO*','HRUH');" style="cursor:pointer"  class="*TD_CLASS_HRUHO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HRUHO*</span><span class="m_red_bet" title="大">*IORATIO_HRUHO*</span></div></td>
						<td id="*HRUHU_HGID*" onClick="betEvent('*HGID*','HRUHU','*IORATIO_HRUHU*','HRUH');" style="cursor:pointer"  class="*TD_CLASS_HRUHU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HRUHU*</span><span class="m_red_bet" title="小">*IORATIO_HRUHU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRUH -->
	
			</table>
			<!---------- HRUH ---------->
			
			
			
			<!---------- HRUC ---------->   
		 	<table id="model_HRUC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
							<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> - 大 / 小</span> - 上半场</span>
							<span class="more_star_bg"><span id="star_HRUC" name="star_HRUC" onClick="addFavorites('HRUC');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HRUC -->
				<tr class="more_white">
						<td id="*HRUCO_HGID*" onClick="betEvent('*HGID*','HRUCO','*IORATIO_HRUCO*','HRUC');" style="cursor:pointer"  class="*TD_CLASS_HRUCO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HRUCO*</span><span class="m_red_bet" title="大">*IORATIO_HRUCO*</span></div></td>
						<td id="*HRUCU_HGID*" onClick="betEvent('*HGID*','HRUCU','*IORATIO_HRUCU*','HRUC');" style="cursor:pointer"  class="*TD_CLASS_HRUCU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HRUCU*</span><span class="m_red_bet" title="小">*IORATIO_HRUCU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HRUC -->
	
			</table>
			<!---------- HRUC ---------->
			
			<!---------- REO ---------->     
		 	<table id="model_REO" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">单 / 双</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_REO" name="star_REO" onClick="addFavorites('REO');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: REO -->
				<tr class="more_white">
						<td id="*REOO_GID*" onClick="betEvent('*GID*','RODD','*IORATIO_REOO*','REO');" style="cursor:pointer"  class="*TD_CLASS_REOO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_REOO*</span></div></td>
						<td id="*REOE_GID*" onClick="betEvent('*GID*','REVEN','*IORATIO_REOE*','REO');" style="cursor:pointer"  class="*TD_CLASS_REOE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_REOE*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: REO -->
	
			</table>
			<!---------- REO ---------->			


			<!---------- HREO ---------->     
		 	<table id="model_HREO" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">单 / 双</span>
                            <span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HREO" name="star_HREO" onClick="addFavorites('HREO');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HREO -->
				<tr class="more_white">
						<td id="*HREOO_HGID*" onClick="betEvent('*HGID*','HRODD','*IORATIO_HREOO*','HREO');" style="cursor:pointer"  class="*TD_CLASS_HREOO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_HREOO*</span></div></td>
						<td id="*HREOE_HGID*" onClick="betEvent('*HGID*','HREVEN','*IORATIO_HREOE*','HREO');" style="cursor:pointer"  class="*TD_CLASS_HREOE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_HREOE*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HREO -->
	
			</table>
			<!---------- HREO ---------->			

			<!---------- RCS ---------->     
		 	<table id="model_RCS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">零失球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RCS" name="star_RCS" onClick="addFavorites('RCS');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RCS -->
				<tr class="more_white">
						<td id="*RCSH_GID*" onClick="betEvent('*GID*','RCSH','*IORATIO_RCSH*','RCS');" style="cursor:pointer"  class="*TD_CLASS_RCSH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RCSH*</span></div></td>
						<td id="*RCSC_GID*" onClick="betEvent('*GID*','RCSC','*IORATIO_RCSC*','RCS');" style="cursor:pointer"  class="*TD_CLASS_RCSC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RCSC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RCS -->
	
			</table>
			<!---------- RCS ---------->
			
			
			
			
			<!---------- RWN ---------->    
		 	<table id="model_RWN" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">零失球获胜</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RWN" name="star_RWN" onClick="addFavorites('RWN');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RWN -->
				<tr class="more_white">
						<td id="*RWNH_GID*" onClick="betEvent('*GID*','RWNH','*IORATIO_RWNH*','RWN');" style="cursor:pointer"  class="*TD_CLASS_RWNH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RWNH*</span></div></td>
						<td id="*RWNC_GID*" onClick="betEvent('*GID*','RWNC','*IORATIO_RWNC*','RWN');" style="cursor:pointer"  class="*TD_CLASS_RWNC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RWNC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RWN -->
			</table>
			<!---------- RWN ---------->




			<!---------- RSHA ---------->
			<table id="model_RSHA" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第一个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHA" name="star_RSHA" onClick="addFavorites('RSHA');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHA -->
			  <tr class="more_white">
			     <td id="*RSHAY_GID*" onClick="betEvent('*GID*','RSHAY','*IORATIO_RSHAY*','RSHA');" style="cursor:pointer"  class="*TD_CLASS_RSHAY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHAY*</span></div></td>
			     <td id="*RSHAN_GID*" onClick="betEvent('*GID*','RSHAN','*IORATIO_RSHAN*','RSHA');" style="cursor:pointer"  class="*TD_CLASS_RSHAN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHAN*</span></div></td>
			     <td id="*RSCAY_GID*" onClick="betEvent('*GID*','RSCAY','*IORATIO_RSCAY*','RSCA');" style="cursor:pointer"  class="*TD_CLASS_RSCAY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCAY*</span></div></td>
			     <td id="*RSCAN_GID*" onClick="betEvent('*GID*','RSCAN','*IORATIO_RSCAN*','RSCA');" style="cursor:pointer"  class="*TD_CLASS_RSCAN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCAN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHA -->
			</table>
			<!---------- RSHA ---------->




			<!---------- RSHB ---------->
			<table id="model_RSHB" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第二个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHB" name="star_RSHB" onClick="addFavorites('RSHB');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHB -->
			  <tr class="more_white">
			     <td id="*RSHBY_GID*" onClick="betEvent('*GID*','RSHBY','*IORATIO_RSHBY*','RSHB');" style="cursor:pointer"  class="*TD_CLASS_RSHBY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHBY*</span></div></td>
			     <td id="*RSHBN_GID*" onClick="betEvent('*GID*','RSHBN','*IORATIO_RSHBN*','RSHB');" style="cursor:pointer"  class="*TD_CLASS_RSHBN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHBN*</span></div></td>
			     <td id="*RSCBY_GID*" onClick="betEvent('*GID*','RSCBY','*IORATIO_RSCBY*','RSCB');" style="cursor:pointer"  class="*TD_CLASS_RSCBY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCBY*</span></div></td>
			     <td id="*RSCBN_GID*" onClick="betEvent('*GID*','RSCBN','*IORATIO_RSCBN*','RSCB');" style="cursor:pointer"  class="*TD_CLASS_RSCBN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCBN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHB -->
			</table>
			<!---------- RSHB ---------->




			<!---------- RSHC ---------->
			<table id="model_RSHC" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第三个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHC" name="star_RSHC" onClick="addFavorites('RSHC');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHC -->
			  <tr class="more_white">
			     <td id="*RSHCY_GID*" onClick="betEvent('*GID*','RSHCY','*IORATIO_RSHCY*','RSHC');" style="cursor:pointer"  class="*TD_CLASS_RSHCY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHCY*</span></div></td>
			     <td id="*RSHCN_GID*" onClick="betEvent('*GID*','RSHCN','*IORATIO_RSHCN*','RSHC');" style="cursor:pointer"  class="*TD_CLASS_RSHCN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHCN*</span></div></td>
			     <td id="*RSCCY_GID*" onClick="betEvent('*GID*','RSCCY','*IORATIO_RSCCY*','RSCC');" style="cursor:pointer"  class="*TD_CLASS_RSCCY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCCY*</span></div></td>
			     <td id="*RSCCN_GID*" onClick="betEvent('*GID*','RSCCN','*IORATIO_RSCCN*','RSCC');" style="cursor:pointer"  class="*TD_CLASS_RSCCN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCCN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHC -->
			</table>
			<!---------- RSHC ---------->




			<!---------- RSHD ---------->
			<table id="model_RSHD" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第四个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHD" name="star_RSHD" onClick="addFavorites('RSHD');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHD -->
			  <tr class="more_white">
			     <td id="*RSHDY_GID*" onClick="betEvent('*GID*','RSHDY','*IORATIO_RSHDY*','RSHD');" style="cursor:pointer"  class="*TD_CLASS_RSHDY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHDY*</span></div></td>
			     <td id="*RSHDN_GID*" onClick="betEvent('*GID*','RSHDN','*IORATIO_RSHDN*','RSHD');" style="cursor:pointer"  class="*TD_CLASS_RSHDN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHDN*</span></div></td>
			     <td id="*RSCDY_GID*" onClick="betEvent('*GID*','RSCDY','*IORATIO_RSCDY*','RSCD');" style="cursor:pointer"  class="*TD_CLASS_RSCDY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCDY*</span></div></td>
			     <td id="*RSCDN_GID*" onClick="betEvent('*GID*','RSCDN','*IORATIO_RSCDN*','RSCD');" style="cursor:pointer"  class="*TD_CLASS_RSCDN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCDN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHD -->
			</table>
			<!---------- RSHD ---------->




			<!---------- RSHE ---------->
			<table id="model_RSHE" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第五个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHE" name="star_RSHE" onClick="addFavorites('RSHE');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHE -->
			  <tr class="more_white">
			     <td id="*RSHEY_GID*" onClick="betEvent('*GID*','RSHEY','*IORATIO_RSHEY*','RSHE');" style="cursor:pointer"  class="*TD_CLASS_RSHEY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHEY*</span></div></td>
			     <td id="*RSHEN_GID*" onClick="betEvent('*GID*','RSHEN','*IORATIO_RSHEN*','RSHE');" style="cursor:pointer"  class="*TD_CLASS_RSHEN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHEN*</span></div></td>
			     <td id="*RSCEY_GID*" onClick="betEvent('*GID*','RSCEY','*IORATIO_RSCEY*','RSCE');" style="cursor:pointer"  class="*TD_CLASS_RSCEY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCEY*</span></div></td>
			     <td id="*RSCEN_GID*" onClick="betEvent('*GID*','RSCEN','*IORATIO_RSCEN*','RSCE');" style="cursor:pointer"  class="*TD_CLASS_RSCEN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCEN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHE -->
			</table>
			<!---------- RSHE ---------->




			<!---------- RSHF ---------->
			<table id="model_RSHF" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第六个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHF" name="star_RSHF" onClick="addFavorites('RSHF');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHF -->
			  <tr class="more_white">
			     <td id="*RSHFY_GID*" onClick="betEvent('*GID*','RSHFY','*IORATIO_RSHFY*','RSHF');" style="cursor:pointer"  class="*TD_CLASS_RSHFY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHFY*</span></div></td>
			     <td id="*RSHFN_GID*" onClick="betEvent('*GID*','RSHFN','*IORATIO_RSHFN*','RSHF');" style="cursor:pointer"  class="*TD_CLASS_RSHFN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHFN*</span></div></td>
			     <td id="*RSCFY_GID*" onClick="betEvent('*GID*','RSCFY','*IORATIO_RSCFY*','RSCF');" style="cursor:pointer"  class="*TD_CLASS_RSCFY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCFY*</span></div></td>
			     <td id="*RSCFN_GID*" onClick="betEvent('*GID*','RSCFN','*IORATIO_RSCFN*','RSCF');" style="cursor:pointer"  class="*TD_CLASS_RSCFN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCFN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHF -->
			</table>
			<!---------- RSHF ---------->




			<!---------- RSHG ---------->
			<table id="model_RSHG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第七个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHG" name="star_RSHG" onClick="addFavorites('RSHG');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHG -->
			  <tr class="more_white">
			     <td id="*RSHGY_GID*" onClick="betEvent('*GID*','RSHGY','*IORATIO_RSHGY*','RSHG');" style="cursor:pointer"  class="*TD_CLASS_RSHGY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHGY*</span></div></td>
			     <td id="*RSHGN_GID*" onClick="betEvent('*GID*','RSHGN','*IORATIO_RSHGN*','RSHG');" style="cursor:pointer"  class="*TD_CLASS_RSHGN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHGN*</span></div></td>
			     <td id="*RSCGY_GID*" onClick="betEvent('*GID*','RSCGY','*IORATIO_RSCGY*','RSCG');" style="cursor:pointer"  class="*TD_CLASS_RSCGY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCGY*</span></div></td>
			     <td id="*RSCGN_GID*" onClick="betEvent('*GID*','RSCGN','*IORATIO_RSCGN*','RSCG');" style="cursor:pointer"  class="*TD_CLASS_RSCGN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCGN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHG -->
			</table>
			<!---------- RSHG ---------->




			<!---------- RSHH ---------->
			<table id="model_RSHH" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第八个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHH" name="star_RSHH" onClick="addFavorites('RSHH');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHH -->
			  <tr class="more_white">
			     <td id="*RSHHY_GID*" onClick="betEvent('*GID*','RSHHY','*IORATIO_RSHHY*','RSHH');" style="cursor:pointer"  class="*TD_CLASS_RSHHY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHHY*</span></div></td>
			     <td id="*RSHHN_GID*" onClick="betEvent('*GID*','RSHHN','*IORATIO_RSHHN*','RSHH');" style="cursor:pointer"  class="*TD_CLASS_RSHHN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHHN*</span></div></td>
			     <td id="*RSCHY_GID*" onClick="betEvent('*GID*','RSCHY','*IORATIO_RSCHY*','RSCH');" style="cursor:pointer"  class="*TD_CLASS_RSCHY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCHY*</span></div></td>
			     <td id="*RSCHN_GID*" onClick="betEvent('*GID*','RSCHN','*IORATIO_RSCHN*','RSCH');" style="cursor:pointer"  class="*TD_CLASS_RSCHN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCHN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHH -->
			</table>
			<!---------- RSHH ---------->




			<!---------- RSHI ---------->
			<table id="model_RSHI" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第九个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHI" name="star_RSHI" onClick="addFavorites('RSHI');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHI -->
			  <tr class="more_white">
			     <td id="*RSHIY_GID*" onClick="betEvent('*GID*','RSHIY','*IORATIO_RSHIY*','RSHI');" style="cursor:pointer"  class="*TD_CLASS_RSHIY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHIY*</span></div></td>
			     <td id="*RSHIN_GID*" onClick="betEvent('*GID*','RSHIN','*IORATIO_RSHIN*','RSHI');" style="cursor:pointer"  class="*TD_CLASS_RSHIN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHIN*</span></div></td>
			     <td id="*RSCIY_GID*" onClick="betEvent('*GID*','RSCIY','*IORATIO_RSCIY*','RSCI');" style="cursor:pointer"  class="*TD_CLASS_RSCIY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCIY*</span></div></td>
			     <td id="*RSCIN_GID*" onClick="betEvent('*GID*','RSCIN','*IORATIO_RSCIN*','RSCI');" style="cursor:pointer"  class="*TD_CLASS_RSCIN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCIN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHI -->
			</table>
			<!---------- RSHI ---------->




			<!---------- RSHJ ---------->
			<table id="model_RSHJ" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHJ" name="star_RSHJ" onClick="addFavorites('RSHJ');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHJ -->
			  <tr class="more_white">
			     <td id="*RSHJY_GID*" onClick="betEvent('*GID*','RSHJY','*IORATIO_RSHJY*','RSHJ');" style="cursor:pointer"  class="*TD_CLASS_RSHJY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHJY*</span></div></td>
			     <td id="*RSHJN_GID*" onClick="betEvent('*GID*','RSHJN','*IORATIO_RSHJN*','RSHJ');" style="cursor:pointer"  class="*TD_CLASS_RSHJN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHJN*</span></div></td>
			     <td id="*RSCJY_GID*" onClick="betEvent('*GID*','RSCJY','*IORATIO_RSCJY*','RSCJ');" style="cursor:pointer"  class="*TD_CLASS_RSCJY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCJY*</span></div></td>
			     <td id="*RSCJN_GID*" onClick="betEvent('*GID*','RSCJN','*IORATIO_RSCJN*','RSCJ');" style="cursor:pointer"  class="*TD_CLASS_RSCJN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCJN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHJ -->
			</table>
			<!---------- RSHJ ---------->




			<!---------- RSHK ---------->
			<table id="model_RSHK" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十一个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHK" name="star_RSHK" onClick="addFavorites('RSHK');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHK -->
			  <tr class="more_white">
			     <td id="*RSHKY_GID*" onClick="betEvent('*GID*','RSHKY','*IORATIO_RSHKY*','RSHK');" style="cursor:pointer"  class="*TD_CLASS_RSHKY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHKY*</span></div></td>
			     <td id="*RSHKN_GID*" onClick="betEvent('*GID*','RSHKN','*IORATIO_RSHKN*','RSHK');" style="cursor:pointer"  class="*TD_CLASS_RSHKN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHKN*</span></div></td>
			     <td id="*RSCKY_GID*" onClick="betEvent('*GID*','RSCKY','*IORATIO_RSCKY*','RSCK');" style="cursor:pointer"  class="*TD_CLASS_RSCKY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCKY*</span></div></td>
			     <td id="*RSCKN_GID*" onClick="betEvent('*GID*','RSCKN','*IORATIO_RSCKN*','RSCK');" style="cursor:pointer"  class="*TD_CLASS_RSCKN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCKN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHK -->
			</table>
			<!---------- RSHK ---------->




			<!---------- RSHL ---------->
			<table id="model_RSHL" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十二个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHL" name="star_RSHL" onClick="addFavorites('RSHL');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHL -->
			  <tr class="more_white">
			     <td id="*RSHLY_GID*" onClick="betEvent('*GID*','RSHLY','*IORATIO_RSHLY*','RSHL');" style="cursor:pointer"  class="*TD_CLASS_RSHLY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHLY*</span></div></td>
			     <td id="*RSHLN_GID*" onClick="betEvent('*GID*','RSHLN','*IORATIO_RSHLN*','RSHL');" style="cursor:pointer"  class="*TD_CLASS_RSHLN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHLN*</span></div></td>
			     <td id="*RSCLY_GID*" onClick="betEvent('*GID*','RSCLY','*IORATIO_RSCLY*','RSCL');" style="cursor:pointer"  class="*TD_CLASS_RSCLY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCLY*</span></div></td>
			     <td id="*RSCLN_GID*" onClick="betEvent('*GID*','RSCLN','*IORATIO_RSCLN*','RSCL');" style="cursor:pointer"  class="*TD_CLASS_RSCLN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCLN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHL -->
			</table>
			<!---------- RSHL ---------->




			<!---------- RSHM ---------->
			<table id="model_RSHM" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十三个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHM" name="star_RSHM" onClick="addFavorites('RSHM');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHM -->
			  <tr class="more_white">
			     <td id="*RSHMY_GID*" onClick="betEvent('*GID*','RSHMY','*IORATIO_RSHMY*','RSHM');" style="cursor:pointer"  class="*TD_CLASS_RSHMY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHMY*</span></div></td>
			     <td id="*RSHMN_GID*" onClick="betEvent('*GID*','RSHMN','*IORATIO_RSHMN*','RSHM');" style="cursor:pointer"  class="*TD_CLASS_RSHMN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHMN*</span></div></td>
			     <td id="*RSCMY_GID*" onClick="betEvent('*GID*','RSCMY','*IORATIO_RSCMY*','RSCM');" style="cursor:pointer"  class="*TD_CLASS_RSCMY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCMY*</span></div></td>
			     <td id="*RSCMN_GID*" onClick="betEvent('*GID*','RSCMN','*IORATIO_RSCMN*','RSCM');" style="cursor:pointer"  class="*TD_CLASS_RSCMN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCMN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHM -->
			</table>
			<!---------- RSHM ---------->




			<!---------- RSHN ---------->
			<table id="model_RSHN" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十四个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHN" name="star_RSHN" onClick="addFavorites('RSHN');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHN -->
			  <tr class="more_white">
			     <td id="*RSHNY_GID*" onClick="betEvent('*GID*','RSHNY','*IORATIO_RSHNY*','RSHN');" style="cursor:pointer"  class="*TD_CLASS_RSHNY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHNY*</span></div></td>
			     <td id="*RSHNN_GID*" onClick="betEvent('*GID*','RSHNN','*IORATIO_RSHNN*','RSHN');" style="cursor:pointer"  class="*TD_CLASS_RSHNN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHNN*</span></div></td>
			     <td id="*RSCNY_GID*" onClick="betEvent('*GID*','RSCNY','*IORATIO_RSCNY*','RSCN');" style="cursor:pointer"  class="*TD_CLASS_RSCNY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCNY*</span></div></td>
			     <td id="*RSCNN_GID*" onClick="betEvent('*GID*','RSCNN','*IORATIO_RSCNN*','RSCN');" style="cursor:pointer"  class="*TD_CLASS_RSCNN*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCNN*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHN -->
			</table>
			<!---------- RSHN ---------->




			<!---------- RSHO ---------->
			<table id="model_RSHO" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">第十五个点球大战</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RSHO" name="star_RSHO" onClick="addFavorites('RSHO');" class="star_down"></span></span>
					</th>
			  </tr>

				<tr>
					<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
					<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <!-- START DYNAMIC BLOCK: RSHO -->
			  <tr class="more_white">
			     <td id="*RSHOY_GID*" onClick="betEvent('*GID*','RSHOY','*IORATIO_RSHOY*','RSHO');" style="cursor:pointer"  class="*TD_CLASS_RSHOY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSHOY*</span></div></td>
			     <td id="*RSHON_GID*" onClick="betEvent('*GID*','RSHON','*IORATIO_RSHON*','RSHO');" style="cursor:pointer"  class="*TD_CLASS_RSHON*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSHON*</span></div></td>
			     <td id="*RSCOY_GID*" onClick="betEvent('*GID*','RSCOY','*IORATIO_RSCOY*','RSCO');" style="cursor:pointer"  class="*TD_CLASS_RSCOY*" width="25%"><div class="more_font"><span class="m_team">进球</span><span class="m_red_bet" title="进球">*IORATIO_RSCOY*</span></div></td>
			     <td id="*RSCON_GID*" onClick="betEvent('*GID*','RSCON','*IORATIO_RSCON*','RSCO');" style="cursor:pointer"  class="*TD_CLASS_RSCON*" width="25%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RSCON*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RSHO -->
			</table>
			<!---------- RSHO ---------->



						
      <!---------- RNBA ---------->
			<table id="model_RNBA" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第一张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBA" name="star_RNBA" onClick="addFavorites('RNBA');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBA -->
			  <tr class="more_white" style="*DISPLAY_RNBA*">
			     <td id="*RNBAH_GID*" onClick="betEvent('*GID*','RNBAH','*IORATIO_RNBAH*','RNBA');" style="cursor:pointer"  class="*TD_CLASS_RNBAH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBAH*</span></div></td>
			     <td id="*RNBAC_GID*" onClick="betEvent('*GID*','RNBAC','*IORATIO_RNBAC*','RNBA');" style="cursor:pointer"  class="*TD_CLASS_RNBAC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBAC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBA -->
			</table>
			<!---------- RNBA ---------->



						
      <!---------- RNBB ---------->
			<table id="model_RNBB" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBB" name="star_RNBB" onClick="addFavorites('RNBB');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBB -->
			  <tr class="more_white" style="*DISPLAY_RNBB*">
			     <td id="*RNBBH_GID*" onClick="betEvent('*GID*','RNBBH','*IORATIO_RNBBH*','RNBB');" style="cursor:pointer"  class="*TD_CLASS_RNBBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBBH*</span></div></td>
			     <td id="*RNBBC_GID*" onClick="betEvent('*GID*','RNBBC','*IORATIO_RNBBC*','RNBB');" style="cursor:pointer"  class="*TD_CLASS_RNBBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBBC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBB -->
			</table>
			<!---------- RNBB ---------->



						
      <!---------- RNBC ---------->
			<table id="model_RNBC" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第三张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBC" name="star_RNBC" onClick="addFavorites('RNBC');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBC -->
			  <tr class="more_white" style="*DISPLAY_RNBC*">
			     <td id="*RNBCH_GID*" onClick="betEvent('*GID*','RNBCH','*IORATIO_RNBCH*','RNBC');" style="cursor:pointer"  class="*TD_CLASS_RNBCH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBCH*</span></div></td>
			     <td id="*RNBCC_GID*" onClick="betEvent('*GID*','RNBCC','*IORATIO_RNBCC*','RNBC');" style="cursor:pointer"  class="*TD_CLASS_RNBCC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBCC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBC -->
			</table>
			<!---------- RNBC ---------->



						
      <!---------- RNBD ---------->
			<table id="model_RNBD" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第四张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBD" name="star_RNBD" onClick="addFavorites('RNBD');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBD -->
			  <tr class="more_white" style="*DISPLAY_RNBD*">
			     <td id="*RNBDH_GID*" onClick="betEvent('*GID*','RNBDH','*IORATIO_RNBDH*','RNBD');" style="cursor:pointer"  class="*TD_CLASS_RNBDH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBDH*</span></div></td>
			     <td id="*RNBDC_GID*" onClick="betEvent('*GID*','RNBDC','*IORATIO_RNBDC*','RNBD');" style="cursor:pointer"  class="*TD_CLASS_RNBDC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBDC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBD -->
			</table>
			<!---------- RNBD ---------->



						
      <!---------- RNBE ---------->
			<table id="model_RNBE" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第五张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBE" name="star_RNBE" onClick="addFavorites('RNBE');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBE -->
			  <tr class="more_white" style="*DISPLAY_RNBE*">
			     <td id="*RNBEH_GID*" onClick="betEvent('*GID*','RNBEH','*IORATIO_RNBEH*','RNBE');" style="cursor:pointer"  class="*TD_CLASS_RNBEH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBEH*</span></div></td>
			     <td id="*RNBEC_GID*" onClick="betEvent('*GID*','RNBEC','*IORATIO_RNBEC*','RNBE');" style="cursor:pointer"  class="*TD_CLASS_RNBEC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBEC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBE -->
			</table>
			<!---------- RNBE ---------->



						
      <!---------- RNBF ---------->
			<table id="model_RNBF" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第六张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBF" name="star_RNBF" onClick="addFavorites('RNBF');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBF -->
			  <tr class="more_white" style="*DISPLAY_RNBF*">
			     <td id="*RNBFH_GID*" onClick="betEvent('*GID*','RNBFH','*IORATIO_RNBFH*','RNBF');" style="cursor:pointer"  class="*TD_CLASS_RNBFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBFH*</span></div></td>
			     <td id="*RNBFC_GID*" onClick="betEvent('*GID*','RNBFC','*IORATIO_RNBFC*','RNBF');" style="cursor:pointer"  class="*TD_CLASS_RNBFC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBFC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBF -->
			</table>
			<!---------- RNBF ---------->



						
      <!---------- RNBG ---------->
			<table id="model_RNBG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第七张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBG" name="star_RNBG" onClick="addFavorites('RNBG');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBG -->
			  <tr class="more_white" style="*DISPLAY_RNBG*">
			     <td id="*RNBGH_GID*" onClick="betEvent('*GID*','RNBGH','*IORATIO_RNBGH*','RNBG');" style="cursor:pointer"  class="*TD_CLASS_RNBGH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBGH*</span></div></td>
			     <td id="*RNBGC_GID*" onClick="betEvent('*GID*','RNBGC','*IORATIO_RNBGC*','RNBG');" style="cursor:pointer"  class="*TD_CLASS_RNBGC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBGC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBG -->
			</table>
			<!---------- RNBG ---------->



						
      <!---------- RNBH ---------->
			<table id="model_RNBH" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第八张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBH" name="star_RNBH" onClick="addFavorites('RNBH');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBH -->
			  <tr class="more_white" style="*DISPLAY_RNBH*">
			     <td id="*RNBHH_GID*" onClick="betEvent('*GID*','RNBHH','*IORATIO_RNBHH*','RNBH');" style="cursor:pointer"  class="*TD_CLASS_RNBHH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBHH*</span></div></td>
			     <td id="*RNBHC_GID*" onClick="betEvent('*GID*','RNBHC','*IORATIO_RNBHC*','RNBH');" style="cursor:pointer"  class="*TD_CLASS_RNBHC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBHC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBH -->
			</table>
			<!---------- RNBH ---------->



						
      <!---------- RNBI ---------->
			<table id="model_RNBI" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第九张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBI" name="star_RNBI" onClick="addFavorites('RNBI');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBI -->
			  <tr class="more_white" style="*DISPLAY_RNBI*">
			     <td id="*RNBIH_GID*" onClick="betEvent('*GID*','RNBIH','*IORATIO_RNBIH*','RNBI');" style="cursor:pointer"  class="*TD_CLASS_RNBIH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBIH*</span></div></td>
			     <td id="*RNBIC_GID*" onClick="betEvent('*GID*','RNBIC','*IORATIO_RNBIC*','RNBI');" style="cursor:pointer"  class="*TD_CLASS_RNBIC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBIC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBI -->
			</table>
			<!---------- RNBI ---------->



						
      <!---------- RNBJ ---------->
			<table id="model_RNBJ" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBJ" name="star_RNBJ" onClick="addFavorites('RNBJ');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBJ -->
			  <tr class="more_white" style="*DISPLAY_RNBJ*">
			     <td id="*RNBJH_GID*" onClick="betEvent('*GID*','RNBJH','*IORATIO_RNBJH*','RNBJ');" style="cursor:pointer"  class="*TD_CLASS_RNBJH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBJH*</span></div></td>
			     <td id="*RNBJC_GID*" onClick="betEvent('*GID*','RNBJC','*IORATIO_RNBJC*','RNBJ');" style="cursor:pointer"  class="*TD_CLASS_RNBJC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBJC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBJ -->
			</table>
			<!---------- RNBJ ---------->



						
      <!---------- RNBK ---------->
			<table id="model_RNBK" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十一张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBK" name="star_RNBK" onClick="addFavorites('RNBK');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBK -->
			  <tr class="more_white" style="*DISPLAY_RNBK*">
			     <td id="*RNBKH_GID*" onClick="betEvent('*GID*','RNBKH','*IORATIO_RNBKH*','RNBK');" style="cursor:pointer"  class="*TD_CLASS_RNBKH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBKH*</span></div></td>
			     <td id="*RNBKC_GID*" onClick="betEvent('*GID*','RNBKC','*IORATIO_RNBKC*','RNBK');" style="cursor:pointer"  class="*TD_CLASS_RNBKC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBKC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBK -->
			</table>
			<!---------- RNBK ---------->



						
      <!---------- RNBL ---------->
			<table id="model_RNBL" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十二张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBL" name="star_RNBL" onClick="addFavorites('RNBL');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBL -->
			  <tr class="more_white" style="*DISPLAY_RNBL*">
			     <td id="*RNBLH_GID*" onClick="betEvent('*GID*','RNBLH','*IORATIO_RNBLH*','RNBL');" style="cursor:pointer"  class="*TD_CLASS_RNBLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBLH*</span></div></td>
			     <td id="*RNBLC_GID*" onClick="betEvent('*GID*','RNBLC','*IORATIO_RNBLC*','RNBL');" style="cursor:pointer"  class="*TD_CLASS_RNBLC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBLC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBL -->
			</table>
			<!---------- RNBL ---------->



						
      <!---------- RNBM ---------->
			<table id="model_RNBM" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十三张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBM" name="star_RNBM" onClick="addFavorites('RNBM');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBM -->
			  <tr class="more_white" style="*DISPLAY_RNBM*">
			     <td id="*RNBMH_GID*" onClick="betEvent('*GID*','RNBMH','*IORATIO_RNBMH*','RNBM');" style="cursor:pointer"  class="*TD_CLASS_RNBMH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBMH*</span></div></td>
			     <td id="*RNBMC_GID*" onClick="betEvent('*GID*','RNBMC','*IORATIO_RNBMC*','RNBM');" style="cursor:pointer"  class="*TD_CLASS_RNBMC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBMC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBM -->
			</table>
			<!---------- RNBM ---------->



						
      <!---------- RNBN ---------->
			<table id="model_RNBN" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十四张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBN" name="star_RNBN" onClick="addFavorites('RNBN');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBN -->
			  <tr class="more_white" style="*DISPLAY_RNBN*">
			     <td id="*RNBNH_GID*" onClick="betEvent('*GID*','RNBNH','*IORATIO_RNBNH*','RNBN');" style="cursor:pointer"  class="*TD_CLASS_RNBNH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBNH*</span></div></td>
			     <td id="*RNBNC_GID*" onClick="betEvent('*GID*','RNBNC','*IORATIO_RNBNC*','RNBN');" style="cursor:pointer"  class="*TD_CLASS_RNBNC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBNC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBN -->
			</table>
			<!---------- RNBN ---------->



						
      <!---------- RNBO ---------->
			<table id="model_RNBO" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十五张罚牌</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNBO" name="star_RNBO" onClick="addFavorites('RNBO');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNBO -->
			  <tr class="more_white" style="*DISPLAY_RNBO*">
			     <td id="*RNBOH_GID*" onClick="betEvent('*GID*','RNBOH','*IORATIO_RNBOH*','RNBO');" style="cursor:pointer"  class="*TD_CLASS_RNBOH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNBOH*</span></div></td>
			     <td id="*RNBOC_GID*" onClick="betEvent('*GID*','RNBOC','*IORATIO_RNBOC*','RNBO');" style="cursor:pointer"  class="*TD_CLASS_RNBOC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNBOC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNBO -->
			</table>
			<!---------- RNBO ---------->


						
						
			<!---------- RNC1 ---------->
			<table id="model_RNC1" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第一个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC1" name="star_RNC1" onClick="addFavorites('RNC1');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC1 -->
			  <tr class="more_white" style="*DISPLAY_RNC1*">
			     <td id="*RNC1H_GID*" onClick="betEvent('*GID*','RNC1H','*IORATIO_RNC1H*','RNC1');" style="cursor:pointer"  class="*TD_CLASS_RNC1H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC1H*</span></div></td>
			     <td id="*RNC1C_GID*" onClick="betEvent('*GID*','RNC1C','*IORATIO_RNC1C*','RNC1');" style="cursor:pointer"  class="*TD_CLASS_RNC1C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC1C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC1 -->
			</table>
			<!---------- RNC1 ---------->


						
						
			<!---------- RNC2 ---------->
			<table id="model_RNC2" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC2" name="star_RNC2" onClick="addFavorites('RNC2');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC2 -->
			  <tr class="more_white" style="*DISPLAY_RNC2*">
			     <td id="*RNC2H_GID*" onClick="betEvent('*GID*','RNC2H','*IORATIO_RNC2H*','RNC2');" style="cursor:pointer"  class="*TD_CLASS_RNC2H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC2H*</span></div></td>
			     <td id="*RNC2C_GID*" onClick="betEvent('*GID*','RNC2C','*IORATIO_RNC2C*','RNC2');" style="cursor:pointer"  class="*TD_CLASS_RNC2C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC2C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC2 -->
			</table>
			<!---------- RNC2 ---------->


						
						
			<!---------- RNC3 ---------->
			<table id="model_RNC3" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第三个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC3" name="star_RNC3" onClick="addFavorites('RNC3');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC3 -->
			  <tr class="more_white" style="*DISPLAY_RNC3*">
			     <td id="*RNC3H_GID*" onClick="betEvent('*GID*','RNC3H','*IORATIO_RNC3H*','RNC3');" style="cursor:pointer"  class="*TD_CLASS_RNC3H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC3H*</span></div></td>
			     <td id="*RNC3C_GID*" onClick="betEvent('*GID*','RNC3C','*IORATIO_RNC3C*','RNC3');" style="cursor:pointer"  class="*TD_CLASS_RNC3C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC3C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC3 -->
			</table>
			<!---------- RNC3 ---------->


						
						
			<!---------- RNC4 ---------->
			<table id="model_RNC4" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第四个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC4" name="star_RNC4" onClick="addFavorites('RNC4');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC4 -->
			  <tr class="more_white" style="*DISPLAY_RNC4*">
			     <td id="*RNC4H_GID*" onClick="betEvent('*GID*','RNC4H','*IORATIO_RNC4H*','RNC4');" style="cursor:pointer"  class="*TD_CLASS_RNC4H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC4H*</span></div></td>
			     <td id="*RNC4C_GID*" onClick="betEvent('*GID*','RNC4C','*IORATIO_RNC4C*','RNC4');" style="cursor:pointer"  class="*TD_CLASS_RNC4C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC4C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC4 -->
			</table>
			<!---------- RNC4 ---------->


						
						
			<!---------- RNC5 ---------->
			<table id="model_RNC5" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第五个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC5" name="star_RNC5" onClick="addFavorites('RNC5');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC5 -->
			  <tr class="more_white" style="*DISPLAY_RNC5*">
			     <td id="*RNC5H_GID*" onClick="betEvent('*GID*','RNC5H','*IORATIO_RNC5H*','RNC5');" style="cursor:pointer"  class="*TD_CLASS_RNC5H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC5H*</span></div></td>
			     <td id="*RNC5C_GID*" onClick="betEvent('*GID*','RNC5C','*IORATIO_RNC5C*','RNC5');" style="cursor:pointer"  class="*TD_CLASS_RNC5C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC5C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC5 -->
			</table>
			<!---------- RNC5 ---------->


						
						
			<!---------- RNC6 ---------->
			<table id="model_RNC6" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第六个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC6" name="star_RNC6" onClick="addFavorites('RNC6');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC6 -->
			  <tr class="more_white" style="*DISPLAY_RNC6*">
			     <td id="*RNC6H_GID*" onClick="betEvent('*GID*','RNC6H','*IORATIO_RNC6H*','RNC6');" style="cursor:pointer"  class="*TD_CLASS_RNC6H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC6H*</span></div></td>
			     <td id="*RNC6C_GID*" onClick="betEvent('*GID*','RNC6C','*IORATIO_RNC6C*','RNC6');" style="cursor:pointer"  class="*TD_CLASS_RNC6C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC6C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC6 -->
			</table>
			<!---------- RNC6 ---------->


						
						
			<!---------- RNC7 ---------->
			<table id="model_RNC7" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第七个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC7" name="star_RNC7" onClick="addFavorites('RNC7');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC7 -->
			  <tr class="more_white" style="*DISPLAY_RNC7*">
			     <td id="*RNC7H_GID*" onClick="betEvent('*GID*','RNC7H','*IORATIO_RNC7H*','RNC7');" style="cursor:pointer"  class="*TD_CLASS_RNC7H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC7H*</span></div></td>
			     <td id="*RNC7C_GID*" onClick="betEvent('*GID*','RNC7C','*IORATIO_RNC7C*','RNC7');" style="cursor:pointer"  class="*TD_CLASS_RNC7C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC7C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC7 -->
			</table>
			<!---------- RNC7 ---------->


						
						
			<!---------- RNC8 ---------->
			<table id="model_RNC8" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第八个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC8" name="star_RNC8" onClick="addFavorites('RNC8');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC8 -->
			  <tr class="more_white" style="*DISPLAY_RNC8*">
			     <td id="*RNC8H_GID*" onClick="betEvent('*GID*','RNC8H','*IORATIO_RNC8H*','RNC8');" style="cursor:pointer"  class="*TD_CLASS_RNC8H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC8H*</span></div></td>
			     <td id="*RNC8C_GID*" onClick="betEvent('*GID*','RNC8C','*IORATIO_RNC8C*','RNC8');" style="cursor:pointer"  class="*TD_CLASS_RNC8C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC8C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC8 -->
			</table>
			<!---------- RNC8 ---------->


						
						
			<!---------- RNC9 ---------->
			<table id="model_RNC9" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第九个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNC9" name="star_RNC9" onClick="addFavorites('RNC9');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNC9 -->
			  <tr class="more_white" style="*DISPLAY_RNC9*">
			     <td id="*RNC9H_GID*" onClick="betEvent('*GID*','RNC9H','*IORATIO_RNC9H*','RNC9');" style="cursor:pointer"  class="*TD_CLASS_RNC9H*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNC9H*</span></div></td>
			     <td id="*RNC9C_GID*" onClick="betEvent('*GID*','RNC9C','*IORATIO_RNC9C*','RNC9');" style="cursor:pointer"  class="*TD_CLASS_RNC9C*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNC9C*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNC9 -->
			</table>
			<!---------- RNC9 ---------->


						
						
			<!---------- RNCA ---------->
			<table id="model_RNCA" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCA" name="star_RNCA" onClick="addFavorites('RNCA');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCA -->
			  <tr class="more_white" style="*DISPLAY_RNCA*">
			     <td id="*RNCAH_GID*" onClick="betEvent('*GID*','RNCAH','*IORATIO_RNCAH*','RNCA');" style="cursor:pointer"  class="*TD_CLASS_RNCAH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCAH*</span></div></td>
			     <td id="*RNCAC_GID*" onClick="betEvent('*GID*','RNCAC','*IORATIO_RNCAC*','RNCA');" style="cursor:pointer"  class="*TD_CLASS_RNCAC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCAC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCA -->
			</table>
			<!---------- RNCA ---------->


						
						
			<!---------- RNCB ---------->
			<table id="model_RNCB" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十一个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCB" name="star_RNCB" onClick="addFavorites('RNCB');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCB -->
			  <tr class="more_white" style="*DISPLAY_RNCB*">
			     <td id="*RNCBH_GID*" onClick="betEvent('*GID*','RNCBH','*IORATIO_RNCBH*','RNCB');" style="cursor:pointer"  class="*TD_CLASS_RNCBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCBH*</span></div></td>
			     <td id="*RNCBC_GID*" onClick="betEvent('*GID*','RNCBC','*IORATIO_RNCBC*','RNCB');" style="cursor:pointer"  class="*TD_CLASS_RNCBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCBC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCB -->
			</table>
			<!---------- RNCB ---------->


						
						
			<!---------- RNCC ---------->
			<table id="model_RNCC" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十二个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCC" name="star_RNCC" onClick="addFavorites('RNCC');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCC -->
			  <tr class="more_white" style="*DISPLAY_RNCC*">
			     <td id="*RNCCH_GID*" onClick="betEvent('*GID*','RNCCH','*IORATIO_RNCCH*','RNCC');" style="cursor:pointer"  class="*TD_CLASS_RNCCH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCCH*</span></div></td>
			     <td id="*RNCCC_GID*" onClick="betEvent('*GID*','RNCCC','*IORATIO_RNCCC*','RNCC');" style="cursor:pointer"  class="*TD_CLASS_RNCCC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCCC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCC -->
			</table>
			<!---------- RNCC ---------->


						
						
			<!---------- RNCD ---------->
			<table id="model_RNCD" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十三个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCD" name="star_RNCD" onClick="addFavorites('RNCD');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCD -->
			  <tr class="more_white" style="*DISPLAY_RNCD*">
			     <td id="*RNCDH_GID*" onClick="betEvent('*GID*','RNCDH','*IORATIO_RNCDH*','RNCD');" style="cursor:pointer"  class="*TD_CLASS_RNCDH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCDH*</span></div></td>
			     <td id="*RNCDC_GID*" onClick="betEvent('*GID*','RNCDC','*IORATIO_RNCDC*','RNCD');" style="cursor:pointer"  class="*TD_CLASS_RNCDC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCDC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCD -->
			</table>
			<!---------- RNCD ---------->


						
						
			<!---------- RNCE ---------->
			<table id="model_RNCE" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十四个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCE" name="star_RNCE" onClick="addFavorites('RNCE');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCE -->
			  <tr class="more_white" style="*DISPLAY_RNCE*">
			     <td id="*RNCEH_GID*" onClick="betEvent('*GID*','RNCEH','*IORATIO_RNCEH*','RNCE');" style="cursor:pointer"  class="*TD_CLASS_RNCEH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCEH*</span></div></td>
			     <td id="*RNCEC_GID*" onClick="betEvent('*GID*','RNCEC','*IORATIO_RNCEC*','RNCE');" style="cursor:pointer"  class="*TD_CLASS_RNCEC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCEC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCE -->
			</table>
			<!---------- RNCE ---------->


						
						
			<!---------- RNCF ---------->
			<table id="model_RNCF" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十五个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCF" name="star_RNCF" onClick="addFavorites('RNCF');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCF -->
			  <tr class="more_white" style="*DISPLAY_RNCF*">
			     <td id="*RNCFH_GID*" onClick="betEvent('*GID*','RNCFH','*IORATIO_RNCFH*','RNCF');" style="cursor:pointer"  class="*TD_CLASS_RNCFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCFH*</span></div></td>
			     <td id="*RNCFC_GID*" onClick="betEvent('*GID*','RNCFC','*IORATIO_RNCFC*','RNCF');" style="cursor:pointer"  class="*TD_CLASS_RNCFC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCFC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCF -->
			</table>
			<!---------- RNCF ---------->


						
						
			<!---------- RNCG ---------->
			<table id="model_RNCG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十六个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCG" name="star_RNCG" onClick="addFavorites('RNCG');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCG -->
			  <tr class="more_white" style="*DISPLAY_RNCG*">
			     <td id="*RNCGH_GID*" onClick="betEvent('*GID*','RNCGH','*IORATIO_RNCGH*','RNCG');" style="cursor:pointer"  class="*TD_CLASS_RNCGH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCGH*</span></div></td>
			     <td id="*RNCGC_GID*" onClick="betEvent('*GID*','RNCGC','*IORATIO_RNCGC*','RNCG');" style="cursor:pointer"  class="*TD_CLASS_RNCGC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCGC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCG -->
			</table>
			<!---------- RNCG ---------->


						
						
			<!---------- RNCH ---------->
			<table id="model_RNCH" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十七个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCH" name="star_RNCH" onClick="addFavorites('RNCH');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCH -->
			  <tr class="more_white" style="*DISPLAY_RNCH*">
			     <td id="*RNCHH_GID*" onClick="betEvent('*GID*','RNCHH','*IORATIO_RNCHH*','RNCH');" style="cursor:pointer"  class="*TD_CLASS_RNCHH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCHH*</span></div></td>
			     <td id="*RNCHC_GID*" onClick="betEvent('*GID*','RNCHC','*IORATIO_RNCHC*','RNCH');" style="cursor:pointer"  class="*TD_CLASS_RNCHC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCHC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCH -->
			</table>
			<!---------- RNCH ---------->


						
						
			<!---------- RNCI ---------->
			<table id="model_RNCI" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十八个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCI" name="star_RNCI" onClick="addFavorites('RNCI');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCI -->
			  <tr class="more_white" style="*DISPLAY_RNCI*">
			     <td id="*RNCIH_GID*" onClick="betEvent('*GID*','RNCIH','*IORATIO_RNCIH*','RNCI');" style="cursor:pointer"  class="*TD_CLASS_RNCIH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCIH*</span></div></td>
			     <td id="*RNCIC_GID*" onClick="betEvent('*GID*','RNCIC','*IORATIO_RNCIC*','RNCI');" style="cursor:pointer"  class="*TD_CLASS_RNCIC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCIC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCI -->
			</table>
			<!---------- RNCI ---------->


						
						
			<!---------- RNCJ ---------->
			<table id="model_RNCJ" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第十九个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCJ" name="star_RNCJ" onClick="addFavorites('RNCJ');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCJ -->
			  <tr class="more_white" style="*DISPLAY_RNCJ*">
			     <td id="*RNCJH_GID*" onClick="betEvent('*GID*','RNCJH','*IORATIO_RNCJH*','RNCJ');" style="cursor:pointer"  class="*TD_CLASS_RNCJH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCJH*</span></div></td>
			     <td id="*RNCJC_GID*" onClick="betEvent('*GID*','RNCJC','*IORATIO_RNCJC*','RNCJ');" style="cursor:pointer"  class="*TD_CLASS_RNCJC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCJC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCJ -->
			</table>
			<!---------- RNCJ ---------->


						
						
			<!---------- RNCK ---------->
			<table id="model_RNCK" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCK" name="star_RNCK" onClick="addFavorites('RNCK');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCK -->
			  <tr class="more_white" style="*DISPLAY_RNCK*">
			     <td id="*RNCKH_GID*" onClick="betEvent('*GID*','RNCKH','*IORATIO_RNCKH*','RNCK');" style="cursor:pointer"  class="*TD_CLASS_RNCKH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCKH*</span></div></td>
			     <td id="*RNCKC_GID*" onClick="betEvent('*GID*','RNCKC','*IORATIO_RNCKC*','RNCK');" style="cursor:pointer"  class="*TD_CLASS_RNCKC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCKC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCK -->
			</table>
			<!---------- RNCK ---------->


						
						
			<!---------- RNCL ---------->
			<table id="model_RNCL" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十一个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCL" name="star_RNCL" onClick="addFavorites('RNCL');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCL -->
			  <tr class="more_white" style="*DISPLAY_RNCL*">
			     <td id="*RNCLH_GID*" onClick="betEvent('*GID*','RNCLH','*IORATIO_RNCLH*','RNCL');" style="cursor:pointer"  class="*TD_CLASS_RNCLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCLH*</span></div></td>
			     <td id="*RNCLC_GID*" onClick="betEvent('*GID*','RNCLC','*IORATIO_RNCLC*','RNCL');" style="cursor:pointer"  class="*TD_CLASS_RNCLC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCLC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCL -->
			</table>
			<!---------- RNCL ---------->


						
						
			<!---------- RNCM ---------->
			<table id="model_RNCM" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十二个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCM" name="star_RNCM" onClick="addFavorites('RNCM');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCM -->
			  <tr class="more_white" style="*DISPLAY_RNCM*">
			     <td id="*RNCMH_GID*" onClick="betEvent('*GID*','RNCMH','*IORATIO_RNCMH*','RNCM');" style="cursor:pointer"  class="*TD_CLASS_RNCMH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCMH*</span></div></td>
			     <td id="*RNCMC_GID*" onClick="betEvent('*GID*','RNCMC','*IORATIO_RNCMC*','RNCM');" style="cursor:pointer"  class="*TD_CLASS_RNCMC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCMC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCM -->
			</table>
			<!---------- RNCM ---------->


						
						
			<!---------- RNCN ---------->
			<table id="model_RNCN" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十三个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCN" name="star_RNCN" onClick="addFavorites('RNCN');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCN -->
			  <tr class="more_white" style="*DISPLAY_RNCN*">
			     <td id="*RNCNH_GID*" onClick="betEvent('*GID*','RNCNH','*IORATIO_RNCNH*','RNCN');" style="cursor:pointer"  class="*TD_CLASS_RNCNH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCNH*</span></div></td>
			     <td id="*RNCNC_GID*" onClick="betEvent('*GID*','RNCNC','*IORATIO_RNCNC*','RNCN');" style="cursor:pointer"  class="*TD_CLASS_RNCNC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCNC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCN -->
			</table>
			<!---------- RNCN ---------->


						
						
			<!---------- RNCO ---------->
			<table id="model_RNCO" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十四个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCO" name="star_RNCO" onClick="addFavorites('RNCO');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCO -->
			  <tr class="more_white" style="*DISPLAY_RNCO*">
			     <td id="*RNCOH_GID*" onClick="betEvent('*GID*','RNCOH','*IORATIO_RNCOH*','RNCO');" style="cursor:pointer"  class="*TD_CLASS_RNCOH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCOH*</span></div></td>
			     <td id="*RNCOC_GID*" onClick="betEvent('*GID*','RNCOC','*IORATIO_RNCOC*','RNCO');" style="cursor:pointer"  class="*TD_CLASS_RNCOC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCOC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCO -->
			</table>
			<!---------- RNCO ---------->


						
						
			<!---------- RNCP ---------->
			<table id="model_RNCP" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十五个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCP" name="star_RNCP" onClick="addFavorites('RNCP');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCP -->
			  <tr class="more_white" style="*DISPLAY_RNCP*">
			     <td id="*RNCPH_GID*" onClick="betEvent('*GID*','RNCPH','*IORATIO_RNCPH*','RNCP');" style="cursor:pointer"  class="*TD_CLASS_RNCPH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCPH*</span></div></td>
			     <td id="*RNCPC_GID*" onClick="betEvent('*GID*','RNCPC','*IORATIO_RNCPC*','RNCP');" style="cursor:pointer"  class="*TD_CLASS_RNCPC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCPC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCP -->
			</table>
			<!---------- RNCP ---------->


						
						
			<!---------- RNCQ ---------->
			<table id="model_RNCQ" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十六个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCQ" name="star_RNCQ" onClick="addFavorites('RNCQ');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCQ -->
			  <tr class="more_white" style="*DISPLAY_RNCQ*">
			     <td id="*RNCQH_GID*" onClick="betEvent('*GID*','RNCQH','*IORATIO_RNCQH*','RNCQ');" style="cursor:pointer"  class="*TD_CLASS_RNCQH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCQH*</span></div></td>
			     <td id="*RNCQC_GID*" onClick="betEvent('*GID*','RNCQC','*IORATIO_RNCQC*','RNCQ');" style="cursor:pointer"  class="*TD_CLASS_RNCQC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCQC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCQ -->
			</table>
			<!---------- RNCQ ---------->


						
						
			<!---------- RNCR ---------->
			<table id="model_RNCR" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十七个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCR" name="star_RNCR" onClick="addFavorites('RNCR');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCR -->
			  <tr class="more_white" style="*DISPLAY_RNCR*">
			     <td id="*RNCRH_GID*" onClick="betEvent('*GID*','RNCRH','*IORATIO_RNCRH*','RNCR');" style="cursor:pointer"  class="*TD_CLASS_RNCRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCRH*</span></div></td>
			     <td id="*RNCRC_GID*" onClick="betEvent('*GID*','RNCRC','*IORATIO_RNCRC*','RNCR');" style="cursor:pointer"  class="*TD_CLASS_RNCRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCRC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCR -->
			</table>
			<!---------- RNCR ---------->


						
						
			<!---------- RNCS ---------->
			<table id="model_RNCS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十八个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCS" name="star_RNCS" onClick="addFavorites('RNCS');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCS -->
			  <tr class="more_white" style="*DISPLAY_RNCS*">
			     <td id="*RNCSH_GID*" onClick="betEvent('*GID*','RNCSH','*IORATIO_RNCSH*','RNCS');" style="cursor:pointer"  class="*TD_CLASS_RNCSH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCSH*</span></div></td>
			     <td id="*RNCSC_GID*" onClick="betEvent('*GID*','RNCSC','*IORATIO_RNCSC*','RNCS');" style="cursor:pointer"  class="*TD_CLASS_RNCSC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCSC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCS -->
			</table>
			<!---------- RNCS ---------->


						
						
			<!---------- RNCT ---------->
			<table id="model_RNCT" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第二十九个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCT" name="star_RNCT" onClick="addFavorites('RNCT');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCT -->
			  <tr class="more_white" style="*DISPLAY_RNCT*">
			     <td id="*RNCTH_GID*" onClick="betEvent('*GID*','RNCTH','*IORATIO_RNCTH*','RNCT');" style="cursor:pointer"  class="*TD_CLASS_RNCTH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCTH*</span></div></td>
			     <td id="*RNCTC_GID*" onClick="betEvent('*GID*','RNCTC','*IORATIO_RNCTC*','RNCT');" style="cursor:pointer"  class="*TD_CLASS_RNCTC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCTC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCT -->
			</table>
			<!---------- RNCT ---------->


						
						
			<!---------- RNCU ---------->
			<table id="model_RNCU" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">第三十个角球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RNCU" name="star_RNCU" onClick="addFavorites('RNCU');" class="star_down"></span></span>
					</th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: RNCU -->
			  <tr class="more_white" style="*DISPLAY_RNCU*">
			     <td id="*RNCUH_GID*" onClick="betEvent('*GID*','RNCUH','*IORATIO_RNCUH*','RNCU');" style="cursor:pointer"  class="*TD_CLASS_RNCUH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_RNCUH*</span></div></td>
			     <td id="*RNCUC_GID*" onClick="betEvent('*GID*','RNCUC','*IORATIO_RNCUC*','RNCU');" style="cursor:pointer"  class="*TD_CLASS_RNCUC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_RNCUC*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: RNCU -->
			</table>
			<!---------- RNCU ---------->
			
			
			
			
			
			<!---------- RHG ---------->         
		 	<table id="model_RHG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">最多进球的半场</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RHG" name="star_RHG" onClick="addFavorites('RHG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RHG -->
				<tr class="more_white">
						<td id="*RHGH_GID*" onClick="betEvent('*GID*','RHGH','*IORATIO_RHGH*','RHG');" style="cursor:pointer"  class="*TD_CLASS_RHGH*" width="50%"><div class="more_font"><span class="m_team">上半场</span><span class="m_red_bet" title="上半场">*IORATIO_RHGH*</span></div></td>
						<td id="*RHGC_GID*" onClick="betEvent('*GID*','RHGC','*IORATIO_RHGC*','RHG');" style="cursor:pointer"  class="*TD_CLASS_RHGC*" width="50%"><div class="more_font"><span class="m_team">下半场</span><span class="m_red_bet" title="下半场">*IORATIO_RHGC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RHG -->
	
			</table>
			<!---------- RHG ---------->
			
			
			
			
			<!---------- RMG ---------->    
		 	<table id="model_RMG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最多进球的半场 - 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RMG" name="star_RMG" onClick="addFavorites('RMG');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RMG -->
				<tr class="more_white">
						<td id="*RMGH_GID*" onClick="betEvent('*GID*','RMGH','*IORATIO_RMGH*','RMG');" style="cursor:pointer"  class="*TD_CLASS_RMGH*" width="35%"><div class="more_font"><span class="m_team">上半场</span><span class="m_red_bet" title="上半场">*IORATIO_RMGH*</span></div></td>
						<td id="*RMGC_GID*" onClick="betEvent('*GID*','RMGC','*IORATIO_RMGC*','RMG');" style="cursor:pointer"  class="*TD_CLASS_RMGC*" width="30%"><div class="more_font"><span class="m_team">下半场</span><span class="m_red_bet" title="下半场">*IORATIO_RMGC*</span></div></td>
						<td id="*RMGN_GID*" onClick="betEvent('*GID*','RMGN','*IORATIO_RMGN*','RMG');" style="cursor:pointer"  class="*TD_CLASS_RMGN*" width="35%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_RMGN*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RMG -->
	
			</table>
			<!---------- RMG ---------->
			
			
			
			
			
			
			
			<!---------- RSB ---------->       
		 	<table id="model_RSB" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">双半场进球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RSB" name="star_RSB" onClick="addFavorites('RSB');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RSB -->
				<tr class="more_white">
						<td id="*RSBH_GID*" onClick="betEvent('*GID*','RSBH','*IORATIO_RSBH*','RSB');" style="cursor:pointer"  class="*TD_CLASS_RSBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RSBH*</span></div></td>
						<td id="*RSBC_GID*" onClick="betEvent('*GID*','RSBC','*IORATIO_RSBC*','RSB');" style="cursor:pointer"  class="*TD_CLASS_RSBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RSBC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RSB -->
	
			</table>
			<!---------- RSB ---------->
			
			
			
			
			
			<!---------- RT3G ---------->     
		 	<table id="model_RT3G" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">首个进球时间-3项</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RT3G" name="star_RT3G" onClick="addFavorites('RT3G');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RT3G -->
				<tr class="more_white">
						<td id="*RT3G1_GID*" onClick="betEvent('*GID*','RT3G1','*IORATIO_RT3G1*','RT3G');" style="cursor:pointer"  class="*TD_CLASS_RT3G1*" ><div class="more_font"><span class="m_team">第26分钟或之前</span><span class="m_red_bet" title="第26分钟或之前">*IORATIO_RT3G1*</span></div></td>
				</tr>                                                                                                            
				                                                                                                                 
				<tr class="more_color">                                                                                          
						<td id="*RT3G2_GID*" onClick="betEvent('*GID*','RT3G2','*IORATIO_RT3G2*','RT3G');" style="cursor:pointer"  class="*TD_CLASS_RT3G2*" ><div class="more_font"><span class="m_team">第27分钟或之后</span><span class="m_red_bet" title="第27分钟或之后">*IORATIO_RT3G2*</span></div></td>
				</tr>                                                                                                            
				                                                                                                                 
				<tr class="more_white">                                                                                          
						<td id="*RT3GN_GID*" onClick="betEvent('*GID*','RT3GN','*IORATIO_RT3GN*','RT3G');" style="cursor:pointer"  class="*TD_CLASS_RT3GN*" ><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RT3GN*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RT3G -->
	
			</table>
			<!---------- RT3G ---------->
			
			
			
			
			
			<!---------- RT1G ---------->     
		 	<table id="model_RT1G" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">首个进球时间</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RT1G" name="star_RT1G" onClick="addFavorites('RT1G');" class="star_down"></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RT1G -->
				<tr class="more_white">
						<td id="*RT1G1_GID*" onClick="betEvent('*GID*','RT1G1','*IORATIO_RT1G1*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G1*" width="50%"><div class="more_font"><span class="m_team">上半场开场 - 14:59分钟</span><span class="m_red_bet" title="上半场开场 - 14:59分钟">*IORATIO_RT1G1*</span></div></td>
						<td id="*RT1G2_GID*" onClick="betEvent('*GID*','RT1G2','*IORATIO_RT1G2*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G2*" width="50%"><div class="more_font"><span class="m_team">15:00分钟 - 29:59分钟</span><span class="m_red_bet" title="15:00分钟 - 29:59分钟">*IORATIO_RT1G2*</span></div></td>
				</tr>                                                                                                            
				                                                                                                                 
				<tr class="more_color">                                                                                          
						<td id="*RT1G3_GID*" onClick="betEvent('*GID*','RT1G3','*IORATIO_RT1G3*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G3*" ><div class="more_font"><span class="m_team">30:00分钟 - 半场</span><span class="m_red_bet" title="30:00分钟 - 半场">*IORATIO_RT1G3*</span></div></td>
						<td id="*RT1G4_GID*" onClick="betEvent('*GID*','RT1G4','*IORATIO_RT1G4*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G4*" ><div class="more_font"><span class="m_team">下半场开场 - 59:59分钟</span><span class="m_red_bet" title="下半场开场 - 59:59分钟">*IORATIO_RT1G4*</span></div></td>
				</tr>                                                                                                            
				                                                                                                                 
				<tr class="more_white">                                                                                          
						<td id="*RT1G5_GID*" onClick="betEvent('*GID*','RT1G5','*IORATIO_RT1G5*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G5*" ><div class="more_font"><span class="m_team">60:00分钟 - 74:59分钟</span><span class="m_red_bet" title="60:00分钟 - 74:59分钟">*IORATIO_RT1G5*</span></div></td>
						<td id="*RT1G6_GID*" onClick="betEvent('*GID*','RT1G6','*IORATIO_RT1G6*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1G6*" ><div class="more_font"><span class="m_team">75:00分钟 - 全场完场</span><span class="m_red_bet" title="75:00分钟 - 全场完场">*IORATIO_RT1G6*</span></div></td>
				</tr>                                                                                                            
				                                                                                                                 
				<tr class="more_color">                                                                                          
						<td id="*RT1GN_GID*" onClick="betEvent('*GID*','RT1GN','*IORATIO_RT1GN*','RT1G');" style="cursor:pointer"  class="*TD_CLASS_RT1GN*" ><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_RT1GN*</span></div></td>
						<td><span></span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RT1G -->
	
			</table>
			<!---------- RT1G ---------->
			
			
			
			<!---------- RDU ---------->
			<table id="model_RDU" cellpadding="0" cellspacing="0" border="0" class="more_table2">
        <tr>
        	<th class="more_title4" colspan="3">
          	<span style="float: left;">双重机会 & 进球 大 / 小</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RDU" name="star_RDU" onClick="addFavorites('RDU');" class="star_down"></span></span>
					</th>
        </tr>


        <!-- START DYNAMIC BLOCK: RDU -->
        <tr class="*DISPLAY_RDU*">
            <td class="game_team"><span>*TEAM_H* / 和局</span></td>
            <td class="game_team"><span>*TEAM_C* / 和局</span></td>
            <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
        </tr>

        <tr class="more_white *DISPLAY_RDU*">
           <td id="*RDUHO_GID*" onClick="betEvent('*GID*','*RDUHO_RTYPE*','*IORATIO_RDUHO*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUHO*" width="35%"><div class="more_font"><span class="m_team">*STR_RDUHO*</span><span class="m_red_bet" title="*STR_RDUHO*">*IORATIO_RDUHO*</span></div></td>
           <td id="*RDUCO_GID*" onClick="betEvent('*GID*','*RDUCO_RTYPE*','*IORATIO_RDUCO*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUCO*" width="30%"><div class="more_font"><span class="m_team">*STR_RDUCO*</span><span class="m_red_bet" title="*STR_RDUCO*">*IORATIO_RDUCO*</span></div></td>
           <td id="*RDUSO_GID*" onClick="betEvent('*GID*','*RDUSO_RTYPE*','*IORATIO_RDUSO*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUSO*" width="35%"><div class="more_font"><span class="m_team">*STR_RDUSO*</span><span class="m_red_bet" title="*STR_RDUNO*">*IORATIO_RDUSO*</span></div></td>
        </tr>
        <tr class="more_color *DISPLAY_RDU*">
           <td id="*RDUHU_GID*" onClick="betEvent('*GID*','*RDUHU_RTYPE*','*IORATIO_RDUHU*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUHU*" width="35%"><div class="more_font"><span class="m_team">*STR_RDUHU*</span><span class="m_red_bet" title="*STR_RDUHU*">*IORATIO_RDUHU*</span></div></td>
           <td id="*RDUCU_GID*" onClick="betEvent('*GID*','*RDUCU_RTYPE*','*IORATIO_RDUCU*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUCU*" width="30%"><div class="more_font"><span class="m_team">*STR_RDUCU*</span><span class="m_red_bet" title="*STR_RDUCU*">*IORATIO_RDUCU*</span></div></td>
           <td id="*RDUSU_GID*" onClick="betEvent('*GID*','*RDUSU_RTYPE*','*IORATIO_RDUSU*','*RDU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_RDUSU*" width="35%"><div class="more_font"><span class="m_team">*STR_RDUSU*</span><span class="m_red_bet" title="*STR_RDUNU*">*IORATIO_RDUSU*</span></div></td>
        </tr>
      	<!-- END DYNAMIC BLOCK: RDU -->
     </table>
     <!---------- RDU ---------->




     <!---------- RDS ---------->
     <table id="model_RDS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
        <tr>
        	<th class="more_title4" colspan="3">
        		<span style="float: left;">双重机会 & 双方球队进球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RDS" name="star_RDS" onClick="addFavorites('RDS');" class="star_down"></span></span>
					</th>
        </tr>

        <!-- START DYNAMIC BLOCK: RDS -->
        <tr>
            <td class="game_team"><span>*TEAM_H* / 和局</span></td>
            <td class="game_team"><span>*TEAM_C* / 和局</span></td>
            <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
        </tr>

        <tr class="more_white">
           <td id="*RDSHY_GID*" onClick="betEvent('*GID*','RDSHY','*IORATIO_RDSHY*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSHY*" width="35%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RDSHY*</span></div></td>
           <td id="*RDSCY_GID*" onClick="betEvent('*GID*','RDSCY','*IORATIO_RDSCY*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSCY*" width="30%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RDSCY*</span></div></td>
           <td id="*RDSSY_GID*" onClick="betEvent('*GID*','RDSSY','*IORATIO_RDSSY*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSSY*" width="35%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RDSSY*</span></div></td>
        </tr>
        <tr class="more_color">
           <td id="*RDSHN_GID*" onClick="betEvent('*GID*','RDSHN','*IORATIO_RDSHN*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSHN*" width="35%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RDSHN*</span></div></td>
           <td id="*RDSCN_GID*" onClick="betEvent('*GID*','RDSCN','*IORATIO_RDSCN*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSCN*" width="30%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RDSCN*</span></div></td>
           <td id="*RDSSN_GID*" onClick="betEvent('*GID*','RDSSN','*IORATIO_RDSSN*','RDS');" style="cursor:pointer"  class="*TD_CLASS_RDSSN*" width="35%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RDSSN*</span></div></td>
        </tr>

      	<!-- END DYNAMIC BLOCK: RDS -->
     </table>
     <!---------- RDS ---------->




     <!---------- RDG ---------->
     <table id="model_RDG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
        <tr>
        	<th class="more_title4" colspan="3">
          	<span style="float: left;">双重机会 & 最先进球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_RDG" name="star_RDG" onClick="addFavorites('RDG');" class="star_down"></span></span>
					</th>
        </tr>

        <!-- START DYNAMIC BLOCK: RDG -->
        <tr>
            <td class="game_team"><span>*TEAM_H* / 和局</span></td>
            <td class="game_team"><span>*TEAM_C* / 和局</span></td>
            <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
        </tr>

        <tr class="more_white">
           <td id="*RDGHH_GID*" onClick="betEvent('*GID*','RDGHH','*IORATIO_RDGHH*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGHH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_RDGHH*</span></div></td>
           <td id="*RDGCH_GID*" onClick="betEvent('*GID*','RDGCH','*IORATIO_RDGCH*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGCH*" width="30%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_RDGCH*</span></div></td>
           <td id="*RDGSH_GID*" onClick="betEvent('*GID*','RDGSH','*IORATIO_RDGSH*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGSH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_RDGSH*</span></div></td>
        </tr>
        <tr class="more_color">
           <td id="*RDGHC_GID*" onClick="betEvent('*GID*','RDGHC','*IORATIO_RDGHC*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGHC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_RDGHC*</span></div></td>
           <td id="*RDGCC_GID*" onClick="betEvent('*GID*','RDGCC','*IORATIO_RDGCC*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGCC*" width="30%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_RDGCC*</span></div></td>
           <td id="*RDGSC_GID*" onClick="betEvent('*GID*','RDGSC','*IORATIO_RDGSC*','RDG');" style="cursor:pointer"  class="*TD_CLASS_RDGSC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_RDGSC*</span></div></td>
        </tr>

      	<!-- END DYNAMIC BLOCK: RDG -->
     </table>
     <!---------- RDG ---------->




			<!---------- ROUE ---------->
			<table id="model_ROUE" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">进球 大 / 小 & 进球 单 / 双</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_ROUE" name="star_ROUE" onClick="addFavorites('ROUE');" class="star_down"></span></span>
					</th>
			  </tr>


			  <!-- START DYNAMIC BLOCK: ROUE -->
			  <tr class="*DISPLAY_ROUE*">
            <td class="game_team"><span>单</span></td>
            <td class="game_team"><span>双</span></td>
        </tr>

			  <tr class="more_white *DISPLAY_ROUE*">
			     <td id="*ROUEOO_GID*" onClick="betEvent('*GID*','*ROUEOO_RTYPE*','*IORATIO_ROUEOO*','*ROUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUEOO*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUEOO*</span><span class="m_red_bet" title="*STR_ROUEOO*">*IORATIO_ROUEOO*</span></div></td>
			     <td id="*ROUEOE_GID*" onClick="betEvent('*GID*','*ROUEOE_RTYPE*','*IORATIO_ROUEOE*','*ROUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUEOE*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUEOE*</span><span class="m_red_bet" title="*STR_ROUEOE*">*IORATIO_ROUEOE*</span></div></td>
			  </tr>
			  <tr class="more_color *DISPLAY_ROUE*">
			     <td id="*ROUEUO_GID*" onClick="betEvent('*GID*','*ROUEUO_RTYPE*','*IORATIO_ROUEUO*','*ROUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUEUO*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUEUO*</span><span class="m_red_bet" title="*STR_ROUEUO*">*IORATIO_ROUEUO*</span></div></td>
			     <td id="*ROUEUE_GID*" onClick="betEvent('*GID*','*ROUEUE_RTYPE*','*IORATIO_ROUEUE*','*ROUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUEUE*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUEUE*</span><span class="m_red_bet" title="*STR_ROUEUE*">*IORATIO_ROUEUE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: ROUE -->

			</table>
			<!---------- ROUE ---------->




			<!---------- ROUP ---------->
			<table id="model_ROUP" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">进球 大 / 小 & 最先进球</span>
						<span class="more_og"></span>
						<span class="more_star_bg"><span id="star_ROUP" name="star_ROUP" onClick="addFavorites('ROUP');" class="star_down"></span></span>
					</th>
			  </tr>


			  <!-- START DYNAMIC BLOCK: ROUP -->
			  <tr class="*DISPLAY_ROUP*">
            <td class="game_team"><span>*TEAM_H* (最先进球)</span></td>
            <td class="game_team"><span>*TEAM_C* (最先进球)</span></td>
        </tr>

			  <tr class="more_white *DISPLAY_ROUP*">
			     <td id="*ROUPOH_GID*" onClick="betEvent('*GID*','*ROUPOH_RTYPE*','*IORATIO_ROUPOH*','*ROUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUPOH*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUPOH*</span><span class="m_red_bet" title="*STR_ROUPOH*">*IORATIO_ROUPOH*</span></div></td>
			     <td id="*ROUPOC_GID*" onClick="betEvent('*GID*','*ROUPOC_RTYPE*','*IORATIO_ROUPOC*','*ROUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUPOC*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUPOC*</span><span class="m_red_bet" title="*STR_ROUPOC*">*IORATIO_ROUPOC*</span></div></td>
			  </tr>
			  <tr class="more_color *DISPLAY_ROUP*">
			     <td id="*ROUPUH_GID*" onClick="betEvent('*GID*','*ROUPUH_RTYPE*','*IORATIO_ROUPUH*','*ROUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUPUH*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUPUH*</span><span class="m_red_bet" title="*STR_ROUPUH*">*IORATIO_ROUPUH*</span></div></td>
			     <td id="*ROUPUC_GID*" onClick="betEvent('*GID*','*ROUPUC_RTYPE*','*IORATIO_ROUPUC*','*ROUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_ROUPUC*" width="50%"><div class="more_font"><span class="m_team">*STR_ROUPUC*</span><span class="m_red_bet" title="*STR_ROUPUC*">*IORATIO_ROUPUC*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: ROUP -->

			</table>
			<!---------- ROUP ---------->
</div>	

</div>

</body>
</html>

