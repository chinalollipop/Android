<?php
/**
 * 优惠活动管理
 * Date: 2019/8/2
 * Time: 14:02
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");
include_once ("../include/redis.php");
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
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];
$categoryId = $_REQUEST['category_id'];

switch ($type){
    case 'del':
        $sql = "SELECT `pic_url`, `content_url`, `pic_url_mobile`, `content_url_mobile`, `pic_url_ad`, `content_url_ad` FROM " . DBPREFIX . "web_promos WHERE `id`=" . $id;
        $result = mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($result);
        define("ROOT_DIR", dirname(__FILE__));
        define("FILE_DIR", ROOT_DIR."/agents/uploads");
        // 删掉活动图片
        @unlink(FILE_DIR . $row['pic_url']);
        @unlink(FILE_DIR . $row['content_url']);
        @unlink(FILE_DIR . $row['pic_url_mobile']);
        @unlink(FILE_DIR . $row['content_url_mobile']);
        @unlink(FILE_DIR . $row['pic_url_ad']);
        @unlink(FILE_DIR . $row['content_url_ad']);

        $sql = "DELETE FROM `" . DBPREFIX . "web_promos` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            returnPromosList('edit');
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    default: break;
}
$searchWhere = ' WHERE 1=1 ';
if($title){
    $searchWhere .= ' AND `title` like "%' . $title . '%"';
}
if($categoryId){
    $searchWhere .= ' AND `category_id`=' . $categoryId;
}
$sql = "SELECT `id`, `title`, `subtitle`, `category_id`, `status`,`sequence`, `created_at`, `updated_at`,`flag` FROM " . DBPREFIX . "web_promos" . $searchWhere . ' ORDER BY `sequence` ASC, `id` DESC';
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

$categorySql = "SELECT `id`, `name`, `tag` FROM " . DBPREFIX . "web_promos_category WHERE status=1";
$categoryResult = mysqli_query($dbLink, $categorySql);
$categoryList = array();
while ($categoryRow = mysqli_fetch_assoc($categoryResult)){
    $categoryList[$categoryRow['id']] = $categoryRow;
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>优惠活动管理</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav"><dt>优惠活动管理</dt>
        <dd>
            <table >
                <tr class="m_tline">
                    <td >&nbsp;&nbsp;&nbsp;
                        活动标题：
                        <input type="text" id="dlg_text" name="title" value="<?php echo $title?>" class="za_text" size="15" placeholder="请输入关键字">
                        &nbsp;&nbsp;&nbsp;活动类别：
                        <select class="za_select za_select_auto" name="category_id" id="category_id">
                            <option value="">请选择类型</option>
                            <?php foreach ($categoryList as $key => $category){?>
                                <option value="<?php echo $key;?>" <?php if($key == $categoryId){?> selected <?php }?>><?php echo $category['name']?></option>
                            <?php }?>
                        </select>
                        <input type="submit" id="dlg_ok" value="查询" class="za_button">
                        &nbsp;&nbsp;&nbsp;<input type="button" class="za_button" onclick="location.href='promos_add.php?uid=<?php echo $uid;?>&lv=<?php echo $lv;?>&langx=<?php echo $langx;?>'" value="新增优惠" />
                        &nbsp;&nbsp;&nbsp;<input type="button" class="za_button" onclick="location.href='promos_category.php?uid=<?php echo $uid;?>&lv=<?php echo $lv;?>&langx=<?php echo $langx;?>'" value="优惠类型" />
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
</FORM>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td >ID</td>
            <td >活动标题</td>
            <td >副标题 </td>
            <td >活动类型</td>
            <td >启用状态</td>
            <td >排序</td>
            <td >创建时间</td>
            <td >更新时间</td>
            <td >操作</td>
        </tr>
        <?php
        if(!empty($lists)){
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['title']?></td>
                <td><?php echo $value['subtitle']?></td>
                <td>
                    <?php foreach ($categoryList as $key => $category){?>
                        <?php if($key == $value['category_id']) echo $category['name']?>
                    <?php }?>
                </td>
                <td><?php echo 1 == $value['status'] ? '启用' : '禁用';?></td>
                <td><?php echo $value['sequence']?></td>
                <td><?php echo $value['created_at']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td align="left">
                    <input type="button" class="za_button" onclick="location.href='promos_edit.php?uid=<?php echo $uid;?>&lv=<?php echo $lv;?>&id=<?php echo $value['id'];?>&langx=<?php echo $langx;?>'" value="修改" />
                    <input type="button" onclick="btn_del(<?php echo $value['id']?>,'<?php echo $value['id']?>')" value="删除" />
                    <?php
                    if($value['category_id']==7 && $value['status']==1){ // 自助领取
                        echo '<input type="button" class="addPromoRule za_button" value="添加活动规则" data-title="'.$value['title'].'" data-flag="'.$value['flag'].'" readonly/>';
                    }
                    ?>
                </td>
            </tr>
        <?php }}else{
        ?>
        <tr class=m_cen >
            <td colspan="13">
                暂无优惠活动
            </td>
        </tr>
        <?php }?>
    </table>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript">
    function btn_del(id,uid) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"promos.php",
            data:{id : id,uid: uid,type : type},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='promos.php?uid='+uid;
                }
            }
        });
    }

    // 添加活动规则
    function addProRule() {
        $('.addPromoRule').off().on('click',function () {
            var type = $(this).attr('data-flag')
            var title = $(this).attr('data-title')
            var url = 'promos_rule.php?type='+type+'&title='+title;
            layer.open({
                title:'添加优惠活动规则',
                type: 2,
               // shade: false,
                area: ['1160px', '650px'],
                fixed: false, //不固定
                //maxmin: true, //开启最大化最小化按钮
                content: url
            });
        })
    }

    addProRule();
</script>
</body>
</html>