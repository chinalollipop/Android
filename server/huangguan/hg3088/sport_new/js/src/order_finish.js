
if (self==top) 	self.location.href="http://"+document.domain;
//window.setTimeout("parent.close_bet()", 45000);
window.onload = function (){
	keepGold_PR="";
	try{
		parent.body.orderRemoveALL();
	}catch (E) {}
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
}