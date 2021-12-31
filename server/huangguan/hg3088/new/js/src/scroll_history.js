/*window.onload = function (){
    try{
        uid=opener.top.uid;
    }catch(E){
        alert(top.mem_logut);
        window.close();
    }
    if (""+select_date=="undefined") select_date="";

}*/

function showclass(tmp_date){
    if(tmp_date==0){
        document.getElementById("today").className="scr_on";
        document.getElementById("yesterday").className="scr_out";
        document.getElementById("before").className="scr_out";
    }
    if(tmp_date==-1){
        document.getElementById("today").className="scr_out";
        document.getElementById("yesterday").className="scr_on";
        document.getElementById("before").className="scr_out";

    }
    if(tmp_date==-2){
        document.getElementById("today").className="scr_out";
        document.getElementById("yesterday").className="scr_out";
        document.getElementById("before").className="scr_on";

    }
    if(tmp_date==""){
        document.getElementById("today").className="scr_out";
        document.getElementById("yesterday").className="scr_out";
        document.getElementById("before").className="scr_out";
    }
}
function chg_date(tmp_date){
    try{
        uid=parent.uid;
        langx=parent.langx;
    }catch(E){
        window.close();
    }
    self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+tmp_date;
}

function chg_pge(){
    var myOddtype=document.getElementById("select");
    var page_no=myOddtype.value;
    //self.location.href="scroll_history.php?uid="+top.uid+"&langx="+top.langx+"&select_date="+select_date+"&page_no="+page_no;
    try{
        uid=parent.uid;
    }catch(E){
       // alert(top.mem_logut);
        window.close();
    }
    self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+select_date+"&page_no="+page_no;

}
function overbars(obj,color){
    obj.className=color;
}
function outbars(obj,color){
    obj.className=color;
}