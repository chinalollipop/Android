<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
?>
<style>
    .phoneBg{width:100%;height:836px;background:url("<?php echo $tplNmaeSession;?>images/phoneBg.jpg?v=2") center center no-repeat;}
    .codeBoxs{position:absolute;display:flex;width:390px;height:250px;left:50%;margin:400px 110px;justify-content:space-between}
    .codeBoxs span{display:inline-block;width:170px;background-size:100% !important}
</style>

<div class="phoneBg">
    <div class="w_900" style="width: 1200px;position: relative;">
        <div class="codeBoxs">
            <span class="download_ios_app"></span>
            <span class="download_android_app"></span>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(function () {


        
    })
</script>