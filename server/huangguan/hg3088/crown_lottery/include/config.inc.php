<?php
define("ROOT_DIR",  dirname(dirname(dirname(__FILE__))));
require_once ROOT_DIR.'/common/database.php';
require_once ROOT_DIR.'/common/config.php';
require_once ROOT_DIR.'/common/function.php';

// 过滤非法字符
$str="and|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database|<|>|=|\"|&|$|#|{|}|[|]|;|?";  //非法字符
$arr=explode("|",$str);//数组非法字符，变单个  
foreach ($_REQUEST as $key=>$value){
	for($i=0;$i<sizeof($arr);$i++){
		if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //检验传递数据是否包含非法字符
            if($i == "0") {
                if(!preg_match("/(\s+and\s*)|(\s*and\s+)/", strtolower($_REQUEST[$key]))) {
                    continue;
                }
            }
            if($i == "6") {
                if(!preg_match("/(\s+by\s*)|(\s*by\s+)/", strtolower($_REQUEST[$key]))) {
                    continue;
                }
            }		
		    echo "Illegal Character ".$arr[$i];
            exit;
		}
	} 
}

