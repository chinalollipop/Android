function ClassFankCal(parentClip,setInput,windowEvent){
    var _se = this; // public
    _se.name = "ClassFankCal";
    var _pr = {}; //pravite
    var _myParent = parentClip;
    var _movieClipBox = {};
    var _startObj = new Date(0);
    var _todayObj = new Date();
    var _MindayObj = new Date();
    var _MaxdayObj = new Date();
    var _eventHandler=new Array();
    var _windowEvent = window.event;
    var _isOpen = false;
    var _tmpBox = {};


    var _set = Object();
    _set.cssTest = false; // 是否要開啓tag.title = CSS name;
    _set.cssShow = true; // 是否要開啓預設CSS;
    _set.monthName = ["January","February","March","April","May","June","July","August","September","October","November","December"]; // 月份語系;
    _set.weekName = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]; // 星期語系;
    _set.futureYear = 10; // 未來年份數;
    _set.defaultShow = false; // 預設是否為開啓;
    _set.docu = document;
    _set.outClose = true;
    _set.maxDate = "";
    _set.minDate = "";


    _pr.eventhandler=function(_evt,_eventName,_obj){
        if (_eventHandler[_eventName]!=undefined){
            _eventHandler[_eventName](_evt,_obj);
        }else{
            alert(_se.name+":"+_eventName+" not override !!");
        }
    }

    _se.addEventListener=function(eventname,eventFunction){
        _eventHandler[eventname]=eventFunction;
    }

    _pr.init = function(){
        if(windowEvent!=undefined) _windowEvent = windowEvent;

        _pr.reset_set();
        var _showDiv = _pr.myCreateElement("div");
        _showDiv.style.position = "absolute" ;
        _showDiv.style.display = (_set.defaultShow)?"":"none";
        _movieClipBox["showDiv"] = _showDiv;
        _pr.setClass(_showDiv,"cal_div");

        var _titleDiv = _pr.myCreateElement("div");
        _pr.setClass(_titleDiv,"cal_YearContain");

        var _previousMonth = _pr.myCreateElement("span");
        if(_set.cssShow) _previousMonth.innerHTML = "&lt;";
        _previousMonth.onclick = function(){_pr.chgMonth(-1);};
        _pr.setClass(_previousMonth,"cal_previous");

        var _nextMonth = _pr.myCreateElement("span");
        if(_set.cssShow) _nextMonth.innerHTML = "&gt;";
        _nextMonth.onclick = function(){_pr.chgMonth(+1);};
        _pr.setClass(_nextMonth,"cal_next");

        var _yearSel = _pr.setYearSel();
        var _monthSel = _pr.setMonthSel();

        var _yearLabel = _pr.myCreateElement("label");
        _yearLabel.appendChild(_yearSel);
        _pr.setClass(_yearLabel,"cal_year_label");

        var _monthLabel = _pr.myCreateElement("label");
        _monthLabel.appendChild(_monthSel);
        _pr.setClass(_monthLabel,"cal_month_label");

        _titleDiv.appendChild(_previousMonth);
        _titleDiv.appendChild(_monthLabel);
        _titleDiv.appendChild(_yearLabel);
        _titleDiv.appendChild(_nextMonth);

        var _calDiv = _pr.buildCalender(_todayObj);

        _showDiv.appendChild(_titleDiv);
        _showDiv.appendChild(_calDiv);

        _se.addEventListener("DATE_CHOOSE",_pr.showDateChoose);
        _se.addEventListener("ERROR_DATE",_pr.showErrorDate);

        _myParent.appendChild(_showDiv);

        //_set.docu.onclick = _pr.chkClose;

        if(_set.cssShow) _pr.setStyle();

    }

    _pr.myCreateElement = function(tagName){
        var _elm = _set.docu.createElement(tagName);
        if(tagName!="style"){
            _elm.setAttribute("name","fank_calander_element");
        }

        return _elm;
    }

    _pr.reset_set = function(){
        if(setInput!=undefined){
            for(var _key in _set){
                if(setInput[_key]!=undefined) _set[_key] = setInput[_key];
            }
        }

        return;
    }

    _pr.setYearSel = function(){
        var _start = _startObj.getFullYear();
        var _end = _todayObj.getFullYear();
        var _selObj = _pr.myCreateElement("select");

        for(var i=_start;i<=_end+_set.futureYear;i++){
            var _opt = new Option(i, i, false, false);
            _selObj.options.add(_opt);
        }

        //selObj.setAttribute("id","yearSel");
        _movieClipBox["yearSel"] = _selObj;
        _selObj.value = _end;
        _selObj.onchange = _pr.changeDate;
        _pr.setClass(_selObj,"cal_year");

        return _selObj;
    }

    _pr.setMonthSel = function(){
        var _now = _todayObj.getMonth();
        var _selObj = _pr.myCreateElement("select");

        for(var i=0;i<_set.monthName.length;i++){
            var _opt = new Option(_set.monthName[i], i, false, false);
            _selObj.options.add(_opt);
        }

        //selObj.setAttribute("id","monthSel");
        _movieClipBox["monthSel"] = _selObj;
        _selObj.value = _now;
        _selObj.onchange = _pr.changeDate;
        _pr.setClass(_selObj,"cal_month");


        return _selObj;
    }

    _pr.buildCalender = function(_dateObj){
        var _endDate = _pr.getLastDate(_dateObj);
        var _showDiv = _pr.myCreateElement("div");
        //showDiv.setAttribute("id","calDiv");
        _movieClipBox["calDiv"] = _showDiv;


        /***** week *****/
        for(var i=0;i<_set.weekName.length;i++){
            var _week = _pr.myCreateElement("span");
            if(i==0) _pr.setClass(_week,"cal_week_left");//week.setAttribute("id","week_left");
            else	 _pr.setClass(_week,"cal_week");//week.setAttribute("id","week");
            _week.innerHTML = _set.weekName[i];


            _showDiv.appendChild(_week);
        }

        _showDiv.appendChild(_pr.myCreateElement("br"))

        /***** days *****/
        for(var i=1;i<=_endDate;i++){
            var _tmpObj = new Date();
            _tmpObj.setFullYear(_dateObj.getFullYear());
            _tmpObj.setMonth(_dateObj.getMonth());
            _tmpObj.setDate(i);
            var _weekNO = _tmpObj.getDay();

            ////console.log(tmpObj.getFullYear()+tmpObj.toGMTString()+"||"+weekNO);
            if(i==1) _pr.addSpace(_showDiv,_weekNO,false);

            var _span = _pr.myCreateElement("span");
            _pr.dayShow(_span,_tmpObj);

            _showDiv.appendChild(_span);

            if(_weekNO==6) _showDiv.appendChild(_pr.myCreateElement("br"));

            if(i==_endDate) _pr.addSpace(_showDiv,_weekNO,true);
        }

        return _showDiv;
    }

    _pr.getLastDate = function(_dateObj){
        var _chk = 31;
        var _tmpObj = new Date();

        for(var i=_chk;i>0;i--){
            _tmpObj.setFullYear(_dateObj.getFullYear());
            _tmpObj.setMonth(_dateObj.getMonth());
            _tmpObj.setDate(i);
            if(_dateObj.getMonth()==_tmpObj.getMonth()) return _tmpObj.getDate();
        }

        return 0;

    }

    _pr.addSpace = function(_divObj,_spaceCnt,_isEnd){
        var _start = 0;
        var _end = 0;
        if(_isEnd){
            _start = _spaceCnt+1 ; _end = 7;
        }else{
            _start = 0; _end = _spaceCnt;
        }

        for(var j=_start;j<_end;j++){
            var _span = _pr.myCreateElement("span");
            if(j==0) _pr.setClass(_span,"cal_date_left cal_space");
            else     _pr.setClass(_span,"cal_date cal_space");
            _span.innerHTML = "&nbsp;&nbsp;";
            _divObj.appendChild(_span);
        }
    }

    _pr.dayShow = function(_spanObj,_dateObj){
        _MaxdayObj = new Date();

        var _weekNO = _dateObj.getDay();
        var _goalClass = "";
        var setClick = true;
        var _tempdate="";

        if(	_dateObj.getFullYear()==_todayObj.getFullYear()
            && _dateObj.getMonth()==_todayObj.getMonth()
            && _dateObj.getDate()==_todayObj.getDate()
        ){
            _tmpBox["goalSpan"] = _spanObj;
            _goalClass = "cal_goal";
        }

        if(	(Date.parse(_dateObj)).valueOf() > (Date.parse(_MaxdayObj)).valueOf()){
            _goalClass = "cal_noHand";
            setClick = false;
        }

        if(	(Date.parse(_dateObj)).valueOf() < (Date.parse(_MindayObj)).valueOf()){
            _goalClass = "cal_noHand";
            setClick = false;
        }



        if(_weekNO==0) _pr.setClass(_spanObj,"cal_date_left "+_goalClass);//span.setAttribute("id","left");
        else		 { _pr.setClass(_spanObj,"cal_date "+_goalClass);
        }

        _spanObj.innerHTML = _dateObj.getDate();
        _spanObj.dateObj = _dateObj;
        if(setClick)_spanObj.onclick = function(){_pr.dateChoose(_spanObj);};

        return;

    }

    _pr.getMyDateStr = function(_dateObj){
        var _year = _dateObj.getFullYear();
        var _month = _dateObj.getMonth()*1+1;
        var _date = _dateObj.getDate();

        var _out_put_str = _year+"-";
        _out_put_str+= (_month*1<10)?"0"+_month:_month;
        _out_put_str+= "-";
        _out_put_str+= (_date*1<10)?"0"+_date:_date;

        return 	_out_put_str;
    }

    //=================== event ====================//

    _se.open = function(_x,_y,_date,_input){
        ////console.log(this.name);
        if(_isOpen == true) return _isOpen;

        var _tmpDate = new Date();
        var _tmpMinDate = new Date();
        var _tmpMaxDate = new Date();
        var _tmpStr = _date.split("-");
        ////console.log(_startObj.getFullYear()+"|"+_tmpStr[0]*1);
        if(!_date.match(/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/g)){

            _pr.eventhandler(null,"ERROR_DATE",{"date":_date});
            return ;

        }else if(_startObj.getFullYear()*1 > _tmpStr[0]*1){
            _pr.eventhandler(null,"ERROR_DATE",{"date":_date});
            return ;

        }

        _tmpDate.setFullYear(_tmpStr[0]*1);
        _tmpDate.setMonth(_tmpStr[1]*1-1);
        _tmpDate.setDate(_tmpStr[2]*1);

        _todayObj = _tmpDate;

        var _tmpMin = _set.minDate.split("-");
        var _tmpMax = _set.maxDate.split("-");
        _tmpMinDate.setFullYear(_tmpMin[0]*1);
        _tmpMinDate.setMonth(_tmpMin[1]*1-1);
        _tmpMinDate.setDate(_tmpMin[2]*1);
        _MindayObj = _tmpMinDate;

        _tmpMaxDate.setFullYear(_tmpMax[0]*1);
        _tmpMaxDate.setMonth(_tmpMax[1]*1-1);
        _tmpMaxDate.setDate(_tmpMax[2]*1);
        _MaxdayObj = _tmpMaxDate;

        _movieClipBox["yearSel"].value = _todayObj.getFullYear();
        _movieClipBox["monthSel"].value = _todayObj.getMonth();

        _pr.resetCalDiv(_todayObj);

        _tmpBox["outInput"] = _input;
        _movieClipBox["showDiv"].style.top = _y+"px";
        _movieClipBox["showDiv"].style.left = _x+"px";
        _movieClipBox["showDiv"].style.display = "";

        if (_set.outClose){
            _pr.setEvent(_set.docu,"click",_pr.chkClose);
        }else{
            _isOpen = true;
        }
        return _isOpen;
    }



    _se.close = function(){
        if (_set.outClose) _pr.delEvent(_set.docu,"click",_pr.chkClose);
        _movieClipBox["showDiv"].style.display = "none";
        _isOpen = false;
        try{
            closeDate();
        }catch(e){}
    }

    _pr.chkClose = function(evt){
        evt = evt || _windowEvent;
        var target = evt.target || evt.srcElement ;

        ////console.log(target.getAttribute("name")+"|"+_isOpen);
        if(!_isOpen){
            _isOpen = true;
        }else if(target.getAttribute("name") != "fank_calander_element"){
            _se.close();
        }
    }

    _pr.changeDate = function(){
        ////console.log("asdasad");
        var _yearSel = _movieClipBox["yearSel"]//document.getElementById("yearSel");
        var _monthSel = _movieClipBox["monthSel"];//document.getElementById("monthSel");
        var _dateObj = new Date();

        _dateObj.setFullYear(_yearSel.value);
        _dateObj.setMonth(_monthSel.value);

        _pr.resetCalDiv(_dateObj);

    }

    _pr.chgMonth = function(_editValue){
        var _dateObj = new Date();

        _dateObj.setFullYear(_movieClipBox["yearSel"].value);
        _dateObj.setMonth(_movieClipBox["monthSel"].value*1+_editValue);

        if(_startObj.getFullYear()*1 <= _dateObj.getFullYear()*1){
            _movieClipBox["yearSel"].value = _dateObj.getFullYear();
            _movieClipBox["monthSel"].value = _dateObj.getMonth();
            _pr.resetCalDiv(_dateObj);
        }

    }

    _pr.resetCalDiv = function(_dateObj){
        var _oldCalDiv = _movieClipBox["calDiv"];
        var _calDiv = _pr.buildCalender(_dateObj);
        _movieClipBox["showDiv"].replaceChild(_calDiv, _oldCalDiv);

        return;
    }


    _pr.dateChoose = function(_targetObj){
        ////console.log(_targetObj._dateValue);
        var _dateValue = _pr.getMyDateStr(_targetObj.dateObj);

        _todayObj = _targetObj.dateObj;

        if(_tmpBox["goalSpan"]!=undefined){
            _pr.dayShow(_tmpBox["goalSpan"],_tmpBox["goalSpan"].dateObj);
        }
        _pr.dayShow(_targetObj,_targetObj.dateObj);


        if(_tmpBox["outInput"]!=undefined) _tmpBox["outInput"].value = _dateValue;

        var _obj = {"date":_dateValue};
        _pr.eventhandler(null,"DATE_CHOOSE",_obj);
    }

    _pr.showDateChoose = function(evt,obj){
        alert("event:DATE_CHOOSE obj.date:"+obj.date);
    }

    _pr.showErrorDate = function(evt,obj){
        alert("event:ERROR_DATE obj.date:"+obj.date);
    }

    //=================== util ======================//

    _pr.setClass = function(targetObj,classStr){
        var browserVar = navigator.userAgent.toLowerCase();
        if(browserVar.indexOf("msie") > -1){
            targetObj.className = classStr;
        }else{
            targetObj.setAttribute("class", classStr);
        }

        if(_set.cssTest) targetObj.setAttribute("title", classStr);
        return;
    }

    _pr.setEvent = function(target,eventName,func){

        if(target.attachEvent!=undefined){
            target.attachEvent("on"+eventName,func);
        }else{
            target.addEventListener(eventName,func,false);
        }
        return;
    }

    _pr.delEvent = function(target,eventName,func){

        if(target.detachEvent!=undefined){
            target.detachEvent("on"+eventName,func);
        }else{
            target.removeEventListener(eventName,func,false);
        }
        return;
    }


    _pr.setStyle = function(){
        var _css ="";
        _css+=".cal_div{";
        _css+="width:226px;";
        _css+="background-color:pink;";
        _css+="}";
        _css+=".cal_previous , .cal_next{";
        _css+="display:inline-block;";
        _css+="width:38px;";
        _css+="text-align: center;";
        _css+="}";
        _css+=".cal_date , .cal_date_left , .cal_week , .cal_week_left  {";
        _css+="display:inline-block;";
        _css+="width:30px;";
        _css+="height:26px;";
        _css+="text-align: center;";
        _css+="font-size: 20px;";
        _css+="line-height: 33px;";
        _css+="border-color:skyblue;";
        _css+="border-width: 2px;";
        _css+="border-right-style:solid;";
        _css+="border-bottom-style:solid;";
        _css+="background-color:#DDDDDD;";
        _css+="}";
        _css+=".cal_date_left , .cal_week_left {";
        _css+="border-left-style:solid;";
        _css+="}";
        _css+=".cal_week ,  .cal_week_left{";
        _css+="display:inline-block;";
        _css+="height:22px;";
        _css+="text-align: center;";
        _css+="font-size: 12px; ";
        _css+="background-color: black;";
        _css+="color: yellow; ";
        _css+="line-height: 25px;";
        _css+="}";
        _css+=".cal_date ,.cal_date_left {";
        _css+="cursor:pointer; ";
        _css+="}";
        _css+=".cal_goal{";
        _css+="color: red;";
        _css+="}";


        var _exist = false;
        var _styles = _set.docu.getElementsByTagName('style');

        for(var i=0; i<_styles.length ; i++){
            if(_styles[i].id=="fankCal_css"){
                _exist = true;
            }
        }

        if(!_exist){
            ////console.log("exist");

            _head = _set.docu.head || _set.docu.getElementsByTagName('head')[0],
                _style = _pr.myCreateElement('style');
            _style.setAttribute("id","fankCal_css");

            _style.type = 'text/css';
            if (_style.styleSheet){
                _style.styleSheet.cssText = _css;
            } else {
                _style.appendChild(_set.docu.createTextNode(_css));
            }

            _head.appendChild(_style);

        }

    }

    _se.getSet = function(){
        var _str = "";
        for(var _key in _set){
            _str += _key+":"+_set[_key]+",";
        }

        return _str;
    }

    _pr.init();//constructor
}