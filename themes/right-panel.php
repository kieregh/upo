	<selction class="right-panel">
<?php
if (isset($_POST["submitLogin"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

	extract($_POST);
	$objPost->email = isset($email) ? base64_encode($db->filtering($email, 'input', 'string', '')) : '';
	$objPost->password = isset($password) ? $db->filtering($password, 'input', 'string', '') : '';
	//$objPost->username = isset($username) ? $db->filtering($username,'input','string','')  : '';
	//$objPost->remember = isset($remember) ? $db->filtering($remember,'input','string', '') : '';
	$objPost->remember = isset($_POST['remember']) ? $db->filtering($_POST['remember'], 'input', 'string', '') : 'n';

	if ($objPost->email != "" && $objPost->password != "") {
		$objUser = new Login('', $objPost);
		$loginReturn = $objUser->loginSubmit();
		$msgType = array('type' => 'err', 'var' => $loginReturn);
	} else {
		$msgType = array('type' => 'err', 'var' => "fillAllvalues");
	}
}
if (isset($_COOKIE["remember"]) && $_COOKIE["remember"] == 'y') {
	$objPost->email = isset($_COOKIE["email"]) ? $_COOKIE["email"] : '';
	$objPost->password = isset($_COOKIE["password"]) ? base64_decode($_COOKIE["password"]) : '';
	$objPost->remember = isset($_COOKIE["remember"]) ? $_COOKIE["remember"] : '';
}

$cookieEmail = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$cookiePass = isset($_COOKIE['password']) ? base64_decode($_COOKIE['password']) : '';
$checked = ($cookieEmail != "" && $cookiePass != "") ? 'checked="checked"' : 'n';

$email = isset($objPost->email) ? base64_decode($db->filtering($objPost->email, 'output', 'string', '')) : $cookieEmail;
$password = isset($objPost->password) ? $db->filtering($objPost->password, 'output', 'string', '') : $cookiePass;

//$remember = isset($this->objPost->remember) ? $this->objPost->remember : '';
$remember = isset($objPost->remember) ? $objPost->remember : $checked;
$content = '<div class="">';
$content .= (isset($sessUserId) && $sessUserId > 0)
? '<div class="welcomeuser">' . HI . ' - <a href="' . SITE_URL . 'user/' . $sessUsername . '/overview/">' . ucfirst($sessUsername) . '</a>  |  <a href="' . SITE_URL . 'message/compose/"><img src="' . SITE_IMG . 'message.png" /></a>    |
					<a href="' . SITE_URL . 'prefer/password/">' . PREFERENCE . '</a>	|	<a href="' . SITE_URL . 'logout">' . LOGOUT . '</a></div>'
: '<div class="login_box">
						<div class="login_heading">
							<h3>' . LOGIN . '</h3>
						</div>
						<div class="login_area">
							<form class="fieldArea" name="Loginform" action="' . SITE_URL . 'login" method="post">
							' . $fields->textBox(array("onlyField" => true, "name" => "email", "extraAtt" => 'placeholder="' . USERNAME . '"', 'value' => $email)) . '
							
							' . $fields->Password(array("onlyField" => true, "name" => "password", "extraAtt" => 'placeholder="' . PASSWORD . '"', 'value' => "$password")) . '
							<div class="clearfix"></div>
							<div class="signin_label"><label><input type="checkbox" id="remember" name="remember" ' . $checked . ' value="y"/> ' . STAYSIGNIN . '</label></div>
							<div class="forgot_label"><a href="' . SITE_URL . 'forgot" title="' . FORGOTPASSWORD . '">' . FORGOTPASSWORD . '</a></div>
							<div class="clearfix"></div>
							<p>' . NOTMEMBERYET . CLICKHERE . ' <a href="' . SITE_URL . 'signup">' . SIGNUP . '</a></p>
							<div class="login_btn"><input type="submit" value="' . LOGIN . '" name="submitLogin"></div>
							</form>
						</div>
					</div>
					<div class="clearfix"></div>';

$content .= '<div class="adv_banner_3">';
if ($module == 'category') {
	$cat = isset($_GET['cat']) ? urldecode($_GET['cat']) : 0;
	$catId = getCategoryid($cat);
	$postType = getTableValue($db, 'tbl_categories', "id ='" . $catId . "'", 'postType');
	if ($postType == 'l') {
		$content .= '<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/link/' . $cat . '" id="link"><img src="' . SITE_IMG . 'submit_new_link.png"></a>';
	} else if ($postType == 't') {
		$content .= '<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/text/' . $cat . '" id="text"><img src="' . SITE_IMG . 'submit_new_text.png"></a>';
	} else if ($postType == 'a') {
		$content .= '<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/link/' . $cat . '" id="link"><img src="' . SITE_IMG . 'submit_new_link.png"></a>
								<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/text/' . $cat . '" id="text"><img src="' . SITE_IMG . 'submit_new_text.png"></a>
								<div class="space"></div>';
	} else {
		$content .= '<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/link" id="link"><img src="' . SITE_IMG . 'submit_new_link.png"></a>
								<div class="space"></div>
								<a href="' . SITE_URL . 'addpost/text" id="text"><img src="' . SITE_IMG . 'submit_new_text.png"></a>
								<div class="space"></div>';
	}
	$content .= '<a href="' . SITE_URL . 'subting"><img src="' . SITE_IMG . 'create-own_btn.png"></a>';
} else {
	$content .= '<div class="space"></div>
						<a href="' . SITE_URL . 'addpost/link" id="link"><img src="' . SITE_IMG . 'submit_new_link.png"></a>
						<div class="space"></div>
						<a href="' . SITE_URL . 'addpost/text" id="text"><img src="' . SITE_IMG . 'submit_new_text.png"></a>
						<div class="space"></div>';
}
$content .= '</div>';
if ($module == 'category' && isset($_SESSION["sessUserId"]) && $_SESSION["sessUserId"] > 0) {
	global $db;
	$cat = isset($_GET['cat']) ? urldecode($_GET['cat']) : 0;
	$catId = getCategoryid($cat);
	$unsubscribeCatStr = getTableValue($db, 'tbl_users', "id ='" . $_SESSION["sessUserId"] . "'", 'unsubscribeCat');
	$sidebarTxt = getTableValue($db, 'tbl_categories', "id ='" . $catId . "'", 'sidebar');
	$unsubscribeCatArr = explode(',', $unsubscribeCatStr);
	$content .= '<div id="categorySubscription">';
	$content .= (in_array($catId, $unsubscribeCatArr)) ? '<a href="javascript:void(0)" onclick="subscribeCategory(' . $catId . ',\'s\')">subscribe</a>' : '<a href="javascript:void(0)" onclick="subscribeCategory(' . $catId . ',\'u\')">Unsubscribe</a><div class="space"></div>' . $sidebarTxt . '';
	$content .= '</div>';
} else if ($module == 'multireddit' && isset($_GET['redditId']) && isset($_SESSION['sessUserId']) && $_SESSION['sessUserId'] > 0) {
	$redditId = isset($_GET['redditId']) ? $_GET['redditId'] : 0;
	$objMultireddit = new multireddit("multireddit", 0, NULL, $redditId);
	$content .= $objMultireddit->multiredditRightPanel();
}
if (isset($sessUserId) && $sessUserId > 0) {
	$userType = getUserType($sessUserId);
} else {
	$userType = 'n';
}
if ($module == 'category') {
	if ($catUserName != '') {
		$createdBy = '<a href="' . SITE_URL . 'user/' . $catUserName . '/overview/" >' . $catUserName . '</a>';
	} else {
		$createdBy = 'Admin';
	}
	$content .=
		'<div class="details">
			' . $cateDescription . '<br />
	        ' . CREATED_BY . ' - ' . $createdBy . '<br />
	        ' . CREATED_ON . ' - ' . $createdDate . '<br />
	        ' . TOTAL_SUBSCRIBER . '(s) - ' . $queryUserCount . '<br />
	    </div>';
}
if ($userType == 'n') {
	$content .= '<div class="membershipbox"><a href="' . SITE_URL . 'membership">' . MEMBERSHIP_MSG . '</a></div>';
}

$content .= '<div class="adv_banner_2"></div>';

$content .= '</div>';
echo $content;
?>
</selction>

<script type="text/javascript">

jQuery(function(){
<?php if ($module == "home") {?>
	//_get_banner(1, 2);
<?php }?>
});
</script>