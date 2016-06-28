<?php
	/*==================================================================
	 PayPal Express Checkout Call
	 ===================================================================
	*/
require_once("../../includes/config.php");		
require_once ("paypalfunctions.php");
$PaymentOption = "PayPal";
if ( $PaymentOption == "PayPal" )
{
	
	$finalPaymentAmount =  $_SESSION["Payment_Amount"];
	
	$resArray = CreateRecurringPaymentsProfile();
	$ack = strtoupper($resArray["ACK"]);
	if($ack=="SUCCESS"){
		$profileId = $resArray["PROFILEID"];
		$createdDate = date("Y-m-d H:i:s");
		$userid = $sessUserId;
		//Update user table
		$objPost1 = new stdClass();
		$objPost1->isMember = 'y';
		$db->update("tbl_users",$objPost1,"id='".$userid."'",'');
		//entry in payment table
		$objPost = new stdClass();
		$objPost->paymentType = 's';
		$objPost->subscriptionId = $profileId;
		$objPost->amount=$_SESSION["Payment_Amount"];
		$objPost->payerId=$sessUserId;
		$objPost->startDate = $createdDate;
		$objPost->status = 'r';
		$objPost->createdDate = $createdDate;
		$db->insert("tbl_payment_history",$objPost);
		//Initilize 10 upvotes for every story.
		$sQlSelPosts = $db->select("tbl_post","id","uid='".$userid."'");
		$objVote = new stdClass();
		$objVote->refType='p';
		$objVote->voteType='u';
		$objVote->createdDate = date("Y-m-d H:i:s");
		$objVote->ipAddress = get_ip_address();
		$objVote->uId = 0;
		if(mysql_num_rows($sQlSelPosts)>0)
		{
			while($PostRes = mysql_fetch_assoc($sQlSelPosts))
			{
				for($i=1;$i<=10;$i++)
				{
				$objVote->refId = $PostRes["id"];
				$db->insert("tbl_votes",$objVote);
				}
			}
		}
		//Send mail for conformation message
			$useremail = getTableValue($db,"tbl_users","id='".$userid."'","email",0);
			$userName = getTableValue($db,"tbl_users","id='".$userid."'","username",0);
			$subject = 'Thank you for Become Premium Member!';
			$msgContent = 'You have successfully purchased premium membership of '.SITE_NM.', Please login  to your account and use premium facilites.';
			$message=generateTemplates($userName,ADMIN_NM,$subject,$msgContent);
			sendEmailAddress(base64_decode($useremail),$subject,$message);
			$_SESSION["msgType"] = array('type'=>'suc','var'=>SUCCESSPURCHASEPREMIUM);
			redirectPage(SITE_URL.'home');
	}
	else{
		$_SESSION["msgType"] = array('type'=>'err','var'=>ERRORPURCHASE);
		redirectPage(SITE_URL.'home');
	}
}		
		
?>
