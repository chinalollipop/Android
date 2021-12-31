/**
 * by nacky.long
 * 创建自定义弹窗
 * @param param
 * 参数结构：
 param = {
            title:'提示',
            tips:"没有任何提示信息！",
            btnOk:'是',
            btnNo:'否',
            funcOk:function () {
            },
            funcNo:function () {
            }
        }
 */
function popup(content,mark,url) {
    if(!mark){
        param = {
            title:'友情提示',
            tips:content,
            btnOk:'确定',
            funcOk:function () {
                if(url){
                    window.location.href=url;
                }
            },
        }
    }else{
        param = {
            title:'友情提示',
            tips:content,
            btnOk:'确定',
            btnNo:'取消',
            funcOk:function () {
                if(url){
                    window.location.href=url;
                }
            },
            funcNo:function () {
                return false;
            }
        }
    }

    var tipWinObj = document.createElement("DIV");
    // tipWinObj.id = uuid();
    tipWinObj.style.cssText = "position:fixed;z-index:9999;width:300px; height:auto; overflow:hidden;background-color:white; border:none;padding-bottom:10px;";
    tipWinObj.style.top = '30%';
    tipWinObj.style.left = '40%';

    var topDiv = document.createElement("DIV");
    topDiv.style.cssText = "height;30px; line-height:30px; font-size:14px;background-color:#0ba1e4;color:white;";

    var titDiv = document.createElement("DIV");
    titDiv.style.cssText = "float:left; width:80%;margin-left:5px;line-height: 30px;font-size: 14px;background-color: rgb(11, 161, 228);color: white;";
    titDiv.innerHTML = param.title;

    var cross = document.createElement("DIV");
    cross.style.cssText = "float:right; cursor:pointer;margin-right:5px;line-height: 30px;font-size: 14px;background-color: rgb(11, 161, 228);color: white;";
    cross.innerHTML = 'X';

    var clearDiv = document.createElement("DIV");
    clearDiv.style.cssText = "clear:both";

    var contentDiv = document.createElement("DIV");
    contentDiv.style.cssText = "height:auto; overflow:hidden; line-height:24px;padding:0px 10px 10px;text-align:center;margin-top:10px;";
    contentDiv.innerHTML = param.tips;

    var okBtn = document.createElement("BUTTON");
    okBtn.style.cssText = "float:right; width:70px; margin-right:15px;cursor:pointer ";
    okBtn.innerHTML = param.btnOk;

    if(mark){
        var noBtn = document.createElement("BUTTON");
        noBtn.style.cssText = "float:right; width:70px;cursor:pointer;margin-right: 15px;";
        noBtn.innerHTML = param.btnNo;
    }

    topDiv.appendChild(titDiv);
    topDiv.appendChild(cross);
    topDiv.appendChild(clearDiv);
    tipWinObj.appendChild(topDiv);
    tipWinObj.appendChild(contentDiv);
    if(mark){
        tipWinObj.appendChild(noBtn);
    }
    tipWinObj.appendChild(okBtn);

    //获取当前页面的第一个body节点对象,
    var body = document.getElementsByTagName("BODY")[0];
    body.appendChild(tipWinObj);

    //鎖屏DIV
    var bgObj = document.createElement("DIV");
    bgObj.style.cssText = "position:fixed;z-index: 9997;top: 0px;left: 0px;background: #000000;filter: alpha(Opacity=30); -moz-opacity:0.30;opacity:0.30;";
    bgObj.style.width = '100%';
    bgObj.style.height = '120%';
    body.appendChild(bgObj);

    cross.onclick = function () {
        body.removeChild(tipWinObj);
        body.removeChild(bgObj);
    };
    okBtn.onclick = function () {
        param.funcOk();
        body.removeChild(tipWinObj);
        body.removeChild(bgObj);
    };
    if(mark){
        noBtn.onclick = function () {
            param.funcNo();
            body.removeChild(tipWinObj);
            body.removeChild(bgObj);
        };
    }
}