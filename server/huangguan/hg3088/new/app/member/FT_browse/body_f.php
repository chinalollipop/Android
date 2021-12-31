<?php
$k =0;
$j =0;
?>

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
	<tr class="bet_game_tr_top" id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
	    <td class="b_cen"><?php echo $match['datetime'];?></td>
	    <td class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FHH');" title="H/H"><font true=""><?php echo $match['ior_FHH'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FHN');" title="H/D"><font true=""><?php echo $match['ior_FHN'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FHC');" title="H/A"><font true=""><?php echo $match['ior_FHC'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FNH');" title="D/H"><font true=""><?php echo $match['ior_FNH'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FNN');" title="D/D"><font true=""><?php echo $match['ior_FNN'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FNC');" title="D/A"><font true=""><?php echo $match['ior_FNC'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FCH');" title="A/H"><font true=""><?php echo $match['ior_FCH'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FCN');" title="A/D"><font true=""><?php echo $match['ior_FCN'];?></font></a></td>
	    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','F','<?php echo $match['bet_Url']; ?>FCC');" title="A/A"><font true=""><?php echo $match['ior_FCC'];?></font></a></td>
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