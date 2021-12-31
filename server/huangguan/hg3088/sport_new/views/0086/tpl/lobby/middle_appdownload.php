<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];

?>
<style>
    .phoneBg{width:100%;min-height:800px;height:calc(100vh - 85px);background:url("<?php echo $tplNmaeSession;?>images/phoneBg.jpg") no-repeat center center;background-size:cover}
    .codeBoxs{display:flex;position:absolute;left:50%;margin-left:-560px;top:504px}
    .codeBoxs img{margin:0 20px}
    .codeBoxs_1{position:absolute;display:flex;width:388px;height:200px;right:34px;top:0;justify-content:space-between}
    .codeBoxs_1 span{display:inline-block;width:160px;background-size:100% !important}
</style>

<div class="phoneBg">
    <div class="w_900" style="width: 1200px;position: relative;">
        <div class="codeBoxs">
            <div class="codeBoxs_1">
                <span class="download_ios_app"></span>
                <span class="download_android_app"></span>
            </div>
            <img src="<?php echo $tplNmaeSession;?>images/iosdown.png?v=1" alt="app下载">
            <img src="<?php echo $tplNmaeSession;?>images/android.png?v=1" alt="app下载">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {


        
    })
</script>