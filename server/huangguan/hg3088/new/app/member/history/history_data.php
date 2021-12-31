<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
//echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$Ttimetop=strtotime(date('Y-m-d'));

require ("../include/traditional.$langx.inc.php");
$sumall=0;
$rsumall=0;

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
	$memname=$_SESSION['UserName'];
	$mid=$_SESSION['userid'];
	$gtype=strtoupper($_REQUEST['game_type']);
	if ($gtype=='' or $gtype=='ALL'){
		$gtype='ALL';
		$style='_fu';
		$active='';		
	}else{
		if($gtype=="FT"){
			$active=" and game_code in (1,11)";		
		}elseif($gtype=="BK"){
			$active=" and game_code in (2,22)";
		}
	}
	
    $de_date_start = date('Y-m-d ',($Ttimetop- 7 * 24 * 3600)) ; // 默认开始时间
    $de_date_end = date('Y-m-d ',$Ttimetop) ; // 默认结束时间

    $date_start=$_REQUEST['date_start']; // 开始时间
    $date_end=$_REQUEST['date_end'];  // 结束时间

    if($date_start !='' && $date_end !=''){
        $de_date_start = $date_start ;
        $de_date_end = $date_end ;
    }

    $xq = array("$His_Week_Sun","$His_Week_Mon","$His_Week_Tue","$His_Week_Wed","$His_Week_Thu","$His_Week_Fri","$His_Week_Sat");

    for($i=0;$i<=7;$i++) {
        $t = $Ttimetop - $i * 24 * 3600;
        $todaytop = date('Y-m-d ', $t);
        $datetimestr .= '<li data-value="'.$todaytop.'"  onclick="changeGameTime(this)">'.$todaytop.'</li>' ;
        $startdatetimestr .='<li data-value="'.$todaytop.'"  onclick="changeGameTime(this,\'first\')">'.$todaytop.'</li>' ;

    }


    ?>
    <html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <link rel="stylesheet" href="../../../style/my_account.css?v=<?php echo AUTOVER; ?>" type="text/css">

    </head>

    <body id="M<?php echo $gtype?>" class="bodyset HIS" onLoad="onLoad()">
    <div class="acc_leftMain HIS_data">
        <!--header-->
        <div class="acc_header noFloat"><h1>帐户历史</h1></div>
        <form method="post" id="history_submit" name="history_submit" style="display:inline;">
            <div class="acc_state_head">
                <!--特制下拉罢--->
                <span class="acc_state_title">按体育查看记录</span>
                <input name="game_type" id="game_type" type="hidden" value=""/>
                <ul class="acc_selectMS" id="type_acc_selectMS">

                </ul>

                <span class="acc_state_date">
                    <!--特制下拉罢--->
         <input name="date_start" type="hidden" value=""/> <!-- 开始时间 -->
         <input name="date_end" type="hidden" value=""/> <!-- 结束时间 -->

          <span class="acc_state_title">日期</span>
                    <!-- 开始日期 -->
		         	<ul class="acc_selectMS" >
				         	<li id="sel_date_s" name="sel_date_s" class="sel_date_cls acc_selectMS_first" data-value="<?php echo (($date_start =='')?date('Y-m-d ',($Ttimetop- 7 * 24 * 3600)):$date_start);?>" class="acc_selectMS_first"><?php echo (($date_start =='')?date('Y-m-d ',($Ttimetop- 7 * 24 * 3600)):$date_start);?></li>
				        	<ul id="chose_date_s" class="acc_selectMS_options" style="display:none">
				          		 <?php
                                 echo $startdatetimestr ;
                                 ?>
				          </ul>
		        	</ul>
          </span>

                <span class="acc_state_to">
                    <!--特制下拉罢--->
          <span class="acc_state_title">到</span>
                    <!-- 结束日期 -->
			        <ul class="acc_selectMS">
			        		<li id="sel_date_e" class="sel_date_cls acc_selectMS_first" name="sel_date_e" data-value="<?php echo ( ($date_end =='')?date('Y-m-d ',$Ttimetop):$date_end );?>" class="acc_selectMS_first"><?php echo ( ($date_end =='')?date('Y-m-d ',$Ttimetop):$date_end );?></li>
			        		<ul id="chose_date_e" class="acc_selectMS_options" style="display:none">
                                <?php
                                echo $datetimestr ;
                                ?>
                                <!--  <li value="2018-03-27" class="On">2018-03-27 </li>-->
			            </ul>
			        </ul>
         	</span>

                <span class="acc_ann_searchBTN" onclick="changeGtpye();">搜寻</span>
            </div>

            <!--   <table border="0" cellpadding="0" cellspacing="0" id="box" class="acc_state_table">
         <h2>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
              <tr>
                <td>
                接體育查看記錄:

	      <select name="game_type" id="game_type" onChange="changeGtpye();">
	        <option value="ALL"><?php /*echo $His_All*/?></option>
	        <option value="FT"><?php /*echo $His_Soccer*/?></option>
	        <option value="BK"><?php /*echo $His_Baseketball*/?></option>
	        <option value="TN"><?php /*echo $His_Tennis*/?></option>
	        <option value="VB"><?php /*echo $His_VolleyBall*/?></option>
	        <option value="BS"><?php /*echo $His_BaseBall*/?></option>
			<option value="FS"><?php /*echo $His_Outright*/?></option>
	        <option value="OP"><?php /*echo $His_Other*/?></option>
	      </select>

                <input type=submit value="<?php /*echo $His_Search*/?>">
                    <input type="button" value="六合历史" class="" onClick="location.href='<?php /*echo BROWSER_IP*/?>/app/member/six/index.php?action=l&uid=<?php /*echo $uid*/?>';"> <input type="button" value="时时彩历史" class="" onClick="location.href='<?php /*echo BROWSER_IP*/?>/app/member/ssc/templates/repore.php';">
                </td>
              </tr>
            </table>
     </h2>
    </table> -->
            <table border="0" cellspacing="0" cellpadding="0" class="acc_state_table">
                <tr class="acc_state_tr_title">
                    <th class="his_time"><?php echo $His_Date?></th>
                    <th class="his_wag" ><?php echo $His_Bet_Amount?></th>
                    <th class="his_wag"><?php echo $His_Valid_Amount?></th>
                    <th class="his_wag">派彩結果</th>
                    <!--th width="25%">有效金额</th-->
                </tr>

                <?php

                $datearr = getDateFromRange($de_date_start,$de_date_end);
                krsort($datearr) ; // 倒序
                foreach($datearr as $date){
                    // 执行处理
                    $tx =strtotime($date) ; // 转时间戳
                    $today=date('m月d日 ',$tx).$week.$xq[date("w",$tx)];

                    // 美东时间 0点到3点，前一天的历史报表数据还未生成，数据从注单表汇总捞取
                    // 将时间分3段（1 当天捞取当天、2 当天3点前捞取前一天、3 当天3点后 捞取前一天以及其他前一天之前的数据）
                    $previous_day = date('Y-m-d',strtotime('-1 day')); // 前一天
                    if( $date == date('Y-m-d') ){ // 当天
                        $sql="select sum(vgold) as vgold,sum(betscore) as betscore,sum(m_result) as m_result from ".DBPREFIX."web_report_data where m_result!='' and m_date='".$date."' and m_name='$memname'".$active;
                    }elseif ( $date == $previous_day && (int)date("G") < 3 ){ // 前一天数据，3点前从注单表捞取，否则捞取注单历史报表
                        $sql="select sum(vgold) as vgold,sum(betscore) as betscore,sum(m_result) as m_result from ".DBPREFIX."web_report_data where m_result!='' and m_date='".$date."' and m_name='$memname'".$active;
                    }else{
                        $sql="select sum(valid_money) as vgold,sum(total) as betscore,sum(user_win) as m_result from ".DBPREFIX."web_report_history_report_data where m_date='".$date."' and userid={$mid}".$active;
                    }

                    $result = mysqli_query($dbLink,$sql);
                    $row = mysqli_fetch_assoc($result);
                    $sum=$row['betscore']+0 ;
                    $rsum=$row['m_result']+0 ;

                    $aa=$aa+$row['betscore'];
                    $bb=$bb+$row['m_result'];
                    $vgold=$vgold+$row['vgold'];

                    if($sum>0){
                        $link='<a href="'."history_view.php?uid=$uid&member_id=$mid&tmp_flag=N&today_gmt=".$date."&gtype=$gtype&date_start=$date_start&date_end=$date_end&langx=$langx".'">'.$today.'</a>';
                    }else{
                        $link=$today;
                    }
                    echo '<tr class="acc_state_tr_cont"  >
                            <td class="acc_state_leftdate" id="d_date"><span >'.$link.'</span></td>
                            <td class="acc_state_number" ><span class="fin_gold">'.number_format($sum).'</span></td>
                            <td class="acc_state_number " >'.number_format($row['vgold']).'</td>
                            <td  class="pai_result">'.number_format($rsum,2).'</td>
                          </tr>';
                }
                ?>
                <tr class="acc_state_tr_total">
                    <td class="acc_state_total_color">总计</td>
                    <td ><?php echo number_format($aa,2)?></td>
                    <td ><?php echo number_format($vgold,2)?></td>
                    <td ><?php echo number_format($bb,2)?></td>
                    <!--td>-</td-->
                </tr>

            </table>
        </form>
    </div>
    <script language="javascript" src="../../../js/jquery.js"></script>
    <script><!--
        var langx='<?php echo $langx?>';
        var gtype ='<?php echo $gtype?>' ;
        setGameType(gtype) ;
        showDateList() ;

        function onLoad(){
            var select_object = document.getElementById("game_type");
            select_object.value = '<?php echo $gtype ?>';

        }
        // 查询按钮
        function changeGtpye(){
            var date_start = $('[name="sel_date_s"]').text() ;
            var date_end = $('[name="sel_date_e"]').text() ;

            if(date_start > date_end){ // 如果所选开始日期大于结束日期
                $('[name="date_start"]').val(date_end) ; // 开始日期
                $('[name="date_end"]').val(date_start) ; // 开始日期
            }else{
                $('[name="date_start"]').val(date_start) ; // 开始日期
                $('[name="date_end"]').val(date_end) ; // 开始日期
            }

            history_submit.submit();
        }
        /*   function overbars(obj,color){
               var className=obj.cells["d_date"].className;
               if (className=="his_list_none") return;
               obj.cells["d_date"].className=color;

           }
           function outbars(obj,color){
               var className=obj.cells["d_date"].className;
               if (className=="his_list_none") return;
               obj.cells["d_date"].className=color;
           }*/

        function setGameType(type) {
            var gametype=[
                {type:'ALL',name:'所有体育'},
                {type:'FT',name:'足球'},
                {type:'BK',name:'篮球 / 美式足球'},
/*                {type:'TN',name:'网球'},
                {type:'VB',name:'排球'},
                {type:'BM',name:'羽毛球'},
                {type:'TT',name:'乒乓球'},
                {type:'BS',name:'棒球'},
                {type:'SK',name:'斯诺克/台球'},
                {type:'FS',name:'冠军'},
                {type:'OP',name:'其他'},
*/
            ] ;
            var str='' ;
            for(var i=0;i<gametype.length;i++){
                if(type==gametype[i].type){
                    str +=' <li id="sel_gtype" onclick="parent.showOption(\'gtype\');" class="acc_selectMS_first">'+gametype[i].name+'</li>\n';
                }
            }
            str += '\t<ul id="chose_gtype" class="acc_selectMS_options" style="display: none;">\n';
            for(var i=0;i<gametype.length;i++){
                if(type==gametype[i].type){
                    str +='<li data-value="'+gametype[i].type+'" class="On">'+gametype[i].name+'</li>';
                }else{
                    str +='<li data-value="'+gametype[i].type+'" class="acc_select" onclick="parent.changeGameType(this)">'+gametype[i].name+'</li>';
                }
            }
            str += '</ul>' ;
            $('#type_acc_selectMS').html(str) ;
        }

        // 公用选择日期
        function showDateList(){
            $('body').on('click','.sel_date_cls',function () {
                $(this).next().show() ;
            });
        }
        function changeGameTime(obj,first) {
            var val =$(obj).data('value') ;
            $(obj).parents('.acc_selectMS').find('.sel_date_cls').text(val) ;
            $('.acc_selectMS_options').hide() ;
         /*   if(first){ // 开始日期
                $('[name="date_start"]').val(val) ;
            }else{ // 结束日期
                $('[name="date_end"]').val(val) ;
            }*/
        }

    --></script>
    </body>
    </html>
    <?php
}
?>
