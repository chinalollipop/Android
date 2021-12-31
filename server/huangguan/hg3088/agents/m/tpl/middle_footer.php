<?php


?>

<div class="footerNav flex">
    <a href="middle_index.php?type=home" target="loadPageBox" class="<?php echo (($navtype=='home' || $navtype=='')?'active':'');?>" data-type="home"> <span class="icon icon_home"></span> <span> 首页 </span> </a>
    <a href="middle_member.php?type=user&navtitle=会员管理" target="loadPageBox" class="<?php echo ($navtype=='user'?'active':'');?>" data-type="user"> <span class="icon icon_user"></span> <span> 会员 </span> </a>
    <a href="middle_report.php?type=bb&navtitle=报表管理" target="loadPageBox" class="<?php echo ($navtype=='bb'?'active':'');?>" data-type="bb"> <span class="icon icon_bb"></span> <span> 报表 </span> </a>
    <a href="middle_setting.php?type=sz&navtitle=设置" target="loadPageBox" class="<?php echo ($navtype=='sz'?'active':'');?>" data-type="sz"> <span class="icon icon_sz"></span> <span> 设置 </span> </a>
</div>

