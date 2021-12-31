<?php
/**
 * 新闻资讯管理
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once ("../include/address.mem.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];

$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];

switch ($type){
    case 'del':
        $sql = "SELECT `cover`,`content` FROM " . DBPREFIX . "web_article WHERE `id`=" . $id;
        $result = mysqli_query($dbLink, $sql);
        $row = mysqli_fetch_assoc($result);

        // 删掉活动图片
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern,$row['content'],$match);
        define("ROOT_DIR", dirname(__FILE__));
        define("FILE_DIR", ROOT_DIR."/agents");
        $aDelImg = array();
        foreach ($match[1] as $k => $v){
            if (strpos($v,'https')!==false || strpos($v,'http')!==false ){
            }else{
                @unlink(FILE_DIR . $v);
            }
        }

        // 删除封面图片
        @unlink(FILE_DIR. '/uploads' . $row['cover']);

//        echo FILE_DIR. '/uploads' . $row['cover']; die;

        $sql = "DELETE FROM `" . DBPREFIX . "web_article` WHERE `id` = {$id}";
        $result = mysqli_query($dbMasterLink, $sql);
        if($result){
            exit(json_encode(['code' => 0, 'msg' => '删除成功！']));
        }else{
            exit(json_encode(['code' => -1, 'msg' => '删除失败！']));
        }
        break;
    default: break;
}
$searchWhere = ' WHERE 1 ';
if($title){
    $searchWhere .= ' AND `title` like "%' . $title . '%"';
}
$sql = "SELECT * FROM " . DBPREFIX . "web_article" . $searchWhere . ' ORDER BY `id` DESC';
$result = mysqli_query($dbLink, $sql);
$lists = array();
while ($row = mysqli_fetch_assoc($result)){
    $lists[$row['id']] = $row;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>新闻资讯管理</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
    </style>
</head>
<body>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav"><dt>新闻资讯管理</dt>
        <dd>
            <table >
                <tr class="m_tline">
                    <td >&nbsp;&nbsp;&nbsp;
                        活动标题：
                        <input type="text" id="dlg_text" name="title" value="<?php echo $title?>" class="za_text" size="15" placeholder="请输入关键字">

                        <input type="submit" id="dlg_ok" value="查询" class="za_button">
                        &nbsp;&nbsp;&nbsp;<input type="button" class="za_button" onclick="location.href='article_add.php?lv=<?php echo $lv;?>&langx=<?php echo $langx;?>'" value="新增新闻" />
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
</FORM>
<div class="main-ui">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="5%">ID</td>
            <td width="15%">活动标题</td>
            <td width="20%">副标题 </td>
            <td width="5%">启用状态</td>
            <td width="7%">是否首页轮播</td>
            <td width="15%">创建时间</td>
            <td width="15%">更新时间</td>
            <td width="10%">操作</td>
        </tr>
        <?php
        if(!empty($lists)){
        foreach ($lists as $k=>$value){?>
            <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['title']?></td>
                <td><?php echo $value['subtitle']?></td>
                <td><?php echo 1 == $value['status'] ? '启用' : '禁用';?></td>
                <td><?php echo 1 == $value['is_hot'] ? '是' : '否';?></td>
                <td><?php echo $value['created_at']?></td>
                <td><?php echo $value['updated_at']?></td>
                <td>
                    <input type="button" class="za_button" onclick="location.href='article_edit.php?lv=<?php echo $lv;?>&id=<?php echo $value['id'];?>&langx=<?php echo $langx;?>'" value="修改" />
                    <input type="button" onclick="btn_del(<?php echo $value['id']?>)" value="删除" />
                </td>
            </tr>
        <?php }}else{
        ?>
        <tr class=m_cen >
            <td colspan="13">
                暂无新闻资讯
            </td>
        </tr>
        <?php }?>
    </table>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">
    function btn_del(id) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"article.php",
            data:{id : id,type : type},
            success:function (response) {
                response = $.parseJSON(response);
                alert(response.msg);
                if (response.code == 0){
                    window.location.href='article.php';
                }
            }
        });
    }
</script>
</body>
</html>