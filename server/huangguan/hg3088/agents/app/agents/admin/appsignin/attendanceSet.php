<?php
/**
 * 棋牌管理-乐游棋牌
 * Date: 2018/11/7
 */
session_start();
require_once '../../include/config.inc.php';
require_once "../../include/address.mem.php";

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$type = isset($_REQUEST["type"]) && $_REQUEST["type"] ? $_REQUEST["type"] : '';
$level=$_REQUEST['level'];

$standardMoney = isset($_REQUEST["standardMoney"]) && $_REQUEST["standardMoney"] ? $_REQUEST["standardMoney"] : ''; // 签到达标金额
$maxstandardMoney = isset($_REQUEST["maxstandardMoney"]) && $_REQUEST["maxstandardMoney"] ? $_REQUEST["maxstandardMoney"] : ''; // 签到最大领取金额
$standardOpen = isset($_REQUEST["standardOpen"]) && $_REQUEST["standardOpen"] ? $_REQUEST["standardOpen"] : ''; // 是否开启签到
$att_level = isset($_REQUEST["att_level"]) && $_REQUEST["att_level"] ? $_REQUEST["att_level"] : ''; // 签到层级
$setdata = isset($_REQUEST["setdata"]) && $_REQUEST["setdata"] ? $_REQUEST["setdata"] : '';
//$attendanceday = isset($_REQUEST["attendanceday"]) && $_REQUEST["attendanceday"] ? $_REQUEST["attendanceday"] : ''; // 签到天数
//$attendancemoney = isset($_REQUEST["attendancemoney"]) && $_REQUEST["attendancemoney"] ? $_REQUEST["attendancemoney"] : ''; // 领取金额
//$attendanceprobability = isset($_REQUEST["attendanceprobability"]) && $_REQUEST["attendanceprobability"] ? $_REQUEST["attendanceprobability"] : ''; // 几率


$redisObj = new Ciredis();

if($type =='standard'){ // 签到金额设置
    $standardset = array(
         'standardmoney'=>$standardMoney,
         'maxstandardMoney'=>$maxstandardMoney,
         'standardswitch'=>$standardOpen,
    );
    if($standardMoney<100){
        echo "<script>alert('签到达标金额不能小于100');</script>";
    }else{
        $redisObj->setOne('attendance_set_standard',json_encode($standardset)) ;
    }

}

// 签到达标金额
$standard = $redisObj->getSimpleOne('attendance_set_standard'); // 取redis 设置的值
$standard = json_decode($standard,true) ;
if(!$standard){
    $standard['standardmoney'] = 1000;
    $standard['standardswitch'] = '';
    $standard['maxstandardMoney'] = 888;
}

// 签到天数对应领取金额
$attendancedata_after = array();

if($type =='probability'){ // 设置签到天数，金额

    $setdata = json_decode(stripslashes($setdata),true); // 处理数据
   // var_dump($setdata);

    $sum_probability=0; // 几率总和

    //$ids = implode(',',array_values($att_level));
    $ids = $att_level;
    $sql = "UPDATE ".DBPREFIX."web_attendanceSetting SET ";
    $e_sql .= "END,";
    $d_sql .= "attendanceDay = CASE id ";
    $m_sql .= "attendanceMoney = CASE id ";
    $p_sql .= "probability = CASE id ";
    foreach ($setdata as $id => $data){
        $d_sql .= sprintf("WHEN %d THEN %d ", $data['id'], $data['attendanceday']);
        $m_sql .= sprintf("WHEN %d THEN %s ", $data['id'], $data['attendancemoney']);
        $p_sql .= sprintf("WHEN %d THEN %s ", $data['id'], $data['attendanceprobability']);
        $sum_probability+=$data['attendanceprobability'];
    }
    if($sum_probability !=1){
        $status = '502.1';
        $describe = '几率有误，几率综合不能大于或小于1，请重新填写几率重新修改';
        original_phone_request_response($status,$describe);
    }

    $sql .= $d_sql.$e_sql.$m_sql.$e_sql.$p_sql   ;
    $sql .= "END where level IN ($ids)";
   // echo $sql;die;
   $res = mysqli_query($dbMasterLink,$sql);
   if($res){
       getAttendanceFromDb();

       $status = '200';
       $describe = '设置成功';
       original_phone_request_response($status,$describe);
   }else{
       $status = '502.2';
       $describe = '数据操作失败';
       original_phone_request_response($status,$describe);
   }

}

$attendancedata_after = $redisObj->getSimpleOne('attendance_set_probability');
$attendancedata_after = json_decode($attendancedata_after,true) ;
if($attendancedata_after ==''){ // 没有redis 数据
    $attendancedata_after = getAttendanceFromDb();
}

// 从数据库获取
function getAttendanceFromDb(){
    global $dbMasterLink,$redisObj ;
    $attendancedata = array();
    $chkdata = array();
    $sql = "select id,level,attendanceDay,attendanceMoney,probability  from ".DBPREFIX."web_attendanceSetting";
    //echo $sql;
    $res = mysqli_query($dbMasterLink,$sql);
    while ($row = mysqli_fetch_assoc($res)){
        $attendancedata[] = $row;
    }
    foreach($attendancedata as $k=>$v) { // 按等级 level 组合数组
        $chkdata[$v["level"]][] = $v;
    }
    $redisObj->setOne('attendance_set_probability',json_encode($chkdata)) ;
    return $chkdata;

}

// var_dump($attendancedata_after);

?>
<html>
<head>
    <title>APP签到设置</title>
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
    <dt>APP签到设置</dt>
    <dd>

    </dd>
</dl>

<div class="main-ui ">
    <div class="width_1300">
        <!-- 设置签到达标金额 -->
        <form action="attendanceSet.php?uid=<?php echo $uid?>&type=standard&lv=<?php echo $level?>"  method="post">
            <table class="m_tab">
                <tr class="m_title">
                    <td>签到存款达标金额(默认1000)</td>
                    <td>最高领取金额</td>
                    <td>前台是否开启签到</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td> <input class="za_text" type="number" name="standardMoney" value="<?php echo  $standard['standardmoney'];?>" > </td>
                    <td> <input class="za_text" type="number" name="maxstandardMoney" value="<?php echo  $standard['maxstandardMoney'];?>" > </td>
                    <td> <input <?php echo  $standard['standardswitch']=='open'?'checked':'';?> type="checkbox" name="standardOpen" value="<?php echo $standard['standardswitch']?$standard['standardswitch']:'open';?>" > </td>
                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>

       <!-- <form action="attendanceSet.php?uid=<?php /*echo $uid*/?>&type=probability&lv=<?php /*echo $level*/?>"  method="post" name="form1" >-->
            <table class="m_tab">
                <tr class="m_title">
                    <td>签到天数</td>
                    <td>领取金额</td>
                    <td>几率(每个等级几率相加为1)</td>
                    <td>操作</td>
                </tr>
                <?php
                foreach ($attendancedata_after as $key=>$value){
                ?>

                        <?php
                        for($i=0;$i<count($value);$i++){
                        ?>
                            <tr class="m_title tr_attendance_<?php echo $value[$i]['level'];?>">

                                <?php if($i==0 || $i ==5 || $i ==10){
                                ?>
                                    <td rowspan="5"> <input class="za_text" type="text" name="attendanceday" value="<?php echo $value[$i]['attendanceDay'];?>" > </td>
                                <?php
                                }
                                ?>

                            <td> <input class="za_text" type="text" name="attendancemoney" value="<?php echo $value[$i]['attendanceMoney'];?>" data-id="<?php echo $value[$i]['id'];?>"> </td>
                            <td> <input class="za_text" type="text" name="attendanceprobability" value="<?php echo $value[$i]['probability'];?>" > </td>
                                <?php if($i==0 || $i ==5 || $i ==10){
                                    ?>
                                    <td rowspan="5"> <input type="submit" class="sign_setting_btn tj_btn za_button" value="设置" data-signlevel="<?php echo $value[$i]['level'];?>" /> </td></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                            }
                        ?>


                    <?php
                }
                ?>
           <!-- <tr class="m_title">
                <td colspan="3"> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
            </tr>-->
            </table>
       <!-- </form>-->

    </div>
</div>
</body>
<script charset="utf-8" src="/js/agents/jquery.js" ></script>
<script type="text/javascript">

    setSignData();
    // 设置签到基本配置
    function setSignData() {
        $('.sign_setting_btn').on('click',function () {
            var level = $(this).attr('data-signlevel');
            var setdata = [];
            $('.tr_attendance_'+level).each(function () { // 金额
                var d_val = $('.tr_attendance_'+level).eq(0).find('input[name="attendanceday"]').val();
                var m_val = $(this).find('input[name="attendancemoney"]').val();
                var id_val = $(this).find('input[name="attendancemoney"]').attr('data-id');
                var p_val = $(this).find('input[name="attendanceprobability"]').val();
                setdata.push(
                    {'id':id_val ,'attendanceday':d_val ,'attendancemoney':m_val ,'attendanceprobability':p_val}
                    );
            })
             //console.log(setdata)
            $.ajax({
                type: 'POST',
                url: 'attendanceSet.php',
                data:{
                    type: 'probability',
                    att_level: level,
                    setdata: JSON.stringify(setdata),
                },
                dataType: 'json',
                success:function(res){
                    alert(res.describe);
                    window.location.reload();
                },
                error:function () {
                    alert('网络错误')
                }
            });
        })

    }
</script>

</html>




