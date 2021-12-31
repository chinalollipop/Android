<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
include_once ("../include/redis.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
$page=$_REQUEST['page'];

$datatime=date('Y-m-d H:i:s');


// 根据条件查询报表（会员名称、投注时间、游戏名称）
$sPrefix = $agsxInitp['data_api_cagent'].$agsxInitp['data_api_user_prefix'].'_';
$agUsername = $_REQUEST['username'] ? $sPrefix.$_REQUEST['username'] : '';
$result_type = $_REQUEST['result_type'] ?$_REQUEST['result_type'] : ''; // 查询类型
if($result_type =='dzyx'){ // 电子游戏
    $game_type_chk = " AND `slottype` > 0 "  ;
}else if($result_type =='zrsx'){ // 真人娱乐
    $game_type_chk = "AND `playType` > 0 ";
}else{ // 全部
    $game_type_chk = "";
}

$sWhere = 1;
$agUsername != '' ? $sWhere .= " AND `username` = '$agUsername' " : '';
$date_s=$_REQUEST['date_start'];
$date_e=$_REQUEST['date_end'];
if ($date_s==''){
    $date_s=date('Y-m-d 00:00:00');
    $date_e=date('Y-m-d 23:59:59', time());
    $sWhere .=" and bettime between '{$date_s}' and '{$date_e}'";
}else{
    $sWhere .=" and bettime between '{$date_s}' and '{$date_e}'";
}

$projectid = $_REQUEST['projectid'];
$projectid !='' ? $sWhere .= " And `thirdprojectid` = '$projectid'" : '';



$agUsername = explode($sPrefix,$agUsername)[1];  // 赋值给搜索框去掉用户名前缀


$mysql="select username,bettime,gamename,thirdprojectid,gamecode,slottype,playType,amount,valid_money,profit,iswin from `".DBPREFIX."ag_projects` where $sWhere $game_type_chk order by `bettime` desc";
//print_r($mysql);
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
//if ($_REQUEST['username']!=''){
$mem_total_money=array();
while($row=@mysqli_fetch_assoc($result)){
    $mem_total_money['amount'] += $row['amount'];
    $mem_total_money['valid_money'] += $row['valid_money'];
    $mem_total_money['profit'] += $row['profit'];
}
//}

$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size";
$result = mysqli_query($dbLink, $mysql);
?>
<html>
<head>
    <title>AG视讯</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        #myFORM td{ padding: 3.5px 0 0  8px;}
        .mem_total_money td span{ color:red;}
        input.za_text {width: 142px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>真人视讯</dt>
    <dd>
        <form id="myFORM" action="" method=post name="myFORM" >
            <table >
                <tr>
                    <td>
                        <select name="result_type" id="result_type" onchange="self.myFORM.submit()">
                            <option value="">全部</option>
                            <option value="zrsx" <?php if($result_type=='zrsx'){echo 'selected';}?> >AG真人视讯</option>
                            <option value="dzyx" <?php if($result_type=='dzyx'){echo 'selected';}?> >AG电子游戏</option>
                        </select>
                        注单号：
                        <input type=TEXT name="projectid" size=10 value="<?php echo $projectid;?>" maxlength=20 class="za_text">
                        注单日期：<input type="text" name="date_start" id="date_start" value="<?php echo $date_s?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        至<input type="text" name="date_end" id="date_end" value="<?php echo $date_e?>"  onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" size=12 maxlength=10 class="za_text" >
                        会员帐号：<input type=TEXT name="username" size=10 value="<?php echo $agUsername;?>" maxlength=20 class="za_text">
                        <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">
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
        </form>
    </dd>
</dl>

<div class="main-ui">
    <table class="m_tab">

        <tr class="mem_total_money">
            <td colspan="10">
                总投注总额：<span><?php echo $mem_total_money['amount'];?></span>&nbsp;&nbsp;
                有效投注额：<span><?php echo $mem_total_money['valid_money'];?></span>&nbsp;&nbsp;
                <?php
                if($agUsername && $agUsername !=''){ // 查询账号时才显示
                    echo '会员结果总额：<span>'.$mem_total_money['profit'].'</span>';
                }
                ?>


            </td>
        </tr>

        <tr class="m_title">
            <td>投注时间</td>
            <td>会员账号</td>
            <td>游戏名称</td>
            <td>注单号</td>
            <td>局号</td>
            <td>下注</td>
            <td>发生金额</td>
            <td>有效投注</td>
            <td>投注盈利</td>
            <td>输/赢</td>
        </tr>
        <?php
        if($cou==0){ // 没有记录
            echo ' <tr ><td colspan="10">没有记录</td></tr>';
        }
        $agGames = $agGames+$agDianziGames;
        $slottype = array(1=>'普通',2=>'免費',8=>'Jackpot',9=>'Jackpot',10=>'紅利',11=>'紅利'); // 电子注单类型
        //$playTypes=$playType['baijiale'];
        while($row=@mysqli_fetch_assoc($result)){
            $aUsername=explode('_',$row['username'], 2);
            if($row['gamename'] == 'BAC') {     //百家乐
                $playTypes=$playType['baijiale'];
            }elseif ($row['gamename'] == 'DT'){     //龙虎
                $playTypes=$playType['longhu'];
            }elseif ($row['gamename'] == 'SHB'){    //骰宝
                $playTypes=$playType['toubao'];
            }elseif ($row['gamename'] == 'ROU'){    //轮盘
                $playTypes=$playType['lunpan'];
            }elseif ($row['gamename'] == 'ULPK'){   //终极德州扑克
                $playTypes=$playType['dezhou'];
            }elseif ($row['gamename'] == 'NN'){     //牛牛玩法
                $playTypes=$playType['niuniu'];
            }elseif ($row['gamename'] == 'BJ'){     //Blackjack
                $playTypes=$playType['Blackjack'];
            }elseif ($row['gamename'] == 'ZJH'){     //炸金花
                $playTypes=$playType['zhajinhua'];
            }elseif ($row['gamename'] == 'SG'){     //三公
                $playTypes=$playType['sangong'];
            }
            ?>
            <tr class="m_rig">
                <td><?php echo $row['bettime']?></td>
                <td><?php echo $aUsername[1]?></td>
                <td class="game_name"><?php echo $agGames[$row['gamename']]?></td>
                <td align="left"><?php echo $row['thirdprojectid']?></td>
                <td align="left"><?php echo $row['gamecode']?></td>
                <td data-slottype="<?php echo $row['slottype']?>" data-playtype="<?php echo $row['playType']?>"><?php echo $row['slottype']>0 ? $slottype[$row['slottype']] : $playTypes[$row['playType']];?></td>
                <td><?php echo number_format($row['amount'],2)?></td>
                <td class="yxtz" data-mon="<?php echo intval($row['valid_money'])?>"><?php echo number_format($row['valid_money'],2)?></td>
                <td class="tzyl" data-mon="<?php echo intval($row['profit'])?>"><?php echo number_format($row['profit'],2)?></td>
                <td><?php if($row['valid_money']==0 && $row['profit']==0){ echo '和局';}else{ echo ($row['iswin']=='0')? "输":"<font color='red'>赢</font>";} ?></td>

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