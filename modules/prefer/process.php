<?php
require_once("../../includes/config.php");
$PayPalMode 			= PAYPAL_ADV_MODE; // sandbox or live
		$PayPalApiUsername 		= PAYPAL_API_USERNAME; //PayPal API Username
		$PayPalApiPassword 		= PAYPAL_API_PASSWORD; //Paypal API password
		$PayPalApiSignature 	= PAYPAL_API_SIGNATURE; //Paypal API Signature
		$PayPalCurrencyCode 	= ADV_CURRENCY; //Paypal Currency Code
		$PayPalReturnURL 		= PAYPAL_RETURN_URL_ADV; //Point to process.php page
		$PayPalCancelURL 		= PAYPAL_CANCEL_URL_ADV; //Cancel URL if user clicks cancel
include_once("paypal.class.php");

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID

if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	
	//get session variables
	$ItemPrice 		= $_SESSION['itemprice'];
	$ItemTotalPrice = $_SESSION['totalamount'];
	$ItemName 		= $_SESSION['itemName'];
	$ItemNumber 	= $_SESSION['itemNo'];
	$ItemQTY 		=$_SESSION['itemQTY'];
	
	$padata = 	'&TOKEN='.urlencode($token).
						'&PAYERID='.urlencode($playerid).
						'&PAYMENTACTION='.urlencode("SALE").
						'&AMT='.urlencode($ItemTotalPrice).
						'&CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
			//echo '<h2>Success</h2>';
			//echo 'Your Transaction ID :'.urldecode($httpParsedResponseAr["TRANSACTIONID"]);
			
				/*
				//Sometimes Payment are kept pending even when transaction is complete. 
				//May be because of Currency change, or user choose to review each payment etc.
				//hence we need to notify user about it and ask him manually approve the transiction
				*/
				
				if('Completed' == $httpParsedResponseAr["PAYMENTSTATUS"])
				{
					//echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
				}
				elseif('Pending' == $httpParsedResponseAr["PAYMENTSTATUS"])
				{
					//echo '<div style="color:red">Transaction Complete, but payment is still pending! You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
				}
			

			//echo '<br /><b>Stuff to store in database :</b><br /><pre>';

				$transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
				$nvpStr = "&TRANSACTIONID=".$transactionID;
				$paypal= new MyPayPal();
				$httpParsedResponseAr = $paypal->PPHttpPost('GetTransactionDetails', $nvpStr, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
					
				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
					
					 
					#### SAVE BUYER INFORMATION IN DATABASE ###
					$objpostUpd = new stdClass();
					$objpostUpd->isActive = 'a';
					$objpostUpd->txnNo = $httpParsedResponseAr["TRANSACTIONID"];
					$objpostUpd->amount = $httpParsedResponseAr["AMT"];
					$db->update("tbl_advertisement",$objpostUpd,"id='".$_SESSION["LastAdvtCreat"]."'",'');
					
					$objPost->paymentType 		= 	'a';
					$objPost->subscriptionId 	= 	$httpParsedResponseAr["TRANSACTIONID"];
					$objPost->amount			=	$httpParsedResponseAr["AMT"];
					$objPost->payerId			=	$sessUserId;
					$createdDate 				= 	date("Y-m-d H:i:s");
					$objPost->startDate 		= 	$createdDate;
					$objPost->status 			= 	'r';
					$objPost->createdDate 		= 	$createdDate;
					$db->insert("tbl_payment_history",$objPost);
					
					$_SESSION["msgType"] = array('type'=>'suc','var' =>YOURADDPLACED);
					redirectPage(SITE_URL);
					
				} else  {
					//echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					

				}
	
	}else{
					//echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			
	}}
?>
