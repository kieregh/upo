<?php
	#############################################################
	# Project:			Bookitt  / Client side
	# Developer:		#43
	# Page: 			Forgot password
	# Started Date: 	28-Aug-2013
	#############################################################
	require_once("../../includes/config.php");
	require_once("class.fpass.php");
	if(isset($sessUserId) && $sessUserId!=0) {
		redirectPage(SITE_URL.'home');
		exit;
	}
	$module = 'fpass';
	$left_panel = false;
	$footer_panel = true;
	
	$winTitle = 'Forgot Password - '.SITE_NM;
    $headTitle = 'Forgot Password';
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
			
	$mainObj = new Fpass(NULL);	
	
if(isset($_POST["submitPass"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		
		extract($_POST);
		$objPost->email = isset($email) ? base64_encode($db->filtering($email,'input','string', '')) : '';
		
		if($objPost->email != "") {
			
			$mainObj = new Fpass($objPost);
			$loginReturn = $mainObj->forgotProdedure();
			
			switch ($loginReturn) {
				case 'succForgotPass' : { $_SESSION["msgType"] = array('type'=>'suc','var'=>'Your request accepted, please check your mail.'); redirectPage(SITE_URL.'login'); break; }
				case 'wrongEmail' : { $_SESSION["msgType"] = array('type'=>'err','var'=>'Email is not valid.'); redirectPage(SITE_URL.'resetpassword'); break; }
				case 'inactivatedUser' : { $_SESSION["msgType"] = array('type'=>'err','var'=>'inactivatedUser'); redirectPage(SITE_URL.'resetpassword'); break; }
			    case 'unapprovedUser' : { $_SESSION["msgType"] = array('type'=>'err','var'=>'unapprovedUser'); redirectPage(SITE_URL.'resetpassword'); break; }
			}
		}
		else
		{
			$_SESSION["msgType"] = array('type'=>'err','var'=>'fillAllvalues'); 
			redirectPage(SITE_URL.'resetpassword'); 
			exit;
		}
	}
	$mainContent = $mainObj->getForm();
	require_once(DIR_THEME."default.nct");
	
?>