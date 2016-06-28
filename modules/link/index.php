<?php
	require_once("../../includes/config.php");
	require_once("class.link.php");
	require_once("../login/class.login.php");
	$right_panel=true;
	$ad_slider = true;
	$module = 'link';

	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$link = isset($_GET['link']) ? urldecode($_GET['link']) : NULL;
 	$mod = isset($_GET["mod"])?$db->filtering($_GET["mod"],'input','string',''):"";
	$searchType = (isset($_GET['searchType'])&& $_GET['searchType']!="")?$_GET['searchType']:"";
	$_SESSION["catselectId"] = $link;
    $objLink = new Link($module, $link,$searchType);

	$winTitle = $link.' - '.SITE_NM;
    $headTitle = $link;

	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));

	$mainContent = $objLink->linkContent($link,$pageNo,$mod);

	require_once(DIR_THEME."default.nct");
?>