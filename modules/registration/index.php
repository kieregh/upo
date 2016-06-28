<?php
	#############################################################
	# Project:			Reddit
	# Developer:		#43	
	# Page: 			Registration
	# Started Date: 	18-Dec-2013
	##############################################################
	
	require_once("../../includes/config.php");
	require_once("class.registration.php");
	$right_panel=false;
	$module = 'registration';
	$table = 'tbl_users';
	$winTitle = 'Registration - '.SITE_NM;
    $headTitle = 'Registration';
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		
			$objPost->username = isset($username) ? $db->filtering($username, 'input', 'string', '') : '';
			//$objPost->lastName = isset($lastName) ? $db->filtering($lastName, 'input', 'string', 'ucwords') : '';
			$objPost->email = isset($email) ? base64_encode($db->filtering($email, 'input', 'string', '')) : '';
			//$objPost->userName = isset($userName) ? $db->filtering($userName, 'input', 'string', 'ucwords') : '';
			$objPost->password = isset($password) ? $db->filtering($password, 'input', 'string', '') : '';
		
		if ($objPost->email != '' && $objPost->password != '' && $objPost->username != '') {
			
			if($db->alreadyExist("tbl_users", "email", $objPost->email)){
				$msgType = array('type'=>'err','var'=>'emailExist');
			}
			if($db->alreadyExist("tbl_users", "username", $objPost->username)){
				$msgType = array('type'=>'err','var'=>'userNameExist');
			}			
			$objPost->createdDate = date('Y-m-d H:i:s');
			$objPost->password = md5($password);
			$objPost->isActive = 'n';
			
			$db->insert("tbl_users", $objPost);
			$uId = mysql_insert_id();
			
			$id = mysql_insert_id();
			$to = base64_decode($objPost->email);
			$subject = 'Thank you for the registration!';
			$vlinkurl = $to.'##'.$id;
			$varilink = '<a href="'.SITE_URL.'home/?vcode='.base64_encode($vlinkurl).'"><span
style="color:#FF6600;text-decoration:none">Click here to active your account.</span></a>';
			
			$msgContent = '
			<p>This message serves as confirmation that your account has been successfully created. IMPORTANT: Please keep the below information safe as it contains your username and password for all your details!
You can login at any time by visiting '.SITE_URL.'login (We recommend you bookmark this link or save it in your favorites)</p>

<p><strong>Registered Email: '.base64_decode($objPost->email).'</strong></p>
<p><strong>Password: '.$password.' </strong></p>



<p>'.$varilink.'</p>';
			$message=generateTemplates($objPost->name,ADMIN_NM,$subject,$msgContent);
			sendEmailAddress($to,$subject,$message);
			$_SESSION["msgType"] = array('type'=>'suc','var'=>MSGREGACTIVATION);
			redirectPage(SITE_URL.'home');
			exit;
		} 
		else {
			$_SESSION["msgType"] = array('type'=>'err','var' =>'fillAllvalues');
		}
	}
	$mainObj = new Registration($module, 0, 0,$objPost);	
	$mainContent  = $mainObj->getForm();
	require_once(DIR_THEME ."default.nct");
?> 