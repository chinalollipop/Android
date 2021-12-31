<?php
session_start();

$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$companyName = $_SESSION['COMPANY_NAME_SESSION'] ;
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

/* 在线人数 */
$sumonline = array('22,919','21,419','32,719','23,151','25,541','15,341','29,170','9,315','18,018','19,261','21,571','25,171');
shuffle($sumonline); // 打乱数组

$gamerule = 'https://gci.6668ag.com/agingame/rules/new/zh/index.jsp?bid=true&vip=true&bac_db=true&bac_pairs=true&bac_superSix=true&bac_in=true&nn=true&bj=true&zjh=true&bf=true&goodRoad=true&stamp=190517_1.80_1';
$og_game_rule_url = "http://video.n80tu2.com/game/rules/ch/index.html";

?>
<style>
    /*live*/
    .mainBody{height:748px;background:url(<?php echo $tplNmaeSession;?>images/live/live-bg1.jpg)}
    .inMain_content{width:1000px;margin:0px auto;margin-bottom:15px}
    .live_content{width:1000px;margin:-10px auto 40px}
    .live_content>ul>li{border:1px solid #363636;width:326px;height:230px;margin-top:70px;margin-right:8px;float:left;position:relative;cursor:pointer;background:#0c0c0c}
    .live_content>ul>li:nth-child(3n){margin-right:0}
    .live_content>ul>li .box{width:326px;height:186px;position:relative}
    .live_content>ul>li .box .lv_bg{position:absolute;top:6px;left:6px}
    .live_content>ul>li .box .lv_model{position:absolute;bottom:0;right:7px}
    .live_content>ul>li .box .lv_logo{position:absolute;top:15px;left:15px}
    .live_content>ul>li .box .lv_title{position:absolute;top:85px;left:15px;color:#dadada;font-size:14px;line-height:1.5}
    .live_content>ul>li .box .lv_online{position:absolute;top:156px;left:15px;color:#fffd8a;font-size:14px}
    .live_content>ul>li .live_title{padding-left:15px;line-height:48px;color:#fff;font-size:16px}
    .live_content>ul>li a.gz_a{display:block;position:absolute;top:195px;right:150px;width:23px;height:23px;line-height:22px;background:url(<?php echo $tplNmaeSession;?>images/123.png)}
    .live_content>ul>li a.start_a{width:120px;height:32px;color:#000;font-size:16px;font-weight:600;border-radius:16px;cursor:pointer;text-align:center;background:#ffce4b;position:absolute;top:191px;right:15px;line-height:32px}
    .live_content>ul>li:hover{background:#ffce4b}
    .live_content>ul>li:hover .box .lv_title{color:#ebea81}
    .live_content>ul>li:hover .live_title{color:#000}
    .live_content>ul>li:hover a.start_a{color:#fff;background:#6e531f}
</style>

<div class="page_banner">
    <div class="promlink">
        <div class="centre clearFix">
            <div class="title"><img src="<?php echo $tplNmaeSession;?>images/live/livemain.jpg"></div>
            <div class="marqueeWarp">
                <p style="text-align: center">
                    <marquee id="msgNews" scrollamount="4" scrolldelay="100" direction="left" onmouseover="this.stop();" onmouseout="this.start();" style="cursor: pointer;height: 30px;line-height: 30px;width: 950px;color: #fff;">
                        <?php echo $_SESSION['memberNotice']; ?>
                    </marquee>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mainBody">
    <div class="inMain_content clearfix">
        <div class="live_content clearfix">
            <ul class="clearfix">
                <li>
                    <div class="box">
                        <p class="lv_bg"><img src="<?php echo $tplNmaeSession;?>images/live/live_bg_1.png" alt="bg"></p>
                        <p class="lv_model"  ><img src="<?php echo $tplNmaeSession;?>images/live/live_1.png" alt="model"></p>
                        <p class="lv_logo"><img src="<?php echo $tplNmaeSession;?>images/ag_logo.png" alt="logo"></p>
                        <h2 class="lv_title">最专业的完善的<br>娱乐平台</h2>
                        <p class="lv_online"><span>在线人数：</span><span id="num01"><?php echo $sumonline[0];?></span></p>
                    </div>
                    <h1 class="live_title">AG旗舰厅</h1>
                    <a href="<?php echo $gamerule;?>" class="gz_a" title="游戏规则" target="_blank"></a>
                    <a href="javascript:;" class="start_a  aLoginCheck-lo" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">开始游戏</a>
                </li>
                <li>
                    <div class="box">
                        <p class="lv_bg"><img src="<?php echo $tplNmaeSession;?>images/live/live_bg_1.png" alt="bg"></p>
                        <p class="lv_model"  ><img src="<?php echo $tplNmaeSession;?>images/live/live_3.png" alt="model"></p>
                        <p class="lv_logo"><img src="<?php echo $tplNmaeSession;?>images/ag_logo.png" alt="logo"></p>
                        <h2 class="lv_title">东南亚最大赌场<br>舒适体验</h2>
                        <p class="lv_online"><span>在线人数：</span><span id="num01"><?php echo $sumonline[2];?></span></p>
                    </div>
                    <h1 class="live_title">AG赌场厅</h1>
                    <a href="<?php echo $gamerule;?>" class="gz_a" title="游戏规则" target="_blank"></a>
                    <a href="javascript:;" class="start_a  aLoginCheck-lo" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">开始游戏</a>
                </li>
                <!-- OG -->
                <li>
                    <div class="box">
                        <p class="lv_bg"><img src="<?php echo $tplNmaeSession;?>images/live/live_bg_1.png" alt="bg"></p>
                        <p class="lv_model"  ><img src="<?php echo $tplNmaeSession;?>images/live/live_2.png" alt="model"></p>
                        <p class="lv_logo"><img src="<?php echo $tplNmaeSession;?>images/og_logo.png" alt="logo"></p>
                        <h2 class="lv_title">亚洲顶级棋牌</h2>
                        <p class="lv_online"><span>在线人数：</span><span id="num01"><?php echo $sumonline[1];?></span></p>
                    </div>
                    <h1 class="live_title">OG旗舰厅</h1>
                    <a href="<?php echo $og_game_rule_url;?>" class="gz_a" title="游戏规则" target="_blank"></a>
                    <a href="javascript:;" class="start_a  aLoginCheck-lo" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">开始游戏</a>
                </li>
                <!-- bbin -->
                <li>
                    <div class="box">
                        <p class="lv_bg"><img src="<?php echo $tplNmaeSession;?>images/live/live_bg_2.png" alt="bg"></p>
                        <p class="lv_model"><img src="<?php echo $tplNmaeSession;?>images/live/live_4.png" alt="model"></p>
                        <p class="lv_logo"><img src="<?php echo $tplNmaeSession;?>images/bbin_logo.png" alt="logo"></p>
                        <h2 class="lv_title">自助切牌，<br>玩家可以主播<br>语音文字聊天。</h2>
                        <p class="lv_online"><span>在线人数：</span><span id="num02"><?php echo $sumonline[3];?></span></p>
                    </div>
                    <h1 class="live_title">BBIN竟咪厅</h1>
                    <a href="javascript:;" class="gz_a" title="游戏规则" onclick="alert('请进入游戏查看')"></a>
                    <a href="javascript:;" class="start_a aLoginCheck-lo" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')">开始游戏</a>
                </li>
                <li>
                    <div class="box">
                        <p class="lv_bg"><img src="<?php echo $tplNmaeSession;?>images/live/live_bg_3.png"  alt="bg"></p>
                        <p class="lv_model"><img src="<?php echo $tplNmaeSession;?>images/live/live_5.png" alt="model"></p>
                        <p class="lv_logo"><img src="<?php echo $tplNmaeSession;?>images/wns_logo.png" alt="logo"></p>
                        <h2 class="lv_title"><?php echo $companyName;?><br>将为您带来更多优质<br>真人娱乐服务</h2>
                    </div>
                    <h1 class="live_title"></h1>
                    <a href="javascript:;"  class="gz_a" title="游戏规则"></a>
                    <a href="javascript:;" class="start_a aLoginCheck-lo" >敬请期待</a>
                </li>
            </ul>
        </div>
    </div>
    
</div>



<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;

    })
</script>