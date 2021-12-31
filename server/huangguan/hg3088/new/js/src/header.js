
var today_count=0;
var early_count=0;

if (""+top.cgTypebtn=="undefined"){
	top.cgTypebtn="re_class";
}

if (""+top.head_gtype=="undefined"){
	top.head_gtype="FT";
}
if (""+top.head_FU=="undefined"){
	top.head_FU="FT";
}
if (""+top.head_btn=="undefined"){
	top.head_btn="today";
}

// 公用获取iframe id
function getIframeId(id) {
    return window.parent.document.getElementById(id) ;
}
// 获取当前id
function getMyselfId(id) {
    return document.getElementById(id) ;
}

function onloaded() {
	if (top.casino != "SI2") {
		try{
            getMyselfId("live").style.display = "none";
            getMyselfId("QA_row").style.display = "none";
		}catch(E){}
	}
	var obj= getMyselfId(top.cgTypebtn+"");
      obj.className="type_on";
	try{
		if ((navigator.appVersion).indexOf("MSIE 6")==-1){
            getMyselfId("download").style.visibility="visible";
		}
	}catch(E){}
	try{
        getMyselfId("today_btn").className="early";
	}catch(E){}
	try{
        getMyselfId("early_btn").className="early";
	}catch(E){}		
	try{
        getMyselfId("rb_btn").className="rb";
	}catch(E){}		
				
	try{
        getMyselfId(top.head_btn+"_btn").className=top.head_btn+"_on";
	}catch(E){}
  
}
function chg_head(a,b,c){
    top.RB_id="";
    top.hot_game="";
    if(top.swShowLoveI)b=3;
    if(top.showtype=='hgft')b=3;
    var hot_str="";
    hot_str="&hot_game="+top.hot_game;
    parent.body.location=a+"&league_id="+b+hot_str;
}
// d 判断是否为滚球
function chg_type(a,b,c,d){
	if(top.swShowLoveI)b=3;
	if(top.showtype=='hgft')b=3;
	parent.body.location=a+"&league_id="+b;
	if(d=='rb'){ // 滚球时
        try {
            getMyselfId('title_FT').className='ord_sportFT_off noFloat' ; // 足球
            getMyselfId('wager_FT').className='hide_game_list' ; // 足球
            getMyselfId('title_BK').className='ord_sportBK_off noFloat' ; // 篮球
            getMyselfId('wager_BK').className='hide_game_list' ; // 篮球
        }catch(E){}

    }
}
function chg_index(a,b,c,d,future){
	top.swShowLoveI=false;
	top.cgTypebtn="re_class";
	parent.body.location.href=b;
	self.location.href=a;
}
function chg_type_class(game_type){
//已選取：黃字 class="type_on"
//選取後離開：白字 class="type_out"
    var obj= getMyselfId(game_type+"");
    var obj_laster= getMyselfId(top.cgTypebtn+"");
    // console.log(obj)
    // console.log(obj_laster)
    if(game_type != top.cgTypebtn ){
      obj.className="type_on";
	 try {
         obj_laster.className="type_out";
     }catch(E) {}
      top.cgTypebtn=game_type;
    }

}
function chg_button_bg(gtype,btn){
	top.head_gtype=gtype;
	if (btn=="early"||btn=="today" || btn =='rb'){
		chg_type_class("re_class"); 
	}
    sessionStorage.setItem('m_type',btn) ;
    sessionStorage.setItem('g_type',top.head_gtype) ;
	if (btn!="rb"){
		if(btn=="early"){
			top.head_FU="FU";	
		}else{
			top.head_FU="FT";
		}
	}
	try{
        getMyselfId(top.head_btn+"_btn").className=top.head_btn;
	}catch(E){}
	top.head_btn=btn;
	try{
        getMyselfId(btn+"_btn").className=btn+"_on";
	}catch(E){}


}
// 语言切换
/*function changeLangx(setlangx){
	/!*
	for(var i=0;i<parent.frames.length;i++){
					parent.frames[i].location.href=((""+parent.frames[i].location).replace("zh-cn",setlangx).replace("zh-cn",setlangx).replace("en-us",setlangx));
	}

*!/
	if (top.langx!=setlangx){
		top.cgTypebtn="re_class";
		top.langx=setlangx;
		top.head_gtype="FT";
		top.head_FU="FT";
		top.head_btn="today";
		top.FT_lid = new Array();
		top.FU_lid = new Array();
		top.FSFT_lid = new Array();
		top.FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
		top.FT_lid['FT_lid_type']= FT_lid_type='';
		top.FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
		top.FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
		top.FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
		top.FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
		top.FU_lid['FU_lid_type']= FU_lid_type='';
		top.FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
		top.FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
		top.FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';
		
		top.BK_lid = new Array();
	top.BU_lid = new Array();
	top.FSBK_lid = new Array();
	top.BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
	top.BK_lid['BK_lid_type']= BK_lid_type='';
	top.BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
	top.BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
	top.BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
	top.BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
	top.BU_lid['BU_lid_type']= BU_lid_type='';
	top.BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
	top.FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
	top.FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';		
	
	top.BS_lid = new Array();
	top.BSFU_lid = new Array();
	top.FSBS_lid = new Array();	
	top.BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
	top.BS_lid['BS_lid_type']= BS_lid_type='';
	top.BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
	top.BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
	top.BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
	top.BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
	top.BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
	top.BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
	top.FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
	top.FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';

	top.TN_lid = new Array();
	top.TU_lid = new Array();
	top.FSTN_lid = new Array();	
	top.TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
	top.TN_lid['TN_lid_type']= TN_lid_type='';
	top.TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
	top.TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
	top.TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
	top.TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
	top.TU_lid['TU_lid_type']= TU_lid_type='';
	top.TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
	top.FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';	
	top.FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';	
	
	top.VB_lid = new Array();
	top.VU_lid = new Array();
	top.FSVB_lid = new Array();	
	top.VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
	top.VB_lid['VB_lid_type']= VB_lid_type='';
	top.VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
	top.VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
	top.VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
	top.VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
	top.VU_lid['VU_lid_type']= VU_lid_type='';
	top.VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
	top.FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
	top.FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL';
	top.OP_lid = new Array();
	top.OM_lid = new Array();
	top.FSOP_lid = new Array();	
	top.OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
	top.OP_lid['OP_lid_type']= OP_lid_type='';
	top.OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
	top.OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
	top.OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
	top.OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
	top.OM_lid['OM_lid_type']= OM_lid_type='';
	top.OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
	top.FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
	top.FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
	top.head_btn="today";
					
		parent.location.href=((""+parent.location).replace("zh-cn",setlangx).replace("zh-cn",setlangx).replace("en-us",setlangx));
	}
	
}*/

// 语言切换
function changeLangx(setlangx){
    top.cgTypebtn="re_class";
    top.langx=setlangx;
    top.head_gtype="FT";
    top.head_FU="FT";
    top.head_btn="today";
    top.FT_lid = new Array();
    top.FU_lid = new Array();
    top.FSFT_lid = new Array();
    top.FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
    top.FT_lid['FT_lid_type']= FT_lid_type='';
    top.FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
    top.FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
    top.FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
    top.FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
    top.FU_lid['FU_lid_type']= FU_lid_type='';
    top.FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
    top.FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
    top.FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';

    top.BK_lid = new Array();
    top.BU_lid = new Array();
    top.FSBK_lid = new Array();
    top.BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
    top.BK_lid['BK_lid_type']= BK_lid_type='';
    top.BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
    top.BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
    top.BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
    top.BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
    top.BU_lid['BU_lid_type']= BU_lid_type='';
    top.BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
    top.FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
    top.FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';

    top.BS_lid = new Array();
    top.BSFU_lid = new Array();
    top.FSBS_lid = new Array();
    top.BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
    top.BS_lid['BS_lid_type']= BS_lid_type='';
    top.BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
    top.BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
    top.BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
    top.BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
    top.BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
    top.BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
    top.FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
    top.FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';

    top.TN_lid = new Array();
    top.TU_lid = new Array();
    top.FSTN_lid = new Array();
    top.TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
    top.TN_lid['TN_lid_type']= TN_lid_type='';
    top.TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
    top.TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
    top.TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
    top.TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
    top.TU_lid['TU_lid_type']= TU_lid_type='';
    top.TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
    top.FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';
    top.FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';

    top.VB_lid = new Array();
    top.VU_lid = new Array();
    top.FSVB_lid = new Array();
    top.VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
    top.VB_lid['VB_lid_type']= VB_lid_type='';
    top.VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
    top.VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
    top.VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
    top.VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
    top.VU_lid['VU_lid_type']= VU_lid_type='';
    top.VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
    top.FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
    top.FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL';
    top.OP_lid = new Array();
    top.OM_lid = new Array();
    top.FSOP_lid = new Array();
    top.OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
    top.OP_lid['OP_lid_type']= OP_lid_type='';
    top.OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
    top.OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
    top.OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
    top.OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
    top.OM_lid['OM_lid_type']= OM_lid_type='';
    top.OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
    top.FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
    top.FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
    top.head_btn="today";

    parent.location.href=((""+parent.location).replace("zh-tw",setlangx).replace("zh-cn",setlangx).replace("en-us",setlangx));
    //}

}
/* 流程 SetRB ---> reloadRB --->  showLayer */

/*滾球提示--將值帶進去去開啟getrecRB.php程式,去抓取伺服器是否有滾球賽程*/
var record_RB = 0;
function reloadRB(gtype,uid){
	reloadPHP.location.href="./getrecRB.php?gtype="+gtype+"&uid="+top.uid;
	//chkMemOnline();
}
// function chkMemOnline(){
// 	memOnline.location.href="./mem_online.php?uid="+top.uid;
// }
/*滾球提示--將getrecRB.php的結果帶進去,去判斷是否record_RB是否大於0,如果有會顯示滾球圖示*/

function showLayer(record_RB){
    getMyselfId('RB_games').innerHTML=record_RB;
    getMyselfId('FT_games').innerHTML=0;
    getMyselfId('BK_games').innerHTML=0;
    getMyselfId('TN_games').innerHTML=0;
    getMyselfId('BS_games').innerHTML=0;
    getMyselfId('VB_games').innerHTML=0;
    getMyselfId('OP_games').innerHTML=0;

}

//-----------------時鍾------------------每秒顯示
var nowTimer=0;
var stimer=0;
function autoZero(val){
	if (val<10){
		return "0"+val;
		}
		return val;
	}


function headerShowTimer(obj){
    var nowDate = new Date(new Date().getTime() - 43200000),
        nY = nowDate.getFullYear(),
        nM = nowDate.getMonth() + 1,
        nD = nowDate.getDate(),
        nH = nowDate.getHours(),
        nMi = nowDate.getMinutes(),
        nS = nowDate.getSeconds();
    nM = nM < 10 ? '0' + nM : nM;
    nD = nD < 10 ? '0' + nD : nD;
    nH = nH < 10 ? '0' + nH : nH;
    nMi = nMi < 10 ? '0' + nMi : nMi;
    nS = nS < 10 ? '0' + nS : nS;
    var fullTime = nY + '年' + nM + '月' + nD +'日'+ ' ' + nH + ':' + nMi + ':' + nS;
    $(obj).text(fullTime);
}

/*function GameCount(games){
	var countgames=games.split(",");
	var recordHash=new Array();
	recordHash["DATE"]=countgames[0];
	
	setTimeStart(recordHash["DATE"]) 
	recordHash["RB"]=0;
	for( var i=1;i<countgames.length;i++){
		var detailgame=countgames[i].split("|");
		recordHash[detailgame[0]+"_"+detailgame[1]]=detailgame[2]*1;
		
	}
	try{
		if (top.head_FU=="FU"){	
     		document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU];
			
		}else{
			document.getElementById('RB_games').innerHTML=recordHash[top.head_gtype+"_RB"];
			document.getElementById('subRB_games').innerHTML=recordHash[top.head_gtype+"_RB"]; 
			document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU]+recordHash["FT_RB"];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU]+recordHash["BK_RB"];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU]+recordHash["TN_RB"];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU]+recordHash["BS_RB"];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU]+recordHash["VB_RB"];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU]+recordHash["OP_RB"];
		}
	}catch(E){}
	today_count=recordHash[top.head_gtype+"_FT"];
	early_count=recordHash[top.head_gtype+"_FU"];
	rb_count = recordHash[top.head_gtype+"_RB"];

	if (top.head_FU=="FT"){
		if (rb_count*1 > 0){
			document.getElementById("rb_btn").style.visibility = "visible";
		}else{
			document.getElementById("rb_btn").style.visibility = "hidden";
		}
	}

  if (top.head_btn=="early"){	
    document.getElementById("early_btn").className="early_on";
  }else if(top.head_btn=="rb"){
  	document.getElementById("rb_btn").className="rb_on";
  }else{
  	document.getElementById("today_btn").className="today_on";
  }
	//chg_button_bg(top.head_gtype,top.head_FU);
	reloadCrditFunction();

}*/

/* 滾球提示--程式一開始值呼叫reloadRb,setInterval函式 多久會呼叫reloadRB函數預設 1分鐘 */
function SetRB(gttype,uid){
	//alert("setRB=>"+top.uid);
	reloadRB(gttype,top.uid);
	setInterval("reloadRB('"+gttype+"','"+top.uid+"')",60*1000);
}


// 公用打开新窗口
function openPublicWindow(url) {
    window.open(url,"win","width=980,height=650,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}
function OnMouseOverEvent() {
	//document.getElementById("informaction").style.display = "block";
}
function OnMouseOutEvent() {
	//document.getElementById("informaction").style.display = "none";
}

// 头部公用初始化 设置头部公用数据
function setHeaderInit(username) {
    getIframeId('head_cre').innerHTML = username ;// 用户名
    showMyaccount() ;
}

// 更改密码
function Go_Chg_pass(){
	var url = "../../../app/member/account/chg_passwd.php?uid="+top.uid+"&langx="+top.langx ;
	Real_Win= openPublicWindow(url) ;
}
function OpenLive(){
	if (top.liveid == undefined) {
		parent.self.location = "";
		return;
	}
	var url = "./live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
    openPublicWindow(url) ;
}

// 打开公用窗口
function openPublicAction(type) {
   // var $btn_result = getIframeId('btn_result') ;
    var url = "../../../app/member/account/all_account_page.php?wintype="+type+"&langx="+top.langx+"&uid="+top.uid ;
   openPublicWindow(url) ;
}

// 头部显示我的，语言切换，帮助
function showMyaccount() {
   var $sel_div_acc = getIframeId('sel_div_acc') ; // 我的
   var $sel_div_langx = getIframeId('sel_div_langx') ; // 语言切换
   var $sel_div_help = getIframeId('sel_div_help') ; // 帮助
   if($sel_div_acc){
       $sel_div_acc.onclick=function (ev) {
           getIframeId('div_acc').style.display='block';
	   }
   }
    if($sel_div_langx){
        $sel_div_langx.onclick=function (ev) {
            getIframeId('div_langx').style.display='block';
        }
    }
    if($sel_div_help){
        $sel_div_help.onclick=function (ev) {
            getIframeId('div_help').style.display='block';
        }
    }

}

function hideDiv(_name){
    var obj = getMyselfId(_name);
    if(obj!=null) obj.style.display="none";
}
// 隐藏余额 显示余额
function hideMoney(hide) {
    if(hide){ // 隐藏余额
        getMyselfId('head_cre').className='head_hideCre' ;
        getMyselfId('credit').style.display='none' ;
        getMyselfId('show_balance').style.display='block' ;
        getMyselfId('hide_balance').style.display='none' ;
        getMyselfId('div_acc').style.display='none' ;

    }else{
        getMyselfId('head_cre').className='' ;
        getMyselfId('credit').style.display='inline-block' ;
        getMyselfId('show_balance').style.display='none' ;
        getMyselfId('hide_balance').style.display='block' ;
        getMyselfId('div_acc').style.display='none' ;
    }

}

function chkDelAllShowLoveI(getGtype){
	top.ShowLoveIarray[getGtype]= new Array();
	top.ShowLoveIOKarray[getGtype]="";
	if(top.swShowLoveI){
		top.swShowLoveI=false;
		eval("parent."+parent.body.sel_gtype+"_lid_type=top."+parent.body.sel_gtype+"_lid['"+parent.body.sel_gtype+"_lid_type']");
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
		var txtnum = top.ShowLoveIarray[tmp1].length;
		if(txtnum !=0)
            getMyselfId(tmp).style.display ="block";
	}catch(E){}
}

function mouseOut_pointer(tmp){
	try{
        getMyselfId(tmp).style.display ="none";
	}catch(E){}
}
try{
	showGtype = top.gtypeShowLoveI;
	var xx=showGtype.length;
}catch(E){
	initDate();
	showGtype = top.gtypeShowLoveI;
}

function initDate(){
	top.gtypeShowLoveI =new Array("FTRE","FT","FU","BKRE","BK","BU","BSRE","BS","BSFU","TNRE","TN","TU","VBRE","VB","VU","OPRE","OP","OM");
	top.ShowLoveIarray = new Array();
	top.ShowLoveIOKarray = new Array();
	for (var i=0 ; i < top.gtypeShowLoveI.length ; i++){
		top.ShowLoveIarray[top.gtypeShowLoveI[i]]= new Array();
		top.ShowLoveIOKarray[top.gtypeShowLoveI[i]]= new Array();
	}
}

//更新信用額度max
function reloadCrditFunction(){
    window.reloadPHP1.location.href='reloadCredit.php?uid='+top.uid+'&langx='+top.langx;
	}
function reloadCredit(cash){
	var tmp=cash.split(" ");
	top.mcurrency=tmp[0];
    getMyselfId("credit").innerHTML=cash;
}

// 观看体育视频
function OpenLive(eventid, gtype){
    if (top.liveid == undefined) {
        parent.self.location = "";
        return;
    }
    window.open("/app/member/live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid+"&eventid="+eventid+"&gtype="+gtype,"Live","width=780,height=585,top=0,left=0,status=no,toolbar=no,scrollbars=no,resizable=no,personalbar=no");
}

// 切换旧版
function goToOldVersion() {
    var $goto_old_version = document.getElementsByClassName('goto_old_version')[0] ;
    var username =getCookieAction('username') ; // 用户名
    var psw =getCookieAction('password') ; // 密码
    // 域名处理
    var urlarr = window.location.host.split('.') ;
    var turl ;
    var lurl ;
    if(urlarr.length <3){ // 不带www 域名
        turl = urlarr[0] ; // 取第一位
        lurl = urlarr[1];
    }else{
        turl = urlarr[1] ;  // 取第二位
        lurl = urlarr[2];
    }
   // $goto_old_version.setAttribute('href','http://www.'+turl+'.'+lurl+'/login.php?username='+username+'&password='+psw) ;

}