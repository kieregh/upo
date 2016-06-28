<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Module: 			Home
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.detail.php");
	
	$right_panel=true;
	$module = 'detail';
	
	$postId = isset($_GET['postId'])? (int)($_GET['postId']): 0;

	$objdetail = new Detail($module,NULL,$postId);
	
	$winTitle = $objdetail->title.' - '.SITE_NM;
    $headTitle = $objdetail->title;
	
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	$mainContent = $objdetail->detailPageContent();
	require_once(DIR_THEME."default.nct");
?>