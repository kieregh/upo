<?php
	#############################################################
	# Project:			Bookitt
	# Developer ID:		43
	# Page: 			membership
	# Started Date: 	31-Dec-2013
	##############################################################
	$reqAuth = true;
	require_once("../../includes/config.php");
	require_once("class.membership.php");
	$left_panel=false;
	$right_panel=true;
	$userType = getUserType($sessUserId);
	if($userType=='y'){
		redirectPage(SITE_URL);
	}
	$module = 'membership';
	$table = 'tbl_post';
	$winTitle 	= 'Membership Plans - '.SITE_NM;
	$headTitle 	= 'Membership Plans';	
    $metaTag 	= getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	require_once( 'paypalfunctions.php' );
	$mainObj = new MembershipPlan($module, $objPost,0);		
	$mainContent = $mainObj->membershipPlan();
		
	//$mainObj = new addPost($objPost, $fields);
	/*if($type == "link")
	{
		$mainContent = $mainObj->getFromLink();
	}
	else{
 	$mainContent = $mainObj->getForm();			
	}*/
	
	
	require_once(DIR_THEME."default.nct");
?>