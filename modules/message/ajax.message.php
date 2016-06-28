<?php 
require_once("../../includes/config.php");
require_once("class.message.php");
$tableName = 'tbl_message';
$objPost = new stdClass();
$objfriend = new message($module, 0,$objPost);

if(isset($_POST['to'])){
	$username = trim($db->filtering($_POST['to'],'input','string',''));
	$usernameCheckSQL = $db->select("tbl_users", "id", "username = '".$username."'");
	$resCnt = mysql_num_rows($usernameCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
//print_r($_POST);
if(isset($_POST['act']) && $_POST["act"]=="deletemsg") {
	
	extract($_POST);
	
	if($type == "inbox") {
		$objPost->trash_to = 'y';
	}
	else if($type == "sent") {
		$objPost->trash_from = 'y';
	}
	
	$db->update("tbl_message",$objPost,"id",$id);
	echo json_encode(array("status"=>"success"));
}
exit;
?>