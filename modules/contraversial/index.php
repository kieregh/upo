<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Module: 			Contraversial
	# Started Date: 	18-Dec-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.contraversial.php");
	require_once("../login/class.login.php");
	$right_panel=true;
	
	
	$module = 'contraversial';
	$winTitle = '화제 - '.SITE_NM;
    $headTitle = ''.CONTROVERSIAL.'';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
    
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$searchType = (isset($_GET['searchType'])&& $_GET['searchType']!="")?$_GET['searchType']:"";
	
	$objcategory = new contraversial($module,0,$searchType);		
	$mainContent = $objcategory->categoryContent($pageNo);
	
	require_once(DIR_THEME."default.nct");
?>