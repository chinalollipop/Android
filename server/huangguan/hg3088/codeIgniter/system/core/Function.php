<?php
 
if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");

/**
 * Remove Invisible Characters
 *
 * @param string
 * @param bool
 *
 * @return string
 */
function remove_invisible_characters($str, $url_encoded = TRUE) {
    $non_displayables = array();

    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/'; //url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';  //url encoded 16-31
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}




/**
 * 递归转义字符以及xss
 * @param string|array $string
 * @param class $security
 *
 * @return $string
 */
function daddslashes_new($string, $security = null) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = daddslashes_new($val, $security);
        }
    } else {
        if ($security !== null && $security instanceof CI_Security) {
            $string = $security->xss_clean($string); // 转义字符以及xss
        }
        $string = addslashes($string);
    }
    return $string;
}

/**
 * Remove Invisible Characters
 *
 * @param string
 * @param class $security
 *
 * @return bool
 */
function mysql_injection_check($string, $security) {
    if (empty($string)) {
        return true;
    }
    if (!($security instanceof CI_Security)) {
        echo "您输入的参数不合法";
        exit;
    }

    $result = false;
    if(is_array($string)) {
        foreach($string as $key => $val) {
            mysql_injection_check($val, $security);
        }
    } else {
	        $result = $security->mysqlIds($string); // 转义字符以及xss

	        if ($result == false) {
	            if (IS_AJAX) {
	                $json['sError']=1;
	                $json['sMsg']="操作失败:您输入的参数不合法";
	                echo json_encode($json);
	                exit;
	            } else {
	                echo "您输入的参数不合法";
	                exit;
	            }
	            
	        }
    }
}

/**
 * 递归检测请求数据中的危险字符串
 * @param array $array
 * @param array $words
 * @return void
 */
function sanitizeRequestRecursive(array $array, $words = array()) {
    foreach ($array as $key => $value) {
        if (in_array($key, array('controller', 'action'))) continue;
        if (is_numeric($value)) continue;

        if (is_array($value)) {
            call_user_func('sanitizeRequestRecursive', $value, $words);
        } else {
            foreach ($words as $group) {
                $filterResult = array_filter($group, function($w) use ($value) {
                    return (stripos($value, $w) !== false ? true : false);
                });

                if (count($filterResult) == count($group)) {
                    echo "您输入的参数不合法";
                    exit;
                }
            }
        }
    }
}

/* End of file index.php */
/* Location: ./index.php */
