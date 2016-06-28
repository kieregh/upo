<?php
function redirectPage($url) {

	echo '<script type="text/javascript">
		window.location.href=\'' . $url . '\'
   </script>';
	// header('location:'.$url);
	exit;
}
function requiredLoginId() {
	global $sessUserType, $sesspUserId, $sessUserId;
	if (isset($sessUserType) && $sessUserType == 's') {
		return $sesspUserId;
	} else {
		return $sessUserId;
	}

}
function sanitize_output($buffer) {
	$search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s');
	$replace = array('>', '<', '\\1', '');
	$buffer = preg_replace($search, $replace, $buffer);
	return $buffer;
}
function domain_details($returnWhat) {
	$arrScriptName = explode('/', $_SERVER['SCRIPT_NAME']);
	$sitePath = 'bookitt';
	if (in_array($sitePath, $arrScriptName) == true) {
		$arrKey = array_search($sitePath, $arrScriptName);
		unset($arrScriptName[$arrKey]);
	}
	$arrScriptName = array_values($arrScriptName);
	//echo "<pre>";print_r($arrScriptName);
	if ($_SERVER['SERVER_NAME'] == 'nct109') {
		$i = 1;
	} else {
		$i = 0;
	}
	if ($returnWhat == 'module') {
		return ($arrScriptName[$i + 3] != "" ? $arrScriptName[$i + 3] : '');
	} else if ($returnWhat == 'dir') {
		return ($arrScriptName[$i + 1] != "" ? $arrScriptName[$i + 1] : '');
	} else if ($returnWhat == 'file') {
		return ($arrScriptName[$i + 4] != "" ? $arrScriptName[$i + 4] : '');
	} else if ($returnWhat == 'file-module') {
		return ($arrScriptName[$i + 2] != "" ? $arrScriptName[$i + 2] : '');
	}

}
function Authentication($reqAuth = false) {
	$todays_date = date("Y-m-d");
	global $adminUserId, $sessUserId, $db;
	$whichSide = domain_details('dir');
	if ($reqAuth == true) {
		if ($whichSide == 'admin') {
			if ($adminUserId == 0) {
				redirectPage(SITE_ADM_MOD . 'login/');
			}

		} else if ($sessUserId != 0) { /*
			$userType=getTableValue($db,'tbl_users',"uId=".$sessUserId, $returnWhat='userType');
			if($userType==2)
			{
				$qrysel= mysql_query("SELECT p.status FROM tbl_payment_history as p WHERE p.payerId=".$sessUserId." AND p.status='r' AND p.endDate >= '".$todays_date."' ORDER BY p.endDate DESC LIMIT 1 ");
				if (mysql_num_rows($qrysel) == 0) {

					$_SESSION["msgType"] = array('type'=>'err','var'=>'noPayment');
					 redirectPage(SITE_URL.'payment');
					 exit;
				}
			}
		*/} else {
			if ($sessUserId <= 0) {
				$_SESSION["msgType"] = array(
					'type' => 'err',
					'var' => 'Login Required',
				);
				redirectPage(SITE_URL . 'login');
			}
		}
	}
}
function redirectErrorPage($error) {
	echo $error;
	//redirectPage(SITE_URL.'modules/error?u='.base64_encode($error));
}
function disMessage($msgArray) {
	$message = '';
	$content = '';
	$type = isset($msgArray["type"]) ? $msgArray["type"] : NULL;
	$var = isset($msgArray["var"]) ? $msgArray["var"] : NULL;
	unset($_SESSION["msgType"]);
	$_SESSION["msgType"] = '';
	if (!is_null($var)) {
		switch ($var) {
		case 'invaildUsers':{
				$message = '' . INVAUSERPASS . '';
				break;}
		case 'NRF':{
				$message = '"' . NORECORDFND . '"';
				break;}
		case 'alreadytaken':{
				$message = 'User Name or Email is already taken';
				break;}
		case 'invaildUsers':{
				$message = '' . INVAUSERPASS . '';
				break;}
		case 'fillAllvalues':{
				$message = 'Fill all required values properly';
				break;}
		case 'insufValues':{
				$message = 'Insufficient values';
				break;}
		case 'succActivateAccount':{
				$message = 'You have successfully activated your account, Please login to continue';
				break;}
		case 'inactivatedUser':{
				$message = 'You haven\'t activated your account, Please check your mail';
				break;}
		case 'unapprovedUser':{
				$message = '' . UNAPPROVED . '';
				break;};
		case 'wrongEmail':{
				$message = '' . WRONGEMAIL . '';
				break;}
		case 'wrongPass':{
				$message = 'You have entered wrong password';
				break;}
		case 'passNotmatch':{
				$message = 'New password and Confirm password doesn\'t match';
				break;}
		case 'succChangePass':{
				$message = 'You have successfully changed your password';
				break;}
		case 'actBlockbyAdmin':{
				$message = 'Your account is blocked by the Admin.';
				break;}
		case 'incorectActivate':{
				$message = 'Incorrect account, Problem to activate your account';
				break;}

		## global admin

		case 'userExist':{
				$message = 'Username is already exist';
				break;}
		case 'emailExist':{
				$message = 'Email address is already exist';
				break;}
		case 'addedUser':{
				$message = 'You have successfully added Global Admin.';
				break;}
		case 'editedUser':{
				$message = 'You have successfully edited Global Admin.';
				break;}
		case 'actUserStatus':{
				$message = 'You have successfully activated Global Admin status.';
				break;}
		case 'deActUserStatus':{
				$message = 'You have successfully de-activated Global Admin status.';
				break;}
		case 'delUser':{
				$message = 'You have successfully deleted Global Admin.';
				break;}

		case 'recAdded':{
				$message = 'Record was added successfully.';
				break;}
		case 'recEdited':{
				$message = 'Record was edited successfully.';
				break;}
		case 'recActivated':{
				$message = 'Record was activated successfully.';
				break;}
		case 'recDeActivated':{
				$message = 'Record was inactivated successfully.';
				break;}
		case 'recDeleted':{
				$message = 'Record was deleted successfully.';
				break;}
		case 'noPerToAccess':{
				$message = 'You don\'t have permission to access the page.';
				break;}
		case 'invalidImage':{
				$message = 'Please upload proper image.';
				break;}

		default:{
				$message = $var;
				break;}

		}
		if ($type == 'suc') {
			$msgClass = 'succMessage';
			$msgBackClass = 'succBackGround';
			$imagePath = '<img src="' . SITE_IMG . 'right-sign.png" height="15" alt="" />';
		} else {
			$msgClass = 'errorMessage';
			$msgBackClass = 'errorBackGround';
			$imagePath = '<img src="' . SITE_IMG . 'wrong-sign.png" height="18" alt="" />';
		}
		$content = '<div class="msgPart"><div id="' . $msgClass . '">
			<div class="' . $msgBackClass . '">
				<div class="imagePart">' . $imagePath . '</div>
				<div class="disMsg">' . $message . '</div>
				<div style="clear:both"></div>
			</div>
		</div></div>';
	}
	$content .= '<script type="text/javascript" language="javascript">
		function close(){
			 $(\'.msgPart\').fadeOut(2500, "linear");
		  }
		setTimeout(function() {
			  $(\'.msgPart\').fadeOut(2500, "linear");
		}, 5000);
	</script>';

	return $content;
}

function genrateRandom($length = 8, $seeds = 'alphanum') {
	// Possible seeds
	$seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$seedings['numeric'] = '0123456789';
	$seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$seedings['hexidec'] = '0123456789abcdef';

	// Choose seed
	if (isset($seedings[$seeds])) {
		$seeds = $seedings[$seeds];
	}

	// Seed generator
	list($usec, $sec) = explode(' ', microtime());
	$seed = (float) $sec + ((float) $usec * 100000);
	mt_srand($seed);

	// Generate
	$str = '';
	$seeds_count = strlen($seeds);

	for ($i = 0; $length > $i; $i++) {
		$str .= $seeds{mt_rand(0, $seeds_count - 1)};
	}

	return $str;
}
// Get IP Address
function get_ip_address() {
	foreach (array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR',
	) as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
					return $ip;
				}
			}
		}
	}
}
function getPagerData($numHits, $limit, $page) {
	$numHits = (int) $numHits;
	$limit = max((int) $limit, 1);
	$page = (int) $page;
	$numPages = ceil($numHits / $limit);

	$page = max($page, 1);
	$page = min($page, $numPages);

	$offset = ($page - 1) * $limit;

	$ret = new stdClass;

	$ret->offset = $offset;
	$ret->limit = $limit;
	$ret->numPages = $numPages;
	$ret->page = $page;

	return $ret;
}
function GetDomainName($url) {
	$now1 = ereg_replace('www\.', '', $url);
	$now2 = ereg_replace('\.com', '', $now1);
	$domain = parse_url($now2);
	if (!empty($domain["host"])) {
		return $domain["host"];
	} else {
		return $domain["path"];
	}

}
function convertDate($date, $time = false, $what = 'default') {
	if ($what == 'wherecond') {
		return date('Y-m-d', strtotime($date));
	} else if ($what == 'display') {
		return date('M d, Y h:i A', strtotime($date));
	} else if ($what == 'onlyDate') {
		return date('M d, Y', strtotime($date));
	} else if ($what == 'gmail') {
		return date('D, M d, Y - h:i A', strtotime($date));
		//Tue, Jul 16, 2013 at 12:14 PM
	} else if ($what == 'default') {
		if (trim($date) != '' && $date != '0000-00-00' && $date != '1970-01-01') {
			if (!$time) {
				$retDt = date('d-m-Y', strtotime($date));
				return $retDt == '01-01-1970' ? '' : $retDt;
			} else {
				'1970-01-01 01:00:00';
				'01-01-1970 01:00 AM';
				$retDt = date('d-m-Y h:i A', strtotime($date));
				return $retDt == '01-01-1970 01:00 AM' ? '' : $retDt;
			}
		} else {
			return '';
		}

	} else if ($what == 'db') {
		if (trim($date) != '' && $date != '0000-00-00' && $date != '1970-01-01') {
			if (!$time) {
				$retDt = date('Y-m-d', strtotime($date));
				return $retDt == '1970-01-01' ? '' : $retDt;
			} else {
				$retDt = date('Y-m-d H:i:s', strtotime($date));
				return $retDt == '1970-01-01 01:00:00' ? '' : $retDt;
			}
		} else {
			return '';
		}

	}
}
function downloadFiles($dir, $file) {
	header("Content-type: application/force-download");
	header('Content-Disposition: inline; filename="' . $dir . $file . '"');
	header("Content-Transfer-Encoding: Binary");
	header("Content-length: " . filesize($dir . $file));
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $file . '"');
	readfile("$dir$file");
}

function sendMail($emailArr = array()) {
	$name = isset($emailArr["name"]) ? $emailArr["name"] : '';
	$email = isset($emailArr["email"]) ? $emailArr["email"] : array();
	$fromEmail = isset($emailArr["fromEmail"]) ? $emailArr["fromEmail"] : FROM_EMAIL;
	$senderName = isset($emailArr["senderName"]) ? $emailArr["senderName"] : FROM_NM;
	$subject = isset($emailArr["subject"]) ? $emailArr["subject"] : '';
	$message = isset($emailArr["content"]) ? $emailArr["content"] : '';
	$cc = array();
	$bcc = array();
	$read = array();
	$reply = array();

	$to = array('0' => array('name' => $name, 'email' => $email));
	$header = genrateHeader($to, $cc, $bcc, $senderName, $fromEmail, $reply, false, $read, false);
	if ($_SERVER["SERVER_NAME"] != 'nct25') {
		if (@mail(genrateHeaderStr($to), $subject, $message, $header)) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
function genrateHeader($to, $cc = array(), $bcc = array(), $senderName = "", $fromEmail, $reply = array(), $setReply = false, $read = array(), $readRecipt = false) {
	$setheader = "";
	$setheader .= 'Content-type: text/html; charset=ISO-8859-1' . "\r\n";
	$setheader .= 'From: ' . $senderName . ' <' . $fromEmail . '>' . "\r\n";
	$setheader .= genrateHeaderStr($to, 'To');
	if (is_array($cc) && count($cc) > 0) {
		$setheader .= genrateHeaderStr($cc, 'Cc');
	}
	if (is_array($bcc) && count($bcc) > 0) {
		$setheader .= genrateHeaderStr($bcc, 'Bcc');
	}
	if (is_array($reply) && ($setReply == true) && (count($reply) > 0)) {

		$setheader .= genrateHeaderStr($reply, 'Reply-To');
	}
	if (is_array($read) && ($readRecipt == true) && (count($read) > 0)) {
		$setheader .= genrateHeaderStr($read, 'Disposition-Notification-To');
	}
	return $setheader;
}

function genrateHeaderStr($whom, $param = '') {
	$returnHeader = '';
	if ($param == '') {
		if (count($whom) > 0) {
			$i = 0;
			foreach ($whom as $rVal) {
				$i++;
				if ($i == count($whom)) {
					$returnHeader .= strtolower($rVal['email']);
				} else {
					$returnHeader .= strtolower($rVal['email']) . ', ';
				}
			}
		}
	} else {
		$returnHeader .= $param . ': ';
		if (count($whom) > 0) {
			$i = 0;
			foreach ($whom as $rVal) {
				$i++;
				if ($i == count($whom)) {
					$returnHeader .= ucwords($rVal['name']) . ' <' . strtolower($rVal['email']) . '>' . "\r\n";
				} else {
					$returnHeader .= ucwords($rVal['name']) . ' <' . strtolower($rVal['email']) . '>, ';
				}
			}
		}
	}
	return $returnHeader;
}

function sendEmailAddress($to, $subject, $message) {
	/*$headers = "MIME-Version: 1.0\r\n";
		    $headers .= "From: " . SITE_NM . " <" . ADMIN_EMAIL . ">\r\nX-Mailer: PHP\n";
		    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		    if (@mail($to, $subject, $message, $headers)) {
		        return true;
		    } else {
		        return false;
	*/

	/* SMTP mail start */
	require_once "class.phpmailer.php";
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled

	//mail via hosting server
	//$mail->Host = 'mail.ncryptedprojects.com';
	//$mail->Port = 587; // or 587
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT; // or 587

	$mail->CharSet = 'UTF-8';

	$mail->IsHTML(true);
	//$mail->Username = 'no-reply@ncryptedprojects.com';
	//$mail->Password = '3}z&@V1z48][';
	$mail->Username = SMTP_USER;
	$mail->Password = SMTP_PASSWORD;
	//$mail->SetFrom(SMTP_USERNAME);
	$mail->From = ADMIN_EMAIL;
	$mail->SetFrom(ADMIN_EMAIL, SITE_NM);
	$mail->AddReplyTo(ADMIN_EMAIL, SITE_NM);
	$mail->Subject = $subject;
	$mail->Body = $message;
	$mail->AddAddress($to);
	$result = true;
	if (!$mail->Send()) {
		//echo "Mailer Error: " . $mail->ErrorInfo;
		$result = false;
	}
	/* SMTP mail end */
	return $result;
}

function showAvailableVariables($types) {
	//echo $types; exit;
	$return = "";

	if ($types == 1) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
					<div class="fclear"><strong>###firstName###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
					<div class="fclear"><strong>###lastName###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Last Name</div>
					<div class="fclear"><strong>###email###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email</div>

					<div class="fclear"><strong>###varActiveLink###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Activation Link</div>
					';
	} else if ($types == 2) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
					<div class="fclear"><strong>###varEmail###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User Email</div>
					<div class="fclear"><strong>###varPassword###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password</div>
					';
	} else if ($types == 3) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
					<div class="fclear"><strong>###merchantName###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Merchant Name</div>
					<div class="fclear"><strong>###price###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price</div>
					<div class="fclear"><strong>###nowPrice###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now Price</div>
					<div class="fclear"><strong>###categoryName###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Category Name</div>
					<div class="fclear"><strong>###cityName###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;City Name</div>
					<div class="fclear"><strong>###dealUrl###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Link to deal url</div>
					<div class="fclear"><strong>###expiryDate###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expiry Date</div>';

	} else if ($types == 4) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
				<div class="fclear"><strong>###name###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact Us name</div>
				<div class="fclear"><strong>###phone###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Phone</div>
				<div class="fclear"><strong>###email###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email</div>
				<div class="fclear"><strong>###comment###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comment</div>';

	} else if ($types == 5) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
				<div class="fclear"><strong>###email###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registered email</div>
				<div class="fclear"><strong>###password###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;password</div>';

	} else if ($types == 7) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
				<div class="fclear"><strong>###enrollment###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Representative enrollment Number.</div>';

	} else if ($types == 8) {
		$return = '<div class="fclear"><strong>###greetings###</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;First Name</div>
				';

	}

	return '<span><strong>Hint:</strong></span><br/>' . $return;
}

function generateTemplates($greetings, $regards, $subject, $msgContent) {
	$content = '';
	$content .= '
    <div style="background-color:#F9F9F9; border:1px solid #E1E1E1; padding:25px; font-family:Verdana, Geneva, sans-serif">
		<div style="padding:0 0 25px 0; color:#006; font-size:22px;">
            <strong><u>' . $subject . '</u></strong>
        </div>
		<div style="font-size:12px;">
            <p>Hello' . ($greetings != '' ? '&nbsp;' . $greetings : '') . ',</p>
		    <p>&nbsp;</p>
            ' . $msgContent . '
            <p>&nbsp;</p>
            <p>Regards,<br />
		' . $regards . '</p>
		</div>
	</div>';
	return $content;
}

function generateEmailTemplate($type, $arrayCont) {
	$qrysel = mysql_query("SELECT templates FROM tbl_templates WHERE id = '" . $type . "'");
	$fetchEmailtemp = mysql_fetch_assoc($qrysel);

	$message = trim(stripslashes($fetchEmailtemp["templates"]));
	$message = str_replace("###greetings###", $arrayCont["greetings"], $message);

	if ($type == '1') {
		$activationURL = '<a href="' . SITE_MOD . '/registration/activation.php?u=act&aKey=' . $arrayCont["actKey"] . '" style="color:#F60; text-decoration:none;" >Click here to active your account.</a>';
		$message = str_replace("###firstName###", $arrayCont["firstName"], $message);
		$message = str_replace("###password###", $arrayCont["password"], $message);
		$message = str_replace("###varActiveLink###", $activationURL, $message);
		$message = str_replace("###adminName###", "Admin", $message);
	} else if ($type == '2') {
		$activationURL = '<a href="' . SITE_URL . 'change-password/' . $arrayCont["actKey"] . '" style="color:#F60; text-decoration:none;" >Click here to Change Password.</a>';
		$message = str_replace("###firstName###", $arrayCont["firstName"], $message);
		$message = str_replace("###varActiveLink###", $activationURL, $message);
		$message = str_replace("###adminName###", "Admin", $message);

	} else if ($type == 3) //	for sending mail to customer //
	{
		$message = str_replace("###merchantName###", $arrayCont["merchantName"], $message);
		$message = str_replace("###dealName###", $arrayCont["dealName"], $message);
		$message = str_replace("###price###", $arrayCont["price"], $message);
		$message = str_replace("###discount###", $arrayCont["discount"], $message);
		$message = str_replace("###expiryDate###", $arrayCont["expiryDate"], $message);
	} else if ($type == 4) //	for sending email on contact us //
	{
		$message = str_replace("###name###", $arrayCont["name"], $message);
		$message = str_replace("###phone###", $arrayCont["phone"], $message);
		$message = str_replace("###email###", $arrayCont["email"], $message);
		$message = str_replace("###comment###", $arrayCont["comment"], $message);
	} else if ($type == '5') {
		$message = str_replace("###password###", $arrayCont["password"], $message);
		$message = str_replace("###email###", $arrayCont["email"], $message);
		$message = str_replace("###adminName###", "Admin", $message);
	} else if ($type == '6') {
		$message = str_replace("###DealTitle###", $arrayCont["DealTitle"], $message);
		$message = str_replace("###DealLocation###", $arrayCont["DealLocation"], $message);
		$message = str_replace("###DealDescription###", $arrayCont["DealDescription"], $message);
		$message = str_replace("###DealImage###", $arrayCont["DealImage"], $message);
		$message = str_replace("###DealUrl###", $arrayCont["DealUrl"], $message);
		$message = str_replace("###expiryDate###", $arrayCont["expiryDate"], $message);
		$message = str_replace("###cityName###", $arrayCont["cityName"], $message);
		$message = str_replace("###adminName###", "Admin", $message);
	} else if ($type == '7') {

		$subscriptionLink = SITE_URL . "subscription";
		$message = str_replace("###remainingDays###", $arrayCont["remainingDays"], $message);
		$message = str_replace("###subscriptionLink###", $subscriptionLink, $message);
	}
	return $message;
}
function getMetaTags($metaArray) {
	$content = NULL;
	$content = '<meta name="description" content="' . $metaArray["description"] . ', ' . $metaArray["keywords"] . ', ' . SITE_NM . ', ' . REGARDS . '" />
		<meta name="keywords" content="' . $metaArray["keywords"] . '" />
		<meta name="author" content="' . $metaArray["author"] . '" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		';
	if (isset($metaArray["nocache"]) && $metaArray["nocache"] == true) {
		$content .= '<meta HTTP-EQUIV="CACHE-CONTROL" content="NO-CACHE" />
		';
	}

	return $content;
}
function generateRandString($totalString = 10) {
	$alphanum = "AaBbC0cDdEe1FfGgH2hIiJj3KkLlM4mNnOo5PpQqR6rSsTt7UuVvW8wXxYy9Zz";
	return substr(str_shuffle($alphanum), 0, $totalString);
}
function getTotalRows($db, $tableName, $condition = '', $countField = '*') {
	$qCount = $db->query('SELECT count( ' . $countField . ' ) FROM ' . $tableName . ' ' . ($condition != "" ? 'WHERE ' . $condition . '' : ''));
	$fetchCount = mysql_fetch_array($qCount);
	return $fetchCount['count( ' . $countField . ' )'];
}
function getTableValue($db, $tableName = '', $whereCond, $returnWhat = '', $printQ = 0) {
	if ($returnWhat == 'array') {
		$qrysel = $db->select($tableName, "*", $whereCond, "", "", $printQ);
		return mysql_fetch_assoc($qrysel);
	} else {
		$qrysel = $db->select($tableName, $returnWhat, $whereCond, "", "", $printQ);
		$fetchVal = mysql_fetch_assoc($qrysel);
		return $fetchVal[$returnWhat];
	}
}
function multipleObjectValues($db, $commaValues, $tableName, $idField, $displayField) {
	$content = NULL;
	$arrValues = explode(',', $commaValues);
	for ($i = 0; $i < count($arrValues); $i++) {
		$id = $db->filtering($arrValues[$i], 'output', 'int', NULL);
		if ($i > 0) {
			$content .= ', ';
		}

		$content .= getTableValue($db, $tableName, $idField . '=' . $id, $displayField, 0);

	}
	return $content;
}

function emptyStringReplace($val, $replaceWith = '-') {
	$val = trim($val);
	if ($val == '') {
		return $replaceWith;
	} else {
		return $val;
	}

}
function GenerateThumbnailInMaGic($varPhoto, $uploadDir, $tmp_name, $widthHeight = '150x150') {

	$ext = strtoupper(substr($varPhoto, strlen($varPhoto) - 4));

	if (($ext == ".JPG" || $ext == ".GIF" || $ext == ".PNG" || $ext == ".BMP" || $ext == ".JPEG")) {

		$imagename = rand() . $ext;
		$pathToImages = $uploadDir . $imagename;
		$pathToThumbs = $uploadDir . '/' . $widthHeight . '/' . $imagename;
		$Photo_Source = copy($tmp_name, $pathToImages);
		if ($Photo_Source) {
			$thumb_command = "convert " . $pathToImages . " -thumbnail " . $widthHeight . " " . $pathToThumbs;
			//-----------thumbnails will be set like widht X height-------------
			$last_line = system($thumb_command, $retval);
			//echo $thumb_command = "convert \"".$pathToImages."\" -thumbnail 100x80 \"".$pathToThumbs."\"";
			//$last_line = system("convert \"".$pathToImages."\" -thumbnail 100x80 \"".$pathToThumbs."\""." 2>&1", $retval);
			return $imagename;
		} else {
			return 'problemImage';
		}
	} else {
		return 'invalidImage';
	}
}
function GenerateThumbnailInGD($imageArr, $width, $height, $dirpath, $newName = '') {
	global $image;
	$size = $imageArr["size"];
	$name = $imageArr["name"];
	$tmp_name = $imageArr["tmp_name"];
	$type = $imageArr["type"];
	if ($size > 0) {
		if ($type == "image/pjpeg" || $type == "image/bmp" || $type == "image/jpeg" || $type == "image/gif") {
			$ext = strtoupper(substr($name, strlen($name) - 4));
			$name = $newName . $ext;
			$TmpName_File = $tmp_name;
			$image->load($TmpName_File);
			$image->resize($width, $height);
			if (file_exists($dirpath)) {
				$image->save($dirpath . "/" . $name);
				return $name;
			} else {
				return "dir not exist";
			}

		} else {
			return "not a valid type";
		}
	} else {
		return "Please select files";
	}
}
function GenerateThumbnail($varPhoto, $uploadDir, $tmp_name, $th_arr = array(), $file_nm = '', $doc = false, $crop_coords = array()) {
	//$ext=strtoupper(substr($varPhoto,strlen($varPhoto)-4));die;
	$ext = '.' . strtoupper(substr($varPhoto, strrpos($varPhoto, ".") + 1));
	$tot_th = count($th_arr);
	if (($ext == ".JPG" || $ext == ".GIF" || $ext == ".PNG" || $ext == ".BMP" || $ext == ".JPEG")) {
		if ($file_nm == '') {
			$imagename = rand() . time() . $ext;
		} else {
			$imagename = $file_nm . $ext;
		}

		$pathToImages = $uploadDir . $imagename;
		$Photo_Source = copy($tmp_name, $pathToImages);

		if ($Photo_Source) {
			for ($i = 0; $i < $tot_th; $i++) {
				resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imagename, $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
			}
			unlink($pathToImages);
			return $imagename;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function resizeImage($filename, $newfilename = "", $max_width, $max_height = '', $withSampling = true, $crop_coords = array()) {

	if ($newfilename == "") {
		$newfilename = $filename;
	}

	$fileExtension = strtolower(strrchr($filename, '.'));
	if ($fileExtension == ".jpg" || $fileExtension == ".jpeg") {
		$img = imagecreatefromjpeg($filename);
	} else if ($fileExtension == ".png") {
		$img = imagecreatefrompng($filename);
	} else if ($fileExtension == ".gif") {
		$img = imagecreatefromgif($filename);
	} else {
		$img = imagecreatefromjpeg($filename);
	}

	$width = imageSX($img);
	$height = imageSY($img);

	// Build the thumbnail
	$target_width = $max_width;
	$target_height = $max_height;
	$target_ratio = $target_width / $target_height;
	$img_ratio = $width / $height;

	if (empty($crop_coords)) {

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
		$new_img = ImageCreateTrueColor($target_width, $target_height);

		//$new_img = imagecreatetruecolor($new_width, $new_height);
		$white = imagecolorallocate($new_img, 255, 255, 255);
		@imagefilledrectangle($new_img, 0, 0, $target_width - 1, $target_height - 1, $white);
		@imagecopyresampled($new_img, $img, ($target_width - $new_width) / 2, ($target_height - $new_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
		// @imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	} else {
		$new_img = imagecreatetruecolor($target_width, $target_height);
		@imagecopyresampled($new_img, $img, 0, 0, $crop_coords['x1'], $crop_coords['y1'], $target_width, $target_height, $crop_coords['x2'], $crop_coords['y2']);
	}

	if ($fileExtension == ".jpg" || $fileExtension == ".jpeg") {
		$createImageSave = imagejpeg($new_img, $newfilename, 100);
	} else if ($fileExtension == '.png') {
		$createImageSave = imagepng($new_img, $newfilename, 9);
	} else if ($fileExtension == ".gif") {
		$createImageSave = imagegif($new_img, $newfilename, 100);
	} else {
		$createImageSave = imagejpeg($new_img, $newfilename, 100);
	}

}

function checkImage($imagePath, $imageName) {
	if (is_file(DIR_UPD . $imagePath . $imageName)) {
		return SITE_UPD . $imagePath . $imageName;
	} else {
		return SITE_ADM_IMG . 'no_image_thumb.gif';
	}
}
function getExt($file) {
	$path_parts = pathinfo($file);
	$ext = $path_parts['extension'];
	return $ext;
}
function ConverCurrency($amount, $from_currency, $to_currency) {
	$string = $amount . strtolower($from_currency) . "=?" . strtolower($to_currency);
	$google_url = "http://www.google.com/ig/calculator?hl=en&q=" . $string;
	$result = file_get_contents($google_url);
	$result = explode('"', $result);
	$confrom = explode(' ', $result[1]);
	$conto = explode(' ', $result[3]);
	return $conto[0];
}
function getHelpImg($title = '') {
	return '<img src="' . SITE_IMG . 'help-icon.png" alt="help" class="vtip" title="' . $title . '"/>';
}

function getStrToArray($str, $sep = ',') {
	$retArr = array();
	$pos = strpos($str, $sep);
	if ($str != '') {
		if ($pos !== false) {
			$retArr = explode($sep, $str);
		} else {
			$retArr[] = $str;
		}

	} else {
		$retArr = array();
	}

	return $retArr;
}

function getUserDetail($userId, $returnWhat) {
	global $db;
	$qSelUser = $db->select('tbl_users', $returnWhat, "uId=" . (int) $userId, "", "", 0);
	if (mysql_num_rows($qSelUser) > 0) {
		$fetchRes = mysql_fetch_object($qSelUser);
		if ($returnWhat == '*') {
			return $fetchRes;
		} else {
			return $fetchRes->$returnWhat;
		}
	}
}
function grabNameFromEmail($email) {
	$emailArr = array();
	$emailArr = explode('@', $email);
	return trim($emailArr[0]);
}

function escapeSearchString($srch) {
	if (is_object($srch)) {
		$res = new stdClass();
		foreach ($srch as $k => $v) {
			$res->$k = trim(mysql_real_escape_string(str_replace(array(
				'_',
				'%',
			), array(
				'\_',
				'\%',
			), $v)));
		}
	} else {
		$res = trim(mysql_real_escape_string(str_replace(array(
			'_',
			'%',
		), array(
			'\_',
			'\%',
		), $srch)));
	}
	return $res;
}
function removeAccents($s, $d = true) {
	$s = str_replace('ó', 'o', $s);
	if ($d) {
		$s = utf8_encode($s);
	}

	$chars = array(
		'_' => '/`|´|\^|~|¨|ª|º|©|®/',
		'a' => '/à|á|â|ã|ä|å|æ/',
		'e' => '/è|é|ê|ë/',
		'i' => '/ì|í|î|ĩ|ï/',
		'o' => '/ò|ó|ô|õ|ö|ó|ø/',
		'u' => '/ù|ú|û|ű|ü|ů/',
		'A' => '/À|Á|Â|Ã|Ä|Å|Æ/',
		'E' => '/È|É|Ê|Ë/',
		'I' => '/Ì|Í|Î|Ĩ|Ï/',
		'O' => '/Ò|Ó|Ô|Õ|Ö|ó|Ø/',
		'U' => '/Ù|Ú|Û|Ũ|Ü|Ů/',
		'c' => '/ć|ĉ|ç/',
		'C' => '/Ć|Ĉ|Ç/',
		'n' => '/ñ/',
		'N' => '/Ñ/',
		'y' => '/ý|ŷ|ÿ/',
		'Y' => '/Ý|Ŷ|Ÿ/',
	);
	$s = trim($s);
	$s = str_replace("&#39;", "'", $s);
	$s = str_replace("&#34;", "\"", $s);
	$s = str_replace(" ", "_", $s);
	$s = str_replace("-", "", $s);
	$s = str_replace("?", "", $s);
	$s = str_replace("&", "", $s);
	$s = str_replace("'", "", $s);
	$s = str_replace("\"", "", $s);
	return str_replace(" ", "-", strtolower(preg_replace($chars, array_keys($chars), $s)));
}
function AccessPermissions() {
	return true;
}
function time_elapsed_string($ptime) {
	/*$ptime = strtotime($ptime);
		    $etime = time() - $ptime;

		    if ($etime < 1)
		    {
		        return '0 seconds';
		    }

		    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
		                30 * 24 * 60 * 60       =>  'month',
		                24 * 60 * 60            =>  'day',
		                60 * 60                 =>  'hour',
		                60                      =>  'minute',
		                1                       =>  'second'
		                );

		    foreach ($a as $secs => $str)
		    {
		        $d = $etime / $secs;
		        if ($d >= 1)
		        {
		            $r = round($d);
		            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
		        }
	*/
	$time = strtotime($ptime);
	$periods = array("초", "분", "시간", "일", "주", "달", "년", "decade");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "10");

	$now = time();
	$difference = $now - $time;
	$tense = "전";

	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
		$difference /= $lengths[$j];
	}
	$difference = round($difference);
	if ($difference != 1) {$periods[$j] .= "";}
	return "$difference$periods[$j] 전";
}
function get_time_difference($start, $end) {
	$uts['start'] = strtotime($start);
	$uts['end'] = strtotime($end);
	if ($uts['start'] !== -1 && $uts['end'] !== -1) {
		if ($uts['end'] >= $uts['start']) {
			$diff = $uts['end'] - $uts['start'];
			if ($days = intval((floor($diff / 86400)))) {
				$diff = $diff % 86400;
			}

			if ($hours = intval((floor($diff / 3600)))) {
				$diff = $diff % 3600;
			}

			if ($minutes = intval((floor($diff / 60)))) {
				$diff = $diff % 60;
			}

			$diff = intval($diff);
			if ($days >= 1) {
				return $days . "일 전";
			} else if ($hours >= 1) {

				return $hours . "시간 전";
			} else if ($minutes >= 1) {

				return $minutes . "분 전";
			} else {

				return " 조금 전";
			}

		}
		/* else
			        {
			            trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
		*/
	} else {
		trigger_error("Invalid date/time data detected", E_USER_WARNING);
	}
	return (false);
}

function getListingCount() {
	global $db;
	$cnt = 0;
	return numberFormat(getTotalRows($db, 'tbl_post', 'isActive="y"', 'id'));
}

function numberFormat($num) {
	return number_format($num, 0, '', ',');
}

function getCategoryid($catName) {
	global $db;
	return getTableValue($db, " tbl_categories", "categoryName LIKE '" . htmlentities($catName) . "'", "id", 0);
}
function getCategoryName($id) {
	global $db;
	return getTableValue($db, " tbl_categories", "id='" . $id . "'", "categoryName", 0);
}

function getCategoryListingCount($catId) {
	global $db;
	$cnt = 0;
	$qry = $db->query("select tbl_post.id from tbl_post inner join  tbl_categories on (tbl_post.catId= tbl_categories.id) where tbl_post.isActive='y' and tbl_post.catId='" . $catId . "'");
	$totRow = mysql_num_rows($qry);
	return $totRow;
}

function getLinkListingCount($linkA = '') {
	global $db;
	$cnt = 0;
	$qry = $db->query("select id from tbl_post where isActive='y' and url like '%" . $linkA . "%'");
	$totRow = mysql_num_rows($qry);
	return $totRow;
}

function getVotingTotal($id, $type, $oncontent) {
	global $db;
	$vote_rec_res = $db->query("SELECT COUNT(id) AS total FROM tbl_votes WHERE refType = '" . $oncontent . "' AND refId = '" . $id . "' AND voteType = '" . $type . "'");
	$vote_rec = mysql_fetch_assoc($vote_rec_res);
	$vote = $vote_rec["total"];
	return $vote;
}
function VotingNow($ref_id, $type, $oncontent) {
	global $db, $sessUserId;
	if ($sessUserId > 0) {
		$sel_qry_res = $db->select("tbl_votes", "id", "refType='" . $oncontent . "' AND refId = '" . $ref_id . "' AND uId = '" . $sessUserId . "' AND voteType = '" . $type . "'");

		if (mysql_num_rows($sel_qry_res) > 0) {
			$sel_qry_row = mysql_fetch_assoc($sel_qry_res);
			$db->query("DELETE FROM tbl_votes WHERE id = '" . $sel_qry_row["id"] . "'");
		} else {
			$ins["uId"] = $sessUserId;
			$ins["refId"] = $ref_id;
			$ins["refType"] = $oncontent;
			$ins["voteType"] = $type;
			$ins["createdDate"] = date("Y-m-d H:i:s");
			$ins["ipAddress"] = get_ip_address();
			$db->insert("tbl_votes", $ins);

		}

		// 포스트 테이블에 업데이트 루틴 추가 by kiere@naver.com 2015.5.12
		$_where="id='".$ref_id."'";
		$post=getPostContent($ref_id);
		$update=array();
		if($type=='u'){
		    $update['v_up']=$post['v_up']+1;
		    $v_up=$update['v_up'];
		    $v_down=$post['v_down'];  	
		}else{
		    $update['v_down']=$post['v_down']+1;
		    $v_up=$post['v_up'];
		    $v_down=$update['v_down']; 	
		} 
		$update['r_score']=getRankScore($v_up,$v_down,$post['createdDate']);
          $db->update('tbl_post',$update,$_where);

		return totalVote($ref_id, $oncontent);
	}
}

function totalVote($ref_id, $oncontent) {
	$totalDvote = getVotingTotal($ref_id, "d", $oncontent);
	$totalUvote = getVotingTotal($ref_id, "u", $oncontent);

	$finalVote = (int) $totalUvote - (int) $totalDvote;
	if ($finalVote < 0) {
		$finalVote = 0;
	}
	return $finalVote;
}

function getHide($ref_id, $oncontent) {
	global $db, $sessUserId;

	$arr["status"] = 100;

	if ($sessUserId > 0) {
		$is_in_db = $db->select("tbl_hide", "id", "refId='" . $ref_id . "' AND uId = '" . $sessUserId . "' AND refType = '" . $oncontent . "'");
		if (mysql_num_rows($is_in_db) <= 0) {
			$ins["uId"] = $sessUserId;
			$ins["refId"] = $ref_id;
			$ins["refType"] = $oncontent;
			$ins["createdDate"] = date("Y-m-d H:i:s");
			$ins["ipAddress"] = get_ip_address();
			$db->insert("tbl_hide", $ins);
			$arr["status"] = 200;
		} else {
			$db->delete("tbl_hide", "refId='" . $ref_id . "' AND uId='" . $sessUserId . "' AND refType = '" . $oncontent . "'", "");
			$arr["status"] = 200;
		}
	}
	return $arr;
}

function getPlan() {
	global $db, $sessUserId;

	$qrydb = $db->select(" tbl_memberplan", "*", "isActive='y'");

	if (mysql_num_rows($qrydb) >= 0) {

	}
}
function getSave($ref_id) {
	global $db, $sessUserId;

	$arr["status"] = 100;

	if ($sessUserId > 0) {
		$qry_db = $db->select("tbl_save", "id", "refId='" . $ref_id . "' AND uId = '" . $sessUserId . "'");

		if (mysql_num_rows($qry_db) <= 0) {
			$inssave["uId"] = $sessUserId;
			$inssave["refId"] = $ref_id;
			$inssave["createdDate"] = date("Y-m-d H:i:s");
			$inssave["ipAddress"] = get_ip_address();
			$db->insert("tbl_save", $inssave);
			$arr["status"] = 201; // For Insert Status
		} else {
			$qry_db_row = mysql_fetch_assoc($qry_db);
			$db->delete("tbl_save", "id", $qry_db_row["id"]);
			$arr["status"] = 202; // For Delete Status
		}
	}
	return $arr;
}

function getReport($ref_id, $oncontent) {
	global $db, $sessUserId;

	if ($sessUserId > 0) {
		$in_db_report = $db->select("tbl_report", "id", "refId='" . $ref_id . "' AND uId = '" . $sessUserId . "' AND refType = '" . $oncontent . "'");
		if (mysql_num_rows($in_db_report) <= 0) {
			$insreport["uId"] = $sessUserId;
			$insreport["refId"] = $ref_id;
			$insreport["refType"] = $oncontent;
			$insreport["createdDate"] = date("Y-m-d H:i:s");
			$insreport["ipAddress"] = get_ip_address();
			$db->insert("tbl_report", $insreport);
			$db->insert("tbl_hide", $insreport);
		}
	}

}

function getPostContent($ref_id) {
	global $db, $sessUserId;

	if ($sessUserId > 0) {
		$selRes = "SELECT  *
					FROM  tbl_post
					WHERE tbl_post.isActive = 'y'
					AND tbl_post.id = '" . $ref_id . "' ";

		$qry_db = $db->query($selRes);

		$html_content = NULL;
		if (mysql_num_rows($qry_db) > 0) {
			$row = mysql_fetch_assoc($qry_db);
		} else {
			$row = NULL;
		}
	} else {
		$row = NULL;
	}
	return $row;
}

function getsubComment($commentId, $postid) {
	$subcontent = NULL;
	global $db, $sessUserId;
	$subCMTQuery = "SELECT tbl_comment.*, tbl_users.username FROM tbl_comment LEFT JOIN tbl_users ON (tbl_comment.uId = tbl_users.id) WHERE (tbl_comment.refId ='$commentId' AND tbl_comment.refType ='c')";
	$subCMTSql = $db->query($subCMTQuery);
	if (mysql_num_rows($subCMTSql) > 0) {
		while ($subRes = mysql_fetch_assoc($subCMTSql)) {
			if (isset($sessUserId) && $sessUserId > 0) {
				$islogin = "yes";
			} else {
				$islogin = 'no';
			}
			$subcurrentTime = date("Y-m-d H:i:s");
			$subcommentid = $db->filtering($subRes["id"], 'output', 'int', '');
			$subcomment = $db->filtering($subRes["comment"], 'output', 'text', '');
			$subusername = $db->filtering($subRes["username"], 'output', 'string', '');
			$subcommentDate = $subRes["createdDate"];
			$subdateDiff = get_time_difference($subcommentDate, $subcurrentTime);
			$Subcomment = getsubComment($subcommentid, $postid);
			$CmtPoint = getCommentPoint($subcommentid);
			$total_vote = totalVote($subcommentid, 'c');

			$subcontent .= '<div class="comment_list">
				<div class="comment_list_two">
                	<div class="icons-wrapper">
                        <a href="javascript:void(0);" class="icon document" onclick="voting(\'' . $subcommentid . '\',\'u\',\'c\',\'' . $islogin . '\',this.id)">UP</a>
                        <a href="javascript:void(0);" class="icon group" onclick="voting(\'' . $subcommentid . '\',\'d\',\'c\',\'' . $islogin . '\',this.id)">DOWN</a>
                    </div>
                    <div class="comment_list_details">
                    	<span><a href="../../user/' . $subusername . '/overview/">' . $subusername . ' </a><span id="vote-' . $subcommentid . '">  ' . $total_vote . '</span> points ' . $subdateDiff . '</span>
                        <p>' . $subcomment . '</p>
                        <div class="delete_report">
                        	<a href="javascript:void(0);" onclick="getReport(\'' . $subcommentid . '\',\'c\',\'' . $islogin . '\');">Report</a><span class="re-btn"><a href="javascript:void(0);" onclick="generatesubcomment(\'' . $subcommentid . '\',\'' . $postid . '\',\'' . $islogin . '\');">Reply</a></span>
							<div id="subcommentformbox' . $subcommentid . '"></div>
                        </div>
                    </div>
					<div id="subcmtbox' . $subcommentid . '"></div>
					' . $Subcomment . '
                </div>

				</div>';
		}
	} else {
		$subcontent = '';
	}
	return $subcontent;
}

function selectLanguage($selFurl) {
	global $db, $lId;
	$content = '';
	$url = '';
	$qry = $db->select('tbl_language', '*', 'isActive="y"');

	//$content .='<form action="" method="get" name="langfrm" id="langfrm">
	//<select name="language" onchange="document.langfrm.submit()">"';
	$content .= "<ul class='about'><h3>Languages</h3>";
	while ($row = mysql_fetch_array($qry)) {
		//if($lId==$row["id"]){$cond='selected="selected"';}else{$cond='';}
		//$content.='<option value="'.$row["id"].'"'.$cond.'>'.$row["languageName"].'</option>"';
		if ($_SERVER["QUERY_STRING"] != "") {
			$str = '';
			foreach ($_GET as $x => $z) {
				if ($x != "language" && $x != "languagesel") {
					$str .= '&' . $x . '=' . $z;
				}
			}
			//$str=ltrim($str,'&');
			$url = $str;
		}

		$content .=
			'<li>
            <a href="javascript:void(0);" onclick="setLanguage(\'' . $row["id"] . '\',\'' . $selFurl . '\');">
                <img src="' . SITE_UPD . 'flag/' . $row["langflag"] . '" alt="' . $row["languageName"] . '" title="' . $row["languageName"] . '" height="20" width="20"/>
            </a>
        </li>';
	}
	$content .= "</ul>";
	//$content.='</select>
	//<input type="hidden" name="languagesel" value="languagesel">
	//</form>';
	return $content;

}
function getUserType($userId) {
	global $db;
	$memberType = $db->select("tbl_users", "isMember", "id='" . $userId . "'", '', 0);
	$memberRes = mysql_fetch_assoc($memberType);
	return $memberRes["isMember"];
}
function postlisthtml($fetchValues, $withclass = true) {
	/*print "<pre>";
		print_r($fetchValues);
	print "</pre>";*/

	global $db, $sessUserId;
	$pDetail = NULL;
	$id = $db->filtering($fetchValues["id"], 'output', 'int', '');
	$title = $db->filtering($fetchValues["title"], 'output', 'string', '');
	$img = $fetchValues["img"];
	$categoryName = $db->filtering($fetchValues["categoryName"], 'output', 'string', '');
	$username = $db->filtering($fetchValues["username"], 'output', 'string', '');
	$url = $db->filtering($fetchValues["url"], 'output', 'text', '');
	$type = $db->filtering($fetchValues["type"], 'output', 'string', '');
	$createdDate = $db->filtering($fetchValues["createdDate"], 'output', 'string', '');
	$isSponcer = $db->filtering($fetchValues["isSponcer"], 'output', 'string', '');
	$embeddcode = $db->filtering($fetchValues["embeddcode"], 'output', 'text', '');
	$isimageUrl = $db->filtering($fetchValues["isimageUrl"], 'output', 'string', '');

	$cuttentDate = date('Y-m-d h:i:s');
	$dateDiff = time_elapsed_string($createdDate);
	if (isset($sessUserId) && $sessUserId > 0) {
		$islogin = "yes";
	} else {
		$islogin = 'no';
	}
	/*Get Saved Record start*/
	$is_save = mysql_num_rows($db->select("tbl_save", "id", "refId = '" . $id . "' AND uId='" . $sessUserId . "'"));
	$is_save_val = $is_save > 0 ? "Unsave" : "" . SAVE . "";
	/*Get Saved Record Over*/
	/*Get Reported Record start*/
	$is_report = mysql_num_rows($db->select("tbl_report", "id", "refId = '" . $id . "' AND refType = 'p' AND uId='" . $sessUserId . "'"));
	if ($is_report > 0) {
		$reportlink = '<div class="report">
							<a href="javascript:void(0);" title="' . REPORTED . '" id="report-' . $id . '">' . REPORTED . '</a>
						 </div>';
	} else {
		$reportlink = '<div class="report">
						<a href="javascript:void(0);" title="' . REPORT . '" onclick="getReport(\'' . $id . '\',\'p\',\'' . $islogin . '\');" id="report-' . $id . '">' . REPORT . '</a>
						 </div>';
	}
	/*Get Reported Record Over*/
	/*Title Url Code start*/
	if ($type == 'l') {
		$titleUrl = '<a href="' . $url . '" target="_self" title="' . seoString(substr($title, 0, 75)) . '" onclick="makehistory(\'' . $id . '\',\'' . $sessUserId . '\');">' . $title . '</a>';
	} else {
		//$titleUrl = '<a href="'.SITE_URL.'detail/'.$id.'/'.urlencode(seoString(substr($title,0,20))).'" title="'.seoString(substr($title,0,75)).'" onclick="makehistory(\''.$id.'\',\''.$sessUserId.'\');">'.substr($title,0,75).'</a>';
		$titleUrl = '<a href="' . SITE_URL . 'detail/' . $id . '/' . urlencode(seoString($title)) . '" title="' . seoString(substr($title, 0, 75)) . '" onclick="makehistory(\'' . $id . '\',\'' . $sessUserId . '\');">' . $title . '</a>';
	}
	/*Title Url Code Over*/
	/*Subimtted post portion start*/
	$TabType = (isset($_GET["type"])) ? $db->filtering($_GET["type"], 'input', 'text', '') : "";

	if ($TabType == "submitted" && $sessUserId > 0) {
		$isMember = getUserType($sessUserId);
		if ($isMember == 'y' && $isSponcer == 'n') {
			$linkSponcer = '<div class="sponcer">
						<a href="javascript:void(0);" onclick="MakeSponcer(\'' . $id . '\',\'p\',\'' . $islogin . '\');" id="sponcered-' . $id . '">Sponsor</a>
						 </div>';
			$linkHidden = '<div class="hide">
							<a href="javascript:void(0);" title="' . HIDE . '" onclick="post_hide(\'' . $id . '\',\'p\',\'' . $islogin . '\')">' . HIDE . '</a>
						</div>';
		} else {
			$linkSponcer = "";
			$linkHidden = '<div class="hide">
							<a href="javascript:void(0);" title="' . HIDE . '" onclick="post_hide(\'' . $id . '\',\'p\',\'' . $islogin . '\')">' . HIDE . '</a>
						</div>';
		}
	} else if ($TabType == "hidden" && $sessUserId > 0) {
		$linkHidden = '<div class="hide">
							<a href="javascript:void(0);" title="' . UNHIDE . '" onclick="post_unhide(\'' . $id . '\',\'p\',\'' . $islogin . '\')">' . UNHIDE . '</a>
						</div>';
		$linkSponcer = "";
	} else {
		$linkHidden = '<div class="hide">
							<a href="javascript:void(0);" title="' . HIDE . '" onclick="post_hide(\'' . $id . '\',\'p\',\'' . $islogin . '\')">' . HIDE . '</a>
						</div>';
		$linkSponcer = "";
	}

	/*Submited post portion end*/
	$total_vote = totalVote($id, 'p');
	if ($withclass == true) {
		$pDetail .= '<li class="box" id="li-post-' . $id . '">';
	} else {
		$pDetail .= '<li id="li-post-' . $id . '">';
	}
	if ($sessUserId > 0) {
		$sqlVoteType = $db->select("tbl_votes", "voteType", "refType='p' and refId='$id' and uId='$sessUserId'", '', 'id desc limit 0,1', 0);
		if (mysql_num_rows($sqlVoteType) > 0) {
			$voteRes = mysql_fetch_assoc($sqlVoteType);
			if ($voteRes["voteType"] == 'd') {
				$downACt = 'iconact';
				$upACt = 'icon';
			} else {
				$upACt = 'iconact';
				$downACt = 'icon';
			}
		} else {
			$upACt = 'icon';
			$downACt = 'icon';
		}
	} else {
		$upACt = 'icon';
		$downACt = 'icon';
	}
	$catId = getCategoryid($categoryName);
	$pDetail .= '<div class="icons-wrapper">
						<a href="javascript:void(0);" class="' . $upACt . ' document up" id="up' . $id . '" onclick="voting(\'' . $id . '\',\'u\',\'p\',\'' . $islogin . '\',this.id)">UP</a>
						<div class="like_point" id="vote-' . $id . '">' . $total_vote . '</div>
						<a href="javascript:void(0);" class="' . $downACt . ' group down" id="down' . $id . '" onclick="voting(\'' . $id . '\',\'d\',\'p\',\'' . $islogin . '\',this.id)" >DOWN</a>
					 </div>';

	if ($isimageUrl == "y") {
		$pDetail .= '<div class="hot_item_img">
						<a href="javascript:void(0);" title="' . seoString($title) . '"><img src="' . $img . '" height="98px" width="98px"/>
						</a>
					</div>';
	} else if ($isimageUrl == "n" && $img != "") {
		$pDetail .= '<div class="hot_item_img">
						<a href="javascript:void(0);" title="' . seoString($title) . '">
							<img src="' . SITE_UPD . 'post/98x98/th1_' . $img . '" />
						</a>
					</div>';
	} /*else {
		$pDetail .= '<div class="hot_item_img"><img src="' . SITE_IMG . 'no_img.jpg" /></div>';
	}*/

	$pDetail .=
		'<div class="hot_item_heading">
						<div>' . $titleUrl;
	if (getDomainFROMUrl($url) != NULL) {
		$pDetail .= '&nbsp;&nbsp;&nbsp;&nbsp;<p><a href="' . SITE_URL . 'domain/' . newgetDomainFROMUrl($url) . '" target="_self">(' . newgetDomainFROMUrl($url) . ')</a></p>';
	}
	$pDetail .= '</div>
						' . ($embeddcode != "" || $embeddcode != NULL ?
		'<div class="dvembedd">
							<a href="javascript:void(0);" onclick="showHideEmbedd(\'' . $id . '\',\'' . SITE_IMG . '\');" >
								<img src="' . SITE_IMG . 'playnew.png" id="imgembeddbutton-' . $id . '" class="pp"/>
							</a>
						</div>' : NULL) . '
						<p>' . $dateDiff . ' <a href="' . SITE_URL . 'user/' . $username . '/overview/">' . $username . '</a> ' . BY . ' <a href="' . SITE_URL . 'c/' . $categoryName . '">/c/' . $categoryName . '</a> 에 ' . SUBMITTED . '</p>
						<p>
						    <span>총 Up 투표수 : '.$fetchValues['v_up'].'</span><br/>
						    <span>총 Down 투표수 : '.$fetchValues['v_down'].'</span><br/>
						    <span>포스트 작성일 : '.$createdDate.'</span><br/>
						    <span>순위 점수  : '.$fetchValues['r_score'].'</span>
						</p>
						<div class="comment_txt">
							<a href="' . SITE_URL . 'comments/' . $id . '/' . $title . '" title="' . COMMENTS . '">' . getTotalComments($id) . COMMENTS . '</a>
						</div>
						<div class="share">
							<a href="javascript:void(0);" title="' . SHARE . '" class="share-link" id="share-' . $id . '" onclick="getsharedialog(\'' . $id . '\',\'' . $islogin . '\')">' . SHARE . '</a>
							<div id="sharedialogbox' . $id . '"></div>
						</div>
						' . $linkHidden . '
						<div class="save">
							<a href="javascript:void(0);" title="' . $is_save_val . '" onclick="getsave(\'' . $id . '\',\'' . $islogin . '\')" id="save-' . $id . '">' . $is_save_val . '</a>
						</div>
						' . $reportlink . $linkSponcer . '
						<div class="flclear"></div>
						<div id="embedd-' . $id . '" class="embedd">
							<div class="flclear"></div>' . $embeddcode . '<div class="flclear"></div>
						</div>
						<div class="flclear"></div>
					</div>
				</li>';

	return $pDetail;
}
function getTotalComments($postid) {
	global $db;
	$CountSql = $db->query("select count(id) as totalcomment from tbl_comment where postId='" . $postid . "'");
	$countSqlRes = mysql_fetch_assoc($CountSql);
	return $total = $CountSql["totalcomment"];

}
function getCommentPoint($commentId) {
	global $db;
	$TotalvoteSql = $db->query("select count(id) as totalvote from tbl_votes where refId='" . $commentId . "' and refType='c' and voteType='u'");
	$totalVoteRes = mysql_fetch_assoc($TotalvoteSql);
	return $toatlVote = $totalVoteRes["totalvote"];
}

function selfURL() {
	if (!isset($_SERVER['REQUEST_URI'])) {
		$serverrequri = $_SERVER['PHP_SELF'];
	} else {
		$serverrequri = $_SERVER['REQUEST_URI'];
	}
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strLeft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
}

function strLeft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}

function view_msgbox($type = "inbox", $id = NULL, $only_view = false) {
	global $db, $sessUserId;
	$arry = array();
	$condition = NULL;
	$status = NULL;
	$fld = "";
	$group_by = "";

	$session_user_id = $sessUserId;

	switch ($type) {
	case 'inbox':$condition = ' AND to_id = "' . $session_user_id . '" AND trash_to = "n" ';
		$status = '"a"';
		$condition .= ($id != NULL ? " AND id = '" . $id . "' " : NULL);
		if ($only_view) {
			$condition .= " AND read_to = 'n' ";
		}

		break;

	case 'sent':$condition = ' AND from_id = "' . $session_user_id . '" AND trash_from = "n" ';
		$status = '"a"';
		//$fld = " GROUP_CONCAT( tbl_message.to_id ) AS to_ids ";
		break;

	case 'trash':$condition = ' AND ((from_id = "' . $session_user_id . '" AND trash_from = "y") OR (to_id = "' . $session_user_id . '" AND trash_to = "y"))';
		//$fld = " GROUP_CONCAT( tbl_message.to_id ) AS to_ids ";

		$status = '"a"';

		break;
	}
	$fld = ($fld != '') ? "," . $fld : "";

	$order = " ORDER BY tbl_message.id DESC";
	$qry = "SELECT *" . $fld . " FROM tbl_message WHERE status IN(" . $status . ")" . $condition . $group_by . $order;
	$res = $db->query($qry);
	if (mysql_num_rows($res) > 0) {
		while ($row = mysql_fetch_assoc($res)) {
			$tmp["id"] = $row["id"];
			$tmp["msg"] = ($row["message"]);
			$tmp["subject"] = $row["subject"] != NULL ? $row["subject"] : "No Subject";
			$tmp["read_from"] = $row["read_from"];
			$tmp["read_to"] = $row["read_to"];
			$tmp["date"] = date('jS M y h:i:s a', strtotime(str_replace('/', '-', $row["createdDate"])));

			$fromRes = mysql_fetch_assoc($db->select("tbl_users", "username", "id = '" . $row["from_id"] . "'"));
			$fromName = $fromRes['username'];
			$tmp["from_id"] = $row["from_id"];
			$tmp["from_name"] = $fromName;

			$toRes = mysql_fetch_assoc($db->select("tbl_users", "username", "id = '" . $row["to_id"] . "'"));
			$toName = $toRes['username'];
			$tmp["to_id"] = $row["to_id"];
			$tmp["to_name"] = $toName;

			$tmp["trash_from"] = $row["trash_from"];
			$tmp["trash_to"] = $row["trash_to"];

			//$tmp["rply"]= ($rply_req ? view_msg_mailbox($type,false,$row["id"]) : array());
			if ($id != NULL) {
				$arry = $tmp;
			} else {
				$arry[] = $tmp;
			}
 
		}
	}

	return $arry;
}
function getclickPrice($pageid, $slotid) {
	global $db;
	$costcount = $db->select("tbl_ads_cost", "cost", "pageId='" . $pageid . "' AND slotId='" . $slotid . "'");
	$costres = mysql_fetch_assoc($costcount);
	return $costres;
}
function seoString($s) {return strtolower(trim(preg_replace('/[^a-zA-Z0-9가-힣]+/', '-', $s), '-'));}
function getDomainFROMUrl($url) {
	if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === FALSE) {
		return false;
	}
	/*** get the url parts ***/
	$parts = parse_url($url);
	/*** return the host domain ***/
	return $parts['scheme'] . '://' . $parts['host'];
}
function newgetDomainFROMUrl($url) {
	$domain = getDomainFROMUrl($url);
	$domain = str_replace("http://", "", $domain);
	$domain = str_replace("https://", "", $domain);
	$domain = str_replace("www.", "", $domain);
	return $domain;
}
// 날짜비교 함수 추가 by kiere@naver.com 2016.5.13 
function get_time_difference2($start, $end) {
	$uts['start'] = strtotime($start);
	$uts['end'] = strtotime($end);
	if ($uts['start'] !== -1 && $uts['end'] !== -1) {
		if ($uts['end'] >= $uts['start']) {
			$diff = $uts['end'] - $uts['start'];	
			$days=intval((floor($diff / 86400)));
			$hours = intval((floor($diff / 3600)));
			$minutes = intval((floor($diff / 60)));
			$seconds=$minutes*60;
			return array('diff'=>$diff,'days'=>$days,'minutes'=>$minutes,'seconds'=>$seconds);
		}

	} else {
		trigger_error("Invalid date/time data detected", E_USER_WARNING);
	}
	return (false);
}
// 순위점수 추출 함수 추가 by kiere@naver.com 2016.5.13
function getRankScore($up,$down,$createDate){
      $s=(int)$up-(int)$down; 	
      $order=log(max(abs($s),1),10);
      if($s==0) $sign=0;
      else{
      	if($s>0) $sign=1;
      	else $sign=-1;
      }
      $start_date='1970-1-1 00:00:00';
      $end_date=$createDate;
	 $diff=get_time_difference2($start_date,$end_date);
	 $diff_val=$diff['days']*86400+$diff['seconds']+(floor($diff['seconds'])/1000000);
      $seconds=$diff_val-1134028003;
	 //return $diff['days']*86400;
	 return round($sign*$order+$seconds/45000,7);
}
// tbl_votes 에서 투표수 counting 해서 지정 테이블에 업데이트하는  함수 추가  by kiere@naver.com 2016.5.13
function resetVotesState($tableName){
	global $db;
     
     $res = $db->query("SELECT id,createdDate FROM ".$tableName);
	if (mysql_num_rows($res) > 0) {
		while ($row = mysql_fetch_assoc($res)) {
			$id=$row['id'];
			if($tableName=='tbl_post') $oncontent='P';
			$createdDate=$row['createdDate'];			
			$v_up_total=getVotingTotal($id, 'u', $oncontent); // 총 up 투표수
			$v_down_total=getVotingTotal($id, 'd', $oncontent); // 총 down 투표수
               $r_score=getRankScore($v_up_total,$v_down_total,$createdDate);
               // tbl_post 업데이트 
               $_where="id='".$id."'";
               $update=array();
               $update['v_up']=$v_up_total;
               $update['v_down']=$v_down_total;
               $update['r_score']=$r_score;
               $db->update($tableName,$update,$_where);
		}
	}   
}

?>
