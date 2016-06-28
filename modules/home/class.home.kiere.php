<?php

class Home {
	private $id;
	private $db;
	private $fields;
	private $module;

	function __construct($module, $id = 0, $objPost = NULL) {
		global $fields, $db, $sessUserId, $sessUsername, $type;
		$this->db = $db;
		$this->module = $module;
		$this->id = $id;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
		$this->objPost = $objPost;
	}

	public function homeContent($pageNo) {
		$content = NULL;
		$homeAdCount = $this->db->query("select id from tbl_advertisement where pageId='1' and slotId='1'");
		if (mysql_num_rows($homeAdCount) > 0) {
			$content .= '<div class="adv_banner_1"></div>';
		}
		$content .=
		'<div class="flclear">&nbsp;</div>
		<div class="hot_listing">
			<ul class="bxslider">' .
		$this->spoceredlisting() .
			'</ul>
		</div>';

		if ($this->sessUserId > 0) {
			$content .=
				'<div class="clear"></div>
			<div class="host_left_tabs">
				<ul>';
			$sqlGetReddit = $this->db->select("tbl_multireddit", "multiRedditName,id", "userId='" . $this->sessUserId . "'", '', '', 0);
			$content .= '<li>채널모음</li>';

			while ($fetchRes = mysql_fetch_array($sqlGetReddit)) {
				$content .= '	<li><a href="' . SITE_URL . 'me/' . $fetchRes["id"] . '/' . $fetchRes['multiRedditName'] . '/" >' . $fetchRes['multiRedditName'] . '</a></li>';
			}
			$content .= '</ul>

			' . $this->fields->button(array("name" => "createRedditbtn", "type" => "submit", "value" => "Create", "extraAtt" => 'onClick="displayForm();"')) . '

			<form name="multiRedditForm" id="multiRedditForm" method="post" action="' . SITE_URL . 'me/">

			' . $this->fields->textBox(array("onlyField" => true, "name" => "multiRedditName", "id" => "multiRedditName", "extraAtt" => 'style="display:none"')) . '

			' . $this->fields->button(array("name" => "submitReddit", "type" => "submit", "value" => "Add", "extraAtt" => 'style="display:none"')) . '

			</form></div>';

		}

		$classOfListing = ($this->sessUserId > 0) ? '<div class="hot_listing hot_page_section">' : '<div class="hot_listing">';

		$content .= $classOfListing . '
			<ul id="container">
				' . $this->postlisting($pageNo) . '
			</ul>
		</div>';
		return $content;
	}

	public function postlisting($pageNo) {
		$content = NULL;
		$CATcond = '';
		$hidecond = '';
		if ($this->sessUserId > 0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='" . $this->sessUserId . "'", 'unsubscribeCat');
			$CATcond = ($unSubStr != '') ? 'AND A.catId NOT IN(' . $unSubStr . ')' : '';
			$sqlGetHidden = $this->db->select("tbl_hide", "refId", "uId='$this->sessUserId' AND refType='p'", '', '', 0);
			if (mysql_num_rows($sqlGetHidden) > 0) {
				while ($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='" . $HiddenItem["refId"] . "'";
				}
			} else {
				$hidecond = '';
			}
		}
		$whereCondition = $hidecond . $CATcond;
		$selRes = "SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, A.embeddcode FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<='" . date("Y-m-d 00:00:00") . "' where A.isActive='y' " . $whereCondition . " GROUP BY A.id ORDER BY A.v_up DESC, A.r_score DESC";
		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager = getPagerData($totalRows, LIMIT, $pageNo);
		if ($pageNo <= $pager->numPages) {
			$offset = $pager->offset;
			if ($offset < 0) {
				$offset = 0;
			}

			$limit = $pager->limit;

			$page = $pager->page;
			$selRes = $selRes . " limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);

			if ($totalRows > 0) {
				while ($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues);
				}
			}
		}
		return $content;
	}

	public function headerPanel($SesscatselectId, $mod) {
		global $module, $type;
		$content = NULL;
		$content =
			'<div id="header">
    	<div class="cetegory_top">
        	<div class="category_section">
			<a class="hover" href="javascript:showMe(1);">
				<span class="menu home">내 채널<img src="' . SITE_IMG . 'cetegory_arrow.png" /></span>
			</a>
			<div class="section home-things visible" id="div_one1" style="display: none;">
			  <div class="section-contents">
			  	<ul>';
		if ($this->sessUserId > 0) {
			$qrySelCategory = $this->db->query("SELECT * FROM tbl_categories WHERE isActive='y' AND uId = '$this->sessUserId' AND languageId = " . $_SESSION["lId"] . "");
			if (mysql_num_rows($qrySelCategory) > 0) {

				while ($fetchValues = mysql_fetch_array($qrySelCategory)) {
					$categoryName = $this->db->filtering($fetchValues["categoryName"], 'output', 'string', 'ucwords');
					$catId = getCategoryid($categoryName);
					$content .= '<li><a href="' . SITE_URL . 'c/' . urlencode(str_replace('/', ' ', $categoryName)) . '/" title="' . $categoryName . '">' . $categoryName . '</a></li> ';
				}
				$content .= '<li class="underlineli"></li>';
			}
			$qrySelCategory1 = $this->db->query("SELECT * FROM tbl_categories WHERE isActive='y' AND typeSubting='0' and uId != '$this->sessUserId' AND languageId = " . $_SESSION["lId"] . "");
			if (mysql_num_rows($qrySelCategory1) > 0) {
				while ($fetchValues1 = mysql_fetch_array($qrySelCategory1)) {
					$categoryName = $this->db->filtering($fetchValues1["categoryName"], 'output', 'string', '');
					$catId = getCategoryid($categoryName);
					$content .= '<li><a href="' . SITE_URL . 'c/' . urlencode(str_replace('/', ' ', $categoryName)) . '/" title="' . $categoryName . '">' . $categoryName . '</a></li> ';
				}
			}
		} else {
			$qrySelCategory = $this->db->query("SELECT * FROM tbl_categories WHERE isActive='y' AND typeSubting='0' AND languageId = " . $_SESSION["lId"] . "");
			if (mysql_num_rows($qrySelCategory) > 0) {
				while ($fetchValues = mysql_fetch_array($qrySelCategory)) {
					$categoryName = $this->db->filtering($fetchValues["categoryName"], 'output', 'string', '');
					$catId = getCategoryid($categoryName);
					$content .= '<li><a href="' . SITE_URL . 'c/' . urlencode(str_replace('/', ' ', $categoryName)) . '/" title="' . $categoryName . '">' . $categoryName . '</a></li>';
				}
			}
		}
		$content .= '
				</ul>
			  </div>
			</div> ';

		$qrySelCategory = $this->db->query("SELECT * FROM tbl_categories WHERE isActive='y' AND typeSubting='0'  AND languageId = " . $_SESSION["lId"] . " ORDER BY RAND() LIMIT 0,10");

		if (mysql_num_rows($qrySelCategory) > 0) {
			$content .= '<span class="cate-none">';
			while ($fetchValues = mysql_fetch_array($qrySelCategory)) {
				$categoryName = $this->db->filtering($fetchValues["categoryName"], 'output', 'string', '');
				$catId = getCategoryid($categoryName);
				$content .= ' - <a href="' . SITE_URL . 'c/' . urlencode(str_replace('/', ' ', $categoryName)) . '/" title="' . $categoryName . '">' . $categoryName . '</a>';
			}
			$content .= '</span>';
		}
		$content .= '</div>
        	</div>
        <div class="main_wrapper">';
		$logo = getTableValue($this->db, " tbl_settings", "", "img", 0);
		$content .= '<div class="logo"><div class="column-4"><a href="' . SITE_URL . '"><img src="' . SITE_UPD . 'logo/150x60/' . $logo . '" /></a></div></div>
            <div class="mobile_toggle"><a href="#"><img src="' . SITE_IMG . 'arrow2.png" /></a></div>
            <div id="mobile_toggle">
            	<ul>
                	<li>
						<div class="mobile_search">
							<form action="' . SITE_URL . 'search" method="post">
								<input type="text" id="search" name="search" placeholder="검색..."/>
								<input type="submit" value="" name="btn_search" style="display:none;" />
							</form>
						</div>
					</li>
                    <li><a href="' . SITE_URL . '">' . HOT . '</a></li>';
		if ($module == 'link') {
			$linkSelected = $SesscatselectId;
			$SesscatselectId = "";
		}
		if (isset($SesscatselectId) && $SesscatselectId != "") {
			$categoryName = getTableValue($this->db, "tbl_categories", "id='" . $SesscatselectId . "'", "categoryName", 0);
			$catId = getCategoryid($categoryName);
			$content .= '
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/hot/">' . HOT . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/new/">' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/rising/">' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/contraversial/">' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/top/">' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/gilded/">' . GILDED . '</a></li>';
		} elseif (isset($linkSelected) && $linkSelected != "") {
			$content .= '
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/hot/">' . HOT . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/new/">' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/rising/">' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/contraversial/">' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/top/">' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/gilded/">' . GILDED . '</a></li>';
		} else {
			$content .= '
						<li><a href="' . SITE_URL . 'new/">' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'rising/">' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'contraversial/">' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'top/">' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'gilded/">' . GILDED . '</a></li>';
		}
		$content .= (isset($_SESSION["sessUserId"]) && $_SESSION["sessUserId"] > 0
			? '<li><a href="' . SITE_URL . 'logout">' . LOGOUT . '</a></li>'
			: '<li><a href="' . SITE_URL . '">' . LOGIN . '</a></li>
						  <li><a href="' . SITE_URL . 'signup">' . SIGNUP . '</a></li>') . '
                </ul>
            </div>
            <div class="nav column-11">
            	<ul>';
		if ($module == 'home' || $module == 'contraversial' || $module == 'top' || $module == 'new' || $module == 'gilded' || $module == 'rising' || $module == 'login' || $module == "detail" || $module == "addpost" || $module == "comments" || $module == "search" || $module == "category" || $module == "multireddit" || $module == "fpass" || $module == "link") {
			if (isset($SesscatselectId) && $SesscatselectId != "") {
				$categoryName = getTableValue($this->db, "tbl_categories", "id='" . $SesscatselectId . "'", "categoryName", 0);
				$catId = getCategoryid($categoryName);
				$content .= '
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/hot/" ' . (($mod == 'hot' || $mod == '') ? 'class="active"' : '') . '>' . HOT . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/new/" ' . ($mod == 'new' ? 'class="active"' : '') . '>' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/rising/" ' . ($mod == 'rising' ? 'class="active"' : '') . '>' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/contraversial/" ' . ($mod == 'contraversial' ? 'class="active"' : '') . '>' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/top/" ' . ($mod == 'top' ? 'class="active"' : '') . '>' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'c/' . $categoryName . '/gilded/" ' . ($mod == 'gilded' ? 'class="active"' : '') . '>' . GILDED . '</a></li>';
			} else if (isset($linkSelected) && $linkSelected != "") {
				$content .= '
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/hot/" ' . (($mod == 'hot' || $mod == '') ? 'class="active"' : '') . '>' . HOT . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/new/" ' . ($mod == 'new' ? 'class="active"' : '') . '>' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/rising/" ' . ($mod == 'rising' ? 'class="active"' : '') . '>' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/contraversial/" ' . ($mod == 'contraversial' ? 'class="active"' : '') . '>' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/top/" ' . ($mod == 'top' ? 'class="active"' : '') . '>' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'domain/' . $linkSelected . '/gilded/" ' . ($mod == 'gilded' ? 'class="active"' : '') . '>' . GILDED . '</a></li>';
			} else {
				$content .=
					'<li><a href="' . SITE_URL . '" ' . ($module == '' || $module == 'home' ? 'class="active"' : '') . '>' . HOT . '</a></li>
						<li><a href="' . SITE_URL . 'new/" ' . ($module == 'new' ? 'class="active"' : '') . '>' . NEWPOST . '</a></li>
						<li><a href="' . SITE_URL . 'rising/" ' . ($module == 'rising' ? 'class="active"' : '') . '>' . RISING . '</a></li>
						<li><a href="' . SITE_URL . 'contraversial/" ' . ($module == 'contraversial' ? 'class="active"' : '') . '>' . CONTROVERSIAL . '</a></li>
						<li><a href="' . SITE_URL . 'top/" ' . ($module == 'top' ? 'class="active"' : '') . '>' . TOP . '</a></li>
						<li><a href="' . SITE_URL . 'gilded/" ' . ($module == 'gilded' ? 'class="active"' : '') . '>' . GILDED . '</a></li>';
			}
		} else if ($module == "user") {
			$user_name = isset($_REQUEST['user']) ? $_REQUEST['user'] : $this->sessUsername;
			$userIdA = getTableValue($this->db, "tbl_users", "username='" . $user_name . "'", "id", 0);
			$content .= '<li><a href="' . SITE_URL . 'user/' . $user_name . '/overview/" ' . ($type == '' || $type == 'overview' ? 'class="active"' : '') . '>' . OVERVIEW . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/submitted/" ' . ($type == '' || $type == 'submitted' ? 'class="active"' : '') . '>' . SUBMITTED . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/comments/" ' . ($type == '' || $type == 'comments' ? 'class="active"' : '') . '>' . COMMENTS . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/liked/" ' . ($type == '' || $type == 'liked' ? 'class="active"' : '') . '>' . LIKED . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/disliked/" ' . ($type == '' || $type == 'disliked' ? 'class="active"' : '') . '>' . DISLIKED . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/hidden/" ' . ($type == '' || $type == 'hidden' ? 'class="active"' : '') . '>' . HIDDEN . '</a></li>
							   <li><a href="' . SITE_URL . 'user/' . $user_name . '/saved/" ' . ($type == '' || $type == 'saved' ? 'class="active"' : '') . '>' . SAVED . '</a></li>';
			if (($this->sessUserId > 0) AND ($userIdA == $this->sessUserId)) {
				$content .= '
						       <li><a href="' . SITE_URL . 'user/' . $user_name . '/history/" ' . ($type == '' || $type == 'history' ? 'class="active"' : '') . '>' . HISTORY . '</a></li>';
			}
		} else if ($module == "message") {
			$content .=
				'<li><a href="' . SITE_URL . 'message/compose/" ' . ($type == '' || $type == 'compose' ? 'class="active"' : '') . '>' . COMPOSE . '</a></li>
				<li><a href="' . SITE_URL . 'message/inbox/" ' . ($type == '' || $type == 'inbox' ? 'class="active"' : '') . '>' . INBOX . '</a></li>
				<li><a href="' . SITE_URL . 'message/sent/" ' . ($type == '' || $type == 'sent' ? 'class="active"' : '') . '>' . SENT . '</a></li>
				<li><a href="' . SITE_URL . 'message/deleted/" ' . ($type == '' || $type == 'deleted' ? 'class="active"' : '') . '>' . DELETED . '</a></li>';
		} else if ($module == "prefer") {
			$content .=
				'<li><a href="' . SITE_URL . 'prefer/password/" ' . ($type == '' || $type == 'password' ? 'class="active"' : '') . '>' . PASSWORD . '</a></li>
				<li><a href="' . SITE_URL . 'prefer/delete/" ' . ($type == '' || $type == 'delete' ? 'class="active"' : '') . '>' . DELETE . '</a></li>
				<li><a href="' . SITE_URL . 'prefer/friend/" ' . ($type == '' || $type == 'friend' ? 'class="active"' : '') . '>' . FRIEND . '</a></li>
				<li><a href="' . SITE_URL . 'prefer/create_advertisement/" ' . ($type == '' || $type == 'create_advertisement' ? 'class="active"' : '') . '>' . CREATEADVERTISMENT . '</a></li>';
		}

		$content .= (isset($_SESSION["sessUserId"]) && $_SESSION["sessUserId"] > 0 ? '' : '<li><a href="' . SITE_URL . 'login" ' . ($module == 'login' ? 'class="active"' : '') . '>' . LOGIN . '</a></li>') . '</ul>
        </div>
        <div class="top_search column-3">
			<form action="' . SITE_URL . 'search" method="post">
				<input type="text" id="search" name="search" placeholder="검색..."/>
				<input type="submit" value="" name="btn_search" style="display:none;" />
			</form>
		</div>
    </div>
    <div class="clearfix"></div>
    </div>';

		return $content;

	}

	public function footerPanel() {
		$content = NULL;
		$qrysel = $this->db->select("tbl_content", "*", "isActive='y'", '', '', 0);
		$NoOfPages = mysql_num_rows($qrysel);
		$halfpage = (int) ($NoOfPages / 2);
		$content = '<a href="#" class="scrollup">Scroll</a><div class="footer-section">
    	<div class="main_wrapper">
        	<div class="column-3">
            	<ul class="about">
                <h3>' . ABOUTUS . '</h3>';
		$qrysel = $this->db->query("select pId,pageName from tbl_content where isActive='y' limit 0," . $halfpage);
		if (mysql_num_rows($qrysel) > 0) {
			while ($qryres = mysql_fetch_array($qrysel)) {
				$pId = $this->db->filtering($qryres["pId"], 'output', 'int', '');
				$pageName = $this->db->filtering($qryres["pageName"], 'output', 'string', '');
				$content .= '<li><a href="' . SITE_URL . 'pages/' . $pId . '/' . urlencode($pageName) . '" title="' . $pageName . '">' . $pageName . '</a></li>';
			}
		}

		$content .= '</ul>
            </div>
            <div class="column-3">
            	<ul class="about">
                <h3>' . HELP . '</h3>';
		$qrysel = $this->db->query("select pId,pageName from tbl_content where isActive='y' limit " . $halfpage . "," . $NoOfPages);

		if (mysql_num_rows($qrysel) > 0) {
			while ($qryres = mysql_fetch_array($qrysel)) {
				$pId = $this->db->filtering($qryres["pId"], 'output', 'int', '');
				$pageName = $this->db->filtering($qryres["pageName"], 'output', 'string', 'ucwords');
				$content .= '<li><a href="' . SITE_URL . 'pages/' . $pId . '/' . urlencode($pageName) . '" title="' . $pageName . '">' . $pageName . '</a></li>';
			}
		}
		$content .= '</ul>
            </div>
			<div class="column-3 languagelist">';
		$currentUrl = selfURL();

		$content .= selectLanguage($currentUrl);
		$content .= '</div>
           <div class="column-6">
            	<h3>' . FOLLOWUS . '</h3>
                <ul class="follows">
                    <li><a href="https://plus.google.com/112633711767247619787" title="Google+" target="_blank"><img src="' . SITE_IMG . 'gmail_icon.png" /></a></li>
                    <li><a href="https://www.facebook.com/vmodz1" title="Facebook" target="_blank"><img src="' . SITE_IMG . 'fb_icon.png" /></a></li>
                    <li><a href="https://www.twitter.com/upodot" title="Twitter" target="_blank"><img src="' . SITE_IMG . 'tweet_icon.png" /></a></li>
					<li><a href="http://www.upodot.com" target="_blank" title="Powered by NCrypted ' . SITE_NM . '"><img src="' . SITE_IMG . 'upodot_favicon.png" /></a></li>
                    <li class="nct_logo"><a href="http://www.upodot.com/" target="_self" title="Ranking News Community"><img src="' . SITE_IMG . 'logo.png" alt="Ranking News Community" /></a></li>
                </ul>
                <div class="copyright_txt">
				&copy; Copyright 2016 upodot.com <a href="http://www.upodot.com">조작없는 랭킹 뉴스</a> All Rights Reserved<br />
				조작없는 랭킹 뉴스 커뮤니티 <a href="http://www.upodot.com">upodot.com</a>
				</div>
            </div>
        </div>
    </div>';

		return $content;
	}

	public function leftPanel() {
		$content = NULL;
		$content = 'left content';
		return $content;
	}

	public function getsahreform($postid) {
		$content = NULL;
		$content .= '<div class="submit_new_post">
            			<form method="post" name="share" id="share" class="filedarea">
						<div class="post_box">

							' . $this->fields->textBox(array("label" => SENDTHISLINKTO . MEND_SIGN, "name" => "linkuser", "extraAtt" => 'placeholder="' . EMAIL . '"', "class" => "required email")) . '

							<br>

							<label id="lbl_msg"></label>

							' . $this->fields->hidden(array("name" => "post_id", "value" => $postid)) . '

							' . $this->fields->hidden(array("name" => "action", "value" => "sharepost")) . '



							' . $this->fields->textBox(array("label" => NAME, "name" => "name", "value" => $this->sessUsername, "extraAtt" => "readonly='readonly'")) . '

							<br>

							' . $this->fields->textBox(array("label" => EMAIL, "name" => "email", "extraAtt" => 'placeholder="' . YOUREMAIL . '"')) . '

							<br>

							' . $this->fields->textarea(array("label" => MESSAGE, "name" => "message")) . '

							<br>

							<p>

									<label for="Captcha" class="cap">' . CAPTCHA . ':</label>

								</p>

									<span class="cpt">



					<img id="imgCaptcha" src="' . SITE_INC . 'capcha/random.php" height="25" alt="Captcha Code" border="0" class="fl"/>

					<a href="javascript:void(0)" onclick="document.getElementById(\'imgCaptcha\').src=\'' . SITE_INC . 'capcha/random.php?\'+Math.random();$(\'#captcha\').focus();$(\'#captcha\').val(\'\');" id="change-image" ><img id="changeCaptcha" src="' . SITE_IMG . 'captcha-ref.jpg" alt="Captcha Refresh" border="0" class="fl" /></a>

				</span><br /><br /><div class="clear"></div><label>&nbsp;</label>

				<input name="captcha" id="captcha" type="text" class="sizeone" autocomplete="off">

				<label id="lbl_msg_captcha" class="error"></label>

						</div>



						<br>

							' . $this->fields->button(array("name" => "shareAddform", "type" => "button", "class" => "btn-share", "value" => SHARE, "extraAtt" => 'onclick="submitsharefun(\'' . $postid . '\')"')) . '

						</form>

					</div>';

		return $content;

	}

	public function spoceredlisting() {

		$content = NULL;

		$sqlSponceredList = $this->db->query("SELECT

					tbl_post.*, tbl_categories.categoryName,tbl_users.username

					FROM  tbl_post

					inner JOIN tbl_categories ON (tbl_categories.id = tbl_post.catId)

					inner JOIN tbl_users ON (tbl_post.uid = tbl_users.id)

					WHERE tbl_post.isActive='y' AND isSponcer='y'

					ORDER BY tbl_post.id DESC");

		if (mysql_num_rows($sqlSponceredList) > 0) {

			while ($fetchValues = mysql_fetch_array($sqlSponceredList)) {

				$content .= postlisthtml($fetchValues, false);

			}

		} else {

			$content .= '

					<li><img src="' . SITE_IMG . 'sbanner.jpg" /></li>

				';

		}

		return $content;

	}
}
?>