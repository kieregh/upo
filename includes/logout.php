<?php
require_once("../includes/config.php");	
if(isset($_SESSION)) {
	foreach($_SESSION  as $k => $v) {
		$_SESSION[$k] = NULL;
		unset($_SESSION[$k]);
	}
}
redirectPage(SITE_URL.'login');
?>
