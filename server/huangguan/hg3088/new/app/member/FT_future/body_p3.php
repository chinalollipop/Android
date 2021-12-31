<?php
$k =0;
$j =0;
?>

<?php foreach($newDataArray as $key=>$match){?>	
<!--SHOW LEGUAGE START-->
<?php if($leagueNameCur!=$match['league']){?>
<tr style="display:" ';'="">
<td colspan="18" class="b_hline">
<table border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td class="legicon" onclick="showLeg('<?php echo $match['league'];?>')">
				<span id="<?php echo $match['league'];?>" name="<?php echo $match['league'];?>" class="showleg">
				<span id="LegOpen"></span>
				<!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				<!--收合聯盟-符號--><!--div id="LegClose"></div-->
				</span>
			</td>
			<td onclick="showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td>
		</tr>
	</tbody>
</table>
</td>
</tr>
<?php 
	$leagueNameCur = $match['league'];
} ?>
<!--SHOW LEGUAGE END-->
<tr class="bet_game_tr_top" id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" *class*="">
	<td rowspan="3" class="b_cen">
		<table>
			<tbody>
				<tr>
					<td class="b_cen"><?php echo $match['datetime'];?></td>
				</tr>
			</tbody>
		</table>
	</td>
	<td rowspan="2" class="team_name none"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_MH"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_MH'];?></font></a></td> <!-- 全场独赢 -->
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRH"><span class="con"><?php if($match['ior_PRH']>0) echo $match['ratio_mb_str'];?></span><span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_PRH'];?></font></a></span></td> <!-- 全场让球 -->
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUC"><span class="con"><?php echo $match['ratio_o_str'];?></span> <span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="大"><font true=""><?php echo $match['ior_POUC'];?></font></a></span></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_PO">单 <a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PO',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="单"><font true=""><?php echo $match['ior_PO'];?></font></a></td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMH"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HPMH'];?></font></a></td> <!-- 半场独赢主队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPRH"><span class="con"><?php if($match['ior_HPRH']>0) echo $hratio_mb_str;?></span><span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPRH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HPRH'];?></font></a></span></td>  <!-- 半场让球主队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPOUC"><span class="con"><?php if($match['ior_HPOUC']>0) echo $match['hratio_o_str'];?></span> <span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPOUC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" 
		title="大"><font true=""><?php echo $match['ior_HPOUC'];?></font></a></span></td>
</tr>
<tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" *class*="">
	<td class="b_cen" id="<?php echo $match['gid'];?>_MC"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_MC'];?></font></a></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRC"><span class="con"><?php if($match['ior_PRC']>0) echo $match['ratio_tg_str'];?></span><span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_PRC'];?></font></a></span></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUH"><span class="con"><?php echo $match['ratio_u_str'];?></span><span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="小"><font true=""><?php echo $match['ior_POUH'];?></font></a></span></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_PE">双 <a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PE',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="双"><font true=""><?php echo $match['ior_PE'];?></font></a></td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMC"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HPMC'];?></font></a></td> <!-- 半场独赢客队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPRC"><span class="con"><?php if($match['ior_HPRC']>0) echo $match['hratio_tg_str'];?></span> <span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPRC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HPRC'];?></font></a></span></td> <!-- 半场让球客队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPOUH"><span class="con"><?php if($match['ior_HPOUH']>0) echo $match['hratio_u_str'];?></span> <span class="ratio"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPOUH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="小"><font true=""><?php echo $match['ior_HPOUH'];?></font></a></span></td>
</tr>
<tr id="TR2_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" *class*="">
	<td class="drawn_td">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr><td align="left">和局</td><td class="hot_td"></td></tr>
			</tbody>
		</table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_MN"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MN',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="和"><font true=""><?php echo $match['ior_MN'];?></font></a></td>
	<td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMN"><a href="javascript:void(0)" 
		onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMN',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="和"><font true=""><?php echo $match['ior_HPMN'];?></font></a></td> <!-- 半场独赢和局 -->
	<td colspan="2" valign="top" class="b_1st">&nbsp;</td>
</tr>

    <?php
    $LeagueAry[] = $match['league'] ;
    $leaguetitle[$match['league']][] = $match['dategh'] ;
    ?>
<?php }?>
<?php
$LeagueAry = array_unique($LeagueAry); // 联赛
// var_dump($LeagueAry);
// var_dump($leaguetitle);

?>
<script language="JavaScript">
    <?php
    foreach ($LeagueAry as $key=>$League){
        if($League){
            echo "parent.LeagueAry[$j]= new Array('$League');\n"; // 联赛
            $j ++ ;
        }

    }
    foreach ($leaguetitle as $key=>$leatitle){
        if($leatitle){
            // echo $key.'--' ;var_dump($leatitle);
            // var_dump($leatitle);
            $leastr = implode(',',$leatitle) ;
            echo "parent.myLeg['$key']= new Array('$leastr') ;\n"; // 联赛
            $k ++ ;
        }

    }

    ?>
</script>