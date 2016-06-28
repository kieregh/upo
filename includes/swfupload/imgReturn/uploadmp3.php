<?php
	require_once('../../config.php');

		// Get the image and create a thumbnail
	$temp_img=$_FILES["uploadfile"]["name"];
	$tmp_name = $_FILES['uploadfile']['tmp_name'];       		
	$expExt = explode('.',$temp_img);
	$fileExtension = end($expExt);
	$fileExtension = strtolower($fileExtension);
	// Use a output buffering to load the image into a variable
	ob_start();
	$file_id = md5($_FILES["uploadfile"]["tmp_name"] + rand()*100000);
	
	
	$temp_img=$_FILES["uploadfile"]["name"];
	$ext=getExt($temp_img);
	//$file_id=md5($temp_img+ rand()*100000);
	$file_temp_name=$file_id.'.'.$ext;
	if(move_uploaded_file($_FILES["uploadfile"]['tmp_name'],DIR_TEMP.$file_temp_name))
	{
		$_SESSION['musicmp3_temp_mp3']=$file_temp_name;
		echo "success";
	}
	else
	{
		echo "fail";
	}
	
	
?>