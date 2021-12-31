<?php
/**
 * 2021新年活动配置
 */

session_start();
require_once '../include/config.inc.php';
require_once "../include/address.mem.php";
require_once '../include/redis.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];

//新增数据
$left_interval = $_REQUEST['left_interval'];
$right_interval = $_REQUEST['right_interval'];
$giftGold = $_REQUEST['giftGold'];

//修改或删除id
$id = $_REQUEST['id'];

$type = isset($_REQUEST["type"]) && $_REQUEST["type"] ? $_REQUEST["type"] : '';
$level=$_REQUEST['level'];  //层级
$redPocketOpen = isset($_REQUEST["redPocketOpen"]) && $_REQUEST["redPocketOpen"] ? $_REQUEST["redPocketOpen"] : ''; // 是否开启签到
$newYearBeginTime = isset($_REQUEST["newYearBegin"]) && $_REQUEST["newYearBegin"] ? $_REQUEST["newYearBegin"] : ''; //2021-01-10 12:00:00
$newYearEndTime = isset($_REQUEST["newYearEnd"]) && $_REQUEST["newYearEnd"] ? $_REQUEST["newYearEnd"] : ''; //2021-02-12 12:00:00


$redisObj = new Ciredis();
if($type =='redPocket'){ // APP红包开关
    $redPocketset = array(
        'redPocketOpen'=>$redPocketOpen,
        'newYearBeginTime'=>$newYearBeginTime,
        'newYearEndTime'=>$newYearEndTime,
    );
    $redisObj->setOne('red_pocket_open',json_encode($redPocketset)) ;
}
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;
//echo '<pre>';
//print_r($aRedPocketset);
if(!$aRedPocketset){
    $aRedPocketset['redPocketOpen'] = '';
    $aRedPocketset['newYearBeginTime'] = '';
    $aRedPocketset['newYearEndTime'] = '';
}

// 新增等级输赢额区间
if($type == 'add'){

    if(!isset($level)){
        exit(json_encode(['err'=>-1,'msg'=>'等级错误，请重新输入'],JSON_UNESCAPED_UNICODE));
    }

    if ($right_interval<= $left_interval ){
        exit(json_encode(['err'=>-4,'msg'=>'输赢额区间有误，请重新输入'],JSON_UNESCAPED_UNICODE));
    }

    if(!isset($giftGold)){
        exit(json_encode(['err'=>-1,'msg'=>'金额错误，请重新输入'],JSON_UNESCAPED_UNICODE));
    }

    $res=mysqli_query($dbMasterLink,"insert `".DBPREFIX."newyear_2021_hb_config` values ('','{$level}','{$left_interval}','{$right_interval}','{$giftGold}')");
    if ($res){
        exit(json_encode(['err'=>0,'msg'=>'']));
    }else{
        exit(json_encode(['err'=>-5,'msg'=>'添加错误，请检查数据！'],JSON_UNESCAPED_UNICODE));
    }
}

if($type == 'delete'){

    if($id == '' || $id == false || !isset($id) ){
        exit(json_encode(['err'=>-6,'msg'=>'删除错误，请检查数据！']));
    }
    $res=mysqli_query($dbMasterLink,"delete from `".DBPREFIX."newyear_2021_hb_config` where id = $id");
    if ($res){
        exit(json_encode(['err'=>0,'msg'=>'']));
    }else{
        exit(json_encode(['err'=>-7,'msg'=>'删除错误，请检查数据！']));
    }
}

if($type == 'edit'){
    //print_r($_REQUEST);exit(11);
    $sql = "UPDATE `" . DBPREFIX . "newyear_2021_hb_config` SET `giftGold`='{$giftGold}' WHERE `id` = {$id}";
    //echo $sql;
    $result = mysqli_query($dbMasterLink, $sql);
    if($result){
        exit(json_encode(['err' => 0, 'msg' => '更新成功！']));
    }else{
        exit(json_encode(['err' => -8, 'msg' => '更新失败！']));
    }

}

$result_data = mysqli_query($dbLink,"select * from ".DBPREFIX."newyear_2021_hb_config order by level asc;");
$cou=mysqli_num_rows($result_data);
if ($cou>0){
    $data=[];
    while ($row = mysqli_fetch_assoc($result_data)){
        $data[]=$row;
    }
}


?>
<html>
<head>
    <title>2021新年活动配置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        .m_title td input.za_text {width: auto !important;text-align: center;}
        .m_title td:nth-child(4) input.za_text,.m_title td:nth-child(5) input.za_text {width: 70px;}
        form{margin-bottom: 20px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt style="width: 200px;">2021新年活动配置</dt>
    <dd>

    </dd>
</dl>

<div class="main-ui ">
    <div class="width_1300">

        <!-- 开关 -->
        <form action="newyear2021_hb_config.php?uid=<?php echo $uid?>&type=redPocket&lv=<?php echo $level?>"  method="post">
            <table class="m_tab">
                <tr class="m_title">
                    <td>APP是否开启领取功能</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td>
                        <input <?php echo  $aRedPocketset['redPocketOpen']=='open'?'checked':'';?> type="checkbox" name="redPocketOpen" value="<?php echo $standard['redPocketOpen']?$standard['redPocketOpen']:'open';?>" > <br/>
                        开启时间：<input type="text" class=newYearBegin name="newYearBegin" value="<?php echo $aRedPocketset['newYearBeginTime']; ?>"/> <br/>
                        关闭时间：<input type="text" class=newYearEnd name="newYearEnd" value="<?php echo $aRedPocketset['newYearEndTime']; ?>"/> <br/>
                        <span style="color: red">
                            （上面填北京时间）
                            注：美东时间2021-02-10 12:00 - 2021-02-12 12:00 <br>
                            同：北京时间2021-02-11 00:00 - 2021-02-12 24:00</span>
                    </td>

                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>

        <table class="m_tab">
            <tr class="m_title">
                <td>等级</td>
                <td>输赢额度</td>
                <td>领取金额</td>
                <td>操作</td>
            </tr>
            <?php
            foreach ($data as $k => $v){
                ?>
                <tr>
                    <td><?php echo $v['level'];?></td>
                    <td><?php echo $v['left_interval'].' --- '.$v['right_interval'];?></td>
                    <!--<td><?php /*echo $v['giftGold'];*/?></td>-->
                    <!--<td> <input type="text" name="giftGold" id=giftGold_"<?php /*echo $v['id'];*/?>" value="<?php /*echo $v['giftGold'];*/?>"> </td>-->
                    <td> <input type="text" class=giftGold_<?php echo $v['id'];?> name="giftGold" value="<?php echo $v['giftGold'];?>"/> </td>

                    <td>
                        <input type="button" class="za_button btn_edit_<?php echo $v['id']?>" onclick="level_edit(<?php echo $v['id']?>,'<?php echo $uid?>','<?php echo $langx?>','<?php echo $loginname?>')" value="修改" />
                        <input type="button" value="删除" class="za_button" onclick="level_del('<?php echo $v['id'];?>')">
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="6">
                    <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)">
                    <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增">
                </td>
            </tr>
        </table>
        <br><br>
        <div id="adds" style="display: none;">
            <div class="connects">
                <form id="newsadd" method="post" action="">
                    <input type="hidden" name="type" value="add" />
                    <table class="m_tab">
                        <tr style="font-weight: bold;"><td>等级</td><td>输赢额区间</td><td>红包金额</td></tr>
                        <tr>
                            <td>
                                <select name="level" id="level_type">
                                    <?php
                                    for($i=1;$i<30;$i++){
                                        echo "<option value='$i'>".$i."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input class="inp1" type="text" id="left_interval" name="left_interval" value="">
                                <input class="inp1" type="text" id="right_interval" name="right_interval" value="">
                            </td>
                            <td><input class="inp1" type="text" id="giftGold" name="giftGold" value=""></td>
                        </tr>
                        <tr class=m_cen >
                            <td colspan="5">
                                <input type="button" value="新增" class="za_button btn2" onclick="level_add()">
                                <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                            </td>
                        </tr>

                    </table>

                </form>
            </div>
        </div>

    </div>
</div>
</body>
<script charset="utf-8" src="/js/agents/jquery.js" ></script>
<script type="text/javascript">

    var uid = '<?php echo $uid;?>';

    function level_del(id) {
        var type = 'delete';
        $.ajax({
            type:"POST",
            url:"newyear2021_hb_config.php",
            data:{
                id: id,
                uid: uid,
                type: type,
            },
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0){
                    alert('更新成功！');
                }else{
                    alert(ret.msg);
                }
                location.reload();
            }
        })
    }

    function level_add() {
        var type = 'add';
        var pams = {};
        pams.uid = uid;
        pams.type = type;
        pams.level = $("#level_type").val();
        pams.left_interval = $("#left_interval").val();
        pams.right_interval = $("#right_interval").val();
        pams.giftGold = $("#giftGold").val();
        $.ajax({
            type:"POST",
            url:"newyear2021_hb_config.php",
            data: pams,
            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0) {
                    alert("添加成功");
                }else{
                    alert(ret.msg);
                }
                location.reload();
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        })

    }


    function level_edit(id,uid,langx,loginname) {
        var type = 'edit';
        var giftGold = $(".giftGold_"+id).val();

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"newyear2021_hb_config.php",
            data:{
                id: id,
                uid: uid,
                langx: langx,
                loginname: loginname,
                type: type,
                giftGold: giftGold,
            },

            success:function(ret) {
                ret = JSON.parse(ret);
                if (ret.err==0) {
                    alert("修改成功");
                }else{
                    alert(ret.msg);
                }
                location.reload();
            }
        })
    }

</script>

</html>




