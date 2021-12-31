<?php
/**
 * 老会员输赢额度重新统计
 * Date: 2018/8/16
 */
define("CONFIG_DIR", dirname(dirname(__FILE__)));
require CONFIG_DIR."/app/agents/include/config.inc.php";

if (php_sapi_name() == "cli")
{
    $start = isset($argv[1]) ? $argv[1] : 0; // 默认保留时间段数据
    $count = isset($argv[2]) ? $argv[2] : 100;
    $result = mysqli_query($dbLink, 'SELECT COUNT(*) AS mem_count FROM ' .DBPREFIX.MEMBERTABLE);
    $row = mysqli_fetch_assoc($result);
    echo "\n【" . date('Y-m-d H:i:s') . "】会员总数【{$row['mem_count']}】";
    while($start <= $row['mem_count']){
        updateDate($start, $count);
        $start += $count;
    }
}

/**
 * 更新会员输赢额度
 * @param $limit
 */
function updateDate($start, $count){
    global $dbLink, $dbMasterLink;
    // 1.读取现有会员用户名
    $sql = 'SELECT `UserName`, `WinLossCredit` FROM ' . DBPREFIX.MEMBERTABLE.' ORDER BY `ID` ASC LIMIT ' . $start . ',' . $count;
    $result = mysqli_query($dbLink, $sql);
    $aUser = [];
    while ($row = mysqli_fetch_assoc($result)){
        $aUser[] = $row['UserName'];
    }

    // 2. 批量统计
    $sql = 'SELECT `UserName`, SUM(`moneyf`) AS `total_before`, SUM(`currency_after`) AS `total_after`, `Type` FROM ' . DBPREFIX . 'web_sys800_data WHERE `UserName` IN ("' . implode('","', $aUser) . '") 
                AND `Checked` = 1 AND `Type` IN ("S", "T") AND `discounType` NOT IN (3,4) GROUP BY `UserName`,`Type`';
    $result = mysqli_query($dbLink, $sql);
    $data = [];
    while ($record = mysqli_fetch_assoc($result)){
        if(isset($record['Type']))
            $data[$record['UserName']][$record['Type']] = sprintf('%.2f', abs($record['total_after'] - $record['total_before']));
    }
    echo "【" . date('Y-m-d H:i:s') . "】查询：【{$start}-{$count}】,存取的会员总数【" . count($data) . "】\n";
    // 3.批量更新
    $userST = [];
    $sql = 'UPDATE ' . DBPREFIX.MEMBERTABLE.' SET `WinLossCredit` = CASE `UserName`';
    foreach ($data as $key => &$value){
        if($value['S'] || $value['T']){ // 更新输赢额度
            $credit = sprintf('%.2f', $value['S'] - $value['T']);
            $sql .= ' WHEN "' . $key . '" THEN ' . $credit;
        }
        $userST[] = $key;
        echo "【" . date('Y-m-d H:i:s') . "】更新【{$key}】,存款【{$value['S']}】,取款【{$value['T']}】，输赢额度【{$credit}】\n";
    }
    $sql .= ' END WHERE `UserName` IN ("' . implode('","', $userST) . '")';
    $updateNum = mysqli_query($dbMasterLink, $sql);

    echo "【" . date('Y-m-d H:i:s') . "】更新成功【{$updateNum}】\n";
}


