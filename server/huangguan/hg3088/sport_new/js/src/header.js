var cgTypebtn = 're_class';
var head_FU = 'FT';
var head_btn = 'today';
var liveid = '';

/* 迁移 部分select.js 开始*/

function onloadSet(w,h,frameName){
    document.getElementById(frameName).height =h;
}
function close_bet(){
    $('.add-bet-container').show();
    document.getElementById('bet_order_frame').height = 0 ;
    bet_order_frame.document.close();
    try {
        bet_order_frame.clearAllTimer();//清除下注窗口定时器
    }catch (e) {

    }
}
/* 迁移 部分select.js 结束*/

function onloaded() {
	var obj= document.getElementById(cgTypebtn+"");
      obj.className="type_on";
	try{
		if ((navigator.appVersion).indexOf("MSIE 6")==-1){
			document.getElementById("download").style.visibility="visible";
		}
	}catch(E){}
	try{
		document.getElementById("today_btn").className="early";
	}catch(E){}
	try{
		document.getElementById("early_btn").className="early";
	}catch(E){}		
	try{
		document.getElementById("rb_btn").className="rb";
	}catch(E){}		
				
	try{
		//alert("onload==>"+head_btn);
		document.getElementById(head_btn+"_btn").className=head_btn+"_on";
	}catch(E){}	
		//更新信用額度   max---

	//showTable();
  	//GameType();
  
}

// 语言切换
function changeLangx(setlangx){
    var cgTypebtn="re_class";
    var langx=setlangx;
    var head_gtype="FT";
    var head_FU="FT";
    var head_btn="today";
    var FT_lid = new Array();
    var FU_lid = new Array();
    var FSFT_lid = new Array();
    FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
    FT_lid['FT_lid_type']= FT_lid_type='';
    FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
    FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
    FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
    FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
    FU_lid['FU_lid_type']= FU_lid_type='';
    FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
    FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
    FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';

    var BK_lid = new Array();
    var BU_lid = new Array();
    var FSBK_lid = new Array();
    BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
    BK_lid['BK_lid_type']= BK_lid_type='';
    BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
    BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
    BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
    BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
    BU_lid['BU_lid_type']= BU_lid_type='';
    BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
    FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
    FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';

    var BS_lid = new Array();
    var BSFU_lid = new Array();
    var FSBS_lid = new Array();
    BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
    BS_lid['BS_lid_type']= BS_lid_type='';
    BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
    BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
    BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
    BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
    BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
    BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
    FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
    FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';

    var TN_lid = new Array();
    var TU_lid = new Array();
    var FSTN_lid = new Array();
    TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
    TN_lid['TN_lid_type']= TN_lid_type='';
    TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
    TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
    TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
    TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
    TU_lid['TU_lid_type']= TU_lid_type='';
    TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
    FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';
    FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';

    var VB_lid = new Array();
    var VU_lid = new Array();
    var FSVB_lid = new Array();
    VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
    VB_lid['VB_lid_type']= VB_lid_type='';
    VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
    VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
    VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
    VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
    VU_lid['VU_lid_type']= VU_lid_type='';
    VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
    FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
    FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL';
    var OP_lid = new Array();
    var OM_lid = new Array();
    var FSOP_lid = new Array();
    OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
    OP_lid['OP_lid_type']= OP_lid_type='';
    OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
    OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
    OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
    OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
    OM_lid['OM_lid_type']= OM_lid_type='';
    OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
    FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
    FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
    var head_btn="today";

    parent.location.href=((""+parent.location).replace("zh-tw",setlangx).replace("zh-cn",setlangx).replace("en-us",setlangx));
    //}

}
/* 流程 SetRB ---> reloadRB --->  showLayer */

/*滾球提示--將值帶進去去開啟getrecRB.php程式,去抓取伺服器是否有滾球賽程*/

function reloadRB(gtype,uid){
	//alert("reloadphp===>"+uid)
	reloadPHP.location.href="./getrecRB.php?gtype="+gtype+"&uid="+uid;
	//alert("reloadphp end")
	chkMemOnline();
}
function chkMemOnline(){
	//memOnline.location.href="./mem_online.php?uid="+uid;
}
/*滾球提示--將getrecRB.php的結果帶進去,去判斷是否record_RB是否大於0,如果有會顯示滾球圖示*/

//-----------------時鍾------------------每秒顯示

/* 滾球提示--程式一開始值呼叫reloadRb,setInterval函式 多久會呼叫reloadRB函數預設 1分鐘 */
function SetRB(gttype,uid){
	//alert("setRB=>"+uid);
	reloadRB(gttype,uid);
	setInterval("reloadRB('"+gttype+"','"+uid+"')",60*1000);
}

function OpenLive(){
	if (liveid == undefined) {
		parent.self.location = "";
		return;
	}
	window.open("./live/live.php?langx="+langx+"&uid="+uid+"&liveid="+liveid,"Live","width=780,height=580,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}

function chkDelAllShowLoveI(getGtype){
	ShowLoveIarray[getGtype]= new Array();
	ShowLoveIOKarray[getGtype]="";
	if(swShowLoveI){
		swShowLoveI=false;
		eval("parent."+parent.body.sel_gtype+"_lid_type="+parent.body.sel_gtype+"_lid['"+parent.body.sel_gtype+"_lid_type']");
		parent.body.pg =0;
		parent.body.body_browse.reload_var("up");
	}else{
		parent.body.ShowGameList();
	}
	showTable();
	parent.body.body_browse.futureShowGtypeTable();
}

function mouseEnter_pointer(tmp){
	try{
		var tmp1 = tmp.split("_")[1];
		var txtnum = ShowLoveIarray[tmp1].length;
		if(txtnum !=0)
			document.getElementById(tmp).style.display ="block";
	}catch(E){}
}

function mouseOut_pointer(tmp){
	try{
	document.getElementById(tmp).style.display ="none";
	}catch(E){}
}
try{
	showGtype = gtypeShowLoveI;
	var xx=showGtype.length;
}catch(E){
	initDate();
	showGtype = gtypeShowLoveI;
}

function initDate(){
	
	gtypeShowLoveI =new Array("FTRE","FT","FU","BKRE","BK","BU","BSRE","BS","BSFU","TNRE","TN","TU","VBRE","VB","VU","OPRE","OP","OM");
	ShowLoveIarray = new Array();
	ShowLoveIOKarray = new Array();
	for (var i=0 ; i < gtypeShowLoveI.length ; i++){
		ShowLoveIarray[gtypeShowLoveI[i]]= new Array();
		ShowLoveIOKarray[gtypeShowLoveI[i]]= new Array();
	}
}





