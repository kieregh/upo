<?php
	session_start();
	set_time_limit(0);
	session_set_cookie_params (3600);
	session_name('bookitt');
	
	global $db, $fields, $module, $adminUserId, $sessUserId,$facebookLogin;
	
	
	if($_SERVER["SERVER_NAME"] == 'nct25') {
		define("DB_HOST","localhost");
		define("DB_USER","root");
		define("DB_PASS","");
		define("DB_NAME","bookitt");		
		define('SITE_URL','http://nct25/bookitt/');
		define('ADMIN_URL',SITE_URL.'admin/');
		define('DIR_URL',$_SERVER["DOCUMENT_ROOT"].'/bookitt/');
		define('DIR_ADMIN',DIR_URL.'/admin/');
	}
	else if($_SERVER["SERVER_NAME"] == 'griting.ncryptedprojects.com') {
		//error_reporting(0);
		define("DB_HOST","griting.ncryptedprojects.com");
		define("DB_USER",'ncrypted_redditc');
		define("DB_PASS",'~D(E^l=ThTWH');
		define("DB_NAME","ncrypted_redditclone");
		define('SITE_URL','http://griting.ncryptedprojects.com/');
		define('ADMIN_URL', SITE_URL.'admin/');
		define('DIR_URL',$_SERVER["DOCUMENT_ROOT"].'/');
		define('DIR_ADMIN',DIR_URL.'admin/');
	}
	else{
		define("DB_HOST","demo.ncryptedprojects.com");
		define("DB_USER",'ncrypted_bktdemo');
		define("DB_PASS",'S?!yJGW[H]-p');
		define("DB_NAME","ncrypted_bookittdemo");
		define('SITE_URL','http://demo.ncryptedprojects.com/bookitt/');
		define('ADMIN_URL', SITE_URL.'admin/');
		define('DIR_URL',$_SERVER["DOCUMENT_ROOT"].'/bookitt/');
		define('DIR_ADMIN',DIR_URL.'admin/');
	}
	
	require_once(DIR_URL.'includes/constant.php');
	require_once(DIR_URL.'includes/help-constant.php');	
	require_once(DIR_FUN.'function.php');
	require_once(DIR_FUN.'dbMain.php');
	require_once(DIR_FUN.'fields.php'); 

	define("DB_CHAR","utf8");
	define("DB_DEBUG",false);
	
	$fields = new fields();
	$db = new main(DB_HOST,DB_USER,DB_PASS,DB_NAME,DB_CHAR,DB_DEBUG);

	/*Do Not change if membership directory is module*/
	define('PAYPAL_RETURN_URL','modules/membership/return.php?paypal=paid');
	define('PAYPAL_CANCEL_URL','modules/membership/return.php?paypal=cancel');
	define ('BUSINESS_NAME','Demo Store');
?>