function count_down(){var e=document.getElementById("refreshTime");if(setTimeout("count_down()",1e3),"Y"==parent.parent.retime_flag){if(parent.parent.retime<=0)return void("N"==parent.parent.loading_var&&reload_lid());parent.parent.retime--,e.innerHTML=parent.parent.retime}}function reload_lid(){location.reload()}function selall(){var e,r=lid_form.elements.length;e=lid_form.sall.checked;for(var n=1;n<r;n++){var t=lid_form.elements[n];"LID"==t.id.substr(0,3)&&(t.checked=e)}}function select_all(e){var r=lid_form.elements.length,n=e;lid_form.sall.checked=n;for(var t=1;t<r;t++){var l=lid_form.elements[t];"LID"==l.id.substr(0,3)&&(l.checked=n)}}function chk_all(e){e||(lid_form.sall.checked=e)}function back(){parent.parent.parent.leg_flag="Y",parent.LegBack()}