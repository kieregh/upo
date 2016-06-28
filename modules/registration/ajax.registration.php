<?php
$content = NULL;
require_once("../../includes/config.php");
include("class.registration.php");
$fields = new fields();
$updFields = new stdClass();
$tableName = 'tbl_users';
if(isset($_POST['email'])){
	$email = trim($db->filtering($_POST['email'],'input','string',''));
	$emailCheckSQL = $db->select("tbl_users", "id", "email = '".base64_encode($email)."'");
	$resCnt = mysql_num_rows($emailCheckSQL);
	if($resCnt==0)
		echo "true";
	else
		echo "false";
}

if(isset($_POST['username'])){
	$username = trim($db->filtering($_POST['username'],'input','string',''));
	$usernameCheckSQL = $db->select("tbl_users", "id", "username = '".$username."'");
	$resCnt = mysql_num_rows($usernameCheckSQL);
	if($resCnt==0)
		echo "true";
	else
		echo "false";
}

if(isset($_REQUEST['captcha'])) {
	if($_REQUEST['captcha']!=$_SESSION['rand_code']) {
		$valid='false'; // Not Allowed
	} else {
		$valid='true'; // Allowed
	}
	echo $valid;
}

//$mainObj = new Registration($db, $module, 0);	
?>