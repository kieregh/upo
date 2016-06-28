<?php 
require_once("../../includes/config.php");
require_once("class.login.php");

$module='login';
$content = NULL;
$mainObj = new Login($module, 0, $objPost);


?>