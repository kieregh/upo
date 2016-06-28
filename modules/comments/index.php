<?php
	require_once("../../includes/config.php");
	require_once("class.comments.php");
	require_once("../login/class.login.php");
	$right_panel=true;
	$postTitle= strtolower(urldecode($_GET["postName"]));
	$module = 'comments';
	$winTitle = $postTitle.'의 댓글';
    $headTitle = 'Comments';
	$postId = isset($_GET['postId']) ? $db->filtering($_GET['postId'],'input','int','') : 0;
	$postName = getTableValue($db, 'tbl_post', "id ='".$postId."'", 'title');
	$winTitle = $postName.'의 댓글';
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	$objcomments = new comments($module, 0, 0,$objPost);
	$mainContent = $objcomments->commentsContent($postId);

	require_once(DIR_THEME."default.nct");
?>