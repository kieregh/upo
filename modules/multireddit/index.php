<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#51
	# Module: 			Category
	# Started Date: 	18-Dec-2013
	##############################################################
	$reqAuth = true;
	require_once("../../includes/config.php");
	require_once("class.multireddit.php");
	$right_panel=true;
	$module = 'multireddit';
	$winTitle = '채널모음 - '.SITE_NM;
    $headTitle = 'Multireddit';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$multiRedditName = isset($_POST['multiRedditName'])?$_POST['multiRedditName']:"";//add
    $redditId = isset($_GET['redditId'])?$_GET['redditId']:'';//page
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$objcategory = new multireddit($module, 0, $objPost,$redditId);		
	if(isset($_POST['multiRedditName']))
	{
		$objPost->multiRedditName = $multiRedditName;
		$mainContent = $objcategory->addMultiReddit($objPost);
	}else if(isset($_GET['redditId']))
	{
		$mainContent = $objcategory->multiRedditContent($pageNo);
	}
	
	require_once(DIR_THEME."default.nct");
?>