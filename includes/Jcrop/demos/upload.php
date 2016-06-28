<?php
include_once('../../../includes/config.php');

$th_arr = array();
$upload_dir = './';
$th_arr[0] = array('width' => '460', 'height' => '400');
echo $temp=GenerateThumbnail($_FILES['updImg']['name'],$upload_dir,$_FILES['updImg']['tmp_name'],$th_arr,'',true);
?>