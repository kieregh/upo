<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		#43
	# Page: 			Search
	# Started Date: 	21-Dec-2013
	##############################################################
	$reqAuth = false;
	
	require_once("../../includes/config.php");
	require_once("class.search.php");
	$left_panel=false;
	$right_panel=true;
	$module = 'search';
	$table = 'tbl_post';
	//$type = $_GET['type'];
	
	$winTitle = 'Search - '.SITE_NM;
	$headTitle = 'Search';	
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$mainContent = NULL;
	$mainObj = new addPost($objPost, $fields);
	
	if(isset($_POST["search"])&& $_POST["search"] != "") {
		
		extract($_POST);
		
		$objPost->search = isset($search) ? $db->filtering($search,'input','string', '') : '';
		
		$mainContent = $mainObj->getSearchResult();
	}
	else
	{
		$mainContent .= "".RNF."";
	}
	
	if(isset($_POST["btn_search_cat"]) && $_POST["btn_search_cat"] != "") {
		extract($_POST);
		$objPost->search_name 	= isset($search_name) ? $db->filtering($search_name,'input','string', '') : '';
		$objPost->cat_nm 		= isset($cat_nm) ? $db->filtering($cat_nm,'input','string', '') : '';
		
		$mainContent = $mainObj->getSearchResult("advance");
	}
	
	if(isset($_POST["submitLink"]) && $_SERVER["REQUEST_METHOD"] == "POST") {}
	
	require_once(DIR_THEME."default.nct");
?>