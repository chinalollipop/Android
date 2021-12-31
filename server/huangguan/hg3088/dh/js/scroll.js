$(function ($) {
    $.myRollUD = function (options) {
    	_settings = {
    		rollBox:null,
    		parent:null,
    		child:null,
    		showNum:5,
    		prevButton:null,
    		nextButton:null,
    		scrollUlLeft:0,
    		boxWidth:0,
    		totalPage:0,
    		nowPage:1,
    		pageBox:null,
    		pageTotal:null,
    		speed:300,
    		type:"page",
    		singleWidth:0
    	}
    	for (var i in options) {
            if (options.hasOwnProperty(i)) {
                _settings[i] = options[i];
            }
        }
    	_settings.totalPage = Math.ceil(_settings.child.length/_settings.showNum);
    	_settings.pageTotal.text(_settings.totalPage);
    	
    	//初始化设定容器高度
    	rollReady = function(rollBox,child,showNum){
    		var width = parseInt(child.outerWidth(true));
    		_settings.singleWidth = width;
    		rollBox.css("width",width*showNum);
    		_settings.boxWidth = width*showNum;
    	}
    	
    	//向左滚动
    	rollUp = function(){
    		if(_settings.type=='page' && _settings.nowPage>1){
	    		var boxWidth = _settings.scrollUlLeft+_settings.boxWidth;
	    		_settings.nowPage = _settings.nowPage-1;
    		}else if(_settings.type=="single" && parseInt(_settings.parent.css("left"))<0){
    			var boxWidth = _settings.scrollUlLeft+_settings.singleWidth;
    		}
    		rollMove(boxWidth);
    	}
    	
    	//向右滚动
    	rollDown = function(){
    		if(_settings.type=='page' && _settings.nowPage<_settings.totalPage){
	    		var boxWidth = _settings.scrollUlLeft-_settings.boxWidth;
	    		_settings.nowPage = _settings.nowPage+1;
    		}else if(_settings.type=="single" && (parseInt(_settings.parent.css("left"))+((_settings.child.length*_settings.singleWidth)-_settings.boxWidth))>0){
    			var boxWidth = _settings.scrollUlLeft-_settings.singleWidth;
    		}
    		rollMove(boxWidth);
    	}
    	
    	rollMove = function(boxWidth){
    		if(boxWidth!=undefined){
    			_settings.parent.animate(
	    			{left:boxWidth},
	    			_settings.speed,
	    			function(){
	    				_settings.scrollUlLeft = boxWidth;
	    				if(_settings.type=='page')_settings.pageBox.text(_settings.nowPage);
	    			}
	    		)
    		}
    	}
    	
    	rollReady(_settings.rollBox,_settings.child,_settings.showNum);
    	
    	_settings.nextButton.live('click',function(){
			rollDown();
		});
    	_settings.prevButton.live('click',function(){
			rollUp();
		});
    };
})