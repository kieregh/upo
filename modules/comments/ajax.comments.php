<?php
require_once("../../includes/config.php");
require_once("class.comments.php");
$module = 'comments';
$user_id = $sessUserId;
$objPost = new stdClass();
$objcomments = new comments($module, 0);
if((isset($_POST["commenttext"]) && $_POST["commenttext"]!="") &&(isset($_POST["subcomment"]) && $_POST["subcomment"]=="true"))
{
	$objPost->comment = $db->filtering($_POST["commenttext"],'input','text','');
	$objPost->refType  = $db->filtering($_POST["type"],'input','string','');
	$objPost->refId = $db->filtering($_POST["post"],'input','int','');
	$objPost->postid = $db->filtering($_POST["postid"],'input','int','');
	$objPost->uId = $user_id;
	$objPost->createdDate = date("Y-m-d H:i:s");
	$objPost->ipAddress = get_ip_address();
	$db->insert("tbl_comment",$objPost);
	$postid = $db->filtering($_POST["postid"],'input','int','');
	$respoce = $objcomments->getComment($postid);
	echo json_encode($respoce);
}
else if(isset($_POST["commenttext"]) && $_POST["commenttext"]!="")
{
	$objPost->comment = $db->filtering($_POST["commenttext"],'input','text','');
	$objPost->refType  = $db->filtering($_POST["type"],'input','string','');
	$objPost->refId = $db->filtering($_POST["post"],'input','int','');
	$objPost->uId = $user_id;
	$objPost->createdDate = date("Y-m-d H:i:s");
	$objPost->postid = $objPost->refId;
	$objPost->ipAddress = get_ip_address();
	$db->insert("tbl_comment",$objPost);
	$respoce = $objcomments->getComment($objPost->refId);
	echo json_encode($respoce);
}
else if(isset($_POST["getcommentbox"]) && $_POST["getcommentbox"]== true)
{
	$commentId = $db->filtering($_POST["commentid"],'input','int','');
	$postId = $db->filtering($_POST["postid"],'input','int','');
	$subcmtBox=$objcomments->subcommentBox($commentId,$postId);
	echo json_encode($subcmtBox);
}
?>