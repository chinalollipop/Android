//admin/user_mang/user_browse.php 快速搜尋功能 

function showSearchDlg() {
	var obj_win = document.getElementById('searchDlg');
	obj_win.style.top = document.body.scrollTop+event.clientY+15;
	obj_win.style.left = document.body.scrollLeft+event.clientX-100;
	obj_win.style.display = "block";

	var dlg_option = document.getElementById('dlg_option');
	var dlg_text = document.getElementById('dlg_text');
	dlg_text.value = document.myFORM.search.value;
	dlg_text.focus();
	dlg_text.select();
}

function closeSearchDlg() {
	var obj_win = document.getElementById('searchDlg');
	obj_win.style.top = document.body.scrollTop+event.clientY+15;
	obj_win.style.left = document.body.scrollLeft+event.clientX-20;
	obj_win.style.display = "none";
}
// 查询会员帐号
function submitSearchDlg() {
	var dlg_option = document.getElementById('dlg_option');
	var dlg_text = document.getElementById('dlg_text');
	if(dlg_text.value && dlg_text.value.length>15){
		alert('请输入5-15位帐号或真实姓名!');
		return false ;
	}
	document.myFORM.search.value = dlg_text.value;
	document.myFORM.submit();
}


//更改sort
function changeSort(str) {
    sort = document.myFORM.sort.value;
    orderby = document.myFORM.orderby.value;
    if(str == sort) {
        if(orderby == "ASC") {
            orderby = "DESC";
        } else {
            orderby = "ASC";
        }
    } else {
        sort = str;
        orderby = "ASC";
    }
    document.myFORM.sort.value = sort;
    document.myFORM.orderby.value = orderby;
    document.myFORM.submit();
}

function line_open(tid,name,alias,phone,address,birthday,wechat) {
    var obj_win = document.getElementById('line_type');
    obj_win.style.top = document.body.scrollTop+event.clientY+25;
    obj_win.style.left = document.body.scrollLeft+event.clientX-100;
    var obj = document.getElementById("user_name");
    obj.innerHTML=name;
    var obj = document.getElementById("user_alias");
    obj.innerHTML=alias;
    var p_obj = document.getElementById("user_phone");
    if(p_obj){
        p_obj.innerHTML=phone;   //电话号码
    }

    var obj = document.getElementById("user_address");
    if(obj){
        obj.innerHTML=address;  //取款密码
    }

    // var obj = document.getElementById("user_notes"); //QQ
    // obj.innerHTML=notes;
    document.getElementById('user_birthday').innerHTML= birthday; // 生日
    var w_obj = document.getElementById('user_wechat') ;
    if(w_obj){
        w_obj.innerHTML= wechat; // 微信号码
    }

    obj_win.style.display = "block";
}
function line_close() {
    var obj_win = document.getElementById("line_type");
    obj_win.style.display = "none";
}
//转移会员
function change_line_open(tid,name) {
    var obj_win = document.getElementById('change_line_type');
    obj_win.style.top = document.body.scrollTop+event.clientY+20;
    obj_win.style.left = document.body.scrollLeft+event.clientX-285;
    var obj = document.getElementById("user");
    obj.innerHTML=name;
    document.line_type.tid.value=tid;
    document.line_type.name.value=name;
    obj_win.style.display = "block";
    obj_win.style.backgroundColor='#AACC00';
}
function change_line_close() {
    var obj_win = document.getElementById("change_line_type");
    obj_win.style.display = "none";
}




