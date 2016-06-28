<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		43
	# Page: 			Login
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.login.php");
	if($sessUserId > 0) {
		redirectPage(SITE_URL.'home');
	}
	$right_panel=false;
	$module = 'login';
	$table = 'tbl_user';
	$winTitle = '로그인 - '.SITE_NM;
    $headTitle = 'Login';
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	if(isset($_POST["submitLogin"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		
		extract($_POST);
		$objPost->email = isset($email) ? $db->filtering(base64_encode($email),'input','string', '') : '';
		$objPost->password = isset($password) ? $db->filtering($password,'input','string', '') : '';
		//$objPost->username = isset($username) ? $db->filtering($username,'input','string','')  : '';
		//$objPost->remember = isset($remember) ? $db->filtering($remember,'input','string', '') : '';
		$objPost->remember =isset($_POST['remember']) ? $db->filtering($_POST['remember'],'input','string', '') : 'n';
		
		if($objPost->email != "" && $objPost->password != "") {
			$objUser = new Login('', $objPost);
			$loginReturn = $objUser->loginSubmit();
			$msgType = array('type'=>'err','var'=>$loginReturn);
		}
		else {
			$msgType = array('type'=>'err','var'=>"fillAllvalues");
		}
	}
	if(isset($_COOKIE["remember"]) && $_COOKIE["remember"] == 'y') {
		$objPost->email = isset($_COOKIE["email"]) ? $_COOKIE["email"] : '';
		$objPost->password = isset($_COOKIE["password"]) ? base64_decode($_COOKIE["password"]) : '';
		$objPost->remember = isset($_COOKIE["remember"]) ? $_COOKIE["remember"] : '';	
	}
	
	$mainObj = new Login($module, 0, $objPost);	
	$mainContent = $mainObj->getForm();
	require_once(DIR_THEME."default.nct");	
?>