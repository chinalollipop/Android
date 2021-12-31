<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>
    .banner{position: relative;height: 550px;}
    .app_bottom{position: absolute;top: 130px;left: 0;}
    .app_bottom .aap_xz{position:absolute}
    .app_bottom .app_wz{font-size:18px;left:100px;color:#e0ca77}
    .app_bottom .app_txt{width:450px;font-size:17px;color:#e0ca77;float:left;margin-top:60px}
    .app_bottom .ewm{position:relative;float:right;margin:30px 0 0 180px}
    .app_bottom .ewm span{background-size: cover !important;display:inline-block;width:156px;height:156px;position:absolute;top:35px;left:4px}
    .app_bottom .ewm span.andriod_app{left: 206px;}
</style>

<div class="banner w_1000">

    <div class="app_bottom">
        <div class="app_wz">APP在手畅玩无优</div>
        <div class="app_txt">
            提供电子、捕鱼、棋牌、视讯、彩票、体育等多种游戏应有尽有，给您改极致的博彩享受！全面支持苹果IOS、安卓Android、平板电脑Ipad，完美手机投注，随时随地，想玩就玩。
            <br>
            Provide a variety of games such as electronics, fishing, chess, video, lottery, sports, etc., to give you the ultimate gaming enjoyment! Full support for Apple IOS, Android ,
        </div>
        <div class="ewm">
            <span class="ios_app download_ios_app"> </span>
            <span class="andriod_app download_android_app"> </span>
            <img src="<?php echo $tplNmaeSession;?>images/ewm.png" alt="">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        
    })
</script>