<?php
$k =0;
$j =0;
?>

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
	<tr class="bet_game_tr_top" id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen"><?php echo $match['datetime'];?></td>
    <td rowspan="2" class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C0');" title="1:0"><font true=""><?php echo $match['ior_H1C0'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C0');" title="2:0"><font true=""><?php echo $match['ior_H2C0'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C1');" title="2:1"><font true=""><?php echo $match['ior_H2C1'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C0');" title="3:0"><font true=""><?php echo $match['ior_H3C0'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C1');" title="3:1"><font true=""><?php echo $match['ior_H3C1'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C2');" title="3:2"><font true=""><?php echo $match['ior_H3C2'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C0');" title="4:0"><font true=""><?php echo $match['ior_H4C0'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C1');" title="4:1"><font true=""><?php echo $match['ior_H4C1'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C2');" title="4:2"><font true=""><?php echo $match['ior_H4C2'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C3');" title="4:3"><font true=""><?php echo $match['ior_H4C3'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C0');" title="0:0"><font true=""><?php echo $match['ior_H0C0'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C1');" title="1:1"><font true=""><?php echo $match['ior_H1C1'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C2');" title="2:2"><font true=""><?php echo $match['ior_H2C2'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C3');" title="3:3"><font true=""><?php echo $match['ior_H3C3'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C4');" title="4:4"><font true=""><?php echo $match['ior_H4C4'];?></font></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>ROVH');" title="Other Score"><font true=""><?php echo $match['ior_OVH'];?></font></a></td>
  </tr>
  <tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C1');" title="0:1"><font true=""><?php echo $match['ior_H0C1'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C2');" title="0:2"><font true=""><?php echo $match['ior_H0C2'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C2');" title="1:2"><font true=""><?php echo $match['ior_H1C2'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C3');" title="0:3"><font true=""><?php echo $match['ior_H0C3'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C3');" title="1:3"><font true=""><?php echo $match['ior_H1C3'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C3');" title="2:3"><font true=""><?php echo $match['ior_H2C3'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C4');" title="0:4"><font true=""><?php echo $match['ior_H0C4'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C4');" title="1:4"><font true=""><?php echo $match['ior_H1C4'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C4');" title="2:4"><font true=""><?php echo $match['ior_H2C4'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C4');" title="3:4"><font true=""><?php echo $match['ior_H3C4'];?></font></a></td>
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
