
var ReloadTimeID='';
function onLoad(){
	if (""+parent.BU_lname_ary=="undefined") parent.BU_lname_ary="ALL";	
	if (""+parent.BU_lid_ary=="undefined") parent.BU_lid_ary="ALL";
	if (""+parent.sel_gtype=="undefined") parent.sel_gtype='BU';
	if (parent.ShowType=="") parent.ShowType = 'OU';
	if (parent.parent.leg_flag=="Y"){
		parent.parent.leg_flag="N";
		parent.pg=0;
		reload_var();
	}		
	parent.loading = 'N';
	if(parent.loading_var == 'N'){
		parent.ShowGameList();
		obj_layer = document.getElementById('LoadLayer');
		obj_layer.style.display = 'none';
	}
	if (parent.retime_flag == 'Y'){
		ReloadTimeID = setInterval("reload_var()",parent.retime*1000);
	}

	futureShowGtypeTable();
}

//倒數自動更新時間
function count_down(){
	setTimeout('count_down()',1000);
	if (parent.retime_flag == 'Y'){
	if(parent.retime <= 0){
		if(parent.loading_var == 'N')
			reload_var();
			return;
		}
		parent.retime--;
		obj_cd = document.getElementById('cd');
		obj_cd.innerHTML = parent.retime;
	}
}

function reload_var(Level){
	parent.loading_var = 'Y';
	if(Level=="up"){
		var tmp = "./BK_future/body_var.php";
	}else{
		var tmp = "./body_var.php";
	}
	//parent.body_var.location.reload();
	parent.body_var.location = tmp+"?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&g_date="+parent.g_date+"&league_id="+parent.parent.BU_lid_type;
}

function chg_gdate(){
	var obj_gdate = document.getElementById("g_date");
	var homepage = "./body_var.php?uid="+parent.uid+"&rtype="+parent.rtype+"&g_date="+obj_gdate.value+"&mtype="+parent.ltype+"&league_id="+parent.parent.BU_lid_type;
	//alert(homepage);
	parent.pg=0;
	parent.body_var.location = homepage;
}
 
function chg_pg(pg){
	if (pg==parent.pg)return;
	parent.pg=pg;
	parent.loading_var = 'Y';
	//alert("./body_var.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg)
	parent.body_var.location = "./body_var.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&g_date="+parent.g_date;
	//onload();
 }
function unload(){
	clearInterval(ReloadTimeID);
}
window.onunload=unload;