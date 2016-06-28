<?php 
require_once("../../includes/config.php");
require_once("class.prefer.php");
$tableName = 'tbl_users';
$objPost = new stdClass();
$objfriend = new prefer($module, 0,$objPost);

if(isset($_POST['currentpass'])){
	$currentpass = trim($db->filtering($_POST['currentpass'],'input','string',''));
	$currentpassCheckSQL = $db->select("tbl_users", "id", "password = '".md5($currentpass)."'");
	$resCnt = mysql_num_rows($currentpassCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
else if(isset($_POST['username'])){
	$username = trim($db->filtering($_POST['username'],'input','string',''));
	$usernameCheckSQL = $db->select("tbl_users", "id", "username = '".$username."'");
	$resCnt = mysql_num_rows($usernameCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
else if(isset($_POST['email'])){
	$email = trim($db->filtering($_POST['email'],'input','string',''));
	$emailCheckSQL = $db->select("tbl_users", "id", "username = '".$email."'");
	$resCnt = mysql_num_rows($emailCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
else if(isset($_POST['password'])){
	$password = trim($db->filtering($_POST['password'],'input','string',''));
	$passwordCheckSQL = $db->select("tbl_users", "id", "password = '".md5($password)."'");
	$resCnt = mysql_num_rows($passwordCheckSQL);
	if($resCnt==0)
		echo "false";
	else
		echo "true";
		
	exit;
}
else if ( isset($_REQUEST["categories"]) ) {
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
else if(isset($_POST['fId']) && $_POST['addfriend']=="true")
{
	$content = NULL;
	$fIdEmail = $_POST['fId'];
	$objPost->fid = getTableValue($db, 'tbl_users', "username ='".$fIdEmail."'", 'id');
	$objPost->createdDate = date('Y-m-d H:i:s');
	$objPost->ipAddress	=	get_ip_address();
	$objPost->uid = $sessUserId;
	$db->insert("tbl_friend", $objPost);
	$content .= $objfriend->friendlist();
	echo json_encode($content);

}
else if(isset($_POST['friendId']) && $_POST['removefriend']=="true")
{
	$content = NULL;
	$fIdEmail = $_POST['friendId'];
	$db->delete("tbl_friend","uid=".$sessUserId." AND fid=".$fIdEmail."",'');
	$content .= $objfriend->friendlist();
	echo json_encode($content);
}
else if(isset($_POST['pageId']) && isset($_POST["pagname"]) && $_POST['pagname']=="true")
{
	$content = NULL;
	$page = $_POST['pageId'];
	//$slotSet='';
	/*$pageslot=$db->select("tbl_ads_cost", "slotId", "pageId=".$page."");
	while($slotres = mysql_fetch_assoc($pageslot))
	{
		
	}
	print_r($slotres);
	$resCnt = mysql_num_rows($pageslot);*/
	if($page > 0)
	{
	$content = $fields->selectbox(array(
		"label"=>"Slot:",
		"name"=>"categoryName",
		 "extraAtt"=>"onchange=changeSlotCost()",
		"class"=>"selectBox-bg",
		 "allow_null"=>0,
		"choices"=>array(""=>"Please Select"),
		"defaultValue"=>false,
		"multiple"=>false,
		"optgroup"=>false,
		"intoDB"=>array("val"=>true,
			   "table"=>"tbl_ads_cost",
				"fields"=>"id,slotId,CONCAT('Slot ',slotId) AS slotName",
				"where"=>"pageId=".$page."",
				"orderBy"=>"slotId",
				"valField"=>"slotId",
				"dispField"=>"slotName"
			)
	));

	echo $content;
	}
}
else if(isset($_POST['page_id']) && isset($_POST['slot_id']) && $_POST['cost']=="true")
{
	$content = NULL;
	$page_id = $_POST['page_id'];
	$slot_id = $_POST['slot_id'];
	if($page_id > 0 && $slot_id > 0)
	{
		$pageslot = $db->select("tbl_ads_cost", "cost,display_sec", " pageId = '".$page_id."' AND slotId = '".$slot_id."'",'','',0);
		$slotrow = mysql_fetch_assoc($pageslot);
		$content .= '<label>Per Click:&nbsp;</label><span>'.$slotrow["cost"].'</span><div class="flclear"></div><label>Display Second:&nbsp;</label><span>'.$slotrow["display_sec"].'</span>';
		
	}
	echo $content;
}
else if(isset($_POST["getadvtbgt"]) && $_POST["getadvtbgt"]=='true' && isset($_POST["seleopt"])){
	extract($_POST);
	$clickPrice = getclickPrice($pageid,$slotid);
	if($seleopt=="price"){
		$getclick = $budget/$clickPrice["cost"];
		$getBugdet = $budget;
		
	}
	else if($seleopt=="clicks"){
		$getBugdet = $clickPrice["cost"]*$total_click;
		$getclick = $total_click;
	}
	$responce["click"]=$getclick;
	$responce["cost"] = $getBugdet;
	if(is_float($responce["click"])){
		
		$responce["click"]='';
	}
	
	echo(json_encode($responce));
}
/*else if(isset($_POST["validaebudget"]) && $_POST["validaebudget"]=='true'){
	extract($_POST);
	$clickPrice = getclickPrice($pageid,$slotid);
	
		$getclick = $budget/$clickPrice["cost"];

	if(is_float($getclick) || $budget <= 0 ){
			return false;
		}
		else{
			return true;
			}
}*/
else if(isset($_POST["budget"]) && $_POST["validbudget"]=='true')
{
	extract($_POST);
	$clickPrice = getclickPrice($pageId,$slotId);
	$totalClick = $budget/$clickPrice["cost"];
	if (is_float($totalClick)) {
       return false;
    } else {
        return true;
    }
}
exit;
?>