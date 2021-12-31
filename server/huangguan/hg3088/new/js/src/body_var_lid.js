
// 倒计时更新
function count_down(){
    var rt=document.getElementById('refreshTime');
    setTimeout('count_down()',1000);
    if (parent.parent.retime_flag == 'Y'){
        if(parent.parent.retime <= 0){
            if(parent.parent.loading_var == 'N')
                reload_lid();
            return;
        }
        parent.parent.retime--;
        rt.innerHTML=parent.parent.retime;
    }
}
// 刷新
function reload_lid(){
    location.reload();
}
// 全选
function selall(){
    var len =lid_form.elements.length;
    var does=true;
    does=lid_form.sall.checked;
    for (var i = 1; i < len; i++) {
        var e = lid_form.elements[i];
        if (e.id.substr(0,3)=="LID") e.checked = does;
    }
}

function select_all(b){
    var len =lid_form.elements.length;
    var does=b;
    lid_form.sall.checked=does;
    for (var i = 1; i < len; i++) {
        var e = lid_form.elements[i];
        if (e.id.substr(0,3)=="LID") e.checked = does;
    }
}

function chk_all(e){
    if(!e) lid_form.sall.checked=e;
}
function back(){
    parent.parent.parent.leg_flag="Y";
    //window.parent.frames.document.getElementsByClassName('body_browse_set')[0].style.overflowY='scroll' ;
    //self.location.href=links;
    parent.LegBack();
}