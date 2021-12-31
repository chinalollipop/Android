top.isTestSite = false;
var gtypeAry = new Array("FT","BK","TN","VB","BS","OP","TT","BM","SK");
var notfind = new Object();
var JQ;
var load_jq_complet = false;
var fade_out_sec = 5000;  //賠率變色畫動秒數
var slide_sec = 100; //slide動畫秒數

try{
		if(console){
				//if(!top.isTestSite) setEmpty(console);
				/*
				console.log = emptyFun;
				console.trace = emptyFun;
				console.error = emptyFun;
				console.info = emptyFun;
				console.warn = emptyFun;
				console.table = emptyFun;*/
				//
		}


}catch(e){
		console = new Object();
		//setEmpty(console);

		console.log = emptyFun;
		console.trace = emptyFun;
		console.error = emptyFun;
		console.info = emptyFun;
		console.warn = emptyFun;
		console.table = emptyFun;
}

function emptyFun(){

}

function setEmpty(console){
		console.log = emptyFun;
		console.trace = emptyFun;
		console.error = emptyFun;
		console.info = emptyFun;
		console.warn = emptyFun;
		console.table = emptyFun;
}



var util = new Object();
util.classname = "[util.js]";
try{ util.HttpRequest = HttpRequest; }catch(e){}
try{ util.ParseHTML = ParseHTML; }catch(e){}
util.fail_count = new Object();
util.fail_limit = 10;
util.timeout_sec = 3000;
util.reload_sw = true;
var load_css = false;
var load_js = false;


//go to page
util.goToPage=function(filename, paramObj){
		util.trace(util.classname+"goToPage: "+filename);
		//if(!util.HttpRequest) util.systemMsg("HttpRequest does not load.");

		util.fail_count[filename] = 0;

		paramObj.targetWindow = paramObj.targetWindow || document.getElementsByTagName("body")[0];
		paramObj.targetHead = paramObj.targetHead || document.getElementsByTagName("head")[0];
		paramObj.loadComplete = paramObj.loadComplete || function(){};
		paramObj.param = paramObj.param||"";

		if(paramObj.filename.indexOf(".php")!=-1){
				paramObj.filepath = filename;
				paramObj.method = "POST";
		}else if(paramObj.filename.indexOf(".html")!=-1){
				paramObj.filepath = "/tpl/member/"+top.langx+"/"+filename+".html";
				paramObj.method = "GET";
		}else{
					util.systemMsg("[type error] "+filename);
		}

		var getHttp = new util.HttpRequest();
		getHttp.addEventListener("LoadComplete", function(html){
				util.loadHtmlFinish(html, paramObj);
		});

		getHttp.addEventListener("onError", function(html){
				if(util.reload_sw){
						util.fail_count[filename]++;

						if(util.fail_count[filename]<util.fail_limit){
								window.setTimeout(function(){
										getHttp.loadURL(paramObj.filepath, paramObj.method, paramObj.param);
								}, util.timeout_sec);
						}else{
								util.systemMsg("[load html fail] "+filename+".html");
						}
				}
		});

		getHttp.loadURL(paramObj.filepath, paramObj.method, paramObj.param);

}


//load html finish
util.loadHtmlFinish=function(html, paramObj){
		//util.trace(util.classname+"loadHtmlFinish");
		//if(!util.ParseHTML) util.systemMsg("ParseHTML does not load.");

		var tempHtml = new util.ParseHTML(html);

		//HTML
		dbody = tempHtml.getTag("div")[0];
		paramObj.targetWindow.innerHTML = "";
		if(dbody)paramObj.targetWindow.appendChild(dbody);




		//===== load JS =====
		var js_count = 0;
		jsAry = tempHtml.getTag("script");
		if(jsAry==0){
			
				//paramObj.loadComplete();
				
				
				//===== load CSS =====
				var css_count = 0;
				cssAry = tempHtml.getTag("link");
				if(cssAry.length==0){
					
						paramObj.loadComplete();
						
				}else{
						for(i=0;i<cssAry.length;i++) {
								var cssObj = cssAry[i];
								var _src = cssObj.href;
				
								//util.trace(_src);
				
								util.fail_count[_src] = 0;
				
				
								util.loadCSS(_src, paramObj, function(){
										css_count++;
				
										if(css_count>=cssAry.length){
												//util.trace("[load css finish]");
												//console.log("[load css finish]");
												paramObj.loadComplete();
		
										}
				
								});
				
						}
				}
				//===== load CSS =====
										
		}else{
				for(i=0;i<jsAry.length;i++) {
						var jsObj = jsAry[i];
						var _src = jsObj.src;

						util.fail_count[_src] = 0;
						//util.trace(_src);

						util.loadScript(_src, paramObj, function(){


								js_count++;
								//util.trace("load js: "+js_count);

								if(js_count>=jsAry.length){
										//console.log("[load js finish]");
										//paramObj.loadComplete();
										
										
										
										//===== load CSS =====
										var css_count = 0;
										cssAry = tempHtml.getTag("link");
										if(cssAry.length==0){
											
												paramObj.loadComplete();
												
										}else{
												for(i=0;i<cssAry.length;i++) {
														var cssObj = cssAry[i];
														var _src = cssObj.href;
										
														//util.trace(_src);
										
														util.fail_count[_src] = 0;
										
										
														util.loadCSS(_src, paramObj, function(){
																css_count++;
										
																if(css_count>=cssAry.length){
																		//util.trace("[load css finish]");
																		//console.log("[load css finish]");
																		paramObj.loadComplete();
								
																}
										
														});
										
												}
										}
										//===== load CSS =====
										
								}

						});
				}
		}
		//===== load JS =====



		

		
		

}

/*
function load_complete(_type, loadFun){
	
		//load_count++;
		
		switch(_type){
			case "css":
				load_css = true;
				break;
			case "js":
				load_js = true;
				break;
			default:
				break;
		}
		
		console.log("[load_complete]"+_type+",css="+load_css+",js="+load_js);
		
		if(load_css && load_js){
		//if(load_count>=2){
				console.log("[load_complete]");
				loadFun();
				load_css = false;
				load_js = false;
				//load_count = 0;
		}
}
*/

//load css
util.loadCSS=function(_src, paramObj, loadFun){
		//util.trace(util.classname+"loadCSS: "+_src);
		var css = document.createElement("link");
		css.setAttribute("rel", "stylesheet");
		css.setAttribute("type", "text/css");
		css.setAttribute("href", _src);

		css.onload=function(){
				//util.trace("load css finish: "+_src);
				//console.log("load css finish: "+_src);
				if(loadFun) loadFun();
		};

		//IE is not working
		css.onerror=function(){
				//util.trace("load css fail: "+_src);

				if(util.reload_sw){
						util.fail_count[_src]++;

						if(util.fail_count[_src]<util.fail_limit){

							window.setTimeout(function(){
									paramObj.targetHead.removeChild(css);
									util.loadCSS(_src, paramObj, loadFun);
							},util.timeout_sec);

						}else{
								var tmp_src = _src.split("/");
								util.systemMsg("[load css fail] "+tmp_src[tmp_src.length-1]);
						}
				}
		};

		paramObj.targetHead.appendChild(css);


}

//load script
util.loadScript=function(_src, paramObj, loadFun){
		//util.trace(util.classname+"loadScript: "+_src);
		//if(!util.HttpRequest) util.systemMsg("HttpRequest does not load.");

		var getHttp = new util.HttpRequest();
		getHttp.addEventListener("LoadComplete",function(html){

				var script = document.createElement("script");
				script.setAttribute("type","text/javascript");
				script.text = html;
				paramObj.targetHead.appendChild(script);

				if(loadFun) loadFun();

		});

		getHttp.addEventListener("onError", function(html){

				if(util.reload_sw){
						util.fail_count[_src]++;

						if(util.fail_count[_src]<util.fail_limit){
								window.setTimeout(function(){getHttp.loadURL(_src,"GET","");}, util.timeout_sec);
						}else{
								var tmp_src = _src.split("/");
								util.systemMsg("[load script fail] "+tmp_src[tmp_src.length-1]);
						}
				}
		});

		getHttp.loadURL(_src,"GET","");

}


//print stack trace
util.printStackTrace=function(code){
	/*
		var _this = arguments.callee.caller;
		var msg = "Stack trace:";
		var base = "\n";
		if(code) msg=code+base+msg;
		while(_this.caller){
				var param = util.getArguments(_this.caller.arguments);
				msg+=base+"function "+_this.caller.name+"("+param+")";
				//msg+=base+"function "+_this.caller.name;
				//msg+=base+"function "+_this.caller;
				_this = _this.caller;
		}

		console.log(msg);
	*/
	console.trace();
}

//get arguments
util.getArguments=function(obj){
		var ret = new Array();
		for(var _key in obj){
				var content = obj[_key];
				if(content!=null){
						if(content.length > 10) content=content.substr(0,10)+"...";
				}
				ret.push(typeof(obj[_key])+" ["+content+"]");


				//ret.push(typeof(obj[_key]));
		}
		return ret.join(",");
}

//print Hash
util.printHash=function(obj, _title){

		var count = 0;
		var str = "";

		if(_title!=null) str+="["+_title+"]\n";

		for(key in obj){
				str+=key+"======>"+obj[key]+"\n";
				count++;
		}
		str+="length======>"+count+"\n";
		util.trace(util.classname+str);
}


//http or https
util.getProtocal=function(){
		return document.location.protocol;
}


util.getWebDomain=function(){
		return document.domain;
}


util.getNowDomain=function(){
		return util.getProtocal()+"//"+util.getWebDomain();
}


//system msg
util.systemMsg=function(msg, isStack){
		console.warn(msg);
		if(isStack!=false) util.printStackTrace();
}

//trace
util.trace=function(msg, isStack){
		if(top.isTestSite){
				console.log(msg);
				//isStack = true;
				if(isStack) util.printStackTrace();
		}
}

util.showTxt=function(txt){
		if(txt+""=="undefined"||txt+""=="null"||txt+""=="NaN")  return "";
		return txt;
}

util.isIPad=function(){
		var agent = navigator.userAgent;
		if(agent.indexOf("iPad")!=-1){
				return true;
		}		
		return false;		
}

//含IE8以下
util.isIE8=function(){
		var ret = false;
		var agent = navigator.userAgent;
		var ie = "MSIE";
		var pos = agent.indexOf(ie);
		//Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C)

		if(pos!=-1){
				var tmp_agent = agent.substring(pos+ie.length,agent.length);
				var str = tmp_agent.indexOf(".");
				var version = tmp_agent.substring(0, str);
				if(version*1<=8) ret = true;
		}
		return ret;
}
util.checkBrowser=function (){
	var ret = false;
	var agent = navigator.userAgent;

	if(agent.indexOf("rv:11")!=-1||agent.indexOf("Firefox")!=-1||agent.indexOf("Edge")!=-1){
		//if(agent.indexOf("Firefox")!=-1){
		ret=true;
	}
	return ret;
}
util.isIE11=function(){//ie11 edge
var ret = true;
var agent = navigator.userAgent;
var ie = "MSIE";
var pos = agent.indexOf(ie);
var brows = new Array("Chrome","Safari","Firefox");
	if(agent.indexOf("Edge")== -1){
		for(var i=0;i<brows.length;i++){
				if(agent.indexOf(brows[i]) != -1){
					ret = false;
					break;
				}
		}
	}
	return ret;
}

//set obj class
util.setObjectClass=function(targetObj,classStr){
		if(targetObj.className!=undefined){
				targetObj.className = classStr;
		}else{
			try{
				targetObj.setAttribute("class", classStr);
			}catch(e){}
		}
}


//get obj class
util.getObjectClass=function(targetObj){
		if(targetObj.className!=undefined){
				return targetObj.className;
		}else{
				return targetObj.getAttribute("class");
		}
}

util.reachBottom=function(DOC){
    var scrollTop = 0;
    var clientHeight = 0;
    var scrollHeight = 0;
    if (DOC.documentElement && DOC.documentElement.scrollTop) {
        scrollTop = DOC.documentElement.scrollTop;
    } else if (DOC.body) {
        scrollTop = DOC.body.scrollTop;
    }
    if (DOC.body.clientHeight && DOC.documentElement.clientHeight) {
        clientHeight = (DOC.body.clientHeight < DOC.documentElement.clientHeight) ? DOC.body.clientHeight: DOC.documentElement.clientHeight;
    } else {
        clientHeight = (DOC.body.clientHeight > DOC.documentElement.clientHeight) ? DOC.body.clientHeight: DOC.documentElement.clientHeight;
    }
    scrollHeight = Math.max(DOC.body.scrollHeight, DOC.documentElement.scrollHeight);
    if (scrollTop + clientHeight == scrollHeight) {
        return true;
    } else {
        return false;
    }
}

util.getObjAbsolute_new=function(obj,stop_name){
		var abs = new Object();

		abs["left"] = obj.offsetLeft;
		abs["top"] = obj.offsetTop;

		while(obj = obj.offsetParent){
			////console.log(obj);
			////console.log(obj.offsetLeft+" >> "+obj.offsetTop);
				if(util.getStyle(obj,"position") == "relative"){
						////console.log(obj.id+"|"+obj.offsetParent.id+"|"+_self.getStyle(obj,"top")+"|"+_self.getStyle(obj,"margin-top")+"|"+obj.offsetTop);
						if((obj.id!="" && obj.offsetParent.id!="") && util.getStyle(obj,"top")!="auto" && util.getStyle(obj,"margin-top")!="auto" && util.getStyle(obj,"margin-top")!="0px"){
								abs["top"] += -obj.offsetTop;
								continue;
						}
				}

				if(stop_name!=undefined && obj.id==stop_name){
						break;
				}else if(util.getStyle(obj,"position") == "absolute"){
						break;
				}

				abs["left"] += obj.offsetLeft;
				abs["top"] += obj.offsetTop;
		}

	return abs;
}


util.getObjAbsolute=function(obj){
		var _abs = new Object();

		_abs["left"] = obj.offsetLeft;
		_abs["top"] = obj.offsetTop;

		while (obj = obj.offsetParent) {
			_abs["left"] += obj.offsetLeft;
			_abs["top"] += obj.offsetTop;
		}

		return _abs;
}


util.getStyle=function(oElm,strCssRule){
		var strValue = "";
		if(document.defaultView && document.defaultView.getComputedStyle){
				strValue = document.defaultView.getComputedStyle(oElm,"").getPropertyValue(strCssRule);
		}else if(oElm.currentStyle){
				strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1){
						return p1.toUpperCase();
				});
				strValue = oElm.currentStyle[strCssRule];
		}else{
				return "error";
		}
		return strValue;
}


util.clearObject=function(obj){
		for(var key in obj){
				delete obj[key];
		}
		return obj;
}

util.clearArray=function(ary){
		ary.length = 0;
		return ary;
}


function getChildAry(objAry, _id, newAry){

		for(var i=0; i<objAry.length; i++){
				var obj = objAry[i];

				if(obj.getAttribute("id")==_id){
						newAry.push(obj);
				}

				if(obj.children.length > 0){
						getChildAry(obj.children, _id, newAry);
				}

		}
		return newAry;
}

function iframe_onError(iframe,errorfunc){
	try{
		check = iframe.contentWindow.document.body.onload;
	}catch(e){
		check = null;
	}
	if(check == null && iframe.loadsrc != undefined ){
		iframe.times = iframe.times || 0;
		errorfunc(iframe);
	}else{
		iframe.times = 0;
		try{
			iframe.loadsrc = ""+iframe.contentWindow.location;
		}catch(e){}
	}
}

function showerror(e){
	e.times+=1;
  if(e.times > 10)	return;
	setTimeout(function(){e.contentWindow.location=e.loadsrc;},5000);
}


function iframe_src(obj, url){

		if(obj!=null&&obj.tagName!=null&&url!=null){
     //2017.0112 johnson 斷線時記錄url
        obj.loadsrc = url;
        
				obj.contentWindow.location = url;
		}
}
function divOnBlur(showdiv,selid){
	//console.log("divOnBlur======>"+showdiv.id);
	selid.onclick=null;
	showdiv.style.display='';
	showdiv.focus();

}

function initDivBlur(showdiv,selid){
	showdiv.tabIndex=100;
	showdiv.onblur=function(){
		showdiv.style.display='none';
		setTimeout(function(){
			selid.onclick=function(){
				//alert("onblur");
				divOnBlur(showdiv,selid);
				document.body.scrollTop = "0";
				}
		},300);
	};

}

function iframe_src_new(obj, url){
	//console.log("util iframe_src_new"+obj+","+obj.tagName+","+url);
		if(obj!=null&&obj.tagName!=null&&url!=null){
				
				//console.log("util.checkBrowser()"+util.checkBrowser());
				if(util.checkBrowser()){
					iframe_src(obj, url);
				return;
				}

				var _id = obj.getAttribute("id");
				var bakObj = document.getElementById(_id+"_bak");

				if(bakObj==null||bakObj.tagName==null){
						trace("obj"+obj);
						bakObj = obj.cloneNode(false);
						bakObj.setAttribute("id", _id+"_bak");
						bakObj.style.display = "none";
						obj.parentNode.appendChild(bakObj);

				}
				bakObj.contentWindow.location = url;
				//console.error(bakObj.innerHTML);
		}
}

//when iframe loaded and parse screen finish
function iframe_rename(_id, Parent){
	if(util.checkBrowser()){

		var dom = (Parent)?Parent.document:document;
		var orgObj = dom.getElementById(_id);
		orgObj.style.display = "";
				return;
				}



		var dom = (Parent)?Parent.document:document;
		var orgObj = dom.getElementById(_id);
		var bakObj = dom.getElementById(_id+"_bak");


		if(orgObj==null||orgObj.tagName==null||bakObj==null||bakObj.tagName==null){
				return;
		}


		var orgName = _id;
		var bakName = _id+"_bak";

		orgObj.setAttribute("id", bakName);
		bakObj.setAttribute("id", orgName);


		dom.getElementById(_id).style.display = "";
		dom.getElementById(_id+"_bak").style.display = "none";

		//iframe_src(dom.getElementById(_id+"_bak"), "about:blank");
		dom.getElementById(_id+"_bak").parentNode.removeChild(dom.getElementById(_id+"_bak"));

}
function getKeyCode(e){
		return (window.event)?window.event.keyCode:e.which;
}
function iframe_onload(iframe, fun){
		//if(fun==null) return;

		//IE (before finish init)
		/*
		iframe.onreadystatechange = function(){
        if (iframe.readyState == "complete"){
            alert("Local iframe is now loaded.");
        }
    };
    */

    //IE (after finish init)
		if(iframe.attachEvent){
		    iframe.attachEvent("onload", function(){
		        //trace("attachEvent");
		        if(fun) fun();
		    });

		//other (after finish init)
		}else{
		    iframe.onload=function(){
		        //trace("onload");
		        if(fun) fun();
		    };
		}
}

function echo(msg){
		if(document.all){
				alert(msg);
		}else{
				console.log(msg);
		}
}
var elemtAll=null;
var aa = false;
var bb = this.name;
document.getElementById=function(_id){
	if(bb=="body"){
			if (elemtAll==null) elemtAll=document.getElementsByTagName("*");
			obj=elemtAll[_id];
	}else{
			obj=document.getElementsByTagName("*")[_id];
	}
	if(obj==null){
			if(notfind[_id]==null){
					obj = new Object();
					obj.style = new Object();
					obj.getAttribute = emptyFun;
					obj.setAttribute = emptyFun;
					obj.innerHTML = emptyFun;
					notfind[_id] = obj;
			}else{
				obj = notfind[_id];
			}
	}
	return obj;
}

function clearElementAll(){
		elemtAll=null;
}

/*
document.getElementById=function(_id){
 		var newAry = new Array();
 		var bodyObj = document.getElementsByTagName("body")[0];
 		var objAry = null;
 		var obj = null;

 		if(bodyObj!=null&&_id!=null){
 				objAry = bodyObj.children;
 				if(bodyObj.getAttribute("id")==_id){
 						obj = bodyObj;
 				}else{
 						obj = getChildAry(objAry, _id, newAry)[0];
 				}
		}

		if(obj==null){

				if(notfind[_id]==null){
						obj = new Object();

						obj.style = new Object();
						obj.getAttribute = emptyFun;
						obj.setAttribute = emptyFun;
						obj.innerHTML = emptyFun;
						notfind[_id] = obj;
				}

				obj = notfind[_id];
				//console.warn("Object \""+_id+"\" is not exist.");
				//if(top.isTestSite) console.trace();
				////util.systemMsg("Object \""+_id+"\" is not exist.");

		}

		return obj;
}
*/

function loadComplet(){
		load_jq_complet = true;
}
/*
try{
		var _src = "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js";
		var paramObj = new Object();
		paramObj["targetHead"] = document.getElementsByTagName("head")[0];
		util.loadScript(_src, paramObj, loadComplet);
}catch(e){
		//console.error(e.toString());
}
*/
JQ = new JQAnimate();

function JQAnimate(){
  var _self = this;
  _self.init=function(){

  }

  //hide
  _self.hide=function(divname, speed, callback){
  		try{
	  			$(divname).hide(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

  //show
  _self.show=function(divname, speed, callback){
  		try{
	  			$(divname).show(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

  //hide/show
  _self.toggle=function(divname, speed, callback){
  		try{
	  			$(divname).toggle(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//slide up
  _self.slideUp=function(divname, speed, callback){
  		try{
	  			$(divname).slideUp(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//slide down
  _self.slideDown=function(divname, speed, callback){
  		try{
	  			$(divname).slideDown(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//slide up/down
  _self.slideToggle=function(divname, speed, callback){
  		try{
	  			$(divname).slideToggle(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//fade in
  _self.fadeIn=function(divname, speed, callback){
  		try{
	  			$(divname).fadeIn(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//fade out
  _self.fadeOut=function(divname, speed, callback){
  		try{
	  			$(divname).fadeOut(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//fade in/out
  _self.fadeToggle=function(divname, speed, callback){
  		try{
	  			$(divname).fadeToggle(speed, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

	//fade to
  _self.fadeTo=function(divname, speed, opacity, callback){
  		try{
	  			$(divname).fadeTo(speed, opacity, callback);
	  	}catch(e){
	  			console.error(e.toString());
	  			throw new Error("error");
	  	}
  }

  //focus out
  _self.focusOut=function(divname, callback){
  		//console.log("set focus out=====>"+divname+","+callback);
  		try{
	  			$(divname).focusout(function(){
	  					return _self.transFun(callback);
	  			});

	  	}catch(e){
	  			console.error(e.toString());
	  			/*
	  			if(!load_jq_complet){
	  					setTimeout(function(){_self.focusOut(divname, callback)}, 1000);
	  			}
	  			*/
	  	}
  }

  _self.transFun=function(callback){

			//if(typeof callback=="function"){
			//		return callback();
			//}
			if(typeof callback=="string"){
					return new Function("return "+callback)();
			}
			return null;
	}

}
