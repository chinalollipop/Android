			<tr>
				<th class="time">时间</th>
				<th>主客队伍</th>
				<th>1:0</th>
				<th>2:0</th>
				<th>2:1</th>
				<th>3:0</th>
				<th>3:1</th>
				<th>3:2</th>
				<th>0:0</th>
				<th>1:1</th>
				<th>2:2</th>
				<th>3:3</th>
				<th>其它</th>
		    </tr>
<?php foreach($newDataArray as $key=>$match){?>	
<?php if($leagueNameCur!=$match['league']){?>
	<tr style="display: ;">
		<td colspan="18" class="b_hline">
			<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg('<?php echo $match['league'];?>')">
				<span id="<?php echo $match['league'];?>" name="<?php echo $match['league'];?>" class="showleg">
					<span id="LegOpen"></span>
				       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
				</span>
		</td><td onclick="showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td></tr></tbody></table>
		</td>
	</tr>
	<?php 
		$leagueNameCur = $match['league'];
	} ?>
    <!--SHOW LEGUAGE END-->
	<tr id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
	    <td rowspan="2" class="b_cen">06-19<br>08:00a</td>
	    <td rowspan="2" class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H1C0');" title="1:0"><font true=""><?php echo $match['ior_H1C0']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H2C0');" title="2:0"><font true=""><?php echo $match['ior_H2C0']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H2C1');" title="2:1"><font true=""><?php echo $match['ior_H2C1']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H3C0');" title="3:0"><font true=""><?php echo $match['ior_H3C0']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H3C1');" title="3:1"><font true=""><?php echo $match['ior_H3C1']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H3C2');" title="3:2"><font true=""><?php echo $match['ior_H3C2']; ?></font></a></td>
	    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H0C0');" title="0:0"><font true=""><?php echo $match['ior_H0C0']; ?></font></a></td>
	    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H1C1');" title="1:1"><font true=""><?php echo $match['ior_H1C1']; ?></font></a></td>
	    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H2C2');" title="2:2"><font true=""><?php echo $match['ior_H2C2']; ?></font></a></td>
	    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H3C3');" title="3:3"><font true=""><?php echo $match['ior_H3C3']; ?></font></a></td>
	    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>HOVH');" title="Other Score"><font true=""><?php echo $match['ior_OVH']; ?></font></a></td>
  	</tr>
  	<tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H0C1');" title="0:1"><font true=""><?php echo $match['ior_H0C1']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H0C2');" title="0:2"><font true=""><?php echo $match['ior_H0C2']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H1C2');" title="1:2"><font true=""><?php echo $match['ior_H1C2']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H0C3');" title="0:3"><font true=""><?php echo $match['ior_H0C3']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H1C3');" title="1:3"><font true=""><?php echo $match['ior_H1C3']; ?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HPD','<?php echo $match['bet_Url']; ?>H2C3');" title="2:3"><font true=""><?php echo $match['ior_H2C3']; ?></font></a></td>
  	</tr>
  <!--SHOW LEGUAGE START-->
<?php }?>