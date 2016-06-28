<?php 
require_once("../../includes/config.php");
require_once("class.settings.php");
$table='tbl_users';

$th_arr = array();
$upload_dir = DIR_TEMP;
$th_arr[0] = array('width' => '460', 'height' => '400');
$temp=GenerateThumbnail($_FILES['uploadfile']['name'],$upload_dir,$_FILES['uploadfile']['tmp_name'],$th_arr,'',true);
echo $image = SITE_TMP.'th1_'.$temp;
//$mime = getimagesize($image);
//echo 'data:'.$mime['mime'].';base64,'.base64_encode(file_get_contents($image));
exit;
?>