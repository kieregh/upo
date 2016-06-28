<?php 
require_once("../../includes/config.php");
require_once("class.membership.php");
//$table='tbl_post';

if($_POST) {
	extract($_POST);
	if($type == "take_action") {
		$mainObj = new MembershipPlan($module, 0, 0,$objPost);		
		$mainObj->takeMemberShip();
	}
}

exit;
?>