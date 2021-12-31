/**
 * 针对单双使用
 *
 */

var count_win=false;
//if (self==top) 	self.location.href="http://"+document.domain;

//window.setTimeout("Win_Redirect()", 45000);
var winRedirectTimer=45000;
var winRedirect=0;
window.onload = function (){

	document.getElementById("gold").blur();
	document.getElementById("gold").focus();
	if(resetCheck){
		var reloadTime=document.getElementById("checkOrder");
		reloadTime.checked=resetCheck;
	}
	var reloadautoOdd=document.getElementById("autoOdd");
	
	if(autoOddCheck){
			reloadautoOdd.checked=autoOddCheck;
	}else{
		autoOddCheck=false;
		reloadautoOdd.checked=autoOddCheck;
	}	
	onclickReloadTime();
	resetGold();
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
	check_ioradio();

}

//檢查賠率變色
function check_ioradio(){
	var tmp_ior=document.getElementById("ioradio_r_h").value;
	//alert("tmp_ior="+tmp_ior+",ioradio="+ioradio);
	if (ioradio==""){
		ioradio=tmp_ior;
	}
	if (ioradio!=tmp_ior){
		ioradio=tmp_ior;
		document.getElementById("ioradio_id").className="lightOn";
	}else{
		document.getElementById("ioradio_id").className="light";
	}
		
}
//盤面自動重取賠率 start
function onclickReloadTime(){
	var reloadTime=document.getElementById("checkOrder");
	resetCheck=reloadTime.checked;
	window.clearTimeout(winRedirect);
	if (!reloadTime.checked){
		//winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
	}else{
		winRedirect=window.setTimeout("winReload()", 1000);
	}
}
function onclickReloadAutoOdd(){
	var reloadautoOdd=document.getElementById("autoOdd");
	autoOddCheck=reloadautoOdd.checked;
}
function orderReload(){
	window.location.href=window.location;
}
function resetTimer(){
	//回復reload時間
	onclickReloadTime();
}
function resetGold(){
	if (""+keepGold!="undefined" && keepGold!="" ){
		document.getElementById("gold").value=keepGold;
        CountWinGold_dy_ds_dyh();
	}
}
function winReload(){
	var showTimer=document.getElementById("ODtimer");
    if(showTimer){
        showTimer = showTimer.innerHTML ;
        showTimer=showTimer*1-1;
        document.getElementById("ODtimer").innerHTML=showTimer;
        if (showTimer<=0){
            window.location.reload();
        }else{
            winRedirect=window.setTimeout("winReload()", 1000);
        }
	}

}
function clearAllTimer(){
	window.clearTimeout(winRedirect);
	winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
}
//盤面自動重取賠率 end

function Win_Redirect(){
	//var i=document.all.uid.value;
	//self.location='../select.php?uid='+i;
	parent.close_bet();
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
        CountWinGold_dy_ds_dyh();
		SubChk();
		
	}
	else if((key < 48 || key > 57) && (key > 95 || key < 106)){alert(message015); return false;}


}


$(function () {
    // 2018 新增
    fastBetAction() ;
    setBetFastAction() ;
}) ;