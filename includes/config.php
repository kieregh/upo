<?php
	session_start();
	set_time_limit(0);
	session_set_cookie_params (3600);
	session_name('bookitt');

	define("DB_CHAR","utf8");
	define("DB_DEBUG",false);
	ini_set('default_charset', 'UTF-8');
	header('Content-Type: text/html;charset=utf-8');
	error_reporting(0);
	/*<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />*/
	date_default_timezone_set('Asia/Seoul');
	global $db, $fields, $module, $adminUserId, $sessUserId, $sessUsername, $isJoinPopup,$lId;

	$header_panel   = true;
	$footer_panel   = true;
	$left_panel	 	= false;
	$right_panel	= true;
	$isPopup		= false;

	$reqAuth 		= isset($reqAuth) ? $reqAuth : false;
	$clsContainer 	= isset($clsContainer) ? $clsContainer : true;
	$isJoinPopup 	= isset($_SESSION["isJoinPopup"]) ?  false : true;
	$adminUserId 	= (isset($_SESSION["adminUserId"]) && $_SESSION["adminUserId"] > 0 ? (int)$_SESSION["adminUserId"] : 0);
	$sessUserId 	= (isset($_SESSION["sessUserId"]) && $_SESSION["sessUserId"] > 0 ? (int)$_SESSION["sessUserId"] : 0);
	$sessUsername 	= (isset($_SESSION["sessUsername"]) && $_SESSION["sessUsername"] != '' ? $_SESSION["sessUsername"] : NULL);
	$msgType 		= isset($_SESSION["msgType"])?$_SESSION["msgType"]:NULL;
	$_SESSION["catselectId"]='';

	$lId = (isset($_SESSION["lId"]) && $_SESSION["lId"] > 1 ? (int)$_SESSION["lId"] : $_SESSION["lId"]=1);

	require_once('database.php');

	require_once(DIR_URL.'includes/language/'.$lId.'.php');

	require_once('help-constant.php');
	require_once('functions/function.php');
	require_once('functions/dbMain.php');
	require_once('functions/fields.php');
	$db = new main(DB_HOST,DB_USER,DB_PASS,DB_NAME,DB_CHAR,DB_DEBUG);
	require_once('constant.php');
	Authentication($reqAuth);

	if(domain_details('dir') == 'admin') {
		$left_panel=true;
		require_once(DIR_ADM_INC.'functions/admin-function.php');
		require_once(DIR_ADM_MOD.'home/class.home.php');
		$objHome = new Home($module, 0);
	}
	else {
		require_once(DIR_MOD.'home/class.home.php');
		$objHome = new Home($module, 0);
	}

	$fields = new fields();
	$objPost = new stdClass();
	/*Paypal Configuration*/
	define('PAYPAL_API_USERNAME',"gguzze_api1.gmail.com");
	define('PAYPAL_API_PASSWORD',"23FBDAFUAUB2J5LA");
	define('PAYPAL_API_SIGNATURE',"AnXQgjH.dN4uIckRi5vJdqya4SCYAgiySopt.w1UBf0LXUS9BGdUg921");

	/*Do Not change if membership directory is module*/
	define('PAYPAL_RETURN_URL','modules/membership/return.php?paypal=paid');
	define('PAYPAL_CANCEL_URL','modules/membership/return.php?paypal=cancel');
	define ('BUSINESS_NAME','유포닷컴');
	/*url over*/
	//echo SITE_URL.PAYPAL_RETURN_URL;
	/*Return url and cancel url for advertisement start*/
	define('ADV_CURRENCY','USD');
	define('PAYPAL_ADV_MODE','live');
	define('PAYPAL_RETURN_URL_ADV',SITE_URL.'prefer/process.php');
	//echo SITE_URL.PAYPAL_RETURN_URL_ADV;
	define('PAYPAL_CANCEL_URL_ADV',SITE_URL.'prefer/index.php');
	/*Return url and cancel url for advertisemtn over*/
	/*Paypal configuration over*/
?>