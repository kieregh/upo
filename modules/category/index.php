<?php
require_once "../../includes/config.php";
require_once "class.category.php";
require_once "../login/class.login.php";
$right_panel = true;
$ad_slider = true;
$module = 'category';

$pageNo = isset($_GET['pageno']) ? (int) $_GET['pageno'] : 0;
//$catId = isset($_GET['catId']) ? (int) $_GET['catId'] : 0;
$cat = isset($_GET['cat']) ? urldecode($_GET['cat']) : '';
$mod = isset($_GET["mod"]) ? $db->filtering($_GET["mod"], 'input', 'string', '') : "hot";
$searchType = (isset($_GET['searchType']) && $_GET['searchType'] != "") ? $_GET['searchType'] : "";
$catId = getCategoryid($cat);
$catUserId = getTableValue($db, 'tbl_categories', "id ='" . $catId . "'", 'uId');
$createdDate = getTableValue($db, 'tbl_categories', "id ='" . $catId . "'", 'createdDate');
$createdDate = date("d-m-Y", strtotime($createdDate));
$cateDescription = getTableValue($db, 'tbl_categories', "id ='" . $catId . "'", 'description');
$queryUserC = mysql_fetch_assoc($db->query("SELECT SUM(CASE WHEN unsubscribeCat  LIKE '%" . $catId . "%' THEN 0 ELSE 1 END) AS totalSubUsers FROM `tbl_users`"));
$queryUserCount = $queryUserC['totalSubUsers'];
$catUserName = getTableValue($db, 'tbl_users', "id ='" . $catUserId . "'", 'username');
$_SESSION["catselectId"] = $catId;
$objcategory = new category($module, $catId, $searchType);

$winTitle = $objcategory->cateTitle . ' - ' . SITE_NM;
$headTitle = $objcategory->cateTitle;

$metaTag = getMetaTags(array("description" => $winTitle,
	"keywords" => $headTitle,
	"author" => SITE_NM));

$mainContent = $objcategory->categoryContent($catId, $pageNo, $mod);

require_once DIR_THEME . "default.nct";
?>