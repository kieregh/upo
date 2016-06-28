<?php
	require_once('../../config.php');
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
	$_SESSION["file_info"] = array();
	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}



	$file_id = md5($_FILES["Filedata"]["tmp_name"] + rand()*100000);
	$_SESSION["file_info"][$file_id] = $imagevariable;
	echo "FILEID:" . $file_id;	// Return the file id to the script
	$temp_img=$_FILES["Filedata"]["name"];
	$ext=getExt($temp_img);
	//$file_id=md5($temp_img+ rand()*100000);
	$file_temp_name=$file_id.'.'.$ext;
	if(move_uploaded_file($_FILES["Filedata"]['tmp_name'],DIR_TEMP.$file_temp_name))
	{
		switch($_POST['type']) {
			case "userResume" :
				$_SESSION['resume_temp_file']=$file_temp_name;
				break;
			default:
				break;	
		}
	}

?>