<?php
	$reqAuth = true;
	require_once("../../includes/config.php");
	require_once("class.addpost.php");
	$left_panel=false;
	$right_panel=true;

	$module = 'addpost';
	$table = 'tbl_post';
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
	$winTitle = '포스트 올리기 - '.SITE_NM;
	$headTitle = 'Add Post';
     $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
      
	if(isset($_POST["submitPost"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		$res = $db->select("tbl_categories","id","categoryName='$categoryName'");
		$row = mysql_fetch_assoc($res);
		$catId = $row['id'];
		//exit;
		$objPost->title 		= isset($title) ? $db->filtering($title,'input','string', '') : '';
		$objPost->description 	= isset($description) ? $db->filtering($description,'input','string', '') : '';
		$objPost->catId 		= isset($catId) ? $db->filtering($catId,'input','int','') : '';
		$objPost->embeddcode	= isset($embeddcode) ? $db->filtering($embeddcode,'input','text','') : '';
		$imageUrl 				= isset($postImageUrl)?$db->filtering($postImageUrl,'input','text',''):"";

		if($_FILES['postImage']['name'] !="" )
		{
			$newName = md5(time().rand());
			$uploadDir = DIR_UPD.'post/';
			$th_arr[0][0] = array('width' => '98', 'height' => '98');
			$th_arr[1][0] = array('width' => '500', 'height' => '500');
			$updimage = GenerateThumbnail($_FILES['postImage']['name'],$uploadDir.'98x98/',$_FILES['postImage']['tmp_name'],$th_arr[0],$newName,true);
			$updimage = GenerateThumbnail($_FILES['postImage']['name'],$uploadDir.'500x500/',$_FILES['postImage']['tmp_name'],$th_arr[1],$newName,true);
		}
		else if($imageUrl!="")
		{
			$updimage = $imageUrl;
			$objPost->isimageUrl = 'y';
		}
		else
		{
			$updimage='';
		}
		$objPost->img = $updimage;
		$objUser = new addPost($objPost,$fields);
		if($objPost->title != '' && $objPost->description != '') {

			$objPost->createdDate = date('Y-m-d H:i:s');
			//$objPost->password = md5($password);
			$objPost->isActive = 'y';
			$objPost->type = 't';
			$objPost->uid = $sessUserId;
			//echo "<pre>";print_r($objPost);exit;
			$db->insert("tbl_post", $objPost);
			$uId = mysql_insert_id();
			$_SESSION["msgType"] = array('type'=>'suc','var' =>POSTADDEDSUCCESSFULLY);
			redirectPage(SITE_URL.'home');
			//$changeReturn = $objUser->submitProcedure();

		}
	}

	if(isset($_POST["submitLink"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		$res = $db->select("tbl_categories","id","categoryName='$categoryName'");
		$row = mysql_fetch_assoc($res);
		$catId = $row['id'];

		$objPost->title = isset($title) ? $db->filtering($title,'input','string', '') : '';
		$objPost->url = isset($url) ? $db->filtering($url,'input','string', '') : '';
		$objPost->catId = isset($catId) ? $db->filtering($catId,'input','int','') : '';
		$objPost->embeddcode	= isset($embeddcode) ? $db->filtering($embeddcode,'input','text','') : '';
		$imageUrl = isset($postImageUrl)?$db->filtering($postImageUrl,'input','text',''):"";

		if($_FILES['postImage']['name'] !="")
		{
			/*$imageName = $_FILES['postImage']['name'];
			$tmpName = $_FILES['postImage']['tmp_name'];
			$type = $_FILES['postImage']['type'];
			$size = $_FILES['postImage']['size'];
			$newName = rand();
			$imgArrMain = array('size'=>$size, 'name'=>$imageName, 'tmp_name'=>$tmpName, 'type'=>$type);
			$updimage = GenerateThumbnailInGD($imgArrMain,'98','98',$uploadDir.'98x98',$newName);
			$updimage = GenerateThumbnailInGD($imgArrMain,'500','500',$uploadDir.'500x500',$newName);
			*/
			$newName = md5(time().rand());
			$uploadDir = DIR_UPD.'post/';
			$th_arr[0][0] = array('width' => '98', 'height' => '98');
			$th_arr[1][0] = array('width' => '500', 'height' => '500');
			$updimage = GenerateThumbnail($_FILES['postImage']['name'],$uploadDir.'98x98/',$_FILES['postImage']['tmp_name'],$th_arr[0],$newName,true);
			$updimage = GenerateThumbnail($_FILES['postImage']['name'],$uploadDir.'500x500/',$_FILES['postImage']['tmp_name'],$th_arr[1],$newName,true);
		}
		else if($imageUrl!="")
		{
			$updimage = $imageUrl;
			$objPost->isimageUrl = 'y';
		}
		else
		{
			$updimage='';
		}
		$objPost->img = $updimage;


		$objUser = new addPost($objPost,$fields,'',$cat);
		if($objPost->title != '' && $objPost->url != '') {

			$objPost->createdDate = date('Y-m-d H:i:s');
			//$objPost->password = md5($password);
			$objPost->isActive = 'y';
			$objPost->type = 'l';
			$objPost->uid = $sessUserId;
			//echo "<pre>";print_r($objPost);exit;
			$db->insert("tbl_post", $objPost);
			$uId = mysql_insert_id();
			$_SESSION["msgType"] = array('type'=>'suc','var' =>LINKADDEDSUCCESSFULLY);
			redirectPage(SITE_URL.'home');

			//$changeReturn = $objUser->submitProcedure();
		}
	}

	$mainObj = new addPost($objPost, $fields,$lId,$cat);
	if($type == "link")
	{
		$mainContent = $mainObj->getFromLink();
	}
	else{
 	$mainContent = $mainObj->getForm();
	}
	require_once(DIR_THEME."default.nct");
?>