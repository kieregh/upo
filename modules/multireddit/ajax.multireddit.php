<?php 
require_once("../../includes/config.php");
require_once("class.multireddit.php");
$table='tbl_post';
$objPost = new stdClass();
$module = 'multireddit';
$redditId = isset($_POST['redditId'])?$_POST['redditId']:0;
$objmultireddit = new multireddit($module, 0,$objPost,$redditId);
if(isset($_POST['categoryAddName']) && isset($_POST['redditId']))
{
	$content = NULL; 
	$categoryAddName = $_POST['categoryAddName'];
	$redditId = $_POST['redditId'];
	$qrycat = $db->select("tbl_categories","id","categoryName='".$categoryAddName."'","","",0);
	if(mysql_num_rows($qrycat)>0){
		$catres = mysql_fetch_assoc($qrycat);
		$qrycatId = $db->select("tbl_multireddit","catId","userId='".$sessUserId."' and id='".$redditId."'","","",0);
		$resCatId = mysql_fetch_assoc($qrycatId);
		$catIdStr = $resCatId["catId"];
		$catIdArr = explode(",",$catIdStr);
		if(!in_array($catres["id"],$catIdArr))
		{
			$catIdArr[] = $catres["id"];
			$objPost->catId = ($catIdStr!="")?implode(",",$catIdArr):$catres["id"];
			$qrycat = $db->update("tbl_multireddit",$objPost,"userId='".$sessUserId."' and id='".$redditId."'","");
			$content .=$objmultireddit->categoryNameListing();
			echo json_encode($content);
			exit;

		}else{
			echo json_encode("donorepeat");
		}
		exit;  
	}else{
		echo json_encode("catnotexist");
		exit;
	}
	
}
else if(isset($_POST['redditId']) && isset($_POST['catId']) && isset($_POST['deletecatId']))
{
	$content = NULL;
	$redditId = $_POST['redditId'];
	$catId = $_POST['catId'];
	$qrycatId = $db->select("tbl_multireddit","catId","userId='".$sessUserId."' and id='".$redditId."'","","",0);
	$resCatId = mysql_fetch_assoc($qrycatId);
	$catIdStr = $resCatId["catId"];
	$catIdArr = explode(",",$catIdStr);
	$count = count($catIdArr);
	for($i=0;$i<count($catIdArr);$i++)
	{
		if($catId==$catIdArr[$i])
		{
			unset($catIdArr[$i]);
		}
	}
	
	$newCatIdArr = $catIdArr;
	$objPost->catId = ($count==1)?"":implode(",",$newCatIdArr);
	$qrycat = $db->update("tbl_multireddit",$objPost,"userId='".$sessUserId."' and id='".$redditId."'","");
	$content .=$objmultireddit->categoryNameListing();
	echo json_encode($content);

}
if(isset($_POST['redditId']) && isset($_POST['postListing']))
{
	$content = NULL;
	$content .= $objmultireddit->postlisting(0);
	echo json_encode($content);
}
if(isset($_POST["id"])&& $_POST["delreddit"]=="true")
{
	extract($_POST);
	$db->delete("tbl_multireddit","id='".$id."' and userId='".$sessUserId."'","");
	echo json_encode("done");
	//redirectPage(SITE_URL);
	exit;
}
if(isset($_POST['redditDesc']) && isset($_POST['redditId']))
{
	$redditId = $_POST['redditId'];
	$objPost->redditDesc =  isset($_POST['redditDesc'])?$db->filtering($_POST['redditDesc'],'input','string',''):'';
	$qrycat = $db->update("tbl_multireddit",$objPost,"userId='".$sessUserId."' and id='".$redditId."'","");
	echo $objPost->redditDesc;
	 
}
exit;
?>