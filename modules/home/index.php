<?php
	require_once("../../includes/config.php");
	require_once("class.home.php");
	require_once("../login/class.login.php");
	$right_panel=true;

	$module = 'home';
	$winTitle = SITE_NM.' - 미스터리 미제사건 UFO 찌라시 의혹 루머 네티즌 수사대';
    $headTitle = 'Home';
	$pageNo = isset($_GET['pageno']) ? (int)$_GET['pageno'] : 0;
	$mod = isset($_GET["mod"])?$db->filtering($_GET["mod"],'input','string',''):"";

	$metaTag = getMetaTags(array("description"=>$winTitle,
			"keywords"=>$headTitle,
			"author"=>SITE_NM));

	if(isset($_POST["submitLogin"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

		extract($_POST);
		$objPost->email = isset($email) ? base64_encode($db->filtering($email,'input','string', '')) : '';
		$objPost->password = isset($password) ? $db->filtering($password,'input','string', '') : '';
		//$objPost->remember = isset($remember) ? $db->filtering($remember,'input','string', '') : '';
		$objPost->remember =isset($_POST['remember']) ? $db->filtering($_POST['remember'],'input','string', '') : '';

		if($objPost->email != "" && $objPost->password != "") {
			$objUser = new Login('', $objPost);
			$loginReturn = $objUser->loginSubmit();
			$msgType = array('type'=>'err','var'=>$loginReturn);
		}
		else {
			$msgType = array('type'=>'err','var'=>"fillAllvalues");
		}

		if(isset($_COOKIE["remember"]) && $_COOKIE["remember"] == 'y') {
			$objPost->email =isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
			$objPost->password = isset($_COOKIE['password']) ? base64_decode($_COOKIE['password']) : '';
			$objPost->remember = 'y';
			}
	}


	if(isset($_GET["vcode"]) && $_GET["vcode"]!='')
	{
		$vcode = $db->filtering($_GET["vcode"],'output','text','');
		$ovcode = base64_decode($vcode);
		$vdata = explode("##",$ovcode);

		$vemail = $db->filtering($vdata[0],'input','string','');
		$vid = $db->filtering($vdata[1],'input','int','');

		$confusersql=$db->select("tbl_users",'id,isActive',"id='".$vid."' AND email = '".base64_encode($vemail)."'",'','',0);


		if(mysql_num_rows($confusersql) > 0)
		{
			$confres = mysql_fetch_assoc($confusersql);
			if($confres["isActive"]=='d' || $confres["isActive"]==''){

				$_SESSION["msgType"] = array('type'=>'err','var'=>'actBlockbyAdmin');
				redirectPage(SITE_URL);
				exit;
			}else{
				if($confres["isActive"]=='n' || $confres["isActive"]=='')
				{
					$objpost = new stdClass();
					$objpost->isActive = 'y';
					$db->update("tbl_users",$objpost,"id='$vid'",'');
					$_SESSION["msgType"] = array('type'=>'suc','var'=>'succActivateAccount');
					redirectPage(SITE_URL.'login');
					exit;
				}
				else
				{
					$_SESSION["msgType"] = array('type'=>'suc','var'=>'incorectActivate');
					redirectPage(SITE_URL);
					exit;
				}
			}
		}
		else
		{
			$_SESSION["msgType"] = array('type'=>'suc','var'=>'incorectActivate');
			redirectPage(SITE_URL);
			exit;
		}
	}
	$objHome = new Home($module, 0, 0,$objPost);

	//$mainContent = NULL;
	$mainContent = $objHome->homeContent($pageNo);
	//$mainContent .= $objHome->homeContent($pageNo,true);

	require_once(DIR_THEME."default.nct");
?>
