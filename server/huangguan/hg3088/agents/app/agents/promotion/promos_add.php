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

$uid = $_REQUEST["uid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];
$type = $_REQUEST['type'];

$categoryList = returnPromosType();

if($type == 'add'){ // 基本记录设置
    $title = $_REQUEST['title'];
    $subtitle = $_REQUEST['subtitle'];
    $categoryId = $_REQUEST['category_id'];
    $aWebsite = $_POST['website']; // 适用站点
    $sWebsite = implode(',', $aWebsite);
    $api_url = isset($_REQUEST['api_url']) ? $_REQUEST['api_url'] : '';
    $api_url_old = isset($_REQUEST['api_url_old']) ? $_REQUEST['api_url_old'] : '';
    $api_url_mobile = isset($_REQUEST['api_url_mobile']) ? $_REQUEST['api_url_mobile'] : '';
    $flag = isset($_POST['flag']) ? $_POST['flag'] : '';
    $status = $_REQUEST['status'];
    $isShow = $_REQUEST['is_show'];
    $sequence = $_REQUEST['sequence'];
    $now = date('Y-m-d H:i:s');

    $oPic = new FileUpload('pic_url');
    $pic_url = $oPic->uploads();
    if($oPic->fileerror) {
        exit("<script> alert('" . $oPic->fileerror . "');history.back();</script>");
    }
    $oContent = new FileUpload('content_url');
    $content_url = $oContent->uploads();
    if($oContent->fileerror) {
        exit("<script> alert('" . $oPic->fileerror . "');history.back();</script>");
    }

    // 手机版
    $oPicMobile = new FileUpload('pic_url_mobile');
    $pic_url_mobile = $oPicMobile->uploads($oldPicImgMobile);
    if(in_array(3, $aWebsite) && $oPicMobile->fileerror) {
        exit("<script> alert('" . $oPicMobile->fileerror . "');history.back();</script>");
    }
    $oContentMobile = new FileUpload('content_url_mobile');
    $content_url_mobile = $oContentMobile->uploads($oldContentImgMobile);
    if(in_array(3, $aWebsite) && $oContentMobile->fileerror) {
        exit("<script> alert('" . $oContentMobile->fileerror . "');history.back();</script>");
    }

    // 广告站
    $oPicAd = new FileUpload('pic_url_ad');
    $pic_url_ad = $oPicAd->uploads();
    if(in_array(4, $aWebsite) && $oPicAd->fileerror) {
        exit("<script> alert('" . $oPicAd->fileerror . "');history.back();</script>");
    }
    $oContentAd = new FileUpload('content_url_ad');
    $content_url_ad = $oContentAd->uploads();
    if(in_array(4, $aWebsite) && $oContentAd->fileerror) {
        exit("<script> alert('" . $oContentAd->fileerror . "');history.back();</script>");
    }
    $sql = "INSERT INTO `" . DBPREFIX . "web_promos` VALUES (NULL,'{$title}','{$subtitle}','{$pic_url}','{$content_url}','{$pic_url_mobile}','{$content_url_mobile}','{$pic_url_ad}','{$content_url_ad}','{$categoryId}','{$sWebsite}','{$api_url}','{$api_url_old}','{$api_url_mobile}','{$flag}','{$status}','{$isShow}','{$sequence}','{$now}','{$now}')";
    $insertId = mysqli_query($dbMasterLink, $sql);
    if($insertId){
        returnPromosList('edit');
        echo "<script> alert('添加成功！'); location.href='promos.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }else{
        echo "<script> alert('添加失败！');history.back();</script>";
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
    <form NAME="myFORM" action="promos_add.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" method="POST" enctype="multipart/form-data">
        <INPUT TYPE=HIDDEN NAME="type" VALUE="add">
        <table  class="m_tab_ed">
            <tr class="m_title_edit">
                <td colspan="2" ><h4>优惠活动基本信息</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">优惠活动标题：</td>
                <td align="left">
                    <input type="text" name="title" id="title" value="" size="50px" maxlength="50" required/>
                    必填，活动主标题,且符合50字。
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">活动副标题：</td>
                <td align="left">
                    <input type="text" name="subtitle" id="subtitle" value="" size="50px" maxlength="50" required/>
                    必填，活动副标题<font color="red"><b>（副标题换行显示）</b></font>且符合50字。
                </td>
            </tr>
            <tr>
                <td class="m_co_ed">优惠活动类型：</td>
                <td align="left">
                    <select  name="category_id" id="category_id" onchange="change(this)">
                        <option value="">请选择类型</option>
                        <?php foreach ($categoryList as $key => $category){?>
                            <option value="<?php echo $key;?>"><?php echo $category['name']?></option>
                        <?php }?>
                    </select>
                    必选，<font color="red"><b>（新版本分类）</b></font>且活动在此分类下展示。
                </td>
            </tr>
            <tr class="m_bc_ed api_url" <?php if($row['category_id'] != 7) {?> style="display: none;" <?php }?>>
                <td class="m_co_ed" width="140">接口地址：</td>
                <td align="left" class="name_title">
                    新版：<input type="text" name="api_url" id="api_url" value="" size="50px" maxlength="50"/><br>
                    旧版：<input type="text" name="api_url_old" id="api_url_old" value="" size="50px" maxlength="50"/><br>
                    手机：<input type="text" name="api_url_mobile" id="api_url_mobile" value="" size="50px" maxlength="50"/>
                    若类型为自动领取，<font color="red"><b>（开发人员填写，开发接口后才可启用）</b></font>会员才可自动领取。
                </td>
            </tr>
            <tr class="m_bc_ed flag" <?php if($row['category_id'] != 7) {?> style="display: none;" <?php }?>>
                <td class="m_co_ed" width="140">活动标识：</td>
                <td align="left"><input type="text" name="flag" id="flag" value="" size="10px" maxlength="10"/>
                    若类型为自动领取，<font color="red"><b>（开发人员填写）</b></font>会员才可点击领取或者申请按钮。
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">是否启用：</td>
                <td align="left">
                    是<input type="radio" name="status" value="1" checked >&nbsp;&nbsp;&nbsp;
                    否<input type="radio" name="status" value="0" >
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">是否轮播图展示：</td>
                <td align="left">
                    是<input type="radio" name="is_show" value="1" >&nbsp;&nbsp;&nbsp;
                    否<input type="radio" name="is_show" value="0" checked >
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">排序：</td>
                <td align="left"><input class="sequence" type="text" name="sequence" value="0" size="5px"/></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动展示：</td>
                <td align="left">
                    <input name="website[]" type="checkbox" value="1" checked="checked"/>新版
                    <input name="website[]" type="checkbox" value="2" />旧版
                    <input name="website[]" type="checkbox" value="3" />手机版
                    <input name="website[]" type="checkbox" value="4" />广告版
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;◎选择后请在如下<font color="red"><b>对应位置上传图片</b></font>，图片上传类型<font color="red"><b>（jpeg、jpg、png）</b></font>
                </td>
            </tr>
            <tr class="m_title_edit">
                <td colspan="2" ><h4>新旧版</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动图片：</td>
                <td align="left">
                    <input type="file" name="pic_url" />
                    ◎活动图片：必填，<font color="red"><b>尺寸：361X151</b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片：</td>
                <td align="left">
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
                    <input type="file" name="pic_url_mobile"/>
                    ◎活动图片：必填，<font color="red"><b>尺寸：666X440（手机自适应） </b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片 :</td>
                <td align="left">
                    <input type="file" name="content_url_mobile" />
                    ◎活动内容大图片：必填，<font color="red"><b>尺寸：宽：666 长：无限制 </b></font>
                </td>
            </tr>
            <tr class="m_title_edit">
                <td colspan="2" ><h4>广告站</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动图片：</td>
                <td align="left">
                    <input type="file" name="pic_url_ad" />
                    ◎活动图片：必填，<font color="red"><b>尺寸：942x168</b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">优惠活动内容图片：</td>
                <td align="left">
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
</script>
</body>
</html>