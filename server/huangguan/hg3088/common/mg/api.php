<?

//error_reporting(E_ALL);
//ini_set('display_errors','On');
require_once "config.php";

function callApi ($sub_url, $auth, $http_method, $contentType, $data) {
	global $root_url, $timezone, $currency, $tx_id, $language;
	try {
		$api_url = $root_url . $sub_url;
		$dataStr = $data;

		$headers[] = "";
		$headers[] = "Authorization:{$auth}";
		$headers[] = "X-DAS-TZ:{$timezone}";
		$headers[] = "X-DAS-CURRENCY:{$currency}";
		$headers[] = "X-DAS-TX-ID:{$tx_id}";
		$headers[] = "X-DAS-LANG:{$language}";
		
		if ($contentType == "json") {
			$headers[] = "Content-Type:application/json";
			$dataStr = json_encode($data);
		} else if ($contentType == "query_string"){
			$dataStr = http_build_query($data);
		}

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataStr);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$resp = curl_exec($ch);
		$failed = curl_errno($ch);
		
		curl_close($ch);
		
		if($failed) {
			return generateResponse(false, "System error."); 
		}
		
		$resp_json = json_decode($resp, true);
		if ($resp_json["error"] != null) {
			return generateResponse(false, $resp_json["error"]);
		} else if ($resp_json["data"] != null) {
			return generateResponse(true, $resp_json["data"]);
		} else {
			return generateResponse(true, $resp_json);
		}
		
	} catch (Exception $e) {
		return generateResponse(false, "system error.");
	}
}

function generateResponse ($success, $body) {
    $resp_str = array(
        "success" => $success,
        "body" => $body
    );
    return $resp_str;
}

function doLogin ()  {
	global $api_username, $api_password, $client_id, $secret;
	$sub_url = "/oauth/token";
	$auth = "Basic " . base64_encode($client_id . ":" . $secret);
	$http_method = "POST";
	$data = array(
		"grant_type" => "password",
		"username" => $api_username,
		"password" => $api_password
  	);

	return callApi($sub_url, $auth, $http_method, "query_string", $data);
}

function doRefreshToken ($refresh_token)  {
	global $client_id, $secret;
	
	if (empty($refresh_token))
		return generateResponse (false, "Invalid input.");
	
	$sub_url = "/oauth/token";
	$auth = "Basic " . base64_encode($client_id . ":" . $secret);
	$http_method = "POST";
	$data = array(
		"grant_type" => "refresh_token",
		"client_id" => $client_id,
		"refresh_token" => $refresh_token
  	);

	return callApi($sub_url, $auth, $http_method, "query_string", $data);
}

function createMember ($access_token, $account_id, $username, $password, $ext_ref)  {
	
	if (empty($access_token) || empty($account_id) || empty($username) || empty($password))
		return generateResponse (false, "Invalid input.");
	
	$sub_url = "/v1/account/member";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
		"parent_id" => $account_id,
		"username" => $username,
		"password" => $password,
		"ext_ref" => $ext_ref
  	);

	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function getAccountDetails ($access_token, $account_id)  {

	if (empty($access_token) || empty($account_id))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/account/{$account_id}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;

	return callApi($sub_url, $auth, $http_method, null, $data);
}

function getAccountDetailsByExtRef ($access_token, $ext_ref)  {

	if (empty($access_token) || empty($ext_ref))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/account?ext_ref={$ext_ref}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;
	
	return callApi($sub_url, $auth, $http_method, null, $data);
}

function getAllMember ($access_token, $account_id, $member, $recursive, $page_size, $page, $sort_by, $desc, $stats)  {

	if (empty($access_token) || empty($account_id) || empty($member) || empty($recursive) || empty($page_size) || empty($page) || empty($sort_by) || empty($desc) || empty($stats))
		return generateResponse (false, "Invalid input.");

	$sub_url = "/v1/account/{$account_id}/children?account_id={$account_id}&member={$member}&recursive={$recursive}&page_size={$page_size}&page={$page}&sort_by={$sort_by}&desc={$desc}&stats={$stats}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;

	return callApi($sub_url, $auth, $http_method, null, $data);
}

function getLaunchGameUrl ($access_token, $member_account_id, $game_id, $language)  {
    global $app_id;
	if (empty($access_token) || empty($member_account_id) || empty($game_id) || empty($language))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/launcher/item";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
		"account_id" => $member_account_id,
		"item_id" => $game_id,
		"app_id" => $app_id,
		"login_context" => array(
			"lang" => $language
		)
  	);

	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function getDemoLaunchGameUrl ($access_token, $game_id, $language)  {
    global $app_id;
	if (empty($access_token) || empty($game_id) || empty($language))
		return generateResponse (false, "Invalid input.");

	$sub_url = "/v1/launcher/item";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
	    "demo" => true,
        "item_id" => $game_id,
        "app_id" => $app_id,
		"login_context" => array(
			"lang" => $language
		)
  	);

	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function getLaunchGameUrlWithExtRef ($access_token, $ext_ref, $game_id, $language)  {

	if (empty($access_token) || empty($ext_ref) || empty($game_id) || empty($language))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/launcher/item";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
		"ext_ref" => $ext_ref,
		"app_item_id" => $game_id,
		"login_context" => array(
			"meta_data" => array(
				"ul" => $language
			)
		)
  	);

	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function createTransactionByAccountId ($access_token, $member_account_id, $external_ref, $category, $sub_category, $type, $amount)  {
	global $default_balance_type;

	if (empty($access_token) || empty($member_account_id) || empty($external_ref) || empty($category) || empty($type) || empty($amount))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/transaction";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
		array(
			"account_id" => $member_account_id,
			"external_ref" => $external_ref,
			"category" => $category,
			"sub_category" => $sub_category,
			"balance_type" => $default_balance_type,
			"type" => $type,
			"amount" => $amount,
			"ext_item_id" => ""
		)
  	);
  	
	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function createTransactionByAccountExtRef ($access_token, $account_ext_ref, $external_ref, $category, $sub_category, $type, $amount)  {
	global $default_balance_type;

	if (empty($access_token) || empty($account_ext_ref) || empty($external_ref) || empty($category) || empty($type) || empty($amount))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/transaction";
	$auth = "Bearer " . $access_token;
	$http_method = "POST";
	$data = array(
		array(
			"account_ext_ref" => $account_ext_ref,
			"external_ref" => $external_ref,
			"category" => $category,
			"sub_category" => $sub_category,
			"balance_type" => $default_balance_type,
			"type" => $type,
			"amount" => $amount,
			"ext_item_id" => ""
		)
  	);
  	
	return callApi($sub_url, $auth, $http_method, "json", $data);
}

function getWalletDetails ($access_token, $account_id)  {

	if (empty($access_token) || empty($account_id))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/wallet?account_id={$account_id}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;

	return callApi($sub_url, $auth, $http_method, null, $data);
}

function getTransactionByExtRef ($access_token, $ext_ref)  {

	if (empty($access_token) || empty($ext_ref))
		return generateResponse (false, "Invalid input.");
		
	$sub_url = "/v1/feed/transaction?external_ref={$ext_ref}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;
	
	return callApi($sub_url, $auth, $http_method, null, $data);
}

function getTransactionByQueries ($access_token, $account_id, $start_time, $end_time, $page, $page_size)  {

	if (empty($access_token) || empty($account_id) || empty($start_time) || empty($end_time) || empty($page) || empty($page_size))
		return generateResponse (false, "Invalid input.");
		
	global $default_wallet_code, $default_balance_type;
	$sub_url = "/v1/feed/transaction?account_id={$account_id}&start_time={$start_time}&end_time={$end_time}&page={$page}&page_size={$page_size}&wallet_code={$default_wallet_code}&balance_type={$default_balance_type}";
	$auth = "Bearer " . $access_token;
	$http_method = "GET";
	$data = null;

	return callApi($sub_url, $auth, $http_method, null, $data);
}

//function getTransactionByCompanyId ($access_token, $company_id, $include_transfers, $start_time, $end_time, $page, $page_size=0, $include_end_round)  {
function getTransactionByCompanyId ($access_token, $company_id, $include_transfers, $start_time, $end_time, $include_end_round, $page, $page_size)  {

    if (empty($access_token) || empty($company_id) || empty($start_time) || empty($end_time) || empty($page) || empty($page_size))
        return generateResponse (false, "Invalid input.");

    $sub_url = "/v1/feed/transaction?company_id={$company_id}&start_time={$start_time}&end_time={$end_time}&page={$page}&page_size={$page_size}&include_transfers={$include_transfers}&include_end_round={$include_end_round}";
    $auth = "Bearer " . $access_token;
    $http_method = "GET";
    $data = null;

    return callApi($sub_url, $auth, $http_method, null, $data);
}

?>