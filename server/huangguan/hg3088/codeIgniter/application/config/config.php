<?php  if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");

/**
 * Index File
 */
$config['index_page'] = '';
$config['charset'] = 'UTF-8';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/**
 *Error Logging Directory Path
 */
$config['log_path'] = '';
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['encryption_key'] = 'kkf$@193RFHllap0';


/**
 * Global XSS Filtering
 */
$config['global_xss_filtering'] = TRUE;
$config['rewrite_short_tags'] = FALSE;