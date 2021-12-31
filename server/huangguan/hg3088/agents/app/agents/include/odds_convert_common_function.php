<?php 

/*
 * 此文件所有函数都来自正网
 * JS赔率转换相关函数
 * */

$odd_type = 'H';
$showior = 100;
$iorpoints = 2;

//$EO_ior= get_other_ioratio("H", 1.27*1-1 , 2.62*1-1 , 100);
//$EO_ior= get_other_ioratio("H", 1.24*1-1 , 2.65*1-1 , 100);
//$EO_ior= get_other_ioratio("H", 1.21*1-1 , 2.68*1-1 , 100);
//$EO_ior= get_other_ioratio("H", 1.18*1-1 , 2.71*1-1 , 100);
//$EO_ior = get_other_ioratio("H", 1.15*1-1 , 2.74*1-1 , 100);
//$EO_ior = get_other_ioratio("H", 0*1-1 , 0*1-1 , 100);

//$ior_EOO = $EO_ior[0]*1+1;
//$ior_EOE = $EO_ior[1]*1+1;

function get_other_ioratio_js($odd_type="H", $iorH, $iorC , $showior=100){
        $out = Array();
        if($iorH!="" || $iorC!=""){
            $out =chg_ior_js($odd_type,$iorH,$iorC,$showior);
        }else{
            $out[0]=$iorH;
            $out[1]=$iorC;
        }
        return $out;
}

function chg_ior_js($odd_f,$iorH,$iorC,$showior){
        $iorH = floor(($iorH*1000)+0.001) / 1000;
        $iorC = floor(($iorC*1000)+0.001) / 1000;

        $ior = Array();
        if($iorH < 11) $iorH *=1000;
        if($iorC < 11) $iorC *=1000;
        //$iorH=parseFloat($iorH);
        //$iorC=parseFloat($iorC);
        switch($odd_f){
            case "H":	//香港變盤(輸水盤)
                $ior = get_HK_ior_js($iorH,$iorC);
                break;
            case "M":	//馬來盤
                $ior = get_MA_ior_js($iorH,$iorC);
                break;
            case "I" :	//印尼盤
                $ior = get_IND_ior_js($iorH,$iorC);
                break;
            case "E":	//歐洲盤
                $ior = get_EU_ior_js($iorH,$iorC);
                break;
            default:	//香港盤
                $ior[0]=$iorH ;
                $ior[1]=$iorC ;
        }
        $ior[0] /= 1000;
        $ior[1] /= 1000;

        $ior[0] = printf_js(Decimal_point_js($ior[0],$showior),$iorpoints=2);
        $ior[1] = printf_js(Decimal_point_js($ior[1],$showior),$iorpoints=2);
        //alert("odd_f="+odd_f+",iorH="+iorH+",iorC="+iorC+",ouH="+ior[0]+",ouC="+ior[1]);
        return $ior;
    }

function get_HK_ior_js( $H_ratio, $C_ratio){
        $out_ior = Array();
        $line = '';$lowRatio='';$nowRatio='';$highRatio='';$nowType="";
        if ($H_ratio <= 1000 && $C_ratio <= 1000){
            $out_ior[0] = floor($H_ratio/10+0.0001)*10;;
            $out_ior[1] = floor($C_ratio/10+0.0001)*10;;
            return $out_ior;
        }
        $line = 2000 - ( $H_ratio + $C_ratio );

        if ($H_ratio > $C_ratio){
            $lowRatio = $C_ratio;
            $nowType = "C";
        }else{
            $lowRatio = $H_ratio;
            $nowType = "H";
        }
        if (((2000 - $line) - $lowRatio) > 1000){
            //對盤馬來盤
            $nowRatio = ($lowRatio + $line) * (-1);
        }else{
            //對盤香港盤
            $nowRatio=(2000 - $line) - $lowRatio;
        }

        if ($nowRatio < 0){
            $highRatio = floor(abs(1000 / $nowRatio) * 1000) ;
        }else{
            $highRatio = (2000 - $line - $nowRatio) ;
        }
        if ($nowType == "H"){
            $out_ior[0] = floor($lowRatio/10+0.0001)*10;
            $out_ior[1] = floor($highRatio/10+0.0001)*10;
            //out_ior[0]=lowRatio;
            //out_ior[1]=highRatio;
        }else{
            $out_ior[0] = floor($highRatio/10+0.0001)*10;
            $out_ior[1] = floor($lowRatio/10+0.0001)*10;
            //out_ior[0]=highRatio;
            //out_ior[1]=lowRatio;
        }
        return $out_ior;
    }

function Decimal_point_js($tmpior,$show){
    $sign="";
    $sign =(($tmpior < 0)?"Y":"N");
    $tmpior = (floor(abs($tmpior) * $show + 1 / $show )) / $show;
    return ($tmpior * (($sign =="Y")? -1:1)) ;
}

function printf_js($vals,$points){ //小數點位數
    $vals = "".$vals;
    $cmd = Array();
    $cmd=explode(".",$vals);
            if(count($cmd)>1){
                for($ii=0; $ii< ($points-$cmd[1].length); $ii++ ) $vals = $vals."0";
            }else{
                $vals=$vals.".";
                for ($ii=0;$ii<$points;$ii++)$vals=$vals."0";
            }
            return $vals;
    }