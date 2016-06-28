<?php
require_once("../../includes/config.php");
require_once("class.advertisement.php");
$tableName = 'tbl_users';
$objPost = new stdClass();

if(isset($_POST['type']) && $_POST['type'] == "advertisement"){
	extract($_POST);
	$sCheckSQL = $db->select("tbl_ads_cost", "display_sec", " pageId = '".$page_id."' AND slotId = '".$slot_id."' AND isActive='y'");
	$sresCnt = mysql_num_rows($sCheckSQL);

	if($sresCnt > 0) {
		$sres = mysql_fetch_assoc($sCheckSQL);
		if ( isset($sres["display_sec"]) && $sres["display_sec"] != "" ) {
			//$CheckSQL = $db->select("tbl_advertisement", "adtitle,adimage,adlink", " pageId = '".$page_id."' AND slotId = '".$slot_id."' ");
			$CheckSQL  = $db->query("select * from 1tbl_advertisement where pageId='".$page_id."' and slotId='".$slot_id."' AND remainclick != '0' and isActive='a' order by rand() limit 0,1");
			$resCnt = mysql_num_rows($CheckSQL);
			if ($resCnt > 0) {
				$rowCnt = mysql_fetch_assoc($CheckSQL);
				$arr["adtitle"] 	= $rowCnt["adtitle"];
				$arr["adimage"] 	= $rowCnt["adimage"];
				$arr["adlink"] 		= $rowCnt["adlink"];
				$arr["display_sec"] = ((int)$sres["display_sec"])*1000;
				$arr["status"] 		= 200;
				//echo "<pre>";print_r($arr);exit;
				//echo json_encode($arr);
				$content='<a href="'.$arr["adlink"].'" title="'.$arr["adlink"].'" target="_blank" onclick="_update_banner_rec(\''.$rowCnt["id"].'\');"><img src="'.SITE_UPD.'banner/th1_'.$arr["adimage"].'"></a>';
				echo json_encode($content);
				exit;
			}
		}
	}
	if($resCnt==0)
		echo "false";
	else
		echo "true";

	exit;
}

if(isset($_POST['type']) && $_POST['type'] == "advertisement_click"){
	extract($_POST);
	$CheckSQL = $db->select("tbl_advertisement", "remainclick", " id = '".$banner_id."' ");
	$resCnt = mysql_fetch_assoc($CheckSQL);
	//print_r($resCnt);
	//exit;
	$remain_click = (int)$resCnt["remainclick"];
	if($remain_click > 0 ) $remain_click = $remain_click - 1;
	else $remain_click = 0;
	/*echo $remain_click;
	echo "UPDATE tbl_advertisement SET remainclick = '".$remain_click."' WHERE id = '".$banner_id."'";
	exit;*/
	$CheckSQL  = $db->query("UPDATE tbl_advertisement SET remainclick = '".$remain_click."' WHERE id = '".$banner_id."' ");


	exit;
}
?>