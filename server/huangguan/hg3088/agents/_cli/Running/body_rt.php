<?php
$k =0;
$j =0;
?>
			<tr>
              <th class="time">时间</th>
              <th class="team">赛事</th>
              <th class="h_oe">0 - 1</th>
              <th class="h_oe">2 - 3</th>
              <th class="h_oe">4 - 6</th>
              <th class="h_oe">7up</th>
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
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RT','<?php echo $match['bet_Url']; ?>R0~1');" title="0~1"><font true=""><?php echo $match['ior_T01'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RT','<?php echo $match['bet_Url']; ?>R2~3');" title="2~3"><font true=""><?php echo $match['ior_T23'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RT','<?php echo $match['bet_Url']; ?>R4~6');" title="4~6"><font true=""><?php echo $match['ior_T46'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RT','<?php echo $match['bet_Url']; ?>ROVER');" title="7up"><font true=""><?php echo $match['ior_OVER'];?></font></a></td>
  </tr>

    <?php
    $LeagueAry[] = $match['league'] ; // 联赛
    $leaguetitle[$match['league']][] = $match['dategh'] ; // 联赛
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