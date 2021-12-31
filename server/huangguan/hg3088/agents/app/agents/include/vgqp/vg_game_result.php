<?php
/**
 * 解析VG棋牌（不同游戏类型解析规则不同）
 * @param $gametype
 * @param $gameInfo
 * @param $info
 * @param $betpoint
 * @return string
 */
function getCardValue($gametype, $gameInfo, $betpoint){
    $cardMean = '';
    switch ($gametype){
        case 1: // 斗地主
            // gameInfo 三家的手牌和底牌   info1 中记录了此局的底分和翻倍信息
            $player = explode(';' , $gameInfo);
            $cardMean = '玩家:' . $player['0'] . ' 对手1:' . $player['1'] . ' 对手2:' . $player['2'] . ' 底牌:' . $player['3'];
            break;
        case 3: // 抢庄牛牛
            // gameInfo 玩家的牌型和手牌
            $player = explode(';' , $gameInfo);
            $cardMean = '牌型:' . $player['0'] . ' 手牌:' . $player['1'];
            break;
        case 4: // 百人牛牛
            // gameinfo 中记录了此局每个注点的发牌信息  betpoint 中记录了此玩家的投注信息
            $betpoints = json_decode($betpoint , true);
            $position = [ 0 => '庄家',1 => '福',2 => '禄',3 => '寿',4 => '禧' ];
            foreach ($betpoints as $key => $value) {
                $betValue = implode(',' , $value['betValue']);
                $cardMean .= ' 位置:'.$position[$value['index']] . ',下注额:' . $betValue ;
            }
            break;
        case 7: // 竞咪楚汉德州
            // gameinfo 中记录了发牌信息    info1 字段记录了此局的赢牌注点、牌型和牌
            $cardMean = '牌信息:'.$gameInfo;
            break;
        case 8: // 推筒子
            $player = json_decode($gameInfo , true);
            foreach ($player as $key => $value) {
                $cardMean .= ' 座位号:' . $value['seat'] . ',玩家手牌:'.$value['handcards'] ;
            }
            break;
        case 9: // 加倍斗地主
            $player = explode(';' , $gameInfo);
            $cardMean = '玩家:' . $player['0'] . ' 对手1:' . $player['1'] . ' 对手2:' . $player['2'] . ' 底牌:' . $player['3'];
            break;
        case 10: // 保险楚汉德州
            $cardMean = '牌信息:'.$gameInfo;
            break;
        case 11: // 血战麻将
            // gameinfo 中记录了结算时 4 家的手牌
            $search = array("minggang","bugang","angang","peng");
            $replace = array("明杠","补杠","暗杠","碰");
            $gameInfo = str_ireplace($search , $replace , $gameInfo);
            $player = explode(';' , $gameInfo);
            $cardMean = '玩家位置:' . $player['0'] . ' 手牌1:' . $player['1'] . ' 手牌2:' . $player['2'] . ' 手牌3:' . $player['3']. ' 手牌4:' . $player['4'];
            break;
        case 12: // 炸金花
            // gameinfo 中记录了玩家的牌型和手牌
            $player = explode(';' , $gameInfo);
            $cardMean = '玩家:' . $player['0'] . ' 对手1:' . $player['1'] . ' 对手2:' . $player['2'] . ' 对手3:' . $player['3'];
            break;
    }
    return $cardMean;
}
