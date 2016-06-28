<?php
require_once("../../includes/config.php");
require_once("class.addpost.php");
$table='tbl_post';

if(isset($_REQUEST['captcha'])) {
	if($_REQUEST['captcha']!=$_SESSION['rand_code']) {
		$valid='false'; // Not Allowed
	} else {
		$valid='true'; // Allowed
	}
	echo $valid;
}

if(isset($_POST['categoryName'])){
	$categoryName = trim($db->filtering($_POST['categoryName'],'input','string',''));
	$categoryNameCheckSQL = $db->select("tbl_categories", "id", "categoryName = '".$categoryName."'");
	$resCnt = mysql_num_rows($categoryNameCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";

	exit;
}

if ( isset($_REQUEST["categories"]) ) {
	$arr = array();
	//select($table,$cols="*",$where=NULL,$groupBy=NULL,$order=NULL,$isDisplay=NULL){
	$res = $db->select("tbl_categories","*","isActive='y' AND languageId='$lId'");
	if( mysql_num_rows($res) > 0 ) {
		$arr["categories"] = array();
		while( $row = mysql_fetch_assoc($res) ) {
			$arr["categories"][] = $db->filtering($row["categoryName"],'output','string','');
		}
	}

	echo json_encode($arr);
}

if(isset($_POST['catId']))
{
	$content = NULL;
	$id = $_POST['catId'];
		$catname = $db->select("tbl_categories", "submissionText", "categoryName = '".$id."'",'','',0);


			$catedesc = mysql_fetch_assoc($catname);
			if($catedesc["submissionText"]!="")
			{
			$content .= '<div class="flclear"></div><div><strong>Submission text:</strong>&nbsp;'.$catedesc["submissionText"].'<div class="flclear"></div></div>';
			}
		else{
			$content.='';
		}
	echo $content;
}
exit;
?>