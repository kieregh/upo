<?php
	#############################################################
	# Project:			zsolt
	# Developer ID:		21
	# Page: 			Account settings
	# Started Date: 	3-Nov-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.settings.php");
	if(!isset($_SESSION['sessUserId']))
	{	
		redirectPage(SITE_URL.'login');
		exit;
	}
	$left_panel=true;
	$module = 'settings';
	$table = 'tbl_users';
	$action = isset($_GET["action"]) ? $db->filtering($_GET["action"],'input','string', '') : 'edit-profile';
	
	if(isset($_POST["submitProfile"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		$th_arr[0] = array('width' => '150', 'height' => '150');
		$th_arr[1] = array('width' => '120', 'height' => '120');
		$upload_dir = DIR_UPD.'profile/';
		$crop_coords = array('x1'=>(int)$_POST['x'],'x2'=>(int)$_POST['w'],'y1'=>(int)$_POST['y'],'y2'=>(int)$_POST['h']);
		echo $temp=GenerateThumbnail($_POST['profile_pic'],$upload_dir,$_POST['profile_pic'],$th_arr,'',true,$crop_coords);
		die;

		/*
		extract($_POST);
			$targ_w = $targ_h = 150;
			$jpeg_quality = 90;
		
			$src = DIR_TMP.'test.jpg';
			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		
			imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
			$targ_w,$targ_h,$_POST['w'],$_POST['h']);
		
			header('Content-type: image/jpeg');
			imagejpeg($dst_r,null,$jpeg_quality);

		die;*/
		extract($_POST);
		$objPost->name = isset($name) ? $db->filtering($name,'input','string','') : NULL;
		$objPost->state = isset($state) ? $db->filtering($state,'input','int','') : NULL;
		$objPost->city = isset($city) ? $db->filtering($city,'input','string','') : NULL;
		$objPost->address = isset($address) ? $db->filtering($address,'input','text','') : NULL;
		$objPost->derssId = isset($derssId) ? $db->filtering($derssId,'input','int','') : NULL;		
		$objPost->shoeId = isset($shoeId) ? $db->filtering($shoeId,'input','int','') : NULL;
		$objPost->zipCode = isset($zipCode) ? $db->filtering($zipCode,'input','int','') : NULL;
		$objPost->website = isset($website) ? $db->filtering($website,'input','string','') : NULL;
		$objPost->aboutMe = isset($aboutMe) ? $db->filtering($aboutMe,'input','text','') : NULL;
		$objPost->birthDate = date('Y-m-d', strtotime($db->filtering($birthDate, 'input', 'string', NULL)));
		//$id = $db->filtering($id,'input','int','');
	
		if($objPost->name != "" && $objPost->state != "") {
				$db->update($table, $objPost, "uId=".$sessUserId, "");			
				$_SESSION["msgType"] = array('type'=>'suc','var'=>'recEdited');
			
			redirectPage(SITE_URL.'settings/edit-profile');				
		}
		else {
			$msgType = array('type'=>'err','var'=>'fillAllvalues');
		}
	
		
	}
	else if(isset($_POST["submitChange"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
			extract($_POST);
			$objPost->oPassword = isset($oPassword) ? $db->filtering($oPassword,'input','string', '') : '';
			$objPost->nPassword = isset($nPassword) ? $db->filtering($nPassword,'input','string', '') : ''; 
			$objPost->cPassword = isset($cPassword) ? $db->filtering($cPassword,'input','string', '') : '';
			$objPost->passvalue = isset($passvalue) ? $db->filtering($passvalue,'input','string', '') : '';
			
			$objUser = new Settings($objPost,NULL);
			
			if($objPost->oPassword != "" && $objPost->nPassword != "" && $objPost->cPassword != "") {
			
			$changeReturn = $objUser->changePassProcedure();
			
			switch ($changeReturn) {
				case 'wrongPass' : $msgType = array('from'=>'admin','type'=>'error','var'=>'wrongPass'); break;
				case 'passNotmatch' : $msgType = array('from'=>'admin','type'=>'error','var'=>'passNotmatch'); break;
				case 'succChangePass' : { 
					$_SESSION["msgType"] = array('from'=>'admin','type'=>'suc','var'=>'succChangePass');
					redirectPage(SITE_URL);
					break; 
				}
			}
		}
	}
	else if(isset($_POST["submitEmail"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
		extract($_POST);
		$objPost->settings_Follow = isset($settings_Follow) ? $db->filtering($settings_Follow,'input','string','') : NULL;
		$objPost->settings_likeOrshare = isset($settings_likeOrshare) ? $db->filtering($settings_likeOrshare,'input','string','') : NULL;
		$objPost->settings_Comment = isset($settings_Comment) ? $db->filtering($settings_Comment,'input','string','') : NULL;
		$objPost->settings_partyInvite = isset($settings_partyInvite) ? $db->filtering($settings_partyInvite,'input','string','') : NULL;
		if($objPost->settings_Follow != "" && $objPost->settings_partyInvite != "") {
				$db->update($table, $objPost, "uId=".$sessUserId, "");			
				$_SESSION["msgType"] = array('type'=>'suc','var'=>'recEdited');
			
			redirectPage(SITE_URL.'settings/edit-email');				
		}
		else {
			$msgType = array('type'=>'err','var'=>'fillAllvalues');
		}
	}
	
	$mainObj = new Settings($objPost, 0, $action);	
	
	$winTitle = $mainObj->headTitle.' - '.SITE_NM;
	$headTitle = $mainObj->headTitle;
	
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
 	$mainContent = $mainObj->getForm();
	require_once(DIR_THEME."default.nct");
?>