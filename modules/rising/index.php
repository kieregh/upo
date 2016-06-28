<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Module: 			Rising
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.rising.php");
	require_once("../login/class.login.php");
	$right_panel=true;
	
	
	$module = 'rising';
	$winTitle = '뜨고 있는 링크 - '.SITE_NM;
    $headTitle = ''.RISING.'';
	
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
    
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$objcategory = new rising($module, 0, 0,$objPost);		
	$mainContent = $objcategory->categoryContent($pageNo);
	
	require_once(DIR_THEME."default.nct");
?>