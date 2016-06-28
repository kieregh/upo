<?php
require_once("../../includes/config.php");
require_once("class.subting.php");
$table='tbl_categories';

if(isset($_POST['categoryName'])){
	$categoryId = $_POST['categoryId'];
	$categoryName = trim($db->filtering($_POST['categoryName'],'input','string','ucwords'));
	$categoryNameCheckSQL = $db->select("tbl_categories", "id", "categoryName = '".$categoryName."' AND id != '".$categoryId."'");
	$resCnt = mysql_num_rows($categoryNameCheckSQL);
	if($resCnt!=0)
		echo "false";
	else
		echo "true";

	exit;
}


exit;
?>