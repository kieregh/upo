<?php
require_once("../../includes/config.php");
require_once("class.detail.php");

//$table='tbl_votes';

$user_id = $sessUserId;
$objPost = new stdClass();

if(isset($_POST["oncontent"]) && $_POST["id"] && $_POST["type"]=='report')
{
	$postid		=	isset($_POST['id'])	?  $db->filtering($_POST['id'],'input','int','') : '';
	$oncontent	=	isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'],'input','string','') : '';
	$get_report	=	getReport($postid,$oncontent);
	
	echo json_encode($get_report);
	
	exit;
}

if(isset($_POST["votetype"]) && $_POST["id"])
{
	$postid 			= 	isset($_POST['id']) ? $db->filtering($_POST['id'],'input','int', '') : '';
	$type 				= 	isset($_POST['votetype']) ? $db->filtering($_POST['votetype'],'input','string', '') : '';
	$oncontent 			= 	isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'],'input','string', '') : '';
	$finalvote["total"]	=	VotingNow($postid,$type,$oncontent);
	
	echo json_encode($finalvote);
	exit;
}

if(isset($_POST["oncontent"]) && $_POST["id"] && $_POST["type"]=='hide')
{
	
	$postid		=	isset($_POST['id']) ? $db->filtering($_POST['id'],'input','int','') : '';
	$oncontent 	= 	isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'],'input','string', '') : '';
	$get_hide 	= getHide($postid,$oncontent);
	
	echo json_encode($get_hide);
	exit;
}

if(isset($_POST["id"]) && $_POST["type"]=='save')
{
	$postid		=	isset($_POST['id']) ? $db->filtering($_POST['id'],'input','int','') : '';
	//$oncontent 	= 	isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'],'input','string', '') : '';
	$get_save	=	getSave($postid);
	
	echo json_encode($get_save);
	exit;
}

?>