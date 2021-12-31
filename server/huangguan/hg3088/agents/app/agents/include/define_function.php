<?php
function show_voucher($line,$id){
    switch($line){
        case 1:
            $show_voucher='OU'.($id+29027142);
            break;
        case 2:
            $show_voucher='OU'.($id+29027142);
            break;
        case 3:
            $show_voucher='OU'.($id+29027142);
            break;
        case 4:
            $show_voucher='DT'.($id+29027142);
            break;
        case 5:
            $show_voucher='DT'.($id+29027142);
            break;
        case 6:
            $show_voucher='DT'.($id+29027142);
            break;
        case 7:
            $show_voucher='P'.($id+29027142);
            break;
        case 8:
            $show_voucher='PR'.($id+29657821);
            break;
        case 9:
            $show_voucher='OU'.($id+29027142);
            break;
        case 10:
            $show_voucher='OU'.($id+29027142);
            break;
        case 11:
            $show_voucher='OU'.($id+29027142);
            break;
        case 12:
            $show_voucher='OU'.($id+29027142);
            break;
        case 13:
            $show_voucher='OU'.($id+29027142);
            break;
        case 14:
            $show_voucher='DT'.($id-29127142);
            break;
        case 15:
            $show_voucher='OU'.($id+29027142);
            break;
        case 19:
            $show_voucher='OU'.($id+29027142);
            break;
        case 20:
            $show_voucher='OU'.($id+29027142);
            break;

    }
    return $show_voucher;
}
//大小球计算：
function odds_dime($mbin1,$tgin1,$dime,$mtype){
    $dime=str_replace('大','',$dime);
    $dime=str_replace('小','',$dime);
    $dime=str_replace('O','',$dime);
    $dime=str_replace('U','',$dime);
    $dime=str_replace('&nbsp;','',$dime);
    $dime=str_replace('&nbsp','',$dime);
    $total_inball=$mbin1+$tgin1;
    $dime_odds=explode("/",$dime);
    switch (sizeof($dime_odds)){
        case 1:
            $odds_inball=$total_inball-$dime_odds[0];
            switch ($mtype){//下大
                case 'OUH':
                    if ($odds_inball>0){
                        $grape=1;
                    }else if ($odds_inball<0){
                        $grape=-1;
                    }else{
                        $grape=0;
                    }
                    break;
                case 'OUC'://下小
                    if ($odds_inball>0){
                        $grape=-1;
                    }else if ($odds_inball<0){
                        $grape=1;
                    }else{
                        $grape=0;
                    }
                    break;
            }
            break;
        case 2:
            if (ceil($dime_odds[0])==$dime_odds[0]){
                $odds_inball=$total_inball-$dime_odds[0];
                switch ($mtype){
                    case "OUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                    case "OUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                }
            }else{
                $odds_inball=$total_inball-$dime_odds[1];
                switch ($mtype){
                    case "OUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                    case "OUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                }
            }
            break;
    }
    $odds_dime=$grape;
    return $odds_dime;
}

//球队进球数大小
function teamballin_odds_dime($mbin1,$tgin1,$dime,$mtype){
    $totalScore=0;
    $team='';
    if(strpos($mtype,'H')>-1){
        $totalScore=$mbin1;
    }elseif(strpos($mtype,'C')>-1){
        $totalScore=$tgin1;
    }
    $teamStr = substr($mtype,-1,1);
    if($teamStr=="O"){
        $team="OUH";
    }elseif($teamStr=="U"){
        $team="OUC";
    }
    $result = odds_dime($totalScore,0,$dime,$team);
    return $result;
}

//让球计算:
function odds_letb($mbin,$tgin,$showtype,$dime,$mtype){
    if(preg_match("/[+]/i",$dime)){
        $letb_odds=explode("+",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){//半球在后1/1.5
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=1;
                                        }else{
                                            $grade=-1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=-1;
                                        }else{
                                            $grade=1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=-1;}else{$grade=1*$letb_odds[1]/100;}
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=1;}else{$grade=-1*$letb_odds[1]/100;}
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else if(preg_match("/[-]/i",$dime)){
        $letb_odds=explode("-",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else{//这里指的是另外的。注意这里
        $letb_odds=explode("/",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin-$letb_odds[1];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[1];
                            switch ($mtype){
                                case 'RH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'RC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }
    $odds_letb=$grade;
    return $odds_letb;
}
//上半大小球计算：
function odds_dime_v($mbin1,$tgin1,$dime,$mtype){
    $dime=str_replace('大','',$dime);
    $dime=str_replace('小','',$dime);
    $dime=str_replace('O','',$dime);
    $dime=str_replace('U','',$dime);
    $dime=str_replace('&nbsp;','',$dime);
    $dime=str_replace('&nbsp','',$dime);
    $total_inball=$mbin1+$tgin1;
    $dime_odds=explode("/",$dime);
    switch (sizeof($dime_odds)){
        case 1:
            $odds_inball=$total_inball-$dime_odds[0];
            switch ($mtype){//下大
                case 'VOUH':
                    if ($odds_inball>0){
                        $grape=1;
                    }else if ($odds_inball<0){
                        $grape=-1;
                    }else{
                        $grape=0;
                    }
                    break;
                case 'VOUC'://下小
                    if ($odds_inball>0){
                        $grape=-1;
                    }else if ($odds_inball<0){
                        $grape=1;
                    }else{
                        $grape=0;
                    }
                    break;
            }
            break;
        case 2:
            if (ceil($dime_odds[0])==$dime_odds[0]){
                $odds_inball=$total_inball-$dime_odds[0];
                switch ($mtype){
                    case "VOUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                    case "VOUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                }
            }else{
                $odds_inball=$total_inball-$dime_odds[1];
                switch ($mtype){
                    case "VOUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                    case "VOUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                }
            }
            break;
    }
    $odds_dime=$grape;
    return $odds_dime;
}
//上半让球计算:
function odds_letb_v($mbin,$tgin,$showtype,$dime,$mtype){
    if(preg_match("/[+]/i",$dime)){
        $letb_odds=explode("+",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){//半球在后1/1.5
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=1;
                                        }else{
                                            $grade=-1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=-1;
                                        }else{
                                            $grade=1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=-1;}else{$grade=1*$letb_odds[1]/100;}
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=1;}else{$grade=-1*$letb_odds[1]/100;}
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else if(preg_match("/[-]/i",$dime)){
        $letb_odds=explode("-",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else{//这里指的是另外的。注意这里
        $letb_odds=explode("/",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin-$letb_odds[1];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[1];
                            switch ($mtype){
                                case 'VRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'VRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }
    $odds_letb=$grade;
    return $odds_letb;
}
//滚球大小球计算：
function odds_dime_rb($mbin1,$tgin1,$dime,$mtype){
    $dime=str_replace('大','',$dime);
    $dime=str_replace('小','',$dime);
    $dime=str_replace('O','',$dime);
    $dime=str_replace('U','',$dime);
    $total_inball=$mbin1+$tgin1;
    $dime_odds=explode("/",$dime);
    switch (sizeof($dime_odds)){
        case 1:
            $odds_inball=$total_inball-$dime_odds[0];
            switch ($mtype){//下大
                case 'ROUH':
                    if ($odds_inball>0){
                        $grape=1;
                    }else if ($odds_inball<0){
                        $grape=-1;
                    }else{
                        $grape=0;
                    }
                    break;
                case 'ROUC'://下小
                    if ($odds_inball>0){
                        $grape=-1;
                    }else if ($odds_inball<0){
                        $grape=1;
                    }else{
                        $grape=0;
                    }
                    break;
            }
            break;
        case 2:
            if (ceil($dime_odds[0])==$dime_odds[0]){
                $odds_inball=$total_inball-$dime_odds[0];
                switch ($mtype){
                    case "ROUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                    case "ROUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                }
            }else{
                $odds_inball=$total_inball-$dime_odds[1];
                switch ($mtype){
                    case "ROUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                    case "ROUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                }
            }
            break;
    }
    $odds_dime=$grape;
    return $odds_dime;
}
//滚球让球计算:
function odds_letb_rb($mbin,$tgin,$showtype,$dime,$mtype){
    if(preg_match("/[+]/i",$dime)){
        $letb_odds=explode("+",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){//半球在后1/1.5
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=1;
                                        }else{
                                            $grade=-1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=-1;
                                        }else{
                                            $grade=1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=-1;}else{$grade=1*$letb_odds[1]/100;}
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=1;}else{$grade=-1*$letb_odds[1]/100;}
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else if(preg_match("/[-]/i",$dime)){
        $letb_odds=explode("-",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else{//这里指的是另外的。注意这里
        $letb_odds=explode("/",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin-$letb_odds[1];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[1];
                            switch ($mtype){
                                case 'RRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'RRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }
    $odds_letb=$grade;
    return $odds_letb;
}
//滚球上半大小球计算：
function odds_dime_vrb($mbin1,$tgin1,$dime,$mtype){
    $dime=str_replace('大','',$dime);
    $dime=str_replace('小','',$dime);
    $dime=str_replace('O','',$dime);
    $dime=str_replace('U','',$dime);
    $total_inball=$mbin1+$tgin1;
    $dime_odds=explode("/",$dime);
    switch (sizeof($dime_odds)){
        case 1:
            $odds_inball=$total_inball-$dime_odds[0];
            switch ($mtype){//下大
                case 'VROUH':
                    if ($odds_inball>0){
                        $grape=1;
                    }else if ($odds_inball<0){
                        $grape=-1;
                    }else{
                        $grape=0;
                    }
                    break;
                case 'VROUC'://下小
                    if ($odds_inball>0){
                        $grape=-1;
                    }else if ($odds_inball<0){
                        $grape=1;
                    }else{
                        $grape=0;
                    }
                    break;
            }
            break;
        case 2:
            if (ceil($dime_odds[0])==$dime_odds[0]){
                $odds_inball=$total_inball-$dime_odds[0];
                switch ($mtype){
                    case "VROUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                    case "VROUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                }
            }else{
                $odds_inball=$total_inball-$dime_odds[1];
                switch ($mtype){
                    case "VROUH":
                        if ($odds_inball>0){
                            $grape=1;
                        }else if($odds_inball<0){
                            $grape=-1;
                        }else if($odds_inball==0){
                            $grape=0.5;
                        }
                        break;
                    case "VROUC":
                        if ($odds_inball>0){
                            $grape=-1;
                        }else if($odds_inball<0){
                            $grape=1;
                        }else if($odds_inball==0){
                            $grape=-0.5;
                        }
                        break;
                }
            }
            break;
    }
    $odds_dime=$grape;
    return $odds_dime;
}
//滚球上半让球计算:
function odds_letb_vrb($mbin,$tgin,$showtype,$dime,$mtype){
    if(preg_match("/[+]/i",$dime)){
        $letb_odds=explode("+",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){//半球在后1/1.5
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=1;
                                        }else{
                                            $grade=-1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if ($letb_odds[1]==100){
                                            $grade=-1;
                                        }else{
                                            $grade=1*$letb_odds[1]/100;
                                        }
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=-1;}else{$grade=1*$letb_odds[1]/100;}
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        if($letb_odds[1]==100){$grade=1;}else{$grade=-1*$letb_odds[1]/100;}
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else if(preg_match("/[-]/i",$dime)){
        $letb_odds=explode("-",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin;
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=1*$letb_odds[1]/100;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<$letb_odds[0]){
                                        $grade=-1;
                                    }else if($abcd>$letb_odds[0]){
                                        $grade=1;
                                    }else if($abcd==$letb_odds[0]){
                                        $grade=-1*$letb_odds[1]/100;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }else{//这里指的是另外的。注意这里
        $letb_odds=explode("/",$dime);
        switch (sizeof($letb_odds)){
            case 1:
                if (strlen($letb_odds[0])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
            case 2:
                if (strlen($letb_odds[1])>2){
                    switch ($showtype){
                        case "H"://让球方是主队
                            $abcd=$mbin-$tgin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[0];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }else{
                    switch ($showtype){
                        case "H"://让球方是主队0.5/1
                            $abcd=$mbin-$tgin-$letb_odds[1];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                            }
                            break;
                        case "C"://让球方是客队
                            $abcd=$tgin-$mbin-$letb_odds[1];
                            switch ($mtype){
                                case 'VRRH':
                                    if ($abcd<0){
                                        $grade=1;
                                    }else if($abcd>0){
                                        $grade=-1;
                                    }else if($abcd==0){
                                        $grade=-0.5;
                                    }
                                    break;
                                case 'VRRC':
                                    if ($abcd<0){
                                        $grade=-1;
                                    }else if($abcd>0){
                                        $grade=1;
                                    }else if($abcd==0){
                                        $grade=0.5;
                                    }
                                    break;
                            }
                            break;
                    }
                }
                break;
        }
    }
    $odds_letb=$grade;
    return $odds_letb;
}
//波胆计算：
function odds_pd($mb_in_score,$tg_in_score,$m_place){

    $betplace='MB'.$mb_in_score.'TG'.$tg_in_score;
    if ($m_place=='OVMB' and $mb_in_score>4){
        $grade=1;
    }elseif ($m_place=='OVMB' and $tg_in_score>4){
        $grade=1;
    }elseif ($m_place==$betplace){
        $grade=1;
    }else{
        $grade=-1;
    }

    $odds_pd=$grade;
    return $odds_pd;
}
//上半波胆计算：
function odds_pd_v($mb_in_score_v,$tg_in_score_v,$m_place){

    $betplace='MB'.$mb_in_score_v.'TG'.$tg_in_score_v;
    if ($m_place=='OVMB' and $mb_in_score_v>3){
        $grade=1;
    }elseif ($m_place=='OVMB' and $tg_in_score_v>3){
        $grade=1;
    }elseif ($m_place==$betplace){
        $grade=1;
    }else{
        $grade=-1;
    }

    $odds_pd_v=$grade;
    return $odds_pd_v;
}
//单双计算:
function odds_eo($mb_in_score,$tg_in_score,$m_place){
    $inball=($mb_in_score+$tg_in_score);
    switch ($inball%2){
        case 1:
            if ($m_place=='ODD'){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 0:
            if ($m_place=='EVEN'){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
    }
    $odds_eo=$grade;
    return $odds_eo;
}
//入球数计算:
function odds_t($mb_in_score,$tg_in_score,$m_place){
    $inball=$mb_in_score+$tg_in_score;
    if ($inball>=0 and $inball<=1){
        $goin_place="0~1";
    }else if ($inball>=2 and $inball<=3){
        $goin_place="2~3";
    }else if ($inball>=4 and $inball<=6){
        $goin_place="4~6";
    }else if ($inball>=7){
        $goin_place="OVER";
    }
    if ($m_place==$goin_place){
        $grade=1;
    }else{
        $grade=-1;
    }
    $odds_t=$grade;
    return $odds_t;
}
//半场入球数计算:
function odds_t_v($mb_in_score,$tg_in_score,$m_place){
    $inball=$mb_in_score+$tg_in_score;
    if ($inball==0){
        $goin_place="HT0";
    }elseif($inball==1){
        $goin_place="HT1";
    }elseif($inball==2){
        $goin_place="HT2";
    }else if ($inball>=3){
        $goin_place="HTOV";
    }
    if ($m_place==$goin_place){
        $grade=1;
    }else{
        $grade=-1;
    }
    $odds_t=$grade;
    return $odds_t;
}
//入球数计算:
function odds_bst($mb_in_score,$tg_in_score,$m_place){
    $inball=$mb_in_score+$tg_in_score;
    if ($inball>=1 and $inball<=2){
        $goin_place="1~2";
    }else if ($inball>=3 and $inball<=4){
        $goin_place="3~4";
    }else if ($inball>=5 and $inball<=6){
        $goin_place="5~6";
    }else if ($inball>=5 and $inball<=6){
        $goin_place="5~6";
    }else if ($inball>=7 and $inball<=8){
        $goin_place="7~8";
    }else if ($inball>=9 and $inball<=10){
        $goin_place="9~10";
    }else if ($inball>=11 and $inball<=12){
        $goin_place="11~12";
    }else if ($inball>=13 and $inball<=14){
        $goin_place="13~14";
    }else if ($inball>=15 and $inball<=16){
        $goin_place="15~16";
    }else if ($inball>=17 and $inball<=18){
        $goin_place="17~18";
    }else if ($inball>=19){
        $goin_place="19UP";
    }
    if ($m_place==$goin_place){
        $grade=1;
    }else{
        $grade=-1;
    }
    $odds_bst=$grade;
    return $odds_bst;
}
//半全计算：
function odds_half($mb_in_score_v,$tg_in_score_v,$mb_in_score,$tg_in_score,$m_place){
    $grade=0;
    if ($mb_in_score_v>$tg_in_score_v){
        $m_w1="H";
    }elseif ($mb_in_score_v==$tg_in_score_v){
        $m_w1="N";
    }else{
        $m_w1="C";
    }

    if ($mb_in_score>$tg_in_score){
        $m_w2="H";
    }elseif ($mb_in_score==$tg_in_score){
        $m_w2="N";
    }else{
        $m_w2="C";
    }
    $m_w="F$m_w1$m_w2";
    if ($m_place==$m_w){
        $grade=1;
    }else{
        $grade=-1;
    }
    $odds_half=$grade;
    return $odds_half;
}
//独赢计算：
function win_chk($mbin,$tgin,$m_type){
    $grade=0;
    switch ($m_type){
        case 'MH':
            if ($mbin>$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'MC':
            if ($mbin<$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'MN':
            if ($mbin==$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
    }
    $win_chk=$grade;
    return $win_chk;
}
//上半独赢计算：
function win_chk_v($mbin,$tgin,$m_type){
    $grade=0;
    switch ($m_type){
        case 'VMH':
            if ($mbin>$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'VMC':
            if ($mbin<$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'VMN':
            if ($mbin==$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
    }
    $win_chk=$grade;
    return $win_chk;
}
//独赢计算：
function win_chk_rb($mbin,$tgin,$m_type){
    $grade=0;
    switch ($m_type){
        case 'RMH':
            if ($mbin>$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'RMC':
            if ($mbin<$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'RMN':
            if ($mbin==$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
    }
    $win_chk=$grade;
    return $win_chk;
}
//上半独赢计算：
function win_chk_vrb($mbin,$tgin,$m_type){
    $grade=0;
    switch ($m_type){
        case 'VRMH':
            if ($mbin>$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'VRMC':
            if ($mbin<$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
        case 'VRMN':
            if ($mbin==$tgin){
                $grade=1;
            }else{
                $grade=-1;
            }
            break;
    }
    $win_chk=$grade;
    return $win_chk;
}
//标准过关计算：
function odds_p($mid,$mtype,$mrate){
    global $dbLink;
    $winrate=1;
    $mid=explode(',',$mid);
    $mtype=explode(',',$mtype);
    $rate1=explode(',',$mrate);
    for($i=0;$i<sizeof($mid);$i++){
        $sql="select MB_Inball,TG_Inball from foot_match where ID=".$mid[$i];
        $result1 = mysqli_query($dbLink, $sql);
        $rowr = mysqli_fetch_assoc($result1);
        $mb_in=$rowr['MB_Inball'];
        $tg_in=$rowr['TG_Inball'];
        if ($mb_in<>'' and $tg_in<>''){
            $graded=win_chk($mb_in,$tg_in,$mtype[$i]);
            switch ($graded){
                case "1":
                    $winrate=$winrate*($rate1[$i]);
                    break;
                case "-1":
                    $winrate=0;
                    break;
                case "0":
                    $winrate=0;
                    break;
            }
        }else{
            $winrate=0;
        }

    }
    $odd_p=$winrate;
    return $odd_p;
}
//让球过关计算：
function odd_pr($mid,$mtype,$mrate,$mplace,$showtype){
    global $dbLink;
    $winrate=1;
    $mid=explode(',',$mid);
    $mtype=explode(',',$mtype);
    $rate=explode(',',$mrate);
    $letb=explode(',',$mplace);
    $show=explode(',',$showtype);
    $cou=sizeof($mid);
    $count=0;
    for($i=0;$i<$cou;$i++){
        $sql="select MB_Inball,TG_Inball from foot_match where ID=".$mid[$i];
        $result1 = mysqli_query($dbLink, $sql);
        $rowr = mysqli_fetch_assoc($result1);
        $mb_in=$rowr['MB_Inball'];
        $tg_in=$rowr['TG_Inball'];
        $graded=letb_chk($mb_in,$tg_in,$show[$i],$letb[$i],$mtype[$i]);
        switch ($graded){
            case "1":
                $winrate=$winrate*(1+$rate[i]);
                break;
            case "-1":
                $winrate=0;
                break;
            case "0":
                $winrate=$winrate;
                break;
            case "0.5":
                $winrate=$winrate*(1+$rate[i]/2);
                break;
            case "-0.5":
                if ($count>1){
                    $winrate=0;
                }else{
                    $winrate=$winrate*(1/2);
                }

                $count=$count+1;
                break;
        }
    }
    $odd_pr=$winrate;
    return $odd_pr;
}

//独赢 & 进球 大 /小
function win_and_ou($mbin,$tgin,$mtype,$ouNum){
    $type = substr($mtype,3,1);
    $ouStr = substr($mtype,4,1);
    switch ($type){
        case 'H'://主队&大
            $winRes = win_chk($mbin,$tgin,"MH");
            break;
        case 'N':
            $winRes = win_chk($mbin,$tgin,"MN");
            break;
        case 'C':
            $winRes = win_chk($mbin,$tgin,"MC");
            break;
    }
    if($ouStr=='O'){
        $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUH");
    }elseif($ouStr=='U'){
        $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUC");
    }

    if($winRes == 1 && $ouRes==1){
        return 1;
    }else{
        return -1;
    }
}

//关于时间的判断中奖
//最先进球
function firstin($mbin_time,$tgin_time,$type){
    $mbArr = json_decode($mbin_time,true);
    $tgArr = json_decode($tgin_time,true);
    if($type="H"){
        if($mbArr[0] > $tgArr[0]){
            return 1;
        }else{
            return -1;
        }
    }elseif($type="C"){
        if($mbArr[0] > $tgArr[0]){
            return -1;
        }else{
            return 1;
        }
    }
}

//双方球队进球
function doublein($mbin,$tgin,$type){
    if($type=="Y"){
        if($mbin>0 && $tgin>0){
            return 1;
        }else{
            return -1;
        }
    }elseif($type=="N"){
        if($mbin>0 && $tgin>0){
            return -1;
        }else{
            return 1;
        }
    }
}

function win_and_doublein($mbin,$tgin,$mtype){
    switch ($mtype){
        case "MTSHY":
            $winRes = win_chk($mbin,$tgin,"MH");
            $dinRes = doublein($mbin,$tgin,"Y");
            break;
        case "MTSNY":
            $winRes = win_chk($mbin,$tgin,"MN");
            $dinRes = doublein($mbin,$tgin,"Y");
            break;
        case "MTSCY":
            $winRes = win_chk($mbin,$tgin,"MC");
            $dinRes = doublein($mbin,$tgin,"Y");
            break;
        case "MTSHN":
            $winRes = win_chk($mbin,$tgin,"MH");
            $dinRes = doublein($mbin,$tgin,"N");
            break;
        case "MTSNN":
            $winRes = win_chk($mbin,$tgin,"MN");
            $dinRes = doublein($mbin,$tgin,"N");
            break;
        case "MTSCN":
            $winRes = win_chk($mbin,$tgin,"MC");
            $dinRes = doublein($mbin,$tgin,"N");
            break;
    }

    if($winRes == 1 && $dinRes==1){
        return 1;
    }else{
        return -1;
    }
}

//进球 大 / 小 & 双方球队进球
function ou_and_doublein($mbin,$tgin,$mtype,$ouNum){
    switch ($mtype){
        case "OUTAOY":
        case "OUTBOY":
        case "OUTCOY":
        case "OUTDOY":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUH");
            if($ouRes==1){
                $dinRes = doublein($mbin,$tgin,"Y");
                if($dinRes==1){
                    return 1;
                }else{
                    return -1;
                }
            }else{
                return -1;
            }
            break;
        case "OUTAON":
        case "OUTBON":
        case "OUTCON":
        case "OUTDON":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUH");
            if($ouRes==1){
                $dinRes = doublein($mbin,$tgin,"N");
                if($dinRes==1){
                    return 1;
                }else{
                    return -1;
                }
            }else{
                return -1;
            }
            break;
        case "OUTAUY":
        case "OUTBUY":
        case "OUTCUY":
        case "OUTDUY":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUC");
            if($ouRes==1){
                $dinRes = doublein($mbin,$tgin,"Y");
                if($dinRes==1){
                    return 1;
                }else{
                    return -1;
                }
            }else{
                return -1;
            }
            break;
        case "OUTAUN":
        case "OUTBUN":
        case "OUTCUN":
        case "OUTDUN":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUC");
            if($ouRes==1){
                $dinRes = doublein($mbin,$tgin,"N");
                if($dinRes==1){
                    return 1;
                }else{
                    return -1;
                }
            }else{
                return -1;
            }
            break;
            break;
    }
}

//独赢&最先进球
function win_and_firstin($mbin,$tgin,$mbin_time,$tgin_time,$mtype){
    $wType = substr($mtype,3,1);
    $fType = substr($mtype,4,1);
    $winRes = win_chk($mbin,$tgin,"M".$wType);
    if($winRes==1){
        $dinRes = firstin($mbin_time,$tgin_time,$fType);
        if($dinRes==1){
            return 1;
        }else{
            return -1;
        }
    }else{
        return -1;
    }
}

//最多进球的半场
function most_half_ballin($mbin,$tgin,$mbin_hr,$tgin_hr,$mtype){
    $upHalf = $mbin_hr+$tgin_hr;
    $allHalf = $mbin+$tgin;
    $downHalf = $allHalf - $upHalf;
    if($upHalf==$downHalf) return 0;
    if($upHalf > $downHalf && $mtype=="HGH") return 1;
    if($upHalf < $downHalf && $mtype=="HGC") return 1;
    return -1;
}


function win_most_half_ballin($mbin,$tgin,$mbin_hr,$tgin_hr,$mtype){
    $upHalf = $mbin_hr+$tgin_hr;
    $allHalf = $mbin+$tgin;
    $downHalf = $allHalf - $upHalf;
    if($upHalf==$downHalf && $mtype=='MGN') return 1;
    if($upHalf > $downHalf && $mtype=="MGH") return 1;
    if($upHalf < $downHalf && $mtype=="MGC" ) return 1;
    return -1;
}

function double_half_in($mbin,$tgin,$mbin_hr,$tgin_hr,$mtype){
    $mbin_down = $mbin-$mbin_hr;
    $tgin_down = $tgin-$tgin_hr;
    if($mtype=="H"){
        if($mbin_hr>0 && $mbin_down>0){
            return 1;
        }else{
            return -1;
        }
    }
    if($mtype=="C"){
        if($tgin_hr>0 && $tgin_down>0){
            return 1;
        }else{
            return -1;
        }
    }
    return -1;
}

function time3_first_in($mb_time,$tg_time,$M_Start,$wType){
    $mstartNum = strtotime($M_Start);
    $min26Num = $mstartNum + 26*60;

    $mbArr = json_decode($mb_time,true);
    $tgArr = json_decode($tg_time,true);

    $mb = $mbArr[0];
    $tg = $tgArr[0];
    $first = min($mb,$tg);

    if($wType=="N" && count($mbArr)==0 && count($tgArr)==0) return 1;
    if($wType==1){
        if($mb && !$tg && $mb<$min26Num )	return 1;
        if(!$mb && $tg && $tg<$min26Num )	return 1;
        if($mb && $tg && $mb<$tg && $mb<$min26Num)	return 1;
        if($mb && $tg && $mb>$tg && $tg<$min26Num)	return 1;
    }
    if($wType==2){
        if($mb && !$tg && $mb>=$min26Num )	return 1;
        if(!$mb && $tg && $tg>=$min26Num )	return 1;
        if($mb && $tg && $mb<$tg && $tg>$min26Num)	return 1;
        if($mb && $tg && $mb>$tg && $mb>$min26Num)	return 1;
    }

    return -1;
}

function time_first_in($mb_time,$tg_time,$M_Start,$wType){
    $mbJson= json_decode($mb_time,true);
    $tgJson = json_decode($tg_time,true);
    $mb = $mbJson[0];
    $tg = $tgJson[0];

    if($wType=="N" && count($mb)==0 && count($tg)==0) return 1;

    if($mb && !$tg)	$firsTime=$mb;
    if(!$mb && $tg)	$firsTime=$tg;
    if($mb && $tg && $mb<$tg) $firsTime=$mb;
    if($mb && $tg && $mb>=$tg) $firsTime=$tg;

    //var_dump($firsTime);
    $mstartNum = strtotime($M_Start);
    //var_dump($mstartNum);

    $time1end = $mstartNum + 14*60+59;
    $time2S = $mstartNum + 15*60;
    $time2E = $mstartNum + 29*60+59;
    $time3S = $mstartNum + 30*60;
    $time3E = $mstartNum + 45*60;
    $time4S = $mstartNum + 45*60+15*60;
    $time4E = $mstartNum + 59*60+59+15*60;
    $time5S = $mstartNum + 60*60+15*60;
    $time5E = $mstartNum + 74*60+59+15*60;
    $time6S = $mstartNum + 75*60+15*60;
    $time6E = $mstartNum + 90*60+15*60;

    /*
	echo "<br/>";
	echo $time1end;
	echo "<br/>";
	echo $time2S;
		echo "<br/>";
	echo $time2E;
		echo "<br/>";
	echo $time3S;
		echo "<br/>";
	echo $time3E;
		echo "<br/>";
	echo $time4S;
		echo "<br/>";
	echo $time4E;
		echo "<br/>";
	echo $time5S;
		echo "<br/>";
	echo $time5E;
		echo "<br/>";
	echo $time6S;
		echo "<br/>";
	echo $time6E;
		echo "<br/>";
	*/
    if($wType==1 && $firsTime>$mstartNum && $firsTime<$time1end) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    if($wType==1 && $firsTime>$time2S && $firsTime<$time2E) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    if($wType==1 && $firsTime>$mstartNum && $firsTime<$time1end) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    if($wType==1 && $firsTime>$mstartNum && $firsTime<$time1end) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    if($wType==1 && $firsTime>$mstartNum && $firsTime<$time1end) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    if($wType==1 && $firsTime>$mstartNum && $firsTime<$time1end) return 1;
    if($wType==1 && ($firsTime==$mstartNum || $firsTime==$time1end) ) return 1;
    return -1;
}
//双重机会
function change_double($mbin,$tgin,$mtype){
    $mh = win_chk($mbin,$tgin,"MH");
    $mc = win_chk($mbin,$tgin,"MC");
    $mn = win_chk($mbin,$tgin,"MN");
    switch ($mtype){
        case "HN":	if($mh==1 || $mn==1){return 1; }else{return -1;}
        case "CN":	if($mc==1 || $mn==1){return 1; }else{return -1;}
        case "HC":  if($mh==1 || $mc==1){return 1; }else{return -1;}
    }
}

function changeDouble_and_ou($mbin,$tgin,$mtype,$ouNum){
    switch ($mtype){
        case "DUAHO":
        case "DUBHO":
        case "DUCHO":
        case "DUDHO":
        case "DUAHU":
        case "DUBHU":
        case "DUCHU":
        case "DUDHU":
            $dcRes = change_double($mbin,$tgin,"HN");
            if($dcRes==-1) return -1;
            break;
        case "DUACO":
        case "DUBCO":
        case "DUCCO":
        case "DUDCO":
        case "DUACU":
        case "DUBCU":
        case "DUCCU":
        case "DUDCU":
            $dcRes = change_double($mbin,$tgin,"CN");
            if($dcRes==-1) return -1;
            break;
        case "DUASO":
        case "DUBSO":
        case "DUCSO":
        case "DUDSO":
        case "DUASU":
        case "DUBSU":
        case "DUCSU":
        case "DUDSU":
            $dcRes = change_double($mbin,$tgin,"HC");
            if($dcRes==-1) return -1;
            break;
    }
    $ouType = substr($mtype,4,1)=="O" ? "OUH" : "OUC";
    $ouRes = odds_dime($mbin,$tgin,$ouNum,$ouType);
    return $ouRes;
}

function change_and_in_double($mbin,$tgin,$mtype){
    switch ($mtype){
        case "DSHY":
        case "DSHN":
            $dcRes = change_double($mbin,$tgin,"HN");
            if($dcRes==-1) return -1;
            break;
        case "DSCY":
        case "DSCN":
            $dcRes = change_double($mbin,$tgin,"CN");
            if($dcRes==-1) return -1;
            break;
        case "DSSY":
        case "DSSN":
            $dcRes = change_double($mbin,$tgin,"HC");
            if($dcRes==-1) return -1;
            break;
    }
    $ouType = substr($mtype,3,1)=="Y" ? "Y" : "N";
    $diRes=doublein($mbin,$tgin,$ouType);
    return $diRes;
}

function ou_and_oe_in($mbin,$tgin,$mtype,$ouNum){
    switch ($mtype){
        case "OUEAOO":
        case "OUEBOO":
        case "OUECOO":
        case "OUEDOO":
        case "OUEAOE":
        case "OUEBOE":
        case "OUECOE":
        case "OUEDOE":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUH");
            if($ouRes==-1) return -1;
            break;
        case "OUEAUO":
        case "OUEBUO":
        case "OUECUO":
        case "OUEDUO":
        case "OUEAUE":
        case "OUEBUE":
        case "OUECUE":
        case "OUEDUE":
            $ouRes = odds_dime($mbin,$tgin,$ouNum,"OUC");
            if($ouRes==-1) return -1;
    }
    $oeType = substr($mtype,5,1)=="O" ? "ODD" : "EVEN";
    $ouRes = odds_eo($mbin,$tgin,$oeType);
    return $ouRes;
}

//赢得任一半场
function win_any_half($mbin,$tgin,$mbin_hr,$tgin_hr,$mtype){
    $mb_down = $mbin - $mbin_hr;
    $tg_down = $tgin - $tgin_hr;
    if($mtype=="WEH"){
        if($mbin_hr > $tgin_hr || $mb_down > $tg_down ) return 1;
    }
    if($mtype=="WEC"){
        if($mbin_hr < $tgin_hr || $mb_down < $tg_down ) return 1;
    }
    return -1;
}

//赢得所有半场
function win_all_half($mbin,$tgin,$mbin_hr,$tgin_hr,$mtype){
    $mb_down = $mbin - $mbin_hr;
    $tg_down = $tgin - $tgin_hr;
    if($mtype=="WBH"){
        if($mbin_hr > $tgin_hr && $mb_down > $tg_down ) return 1;
    }
    if($mtype=="WBC"){
        if($mbin_hr < $tgin_hr && $mb_down < $tg_down ) return 1;
    }
    return -1;
}

//零失球
function lost_inzero($mbin,$tgin,$mtype){
    if($mtype=="CSH"){//主队0失球，客队为0份
        if($tgin==0) return 1;
    }
    if($mtype=="CSC"){
        if($mbin==0) return 1;
    }
    return -1;
}

//零失球获胜
function win_lost_inzero($mbin,$tgin,$mtype){
    $type = substr($mtype,2,1) == "H"? "CSH":"CSC";
    $losinzerRes = lost_inzero($mbin,$tgin,$type);
    if( $losinzerRes == -1 ) return -1;
    if($mtype=="WNH"){
        if($mbin > $tgin ) return 1;
    }
    if($mtype=="WNC"){
        if($mbin < $tgin ) return 1;
    }
    return -1;
}

//进球最后一位数
function store_last_num($mbin,$tgin,$mtype){
    $mtype = str_replace("R", "", $mtype);
    $store = substr($mtype,2,1) == "H"? $mbin:$tgin;
    $type = substr($mtype,-1,1);
    $storeLastNum = substr($store,-1,1);
    switch ($type){
        case 0:
            if($storeLastNum==0 || $storeLastNum==5){ return 1; }else{ return -1; }
        case 1:
            if($storeLastNum==1 || $storeLastNum==6){ return 1; }else{ return -1; }
        case 2:
            if($storeLastNum==2 || $storeLastNum==7){ return 1; }else{ return -1; }
        case 3:
            if($storeLastNum==3 || $storeLastNum==8){ return 1; }else{ return -1; }
        case 4:
            if($storeLastNum==4 || $storeLastNum==9){ return 1; }else{ return -1; }
    }
    return -1;
}
//球队得分大小
function team_score_ou($mbin1,$tgin1,$dime,$mtype){
    $mtype =$teamFlag=str_replace("R", "", $mtype);
    $ouFlag=substr($mtype,-1,1);//大小
    $dime=str_replace('O','',$dime);
    $dime=str_replace('U','',$dime);
    $teamFlag = substr($teamFlag,2,1);//主客
    if($teamFlag=="H")$total_inball=$mbin1;
    if($teamFlag=="C")$total_inball=$tgin1;
    $odds_inball=$total_inball-$dime;

    switch ($ouFlag){//大
        case 'O':
            if($odds_inball>0){
                $grape=1;
            }elseif($odds_inball<0){
                $grape=-1;
            }else{
                $grape=0;
            }
            break;
        case 'U'://小
            if($odds_inball>0){
                $grape=-1;
            }elseif($odds_inball<0){
                $grape=1;
            }else{
                $grape=0;
            }
            break;
    }
    $odds_dime=$grape;
    return $odds_dime;
}

//净胜球数
function team_net_profit($mbin,$tgin,$mtype){
    if($mtype=="WM0"){
        if($mbin==$tgin && $mbin==0){
            return 1;
        }else{
            return -1;
        }
    }
    if($mtype=="WMN"){
        if($mbin==$tgin && $mbin>0){
            return 1;
        }else{
            return -1;
        }
    }
    if($mtype=="WMHOV"){
        if($mbin-$tgin>3){
            return 1;
        }else{
            return -1;
        }
    }
    if($mtype=="WMCOV"){
        if($tgin-$mbin>3){
            return 1;
        }else{
            return -1;
        }
    }

    $team=substr($mtype,2,1);
    $num=substr($mtype,3,1);

    if($team=="H"){
        if($mbin-$tgin==$num) return 1;
    }elseif($team=="C"){
        if($tgin-$mbin==$num) return 1;
    }

    return -1;

}

//三项让球投注
/*
主场[-2]
主场[-1]=主场让一球半
主场[+1]=主场受让半球
主场[+2]=主场受让一球半
客场[-2]=客场让二球半
客场[-1]
客场[+1]=客场受让半球
客场[+2]
和局[-2]
和局[-1]=主场净胜一球
和局[+1]
和局[+2]=客场净胜二球
*/
function rb_three_bet($mbin,$tgin,$mtype,$showType){
    $symbol = substr($showType,0,1);
    $number = substr($showType,1,1);
    if($mtype=="W3H"){
        if($symbol=='+'){
            if($mbin + $number - 0.5 > $tgin){ return 1; }else{  return -1;  }
        }elseif($symbol=='-'){
            if($mbin - $number - 0.5 > $tgin){ return 1; }else{  return -1;  }
        }
    }elseif($mtype=="W3C"){
        if($symbol=='+'){
            if($tgin + $number - 0.5 > $mbin){ return 1; }else{  return -1;  }
        }elseif($symbol=='-'){
            if($tgin - $number - 0.5 > $mbin){ return 1; }else{  return -1;  }
        }
    }elseif($mtype=="W3N"){
        //符号判断主客队 + 客队净胜 - 主队净胜，净胜几个球就是几个，多了也是输
        if($symbol=='+'){
            if($tgin - $mbin == $number){ return 1; }else{  return -1;  }
        }elseif($symbol=='-'){
            if($mbin - $tgin == $number){ return 1; }else{  return -1;  }
        }
    }


}

/**
 * 重新生成历史报表
 * @param $userid
 * @param $username
 * @param $StartTime
 */
function update_bet_history_report($userid, $username,$StartTime){

    if(isset($StartTime) && $StartTime!='') {

        //重新生成某天-某天的报表数据，包含 开始天，不包含 结束天
        $start_time = strtotime($StartTime);

        if($StartTime > date("Y-m-d", strtotime("-1 day"))) {
//            exit("起始时间不能大于昨天");
            return array('status'=>false, 'msg'=>'起始时间不能大于昨天');
        }

        $stop_time = strtotime($StartTime."+1 day");

        $result = countall($start_time, $stop_time, $userid, $username, false);
    }else{
        $result = array('status'=>false, 'msg'=>'StartTime参数有误');
    }
    return $result;
}

/**
 *
 * 根据条件生成历史报表
 * @param date $StartTime
 * @param date $stop_time
 * @param int $userid
 * @param string $username
 * @param boolean $reGeneral
 *
 */
function countall($StartTime, $stop_time, $userid, $username, $reGeneral=false){
    global $dbMasterLink, $dbLink;

    $conn = $dbMasterLink;

    //如果结束时间大于当天凌晨，则将当天凌晨当做结束时间
    if($stop_time > strtotime(date("Y-m-d"))) {
        $stop_time = strtotime(date("Y-m-d"));
    }

//    echo date('YmdHis')."  插入库开始\n";
    //首先，从历史报表里面清楚掉数据，再重新计算
    $sql = " DELETE from ".DBPREFIX."web_report_history_report_data where userid='{$userid}' and M_Date >= '".date("Y-m-d",$StartTime)."' and M_Date < '".date("Y-m-d",$stop_time)."'";
    mysqli_query($conn, $sql);
    $sql = " DELETE from ".DBPREFIX."web_report_history_report_flag where order_date >= '".date("Y-m-d",$StartTime)."' and order_date < '".date("Y-m-d",$stop_time)."' ";
    mysqli_query($conn, $sql);

    for($i=1; $i<=50; $i++) {

        $end_time = $StartTime + 3600*24;

        $result=mysqli_query($conn, "START TRANSACTION");
        if (!$result) {
            return array('status'=>false, 'msg'=>'事务开启失败');
        }

        // 全部捞出，然后根据（游戏类别、用户名、日期）将数据归类
        if($end_time <= $stop_time) {

//            @error_log(date('Y-m-d H:i:s')."----------------------计算注单量、下注总额、输赢汇总 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_user_renew.php.log');
            $sql = "select Userid, M_Name as username, Agents, World, Corprator, Super, Admin, Active as game_code,sum(1) as count_pay,sum(BetScore) as total, sum(M_Result) as user_win,M_date,BetTime as bet_time,now() as create_time from ".DBPREFIX."web_report_data 
            where Userid = '{$userid}' and M_Date='".date('Y-m-d',$StartTime)."' and testflag=0 and `Cancel`=0 
            group by username,Active";
            $result=mysqli_query($dbLink, $sql);
            if(!$result) {
                mysqli_query($dbLink, "ROLLBACK");
//                die('计算报表数据失败11！ ' . mysqli_error($conn));
                return array('status'=>false, 'msg'=>'计算报表数据失败11！' );
            }
            $cou = mysqli_num_rows($result);
            if ($cou>0){

                $data_total=[];
                while ($row = mysqli_fetch_assoc($result)){
                    $data_total[]=$row;
                }

//                @error_log(date('Y-m-d H:i:s')."----------------------计算有效下注总额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, sum(VGOLD) as valid_money, Active as game_code from ".DBPREFIX."web_report_data 
                where Userid = '{$userid}' and M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 
                group by username,Active";
                $result=mysqli_query($dbLink, $sql);
                if(!$result) {
                    mysqli_query($conn, "ROLLBACK");
//                    die('计算报表数据失败22！ ' . mysqli_error($conn));
                    return array('status'=>false, 'msg'=>'计算报表数据失败22！' );
                }
                $cou = mysqli_num_rows($result);
                if ($cou>0) {
                    $data_valid_money = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data_valid_money[] = $row;
                    }
                }

//                @error_log(date('Y-m-d H:i:s')."----------------------计算返水有效投注金额 Start".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                // valid_money 有效下注总额（用户，分类）
                $sql = "select Userid, M_Name as username, BetType_en, M_Rate, VGOLD, Active as game_code from ".DBPREFIX."web_report_data 
                where Userid = '{$userid}' and  M_Date='".date('Y-m-d',$StartTime)."' and 
                checked = 1 and testflag=0 and `Cancel`=0 ";
                $result=mysqli_query($dbLink, $sql);
                if(!$result) {
                    mysqli_query($conn, "ROLLBACK");
//                    die('计算报表数据失败33！ ' . mysqli_error($conn));
                    return array('status'=>false, 'msg'=>'计算报表数据失败33！' );
                }
                $cou = mysqli_num_rows($result);
                if ($cou>0) {
                    $data_rebate_valid_money=[];
                    while ($row = mysqli_fetch_assoc($result)) {
                        if($row['BetType_en'] == '1x2' or $row['BetType_en'] == 'Odd/Even' or $row['BetType_en']=='1st Half 1x2' or
                            $row['BetType_en'] =='Running 1x2' or $row['BetType_en']=='1st Half Running 1x2'){

                            if ($row['M_Rate']>=1.5){ // 单双、独赢、半场独赢，赔率小于1.5的不算入有效投注
                                $data_rebate_valid_money[]=$row;
                            }
                        }else{
                            if ($row['M_Rate']>=0.5){ // 0.5以下的不算入有效投注
                                $data_rebate_valid_money[]=$row;
                            }
                        }
                    }

                }

                // 按照用户名、游戏类别 归类下注金额、有效投注金额、有效返水投注金额
                foreach ($data_total as $k => $v){

                    foreach ($data_valid_money as $k1 => $v1){

                        if ($v['game_code'] == $v1['game_code'] && $v['username'] == $v1['username']){
                            $data_total[$k]['valid_money'] += $v1['valid_money'];
                        }
                    }

                    foreach ($data_rebate_valid_money as $k3 => $v3){
                        if ($v['game_code'] == $v3['game_code'] && $v['username'] == $v3['username']){
                            $data_total[$k]['valid_money_rebate'] += $v3['VGOLD'];
                        }
                    }

                }

                foreach ($data_total as $k =>$v){

                    $sql = "insert into ".DBPREFIX."web_report_history_report_data(userid, username, Agents, World, Corprator, Super, Admin, game_code,count_pay,total,valid_money,valid_money_rebate,user_win,M_date,bet_time,create_time)
                    VALUE( ".$v['Userid'].",'".$v['username']."','".$v['Agents']."','".$v['World']."','".$v['Corprator']."','".$v['Super']."','".$v['Admin']."',
                    '".$v['game_code']."','".$v['count_pay']."','".$v['total']."','".$v['valid_money']."','".$v['valid_money_rebate']."','".$v['user_win']."','".$v['M_date']."','".$v['bet_time']."','".$v['create_time']."' ) ";

//                    @error_log(date('Y-m-d H:i:s')."----------------------记录报表 end".PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
//                    @error_log($sql.PHP_EOL, 3, '/tmp/group/history_daily_report_general_hg_member_renew.log');
                    $result=mysqli_query($conn, $sql);
                    if(!$result) {
                        mysqli_query($conn, "ROLLBACK");
//                        die('计算报表数据失败！ ' . mysqli_error($conn));
                        return array('status'=>false, 'msg'=>'计算报表数据失败44！' );
                    }
                }

            }

            if($reGeneral) {
                $sql = " insert into ".DBPREFIX."web_report_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 2) ";
            }else {
                $sql = " insert into ".DBPREFIX."web_report_history_report_flag(order_date, flag) value('".date("Y-m-d",$StartTime)."', 1) ";
            }

            $result=mysqli_query($conn, $sql);
            if(!$result) {
                mysqli_query($conn, "ROLLBACK");
//                echo('插入计算成功表示符失败！' . mysqli_error($conn));
                return array('status'=>false, 'msg'=>'插入计算成功表示符失败！' );
                continue;
            }else {
                mysqli_query($conn, "COMMIT");
            }
        }
        else {
//            echo "会员 {$username}，".date("Y-m-d", $StartTime-86400)."所有的计算完成！\n";
            return array('status'=>true, 'msg'=>"会员 {$username}，".date("Y-m-d", $StartTime-86400)."所有的计算完成！" );
            break;
        }
        $StartTime = $end_time;
    }
}
?>