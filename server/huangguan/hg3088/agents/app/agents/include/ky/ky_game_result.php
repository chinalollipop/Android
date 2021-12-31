<?php
/**
 * 解析开元棋牌（不同游戏类型解析规则不同）
 * @param $kindId
 * @param $cardValue
 * @return string
 */
define("CONFIG_DIR", '../../../');

function getCardValue($kindId, $cardValue){
    $cardMean = '';
    switch ($kindId){
        case 220: // 炸金花
        case 230: // 极速炸金花
        case 860: // 三公
            $cardMean = getPoker($cardValue);
            break;
        case 830: // 抢庄牛牛
        case 870: // 通比牛牛
        case 8150: // 看四张抢庄牛牛
            $cardMean = getPoker($cardValue, 5);
            break;
        case 380: // 幸运五张
            $cardMean = getPoker($cardValue, 5, 0, $kindId);
            break;
        case 390: // 射龙门
            $cardMean = getPoker($cardValue, 3, 0);
            break;
        case 600: // 21点
            $cardMean = getPoker($cardValue, 3, 0, $kindId);
            break;
        case 610: // 斗地主
            $cardMean = getPoker($cardValue, 17, 2, $kindId);
            break;
        case 620: // 德州扑克
            $cardMean = getPoker($cardValue, 2, 0, $kindId);
            break;
        case 630: // 十三水
            $cardMean = getPoker($cardValue, 8, 0, $kindId);
            break;
        case 720: // 二八杠
            $cardMean = getPoker($cardValue, 2, 3, $kindId);
            break;
        case 730: // 抢庄牌九
            $cardMean = getPoker($cardValue, 2, 3, $kindId);
            break;
        case 740: // 二人麻将, 乐游棋牌
        case 8120: // 血战到底, 乐游棋牌
        case 8180: // 宝石消消乐, 乐游棋牌
            $cardMean = getPoker($cardValue,'',0,$kindId);
            break;
        case 880: // 欢乐红包
            $cardMean = getPoker($cardValue, 1, 3, $kindId);
            break;
        case 890: // 看牌抢庄牛牛, 乐游棋牌
            $cardMean = getPoker($cardValue, 5, 1, $kindId);
            break;
        case 900: // 龙虎
            $cardMean = getPoker($cardValue, 1, 0, $kindId);
            break;
        case 910: // 百家乐
            $cardMean = getPoker($cardValue, 3, 0, $kindId);
            break;
        case 930: // 百人牛牛, 乐游棋牌
           // echo $cardValue.'--';
            $cardMean = getPoker($cardValue, 5, 0, $kindId);
            break;
        case 950: // 红黑大战, 乐游棋牌
            $cardMean = getPoker($cardValue,3,0,$kindId);
            break;
    }
    return $cardMean;
}

function getPoker($cardValue, $cardCount = 3, $lastCard = 1, $kindId = 0)
{
    global $kyPoker;
    $cardMean = $otherCard = $publicCard = '';
    //echo $lastCard.'----';
    switch ($lastCard){
        case 0:
            $cardMean['winChair'] = '';
            break;
        case 1:
            $cardMean['winChair'] = ' (赢家-' . substr($cardValue, -1, 1) . ')';
            break;
        case 2:
            $cardMean['winChair'] = ' (地主-' . substr($cardValue, -1, 1) . ')';
            break;
        case 3:
            $cardMean['winChair'] = ' (庄家-' . substr($cardValue, -1, 1) . ')';
            break;
    }
    if($kindId == 380 && strlen($cardValue) == 10 || $kindId == 910 || $kindId == 600){

    }elseif($kindId == 620){
        $otherCard = substr($cardValue, -10, 10);
        $cardValue = substr($cardValue, 0, -10);
    }elseif($kindId == 880){
        $cardValue = substr($cardValue, 0, -2);
    }elseif($kindId == 930){ // 百人牛牛, 乐游棋牌
        $cardValue = substr($cardValue, 0, 55);
    }
    else{
        $cardValue = substr($cardValue, 0, -1);
    }


    if($kindId == 600){
        $player = explode(',', $cardValue);
        $players = $piersCards = $playerPier = [];
        foreach ($player as $key => $handCard){
            $chair = substr($handCard, 0, 1);
            $cards = substr($handCard, 1);
            $players[$chair] = $cards;
        }
        foreach ($players as $chair => $cards){
            $betCards = explode('|', $cards);
            foreach ($betCards as $key => $betCard){
                $betPosition = '';
                if($betCard){
                    if($key > 0){
                        $betPosition = substr($betCard, 0, 1);
                        $betCard = substr($betCard, 1);
                    }
                    $piers = explode('-', $betCard);
                    foreach ($piers as $p => $pier){
                        if($pier){
                            $piersCard = '';
                            $piersPoker = str_split($pier, 2);
                            foreach ($piersPoker as $card){
                                $piersCard .= '<img src="'.CONFIG_DIR.'images/ky/poker/' . $kyPoker[$card[0]] . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
                            }
                            $piersCards[$chair][$betPosition ? $betPosition : $key][$p] = $piersCard;
                        }
                    }
                }
            }
        }
        foreach ($piersCards as $chair => $playerCards){
            foreach ($playerCards as $betChair => $cards){
                $playerPier[$chair][$betChair] = implode(',', $cards);
            }
        }
        foreach ($playerPier as $chair => $cards){
            $cardMean['cardChair'][$chair == 0 ? '庄' : $chair] = implode('|', $cards);
        }
    }elseif($kindId == 630 ){
        $player = explode(';', substr($cardValue, 0, -1));
        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $piers = explode(',', $handCard);
                foreach ($piers as $key => $pier){
                    if($key < 3){
                        $poker = str_split(substr($pier, 0, -1), 2);
                        foreach ($poker as $card){
                            $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/poker/' . $kyPoker[$card[0]] . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
                        }
                        $playerCard .= ',';
                    }
                }
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }
        }
    }elseif($kindId == 720){
        $player = str_split($cardValue, $cardCount);
        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $ma = str_split($handCard, 2);
                foreach ($ma as $key => $card){
                    $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/mahjong/' . $card[0] . '.png" alt="" style="width:25px;height:20px;"><img src="'.CONFIG_DIR.'images/ky/mahjong/' . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
                }
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }
        }
    }elseif($kindId == 730){
        $player = str_split($cardValue, $cardCount * 2);
        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $paijiu = str_split($handCard, 2);
                foreach ($paijiu as $key => $card){
                    $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/paigow/paijiu_card_' . $card . '.png" alt="" style="width:25px;height:20px;">';
                }
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }
        }
    }elseif($kindId == 880){
        $player = str_split($cardValue, $cardCount);
        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/dice/tou' . $handCard . '.png" alt="" style="width:25px;height:20px;">';
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }

        }
    }elseif($kindId == 900 ){
        $poker = str_split($cardValue, $cardCount * 2);
        foreach ($poker as $key => $card){
            $playerCard = '';
            if($key < 2){
                $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/poker/' . $kyPoker[$card[0]] . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
                $keyMain = $key == 0 ? '龙' : '虎';
                $cardMean['cardChair'][$keyMain] = $playerCard;
            }
        }
    }elseif ($kindId == 740 ){ // 二人麻将，乐游棋牌
        $player = explode(',', substr($cardValue, 0, -1));

        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $piers = explode(',', $handCard);
                foreach ($piers as $key => $pier){
                    $poker = str_split($pier, 2);
                    foreach ($poker as $card){
                        $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/majiang/ermj_s0_' . $card . '.png" alt="" style="width:25px;height:35px;">';
                    }
                    $playerCard .= ',';

                }
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }
        }



    }elseif ($kindId == 8120){ // 血战到底，乐游棋牌
        $player = explode(',', substr($cardValue, 0, -1));

        foreach ($player as $chair => $handCard){
            $playerCard = '';
            if($handCard != '0'){
                $piers = explode(',', $handCard);
                foreach ($piers as $key => $pier){
                    $poker = str_split($pier, 2);
                    foreach ($poker as $card){
                        $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/mj_xzdd/' . $card . '.png" alt="" style="width:25px;height:35px;">';
                    }
                    $playerCard .= ',';

                }
                $cardMean['cardChair'][$chair + 1] = $playerCard;
            }
        }


    }elseif ($kindId == 8180 ){ // 宝石消消乐
            $poker = str_split($cardValue, 2);
            $playerCard = '';
            foreach ($poker as $key => $card){
                if($card>0){
                    $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/bsxxl/' . ($key+1) . '.png" alt="" style="width:25px;height:20px;">*'.substr($card,1);
                }

            }
        $cardMean['cardChair'][$key] = $playerCard;


    } else{
        if($kindId == 930){ // 百人牛牛, 乐游棋牌
            $player = str_split($cardValue, 11);
        }else{
            $player = str_split($cardValue, $cardCount * 2);
        }

        foreach ($player as $chair => $handCard){
            if($kindId == 910 && $chair > 1){
                break;
            }
            if($kindId == 930) { // 百人牛牛, 乐游棋牌
                $handCard = substr($handCard,1,10) ;
            }

            $playerCard = '';
            if($handCard != '0'){
                $poker = str_split($handCard, 2);
                foreach ($poker as $key => $card){
                    if($card != '0')
                        $playerCard .= '<img src="'.CONFIG_DIR.'images/ky/poker/' . $kyPoker[$card[0]] . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
                }
                if($kindId == 380){
                    $keyMain = '换牌' . ($chair == 0 ? '前' : '后');
                }elseif($kindId == 610 && $chair == 3){
                    $keyMain = '地主牌';
                }elseif($kindId == 910){
                    $keyMain = $chair == 0 ? '闲' : '庄';
                }elseif($kindId == 930){  // 百人牛牛, 乐游棋牌
                    switch ($chair){
                        case '0':
                            $keyMain = '天';
                            break;
                        case '1':
                            $keyMain = '地';
                            break;
                        case '2':
                            $keyMain = '玄';
                            break;
                        case '3':
                            $keyMain = '黄';
                            break;
                        case '4':
                            $keyMain = '庄';
                            break;

                    }
                }elseif($kindId == 950){ // 红黑大战
                    $keyMain = $chair == 0 ? '红方' : '黑方';
                }else{
                    $keyMain = $chair + 1;
                }
                $cardMean['cardChair'][$keyMain] = $playerCard;
            }
        }
        if($otherCard){ // 德州扑克公共牌
            $poker = str_split($otherCard, 2);
            foreach ($poker as $key => $card){
                if($card != '0')
                    $publicCard .= '<img src="'.CONFIG_DIR.'images/ky/poker/' . $kyPoker[$card[0]] . $card[1] . '.png" alt="" style="width:25px;height:20px;">';
            }
            $cardMean['cardChair']['公共牌'] = $publicCard;
        }
    }
    return $cardMean;
}