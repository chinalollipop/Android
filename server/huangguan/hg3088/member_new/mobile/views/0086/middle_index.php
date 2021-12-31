<?php

$uid=$_SESSION["Oid"];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName']; // 拿到用户名


?>


<!-- 轮播图 区域-->
<div class="carousel swiper-container">
    <div class="swiper-wrapper">

    </div>
    <!-- 如果需要分页器 -->
    <div class="swiper-pagination"></div>
</div>
<!-- 滚动公告 -->
<div class="notice index_notice" onclick="javascript:void(0);location.href='<?php echo TPL_NAME;?>moremessage.php'">
    <div class="notice-cont">
                         <span class="notice-icon">
                             <i class="fa fa-volume-down"></i>
                         </span>
        <div class="text">
            <marquee>
                <?php echo getScrollMsg();?>
            </marquee>
        </div>

    </div>
</div>

<div id="content">
    <div class="Menual">
        <a href="javascript:;" onclick="ifHasLogin('/template/sport_main.php','','<?php echo $oid?>')"><span class="game-sport-logo"></span>体育投注</a>
    </div>
    <div class="Menual">
            <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/avia/avia_api.php?action=getLaunchGameUrl\',\'win\',\''.$oid.'\')';?>" >
            <span class="game-fydj-logo"></span>泛亚电竞</a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/thunfire/fire_api.php?action=getLaunchGameUrl\',\'win\',\''.$oid.'\')';?>" >
            <span class="game-lhdj-logo"></span>雷火电竞</a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'live.php\',\'\',\''.$oid.'\')';?>" >
            <span class="game-live-ag-logo"></span>AG视讯</a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/og/login.php\',\'win\',\''.$oid.'\')';?>" >
            <span class="game-live-og-logo"></span>OG视讯</a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/bbin/login.php\',\'win\',\''.$oid.'\')';?>" >
            <span class="game-live-bbin-logo"></span>BBIN视讯</a>
    </div>

    <div class="Menual">
        <a onclick="ifHasLogin('<?php echo $cpUrl;?>','win','<?php echo $oid?>')" >
            <span class="game-lottery-logo"></span>彩票游戏
        </a>
    </div>

    <?php if($_SESSION['Agents'] == 'demoguest'){?>
        <div class="Menual">
            <a href="https://sw.vgvip88.com" target="_blank">
                <span class="game-chess-logo-hg"></span>VG棋牌
            </a>
        </div>
        <div class="Menual">
            <a href="https://demo.leg666.com" target="_blank">
                <span class="game-chess-logo-ly"></span>乐游棋牌
            </a>
        </div>

        <div class="Menual">
            <a href="http://play.ky206.com/jump.do" target="_blank">
                <span class="game-chess-logo-ky"></span>开元棋牌
            </a>
        </div>
    <?php }else{?>

        <div class="Menual">
            <a onclick="ifHasLogin('/vgqp/','','<?php echo $oid?>')">
                <span class="game-chess-logo-hg"></span>VG棋牌
            </a>
        </div>
        <div class="Menual">
            <a onclick="ifHasLogin('/lyqp/','','<?php echo $oid?>')">
                <span class="game-chess-logo-ly"></span>乐游棋牌
            </a>
        </div>
       <!-- <div class="Menual">
            <a onclick="ifHasLogin('/hgqp/','','<?php /*echo $oid*/?>')">
                <span class="game-chess-logo-hg"></span>皇冠棋牌
            </a>
        </div>-->

        <div class="Menual">
            <a onclick="ifHasLogin('/ky/','','<?php echo $oid?>')">
                <span class="game-chess-logo-ky"></span>开元棋牌
            </a>
        </div>
    <?php } ?>
    <div class="Menual">
            <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/klqp/\',\'\',\''.$oid.'\')';?>" >
            <span class="game-chess-logo-kl"></span>快乐棋牌
        </a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/'.TPL_NAME.'listGames.php\',\'\',\''.$oid.'\')';?>" >
            <span class="game-game-logo"></span>电子游戏</a>
    </div>
    <div class="Menual">
        <a href="javascript:;" onclick="<?php echo ($flage && $flage=='test')?'alert(\'非常抱歉，请您注册真实会员！\')':'ifHasLogin(\'/zrsx_login.php?gameid=6&uid='.$oid.'\',\'win\',\''.$oid.'\')';?>" >
            <span class="game-by-logo"></span>捕鱼王</a>
    </div>

    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>promo.php" >
            <span class="game-promo-logo"></span>优惠活动
        </a>
    </div>
    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>agents_reg.php" >
            <span class="game-dljm-logo"></span>代理加盟</a>
    </div>
    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>qqwechat.php" >
            <span class="game-lxwm-logo"></span>联系我们</a>
    </div>
    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>help.php" >
            <span class="game-help-logo"></span>新手教学</a>
    </div>
    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>moremessage.php" >
            <span class="game-hggg-logo"></span>皇冠公告</a>
    </div>
    <div class="Menual">
        <a href="<?php echo $weburl?>" >
            <span class="game-pc-logo"></span>电脑版</a>
    </div>

    <div class="Menual">
        <a href="<?php echo TPL_NAME;?>appdownload.php" >
            <span class="game-app-logo"></span>APP下载
        </a>
    </div>

    <div class="clear"></div>
</div>


<script type="text/javascript">


</script>
