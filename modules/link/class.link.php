<?php
class Link {
	private $id;
	private $db;
	private $fields;
	private $module;
	function __construct($module, $link="",$searchType="") {
		global $fields, $db,$sessUserId,$sessUsername,$type;
		$this->db = $db;
		$this->module = $module;
		$this->link = $link;
		$this->fields = $fields;
		$this->sessUserId = $sessUserId;
		$this->sessUsername = $sessUsername;
		$this->type = $type;
		$this->searchType = $searchType;
	}
	public function linkContent($link,$pageNo,$mod) {
		$content = NULL;

		//		<div class="inner-title">'.getTableValue($this->db,'tbl_categories',"id='".$catId."'",'categoryName',0).'</div>
		$content.='<div class="adv_banner_1"></div>';
		if($mod=="contraversial" || $mod=="top")
		$content.='<label for="srchType" class="filterresults">Filter Results By:</label>
			<select name="searchType" onChange="return searchType(this.value,\''.$mod.'\');" class="filt">
				<option value="">All</option>
				<option value="hour/" '.($this->searchType=="hour"?"selected":"").' >This Hour</option>
				<option value="day/" '.($this->searchType=="day"?"selected":"").'>This Day</option>
				<option value="week/" '.($this->searchType=="week"?"selected":"").'>This Week</option>
				<option value="month/" '.($this->searchType=="month"?"selected":"").'>This Month</option>
				<option value="year/" '.($this->searchType=="year"?"selected":"").'>This Year</option>

			</select>';
		$content.='<div class="hot_listing">
			<ul id="container">';
			if($mod=="hot")
			{
				$content .= $this->postlistingHot($link,$pageNo);
			}
			else if($mod=="new")
			{
				$content .= $this->postlistingNew($link,$pageNo);
			}
			else if($mod=="rising")
			{
				$content .= $this->postlistingRising($link,$pageNo);
			}
			else if($mod=="contraversial")
			{
				$content .= $this->postlistingContraversial($link,$pageNo);
			}
			else if($mod=="top")
			{
				$content .= $this->postlistingTop($link,$pageNo);
			}
			else if($mod=="gilded")
			{
				$content.= $this->postlistingGilded($link,$pageNo);
			}
			else{
			  $content.= $this->postlisting($link,$pageNo);
			}
			//$content.= $this->postlisting($catId,$pageNo);
			$content.='</ul>
				</div>';

		return $content;
	}
	public function postlisting($link,$pageNo){
		$content= NULL;

		$selRes = "SELECT tbl_post.id,tbl_post.title,tbl_post.description,tbl_post.img,tbl_post.embeddcode,tbl_post.isimageUrl,tbl_post.url,tbl_post.type, tbl_categories.categoryName,tbl_users.username,tbl_post.createdDate,tbl_post.isSponcer FROM  tbl_post
			INNER JOIN tbl_categories ON (tbl_categories.id = tbl_post.catId)
			INNER JOIN tbl_users ON (tbl_post.uid = tbl_users.id)
			WHERE tbl_post.isActive='y' AND tbl_post.url like '%".$link."%' ORDER BY tbl_post.id DESC";
		$qSelRes 	= $this->db->query($selRes);
		$totalRows 	= mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
			$limit  	= $pager->limit;
			$page  		= $pager->page;
			$selRes 	= $selRes." limit $offset, $limit";
			$qSelRes 	= $this->db->query($selRes);
			if($totalRows>0)
			{
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes))
				{
					$content.= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
	}

	public function postlistingHot($link,$pageNo){
		$content = NULL;
		$CATcond='';
		$hidecond='';
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND A.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
					$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		$curcatcond = " and A.url like '%".$link."%'";
		$whereCondition = $hidecond.$CATcond.$curcatcond;
		$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, A.embeddcode, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' AND B.createdDate<'".date("Y-m-d 00:00:00")."' where A.isActive='y' ".$whereCondition." GROUP BY A.id ORDER BY totalVotes DESC";
		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  		= $pager->page;
			$selRes 	= $selRes." limit $offset, $limit";
			$qSelRes 	= $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
		}
		return $content;
	}

	public function postlistingNew($link,$pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND tbl_post.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {

				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {

						$hidecond .= " AND tbl_post.id !='".$HiddenItem["refId"]."'";

				}
			}
			else {
				$hidecond='';

			}
		}
		$curcatcond = " and tbl_post.url like '%".$link."%'";
		$whereCondition = $hidecond.$CATcond.$curcatcond;
		$selRes="SELECT tbl_post.*,tbl_categories.categoryName,tbl_users.username FROM tbl_post
		INNER JOIN tbl_categories ON(tbl_categories.id = tbl_post.catId)
		INNER JOIN tbl_users ON(tbl_users.id = tbl_post.uid)
		where tbl_post.isActive='y' ".$whereCondition." ORDER BY tbl_post.createdDate DESC";

		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  		= $pager->page;
			$selRes 	= $selRes." limit $offset, $limit";
			$qSelRes 	= $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
		}
		return $content;
		}

	public function postlistingContraversial($link,$pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		$searchCond = '';
		if($this->searchType=="hour")
		{
			$searchCond	=" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";
		}
		else if($this->searchType=="day")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 24 HOUR)";
		}
		else if($this->searchType=="week")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 168 HOUR)";
		}
		else if($this->searchType=="month")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 720 HOUR)";
		}
		else if($this->searchType=="year")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 9760 HOUR)";
		}
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND A.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {

				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {

						$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		$curcatcond = " and A.url like '%".$link."%'";
		$whereCondition = $hidecond.$CATcond.$curcatcond.$searchCond;
		$selRes="SELECT A.*, COUNT(tbl_comment.postid) as commentcount, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, A.embeddcode, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) LEFT JOIN tbl_comment ON (A.id=tbl_comment.postid) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND B.refType ='p' AND voteType ='u' where A.isActive='y' ".$whereCondition." GROUP BY A.id order by commentcount desc";

		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  = $pager->page;
			$selRes = $selRes." limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
	}

	public function postlistingGilded($link,$pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND A.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {
				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {
						$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';

			}
		}
		$curcatcond = " and A.url like '%".$link."%'";
		$whereCondition = $hidecond.$CATcond.$curcatcond;
		$selRes="SELECT A.*, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND refType ='p' AND voteType ='u' where A.isActive='y' AND tbl_users.isMember='y' ".$whereCondition." GROUP BY A.id ORDER BY A.id DESC";

		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  = $pager->page;
			$selRes = $selRes." limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
		}

	public function postlistingTop($link,$pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		$searchCond = '';
		if($this->searchType=="hour")
		{
			$searchCond	=" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 1 HOUR)";
		}
		else if($this->searchType=="day")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 24 HOUR)";
		}
		else if($this->searchType=="week")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 168 HOUR)";
		}
		else if($this->searchType=="month")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 720 HOUR)";
		}
		else if($this->searchType=="year")
		{
			$searchCond =" AND tbl_comment.createdDate >= DATE_SUB(CURDATE(), INTERVAL 9760 HOUR)";
		}
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND tbl_post.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {

				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {

						$hidecond .= " AND tbl_post.id !='".$HiddenItem["refId"]."'";
				}
			}
			else {
				$hidecond='';

			}
		}
		$curcatcond = " and tbl_post.url like '%".$link."%'";
		$whereCondition = $hidecond.$CATcond.$curcatcond.$searchCond;
		$selRes = "SELECT tbl_post.*,tbl_categories.categoryName,tbl_users.username,B.voteType,tbl_comment.createdDate as crDate, COUNT(B.id) AS totalVotes FROM tbl_post
		INNER JOIN tbl_categories ON(tbl_categories.id = tbl_post.catId) LEFT JOIN tbl_comment ON (tbl_post.id=tbl_comment.postid)
		INNER JOIN tbl_users ON(tbl_users.id = tbl_post.uid) LEFT JOIN `tbl_votes` AS B ON tbl_post.id=B.refId AND  voteType ='u'
		where tbl_post.isActive='y' ".$whereCondition." GROUP BY tbl_post.id ORDER BY totalVotes DESC";

		$qSelRes 	= $this->db->query($selRes);
		$totalRows 	= mysql_num_rows($qSelRes);
		$pager  	= getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  = $pager->page;
			$selRes = $selRes." limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
		}

	public function postlistingRising($link,$pageNo){

		$content = NULL;
		$CATcond='';
		$hidecond='';
		if($this->sessUserId >0) {
			$unSubStr = getTableValue($this->db, 'tbl_users', "id ='".$this->sessUserId."'", 'unsubscribeCat');
			$CATcond =($unSubStr!='')?'AND A.catId NOT IN('.$unSubStr.')':'';
			$sqlGetHidden = $this->db->select("tbl_hide","refId","uId='$this->sessUserId' AND refType='p'",'','',0);
			if ( mysql_num_rows($sqlGetHidden) > 0 ) {

				while($HiddenItem = mysql_fetch_assoc($sqlGetHidden)) {

						$hidecond .= " AND A.id !='".$HiddenItem["refId"]."'";

				}
			}
			else {
				$hidecond='';
			}
		}
		else{
			$hidecond='';
		}
		$curcatcond = " and A.url like '%".$link."%'	";
		$whereCondition = $hidecond.$CATcond.$curcatcond;
		$selRes="SELECT A.*, COUNT(tbl_comment.postid) as commentcount, B.refId, B.refType, B.voteType, tbl_categories.categoryName, tbl_users.username, A.embeddcode, COUNT(B.id) AS totalVotes FROM tbl_post AS A INNER JOIN tbl_categories ON (tbl_categories.id = A.catId) LEFT JOIN tbl_comment ON (A.id=tbl_comment.postid) INNER JOIN tbl_users ON (A.uid = tbl_users.id) LEFT JOIN `tbl_votes` AS B ON A.id=B.refId AND B.refType ='p' AND voteType ='u' where A.isActive='y' ".$whereCondition." GROUP BY A.id order by commentcount desc";

		$qSelRes = $this->db->query($selRes);
		$totalRows = mysql_num_rows($qSelRes);
		$pager  = getPagerData($totalRows, LIMIT, $pageNo);
		if($pageNo<=$pager->numPages){
			$offset = $pager->offset;
			if($offset<0) $offset=0;
				 $limit  = $pager->limit;

			$page  = $pager->page;
			$selRes = $selRes." limit $offset, $limit";
			$qSelRes = $this->db->query($selRes);
			if ( $totalRows > 0 ) {
				$i=$offset+1;
				while($fetchValues = mysql_fetch_array($qSelRes)) {
					$content .= postlisthtml($fetchValues,$i);
					$i++;
				}
			}
			else
			{
				$content .= "<li>".RNF."</li>";
			}
		}
		return $content;
	}
}
?>