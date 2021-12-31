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
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg

?>

<style>
    .swiper-wrapper {min-height: 120px;max-height: 143px;}
    .page-tiger-hunting{background:center top no-repeat url(<?php echo TPL_NAME;?>images/tigerBg.jpg);background-size:cover;min-height: 1200px;position:relative;background-color:inherit;}
    .gamer-wrap{width: 1360px;margin:0 auto;padding-top: 438px;}
    .game-title{width:100%;height:78px;background-color:#f0f0f0;overflow:hidden}
    .game-title .game-title-nav{float:left;overflow:hidden;line-height:50px;font-size:18px;color:#fff;margin-left: 80px;}
    .game-title-nav span{float:left;margin-left:30px}
    .game-title-nav ul{float:left;overflow:hidden}
    .game-title-nav ul li{float:left;width: 165px;height: 100%;position: relative;}
    .game-title-nav ul li:nth-child(1):after,.game-title-nav ul li:nth-child(2):after{position:absolute;content:"";display:inline-block;width:19px;height:20px;right:12px;top:4px;background:url(/images/game/hot.png) no-repeat;background-size:contain}
    .game-title-nav ul li a{display:inline-block;font-size:18px;color:#000;width:100%;height:100%;white-space:normal;transition:.3s}
    .game-title-nav ul li a.active,.game-title-nav ul li a:hover{background:#fff;color:#000}
    .game-title-search{float:right;overflow:hidden;margin:17px 80px 0 0;position:relative}
    .game-title-search input{width:320px;height:44px;border:1px solid #c1c1c1;border-radius:5px;padding-left:10px;box-shadow:inset 1px 1px 6px #d4d2d2}
    .game-title-search button{width:92px;height:38px;font-size:20px;color:#fff;background:#ff8f03;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;cursor:pointer;vertical-align:bottom;margin-left:5px;outline:none;position:absolute;right:4px;top:3px}
    .game-title-nav ul li a span{float:none;display:block;height:46px;width:100%;margin:0}
    .game-title-nav ul li a p{text-align:center;line-height:30px}
    .game-title-nav ul li a .ag_icon{background: url(/images/game/nav_ag.png) center no-repeat;}
    .game-title-nav ul li a .mg_icon{background: url(/images/game/nav_mg.png) center no-repeat;}
    .game-title-nav ul li a .mw_icon{background: url(/images/game/nav_mw.png) center no-repeat;}
    .game-title-nav ul li a .cq9_icon{background: url(/images/game/nav_cq9.png) center no-repeat;}
    .game-title-nav ul li a .fg_icon{background: url(/images/game/nav_fg.png) center no-repeat;}
    .game-box{background:#fff;padding-top:20px}
    .game-box-middle{margin:0 auto;width: 1200px;}
    .game-hot-tit{height:29px;border-bottom:1px solid #eeaf45;position:relative}
    .game-hot-tit .fl{width:98px;height:30px;font-size:16px;color:#fff;text-align:center;line-height:30px;background:#eeaf45;border-top-left-radius:5px;border-top-right-radius:5px;position:absolute;bottom:0;left:0}
    .game-hot-tit  .fr{font-size:16px;color:#eeaf45}
    .game-hot-tit  .fr span,.game-hot-tit  .fr img{vertical-align:middle;display:inline-block}
    .game-hot-tit .arrow_icon{display: inline-block;width: 10px;height: 15px;background-position: 0 -33px;}
    .game-slide{height:129px ;border:1px solid transparent;position:relative;width:143px}
    .game-slide .game-slide-tit{font-size:12px;color:#fff;text-align:center;height:24px;line-height:24px;background:rgba(0,0,0,0.4);position:absolute;left:0;width:100%;bottom:0}
    .game-slide .mask{position:absolute;width:100%;height:118px;left:0px;bottom:18px;z-index:1;transition:all 0.3s}
    .game-slide .mask a{display:block;width:88px;height:27px;line-height:27px;font-size:14px;color:#fff;text-align:center;background:#eeaf45;border-radius:3px;margin:0 auto}
    .game-slide .mask .freeplay{margin-top:30px}
    .game-slide .mask .comegame{margin-top:8px}
    .game-slide .mask .mg_comegame {margin-top: 45px;}
    .game-slide .slide-img{display: block;position:relative;width: 100%;height: 100%;}
    .game-slide:hover .game-slide-tit{background:#724c2e;color:#ffb400}
    .game-slide:hover{border:1px solid #ffc12b}
    .picScroll-left ul li{float:left;margin-right:11px}
    .picScroll-left{position:relative}
    .picScroll-left .next{width:50px;height:50px;background:url("<?php echo TPL_NAME;?>images/next.jpg") no-repeat;display:block;position:absolute;right: -74px;top:50%;margin-top:-25px;z-index:1;cursor:pointer;}
    .picScroll-left .prev{width:50px;height:50px;display:block;background:url("<?php echo TPL_NAME;?>images/left.jpg");left: -65px;top:50%;margin-top:-25px;position:absolute;z-index:1;cursor:pointer;}
    .game-recommend{margin-top:18px}
    .hot-game-wrap{margin-top:45px}
    .hot-game-list .game-slide{float:left;margin-right: 5px;margin-bottom: 5px;}
    .hot-game-list{margin-top:20px}

    .jackport{position:absolute;width:455px;right:17%;top:30.5%}
    .jackport .t_num i{width:38px;height:57px;display:inline-block;background:url(<?php echo TPL_NAME;?>images/number.png) no-repeat;background-position:0 0}
    .jackport .t_num i:nth-child(12){display:none}
    .jackport .t_num span{color:#fff4a1;font-size:20px;font-weight:800;}

</style>

<div class="page-tiger-hunting">
    <!-- 奖池 -->
    <div class="jackport">
        <span class="t_num t_num1">
            <i style="background-position: 0px -198px;"></i>
            <i style="background-position: 0px 0px;"></i>
            <span>,</span>
            <i style="background-position: 0px -462px;"></i>
            <i style="background-position: 0px -65px;"></i>
            <i style="background-position: 0px -197px;"></i>
            <span>,</span>
            <i style="background-position: 0px -65px;"></i>
            <i style="background-position: 0px -329px;"></i>
            <i style="background-position: 0px -131px;"></i>
           <span>.</span>
            <i style="background-position: 0px -174px;"></i>
            <i style="background-position: 0px -264px;"></i>
            <i style="background-position: 0px -65px;"></i>


        </span>
    </div>

<div class="gamer-wrap">

    <div class="game-title">
        <div class="game-title-nav">
            <!--<span>游戏类型：</span>-->
            <ul>
                <li><a href="javascript:;" class="<?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag"><span class="ag_icon"></span><p>AG电子</p></a></li>
                <li><a href="javascript:;" class="<?php echo $gametype=='mg'?'active':''; ?>" data-gametype="mg"><span class="mg_icon"></span><p>MG电子</p></a></li>
                <li><a href="javascript:;" class="<?php echo $gametype=='mw'?'active':''; ?>"  data-gametype="mw"><span class="mw_icon"></span><p>MW电子</p></a></li>
                <li><a href="javascript:;" class="<?php echo $gametype=='cq'?'active':''; ?>" data-gametype="cq"><span class="cq9_icon"></span><p>CQ9电子</p></a></li>
                <li><a href="javascript:;" class="<?php echo $gametype=='fg'?'active':''; ?>" data-gametype="fg"><span class="fg_icon"></span><p>FG电子</p></a></li>

            </ul>
        </div>
        <div class="game-title-search">
            <input type="text" placeholder="请输入您想要搜索的游戏名" class="seachgame_input" maxlength="14">
            <button class="submit-btn">搜索</button>
        </div>
    </div>
    <div class="game-box">
        <div class="game-box-middle">
            <div class="game-hot-tit clearfix">
                <span class="fl">推荐游戏</span>
                <a class="fr" ><span>更多游戏</span> <span class="cm_icon arrow_icon" > </span> </a>
            </div>
            <div class="game-recommend">
                <div class="picScroll-left">

                    <div class="next swiper-button-next"></div>
                    <div class="prev swiper-button-prev"></div>
               <!--     <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>-->

                    <div class="bd swiper-container">
                            <div class="swiper-wrapper recommend_Game" >

                            </div>

                    </div>

                </div>

            </div>
            <div class="hot-game-wrap">
                <div class="game-hot-tit clearfix">
                    <span class="fl">热门游戏</span>

                </div>

                <div class="hot-game-list clearfix">

                </div>
            </div>
            <!-- 页码 -->
            <div class="pagination">

            </div>
        </div>
    </div>
</div>


</div>


<script type="text/javascript">
    $(function () {

        clearInterval(gameJackPort);
        var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;
        var test_username = '<?php echo $test_username;?>';
        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        var sumarr = [41813152.31,41313552.16,42315192.25,41135157.71,42513151.75,42113152.51,47115112.75,41735117.41,43131117.90,42137110.81];
        var sum = sumarr[parseInt(Math.random()*10)];
        gameJackPort = setInterval(function(){
            show_num1(sum)
        },1500);
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
                var y = -parseInt(num) * 66;
                var obj = $(".t_num1 i").eq(i);
                obj.animate({
                    backgroundPosition: '(0 ' + String(y) + 'px)'
                }, 'slow', 'swing', function() {});
            }

        }
        var gameSwiper = ''; // 轮播
        var count = 16; // 每页展示数量
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
                    var realurl = '/app/member/zrsx/login.php?uid='+uid+'&gameid='+gamelist[j].gameid; // 真钱
                    var tryurl = '/app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+gamelist[j].gameid; // 试玩

                        gstr +='   <div class="game-slide">' ;
                        if(game_type_c=='ag' || game_type_c=='cq'){ // ag cq9
                            if(game_type_c=='cq'){
                                realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                                tryurl = 'https://demo.cqgame.games/';
                            }
                            gstr += '<img class="slide-img" src="'+ gamelist[j].gameurl +'" alt="">' ;
                            gstr +='                        <p class="game-slide-tit">'+ gamelist[j].name +'</p>' +
                                '                        <div class="mask hide">' +
                                '                            <a class="freeplay" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>' +
                                '                            <a class="comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                                '                        </div>' +
                                '                    </div>';
                            }else if(game_type_c=='mg'){ // mg
                            realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                            tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;

                            gstr += '<span class="slide-img" style="background: url(images/game/mg/more/'+gamelist[j].gameurl +') center no-repeat;background-size: 86%;" ></span>' ;
                            gstr +='                        <p class="game-slide-tit">'+ gamelist[j].name +'</p>' +
                                '                        <div class="mask hide">' +
                                '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                                '                        </div>' +
                                '                    </div>';
                        } else if(game_type_c=='mw'){ // mw
                        realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;

                        gstr += '<span class="slide-img" style="background-image: url(images/game/mw/'+gamelist[j].gameIcon +');background-position: -5px 0;background-size:cover;" ></span>' ;
                        gstr +='                        <p class="game-slide-tit">'+ gamelist[j].gameName +'</p>' +
                            '                        <div class="mask hide">' +
                            '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                            '                        </div>' +
                            '                    </div>';
                    } else if(game_type_c=='fg'){ // fg
                            realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                            tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                            gstr += '<img class="slide-img" src="images/game/fg/'+gamelist[j].gameIcon +'">' ;
                            gstr +='                        <p class="game-slide-tit">'+ gamelist[j].gameName +'</p>' +
                                '                        <div class="mask hide">' +
                                '                            <a class="freeplay" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>' +
                                '                            <a class="comegame fg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                                '                        </div>' +
                                '                    </div>';
                        }

                }

            $('.hot-game-list').html(gstr) ;
            hoverGmae();


        }
        // 推荐游戏
        function recommendGame() {

            var gstr ='' ;
            for (var i = 5 ; i < 20; i++) {
                var realurl = '/app/member/zrsx/login.php?uid='+uid+'&gameid='+game_list[i].gameid;
                var tryurl = '/app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+game_list[i].gameid;
                gstr +='<div class="swiper-slide ">' +
                    '                                <div class="game-slide">' ;
                if(game_type_c=='ag' || game_type_c=='cq'){ // ag
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
                }else if(game_type_c=='mg'){ // mg
                    realurl = '/app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                    tryurl = '/app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+game_list[i].gameid ;

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

                    // gstr += '<img class="slide-img" src="../../images/game/mw/'+ game_list[i].gameIcon +'" alt="">' ;
                    gstr += '<span class="slide-img" style="background-image: url(images/game/mw/'+game_list[i].gameIcon +');background-position: -5px 0;background-size:cover;" ></span>' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].gameName +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>'+
                        '</div>';
                }else if(game_type_c=='fg'){ // fg
                    realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameId ;
                    tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+game_list[i].gameId ;

                    gstr += '<img class="slide-img" src="images/game/fg/'+game_list[i].gameIcon +'">' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].gameName +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="freeplay" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>' +
                        '                            <a class="comegame fg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
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

        // ag mg 游戏切换
        function changeGameNav(){
            $('.game-title-nav ul').find('a').on('click',function () {
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                $(this).addClass('active').parents('li').siblings().find('a').removeClass('active');
                int_page(1);
                setPageCount();
                recommendGame();
            })
        }

        int_page(1);
        recommendGame();
        setPageCount() ;
        seachGameName();
        enterSubmitAction();
        changeGameNav();

    })
</script>
