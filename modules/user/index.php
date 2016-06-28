<?php
	#############################################################
	# Project:			Reddit
	# Developer ID:		43
	# Page: 			user
	# Started Date: 	2-Nov-2013
	##############################################################
	//$reqAuth = true;
	
	require_once("../../includes/config.php");
	require_once("class.user.php");
	$left_panel=false;
	$right_panel=true;
	
	
	$module = 'user';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$table = 'tbl_post';
	//print_r($_GET);
	$type = isset($_GET['type']) ? $_GET['type'] : NULL;
	
	$winTitle = 'User - '.SITE_NM;
	$headTitle = 'User';	
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	$mainObj = new user($objPost, $fields);
	$mainContent = $mainObj->userHeader();
	
	if($type == "comments")
	{
		$mainContent .= $mainObj->postlistingcomments($pageNo);
	}
	else if($type == "liked")
	{
		$mainContent .= $mainObj->postlistingliked($pageNo);
	}
	else if($type == "disliked")
	{
		$mainContent .= $mainObj->postlistingdisliked($pageNo);
	}
	else if($type == "hidden")
	{
		$mainContent .= $mainObj->postlistinghidden($pageNo);
	}
	else if($type == "saved")
	{
		$mainContent .= $mainObj->postlistingsaved($pageNo);
	}
	else if($type == "overview")
	{
		$username= (isset($_GET["user"]))?$db->filtering($_GET["user"],"input","text",""):"";
		if($username!="")
		{
			$userId = getTableValue($db,"tbl_users","username='".$username."'","id");
		}
		else
		{
			$userId=$sessUserId;
		}
		$mainContent .= $mainObj->postlistingoverview($pageNo,$userId);
	}
	else if($type == "history")
	{
		$mainContent .= $mainObj->postlistinghistory($pageNo);
	}
	else if($type == "submitted")
	{
		$mainContent .= $mainObj->postlistingsubmitted($pageNo);
	}
	
	require_once(DIR_THEME."default.nct");
?>