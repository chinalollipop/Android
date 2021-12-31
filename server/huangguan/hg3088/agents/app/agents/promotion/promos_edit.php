<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");

include_once ("../include/tools/FileUpload.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$id = $_REQUEST['id'];
$uid = $_REQUEST["uid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];
$type = $_REQUEST['type'];
$now = date('Y-m-d H:i:s');

$sql = "SELECT * FROM " . DBPREFIX . "web_promos WHERE `id`=" . $id;
$result = mysqli_query($dbLink, $sql);
$row = mysqli_fetch_assoc($result);

$categoryList = returnPromosType();

// 优惠活动适用站点
$aWebsites = explode(',', $row['website']); // 1：PC；2：Mobile；3：AD

if($type == 'edit'){ // 基本记录设置
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $categoryId = $_POST['category_id'];
    $aWebsite = $_POST['website']; // 适用站点
    $sWebsite = implode(',', $aWebsite);
    $api_url = isset($_POST['api_url']) ? $_POST['api_url'] : '';
    $api_url_old = isset($_POST['api_url_old']) ? $_POST['api_url_old'] : '';
    $api_url_mobile = isset($_POST['api_url_mobile']) ? $_POST['api_url_mobile'] : '';
    $flag = isset($_POST['flag']) ? $_POST['flag'] : '';
    $status = $_POST['status'];
    $isShow = $_POST['is_show'];
    $sequence = $_POST['sequence'];
    $oldPicImg = $_POST['old_pic_img'];
    $oldContentImg = $_POST['old_content_img'];

    // 新旧版
    $oPic = new FileUpload('pic_url');
    $pic_url = $oPic->uploads($oldPicImg);
    if(!$pic_url){
        $pic_url = $oldPicImg;
    }
    if(!$pic_url){
        exit("<script> alert('" . $oPic->fileerror . "');history.back();</script>");
    }
    $oContent = new FileUpload('content_url');
    $content_url = $oContent->uploads($oldContentImg);
    if(!$content_url){
        $content_url = $oldContentImg;
    }
    if(!$content_url){
        exit("<script> alert('" . $oPic->fileerror . "');history.back();</script>");
    }

    // 手机版
    $oldPicImgMobile = $_POST['old_pic_img_mobile'];
    $oldContentImgMobile = $_POST['old_content_img_mobile'];
    $oPicMobile = new FileUpload('pic_url_mobile');
    $pic_url_mobile = $oPicMobile->uploads($oldPicImgMobile);
    if(!$pic_url_mobile){
        $pic_url_mobile = $oldPicImgMobile;
    }
    if(in_array(3, $aWebsite) && !$pic_url_mobile){
        exit("<script> alert('" . $oPicMobile->fileerror . "');history.back();</script>");
    }
    $oContentMobile = new FileUpload('content_url_mobile');
    $content_url_mobile = $oContentMobile->uploads($oldContentImgMobile);
    if(!$content_url_mobile){
        $content_url_mobile = $oldContentImgMobile;
    }
    if(in_array(3, $aWebsite) && !$content_url_mobile){
        exit("<script> alert('" . $oContentMobile->fileerror . "');history.back();</script>");
    }

    // 广告站
    $oldPicImgAd = $_POST['old_pic_img_ad'];
    $oldContentImgAd = $_POST['old_content_img_ad'];
    $oPicAd = new FileUpload('pic_url_ad');
    $pic_url_ad = $oPicAd->uploads($oldPicImgAd);
    if(!$pic_url_ad){
        $pic_url_ad = $oldPicImgAd;
    }
    if(in_array(4, $aWebsite) && !$pic_url_ad){
        exit("<script> alert('" . $oPicAd->fileerror . "');history.back();</script>");
    }
    $oContentAd = new FileUpload('content_url_ad');
    $content_url_ad = $oContentAd->uploads($oldContentImgAd);
    if(!$content_url_ad){
        $content_url_ad = $oldContentImgAd;
    }
    if(in_array(4, $aWebsite) && !$content_url_ad){
        exit("<script> alert('" . $oContentAd->fileerror . "');history.back();</script>");
    }

    $sql = "UPDATE `" . DBPREFIX . "web_promos` SET `title`='{$title}',`subtitle`='{$subtitle}',`pic_url`='{$pic_url}',`content_url`='{$content_url}',`pic_url_mobile`='{$pic_url_mobile}',
            `content_url_mobile`='{$content_url_mobile}',`pic_url_ad`='{$pic_url_ad}',`content_url_ad`='{$content_url_ad}',`category_id`='{$categoryId}',`website`='{$sWebsite}',
            `api_url`='{$api_url}',`api_url_old`='{$api_url_old}',`api_url_mobile`='{$api_url_mobile}',`flag`='{$flag}',`status`='{$status}',`is_show`='{$isShow}',`sequence`='{$sequence}',`updated_at`='{$now}' WHERE `id` = {$id}";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        returnPromosList('edit');
        echo "<script> alert('更新成功！'); location.href='promos.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }else{
        echo "<script> alert('更新失败！'); history.back();</script>";
    }
}
?>
<html>
<head>
    <title>main</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>
<body >
<dl class="main-nav">
    <dt>优惠活动管理</dt>
    <dd></dd>
</dl>
<div class="main-ui all width_1000">
    <form NAME="myFORM" action="promos_edit.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" method="POST" enctype="multipart/form-data">
        <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id?>">
        <INPUT TYPE=HIDDEN NAME="type" VALUE="edit">
        <table  class="m_tab_ed">
            <tr class="m_title_edit">
                <td colspan="2" ><h4>优惠活动基本信息</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">优惠活动标题 :</td>
                <td align="left" class="name_title">
                    <input type="text" name="title" id="title" value="<?php echo $row['title']?>" size="50px" maxlength="50" required/>
                    必填，活动主标题,且符合50字。
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">活动副标题：</td>
                <td align="left">
                    <input type="text" name="subtitle" id="subtitle" value="<?php echo $row['subtitle']?>" size="50px" maxlength="50" required/>
                    必填，活动副标题<font color="red"><b>（副标题换行显示）</b></font>且符合50字。
                </td>
            </tr>
            <tr>
                <td class="m_co_ed">优惠活动类型：</td>
                <td align="left">
                    <select  name="category_id" id="category_id" onchange="change(this)">
                        <option value="">请选择类型</option>
                        <?php foreach ($categoryList as $key => $category){?>
                            <option value="<?php echo $key;?>" <?php if($key == $row['category_id']) echo 'selected';?>><?php echo $category['name']?></option>
                        <?php }?>
                    </select>
                    必选，<font color="red"><b>（新版本分类）</b></font>且活动在此分类下展示。
                </td>
            </tr>
            <tr class="m_bc_ed api_url" <?php if($row['category_id'] != 7) {?> style="display: none;" <?php }?>>
                <td class="m_co_ed" width="140">接口地址：</td>
                <td align="left" class="name_title">
                    新版：<input type="text" name="api_url" id="api_url" value="<?php echo $row['api_url']?>" size="50px" maxlength="50"/><br>
                    旧版：<input type="text" name="api_url_old" id="api_url_old" value="<?php echo $row['api_url_old']?>" size="50px" maxlength="50"/><br>
                    手机：<input type="text" name="api_url_mobile" id="api_url_mobile" value="<?php echo $row['api_url_mobile']?>" size="50px" maxlength="50"/>
                    若类型为自动领取，<font color="red"><b>（开发人员填写，开发接口后才可启用）</b></font>会员才可自动领取。
                </td>
            </tr>
            <tr class="m_bc_ed flag" <?php if($row['category_id'] != 7) {?> style="display: none;" <?php }?>>
                <td class="m_co_ed" width="140">活动标识：</td>
                <td align="left"><input type="text" name="flag" id="flag" value="<?php echo $row['flag']?>" size="10px" maxlength="10"/>
                    若类型为自动领取，<font color="red"><b>（开发人员填写）</b></font>会员才可点击领取或者申请按钮。
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动展示：</td>
                <td align="left">
                    <input name="website[]" type="checkbox" value="1" <?php echo(in_array(1, $aWebsites) ? 'checked' : '')?> />新版
                    <input name="website[]" type="checkbox" value="2" <?php echo(in_array(2, $aWebsites) ? 'checked' : '')?> />旧版
                    <input name="website[]" type="checkbox" value="3" <?php echo(in_array(3, $aWebsites) ? 'checked' : '')?> />手机版
                    <input name="website[]" type="checkbox" value="4" <?php echo(in_array(4, $aWebsites) ? 'checked' : '')?> />广告版
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;◎选择后请在如下<font color="red"><b>对应位置上传图片</b></font>，图片上传类型<font color="red"><b>（jpeg、jpg、png）</b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">是否启用：</td>
                <td align="left">
                    是<input type="radio" name="status" value="1" <?php echo ($row['status'] == 1 ? 'checked' : '');?> >&nbsp;&nbsp;&nbsp;
                    否<input type="radio" name="status" value="0" <?php echo ($row['status'] == 0 ? 'checked' : '');?>>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">是否轮播图展示：</td>
                <td align="left">
                    是<input type="radio" name="is_show" value="1" <?php echo ($row['is_show'] == 1 ? "checked" : "");?> >&nbsp;&nbsp;&nbsp;
                    否<input type="radio" name="is_show" value="0" <?php echo ($row['is_show'] == 0 ? "checked" : "");?>>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">排序：</td>
                <td align="left"><input class="sequence" type="text" name="sequence" value="<?php echo $row['sequence']?>" size="5"/></td>
            </tr>
            <tr class="m_title_edit">
                <td colspan="2" ><h4>新旧版</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动图片 :</td>
                <td align="left" class="real_name">
                    <?php
                    if($row['pic_url']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['pic_url'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_pic_img" value="<?php echo $row['pic_url'];?>" type="hidden" >
                    <input type="file" name="pic_url"/>
                    ◎活动图片：必填，<font color="red"><b>尺寸：361X151 </b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片 :</td>
                <td align="left">
                    <?php
                    if($row['content_url']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['content_url'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_content_img" value="<?php echo $row['content_url'];?>" type="hidden" >
                    <input type="file" name="content_url" />
                    ◎活动内容大图片：必填，<font color="red"><b>尺寸：宽：1185 长：无限制</b></font>
                </td>
            </tr>
            <tr class="m_title_edit">
                <td colspan="2" ><h4>手机版</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动图片 :</td>
                <td align="left" class="real_name">
                    <?php
                    if($row['pic_url_mobile']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['pic_url_mobile'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_pic_img_mobile" value="<?php echo $row['pic_url_mobile'];?>" type="hidden" >
                    <input type="file" name="pic_url_mobile"/>
                    ◎活动图片：必填，<font color="red"><b>尺寸：666X440（手机自适应） </b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片 :</td>
                <td align="left">
                    <?php
                    if($row['content_url_mobile']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['content_url_mobile'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_content_img_mobile" value="<?php echo $row['content_url_mobile'];?>" type="hidden" >
                    <input type="file" name="content_url_mobile" />
                    ◎活动内容大图片：必填，<font color="red"><b>尺寸：宽：666 长：无限制 </b></font>
                </td>
            </tr>
            <tr class="m_title_edit">
                <td colspan="2" ><h4>广告版</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动图片 :</td>
                <td align="left" class="real_name">
                    <?php
                    if($row['pic_url_ad']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['pic_url_ad'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_pic_img_ad" value="<?php echo $row['pic_url_ad'];?>" type="hidden" >
                    <input type="file" name="pic_url_ad" />
                    ◎活动图片：必填，<font color="red"><b>尺寸：942x168 </b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片 :</td>
                <td align="left">
                    <?php
                    if($row['content_url_ad']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['content_url_ad'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_content_img_ad" value="<?php echo $row['content_url_ad'];?>" type="hidden" >
                    <input type="file" name="content_url_ad" />
                    ◎活动内容大图片：必填，<font color="red"><b>尺寸：宽：940 长：无限制</b></font>
                </td>
            </tr>
            <tr class="m_bc_ed" align="center">
                <td colspan="2">
                    <input type=SUBMIT name="OK" value="确定" class="za_button">
                    &nbsp; &nbsp; &nbsp;
                    <input type=BUTTON name="FormsButton" value="取消" id="FormsButton" onClick="window.location.replace('promos.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>');" class="za_button">
                </td>
            </tr>
        </table>
    </form>

</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript">
    function change(obj) {
        var categoryId = $(obj).val();
        if (categoryId == 7) { // 自动领取
            $('.api_url').show();
            $('.flag').show();
        } else {
            $('.api_url').hide();
            $('.flag').hide();
        }
    }

    function show_win(pic) {
        var picUrl = '<?php echo HTTPS_HEAD.'://'.$_SERVER['HTTP_HOST'].'/uploads'; ?>' + pic;
        var str = '<img width="100%;" src="'+picUrl+'" />';
        layer.open({
            type: 1,
            title:'图片预览',
            closeBtn: 1,
            anim: 2, // 动画风格
            area:['600px', '400px'], //宽高
            shade: 0.5,
            shadeClose: true,
            content: str

        });
    }

</script>
</body>
</html>