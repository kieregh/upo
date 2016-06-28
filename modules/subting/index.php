<?php
	require_once("../../includes/config.php");
	require_once("class.subting.php");
	require_once("../login/class.login.php");
	$right_panel=true;

	$module = 'subting';
	$winTitle = '채널만들기 - '.SITE_NM;
    $headTitle = 'Subting';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$subId = isset($_GET['subId']) ? (int)$_GET['subId'] : 0;
    $objPost = new stdClass();
	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));

	if (isset($_POST["submitSubting"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
			extract($_POST);
			$id = isset($id) ? $db->filtering($id, 'input', 'int', '') : '0';
			$objPost->categoryName = isset($categoryName) ? $db->filtering($categoryName, 'input', 'string', '') : '';
			$objPost->title = isset($title) ? $db->filtering($title, 'input', 'string', '') : '';
			$objPost->description = isset($description) ? $db->filtering($description, 'input', 'text', '') : '';
			$objPost->sidebar = isset($sidebar) ? $db->filtering($sidebar, 'input', 'text', '') : '';
			$objPost->submissionText = isset($submissionText) ? $db->filtering($submissionText, 'input', 'text', '') : '';
			$objPost->typeSubting = isset($typeSubting) ? $db->filtering($typeSubting, 'input', 'string', '') : '0';
			$objPost->postType = isset($postType) ? $db->filtering($postType, 'input', 'string', '') : 'a';

		if ($objPost->categoryName != '' && $objPost->title != '' && $objPost->description != '' &&  $objPost->sidebar != '' && $objPost->submissionText !=''){

			if($id>0){
				//echo "EDIT<pre>";print_r($objPost);exit;
				$db->update("tbl_categories", $objPost, "id=".$id, "");
				$_SESSION["msgType"] = array('type'=>'suc','var'=>'recEdited');
				redirectPage(SITE_URL.'home');
			}else{
				$objPost->createdDate = date('Y-m-d H:i:s');
				$objPost->uId = $sessUserId;
				$objPost->isActive = 'y';
				$objPost->languageId = '1';
				// /echo "INS<pre>";print_r($objPost);exit;
				$db->insert("tbl_categories",$objPost);
				$catId = mysql_insert_id();
				$_SESSION["msgType"] = array('type'=>'suc','var' =>"Subting added successful");
				redirectPage(SITE_URL.'home');
			}
			exit;
		}
	}

	$objcategory = new subting($module, $subId, 0,$objPost);
	$mainContent = $objcategory->subtingContent($pageNo);

	require_once(DIR_THEME."default.nct");
?>