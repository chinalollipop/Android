var count_win=false;var winRedirectTimer=45000;var winRedirect=0;window.onload=function(){document.getElementById("gold").blur();document.getElementById("gold").focus();if(resetCheck){var a=document.getElementById("checkOrder");a.checked=resetCheck}var b=document.getElementById("autoOdd");if(autoOddCheck){b.checked=autoOddCheck}else{autoOddCheck=false;b.checked=autoOddCheck}onclickReloadTime();resetGold();parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");check_ioradio()};function check_ioradio(){var a=document.getElementById("ioradio_r_h").value;if(ioradio==""){ioradio=a}if(ioradio!=a){ioradio=a;document.getElementById("ioradio_id").className="lightOn"}else{document.getElementById("ioradio_id").className="light"}}function onclickReloadTime(){var a=document.getElementById("checkOrder");resetCheck=a.checked;window.clearTimeout(winRedirect);if(!a.checked){}else{winRedirect=window.setTimeout("winReload()",1000)}}function onclickReloadAutoOdd(){var a=document.getElementById("autoOdd");autoOddCheck=a.checked}function orderReload(){window.location.href=window.location}function resetTimer(){onclickReloadTime()}function clearAllTimer(){window.clearTimeout(winRedirect);winRedirect=window.setTimeout("Win_Redirect()",winRedirectTimer)}function winReload(){var a=document.getElementById("ODtimer");if(a){var b=a.innerHTML;b=b*1-1;a.innerHTML=b;if(b<=0){window.location.href=window.location}else{winRedirect=window.setTimeout("winReload()",1000)}}}function loadedorderLive(){document.all.gold.focus();try{parent.live_order_height(document.body.scrollHeight)}catch(a){}}function Win_Redirect(){parent.close_bet()}function resetGold(){if(""+keepGold!="undefined"&&keepGold!=""){document.getElementById("gold").value=keepGold;CountWinGold_dy_ds_dyh()}}function CheckKey(a){var b=window.event?a.keyCode:a.which;if(b==32){return false}if(b==13){CountWinGold();SubChk()}else{if((b<48||b>57)&&(b>95||b<106)){alert(message015);return false}}}$(function(){fastBetAction();setBetFastAction()});