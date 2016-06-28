<?php
	$reqAuth = true;
	require_once("../../includes/config.php");
	require_once("class.prefer.php");
	require_once("paypal.class.php");
	$left_panel=false;
	$right_panel=true;
	$module = 'prefer';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$table = 'tbl_users';
	$type = isset($_GET['type']) ? $_GET['type'] : NULL;
	//print_r($_GET);
	$winTitle = 'Preference - '.SITE_NM;
	$headTitle = 'Preference';	
    $metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));
	
	$mainObj = new prefer($objPost, $fields);
	$mainContent = $mainObj->preferHeader();
	//$mainObj->friendlist();

	if (isset($_POST["changepassword"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		
			$objPost->currentpass = isset($currentpass) ? $db->filtering($currentpass, 'input', 'string', '') : '';
			//$objPost->lastName = isset($lastName) ? $db->filtering($lastName, 'input', 'string', 'ucwords') : '';
			$objPost->newpass = isset($newpass) ? $db->filtering($newpass, 'input', 'string', '') : '';
			$objPost->verifypass = isset($verifypass) ? $db->filtering($verifypass, 'input', 'string', '') : '';
			
					if ($objPost->newpass != '' && $objPost->currentpass != '') {
							$changeReturn = $mainObj->submitProcedure($objPost);
							redirectPage(SITE_URL);
							break; 
						} 
	}
	
	if (isset($_POST["deleteaccount"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		
		$objPost->username = isset($username) ? $db->filtering($username, 'input', 'string', '') : '';
		//$objPost->lastName = isset($lastName) ? $db->filtering($lastName, 'input', 'string', 'ucwords') : '';
		$objPost->password = isset($password) ? $db->filtering($password, 'input', 'string', '') : '';

		if ($objPost->username != '' && $objPost->password != '') {
			$changeReturn = $mainObj->submitProcedureDeleteAccount($objPost);
			redirectPage(SITE_URL);
			break; 
		} 
	}
	
	if(isset($_POST["payadvertisement"])){
		
		extract($_POST);
		$objPost->uId = $sessUserId;
		$objPost->adimage = "";
		$objPost->pageId = $db->filtering($pageId,'input','int','');
		$objPost->slotId = $db->filtering($categoryName,'input','int','');
		$objPost->cost = $db->filtering($budget,'input','string','');
		$objPost->noclick = $db->filtering($total_click,'input','int','');
		$objPost->remainclick = $db->filtering($total_click,'input','int','');
		$objPost->isActive = 'd';
		$objPost->adlink = $db->filtering($addlink,'input','string','');
		$objPost->createdDate = date("Y-m-d H:i:s");
		$objPost->updateDate = date("Y-m-d H:i:s");
		$objPost->ipAddress = get_ip_address();
		
		if($_FILES['banner']['name'] !="")
		{
				$newName = md5(time().rand());
				$uploadDir = DIR_UPD.'banner/';
				if($objPost->slotId == 1)
				{
					$th_arr[0][0] = array('width' => '650', 'height' => '146');
				}
				else if($objPost->slotId == 2)
				{
					$th_arr[0][0] = array('width' => '300', 'height' => '253');
				}
				//$th_arr[1][0] = array('width' => '500', 'height' => '500');
				$banner = GenerateThumbnail($_FILES['banner']['name'],$uploadDir,$_FILES['banner']['tmp_name'],$th_arr[0],$newName,true);	
				//$banner = GenerateThumbnail($_FILES['postImage']['name'],$uploadDir.'500x500/',$_FILES['postImage']['tmp_name'],$th_arr[1],$newName,true);			
		}
		else
		{
			$banner='';
		}
		
		$objPost->adimage = $banner;
		
		$db->insert("tbl_advertisement",$objPost);
		$lastAdvt=mysql_insert_id();
		$_SESSION["LastAdvtCreat"]=$lastAdvt;
		//header("'locaton:'".SITE_URL."'prefet/payadvt.php)
		
		//Mainly we need 4 variables from an item, Item Name, Item Price, Item Number and Item Quantity.
		$ItemName = "Advertisement on '".SITE_NM."'"; //Item Name
		$ItemPrice = $objPost->cost; //Item Price
		$ItemNumber = $lastAdvt; //Item Number
		$ItemQty = "1"; // Item Quantity
		$ItemTotalPrice = ($ItemPrice*$ItemQty); //(Item Price x Quantity = Total) Get total amount of product; 
		$PayPalMode 			= PAYPAL_ADV_MODE; // sandbox or live
		$PayPalApiUsername 		= PAYPAL_API_USERNAME; //PayPal API Username
		$PayPalApiPassword 		= PAYPAL_API_PASSWORD; //Paypal API password
		$PayPalApiSignature 	= PAYPAL_API_SIGNATURE; //Paypal API Signature
		$PayPalCurrencyCode 	= ADV_CURRENCY; //Paypal Currency Code
		$PayPalReturnURL 		= PAYPAL_RETURN_URL_ADV; //Point to process.php page
		$PayPalCancelURL 		= PAYPAL_CANCEL_URL_ADV; //Cancel URL if user clicks cancel
	//Data to be sent to paypal
		$padata = 	'&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
					'&PAYMENTACTION=Sale'.
					'&ALLOWNOTE=1'.
					'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
					'&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
					'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice). 
					'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
					'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
					'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
					'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
					'&AMT='.urlencode($ItemTotalPrice).				
					'&RETURNURL='.urlencode($PayPalReturnURL ).
					'&CANCELURL='.urlencode($PayPalCancelURL);	
			
			//We need to execute the "SetExpressCheckOut" method to obtain paypal token
			$paypal= new MyPayPal();
			$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			
			//Respond according to message we receive from Paypal
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
			{
						
					// If successful set some session variable we need later when user is redirected back to page from paypal. 
					$_SESSION['itemprice'] =  $ItemPrice;
					$_SESSION['totalamount'] = $ItemTotalPrice;
					$_SESSION['itemName'] =  $ItemName;
					$_SESSION['itemNo'] =  $ItemNumber;
					$_SESSION['itemQTY'] =  $ItemQty;
					
					if($PayPalMode=='sandbox')
					{
						$paypalmode 	=	'.sandbox';
					}
					else
					{
						$paypalmode 	=	'';
					}
					//Redirect user to PayPal store with Token received.
					$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
					header('Location: '.$paypalurl);
				 
			}else{
				//Show error message
				$mainContent.= '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				$mainContent.= '<pre>'.	implode(",",$httpParsedResponseAr);
				$mainContent.= '</pre>';
			}
		 
	}
	
	if($type == "password")
	{
		$mainContent .= $mainObj->password($pageNo);
	}
	if($type == "delete")
	{
		$mainContent .= $mainObj->delete($pageNo);
	}
	if($type == "friend")
	{
		$mainContent .= $mainObj->friend($pageNo);
	}
	if($type == "create_advertisement")
	{
		$mainContent .= $mainObj->createAdvertisment($pageNo);
	}
	require_once(DIR_THEME."default.nct");
?>