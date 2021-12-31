var REGULAR_0=[/^d[0-9A-Za-z]{0,}$/,/^d(?![a-zA-Z]+$)[0-9A-Za-z]{5,11}$/];var REGULAR_1=[/^[0-9A-Za-z]{6,12}$/,/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/];var REGULAR_3=[/^(?![0-9]+$)[\a-zA-Z0-9\u4E00-\u9FA5]+$/];var REGULAR_4=[/[0-9A_Za-z_\u4e00-\u9fa5.&=+$%-+@!~*?:,#`^\(\)<>{}\[\]{};'‘’]{2,}/,/^((https|http|ftp|rtsp|mms):\/\/)?(([0-9a-z_!~*'().&=+$%-]+:)?[0-9a-z_!~*'().&=+$%-]+@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/?)|(\/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+\/?)$/];var REGULAR_5=[/[0-9A_Za-z_\u4e00-\u9fa5.&=+$%-+@!~*?:,#`^\(\)<>{}\[\]{};'‘’]{2,}/];var REGULAR_6=[/^(1)([0-9]{10})$/];var REGULAR_7=[/^[1-9][0-9]{4,}$/];var REGULAR_8=[/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/];var REGULAR_10=[/^[0-9]{10,20}$/];var REGULAR_11=[/([\u4e00-\u9fa5]{2})+(.*)/,/^[\u4e00-\u9fa5]{2,}$/];var REGULAR_13=[/^[0-9]{4}$/];var REGULAR_14=[/^[[0-9a-zA-Z]{4}$/];function isMobel(value){var tel=/^1[3|4|5|6|7|8|9|][0-9]{9}$/;if(tel.test(value)){return true}else{return false}}function isChinese(val){var tx=/[\u4E00-\u9FA5]{2,7}/g;return tx.test(val)}function isQQNumber(val){var reg=/^[1-9][0-9]{4,}$/;return reg.test(val)}function isWechat(val){var reg=/^[-_a-zA-Z0-9]{4,25}$/;return reg.test(val)}function isEmailAddress(val){var reg=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;return reg.test(val)}function isBankAccount(val){var reg=/^[0-9]{10,20}$/;return reg.test(val)}function check_null(string){var i=string.length;var j=0;var k=0;var flag=true;while(k<i){if(string.charAt(k)!=" "){j=j+1}k=k+1}if(j==0){flag=false}return flag}function isNum(N){var Ns=/^[A-Za-z0-9]{4,25}$/;if(!Ns.test(N)){return false}else{return true}}function isNumber(val){var reg=/^[0-9]+$/g;return reg.test(val)}function VerifyData(tip){var iconstr='<span class="error-icon">!</span>';var $error=document.getElementById("error_msg");var flag=true;if(removeAllSpace(_$("username").value)==""){setPublicPop("所需帐号不能为空");flag=false;return false}if(!isNum(removeAllSpace(_$("username").value))){setPublicPop("请输入正确的账号！格式：以英文+数字,长度5-15");flag=false;return false}if(removeAllSpace(_$("username").value).length<5||removeAllSpace(_$("username").value).length>15){setPublicPop("账号需在5-15位之间");flag=false;return false}if(removeAllSpace(_$("password").value)==""){setPublicPop("所需密码不能为空");flag=false;return false}if(removeAllSpace(_$("password").value).length<6||removeAllSpace(_$("password").value).length>15){setPublicPop("密码需在6-15位之间");flag=false;return false}if(removeAllSpace(_$("password").value)!=removeAllSpace(_$("password2").value)){setPublicPop("请检查账户密码与确认密码一致");flag=false;return false}var phone=$("#phone").val();var wechat=$("#wechat").val();var qq=$("#qq").val();if(phone!=undefined&&(removeAllSpace(phone)==""||!isMobel(removeAllSpace(phone)))){setPublicPop("请输入正确的手机号码!");flag=false;return false}if(wechat!=undefined&&(!isWechat(removeAllSpace(wechat))||removeAllSpace(wechat)=="")){setPublicPop("请输入正确的微信号码!");flag=false;return false}if(qq!=undefined&&(!isQQNumber(removeAllSpace(qq))||removeAllSpace(qq)=="")){setPublicPop("请输入正确的QQ号码!");flag=false;return false}if(!$(".checkbox-item").hasClass("checked")){setPublicPop("请同意本站协议条款");flag=false;return false}return flag}function agreeMentAction(){$(".checkbox-item").off().on("click",function(){if($(this).hasClass("checked")){$(this).removeClass("checked")}else{$(this).addClass("checked")}})};