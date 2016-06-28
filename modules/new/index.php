<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Module: 			Category
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.new.php");
	require_once("../login/class.login.php");
	$right_panel=true;

	
	$module = 'new';
	$winTitle = '최신 - '.SITE_NM;
    $headTitle = ''.NEWPOST.'';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
    
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$objcategory = new category($module, 0, 0,$objPost);		
	$mainContent = $objcategory->categoryContent($pageNo);
	
	require_once(DIR_THEME."default.nct");
?>