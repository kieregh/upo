<?php
require_once("../config.php");
require_once("class.addFund.php");

//paypal recuring profile set starts here
require_once 'PaypalRecurringPaymentProfile.php';
$api_username               = PAYPAL_API_USERNAME;
$api_pasword                = PAYPAL_API_PASSWORD;
$api_signature              = PAYPAL_API_SIGNATURE;
$api_env                    = PaypalRecurringPaymentProfile::env_type_sandbox;
$api_version                = '75.0';
//paypal recuring profile set ends here
$table='tbl_payment_history';
$objPost = new stdClass();
$objNew = new stdClass();
$objExpire = new stdClass();

$qrysel = $db->select($table,"*","status='r' AND paymentType='s'",0);
if(mysql_num_rows($qrysel) > 0) {
while($fetchRes=mysql_fetch_object($qrysel))
{
	
		$pp_profile = new PaypalRecurringPaymentProfile($api_username, $api_pasword, $api_signature, $api_version, $api_env);
		$pp_profile_details         = $pp_profile->getProfileDetails($fetchRes->subscriptionId);
		$planFees=$fetchRes->amount;
		if($pp_profile_details['STATUS']=='Active')
		{
								
				if($pp_profile_details['NUMCYCLESCOMPLETED']>$fetchRes->totalOccurance)
				{
					$objPost->totalOccurance = $pp_profile_details['NUMCYCLESCOMPLETED'];
					$db->update($table, $objPost, "subscriptionId='".$fetchRes->subscriptionId."'", "");
					//if number of remaining cycle is over than it expires
					if(($fetchRes->totalOccurance == $pp_profile_details['NUMCYCLESCOMPLETED']) || $pp_profile_details['NUMCYCLESREMAINING']==0)
					{
						$objNew->isMember ='n';
						$db->update("tbl_users", $objNew, "id='".$fetchRes->payerId."'", "");
						$objExpire->status ='e';
						$db->update($table, $objExpire, "subscriptionId='".$fetchRes->subscriptionId."'", "");
					}
				}
		}
		else{
			
			$objNew->isMember ='n';
			$db->update("tbl_users", $objNew, "id='".$fetchRes->payerId."'", "");
			
			$objExpire->status ='c';
			$db->update($table, $objExpire, "subscriptionId='".$fetchRes->subscriptionId."'", "");
		}
	
}
}
	
exit;
?>