<?php 
require_once("../../includes/config.php");
require_once("class.fpass.php");
$tableName = 'tbl_users';
$objPost = new stdClass();
$objfriend = new Fpass($module, 0,$objPost);

if(isset($_POST['email'])){
	$email = trim($db->filtering($_POST['email'],'input','string',''));
	$emailCheckSQL = $db->select("tbl_users", "id", "email = '".base64_encode($email)."'");
	$resCnt = mysql_num_rows($emailCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
exit;
?>