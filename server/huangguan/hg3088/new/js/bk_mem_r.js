var ReloadTimeID="";function onLoad(){""+parent.BK_lname_ary=="undefined"&&(parent.BK_lname_ary="ALL"),""+parent.BK_lid_ary=="undefined"&&(parent.BK_lid_ary="ALL"),""+parent.sel_gtype=="undefined"&&(parent.sel_gtype="BK"),""==parent.ShowType&&(parent.ShowType="OU"),"Y"==parent.parent.leg_flag&&(parent.parent.leg_flag="N",parent.pg=0,reload_var()),(parent.loading="N")==parent.loading_var&&(parent.ShowGameList(),obj_layer=document.getElementById("LoadLayer"),obj_layer.style.display="none"),"Y"==parent.retime_flag&&(ReloadTimeID=setInterval("reload_var()",1e3*parent.retime))}function count_down(){if(setTimeout("count_down()",1e3),"Y"==parent.retime_flag){if(parent.retime<=0)return void("N"==parent.loading_var&&reload_var());parent.retime--,obj_cd=document.getElementById("cd"),obj_cd.innerHTML=parent.retime}}function reload_var(e){if(parent.loading_var="Y","up"==e)var a="./"+parent.sel_gtype+"_browse/body_var.php";else a="./body_var.php";parent.body_var.location=a+"?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&league_id="+parent.parent.BK_lid_type}function chg_pg(e){e!=parent.pg&&(parent.pg=e,parent.loading_var="Y",parent.body_var.location="./body_var.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg)}function unload(){clearInterval(ReloadTimeID)}window.onunload=unload;