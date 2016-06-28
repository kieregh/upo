<?php
require_once("../../includes/config.php");
require_once("class.registration.php");
$objPost = new stdClass();
$token = isset($_GET["token"]) ? $_GET["token"] : NULL;
if($token != NULL) {
	$selUser = $db->select('tbl_users', 'uId', 'activationCode=\''.$token.'\'', '', '', 0);

	if(mysql_num_rows($selUser) > 0) {
		$fetchRes = mysql_fetch_array($selUser);
		$uId = $db->filtering($fetchRes["uId"], 'output', 'int', 0);

		$objPost->isActive = 'y';
		$objPost->activationCode = md5(time());
		$db->update("tbl_users", $objPost, "uId=".$uId."","");
		$_SESSION["msgType"] = array('type'=>'suc','var'=>'succActivateAccount');
	}
	else{
		$_SESSION["msgType"] = array('type'=>'err','var'=>'incorectActivate');
	}
}
$_SESSION["isJoinPopup"] = false;
redirectPage(SITE_URL.'home');

?>