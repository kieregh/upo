<?php
	require_once("../../includes/config.php");
	require_once("class.top.php");
	require_once("../login/class.login.php");
	$right_panel=true;

	$module = 'top';
	$winTitle = '고득점 - '.SITE_NM;
    $headTitle = ''.TOP.'';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;

	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$searchType = (isset($_GET['searchType'])&& $_GET['searchType']!="")?$_GET['searchType']:"";

	$objcategory = new top($module, 0, $searchType);
	$mainContent = $objcategory->categoryContent($pageNo);

	require_once(DIR_THEME."default.nct");
?>