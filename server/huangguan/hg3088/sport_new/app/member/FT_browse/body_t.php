<?php
$k =0;
$j =0;
$navstr ='<tr class="play_tr_nav">
               <th class="time"> 时间 </th>
              <th class="team"> 赛事 </th>
              <th class="h_oe">0 - 1</th>
              <th class="h_oe">2 - 3</th>
              <th class="h_oe">4 - 6</th>
              <th class="h_oe">7up</th>
        </tr>';
?>

<?php foreach($newDataArray as $key=>$match){?>	
<!--SHOW LEGUAGE START-->
<?php if($leagueNameCur!=$match['league']){?>
  <tr class="tr_league">
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
        echo $navstr;
		$leagueNameCur = $match['league'];
	} ?>
<!--SHOW LEGUAGE END-->
  <tr id="TR_<?php echo $match['dategh'];?>">
    <td class="b_cen">
        <table>
            <tbody>
            <tr>
                <td class="today_time"> <?php echo $match['datetime'];?> </td>
            </tr>
            </tbody>
        </table>

    </td>
    <td class="team_name"><span class="team_name_zd"><?php echo $match['team_h'];?></span><br><span class="team_name_kd"><?php echo $match['team_c'];?></span></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','T','<?php echo $match['bet_Url']; ?>0~1');" title="0~1"><font true=""><?php echo $match['ior_T01'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','T','<?php echo $match['bet_Url']; ?>2~3');" title="2~3"><font true=""><?php echo $match['ior_T23'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','T','<?php echo $match['bet_Url']; ?>4~6');" title="4~6"><font true=""><?php echo $match['ior_T46'];?></font></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','T','<?php echo $match['bet_Url']; ?>OVER');" title="7up"><font true=""><?php echo $match['ior_OVER'];?></font></a></td>
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
