<?php
$k =0;
$j =0;
?>
<tr>
			<th class="time">时间</th>
			<th class="team">赛事</th>
			<th class="h_f">主/主</th>
			<th class="h_f">主/和</th>
			<th class="h_f">主/客</th>
			<th class="h_f">和/主</th>
			<th class="h_f">和/和</th>
			<th class="h_f">和/客</th>
			<th class="h_f">客/主</th>
			<th class="h_f">客/和</th>
			<th class="h_f">客/客</th>
</tr> 
<?php foreach($newDataArray as $key=>$match){?>	
 <!--SHOW LEGUAGE START-->
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
	    <td class="b_cen"><?php echo $match['datetime'];?></td>
	    <td class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHH');" title="H/H"><?php if($oddsBackground[$key]['ior_FHH']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FHH'];?></font><?php if($oddsBackground[$key]['ior_FHH']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHN');" title="H/D"><?php if($oddsBackground[$key]['ior_FHN']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FHN'];?></font><?php if($oddsBackground[$key]['ior_FHN']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHC');" title="H/A"><?php if($oddsBackground[$key]['ior_FHC']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FHC'];?></font><?php if($oddsBackground[$key]['ior_FHC']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNH');" title="D/H"><?php if($oddsBackground[$key]['ior_FNH']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FNH'];?></font><?php if($oddsBackground[$key]['ior_FNH']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNN');" title="D/D"><?php if($oddsBackground[$key]['ior_FNN']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FNN'];?></font><?php if($oddsBackground[$key]['ior_FNN']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNC');" title="D/A"><?php if($oddsBackground[$key]['ior_FNC']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FNC'];?></font><?php if($oddsBackground[$key]['ior_FNC']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCH');" title="A/H"><?php if($oddsBackground[$key]['ior_FCH']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FCH'];?></font><?php if($oddsBackground[$key]['ior_FCH']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCN');" title="A/D"><?php if($oddsBackground[$key]['ior_FCN']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FCN'];?></font><?php if($oddsBackground[$key]['ior_FCN']==1){ ?> </font> <?php } ?></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCC');" title="A/A"><?php if($oddsBackground[$key]['ior_FCC']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_FCC'];?></font><?php if($oddsBackground[$key]['ior_FCC']==1){ ?> </font> <?php } ?></a></td>
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