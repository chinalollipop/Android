<?php
session_start();
include("../include/address.mem.php");
require_once("../include/config.inc.php");
require("../include/define_function_list.inc.php");
require ("../include/traditional.zh-cn.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];

$seconds=$_REQUEST["seconds"];
$datatime=date('Y-m-d H:i:s');
if ($seconds==''){
    $seconds=180;
}
$page=$_REQUEST['page'];
if ($page==''){
    $page=0;
}

$config ['allgame']=array('3'=>'廣東快樂十分','47'=>'重慶快樂十分','2'=>'重慶時時彩','51'=>'北京賽車(PK10)','69'=>'香港六合彩','159'=>'江苏快三','168'=>'幸运飞艇','189'=>'极速赛车','207'=>'分分彩','407'=>'三分彩','507'=>'五分彩','607'=>'腾讯二分彩','222'=>'极速飞艇','304'=>'PC蛋蛋','384'=>'极速快三');
$config ['game']=array(
    3=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    47=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    2=>array('5'=>'第一球','6'=>'第二球','7'=>'第三球','8'=>'第四球','9'=>'第五球','10'=>'1-5大小','11'=>'1-5單雙','12'=>'總和大小','13'=>'總和單雙','14'=>'龍虎和','15'=>'前三','16'=>'中三','17'=>'後三'),
    51=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    69=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    159=>array('161'=>'猜必出','162'=>'大小单双','163'=>'通选-&gt;豹子','164'=>'三同号','165'=>'和值','166'=>'二不同号','167'=>'二同号复选','378'=>'猜必不出','379'=>'三不同','380'=>'二同号单选','381'=>'通选-&gt;顺子','382'=>'通选-&gt;对子','383'=>'通选-&gt;三不同'),
    168=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    189=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    207=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五',),
    407=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五',),
    507=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五',),
    607=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五',),
    222=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    304=>array('19'=>'第一球','20'=>'第二球','21'=>'第三球','22'=>'第四球','23'=>'第五球','24'=>'第六球','25'=>'第七球','26'=>'第八球','27'=>'1-8大小','28'=>'1-8單雙','29'=>'1-8尾數大小','30'=>'1-8合數單雙','31'=>'1-8方位','32'=>'1-8中發白','33'=>'總和大小','34'=>'總和單雙','35'=>'總和尾數大小','36'=>'龍虎','37'=>'任選二','38'=>'選二連直','39'=>'選二連組','40'=>'任選三','41'=>'選三前直','42'=>'選三前組','43'=>'任選四','44'=>'任選五'),
    384=>array('386'=>'猜必出','387'=>'大小单双','388'=>'通选-&gt;豹子','389'=>'三同号','390'=>'和值','391'=>'二不同号','392'=>'二同号复选','393'=>'猜必不出','394'=>'三不同','395'=>'二同号单选','396'=>'通选-&gt;顺子','397'=>'通选-&gt;对子','398'=>'通选-&gt;三不同')
);


// 根据条件查询报表（会员名称、投注时间、游戏名称）
$result_type = $_REQUEST['result_type'] ? $_REQUEST['result_type'] : ''; // 查询彩种类型
$agUsername = $_REQUEST['username'] ? $_REQUEST['username'] : '';
$date_start = $_REQUEST['bettime'] ? $_REQUEST['bettime'] : date('Y-m-d');
$date_start = strtotime($date_start);
$sWhere = 1;
$agUsername != '' ? $sWhere .= " AND `username` = '$agUsername' " : '';
//$date_start != '' ? $sWhere .= " AND `bet_time` >= '$date_start'" : '';

if($result_type){
    $game_code_chk = " AND `game_code` = '$result_type' "  ;
}else{
    $game_code_chk = ''  ;
}
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=strtotime(date('Y-m-d 00:00:00'));
    $date_e=strtotime(date('Y-m-d 23:59:59', time()));
    $sWhere .=" and bet_time between '{$date_s}' and '{$date_e}'";
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 23:59:59', time());
}else{
    $date_s=strtotime($date_s);
    $date_e=strtotime($date_e);
    $sWhere .=" and bet_time between '{$date_s}' and '{$date_e}'";
    $date_s=date('Y-m-d 00:00:00', $date_s);
    $date_e=date('Y-m-d 23:59:59', $date_e);
}

$id = $_REQUEST['id'];
$id !='' ? $sWhere .= " AND `id` = '$id'" : '';

$round = $_REQUEST['round'];
$round != '' ? $sWhere .= " AND `round` = '$round'" : '';

$result_count = $_REQUEST['result_count']; //是否结算
$result_count != '' ? $sWhere .= " AND `count` = '$result_count'" : '';

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$mysql="select id,total,user_win,username,round,game_code,type_code,drop_money,bet_time,valid_money,happy8,drop_content,xq_rate,xq_de_id,count_pay,`count`,user_rate,cancel from `".$database['cpDefault']['prefix']."bill` where $sWhere $game_code_chk order by `bet_time` desc";
//echo '<pre>';
//echo $mysql;
//echo '</pre>';
$result = mysqli_query($cpMasterDbLink,$mysql);
$cou=mysqli_num_rows($result);
//if ($agUsername!=''){
$mem_total_money=array();
while($row=@mysqli_fetch_assoc($result)){
    $mem_total_money['drop_money'] += $row['drop_money'];
    $mem_total_money['valid_money'] += $row['valid_money'];
    $mem_total_money['user_win'] += $row['user_win'];
}
//}
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size";
$result = mysqli_query($cpMasterDbLink, $mysql);
?>
    <html>
    <head>
        <title>体育彩票</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gbk">
        <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">
            #myFORM td{ padding: 3.5px 0 0  8px;}
            .mem_total_money td span{ color:red;}
            input.za_text {width: 142px;}
        </style>
    </head>
    <body >
    <form id="myFORM" action="" method=post name="myFORM" >
        <dl class="main-nav">
            <dt>体育彩票</dt>
            <dd>
                <table >
                    <tr>
                        <td>
                            <select name="result_type" id="result_type" onchange="self.myFORM.submit()">
                                <option value="">全部</option>
                                <option value="2" <?php if($result_type=='2'){echo 'selected';}?> >欢乐生肖</option>
                                <option value="3" <?php if($result_type=='3'){echo 'selected';}?> >广东快乐十分</option>
                                <option value="51" <?php if($result_type=='51'){echo 'selected';}?> >北京PK10</option>
                                <option value="69" <?php if($result_type=='69'){echo 'selected';}?> >香港彩</option>
                                <option value="304" <?php if($result_type=='304'){echo 'selected';}?> >PC蛋蛋</option>
                                <option value="168" <?php if($result_type=='168'){echo 'selected';}?> >幸运飞艇</option>
                                <option value="159" <?php if($result_type=='159'){echo 'selected';}?> >江苏快三</option>
                                <option value="47" <?php if($result_type=='47'){echo 'selected';}?> >幸运农场</option>
                                <option value="207" <?php if($result_type=='207'){echo 'selected';}?> >分分彩</option>
                                <option value="407" <?php if($result_type=='407'){echo 'selected';}?> >三分彩</option>
                                <option value="507" <?php if($result_type=='507'){echo 'selected';}?> >五分彩</option>
                                <option value="607" <?php if($result_type=='607'){echo 'selected';}?> >腾讯二分彩</option>
                                <option value="222" <?php if($result_type=='222'){echo 'selected';}?> >极速飞艇</option>
                                <option value="189" <?php if($result_type=='189'){echo 'selected';}?> >极速赛车</option>
                                <option value="384" <?php if($result_type=='384'){echo 'selected';}?> >极速快三</option>
                            </select>
                            期数：<input type=TEXT name="round" size=10 value="<?php echo $round;?>" maxlength=20>
                            注单号：<input type=TEXT name="id" size=10 value="<?php echo $id;?>" maxlength=20 class="za_text">
                            注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                            至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                            会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $agUsername;?>" maxlength=20 >
                            <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">

                            <?php echo $Rep_Bet_State?>
                            <select name='result_count' id="result_count" onChange="self.myFORM.submit()">
                                <option value=""><?php echo $Rel_All?></option>
                                <option value="1"><?php echo $Rep_Results?></option>
                                <option value="0"><?php echo $Rep_No_Results?></option>
                            </select>

                            共<?php echo $cou?>条
                            <select name='page' onChange="self.myFORM.submit()">
                                <?php
                                if ($page_count==0){
                                    $page_count=1;
                                }
                                for($i=0;$i<$page_count;$i++){
                                    if ($i==$page){
                                        echo "<option selected value='$i'>".($i+1)."</option>";
                                    }else{
                                        echo "<option value='$i'>".($i+1)."</option>";
                                    }
                                }
                                ?>
                            </select> 共<?php echo $page_count?> 页
                        </td>
                    </tr>
                </table>
            </dd>
        </dl>
    </form>

    <div class="main-ui">
        <table class="m_tab">

            <tr class="mem_total_money">
                <td colspan="10">
                    总投注总额：<span><?php echo ($mem_total_money['drop_money']>0)?$mem_total_money['drop_money']:'0';?></span>&nbsp;&nbsp;
                    有效投注额：<span><?php echo ($mem_total_money['valid_money']>0)?$mem_total_money['valid_money']:'0';?></span>&nbsp;&nbsp;
                    <?php
                    if($agUsername && $agUsername !=''){
                        echo ' 会员结果总额：<span>'.round($mem_total_money['user_win'],1).'</span>';
                    }
                    ?>

                </td>
            </tr>

            <tr class="m_title">
                <td align="center">注单号</td>
                <td align="center">用户名称</td>
                <td align="center">投注时间</td>
                <td align="center">游戏名称</td>
                <td align="center">期数</td>
                <td align="center">投注内容</td>
                <td align="center">投注金额</td>
                <td align="center">中奖金额</td>
            </tr>
            <?php
            $allgame = $config ['gametype'];
            while($row=@mysqli_fetch_assoc($result)){
                $row['user_win']=number_format($row['user_win'],2);
                ?>
                <tr class="m_rig" onmouseover=sbar(this) onmouseout=cbar(this)>
                    <td align="center"><?php echo $row['id']?></td>
                    <td align="center"><?php echo $row['username']?></td>
                    <td align="center"><?php echo date('Y-m-d H:i:s',$row['bet_time'])?></td>
                    <td align="center"><?php echo $config ['allgame'][$row['game_code']]?></td>
                    <td align="center"><?php echo $row['round']?></td>
                    <td align="center" class="bet_content"><?php
                        if($row['game_code']==69){ // 香港六合彩
                            $types = $config ['lottery_menu'];
                            $lhcContent = getxq($cpMasterDbLink,$row,$types);
                            echo $lhcContent;
                            echo '</span> @ <span style="color:red">'.$row['user_rate'].'</span>';

                        }else{
                            $typecode= $config['ten_typecode']+$config['ssc_typecode']+$config['saiche_typecode']+$config['k3_typecode']+$config['xyft_typecode']+$config['jsft_typecode']+$config['jssaiche_typecode']+$config['jsSSC_typecode']+$config['sfcSSC_typecode']+$config['wfcSSC_typecode']+$config['efcSSC_typecode']+$config['pcdd_typecode']+$config['jsk3_typecode'];
                            echo get($row['type_code'],$row['happy8'],$row['drop_content'],$row['game_code'],$typecode);
                            echo '</span> @ <span style="color:red">'.$row['user_rate'].'</span>';
                            if(in_array($row['type_code'],array(2032,2035,2038,2039)) && $row['game_code']!=69){
                                echo '<br/>复式『 '.($row['total']/$row['drop_money']).' 組 』<br/>';
                                echo ''.($row['drop_content']).'';
                            }
                        }
                        ?></td>
                    <td align="center"><?php echo $row['drop_money']?></td>
                    <td align="center" <?php if ($row['user_win']<=0){ ?>style="color: red;"<?php } ?>>
                        <?php
                            echo $row['cancel']==1?'取消':$row['user_win'];
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    </body>
    <script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
    <script type="text/javascript">
        function sbar(st){
            st.style.backgroundColor='#BFDFFF';
        }
        function cbar(st){
            st.style.backgroundColor='';
        }

    </script>
    </html>
<?php

function get($type_code,$happy8,$drop_content,$game_code=2,$typecode){

    $ssc_code=array(2,45,46);
    $ten_code=array(3,47,48);

    if(in_array($game_code,$ssc_code)){
        if($type_code<=1004){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1005 && $type_code<=1008){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1009 && $type_code<=1015){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==207){
        if($type_code<=1104){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1105 && $type_code<=1108){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1109 && $type_code<=1115){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==407){ // 三分彩
        if($type_code<=1204){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1205 && $type_code<=1208){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1209 && $type_code<=1215){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==507){ // 五分彩
        if($type_code<=1304){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1305 && $type_code<=1308){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1309 && $type_code<=1315){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==607){ // 腾讯二分彩
        if($type_code<=1404){
            return $typecode[$type_code][1].'『'.$drop_content.'』';
        }
        elseif($type_code>=1405 && $type_code<=1408){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
        elseif($type_code>=1409 && $type_code<=1415){
            return $typecode[$type_code][2];
        }
        else{
            return $typecode[$type_code][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==51){
        if($type_code<=3010 or $type_code==3021){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3011 && $type_code<=3020){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==168){
        if($type_code<=3110 or $type_code==3121){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3111 && $type_code<=3120){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==222){
        if($type_code<=3310 or $type_code==3321){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3311 && $type_code<=3320){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }elseif ($game_code==159){
        if($type_code <= 1505){
            $name="猜必出";
        }else if($type_code == 1506 || $type_code == 1507 || $type_code == 1556  || $type_code == 1557 ){
            $name="大小单双";
        }else if($type_code == 1508 || $type_code == 1558 || $type_code == 1559 || $type_code == 1560 ){
            $name="通选";
        }else if( $type_code >= 1509 && $type_code <= 1514 ){
            $name="三同号";
        }else if( ($type_code >= 1515 && $type_code <= 1528) ||  $type_code == 1611 ||  $type_code == 1612 ) {
            $name="和值";
        }else if($type_code > 1528 && $type_code <= 1543 ){
            $name="二不同号";
        }else if($type_code > 1543 && $type_code <= 1549 ){
            $name="二同号复选";
        }else if($type_code > 1549 && $type_code <= 1555 ){
            $name="猜必不出";
        }else if($type_code > 1560 && $type_code <= 1580 ){
            $name="三不同";
        }else if($type_code > 1580 && $type_code <= 1610 ){
            $name="二同号单选";
        }


        echo $name."『 ".$typecode[$type_code][1]."』";
    }elseif ($game_code==384){ // 极速快三
        if($type_code <= 2505){
            $name="猜必出";
        }else if($type_code == 2506 || $type_code == 2507 || $type_code == 2556  || $type_code == 2557 ){
            $name="大小单双";
        }else if($type_code == 2508 || $type_code == 2558 || $type_code == 2559 || $type_code == 2560 ){
            $name="通选";
        }else if( $type_code >= 2509 && $type_code <= 2514 ){
            $name="三同号";
        }else if( ($type_code >= 2515 && $type_code <= 2528) ||  $type_code == 2611 ||  $type_code == 2612 ) {
            $name="和值";
        }else if($type_code > 2528 && $type_code <= 2543 ){
            $name="二不同号";
        }else if($type_code > 2543 && $type_code <= 2549 ){
            $name="二同号复选";
        }else if($type_code > 2549 && $type_code <= 2555 ){
            $name="猜必不出";
        }else if($type_code > 2560 && $type_code <= 2580 ){
            $name="三不同";
        }else if($type_code > 2580 && $type_code <= 2610 ){
            $name="二同号单选";
        }


        echo $name."『 ".$typecode[$type_code][1]."』";
    }elseif ($game_code==304){
        return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
    }elseif ($game_code==189){
        if($type_code<=3210 or $type_code==3221){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=3211 && $type_code<=3220){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }
    }else{
        if ($type_code<=2008){
            return $typecode[$type_code][1]."『 ".$drop_content."』";
        }else if($type_code>=2009 && $type_code<=2023){
            return $typecode[$happy8][1]."『 ".$typecode[$type_code][2]."』";
        }else{
            return $typecode[$type_code][2];
        }
    }
}


function getxq($cpMasterDbLink,$row,$types){
    $sql="select id, classid,class1,class2,class3,rate,locked from gxfcy_xq_defrate ";
    $sql.=" where id>0 order by id asc";
    $program_char = "utf8" ;
    mysqli_set_charset( $cpMasterDbLink , $program_char );

    $result = mysqli_query($cpMasterDbLink ,$sql);
    $cou=mysqli_num_rows($result);
    if ($cou<0){
        exit(array(['-1'=>'error']));
    }
    $XQgameinfo = array();
    while($XQrow=@mysqli_fetch_assoc($result)){
        $XQgameinfo[$XQrow['id']]=$XQrow;
    }

    if($row['happy8']==12){
        $_tmps=explode(",",$row['drop_content']);
        $_tmpsrate=explode(",",$row['xq_rate']);
        $res= "过关:<br>";
        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class2']."『 ".$XQgameinfo[$v]['class3']."』 @ ".$_tmpsrate[$k]."<br>";
        }
    }else if($row['happy8']==13){
        $res= $XQgameinfo[$row['xq_de_id']]['class1']."『 ".$XQgameinfo[$row['xq_de_id']]['class2']."』";
        $res.= "<BR>復式『 ".$row['count_pay']." 組 』";
        $_tmps=explode(",",$row['drop_content']);
        $res.= "<BR>".implode("、", $_tmps)."<br>";
    }else if($row['happy8']>=17 and $row['happy8']<=21){
        $types=$types;
        $_tmps=explode(",",$row['drop_content']);
        $res= "合肖『 ".$types[$row['happy8']]."』<br>";
        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class3']."、";
        }
    }else if($row['happy8']>=27 and $row['happy8']<=47){
        $_tmps=explode(",",$row['drop_content']);
        $res= $XQgameinfo[$_tmps[0]]['class1']."『 ".$XQgameinfo[$_tmps[0]]['class2']."』";
        $res.= "<BR>復式『 ".$row['count_pay']." 組 』<BR>";

        foreach ($_tmps as $k=>$v){
            $res.= $XQgameinfo[$v]['class3']."、";
        }
    }else{
        $res= $XQgameinfo[$row['xq_de_id']]['class2']."『 ".$XQgameinfo[$row['xq_de_id']]['class3']."』";
    }
    return $res;
}

?>