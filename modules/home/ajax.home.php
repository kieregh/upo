<?php
require_once "../../includes/config.php";
require_once "class.home.php";

$table = 'tbl_votes';
$module = 'home';
$user_id = $sessUserId;
$objPost = new stdClass();
$objHome = new Home($module, 0, 0, $objPost);

if (isset($_POST["votetype"]) && $_POST["id"] && $_POST["voting"] == "true") {
	$postid = isset($_POST['id']) ? $db->filtering($_POST['id'], 'input', 'int', '') : '';
	$type = isset($_POST['votetype']) ? $db->filtering($_POST['votetype'], 'input', 'string', '') : '';
	$oncontent = isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'], 'input', 'string', '') : '';
	$finalvote["total"] = VotingNow($postid, $type, $oncontent);

	echo json_encode($finalvote);
	exit;
}

if (isset($_POST["oncontent"]) && $_POST["id"] && $_POST["type"] == 'hide') {

	$postid = isset($_POST['id']) ? $db->filtering($_POST['id'], 'input', 'int', '') : '';
	$oncontent = isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'], 'input', 'string', '') : '';
	$get_hide = getHide($postid, $oncontent);

	echo json_encode($get_hide);
	exit;
}

if (isset($_POST["oncontent"]) && $_POST["id"] && $_POST["type"] == 'unHide') {

	$postid = isset($_POST['id']) ? $db->filtering($_POST['id'], 'input', 'int', '') : '';
	$oncontent = isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'], 'input', 'string', '') : '';
	$get_hide = getHide($postid, $oncontent);

	echo json_encode($get_hide);
	exit;
}

if (isset($_POST["id"]) && $_POST["type"] == 'save') {
	$postid = isset($_POST['id']) ? $db->filtering($_POST['id'], 'input', 'int', '') : '';
	//$oncontent 	= 	isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'],'input','string', '') : '';
	$get_save = getSave($postid);

	echo json_encode($get_save);
	exit;
}

if (isset($_POST["oncontent"]) && $_POST["id"] && $_POST["type"] == 'report') {
	$postid = isset($_POST['id']) ? $db->filtering($_POST['id'], 'input', 'int', '') : '';
	$oncontent = isset($_POST['oncontent']) ? $db->filtering($_POST['oncontent'], 'input', 'string', '') : '';
	$get_report = getReport($postid, $oncontent);

	echo json_encode($get_report);
	exit;
}
if (isset($_POST["postid"]) && $_POST["action"] == "getshareform") {
	$postId = $db->filtering($_POST["postid"], 'input', 'int', '');
	$shareform = $objHome->getsahreform($postId);
	echo json_encode($shareform);
	exit;
}

if (isset($_POST["linkuser"]) && isset($_POST["post_id"]) && isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["message"]) && $_POST["action"] == "sharepost") {
	extract($_POST);
	$objPost->linkuser = isset($linkuser) ? $db->filtering($linkuser, 'input', 'string', '') : '';
	$objPost->email = isset($email) ? $db->filtering($email, 'input', 'string', '') : '';
	$objPost->message = isset($message) ? $db->filtering($message, 'input', 'string', '') : '';
	$objPost->post = isset($post_id) ? $db->filtering($post_id, 'input', 'string', '') : '';
	$objPost->name = isset($name) ? $db->filtering($name, 'input', 'string', '') : '';
	$objPost->captcha = isset($captcha) ? $db->filtering($captcha, 'input', 'string', '') : '';

	$post_detail = getPostContent($objPost->post);
	$posttitle = $post_detail["title"];

	$postlink = SITE_URL . "detail/" . $objPost->post . "/" . ($posttitle);
	$commentlink = SITE_URL . "comments/" . $objPost->post . "/" . ($posttitle);
	//echo $postlink;
	//exit;
	$to = $objPost->linkuser;
	$subject = $sessUsername . ' Has Shared a link';

	$msgContent = '
			<p>' . $sessUsername . ' from ' . SITE_URL . ' has shared a link with you.</p>
			<p>' . $objPost->message . '</p>
			<p>' . $posttitle . '</p>
			<p>' . $postlink . '</p>
			<p>You can view Comments here:</p>
			<p>' . $commentlink . '</p>';

	if ($_SESSION["rand_code"] == $objPost->captcha) {
		$message = generateTemplates('', ADMIN_NM, $subject, $msgContent);
		sendEmailAddress($to, $subject, $message);
	} else {
		$msgcap = "" . CAPTCHASHARE . "";
		echo json_encode($msgcap);
	}
	exit;

}

if (isset($_POST['subscribeCat']) && isset($_POST['catId']) && isset($_POST['type'])) {
	$content = NULL;
	$type = $_POST['type'];
	$catId = $_POST['catId'];
	$unsubscribeCatStr = getTableValue($db, 'tbl_users', "id ='" . $_SESSION["sessUserId"] . "'", 'unsubscribeCat');
	$unsubscribeCatArr = explode(',', $unsubscribeCatStr);
	if ($type == 's') {
		for ($i = 0; $i < count($unsubscribeCatArr); $i++) {
			if ($catId == $unsubscribeCatArr[$i]) {
				unset($unsubscribeCatArr[$i]);
			}

		}
		$newunsubscribeCatStr = implode(',', $unsubscribeCatArr);
		$objPost->unsubscribeCat = $newunsubscribeCatStr;
		$db->update("tbl_users", $objPost, "id='" . $sessUserId . "'", '', '');

		$content .= '<a href="javascript:void(0)" onclick="subscribeCategory(' . $catId . ',\'u\')">Unsubscribe</a>';

	} else if ($type == 'u') {
		$newunsubscribeCatStr = ($unsubscribeCatStr == "") ? $catId : $unsubscribeCatStr . ',' . $catId;
		$objPost->unsubscribeCat = $newunsubscribeCatStr;
		$db->update("tbl_users", $objPost, "id='" . $sessUserId . "'", '', '');
		$content .= '<a href="javascript:void(0)" onclick="subscribeCategory(' . $catId . ',\'s\')">subscribe</a>';
	}
	echo json_encode($content);
	exit;
}
/*Ajax for make History start*/
if (isset($_POST["postid"]) && isset($_POST["user"]) && isset($_POST["makeHistory"])) {
	$PostId = $db->filtering($_POST["postid"], "input", "int", "");
	$userId = $db->filtering($_POST["user"], "input", "int", "");
	if ($userId > 0) {
		$objPost->userId = $userId;
		$objPost->postId = $PostId;
		$objPost->createdDate = date("Y-m-d H:i:s");
		$objPost->ipAddress = get_ip_address();
		$db->insert("tbl_history", $objPost);
	}
	exit;
}
/*Ajax for make history over*/
/*Ajax for set language start*/
if (isset($_POST["language"]) && $_POST["setLanguate"] == 'true') {
	$_SESSION["lId"] = $_POST["language"];
	$page = $_SERVER['PHP_SELF'];
	$sec = "3";
	header("Refresh: $sec; url=$page");
}
/*Ajax for set language over*/
?>