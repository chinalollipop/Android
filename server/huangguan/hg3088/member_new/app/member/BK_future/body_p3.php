		<tr>
		    <th class="time">时间</th>
		    <th class="team">赛事</th>
		    <th nowrap="" class="h_1x2">独赢</th>
		    <th class="h_r">让球</th>
		    <th class="h_ou">大小</th>
		    <th class="h_oe">单/双</th>
		    <th class="h_oe" colspan="2">球队得分：大/小</th>
	    </tr>           
<!--SHOW LEGUAGE START-->
 <?php foreach($newDataArray as $key=>$match){?>
 <?php if($leagueNameCur!=$match['league']){?>
<tr style="display:;">
	<td colspan="10" class="b_hline">
		<table border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td class="legicon" onclick="parent.showLeg('<?php echo $match['league'];?>')">
				      	<span id="<?php echo $match['league'];?>" class="showleg">
				        	<span id="LegOpen"></span>
				          <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				          <!--收合聯盟-符號--><!--div id="LegClose"></div-->
				      	</span>
					</td>
					<td onclick="parent.showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<?php 
	$leagueNameCur = $match['league'];
} ?>
<!--SHOW LEGUAGE END-->
<tr id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" *class*="">
	<td rowspan="2" class="b_cen"><table><tbody><tr><td class="b_cen"><?php echo $match['datetime'];?></td></tr></tbody></table></td>
	<td rowspan="2" class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	<td class="b_cen self_win"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PMH',3,10)" title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_PMH'];?></font></a></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRH"><span class="con"><?php echo $match['ratio_mb_str'];?></span><span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRH',3,10)" title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_PRH'];?></font></a></span></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUC"><span class="con"><?php echo $match['ratio_o_str'];?></span> <span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUC',3,10)" title="大"><font true=""><?php echo $match['ior_POUC'];?></font></a></span></td>
	<td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_PO']>0) echo '单';?>&nbsp;</span><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'EOO',3,10)"><font true=""><?php echo $match['ior_PO'];?></font></a></span></td><!--综合过关新增主队单双-->
	<td class="b_1stR h_dx_big_1"><span class="con"><?php if($match['ior_POUHO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouho_str'];?><?php } ?></span><span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUHO',3,10)"><font true=""><?php echo $match['ior_POUHO'];?></font></a></span></td>
	<td class="b_1stR h_dx_small_1"><span class="con"><?php if($match['ior_POUHU']>0){?><font class="text_green">小</font><?php echo $match['ratio_ouhu_str'];?><?php } ?></span><span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUHU',3,10)"><font true=""><?php echo $match['ior_POUHU'];?></font></a></span></td>
</tr>
<tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" *class*="">
	<td class="b_cen self_win p3_teamc"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PMC',3,10)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_PMC'];?></font></a></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRC"><span class="con"><?php echo $match['ratio_tg_str'];?></span> <span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRC',3,10)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_PRC'];?></font></a></span></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUH"><span class="con"><?php echo $match['ratio_u_str'];?></span> <span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUH',3,10)" title="小"><font true=""><?php echo $match['ior_POUH'];?></font></a></span></td>
	<td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_PE']>0) echo '双';?>&nbsp;</span><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'EOE',3,10)"><font true=""><?php echo $match['ior_PE'];?></font></a></span></td><!--综合过关新增客队单双-->
	<td class="b_1stR h_dx_big_2"><span class="con"><?php if($match['ior_POUCO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouco_str'];?><?php } ?></span><span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUCO',3,10)"><font true=""><?php echo $match['ior_POUCO'];?></font></a></span></td>
	<td class="b_1stR h_dx_small_2"><span class="con"><?php if($match['ior_POUCU']>0){?><font class="text_green">小</font><?php echo $match['ratio_oucu_str'];?><?php } ?></span><span class="ratio"><a href="javascript:void(0)" onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUCU',3,10)"><font true=""><?php echo $match['ior_POUCU'];?></font></a></span></td>
</tr>
<?php }?>