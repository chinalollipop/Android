<?php
require ("../include/config.inc.php");

$lists = returnPromosList('',4);
?>
<style type="text/css">


</style>

<!-- 主体 -开始 -->
<div class="subban subban5"></div>
<div class="psr">
    <div class="banyuan">

    </div>
</div>

<div class="subcont">
    <div class="inner">
        <div class="cl h13"></div>
        <div class="youhuicont">
            <ul>
                <?php foreach ($lists as $key => $activity){?>
                    <li>
                        <div class="yh1"><img src="<?php echo $activity['imgurl'];?>" alt=""></div>
                        <div class="yh2"><img src="<?php echo $activity['contenturl'];?>" alt=""></div>
                        <div class="cl"></div>
                    </li>
                <?php }?>
            </ul>
        </div>
        <div class="cl h25"></div>
    </div>
</div>
<div class="cl"></div>


<!-- 主体 -结束 -->


