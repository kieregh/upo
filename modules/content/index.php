<?php
	#############################################################
	# Project:			BCD  / Client side
	# Developer:		Yadav Chetan
	# Page: 			Content page
	# Started Date: 	26-Aug-2013
	##############################################################

	require_once("../../includes/config.php");
	require_once("class.content.php");
	
 	$pId = isset($_GET["id"]) ? $_GET["id"] : 0;
	$module = 'content';
	$table = "tbl_content";
	//$templatePath = $page;
	$objPost = new stdClass();
	

	$mainObj = new Content($pId);
	
	$mainContent = $mainObj->getCont();
	
	$pageTitle = $mainObj->pageTitle;
	$metaDesc = $mainObj->metaDesc;	
	$metaKeyword = $mainObj->metaKeyword;
		
	$winTitle = $pageTitle.' - '.SITE_NM;
	$headTitle = $pageTitle;

		
	$metaTag = getMetaTags(array("description"=>$metaDesc,
			"keywords"=>$metaKeyword,
			"author"=>SITE_NM));
	

	
	require_once(DIR_THEME . "default.nct");
	
?>