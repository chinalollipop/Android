function SubChk(){var t=0,a=0,r=document.all.password_safe.value,e=document.all.password.value;document.all.username.value;if(""==r)return document.all.password_safe.focus(),alert(top.str_input_longin_id),!1;if(""==e)return document.all.password.focus(),alert(top.str_input_pwd),!1;if(r.length<6||12<r.length)return alert(top.str_longin_limit1),!1;for(idx=0;idx<r.length;idx++){if(!("a"<=r.charAt(idx)&&r.charAt(idx)<="z"||"A"<=r.charAt(idx)&&r.charAt(idx)<="Z"||"0"<=r.charAt(idx)&&r.charAt(idx)<="9"))return alert(top.str_longin_limit1),!1;("a"<=r.charAt(idx)&&r.charAt(idx)<="z"||"A"<=r.charAt(idx)&&r.charAt(idx)<="Z")&&(a=1),"0"<=r.charAt(idx)&&r.charAt(idx)<="9"&&(t=1)}if(0==t||0==a)return alert(top.str_longin_limit2),!1;ChgPwdForm.submit()}function ChkMem(){var t=document.all.password_safe.value,a=document.all.uid.value;document.getElementById("getData").src="mem_chk.php?uid="+a+"&langx="+langx+"&username="+t}