<?php
	$reqAuth = true;
	require_once("../../includes/config.php");
	require_once("class.message.php");
	$left_panel=false;
	$right_panel=true;
	$module = 'message';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$table = 'tbl_message';
	//print_r($_GET);
	$type = isset($_GET['type']) ? $_GET['type'] : NULL;
	
	$winTitle = 'Preference - '.SITE_NM;
	$headTitle = 'Preference';	
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	$mainObj = new message($objPost, $fields);
	$mainContent = $mainObj->messageHeader();
	//$mainObj->friendlist();
	
	if (isset($_POST["send"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		$userId = mysql_fetch_assoc($db->select("tbl_users", "id", "username = '".$to."'"));
		$userId = $userId['id'];
		$userName = $userId['username'];
		
		if ( $userId != $sessUserId ) {
			$objPost->from_id = $sessUserId;
			$objPost->to_id = $userId;
			$objPost->subject = isset($subject) ? $db->filtering($subject,'input','string', '') : '';
			$objPost->message = isset($description) ? $db->filtering($description,'input','string', '') : ''; 
			$objPost->createdDate = date('Y-m-d H:i:s');
			$objPost->status = 'a';
			$objPost->ipAddress = get_ip_address();
			
			if ( $objPost->to_id != '' && $objPost->subject != '') {
				$db->insert("tbl_message", $objPost);
				$_SESSION["msgType"] = array('type'=>'suc','var'=>"Message Send Successfully..");
			} 
		}
	}
	
	if($type == "compose")
	{
		$mainContent .= $mainObj->compose($pageNo);
	}
	if($type == "inbox")
	{
		$mainContent .= $mainObj->inbox($pageNo);
	}
	if($type == "sent")
	{
		$mainContent .= $mainObj->sent($pageNo);
	}
	if($type == "deleted")
	{
		$mainContent .= $mainObj->delete($pageNo);
	}
	
	require_once(DIR_THEME."default.nct");
?>