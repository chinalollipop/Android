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
	//top.keepGold="";
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
//盤面自動重取賠率 start
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
	top.autoOddCheck=reloadautoOdd.checked;
}
function orderReload(){
	window.location.href=window.location;
}
function resetTimer(){
	//回復reload時間
	onclickReloadTime();
}
function resetGold(){
	if (""+top.keepGold!="undefined" && top.keepGold!="" ){
		document.getElementById("gold").value=top.keepGold;
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
	else if((key < 48 || key > 57) && (key > 95 || key < 106)){alert(top.message015); return false;}


}

// function SubChk() {
//  if(document.all.gold.value=='') {
//   document.all.gold.focus();
//   alert(top.message001);
//   return false;
//
//  }else if(!checkInputInt(document.all.gold.value)){
//      document.all.gold.focus();
//      alert(top.message002);
//     return false ;
//  }
//  else if(isNaN(document.all.gold.value) == true)
//  {
//   document.all.gold.focus();
//   alert(top.message002);
//   return false;
//
//  }
//
//  else if(eval(document.all.gold.value*1) < (document.all.gmin_single.value.replace(",",'')*1)){
//   document.all.gold.focus();
//   alert(top.message003+" "+top.mcurrency+" "+document.all.gmin_single.value);
//   return false;
//
//   }
//
//     else if(eval(document.all.gold.value*1) > eval(document.all.gmax_single.value*1)){
//      	document.all.gold.focus();
//      	alert(top.message004+" "+top.mcurrency+" "+document.all.gmax_single.value);
//   return false;
//
//      	}
//   else if (document.all.pay_type.value!='1') //不檢查現金顧客
//   {
//       if(eval(document.all.gold.value*1) > eval(document.all.singleorder.value))
//       {
//        document.all.gold.focus();
//        alert(top.message006+" "+top.mcurrency+" "+document.all.singleorder.value);
//        return false;
//
//       }
//     if((eval(document.all.restsinglecredit.value)+eval(document.all.gold.value*1)) > eval(document.all.singlecredit.value))
//     {
//      document.all.gold.focus();
//      if (eval(document.all.restsinglecredit.value)==0)
//      {
//      	alert(top.message007);
//      }else{
//      	alert(top.message008+document.all.restsinglecredit.value+top.message009);
//      }
//      return false;
//
//     }
//   }
//     else if(eval(document.all.gold.value*1) > eval(document.all.restcredit.value))
//     {
//      document.all.gold.focus();
//      alert(top.message010);
//      return false;
//
//     }
//
//
// if(!confirm(top.message011+document.all.pc.innerHTML+top.message016)){return false;}
// document.all.btnCancel.disabled = true;
// document.all.Submit.disabled = true;
// document.forms[0].submit();
//
// }


$(function () {
    // 2018 新增
    fastBetAction() ;
    setBetFastAction() ;
}) ;