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
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg mw cq

?>

<style>
    .jackport{width: 360px;margin-top: 5px;}
    .jackport .t_num i{width: 27px;height: 40px;display:inline-block;background:url(<?php echo TPL_NAME;?>images/number.png) no-repeat;background-position:0 0;}
    .jackport .t_num i:nth-child(12){display:none}
    .jackport .t_num span{color:#fff4a1;font-size:20px;font-weight:800;}
    .jackpot{background:#27272d;height:150px;width:100%}
    .jackpot_left{width:30%;text-align:center;padding-top:30px}
    .jackpot_left div:first-child{font-size:18px;color:#fff}
    .jackpot_left div:nth-child(2){font-size:30px;color:#efd08b;font-weight:600}
    .jackpot_center{width:5%}
    .jackpot_center span{display:inline-block;width:1px;vertical-align:33px;color:#fff}
    .winning_name{margin:8px 0;display: flex;width: 366px;}
    .winning_name span{margin-right:20px;flex: 1;}
    .winning_name span:last-child{color:#efd08b;margin-right: 0;}

    .slots {background:rgb(61, 61, 61); }
    .slots .mainBody .hot-game-list{overflow:hidden;/*display:flex;*/align-items:center;justify-content:space-around;flex-wrap:wrap;margin:0 auto;padding-bottom: 10px;}
    .slots .mainBody .game_li{width: 19%;height: 200px;margin: .5% .55%;text-align:center;position:relative;float: left;}
    .slots .mainBody .game_li:nth-child(5n+1) {margin-left: 0;}
    figure{background:#123851;color:#ffffff;text-align:center;width:100%;height:100%;margin:10px 1%;float:left;position:relative;overflow:hidden;-webkit-box-shadow:0 0 7px 1px grey;-moz-box-shadow:0 0 7px 1px gray;box-shadow:0 0 7px 1px grey}
    figure:before{content:'';background:#ffffff;width:100%;height:100%;position:absolute;top:0;left:0;-webkit-transition:all 0.3s ease-in-out;transition:all 0.3s ease-in-out;-webkit-transform:skew(-10deg) rotate(-10deg) translateY(-50%);transform:skew(-10deg) rotate(-10deg) translateY(-50%);border:1px solid blue}
    .slots .mainBody .game_li img{position:absolute;width: 160px;height: 144px;margin-left: -80px;left:50%;margin-top: -72px;top:50%;}
    figure *{-webkit-box-sizing:border-box;box-sizing:border-box;-webkit-transition:all 0.4s ease-in-out;transition:all 0.4s ease-in-out}
    figure img,.slots .game_li span{opacity:0.4;max-width:100%;position:relative}
    figure:hover img,figure.hover img,.slots .game_li figure:hover span{opacity:1;-webkit-transform:scale(1.1);transform:scale(1.1)}
    figure:hover h2,figure.hover h2{top:150%}
    figure:hover~.dy_button{display:block}
    figure:hover:before,figure.hover:before{-webkit-transform:skew(-10deg) rotate(-10deg) translateY(-150%);transform:skew(-10deg) rotate(-10deg) translateY(-150%)}
    figure figcaption{position:absolute;top:0;left:0;bottom:0;right:0}
    figure h2{font-size:1.7em;font-weight:400;text-transform:uppercase;background:#000000;display:inline-block;padding-top:3px;margin:0;position:absolute;left:40px;right:40px;top:50%;-webkit-transform:skew(-10deg) rotate(-10deg) translate(0,-50%);transform:skew(-10deg) rotate(-10deg) translate(0,-50%)}
    figure a{position:absolute;left:0;right:0;/*top:0;bottom:0;*/z-index:2;height: 50%;}
    figure a.user_play {bottom: 0;}
    figure a.mg_user_play {height: 100%;}
    .dy_button{display:none;position:absolute;left:0;right:0;top:0;bottom:0;z-index:1;margin-top: 13%;}
    .dy_button a{display:block;width:60%;height:39px;line-height:39px;font-size:20px;color:#fff;text-align:center;background:#eeaf45;border-radius:3px;margin:0 auto;margin-top:30px}
    .dy_button a.mg_cla{margin-top: 60px;}
    .slots .mainBody .warp .searchBar{width:100%;height:45px;background:url(images/game/navbg.jpg) no-repeat;}
    .slots .mainBody .warp .searchBar h3{width:144px;height:35px;line-height:35px;font-size:16px;color:#fff;text-align:center;font-weight:700}
    .slots .mainBody .warp .searchBar .inputBox{width:314px;height:35px;background:#202025;border-radius:70px}
    .slots .mainBody .warp .searchBar .inputBox .searchInput{width:245px;height:26px;line-height:26px;background:none;border:none;border-right:1px solid #2e2e32;margin:6px 0 0 15px}
    .slots .mainBody .warp .searchBar .advance .typeOfGame .cbox-row .cbox-label,.slots .mainBody .warp .searchBar .advance .typeOfGame .cbox-row a,.slots .mainBody .warp .searchBar .advance .typeOfGame .cbox-row span,.slots .mainBody .warp .searchBar .btnBox,.slots .mainBody .warp .searchBar .btnBox .btn1,.slots .mainBody .warp .searchBar .inputBox,.slots .mainBody .warp .searchBar .inputBox .ico,.slots .mainBody .warp .searchBar .inputBox .searchInput,.slots .mainBody .warp .searchBar .keywordsBox,.slots .mainBody .warp .searchBar .keywordsBox h4,.slots .mainBody .warp .searchBar .keywordsBox ul,.slots .mainBody .warp .searchBar .keywordsBox ul li,.slots .mainBody .warp .searchBar h3,.slots .mainBody .warp .slotsGame .award span,.slots .mainBody .warp .slotsGame .gameBox,.slots .mainBody .warp .slotsGame .king .ico,.slots .mainBody .warp .slotsGame .star .ico,.slots .mainBody .warp .topList .part.textPart .likeList ul li .likeIco,.slots .mainBody .warp .topList .part.textPart .likeList ul li p,.slots .mainBody .warp .topList .part.textPart .likeList ul li span,.fl{float:left;*display:inline}
    .slots .mainBody .warp .searchBar .inputBox .ico{width:28px;height:29px;background:url(images/game/ss1.png);margin:5px 0 0 10px;cursor:pointer;transition:all .5s ease}

    .slots .mainBody .warp .slotsGame{width:100%;min-height: 360px;padding: 0 20px;}
    .slots .mainBody .warp .slotsGame .hr{width:210px;height:1px;margin:0}
    .slots .mainBody .warp .slotsGame .btn1{width:164px;height:32px;line-height:32px;color:#fff;margin:20px auto 0;text-shadow:none;border-radius:32px;text-align:center;background:url("images/game/dzmf.png");transition:all 0.5s}
    .slots .mainBody .warp .slotsGame .btn1:hover{background:url("images/game/dzmf2.png")}
    .slots .mainBody .warp .slotsGame .btn1.btn2{background:url("images/game/zdkj.png");transition:all 0.5s}
    .slots .mainBody .warp .slotsGame .btn2:hover{background:url("images/game/zdkj1.png")}
    .slots .game_li span {display: inline-block;width: 150px;height: 140px;margin-top: 25px;}
</style>
<div class="w_1200 slots">

    <div class="mainBody pr">
        <div class="warp">
            <!--<div class="jackpot">
                <div class="jackpot_left fl">
                    <div>千万奖池，一拉即中</div>
                    <div class="jackport">
                        <span class="t_num t_num1">
                            <i style="background-position: 0px -235px;"></i>
                            <i style="background-position: 0px 0px;"></i>
                            <span>,</span>
                            <i style="background-position: 0px -422px;"></i>
                            <i style="background-position: 0px -47px;"></i>
                            <i style="background-position: 0px -188px;"></i>
                            <span>,</span>
                            <i style="background-position: 0px -47px;"></i>
                            <i style="background-position: 0px -281px;"></i>
                            <i style="background-position: 0px -93px;"></i>
                           <span>.</span>
                            <i style="background-position: 0px -174px;"></i>
                            <i style="background-position: 0px -234px;"></i>
                            <i style="background-position: 0px -47px;"></i>

                        </span>
                    </div>
                    <div>SUPER AWARD</div>
                </div>

                <div style="clear: both"></div>
            </div>-->
            <div class="searchBar">
                <div class="game_choose fl">
                    <ul>
                        <!--<li class="game_active">热门游戏</li>
                        <li>漫威热门系列</li>-->
                        <li class="<?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag">AG电子</li>
                        <li class="<?php echo $gametype=='mg'?'active':''; ?>" data-gametype="mg">MG电子</li>
                        <li class="<?php echo $gametype=='mw'?'active':''; ?>" data-gametype="mw">MW电子</li>
                        <li class="<?php echo $gametype=='cq'?'active':''; ?>" data-gametype="cq">CQ9电子</li>
                        <li class="<?php echo $gametype=='fg'?'active':''; ?>" data-gametype="fg">FG电子</li>
                        <!--<li>电影老虎机</li>
                        <li>纸牌游戏</li>-->
                    </ul>
                </div>
                <h3>游戏搜索</h3>
                <div class="inputBox">
                    <label>
                        <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="搜索游戏">
                    </label>
                    <div class="submit-btn ico"></div>
                </div>

            </div>
            <!--                游戏-->
            <div class="slotsGame" id="gameSearch11">
                <div class="game_banner">
                    <div class="swiper-container" >
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" >
                                <a href="javascript:;" >
                                    <img src="images/game/game_ag.jpg?v=3" class="swiper-lazy" alt="">
                                </a>
                            </div>
                            <div class="swiper-slide" >
                                <a href="javascript:;" >
                                    <img src="images/game/game_mg.jpg?v=3" class="swiper-lazy" alt="">
                                </a>
                            </div>

                        </div>
                        <!-- 分页 -->
                        <!--<div class="swiper-pagination"></div>-->
                    </div>
                </div>

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

        clearInterval(gameJackPort);
        // var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;


        var test_username = '<?php echo $test_username;?>';
        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        var sumarr = [61813152.31,41313552.16,72315192.25,41135157.71,42513151.75,52113152.51,47115112.75,41735117.41,63131117.90,42137110.81];
        var sum = sumarr[parseInt(Math.random()*10)];
        // gameJackPort = setInterval(function(){
        //     show_num1(sum)
        // },1500);
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
                var y = -parseInt(num) * 47;
                var obj = $(".t_num1 i").eq(i);
                obj.animate({
                    backgroundPosition: '(0 ' + String(y) + 'px)'
                }, 'slow', 'swing', function() {});
            }

        }
       
        var count = 10; // 每页展示数量
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
            var cla = '';
            var gstr ='' ;
                page_tt = Math.ceil(gamelist.length / count); // 总页数

                for (var j = (cp - 1) * count; j < (cp * count > gamelist.length ? gamelist.length : cp * count); j++) {
                    var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+gamelist[j].gameid; // 真钱
                    var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+gamelist[j].gameid; // 试玩

                
                    gstr += '<div class="game_li">' +
                        '       <figure>' ;
                    if(game_type_c=='ag' || game_type_c=='cq') { // ag
		            if(game_type_c=='cq'){
                                realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                                tryurl = 'https://demo.cqgame.games/';
                            }

                        gstr += '           <img src="'+ gamelist[j].gameurl +'" alt="Image">' ;
                    }else if(game_type_c=='mg'){ // mg
                        cla = 'mg_cla';
                        realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        gstr += '           <span style="background: url(images/game/mg/more/'+gamelist[j].gameurl +') center no-repeat;background-size: 86%;" ></span>' ;

                    }else if(game_type_c=='mw'){ // mw
                        cla = 'mg_cla';
                        realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                        gstr += '           <span style="background: url(images/game/mw/'+gamelist[j].gameIcon +') center no-repeat;background-size: 100%;" ></span>' ;

                    }else if(game_type_c=='fg'){ // fg
                        realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                        tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                        gstr += '           <span style="background: url(images/game/fg/'+gamelist[j].gameIcon +') center no-repeat;background-size: 100%;" ></span>' ;

                    }

                    gstr +='           <figcaption>' +
                        '                        <h2>免费<strong> 试玩</strong></h2>' +
                        '            </figcaption>' ;
                    if(game_type_c=='ag' || game_type_c=='cq' || game_type_c=='fg') { // ag
                        gstr += '<a href="javascript:;" class="try_play" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')"></a>' ;
                    }

                    gstr += ' <a href="javascript:;" class="user_play '+ ((game_type_c=='mg' || game_type_c=='mw')?'mg_user_play':'') +'" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" ></a>' +
                        '      </figure>' +
                        '      <div class="dy_button">' ;
                    if(game_type_c=='ag' || game_type_c=='cq' || game_type_c=='fg') { // ag cq fg
                        gstr += ' <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试玩</a>';
                    }
                    gstr +='           <a href="javascript:;" class="'+ cla +'" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '      </div>' +
                        '  </div>';

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

                    gstr += '<span class="slide-img" style="background: url(images/game/mg/more/'+game_list[i].gameurl +') center no-repeat;background-size: 86%;" ></span>' ;
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

                    // gstr += '<img class="slide-img" src="images/game/mw/'+ game_list[i].gameIcon +'" alt="">' ;
                    gstr += '<span class="slide-img" style="background-image: url(images/game/mw/'+game_list[i].gameIcon +');background-position: 0 -10px;" ></span>' ;
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



        }

        // 鼠标hover 游戏
        function hoverGmae() {
            $('.game-slide').each(function () {
                $(this).hover(function(){
                    $(this).find('.slide-img').addClass('blur');
                    $(this).find('.mask').removeClass('hide')
                }, function(){

                    $(this).find('.slide-img').removeClass('blur');
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

        // ag mg mw 游戏切换
        function changeGameNav(){
            $('.game_choose ul').find('li').on('click',function () {
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                $(this).addClass('active').siblings('li').removeClass('active');
                int_page(1);
                setPageCount();
               // recommendGame();
            })
        }

        indexCommonObj.bannerSwiper();
        int_page(1);
       // recommendGame();
        setPageCount() ;
        seachGameName();
        enterSubmitAction();
        changeGameNav();

    })
</script>