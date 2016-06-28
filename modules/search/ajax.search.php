<?php 
require_once("../../includes/config.php");
require_once("class.search.php");
$table='tbl_post';
if(isset($_REQUEST['captcha'])) {
	if($_REQUEST['captcha']!=$_SESSION['rand_code']) {
		$valid='false'; // Not Allowed
	} else {
		$valid='true'; // Allowed
	}
	echo $valid;
}

if ( isset($_REQUEST["categories"]) ) {
	$arr = array();
	//select($table,$cols="*",$where=NULL,$groupBy=NULL,$order=NULL,$isDisplay=NULL){
	$res = $db->select("tbl_categories","*","isActive='y'");
	if( mysql_num_rows($res) > 0 ) {
		$arr["categories"] = array();
		while( $row = mysql_fetch_assoc($res) ) {
			$arr["categories"][] = $row["categoryName"];
		}
	}
	
	echo json_encode($arr);
}

exit;
?>