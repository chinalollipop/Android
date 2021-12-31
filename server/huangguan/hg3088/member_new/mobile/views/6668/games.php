<?php
include_once('../../include/config.inc.php');
require_once("../../../../common/mg/config.php");
require_once("../../../../common/ag/config.php");
require_once("../../../../common/cq9/config.php");

$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名

$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:'ag'; // 不传默认ag ,ag mg cq9 mw

// AG电子游戏列表
foreach ($agXinGames as $k => $v){
    if (strlen($v['gameTypeM'])>0){
        $agGameList[$k]['gameid'] = $v['gameTypeM'];
        $agGameList[$k]['name'] = $v['name'];
        $agGameList[$k]['gameurl'] = '/images/aggame/'.$v['gameurl'];
    }
}
$agGameList = array_values($agGameList);

// WM电子游戏列表
foreach ($aWmGames as $k => $v){
    $mwGameList[$k]['gameId'] = $v['gameId'];
    $mwGameList[$k]['gameName'] = $v['gameName'];
    $mwGameList[$k]['gameIcon'] = '/images/mwgame/'.$v['gameIcon'];
    $mwGameList[$k]['gameRuleUrl'] = $v['gameRuleUrl'];
}

// CQ9电子游戏列表
foreach ($aCqGames as $k => $v){
    $cqGameList[$k]['gameid'] = $v['gameid'];
    $cqGameList[$k]['name'] = $v['name'];
    $cqGameList[$k]['gameurl'] = '/images/cqgame/'.$v['gameurl'];
}

?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
    <style type="text/css">
        .tab-nav .item{display: none;}
        .tab-nav .item.active{display: inline-block;}
        .tab-nav .item a, .tab-nav .item span{padding: .4rem 1rem;}
        .nav-over {overflow-x: auto;}
        /* .tab-nav {width: 125%;padding-bottom: 8px;}*/
    </style>
</head>
<body >
<div id="container" class="dialog-content">

    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间部分 -->
    <div class="content-center ele-game">

        <div class="liveNav">
            <span class="live-money">电子额度：￥<span id="video_blance" class="live_money"></span></span>
            <a class="live-change limit-toggle"><span class="change-icon"></span>额度转换</a>
        </div>
        <!-- 标签切换 -->
        <div class="nav-over">
            <div class="tab-nav">
            <div class="item <?php echo ($gametype=='ag')?'active':'';?>" data-action="1">
                <a href="javascript:;" onclick="getGameList('ag',this)">AG电子</a>
            </div>
            <div class="item <?php echo ($gametype=='mg')?'active':'';?>" >
                <a href="javascript:;" onclick="getGameList('mg',this)">MG电子</a>
            </div>
            <div class="item <?php echo ($gametype=='cq')?'active':'';?>" >
                <a href="javascript:;" onclick="getGameList('cq',this)">CQ9电子</a>
            </div>
            <div class="item <?php echo ($gametype=='mw')?'active':'';?>" >
                <a href="javascript:;" onclick="getGameList('mw',this)">MW电子</a>
            </div>
                <div class="item <?php echo ($gametype=='fg')?'active':'';?>" >
                    <a href="javascript:;" onclick="getGameList('fg',this)">FG电子</a>
                </div>
        </div>
        </div>
        <!-- 内容区域 -->
        <div class="type-wrap game-list ">

        </div>


    </div>


    <div class="clear"></div>

    <!-- 底部footer -->
    <div id="footer">

    </div>


</div>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/animate.js"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid;?>';
    var gametype = '<?php echo $gametype;?>';

    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    setLoginHeaderAction('电子游戏','','',usermon,uid) ;
    setFooterAction(uid);  // 在 addServerUrl 前调用

    // pt 游戏列表
    var ptlist =[
        {
            name:'狂欢夜' ,
            gameurl:'/images/ptgame/ano.png',
        },
        {
            name:'狂野的亚马逊' ,
            gameurl:'/images/ptgame/ashamw.png',
        },
        {
            name:'北极秘宝' ,
            gameurl:'/images/ptgame/art.png',
        },
        {
            name:'海滨嘉年华' ,
            gameurl:'/images/ptgame/bl.png',
        },
        {
            name:'奖金熊' ,
            gameurl:'/images/ptgame/bob.png',
        },
        {
            name:'船长的宝藏' ,
            gameurl:'/images/ptgame/ct.png',
        },
        {
            name:'猫女王' ,
            gameurl:'/images/ptgame/catqc.png',
        },

    ];



    // 游戏列表渲染
    function getGameList(id,obj) {
        if(obj){
            $(obj).parent().addClass('active').siblings().removeClass('active') ; // 标签切换类
        }
        var str = '<ul>' ;
        var gamelist ;
        var licla = 'mg-list' ;
        if(id=='mg'){
            // console.log('gggg');
            if(uid){
                get_blance('mg') ;
            }
            gamelist = <?php echo json_encode($mgGamesInfo, JSON_UNESCAPED_UNICODE);?> ;
            licla = 'mg-list' ;
            for(var i=0;i<gamelist.length;i++){
                var realurl = "/mg/mg_api.php?action=getLaunchGameUrl&game_id="+gamelist[i].item_id;
                str +='<li class="'+licla+'"><a  href="javascript:;" onclick="ifHasLogin(\''+realurl+'\',\'win\',\''+uid+'\')">' +
                   // '<span class="mg_img" style="background-image: url(/images/mg/'+gamelist[i].gameurl+'.png)" ></span>'+
                    '<img src="/images/mg/app_more/'+gamelist[i].gameurl+'" >'+
                    '<p>'+gamelist[i].name+'</p>'+
                    '</a></li>' ;
            }
            str +='</ul>' ;
        }
        else if(id=='ag'){
            // console.log('ppppp');
            if(uid){
                get_blance('balance') ;
            }

            gamelist = <?php echo json_encode($agGameList, JSON_UNESCAPED_UNICODE);?> ;
            licla = 'pt-list' ;
            for(var i=0;i<gamelist.length;i++){
                var realurl = '/zrsx_login.php?gameid='+gamelist[i].gameid;
                str +='<li class="'+licla+'"><a  href="javascript:;" onclick="ifHasLogin(\''+realurl+'\',\'win\',\''+uid+'\')">' +
                    '<img src="'+gamelist[i].gameurl+'" />'+
                    '<p>'+gamelist[i].name+'</p>'+
                    '</a></li>' ;
            }
            str +='</ul>' ;
        }
        else if(id=='cq'){

            if(uid){
                get_blance('cq') ;
            }

            gamelist = <?php echo json_encode($cqGameList, JSON_UNESCAPED_UNICODE);?> ;
            //console.log(gamelist);
            licla = 'cq-list' ;
            for(var i=0;i<gamelist.length;i++){
                var realurl = "/cq9/cq9_api.php?action=getLaunchGameUrl&game_id="+gamelist[i].gameid;
                str +='<li class="'+licla+'"><a  href="javascript:;" onclick="ifHasLogin(\''+realurl+'\',\'win\',\''+uid+'\')">' +
                    '<img src="'+gamelist[i].gameurl+'" />'+
                    '<p>'+gamelist[i].name+'</p>'+
                    '</a></li>' ;
            }
            str +='</ul>' ;
        }
        else if(id=='mw'){
            if(uid){
                get_blance('mw') ;
            }

            gamelist = <?php echo json_encode($mwGameList,JSON_UNESCAPED_UNICODE);?>;
            // console.log(gamelist)
            licla = 'mw-list' ;
            for (var i=0;i<gamelist.length;i++){
                var realurl = "/mw/mw_api.php?action=appGameLobby&gameId="+gamelist[i].gameId;
                str +='<li class="'+licla+'"><a  href="javascript:;" onclick="ifHasLogin(\''+realurl+'\',\'win\',\''+uid+'\')">' +
                    '<img style="width: 90%" src="'+gamelist[i].gameIcon+'" />'+
                    '<p >'+gamelist[i].gameName+'</p>'+
                    '</a></li>';
                str +='</ul>' ;
            }

        }else if(id=='fg'){
            if(uid){
                get_blance('fg') ;
            }

            gamelist = <?php echo json_encode($fgGameList,JSON_UNESCAPED_UNICODE);?>;
            //console.log(gamelist)
            licla = 'fg-list' ;
            for (var i=0;i<gamelist.length;i++){
                var realurl = "/fg/fg_api.php?action=getLaunchGameUrl&game_id="+gamelist[i].gameId;
                str +='<li class="'+licla+'"><a  href="javascript:;" onclick="ifHasLogin(\''+realurl+'\',\'win\',\''+uid+'\')">' +
                    '<img style="width: 90%" src="/images/fggame/'+gamelist[i].gameIcon+'" />'+
                    '<p >'+gamelist[i].gameName+'</p>'+
                    '</a></li>';
                str +='</ul>' ;
            }

        }
        else{
            gamelist = ptlist ;
            licla = 'pt-list' ;
        }

        $('.game-list').html(str) ;

    }

    getGameList(gametype) ; // 默认 ag


</script>


</body>
</html>