<?php
require ("../include/config.inc.php");

$lists = returnPromosList('',4);

?>
<style type="text/css">
    /* CSS Document */
    .yhhd_m{
        float:left;
        display:inline;
        width:100%;
        margin-top:50px;
    }
    .yhhd_mn{
        margin:0 auto;
        overflow:hidden;
        width:942px;
        margin-bottom:20px ;
    }
    .yhhd_mn_pic{
        display:block;
        width:100%;
        margin-bottom:7px;
    }
    .yhhd_mn span{cursor:pointer}
    .yhhd_mn_con{ text-align:center}
</style>

<!-- 主体 -开始 -->
<div class="yhhd_m">
    <div class="yhhd_mn">
        <p class="yhhd_mn_pic"><img src="images/promos/yhhd_pic1.jpg" /></p>
        <span class="yhhd_mn_pic">
            <img src="images/promos/yhhd_cgp.png?v=2" />

        </span>
        <div class="yhhd_mn_con" style="display:none;">
            <img src="images/promos/cgp1.jpg" usemap="#planetmap" />
            <map name="planetmap" id="planetmap">
                <area shape="rect" coords="100,325,690,390" onclick="window.open('https://cgpay.pw')">
            </map>
        </div>
        <?php foreach ($lists as $key => $activity){?>
            <span class="yhhd_mn_pic"><img src="<?php echo $activity['imgurl'];?>" /></span>
            <div class="yhhd_mn_con" style="display:none;">
                <img src="<?php echo $activity['contenturl'];?>"/>
            </div>
        <?php }?>
    </div>
</div>
<!-- 主体 -结束 -->


