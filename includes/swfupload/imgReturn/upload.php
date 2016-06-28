<?php
	require_once('../../config.php');
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	//session_start();
	ini_set("html_errors", "0");

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}

	// Get the image and create a thumbnail
	$temp_img=$_FILES["Filedata"]["name"];
	$tmp_name = $_FILES["Filedata"]["tmp_name"];       		
	$expExt = explode('.',$temp_img);
	$fileExtension = end($expExt);
	$fileExtension = strtolower($fileExtension);

	if($fileExtension=="jpg" || $fileExtension=="jpeg" ){
		$img = imagecreatefromjpeg($tmp_name);	
	}
	else if($fileExtension=="png"){
		$img = imagecreatefrompng($tmp_name);
	}
	else if($fileExtension=="gif"){
		$img = imagecreatefromgif($tmp_name);
	}
	else
		$img = imagecreatefromjpeg($tmp_name);	
		
	if (!$img) {
		echo "ERROR:could not create image handle ". $_FILES["Filedata"]["tmp_name"];
		exit(0);
	}

	$width = imageSX($img);
	$height = imageSY($img);

	if (!$width || !$height) {
		echo "ERROR:Invalid width or height";
		exit(0);
	}

	// Build the thumbnail
	$target_width = 94;
	$target_height = 94;
	$target_ratio = $target_width / $target_height;

	$img_ratio = $width / $height;

	if ($target_ratio > $img_ratio) {
		$new_height = $target_height;
		$new_width = $img_ratio * $target_height;
	} else {
		$new_height = $target_width / $img_ratio;
		$new_width = $target_width;
	}

	if ($new_height > $target_height) {
		$new_height = $target_height;
	}
	if ($new_width > $target_width) {
		$new_height = $target_width;
	}

	$new_img = ImageCreateTrueColor(94, 94);
	if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0)) {	// Fill the image black
		echo "ERROR:Could not fill new image";
		exit(0);
	}

	if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $new_width, $new_height, $width, $height)) {
		echo "ERROR:Could not resize image";
		exit(0);
	}
	// Use a output buffering to load the image into a variable
	ob_start();

	if($fileExtension=="jpg" || $fileExtension=="jpeg" ){
		$createImageSave=imagejpeg($new_img);
	}else if($fileExtension=='png' || $fileExtension=='PNG') {
		$createImageSave = imagepng($new_img); 
	}else if($fileExtension=="gif"){
		$createImageSave=imagegif($new_img);
	}else
		$createImageSave=imagejpeg($new_img);

	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}

	$imagevariable = ob_get_contents();
	ob_end_clean();
	if($_POST['type']=='bumpUpImage'){
		$newwidth=964;
		$newheight=526;						
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$img,0,0,0,0,$newwidth,$newheight, $width,$height);
		$temp_img=$_FILES["Filedata"]["name"];
		$ext=getExt($temp_img);
		//$file_id=md5($temp_img+ rand()*100000);
		$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
		$file_temp_name=$file_id.'.'.$ext;
		$createImageSave=imagejpeg($tmp,DIR_TEMP.$file_temp_name,75);
		$_SESSION['bumpUpImage_temp_img']=$file_temp_name;
	}

	$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
	
	// ADD TMP START
	/*define('DIR_FS',$_SERVER['DOCUMENT_ROOT'].'/test/SWFUpload2201/demos/applicationdemo/upload/');
	//define('DIR_FS',$_SERVER['DOCUMENT_ROOT'].'/kobragaming/test/SWFUpload2201/demos/applicationdemo/upload/');
	$file_temp_name=$file_id.'.jpg';
	if(move_uploaded_file($_FILES["Filedata"]["tmp_name"],DIR_FS.$file_temp_name)){
		$_SESSION['category_temp_img']=$file_temp_name;
	}*/
	// ADD TMP END
	
	$_SESSION["file_info"][$file_id] = $imagevariable;

	echo "FILEID:" . $file_id;	// Return the file id to the script
	
	
	$temp_img=$_FILES["Filedata"]["name"];
	$ext=getExt($temp_img);
	//$file_id=md5($temp_img+ rand()*100000);
	$file_temp_name=$file_id.'.'.$ext;
	if(move_uploaded_file($_FILES["Filedata"]['tmp_name'],DIR_TEMP.$file_temp_name))
	{
		switch($_POST['type']) {
			case "dealImage" :
				$_SESSION['dealImage_temp_img']=$file_temp_name;
				break;
			case "profileImage" :
				$_SESSION['profileImage_temp_img']=$file_temp_name;
				break;
			
			default:
				$_SESSION['dealImage_temp_img']=$file_temp_name;
				break;
		}
	}

	
?>