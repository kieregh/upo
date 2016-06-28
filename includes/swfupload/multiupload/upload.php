<?php
include('../../config.php');
	// Work-around for setting up a session because Flash Player doesn't send the cookies
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
	
	$fileid = isset($_POST["FILEID"])?$_POST["FILEID"]:'';
	echo $fileid;
	
	$_SESSION['ac'][] = $_POST["PHPSESSID"];
	$rand1 = rand().'.jpg';
	$galleryPath = DIR_IMG."gallery/";
	
	if(move_uploaded_file($_FILES["Filedata"]["tmp_name"],$galleryPath.$rand1))
		$_SESSION["c"][rand()]=$galleryPath.$rand1;
?>