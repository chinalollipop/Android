<?php
/**
 * 6668的2020新年活动几率配置
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
$type = isset($_REQUEST["type"]) && $_REQUEST["type"] ? $_REQUEST["type"] : '';
$level=$_REQUEST['level'];

$redPocketOpen = isset($_REQUEST["redPocketOpen"]) && $_REQUEST["redPocketOpen"] ? $_REQUEST["redPocketOpen"] : ''; // 是否开启签到
$att_level = isset($_REQUEST["att_level"]) && $_REQUEST["att_level"] ? $_REQUEST["att_level"] : ''; // 签到层级
$setdata = isset($_REQUEST["setdata"]) && $_REQUEST["setdata"] ? $_REQUEST["setdata"] : '';

$redisObj = new Ciredis();

if($type =='redPocket'){ // APP红包开关
    $redPocketset = array(
        'redPocketOpen'=>$redPocketOpen,
    );
    $redisObj->setOne('red_pocket_open_6668_2020',json_encode($redPocketset)) ;
}
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open_6668_2020'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;
if(!$aRedPocketset){
    $aRedPocketset['redPocketOpen'] = '';
}


// 签到天数对应领取金额
$data_config = array();

if($type =='probability'){ // 设置签到天数，金额

    $setdata = json_decode(stripslashes($setdata),true); // 处理数据
//    var_dump($setdata);

    $sum_probability=0; // 几率总和

    //$ids = implode(',',array_values($att_level));
    $ids = $att_level;
    $sql = "UPDATE ".DBPREFIX."newyear_2020_6668_config SET ";
    $e_sql .= "END,";
    $d_sql .= "WinLossCredit = CASE id ";
    $m_sql .= "giftGold = CASE id ";
    $p_sql .= "probability = CASE id ";
    foreach ($setdata as $id => $data){
        $d_sql .= sprintf("WHEN %d THEN %d ", $data['id'], $data['WinLossCredit']);
        $m_sql .= sprintf("WHEN %d THEN %s ", $data['id'], $data['giftGold']);
        $p_sql .= sprintf("WHEN %d THEN %s ", $data['id'], $data['probability']);
        $sum_probability=bcadd($sum_probability,$data['probability'],2); //bcadd — 将两个高精度数字相加
    }
    if($sum_probability !=1){
        $status = '502.1';
        $describe = '几率有误，几率综合不能大于或小于1，请重新填写几率重新修改';
        original_phone_request_response($status,$describe);
    }

    $sql .= $d_sql.$e_sql.$m_sql.$e_sql.$p_sql   ;
    $sql .= "END where level IN ($ids)";
//    echo $sql;
   $res = mysqli_query($dbMasterLink,$sql);

   if($res){
       getConfigFromDb();

       $status = '200';
       $describe = '设置成功';
       original_phone_request_response($status,$describe);
   }else{
       $status = '502.2';
       $describe = '数据操作失败';
       original_phone_request_response($status,$describe);
   }

}

$data_config = $redisObj->getSimpleOne('newyear_2020_6668_config');
$data_config = json_decode($data_config,true) ;
if($data_config ==''){ // 没有redis 数据
    $data_config = getConfigFromDb();
}

// 从数据库获取
function getConfigFromDb(){
    global $dbMasterLink,$redisObj, $_REQUEST ;

    $data_config = array();
    $chkdata = array();
    $sql = "select *  from ".DBPREFIX."newyear_2020_6668_config";
//    echo $sql;
    $res = mysqli_query($dbMasterLink,$sql);
    while ($row = mysqli_fetch_assoc($res)){
        $data_config[] = $row;
    }
    foreach($data_config as $k=>$v) { // 按等级 level 组合数组
        $chkdata[$v["level"]][] = $v;
    }

//    print_r($chkdata);die;

    $redisObj->setOne('newyear_2020_6668_config',json_encode($chkdata)) ;
    return $chkdata;

}

// var_dump($data_config_after);

?>
<html>
<head>
    <title>6668的2020新年活动几率配置</title>
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
    <dt style="width: 200px;">6668的2020新年活动几率配置</dt>
    <dd>

    </dd>
</dl>

<div class="main-ui ">
    <div class="width_1300">

        <!-- 开关 -->
        <form action="newyear2020_6668_config.php?uid=<?php echo $uid?>&type=redPocket&lv=<?php echo $level?>"  method="post">
            <table class="m_tab">
                <tr class="m_title">
                    <td>APP是否开启领取功能</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td> <input <?php echo  $aRedPocketset['redPocketOpen']=='open'?'checked':'';?> type="checkbox" name="redPocketOpen" value="<?php echo $standard['redPocketOpen']?$standard['redPocketOpen']:'open';?>" > </td>
                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>

        <table class="m_tab">
            <tr class="m_title">
                <td>等级</td>
                <td>输赢额度</td>
                <td>领取金额</td>
                <td>几率(每个等级几率相加为1)</td>
                <td>操作</td>
            </tr>
            <?php
            foreach ($data_config as $key=>$value){

                for($i=0;$i<count($value);$i++){
                ?>
                    <tr class="m_title tr_attendance_<?php echo $value[$i]['level'];?>">

                        <?php if($i==0 || $i ==5 || $i ==10){
                        ?>
<!--                            <td rowspan="3"> <input class="za_text" type="text" name="level" value="--><?php //echo $value[$i]['level'];?><!--" > </td>-->
                            <td rowspan="3"> <?php echo $value[$i]['level'];?> </td>
                        <?php
                        }
                        ?>

                    <td> <?php echo $value[$i]['WinLossCredit'];?><input class="za_text" type="hidden" name="WinLossCredit" value="<?php echo $value[$i]['WinLossCredit'];?>" data-id="<?php echo $value[$i]['id'];?>"> </td>
                    <td> <input class="za_text" type="text" name="giftGold" value="<?php echo $value[$i]['giftGold'];?>" data-id="<?php echo $value[$i]['id'];?>"> </td>
                    <td> <input class="za_text" type="text" name="probability" value="<?php echo $value[$i]['probability'];?>" > </td>
                        <?php if($i==0 || $i ==5 || $i ==10){
                            ?>
                            <td rowspan="3"> <input type="submit" class="sign_setting_btn tj_btn za_button" value="设置" data-signlevel="<?php echo $value[$i]['level'];?>" /> </td></td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

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
                var wl_val = $(this).find('input[name="WinLossCredit"]').val();
                var id_val = $(this).find('input[name="giftGold"]').attr('data-id');
                var gg_val = $(this).find('input[name="giftGold"]').val();
                var p_val = $(this).find('input[name="probability"]').val();
                setdata.push(
                    {'id':id_val ,'WinLossCredit':wl_val ,'giftGold':gg_val,'probability':p_val}
                    );
            })
             //console.log(setdata)
            $.ajax({
                type: 'POST',
                url: 'newyear2020_6668_config.php',
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




