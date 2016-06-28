<?php 
require_once("../../includes/config.php");
require_once("class.user.php");
$objPost = new stdClass();
$objUser = new user();
$table='tbl_post';
if(isset($_REQUEST['captcha'])) {
	if($_REQUEST['captcha']!=$_SESSION['rand_code']) {
		$valid='false'; // Not Allowed
	} else {
		$valid='true'; // Allowed
	}
	echo $valid;
}

if ( isset($_REQUEST["categories"]) ) {
	$arr = array();
	//select($table,$cols="*",$where=NULL,$groupBy=NULL,$order=NULL,$isDisplay=NULL){
	$res = $db->select("tbl_categories","*","isActive='y'");
	if( mysql_num_rows($res) > 0 ) {
		$arr["categories"] = array();
		while( $row = mysql_fetch_assoc($res) ) {
			$arr["categories"][] = $row["categoryName"];
		}
	}
	
	echo json_encode($arr);
}

if(isset($_POST["removehistory"])&&$_POST['removehistory']=="true")
{
	$content = NULL;
	//$fIdEmail = $_POST['friendId'];
	if($sessUserId > 0)
	{
		$db->delete("tbl_history","userId=".$sessUserId."",'');
		
	}

}
if(isset($_POST["makeSponcer"]) && $_POST["makeSponcer"]=="true" && isset($_POST["postid"]))
{
	$postid = $db->filtering($_POST["postid"],"input",'int','');
	if($postid>0 && $sessUserId>0)
	{
		$objUpd = new stdClass();
		$objUpd->isSponcer='n';
		$db->update("tbl_post",$objUpd,"uid='".$sessUserId."'",'');
		$objPost->isSponcer='y';
		$db->update("tbl_post",$objPost,"id='".$postid."' and uid='".$sessUserId."'",'');
		echo json_encode("done");
	}
	else
	{
		echo json_encode("no");
	}
	
	//$submittedPosts  = $objUser->postlistingsubmitted(0);
	//echo json_encode($submittedPosts);

}
exit;
?>