<?php
	#############################################################
	# Project:			Project Name
	# Developer ID:		38
	# Page: 			Change Password
	# Started Date: 	28-Aug-2013
	##############################################################
	require_once("../../includes/config.php");
	require_once("class.contactus.php");

	$left_panel = true;
	$winTitle = 'Contact Us - '.SITE_NM;
	$headTitle = 'Contact Us';

	$module = 'contactus';
	$table='tbl_contactus';

	$metaTag = getMetaTags(array("description"=>"contactus",
		"keywords"=>'contactus',
		"author"=>SITE_NM));

	if(isset($_POST["submitAddForm"]) && $_POST["submitAddForm"] == 'Submit') {
		extract($_POST);
		$objPost->name = isset($name) ? $db->filtering($name,'input','string', '') : '';
		$objPost->phone = isset($phone) ? $db->filtering($phone,'input','string', '') : '';
		$objPost->email = isset($email) ? base64_encode($db->filtering($email,'input','string', '')) : '';
		$objPost->comment = isset($comment) ? $db->filtering($comment,'input','string', '') : '';
		$objPost->createdDate = date('Y-m-d H:i:s');
		if($objPost->name != "" && $objPost->email!='') {

			$db->insert($table,$objPost);

			$subject = "Contact Us ".SITE_NM;
			$contArray = array("name"=>$name,"phone"=>$phone,"email"=>$email,"comment"=>$comment,"greetings"=>'Admin');

			$message = generateEmailTemplate(4, $contArray);
			$status=sendMail(array(
				"name"=>'Admin',
				"fromEmail"=>$email,
				"email"=>ADMIN_EMAIL,
				"subject"=>$subject,
				"content"=>$message
			  ));


			$_SESSION["msgType"] = array('type'=>'suc','var'=>'succContactus');
			redirectPage(SITE_URL.'contact-us'); exit;
		}
		else
		{
			$_SESSION["msgType"] = array('type'=>'err','var'=>'fillAllvalues');
			redirectPage(SITE_URL.'contact-us'); exit;
		}
	}

	$objUser = new Contactus($module, NULL);
	$mainContent = $objUser->getForm();
	require_once(DIR_THEME."default.nct");
?>