<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
// $m_date=date('Y-m-d h:i:s');
$m_date=date('Y-m-d');
//echo $m_date ;
// 默认查询当天的数据
$date_start = $_REQUEST['date_start'];
$date_end = $_REQUEST['date_end'];
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$username=$_REQUEST['username'];
$thistype = $_REQUEST['thistype'] ;
$page_num = $_REQUEST['page'] ; // 当前第几页
$pagesize = 15 ; // 每页显示条数

if(!$page_num){
    $page_num = 1 ; // 没有值得情况下赋值 1
}

// 类型，T 提款(默认)，S 存款, Q 额度转换记录，C 汇款信息回查
if($thistype =='T'){
    $type = '提款' ;
    $addIcon = '-' ;
}else if($thistype =='S'){
    $type = '存款' ;
    $addIcon = '' ;
}else if($thistype =='R'){
    $type = '返水' ;
    $addIcon = '' ;
}else if($thistype =='Q'){
    $type = '额度转换记录';
}else if($thistype =='C'){
    $type = '汇款信息回查';
}
if($date_start=='' && $date_end ==''){ // 时间为空设定默认今天
    $date_start=$date_end=$m_date;
}
if($date_start < date("Y-m-d", strtotime("-1 month"))) {  //最多查询一月
    $date_start = date("Y-m-d", strtotime("-1 month"));
}

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
?>
<html>
<head>
    <title>History</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <link rel="stylesheet" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>

        #MFT #box { width:650px;}
        #MFT .news { white-space: normal!important; color:#300; text-align:left; padding:2px 4px;}
        .w100{ width: 100% !important;}
        .btn3, .btn4{ width: auto;}

    </style>
</HEAD>
<BODY id="MFT" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;" class="record">
<div class="mv ui-main">
    <div class="mc-con3">

        <div class=" mc-rtct" id="div_Bg">
            <div class="mc-ct" id="div_Main">

                <ul class="fd_mn_mn">
                    <li class="depositTab2  " data-action="active_S">
                        <h4><a href="/app/member/onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $username?>&thistype=S&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>" target="body" >存款记录</a></h4>
                    </li>
                    <li class="depositTab2 " data-action="active_R">
                        <h4><a href="/app/member/onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $username?>&thistype=R&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>" target="body" >返水记录</a></h4>
                    </li>
                    <li class="depositTab2 " data-action="active_T">
                        <h4><a href="/app/member/onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $username?>&thistype=T&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>" target="body" >取款记录</a></h4>
                    </li>
                    <li class="depositTab2 " data-action="active_Q">
                        <h4><a href="/app/member/onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $username?>&thistype=Q&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>" target="body" >额度转换记录</a></h4>
                    </li>
                    <li class="depositTab2 " data-action="active_C">
                        <h4><a href="/app/member/onlinepay/record.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&username=<?php echo $username?>&thistype=C&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>" target="body" >汇款信息回查</a></h4>
                    </li>

                    <div class="clear"></div>
                </ul>
                <div class="fd-box3 mc-mn-bg" id="div_Detail">
                    <div class="fm w100 mgbt20">
                        <form method="post" id="qryform" name="qryform" action="">
                            <input type="hidden" name="from_check" value="check"/>
                            <div class="label w100">
                                <span class="fltlft mgrt5">从：</span>
                                <div class="dateSlt mgrt10" id="div_DateFrom">
                                    <span class="cld"></span>
                                    <input type="text" value="" name="date_start" id="date_start" readonly="" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})">

                                </div>
                                <span class="fltlft mgrt5">到 </span>
                                <div class="dateSlt mgrt10" id="div_DateTo"><span class="cld"></span>
                                    <input type="text" value="" name="date_end" id="date_end" readonly="" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})">
                                </div>
                                <a class="btn3 btn4 fltlft wsnwp" href="javascript:;" id="a_Query" style="border-radius: 20px;" onclick="javascript:qryform.submit();">
                                    <span class="icon_enter"></span>
                                    <span>查询</span>
                                </a>
                            </div>
<!--                            <input name="uid" id="uid" type="hidden" value="--><?php //echo $uid;?><!--">-->
<!--                            <input name="langx" id="langx" type="hidden" value="zh-cn">-->
<!--                            <input name="thistype" id="thistype" type="hidden" value="--><?php //echo $thistype;?><!--">-->
<!--                            <input name="username" id="username" type="hidden" value="--><?php //echo $username;?><!--">-->
                        </form>
                    </div>
                    <div id="div_Query">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mc-table tactr fwbd mgbt20">
                            <tbody>
                            <tr>
                                <th>会员</th>
                                <th>姓名</th>
                                <th>日期</th>
                                <th>使用币值</th>
                                <th>金额</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                            <?php

                            $sWhere=1;
                            $username ? $sWhere.=" and UserName='{$username}'" : '';
                            $thistype ? $sWhere.=" and Type='{$thistype}'" : '';
                            //$date_start ? $sWhere .= " and Date > '{$date_start} 00:00:00'": '';
                            //$date_end ? $sWhere .=" and Date < '{$date_end} 23:59:59'" : '';
                            $date_start ? $sWhere .= " and addDate between '{$date_start}' and '{$date_end}'": ''; // 查询入库时间，与后台一致
                            if ($thistype=='S'||$thistype=='R'||$thistype=='Q'){
                                $mysql="select ID,Checked,Gold,Type,UserName,CurType,AddDate,Date,AuditDate,Name,User,Order_Code,reason,notes,`From`,`To` from ".DBPREFIX."web_sys800_data where $sWhere order by id desc limit ". ($page_num-1)*$pagesize .", $pagesize";
                            }else{
                                $mysql="select ID,Checked,Gold,Type,UserName,CurType,AddDate,Date,AuditDate,Name,User,Bank_Account,reason,notes,`From`,`To` from ".DBPREFIX."web_sys800_data where $sWhere order by id desc limit ". ($page_num-1)*$pagesize .", $pagesize";
                            }
                            $pagesql = "select count(*) as amount,ID,Checked,Gold,Type,UserName,CurType,AddDate,Date,AuditDate,Name,User from ".DBPREFIX."web_sys800_data where $sWhere order by id desc";
                             //echo $mysql ;echo '<br>';
                            //echo $pagesql ;
                            $pageresult = mysqli_query($dbLink,$pagesql);
                           // $pagerow = mysqli_fetch_row($pageresult);
                            $pagerow = mysqli_fetch_array($pageresult);
                            $amount = $pagerow['amount']; // 计算总数
                            // echo $amount ;
                            $result = mysqli_query($dbLink,$mysql);
                            while ($myrow=mysqli_fetch_assoc($result)){
                                if ($myrow['Checked']=='0'){ // 未审核
                                    $checked='审核中'; // 新申请的单子不管后台是否审核直接显示  已审核（配合运营）2018-lincoin
                                }else if ($myrow['Checked']=='1'){ // 成功
                                    $checked='成功';
                                }else if ($myrow['Checked']=='-1'){ // 失败
                                    $checked='失败';
                                }
                                $gold+=$myrow['Gold'];
                                ?>
                                <tr class="b_rig">
                                    <td><?php echo $myrow['UserName']?></td>
                                    <td class="record_name"><?php echo returnRealName($myrow['Name'])?></td>
                                    <td ><?php echo $myrow['AuditDate']?></td>
                                    <td><?php echo $myrow['CurType']?></td>
                                    <td class="red_txt"><?php echo $addIcon.number_format($myrow['Gold'],2)?></td>
                                    <td class="beizhu red_txt">
                                        <?php
                                        if($myrow['Checked']=='-1'){ // Checked 0 未审核，1 成功，-1 失败，只有拒绝才需要展示
                                            echo $myrow['reason'] ;
                                        }else{
                                            if ($thistype=='S'||$thistype=='Q'||$thistype=='R'){
                                                echo $myrow['notes'];
                                            }else{
                                                echo returnBankAccount($myrow['Bank_Account']);
                                            }
                                        }

                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($myrow['Type']=='S'){
                                            echo '存款'.$checked;
                                        }elseif($myrow['Type']=='R'){
                                            echo '返水'.$checked;
                                        }elseif($myrow['Type']=='Q'){
                                            if ($myrow['From'] == 'hg' && $myrow['To'] == 'cp'){
                                                echo '从体育转至彩票'.$checked;
                                            }
                                            if($myrow['From'] == 'hg' && $myrow['To'] == 'ag' ){
                                                echo '从体育转至AG'.$checked;
                                            }
                                            if($myrow['From'] == 'cp' && $myrow['To'] == 'hg' ){
                                                echo '从彩票转至体育'.$checked;
                                            }
                                            if($myrow['From'] == 'ag' && $myrow['To'] == 'hg' ){
                                                echo '从AG转至体育'.$checked;
                                            }
                                            if($myrow['From'] == 'hg' && $myrow['To'] == 'cq' ){
                                                echo '从体育转至CQ9电子'.$checked;
                                            }
                                            if($myrow['From'] == 'hg' && $myrow['To'] == 'fg' ){
                                                echo '从体育转至FG电子'.$checked;
                                            }
                                        }elseif($myrow['Type']=='C'){
                                            echo '汇款信息回查'.$checked;
                                        }else{
                                            echo '提款'.$checked;
                                        }
                                    ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>


                            <tr class="">
                                <td colspan="7">金额总计(RMB)：<?php echo ($gold>0)?$addIcon.$gold:'0' ?></td>
                            </tr>

                            </tbody>
                        </table>

                        <br>

                        <div class="tactr">
                            <div class="pgnt" type="Main" ipagenumber="1" itotalpage="1">

                                <ul>
                                    <?php
                                    $page_all = ceil(($amount/$pagesize)) ; // 总页数
                                       for($i =0;$i<$page_all;$i++){
                                           $pageadd = $i+1 ;
                                           if($page_num == $pageadd ){ // 当前选中页
                                               echo " <li class=\"Page active\">".($i+1)."</li>" ;
                                           }else{
                                               echo " <li class='Page'><a href='record.php?uid=$uid&langx=$langx &username=$username&thistype=$thistype&date_start=$date_start&date_end=$date_end&page=$pageadd'>". $pageadd ."</a></li>" ;
                                           }

                                       }

                                    ?>
                                    <!--<li class="Page active">1</li>
                                    <li class="Page">3</li>-->
                                    <input type="text" id="gopage" name="gopage">
                                    <a class="btn4 white" href="javascript:void(0);" id="Go" onclick="gopage()">GO</a>
                                </ul>

                                <div class="clear"></div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="clear">
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript">
        var uid = <?php echo '\''.$uid.'\'' ?> ; // 用户 id
        var langx = <?php echo '\''.$langx.'\'' ?> ;
        var username = <?php echo '\''.$username.'\'' ?> ;
        var thistype = <?php echo '\''.$thistype.'\'' ?> ;
        var page_num = <?php echo '\''.$page_num.'\'' ?> ;
        var date_start = <?php echo '\''.$date_start.'\'' ?> ;
        var date_end = <?php echo '\''.$date_end.'\'' ?> ;

        $(document).ready(function(){

            //给input赋值
           /* $('#date_start').val(laydate.now(0, 'YYYY-MM-DD'));
            $('#date_end').val(laydate.now(0, 'YYYY-MM-DD'));*/
            $('#date_start').val(date_start);
            $('#date_end').val(date_end);
            $('#gopage').val(page_num);

            var thistype = <?php echo  '\''.$thistype.'\'' ?>;  // 当前类型
            // 标签切换
            function setNavAction() {
                $('.fd_mn_mn').find('li').each(function () {
                    var ac = $(this).data('action') ;
                   // console.log(ac)
                    if('active_'+thistype == ac){
                        $(this).addClass('active') ;
                    }
                }) ;
            }
            setNavAction() ;





        });
        // 跳转到指定页
        function gopage() {
            var page=document.getElementById("gopage").value;
            location.href='record.php?uid='+uid+'&langx='+langx+'&username='+username+'&thistype='+thistype+'&date_start='+date_start+'&date_end='+ date_end +'&page='+page;
        }

    </script>
</div>

</BODY>
</HTML>
