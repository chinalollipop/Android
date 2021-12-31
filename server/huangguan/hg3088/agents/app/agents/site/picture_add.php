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

$categorySql = "SELECT `id`, `name`, `tag` FROM " . DBPREFIX . "web_picture_category WHERE status=1";
$categoryResult = mysqli_query($dbLink, $categorySql);
$categoryList = array();
while ($categoryRow = mysqli_fetch_assoc($categoryResult)){
    $categoryList[$categoryRow['id']] = $categoryRow;
}


if($type == 'add'){ // 基本记录设置
    $categoryId = $_REQUEST['category_id'];

    //$key = $_REQUEST['key'];
    $key = $categoryList[$categoryId]['tag'];   //分类标识
    $aWebsite = $_POST['website']; // 适用站点
    $sWebsite = implode(',', $aWebsite);

    $status = $_REQUEST['status'];
    $remark = $_REQUEST['remark'];
    $now = date('Y-m-d H:i:s');

    // 上传图片
    $oPic = new FileUpload('pic_url','/page');
    $pic_url = $oPic->uploads();
    //if(in_array(3, $aWebsite) && $oPic->fileerror) {
    if($oPic->fileerror) {
        exit("<script> alert('" . $oPic->fileerror . "');history.back();</script>");
    }


    $sql = "INSERT INTO `" . DBPREFIX . "web_pconfig` VALUES (NULL,'{$key}','{$pic_url}','{$categoryId}','{$sWebsite}','{$status}','{$remark}','{$now}','{$now}')";
    $insertId = mysqli_query($dbMasterLink, $sql);
    if($insertId){
        refreshPicCache();
        echo "<script> alert('添加成功！'); location.href='picture.php?uid=$uid&lv=$lv&langx=$langx';</script>";
    }else{
        echo "<script> alert('添加失败！');history.back();</script>";
    }
}

// 更新图片缓存
function refreshPicCache()
{
    global $dbMasterLink;
    $sql = "SELECT `id`, `key`, `pic_url`, `category_id`, `remark` FROM " . DBPREFIX . "web_pconfig WHERE status = 1";
    $result = mysqli_query($dbMasterLink, $sql);
    $lists = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $lists[$row['key']] = !empty($row['pic_url']) ? PROMOS_PIC_DOMAIN . $row['pic_url'] : '';
    }
    $pconfigPicSet = json_encode($lists);
    $redisObj = new Ciredis();
    $redisObj->setOne('pic_config_set', $pconfigPicSet);
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
    <dt>图片管理</dt>
    <dd></dd>
</dl>
<div class="main-ui all width_1000">
    <form NAME="myFORM" action="picture_add.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" method="POST" enctype="multipart/form-data">
        <INPUT TYPE=HIDDEN NAME="type" VALUE="add">
        <table  class="m_tab_ed">
            <tr class="m_title_edit">
                <td colspan="2" ><h4>图片基本信息</h4></td>
            </tr>
            <!--<tr class="m_bc_ed">
                <td class="m_co_ed" width="140">图片标识：</td>
                <td align="left">
                    <input type="text" name="key" id="key" value="" size="50px" maxlength="50" required/>
                    必填，图片标识,且符合50字。
                </td>
            </tr>-->
            <tr>
                <td class="m_co_ed">图片类型：</td>
                <td align="left">
                    <select  name="category_id" id="category_id" onchange="change(this)">
                        <option value="">请选择类型</option>
                        <?php foreach ($categoryList as $key => $category){?>
                            <option value="<?php echo $key;?>"><?php echo $category['name']?></option>
                        <?php }?>
                    </select>
                    必选，<font color="red"><b>（图片分类）</b></font>且活动在此分类下展示。
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
                <td class="m_co_ed" width="140">图片备注：</td>
                <td align="left">
                    <input type="text" name="remark" id="remark" value="" size="50px" maxlength="50" required/>
                    必填，图片备注,且符合50字。
                </td>
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
                <td colspan="2" ><h4>图片信息</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">图片上传：</td>
                <td align="left">
                    <input type="file" name="pic_url" />
                    ◎活动图片：必填，
                    <font color="red"><b>微信二维码图片尺寸：258X258</b></font>
                    <font color="red"><b>APP下载尺寸：260X260</b></font>
                </td>
            </tr>



            <!--<tr class="m_title_edit">
                <td colspan="2" ><h4>图片类型</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">左浮动/IOS下载：</td>
                <td align="left" class="name_title">
                    <input type="file" name="left_pic_url" />
                    ◎活动图片：必填，<font color="red"><b>APP下载尺寸：260X260</b></font>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">右浮动/Android下载：</td>
                <td align="left" class="name_title">
                    <input type="file" name="right_pic_url" />
                    ◎活动图片：必填，<font color="red"><b>APP下载尺寸：260X260</b></font>
                </td>
            </tr>-->



            <tr class="m_bc_ed" align="center">
                <td colspan="2">
                    <input type=SUBMIT name="OK" value="确定" class="za_button">
                    &nbsp; &nbsp; &nbsp;
                    <input type=BUTTON name="FormsButton" value="取消" id="FormsButton" onClick="window.location.replace('picture.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>');" class="za_button">
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function change(obj) {
        var categoryId = $(obj).val();
        //console.log(categoryId);
        if (categoryId != 2) { // 不是微信二维码，需区分类型
            $('.api_url').show();
            $('.pic_info').hide();
        } else {
            $('.pic_info').show();
            $('.api_url').hide();
        }
    }
</script>
</body>
</html>