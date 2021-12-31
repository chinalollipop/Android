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

$sql = "SELECT * FROM " . DBPREFIX . "web_article WHERE `id`=" . $id;
$result = mysqli_query($dbLink, $sql);
$row = mysqli_fetch_assoc($result);

if($type == 'edit'){ // 基本记录设置
    $title = $_REQUEST['title'];
    $subtitle = $_REQUEST['subtitle'];
    $aWebsite = $_REQUEST['website']; // 适用站点
    $sWebsite = implode(',', $aWebsite);
    $flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : '';
    $status = $_REQUEST['status'];
    $isShow = $_REQUEST['is_show'];
    $is_hot = $_REQUEST['is_hot'];
    $oldCover = $_REQUEST['old_cover'];
    $content = $_REQUEST['content'];

    // 新旧版
    $oCover = new FileUpload('cover','/articlecover');
    $cover_url = $oCover->uploads($oldCover);
    if(!$cover_url){
        $cover_url = $oldCover;
    }
    if(!$cover_url){
        exit("<script> alert('" . $oCover->fileerror . "');history.back();</script>");
    }

    $sql = "UPDATE `" . DBPREFIX . "web_article` SET `title`='{$title}',`subtitle`='{$subtitle}',
            `cover`='{$cover_url}',`content`='{$content}',`status`='{$status}',`is_hot`='{$is_hot}',`updated_at`='{$now}' WHERE `id` = {$id}";
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        echo "<script> alert('更新成功！'); location.href='article.php?uid=$uid&lv=$lv&langx=$langx';</script>";
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
    <script type="text/javascript" charset="utf-8" src="../include/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../include/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="../include/ueditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body >
<dl class="main-nav">
    <dt>新闻资讯管理-修改</dt>
    <dd></dd>
</dl>
<div class="main-ui all width_1000">
    <form NAME="myFORM" action="article_edit.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>" method="POST" enctype="multipart/form-data">
        <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id?>">
        <INPUT TYPE=HIDDEN NAME="type" VALUE="edit">
        <table  class="m_tab_ed">
            <tr class="m_title_edit">
                <td colspan="2" ><h4>新闻资讯基本信息</h4></td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">新闻资讯标题 :</td>
                <td align="left" class="name_title">
                    <input type="text" name="title" id="title" value="<?php echo $row['title']?>" size="50px" maxlength="50" required/>
                    必填，活动主标题,且符合50字。
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed" width="140">活动副标题：</td>
                <td align="left">
                    <input type="text" name="subtitle" id="subtitle" value="<?php echo $row['subtitle']?>" size="150px" maxlength="50" required/><br>
                    必填，活动副标题<font color="red"><b>（副标题换行显示）</b></font>且符合100字。
                </td>
            </tr>
            <tr class="m_bc_ed flag" <?php if($row['category_id'] != 7) {?> style="display: none;" <?php }?>>
                <td class="m_co_ed" width="140">活动标识：</td>
                <td align="left"><input type="text" name="flag" id="flag" value="<?php echo $row['flag']?>" size="10px" maxlength="10"/>
                    若类型为自动领取，<font color="red"><b>（开发人员填写）</b></font>会员才可点击领取或者申请按钮。
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
                <td class="m_co_ed">是否首页轮播：</td>
                <td align="left">
                    是<input type="radio" name="is_hot" value="1" <?php echo ($row['is_hot'] == 1 ? 'checked' : '');?> >&nbsp;&nbsp;&nbsp;
                    否<input type="radio" name="is_hot" value="0" <?php echo ($row['is_hot'] == 0 ? 'checked' : '');?> >
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">文章封面图片 :</td>
                <td align="left" class="real_name">
                    <?php
                    if($row['cover']){?>
                        <input type="button" class="levelmanage za_button" onClick="show_win('<?php echo $row['cover'];?>');" value="图片预览" />
                    <?php }?>
                    <input name="old_cover" value="<?php echo $row['cover'];?>" type="hidden" >
                    <input type="file" name="cover"/>
                </td>
            </tr>
            <tr class="m_bc_ed">
                <td class="m_co_ed">内容：</td>
                <td align="left">
                    <script id="editor" type="text/plain" name="content" style="width:100%;height:200px;"><?php echo $row['content'];?></script>
                </td>
            </tr>
            <tr class="m_bc_ed" align="center">
                <td colspan="2">
                    <input type=SUBMIT name="OK" value="确定" class="za_button">
                    &nbsp; &nbsp; &nbsp;
                    <input type=BUTTON name="FormsButton" value="取消" id="FormsButton" onClick="window.location.replace('article.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>');" class="za_button">
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
        var str = '<img src="'+picUrl+'" style="width: 100%;">';
        layer.open({
            type: 1,
            title:'图片预览',
            skin: 'layui-layer-gameplay',
            anim: 2, // 动画风格
            area: ['600px', '400px'],
            content: str,
            shadeClose: true,
            shade: 0.5,
            yes: function(){
                layer.closeAll();
            }
        });
    }

</script>

<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
</script>
</body>
</html>