<?php
require_once("../../includes/config.php");
require_once ("paypalfunctions.php");

$memberPlanSql=$db->select("tbl_memberplan","*","isActive='y'");
$memberPlanres = mysql_fetch_assoc($memberPlanSql);
$PlanName = $memberPlanres["name"];
$PlanDesc = substr($memberPlanres["description"],0,40);
$PlanPrice = $memberPlanres["price"];


$paymentAmount = $PlanPrice;
$_SESSION["Payment_Amount"] = $paymentAmount;

$currencyCodeType = "USD";
$paymentType = "Sale";

$returnURL = SITE_URL."membership/order_confirm.php";

$cancelURL = SITE_URL."membership/index.php";

$resArray = CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL, $PlanDesc);

$ack = strtoupper($resArray["ACK"]);
if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
{
	RedirectToPayPal ( $resArray["TOKEN"] );
}
else
{
	//Display a user friendly Error on the page using any of the following error information returned by PayPal
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);

	echo "<br />SetExpressCheckout API call failed. ";
	echo "<br />Detailed Error Message: " . $ErrorLongMsg;
	echo "<br />Short Error Message: " . $ErrorShortMsg;
	echo "<br />Error Code: " . $ErrorCode;
	echo "<br />Error Severity Code: " . $ErrorSeverityCode;
}
?>
