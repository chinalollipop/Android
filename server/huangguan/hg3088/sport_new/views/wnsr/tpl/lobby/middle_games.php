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
$yesday = date('Y-m-d',strtotime('-1 day'));

$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg

?>

<style>
    .mainBody {background: #4d4d4d;}
    .game_bg{padding-top:20px;background:#121011 url(images/game/wnsr_game_bg.png) top center no-repeat;}
    .dz_banner{width:100%;height:230px;background:url(images/wnsr_dz_banner.jpg) no-repeat top center;position:relative;padding-bottom:30px}
    .dz_banner .promlink{background:#101010}

    .slots .ullist{overflow:hidden;margin-bottom:20px}
    .slots .ullist li{background:#000;width:165px;height:50px;text-align:center;text-align:center;float:left}
    .slots .ullist li a{display:block;height:50px;}
    .slots .ullist li.active a,.slots .ullist li:hover a{background:#4d4d4d}
    .slots .ullist li .conDiv{display:inline-block;overflow:hidden}
    .slots .ullist li .imgDiv{float:left;line-height:48px}
    .slots .ullist li .imgDiv img{vertical-align:middle;margin-top: 9px;}
    .slots .ullist li .textDiv{float:left;text-align:left;padding-top:12px;color:#e6d884;font-size:14px;margin-left:5px;line-height:1}
    .slots .ullist li .textDiv span{display:block;font-size:12px;margin-top:2px}
    .slots .ullist li:last-child .textDiv{line-height:50px;padding-top:0;font-size:16px}
    .slots .bdDiv{background:#434343;min-height:685px}

    .slots .mainBody .searchBar{width:100%;height:70px;background:#29292e;border-radius:70px;}
    .slots .mainBody .searchBar h3{width:144px;height:70px;line-height:70px;font-size:18px;color:#fff;text-align:center;font-weight:700}
    .slots .mainBody .searchBar .inputBox{width:314px;height:40px;margin:15px 0;background:#202025;border-radius:70px}
    .slots .mainBody .searchBar .inputBox .searchInput{width:245px;height:26px;line-height:26px;background:none;border:none;border-right:1px solid #2e2e32;margin:6px 0 0 15px}
    .slots .mainBody .searchBar .advance .typeOfGame .cbox-row .cbox-label,.slots .mainBody .searchBar .advance .typeOfGame .cbox-row a,.slots .mainBody .searchBar .advance .typeOfGame .cbox-row span,.slots .mainBody .searchBar .btnBox,.slots .mainBody .searchBar .btnBox .btn1,.slots .mainBody .searchBar .inputBox,.slots .mainBody .searchBar .inputBox .ico,.slots .mainBody .searchBar .inputBox .searchInput,.slots .mainBody .searchBar .keywordsBox,.slots .mainBody .searchBar .keywordsBox h4,.slots .mainBody .searchBar .keywordsBox ul,.slots .mainBody .searchBar .keywordsBox ul li,.slots .mainBody .searchBar h3,.slots .mainBody .slotsGame .award span,.slots .mainBody .slotsGame .gameBox,.slots .mainBody .slotsGame .king .ico,.slots .mainBody .slotsGame .star .ico,.slots .mainBody .topList .part.textPart .likeList ul li .likeIco,.slots .mainBody .topList .part.textPart .likeList ul li p,.slots .mainBody .topList .part.textPart .likeList ul li span,.fl{float:left;*display:inline}
    .slots .mainBody .searchBar .inputBox .ico{width:28px;height:29px;background:url(images/game/ss.png);margin:5px 0 0 10px;cursor:pointer;transition:all .5s ease}
    .slots .mainBody .hot-game-list{overflow: hidden;}
    .slots .mainBody .slotsGame{width:100%;min-height: 360px;}
    .slots .mainBody .slotsGame .gameBox{cursor:pointer;position:relative;width:186px;height:210px;background:url(images/game/game_list_bg.jpg) no-repeat;margin:0 5px 20px;}
    .slots .mainBody .slotsGame .gameBox:nth-child(5n+1) {margin-left: 15px;}
    .slots .mainBody .slotsGame .gameBox .gameName{width:100%;height:46px}
    .slots .mainBody .searchBar,.slots .mainBody .slotsGame .gameBox .gameName,.slots .mainBody .slotsGame .gameBox .imgBox,.slots .mainBody .slotsTitle,.slots .mainBody .topList,.slots .mainBody .topList .part.textPart .likeList,.slots .mainBody .topList .part h2,.slots .typeOfGame,.pr{position:relative}
    .slots .mainBody .slotsGame .gameName h3{width:100%;height:46px;line-height:46px;text-align:center;font-size:16px;font-weight:400;color:#fff;z-index:1}
    .slots .mainBody .slotsGame .gameBox .imgBox{width:100%;overflow:hidden}
    .slots .mainBody .slotsGame .gameBox .imgBox span{display: block;width: 150px;height: 135px;margin: 12px auto 0;}
    .slots .mainBody .slotsGame .hr{width:210px;height:1px;margin:0}
    .slots .mainBody .slotsGame .btn1{width:164px;height:32px;line-height:32px;color:#fff;margin:20px auto 0;text-shadow:none;border-radius:32px;text-align:center;background:url("images/game/dzmf.png");transition:all 0.5s}
    .slots .mainBody .slotsGame .btn1:hover{background:url("images/game/dzmf2.png")}
    .slots .mainBody .slotsGame .btn1.btn2{background:url("images/game/zdkj.png");transition:all 0.5s}
    .slots .mainBody .slotsGame .btn2:hover{background:url("images/game/zdkj1.png")}
    .slots .mainBody .slotsGame .gameBox .game_btn{position:absolute;width:100%;height:118px;left:0px;bottom:18px;z-index:1;transition:all 0.3s}
    .slots .mainBody .slotsGame .gameBox .game_btn a{display:block;width:100px;height:27px;line-height:27px;font-size:14px;color:#fff;text-align:center;background:#eeaf45;border-radius:3px;margin:10px auto 0}
    .slots .mainBody .slotsGame .gameBox .game_btn .freeplay{margin-top:20px;background:#4e4d4d}

    /* 游戏分类，搜索*/
    .games_search_menu{border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;height: 60px;padding:3px;position: relative;width: 950px;z-index: 200;margin:0px auto;margin-bottom:10px;}
    .games_search_menu .menulist{display:inline-block;}
    .games_search_menu .menulist a{ padding:1px 4px; color: #ffe49b;}
    .games_search_menu .menulist a.active,.games_search_menu .menulist a:hover{color:#FE9935;}
    .sf-menu,.sf-menu *{margin:0;padding:0;list-style:none}
    .sf-menu{float:left;margin-top:1em;margin-left:10px;line-height:28px}
    .sf-menu ul{box-shadow:2px 2px 6px rgba(0,0,0,.2)}
    .sf-menu li{float:left;position:relative}
    .sf-menu a{padding:.75em 1em;text-decoration:none}
    .sf-menu a,.sf-menu a:visited{color:#927f55}
    .sf-menu li li{background:#2c2c2c}
    .sf-menu li li li{background:#9AAEDB}
    .sf-menu li:hover,.sf-menu li.sfHover,.sf-menu a:focus,.sf-menu a:hover,.sf-menu a:active{outline:0;color:#ffe49b}
    .sf-select .cur-select{position:absolute;display:block;width:140px;height:25px;line-height:25px;text-indent:10px;border:1px solid #ffe49b;color:#ffe49b}
    .sf-select:hover .cur-select{background-color:#2c2c2c}
    .sf-select select{position:absolute;top:0;right:0;width:150px;height:29px;opacity:0;color:#f80}
    .sf-select select option{text-indent:10px}
    .sf-select select option:hover{background-color:#f80;color:#fff}
    .menulist ul li .sty-mar{margin-left:5px;line-height:30px;padding-left:4px}
    .search_GName{height:25px;line-height:25px;text-indent:10px;border:1px solid #646464;background-color:#1A0C04;color:#ffe49b}
    .search_Btn{cursor:pointer;border:none;background:url(images/game/game_btn.png) center center no-repeat;width:70px;height:30px}

</style>
<div class="promlink">
    <div class="game_banner">

    </div>
</div>

<div class="game_bg">

    <div class="w_1000 slots">
        <div class="game_choose">
            <ul class="ullist">
                <li class="<?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag">
                    <a href="javascript:;">
                        <div class="conDiv">
                            <div class="imgDiv">
                                <img src="images/game/dz-icon3.png" alt="">
                            </div>
                            <div class="textDiv">AG电子<span>ELECTRONC</span></div>
                        </div>
                    </a>
                </li>
                <li class="<?php echo $gametype=='mg'?'active':''; ?>"  data-gametype="mg">
                    <a href="javascript:;">
                        <div class="conDiv">
                            <div class="imgDiv">
                                <img src="images/game/dz-icon1.png" alt="">
                            </div>
                            <div class="textDiv">MG电子<span>ELECTRONC</span></div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo ($gametype=='cq' )?'active':''; ?>" data-gametype="cq">
                    <a href="javascript:;">
                        <div class="conDiv">
                            <div class="imgDiv">
                                <img src="images/game/dz-icon2.png" alt="">
                            </div>
                            <div class="textDiv">CQ9电子<span>ELECTRONC</span></div>
                        </div>
                    </a>
                </li>
                <li class="<?php echo $gametype=='mw'?'active':''; ?>"  data-gametype="mw">
                    <a href="javascript:;">
                        <div class="conDiv">
                            <div class="imgDiv">
                                <img src="images/game/dz-icon4.png" alt="">
                            </div>
                            <div class="textDiv">MW电子<span>ELECTRONC</span></div>
                        </div>
                    </a>
                </li>
                <li class="<?php echo $gametype=='fg'?'active':''; ?>"  data-gametype="fg">
                    <a href="javascript:;">
                        <div class="conDiv">
                            <div class="imgDiv">
                                <img src="images/game/dz-icon5.png" alt="">
                            </div>
                            <div class="textDiv">FG电子<span>ELECTRONC</span></div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="mainBody">
            <div class="games_search_menu">
                <div class="menulist clearfix">
                    <!--      <ul class="sf-menu sf-js-enabled">
                              <li class="current sf-menu-txt"> <a >游戏分类：</a></li>
                              <li class="current"></li><li><a href="javascript:void(0)" >全部</a></li>
                              <li><a href="javascript:void(0)" >经典老虎机</a></li>
                              <li><a href="javascript:void(0)" >视频扑克</a></li>
                              <li>
                                  <span class="col-ye floatL sty-mar">&nbsp;&nbsp;搜索游戏:</span>
                                  <input name="search_GName" type="text" class="search_GName seachgame_input sty-mar" >
                                  <input name="search_Btn" type="button" value="搜索" class="search_Btn submit-btn">
                              </li>
                          </ul>-->
                </div>
            </div>
            <!-- <div class="searchBar">
                 <h3>游戏搜索</h3>
                 <div class="inputBox">
                     <label>
                         <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="搜索游戏">
                     </label>
                     <div class="submit-btn ico"></div>
                 </div>
             </div>-->


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
<script type="text/javascript">
    $(function () {

        //clearInterval(gameJackPort);
        var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;
        var test_username = '<?php echo $test_username;?>';
        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        // var sumarr = [61813152.31,41313552.16,72315192.25,41135157.71,42513151.75,52113152.51,47115112.75,41735117.41,63131117.90,42137110.81];
        // var sum = sumarr[parseInt(Math.random()*10)];
        // gameJackPort = setInterval(function(){
        //     show_num1(sum)
        // },1500);
        // // jackport 数字
        // function show_num1(n) {
        //     //console.log(n);
        //     sum = Number(sum)+1.31;
        //     sum = Math.round(sum*100)/100 ;
        //
        //     var it = $(".t_num1 i");
        //     var len = String(n).length;
        //     for(var i = 0; i < len; i++) {
        //         if(it.length <= i) {
        //             $(".t_num1").append("<i class='no'></i>");
        //         }
        //         var num = String(n).charAt(i);
        //         //根据数字图片的高度设置相应的值
        //         var y = -parseInt(num) * 47;
        //         var obj = $(".t_num1 i").eq(i);
        //         obj.animate({
        //             backgroundPosition: '(0 ' + String(y) + 'px)'
        //         }, 'slow', 'swing', function() {});
        //     }
        //
        // }

        var count = 15; // 每页展示数量
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
                if(game_type_c=='ag' || game_type_c=='cq') { // ag
                    if(game_type_c=='cq'){
                        realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                        tryurl = 'https://demo.cqgame.games/';
                    }
                    gstr += '<div class="gameName"> <h3>'+ gamelist[j].name  +'</h3></div> ' +
                        '<span class="slide-img" style="background:url('+  gamelist[j].gameurl +') center no-repeat;background-size: 98%;"></span>' +
                        '</div>' +
                        '                        <div class="game_btn hide">' +
                        '                            <a href="javascript:;" class="freeplay btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试玩</a>' +
                        '                            <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>' ;
                }else if(game_type_c=='mg'){ // mg
                    realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                    tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;
                    gstr += '<div class="gameName"> <h3>'+ gamelist[j].name  +'</h3></div>' +
                        '<span class="slide-img mg_img" style="background: url(images/game/mg/more/'+gamelist[j].gameurl +') center no-repeat;background-size: 86%;" ></span>' +
                        '</div>' +
                        '                        <div class="game_btn hide">' +
                        '                            <a style="margin-top: 38px;" href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>' ;
                }else if(game_type_c=='mw'){ // mw
                    realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                    gstr += '<div class="gameName"> <h3>'+ gamelist[j].gameName  +'</h3></div>' +
                        '<span class="slide-img mg_img" style="background: url(images/game/mw/'+gamelist[j].gameIcon +') center no-repeat;background-size: 100%;" ></span>' +
                        '</div>' +
                        '                        <div class="game_btn hide">' +
                        '                            <a style="margin-top: 38px;" href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>' ;
                }else if(game_type_c=='fg'){ // fg
                    realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                    tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                    gstr += '<div class="gameName"> <h3>'+ gamelist[j].gameName  +'</h3></div>' +
                        '<span class="slide-img mg_img" style="background: url(images/game/fg/'+gamelist[j].gameIcon +') center no-repeat;background-size: 100%;" ></span>' +
                        '</div>' +
                        '                        <div class="game_btn hide">' +
                        '                            <a href="javascript:;" class="freeplay btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>'+
                        '                            <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>' ;
                }

                gstr += '                        </div>' +
                    '                    </div>';


            }

            $('.hot-game-list').html(gstr) ;
            hoverGmae();


        }

        // 鼠标hover 游戏
        function hoverGmae() {
            $('.gameBox').each(function () {
                $(this).hover(function(){
                    $(this).find('.slide-img').addClass('blur');
                    $(this).find('.game_btn').removeClass('hide')
                }, function(){

                    $(this).find('.slide-img').removeClass('blur');
                    $(this).find('.game_btn').addClass('hide')
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
                    // console.log(v.name)
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
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                setGameList(gametype) ;
                $(this).addClass('active').siblings('li').removeClass('active');
                int_page(1);
                setPageCount();

            })
        }

        // 游戏分类
        function setGameList(par){
            var $menulist = $('.menulist');
            var list ='' ;
            if(par == 'mg'){ // mg
                list ='<ul class="sf-menu sf-js-enabled">' +
                    '<li class="current sf-menu-txt"> <a >游戏分类：</a></li>' +
                    '<li class="current"></li>' +
                    '<li><a href="javascript:void(0)" class="active sel" data-to="all">全部</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="video">视频老虎机</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="classic">经典老虎机</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="bonus">奖金老虎机</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="feature">特色老虎机</a></li>' +
                    '<li>' +
                    '<span class="col-ye floatL sty-mar">&nbsp;&nbsp;搜索游戏:</span>' +
                    '<input name="search_GName" type="text" class="search_GName seachgame_input sty-mar" >' +
                    '<input name="search_Btn" type="button" value="搜索" class="search_Btn submit-btn">' +
                    '</li>' +
                    '</ul>';
            }else if(par == 'ag'){ // ag
                list =' <ul class="sf-menu sf-js-enabled">' +
                    '<li class="current sf-menu-txt"> <a >游戏分类：</a></li>' +
                    '<li class="current"></li>' +
                    '<li><a href="javascript:void(0)" class="active sel" data-to="all">全部</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="slot">经典老虎机</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="video">视频扑克</a></li>' +
                    '<li><a href="javascript:void(0)" class="sel" data-to="table">桌上游戏</a></li>' +
                    '<li>' +
                    '<span class="col-ye floatL sty-mar">&nbsp;&nbsp;搜索游戏:</span>' +
                    '<input name="search_GName" type="text" class="search_GName seachgame_input sty-mar" >' +
                    '<input name="search_Btn" type="button" value="搜索" class="search_Btn submit-btn">' +
                    '</li>' +
                    '</ul>' ;
            }else{
                list =' <ul class="sf-menu sf-js-enabled">' +
                    '<li>' +
                    '<span class="col-ye floatL sty-mar">&nbsp;&nbsp;搜索游戏:</span>' +
                    '<input name="search_GName" type="text" class="search_GName seachgame_input sty-mar" >' +
                    '<input name="search_Btn" type="button" value="搜索" class="search_Btn submit-btn">' +
                    '</li>' +
                    '</ul>' ;
            }
            $menulist.html(list) ;
            seachGameName();
            // 筛选游戏
            $menulist.on('click','.sel',function () {
                var g_type = $(this).attr('data-to');
                var chooseGameList = new Array();
                $(this).addClass('active').parent('li').siblings().find('a').removeClass('active');
                if(g_type=='all'){ // 全部
                    chooseGameList = game_list;
                }else{
                    $.each(game_list,function (i,v) {
                        if(v.type == g_type){ // 匹配搜索
                            // console.log(v.name)
                            chooseGameList.push(
                                {
                                    name: v.name ,
                                    gameurl: v.gameurl,
                                    gameid: v.gameid,
                                    type: v.type,
                                }
                            )
                        }
                    })

                }
                // console.log(chooseGameList)
                int_page(1,chooseGameList);
                setPageCount();
            })
        }



        int_page(1);

        setPageCount() ;

        enterSubmitAction();
        changeGameNav();
        setGameList('ag')
    })
</script>