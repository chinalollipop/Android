<?php
session_start();

include "../../../../app/member/include/config.inc.php";
require_once("../../../../../common/mg/config.php");
require_once("../../../../../common/ag/config.php");
require_once("../../../../../common/cq9/config.php");

/* 奖池 */
$sumarr = array('1,312,210.11','3,452,241.12','2,912,279.31','1,412,279.71','2,710,269.92','3,152,701.10','1,541,691.81','2,341,791.01','3,171,740.51','4,318,095.15','1,016,678.91','1,268,651.60','1,578,711.15','2,171,071.43','1,471,041.47','1,321,011.12','2,141,221.08');
shuffle($sumarr); // 打乱数组

$game_af_str = json_encode($sumarr);

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
    .game_banner{height:400px;background:url(<?php echo TPL_NAME;?>images/game/game_banner.jpg) center no-repeat}
    .game_banner>div{position:relative;height:100%}
    .game_banner>div span{position:absolute;display:inline-block;width:90px;height:97px}
    .game_banner>div .icon_1{background:url(<?php echo TPL_NAME;?>images/game/game_icon_1.png) center no-repeat;top:135px;animation:right-pig-move 8s infinite alternate both}
    .game_banner>div .icon_2{background:url(<?php echo TPL_NAME;?>images/game/game_icon_2.png) center no-repeat;left:470px;top:45px;animation:left-pig-move 8s infinite alternate both}
    .qgx{height:150px;width:2px;display:inline-block}
    .winning_name{margin:8px 0}
    .winning_name span{margin-right:20px}
    .winning_name span:last-child{color:#efd08b}
    .slots>div{padding:15px 5px;overflow: hidden;}
    .slots .searchBar{width:250px}
    .slots .searchBar h3{width:144px;height:70px;line-height:70px;font-size:18px;color:#fff;text-align:center;font-weight:700}
    .slots .searchBar .inputBox{width:100%;height:65px;background:#fff;position:relative}
    .slots .searchBar .inputBox .searchInput{height:100%;width:200px;background:none;border:none;padding-left:20px;font-size:20px}
    .slots .searchBar .advance .typeOfGame .cbox-row .cbox-label,.slots .searchBar .advance .typeOfGame .cbox-row a,.slots .searchBar .advance .typeOfGame .cbox-row span,.slots .searchBar .btnBox,.slots .searchBar .btnBox .btn1,.slots .searchBar .keywordsBox,.slots .searchBar .keywordsBox h4,.slots .searchBar .keywordsBox ul,.slots .searchBar .keywordsBox ul li,.slots .searchBar h3,.slots .slotsGame .award span,.slots .slotsGame .gameBox,.slots .slotsGame .king .ico,.slots .slotsGame .star .ico,.slots .topList .part.textPart .likeList ul li .likeIco,.slots .topList .part.textPart .likeList ul li p,.slots .topList .part.textPart .likeList ul li span,.fl{float:left;*display:inline}
    .slots .searchBar .inputBox .ico{position:absolute;width:40px;height:40px;background:url(<?php echo TPL_NAME;?>images/game/ss.png) center no-repeat;cursor:pointer;transition:all .5s ease;top:10px;right:0}
    .game_title_bottom .game_yxfl a{position:relative;display:inline-block;color:#4c4b4b;padding:5px 0 5px 40px;font-size:16px;margin-right:25px}
    .game_title_bottom .game_yxfl a.active{color:#47a4f7}
    .game_title_bottom .game_yxfl a:before{position:absolute;content:'';display:inline-block;width:26px;height:30px;background:url(images/game/tip_icon.png) no-repeat;background-position:-31px 2px;transition:all 0.2s ease-in-out;left:5px;top:-5px}
    .game_title_bottom .game_yxfl a:nth-child(2):before{background-position:-169px 0px}
    .game_title_bottom .game_yxfl a:last-child:before{background-position:-97px 0px}
    .game_title_bottom .game_yxfl a.active:before{background-position-y:-35px}
    .slots .game_right{width:930px}
    .game_choose{background: #fff;}
    .game_choose ul{margin-left:10px}
    .game_choose ul li{transition:.3s;position:relative;cursor:pointer;float:left;width:110px;height:65px;background:#fff;color:#424242;text-align:center;line-height:100px;font-size:16px;}
    .game_choose ul li.active:before{background-position-y:5px !important}
    .game_choose ul li:before{position:absolute;content:'';display:inline-block;width:55px;height:40px;margin:0 22px;left:5px;background:url(<?php echo TPL_NAME;?>images/game/title_icon.png) no-repeat;background-position:2px -33px}
    .game_choose ul li:after{position:absolute;content: '';display: inline-block;width: 1px;height: 50px;background: #f5f5f5;top: 10px;left: 108px;z-index: 1;}
    .game_choose ul li.mg_li:before{background-position:-116px -36px}
    .game_choose ul li.mw_li:before{width:60px;background-position:-376px -36px}
    .game_choose ul li.cq_li:before{width:65px;background-position:-237px -36px;margin:0 18px}
    .game_choose ul li.fg_li:before{background-position:-516px -36px}
    .slots .slotsGame{width:100%;min-height:360px;margin-top:15px;background: #fff;}
    .slots .hot-game-list{overflow:hidden;padding:15px 5px 0;height:824px}
    .slots .slotsGame .gameBox{position:relative;width:160px;border-radius:5px;transition:all .5s ease;margin:0 10px 20px;height:180px;border:1px solid #ccc}
    .slots .slotsGame .gameBox:nth-child(5n+1){margin-left:16px}
    .slots .slotsGame .gameBox .imgBox img{width:100%;height:100%}
    .slots .slotsGame .gameBox .gameName{width:100%;height:35px}
    .slots .searchBar,.slots .slotsGame .gameBox .gameName,.slots .slotsGame .gameBox .imgBox,.slots .slotsTitle,.slots .topList,.slots .topList .part.textPart .likeList,.slots .topList .part h2,.slots .typeOfGame,.pr{position:relative}
    .slots .slotsGame .gameName h3{text-align:center;line-height:35px;font-size:14px;font-weight:400;color:#4c4b4b;z-index:1}
    .slots .slotsGame .gameBox .imgBox{width:100%;height:145px;border-radius:5px 5px 0 0;overflow:hidden}
    .hr{width:100%;height:1px;border-bottom:1px dashed #fff}
    .slots .slotsGame .hr{width:165px;height:1px;margin:0}
    .slots .slotsGame .btn1{display:block;width:74%;height:35px;line-height:35px;font-size:14px;color:#fff;text-align:center;background:#708ae8;background:linear-gradient(to right,#708ae8 0%,#5ea0ea 100%);border-radius:5px;margin:15px auto;transition:.3s}
    .slots .slotsGame .btn1:first-child{margin-top:28px}
    .slots .slotsGame .btn1.btn_mg:first-child{margin-top:47px}
    .slots .slotsGame .btn1:hover{opacity:.9}
    .slots .slotsGame .gameBox .mask{position:absolute;width:100%;height:145px;left:0px;bottom:37px;z-index:1;transition:all 0.3s;background:rgba(0,0,0,.4)}
    .jackPotAll{background:#fff;margin:15px 0 20px;color:#8a8a8a}
    .jackPotAll .title{width:100%;height:80px;padding-bottom:15px;background:url(<?php echo TPL_NAME;?>images/game/jackpot.png) center no-repeat;border-bottom:1px solid #e9e8e8}
    .jackPotAll .list{display:-webkit-flex;display:flex;padding:22px 0;border-bottom:1px solid #f5f5f5}
    .jackPotAll .list>span{display:inline-block}
    .jackPotAll .list>span:first-child{width:75px;height:75px;margin:0 20px}
    .jackPotAll .list>span a{display: inline-block;width: 100%;}
    .jackPotAll .list>span a img{width: 100%;border-radius:100%;border: 1px solid #5da1ea;}
    .jackPotAll .list>span p{margin-top:15px}
    .jackPotAll .list>span p:last-child{margin-top:7px}
    .app_download{position:relative;text-align:center;display:block;width:200px;padding-left:50px;height:65px;line-height:65px;border-radius:10px;font-size:24px;background:#708ae8;background:linear-gradient(to bottom,#708ae8 0%,#5ea0ea 100%)}
    .app_download:hover{opacity: .8;}
    .app_download:before{position:absolute;content:'';display:inline-block;width:32px;height:100%;left:35px;background:url(<?php echo TPL_NAME;?>images/game/app_icon.png) center no-repeat}
    .pagination{background:none;box-shadow:none;margin-bottom:0;padding:10px 0 20px}
</style>

<div class="game_bg">
    <div class="game_banner">
        <div class="w_1200">
            <span class="icon_1"> </span>
            <span class="icon_2"> </span>
        </div>
    </div>
    <div class="slots">

        <div class="w_1200">
            <!-- 左侧 -->
            <div class="left searchBar">
                <div class="game_title_bottom">
                    <!-- <div class="fl game_yxfl">
                         <a href="javascript:;" class="active" data-type="all">全部游戏</a>
                         <a href="javascript:;" data-type="rm">热门游戏</a>
                         <a href="javascript:;" data-type="new">最新游戏</a>
                     </div>-->
                    <div class="inputBox fr border_shadow">
                        <label>
                            <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="游戏搜索">
                        </label>
                        <div class="submit-btn ico"></div>
                    </div>
                    <div style="clear: both"></div>

                </div>

                <!-- jackpots -->
                <div class="jackPotAll border_shadow">
                    <div class="title"> </div>
                    <div class="jackpotLi recommend_Game">
                        <div class="list">
                            <span class="icon"></span>
                            <span class="txt ">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                        <div class="list">
                            <span class="icon "></span>
                            <span class="txt">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                        <div class="list">
                            <span class="icon "></span>
                            <span class="txt">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                        <div class="list">
                            <span class="icon"></span>
                            <span class="txt">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                        <div class="list">
                            <span class="icon"></span>
                            <span class="txt">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                        <div class="list">
                            <span class="icon"></span>
                            <span class="txt">
                                    <p>双龙抢珠</p>
                                    <p>￥754545.32</p>
                                </span>
                        </div>
                    </div>
                </div>

                <a class="to_downloadapp app_download" href="javascript:;"> 下载客户端 </a>

            </div>
            <!-- 右侧 -->
            <div class="right game_right">
                <div class="gameList_all">
                    <div class="game_choose border_shadow">
                        <ul>
                            <!--<li class="game_active">热门游戏</li>
                            <li>漫威热门系列</li>-->
                            <li class="ag_li <?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag">AG电子</li>
                            <li class="mg_li <?php echo $gametype=='mg'?'active':''; ?>" data-gametype="mg">MG电子</li>
                            <li class="cq_li <?php echo $gametype=='cq'?'active':''; ?>" data-gametype="cq">CQ9电子</li>
                            <li class="mw_li <?php echo $gametype=='mw'?'active':''; ?>" data-gametype="mw">MW电子</li>
                            <li class="fg_li <?php echo $gametype=='fg'?'active':''; ?>" data-gametype="fg">FG电子</li>
                            <!--<li>电影老虎机</li>
                            <li>纸牌游戏</li>-->
                        </ul>
                        <div style="clear: both"></div>
                    </div>

                    <!--   游戏-->
                    <div class="slotsGame border_shadow" id="gameSearch11">
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
</div>


<script type="text/javascript">
    $(function () {
        var jackpotArr = '<?php echo $game_af_str;?>';
        jackpotArr = $.parseJSON(jackpotArr);
       // console.log(jackpotArr);

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
                var y = -parseInt(num) * 52;
                var obj = $(".t_num1 i").eq(i);
                obj.animate({
                    backgroundPosition: '(0 ' + String(y) + 'px)'
                }, 'slow', 'swing', function() {});
            }

        }
       // var gameSwiper = ''; // 轮播
        var count = 20; // 每页展示数量
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
            jackpotArr.sort(randomSort);
            var gstr ='' ;
            for (var i = 7 ; i < 13; i++) {
                var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+game_list[i].gameid;
                var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+game_list[i].gameid;
                if(game_type_c=='ag' || game_type_c=='cq'){ // ag  cq9
                    if(game_type_c=='cq'){
                       realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                       tryurl = 'https://demo.cqgame.games/';
                    }
                }
                else if(game_type_c=='mg'){ // mg
                    realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                    tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+game_list[i].gameid ;
                }
                else if(game_type_c=='mw'){ // mw
                    realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+game_list[i].gameId ;
                }else if(game_type_c=='fg') { // fg
                    realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id=' + game_list[i].gameId;
                    tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id=' + game_list[i].gameId;
                }
                gstr +='<div class="list">' +
                    '       <span class="icon"><a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" title="立即游戏"> ';
                if(game_type_c=='mg'){
                    gstr += '<span style="background: url(images/game/mg/more/'+game_list[i].gameurl +') center no-repeat;background-size: 100%;display: block;height: 100%;margin: 0 auto;"> </span>';
                }else if(game_type_c=='mw' || game_type_c=='fg'){
                    game_list[i].name = game_list[i].gameName;
                    gstr +='<img src="images/game/'+game_type_c+'/'+game_list[i].gameIcon +'" >';
                }else{
                    gstr += '<img src="'+  game_list[i].gameurl +'"> ';
                }

                gstr += '</a></span>' +
                    '         <span class="txt">' +
                    '            <p>'+ game_list[i].name +'</p>' +
                    '             <p>￥<span class="cjcj_num">'+ jackpotArr[i] +'</span></p>' +
                    '         </span>' +
                    '     </div>';

            }

            $('.recommend_Game').html(gstr) ;
            hoverGmae();

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
                recommendGame();

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

        function randomSort(a, b) {
            return Math.random()>.5 ? -1 : 1;
            //用Math.random()函数生成0~1之间的随机数与0.5比较，返回-1或1
        }

        int_page(1);
        recommendGame();
        setPageCount() ;
        seachGameName();
        //enterSubmitAction();
        changeGameNav();
        changeGameType();

    })
</script>