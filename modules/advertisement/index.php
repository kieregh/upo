<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Module: 			Advertisement
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.advertisement.php");
	require_once("../login/class.login.php");
	$right_panel=true;
	$module = 'advertisement';
	$winTitle = 'Advertisements - '.SITE_NM;
    $headTitle = 'Advertisements';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
    
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$objcategory = new advertisement($module, 0, 0,$objPost);		
	$mainContent = $objcategory->categoryContent($pageNo);
	
	require_once(DIR_THEME."default.nct");
?>