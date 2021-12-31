<?php
session_start();

include "../../../../app/member/include/config.inc.php";
require_once("../../../../../common/mg/config.php");
require_once("../../../../../common/ag/config.php");
require_once("../../../../../common/cq9/config.php");

// AG电子游戏列表
foreach ($agXinGames as $k => $v){
    $agGameList[$k]['gameid'] = $v['gameTypeW'];
    $agGameList[$k]['name'] = $v['name'];
    $agGameList[$k]['gameurl'] = '/images/game/ag/'.$v['gameurl'];
}
$agGameList = array_values($agGameList);

// WM电子游戏列表
foreach ($aWmGames as $k => $v){
    $mwGameList[$k]['gameId'] = $v['gameId'];
    $mwGameList[$k]['gameName'] = $v['gameName'];
    $mwGameList[$k]['gameIcon'] = $v['gameIcon'];
    $mwGameList[$k]['gameRuleUrl'] = $v['gameRuleUrl'];
}

// CQ9电子游戏列表
foreach ($aCqGames as $k => $v){
    $cqGameList[$k]['gameid'] = $v['gameid'];
    $cqGameList[$k]['name'] = $v['name'];
    $cqGameList[$k]['gameurl'] = '/images/game/cq9/'.$v['gameurl'];
}

// MG电子游戏列表
foreach ($mgGamesInfo as $k => $v){
    $mgGamesInfo[$k]['gameid'] = $v['item_id'];
}
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg mw cq

?>

<style>
    .pagination{background: #272727;}
    .pagination a{color: #fff;}
    .jackport{width: 546px;margin-top: 30px;}
    .jackport .t_num i{width: 43px;height: 52px;display:inline-block;background:url(<?php echo TPL_NAME;?>images/number.png) no-repeat;background-position:0 0;}
    .jackport .t_num i:nth-child(12){display:none}
    .jackport .t_num span{color:#269cff;font-size:20px;font-weight:800;margin: 0 -5px;}
    .jackpot{position:relative}
    .jackpot>div{display: inline-block;position: relative;}
    .jackpot .jackpot_left{margin-left: 70px;}
    .jackpot .jackpot_center{text-align:center;margin: 95px 0 0 400px;}
    .jackpot .jackpot_center div:first-child{width:453px;height:149px;background:url(images/game/game_jc.png) center no-repeat;background-size:100%;margin-left: 15px;}
    .jackpot .jackpot_center div:nth-child(2){font-size:30px;color:#efd08b;font-weight:600}
    .jackpot .jackpot_left span,.jackpot .jackpot_right span{display: inline-block;position: absolute;}
    .jackpot .jackpot_right{position:absolute;width:690px;z-index:1;margin-left: -55px;}
    .jackpot .jackpot_right .right_1{width:404px;height:388px;background:url(images/game/game_right.png) no-repeat;background-size:96%;margin-top: 45px;animation: rightToCenter 1s forwards;}
    .jackpot .jackpot_right .right_2{width:79px;height:80px;background:url(images/game/game_icon_1.png) no-repeat;background-size:100%;margin-left: 275px;animation: right-pig-move 8s infinite alternate both;}
    .jackpot .jackpot_left .left_1{width: 79px;height: 80px;background: url(images/game/game_icon_3.png) no-repeat;background-size:100%;top: 109px;left: -40px;animation: right-pig-move 8s infinite alternate both;}
    .jackpot .jackpot_left .left_2{width: 330px;height: 428px;background: url(images/game/game_left.png) no-repeat;background-size:98%;animation: leftToCenter 1s forwards;}
    .jackpot .jackpot_left .left_3{width: 60px;height: 61px;background: url(images/game/game_icon_2.png) no-repeat;background-size:100%;left: 270px;top: 110px;animation: left-pig-move 8s infinite alternate both;}
    .qgx{height:150px;width:2px;display:inline-block}
    .winning_name{margin:8px 0}
    .winning_name span{margin-right:20px}
    .winning_name span:last-child{color:#efd08b}
    .slots .searchBar{background:#272727;border: 1px solid #363636;border-radius:10px 10px 0 0;padding:15px 30px}
    .slots .searchBar h3{width:144px;height:70px;line-height:70px;font-size:18px;color:#fff;text-align:center;font-weight:700}
    .slots .searchBar .inputBox{width:200px;height:37px;border-radius:5px;border:1px solid #ccc;position:relative}
    .slots .searchBar .inputBox .searchInput{color:#fff;height:30px;line-height:30px;background:none;border:none;margin:4px 0 0 0;padding-left:5px}
    .slots .searchBar .advance .typeOfGame .cbox-row .cbox-label,.slots .searchBar .advance .typeOfGame .cbox-row a,.slots .searchBar .advance .typeOfGame .cbox-row span,.slots .searchBar .btnBox,.slots .searchBar .btnBox .btn1,.slots .searchBar .keywordsBox,.slots .searchBar .keywordsBox h4,.slots .searchBar .keywordsBox ul,.slots .searchBar .keywordsBox ul li,.slots .searchBar h3,.slots .slotsGame .award span,.slots .slotsGame .gameBox,.slots .slotsGame .king .ico,.slots .slotsGame .star .ico,.slots .topList .part.textPart .likeList ul li .likeIco,.slots .topList .part.textPart .likeList ul li p,.slots .topList .part.textPart .likeList ul li span,.fl{float:left;*display:inline}
    .slots .searchBar .inputBox .ico{position:absolute;width:30px;height:30px;background:url(images/game/ss.png) center no-repeat;cursor:pointer;transition:all .5s ease;top:3px;right:0}
    .game_title_bottom{margin-top:15px;padding:30px 0 15px;border-top:1px solid #f2f2f2}
    .game_title_bottom .game_yxfl a{position:relative;display:inline-block;color:#fff;padding:5px 0 5px 40px;font-size:16px;margin-right:25px}
    .game_title_bottom .game_yxfl a.active{color:#47a4f7}
    .game_title_bottom .game_yxfl a:before{position:absolute;content:'';display:inline-block;width:26px;height:30px;background:url(images/game/tip_icon.png) no-repeat;background-position:-31px 2px;transition:all 0.2s ease-in-out;left:5px;top:-5px}
    .game_title_bottom .game_yxfl a:nth-child(2):before{background-position:-169px 0px}
    .game_title_bottom .game_yxfl a:last-child:before{background-position:-97px 0px}
    .game_title_bottom .game_yxfl a.active:before{background-position-y:-35px}
    .game_choose{margin-top:15px;}
    .game_choose ul{margin-left:10px;}
    .game_choose ul li.mw_li{padding-left: 45px;}
    .game_choose ul li.cq_li{padding-left: 50px;}
    .game_choose ul li{transition:.3s;position:relative;cursor:pointer;float:left;width:150px;height:50px;background:#fff;color:#000;margin:0 10px;border-radius:50px;text-align:center;line-height:50px;font-size:20px;box-shadow:0px 0px 0px 1px #eae5e5;padding-left:30px}
    .game_choose ul li.active{background: #fa9602;background: linear-gradient(to right,#fa9602 0%,#fec707 100%);color:#fff}
    .game_choose ul li:before{position:absolute;content:'';display:inline-block;width:55px;height:40px;margin:5px 10px;left:5px;background:url(images/game/title_icon.png) no-repeat;background-position: -11px -3px;}
    .game_choose ul li.active:before{background-position-y: -40px !important;}
    .game_choose ul li.mg_li:before{background-position:-85px -3px}
    .game_choose ul li.mw_li:before{width: 60px;background-position:-248px -3px}
    .game_choose ul li.cq_li:before{width: 65px;background-position:-162px -3px}
    .game_choose ul li.fg_li:before{background-position:-328px -3px}
    .slots .slotsGame{width:100%;min-height:360px}
    .slots .hot-game-list{background:#272727;border:1px solid #363636;overflow:hidden;padding:10px 20px}
    .slots .slotsGame .gameBox{position:relative;width:165px;border-radius:5px;transition:all .5s ease;margin:0 13px 20px;height:194px;border:1px solid #ccc}
    .slots .slotsGame .gameBox .imgBox img{width:100%;height:100%}
    .slots .slotsGame .gameBox .gameName{width:100%;height:46px}
    .slots .searchBar,.slots .slotsGame .gameBox .gameName,.slots .slotsGame .gameBox .imgBox,.slots .slotsTitle,.slots .topList,.slots .topList .part.textPart .likeList,.slots .topList .part h2,.slots .typeOfGame,.pr{position:relative}
    .slots .slotsGame .gameName h3{height:45px;line-height:45px;font-size:14px;font-weight:400;color:#fff;z-index:1;text-align: center;}
    .slots .slotsGame .gameBox .imgBox{width:165px;height:149px;border-radius:5px 5px 0 0;overflow:hidden}
    .slots .slotsGame .hr{width:165px;height:1px;margin:0}
    .slots .slotsGame .btn1{display:block;width:60%;height:35px;line-height:35px;font-size:14px;color:#fff;text-align:center;background:linear-gradient(to bottom,#f3af1a,#ffcb59);border-radius:3px;margin:15px auto;transition:.3s}
    .slots .slotsGame .btn1:first-child{margin-top:20px}
    .slots .slotsGame .btn1.btn_mg:first-child{margin-top:47px}
    .slots .slotsGame .btn1:hover{opacity:.9}
    .slots .slotsGame .gameBox .mask{position:absolute;width:100%;height:149px;left:0px;bottom:45px;z-index:1;transition:all 0.3s;background:rgba(0,0,0,.4)}
    .gameList_all{box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, .1)}

    @keyframes leftToCenter {
        0% {opacity: .2;transform:  translate(-400px, 0);}
        100% {opacity: 1;transform: translate(0, 0);}
    }
    @keyframes rightToCenter {
        0% {opacity: .2;transform:  translate(400px, 0);}
        100% {opacity: 1;transform: translate(0, 0);}
    }

</style>

<div class="game_bg">
    <div class="slots">
        <div id="new-banner" style="background:url(<?php echo TPL_NAME;?>images/nav_dz.jpg) no-repeat center top; height:213px;">

        </div>

        <div id="sidebarwrap">
            <div class="gameList_all w_1200">
                <div class="searchBar">
                    <div class="game_choose">
                        <ul>
                            <!--<li class="game_active">热门游戏</li>
                            <li>漫威热门系列</li>-->
                            <li class="ag_li <?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag">AG电子</li>
                            <li class="mg_li <?php echo $gametype=='mg'?'active':''; ?>" data-gametype="mg">MG电子</li>
                            <li class="mw_li <?php echo $gametype=='mw'?'active':''; ?>" data-gametype="mw">MW电子</li>
                            <li class="cq_li <?php echo $gametype=='cq'?'active':''; ?>" data-gametype="cq">CQ9电子</li>
                            <li class="fg_li <?php echo $gametype=='fg'?'active':''; ?>" data-gametype="fg">FG电子</li>
                            <!--<li>电影老虎机</li>
                            <li>纸牌游戏</li>-->
                        </ul>
                        <div style="clear: both"></div>
                    </div>
                    <div class="game_title_bottom">
                        <div class="fl game_yxfl">
                            <a href="javascript:;" class="active" data-type="all">全部游戏</a>
                            <a href="javascript:;" data-type="rm">热门游戏</a>
                            <a href="javascript:;" data-type="new">最新游戏</a>
                        </div>
                        <div class="inputBox fr">
                            <label>
                                <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="搜索游戏">
                            </label>
                            <div class="submit-btn ico"></div>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                </div>
                <!--                游戏-->
                <div class="slotsGame" id="gameSearch11">
                    <div class="hot-game-list">

                    </div>
                    <div class="pagination">
                        <!--<span class="disabled" title="首页">上一页</span>
                        <span class="current">1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                        <span>...</span>
                        <span>110</span>
                        <span>下一页</span>-->
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {

        clearInterval(gameJackPort);
        // var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;
        var test_username = '<?php echo $test_username;?>';
        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        var sumarr = [61813152.31,41313552.16,72315192.25,41135157.71,42513151.75,52113152.51,47115112.75,41735117.41,63131117.90,42137110.81];
        var sum = sumarr[parseInt(Math.random()*10)];
//        gameJackPort = setInterval(function(){
//            show_num1(sum)
//        },1500);
        // jackport 数字
        function show_num1(n) {
            //console.log(n);
            sum = Number(sum)+1.31;
            sum = Math.round(sum*100)/100 ;

            var it = $(".t_num1 i");
            var len = String(n).length;
            for(var i = 0; i < len; i++) {
                if(it.length <= i) {
                    $(".t_num1").append("<i class='no'></i>");
                }
                var num = String(n).charAt(i);
                //根据数字图片的高度设置相应的值
                var y = -parseInt(num) * 52;
                var obj = $(".t_num1 i").eq(i);
                obj.animate({
                    backgroundPosition: '(0 ' + String(y) + 'px)'
                }, 'slow', 'swing', function() {});
            }

        }
        var gameSwiper = ''; // 轮播
        var count = 24; // 每页展示数量
        var page_tt = 0; // 初始页码
        var game_type_c ='ag' ; // 默认游戏类型
        var game_list = {};

        if(fr_gametype != ''){
            game_type_c = fr_gametype ;
        }


        


        // 游戏列表渲染
        function int_page(cp,gamelist) {
            if(game_type_c=='ag'){ //ag
                game_list = <?php echo json_encode($agGameList, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='mg'){ // mg
                game_list = <?php echo json_encode($mgGamesInfo, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='cq'){ // cq
                //game_list = cq_list ;
                game_list = <?php echo json_encode($cqGameList, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='mw'){ // mw
                game_list = <?php echo json_encode($mwGameList,JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='fg'){ // fg
                game_list = <?php echo json_encode($fgGameList,JSON_UNESCAPED_UNICODE);?> ;
            }
            if(!gamelist){
                gamelist = game_list;
            }

            var gstr ='' ;
                page_tt = Math.ceil(gamelist.length / count); // 总页数

                for (var j = (cp - 1) * count; j < (cp * count > gamelist.length ? gamelist.length : cp * count); j++) {
                    var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+gamelist[j].gameid; // 真钱
                    var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+gamelist[j].gameid; // 试玩

                    gstr += '  <div class="gameBox">' +
                        '                        <div class="imgBox">' ;
                    if(game_type_c=='ag'  || game_type_c=='cq') { // ag cq
		            if(game_type_c=='cq'){
                                realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                                tryurl = 'https://demo.cqgame.games/';
                            }
			    
                        gstr += '<img alt="" src="'+  gamelist[j].gameurl +'">' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].name  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            '                            <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试用</a>' +
                            '                            <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                           '</div>';
                    }else if(game_type_c=='mg'){ // mg
                        realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        gstr += '<span class="mg_img" style="background: url(images/game/mg/more/'+gamelist[j].gameurl +') center no-repeat;background-size: 86%;display: block;height: 130px;margin: 0 auto;" ></span>' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].name  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }else if(game_type_c=='mw'){ // mw
                        realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                        gstr += '<img src="images/game/mw/'+gamelist[j].gameIcon +'" >' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].gameName  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }else if(game_type_c=='fg'){ // fg
                        realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                        tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                        gstr += '<img src="images/game/fg/'+gamelist[j].gameIcon +'" >' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].gameName  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试用</a>'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }

                    gstr += '  </div>' ;

                }

            $('.hot-game-list').html(gstr) ;
            hoverGmae();


        }
        // 推荐游戏
        function recommendGame() {

            var gstr ='' ;
            for (var i = 5 ; i < 20; i++) {
                var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+game_list[i].gameid;
                var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+game_list[i].gameid;
                gstr +='<div class="swiper-slide ">' +
                    '                                <div class="game-slide">' ;
                if(game_type_c=='ag' || game_type_c=='cq'){ // ag  cq9
                    if(game_type_c=='cq'){
                       realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                       tryurl = 'https://demo.cqgame.games/';
                    }
                    gstr += '<img class="slide-img" src="'+ game_list[i].gameurl +'" alt="">' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].name +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="freeplay" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>' +
                        '                            <a class="comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>  '+
                        '</div>';
                }
                else if(game_type_c=='mg'){ // mg
                    realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                    tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+game_list[i].gameid ;

                    gstr += '<span class="slide-img" style="background-image: url(images/game/mg/'+game_list[i].gameurl +'.png);" ></span>' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].name +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>  '+
                        '</div>';
                }
                else if(game_type_c=='mw'){ // mw
                    // console.log(game_list)
                    realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+game_list[i].gameId ;

                    gstr += '<img class="slide-img" src="images/game/mw/'+game_list[i].gameIcon +'" alt="">' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].gameName +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>'+
                        '</div>';
                }

            }

            $('.recommend_Game').html(gstr) ;
            hoverGmae();
           // console.log(gameSwiper)
            if(!gameSwiper){
                gameSwiper = new Swiper('.swiper-container',{
                    autoplay : 2500, // 自动滚动
                    slidesPerView : 8,
                    spaceBetween : 10, // 图片间隔
                    speed:500,
                    loop : true ,
                    prevButton:'.swiper-button-prev',
                    nextButton:'.swiper-button-next',
                    autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)
                    //spaceBetween : '10%',按container的百分比
                })
            }else{
                gameSwiper.slideTo(0);
                gameSwiper.update();
                gameSwiper.reLoop();
            }


        }

        // 鼠标hover 游戏
        function hoverGmae() {
            $('.gameBox').each(function () {
                $(this).hover(function(){
                    $(this).find('.mask').removeClass('hide')
                }, function(){
                    $(this).find('.mask').addClass('hide')
                })
            })
        }

    // 页码设置
    function setPageCount() {
        if (page_tt > 0) {
            var pstr = '';
               /*'<a href="javascript:void(0)"> << </a>' +*/
               // ' <a href="javascript:void(0)" topage="next"> < </a>' ;

            for (var j = 1; j <= page_tt; j++) {
                if (1 == j) {
                    pstr +='<a href="javascript:void(0)" class="swShowPage active" topage="1"> 1 </a>' ;
                } else {
                    pstr +='<a href="javascript:void(0)" class="swShowPage" topage="'+j+'">'+ j +'</a>' ;
                }
            }
           // pstr += ' <a href="javascript:void(0)" topage="pre"> > </a>';
                /*' <a href="javascript:void(0)"> << </a>'*/

            $('.pagination').html(pstr) ;

            $('.pagination').on('click','a',function () { // 绑定切换页码事件
                $(this).addClass('active').siblings().removeClass('active') ;
                int_page($(this).attr('toPage')) ;
            }) ;
        }
    }

    // 搜索游戏
        function seachGameName(){
            $('.submit-btn').on('click',function () {
                var txt = $('.seachgame_input').val();
                var seach_game_list = new Array();
                $.each(game_list,function (i,v) {
                    if (game_type_c=='mw' || game_type_c=='fg'){
                        if(v.gameName.indexOf(txt)>-1){ // 匹配搜索
                            // console.log(v.gameName)
                            seach_game_list.push(
                                {
                                    gameName: v.gameName ,
                                    gameIcon: v.gameIcon,
                                    gameId: v.gameId,
                                }
                            )
                        }
                    } else{
                        if(v.name.indexOf(txt)>-1){ // 匹配搜索
                            // console.log(v.name)
                            seach_game_list.push(
                                {
                                    name: v.name ,
                                    gameurl: v.gameurl,
                                    gameid: v.gameid,
                                }
                            )
                        }
                    }

                })
                int_page(1,seach_game_list);
                setPageCount();

            })
        }

        // ag mg 游戏切换
        function changeGameNav(){
            $('.game_choose ul').find('li').on('click',function () {
                $('.game_yxfl a').removeClass('active').eq(0).addClass('active');
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                $(this).addClass('active').siblings('li').removeClass('active');
                int_page(1);
                setPageCount();
                // recommendGame();

            })
        }

        // 游戏筛选分类
        function changeGameType(){
            $('.game_yxfl').find('a').on('click',function () {
                var gametype = $(this).attr('data-type');
                $(this).addClass('active').siblings().removeClass('active');
                var choose_game_list = new Array();
                if(gametype == 'all'){
                    choose_game_list =  game_list;
                }else{
                    $.each(game_list,function (i,v) {
                        // console.log(i%2)
                        if(gametype == 'rm'){ // 热门游戏
                            if(i%2==0 && i<30){
                                // console.log(v.name)
                                if (game_type_c=='mw' || game_type_c=='fg'){
                                    choose_game_list.push(
                                        {
                                            gameName: v.gameName ,
                                            gameIcon: v.gameIcon,
                                            gameId: v.gameId,
                                        }
                                    )
                                }else{
                                    choose_game_list.push(
                                        {
                                            name: v.name ,
                                            gameurl: v.gameurl,
                                            gameid: v.gameid,
                                        }
                                    )
                                }
                            }
                        }else if(gametype == 'new'){ // 最新游戏
                            if(i%2==1 && i>20){
                                // console.log(v.name)
                                if (game_type_c=='mw' || game_type_c=='fg'){
                                    choose_game_list.push(
                                        {
                                            gameName: v.gameName ,
                                            gameIcon: v.gameIcon,
                                            gameId: v.gameId,
                                        }
                                    )
                                }else{
                                    choose_game_list.push(
                                        {
                                            name: v.name ,
                                            gameurl: v.gameurl,
                                            gameid: v.gameid,
                                        }
                                    )
                                }
                            }
                        }

                    })
                }

               // console.log(choose_game_list)
                int_page(1,choose_game_list);
                setPageCount();

            })
        }

        int_page(1);
       // recommendGame();
        setPageCount() ;
        seachGameName();
        //enterSubmitAction();
        changeGameNav();
        changeGameType();

    })
</script>