

var count_win=false;
//if (self==top) 	self.location.href="http://"+document.domain;
var winRedirectTimer=45000;
var winRedirect=0;
window.onload = function (){
	//top.keepGold="";
	//window.setTimeout("Win_Redirect()", 45000);
	document.getElementById("gold").blur();
	document.getElementById("gold").focus();
	if(""+top.resetCheck!="undefined"){
		var reloadTime=document.getElementById("checkOrder");
		reloadTime.checked=top.resetCheck;
	}
	var reloadautoOdd=document.getElementById("autoOdd");
	
	if(""+top.autoOddCheck!="undefined"){
			reloadautoOdd.checked=top.autoOddCheck;
	}else{
		top.autoOddCheck=false;
		reloadautoOdd.checked=top.autoOddCheck;
	}	
	onclickReloadTime();
	resetGold();
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
	check_ioradio();

}


//檢查賠率變色
function check_ioradio(){
	var tmp_ior=document.getElementById("ioradio_r_h").value;
	//alert("tmp_ior="+tmp_ior+",top.ioradio="+top.ioradio);
	if (top.ioradio==""){
		top.ioradio=tmp_ior;
	}
	if (top.ioradio!=tmp_ior){
		top.ioradio=tmp_ior;
		document.getElementById("ioradio_id").className="lightOn";
	}else{
		document.getElementById("ioradio_id").className="light";
	}
}
function onclickReloadTime(){
	var reloadTime=document.getElementById("checkOrder");
	top.resetCheck=reloadTime.checked;
	window.clearTimeout(winRedirect);
	if (!reloadTime.checked){
		//winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
	}else{
		winRedirect=window.setTimeout("winReload()", 1000);
	}
}
function onclickReloadAutoOdd(){
	
	var reloadautoOdd=document.getElementById("autoOdd");
	//alert(reloadautoOdd.checked);
	top.autoOddCheck=reloadautoOdd.checked;
}
function resetTimer(){
	//回復reload時間
//document.getElementById("checkOrder").checked	=top.resetCheck;


	onclickReloadTime();
	}
function clearAllTimer(){
	//keep住 reload
//	top.resetCheck=document.getElementById("checkOrder").checked;
//		reloadTime.checked=false;
		window.clearTimeout(winRedirect);
		winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
		//onclickReloadTime();
	}
function winReload(){
	var showTimer=document.getElementById("ODtimer");
	if(showTimer){
        showTimer = showTimer.innerHTML ;
        showTimer=showTimer*1-1;
        document.getElementById("ODtimer").innerHTML=showTimer;
        if (showTimer<=0){
            orderReload();
        }else{
            winRedirect=window.setTimeout("winReload()", 1000);
        }
	}

}
function orderReload(){
	window.location.href=window.location;
}
function loadedorderLive(){
	document.all.gold.focus();
	try{
		parent.live_order_height(document.body.scrollHeight);
	} catch (E) {}	
}
function Win_Redirect(){
	/*
	var i=document.all.uid.value;
	var live="";
	try{
		live= document.getElementById("live").value;
	} catch (E) {}
	self.location='../select.php?uid='+i+'&live='+live;
	*/
	parent.close_bet();
}
function resetGold(){
		if (""+top.keepGold!="undefined" && top.keepGold!="" ){
	
			document.getElementById("gold").value=top.keepGold;
			CountWinGold();
		}
	}
function CheckKey(evt){
	var key = window.event ? evt.keyCode : evt.which;	
	//alert(key)
	//var keychar = String.fromCharCode(key);
	//alert(keychar)
	
	if(key == 32){
		return false;
		}
	
	if(key == 13) {
		CountWinGold();
		SubChk();
		
	}
	else if((key < 48 || key > 57) && (key > 95 || key < 106)){alert(top.message015); return false;}


}


$(function () {
    // 2018 新增
    fastBetAction() ;
    setBetFastAction() ;
}) ;